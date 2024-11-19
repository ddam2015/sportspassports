<div id="teams" class="cell small-12 medium-12 large-12 options-wrapper">
  <div id="dialong_div"></div>
  <?php global $wp_query; $org_data = g365_get_org_profile( $wp_query->query_vars['org_name'] ); $org_rosters = cp_get_rosters(array('org_id' => $org_data->id, 'event_id' => 0), false, true); 
  if (isset($_GET['team_id'])) {
    $team_id = $_GET['team_id'];
    $year = $_GET['y'];    
  } else {
    echo "Missing roster id";
  }
  if( is_array($org_rosters) ) :
  $roster_array = g365_filter_unmatched_data($org_rosters[0], $team_id, 'team_id');// Only show matched team_id data
  $select_year = $_POST['year'];
  $available_stat_years = g365_year_end_date('event_time', most_recent_event(2));
  if(!isset($select_year)){
    $select_year = $year; // Set a default year to the lastest available year in dropdwon
  }
  ?>
  <div class="team_back_btn small-12 medium-12 large-12">
    <button class="team_back_btn buttonization"><a style="color:#ffffff;text-decoration:none;" href="<?php echo get_site_url().'/club/'.$wp_query->query_vars['org_name'].'/teams' ?>"> Back to team page </a></button>
  </div>
  <div>
    <form method="post" id="cp-year-selection-form" class="grid-x">
      <div class="small-12 large-3 small-padding-right" style="width: 200px">
        <select onchange="this.form.submit()" name="year" id="year" style="border-radius: 20px">
          <?php foreach($available_stat_years as $available_stat_year): ?>
            <option <?php if(isset($select_year) && $select_year == $available_stat_year){echo 'selected= "selected"';} ?> value="<?php echo $available_stat_year ?>"><?php echo g365_date_format($available_stat_year, 2)?> Season</option>
          <?php endforeach; ?>
        </select>
      </div>
    </form>
  </div>
  <?php foreach( $roster_array as $ros_dex => $ros_data ) : ?>
    <div class="cell small-12 club-teams-overview">
      <?php $team_name = str_replace('.', '', $ros_data->team_name); ?>
      <h1 style="text-align:center;margin-bottom:1px;"><?php echo g365_level_key($ros_data->team_level) . ((empty($team_name)) ? '' : ' ' . $team_name); ?></h1>
    <div class="ResponsiveWrapper">
      <h2 class=" small-padding-sides">Category Leaders</h2>
      <?php 
      /* Default top players from each stat types */
//        $default_pl_stats_old = slb_by_year($select_year, 3, $arg=array('level', 'stat_type'), $ros_data->org_id, $ros_data->team_id); echo "<pre>"; print_r($default_pl_stats_old); echo "</pre>";/*if-2*/
      $default_pl_stats = slb_by_year_query($stat_type, $select_year, 5, $ros_data->org_id, $ros_data->team_id);
      
