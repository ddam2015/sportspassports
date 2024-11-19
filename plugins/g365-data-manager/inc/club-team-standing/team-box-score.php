<?php
  global $wp_query; $team_id = $wp_query->query_vars['tm']; $game_id = $wp_query->query_vars['gm']; $select_year = $wp_query->query_vars['y'];
  $club_team_datas = g365_club_team_stat($event_id = null, $team_id, $org_id, $opponent_id = null, $select_year, 7, array($select_year, $game_id, 'is_box_score_only'));

// print_r([$event_id = null, $team_id,$org_id, $opponent_id = null, $select_year, 7, array($select_year, $game_id, 'is_box_score_only')]);

  $placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png';
  $org_logo = '/wp-content/uploads/org-logos/';
  $validate_pl_stat = array();
  $filtered = array();
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
?>
<div class="cts_box_score small-12 medium-12 large-12">
  <?php
    foreach($filtered as $boxscore_list)://b
      if(!empty($boxscore_list)): //c
        if($boxscore_list['gm_r_label'] == "W"){
          $gm_result_color = 'style="color:white; font-weight:bold"';
        }else{
          $gm_result_color = 'style="color:hsl(0,60%,50%); font-weight:bold"';
        }?>
        <div class="grid-x pl_box_score" id="pl_box_score-<?php echo $boxscore_list['game_id'] ?>">
          <?php foreach($boxscore_list['player_stat'] as $pl_stat): ?>
          <div class="small-12 medium-12 large-12">
            <div class="grid-x">
              <div class="ts_top_result small-12 medium-12 large-12">
                <span><?php echo $boxscore_list['game_result']; ?></span>
              </div>
            </div>
          </div>
          <div class="small-12 medium-12 large-6 small-padding">
            <div class="small-12 medium-12 large-12 small-padding-top">
              <span>
                <a class="logo_box flex align-center" href="<?php echo get_site_url().'/club/'.$boxscore_list['org_nickname'].'/teams'; ?>" target="_blank"><img alt="<?php echo $club_team_data[0]['full_team_name']; ?>" title="<?php echo $club_team_data[0]['full_team_name']; ?>" src="<?php echo (!empty($club_team_data[0]['org_logo']) ? $club_team_data[0]['org_logo'] != "NULL"  ? get_site_url().$org_logo.$club_team_data[0]['org_logo'] : $placeholder_img : $placeholder_img); ?>">
                </a>
               <?php $team_name = str_replace('.', '', $club_team_data[0]['full_team_name']); ?>
              <?php $opp_name = str_replace('.', '', $boxscore_list['opp_name']); ?>
              </span>
              <span class="ts_top flex align-center"><?php echo $team_name; ?></span>
            </div>
            <?php echo g365_cts_tb(cts_box_score_tb(array($pl_stat[0]['pl_data']))); echo cts_st_tb(null,3)[0]; foreach(cts_st_tb(array($pl_stat[0]['pl_data']),2) as $pl_data){ echo cts_st_tb(array($pl_data,$boxscore_list['event_id'], $select_year,$boxscore_list['event_name']),3)[1];} echo cts_st_tb(null,3)[2]; ?>
          </div>
          <div class="small-12 medium-12 large-6 small-padding">
            <div class="small-12 medium-12 large-12 small-padding-top">
              <span>
                <a class="logo_box flex align-center" href="<?php echo get_site_url().'/club/'.$boxscore_list['opp_nickname'].'/teams'; ?>" target="_blank"><img alt="<?php echo $boxscore_list['opp_name']; ?>" title="<?php echo $boxscore_list['opp_name']; ?>" src="<?php echo (!empty($boxscore_list['opp_logo']) ? $boxscore_list['opp_logo'] != "NULL"  ? get_site_url().$org_logo.$boxscore_list['opp_logo'] : $placeholder_img : $placeholder_img); ?>">
                </a>
              </span>
              <span class="ts_top flex align-center"><?php echo $opp_name; ?></span>
            </div>
            <?php echo g365_cts_tb(cts_box_score_tb(array($pl_stat[1]['opp_data']))); echo cts_st_tb(null,3)[0]; foreach(cts_st_tb(array($pl_stat[1]['opp_data']),2) as $pl_data){ echo cts_st_tb(array($pl_data,$boxscore_list['event_id'],$select_year,$boxscore_list['event_name']),3)[1];} echo cts_st_tb(null,3)[2]; ?>
          </div>
          <?php endforeach; ?>
        </div>
      <?php else: echo ("<p>".g365_message()['not_available']."</p>"); endif; //c
    endforeach;// b
//     endforeach;// a
  endforeach;/*foreach-main*/
  ?>
</div>
<style type="text/css" scoped>.full_width_container, #masthead, #site-footer{ display: none; }</style>