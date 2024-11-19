<?php
$key_level = (g365_return_keys('g365_grade_key')); 
$select_year = $_POST['year'];
$select_level = $_POST['roster_level'];
$limit_result = 50;
$post_stat_type = $_POST['stat_type'];
$post_stat_catagory = $_POST['stat_catagory'];
$stat_lists = g365_stat_list();
$current_year = date('Y');
$number_of_years = 3;
$default_num_pl = 5;
$set_top_pl_num = 50;
$available_stat_years = g365_year_end_date('event_time', most_recent_event(2));
if(!isset($select_year)){
  $select_year = $available_stat_years[0]; // Set a default year to the lastest available year in dropdwon
  $default_pl_stats = slb_by_year($select_year, 1, $arg=array('level', 'stat_type'), $org_id, $team_id);
  if(array_filter(array_map('array_filter', $default_pl_stats))){
    $select_year = $available_stat_years[0];
  }else{
    $select_year = $available_stat_years[1];
  }
}
// echo "<pre>";
// print_r($default_pl_stats);
// echo "</pre>";
?>
<div>
  <form method="post" id="statleader-form" class="grid-x">
    <div class="small-12 large-3 small-padding-right" style="width: 200px">
      <select name="year" id="year" style="border-radius: 20px">
        <?php foreach($available_stat_years as $available_stat_year): ?>
          <option <?php if(isset($select_year) && $select_year == $available_stat_year){echo 'selected= "selected"';} ?> value="<?php echo $available_stat_year ?>"><?php echo g365_date_format($available_stat_year, 2)?> Season</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="small-12 large-3 small-padding-right" style="width: 154px">
      <select name="roster_level" id="roster_level" style="border-radius: 20px"> 
        <option>All Levels</option>
        <?php for($i = 8; $i <= 47; $i++): if(($i > 7 && $i < 18) || ($i > 39 && $i < 48 )): ?>
          <option <?php if(isset($select_level) && $select_level == $i){echo 'selected= "selected"';} ?> value="<?php echo $i ?>"><?php echo $key_level[$i]; ?></option> 
        <?php endif; endfor; ?>
      </select>
    </div>
    <div class="small-12 large-3" style="width: 200px">
      <select name="stat_catagory" id="stat_catagory" style="border-radius: 20px"> 
        <option value="">All Stat Categories</option>
        <?php $stat_lists = g365_stat_list(); foreach($stat_lists as $index => $stat_list): $stat_type = $stat_lists[$index]['type']; $stat_alias = $stat_lists[$index]['alias'];?>
        <option <?php if(isset($post_stat_catagory) && $post_stat_catagory == $stat_alias){echo 'selected= "selected"';} ?> value="<?php echo $stat_alias ?>"><?php echo $stat_type ?></option> 
        <?php endforeach; ?>
      </select>
    </div>
    <input type="submit" value="Filter Options" class="slb_btn small-12 medium-12 large-12" />
  </form>
</div>
<?php 
  /* Default top 5 players from all 5 stat types */
  if( (!isset($select_year) && !isset($select_level) && !isset($post_stat_catagory) ) || (!is_numeric($select_level) && empty($post_stat_catagory)) ): $default_pl_stats = slb_by_year($select_year, 1, $arg=array('level', 'stat_type'), $org_id, $team_id);/*if-2*/
  if(array_filter(array_map('array_filter', $default_pl_stats))): /*if-filter*/
?>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($default_pl_stats)):/*if-2a*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-2a*/ ?>
  <div class="small-12 medium-6 large-6">
    <div class="responsive-table">
      <table class="stat-table">
        <?php
          echo leaderboard_tb_form($index, $level);
          foreach($default_pl_stat as $pl_stat):/*foreach-2b*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $select_year), 2);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_name']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo $pl_stat[$index]; ?></td>
        </tr>
        <?php endforeach;/*endforeach-2b*/ ?>
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
  <?php endforeach;/*endforeach-2a*/ ?>
  <?php endif;/*endif-2a*/ if(empty($default_pl_stats)):/*if-2b*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-2b*/ ?>
</div>
<?php else:/*else filter*/ ?>
 <h3 class="text-center small-padding small-12 medium-12 large-12">There is no result for selected year</h3>
