<!-- Current Season Averages -->
<?php 
  // if( $tournament_events !== '' && !empty($tournament_events) ) :
  // Current year stat start from Sep 1 - Aug 31
  !empty($event_date) ? $event_date = $event_date : $event_date = '';
  $pl_year_list  = g365_season_stat($player_id, $event_date, 'not_date_range', $arg[1]); // All available stats from different events
  $pl_season_avg = g365_season_stat($player_id, $arg[0], 'is_date_range', $arg[1]); // Stats within date range
  $check_subscription = stat_subscription($player_id);
//   if( $arg[0] == year_exception_list() || (!empty($check_subscription) && in_array($arg[0], $check_subscription[0])) ):/*if-cs*/
  if( !empty($pl_season_avg) ):
    $current_year = json_decode(json_encode($pl_season_avg), true); 
    $current_year = array_filter( $current_year, 'g365_array_filter' ); // Filter out empty game stat
    if(empty($current_year)){
      $avg_pt = '0'; $avg_reb = '0'; $avg_ast = '0'; $avg_stl = '0'; $avg_blk = '0'; $ave_time_dec = '0'; $avg_three_pt = '0';
    }else{
      $count = count($current_year);
      $total_pt = 0;
      $total_three_pt = 0;
      $total_reb = 0;
      $total_ast = 0;
      $total_stl = 0;
      $total_blk = 0;
      $total_pl_t = 0;
      foreach( $current_year as $index => $season_stats ){ 
        $season_stat = json_decode($season_stats['stats'], true);
        if(!empty($season_stat['time_pl'])){ $season_time_dec = g365_time_to_decimal($season_stat['time_pl']); }else{ $season_time_dec = ''; }
        $total_pt   += $season_stat['pts'];
        $total_reb  += $season_stat['rbs']; 
        $total_ast  += $season_stat['ast'];
        $total_stl  += $season_stat['stl']; 
        $total_blk  += $season_stat['blk']; 
        $total_pl_t += (int)$season_time_dec;  
        $total_three_pt   += $season_stat['three_pt'];
      }
      $avg_pt   = $total_pt/$count;
      $avg_reb  = $total_reb/$count;
      $avg_ast  = $total_ast/$count;
      $avg_stl  = $total_stl/$count;
      $avg_blk  = $total_blk/$count;
      $avg_three_pt   = $total_three_pt/$count;
      $ave_time_dec = round($total_pl_t/$count, 2);
    }
?>
<div id="profile-stats-avg" class="cell large-margin-bottom small-12">
  <div class="ave_field large-margin-bottom table-scroll">
    <h3 class="stats__table-heading"><?php echo g365_date_format($arg[0], 2); ?> Season Averages</h3>
    <table class="text-center ghost-white-bg no-margin-bottom">
      <tbody class="stats__table--player">
        <tr>
          <th>PPG</th>
          <th>RPG</th>
          <th>APG</th>
          <th>BPG</th>
          <th>SPG</th>
          <th>3PT</th>
        </tr>
        <tr class="color-body emphasis">
          <td><?php echo round($avg_pt, 1); ?></td>
          <td><?php echo round($avg_reb, 1); ?></td>
          <td><?php echo round($avg_ast, 1); ?></td>
          <td><?php echo round($avg_blk, 1); ?></td>
          <td><?php echo round($avg_stl, 1); ?></td>
          <td><?php echo round($avg_three_pt, 1); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php else: ?>
<div id="profile-stats-avg" class="cell large-margin-bottom medium-padding small-12">
  <div class="ave_field large-margin-bottom table-scroll">
     <h3 class="stats__table-heading"><?php echo g365_date_format($arg[0], 2); ?> Season Averages</h3>
     <p>
       <?php echo g365_date_format($arg[0], 2); echo g365_message()['season_stat']; ?> 
    </p>
  </div>
</div>
<?php endif; //endif;/*endif-cs*/?> 