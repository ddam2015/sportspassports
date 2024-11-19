<?php 
// Add headers to prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>

<?php
  
      $year_fil = hhh_get_event_dates($player_id);
      if(empty($year_fil)){
        $year_fil = g365_date_format($first_date, 11);
        preg_match_all("/'([^']+)'/", $year_fil['season_db_format'], $matches);
        $first_date = $matches[1][0];  // Trim to remove leading/trailing spaces
        $second_date = $matches[1][1];
        $unlocked = 0;
      } else{
      $date_array = explode("|", $year_fil);
      $first_date = trim($date_array[0]);  // Trim to remove leading/trailing spaces
      $second_date = trim($date_array[1]);  
      }
      // Convert date strings to DateTime objects
      $first_date = new DateTime($first_date);
      $second_date = new DateTime($second_date);
      $currentDateTime = new DateTime();
      $currentDate = $currentDateTime->format('Y-m-d H:i:s'); // Output format: YYYY-MM-DD
//       echo(' first: ' . $first_date->format('Y-m-d H:i:s') . ' second: ' . $second_date->format('Y-m-d H:i:s'));

      $current_user_id = get_current_user_id();
      if($current_user_id != 0){
      $unlocked = g365_check_scouting_unlocked($current_user_id);
      }

      if(empty($unlocked)){$unlocked = 0;}

      $seasons = generate_seasons();
      // Determine the current season to pre-select it in the dropdown
      $current_year = (int)date('Y');
      $current_month = (int)date('m');
      $current_season = ($current_month >= 9) ? "$current_year-" . ($current_year + 1) : ($current_year - 1) . "-$current_year";
//       echo '<br>' . $current_season . ' here';
      list($start_year, $end_year) = explode('-', $current_season);
      $start_date = new DateTime("$start_year-09-01"); // September 1st of the start year
      $end_date = new DateTime("$end_year-08-31");     // August 31st of the end year

//       echo "Start Date: " . $start_date->format('Y-m-d') . "\n";
//       echo "End Date: " . $end_date->format('Y-m-d') . "\n";
//       echo "current Date: " . $currentDate . "\n";

      $ev_acts = g365_get_event(['authorized_user'=>$unlocked, 
                                 'starting_date'=>$start_date->format('Y-m-d'), 
                                 'end_date'=>$end_date->format('Y-m-d'), 
                                 'current_date'=>$currentDate], 'HHH');

      $ev_acts = json_decode(json_encode($ev_acts), true);
//       print_r($ev_acts);
      ?>

      <!-- HTML Dropdown for Year Filter -->
      <div class="year-filter-container">
        <label for="year-filter" style="color: white;">Filter by Season: </label>
        <select id="year-filter" class="year-filter">
          <?php
          foreach ($seasons as $season) {
              // Check if the current season matches and pre-select it
              $selected = ($season === $current_season) ? 'selected' : '';
              echo "<option value='$season' $selected>$season</option>";
          }
          ?>
        </select>
      </div>

      <div class="medium-padding loading-container" id="scouting-events-container">
        <div class="grid-y">
          <div class="small-12 medium-6 large-6 h_ev_box-container">
            <?php if($unlocked === 0){ echo '<a class="button buttonization scouting-purchase" href="https://sportspassports.com/product/hhh-scouting-report/">Purchase Scouting Report </a>';}      ?>
              <div class="small-12 medium-12 large-12 medium-padding">
                <div class="grid-x small-up-2 medium-up-4 large-up-4 text-center">
                  <?php
                  $counter = 0;
                  //this section is where we organize the event by date and place the national evaluations first
                  //print_r($ev_acts);
                  $events = $ev_acts;  // Assuming $ev_acts is your original events array
                  $sortedEvents = sortEventsFromToday($events);