//       print "<pre>";
//       print_r($default_pl_stats);
//       print "</pre>";
     ?>
    <div class="grid-x" style="font-size:12px">
      <?php if(!empty($default_pl_stats)):// && (!empty($default_pl_stats['stat_point']) && !empty($default_pl_stats['stat_rebound']) && !empty($default_pl_stats['stat_assist']) && !empty($default_pl_stats['stat_steal']) && !empty($default_pl_stats['stat_block'])) ):/*if-2a*/ ?>
      <?php foreach($default_pl_stats as $index => $default_pl_stat): $default_pl_stat = json_decode($default_pl_stat, true); //echo "<pre>"; print_r($default_pl_stat); echo "</pre>"; /*foreach-2a*/ ?>
      <div class="team_leaders small-6 medium-3 large-4">
        <div class="team-responsive-table">
          <table class="stats_customize" >
            <?php
              echo leaderboard_tb_form($index, $level);
              //foreach($default_pl_stat as $pl_stat):/*foreach-2b*/
              $player_nickname = $default_pl_stat['player_nickname']; 
              $player_id = $default_pl_stat['player_id'];
              $event_nickname = $default_pl_stat['event_nickname'];
              $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
              $player_profile = get_site_url().'/player/'.$player_nickname.'/stats/#stat'.str_replace('-','',g365_date_format($select_year, 2));
              if( !empty($default_pl_stats) ):
            ?>
            <tr>
              <td>
                <div class="small-padding-bottom">
                  <a class="flex" href="<?php echo $player_profile ?>" target="_blank">
                    <div class="small-padding-right">
                      <figure>
                        <div class="team-image-wrapper">
                          <img alt="<?php echo $default_pl_stat['player_name']; ?>" title="<?php echo $default_pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                        </div>
                      </figure>
                    </div>
                  </a>
                </div>
                <div>
                  <a class="flex" href="<?php echo $player_profile ?>" target="_blank"><div class="small-12 buttonization" style="font-size:14px;width:100%;line-height: 1;"><?php echo $default_pl_stat['player_name']; ?></div></a>
                </div>
              </td>
              <td>
                <h5><?php echo $default_pl_stat[$index]; ?></h5>
              </td>
            </tr>
            <?php endif; if(empty($default_pl_stat)): ?>
              <div><?php echo g365_message()['not_available']; ?></div>
            <?php endif; //endforeach;/*endforeach-2b*/ ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endforeach;/*endforeach-2a*/ ?>
      <?php endif;/*endif-2a*/ if(empty($default_pl_stats)  || (empty($default_pl_stats['stat_point']) && empty($default_pl_stats['stat_rebound']) && empty($default_pl_stats['stat_assist']) && empty($default_pl_stats['stat_steal']) && empty($default_pl_stats['stat_block'])) ):/*if-2b*/ ?>
        <p><?php echo g365_message()['g365_ev_ranking']; ?></p>
      <?php endif;/*endif-2b*/ ?>
    </div>
    <?php //endif;/*endif-2*/ ?>
    </div>
    </div>
    <div class="grid-container">
      <div class="grid-x small-padding-sides">
      <?php 
//             endforeach; else:/*endif-active*/ echo ('<div>'.g365_message()['not_available'].'</div>'); endif/*endif-active*/; 
            $team_rosters = g365_team_rosters($select_year, $team_id, $ros_data->org_id, 2);
          if(!empty($team_rosters)){
            echo "<h1 class='small-margin-top'>Event Rosters & Box Scores</h1>";
          }
          
        ?>
        <div class="orbit large-12 large-margin-bottom redeploy event-orbit" role="region" aria-label="Favorite Space Pictures" data-orbit  data-auto-play="false" style="" >
          <div class="orbit-wrapper">
            <div class="orbit-controls orbit-controls-event">
              <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
              <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
            </div>
            <ul class="orbit-container <?php echo $org_list->id ?>" style="height: 128px !important;"> <?php
            $counter = 0;
            foreach($team_rosters as $team_roster): ?>
            <?php 
                if($counter === 0){
                       echo '<li class="is-active orbit-slide cell"><figure class="orbit-figure"><div class="event-contain">';
                }  
                $event_pull= g365_get_event_data($team_roster->event_id, true);
