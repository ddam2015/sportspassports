<!--  
*stat leaderboard specifically showing information for the event you have chosen.
-->

<div>
  <p>
    hello x2
  </p>
</div>
<?php 
$play_id = get_current_user_id(); //get current user logged in
$arg['dir_ev'] = $player_id;
$player_id = $play_id;
$arg['dcp_nav'] = true;
$arg['enabled_access'] = true;
$arg['stat_catagory'] = 'stat_point';
$arg['roster_level'] = 'Gold';
$arg['roster_dvs'] = 40;


echo $arg['dir_ev'] . ' ' . $player_id . ' ' . $arg['dcp_nav'] . ' ' . $arg['enabled_access'];

?>


<?php $ev_id = filter_input( INPUT_GET, 'ev', FILTER_SANITIZE_NUMBER_INT ); $ev_type = url_param('type'); empty($arg['enabled_access']) ? "" : $enabled_access = $arg['enabled_access']; 
$authorized_user = get_current_user_id(); 
if(empty($arg['dir_ev'])){ $arg['dir_ev'] = ''; } if(empty($ev_id)){ $ev_id = $arg['dir_ev']; $ajax_url = true; }
if(!isset($_POST['roster_level']) && !isset($_POST['roster_dvs']) && !isset($_POST['stat_catagory']) && !isset($_POST['ev_val'])){ $post_level_val = "false"; $post_dvs_val = "false"; $post_stat_val = "false"; $post_ev_val = "false"; }else{ $post_ros_level = $_POST['roster_level']; 
$post_dvs = $_POST['roster_dvs']; $post_stat_catagory = $_POST['stat_catagory']; $post_ev_id = (empty($_POST['ev_val']) ? '' : $_POST['ev_val']); }
echo('HERE-> post_level_val: ' . $post_level_val . ' $post_dvs_val: ' . $post_dvs_val . ' $post_stat_val: ' . $post_stat_val . ' $post_ev_val: ' . $post_ev_val . ' $post_ros_level: ' . $post_ros_level . ' $post_dvs: ' . $post_dvs . ' $post_stat_catagory: ' . $post_stat_catagory . ' $post_ev_id: ' . $post_ev_id . ' $authorized_user: ' . $authorized_user . ' $ev_id: ' . $ev_id . ' $argdir_ev: ' . $arg['dir_ev'] . ' $argdcp_nav: ' . $arg['dcp_nav'] . ' enabled_access: ' . $arg['enabled_access'] . ' stat_catagory: ' . $arg['stat_catagory'] . ' roster_level: ' . $arg['roster_level'] . ' roster_dvs: ' . $arg['roster_dvs'] . ' <- END' );
$g365_stat_leader = cj_remote_stat_leader(['post_level_val'=>(empty($post_level_val) ? "" : $post_level_val), 'post_dvs_val'=>(empty($post_dvs_val) ? "" : $post_dvs_val), 'post_stat_val'=>(empty($post_stat_val) ? "" : $post_stat_val), 'post_ev_val'=>(empty($post_ev_val) ? "" : $post_ev_val), 'select_level'=>(empty($post_ros_level) ? "" : $post_ros_level), 'post_dvs'=>(empty($post_dvs) ? "" : $post_dvs), 'post_stat_catagory'=>(empty($post_stat_catagory) ? "" : $post_stat_catagory), 'post_ev_id'=>(empty($post_ev_id) ? "" : $post_ev_id), 'authorized_user'=>(empty($authorized_user) ? "" : $authorized_user), 'filter_ev_id'=>(empty($ev_id) ? "" : $ev_id), 'is_dcp'=>'hhh', 'hhh_ev_id'=>$arg['dir_ev'], 'hhh_dcp_nav'=>$arg['dcp_nav'], 'hhh_enable_access'=>$arg['enabled_access'], 'hhh_stat_catagory'=>$arg['stat_catagory'], 'hhh_roster_level'=>$arg['roster_level'], 'hhh_roster_dvs'=>$arg['roster_dvs'] ]);
// g365_stat_leader($arg['dir_ev'], $arg['stat_catagory'], "", "", $arg['roster_level'], 2, $arg['roster_dvs'], "");
  $g365_stat_leader = json_decode(json_encode($g365_stat_leader), true);
