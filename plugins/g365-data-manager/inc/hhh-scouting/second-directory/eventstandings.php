<!-- cts: Club Team Standing 
*event standings and box scores filtered for the specific event you chose.
-->
<?php 
$event_id = $player_id;
  global $wp_query; $key_level = (g365_return_keys('g365_grade_key'));
  if(!empty($wp_query->query_vars['lv_label'])){ 
    $is_post_lv = $key_lv[$wp_query->query_vars['lv_label']]; 
  }else{ $is_post_lv = ''; }
  $select_year = $_POST['g365_year'];
  $select_group = $_POST['group_lv'];
  $select_lv_play = $_POST['lv_of_play'];
//   if(!isset($select_group)){ $select_gp_div = $arg[0]; }else{ $select_gp_div = '17,16,15,44,43,42,41,40'; }
  $select_gp_div = '17,16,15,44,43,42,41,40';
//   echo('here: ' . $select_gp_div);
  if(!isset($select_year) && !isset($select_lv_play) && !isset($select_group)){
    $select_group = 'youth-girls';
    if(empty($arg['season_year'])){ $select_year = wp_date('Y'); }else{ $select_year = $arg['season_year']; }
    $select_lv_play = '';
  }
  if(!empty($select_lv_play)){ $lv_of_lv_pg = "&lv=".$select_lv_play.""; }else{ $lv_of_lv_pg = ""; }
?>
<!-- <div id="dialong_div "></div> -->
<div class="hhh_box_scores_overdrive"></div>
  

<?php
  $placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png';
  $org_logo = '/wp-content/uploads/org-logos/';
  if(empty($wp_query->query_vars['lv_type']) || $wp_query->query_vars['lv_type'] == 'all-levels'): /*if-main*/
//   echo $arg['season_year'];
?>
  <section class="flex flex-wrap mv1 medium-padding-bottom">
    <?php 
//       echo g365_submenu_type(array($wp_query->query_vars["pg_type"], $select_year), 2); 
      echo g365_submenu_type(['post_gp_lv'=>$select_group, 'post_year'=>$select_year, 'lv_play'=>$select_lv_play], 15);
    ?>
  </section>
<?php
//   $club_team_datas = g365_club_team_stat($event_id = null, $team_id = null, (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']), $opponent_id = null, $_POST['g365_year'], 7, array($arg[0], null, 'is_standing_only', null, null, $wp_query->query_vars["lv_pl"], 'is_main_ts'=>true));
  $filtered_club_team_data = array();
