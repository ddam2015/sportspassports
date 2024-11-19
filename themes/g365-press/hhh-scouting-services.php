<?php
/**
 * Template Name: HHH-scouting-services
 * Author: Chritsian Jimenez
 * Version: 1.0
 * description: starting page where the initial directory is as well as the landing page. 
 */
get_header();
$news_feat = new WP_Query( array( 'category_name' => 'hhh-featured', 'posts_per_page' => 4 ) );

  function spp_tile_template( $target_num, $news_feat, $classes ) {
    $tile_type = get_post_meta($news_feat->posts[$target_num]->ID, 'video_head', true);
    if( empty($tile_type) ) {
      $tile_type = '<img src="' . (( has_post_thumbnail($news_feat->posts[$target_num]->ID) ) ? get_the_post_thumbnail_url( $news_feat->posts[$target_num]->ID, "featured-tile" ) : get_site_url() . "/wp-content/themes/hhhoops-press/assets/hhhoops_profile_placeholder_640x640.jpg") . '" alt="' . $news_feat->posts[$target_num]->post_title . '" />';
    } else {
      $video_settings = explode(":", $tile_type);
      if( $video_settings[0] === 'youtube' ) {
        global $tile_vid;
        global $tile_video_settings;
        $tile_type = '<div id="tile_player_' . $news_feat->posts[$target_num]->ID . '"></div>';
        $tile_vid = true;
        $tile_video_settings[] = (object) [
          'id' => 'tile_player_' . $news_feat->posts[$target_num]->ID,
          'data'=> (object)[
            'height' => '640.125',
            'width' => '1138',
            'videoId' => $video_settings[1],
            'playerVars' => (object)[
              'controls' => 0,
              'fs'  => 0,
              'modestbranding'  => 1,
              'enablejsapi' => 1,
              'loop' => 1,
              'playlist' => $video_settings[1]
            ]
          ]
        ];
        $classes .= ' responsive-embed';
        //og code before embed method of youtube auto play
//         $tile_type = '<iframe type="text/html" width="1138" height="640.125"
// src="https://www.youtube.com/embed/' . $video_settings[1] . '?autoplay=1&controls=0&enablejsapi=1&loop=1&modestbranding=1&fs=0" frameborder="0"></iframe>';
//         $classes .= ' responsive-embed';
      }
    }
    return '        <div id="news-' . $news_feat->posts[$target_num]->ID . '" class="black-border thick-border tile relative maximum-height">
          <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '" class="' . $classes . '">' . $tile_type . '</a>
          <h1 class="article-info">
            <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '">' . $news_feat->posts[$target_num]->post_title . '</a>' . 
            (( !empty($news_feat->posts[$target_num]->post_excerpt) ) ? "<p class=\"no-margin cute orange text-lowercase\">" . $news_feat->posts[$target_num]->post_excerpt . "</p>" : "") . 
          '</h1>
        </div>';
  } ?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script> -->
<!-- <script src="https://media.sportspassports.com/js/sportspassports.features.min.js"></script> -->
<!-- <link rel="stylesheet" id="spp-css" href="https://media.sportspassports.com/css/spp.style.min.css?version=1" type="text/css" media="all" /> -->
<div class="scouting-header xlarge-margin-top">
  <img src="https://sportspassports.com/wp-content/uploads/2024/10/HHH-Scouting-Report-400x300-1.png" >
  <h1>
<!--     Scouting Report -->
<!--     <?php $player_id = get_current_user_id(); echo $player_id; ?> -->
    
  </h1>
</div>
 
<div class="tabs-container table-scroll-mobile" >
        <button class="tab scouting-tab primedir_home prime-active" data-tab-id="home">Home</button>
        <button class="tab scouting-tab primedir_events" data-tab-id="events">Events</button>
        <button class="tab scouting-tab primedir_player_directory" data-tab-id="player_directory">Player Directory</button>
        <button class="tab scouting-tab primedir_stat_leaderboard" data-tab-id="stat_leaderboard">Stat Leaderboard</button>
        <button class="tab scouting-tab primedir_event_boxscores" data-tab-id="event_boxscores">Event Standings/Box scores</button>
        <button id="player-recruits" class="tab scouting-tab primedir_my_recruits" data-tab-id="my_recruits">My Recruits</button>
        <button class="tab scouting-tab primedir_purchase" data-tab-id="purchase">Purchase Scouting Report</button>