print_r($g365_stat_leader);
  $key_level = $g365_stat_leader[1];
// print_r($key_level);
  $stat_lists = $g365_stat_leader[2];
// print_r($stat_lists);
  if(isset($_POST['year'])){ $select_year = $_POST['year']; };
  if(!isset($post_stat_catagory)){$post_stat_catagory = $g365_stat_leader[9];}
  $default_event_info = $g365_stat_leader[3];
  $event_info = $g365_stat_leader[4];
  $default_num_pl = 5;
  $set_top_pl_num = 50; //echo "<pre>"; print_r($g365_stat_leader[13]); echo "</pre>";
  $ev_location = str_replace('|', ' | ', $event_info['locations']);
  if(!empty($g365_stat_leader[10])):/*if-main-page*/
?>
  <div class="all-tournament__details test" style="flex-wrap: wrap;flex-direction: row;">
    <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $event_info['logo_img']; ?>" alt="<?php echo $event_info['name']; ?>"></div>
    <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo date("F j, Y",strtotime($event_info['eventtime'])); ?></h4></div>
    <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
  </div>
  <div>
  <!--   <?php if($arg['dcp_nav'] == true): ?><div class="medium-padding text-center"><?php echo g365_submenu_type(['ev_id'=>$arg['dir_ev'], 'type'=>$ev_type], 7);  ?></div><?php endif; ?> -->
  <form method="post" action="" id="statleader-form" class="grid-x">
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 152px">
      <select name="roster_dvs" id="roster_dvs" style="border-radius: 20px">
        <option value="">All Divisions</option>
        <?php for($i = 40; $i <= 47; $i++): //if(($i > 11 && $i < 18) || ($i > 39 && $i < 48 )):/*if-a*/ ?>
          <option <?php if($i == 45){continue;} if(isset($post_dvs) && $post_dvs == $i){echo 'selected= "selected"';} ?> value="<?php echo $i ?>"><?php echo $g365_stat_leader[1][$i]; ?></option> 
        <?php /*endif;if-a*/ endfor; ?>
      </select>
    </div>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 200px">
      <select name="roster_level" id="roster_level" style="border-radius: 20px"> 
        <option value="">All Levels of Play</option>
        <?php foreach($g365_stat_leader[8] as $index => $level_list):/*foreach-a*/ ?>
          <option <?php if($index == 1){continue;} if(isset($post_ros_level) && $post_ros_level == $g365_stat_leader[8][$index]){echo 'selected= "selected"';} ?> value="<?php echo $level_list ?>"><?php echo $g365_stat_leader[8][$index]; ?></option> 
        <?php endforeach;/*endforeach-a*/ ?>
      </select>
    </div>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 200px">
      <select name="stat_catagory" id="stat_catagory" style="border-radius: 20px"> 
        <?php foreach($stat_lists as $index => $stat_list): $stat_type = $stat_lists[$index]['type']; $stat_alias = $stat_lists[$index]['alias'];?>
        <option <?php if(isset($post_stat_catagory) && $post_stat_catagory == $stat_alias){echo 'selected= "selected"';} ?> value="<?php echo $stat_alias ?>"><?php echo ($stat_type."s") ?></option> 
        <?php endforeach; ?>
      </select>
    </div>
    <?php if(empty($ev_id)): ?><div class="small-12 medium-12 large-3 small-padding-right" style="width: 260px"><?php echo $g365_stat_leader[6]; ?></div><?php endif; ?>
    <div class="small-12 medium-12 large-3 small-padding-right" style="width: 135px">
      <input type="submit" id="slb_submit_btn" value="Filter Options" class="slb_btn small-12 medium-12 large-3" />
    </div>
  </form>
 </div>