// echo(' event_id: ' . $event_id . ' //$team_id: ' . $team_id . ' //$opponent_id: ' . $opponent_id . ' //is_dcp_ev: ' . $arg['is_dcp_ev'] . ' //$select_year: ' . $select_year . ' //$select_gp_div: ' . $select_gp_div . ' //$select_group: ' . $select_group . ' //$select_lv_play: ' . $select_lv_play . ' //$player_id: ' . $player_id . '//');
  $club_team_datas = cj_g365_club_team_stat($event_id, $team_id = null, (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']), $opponent_id = null, $select_year, 9, array($select_gp_div, null, 'is_standing_only', null, null, $select_group, 'is_main_ts'=>true, 'level_of_play'=>$select_lv_play, 'is_dcp_ev'=>$player_id, 'is_unlocked_dcp_ev'=>true, 'is_dcp'=>true));
//   var_dump($club_team_datas);
  $testingfunctioncalls =  cj_g365_get_event_data($player_id, true);
//   var_dump($testingfunctioncalls);
  foreach($club_team_datas as $index => $club_team_data){
    $filtered_club_team_data[$index] = array_slice($club_team_data, 0, 10);
  }
  foreach($filtered_club_team_data as $level_index => $club_team_data): /*foreach-a*/
?>
  <h5><?php echo ($key_level[$level_index].' '.$_POST['lv_of_play']);  ?></h5>
  <table class="cell cts_tb hhh-event-bxs">
    <?php echo club_team_tb_form(); ?>
    <?php foreach($club_team_data as $index => $club_team_data_list): /*foreach-b*/ $box_score = $club_team_data_list['standing']; ?>
    <tr>
      <td>
        <div class="flex items-center grid-y-mobile">
          <span class="vr_btn small-margin-right" id="<?php echo $club_team_data_list['team_id'] ?>" onClick="view_result(this.id)">Box Scores</span>
          <span class="small-margin-right">
            <img style="height:25px;width:35px;" alt="<?php echo $club_team_data_list['full_team_name']; ?>" title="<?php echo $club_team_data_list['full_team_name']; ?>" src="<?php echo (!empty($club_team_data_list['org_logo']) ? $club_team_data_list['org_logo'] != "NULL" ? $org_logo.$club_team_data_list['org_logo'] : $placeholder_img : $placeholder_img); ?>">
          </span>
          <span><?php echo $club_team_data_list['full_team_name']; ?></span>
        </div>
      </td>
      <td><?php echo !empty($club_team_data_list['win']) ? round($club_team_data_list['win'], 2) : '0'; ?></td>
      <td><?php echo !empty($club_team_data_list['loss']) ? round($club_team_data_list['loss'], 2) : '0'; ?></td>
      <td><?php echo !empty($club_team_data_list['pct']) ? round((float)(number_format($club_team_data_list['pct'], 3)) * 100 ) . '%' : '0%'; ?></td>
      <td><?php echo !empty($club_team_data_list['ppg']) ? number_format(round($club_team_data_list['ppg'], 1), 1) : '0'; ?></td>
      <td><?php echo !empty($club_team_data_list['opp_ppg']) ? number_format(round($club_team_data_list['opp_ppg'], 1), 1) : '0'; ?></td>
    </tr>
    <tr id="<?php echo $club_team_data_list['team_id'] ?>-result_box" class="result_box">
      <td colspan="6">
        <span class="close_vr_btn small-margin-right" id="<?php echo $club_team_data_list['team_id'] ?>" onClick="view_result(this.id)">Close</span>
        <div class="grid-x cts_box_score small-12 medium-12 large-12">
          <?php
            $box_score = json_decode('['.$box_score.']', true);
            $group_by_events = array();
            foreach($box_score as $data_list){$group_by_events[$data_list['event_name']][] = $data_list;}
            foreach($group_by_events as $index=> $group_by_event): //a
              echo '<h5 class="small-12 medium-12 large-12 text-center" style="text-decoration:underline">'.$index.'</h5>';
              foreach($group_by_event as $boxscore_list): //b
                if(!empty($boxscore_list['full_team_name'])){ $full_team_name = $boxscore_list['full_team_name']; }else{ $full_team_name = ''; }
                if(!empty($boxscore_list)): //c
                  if($boxscore_list['gm_r_label'] == "W"){
                    $gm_result_color = 'style="color:white; font-weight:bold"';
                  }else{
                    $gm_result_color = 'style="color: hsl(0,60%,50%); font-weight:bold"';
                  }?>
                  <div class="stats_customize cts_res flex items-center small-margin-bottom small-12 medium-12 large-12">
                    <div class="team_logo_box hide-for-small-only">
                      <a href="<?php echo get_site_url().'/club/'.$boxscore_list['org_nickname'].'/teams'; ?>" target="_blank"><img style="height:100px;width:125px;" alt="<?php echo $club_team_data_list['full_team_name']; ?>" title="<?php echo $club_team_data_list['full_team_name']; ?>" src="<?php echo (!empty($club_team_data_list['org_logo']) ? $club_team_data_list['org_logo'] != "NULL" ? $org_logo.$club_team_data_list['org_logo'] : $placeholder_img : $placeholder_img); ?>"></a>
                    </div>
                    <div class="grid-x cts_res_box align-center">
                      <div class="small-4 medium-4 large-2 large-offset-2">
                        <span class="wrap-text--200 small-4 medium-4 large-4"><?php echo $club_team_data_list['full_team_name']; ?></span>
                      </div>
                      <div class="grid-x small-4 medium-4 large-4 align-center">
                        <span class="small-padding-right small-12 medium-12 large-12" <?php echo $gm_result_color; ?>><?php echo $boxscore_list['game_result']; ?></span>
                        <button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:12px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-select-year="<?php echo $select_year ?>" data-event-name="<?php echo $boxscore_list['event_name'] ?>" data-game-id="<?php echo $boxscore_list['game_id'] ?>" data-team-id="<?php echo $club_team_data_list['team_id'] ?>" data-url="<?php echo home_url(); ?>"> Box Score</button>
                      </div>
                      <div class="grid-x small-4 medium-4 large-4">
                        <span class="large-8 end"><?php echo $boxscore_list['opp_name']; ?></span>
                      </div>
                    </div>
                    <div class="opp_logo_box hide-for-small-only">
                      <a href="<?php echo get_site_url().'/club/'.$boxscore_list['opp_nickname'].'/teams'; ?>" target="_blank"><img style="height:100px;width:125px;" alt="<?php echo $full_team_name; ?>" title="<?php echo $full_team_name; ?>" src="<?php echo (!empty($boxscore_list['opp_logo']) ? $boxscore_list['opp_logo'] != "NULL" ? $org_logo.$boxscore_list['opp_logo'] : $placeholder_img : $placeholder_img); ?>"></a>
                    </div>
                  </div>
                <?php else: echo ("<p>".g365_message()['not_available']."</p>"); endif; //c
              endforeach;// b
            endforeach;// a
          ?>
        </div>
      </td>
    </tr>
    <?php endforeach;/*endforeach-b*/ ?>
<!--     <tr class="text-center slb_more_list" id="<?php //echo $index ?>" style="font-weight:bold;cursor:pointer;">
      <td class="buttonization">
        <div>
          <a style="color:#ffffff" href="<?php echo get_site_url(); ?>/club-team-standing/<?php echo empty($_POST['group_lv']) ? 'youth-girls' :$_POST['group_lv']; ?>/<?php $lv_url = strtolower(str_replace(array(" / ","/"," "), array("-","-","-"), $key_level[$level_index])); echo $lv_url ?>/<?php echo $level_index?>/?y=<?php echo $select_year. $lv_of_lv_pg?>&ev_id=<?php echo $event_id ?>"><span>View Complete Leaders</span></a>
        </div>
      </td>
    </tr> -->
    </tbody>
  </table>
<?php endforeach;/*endforeach-a*/ else: g365_dir_render('club-team-standing','by-level-standing', '', $arg = array($select_year, $key_level, $org_id, $org_logo, $placeholder_img, $level_index)); endif/*endif-main*/; echo (cts_dialog_js()); ?>

<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script>
    function submitFormEvent(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Get the form data
        var formData = $('#hhh-form-single').serialize();
        var event_id = <?php  echo json_encode($event_id);  ?>;
        var isSingleEvent = true;
        
        // Add event_id to formData
        formData += '&event_id=' + encodeURIComponent(event_id);
        formData += '&single_event=' + encodeURIComponent(isSingleEvent);
        console.log('Form data:', formData);

        // Make an AJAX request to submit the form data to the same file
        $.ajax({
            type: 'POST',
            url: '../../hhh-event-box-no-reload.php', // Submit to the same URL
            data: formData,
//             dataType: 'json', // Adjust the dataType based on your server response
            success: function (response) {
                // Handle the response, e.g., update the page content
//                 console.log('server response: ' , response);
                $('#2nd-dir-eventbox').html(response);
            },
            error: function () {
                // Handle error if needed
                alert('Failed to submit the form.');
            }
        });
    }
</script>