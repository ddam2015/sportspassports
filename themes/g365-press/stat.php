<?php
/**
 * Template Name: Admin Player Stat 
 */

$data = $_GET['data'];
if (isset($_GET['data'])) {
  $data = $_GET['data'];
} else {
  echo "Selected year parameter is undefined";
}
// if (isset($_GET['data'])) {
//   $data = $_GET['data'];
// } else {
//   echo "Post data is undefined";
// }
echo $data = stripslashes($data);
$data = json_decode($data);
echo $data->year;
$previous_year = bcsub($data->year,'1');
// echo $date;
// echo $previous_year;
$stat_by_years = g365_stat_table_query($previous_year.'-09-01', $data->year.'-08-31', $data_type = 'total_stat');
// $stat_by_years = g365_stat_table_query('2015-09-01', '2016-08-31', $data_type = 'total_stat');
if( !empty($stat_by_years) ):
  if($data_type = 'total_stat'):
    $stat_by_year = json_decode(json_encode($stat_by_years), true);
//     var_dump($stat_by_year);
?>  
    <table class="edit-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Player</th>
          <th>Point</th>
          <th>Rebound</th>
          <th>Assist</th>
          <th>Steal</th>
          <th>Block</th>
        </tr>
      </thead>
<?php
      foreach( $stat_by_year as $season_stats ):
      $pl_id = $season_stats['player_id'];
      $pl_name = $season_stats['player_name'];
      $total_pt = $season_stats['total_point'];
      $total_reb = $season_stats['total_rebound'];
      $total_ast = $season_stats['total_assist'];
      $total_stl = $season_stats['total_steal'];
      $total_blk = $season_stats['total_block'];
?>      
        <tbody>
          <tr class="player-stat">
            <td><?php echo $pl_id ?></td>
            <td><?php echo $pl_name ?></td>
            <td><?php echo $total_pt ?></td>
            <td><?php echo $total_reb ?></td>
            <td><?php echo $total_ast ?></td>
            <td><?php echo $total_stl ?></td>
            <td><?php echo $total_blk ?></td>
          </tr>        
        </tbody>
<?
      endforeach;
  endif;
else:
  echo 'There is no record for selected field';
endif;
?>