<!-- // Event: Top 50 players -->
<div id="dialong_div"></div>
<div class="stat_leaderboard grid-x small-padding-top max-width-1200">
  <?php if(!empty($g365_stat_leader[0][$post_stat_catagory])):/*if-2a*/ foreach($g365_stat_leader[0] as $index => $default_pl_stat):/*foreach-2a*/ ?>
  <div class="small-12 medium-12 large-12">
    <div class="responsive-table">
      <div class="tableheader--sticky show-for-small-only">
            <p>Player</p>
            <p>Level</p>
            <p>Division</p>
            <p>PPG</p>
      </div>
      <table class="stat-table">
        <?php 
          echo $g365_stat_leader[7];
          foreach($default_pl_stat as $pl_stat): /*foreach-2b*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $fav_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'player_id'=>$player_id, 'user_id'=>get_current_user_id()], 'SELECT');
          if(($pl_stat['is_fav'] === 'true')){$fav_icon='⭐️';}else{$fav_icon='<a href="#" class="btn-flip" data-back="⭐️" data-front="✩"></a>';}
        ?>
        <tr>
          <td>
            <div class="flex item-center">
              <div class="fav-btn small-padding-right">
                <?php $is_fav_icon = get_dcp_auth_data(['user_id'=>$authorized_user, 'ev_id'=>(empty($ev_id) ? "" : $ev_id), 'fav_icon'=>(empty($fav_icon) ? "" : $fav_icon), 'pl_id'=>(empty($player_id) ? "" : $player_id), 'enabled_access'=>true], 'fav-star'); echo (empty($is_fav_icon['fav_icon']) ? "" : $is_fav_icon['fav_icon']); ?>
              </div>
              <div>
                <a class="flex align-middle" href="<?php echo $pl_stat['player_profile']; ?>" target="_blank">
                  <div class="small-padding-right">
                    <figure>
                      <div class="image-wrapper">
                        <img alt="<?php echo $pl_stat['player_nickname']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $pl_stat['player_img']; ?>" class="rounded">
                      </div>
                    </figure>
                  </div><?php echo $pl_stat['player_name']; ?>
                </a>
              </div>
            </div>
          </td>
          <td class="text-left small-padding-right"><?php echo !empty($pl_stat['player_division']) ? $pl_stat['player_division'] : '-'; ?></td>
          <td class="text-left small-padding-right"><?php echo !empty($g365_stat_leader[1][$pl_stat['player_level']]) ? $g365_stat_leader[1][$pl_stat['player_level']] : '-'; ?></td>
          <td class="text-left small-padding-right"><?php echo !empty($pl_stat[$index]) ? $pl_stat[$index] : '0'; ?></td>
        </tr>
        <?php $is_rvl_enabled = get_dcp_auth_data(['user_id'=>$authorized_user, 'ev_id'=>$ev_id, 'fav_icon'=>$fav_icon, 'pl_id'=>$player_id, 'pl_name'=>$pl_stat['player_name'], 'pl_nickname'=>$pl_stat['player_nickname'], 'fav_data'=>$fav_data, 'pl_img'=>$pl_stat['player_img'], 'pl_grad_year'=>$pl_stat['pl_grad_year'], 'pl_position'=>$pl_stat['pl_position'], 'pl_height'=>$pl_stat['pl_height'], 'gpa'=>$pl_stat['pl_gpa'], 'sat'=>$pl_stat['pl_sat'], 'act'=>$pl_stat['pl_act'], 'pl_contact_info'=>$pl_stat['pl_contact_info'], 'enabled_access'=>true], 'fav-star'); echo (empty($is_rvl_enabled['enabled_reveal']) ? "" : $is_rvl_enabled['enabled_reveal']); endforeach;/*endforeach-2b*/ ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php echo ajax_data_xfer(['class_name'=>'fav_pl', 'url'=>$ajax_url], 'add_fav'); endforeach;/*endforeach-2a*/ else:/*else-2a*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo $g365_stat_leader[5]; ?></h3>
  <?php endif;/*endif-2b*/ echo dcp_custom_js(null,'dcp-fav-star'); ?>
</div>
<?php else: echo "<div class='text-center'><h4>".g365_message()['not_available']."</h4></div>"; endif;/*endif-main-page*/ ?>