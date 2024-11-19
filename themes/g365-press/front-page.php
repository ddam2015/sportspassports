<?php
/**
 * The front page template
 * @package OGP G365 Press
 * @since G365 1.0.0
 */

// News query for the slider
$news_feat = new WP_Query( array( 'category_name' => 'Featured', 'posts_per_page' => 6 ) );

//https://dev.grassroots365.com/wp-content/uploads/display-assets/event-promo-g365.jpg
//https://dev.grassroots365.com/wp-content/uploads/2017/11/g365-posts-banner.jpg
get_header();

//see if we need a splash display
$g365_ad_info = g365_start_ads( $post->ID );

$default_event_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';

$g365_layout_type = get_option( 'g365_layout' );
if( $g365_layout_type['front_layout']['type'] === 'tiles' && count($news_feat->posts) === 6 ){
  //trigger for tile video support
  $tile_vid = false;
  $tile_video_settings = [];

  //get tile banner
	$g365_tile_banner = get_option( 'g365_display' );
//   print $g365_tile_banner[1];
	//reassign to focus on tile banner
	$g365_tile_banner = $g365_tile_banner['site_4'];
  $g365_tile_banner_build = '';

  //build tile banner from global settings if we have data
  if ( !empty($g365_tile_banner['title']) ) {
    if ( !empty($g365_tile_banner['link']) ) {
      $g365_tile_banner_build .= '<h2 class="no-margin"><a href="' . $g365_tile_banner['link'] . '">' . $g365_tile_banner['title'] . '</a></h2>';
//       echo $g365_tile_banner_build;
    } else {
      $g365_tile_banner_build .= '<h2 class="no-margin">' . $g365_tile_banner['title'] . '</h2>';
//       echo $g365_tile_banner_build;
    }
  }
  if ( !empty($g365_tile_banner['sub_title']) ) $g365_tile_banner_build .= '<p class="no-margin">' . $g365_tile_banner['sub_title'] . '</p>';
  function g365_tile_template( $target_num, $news_feat, $classes ) {
    $tile_type = get_post_meta($news_feat->posts[$target_num]->ID, 'video_head', true);
    if( empty($tile_type) ) {
      $tile_type = '<img src="' . (( has_post_thumbnail($news_feat->posts[$target_num]->ID) ) ? get_the_post_thumbnail_url( $news_feat->posts[$target_num]->ID, "featured-tile" ) : get_site_url() . "/wp-content/themes/g365-press/assets/g365_blank-placeholder_640x640.jpg") . '" alt="' . $news_feat->posts[$target_num]->post_title . '" />';
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
              'enablejsapi' => 1
            ]
          ]
        ];
        $classes .= ' responsive-embed';
//         $tile_type = '<iframe type="text/html" width="1138" height="640.125"
// src="https://www.youtube.com/embed/' . $video_settings[1] . '?autoplay=1&controls=0&enablejsapi=1&loop=1&modestbranding=1&fs=0" frameborder="0"></iframe>';
//         $classes .= ' responsive-embed';
      }
    }
    return '        <div id="news-' . $news_feat->posts[$target_num]->ID . '" class="white-border thick-border tile relative maximum-height">
          <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '" class="' . $classes . '">' . $tile_type . '</a>
          <h1 class="article-info">
            <a href="' . get_permalink($news_feat->posts[$target_num]->ID) . '">' . $news_feat->posts[$target_num]->post_title . '</a>' . 
            (( !empty($news_feat->posts[$target_num]->post_excerpt) ) ? "<p class=\"no-margin cute orange text-lowercase\">" . $news_feat->posts[$target_num]->post_excerpt . "</p>" : "") . 
          '</h1>
        </div>';
  } ?>
<section class="site-main width-hd hero<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>">
  <?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after']; ?>
   <video class="hero__video hide-for-small-only" autoplay="autoplay" loop="loop" muted="muted" playsinline="">
     <source src="https://sportspassports.com/wp-content/uploads/2024/01/PASSPORT-Hype-Video-Test_2-Updated-Outro.mp4">
   </video>
