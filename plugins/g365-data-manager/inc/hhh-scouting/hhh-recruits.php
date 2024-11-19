<!--  
* Description: the my recruits page where it shows the favorited players for the current user logged in.
-->
<!-- <p>Content for recruits <?php echo $player_id ?> </p> -->

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
  
if (isset($_POST['player_id']) && $_POST['ajax_request'] === true) {
$current_active_user = $_POST['player_id'];
}else{
  $current_active_user = $player_id;
}
?>
  
<div id="recruits-reload" class="medium-padding main_fav">
  <h3>Recruits' List</h3>  
  <?php 
  $player_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'user_id'=>$current_active_user], 'SELECT');  
//   print_r($player_data);
  if(!empty($player_data)): 
  foreach($player_data as $pl_data): 
  $pl_id = $pl_data['player_id']; 
  $pl_note = json_decode($pl_data['notes'], true); 
  $pl_data_fields = json_decode($pl_data['pl_data'], true); 
  $rec_id = $pl_data['id']; 
  $pl_info = g365_get_pl_data(['pl_id'=>$pl_id]); 
  $ev_recruit = url_param('type'); 
  if($ev_recruit == 'my-recruits'){ $url = true; }else{ $url = false; } ?>
    <div class="grid-x home_fav_box" id="<?php echo $rec_id ?>">
      <div class="small-6 medium-6 large-1 rm_fav small-margin-bottom" data-toggle="rm_<?php echo $rec_id ?>" data-rm-id="<?php echo $rec_id ?>" >
        <a class="rm_btn hhh_remove_favorite" href="#" role="button" data-rm-id="<?php echo $rec_id ?>" onClick="rm_fav(this)" data-close aria-label="Close reveal">
          <span>remove</span>
          <div class="rm_icon">
            <i class="rm_x fa fa-remove">X</i>
            <i class="rm_x fa fa-check">X</i>
          </div>
        </a>
      </div>
<!--       <?php   
      print_r($pl_info);
      print_r($pl_data_fields);
      ?> -->
      <div class="small-12 medium-6 large-4 flex text-center">
        <div class="cell" data-alphabet="A">
          <a class="emphasis" href="<?php echo get_site_url(); ?>/player/<?php echo $pl_info[0]->nickname; ?>" target="_blank">
              <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="https://sportspassports.com/wp-content/uploads/player-profiles/<?php echo $pl_info[0]->profile_img; ?>" alt="Player headshot for <?php echo $pl_info[0]->name; ?>" src="https://sportspassports.com/wp-content/uploads/player-profiles/<?php echo $pl_info[0]->profile_img; ?>"><br>
              <p><?php echo $pl_info[0]->name; ?></p>
          </a>
        </div>
      </div>
      <div class="info-fav small-12 medium-12 large-6">
         <?php echo cdp_fav_pl_info(['pl_school'=>$pl_info[0]->school,'grad_year'=>$pl_info[0]->grad_year,'position'=>g365_get_pl_data(['pst_id'=>$pl_info[0]->position], 'position')[0]->abbr,'height'=>empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in),'gpa'=>$pl_info[0]->gpa,'sat'=>$pl_info[0]->sat,'act'=>$pl_info[0]->act,'contact_info'=>empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : ($pl_info[0]->email."<br/>".$pl_info[0]->phone)], 'pl_fav'); ?>
      </div>
<!--       <div class="small-12 medium-12 large-3">
        <div class="small-12 medium-12 large-12"><a href="">Link to Passport</a></div>
      </div> -->
<!--       <div class="small-12 medium-12 large-12 fav_note" data-toggle="pl_<?php echo $pl_id; ?>">
        <h5>Notes: <?php echo $pl_note['notes']; ?></h5>
        <i class="fi-pencil float-right" data-pl-id="<?php echo $pl_id?>"></i>
        
      </div> -->
      <div class="small-12 medium-12 large-12">
        <div id="original-pre-submit<?php echo $pl_id ?>" class="small-margin-bottom">Current Notes: <?php echo $pl_note['notes']; ?></div>
        <input type="text" class="notesInput" data-player-id="<?php echo $pl_id ?>" value="<?php echo $pl_note['notes']; ?>"> </input>
<!--         <i class="fi-pencil float-right" data-pl-id="<?php echo $pl_id?>"></i> -->
        <button onclick="editNotes(<?php echo $pl_id ?>, <?php echo $player_id ?>)">Update</button>
        <div id="response<?php echo $pl_id ?>"></div>
      </div>
      <script src="../wp-content/plugins/g365-data-manager/inc/hhh-scouting//custom-js/my-recruits.js"></script>
    </div>
  <?php $cj_new_player = 'https://sportspassports.com/wp-content/uploads/player-profiles/' . $pl_info[0]->profile_img; ?>
  <?php $position_abbr = g365_get_pl_data(['pst_id'=>$pl_info[0]->position], 'position'); 
