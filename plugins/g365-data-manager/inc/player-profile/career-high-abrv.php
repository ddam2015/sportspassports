<!-- Current Season Averages -->
<?php 
  // if( $tournament_events !== '' && !empty($tournament_events) ) :
  // Current year stat start from Sep 1 - Aug 31
  !empty($event_date) ? $event_date = $event_date : $event_date = '';
//   $pl_year_list  = g365_season_stat($player_id, $event_date, 'not_date_range', $arg[1]); // All available stats from different events
  $pl_career_highs = g365_season_stat($player_id, $arg[0], 'career_high', $arg[1]); // Stats within date range
  $check_subscription = stat_subscription($player_id);
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
  if( !empty($pl_career_high) ):
?>
<div id="profile-stats-avg" class="cell large-margin-bottom small-12">
  <div class="ave_field table-scroll">
    <h3 class="stats__table-heading">Career Highs</h3>
    <table class="text-center ghost-white-bg no-margin-bottom">
      <tbody class="stats__table--player">
        <tr>
          <th>PTS</th>
          <th>REB</th>
          <th>AST</th>
          <th>BLK</th>
          <th>STL</th>
          <th>3PM</th>
        </tr>
        <tr class="color-body emphasis">
          <td><?php echo round(max($pl_career_high['pts']), 2); ?></td>
          <td><?php echo round(max($pl_career_high['rbs']), 2); ?></td>
          <td><?php echo round(max($pl_career_high['ast']), 2); ?></td>
          <td><?php echo round(max($pl_career_high['blk']), 2); ?></td>
          <td><?php echo round(max($pl_career_high['stl']), 2); ?></td>
          <td><?php echo round(max($pl_career_high['three_pt']), 2); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php endif; //endif;/*endif-cs*/?> 