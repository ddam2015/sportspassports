<?php
/**
 * Template Name: All Tournament Award List
 */
?>
<!-- <table class="stat-table">
  <tr>
    <th>Events</th>
    <th>All-Tournament Team</th>
    <th>All-Tournament MVP</th>
    <th>Champions</th>
    <th>Runner-Up</th>
  </tr> -->
<?php
//   function is_award_img($url){
//     $init_curl = curl_init($url);
//     curl_setopt($init_curl, CURLOPT_NOBODY, true);
//     curl_exec($init_curl);
//     $code = curl_getinfo($init_curl, CURLINFO_HTTP_CODE);
//     if ($code == 200){ $status = true; }else{ $status = false; }
//     curl_close($init_curl);
//     return $status;
//   }
//   foreach( g365_all_tournament_award_list() as $tounament_awd_lists ):
//   $awd_url_name = str_replace(array("'", " ", "--"), array("", "-", "-"), $tounament_awd_lists->short_name);
//   $all_tournament_team_url = 'https://grassroots365.com/wp-content/themes/g365-press/assets/badges/all-tournament/'. $awd_url_name .'-All-Tournament-Team.png';
//   $all_tournament_mvp_url = 'https://grassroots365.com/wp-content/themes/g365-press/assets/badges/all-tournament/'. $awd_url_name .'-All-Tournament-MVP.png';
//   $all_tournament_champion_url = 'https://grassroots365.com/wp-content/themes/g365-press/assets/badges/all-tournament/'. $awd_url_name .'-Champions.png';
//   $all_tournament_runner_up_url = 'https://grassroots365.com/wp-content/themes/g365-press/assets/badges/all-tournament/'. $awd_url_name .'-Runner-Up.png';
//   if( is_award_img($all_tournament_team_url) === true ){ $all_awd_team_url = $all_tournament_team_url; }else{ $all_awd_team_url = 'https://grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif'; }
//   if( is_award_img($all_tournament_mvp_url) == true ){ $all_awd_mvp_url = $all_tournament_mvp_url; }else{ $all_awd_mvp_url = 'https://grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif'; }
//   if( is_award_img($all_tournament_champion_url) == true ){ $all_awd_champion_url = $all_tournament_champion_url; }else{ $all_awd_champion_url = 'https://grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif'; }
//   if( is_award_img($all_tournament_runner_up_url) == true ){ $all_awd_runner_up_url = $all_tournament_runner_up_url; }else{ $all_awd_runner_up_url = 'https://grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif'; }
//   echo "<pre>"; print_r($awd_url_name); echo "</pre>";
?>
<!--   <tr>
    <td><?php echo $tounament_awd_lists->name; ?></td>
    <td><img width="200" height="150" class="award_container" src="<?php echo $all_awd_team_url; ?>" title="<?php echo $tounament_awd_lists->name; ?>"></td>
    <td><img width="200" height="150" class="award_container" src="<?php echo $all_awd_mvp_url; ?>" title="<?php echo $tounament_awd_lists->name; ?>"></td>
    <td><img width="200" height="150" class="award_container" src="<?php echo $all_awd_champion_url; ?>" title="<?php echo $tounament_awd_lists->name; ?>"></td>
    <td><img width="200" height="150" class="award_container" src="<?php echo $all_awd_runner_up_url; ?>" title="<?php echo $tounament_awd_lists->name; ?>"></td>
  </tr> -->
<?php //endforeach; ?>
<!-- </table> -->