//                   print_r($sortedEvents);

                  foreach($sortedEvents as $ev_act): 
                  
                  if( strpos($ev_act['short_name'], 'Scouting Report') !== false ){
                    continue;
                  }
      //             echo cj_hhh_tb(['lock_status'=>1,'ev_nickname'=>$ev_act['nickname'], 'ev_link'=>$ev_act['link'], 'img_logo'=>(empty($ev_act['img_logo']) ? "" : $ev_act['img_logo']), 'ev_name'=>$ev_act['name'], 'ev_date'=>$ev_act['dates'], 'logo_img'=>$ev_act['logo_img'], 'ev_id'=>$ev_act['id'], 'ev_type'=>$ev_act['org']]); 
                          $ev_link = $ev_act['link']; $ev_id = $ev_act['id']; $target = 'target="_blank"'; $ev_name = $ev_act['name']; $ev_nickname = $ev_act['nickname'];
                          if($unlocked === 0){ // Locked
                            // Exclude custom events from direct purchase link on g365 product page
                            if( $ev_act['org'] == '7164' ){
                              $ev_href = '<a href="https://sportspassports.com/product/hhh-scouting-report/" '.$target.' class="scouting-locked"><img class="small-margin-bottom" loading="lazy" data-src="'.$ev_act['img_logo'].'" alt="'.$ev_act['name'].'" src="'.$ev_act['logo_img'].'">';
                            }else{
                              $ev_link = get_site_url() . '/product/hhh-scouting-report/';
                              $ev_href = '<a href="'.$ev_link.'" '.$target.'><img class="small-margin-bottom" loading="lazy" data-src="'.$ev_act['img_logo'].'" alt="'.$ev_act['name'].'" src="'.$ev_act['logo_img'].'">';
                            }
                            $hover = ' event-unlock__trigger ';
                            $locked_style = 'style="opacity: 0.6;filter: grayscale(1);"';
                        }else{ // Unlocked
                          $ev_link = get_site_url() . '/account/dcp/teams/'.$ev_id.'/?type=team';
                          $locked_style = ''; $target = ''; $hover = '';
//                           $ev_href = '<a class="event_directory scouting-locked" data-endpoint="hhh-scouting-report-second-directory.php?button='.$ev_act['id'].'"><img class="small-margin-bottom" loading="lazy" data-src="'.$ev_act['img_logo'].'" alt="'.$ev_act['name'].'" src="'.$ev_act['logo_img'].'"  data-ev-id="'.$ev_act['id'].'" data-ev-time="'.$ev_act['eventtime'].'">';
                          $ev_href = '<a class="event_directory scouting-locked" data-endpoint="hhh-scouting-report-second-directory.php?button='.$ev_act['id'].'&start_date='.$start_date->format('Y-m-d').'&end_date='.$end_date->format('Y-m-d').'"><img class="small-margin-bottom" loading="lazy" data-src="'.$ev_act['img_logo'].'" alt="'.$ev_act['name'].'" src="'.$ev_act['logo_img'].'" data-ev-id="'.$ev_act['id'].'" data-ev-time="'.$ev_act['eventtime'].'">';


      //                     $ev_href = '<a class="event_directory" data-endpoint="' . get_site_url() . '/hhh-scouting-services-3/hhh-scouting-report-second-directory.php?button='.$ev_act['id'].'"><img class="small-margin-bottom" loading="lazy" data-src="'.$ev_act['img_logo'].'" alt="'.$ev_act['name'].'" src="'.$ev_act['logo_img'].'">';  
                        }

                        $event_name = $ev_act['name'];
                        $substringToRemove = "Hype Her Hoops";
                        $result = str_replace($substringToRemove, "", $event_name);
                        $dcp_ev = '
                          <div class="cell">
                            <div class="ev_inner is_act is_act-hhh bg-gray'.$hover.'" '.$locked_style.'>
                              '.$ev_href.'
                              <label class="text-white emphasis font-dharma" style="font-size:24px; line-height: 1;">'.$result.'</label>
                              <label class="text-white font-dharma" style="font-size:16px">'.g365_build_dates($ev_act['dates'], 2).'</label></a></a>
                            </div>
                          </div>
                        ';
                        echo $dcp_ev;
                        $counter++;
                  ?>
                  <?php endforeach; ?>
                </div>
      <div class="scout-loading" id="scout-loading-message">


                <div class="spinner">
                  <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                  </svg>
                </div>


                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="content" id="content-container"></div>
      <?php