<!--    <img class="hero__img" src="https://dev.sportspassports.com/wp-content/uploads/2024/05/PASSPORT-Hype-Video-Test_2-Updated-Outro.jpg" alt="Sports Passports Hero"> -->
   <div class="hero__info hide">
     <h1>Welcome <span class="block hero__to">to</span><span class="block hero__passport">The Passport</span></h1>
   </div>
</section>
<!-- Three JS Loader -->
<!-- <script src="./wp-content/plugins/three-js/three.min.js"></script>
<script src="./wp-content/plugins/three-js/GLTFLoader.js"></script> -->

<?php } else { ?>

<section class="site-main small-padding-top xlarge-padding-bottom grid-container<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>">
  <?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after']; ?>
  <div class="grid-x grid-margin-x">
    <div id="main" class="small-12 medium-8 cell">
      <div class="tiny-padding gset no-border">
        <h2 class="entry-title"><a href="/category/news/">News</a></h2>
      </div>
      <div id="slider-wrapper" class="tiny-padding gset no-border medium-margin-bottom">
        <div class="grid-x collapse">
          <div class="small-12 medium-12 large-9 cell">
            <div id="news_rotator">

              <!-- News Slides	 -->
              <?php if ( $news_feat -> have_posts() ) : while ( $news_feat -> have_posts() ) : $news_feat -> the_post(); ?>

              <div id="news-<?php echo $post->ID; ?>" class="green-border tab-slider relative">
                <a href="<?php echo get_permalink(); ?>">
                  <img src="<?php echo ( has_post_thumbnail() ) ? the_post_thumbnail_url( 'featured-home' ) : 'http://image.mlive.com/home/mlive-media/width960/img/kalamazoogazette/photo/2016/12/22/-c8733c1e608c238b.JPG'; ?>" alt="<?php echo get_the_title(); ?>" />
                </a>
                <h4 class="article-info">
                  <a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
                </h4>
              </div>

              <?php endwhile; wp_reset_postdata(); endif; ?>

            </div>
          </div>
          <div class="small-12 medium-12 large-3 cell">
            <div class="tabs tabs-vertical vertical flex-container flex-dir-column green-border slide-thumbs maximum-height" id="news_nav">

            <?php if ( $news_feat -> have_posts() ) : while ( $news_feat -> have_posts() ) : $news_feat -> the_post(); ?>

              <div class="tabs-title flex-child-auto flex-container flex-dir-column">
                <a href="#news<?php echo $post->ID; ?>"><span><?php echo ( has_excerpt() ) ? get_the_excerpt() : get_the_title(); ?></span></a>
              </div>

            <?php endwhile; wp_reset_postdata(); endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php } //end drafult and hero section ?>

<section id="content" class="site-main small-padding-top xlarge-padding-bottom grid-container">
  
<?php //if we have page content
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php the_content(); ?>

<?php endwhile; endif; ?>
  </div>
