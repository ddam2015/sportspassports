<?php
$event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
// $event_info = g365_get_event_data($event_id, true);
if(isset($_POST['ev_val'])){ $post_ev_val = $_POST['ev_val']; }
$pl_division = g365_division();
$event_info = g365_get_event_data($post_ev_val, true);
$key_level = (g365_return_keys('g365_grade_key'));
if(isset($_POST['year'])){ $select_year = $_POST['year']; }else{ $select_year = ''; }
$select_level = $_POST['roster_level'];
if(isset($_POST['stat_type'])){ $post_stat_type = $_POST['stat_type']; }
$post_dvs = $_POST['roster_dvs'];
$post_stat_catagory = $_POST['stat_catagory'];
$stat_lists = g365_stat_list();
$most_recent_event = most_recent_event(1);
$most_recent_event_id = $most_recent_event[0]->event_id;
$default_event_info = g365_get_event_data($most_recent_event_id, true);
$default_num_pl = 5;
$set_top_pl_num = 50;
?>
<div>
  <form method="post" action="" id="statleader-form" class="grid-x">
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 152px">
      <select name="roster_level" id="roster_level" style="border-radius: 20px"> 
        <option value="">All Divisions</option>
        <?php for($i = 8; $i <= 47; $i++): if(($i > 7 && $i < 18) || ($i > 39 && $i < 48 )):/*if-a*/ ?>
          <option <?php if(isset($select_level) && $select_level == $i){echo 'selected= "selected"';} ?> value="<?php echo $i ?>"><?php echo $key_level[$i]; ?></option> 
        <?php endif;/*if-a*/ endfor; ?>
      </select>
    </div>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 200px">
      <select name="roster_dvs" id="roster_dvs" style="border-radius: 20px"> 
        <option value="">All Levels of Play</option>
        <?php foreach($pl_division as $index => $dvs_list):/*foreach-a*/ ?>
          <option <?php if(isset($post_dvs) && $post_dvs == $pl_division[$index]){echo 'selected= "selected"';} ?> value="<?php echo $dvs_list ?>"><?php echo $pl_division[$index]; ?></option> 
        <?php endforeach;/*endforeach-a*/ ?>
      </select>
    </div>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 200px">
      <select name="stat_catagory" id="stat_catagory" style="border-radius: 20px"> 
        <option value="">All Stat Categories</option>
        <?php $stat_lists = g365_stat_list(); foreach($stat_lists as $index => $stat_list): $stat_type = $stat_lists[$index]['type']; $stat_alias = $stat_lists[$index]['alias'];?>
        <option <?php if(isset($post_stat_catagory) && $post_stat_catagory == $stat_alias){echo 'selected= "selected"';} ?> value="<?php echo $stat_alias ?>"><?php echo ($stat_type."s") ?></option> 
        <?php endforeach; ?>
      </select>
    </div>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 260px">
<!--       <input type="button" id="change_event_btn" class="spotlight__card--heading" value="Change Event"/> -->
    <?php echo (avi_ev_list(array($default_event_info->id))); ?>
    </div>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
      <input type="submit" id="slb_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
    </div>
<!--     <div id="event_search_box" class="small-margin-bottom small-12 large-12 small-padding-right small-margin-top"> -->
<!--       <input type="text" class="g365_livesearch_input ls_query" id="event_link_selector" data-g365_type="stat_leaderboard" placeholder="Enter Event Name" autocomplete="off" name="ls_query" maxlength="60" style="border-radius: 20px"> -->
<!--     </div> -->
  </form>
</div>
<!------------------- Default Page ------------------->
<?php 
  // Default page with a list of top 5 players from all 5 stat types(Base on most recent event);
  if( empty($event_id) && !is_numeric($select_level) && empty($post_stat_catagory) && !isset($post_ev_val) ): /*if-1*/
  $pl_data_type = g365_stat_leader($most_recent_event_id, $post_stat_catagory, $select_year, '', $select_level, $type = 4);// Default top 5 players from all 5 stat types by most recent event
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '4', '', $default_num_pl, '');
  $ev_location = str_replace('|', ' | ', $default_event_info->locations);
?>
<!-- <h3 class="large-12 text-center small-padding-top" style="text-decoration:underline"><?php echo $default_event_info->name; ?></h3> -->
<div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
  <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $default_event_info->logo_img; ?>" alt="<?php echo $default_event_info->name;?>"></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo g365_build_dates($default_event_info->dates, 2); ?></h4></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-b*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat): /*foreach-a*/ $ev_year = $default_pl_stats[$index][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7);?>
  <div class="small-12 medium-6 large-6">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = false);
          foreach( $default_pl_stat as $pl_stat):/*foreach-b*/ 
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $most_recent_event_id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-b*/?>
        <tr class="text-center slb_more_list" id="<?php echo $index ?>" style="font-weight:bold;cursor:pointer;">
          <td  class="buttonization">
            <div>
              <span>View Complete Leaders</span>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach; /*endforeach-a*/ endif;/*endif-b*/ if(empty($pl_data_type)):/*if-c*/?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-c*/ ?>
