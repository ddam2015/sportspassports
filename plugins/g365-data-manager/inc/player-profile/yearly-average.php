<!-- Yearly Averages -->
<?php 
  $pl_year_list  = g365_season_stat($player_id, $event_date, $is_date_range = false, $arg); // All available stats from different events
  if(!empty($pl_year_list)):
?>
  <h2 class="large-padding-top">Yearly Season Averages</h2>
<?php endif; ?>
<ul class="tabs separate small-margin-left small-margin-bottom small-up-2 medium-up-3 small-12 medium-12 large-12 text-center grid-x" id="season-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
<?php
  $pl_year_lists = json_decode(json_encode($pl_year_list), true);  //print_r($pl_year_lists);
  foreach( $pl_year_lists as $pl_years ):
  $query_event_date = $pl_years['event_date'];
?>
  <li class="tabs-title cell">
    <a id="click<?php echo preg_replace('/\s+|\.|-/', '', g365_date_format($query_event_date, 2)); ?>" href="#stat<?php echo preg_replace('/\s+|\.|-/', '', g365_date_format($query_event_date, 2)); ?>" class="profile-title block">
      <?php echo g365_date_format($query_event_date, 2); ?>
    </a>
  </li>
  <?php endforeach; ?>
</ul>
  <?php 
    foreach( $pl_year_lists as $pl_years ): //print_r($pl_years);
    $query_event_date = $pl_years['event_date'];
  ?>
  <div class="tabs-content small-margin-left small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
    <div class="tabs-panel" id="stat<?php echo preg_replace('/\s+|\.|-/', '', g365_date_format($pl_years['event_date'], 2)); ?>">
      <div class="medium-padding info-block">
        <div class="grid-x grid-margin-x">
          <div class="cell small-12">
            <?php 
              $pl_season_avg = g365_season_stat($player_id, $query_event_date, $is_date_range = true, $arg);// Stats within date range 
                $current_year = json_decode(json_encode($pl_season_avg), true); //print_r($current_year);
                $current_year = array_filter( $current_year, 'g365_array_filter' ); //print_r($current_year);// Filter out empty game stat
                 if(empty($current_year)){
                  $avg_pt   = '0'; $avg_reb  = '0'; $avg_ast  = '0'; $avg_stl  = '0'; $avg_blk  = '0'; $ave_time_dec = '0';
                 }else{
                  $count = count($current_year); 
                  $total_pt = 0;
                  $total_reb = 0;
                  $total_ast = 0;
                  $total_stl = 0;
                  $total_blk = 0;
                  $total_pl_t = 0;
                  foreach( $current_year as $index => $season_stats ){
                    $season_stat = json_decode($season_stats['stats'], true);
                    $season_time_dec = g365_time_to_decimal($season_stat['time_pl']);
                    $total_pt   += $season_stat['pts'];
                    $total_reb  += $season_stat['rbs']; 
                    $total_ast  += $season_stat['ast']; 
                    $total_stl  += $season_stat['stl']; 
                    $total_blk  += $season_stat['blk']; 
                    $total_pl_t += $season_time_dec;  
                  }
                  $avg_pt   = round($total_pt/$count, 2);
                  $avg_reb  = round($total_reb/$count, 2);
                  $avg_ast  = round($total_ast/$count, 2);
                  $avg_stl  = round($total_stl/$count, 2);
                  $avg_blk  = round($total_blk/$count, 2);
                  $ave_time_dec = round($total_pl_t/$count, 2);
                 }
            ?>
            <div id="profile-stats-avg" class="cell small-12">
               <h3 class="stats__table-heading"><?php echo g365_date_format($query_event_date, 2);  ?> Season Stats</h3>
                <table class="text-center ghost-white-bg no-margin-bottom">
                  <tbody class="stats__table--player">
                    <tr>
                      <th>PTS</th>
                      <th>REB</th>
                      <th>AST</th>
                      <th>STL</th>
                      <th>BLK</th>
                    </tr>
                    <tr class="color-body emphasis">
                      <td><?php echo $avg_pt; ?></td>
                      <td><?php echo $avg_reb; ?></td>
                      <td><?php echo $avg_ast; ?></td>
                      <td><?php echo $avg_stl; ?></td>
                      <td><?php echo $avg_blk; ?></td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>