<!-- 
* Description: shows all the players write ups written by our coordinators (i think marlon and quincy) as well as all the players to watch at the end of the scroll.
-->

<!-- <?php $play_id = get_current_user_id(); echo $play_id; echo ' player_id'; echo $player_id; ?> -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<select class="filter-scouting-dropdown small-margin-top" id="levelFilter">
  <option value="">All Levels</option>
  <option value="Division I - Power 5">Division I - Power 5</option>
  <option value="Division I - Mid Major">Division I - Mid Major</option>
  <option value="Division I - Low Major">Division I - Low Major</option>
  <option value="Division II">Division II</option>
  <option value="Division III">Division III</option>
  <option value="NAIA">NAIA</option>
  <option value="JUCO - Junior College">JUCO - Junior College</option>
</select>

<script>
  // Add this script just below the dropdown code
  $(document).ready(function () {
    // Initial load
    filterPlayersByLevel();

    // Event listener for dropdown change
    $("#levelFilter").change(function () {
      filterPlayersByLevel();
    });

    function filterPlayersByLevel() {
      var selectedLevel = $("#levelFilter").val();

      // Hide all players initially
      $(".recruits-box").hide();

      // Show players based on the selected level
      if (selectedLevel === "") {
        $(".recruits-box").show();
      } else {
        $(".recruits-box[data-level='" + selectedLevel + "']").show();
      }
    }
  });
</script>

<?php

$grabPlayersStats = grabplayerstats($player_id);
// $grabPlayersStats = json_encode($grabPlayersStats);


if(!empty($grabPlayersStats)): 
  foreach($grabPlayersStats as $pl_data):
    if(empty($pl_data->profile_img)){
      $profie_img = 'https://sportspassports.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
    }else{
      $profie_img = 'https://sportspassports.com/wp-content/uploads/player-profiles/' . $pl_data->profile_img;
    }
    $player_data = g365_get_profile( $pl_data->id, false, 0 );
//     print_r($player_data);
    $xtra_pl_data = json_decode($pl_data->trends);
//     print_r($xtra_pl_data);
?>

<div class="grid-x home_fav_box recruits-box" id="<?php echo $pl_data->id; ?>" data-level="<?php echo $xtra_pl_data->level_division; ?>">
  <div class="hhh_writeups_container">
    <div class="report-left-container">
      <img src="<?php echo $profie_img; ?>" alt="player-img" class="writeups_pl_img">
      <div class="player_details">
        <p class="additional-info">Class:<strong> <?php echo $pl_data->grad_year; ?></strong></p>
        <p class="additional-info">Height:<strong> <?php echo $pl_data->height_ft . "' " . $pl_data->height_in . "''"; ?></strong></p>
        <p class="additional-info">Hometown:<strong> <?php echo $pl_data->city . ", " . $pl_data->state; ?></strong></p>
        <p class="additional-info">Club:<strong> <?php echo $player_data->club_name; ?></strong></p>
        <p class="additional-info">Position:<strong> <?php echo $player_data->position_name; ?></strong></p>
      </div>
    </div>
    
    <div class="player-name-container">
      <p class="hhh_player_name"><?php echo $pl_data->name; ?></p>
      
      <?php 
      $event_game_avg = avg_game_stat($pl_data->id, $player_id);
      ?>
      
      <table class="<?php if($player_id === '863'){echo 'hidden hide '; } ?>text-center ghost-white-bg no-margin-bottom">
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
              <td><?php echo $event_game_avg['avg_pt']; ?></td>
              <td><?php echo $event_game_avg['avg_reb']; ?></td>
              <td><?php echo $event_game_avg['avg_ast']; ?></td>
              <td><?php echo $event_game_avg['avg_blk']; ?></td>
              <td><?php echo $event_game_avg['avg_stl']; ?></td>
              <td><?php echo $event_game_avg['avg_three']; ?></td>
            </tr>
          </tbody>
      </table>
    
      <div class="scouting-strength">
        
        <p class="small-margin-bottom small-margin-top">
          <strong>
          Evaluation:
          </strong>
          <?php echo $pl_data->evaluation; ?>
        </p>
        <p class="no-margin-bottom">
          <strong>
          Offers:
          </strong>
          <?php echo $xtra_pl_data->offers; ?>
        </p>
        <p class="no-margin-bottom">
          <strong>
          Level:
          </strong>
          <?php echo $xtra_pl_data->level_division; ?>
        </p>
        <a class="large-margin-top scouting-link" href="https://sportspassports.com/player/<?php echo $pl_data->nickname; ?>">View Full Profile</a>
      </div>
      
      <div class="scouting-weak">
        <p class="no-margin-bottom small-margin-top"><strong>Strengths</strong></p>
        <p>
          <?php 
          $strengths_arr = json_decode($pl_data->strengths, true);
          $totalElements = count($strengths_arr);
          foreach ($strengths_arr as $key => $value) {
              echo $value;
              if ($key < $totalElements - 1) {
                  echo ', ';
              }
          }
          ?>
        </p>
        <p class="no-margin-bottom small-margin-top"><strong>Weaknesses</strong></p>
        <p>
          <?php 
          $weak_arr = json_decode($pl_data->weaknesses, true);
          $totalElements = count($weak_arr);
          foreach ($weak_arr as $key => $value) {
              echo $value;
              if ($key < $totalElements - 1) {
                  echo ', ';
              }
          }
          ?>
        </p>
        
      </div>
    </div>
  </div>
</div>
<?php
endforeach; 
endif;
?>

<div class="grid-x home_fav_box players-watch">
  <h2 class="watch-center">
    Players to Watch
  </h2>
  <?php
  if(!empty($grabPlayersStats)): 
  foreach($grabPlayersStats as $pl_data):
  $xtra_pl_data = json_decode($pl_data->trends);
//     print_r($xtra_pl_data);
  if($xtra_pl_data->player_to_watch == 'True'){
    $player_data = g365_get_profile( $pl_data->id, false, 0 );
  ?>
  <div class="watch-container medium-margin-bottom">
    
  <p class="watch-play-name no-margin-bottom">
    <strong><?php echo $pl_data->name; ?></strong>
  </p>
  <p class="watch-play-desc no-margin-bottom">
    <?php echo $pl_data->height_ft . "'" . $pl_data->height_in; ?> <?php echo ' ' . $pl_data->grad_year . ' ' . $player_data->position_name . ' <br>' . $player_data->club_name; ?>
  </p>
  <a class="no-margin-top scouting-link" href="https://sportspassports.com/player/<?php echo $pl_data->nickname; ?>">View Full Profile</a>  
  </div>
  
  <?php
  }
  endforeach; 
  endif;
  ?>
</div>