</div>
<?php endif;/*endif-1*/
  // Default page with top 50 by selected stat type
  if( empty($event_id) && !is_numeric($select_level) && !empty($post_stat_catagory) && isset($post_ev_val) ):/*if-7*/  
  $pl_data_type = g365_stat_leader($post_ev_val, $post_stat_catagory, $select_year, '', $select_level, $type = 4, $post_dvs);
  $pl_data_type = json_decode( json_encode($pl_data_type), true); 
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '3', '', $set_top_pl_num, $post_stat_catagory);
  $ev_year = $default_pl_stats[$post_stat_catagory][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7);
  $ev_location = str_replace('|', ' | ', $event_info->locations);
?>
<div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
  <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>"></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo g365_build_dates($event_info->dates, 2); ?></h4></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-7d*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-7c*/ ?>
  <div class="small-12 medium-12 large-12">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = false, 'dvs-lv');
          foreach( $default_pl_stat as $pl_stat):/*foreach-7d*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $most_recent_event_id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-left small-padding-right"><?php echo !empty($pl_stat['player_division']) ? $pl_stat['player_division'] : '-'; ?></td>
          <td class="text-left small-padding-right"><?php echo $key_level[$pl_stat['player_level']]; ?></td>
          <td class="text-left small-padding-right"><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-7d*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*endforeach-7c*/ endif;/*endif-7d*/ if(empty($pl_data_type)):/*endif-7e*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-7e*/ ?>
</div>
<?php endif;/*endif-7*/
  // Default page with top 50 by selected level
  if( (empty($event_id) && is_numeric($select_level) && empty($post_stat_catagory)) && isset($post_ev_val) ):/*if-8*/  
  $pl_data_type = g365_stat_leader($post_ev_val, $post_stat_catagory, $select_year, '', $select_level, $type = 2, $post_dvs); // Default top 5 players from all 5 stat types by most recent event and selected level
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '4', '', $default_num_pl, '');
  $ev_location = str_replace('|', ' | ', $event_info->locations);
?>
<div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
  <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>"></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo g365_build_dates($event_info->dates, 2); ?></h4></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-8d*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-8c*/   $ev_year = $default_pl_stats[$index][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7); ?>
  <div class="large-6">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = true, 'dvs-lv');
          foreach( $default_pl_stat as $pl_stat):/*foreach-8d*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $most_recent_event_id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-left small-padding-right"><?php echo !empty($pl_stat['player_division']) ? $pl_stat['player_division'] : '-'; ?></td>
          <td class="text-left small-padding-right"><?php echo $key_level[$pl_stat['player_level']]; ?></td>
          <td><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-8d*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*endforeach-c*/ endif;/*endif-d*/ if(empty($pl_data_type)):/*endif-8e*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-8e*/ ?>
</div>
<?php endif;/*endif-2*/ 
// Default page with selected options(Base on most recent event);
  if( empty($event_id) && is_numeric($select_level) && !empty($post_stat_catagory) && isset($post_ev_val) ): /*if-3*/
  $pl_data_type = g365_stat_leader($post_ev_val, $post_stat_catagory, $select_year, '', $select_level, 2, $post_dvs);
  $pl_data_type = json_decode( json_encode($pl_data_type), true); 
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '3', '', $set_top_pl_num, $post_stat_catagory);
  $ev_year = $default_pl_stats[$post_stat_catagory][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7);
?>
<div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
  <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>"></div>
  <div style="flex: 0 1 100%;" class="text-center"><h3><?php echo g365_build_dates($event_info->dates, 2); ?></h3></div>
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-d*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-c*/ ?>
  <div class="large-12">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = true, 'dvs-lv');
          foreach( $default_pl_stat as $pl_stat):/*foreach-d*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $most_recent_event_id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-left small-padding-right"><?php echo !empty($pl_stat['player_division']) ? $pl_stat['player_division'] : '-'; ?></td>
          <td class="text-left small-padding-right"><?php echo $key_level[$pl_stat['player_level']]; ?></td>
          <td><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-d*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*endforeach-c*/ endif;/*endif-d*/ if(empty($pl_data_type)):/*endif-e*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-e*/ ?>
</div>
<!------------------- End Default Page ------------------->
<!------------------- By Event Page ---------------------->
<?php endif;/*endif-3*/
  // Event default page with a list of top 5 players from all 5 stat types(Base on selected event)
  if( empty($event_id) && isset($post_ev_val) && (!is_numeric($select_level) && empty($post_stat_catagory)) ):/*if-4*/
  $pl_data_type = g365_stat_leader($post_ev_val, $post_stat_catagory, $select_year, '', $select_level, $type = 4, $post_dvs); // Default top 5 players from all 5 stat types by most recent event
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '4', '', $default_num_pl, '');
  $ev_location = str_replace('|', ' | ', $event_info->locations);
