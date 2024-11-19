<?php
/**
 * Template Name: HHH-home
 * Author: Chritsian Jimenez
 * Version: 1.0
 * description: starting page where the initial directory is as well as the landing page. 
 */
get_header();
$news_feat = new WP_Query( array( 'category_name' => 'hhh-featured', 'posts_per_page' => 3 ) );

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
  <img src="https://sportspassports.com/wp-content/uploads/2023/11/Hype-Her-Hoops-400x103-1.png" >
  <h1>
    Landing Page
  </h1>
  
<div class="tabs-container table-scroll-mobile" >
        <a href="https://dev.sportspassports.com/hhh-home/" class="tab scouting-tab" style="color: white;">Home</a>
        <button class="tab scouting-tab primedir_events"  id="events" data-tab-id="events">Events</button>
        <button class="tab scouting-tab primedir_player_directory"  id="playerDir" data-tab-id="player_directory">Player Directory</button>
        <button class="tab scouting-tab primedir_stat_leaderboard"  id="stat" data-tab-id="stat_leaderboard">Stat Leaderboard</button>
        <button class="tab scouting-tab primedir_event_boxscores" id="eventStandings"  data-tab-id="event_boxscores">Event Standings/Box scores</button>
        <button id="player-recruits" class="tab scouting-tab primedir_my_recruits"  id="recruits" data-tab-id="my_recruits">My Recruits</button>
</div>
  
   <section class="site-main width-hd hero-tiles">
    <div class="grid-x white-border thick-border" style="overflow-x:scroll; flex-wrap: nowrap;">
  
            <div class="cell small-4 maximum-height">
              <?php echo spp_tile_template( 0, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo spp_tile_template( 1, $news_feat, 'tile-image' ); ?>
            </div>
            <div class="cell small-4 maximum-height">
              <?php echo spp_tile_template( 2, $news_feat, 'tile-image' ); ?>
            </div>
  </div>
    
  </section>
  
</div>

<section id="content" class="site-main small-padding-top xlarge-padding-bottom grid-container">
  
<?php //if we have page content
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php the_content(); ?>

<?php endwhile; endif; ?>
  </div>
</div>
</section>

<!-- jQuery script -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script type="text/javascript">
  let navBtns = document.querySelectorAll('button.scouting-tab');
    navBtns.forEach(btn => btn.addEventListener('click', function(e) {
      window.location.href = window.location.origin + `/hhh-scouting-services-3?pg=${e.currentTarget.getAttribute('data-tab-id')}`;
    })
  )
</script>

<?php
get_footer();