//                 echo '<pre>'; print_r($event_pull ); '</pre>';
              ?>
                <a id="click<?php echo $team_roster->event_id; ?>"  class="event-results--subevent profile-title grid-y align-center align-middle no-border text-center" style="font-size: 13px; text-decoration: none; " onclick="displayTeam(event, '<?php echo $team_roster->event_id; ?>')">
                  
                  <img class="orbit-image drop-shadow fit-cover transition-2 hover-scale" src="<?php echo $event_pull->logo_img; ?>">
                  <?php echo $event_pull->name; ?>
                </a>
                <?php
                $counter++;
                if($counter === 8 || $club_team_stat === array_key_last($team_rosters)){
                 echo ' </div></figure></li>';
                 $counter = 0;
                }?>
            <?php endforeach ?>
            </ul>
          </div>
        </div>
        
        <script style="display:none">     

            function clearActiveSelections(type) {
              if(type == 'event') {
                const selections = document.querySelectorAll('.stat-organization a');
                selections.forEach(selection => {
                  selection.classList.remove('active-selection');
                })
              } else if(type == 'stat') {
                const selections = document.querySelectorAll('.orbit-container img');
                selections.forEach(selection => {
                  selection.classList.remove('active-selection--shadow');

                })
              }
            }
          
            function displayTeam(event, id){
               clearActiveSelections('stat');
               event.target.classList.add('active-selection--shadow');
              closingPrevTeams();
              $('#team_event_players' + id).css('display','block');
              $('.event' + id).css('display','block');
              $('.bx_score_title').css('display','block');
            }
            function closingPrevTeams(id){
              $('.doublecheck').css('display','none'); 
            }
          
            function closeTeam(id){
              $('.doublecheck').css('display','none'); 
            }
        </script>
        
<?php foreach($team_rosters as $team_roster):
                  ?>
                  <div class="small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
                    <div class="doublecheck" id="team_event_players<?php echo $team_roster->event_id; ?>" style="display:none;">
                      <div class="info-block">
                        <div class="grid-x grid-margin-x rosters_box_updates" id="club-team-game-scores">
                          <div class="cell small-12">
                            <div id="profile-stats-avg" class="cell small-12">
<!--                               <h3 class="" style=";color:#fff ;text-align:center;padding:20px"><?php echo $team_roster->event_name; ?></h3> -->
                              <div class="exit_container">
                                <a class="event_stat_exit" style="text-decoration: none;" onclick="closeCurrent('<?php echo $team_roster->event_id; ?>')">X</a>
                              </div>
                            </div>
                          </div>
                          
                          <?php $team_roster->players = json_decode($team_roster->players);?>
                              <div class="orbit large-12 large-margin-bottom redeploy event-orbit" role="region" aria-label="Favorite Space Pictures" data-orbit  data-auto-play="false" style="" >
                              <div class="orbit-wrapper">
                                <div class="orbit-controls orbit-controls-team">
                                  <button class="orbit-previous pl_img_prev"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
                                  <button class="orbit-next pl_img_nxt"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
                                </div>
                                <ul class="orbit-container orb_container_v2" style="height: 128px !important;">
                                <a class="event_results_exit" style="text-decoration: none;" onclick="closeTeam('<?php echo preg_replace('/\s+|\.|-/', '', $team_roster->event_id); ?>')">X</a><?php
                                $counter = 0;
                                foreach($team_roster->players as $pl_id => $pl_data):
                                  ?>
                                <?php 
                                    if($counter === 0){echo '<li class="is-active orbit-slide cell"><figure class="orbit-figure"><div class="event-contain" id="teamStatPlayerContainer">';}  
                                    $event_pull= g365_get_event_data($team_roster->event_id, true);
//                                       echo $team_roster->players;
                                      $pl_info = g365_player_db($pl_id);
                                      $img_link = g365_player_img_dir($pl_info[0]->nickname, $event_pull->nickname, $pl_id);
//                                     print_r($img_link);
                                  ?>
                                    <div class="pl_display_team_stats">
<!--                                       $player_profile = get_site_url().'/player/'.$player_nickname.'/stats/#stat'.str_replace('-','',g365_date_format($select_year, 2)); -->
                                      <img class="orbit-image team_stats_pl_img" src="<?php echo $img_link; ?>">
                                      <a class="team_stat_name_displ font-eurostile text-uppercase bold" href="<?php echo get_site_url(); ?>/player/<?php echo $pl_info[0]->nickname ?>"> <?php echo $pl_info[0]->name ?> </a>
                                      <a class="team_stat_jers_displ font-eurostile" href="<?php echo get_site_url(); ?>/player/<?php echo $pl_info[0]->nickname ?>" style="text-shadow: 0 2px 2px rgba(0,0,0,0.2);"> <?php echo $pl_data->j_num ?> </a>
                                      <a class="team_stat_play_displ font-eurostile bold" href="<?php echo get_site_url(); ?>/player/<?php echo $pl_info[0]->nickname ?>"> View Profile </a>
                                    </div>
                                    <?php
                                    $counter++;
                                    if($counter === 6 || $club_team_stat === array_key_last($team_rosters)){
                                     echo ' </div></figure></li>';
                                     $counter = 0;
                                    }?>
                                <?php endforeach?>
                                </ul>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