// echo fav_reveal(['data_toggle'=>'pl_'.$pl_id, 'full_name'=>$pl_info[0]->name, 'pl_nickname'=>$pl_info[0]->nickname, 'data_note'=>'note_'.$pl_id, 'fav_data'=>g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'player_id'=>$pl_id, 'user_id'=>$current_active_user], 'SELECT'), 'pl_id'=>$pl_id, 'pl_img'=>$cj_new_player, 'pl_grad_year'=>(empty($pl_info[0]->grad_year) ? "" : $pl_info[0]->grad_year), 'school'=>(empty($pl_info[0]->school) ? "" : $pl_info[0]->school), 'school'=>(empty($pl_info[0]->school) ? "" : $pl_info[0]->school), 'pl_position'=>(empty($pl_info[0]->position) ? "" : $position_abbr[0]->abbr), 'pl_height'=>(empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in)), 'gpa'=>(empty($pl_info[0]->gpa) ? "" : $pl_info[0]->gpa), 'sat'=>(empty($pl_info[0]->sat) ? "" : $pl_info[0]->sat), 'act'=>(empty($pl_info[0]->act) ? "" : $pl_info[0]->act), 'pl_contact_info'=>(empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : (empty($pl_info[0]->city) ? '-' : $pl_info[0]->city).', '.(empty($pl_info[0]->state) ? '-' : $pl_info[0]->state).'<br/>'.($pl_info[0]->email."<br/>".$pl_info[0]->phone))], 'edit_note'); 
// echo fav_reveal(['rec_id'=>$rec_id, 'data_toggle'=>'rm_'.$rec_id, 'full_name'=>$pl_info[0]->name, 'pl_id'=>(empty($rec_id) ? "" : $rec_id), 'pl_img'=>(empty($cj_new_player) ? "" : $cj_new_player), 'pl_grad_year'=>(empty($pl_info[0]->grad_year) ? "" : $pl_info[0]->grad_year), 'school'=>(empty($pl_info[0]->school) ? "" : $pl_info[0]->school), 'pl_position'=>(empty($pl_info[0]->position) ? "" : $position_abbr[0]->abbr), 'pl_height'=>(empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in)), 'gpa'=>(empty($pl_info[0]->gpa) ? "" : $pl_info[0]->gpa), 'sat'=>(empty($pl_info[0]->sat) ? "" : $pl_info[0]->sat), 'act'=>(empty($pl_info[0]->act) ? "" : $pl_info[0]->act), 'pl_contact_info'=>(empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : (empty($pl_info[0]->city) ? '-' : $pl_info[0]->city).', '.(empty($pl_info[0]->state) ? '-' : $pl_info[0]->state).'<br/>'.($pl_info[0]->email."<br/>".$pl_info[0]->phone))], 'remove_fav'); 
endforeach; 
else: echo ("<p>No player is added to the favorite list</p>");
endif; 
echo ajax_data_xfer(['class_name'=>'rm_pl', 'url'=>$url], 'remove_fav'); 
echo ajax_data_xfer(['class_name'=>'edit_note', 'url'=>$url], 'add_fav'); 
echo dcp_custom_js(['delay'=>500], 'dcp-rm'); ?>
<!-- <script src="../wp-content/plugins/g365-data-manager/inc/hhh-scouting//custom-js/my-recruits-remove.js"></script> -->
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function () {
  // Function to load recruits content
  function loadRecruitsContent() {
//   console.log('domain expansion');
    var playerId = <?php echo $player_id; ?>;
    $("#recruits-reload").html('<p>Loading...</p>');
    
    // Use AJAX to load content without refreshing the page
    $.ajax({
      type: "POST",
      url: window.location.href, // Use the current page as the AJAX handler
      data: { player_id: playerId, ajax_request: true }, // Add a flag for AJAX requests
      success: function (response) {
//         console.log(response);
        // Update the content of the recruits container
        $("#recruits-reload").html($(response).find("#recruits-reload").html());
      },
      error: function (error) {
        console.error("Error loading recruits content:", error);
        // Display an error message if needed
        $("#recruits-reload").html('<p>Error loading content. Please try again.</p>');
      },
    });
  }

  // Event listener for tab click
  $("#player-recruits").on("click", function (e) {
//     e.preventDefault();
    // Load recruits content when the tab is clicked
    loadRecruitsContent();
  });
    
$(document).on('click', '.hhh_remove_favorite', function(event) {
    // Get the rec_id from the button's data attribute
    var rmId = $(this).data('rm-id');
    if(window.location.hostname === 'dev.sportspassports.com'){var ajaxBaseUrl = '/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cjcustomdb-dev.php'; var sitetype = 'dev';}else if(window.location.hostname === 'sportspassports.com'){var ajaxBaseUrl = '/srv/users/spp-serverpilot/apps/sportspassports-press/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cjcustomdb-live.php'; var sitetype = 'live';};
  if(window.location.hostname === 'dev.sportspassports.com'){var linking = '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cj-remove-favorites-script.php'; var sitetype = 'dev'; console.log("dev");}else if(window.location.hostname === 'sportspassports.com'){var linking = '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cj-remove-favorites-script-live.php'; var sitetype = 'live'; console.log("live");};
    // Make an Ajax request to handle the deletion
  
    $.ajax({
        type: 'POST',
        url: linking, // Replace with the actual path to your server-side script
        data: { rec_id: rmId,
                ajaxBaseUrl: ajaxBaseUrl
              },
        dataType: 'json',
        success: function(response) {
            // Handle the Ajax response if needed
//             console.log('Player removed from recruit list:', response);

            // Call the existing rm_fav function if needed
            rm_fav(event.target);

            // Close the reveal
            $(event.target).closest('.reveal').foundation('close');
        },
        error: function(error) {
            console.error('Ajax error:', error);
        }
    });
});
  
});
</script>

<?php
  
}