</div>
</section>
<section id="content" class="site-main small-padding-top xlarge-padding-bottom grid-container">
		<div class="grid-x grid-margin-x"><div class="cell small-12">
    <!-- Player profiles, events and teams statistics -->
      <?php g365_dir_render('home-page','pl-ev-tm-statistics', '', $arg = null); ?>
      <h2 class="text-center">Supported Brands</h2>
    <div class="partner-slider">
      <div class="partner-slide-track">
        <a href="https://grassroots365.com/calendar/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerG365">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/G365-Series-300x100.png" alt="Adidas Logo" />
                </div>
            </a>
        <a href="https://elitebasketballcircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerEBC">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/ebc-series-300x100.png" alt="JR Nba Logo" />
                </div>
            </a>
        <a href="https://hypeherhoopscircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerHHH">
                    <img src="https://hypeherhoopscircuit.com/wp-content/themes/hhhoops-press/assets/tiny-logos/Hype-Her-Hoops-logo.png" alt="M-Experiment Logo" />
                </div>
            </a>
        <a href="https://scholasticseries.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerSCS">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/Scholastic-Series-300x100.png" alt="Livebarn Logo" />
                </div>
            </a>
        <a href="https://thestagecircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerStage">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/stage-series-300x100.png" alt="HOAG Orthopedic Institute Logo" />
                </div>
            </a>
        <a href="https://breakthroughcircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerBTC">
                    <img src="https://breakthroughcircuit.com/wp-content/themes/btc-press/assets/tiny-logos/Breakthrough-Circuit-Logo-400x300.png" alt="Wooden Legacy Logo" />
                </div>
            </a>
        <a href="https://socal.nhsbca.org/socal-california-live-2023" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerCalLive">
                    <img src="https://socal.nhsbca.org/hs-fs/hubfs/California%20Live%20Logo.png?width=250&height=271&name=California%20Live%20Logo.png" alt="Wooden Legacy Logo" />
                </div>
            </a>
    <!--     repeat -->
        <a href="https://grassroots365.com/calendar/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerG3652">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/G365-Series-300x100.png" alt="Adidas Logo" />
                </div>
            </a>
        <a href="https://elitebasketballcircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerEBC2">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/ebc-series-300x100.png" alt="JR Nba Logo" />
                </div>
            </a>
        <a href="https://hypeherhoopscircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerHHH">
                    <img src="https://hypeherhoopscircuit.com/wp-content/themes/hhhoops-press/assets/tiny-logos/Hype-Her-Hoops-logo.png" alt="M-Experiment Logo" />
                </div>
            </a>
        <a href="https://scholasticseries.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerSCS2">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/Scholastic-Series-300x100.png" alt="Livebarn Logo" />
                </div>
            </a>
        <a href="https://thestagecircuit.com/" target="_blank" rel="noopener">
                <div class="partner-slide" id="partnerStage2">
                    <img src="https://grassroots365.com/wp-content/uploads/2021/02/stage-series-300x100.png" alt="OC Sports Comission Logo" />
                </div>
            </a>
      </div>
    </div>
  
<!-- Player and Team Spotlight -->
  <?php g365_dir_render('home-page','player-team-spotlight', '', $arg = null); ?>

  
</section>

    <section class="home__search">
        <div class="home__search--container">
            <h2 class="text-uppercase hide">Player Search</h2>
            <div class="relative">
          <span class="search-mag fi-magnifying-glass"></span>
          <input type="text" class='search-hero g365_livesearch_input' data-g365_type="player_profiles" placeholder="Player Search" autocomplete="off">
        </div>
        </div>
        <div class="home__search--container">
            <h2 class="text-uppercase hide">Team Search</h2>
    <div class="relative">
          <span class="search-mag fi-magnifying-glass"></span>
          <input type="text" class="search-hero g365_livesearch_input ls_query" data-g365_type="club_profiles" placeholder="Team Search" autocomplete="off" name="ls_query" maxlength="60">
        </div>
        </div>
    </section>
<?php
//if we have a splash graphic, add  the elements to support, part 1
if( !empty($g365_ad_info['splash']) ) echo $g365_ad_info['splash'];

get_footer();

//if we have a splash graphic, initialize it now that foundation() has started, part 2
if( !empty($g365_ad_info['splash']) ) echo '<script type="text/javascript">
    var g365_closed = localStorage.getItem("g365_close_today");
    var g365_closed_date = localStorage.getItem("g365_close_today_date");
    var g365_now_date = new Date();
    if( g365_closed_date !== null && new Date(g365_closed_date).getDate() !== g365_now_date.getDate() ) {
      localStorage.removeItem("g365_close_today");
      localStorage.removeItem("g365_close_today_date");
      g365_closed = null;
    }
    if( g365_closed === null ) {
      (function($){$("#g365_home_reveal").foundation("open");})(jQuery);
    }
  </script>';
      
if( $tile_vid ) {
  print_r(
    '<script>
      var tag = document.createElement("script");
      tag.src = "https://youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName("script")[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      var tile_players = ' . json_encode( $tile_video_settings) . ';
      function onYouTubeIframeAPIReady() {
        tile_players.forEach( function( vid_settings, dex ) {
          vid_settings.data.events = {
            "onReady": onPlayerReady,
            "onStateChange": onPlayerStateChange
          };
          tile_players[dex]["video_ref"] = new YT.Player( vid_settings.id, vid_settings.data);
        });
      }
       function onPlayerReady(event) {
         event.target.playVideo();
         event.target.mute();
       }
       function onPlayerStateChange(event) {
        if( event.data === 0 ){
         event.target.playVideo();
        }
       }
    </script>'
  );
}

?>