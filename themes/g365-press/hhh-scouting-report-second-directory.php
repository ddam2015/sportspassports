<?php
/**
 * Template Name: hhh-scouting-services-second-directory
 * Author: Chritsian Jimenez
 * Version: 1.0
 * Description: This is where the page leads to when you have purchased the scouting report and clicked an event. Directing you to the second directory with tabs filtered to only show players and stats for that event.
 */


// Retrieve the ID from the URL query parameters
if (isset($_GET['button'])) {
    $buttonId = $_GET['button'];
    $start_date = $_GET['start_date']; // e.g., "2024-09-01"
    $end_date = $_GET['end_date'];     // e.g., "2025-08-31"

    // Use the ID to generate content (replace this with your logic)
    $content = "Content for Button ID: $buttonId";
//     echo "<div>$content</div>";
} else {
    // Handle the case when the button parameter is not set
    echo 'Button parameter is missing';
}
$ev_acts = g365_get_event_data($buttonId, true);
$ev_date = g365_build_dates($ev_acts->dates, 2);
$ev_location = g365_build_locations($ev_acts->locations, 1);

  
  
// print_r($ev_acts); 
?>




<div class='medium-padding in_event_directory' id='scouting-events-container-test'>

<div>  
    <a class='buttonization scouting-report-buttonization test' href='#' id="back-to-events-button" data-start-date="<?php echo $start_date; ?>" data-end-date="<?php echo $end_date; ?>">
        < Back to all events
    </a>
</div>
  
  <div class='grid-y'>
    <div class='small-12 medium-6 large-6'>
        <div class='small-12 medium-12 large-12 medium-padding'>
          <div class='grid-x small-up-2 medium-up-4 large-up-4 text-center'>
                <div class='img-container mx-auto'>
                    <img src='<?php echo $ev_acts->logo_img ?>' > 
                    <p class="font-dharma emphasis" style="font-size: 2rem; line-height: 0;"><?php echo $ev_date ?></p>
                    <p class="font-dharma" style="font-size: 1.6rem;"><?php echo $ev_location ?></p>
                </div>
            </div>
        </div>
      
        <div class="tabs-container" class="table-scroll-mobile">
                <button class="sub-tab scouting-tab tab subdir-2nd-dir-writeups subdir-active" data-tab-id="2nd-dir-writeups">Write Ups</button>
          
                <?php 
                $short_name = $ev_acts->short_name;
                if(strpos($short_name, 'National Evaluations') !== false){
                  
                  echo '
                  
                  <button class="sub-tab scouting-tab tab subdir-2nd-dir-eventstat hide" data-tab-id="2nd-dir-eventstat">Event Stat Leaderboard</button>
                  <button class="sub-tab scouting-tab tab subdir-2nd-dir-eventbox hide" data-tab-id="2nd-dir-eventbox">Event Standings/Box scores</button>
                  <button class="sub-tab scouting-tab tab subdir-2nd-dir-playdir hide" data-tab-id="2nd-dir-playdir">Player Directory</button>
                  
                  ';
                }else{
                  
                  echo '
                  
                  <button class="sub-tab scouting-tab tab subdir-2nd-dir-eventstat hide" data-tab-id="2nd-dir-eventstat">Event Stat Leaderboard</button>
                  <button class="sub-tab scouting-tab tab subdir-2nd-dir-eventbox" data-tab-id="2nd-dir-eventbox">Event Standings/Box scores</button>
                  <button class="sub-tab scouting-tab tab subdir-2nd-dir-playdir" data-tab-id="2nd-dir-playdir">Player Directory</button>
                  
                  ';
                  
                }
          
                ?>
<!--                 <button class="sub-tab scouting-tab tab subdir-2nd-dir-eventstat " data-tab-id="2nd-dir-eventstat">Event Stat Leaderboard</button>
                <button class="sub-tab scouting-tab tab subdir-2nd-dir-eventbox" data-tab-id="2nd-dir-eventbox">Event Standings/Box scores</button>
                <button class="sub-tab scouting-tab tab subdir-2nd-dir-playdir" data-tab-id="2nd-dir-playdir">Player Directory</button> -->
        </div>
      
      
      
        <div id="tab-content-container">
            <div id="2nd-dir-writeups" class="sub-tab-content sub-active-tab">
                <?php g365_dir_render('hhh-scouting/second-directory', 'write-ups', $buttonId, $arg=null); ?>
            </div>

            <div id="2nd-dir-eventstat" class="sub-tab-content">
<!--               <div class="event-sub-dir">
              <div class="spp-container sub-dir-container"></div>  
              </div> -->
              
              hello2
              
                <?php g365_dir_render('hhh-scouting/second-directory', 'eventstat', $buttonId, $arg=null); ?>
            </div>

            <div id="2nd-dir-eventbox" class="sub-tab-content">
                <?php g365_dir_render('hhh-scouting/second-directory', 'eventstandings', $buttonId, $arg=null); ?>
            </div>

            <div id="2nd-dir-playdir" class="sub-tab-content">
                <?php g365_dir_render('hhh-scouting/second-directory', 'playdir', $buttonId, $arg=null); ?>
            </div>
        </div>
    </div>
  </div>
</div>
  

<script>
// console.log('testing fetch');

  
</script>
  

