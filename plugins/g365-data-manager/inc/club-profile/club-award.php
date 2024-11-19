<?php
  global $wp_query;
  $org_data = g365_get_org_profile( $wp_query->query_vars['org_name'] ); 
  $org_rosters = g365_get_rosters(array('org_id' => $org_data->id, 'event_id' => 0), false, true);
  $select_year = year_dd_opt('most_recent_event')[1];
  if(empty($select_year)){ $select_year = g365_date_format('year_only', 5); }
  $def_club_award = g365_team_rosters($select_year, $team_id, $org_data->id, 4);
  $club_pl_id = array(); // Get a list of all players in team rosters
  foreach($def_club_award as $club_player){ 
  $player_id_list = $club_player->players;
    if(!empty($player_id_list)){
      $player_id_list = array_keys(json_decode($player_id_list, true));
      foreach($player_id_list as $index => $player_ids){ // Filter players
        if (!in_array($player_ids, $club_pl_id)){
          $club_pl_id[] = $player_ids;
        }   
      }
    }
  }
  $player_data = g365_get_award(null, $select_year, $org_data->id, null, 2);
?>
<?php 
  g365_dir_render('club-profile', 'championship', $player_id, $arg=array($select_year, $team_id, $org_data->id, 'org_champ'));
  g365_dir_render('club-profile', 'season-club-team-ranking', $player_id, $arg = array($select_year, $org_data->id));
  g365_dir_render('club-profile', 'individual-award', $player_id, $arg=array($player_data, $select_year));
?>