// Your existing PHP code for initial page load...
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script> 
//     console.log('script running');
    // JavaScript to handle button clicks and content loading
    document.addEventListener('DOMContentLoaded', function () {
//         console.log('DOM fully loaded and parsed');
        var buttons = document.querySelectorAll('.event_directory');
        var contentContainer = document.getElementById('content-container');
        var getRidof = document.getElementById('scouting-events-container');
        var loadingMessage = document.getElementById('scout-loading-message');
        var yearFilter = document.getElementById('year-filter');

        buttons.forEach(function (button) {
          var evId = button.getAttribute('data-ev-id');
            button.addEventListener('click', function () {
              
                // Show loading message covering all buttons
                loadingMessage.style.display = 'flex !important';
              
                // Hide previous content
                contentContainer.innerHTML = '';
                // Show loading message
                loadingMessage.style.display = 'block';

                var cacheBuster = new Date().getTime(); // Get current timestamp as a unique value
                // Fetch content asynchronously
                var endpoint = this.getAttribute('data-endpoint') +'&cache='+ cacheBuster;
                fetch(endpoint)
                    .then(response => response.text())
                    .then(data => {

                  
                        // Hide loading message
                        loadingMessage.style.display = 'none';
                  
                        getRidof.style.display = 'none';
                        // Display the fetched content
                        contentContainer.innerHTML = data;
                        contentContainer.style.display = 'block'; // Show the content
                        
                        // Load sportspassports.features.min.js script after the API content is appended
//                         $.getScript('https://media.sportspassports.com/js/sportspassports.features.min.js');
                          
                        // Include and execute scripts in the fetched content
                        var scriptContainer = document.createElement('div');
                        scriptContainer.innerHTML = data;

                        var scripts = scriptContainer.querySelectorAll('script');
                        scripts.forEach(function (script) {
                            var newScript = document.createElement('script');
                            newScript.innerHTML = script.innerHTML;
                            document.head.appendChild(newScript).parentNode.removeChild(newScript);
                        });
                              
                  
                  
                    })
                    .catch(error => {
                        console.error('Error fetching content:', error);
                    });
            });
        });

        // Handle the year filter functionality
//         var yearFilter = document.getElementById('year-filter');
        if (yearFilter) {

            yearFilter.addEventListener('change', function () {
                var selectedSeason = this.value;

                // Example format for `selectedSeason` is '2024-2025'
                var [startYear, endYear] = selectedSeason.split('-');

                // Construct start and end dates for the season
                var startDate = startYear + '-09-01';  // Start date is always September 1st
                var endDate = endYear + '-08-31';      // End date is always August 31st

                // Trigger AJAX call with the constructed dates
                var cacheBuster = new Date().getTime();  // To prevent caching
                var endpoint = 'hhh-event-reload.php' + '?start_date=' + startDate + '&end_date=' + endDate + '&cache=' + cacheBuster;

                // Show the loading message
                loadingMessage.style.display = 'block';

                // Fetch the new content via AJAX
                fetch(endpoint)
                    .then(response => response.text())
                    .then(data => {
                        loadingMessage.style.display = 'none';  // Hide the loading message
                        getRidof.style.display = 'none';  // Hide the previous content

                        // Display the fetched content
                        contentContainer.innerHTML = data;
                        contentContainer.style.display = 'block';

                        // Include and execute scripts in the fetched content
                        var scriptContainer = document.createElement('div');
                        scriptContainer.innerHTML = data;

                        var scripts = scriptContainer.querySelectorAll('script');
                        scripts.forEach(function (script) {
                            var newScript = document.createElement('script');
                            newScript.innerHTML = script.innerHTML;
                            document.head.appendChild(newScript).parentNode.removeChild(newScript);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching content:', error);
                    });
            });
          
          
        }

    });

</script>