?>
<div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
  <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>"></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo g365_build_dates($event_info->dates, 2); ?></h4></div>
  <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-4a*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-4a*/ $ev_year = $default_pl_stats[$index][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7); ?>
  <div class="small-12 medium-6 large-6">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          if(isset($post_dvs) && $post_dvs != ''){ echo leaderboard_tb_form($index, $level = false, 'dvs'); }else{ echo leaderboard_tb_form($index, $level = false); }
          foreach( $default_pl_stat as $pl_stat):/*foreach-4b*/
          $dvs_tr = ('<td class="text-left small-padding-right">'.(!empty($pl_stat['player_division']) ? $pl_stat['player_division'] : '-').'</td>');
if(isset($post_dvs) && $post_dvs != ''){ $is_dvs = $dvs_tr; }else{ $is_dvs = ''; }
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $event_info->id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                      <?php //echo $img_output; ?>
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <?php echo $is_dvs; ?>
          <td class="text-right small-padding-right"><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-4b*/ ?>
        <tr class="text-center slb_more_list" id="<?php echo $index ?>" style="font-weight:bold;cursor:pointer;">
          <td class="buttonization">
            <div>
              <span>View Complete Leaders</span>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*foreach-4a*/ endif;/*endif-4a*/ if(empty($pl_data_type)):/*if-4b*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-4b*/ ?>
</div>
<?php endif;/*endif-4*/
  // Event: Top 50 players(Base on selected stat catagory)
  if( !empty($event_id) && isset($post_ev_val) && !is_numeric($select_level) && !empty($post_stat_catagory) ):/*if-9*/ 
  $pl_data_type = g365_stat_leader($event_id, $post_stat_catagory, $select_year, '', $select_level, $type = 4);
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '3', '', $set_top_pl_num, $post_stat_catagory);
  $ev_year = $default_pl_stats[$post_stat_catagory][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7);
?>
<div class="all-tournament__details">
  <img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>">
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-5a*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-5a*/ ?>
  <div class="small-12 medium-12 large-12">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = false);
          foreach( $default_pl_stat as $pl_stat):/*foreach-5b*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $event_info->id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-5b*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*endforeach-5a*/ endif;/*endif-5a*/ if(empty($pl_data_type)):/*if-5b*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-5b*/ ?>
</div>
<?php endif;/*endif-9*/ 
  // Top 5 players(Base on selected level)
  if( !empty($event_id) && isset($post_ev_val) && is_numeric($select_level) && empty($post_stat_catagory) ):/*if-10*/ 
  $pl_data_type = g365_stat_leader($event_id, $post_stat_catagory, $select_year, '', $select_level, $type = 2); // Default top 5 players from all 5 stat types by most recent event
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '4', '', $default_num_pl, '');
?>
<div class="all-tournament__details">
  <img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>">
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-10a*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-10a*/ $ev_year = $default_pl_stats[$index][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7); ?>
  <div class="small-12 medium-6 large-6">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = true);
          foreach( $default_pl_stat as $pl_stat):/*foreach-10b*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $event_info->id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo $key_level[$pl_stat['player_level']]; ?></td>
          <td><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-10b*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*foreach-10a*/ endif;/*endif-10a*/ if(empty($pl_data_type)):/*if-10b*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-10b*/ ?>
</div>
<?php endif;/*endif-10*/
  // Top 50 players(Base on selected event)
  if( !empty($event_id) && isset($post_ev_val) && is_numeric($select_level) && !empty($post_stat_catagory)): /*if-5*/
  $pl_data_type = g365_stat_leader($event_id, $post_stat_catagory, $select_year, '', $select_level, $type = 2);
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '3', '', $set_top_pl_num, $post_stat_catagory);
  $ev_year = $default_pl_stats[$post_stat_catagory][0]["event_time"]; $ev_year = g365_date_format($ev_year, 7);
?>
<div class="all-tournament__details">
  <img class="width-50-200" src="<?php echo $event_info->logo_img; ?>" alt="<?php echo $event_info->name;?>">
</div>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-5a*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-5a*/ ?>
  <div class="small-12 medium-12 large-12">
    <div class="responsive-table">
      <table class="stat-table">
        <?php 
          echo leaderboard_tb_form($index, $level = true);
          foreach( $default_pl_stat as $pl_stat):/*foreach-5b*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $event_info->id, $pl_data_type[0]['ev_type'], $ev_year, strtolower(preg_replace('/\s+|\.|-/', '-',$event_nickname))), 1);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo $key_level[$pl_stat['player_level']]; ?></td>
          <td><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php endforeach;/*endforeach-5b*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*endforeach-5a*/ endif;/*endif-5a*/ if(empty($pl_data_type)):/*if-5b*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-5b*/ ?>
</div>
<?php endif;/*endif-5*/ ?>
<!------------------- End By Event Page ---------------------->