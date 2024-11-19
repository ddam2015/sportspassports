<?php
/**
 * The template for displaying the header
 * @package Sports Passport Press
 * @since G365 1.0.0
 */
$g365_layout_settings = get_option( 'g365_layout' );
switch( $g365_layout_settings['menu_layout']['type'] ) {
  case 'mega':
    $g365_nav_body_class = 'mega-top';
    break;
  case 'side_slide':
    $g365_nav_body_class = 'side-slide-top';
    break;
  default:
    $g365_nav_body_class = 'reg-header';
}

// if( is_cart() || is_checkout() || is_account_page() ) {
//   $g365_layout_settings['menu_layout']['type'] = 'minimal';
//   $g365_nav_body_class .= ' min-header';
// } else {
//   $g365_nav_body_class .= ' reg-header';
// }

//rvrsev

?>

<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php wp_title( ' ' )?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
<!--<link rel="publisher" href="googlePlus Page"/> -->
    <?php wp_head();?>
  
  <!-- Include Handlebars from a CDN -->
<!--   <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
</head>

<body itemscope="itemscope" itemtype="http://schema.org/WebSite" <?php body_class( $g365_nav_body_class ); ?>>
  <div id="dialong_result_box_div"></div>
	<div id="page">
		<!-- Start Top Bar -->
		<header id="masthead" class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
      <?php 
        switch( $g365_layout_settings['menu_layout']['type'] ) :
          case 'minimal':
      ?>
			<div id="mastInsert" class="grid-x white header-bg">
				<!--logo -->
				<div class="cell shrink">
					<div id="site-logo-bar" class="grid-x collapse">
						<a class="g365-logo main-logo title-logos current-site cell shrink has-tip" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/grassroots_365_tiny_dk-stroke.png" alt="<?php bloginfo( 'name' ); ?> Official Logo" /><span class="show-for-sr"><?php bloginfo( 'name' ); ?> Official Site</span></a>
						<a class="secondary-logo title-logos cell shrink has-tip"  href="http://opengympremier.com" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/open_gym_premier_tiny_dk.png" alt="Open Gym Premier Official Logo" /><span class="show-for-sr">Open Gym Premier Official Site</span></a>
						<a class="secondary-logo title-logos cell shrink has-tip"  href="http://elitebasketballcircuit.com" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/ebc_tiny_dk.png" alt="Elite Basketball Circuit Official Logo" /><span class="show-for-sr">Elite Basketball Circuit Official Site</span></a>
                        <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://pulsevolleyball.com/" target="_blank"><img src="https://opengympremier.com/wp-content/uploads/2021/05/logo-pulse-solo-sm.png" alt="Pulse Official Logo" /><span class="show-for-sr">Pulse Volleyball Official Site</span></a>
                        <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://sportsboardroom.com/" target="_blank"><img src="/wp-content/themes/g365-press/assets/tiny-logos/sports-boardroom.png" alt="Sports Boardroom Official Logo" /><span class="show-for-sr">Sports Boardroom Official Site</span></a>
                        <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://thestagecircuit.com/" target="_blank"><img src="https://opengympremier.com/wp-content/uploads/2022/01/The-Stage-Logo-tiny.png" alt="The Stage Circuit Official Logo" /><span class="show-for-sr">The Stage Circuit Official Site</span></a>
					</div>
					<!--description -->
					<h3 itemprop="description" class="show-for-sr"><?php echo get_bloginfo( 'description' )?></h3>
					<!--skip to content-->
					<div class="show-on-focus skip-link"><a href="#content" title="Skip to content"><?php _e( 'Skip to content', 'ogp-press' ) ?></a></div>
				</div>
        <div class="cell auto"></div>
        <!-- the trigger -->
        <div id="site-header-menu" class="site-header-menu cell shrink">
          <div class="tiny-padding-top medium-padding-right">
            <a href="/" class="button no-margin-bottom">Home</a> 
          </div>
        </div>
      </div>

      <?php
          break;
          case 'mega':
      ?>

      <!--logo -->
			<div id="mastInsert" class="grid-x header-bg">
				<div class="cell shrink">
					<div id="site-logo-bar" class="grid-x collapse">
						<a class="g365-logo main-logo title-logos current-site cell shrink has-tip" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/grassroots_365_tiny_dk-stroke.png" alt="<?php bloginfo( 'name' ); ?> Official Logo" /><span class="show-for-sr"><?php bloginfo( 'name' ); ?> Official Site</span></a>
						<a class="secondary-logo title-logos cell shrink has-tip"  href="http://opengympremier.com" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/open_gym_premier_tiny_dk.png" alt="Open Gym Premier Official Logo" /><span class="show-for-sr">Open Gym Premier Official Site</span></a>
						<a class="secondary-logo title-logos cell shrink has-tip"  href="http://elitebasketballcircuit.com" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/ebc_tiny_dk.png" alt="Elite Basketball Circuit Official Logo" /><span class="show-for-sr">Elite Basketball Circuit Official Site</span></a>
	          <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://pulsevolleyball.com/" target="_blank"><img src="https://opengympremier.com/wp-content/uploads/2021/05/logo-pulse-solo-sm.png" alt="Pulse Official Logo" /><span class="show-for-sr">Pulse Volleyball Official Site</span></a>
            <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://sportsboardroom.com/" target="_blank"><img src="/wp-content/themes/g365-press/assets/tiny-logos/sports-boardroom.png" alt="Sports Boardroom Official Logo" /><span class="show-for-sr">Sports Boardroom Official Site</span></a>
                        <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://thestagecircuit.com/" target="_blank"><img src="https://opengympremier.com/wp-content/uploads/2022/01/The-Stage-Logo-tiny.png" alt="The Stage Circuit Official Logo" /><span class="show-for-sr">The Stage Circuit Official Site</span></a>
          </div>
					<!--description-->
					<h3 itemprop="description" class="show-for-sr"><?php echo get_bloginfo( 'description' )?></h3>
					<!--skip to content-->
					<div class="show-on-focus skip-link"><a href="#content" title="Skip to content"><?php _e( 'Skip to content', 'g365-press' ) ?></a></div>
				</div>
        <div class="cell auto"></div>
        <!-- the trigger -->
        <div id="site-header-menu" class="site-header-menu cell shrink" data-curtain-menu-button>
          <div class="input-group tiny-padding-top small-padding-right no-margin-bottom pointer">
            <div class="input-group-label">
              Menu 
            </div>
            <div class="input-group-button">
              <div class="curtain-menu-button-toggle">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- the menu  -->
        <div class="curtain-menu">
          <div class="curtain"></div>
          <div class="curtain"></div>
          <div class="curtain"></div>
          <nav  id="site-navigation" class="main-navigation curtain-menu-wrapper" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
            <div class="grid-container medium-padding">
              <?php g365_mega_nav(); ?>
            </div>
          </nav>
        </div>
      </div>

      <?php
          break;
        case 'side_slide':
      ?>

      <!--logo -->
			<div id="mastInsert" class="grid-x header-bg">
				<div class="cell shrink">
					<div id="site-logo-bar" class="grid-x collapse">
						<a class="g365-logo main-logo title-logos current-site cell shrink has-tip" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/grassroots_365_tiny_dk-stroke.png" alt="<?php bloginfo( 'name' ); ?> Official Logo" /><span class="show-for-sr"><?php bloginfo( 'name' ); ?> Official Site</span></a>
						<a class="secondary-logo title-logos cell shrink has-tip"  href="http://opengympremier.com" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/open_gym_premier_tiny_dk.png" alt="Open Gym Premier Official Logo" /><span class="show-for-sr">Open Gym Premier Official Site</span></a>
						<a class="secondary-logo title-logos cell shrink has-tip"  href="http://elitebasketballcircuit.com" target="_blank"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/ebc_tiny_dk.png" alt="Elite Basketball Circuit Official Logo" /><span class="show-for-sr">Elite Basketball Circuit Official Site</span></a>
					  <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://pulsevolleyball.com/" target="_blank"><img src="https://opengympremier.com/wp-content/uploads/2021/05/logo-pulse-solo-sm.png" alt="Pulse Official Logo" /><span class="show-for-sr">Pulse Volleyball Official Site</span></a>
            <a class="secondary-logo g365-logo title-logos cell shrink has-tip" href="https://sportsboardroom.com/" target="_blank"><img src="/wp-content/themes/g365-press/assets/tiny-logos/sports-boardroom.png" alt="Sports Boardroom Official Logo" /><span class="show-for-sr">Sports Boardroom Official Site</span></a>
          </div>
					<!--description-->
					<h3 itemprop="description" class="show-for-sr"><?php echo get_bloginfo( 'description' )?></h3>
					<!--skip to content-->
					<div class="show-on-focus skip-link"><a href="#content" title="Skip to content"><?php _e( 'Skip to content', 'g365-press' ) ?></a></div>
				</div>
        <div class="cell auto"></div>
        <!-- the trigger -->
        <div id="site-header-menu" class="site-header-menu cell shrink" data-side-slide-menu-button>
          <div class="input-group tiny-padding-top small-padding-right no-margin-bottom pointer">
            <div class="input-group-label">
              Menu 
            </div>
            <div class="input-group-button">
              <div class="side-slide-menu-button-toggle">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- the menu  -->
        <nav  id="site-navigation" class="main-navigation side-slide-menu-wrapper" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
          <?php g365_side_slide_nav(); ?>
        </nav>
      </div>

      <?php
          break;
        default:
      ?>

      <!--logo -->
			<div id="mastInsert" class="grid-x">
				<div class="cell small-12 medium-2 small-order-2 medium-order-1 header-bg">
					<div id="site-logo-bar" class="grid-x collapse">
						<a class="nav__logo g365-logo main-logo title-logos current-site cell shrink has-tip" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/Passport-2023.png" alt="<?php bloginfo( 'name' ); ?> Official Logo" /><span class="show-for-sr"><?php bloginfo( 'name' ); ?> Official Site</span></a>
					  
          </div>
					<!--description-->
					<h3 itemprop="description" class="show-for-sr"><?php echo get_bloginfo( 'description' )?></h3>
					<!--skip to content-->
					<div class="show-on-focus skip-link"><a href="#content" title="Skip to content"><?php _e( 'Skip to content', 'g365-press' ) ?></a></div>
				</div>
        <nav id="site-header-menu" class="site-header-menu cell small-12 medium-10 small-order-1 medium-order-2 header-bg hide-for-small-only" role="navigation" aria-label="Partners and Title Navigation">
					  <?php if ( function_exists( 'g365_title_nav' ) ) g365_title_nav(); ?>
				</nav><!-- .title-navigation -->
				<nav id="site-navigation" class="main-navigation cell small-12 small-order-3 show-for-small-only" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
					<a class="show-for-small-only button menuButton" data-toggle="main-nav-drawer"><span class="fi-list"></span> Menu</a>
					<div id="main-nav-drawer" class="nav-drawer" style="display: none;" data-toggler data-closable="slide-out-left" data-animate="slide-in-left">
						<button class="close-button show-for-small-only" data-close>&times;</button>
						<a class="main-logo-menu main-logo g365-logo show-for-small-only" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="nav__logo" src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/Passport-2023.png" alt="<?php bloginfo( 'name' ); ?>" /><span class="show-for-sr"><?php bloginfo( 'name' ); ?></span></a>
						<?php if ( function_exists( 'g365_title_nav' ) ) g365_title_nav(); ?>
					</div>
				</nav><!-- .main-navigation -->
			</div>
        <?php
          endswitch;
        ?>
		</header>	
		<!-- End Top Bar -->
