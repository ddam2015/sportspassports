<?php
/** 
 * Badge/Award reconciler
 */
//get achievements
function g365_achievement_reconciler( $target_lists = null, $to_limit = false ){
  //if we don't have an object, get out
  if( $target_lists === null || (!is_object($target_lists) && !is_object(json_decode($target_lists))) ) return 'Need object to start.';
  if( gettype($target_lists) === 'string' ) $target_lists = json_decode($target_lists);

  //global db vars
  global $wpdb;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_teams = $wpdb->g365_teams;
	$wpdb_rosters = $wpdb->g365_rosters;
	$wpdb_coaches = $wpdb->g365_coaches;
	$wpdb_games = $wpdb->g365_games;
  $wpdb_awards = $wpdb->g365_awards;
  $wpdb_awards_ref = $wpdb->g365_award_refs;
  //badge_type constants for database
  $process_key = array(
    'pl' => 1,
    'co' => 2,
    'ct' => 3,
    'og' => 4,
    'aw' => 5
  );
  //global vars
  $process_types_key = array(); //array of badge_type(s)
  $progression_results = array(
    'aw_check' => array(), //awards data check
    'us_check' => array(), //user data check
    'us_calc' => array(), //user calculations
    'as_check' => array()  //assignment results
  );
  
  //get the types to process
  foreach( $target_lists as $key => $ids ) {
    //if we don't have a list of ids then exit processing
    if( gettype($ids) !== 'array' ) return "Need id array.";
    //if we are doing a full clear process that differently
    if( $key === 'clearall') {
      //make sure there are no other keys
      $target_list_keys = array_keys((array)$target_lists);
      if( in_array( array('pl', 'co', 'ct', 'og', 'aw', 'aw_tm'), $target_list_keys) ) return "Cannot process more than clearall.";
      //if there is a full recalculation, we will include all types from $process_key
      $progression_results['us_check'] = $progression_results['us_calc'] = $progression_results['as_check'] = array(
        'pl' => array(),
        'co' => array(),
        'ct' => array(),
        'og' => array(),
        'aw' => array()
      );
      $process_types_key = array(1,2,3,4,5);
      break;
    }
    $process_types_key[] = $process_key[$key];
    $progression_results['us_check'][$key] = array();
    $progression_results['us_calc'][$key] = array();
    $progression_results['as_check'][$key] = array();
    
    switch( $key ) {
      case 'pl':
        //I haven't worked out how to optimize these queries but it seems like there is probably something that can be done
        $prog_query = $wpdb->get_results( "SELECT st.*, ev.name, ev.eventtime, ev.type AS ev_type, ev.enabled AS ev_enabled FROM $wpdb_stats AS st LEFT JOIN $wpdb_events AS ev ON st.event=ev.id WHERE st.player IN (" . implode(',', $ids) . ");" );
        //if we pulled an empty set, set an error message
        if( $prog_query === false || empty($prog_query) ) $progression_results['us_check'][$key] = (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database pull error.') : 'Data pull error.');
        //if we have good data, then organize by player
        foreach( $prog_query as $dex => $data ) {
          //error is thrown if you try to reference the player id directy from the dataset..
          $pl_id = $data->player;
					//make sure that the stats isn't a string
					$data->stats = json_decode( $data->stats );
          //make sure there is an array to write to
          if( !isset($progression_results['us_check'][$key][$pl_id]) ) $progression_results['us_check'][$key][$pl_id] = array();
          //reorganzie data by the id of the data being searched
          $progression_results['us_check'][$key][$pl_id][] = $data;
        }
        break;
      case 'co':
        $progression_results['us_check'][$key] = "SELECT ros.*, ev.name, ev.eventtime FROM $wpdb_rosters AS ros LEFT JOIN $wpdb_events AS ev ON ros.event=ev.id WHERE ros.coach IN (" . implode(',', $ids) . ");";
        break;
      case 'ct':
        $progression_results['us_check'][$key] = "SELECT ros.*, ev.name, ev.eventtime FROM $wpdb_rosters AS ros LEFT JOIN $wpdb_events AS ev ON ros.event=ev.id WHERE ros.team IN (" . implode(',', $ids) . ");";
        break;
      case 'og':
        $progression_results['us_check'][$key] = "SELECT ros.*, ev.name, ev.eventtime FROM $wpdb_rosters AS ros LEFT JOIN $wpdb_events AS ev ON ros.event=ev.id WHERE ros.org IN (" . implode(',', $ids) . ");";
        break;
      case 'aw':
        $progression_results['us_check'][$key] = "SELECT aw.*, ev.name, ev.eventtime FROM $wpdb_awards_ref AS aw LEFT JOIN $wpdb_events AS ev ON aw.event=ev.id WHERE aw.player IN (" . implode(',', $ids) . ");";
        break;
      case 'aw_tm':
        $progression_results['us_check'][$key] = "SELECT aw.*, ev.name, ev.eventtime FROM $wpdb_awards_ref AS aw LEFT JOIN $wpdb_events AS ev ON aw.event=ev.id WHERE aw.team IN (" . implode(',', $ids) . ");";
        break;
    }
  }
  //all awards for these progessions
  $aw_progression = $wpdb->get_results("SELECT aw.id, aw.badge_type, aw.badge_range, ev.id AS ev_id, ev.dates, aw.name, aw.progression FROM $wpdb_awards AS aw LEFT JOIN $wpdb_events AS ev ON ev.id = aw.badge_range WHERE aw.badge_type IN ( " . implode($process_types_key) . " )");
  //if we don't have any progression, exit
  if( empty($aw_progression) ) return 'Cannot find any progressions for this type.';
  //loop through the progressions, parse and organize
  foreach( $aw_progression as $dex => $awards_progression ) {
    //get the proper alpha key because we only store the number in the database
    $key = array_search($awards_progression->badge_type, $process_key);
    //if we don't have an array to put the progressions in, make one
    if( !isset($progression_results['aw_check'][$key]) ) $progression_results['aw_check'][$key] = array();
    //decode the json and write it to the progression before we assign it to to array
    $awards_progression->progression = json_decode($awards_progression->progression);
    //figure out the timestamp range
    switch( $awards_progression->badge_range ) {
      case null:
        //null or blank means total by year
        $awards_progression->badge_range = 0;
        break;
      case '-1':
        //'-1' means lifetime
        $awards_progression->badge_range = 1;
        break;
      default:
				//any other number means event
				$awards_progression->badge_range = 2;
				//make time for when this happened
				$awards_progression->eventtime = g365_build_dates($awards_progression->dates, 5);
        break;
    }
		//we don't need to carry around the event dates
		unset( $awards_progression->dates );
		//we don't need to carry around event id
		if( !is_numeric($awards_progression->ev_id) ) unset( $awards_progression->ev_id );
    //assign award to the main object
    $progression_results['aw_check'][$key][] = $awards_progression;
  }
  //at this point we should have all the progrssions and user data that we need to process to badges from scratch
  //start with what ever data was passed in, top level key is badge_type
  foreach( $target_lists as $key => $ids ) {
    //then we will evaluate all ids individually
    foreach( $ids as $id ) {
			//make a array element for each player so they can get awards
			$progression_results['us_calc'][$key][$id] = [];
      //once we have a single id isolated, now we run the progressions for the type
      foreach( $progression_results['aw_check'][$key] as $prog_id => $prog_data ) {
				//figure out of you have to make dates
				if( !isset($progression_results['us_calc'][$key][$id][$prog_data->badge_range]) ) {
					// put all stats for a particiular player into a specific array
					$progression_results['us_calc'][$key][$id][$prog_data->badge_range] = array();
					//which type of award is it?
					switch ( $prog_data->badge_range ) {
						case 0: //by year
							foreach( $progression_results['us_check'][$key][$id] as $this_dex => $pl_stats ) {
								//label for the player stat, when it happened
								$label = (date('Y', strtotime($pl_stats->eventtime)) - (( intval(date('n', strtotime($pl_stats->eventtime))) < 9 ) ? 1 : 0 ));
								//if this wasn't created, create it
								if( !isset($progression_results['us_calc'][$key][$id][$prog_data->badge_range][$label]) ) $progression_results['us_calc'][$key][$id][$prog_data->badge_range][$label] = [];
								//push the player data now that we have to right handle
								array_push($progression_results['us_calc'][$key][$id][$prog_data->badge_range][$label], $pl_stats);
							}
							break;
						case 1: //by lifetime
							//create the lifetime holder
							$progression_results['us_calc'][$key][$id][$prog_data->badge_range][0] = [];
							foreach( $progression_results['us_check'][$key][$id] as $this_dex => $pl_stats ) {
								//push the player data now that we have to right handle
								array_push($progression_results['us_calc'][$key][$id][$prog_data->badge_range][0], $pl_stats);
							}
							break;
						case 2: //by event
							foreach( $progression_results['us_check'][$key][$id] as $this_dex => $pl_stats ) {
								//label for the player stat, when it happened
								$label = $pl_stats->event;
								//if this wasn't created, create it
								if( !isset($progression_results['us_calc'][$key][$id][$prog_data->badge_range][$label]) ) $progression_results['us_calc'][$key][$id][$prog_data->badge_range][$label] = [];
								//push the player data now that we have to right handle
								array_push($progression_results['us_calc'][$key][$id][$prog_data->badge_range][$label], $pl_stats);
							}
							break;
					}
				}
				$prog_data1 = [];
				//if we have already made this badge_type orginized player data, please proceed with looping through the data
				if( isset($progression_results['us_calc'][$key][$id][$prog_data->badge_range]) ) {
					//create array for awards if there isn't any
					if( !isset($progression_results['as_check'][$key][$id]) ) $progression_results['as_check'][$key][$id] = [];
					//add an array for started award progressions
					$progression_results['as_check'][$key][$id][$prog_data->id] = ['calc' => [ 'year' => [],  'pts' => [], 'level' => [], 'lvl' => [] ] ];
					//switch based on the badge_range
					switch( $prog_data->badge_range ) {
						case 0: //by year
							//process each id against the progressions we've queried for
							foreach( $prog_data->progression as $prog_key => $prog_item ) {
								//process each query in the progression stack that we've pulled;
								switch( $prog_key ) {
									case 'imgs':
										break;
									case 'stat_pts': //player query
										//per season, add all the points for that event 
										foreach( $progression_results['us_calc'][$key][$id][$prog_data->badge_range] as $year_dex => $user_data ) {
											array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['year'], count($user_data));
											//write the zero values for each event
											$prog_level = 0;
											$prog_pts = 0;
											//make scores for each year
											foreach( $user_data as $the_dex => $user_stats ) {
												//if it doesn't have a stats component, skip it
												if( !isset($user_stats->stats->pts) ) continue;
												//add it to the total
												$prog_pts += intval($user_stats->stats->pts);
												array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['pts'], $prog_pts);
											}
											//if we are calculating that way and they don't meet the minimum requirement
											if( $to_limit ) {
												//if they have pts, and the pts are above the lowest level, evaluate the level of achievement
												if( $prog_pts > 0 && $prog_pts >= $prog_item['0'] ) {
													//if the points are above or equal to the threshholds, adjust the achievement level
													foreach( $prog_item as $dex => $prog_levels ) {
														array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['level'], $prog_levels);
														if( $prog_pts >= $prog_levels  ) {
															//didn't want to set 0 in that progression column..
															$prog_level = $dex;
															array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['lvl'], $prog_level);
														} else {
															break;
														}
													}
													$progression_results['as_check'][$key][$id][$prog_data->id]['result'][$year_dex] = ( $wpdb->query( "INSERT INTO $wpdb_awards_ref (player, team, ranking, event, award, class, name, progression) VALUES ( " . $id . ", 0, 0, 0, " . $prog_data->id . ", " .  $year_dex . ", '" . $prog_data->name . "', " . $prog_level . " ) ON DUPLICATE KEY UPDATE progression=VALUES(progression);" ) === false ) ? g365_output_db_error('Player update error.') : "Updated successfully. => " . $prog_level . " : " . $prog_data->name;
												}
											} else {
												//if they don't explictily ask for it, give them back an array with the exact the numbers for each award
												//if they have pts, evaluate the level of achievement
												if( $prog_pts > 0 ) {
													//if the points are above or equal to zero, send the totals
													$progression_results['as_check'][$key][$id][$prog_data->id]['result'] = [
														'prog_pts'		=> $prog_pts,
														'prog_total'	=> end($prog_item),
														'aw_id'				=> $prog_data->id,
														'aw_name'			=> $prog_data->name,
														'year_dex'		=> $year_dex
													];
												}
											}
										}
										break;
								}
							}
							break;
						case 1: //by lifetime
							//process each id against the progressions we've queried for
							foreach( $prog_data->progression as $prog_key => $prog_item ) {
								//process each query in the progression stack that we've pulled
								switch( $prog_key ) {
									case 'imgs':
										break;
									case 'stat_pts': //player query
										//this progression is super simple...add all the player points
										$prog_level = 0;
										$prog_pts = 0;
										//lifetime, add all the player points, start with individual users 
										foreach( $progression_results['us_calc'][$key][$id][$prog_data->badge_range] as $this_dex => $user_data ) {
											//then break it down by individual score made
											foreach( $user_data as $ev_id_dex => $indi_records ) {
												//if it has a points component
												if( !isset($indi_records->stats->pts) ) continue;
												//add it to the total
												$prog_pts += intval($indi_records->stats->pts);
												array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['pts'], $prog_pts);
											}
										}
										//if we are calculating that way and they don't meet the minimum requirement
										if( $to_limit ) {
											//if they have pts, and the pts are above the lowest level, evaluate the level of achievement
											if( $prog_pts > 0 && $prog_pts >= $prog_item['0'] ) {
												//if the points are above or equal to the threshholds, adjust the achievement level
												foreach( $prog_item as $dex => $prog_levels ) {
													array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['level'], $prog_levels);
													if( $prog_pts >= $prog_levels  ) {
														//didn't want to set 0 in that progression column..
														$prog_level = $dex;
														array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['lvl'], $prog_level);
													} else {
														break;
													}
												}
												$progression_results['as_check'][$key][$id][$prog_data->id]['result'] = ( $wpdb->query( "INSERT INTO $wpdb_awards_ref (player, team, ranking, event, award, name, progression) VALUES ( " . $id . ", 0, 0, 0, " . $prog_data->id . ", '" . $prog_data->name . "', " . $prog_level . " ) ON DUPLICATE KEY UPDATE progression=VALUES(progression);" ) === false ) ? g365_output_db_error('Player update error.') : "Updated successfully. => " . $prog_level . " : " . $prog_data->name;
											}
										} else {
											//if they don't explictily ask for it, give them back an array with the exact the numbers for each award
											//if they have pts, evaluate the level of achievement
											if( $prog_pts > 0 ) {
												//if the points are above or equal to zero, send the totals
												$progression_results['as_check'][$key][$id][$prog_data->id]['result'] = [
													'prog_pts'		=> $prog_pts,
													'prog_total'	=> end($prog_item),
													'aw_id'				=> $prog_data->id,
													'aw_name'			=> $prog_data->name,
													'ev_id'				=> $ev_id
												];
											}
										}
										break;
								}
							}
							break;
						case 2: //by event
							//process each id against the progressions we've queried for
							foreach( $prog_data->progression as $prog_key => $prog_item ) {
								//process each query in the progression stack that we've pulled;
								switch( $prog_key ) {
									case 'imgs':
										break;
									case 'stat_pts': //player query
										//per event, add all the points for that event 
										foreach( $progression_results['us_calc'][$key][$id][$prog_data->badge_range] as $ev_id => $user_data ) {
											//then make sure it only for one event
											if( $prog_data->ev_id == $ev_id ) {
												//write the zero values for each event
												$prog_level = 0;
												$prog_pts = 0;
												//then break it down by individual score made
												foreach( $user_data as $ev_id_dex => $indi_records ) {
													//if it doesn't have a stats component, skip it
													if( !isset($indi_records->stats->pts) ) continue;
													//add it to the total
													$prog_pts += intval($indi_records->stats->pts);
													array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['pts'], $prog_pts);
												}
												//if we are calculating that way and they don't meet the minimum requirement
												if( $to_limit ) {
													//if they have pts, and the pts are above the lowest level, evaluate the level of achievement
													if( $prog_pts > 0 && $prog_pts >= $prog_item['0'] ) {
														//if the points are above or equal to the threshholds, adjust the achievement level
														foreach( $prog_item as $dex => $prog_levels ) {
															array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['level'], $prog_levels);
															if( $prog_pts >= $prog_levels  ) {
																//didn't want to set 0 in that progression column..
																$prog_level = $dex;
																array_push($progression_results['as_check'][$key][$id][$prog_data->id]['calc']['lvl'], $prog_level);
															} else {
																break;
															}
														}
														$progression_results['as_check'][$key][$id][$prog_data->id]['result'] = ( $wpdb->query( "INSERT INTO $wpdb_awards_ref (player, team, ranking, event, award, name, progression) VALUES ( " . $id . ", 0, 0, " . $ev_id . ", " . $prog_data->id . ", '" . $prog_data->name . "', " . $prog_level . " ) ON DUPLICATE KEY UPDATE progression=VALUES(progression);" ) === false ) ? g365_output_db_error('Player update error.') : "Updated successfully. => " . $prog_level . " : " . $prog_data->name;
													}
												} else {
													//if they don't explictily ask for it, give them back an array with the exact the numbers for each award
													//if they have pts, evaluate the level of achievement
													if( $prog_pts > 0 ) {
														//if the points are above or equal to zero, send the totals
														$progression_results['as_check'][$key][$id][$prog_data->id]['result'] = [
															'prog_pts'		=> $prog_pts,
															'prog_total'	=> end($prog_item),
															'aw_id'				=> $prog_data->id,
															'aw_name'			=> $prog_data->name,
															'ev_id'				=> $ev_id
														];
													}
												}
											}
										}
										break;
								}
							}
							break;
					}
				}
      }
    }
		return $progression_results['as_check'];
  }
}