</div>


<!-- Content area -->
<div id="tab-content-container">
  
      <div id="home" class="tab-content active-tab">
          <!-- Content for Tab 1 (Home) -->
        <br>
         <section class="site-main width-hd hero-tiles hhh-featured-tiles">
            <div class="grid-x white-border thick-border" style="overflow-x:scroll; flex-wrap: nowrap;">

                    <div class="cell small-3 maximum-height">
                      <?php echo spp_tile_template( 0, $news_feat, 'tile-image' ); ?>
                    </div>
                    <div class="cell small-3 maximum-height">
                      <?php echo spp_tile_template( 1, $news_feat, 'tile-image' ); ?>
                    </div>
                    <div class="cell small-3 maximum-height">
                      <?php echo spp_tile_template( 2, $news_feat, 'tile-image' ); ?>
                    </div>
                    <div class="cell small-3 maximum-height">
                      <?php echo spp_tile_template( 3, $news_feat, 'tile-image' ); ?>
                    </div>
          </div>
        </section>
        <br>
        <?php echo do_shortcode('[load_page id="84995"]'); ?>
        <?php echo do_shortcode('[load_page id="120232"]'); ?>
        <div class="orbit large-margin-top large-margin-bottom" role="region" aria-label="Favorite  Pictures" data-orbit="" data-resize="gr0eda-orbit" id="gr0eda-orbit" data-n="cgqrpa-n" data-events="resize">
          <div class="orbit-wrapper">
            <div class="orbit-controls hide">
              <button class="orbit-previous" tabindex="0"><span class="show-for-sr">Previous Slide</span>ᐸ</button>
              <button class="orbit-next" tabindex="0"><span class="show-for-sr">Next Slide</span>ᐳ</button>
            </div>
            <ul class="orbit-container college-container" tabindex="0" style="height: 144px;">
              <li class="orbit-slide  is-active" data-slide="0">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/CSUMB_wordmark_color.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/GEORGIA-FS-FC-2048x883.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/Los_Medanos_College_logo.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/Occidental_College_Seal.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/San_Francisco_Dons_logo.png" alt="">
                </figure>
              </li>
              <li class="orbit-slide" data-slide="1">
                <figure class="orbit-figure">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/sdsu logo.jpg" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/u of alabama.jpeg" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/UNLV_Athletics_Script_Logo.png" alt="">
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/Utah_State_Aggies_logo.png" alt>
                  <img decoding="async" class="orbit-image college-image" loading="lazy" src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/hhh-colleges/usc-logo.png" alt="">
                </figure>
              </li>



            </ul>
          </div>
          <nav class="orbit-bullets">
            <button class="is-active" data-slide="0">
              <span class="show-for-sr">First slide details.</span>
            </button>
            <button data-slide="1" class=""><span class="show-for-sr">Second slide details.</span></button>


          </nav>
        </div><br>
    </div>
  
    <div id="events" class="tab-content">
        <!-- Content for Tab 1 -->
        <?php g365_dir_render('hhh-scouting', 'hhh-events', $player_id, $arg=null); ?>
    </div>

    <div id="player_directory" class="tab-content">
        <!-- Content for Tab 2 -->
        <?php g365_dir_render('hhh-scouting', 'hhh-player-direct', $player_id, $arg=null); ?>
    </div>

    <div id="stat_leaderboard" class="tab-content">
        <!-- Content for Tab 3 -->
        <?php g365_dir_render('hhh-scouting', 'hhh-stat-leaderboard', $player_id, $arg=null); ?>
    </div>
  
    <div id="event_boxscores" class="tab-content">
        <!-- Content for Tab 4 -->
        <?php g365_dir_render('hhh-scouting', 'hhh-event-box', $player_id, $arg=null); ?>
    </div>
  
    <div id="my_recruits" class="tab-content">
        <!-- Content for Tab 5 -->
        <?php g365_dir_render('hhh-scouting', 'hhh-recruits', $player_id, $arg=null); ?>
    </div>
  
    <div id="purchase" class="tab-content">
        <!-- Content for Tab 5 -->
        <?php //g365_dir_render('hhh-scouting', 'hhh-recruits', $player_id, $arg=null); ?>
    </div>
  
</div>