<?php endforeach; ?>
        <?php
          $club_team_stat_lists = g365_club_team_stat($event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 5); 
//         print_r([$event_id, $team_id, $org_data->id, $opponent_id, $select_year]);
//         echo "<pre>"; print_r($club_team_stat_lists); echo "</pre>";
          if(!empty($club_team_stat_lists)){
            $team_graph = g365_program_graph($club_team_stat_lists, 'game_result_label');
          }else{
            echo ("<p>".g365_message()['team_win_loss']."</p>");
          }
         ?>
         
        
        <div class="small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
                    <div class="doublecheck bx_score_title" style="display: none;">
                      <div class="info-block">
                        <div class="grid-x grid-margin-x" id="club-team-game-scores">
                          <div class="cell small-12">
                            <div id="profile-stats-avg" class="cell small-12">
                              <h3 class="" style=";color:#fff ;text-align:center;padding:20px"><?php echo $club_team_stat->event_name; ?></h3>
                              <div class="exit_container">
                                <a class="event_stat_exit" style="text-decoration: none;" onclick="closeCurrent('<?php echo preg_replace('/\s+|\.|-/', '', $club_team_stat->event_id); ?>')">X</a>
                              </div>
                              <div class="grid-x small-padding-top small-padding-bottom" style="background:black; color: white;">
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-3 medium-2 large-2 table-font-mobile">Team</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-3 medium-2 large-2 table-font-mobile">Opponent</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-3 medium-2 large-3 table-font-mobile">Result</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-3 medium-2 large-3 table-font-mobile">Action</p>
                              </div>
                            </div>
                          </div>
                          <?php foreach($club_team_stat_lists as $index => $club_team_stat_list){
                                  $game_court = $club_team_stat_list->game_court;
                                  $game_time = date_format(date_create($club_team_stat_list->game_time), 'M d Y g:i A');
                                  $opponent = g365_club_team_stat($event_id, $team_id, $org_id, $club_team_stat_list->opponent_id, $year, $type = 3);
                                  $opponent_name = str_replace('.', '', $opponent[0]->team_name);
                                  $game_result = $club_team_stat_list->game_result;
                                  $year = date('Y', strtotime($club_team_stat_list->game_time));
//                                   print_r($year);
  
                                  if(empty($opponent_name)){
                                    $opponent_name = "Opponent";
                                  }else{
                                    $opponent_name = $opponent_name;
                                  }
                                  if(empty($game_result)){
                                    $game_result = "N/A";
                                  }else{
                                    $game_result = $game_result;
                                  } 
                                  echo('
                                    <div class="cell small-12 doublecheck event'.$club_team_stat_list->event_id.'" id="event'.$club_team_stat_list->event_id.'"  style="display: none">
                                      <div class="info no-margin-bottom bg-white" style="margin-bottom: 0 !important" >
                                        <div class="club_game_result_ls ls-hover grid-x small-padding-bottom">
                                          <div class="small-3 medium-2 large-2 text-center font-eurostile weight-bold text-black table-font-mobile">
                                            '.$club_team_stat_list->team_name.'
                                          </div>
                                          <div class="small-3 medium-2 large-2 text-center font-eurostile weight-bold text-black table-font-mobile">
                                            '.$opponent_name.'
                                          </div>
                                          <div class="small-3 medium-2 large-3 text-center font-eurostile weight-bold text-black table-font-mobile">
                                            '.$game_result.'
                                          </div>
                                          <div class="small-3 medium-2 large-3 text-center font-eurostile weight-bold text-black table-font-mobile">
                                             <button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:12px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-select-year="'.$select_year.'" data-event-name="'.$club_team_stat_list->event_name.'" data-game-id="'.$club_team_stat_list->game_id.'" data-team-id="'.$club_team_stat_list->team_id.'" data-url="'.home_url().'"> Box Score</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  ');
  
                                } ?>
                        </div>
                      </div>
                    </div>
                  </div>
        
        
        
        <div class="team_roster_block cell small-12 medium-6 large-6 small-margin-bottom ">
<!--           <h5>Active Rosters</h5> -->
          <?php // $defautl_ros = g365_team_rosters($select_year, $team_id, $ros_data->org_id, 1); if(!empty($defautl_ros)): /*if-active*/ foreach($defautl_ros as $team_roster): ?>
<!--            <ul class="accordion club-rosters xsmall-padding-bottom" data-accordion data-allow-all-closed="true">
            <li class="accordion-item" data-accordion-item> -->
              <!-- Accordion tab title -->
<!--               <a href="#" class="accordion-title" style="font-size:12px; padding:10px 0 10px 14px"><?php // echo !empty($team_roster->event_name) ? $team_roster->event_name : "Club Team" ?></a> -->
<!--               <div class="extra-info grid-container">
                <div class="grid-x grid-margin-x">
                </div>
              </div>
              <div class="accordion-content" data-tab-content>
                <div class="grid-container"> -->
                  <?php 
//                     if( empty($team_roster->players) ) {
//                       echo 'No roster uploaded for this team.';
//                     } else {
//                       $roster_players = array();
//                       $team_veri = 'verified-team';
//                       $team_roster->players = json_decode($team_roster->players);
//                       foreach( $team_roster->players as $pl_id => $pl_data ){
//                         $pl_info = g365_player_db($pl_id);
//                         $roster_players[] = '<tr><td class="pl_name"><a href="' . get_site_url() . '/player/' . $pl_info[0]->nickname . '" target="_blank">' . $pl_info[0]->name . '</a></td><td class="jersey_num">#' . $pl_data->j_num . '</td></tr>';
//                       }
//                       echo '<table><thead><th>Player</th><th>Jersey</th><tbody>';
//                       echo implode('', $roster_players);
//                       echo '</tbody></table>';
//                     }
                  ?>
<!--                 </div>
              </div>
            </li>
           </ul> -->
          <?php 
//             endforeach; else:/*endif-active*/ echo ('<div>'.g365_message()['not_available'].'</div>'); endif/*endif-active*/; 
            $team_rosters = g365_team_rosters($select_year, $team_id, $ros_data->org_id, 2);
          ?>
          
          <?php foreach($team_rosters as $team_roster): 
          ?>     
              
           <ul class="accordion club-rosters xsmall-padding-bottom hide" data-accordion data-allow-all-closed="true">
            <li class="accordion-item" data-accordion-item>
              <!-- Accordion tab title -->
              <a href="#" class="accordion-title" style="font-size:12px; padding:10px 0 10px 14px"><?php echo $team_roster->event_name ?></a>
              <div class="extra-info grid-container">
                <div class="grid-x grid-margin-x">
                </div>
              </div>
              <div class="accordion-content" data-tab-content>
                <div class="grid-container">
              <?php if( empty($team_roster->players) ) {
                echo 'No roster uploaded for this team.';
              } else {
                $roster_players = array();
                $team_veri = 'verified-team'; 
                $team_roster->players = json_decode($team_roster->players);
                foreach( $team_roster->players as $pl_id => $pl_data ) {
                  $pl_info = g365_player_db($pl_id);
                  $roster_players[] = '<tr><td class="pl_name"><a href="' . get_site_url() . '/player/' . $pl_info[0]->nickname . '" target="_blank">' . $pl_info[0]->name . '</a></td><td class="jersey_num">#' . $pl_data->j_num . '</td></tr>';
                }
                echo '<table><thead><th>Player</th><th>Jersey</th><tbody>';
                echo implode('', $roster_players);
                echo '</tbody></table>';
              } ?>
                </div>
              </div>
            </li>
           </ul>         
          <?php endforeach ?>
      </div>
<!--       <div class="cell small-12 medium-6 large-6 win-loss-statistics">
        <h5 style="text-align: center;">Win/Loss Statistics</h5>
        <?php 
          $team_id = $ros_data->team_id;
          $game_stats = game_stat_filter($player_id, $event_id, $is_only_event = true, $select_year, $exception = null); 
          $club_team_stat_lists = g365_club_team_stat($event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 5);
          if(!empty($club_team_stat_lists)){
            $team_graph = g365_program_graph($club_team_stat_lists, 'game_result_label');
          }else{
            echo ("<p>".g365_message()['team_win_loss']."</p>");
          }
        ?>
        <div class="club_team_pchart" id="roster_game_chart_<?php echo $team_id; ?>"</div>
        <?php echo club_game_chart($team_graph['win'], $team_graph['loss'], '','roster_game_chart_'.$team_id); ?>
      </div>
      </div> -->
             
      <div class="team-award cell small-12 medium-12 large-12"><!-- Team rankings -->
        <?php g365_dir_render('club-profile','team-ranking', $player_id, $arg = array($org_data->id, $select_year, $team_id)); ?>
      </div>
      <div class="team-award cell small-12 medium-12 large-12"><!-- Award -->
        <?php g365_dir_render('club-profile','team-indivdual-award', $player_id, $arg = array($select_year, $team_id, $org_data->id)); ?>
      </div>
      <div class="team-championship cell small-12 medium-12 large-12">
        <?php g365_dir_render('club-profile','season-team-ranking', $player_id, $arg=array($select_year, $team_id)); ?>
      </div>
      
      <div class="cell small-12 medium-12 large-12 small-margin-top teams-schedule">
        <h1 class="small-text-center">Schedule</h1>
      <?php if( empty($ros_data->event_names) ) {
        echo '<p class="small-text-center">No schedule uploaded for this team.</p>';
      } else {
        $roster_events = array();
        $ros_data->event_names = json_decode($ros_data->event_names);
        foreach( $ros_data->event_names as $ev_id => $ev_int ) {
          $ev_locs = (empty($org_rosters[2][ $ev_id ]->short_locations)) ? $org_rosters[2][ $ev_id ]->locations : $org_rosters[2][ $ev_id ]->short_locations;
          $roster_events[date('y/m/d', strtotime($org_rosters[2][ $ev_id ]->eventtime))] = '<tr class=""><td class="ev_date">' . $org_rosters[2][ $ev_id ]->eventtime . '</td><td class="ev_player"><a href="' . get_site_url() . '/event/' . $org_rosters[2][ $ev_id ]->url . '/" target="_blank">' . $org_rosters[2][ $ev_id ]->name . '</a></td><td>' . (( empty($ev_locs) ) ? '&nbsp;' : g365_build_locations($ev_locs, 3)) . '</td></tr>';
        }
        ksort( $roster_events );
        echo '<table><thead><th>Date</th><th>Event</th><th>Location</th><tbody>';
        echo implode('', $roster_events);
        echo '</tbody></table>';
      } ?>
      </div>
  </div>
  <div class="team-championship cell small-12 medium-12 large-12 medium-margin-top">
        <?php g365_dir_render('club-profile','championship', $player_id, $arg = array($select_year, $team_id, $org_data->id, 'team_champ')); ?>
      </div>    
  <?php endforeach; ?>
  <?php else: ?>
  <p>No default team rosters created.</p>
  <?php endif; ?>
</div>
<?php echo (cts_dialog_js()); ?>