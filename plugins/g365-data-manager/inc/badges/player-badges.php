<?php //g365_passport_validation('subscription-validation'): ?>
<div class="grid-x medium-12 large-12 badge_container">
<div class="badge-overview large-12">
<?php
  $player_badge_data = g365_badges('player-badge-views', ['pl_id'=>$player_id])['player_badge_data'];
  $default_logo = get_site_url() . '/wp-content/themes/g365-press/assets/badges/event-stats/empty-badge.png';
  if(!empty($player_badge_data)): /*if-main-array*/
  foreach($player_badge_data as $index => $player_badge_views): /*foreach-main*/
  if($index == 'Year'){ $hide_year = 'display:none'; }
?>
<!-- New Badge Container -->
  <div class="grid-y badge-category" id="<?php echo 'bdg_'.strtolower(str_replace(' ', '_', $index)); ?>" style="width: 100%; <?php echo $hide_year; ?>">
    <div><h2 class="bbadge-category--heading"><?php echo $index; ?></h2></div>
    <?php
      $sorting_bdg_order = array('indi_gm_indi_event', 'avg_cond_indi_event', 'cumulative_individual_event');
      $new_player_bdg_views = array();
      foreach ($sorting_bdg_order as $key) { $new_player_bdg_views[$key] = $player_badge_views[$key]; }
      foreach($new_player_bdg_views as $catagory_index => $player_badge_view): /*foreach-1*/
      // Create custom string/variable for badge catagory description
      switch($catagory_index){
        // Three data types are needed for each badge catagories($badge_type, $url_folder, $url_alias)
        case 'cumulative_individual_event':
          $custom_string = 'Totals (Max 6 Games)';
          $badge_type = '2';
          $url_folder = 'event-totals';
          $url_alias = '';
          $bdg_type_label = 'Total';
          break;
        case 'avg_cond_indi_event':
          $custom_string = 'Average (Min 3 Games)';
          $badge_type = '0';
          $url_folder = 'event-avg';
          $url_alias = '-avg';
          $bdg_type_label = 'Average';
          break;
        case 'indi_gm_indi_event':
          $custom_string = 'Individual Game';
          $badge_type = '1';
          $url_folder = 'in-game';
          $url_alias = '-in-game';
          $bdg_type_label = 'Individual';
          break;
        default:
          $custom_string = $catagory_index;
          $is_year_only = 'true';
          $url_folder = 'year-totals';
          $url_alias = '';
          $bdg_type_label = $catagory_index;
          // Set yearly badge to show total only
          $badge_type = '2';
      }
    ?>
      <div class="badge-row no-margin-bottom">
        <h3><?php echo $custom_string; ?></h3>
        <div class="grid-x align-spaced" id="<?php echo 'bdg_'.$catagory_index; ?>">
          <?php
            $bdg_stat_catagories = badge_catagory('badge-front-end-stat-catagory')['badge_awards'];
            $bdg_data = json_decode('['.$bdg_stat_catagories[$badge_type]->bdg_data.']');
            $bdg_data = json_decode(json_encode($bdg_data), true);
            usort($bdg_data, function($a, $b){ return $a['type'] <=> $b['type']; });
            foreach($bdg_data as $sub_index => $bdg_stat_catagory):
            if(is_array($bdg_stat_catagory['type'])){ krsort($bdg_stat_catagory['type']); }
            $stat_label = badge_catagory('badge-range-catagory')[$bdg_stat_catagory['type']];
            $total_only_array = $player_badge_view[$stat_label];
            !empty($total_only_array) ? $get_keys = end(array_keys($total_only_array)) : $get_keys = '';
            $badge_logo = badge_catagory('badge-front-end-stat-catagory', ['badge_range'=>$get_keys])['badge_url'][0]->logo_img;
            if(is_array($total_only_array)){ $unlocked_badge_stat_number = count($total_only_array); }else{ $unlocked_badge_stat_number = '0'; }
            $bdg_type_name = ucfirst(str_replace('three_pt', '3 Pointers', $stat_label));
          ?>
            <div class="badge-col grid-y align-middle  small-4 medium-2 bdg_main_catagories">
              <img src="<?php echo !empty($badge_logo) ? $badge_logo : $default_logo; ?>">
              <p><?php echo $bdg_type_name; ?></p>
              <strong><?php echo ($unlocked_badge_stat_number.' of '. $bdg_stat_catagory['count']); ?></strong>
            </div>
            <div class="grid-y align-spaced bdg_stat_view small-padding-bottom" style="display:none;">
              <div class="grid-x align-justify">
                <h3><?php echo $bdg_type_name; ?></h3>
                <p><?php echo ($unlocked_badge_stat_number.' of '. $bdg_stat_catagory['count']); ?></p>
              </div>
              <!--   should be dynamic change per category -->
              <div class="badges__inner-container">
                <div class="grid-x align-spaced">
                  <?php 
                    $stat_bdg_details = badge_catagory('badge-front-end-stat-catagory')['badge_range'];
                    foreach($stat_bdg_details as $index => $each_stat_bdg_data):
                      $each_stat_badges = json_decode($each_stat_bdg_data->bdg_stat_type, true);
                      if(!empty($each_stat_badges[$bdg_stat_catagory['type']])){
                        $each_stats_badges = explode(',', $each_stat_badges[$bdg_stat_catagory['type']]);
                      }else{ $each_stats_badges = ''; }
                      // Check if giving stat is not an empty array
                      if(!empty($each_stats_badges[0])):
                        usort($each_stats_badges, function ($a, $b){ return trim(explode('_', $a)[1]) - trim(explode('_', $b)[1]); });
                        foreach($each_stats_badges as $each_stat_badge):
//                           $unlocked_badge_keys = array_keys($total_only_array);
                          !empty($total_only_array) ? $unlocked_badge_keys = array_keys($total_only_array) : $unlocked_badge_keys = '';
                          if(is_array($unlocked_badge_keys)){
                            if(in_array($each_stat_badge, $unlocked_badge_keys)){ $is_unlocked = ''; }else{ $is_unlocked = 'badge--empty'; }
                          }
                  ?>
                        <div class="badge-col grid-y align-middle  small-4 medium-2 <?php echo $is_unlocked; ?>">
                          <img src="https://grassroots365.com/wp-content/themes/g365-press/assets/badges/event-stats/<?php echo $url_folder.'/'.$stat_label.'/'.$stat_label.$url_alias.'-'.trim(explode('_', $each_stat_badge)[1]).'.png'; ?>">
                        </div>
                  <?php endforeach; endif; endforeach; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <button class="button button--borderless badge-button" id="<?php echo $catagory_index; ?>" onclick="badge_navigator(this)">View All <?php echo $bdg_type_label; ?> Achievements</button>
        </div>
      </div>
    <?php endforeach; /*endforeach-1*/ ?>
  </div>
<?php endforeach; /*endforeach-main*/ else: echo ('<h4 class="text-center">'.g365_message()['not_available'].'</h4>'); endif;/*endif-main-array*/ ?>
</div>
<?php echo g365_custom_js('badge-cookies'); echo g365_custom_js('player-badges'); ?>