<!-- jQuery script -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<script>
$(document).ready(function() {
//     console.log('jQuery is working!');

//     const queryString = window.location.search;
//     console.log('Query string:', queryString);

//     const urlParams = new URLSearchParams(queryString);
//     const selectFromHome = urlParams.get('pg');
//     console.log('selectFromHome:', selectFromHome);

//     if(selectFromHome) {
//         console.log("hello");
//         // Hide all tabs
//         $('.tab-content').removeClass('active-tab');
//         $('button.scouting-tab').removeClass('prime-active');
//     }

//     switch(selectFromHome) {
//         case 'events':
//             $('#' + selectFromHome).addClass('active-tab');
//             $('.primedir_' + selectFromHome).addClass('prime-active');
//             break;
//         case 'player_directory':
//             $('#' + selectFromHome).addClass('active-tab');
//             $('.primedir_' + selectFromHome).addClass('prime-active');
//             break;
//         case 'stat_leaderboard':
//             $('#' + selectFromHome).addClass('active-tab');
//             $('.primedir_' + selectFromHome).addClass('prime-active');
//             break;
//         case 'event_boxscores':
//             $('#' + selectFromHome).addClass('active-tab');
//             $('.primedir_' + selectFromHome).addClass('prime-active');
//             break;
//         case 'my_recruits':
//             $('#' + selectFromHome).addClass('active-tab');
//             $('.primedir_' + selectFromHome).addClass('prime-active');
//             break;
//     }

    $('button.scouting-tab').on('click', function() {
        var tabId = $(this).data('tab-id');
//         console.log('Tab clicked:', tabId);

        // Hide all tabs
        $('.tab-content').removeClass('active-tab');
        $('button.scouting-tab').removeClass('prime-active');

        // Show the selected tab
        $('#' + tabId).addClass('active-tab');
        $('.primedir_' + tabId).addClass('prime-active');
    });
  
    $(document).on('click', '.tabs-container .sub-tab', function() {
//             console.log('doc ready');
            $('.sub-tab').removeClass('subdir-active');
            $(this).addClass('sub-active-tab');

            $('.sub-tab-content').removeClass('sub-active-tab');
            var tabId = $(this).data('tab-id');
            $('#' + tabId).addClass('sub-active-tab');
            $('.subdir-' + tabId).addClass('subdir-active');
    });
  
    $('button.scouting-tab').on('click', function() {
        var tabId = $(this).data('tab-id');

        // Check if "purchase" tab is clicked and redirect
        if (tabId === 'purchase') {
            window.location.href = 'https://sportspassports.com/product/hhh-scouting-report/';
            return; // Stop further execution
        }

        // Hide all tabs
        $('.tab-content').removeClass('active-tab');
        $('button.scouting-tab').removeClass('prime-active');

        // Show the selected tab
        $('#' + tabId).addClass('active-tab');
        $('.primedir_' + tabId).addClass('prime-active');
    });
  
  
    $(document).on('click', '#back-to-events-button', function (e) {
//         console.log("Back button clicked");

        e.preventDefault(); // Prevent the default behavior of the link

        // Get the start and end dates from data attributes on the button
        const startDate = $(this).data('start-date');
        const endDate = $(this).data('end-date');

        const contentContainer = document.getElementById('scouting-events-container-test');
        const loadingMessage = document.getElementById('scout-loading-message'); // Optional: If you have a loading spinner

        if (contentContainer) {
//             console.log("Fetching events with start date:", startDate, "and end date:", endDate);

            // Show a loading message or spinner
            loadingMessage.style.display = 'block';

            // Fetch the events page dynamically using AJAX with cache buster
            var cacheBuster = new Date().getTime();
            const url = `hhh-event-reload.php?start_date=${startDate}&end_date=${endDate}&cache=${cacheBuster}`; // Dynamically use date parameters

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    // Hide loading message or spinner
                    loadingMessage.style.display = 'none';

                    // Replace the current content with the fetched content
                    contentContainer.innerHTML = data;

                    // Optionally, re-bind any event listeners if needed
                    if (typeof bindEventListeners === "function") {
                        bindEventListeners(); // Rebind event listeners if necessary
                    }
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    contentContainer.innerHTML = '<div class="error">Error loading events. Please try again later.</div>';
                });
        }
    });


  
  
});
  
  

  
</script>

<?php
get_footer();