<?php endif /*endif-filter*/; endif;/*endif-2*/
  /* Top 5 players by selected year, selected level and ALL stat types */
  if( !empty($select_year) && is_numeric($select_level) && empty($post_stat_catagory) ):/*if-3*/
  $pl_data_type = g365_stat_leader($event_id = null, $post_stat_catagory, $select_year, '', $select_level, $type = 6);
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '4', '', $default_num_pl, $post_stat_catagory);
?>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)):/*if-3a*/ ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):/*foreach-3*/ ?>
  <div class="small-12 medium-6 large-6">
    <div class="responsive-table">
      <table class="stat-table">
        <?php
          echo leaderboard_tb_form($index, $level = true);
          foreach($default_pl_stat as $pl_stat):/*foreach-3a*/
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $select_year), 2);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_name']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td><?php echo $key_level[$pl_stat['player_level']]; ?></td>
          <td><?php echo $pl_stat[$index]; ?></td>
        </tr>
        <?php endforeach;/*endforeach-3a*/ ?>
<!--         <tr class="text-center slb_more_list" id="<?php echo $index ?>" style="font-weight:bold;cursor:pointer;">
          <td class="buttonization">
            <div>
              <span>View Complete Leaders</span>
            </div>
          </td>
        </tr> -->
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach;/*endforeach-3*/ endif/*endif-3a*/; if( empty($pl_data_type) ):/*if-3b*/ ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif;/*endif-3b*/ ?>
</div>
<?php endif;/*endif-3*/
  /* Top 50 players with selected level and selected stat catagory */
  if( !empty($select_year) && is_numeric($select_level) && !empty($post_stat_catagory) ):/*if-4*/
  $pl_data_type = g365_stat_leader($event_id = null, $post_stat_catagory, $select_year, '', $select_level, $type = 7);
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  $default_pl_stats = g365_stat_table_filter($pl_data_type, '3', '', $set_top_pl_num, $post_stat_catagory);
?>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($pl_data_type)): ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat): ?>
  <div class="small-12 medium-12 large-12">
    <div class="responsive-table">
      <table class="stat-table">
        <?php
          echo leaderboard_tb_form($index, $level);
          foreach($default_pl_stat as $pl_stat):
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $select_year), 2);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_name']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo $pl_stat[$index]; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; if(empty($pl_data_type)): ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif; ?>
</div>
<?php endif;/*endif-4*/
  /* Top 50 players all levels by selected stat catagory */
  if( !empty($select_year) && !is_numeric($select_level) && !empty($post_stat_catagory) ): $default_pl_stats = slb_by_year( $select_year, 2, array('level', 'stat_type'=>$post_stat_catagory), $org_id, $team_id );/*if-1*/
  if (array_filter(array_map('array_filter', $default_pl_stats))): /*if-filter*/
?>
<div class="stat_leaderboard grid-x small-padding-top">
  <?php if(!empty($default_pl_stats)): ?>
  <?php foreach($default_pl_stats as $index => $default_pl_stat):?>
  <div class="small-12 medium-12 large-12">
    <div class="responsive-table">
      <table class="stat-table">
        <?php
          echo leaderboard_tb_form($index, $level);
          foreach($default_pl_stat as $pl_stat):
          $player_nickname = $pl_stat['player_nickname']; 
          $player_id = $pl_stat['player_id'];
          $event_nickname = $pl_stat['event_nickname'];
          $img_link = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
          $player_profile = custom_link(array($player_nickname, $select_year), 2);
        ?>
        <tr>
          <td>
            <div>
              <a class="flex align-middle" href="<?php echo $player_profile ?>" target="_blank">
                <div class="small-padding-right">
                  <figure>
                    <div class="image-wrapper">
                      <img alt="<?php echo $pl_stat['player_name']; ?>" title="<?php echo $pl_stat['player_name']; ?>" data-mptype="image" src="<?php echo $img_link; ?>" class="rounded">
                    </div>
                  </figure>
                </div><?php echo $pl_stat['player_name']; ?>
              </a>
            </div>
          </td>
          <td class="text-right small-padding-right"><?php echo $pl_stat[$index]; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; if(empty($default_pl_stats)): ?>
    <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['unavailable_opts']; ?></h3>
  <?php endif; ?>
</div>
<?php else: /*else-filter*/ ?>
  <h3 class="text-center small-padding small-12 medium-12 large-12"><?php echo g365_message()['selected_year']; ?></h3>
<?php endif; /*endif-filter*/ endif;/*endif-1*/ ?>