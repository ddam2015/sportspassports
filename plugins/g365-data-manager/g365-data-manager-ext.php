<?php
/*
 *	Description: An Extension of Grassroots 365 Data Management contains additional functionality to support Grassroot 365 website.
 *	Author: Daradona Dam
 *	Version: 1.0
 */
function dbs(){
  global $wpdb; $dbs = new \stdClass();
  $dbs->stats = $wpdb->g365_stats;
  $dbs->events = $wpdb->g365_events;
  $dbs->players = $wpdb->g365_players;
  $dbs->games = $wpdb->g365_games;
  $dbs->rosters = $wpdb->g365_rosters;
  $dbs->teams = $wpdb->g365_teams;
  $dbs->orgs = $wpdb->g365_orgs;
  $dbs->slb_yearly_stat = $wpdb->g365_yearly_slb;
  $dbs->coaches = $wpdb->g365_coaches;
  $dbs->rankings = $wpdb->g365_rankings;
  $dbs->awards = $wpdb->g365_awards;
  $dbs->award_refs = $wpdb->g365_award_refs;
  $dbs->groups = $wpdb->g365_groups;
  $dbs->favorites = $wpdb->g365_favorites;
  $dbs->positions = $wpdb->g365_positions;
  $dbs->images = $wpdb->g365_images;
  $dbs->badges = $wpdb->g365_badges;
  $dbs->badges_core = $wpdb->g365_badges_core;
  $dbs->player_badges = $wpdb->g365_player_badges;
  $dbs->api_keys = $wpdb->g365_api_keys;
  $dbs->claims = $wpdb->g365_claims;
  $dbs->team_stats = $wpdb->g365_team_stats;
  $dbs->device_tokens = $wpdb->g365_device_tokens;
  $result = '{
    "players": "'.$dbs->players.'",
    "events": "'.$dbs->events.'",
    "stats": "'.$dbs->stats.'",
    "games": "'.$dbs->games.'",
    "rosters": "'.$dbs->rosters.'",
    "teams": "'.$dbs->teams.'",
    "coaches": "'.$dbs->coaches.'",
    "rankings": "'.$dbs->rankings.'",
    "awards": "'.$dbs->awards.'",
    "award_refs": "'.$dbs->award_refs.'",
    "groups": "'.$dbs->groups.'",
    "slb_yearly_stats": "'.$dbs->slb_yearly_stat.'",
    "orgs": "'.$dbs->orgs.'",
    "favorites": "'.$dbs->favorites.'",
    "positions": "'.$dbs->positions.'",
    "images": "'.$dbs->images.'",
    "badges": "'.$dbs->badges.'",
    "badges_core": "'.$dbs->badges_core.'",
    "player_badges": "'.$dbs->player_badges.'",
    "api_keys": "'.$dbs->api_keys.'",
    "claims": "'.$dbs->claims.'",
    "team_stats": "'.$dbs->team_stats.'",
    "device_tokens": "'.$dbs->device_tokens.'"
  }';
  return $result;
}
function g365_stat_table_query($query_start_date, $query_end_date, $data_type){ // $data_type for total stat or avg stat
  global $wpdb;
  $dbs = json_decode(dbs());
  return $wpdb->get_results(
    " SELECT DISTINCT players.id AS player_id, players.name AS player_name, SUM(JSON_EXTRACT(stats.stats, '$.pts')) AS total_point, SUM(JSON_EXTRACT(stats.stats, '$.rbs')) AS total_rebound, SUM(JSON_EXTRACT(stats.stats, '$.ast')) AS total_assist, SUM(JSON_EXTRACT(stats.stats, '$.stl')) AS total_steal, SUM(JSON_EXTRACT(stats.stats, '$.blk')) AS total_block FROM $dbs->stats AS stats 
      INNER JOIN $dbs->players AS players 
      ON stats.player = players.id
      INNER JOIN $dbs->events AS events
      ON stats.event = events.id
      WHERE game > 0 AND events.eventtime BETWEEN '$query_start_date' AND '$query_end_date'
      GROUP BY players.id, player_name
    "
  );
}
function g365_top_pl_stat($event_id = null, $stat_type = null, $stat = null, $previous_year = null, $input_year = null, $limit_result, $arg = null, $type = null){
  global $wpdb;
  $selected_brand = $arg['brand'];
  if(!empty($selected_brand)){ $brand = ' AND events.org = '.$selected_brand.' '; }else{ $brand = ''; }
  $dbs = json_decode(dbs());
  $query_start_date = $previous_year.'-09-01'; // Start month is 09/01
  $query_end_date = $input_year.'-08-31'; // End month is 08/31
  $stat_filter_condition = " ( pl_minute >= 5 OR (ISNULL(pl_minute) AND sum_all_stats > 0) OR ((pl_minute = '') AND sum_all_stats > 0) OR (pl_minute < 5 AND sum_all_stats > 0)  ) ";
  $point = "JSON_EXTRACT(stats.stats, '$.pts')"; $rebound = "JSON_EXTRACT(stats.stats, '$.rbs')"; $assist = "JSON_EXTRACT(stats.stats, '$.ast')"; $steal = "JSON_EXTRACT(stats.stats, '$.stl')"; $three_pt = "JSON_EXTRACT(stats.stats, '$.three_pt')"; $block = "JSON_EXTRACT(stats.stats, '$.blk')"; $play_time = "JSON_EXTRACT(stats.stats, '$.time_pl')";
  $stat_pt = "IF ( ISNULL($point) OR $point = '', '0', $point )"; $stat_reb = "IF ( ISNULL($rebound) OR $rebound = '', '0', $rebound )"; $stat_ast = "IF ( ISNULL($assist) OR $assist = '', '0', $assist )"; $stat_stl = "IF ( ISNULL($steal) OR $steal = '', '0', $steal )"; $stat_three_pt = "IF ( ISNULL($three_pt) OR $three_pt = '', '0', $three_pt )"; $stat_blk = "IF ( ISNULL($block) OR $block = '', '0', $block )";
  if(!empty($event_id)){
    $sql_and = ' AND ';
    $is_event = ' rosters.event = '.$event_id;
    $is_year_option = '';
  }else{ // Search by year
    $is_event = '';
    $sql_and = '';
    $is_year_option = ' event_time BETWEEN '."'$query_start_date'".' AND '."'$query_end_date'";
  }
  if( $stat_type == '2' ){ // Average all
    $limit = '';
  }
  elseif( $stat_type == '1' ){ // Total top players base on $limit_result
    $limit = 'LIMIT '.$limit_result;
  }
  $include_field = " games.id AS game_id, stats.id AS stat_id, rosters.org AS org_id, rosters.id AS roster_id, rosters.event AS event_id, players.id AS player_id, players.nickname AS player_nickname, players.name AS player_name, rosters.enabled AS roster_enabled, players.enabled AS player_enabled, rosters.players AS roster_player, events.eventtime AS event_time, events.name AS event_name, IF( ISNULL(orgs.abbreviation) OR orgs.abbreviation = '', CONCAT(rosters.level, 'U ', orgs.name, ' ', teams.name, ' ', IF( ISNULL(rosters.division) OR rosters.division = '', '', rosters.division )), CONCAT(rosters.level, 'U ', orgs.abbreviation, ' ', teams.name, ' ', IF( ISNULL(rosters.division) OR rosters.division = '', '', rosters.division )) ) AS 'team_name', ";
  $stat_cal = " $stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_three_pt AS stat_three, $stat_blk AS stat_block, SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) AS pl_minute, ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) AS sum_all_stats ";
  $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->players players ON rosters.players LIKE CONCAT('%\"', players.id, '\":%') $sql_and $is_event INNER JOIN $dbs->stats stats ON stats.event = rosters.event AND stats.game = games.id AND stats.player = players.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
  $filter_stat = " HAVING $is_year_option $is_event AND $stat_filter_condition AND roster_enabled = 1 AND player_enabled = 1 AND rosters.players LIKE CONCAT('%\"', players.id, '\":%') $sql_and $is_event ";
  $order_by = " ORDER BY CAST($stat AS UNSIGNED) DESC ";
  switch($type){
    case 1:
      $include_field = " games.id AS game_id, stats.id AS stat_id, rosters.org AS org_id, rosters.id AS roster_id, rosters.event AS event_id, players.id AS player_id, players.nickname AS player_nickname, players.name AS player_name, rosters.enabled AS roster_enabled, players.enabled AS player_enabled, rosters.players AS roster_player, events.org brand, events.eventtime AS event_time, events.name AS event_name, IF( ISNULL(orgs.abbreviation) OR orgs.abbreviation = '', CONCAT(rosters.level, 'U ', orgs.name, ' ', teams.name, ' ', IF( ISNULL(rosters.division) OR rosters.division = '', '', rosters.division )), CONCAT(rosters.level, 'U ', orgs.abbreviation, ' ', teams.name, ' ', IF( ISNULL(rosters.division) OR rosters.division = '', '', rosters.division )) ) AS 'team_name', ";
      $stat_filter_condition = " ( SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) >= 5 OR (ISNULL(SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)) AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR ((SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) = '') AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR (SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) < 5 AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0)  ) ";
      $date_range = ' events.eventtime BETWEEN '."'$arg[0]-$arg[1]-$arg[2]'".' AND '."'$arg[3]-$arg[4]-$arg[5]'";
      $filter_stat = " WHERE $date_range AND $stat_filter_condition $brand AND rosters.enabled = 1 AND players.enabled = 1 AND rosters.players LIKE CONCAT('%\"', players.id, '\":%') ";
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat $order_by $limit");      
      break;
  }
  return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat $order_by $limit");
} // End
function g365_stat_leader($event_id = null, $stat = null, $input_year = null, $limit_result = null, $player_level = null, $type = null, $pl_division = null, $arg = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $date = g365_date_format($input_year, 1);
  $is_date_range = ' event_time BETWEEN '."$date";
  if(!empty($pl_division)){
    $dvs = " AND IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.division, rosters_home.division) = '$pl_division' ";
  }else{$dvs = "";}
  $point = "JSON_EXTRACT(stats.stats, '$.pts')"; $rebound = "JSON_EXTRACT(stats.stats, '$.rbs')"; $assist = "JSON_EXTRACT(stats.stats, '$.ast')"; $steal = "JSON_EXTRACT(stats.stats, '$.stl')"; $three_pt = "JSON_EXTRACT(stats.stats, '$.three_pt')"; $block = "JSON_EXTRACT(stats.stats, '$.blk')"; $play_time = "JSON_EXTRACT(stats.stats, '$.time_pl')";
  $stat_pt = "IF ( ISNULL($point) OR $point = '', '0', $point )"; $stat_reb = "IF ( ISNULL($rebound) OR $rebound = '', '0', $rebound )"; $stat_ast = "IF ( ISNULL($assist) OR $assist = '', '0', $assist )"; $stat_stl = "IF ( ISNULL($steal) OR $steal = '', '0', $steal )"; $stat_three_pt = "IF ( ISNULL($three_pt) OR $three_pt = '', '0', $three_pt )"; $stat_blk = "IF ( ISNULL($block) OR $block = '', '0', $block )";
  $include_field = " events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.nickname AS event_nickname, events.eventtime AS event_time, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) AS player_level, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.org, rosters_home.org) AS org_id, ";
  $stat_cal = " $stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_three_pt AS stat_three, $stat_blk AS stat_block, SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) AS pl_minute, ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) AS sum_all_stats ";
  $stat_filter_condition = " ( pl_minute >= 5 OR (ISNULL(pl_minute) AND sum_all_stats > 0) OR ((pl_minute = '') AND sum_all_stats > 0) OR (pl_minute < 5 AND sum_all_stats > 0)  ) ";
  $joins = " FROM $dbs->stats AS stats LEFT JOIN $dbs->games AS games ON games.id = stats.game LEFT JOIN $dbs->players AS players ON players.id = stats.player LEFT JOIN $dbs->rosters AS rosters_away ON rosters_away.id = games.away_team LEFT JOIN $dbs->rosters AS rosters_home ON rosters_home.id = games.home_team LEFT JOIN $dbs->events AS events ON events.id = games.event_id ";
  $filter_stat = " HAVING $is_date_range AND player_level = $player_level AND $stat_filter_condition AND player_enabled = 1 AND stat_enabled = 1 ";
  $order_by = " ORDER BY CAST($stat AS UNSIGNED) DESC ";
  if(!empty($player_level)){ $pl_level = " AND IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) = $player_level "; }else{ $pl_level = ""; }
  switch($type){
    case '1': // Average stat by YEAR and stat type
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat $order_by");
      break;
    case '2': // Average stat by EVENT and level
      $include_field = " events.id AS event_id, events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.eventtime AS event_time, events.nickname AS event_nickname, events.name AS event_name, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) AS player_level, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.division, rosters_home.division) AS player_division, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.org, rosters_home.org) AS org_id, ";
      $stat_filter_condition = " ( SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) >= 5 OR (ISNULL(SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)) AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR ((SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) = '') AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR (SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) < 5 AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0)  ) ";
      $filter_stat = " WHERE events.id = $event_id $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat");
      break;
    case '3': // Default average stat by year with all top 5 from 5 stat types
      $filter_stat = " HAVING $is_date_range AND $stat_filter_condition AND player_enabled = 1 AND stat_enabled = 1 ";
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat ");
      break;
    case '4': // Average stat by event.
      $include_field = " events.id AS event_id, events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.eventtime AS event_time, events.nickname AS event_nickname, events.name AS event_name, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) AS player_level, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.division, rosters_home.division) AS player_division, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.org, rosters_home.org) AS org_id, ";
      $stat_filter_condition = " ( SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) >= 5 OR (ISNULL(SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)) AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR ((SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) = '') AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR (SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) < 5 AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0)  ) ";
      $filter_stat = " WHERE events.id = $event_id $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      $stat_cal = " $stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_three_pt AS stat_three, $stat_blk AS stat_block ";
//       $filter_stat = " HAVING event_id = $event_id AND $stat_filter_condition AND player_enabled = 1 AND stat_enabled = 1 ";
      // Use this for player and team spotlight
      !empty($arg['is_player_team_spotlight']) ? $pl_tm_spotlight = $arg['is_player_team_spotlight'] : $pl_tm_spotlight = '';
      if($pl_tm_spotlight == true){
        $include_field = " events.id AS event_id, events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, (SELECT st.profile_img FROM $dbs->stats st WHERE st.event = $event_id AND st.game = 0 AND st.player = player_id) AS ev_profile_img, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.eventtime AS event_time, events.nickname AS event_nickname, events.name AS event_name, events.logo_img AS event_logo, ";
        $avg_select = " SELECT player_name, AVG(stat_point) avg_stat, event_name, event_logo, player_nickname player_url, ev_profile_img, event_nickname, player_id FROM ( ";
        $avg_close = " ) avg_stat GROUP BY player_id ORDER BY avg_stat DESC LIMIT $limit_result ";
        return $wpdb->get_results("$avg_select SELECT $include_field $stat_cal $joins $filter_stat $avg_close");
      }
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat");
      break;
    case '5': // Average stat by YEAR with ALL LEVELS and SELECTED STAT CATAGORY
      $filter_stat = " HAVING $is_date_range AND $stat_filter_condition AND player_enabled = 1 AND stat_enabled = 1 ";
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat $order_by");
      break;
    case '6': // Average stat by YEAR with selected LEVEL and ALL STAT CATAGORIES
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat");
      break;
    case '7': // Total stat by YEAR with selected LEVEL and selected STAT CATAGORY
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat");
      break;
    case '8': // Total stat by EVENT with selected level and selected stat catagory
      $include_field = " games.event_id AS event_id, events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.nickname AS event_nickname, events.name AS event_name, events.eventtime AS event_time, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) AS player_level, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.org, rosters_home.org) AS org_id, ";
      $filter_stat = " HAVING event_id = $event_id AND player_level = $player_level AND $stat_filter_condition AND player_enabled = 1 AND stat_enabled = 1 ";
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat $order_by");
      break;
    case '9': // Remote average stat by event.
      $stat_cal = " $stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_three_pt AS stat_three, $stat_blk AS stat_block ";
      $stat_filter_condition = " ( SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) >= 5 OR (ISNULL(SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)) AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR ((SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) = '') AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR (SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) < 5 AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0)  ) ";
      $joins = " FROM $dbs->stats AS stats INNER JOIN $dbs->games AS games ON games.id = stats.game INNER JOIN $dbs->players AS players ON players.id = stats.player INNER JOIN $dbs->rosters AS rosters_away ON rosters_away.id = games.away_team INNER JOIN $dbs->rosters AS rosters_home ON rosters_home.id = games.home_team INNER JOIN $dbs->events AS events ON events.id = games.event_id ";
      $include_field = " events.id AS event_id, events.locations, events.eventtime, events.logo_img, events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, players.grad_year AS pl_grad_year, players.position AS pl_position, CONCAT(players.height_ft,\"'\",players.height_in) AS pl_height, players.gpa AS pl_gpa, players.sat AS pl_sat, CONCAT(players.email,\" <br\>\",players.phone) AS pl_contact_info, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.eventtime AS event_time, events.nickname AS event_nickname, events.name AS event_name, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) AS player_level, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.division, rosters_home.division) AS player_division, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.org, rosters_home.org) AS org_id, ";
      $filter_stat = " WHERE events.id = $event_id AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat");
      break;
    case '10': // Remote average stat by EVENT and level
      if(!empty($player_level)){ $pl_level = " AND IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) = $player_level "; }else{ $pl_level = ""; }
      $stat_cal = " $stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_three_pt AS stat_three, $stat_blk AS stat_block ";
      $stat_filter_condition = " ( SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) >= 5 OR (ISNULL(SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)) AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR ((SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) = '') AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0) OR (SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) < 5 AND ($stat_pt + $stat_reb + $stat_ast + $stat_stl + $stat_three_pt + $stat_blk) > 0)  ) ";
      $joins = " FROM $dbs->stats AS stats INNER JOIN $dbs->games AS games ON games.id = stats.game INNER JOIN $dbs->players AS players ON players.id = stats.player INNER JOIN $dbs->rosters AS rosters_away ON rosters_away.id = games.away_team INNER JOIN $dbs->rosters AS rosters_home ON rosters_home.id = games.home_team INNER JOIN $dbs->events AS events ON events.id = games.event_id ";
      $include_field = " events.id AS event_id, events.locations, events.eventtime, events.logo_img, events.org AS ev_type, players.id AS player_id, players.name AS player_name, players.nickname AS player_nickname, players.grad_year AS pl_grad_year, players.position AS pl_position, CONCAT(players.height_ft,\"'\",players.height_in) AS pl_height, players.gpa AS pl_gpa, players.sat AS pl_sat, players.act AS pl_act, CONCAT(players.email,\" <br\>\",players.phone) AS pl_contact_info, players.enabled AS player_enabled, stats.enabled AS stat_enabled, events.eventtime AS event_time, events.nickname AS event_nickname, events.name AS event_name, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.level, rosters_home.level) AS player_level, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.division, rosters_home.division) AS player_division, IF(JSON_CONTAINS(rosters_away.players, CONCAT(players.id), '$'), rosters_away.org, rosters_home.org) AS org_id, ";
      $filter_stat = " WHERE events.id = $event_id $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      if(empty($arg['brand_type'])){ $brand_type = ''; }else{ $brand_type = $arg['brand_type']; }
      if(!empty($arg['year'])){
        $is_group_by_year = ' AND events.eventtime BETWEEN '.g365_date_format($arg['year'], 1);
      }else{ $is_group_by_year = ''; }
      if($brand_type == 'tsc'){
        $filter_stat = " WHERE events.org = 3 $is_group_by_year $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      }
      if($brand_type == 'ebc'){
        $filter_stat = " WHERE events.org = 2 $is_group_by_year $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      }
      if($brand_type == 'scs'){
        $filter_stat = " WHERE events.org = 7165 $is_group_by_year $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      }
      if($brand_type == 'hhh'){
        $filter_stat = " WHERE events.org = 7164 $is_group_by_year $pl_level $dvs AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
      }
      return $wpdb->get_results("SELECT $include_field $stat_cal $joins $filter_stat");
      break;
  }
}
function g365_array_filter($stat){ //Filter empty stat
  !empty($stat['stats']) ? $stats = $stat['stats'] : $stats = '';
  if(is_array($stat) == 1){
    $stat_field = json_decode($stats); // Decode array to object
    if(!empty($stat_field->time_pl)){ $play_time = $stat_field->time_pl; }else{ $play_time = ''; }
    if(!empty($stat_field->ast)){ $ast = $stat_field->ast; }else{ $ast = 0; }
    if(!empty($stat_field->blk)){ $blk = $stat_field->blk; }else{ $blk = 0; }
    if(!empty($stat_field->pts)){ $pts = $stat_field->pts; }else{ $pts = 0; }
    if(!empty($stat_field->rbs)){ $rbs = $stat_field->rbs; }else{ $rbs = 0; }
    if(!empty($stat_field->stl)){ $stl = $stat_field->stl; }else{ $stl = 0; }
    if(!empty($stat_field->three_pt)){ $three_pt = $stat_field->three_pt; }else{ $three_pt = 0; }
    $sum_of_all_stats = $ast + $blk + $pts + $rbs + $stl + $three_pt;
  }
  else if(is_object($stat) == 1){
    $stat_field = $stat->stats;
    if(!empty($stat_field->time_pl)){ $play_time = $stat_field->time_pl; }else{ $play_time = ''; }
    if(!empty($stat_field->ast)){ $ast = $stat_field->ast; }else{ $ast = 0; }
    if(!empty($stat_field->blk)){ $blk = $stat_field->blk; }else{ $blk = 0; }
    if(!empty($stat_field->pts)){ $pts = $stat_field->pts; }else{ $pts = 0; }
    if(!empty($stat_field->rbs)){ $rbs = $stat_field->rbs; }else{ $rbs = 0; }
    if(!empty($stat_field->stl)){ $stl = $stat_field->stl; }else{ $stl = 0; }
    if(!empty($stat_field->three_pt)){ $three_pt = $stat_field->three_pt; }else{ $three_pt = 0; }
    $sum_of_all_stats = $ast + $blk + $pts + $rbs + $stl + $three_pt;
  }else{
    echo 'Unable to identify stat data type';
  }
  if(!empty($play_time)){
    $array = explode(':', $play_time);
    $check_min = $array[1]; // Minute
    return ( ($check_min > 5) || empty($play_time) && (array_sum((array) $stat_field) > 0) || (($check_min <= 5) && (array_sum((array) $stat_field) > 0)) );
  }else{
    if($sum_of_all_stats > 0){
      return $sum_of_all_stats;
    }
  }
}
function top_player_query_fn($pl_stat_tb, $type = null, $sort_type, $limit_result, $arg = null){
  $column_id = array_column($pl_stat_tb, 'player_id'); // Column id
  $dup_counts = array_count_values($column_id); // Count dup column ids
    foreach($dup_counts as $key => $dup_count){
      $sum = 0; $sum_pt = 0; $sum_reb = 0; $sum_ast = 0; $sum_stl = 0; $sum_three = 0; $sum_blk = 0;
      for($i=0; $i < $dup_count; $i++){
        $dup_pos = array_search($key, $column_id);
        $player_name = $pl_stat_tb[$dup_pos]['player_name'];
        $player_nickname = $pl_stat_tb[$dup_pos]['player_nickname'];
        if(!empty($pl_stat_tb[$dup_pos]['player_profile'])){ $player_profile = $pl_stat_tb[$dup_pos]['player_profile']; }else{ $player_profile = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['img_link'])){ $player_img = $pl_stat_tb[$dup_pos]['img_link']; }else{ $player_img = ''; }
        $event_nickname = $pl_stat_tb[$dup_pos]['event_name'];
        $event_name = $pl_stat_tb[$dup_pos]['event_nickname'];
        $event_time = $pl_stat_tb[$dup_pos]['event_time'];
        $event_id = $pl_stat_tb[$dup_pos]['event_id'];
        $player_id = $pl_stat_tb[$dup_pos]['player_id'];
        if(!empty($pl_stat_tb[$dup_pos]['pl_grad_year'])){ $pl_grad_year = $pl_stat_tb[$dup_pos]['pl_grad_year']; }else{ $pl_grad_year = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['pl_position'])){ $pl_position = $pl_stat_tb[$dup_pos]['pl_position']; }else{ $pl_position = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['pl_height'])){ $pl_height = $pl_stat_tb[$dup_pos]['pl_height']; }else{ $pl_height = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['pl_gpa'])){ $pl_gpa = $pl_stat_tb[$dup_pos]['pl_gpa']; }else{ $pl_gpa = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['pl_sat'])){ $pl_sat = $pl_stat_tb[$dup_pos]['pl_sat']; }else{ $pl_sat = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['pl_act'])){ $pl_act = $pl_stat_tb[$dup_pos]['pl_act']; }else{ $pl_act = ''; }
        if(!empty($pl_stat_tb[$dup_pos]['pl_contact_info'])){ $pl_contact_info = $pl_stat_tb[$dup_pos]['pl_contact_info']; }else{ $pl_contact_info = ''; }
        $player_level = $pl_stat_tb[$dup_pos]['player_level'];
        $player_division = $pl_stat_tb[$dup_pos]['player_division'];
        if(!empty($pl_stat_tb[$dup_pos]['is_fav'])){ $is_fav = $pl_stat_tb[$dup_pos]['is_fav']; }else{ $is_fav = ''; }
        $sum += $pl_stat_tb[$dup_pos][$sort_type];
        $sum_pt += $pl_stat_tb[$dup_pos]['stat_point']; 
        $sum_reb += $pl_stat_tb[$dup_pos]['stat_rebound']; 
        $sum_ast += $pl_stat_tb[$dup_pos]['stat_assist'];
        $sum_three += $pl_stat_tb[$dup_pos]['stat_three'];
        $sum_stl += $pl_stat_tb[$dup_pos]['stat_steal']; 
        $sum_blk += $pl_stat_tb[$dup_pos]['stat_block'];
        unset($column_id[$dup_pos]); // Get rid of duplicate column
        unset($pl_stat_tb[$dup_pos]); // Get rid of array base on duplicate position 
      }
      if($type == 3){
        if($arg == 4){// Remote data with default level
          $new_st_avg_arr[] = array( 'player_name' => $player_name, 'player_nickname' => $player_nickname, 'player_id' => $player_id, 'player_profile' => $player_profile, 'player_img' => $player_img, 'pl_grad_year' => $pl_grad_year, 'pl_position' => (empty($pl_position) ? '': $pl_position), 'pl_height' => (empty($pl_height) ? '': $pl_height), 'pl_gpa' => (empty($pl_gpa) ? '': $pl_gpa), 'pl_sat' => (empty($pl_sat) ? '': $pl_sat), 'pl_act' => (empty($pl_act) ? '': $pl_act), 'pl_contact_info' => (empty($pl_contact_info) ? '': $pl_contact_info), 'is_fav' => (empty($is_fav) ? '': $is_fav), 'player_level' => (empty($player_level) ? '': $player_level), 'player_division' => (empty($player_division) ? '': $player_division), 'event_nickname' => (empty($event_nickname) ? '': $event_nickname), 'event_time' => (empty($event_time) ? '': $event_time), $sort_type => round( ($sum/$dup_count),1) );
        }else{
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $new_st_avg_arr[] = array( 'player_name' => $player_name, 'player_nickname' => $player_nickname, 'player_id' => $player_id, 'player_level' => g365_return_keys('g365_grade_key')[$player_level], 'player_division' => $player_division, 'event_nickname' => $event_nickname, 'event_name' => $event_name, 'event_id' => $event_id, 'event_time' => $event_time, $sort_type => round( ($sum/$dup_count),1), 'player_img'=>$img_link );
        }
      }else{
        $new_st_avg_arr[] = array( 'player_name' => $player_name, 'player_nickname' => $player_nickname, 'player_id' => $player_id, 'player_profile' => $player_profile, 'player_img' => $player_img, 'pl_grad_year' => $pl_grad_year, 'pl_position' => $pl_position, 'pl_height' => $pl_height, 'pl_gpa' => $pl_gpa, 'pl_sat' => $pl_sat, 'pl_act' => $pl_act, 'pl_contact_info' => $pl_contact_info, 'is_fav' => $is_fav, 'player_level' => $player_level, 'player_division' => $player_division, 'event_nickname' => $event_nickname, 'event_time' => $event_time, 'stat_point' => round( ($sum_pt/$dup_count),1), 'stat_rebound' => round( ($sum_reb/$dup_count),1), 'stat_assist' => round( ($sum_ast/$dup_count),1), 'stat_three' => round( ($sum_three/$dup_count),1), 'stat_steal' => round( ($sum_stl/$dup_count),1), 'stat_block' => round( ($sum_blk/$dup_count),1) );
      }
      $sort_type_column = array_column($new_st_avg_arr, $sort_type); // Column pointer
      array_multisort($sort_type_column, SORT_DESC, $new_st_avg_arr); // Sort avg
      $new_st_avg_arr = array_slice($new_st_avg_arr, 0, $limit_result); // Limit top results
    }
    return $new_st_avg_arr;
}
function g365_stat_table_filter($pl_stat_tb, $type = null, $sort_type, $limit_result, $stat_types = null, $arg = null){ // Filter stat based on select options
  if(empty($stat_types)){ // All 5 Stat Catagories
    $stat_types = array('stat_point' => 'stat_point', 'stat_rebound' => 'stat_rebound', 'stat_assist' => 'stat_assist', 'stat_block' => 'stat_block', 'stat_three' => 'stat_three', 'stat_steal' => 'stat_steal');
  }else{ // Specific Stat Catagory
    $stat_types = array($stat_types => $stat_types);
  }
  $main_array = array();
  switch($type){
    case '1' : // Total
      $new_st_avg_arr = $pl_stat_tb;
      return $new_st_avg_arr;
      break;
    case '2' : // Average
      $new_st_avg_arr = top_player_query_fn($pl_stat_tb, $type, $sort_type, $limit_result);
      return $new_st_avg_arr;
      break;
    case '3' : // For default stat leaderboard page by year and stat type
      $dafault_stat_leader = array();
      foreach($stat_types as $index => $stat_type){
        $top_player_query = top_player_query_fn($pl_stat_tb, $render_type = 3, $sort_type = $index, $limit_result, $arg);
        $dafault_stat_leader[$index] = $top_player_query;
      }
      return $dafault_stat_leader;
      break;
    case '4' : // For default stat leaderboard page by year and top 5 by stat type
      $dafault_stat_leader = array();
      foreach($stat_types as $index => $stat_type){
        $top_player_query = top_player_query_fn($pl_stat_tb, $render_type = null, $sort_type = $index, $limit_result);
        $dafault_stat_leader[$index] = $top_player_query;
      }
      return $dafault_stat_leader;
      break;
    case '5': // Decode remote data
      $dafault_stat_leader = array();
      $pl_stat_tb = stripslashes($pl_stat_tb);
      $pl_stat_tb = json_decode($pl_stat_tb, true);
      foreach($stat_types as $index => $stat_type){
        $top_player_query = top_player_query_fn($pl_stat_tb, $render_type = null, $sort_type = $index, $limit_result);
        $dafault_stat_leader[$index] = $top_player_query;
      }
      return $dafault_stat_leader;
      break;
      case '6' : // For default stat leaderboard page by year and stat type
      $dafault_stat_leader = array();
      $pl_stat_tb = stripslashes($pl_stat_tb);
      $pl_stat_tb = json_decode($pl_stat_tb, true);
      foreach($stat_types as $index => $stat_type){
        $top_player_query = top_player_query_fn($pl_stat_tb, $render_type = 3, $sort_type = $index, $limit_result);
        $dafault_stat_leader[$index] = $top_player_query;
      }
      return $dafault_stat_leader;
      break;
  }
} 
function g365_social_sharing_btn($player_name){
  $player_profile_link = get_site_url().'/player/'.$player_name.'/stats/';
  $social_sharing_btn = '
    <div class="fb-share-button" data-href="'.$player_profile_link.'" data-layout="button_count" data-size="large"></div>
    <div class="small-padding-right"><i class="new-twitter-icon"></i><a class="twitter-share-button twitterBtn smGlobalBtn" target=_blank href="https://twitter.com/share?text=Checkout my profile stats on G365:&url=' .$player_profile_link.'" data-size="large">Tweet</a></div>
';
  return $social_sharing_btn;
}

function g365_dir_render($sub_dir = null, $file_name, $player_id = null, $arg = null){
  $dir = 'inc/'.$sub_dir.'/'.$file_name.'.php';
  include($dir);
  $output_dir = ob_get_contents();
  return $output_dir;
}
function g365_pl_game_stat($player_id, $event_id, $is_only_event, $year = null, $exception = null){ // Player profile Game Stats
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $joins = " FROM $dbs->stats AS stats LEFT JOIN $dbs->games AS games ON games.id = stats.game LEFT JOIN $dbs->players AS players ON players.id = stats.player LEFT JOIN $dbs->rosters AS rosters_away ON rosters_away.id = games.away_team LEFT JOIN $dbs->rosters AS rosters_home ON rosters_home.id = games.home_team LEFT JOIN $dbs->events AS events ON events.id = games.event_id ";
  $point = "JSON_EXTRACT(stats.stats, '$.pts')"; $rebound = "JSON_EXTRACT(stats.stats, '$.rbs')"; $assist = "JSON_EXTRACT(stats.stats, '$.ast')"; $steal = "JSON_EXTRACT(stats.stats, '$.stl')"; $three_pt = "JSON_EXTRACT(stats.stats, '$.three_pt')"; $block = "JSON_EXTRACT(stats.stats, '$.blk')"; $play_time = "JSON_EXTRACT(stats.stats, '$.time_pl')";
  $stat_pt = "IF ( ISNULL($point) OR $point = '', '0', $point )"; $stat_reb = "IF ( ISNULL($rebound) OR $rebound = '', '0', $rebound )"; $stat_ast = "IF ( ISNULL($assist) OR $assist = '', '0', $assist )"; $stat_stl = "IF ( ISNULL($steal) OR $steal = '', '0', $steal )"; $stat_three_pt = "IF ( ISNULL($three_pt) OR $three_pt = '', '0', $three_pt )"; $stat_blk = "IF ( ISNULL($block) OR $block = '', '0', $block )";
//   $stat_cal = "$stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_blk AS stat_block, SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) AS pl_minute ";
  $stat_filter_condition = " ( (($stat_pt+$stat_reb+$stat_ast+$stat_three_pt+$stat_stl+$stat_blk)>0) OR SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)>=5 ) ";
  $include_field = "players.id AS player_id, events.id AS event_id, events.name AS event_name, events.eventtime AS event_time, games.start_time AS game_date_time, games.court AS game_court, games.id AS game_id, players.enabled AS player_enabled, stats.enabled AS stat_enabled, stats.stats ";
  $filter_stat = " WHERE events.eventtime BETWEEN $year AND players.id = $player_id AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
  $order_by = " ORDER BY events.eventtime DESC ";
  if($is_only_event == true){ // Pull all events without stat data
    $exception_list = event_exception_list();
    $include_field = "players.id AS player_id, players.nickname AS player_nickname, events.id AS event_id, events.name AS event_name, events.eventtime AS event_time, events.logo_img AS event_logo, players.enabled AS player_enabled, stats.enabled AS stat_enabled, stats.stats ";
    switch($exception){
      case '1':
        $filter_stat = "$filter_stat AND event_id NOT IN ($exception_list)";
        break;
      case '2':
        $filter_stat = "$filter_stat AND event_id IN ($exception_list)";
        break;
      case '3': // Admin player stats
        $include_field = "players.name AS player_name, players.id AS player_id, events.id AS event_id, events.name AS event_name, games.start_time, games.court, events.eventtime AS event_time, players.enabled AS player_enabled, stats.enabled AS stat_enabled, IF( rosters_home.players LIKE CONCAT('%\"',players.id, '\":%'), rosters_home.org, rosters_away.org ) AS ros_org, IF( rosters_home.players LIKE CONCAT('%\"',players.id, '\":%'), rosters_home.level, rosters_away.level ) AS ros_level, IF( rosters_home.players LIKE CONCAT('%\"',players.id, '\":%'), rosters_home.division, rosters_away.division ) AS ros_division, IF( rosters_home.players LIKE CONCAT('%\"',players.id, '\":%'), rosters_home.team, rosters_away.team ) AS team,
stats.stats";
        $include_field_tb1 = "player_name, player_id, event_id, event_name, start_time, court, event_time, player_enabled, stat_enabled, ros_org, ros_level, ros_division, tm.search_list AS team_name, stats ";
        $include_field_tb2 = "player_name, player_id, event_id, event_name, start_time, court, event_time, player_enabled, stat_enabled, ros_org, ros_level, ros_division, CONCAT(org.name,' ', team_name) AS team_name, stats ";
        return $wpdb->get_results(" SELECT $include_field_tb2 FROM (SELECT $include_field_tb1 FROM ( SELECT $include_field $joins $filter_stat $order_by) tb_1 LEFT JOIN $dbs->teams tm ON tm.id = tb_1.team) tb_2 LEFT JOIN $dbs->orgs org ON org.id = tb_2.ros_org ");
        break;
      case '4':
        $exception_list = event_exception_list('scholastic');
        $filter_stat = "$filter_stat AND event_id IN ($exception_list)";
        break;
    }
  }
  if($is_only_event == false){ // Pull all data base on specific event id. Need player id and event id 
    $include_field = "players.id AS player_id, events.id AS event_id, events.name AS event_name, events.eventtime AS event_time, games.start_time AS game_date_time, games.court AS game_court, games.id AS game_id, players.enabled AS player_enabled, stats.enabled AS stat_enabled, (SELECT MAX(tm.id) FROM $dbs->rosters ros LEFT JOIN $dbs->teams tm ON ros.team = tm.id WHERE ros.players LIKE CONCAT('%\"',players.id,'\":%') AND (ros.id = games.home_team OR ros.id = games.away_team)) AS team_id, stats.stats ";
    $filter_stat = "WHERE players.id = $player_id AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 AND events.id = $event_id";
  }
  return $wpdb->get_results("SELECT $include_field $joins $filter_stat $order_by");
}
function g365_pl_game_stat_sh($player_id, $event_id, $is_only_event, $year=null, $exception = null, $type){ // Player profile Game Stats
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $joins = " FROM $dbs->stats AS stats LEFT JOIN $dbs->games AS games ON games.id = stats.game LEFT JOIN $dbs->players AS players ON players.id = stats.player LEFT JOIN $dbs->rosters AS rosters_away ON rosters_away.id = games.away_team LEFT JOIN $dbs->rosters AS rosters_home ON rosters_home.id = games.home_team LEFT JOIN $dbs->events AS events ON events.id = games.event_id ";
  $point = "JSON_EXTRACT(stats.stats, '$.pts')"; $rebound = "JSON_EXTRACT(stats.stats, '$.rbs')"; $assist = "JSON_EXTRACT(stats.stats, '$.ast')"; $steal = "JSON_EXTRACT(stats.stats, '$.stl')"; $three_pt = "JSON_EXTRACT(stats.stats, '$.three_pt')"; $block = "JSON_EXTRACT(stats.stats, '$.blk')"; $play_time = "JSON_EXTRACT(stats.stats, '$.time_pl')";
  $stat_pt = "IF ( ISNULL($point) OR $point = '', '0', $point )"; $stat_reb = "IF ( ISNULL($rebound) OR $rebound = '', '0', $rebound )"; $stat_ast = "IF ( ISNULL($assist) OR $assist = '', '0', $assist )"; $stat_stl = "IF ( ISNULL($steal) OR $steal = '', '0', $steal )"; $stat_three_pt = "IF ( ISNULL($three_pt) OR $three_pt = '', '0', $three_pt )"; $stat_blk = "IF ( ISNULL($block) OR $block = '', '0', $block )";
//   $stat_cal = "$stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_blk AS stat_block, SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) AS pl_minute ";
  $stat_filter_condition = " ( (($stat_pt+$stat_reb+$stat_ast+$stat_three_pt+$stat_stl+$stat_blk)>0) OR SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)>=5 ) ";
  $include_field = "players.id AS player_id, events.id AS event_id, events.name AS event_name, events.eventtime AS event_time, games.start_time AS game_date_time, games.court AS game_court, games.id AS game_id, players.enabled AS player_enabled, stats.enabled AS stat_enabled ";
  $filter_stat = " WHERE events.eventtime BETWEEN $year AND players.id = $player_id AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 ";
  $order_by = " ORDER BY events.eventtime DESC";
  if($is_only_event == true){ // Pull all events without stat data
    $exception_list = event_exception_list();
    $include_field = "players.id AS player_id, players.nickname AS player_nickname, events.id AS event_id, events.name AS event_name, events.logo_img event_logo, events.dates event_date, events.eventtime event_time, events.locations event_location, players.enabled player_enabled, stats.enabled stat_enabled ";
    switch($exception){
      case '1':
        $filter_stat = "$filter_stat AND event_id NOT IN ($exception_list)";
        break;
      case '2':
        $filter_stat = "$filter_stat AND event_id IN ($exception_list)";
        break;
    }
    switch($type){
      case '1':
        if(!is_numeric($event_id)) return 'Need correct event id value to process';
        if(empty($event_id)) return 'Need event id to process';
        $filter_stat = "WHERE players.id = $player_id AND $stat_filter_condition AND players.enabled = 1 AND events.id = $event_id";
        break;
    }
  }
  if($is_only_event == false){ // Pull all data base on specific event id. Need player id and event id 
    if(!is_numeric($event_id)) return 'Need correct event id value to process';
    if(empty($event_id)) return 'Need event id to process';
    $filter_stat = "WHERE players.id = $player_id AND $stat_filter_condition AND players.enabled = 1 AND stats.enabled = 1 AND events.id = $event_id";
  }
  
  $results = $wpdb->get_results("SELECT event_id, event_name, event_logo, event_date, event_location From (SELECT $include_field $joins $filter_stat $order_by) event_list GROUP BY event_id");
  foreach($results as $result){
    // todo below line should not be needed. We should store images as https not http.
    if(isset($result->event_logo)){
      $result->event_logo = str_replace('http://', 'https://', $result->event_logo); 
    }
  }
  
  return $results;
}
function game_stat_filter($player_id, $event_id, $is_only_event, $year, $exception){ // Filter duplicate events
  $g365_game_data = g365_pl_game_stat($player_id, $event_id, $is_only_event, $year, $exception);
  $unique_event_id = array();
  foreach ($g365_game_data as $item){
    if (!array_key_exists($item->event_id, $unique_event_id)){
      $unique_event_id[$item->event_id] = $item;
    }
  }
  return $unique_event_id;
}
function game_stat_filter_sh($player_id, $event_id, $is_only_event, $year, $exception){ // Filter duplicate events
  $g365_game_data = g365_pl_game_stat_sh($player_id, $event_id, $is_only_event, $year, $exception, 1);
  $unique_event_id = array();
  if(is_array($g365_game_data)){
    foreach ($g365_game_data as $item){
      if (!array_key_exists($item->event_id, $unique_event_id)){
        $unique_event_id[$item->event_id] = $item;
      }
    }
  }
  return $unique_event_id;
}
function avg_game_stat($player_id, $event_id){
  !empty($year) ? $year = $year : $year = '';
  $event_game_avgs = g365_pl_game_stat($player_id, $event_id, $is_only_event = false, $year, $exception = null);
  if(empty($event_game_avgs)){
    $avg_pt = '0'; $avg_reb = '0'; $avg_ast = '0'; $avg_stl = '0'; $avg_blk = '0'; $avg_three = '0'; $ave_time_dec = '0';
  }else{
    $total_pt = 0;
    $total_three = 0;
    $total_reb = 0;
    $total_ast = 0;
    $total_stl = 0;
    $total_blk = 0;
    $count = count($event_game_avgs);
    foreach($event_game_avgs as $event_game_avg){
      $event_game_avg = json_decode($event_game_avg->stats);
      $total_pt  += $event_game_avg->pts;
      $total_reb += $event_game_avg->rbs;
      $total_ast += $event_game_avg->ast;
      $total_stl += $event_game_avg->stl;
      $total_blk += $event_game_avg->blk;
      $total_three += $event_game_avg->three_pt;
    }
    $avg_pt  = round($total_pt/$count, 1);
    $avg_reb = round($total_reb/$count, 1);
    $avg_ast = round($total_ast/$count, 1);
    $avg_stl = round($total_stl/$count, 1);
    $avg_blk = round($total_blk/$count, 1);
    $avg_three = round($total_three/$count, 1);
  }
  return array( 'avg_pt' => $avg_pt, 'avg_reb' => $avg_reb, 'avg_ast' => $avg_ast, 'avg_stl' => $avg_stl, 'avg_three' => $avg_three, 'avg_blk' => $avg_blk);
}
function g365_cts_tb($arg = null,$type = null){
  $tb = '
    <div class="avg_field large-margin-bottom table-scroll">
      <table class="text-left ghost-white-bg no-margin-bottom">
        <tbody class="stats__table--player">
          <tr>
            <th>PTS</th><th>REB</th><th>AST</th><th>BLK</th><th>STL</th><th>3PT</th>
          </tr>
          <tr class="color-body emphasis">              
            <td>'.$arg[0].'</td><td>'.$arg[1].'</td><td>'.$arg[2].'</td><td>'.$arg[3].'</td><td>'.$arg[5].'</td><td>'.$arg[4].'</td>
          </tr>
        </tbody>
      </table>
    </div>
  ';
  return $tb;
}
function cts_box_score_tb($arg = null,$type = null){
  $total_pts = 0; $total_reb = 0; $total_ast = 0; $total_stl = 0; $total_three = 0; $total_blk = 0; foreach($arg[0] as $pl_data){$stat_data = $pl_data['player_info']['stats']; $total_pts += $stat_data['pts']; $total_reb += $stat_data['rbs']; $total_ast += $stat_data['ast']; $total_stl += $stat_data['stl']; $total_three += $stat_data['three_pt']; $total_blk += $stat_data['blk'];}
  return array($total_pts,$total_reb,$total_ast,$total_blk,$total_three,$total_stl);
}
/**
 * Player profile season avg
 */
function g365_season_stat($player_id, $event_date = null, $type, $exception = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $event_yearly = g365_date_format($event_date, 1);
  $exception_list = event_exception_list();
  $from = " FROM $dbs->stats AS stats ";
  $joins = " INNER JOIN $dbs->players AS players ON stats.player = players.id INNER JOIN $dbs->events AS events ON stats.event = events.id ";
  switch($exception){
    case '1': // Exclude specific event
      $filter_event = " AND events.id NOT IN ($exception_list) AND stats.enabled = 1 ";
      break;
    case '2': // Include only specific event
      $filter_event = " AND events.id IN $exception_list AND stats.enabled = 1 ";
      break;
    default: $filter_event = '';
  }
  if(empty($player_id) || $player_id == null){
    $pl_option = " stats.enabled = 1 ";
  } else {
    $pl_option = "players.id = $player_id AND stats.enabled = 1 AND ";
  }
  switch($type){
    case 'is_date_range': // Within specific year range
      $distinct_event_time = "events.eventtime AS event_date, players.id AS player_id, players.name AS player_name, stats.game AS game, stats.stats AS stats";
      $date_range = "AND events.eventtime BETWEEN $event_yearly ";
      $order_by = "";
      $where = " WHERE $pl_option game > 0 AND stats.enabled = 1 $filter_event $date_range AND stats.stats NOT LIKE '{}' ";
      $result = $wpdb->get_results("SELECT $distinct_event_time $from $joins $where $order_by");
      break;
    case 'not_date_range': // Distinct year only
      $distinct_event_time = "DISTINCT YEAR(events.eventtime) AS event_date";
      $order_by = "ORDER BY event_date DESC";
      $where = " WHERE $pl_option game > 0 $filter_event AND stats.stats NOT LIKE '{}' ";
      $result = $wpdb->get_results("SELECT $distinct_event_time $from $joins $where $order_by");
      break;
    case 'career_high': // Player career high stats
//       $result = $wpdb->get_results(" SELECT st.stats FROM $dbs->stats st INNER JOIN $dbs->events ev WHERE st.player = $player_id AND st.game != 0 AND st.enabled = 1 AND st.event != 504 AND st.stats != '' AND JSON_EXTRACT(st.stats, '$.pts') != '' AND JSON_EXTRACT(st.stats, '$.pts') != 0 AND ev.enabled = 1 AND ev.org NOT IN (2) ORDER BY JSON_EXTRACT(st.stats, '$.pts') DESC LIMIT 1 ");
      $result = $wpdb->get_results(" SELECT GROUP_CONCAT(DISTINCT st.stats) stats FROM $dbs->events ev INNER JOIN $dbs->stats st WHERE st.player = $player_id AND st.game != 0 AND st.enabled = 1 AND st.event != 504 AND st.stats != '' AND JSON_EXTRACT(st.stats, '$.pts') != '' AND JSON_EXTRACT(st.stats, '$.pts') != 0 AND ev.enabled = 1 AND ev.org NOT IN (2) GROUP BY st.event, JSON_EXTRACT(st.stats, '$.pts') ORDER BY JSON_EXTRACT(st.stats, '$.pts') DESC ");
      break;
  }
  return $result;
}

function g365_date_format($year = null, $type, $arg = null){
  $and = " AND ";
  switch($type){
    case 1: // Query Given Year(2021) -> 2020-09-01 AND 2021-08-31
//       if(!is_numeric($year)) return "Need valid year to process";
      $end_date = $year."-08-31";
      $format_year = new DateTime($end_date);
      $previous_year = date( "Y",strtotime("-1 year", strtotime($format_year->format("Y-m-d"))) ); 
      $start_date = $previous_year."-09-01";
      $result = "'$start_date' $and '$end_date'";
      break;
    case 2:
      // Ex: Year(2021) -> 2021-22
      $format_eventtime = ($year."-01-01"); // Hardcode Datetime
      $current_year = date("Y"); // Ex:2021
      $format_eventtime = new DateTime($format_eventtime);
      $format_eventtime_stat = $format_eventtime->format("Y");
      $format_short_year = $format_eventtime->format("y");
      $previous_year = date( "Y",strtotime("-1 year", strtotime($format_eventtime->format("Y-m-d"))) ); 
//       $previous_year = $year - 1;
      $result = $previous_year."-".$format_short_year;
      break;
    case 3: // Current(2021) -> 2020-09-01 AND 2021-08-31
      $current_year = date("y")."-08-31"; // Set current year to year
      $result = $current_year;
      break;
    case 4:
      $begin_year = ($year - 1)."-09-01";
      $end_year = $year."-08-31";
      $result = array($begin_year, $end_year);
      break;
    case 5: // Season year
      $current_year = date("Y"); // Ex:2021
      $following_year = date( "Y", strtotime("+1 year", strtotime($current_year)) ); //2022
      $previous_year = date( "Y", strtotime("-1 year", strtotime($current_year)) ); // 2020 
      if( date("m") > "08" ){
        if($year = 'full_year'){
//           $result = $current_year."-".$following_year;
          $result = $current_year."-".substr($following_year, 2);
        }
        else if($year = 'year_only'){ // Get season year only(if month > 8 then season year is current year + 1: 2022)
          $result = $following_year;
        }else{
          $result = $current_year."-".substr($following_year, 2);
        }
      }else{
        if($year = 'full_year'){
          $result = $previous_year."-".$current_year;  
        }else{
          $result = $previous_year."-".substr($current_year, 2);
        }
      }
      break;
    case 6: // Giving year with season year format
      // Ex: Giving year 2021
      $following_year = date( "Y", strtotime("+1 year", strtotime($year)) ); //2022
      if( date("m") > "08" ){ $result = $following_year; }
      break;
    case 7: // Check event date to determine profile year
      $month = date_parse_from_format("Y-m-d", $year); 
      $month = $month["month"];
      if( $month > "08" ){
        $result = date("Y", strtotime($year))+1;
      }else{
        $result = date("Y", strtotime($year));
      }
      break;
    case 8: 
      // For pp renewal: New-> user case: 13
      // Giving year(with full date and time) to season year format(yyyy)
      // Ex: Giving year 2022-07-15
      $previous_year = date( "Y", strtotime("-1 year", strtotime($year)) ); //2023
      $month = date_parse_from_format("Y-m-d", $year); 
      $month = $month["month"];
      if( $month > "08" ){ $result = date( "Y", strtotime($year)); }
      else{ $result = $previous_year; }
      return $result;
      break;
    case 9: // Season year list. List in array base on current date
      $default_set_year = '2020'; // Default year when stats are first recorded.
      $current_date = date_parse_from_format("Y-m-d", wp_date('Y-m-d')); 
      $year = $current_date["year"];
      $month = $current_date["month"];
      $day = $current_date["day"];
      if($month > 8){ $year = $year; }else{ $year = $year - 1; }
      $result = array();
      for($i=$default_set_year; $i<=$year; $i++){ $result[] = $i; }
      if(empty($arg['result_format'])){ $result_format = ""; }
      else{ 
        $result_format = $arg['result_format']; 
        switch($result_format){
          case 'array-list':
            return $result;
            break;
          case 'season-format': // Season format: 2021-22 etc.
            break;
        }
      }
      break;
    case 10: // Passport DB Season Year
      $current_year = date("Y"); // Ex:2022
      $following_year = date( "Y", strtotime("+1 year", strtotime($current_year)) ); //2023
      $previous_year = date( "Y", strtotime("-1 year", strtotime($current_year)) ); // 2021 
      if( date("m") > "08" ){
        $result = $current_year;
      }else{
        $result = $previous_year;
      }
      break;
    case 11: // SLB API Season Years
      $current_year = date("Y");
      $following_year = date( "Y", strtotime("+1 year", strtotime($current_year)) );
      $prev_year = date( "Y", strtotime("-1 year", strtotime($current_year)) );
      if(!empty($year)){
        $prev_selected_year = $year - 1;
        $select_db_format = "'" . $prev_selected_year . "-09-01' AND '" . $year . "-08-31'";
      }
      if( date("m") > "08" ){
        $db_format = "'" . $current_year . "-09-01' AND '" . $following_year . "-08-31'";
        $current_year = $following_year;
      }else{
        $db_format = "'" . $prev_year . "-09-01' AND '" . $current_year . "-08-31'";
      }
      $year_list = array();
      for($i = $current_year; $i >= 2021; $i--){
        $year_list[$i] = ($i-1)."-".substr( ($i), -2 );
      }
      return ['season_label_format'=>$year_list, 'season_db_format'=>$db_format, 'select_db_format'=>$select_db_format];
      break;
    case 12:
      global $wpdb; $dbs = json_decode(dbs());
      !empty($arg['org_list']) ? $org_list = $arg['org_list'] : $org_list = '';
      $set_season_year = " IF( MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime) ) ";
      $event_years = $wpdb->get_results(" SELECT $set_season_year event_date FROM $dbs->events ev INNER JOIN $dbs->stats st ON ev.id = st.event WHERE ev.org IN ($org_list) AND ev.enabled = 1 AND st.game != 0 AND ev.id NOT IN (504) GROUP BY $set_season_year ORDER BY $set_season_year DESC LIMIT 1 ");
      foreach($event_years as $event_year){
        $db_format = "'" . ($event_year->event_date - 1) . "-09-01' AND '" . $event_year->event_date . "-08-31'";
        return $db_format;
      }
      break;
    case 13: 
      $result = (int)$year + 1;
      break;
    case 14: // Select years for app team standing
      $current_year = (int)date('Y');
      $current_month = (int)date('m');

      // Start year for the seasons
      $start_year = 2021;

      // Initialize an empty array for the year list
      $year_list = [];

      // If it's September or later, add the next season first (newest)
      if ($current_month >= 9) {
        $year_list[] = [
          "id" => $current_year + 1,
          "name" => $current_year . '-' . substr(($current_year + 1), -2) . ' Season'
        ];
      }

      // Create seasons from the current year to the start year
      for ($year = $current_year; $year >= $start_year; $year--) {
        $year_list[] = [
          "id" => $year,
          "name" => (($year - 1) . '-' . substr($year, -2) . ' Season')
        ];
      }

      return $year_list;
      break;
    case 15: // Get end date from select years for app team standing
      return ( ($year + 1) . '-08-31' );
      break;
    case 16: // Query Given Year(2021) -> 2020-09-01 AND 2021-08-31
            $and = " AND ";
            $today = new DateTime();
            $end_date = $year."-08-31";
            $format_year = new DateTime($end_date);

            // If today's date is beyond the end date, increment the year
            if ($today > $format_year) {
                $year++;  // Increment the year if we are past the season's end date
                $end_date = $year . "-08-31";
            }

            $previous_year = date( "Y",strtotime("-1 year", strtotime($end_date)));
            $start_date = $previous_year . "-09-01";
            $result = "'$start_date' $and '$end_date'";
      break;
    case 17: // Query Given Year(2021) -> 2020-09-01 AND 2021-08-31 for beginning of season, include previous season for first two months
            $and = " AND ";
            $today = new DateTime();
            $currentMonth = $today->format('m'); // Get the current month
            $currentDay = $today->format('d'); // Get the current day
            $currentYear = $today->format('Y');

            // Check if today's date is before or after September to decide which season we are in
            if ($currentMonth >= 9) {
                // After or in September, we are in the current year's season
                $start_year = $currentYear;
                $end_year = $currentYear + 1;
            } else {
                // Before September, we are still in the previous season
                $start_year = $currentYear - 1;
                $end_year = $currentYear;
            }

            // Calculate the current season's start and end dates
            $start_date = $start_year . "-09-01";
            $end_date = $end_year . "-08-31";

            // Collect debug information in a variable
            $debug_info = "Start Year = $start_year, End Year = $end_year, Start Date = $start_date, End Date = $end_date, Current month = $currentMonth, Current day = $currentDay, Current year = $currentYear";

            // If it's September or October, include the previous season
            if ($currentMonth == 9 || $currentMonth == 10) {
                $previous_start_date = ($start_year - 1) . "-09-01";
                $previous_end_date = $start_year . "-08-31";

                // Include both the previous and current seasons
                $result = "'$previous_start_date' $and '$previous_end_date' $and '$start_date' $and '$end_date'";
            } else {
                // Only show the current season
                $result = "'$start_date' $and '$end_date'";
            }

            // Collect more debug info
            $debug_info .= ", Resulting Date Range: $result";

            // to test whats the info getting grabbed
//             return $debug_info;
      break;
    case 18: // Get season year in format of 2023, 2024 etc
      // Get the current month
      $current_month = date('n'); // 'n' returns the month as a number without leading zeros
      $current_year = date('Y');   // 'Y' returns the current year

      // Check if the current month is September (9) or later
      if ($current_month >= 9) {
        // Return the next year
        return $current_year + 1;
      }

      // Return the current year if the month is before September
      return $current_year;
      break;
  }
  return $result;
}
// duuu
function g365_year_end_date($column_name, $year_list){
  // If giving year is the same as current year but current month is September and up, split giving year to two(2021->array(2021,2022));
//   if( empty($year_list) && !in_array($year_list) ) return 'Giving year(s) needs to be an array'; 
  $current_year = date('Y'); // Ex:2022
  $following_year = date( "Y",strtotime("+1 year", strtotime($current_year)) ); //2023
  $av_dates = $year_list;
  $av_dates = json_decode(json_encode($av_dates), true);
  $available_years = array();
//   if( !is_array($av_dates) ) return 'Need proper data type to proceed';
// Extract available years
  foreach($av_dates as $av_date){
    $available_years[] = $av_date[$column_name];
  }
// If current year is in the list and month is September or later, add the following year
  if( in_array($current_year, $available_years) && (date("m") > "08") ){
    array_unshift($available_years, $following_year);
  }
// If available years is empty and month is September or later, add the following year
  if( empty($available_years) && (date("m") > "8") ){
    array_unshift($available_years, $following_year);
  }
// If current year is not in the list and is greater than the first year in the list, add the current year
  if( !in_array($current_year, $available_years) && ($current_year > $available_years[0]) ){
    array_unshift($available_years, $current_year);
  }
  $new_list = array_unique($available_years);
  return $new_list;
}
function g365_club_team_stat($event_id = null, $team_id = null, $org_id, $opponent_id = null, $year = null, $type, $arg = null){
  global $wpdb; $default_pct = '0.59';
  $dbs = json_decode(dbs());
  $date_format = g365_date_format($year, 1); 
  $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
  $filter_game = " WHERE orgs.id = $org_id AND events.eventtime BETWEEN $date_format ";
  $order_by = " ORDER BY events.eventtime DESC ";
  $include_field = " rosters.id AS roster_id, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team  ) AS opponent_id, ";
  $case_game_result = " (CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END) AS game_result, (CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END) AS game_result_label ";
  
  switch($type){
    case 1: // List of all program games
      if(!empty($event_id)){ // Get games on certain event
        $filter_game = " $filter_game AND events.id = $event_id ";
      }
      elseif(empty($event_id)){// Get all games by Org
        $filter_game = $filter_game;      
      }else{
        echo "Missing event id";
      }
      $order_by = " $order_by, teams.level ASC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 2: // Event only
      $include_field = "DISTINCT events.id AS event_id, events.eventtime AS event_time, events.name AS event_name, events.org AS event_org";
      $group_by = " GROUP BY events.id ";
      return $wpdb->get_results("SELECT $include_field $joins $filter_game $group_by $order_by");
      break;
    case 3: // Opponent only
      $include_field = " DISTINCT teams.search_list AS team_name ";
      $filter_game = " WHERE rosters.id = $opponent_id ";
      return $wpdb->get_results("SELECT $include_field $joins $filter_game");
      break;
    case 4: // Year only. Need Org id only
      $include_field = " DISTINCT YEAR(events.eventtime) AS event_date ";
      $filter_game = " WHERE orgs.id = $org_id ";
      $group_by = " GROUP BY events.eventtime ";
      $order_by = " ORDER BY YEAR(events.eventtime) DESC ";
      return $wpdb->get_results("SELECT $include_field $joins $group_by $order_by");
      break;
    case 5: // With team id
      if(empty($team_id)) return 'Need team id to process';
      $filter_game = " WHERE orgs.id = $org_id AND teams.id = $team_id AND games.start_time BETWEEN $date_format ";
      $order_by = " $order_by, teams.level ASC, games.start_time DESC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 6: // Overall team/club statistics. Need org id only
      $filter_game = " WHERE orgs.id = $org_id AND games.start_time BETWEEN $date_format ";
      $order_by = " $order_by, teams.level ASC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 7: // Club team standing
//       echo("<script>console.log('post_year=2023: " . $arg['post_year'] . " lv_key=17: " . $get_lv_key . " cts=empty: " . $_POST['cts_type'] . " is_dcp=empty: " . $is_dcp . "is_dcp_ev=empty: " . $arg['is_dcp_ev'] . " post_ros_dvs=empty: " . $arg['post_ros_dvs'] . " is_unlocked_dcp_ev=empty: " . $is_unlocked_dcp_ev . " brand_sel=the-stage: " . $brand_sel . " ');</script>");
//       $g365_club_team_stat = g365_club_team_stat(null, null, null, null, $arg['post_year'], 7, [$get_lv_key, null, 'is_standing_only', 'by-level', $_POST['cts_type'], 'is_dcp'=>$is_dcp, 'is_dcp_ev'=>$arg['is_dcp_ev'], 'post_ros_dvs'=>$arg['post_ros_dvs'], 'is_unlocked_dcp_ev'=>$is_unlocked_dcp_ev]);
  // Call works :    Array ( $event_id[0] => $team_id[1] => 14422 [2] => [3] => [4] => 2024 [5] => 7 [6] => Array ( [0] => 2024 [1] => 3305607 [2] => is_box_score_only ) )
  // Call no works : Array ( $event_id[0] => $team_id[1] => 14422 [2] => [3] => [4] => 2024 [5] => 7 [6] => Array ( [0] => 2024 [1] => 3247549 [2] => is_box_score_only ) )
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
      }else{
//         echo("<script>console.log('testing0');</script>");
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
//         echo("<script>console.log('testing1');</script>");
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\",  \"team_logo\": \"',IF(org_logo IS NULL, '', org_logo),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
//           echo("<script>console.log('testing3');</script>");
          $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          
//           g365_club_team_stat(null, null, null, null, $arg['post_year'], 10, [$get_lv_key, null, 'is_standing_only', 'by-level', $_POST['cts_type'], 'is_dcp'=>$is_dcp, 'is_dcp_ev'=>$arg['is_dcp_ev'], 'post_ros_dvs'=>$arg['post_ros_dvs'], 'is_unlocked_dcp_ev'=>$is_unlocked_dcp_ev, 'brand_selected'=>$brand_sel]);
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";
          $tb_2_fields = " team_id, team_level, org_logo, org $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";
          $tb_1_fields = " inner_tb.* ";
          if(!empty($arg[3])){
//             echo("<script>console.log('testing5');</script>");
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           echo("<script>console.log('testing4');</script>");
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT * FROM ( SELECT * FROM ( SELECT * FROM ( SELECT * $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");        
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          $query = "SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;";
          //print_r($query);
          $results = $wpdb->get_results($query);
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
    case 8:
      // 0: game id. 1: team id.
//       !empty($arg[0]) ? $game_id = 'AND games.id = ' . $arg[0] : $game_id = '';
//       !empty($arg[1]) ? $team_id = 'AND teams.id = ' . $arg[1]: $team_id = '';
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $include_field = " orgs.profile_img AS org_logo, rosters.id AS roster_id, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, rosters.level AS level_of_play, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ";
      $filter_game = " WHERE games.start_time BETWEEN $date_format AND games.id = $arg[0] AND teams.id = $arg[1] ";
//       $filter_game = " WHERE games.start_time BETWEEN $date_format $game_id $team_id ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game");
      break;
    case 9:
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
      }else{
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";
          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";
          $tb_1_fields = " inner_tb.* ";
          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");        
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
    case 10:
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $brand_selected = empty($arg['brand_selected']) ? '' : $arg['brand_selected'];
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
        
      }else{
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
        
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      
      if ($brand_selected == "the-stage") {
          $default_pct = '0.49';
          $org_id = '3'; // Assuming '3' is the ID for "The Stage"
          
      }else if ($brand_selected == "grassroots-365"){
//           $default_pct = '0.49';
          $org_id = '3191'; // Assuming '7729' is the ID
      }else if ($brand_selected == "scholastic-series"){
          $default_pct = '0.49';
          $org_id = '7165'; // Assuming '7165' is the ID
      }else if ($brand_selected == "hype-her-hoops-circuit"){
          $default_pct = '0.49';
          $org_id = '7164'; // Assuming '7164' is the ID
      }else if ($brand_selected == "breakthrough-circuit"){
          $default_pct = '0.49';
          $org_id = '7729'; // Assuming '7729' is the ID
      }else {
          $org_id = null;

      }
      
      if (!is_null($org_id)) {
          $additionalCondition = "AND events.org = $org_id ";
          $box_score_condi = " teams.level IN ($arg[0]) $additionalCondition AND start_time BETWEEN $date_format ";
        
      } else {
          $additionalCondition = "";
          
      }
      
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          
          if (is_null($org_id)) {
              //if no brand then stick to the OG
              $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          }else{
              //if there is a brand then use else or if else to change the number of games required to display the team in the standings
              if ($brand_selected == "the-stage") {
                $num_game_by_month = 0;
              }else if ($brand_selected == "scholastic-series"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "hype-her-hoops-circuit"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "breakthrough-circuit"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "grassroots-365"){
                $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
              }
          }
          
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";

          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";

          $tb_1_fields = " inner_tb.* ";

          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $additionalCondition $is_dcp  $ros_division AND games.start_time BETWEEN $date_format ";
            
            
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
                
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
                
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
    case 11:
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $brand_selected = empty($arg['brand_selected']) ? '' : $arg['brand_selected'];
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
        
      }else{
        $box_score_condi = " teams.level IN ($arg[6]) $is_dcp AND start_time BETWEEN $date_format ";
        
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      
      if ($brand_selected == "the-stage") {
          $default_pct = '0.49';
          $org_id = '3'; // Assuming '3' is the ID for "The Stage"
          
      }else if ($brand_selected == "grassroots-365"){
//           $default_pct = '0.49';
          $org_id = '3191'; // Assuming '7729' is the ID
      }else if ($brand_selected == "scholastic-series"){
          $default_pct = '0.49';
          $org_id = '7165'; // Assuming '7165' is the ID
      }else if ($brand_selected == "hype-her-hoops-circuit"){
          $default_pct = '0.49';
          $org_id = '7164'; // Assuming '7164' is the ID
      }else if ($brand_selected == "breakthrough-circuit"){
          $default_pct = '0.49';
          $org_id = '7729'; // Assuming '7729' is the ID
      }else {
          $org_id = null;

      }
      
      $girls_division_mapping = [
          "Varsity Girls" => 47,
          "JV Girls" => 46,
          "Frosh/Soph Girls" => 45,
          "Girls 8th Grade" => 44,
          "Girls 7th Grade" => 43,
          "Girls 6th Grade" => 42,
          "Girls 5th Grade" => 41,
          "Girls 4th Grade" => 40,
          "15U/9th Grade" => 15
      ];
      
      if (!is_null($org_id)) {
          $additionalCondition = "AND events.org = $org_id ";
          $level_picked = $arg['select_group'];
//           print_r($arg);
          // Check if $level_picked contains a comma
          if (strpos($level_picked, ',') !== false) {
              // If it contains a comma, treat it as an array and use it directly
              $box_score_condi = " teams.level IN ($level_picked) $additionalCondition AND start_time BETWEEN $date_format ";
          } else {
              // If it does not contain a comma, check if the value is in the mapping
//               echo "here $level_picked <br>";
              if (isset($girls_division_mapping[$level_picked])) {
                  // Use the mapped numeric value
                  $numeric_part = $girls_division_mapping[$level_picked];
                  $box_score_condi = " teams.level IN ($numeric_part) $additionalCondition AND start_time BETWEEN $date_format ";
              } else {
                  // If no numeric part is found in the mapping, use regex to extract the numeric part
                  if (preg_match('/^\d+/', $level_picked, $matches)) {
                      $numeric_part = $matches[0];
                      $box_score_condi = " teams.level IN ($numeric_part) $additionalCondition AND start_time BETWEEN $date_format ";
                  } else {
                      // If no numeric part is found, use the original value
                      $box_score_condi = " teams.level IN ($level_picked) $additionalCondition AND start_time BETWEEN $date_format ";
                  }
              }
          }
      } else {
          $additionalCondition = "";
      }
     
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          
          if (is_null($org_id)) {
              //if no brand then stick to the OG
              $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          }else{
              //if there is a brand then use else or if else to change the number of games required to display the team in the standings
              if ($brand_selected == "the-stage") {
                $num_game_by_month = 0;
              }else if ($brand_selected == "scholastic-series"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "hype-her-hoops-circuit"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "breakthrough-circuit"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "grassroots-365"){
                $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
//                 $num_game_by_month = 0;
              }
          }
          
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";

          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";

          $tb_1_fields = " inner_tb.* ";
          
          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[6]) $additionalCondition $is_dcp  $ros_division AND games.start_time BETWEEN $date_format ";
            
            
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
//           echo "SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ";
          $querystring = "SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ";
          
//           $querystring = str_replace("teams.level IN () AND", "", $querystring);
//           $querystring = str_replace("events.org = 3191 AND", "", $querystring);
             $results = $wpdb->get_results($querystring);
//           echo "debug -->";
//           print_r($querystring);
//           if(empty($results)) {echo "No Games Available For This Season";}
//           die();
    
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){

                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
                
              }else{
                
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
                
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      
        
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
    case 12: // Team standing for mobile app
      if(empty($arg['select_brand'])){ echo 'Need select_brand to continue'; exit; }else{ $select_brand = $arg['select_brand']; }
      if(empty($arg['select_year'])){ echo 'Need select_year to continue'; exit; }else{ $select_year = g365_date_format($arg['select_year'], 1); $end_date = g365_date_format($arg['select_year'], 1); }
      if(empty($arg['win_loss_percent_cutoff'])){ echo 'Need win_loss_percent_cutoff to continue'; exit; }else{ $win_loss_percent_cutoff = $arg['win_loss_percent_cutoff']; }
      if(empty($arg['max_results_per_division'])){ echo 'Need max_results_per_division to continue'; exit; }else{ $max_results_per_division = $arg['max_results_per_division']; }
      if(empty($arg['show_girls'])){ echo 'Need to clarify if it includes girls or not to continue'; exit; }else{ $show_girls = $arg['show_girls']; }
      if(empty($arg['division'])){ echo 'Need division to continue'; exit; }else{ $division = $arg['division']; }
      if(empty($arg['level_of_play'])){ echo 'Need level_of_play to continue'; exit; }else{ $level_of_play = $arg['level_of_play']; }
      
      $top_team_standing = $wpdb->get_results("
        -- Get all events for the given brand within the current season
        WITH CTE_Events AS
        (
            SELECT id, name
            FROM $dbs->events
            WHERE
              org = $select_brand
              AND eventtime BETWEEN $select_year
              AND enabled = 1
              AND type NOT IN (5, 6, 7, 8) -- exclude training, college placement service, all star game, and passport membership
        ),

        -- Get all games associated with the events in the current range
        CTE_Games AS
        (
            SELECT *
            FROM $dbs->games
            WHERE
                start_time BETWEEN $select_year
                AND event_id IN (SELECT id FROM CTE_Events)
        ),

        -- Get all the games with points
        CTE_GamesWL AS
        (
            SELECT 
                event_id,
                home_team AS roster_id,
                home_team_score AS points_for,
                away_team_score AS points_against,
                home_team_score > away_team_score AS is_win
            FROM CTE_Games
            UNION ALL
            SELECT 
                event_id,
                away_team AS roster_id,
                away_team_score AS points_for,
                home_team_score AS points_against,
                home_team_score < away_team_score AS is_win
            FROM CTE_Games
            WHERE home_team_score IS NOT NULL AND away_team_score IS NOT NULL
        ),

        -- Total the W/L counts for each
        CTE_WLByRoster AS
        (
            SELECT 
                roster_id,
                SUM(CASE WHEN is_win = 1 THEN 1 ELSE 0 END) AS total_wins,
                SUM(CASE WHEN is_win = 0 THEN 1 ELSE 0 END) AS total_losses,
                SUM(points_for) AS total_points_for,
                SUM(points_against) AS total_points_against,
                COUNT(*) AS games_played
            FROM CTE_GamesWL
            WHERE points_for IS NOT NULL AND points_against IS NOT NULL
            GROUP BY roster_id
        ),

        -- Get roster details for the organization and team, including level of play
        CTE_RosterDetails AS
        (
            SELECT WLBR.*, R.org, R.team, CASE WHEN '$level_of_play' = 'All' THEN 'All' ELSE R.division END AS level_of_play
            FROM CTE_WLByRoster AS WLBR
            LEFT JOIN $dbs->rosters AS R
            ON WLBR.roster_id = R.id
        ),

        -- Get the org name and profile image
        CTE_OrgDetails AS
        (
            SELECT R.*, O.name, O.profile_img, O.abbreviation AS org_nickname
            FROM CTE_RosterDetails AS R
            LEFT JOIN $dbs->orgs AS O
            ON R.org = O.id
        ),

        -- Get the team division and name
        CTE_Team AS
        (
            SELECT O.*, T.level as division, T.name AS team_name
            FROM CTE_OrgDetails AS O
            LEFT JOIN $dbs->teams AS T
            ON O.team = T.id
        ),

        -- Calculate win percentage
        CTE_WinPercentage AS
        (
            SELECT
                org AS org_id,
                team as team_id,
                level_of_play,
                division,
                name as team_name, 
                team_name as team_description,
                CONCAT(name, ' ', division, 'U ', team_name) AS full_team_name,
                IF(org_nickname IS NOT NULL AND org_nickname != '', CONCAT(org_nickname, ' ', division, 'U ', team_name), null) AS team_nickname,
                profile_img as org_logo,
                SUM(total_wins) AS total_wins,
                SUM(total_losses) AS total_losses,
                CASE 
                    WHEN (SUM(total_wins) + SUM(total_losses)) = 0 THEN 0 
                    ELSE SUM(total_wins) / NULLIF(SUM(total_wins) + SUM(total_losses), 0) 
                END AS win_percentage,
                SUM(total_points_for) / SUM(games_played) AS ppg,
                SUM(total_points_against) / SUM(games_played) AS opp_ppg
            FROM CTE_Team
            GROUP BY name, level_of_play, team_name, org, profile_img, team, division
        ),

        -- Apply filters for some business logic (show/hide girls, only include results with minimum amount of games played)
        CTE_FilteredResults AS
        (
            SELECT *,
                CASE 
                    WHEN '$division' = 'All' THEN 1 ELSE division = '$division' END AS division_match
            FROM CTE_WinPercentage
            WHERE
                win_percentage >= $win_loss_percent_cutoff
                AND (
                    CASE
                        WHEN $show_girls = false THEN division NOT IN (39, 40, 41, 42, 43, 44, 45, 46, 47)
                        ELSE 1=1
                    END
                )
                AND total_wins + total_losses >= (
                    CASE
                        WHEN CURDATE() > $end_date THEN 20 -- Use max 20 for past seasons
                        WHEN MONTH(CURDATE()) IN (9, 10, 11) THEN 4
                        WHEN MONTH(CURDATE()) IN (12, 1, 2) THEN 8
                        WHEN MONTH(CURDATE()) IN (3, 4, 5) THEN 16
                        WHEN MONTH(CURDATE()) IN (6, 7) THEN 18
                        WHEN MONTH(CURDATE()) = 8 THEN 20
                        ELSE 0
                    END
                )
        ),

        -- Rank results to be able to filter results count for each division
        CTE_RankedFilteredTeams AS
        (
            SELECT *,
                ROW_NUMBER() OVER (PARTITION BY division ORDER BY win_percentage DESC) AS result_num
            FROM CTE_FilteredResults
            WHERE division_match = 1
            AND UPPER(level_of_play) COLLATE utf8mb4_0900_ai_ci = UPPER('$level_of_play') COLLATE utf8mb4_0900_ai_ci
        )

        -- Final select after applying row number filtering
        SELECT org_id, team_id, level_of_play, division, full_team_name, team_nickname, org_logo, total_wins, total_losses, win_percentage, ppg, opp_ppg
        FROM CTE_RankedFilteredTeams
        WHERE result_num <= $max_results_per_division
        ORDER BY division DESC, win_percentage DESC;
      ");
      return $top_team_standing;
      break;
    case 13: // Team standing game results for mobile app
      if(empty($arg['select_brand'])){ echo 'Need select_brand to continuep'; exit; }else{ $select_brand = $arg['select_brand']; }
      if(empty($arg['select_year'])){ echo 'Need select_year to continue'; exit; }else{ $select_year = g365_date_format($arg['select_year'], 1); $end_date = g365_date_format($arg['select_year'], 1); }
      if(empty($arg['team_org'])){ echo 'Need team_org to continue'; exit; }else{ $team_org = $arg['team_org']; }
      if(empty($arg['team_id'])){ echo 'Need team_id to continue'; exit; }else{ $team_id = $arg['team_id']; }

      $ts_game_results = $wpdb->get_results("
        -- Get all events for the given brand within the current season
        WITH CTE_Events AS (
            SELECT 
                id AS event_id, 
                name AS event_name
            FROM 
                $dbs->events
            WHERE 
                org = $select_brand
                AND eventtime BETWEEN $select_year
                AND enabled = 1
                AND type NOT IN (5, 6, 7, 8)
        ),

        -- Get all the rosters for the given organization and team
        CTE_Rosters AS (
            SELECT 
                E.event_id, 
                E.event_name, 
                R.id AS roster_id, 
                R.org, 
                R.team AS team_id
            FROM 
                CTE_Events AS E
            LEFT JOIN 
                $dbs->rosters AS R
            ON 
                E.event_id = R.event
            WHERE 
                R.org = $team_org
                AND R.team = $team_id
        ),

        -- Get all the games for the rosters
        CTE_Games AS (
            SELECT 
                G.id game_id,
                R.event_id, 
                R.event_name, 
                R.org, 
                R.team_id,
                CASE WHEN R.roster_id = G.home_team THEN G.home_team ELSE G.away_team END AS roster_id,
                CASE WHEN R.roster_id = G.home_team THEN G.home_team_score ELSE G.away_team_score END AS score,
                CASE WHEN R.roster_id = G.home_team THEN G.away_team ELSE G.home_team END AS opp_roster_id,
                CASE WHEN R.roster_id = G.home_team THEN G.away_team_score ELSE G.home_team_score END AS opp_score
            FROM 
                CTE_Rosters AS R
            LEFT JOIN 
                $dbs->games AS G
            ON 
                R.roster_id = G.home_team OR R.roster_id = G.away_team
            WHERE home_team_score IS NOT NULL and away_team_score IS NOT NULL
        ),

        -- Get outcome of game (W/L) for each game
        CTE_WL AS (
            SELECT 
                *, 
                CASE WHEN score > opp_score THEN 'W' ELSE 'L' END AS outcome
            FROM 
                CTE_Games
        ),

        -- Get the organization name and profile image
        CTE_OrgDetails AS (
            SELECT 
                WL.*, 
                O.name, 
                O.profile_img
            FROM 
                CTE_WL AS WL
            LEFT JOIN 
                $dbs->orgs AS O
            ON 
                WL.org = O.id
        ),

        -- Get the opponents roster details
        CTE_OppRoster AS (
            SELECT 
                OD.*, 
                R.org AS opp_org_id, 
                R.team AS opp_team_id
            FROM 
                CTE_OrgDetails AS OD
            LEFT JOIN 
                $dbs->rosters AS R
            ON 
                OD.opp_roster_id = R.id
        ),

        -- Get the opponents organization details
        CTE_OppOrgDetails AS (
            SELECT 
                R.*, 
                O.name AS opp_name, 
                O.profile_img AS opp_profile_img
            FROM 
                CTE_OppRoster AS R
            LEFT JOIN 
                $dbs->orgs AS O
            ON 
                R.opp_org_id = O.id
        ),

        -- Get the team level and name
        CTE_Team AS (
            SELECT 
                OOD.*, 
                T.level, 
                T.name AS team_name
            FROM 
                CTE_OppOrgDetails AS OOD
            LEFT JOIN 
                $dbs->teams AS T
            ON 
                OOD.team_id = T.id
        ),

        -- Get the opponent team level and name
        CTE_OppTeam AS (
            SELECT 
                CTE_Team.*, 
                T.level AS opp_level, 
                T.name AS opp_team_name
            FROM 
                CTE_Team
            LEFT JOIN 
                $dbs->teams AS T
            ON 
                CTE_Team.opp_team_id = T.id
        )

        -- Final query to combine everything
        SELECT 
            event_id,
            game_id,
            event_name,
            org,
            roster_id,
            score,
            opp_org_id,
            opp_roster_id,
            opp_score,
            outcome,
            level,
            CONCAT(name, ' ', level, 'U ', team_name) AS full_team_name,
            profile_img AS org_logo,
            CONCAT(opp_name, ' ', opp_level, 'U ', opp_team_name) AS opp_full_team_name,
            opp_profile_img AS opp_org_logo
        FROM 
            CTE_OppTeam;
      ");
      return $ts_game_results;
      break;
    case 14: // loads the standings
      if(empty($arg['select_brand'])){ echo 'Need select_brand to continue'; exit; }else{ $select_brand = $arg['select_brand']; }
      if(empty($arg['select_year'])){ echo 'Need select_year to continue'; exit; }else{ $select_year = g365_date_format($arg['select_year'], 1);          $end_date = $arg['select_year'].'-08-31'; //g365_date_format($arg['select_year'], 1); this is not working in the sql would make it like "CASE WHEN CURDATE() > '2023-09-01' AND '2024-08-31'" which is incorrect
      }
      if(empty($arg['win_loss_percent_cutoff'])){ echo 'Need win_loss_percent_cutoff to continue'; exit; }else{ $win_loss_percent_cutoff = $arg['win_loss_percent_cutoff']; }
      if(empty($arg['max_results_per_division'])){ echo 'Need max_results_per_division to continue'; exit; }else{ $max_results_per_division = $arg['max_results_per_division']; }
      if(empty($arg['show_girls'])){ echo 'Need to clarify if it includes girls or not to continue'; exit; }else{ $show_girls = $arg['show_girls']; }
      if(empty($arg['division'])){ echo 'Need division to continue'; exit; }else{ $division = $arg['division']; }
      if(empty($arg['level_of_play'])){ echo 'Need level_of_play to continue'; exit; }else{ $level_of_play = $arg['level_of_play']; }
      // print_r($end_date);
      $query = ("
        
        WITH CTE_Events AS
        (
            SELECT id, name
            FROM $dbs->events
            WHERE
              org = (
                  SELECT id
                  FROM $dbs->orgs
                  WHERE nickname = '$select_brand'
              )
              AND eventtime BETWEEN $select_year
              AND enabled = 1
              AND type NOT IN (5, 6, 7, 8) 
        ),

        
        CTE_Games AS
        (
            SELECT *
            FROM $dbs->games
            WHERE
                start_time BETWEEN $select_year
                AND event_id IN (SELECT id FROM CTE_Events)
        ),

        
        CTE_GamesWL AS
        (
            SELECT 
                event_id,
                home_team AS roster_id,
                home_team_score AS points_for,
                away_team_score AS points_against,
                home_team_score > away_team_score AS is_win
            FROM CTE_Games
            UNION ALL
            SELECT 
                event_id,
                away_team AS roster_id,
                away_team_score AS points_for,
                home_team_score AS points_against,
                home_team_score < away_team_score AS is_win
            FROM CTE_Games
            WHERE home_team_score IS NOT NULL AND away_team_score IS NOT NULL
        ),

        
        CTE_WLByRoster AS
        (
            SELECT 
                roster_id,
                SUM(CASE WHEN is_win = 1 THEN 1 ELSE 0 END) AS total_wins,
                SUM(CASE WHEN is_win = 0 THEN 1 ELSE 0 END) AS total_losses,
                SUM(points_for) AS total_points_for,
                SUM(points_against) AS total_points_against,
                COUNT(*) AS games_played
            FROM CTE_GamesWL
            WHERE points_for IS NOT NULL AND points_against IS NOT NULL
            GROUP BY roster_id
        ),

        
        CTE_RosterDetails AS
        (
            SELECT WLBR.*, R.org, R.team, CASE WHEN '$level_of_play' = 'All' THEN 'All' ELSE R.division END AS level_of_play
            FROM CTE_WLByRoster AS WLBR
            LEFT JOIN $dbs->rosters AS R
            ON WLBR.roster_id = R.id
        ),

        
        CTE_OrgDetails AS
        (
            SELECT R.*, O.name, O.profile_img
            FROM CTE_RosterDetails AS R
            LEFT JOIN $dbs->orgs AS O
            ON R.org = O.id
        ),

        
        CTE_Team AS
        (
            SELECT O.*, T.level as division, T.name AS team_name
            FROM CTE_OrgDetails AS O
            LEFT JOIN $dbs->teams AS T
            ON O.team = T.id
        ),

        
        CTE_WinPercentage AS
        (
            SELECT
                org AS org_id,
                team as team_id,
                level_of_play,
                division,
                name as team_name, 
                team_name as team_description,
                CONCAT(name, ' ', division, 'U ', team_name) AS full_team_name,
                profile_img as org_logo,
                SUM(total_wins) AS total_wins,
                SUM(total_losses) AS total_losses,
                CASE 
                    WHEN (SUM(total_wins) + SUM(total_losses)) = 0 THEN 0 
                    ELSE SUM(total_wins) / NULLIF(SUM(total_wins) + SUM(total_losses), 0) 
                END AS win_percentage,
                SUM(total_points_for) / SUM(games_played) AS ppg,
                SUM(total_points_against) / SUM(games_played) AS opp_ppg
            FROM CTE_Team
            GROUP BY name, level_of_play, team_name, org, profile_img, team, division
        ),

        
        CTE_FilteredResults AS
        (
            SELECT *,
                CASE 
                    WHEN '$division' = 'All' THEN 1 ELSE division = '$division' END AS division_match
            FROM CTE_WinPercentage
            WHERE
                win_percentage >= '$win_loss_percent_cutoff'
                AND (
                    CASE
                        WHEN $show_girls = false THEN division NOT IN (39, 40, 41, 42, 43, 44, 45, 46, 47)
                        ELSE 1=1
                    END
                )
                AND total_wins + total_losses >= (
                    CASE
                        WHEN CURDATE() > '$end_date' THEN 20
                        WHEN MONTH(CURDATE()) IN (9, 10, 11) THEN 4
                        WHEN MONTH(CURDATE()) IN (12, 1, 2) THEN 8
                        WHEN MONTH(CURDATE()) IN (3, 4, 5) THEN 16
                        WHEN MONTH(CURDATE()) IN (6, 7) THEN 18
                        WHEN MONTH(CURDATE()) = 8 THEN 20
                        ELSE 0
                    END
                )
        ),
        CTE_RankedFilteredTeams AS
        (
            SELECT *,
                ROW_NUMBER() OVER (PARTITION BY division ORDER BY win_percentage DESC) AS result_num
            FROM CTE_FilteredResults
            WHERE division_match = 1
            AND UPPER(level_of_play) COLLATE utf8mb4_0900_ai_ci = UPPER('$level_of_play') COLLATE utf8mb4_0900_ai_ci
        )
        SELECT *, org_id, team_id, level_of_play, division, full_team_name, org_logo, total_wins, total_losses, win_percentage, ppg, opp_ppg
        FROM CTE_RankedFilteredTeams
        WHERE result_num <= '$max_results_per_division'
        ORDER BY division DESC, win_percentage DESC;
      ");
      return $wpdb->get_results($query);
      break;
    case 15: // loaded with ajax. so returns HTML.
      if(empty($arg['select_brand'])){ echo 'Need select_brand to continue'; exit; }else{ $select_brand = $arg['select_brand']; }
      if(empty($arg['selected_org_id'])){ echo 'Need selected_org_id to continue'; exit; }else{ $selected_org_id = $arg['selected_org_id']; }
      if(empty($arg['select_year'])){ echo 'Need select_year to continue'; exit; }else{ $select_year = $arg['select_year']; $select_year_range = g365_date_format($arg['select_year'], 1); }
      if(empty($arg['team_org'])){ echo 'Need team_org to continue'; exit; }else{ $team_org = $arg['team_org']; }
      if(empty($arg['team_id'])){ echo 'Need team_id to continue'; exit; }else{ $team_id = $arg['team_id']; }
      if(empty($arg['team_org_id'])){ echo 'Need team_org_id to continue'; exit; }else{ $team_org_id = $arg['team_org_id']; }
      if(empty($arg['club_team_data_list'])){ echo 'Need club_team_data_list to continue'; exit; }else{ $club_team_data_list = $arg['club_team_data_list']; }
      $query = "
      -- Get all events for the given brand within the current season
      WITH CTE_Events AS (
          SELECT 
              id AS event_id, 
              name AS event_name,
              eventtime AS event_time
          FROM 
              $dbs->events
          WHERE 
              org = $selected_org_id
              AND eventtime BETWEEN $select_year_range
              AND enabled = 1
              AND type NOT IN (5, 6, 7, 8)
      ),

      -- Get all the rosters for the given organization and team
      CTE_Rosters AS (
          SELECT 
              E.event_id, 
              E.event_name,
              E.event_time,
              R.id AS roster_id, 
              R.org, 
              R.team AS team_id,
              R.level AS level
          FROM 
              CTE_Events AS E
          LEFT JOIN 
              $dbs->rosters AS R
          ON
              E.event_id = R.event
          WHERE 
              R.org = $team_org_id
              AND R.team = $team_id
      ),

      -- Get all the games for the rosters
      CTE_Games AS (
          SELECT 
              G.id AS game_id,
              R.event_id, 
              R.event_name,
              R.event_time,
              R.org, 
              R.team_id,
              R.level,
              CASE WHEN R.roster_id = G.home_team THEN G.home_team ELSE G.away_team END AS roster_id,
              CASE WHEN R.roster_id = G.home_team THEN G.home_team_score ELSE G.away_team_score END AS score,
              CASE WHEN R.roster_id = G.home_team THEN G.away_team ELSE G.home_team END AS opp_roster_id,
              CASE WHEN R.roster_id = G.home_team THEN G.away_team_score ELSE G.home_team_score END AS opp_score
          FROM 
              CTE_Rosters AS R
          LEFT JOIN 
              $dbs->games AS G
          ON 
              R.roster_id = G.home_team OR R.roster_id = G.away_team
          WHERE home_team_score IS NOT NULL and away_team_score IS NOT NULL
      ),

      -- Get outcome of game (W/L) for each game
      CTE_WL AS (
          SELECT 
              *, 
              CASE WHEN score > opp_score THEN 'W' ELSE 'L' END AS outcome
          FROM 
              CTE_Games
      ),

      -- Get the organization name and profile image
      CTE_OrgDetails AS (
          SELECT 
              WL.*, 
              O.name, 
              O.abbreviation, 
              O.profile_img
          FROM 
              CTE_WL AS WL
          LEFT JOIN 
              $dbs->orgs AS O
          ON 
              WL.org = O.id
      ),

      -- Get the opponents roster details
      CTE_OppRoster AS (
          SELECT 
              OD.*, 
              R.org AS opp_org_id, 
              R.team AS opp_team_id,
              R.level AS opp_level
          FROM 
              CTE_OrgDetails AS OD
          LEFT JOIN 
              $dbs->rosters AS R
          ON 
              OD.opp_roster_id = R.id
      ),

      -- Get the opponents organization details
      CTE_OppOrgDetails AS (
          SELECT 
              R.*, 
              O.name AS opp_name, 
              O.abbreviation AS opp_abbreviation, 
              O.profile_img AS opp_profile_img
          FROM 
              CTE_OppRoster AS R
          LEFT JOIN 
              $dbs->orgs AS O
          ON 
              R.opp_org_id = O.id
      ),

      -- Get the team level and name
      CTE_Team AS (
          SELECT 
              OOD.*, 
              T.name AS team_name
          FROM 
              CTE_OppOrgDetails AS OOD
          LEFT JOIN 
              $dbs->teams AS T
          ON 
              OOD.team_id = T.id
      ),

      -- Get the opponent team name
      CTE_OppTeam AS (
          SELECT 
              CTE_Team.*,
              T.name AS opp_team_name
          FROM 
              CTE_Team
          LEFT JOIN 
              $dbs->teams AS T
          ON 
              CTE_Team.opp_team_id = T.id
      )

      -- Final query to combine everything
      SELECT 
          game_id,
          event_id,
          event_name,
          event_time,
          org,
          roster_id,
          score,
          opp_org_id,
          opp_roster_id,
          opp_score,
          outcome,
          level,
          CONCAT(CASE WHEN abbreviation IS NOT NULL AND LENGTH(abbreviation) > 0 THEN abbreviation ELSE name END, ' ', level, 'U', CASE WHEN team_name IS NOT NULL THEN CONCAT(' ', team_name) ELSE '' END) AS full_team_name,
          profile_img AS org_logo,
          CONCAT(CASE WHEN opp_abbreviation IS NOT NULL AND LENGTH(opp_abbreviation) > 0 THEN opp_abbreviation ELSE opp_name END, ' ', opp_level, 'U', CASE WHEN opp_team_name IS NOT NULL THEN CONCAT(' ', opp_team_name) ELSE '' END) AS opp_full_team_name,
          opp_profile_img AS opp_org_logo
      FROM 
          CTE_OppTeam ORDER BY event_time DESC;
      ";
      
      
      // Small debug.
//        $dbs = ['events' => $dbs->events, 'rosters' => $dbs->rosters, 'games' => $dbs->games, 'orgs' => $dbs->orgs, 'teams' => $db->teams];
//        foreach($dbs as $db_name => $db){
//          echo "<br><br><br>$db_name"; 
//          $r = $wpdb->get_results("SELECT * from $db limit 2;");
//          print_r(json_encode($r));
//        }
       $placeholder_img = 'g365_blank-placeholder_400x300.png';
       $ts_game_results = $wpdb->get_results($query);
      
       
      // Check for errors
//       if ($wpdb->last_error) {
//           // Display the error message
//           echo "Database error: " . $wpdb->last_error;

//           // Optionally, print the full error message with more details
//           $wpdb->print_error();
//       } else {
//           // If no error, you can proceed with handling the query results
//           print_r(json_encode($ts_game_results));
//       }
//       echo "\n\nResults: <br><br>";
//       echo " \n\n<br><br>";
//       echo "query: <br><br>";
//       print_r($query);
      
      // not : org nickname is missing...
      //print_r($club_team_data_list);
      //print_r(json_encode($ts_game_results));
      
      
       // Generate the HTML that the ajax query hitting case 15 needs.
      ?><tr id="<?php echo $team_id ?>-result_box" class="result_box">
        <td colspan="6">
          <span class="close_vr_btn small-margin-right" id="<?php echo $team_id; ?>" onClick="view_result(event, <?php echo $team_id; ?>)">Close</span><!-- Triggers removal from DOM -->
          <div class="grid-x cts_box_score small-12 medium-12 large-12">
            <?php
              $box_score = $ts_game_results; // json_decode('['.$box_score.']', true);
              $group_by_events = array();
//              print_r($ts_game_results);
            
             foreach($box_score as $data_list){
               $group_by_events[$data_list->event_name][] = $data_list;
             }
      
            // print_r(json_encode($group_by_events));
            
             foreach($group_by_events as $index => $group_by_event): //a

               echo '<h5 class="small-12 medium-12 large-12 text-center" style="text-decoration:underline">'.$index.'</h5>';
               foreach($group_by_event as $boxscore_list): //b
                 if(!empty($boxscore_list->full_team_name)){ $full_team_name = $boxscore_list->full_team_name; }else{ $full_team_name = ''; }
                 if(!empty($boxscore_list)): //c
                   if($boxscore_list->outcome/*gm_r_label*/ == "W"){
                     $gm_result_color = 'style="color:white; font-weight:bold"';
                   }else{
                     $gm_result_color = 'style="color: hsl(0,60%,50%); font-weight:bold"';
                   }?>
                   <?php /*print_r($boxscore_list); */?>
            
                    <div class="stats_customize cts_res flex items-center small-margin-bottom small-12 medium-12 large-12">
                      <div class="team_logo_box hide-for-small-only">
                        <a href="<?php echo get_site_url().'/club/'.(isset($boxscore_list->org_nickname) ? $boxscore_list->org_nickname : $boxscore_list->org /*''*/).'/teams'; ?>" target="_blank"><img style="height:100px;width:125px;" alt="<?php echo $club_team_data_list->full_team_name; ?>" title="<?php echo $club_team_data_list->full_team_name; ?>" src="/wp-content/uploads/org-logos/<?php echo (!empty($club_team_data_list->org_logo) ? $club_team_data_list->org_logo != "NULL" ? $org_logo.$club_team_data_list->org_logo : $placeholder_img : $placeholder_img); ?>"/></a>
                      </div>
                      <?php $team_name = str_replace('.', '', $club_team_data_list->full_team_name);
                            $opp_name = str_replace('.', '', $boxscore_list->opp_full_team_name /*opp_name*/); ?>
                      <div class="grid-x cts_res_box align-center">
                        <div class="small-4 medium-4 large-2 large-offset-2">
                          <span class="wrap-text--200 small-4 medium-4 large-4"><?php echo $team_name; ?></span>
                        </div>
                        <div class="grid-x small-4 medium-4 large-4 align-center">
                          <span class="small-padding-right small-12 medium-12 large-12" <?php echo $gm_result_color; ?>>(<?php echo $boxscore_list->outcome.' '.$boxscore_list->score.' - '.$boxscore_list->opp_score /*$boxscore_list->game_result;*/ ?>)</span>
                          <button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:12px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-select-year="<?php echo $select_year ?>" data-event-name="<?php echo $boxscore_list->event_name ?>" data-game-id="<?php echo $boxscore_list->game_id; ?>" data-team-id="<?php echo $club_team_data_list->team_id ?>" data-url="<?php echo home_url(); ?>">Box Score<?php /* echo $boxscore_list->event_time;*/ ?></button>
                        </div>
                        <div class="grid-x small-4 medium-4 large-4">
                          <span class="large-8 end"><?php echo $opp_name; ?></span>
                        </div>
                      </div>
                      <div class="opp_logo_box hide-for-small-only">
                        <a href="<?php echo get_site_url().'/club/'.(isset($boxscore_list->org_nickname) ? $boxscore_list->opp_nickname : $boxscore_list->opp_org_id/*''*/).'/teams'?>" target="_blank"><img style="height:100px;width:125px;" alt="<?php echo $full_team_name; ?>" title="<?php echo $full_team_name; ?>" src="/wp-content/uploads/org-logos/<?php echo (!empty($boxscore_list->opp_org_logo/*opp_logo*/) ? $boxscore_list->opp_org_logo/*opp_logo*/ != "NULL" ? $org_logo.$boxscore_list->opp_org_logo/*opp_logo*/ : $placeholder_img : $placeholder_img); ?>"></a>
                      </div>
                    </div>
                  <?php else: echo ("<p>".g365_message()['not_available']."</p>"); endif; //c
                endforeach;// b
              endforeach;// a
            ?>
          </div>
        </td>
      </tr>
    <?php
      // Return not needed as we come here in ajax.
      break;
  }
}
function club_game_chart($win_count, $loss_count, $title, $div_name){
  $js_fn = '
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {"packages":["corechart"]});
    google.charts.setOnLoadCallback(draw_chart);
    function draw_chart() {
      var data = google.visualization.arrayToDataTable([
      ["Percentage", "Win/Loss Percentage"],
      ["Win: '.$win_count.'", '.$win_count.'],
      ["Loss: '.$loss_count.'", '.$loss_count.']
    ]);
      var options = {"title": "'.$title.' Teams Win/Loss %", pieStartAngle: 0, colors: [ "#cccccc", "#28282B"], is3D: true, "width":320, "height":200, backgroundColor: "transparent", hAxis: {textStyle: {color: "#ffffff"}}, vAxis: {textStyle: {color: "#ffffff"}}, legend: {textStyle: {color:"#ffffff"}}, titleTextStyle: {color: "#ffffff"}, "chartArea": {"width": "100%", "height": "80%"}, };
      var chart = new google.visualization.PieChart(document.getElementById("'.$div_name.'"));
      chart.draw(data, options);
    }
    </script>
  ';
  if(!empty($win_count) && !empty($loss_count)){
    return $js_fn;
  }
}
function g365_program_graph($data, $column){
  $win = 'W';
  $win_count = array_count_values(array_column($data, $column))[$win]; 
  $loss = 'L';
  $loss_count = array_count_values(array_column($data, $column))[$loss];
  $undefined = '';
  $inconclusive = array_count_values(array_column($data, $column))[$undefined];
  if(empty($win_count)){
    $win_count = '0';
  }
  if(empty($loss_count)){
    $loss_count = '0';
  }
  $result = array('win' => $win_count, 'loss' => $loss_count);
  return $result;
}
function game_schedule_result($event_id, $team_id, $org_id, $opponent_id, $year, $type){
  $club_team_stat_lists = g365_club_team_stat($event_id, $team_id, $org_id, $opponent_id, $year, $type); 
  $result = array();
  foreach($club_team_stat_lists as $index => $club_team_stat_list){
    $game_court = $club_team_stat_list->game_court;
    $game_time = date_format(date_create($club_team_stat_list->game_time), 'M d Y g:i A');
    $opponent = g365_club_team_stat($event_id, $team_id, $org_id, $club_team_stat_list->opponent_id, $year, $type = 3);
    $opponent_name = $opponent[0]->team_name;
    $game_result = $club_team_stat_list->game_result;
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
    $result[] =  '
      <div class="cell small-12">
        <div class="info">
          <div class="club_game_result_ls ls-hover grid-x small-padding-bottom">
            <div class="small-12 medium-8 large-8">
              '.$club_team_stat_list->team_name." VS ".$opponent_name.": ".$game_time." (".$game_court.")".'
            </div>
            <div class="text-right small-12 medium-4 large-4">
              '.$game_result.'
            </div>
          </div>
        </div>
      </div>
    ';
  }
  return $result;
}
function game_schedule_result_new($event_id, $team_id, $org_id, $opponent_id, $year, $type){
  $club_team_stat_lists = g365_club_team_stat($event_id, $team_id, $org_id, $opponent_id, $year, $type); 
  $result = array();
  foreach($club_team_stat_lists as $index => $club_team_stat_list){
    $game_court = $club_team_stat_list->game_court;
    $game_time = date_format(date_create($club_team_stat_list->game_time), 'M d Y g:i A');
    $opponent = g365_club_team_stat($event_id, $team_id, $org_id, $club_team_stat_list->opponent_id, $year, $type = 3);
    $opponent_name = $opponent[0]->team_name;
    $game_result = $club_team_stat_list->game_result;
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
    $result[] =  '
      <div class="cell small-12">
        <div class="info no-margin-bottom bg-white" style="margin-bottom: 0 !important">
          <div class="club_game_result_ls ls-hover grid-x small-padding-bottom">
            <div class="small-2 medium-2 large-2 text-center font-eurostile weight-bold text-black table-font-mobile">
              '.$club_team_stat_list->team_name.'
            </div>
            <div class="small-2 medium-2 large-2 text-center font-eurostile weight-bold text-black table-font-mobile">
              '.$opponent_name.'
            </div>
            <div class="small-2 medium-2 large-2 text-center font-eurostile weight-bold text-black table-font-mobile">
              '.$game_result.'
            </div>
             <div class="small-2 medium-2 large-2 text-center font-eurostile weight-bold text-black table-font-mobile">
              '.$game_court.'
            </div>
             <div class="small-4 medium-4 large-4 text-center font-eurostile weight-bold text-black table-font-mobile">
              '.$game_time.'
            </div>
          </div>
        </div>
      </div>
    ';
  }
  return $result;
}
function g365_filter_unmatched_data($array, $matched_val, $matched_col){ // Filter unmatched data base on column value
  if(!empty($array) && !empty($matched_val) && !empty($matched_col)){
    $unique_team_id = array();
    foreach ($array as $index => $item){
      if ($item->$matched_col == $matched_val){
        $unique_team_id[$item->$matched_col] = $item;
      }
    }
  }else{
    echo "Missing argument(s) for filtering unmatched data";
  }
  return $unique_team_id;
}
//function moved to dev-team
// function g365_player_img_dir($player_nickname, $event_nickname, $player_id, $type = null){
//   global $wpdb;
//   $wpdb_players = $wpdb->g365_players;
//   $get_profile_img_url = 
//     "SELECT profile_img FROM $wpdb_players WHERE id = $player_id;";
//   $profile_img_url_results = $wpdb->get_results($get_profile_img_url);
//   $profile_img_url = './wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg';
//   $event_pro_img_url = './wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
//   $default_img = get_site_url().'/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
//   if( file_exists($event_pro_img_url) ){ // Get event image
//     $event_pro_img_url = get_site_url().'/wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
//     return $event_pro_img_url;
//   }else{ 
//     if( file_exists($profile_img_url) ){ // Profile image if no event image
//       $profile_img_url = get_site_url().'/wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg';
//       return $profile_img_url;
//     }else{ 
//       if(!empty($profile_img_url_results)) {
//         $profile_img_url = $profile_img_url_results;
//       }else{
//       $camp_img_url = get_stat_tb($player_nickname, $event_nickname, $player_id);
//       if(!empty($camp_img_url)){ $camp_img_url = $camp_img_url[0]['profile_img']; } // Latest camp image
//       if(!empty($camp_img_url)){
//         return $camp_img_url;        
//       }else{
//         return $default_img;        
//       }
//      }
//     }
//   }
// }

function spp_player_img_dir($player_nickname, $event_nickname, $player_id, $type = null){
  $profile_img_url = 'https://sportspassports.com/wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg';
  $event_pro_img_url = 'https://sportspassports.com/wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
  $default_img = 'https://sportspassports.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
  if( file_exists($event_pro_img_url) ){ // Get event image
    $event_pro_img_url = 'https://sportspassports.com/wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
    return $event_pro_img_url;
  }else{ 
    if( file_exists($profile_img_url) ){ // Profile image if no event image
      $profile_img_url = 'https://sportspassports.com/wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg';
      return $profile_img_url;
    }else{ 
      $camp_img_url = get_stat_tb($player_nickname, $event_nickname, $player_id);
      if(!empty($camp_img_url)){ $camp_img_url = $camp_img_url[0]['profile_img']; } // Latest camp image
      if(!empty($camp_img_url)){
        return $camp_img_url;        
      }else{
        return $default_img;        
      }
    }
  }
}

function g365_award_dir($event_shortname, $sub_folder, $type){
  $default_profile_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/Passport-P-2023.png';
  $award_badge_folder = '/wp-content/themes/g365-press/assets/badges/'.$sub_folder.'/'.str_replace(array(" ", "'"), array("-", ""), $event_shortname)."-".$type.".png";
  $check_folder = '.'.$award_badge_folder;
  if(file_exists($check_folder)){
    return get_site_url().$award_badge_folder;
  }else{
    return $default_profile_img;
  }
}
function get_stat_tb($player_nickname, $event_nickname, $player_id){
  global $wpdb;
  $stat_table = $wpdb->g365_stats;
  $event_table = $wpdb->g365_events;  
  $result = $wpdb->get_results("SELECT * FROM $stat_table stats LEFT JOIN $event_table ev
ON ev.id = stats.event WHERE stats.player = $player_id AND stats.enabled = 1 AND ( (stats.profile_img != '') AND (stats.profile_img IS NOT NULL) ) AND stats.game = 0 ORDER BY ev.eventtime DESC");
  $result = json_decode( json_encode($result), true);
  return $result;
}
function g365_stat_list(){
  $stat_lists = array( 'stat_point' => array('alias' => 'stat_point', 'type' => 'Point'), 'stat_rebound' => array('alias' => 'stat_rebound', 'type' => 'Rebound'), 'stat_assist' => array('alias' => 'stat_assist', 'type' => 'Assist'), 'stat_block' => array('alias' => 'stat_block', 'type' => 'Block'), 'stat_three' => array('alias' => 'stat_three', 'type' => '3-Point'), 'stat_steal' => array('alias' => 'stat_steal', 'type' => 'Steal') );
  return $stat_lists;
}
function most_recent_event($type){
  global $wpdb;
  $dbs = json_decode(dbs());
  $include_field = " games.event_id AS event_id, events.eventtime AS event_time ";
  $from = " FROM $dbs->events AS events ";
  $joins = " INNER JOIN $dbs->games AS games ON games.event_id = events.id ";
  $where = " WHERE events.enabled = 1 ";
  $group_by = " GROUP BY events.id ";
  $order_by = " ORDER BY events.eventtime DESC ";
  switch($type){
    case 1:
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
    case 2:
      $include_field = " DISTINCT YEAR(events.eventtime) AS event_time ";
      $group_by = " GROUP BY event_time ";
      $order_by = " ORDER BY event_time DESC ";
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
    case 3: // DCP events
      $where = " WHERE events.enabled = 1 AND events.org = 3 ";
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
  }
}
// duuu
function cp_date_selector($pl_id = null, $type){
  global $wpdb;
  $dbs = json_decode(dbs());
  $include_field = " DISTINCT YEAR(ev.eventtime) AS event_date ";
  $from = " FROM $dbs->orgs AS orgs ";
  $joins = " LEFT JOIN $dbs->rosters ros ON ros.org = orgs.id LEFT JOIN $dbs->events ev ON ros.event = ev.id ";
  $where = " WHERE (ev.eventtime IS NOT NULL) ";
  $order_by = " ORDER BY event_date DESC ";
  switch($type){
    case 'ev_ros':
      $where = " WHERE (ev.eventtime IS NOT NULL) AND ev.eventtime <= CURDATE() ";
      return $wpdb->get_results("SELECT $include_field $from $joins $where $order_by");
      break;
    case 'pl_stat':
      if( empty($pl_id) ) return "Need player id to process";
      return $wpdb->get_results("SELECT IF( MONTH(ev.eventtime) > 08, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime) ) AS event_date FROM $dbs->stats stat LEFT JOIN $dbs->players pl ON pl.id = stat.player LEFT JOIN $dbs->events ev ON ev.id = stat.event WHERE pl.id = $pl_id AND stat.game > 0 AND stat.enabled = 1 ORDER BY event_date DESC");
      break;
    case 'pl_stat_year': // Need pl_id
      $from = " FROM $dbs->stats AS stats ";
      $joins = " LEFT JOIN $dbs->events AS events ON stats.event = events.id ";
      $where = " WHERE stats.game > 1 AND stats.player = $pl_id ";
      $include_field = " IF( MONTH(events.eventtime)>8, YEAR(events.eventtime)+1, YEAR(events.eventtime) ) AS event_date ";
      $group_by = " GROUP BY event_date ";
      $order_by = " ORDER BY event_date DESC ";
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
    case 'team_game':
      $include_field = " DISTINCT YEAR(start_time) AS game_year ";
      $from = " FROM $dbs->games ORDER BY start_time DESC";
      return $wpdb->get_results("SELECT $include_field $from");
      break;
  }
}
function event_exception_list($type = null){
  // Exclude stats from event list and camps
  global $wpdb;
  $dbs = json_decode(dbs());
  $camp_ids = $wpdb->get_results("SELECT id from $dbs->events WHERE org IN (2, 7165, 7474)");
  $camp_ids = json_decode(json_encode($camp_ids), true);
  $camp_id_list = array();
  foreach($camp_ids as $camp_id){
    foreach($camp_id as $result){
      $camp_id_list[] = $result;
    }
  }
  $camp_id_list = implode(',', $camp_id_list);
  switch($type){
    case 'scholastic':
      $ev_ids = $wpdb->get_results("SELECT id from $dbs->events WHERE org IN (7165, 7474)");
      $ev_ids = json_decode(json_encode($ev_ids), true);
      $ev_id_list = array();
      foreach($ev_ids as $ev_id){
        foreach($ev_id as $result){
          $ev_id_list[] = $result;
        }
      }
      $ev_id_list = implode(',', $ev_id_list);
      return $ev_id_list;
      break;
  }
  return $camp_id_list;
}
function year_exception_list($type = null){
  $year_exc_list = array('2021');
  switch($type){
    case 'in-array':
      return $year_exc_list;
      break;
  }
  $year_exc_list = implode(',', $year_exc_list);
  return $year_exc_list;
}
function pp_ev_exception(){ // Passport exception events
  // 505: High School Fall Preview
  $pp_ev_exclude = array('505');
  return $pp_ev_exclude;
}
function custom_exception_list($type = null){ // Passport exception events
  switch($type){
    case 'events-stats':
      return '504';
      break;
    case 'orgs-stats':
      return '7728';
      break;
    case 'slb-exclude-orgs':
      return '7094, 1';
      break;
  }
}
function leaderboard_tb_form($index, $level = null, $type = null){
  if($index == "stat_point"){
    $alias = "PPG";
  }
  else if($index == "stat_three"){
    $index = "stat_3-Point";
    $alias = "3PT";
  }
  else if($index == "stat_rebound"){
    $alias = "RPG";
  }
  else if($index == "stat_assist"){
    $alias = "APG";
  }
  else if($index == "stat_steal"){
    $alias = "SPG";
  }
  else if($index == "stat_block"){
    $alias = "BPG";
  }
  if($level == true){
    $level = "<th>LEVEL</th>";
  }else{
    $level = "";
  }
  switch($type){
    case 'dvs-lv':
    case 'remote_tsc_tlb':
      $division = '<th style="text-align:left">DIVISION</th>';
      $level = '<th style="text-align:left">LEVEL</th>';
      $form = '
        <thead>
          <tr>
            <th> '.strtoupper(str_replace(array("stat_", "_"), array("", "-"), $index)).'S</th>
            '.$level.'
            '.$division.'
            <th><span>'.$alias.'</span></th>
          </tr>
        </thead>
        <tbody>
      ';
      return $form;
    case 'dvs':
      $level = '<th style="text-align:left">LEVEL</th>';
      $form = '
        <thead>
          <tr>
            <th> '.strtoupper(str_replace(array("stat_", "_"), array("", "-"), $index)).'S</th>
            '.$level.'
            <th><span>'.$alias.'</span></th>
          </tr>
        </thead>
        <tbody>
      ';
      return $form;
      break;
    case 'api-fields':
      return ['label'=>strtoupper(str_replace(array("stat_", "_"), array("", "-"), $index)).'S', 'alias'=>$alias];
      break;
  }
  $form = '
    <thead>
      <tr>
        <th> '.strtoupper(str_replace(array("stat_", "_"), array("", "-"), $index)).'S</th>
        '.$level.'
        <th><span>'.$alias.'</span></th>
      </tr>
    </thead>
    <tbody>
  ';
  return $form;
}
function club_team_tb_form($ype=null, $tb_headers=null){
  $sortable_icon = get_site_url()."/wp-content/themes/g365-press/assets/tiny-logos/sort-icon.png";
  $field_names = array('team_name'=>'TEAM NAME', 'win'=>'W', 'loss'=>'L', 'winning_percentage'=>'PCT', 'point_per_game'=>'PPG', 'opponent_ppg'=>'OPP PPG');
  $arr_field_names = array();
  foreach($field_names as $field_name){
    $arr_field_names[] = '<th><span>'.$field_name.'</span></th>';
  }
  $form = '
    <thead>
      <tr>'.$arr_field_names[0].$arr_field_names[1].$arr_field_names[2].$arr_field_names[3].$arr_field_names[4].$arr_field_names[5].'</tr>
    </thead>
    <tbody>
  ';
  return $form;
}
function g365_img_resizer($img, $new_res, $alt, $title){
  $img_name = $img;
  $percent = $new_res;
  header('Content-Type: image/jpeg');
  list($width, $height) = getimagesize($img_name);
  $new_width = $width * $percent;
  $new_height = $height * $percent;
  // Resample
  $new_img = imagecreatetruecolor($new_width, $new_height);
  $image = imagecreatefromjpeg($img_name);
  imagecopyresampled($new_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
  ob_start ();
  imagejpeg($new_img);
  imagedestroy($new_img);
  $data = ob_get_contents ();
  ob_end_clean ();
  $image = "<img alt=".$alt." title=".$title." src='data:image/jpeg;base64,".base64_encode ($data)."'>";
  return $image;
}
function slb_by_year_query($stat_type, $year, $type, $org_id, $team_id){
  global $wpdb;
  $dbs = json_decode(dbs());
//   $top_team_leader = $wpdb->g365_top_team_leaders;
  $year = g365_date_format($year, 1);
  $joins = " FROM $dbs->stats stats INNER JOIN $dbs->events AS events ON stats.event = events.id INNER JOIN $dbs->rosters AS ros ON ros.players LIKE CONCAT('%\"',stats.player,'\":%') INNER JOIN $dbs->players AS players ON players.id = stats.player ";
  $point = "JSON_EXTRACT(stats.stats, '$.pts')"; $rebound = "JSON_EXTRACT(stats.stats, '$.rbs')"; $assist = "JSON_EXTRACT(stats.stats, '$.ast')"; $steal = "JSON_EXTRACT(stats.stats, '$.stl')"; $three_pt = "JSON_EXTRACT(stats.stats, '$.three_pt')"; $block = "JSON_EXTRACT(stats.stats, '$.blk')"; $play_time = "JSON_EXTRACT(stats.stats, '$.time_pl')";
  $stat_pt = "IF ( ISNULL($point) OR $point = '', '0', $point )"; $stat_reb = "IF ( ISNULL($rebound) OR $rebound = '', '0', $rebound )"; $stat_ast = "IF ( ISNULL($assist) OR $assist = '', '0', $assist )"; $stat_stl = "IF ( ISNULL($steal) OR $steal = '', '0', $steal )"; $stat_three_pt = "IF ( ISNULL($three_pt) OR $three_pt = '', '0', $three_pt )"; $stat_blk = "IF ( ISNULL($block) OR $block = '', '0', $block )";
  $tb2_point = "JSON_EXTRACT(stats, '$.pts')"; $tb2_rebound = "JSON_EXTRACT(stats, '$.rbs')"; $tb2_assist = "JSON_EXTRACT(stats, '$.ast')"; $tb2_steal = "JSON_EXTRACT(stats, '$.stl')"; $tb2_three_pt = "JSON_EXTRACT(stats, '$.three_pt')"; $tb2_block = "JSON_EXTRACT(stats, '$.blk')"; $tb2_play_time = "JSON_EXTRACT(stats, '$.time_pl')";
  $tb2_stat_pt = "IF ( ISNULL($tb2_point) OR $tb2_point = '', '0', $tb2_point )"; $tb2_stat_reb = "IF ( ISNULL($tb2_rebound) OR $tb2_rebound = '', '0', $tb2_rebound )"; $tb2_stat_ast = "IF ( ISNULL($tb2_assist) OR $tb2_assist = '', '0', $tb2_assist )"; $tb2_stat_stl = "IF ( ISNULL($tb2_steal) OR $tb2_steal = '', '0', $tb2_steal )"; $tb2_stat_three_pt = "IF ( ISNULL($tb2_three_pt) OR $tb2_three_pt = '', '0', $tb2_three_pt )"; $tb2_stat_blk = "IF ( ISNULL($tb2_block) OR $tb2_block = '', '0', $tb2_block )";
//   $stat_cal = "$stat_pt AS stat_point, $stat_reb AS stat_rebound, $stat_ast AS stat_assist, $stat_stl AS stat_steal, $stat_blk AS stat_block, SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1) AS pl_minute ";
  $stat_filter_condition = " ( (($stat_pt+$stat_reb+$stat_ast+$stat_three_pt+$stat_stl+$stat_blk)>0) || SUBSTRING_INDEX(SUBSTRING_INDEX($play_time,':',2),':',-1)>=5 ) ";
  $include_field = " players.id AS player_id,players.name AS player_name,players.nickname AS player_nickname,players.enabled AS player_enabled,events.nickname AS event_nickname,events.eventtime AS event_time,if(json_contains(rosters_away.players,concat(players.id),'$'),rosters_away.level,rosters_home.level) AS player_level,if(json_contains(rosters_away.players,concat(players.id),'$'),rosters_away.org,rosters_home.org) AS org_id, stats.stats ";
  $filter_stat = " WHERE $stat_filter_condition AND players.enabled = 1 ";
  $where = " WHERE event_time BETWEEN $year ";
  $group_by = " GROUP BY player_id ";
  $order_by = " ORDER BY $stat_type DESC ";
  switch($stat_type){
    case 'stat_point':
      $tb2_stat_type = $tb2_stat_pt;
      break;
    case 'stat_rebound':
      $tb2_stat_type = $tb2_stat_reb;
      break;
    case 'stat_assist':
      $tb2_stat_type = $tb2_stat_ast;
      break;
    case 'stat_block':
      $tb2_stat_type = $tb2_stat_blk;
      break;
    case 'stat_three':
      $tb2_stat_type = $tb2_stat_three_pt;
      break;
    case 'stat_steal':
      $tb2_stat_type = $tb2_stat_stl;
      break;
  }
  switch($type){
    case 1:
      $limit = " LIMIT 5 ";
      $result = $wpdb->get_results("SELECT player_name, player_nickname, player_id, GROUP_CONCAT(event_time), TRIM(ROUND(avg($tb2_stat_type),2))+0 AS $stat_type FROM ( SELECT $include_field $joins $filter_stat ) tb_1 $where $group_by $order_by $limit");
      break;
    case 2:
      $limit = " LIMIT 50 ";
      $result = $wpdb->get_results("SELECT player_name, player_nickname, player_id, GROUP_CONCAT(event_time), TRIM(ROUND(avg($tb2_stat_type),2))+0 AS $stat_type FROM ( SELECT $include_field $joins $filter_stat ) tb_1 $where $group_by $order_by $limit");
      break;
//     case 3:
//       $limit = " LIMIT 1 ";
//       $result = $wpdb->get_results("SELECT player_name, player_nickname, player_id, TRIM(ROUND(avg($stat_type),2))+0 AS $stat_type FROM wp_54ab678738_g365_top_team_leaders WHERE game_time BETWEEN $year AND org_id = $org_id AND team_id = $team_id GROUP BY player_id ORDER BY $stat_type DESC $limit");
//       break;
    case 4:
      $include_field = " SUBSTRING_INDEX(GROUP_CONCAT('{\"player_name\": \"',player_name, '\", \"player_nickname\": \"',player_nickname, '\", \"player_id\": ',player_id, ', \"stat_point\": ',stat_point,'}' order by stat_point desc),',{',1) AS stat_point, SUBSTRING_INDEX(GROUP_CONCAT('{\"player_name\": \"',player_name, '\", \"player_nickname\": \"',player_nickname, '\", \"player_id\": ',player_id, ', \"stat_rebound\": ',stat_rebound,'}' order by stat_rebound desc),',{',1) AS stat_rebound, SUBSTRING_INDEX(GROUP_CONCAT('{\"player_name\": \"',player_name, '\", \"player_nickname\": \"',player_nickname, '\", \"player_id\": ',player_id, ', \"stat_assist\": ',stat_assist,'}' order by stat_assist desc),',{',1) AS stat_assist, SUBSTRING_INDEX(GROUP_CONCAT('{\"player_name\": \"',player_name, '\", \"player_nickname\": \"',player_nickname, '\", \"player_id\": ',player_id, ', \"stat_block\": ',stat_block,'}' order by stat_block desc),',{',1) AS stat_block, SUBSTRING_INDEX(GROUP_CONCAT('{\"player_name\": \"',player_name, '\", \"player_nickname\": \"',player_nickname, '\", \"player_id\": ',player_id, ', \"stat_three\": ',stat_three,'}' order by stat_three desc),',{',1) AS stat_three, SUBSTRING_INDEX(GROUP_CONCAT('{\"player_name\": \"',player_name, '\", \"player_nickname\": \"',player_nickname, '\", \"player_id\": ',player_id, ', \"stat_steal\": ',stat_steal,'}' order by stat_steal desc),',{',1) AS stat_steal ";
      $inner_field = " player_name, player_nickname, player_id, TRIM(ROUND(avg(stat_point),2))+0 AS stat_point, TRIM(ROUND(avg(stat_rebound),2))+0 AS stat_rebound, TRIM(ROUND(avg(stat_assist),2))+0 AS stat_assist, TRIM(ROUND(avg(stat_steal),2))+0 AS stat_steal, TRIM(ROUND(avg(stat_block),2))+0 AS stat_block, TRIM(ROUND(avg(stat_three),2))+0 AS stat_three ";
      $main_field = " ros.org AS org_id, ros.team AS team_id, players.name AS player_name, players.id AS player_id, players.nickname AS player_nickname, IF((isnull(json_extract(stats.stats,'$.pts')) OR (json_extract(stats.stats,'$.pts') = '')),'0', json_extract(stats.stats,'$.pts')) AS stat_point, IF((isnull(json_extract(stats.stats,'$.rbs')) OR (json_extract(stats.stats,'$.rbs') = '')),'0',json_extract(stats.stats,'$.rbs')) AS stat_rebound, IF((isnull(json_extract(stats.stats,'$.ast')) OR (json_extract(stats.stats,'$.ast') = '')),'0',json_extract(stats.stats,'$.ast')) AS stat_assist, IF((isnull(json_extract(stats.stats,'$.stl')) OR (json_extract(stats.stats,'$.stl') = '')),'0',json_extract(stats.stats,'$.stl')) AS stat_steal, IF((isnull(json_extract(stats.stats,'$.three_pt')) OR (json_extract(stats.stats,'$.three_pt') = '')),'0',json_extract(stats.stats,'$.three_pt')) AS stat_three, IF((isnull(json_extract(stats.stats,'$.blk')) OR (json_extract(stats.stats,'$.blk') = '')),'0',json_extract(stats.stats,'$.blk')) AS stat_block ";
      $main_where = " WHERE ((substring_index(substring_index(json_extract(stats.stats,'$.time_pl'),':',2),':',-(1)) >= 5) or (isnull(substring_index(substring_index(json_extract(stats.stats,'$.time_pl'),':',2),':',-(1))) and (((((IF((isnull(json_extract(stats.stats,'$.pts')) or (json_extract(stats.stats,'$.pts') = '')),'0',json_extract(stats.stats,'$.pts')) + IF((isnull(json_extract(stats.stats,'$.rbs')) or (json_extract(stats.stats,'$.rbs') = '')),'0',json_extract(stats.stats,'$.rbs'))) + IF((isnull(json_extract(stats.stats,'$.ast')) or (json_extract(stats.stats,'$.ast') = '')),'0',json_extract(stats.stats,'$.ast'))) + IF((isnull(json_extract(stats.stats,'$.stl')) or (json_extract(stats.stats,'$.stl') = '')),'0',json_extract(stats.stats,'$.stl'))) + IF((isnull(json_extract(stats.stats,'$.blk')) or (json_extract(stats.stats,'$.blk') = '')),'0',json_extract(stats.stats,'$.blk'))) > 0)) or ((substring_index(substring_index(json_extract(stats.stats,'$.time_pl'),':',2),':',-(1)) = '') and (((((IF((isnull(json_extract(stats.stats,'$.pts')) or (json_extract(stats.stats,'$.pts') = '')),'0',json_extract(stats.stats,'$.pts')) + IF((isnull(json_extract(stats.stats,'$.rbs')) or (json_extract(stats.stats,'$.rbs') = '')),'0',json_extract(stats.stats,'$.rbs'))) + IF((isnull(json_extract(stats.stats,'$.ast')) or (json_extract(stats.stats,'$.ast') = '')),'0',json_extract(stats.stats,'$.ast'))) + IF((isnull(json_extract(stats.stats,'$.stl')) or (json_extract(stats.stats,'$.stl') = '')),'0',json_extract(stats.stats,'$.stl'))) + IF((isnull(json_extract(stats.stats,'$.blk')) or (json_extract(stats.stats,'$.blk') = '')),'0',json_extract(stats.stats,'$.blk'))) > 0)) or ((substring_index(substring_index(json_extract(stats.stats,'$.time_pl'),':',2),':',-(1)) < 5) and (((((IF((isnull(json_extract(stats.stats,'$.pts')) or (json_extract(stats.stats,'$.pts') = '')),'0',json_extract(stats.stats,'$.pts')) + IF((isnull(json_extract(stats.stats,'$.rbs')) or (json_extract(stats.stats,'$.rbs') = '')),'0',json_extract(stats.stats,'$.rbs'))) + IF((isnull(json_extract(stats.stats,'$.ast')) or (json_extract(stats.stats,'$.ast') = '')),'0',json_extract(stats.stats,'$.ast'))) + IF((isnull(json_extract(stats.stats,'$.stl')) or (json_extract(stats.stats,'$.stl') = '')),'0',json_extract(stats.stats,'$.stl'))) + IF((isnull(json_extract(stats.stats,'$.blk')) or (json_extract(stats.stats,'$.blk') = '')),'0',json_extract(stats.stats,'$.blk'))) > 0))) and (players.enabled = 1)
 ";
      $group_by = " GROUP BY stats.game, ros.org, ros.team, events.id, players.id ";
      $inner_where = " WHERE events.eventtime BETWEEN $year AND ros.org = $org_id AND ros.team = $team_id AND ros.event != 0 AND stats.game != 0 AND ros.enabled = 1 AND stats.enabled = 1 AND players.id != '11000' AND players.id != '11001' AND $stat_filter_condition $group_by";
      $result = $wpdb->get_results("SELECT $include_field FROM (SELECT $inner_field FROM (SELECT $main_field $joins $inner_where) tb_1 GROUP BY player_id ) tb_2");
      $stat_leaders = array();
      foreach ($result[0] as $index => $stat_leader){
        $stat_leaders[$index] = $stat_leader;
      }
      return $stat_leaders;
      break;
    case 5: // New Team Leaders
      $tm_pl_gm = array(); $indi_pl_stats = array(); $stat_avgs = array();
      $where = " WHERE ev.eventtime BETWEEN $year AND ros.org = $org_id AND ros.team = $team_id AND ros.event != 0 AND ros.enabled = 1 AND pl.id != '11000' AND pl.id != '11001' ";
      $player_id_list = $wpdb->get_results("SELECT GROUP_CONCAT(pl_id) pl_id_list FROM (SELECT pl.id pl_id FROM $dbs->rosters ros INNER JOIN $dbs->players pl ON ros.players LIKE CONCAT('%\"',pl.id,'\":%') INNER JOIN $dbs->events ev ON ev.id = ros.event $where GROUP BY pl.id) tb_1 ");
      $pl_id_list = $player_id_list[0]->pl_id_list;
      $pl_stats = $wpdb->get_results("SELECT stats.*, ev.nickname event_nickname, pl.name player_name, pl.nickname player_nickname, pl.id player_id FROM $dbs->stats stats INNER JOIN $dbs->events ev ON stats.event = ev.id INNER JOIN $dbs->players pl ON stats.player = pl.id WHERE stats.player IN ($pl_id_list) AND stats.game != 0 AND stats.enabled = 1 AND $stat_filter_condition ");
      $stat_catagories = ['stat_point'=>'pts', 'stat_rebound'=>'rbs', 'stat_assist'=>'ast', 'stat_steal'=>'stl', 'stat_block'=>'blk', 'stat_three'=>'three_pt'];
      // Team player games
      foreach($pl_stats as $pl_stat){
        $tm_pl_gm[$pl_stat->player][] = $pl_stat->stats;
        $tm_pl_gm[$pl_stat->player]['event_nickname'] = $pl_stat->event_nickname;
        $tm_pl_gm[$pl_stat->player]['player_id'] = $pl_stat->player_id;
        $tm_pl_gm[$pl_stat->player]['player_nickname'] = $pl_stat->player_nickname;
        $tm_pl_gm[$pl_stat->player]['player_name'] = $pl_stat->player_name;
      }
      // Individual player games
      foreach( $tm_pl_gm as $index => $indi_stats ){
        // Get individual player stats from each catagories
        foreach($stat_catagories as $cat_dex => $stat_catagory){
          // Individual player stats
          foreach($indi_stats as $dex => $stat_catagory_total){
            // Get total from each stat catagories
            $indi_pl_stats[$cat_dex][$index][$cat_dex] += ((array) json_decode( $stat_catagory_total ))[$stat_catagory];
            $indi_pl_stats[$cat_dex][$index]['event_nickname'] = $indi_stats['event_nickname'];
            $indi_pl_stats[$cat_dex][$index]['player_id'] = $indi_stats['player_id'];
            $indi_pl_stats[$cat_dex][$index]['player_nickname'] = $indi_stats['player_nickname'];
            $indi_pl_stats[$cat_dex][$index]['player_name'] = $indi_stats['player_name'];
          }
          // Get an average from each catagories
          $avg[$cat_dex][$index][$cat_dex] = round($indi_pl_stats[$cat_dex][$index][$cat_dex] / count($indi_stats), 1);
          $avg[$cat_dex][$index]['event_nickname'] = $indi_pl_stats[$cat_dex][$index]['event_nickname'];
          $avg[$cat_dex][$index]['player_id'] = $indi_pl_stats[$cat_dex][$index]['player_id'];
          $avg[$cat_dex][$index]['player_nickname'] = $indi_pl_stats[$cat_dex][$index]['player_nickname'];
          $avg[$cat_dex][$index]['player_name'] = $indi_pl_stats[$cat_dex][$index]['player_name'];
          arsort($avg[$cat_dex]);
          $avg[$cat_dex] = array_slice($avg[$cat_dex], 0, 1);
        }
      }
      foreach($avg as $index => $json_avg){
        $stat_avgs[$index] = trim(json_encode($json_avg), '[]');
      } 
      return $stat_avgs;
      break;
  } 
  $result = json_decode( json_encode($result), true);
  return $result;
}
function slb_by_year($year, $type, $arg=array('level', 'stat_type'), $org_id, $team_id){
  $stat_types = array('stat_point' => 'stat_point', 'stat_rebound' => 'stat_rebound', 'stat_assist' => 'stat_assist', 'stat_block' => 'stat_block', 'stat_three' => 'stat_three', 'stat_steal' => 'stat_steal');
  $dafault_stat_leader = array();
  switch($type){
    case 1:
      foreach($stat_types as $index => $stat_type){
        $array_list = slb_by_year_query($index, $year, $type, $org_id, $team_id);
        $dafault_stat_leader[$index] = $array_list;
      }
      break;
    case 2:
      $array_list = slb_by_year_query($arg['stat_type'], $year, $type, $org_id, $team_id);
      $dafault_stat_leader[$arg['stat_type']] = $array_list;
      break;
    case 3: // 1 top Leader on each stat catagories
      foreach($stat_types as $index => $stat_type){
//         $array_list = slb_by_year_query($index, $year, $type, $org_id, $team_id);
        $array_list = team_leader($org_id, $team_id, $stat_type, $year);
        $dafault_stat_leader[$index] = $array_list;
      }
      break;
  }
  return $dafault_stat_leader;
}
function g365_team_rosters($year, $team_id, $org_id, $type, $args = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $include_field = " roster.id AS roster_id, roster.updatetime, roster.enabled, roster.org AS org_id, roster.team AS team_id, roster.event AS event_id, ev.name AS event_name, ev.eventtime AS event_time, @level_ref:=roster.level AS level, roster.division, roster.team_type, roster.coach AS coach_id, roster.asst AS asst_id, roster.players, roster.description, roster.events AS event_names, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.abbreviation as org_abbr, teams.name AS team_name, teams.level AS team_level, ev.name AS event_name, ev.short_name AS event_short, JSON_EXTRACT(ev.divisions, CONCAT('$.\"', @level_ref, '\"')) AS event_division, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.team_restrictions')) as team_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.date_restrictions')) as date_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_name')) as pool_name, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_number')) as pool_number, CONCAT(coaches.first_name,' ',coaches.last_name) AS coach_name, CONCAT(assts.first_name,' ',assts.last_name) AS asst_name ";
  $joins = " FROM $dbs->rosters AS roster LEFT JOIN $dbs->orgs AS orgs ON roster.org=orgs.id LEFT JOIN $dbs->teams AS teams ON roster.team=teams.id LEFT JOIN $dbs->events AS ev ON roster.event=ev.id LEFT JOIN $dbs->coaches AS coaches ON roster.coach=coaches.id LEFT JOIN $dbs->coaches AS assts ON roster.asst=assts.id ";
  $condition = " WHERE roster.org = $org_id AND teams.id = $team_id AND roster.enabled != 0 ";
  $order_by = " ORDER BY roster.level DESC, ev.id ASC ";
  switch($type){
    case 1: // Default club team roster
      $condition = " WHERE roster.org = $org_id AND teams.id = $team_id AND roster.event = 0 AND roster.enabled != 0 ";
      $result = $wpdb->get_results("SELECT $include_field $joins $condition $order_by");
      break;
    case 2: // All previous event rosters
      $condition = " WHERE roster.org = $org_id AND teams.id = $team_id AND ev.id != 0 AND ev.eventtime BETWEEN $year ";  
      $result = $wpdb->get_results("SELECT $include_field $joins $condition $order_by");
      break;
    case 3: // All rosters from selected org and team
      $result = $wpdb->get_results("SELECT $include_field $joins $condition $order_by");
      break;
    case 4: // All rosters from selected org
      $condition = " WHERE roster.org = $org_id ";
      $result = $wpdb->get_results("SELECT $include_field $joins $condition $order_by");
      break;
    case 5: // DCP event rosters
      if(!empty($args['event_id'])){ $event_id = $args['event_id']; }
      if(!empty($args['pl_id'])){ $pl_id = $args['pl_id']; }else{ $pl_id = ''; }
      $condition = " WHERE roster.org = $org_id AND teams.id = $team_id AND roster.event = $event_id AND roster.enabled != 0 ";
      $result = $wpdb->get_results("SELECT $include_field $joins $condition $order_by");
      break;
  }
  return $result;
}
function team_leader($org_id, $team_id, $stat_type, $year){
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);  
  $result = $wpdb->get_results("SELECT player_name, player_nickname, player_id, TRIM(ROUND(avg($stat_type),2))+0 AS $stat_type FROM (
select if((`rosters_home`.`players` like concat('%\"',`players`.`id`,'\":%')),`rosters_home`.`org`,`rosters_away`.`org`) AS `org_id`,if((`rosters_home`.`players` like concat('%\"',`players`.`id`,'\":%')),`rosters_home`.`team`,`rosters_away`.`team`) AS `team_id`,`players`.`name` AS `player_name`,`players`.`id` AS `player_id`,`players`.`nickname` AS `player_nickname`,`games`.`event_id` AS `event_id`,`games`.`start_time` AS `game_time`,`players`.`enabled` AS `player_enabled`,`stats`.`enabled` AS `stat_enabled`,if((isnull(json_extract(`stats`.`stats`,'$.pts')) or (json_extract(`stats`.`stats`,'$.pts') = '')),'0',json_extract(`stats`.`stats`,'$.pts')) AS `stat_point`,if((isnull(json_extract(`stats`.`stats`,'$.rbs')) or (json_extract(`stats`.`stats`,'$.rbs') = '')),'0',json_extract(`stats`.`stats`,'$.rbs')) AS `stat_rebound`,if((isnull(json_extract(`stats`.`stats`,'$.ast')) or (json_extract(`stats`.`stats`,'$.ast') = '')),'0',json_extract(`stats`.`stats`,'$.ast')) AS `stat_assist`,if((isnull(json_extract(`stats`.`stats`,'$.stl')) or (json_extract(`stats`.`stats`,'$.stl') = '')),'0',json_extract(`stats`.`stats`,'$.stl')) AS `stat_steal`,if((isnull(json_extract(`stats`.`stats`,'$.three_pt')) or (json_extract(`stats`.`stats`,'$.three_pt') = '')),'0',json_extract(`stats`.`stats`,'$.three_pt')) AS `stat_three`,if((isnull(json_extract(`stats`.`stats`,'$.blk')) or (json_extract(`stats`.`stats`,'$.blk') = '')),'0',json_extract(`stats`.`stats`,'$.blk')) AS `stat_block`,substring_index(substring_index(json_extract(`stats`.`stats`,'$.time_pl'),':',2),':',-(1)) AS `pl_minute`,((((if((isnull(json_extract(`stats`.`stats`,'$.pts')) or (json_extract(`stats`.`stats`,'$.pts') = '')),'0',json_extract(`stats`.`stats`,'$.pts')) + if((isnull(json_extract(`stats`.`stats`,'$.rbs')) or (json_extract(`stats`.`stats`,'$.rbs') = '')),'0',json_extract(`stats`.`stats`,'$.rbs'))) + if((isnull(json_extract(`stats`.`stats`,'$.ast')) or (json_extract(`stats`.`stats`,'$.ast') = '')),'0',json_extract(`stats`.`stats`,'$.ast'))) + if((isnull(json_extract(`stats`.`stats`,'$.stl')) or (json_extract(`stats`.`stats`,'$.stl') = '')),'0',json_extract(`stats`.`stats`,'$.stl'))) + if((isnull(json_extract(`stats`.`stats`,'$.blk')) or (json_extract(`stats`.`stats`,'$.blk') = '')),'0',json_extract(`stats`.`stats`,'$.blk'))) AS `sum_all_stats` from ((((`$dbs->stats` `stats` join `$dbs->games` `games` on((`games`.`id` = `stats`.`game`))) join `$dbs->players` `players` on((`players`.`id` = `stats`.`player`))) join `$dbs->rosters` `rosters_away` on((`rosters_away`.`id` = `games`.`away_team`))) join `$dbs->rosters` `rosters_home` on((`rosters_home`.`id` = `games`.`home_team`))) 
having (org_id = $org_id AND team_id = $team_id AND stat_enabled = 1 AND ((`pl_minute` >= 5) or (isnull(`pl_minute`) and (`sum_all_stats` > 0)) or ((`pl_minute` = '') and (`sum_all_stats` > 0)) or ((`pl_minute` < 5) and (`sum_all_stats` > 0))) and (`player_enabled` = 1))) results WHERE game_time BETWEEN $year AND org_id = $org_id AND team_id = $team_id GROUP BY player_id ORDER BY $stat_type DESC LIMIT 1");
  $result = json_decode( json_encode($result), true);
  return $result;
}
//get all player profile data
function g365_get_award($player = null, $year, $org_id, $team_id, $type) {
	//make sure we have a value
// 	if( $player === null || empty($player) ) return 'Need Player id to start build.';
  if(!empty($player)){
    $player = implode(',', $player); // Get a list of players in team  
  }
  $year = g365_date_format($year, 1);
	global $wpdb;
  $dbs = json_decode(dbs());
  $data_columns = new \stdClass();
  $include_field = " (ev.eventtime) AS event_time, org.id AS org_id, ar.player AS player_id, pl.name AS player_name, pl.nickname AS player_nickname, ar.event as event_id, ev.short_name as event_shortname, ev.nickname as event_nickname, aw.type as award_type, aw.name as award, aw.logo_img as award_img, ar.name as award_title, ar.enabled as enabled ";
  $joins = " FROM $dbs->award_refs AS ar LEFT JOIN $dbs->awards AS aw ON ar.award=aw.id LEFT JOIN $dbs->rankings AS rk ON ar.ranking=rk.id LEFT JOIN $dbs->groups AS rk_gr ON rk.group_id=rk_gr.id LEFT JOIN $dbs->events AS ev ON ev.id = ar.event LEFT JOIN $dbs->rosters AS ros ON ros.event = ev.id LEFT JOIN $dbs->orgs AS org ON org.id = ros.org LEFT JOIN $dbs->players AS pl ON pl.id = ar.player ";
  $where = " WHERE org.id = $org_id AND ( ros.players LIKE CONCAT('%\"',ar.player,'\":%') AND ros.event = ar.event ) AND ros.team = $team_id AND ev.eventtime BETWEEN $year AND ar.enabled = 1 ";
  $order_by = " ORDER BY ar.event DESC ";
  switch($type){
    case 1:
      $data_columns->awards = $wpdb->get_results("SELECT $include_field $joins $where $order_by");
      break;
    case 2:
      $where = " WHERE org.id = $org_id AND ( ros.players LIKE CONCAT('%\"',ar.player,'\":%') AND ros.event = ar.event ) AND ev.eventtime BETWEEN $year AND ar.enabled = 1 ";
      $order_by = " ORDER BY ar.event DESC ";
      $data_columns->awards = $wpdb->get_results("SELECT $include_field $joins $where $order_by");
      break;
  }
  return $data_columns;
}
function cp_team_ranking($team_id=null, $org_id, $year, $type){
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $include_field = " *, JSON_EXTRACT(team_rankings, '$[*].org.team_id') AS is_team_id ";
  $where = " HAVING start_datetime BETWEEN $year ";
  $condition = " AND ( rankings LIKE ('[$org_id,%') OR rankings LIKE ('%, $org_id,%') OR rankings LIKE ('%, $org_id]') ) ";
  $order_by = " ORDER BY start_datetime DESC ";
  switch($type){
    case 1: // org_id only
      $result = $wpdb->get_results("SELECT $include_field FROM $dbs->rankings $where $condition $order_by");
      break;
    case 2: // team_id only
      $condition = $condition." AND is_team_id LIKE CONCAT('%\"',$team_id,'\":%') ";
      $result = $wpdb->get_results("SELECT $include_field FROM $dbs->rankings $where $condition $order_by");
      break;
  }
  return $result;
}
function ranking_label($rank){
  $region = 'en_US';
  $r_format = new NumberFormatter($region, NumberFormatter::ORDINAL);
  $rank_abbr = $r_format->format($rank);
  return $rank_abbr;
}
function championship_award($year, $type, $team_id=null, $org_id=null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $joins = " FROM $dbs->games game LEFT JOIN $dbs->events ev ON ev.id = game.event_id LEFT JOIN $dbs->rosters home_ros ON home_ros.id = game.home_team LEFT JOIN $dbs->rosters away_ros ON away_ros.id = game.away_team LEFT JOIN $dbs->teams home_teams ON home_teams.id = home_ros.team LEFT JOIN $dbs->teams away_teams ON away_teams.id = away_ros.team ";
  $wtb_cond = " WHERE (bracket_name LIKE 'Playoffs' OR bracket_name LIKE 'Championship Playoffs') AND (home_team_score IS NOT NULL OR away_team_score IS NOT NULL) ";
  $winner_tb = $wpdb->get_results("SELECT ev.id AS event_id, ev.name AS event_name, ev.short_name AS event_shortname, game.division, game.start_time AS game_time, game.home_team AS home_ros_id, game.home_team_score AS home_ros_score, game.away_team AS away_ros_id, game.away_team_score AS away_ros_score, game.bracket_name, IF( game.home_team_score > game.away_team_score, game.home_team, game.away_team ) AS champ_ros, IF( game.home_team_score > game.away_team_score, home_teams.id, away_teams.id ) AS championship_team, IF( game.home_team_score > game.away_team_score, home_teams.search_list, away_teams.search_list ) AS championship_team_name, IF( game.home_team_score > game.away_team_score, away_teams.id, home_teams.id ) AS runner_up_team, IF( game.home_team_score > game.away_team_score, away_teams.search_list, home_teams.search_list ) AS runner_up_team_name $joins $wtb_cond ORDER BY event_id, home_ros.level, home_ros.level, game_time DESC");
  $wtb_orw = " WHERE game.start_time BETWEEN $year AND (home_team_score IS NOT NULL OR away_team_score IS NOT NULL) ";
  $include_orw_field = " event_id, event_name, substring_index(group_concat(org_id ORDER BY num_win DESC),',',1) AS winner_org_id, division, substring_index(group_concat(most_win_ros ORDER BY num_win DESC),',',1) AS most_win_ros_id, MAX(num_win) AS num_win "; 
  $include_orw_ctb_field = " org_id, event_id, event_name, division, most_win_ros, count(most_win_ros) AS num_win FROM (SELECT IF(game.home_team_score > game.away_team_score, home_teams.org, away_teams.org) AS org_id, ev.id AS event_id, ev.name AS event_name, game.division, game.start_time AS game_time, game.home_team AS home_ros_id, game.home_team_score AS home_ros_score, game.away_team AS away_ros_id, game.away_team_score AS away_ros_score, game.bracket_name, IF( game.home_team_score > game.away_team_score, game.home_team, game.away_team ) AS most_win_ros, IF( game.home_team_score > game.away_team_score, home_teams.id, away_teams.id ) AS most_win_team ";
  $group_by_orw_ctb2 = " GROUP BY division, most_win_ros, org_id, champ_tb.event_id ";
  $order_by_orw_ctb = " ORDER BY event_id, game_time DESC ";
  $order_by_ctb2 = " ORDER BY event_id, num_win DESC ";
  $overall_wins = $wpdb->get_results("SELECT $include_orw_field FROM (SELECT $include_orw_ctb_field $joins $wtb_orw $order_by_orw) champ_tb $group_by_orw_ctb2 $order_by_ctb2) champ_tb2 GROUP BY division, champ_tb2.event_id HAVING winner_org_id = $org_id ORDER BY event_id");
  $winner_tb = json_decode(json_encode($winner_tb), true);
  $overall_wins = json_decode(json_encode($overall_wins), true);
  $champ_bracket = array();
  $team_championship = array();
  foreach($overall_wins as $overall_win){ 
    foreach($winner_tb as  $index => $winner){
      if( ($overall_win['most_win_ros_id'] == $winner['home_ros_id'] && $overall_win['event_id'] == $winner['event_id'] && ($overall_win['division'] == $winner['division'] ) || ($overall_win['most_win_ros_id'] == $winner['away_ros_id'] && $overall_win['event_id'] == $winner['event_id'] && ($overall_win['division'] == $winner['division']))) ){ 
        $champ_ros_col = array_column($champ_bracket, 'champ_ros');
        if (!in_array($overall_win['most_win_ros_id'], $champ_ros_col)){
          $champ_bracket[$index] = $winner;
        }
      }
    }
  }
  switch($type){
    case 'team_champ': // Need $team_id only
      foreach($champ_bracket as $index => $winner_type){
        if($team_id == $winner_type['championship_team']){
          $team_championship[$index] = $winner_type;
          unset($team_championship[$index]['runner_up_team']);
          unset($team_championship[$index]['runner_up_team_name']);
        }
        if($team_id == $winner_type['runner_up_team']){
          $team_championship[$index] = $winner_type;
          unset($team_championship[$index]['championship_team']);
          unset($team_championship[$index]['championship_team_name']);
        }
      }
      return $team_championship;
      break;
    case 'org_champ': // Need $org_id only
      foreach($overall_wins as $overall_win){
        $ros_id = $overall_win['most_win_ros_id'];
        $get_team_id = $wpdb->get_results("SELECT team.id FROM $dbs->teams team LEFT JOIN $dbs->rosters ros ON ros.team = team.id WHERE ros.id = $ros_id");
        foreach($champ_bracket as $index => $winner_type){
          if($get_team_id[0]->id == $winner_type['championship_team']){
            $team_championship[$index] = $winner_type;
            unset($team_championship[$index]['runner_up_team']);
            unset($team_championship[$index]['runner_up_team_name']);
          }
          if($get_team_id[0]->id == $winner_type['runner_up_team']){
            $team_championship[$index] = $winner_type;
            unset($team_championship[$index]['championship_team']);
            unset($team_championship[$index]['championship_team_name']);
          }
        }
      }
      return $team_championship;
      break;
    case 'pl_profile_champ':
      break;
  }
}
function club_team_award($type, $args = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  !empty($args['selected_year']) ? $selected_year = $args['selected_year'] : $selected_year = '';
  !empty($args['award_type']) ? $award_type = $args['award_type'] : $award_type = '';
  !empty($args['team_id']) ? $team_id = $args['team_id'] : $team_id = '';
  !empty($args['org_id']) ? $org_id = $args['org_id'] : $org_id = '';
  if(!empty($award_type) && $award_type == 'seasonal'){ $award_condi = "type = '39' AND"; }else{ $award_condi = ''; }
  switch($type){
    case 'team':
      return $wpdb->get_results("SELECT * FROM $dbs->awards WHERE $award_condi YEAR(updatetime) = $selected_year AND JSON_CONTAINS(progression, JSON_OBJECT('team_id', $team_id))");
      break;
    case 'org':
      if(empty($org_id)) "Need organization ID to process";
      if(empty($team_id)) "Need team ID to process";
      $team_ids = $wpdb->get_results("SELECT GROUP_CONCAT(team) team_id FROM ( SELECT ros.team FROM $dbs->rosters ros INNER JOIN $dbs->orgs org ON ros.org = org.id WHERE org.id = $org_id GROUP BY team ) inner_tb");
      if(!empty($team_id)) $team_info = $wpdb->get_results("SELECT search_list team_name FROM $dbs->teams WHERE id = $team_id");
      return ['team_id'=>$team_ids, 'team_info'=>$team_info];
      break;
  }
}
function page_loader($form=null, $field, $type){
  switch($type){
    case 'game-stat':
      $form_fn = '
        function ev_form_submit(element){
          $(".tabs-panel").removeClass("dialog-active");
          var current_url = window.location.href;
          if(element.id !== current_url){
            var append_to_id = document.getElementById("'.$field.'");
//             append_to_id.classList.add("page_loader");
            var form = document.getElementById("'.$form.'");
            form.action = element.id;
            form.submit();
          }
        }
      ';
      break;
    case 'cp-team-list':
      $form_fn = '
        function cp_form_submit(element){
          var append_to_id = document.getElementById("'.$field.'");
//           append_to_id.classList.add("page_loader");
        }
      ';
      break;
    case 'camp-stat':
      $form_fn = '
        function ev_form_submit(element){
          $(".tabs-panel").removeClass("dialog-active");        
          var current_url = window.location.href;
          if(element.id !== current_url){
            var append_to_id = document.getElementById("'.$field.'");
//             append_to_id.classList.add("page_loader");
            var form = document.getElementById("'.$form.'");
            form.action = element.id;
            form.submit();
          }
        }
      ';
      break;
  }
  return $js_fn;
}
function cp_team_list($org_id, $year){
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $result = $wpdb->get_results("SELECT teams.name AS team_name, teams.level AS team_level, roster.team AS team_id FROM $dbs->rosters AS roster LEFT JOIN $dbs->orgs AS orgs ON roster.org=orgs.id LEFT JOIN $dbs->teams AS teams ON roster.team=teams.id WHERE roster.org = $org_id AND roster.event = 0 AND roster.updatetime BETWEEN $year GROUP BY roster.team ORDER BY teams.level DESC");
  return $result;
}
function g365_player_db($player_id, $arg = null, $type = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  empty($arg['org_id']) ? $org_id = "": $org_id = $arg['org_id'];
  $joins = " INNER JOIN $dbs->orgs orgs ON pl.school = orgs.id "; $condition = " WHERE orgs.id = $org_id GROUP BY orgs.id ";
  $result = $wpdb->get_results("SELECT * FROM $dbs->players pl WHERE pl.id = $player_id");
  switch($type){
    case 'org-sch':
      if(!empty($org_id) && is_numeric($org_id)){ return $wpdb->get_results("SELECT orgs.name AS org_name FROM $dbs->players pl $joins $condition"); }else{ return $result; }
      break;
  }
  return $result;
}
function cp_get_rosters( $id_data = null, $truncate = false, $admin_switch = false ) {
  $year = g365_date_format($year, 1);
  //hard coded for event_id
  if( $id_data === null || !is_array($id_data) ) return 'Need Roster parameters to start.';
	if( !is_numeric($id_data['event_id']) && !is_numeric($id_data['org_id']) && !isset($id_data['unlock']) ) return 'Need numeric id.';

	global $wpdb;
  $dbs = json_decode(dbs());
  $where_string = array();
//   if( !current_user_can( 'administrator' ) ) $where_string[] = 'roster.enabled = 0';
  
  if( is_numeric($id_data['org_id']) ) $where_string[] = 'roster.org = ' . intval( $id_data['org_id'] );
  if( is_numeric($id_data['event_id']) ) $where_string[] = 'roster.event = ' . intval( $id_data['event_id'] );
  if( is_numeric($id_data['level']) ) $where_string[] = 'roster.level = ' . intval( $id_data['level'] );
  if( is_numeric($id_data['team_id']) ) $where_string[] = 'roster.team = ' . intval( $id_data['team_id'] );
  $where_string = implode(' AND ', $where_string);
  if( !empty($where_string) ) $where_string = 'WHERE ' . $where_string;
  
  if( $id_data['order_by_master'] ) {
    $order_by = $id_data['order_by_master'];
  } else {
    $order_by = (( isset($id_data['order_by']) ) ? ('roster.' . $id_data['order_by']) : 'roster.level');
    $order_by .= (( $id_data['order_direction'] === 'ASC' ) ? ' ASC' : ' DESC');
  }
  
  $limit = '';
  if( isset($id_data['pg_no']) ) {
    $pg_no = intval($id_data['pg_no']);
    $per_pg = ( isset($id_data['per_pg']) ) ? intval($id_data['per_pg']) : 50;
    $offset = (($pg_no - 1) * $per_pg);
    $limit = "LIMIT $offset, $per_pg";
  }
  //add team and time restriction data for admin use
  if( current_user_can( 'administrator' ) && $admin_switch ) {
    $data_columns = $wpdb->get_results(
      "SELECT roster.id, teams.createdate AS team_createdate, roster.updatetime, roster.enabled, roster.org AS org_id, roster.team AS team_id, roster.event AS event_id, @level_ref:=roster.level AS level, roster.division,
      roster.team_type, roster.coach AS coach_id, roster.asst AS asst_id, roster.players, roster.description, roster.events AS event_names,
      orgs.name AS org_name, orgs.abbreviation as org_abbr, teams.name AS team_name, teams.level AS team_level, ev.name AS event_name, ev.short_name AS event_short, JSON_EXTRACT(ev.divisions, CONCAT('$.\"', @level_ref, '\"')) AS event_division,
      JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.team_restrictions')) as team_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.date_restrictions')) as date_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_name')) as pool_name,
      JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_number')) as pool_number, CONCAT(coaches.first_name,' ',coaches.last_name) AS coach_name, CONCAT(assts.first_name,' ',assts.last_name) AS asst_name
      FROM $dbs->rosters AS roster
      LEFT JOIN $dbs->orgs AS orgs ON roster.org=orgs.id
      LEFT JOIN $dbs->teams AS teams ON roster.team=teams.id
      LEFT JOIN $dbs->events AS ev ON roster.event=ev.id
      LEFT JOIN $dbs->coaches AS coaches ON roster.coach=coaches.id
      LEFT JOIN $dbs->coaches AS assts ON roster.asst=assts.id
      $where_string 
      ORDER BY $order_by $limit;"
    );
  } else {
    $data_columns = $wpdb->get_results(
      "SELECT roster.id, teams.createdate AS team_createdate, roster.updatetime, roster.enabled, roster.org AS org_id, roster.team AS team_id, roster.event AS event_id, @level_ref:=roster.level AS level, roster.division,
      roster.team_type, roster.coach AS coach_id, roster.asst AS asst_id, roster.players, roster.description, roster.events AS event_names,
      orgs.name AS org_name, orgs.abbreviation as org_abbr, teams.name AS team_name, teams.level AS team_level, ev.name AS event_name, ev.short_name AS event_short, JSON_EXTRACT(ev.divisions, CONCAT('$.\"', @level_ref, '\"')) AS event_division,
      CONCAT(coaches.first_name,' ',coaches.last_name) AS coach_name, CONCAT(assts.first_name,' ',assts.last_name) AS asst_name
      FROM $dbs->rosters AS roster
      LEFT JOIN $dbs->orgs AS orgs ON roster.org=orgs.id
      LEFT JOIN $dbs->teams AS teams ON roster.team=teams.id
      LEFT JOIN $dbs->events AS ev ON roster.event=ev.id
      LEFT JOIN $dbs->coaches AS coaches ON roster.coach=coaches.id
      LEFT JOIN $dbs->coaches AS assts ON roster.asst=assts.id
      $where_string
      ORDER BY $order_by $limit;"
    );
  }
//     ORDER BY updatetime DESC
//     LIMIT $offset, $per_pg;"
	//return message if we can't find record
	if( empty($data_columns) ) return "Couldn't retrieve roster for this id.";
  //set the return array
  $data_return = array($data_columns);
	//only return basic data
	if( $truncate )	return $data_return;
	//Format player ids, event ids, and locking to use in queries
  $ev_ids = array();
  $pl_ids = array();
  //constants for calculating age locking 
  $today = date("Y-m-d");
  $cont_target_year = (date('Y', strtotime($today)) - (( intval(date('n', strtotime($today))) > 8 ) ? 0 : 1 ));
  //loop through all results to make remaining queries
  foreach( $data_columns as $id_num => $roster_row ) {
    if( !is_null($roster_row->players) ) {
      $pl_keys = g365_validate_ids(array_keys((array)json_decode($roster_row->players)));
      if( is_array($pl_keys) ) $pl_ids = array_merge($pl_ids, $pl_keys);
    }
    if( !is_null($roster_row->event_names) ) {
      $ev_keys = g365_validate_ids(array_keys((array)json_decode($roster_row->event_names)));
      if( is_array($ev_keys) ) $ev_ids = array_merge($ev_ids, $ev_keys);
    }
    //all rosters are locked by their own level
    $birth_lock = ($cont_target_year - $roster_row->level) . "-08-15";
    $roster_row->division_selector_birth_lock = "> '" . $birth_lock . "'-OR";
    $roster_row->division_selector_class_lock = '> ' . ($cont_target_year + 18 - $roster_row->level) . '-OR';
    //get event divison type
    if( empty($roster_row->event_division) || $roster_row->event_division === 0 ) {
      $roster_row->division_selector_lock_type = 0;
    } else {
      $roster_row->event_division = json_decode($roster_row->event_division);
      $roster_row->division_selector_lock_type = ( ( is_object($roster_row->event_division) ) ? $roster_row->event_division->{$roster_row->division} : 1 );
    }
  }
  //exit if we had a bad players format
  if( empty($pl_ids) ) {
    $data_return[] = null;
  } else {
    //build the where string for however many players we need to get
    $query_where = g365_build_where(array('id'=>$pl_ids));
    //Grab player data and add them to the tree
    //change query if admin data and variable
    if( current_user_can( 'administrator' ) && $admin_switch ) {
      $players_full = $wpdb->get_results(
        "SELECT id, name, city, state, profile_img, birthday, grad_year, nickname AS url, verified, JSON_UNQUOTE(JSON_EXTRACT(notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(notes, '$.recard_img')) as recard_img, access
        FROM $dbs->players
        $query_where
        ORDER BY name;",
        OBJECT_K
      );
    } else {
      $players_full = $wpdb->get_results(
        "SELECT id, name, city, state, profile_img, grad_year, nickname AS url, verified
        FROM $dbs->players
        $query_where
        ORDER BY name;",
        OBJECT_K
      );
    }
    $data_return[] = $players_full;
  }
  
  //exit if we had a bad events format
  if( empty($ev_ids) ) {
    $data_return[] = null;
  } else {
    $query_where = g365_build_where(array('id'=>$ev_ids));
    //Grab player data and add them to the tree
    $data_return[] = $wpdb->get_results(
      "SELECT id, DATE_FORMAT(eventtime, '%m/%d/%y') AS eventtime, name, short_name, dates, locations, short_locations, nickname AS url 
      FROM $dbs->events
      $query_where
      ORDER BY eventtime;",
      OBJECT_K
    );
  }
	return $data_return;  
}

function stat_subscription($player_id, $arg = null, $type = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $event_exception_list = event_exception_list();
  
  if(empty($player_id)) return "Need player id to process";
  
  $result = $wpdb->get_results("SELECT stats.player AS player_id, stats.game, stats.event AS event_id, JSON_EXTRACT(JSON_EXTRACT( stats.stats, '$.seasons'), '$[0]') AS year_of_paid_sub, JSON_EXTRACT(JSON_EXTRACT( stats.stats, '$.events'), '$[0]') AS per_event_paid_sub, JSON_EXTRACT(JSON_EXTRACT( stats.stats, '$.monthly'), '$[0]') AS monthly_paid_sub FROM $dbs->stats AS stats WHERE stats.player = $player_id AND game = 0 AND stats.event NOT IN ($event_exception_list) AND ( JSON_EXTRACT(JSON_EXTRACT( stats.stats, '$.seasons'), '$[0]') IS NOT NULL OR JSON_EXTRACT(JSON_EXTRACT( stats.stats, '$.events'), '$[0]') IS NOT NULL OR JSON_EXTRACT(JSON_EXTRACT( stats.stats, '$.monthly'), '$[0]') IS NOT NULL ) AND stats.enabled = 1");

  $subscription_year = array(); 
  $yearly_subscription_purchased_date = array(); 
  $subscription_data = array(); 
  $monthly_subs_data = array(); 
  $subscription_event = array();

  foreach($result as $stat_subscription){
    $pl_subsc_years = json_decode($stat_subscription->year_of_paid_sub);
    if(!empty($stat_subscription->per_event_paid_sub)){ 
      $pl_subsc_events = json_decode($stat_subscription->per_event_paid_sub); 
    }
    if(!empty($stat_subscription->monthly_paid_sub)){ 
      $pl_subsc_monthly = json_decode($stat_subscription->monthly_paid_sub, true); 
    }

    // Handle yearly subscriptions
    if(!empty($pl_subsc_years)){
      foreach($pl_subsc_years as $index => $pl_subsc_year){
        $subscription_year[] = (int) $index + 1;
        $yearly_subscription_purchased_date[] = $pl_subsc_year->paid;
      }

      // Handle monthly subscriptions
      if(!empty($pl_subsc_monthly)){
        foreach($pl_subsc_monthly as $year => $months){
          foreach($months as $month => $monthly_sub){
            $monthly_subs_data[] = $monthly_sub['paid'];
          }
        }
      }
      $subscription_data = [
        'yearly_subscription' => $subscription_year, 
        'yearly_subscription_purchased_date' => $yearly_subscription_purchased_date, 
        'monthly_subscription_data' => $monthly_subs_data
      ];
    }
    elseif(empty($pl_subsc_years) && !empty($pl_subsc_monthly)){
      foreach($pl_subsc_monthly as $year => $months){
        foreach($months as $month => $monthly_sub){
          $monthly_subs_data[] = $monthly_sub['paid'];
        }
      }
      $subscription_data = ['monthly_subscription_data' => $monthly_subs_data];
    }

    // Handle per-event subscriptions (currently unused)
    if(!empty($pl_subsc_events)){
      foreach($pl_subsc_events as $key => $pl_subsc_event){
        $subscription_event[] = $key;
      }
    }
  }
  
  return array($subscription_year, $subscription_event, $subscription_data);
}

function monthly_subscription_frontend($game_data, $monthly_sub_data, $arg, $stat_type, $return_object_only = false) {
  $html_output = ''; // Initialize an empty string to accumulate HTML
  $array_output = [];
 
  foreach ($game_data as $game_stat) {
    $event_time = strtotime($game_stat->event_time);
    $subscription_data = $monthly_sub_data['monthly_subscription_data'];

    $is_within_1_month = false;

    foreach ($subscription_data as $dates) {
      if (is_array($dates)) {
        foreach ($dates as $date) {
          $subscription_time = strtotime($date);
          $diff_in_seconds = abs($event_time - $subscription_time);

          // 30 days in seconds: 30 * 24 * 60 * 60 = 2592000
          if ($diff_in_seconds <= 2592000) {
            $is_within_1_month = true;
            break 2; // Break out of both loops if a match is found
          }
        }
      } else {
        $subscription_time = strtotime($dates);
        $diff_in_seconds = abs($event_time - $subscription_time);

        if ($diff_in_seconds <= 2592000) {
          $is_within_1_month = true;
          break; // Break out of the loop if a match is found
        }
      }
    }
    
    if($game_stat->event_name !== null){
      $array_output[$game_stat->event_id] = [
        'is_within_1_month' => $is_within_1_month,
        'player_nickname' => $game_stat->player_nickname,
        'event_name' => $game_stat->event_name
      ];
    }

    $get_url_linkage = g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat');
    if($is_within_1_month){
      $is_lock_trigger = '';
      $is_ev_locked = '';
      $html_output .= '<li class="tabs-title cell '.$is_lock_trigger.'">
        <a '.$is_ev_locked.' onclick="ev_form_submit(this)" id="' . $get_url_linkage . '" href="' . $get_url_linkage . '" class="profile-title block">
            ' . htmlspecialchars($game_stat->event_name, ENT_QUOTES, 'UTF-8') . '
        </a>
      </li>';
    }else{
      $is_lock_trigger = 'event-unlock__trigger';
      $is_ev_locked = 'class="fi-lock ev_locked"';
      $html_output .= '<li class="tabs-title cell '.$is_lock_trigger.'">
        <a '.$is_ev_locked.' class="profile-title block">
            ' . htmlspecialchars($game_stat->event_name, ENT_QUOTES, 'UTF-8') . '
        </a>
      </li>';
    }
  }
  
  if($return_object_only === 'object-return') return $array_output;
  return $html_output;
}

function g365_message($arg = null){
  empty($arg["photo_year"]) ? $photo_year = "" : $photo_year = g365_date_format($arg["photo_year"], 2);
  empty($arg['media_type']) ? $media_type = "" : $media_type = $arg['media_type'];
  empty($arg['photo_approved_max']) ? $photo_approved_max = "" : $photo_approved_max = $arg['photo_approved_max'];
  empty($arg['vid_approved_max']) ? $vid_approved_max = "" : $vid_approved_max = $arg['vid_approved_max'];
  empty($arg['photo_pending_max']) ? $photo_pending_max = "" : $photo_pending_max = $arg['photo_pending_max'];
  empty($arg['vid_pending_max']) ? $vid_pending_max = "" : $vid_pending_max = $arg['vid_pending_max'];
  $not_available = "Not available.";
  $admin_pl_st = "There isn't any available stats.";
  $unavailable_opts = "There is no result for selected option(s).";
  $selected_year = "There is no result for selected year.";
  $p_ev_stat = "Participate in an event to receive stats.";
  $season_stat = " Season stats are not available. Participate in our tournaments to receive stats.";
  $selected_year_team = "Teams are currently not available with selected year.";
  $g365_ev_ranking = "Please Participate in a Grassroots 365 event to receive ranking.";
  $team_win_loss = "Team Win/Loss % is not available.";
  $p_ev_award = "Participate in a Grassroots 365 event to receive awards.";
  $champ_award = "Championship award is not available.";
  $seasonal_award = "Seasonal award is not available.";
  $team_ranking = "Team Ranking is not available.";
  $passport_avg = "Upgrade to the passport to view season averages.";
  $pp_admin = "This profile is locked";
  $pp_ev_admin = "Only this event is unlocked: ";
  $db_message = "Missing database table(s).";
  $camp_stat = "Participate in an EBC camp to receive stats.";
  $gm_result = "Game result is not available.";
  $access_deny = "You do not have access to this page. Please contact our customer service.";
  $no_roster = "No roster uploaded for this team.";
  $unlock_tsc = "Please unlock The Stage event(s).";
  $team_standing = "Team standing is not available.";
  $photo_upload = "No photo available in this photo gallery.";
  $changes_saved = "Your changes have been successfully saved.";
  $dup_file_name = "Duplicate file name found. Please change file name to continue.";
  $claimed_pl = "You have not claimed any players under this account.";
  $admin_user_photo = "List of ". $media_type ."s that are assigned to players by OGP admin.";
  $approved_max_limit = "You have reached your approved photo upload limit(". $photo_approved_max ."). Please contact administrator for more information.";
  $vid_approved_max_limit = "You have reached your approved video upload limit(". $vid_approved_max ."). Please contact administrator for more information.";
  $pending_max_limit = "You have reached your pending photo upload limit(". $photo_pending_max ."). Please contact administrator for more information.";
  $vid_pending_max_limit = "You have reached your pending photo upload limit(". $vid_pending_max ."). Please contact administrator for more information.";
  $photo_max_limit = "You have reached your approved(". $photo_approved_max .")/pending(". $photo_pending_max .") photo upload limit. Please contact administrator for more information.";
  $video_max_limit = "You have reached your approved(". $vid_approved_max .")/pending(". $vid_pending_max .") photo upload limit. Please contact administrator for more information.";
  $photo_unlocked = "Unlock Passport ". $photo_year ." Season to view this photo.";
  $remove_file = "File is deleted.";
  $pp_ph_link = "Unlock the passport to gain access to photos, stats, awards, and more!";
  $admin_view = "You're in admin view mode";
  $admin_no_photo = "No photo available.";
  $video_upload = "No video available in this video gallery.";
  $admin_user_video = "List of videos that are assigned to players by OGP admin.";
  $max_upload_at_a_time = "<p>Max upload of <strong>5</strong> media files at a time.</p>";
  $photo_allowed_file = "<p>File format: ".strtoupper(implode(', ', g365_media_file_type('photo'))).".</p>";
  $video_allowed_file = "<p>File format: ".strtoupper(implode(', ', g365_media_file_type('video'))).".</p>";
  $profile_video = "<p>You can have one profile video display on your profile. To change default profile video, unpublish video and set a different video to public.</p>";
  $api_slb_no_event_year = "No available events for selected year";
  $recent_achievements = "Recent In Game Achievements";
  $no_achievements = "Achievements badges are not available";
  $event_calendar = "Event list is currently unavailable. Please check back later.";
  $result = array('not_available'=>$not_available, 'admin_pl_st'=>$admin_pl_st, 'unavailable_opts'=>$unavailable_opts, 'selected_year'=>$selected_year,'p_ev_stat'=>$p_ev_stat, 'season_stat'=>$season_stat, 'selected_year_team'=>$selected_year_team, 'g365_ev_ranking'=>$g365_ev_ranking, 'team_win_loss'=>$team_win_loss, 'p_ev_award'=>$p_ev_award, 'champ_award'=>$champ_award, 'team_ranking'=>$team_ranking, 'passport_avg'=>$passport_avg, 'pp_admin'=>$pp_admin, 'pp_ev_admin'=>$pp_ev_admin, 'db_message'=>$db_message, 'camp_stat'=>$camp_stat, 'gm_result'=>$gm_result, 'access_deny'=>$access_deny, 'no_roster'=>$no_roster, 'unlock_tsc'=>$unlock_tsc, 'team_standing'=>$team_standing, 'photo_upload'=>$photo_upload, 'changes_saved'=>$changes_saved, 'dup_file_name'=>$dup_file_name, 'claimed_pl'=>$claimed_pl, 'photo_unlocked'=>$photo_unlocked, 'admin_user_photo'=>$admin_user_photo, 'approved_max_limit'=>$approved_max_limit, 'pending_max_limit'=>$pending_max_limit, 'photo_max_limit'=>$photo_max_limit, 'remove_file'=>$remove_file, 'pp_ph_link'=>$pp_ph_link, 'admin_view'=>$admin_view, 'admin_no_photo'=>$admin_no_photo, 'video_upload'=>$video_upload, 'admin_user_video'=>$admin_user_video, 'max_upload_at_a_time'=>$max_upload_at_a_time, 'photo_allowed_file'=>$photo_allowed_file, 'video_allowed_file'=>$video_allowed_file, 'vid_approved_max_limit'=>$vid_approved_max_limit, 'vid_pending_max_limit'=>$vid_pending_max_limit, 'video_max_limit'=>$video_max_limit, 'profile_video'=>$profile_video, 'seasonal_award'=>$seasonal_award, 'api_slb_no_event_year'=>$api_slb_no_event_year, 'recent_achievements'=>$recent_achievements, 'event_calendar'=>$event_calendar, 'no_achievements'=>$no_achievements);
  return $result;
}
function sortable_stat($arg = null,$type = null){
  switch($type){
    case 'cts_stat':
      $cts_stats = array('win', 'pct', 'ppg', 'opp_ppg');
      $cts_st_lists = array();
      $select_type = $_POST['cts_type'];
      foreach($cts_stats as $cts_stat){
        if(!isset($select_type)){
          $default = 'selected=selected';
          $select_type = '';
        }else{
          $default = '';
        }
        if(isset($select_type) && ($select_type == $cts_stat)){
          $is_selected_type = 'selected=selected';
        }else{
          $is_selected_type = '';
        }
        $cts_st_lists[] = '<option '.$is_selected_type.' value='.$cts_stat.'>'.str_replace('_', ' ', strtoupper($cts_stat)).'</option>';
      }
      $cts_st_lists = implode('', $cts_st_lists);
      $form = '
        <div class="small-12 medium-12 large-7">
          <form method="post" action="" name="statleader-form" id="statleader-form hi" class="grid-x">
            <div class="small-12 large-3 small-padding-right" style="width: 200px">
              <select style="border-radius: 20px" name="cts_type" id="cts_type" style="border-radius: 20px">
              <option '.$default.' value="" >Sort by</option>
                '.$cts_st_lists.'
              </select>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
  }
}
//duuu
function year_dd_opt($type, $arg = null){
  if(!empty($pl_id)){ $pl_id = $pl_id; }else{ $pl_id = ''; }
  switch($type){
    case 'most_recent_event':
      $available_stat_years = g365_year_end_date('event_time', most_recent_event(2)); 
      break;
    case 'cp_date_selector': // Club program year
      $available_stat_years = g365_year_end_date('event_date', cp_date_selector($pl_id, 'ev_ros'));
//      echo "<script>console.log('Debug Objects: " . json_encode(cp_date_selector($pl_id, 'ev_ros')) . "' );</script>";
      break;
    case 'ct_year': // Club team year
      $available_stat_years = g365_year_end_date('game_year', cp_date_selector($pl_id, 'team_game'));
      break;
  }
  $year_list = array();
  $mobile_app_year_list = array();
  $redir = ''; $is_selected_type = '';
  if(!empty($available_stat_years)){
    foreach($available_stat_years as $available_stat_year){
      if(isset($_POST['year'])){ $select_year = $_POST['year']; }
      if(!isset($select_year) && empty($arg[0])){
        $select_year = $available_stat_years[0]; // Set a default year to the lastest available year in dropdown
      }
      else if(!isset($select_year) && !empty($arg[0])){
        $select_year = $arg[0];
        if($select_year == $available_stat_year){
          $is_selected_type = 'selected=selected';
        }else{
          $is_selected_type = '';
        }
      }
      else if(isset($select_year)){
        if($select_year == $available_stat_year){
          $is_selected_type = 'selected=selected';
          if(!empty($arg[0])){
            $redir = './';
          }
        }else{
          $is_selected_type = '';
        }
      }
      $year_list[] = '<option '.$is_selected_type.' value='.$available_stat_year.'>'.g365_date_format($available_stat_year, 2).' Season </option>';
      $mobile_app_year_list[] = [''.$available_stat_year.'' => g365_date_format($available_stat_year, 2).' Season'];
    }
  }
  switch($type){
    case 'dcp_year': // Digital Coach Packet year base on club team year
      $available_stat_years = g365_year_end_date('game_year', cp_date_selector($pl_id, 'team_game'));
      foreach($available_stat_years as $available_stat_year){
        $select_year = $arg['post_year']; // $_POST['year'];
        if(isset($select_year) && ($select_year == $available_stat_year)){
          $is_selected_type = 'selected=selected';
        }else{$is_selected_type = '';}
        $year_list[] = '<option '.$is_selected_type.' value='.$available_stat_year.'>'.g365_date_format($available_stat_year, 2).' Season </option>';
        $mobile_app_year_list[] = [''.$available_stat_year.'' => g365_date_format($available_stat_year, 2).' Season'];
      }
      break;
    case 'spp_app_year': // Get a list of available years for team standing to use with SPP app.
      $available_stat_years = g365_year_end_date('game_year', cp_date_selector($pl_id, 'team_game'));
      foreach($available_stat_years as $available_stat_year){
        $select_year = $arg['post_year']; // $_POST['year'];
        if(isset($select_year) && ($select_year == $available_stat_year)){
          $is_selected_type = 'selected=selected';
        }else{$is_selected_type = '';}
        $year_list[] = $available_stat_year;
        $mobile_app_year_list[] = [''.$available_stat_year.'' => g365_date_format($available_stat_year, 2).' Season'];
      }
      break;
  }
  $year_list_array = $year_list;
  $year_list = implode('', $year_list);
  $form = '
    <div class="small-12 medium-12 large-12">
      <form method="post" action="./" name="statleader-form" id="statleader-form" class="grid-x stat-clubs">
        <div class="small-12 large-3 small-padding-right h" style="width: 200px; margin-left: auto;">
          <select onchange="this.form.submit()" name="year" id="year" style="border-radius: 20px">
            '.$year_list.'
          </select>
        </div>
      </form>
    </div>
  ';
   $cts_form = '
    <div class="small-12 medium-12 large-12">
      <div class="small-12 large-3 small-padding-right" style="width: 200px">
        <select onchange="this.form.submit()" name="year" id="year" style="border-radius: 20px">
          '.$year_list.'
        </select>
      </div>
    </div>
   ';
   $dcp_form = '
    <div class="small-12 large-3 small-padding-right" style="width: 200px">
      <select name="g365_year" id="year" style="border-radius: 20px">
        '.$year_list.'
      </select>
    </div>
   ';
  return array($form, $select_year, $cts_form, $dcp_form, $year_list_array, $mobile_app_year_list);
}
function url_param($param){
  if (isset($_GET[$param])) { $param_name = $_GET[$param]; }else{ $param_name = ""; }
  return $param_name;
}
function g365_url_linkage($arg = null, $type = null){
  switch($type){
    case 'game-stat':
    case 'camp-stat':
      $url = get_site_url(); $sub_dir = '/player/'.$arg[0].'/profiles/'.$arg[1].'/'; $params = strtolower(preg_replace('/\s+|\.|-/', '-', $arg[2]));
      if($arg[3] == false){
        return $url.$sub_dir.'#'.$params;
      }else{
        return $params;
      }
      break;
    case 'tc-tournament-stat': // tc: tournament/camp
      $url = get_site_url(); $sub_dir = '/player/'.$arg[0].'/stats/'.$arg[1].'/'.$arg[4].'/'.$arg[5]; $params = strtolower(preg_replace('/\s+|\.|-/', '-', $arg[2]));
      if($arg[3] == false){
        return $url.$sub_dir.'/#'.$params;
      }else{
        return $params;
      }
      break;
    case 'tc-camp-stat': // tc: tournament/camp
      $url = get_site_url(); $sub_dir = '/player/'.$arg[0].'/stats/'.$arg[1].'/'.$arg[4].'/'.$arg[5]; $params = strtolower(preg_replace('/\s+|\.|-/', '-', $arg[2]));
      if($arg[3] == false){
        return $url.$sub_dir.'/#'.$params;
      }else{
        return $params;
      }
      break;
    case 'tbs_to_pl': // Team box score to player profile
      $url = get_site_url(); $sub_dir = '/player/'.$arg[0].'/stats/'.$arg[1].'/'.$arg[2].'/tournament'; $params = strtolower(preg_replace('/\s+|\.|-/', '-', $arg[3]));
      return '<a href="'.$url.$sub_dir.'/#'.$params.'" target="_blank">'.$arg[4].'</a>';
      break;
  }
}
function blur_box($year){
  $year = g365_date_format($year, 2);
  $result = '
    <div id="profile-stats-avg" class="cell large-margin-bottom  small-12">
      <div class="ave_field large-margin-bottom table-scroll">
        <h3 class="stats__table-heading">'.$year.' Season Averages</h3>
        <table class="text-left ghost-white-bg no-margin-bottom">
          <tbody class="stats__table--player event-unlock__trigger">
            <tr>
              <th>PPG</th>
              <th>RPG</th>
              <th>APG</th>
              <th>BPG</th>
              <th>SPG</th>
              <th>3PT</th>
            </tr>
            <tr class="color-body emphasis blur_box">
              <td>N/A</td>
              <td>N/A</td>
              <td>N/A</td>
              <td>N/A</td>
              <td>N/A</td>
              <td>N/A</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  ';
  return $result;
}
function pp_pay_link($year, $isPopup = false){
  $year = g365_date_format($year, 2);
  $pp_link = '<a href="'.get_site_url().'/product/passport-annual/" class="small-padding locked__link locked__link--annual">Get the '.$year.' Season Passport </a>';
  $default = '
    <div class="locked__image-wrapper large-12">' .
      ($isPopup ? '' : '<img src="https://sportspassports.com/wp-content/uploads/2021/09/locked-placeholder-img.png" alt="Locked stats placeholder" class="locked__image">')
      .'<div class="locked__modal-wrapper text-center">
        <i class="fi-unlock locked__modal--icon"></i>
        <h3>Upgrade to the Passport!</h3>
        <ul class="locked__modal--list">
          <li>One-time Age/Grade Verification</li>
          <li>Real-time Stats</li>
          <li>Professional Headshot</li>
          <li>Awards and Achievement Badges</li>
          <li>Photo/Video Storage</li>
          <li>Player Exposure</li>
        </ul>
        <a href="https://sportspassports.com/product/passport-annual/" class="small-padding locked__link locked__link--annual">Get the '.$year.' Season Passport</a>
      </div>
    </div>
  ';
  $hover_opt = '
    <div class="event__tooltip--wrapper">
      <div class="event__tooltip small-padding" id="eventTooltip">
       <i class="fi-x event__tooltip--exit"></i>
        <i class="fi-unlock locked__modal--icon"></i>
        <h3>Upgrade to the Passport!</h3>
        <ul class="locked__modal--list">
          <li>One-time Age/Grade Verification</li>
          <li>Real-time Stats</li>
          <li>Professional Headshot</li>
          <li>Awards and Achievement Badges</li>
          <li>Photo/Video Storage</li>
          <li>Player Exposure</li>
        </ul>
        '.$pp_link.'
      </div>
    </div>
  ';
  return array($default, $hover_opt);
}
function get_event($ev_id, $type = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  switch($type){
    case 'dcp-team-ev':
      return $wpdb->get_results("SELECT ev.name AS ev_name, ev.org AS ev_org, ev.logo_img, ev.eventtime, ev.locations FROM $dbs->events ev WHERE ev.id = $ev_id");
      break;
  }
  if( empty($ev_id) ) return "Need event id to process";
  return $wpdb->get_results("SELECT ev.name AS ev_name FROM $dbs->events ev WHERE ev.id = $ev_id");
}
function get_tm_ranking($team_id, $arg = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  if(empty($team_id)) return 'Need team id to process';
  return $wpdb->get_results("SELECT org.name AS org_name, tm.search_list AS team_name, tm.level AS team_level, tm.name AS team_name_only FROM $dbs->rosters ros LEFT JOIN $dbs->orgs org ON org.id = ros.org LEFT JOIN $dbs->teams tm ON tm.id = ros.team WHERE tm.id = $team_id");
}
function g365_custom_url($type, $arg = null){
  switch($type){
    case 'cp_team_url':
      $url = "?team_id=".$arg[0]."&y=".$arg[1];
      break;
  }
  return $url;
}
function cts_type_selector($type = null, $arg = null){
  $yb_levels = "14,13,12,11,10,9,8"; //0
  $yg_levels = "17,16,15,44,43,42,41,40"; //1
  $hs_levels = "17,16,15,14,13,12"; //2
  $all_levels = "17,16,15,14,13,12,11,10,9,8,47,46,45,44,43,42,41,40"; //4
  $lv_pl = "Open,Gold,Silver,Bronze,Copper"; //3
  $level_catagories = "Youth Boys,Youth Girls,High School"; //5
  $brand_pick = "All Brands,grassroots-365,the-stage,scholastic-series,hype-her-hoops-circuit,breakthrough-circuit"; //6
  $stage_level_catagories = "Youth Boys,High School"; //7
  $hhh_level_catagories = "Youth Girls,High School"; //8
  $ybhs_levels = '17,16,15,14,13,12,11,10,9,8'; //9
  $yghs_levels = '17,16,15,14,13,12,44,43,42,41,40'; //10
  $updated_ybhs_levels = 'All Levels,17,16,15,14,13,12,11,10,9,8'; //11
  $app_brands = ["3191" => "grassroots-365","3" => "the-stage","7165" => "scholastic-series","7164" => "hype-her-hoops-circuit", "7729" => "breakthrough-circuit"];
  return [$yb_levels, $yg_levels, $hs_levels, $lv_pl, $all_levels, $level_catagories, 'youth-boys'=>$yb_levels, 'youth-girls'=>$yg_levels, 'high-school'=>$hs_levels, $brand_pick, $stage_level_catagories, $hhh_level_catagories, $ybhs_levels, $yghs_levels, $updated_ybhs_levels, 'brands'=>$app_brands];
}
function cts_st_tb($arg = null, $type = null){
  $validate_pl_stat = array();
  !empty($arg[0]['player_info']['stats']) ? $stats = $arg[0]['player_info']['stats'] : $stats = '';
  !empty($arg[0]['player_info']['player_name']) ? $pl_name = ucwords(strtolower($arg[0]['player_info']['player_name'])) : $pl_name = '';
  !empty($arg[0]['player_info']['player_nickname']) ? $pl_nickname = ucwords(strtolower($arg[0]['player_info']['player_nickname'])) : $pl_nickname = '';
  $tb_head = '<div class="large-margin-bottom table-scroll"><table class="text-left ghost-white-bg small-margin-bottom"><tbody class=""><tr class="stats__table--playerGames"><th>NAME</th><th>PTS</th><th>REB</th><th>AST</th><th>BLK</th><th>STL</th><th>3PT</th></tr>';
  $tb_head_close = '</tbody></table></div>';
  $st_tb_head = '<tr class="color-body emphasis">';
  $st_tb_body =  '<td>'.(empty($pl_name) ? '-' : g365_url_linkage(array($pl_nickname,$arg[1],$arg[2],$arg[3],$pl_name),'tbs_to_pl')).'</td><td>'.(empty($stats['pts']) ? 0 : $stats['pts']).'</td><td>'.(empty($stats['rbs']) ? 0 : $stats['rbs']).'</td><td>'.(empty($stats['ast']) ? 0 : $stats['ast']).'</td><td>'.(empty($stats['blk']) ? 0 : $stats['blk']).'</td><td>'.(empty($stats['stl']) ? 0 : $stats['stl']).'</td><td>'.(empty($stats['three_pt']) ? 0 : $stats['three_pt']).'</td></tr>';
  switch($type){
    case 1:
      $container = '<div class="cell small-12"><div id="profile-stats-avg" class="cell small-12"><h3 class="stats__table-heading"><?php echo $game_stat->event_name; ?></h3></div><div class="ave_field large-margin-bottom table-scroll"><strong>Event Averages:</strong>';
      $container_close = '</div></div>';
      $st_tb_head = '<table class="text-left ghost-white-bg small-margin-bottom"><tbody class=""><tr class="stats__table--playerGames"><th>PPG</th><th>RPG</th><th>APG</th><th>BPG</th><th>SPG</th><th>3PT</th></tr>';
      $avg_tb = '<tr class="color-body emphasis"></tr></tbody></table>';
      return array($container,$container_close);
      break;
    case 2: // Filter out 0s stats
      foreach($arg[0] as $pl_data){
        $pts = $pl_data['player_info']['stats']['pts']; $ast = $pl_data['player_info']['stats']['ast']; $rbs = $pl_data['player_info']['stats']['rbs']; $stl = $pl_data['player_info']['stats']['stl']; $three_pt = $pl_data['player_info']['stats']['three_pt']; $blk = $pl_data['player_info']['stats']['blk']; $play_min = explode(':', $pl_data['player_info']['stats']['time_pl']); $play_min = $play_min[1]; $sum_stats = $pts+$ast+$rbs+$stl+$three_pt+$blk;
        if($sum_stats>0 || $play_min>=5){$validate_pl_stat[] = $pl_data;}
      }
      return $validate_pl_stat;
      break;
    case 3:
      return array($tb_head, $st_tb_head.$st_tb_body, $tb_head_close);
      break;
    case 4:
      $tb_head = '<div class="table-scroll"><table class="text-left ghost-white-bg no-margin-bottom"><tbody class=""><tr class="stats__table--playerGames"><th>GAME RESULTS</th><th>PTS</th><th>REB</th><th>AST</th><th>BLK</th><th>STL</th><th>3PT</th></tr>';
      return array($tb_head);
      break;
  }
}
function cts_dialog_js($arg = null, $type = null){
 $script  = '<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">';
  $script = '<link rel="stylesheet" href="/resources/demos/style.css">';
 $script .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js?loc=5"></script>';
 $script .= '<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js?loc=6"></script>';
   $script = '<script>var loadingText = "<div class=\"loader\"><h1>Loading...&nbsp;&nbsp;&nbsp;<svg style=\"stroke:white;\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d=\"M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z\" opacity=\".25\"/><path d=\"M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z\" class=\"spinner_ajPY\"/></svg><h1></div>";</script>';
  switch($type){
    case 'dcp-tm-ros':
      $script .= '
        <script>
          function dcp_tm_ros(pointer){
            var box_score_dialog2 = $("#dialong_div").dialog({
              autoOpen: false, 
              open: function (event, ui){
                $("body").css({ overflow: "hidden" });
              },
              close: function(event, ui){
                $("body").css({ overflow: "visible" });
              },
              autoOpen: false,
              modal: true,
              width: "90%",
              closeOnEscape: true,
              responsive: true,
              title: "Recruit Player"
            });
            
            // reset color for loading...
            $(".ui-dialog-content").css({background: "transparent"});
          
            var pl_id = pointer.dataset.plId;
            var site_url = pointer.dataset.urlLink;
            var ev_id = pointer.dataset.evId;
            var url = site_url+"/account/dcp/teams/"+ev_id+"/?type=player-event&pl="+pl_id;

            box_score_dialog2.html("Loading...").dialog("open");
            
            // style for loading small and centered
            $(box_score_dialog2[0].parentElement).css({
                 "position": "fixed",
                 "top": "calc(50vh - 50px)",
                 "left": "calc(50vw - 100px)",
                 "width": "200px",
                 "height": "100px"
            });
              
            $.get(url, function(data) {
              var tempDiv = $("<div>").html(data);
              var eventContent = tempDiv.find(".dcp-event").html();
              
              box_score_dialog2.html(eventContent);
              $(".recruit-player").parent().parent().css({background: "rgb(125, 121, 121)"});
              $(box_score_dialog2[0].parentElement).css({
                 "position": "fixed",
                 "top": "150px",
                 "left": "5%",
                 "height": "auto",
                 "width": "90%",
                 "display": "",
                 "z-index": "103"
              });
              
              $(box_score_dialog2[0].parentElement).on("click", ".btn-close", function (event){
                  box_score_dialog2 && box_score_dialog2.dialog("close");
              });
              
              return false;
            });
          }
        </script>
      ';
      return $script;
      break;
  }
  $script .= '
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            function view_result(result_team_id){$("#"+result_team_id+"-result_box").toggle();}
            var box_score_dialog = $("#dialong_div").dialog({
              autoOpen: false, 
              open: function (event, ui){$("body").css({ overflow: "hidden" });$(".pl_box_score").css({height:"900px", overflow:"auto"});},
              close: function(event, ui){$("body").css({ overflow: "visible" });},
              open: function (event, ui){$(".ui-dialog").css({ margin: "40px" });$("#masthead").css({display: "block"});$("#site-footer").css({display: "block"});$(".full_width_container").css({display: "block"});},
              autoOpen: false,
              modal: true,
              width: "100%",
              margin: 0,
              closeOnEscape: true,
              responsive: true
            });
            function pl_box_score(pointer){
              var game_id = pointer.dataset.gameId;
              var gamed_date = pointer.dataset.gameDate;
              if(typeof gamed_date === "undefined"){ gamed_date = ""; }else{ gamed_date = gamed_date; }
              var team_id = pointer.dataset.teamId;
              var event_name = pointer.dataset.eventName;
              var select_year = pointer.dataset.selectYear;
              var url = pointer.dataset.url;
              $(".tabs-panel").addClass("dialog-active");
              box_score_dialog.html("Loading...").load(url+"/club-team-standing/team-box-score/_/_/"+team_id+"/"+game_id+"/"+select_year+"/").dialog({title: event_name+gamed_date}).dialog("open");
              return false;
            }
          });
        </script>
      ';
  return $script;
}
function pl_stat_season_options($arg = null, $type = null){
  global $wp_query;
  if(!empty($arg[0])){ 
    $check_subscription = stat_subscription($arg[0]); 
    $available_stat_years = g365_year_end_date('event_date', cp_date_selector($arg[0], 'pl_stat'));
  }else{
    $check_subscription[0] = '';
    $check_subscription[1] = '';
    $check_subscription[2] = '';
    $available_stat_years = '';
  }
  if(isset($_POST['year'])){ $select_year = $_POST['year']; }
  $y_param = $wp_query->query_vars['y'];
  if(empty($available_stat_years)){
    $available_stat_years = g365_year_end_date('event_date', array((object) array('event_date'=>date('Y'))));
  }else{
    $available_stat_years = $available_stat_years;
  }
  if (isset($wp_query->query_vars['pl_tp'])){
    $event_id = $wp_query->query_vars['pl_tp'];
  }
  if(!isset($select_year)){
    if(!empty($y_param)){
      $select_year = $y_param;
    }else{
      $select_year = $available_stat_years[0];            
    }
  }
  return array($select_year, $available_stat_years, $check_subscription[0], $check_subscription[1], $check_subscription[2]);
}
function g365_submenu_type($arg = null, $type = null){
  // 0: $wp_query->query_vars["pg_type"]; 1: $select_year; 2: $wp_query->query_vars["lv_label"];
  if(empty($arg[0])){ $arg[0] = ''; }
  if(empty($arg[0]) || strtolower($arg[0]) === 'youth-boys'){
    $yb_active = ' cts--active '; 
  }else{$yb_active = '';}
  if(strtolower($arg[0]) === 'youth-girls'){
    $yg_active = ' cts--active '; 
  }else{$yg_active = '';}
  if(strtolower($arg[0]) === 'high-school'){
    $hs_active = ' cts--active '; 
  }else{$hs_active = '';}
  $key_level = (g365_return_keys('g365_grade_key'));
  switch($type){
    case 1:
      $lv = 'all-levels';
      $form = '
        <div>
          <div class="cts_btn_group">
            <a class="cts_btn cts_btn--filter '.$yb_active.'" tabindex="0" href="'.get_site_url().'/club-team-standing/youth-boys/'.$lv.'/?y='.$arg[1].'">Youth Boys</a>
            <a class="cts_btn cts_btn--filter '.$yg_active.'" tabindex="0" href="'.get_site_url().'/club-team-standing/youth-girls/'.$lv.'/?y='.$arg[1].'">Youth Girls</a>
            <a class="cts_btn cts_btn--filter '.$hs_active.'" tabindex="0" href="'.get_site_url().'/club-team-standing/high-school/'.$lv.'/?y='.$arg[1].'">High School</a>
          </div>
        </div>
      ';
      break;
    case 2:
      $lv = (empty($arg[2]) ? '' : $arg[2]);
      $yb_lv_list = array(); $yg_lv_list = array(); $hs_lv_list = array();
      $yb_lv_arrs = explode(',', cts_type_selector()[0]);  $yg_lv_arrs = explode(',', cts_type_selector()[1]);  $hs_lv_arrs = explode(',', cts_type_selector()[2]);
      foreach($yb_lv_arrs as $yb_lv_arr){$yb_lv_list[] = '<a href="'.get_site_url().'/club-team-standing/youth-boys/'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$yb_lv_arr])).'/'.$yb_lv_arr.'/?y='.$arg[1].'"><li>'.$key_level[$yb_lv_arr].'</li></a>';}
      foreach($yg_lv_arrs as $yg_lv_arr){$yg_lv_list[] = '<a href="'.get_site_url().'/club-team-standing/youth-girls/'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$yg_lv_arr])).'/'.$yg_lv_arr.'/?y='.$arg[1].'"><li>'.$key_level[$yg_lv_arr].'</li></a>';}
      foreach($hs_lv_arrs as $hs_lv_arr){$hs_lv_list[] = '<a href="'.get_site_url().'/club-team-standing/high-school/'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$hs_lv_arr])).'/'.$hs_lv_arr.'/?y='.$arg[1].'"><li>'.$key_level[$hs_lv_arr].'</li></a>';}
      $yb_lv_list = implode('', $yb_lv_list);  $yg_lv_list = implode('', $yg_lv_list);  $hs_lv_list = implode('', $hs_lv_list);
      $form = '
        <div class="small-12 medium-12 large-5 small-padding-bottom">
          <div class="cts_btn_group">
            <ul class="dropdown menu" data-dropdown-menu>
              <li>
                <a class="cts_btn cts_btn--filter '.$yb_active.'" tabindex="0" href="">Youth Boys</a>
                <ul class="cts_ddm menu">
                  <a href="'.get_site_url().'/club-team-standing/youth-boys/all-levels/?y='.$arg[1].'"><li>All Levels</li></a>
                  '.$yb_lv_list.'
                </ul>
              </li>
              <li>
                <a class="cts_btn cts_btn--filter '.$yg_active.'" tabindex="0" href="">Youth Girls</a>
                <ul class="cts_ddm menu">
                  <a href="'.get_site_url().'/club-team-standing/youth-girls/all-levels/?y='.$arg[1].'"><li>All Levels</li></a>
                  '.$yg_lv_list.'
                </ul>
              </li>
              <li>
                <a class="cts_btn cts_btn--filter '.$hs_active.'" tabindex="0" href="">High School</a>
                <ul class="cts_ddm menu">
                  <a href="'.get_site_url().'/club-team-standing/high-school/all-levels/?y='.$arg[1].'"><li>All Levels</li></a>
                  '.$hs_lv_list.'
                </ul>
              </li>
            </ul>
          </div>
        </div>
      ';
      break;
     case 3:
      if(empty($arg[0]) || strtolower($arg[0]) === 'tournament'){
        $tournament_active = ' cts--active '; 
      }else{$tournament_active = '';}
      if(strtolower($arg[0]) === 'camp'){
        $camp_active = ' cts--active '; 
      }else{$camp_active = '';}
      if(strtolower($arg[0]) === 'scholastic'){
        $scholastic_active = ' cts--active '; 
      }else{$scholastic_active = '';}
      $form = '
        <div class="cts_btn_group player-stat-font">
          <a style="text-decoration:none" class="cts_btn cts_btn--filter '.$tournament_active.' player-tab-font" tabindex="0" href="'.get_site_url().'/player/'.$arg[1].'/stats/_/'.$arg[2].'/tournament">Tournaments</a>
          <a style="text-decoration:none" class="cts_btn cts_btn--filter '.$camp_active.' player-tab-font" tabindex="0" href="'.get_site_url().'/player/'.$arg[1].'/stats/_/'.$arg[2].'/camp">Camps</a>
          <a style="text-decoration:none" class="cts_btn cts_btn--filter '.$scholastic_active.' player-tab-font" tabindex="0" href="'.get_site_url().'/player/'.$arg[1].'/stats/_/'.$arg[2].'/scholastic">Scholastic</a>
        </div>
      ';
      break;
    case 4:
      $lv = $arg[2];
      $lv_pl_list = array();
      $lv_pl_arrs = explode(',', cts_type_selector()[3]);
      foreach($lv_pl_arrs as $lv_pl_arr){$lv_pl_list[] = '<a href="'.get_site_url().'/club-team-standing/youth-boys/all-levels/_/_/_/'.$arg[1].'/'.strtolower($lv_pl_arr).'"><li>'.$lv_pl_arr.'</li></a>';}
      $lv_pl_list = implode('', $lv_pl_list);
      $form = '
        <div class="small-12 medium-12 large-5 small-padding-bottom small-padding-left">
          <div class="cts_btn_group">
            <ul class="dropdown menu" data-dropdown-menu>
              <li>
                <a class="cts_btn cts_btn--filter '.$yb_active.'" tabindex="0" href="">Level Of Play</a>
                <ul class="cts_ddm menu">
                  <a href="'.get_site_url().'/club-team-standing/youth-boys/all-levels/?y='.$arg[1].'"><li>All Levels</li></a>
                  '.$lv_pl_list.'
                </ul>
              </li>
            </ul>
          </div>
        </div>
      ';
      break;
    case 5:
      global $wp_query;
      $yb_lv_list = array(); $yg_lv_list = array(); $hs_lv_list = array(); $lv_pl_list = array();
      $yb_lvs = explode(',', cts_type_selector()[0]);
      foreach($yb_lvs as $yb_lv){if(isset($_POST['group_lv']) && $_POST['group_lv'] == strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$yb_lv]))){$is_selected = 'selected=selected';}else{$is_selected = '';} $yb_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$yb_lv])).'">'.$key_level[$yb_lv].'</option>';}
      $yb_lv_list = implode('', $yb_lv_list);
      $yg_lvs = explode(',', cts_type_selector()[1]);
      foreach($yg_lvs as $yg_lv){$yg_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$yg_lv])).'">'.$key_level[$yg_lv].'</option>';}
      $yg_lv_list = implode('', $yg_lv_list);
      $hs_lvs = explode(',', cts_type_selector()[2]);
      foreach($hs_lvs as $hs_lv){$hs_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$key_level[$hs_lv])).'">'.$key_level[$hs_lv].'</option>';}
      $hs_lv_list = implode('', $hs_lv_list);
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lv_pls as $lv_pl){$lv_pl_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(array(" / ","/"," "),array("-","-","-"),$yb_lv)).'">'.$lv_pl.'</option>';}
      $lv_pl_list = implode('', $lv_pl_list); $pg_type = $wp_query->query_vars['pg_type']; $cts_url = get_site_url().'/club-team-standing/';
      echo year_dd_opt('ct_year',array(url_param('y')))[2];
      if (!empty($_POST['group_lv'])){
//         echo '<script type="text/javascript">window.location = "'.$cts_url.$pg_type.'/'.$aa.'" </script>';
      }
      $form = '
       <div>
          <form method="post" action="https://dev.grassroots365.com/club-team-standing/youth-boys/14u-8th-grade" id="statleader-form" class="grid-x">
            <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
              <select name="group_lv" id="group_lv" style="border-radius: 20px"> 
                <option disabled>Youth Boys</option>
                <option value="yb-all-levels">All Youth Boy Levels</option>
                '.$yb_lv_list.'
                <option disabled>Youth Girls</option>
                <option value="yg-all-levels">All Youth Girl Levels</option>
                '.$yg_lv_list.'
                <option disabled>High School</option>
                <option value="hs-all-levels">All High School Levels</option>
                '.$hs_lv_list.'
              </select>
            </div>
            <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
              <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px"> 
                <option disabled>Level of Play</option>
                <option>All Level of Play</option>
                '.$lv_pl_list.'
              </select>
            </div>
            TEST
            '. ($selected_org_id ? ('<input type="hidden" name="organization-id" value="'. $selected_org_id .'">') : '') .'
            <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
              <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 6:
      global $wp_query;
      $hs_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
      $hs_lvs = explode(',', cts_type_selector()[2]);
      $lvs_play = g365_division(null, 'dcp');
      foreach($hs_lvs as $hs_lv){ if(isset($post_gp_lv) && $post_gp_lv == $key_level[$hs_lv]){ $is_selected = 'selected=selected'; }elseif(!isset($post_gp_lv) && $key_level[$hs_lv] == '17U'){ $is_selected = 'selected=selected'; }else{ $is_selected = ''; }
      $hs_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$hs_lv].'">'.$key_level[$hs_lv].'</option>'; }
      $hs_lv_list = implode('', $hs_lv_list);
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
      $lv_play_list[] = '<option '.$lv_selected.' value="'.$lv_play.'">'.$lv_play.'</option>'; }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                    <option value="">All Levels of Play</option>
                    '.$lv_play_list.'
                  </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  <option disabled>High School</option>
                  '.$hs_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 7:// The Stage team-event catagories selection
      if(empty($arg['type']) || strtolower($arg['type']) === 'team'){ $team_active = ' cts--active '; }else{ $team_active = ''; }
      if(strtolower($arg['type']) === 'stat'){ $stat_active = ' cts--active '; }else{ $stat_active = ''; }
      if(strtolower($arg['type']) === 'team-standing'){ $tm_standing_active = ' cts--active '; }else{ $tm_standing_active = ''; }
      if(strtolower($arg['type']) === 'player-directory'){ $pl_dir_active = ' cts--active '; }else{ $pl_dir_active = ''; }
      if(strtolower($arg['type']) === 'my-recruits'){ $recruit_list_active = ' cts--active '; }else{ $recruit_list_active = ''; }
      $form = '
        <div>
          <div class="cts_btn_group">
            <a class="cts_btn cts_btn--filter '.$team_active.'" tabindex="0" href="'.get_site_url().'/account/dcp/teams/'.$arg['ev_id'].'/?type=team">Teams/Rosters</a>
            <a class="cts_btn cts_btn--filter '.$stat_active.'" tabindex="0" href="'.get_site_url().'/account/dcp/teams/'.$arg['ev_id'].'/?type=stat">Stat Leaderboard</a>
            <a class="cts_btn cts_btn--filter '.$tm_standing_active.'" tabindex="0" href="'.get_site_url().'/account/dcp/teams/'.$arg['ev_id'].'/?type=team-standing">Team Standings</a>
            <a class="cts_btn cts_btn--filter '.$pl_dir_active.'" tabindex="0" href="'.get_site_url().'/account/dcp/teams/'.$arg['ev_id'].'/?type=player-directory">Player Directory</a>
            <a class="cts_btn cts_btn--filter '.$recruit_list_active.'" tabindex="0" href="'.get_site_url().'/account/dcp/teams/'.$arg['ev_id'].'/?type=my-recruits">My Recruits</a>
          </div>
        </div>
      ';
      return $form;
      break;
    case 8:// Admin pending/approved
      if(empty($arg['type']) || strtolower($arg['type']) === 'waiting-approval'){ $waiting_appr_active = ' cts--active '; }else{ $waiting_appr_active = ''; }
      if(strtolower($arg['type']) === 'approved'){ $approved_active = ' cts--active '; }else{ $approved_active = ''; }
      $form = '
        <div style="display:block; text-align:center;">
          <div class="admin_photo_approval">
            <a class="cts_btn cts_btn--filter '.$waiting_appr_active.'" tabindex="0" href="'.$arg['admin_ph_pd_url'].'&submenu=waiting-approval&pageno=1">Waiting for Approval</a>
            <a class="cts_btn cts_btn--filter '.$approved_active.'" tabindex="0" href="'.$arg['admin_ph_pd_url'].'&submenu=approved&pageno=1">Approved</a>
          </div>
        </div>
      ';
      return $form;
      break;
    case 9: // G365 team standing: Add levels of play
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
      $all_lvs = explode(',', cts_type_selector()[4]);
      foreach($all_lvs as $all_lv){ if(isset($post_gp_lv) && $post_gp_lv == $key_level[$all_lv]){ $is_selected = 'selected=selected'; }elseif(!isset($post_gp_lv) && $key_level[$all_lv] == '14U / 8th Grade'){ $is_selected = 'selected=selected'; }else{ $is_selected = ''; }
      $all_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$all_lv].'">'.$key_level[$all_lv].'</option>'; }
      $all_lv_list = implode('', $all_lv_list);      
      $lvs_play = g365_division(null, 'dcp');
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
      $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                '.sortable_stat($argument, 'cts_stat').'
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 10:// Admin Automatically assign badges/Manually assign badges
      if(empty($arg['type']) || strtolower($arg['type']) === 'default-badge'){ $default_bdg_active = ' cts--active '; }else{ $default_bdg_active = ''; }
      if(strtolower($arg['type']) === 'manual-badge'){ $manually_bdg_active = ' cts--active '; }else{ $manually_bdg_active = ''; }
      $form = '
        <div class="small-padding" style="display:block; text-align:center;">
          <div class="admin_photo_approval">
            <a class="cts_btn cts_btn--filter '.$default_bdg_active.'" tabindex="0" href="'.$arg['admin_bdg'].'&submenu=default-badge">Default</a>
            <a class="cts_btn cts_btn--filter '.$manually_bdg_active.'" tabindex="0" href="'.$arg['admin_bdg'].'&submenu=manual-badge">Manually Assign Badges</a>
          </div>
        </div>
      ';
      return $form;
      break;
    case 11: // Main team standing page with all divisions
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
      $all_lvs = explode(',', cts_type_selector()[5]);
      foreach($all_lvs as $all_lv){
        if(!empty($post_gp_lv) && $post_gp_lv == strtolower(str_replace(' ', '-', $all_lv))){ 
          $is_selected = 'selected=selected'; }
        elseif(empty($post_gp_lv)){ $is_selected = ''; }
        else{ $is_selected = ''; }
        $all_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(' ', '-', $all_lv)).'">'.$all_lv.'</option>'; 
      }
      $all_lv_list = implode('', $all_lv_list);      
      $lvs_play = g365_division(null, 'dcp');
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){
        if(!empty($post_lv_play) && $post_lv_play == $lv_play){ 
          $lv_selected = 'selected=selected'; }
        elseif(empty($post_lv_play)){ $lv_selected = ''; }
        else{ $lv_selected = ''; }
        $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
      }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      // Add sort option if needed
      // <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
        // '.sortable_stat($argument, 'cts_stat').'
      // </div>
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 12: // For DCP team standing only
      global $wp_query;
      $hs_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
      $hs_lvs = explode(',', cts_type_selector()[2]);
      $lvs_play = g365_division(null, 'dcp');
      foreach($hs_lvs as $hs_lv){ if(isset($post_gp_lv) && $post_gp_lv == $key_level[$hs_lv]){ $is_selected = 'selected=selected'; }elseif(!isset($post_gp_lv) && $key_level[$hs_lv] == '17U'){ $is_selected = 'selected=selected'; }else{ $is_selected = ''; }
      $hs_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$hs_lv].'">'.$key_level[$hs_lv].'</option>'; }
      $hs_lv_list = implode('', $hs_lv_list);
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
      $lv_play_list[] = '<option '.$lv_selected.' value="'.$lv_play.'">'.$lv_play.'</option>'; }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select onchange="this.form.submit()" name="group_lv" id="group_lv" style="border-radius: 20px">
                  <option disabled>High School</option>
                  '.$hs_lv_list.'
                </select>
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 13: // Main team standing filter options for SPP app
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
      $all_lvs = explode(',', cts_type_selector()[5]);
      foreach($all_lvs as $all_lv){
        if(!empty($post_gp_lv) && $post_gp_lv == strtolower(str_replace(' ', '-', $all_lv))){ 
          $is_selected = 'selected=selected'; }
        elseif(empty($post_gp_lv)){ $is_selected = ''; }
        else{ $is_selected = ''; }
        $all_lv_list[] = $all_lv; 
      } 
      $lvs_play = g365_division(null, 'dcp');
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){
        if(!empty($post_lv_play) && $post_lv_play == $lv_play){ 
          $lv_selected = 'selected=selected'; }
        elseif(empty($post_lv_play)){ $lv_selected = ''; }
        else{ $lv_selected = ''; }
        $lv_play_list[] = $lv_play; 
      }
      $select_year = year_dd_opt('spp_app_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[4];
      $group_divison = ['youth-boys'=>cts_type_selector()['youth-boys'], 'youth-girls'=>cts_type_selector()['youth-girls'], 'high-school'=>cts_type_selector()['high-school']];
      return ['year_list'=>$select_year, 'lv_play'=>$lv_play_list, 'group_type'=>$all_lv_list, 'group_divison'=>$group_divison];
      break;
    case 14:
        global $wp_query;
        $hs_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
        $hs_lvs = explode(',', cts_type_selector()[1]);
        $lvs_play = g365_division(null, 'HHH');
        foreach($hs_lvs as $hs_lv){ if(isset($post_gp_lv) && $post_gp_lv == $key_level[$hs_lv]){ $is_selected = 'selected=selected'; }elseif(!isset($post_gp_lv) && $key_level[$hs_lv] == '17U'){ $is_selected = 'selected=selected'; }else{ $is_selected = ''; }
        $hs_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$hs_lv].'">'.$key_level[$hs_lv].'</option>'; }
        $hs_lv_list = implode('', $hs_lv_list);
        $lv_pls = explode(',', cts_type_selector()[3]);
        foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
        $lv_play_list[] = '<option '.$lv_selected.' value="'.$lv_play.'">'.$lv_play.'</option>'; }
        $lv_play_list = implode(',', $lv_play_list);
        $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
        $form = '
          <div>
            <form method="post" id="hhh-form" class="grid-x" action="" onsubmit="submitForm(event);">
              <div class="flex item-center">
                '.$select_year.'
                <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                  <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                      <option value="">All Levels of Play</option>
                      '.$lv_play_list.'
                    </select>
                </div>
                
                <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                  <input type="submit" id="cts_submit_btn" value="Search" class="slb_btn small-12 medium-12 large-3" />
                </div>
              </div>
            </form>
          </div>
        ';
        return $form;
    break;
    case 15:
        global $wp_query;
        $hs_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; 
        $hs_lvs = explode(',', cts_type_selector()[1]);
        $lvs_play = g365_division(null, 'HHH');
        foreach($hs_lvs as $hs_lv){ if(isset($post_gp_lv) && $post_gp_lv == $key_level[$hs_lv]){ $is_selected = 'selected=selected'; }elseif(!isset($post_gp_lv) && $key_level[$hs_lv] == '17U'){ $is_selected = 'selected=selected'; }else{ $is_selected = ''; }
        $hs_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$hs_lv].'">'.$key_level[$hs_lv].'</option>'; }
        $hs_lv_list = implode('', $hs_lv_list);
        $lv_pls = explode(',', cts_type_selector()[3]);
        foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
        $lv_play_list[] = '<option '.$lv_selected.' value="'.$lv_play.'">'.$lv_play.'</option>'; }
        $lv_play_list = implode(',', $lv_play_list);
        $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
        $form = '
          <div>
            <form method="post" id="hhh-form-single" class="grid-x" action="" onsubmit="submitFormEvent(event);">
              <div class="flex item-center">
                <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                  <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                      <option value="">All Levels of Play</option>
                      '.$lv_play_list.'
                    </select>
                </div>
                
                <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                  <input type="submit" id="cts_submit_btn" value="Search" class="slb_btn small-12 medium-12 large-3" />
                </div>
              </div>
            </form>
          </div>
        ';
        return $form;
    break;
    case 16: // Main team standing page with all divisions but being seperated by brand -> cj edition
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $all_br_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; $post_brand = $arg['brand_sel']; 
      
      if ($post_brand == "the-stage") {
        $all_lvs = explode(',', cts_type_selector()[7]);
      }else if ($post_brand == "grassroots-365"){
        $all_lvs = explode(',', cts_type_selector()[7]);
      }else if ($post_brand == "scholastic-series"){
        $all_lvs = explode(',', cts_type_selector()[5]);
      }else if ($post_brand == "hype-her-hoops-circuit"){
        $all_lvs = explode(',', cts_type_selector()[8]);
      }else if ($post_brand == "breakthrough-circuit"){
        $all_lvs = explode(',', cts_type_selector()[7]);
      }else{
        $all_lvs = explode(',', cts_type_selector()[5]);
      }
      foreach($all_lvs as $all_lv){
        if(!empty($post_gp_lv) && $post_gp_lv == strtolower(str_replace(' ', '-', $all_lv))){ 
          $is_selected = 'selected=selected'; }
        elseif(empty($post_gp_lv)){ $is_selected = ''; }
        else{ $is_selected = ''; }
        $all_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(' ', '-', $all_lv)).'">'.$all_lv.'</option>'; 
      }
      $all_lv_list = implode('', $all_lv_list);
      
      //get brands same way as levels
      $all_brands = explode(',', cts_type_selector()[6]);
      foreach($all_brands as $all_br){
        if(!empty($post_brand) && $post_brand == strtolower(str_replace(' ', '-', $all_br))){ 
          $br_selected = 'selected=selected'; }
        elseif(empty($post_brand)){ $br_selected = ''; }
        else{ $br_selected = ''; }
        $all_br_list[] = '<option '.$br_selected.' value="'.strtolower(str_replace(' ', '-', $all_br)).'">'.$all_br.'</option>'; 
      }
      $all_br_list = implode('', $all_br_list);
      
//       $lvs_play = g365_division(null, 'dcp'); //look into this function to see how I can implemented the different brands
      if ($post_brand == "the-stage") { $lvs_play = g365_division(null, 'stage'); } else { $lvs_play = g365_division(null, 'dcp'); }
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){
        if(!empty($post_lv_play) && $post_lv_play == $lv_play){ 
          $lv_selected = 'selected=selected'; }
        elseif(empty($post_lv_play)){ $lv_selected = ''; }
        else{ $lv_selected = ''; }
        if($lv_play == '3SGB'){
          
          if(!empty($post_lv_play) && $post_lv_play == 'Gold'){ 
          $lv_selected = 'selected=selected'; }
          
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="Gold">'.$lv_play.'</option>';
          $lv_play = 'Gold';
        }else{
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
        }
      }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      // Add sort option if needed
      // <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
        // '.sortable_stat($argument, 'cts_stat').'
      // </div>
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px; display: none;">
                <select name="brand_select" id="brand_select" style="border-radius: 20px; display: none;">
                  '.$all_br_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 17: // G365 team standing: Add levels of play with brand new version
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; $post_brand = $arg['brand_sel'];
//       $all_lvs = explode(',', cts_type_selector()[4]);
      
      if ($post_brand == "the-stage") {
        $all_lvs = explode(',', cts_type_selector()[9]);
      }else if ($post_brand == "grassroots-365"){
        $all_lvs = explode(',', cts_type_selector()[4]);
      }else if ($post_brand == "scholastic-series"){
        $all_lvs = explode(',', cts_type_selector()[4]);
      }else if ($post_brand == "hype-her-hoops-circuit"){
        $all_lvs = explode(',', cts_type_selector()[10]);
      }else if ($post_brand == "breakthrough-circuit"){
        $all_lvs = explode(',', cts_type_selector()[9]);
      }else{
        $all_lvs = explode(',', cts_type_selector()[4]);
      }
//       foreach($all_lvs as $all_lv){
//         if(!empty($post_gp_lv) && $post_gp_lv == strtolower(str_replace(' ', '-', $all_lv))){ 
//           $is_selected = 'selected=selected'; 
//         }elseif(empty($post_gp_lv)){
//           $is_selected = ''; 
//         } else{
//           $is_selected = ''; 
//         }
//         $all_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(' ', '-', $all_lv)).'">'.$all_lv.'</option>'; 
//       }
//       $all_lv_list = implode('', $all_lv_list);
      
      foreach($all_lvs as $all_lv){ 
        if(isset($post_gp_lv) && $post_gp_lv == $key_level[$all_lv] ){
          $is_selected = 'selected=selected'; 
        }elseif(!isset($post_gp_lv) && $key_level[$all_lv] == '14U / 8th Grade'){ 
          $is_selected = 'selected=selected'; 
        }else{
          $is_selected = '';
        }
      $all_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$all_lv].'">'.$key_level[$all_lv].'</option>';
      }
      $all_lv_list = implode('', $all_lv_list); 
      
      //get brands same way as levels
      $all_brands = explode(',', cts_type_selector()[6]);
      foreach($all_brands as $all_br){
        if(!empty($post_brand) && $post_brand == strtolower(str_replace(' ', '-', $all_br))){ 
          $br_selected = 'selected=selected'; }
        elseif(empty($post_brand)){ $br_selected = ''; }
        else{ $br_selected = ''; }
        $all_br_list[] = '<option '.$br_selected.' value="'.strtolower(str_replace(' ', '-', $all_br)).'">'.$all_br.'</option>'; 
      }
      $all_br_list = implode('', $all_br_list);
      
//       $lvs_play = g365_division(null, 'dcp');
      if ($post_brand == "the-stage") { $lvs_play = g365_division(null, 'stage'); } else { $lvs_play = g365_division(null, 'dcp'); }
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
      if($lv_play == '3SGB'){
          
          if(!empty($post_lv_play) && $post_lv_play == 'Gold'){ 
          $lv_selected = 'selected=selected'; }
          
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="Gold">'.$lv_play.'</option>';
          $lv_play = 'Gold';
        }else{
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
        }                       
//       $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
      }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3]; 
      
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px; display: none;">
                <select name="brand_select" id="brand_select" style="border-radius: 20px; display: none;">
                  '.$all_br_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                '.sortable_stat($argument, 'cts_stat').'
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 18: // Extension of case 16 to get values only to build for SPP App.
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $all_br_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; $post_brand = $arg['brand_sel']; 
      
      if ($post_brand == "the-stage") {
        $all_lvs = explode(',', cts_type_selector()[7]);
      }else if ($post_brand == "grassroots-365"){
        $all_lvs = explode(',', cts_type_selector()[5]);
      }else if ($post_brand == "scholastic-series"){
        $all_lvs = explode(',', cts_type_selector()[5]);
      }else if ($post_brand == "hype-her-hoops-circuit"){
        $all_lvs = explode(',', cts_type_selector()[8]);
      }else if ($post_brand == "breakthrough-circuit"){
        $all_lvs = explode(',', cts_type_selector()[7]);
      }else{
        $all_lvs = explode(',', cts_type_selector()[5]);
      }
      foreach($all_lvs as $all_lv){
        if(!empty($post_gp_lv) && $post_gp_lv == strtolower(str_replace(' ', '-', $all_lv))){ 
          $is_selected = 'selected=selected'; }
        elseif(empty($post_gp_lv)){ $is_selected = ''; }
        else{ $is_selected = ''; }
        $all_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(' ', '-', $all_lv)).'">'.$all_lv.'</option>';
        $mobile_app_all_lv_list[cts_type_selector()[strtolower(str_replace(' ', '-', $all_lv))]] = $all_lv;
      }
      $all_lv_list = implode('', $all_lv_list);
      
      //get brands same way as levels
      $all_brands = explode(',', cts_type_selector()[6]);
      foreach($all_brands as $all_br){
        if(!empty($post_brand) && $post_brand == strtolower(str_replace(' ', '-', $all_br))){ 
          $br_selected = 'selected=selected'; }
        elseif(empty($post_brand)){ $br_selected = ''; }
        else{ $br_selected = ''; }
        $all_br_list[] = '<option '.$br_selected.' value="'.strtolower(str_replace(' ', '-', $all_br)).'">'.$all_br.'</option>';
        $mobile_app_all_br_list[strtolower(str_replace(' ', '-', $all_br))] = $all_br;
      }
      $all_br_list = implode('', $all_br_list);
      
//       $lvs_play = g365_division(null, 'dcp'); //look into this function to see how I can implemented the different brands
      if ($post_brand == "the-stage") { $lvs_play = g365_division(null, 'stage'); } else { $lvs_play = g365_division(null, 'dcp'); }
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){
        if(!empty($post_lv_play) && $post_lv_play == $lv_play){ 
          $lv_selected = 'selected=selected'; }
        elseif(empty($post_lv_play)){ $lv_selected = ''; }
        else{ $lv_selected = ''; }
        if($lv_play == '3SGB'){
          
          if(!empty($post_lv_play) && $post_lv_play == 'Gold'){ 
          $lv_selected = 'selected=selected'; }
          
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="Gold">'.$lv_play.'</option>';
          $mobile_app_lv_play_list['Gold'] = $lv_play;
          $lv_play = 'Gold';
        }else{
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
          $mobile_app_lv_play_list[$lv_play] = $lv_play;
        }
      }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      $mobile_app_select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[5];
      // Add sort option if needed
      // <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
        // '.sortable_stat($argument, 'cts_stat').'
      // </div>
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px; display: none;">
                <select name="brand_select" id="brand_select" style="border-radius: 20px; display: none;">
                  '.$all_br_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return ['select_year'=>$mobile_app_select_year, 'all_br_list'=>$mobile_app_all_br_list, 'lv_play_list'=>$mobile_app_lv_play_list, 'all_lv_list'=>$mobile_app_all_lv_list];
      break;
    case 19: // Extension of case 17 for mobile app
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; $post_brand = $arg['brand_sel'];
//       $all_lvs = explode(',', cts_type_selector()[4]);
      
      if ($post_brand == "the-stage") {
        $all_lvs = explode(',', cts_type_selector()[9]);
      }else if ($post_brand == "grassroots-365"){
        $all_lvs = explode(',', cts_type_selector()[4]);
      }else if ($post_brand == "scholastic-series"){
        $all_lvs = explode(',', cts_type_selector()[4]);
      }else if ($post_brand == "hype-her-hoops-circuit"){
        $all_lvs = explode(',', cts_type_selector()[10]);
      }else if ($post_brand == "breakthrough-circuit"){
        $all_lvs = explode(',', cts_type_selector()[9]);
      }else{
        $all_lvs = explode(',', cts_type_selector()[4]);
      }
//       foreach($all_lvs as $all_lv){
//         if(!empty($post_gp_lv) && $post_gp_lv == strtolower(str_replace(' ', '-', $all_lv))){ 
//           $is_selected = 'selected=selected'; 
//         }elseif(empty($post_gp_lv)){
//           $is_selected = ''; 
//         } else{
//           $is_selected = ''; 
//         }
//         $all_lv_list[] = '<option '.$is_selected.' value="'.strtolower(str_replace(' ', '-', $all_lv)).'">'.$all_lv.'</option>'; 
//       }
//       $all_lv_list = implode('', $all_lv_list);
      
      foreach($all_lvs as $all_lv){ 
        if(isset($post_gp_lv) && $post_gp_lv == $key_level[$all_lv] ){
          $is_selected = 'selected=selected'; 
        }elseif(!isset($post_gp_lv) && $key_level[$all_lv] == '14U / 8th Grade'){ 
          $is_selected = 'selected=selected'; 
        }else{
          $is_selected = '';
        }
      $all_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$all_lv].'">'.$key_level[$all_lv].'</option>';
      $mobile_app_all_lv_list[$key_level[$all_lv]] = $key_level[$all_lv];
      }
      $all_lv_list = implode('', $all_lv_list); 
      
      //get brands same way as levels
      $all_brands = explode(',', cts_type_selector()[6]);
      foreach($all_brands as $all_br){
        if(!empty($post_brand) && $post_brand == strtolower(str_replace(' ', '-', $all_br))){ 
          $br_selected = 'selected=selected'; }
        elseif(empty($post_brand)){ $br_selected = ''; }
        else{ $br_selected = ''; }
        $all_br_list[] = '<option '.$br_selected.' value="'.strtolower(str_replace(' ', '-', $all_br)).'">'.$all_br.'</option>';
        $mobile_app_all_br_list[strtolower(str_replace(' ', '-', $all_br))] = $all_br;
      }
      $all_br_list = implode('', $all_br_list);
      
//       $lvs_play = g365_division(null, 'dcp');
      if ($post_brand == "the-stage") { $lvs_play = g365_division(null, 'stage'); } else { $lvs_play = g365_division(null, 'dcp'); }
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){ if(isset($post_lv_play) && $post_lv_play == $lv_play){ $lv_selected = 'selected=selected'; }elseif(!isset($post_lv_play)){ $lv_selected = ''; }else{ $lv_selected = ''; }
      if($lv_play == '3SGB'){
          
          if(!empty($post_lv_play) && $post_lv_play == 'Gold'){ 
          $lv_selected = 'selected=selected'; }
          
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="Gold">'.$lv_play.'</option>';
          $mobile_app_lv_play_list['Gold'] = $lv_play;
          $lv_play = 'Gold';
        }else{
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
          $mobile_app_lv_play_list[$lv_play] = $lv_play;
        }                       
//       $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
      }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      $mobile_app_select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[5];
      
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px; display: none;">
                <select name="brand_select" id="brand_select" style="border-radius: 20px; display: none;">
                  '.$all_br_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                '.sortable_stat($argument, 'cts_stat').'
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return ['select_year' => $mobile_app_select_year, 'all_br_list' => $mobile_app_all_br_list, 'lv_play_list' => $mobile_app_lv_play_list,  'all_lv_list' => $mobile_app_all_lv_list];
      break;
    case 20: // Main team standing page with all divisions but being seperated by brand -> cj edition / after the stat update
      global $wp_query;
      $all_lv_list = array(); $lv_pl_list = array(); $lv_play_list = array(); $all_br_list = array();
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; $post_brand = $arg['brand_sel']; 
//       print_r($arg);
      
      $team_levels = spp_get_divisions_lvl( $post_year, $post_brand);

//       echo "<br><br> ===== two2: " . $team_levels['team_levels_string'] . " ====";
      
//       $all_lv_list[] = '<option value="'.htmlspecialchars($team_levels['team_levels_string']).'">All Divisions</option>';
      
      // Condition for selecting "All Divisions"
      // Check if $team_levels['team_levels_string'] is not empty and the selected option matches
      $select_all_divisions = !empty($team_levels['team_levels_string']) && (isset($post_gp_lv) && $post_gp_lv == htmlspecialchars($team_levels['team_levels_string']));

      // Add "All Divisions" option at the top
      $all_lv_list[] = '<option value="'.htmlspecialchars($team_levels['team_levels_string']).'"'. ($select_all_divisions ? ' selected="selected"' : '') .'>All Divisions</option>';

      foreach ($team_levels['team_levels'] as $all_lv) { 
          if (isset($post_gp_lv) && $post_gp_lv == $key_level[$all_lv]) {
              $is_selected = 'selected=selected'; 
          } elseif (!isset($post_gp_lv) && $key_level[$all_lv] == '14U / 8th Grade') { 
              $is_selected = 'selected=selected'; 
          } else {
              $is_selected = '';
          }
          $all_lv_list[] = '<option '.$is_selected.' value="'.$key_level[$all_lv].'">'.$key_level[$all_lv].'</option>';
          $mobile_app_all_lv_list[$key_level[$all_lv]] = $key_level[$all_lv];
      }

      $all_lv_list = implode('', $all_lv_list);
      
      //get brands same way as levels
      $all_brands = explode(',', cts_type_selector()[6]);
      foreach($all_brands as $all_br){
        if(!empty($post_brand) && $post_brand == strtolower(str_replace(' ', '-', $all_br))){ 
          $br_selected = 'selected=selected'; }
        elseif(empty($post_brand)){ $br_selected = ''; }
        else{ $br_selected = ''; }
        $all_br_list[] = '<option '.$br_selected.' value="'.strtolower(str_replace(' ', '-', $all_br)).'">'.$all_br.'</option>'; 
      }
      $all_br_list = implode('', $all_br_list);
      
//       $lvs_play = g365_division(null, 'dcp'); //look into this function to see how I can implemented the different brands
      if ($post_brand == "the-stage") { $lvs_play = g365_division(null, 'stage'); } else { $lvs_play = g365_division(null, 'dcp'); }
      $lv_pls = explode(',', cts_type_selector()[3]);
      foreach($lvs_play as $lv_play){
        if(!empty($post_lv_play) && $post_lv_play == $lv_play){ 
          $lv_selected = 'selected=selected'; }
        elseif(empty($post_lv_play)){ $lv_selected = ''; }
        else{ $lv_selected = ''; }
        if($lv_play == '3SGB'){
          
          if(!empty($post_lv_play) && $post_lv_play == 'Gold'){ 
          $lv_selected = 'selected=selected'; }
          
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="Gold">'.$lv_play.'</option>';
          $lv_play = 'Gold';
        }else{
          $lv_play_list[] = '<option '.$lv_selected.' post_lv_play ="'.$post_lv_play.'" value="'.$lv_play.'">'.$lv_play.'</option>'; 
        }
      }
      $lv_play_list = implode(',', $lv_play_list);
      $select_year = year_dd_opt('dcp_year',['post_year'=>$post_year, 'post_gp_lv'=>$post_gp_lv])[3];
      // Add sort option if needed
      // <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
        // '.sortable_stat($argument, 'cts_stat').'
      // </div>
      $form = '
        <div>
          <form method="post" id="dcp-form" class="grid-x">
            <div class="flex item-center">
              '.$select_year.'
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px; display: none;">
                <select name="brand_select" id="brand_select" style="border-radius: 20px; display: none;">
                  '.$all_br_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="lv_of_play" id="lv_of_play" style="border-radius: 20px">
                  <option value="All">All Levels of Play</option>
                  '.$lv_play_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 220px">
                <select name="group_lv" id="group_lv" style="border-radius: 20px">
                  '.$all_lv_list.'
                </select>
              </div>
              <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
                <input type="submit" id="cts_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
              </div>
            </div>
          </form>
        </div>
      ';
      return $form;
      break;
    case 21: // Extension of case 20 to replace the values to build for SPP App. //ddam
      $post_gp_lv = $arg['post_gp_lv']; $post_year = $arg['post_year']; $post_lv_play = $arg['lv_play']; $post_brand = $arg['brand_sel']; 
      
//       $mobile_app_all_br_list = cts_type_selector()['brands'];
      global $wpdb; $dbs = json_decode(dbs());
      $mobile_app_all_br_list = $wpdb->get_results(" SELECT id, name, nickname, profile_img FROM $dbs->orgs WHERE id IN (3191, 3, 7165, 7164, 7729) ");
      $get_all_levels = [];
      $levels_by_brands = brand_divisions_levels(['brand'=>$post_brand, 'year'=>$post_year])['brand_level_by_events'];
      
      // Temporary array to store divisions
       $temp_levels = [];

      // Collect all levels in the temporary array
      foreach($levels_by_brands as $levels_by_brand){
        if($levels_by_brand->division == '3SGB'){ 
          $levels_by_brand->division = 'Gold'; 
        }
        $temp_levels[$levels_by_brand->division] = $levels_by_brand->division;
      }

      // Predefined order for divisions
      $ordered_divisions = ['Open', 'Gold', 'Silver', 'Bronze', 'Copper'];

      // Start with "All" first
      $get_all_levels['All'] = 'All Levels of Play';

      // Add levels in the desired order
      foreach($ordered_divisions as $division) {
        if(isset($temp_levels[$division])) {
          $get_all_levels[$division] = $temp_levels[$division];
        }
      }
      $mobile_app_lv_play_list = $get_all_levels;
      
      $get_all_divisions = [];
      $divisions_by_brands = brand_divisions_levels(['brand'=>$post_brand, 'year'=>$post_year])['brand_division_by_events'];
      foreach($divisions_by_brands as $divisions_by_brand){
        $get_all_divisions['All'] = 'All Divisions';
        $get_all_divisions[$divisions_by_brand->level] = g365_return_keys('g365_grade_key')[$divisions_by_brand->level];
      }
      $mobile_app_all_lv_list = $get_all_divisions;
      
      $mobile_app_select_year = g365_date_format(null, 14);
      
      return ['select_year '=> $mobile_app_select_year, 'all_br_list' => $mobile_app_all_br_list, 'lv_play_list' => $mobile_app_lv_play_list, 'all_lv_list' => $mobile_app_all_lv_list];
      break;
  }
  return $form;
}
function custom_link($arg = null,$type = null){
  switch($type){
    case 1:
      if($arg[2] == '2'){ $arg[2] = 'camp'; }else if($arg[2] == '3191'){ $arg[2] = 'tournament'; }else if($arg[2] == '7165'){ $arg[2] = 'scholastic'; }else{ $arg[2] = 'tournament'; }
      $c_link = get_site_url().'/player/'.$arg[0].'/stats/'.$arg[1].'/'.$arg[3].'/'.$arg[2].'/#'.$arg[4];
      break;
    case 2:
      $c_link = get_site_url().'/player/'.$arg[0].'/stats/_/'.$arg[1].'/';
      break;
  }
  return $c_link;
}
function pl_gm_st_tb($arg = null, $type = null){
  $placeholder_img = $arg[2]; $org_logo = $arg[3]; $box_score = array();
  foreach($arg[0] as $index => $game_stat_list): 
    $game_date = ( !empty($game_stat_list->game_date_time) || ($game_stat_list->game_date_time) !== NULL ? " (".date('F j, Y, g:iA', strtotime($game_stat_list->game_date_time)).")" : "" ); 
    $game_stat_data = json_decode($game_stat_list->stats);
    !empty($org_id) ? $org_id = $org_id : $org_id = '';
    $gm_res = g365_club_team_stat($event_id = null, $team_id = null, $org_id, $opponent_id = null, $arg[1], 8, array($game_stat_list->game_id,$game_stat_list->team_id));
    if($gm_res[0]->game_result_label == "W"){
      $gm_result_color = 'style="color:white; font-weight:bold;font-size:15px"'; 
    }else{
      $gm_result_color = 'style="color:hsl(0,60%,50%); font-weight:bold;font-size:15px"';
    }
  if(!empty($gm_res[0]->org_logo) && $gm_res[0]->org_logo != "NULL"){
    $is_org_logo = $org_logo.$gm_res[0]->org_logo;
  }else{$is_org_logo = $placeholder_img;}
  if(!empty($gm_res[0]->opp_logo) && $gm_res[0]->opp_logo != "NULL"){
    $is_opp_logo = $org_logo.$gm_res[0]->opp_logo;
  }else{$is_opp_logo = $placeholder_img;}
  $club_link = get_site_url().'/club/'.$gm_res[0]->org_nickname;
  $opp_club_link = get_site_url().'/club/'.$gm_res[0]->opp_nickname;
  $pts = (!empty($game_stat_data->pts)?$game_stat_data->pts:'0');
  $reb = (!empty($game_stat_data->rbs)?$game_stat_data->rbs:'0');
  $ast = (!empty($game_stat_data->ast)?$game_stat_data->ast:'0');
  $blk = (!empty($game_stat_data->blk)?$game_stat_data->blk:'0');
  $stl = (!empty($game_stat_data->stl)?$game_stat_data->stl:'0');
  $three_pt = (!empty($game_stat_data->three_pt)?$game_stat_data->three_pt:'0');
  $team_name = str_replace('.', '', $gm_res[0]->team_name);
  $tr_results = '<td>'.$pts.'</td><td>'.$reb.'</td><td>'.$ast.'</td><td>'.$blk.'</td><td>'.$stl.'</td><td>'.$three_pt.'</td></tr>';
  $tr_gm_res = '<tr class="color-body emphasis"><td><div class="stats_customize cts_res flex items-center small-margin-bottom small-12 medium-12 large-12" style="max-width:640px">
          <div class="team_logo_box hide-for-small-medium">
            <a class="flex align-middle" href="'.$club_link.'" target="_blank"><img style="height:100px;width:125px;z-index:0;" src="'.$is_org_logo.'">
            </a>
          </div>
          <div class="grid-x cts_res_box align-center">
            <div class="small-4 medium-4 large-2 large-offset-2 hide-for-small-medium">
              <span class="large-8 end" style="width:100%;font-size:14px">'.$gm_res[0]->org_name.' '.$team_name.'</span>
            </div>
            <div class="grid-x small-12 medium-12 large-3 align-center">
              <span class="small-padding-right small-12 medium-12 large-12"'.$gm_result_color.'>('.$gm_res[0]->game_result.')</span>
              <button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:11px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-game-date="'.$game_date.'" data-select-year="'.$arg[1].'" data-event-name="'.$game_stat_list->event_name.'" data-game-id="'.$game_stat_list->game_id.'" data-team-id="'.$game_stat_list->team_id.'" data-url="'.home_url().'"> Box Score</button>
            </div>
            <div class="grid-x small-4 medium-4 large-4 hide-for-small-medium">
              <span class="large-8 end" style="font-size:14px">'.$gm_res[0]->opp_name.'</span>
            </div>
          </div>
          <div class="opp_logo_box hide-for-small-medium">
            <a class="flex align-middle" href="'.$opp_club_link.'" target="_blank"><img style="height:100px;width:125px;z-index:0;" src="'.$is_opp_logo.'">
            </a>
          </div>
        </div></td>'.$tr_results.'</tr>';
      $message = '<p>'.g365_message()['gm_result'].'</p></td>';
    if(!empty($gm_res[0]->game_result)):
      $box_score[] = $tr_gm_res;
    else: $box_score[] = '<tr class="color-body emphasis"><td>'.$message.'</td>'.$tr_results.'</tr>'; endif;
  endforeach;
  return $box_score;
}
function ct_box_score($arg = null, $type = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $team_box_score = array();
  $club_team = $arg[2].' '.g365_level_key($arg[3]) . ((empty($arg[4])) ? '' : ' ' . $arg[4]);
  foreach($arg[0] as $team_data){
    if($team_data->game_result_label == "W"){
      $gm_result_color = 'style="color:white; font-weight:bold"';
    }else{
      $gm_result_color = 'style="color:hsl(0,60%,50%); font-weight:bold"';
    }
    $team_name = $wpdb->get_results("SELECT CONCAT(org.name, ' ', tm.search_list) AS team_name FROM $dbs->rosters ros INNER JOIN $dbs->teams tm ON ros.team = tm.id INNER JOIN $dbs->orgs org ON ros.org = org.id WHERE ros.id = $team_data->opponent_id");
    $box_score_btn = '<div class="stats_customize cts_res flex items-center small-margin-bottom small-12 medium-12 large-12"><div class="grid-x cts_res_box align-center"><div class="small-4 medium-4 large-2 large-offset-2"><span class="screen-text--small wrap-text--200 small-4 medium-4 large-4">'.$club_team.'</span></div><div class="grid-x small-4 medium-4 large-4 align-center"><span class="screen-text--small small-padding-right small-12 medium-12 large-12" '.$gm_result_color.' >('.$team_data->game_result.')</span><button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:12px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-select-year="'.$arg[1].'" data-event-name="'.$team_data->event_name.'" data-game-id="'.$team_data->game_id.'" data-team-id="'.$team_data->team_id.'" data-url="'.home_url().'"> Box Score</button></div><div class="grid-x small-4 medium-4 large-4"><span class="screen-text--small large-8 end">'.$team_name[0]->team_name.'</span></div></div></div>';
    $team_box_score[$team_data->event_name][] = $box_score_btn;
  }
  return $team_box_score;
}
function slb_ev_list($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs());
  switch($type){
    case 'dcp':
      $dcp_ev = " AND ev.org = 3 ";
      return $wpdb->get_results("SELECT ev.id, ev.eventtime, ev.name, ev.short_name, ev.nickname FROM $dbs->events ev INNER JOIN $dbs->stats st ON st.event = ev.id WHERE st.game > 0 AND ev.enabled = 1 $dcp_ev GROUP BY ev.id ORDER BY FIELD(ev.id, $arg[0]) DESC, ev.eventtime DESC");
      break;
    case 'ebc':
      $ebc_ev = " AND ev.org = 2 ";
      return $wpdb->get_results("SELECT ev.id, ev.eventtime, ev.name, ev.short_name, ev.nickname FROM $dbs->events ev INNER JOIN $dbs->stats st ON st.event = ev.id WHERE st.game > 0 AND ev.enabled = 1 $ebc_ev GROUP BY ev.id ORDER BY FIELD(ev.id, $arg[0]) DESC, ev.eventtime DESC");
      break;
      case 'scs':
      $scs_ev = " AND ev.org = 7165 ";
      return $wpdb->get_results("SELECT ev.id, ev.eventtime, ev.name, ev.short_name, ev.nickname FROM $dbs->events ev INNER JOIN $dbs->stats st ON st.event = ev.id WHERE st.game > 0 AND ev.enabled = 1 $scs_ev GROUP BY ev.id ORDER BY FIELD(ev.id, $arg[0]) DESC, ev.eventtime DESC");
      break;
      case 'tsc':
      $tsc_ev = " AND ev.org = 3 ";
      return $wpdb->get_results("SELECT ev.id, ev.eventtime, ev.name, ev.short_name, ev.nickname FROM $dbs->events ev INNER JOIN $dbs->stats st ON st.event = ev.id WHERE st.game > 0 AND ev.enabled = 1 $tsc_ev GROUP BY ev.id ORDER BY FIELD(ev.id, $arg[0]) DESC, ev.eventtime DESC");
      break;
      case 'hhh':
      $hhh_ev = " AND ev.org = 7164 ";
      return $wpdb->get_results("SELECT ev.id, ev.eventtime, ev.name, ev.short_name, ev.nickname FROM $dbs->events ev INNER JOIN $dbs->stats st ON st.event = ev.id WHERE st.game > 0 AND ev.enabled = 1 $hhh_ev GROUP BY ev.id ORDER BY FIELD(ev.id, $arg[0]) DESC, ev.eventtime DESC");
      break;
    default:
      $dcp_ev = "";
  }
  return $wpdb->get_results("SELECT ev.* FROM $dbs->events ev INNER JOIN $dbs->stats st ON st.event = ev.id WHERE st.game > 0 AND ev.enabled = 1 $dcp_ev GROUP BY ev.id ORDER BY FIELD(ev, $arg[0]) DESC, ev.eventtime DESC");
}
function avi_ev_list($arg = null, $type = null){
  if(!empty($arg['ev_type'])){ $ev_type = $arg['ev_type']; }else{ $ev_type = ''; }
  $avi_ev_lists = slb_ev_list([$arg[0]], $ev_type);
  if(!empty($arg['remote_post_ev_id'])){
    $select_ev = $arg['remote_post_ev_id'];
  }else{ if(isset($_POST['ev_val'])){ $select_ev = $_POST['ev_val']; } }
  $ev_list = array();
  foreach($avi_ev_lists as $avi_ev_list){
    if(!isset($select_ev) && empty($arg[0])){
      $select_ev = $avi_ev_list->id;
    }
    else if(!isset($select_ev) && !empty($arg[0])){
      $select_ev = $arg[0];
      if($select_ev == $avi_ev_list->id){
        $is_selected_type = 'selected=selected';
      }else{
        $is_selected_type = '';
      }
    }
    else if(isset($select_ev)){
      if($select_ev == $avi_ev_list->id){
        $is_selected_type = 'selected=selected';
      }else{
        $is_selected_type = '';
      }
    }
      $ev_list[] = '<option '.$is_selected_type.' value='.$avi_ev_list->id.'>'.$avi_ev_list->name.'</option>';
  }
  $ev_list = implode('',$ev_list);
  switch($type){
    case 'default_ev_id':
      return $avi_ev_lists[0]->id;
      break;
  }
  $form = '
    <div class="small-12 large-3 small-padding-right" style="width: 100%">
      <select name="ev_val" style="border-radius: 20px">
        '.$ev_list.'
      </select>
    </div>
  ';
  return $form;
}
function check_fav_icon($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs());
  if( empty($arg['user_id']) ) return 'Need user id to process';
  if( empty($arg['pl_id']) ) return 'Need player id to process';
  $results = $wpdb->get_results("SELECT * FROM $dbs->favorites WHERE user_id = ".$arg['user_id']." AND enabled = 1 AND player_id = ".$arg['pl_id']);
  if(!empty($results)){
    return 'true';
  }else{return 'false';}
}
function remote_stat_leader($arg = null, $type = null){ // If needed, it can be used as API call for g365 stat leaderboard
  $stat_lists = g365_stat_list();
  $pl_division = g365_division();
//   $post_ros_level = $arg['post_dvs'];
//   $post_dvs = $arg['select_level'];
  $post_ros_level = $arg['select_level'];
  $post_dvs = $arg['post_dvs'];
  $post_ev_id = $arg['post_ev_id'];
//   $brand_type = '';
  if($arg['brand_type'] == 'scs'){
    $brand_type = 'scs';
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  if($arg['brand_type'] == 'ebc'){
    $brand_type = 'ebc';
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  if($arg['brand_type'] == 'tsc'){
    $brand_type = 'tsc';
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  if($arg['brand_type'] == 'hhh'){
    $brand_type = 'hhh';
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  $most_recent_event = most_recent_event(3);
  $most_recent_event_id = $most_recent_event[0]->event_id;
  $default_event_info = g365_get_event_data($most_recent_event_id, true);
  $avi_ev_list = avi_ev_list([$default_event_info->id, 'remote_post_ev_id'=>$post_ev_id, 'ev_type'=>$arg['is_dcp']]);
  $post_stat_catagory = $arg['post_stat_catagory'];
  if(empty($arg['filter_ev_id'])){
    if($arg['post_level_val'] === 'false' && $arg['post_dvs_val'] === 'false' && $arg['post_stat_val'] === 'false' && $arg['post_ev_val'] === 'false'){
      $post_stat_catagory = key($stat_lists); $post_ev_id = avi_ev_list([$default_event_info->id, 'ev_type'=>$arg['is_dcp']], 'default_ev_id');
    }
  }else{
    if($arg['post_level_val'] === 'false' && $arg['post_dvs_val'] === 'false' && $arg['post_stat_val'] === 'false' && $arg['post_ev_val'] === 'false'){
      $post_stat_catagory = key($stat_lists); $post_ev_id = $arg['filter_ev_id'];
    }else{
      $post_stat_catagory = $post_stat_catagory; $post_ev_id = $arg['filter_ev_id'];
    }
  }
  $leaderboard_tb_form = leaderboard_tb_form($post_stat_catagory, true, 'remote_tsc_tlb');
  $event_info = g365_get_event_data($post_ev_id, true);
  $ev_date_format = g365_date_format($event_info->eventtime, 7);
  $pl_data_type = g365_stat_leader($post_ev_id, $post_stat_catagory, '', '', $post_dvs, 10, $post_ros_level, ['brand_type'=>$brand_type, 'year'=>$is_year]);
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $new_pl_data_type = array();
  $new_pl_data_type_2 = array();
  $hhh_default_img = 'https://hypeherhoopscircuit.com/wp-content/uploads/2022/11/H-2c.png';
  function is_player_img($url){
    $init_curl = curl_init($url);
    curl_setopt($init_curl, CURLOPT_NOBODY, true);
    curl_exec($init_curl);
    $code = curl_getinfo($init_curl, CURLINFO_HTTP_CODE);
    if ($code == 200){ $status = true; }else{ $status = false; }
    curl_close($init_curl);
    return $status;
  }
  foreach($pl_data_type as $key => $pl_data_types){
    $pl_prof_img = get_site_url().'/wp-content/uploads/player-profiles/'.$pl_data_type[$key]['player_nickname'].'_'.$pl_data_type[$key]['player_id'].'.jpg';
    $player_profile = custom_link(array($pl_data_types['player_nickname'], $post_ev_id, $pl_data_type[0]['ev_type'], $ev_date_format, strtolower(preg_replace('/\s+|\.|-/', '-',$pl_data_types['event_nickname']))), 1);
    $new_pl_data_type[] = $pl_data_types;
    $new_pl_data_type[$key]['img_link'] = $pl_prof_img;
    $new_pl_data_type[$key]['player_profile'] = $player_profile;
    $new_pl_data_type[$key]['is_fav'] = check_fav_icon(['user_id'=>$arg['authorized_user'], 'pl_id'=>$pl_data_types['player_id']]);
  }
  $ev_date = g365_build_dates($event_info->dates, 2);
  $default_pl_stats = g365_stat_table_filter($new_pl_data_type, '3', '', '50', $post_stat_catagory, '4');
  return [$default_pl_stats, g365_return_keys('g365_grade_key'), $stat_lists, $default_event_info, $event_info, g365_message()['unavailable_opts'], $avi_ev_list, $leaderboard_tb_form, g365_division(null, 'dcp'), $post_stat_catagory, $post_ev_id, g365_message()['not_available'], $ev_date, 'test'=>$new_pl_data_type_2];
}
function g365_get_event($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs()); $authorized_user = $arg['authorized_user'];
  switch($type){
    case 'acts':
      // Add additional events
//       $additional_events = '663, 642, 717, 720, 721';
      $additional_events = '856, 857';
      $additional_event_query = $wpdb->get_results(" SELECT *, (SELECT GROUP_CONCAT('TRUE') FROM $dbs->favorites fav INNER JOIN $dbs->events ev ON fav.event_id = ev.id WHERE ev_tb.id = fav.event_id AND fav.user_id = $authorized_user) AS unlocked
FROM (SELECT * FROM $dbs->events ev WHERE ev.org IN (7164) OR ev.id IN ($additional_events) AND ev.enabled = 1 ORDER BY ev.eventtime DESC) ev_tb ");
      $results = $wpdb->get_results(" SELECT *, (SELECT GROUP_CONCAT('TRUE') FROM $dbs->favorites fav INNER JOIN $dbs->events ev ON fav.event_id = ev.id WHERE ev_tb.id = fav.event_id AND fav.user_id = $authorized_user) AS unlocked
FROM (SELECT * FROM $dbs->events ev WHERE ev.org IN (3, 7474) AND ev.enabled = 1 ORDER BY ev.eventtime DESC) ev_tb ");
      for($i=0; $i < count($additional_event_query); $i++){
        array_push($results, $additional_event_query[$i]);      
      }
      return $results;
      break;
    case 'HHH':
      $first_date = $arg['starting_date'];
      $second_date = $arg['end_date'];  
      $current_date = $arg['current_date'];
      $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $dbs->events ev WHERE ev.org = 7164 AND ev.id != 832 AND ev.eventtime >= %s AND ev.eventtime <= %s AND ev.eventtime <= %s",
        $first_date,
        $current_date,
        $second_date
    ));
      return $results;
      break;

  }
}
function g365_division($arg = null, $type = null){
  $dvs_list = array('Open', 'Gold', 'Silver', 'Bronze', 'Copper');
  switch($type){
    case 'dcp':
      $dvs_list = array('Gold', 'Open', 'Silver', 'Bronze');
      break;
    case 'HHH':
      $dvs_list = array('Gold', 'Silver', 'Bronze');
      break;
    case 'stage':
      $dvs_list = array('3SGB', 'Open', 'Silver', 'Bronze');
      break;
    case 'scibca':
      $dvs_list = array('Advocates for Athletes', 'Passport', 'SCIBCA', 'ABT', 'OCYSF', 'Kangaroo', 'Destination Irvine', 'Baller TV');
      break;
  }
  return $dvs_list;
}
function g365_team_standing($arg = null, $type = null){
  // $arg[0]: 2022; $arg[1]: level keys; $arg[2]: ; $arg[3]: /wp-content/uploads/org-logos/; $arg[4]: /wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png;
  $key_level = (g365_return_keys('g365_grade_key'));
  $get_lv_key = array_search($arg['post_gp_lv'], $key_level);
  $brand_sel = $arg['brand_sel'];
  if($arg['is_dcp_only'] === true){ $is_dcp = true; }else{ $is_dcp = $arg['is_dcp_only']; }
//   if($arg['is_unlocked_dcp_ev'] === true){ $is_unlocked_dcp_ev = true; }else{ $is_unlocked_dcp_ev = $arg['is_unlocked_dcp_ev']; }
  !empty($arg['is_unlocked_dcp_ev']) ? $is_unlocked_dcp_ev = $arg['is_unlocked_dcp_ev'] : $is_unlocked_dcp_ev = '' ;
//    $club_team_datas = g365_club_team_stat($event_id = null, $team_id = null, false, $opponent_id = null, $select_year, 10, array($select_gp_div, null, 'is_standing_only', null, null, $select_group, 'is_main_ts'=>true, 'level_of_play'=>$select_lv_play, 'brand_selected'=>$brand_sel));   
//   echo("<script>console.log('post_year=2023: " . $arg['post_year'] . " lv_key=17: " . $get_lv_key . " cts=empty: " . $_POST['cts_type'] . " is_dcp=empty: " . $is_dcp . "is_dcp_ev=empty: " . $arg['is_dcp_ev'] . " post_ros_dvs=empty: " . $arg['post_ros_dvs'] . " is_unlocked_dcp_ev=empty: " . $is_unlocked_dcp_ev . " brand_sel: " . $brand_sel . " ');</script>");
  $g365_club_team_stat = g365_club_team_stat(null, null, null, null, $arg['post_year'], 10, [$get_lv_key, null, 'is_standing_only', 'by-level', $_POST['cts_type'], 'is_dcp'=>$is_dcp, 'is_dcp_ev'=>$arg['is_dcp_ev'], 'post_ros_dvs'=>$arg['post_ros_dvs'], 'is_unlocked_dcp_ev'=>$is_unlocked_dcp_ev, 'brand_selected'=>$brand_sel]);
  $g365_get_site_url = g365_get_site_url(null, 'default-root');
  $club_team_tb_form = club_team_tb_form(); $key_level = (g365_return_keys('g365_grade_key'));
  $cts_lv_type = g365_submenu_type(['post_year'=>$arg['post_year'], 'post_gp_lv'=>$arg['post_gp_lv'], 'lv_play'=>$arg['post_ros_dvs']], 6);
  // Use this menu filter options for only unlocked DCP events
  if($is_unlocked_dcp_ev == true){
    $cts_lv_type = g365_submenu_type(['post_year'=>$arg['post_year'], 'post_gp_lv'=>$arg['post_gp_lv'], 'lv_play'=>$arg['post_ros_dvs']], 12);
  }
  $cts_js_caller = cts_dialog_js();
  $select_option = '
    <div class="grid-x">
      <div class="flex item-center">
        <div>'.$cts_lv_type.'</div>
      </div>
    </div>
  ';
  return [$select_option, $g365_club_team_stat, $club_team_tb_form, $key_level, $cts_js_caller, $g365_get_site_url, g365_message()['not_available'], g365_message()['team_standing']];
}
function g365_get_site_url($arg = null, $type = null){
  switch($type){
    case 'g365-prod':
      $g365_url = 'https://grassroots365.com/'; $g365_dev_url = 'https://dev.grassroots365.com/';
      return ['g365_url'=>$g365_url, 'g365_dev_url'=>$g365_dev_url, 'placeholder_img'=>$g365_url.'wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png', 'org_logo'=>$g365_url.'wp-content/uploads/org-logos/'];
      break;
    case 'default-root':
      return ['g365_url'=>get_site_url().'/', 'g365_dev_url'=>get_site_url().'/', 'placeholder_img'=>get_site_url().'/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png', 'org_logo'=>get_site_url().'/wp-content/uploads/org-logos/'];
      break;
  }
}
// add_action( 'send_headers', 'g365_custom_cors' );
function g365_custom_cors($arg = null, $type = null){
  $http_origin = (empty($_SERVER['HTTP_ORIGIN']) ? "": $_SERVER['HTTP_ORIGIN']);
  switch($type){
    case 'tsc': // The stage circuit
      if ($http_origin == "https://dev.thestagecircuit.com" || $http_origin == "https://thestagecircuit.com" || $http_origin == 'https://opengympremier.com' || $http_origin == "https://dev.sportspassports.com" || $http_origin == "https://sportspassports.com"){
        header("Access-Control-Allow-Origin: $http_origin");
      }
      break;
    case 'feature-api-request':
      $verify_request = (empty($arg['verify_request']) ? "": $arg['verify_request']);
      $allowed_url_request = (empty($arg['url_request']) ? "": $arg['url_request']);
      if($verify_request == true){
        header('Access-Control-Allow-Origin: '.$allowed_url_request.' ');
        header('Access-Control-Allow-Methods: GET');
      }
      break;
  }
}
// add_action( 'plugins_loaded', 'handle_feature_api_request' );
// function handle_feature_api_request($args = null){
//   $verify_request = (empty($args['verify_request']) ? "": $args['verify_request']);
//   $allowed_url_request = (empty($args['url_request']) ? "": $args['url_request']);
//   if($verify_request == true){
//     header('Access-Control-Allow-Origin: '.$allowed_url_request.' ');
//     header('Access-Control-Allow-Methods: GET');
//   }
// }
function g365_data_xfer($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs());
  $db_name = $arg['db_tb'];
  $g365_db_tb = $dbs->$db_name;
  $query_type = $arg['qn_type'];
  $cond = ""; $limit = ""; $order = "";
  switch($type){
    case 'INSERT':
      $field_val = $arg['insert_field_val'];
      $sql = $wpdb->query("$type INTO $g365_db_tb VALUES ($field_val) ON DUPLICATE KEY UPDATE notes=VALUES(notes), enabled = 1, pl_data=VALUES(pl_data), updatetime=CURRENT_TIMESTAMP");
      return $sql;
      break;
    case 'SELECT':
      if(!empty($arg['limit'])){
        $limit = " LIMIT ".$arg['limit'];
      }else{$limit = "";}
      switch($query_type){
        case '1': //With conditions: fav notes and fav list
          $order = " ORDER BY updatetime DESC, createdate DESC ";
          if(!empty($arg['player_id'])){
            $cond = "WHERE enabled = 1 AND event_id IS NULL AND user_id = ".$arg['user_id']." AND player_id = ".$arg['player_id'];
          }else{$cond = "WHERE enabled = 1 AND event_id IS NULL AND user_id = ".$arg['user_id'];}
          break;
      }
      $sql = $wpdb->get_results(" $type * FROM $g365_db_tb $cond $order $limit ");
      $sql = json_decode(json_encode($sql), true);
      return $sql;  
      break;
//     case 'DELETE': // Delete record
//       $cond = " WHERE id = ".$arg['rec_id'];
//       $sql = " $type FROM $db_prefix$g365_db_tb $cond ";
//       mysqli_query($conn, $sql);
//       return $sql;
//       break;
     case 'DELETE': // Disable instead of delete the record
      $cond = " SET enabled = 0 WHERE id = ".$arg['rec_id'];
      $sql = $wpdb->query(" UPDATE $g365_db_tb $cond ");
      return $sql;
      break;
  }
}
function fav_reveal($arg = null, $type = null){
  $pl_school = empty($arg['school']) ? '': $arg['school']; $pl_position = empty($arg['pl_position']) ? '': $arg['pl_position']; $pl_grad_year = empty($arg['pl_grad_year']) ? '': $arg['pl_grad_year']; $pl_height = empty($arg['pl_height']) ? '': $arg['pl_height']; $pl_gpa = empty($arg['gpa']) ? '': $arg['gpa']; $pl_sat = empty($arg['sat']) ? '': $arg['sat']; $pl_act = empty($arg['act']) ? '': $arg['act']; $pl_contact = empty($arg['pl_contact_info']) ? '': $arg['pl_contact_info']; $pl_img = empty($arg['pl_img']) ? '': $arg['pl_img']; $pl_name = empty($arg['full_name']) ? '': $arg['full_name']; $pl_nickname = empty($arg['pl_nickname']) ? '': $arg['pl_nickname']; $pl_id = empty($arg['pl_id']) ? '': $arg['pl_id']; $data_note = empty($arg['data_note']) ? '': $arg['data_note'];
  $pl_data = '
    <div class="info-fav small-12 medium-12 large-6">
      '.(empty($pl_school) ? '': ('<p>School: '.$pl_school)).''.(empty($pl_position) ? '': ('<p>Position: '.$pl_position)).''.(empty($pl_height) ? '': ('<p>Height: '.$pl_height)).''.(empty($pl_grad_year) ? '': ('<p>Grad Year: '.$pl_grad_year)).''.(empty($pl_gpa) ? '': ('<p>GPA: '.$pl_gpa)).''.(empty($pl_sat) ? '': ('<p>SAT: '.$pl_sat)).''.(empty($pl_act) ? '': ('<p>ACT: '.$pl_act)).''.(empty($pl_contact) ? '': ('<p>Contact: '.$pl_contact)).'
    </div>
  ';
  switch($type){
    case 'add_fav':
      $fav_notes = array();
      if(!empty($arg["fav_data"])){ foreach($arg["fav_data"] as $fav_info){$fav_note = json_decode($fav_info["notes"], true); $fav_notes[] = $fav_note['notes'];} }
      $reveal_form = '
        <div class="reveal small fav_box" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
          <div class="grid-x small-padding-bottom">
            <div class="small-6 medium-6 large-4 flex text-center">
              <div class="cell" data-alphabet="A">
                <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="'.$pl_img.'" alt="Player headshot for '.$pl_name.'" src="'.$pl_img.'"><br><p>'.$pl_name.'</p>
              </div>
            </div>
            '.$pl_data.'
          </div>
          <textarea style="text-transform: none;" class="secondary button text-left" style="color:#000" id="'.$data_note.'" name="'.$data_note.'" rows="2" cols="50" placeholder="Leave a note for '.$pl_name.'">'.(empty($fav_notes) ? "" : $fav_notes[0]).'</textarea>
          <button onClick="fav_icon_animation(this)" class="fav_pl success button no-margin-bottom" data-pl-id="'.$pl_id.'" data-pl-name="'.$pl_name.'" data-pl-nickname="'.$pl_nickname.'" data-pl-img="'.$pl_img.'" data-pl-grad-year="'.$pl_grad_year.'" data-pl-position="'.$pl_position.'" data-pl-height="'.$pl_height.'" data-pl-gpa="'.$pl_gpa.'" data-pl-sat="'.$pl_sat.'" data-pl-act="'.$pl_act.'" data-pl-contact-info="'.$pl_contact.'" data-close aria-label="Close reveal">Add to recruit list</button>
          <button class="secondary button" data-close aria-label="Close reveal">Cancel</button>
        </div>
      ';
      break;
    case 'remove_fav':
      $reveal_form = '
        <div class="1 reveal small fav_box" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
        <div class="grid-x small-padding-bottom">
          <div class="small-6 medium-6 large-4 flex text-center">
            <div class="cell" data-alphabet="A">
              <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="'.$pl_img.'" alt="Player headshot for '.$pl_name.'" src="'.$pl_img.'"><br><p>'.$pl_name.'</p>
            </div>
          </div>
          '.$pl_data.'
        </div>
          <p class="lead medium-padding-bottom">Do you want to remove <span style="font-size:22px; font-weight:bolder; text-decoration:underline;">'.$pl_name.'</span> from your recruit list?</p>
          <button class="rm_pl success button no-margin-bottom" data-rm-id="'.$arg["rec_id"].'" onClick="rm_fav(this)" data-close aria-label="Close reveal">Remove from recruit list</button>
          <button class="secondary button" data-close aria-label="Close reveal">Cancel</button>
        </div>
      ';
      break;
    case 'edit_note':
      $fav_notes = array();
      foreach($arg["fav_data"] as $fav_info){$fav_note = json_decode($fav_info["notes"], true); $fav_notes[] = $fav_note['notes'];}
      $reveal_form = '
        <div class="2 reveal small fav_box" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
          <div class="grid-x small-padding-bottom">
            <div class="small-6 medium-6 large-4 flex text-center">
              <div class="cell" data-alphabet="A">
                <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="'.$pl_img.'" alt="Player headshot for '.$pl_name.'" src="'.$pl_img.'"><br><p>'.$pl_name.'</p>
              </div>
            </div>
            '.$pl_data.'
          </div>
          <textarea style="text-transform: none;" class="secondary button text-left" style="color:#000" id="'.$arg["data_note"].'" name="'.$arg["data_note"].'" rows="2" cols="50" placeholder="Leave a note for here '.$pl_name.'">'.$fav_notes[0].'</textarea>
          <button onClick="reload_pg(this)" class="edit_note success button no-margin-bottom" data-pl-id="'.$arg["pl_id"].'" data-pl-name="'.$pl_name.'" data-pl-nickname="'.$pl_nickname.'" data-pl-img="'.$pl_img.'" data-pl-grad-year="'.$pl_grad_year.'" data-pl-position="'.$pl_position.'" data-pl-height="'.$pl_height.'" data-pl-gpa="'.$pl_gpa.'" data-pl-sat="'.$pl_sat.'" data-pl-contact-info="'.$pl_contact.'" data-close aria-label="Close reveal">Update Note</button>
          <button class="secondary button" data-close aria-label="Close reveal">Cancel</button>
        </div>
      ';
      break;
      case 'ls_pl':
      $fav_notes = array(); $dir_pl_dir_url = $arg['redir_url'].'?type=player-directory';
      foreach($arg["fav_data"] as $fav_info){$fav_note = json_decode($fav_info["notes"], true); $fav_notes[] = $fav_note['notes'];}
      $reveal_form = '
        <div class="reveal small fav_box fast" data-close-on-esc="false" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
          <div class="grid-x small-padding-bottom">
            <div class="small-6 medium-6 large-4 flex text-center">
              <div class="cell" data-alphabet="A">
                <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="'.$pl_img.'" alt="Player headshot for '.$pl_name.'" src="'.$pl_img.'"><br><p>'.$pl_name.'</p>
              </div>
            </div>
            '.$pl_data.'
          </div>
          <textarea style="text-transform: none;" class="secondary button text-left" style="color:#000" id="'.$arg["data_note"].'" name="'.$arg["data_note"].'" rows="2" cols="50" placeholder="Leave a note for '.$arg["full_name"].'">'.$fav_notes[0].'</textarea>
            <a class="small-padding-right" href="'.$arg['redir_url'].'"><button class="ls_pl success button no-margin-bottom" data-dcp-url="'.$arg['redir_url'].'" data-pl-id="'.$arg["pl_id"].'" data-pl-name="'.$arg["full_name"].'" data-pl-nickname="'.$arg["pl_nickname"].'" data-pl-img="'.$arg["pl_img"].'" data-pl-grad-year="'.$arg["pl_grad_year"].'" data-pl-position="'.$arg["pl_position"].'" data-pl-height="'.$arg["pl_height"].'" data-pl-gpa="'.$arg["pl_gpa"].'" data-pl-sat="'.$arg["pl_sat"].'" data-pl-contact-info="'.$arg["pl_contact_info"].'" data-close aria-label="Close reveal">Add to recruit list</button></a>
            <a class="small-padding-right" href="'.$dir_pl_dir_url.'"><button class="secondary button no-margin" data-close aria-label="Close reveal">Cancel</button></a>
        </div>
      ';
      break;
    case 'locked_ls_pl':
      $dir_pl_dir_url = $arg['redir_url'].'?type=player-directory';
      $reveal_form = '
        <div class="reveal small fav_box fast" data-close-on-esc="false" id="'.$arg["data_toggle"].'" data-reveal data-close-on-click="false" data-animation-in="slide-out-up" data-animation-out="spin-out">
         <p class="lead medium-padding-bottom">'.$pl_name.' is not available in <span style="font-size:22px; font-weight:bolder; text-decoration:underline;">'.$arg['ev_name'].'</span>. Please unlock him/her by purchasing the event that they are in.</p>
         <a class="small-padding-right" href="'.$dir_pl_dir_url.'"><button class="secondary button no-margin" data-close aria-label="Close reveal">Cancel</button></a>
        </div>
      ';
      break;
  }
  return $reveal_form;
}
function ajax_data_xfer($arg = null, $type = null){
  $script = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js?loc=10"></script>';
  $script .= '<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.min.css?loc=12">';
//   $script .= '<link rel="stylesheet" href="/resources/demos/style.css">';
  $script .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js?loc=6"></script>';
  $script .= '<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js?loc=7"></script>';
  $class_name = $arg['class_name'];
  $dir_url = '../ajax-caller';
  $admin_dir_url = '../../wp-admin/ajax-caller.php';
  switch($type){
    case 'add_fav': if($arg['url'] == true){ $dir_url = '../../../dcp/ajax-caller'; }
      $vars = 'var pl_id = this.dataset.plId; var pl_name = this.dataset.plName; var pl_nickname = this.dataset.plNickname; var pl_grad_year = this.dataset.plGradYear; var pl_position = this.dataset.plPosition; var pl_height = this.dataset.plHeight; var pl_gpa = this.dataset.plGpa; var pl_sat = this.dataset.plSat; var pl_act = this.dataset.plAct; var pl_contact_info = this.dataset.plContactInfo; var pl_img = this.dataset.plImg; var dir_url = "'.$dir_url.'"; var pl_note = $("#note_"+pl_id).val();';
      $data_fields = 'post_type: "add_fav", pl_id: pl_id, pl_note: pl_note, pl_name: pl_name, pl_img: pl_img, pl_nickname: pl_nickname, pl_grad_year: pl_grad_year, pl_position: pl_position, pl_height: pl_height, pl_gpa: pl_gpa, pl_sat: pl_sat, pl_act: pl_act, pl_contact_info: pl_contact_info';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
    //         success: function() {alert('.$vars.');}
            });
          });
          function rm_fav(pointer){
            var rm_id = pointer.dataset.rmId;
            $("#"+rm_id).hide("slow");
          }
        </script>';
      return $script;
      break;
    case 'remove_fav': if($arg['url'] == true){ $dir_url = '../../../dcp/ajax-caller'; }
      $vars = ' var rec_id = this.dataset.rmId; var dir_url = "'.$dir_url.'"; ';
      $data_fields = 'post_type: "remove_fav", rec_id: rec_id ';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
    //          success: function() {alert('.$vars.');}
            });
          });
          function rm_fav(pointer){
            var rm_id = pointer.dataset.rmId;
            $("#"+rm_id).hide("slow");
          }
        </script>';
      return $script;
      break;
    case 'ls_pl':
      $dir_url = "../../../dcp/ajax-caller";
      $vars = 'var pl_id = this.dataset.plId; var pl_name = this.dataset.plName; var pl_nickname = this.dataset.plNickname; var pl_grad_year = this.dataset.plGradYear; var pl_position = this.dataset.plPosition; var pl_height = this.dataset.plHeight; var pl_gpa = this.dataset.plGpa; var pl_sat = this.dataset.plSat; var pl_contact_info = this.dataset.plContactInfo; var pl_img = this.dataset.plImg; var dir_url = "'.$dir_url.'"; var pl_note = $("#note_"+pl_id).val();';
      $data_fields = 'post_type: "add_fav", pl_id: pl_id, pl_note: pl_note, pl_name: pl_name, pl_img: pl_img, pl_nickname: pl_nickname, pl_grad_year: pl_grad_year, pl_position: pl_position, pl_height: pl_height, pl_gpa: pl_gpa, pl_sat: pl_sat, pl_contact_info: pl_contact_info';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
    //          success: function() {alert('.$vars.');}
            });
          });
          function rm_fav(pointer){
            var rm_id = pointer.dataset.rmId;
            $("#"+rm_id).hide("slow");
          }
        </script>';
      return $script;
      break;
    case 'assign-ph-pl': // Save assign photo to player(s).
      $vars = 'var img_id = this.dataset.imgId; var img_name = this.dataset.imgName; var dir_url = "'.$admin_dir_url.'"; var pl_id_list = $("#pl_list_string-"+img_id).attr("value");';
      $success_message = g365_message()['changes_saved'];
      $data_fields = 'post_type: "assign-ph-pl", pl_id_list: pl_id_list, img_name: img_name, img_id: img_id';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
              success: function(data){
                if(!alert("'.$success_message.'")){
                  window.location.reload(); 
                }
              }
            });
          });
        </script>';
      return $script;
      break;
    case 'approve-photo': // Approve pending photo(s).
      $vars = 'var img_id = this.dataset.imgId; var dir_url = "'.$admin_dir_url.'"; var pl_id_list = $("#pl_list_string-"+img_id).attr("value"); var checkbox_id = $(this).attr("id");
  var get_ch_id = document.getElementById(checkbox_id); if(get_ch_id.checked){ var is_checked = 1; }else{ var is_checked = 0; }'; 
      $success_message = g365_message()['changes_saved'];
      $data_fields = 'post_type: "approve-photo", img_id: img_id, pl_id_list: pl_id_list, admin_approved: is_checked';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
              success: function(data){
//                 if(!alert("'.$success_message.'")){
//                   window.location.reload(); 
//                 }
              }
            });
          });
        </script>';
      return $script;
      break;
    case 'user-photo-status':
      $vars = 'var img_id = this.dataset.imgId; var dir_url = "'.$admin_dir_url.'"; var pl_id_list = $("#pl_list_string-"+img_id).attr("value"); var checkbox_id = $(this).attr("id"); var user_admin_toggle = this.dataset.userAdminToggle;
  var get_ch_id = document.getElementById(checkbox_id); if(get_ch_id.checked){ var is_checked = 1; }else{ var is_checked = 0; }';
      $data_fields = 'post_type: "user-photo-status", img_id: img_id, pl_id_list: pl_id_list, user_toggle: is_checked, user_admin_toggle: user_admin_toggle';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
//              success: function(data){}
            });
          });
        </script>';
      return $script;
      break;
    case 'user-assinged-ph': // Edit assigned photos.
      $vars = 'var img_id = this.dataset.imgId; var dir_url = "'.$admin_dir_url.'"; var pl_id_list = $("#pl_list_string-"+img_id).attr("value");';
      $success_message = g365_message()['changes_saved'];
      $data_fields = 'post_type: "user-photo-assigned", pl_id_list: pl_id_list, img_id: img_id';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
              success: function(data){
                if(!alert("'.$success_message.'")){
                  window.location.reload(); 
                }
              }
            });
          });
        </script>';
      return $script;
      break;
    case 'edit-photo': // Remove photos
      $vars = 'var img_id = this.dataset.imgId; var dir_url = "'.$admin_dir_url.'";'; 
      $success_message = g365_message()['remove_file'];
      $data_fields = 'post_type: "admin-photo-edit", img_id: img_id';
      $script .= 
        '<script>
          $(document).on("click", ".'.$class_name.'", function(){
             '.$vars.'
            $.ajax({
             url: dir_url,
             data: {'.$data_fields.'},
             type: "POST",
              success: function(data){
                if(!alert("'.$success_message.'")){
                  window.location.reload(); 
                }
              }
            });
          });
        </script>';
      return $script;
      break;
    case 'save-badge': // Save badge operators
//       $vars = 'var img_id = this.dataset.imgId; var dir_url = "'.$admin_dir_url.'";';
      $vars = 'var note_data = "'.$arg['note_data'].'"; var dir_url = "'.$admin_dir_url.'";';
      $data_fields = 'post_type: "admin-badge", note_data: note_data';
      $script .= 
        '
           '.$vars.'
          $.ajax({
           url: dir_url,
           data: { post_type: "admin-badge", badge_id: badge_id, note_row: note_data, website_id: website_id, badge_cat_op_array: badge_gr_cat_op_array, badge_gr_cat_op_val_array: badge_gr_cat_op_val_array, season_year_val: season_year_val, stat_type_array: badge_gr_stat_type_array, badge_gr_stat_op_array: badge_gr_stat_op_array, badge_gr_stat_op_val_array: badge_gr_stat_op_val_array, badge_name: badge_name },
           type: "POST",
            success: function(data){
              saved_index++;
              if(saved_index == i){
                if(!alert("Changes are saved")){
                  window.location.reload(); 
                }
              }
            }
          });
        ';
//       return $script;
      return $script;
      break;
  }
}
function fav_insert($arg = null, $type = null){
  switch($type){
    case 'fav_insert':
      return "'".wp_date('Y-m-d H:i:s')."', '".wp_date('Y-m-d H:i:s')."', DEFAULT, 1, NULL, ".get_current_user_id().", ".$arg['pl_id'].", JSON_OBJECT('notes', '".$arg['pl_note']."'), JSON_OBJECT('img_link', '".$arg['pl_img']."', 'pl_name', '".$arg['pl_name']."', 'pl_nickname', '".$arg['pl_nickname']."', 'grad_year', '".$arg['pl_grad_year']."', 'position', '".$arg['pl_position']."', 'height', '".$arg['pl_height']."', 'gpa', '".$arg['pl_gpa']."', 'sat', '".$arg['pl_sat']."', 'contact_info', '".$arg['pl_contact_info']."')";
      break;
  }
}
function cdp_fav_pl_info($arg = null, $type = null){
  if(!empty($arg['pl_name'])){
    $pl_name = '<p>'.$arg['pl_name'].'</p>';
  }else{$pl_name="";}
  if(!empty($arg['pl_school'])){
    $pl_school = '<p>'.$arg['pl_school'].'</p>';
  }else{$pl_school='';}
  if(!empty($arg['grad_year'])){
    $grad_year = '<p>Class of '.$arg['grad_year'].'</p>';
  }else{$grad_year="";}
  if(!empty($arg['position'])){
    $position = '<p>Position: '.$arg['position'].'</p>';
  }else{$position="";}
  if(!empty($arg['height'])){
    $height = '<p>Height: '.$arg['height'].'</p>';
  }else{$height="";}
  if(!empty($arg['gpa'])){
    $gpa = '<p>GPA: '.$arg['gpa'].'</p>';
  }else{$gpa="";}
  if(!empty($arg['sat'])){
    $sat = '<p>SAT: '.$arg['sat'].'</p>';
  }else{$sat="";}
  if(!empty($arg['act'])){
    $sat = '<p>ACT: '.$arg['act'].'</p>';
  }else{$act="";}
  if(!empty($arg['contact_info'])){
    $contact_info = '<p>Contact: '.$arg['contact_info'].'</p>';
  }else{$contact_info="";}
  switch($type){
    case 'pl_fav':
      return ''.$pl_school.''.$grad_year.''.$position.''.$height.''.$gpa.''.$sat.''.$contact_info.'';
      break;
    case 'hm_pl_fav':
      break;
  }
}
function g365_get_pl_data($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs()); empty($arg['pl_id']) ? $pl_id = '' : $pl_id = $arg['pl_id'];
  switch($type){
    case 'position':
      if(!empty($arg['pst_id'])){ return $wpdb->get_results("SELECT * FROM $dbs->positions WHERE id=".$arg['pst_id']); }
      break;
    case 'tsc':
      return $wpdb->get_results("SELECT * FROM $dbs->players LIMIT 1000");
      break;
    case 'g365-pl-data':
      return $wpdb->get_results("SELECT * FROM $dbs->players pl WHERE pl.id = $pl_id");
      break;
    case 'g365-pl-photo':
      if(!empty($pl_id)){ return $wpdb->get_results("SELECT pl.name FROM $dbs->players pl WHERE pl.id = $pl_id"); }
      break;
    case 'g365-pl-custom':
      empty($arg['field_list']) ? $fields = '' : $fields = $arg['field_list'];
      return $wpdb->get_results("SELECT $fields FROM $dbs->players pl WHERE pl.id = $pl_id");
      break;
  }
  if(!empty($arg['pl_id'])){ return $wpdb->get_results("SELECT * FROM $dbs->players WHERE id=".$arg['pl_id']); }
}
function dcp_custom_js($arg = null, $type = null){
  $reload_pg = 'function reload_pg(el){setTimeout(function(){location.reload(1);}, '.(empty($arg['delay']) ? "" : $arg['delay']).');}';
  $rm_btn = '(function(){var removeSuccess; removeSuccess = function(){return $(".rm_btn").removeClass("success");};
        $(document).ready(function(){return $(".rm_btn").click(function(){$(this).addClass("success"); return setTimeout(removeSuccess, 3000);});})}).call(this);';
  $fav_icon = 'function fav_icon_animation(el){var id = el.dataset.plId; $("#"+id+" a").addClass("fav_animation"); $("#"+id+" a").removeClass("btn-flip");}';
  $ls_reveal = '$(document).ready(function(){$("#pl_"+'.(empty($arg['pl_id']) ? "" : $arg['pl_id']).').foundation("open");});';
  switch($type){
    case 'dcp-pg-reload': $reload_pg = 'function reload_pg(el){var url = el.dataset.dcpUrl; setTimeout(function(){location.href = url;}, '.$arg['delay'].');}'; return '<script>'.$reload_pg.'</script>';
      break;
    case 'dcp-rm': return '<script>'.$reload_pg.$rm_btn.'</script>';
      break;
    case 'dcp-fav-star': return '<script>'.$fav_icon.'</script>';
      break;
    case 'ls_reveal': return '<script>'.$ls_reveal.'</script>';
      break;
  }
}
function dcp_tb($arg = null, $type = null){
  $ev_link = $arg['ev_link']; $ev_id = $arg['ev_id']; $target = 'target="_blank"'; $ev_name = $arg['ev_name']; $ev_nickname = $arg['ev_nickname'];
//   echo " ev_link: " . $ev_link . " ev_id: " . $ev_id . " target: " . $target . " ev_name: " . $ev_name . " ev_nickname: " . $ev_nickname;
  $hover = ' event-unlock__trigger ';
  $locked_style = 'style="opacity: 0.3;filter: grayscale(1);"';
  if(empty($arg['lock_status'])){ // Locked
    // Exclude custom events from direct purchase link on g365 product page
    if( $arg['ev_type'] == '7474' ){
      $ev_href = '<img class="small-margin-bottom" loading="lazy" data-src="'.$arg['img_logo'].'" alt="'.$arg['ev_name'].'" src="'.$arg['logo_img'].'">';
    }
    // Set certain products to be available to all DCP then update function dcp_access case 'is_ev_unlocked'
    elseif( $ev_id === '857' ){
       $ev_link = get_site_url() . '/account/dcp/teams/'.$ev_id.'/?type=team';
       $locked_style = ''; $target = ''; $hover = '';
       $ev_href = '<a href="'.$ev_link.'" '.$target.'><img class="small-margin-bottom" loading="lazy" data-src="'.$arg['img_logo'].'" alt="'.$arg['ev_name'].'" src="'.$arg['logo_img'].'">';
     }
    else{
      $ev_link = get_site_url() . '/product/digital-coaching-packet-'.$ev_nickname;
      $ev_href = '<a href="'.$ev_link.'" '.$target.'><img class="small-margin-bottom" loading="lazy" data-src="'.$arg['img_logo'].'" alt="'.$arg['ev_name'].'" src="'.$arg['logo_img'].'">';
    }
//     $ev_link = 
//     $product_link = '
//       <div class="event__tooltip small-padding" id="eventTooltip">
//        <i class="fi-x event__tooltip--exit"></i>
//         <i class="fi-unlock locked__modal--icon"></i>
//         <h3>'.$ev_name.'</h3>
//         <a href="'.$ev_link.'" class="small-padding locked__link locked__link--annual">Unlock '.$ev_name.' here.</a>
//       </div>
//     ';
  }else{ // Unlocked
    $ev_link = get_site_url() . '/account/dcp/teams/'.$ev_id.'/?type=team';
    $locked_style = ''; $target = ''; $hover = '';
    $ev_href = '<a href="'.$ev_link.'" '.$target.'><img class="small-margin-bottom" loading="lazy" data-src="'.$arg['img_logo'].'" alt="'.$arg['ev_name'].'" src="'.$arg['logo_img'].'">';
  }
  $dcp_ev = '
    <div class="cell">
      <div class="ev_inner is_act'.$hover.'" '.$locked_style.'>
        '.$ev_href.'
        <label class="emphasis">'.$arg['ev_name'].'</label></a>
        <label class="emphasis">'.g365_build_dates($arg['ev_date'], 2).'</label></a>
      </div>
    </div>
  ';
  return $dcp_ev;
}
function get_club_pl($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs());
  $ev_id = $arg['ev_id'];
  return $wpdb->get_results("SELECT ros.org AS org_id, ros.team AS team_id, org.name AS org_name, ros.level AS division, ros.division AS level_of_play, ros.players FROM $dbs->rosters ros INNER JOIN $dbs->orgs org ON ros.org = org.id WHERE ros.event = $ev_id ORDER BY division DESC, org_name DESC");
}
function dcp_access($arg = null, $type = null){
  global $wpdb; $dbs = json_decode(dbs()); $ev_id = $arg['ev_id']; $user_id = $arg['user_id']; if(!empty($arg['pl_id'])){ $pl_id = $arg['pl_id']; }
  $fields = " fav.event_id, ros.players "; $joins = " FROM $dbs->favorites fav INNER JOIN $dbs->rosters ros ON ros.event = fav.event_id "; $conditions = " WHERE user_id = $user_id AND event_id IS NOT NULL ";
  switch($type){
    case 'unlock_ev_list':
      $form = '
        <div>
          <form method="post" action="" id="dcp-form" class="grid-x">
            <div class="small-12 medium-12 large-3 small-padding-right" style="width: 152px">
              <select name="roster_level" id="roster_level" style="border-radius: 20px"> 
                <?php for($i = 8; $i <= 47; $i++): if(($i > 7 && $i < 18) || ($i > 39 && $i < 48 )):/*if-a*/ ?>
                  <option <?php if(isset($post_ros_level) && $post_ros_level == $i){echo "selected= "selected"";} ?> value="<?php echo $i ?>"><?php echo $g365_stat_leader[1][$i]; ?></option> 
                <?php endif;/*if-a*/ endfor; ?>
              </select>
            </div>
          </form>
        </div>
      ';
      return $result; 
      break;
    case 'is_ev_unlocked':
      $authorized_user = get_current_user_id();
      if(!empty($ev_id)){
        // This is where certain event is unlocked to all users
        if($ev_id = '857'){
          return true;
        }else{
          $fields = " user_id "; $conditions = " WHERE user_id = $user_id AND event_id = $ev_id ";
          $is_unlocked = $wpdb->get_results("SELECT $fields FROM $dbs->favorites fav 
    $conditions");
          $is_unlocked = json_decode(json_encode($is_unlocked), true);
          if(in_array($user_id, $is_unlocked[0])){ return true; }else{ return false; }
        }
      }
      break;
//     case 'is_ls_pl_unlocked':
//       $fields = " user_id "; $conditions = " WHERE user_id = $authorized_user AND event_id = $ev_id ";
//       $is_unlocked = $wpdb->get_results("SELECT $fields FROM $dbs->favorites fav 
//   $conditions");
//       break;
    case 'is_pl_unlocked':
      $is_unlocked = $wpdb->get_results("SELECT $fields $joins $conditions");
      $fields = " ev.id AS ev_id, ev.name AS ev_name, ev.logo_img, ev.link, ev.nickname ";
      $conditions = " WHERE ros.players LIKE CONCAT('%\"',$pl_id,'\":%') AND ev.enabled = 1 AND ev.org = 3 ";
      $pl_ev_access = $wpdb->get_results(" SELECT $fields FROM $dbs->rosters ros INNER JOIN $dbs->events ev ON ros.event = ev.id $conditions ");
      $avai_ev_list = array(); $pl_ev_access_dec = json_decode(json_encode($pl_ev_access), true);
      foreach($pl_ev_access_dec as $pl_ev_acc){
        $unlock_dcp_link = get_site_url().'/product/digital-coaching-packet-'.$pl_ev_acc['nickname'];
        $avai_ev = '<div class="flex item-center text-center small-padding-bottom" style="width:150px;"><a href="'.$unlock_dcp_link.'" target="_blank"><img class="small-margin-bottom" loading="lazy" data-src="" alt="'.$pl_ev_acc['ev_name'].'" src="'.$pl_ev_acc['logo_img'].'"><label class="emphasis">'.$pl_ev_acc['ev_name'].'</label></a></div>';
        $avai_ev_list[] = $avai_ev;
      } 
      $avai_ev_list = implode(' ', $avai_ev_list);
      $ls_pl_unlocked_revl = fav_reveal(['data_toggle'=>'pl_'.$arg['pl_id'], 'full_name'=>$arg['pl_name'], 'school'=>$arg['school'], 'pl_nickname'=>$arg['pl_nickname'], 'data_note'=>'note_'.$arg['pl_id'], 'fav_data'=>$arg['fav_data'], 'pl_id'=>$arg['pl_id'], 'pl_img'=> empty($arg['pl_profile_img']) ? $arg['default_img'] : get_site_url().'/wp-content/uploads/player-profiles/'.$arg['pl_profile_img'], 'redir_url'=>get_site_url().(explode('?', $_SERVER['REQUEST_URI'], 2)[0]), 'pl_grad_year'=>(empty($arg['pl_grad_year']) ? "" : $arg['pl_grad_year']), 'pl_position'=>(empty($arg['pl_position']) ? "" : $arg['pl_position']), 'pl_height'=>(empty($arg['pl_height']) ? "" : $arg['pl_height']), 'gpa'=>(empty($arg['gpa']) ? "" : $arg['gpa']), 'sat'=>(empty($arg['sat']) ? "" : $arg['sat']), 'act'=>(empty($arg['act']) ? "" : $arg['act']), 'pl_contact_info'=>(empty($arg['pl_contact_info']) ? "" : $arg['pl_contact_info'])], 'ls_pl');
      $ls_pl_locked_revl = fav_reveal(['data_toggle'=>'pl_'.$arg['pl_id'],  'ev_name'=>$arg['ev_name'], 'ev_logo'=>$avai_ev_list, 'ev_link'=>$pl_ev_access[0]->link, 'full_name'=>$arg['pl_name'], 'pl_nickname'=>$arg['pl_nickname'], 'data_note'=>'note_'.$arg['pl_id'], 'fav_data'=>$arg['fav_data'], 'pl_id'=>$arg['pl_id'], 'pl_img'=> empty($arg['pl_profile_img']) ? $arg['default_img'] : get_site_url().'/wp-content/uploads/player-profiles/'.$arg['pl_profile_img'], 'redir_url'=>get_site_url().(explode('?', $_SERVER['REQUEST_URI'], 2)[0])], 'locked_ls_pl');
      $pl_list = array();
      foreach($is_unlocked as $other_data){
        $other_data->players = json_decode($other_data->players);
        foreach($other_data->players as $pl_id => $data){ $pl_list[] = $pl_id; }
      }
      if(in_array($arg['pl_id'], $pl_list)){ return $ls_pl_unlocked_revl; }else{ return $ls_pl_locked_revl; }
      break;
  }
}
function get_dcp_auth_data($arg = null, $type = null){
  switch($type){
    case 'fav-star':
      $form = '<span style="font-size:20px;cursor:pointer" data-select-id="'.$arg['pl_id'].'" id="'.$arg['pl_id'].'" data-toggle="pl_'.$arg['pl_id'].'">'.$arg['fav_icon'].'</span>';
      $fav_reveal = fav_reveal(['data_toggle'=>'pl_'.(empty($arg['pl_id']) ? "" : $arg['pl_id']), 'full_name'=>(empty($arg['pl_name']) ? "" : $arg['pl_name']), 'pl_nickname'=>(empty($arg['pl_nickname']) ? "" : $arg['pl_nickname']), 'school'=>(empty($arg['school']) ? "" : $arg['school']), 'data_note'=>'note_'.(empty($arg['pl_id']) ? "" : $arg['pl_id']), 'fav_data'=>(empty($arg['fav_data']) ? "" : $arg['fav_data']), 'pl_id'=>(empty($arg['pl_id']) ? "" : $arg['pl_id']), 'pl_img'=>(empty($arg['pl_img']) ? "" : $arg['pl_img']), 'pl_grad_year'=>(empty($arg['pl_grad_year']) ? "" : $arg['pl_grad_year']), 'pl_position'=>(empty($arg['pl_position']) ? "" : $arg['pl_position']), 'pl_height'=>(empty($arg['pl_height']) ? "" : $arg['pl_height']), 'gpa'=>(empty($arg['gpa']) ? "" : $arg['gpa']), 'sat'=>(empty($arg['sat']) ? "" : $arg['sat']), 'act'=>(empty($arg['act']) ? "" : $arg['act']), 'pl_contact_info'=>(empty($arg['pl_contact_info']) ? "" : $arg['pl_contact_info'])], 'add_fav');
      if($arg['enabled_access'] == true){
        if(dcp_access(['ev_id'=>$arg['ev_id'], 'user_id'=>$arg['user_id']], 'is_ev_unlocked') == true){
          return ['fav_icon'=>$form, 'enabled_reveal'=>$fav_reveal];
        }
      }
      break;
  }
}
function order_array($array, $custom_arrays){
  $new_order = array();
  foreach($custom_arrays as $custom_array){
    if(isset($array[$custom_array])){
      $new_order[$custom_array] = $array[$custom_array];
    }
  }
  return $new_order;
}
function g365_remote_tournament_award($arg = null){
  $g365_ad_info = g365_start_ads( $post->ID );
  $default_profile_img = 'https://sportspassports.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
//   return $default_profile_img; exit;
  //we need all group options to create the navigation
  $award_options = g365_get_groups_data( 89, 3 , array('truncate'=>true, 'tsc_only'=>true) );
  //if the group id doesn't come in on the url set it to be global
  $post_aw_id = $arg['award_id'];
  if( empty($post_aw_id) ){ $award_id = ( !empty($award_options) ? $award_options->records[0]->id : null); }else{ $award_id = $post_aw_id; } 
  //get all the awards
  $award_data = ( !empty($award_id) ) ? g365_build_awards( $award_id, array('truncate'=>true) ) : null;
  //general urls for the page build, one version for page switching, one referencing this page
  // Check if site is dev or production then reroute it to the stage url.
  if(strpos(get_site_url(), 'dev.') !== false){
    $tsc_url = 'https://dev.thestagecircuit.com';
  }else{ $tsc_url = 'https://thestagecircuit.com'; }
  $awards_url = $tsc_url.'/tournament-awards/'; 
  $awards_url_event = $awards_url . $award_id;
  $award_data_divisions = g365_build_awards(89);
  $key_level = (g365_return_keys('g365_all_tournament_grade_key'));
  $allTournamentbg = 'https://grassroots365.com/wp-content/uploads/2021/09/all-tournament-header-1.jpg';
  $aw_script = ' <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js?ver=2.2.4&loc=8" id="jquery-js"></script>
  <script>$("#series_selector, #view_switch").change(function(){ window.location.href = $( "option:selected", this ).val(); });</script>
  ';
//   $get_template_part = get_template_part('page-parts/content', get_post_type());
//   $get_template_part_none = get_template_part('page-parts/content', 'none');
  return ['g365_ad_info'=>$g365_ad_info, 'default_profile_img'=>$default_profile_img, 'award_options'=>$award_options, 'have_posts'=>have_posts(), 'the_post'=>the_post(), 'allTournamentbg'=>$allTournamentbg, 'awards_url'=>$awards_url, 'award_data'=>$award_data, 'key_level'=>$key_level, 'aw_script'=>$aw_script, 'award_id'=>$award_id];
}
function file_dir($type = null){
  switch($type){
    case 'filepond':
      return get_site_url().('/wp-content/themes/g365-press/inc/filepond-core/');
      break;
    case 'filepond-js':
      return get_site_url().('/wp-content/themes/g365-press/inc/');
      break;
  }
}
function filepond_core(){
  $files = '
    <link href="'.file_dir('filepond').'filepond-plugin-image-preview.css" rel="stylesheet" />
    <link href="'.file_dir('filepond').'filepond-plugin-image-edit.css" rel="stylesheet" />
    <link href="'.file_dir('filepond').'filepond.css" rel="stylesheet" />
    <script src="'.file_dir('filepond').'filepond.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-preview.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-file-validate-type.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-crop.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-edit.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-exif-orientation.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-resize.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-transform.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-image-validate-size.js"></script>
    <script src="'.file_dir('filepond').'filepond-plugin-file-validate-size.js"></script>
  ';
  $ext_files = '<script src="'.file_dir('filepond-js').'filepond-management/filepond.js"></script>';
  return ['core'=>$files, 'ext'=>$ext_files];
}
function database_columns($type, $args = null){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($args['db_tb_name']) ? $db_tb_name = $args['db_tb_name'] : $db_tb_name = '';
  switch($type){
    case 'badges':
      $badge_columns = array();
      $tb_name = $dbs->$db_tb_name;
//       $results = $wpdb->get_results(" SELECT `COLUMN_NAME` badge FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` = '$tb_name' GROUP BY `COLUMN_NAME` ");
      $results = $wpdb->get_results(" SELECT badge FROM( SELECT `COLUMN_NAME` badge, `ORDINAL_POSITION` position FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` = '$tb_name' GROUP BY position, badge ORDER BY position ASC ) tb_data ");
      foreach($results as $index => $result){
        $badge_columns[$result->badge] = $args[$result->badge];
      }
      return $badge_columns;
      break;
    case 'app-device-token':
      $device_token = array();
      $tb_name = $dbs->$db_tb_name;
      $results = $wpdb->get_results(" SELECT device_token FROM( SELECT `COLUMN_NAME` device_token, `ORDINAL_POSITION` position FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` = '$tb_name' GROUP BY position, device_token ORDER BY position ASC ) tb_data ");
      foreach($results as $index => $result){
        $device_token[$result->device_token] = $args[$result->device_token];
      }
      return $device_token;
      break;
  }
}
function g365_db_handler($type = null, $query_type = null, $fields = null){
  global $wpdb; $dbs = json_decode(dbs());
  empty($fields['pl_id']) ? $pl_id = 'NULL': $pl_id = $fields['pl_id'];
  empty($fields['img_name']) ? $img_name = 'NULL' : $img_name = $fields['img_name'];
  empty($fields['private']) ? $private = 0 : $private = $fields['private'];
  empty($fields['img_id']) ? $img_id = 'NULL' : $img_id = $fields['img_id'];
  $auth_user = $fields['auth_user'];
  if(empty($fields['claimed_pl']['pl_ed'])){ $claimed_data = ''; }else{ $claimed_data = implode(',', $fields['claimed_pl']['pl_ed']); }
  $file_type = str_replace(array('video/', 'image/'), array('', ''), $fields['file_type']);
//   print_r($fields['file_type']); die;
  switch($type){
    case 'photo-upload':
      switch($query_type){
        case 'admin':
          switch($fields['dup_name']){
            case '':
              $img_name = $fields['img_name'];
              break;
            case 'found':
//               $img_name = wp_date('m-j-y-h-i-s-') . $fields['img_name'];
              $img_name = wp_date('Y-m-d-H-i-') . $fields['img_name'];
              break;
          }
          $query = 'INSERT';
          $current_post_date = wp_date('Y-m-d H:i:s');
          $field_vals = " '".$current_post_date."', '".$current_post_date."', DEFAULT, ".$auth_user.", 1, JSON_OBJECT('pl_id', JSON_ARRAY()), ".$private.", 1, '".$img_name."', 0, JSON_OBJECT('file_type', '".$file_type."'), 1 ";
          $wpdb->query("$query INTO $dbs->images VALUES ($field_vals)");
          break;
        case 'user':
          switch($fields['dup_name']){
            case '':
              $img_name = $fields['img_name'];
              break;
            case 'found':
//               $img_name = wp_date('m-j-y-h-i-s-') . $fields['img_name'];
              $img_name = wp_date('Y-m-d-H-i-') . $fields['img_name'];
              break;
          }
          $query = 'INSERT';
          $current_post_date = wp_date('Y-m-d H:i:s');
          $field_vals = " '".$current_post_date."', '".$current_post_date."', DEFAULT, ".$auth_user.", 1, JSON_OBJECT('pl_id', JSON_ARRAY(".$claimed_data.")), ".$private.", 1, '".$img_name."', 1, JSON_OBJECT('file_type', '".$file_type."'), 0 ";
          $dbs = json_decode(dbs());
          $wpdb->query("$query INTO $dbs->images VALUES ($field_vals)");
          break;
      }
      break;
    case 'photo-player':
      $vid_ext = g365_media_file_type('video-ext-str');
      $query = 'UPDATE'; empty($fields['pl_id_list']) ? $pl_id_list = '': $pl_id_list = $fields['pl_id_list'];
      $results = $wpdb->get_results(" SELECT * FROM $dbs->images WHERE enabled = 1 AND rejected = 0 AND admin_addition = 0 AND ( player_id LIKE '%[$pl_id_list]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($vid_ext) ) ");
//       return (sizeof($results));
      if($fields['admin_approved'] == '1'){
        // Set the first approved video public and any after in private so that user can set only one profile video at a time
        if(sizeof($results) > 0.5){ $is_private = ' , private = 1 '; $is_admin_approved = ' , rejected = 0 '; }
        else{  $is_private = ' , private = 0 '; $is_admin_approved = ' , rejected = 0 '; }
      }
      else{ $is_admin_approved = ' , rejected = 1 '; $is_private = ' , private = 0 '; }
      if($fields['photo_private'] == '1'){ $is_user_set_private = ' private = 0 '; }else{ $is_user_set_private = ' private = 1 '; }
      switch($query_type){
        case 'admin-assigned':
          $wpdb->query("$query $dbs->images SET player_id = '{\"pl_id\": [$pl_id_list]}' WHERE id = $img_id");
          break;
        case 'admin-toggle': // Pending or Approve
          $results = $wpdb->get_results(" SELECT * FROM $dbs->images WHERE enabled = 1 AND rejected = 0 AND admin_addition = 0 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($vid_ext) ) ");
          $wpdb->query("$query $dbs->images SET player_id = '{\"pl_id\": [$pl_id_list]}' $is_admin_approved $is_private WHERE id = $img_id");
          break;
        case 'user-toggle': // Private or Public
          if(!empty($fields['user_admin_toggle']) && $fields['user_admin_toggle'] === true){ $is_user_admin_toggle = ''; }else{ $is_user_admin_toggle = " player_id = '{\"pl_id\": [$pl_id_list]}', "; }
          $wpdb->query("$query $dbs->images SET $is_user_set_private WHERE id = $img_id");
          break;
        case 'user-assigned': // Edit claimed player(s) under approved uploaded photos
          $wpdb->query("$query $dbs->images SET player_id = '{\"pl_id\": [$pl_id_list]}' WHERE id = $img_id");
          break;
      }
      break;
    case 'photo-edit':
      $query = 'UPDATE';
      switch($query_type){
        case 'admin-delete':
          $wpdb->query("$query $dbs->images SET enabled = 0 WHERE id = $img_id");
          break;
        case 'user-toggle-app': // Private or Public for mobile app
          empty($fields['toggle_val']) ? $toggle_val = '0' : $toggle_val = $fields['toggle_val'];
          $wpdb->query("$query $dbs->images SET private = $toggle_val WHERE id = $img_id");
          break;
      }
      break;
    case 'admin-badge':
      $query = 'INSERT';
      $enabled = $fields['badge_enabled'];
      $note_data = $fields['note_data'];
      $website_id = $fields['website_id'];
      $badge_individual_ev_json = $fields['badge_individual_ev_json'];
      $badge_id = $fields['badge_id'];
      $badge_type = $fields['badge_type'];
      $badge_range = $fields['badge_range'];
      $badge_url = $fields['badge_url'];
      $website_id = implode(', ', $website_id);
      $catagory_op = json_decode(json_encode($fields['badge_cat_op_array']), true);
      $catagory_op_val = json_decode(json_encode($fields['badge_gr_cat_op_val_array']), true);
      $stat_type_array = json_decode(json_encode($fields['stat_type_array']), true);
      $badge_gr_stat_op_array = json_decode(json_encode($fields['badge_gr_stat_op_array']), true);
      $badge_gr_stat_op_val_array = json_decode(json_encode($fields['badge_gr_stat_op_val_array']), true);
      $season_year = $fields['season_year_val'];
      !empty($season_year) ? $json_season_year = ' "season_year", "'.$season_year.'" ' : $json_season_year = '';
      $badge_name = $fields['badge_name'];
      $badge_data_validation = g365_data_validation('badge-data', 'data-validation', ['check_op_int'=>$catagory_op, 'check_op_val'=>$catagory_op_val, 'check_indi_ev'=>$badge_individual_ev_json, 'check_stat_type'=>$stat_type_array, 'check_stat_op'=>$badge_gr_stat_op_array, 'check_stat_op_val'=>$badge_gr_stat_op_val_array, 'operator_list'=>badge_catagory('badge-catagory-validation')]);
      $current_post_date = wp_date('Y-m-d H:i:s');
      if(!empty($badge_data_validation['valid_op_val_results']['event']) && !empty($badge_data_validation['indi_ev_val_result']['event'])){
        $add_indi = ', ';
      }
      $season_field = ' '.$badge_data_validation['valid_op_results']['season'].' '.$badge_data_validation['valid_op_val_results']['season'].' '.$json_season_year.' ';
      $event_field = ' '.$badge_data_validation['valid_op_results']['event'].' '.$badge_data_validation['valid_op_val_results']['event'].' '.$add_indi.' '.$badge_data_validation['indi_ev_val_result']['event'].' ';
      $game_field = ' '.$badge_data_validation['valid_op_results']['game'].' '.$badge_data_validation['valid_op_val_results']['game'].' ';
      $trophy_field = ' '.$badge_data_validation['valid_op_results']['trophy'].' '.$badge_data_validation['valid_op_val_results']['trophy'].' ';
      $pts_field = ' '.$badge_data_validation['valid_st_type_results']['pts'].' '.$badge_data_validation['valid_st_op_results']['pts'].' '.$badge_data_validation['valid_st_op_val_results']['pts'].' ';
      $reb_field = ' '.$badge_data_validation['valid_st_type_results']['reb'].' '.$badge_data_validation['valid_st_op_results']['reb'].' '.$badge_data_validation['valid_st_op_val_results']['reb'].' ';
      $ast_field = ' '.$badge_data_validation['valid_st_type_results']['ast'].' '.$badge_data_validation['valid_st_op_results']['ast'].' '.$badge_data_validation['valid_st_op_val_results']['ast'].' ';
      $stl_field = ' '.$badge_data_validation['valid_st_type_results']['stl'].' '.$badge_data_validation['valid_st_op_results']['stl'].' '.$badge_data_validation['valid_st_op_val_results']['stl'].' ';
      $blk_field = ' '.$badge_data_validation['valid_st_type_results']['blk'].' '.$badge_data_validation['valid_st_op_results']['blk'].' '.$badge_data_validation['valid_st_op_val_results']['blk'].' ';
      $three_pt_field = ' '.$badge_data_validation['valid_st_type_results']['three_pt'].' '.$badge_data_validation['valid_st_op_results']['three_pt'].' '.$badge_data_validation['valid_st_op_val_results']['three_pt'].' ';
      $bdg_db_columns = database_columns('badges', ['db_tb_name'=>'badges', 'id'=>$badge_id, 'createdate'=>"'$current_post_date'", 'updatetime'=>"'$current_post_date'", 'enabled'=>$enabled, 'website'=>'JSON_OBJECT("value", JSON_ARRAY('.$website_id.'))', 'season'=>'JSON_OBJECT('.$season_field.')', 'event'=>'JSON_OBJECT('.$event_field.')', 'game'=>'JSON_OBJECT('.$game_field.')', 'trophy'=>'JSON_OBJECT('.$trophy_field.')', 'point'=>'JSON_OBJECT('.$pts_field.')', 'rebound'=>'JSON_OBJECT('.$reb_field.')', 'assist'=>'JSON_OBJECT('.$ast_field.')', 'steal'=>'JSON_OBJECT('.$stl_field.')', 'block'=>'JSON_OBJECT('.$blk_field.')', 'three_pt'=>'JSON_OBJECT('.$three_pt_field.')', 'note'=>"'$note_data'", 'badge_type'=>"'$badge_type'", 'badge_range'=>"'$badge_range'", 'badge_name'=>"'$badge_name'", 'badge_url'=>"'$badge_url'", 'admin_addition'=>0]);
      $field_vals = implode(', ', $bdg_db_columns);
      $wpdb->query("$query INTO $dbs->badges VALUES ($field_vals) ON DUPLICATE KEY UPDATE updatetime=CURRENT_TIMESTAMP, enabled =values(enabled), website=values(website), season=values(season), event=values(event), game=values(game), trophy=values(trophy), point=values(point), rebound=values(rebound), assist=values(assist), steal=values(steal), block=values(block), three_pt=values(three_pt), note=values(note), badge_type=values(badge_type), badge_range=values(badge_range), badge_name=values(badge_name), badge_url=values(badge_url) ");
      break;
    case 'player-badge':
      $group_concat_max_len = " SET SESSION group_concat_max_len = 18446744073709551615; ";
      $wpdb->get_results("$group_concat_max_len");
      $query = 'INSERT';
      $current_post_date = wp_date('Y-m-d H:i:s');
      $insert_field_list = array(); $player_id_list = array(); $player_id_lists = array(); $stat_conditions_list = array(); $badge_types_list = array(); $season_date_range = array(); $open_num_season_list = array(); $num_season_field_list = array(); $close_num_season_list = array(); $bdg_type_value = array(); $bdg_type_operator = array(); $get_field_type_value = array(); $number_of_type = array(); $number_of_type_list = array(); $stat_avg_conditions_list = array(); $stat_avg_fields_list = array(); $inner_tb_where = array(); $stat_fields_total_list = array(); $stat_conditions_total_list = array(); $stat_conditions_field_list = array(); $stat_types_json_list = array(); $cumulative_stats = array(); $avg_min_gm_stats = array(); $avg_min_gm_conditions = array(); $cumulative_stats_field = array();
//       if(!empty($fields['badge_id_list'])){
//         $badge_ids_list = $fields['badge_id_list'];
//         $badge_ids_list = implode(', ', $badge_ids_list);
//         $selected_badge_id = "AND id IN ($badge_ids_list)";
//       }else{ $selected_badge_id = ""; }
      
      if(!empty($fields['badge_id_list']) && is_numeric($fields['badge_id_list'])){
        $badge_ids_list = $fields['badge_id_list'];
        $selected_badge_id = "AND id IN ($badge_ids_list)";
      }else{ $selected_badge_id = ""; }
      
//       $player_badges = $wpdb->get_results("SELECT * FROM $dbs->badges bdg WHERE JSON_EXTRACT(bdg.event, '$.indi_val') IS NULL");
//       if(!empty($player_badges)){
//         $row_number = 1;
//         foreach($player_badges as $player_badges_data){
//           $row_number++;
//           $player_badges_data = json_decode(json_encode($player_badges_data), true);
//           $website_id = json_decode($player_badges_data['website']);
//           $website_id = implode(', ', $website_id->value);
//           $note = $player_badges_data['note'];
//           $enabled = $player_badges_data['enabled'];
//           $badge_id = $player_badges_data['id'];
//           $badge_type_data = $player_badges_data['badge_type'];
//           $badge_range_data = $player_badges_data['badge_range'];
//           $badge_name = $player_badges_data['badge_name'];
//           $badge_url = $player_badges_data['badge_url'];
//           $badge_admin_addition = $player_badges_data['admin_addition'];
//           $badge_types = badge_catagory('badge-type');
//           // Check each badge catagories data
//           foreach($badge_types as $badge_type){
//             $get_player_bdg_type = json_decode($player_badges_data[$badge_type]);
//             $bdg_type_value[$badge_type] = $get_player_bdg_type->value;
//             $bdg_type_operator[$badge_type] = $get_player_bdg_type->operator;
//             $bdg_type_season = $get_player_bdg_type->season_year;
//             if(!empty($bdg_type_season)){ // Only include date range if season year is available
//               $bdg_type_season = explode(', ', $bdg_type_season);
//               foreach($bdg_type_season as $each_season){
//                 $season_date_range[$row_number][] = g365_date_format($each_season, 1);
//               }
//               $get_list_in_str = implode(' OR st.updatetime BETWEEN ', $season_date_range[$row_number]);
//               $badge_types_list[$row_number] = " AND (st.updatetime BETWEEN ".$get_list_in_str.") "; // Date range
//             }
//             // Create outer select table
//             $open_num_season_list[$row_number] = " SELECT * FROM ( ";
//             // Set number of season as default string
//             $num_season_field_list[$row_number] = " , COUNT(DISTINCT IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime))) number_of_season ";
//             $close_num_season_list[$row_number] = " ) inner_tb ";
//             // Check if catagory types are not empty and exclude season
//             if(!empty($bdg_type_value[$badge_type])){
//               if($badge_type == 'season'){
//                 $get_field_type_value[$row_number][$badge_type] = " ".$num_season_field_list[$row_number]." ";
//               }else{ $get_field_type_value[$row_number][$badge_type] = " , COUNT(DISTINCT st.".$badge_type.") number_of_".$badge_type."  "; }
//               $number_of_type[$row_number][$badge_type] = " number_of_".$badge_type." ".$bdg_type_operator[$badge_type]." ".$bdg_type_value[$badge_type]." ";
//               $number_of_type_list[$row_number][$badge_type] = $number_of_type[$row_number][$badge_type];
//             }else{
//               $get_field_type_value[$row_number][$badge_type] = '';
//               $number_of_type[$row_number][$badge_type] = '';
//             }
//           }
//           $bdg_field_value_index = implode('', $get_field_type_value[$row_number]); // Catatory type fields to use for db columns
//           $bdg_type_value_index = implode('AND ', $number_of_type_list[$row_number]); // Catatory type values to use after inner table where clause
//   //         echo "<pre>"; print_r($bdg_field_value_index); echo "</pre>";
//           // Check stats
//           $stat_catagories = badge_catagory('badge-stat-catagory');
//           foreach($stat_catagories as $index => $stat_catagory){
//             $get_player_bdg_stat = json_decode($player_badges_data[$stat_catagory]);
//             $bdg_data_value = $get_player_bdg_stat->value;
//             $bdg_data_operator = $get_player_bdg_stat->operator;
//             $bdg_data_type = $get_player_bdg_stat->type;
//             switch($bdg_data_type){
//               case 'total':
//                 // Set total stat to use with where clause
//                 if(!empty($bdg_data_operator)){ 
//                   $stat_fields_total_list[$row_number][] = ", SUM(JSON_EXTRACT(st.stats, '$.".$index."')) total_".$index." "; 
//                   $stat_conditions_total_list[$row_number][] = " total_".$index." ".$bdg_data_operator . $bdg_data_value."  ";
//                 }
//                 break;
//               case 'average':
//                 if(!empty($bdg_data_operator)){ 
//                   $stat_avg_fields_list[$row_number][] = ", AVG(JSON_EXTRACT(st.stats, '$.".$index."')) avg_".$index." ";
//                   $stat_avg_conditions_list[$row_number][] = " avg_".$index." ".$bdg_data_operator . $bdg_data_value." ";
//                 }
//                 break;
//               case 'individual_game':
//                 if(!empty($bdg_data_operator)){ $stat_conditions_list[$row_number][] = " JSON_EXTRACT(st.stats, '$.".$index."') ".$bdg_data_operator . $bdg_data_value."  "; }
//                   break;
//             }
//           }
//           $stat_condition_total_list = implode('AND ', $stat_conditions_total_list[$row_number]); // Total
//           $stat_avg_condition_list = implode('AND ', $stat_avg_conditions_list[$row_number]); // Average
//           $stat_condition_list = implode('AND ', $stat_conditions_list[$row_number]); // Individual
//           $stat_field_total_list = implode('', $stat_fields_total_list[$row_number]);
//           $stat_avg_field_list = implode('', $stat_avg_fields_list[$row_number]);
//   //          echo "<pre>"; print_r($stat_condition_list); echo "</pre>";
//           $fields = " pl.id player_id $bdg_field_value_index $stat_avg_field_list $stat_field_total_list";
//           !empty($stat_condition_list) ? $and = " AND " : $and = "";
//           if(!empty($stat_condition_list) && !empty($stat_avg_condition_list)){ $avg_sign = " AND "; }
//           elseif(empty($stat_condition_list) && !empty($stat_avg_condition_list)){ $avg_sign = " WHERE "; }
//           else{ $avg_sign = ""; }
//           $where = " WHERE st.enabled = 1 AND st.game != 0 AND pl.enabled = 1 $and $stat_condition_list AND ev.org in ($website_id) $badge_types_list[$row_number] ";
//           ( !empty($bdg_type_value_index) || !empty($stat_avg_condition_list) || !empty($stat_condition_total_list) ) ? $is_where = " WHERE " : $is_where = "";
//           ( !empty($bdg_type_value_index) && (!empty($stat_avg_condition_list) || !empty($stat_condition_total_list) || !empty($stat_condition_list)) ) ? $is_type_and = " AND " : $is_type_and = "";
//           ( !empty($stat_avg_condition_list) && (!empty($stat_condition_total_list) || !empty($stat_condition_list)) ) ? $is_avg_and = " AND " : $is_avg_and = "";
//   //         ( !empty($stat_condition_total_list) && !empty($stat_condition_list) ) ? $is_indi_and = " AND " : $is_indi_and = "";
//           $inner_tb_where[$row_number] = $is_where . $bdg_type_value_index . $is_type_and . $stat_avg_condition_list . $is_avg_and . $stat_condition_total_list;
//           $group_by = " GROUP BY pl.id ";
//           $joins = " FROM $dbs->players pl INNER JOIN $dbs->stats st ON pl.id = st.player INNER JOIN $dbs->events ev ON st.event = ev.id ";
//           $badge_queries = $wpdb->get_results("$open_num_season_list[$row_number] SELECT $fields $joins $where $group_by $close_num_season_list[$row_number] $inner_tb_where[$row_number] ");

//           foreach($badge_queries as $badge_query){ $player_id_list[$row_number][] = $badge_query->player_id; }
//           $player_id_string = implode(', ', $player_id_list[$row_number]);
//           $player_id_lists[$row_number] = $player_id_string;
//           $player_bdg_data = "[$player_id_lists[$row_number]]";
//           $badges_core_fields = database_columns('badges', ['db_tb_name'=>'badges_core', 'id'=>'DEFAULT', 'createdate'=>"'$current_post_date'", 'updatetime'=>"'$current_post_date'", 'enabled'=>"'$enabled'", 'note'=>"'$note'", 'badge_id'=>$badge_id, 'badge_type'=>"'$badge_type_data'", 'badge_range'=>"'$badge_range_data'",  'badge_name'=>"'$badge_name'", 'badge_url'=>"'$badge_url'", 'admin_addition'=>$badge_admin_addition, 'badge_data'=>"'$player_bdg_data'"]);
//           $badges_core_fields = implode(', ', $badges_core_fields);
//           $insert_field_list[] = "( $badges_core_fields )";
//         }
//         $insert_field_values = implode(', ', $insert_field_list);
//         $wpdb->query("$query INTO $dbs->badges_core VALUES $insert_field_values ON DUPLICATE KEY UPDATE updatetime=CURRENT_TIMESTAMP, enabled=values(enabled), note=values(note), badge_name=values(badge_name), badge_type=values(badge_type), badge_range=values(badge_range), badge_url=values(badge_url), admin_addition=values(admin_addition), badge_data=values(badge_data) ");
//       }
      
      /*** By Individual Event ***/
      $player_indi_ev = $wpdb->get_results(" SELECT * FROM $dbs->badges bdg WHERE bdg.enabled = 1 AND JSON_EXTRACT(bdg.event, '$.indi_val') IS NOT NULL $selected_badge_id ");
//       if(!empty($player_indi_ev)){
        foreach($player_indi_ev as $player_badges_data){
          $row_number++;
          $player_badges_data = json_decode(json_encode($player_badges_data), true);
          $website_id = json_decode($player_badges_data['website']);
          $website_id = implode(', ', $website_id->value);
          $note = $player_badges_data['note'];
          $enabled = $player_badges_data['enabled'];
          $badge_id = $player_badges_data['id'];
          $badge_type_data = $player_badges_data['badge_type'];
          $badge_range_data = $player_badges_data['badge_range'];
          $badge_name = $player_badges_data['badge_name'];
          $badge_url = $player_badges_data['badge_url'];
          $badge_admin_addition = $player_badges_data['admin_addition'];
          $badge_types = badge_catagory('badge-type');
          // Check each badge catagories data
          foreach($badge_types as $badge_type){
            $get_player_bdg_type = json_decode($player_badges_data[$badge_type]);
            $bdg_type_value[$badge_type] = $get_player_bdg_type->value;
            $bdg_type_operator[$badge_type] = $get_player_bdg_type->operator;
            $bdg_type_season = $get_player_bdg_type->season_year;
            if(!empty($bdg_type_season)){ // Only include date range if season year is available
              $bdg_type_season = explode(', ', $bdg_type_season);
              foreach($bdg_type_season as $each_season){
                $season_date_range[$row_number][] = g365_date_format($each_season, 1);
              }
              $get_list_in_str = implode(' OR st.updatetime BETWEEN ', $season_date_range[$row_number]);
              $badge_types_list[$row_number] = " AND (st.updatetime BETWEEN ".$get_list_in_str.") "; // Date range
            }
            // Create outer select table
            $open_list[$row_number] = " SELECT CONCAT('[', GROUP_CONCAT(group_ev_bdg_data), ']') badge_data FROM (SELECT player_id, SUM(pts) cumulative_stat, COUNT(number_of_game) per_event_game, event_id, GROUP_CONCAT(player_badge_data) group_ev_bdg_data FROM ( ";
            // Set number of season as default string
            $num_season_field_list[$row_number] = " , COUNT(DISTINCT IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime))) number_of_season ";  
            $close_inner_tb_list[$row_number] = " ) inner_tb ";
            $close_bdg_data_list[$row_number] = " ) tb_badge_data ";
            // Check if catagory types are not empty and exclude season
            if(!empty($bdg_type_value[$badge_type])){
              if($badge_type == 'season'){
                $get_field_type_value[$row_number][$badge_type] = " ".$num_season_field_list[$row_number]." ";
              }else{ $get_field_type_value[$row_number][$badge_type] = " , COUNT(DISTINCT st.".$badge_type.") number_of_".$badge_type."  "; }
              $number_of_type[$row_number][$badge_type] = " per_event_".$badge_type." ".$bdg_type_operator[$badge_type]." ".$bdg_type_value[$badge_type]." ";
              $number_of_type_list[$row_number][$badge_type] = $number_of_type[$row_number][$badge_type];
            }else{
              $get_field_type_value[$row_number][$badge_type] = '';
              $number_of_type[$row_number][$badge_type] = '';
            }
          }
          $bdg_field_value_index = implode('', $get_field_type_value[$row_number]); // Catatory type fields to use for db columns
//           $bdg_type_value_index = implode('AND ', $number_of_type_list[$row_number]); // Catatory type values to use after inner table where clause
          // Check stats
          $stat_catagories = badge_catagory('badge-stat-catagory');
          foreach($stat_catagories as $index => $stat_catagory){
            $get_player_bdg_stat = json_decode($player_badges_data[$stat_catagory]);
            $bdg_data_value = $get_player_bdg_stat->value;
            $bdg_data_operator = $get_player_bdg_stat->operator;
            $bdg_data_type = $get_player_bdg_stat->type;
            switch($bdg_data_type){
              case 'total':
                // Set total stat to use with where clause
                if(!empty($bdg_data_operator)){ 
                  $stat_fields_total_list[$row_number][] = ", SUM(JSON_EXTRACT(st.stats, '$.".$index."')) total_".$index." "; 
                  $stat_conditions_total_list[$row_number][] = " total_".$index." ".$bdg_data_operator . $bdg_data_value."  ";
                }
                break;
              case 'average':
                if(!empty($bdg_data_operator)){ 
                  $stat_avg_fields_list[$row_number][] = ", AVG(JSON_EXTRACT(st.stats, '$.".$index."')) avg_".$index." ";
                  $stat_avg_conditions_list[$row_number][] = " avg_".$index." ".$bdg_data_operator . $bdg_data_value." ";
                }
                break;
              case 'individual_game':
                if(!empty($bdg_data_operator)){
                  $stat_conditions_list[$row_number][] = " JSON_EXTRACT(st.stats, '$.".$index."') ".$bdg_data_operator . $bdg_data_value."  "; 
                  $stat_conditions_field_list[$row_number][] = " JSON_EXTRACT(st.stats, '$.".$index."') ".$index." ";
                  $stat_types_json_list[$row_number][] = " JSON_EXTRACT(st.stats, '$.".$index."') ";
                  $cumulative_stats[$row_number][] = " cumulative_stat ".$bdg_data_operator . $bdg_data_value." "; // To use with inner_where
                  $cumulative_stats_field[$row_number][] = " SUM(".$index.") cumulative_stat "; // To use with cumulative field
                  $avg_min_gm_stats[$row_number][] = " SUM(".$index.")/COUNT(number_of_game) avg_stat "; // To use with avg field
                  $avg_min_gm_conditions[$row_number][] = " avg_stat ".$bdg_data_operator . $bdg_data_value." "; // To use with avg field
                }
                  break;
            }
          }
//           $stat_condition_total_list = implode('AND ', $stat_conditions_total_list[$row_number]); // Total
//           $stat_avg_condition_list = implode('AND ', $stat_avg_conditions_list[$row_number]); // Average
          $stat_condition_list = implode('AND ', $stat_conditions_list[$row_number]); // Individual
          $stat_condition_field_list = implode('AND ', $stat_conditions_field_list[$row_number]); // Individual event stat field
          $stat_type_json_list = implode('AND ', $stat_types_json_list[$row_number]); // Individual event stat type json
          $cumulative_stat = implode('AND ', $cumulative_stats[$row_number]); // Individual event cumulative stat
          $avg_min_gm_stat = implode('AND ', $avg_min_gm_stats[$row_number]); // Individual event avg stat
          $avg_min_gm_condition = implode('AND ', $avg_min_gm_conditions[$row_number]); // Individual event avg stat
          $cumulative_stat_field = implode('AND ', $cumulative_stats_field[$row_number]); // Individual event cumulative stat
//           $stat_field_total_list = implode('', $stat_fields_total_list[$row_number]);
//           $stat_avg_field_list = implode('', $stat_avg_fields_list[$row_number]);
  //         $fields = " pl.id player_id $bdg_field_value_index $stat_avg_field_list $stat_field_total_list";
          $season_year = " ( IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime)) ) season_year ";
          $num_of_season = " COUNT(DISTINCT IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime))) number_of_season ";
          $event_id = " st.event event_id ";
          $num_of_game = " COUNT(st.game) number_of_game ";
          $player_badge_data = " JSON_OBJECT('player_id', pl.id, 'event_id', st.event, 'season_year', ( IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime)) ), 'stat_pts', $stat_type_json_list, 'game_id', st.game) player_badge_data ";
          $fields = " pl.id player_id, $season_year , $num_of_season, $event_id, $num_of_game, $stat_condition_field_list, $player_badge_data ";
          // Set up inner_table where condition
          !empty($stat_condition_list) ? $and = " AND " : $and = "";
          if(!empty($stat_condition_list) && !empty($stat_avg_condition_list)){ $avg_sign = " AND "; }
          elseif(empty($stat_condition_list) && !empty($stat_avg_condition_list)){ $avg_sign = " WHERE "; }
          else{ $avg_sign = ""; }
          $where = " WHERE st.enabled = 1 AND st.game != 0 AND pl.enabled = 1 $and $stat_condition_list AND ev.org in ($website_id) ";
          ( !empty($bdg_type_value_index) || !empty($stat_avg_condition_list) || !empty($stat_condition_total_list) ) ? $is_where = " WHERE " : $is_where = "";
          ( !empty($bdg_type_value_index) && (!empty($stat_avg_condition_list) || !empty($stat_condition_total_list)) ) ? $is_type_and = " AND " : $is_type_and = "";
          ( !empty($stat_avg_condition_list) && (!empty($stat_condition_total_list) || !empty($stat_condition_list)) ) ? $is_avg_and = " AND " : $is_avg_and = "";
  //         ( !empty($stat_condition_total_list) && !empty($stat_condition_list) ) ? $is_indi_and = " AND " : $is_indi_and = "";
          $inner_tb_where[$row_number] = $is_where . $bdg_type_value_index . $is_type_and . $stat_avg_condition_list . $is_avg_and . $stat_condition_total_list;
          $group_by = " GROUP BY pl.id, ev.eventtime, st.event, st.stats, st.game ";
          $tb_bdg_group_by = " GROUP BY inner_tb.player_id, inner_tb.event_id ";
          $joins = " FROM $dbs->players pl INNER JOIN $dbs->stats st ON pl.id = st.player INNER JOIN $dbs->events ev ON st.event = ev.id ";
        switch(json_decode($player_badges_data['event'])->indi_val){
          case 'cumulative_individual_event':
            $open_list[$row_number] = " SELECT player_id, event_id, CONCAT('[', (JSON_OBJECT('player_id', player_id, 'season_year', season_year, 'cumulative_stat', cumulative_stat, 'per_event_game', per_event_game, 'event_id', event_id)), ']') badge_data FROM ( SELECT player_id, GROUP_CONCAT(DISTINCT season_year) season_year, $cumulative_stat_field, COUNT(number_of_game) per_event_game, event_id FROM ( ";
            $fields = " pl.id player_id, $season_year , $num_of_season, $event_id, $num_of_game, $stat_condition_field_list ";
            $where = " WHERE st.enabled = 1 AND st.game != 0 AND pl.enabled = 1 AND ev.org in ($website_id) ";
            !empty($cumulative_stat) ? $cumul_and = " AND " : $cumul_and = "";
            if( empty($bdg_type_value_index) &&  empty($stat_avg_condition_list) && empty($stat_condition_total_list) ){ $cumul_where = " WHERE "; $cumul_and = ""; }else{ $cumul_where = ""; $cumul_and = " AND "; }
            $inner_tb_where[$row_number] = $is_where . $bdg_type_value_index . $is_type_and . $stat_avg_condition_list . $is_avg_and . $stat_condition_total_list . $cumul_where . $cumul_and . $cumulative_stat;
            break;
          case 'cumulative_event_year': // All catagories such as Year, Event, Game and Trophy should be empty
            $open_list[$row_number] = " SELECT CONCAT('[', GROUP_CONCAT(group_ev_bdg_data), ']') badge_data FROM (SELECT player_id, season_year, $cumulative_stat_field, COUNT(number_of_game) per_event_game, GROUP_CONCAT(event_id), GROUP_CONCAT(player_badge_data) group_ev_bdg_data FROM ( ";
            $fields = " pl.id player_id, $season_year , $num_of_season, $event_id, $num_of_game, $stat_condition_field_list, $player_badge_data ";
            !empty($cumulative_stat) ? $cumul_and = " AND " : $cumul_and = "";
            if( empty($bdg_type_value_index) &&  empty($stat_avg_condition_list) && empty($stat_condition_total_list) ){ $cumul_where = " WHERE "; $cumul_and = ""; }else{ $cumul_where = ""; $cumul_and = " AND "; }
            $inner_tb_where[$row_number] = $is_where . $bdg_type_value_index . $is_type_and . $stat_avg_condition_list . $is_avg_and . $stat_condition_total_list . $cumul_where . $cumul_and . $cumulative_stat;
            $where = " WHERE st.enabled = 1 AND st.game != 0 AND pl.enabled = 1 AND ev.org in ($website_id) ";
            break;
          case 'indi_gm_indi_event':
            $open_list[$row_number] = " SELECT player_id, per_event_game, event_id, CONCAT('[', (group_ev_bdg_data), ']') badge_data FROM ( SELECT player_id, COUNT(number_of_game) per_event_game, event_id, GROUP_CONCAT(player_badge_data) group_ev_bdg_data FROM ( ";
            $player_badge_data = " JSON_OBJECT('player_id', pl.id, 'season_year', ( IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime)) )) player_badge_data ";
            $fields = " pl.id player_id, $season_year , $num_of_season, $event_id, $num_of_game, $stat_condition_field_list, $player_badge_data ";
            break;
          case 'avg_cond_indi_event':
            $open_list[$row_number] = " SELECT player_id, avg_stat, per_event_game, event_id, CONCAT('[', (group_ev_bdg_data), ']') badge_data FROM (SELECT player_id, $avg_min_gm_stat, COUNT(number_of_game) per_event_game, event_id, GROUP_CONCAT(player_badge_data) group_ev_bdg_data FROM ( ";
            !empty($avg_min_gm_condition) ? $avg_and = " AND " : $avg_and = "";
            if( empty($bdg_type_value_index) &&  empty($stat_avg_condition_list) && empty($stat_condition_total_list) ){ $avg_where = " WHERE "; $avg_and = ""; }else{ $avg_where = ""; $avg_and = ""; }
            $fields = " pl.id player_id, $season_year , $num_of_season, $event_id, $num_of_game, $stat_condition_field_list, $player_badge_data ";
            $inner_tb_where[$row_number] = $is_where . $bdg_type_value_index . $is_type_and . $stat_avg_condition_list . $is_avg_and . $stat_condition_total_list . $avg_where . $avg_and . $avg_min_gm_condition;
            break;
        }
          $badge_queries = $wpdb->get_results("$open_list[$row_number] SELECT $fields $joins $where $group_by $close_inner_tb_list[$row_number] $tb_bdg_group_by $close_bdg_data_list[$row_number] $inner_tb_where[$row_number]");
//           echo "<pre>"; print_r("$open_list[$row_number] SELECT $fields $joins $where $group_by $close_inner_tb_list[$row_number] $tb_bdg_group_by $close_bdg_data_list[$row_number] $inner_tb_where[$row_number]"); echo "</pre>";
        if(!empty($badge_queries[0]->badge_data)){
          foreach($badge_queries as $index => $badge_query){
            $player_id_list[$row_number][] = database_columns('badges', ['db_tb_name'=>'player_badges', 'id'=>'DEFAULT', 'createdate'=>"'$current_post_date'", 'updatetime'=>"'$current_post_date'", 'enabled'=>"'$enabled'", 'note'=>"'$note'",  'badge_id'=>$badge_id, 'player_id'=>$badge_query->player_id, 'event_id'=>$badge_query->event_id, 'admin_addition'=>$badge_admin_addition, 'badge_data'=>"'$badge_query->badge_data'"]);
            $player_id_lists[$row_number][$index] = '('. implode(', ', $player_id_list[$row_number][$index]) .')';
          }
        }
//         if( !empty($player_id_lists[$row_number]) ){
//          $insert_field_cumulative_list[$row_number][] = implode(', ', $player_id_lists[$row_number]);
//         }
//         foreach($insert_field_cumulative_list[$row_number] as $insert_field_cumulative_lists){
// //             echo "<pre>"; print_r("$query INTO $dbs->player_badges VALUES $insert_field_cumulative_lists ON DUPLICATE KEY UPDATE updatetime=CURRENT_TIMESTAMP, enabled=values(enabled), note=values(note), admin_addition=values(admin_addition), badge_data=values(badge_data)"); echo "</pre>";
//           $wpdb->query("$query INTO $dbs->player_badges VALUES $insert_field_cumulative_lists ON DUPLICATE KEY UPDATE updatetime=CURRENT_TIMESTAMP, enabled=values(enabled), note=values(note), admin_addition=values(admin_addition), badge_data=values(badge_data)");
//         }
      }
      foreach($player_id_lists as $player_id_list){
        $data = $player_id_list;
        $chunk_size = 200;
        $total_rows = count($data);
        for ($i = 0; $i < $total_rows; $i += $chunk_size) {
          $chunk = array_slice($data, $i, $chunk_size);
          $badge_records = [];
          foreach ($chunk as $row) {
            $badge_records[] = $row;
          }
        $insert_field_cumulative_lists = implode(', ', $badge_records);
        $wpdb->query("$query INTO $dbs->player_badges VALUES $insert_field_cumulative_lists ON DUPLICATE KEY UPDATE updatetime=CURRENT_TIMESTAMP, enabled=values(enabled), note=values(note), admin_addition=values(admin_addition), badge_data=values(badge_data)");
        }
      }
        break;
//       Admin Assign Player Badge
//       case 'admin-player-badge':
//         $query = 'INSERT';
//         $data_to_use = array();
//         $current_post_date = wp_date('Y-m-d H:i:s');
//         !empty($fields['badge_id_list']) ? $badge_id_list = $fields['badge_id_list'] : $badge_id_list = '';
//         !empty($fields['badge_note_list']) ? $badge_note_list = $fields['badge_note_list'] : $badge_note_list = '';
//         !empty($fields['badge_name_list']) ? $badge_name_list = $fields['badge_name_list'] : $badge_name_list = '';
//         !empty($fields['badge_url_list']) ? $badge_url_list = $fields['badge_url_list'] : $badge_url_list = '';
//         !empty($fields['badge_player_id']) ? $badge_player_id = $fields['badge_player_id'] : $badge_player_id = '';
//         !empty($fields['badge_enabled_list']) ? $badge_enabled_list = $fields['badge_enabled_list'] : $badge_enabled_list = '';
//         !empty($fields['badge_type_list']) ? $badge_type_list = $fields['badge_type_list'] : $badge_type_list = '';
//         !empty($fields['badge_range_list']) ? $badge_range_list = $fields['badge_range_list'] : $badge_range_list = '';
//         foreach($badge_id_list as $index => $badge_id_data){
//           $badge_note_data = $badge_note_list[$index];
//           $bdg_name_data = $badge_name_list[$index];
//           $bdg_url_data = $badge_url_list[$index];
//           $bdg_type_data = $badge_type_list[$index];
//           $bdg_range_data = $badge_range_list[$index];
//           $bdg_player_id_data = $badge_player_id[$index];
//           $bdg_enabled_data = $badge_enabled_list[$index];
// //           'JSON_OBJECT(\"player_id\", '.$bdg_player_id_data.')'
//           $badges_core_fields = database_columns('badges', ['db_tb_name'=>'badges_core', 'id'=>'DEFAULT', 'createdate'=>"'$current_post_date'", 'updatetime'=>"'$current_post_date'", 'enabled'=>$bdg_enabled_data, 'note'=>"'$badge_note_data'", 'badge_id'=>"'$badge_id_data'", 'badge_type'=>"'$bdg_type_data'", 'badge_range'=>"'$bdg_range_data'", 'badge_name'=>"'$bdg_name_data'", 'badge_url'=>"'$bdg_url_data'", 'admin_addition'=>'1', 'badge_data'=>'JSON_ARRAY(JSON_OBJECT("player_id", '.$bdg_player_id_data.'))']);
//           $badges_core_fields = implode(', ', $badges_core_fields);
//           $data_to_use[] = "( $badges_core_fields )";
//         }
//         $save_player_badge = implode(', ', $data_to_use);
//         $wpdb->query(" $query INTO $dbs->badges_core VALUES $save_player_badge ON DUPLICATE KEY UPDATE updatetime=CURRENT_TIMESTAMP, enabled=values(enabled), note=values(note), badge_name=values(badge_name), badge_type=values(badge_type), badge_range=values(badge_range), badge_url=values(badge_url), badge_data=values(badge_data) ");
// //       }
//       break;
  }
}
function delete_file($direct_path, $file_name, $type = null){
  $to_del_file = $direct_path . $file_name;
  if(is_file($to_del_file)){
    chmod($to_del_file, 0777); // Grant full access to perform deletion
    return unlink($to_del_file); 
  }
}
function g365_remote_img_upload($arg = null, $type = null){
  // Set default media server
  empty($arg['media-folder']) ? $media_folder = 'https://media.grassroots365.com/file-upload/remote-upload-handler.php' : $media_folder = $arg['media-folder'];
  // Set default local media folder where temp files are saved
  empty($arg['lmf']) ? $lmf = get_site_url().'/wp-content/themes/g365-press/assets/photo-additions/uploads/' : $lmf = $arg['lmf']; $proc_type = $arg['proc_type']; $auth_user = $arg['auth_user'];
  // Check file type before upload
  $file_type = str_replace(array('video/', 'image/'), array('', ''), $arg['file_type']);
  if(in_array($file_type, g365_media_file_type('photo'))){ $get_file_type = 'image'; }
  else if(in_array($file_type, g365_media_file_type('video'))){ $get_file_type = 'video'; }
  // Check user account type to save file(s) accordingly
  if($proc_type === 'admin'){ $is_admin = 'admin'; }else{ $is_admin = 'user/'.$auth_user; }
  switch($type){
    case 'g365-media':
      switch($arg['dup_name']){
        case '':
          $img_name = $arg['image_name'];
          break;
        case 'found':
//           $img_name = wp_date('m-j-y-h-i-s-') . basename($arg['image_name']);
          $img_name = wp_date('Y-m-d-H-i-') . basename($arg['image_name']);
          break;
      }
      // Move uploaded file to a temp location
      $file_name = $img_name;
      $upload_dir = $lmf;
      $upload_file = $upload_dir . $arg['image_name'];
      // Prepare remote upload data
      $upload_request = array('file_name' => $file_name, 'file_data' => base64_encode(file_get_contents($upload_file)), 'upload_type'=>$is_admin, 'get_file_type'=>$get_file_type);
      // Execute remote upload
      $post_method = curl_init();
      curl_setopt($post_method, CURLOPT_URL, $media_folder);
      curl_setopt($post_method, CURLOPT_TIMEOUT, 30);
      curl_setopt($post_method, CURLOPT_POST, 1);
      curl_setopt($post_method, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($post_method, CURLOPT_POSTFIELDS, $upload_request);
      $response = curl_exec($post_method);
      curl_close($post_method);
      break;
    case 'file-url':
      $img_name = wp_date('Y-m-d-H-i-') . basename($arg['image_name']);
      return $img_name;
      break;
  }
}
function g365_img_queries($type = null, $arg = null, $offset = null, $limit = null){
  global $wpdb; $dbs = json_decode(dbs());
  if( !empty($arg['file_type']) && ($arg['file_type'] == 'photo') ){ 
    $media_ext = g365_media_file_type('photo-ext-str'); 
  }
  else if( !empty($arg['file_type']) && ($arg['file_type'] == 'video') ){ 
    $media_ext = g365_media_file_type('video-ext-str'); 
  }
  empty($arg['pl_id']) ? $pl_id = '' : $pl_id = $arg['pl_id']; empty($arg['user_id']) ? $user_id = '' : $user_id = $arg['user_id'];
  $conditions = " WHERE admin_addition = 1 AND enabled = 1 "; 
  $order = " ORDER BY updatetime DESC ";
  $limit = "LIMIT ". $limit;
  $offset = "OFFSET " . $offset;
  if(empty($arg['pl_id'])){ $pl_id = ''; $is_pl_filter = ''; }else{ $pl_id = $arg['pl_id']; $is_pl_filter = " AND JSON_CONTAINS(player_id, '$pl_id', '$.pl_id') = 1 "; }
  switch($type){
    case 'admin-photo-upload':
      $conditions = $conditions . " AND JSON_LENGTH(JSON_EXTRACT(img.player_id, '$.pl_id')) = 0 ";
      return $wpdb->get_results("SELECT * FROM $dbs->images img $conditions $order $limit $offset");
      break;
    case 'admin-assigned-photo':
      $conditions = $conditions . "AND JSON_LENGTH(JSON_EXTRACT(img.player_id, '$.pl_id')) > 0 ";
      return $wpdb->get_results("SELECT * FROM $dbs->images img $conditions $is_pl_filter $order $limit $offset");
      break;
    case 'user-photo-upload':
      if(empty($arg['pl_id'])){
        $conditions = " WHERE admin_addition = 0 AND enabled = 1 AND rejected = 1 ";
        if($arg['is_approved'] === 'approved'){ $conditions = " WHERE admin_addition = 0 AND enabled = 1 AND rejected = 0 "; }
      }else{
        $conditions = " WHERE admin_addition = 0 AND enabled = 1 $is_pl_filter ";
      }
      return $wpdb->get_results("SELECT * FROM $dbs->images img $conditions $order $limit $offset");
      break;
    case 'player-photo-view':
      empty($arg['pl_id']) ? $pl_id = '' : $pl_id = $arg['pl_id']; $media_ext = g365_media_file_type('photo-ext-str');
      $conditions = " WHERE enabled = 1 AND private = 0 AND rejected = 0 AND JSON_CONTAINS(player_id, '$pl_id', '$.pl_id') = 1 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      return $wpdb->get_results("SELECT * FROM $dbs->images img $conditions $order $limit $offset");
      break;
    case 'user-acct-ph-view':
      switch($arg['user_upload_status']){
        case 'approved':
          $conditions = " WHERE enabled = 1 AND rejected = 0 AND user_id = $user_id ";
          break;
        case 'pending':
          $conditions = " WHERE enabled = 1 AND rejected = 1 AND user_id = $user_id ";
          break;
        case 'admin-user-view': // Allow admin uploaded photos to be displayed and public/private by user at their front end
          $conditions = " WHERE enabled = 1 AND rejected = 0 AND admin_addition = 1 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
          break;
      }
      return $wpdb->get_results("SELECT * FROM $dbs->images img $conditions $order");
      break;
    case 'profile-video':
      $media_ext = g365_media_file_type('video-ext-str');
      $conditions = " WHERE enabled = 1 AND private = 0 AND rejected = 0 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      $order = " ORDER BY admin_addition ASC ";
      $results = $wpdb->get_results("SELECT * FROM $dbs->images img $conditions $order");
      $profile_videos = array();
      foreach($results as $result){
        if($result->admin_addition == 1){ $post_auth = 'administrator'; $display = 'style="z-index:0"'; }else{ $post_auth = 'user'; $display = 'style="z-index:2"'; }
        $profile_videos[] = g365_media_view_rendering('video-only', ['auth_user'=>$post_auth, 'user_id'=>$result->user_id, 'file_name'=>$result->img_name, 'file_id'=>$result->id, 'display'=>$display])['profile_video'];
      }
      return $profile_videos;
      break;
    case 'mobile-app-profile-video':
      $media_ext = g365_media_file_type('video-ext-str');
      $conditions_public = " WHERE enabled = 1 AND private = 0 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      $conditions_private = " WHERE enabled = 1 AND private = 1 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      $conditions_pending = " WHERE enabled = 1 AND rejected = 1 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      $order = " ORDER BY admin_addition ASC ";
      $results_public = $wpdb->get_results("SELECT * FROM $dbs->images img $conditions_public $order");
      $results_private = $wpdb->get_results("SELECT * FROM $dbs->images img $conditions_private $order");
      $results_pending = $wpdb->get_results("SELECT * FROM $dbs->images img $conditions_pending $order");
      $profile_videos = array();
      foreach($results_public as $result){
        if($result->admin_addition == 1){ $post_auth = 'administrator'; $display = 'style="z-index:0"'; }else{ $post_auth = 'user'; $display = 'style="z-index:2"'; }
        $profile_videos['public'][$result->id] = g365_media_view_rendering('mobile-app-video-only', ['auth_user'=>$post_auth, 'user_id'=>$result->user_id, 'file_name'=>$result->img_name, 'file_id'=>$result->id, 'display'=>$display]);
      }
      foreach($results_private as $result){
        if($result->admin_addition == 1){ $post_auth = 'administrator'; $display = 'style="z-index:0"'; }else{ $post_auth = 'user'; $display = 'style="z-index:2"'; }
        $profile_videos['private'][$result->id] = g365_media_view_rendering('mobile-app-video-only', ['auth_user'=>$post_auth, 'user_id'=>$result->user_id, 'file_name'=>$result->img_name, 'file_id'=>$result->id, 'display'=>$display]);
      }
      foreach($results_pending as $result){
        if($result->admin_addition == 1){ $post_auth = 'administrator'; $display = 'style="z-index:0"'; }else{ $post_auth = 'user'; $display = 'style="z-index:2"'; }
        $profile_videos['pending'][$result->id] = g365_media_view_rendering('mobile-app-video-only', ['auth_user'=>$post_auth, 'user_id'=>$result->user_id, 'file_name'=>$result->img_name, 'file_id'=>$result->id, 'display'=>$display]);
      }
      return $profile_videos;
      break;
    case 'mobile-app-player-photo-view':
      $group_photo = [];
      empty($arg['pl_id']) ? $pl_id = '' : $pl_id = $arg['pl_id']; $media_ext = g365_media_file_type('photo-ext-str');
      $conditions_pending = " WHERE enabled = 1 AND rejected = 1 AND JSON_CONTAINS(player_id, '$pl_id', '$.pl_id') = 1 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      $conditions_approved = " WHERE enabled = 1 AND rejected = 0 AND JSON_CONTAINS(player_id, '$pl_id', '$.pl_id') = 1 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($media_ext) ) ";
      $pending_photo = $wpdb->get_results("SELECT img.id, img.private, img.user_id, img.verified, img.img_name, img.admin_addition FROM $dbs->images img $conditions_pending $order");
      $approved_photo = $wpdb->get_results("SELECT img.id, img.private, img.user_id, img.verified, img.img_name, img.admin_addition FROM $dbs->images img $conditions_approved $order");
      return ['pending'=>$pending_photo, 'approved'=>$approved_photo];
      break;
//     case 'player-badge':
//       !empty($arg['pl_id']) ? $pl_id = $arg['pl_id'] : $pl_id = '\"\"';
//       $where = " WHERE JSON_CONTAINS(badge_data, '{\"pl_id\": [$pl_id]}') AND enabled = 1 ";
//       $order_by = " ORDER BY admin_addition DESC, updatetime DESC ";
//       $results = $wpdb->get_results("SELECT badge_id, badge_name, badge_url FROM $dbs->badges_core $where $order_by ");
//       return $results;
//       break;
  }
}

function g365_img_count($type = null, $arg = null){
  global $wpdb; $dbs = json_decode(dbs());
  empty($arg['pl_id']) ? $pl_id = '' : $pl_id = $arg['pl_id']; empty($arg['user_id']) ? $user_id = '' : $user_id = $arg['user_id'];
  $conditions = " WHERE admin_addition = 1 AND enabled = 1 "; $order = " ORDER BY updatetime DESC ";
  switch($type){
    case 'admin-photo-upload':
      $conditions = $conditions . " AND JSON_LENGTH(JSON_EXTRACT(img.player_id, '$.pl_id')) = 0 ";
      return $wpdb->get_results("SELECT COUNT (*) AS count FROM $dbs->images img $conditions $order");
      break;
    case 'admin-assigned-photo':
      if(empty($arg['pl_id'])){ $pl_id = ''; $is_pl_filter = ''; }else{ $pl_id = $arg['pl_id']; $is_pl_filter = " AND JSON_CONTAINS(player_id, '$pl_id', '$.pl_id') = 1 "; }
      $conditions = $conditions . "AND JSON_LENGTH(JSON_EXTRACT(img.player_id, '$.pl_id')) > 0 ";
      return $wpdb->get_results("SELECT COUNT (*) AS count FROM $dbs->images img $conditions $is_pl_filter $order");
      break;
    case 'user-photo-upload':
//       $conditions = " WHERE admin_addition = 0 AND enabled = 1 ";
      if(empty($arg['pl_id'])){
        $conditions = " WHERE admin_addition = 0 AND enabled = 1 AND rejected = 1 ";
        if($arg['is_approved'] === 'approved'){ $conditions = " WHERE admin_addition = 0 AND enabled = 1 AND rejected = 0 "; }
      }else{
        $conditions = " WHERE admin_addition = 0 AND enabled = 1 $is_pl_filter ";
      }
      return $wpdb->get_results("SELECT COUNT (*) AS count FROM $dbs->images img $conditions $order");
      break;
    case 'player-photo-view':
      empty($arg['pl_id']) ? $pl_id = '' : $pl_id = $arg['pl_id'];
      $conditions = " WHERE enabled = 1 AND private = 0 AND rejected = 0 AND JSON_CONTAINS(player_id, '$pl_id', '$.pl_id') = 1 ";
      return $wpdb->get_results("SELECT COUNT(*) AS count FROM $dbs->images img $conditions $order");
      break;
    case 'user-acct-ph-view':
      switch($arg['user_upload_status']){
        case 'approved':
          $conditions = " WHERE enabled = 1 AND rejected = 0 AND user_id = $user_id ";
          break;
        case 'pending':
          $conditions = " WHERE enabled = 1 AND rejected = 1 AND user_id = $user_id ";
          break;
        case 'admin-user-view': // Allow admin uploaded photos to be displayed and public/private by user at their front end
          $conditions = " WHERE enabled = 1 AND rejected = 0 AND admin_addition = 1 AND ( player_id LIKE '%[$pl_id]%' OR player_id LIKE '%, $pl_id,%' OR player_id LIKE '%[$pl_id,%' OR player_id LIKE '%, $pl_id]%' ) ";
          break;
      }
      return $wpdb->get_results("SELECT COUNT (*) FROM $dbs->images img $conditions $order");
      break;
  }
}

function g365_media_dir($type = null, $arg = null){
  $auth_user = $arg['auth_user']; $user_id = $arg['user_id'];
//   Check what type of photo folder for picture rendering
  if($auth_user === 'administrator'){ $get_folder = 'admin/'; }else{ $get_folder = "user/".$user_id."/"; }
  switch($type){
    case 'admin-photo-media-g365':
      return "https://media.grassroots365.com/player-photo-upload/".$get_folder;
       break;
    case 'user-media-g365':
       return "https://media.grassroots365.com/player-photo-upload/".$get_folder;
       break;
    case 'admin-video-media-g365':
       return "https://media.grassroots365.com/player-video-upload/".$get_folder;
       break;
    case 'user-video-media-g365':
       return "https://media.grassroots365.com/player-video-upload/".$get_folder;
       break;
   }
}
function g365_custom_js($type){
  switch($type){
    case 'filepond':
      $script = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js?loc=2'></script>";
      $script = "<script src='https://code.jquery.com/ui/1.13.0/jquery-ui.min.js?loc=1'></script>";
      $script .= "<script type='text/javascript' src='../../wp-content/plugins/g365-data-manager/js/".$type.".js'></script>";
      return $script;
      break;
    case 'badges':
      $script = "<script type='text/javascript' src='../../../wp-content/plugins/g365-data-manager/js/".$type.".js'></script>";
      return $script;
      break;
//     case 'badge-cookies':
//       $script = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>";
//       $script .= "<script type='text/javascript' src='../../../wp-content/plugins/g365-data-manager/js/".$type.".js'></script>";
//       return $script;
//       break;
    case 'player-badges':
      $script = "<script type='text/javascript' src='../../../wp-content/plugins/g365-data-manager/js/".$type.".js'></script>";
      return $script;
      break;
  }
}
function ph_toggle($arg = null, $type = null){
  if($arg['is_checked'] == 0){ $is_checked = 'checked'; }else{ $is_checked = ''; }
  switch($type){
    case 'admin-pending':
      $toggle_approval = '
        <div class="ph_pending_toggle">
          <div class="ph_align">  
            <div class="ph_checkbox">
              <label>
                <input data-img-id="'.$arg['img_id'].'" type="checkbox" class="ph_toggle" '.$is_checked.' id="'.$arg['toggle_id'].'" name="ph_toggle" value="Y" required="">
                <span class="ph_element">
                  <span class="srone">
                    <span class="ph_element_two"></span>
                  </span>
                </span>
              </label>
            </div>   
          </div>
        </div>
      ';
      break;
    case 'user-approved':
      $toggle_approval = '
        <div class="ph_pending_toggle">
          <div class="ph_align">
            <div class="user_ph_checkbox">
              <label>
                <input data-img-id="'.$arg['img_id'].'" data-user-admin-toggle="'.$arg['user_admin_toggle'].'" type="checkbox" class="user_ph_toggle" '.$is_checked.' id="'.$arg['toggle_id'].'" name="ph_toggle" value="Y" required="">
                <span class="ph_element">
                  <span class="srone">
                    <span class="ph_element_two"></span>
                  </span>
                </span>
              </label>
            </div>   
          </div>
        </div>
      ';
      break;
  }
  return $toggle_approval;
}
function g365_admin_rm_btn($arg = null){
  $button = '
    <div onclick="remove_assign_pl(this)" class="small-6 medium-6 large-1 rm_fav small-margin-bottom large-margin-right" data-toggle="rm_'.$arg['pl_id'].'" data-rm-id="'.$arg['pl_id'].'" >
      <a class="rm_btn" href="#" role="button">
        <span>remove</span>
        <div class="rm_icon">
          <i class="rm_x fa fa-remove">X</i>
          <i class="rm_x fa fa-check">X</i>
        </div>
      </a>
    </div>
  ';
  return $button;
}
function all_custom_queries($type = null, $arg = null){
  global $wpdb; $dbs = json_decode(dbs()); $ev_id = $arg['ev_id']; $lv_of_play = $arg['lv_of_play'];
  switch($type){
    case 'act-ros':
      $result = $wpdb->get_results("SELECT ev.name AS event_name, org.name AS org_name, ros.division AS division, ros.level AS grade, ros.players AS players FROM $dbs->rosters ros INNER JOIN $dbs->orgs org ON ros.org = org.id INNER JOIN $dbs->events ev ON ros.event = ev.id WHERE ros.event = $ev_id AND ros.level in ($lv_of_play) ORDER BY ros.level DESC");
      return $result;
      break;
    case 'get-badges':
      $result = $wpdb->get_results("SELECT * FROM $dbs->awards awd WHERE awd.enabled = 1 AND badge_type = 1");
      return $result;
      break;
  }
}
function g365_photo_upload_process_type($type = null, $arg = null){
  $img_name = $arg['img_name']; $auth_user_id = $arg['auth_user_id']; $lmf = $arg['lmf'];
  if($type !== 'admin'){ $media_link = g365_media_dir('user-media-g365', ['auth_user'=>$auth_user_id, 'user_id'=>$auth_user_id]) . $img_name; }else{ $media_link = g365_media_dir('admin-photo-media-g365', ['user_id'=>$auth_user_id]) . $img_name; }
  $is_file_exist = g365_check_remote_file_info(['file_url'=>$media_link])['file_exist'];
//   if(@getimagesize($media_link)){ // error: duplicate file name found.
  if($is_file_exist = 1){ // error: duplicate file name found.
  // Handle g365 db images uploader
    g365_db_handler('photo-upload', $type, ['img_name'=>$img_name, 'auth_user'=>$auth_user_id, 'dup_name'=>'found', 'claimed_pl'=>$arg['claimed_pl'], 'file_type'=>$arg['file_type']]);
    g365_remote_img_upload(['image_name'=>$img_name, 'lmf'=>$lmf, 'proc_type'=>$type, 'auth_user'=>$auth_user_id, 'dup_name'=>'found', 'file_type'=>$arg['file_type']], 'g365-media');
    delete_file(dirname(__FILE__, 3).'/assets/photo-additions/uploads/', $img_name);
//     delete_file(dirname(__FILE__, 3).'/themes/g365-press/assets/photo-additions/uploads/', $img_name);
  }else{
    g365_db_handler('photo-upload', $type, ['img_name'=>$img_name, 'auth_user'=>$auth_user_id, 'claimed_pl'=>$arg['claimed_pl'], 'file_type'=>$arg['file_type']]);
    g365_remote_img_upload(['image_name'=>$img_name, 'lmf'=>$lmf, 'proc_type'=>$type, 'auth_user'=>$auth_user_id, 'file_type'=>$arg['file_type']], 'g365-media');
  }
}
function g365_ls_claim_player($arg = null, $type = null){
  $auth_user = $arg['auth_user']; $form_type = $arg['form_type'];
  $rg_presets = ' data-g365_init_pre="'.$form_type.'_preset:::user_ac::' . ((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . $auth_user . '"';
  $photo_presets = ((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . $auth_user;
  switch($type){
    case 'player-registration':
      $ls_claim_pl = '
        <div class="cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"Player Registration","type":"'.$form_type.'","items": {}}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "'.g365_make_admin_key().'"};</script>
          <p>&nbsp;</p>
          <div><div id="g365_form_options_anchor" data-g365_type="'.$form_type.'" '.$rg_presets.'></div></div>
        </div>
      ';
      return $ls_claim_pl;
      break;
    case 'photo-player-claim':
//         <input type="text" class="search-hero g365_livesearch_input expanded" data-g365_action="select_data" data-g365_type="user_photo_claim" data-ls_user_ac="'.$photo_presets.'" placeholder="Enter Player Name" autocomplete="off" autofocus>
      $ls_claim_pl = '
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="" data-g365_form_template="form_template_min" data-g365_type="user_photo_claim" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" data-ls_user_ac="'.$photo_presets.'" data-g365_form_template_new="form_template_min" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="event_id_pm" data-g365_select_click="pl_cert_add_button" placeholder="Full Name" autocomplete="off" autofocus>
      ';
      return $ls_claim_pl;
      break;
  }
}
function allow_upload_size($type, $requirements = null){
  $allow_size_val = 10; // Need default value to convert
  if(!empty($requirements['in_bytes']) && $requirements['in_bytes'] === true){ $allow_size = ($allow_size_val*1000000); }else{ $allow_size = $allow_size_val.'MB'; }
  switch($type){
    case 'upload-photo-size':
      return $allow_size;
      break;
    case 'upload-video-size':
      $allow_size_val = 50;
      if(!empty($requirements['in_bytes']) && $requirements['in_bytes'] === true){ $allow_size = ($allow_size_val*1000000); }else{ $allow_size = $allow_size_val.'MB'; }
      return $allow_size;
      break;
  }
}
function g365_media_upload_limit($type, $user_id){ // Set number of approved and pending photos upload
  global $wpdb; $dbs = json_decode(dbs()); $allowed_approved_photo = 50; $allowed_pending_photo = 20; $allowed_approved_video = 5; $allowed_pending_video = 5;
  $img_ext = g365_media_file_type('photo-ext-str');
  $vid_ext = g365_media_file_type('video-ext-str');
  $photo_approved_number = $wpdb->get_results("SELECT id FROM $dbs->images WHERE enabled = 1 AND user_id = $user_id AND admin_addition = 0 AND rejected = 0 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($img_ext) ) ");
  $photo_pending_number = $wpdb->get_results("SELECT id FROM $dbs->images WHERE enabled = 1 AND user_id = $user_id AND admin_addition = 0 AND rejected = 1 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($img_ext) ) ");
  $video_approved_number = $wpdb->get_results("SELECT id FROM $dbs->images WHERE enabled = 1 AND user_id = $user_id AND admin_addition = 0 AND rejected = 0 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($vid_ext) ) ");
  $video_pending_number = $wpdb->get_results("SELECT id FROM $dbs->images WHERE enabled = 1 AND user_id = $user_id AND admin_addition = 0 AND rejected = 1 AND ( REPLACE(JSON_EXTRACT(highlight, '$.file_type'), '\"', '') IN ($vid_ext) ) ");
  $photo_approved_number = json_decode(json_encode($photo_approved_number), true);
  $photo_pending_number = json_decode(json_encode($photo_pending_number), true);
  $video_approved_number = json_decode(json_encode($video_approved_number), true);
  $video_pending_number = json_decode(json_encode($video_pending_number), true);
  $is_ph_approved_limit_reach = count($photo_approved_number);
  $is_ph_pending_limit_reach = count($photo_pending_number);
  $is_vid_approved_limit_reach = count($video_approved_number);
  $is_vid_pending_limit_reach = count($video_pending_number);
  switch($type){
    case 'photo':
      if($is_ph_approved_limit_reach >= $allowed_approved_photo && $is_ph_pending_limit_reach < $allowed_pending_photo){
        return ['message'=>g365_message(['photo_approved_max'=>$allowed_approved_photo])['approved_max_limit'], 'locked'=>true];
      }
      if($is_ph_approved_limit_reach < $allowed_approved_photo && $is_ph_pending_limit_reach >= $allowed_pending_photo){
        return ['message'=>g365_message(['photo_pending_max'=>$allowed_pending_photo])['pending_max_limit'], 'locked'=>true];
      }
      else if($is_ph_approved_limit_reach >= $allowed_approved_photo && $is_ph_pending_limit_reach >= $allowed_pending_photo){
        return ['message'=>g365_message(['photo_approved_max'=>$allowed_approved_photo, 'photo_pending_max'=>$allowed_pending_photo])['photo_max_limit'], 'locked'=>true];
      }
      break;
    case 'video':
      if($is_vid_approved_limit_reach >= $allowed_approved_video && $is_vid_pending_limit_reach < $allowed_pending_video){
        return ['message'=>g365_message(['vid_approved_max'=>$allowed_approved_video])['vid_approved_max_limit'], 'locked'=>true];
      }
      if($is_vid_approved_limit_reach < $allowed_approved_video && $is_vid_pending_limit_reach >= $allowed_pending_video){
        return ['message'=>g365_message(['vid_pending_max'=>$allowed_pending_video])['vid_pending_max_limit'], 'locked'=>true];
      }
      else if($is_vid_approved_limit_reach >= $allowed_approved_video && $is_vid_pending_limit_reach >= $allowed_pending_video){
        return ['message'=>g365_message(['vid_approved_max'=>$allowed_approved_video, 'vid_pending_max'=>$allowed_pending_video])['video_max_limit'], 'locked'=>true];
      }
      break;
  }
}
function g365_custom_ninja_forms($type){
  switch($type){
    case 'report-photo':
      if(strpos(get_site_url(), 'dev.')){ return Ninja_Forms()->display(4); }else{ return Ninja_Forms()->display(4); }
      break;
  }
}
function g365_media_file_type($type){
  switch($type){
    case 'photo':
      return ['jpg', 'jpeg', 'png', 'svg', 'gif'];
      break;  
    case 'video':
      return ['mp4', 'mov', 'quicktime'];
      break;
    case 'allowed-image-ext':
      return "image/jpg, image/jpeg, image/png, image/svg, image/gif";
      break;
    case 'allowed-video-ext':
      return "video/mp4, video/mov, video/quicktime";
      break;
    case 'photo-ext-str':
      return "'jpg', 'jpeg', 'png', 'svg', 'gif'";
      break;  
    case 'video-ext-str':
      return "'mp4', 'mov', 'quicktime'";
      break;
  }
}
function g365_file_upload($type){
  switch($type){
    case 'admin-photo':
      $result = '
        <div class="photo-upload-box">
          <input type="file" id="imagesFilepond" class="filepond" name="filepond[]" multiple data-allow-reorder="true" data-max-file-size="'.allow_upload_size('upload-photo-size').'" accepted-file-types="'.g365_media_file_type('allowed-image-ext').'">
          <div class="link_view_full_list finish-upload" style="display: none;">
            <button style="display: none;" onclick="window.location.reload(true);" class="finish-upload">Finish upload photo(s)</button>
          </div>
        </div>
      ';
      break;
    case 'admin-video':
      $result = '
        <div class="photo-upload-box">
          <input type="file" id="imagesFilepond" class="filepond" name="filepond[]" multiple data-allow-reorder="true" data-max-file-size="'.allow_upload_size('upload-video-size').'" accepted-file-types="'.g365_media_file_type('allowed-video-ext').'">
          <div class="link_view_full_list finish-upload" style="display: none;">
            <button style="display: none;" onclick="window.location.reload(true);" class="finish-upload">Finish upload videos(s)</button>
          </div>
        </div>
      ';
      break;
    case 'video':
      $result = '
        <div class="photo-upload-box">
          <input type="file" id="imagesFilepond" class="filepond" name="filepond[]" multiple data-allow-reorder="true" data-max-file-size="'.allow_upload_size('upload-video-size').'" accepted-file-types="'.g365_media_file_type('allowed-video-ext').'">
          <div class="link_view_full_list finish-upload" style="display: none;">
            <section class="pretty-buttons small-padding-top">
              <div class="grid-x pretty-container">
                <div class="small-12 large-12 small-padding-bottom">
                  <a onclick="window.location.reload(true);" class="pretty-btn pretty-btn-1">Finish upload video(s)
                    <svg><rect x="0" y="0" fill="none" width="100%" height="100%"></rect></svg>
                  </a>
                </div>
              </div>
            </section>
          </div>
        </div>
      ';
      break;
    case 'photo':
      $result = '
        <div class="photo-upload-box">
          <input type="file" id="imagesFilepond" class="filepond" name="filepond[]" multiple data-allow-reorder="true" data-max-file-size="'.allow_upload_size('upload-photo-size').'" accepted-file-types="'.g365_media_file_type('allowed-image-ext').'">
          <div class="link_view_full_list finish-upload" style="display: none;">
            <section class="pretty-buttons small-padding-top">
              <div class="grid-x pretty-container">
                <div class="small-12 large-12 small-padding-bottom">
                  <a onclick="window.location.reload(true);" class="pretty-btn pretty-btn-1">Finish upload photo(s)
                    <svg><rect x="0" y="0" fill="none" width="100%" height="100%"></rect></svg>
                  </a>
                </div>
              </div>
            </section>
          </div>
        </div>
      ';
      break;
  }
  return $result;
}
function g365_media_view_rendering($type = null, $arg = null){
  $given_file_ext = $arg['given_file_ext']; $given_file_ext = json_decode($given_file_ext); $given_file_ext = str_replace(array('image/', 'video/'), array('', ''),  $given_file_ext->file_type);
  $is_admin = $arg['is_admin'];
  $auth_user = $arg['auth_user']; $user_id = $arg['user_id'];
  $file_name = $arg['file_name']; $file_id = $arg['file_id'];
  $file_private = $arg['file_private']; 
  $display = $arg['display'];
  empty($arg['media_type']) ? $check_media_type = '' : $check_media_type = $arg['media_type'];
  $pop_up_display = '<img class="photo__img--attach">';
  $file_type = 'image';
  switch($type){
    case 'view-media-file':
      if($is_admin == 0){ // Check if this is an admin upload. '0' not an admin; '1' is an admin
        if(in_array($given_file_ext, g365_media_file_type('photo'))){ // View file is image
          $file_link = g365_media_dir('user-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
          $file_display = '<img loading="lazy" class="photo__img photo-frames-image" src="'.$file_link . $file_name.'" alt="'.$file_name.'" data-id="'.$file_id.'"/>';
        }
        else if(in_array($given_file_ext, g365_media_file_type('video'))){ // View file is video
          $file_link = g365_media_dir('user-video-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
          $file_display = '<video style="z-index:-1;" class="photo__img photo-frames-image" controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>';
          $pop_up_display = '<video controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>';
          $file_type = 'video';
        }
      }
      if($is_admin == 1){
        if(in_array($given_file_ext, g365_media_file_type('photo'))){
          $file_link = g365_media_dir('admin-photo-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
          $file_display = '<img loading="lazy" class="photo__img photo-frames-image" src="'.$file_link . $file_name.'" alt="'.$file_name.'" data-id="'.$file_id.'"/>';
        }
        else if(in_array($given_file_ext, g365_media_file_type('video'))){
          $file_link = g365_media_dir('admin-video-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
          $file_display = '<video style="z-index:-1;" class="photo__img photo-frames-image" controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>';
          $pop_up_display = '<video controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>';
          $file_type = 'video';
        }
      }
      return ['main_view'=>$file_display, 'model_view_class'=>$pop_up_display, 'file_type'=>$file_type];
      break;
    case 'photo-only':
      if(in_array($given_file_ext, g365_media_file_type('photo'))){ // View file is image
        $file_link = g365_media_dir('user-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
        $user_toggle = ph_toggle(['toggle_id'=>'tog_'.$file_id, 'is_checked'=>$file_private, 'img_id'=>$file_id], 'user-approved');
        $file_display = 
          '<div class="photo__pending--img-container">
            <div class="photo__img-container photo-frames-container" id="photoAttachPlayer-'.$file_id.'" data-img-id="'.$file_id.'" >
  <img loading="lazy" class="photo__img photo-frames-image" src="'.$file_link . $file_name.'" alt="'.$file_name.'" data-id="'.$file_id.'"/>
            </div>
            '.$user_toggle.'
          </div>';
        $media_only = '<div st class="photo__img-container photo-frames-container"><img loading="lazy" class="photo__img photo-frames-image" src="'.$file_link . $file_name.'" alt="'.$file_name.'" data-id="'.$file_id.'"></div>';
      }
      break;
    case 'video-only':
      $file_link = g365_media_dir('user-video-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
      $user_toggle = ph_toggle(['toggle_id'=>'tog_'.$file_id, 'is_checked'=>$file_private, 'img_id'=>$file_id], 'user-approved');
      if(in_array($given_file_ext, g365_media_file_type('video'))){ // View file is video
        $file_display = 
          '<div class="photo__pending--img-container">
            <div class="photo__img-container photo-frames-container" id="photoAttachPlayer-'.$file_id.'" data-img-id="'.$file_id.'" >
              <video style="z-index:-1;" class="photo__img photo-frames-image" controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>
            </div>
            '.$user_toggle.'
          </div>';
        $media_only = '<div class="photo__img-container" ><video class="photo__img photo-frames-image" controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video></div>';
        $pop_up_display = '<video controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>';
      }
      $profile_video = ['video'=>'<video controls="controls" height="200" width="300" name="'.$file_name.'" id="default-profile-video-'.$file_id.'" '.$display.'><source src="'.$file_link . $file_name.'"></video>', 'file_id'=>$file_id, 'video_name'=>$file_name, 'video_src'=>$file_link.$file_name];
      break;
    case 'user-admin-view': // Admin assigned media to player. Player able to view assigned media on their account.
      if($is_admin == 1){
        $file_link = g365_media_dir('admin-'.$check_media_type.'-media-g365', ['auth_user'=>'administrator']);
        if(in_array($given_file_ext, g365_media_file_type('photo'))){
          $file_display = '<div class="photo__img-container" ><img loading="lazy" class="photo__img photo-frames-image" src="'.$file_link . $file_name.'" alt="'.$file_name.'" data-id="'.$file_id.'"/><div>';
        }
        else if(in_array($given_file_ext, g365_media_file_type('video'))){
          $file_display = '<div class="photo__img-container" ><video class="photo__img photo-frames-image" controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video></div>';
          $pop_up_display = '<video controls="controls" height="200" width="300" name="Video Name"><source src="'.$file_link . $file_name.'"></video>';
        }
      }
      break;
    case 'mobile-app-video-only':
      $file_link = g365_media_dir('user-video-media-g365', ['auth_user'=>$auth_user, 'user_id'=>$user_id]);
      return $file_link.$file_name;
      break;
  }
  return ['main_view'=>$file_display, 'model_view_class'=>$pop_up_display, 'file_type'=>$file_type, 'media_only'=>$media_only, 'profile_video'=>$profile_video];
}
function g365_woocom_current_page($type, $arg = null){
  global $wp; $current_url = home_url(add_query_arg(array(), $wp->request));
  switch($type){
    case 'woocom-url':
      return $current_url;
      break;
    case 'page-type':
      $photo_page = '/account/player-photos'; 
      $video_page = '/account/player-videos'; 
      if(strpos($current_url, $photo_page) !== false){
        return ['status'=>'image', 'label'=>'Photo', 'alias'=>'photo'];
      }
      else if(strpos($current_url, $video_page) !== false){
        return ['status'=>'video', 'label'=>'Video', 'alias'=>'video'];
      }
      break;
  }
}
function g365_check_remote_file_info($arg = null){
  if(empty($arg['file_url'])) return 'Need file url to process';
  $url = $arg['file_url'];
  $check_file = curl_init($url);
  curl_setopt($check_file, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($check_file, CURLOPT_HEADER, TRUE);
  curl_setopt($check_file, CURLOPT_NOBODY, TRUE);
  $data = curl_exec($check_file);
  $file_size = curl_getinfo($check_file, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
  $http_response_code = curl_getinfo($check_file, CURLINFO_HTTP_CODE);
  curl_close($check_file);
  return ['file_exist' => (int) $http_response_code == 200, 'file_size' => (int) $file_size];
}
function g365_remote_api($type = null, $arg = null){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($arg['target_url']) ? $target_url = $arg['target_url'] : $target_url = '';
  $target_org = g365_return_keys('keys_by_domain')[$target_url]['org_id'];
  // Add Jr. SS3B to the stage calendar
  if($target_org === 3){
    $target_org = ' 3 AND ev.org = 8523 ';
  }
  switch($type){
    case 'ebc-watchlist':
      //pull vars if we have them
      // Group id: '148' for All EBC Players Watchlist
      $watchlist_set = ( empty($arg['wt_id']) ? 148 : $arg['wt_id'] );
      $watchlist_date = ( empty($arg['wt_tp']) ? null : explode('_', $arg['wt_tp']) );
      if( is_array($watchlist_date) && !empty($watchlist_date[0]) ) {
        $watchlist_date = array(
          'min-limit' => $watchlist_date[0],
          'max-limit' => (empty($watchlist_date[1]) ? $watchlist_date[0] : $watchlist_date[1])
        );
      }
      $watchlist_data = g365_build_watchlist($watchlist_set, $watchlist_date);
      // Get player img
      $ebc_watch_list_player_imgs = array(); 
      foreach($watchlist_data->records as $dex => $group_data){
        foreach($group_data->records as $dex => $subgroup_data){
          foreach($subgroup_data->rankings as $subdex => $player_id){
//             $validate_player_img = g365_player_img_dir($watchlist_data->player_records[$player_id]->player_url, $watchlist_data->player_records[$player_id]->event_nickname, $watchlist_data->player_records[$player_id]->id);
            $validate_player_img = spp_player_img_dir($watchlist_data->player_records[$player_id]->player_url, $watchlist_data->player_records[$player_id]->event_nickname, $watchlist_data->player_records[$player_id]->id);
            $ebc_watch_list_player_imgs[$player_id] = $validate_player_img;
          }
        }
      }
      // EBC Spotlight: Stat Leaderboard
      $event_id = $wpdb->get_results("SELECT ev.id event_id FROM $dbs->stats st INNER JOIN $dbs->events ev ON st.event = ev.id WHERE st.game != 0 AND ev.org = 2 ORDER BY ev.eventtime DESC LIMIT 1");
      return ['start_ads'=>g365_start_ads($arg['post_id']), 'watchlist_date'=>$watchlist_date, 'watchlist_date_min_limit'=>$watchlist_date['min-limit'], 'watchlist_data'=>$watchlist_data, 'watchlist_set'=>$watchlist_set, 'ebc_watch_list_player_imgs'=>$ebc_watch_list_player_imgs, 'stat_leaderboard'=>['stat_leaderboard'=>g365_stat_leader($event_id = $event_id[0]->event_id, $stat = null, $input_year = null, $limit_result = 5, $player_level = null, $type = 4, $pl_division = null, ['is_player_team_spotlight'=>true])]];
      break;
    case 'hhh-team-ranking':
      $rank_set = ( empty($arg['rk_id']) ? 47 : $arg['rk_id'] );
      $rank_date = ( empty($arg['rk_tp']) ? null : explode('_', $arg['rk_tp']) );
      if( is_array($rank_date) && !empty($rank_date[0]) ) {
        $rank_date = array(
          'min-limit' => $rank_date[0],
          'max-limit' => (empty($rank_date[1]) ? $rank_date[0] : $rank_date[1])
        );
      }
      $ranking_data = g365_build_ranking(184, $rank_date);
      // Get girls' team info
      $team_info = $wpdb->get_results("SELECT id, level, name FROM $dbs->teams WHERE level > 30 and level < 74" );
      $team_data = array();
      $team_info = json_decode(json_encode($team_info), true);
      foreach($team_info as $team_info_list){
        $full_team_name = g365_level_key($team_info_list['level']) . ((empty($team_info_list['name'])) ? '' : ' ' . $team_info_list['name']);
        $full_team_ext = strtolower(str_replace(array(' ', '(',')'), array('-', '', ''), $full_team_name)); 
        $team_custom_url = g365_custom_url( 'cp_team_url', array($team_info_list['id'], g365_date_format($ranking_data->records[0]->start_datetime, 7)) ); 
        $team_data[$team_info_list['id']] = ['url'=>($full_team_ext . $team_custom_url), 'name'=>$full_team_name];
      }
      $default_profile_img = 'https://hypeherhoopscircuit.com/wp-content/uploads/2022/11/H-2c.png';
      return ['start_ads'=>g365_start_ads($arg['post_id']), 'rank_date'=>$rank_date, 'rank_date_min_limit'=>$rank_date['min-limit'], 'ranking_data'=>$ranking_data, 'rank_set'=>$rank_set, 'default_profile_img'=>$default_profile_img, 'team_info'=>$team_data];
      break;
    case 'hhh-watchlist':
      //pull vars if we have them
      // Group id: '194' for All HHH Players Watchlist
      $watchlist_set = ( empty($arg['wt_id']) ? 194 : $arg['wt_id'] );
      $watchlist_date = ( empty($arg['wt_tp']) ? null : explode('_', $arg['wt_tp']) );
      if( is_array($watchlist_date) && !empty($watchlist_date[0]) ) {
        $watchlist_date = array(
          'min-limit' => $watchlist_date[0],
          'max-limit' => (empty($watchlist_date[1]) ? $watchlist_date[0] : $watchlist_date[1])
        );
      }
      $watchlist_data = g365_build_watchlist($watchlist_set, $watchlist_date);
      // Get player img
      $hhh_watch_list_player_imgs = array(); 
      foreach($watchlist_data->records as $dex => $group_data){
        foreach($group_data->records as $dex => $subgroup_data){
          foreach($subgroup_data->rankings as $subdex => $player_id){
            $validate_player_img = g365_player_img_dir($watchlist_data->player_records[$player_id]->player_url, $watchlist_data->player_records[$player_id]->event_nickname, $watchlist_data->player_records[$player_id]->id);
            $hhh_watch_list_player_imgs[$player_id] = $validate_player_img;
          }
        }
      }
      return ['start_ads'=>g365_start_ads($arg['post_id']), 'watchlist_date'=>$watchlist_date, 'watchlist_date_min_limit'=>$watchlist_date['min-limit'], 'watchlist_data'=>$watchlist_data, 'watchlist_set'=>$watchlist_set, 'hhh_watch_list_player_imgs'=>$hhh_watch_list_player_imgs];
      break;
    case 'calendar-api':// Remote API for Calendar
      $today_date = date("Y-m-d H:i:s");
      $calendar_data = $wpdb->get_results(" SELECT * FROM $dbs->events ev WHERE ev.org = $target_org AND ev.enabled = 1 AND (ev.eventtime >= '$today_date') ORDER BY YEAR(ev.eventtime) ASC, MONTH(ev.eventtime) ASC, DAY(ev.eventtime) ASC ");
      $event_calendar = array();
      foreach($calendar_data as $index => $calendar_info){
        $event_calendar[] = $calendar_info;
        $event_calendar[$index]->dates = g365_build_dates($calendar_info->dates, 2);
        $event_calendar[$index]->locations = str_replace('|', ' | ', $calendar_info->locations);
      }
      return ['event_calendar'=>$event_calendar, 'nv_message'=>g365_message()['event_calendar']];
      break;
    case 'ebc-event-profile':
      !empty($arg['event_id']) ? $event_id = $arg['event_id'] : $event_id = '';
      $event_data = g365_get_event_data( $event_id );
      return $event_data;
      break;
    case 'all-tournament-profile-get-groups':
      !empty($arg['event_id']) ? $event_id = $arg['event_id'] : $event_id = '';
      $event_data = g365_get_groups_data( 89, 3 , array('truncate'=>true) );
      return $event_data;
      break;
    case 'all-tournament-profile-award-build':
      !empty($arg['award_id']) ? $award_id = $arg['award_id'] : $award_id = '';
      $award_data = ( !empty($award_id) ) ? g365_build_awards( $award_id, array('truncate'=>true) ) : null;
      return $award_data;      
      break;
    case 'spp-g365-event-img':
      !empty($arg['event_id']) ? $event_id = $arg['event_id'] : $event_id = '';
      !empty($arg['player_id']) ?  $player_id = $arg['player_id'] :  $player_id = '';
      if(!empty($event_id)){
        return $wpdb->get_results(" SELECT player player_id, profile_img FROM $dbs->stats WHERE event = $event_id AND game = 0 AND enabled = 1 AND profile_img IS NOT NULL ");
      }
      break;
    case 'spp-g365-profile-img':
      $get_player_profile_img = $wpdb->get_results(" SELECT id player_id, profile_img FROM $dbs->players WHERE enabled = 1 AND profile_img IS NOT NULL ");
      $player_img_lists = array();
      foreach($get_player_profile_img as $player_img_list){
        $player_img_lists[$player_img_list->player_id] = $player_img_list;
      }
      return $player_img_lists;
      break;
  }
}
function g365_get_media_thumbnail($type, $arg = null){
  $file_id = $arg['file_id']; $default_video_id = $arg['default_video_id']; $video_name = $arg['video_name']; $video_src = $arg['video_src'];
  switch($type){
    case 'video-thumbnail':
      $thumbnail = '<div onclick="upload_video(this)" class="video-thumb cell small-6 medium-4 large-3" data-video-id="'.$file_id.'" data-video-name="'.$video_name.'" data-video-src="'.$video_src.'"><img class="uploaded-video" src="https://dev.grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif" style="width: 104px; height: 70px; margin-bottom: 10px;"></div>';
      return ['thumbnail'=>$thumbnail];
      break;
  }
}
/*--------------------------------------
Woocommerce - Allow Guest Checkout on Certain products
----------------------------------------*/
// Display Guest Checkout Field
add_action('woocommerce_product_options_general_product_data', 'g365_custom_field_for_guest_checkout');
function g365_custom_field_for_guest_checkout(){
  global $woocommerce, $post;
  echo '<div class="options_group">';
  // Checkbox
  woocommerce_wp_checkbox( 
    array( 
    'id'            => '_allow_guest_checkout', 
    'wrapper_class' => 'show_if_simple', 
    'label'         => __('Checkout', 'woocommerce'), 
    'description'   => __('Allow Guest Checkout', 'woocommerce') 
    )
  );
  echo '</div>';
}
// Save Guest Checkout Field
add_action('woocommerce_process_product_meta', 'g365_save_custom_field_for_guest_checkout');
function g365_save_custom_field_for_guest_checkout($post_id){
	$woocommerce_checkbox = isset($_POST['_allow_guest_checkout']) ? 'yes' : 'no';
	update_post_meta( $post_id, '_allow_guest_checkout', $woocommerce_checkbox );
}
// Enable Guest Checkout on Certain products
add_filter('pre_option_woocommerce_enable_guest_checkout', 'g365_enable_guest_checkout_on_certain_product');
function g365_enable_guest_checkout_on_certain_product($value){
  if(WC()->cart){
    $cart = WC()->cart->get_cart();
    foreach( $cart as $item ){
      if(get_post_meta( $item['product_id'], '_allow_guest_checkout', true ) == 'yes'){
        $value = "yes";
      }else{
        $value = "no";
        break;
      }
    }
  }
  return $value;
}
// Passport subscription renewal
function g365_subscription_pp_renewal_fn($order_id){
  global $wpdb; $dbs = json_decode(dbs());
  $order_data_meta = get_post_meta($order_id, '_order_data_add', true);
  $order = wc_get_order($order_id);
  foreach ($order->get_items() as $item){
    $product = wc_get_product($item->get_product_id());
    $prod_sku = $product->get_sku(); $prod_type = substr($prod_sku, 0, strpos($prod_sku, '-')); $prod_ev_id = substr($prod_sku, strpos($prod_sku, '-') + 1);
    if( in_array($product->get_sku(), array('GPP-001', 'GPP-002')) ){
      $order_data_meta = get_post_meta( $order_id, '_order_data', true );
      if( !empty($order_data_meta) ){
        $order_data_meta = explode( '|', $order_data_meta );
        $order_data_meta_proc = array();
        foreach( $order_data_meta as $dex => $data ){
          $data = explode( ',', $data );
          $type = array_shift($data);
          if( count($data) > 0 ){
            $order_data_meta_proc[$type] = array('ids' => $data, 'result' => array());
            switch( $type ){
              case 'passport':
                $order_data_add_meta = get_post_meta( $order_id, '_order_data_add', true );
                //this is the parse string that should come out of a successfull pp purchase: passport,200651:::seasons::2021
                if( !empty($order_data_add_meta) ){
                  //explode the types, in this case we just care about 'passport' for additional processing
                  $order_data_add_meta = explode( '|', $order_data_add_meta );
                  //loop through the saved checkout data
                  foreach( $order_data_add_meta as $dex => $data ) {
                    //break down the var we get
                    $data = explode( ',', $data );
                    //pull the key off the front
                    $type = array_shift($data);
                    //if we have any data, continue to process
                    if( count($data) === 1 ){
                      //break down the var again
                      $sub_data = explode( ':::', $data[0] );
                      //pull the id off the string
                      $sub_id = array_shift($sub_data);
                      //break the last two vars that we need to write the query
                      $sub_data = explode( '::', $sub_data[0] );
                      if( count($sub_data) === 2 ){
                        //should be either 'seasons' or 'events'
                        $sub_type = $sub_data[0];
                        //the year or event that is being unlocked
                        $sub_target = $sub_data[1];
                        //reference to the time that the unlocking happened
                        $today = wp_date("Y-m-d H:i:s");
                        //WARNING :: pull the line item to write the cost, come up with a lock for this, right now multiple items with over write each other, which is only a problem, with multiple pp purchased and discounted separately, which might not be possible.
                        $amount = $item->get_total();
                        //try to write the payment confirmation timestamp to any records that we find in the order details
                        $previous_pp_season = $wpdb->query("SELECT id FROM $dbs->stats WHERE id = $sub_id AND JSON_EXTRACT(stats, '$.$sub_type') IS NOT NULL");
                        $sub_target = g365_date_format($sub_target, 13);
                        // echo "<pre>"; print_r($order_data_add_meta); echo "</pre>";
                        // If previous passport purchase existed
                        if(!empty($previous_pp_season)){
                          $wpdb->query("UPDATE $dbs->stats SET stats = JSON_MERGE_PATCH(stats, JSON_OBJECT('$sub_type', JSON_OBJECT('$sub_target', JSON_OBJECT('paid', '$today', 'amount', $amount, 'order_id', $order_id)))) WHERE id = $sub_id");
                        }else{
                          $wpdb->query("UPDATE $dbs->stats SET stats = JSON_OBJECT('$sub_type', JSON_OBJECT('$sub_target', JSON_OBJECT('paid', '$today', 'amount', $amount, 'order_id', $order_id))) WHERE id = $sub_id");
                        }
                      }
                    }
                  }
                }
                else{
//                   $prod_renew_year = $order->date_created->format('Y');
                  $prod_renew_year = g365_date_format('', 10);
                  $order_data_add = get_post_meta( $order_id, '_order_data', true );
                  //explode the types, in this case we just care about 'passport' for additional processing
                  $order_data_add = explode( '|', $order_data_add );
                  $today = wp_date("Y-m-d H:i:s");
                  //loop through the saved checkout data
                  foreach( $order_data_add as $dex => $data ){
                    //break down the var we get
                    $data = explode( ',', $data );
                    //pull the key off the front
                    $type = array_shift($data);
                    //if we have any data, continue to process
                    if( count($data) > 0 ){
                      foreach($data as $saved_id){
                        $wpdb->query("UPDATE $dbs->stats SET stats = JSON_MERGE_PATCH(stats, JSON_OBJECT('seasons', JSON_OBJECT('$prod_renew_year', JSON_OBJECT('paid', '$today', 'order_id', $order_id)))) WHERE id = $saved_id");
                      }
                    }
                  }
                }
                break;
            }
          }
        }
      }
    }
  }
}
function g365_update_passport_subscription_pricing($product_name, $set_price, $args = null){
  global $wpdb; $dbs = json_decode(dbs()); // Old: 2021-22 Season Passport, Passport Annual Pass 2021-22, Current: Passport Annual Pass
  $start_date = $args['start_date'];
  $end_date = $args['end_date'];
  // Use this for "2021-22 Season Passport and Passport Annual Pass 2021-22" only
  $list_of_order_item_ids = array();
  $order_item_ids = $wpdb->get_results("SELECT order_item_id FROM wp_54ab678738_woocommerce_order_items WHERE order_item_name = '$product_name'");
  foreach($order_item_ids as $order_item_id){ $list_of_order_item_ids[] = $order_item_id->order_item_id; }
  $list_of_order_item_ids = implode(', ',$list_of_order_item_ids);
//   Update base on order quantity
  $get_order_ids = $wpdb->get_results("SELECT * FROM wp_54ab678738_woocommerce_order_itemmeta WHERE (order_item_id in ($list_of_order_item_ids) AND meta_key = '_qty')");
  // End
  // Use this for "Passport Annual Pass" only
//   $get_order_ids = $wpdb->get_results("SELECT oi.order_item_id, om.meta_value as order_qty, o.post_date
//     FROM wp_54ab678738_woocommerce_order_items oi
//     JOIN wp_54ab678738_woocommerce_order_itemmeta om ON oi.order_item_id = om.order_item_id
//     JOIN wp_54ab678738_posts o ON oi.order_id = o.ID
//     WHERE
//     om.meta_key = '_qty'
//     AND o.post_date BETWEEN '$start_date' AND '$end_date' ");
  // End
  foreach($get_order_ids as $order_detail){
    // Use this for "Passport Annual Pass" only
    $order_qty = $order_detail->meta_value;
    $order_id = $order_detail->order_item_id;
    $new_price = $order_qty * $set_price;
    // End
    // Use this for "2021-22 Season Passport and Passport Annual Pass 2021-22" only
//      $order_qty = $order_detail->order_qty;
//     $order_id = $order_detail->order_item_id;
//     $new_price = $order_qty * $set_price;
    // End
    $wpdb->query("UPDATE wp_54ab678738_woocommerce_order_itemmeta SET meta_value = $new_price WHERE (meta_key = '_line_subtotal' and order_item_id = $order_id) OR (meta_key = '_line_total' AND order_item_id = $order_id)");
  }
}
// function recalculate_all_subscription_total($args){
//   $subscription_meta = array();
//   $pp_sub_ids = array();
//   $start_date = $args['start_date'];
//   $end_date = $args['end_date'];

//   $subscriptions = wcs_get_subscriptions(['subscriptions_per_page' => -1]);

//   foreach($subscriptions as $index => $subscription){
//     $date_created = $subscription->get_date_created();
//     if( $date_created && ($date_created->format('Y-m-d') >= $start_date && $date_created->format('Y-m-d') <= $end_date) ){
//       $subscription_meta[] = $index;
//     }
//   }
//   foreach($subscription_meta as $subscription_new_meta){
//     $order = new \WC_Order($subscription_new_meta);
//     $new_total = $order->calculate_totals();
//   }
//   return true;
// }
function recalculate_all_subscription_total($args){
  global $wpdb;
  $subscription_meta = array();
  $pp_sub_ids = array();
  $start_date = $args['start_date'];
  $end_date = $args['end_date'];

  $query = $wpdb->prepare(
    "SELECT ID FROM wp_54ab678738_posts WHERE post_type = 'shop_subscription' AND post_date BETWEEN %s AND %s",
    $start_date,
    $end_date
  );

  $subscription_ids = $wpdb->get_col($query);

  foreach ($subscription_ids as $subscription_id) {
    $subscription_meta[] = $subscription_id;
  }

  foreach ($subscription_meta as $subscription_new_meta) {
    $order = new \WC_Order($subscription_new_meta);
    $new_total = $order->calculate_totals();
  }

  return true;
}

function check_monthly_sub($monthly_data){
  if(!empty($monthly_data)){
    foreach($monthly_data as $monthly_subscription_valid_data){
      // Calculate the subscription end date (one month from the subscription start date)
      $subscription_start_date = strtotime($monthly_subscription_valid_data);
      $monthly_subscription_end_date = date("Y-m-d H:i:s", strtotime('+1 month', $subscription_start_date));

      // Get the current date and time
      $current_date = wp_date('Y-m-d H:i:s');

      // Check if the current date is within the subscription period
      if($current_date >= $monthly_subscription_valid_data && $current_date <= $monthly_subscription_end_date){
        return 'true';
        break; // Stop the loop if one valid subscription is found
      }
    }
  }else{ return 'false'; }
}
function g365_passport_validation($type, $arg = null){
  // Saved unlocked year is different by an actual season year. Need to add 1 to saved unlocked year to match season year
  if(!empty($arg['selected_year'])){ $selected_year = $arg['selected_year']; }else{ $selected_year = ''; }
  if(!empty($arg['pp_data'])){ $player_subscription_data = $arg['pp_data']; }else{ $player_subscription_data = ''; }
  switch($type){
    case 'subscription-validation': // Unlocked passport view until subscription period is over.
      $yearly_subscription_period_list = array(); $subscription_period_list = array();
      $result = 'false'; // Default result
      if(!empty($player_subscription_data['yearly_subscription'])){
        $yearly_subscription_period_list = [];
        $subscription_period_list = [];

        // Yearly subscription expiration date
        foreach($player_subscription_data['yearly_subscription_purchased_date'] as $subscription_valid_data){
          $yearly_subscription_period_list[] = date("Y-m-d H:i:s", strtotime('+1 year', strtotime($subscription_valid_data)));
        }

        // Check if selected year is in the exceptions or the subscription list
        if(in_array($selected_year, year_exception_list('in-array')) || in_array($selected_year, $player_subscription_data['yearly_subscription'])){
          return 'true';
        }

        // Check if the current date is within any yearly subscription period
        if(!empty($yearly_subscription_period_list)){
          foreach($yearly_subscription_period_list as $yearly_subscription_period_data){
            if(wp_date('Y-m-d H:i:s') < $yearly_subscription_period_data){
              $subscription_period_list[] = 'true';
            }
          }
          if(in_array('true', $subscription_period_list)){
            return 'true';
          }else{
//             return 'false';
            return check_monthly_sub($player_subscription_data['monthly_subscription_data']);
          }
        }else{
          return $result;
        }
      }
      else{      
          if(!empty($player_subscription_data['monthly_subscription_data'])) {
           return check_monthly_sub($player_subscription_data['monthly_subscription_data']);
          }
      }
      return $result;
      break;
    case 'restricted-passport-photo':
      // Check photos' date to determine what season year they are in
      empty($arg['photo_upload_date']) ? $photo_upload_date = '': $photo_upload_date = $arg['photo_upload_date'];
      empty($arg['photo_order']) ? $photo_order = '': $photo_order = $arg['photo_order'];
      if(!empty($photo_upload_date)){
        $formate_date = date_parse_from_format('Y-m-d', $photo_upload_date); 
        $year = $formate_date['year'];
        $month = $formate_date['month'];
        if( $month > '08' ){ $photo_year = $year + 1; } // If month > 08 push it to new season year
        else{ $photo_year = $year; }
      }
      return '<img class="photo__img" src="'.get_site_url().'/wp-content/uploads/event-profiles/g365_profile_placeholder.gif" alt="default-placeholder-img" data-order="'.$photo_order.'"><a target="_blank" href="'.get_site_url().'/product/passport-annual/"><p class="unlocked_ph_ms">'.g365_message(['photo_year'=>$photo_year])['photo_unlocked'].'</p></a>';
      break;
    case 'tounament-manager':
      // Simplify passport data
      $yearly_subscription_purchased_date = array();
      !empty($arg['tounament_pl_pp_data']) ? $tounament_pl_pp_data = $arg['tounament_pl_pp_data'] : $tounament_pl_pp_data = '';
      $tounament_pl_pp_data = json_decode($tounament_pl_pp_data, true);
      foreach($tounament_pl_pp_data['seasons'] as $pl_pp_data){
        if(!empty($pl_pp_data['paid'])){ $pl_paid_date = $pl_pp_data['paid']; }
        $yearly_subscription_purchased_date[] = $pl_paid_date; 
      }
      if(!empty($tounament_pl_pp_data['seasons'])){
        $pl_pp_years = array_keys($tounament_pl_pp_data['seasons']);
      }
      return ['yearly_subscription'=>$pl_pp_years, 'yearly_subscription_purchased_date'=>$yearly_subscription_purchased_date];
      break;
  }
}
function g365_pp_checkout_form($args){
  $annual_form_init      = $args['annual_form_template_init'];
  $annual_form_template  = $args['annual_form_template'];
  $monthly_form_init     = $args['monthly_form_template_init'];
  $monthly_form_template = $args['monthly_form_template'];
  global $woocommerce;
  $check_cart_items = $woocommerce->cart->get_cart();
  foreach($check_cart_items as $index => $values){
    $product =  wc_get_product($values['data']->get_id());
    $passport_pass_type = strtolower(str_replace(' ', '-', $product->name));
    switch($passport_pass_type){
      case 'passport-annual-pass':
        return ['form_init'=>$annual_form_init, 'form_template'=>$annual_form_template];
        break;
      case 'passport-monthly-pass':
        return ['form_init'=>$monthly_form_init, 'form_template'=>$monthly_form_template];
        break;
    }
  }
//   foreach($check_cart_items as $index => $values){
//     $product =  wc_get_product($values['data']->get_id());
//     $passport_pass_type = $product->attributes['g365-passport-pass'];
//     $passport_pass_type = str_replace(' ', '-', $passport_pass_type);
//     switch($passport_pass_type){
//       case 'Annual-Pass':
//         return ['form_init'=>$annual_form_init, 'form_template'=>$annual_form_template];
//         break;
//       case 'Monthly-Pass':
//         return ['form_init'=>$monthly_form_init, 'form_template'=>$monthly_form_template];
//         break;
//     }
//   }
}
function g365_badges($type, $args = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  /* For stat type selection: It can only be selected one type of stat per operation */
  !empty($args['operator_badge_type']) ? $operator_badge_type = $args['operator_badge_type'] : $operator_badge_type = '';
  !empty($args['operator_value']['stat']) ? $stat_value = $args['operator_value']['stat'] : $stat_value = '';
  !empty($args['operator_symbol']['stat']) ? $stat_symbol = $args['operator_symbol']['stat'] : $operator_symbol = '';
  !empty($args['operator_value']['game']) ? $operator_value = $args['operator_value']['game'] - 1 : $operator_value = '';
  !empty($args['operator_symbol']['game']) ? $operator_symbol = $args['operator_symbol']['game'] : $operator_symbol = '';
  !empty($args['stat_type']['stat']) ? $stat_type = $args['stat_type']['stat'] : $stat_type = '';// "ast", "blk", "pts", "rbs", "stl", "three_pt"
  // Event
  !empty($args['is_event']) ? $event_id = $args['is_event'] : $event_id = '';
  if(!empty($event_id)){
    $event_join = " INNER JOIN $dbs->events ev ON st.event = ev.id ";
    $event_condition = " AND ev.id = $event_id ";
  }else{ $event_join = ''; $event_condition = ''; }
  // Season
  !empty($args['is_season']) ? $season_year = g365_date_format($args['is_season'], 1) : $season_year = '';
  if(!empty($season_year)){ $season_condition = " AND st.updatetime BETWEEN $season_year "; }else{ $season_condition = ''; }
  if(!empty($args['indi_game_value'])){ $indi_game_value = $args['indi_game_value']; $is_indi_condition = " AND JSON_EXTRACT(st.stats, '$.$stat_type') $stat_symbol $indi_game_value "; }else{ $indi_game_value = ''; }
  // Website
  !empty($args['site_id']) ? $site_id = $args['site_id'] : $site_id = '';
  if(!empty($site_id)){ $site_join = " INNER JOIN $dbs->events ev ON st.event = ev.id "; $site_condition = " AND ev.org = $site_id "; }else{ $site_join = ''; $site_condition = ''; }
  $where = " WHERE st.enabled = 1 AND st.game != 0 AND pl.enabled = 1 $event_condition $season_condition $indi_game_value $site_condition ";
  if(!empty($stat_type)){
    $result = $wpdb->get_results("SELECT pl.id player_id, JSON_EXTRACT(st.stats, '$.$stat_type') $stat_type FROM $dbs->players pl INNER JOIN $dbs->stats st ON pl.id = st.player $event_join $site_join $where ORDER BY CAST(player_id AS unsigned) ASC, CAST(JSON_EXTRACT(st.stats, '$.$stat_type') AS unsigned) DESC");
  }
  $result = json_decode(json_encode($result), true);
  switch($type){
    case 'operator-badge-type': // Check and process badges
      $group_array = array(); $pl_id_list = array(); $avg_results = array();
      foreach($result as $val){ $group_array[$val['player_id']][] = $val; }
      foreach($group_array as $index => $get_array){
        // Check if number of game is set. If it's set then trim only the top games to process
        $array_to_sum = array_slice($get_array, 0, $operator_value);
        $is_pl_id_found = array_sum(array_column($array_to_sum, $stat_type)) . $stat_symbol . $stat_value;
        $is_pl_id_found = eval('return '.$is_pl_id_found.';');
        switch($operator_badge_type){
          case 'limited-cumulative-total':
            if($is_pl_id_found == 1){ $pl_id_list[] = $index; }
            break;
          case 'individual-total':
            $pl_id_list[] = $index;
            break;
          case 'individual-average':
            foreach ($result as $val){
              if(!isset($avg_results[$val['player_id']])){
                $avg_results[$val['player_id']] = $val;
                $avg_results[$val['player_id']]['count'] = 1;
              }else{
                $avg_results[$val['player_id']][$stat_type] += $val[$stat_type];
                $avg_results[$val['player_id']]['count'] ++;
              }
            }
            foreach($avg_results as $dex => $avg_result){
              $avg_results[$dex]['avg'] = $avg_result[$stat_type]/$avg_result['count'];
              $is_pl_id_found = $avg_results[$dex]['avg'] . $stat_symbol . $stat_value;
              $is_pl_id_found = eval('return '.$is_pl_id_found.';');
              if($is_pl_id_found == 1){ $pl_id_list[] = $dex; }
            }
            return $pl_id_list;
            break;
        }
      }
      return $pl_id_list;
      break;
    case 'saved-badge':
      $where = " WHERE bdg.enabled = 1 ";
      $saved_badge_types = $wpdb->get_results("SELECT * FROM $dbs->badges bdg $where");
      return $saved_badge_types;
      break;
    case 'player-badge':
      $badge_options = array();
      $get_badges_logo = $wpdb->get_results("SELECT * FROM $dbs->awards awd WHERE awd.enabled = 1 AND badge_type = 1");
      $player_badges_data = json_decode(json_encode($get_badges_logo), true);
      foreach($player_badges_data as $player_badge_data){ $badge_options[] = '<option bdg_url="'.$player_badge_data['logo_img'].'" id="'.$player_badge_data['id'].'" >'.$player_badge_data['name'].'</option>'; }
      $badge_options = implode('', $badge_options);
      $player_badges = $wpdb->get_results("SELECT * FROM $dbs->badges bdg WHERE bdg.enabled = 1");
      return ['player_badges'=>$player_badges, 'get_badges'=>$badge_options];
      break;
    case 'admin-player-badge':
      !empty($args['pl_id']) ? $pl_id = $args['pl_id'] : $pl_id = '\"\"';
//       $player_badges = $wpdb->get_results("SELECT * FROM $dbs->badges_core WHERE enabled = 1 AND JSON_CONTAINS(JSON_EXTRACT(badge_data, '$[*].player_id'),'$pl_id','$') ORDER BY admin_addition ASC, CAST(SUBSTRING_INDEX(badge_id,'-',-1) as UNSIGNED) ASC ");
      $player_badges = $wpdb->get_results("SELECT pl_bdg.createdate, pl_bdg.updatetime, pl_bdg.enabled, pl_bdg.note, pl_bdg.badge_id, bdg.badge_type, bdg.badge_range, bdg.badge_name, bdg.badge_url, bdg.admin_addition, GROUP_CONCAT(pl_bdg.badge_data) badge_data FROM $dbs->player_badges pl_bdg INNER JOIN $dbs->badges bdg ON pl_bdg.badge_id = bdg.id WHERE player_id = $pl_id GROUP BY pl_bdg.createdate, pl_bdg.updatetime, pl_bdg.enabled, pl_bdg.note, pl_bdg.badge_id, bdg.badge_type, bdg.badge_range, bdg.badge_name, bdg.badge_url, bdg.admin_addition");
      $player_yearly_badges = $wpdb->get_results("SELECT pl_bdg.badge_id, ( IF(MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime)) ) event_year, bdg.badge_type, bdg.badge_range, bdg.badge_name FROM $dbs->player_badges pl_bdg  INNER JOIN $dbs->badges bdg ON pl_bdg.badge_id = bdg.id INNER JOIN $dbs->events ev ON ev.id = pl_bdg.event_id WHERE player_id = $pl_id AND pl_bdg.enabled = 1 GROUP BY badge_id, event_year");
      return ['player_badges'=>$player_badges, 'player_yearly_badges'=>$player_yearly_badges];
      break;
    case 'player-badge-views': // Player badge render views
      !empty($args['pl_id']) ? $pl_id = $args['pl_id'] : $pl_id = '';
      if(!empty($pl_id)){
        $player_badge_data = array(); $player_badge_stat = array(); $player_badge_years = array(); $player_yearly_badges = array();
//         $badge_data = g365_badges('admin-player-badge', ['pl_id'=>$pl_id]);
        $badge_data = g365_badges('admin-player-badge', ['pl_id'=>$pl_id])['player_badges'];
        $badge_yearly_data = g365_badges('admin-player-badge', ['pl_id'=>$pl_id])['player_yearly_badges'];
        foreach($badge_yearly_data as $badge_yearly){ $player_yearly_badges[$badge_yearly->event_year][] = $badge_yearly; }
        foreach(badge_catagory('badge-player-catagory') as $badge_player_catagory){
          foreach($badge_data as $player_bdg_data){
//             echo "<pre>"; print_r($badge_data); echo "</pre>";
            $pl_bdg_data = json_decode($player_bdg_data->badge_data, true);
            if(!empty($pl_bdg_data)){
              $pl_bdg_data = array_filter($pl_bdg_data, function($var){ !empty($args['pl_id']) ? $pl_id = $args['pl_id'] : $pl_id = ''; return ($var['player_id'] = ".$pl_id."); });
            }
            $get_badge_range = substr($player_bdg_data->badge_range, 0, strpos($player_bdg_data->badge_range, '_'));
            $get_badge_range_name = badge_catagory('badge-range-catagory')[$get_badge_range];
            // Check to see what badge catagory to use with player badge profile front end
            $get_bdg_type = substr($player_bdg_data->badge_range, 0, strpos($player_bdg_data->badge_range, '_'));
            // Hardcoded In An Event badge_range ids: 1 to 18
            if(!empty($get_bdg_type > 0 && $get_bdg_type < 19)){ $badge_player_catagories = 'In An Event'; }
            else{ $badge_player_catagories = 'Year'; }
//             if(!empty($pl_bdg_data)){
//               foreach($pl_bdg_data as $pl_bdg_stat){
//                 $player_badge_stat[] = $pl_bdg_stat['stat_pts'];
//                 $player_badge_years[$pl_bdg_stat['season_year']][$get_badge_range_name][$player_bdg_data->badge_range] = $player_bdg_data->badge_name;
//               }
//             }
//             ksort($player_badge_years);
            // Get yearly badges
            if(!empty($player_yearly_badges)){
              foreach($player_yearly_badges as $index => $player_yearly_bdgs){
                foreach($player_yearly_bdgs as $player_yearly_bdg){
                  // Filter out all stat types except total
                  $yearly_catagories = badge_catagory('badge-yearly-catagory');
                  $formated_badge_range = substr($player_yearly_bdg->badge_range, 0, strpos($player_yearly_bdg->badge_range, '_'));
                  // If giving stat type match with badge range value, keep those values
                  if($yearly_catagories[$get_badge_range_name] == $formated_badge_range){
                    $check = $player_yearly_bdg->badge_range;
                  }
                  $player_badge_years[$index][$get_badge_range_name][$check] = $player_yearly_bdg->badge_name;
                }
              }
            }
            krsort($player_badge_years);
//             $player_badge_data[$badge_player_catagories][$player_bdg_data->badge_type][$get_badge_range_name][$player_bdg_data->badge_range] = $player_bdg_data->badge_name;
            $player_badge_data[$badge_player_catagories][$player_bdg_data->badge_type][$get_badge_range_name][$player_bdg_data->badge_range] = $player_bdg_data->badge_name;
            $player_badge_data['Year'] = $player_badge_years;
          }
        }
        return ['player_badge_data'=>$player_badge_data, 'badge_data'=>$badge_data];
      }
      break;
  }
}
function badge_catagory($type, $args = null){
  switch($type){
    case 'badge-catagory': // All badge form field for names and values
      $bdg_catagory = ['season', 'event', 'game', 'trophy', 'pts', 'reb', 'ast', 'stl', 'blk', 'three_pt', '3191', '1', '2', '3', '7164', '7165'];
      break;
    case 'badge-catagory-validation': // All fields for table update. It is used for data validation before write it to db
      $bdg_catagory = ['season', 'event', 'game', 'trophy', 'pts', 'reb', 'ast', 'stl', 'blk', 'three_pt'];
      break;
    case 'badge-stat-catagory': // All stat column for writing records to
      $bdg_catagory = ['pts'=>'point', 'rbs'=>'rebound', 'ast'=>'assist', 'stl'=>'steal', 'blk'=>'block', 'three_pt'=>'three_pt'];
      break;
    case 'badge-type': // All badge type column for writing records to
      $bdg_catagory = ['season', 'event', 'game'];
      break;
    case 'badge-range-catagory': // Player badge stats front end with custom badge range
      $bdg_catagory = ['1'=>'points', '2'=>'rebounds', '3'=>'assists', '4'=>'steals', '5'=>'blocks', '6'=>'three_pt', '7'=>'points', '8'=>'rebounds', '9'=>'assists', '10'=>'steals', '11'=>'blocks', '12'=>'three_pt', '13'=>'points', '14'=>'rebounds', '15'=>'assists', '16'=>'steals', '17'=>'blocks', '18'=>'three_pt'];
      break;
    case 'badge-player-catagory': // Player profile badge front end catagory
      $bdg_catagory = ['in_an_event'=>'In An Event','year'=>'Year','career'=>'Career'];
      break;
    case 'badge-front-end-stat-catagory': // Player profile badge front end stat catagory
      global $wpdb; $dbs = json_decode(dbs());
      !empty($args['badge_range']) ? $badge_key = $args['badge_range'] : $badge_key = '';
//       $badge_data = $wpdb->get_results("SELECT JSON_OBJECT('stat', JSON_ARRAY(GROUP_CONCAT(DISTINCT(SUBSTRING_INDEX(badge_range, '_', 1))), COUNT(type))) badge_result FROM $dbs->awards WHERE enabled = 1 AND badge_type = 1 GROUP BY type");
      $badge_data = $wpdb->get_results("SELECT GROUP_CONCAT( JSON_OBJECT('type', badge_type, 'count', count_bdg_type) ) bdg_data FROM (
SELECT GROUP_CONCAT(DISTINCT(SUBSTRING_INDEX(badge_range, '_', 1))) badge_type, count(type) count_bdg_type, IF(type>20 && type<27, 'total', IF(type>26 && type<33, 'average', IF(type>32 && type<39, 'in a game', ''))) stat_bdg_type FROM $dbs->awards WHERE enabled = 1 AND badge_type = 1 GROUP BY type) inner_tb GROUP BY inner_tb.stat_bdg_type");
      $badge_url =  $wpdb->get_results("SELECT logo_img FROM $dbs->awards WHERE enabled = 1 AND badge_type = 1 AND badge_range = '$badge_key'");
      $badge_range =  $wpdb->get_results("SELECT JSON_OBJECT(GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(badge_range, '_', 1)), (GROUP_CONCAT(badge_range))) bdg_stat_type FROM $dbs->awards WHERE enabled = 1 AND badge_type = 1 GROUP BY type");
      $bdg_catagory = ['badge_awards'=>$badge_data, 'badge_url'=>$badge_url, 'badge_range'=>$badge_range];
      break;
    case 'player-badge-catagory': // All stat column for writing records to
      $bdg_catagory = ['pts'=>'point', 'rbs'=>'rebound', 'ast'=>'assist', 'stl'=>'steal', 'blk'=>'block', 'three_pt'=>'three_pt'];
      break;
    case 'badge-yearly-catagory': // Player profile yearly view rendering
      $bdg_catagory = ['points'=>'1', 'rebounds'=>'2', 'assists'=>'3', 'steals'=>'4', 'blocks'=>'5', 'three_pt'=>'6'];
      break;
    case 'stat-catagories':
      return ['stat_point'=>'Points', 'stat_rebound'=>'Rebounds', 'stat_assist'=>'Assists', 'stat_steal'=>'Steals', 'stat_block'=>'Blocks', 'stat_three'=>'3-Points'];
      break;
    case 'stat-catagories-v2':
      return ['total_points'=>'Points', 'reb'=>'Rebounds', 'ast'=>'Assists', 'stl'=>'Steals', 'blk'=>'Blocks', 'three_pt_made'=>'3-Points Made', 'three_pt_att'=>'3-Point Attempts', 'three_pt_percentage'=>'3-Point Percentage', 'ft_made'=>'Free Throws Made', 'ft_att'=>'Free Throw Attempts', 'ft_percentage'=>'Free Throws Percentage', 'fg_made'=>'Field Goals Made', 'fg_att'=>'Field Goal Attempts', 'fg_percentage'=>'Field Goals Percentage'];
      break;
  }
  return $bdg_catagory;
}
function stat_catagories($type){
  switch($type){
    case 'stat-catagories-v2':
      return ['pts'=>'Points', 'rbs'=>'Rebounds', 'ast'=>'Assists', 'stl'=>'Steals', 'blk'=>'Blocks', 'three_pt'=>'3-Points Made', 'three_pt_att'=>'3-Point Attempts', 'three_pt_percentage'=>'3-Point Percentage', 'ft_made'=>'Free Throws Made', 'ft_att'=>'Free Throw Attempts', 'ft_percentage'=>'Free Throws Percentage', 'fg_made'=>'Field Goals Made', 'fg_att'=>'Field Goal Attempts', 'fg_percentage'=>'Field Goals Percentage'];
      break;
  }
}
function bind_to_template($replacements, $template, $args = null, $type = null){
  global $wpdb;
  $dbs = json_decode(dbs());
  /*Template Field Values*/
  $list_of_fields = array(); $badge_options = array();
  !empty($args['badge_id']) ? $badge_id = $args['badge_id'] : $badge_id = '1';
  !empty($args['badge_name']) ? $badge_name = $args['badge_name'] : $badge_name = '';
  !empty($args['badge_url']) ? $badge_url = $args['badge_url'] : $badge_url = '';
  !empty($args['badge_admin']) ? $badge_admin = $args['badge_admin'] : $badge_admin = '';
  !empty($args['catagory_fields']) ? $catagory_fields = $args['catagory_fields'] : $catagory_fields = '';
  !empty($args['note_field']) ? $note_field = $args['note_field'] : $note_field = '';
  !empty($args['website_array']) ? $website_array = $args['website_array'] : $website_array = '';
  !empty($args['get_badge']) ? $get_badge = $args['get_badge'] : $get_badge = '';
  !empty($args['badge_name_id']) ? $badge_name_id = $args['badge_name_id'] : $badge_name_id = '';
  $bdg_catagories = json_decode(json_encode(badge_catagory('badge-catagory')), true);
  $get_badges_logo = $wpdb->get_results("SELECT * FROM $dbs->awards awd WHERE awd.enabled = 1 AND badge_type = 1");
  $player_badges_data = json_decode(json_encode($get_badges_logo), true);
  foreach($player_badges_data as $player_badge_data){
    if($badge_name == $player_badge_data['name']){
      $badge_name_selected = 'selected';
    }else{ $badge_name_selected = ''; }
    $badge_options[] = '<option bdg_type="'.$player_badge_data['badge_range'].'" bdg_url="'.$player_badge_data['logo_img'].'" id="'.$player_badge_data['id'].'" '.$badge_name_selected.' >'.$player_badge_data['name'].'</option>';
  }
  $badge_logo = '<div style="flex: 0 1 100%;" class="text-center"><img id="badge-'.$badge_id.'-logo" class="admin_badge_logo_container" src="'.$badge_url.'" alt="'.$badge_name.'"></div>';
  $badge_options = implode('', $badge_options);
  foreach($bdg_catagories as $bdg_catagory){
    if(is_array($website_array)){
      if(in_array(''.$bdg_catagory.'', $website_array)){ $catagory_checked = 'checked'; }else{ $catagory_checked = ''; }    
    }else{ $catagory_checked = ''; }
    !empty($args['cumulative_individual_'.$bdg_catagory]) ? $cumu_indi_ev = $args['cumulative_individual_'.$bdg_catagory] : $cumu_indi_ev = '';
    !empty($args['cumulative_'.$bdg_catagory.'_year']) ? $cumu_ev_year = $args['cumulative_'.$bdg_catagory.'_year'] : $cumu_ev_year = '';
    !empty($args['indi_gm_indi_'.$bdg_catagory]) ? $indi_gm_indi_ev = $args['indi_gm_indi_'.$bdg_catagory] : $indi_gm_indi_ev = '';
    !empty($args['avg_cond_indi_'.$bdg_catagory]) ? $avg_cond_indi_ev = $args['avg_cond_indi_'.$bdg_catagory] : $avg_cond_indi_ev = '';
    !empty($args[''.$bdg_catagory.'_value']) ? $catagory_value = $args[''.$bdg_catagory.'_value'] : $catagory_value = '';
    !empty($args[''.$bdg_catagory.'_year']) ? $catagory_year = $args[''.$bdg_catagory.'_year'] : $catagory_year = '';
    !empty($args['op_'.$bdg_catagory.'']) ? $operation_type = $args['op_'.$bdg_catagory.''] : $operation_type = '';
    !empty($args['type_'.$bdg_catagory.'']) ? $stat_type = $args['type_'.$bdg_catagory.''] : $stat_type = ''; // Total, Average, Individual Game
    if($operation_type == '='){ $equal_selected = 'selected'; }else{ $equal_selected = ''; }
    if($operation_type == '>'){ $greater_selected = 'selected'; }else{ $greater_selected = ''; }
    if($operation_type == '<'){ $lesser_selected = 'selected'; }else{ $lesser_selected = ''; }
    if($operation_type == '>='){ $goe_selected = 'selected'; }else{ $goe_selected = ''; }
    if($operation_type == '<='){ $loe_selected = 'selected'; }else{ $loe_selected = ''; }
    if($stat_type == 'total'){ $stat_total = 'selected'; }else{ $stat_total = ''; }
    if($stat_type == 'average'){ $stat_average = 'selected'; }else{ $stat_average = ''; }
    if($stat_type == 'individual_game'){ $stat_individual_game = 'selected'; }else{ $stat_individual_game = ''; }
    if($cumu_indi_ev == 'cumulative_individual_event'){ $cumulative_individual_event = 'selected'; }else{ $cumulative_individual_event = ''; }
    if($cumu_ev_year == 'cumulative_event_year'){ $cumulative_event_year = 'selected'; }else{ $cumulative_event_year = ''; }
    if($indi_gm_indi_ev == 'indi_gm_indi_event'){ $indi_gm_indi_event = 'selected'; }else{ $indi_gm_indi_event = ''; }
    if($avg_cond_indi_ev == 'avg_cond_indi_event'){ $avg_cond_indi_event = 'selected'; }else{ $avg_cond_indi_event = ''; }
    $list_of_fields['badge_id'] = $badge_id;
    $list_of_fields['badge_name'] = $badge_name;
    $list_of_fields['badge_url'] = $badge_logo;
    $list_of_fields['badge_admin'] = $badge_admin;
    $list_of_fields['note_field'] = $note_field;
    $list_of_fields['badge_option'] = $badge_options;
    $list_of_fields[''.$bdg_catagory.''] = $catagory_checked;
    $list_of_fields['equal_selected_'.$bdg_catagory.''] = $equal_selected;
    $list_of_fields['greater_selected_'.$bdg_catagory.''] = $greater_selected;
    $list_of_fields['lesser_selected_'.$bdg_catagory.''] = $lesser_selected;
    $list_of_fields['goe_selected_'.$bdg_catagory.''] = $goe_selected;
    $list_of_fields['loe_selected_'.$bdg_catagory.''] = $loe_selected;
    $list_of_fields[''.$bdg_catagory.'_total'] = $stat_total;
    $list_of_fields[''.$bdg_catagory.'_average'] = $stat_average;
    $list_of_fields[''.$bdg_catagory.'_individual_game'] = $stat_individual_game;
    $list_of_fields['cumulative_individual_'.$bdg_catagory] = $cumulative_individual_event;
    $list_of_fields['cumulative_'.$bdg_catagory.'_year'] = $cumulative_event_year;
    $list_of_fields['indi_gm_indi_'.$bdg_catagory] = $indi_gm_indi_event;
    $list_of_fields['avg_cond_indi_'.$bdg_catagory] = $avg_cond_indi_event;
    $list_of_fields[''.$bdg_catagory.'_year'] = $catagory_year;
    $list_of_fields[''.$bdg_catagory.'_value'] = $catagory_value;
    $list_of_fields['plain_badge_name'] = $badge_name;
  }
  switch($type){
    case 'api-forms':
      $stat_options = array(); $stat_year_list = array();
      !empty($args['admin_keys']) ? $admin_keys = $args['admin_keys'] : $admin_keys = '';
      !empty($args['stat_type']) ? $stat_type = $args['stat_type'] : $stat_type = 'POINTS';
      !empty($args['stat_abbr']) ? $stat_abbr = $args['stat_abbr'] : $stat_abbr = 'PPG';
      $default_banner = 'https://grassroots365.com/wp-content/uploads/2021/09/stat-header.jpg';
      $list_of_fields['admin_keys'] = $admin_keys;
      $list_of_fields['stat_type'] = $stat_type;
      $list_of_fields['stat_abbr'] = $stat_abbr;
      $stat_catagories = json_decode(json_encode(badge_catagory('stat-catagories')), true);
      foreach($stat_catagories as $index => $stat_catagory){
        $stat_options[] = '<option '.$badge_name_selected.' value="'.$index.'">'.$stat_catagory.'</option>';
      }
      $stat_options = implode('', $stat_options);
      $list_of_fields['stat_options'] = $stat_options;
      $list_of_fields['default_banner'] = $default_banner;
      break;
    case 'acct-api-forms':
    case 'org-api-forms':  
      !empty($args['bind_acct_data']) ? $bind_acct_data = $args['bind_acct_data'] : $bind_acct_data = '';
      $list_of_fields['get_download_btn'] = $bind_acct_data['download_btn'];
      $list_of_fields['google_jquery_link'] = $bind_acct_data['google_jquery_link'];
      $list_of_fields['js_link'] = $bind_acct_data['js_link'];
      $list_of_fields['style_link'] = $bind_acct_data['style_link'];
      $list_of_fields['target_url'] = $bind_acct_data['target_url'];
      $list_of_fields['api_keys'] = $bind_acct_data['api_keys'];
      $list_of_fields['secret_keys'] = $bind_acct_data['secret_keys'];
      $list_of_fields['request_data'] = $bind_acct_data['request_data'];
      break;
  }
  $replacements = $list_of_fields;
  return preg_replace_callback('/{{(.+?)}}/',
  function($matches) use ($replacements){
    return $replacements[$matches[1]];
  }, $template);
}
function g365_data_validation($type, $sub_type = null, $args = null){
  switch($type){
    case 'badge-data':
      switch($sub_type){
        case 'data-validation':
          $filter_int = array_filter($args['check_op_int'], function($selectd_value, $selectd_key){ return $selectd_value != 'N/A' && $selectd_value != ''; }, ARRAY_FILTER_USE_BOTH);
          $filter_val = array_filter($args['check_op_val'], function($selectd_value, $selectd_key){ return is_numeric($selectd_value) && $selectd_value != ''; }, ARRAY_FILTER_USE_BOTH);
          $filter_indi_ev = array_filter($args['check_indi_ev'], function($selectd_value, $selectd_key){ return $selectd_value != 'N/A' && $selectd_value != ''; }, ARRAY_FILTER_USE_BOTH);
          $filter_st_type = array_filter($args['check_stat_type'], function($selectd_value, $selectd_key){ return is_string($selectd_value) && $selectd_value != 'N/A' && $selectd_value != ''; }, ARRAY_FILTER_USE_BOTH);
          $filter_st_op = array_filter($args['check_stat_op'], function($selectd_value, $selectd_key){ return is_string($selectd_value) && $selectd_value != 'N/A' && $selectd_value != ''; }, ARRAY_FILTER_USE_BOTH);
          $filter_st_op_val = array_filter($args['check_stat_op_val'], function($selectd_value, $selectd_key){ return is_numeric($selectd_value) && $selectd_value != ''; }, ARRAY_FILTER_USE_BOTH);
          $operator_list = array(); $operator_val_list = array(); $operator_st_list = array(); $operator_st_op_list = array(); $operator_st_op_val_list = array(); $valid_op_results = array(); $valid_op_val_results = array(); $valid_st_type_results = array(); $valid_st_op_results = array(); $valid_st_op_val_results = array(); $indi_ev_val_list = array(); $indi_ev_val_result = array();
          foreach($args['operator_list'] as $operator_type){
            if(in_array($operator_type, array_keys($filter_int))){ $op_list = '"operator", "'.$filter_int[$operator_type].'", '; }else{ $op_list = ''; }
            $operator_list[$operator_type] = $op_list;
            if(in_array($operator_type, array_keys($filter_val))){ $op_val_list = '"value", "'.$filter_val[$operator_type].'"'; }else{ $op_val_list = ''; }
            if(in_array($operator_type, array_keys($filter_indi_ev))){ $op_indi_ev_val = ' "indi_val", "'.$filter_indi_ev[$operator_type].'"'; }else{ $op_indi_ev_val = ''; }
            if(in_array($operator_type, array_keys($filter_st_type))){ $st_type_list = '"type", "'.$filter_st_type[$operator_type].'", '; }else{ $st_type_list = ''; }
            if(in_array($operator_type, array_keys($filter_st_op))){ $st_op_list = '"operator", "'.$filter_st_op[$operator_type].'", '; }else{ $st_op_list = ''; }
            if(in_array($operator_type, array_keys($filter_st_op_val))){ $st_op_val_list = '"value", "'.$filter_st_op_val[$operator_type].'"'; }else{ $st_op_val_list = ''; }
            $operator_list[$operator_type] = $op_list;
            $operator_val_list[$operator_type] = $op_val_list;
            $indi_ev_val_list[$operator_type] = $op_indi_ev_val;
            $operator_st_list[$operator_type] = $st_type_list;
            $operator_st_op_list[$operator_type] = $st_op_list;
            $operator_st_op_val_list[$operator_type] = $st_op_val_list;
            $valid_op_results[$operator_type] = $operator_list[$operator_type];
            $indi_ev_val_result[$operator_type] = $indi_ev_val_list[$operator_type];
            $valid_op_val_results[$operator_type] = $operator_val_list[$operator_type];
            $valid_st_type_results[$operator_type] = $operator_st_list[$operator_type];
            $valid_st_op_results[$operator_type] = $operator_st_op_list[$operator_type];
            $valid_st_op_val_results[$operator_type] = $operator_st_op_val_list[$operator_type];
          }
          return ['valid_op_results'=>$valid_op_results, 'valid_op_val_results'=>$valid_op_val_results, 'valid_st_type_results'=>$valid_st_type_results, 'valid_st_op_results'=>$valid_st_op_results, 'valid_st_op_val_results'=>$valid_st_op_val_results, 'indi_ev_val_result'=>$indi_ev_val_result];
          break;
      }
      break;
  }
}
function manually_update_renewal_order_pp_data($start_date, $end_date, $season_year, $paid_date){
  // $start_date : "2022-08-31", $end_date: "2022-09-30"
  // Update subscription renewal order pp data
  $subscription_meta = array(); $pp_sub_ids = array(); $pp_sub_stat_ids = array();
  $subscriptions = wcs_get_subscriptions(['subscriptions_per_page' => -1]);
  foreach($subscriptions as $index => $subscription){
    $related_order = $subscription->get_related_orders();
    if(count($related_order) > 1){ // Check if the renewal exist
      $last_order = array_slice($related_order, 0, 1); // Get the last renewal order
      $last_order_detail = wc_get_order( $last_order[0] );
      $last_order_date = $last_order_detail->get_date_paid()->format('Y-m-d');
      if( $last_order_date > $start_date && $last_order_date < $end_date ){
        $subscription_details = json_decode(json_encode($last_order_detail->meta_data), true);
        $subscription_meta[$index] = $subscription_details;
        $subscription_meta[$index]['subscription_id'] = $index;
      }
    }
  }
  foreach($subscription_meta as $dex => $subscription_detail_id){
    foreach($subscription_detail_id as $subscription_detail){
      if( !empty($subscription_detail['value']) && (strpos($subscription_detail['value'], 'passport') ) !== false){
        $pp_sub_ids[] = $dex;
        if( strpos($subscription_detail['value'], ':::') !== false ){ $pp_sub_stat_id = substr($subscription_detail['value'], 0, strpos($subscription_detail['value'], ':::')); }else{ $pp_sub_stat_id = $subscription_detail['value']; }
  //         $pp_sub_stat_id = substr($subscription_detail['value'], 0, strpos($subscription_detail['value'], ':::'));
        $id_only = str_replace('passport,', '', $pp_sub_stat_id);
        $pp_sub_stat_ids[] = $id_only;
      }
    }
  }
  global $wpdb; $dbs = json_decode(dbs());
  $pp_sub_stat_ids = implode(', ', $pp_sub_stat_ids);
  $wpdb->query("UPDATE $dbs->stats SET stats = JSON_MERGE_PATCH(stats, JSON_OBJECT('seasons', JSON_OBJECT($season_year, JSON_OBJECT('paid', '$paid_date')))) WHERE id in ($pp_sub_stat_ids)");
}
function stat_platform_girl_level($args = null){
  // Fix girl level display
  !empty($args['team_name']) ? $team_name = $args['team_name'] : $team_name = '';
  $girl_levels = ['40U', '41U', '42U', '43U', '44U', '45U', '46U', '47U'];
  $girl_format_levels = ['Girls 4th Grade', 'Girls 5th Grade', 'Girls 6th Grade', 'Girls 7th Grade', 'Girls 8th Grade', 'Frosh/Soph Girls', 'JV Girls', 'Varsity Girls'];
  return $converted_levels = str_replace($girl_levels, $girl_format_levels, $team_name);
}

/*
** React App with Wordpress API
*/
add_action('rest_api_init', 'register_api_hooks');
function register_api_hooks(){
  register_rest_route(
    'app-data-request', '/v1/(?P<data_type>\w+)/(?P<event_game>\w+)/(?P<game_stat>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'react_api_callback',
    )
  );
}
// Post request from remote API
add_action('rest_api_init', 'remote_api_request');
function remote_api_request(){
  register_rest_route(
    'app-post-request', '/v1/(?P<data_type>\w+)',
    array(
      'methods'  => 'POST',
      'callback' => 'react_wp_api_callback',
    )
  );
}
// Login request from remote API
add_action('rest_api_init', 'login_api_hooks');
function login_api_hooks(){
  register_rest_route(
    'app-login-request', '/login/',
    array(
      'methods'  => 'POST',
      'callback' => 'login',
    )
  );
}
// Add custom endpoint for user registration
add_action('rest_api_init', 'mobile_app_account_creation');
function mobile_app_account_creation(){
  register_rest_route(
    'user-register/v1', '/register', 
    array(
      'methods' => 'POST',
      'callback' => 'account_creation',
    )
  );
}
add_action('rest_api_init', 'register_stat_api_hooks');
function register_stat_api_hooks(){
  register_rest_route(
    'app-stat-data-request', '/v1/(?P<data_type>\w+)/(?P<org_type>\w+)/(?P<event_id>\w+)/(?P<stat_type>\w+)/(?P<lv_type>\w+)/(?P<division>\w+)/(?P<year>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'react_api_callback',
    )
  );
}
add_action('rest_api_init', 'register_standing_api_hooks');
function register_standing_api_hooks(){
  register_rest_route(
    'app-standing-data-request', '/v1/(?P<data_type>\w+)/(?P<select_year>\w+)/(?P<division_list>\w+)/(?P<player_type>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'react_api_callback',
    )
  );
}
add_action('rest_api_init', 'register_user_data_api_hooks');
function register_user_data_api_hooks(){
  register_rest_route(
    'app-user-data-request', '/v1/(?P<data_type>\w+)/(?P<user_id>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'react_api_callback',
    )
  );
}
add_filter('jwt_auth_token_before_dispatch', 'jwt_login_token', 10, 2);
function jwt_login_token($token, $user){
  $token['user_id'] = $user->ID;
  return $token;
}
add_action('rest_api_init', 'register_boxscore_api_hooks');
function register_boxscore_api_hooks(){
  register_rest_route(
    'app-boxscore-data-request', '/v1/(?P<data_type>\w+)/(?P<team_id>\w+)/(?P<org_id>\w+)/(?P<game_id>\w+)/(?P<select_year>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'react_api_callback',
    )
  );
}
add_action('rest_api_init', 'register_player_data_api_hooks');
function register_player_data_api_hooks(){
  register_rest_route(
    'app-player-data-request', '/v1/(?P<data_type>\w+)/(?P<player_id>\w+)/(?P<args_one>\w+)/(?P<args_two>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'react_api_callback',
    )
  );
}

add_action('rest_api_init', 'register_post_player_api_hooks');
function register_post_player_api_hooks(){
  register_rest_route(
    'app-post-player-data', 
    '/v1/(?P<data_type>\w+)/',
    array(
      'methods'  => 'POST',
      'callback' => 'post_player_data',
      'permission_callback' => function () {
        return current_user_can( 'player_editor' );
      },
    )
  );
}

add_action('rest_api_init', 'register_get_player_api_hooks');
function register_get_player_api_hooks(){
  // Route for player search or getting all players
  register_rest_route(
    'app-get-player-data', 
    '/v1/players/', // This route handles search queries
    array(
      'methods'  => 'GET',
      'callback' => 'get_players_data',
      'args' => array(
        'search' => array(
          'required' => false,
          'validate_callback' => function($param, $request, $key) {
            return is_string($param) && strlen($param) >= 2;
          }
        ),
        'page' => array(
          'required' => false,
          'default' => 1,
          'validate_callback' => function($param, $request, $key) {
            return is_numeric($param) && $param > 0;
          }
        ),
        'per_page' => array(
          'required' => false,
          'default' => 10,
          'validate_callback' => function($param, $request, $key) {
            return is_numeric($param) && $param > 0;
          }
        ),
      )
    )
  );
  
  // Route for fetching data for a specific player by ID
  register_rest_route(
    'app-get-player-data', 
    '/v1/players/(?P<player_id>\d+)', // Adjusted route for specific player ID
    array(
      'methods'  => 'GET',
      'callback' => 'get_player_data',
      'args' => array(
        'player_id' => array(
          'required' => true,
          'validate_callback' => function($param, $request, $key) {
            return is_numeric($param);
          }
        )
      )
    )
  );
}

add_action('rest_api_init', 'register_update_player_api_hooks');
function register_update_player_api_hooks(){
  register_rest_route(
    'app-update-player-data', 
    '/v1/(?P<data_type>\w+)/(?P<player_id>\w+)',
    array(
      'methods'  => 'PUT',
      'callback' => 'update_player_data',
    )
  );
}

add_action('rest_api_init', 'register_get_user_api_hooks');
function register_get_user_api_hooks(){
  register_rest_route(
    'app-get-user-data', 
    '/v1/(?P<data_type>\w+)/(?P<user_id>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'get_user_data',
    )
  );
}

add_action('rest_api_init', 'register_update_user_profile_endpoint');
function register_update_user_profile_endpoint() {
  register_rest_route(
    'app-update-user-data',
    '/v1/user',
    array(
      'methods'  => 'POST',
      'callback' => 'update_user_data',
    )
  );
}

add_action('rest_api_init', 'register_user_claim_endpoint');
function register_user_claim_endpoint() {
  register_rest_route(
    'app-user-claim-data',
    '/v1/claim/(?P<player_id>\w+)',
    array(
      'methods'  => 'POST',
      'callback' => 'user_claim_data',
    )
  );
}

add_action('rest_api_init', 'user_reset_password_endpoint');
function user_reset_password_endpoint() {
  register_rest_route(
    'app-user-reset-password',
    '/v1/reset-password',
    array(
      'methods'  => 'POST',
      'callback' => 'user_reset_password',
    )
  );
}

add_action('rest_api_init', 'player_position_endpoint');
function player_position_endpoint() {
  register_rest_route(
    'app-player-position',
    '/v1/player-position',
    array(
      'methods'  => 'GET',
      'callback' => 'player_position',
    )
  );
}

// Add a custom REST endpoint for file upload for web
add_action('rest_api_init', 'player_file_upload_endpoint');
function player_file_upload_endpoint() {
  register_rest_route(
    'app-player-file-upload',
    '/v1/player-file-upload', array(
    'methods' => 'POST',
    'callback' => 'player_file_upload',
  ));
}

// Add a custom REST endpoint for file upload for mobile app react native
add_action('rest_api_init', 'player_file_upload_rn_endpoint');
function player_file_upload_rn_endpoint() {
  register_rest_route(
    'app-player-file-upload-rn',
    '/v1/player-file-upload-rn', array(
    'methods' => 'POST',
    'callback' => 'player_file_upload_rn',
  ));
}

add_action('rest_api_init', 'player_achievement_endpoint');
function player_achievement_endpoint() {
  register_rest_route(
    'app-player-achievement',
    '/v1/player-achievement/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'player_achievement',
  ));
}

add_action('rest_api_init', 'get_badge_endpoint');
function get_badge_endpoint() {
  register_rest_route(
    'app-badges',
    '/v1/badges', array(
    'methods' => 'GET',
    'callback' => 'get_badges',
  ));
  register_rest_route(
    'app-badges',
    '/v1/badges/(?P<badge_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_badge',
  ));
}

add_action('rest_api_init', 'get_eligible_tag_endpoint');
function get_eligible_tag_endpoint() {
  register_rest_route(
    'app-eligible-tag',
    '/v1/eligible-tag', array(
    'methods' => 'POST',
    'callback' => 'get_eligible_tag',
  ));
}

add_action('rest_api_init', 'get_player_career_highs_endpoint');
function get_player_career_highs_endpoint() {
  register_rest_route(
    'app-career-highs',
    '/v1/career-highs/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_career_high',
  ));
}

add_action('rest_api_init', 'get_player_season_avg_endpoint');
function get_player_season_avg_endpoint() {
  register_rest_route(
    'app-season-avg',
    '/v1/season-avg/(?P<player_id>\w+)', array(
    'methods' => 'POST',
    'callback' => 'get_season_avg',
  ));
}

add_action('rest_api_init', 'player_subscription_validation_endpoint');
function player_subscription_validation_endpoint() {
  register_rest_route(
    'app-subscription-validation',
    '/v1/subscription-validation/(?P<player_id>\w+)/(?P<season_year>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_subscription_validation',
  ));
}

add_action('rest_api_init', 'player_event_average_endpoint');
function player_event_average_endpoint() {
  register_rest_route(
    'app-event-average',
    '/v1/event-average/(?P<player_id>\w+)/(?P<event_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_event_average',
  ));
}

add_action('rest_api_init', 'player_events_endpoint');
function player_events_endpoint() {
  register_rest_route(
    'app-events-stat',
    '/v1/events-stat/(?P<event_type>\w+)/(?P<player_id>\w+)/(?P<selected_year>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_player_events',
  ));
}

add_action('rest_api_init', 'player_game_results_endpoint');
function player_game_results_endpoint() {
  register_rest_route(
    'app-game-results',
    '/v1/game-results/(?P<player_id>\w+)/(?P<event_id>\w+)/(?P<selected_year>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_player_game_results',
  ));
}

add_action('rest_api_init', 'player_statistics_endpoint');
function player_statistics_endpoint() {
  register_rest_route(
    'app-player-statistics',
    '/v1/player-statistics/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_player_statistics',
  ));
}

add_action('rest_api_init', 'player_camp_profile_endpoint');
function player_camp_profile_endpoint() {
  register_rest_route(
    'app-player-camp-profile',
    '/v1/camp-profile/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_player_camp_profile',
  ));
}

add_action('rest_api_init', 'organizations_endpoint');
function organizations_endpoint() {
  register_rest_route(
    'app-organizations',
    '/v1/organizations', array(
    'methods' => 'GET',
    'callback' => 'get_organizations',
  ));
}

add_action('rest_api_init', 'stat_leaderboard_endpoint');
function stat_leaderboard_endpoint() {
  register_rest_route(
    'app-stat-leaderboard',
    '/v1/stat-leaderboard/(?P<organization_id>\w+)/(?P<selected_year>\w+)/(?P<event_id>\w+)/(?P<stat_catagories>\w+)/(?P<roster_division>\w+)/(?P<roster_level>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_stat_leaderboard',
  ));
}

add_action('rest_api_init', 'stat_leaderboard_new_endpoint');
function stat_leaderboard_new_endpoint() {
  register_rest_route(
    'app-stat-leaderboard-new',
    '/v1/stat-leaderboard-new/(?P<organization_id>\w+)/(?P<selected_year>\w+)/(?P<event_id>\w+)/(?P<stat_catagories>\w+)/(?P<roster_division>\w+)/(?P<roster_level>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_stat_leaderboard_new',
  ));
}

add_action('rest_api_init', 'stat_leaderboard_spotlight_endpoint');
function stat_leaderboard_spotlight_endpoint() {
  register_rest_route(
    'app-stat-leaderboard-spotlight',
    '/v1/stat-leaderboard-spotlight', array(
    'methods' => 'GET',
    'callback' => 'get_stat_leaderboard_spotlight',
  ));
}

add_action('rest_api_init', 'team_standings_endpoint');
function team_standings_endpoint() {
  register_rest_route(
    'app-team-standings',
    '/v1/team-standings', array(
    'methods' => 'POST',
    'callback' => 'get_team_standings',
  ));
}

add_action('rest_api_init', 'team_standing_game_results_endpoint');
function team_standing_game_results_endpoint() {
  register_rest_route(
    'app-ts-game-results',
    '/v1/ts-game-results', array(
    'methods' => 'POST',
    'callback' => 'get_team_standing_game_results',
  ));
}

add_action('rest_api_init', 'full_team_standings_endpoint');
function full_team_standings_endpoint() {
  register_rest_route(
    'app-full-team-standings',
    '/v1/full-team-standings', array(
    'methods' => 'POST',
    'callback' => 'get_full_team_standings',
  ));
}

add_action('rest_api_init', 'boxscores_endpoint');
function boxscores_endpoint() {
  register_rest_route(
    'app-boxscores',
    '/v1/boxscores/(?P<team_id>\w+)/(?P<game_id>\w+)/(?P<selected_year>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_boxscores',
  ));
}

add_action('rest_api_init', 'profile_media_endpoint');
function profile_media_endpoint() {
  register_rest_route(
    'app-profile-media',
    '/v1/profile-media/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_profile_media',
  ));
}

add_action('rest_api_init', 'mobile_app_qr_endpoint');
function mobile_app_qr_endpoint() {
  register_rest_route(
    'app-player-qr-code',
    '/v1/player-qr-code/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_player_qr_code',
  ));
}

add_action('rest_api_init', 'order_subscription_endpoint');
function order_subscription_endpoint() {
  register_rest_route(
    'app-order-subscription',
    '/v1/order-subscription/(?P<user_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_order_subscription',
  ));
}

add_action('rest_api_init', 'toggle_player_media');
function toggle_player_media() {
  register_rest_route(
    'app-toggle-player-media',
    '/v1/toggle-player-media', array(
    'methods' => 'POST',
    'callback' => 'post_toggle_player_media',
  ));
}

add_action('rest_api_init', 'missing_profile_information');
function missing_profile_information() {
  register_rest_route(
    'app-missing-profile-information',
    '/v1/missing-profile-information/(?P<user_id>\w+)/(?P<player_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'post_missing_profile_information',
  ));
}

add_action('rest_api_init', 'register_device_token_endpoint');
function register_device_token_endpoint() {
  register_rest_route(
    'app-register-device-token-endpoint',
    '/v1/register-device-token-endpoint', array(
    'methods' => 'POST',
    'callback' => 'post_device_token',
  ));
}

add_action('rest_api_init', 'team_spotlight_endpoint');
function team_spotlight_endpoint() {
  register_rest_route(
    'app-team-spotlight',
    '/v1/team-spotlight', array(
    'methods' => 'GET',
    'callback' => 'get_team_spotlight',
  ));
}

add_action('rest_api_init', 'program_team_screen_endpoint');
function program_team_screen_endpoint() {
  register_rest_route(
    'app-program-team',
    '/v1/program-team/(?P<select_year>\w+)/(?P<org_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_program_team',
  ));
}

add_action('rest_api_init', 'program_header_data_endpoint');
function program_header_data_endpoint() {
  register_rest_route(
    'app-program-header-data',
    '/v1/program-header-data/(?P<org_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_program_header_data',
  ));
}

add_action('rest_api_init', 'team_data_endpoint');
function team_data_endpoint() {
  register_rest_route(
    'app-team-data',
    '/v1/app-team-data/(?P<team_id>\w+)/(?P<org_id>\w+)/(?P<select_year>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_team_data',
  ));
}

add_action('rest_api_init', 'program_directory_endpoint');
function program_directory_endpoint() {
  register_rest_route(
    'app-program-directory',
    '/v1/program-directory/(?P<org_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_programs',
  ));
}

add_action('rest_api_init', 'program_trophy_case_endpoint');
function program_trophy_case_endpoint() {
  register_rest_route(
    'app-program-trophy-case',
    '/v1/program-trophy-case/(?P<org_id>\w+)/(?P<select_year>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_programs_trophy_case',
  ));
}

add_action('rest_api_init', 'coach_creation_endpoint');
function coach_creation_endpoint() {
  register_rest_route(
    'app-coach-creation',
    '/v1/coach-creation', array(
    'methods' => 'POST',
    'callback' => 'create_coach_account',
  ));
}

add_action('rest_api_init', 'update_coach_endpoint');
function update_coach_endpoint() {
  register_rest_route(
    'app-update-coach',
    '/v1/update-coach', array(
    'methods' => 'PUT',
    'callback' => 'update_coach_account',
  ));
}

add_action('rest_api_init', 'get_coach_data_endpoint');
function get_coach_data_endpoint() {
  register_rest_route(
    'app-get-coach-data',
    '/v1/get-coach-data/(?P<coach_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_coach_data',
  ));
}

add_action('rest_api_init', 'coach_claim_unclaim_program_endpoint');
function coach_claim_unclaim_program_endpoint() {
  register_rest_route(
    'app-coach-claim-unclaim-program',
    '/v1/coach-claim-unclaim-program', array(
    'methods' => 'PUT',
    'callback' => 'coach_claim_unclaim_program',
  ));
}

add_action('rest_api_init', 'coach_claim_request_endpoint');
function coach_claim_request_endpoint() {
  register_rest_route(
    'app-coach-claim-request',
    '/v1/coach-claim-request', array(
    'methods' => 'POST',
    'callback' => 'coach_claim_program_request',
  ));
}

add_action('rest_api_init', 'coach_roster_levels_endpoint');
function coach_roster_levels_endpoint() {
  register_rest_route(
    'app-coach-roster-levels',
    '/v1/coach-roster-levels/(?P<user_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'coach_roster_levels',
  ));
}

add_action('rest_api_init', 'coach_create_roster_endpoint');
function coach_create_roster_endpoint() {
  register_rest_route(
    'app-coach-create-roster',
    '/v1/coach-create-roster', array(
    'methods' => 'POST',
    'callback' => 'coach_create_roster',
  ));
}

add_action('rest_api_init', 'edit_coach_roster_endpoint');
function edit_coach_roster_endpoint() {
  register_rest_route(
    'app-coach-roster-editor',
    '/v1/coach-roster-editor', array(
    'methods' => 'PUT',
    'callback' => 'edit_coach_roster',
  ));
}

add_action('rest_api_init', 'coach_directory_endpoint');
function coach_directory_endpoint() {
  register_rest_route(
    'app-coach-directory',
    '/v1/coach-directory', array(
    'methods' => 'GET',
    'callback' => 'coach_directory',
  ));
}

add_action('rest_api_init', 'coach_player_search_endpoint');
function coach_player_search_endpoint() {
  register_rest_route(
    'app-coach-player-search',
    '/v1/coach-player-search/(?P<roster_level>\w+)', array(
    'methods' => 'GET',
    'callback' => 'coach_player_search',
  ));
}

add_action('rest_api_init', 'coach_rosters_endpoint');
function coach_rosters_endpoint() {
  register_rest_route(
    'app-coach-rosters',
    '/v1/coach-rosters/(?P<user_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'coach_rosters',
  ));
}

add_action('rest_api_init', 'event_search_endpoint');
function event_search_endpoint() {
  register_rest_route(
    'app-event-search',
    '/v1/event-search', array(
    'methods' => 'GET',
    'callback' => 'event_search',
  ));
}

add_action('rest_api_init', 'director_create_program_endpoint');
function director_create_program_endpoint() {
  register_rest_route(
    'app-director-create-program',
    '/v1/director-create-program', array(
    'methods' => 'POST',
    'callback' => 'director_create_program',
  ));
}

add_action('rest_api_init', 'director_update_program_endpoint');
function director_update_program_endpoint() {
  register_rest_route(
    'app-director-update-program',
    '/v1/director-update-program', array(
    'methods' => 'PUT',
    'callback' => 'director_update_program_request',
  ));
}

add_action('rest_api_init', 'director_owned_program_data_endpoint');
function director_owned_program_data_endpoint() {
  register_rest_route(
    'app-director-owned-program-data',
    '/v1/director-owned-program-data/(?P<user_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'director_owned_program',
  ));
}

add_action('rest_api_init', 'get_event_division_endpoint');
function get_event_division_endpoint() {
  register_rest_route(
    'app-event-division',
    '/v1/event-division/(?P<event_id>\w+)/(?P<roster_division>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_event_division',
  ));
}

add_action('rest_api_init', 'get_event_level_endpoint');
function get_event_level_endpoint() {
  register_rest_route(
    'app-event-level',
    '/v1/event-level/(?P<event_id>\w+)/(?P<division_id>\w+)', array(
    'methods' => 'GET',
    'callback' => 'get_event_level',
  ));
}

add_action('rest_api_init', 'director_coach_roster_submission_endpoint');
function director_coach_roster_submission_endpoint() {
  register_rest_route(
    'app-roster-submission',
    '/v1/roster-submission', array(
    'methods' => 'POST',
    'callback' => 'roster_submission',
  ));
}

add_action('rest_api_init', 'spp_subscription_pricing_endpoint');
function spp_subscription_pricing_endpoint() {
  register_rest_route(
    'spp-subscription-pricing',
    '/v1/subscription-pricing', array(
    'methods' => 'POST',
    'callback' => 'update_subscription_pricing',
  ));
}

add_action('rest_api_init', 'spp_recalculate_pricing_endpoint');
function spp_recalculate_pricing_endpoint() {
  register_rest_route(
    'spp-recalculate-pricing',
    '/v1/recalculate-pricing', array(
    'methods' => 'GET',
    'callback' => 'recalculate_pricing',
  ));
}
add_action('rest_api_init', 'register_get_us_api_hooks');
function register_get_us_api_hooks(){
  register_rest_route(
    'app-get-us-data', 
    '/v1/(?P<data_type>\w+)/(?P<user_id>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'get_us_data',
    )
  );
}

add_action('rest_api_init', 'get_program_information_hooks');
function get_program_information_hooks(){
  register_rest_route(
    'app-get-program-information', 
    '/v1/program-information/(?P<program_id>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'get_program_information',
    )
  );
}

// Stat Platform: Get player stats
add_action('rest_api_init', 'get_player_stats_hooks');
function get_player_stats_hooks(){
  register_rest_route(
    'get-player-stats', 
    '/v1/(?P<event_id>\w+)/(?P<game_id>\w+)',
    array(
      'methods'  => 'GET',
      'callback' => 'get_sl_player_stats',
    )
  );
}

function get_sl_player_stats($request){
//   $auth = $request->get_header('Authorization');
//   if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $event_id = $request->get_param('event_id');
    $game_id = $request->get_param('game_id');
    $get_player_stats = $wpdb->get_results(" SELECT st.player, pl.name, st.stats FROM $dbs->stats st INNER JOIN $dbs->players pl ON st.player = pl.id WHERE st.enabled = 1 AND st.stats IS NOT NULL AND stats != '' AND st.event = $event_id AND st.game = $game_id ");
    return new WP_REST_Response(['player_stats' => $get_player_stats], 200);
//   }else{
//     return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
//   }
}

function get_program_information($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $org_id = $request->get_param('program_id');
    $get_org_info = $wpdb->get_results(" SELECT id, name, abbreviation, profile_img, director_first, director_last, director_email, director_phone, email, phone, address, city, state, zip, country, link, social, videos, notes, county, nickname, tags, search_list FROM $dbs->orgs WHERE enabled = 1 AND id = $org_id  ");
    return new WP_REST_Response(['program_data' => $get_org_info], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function recalculate_pricing($request){
//   $auth = $request->get_header('Authorization');
//   if($auth){
//     $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
// //     Send GET request
//     $response = wp_remote_post($url, array(
//       'headers' => array(
//         'Authorization' => $auth,
//       ),
//       'timeout' => 45, // Adjust timeout as needed
//     ));
//     $body_res = $response['body'];
//     // Decode JSON string
//     $data = json_decode($body_res, true);
//     // Extract user_id
//     $user_id = (int)$data['data']['user_id'];
//     $user_meta = get_userdata($user_id);
//     $user_roles = $user_meta->roles;
    
// //     if($user_roles === 'administrator'){
//       $get_body = $request->get_body();
//       $request_body = json_decode( $get_body, true );
//       $start_date = $request_body['start_date'];
//       $end_date = $request_body['end_date'];
//       $update_spp_pricing = recalculate_all_subscription_total(['start_date'=>$start_date, 'end_date'=>$end_date]);
// //       return new WP_REST_Response(['message' => 'Passport records are successfully recalculated.', $user_roles], 200);
// //     }else{
// //       return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
// //     }
//       return new WP_REST_Response(['message' => 'Passport records are successfully recalculated.'], 200);
//   }else{
//     return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
//   }
}

function update_subscription_pricing($request){
//   $auth = $request->get_header('Authorization');
//   if($auth){
//     $get_body = $request->get_body();
//     $request_body = json_decode( $get_body, true );
//     $product_name = $request_body['product_name'];
//     $new_price = $request_body['new_price'];
//     $start_date = $request_body['start_date'];
//     $end_date = $request_body['end_date'];
//     $update_spp_pricing = g365_update_passport_subscription_pricing($product_name, $new_price, ['start_date'=>$start_date, 'end_date'=>$end_date]);
//     return new WP_REST_Response(['message' => 'Records are successfully updated.'], 200);
//   }else{
//     return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
//   }
}

function roster_submission($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $event_id = $request_body['event_id'];
    $roster_id = $request_body['roster_id'];
    $division = $request_body['division'];
    $level = $request_body['level'];
    // Step 1: Retrieve the existing record
    $existing_roster = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM {$dbs->rosters} WHERE id = %d",
        $roster_id
      ),
      ARRAY_A // Return the result as an associative array
    );

    // Step 2: Modify the retrieved record (for example, update the event ID)
    if($existing_roster){
      $new_roster_data = $existing_roster;
      $new_roster_data['event'] = $event_id;
      $new_roster_data['division'] = $division;
      $new_roster_data['level'] = $level;
      unset($new_roster_data['id']); // Remove the ID field to avoid conflict (as we're inserting a new row)

      // Step 3: Insert the modified record as a new row
      $add_roster_to_event = $wpdb->insert($dbs->rosters, $new_roster_data);

      if($add_roster_to_event !== false){
        // Insertion successful
        $new_roster_id = $wpdb->insert_id; // Get the ID of the newly inserted row
      }else{
        // Insertion failed
        return new WP_REST_Response(['Error creating roster'], 400);
      }
    }else{
      return new WP_REST_Response(['Error creating roster'], 400);
    }
    return new WP_REST_Response(['message' => 'Roster is successfully created.', 'id' => $new_roster_id], 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function get_event_level($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $event_id = $request->get_param('event_id');
    $division_id = $request->get_param('division_id');
    $get_results = $wpdb->get_results("SELECT divisions FROM $dbs->events WHERE enabled = 1 AND id = $event_id");

    // Decode JSON to PHP array
    $data = json_decode($get_results[0]->divisions, true);
    $filtered_data = [];
    if (isset($data[$division_id])) {
      // Initialize an empty array to store the formatted names
      $order = ["Open", "Gold", "Silver", "Bronze", "Copper"];
      $names_with_keys = [];
      $names_in_order = [];

      // Iterate over the names and create key-value pairs
      foreach($data[$division_id] as $key => $value){
        $names_with_keys[$key] = $key;
      }

      foreach($order as $key){
        if(isset($names_with_keys[$key])){
          $names_in_order[$key] = $names_with_keys[$key];
        }
      }

      // Assign the formatted names to the key $division_id
      $filtered_data[] = $names_in_order;
    }
   return new WP_REST_Response(['event_level' => $filtered_data], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_event_division($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $event_id = $request->get_param('event_id');
    $roster_division = $request->get_param('roster_division');
    $get_results = $wpdb->get_results("SELECT divisions FROM $dbs->events WHERE enabled = 1 AND id = $event_id");

    // Decode JSON to PHP array
    $data = json_decode($get_results[0]->divisions, true);
    // Initialize an empty array to store the grouped result
    $grouped_data = [];

    $filtered_data = array_filter($data, function ($key) use ($roster_division) {
      if($roster_division >= 8 && $roster_division <= 17){
        return ( $key >= intval($roster_division) && $key <= 17);
      }
      elseif($roster_division >= 40 && $roster_division <= 50){
        return ( $key >= intval($roster_division) && $key <= 50 );
      }
    }, ARRAY_FILTER_USE_KEY);

    // Iterate over the decoded data
    foreach($filtered_data as $key => $value){
      // Check if $value is an array (for cases with named values)
      if (is_array($value)) {
        // Map the number to the names
        $grouped_data[$key] = g365_return_keys('g365_grade_key')[$key];
      } else {
        // Handle cases where the value is a single number
        $grouped_data[$key] = g365_return_keys('g365_grade_key')[$key];
      }
    }
   return new WP_REST_Response(['event_divisoin' => $grouped_data], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function director_owned_program($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $user_id = $request->get_param('user_id');
    $user_owns_g365 = get_user_meta($user_id, '_user_owns_g365', true);

    // If the meta field is not empty, append ID
    if(isset($user_owns_g365['og_ed'])){ $org_id = $user_owns_g365['og_ed']; }else{ $org_id = null; }
   return new WP_REST_Response(['program_id' => $org_id], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function director_update_program_request($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $name = $request_body['name'];
    $abbreviation = $request_body['club_nickname'];
    $program_id = $request_body['program_id'];
    $director_firstname = $request_body['director_firstname'];
    $director_lastname = $request_body['director_lastname'];
    $director_email = $request_body['director_email'];
    $director_phone = $request_body['director_phone'];
    $program_email = $request_body['program_email'];
    $program_phone = $request_body['program_phone'];
    $program_address = $request_body['program_address'];
    $city = $request_body['city'];
    $state = $request_body['state'];
    $zip = $request_body['zip'];
    $country = $request_body['country'];
    $county = $request_body['county'];
    $site_url = $request_body['site_url'];
    $instagram = $request_body['instagram'];
    $file_type = $request_body['file_type'];
    $profile_img = strtolower(str_replace(' ', '-', $request_body['name']) . '_' . $program_id. '.' .$file_type);
    $user_id = $request_body['user_id'];
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    }else{
      $site_tage = "SPP";
    }
  
    // Prepare the SQL query with the updated access JSON
    $sql = $wpdb->prepare("
      UPDATE $dbs->orgs
      SET 
        updatetime = CURRENT_TIMESTAMP,
        name = %s, 
        abbreviation = %s, 
        profile_img = %s, 
        director_first = %s, 
        director_last = %s, 
        director_email = %s, 
        director_phone = %s, 
        email = %s, 
        phone = %s, 
        address = %s, 
        city = %s, 
        state = %s, 
        zip = %s, 
        country = %s, 
        county = %s, 
        link = %s,
        social = JSON_OBJECT('Instagram', %s)
      WHERE id = %d",
      $name,
      $abbreviation,
      $profile_img,
      $director_firstname,
      $director_lastname,
      $director_email,
      $director_phone,
      $program_email,
      $program_phone,
      $program_address,
      $city,
      $state,
      $zip,
      $country,
      $county,
      $site_url,
      $instagram,
      $program_id // ID of the record to update
    );

    // Execute the query
    $result = $wpdb->query($sql);

    if($result === false){
      // Error handling
      return new WP_REST_Response(array('Error updating coach'), 400);
    }else{
      return new WP_REST_Response(array('message' => 'Program information is successfully updated.'), 200);
    }
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function director_create_program($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $name = $request_body['name'];
    $abbreviation = $request_body['club_nickname'];
    $director_firstname = $request_body['director_firstname'];
    $director_lastname = $request_body['director_lastname'];
    $director_email = $request_body['director_email'];
    $director_phone = $request_body['director_phone'];
    $program_email = $request_body['program_email'];
    $program_phone = $request_body['program_phone'];
    $program_address = $request_body['program_address'];
    $city = $request_body['city'];
    $state = $request_body['state'];
    $zip = $request_body['zip'];
    $country = $request_body['country'];
    $county = $request_body['county'];
    $site_url = $request_body['site_url'];
    $instagram = $request_body['instagram'];
    $file_type = $request_body['file_type'];
    $nickname = strtolower(str_replace(' ', '-', $request_body['name']) . '-' .$state);
    $user_id = $request_body['user_id'];
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    }else{
      $site_tage = "SPP";
    }

    // Define the new access data based on $site_tage
    $new_access_data = [];
    $new_access_data[$site_tage][] = $user_id;
    // Encode the merged access data to JSON format
    $access_json = json_encode($new_access_data);
    
     // Prepare the SQL query for inserting a new record
    $sql = $wpdb->prepare("
      INSERT INTO $dbs->orgs (
        createtime, 
        updatetime, 
        name, 
        abbreviation, 
        profile_img, 
        director_first, 
        director_last, 
        director_email, 
        director_phone, 
        email, 
        phone, 
        address, 
        city, 
        state, 
        zip, 
        country, 
        county, 
        link, 
        nickname,
        access, 
        social
      ) VALUES (
        CURRENT_TIMESTAMP, 
        CURRENT_TIMESTAMP, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        %s, 
        JSON_OBJECT('Instagram', %s)
      )",
      $name,
      $abbreviation,
      null,
      $director_firstname,
      $director_lastname,
      $director_email,
      $director_phone,
      $program_email,
      $program_phone,
      $program_address,
      $city,
      $state,
      $zip,
      $country,
      $county,
      $site_url,
      $nickname,
      $access_json,
      $instagram
    );
    // Execute the query
    $result = $wpdb->query($sql);
    if($result === false){
      // Error handling
      return new WP_REST_Response(array('Error creating program'), 400);
    }else{
      $insert_program_id = $wpdb->insert_id;
      if(empty($file_type)){
        $profile_img = null;
      }else{
        $profile_img = strtolower(str_replace(' ', '-', $request_body['name']) . '_' . $insert_program_id. '.' .$file_type);      
      }
      
       $update_program_img = array(
        'profile_img' => $profile_img
      );

      $where = array('id' => $insert_program_id);

      $update_org_profile_img = $wpdb->update($dbs->orgs, $update_program_img, $where);
      
      // Get the existing user meta
      $user_owns_g365 = get_user_meta($user_id, '_user_owns_g365', true);

      // If the meta field is not empty, append ID
      if(isset($user_owns_g365['og_ed'])){ array_push($user_owns_g365['og_ed'], $insert_program_id); }
      else{
        // If the meta field is empty, initialize it as an array containing only ID
        $user_owns_g365['og_ed'] = array($insert_program_id);
      }
      update_user_meta($user_id, '_user_owns_g365', $user_owns_g365);
      return new WP_REST_Response(array('message' => 'Program ID '.$insert_program_id.' is successfully created.', 'id' => $insert_program_id), 200);
    }
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function event_search($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $get_events = $wpdb->get_results(" SELECT id, name, logo_img, dates, divisions FROM $dbs->events WHERE enabled = 1 AND eventtime > NOW() - INTERVAL 1 WEEK ");
    $events = json_decode(json_encode($get_events), true);
    
    function extract_divisions($array, &$divisons){
      foreach($array as $key => $value){
        if(is_array($value)){
          extract_divisions($value, $divisons);
        }elseif(is_string($key)){
          $divisons[$key] = $key;
        }
      }
    }
    
    foreach($events as &$event){
      if(isset($event['divisions'])){
        $divisons = [];
        extract_divisions(json_decode($event['divisions'], true), $divisons);
        $get_divisions = array_unique($divisons);
        $event['divisions'] = $get_divisions;
        $event['dates'] = g365_build_dates($event['dates'], $type = 2, $abbv = false, $add_reg = false);
      }
    }
    return new WP_REST_Response(['events' => $events], 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_rosters($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $user_id = $request->get_param('user_id');
    $user_data = get_user_meta($user_id, '_user_owns_g365', true);
    if(array_key_exists('og_ed', $user_data)){
      $og_id = $user_data['og_ed'][0];
    }
    $get_rosters = $wpdb->get_results(" SELECT ros.id, ros.enabled visibility, ros.name roster_name, org.abbreviation org_abbreviation, org.name org_name, ros.team_type, ros.players, ros.level, coa.name coach_name, (SELECT asst_coa.name FROM $dbs->rosters asst_ros LEFT JOIN $dbs->coaches asst_coa ON asst_ros.asst = asst_coa.id WHERE asst_ros.id = ros.id) asst_coach_name FROM $dbs->rosters ros INNER JOIN $dbs->orgs org ON ros.org = org.id LEFT JOIN $dbs->coaches coa ON ros.coach = coa.id WHERE ros.org = $og_id AND ros.event = 0 ");
 
    $filtered_rosters = array_map(function($item) use ($wpdb, $dbs) {
      // Decode the players JSON
      $player_ids = json_decode($item->players, true);

      // Initialize an array to store player details (name and j_num)
      $player_details = [];

      if ($player_ids) {
        // Extract player IDs
        $ids = array_keys($player_ids);

        // Prepare and execute the query to get player names
        $ids_placeholder = implode(',', array_fill(0, count($ids), '%d'));
        $players_query = $wpdb->prepare("
          SELECT id, name 
          FROM $dbs->players 
          WHERE id IN ($ids_placeholder)
        ", $ids);

        $players = $wpdb->get_results($players_query);

        // Map player IDs to names and jersey numbers
        foreach ($players as $player) {
          $player_id = $player->id;
          $player_details[$player_id] = [
            'name' => $player->name,
            'j_num' => $player_ids[$player_id]['j_num'] // Get j_num from the decoded JSON
          ];
        }
      }

      return [
        'id' => $item->id,
        'visibility' => $item->visibility,
        'coach' => $item->coach_name,
        'assistant_coach' => $item->asst_coach_name,
        'roster_name' => (!empty($item->org_abbreviation)) ? $item->org_abbreviation . ' ' . g365_return_keys('g365_grade_key_short')[$item->level] . ' ' . $item->roster_name : $item->org_name . ' ' . g365_return_keys('g365_grade_key_short')[$item->level] . ' ' . $item->roster_name,
        'team_type' => $item->team_type,
        'players' => $player_details, // Store player details (name and j_num) here
        'division' => $item->level
      ];
  }, $get_rosters);

    return new WP_REST_Response(['coach_rosters' => $filtered_rosters], 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_player_search($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $roster_level = $request->get_param('roster_level');
    // Fetch player data from the database
    $coach_player_search = $wpdb->get_results("SELECT id, name, birthday, grad_year, verified, city, state FROM $dbs->players WHERE enabled = 1");
    $updated_player_search = [];

    foreach($coach_player_search as $player){
      $player_level = g365_age_level($player->birthday, $pl_date_now = null, $pl_time_zone = null, $level_proc = false);

      // Determine the eligible tag based on the player level
      if(($player_level > 7 && $player_level < 18) || ($player_level > 38 && $player_level < 48) || ($player_level > 60)){
        if($player_level <= $roster_level){
          $eligible_tag = 'ELIGIBLE';
        }else{
          $eligible_tag = 'INELIGIBLE';
        }
      }else{
        $eligible_tag = 'INELIGIBLE';
      }
      if($player->verified >= 2){
        $verify_tage = 'VERIFIED';
      }else{
        $verify_tage = 'UNVERIFIED';
      }

      // Append the eligible tag to the player data
      $player->eligible_tag = $eligible_tag;
      $player->verify_tag = $verify_tage;

      // Add the updated player data to the result array
      if($roster_level > 6 && $roster_level < 18){
        if( ($player_level >= ($roster_level - 2)) && ($player_level <= ($roster_level + 1)) ){
          $updated_player_search[] = $player;        
        }
      }else{
        $updated_player_search[] = $player;
      }
    }
    
    return new WP_REST_Response(['coach_player_search' => $updated_player_search], 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_directory($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $coach_directory = $wpdb->get_results("SELECT id, name, city, state FROM $dbs->coaches");
    return new WP_REST_Response(['coach_directory' => $coach_directory], 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function edit_coach_roster($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $roster_id = $request_body['roster_id'];
    $visibility = $request_body['visibility'];
    $team_type = $request_body['team_type'];
    $event = $request_body['event'];
    $division = $request_body['division'];
    $coach = $request_body['coach'];
    $asst_coach = $request_body['asst_coach'];
    $roster = $request_body['roster'];

    $new_roster_data = array(
      'enabled' => $visibility,
      'team_type' => $team_type,
      'event' => $event,
      'division' => $division,
      'coach' => $coach,
      'asst' => $asst_coach,
      'players' => json_encode($roster)
    );

    $where = array('id' => $roster_id);

    $update_roster = $wpdb->update($dbs->rosters, $new_roster_data, $where);
    if($update_roster === false){
      return new WP_REST_Response(['Error creating roster'], 400);
    }else{
      return new WP_REST_Response(['message' => 'Roster is successfully updated.', 'id' => $roster_id], 200);
    }
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_create_roster($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $org_id = $request_body['org_id'];
    $level = $request_body['level'];
    $team_type = $request_body['team_type'];
    $name = $request_body['name'];
    $new_team_data = array(
      'org' => $org_id,
      'level' => $level,
      'team_type' => $team_type,
      'name' => $name,
    );
    //try to insert a new record
    $add_team = $wpdb->insert( $dbs->teams, $new_team_data );
    if($add_team){
      $insert_team_id = $wpdb->insert_id;
      $new_roster_data = array(
        'org' => $org_id,
        'team' => $insert_team_id,
        'level' => $level,
        'team_type' => $team_type,
        'event' => 0,
        'name' => $name,
      );
      $add_roster = $wpdb->insert( $dbs->rosters, $new_roster_data );
      if($add_roster){
        $insert_roster_id = $wpdb->insert_id;
        // Data to be updated
        $data = array( 'roster' => $insert_roster_id );
        $where = array( 'id' => $insert_team_id );
        $data_format = array( '%d' );
        $where_format = array( '%d');
        $update_team = $wpdb->update($dbs->teams, $data, $where, $data_format, $where_format);
      }
    }else{
      return new WP_REST_Response(['Error createing roster'], 400);
    }
    return new WP_REST_Response(['message' => 'Roster is successfully created.', 'id' => $insert_roster_id], 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_roster_levels($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $user_id = $request->get_param('user_id');
    $get_org_id = get_user_meta($user_id, '_user_owns_g365', true);
    if(array_key_exists('og_ed', $get_org_id)){
      $og_id = $get_org_id['og_ed'];
      $get_org_name = g365_get_org_profile($og_id[0])->name;
      $abbr = g365_get_org_profile($og_id[0])->abbreviation;
      $roster_levels = g365_return_keys('g365_grade_key');
      $roster_level_data = ['boys' => [], 'girls' => []];
      foreach($roster_levels as $key => $value){
        if($key >= 8 && $key <= 17){
          $roster_level_data['boys'][$key] = $value;
        }
        if($key >= 40 && $key <= 47){
          $roster_level_data['girls'][$key] = $value;
        }
      }
      $roster_org_data = ['program_data' => ['id' => $og_id[0], 'name' => $get_org_name, 'abbreviation' => $abbr], 'roster_levels' => $roster_level_data];
    }
    else{
      return new WP_REST_Response(['message' => 'No program found under your account. Please claim or request access to the program.'], 400);
    }
    return new WP_REST_Response($roster_org_data, 200);
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_claim_program_request($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    // Get user and player data
    $user_data = $request->get_json_params();  
    // type, target, site_key,	email, status, owner_id, request_name, owner_data
    $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
    // Send GET request
    $response = wp_remote_post($url, array(
      'headers' => array(
        'Authorization' => $auth,
      ),
      'timeout' => 45, // Adjust timeout as needed
    ));
    $body_res = $response['body'];
    // Decode JSON string
    $data = json_decode($body_res, true);
    // Extract user_id
    $user_id = (int)$data['data']['user_id'];
    $datetime = current_time('mysql');
    $program_id = $user_data['program_id'];
    $user_info = get_userdata( $user_id );
    $user_email = $user_info->user_email;
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    } else {
      $site_tage = "SPP";
    // Encode the modified array back to JSON string
    }
    $result = $wpdb->insert(
      $dbs->claims,
      array(
        'updatetime' => $datetime,
        'type' => 2,
        'target' => $program_id,
        'site_key' => $site_tage,
        'email' => $user_email,
        'status' => 1,
        'owner_id' => $user_id,
        'owner_data' => null
      )
    );
    if($result === false){
      // Check if the last query caused a duplicate entry error
      if (strpos($wpdb->last_error, 'Duplicate entry') !== false) {
        // Handle duplicate entry error
        return new WP_Error( 'duplicate_request', esc_html__( 'Claim has already been submitted for this player.', 'my-text-domain' ), array( 'status' => 400 ) );
      }else{
        // Handle other database error
        return new WP_Error( 'critical_error', esc_html__( 'Error: ' . $wpdb->last_error, 'my-text-domain' ), array( 'status' => 400 ) );
      }
    }else{
      $claim_request_message = array('message' => 'Claim request has been successfully submitted.');
      return new WP_REST_Response($claim_request_message, 200);
    }
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function coach_claim_unclaim_program($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $program_id = $request_body['program_id'];
    $director_firstname = $request_body['director_firstname'];
    $director_lastname = $request_body['director_lastname'];
    $director_email = $request_body['director_email'];
    $director_phone = $request_body['director_phone'];
    $program_email = $request_body['program_email'];
    $program_phone = $request_body['program_phone'];
    $program_address = $request_body['program_address'];
    $city = $request_body['city'];
    $state = $request_body['state'];
    $zip = $request_body['zip'];
    $country = $request_body['country'];
    $county = $request_body['county'];
    $site_url = $request_body['site_url'];
    $instagram = $request_body['instagram'];
    $file_type = $request_body['file_type'];
    $profile_img = strtolower(str_replace(' ', '-', $request_body['name']) . '_' . $program_id. '.' .$file_type);
    $user_id = $request_body['user_id'];
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    }else{
      $site_tage = "SPP";
    }
    // Retrieve the current access data
    $current_access_query = $wpdb->prepare("
      SELECT access 
      FROM $dbs->orgs 
      WHERE id = %d
    ", $program_id);
    
    // Get the existing user meta
    $user_owns_g365 = get_user_meta($user_id, '_user_owns_g365', true);
    
    // If the meta field is not empty, append ID
    if(isset($user_owns_g365['og_ed'])){ array_push($user_owns_g365['og_ed'], $program_id); }
    else{
      // If the meta field is empty, initialize it as an array containing only ID
      $user_owns_g365['og_ed'] = array($program_id);
    }

    $current_access = $wpdb->get_var($current_access_query);

    // Decode the current access JSON data, or initialize as an empty array if null
    $current_access_data = $current_access ? json_decode($current_access, true) : [];

    // Define the new access data based on $site_tage
    $new_access_data = [$user_id];
    
    // Check if $site_tage already exists in the current access data
    if(isset($current_access_data[$site_tage])){
      // If it exists, add the new user_id only if it's not already in the array
      if(!in_array($user_id, $current_access_data[$site_tage])){
        $current_access_data[$site_tage][] = $user_id;
        update_user_meta($user_id, '_user_owns_g365', $user_owns_g365);
      }
    }else{
      // If it does not exist, set it with the new coach_id
      $current_access_data[$site_tage] = $new_access_data;
      update_user_meta($user_id, '_user_owns_g365', $user_owns_g365);
    }

    // Encode the merged access data to JSON format
    $access_json = json_encode($current_access_data);

    // Prepare the SQL query with the updated access JSON
    $sql = $wpdb->prepare("
      UPDATE $dbs->orgs
      SET 
        updatetime = CURRENT_TIMESTAMP,
        profile_img = %s, 
        director_first = %s, 
        director_last = %s, 
        director_email = %s, 
        director_phone = %s, 
        email = %s, 
        phone = %s, 
        address = %s, 
        city = %s, 
        state = %s, 
        zip = %s, 
        country = %s, 
        county = %s, 
        link = %s,
        access = %s,
        social = JSON_OBJECT('Instagram', %s)
      WHERE id = %d",
      $profile_img,
      $director_firstname,
      $director_lastname,
      $director_email,
      $director_phone,
      $program_email,
      $program_phone,
      $program_address,
      $city,
      $state,
      $zip,
      $country,
      $county,
      $site_url,
      $access_json, // Updated access field
      $instagram,
      $program_id // ID of the record to update
    );

    // Execute the query
    $result = $wpdb->query($sql);

    if($result === false){
      // Error handling
      return new WP_REST_Response(array('Error updating coach'), 400);
    }else{
      return new WP_REST_Response(array('message' => 'Program information is successfully updated.'), 200);
    }
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_coach_data($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    $coach_id = $request->get_param('coach_id');
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    } else {
      $site_tage = "SPP";
    }
    $get_org_directory = $wpdb->get_results(" SELECT id FROM $dbs->orgs WHERE enabled = 1 AND type = 1 AND JSON_CONTAINS(access, '$coach_id', '$.$site_tage') ");
    if(empty($get_org_directory->id)){
      $org_directory = $wpdb->get_results(" SELECT org.id, org.name, org.abbreviation, org.city, org.state, org.country, org.access FROM $dbs->orgs org WHERE org.enabled = 1 AND org.type = 1 ");   
    }
    foreach($org_directory as &$org){
      if($org->access){
        $org->tag = 'Request Access';
      }
    }
    return new WP_REST_Response(['coach_org_directory' => $org_directory], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function update_coach_account($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $coach_id = $request_body['coach_id'];
    $email = $request_body['email'];
    $phone = $request_body['phone'];
    $city = $request_body['city'];
    $state = $request_body['state'];
    $instagram = $request_body['instagram'];
    $sql = $wpdb->query(" UPDATE $dbs->coaches SET email = '$email', phone = '$phone', city = '$city', state = '$state', social = JSON_OBJECT('instagram', '$instagram') WHERE id = $coach_id ");
    if($sql === false){
      return new WP_REST_Response(array('Error updating coach'), 400);
    }else{
      $message = 'Coach account ID: '. $coach_id . ' is successfully updated.';
    }
    return new WP_REST_Response(['message' => $message], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function create_coach_account($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $user_id = $request_body['user_id'];
    $first_name = $request_body['first_name'];
    $last_name = $request_body['last_name'];
    $nickname = $first_name . '-' . $last_name;
    $email = $request_body['email'];
    $phone = $request_body['phone'];
    $city = $request_body['city'];
    $state = $request_body['state'];
    $instagram = $request_body['instagram'];
    $file_type = $request_body['file_type'];
    $user_owns_g365 = get_user_meta($user_id, '_user_owns_g365', true);
    
    $existing_coach_query = $wpdb->get_var($wpdb->prepare("
      SELECT COUNT(*) 
      FROM $dbs->coaches
      WHERE nickname = %s
    ", $nickname));
    
    if($existing_coach_query->count < 1){
      $sql = $wpdb->prepare("
        INSERT INTO $dbs->coaches (
          createtime, 
          updatetime, 
          account_level, 
          enabled, 
          first_name, 
          last_name, 
          nickname, 
          email, 
          phone, 
          profile_img, 
          city, 
          state, 
          social, 
          videos, 
          notes, 
          name
        )
        VALUES (
          CURRENT_TIMESTAMP,
          CURRENT_TIMESTAMP, 
          %d, 
          %d, 
          %s, 
          %s, 
          %s, 
          %s, 
          %s, 
          %s,
          %s, 
          %s, 
          JSON_OBJECT('Instagram', %s),
          NULL, 
          NULL, 
          DEFAULT
        )", 
        1, 
        1, 
        $first_name, 
        $last_name, 
        $nickname, 
        $email, 
        $phone, 
        NULL, 
        $city, 
        $state, 
        $instagram
      );
      $result = $wpdb->query($sql);
      if($result === false){
        // Error handling
        return new WP_REST_Response(array('Error inserting coach'), 400);
      }else{
        $coach_id = $wpdb->insert_id;
        $profile_img = $nickname . '_' . $coach_id . '.' . $file_type;
        $wpdb->query(" UPDATE $dbs->coaches SET profile_img = '$profile_img' WHERE id = $coach_id ");
        if(is_array($user_owns_g365)){
          if(!isset($user_owns_g365['co_ed'])){
            // Initialize 'co_ed' as an empty array if it does not exist
            $user_owns_g365['co_ed'] = [];
          }
        // Check if $coach_id is not already in the 'co_ed' array
          if(!in_array($coach_id, $user_owns_g365['co_ed'])){
            // Push $coach_id to the 'co_ed' array
            array_push($user_owns_g365['co_ed'], strval($coach_id));

            // Update the user meta with the new value
            update_user_meta($user_id, '_user_owns_g365', $user_owns_g365);
          }
        }
      }
    }else{
      return new WP_REST_Response(array('Error inserting coach'), 400);
    }
    
    return new WP_REST_Response(['id' => $coach_id], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_programs_trophy_case($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $select_year = $request->get_param('select_year');
    $org_id = $request->get_param('org_id');
    $year_selection_dropdown = year_dd_opt('most_recent_event')[5];
    $champion_data = [];
    $group_champion_data = [];
    $individual_award = [];
    
    if($select_year !== 'undefined'){
      $championship_awards = championship_award($select_year, 'org_champ', '', $org_id);
      foreach($championship_awards as $index => $championship_award){
        $event_shortname = $championship_award['event_shortname'];
        $champ_name = $championship_award['championship_team_name'];
        $award_type = array('Champions', 'Runner-Up', 'championship-and-runner-up');
        $badge_log_champ = g365_award_dir($event_shortname, $award_type[2], $award_type[0]);
        $badge_log_runner_up = g365_award_dir($event_shortname, $award_type[2], $award_type[1]);

        if(!empty($championship_award['championship_team'])){
          $logo = $badge_log_champ;
        }else{
          $logo = $badge_log_runner_up;
        }
        $champion_data['All Champions'][] = [
          'championship_team_name' => $champ_name,
          'logo' => $logo
        ];;
        $champion_data[$champ_name][] = [
          'championship_team_name' => $champ_name,
          'logo' => $logo
        ];
    }
      
    $player_data = g365_get_award(null, $select_year, $org_id, null, 2);
    $not_just_watchlist = array_filter($player_data->awards, function($val){ return $val->award_type != 8; });
    $pl_img_url = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
    $filtered_indi_data = array_map(function($item){
      return [
        'org_id' => $item->org_id,
        'player_id' => $item->player_id,
        'player_name' => $item->player_name,
        'player_nickname' => $item->player_nickname,
        'player_img' => g365_player_img_dir($item->player_nickname, $item->event_nickname, $item->player_id),
        'event_id' => $item->event_id,
        'event_shortname' => $item->event_shortname,
        'event_nickname' => $item->event_nickname,
        'award_title' => $item->award_title
      ];
    }, $not_just_watchlist);
      foreach($filtered_indi_data as $indi_data_by_event){
        $individual_award['All Awards'][] = $indi_data_by_event;
        $individual_award[$indi_data_by_event['event_shortname']][] = $indi_data_by_event;
      }
      $get_ranking_data = [];
      $team_rankings = cp_team_ranking('', $org_id, $select_year, 1);
      $t_ranking_lists = [];
      foreach($team_rankings as $index => $team_ranking){
        $t_ranking_lists[$team_ranking->start_datetime][] = $team_ranking;
      }
      foreach($t_ranking_lists as $index => $ranking_blocks){
        $ranking_date = new DateTime($ranking_blocks[0]->start_datetime);
      }
            
      foreach($t_ranking_lists as $index => $ranking_blocks){
        $ranking_date = new DateTime($ranking_blocks[0]->start_datetime);
        foreach($ranking_blocks as $ranking_block){
          $ranking_arr = str_replace(str_split('[]'),"", explode(",",$ranking_block->rankings));
          $team_id = str_replace(array(str_split('[]'), '"','[',']'), array('','','',''), explode(",", $ranking_block->is_team_id));
          $rankings = array_search($org_id, $ranking_arr)+1; 
          $is_team = get_tm_ranking($team_id[($rankings-1)])[0]->team_name;
          $org_data = g365_get_org_profile( $org_id );
          empty($is_team) ? $is_team = $org_data->name : $is_team;
          $logo = get_site_url().'/wp-content/themes/g365-press/assets/team-rankings/Ranking-'.$rankings.'.png';
          $get_ranking_data['All Rankings'][] = ['logo' => $logo, 'name' => $is_team, 'grade' => $ranking_block->ranking_type];
          $get_ranking_data[$ranking_date->format('F Y')][] = ['logo' => $logo, 'name' => $is_team, 'grade' => $ranking_block->ranking_type];
        }
      }
      
      $trophy_case_data = ['year_list' => $year_selection_dropdown, 'championship' => $champion_data, 'individual_award' => $individual_award, 'team_rankings' => $get_ranking_data];
    }else{
      $trophy_case_data = ['year_list' => $year_selection_dropdown];
    }
    return new WP_REST_Response(['trophy_case_data'=>$trophy_case_data], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_programs($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    if($request->get_param('org_id') !== 'undefined'){
      $org_id = $request->get_param('org_id');
      $orgs_data = $wpdb->get_results("SELECT * FROM $dbs->orgs WHERE type = 1 AND enabled = 1 AND id = $org_id ORDER BY id ASC");    
    }else{
      $orgs_data = $wpdb->get_results("SELECT * FROM $dbs->orgs WHERE type = 1 AND enabled = 1 ORDER BY id ASC");    
    }
    
    $filtered_org_data = array_map(function($item){
      return [
        'id' => $item->id,
        'name' => $item->name,
        'logo' => get_site_url(). '/wp-content/uploads/org-logos/'. $item->profile_img,
        'city' => $item->city,
        'state' => $item->state,
        'country' => $item->country
      ];
    }, $orgs_data);
    
    return new WP_REST_Response($filtered_org_data, 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_team_data($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $available_stat_years = g365_year_end_date('event_time', most_recent_event(2));
    $season_years_values = array_values($available_stat_years);
    // Function to transform the array
    function transform_season_stat_year($array) {
      $new_array = [];
      foreach ($array as $year) {
        $previous_year = $year - 1;
        $formatted_string = "$previous_year-$year: $year";
        $new_array[] = $formatted_string;
      }
      return $new_array;
    }
    // Transform the array
    $transformed_season_stat_year = transform_season_stat_year($season_years_values);
    $team_id = $request->get_param('team_id');
    $org_id = $request->get_param('org_id');
    $select_year = $request->get_param('select_year');
    
    if($select_year !== 'undefined' && $org_id !== 'undefined'){
      $club_team_stat_lists = g365_club_team_stat('', $team_id, $org_id, '', $select_year, 5);
      $boxscore_data = []; $filtered_boxscore_data = [];
      foreach($club_team_stat_lists as $index => $club_team_stat_list){
        $game_court = $club_team_stat_list->game_court;
        $game_time = date_format(date_create($club_team_stat_list->game_time), 'M d Y g:i A');
        $opponent = g365_club_team_stat($event_id, $team_id, $org_id, $club_team_stat_list->opponent_id, $year, $type = 3);
        $opponent_name = $opponent[0]->team_name;
        $game_result = $club_team_stat_list->game_result;
        $year = date('Y', strtotime($club_team_stat_list->game_time));
        $boxscore_data[] = ['game_time'=>$game_time, 'event_id'=>$club_team_stat_list->event_id, 'opp_name'=>$opponent_name, 'game_result'=>$game_result, 'team_id'=>$team_id, 'select_year'=>$select_year, 'game_id'=>$club_team_stat_list->game_id];
      }

    // First, count the total games for each event_id
    $total_games = [];
    foreach($boxscore_data as $item){
      $event_id = $item['event_id'];
      if(!isset($total_games[$event_id])){
        $total_games[$event_id] = 0;
      }
      $total_games[$event_id]++;
    }

    // Then, assign the game_number in reverse order
    $grouped_data = [];
    $game_numbers = [];

    foreach($boxscore_data as $item){
      $event_id = $item['event_id'];
      if(!isset($grouped_data[$event_id])){
        $grouped_data[$event_id] = [];
        $game_numbers[$event_id] = $total_games[$event_id];
      }
      $item['game_number'] = $game_numbers[$event_id];
      $grouped_data[$event_id][] = $item;
      $game_numbers[$event_id]--;
    }

    // Sort each group by game_time
    foreach($grouped_data as $event_id => &$games){
      usort($games, function ($a, $b) {
        return strtotime($a['game_time']) - strtotime($b['game_time']);
      });
    }
      $catagories_leaders = slb_by_year_query('', $select_year, 5, $org_id, $team_id);

      $team_rosters = g365_team_rosters($select_year, $team_id, $org_id, 2);
      $filtered_event_data = array_map(function($item){
        return [
          'event_id' => $item->event_id
        ];
      }, $team_rosters);
      $get_event_data = [];
      foreach($filtered_event_data as $event_data){
        $get_event_data[] = g365_get_event_data($event_data['event_id'], true);
      }

      $filtered_event_pull = array_map(function($item){
        return [
          'event_id' => $item->id,
          'event_name' => $item->name,
          'logo' => $item->logo_img
        ];
      }, $get_event_data);

      $get_fil_event_data = [];
      foreach($filtered_event_pull as $index => $filtered_event_data){
        $get_fil_event_data[] = $filtered_event_data;
        $get_fil_event_data[$index]['event_results'] = $grouped_data[$filtered_event_data['event_id']];
      }
      foreach($get_fil_event_data as &$filter_event_data){
        foreach($filter_event_data['event_results'] as &$event_data){
          $event_data['opp_name'] = str_replace('.', '', $event_data['opp_name']);
        }
      }
      $championship = championship_award($select_year, 'team_champ', $team_id, $org_id);
      $team_rankings = cp_team_ranking($team_id, $org_id, $select_year, 2);
      $indi_awards = g365_get_award(null, $select_year, $team_id, $org_id, 1);
    }
    return new WP_REST_Response(['available_stat_year'=>$transformed_season_stat_year, 'catagories_leaders'=>$catagories_leaders, 'event_data'=>$get_fil_event_data, 'championship'=>$championship, 'team_ranking'=>$team_rankings, 'individual_award'=>$indi_awards], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_program_header_data($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $org_id = $request->get_param('org_id');
    $lifetime_events = sizeof(cj_get_club_lifetime_events($org_id, 1));
    $championship_awards = cj_championship_award_get_all('org_champ', $org_id); 
    $trophies_won = 0;
    foreach($championship_awards as $championship_award){ $trophies_won++; }
    $player_data = cj_g365_get_award(null, $org_id, null, 2);
    $individual_award = 0;
    foreach($player_data->awards as $count => $player_datas){ $individual_award++; }
    $program_statistics = [['lifetime_events'=>['logo'=>'https://sportspassports.com/wp-content/uploads/2023/08/events-played-icon.png', 'data'=>$lifetime_events]], ['trophies_won'=>['logo'=>'https://dev.sportspassports.com/wp-content/uploads/2023/08/awards-earned-icon.png', 'data'=>$trophies_won]], ['individual_award'=>['logo'=>'https://dev.sportspassports.com/wp-content/uploads/2023/08/badges-earned-icon.png', 'data'=>$individual_award]]];
//     $program_info = 
    $org_data = g365_get_org_profile($org_id);
    $filtered_org_data = [
      'id' => $org_data->id,
      'url' => get_site_url(). '/wp-content/uploads/org-logos/'. $org_data->profile_img,
      'name' => $org_data->name,
      'abbreviation' => $org_data->abbreviation,
      'city' => $org_data->city,
      'state' => $org_data->state,
      'director_name' => $org_data->director_first . ' '. $org_data->director_last,
      'link' => $org_data->link,
      'social' => $org_data->social
    ];

    return new WP_REST_Response(['program_info'=>$filtered_org_data, 'program_statistics'=>$program_statistics], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_program_team($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $roster_year = year_dd_opt('cp_date_selector')[5];
    if($request->get_param('select_year') !== 'undefined'){
      $org_id = $request->get_param('org_id');
      $ros_datas = cp_team_list($org_id, $request->get_param('select_year'));
      $boys = [];
      $girls = [];

      foreach($ros_datas as $team){
        if((int)$team->team_level > 17){
          $girls[] = $team;
          $team->team_level = g365_return_keys('g365_grade_key_short')[$team->team_level];
          $team->team_name = $team->team_level . ' ' . $team->team_name;
        }else{
          $boys[] = $team;
          $team->team_level = g365_return_keys('g365_grade_key_short')[$team->team_level];
          $team->team_name = $team->team_level . ' ' . $team->team_name;
        }
      }
    }
    return new WP_REST_Response(['program_roster_year'=>$roster_year, 'teams'=>['boys'=>$boys, 'girls'=>$girls]], 200);
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_team_spotlight($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $team_spotlight = [];
    $ad = 'https://sportspassports.com/wp-content/uploads/2024/08/FallKickoffSlider-1.jpg';
    $ad_url = 'https://grassroots365.com/product/ogp-fall-kickoff/';
    
    $team_rankings = player_team_spotlight('app-team-ranking')['org_records'];
    $team_data = player_team_spotlight('app-team-ranking')['org_data'];
    $championships = player_team_spotlight('championship', ['app-championship'=>true]);
    
    foreach($team_rankings as $team_ranking){
      $team_name = $team_data->org_records[$team_ranking->rankings[0]]->name;
      $org_img = ( empty($team_data->org_records[$team_ranking->rankings[0]]->org_logo) ) ? $default_profile_img : get_site_url() . '/wp-content/uploads/org-logos/' . $team_data->org_records[$team_ranking->rankings[0]]->org_logo;
//       $team_url = get_site_url()."/club/".$team_data->org_records[$team_ranking->rankings[0]]->org_url."/teams/".$full_team_url."/".$team_custom_url;
      $club_url = get_site_url()."/club/".$team_data->org_records[$team_ranking->rankings[0]]->org_url;
      $ranking_badge = 'https://grassroots365.com/wp-content/themes/g365-press/assets/team-rankings/Ranking-1.png';
      $ranking_date = date('F Y', strtotime($team_ranking->start_datetime));
      $ranking_grade = $team_ranking->ranking_type;
      $team_spotlight[] = ['name'=>$team_name, 'ranking_badge'=>$ranking_badge, 'org_logo'=>$org_img, 'club_url'=>$club_url, 'ranking_date'=>$ranking_date, 'grade'=>$ranking_grade];
    }
    return new WP_REST_Response(['ad'=>$ad, 'ad_url'=>$ad_url, 'team_spotlight'=>['team_ranking'=>$team_spotlight, 'champion'=>$championships]], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function post_device_token($request){
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    $user_id = $request_body['user_id'];
    $new_device_token = $request_body['device_token'];
    $current_post_date = wp_date('Y-m-d H:i:s');

    // Query to check if the device token already exists
    $existing_tokens_query = $wpdb->prepare("
      SELECT JSON_UNQUOTE(JSON_EXTRACT(device_token, '$.device_token')) AS tokens 
      FROM $dbs->device_tokens 
      WHERE user_id = %d
    ", $user_id);

    $existing_tokens = $wpdb->get_var($existing_tokens_query);

    // Check if the new device token does not exists
    if($existing_tokens && strpos($existing_tokens, $new_device_token) === false){
//         return new WP_REST_Response(array('message' => 'Device token already exists'), 400);
      // Append the new device token
      $query = $wpdb->prepare("
          INSERT INTO $dbs->device_tokens 
          VALUES (DEFAULT, %s, %s, %d, %d, JSON_OBJECT('device_token', JSON_ARRAY(%s))) 
          ON DUPLICATE KEY UPDATE 
          device_token = JSON_ARRAY_APPEND(device_token, '$.device_token', %s)
      ", $current_post_date, $current_post_date, 1, $user_id, $new_device_token, $new_device_token);

      // Execute the query
      $result = $wpdb->query($query);

      // Prepare response
      if ($result === false) {
          return new WP_REST_Response(array('message' => 'Insert or update failed'), 500);
      } else {
          return new WP_REST_Response(array('message' => 'Insert or update successful'), 200);
      }
    }
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function post_missing_profile_information($request){
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $user_id = $request->get_param('user_id');
    $player_id = $request->get_param('player_id');
    $profile_missing_info = $wpdb->get_results("  
      SELECT
        16 - (
        (CASE WHEN email IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN phone IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN address IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN city IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN state IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN zip IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN country IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN birthday IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN grad_year IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN height_ft IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN social IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN club_team IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN school IS NULL THEN 0 ELSE 1 END) +
        (CASE WHEN position IS NULL THEN 0 ELSE 1 END)) AS total_missing_info,
        CONCAT_WS(', ',
        IF(email IS NOT NULL, 'email', NULL),
        IF(phone IS NOT NULL, 'phone', NULL),
        IF(address IS NOT NULL, 'address', NULL),
        IF(city IS NOT NULL, 'city', NULL),
        IF(state IS NOT NULL, 'state', NULL),
        IF(zip IS NOT NULL, 'zip', NULL),
        IF(country IS NOT NULL, 'country', NULL),
        IF(birthday IS NOT NULL, 'birthday', NULL),
        IF(grad_year IS NOT NULL, 'grad_year', NULL),
        IF(height_ft IS NOT NULL, 'height_ft', NULL),
        IF(social IS NOT NULL, 'social', NULL),
        IF(club_team IS NOT NULL, 'club_team', NULL),
        IF(school IS NOT NULL, 'school', NULL),
        IF(position IS NOT NULL, 'position', NULL)
        ) AS present_fields,
        TRIM(TRAILING ', ' FROM CONCAT(
            IF(email IS NULL, 'email, ', ''),
            IF(phone IS NULL, 'phone, ', ''),
            IF(address IS NULL, 'address, ', ''),
            IF(city IS NULL, 'city, ', ''),
            IF(state IS NULL, 'state, ', ''),
            IF(zip IS NULL, 'zip, ', ''),
            IF(country IS NULL, 'country, ', ''),
            IF(birthday IS NULL, 'birthday, ', ''),
            IF(grad_year IS NULL, 'grad_year, ', ''),
            IF(height_ft IS NULL, 'height_ft, ', ''),
            IF(social IS NULL, 'social, ', ''),
            IF(club_team IS NULL, 'club_team, ', ''),
            IF(school IS NULL, 'school, ', ''),
            IF(position IS NULL, 'position, ', '')
        )) AS missing_fields,
        CONCAT(
            (CASE WHEN email IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN phone IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN address IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN city IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN state IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN zip IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN country IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN birthday IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN grad_year IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN height_ft IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN social IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN club_team IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN school IS NULL THEN 0 ELSE 1 END) +
            (CASE WHEN position IS NULL THEN 0 ELSE 1 END),
            '/',
            16
        ) AS complete_profile_info
      FROM $dbs->players pl WHERE pl.id = $player_id AND JSON_CONTAINS(access, '$user_id', '$.SPD')
    ");
    return new WP_REST_Response($profile_missing_info, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function post_toggle_player_media($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $get_body = $request->get_body();
    $request_body = json_decode( $get_body, true );
    empty($request_body['toggle_val']) ? $toggle_val = '0' : $toggle_val = $request_body['toggle_val'];
    if($toggle_val == 1){ $label = 'is hidden.'; }else{ $label = 'is visible.'; }
    $img_id = $request_body['img_id'];
    g365_db_handler('photo-edit', 'user-toggle-app', ['toggle_val'=>$toggle_val, 'img_id'=>$img_id]);
    return new WP_REST_Response('Media id:' . $img_id . ' is updated and now ' . $label, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_order_subscription($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $user_id = $request->get_param('user_id');
//     $get_player_idaaa = '$get_player_idaaa[0]->player';
    function get_user_orders($user_id){
      global $wpdb; $dbs = json_decode(dbs());
//       // Ensure WooCommerce functions are available
      if (!function_exists('wc_get_orders')){ return; }
      $customer_orders = wc_get_orders(array('customer' => $user_id,));
      $orders_data = [];
      foreach ($customer_orders as $customer_order) {
        $order_id = $customer_order->get_id();
        $order = wc_get_order($order_id);
        $order_total = $order->get_total();
        $items = $order->get_items();
        $order_items = [];
        $get_player_id = [];
        
        $order_data_add_meta = get_post_meta( $order_id, '_order_data', true );
        if( !empty($order_data_add_meta) ){
          //explode the types, in this case we just care about 'passport' for additional processing
          $order_data_add_meta = explode( '|', $order_data_add_meta );
          //loop through the saved checkout data
          foreach( $order_data_add_meta as $dex => $data ){
            //break down the var we get
            $data = explode( ',', $data );
            //pull the key off the front
            $type = array_shift($data);
            //if we have any data, continue to process
            if( count($data) > 0 ){
              foreach($data as $player_id_list){
                $player_id_list = intval($player_id_list);
                $get_player_id[] = $wpdb->get_results("SELECT player FROM $dbs->stats WHERE id = $player_id_list");
              }
            }
          }
        }
        
        foreach ($items as $item_id => $item) {
          $product_name = $item->get_name();
          $product_quantity = $item->get_quantity();
          $order_items[] = array(
            'name' => $product_name,
            'quantity'  => $product_quantity,
            'order_date'  => $order->get_date_created()->date('Y-m-d H:i:s'),
            'player_id' => $get_player_id,
          );
        }

        $orders_data[] = array(
          'order_id' => $order_id,
          'total' => $order_total,
          'items' => $order_items,
        );
      }
      return $orders_data;
    }
    $user_orders = get_user_orders($user_id);

    return new WP_REST_Response($user_orders, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_player_qr_code($request){
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    $player_id = $request->get_param('player_id');
    $result = $wpdb->get_results(" SELECT nickname FROM $dbs->players WHERE id = $player_id ");
    $get_qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.get_site_url().'/player/'.$result[0]->nickname;
    return new WP_REST_Response($get_qr_code, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_profile_media($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $player_profile_photo = g365_img_queries('mobile-app-player-photo-view', ['pl_id'=>$player_id]);
    $player_data = g365_get_profile( $player_id, false, 0 );
    $player_profile_video = g365_img_queries('mobile-app-profile-video', ['pl_id'=>$player_id]);
    $youtube_videos = [];
    $photo_pending_data = []; $photo_approved_data = [];
    foreach($player_profile_photo['pending'] as $index => $profile_photo){
      $user_meta = get_userdata($profile_photo->user_id); $user_roles = $user_meta->roles;
      $photo_pending_data[$index]['data'] = $profile_photo;
      $photo_pending_data[$index]['link'] = g365_media_dir('admin-photo-media-g365', ['auth_user'=>$user_roles[0], 'user_id'=>$profile_photo->user_id]) . $profile_photo->img_name;
    }
    foreach($player_profile_photo['approved'] as $index => $profile_photo){
      $user_meta = get_userdata($profile_photo->user_id); $user_roles = $user_meta->roles;
      $photo_approved_data[$index]['data'] = $profile_photo;
      $photo_approved_data[$index]['link'] = g365_media_dir('admin-photo-media-g365', ['auth_user'=>$user_roles[0], 'user_id'=>$profile_photo->user_id]) . $profile_photo->img_name;
    }
    $thumbnail = 'https://sportspassports.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
    foreach( $player_data->stats as $key => $val ){
      if( !empty( $val->video ) ) {
        $youtube_videos[$key]['links'] = 'https://www.youtube.com/embed/'.$val->video.'?rel=0';
        $youtube_videos[$key]['thumbnails'] = 'http://img.youtube.com/vi/'.$val->video.'/0.jpg';
      }
    }
    return new WP_REST_Response(['player_profile_media' => ['photos' => ['pending'=>$photo_pending_data, 'approved'=>$photo_approved_data], 'videos' => ['youtube_videos' => $youtube_videos, 'upload_videos' => ['upload_video_thumbnail' => $thumbnail, 'links' => $player_profile_video]]]], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_boxscores($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $team_id = $request->get_param('team_id');
    $game_id = $request->get_param('game_id');
    $selected_year = $request->get_param('selected_year');
    $boxscore_list = [];
    
    $club_team_datas = g365_club_team_stat(null, $team_id, null, null, $selected_year, 7, array($selected_year, $game_id, 'is_box_score_only'));
    foreach($club_team_datas as $club_team_data){
      $boxscore_list[] = (json_decode($club_team_data[0]['box_score']));
    }
    foreach($boxscore_list as &$boxscore){
      $boxscore->team_name = str_replace('.', '', $boxscore->team_name);
      $boxscore->opp_name = str_replace('.', '', $boxscore->opp_name);
    }
    $team_total_stats = [
      'pts' => 0,
      'ast' => 0,
      'rbs' => 0,
      'stl' => 0,
      'blk' => 0,
      'three_pt' => 0,
    ];
    $opp_total_stats = [
      'pts' => 0,
      'ast' => 0,
      'rbs' => 0,
      'stl' => 0,
      'blk' => 0,
      'three_pt' => 0,
    ];
    foreach ($boxscore_list as $boxscore) {
      foreach ($boxscore->player_stat as $player_stat) {
        // Sum player stats
        if (isset($player_stat[0]->pl_data)) {
          foreach ($player_stat[0]->pl_data as $player) {
            $team_total_stats['pts'] += $player->player_info->stats->pts;
            $team_total_stats['ast'] += $player->player_info->stats->ast;
            $team_total_stats['rbs'] += $player->player_info->stats->rbs;
            $team_total_stats['blk'] += $player->player_info->stats->blk;
            $team_total_stats['stl'] += $player->player_info->stats->stl;
            $team_total_stats['three_pt'] += $player->player_info->stats->three_pt;
          }
        }

        // Sum opponent stats
        if (isset($player_stat[1]->opp_data)) {
          foreach ($player_stat[1]->opp_data as $opponent) {
            $opp_total_stats['pts'] += $opponent->player_info->stats->pts;
            $opp_total_stats['ast'] += $opponent->player_info->stats->ast;
            $opp_total_stats['rbs'] += $opponent->player_info->stats->rbs;
            $opp_total_stats['blk'] += $opponent->player_info->stats->blk;
            $opp_total_stats['stl'] += $opponent->player_info->stats->stl;
            $opp_total_stats['three_pt'] += $opponent->player_info->stats->three_pt;
          }
        }
      }
    }
    $boxscore_list[0]->team_total_stats = $team_total_stats;
    $boxscore_list[0]->opp_total_stats = $opp_total_stats;
    return new WP_REST_Response(['boxscore'=>$boxscore_list], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_full_team_standings($request){
  $auth = $request->get_header('Authorization');
  $get_body = $request->get_body();
  $request_body = json_decode( $get_body, true );
  if($auth){
    $select_year = $request_body['select_year'];
    $select_gp_div = $request_body['select_gp_div'];
    $select_group = $request_body['select_group'];
    $select_lv_play = $request_body['select_lv_play'];
    $brand = $request_body['brand'];
    $is_year = $request_body['is_year'];
    $is_gp_lv = $request_body['is_gp_lv'];
    $filter_options = g365_submenu_type(['post_gp_lv'=>$select_group, 'post_year'=>$select_year, 'lv_play'=>$select_lv_play, 'brand_sel'=>$brand], 19);
    // Add empty option for all level of play
    $new_lv_entry = ["" => "All Levels of Play"];
    $new_dv_entry = ["" => "All Divisions"];
    // Use array_merge to prepend the new entry
    $filter_options["lv_play_list"] = $new_lv_entry + $filter_options["lv_play_list"];
    $filter_options["all_lv_list"] = $new_dv_entry + $filter_options["all_lv_list"];
    $club_team_data = g365_team_standing(['post_year'=>$select_year, 'post_ros_dvs'=>$select_lv_play, 'post_gp_lv'=>$select_group, 'is_year'=>$is_year, 'is_dcp_only'=>false, 'is_gp_lv'=>$is_gp_lv, 'brand_sel'=>$brand])[1];
    return new WP_REST_Response(['filter_options' => $filter_options, 'club_team_data' => $club_team_data], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_team_standing_game_results($request){
  $auth = $request->get_header('Authorization');
  $get_body = $request->get_body();
  $request_body = json_decode( $get_body, true );
  if($auth){
    $select_year = $request_body['select_year'];
    $brand = $request_body['brand'];
    $team_org = $request_body['team_org'];
    $team_id = $request_body['team_id'];
    
    $ts_game_results = g365_club_team_stat(null, null, false, null, $select_year, 13, ['select_brand'=>$brand, 'select_year'=>$select_year, 'team_org'=>$team_org, 'team_id'=>$team_id]);
    foreach($ts_game_results as &$ts_game_result){
      $ts_game_result->full_team_name = str_replace('.', '', $ts_game_result->full_team_name);
      $ts_game_result->full_team_name = stat_platform_girl_level(['team_name'=>$ts_game_result->full_team_name]);
      $ts_game_result->opp_full_team_name = str_replace('.', '', $ts_game_result->opp_full_team_name);
      $ts_game_result->opp_full_team_name = stat_platform_girl_level(['team_name'=>$ts_game_result->opp_full_team_name]);
    }
    return new WP_REST_Response(['ts_game_results'=>$ts_game_results], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_team_standings($request){ //ddam
  $auth = $request->get_header('Authorization');
  $get_body = $request->get_body();
  $request_body = json_decode( $get_body, true );
  if($auth){
    $select_year = $request_body['select_year'];
    $select_division = $request_body['select_division'];
    $select_lv_play = $request_body['select_lv_play'];
    $brand = $request_body['brand'];
    $filter_options = g365_submenu_type(['post_year'=>$select_year, 'brand_sel'=>$brand], 21);
    switch($brand){
      case 3191:
      case 3:
        $is_girls_included = 'false';
        break;
      default:
        $is_girls_included = 'true';
        break;
    }
    
    $reorder_brands = [];
    foreach ($filter_options['all_br_list'] as $id => $name) {
      $reorder_brands[] = $name;
    }
    // Move item with id "3191" to the top of the array
    usort($reorder_brands, function ($a, $b) {
      return $a->id === "3191" ? -1 : 1;
    });
    $filter_options['all_br_list'] = $reorder_brands;
    
    $reorder_levels = [];
    foreach ($filter_options['lv_play_list'] as $id => $name) {
      $reorder_levels[] = ["id" => $id, "name" => $name];
    }
    $filter_options['lv_play_list'] = $reorder_levels;
    
    $reorder_divisions = [];
    foreach ($filter_options['all_lv_list'] as $id => $name) {
      $reorder_divisions[] = ["id" => $id, "name" => $name];
    }
    $filter_options['all_lv_list'] = $reorder_divisions;
    $club_team_data = g365_club_team_stat(null, null, false, null, $select_year, 12, ['select_brand'=>$brand, 'select_year'=>$select_year, 'win_loss_percent_cutoff'=>'0.5', 'max_results_per_division'=>'10', 'show_girls'=>$is_girls_included, 'division'=>$select_division, 'level_of_play'=>$select_lv_play]);
    foreach($club_team_data as &$team){
      $team->full_team_name = str_replace('.', '', $team->full_team_name);
      $team->team_nickname = str_replace('.', '', $team->team_nickname);
    }
    return new WP_REST_Response(['filter_options'=>$filter_options, 'club_team_data'=>$club_team_data], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_stat_leaderboard_spotlight($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $stat_leaderboards = player_team_spotlight('mobile-app-stat-leaderboard');
    $ad = 'https://sportspassports.com/wp-content/uploads/2024/08/EBCSlogan.jpg';
    $ad_url = 'https://elitebasketballcircuit.com/calendar/';
    $stat_leaderboards_spotlight = [];
    foreach($stat_leaderboards as $stat_leaderboard){
      empty($stat_leaderboard->ev_profile_img) ? $profile_img = g365_player_img_dir($stat_leaderboard->player_url, $stat_leaderboard->event_nickname, $stat_leaderboard->player_id) : $profile_img = $stat_leaderboard->ev_profile_img;
      $player_profile_link = get_site_url().'/player/'.$stat_leaderboard->player_url;
      $stat_leaderboards_spotlight[] = [
        'player_data' => [
          'player_name' => $stat_leaderboard->player_name,
          'avg_stat' => $stat_leaderboard->avg_stat,
          'event_name' => $stat_leaderboard->event_name,
          'event_logo' => $stat_leaderboard->event_logo,
          'event_nickname' => $stat_leaderboard->event_nickname,
          'player_id' => $stat_leaderboard->player_id,
          'profile_img' => $profile_img,
          'player_profile_link' => $player_profile_link
        ]
      ];
    }
    return new WP_REST_Response(['ad'=>$ad, 'ad_url'=>$ad_url, 'player_spotlight'=>$stat_leaderboards_spotlight], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_stat_leaderboard_new($request){ //ddam
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $organization_id = $request->get_param('organization_id'); // Default brand to G365
    if($organization_id === 'undefined'){
      $organization_id = '3191';
    }
    $selected_year = $request->get_param('selected_year');
    if($selected_year === 'undefined'){
      $selected_year = g365_date_format('', 18);
    }
    $year_data = " events.eventtime BETWEEN " . g365_date_format('', 12, ['org_list'=>$organization_id]) . " AND";
    $special_cond = ' AND events.org = '. $organization_id .' ';
    $latest_event = $wpdb->get_results("SELECT events.* FROM $dbs->events events INNER JOIN $dbs->stats st ON st.event = events.id WHERE $year_data events.enabled != 0 $special_cond AND st.event != 0 AND st.game != 0 AND events.id NOT IN (504) GROUP by events.id ORDER BY eventtime DESC, id DESC LIMIT 1");
    $latest_event = json_decode(json_encode($latest_event), true);
    $latest_event = $latest_event[0]['id'];
    
    $event_id = $request->get_param('event_id');
    if($event_id === 'undefined'){
      $event_id = $latest_event;
    }
    $stat_catagories = $request->get_param('stat_catagories');
    if($stat_catagories === 'undefined'){
      $stat_catagories = 'stat_point';
    }
    $roster_level = $request->get_param('roster_level');
    if($roster_level === 'undefined' || $roster_level === '_'){
      $roster_level = '';
    }
    $roster_division = $request->get_param('roster_division');
    if($roster_division === 'undefined' || $roster_division === '_'){
      $roster_division = '';
    }
    $organization_id_encrypted = secured_data('encrypt', ['keys'=>$organization_id]);
    if(!empty($event_id)){ $filter_options = '&filter_options=select_year='.$selected_year.'%26get_ev='.$event_id.'%26get_st_cat='.$stat_catagories.'%26roster_lv='.$roster_level.'%26roster_dv='.$roster_division; }
    else{ $filter_options = ''; }
    $admin_key = secured_data('secured-api-keys', ['keys'=>'secret_keys=bEZ5VDNkTFh5blhQbGp6UitZeGd3UT09&api_keys=SDRzVzV4RzI1ZWxSbTBsaUI3Ujk2Yzc1aE5Hd0g1TVBzeEVBSzNTcGJhenpqMGRWNFZsVEdycnF4UT09&special_cond='.$organization_id_encrypted.''])['encrypted'];
    $get_slb_url = 'https://sportspassports.com/features/v1/stat-leaderboard/?admin_keys='.$admin_key.$filter_options;
    
    $slb_response = wp_remote_get($get_slb_url, ['timeout' => 60]);

    if (is_wp_error($slb_response)) {
      $error_message = $slb_response->get_error_message();
      return new WP_Error('rest_error', esc_html__('Failed to fetch the stat leaderboard. Error: ') . $error_message, array('status' => 500));
    }

    $body = wp_remote_retrieve_body($slb_response);
    $data = json_decode($body, true);
    // Add empty option for level of play and division
    $new_entry = array(
      "level" => "",
      "name" => "All Divisions"
    );
    array_unshift($data['levels_of_play'], '');
    array_unshift($data['dv_list'], $new_entry);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return new WP_Error('rest_error', esc_html__('Error decoding JSON response.'), array('status' => 500));
    }
    // Only show latest event on default page
    if($request->get_param('selected_year') === 'undefined'){
      // Check if $data['events'] has at least one event
      if (isset($data['events']) && is_array($data['events']) && count($data['events']) > 0) {
        // Set $data['events'] to only the first event (index 0)
        $data['events'] = [$data['events'][0]]; // Wrap in an array to maintain the structure
      } else {
        // Handle the case where there are no events
        $data['events'] = []; // Or some default value
      }
      // Remove fields from the $data array
      unset($data['levels_of_play']);
      unset($data['dv_list']);
      unset($data['year_list']);
    }
    // While filtering, remove player data
//     if( $request->get_param('selected_year') !== 'undefined' && empty($roster_level) && empty($roster_division) ){
//       unset($data['stat_leaderboard_data']);
//     }
    unset($data['org_id']);
    unset($data['api_keys']);
    $data['year_list'] = g365_date_format('', 14);
    return new WP_REST_Response($data, 200);
    }else{
      return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
    }
}

function get_stat_leaderboard($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $organization_id = $request->get_param('organization_id');
    $selected_year = ($request->get_param('selected_year') === '_') ? '' : $request->get_param('selected_year');
    $year_data = " events.eventtime BETWEEN " . g365_date_format('', 12, ['org_list'=>$organization_id]) . " AND";
    $special_cond = ' AND events.org = '. $organization_id .' ';
    $latest_event = $wpdb->get_results("SELECT events.* FROM $dbs->events events INNER JOIN $dbs->stats st ON st.event = events.id WHERE $year_data events.enabled != 0 $special_cond AND st.event != 0 AND st.game != 0 AND events.id NOT IN (504) GROUP by events.id ORDER BY eventtime DESC, id DESC LIMIT 1");
    $latest_event = json_decode(json_encode($latest_event), true);
    $latest_event = $latest_event[0]['id'];
    
    $event_id = ($request->get_param('event_id') === '_') ? $latest_event : $request->get_param('event_id');
    $stat_catagories = $request->get_param('stat_catagories');
    $roster_level = ($request->get_param('roster_level') === '_') ? '' : $request->get_param('roster_level');
    $roster_division = ($request->get_param('roster_division') === '_') ? '' : $request->get_param('roster_division');
    $organization_id_encrypted = secured_data('encrypt', ['keys'=>$organization_id]);
    if(!empty($event_id)){ $filter_options = '&filter_options=select_year='.$selected_year.'%26get_ev='.$event_id.'%26get_st_cat='.$stat_catagories.'%26roster_lv='.$roster_level.'%26roster_dv='.$roster_division; }
    else{ $filter_options = ''; }
    $admin_key = secured_data('secured-api-keys', ['keys'=>'secret_keys=bEZ5VDNkTFh5blhQbGp6UitZeGd3UT09&api_keys=SDRzVzV4RzI1ZWxSbTBsaUI3Ujk2Yzc1aE5Hd0g1TVBzeEVBSzNTcGJhenpqMGRWNFZsVEdycnF4UT09&special_cond='.$organization_id_encrypted.''])['encrypted'];
    $get_slb_url = 'https://sportspassports.com/features/v1/stat-leaderboard/?admin_keys='.$admin_key.$filter_options;
    
    $slb_response = wp_remote_get($get_slb_url, ['timeout' => 60]);

    if (is_wp_error($slb_response)) {
      $error_message = $slb_response->get_error_message();
      return new WP_Error('rest_error', esc_html__('Failed to fetch the stat leaderboard. Error: ') . $error_message, array('status' => 500));
    }

    $body = wp_remote_retrieve_body($slb_response);
    $data = json_decode($body, true);
    // Add empty option for level of play and division
    $new_entry = array(
      "level" => "",
      "name" => "All Divisions"
    );
    array_unshift($data['levels_of_play'], '');
    array_unshift($data['dv_list'], $new_entry);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return new WP_Error('rest_error', esc_html__('Error decoding JSON response.'), array('status' => 500));
    }

    return new WP_REST_Response($data, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_organizations($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $get_org_lists =  slb_org_menu('slb-org-data', [], "stlb")['org_list'];
    return new WP_REST_Response($get_org_lists, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_player_camp_profile($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $player_camp_profile = g365_get_profile( $player_id, false, 0 );
    $get_camp_profile = json_decode(json_encode($player_camp_profile->stats), true);
    $filtered_camp_data = array_filter($get_camp_profile, function($obj) {
      return ($obj['event_type'] === '2' && $obj['game_id'] === '0');
    });

    foreach ($filtered_camp_data as &$obj) {
      unset($obj['id']);
      unset($obj['game_id']);
      unset($obj['event_url']);
      unset($obj['event_link']);
      unset($obj['trends']);
      unset($obj['game_handle']);
      unset($obj['event_type']);
      unset($obj['enabled']);
    }
    return new WP_REST_Response($filtered_camp_data, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_player_statistics($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $player_statistics = spp_statistics('player-statistics', ['player_id'=>$player_id]);
    $player_data = g365_get_profile( $player_id, false, 0 );
    $player_badge_data = g365_badges('player-badge-views', ['pl_id'=>$player_id])['player_badge_data'];
    $array = json_decode(json_encode($player_badge_data), true);

    if (array_key_exists('In An Event', $array)) {
      $array['In_An_Event'] = $array['In An Event'];
      unset($array['In An Event']);
    }
    // Encode the array back to a JSON string
    $new_json_string = json_decode(json_encode($array), true);
    
    $ward_list = [];
    $logo_event_played = get_site_url().'/wp-content/themes/g365-press/assets/tiny-logos/events-played-icon.png';
    $logo_badge_earned = get_site_url().'/wp-content/themes/g365-press/assets/tiny-logos/badges-earned-icon.png';
    $logo_awards_earned = get_site_url().'/wp-content/themes/g365-press/assets/tiny-logos/awards-earned-icon.png';
    $default_badge_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/Passport-P-2023.png';
    
    foreach($player_data->awards as $index => $award){
      $ward_list[$index]['event_name'] = $award->short_name;
      $ward_list[$index]['award_title'] = $award->award_title;
      $ward_list[$index]['award_img'] = $award->award_img;
      $event_shortname = $ward_list[$index]['event_name']."-".$award->award.".png"; $event_shortname = str_replace(" ", "-", $event_shortname);
      if($award->award_type == 11 || $award->award_type == 12 || $award->award_type >= 1 && $award->award_type <= 5 || $award->award_type == 9 || $player_data->awards[$dex]->award_type == 134 || $player_data->awards[$dex]->award_type == 135 || $player_data->awards[$dex]->award_type == 136 || $award->award_type = 41){
        // Hardcode award for now
        $img_url = $ward_list[$index]['award_img'].$event_shortname;

        if(strpos($img_url, 's\'') !== false || strpos($img_url, '\'s') !== false || strpos($img_url, '--') !== false || strpos($img_url, '---') !== false || strpos($img_url, '#') !== false){
          $img_url = $default_badge_img;
        }

        $ward_list[$index]['award_img'] = $img_url;
      }
    }
    $get_total_award_earned = count($ward_list);
    
    $recent_unlocked_badges = []; 
    foreach(json_decode($player_statistics['player_badge_data'][0]->badge_data) as $recent_unlocked_badge){
      $recent_unlocked_badge = json_decode(json_encode($recent_unlocked_badge), true);
      $recent_unlocked_badge_type = substr(key($recent_unlocked_badge), 0, strpos(key($recent_unlocked_badge), '_'));
      if(!empty($recent_unlocked_badge) && is_array($recent_unlocked_badge)){
        // Only get the most recent in the game stats
        if( $recent_unlocked_badge_type >= 13 && $recent_unlocked_badge_type <= 18){
          $recent_unlocked_badges[$recent_unlocked_badge_type] = $recent_unlocked_badge[key($recent_unlocked_badge)];            
        }
      }
    }
//     return new WP_REST_Response(['achievement_badges'=>$new_json_string], 200);
    return new WP_REST_Response(['player_statistics'=>[['logo'=>$logo_event_played, 'total_lifetime_events_played'=>intval($player_statistics['event_played'][0]->event_played)], ['logo'=>$logo_badge_earned, 'total_badges_earned'=>intval($player_statistics['player_badge_data'][0]->badge_earned)], ['logo'=>$logo_awards_earned, 'total_awards_earned'=>$get_total_award_earned]], 'top_achievement_badges'=>$recent_unlocked_badges, 'award_earned'=>$ward_list, 'achievement_badges'=>$new_json_string], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_player_game_results($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $event_id = $request->get_param('event_id');
    $selected_year = $request->get_param('selected_year');
    $player_game_stats = g365_pl_game_stat($player_id, $event_id, false, $selected_year, null);
    $stats_only = []; $boxscore_list = [];
    
    foreach($player_game_stats as $item){
      $stats_only['player_stats'][$item->game_id] = json_decode($item->stats);
     $club_team_datas = g365_club_team_stat(null, $item->team_id, null, null, $selected_year, 7, array($selected_year, $item->game_id, 'is_box_score_only'));
      foreach($club_team_datas as $club_team_data){
        $boxscore_list[] = (json_decode($club_team_data[0]['box_score']));
      }
    }
    foreach($boxscore_list as &$boxscores){
      $boxscores->team_name = str_replace('.', '', $boxscores->team_name);
      $boxscores->opp_name = str_replace('.', '', $boxscores->opp_name);
    }
    return new WP_REST_Response(['player_stats'=>$stats_only['player_stats'], 'boxscore'=>$boxscore_list], 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_player_events($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $event_type = $request->get_param('event_type');
    $player_id = $request->get_param('player_id');
    $selected_year = $request->get_param('selected_year');
    if($event_type === 'tournaments'){ $event_type_int = 1; }
    elseif($event_type === 'camps'){ $event_type_int = 2; }
    elseif($event_type === 'scholastic'){ $event_type_int = 4; }
    $events_stat_list = game_stat_filter($player_id, null, true, $selected_year, $event_type_int);
//     krsort($events_stat_list);
    return new WP_REST_Response($events_stat_list, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_event_average($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $event_id = $request->get_param('event_id');
    $player_event_average = avg_game_stat($player_id, $event_id);
    return new WP_REST_Response($player_event_average, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_subscription_validation($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $player_id = $request->get_param('player_id');
    $season_year = $request->get_param('season_year');
    $player_subscription_data = stat_subscription($player_id);
    $is_active = g365_passport_validation('subscription-validation', ['selected_year'=>$season_year, 'pp_data'=>$player_subscription_data[2]]);
    return new WP_REST_Response($is_active, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function check_event_subscription_available($event_id, $player_id){
  $player_subscription_data = stat_subscription($player_id);
  return $player_subscription_data;
}

function get_season_avg($request){
  $auth = $request->get_header('Authorization');
  $get_body = $request->get_body();
  if(empty($get_body)){
    $request_body = $request->get_header('Body');
    $request_body = json_decode( $request_body );
    $season_year = $request_body->season_year;
  }else{
    $request_body = json_decode( $get_body, true ); 
    $season_year = $request_body['season_year'];
  } 
  if($auth){
    $pl_season_avg = g365_season_stat($request->get_param('player_id'), $season_year, 'is_date_range', 1);
    $current_year = json_decode(json_encode($pl_season_avg), true); 
    $current_year = array_filter( $current_year, 'g365_array_filter' );
    if( !empty($pl_season_avg) ):
      $current_year = json_decode(json_encode($pl_season_avg), true); 
      $current_year = array_filter( $current_year, 'g365_array_filter' ); // Filter out empty game stat
      if(empty($current_year)){
        $avg_pt = '0'; $avg_reb = '0'; $avg_ast = '0'; $avg_stl = '0'; $avg_blk = '0'; $ave_time_dec = '0'; $avg_three_pt = '0';
      }else{
        $count = count($current_year);
        $total_pt = 0;
        $total_three_pt = 0;
        $total_reb = 0;
        $total_ast = 0;
        $total_stl = 0;
        $total_blk = 0;
        $total_pl_t = 0;
        foreach( $current_year as $index => $season_stats ){ 
          $season_stat = json_decode($season_stats['stats'], true);
          if(!empty($season_stat['time_pl'])){ $season_time_dec = g365_time_to_decimal($season_stat['time_pl']); }else{ $season_time_dec = ''; }
          $total_pt   += $season_stat['pts'];
          $total_reb  += $season_stat['rbs']; 
          $total_ast  += $season_stat['ast'];
          $total_stl  += $season_stat['stl']; 
          $total_blk  += $season_stat['blk']; 
          $total_pl_t += (int)$season_time_dec;  
          $total_three_pt   += $season_stat['three_pt'];
        }
        $avg_pt   = $total_pt/$count;
        $avg_reb  = $total_reb/$count;
        $avg_ast  = $total_ast/$count;
        $avg_stl  = $total_stl/$count;
        $avg_blk  = $total_blk/$count;
        $avg_three_pt   = $total_three_pt/$count;
        $ave_time_dec = round($total_pl_t/$count, 2);
      }
    endif;
    $catagories_avg = array('avg_pt'=>$avg_pt, 'avg_reb'=>$avg_reb, 'avg_ast'=>$avg_ast, 'avg_stl'=>$avg_stl, 'avg_blk'=>$avg_blk, 'avg_three_pt'=>$avg_three_pt);
    return new WP_REST_Response($catagories_avg, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_career_high($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    $pl_career_highs = g365_season_stat($request->get_param('player_id'), '', 'career_high', '');
    return new WP_REST_Response($pl_career_highs, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_eligible_tag($request){
  $auth = $request->get_header('Authorization');
  $get_body = $request->get_body();
  if(empty($get_body)){
    $request_body = $request->get_header('Body');
    $request_body = json_decode( $request_body );
    $player_birthday = $request_body->player_birthday;
  }else{
    $request_body = json_decode( $get_body, true );  
    $player_birthday = $request_body['player_birthday'];
  } 
  if($auth){
    $player_level = g365_age_level($player_birthday, $pl_date_now = null, $pl_time_zone = null, $level_proc = false);
    if( ($player_level > 7 && $player_level < 18) || ($player_level > 38 && $player_level < 48) || ($player_level > 60) ){
      $get_eligible_tag = g365_return_keys('g365_grade_key')[$player_level];
    }else{
      $get_eligible_tag = '';
    }
    return new WP_REST_Response($get_eligible_tag, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_badges($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $get_badges = $wpdb->get_results(" SELECT badge_name, badge_url FROM $dbs->badges WHERE enabled = 1 ");
    return new WP_REST_Response($get_badges, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_badge($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $badge_id = $request->get_param('badge_id');
    $get_badge = $wpdb->get_results(" SELECT badge_name, badge_url FROM $dbs->badges WHERE enabled = 1 AND id = $badge_id ");
    return new WP_REST_Response($get_badge, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function player_achievement($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $player_id = $request->get_param('player_id');
    $get_achievement = $wpdb->get_results(" SELECT pl_bdg.badge_id, bdg.badge_name, bdg.badge_url, pl_bdg.badge_data FROM $dbs->player_badges pl_bdg INNER JOIN $dbs->badges bdg ON pl_bdg.badge_id = bdg.id WHERE pl_bdg.enabled = 1 AND pl_bdg.player_id = $player_id ");
    if(empty($get_achievement[0]->badge_id)){
      return new WP_REST_Response(['message'=>g365_message()['no_achievements']], 200);
    }
    return new WP_REST_Response($get_achievement, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function player_file_upload($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    // Check if a file is uploaded
    if (!empty($_FILES['file'])) {
    $file = $_FILES['file'];
    $player_name = isset($_POST['player_name']) ? str_replace(' ', '-', sanitize_text_field($_POST['player_name'])) : '';
    $player_id = isset($_POST['player_id']) ? sanitize_text_field($_POST['player_id']) : '';
    $file_type = isset($_POST['file_type']) ? sanitize_text_field($_POST['file_type']) : '';
    
    // Check if directory exists, if not create it
    $upload_dir = wp_upload_dir();
    $target_dir = $upload_dir['basedir'] . '/'.$file_type.'/';
    if (!file_exists($target_dir)) {
      wp_mkdir_p($target_dir);
    }

    // Generate unique filename
    $file_name = sanitize_file_name($file['name']);
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'heic'); // Added 'heic' extension
    
    // Check if the file type is allowed
    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        return new WP_Error('file_type_error', 'Only JPG, JPEG, PNG, GIF, and HEIC files are allowed.', array('status' => 400));
    }

    $saved_file_name = $player_name . '_' . $player_id . '.' . $file_extension;
    $target_file = $target_dir . $saved_file_name;

    // Move uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
      // File uploaded successfully
      $response = array(
        'success' => true,
        'message' => 'File uploaded successfully.',
        'file_name' =>  $saved_file_name,
        'file_url' => $upload_dir['baseurl'] . '/'.$file_type.'/' . $saved_file_name
      );
      return new WP_REST_Response($response, 200);
    }else{
      // Error uploading file
      return new WP_Error('upload_error', 'Error uploading file.', array('status' => 500));
    }
//       return $player_name;
    }
    else{
      // No file uploaded
      return new WP_Error('no_file', 'No file uploaded.', array('status' => 400));
    }
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function player_file_upload_rn($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    // Check if a file is uploaded
    if (!empty($_FILES['file'])) {
    $file = $_FILES['file'];
    $player_name = isset($_POST['player_name']) ? str_replace(' ', '-', sanitize_text_field($_POST['player_name'])) : '';
    $player_id = isset($_POST['player_id']) ? sanitize_text_field($_POST['player_id']) : '';
    $file_type = isset($_POST['file_type']) ? sanitize_text_field($_POST['file_type']) : '';
    $user_id = isset($_POST['user_id']) ? ($_POST['user_id']) : '';
    $claimed_pl = [];
    if(!empty($user_id)){ // Player video and photo only
       // Check if directory exists, if not create it
        $lmf = get_site_url(). '/wp-content/themes/g365-press/assets/photo-additions/uploads/';
        $upload_dir = WP_CONTENT_DIR;
        $target_dir = $upload_dir . '/themes/g365-press/assets/photo-additions/uploads/';
        if (!file_exists($target_dir)) { wp_mkdir_p($target_dir); }
      
        // Generate unique filename
        $file_name = sanitize_file_name($file['name']);
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'heic', 'mp4', 'mov', 'quicktime');

        // Check if the file type is allowed
        if(!in_array(strtolower($file_extension), $allowed_extensions)){
            return new WP_Error('file_type_error', 'Only JPG, JPEG, PNG, GIF, HEIC, MP4, MOV and QUICKTIME files are allowed.', array('status' => 400));
        }

        $saved_file_name = $player_name . '_' . $player_id . '.' . $file_extension;
        $target_file = $target_dir . $saved_file_name;
        $claimed_pl['pl_ed'] = [$player_id];
      
        $remote_saved_img_name = g365_remote_img_upload(['image_name'=>$saved_file_name, 'proc_type'=>'user', 'lmf'=>$lmf, 'auth_user'=>$user_id, 'file_type'=>$file_extension], 'file-url');
        if(in_array(strtolower($file_extension), array('jpg', 'jpeg', 'png', 'gif', 'heic'))){
          $media_location_file = 'player-photo-upload';
        }else{
          $media_location_file = 'player-video-upload';
        }
        $user_media_url = 'https://media.grassroots365.com/'.$media_location_file.'/user/' .$user_id . '/';
        // Move uploaded file to the target directory
        if(move_uploaded_file($file['tmp_name'], $target_file)){
          // File uploaded successfully
          $response = array(
            'success' => true,
            'message' => 'File uploaded successfully.',
            'file_name' =>  $remote_saved_img_name,
            'file_url' => $user_media_url . $remote_saved_img_name
          );

          g365_photo_upload_process_type('user', ['img_name'=>$saved_file_name, 'lmf'=>$lmf, 'auth_user_id'=>$user_id, 'claimed_pl'=>$claimed_pl, 'file_type'=>$file_extension]);
          delete_file(dirname(__FILE__, 3).'/themes/g365-press/assets/photo-additions/uploads/', ($saved_file_name.'.'.$file_extension));
          return new WP_REST_Response($response, 200);
        }else{
          // Error uploading file
          return new WP_Error('upload_error', 'Error uploading file.', array('status' => 500));
        }
      
      }else{
        // Check if directory exists, if not create it
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['basedir'] . '/'.$file_type.'/';
        if (!file_exists($target_dir)) { wp_mkdir_p($target_dir); }
      
        // Generate unique filename
        $file_name = sanitize_file_name($file['name']);
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'heic');

        // Check if the file type is allowed
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            return new WP_Error('file_type_error', 'Only JPG, JPEG, PNG, GIF, and HEIC files are allowed.', array('status' => 400));
        }

        $saved_file_name = $player_name . '_' . $player_id . '.' . $file_extension;
        $target_file = $target_dir . $saved_file_name;

        // Move uploaded file to the target directory
        if(move_uploaded_file($file['tmp_name'], $target_file)){
          // File uploaded successfully
          $response = array(
            'success' => true,
            'message' => 'File uploaded successfully.',
            'file_name' =>  $saved_file_name,
            'file_url' => $upload_dir['baseurl'] . '/'.$file_type.'/' . $saved_file_name
          );
          return new WP_REST_Response($response, 200);
        }else{
          // Error uploading file
          return new WP_Error('upload_error', 'Error uploading file.', array('status' => 500));
        }
      }
    }
    else{
      // No file uploaded
      return new WP_Error('no_file', 'No file uploaded.', array('status' => 400));
    }
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function player_position($request){
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb; $dbs = json_decode(dbs());
    $get_positions = $wpdb->get_results(" SELECT id, name, abbr FROM $dbs->positions ");
    return new WP_REST_Response($get_positions, 200);
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function user_reset_password($request){
  $auth = $request->get_header('Authorization');
  $body = file_get_contents('php://input');
  $request_body = json_decode($body, true);
  if($auth){
    if( isset( $request_body['user_email'] ) ){
      $user_email = sanitize_email( $request_body['user_email'] );
      $user_data = get_user_by( 'email', $user_email );
      if( $user_data ){
        $user_login = $user_data->user_login;
        $reset_key = get_password_reset_key( $user_data );
        $reset_url = esc_url( add_query_arg( array( 'action' => 'rp', 'key' => $reset_key, 'login' => rawurlencode( $user_login ) ), wp_login_url() ) );
        $email_subject = 'Password Reset';
        $email_message = 'Click the following link to reset your password: ' . $reset_url;
        $headers = array(
          'Content-Type: text/html; charset=UTF-8',
          'From: Sports Passports <info@sportspassports.com>',
          'Reply-To: Do Not Reply <noreply@sportspassports.com>',
        );
        
        wp_mail( $user_email, $email_subject, $email_message, $headers );
        $reset_password_message = array('message' => 'An email has been sent with instructions to reset your password.');
        return new WP_REST_Response($reset_password_message, 200);
      }else{
        return new WP_Error( 'no_record_found', esc_html__( 'Email provided does not exist in our system.', 'my-text-domain' ), array( 'status' => 404 ) );
      }
    }
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function user_claim_data($request){
  // Check header for authorization
  $auth = $request->get_header('Authorization');
  global $wpdb; $dbs = json_decode(dbs());
  if($auth){
    // Get user and player data
    $user_data = $request->get_json_params();  
    // type, target, site_key,	email, status, owner_id, request_name, owner_data
    $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
    // Send GET request
    $response = wp_remote_post($url, array(
      'headers' => array(
        'Authorization' => $auth,
      ),
      'timeout' => 45, // Adjust timeout as needed
    ));
    $body_res = $response['body'];
    // Decode JSON string
    $data = json_decode($body_res, true);
    // Extract user_id
    $user_id = (int)$data['data']['user_id'];
    $datetime = current_time('mysql');
    $player_id = $request->get_param('player_id');
    $name = $user_data['name'];
    $phone = $user_data['phone'];
    $birthday = $user_data['birthday'];
    $relation = $user_data['relation'];
    $owner_data = array('name' => $name, 'phone' => $phone, 'birthday' => $birthday, 'relation' => $relation);
    $json_owner_data = json_encode($owner_data);
    $user_info = get_userdata( $user_id );
    $user_email = $user_info->user_email;
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    } else {
      $site_tage = "SPP";
    // Encode the modified array back to JSON string
    }
    $result = $wpdb->insert(
      $dbs->claims,
      array(
        'updatetime' => $datetime,
        'type' => 1,
        'target' => $player_id,
        'site_key' => $site_tage,
        'email' => $user_email,
        'status' => 1,
        'owner_id' => $user_id,
        'owner_data' => $json_owner_data
      )
    );
    if($result === false){
      // Check if the last query caused a duplicate entry error
      if (strpos($wpdb->last_error, 'Duplicate entry') !== false) {
        // Handle duplicate entry error
        return new WP_Error( 'duplicate_request', esc_html__( 'Claim has already been submitted for this player.', 'my-text-domain' ), array( 'status' => 400 ) );
      }else{
        // Handle other database error
        return new WP_Error( 'critical_error', esc_html__( 'Error: ' . $wpdb->last_error, 'my-text-domain' ), array( 'status' => 400 ) );
      }
    }else{
      $claim_request_message = array('message' => 'Claim request has been successfully submitted.');
      return new WP_REST_Response($claim_request_message, 200);
    }
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

function update_user_data($request) {
  $auth = $request->get_header('Authorization');
  if($auth){
    $user_data = $request->get_json_params(); // Assuming JSON format for update data
    // URL to which the POST request will be sent
    $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
    // Send GET request
    $response = wp_remote_post($url, array(
      'headers' => array(
        'Authorization' => $auth,
      ),
      'timeout' => 45, // Adjust timeout as needed
    ));
    $body_res = $response['body'];
    // Decode JSON string
    $data = json_decode($body_res, true);
    // Extract user_id
    $user_id = (int)$data['data']['user_id'];
    if(!$user_id || empty($user_data)){
      return new WP_Error('invalid_data', 'Invalid user ID or update data', array('status' => 400));
    }

    // Update user profile data
    $updated = wp_update_user(array(
      'ID' => $user_id,
      'user_login' => isset($user_data['user_login']) ? $user_data['user_login'] : '',
      'user_email' => isset($user_data['user_email']) ? $user_data['user_email'] : '',
    ));

    if (is_wp_error($updated)) {
      return new WP_Error('update_failed', $updated->get_error_message(), array('status' => 500));
    }
    // Return success response
    return array(
      'message' => 'User profile updated successfully',
    );
  }else{ return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) ); }
}

// Handle the request for the user-data endpoint
function get_user_data($request){
  global $wpdb;
  $dbs = json_decode(dbs());
  $auth = $request->get_header('Authorization');
  if(strpos(get_site_url(), 'dev.') !== false){
    $site_tage = "SPD";
  } else {
    $site_tage = "SPP";
  // Encode the modified array back to JSON string
  }
  if($auth){
    // URL to which the POST request will be sent
    $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
    // Send GET request
    $response = wp_remote_post($url, array(
      'headers' => array(
        'Authorization' => $auth,
      ),
      'timeout' => 45, // Adjust timeout as needed
    ));
    $body_res = $response['body'];
    // Decode JSON string
    $data = json_decode($body_res, true);
    // Extract user_id
    $user_id = (int)$data['data']['user_id'];
    $get_user_own_data = $wpdb->get_results(" SELECT id FROM $dbs->players WHERE JSON_CONTAINS(JSON_EXTRACT(access, '$.$site_tage'), '$user_id') ");
    // Check if claim players' passport subscription is active or not
    if(!empty($get_user_own_data)){
      $pp_player_list = array();
      foreach($get_user_own_data as $player_id){
        $pp_data = stat_subscription($player_id->id);
        if(!empty($pp_data[2])){
          $is_subscription = g365_passport_validation('subscription-validation', ['selected_year'=>date('Y'), 'pp_data'=>$pp_data[2]]);
        }else{
          $is_subscription = 'false';
        }
        if($is_subscription === 'true'){
          $subscription_tag = 'Active';
        }else{
          $subscription_tag = 'Inactive';
        }
        $pp_player_list[$player_id->id] = $subscription_tag;
      }
    }else{
      $pp_player_list = 'false';
    }
    $is_available_claim_pl = 'false';
    if(!empty($get_user_own_data[0])){ $is_available_claim_pl = 'true'; }
    if(!empty($user_id)){
      $user_id = intval($user_id);
      $user_data = get_userdata($user_id);
      if($user_data){
        $response = array(
          'user_id'    => $user_data->ID,
          'user_login' => $user_data->user_login,
          'user_email' => $user_data->user_email,
          'user_role' => $user_data->roles,
          'owned_player' => $is_available_claim_pl,
          'subscription' => $pp_player_list
        );
        wp_send_json($response);
      }else{
        $response = array(
          'success' => false,
          'data'    => 'User not found.'
        );
        wp_send_json($response, 404);
      }
    }else{
      $response = array(
        'success' => false,
        'data'    => 'User ID parameter is missing.'
      );
      wp_send_json($response, 400);
    }
  }else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function update_player_data($request){
  // Check header authentication
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());
    // Get user own data
    // URL to which the POST request will be sent
    $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
    // Send GET request
    $response = wp_remote_post($url, array(
      'headers' => array(
        'Authorization' => $auth,
      ),
      'timeout' => 45, // Adjust timeout as needed
    ));
    $body_res = $response['body'];
    // Decode JSON string
    $data = json_decode($body_res, true);
    // Extract user_id
    $user_id = (int)$data['data']['user_id'];
    
    // Get player id
    $player_id = $request->get_param('player_id');
    // Get player's access
    $player_access = $wpdb->get_results("SELECT access FROM $dbs->players WHERE enabled = 1 AND id = $player_id");
    // Allow user that has own the player(s) only
    // Decode JSON string to PHP array
    $array = json_decode($player_access[0]->access, true);
    if(strpos(get_site_url(), 'dev.') !== false){
      $site_tage = "SPD";
    } else {
      $site_tage = "SPP";
      // Encode the modified array back to JSON string
    }
    // Check if the array contains a certain number
    if (in_array($user_id, $array[$site_tage])){
      // Perform update
      $body = file_get_contents('php://input');
      $player_data = json_decode($body, true);
      // Retrieve the data from the request
      $email = isset($player_data['email']) ? $player_data['email'] : null;
      $phone = isset($player_data['phone']) ? $player_data['phone'] : null;
      $address = isset($player_data['address']) ? $player_data['address'] : null;
      $city = isset($player_data['city']) ? $player_data['city'] : null;
      $state = isset($player_data['state']) ? $player_data['state'] : null;
      $zip = isset($player_data['zip']) ? $player_data['zip'] : null;
      $country = isset($player_data['country']) ? $player_data['country'] : null;
      $height_ft = isset($player_data['height_ft']) ? $player_data['height_ft'] : null;
      $height_in = isset($player_data['height_in']) ? $player_data['height_in'] : null;
      $weight = isset($player_data['weight']) ? $player_data['weight'] : null;
      $position = isset($player_data['position']) ? $player_data['position'] : null;
      $existing_social = json_decode($_POST['social'], true);
      $existing_notes_jersey_size = json_decode($_POST['notes'], true);
      // Update the specific key(s) in the associative array
      $existing_social['Instagram'] = $player_data['social']['Instagram'];
      $existing_notes_jersey_size['jersey_size'] = $player_data['notes']['jersey_size'];
//       // Encode the associative array back to JSON format
      $social = json_encode($existing_social);
      $notes = json_encode($existing_notes_jersey_size);
      $club_team = isset($player_data['club_team']) ? $player_data['club_team'] : null;
      $school = isset($player_data['school']) ? $player_data['school'] : null;
      $gpa = isset($player_data['gpa']) ? $player_data['gpa'] : null;
      $sat = isset($player_data['sat']) ? $player_data['sat'] : null;
      $act = isset($player_data['act']) ? $player_data['act'] : null;
      $get_update_data = array();
      $fields_to_update = array(
        'email', 
        'phone',
        'address', 
        'city', 
        'state', 
        'zip', 
        'country',
        'height_ft', 
        'height_in', 
        'weight',
        'position',
        'social',
        'notes',
        'club_team',
        'school',
        'gpa',
        'sat',
        'act'
      );
      foreach ($fields_to_update as $field) {
        if (isset(${$field})) {
          $get_update_data[$field] = ${$field};
        }
      }
      $results = $wpdb->update(
        $dbs->players,
          $get_update_data,
          array(
            'id' => $player_id,
            'enabled' => 1
          )
      );
      $update_data = array('message' => 'Player ID: '.$player_id.' profile is updated.');
      return new WP_REST_Response($update_data, 200);
    }
    else{
      return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have access to this player.', 'my-text-domain' ), array( 'status' => 401 ) );
    }
  }
  else{
    return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
  }
}

function get_players_data($request){
  // Check if Authorization header is present in the request
  $auth = $request->get_header('Authorization');
  if ($auth) {
    global $wpdb;
    $dbs = json_decode(dbs());
    
    // Get search term from query parameters (if any)
    $search = $request->get_param('search');
    
    // Validate that the search input is at least 2 characters long
    if (strlen($search) <= 1) {
      return new WP_Error('rest_invalid_param', esc_html__('Search term must be at least 2 characters long.', 'my-text-domain'), array('status' => 400));
    }

    // Pagination parameters
    $page = (int) $request->get_param('page') ?: 1;
    $per_page = (int) $request->get_param('per_page') ?: 10; // Default to 10 items per page
    $offset = ($page - 1) * $per_page;

    // SQL query with search and pagination
    $results = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT id, first_name, last_name, nickname, profile_img, address, city, state, country
         FROM $dbs->players
         WHERE enabled = 1 AND (first_name LIKE %s OR last_name LIKE %s OR nickname LIKE %s)
         ORDER BY id DESC
         LIMIT %d OFFSET %d",
        '%' . $wpdb->esc_like($search) . '%',
        '%' . $wpdb->esc_like($search) . '%',
        '%' . $wpdb->esc_like($search) . '%',
        $per_page,
        $offset
      )
    );

    // Get total number of matching players for pagination info
    $total = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM $dbs->players WHERE enabled = 1 AND (first_name LIKE %s OR last_name LIKE %s OR nickname LIKE %s)",
        '%' . $wpdb->esc_like($search) . '%',
        '%' . $wpdb->esc_like($search) . '%',
        '%' . $wpdb->esc_like($search) . '%'
      )
    );

    // Pagination data
    $total_pages = ceil($total / $per_page);

    // Prepare response
    $data = array(
      'players' => $results,
      'pagination' => array(
        'total' => (int) $total,
        'total_pages' => (int) $total_pages,
        'current_page' => (int) $page,
        'per_page' => (int) $per_page
      )
    );

    return new WP_REST_Response($data, 200);
  } else {
    return new WP_Error('rest_forbidden', esc_html__('Authentication required.', 'my-text-domain'), array('status' => 401));
  }
}

function get_player_data($request){
  // Check if Authorization header is present in the request
  $auth = $request->get_header('Authorization');
  if($auth){
    global $wpdb;
    $dbs = json_decode(dbs());

    // Get the player ID from the request
    $player_id = $request->get_param('player_id');

    // Fetch player data with additional fields
    $results = $wpdb->get_results($wpdb->prepare(
      "SELECT pl.id, pl.first_name, pl.last_name, pl.nickname, pl.birthday, verified, pl.profile_img, 
              pl.address, pl.city, pl.state, pl.zip, pl.country, pl.email, pl.phone, 
              pl.grad_year, org.name AS club, pl.height_ft, pl.height_in, pl.weight, 
              pos.abbr AS position, pl.notes, pl.school, pl.gpa, pl.sat, pl.act 
       FROM $dbs->players pl 
       LEFT JOIN $dbs->positions pos ON pl.position = pos.id 
       LEFT JOIN $dbs->orgs org ON org.id = pl.club_team 
       WHERE pl.enabled = 1 AND pl.id = %d 
       ORDER BY pl.id DESC", 
      $player_id
    ));

    // Check if player data exists
    if($results){
      // Get the season year data for the player
      $season_year = pl_stat_season_options(array($player_id))[1];
      $season_years_values = array_values($season_year);

      // Function to transform the season stat year array
      function transform_season_stat_year($array) {
        $new_array = [];
        foreach ($array as $year) {
          $previous_year = $year - 1;
          $formatted_string = "$previous_year-$year: $year";
          $new_array[] = $formatted_string;
        }
        return $new_array;
      }

      // Transform the season year array
      $transformed_array = transform_season_stat_year($season_years_values);

      // Convert the player result to an associative array and add season stat year
      $player_data = json_decode(json_encode($results[0]), true);
      $player_data['season_stat_year'] = $transformed_array;
      
      switch($player_data['verified']){
        case 0:
          $player_data['verified'] = 'Unverified';
          break;
        case 1:
          $player_data['verified'] = 'Unverified';
          break;
        case 2:
          $player_data['verified'] = 'Verified';
          break;
        case 3:
          $player_data['verified'] = 'Verified';
          break;
      }
      // Prepare the response data
      $data = array('message' => $player_data);
      return new WP_REST_Response($data['message'], 200);
    }else{
      // Return a 404 if player data not found
      return new WP_Error('no_player', 'Player not found', array('status' => 404));
    }
  }else{
    // Return a 401 if Authorization header is missing
    return new WP_Error('rest_forbidden', esc_html__('Authentication required.', 'my-text-domain'), array('status' => 401));
  }
}

function post_player_data($request){
    // Check if Authorization header is present in the request
    $auth = $request->get_header('Authorization');
    if($auth){
      global $wpdb;
      $dbs = json_decode(dbs());
      $body = file_get_contents('php://input');
      $player_data = json_decode($body, true);

      $datetime = current_time('mysql');
      $first_name = $player_data['first_name'];
      $last_name = $player_data['last_name'];
      $names = $first_name .' '. $last_name;
      $nickname = str_replace(' ', '-', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $names)));
      $email = $player_data['email'];
      $phone = $player_data['phone'];
      $profile_img = null;
      $address = $player_data['address'];
      $city = $player_data['city'];
      $state = $player_data['state'];
      $zip = $player_data['zip'];
      $country = $player_data['country'];
      $birthday = $player_data['birthday'];
      $grad_year = $player_data['grad_year'];
      $height_ft = $player_data['height_ft'];
      $height_in = $player_data['height_in'];
      $weight = $player_data['weight'];
      $position = $player_data['position'];
      $social = json_encode($player_data['social']);
      $notes = json_encode($player_data['notes']);
      $videos = $player_data['videos'];
      $club_team = $player_data['club_team'];
      $school = $player_data['school'];
      $gpa = $player_data['gpa'];
      $sat = $player_data['sat'];
      $act = $player_data['act'];
      // Check if a player with the same first_name and last_name already exists
      $existing_player = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $dbs->players WHERE first_name = %s AND last_name = %s", $first_name, $last_name)
      );
      // If a duplicate player is found, return an error message
      if ($existing_player) {
        return new WP_Error( 'duplicate_player', 'Player with the same first name and last name already exists.', array( 'status' => 400 ) );
      }

      /** Create player access point **/
      // URL to which the POST request will be sent
      $url = get_site_url().'/wp-json/jwt-auth/v1/token/validate';
      // Send GET request
      $response = wp_remote_post($url, array(
        'headers' => array(
          'Authorization' => $auth,
        ),
        'timeout' => 45, // Adjust timeout as needed
      ));
      $body_res = $response['body'];
      // Decode JSON string
      $data = json_decode($body_res, true);
      // Extract user_id
      $user_id = (int)$data['data']['user_id'];
      if(strpos(get_site_url(), 'dev.') !== false){
        $site_tage = "SPD";
      } else {
        $site_tage = "SPP";
      // Encode the modified array back to JSON string
      }
      $user_player_access = array($site_tage => array($user_id));
      $access_val = json_encode($user_player_access);
      /** End create player access point **/
      $wpdb->insert(
        $dbs->players,
        array(
          'createtime' => $datetime,
          'updatetime' => $datetime,
          'enabled' => '1',
          'first_name' => $first_name,
          'last_name' => $last_name,
          'nickname' => $nickname,
          'email' => $email,
          'phone' => $phone,
          'profile_img' => $profile_img,
          'address' => $address,
          'city' => $city,
          'state' => $state,
          'zip' => $zip,
          'country' => $country,
          'birthday' => $birthday,
          'verified' => '0',
          'grad_year' => $grad_year,
          'height_ft' => $height_ft,
          'height_in' => $height_in,
          'weight' => $weight,
          'position' => $position,
          'social' => $social, // {"Instagram": "baller_aadem"}
          'notes' => $notes, // {"jersey_size": "A_Sm"} , A_Md, A_Lg, A_XL
          'videos' => $videos,
          'club_team' => $club_team,
          'school' => $school,
          'gpa' => $gpa,
          'sat' => $sat,
          'act' => $act,
          'access' => $access_val,
        )
      );
      // $player_id = $wpdb->insert_id;
      // Check for database errors
      if($wpdb->last_error){
        // Log or echo the error for debugging
        return new WP_Error( 'database_error', esc_html__( 'Database error:' .$wpdb->last_error, 'my-text-domain' ), array( 'status' => 401 ) );
      }else{
        // No errors, proceed to get the inserted player ID
        $player_id = $wpdb->insert_id;
        if($player_id === 0){
          // $wpdb->insert_id returned 0, handle this case
          // Log or echo an error message for debugging
          return new WP_Error( 'database_error', esc_html__( 'Failed to get inserted Player ID', 'my-text-domain' ), array( 'status' => 401 ) );
        }
      }

      // Send email to the player
      $subject = 'Account Created Successfully';
      $message = 'Dear ' . $first_name . ',<br><br>Your account has been created successfully.<br><br>Thank you.';
      $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Sports Passports <info@sportspassports.com>',
        'Reply-To: Do Not Reply <noreply@sportspassports.com>',
      );
      wp_mail($email, $subject, $message, $headers);
      
      // Get the existing user meta
      $user_owns_g365 = get_user_meta($user_id, '_user_owns_g365', true);
      // If the meta field is not empty, append ID
      if(!empty($user_owns_g365)){ array_push($user_owns_g365['pl_ed'], $player_id); }
      else{
        // If the meta field is empty, initialize it as an array containing only ID
        $user_owns_g365['pl_ed'] = array($player_id);
      }
      // Update the user meta field with the modified array
      update_user_meta($user_id, '_user_owns_g365', $user_owns_g365);
      
      return new WP_REST_Response( array( 'message' => ''.$player_data['first_name'].' '.$player_data['last_name'].' profile saved successfully', 'player_id' => $player_id ), 200 );
    } else {
        return new WP_Error( 'rest_forbidden', esc_html__( 'Authentication required.', 'my-text-domain' ), array( 'status' => 401 ) );
    }
}

function react_wp_api_callback(WP_REST_Request $request){
  global $wpdb;
  $dbs = json_decode(dbs());
  $post_data = $request->get_json_params();
  $get_post_data = $request['get_data'];
  $player_stats = isset($post_data['player_stats']) ? $post_data['player_stats'] : '';
  !empty($post_data['game_id']) ? $game_id = $post_data['game_id'] : $game_id = '';
  !empty($post_data['event_id']) ? $event_id = $post_data['event_id'] : $event_id = '';
  !empty($post_data['home_score']) ? $home_score = $post_data['home_score'] : $home_score = '';
  !empty($post_data['away_score']) ? $away_score = $post_data['away_score'] : $away_score = '';
  $exposure_game_data = '{"Id": "'.$game_id.'", "AwayScore": "'.$away_score.'", "HomeScore": "'.$home_score.'", "AwayTeamScore": "'.$away_score.'", "HomeTeamScore": "'.$home_score.'"}';
  $data_type = $request['data_type'];
  
  $player_stat_list = array();
  if(!empty($player_stats)){
    foreach($player_stats as $player_stat){
      $player_id = intval($player_stat['player_id']);
      $event_id = intval($player_stat['event_id']);
      $game_id = intval($player_stat['game_id']);
      $stat_catagories = array();
      foreach(stat_catagories('stat-catagories-v2') as $index => $stat_types){
        $stat_catagory = intval($player_stat['stats'][$index]);
        $stat_catagories[] = " '".$index."', $stat_catagory ";
      }
      $stat_catagories = implode(',', $stat_catagories);
      $player_stat_list[] = "(DEFAULT, $player_id, $event_id, $game_id, DEFAULT, 1, 0, NULL, NULL, NULL, NULL, JSON_OBJECT($stat_catagories), NULL, NULL)";
    }
  }
  $player_stat_lists = implode(', ', $player_stat_list);
  switch($data_type){
    case 'post_player_stats':
      $sql = $wpdb->query(" INSERT INTO $dbs->stats VALUES $player_stat_lists ON DUPLICATE KEY UPDATE stats=VALUES(stats) ");
      $data = array('message' => $sql);
      return new WP_REST_Response($data, 200);
      break;
    case 'exposure':
      !empty($post_data['event_id']) ? $event_id = $post_data['event_id'] : $event_id = '';
      $get_org_id = $wpdb->get_results("SELECT org FROM $dbs->events WHERE id = $event_id");
      $get_org_id = $get_org_id[0]->org;
      if($get_org_id === '7474'){
        return post_game_data_to_SCIBCA_exposure( 'api/v1/games', $game_id, $event_id, $exposure_game_data, ['remote_exposure'=>true, 'type'=>'spp-app']);      
      }else{
        return post_game_data_to_exposure( 'api/v1/games', $game_id, $event_id, $exposure_game_data, ['remote_exposure'=>true, 'type'=>'spp-app']);      
      }
      break;
    case 'game_score':
      $sql = $wpdb->query(" UPDATE $dbs->games SET home_team_score = $home_score, away_team_score = $away_score WHERE exposure_game_id = $game_id ");
      $data = array('message' => $sql);
      return new WP_REST_Response($data, 200);
      break;
  }
}
function react_api_callback($request){
  global $wpdb;
  $dbs = json_decode(dbs());
  $data_type = $request['data_type'];
  !empty($request['param']) ? $param = $request['param'] : $param = '';
  if(!empty($request['year'])){
    if($request['year']  == '_'){ $year = ''; }else{ $year = $request['year']; }
  }else{ $year = ''; }
  if(!empty($request['event_id'])){
    if($request['event_id']  == '_'){ $event_id = ''; }else{ $event_id = $request['event_id']; }
  }else{ $event_id = ''; }
  if(!empty($request['org_type'])){
    if($request['org_type'] == '_'){ $org_type = ''; }else{ $org_type = $request['org_type']; }
  }else{ $org_type = ''; }
  if(!empty($request['stat_type'])){
    if($request['stat_type']  == '_'){ $stat_type = ''; }else{ $stat_type = $request['stat_type']; }
  }else{ $stat_type = ''; }
  if(!empty($request['lv_type'])){
    if($request['lv_type']  == '_'){ $lv_type = ''; }else{ $lv_type = $request['lv_type']; }
  }else{ $lv_type = ''; }
  if(!empty($request['division'])){
    if($request['division']  == '_'){ $division = ''; }else{ $division = $request['division']; }
  }else{ $division = ''; }
  switch($data_type){
    case 'events':
      $results = $wpdb->get_results("SELECT * FROM $dbs->events WHERE enabled = 1 AND eventtime >= DATE_SUB(NOW(), INTERVAL 10 WEEK) ORDER BY eventtime DESC");
//       if(empty($results->id)){
//         $results = $wpdb->get_results("SELECT * FROM $dbs->events WHERE enabled = 1 AND eventtime >= DATE_SUB(NOW(), INTERVAL 24 MONTH) ORDER BY eventtime DESC");  
//       }
      $data = array('message' => $results);
      return new WP_REST_Response($data, 200);
      break;
    case 'games':
      if(!empty($request['event_game'])){
        if($request['event_game']  == '_'){ $event_game = ''; }else{ $event_game = 'AND event_id = '.$request['event_game']; }
      }else{ $event_game = ''; }
      if(!empty($request['game_stat'])){
        if($request['game_stat']  == '_'){ $game_stat = ''; }else{ $game_stat = 'AND games.id = '.$request['game_stat'];}
      }else{ $game_stat = ''; }
      $game_by_event = " WHERE events.eventtime BETWEEN NOW() - INTERVAL 10 WEEK AND NOW() + INTERVAL 2 WEEK $event_game $game_stat ";
      $results = $wpdb->get_results(" SELECT games.id, games.exposure_game_id, games.event_id, events.name event_name, games.court, games.division, games.start_time, games.location, games.home_team AS home_roster_id, games.home_team_score, games.away_team AS away_roster_id, games.away_team_score, IF( home_org.abbreviation IS NULL OR home_org.abbreviation = '', CONCAT(home_org.name,' ', home_roster.level,'U ', IF(home_team.name IS NULL OR home_team.name = '', '', home_team.name)), CONCAT(home_org.abbreviation,' ', home_roster.level,'U ', IF(home_team.name IS NULL OR home_team.name = '', '', home_team.name)) ) AS home_team, IF( home_org.profile_img IS NULL OR home_org.profile_img = '', 'https://sportspassports.com/club/sc-mid-ca/teams', CONCAT('https://sportspassports.com/wp-content/uploads/org-logos/', home_org.profile_img) ) AS home_logo, IF( away_org.abbreviation IS NULL OR away_org.abbreviation = '', CONCAT(away_org.name,' ', away_roster.level,'U ', IF(away_team.name IS NULL OR away_team.name = '', '', away_team.name)), CONCAT(away_org.abbreviation,' ', away_roster.level,'U ', IF(away_team.name IS NULL OR away_team.name = '', '', away_team.name)) ) AS away_team, IF( away_org.profile_img IS NULL OR away_org.profile_img = '', 'https://sportspassports.com/club/sc-mid-ca/teams', CONCAT('https://sportspassports.com/wp-content/uploads/org-logos/', away_org.profile_img) ) AS away_logo FROM $dbs->games AS games LEFT JOIN $dbs->events events ON events.id = games.event_id LEFT JOIN $dbs->rosters AS home_roster ON games.home_team = home_roster.id LEFT JOIN $dbs->teams AS home_team ON home_roster.team = home_team.id LEFT JOIN $dbs->orgs AS home_org ON home_roster.org = home_org.id LEFT JOIN $dbs->rosters AS away_roster ON games.away_team = away_roster.id LEFT JOIN $dbs->teams AS away_team ON away_roster.team = away_team.id LEFT JOIN $dbs->orgs AS away_org ON away_roster.org = away_org.id $game_by_event ORDER BY court, start_time ");
      $data = array('message' => $results);
      return new WP_REST_Response($data, 200);
      break;
    case 'rosters':
      if(!empty($request['event_game'])){
        if($request['event_game']  == '_'){ $event_game = ''; }else{ $event_game = $request['event_game']; }
      }else{ $event_game = ''; }
      return api_get_game($event_game);
      break;
    case 'players':
      if($param < 1){ $where = "WHERE pl.enabled = 1"; }else{ $where = "WHERE pl.enabled = 1 AND pl.id = $param"; }
//       $results = $wpdb->get_results("SELECT pl.*, pos.name position_name, pos.abbr position_abbr FROM $dbs->players pl LEFT JOIN $dbs->positions pos ON pl.position = pos.id $where");
      $results = $wpdb->get_results(" SELECT pl.id, pl.name, pl.profile_img, pl.city, pl.grad_year, pl.state, pl.club_team, pl.position, pl.height_ft, pl.height_in, pos.name position_name, pos.abbr position_abbr, pl.birthday FROM $dbs->players pl LEFT JOIN $dbs->positions pos ON pl.position = pos.id $where ");
      function minify_json($json){
        // Remove whitespace between JSON elements
        $json = preg_replace('/\s+/', '', $json);
        return $json;
      }
//       $results = minify_json($results);
      $data = array('message' => $results);
      return new WP_REST_Response($data, 200);
      break;
    case 'clubs':
      if($param < 1){ $where = ''; }else{ $where = "WHERE id IN ($param)"; }
      $results = $wpdb->get_results("SELECT * FROM $dbs->orgs $where");
      $data = array('message' => $results);
      return new WP_REST_Response($data, 200);
      break;
    case 'orgs':
      $get_org_lists =  slb_org_menu('slb-org-data');
      $data = array('message' => $get_org_lists['org_list']);
      return new WP_REST_Response($data, 200);
      break;
    case 'stats':
      $stat_leaderboard = features_api('stat-leaderboard', ['event_id'=>$event_id, 'api_key'=>$org_type, 'org_id'=>$org_type, 'stat_type'=>$stat_type, 'lv_type'=>$lv_type, 'dv_type'=>$division, 'select_year'=>$year]);
      $data = array('message' => $stat_leaderboard);
      return new WP_REST_Response($data, 200);
      break;
    case 'standings':
      $select_year = wp_date('Y');
      $filter_options = g365_submenu_type(['post_gp_lv'=>'youth-boys', 'post_year'=>$select_year, 'lv_play'=>''], 13);
      if(!empty($request['select_year'])){
        if($request['select_year']  == '_'){ $select_year = ''; }else{ $select_year = $request['select_year']; }
      }else{ $select_year = ''; }
      if(!empty($request['division_list'])){
        if($request['division_list']  == '_'){ $division_list = ''; }else{ $division_list = str_replace('_', ',', $request['division_list']); }
      }else{ $division_list = ''; }
      if(!empty($request['player_type'])){
        if($request['player_type']  == '_'){ $player_type = ''; }else{ $player_type = str_replace('_', '-', $request['player_type']); }
      }else{ $player_type = ''; }
      $standings = g365_club_team_stat($event_id = null, $team_id = null, '', $opponent_id = null, $select_year, 7, array($division_list, null, 'is_standing_only', null, null, $player_type, 'is_main_ts'=>true, 'level_of_play'=>''));
      $data = array('message' => ['standings'=>$standings, 'filter_options'=>$filter_options]);
      return new WP_REST_Response($data, 200);
      break;
    case 'user_owned_data':
      !empty($request['user_id']) ? $user_id = $request['user_id'] : $user_id = '';
      $user_owned_data = $wpdb->get_results("SELECT * FROM $dbs->players pl WHERE JSON_CONTAINS(access, '$user_id', '$.SPD')");
      $data = array('message' => $user_owned_data);
      return new WP_REST_Response($data, 200);
      break;
    case 'boxscores':
      if(!empty($request['team_id'])){
        if($request['team_id']  == '_'){ $team_id = ''; }else{ $team_id = $request['team_id']; }
      }else{ $team_id = ''; }
      if(!empty($request['org_id'])){
        if($request['org_id']  == '_'){ $org_id = ''; }else{ $org_id = $request['org_id']; }
      }else{ $org_id = ''; }
      if(!empty($request['org_id'])){
        if($request['org_id']  == '_'){ $org_id = ''; }else{ $org_id = $request['org_id']; }
      }else{ $org_id = ''; }
      if(!empty($request['select_year'])){
        if($request['select_year']  == '_'){ $select_year = ''; }else{ $select_year = $request['select_year']; }
      }else{ $select_year = ''; }
      if(!empty($request['game_id'])){
        if($request['game_id']  == '_'){ $game_id = ''; }else{ $game_id = $request['game_id']; }
      }else{ $game_id = ''; }
      $club_team_datas = g365_club_team_stat($event_id = null, $team_id, $org_id = null, $opponent_id = null, $select_year, 7, array($select_year, $game_id, 'is_box_score_only'));
      $filtered = array();
      $get_player_stats = array();
      foreach($club_team_datas as $club_team_data): /*foreach-main*/
        $box_score = $club_team_data[0]['box_score'];
        $box_score = json_decode('['.$box_score.']', true);
        foreach($box_score as $index => $columns){
          foreach($columns as $key => $value){
            if($key == 'game_id' && $value == $game_id){
              $filtered[] = $columns;
            }
          }
        }
      endforeach;
      $data = array('message' => $filtered);
      return new WP_REST_Response($data, 200);
      break;
    case 'player_data':
      if(!empty($request['player_id'])){
        if($request['player_id']  == '_'){ $player_id = ''; }else{ $player_id = $request['player_id']; }
        $player_data_type = $request['args_one'];
        $player_birthday = str_replace('-', '_', $request['args_two']);
      }else{ $player_id = ''; }
      switch($player_data_type){
        case 'player_achievement':
          if(!empty($request['player_id'])){
            if($request['player_id']  == '_'){ $get_player = ''; }
            else{ $get_player = 'player = '.$request['player_id'].' AND'; }
          }
          break;
      }
      $pl_career_highs = g365_season_stat($player_id, '', 'career_high', '');
      $pl_career_high = array();
      foreach($pl_career_highs as $index => $filter_stats){
        $filter_stats = json_decode($pl_career_highs[$index]->stats, true);
        $pl_career_high['pts'][] = $filter_stats['pts'];
        $pl_career_high['rbs'][] = $filter_stats['rbs'];
        $pl_career_high['ast'][] = $filter_stats['ast'];
        $pl_career_high['stl'][] = $filter_stats['stl'];
        $pl_career_high['blk'][] = $filter_stats['blk'];
        $pl_career_high['three_pt'][] = $filter_stats['three_pt'];
      }
      $player_achievement = $wpdb->get_results("SELECT player, event, game, stats FROM $dbs->stats WHERE $get_player game != 0 AND event != 504 AND JSON_LENGTH(stats) > 0");
      // Player Eligible Tag
//       $player_eligible_tag = function spp_age_level( $player_birthday, $pl_date_now = null, $pl_time_zone = null, $level_proc = true );
      $player_data = ['player_info'=>g365_get_profile( $player_id, false, 0 ), 'player_career_high'=>$pl_career_high, 'player_achievement'=>$player_achievement, 'player_eligibility'=>'$player_eligible_tag'];
      $data = array('message' => $player_data[$player_data_type]);
      return new WP_REST_Response($data, 200);
      break;
  }
}

// To get login information
// function login($request){
//   $user_credential = array();
//   $user_credential['user_login'] = $request["username"];
//   $user_credential['user_password'] =  $request["password"];
//   $user_credential['remember'] = true;
//   $user = wp_signon($user_credential, false);
//   if(is_wp_error($user))
//     echo $user->get_error_message();
//     echo $user->get_error_code();
//   return $user;
// }
// Post request to create account on SPP
function account_creation($request){
  $parameters = $request->get_json_params(); // Get parameters from request body
  // Check if required parameters are provided
  if (empty($parameters['username']) || empty($parameters['email']) || empty($parameters['password'])) {
    return new WP_Error('invalid_data', __('Username, email, and password are required.'), array('status' => 400));
  }
  // Check if the username is already in use
  if (username_exists($parameters['username'])) {
    return new WP_Error('username_taken', __('Username is already taken.'), array('status' => 409));
  }
  // Check if the email address is already in use
  if (email_exists($parameters['email'])) {
    return new WP_Error('email_taken', __('Email address is already in use.'), array('status' => 409));
  }
  // Create the user account
  $user_id = wp_create_user($parameters['username'], $parameters['password'], $parameters['email']);
  // Check if user creation was successful
  if (is_wp_error($user_id)) {
    return new WP_Error('user_creation_failed', $user_id->get_error_message(), array('status' => 500));
  }

  $login_user = login($parameters);
  $get_login = send_post_request_after_registration($login_user->ID, $parameters['password']);
  
//   return array('user_id' => $user_id, 'email' => $parameters['email'], 'username' => $parameters['username'], 'token' => $token);
  return $get_login;
}
// add_action( 'after_setup_theme', 'custom_login' );
/*
** End React App with Wordpress API
*/
function send_post_request_after_registration($user_id, $user_password){
    // Get user data
    $user_info = get_userdata($user_id);
    
    // Prepare data to be sent in the POST request
    $data = array(
      'username' => $user_info->user_email,
      'password' => $user_password,
    );
    
    // URL to which the POST request will be sent
    $url = get_site_url().'/wp-json/jwt-auth/v1/token';
    
    // Send POST request
    $response = wp_remote_post($url, array(
      'method' => 'POST',
      'body' => $data,
      'timeout' => 45, // Adjust timeout as needed
    ));
    
    // Check if request was successful
    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      // Handle error
      error_log("POST request failed: $error_message");
    } else {
      // Request successful
      $response_body = wp_remote_retrieve_body($response);
      // Do something with the response if needed
      // For example, you can log it
      error_log("POST request successful. Response: $response_body");
    }
  return $response_body;
}
function g365_reorder_courts($data, $day){
  $matched_array = array(); $not_matched_array = array();
  foreach($data as $key => $val){
    if(wp_date("l", strtotime($val->start_time)) !== $day){
      $matched_array[$val->court][] = $val;
    }else{
      $not_matched_array[$val->court][] = $val;
    }
  }
  $new_array = array_merge_recursive($not_matched_array, $matched_array);
  $new_array = call_user_func_array('array_merge', array_values($new_array) ? : [[]]);
  return $new_array;
}
function team_standing_game($type = null, $args = null){
  !empty($args['select_year']) ? $select_year = $args['select_year'] : $select_year = '';
  $today_month = wp_date('m');
  $season_year = g365_date_format('year_only', 5);
  if( $select_year >= $season_year ){
    if( in_array($today_month, range(9, 11)) ){
      return 4;
    }
    elseif( in_array($today_month, [12, 1, 2]) ){
      return 8;
    }
    elseif( in_array($today_month, range(3, 5)) ){
      return 16;
    }
    elseif( in_array($today_month, [6, 7]) ){
      return 18;
    }
    elseif( $today_month == 8 ){
      return 20;
    }
  }else{
    return 20;
  }
}
function g365_all_tournament_award_list($type = null){
  global $wpdb; $dbs = json_decode(dbs());
  return $result = $wpdb->get_results("SELECT * FROM $dbs->events WHERE Year(eventtime) > '2020' ORDER BY eventtime DESC");
}
function custom_web_brands($type = null, $args = null){
  switch($type){
    case 'all-brands':
      return ['All Brands'=>'', 'G365'=>3191, 'OGP'=>1, 'EBC'=>2, 'Stage'=>3, 'Stage Youth'=>4, 'HHH'=>7164, 'Scholastic'=>7165];
      break;
  }
}
function get_event_data($type, $args = null){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($args['event_id']) ? $event_id =  $args['event_id'] : $event_id = '';
  switch($type){
    case 'dcp-year-only':
      return $wpdb->get_results(" SELECT Year(eventtime) event_year FROM $dbs->events WHERE id = $event_id ");
      break;
  }
}
function player_team_spotlight($type, $args = null){
  global $wpdb; $dbs = json_decode(dbs());
  switch($type){
    case 'stat-leaderboard':
      $exclude_org = custom_exception_list('orgs-stats');
      $event_id = $wpdb->get_results("SELECT ev.id event_id FROM $dbs->stats st INNER JOIN $dbs->events ev ON st.event = ev.id WHERE st.game != 0 AND ev.id NOT IN (504) AND ev.org NOT IN ($exclude_org) ORDER BY ev.eventtime DESC LIMIT 1");
      return g365_stat_leader($event_id = $event_id[0]->event_id, $stat = null, $input_year = null, $limit_result = 5, $player_level = null, $type = 4, $pl_division = null, ['is_player_team_spotlight'=>true]);
      break;
    case 'all-tournament-mvp':
      $award_options = g365_get_groups_data( 89, 3 , array('truncate'=>true) );
      $award_data = ( !empty($award_options->records[0]->id) ) ? g365_build_awards( $award_options->records[0]->id, array('truncate'=>true) ) : null;
      return ['award_data'=>array_slice($award_data->awards, 0, 5), 'event_data'=>$award_data->records[0]];
      break;
    case 'team-ranking':
      $ranking_data = g365_build_ranking(47, $rank_date);
      return ['org_records'=>array_slice($ranking_data->records[0]->records, 0, 5), 'org_data'=>$ranking_data];
    case 'app-team-ranking':
      $ranking_data = g365_build_ranking(47, $rank_date);
      return ['org_records'=>array_slice($ranking_data->records[0]->records, 0, 6), 'org_data'=>$ranking_data];
      break;
    case 'championship':
      empty($args['app-championship']) ? $is_app = false : $is_app = $args['app-championship'];
      $today_date = date("Y-m-d H:i:s");
      // Select latest event after 2 days
      $get_event = $wpdb->get_results("SELECT ev.id event_id FROM $dbs->stats st INNER JOIN $dbs->events ev ON st.event = ev.id WHERE st.game != 0 AND (ev.eventtime + INTERVAL 2 DAY) <= '$today_date' AND ev.org NOT IN (2, 3, 7165) AND ev.id != 504 GROUP BY ev.id ORDER BY ev.eventtime DESC LIMIT 1");
      $event_id = $get_event[0]->event_id;
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 100000000;");
      $results = $wpdb->get_results("SELECT division, GROUP_CONCAT(CONCAT( JSON_OBJECT(home_team, IF(home_team_score > away_team_score, '1', '0'), away_team, IF(home_team_score < away_team_score, '1', '0'), IF( (bracket_name = 'Playoffs') OR (bracket_name = 'Championship Playoffs') OR (bracket_name = 'Championship'), IF(home_team_score > away_team_score, CONCAT('po_', home_team), CONCAT('po_', away_team)), IF(home_team_score > away_team_score, CONCAT('po_', home_team), CONCAT('po_', home_team)) ), IF( (bracket_name = 'Playoffs') OR (bracket_name = 'Championship Playoffs') OR (bracket_name = 'Championship'), '1', '0')) )) json_results FROM ( SELECT * FROM $dbs->games WHERE event_id = $event_id ORDER BY start_time DESC ) inner_tbd GROUP BY division");
      $get_division_groups = array(); $get_pos = array(); $get_champ_team_data = array();
      $count = 0;
      foreach($results as $result){
        // Group by division
        $json_results = json_decode(('['.$result->json_results.']'));
        $get_division_groups[$result->division] = $json_results;
      }
      // Group by playoffs games
      foreach( $get_division_groups as $index => $get_division_group){
        foreach( $get_division_group as $get_po_games ){
          foreach( $get_po_games as $dex => $get_po_game ){
            $get_pos[$index][$dex] += $get_po_game;
            $get_pos[$index]['Championship_'.$dex] += ($get_po_games->$dex + $get_po_games->{'po_'.$dex});
          }
        }
        arsort($get_pos[$index]);
        $get_pos[$index] = array_slice($get_pos[$index], 0, 1);
      }
      foreach($get_pos as $index => $get_team_data){
        $count++;
        if($count < 6){
          $team_id = key($get_team_data);
          $team_id = substr($team_id, strpos($team_id, "_") + 1);   
          $get_champ_team_data[$index] = $wpdb->get_results("SELECT org.nickname org_url, org.name org_name, org.profile_img org_logo, ev.name event_name, ev.logo_img event_logo, ev.nickname event_nickname FROM $dbs->teams tm INNER JOIN $dbs->rosters ros ON tm.id = ros.team INNER JOIN $dbs->orgs org ON ros.org = org.id INNER JOIN $dbs->events ev ON ros.event = ev.id WHERE ros.id = $team_id");
        }
        if($is_app == true){
          if($count < 7){
            $team_id = key($get_team_data);
            $team_id = substr($team_id, strpos($team_id, "_") + 1);   
            $get_champ_team_data[$index] = $wpdb->get_results("SELECT org.nickname org_url, org.name org_name, org.profile_img org_logo, ev.name event_name, ev.logo_img event_logo, ev.nickname event_nickname FROM $dbs->teams tm INNER JOIN $dbs->rosters ros ON tm.id = ros.team INNER JOIN $dbs->orgs org ON ros.org = org.id INNER JOIN $dbs->events ev ON ros.event = ev.id WHERE ros.id = $team_id");
          }
        }
      }
      return $get_champ_team_data;
      break;
    case 'mobile-app-stat-leaderboard':
      $exclude_org = custom_exception_list('orgs-stats');
      $event_id = $wpdb->get_results("SELECT ev.id event_id FROM $dbs->stats st INNER JOIN $dbs->events ev ON st.event = ev.id WHERE st.game != 0 AND ev.id NOT IN (504) AND ev.org NOT IN ($exclude_org) ORDER BY ev.eventtime DESC LIMIT 1");
      return g365_stat_leader($event_id = $event_id[0]->event_id, $stat = null, $input_year = null, $limit_result = 6, $player_level = null, $type = 4, $pl_division = null, ['is_player_team_spotlight'=>true]);
      break;
  }
}
function features_api($type = null, $args = null){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($args['event_id']) ? $event_id = $args['event_id'] : $event_id = '';
  !empty($args['api_keys']) ? $api_keys = $args['api_keys'] : $api_keys = '';
  !empty($args['org_id']) ? $org_id = $args['org_id'] : $org_id = '';
  !empty($args['stat_type']) ? $stat_type = $args['stat_type'] : $stat_type = '';
  !empty($args['lv_type']) ? $lv_type = $args['lv_type'] : $lv_type = '';
  !empty($args['dv_type']) ? $dv_type = $args['dv_type'] : $dv_type = '';
  !empty($args['special_cond']) ? $special_cond = $args['special_cond'] : $special_cond = '';
  !empty($args['select_year']) ? $select_year = $args['select_year'] : $select_year = '';
  if(empty($select_year)){
    if(empty($special_cond)){
      $year_data = " events.eventtime BETWEEN " . g365_date_format('', 12, ['org_list'=>$org_id]) . " AND";
      $year_by_org = $org_id;
    }else{
      $year_by_org = secured_data('decrypt', ['keys'=>$special_cond]);
      $year_data = " events.eventtime BETWEEN " . g365_date_format('', 12, ['org_list'=>$year_by_org]) . " AND";
    }
  }else{
    $year_data = " events.eventtime BETWEEN " . g365_date_format($select_year, 11)['select_db_format'] . " AND";
  }
  if(!empty($args['special_cond'])){
    $decrypt_org = secured_data('decrypt', ['keys'=>$special_cond]);
    $special_cond = ' AND events.org IN ('.$decrypt_org.') ';
  }else{
    $special_cond = ' AND events.org IN ('. $org_id .') '; 
  }
  switch($type){
    case 'stat-leaderboard':
      $lv_play_list = array(); $event_list = array(); $dv_list = array(); $year_list = array();
      $events = $wpdb->get_results(" SELECT events.id, events.eventtime, events.name, events.logo_img, events.locations, events.dates FROM $dbs->events events INNER JOIN $dbs->stats st ON st.event = events.id WHERE $year_data events.enabled != 0 $special_cond AND st.event != 0 AND st.game != 0 AND events.id NOT IN (504) GROUP by events.id ORDER BY eventtime DESC, id DESC ");
      foreach($events as $index => $get_event){ $events[$index]->dates = g365_build_dates($get_event->dates, 2); }
      // Check to see if specific event is selected if not give them the latest event as a default event
      $latest_event = $wpdb->get_results("SELECT events.* FROM $dbs->events events INNER JOIN $dbs->stats st ON st.event = events.id WHERE $year_data events.enabled != 0 $special_cond AND st.event != 0 AND st.game != 0 AND events.id NOT IN (504) GROUP by events.id ORDER BY eventtime DESC, id DESC LIMIT 1");
      $latest_event = json_decode(json_encode($latest_event), true);
      $latest_event = $latest_event[0]['id'];
      if(!empty($event_id)){ $latest_event = $args['event_id']; }
      // Get players stats
      $pl_stat_data = g365_stat_leader($latest_event, $stat_type, '', '', $dv_type, 4, $lv_type);
      $pl_stat_data = json_decode( json_encode($pl_stat_data), true);
      // Filter players stats for top players
      $top_pl_stats = g365_stat_table_filter($pl_stat_data, '3', '', $default_num_pl = 50, $stat_type);
      foreach($events as $event){ $event_list[] = $event->id; }
      $event_list = implode(',', $event_list);
      // Get levels of play
      $lvs_of_play = $wpdb->get_results(" SELECT division FROM $dbs->rosters WHERE event IN ($event_list) AND division IS NOT NULL AND division != '' AND division != 'Do Not Show' GROUP BY division ");
      foreach($lvs_of_play as $lv_of_play){ $lv_play_list[] = $lv_of_play->division; }
      // Get division list
      $dvs_type = $wpdb->get_results(" SELECT level, CONCAT(level, 'U') name FROM $dbs->rosters WHERE event IN ($event_list) AND level IS NOT NULL AND level != '' GROUP BY level ORDER BY level ASC ");
      foreach($dvs_type as $index => $dv_type){ $dv_list[$index] = (object) ['level'=>$dvs_type[$index]->level, 'name'=>g365_return_keys('g365_grade_key')[$dv_type->level]]; }
      // Get year
      $set_season_year = " IF( MONTH(ev.eventtime) > 8, YEAR(ev.eventtime) + 1, YEAR(ev.eventtime) ) ";
      $event_years = $wpdb->get_results(" SELECT $set_season_year event_date FROM $dbs->events ev INNER JOIN $dbs->stats st ON ev.id = st.event WHERE ev.org IN ($year_by_org) AND ev.enabled = 1 AND st.game != 0 AND ev.id NOT IN (504) GROUP BY $set_season_year ORDER BY $set_season_year DESC ");
      foreach($event_years as $index => $event_year){ 
        $year_label = g365_date_format($event_year->event_date, 2).' Season';
        $year_list[] = (object) ['value'=>$event_year->event_date, 'name'=>$year_label]; 
      }
      return ['events'=>$events, 'stat_leaderboard_data'=>$top_pl_stats, 'api_keys'=>$api_keys, 'levels_of_play'=>$lv_play_list, 'dv_list'=>$dv_list, 'year_list'=>$year_list, 'org_id'=>$org_id];
      break;
  }
}
function secured_data($type = null, $args = null){
  !empty($args['keys']) ? $encrypt_keys = $args['keys'] : $encrypt_keys = '';
  // Store the cipher method
  $ciphering = 'AES-128-CBC';
  $options = 0;
  // Non-NULL Initialization Vector for encryption
  $encryption_iv = 'OGP-T04DL3X5xG';
  // Store the encryption key
  $encryption_key = 'ogp-encrypt-keys';
  // Second layer 36 bytes keys
  $second_layer_key = 'H4sW5xG25elRm0liB7R96c75hNGwH5MPsxEA';
  switch($type){
    case 'encrypt':
      // Use openssl_encrypt() function to encrypt the data
      $encryption = openssl_encrypt($encrypt_keys, $ciphering, $encryption_key, $options, $encryption_iv);
      return base64_encode($encryption);
      break;
    case 'decrypt':
      $decryption = openssl_decrypt(base64_decode($encrypt_keys), $ciphering, $encryption_key, $options, $encryption_iv);
      return $decryption;
      break;
    case 'secured-api-keys':
      // Non-NULL Initialization Vector for encryption
      $encryption_iv = 'SPP-API-KEYS-T04DL3X5xG';
      // Store the encryption key
      $encryption_key = 'spp-api-keys-encrypt-keys';
      $api_keys_encryption = base64_encode( $second_layer_key . (openssl_encrypt($encrypt_keys, $ciphering, $encryption_key, $options, $encryption_iv)) );
      
      $encrypt_keys = base64_decode($encrypt_keys);
      $encrypt_keys = substr($encrypt_keys, 36);
      $api_keys_decryption = openssl_decrypt($encrypt_keys, $ciphering, $encryption_key, $options, $encryption_iv);
      return ['encrypted'=>$api_keys_encryption, 'decrypted'=>$api_keys_decryption];
      break;
  }
}
function get_encrypted_api_keys(){
  global $wpdb; $dbs = json_decode(dbs()); $org_id_lists = array();
  $org_id = $wpdb->get_results("SELECT id FROM $dbs->orgs");
  foreach($org_id as $org_id_list){
    $org_id_lists['default'][]   = $org_id_list->id;
    $org_id_lists['encrypted'][] = secured_data('secured-api-keys', ['keys'=>$org_id_list->id])['encrypted'];
  }
  return $org_id_lists;
}
function admin_api_console($type = null, $args = null){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($args['secret_keys']) ? $secret_keys = $args['secret_keys'] : $secret_keys = '';
  !empty($args['api_keys']) ? $api_keys = $args['api_keys'] : $api_keys = '';
  !empty($args['request_url']) ? $request_url_param = '&request_url=' . $args['request_url'] : $request_url_param = '';
  $org_keys_param = 'secret_keys=' . $args['secret_keys'];
  $api_keys_param = '&api_keys=' . $args['api_keys'];
  $admin_mod = $wpdb->get_results(" SELECT * FROM $dbs->api_keys WHERE api_keys = '".$api_keys."' AND enabled = 1 ");
  // Get special_cond and admin_mod from backend admin console.
  $default_param = $org_keys_param . $api_keys_param . $request_url_param;
  $admin_keys = secured_data('secured-api-keys', ['keys'=>$default_param])['encrypted'];
  switch($type){
    case 'features-api':
      if(empty($admin_mod[0])){
        if( !empty($secret_keys) && !empty($api_keys) ){
          return '<div id="admin_keys">' . get_site_url().'/features/v1/stat-leaderboard/?admin_keys=' . $admin_keys . '</div>';
        }else{ return 'Missing API keys and secret keys.'; }
      }else{
        // If we need to specifically target certain org
        $admin_keys = $admin_mod[0]->admin_keys . $request_url_param;
        return '<div id="admin_keys">' . get_site_url().'/features/v1/stat-leaderboard/?admin_keys=' . $admin_keys . '</div>';
      }
      break;
    case 'slb-by-org':
      !empty($args['request_data']) ? $request_data =  $args['request_data'] : $request_data = '';
      $selected_org = '&special_cond=' . $request_data;
      $default_param = $org_keys_param . $api_keys_param . $selected_org;
      $admin_keys = secured_data('secured-api-keys', ['keys'=>$default_param])['encrypted'];
      return '<div id="admin_keys">' . get_site_url().'/features/v1/stat-leaderboard/?admin_keys=' . $admin_keys . '</div>';
      break;
  }
}
function get_acct_features_templates($type = null, $args = null){
  $website_url = get_site_url();
  $google_jquery_link = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js?loc=4';
  if(strpos(get_site_url(), 'dev.')){
    $js_link = get_site_url() . '/wp-content/themes/g365-press/js/inc/sportspassports.features.min.js';
    $style_link = get_site_url() . '/wp-content/themes/g365-press/css/spp.style.min.css?version=1';
  }else{
    $js_link = 'https://media.sportspassports.com/js/sportspassports.features.min.js';
    $style_link = 'https://media.sportspassports.com/css/spp.style.min.css?version=1';
  }
  $target_url = get_site_url() . '/api-data-form/';
  if(!empty($args['user_data'])){ $user_id = $args['user_data']; }
  else{ $user_id = ''; }
  $api_keys = secured_data('secured-api-keys', ['keys'=>$user_id])['encrypted'];
  $secret_keys = secured_data('encrypt', ['keys'=>$user_id]);
  switch($type){
    case 'acct-data':
      $download_btn = '<button id="phpDL" data-type="text/php">Download PHP</button>';
      $download_btn .= '<button id="htmlDL" data-type="text/html">Download HTML</button>';
      return ['download_btn'=>$download_btn, 'google_jquery_link'=>$google_jquery_link, 'js_link'=>$js_link, 'style_link'=>$style_link, 'target_url'=>$target_url, 'api_keys'=>$api_keys, 'secret_keys'=>$secret_keys];
      break;
    case 'slb-by-org':
      !empty($args['selected_org']) ? $get_selected_org = $args['selected_org'] : $get_selected_org = '';
      $api_keys = secured_data('secured-api-keys', ['keys'=>'1'])['encrypted'];
      $secret_keys = secured_data('encrypt', ['keys'=>'1']);
      $selected_org = secured_data('encrypt', ['keys'=>$get_selected_org]);
      return ['google_jquery_link'=>$google_jquery_link, 'js_link'=>$js_link, 'style_link'=>$style_link, 'target_url'=>$target_url, 'api_keys'=>$api_keys, 'secret_keys'=>$secret_keys, 'request_data'=>$selected_org];
      break;
  }
}
function slb_org_menu($type = null, $args = null, $access = null){
  switch($access){
      case 'stlb':
      global $wpdb; $dbs = json_decode(dbs());
      // Set exception org list just in case we need to hide specicfic org
      // OGP Spring League: 7094;
      $exclude_org_list = custom_exception_list('slb-exclude-orgs');
      $exclude_type_list = '8'; // Use to exclude passport
      $get_org_list = $wpdb->get_results(" SELECT org.name, org.id, org.nickname, org.profile_img FROM $dbs->events ev INNER JOIN $dbs->stats st ON ev.id = st.event INNER JOIN $dbs->orgs org ON ev.org = org.id WHERE ( org.enabled = 1 AND ev.enabled = 1 AND st.game > 1 AND org.id NOT IN ($exclude_org_list) AND ev.type NOT IN ($exclude_type_list) ) GROUP BY ev.org ORDER BY FIELD(org.id, 3191) DESC ");
      switch($type){
        case 'slb-org-data':
          !empty($args['org_string']) ? $org_string = $args['org_string'] : $org_string = '';
          $get_org_data = $wpdb->get_results(" SELECT org.id FROM $dbs->events ev INNER JOIN $dbs->stats st ON ev.id = st.event INNER JOIN $dbs->orgs org ON ev.org = org.id WHERE (org.enabled = 1 AND ev.enabled = 1 AND st.game != 0) AND org.nickname = '$org_string' GROUP BY ev.org ");
          include_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/g365-data-manager/js/form-templates/spp_acct_api_form.php';
          // Get selected org for slb.
          $get_slb_by_org = bind_to_template($replacements, $spp_slb_by_org, ['bind_acct_data'=>get_acct_features_templates('slb-by-org', ['selected_org'=>$get_org_data[0]->id])], 'org-api-forms');
          break;
      }
      return ['org_list'=>$get_org_list, 'by_org'=>$get_slb_by_org];
      break;
    case 'standings':
      global $wpdb; $dbs = json_decode(dbs());
      $exclude_org_list = custom_exception_list('slb-exclude-orgs');
      $exclude_type_list = '8'; // Use to exclude passport
      $get_org_list = $wpdb->get_results("SELECT org.name, org.id, org.nickname, org.profile_img FROM $dbs->orgs org INNER JOIN $dbs->events ev ON org.id = ev.org WHERE org.enabled = 1 AND ev.enabled = 1 AND org.id IN (3191, 3, 7165, 7164, 7729) AND ev.type NOT IN ($exclude_type_list) AND ev.org NOT IN ($exclude_org_list) GROUP BY org.id ORDER BY FIELD(org.id, 3191, 3, 7165, 7164, 7729) ASC");
            return ['org_list' => $get_org_list];
      break;
      
  }
}
function spp_statistics( $type = null, $args =null){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($args['player_id']) ? $player_id = $args['player_id'] : $player_id = '';
  switch($type){
    case 'player-statistics':
      $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      $player_badge_data = $wpdb->get_results(" SELECT COUNT(DISTINCT badge_range) badge_earned, CONCAT('[',GROUP_CONCAT( JSON_OBJECT(badge_range, JSON_OBJECT('name', badge_name, 'badge_logo', badge_url) ) ORDER BY badge_id ASC ), ']') badge_data FROM (SELECT bdg.badge_range badge_range, bdg.id badge_id, bdg.badge_name badge_name, bdg.badge_url FROM $dbs->badges bdg INNER JOIN $dbs->player_badges pl_bdg ON bdg.id = pl_bdg.badge_id WHERE pl_bdg.player_id = $player_id ) inner_tb ");
      $event_played = $wpdb->get_results(" SELECT COUNT(DISTINCT ev.id) event_played FROM $dbs->events ev INNER JOIN $dbs->stats st ON ev.id = st.event WHERE st.player = $player_id AND st.game != 0 AND st.enabled = 1 AND st.stats != '{}' AND st.stats != '' AND ev.id != 504 ");
      return ['player_badge_data'=>$player_badge_data, 'event_played'=>$event_played];
      break;
    case 'homepage-statistics':
      $total_events = $wpdb->get_results(" SELECT COUNT(ev.id) total_events FROM $dbs->events ev WHERE ev.enabled = 1 ");
      $total_pl_profile = $wpdb->get_results(" SELECT COUNT(pl.id) total_pl_profile FROM $dbs->players pl WHERE pl.enabled = 1 ");
      $total_tm_profile = $wpdb->get_results(" SELECT COUNT(tm.id) total_tm_profile FROM $dbs->teams tm WHERE tm.enabled = 1 ");
      return ['total_events'=>$total_events[0]->total_events, 'total_pl_profile'=>$total_pl_profile[0]->total_pl_profile, 'total_tm_profile'=>$total_tm_profile[0]->total_tm_profile];
      break;
    case 'club-statistics':
      !empty($args['org_id']) ? $org_id = $args['org_id'] : $org_id = '';
      $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      $club_event_played = $wpdb->get_results(" SELECT COUNT(event_id) total_event_played FROM (SELECT gm.event_id event_id FROM $dbs->games gm INNER JOIN $dbs->rosters ros ON (gm.home_team = ros.id) OR (gm.away_team = ros.id) WHERE ros.org = $org_id GROUP BY gm.event_id ) tb ");
      return ['club_event_played'=>$club_event_played];
      break;
  }
}
// Get game data for api use
function api_get_game($game_id = null){
	global $wpdb;
//   Hide warning for empty rosters
  if( empty($game->away_players_data) || empty($game->home_players_data) || empty($game->home_players) || empty($game->away_players) ){
    error_reporting(E_ERROR | E_PARSE);
  }
	if( !is_numeric($game_id) ) return "Need valid Game ID";
  $game_id = intval($game_id);
  $game = $wpdb->get_results(
      "SELECT home_roster.level, games.id, games.event_id, games.court, games.division, games.start_time, games.location, games.home_team AS home_roster_id, games.home_team_score, games.away_team AS away_roster_id, games.away_team_score, IF( home_org.abbreviation IS NULL OR home_org.abbreviation = '', CONCAT(home_org.name,' ', home_roster.level,'U ', home_team.name), CONCAT(home_org.abbreviation,' ', home_roster.level,'U ', home_team.name) ) AS home_team, IF( away_org.abbreviation IS NULL OR away_org.abbreviation = '', CONCAT(away_org.name,' ', away_roster.level,'U ', away_team.name), CONCAT(away_org.abbreviation,' ', away_roster.level,'U ', away_team.name) ) AS away_team, home_org.profile_img AS home_profile_img, away_org.profile_img AS away_profile_img, home_roster.players AS home_players, away_roster.players AS away_players
      FROM $wpdb->g365_games AS games
      LEFT JOIN $wpdb->g365_rosters AS home_roster ON games.home_team = home_roster.id
      LEFT JOIN $wpdb->g365_teams AS home_team ON home_roster.team = home_team.id
      LEFT JOIN $wpdb->g365_orgs AS home_org ON home_roster.org = home_org.id
      LEFT JOIN $wpdb->g365_rosters AS away_roster ON games.away_team = away_roster.id
      LEFT JOIN $wpdb->g365_teams AS away_team ON away_roster.team = away_team.id
      LEFT JOIN $wpdb->g365_orgs AS away_org ON away_roster.org = away_org.id
      WHERE games.id = $game_id;"
  );
  $game = (is_array($game)) ? $game[0] : $game;
  $where_ids = array();
  $query_data = null;
  $home_query_ids = null;
  if( empty($game->home_players) ) {
    $game->home_players = '';
    $game->home_players_data = '';
  } else {
    $game->home_players_data = json_decode($game->home_players);
    $home_query_ids = array_keys((array) $game->home_players_data);
    $where_ids = array_merge( $where_ids, $home_query_ids);
  }
  if( empty($game->away_players) ) {
    $game->away_players = '';
    $game->away_players_data = '';
  } else {
    $game->away_players_data = json_decode($game->away_players);
    $where_ids = array_merge( $where_ids, array_keys((array) $game->away_players_data));
  }
  if( !empty($game->home_players) || !empty($game->away_players) ) {
    $query_where = g365_build_where(array('pl.id'=>$where_ids));
    //Grab player data and add them to the tree
    $query_data = $wpdb->get_results(
      "SELECT pl.id, pl.name, pl.birthday, pl.city, pl.state, pl.profile_img, pl.nickname AS url, pl.verified, st.stats AS stats
      FROM $wpdb->g365_players AS pl
      LEFT JOIN $wpdb->g365_stats AS st ON st.player = pl.id AND st.game = $game_id
      $query_where
      ORDER BY name;",
      OBJECT_K
    );
    foreach($query_data as $id_num => &$player_data) {
      //add reference for home or away
      $player_data->homeaway = ( in_array($id_num, $home_query_ids) ) ? 'home' : 'away';
      $player_data->element_title = $player_data->name;
      //add stat data into the main data tree
      if( !empty($player_data->stats) ) {
        $player_data->stats = json_decode($player_data->stats);
        foreach($player_data->stats as $stat_name => $stat_val) $player_data->{$stat_name} = $stat_val;
        unset($player_data->stats);
      }
      if( empty($player_data->profile_img) ) {
        $player_data->profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
      } else {
        $player_data->profile_img = get_site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img;
      }
    }
    $birthday = 'birthday';
    foreach ( $game->home_players_data as $pl_dex => $pl_data ){
      $game->home_players_data->{$pl_dex} = $query_data[$pl_dex];
      $get_birth_year = $game->home_players_data->{$pl_dex}->$birthday;
      if( spp_age_level($game->home_players_data->{$pl_dex}->$birthday) > $game->level ){
        $eligibility = 'Ineligible';
      }else{ $eligibility = ''; }
      $game->home_players_data->{$pl_dex}->eligibility = $eligibility; 
    }
    foreach ( $game->away_players_data as $pl_dex => $pl_data ){
      $game->away_players_data->{$pl_dex} = $query_data[$pl_dex];
      if( spp_age_level($game->away_players_data->{$pl_dex}->$birthday) > $game->level ){
        $eligibility = 'Ineligible';
      }else{ $eligibility = ''; }
      $game->away_players_data->{$pl_dex}->eligibility = $eligibility; 
    }
  }
	return $game;
}
function spp_age_level( $pl_date_time, $pl_date_now = null, $pl_time_zone = null, $level_proc = true ){
  
  //exit if we don't have a target date to process
  if( empty($pl_date_time) ) return 'Need target time to process';
  //set time zone if not provided
  if( empty($pl_time_zone) ) {
    $pl_time_zone = new DateTimeZone('America/Los_Angeles');
  } else {
    $pl_time_zone = new DateTimeZone($pl_time_zone);
  }
  //turn the supplied string into a real date
  $pl_date_time = new DateTime($pl_date_time, $pl_time_zone);
  //if we didn't get a target date supplied, assume it's today
  if( empty($pl_date_now) ) {
    $pl_date_now = new DateTime("now", $pl_time_zone);
  } else {
    $pl_date_now = new DateTime($pl_date_now, $pl_time_zone);
  }
  //do the first age calculation
  $ver_level = ( intval($pl_date_now->format('Y')) - intval($pl_date_time->format('Y')));
  //see if we need to modify the base number
  
  if( intval($pl_date_now->format('n')) < 9 ) {
    if( intval($pl_date_time->format('n')) > 8 ) $ver_level -= 1;
  } else {
    if( intval($pl_date_time->format('n')) < 9 ) $ver_level += 1;
  }
  //process the raw number into a level title
  if( $level_proc ) $ver_level = ($ver_level);
  return $ver_level;
}
function calculate_age_grade($birthday){
  // Convert birthday to a DateTime object
  $birthDate = new DateTime($birthday);
  // Get the current date
  $currentDate = new DateTime();
  // Calculate the difference between the two dates
  $age = $currentDate->diff($birthDate);
  // Return the age
  return $age->y;
}
function brand_divisions_levels($args){
  global $wpdb; $dbs = json_decode(dbs());
  if(empty($args['year'])){ echo 'Need year to continue.'; exit; }else{ $year = $args['year']; $season_year = g365_date_format($year, 1); }
  if(empty($args['brand'])){ echo 'Need brand to continue.'; exit; }else{ $brand = $args['brand']; }
  $get_events = "
    SELECT id
      FROM $dbs->events
      WHERE
        org = $brand
        AND eventtime BETWEEN $season_year
        AND enabled = 1
        AND type NOT IN (5, 6, 7, 8) -- exclude training, college placement service, all star game, and passport membership
  ";
  $brand_division_by_events = $wpdb->get_results("
     WITH CTE_Events AS
       (
        $get_events
       ),
     CTE_Roster_Level AS
      (
        SELECT level
        FROM $dbs->rosters
        WHERE
          event IN (SELECT id FROM CTE_Events) AND level NOT IN (39, 40, 41, 42, 43, 44, 45, 46, 47)
      )
     SELECT * FROM CTE_Roster_Level GROUP BY level ORDER BY level ASC;
  ");
  $brand_level_by_events = $wpdb->get_results("
     WITH CTE_Events AS
       (
        $get_events
       ),
     CTE_Roster_Division AS
      (
        SELECT division
        FROM $dbs->rosters
        WHERE
          event IN (SELECT id FROM CTE_Events) AND level NOT IN (39, 40, 41, 42, 43, 44, 45, 46, 47)
      )
     SELECT * FROM CTE_Roster_Division WHERE division IS NOT NULL AND division != '' GROUP BY division ORDER BY division ASC;
  ");
  return ['brand_division_by_events' => $brand_division_by_events, 'brand_level_by_events' => $brand_level_by_events];
}
function cp_roster_list($org_id, $year){
    global $wpdb; 
    $dbs = json_decode(dbs()); 
    $year = g365_date_format($year, 1); 
    // Step 1: Fetch event IDs within the date range
    $get_events = "
        SELECT id
        FROM {$dbs->events}
        WHERE eventtime BETWEEN $year
    ";
    
    // Step 2: Use the fetched events to get roster details, checking if name is empty, use search_list instead
    $get_rosters = $wpdb->get_results("
        WITH CTE_Events AS (
            $get_events
        ),
        CTE_Roster_Details AS (
            SELECT DISTINCT r.id AS roster_id, r.team AS team_id, r.level AS team_level, r.org
            FROM {$dbs->rosters} r
            WHERE r.event IN (SELECT id FROM CTE_Events) AND r.org = $org_id
        )
        SELECT r.roster_id, r.team_id, r.team_level, r.org, 
               COALESCE(t.name, t.search_list) AS team_name -- If name is empty, use search_list
        FROM CTE_Roster_Details r
        LEFT JOIN {$dbs->teams} t ON t.id = r.team_id
        ORDER BY r.team_level DESC;
    ");

    return $get_rosters;
}
?>