<!--     <div class="nav-spacer"></div> -->
    <!-- Start Content Area -->
    <?php
      if ( !is_front_page() && !is_product() && ( has_post_thumbnail( $post->ID ) || is_archive() || !empty( $hero_video ) ) ) {
        $continue_process = true;
        if( is_archive() ) {
//           echo '<h1>' . get_the_archive_title( $post->ID ) . '</h1>';
          switch( get_the_archive_title( $post->ID ) ){
            case 'Archives: Careers at OGP':
              $arc_img = wp_get_attachment_image( 746, 'full');
              break;
            default:
              $continue_process = false;
          }
        }
        if( $continue_process ) {
          echo '<div class="hero-img">';
          if( !empty( $hero_video ) ) echo '<div class="hero-video-wrapper"><div><iframe class="hero-video" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" title="YouTube video player" width="2000" height="1200" src="https://www.youtube.com/embed/' . $hero_video . '?autohide=1&autoplay=1&controls=0&enablejsapi=1&disablekb=1&fs=0&iv_load_policy=3&loop=1&modestbranding=1&playsinline=1&rel=0&showinfo=0&origin=' . urlencode( get_site_url() ) . '&widgetid=1&mute=1&playlist=' . $hero_video . '"></iframe></div></div>';
          if( !empty( $arc_img ) ) {
            echo $arc_img;
          } else {
            the_post_thumbnail( $post->ID );
          }
          echo '</div>';
          if( !empty( $hero_video ) ) echo '<div class="hero-video-shield"></div>';
        }
      }

      if( !is_front_page() || $g365_layout_settings['front_layout']['type'] !== 'tiles' ) echo '<div class="grid-container">';
    ?>