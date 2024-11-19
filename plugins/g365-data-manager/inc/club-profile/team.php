<?php $select_year = year_dd_opt('cp_date_selector')[1]; echo year_dd_opt('cp_date_selector')[0]; $check_date = g365_date_format($select_year, 4); ?>
  <ul class="accordion club-rosters grid-x large-12">
    <?php $ros_datas = cp_roster_list($arg[0]->id, $select_year);
    $unique_teams = array();
    foreach($ros_datas as $item) {
      if(!isset($unique_teams[$item->team_id])) {
        $unique_teams[$item->team_id] = $item;
      }
    }
    $ros_datas = array_values($unique_teams);
//     echo "<script>console.log(" . $select_year . ");</script>";
    $group_team_levels = array();
    foreach ($ros_datas as $item) {
      if(!empty($item->team_level)){ $group_team_levels[$item->team_level][] = $item; }
    }
    asort($group_team_levels, SORT_NUMERIC);
    if(!empty($group_team_levels)):
    foreach( $group_team_levels as $ros_dex => $ros_data ) :
      echo ( '<div class="team_ros grid-x small-12 medium-12 large-12 large-up-5"><h3 class="large-12">' . g365_level_key($ros_dex) . '</h3>' );
      foreach($ros_data as $team_list): 
      $full_team_url = g365_level_key($team_list->team_level) . ((empty($team_list->team_name)) ? '' : ' ' . $team_list->team_name); 
      $full_team_url = strtolower(str_replace(array(' ','(',')'),array('-','',''),$full_team_url));
      $team_name = rtrim($team_list->team_name, '.');
    ?>
      <li id="team_roster_list" class="team_roster accordion-item cell medium-12 large-12" data-accordion-item>
        <!-- Accordion tab title -->
        <a onclick="cp_form_submit(this)" href="<?php echo get_site_url(); ?>/club/<?php echo $arg[0]->nickname; ?>/teams/<?php echo ($full_team_url."?team_id=".$team_list->team_id."&y=".$select_year); ?>"><?php echo g365_level_key($team_list->team_level) . ((empty($team_list->team_name)) ? '' : ' ' . $team_name); ?></a>
        <?php if( !empty($team_list->coach_name) || !empty($team_list->asst_name) || !empty($team_list->description)) { ?>
        <div class="extra-info grid-container">
          <div class="grid-x grid-margin-x">
          <?php if( !empty($team_list->coach_name) ) echo '<div class="cell shrink">Coach: ' . $team_list->coach_name . '</div>'; ?>
          <?php if( !empty($team_list->asst_name) ) echo '<div class="cell shrink">Asst. Coach: ' . $team_list->asst_name . '</div>'; ?>
          <?php if( !empty($team_list->description) ) echo '<div class="cell auto">Practice: ' . $team_list->description . '</div>'; ?>
          </div>
        </div>
        <?php } ?>
      </li>
      <?php endforeach; ?>
      </div>
    <?php endforeach; else:?>
    <div class="width-full"><p class="text-center"><?php echo g365_message()['selected_year_team']; ?></p></div>
    <?php endif; ?>
  </ul>
<?php echo page_loader($from, 'team_roster_list', 'cp-team-list'); ?>