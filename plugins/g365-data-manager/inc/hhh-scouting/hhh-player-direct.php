<!-- <p>Content for player directory</p> 
* Description: Main directory of the scouting report.
-->
<?php  
$unlocked = 0;
$current_user_id = get_current_user_id();
// echo $current_user_id . ' ';
if($current_user_id != 0){
$unlocked = g365_check_scouting_unlocked($current_user_id);
}
if(empty($unlocked)){$unlocked = 0;}

// echo "player " . $unlocked;

if($unlocked === 0 ){
  
?>
  <div class="scouting-false-container">
    <div class="scouting-false-mid">
      <h3 class="small-margin-top">
        Scouting Report Inactive.
      </h3>
      <p>
        In order to obtain access to all functionalities of the scouting report please login to account with access or purchase below.
      </p>
      <a class="button buttonization scouting-purchase directory-access" href="https://sportspassports.com/product/hhh-scouting-report/">Purchase Scouting Report </a>
    </div>
  </div>
  
<?php
}else{

?>
<h2>
  Player Search 
<!--   <?php    echo $player_id;     ?> -->
</h2>

<div class="hhh-player-direc">
  Search All Players in the Hype Her Hoops Directory
  
</div>

<?php 
  $livesearching = '<div class="relative small-12 medium-12 large-12 large-padding-bottom scouting-search-padd">
			<span class="search-mag fi-magnifying-glass"></span>
			<input type="text" class="search-hero" id="search" placeholder="Enter Player Name" autocomplete="off" autofocus> 
      <div id="result" class="resulting_custom_live"></div>
		</div>';

echo $livesearching;
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="../wp-content/plugins/g365-data-manager/inc/hhh-scouting//custom-js/hhh-livesearch.js"></script>

<?php
      
}

