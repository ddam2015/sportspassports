<?php
//just for reference
//add_action( 'after_setup_theme', 'set_global_variables', 1 );

//ninja forms

//remove all css
function remove_nf_enqueue_scripts(){
  wp_dequeue_style( 'nf-display' );
}
add_action( 'nf_display_enqueue_scripts', 'remove_nf_enqueue_scripts');
echo g365_custom_cors(null, 'tsc');

function g365_reg_admin_script(){
  wp_register_script( 'jqueryUI', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js?loc=9', array('jquery'), '1.12.1', true );
  wp_register_script( 'js-g365-all-admin-tournament', site_url( '/', 'https' ) . 'tournament-table.js', array('jquery', 'foundation'), filemtime(WP_PLUGIN_DIR . "/g365-data-manager/js/tournament_table.js"), true );
  wp_register_script( 'foundation', '//cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js', array('jquery'), filemtime(get_template_directory() . '/js/app.js'), true );
  wp_register_script( 'js-all', get_template_directory_uri() . '/js/app.js', array('jquery'), filemtime(get_template_directory() . '/js/app.js'), true );
	wp_register_script( 'js-g365-all-admin', site_url( '/', 'https' ) . 'data-processor-admin.js', array('jquery', 'foundation'), filemtime(WP_PLUGIN_DIR . "/g365-data-manager/js/g365_ajax_cookie_ls_app_admin.js"), true );
  wp_register_script( 'js-g365-all-front-admin', site_url( '/', 'https' ) . 'data-processor-front-admin.js', array('jquery','foundation'), filemtime(WP_PLUGIN_DIR . "/g365-data-manager/js/g365_ajax_cookie_ls_app_front_admin.js"), true );
}
add_action( 'init', 'g365_reg_admin_script');


// remove comment  rss links from pages
function remove_unnecessary_rss_links( ){
	if( is_page() ){
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'feed_links_extra', 3);
	}
}
add_action( 'wp', 'remove_unnecessary_rss_links', 0 );

// add page content rss back, since we can only remove all feeds @remove_unecessary_rss_links
function add_content_rss_feed_link_to_pages( ){
	if( is_page() ){
		 echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . 
		 esc_attr( sprintf( __('%1$s %2$s Feed'), get_bloginfo('name'), "|") ) . '" href="' . 
		 esc_url( get_feed_link() ) . "\" />\n";
    }
}
add_action( 'wp_head', 'add_content_rss_feed_link_to_pages', 3 );

// don't load images or admin assets in
function load_404_template_for_images(){
	if( ! is_attachment() || is_admin() )
		return;
		
	global $wp_query;
	$wp_query->set_404();
	header( 'HTTP/1.0 404 Not Found' );
	
}
add_filter( 'wp', 'load_404_template_for_images', 1 );


/**
 * Remove height and width from featured images
 */
function remove_thumbnail_width_height( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}
add_filter( 'post_thumbnail_html', 'remove_thumbnail_width_height', 10, 5 );


/**
 * Add the id for the 
 */
add_filter('clean_url','mod_clean_url',10,3);
 function mod_clean_url( $good_protocol_url, $original_url, $_context) {
    if (false !== strpos($original_url, 'data-processor.js') || false !== strpos($original_url, 'data-processor-admin.js') || false !== strpos($original_url, 'g365-press/js/app.js') || false !== strpos($original_url, 'g365-press/js/app-admin.js') || false !== strpos($original_url, 'data-processor-front-admin.js')) {
      remove_filter('clean_url','mod_clean_url',10,3);
      $url_parts = parse_url($good_protocol_url);
      $url_build = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
      if( !empty($url_parts['query']) ) $url_build .= '?' . $url_parts['query'];
//       if( !empty($url_parts['fregment']) ) $url_build .= '#' . $url_parts['fragment'];
      return $url_build . "' id='g365_form_script";
    }
    return $good_protocol_url;
}

/**
 * Enqueue scripts and styles
 */
function theme_scripts() {
	
	if( is_admin() ) return;
	
// 	wp_dequeue_style( 'open-sans' );
// 	wp_dequeue_style( 'jetpack-google-fonts' );
// 	wp_enqueue_style( 'font-all', '//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:300,400,400i,600,700,800|Teko:300,400,500,600,700' );
// 	wp_enqueue_style( 'font-all', '//fonts.googleapis.com/css?family=Archivo+Narrow:400,700,400i|Teko:700,600' );
  
  $fonts_to_load = ( is_page_template( 'player-profile.php' ) ) ? '//fonts.googleapis.com/css?family=Montserrat:400,400i,700|News+Cycle:400,700|Titillium+Web:900' : '//fonts.googleapis.com/css?family=Montserrat:400,400i,700|News+Cycle:400,700';
	wp_enqueue_style( 'font-all', $fonts_to_load );
	wp_enqueue_style( 'foundation-all', get_template_directory_uri() . "/css/style.css", array('font-all'), filemtime(get_template_directory() . '/css/style.css') );
	wp_enqueue_style( 'stat-card', get_template_directory_uri() . "/css/stat-card.css", array('font-all'), filemtime(get_template_directory() . '/css/stat-card.css') );
	wp_enqueue_style( 'jqueryUI', "https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.min.css?loc=11", array());

  // add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
//   wp_enqueue_style( 'automate-awards','/wp-content/plugins/g365-data-manager/css/automate_awards.css', array(), '1.0', 'all');

	wp_deregister_script('jquery');
	wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js?loc=12', array(), '3.6.0', true );
  wp_enqueue_script( 'jqueryUI', 'https://code.jquery.com/ui/1.13.0/jquery-ui.min.js?loc=13', array(), '1.13.0', true );
	wp_enqueue_script( 'foundation' );
  wp_enqueue_script( 'js-all' );

}
add_action( 'wp_enqueue_scripts', 'theme_scripts');

function add_firebase_sdk_app() {
    // Enqueue Firebase JavaScript SDK
    wp_enqueue_script('firebase-app', 'https://www.gstatic.com/firebasejs/9.0.1/firebase-app.js', array(), null, true);
  
    //Make sure the script is treated as a module
    add_filter('script_loader_tag', function ($tag, $handle, $src) {
      if('firebase-app' !== $handle) {
        return $tag;
      }
      //Modify the script tag to include 'type="module"'
      return '<script type="module" src="' . esc_url($src) . '"></script>';
    }, 10, 3);

    // Add your Firebase configuration
    wp_add_inline_script('firebase-app', "
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.0.1/firebase-app.js';
        var firebaseConfig = {
            apiKey: 'AIzaSyCaBMTKjOlVUZQlkBt6knQROqeHYceRnD0',
            authDomain: 'the-passport-app.firebaseapp.com',
            projectId: 'the-passport-app',
            storageBucket: 'the-passport-app.appspot.com',
            messagingSenderId: '823562590667',
            appId: '1:823562590667:web:22d327d74a7b1a63debe4e',
            measurementId: 'G-LD7EE1VM26'
        };
        initializeApp(firebaseConfig);
    ");
}

// Hook into wp_enqueue_scripts
add_action('wp_enqueue_scripts', 'add_firebase_sdk_app');


/*
* favicon
*/
function add_apple_touch_icons(){
	echo '<meta name="HandheldFriendly" content="true" />'. "\n";
	echo '<meta name="MobileOptimized" content="width" />'. "\n";
	echo '<link rel="icon" href="' . get_template_directory_uri() . '/assets/favicon-32x32.png" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . get_template_directory_uri() . '/assets/favicon-192x192.png" sizes="192x192">' . "\n";
	echo '<link rel="apple-touch-icon-precomposed" href="' . get_template_directory_uri() . '/assets/favicon-180x180.png" sizes="180x180">' . "\n";
	echo '<meta name="msapplication-TileImage" content="' . get_template_directory_uri() . '/assets/favicon-270x270.png" sizes="270x270">' . "\n";
	echo '<meta name="theme-color" content="#000000">' . "\n";

  //   echo '<meta name="apple-itunes-app" content="app-id=1439949753"/>';
}
add_action('wp_head', 'add_apple_touch_icons', 1);

/**
 * Setup theme support
 */
if ( ! function_exists( 'theme_setup' ) ) :
	function theme_setup() {
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
    add_theme_support( 'align-wide' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-formats', array( 'quote' ) );

		load_theme_textdomain( 'g365-press' );
		add_image_size( 'featured-home', 900, 420, array( 'center', 'center') );
    add_image_size('medium', get_option( 'medium_size_w' ), get_option( 'medium_size_h' ), array( 'center', 'center') );
    add_image_size('large', get_option( 'large_size_w' ), get_option( 'large_size_h' ), array( 'center', 'center') );

		register_nav_menus( array(
			'title_nav'   => 'Title Navigation Bar',
			'main_nav'    => 'Main Navigation Bar',
			'footer_nav'  => 'Footer Navigation List',
			'event_menu_region'   => 'Regional Events Menu',
			'event_menu_season'   => 'Seasonal Events Menu',
			)
		);
	}
endif; // theme_setup
add_action( 'after_setup_theme', 'theme_setup', 5 );

//down sample any uploaded images to a maximum size
function upload_image_limiter ( $params )
{
    $filePath = $params['file'];

    if ( (!is_wp_error($params)) && file_exists($filePath) && in_array($params['type'], array('image/png','image/gif','image/jpeg','image/jpg')))
    {
        $image = wp_get_image_editor( $filePath );
        if ( ! is_wp_error( $image ) ) {
          $image->resize( 1200, 1000 );
          $image->save( $filePath );
        }
        else
        {
            $params = wp_handle_upload_error
            (
                $filePath,
                $image->get_error_message() 
            );
        }
    }

    return $params;
}
add_filter( 'wp_handle_upload', 'upload_image_limiter' );

// Event menu by region
if ( ! function_exists( 'g365_event_menu_region_nav' ) ) {
	function g365_event_menu_region_nav( $placer = null ) {
		return wp_nav_menu( array(
			'theme_location' => 'event_menu_region',
      'container'      => false,
      'menu_id'        => 'event-menu-region',
			'menu_class'     => 'grid-x grid-margin-x',
			'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
			'fallback_cb'    => false,
			'walker'         => new g365_Event_Walker(),
      'echo'           => false
		));
	}
}
// Event menu by season 'g365_event_menu_nav'
if ( ! function_exists( 'g365_event_menu_season_nav' ) ) {
	function g365_event_menu_season_nav( $placer = null ) {
		return wp_nav_menu( array(
			'theme_location' => 'event_menu_season',
      'container'      => false,
      'menu_id'        => 'event-menu-season',
			'menu_class'     => 'grid-x grid-margin-x small-margin-collapse',
			'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
			'fallback_cb'    => false,
			'walker'         => new g365_Event_Walker(),
      'echo'           => false
		));
	}
}
// Main Navigation w/ Drawers
if ( ! function_exists( 'g365_main_nav' ) ) {
	function g365_main_nav() {
    function mobile_menu_support($classes, $item, $args) {
      $classes[] = ( in_array('non-mobile', $classes) ) ? 'hide' : 'hide-for-medium';
      return $classes;
    }
    add_filter('nav_menu_css_class', 'mobile_menu_support', 1, 3);
    $title_menu_for_mobile = wp_nav_menu( array(
			'theme_location' => 'title_nav',
			'container'      => false,
			'items_wrap'     => '%3$s',
      'echo'           => false
		));
    remove_filter('nav_menu_css_class', 'mobile_menu_support', 1 );

		wp_nav_menu( array(
			'theme_location' => 'main_nav',
			'container'      => false,
			'menu_class'     => 'dropdown menu medium-horizontal align-center',
			'items_wrap'     => '<ul id="main-nav" class="%2$s" data-dropdown-menu>' . $title_menu_for_mobile . '%3$s</ul>',
			'fallback_cb'    => false,
			'walker'         => new g365_Top_Bar_Walker()
		));
	}
}
// Main Navigation Mega
if ( ! function_exists( 'g365_mega_nav' ) ) {
	function g365_mega_nav() {
		wp_nav_menu( array(
			'theme_location' => 'main_nav',
			'container'      => false,
			'menu_class'     => 'menu grid-x grid-margin-x menu-mega',
			'items_wrap'     => '<ul id="main-nav" class="%2$s">%3$s</ul>',
			'fallback_cb'    => false,
			'walker'         => new g365_Mega_Walker()
		));
	}
}
// Main Navigation Side Slide
if ( ! function_exists( 'g365_side_slide_nav' ) ) {
	function g365_side_slide_nav() {
		wp_nav_menu( array(
			'theme_location' => 'main_nav',
			'container'      => false,
			'menu_class'     => 'vertical dropdown menu',
			'items_wrap'     => '<ul id="main-nav" class="%2$s" data-dropdown-menu>%3$s</ul>',
			'fallback_cb'    => false,
			'walker'         => new g365_Side_Slide_Walker()
		));
	}
}
// Title Nav
if ( ! function_exists( 'g365_title_nav' ) ) {
	function g365_title_nav() {
		wp_nav_menu( array(
			'theme_location' => 'title_nav',
			'container'      => false,
			'menu_class'     => 'title-nav dropdown menu horizontal align-center',
			'items_wrap'     => '<ul id="title-nav" class="%2$s" data-dropdown-menu>%3$s</ul>',
			'fallback_cb'    => false
		));
	}
}
// Footer Nav
if ( ! function_exists( 'g365_footer_nav' ) ) {
	function g365_footer_nav() {
		wp_nav_menu( array(
			'theme_location' => 'footer_nav',
			'container'      => false,
			'menu_class'     => 'menu vertical medium-horizontal align-center text-center',
			'items_wrap'     => '<ul id="footer-nav" class="%2$s">%3$s</ul>',
			'fallback_cb'    => false,
			'walker'         => new g365_Top_Bar_Walker()
		));
	}
}


/**
 * Add support for buttons in the top-bar menu:
 * 1) In WordPress admin, go to Apperance -> Menus.
 * 2) Click 'Screen Options' from the top panel and enable 'CSS CLasses' and 'Link Relationship (XFN)'
 * 3) On your menu item, type 'has-form' in the CSS-classes field. Type 'button' in the XFN field
 * 4) Save Menu. Your menu item will now appear as a button in your top-menu
*/
if ( ! function_exists( 'add_menuclass' ) ) {
	function add_menuclass( $ulclass ) {
		$find = array('/<a rel="button"/', '/<a title=".*?" rel="button"/');
		$replace = array('<a rel="button" class="button"', '<a rel="button" class="button"');

		return preg_replace( $find, $replace, $ulclass, 1 );
	}
	add_filter( 'wp_nav_menu','add_menuclass' );
}

if ( ! function_exists( 'g365_excerpt' ) ) :
	/**
	 * Displays the optional excerpt.
	 * Wraps the excerpt in a div element.
	 * Create your own g365_excerpt() function to override in a child theme.
	 * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
	 */
	function g365_excerpt( $class = 'entry-summary' ) {
		$class = esc_attr( $class );

		if ( has_excerpt() || is_search() ) : ?>
			<div class="<?php echo $class; ?>">
				<?php the_excerpt(); ?>
			</div><!-- .<?php echo $class; ?> -->
		<?php endif;
	}
endif;

if ( ! function_exists( 'g365_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
 * Create your own g365_excerpt_more() function to override in a child theme.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function g365_excerpt_more() {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'g365-press' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'g365_excerpt_more' );
endif;

if ( ! function_exists( 'g365_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 * Create your own g365_entry_meta() function to override in a child theme.
 */
function g365_entry_meta() {
	if ( 'posts' === get_post_type() ) {
		$author_avatar_size = apply_filters( 'g365_author_avatar_size', 49 );
		printf( '<span class="byline"><span class="author vcard">%1$s <span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			_x( 'Author', 'Used before post author name.', 'g365-press' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		g365_entry_date();
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'g365-press' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( 'posts' === get_post_type() ) {
// 		g365_entry_taxonomies();
	}

	if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'g365-press' ), get_the_title() ) );
		echo '</span>';
	}
}
endif;

if ( ! function_exists( 'g365_entry_date' ) ) :
/**
 * Prints HTML with date information for current post.
 * Create your own g365_entry_date() function to override in a child theme.
 */
function g365_entry_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
// 		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> | <time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);

	printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
		_x( 'Posted on', 'Used before publish date.', 'g365-press' ),
		esc_url( get_permalink() ),
		$time_string
	);
}
endif;

function post_type_content($page_name, $print = true, $post_type = 'page' ) {
	$page = get_page_by_title( $page_name, 'OBJECT', $post_type );
	$page = ( $page !== null ) ? $page->post_content : null;
	if( !empty($page) ) {
		if( $print ){
			echo apply_filters('the_content', $page);
		} else {
			return apply_filters('the_content', $page);
		}
	} else {
		return null;
	}
}

function foundation_content($content) {
	if( strpos(trim($content), '<div class="grid-x') === 0 ){
		return $content;
	} else {
    $return_string = '<div class="grid-x grid-margin-x">';
    if( strpos(trim($content), '<div class="cell') === 0 ) {
      $return_string .= $content;
    } else {
      $return_string .= '<div class="cell small-12">' . $content . '</div>';
    }
    $return_string .= '</div>';
		return $return_string;
	}
} 
add_filter('the_content', 'foundation_content');

//admin page functions


//add options to admin menu
function g365_admin_menu() {
	add_menu_page( 'SPP Manager', 'SPP Manager', 'manage_options', 'admin_data', 'g365_admin', 'dashicons-chart-line', 26  );
  add_submenu_page( 'admin_data', 'Dashboard', 'Dashboard', 'manage_options', 'admin_data', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Tournament Manager', 'Tournament Manager', 'manage_options', 'admin_data_tournaments', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Rosters', 'Rosters', 'manage_options', 'admin_data_ros', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Roster', 'Roster', 'manage_options', 'admin_data_rosters', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Player Certs', 'Player Certifications', 'manage_options', 'admin_data_pl_cert', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Player Events', 'Player Events', 'manage_options', 'admin_data_pl_ev', 'g365_admin' ); 
  add_submenu_page( 'admin_data', 'Player Event', 'Player Event', 'manage_options', 'admin_data_stats', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Player Event SS', 'Player Event SS', 'manage_options', 'admin_data_stats_ss', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Players', 'Players', 'manage_options', 'admin_data_pls', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Player', 'Player', 'manage_options', 'admin_data_players', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Club', 'Club', 'manage_options', 'admin_data_clubs', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Settings', 'Settings', 'manage_options', 'admin_data_settings', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Export', 'Export', 'manage_options', 'admin_export_data', 'g365_admin' );  
  add_submenu_page( 'admin_data', 'Claim', 'Claim', 'manage_options', 'admin_claim_data', 'g365_admin' ); 
  add_submenu_page( 'admin_data', 'Stat', 'Stat', 'manage_options', 'admin_stat_data', 'g365_admin' ); 
  add_submenu_page( 'admin_data', 'Awards', 'Awards', 'manage_options', 'admin_data_awards', 'g365_admin' );  
//   add_submenu_page( 'admin_data', 'Badges', 'Badges', 'manage_options', 'admin_badge_data', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Badges', 'Badges', 'manage_options', 'admin_g365_badge_data', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Photo Verification', 'Photo Verification', 'manage_options', 'admin_data_photo_verif', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Video Verification', 'Video Verification', 'manage_options', 'admin_data_video_verif', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Features', 'Features', 'manage_options', 'admin_feature_api', 'g365_admin' );
  add_submenu_page( 'admin_data', 'All Tournament', 'All Tournament', 'manage_options', 'admin_all_tournament', 'g365_admin' );
  //add new team reviews
  add_submenu_page( 'admin_data', 'Team Events SS', 'Team Events SS', 'manage_options', 'admin_data_team_ev', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Team Event SS', 'Team Event SS', 'manage_options', 'admin_data_team_stats', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Passport Report', 'Passport Report', 'manage_options', 'admin_pass_rep', 'g365_admin' );
  add_submenu_page( 'admin_data', 'Data Merge', 'Data Merge', 'manage_options', 'admin_data_merge', 'g365_admin' );
}
add_action( 'admin_menu', 'g365_admin_menu' );

use g365_admin as admin_data_tournaments;
use g365_admin as admin_data_ros;
use g365_admin as admin_data_rosters;
use g365_admin as admin_data_pl_cert;
use g365_admin as admin_data_pl_ev;
use g365_admin as admin_data_stats;
use g365_admin as admin_data_stats_ss;
use g365_admin as admin_data_pls;
use g365_admin as admin_data_players;
use g365_admin as admin_data_clubs;
use g365_admin as admin_data_settings;
use g365_admin as admin_export_data;
use g365_admin as admin_claim_data;
use g365_admin as admin_data_awards;
// use g365_admin as admin_badge_data;
use g365_admin as admin_g365_badge_data;
use g365_admin as admin_data_photo_verif;
use g365_admin as admin_data_video_verif;
use g365_admin as admin_all_tournament;
use g365_admin as admin_data_team_ev;
use g365_admin as admin_data_team_stats;


//build admin page
function g365_admin(){
  wp_enqueue_style( 'foundation-admin', site_url( '/wp-content/themes/g365-press/css/', 'https' ) . "style-admin.css", array(), filemtime(get_template_directory() . '/css/style-admin.css') );
	wp_enqueue_script( 'admin-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js?loc=14', array(), '3.6.0', true );
  wp_enqueue_script( 'foundation' );
	wp_enqueue_script( 'js-g365-all-admin');
  wp_enqueue_script( 'js-g365-all-admin-tournament');
  wp_enqueue_script( 'jqueryUI');
	$g_action = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_URL );
	$g365_sections = array(
		'admin_data' => array(
			'title'		=> 'Dashboard',
			'tab_name'	=> 'Dashboard',
			'url'			=> '?page=admin_data'
		),
		'admin_data_tournaments' => array(
			'title'		=> 'Manage Tournaments',
			'tab_name'	=> 'Tournament',
			'url'			=> '?page=admin_data_tournaments'
		),
		'admin_data_ros' => array(
			'title'		=> 'Manage Rosters',
			'tab_name'	=> 'Rosters',
			'url'			=> '?page=admin_data_ros'
		),
		'admin_data_rosters' => array(
			'title'		=> 'Manage Roster',
			'tab_name'	=> 'Roster',
			'url'			=> '?page=admin_data_rosters'
		),
		'admin_data_pl_cert' => array(
			'title'		=> 'Manage Player Certification',
			'tab_name'	=> 'Certification',
			'url'			=> '?page=admin_data_pl_cert'
		),
		'admin_data_pl_ev' => array(
			'title'		=> 'Manage Player Events',
			'tab_name'	=> 'Player Events',
			'url'			=> '?page=admin_data_pl_ev'
		),
		'admin_data_stats' => array(
			'title'		=> 'Manage Player Event',
			'tab_name'	=> 'Player Event',
			'url'			=> '?page=admin_data_stats'
		),
    'admin_data_stats_ss' => array(
			'title'		=> 'Manage Player Event',
			'tab_name'	=> 'Player Event SS',
			'url'			=> '?page=admin_data_stats_ss'
		),
		'admin_data_pls' => array(
			'title'		=> 'Manage Players',
			'tab_name'	=> 'Players',
			'url'			=> '?page=admin_data_pls'
		),
		'admin_data_players' => array(
			'title'		=> 'Manage Player',
			'tab_name'	=> 'Player',
			'url'			=> '?page=admin_data_players'
		),
		'admin_data_clubs' => array(
			'title'		=> 'Manage Clubs',
			'tab_name'	=> 'Club',
			'url'			=> '?page=admin_data_clubs'
		),
		'admin_data_settings' => array(
			'title'		=> 'Manage Site Settings',
			'tab_name'	=> 'Settings',
			'url'			=> '?page=admin_data_settings'
		),
		'admin_export_data' => array(
			'title'		=> 'Export Data',
			'tab_name'	=> 'Export',
			'url'			=> '?page=admin_export_data'
		),
		'admin_claim_data' => array(
			'title'		=> 'Claim Data',
			'tab_name'	=> 'Claim',
			'url'			=> '?page=admin_claim_data'
		),
		'admin_stat_data' => array(
			'title'		=> 'Stat Data',
			'tab_name'	=> 'Stat',
			'url'			=> '?page=admin_stat_data'
		),
		'admin_data_awards' => array(
			'title'		=> 'Manage Awards',
			'tab_name'	=> 'Awards',
			'url'			=> '?page=admin_data_awards'
		),
// 		'admin_badge_data' => array(
// 			'title'		=> 'Badge Data',
// 			'tab_name'	=> 'Badges',
// 			'url'			=> '?page=admin_badge_data'
// 		),
    'admin_g365_badge_data' => array(
			'title'		=> 'Badge Data',
			'tab_name'	=> 'Badges',
			'url'			=> '?page=admin_g365_badge_data'
		),
    'admin_data_photo_verif' => array(
			'title'		=> 'Manage Photo Verification',
			'tab_name'	=> 'Photo Verification',
			'url'			=> '?page=admin_data_photo_verif'
		),
    'admin_data_video_verif' => array(
			'title'		=> 'Manage Video Verification',
			'tab_name'	=> 'Video Verification',
			'url'			=> '?page=admin_data_video_verif'
		),
    'admin_api_feature' => array(
			'title'		=> 'Feature API',
			'tab_name'	=> 'Features',
			'url'			=> '?page=admin_feature_api'
		),
    'admin_all_tournament' => array(
			'title'		=> 'All Tournament Data',
			'tab_name'	=> 'All Tournament',
			'url'			=> '?page=admin_all_tournament'
		),
    'admin_data_team_ev' => array(
			'title'		=> 'Manage Team Events',
			'tab_name'	=> 'Team Events SS',
			'url'			=> '?page=admin_data_team_ev'
		),
		'admin_data_team_stats' => array(
			'title'		=> 'Manage Team Event',
			'tab_name'	=> 'Team Event SS',
			'url'			=> '?page=admin_data_team_stats'
		),
    'admin_pass_rep' => array(
			'title'		=> 'Report Data',
			'tab_name'	=> 'Passport Report',
			'url'			=> '?page=admin_pass_rep'
		),
    'admin_data_merge' => array(
			'title'		=> 'Data Merge',
			'tab_name'	=> 'Data Merge',
			'url'			=> '?page=admin_data_merge'
		),
	);
	echo '<div id="g365_data_manager_wrapper" class="g365_data_manager_wrapper">';
	$g365_admin_url = '';
	$nav = '';
	foreach( $g365_sections as $section => $section_data ) {
		if( $section == $g_action ) {
			echo '<h1>' . $section_data['title'] . '</h1>';
			$g365_admin_url = $section_data['url'];
			if( $section !== 'admin_data' ) $nav .= '<a href="' . $section_data['url'] . '" class="nav-tab nav-tab-active">' . $section_data['tab_name'] . '</a>';
		} else {
			if( $section !== 'admin_data_settings' && $section !== 'admin_data' ) $nav .= '<a href="' . $section_data['url'] . '" class="nav-tab">' . $section_data['tab_name'] . '</a>';
		}
	}
	if( $g_action !== 'admin_data_settings' ) echo '<nav class="nav-tab-wrapper">' . $nav . '</nav><div id="g365_data_manager_admin" class="g365_data_manager_content_wrapper">';

	switch( $g_action ) {
    case 'admin_data':
      ?>
      <h1>Dashboard</h1>
      <small>Welcome to the G365 Data Hub</small>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12">
          <p>Orientation Documents to follow.</p>
        </div>
      </div>
      <?php
      break;
		case 'admin_data_pl_cert':
      //static verification levels
      $verification_levels = ['Incomplete','Awaiting Verification', 'Awaiting Verification - with Documents Attached','Verified','Certified'];
      //default per_pg
      $default_per_pg = 50;
      //attempt to retrieve vars
      $pg_no = filter_input( INPUT_GET, 'pg_no', FILTER_SANITIZE_NUMBER_INT );
      $per_pg = filter_input( INPUT_GET, 'per_pg', FILTER_SANITIZE_NUMBER_INT );
      $ver_lvl = filter_input( INPUT_GET, 'ver_lvl', FILTER_SANITIZE_NUMBER_INT );
      $order_by = filter_input( INPUT_GET, 'odr', FILTER_SANITIZE_NUMBER_INT );
      //check vars, set boundaries
      if( empty($pg_no) ) $pg_no = 1;
      if( empty($per_pg) || $per_pg > 100 ) $per_pg = $default_per_pg;
      if( (empty($ver_lvl) && $ver_lvl !== '0') || $ver_lvl > 4  ) $ver_lvl = 1;
      $change_compare = ( $ver_lvl == 1 || $ver_lvl == 2 ) ? '<=' : '=';
      if( empty($order_by) ) $order_by = 1;
      switch( $order_by ){
        case 2:
          $order_by = 'player.name';
          break;
        case 3:
          $order_by = 'player.last_name';
          break;
        default:
          $order_by = 'player.updatetime DESC';
          break;
      }
      //get total records for $ver_lvl requested
      $pl_tobe_count = g365_count_players_verify( $ver_lvl );
      $total_pages = ceil($pl_tobe_count/$per_pg);
      //limit $pg_no to max if over
      if( $pg_no > $total_pages ) $pg_no = $total_pages;
      //get player data
      $pl_tobe_cert = g365_get_players_verify( $ver_lvl, $pg_no, $per_pg, 'player.updatetime DESC', $change_compare );
      //set variable brackets for pagnation
      $start_pages = ( $total_pages > 9 ) ? ((($pg_no - 5) < 1) ? 1 : $pg_no - 5) : 1;
      $end_pages = ( $total_pages > 9 ) ? ((($start_pages + 9) > $total_pages) ? $total_pages : $start_pages + 9) : $total_pages;
      $admin_string = '';
      if( current_user_can('administrator') ) {
        $admin_string = ', "g365_admin" : "true"';
        $current_user = wp_get_current_user();
        $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
        $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
      }

      ?>
      <h2>Players Certification Status</h2>
      <small></small>
      <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="player_admin"></div>
      </div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Players" : {"name" : "Make changes to player data", "title" : "", "type" : "player_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12">
          <div class="button-group tiny-margin-bottom">
            <?php foreach( $verification_levels as $lvl => $lvl_name ) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $lvl . (($per_pg == 50) ? '' : '&per_pg=' . $per_pg ) . '" class="button' . (($lvl == $ver_lvl) ? ' is-active' : '') . (($lvl == 0 || $lvl == 4) ? ' hide' : '') . '">' . $lvl_name . '</a>'; ?>
          </div>
        </div>
      <?php if( !empty($pl_tobe_cert) ) {
        echo '<div class="cell small-6">Page ' . $pg_no . '/' . $total_pages . '</div>';
        echo '<div class="cell small-6 text-right">Per Page: <input class="no-margin-bottom inline input-shrink" type="number" data-g365-admin-page-target="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&per_pg=" data-g365-per-pg-og-val="' . $per_pg . '" value="' . $per_pg . '" onfocusout="if( $(this).attr(\'data-g365-per-pg-og-val\') !== $(this).val() ) document.location=$(this).attr(\'data-g365-admin-page-target\') + $(this).val();" /></div>';
        ?>
        <div class="cell small-12">
          <table class="edit-table">
            <thead>
              <tr>
                <th>Action</th>
                <th>Player</th>
                <th>Last Update</th>
                <th>Age</th>
                <th>Birthdate</th>
                <?php if( $ver_lvl == 1 || $ver_lvl == 2 ) echo '<th>Birth Cert</th>'; ?>
                <th>Grade</th>
                <th>Class</th>
                <?php if( $ver_lvl == 1 || $ver_lvl == 2 ) echo '<th>Report Card</th>'; ?>
                <?php if( $ver_lvl == 1 || $ver_lvl == 2 ) echo '<th>Verify?</th>'; ?>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach( $pl_tobe_cert as $pl_dex => $pl_data ) {
                if( empty($pl_data->name) ) continue;
                $pl_data->notes = json_decode($pl_data->notes);
                $today = date("Y-m-d");
                $birthday_formatted = ((empty($pl_data->birthday)) ? '' : date('m/d/y', strtotime($pl_data->birthday)));
                $grade_formatted = ((empty($pl_data->grad_year)) ? '' : g365_class_to_grade($pl_data->grad_year));
                echo '<tr class="player-cert ' . (( $pl_data->verified < 2 ) ? 'unverified-player' : 'verified-player') . '">';
                echo '<td><a class="button small tiny-margin g365-edit-data" id="pl_ad_' . $pl_data->id . '" data-g365_type="player_admin::' . $pl_data->id . '">Edit</a></td>';
                echo '<td>' . $pl_data->name . '</td>';
                echo '<td>' . date('m-d-Y g:i a', strtotime($pl_data->updatetime)) . '</td>';
                echo '<td>' . ((empty($pl_data->birthday)) ? '' : date_diff(date_create($pl_data->birthday), date_create($today))->format('%y')) . '</td>';
                echo '<td>' . $birthday_formatted . '</td>';
                if( $ver_lvl == 1 || $ver_lvl == 2 ) echo '<td>' . ((empty($pl_data->bcert_img)) ? '' : '<div class="inline add-button" onclick="$(this).next().toggleClass(\'hide\');">+</div><div class="supplemental-data hide"><a class="close-button remove-button" onclick="$(this).parent().addClass(\'hide\');">X</a><div class="grid-x grid-margin-x"><h3 class="cell small-12">' . $pl_data->name . ' Birth Certificate: DOB: ' . $birthday_formatted . '</h3><img class="tournament-player-img" loading="lazy" onclick="$(this).toggleClass(\'huge\');" src="' . site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $pl_data->bcert_img . '" /></div></div>') . '</td>';
                echo '<td>' . $grade_formatted . '</td>';
                echo '<td>' . ((empty($pl_data->grad_year)) ? '' : $pl_data->grad_year) . '</td>';
                if( $ver_lvl == 1 || $ver_lvl == 2 ) echo '<td>' . ((empty($pl_data->recard_img)) ? '' : '<div class="inline add-button" onclick="$(this).next().toggleClass(\'hide\');">+</div><div class="supplemental-data hide"><a class="close-button remove-button" onclick="$(this).parent().addClass(\'hide\');">X</a><div class="grid-x grid-margin-x"><h3 class="cell small-12">' . $pl_data->name . ' Report Card: Grade: ' . $grade_formatted . '</h3><img class="tournament-player-img" loading="lazy" onclick="$(this).toggleClass(\'huge\');" src="' . site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $pl_data->recard_img . '" /></div></div>') . '</td>';
                if( $ver_lvl == 1 || $ver_lvl == 2 ) echo '<td class="verification-form"><form class="g365_admin_verification_toggle_cert"><input type="hidden" class="pl_id" name="pl_' . $pl_data->id . '[id]" value="' . $pl_data->id . '" /><input type="hidden" name="pl_' . $pl_data->id . '[proc_type]" value="proc_data" /><input type="radio"  id="pl_' . $pl_data->id . '_yes" name="pl_' . $pl_data->id . '[data][verified]" value="2" /><label for="pl_' . $pl_data->id . '_yes">Yes</label><input type="radio" id="pl_' . $pl_data->id . '_no" name="pl_' . $pl_data->id . '[data][verified]" value="1" checked /><label for="pl_' . $pl_data->id . '_no">No</label></form></td>';
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php
          if( $total_pages > 1 ) { ?>
          <div class="cell small-12">
            <div class="button-group tiny-margin-top">
            <?php
            if( $start_pages > 1 ) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button"><<</a><a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . ($pg_no - 1) . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button"><</a>';
            foreach(range($start_pages, $end_pages)  as $i) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . $i . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button' . (($i == $pg_no) ? ' is-active' : '') . '">' . $i . '</a>';
            if( $total_pages > 10 ) echo '<input class="button inline input-shrink" type="number" data-g365-admin-page-target="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '&pg_no=" data-g365-per-pg-og-val="' . $pg_no . '" onfocusout="if( $(this).val() !== \'\' && $(this).attr(\'data-g365-per-pg-og-val\') !== $(this).val() ) document.location=$(this).attr(\'data-g365-admin-page-target\') + $(this).val();" placeholder="#" />';
            if( $end_pages < $total_pages ) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . ($pg_no + 1) . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button">></a><a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . $total_pages . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button">>></a>';
            ?>
            </div>
          </div>
          <?php
          }
        } else {
        ?>
        <div class="cell small-12 medium-8 large-6">
          <p>No players for this progression at this time.</p>
        </div>
        <?php
        }
        ?>
      </div>
      <?php
			break;
		case 'admin_data_pls':
      //default per_pg
      $default_per_pg = 50;
      //attempt to retrieve vars
      $pg_no = filter_input( INPUT_GET, 'pg_no', FILTER_SANITIZE_NUMBER_INT );
      $per_pg = filter_input( INPUT_GET, 'per_pg', FILTER_SANITIZE_NUMBER_INT );
      //check vars, set boundaries
      if( empty($pg_no) ) $pg_no = 1;
      if( empty($per_pg) || $per_pg > 100 ) $per_pg = $default_per_pg;
      //get total records
      $pl_count = g365_count_players();
      $total_pages = ceil($pl_count/$per_pg);
      //limit $pg_no to max if over
      if( $pg_no > $total_pages ) $pg_no = $total_pages;
      //get player data
      $pls = g365_get_players( $pg_no, $per_pg );
      //set variable brackets for pagnation
      $start_pages = ( $total_pages > 9 ) ? ((($pg_no - 5) < 1) ? 1 : $pg_no - 5) : 1;
      $end_pages = ( $total_pages > 9 ) ? ((($start_pages + 9) > $total_pages) ? $total_pages : $start_pages + 9) : $total_pages;
      ?>
      <h2>Recent Player Updates</h2>
      <small></small>
      <div class="grid-x grid-margin-x">
      <?php if( !empty($pls) ) {
        echo '<div class="cell small-6">Page ' . $pg_no . '/' . $total_pages . '</div>';
        echo '<div class="cell small-6 text-right">Per Page: <input class="no-margin-bottom inline input-shrink" type="number" data-g365-admin-page-target="' . $g365_admin_url . '&per_pg=" data-g365-per-pg-og-val="' . $per_pg . '" value="' . $per_pg . '" onfocusout="if( $(this).attr(\'data-g365-per-pg-og-val\') !== $(this).val() ) document.location=$(this).attr(\'data-g365-admin-page-target\') + $(this).val();" /></div>';
        ?>
        <div class="cell small-12">
          <table class="edit-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Player</th>
                <th>Last Update</th>
                <th>Age</th>
                <th>Birthdate</th>
                <th>Grade</th>
                <th>Class</th>
              </tr>
            </thead>
            <tbody>
              <?php
//               array_multisort(array_column($pls, 'name'), SORT_ASC, SORT_STRING, $pls); // Sort name field
              foreach( $pls as $pl_dex => $pl_data ) { 
                if( empty($pl_data->name) ) continue;
                $pl_data->notes = json_decode($pl_data->notes);
                $today = date("Y-m-d");
                $birthday_formatted = ((empty($pl_data->birthday)) ? '' : date('m/d/y', strtotime($pl_data->birthday)));
                $grade_formatted = ((empty($pl_data->grad_year)) ? '' : g365_class_to_grade($pl_data->grad_year));
                echo '<tr class="player-cert ' . (( $pl_data->verified < 2 ) ? 'unverified-player' : 'verified-player') . '">';
                  echo '<td>' . $pl_data->id . '</td>';
                  echo '<td>' . $pl_data->name . '</td>';
                  echo '<td>' . date('m-d-Y g:i a', strtotime($pl_data->updatetime)) . '</td>';
                  echo '<td>' . ((empty($pl_data->birthday)) ? '' : date_diff(date_create($pl_data->birthday), date_create($today))->format('%y')) . '</td>';
                  echo '<td>' . $birthday_formatted . '</td>';
                  echo '<td>' . $grade_formatted . '</td>';
                  echo '<td>' . ((empty($pl_data->grad_year)) ? '' : $pl_data->grad_year) . '</td>';
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php
          if( $total_pages > 1 ) { ?>
          <div class="cell small-12">
            <div class="button-group tiny-margin-top">
            <?php
            if( $start_pages > 1 ) echo '<a href="' . $g365_admin_url . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button"><<</a><a href="' . $g365_admin_url . '&pg_no=' . ($pg_no - 1) . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button"><</a>';
            foreach(range($start_pages, $end_pages)  as $i) echo '<a href="' . $g365_admin_url . '&pg_no=' . $i . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button' . (($i == $pg_no) ? ' is-active' : '') . '">' . $i . '</a>';
            if( $total_pages > 10 ) echo '<input class="button inline input-shrink" type="number" data-g365-admin-page-target="' . $g365_admin_url . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '&pg_no=" data-g365-per-pg-og-val="' . $pg_no . '" onfocusout="if( $(this).val() !== \'\' && $(this).attr(\'data-g365-per-pg-og-val\') !== $(this).val() ) document.location=$(this).attr(\'data-g365-admin-page-target\') + $(this).val();" placeholder="#" />';
            if( $end_pages < $total_pages ) echo '<a href="' . $g365_admin_url . '&pg_no=' . ($pg_no + 1) . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button">></a><a href="' . $g365_admin_url . '&pg_no=' . $total_pages . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button">>></a>';
            ?>
            </div>
          </div>
          <?php
          }
        } else {
        ?>
        <div class="cell small-12 medium-8 large-6">
          <p>No players for this search.</p>
        </div>
        <?php
        }
        ?>
      </div>
      <?php
			break;
		case 'admin_data_ros':
      //see if we have a event id otherwise load the default rosters
      $org_id = filter_input( INPUT_GET, 'org_id', FILTER_SANITIZE_NUMBER_INT );
      $ev_id = filter_input( INPUT_GET, 'ev_id', FILTER_SANITIZE_NUMBER_INT );
      //set defaults
      if( empty($ev_id) ) {
        $ev_id = '0';
        $event_data = (object) array(
          'id'        => '0',
          'name'      => 'Default',
          'eventtime' => '9999-12-31 0:00:00',
          'divisions' => '{"8": "0", "9": "0", "10": "0", "11": "0", "12": "0", "13": "0", "14": "0", "15": "0", "16": "0", "17": "0", "41": "0", "42": "0", "43": "0", "44": "0", "46": "0", "47": "0"}'
        );
      }
      //if we have an org_id, get info for it
      if( !empty($org_id) ) {
        //globals for db
        global $wpdb;
        $wpdb_orgs = $wpdb->g365_orgs;
        $wpdb_rosters = $wpdb->g365_rosters;
        $wpdb_events = $wpdb->g365_events;
        $wpdb_teams = $wpdb->g365_teams;
        $wpdb_players = $wpdb->g365_players;

        //pull some org data to make sure we have a viable id
        $org_data = $wpdb->get_row( "SELECT * FROM $wpdb_orgs WHERE id = $org_id;" );
        
        $where_ev = "AND roster.event IN (0, $ev_id)";
        if( $ev_id === '0' ) $where_ev = "AND roster.event = 0";
        //try to pull rosters for the event we are loading
        $full_rosters = $wpdb->get_results(
          "SELECT roster.id AS ros_id, ev.id AS ev_id, ev.name AS ev_name, ev.eventtime AS ev_time, roster.event, roster.enabled AS ros_enabled, 
          tm.name AS team_name, tm.level AS team_level, roster.level AS ros_level
          FROM $wpdb_rosters AS roster
          LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
          LEFT JOIN $wpdb_teams AS tm ON roster.team=tm.id
          WHERE roster.org = $org_id $where_ev ORDER BY roster.event=0 DESC, ev.eventtime DESC, roster.level DESC, tm.name;"
        );
        //if we aren't on the default rosters page make a set for them too and get event details
        if( $ev_id !== '0' ) {
          $event_roster_ids = array();
          $event_data = $wpdb->get_row( "SELECT * FROM $wpdb_events WHERE id = $ev_id;" );
          $event_rosters = all_rosters_filter($ev_id, $full_rosters);
          //if we have event rosters collect the ids for later reference
          if( !empty($event_rosters) ) foreach( $event_rosters as $dex => $roster_data ) $event_roster_ids[] = $roster_data->ros_id;
        }

      }
      
      ?>
      <script type="text/javascript">
        document.g365_herf_adv = {'href':'/wp-admin/admin.php?page=admin_data_ros', 'href_build': {'org_id':'<?php echo $org_id; ?>', 'ev_id':'<?php echo $ev_id; ?>'}};
      </script>
      <div class="input-group">
        <h5 class="input-group-label">Club Teams:</h5>
        <input type="text" class='input-group-field g365_livesearch_input' data-g365_type="club_profiles_admin" placeholder="Enter Club Name" autocomplete="off" value="<?php echo $org_data->short_name ?? $org_data->name ?? ''; ?>" autofocus>
        <h5 class="input-group-label">Events:</h5>
        <input type="text" class='input-group-field g365_livesearch_input' data-g365_type="event_profiles_admin" placeholder="Enter Club Name" autocomplete="off" value="<?php echo $event_data->name ?? ''; ?>" autofocus>
      </div>

      <div class="cell small-12 medium-8 large-6">
        <?php if( !empty($org_id) ) { ?>
            <h2 class="tiny-margin-bottom large-margin-top"><?php echo $org_data->short_name ?? $org_data->name ?? ''; ?></h2>
            <?php
            //if we have roster data, process otherwise print the page to prompt roster creation
            if( empty($full_rosters) ) { ?>
              <div class="cell small-12 medium-8 large-6">
                <p>
                  Please add a roster to this club.
                </p>
                <script type="text/javascript">
                  var g365_form_details = {
                    "items" : {
                      "Teams":{
                        "name":"",
                        "title":"",
                        "type":"team_names_sl",
                        "items":{}
                      }
                    },
                    "wrapper_target" : "g365_form_options_anchor",
                    "user_org": "",
                    "admin_key": "<?php echo g365_make_admin_key(); ?>"
                  };
                </script>
                <div>
                  <div id="g365_form_options_anchor" data-g365_type="team_names_sl" data-g365_init_pre="team_names_sl_preset:::org_id::<?php echo $org_id; ?>:::org_name::<?php echo $org_data->name; ?>:::event_id::<?php echo $ev_id; ?>"></div>
                </div>
              </div>

            <?php } else {
              $default_rosters_sort = '';
              //pull default rosters out of the data set to build the listing for the roster adding buttons
              $default_rosters = all_rosters_filter('0', $full_rosters);
              $default_roster_ids = array();
              $event_rosters = $default_rosters;
              //now sort the default rosters by enabled and not
              $default_rosters_sort = array('enabled'=>array(),'disabled'=>array());
              foreach( $default_rosters as $dex => $roster_data ) {
                //collect the ids for later reference
                $default_roster_ids[] = $roster_data->ros_id;
                if( $roster_data->ros_enabled == 1 ) {
                  $default_rosters_sort['enabled'][] = '"' . $roster_data->ros_id . '":{"team_name":"' . g365_level_key($roster_data->team_level) . ((empty($roster_data->team_name)) ? '' : ' ' . $roster_data->team_name) . '", "status":"enabled_team", "team_level":"' . $roster_data->team_level . '"}';
                } else {
                  $default_rosters_sort['disabled'][] = '"' . $roster_data->ros_id . '":{"team_name":"' . g365_level_key($roster_data->team_level) . ((empty($roster_data->team_name)) ? '' : ' ' . $roster_data->team_name) . '", "status":"disabled_team", "team_level":"' . $roster_data->team_level . '"}';
                }
              }

    //               echo '<pre>';
    //               print_r( $default_rosters_sort );
    //               echo '</pre>';

              //if we have rosters for this event and it is editable print the form else print the event results
              if( $ev_id === '0' ) {
                $preset_vars = array(
                  'org_id::' . $org_id,
                  'rosters_enabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['enabled'])) . '}',
                  'rosters_disabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['disabled'])) . '}',
                );

                ?>
                <a  class="button g365-primary-submit float-right medium small-small g365-edit-data" id="team_names_sl_<?php echo $ev_id; ?>" data-g365_type="team_names_sl" data-g365_init_pre="team_names_sl_preset:::org_id::<?php echo $org_id; ?>:::org_name::<?php echo $wpdb->get_var( "SELECT name FROM $wpdb_orgs WHERE id = $org_id" ); ?>:::event_id::<?php echo $ev_id; ?>" data-wrapper_target="g365-reveal-modal">Add New Team/Roster</a>                         <a  class="button float-right medium tiny-margin-right small-small g365-edit-data no-margin-bottom" id="rosters_sl_new" data-g365_type="rosters_sl_xl" data-g365_init_pre="rosters_sl_xl_preset:::<?php echo implode(':::', $preset_vars); ?>" data-wrapper_target="g365-reveal-modal">Add to Event</a>
                <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                  <div class="relative">
                    <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div id="g365-reveal-modal" class="form_wrapper"></div>
                </div>
                <div class="clearfix"></div>
                <script type="text/javascript">
                  var g365_form_details = { "items" : { "Rosters":{ "name":"", "title":"Default", "type":"ro_ed", "items":{} }}, "wrapper_target" : "g365_form_options_anchor", "user_org": "<?php echo get_user_meta($current_user->ID, '_default_org', true); ?>", "admin_key": "<?php echo g365_make_admin_key(); ?>"
                  };
                </script>
                <div>
                  <?php
                  $item_string = '';
                  if( !empty($default_rosters) ) $item_string .= (':::' . implode(':::', $default_roster_ids));
                  ?>
                  <div id="g365_form_options_anchor" data-g365_type="ro_ed<?php echo ($item_string ?? ''); ?>"></div>
                </div>


                <?php
              } else {
                if( count($event_rosters) > 0 ) {
                  $preset_vars = array(
                    'event_id::' . $event_data->id,
                    'event_full_name::' . $event_data->name,
                    'org_id::' . $org_id,
                    'event_divisions:: ' . str_replace('"', "'", $event_data->divisions),
                    'rosters_enabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['enabled'])) . '}',
                    'rosters_disabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['disabled'])) . '}',
                  );
                ?>
                <a  class="button float-right medium small-small g365-edit-data" id="rosters_sl_<?php echo $ev_id; ?>" data-g365_type="rosters_sl" data-g365_init_pre="rosters_sl_preset:::<?php echo implode(':::', $preset_vars); ?>" data-wrapper_target="g365-reveal-modal">Add New Rosters</a>
                <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal>
                  <div class="relative">
                    <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div id="g365-reveal-modal" class="form_wrapper"></div>
                </div>
                <div class="clearfix"></div>
                <script type="text/javascript">
                  var g365_form_details = { "items" : { "Rosters":{ "name":"", "title":"<?php echo $event_data->name ; ?>", "type":"to_ed", "items":{} }}, "wrapper_target" : "g365_form_options_anchor", "user_org": "<?php echo $org_id; ?>", "admin_key": "<?php echo g365_make_admin_key(); ?>" };
                </script>
                <div>
                  <?php
                  $item_string = '';
                  if( !empty($event_roster_ids) ) $item_string = (':::' . implode(':::', $event_roster_ids));
                  ?>
                  <div id="g365_form_options_anchor" data-g365_type="to_ed<?php echo ($item_string ?? ''); ?>"></div>
                </div>
              </div>
            <?php } else {
                echo '<h2 clsss="no-margin-bottom">' . $event_data->name . '</h2>';
                echo '<h2>No rosters found.</h2><p>We couldn\'t find any rosters for this event.</p>';
            }
          }
        }
      }
      break;
		case 'admin_data_ros_old':
      //default per_pg
      $default_per_pg = 50;
      //attempt to retrieve vars
      $pg_no = filter_input( INPUT_GET, 'pg_no', FILTER_SANITIZE_NUMBER_INT );
      $per_pg = filter_input( INPUT_GET, 'per_pg', FILTER_SANITIZE_NUMBER_INT );
      //check vars, set boundaries
      if( empty($pg_no) ) $pg_no = 1;
      if( empty($per_pg) || $per_pg > 100 ) $per_pg = $default_per_pg;
      //get total records
      $pl_count = g365_count_rosters();
      $total_pages = ceil($pl_count/$per_pg);
      //limit $pg_no to max if over
      if( $pg_no > $total_pages ) $pg_no = $total_pages;
      //get player data
      $ros = g365_get_rosters( array('unlock' => true, 'pg_no' => $pg_no, 'per_pg' => $per_pg), false, true );
      //set variable brackets for pagnation
      $start_pages = ( $total_pages > 9 ) ? ((($pg_no - 5) < 1) ? 1 : $pg_no - 5) : 1;
      $end_pages = ( $total_pages > 9 ) ? ((($start_pages + 9) > $total_pages) ? $total_pages : $start_pages + 9) : $total_pages;
      ?>
      <h2>Recent Rosters Updates</h2>
      <small></small>
      <div class="grid-x grid-margin-x">
      <?php if( !is_string($ros) ) {
        echo '<div class="cell small-6">Page ' . $pg_no . '/' . $total_pages . '</div>';
        echo '<div class="cell small-6 text-right">Per Page: <input class="no-margin-bottom inline input-shrink" type="number" data-g365-admin-page-target="' . $g365_admin_url . '&per_pg=" data-g365-per-pg-og-val="' . $per_pg . '" value="' . $per_pg . '" onfocusout="if( $(this).attr(\'data-g365-per-pg-og-val\') !== $(this).val() ) document.location=$(this).attr(\'data-g365-admin-page-target\') + $(this).val();" /></div>';
        ?>
          <div class="cell small-12">
            <table class="edit-table">
              <thead>
                <tr>
                  <th>Club Team</th>
                  <th>Level</th>
                  <th>Division</th>
                  <th>Players</th>
                </tr>
              </thead>
              <tbody>
              <?php
              foreach( $ros[0] as $ros_dex => $ros_data ) {
                $roster_players = array();
                $team_veri = 'no-players';
                if( !empty($ros_data->players) ) {
                  $team_veri = 'verified-team';
                  $today = date("Y-m-d");
                  $ros_data->players = json_decode($ros_data->players);
                  if( $ros_data->players !== 'null' ) foreach( $ros_data->players as $pl_id => $pl_data ) {
                    $supplemental_cert_data = ($ros[1][$pl_id]->profile_img === null) ? '' : '<div class="cell small-12 medium-3"><img class="tournament-player-img profile_img" src="' . site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $ros[1][$pl_id]->profile_img . '" /></div>';
                    $supplemental_cert_data .= ($ros[1][$pl_id]->recard_img !== null || $ros[1][$pl_id]->bcert_img !== null) ? '<div class="cell small-12 medium-9">' : '';
                    $supplemental_cert_data .= ($ros[1][$pl_id]->recard_img === null) ? '' : '<img class="tournament-player-img" loading="lazy" onclick="$(this).toggleClass(\'huge\');" src="' . site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $ros[1][$pl_id]->recard_img . '" />';
                    $supplemental_cert_data .= ($ros[1][$pl_id]->bcert_img === null) ? '' : '<img class="tournament-player-img" loading="lazy" onclick="$(this).toggleClass(\'huge\');" src="' . site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $ros[1][$pl_id]->bcert_img . '" />';
                    $supplemental_cert_data .= ($ros[1][$pl_id]->recard_img !== null || $ros[1][$pl_id]->bcert_img !== null) ? '</div>' : '';
                    if( !empty($supplemental_cert_data) ) $supplemental_cert_data = ' <div class="inline add-button float-right" onclick="$(this).next().toggleClass(\'hide\');">+</div><div class="supplemental-data hide"><a class="close-button remove-button" onclick="$(this).parent().addClass(\'hide\');">X</a><div class="grid-x grid-margin-x"><h3 class="cell small-12">' . $ros[1][$pl_id]->name . '</h3>' . $supplemental_cert_data . '</div></div>';
                    if($ros[1][$pl_id]->verified > 1) {
                      $roster_players[] = '<tr class="tournament-players verified-player"><td class="jersey_num">' . $pl_data->j_num . '</td><td class="pl_name">' . $ros[1][$pl_id]->name . $supplemental_cert_data . '</td><td class="pl_grade">' . ((empty($ros[1][$pl_id]->grad_year)) ? '' : g365_class_to_grade($ros[1][$pl_id]->grad_year)) . '</td><td class="pl_birthday">' . ((empty($ros[1][$pl_id]->birthday)) ? '' : date('m/d/y', strtotime($ros[1][$pl_id]->birthday))) . '</td><td class="verification-form">Verified</td></tr>';
                    } else {
                      $roster_players[] = '<tr class="tournament-players unverified-player"><td class="jersey_num">' . $pl_data->j_num . '</td><td class="pl_name">' . $ros[1][$pl_id]->name . $supplemental_cert_data . '</td><td class="pl_grade">' . ((empty($ros[1][$pl_id]->grad_year)) ? '' : g365_class_to_grade($ros[1][$pl_id]->grad_year)) . '</td><td class="pl_birthday">' . ((empty($ros[1][$pl_id]->birthday)) ? '' : date('m/d/y', strtotime($ros[1][$pl_id]->birthday))) . '</td><td class="verification-form"><form class="g365_admin_verification_toggle_tournament"><input type="hidden" class="pl_id" name="pl_' . $ros[1][$pl_id]->id . '[id]" value="' . $ros[1][$pl_id]->id . '" /><input type="hidden" name="pl_' . $ros[1][$pl_id]->id . '[proc_type]" value="proc_data" /><input type="radio"  name="pl_' . $ros[1][$pl_id]->id . '[data][verified]" value="2" /> Yes | <input type="radio" name="pl_' . $ros[1][$pl_id]->id . '[data][verified]" value="1" checked /> No</form></td></tr>';
                      $team_veri = 'unverified-team';
                    }
                  }
                }
                echo '<tr class="team-row ' . $team_veri . '">';
                echo '<td>' . (( empty($ros_data->org_abbr) ) ? $ros_data->org_name : $ros_data->org_abbr . ' (' . $ros_data->org_name . ')') . ' ' . $ros_data->team_level . 'U' . ((empty($ros_data->team_name)) ? '' : ' ' . $ros_data->team_name) . '</td>';
                echo '<td>' . $ros_data->level . 'U' . '</td>';
                echo '<td>' . $ros_data->division . '</td>';
                echo '<td>' . (($team_veri === 'no-players') ? '' : '<table class="pl_block hide" cellspacing="0"><thead><tr><th>#</th><th>Name</th><th>Attend</th><th>Grade</th><th>Birthday</th><th>Verification</th></tr></thead><tbody>' . implode('', $roster_players) . '</tbody></table><div class="button no-margin" onclick="$(this).prev().toggleClass(\'hide\');">Toggle Players</div>') . '</td>';
                echo '</tr>';
              }
              ?>
              </tbody>
            </table>
          </div>
        <?php
          if( $total_pages > 1 ) { ?>
          <div class="cell small-12">
            <div class="button-group tiny-margin-top">
            <?php
            if( $start_pages > 1 ) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button"><<</a><a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . ($pg_no - 1) . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button"><</a>';
            foreach(range($start_pages, $end_pages)  as $i) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . $i . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button' . (($i == $pg_no) ? ' is-active' : '') . '">' . $i . '</a>';
            if( $total_pages > 10 ) echo '<input class="button inline input-shrink" type="number" data-g365-admin-page-target="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '&pg_no=" data-g365-per-pg-og-val="' . $pg_no . '" onfocusout="if( $(this).val() !== \'\' && $(this).attr(\'data-g365-per-pg-og-val\') !== $(this).val() ) document.location=$(this).attr(\'data-g365-admin-page-target\') + $(this).val();" placeholder="#" />';
            if( $end_pages < $total_pages ) echo '<a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . ($pg_no + 1) . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button">></a><a href="' . $g365_admin_url . '&ver_lvl=' . $ver_lvl . '&pg_no=' . $total_pages . (($per_pg == $default_per_pg) ? '' : '&per_pg=' . $per_pg ) . '" class="button">>></a>';
            ?>
            </div>
          </div>
          <?php
          }
        } else {
        ?>
        <div class="cell small-12 medium-8 large-6">
          <p>No players for this search.</p>
          <p><?php echo $ros; ?></p>
        </div>
        <?php
        }
        ?>
      </div>
      <?php
			break;
    case 'admin_data_pl_ev':
    $event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
    if( empty($event_id) ) : ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <label for="event_link_selector">Event</label>
          <div class="form-holder">
            <input type="text" class="g365_livesearch_input" id="event_link_selector" data-g365_type="event_admin_stat" placeholder="Enter Event Name" autocomplete="off">
          </div>
        </div>
      </div>
    <?php
    else :
    $event_stats = g365_get_stats( null, intval( $event_id ), '0-1', 'stats.enabled DESC, player.first_name, player.last_name', null, 'camps' );
//     print_r($event_stats);
    $event_info = g365_get_event_data( $event_id, true);
    global $wpdb;
    $wpdb_stats = $wpdb->g365_stats;

//     $event_stats = $wpdb->get_results(
//       "SELECT
//         JSON_UNQUOTE(JSON_EXTRACT(trends, '$.ss_event_participated')) AS event_participated
//       FROM 
//           $wpdb_stats
//       WHERE JSON_UNQUOTE(JSON_EXTRACT(trends, '$.ss_event_participated')) = $event_id;");
//       print_r($event_id);
    if( !empty($event_stats) && is_array($event_stats) ) {
        $admin_string = '';
        if( current_user_can('administrator') ) {
          $admin_string = ', "g365_admin" : "true"';
          $current_user = wp_get_current_user();
          $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
          $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
        }
//           <div class="grid-x grid-margin-x">
//             <div class="cell small-12 medium-8 large-6 medium-offset-2 large-offset-3">
//             </div>
//           </div>

      echo '<h2>' . reset($event_stats)->event_name . '</h2>'; ?>
<!--         <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="player_event_admin"></div>
      </div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script> -->
      <?php 
        if( intval($event_id) === 504 ) { ?>
      <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="player_event_admin"></div>
      </div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

      <div class="grid-x grid-margin-x">
        <div class="cell small-12">
          <table class="edit-table">
            <thead>
              <tr>
                <th>Player</th>
                <th>Action</th>
                <th>Age</th>
                <th>Birthdate</th>
                <th>Grade</th>
                <th>Class</th>
                <th>Purchase History</th>
              </tr>
            </thead>
            <tbody>
            <?php
            //separate sections for disabled and active records
            $disabled_switch = false;
            //loop through all the records and write them
            foreach( $event_stats as $stat_dex => $stat_data ) {
              //if we don't have a name then the record is probably a glitch, don't show it
              if( empty($stat_data->name) ) continue;
              //if this is the loop we switch to disabled records make the appropriate header and separator
              if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                $disabled_switch = true;
              }
              //parse the passport purchase history
              $stat_data->stats = json_decode($stat_data->stats);
              //figure out the disabled status
              $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
              //compile the purchase history
              $purchase_history = array();
              if( !empty($stat_data->stats) ) {
                foreach($stat_data->stats as $history_type => $type_data){
                  $purchase_history_sub = array('<strong>' . ucfirst($history_type) . '</strong>');
                  foreach($type_data as $type_key => $key_data){
                    if( $history_type === 'seasons' ) {
                      $purchase_history_line_item = '<strong>' . $type_key . '</strong>:';
                      $purchase_history_line_item .= ' <em class="text-uppercase dark-gray tiny-margin-left">Time</em>: ' . (($key_data->paid) ? date('m/d/y H:i:s', strtotime($key_data->paid)) : '--');
                      $purchase_history_line_item .= ' <em class="text-uppercase dark-gray tiny-margin-left">Order</em>: ' . (($key_data->order_id) ? '<a class="text-underline bold" href="' . site_url() . '/wp-admin/post.php?post=' . $key_data->order_id . '&action=edit">' . $key_data->order_id . '</a>' : '--');
                      $purchase_history_line_item .= ' <em class="text-uppercase dark-gray tiny-margin-left">Amount</em>: ' . (($key_data->amount) ? '$' . $key_data->amount : '--');
                    } else {
                      global $wpdb;
                      $purchase_history_line_item = '<strong>' . $wpdb->get_var( "SELECT name FROM $wpdb->g365_events WHERE id = $type_key;" ) . '</strong>:';
                      $purchase_history_line_item .= ' <em class="text-uppercase dark-gray tiny-margin-left">Time</em>: ' . (($key_data->paid) ? date('m/d/y H:i:s', strtotime($key_data->paid)) : '--');
                      $purchase_history_line_item .= ' <em class="text-uppercase dark-gray tiny-margin-left">Order</em>: ' . (($key_data->order_id) ? '<a class="text-underline bold" href="' . site_url() . '/wp-admin/post.php?post=' . $key_data->order_id . '&action=edit">' . $key_data->order_id . '</a>' : '--');
                      $purchase_history_line_item .= ' <em class="text-uppercase dark-gray tiny-margin-left">Amount</em>: ' . (($key_data->amount) ? '$' . $key_data->amount : '--');
                    }
                    $purchase_history_sub[] = $purchase_history_line_item;
                  }
                  $purchase_history[] = implode('<br>', $purchase_history_sub);
                }
              }
              echo "<tr$enabled>";
                echo '<td>' . $stat_data->name . '</td>';
                echo '<td><a class="button small tiny-margin g365-edit-data" id="pl_ev_' . $stat_data->id . '" data-g365_type="player_event_admin::' . $stat_data->id . '">Edit</a></td>';
                echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create(date("Y-m-d")))->format('%y')) . '</td>';
                echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
                echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
                echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
                echo '<td>' . ((empty($purchase_history)) ? '' : implode('<br>', $purchase_history)) . '</td>';
              echo '</tr>';
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php } else if(intval($event_id) >= 540 && intval($event_id) <= 549){ ?>
      <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="player_event_admin"></div>
      </div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

      <div class="grid-x grid-margin-x">
        <div class="cell small-12">
          <table class="edit-table">
            <thead>
              <tr>
                <th>Player</th>
                <th>Action</th>
                <th>Age</th>
                <th>Birthdate</th>
                <th>High School</th>
                <th>Grade</th>
                <th>GPA</th>
                <th>SAT</th>
                <th>ACT</th>
                <th>Position</th>
                <th>Class</th>
                <th>Jersey Size</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $disabled_switch = false;
            foreach( $event_stats as $stat_dex => $stat_data ) {
              if( empty($stat_data->name) ) continue;
              if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                $disabled_switch = true;
              }
              $stat_data->notes = json_decode($stat_data->notes);
              $today = date("Y-m-d");
              $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
              echo "<tr$enabled>";
                echo '<td>' . $stat_data->name . '</td>';
                echo '<td><a class="button small tiny-margin g365-edit-data" id="pl_ev_' . $stat_data->id . '" data-g365_type="player_event_admin::' . $stat_data->id . '">Edit</a></td>';
                echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create($today))->format('%y')) . '</td>';
                echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
//                   echo '<td>' . ((empty($stat_data->school_name)) ? '' : $stat_data->school_name) . '</td>';
                echo '<td>' . ((is_numeric($stat_data->player_school)) ? $stat_data->school_name : $stat_data->player_school) . '</td>';
                echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
                echo '<td>' . ((empty($stat_data->gpa)) ? '' : $stat_data->gpa) . '</td>';
                echo '<td>' . ((empty($stat_data->sat)) ? '' : $stat_data->sat) . '</td>';
                echo '<td>' . ((empty($stat_data->act)) ? '' : $stat_data->act) . '</td>';
                echo '<td>' . ((empty($stat_data->position_name)) ? '' : $stat_data->position_name) . '</td>';
                echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
                echo '<td>' . ((empty($stat_data->notes->jersey_size)) ? '' : $stat_data->notes->jersey_size) . '</td>';
//                   echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
              echo '</tr>';
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
          
        <?php } else if($event_info->org == 7164) { ?>
          <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                <div class="relative">
                  <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="g365_form_options_anchor" data-g365_type="hhh_player_event_admin"></div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "hhh_player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

          <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <table class="edit-table">
                <thead>
                  <tr>
                    <th>Player</th>
                    <th>Action</th>
                    <th>Age</th>
                    <th>Birthdate</th>
                    <th>Jersey Size</th>
                    <th>Grade</th>
                    <th>Class</th>
                    <th>Evaluation</th>
                    <th>Home display?</th>
                    <th>Strengths</th>
                    <th>Weaknesses</th>
                    <th>Stats</th>
<!--                     <th>Event Data</th> -->
                    <th>Video</th>
                    <th>Level</th>
                    <th>Offers</th>
                    <th>Player to Watch?</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $disabled_switch = false;
                foreach( $event_stats as $stat_dex => $stat_data ) {
  //                 print_r($stat_data);
                  if( empty($stat_data->name) ) continue;
                  if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                    echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                    $disabled_switch = true;
                  }
                  $pl_xtra_data = json_decode($stat_data->trends);
                  $stat_data->notes = json_decode($stat_data->notes);
                  $today = date("Y-m-d");
                  $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
                  echo "<tr$enabled>";
                    echo '<td>' . $stat_data->name . '</td>';
                    echo '<td><a class="button small tiny-margin g365-edit-data" id="pl_ev_' . $stat_data->id . '" data-g365_type="hhh_player_event_admin::' . $stat_data->id . '">Edit</a></td>';
                    echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create($today))->format('%y')) . '</td>';
                    echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
                    echo '<td>' . ((empty($stat_data->notes->jersey_size)) ? '' : $stat_data->notes->jersey_size) . '</td>';
                    echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
                    echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
                    echo '<td>' . ((empty($stat_data->evaluation)) ? '' : $stat_data->evaluation) . '</td>';
                    echo '<td>' . ((empty($pl_xtra_data->hhh_front_page)) ? 'False' : $pl_xtra_data->hhh_front_page) . '</td>';
                    echo '<td>' . ((empty($stat_data->strengths)) ? '' : $stat_data->strengths) . '</td>';
                    echo '<td>' . ((empty($stat_data->weaknesses)) ? '' : $stat_data->weaknesses) . '</td>';
                    echo '<td>' . ((empty($stat_data->stats)) ? '' : $stat_data->stats) . '</td>';
//                     echo '<td>' . ((empty($stat_data->trends)) ? '' : $stat_data->trends) . '</td>';
                    echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
                    echo '<td>' . ((empty($pl_xtra_data->level_division)) ? '' : $pl_xtra_data->level_division) . '</td>';
                    echo '<td>' . ((empty($pl_xtra_data->offers)) ? '' : $pl_xtra_data->offers) . '</td>';
                    echo '<td>' . ((empty($pl_xtra_data->player_to_watch)) ? '0' : $pl_xtra_data->player_to_watch) . '</td>';
                  echo '</tr>';
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>  
          
        <?php } else if($event_info->org == 8437) { ?>

          <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                <div class="relative">
                  <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="g365_form_options_anchor" data-g365_type="ss_player_event_admin"></div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "ss_player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

          <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <table class="edit-table">
                <thead>
                  <tr>
                    <th>Player</th>
                    <th>Action</th>
                    <th>Event</th>
                    <th>Age</th>
                    <th>Birthdate</th>
                    <th>Jersey Size</th>
                    <th>Grade</th>
                    <th>Class</th>
                    <th>Evaluation</th>
                    <th>Strengths</th>
                    <th>Weaknesses</th>
                    <th>Stats</th>
<!--                     <th>Event Data</th> -->
                    <th>Video</th>
<!--                     <th>Level</th>
                    <th>Offers</th>
                    <th>Player to Watch?</th> -->
                    <th>Front Page</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $disabled_switch = false;
                $player_stats = scope_get_stats( null, intval( $event_id ), '0-1', 'front_page DESC, event_time DESC', null, 'camps' ); 
//                 print_r($player_stats);
                foreach( $player_stats as $stat_dex => $stat_data ) {
  //                 print_r($stat_data);
                  if( empty($stat_data->name) ) continue;
                  if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                    echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                    $disabled_switch = true;
                  }
                  $pl_xtra_data = json_decode($stat_data->trends);
                  $stat_data->notes = json_decode($stat_data->notes);
                  $event_data = g365_get_event_data($stat_data->event);
                  $today = date("Y-m-d");
                  $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
                  echo "<tr$enabled>";
                    echo '<td>' . $stat_data->name . '</td>';
                    echo '<td><a class="button small tiny-margin g365-edit-data" id="pl_ev_' . $stat_data->id . '" data-g365_type="ss_player_event_admin::' . $stat_data->id . '">Edit</a></td>';
                    echo '<td>' . ((empty($event_data->name)) ? '' : $event_data->name) . '</td>';
                    echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create($today))->format('%y')) . '</td>';
                    echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
                    echo '<td>' . ((empty($stat_data->notes->jersey_size)) ? '' : $stat_data->notes->jersey_size) . '</td>';
                    echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
                    echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>'; 
                    if ($event_data->org == 7164 || $event_data->org == 2 || $event_data->org == 3 || $event_data->org == 7165 || $event_data->org == 3191 ) {
                        echo '<td>' . ((empty($pl_xtra_data->ss_evaluation)) ? '' : $pl_xtra_data->ss_evaluation) . '</td>';
                    } else {
                        echo '<td>' . ((empty($stat_data->evaluation)) ? '' : $stat_data->evaluation) . '</td>';
                    }
                    echo '<td>' . ((empty($stat_data->strengths)) ? '' : $stat_data->strengths) . '</td>';
                    echo '<td>' . ((empty($stat_data->weaknesses)) ? '' : $stat_data->weaknesses) . '</td>';
                    echo '<td>' . ((empty($stat_data->stats)) ? '' : $stat_data->stats) . '</td>';
//                     echo '<td>' . ((empty($stat_data->trends)) ? '' : $stat_data->trends) . '</td>';
                    echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
//                     echo '<td>' . ((empty($pl_xtra_data->level_division)) ? '' : $pl_xtra_data->level_division) . '</td>';
//                     echo '<td>' . ((empty($pl_xtra_data->offers)) ? '' : $pl_xtra_data->offers) . '</td>';
//                     echo '<td>' . ((empty($pl_xtra_data->player_to_watch)) ? '0' : $pl_xtra_data->player_to_watch) . '</td>';
                    echo '<td>' . ((empty($pl_xtra_data->front_page)) ? 'False' : $pl_xtra_data->front_page) . '</td>';
                  echo '</tr>';
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>  
          
        <?php } else { ?>
<!--       <p>
      hello  
      </p> -->
      <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="player_event_admin"></div>
      </div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

      <div class="grid-x grid-margin-x">
        <div class="cell small-12">
          <table class="edit-table">
            <thead>
              <tr>
                <th>Player</th>
                <th>Action</th>
                <th>Age</th>
                <th>Birthdate</th>
                <th>Jersey Size</th>
                <th>Grade</th>
                <th>Class</th>
                <th>Evaluation</th>
                <th>Strengths</th>
                <th>Weaknesses</th>
                <th>Stats</th>
                <th>Event Data</th>
                <th>Video</th>
<!--                   <th class="hide">Level</th>
                <th class="hide">Offers</th>
                <th class="hide">Player to Watch?</th> -->
              </tr>
            </thead>
            <tbody>
            <?php
            $disabled_switch = false;
            foreach( $event_stats as $stat_dex => $stat_data ) {
//                 print_r($stat_data);
              if( empty($stat_data->name) ) continue;
              if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                $disabled_switch = true;
              }
              $pl_xtra_data = json_decode($stat_data->trends);
              $stat_data->notes = json_decode($stat_data->notes);
              $today = date("Y-m-d");
              $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
              echo "<tr$enabled>";
                echo '<td>' . $stat_data->name . '</td>';
                echo '<td><a class="button small tiny-margin g365-edit-data" id="pl_ev_' . $stat_data->id . '" data-g365_type="player_event_admin::' . $stat_data->id . '">Edit</a></td>';
                echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create($today))->format('%y')) . '</td>';
                echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
                echo '<td>' . ((empty($stat_data->notes->jersey_size)) ? '' : $stat_data->notes->jersey_size) . '</td>';
                echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
                echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
                echo '<td>' . ((empty($stat_data->evaluation)) ? '' : $stat_data->evaluation) . '</td>';
                echo '<td>' . ((empty($stat_data->strengths)) ? '' : $stat_data->strengths) . '</td>';
                echo '<td>' . ((empty($stat_data->weaknesses)) ? '' : $stat_data->weaknesses) . '</td>';
                echo '<td>' . ((empty($stat_data->stats)) ? '' : $stat_data->stats) . '</td>';
                echo '<td>' . ((empty($stat_data->trends)) ? '' : $stat_data->trends) . '</td>';
                echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
//                   echo '<td class="hide">' . ((empty($pl_xtra_data->level_division)) ? '' : $pl_xtra_data->level_division) . '</td>';
//                   echo '<td class="hide">' . ((empty($pl_xtra_data->offers)) ? '' : $pl_xtra_data->offers) . '</td>';
//                   echo '<td class="hide">' . ((empty($pl_xtra_data->player_to_watch)) ? '0' : $pl_xtra_data->player_to_watch) . '</td>';
              echo '</tr>';
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php
        }
      } else {
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <p>No records found.</p>
          <a href="<?php echo $g365_sections[$g_action]['url']; ?>">Modify Search</a>
        </div>
      </div>
      <?php
      }
    endif;
    break;


		case 'admin_data_tournaments': // todo here.
      $event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
      if( empty($event_id) ) : ?>
        <div class="grid-x grid-margin-x">  
          <div class="cell small-12 medium-8 large-6">
            <label for="event_link_selector">Event</label>
            <div class="form-holder">
              <input type="text" class="g365_livesearch_input" id="event_link_selector" data-g365_type="event_admin_tournament" placeholder="Enter Event Name" autocomplete="off">
            </div>
          </div>
        </div>
      <?php
      else :
        //if we need to grab data from exposure, do it before we build the page
        if( isset( $_POST['export_games'] ) ) $save_count = save_exposure_game( $event_id );
        if( isset( $_POST['SCIBCA_export_games'] ) ) $save_count = save_SCIBCA_exposure_game( $event_id );
        //get the roster and event data for this id
        $event_rosters = g365_get_rosters(array('event_id' => $event_id, 'order_by_master' => 'roster.level ASC, CASE WHEN roster.division = \'Open\' THEN \'1\' WHEN roster.division = \'Gold\' THEN \'2\' WHEN roster.division = \'Silver\' THEN \'3\' WHEN roster.division = \'Bronze\' THEN \'4\' WHEN roster.division = \'Copper\' THEN \'5\' ELSE roster.division END ASC, orgs.name ASC'), false, true);
//         echo '<pre class="">';
//         print_r($event_rosters);
//         echo '</pre>';
//         echo('<br><br>');
        
        
        $event_info = g365_get_event_data( $event_id, true);
        //day list for taking attendance
        $event_days = '';
        if( !empty($event_info->dates) ) $event_days = g365_get_days_from_string( $event_info->dates );
        if( is_string($event_rosters[0]) ) {
          echo '<p>No rosters found for ' . $event_info->name . '.</p>'; ?>
          <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-8 large-6">
              <label for="event_link_selector">Event</label>
              <div class="form-holder">
                <input type="text" class="g365_livesearch_input" id="event_link_selector" data-g365_type="event_admin_tournament" placeholder="Enter Event Name" autocomplete="off">
              </div>
            </div>
          </div>
          <?php
        } else {
          echo '<h2>' . $event_info->name . '</h2>';
          $admin_string = '';
          if( current_user_can('administrator') ) {
            $admin_string = ', "g365_admin" : "true"';
            $current_user = wp_get_current_user();
            $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
            $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
          } ?>
          <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="tournament_roster_admin"></div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Tournament Manager" : {"name" : "Make changes to tournament manager", "title" : "", "type" : "tournament_roster_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>
          <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
               $(document).ready(function() {
                 $('.single-checkbox input[type="checkbox"]').on('click', function() {
                    var $this = $(this);
                    var $checkboxes = $this.closest('.single-checkbox').find('input[type="checkbox"]');
                    $checkboxes.not($this).prop('checked', false);
                  });
                });
             });
          </script>
          <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-4 large-2">
              <?php 
                //get the total players for this event
                $total_players = count($event_rosters[1]);
                $paid_players = 0;
                //loop through avaialbile player, see who has unlocked this event
                foreach( $event_rosters[1] as $pl_id => &$pl_data1 ) {
                  
                  //parse and set their status in relation to this event
                  $pl_data1->unlock_status = g365_player_unlock_status(null, $pl_data1->unlock_data, null, $event_info->eventtime);
//                   echo "<pre>"; print_r($pl_data1->unlock_status); echo "</pre>";
                  //if they are valid for this event, add them to the paid players
                  if( ($pl_data1->unlock_status[0] !== 'N/A') && ($pl_data1->unlock_status[0] !== 'Expired') ) $paid_players++;
                }
                echo "<strong>Paid Players:</strong> $paid_players/$total_players (" . round(($paid_players/$total_players)*100) . "%)<br/>";
                echo "<strong>Total # of Teams:</strong> ".count($event_rosters[0])."";
          
              ?>
            </div>
            <div class="cell small-12 medium-8 large-10">
              <div class ="expo_data_manager_actions align-right tiny-margin-bottom grid-container grid-x grid-margin-x">
                <form method="post" class="tiny-margin-right" id="exposure_form">
                  <?php if( !empty($save_count) ) echo 'Saved ' . $save_count . ' records.'; ?>
                  <?php if($event_info->org === '7474'): ?>
                    <input type="submit" name="SCIBCA_export_games" class="button-primary button-large" value="Pull SCIBCA Exposure Data" />
                  <?php else: ?>
                    <input type="submit" name="export_games" class="button-primary button-large" value="Pull Exposure Data" />
                  <?php endif; ?>
                </form> 
                <form id="g365-export-form" method="post" action="<?php echo site_url() . '/wp-content/plugins/g365-data-manager/export-data.php'; ?>"> 
                  <input name="get_info" type="hidden" id="get_info" value="exposure">
                  <input name="event_id" type="hidden" id="event_id" value="<?php echo $event_id; ?>">
                  <input type="submit" id="get-data-button" class="button-primary button-large" value="Download first import for Exposure">
                </form>
              </div>
            </div>
            <div class="cell small-12">
<!--               <img id="loading_spinner" src="https://grassroots365.com/wp-content/uploads/wide/homepage-2.jpg"> -->
              <table class="edit-table">
                <thead>
                  <tr>
                    <th><div class="small-margin-sides">Action</div></th>
                    <th>Division</th>
                    <th>Club Team</th>
                    <th>Level</th>
                    <th>Roster ID</th>
                    <th>Players</th>
                    <th>% Paid</th>
                    <th>% Verified</th>
                    <th>% Claimed</th>
                  </tr>
                </thead>
                <tbody>
                <?php
          
                
          
                foreach( $event_rosters[0] as $ros_dex => $ros_data ) {
                  $roster_players = array();
                  $team_veri = 'no-players';
                  if( !empty($ros_data->players) ) {
                    $team_veri = 'verified-team';
                    $today = date("Y-m-d");
                    $ros_data->players = json_decode($ros_data->players);
                    $prod_site_img = site_url( '/wp-content/uploads/player-profiles/', 'https' ); 
                    $prod_site_img = str_replace('dev.', '', $prod_site_img);
                    
                    
                    // Decode the players JSON string
//                     $ros_data->players = json_decode($ros_data->players, true);
//                     echo print_r($ros_data->players, true) . '<br><br>';
                    foreach( $ros_data->players as $pl_id => $pl_data ) {
                      
                        global $wpdb;
                        $query = $wpdb->prepare(
                            "SELECT notes
                             FROM $wpdb->g365_players
                             WHERE id = %d",
                            $pl_id
                        ); 
                        
                        $notes = $wpdb->get_var($query); 
                        $statusArray = json_decode($notes)->player_status->{$pl_id} ?? [];
                      
//                        echo '<pre class="">';
//                        print_r($event_rosters[1][$pl_id]); //players info. seems some players are missing //cronos working here
//                        echo '</pre>';
                      $profile_headshot = ($event_rosters[1][$pl_id]->profile_img === null) ? '' : '<img class="tournament-player-img profile_img" loading="lazy" src="' . $prod_site_img . '' . $event_rosters[1][$pl_id]->profile_img . '" />';
//                       $profile_headshot = "";
//                       $supplemental_cert_data .= ($event_rosters[1][$pl_id]->recard_img !== null || $event_rosters[1][$pl_id]->bcert_img !== null) ? '<div class="cell small-12 medium-9">' : '';
//                       $supplemental_cert_data .= ($event_rosters[1][$pl_id]->recard_img === null) ? '' : '<img class="tournament-player-img" loading="lazy" onclick="$(this).toggleClass(\'huge\');" src="' . site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $event_rosters[1][$pl_id]->recard_img . '" />';
//                       $supplemental_cert_data .= ($event_rosters[1][$pl_id]->bcert_img === null) ? '' : '<img class="tournament-player-img" loading="lazy" onclick="$(this).toggleClass(\'huge\');" src="' . site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $event_rosters[1][$pl_id]->bcert_img . '" />';
//                       $supplemental_cert_data .= ($event_rosters[1][$pl_id]->recard_img !== null || $event_rosters[1][$pl_id]->bcert_img !== null) ? '</div>' : '';
//                       if( !empty($supplemental_cert_data) ) $supplemental_cert_data = ' <div class="inline add-button float-right" onclick="$(this).next().toggleClass(\'hide\');">+</div><div class="supplemental-data hide"><a class="close-button remove-button" onclick="$(this).parent().addClass(\'hide\');">X</a><div class="grid-x grid-margin-x"><h3 class="cell small-12">' . $event_rosters[1][$pl_id]->name . '</h3>' . $supplemental_cert_data . '</div></div>';
                      $roster_labels = '';
                      //if the birth and lock, make is so.
                      if( isset($ros_data->division_selector_birth_lock) && !empty($event_rosters[1][$pl_id]->birthday) ) {
//                           $roster_labels .= intval($event_rosters[1][$pl_id]->grad_year);
                        //if birth lock is grerater in time than player, player is too old, find out if exception or violation
                        if( strtotime(explode("'", $ros_data->division_selector_birth_lock)[1]) > strtotime($event_rosters[1][$pl_id]->birthday) ) {
                          //in the right grade?
                          if( intval(explode("-", explode(" ", $ros_data->division_selector_class_lock)[1])[0]) < intval($event_rosters[1][$pl_id]->grad_year) ) {
                            $roster_labels .= '<span class="tag-label">Exception</span>';
                          } else {
                            $roster_labels .= '<span class="tag-label">Ineligible</span>';
                          } 
                        }
                      }
                      
//                       if($pl_id == 95217){
// //                       echo '<pre class="">';
// //                       print_r($event_rosters[1]); //players info. seems some players are missing //cronos working here
// //                       echo '</pre>';
//                       echo('<br><br>');
//                       echo("<script>console.log('CronoSSs dimension: " . print_r($pl_id) . " // " . print_r($pl_data) .  " // " . /*print_r($event_rosters[1]) .*/ " ');</script><br><br>");
//                       }
                      
                      // Debugging access data
//                       echo('<pre class="">' . $pl_id . ' ' . print_r($event_rosters[1][$pl_id]->access, true) . '</pre>');
                      //if the player is unclaimed
                      if( empty($event_rosters[1][$pl_id]->access) ) $roster_labels .= '<span class="tag-label">Unclaimed</span>';
                      //players profile subscription status
//                       echo "<pre>"; print_r($event_rosters[1][$pl_id]); echo "</pre>";
                      $player_sub_status = '<div class="white-border ghost-white-bg text-center"><span class="white-bg dark-gray tiny-header block">' . $event_rosters[1][$pl_id]->unlock_status[0] . '</span><small>' . $event_rosters[1][$pl_id]->unlock_status[1] . '</small></div>';
                      //players verified or not
                      $roster_build_string = '';
                      
                      //set the players classes
                      if($event_rosters[1][$pl_id]->verified > 1) {
                        $roster_build_string = '<tr class="tournament-players verified-player"><td>' . $profile_headshot . '</td>';
                      } else {
                        $roster_build_string = '<tr class="tournament-players unverified-player"><td>' . $profile_headshot . '</td>';
                        $team_veri = 'unverified-team';
                      }
                      //set the generic player data
                      $roster_build_string .= '<td class="jersey_num">' . $pl_data->j_num . '</td>';
                      $roster_build_string .= '<td class="pl_name">' . $event_rosters[1][$pl_id]->name . '</td>';
                      
                      //add the last cell to the table with the labels in it
                      $roster_build_string .= '<td class="relative single-checkbox">' . $roster_labels;
//                       $roster_build_string .= '<th>Director: ' . $ros_data->director_name . ' ' . $ros_data->director_phone . '</th>';
//                       $roster_build_string .= '<td class="pl_status">';
                      $roster_build_string .= '<form class="g365_admin_pl_status_toggle_tournament relative">';
//                       $roster_build_string .= '<input type="hidden" class="ros_id" name="id" value="' . $ros_data->id . '" />';
                      $roster_build_string .= '<input type="hidden" class="pl_id" name="pl_id" value="' . $event_rosters[1][$pl_id]->id . '" />';
//                       $roster_build_string .= '<input type="hidden" name="pl_' . $event_rosters[1][$pl_id]->id . '[proc_type]" value="proc_data" />';
//                       !empty($player_status->{$event_rosters[1][$pl_id->id]}) && is_array($player_status->{$event_rosters[1][$pl_id]->id}))
                      if($statusArray !== null) {
                        $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="status[]" value="banned"' . (in_array('banned', $statusArray) ? ' checked="checked" ' : '') . '/>Banned';
                        $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="status[]" value="suspended"' . (in_array('suspended', $statusArray) ? ' checked="checked" ' : '') . '/>Suspended';
                      }else {
                        $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="status[]" value="banned"/>Banned';
                        $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="status[]" value="suspended"/>Suspended';
                      }
                      $roster_build_string .= '</form></td>';
                      
                      //set the attendance field
                      if( is_array($event_days) ) {
                        //wrapper
                        $roster_build_string .= '<td class="pl_attend">';
                        //form mostly controlled by the admin js
                        $roster_build_string .= '<form class="g365_admin_attendance_toggle_tournament relative">';
//                         //minimum variables to get data processed by the server
//                         $roster_build_string .= '<input type="hidden" name="[proc_type]" value="proc_data" />';
                        //target roster to put the player data
                        $roster_build_string .= '<input type="hidden" class="ros_id" name="id" value="' . $ros_data->id . '" />';
                        $roster_build_string .= '<input type="hidden" class="pl_id" name="pl_id" value="' . $event_rosters[1][$pl_id]->id . '" />';
                        //build the days we need for this event and set existing attendance
                        foreach( $event_days as $day_dex => $the_day ) {
                          if( $day_dex !== 0 ) $roster_build_string .= '<br>';
                          //checkbox for the days
                          if( !empty($ros_data->attendance->{$event_rosters[1][$pl_id]->id}) && is_array($ros_data->attendance->{$event_rosters[1][$pl_id]->id}) )
                          {
                            $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="attend[]" value="' . $the_day . '" ' . ((in_array($the_day, $ros_data->attendance->{$event_rosters[1][$pl_id]->id})) ? 'checked="checked" ' : '') . '/> ' . $the_day;
//                             $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="attend[]" value="' . $the_day . '" ' . ((in_array($the_day, $ros_data->attendance->{$event_rosters[1][$pl_id]->id})) ? 'checked="checked" ' : '') . '/> ' . $the_day;
                          }else{
                            $roster_build_string .= '<input type="checkbox" class="no-margin-bottom" name="attend[]" value="' . $the_day . '" /> ' . $the_day;
                          }
                        }
                        $roster_build_string .= '</form></td>';
                      } else {
                        $roster_build_string .= '<td class="pl_attend">--</td>';
                      }
                      
                      $roster_build_string .= '<td class="pl_paid">' . $player_sub_status . '</td>';
                      $roster_build_string .= '<td class="pl_grade">' . ((empty($event_rosters[1][$pl_id]->grad_year)) ? '--' : g365_class_to_grade($event_rosters[1][$pl_id]->grad_year)) . '</td>';
                      $roster_build_string .= '<td class="pl_birthday">' . ((empty($event_rosters[1][$pl_id]->birthday)) ? '--' : date('m/d/y', strtotime($event_rosters[1][$pl_id]->birthday))) . '</td>';
                      
                      //set the verification form or notice
                      if($event_rosters[1][$pl_id]->verified > 1) {
                        $roster_build_string .= '<td class="verification-form">Verified</td></tr>';
                      } else {
                        $roster_build_string .= '<td class="verification-form relative"><form class="g365_admin_verification_toggle_tournament">';
                        $roster_build_string .= '<input type="hidden" class="pl_id" name="pl_' . $event_rosters[1][$pl_id]->id . '[id]" value="' . $event_rosters[1][$pl_id]->id . '" />';
                        $roster_build_string .= '<input type="hidden" name="pl_' . $event_rosters[1][$pl_id]->id . '[proc_type]" value="proc_data" />';
                        $roster_build_string .= '<input type="radio"  name="pl_' . $event_rosters[1][$pl_id]->id . '[data][verified]" value="2" /> Yes | <input type="radio" name="pl_' . $event_rosters[1][$pl_id]->id . '[data][verified]" value="1" checked /> No';
                        $roster_build_string .= '</form></td></tr>';
                      }
                      
                      $roster_players[] = $roster_build_string;
                      
                      
                    }
                  }
                  echo '<tr class="team-row ' . $team_veri . '">';
                  echo '<td><a class="button small tiny-margin g365-edit-data" id="to_ro_' . $ros_data->id . '" data-g365_type="tournament_roster_admin::' . $ros_data->id . '">Edit</a><a class="button small tiny-margin g365-remove-data site-close-button" id="to_ro_' . $ros_data->id . '" data-g365_type="tournament_roster_admin::' . $ros_data->id . '">Delete</a></td>';
                  echo '<td>' . g365_level_key($ros_data->level) . '</td>';
//                   echo '<td>' . (( empty($ros_data->org_abbr) ) ? $ros_data->org_name : $ros_data->org_abbr . ' (' . $ros_data->org_name . ')') . ' ' . g365_level_key($ros_data->team_level) . ((empty($ros_data->team_name)) ? '' : ' ' . $ros_data->team_name) . '</td>'; 
                  echo '<td>' . (( empty($ros_data->org_abbr) ) ? $ros_data->org_name : $ros_data->org_abbr ) . ' ' . g365_level_key($ros_data->team_level) . ((empty($ros_data->team_name)) ? '' : ' ' . $ros_data->team_name) . '</td>'; //testing here cj
                  echo '<td>' . $ros_data->division . '</td>';
                  echo '<td>' . $ros_data->id . '</td>';
                  if($ros_data->coach_name !== null && $ros_data->asst_name !== null) {
                  echo '<td>' . (($team_veri === 'no-players') ? '' : '<div class="button no-margin btn-togglePlayer">Toggle Players</div><table class="pl_block pl_block--tournament hide" cellspacing="0"><thead><tr><td colspan="9">Director: ' . $ros_data->director_name . ' ' . $ros_data->director_phone . '</td></tr><tr><td colspan="9">Coach: ' . $ros_data->coach_name . ' ' . $ros_data->coaches_phone . '<input  id="coach_present-'. $event_id . '-'. $ros_data->id .'" style="position: relative;top: -1px;left: 7px;margin: 0 0px 0 18px;scale: 0.7;" type="checkbox"><label for="coach_present-'. $event_id . '-'. $ros_data->id .'">Coach is present</label></td></tr><tr><td colspan="9">Assistant Coach: ' . $ros_data->asst_name . '</td></tr><tr><th></th><th>#</th><th>Name</th><th>Status</th><th>Attend</th><th>Paid</th><th>Grade</th><th>Birthday</th><th>Verification</th></tr></thead><tbody>' . implode('', $roster_players) . '</tbody></table>
                  <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
                  <script type="text/javascript">
                    // module for 3 day persistence of the checkbox "coach present" based on coach(name or ID)-team(ID)-event(ID?)
                    // FE storage only.
                    document.addEventListener("DOMContentLoaded", function() {
                      var key = `coach_present-'. $event_id . '-'. $ros_data->id .'`;
                      var $coachCheckbox = $(`#${key}`);
                      
                      // Check how old it is just the presence of the key means "checked" and value is the actual age it was saved.
                      var age = localStorage.getItem(key);
                      var maxAge = 3 * 24 * 60 * 60 * 1000;
                      
                      if(age){
                        if(age < Date.now() - maxAge){
                            // too old cleanup
                            localStorage.removeItem(key);
                        }else{
                            $coachCheckbox.prop("checked", true);
                        }
                      }

                      // Save the checkbox state in localStorage whenever it is changed
                      $coachCheckbox.on("change", function() {
                        var isChecked = $(this).prop("checked");
                        isChecked ? localStorage.setItem(key, Date.now()) : localStorage.removeItem(key);
                      });
                    });
                  </script>') . '</td>';
                  } else if($ros_data->coach_name !== null && $ros_data->asst_name == null) {
                    echo '<td>' . (($team_veri === 'no-players') ? '' : '<div class="button no-margin btn-togglePlayer">Toggle Players</div><table class="pl_block pl_block--tournament hide" cellspacing="0"><thead><tr><td colspan="9">Director: ' . $ros_data->director_name . ' ' . $ros_data->director_phone . '</td></tr><tr><td colspan="9">Coach: ' . $ros_data->coach_name . ' ' . $ros_data->coaches_phone . '<input  id="coach_present-'. $event_id . '-'. $ros_data->id .'" style="position: relative;top: -1px;left: 7px;margin: 0 0px 0 18px;scale: 0.7;" type="checkbox"><label for="coach_present-'. $event_id . '-'. $ros_data->id .'">Coach is present</label></td></tr><tr><th></th><th>#</th><th>Name</th><th>Status</th><th>Attend</th><th>Paid</th><th>Grade</th><th>Birthday</th><th>Verification</th></tr></thead><tbody>' . implode('', $roster_players) . '</tbody></table>
                  <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
                  <script type="text/javascript">
                    // module for 3 day persistence of the checkbox "coach present" based on coach(name or ID)-team(ID)-event(ID?)
                    // FE storage only.
                    document.addEventListener("DOMContentLoaded", function() {
                      var key = `coach_present-'. $event_id . '-'. $ros_data->id .'`;
                      var $coachCheckbox = $(`#${key}`);
                      
                      // Check how old it is just the presence of the key means "checked" and value is the actual age it was saved.
                      var age = localStorage.getItem(key);
                      var maxAge = 3 * 24 * 60 * 60 * 1000;
                      
                      if(age){
                        if(age < Date.now() - maxAge){
                            // too old cleanup
                            localStorage.removeItem(key);
                        }else{
                            $coachCheckbox.prop("checked", true);
                        }
                      }

                      // Save the checkbox state in localStorage whenever it is changed
                      $coachCheckbox.on("change", function() {
                        var isChecked = $(this).prop("checked");
                        isChecked ? localStorage.setItem(key, Date.now()) : localStorage.removeItem(key);
                      });
                    });
                  </script>') . '</td>';
                  } else {
                    echo '<td>' . (($team_veri === 'no-players') ? '' : '<div class="button no-margin btn-togglePlayer">Toggle Players</div><table class="pl_block pl_block--tournament hide" cellspacing="0"><thead><tr><td colspan="9">Director: ' . $ros_data->director_name . ' ' . $ros_data->director_phone . '</td></tr><tr><th></th><th>#</th><th>Name</th><th>Status</th><th>Attend</th><th>Paid</th><th>Grade</th><th>Birthday</th><th>Verification</th></tr></thead><tbody>' . implode('', $roster_players) . '</tbody></table>') . '</td>';
                  }
                  echo '<td class="player-paid"></td>';
                  echo '<td class="player-verified"></td>';
                  echo '<td class="player-claimed"></td>';
                  echo '</tr>';
                }
          
                // Check after the loop
//                 if (isset($event_rosters[1][95217])) {
//                     echo '<pre class="final-data">Data for 95217 after loop:';
//                     print_r($event_rosters[1][95217]);
//                     echo '</pre>';
//                 }
                ?>
                </tbody>
              </table>
            </div>
          </div>
          <script type="text/javascript">
            //Calculate % of paid players per team
            document.addEventListener("DOMContentLoaded", function(){
                var allPaidAmt = document.querySelectorAll('.player-paid');
                var allVerifiedAmt = document.querySelectorAll('.player-verified');
                var allClaimedAmt = document.querySelectorAll('.player-claimed');

                allPaidAmt.forEach(function(cell) {
                    // check to see if player roster is available using toggle players
                    if(cell.previousSibling.innerText.length === 0) {
                       return;
                    } else {
                        var paidCount = 0;
                        var totalCount = 0;
                        var percentage = 0;

                        var playerStatus = cell.previousSibling.children[1].querySelectorAll('.pl_paid span');
                        playerStatus.forEach(function(status){
                            if( ((status.innerText !== 'N/A') && (status.innerText !== 'Expired')) && status.innerText !== '' ) {
                            paidCount++;
                            }
                            totalCount++;
                        }) 
                        percentage = Math.round((paidCount / totalCount) * 100);

                        cell.innerText = paidCount + '/' + totalCount + ' (' + percentage + '%)';
                    }
                 });


                 allVerifiedAmt.forEach(function(cell){
                    // check to see if player roster is available using toggle players
                    if(cell.previousSibling.previousSibling.innerText.length === 0) {
                       return;
                    } else {
                        var verifiedCount = 0;
                        var totalCount = 0;
                        var percentage = 0;

                        var playerStatus = cell.previousSibling.previousSibling.children[1].querySelectorAll('.verification-form');
                        playerStatus.forEach(function(status){
                            if(status.innerText === 'Verified') {
                           verifiedCount++;
                            }
                            totalCount++;
                        }) 
                        percentage = Math.round((verifiedCount / totalCount) * 100);

                        cell.innerText =verifiedCount + '/' + totalCount + ' (' + percentage + '%)';
                    }
                 });

                 allClaimedAmt.forEach(function(cell){
                    // check to see if player roster is available using toggle players
                    if(cell.previousSibling.previousSibling.previousSibling.innerText.length === 0) {
                       return;
                    } else {
                        var claimedCount = 0;
                        var totalCount = 0;
                        var percentage = 0;

                        var playerStatus = cell.previousSibling.previousSibling.previousSibling.children[1].querySelectorAll('[class="relative"]');

            
                        playerStatus.forEach(function(status){
                            var labels = status.querySelectorAll('.tag-label');
                            var hasClaimedTag = false;
                            

                            // check for multiple tags, flag true if there is unclaimed in that list
                            labels.forEach(function(label) {
                                if(label.innerText === 'Unclaimed') {
                                    hasClaimedTag = true;
                                }
                            })

                            // if only one tag is 'unclaimed', or multiple tags and one includes 'unclaimed', skip claim count but add to total
                            if(status.innerText === 'Unclaimed' || hasClaimedTag == true) {
                                totalCount++;
                                return;
                            } else {
                                claimedCount++;
                                totalCount++;
                            }
                        }) 
                        percentage = Math.round((claimedCount / totalCount) * 100);

                        cell.innerText = claimedCount + '/' + totalCount + ' (' + percentage + '%)';
                    }
                 });
              
              var playerNamesElements = Array.from(document.querySelectorAll('.pl_name'));
              var playerNamesText = Array.from(document.querySelectorAll('.pl_name')).map(x => x.innerText);
              
             var alreadySeen = {};
             var duplicateNames = [];
              
              playerNamesText.forEach(function(str) {
                if (alreadySeen[str])
                  duplicateNames.push(str);
                else
                  alreadySeen[str] = true;
              });
              
              
              playerNamesElements.forEach(function(element) {
                var outerLoopText = element.innerText;
                for(var i = 0; i < duplicateNames.length; i++) {
                  if((duplicateNames[i] === outerLoopText) && (outerLoopText !== '' )  && (outerLoopText !== 'Home Player Zero' )  && (outerLoopText !== 'Away Player Zero' )) {
                    element.classList.add('pl_name--duplicate');
//                     element.setAttribute('data-other-teams', element.parentElement.parentElement.parentElement.parentElement.previousSibling.previousSibling.previousSibling.innerText);
//                     console.log();
                  }
                }
              })
              
               var duplicatePlayers = document.querySelectorAll('.pl_name--duplicate');
              
               duplicatePlayers.forEach(function(player) {
                 var span = document.createElement('span');
                 span.classList.add('duplicate-tooltip');
                 player.appendChild(span);
                 var name = player.firstChild.textContent;
                 var playerTeam = player.parentElement.parentElement.parentElement.parentElement.previousSibling.previousSibling.previousSibling.innerText;
                 
                 duplicatePlayers.forEach(function(otherPlayer) {
                   var otherTeam = otherPlayer.parentElement.parentElement.parentElement.parentElement.previousSibling.previousSibling.previousSibling.innerText;
//                    check player name match
                   if(name == otherPlayer.firstChild.textContent) {
                     
//                    check if its the same team name
                      if(playerTeam !== otherTeam) {
                        player.children[0].innerHTML = otherPlayer.parentElement.parentElement.parentElement.parentElement.previousSibling.previousSibling.previousSibling.innerText;
                      } 
                     
                   } 
                 })
               })
              
            });
          </script>
        <?php
        }
      endif;
      break;
            


		case 'admin_export_data':
			g365_export();
			break;
		case 'admin_data_stats':
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats":{"name":"Make changes to player event stats","title":"","type":"player_event","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};</script>
          <div>
            <div id="g365_form_options_anchor" data-g365_type="player_event"></div>
          </div>
        </div>
      </div>
      <?php
			break;
    case 'admin_data_stats_ss':
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats":{"name":"Make changes to player event stats","title":"","type":"ss_player_event","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};</script>
          <div>
            <div id="g365_form_options_anchor" data-g365_type="ss_player_event"></div>
          </div>
        </div>
      </div>
      <?php
			break;
		case 'admin_data_clubs':
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Clubs":{"name":"Make changes to clubs","title":"","type":"club_names_admin","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};</script>
          <div class="form-holder">
            <div id="g365_form_options_anchor" data-g365_type="club_names_admin"></div>
          </div>
        </div>
      </div>
      <?php
			break;
		case 'admin_data_rosters':
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Rosters":{"name":"Make changes to team roster","title":"","type":"rosters_teams_admin","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};</script>
          <div class="form-holder">
            <div id="g365_form_options_anchor" data-g365_type="rosters_teams_admin"></div>
          </div>
        </div>
      </div>
      <?php
			break;
		case 'admin_data_awards': 
      ?>
     <link rel="stylesheet" href="/wp-content/plugins/g365-data-manager/css/automate_awards.css"></link>
      <form class="input-group-label" id="awards" method="post">
        <label class="input-group-label" for="start_date" name="start_date">Start Date:</label>
        <input type="date" class='input-group-field' id="start_date" name="start_date" data-g365_type="" placeholder="mm/dd/yyyy" value="<?php echo $_POST['start_date']; ?>" autofocus>
        <label class="input-group-label" for="end_date" name="end_date">End Date:</label>
        <input type="date" class='input-group-field' id="end_date" name="end_date" data-g365_type="" placeholder="mm/dd/yyyy" value="<?php echo $_POST['end_date']; ?>" autofocus>
        <button class="button-primary button-large" id="date-submit" type="submit">Submit</button>
      </form>

      <h2>Awards</h2>   

      <?php
      mk_g365_get_event_date($_POST['start_date'], $_POST['end_date']);
			break;
    case 'admin_g365_badge_data': g365_dir_render('badges', 'admin-badges', $player_id, []);
    break;
    case 'admin_feature_api': g365_dir_render('api', 'admin-console', $player_id, []);
    break;
		case 'admin_badge_data':
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
					<?php
					//get the results from the function
					$result = null;
						print_r($_POST); //g365_achievement_reconciler( $player_list, true );

					if( $result == null ) { ?>
						<h3>Please select some players.<h3>
						<div class="grid-x grid-margin-x">
							<div class="cell small-12 medium-8 large-6">
								<script type="text/javascript">var g365_form_details = {"items" : {"Manage Players":{"name":"Make changes to players","title":"","type":"player_award","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};</script>
							<div>
							<div id="g365_form_options_anchor" data-g365_type="player_award"></div>
							</div>
							</div>
						</div>
						<?php
					} else {
						echo '<h3>Results</h3>';
						//badge_type constants
						$process_key = array(
							'pl' => "Player",
							'co' => "Coach",
							'ct' => "Club Team",
							'og' => "Club",
							'aw' => "Award"
						);
						foreach( $result as $type => $data ) {
							echo '<h4>' . $process_key[$type] . '</h4>';
							foreach( $data as $pl_id => $aw_data ) {
								echo '<p>' . $pl_id . ' : ';
								foreach( $aw_data as $aw_id => $outcome) {
									echo $aw_id . '</p>';
									foreach( $outcome['result'] as $dex => $result ) {
										echo '<p>' . $result . '</p>';
									}
								}
							}
						}
					}
					?>
        </div>
      </div>
      <?php
			break;
    case 'admin_claim_data':
      
//       $command = filter_input( INPUT_GET, 'fix_it', FILTER_SANITIZE_NUMBER_INT );
//       if( !empty($command) ) {
//         global $wpdb;
//         $wpdb_players = $wpdb->g365_players;
//         $result = array();
//         $records_to_fix = $wpdb->get_results( "SELECT id, first_name, last_name, state, city FROM $wpdb_players WHERE nickname IS NULL;" );
//         foreach($records_to_fix as $dex => &$data) {
//           $data->nickname = g365_process_data_point( 'nickname', $data->first_name . '-' . $data->last_name);
//           $result[$data->id] = $wpdb->query( "UPDATE $wpdb_players SET nickname = '$data->nickname' WHERE id = $data->id;" );
//           if( $result[$data->id]  === false && !empty($data->state)) {
//             $data->nickname = g365_process_data_point( 'nickname', $data->first_name . '-' . $data->last_name . '-' . $data->state);
//             $result[$data->id] = $wpdb->query( "UPDATE $wpdb_players SET nickname = '$data->nickname' WHERE id = $data->id;" );
//           }
//           if( $result[$data->id]  === false ) {
//             $result[$data->id] = array('name' => $data->first_name . ' ' . $data->last_name, 'id' => $data->id, 'result' => g365_output_db_error('Database update error.'), 'query' => "UPDATE $wpdb_players SET nickname = '$data->nickname' WHERE id = $data->id;");
//           } else {
//             $result[$data->id] = array('name' => $data->first_name . ' ' . $data->last_name, 'id' => $data->id, 'result' => $result[$data->id]);
//           }
//         }
//         echo '<pre>';
//         print_r( $result );
//         echo '</pre>';
//       }
//       echo '<a href="/wp-admin/admin.php?page=admin_claim_data&fix_it=1">Fix it</a>';
      
      
      $admin_string = '';
      if( current_user_can('administrator') ) {
        $admin_string = ', "g365_admin" : "true"';
        $current_user = wp_get_current_user();
        $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
        $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
      } ?>
        <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
              <div class="relative">
                <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
              </div>
              <div id="g365_form_options_anchor" data-g365_type="claiming"></div>
        </div>
        <script type="text/javascript">var g365_form_details = {"items" : {"Claim Manager" : {"name" : "Administer Claims", "title" : "", "type" : "claiming", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>
        <h1>Claim Request Status</h1>
        <a class="button no-margin-bottom" onclick="$(this).next().slideToggle();">Start New Claim</a>
        <div class="callout" style="display:none;">
          <ul class="tabs" data-tabs id="claim_start">
            <li class="tabs-title is-active"><a data-tabs-target="claim_player" href="#claim_player" aria-selected="true">Player</a></li>
            <li class="tabs-title"><a data-tabs-target="claim_club" href="#claim_club">Club</a></li>
          </ul>
          <div class="tabs-content" data-tabs-content="claim_start">
            <div class="tabs-panel is-active" id="claim_player">
              <form method="POST">
                <div class="tiny-margin-bottom tiny-padding no-input-margin">
                  <label for="pl_name" class="tiny-margin-top louder">Player Name <span class="req">*</span></label>
                  <input type="hidden" id="pl_id" name="pl_id" required>
                  <input type="text" id="pl_name" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="player_names_admin" data-ls_target="pl_id" data-g365_form_dest="player_add" placeholder="Enter Player Name">
                </div>
                <input type="submit" class="button" value="Initiallize Claim">
              </form>
            </div>
            <div class="tabs-panel" id="claim_club">
              <form method="POST">
                <div class="tiny-margin-bottom tiny-padding no-input-margin">
                  <label for="og_name" class="tiny-margin-top louder">Club Name <span class="req">*</span></label>
                  <input type="hidden" id="og_id" name="og_id" required>
                  <input type="text" id="og_name" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="club_names_admin" data-ls_target="og_id" data-g365_form_dest="org_add" placeholder="Enter Club Name">
                </div>
                <input type="submit" class="button" value="Initiallize Claim">
              </form>
            </div>
          </div>
        </div>
        <?php
          $claim_type = ( !empty($_GET['cl_type']) ) ? intval( $_GET['cl_type'] ) : 0;
          $claim_records = g365_get_claims(null, 0);
//       echo '<pre>';
//       print_r($claim_records);
//       echo '</pre>';
          if( gettype($claim_records) === 'string' ) {
            echo '<p class="callout">' . $claim_records . '</p>';
          } else {
            ?>
        <div class="large-margin-top">
          <h3>Players</h3>
          <table class="edit-table tiny-margin-top">
            <thead>
              <tr>
                <th>Index</th>
                <th>Player Name</th>
                <th>Site</th>
                <th>Requester email</th>
                <th>Status</th>
                <th>Requester Name</th>
                <th>Requester Phone</th>
                <th>Requester Relation</th>
                <th>Requester Player Birthday Input</th>
                <th>Player Birthday (database)</th>
                <th>Birthday Match</th>
                <th>Approve</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $default_message = '<tr><td colspan="6"><strong>No players to approve now.</strong></td></tr>';
              foreach($claim_records as $claim_record){
//                 print_r($claim_record);
                if( $claim_record->type != 1 ) continue;
                $default_message = false;
                $req_xtra_data = json_decode($claim_record->owner_data);
                if( $req_xtra_data->birthday != null && $req_xtra_data->birthday === $claim_record->birthday){
                  $match = '<td style="color: #00FF00"> Match </td>';
                }else{
                  $match = '<td style="color: #FF0000"> Don\'t Match </td>';
                }
            ?>
              <tr>
                <td><?php echo $claim_record->id ?></td>
                <td><?php echo $claim_record->name ?></td>
                <td><?php echo $claim_record->site_key ?></td>
                <td><?php echo $claim_record->email ?></td>
                <td><?php echo ( $claim_record->status == 1 ) ? "Pending" : 'Authorized' ?></td>
                <td><?php echo $req_xtra_data->name ?></td>
                <td><?php echo $req_xtra_data->phone ?></td>
                <td><?php echo $req_xtra_data->relation ?></td>
                <td><?php echo $req_xtra_data->birthday ?></td>
                <td><?php echo $claim_record->birthday ?></td>
                <?php echo $match;   ?>
                <td><a class="button small tiny-margin g365-edit-data" data-g365_type="claiming::<?php echo $claim_record->id  ?>">Approve</a></td>
                <td><a class="button small tiny-margin g365-remove-data" data-g365_type="claiming_delete::<?php echo $claim_record->id  ?>">Delete</a></td>
              </tr>
            <?php
              }
              if( $default_message !== false ) echo $default_message;
            ?>
            </tbody>
          </table>
        </div>
        <div class="">
          <h3>Clubs</h3>
          <table class="edit-table tiny-margin-top">
            <thead>
              <tr>
                <th>Index</th>
                <th>Player Name</th>
                <th>Site</th>
                <th>Requester email</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $default_message = '<tr><td colspan="6"><strong>No clubs to approve now.</strong></td></tr>';
              foreach($claim_records as $claim_record){
                if( $claim_record->type != 2 ) continue;
                $default_message = false;
            ?>
              <tr>
                <td><?php echo $claim_record->id ?></td>
                <td><?php echo $claim_record->name ?></td>
                <td><?php echo $claim_record->site_key ?></td>
                <td><?php echo $claim_record->email ?></td>
                <td><?php echo ( $claim_record->status == 1 ) ? "Pending" : 'Authorized' ?></td>
                <td><a class="button small tiny-margin g365-edit-data" data-g365_type="claiming::<?php echo $claim_record->id  ?>">Approve</a></td>
              </tr>
            <?php
              }
              if( $default_message !== false ) echo $default_message;
            ?>
            </tbody>
          </table>
        </div>
          <?php
          }
      break;
    case 'admin_stat_data':
      g365_dir_render('admin-stat', 'admin-top-player-search', null, $arg = null);
      break;
		case 'admin_data_players':
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <script type="text/javascript">
            var g365_form_details = {"items" : {"Manage Players":{"name":"Make changes to players","title":"","type":"player_names_admin","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};
          </script>
<!--           <script 
            async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC83wbHkNlC0B_Wm-aQ17Qemz3T2Y35Tr0&loading=async&libraries=places"> 
//             &callback=initMap
          </script> -->
          <script>
            
//             (function() {
//               var script = document.createElement('script');
//               script.async = true;
//               script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC83wbHkNlC0B_Wm-aQ17Qemz3T2Y35Tr0&loading=async&libraries=places';
//               // If you have a callback function, you can uncomment the next line and replace 'initMap' with your callback function name
//               // script.src += '&callback=initMap';
//               document.head.appendChild(script);
//             })();
//             var autocomplete;
//             var address1Field;

//             window.onload = function () {
//               $(document).on('focus', 'input[placeholder="123 Common St."]',function(event) {
//                 var initKey = 'map_initialised';
//                 if(!event.target.attributes[initKey]){
//                   event.target.setAttribute(initKey, 'true');
//                   initAutocomplete(event.target);
//                 }
//               });
//             }

//             function initAutocomplete(inputField) {
//               address1Field = inputField;
//               autocomplete = new google.maps.places.Autocomplete(inputField, {
//                 componentRestrictions: { country: ["us", "ca"] },
//                 fields: ["address_components", "geometry"],
//                 types: ["address"],
//               });
//               autocomplete.addListener("place_changed", fillInAddress);
//             }

//             function fillInAddress() {
//               const place = autocomplete.getPlace();
//               let address1 = "";
//               let postcode = "";

//               for(const component of place.address_components) {

//                 const componentType = component.types[0];

//                 switch(componentType) {
//                   case "street_number": {
//                     address1 = `${component.long_name} ${address1}`;
//                     break;
//                   }
//                   case "route": {
//                     address1 += component.short_name;
//                     break;
//                   }
//                   case "postal_code": {
//                     postcode += `${component.long_name}${postcode}`;
//                     break;
//                   }
//                   case "postal_code_suffix": {
//                     postcode += `-${component.long_name}`;
//                     break;
//                   }
//                   case "locality":
//                     $('input[placeholder="Anytown"').toArray()[0].value = component.long_name;
//                     break;
//                   case "administrative_area_level_1": {
//                     $('#search_player_names_admin_player_names_0_state').toArray()[0].value = component.short_name;
//                     break;
//                   }
//                   case "country":
//                     $('input[placeholder="Any Country"').toArray()[0].value = component.long_name;
//                     break;
//                 }
//               }
//               $('input[placeholder="12345"').toArray()[0].value = postcode;
//               address1Field.value = address1;
//           }
          </script>
          <div>
            <div id="g365_form_options_anchor" data-g365_type="player_names_admin"></div>
            <div class="relative">
              <span class="search-mag fi-magnifying-glass"></span>
              <input type="hidden" class='search-hero g365_livesearch_input' data-g365_type="event_profiles_admin" placeholder="Enter Event Name" autocomplete="off" autofocus>
            </div>
          </div>
        </div>
      </div>
      <?php
			break;
		case 'admin_data_settings':
			$g365_admin_settings = array(
				'display' => array(
					'section_title'		=> 'Global Ads',
					'section_records'	=> array(
						'site_1' => array(
							'title' => 'Premier Global Ad',
							'description'=> 'First global ad to appear, second in over all rotation.',
							'items' => array(
								'title' => array(
									'element_type' => 'input',
									'title' => 'Ad Meta Title',
									'description'=> 'Less than 100 characters.',
									'type' => 'text',
									'limits' => 'maxlength="100"',
									'data' => '',
									'value' => ''
								),
								'link' => array(
									'element_type' => 'input',
									'title' => 'Ad Link',
									'description'=> 'Less than 200 characters. Absolute link.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								),
								'img' => array(
									'element_type' => 'input',
									'title' => 'Ad Graphic',
									'description'=> 'Must be exactly 1200px X 150px.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								)
							)
						),
						'site_2' => array(
							'title' => 'Secondary Global Ad',
							'description'=> 'Second global ad to appear, fourth in over all rotation.',
							'items' => array(
								'title' => array(
									'element_type' => 'input',
									'title' => 'Ad Meta Title',
									'description'=> 'Less than 100 characters.',
									'type' => 'text',
									'limits' => 'maxlength="100"',
									'data' => '',
									'value' => ''
								),
								'link' => array(
									'element_type' => 'input',
									'title' => 'Ad Link',
									'description'=> 'Less than 200 characters. Absolute link.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								),
								'img' => array(
									'element_type' => 'input',
									'title' => 'Ad Graphic',
									'description'=> 'Must be exactly 1200px X 150px.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								)
							)
						),
						'site_3' => array(
							'title' => 'Splash Homepage Ad',
							'description'=> 'Appears above homepage, and has to be interacted with to pass.',
							'items' => array(
								'title' => array(
									'element_type' => 'input',
									'title' => 'Ad Meta Title',
									'description'=> 'Less than 100 characters.',
									'type' => 'text',
									'limits' => 'maxlength="100"',
									'data' => '',
									'value' => ''
								),
								'link' => array(
									'element_type' => 'input',
									'title' => 'Ad Link',
									'description'=> 'Less than 200 characters. Absolute link.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								),
								'img' => array(
									'element_type' => 'input',
									'title' => 'Ad Graphic',
									'description'=> 'Must be smaller than 1200px X 600px.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								),
								'img_mobile' => array(
									'element_type' => 'input',
									'title' => 'Ad Mobile Graphic',
									'description'=> 'Displays below 600px. Must be between 600px X 600px-1200px.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								),
								'description' => array(
									'element_type' => 'input',
									'title' => 'Ad Description',
									'description'=> 'Less than 150 characters.',
									'type' => 'text',
									'limits' => 'maxlength="150"',
									'data' => '',
									'value' => ''
								)
							)
						),
						'site_4' => array(
							'title' => 'Homepage ',
							'description'=> 'Appears as a banner in the middle of the tiles in the Tiled Homepage Layout.',
							'items' => array(
								'title' => array(
									'element_type' => 'input',
									'title' => 'Featured Banner Title',
									'description'=> 'Less than 50 characters.',
									'type' => 'text',
									'limits' => 'maxlength="50"',
									'data' => '',
									'value' => ''
								),
								'sub_title' => array(
									'element_type' => 'input',
									'title' => 'Banner Tile Sub Header',
									'description'=> 'Less than 200 characters.',
									'type' => 'text',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								),
								'link' => array(
									'element_type' => 'input',
									'title' => 'Banner Tile Link',
									'description'=> 'Less than 200 characters. Absolute link.',
									'type' => 'url',
									'limits' => 'maxlength="200"',
									'data' => '',
									'value' => ''
								)
							)
						)
					)
				),
				'layout' => array(
					'section_title'		=> 'Global Layouts',
					'section_records'	=> array(
						'front_layout' => array(
							'title' => 'Homepage Layout',
							'description'=> 'Switch between featured posts layouts',
							'items' => array(
								'type' => array(
									'element_type' => 'radio',
									'title' => 'Layout',
									'description'=> 'Choose one.',
									'type' => 'radio',
									'chosen' => 'checked',
									'value' => 'news',
                  'options' => array(
                    array( 'option_name' => 'News Slider', 'option' => 'news' ),
                    array( 'option_name' => 'Large Tiles', 'option' => 'tiles' )
                  )
								)
							)
						),
						'menu_layout' => array(
							'title' => 'Menu Layout',
							'description'=> 'Switch between menu styles',
							'items' => array(
								'type' => array(
									'element_type' => 'radio',
									'title' => 'Menu Type',
									'description'=> 'Choose one.',
									'type' => 'radio',
									'chosen' => 'checked',
									'value' => 'drawer',
                  'options' => array(
                    array( 'option_name' => 'Traditional Menu', 'option' => 'drawer' ),
                    array( 'option_name' => 'Mega Menu', 'option' => 'mega' ),
                    array( 'option_name' => 'Side Slide Menu', 'option' => 'side_slide' )
                  )
								)
							)
						)
          )
        ),
        'connector' => array(
          'section_title'		=> 'Grassroots 365 Connection',
          'section_records'	=> array(
            'connector_data' => array(
              'title' => 'Grassroots 365 API Keys',
              'description'=> 'Add keys to enable Grassroots 365 functionality',
              'items' => array(
                'trans_key' => array(
                  'element_type' => 'input',
                  'title' => 'Transaction Key',
                  'description'=> 'Copy from Grassroots 365 account.',
                  'type' => 'text',
                  'limits' => 'maxlength="34"',
                  'data' => '',
                  'value' => ''
                ),
                'trans_id' => array(
                  'element_type' => 'input',
                  'title' => 'Transaction ID',
                  'description'=> 'Copy from Grassroots 365 account.',
                  'type' => 'text',
                  'limits' => 'maxlength="29"',
                  'data' => '',
                  'value' => ''
                )
              )
            )
          )
        )
			);
			function g365_update_admin_site_settings( $old, $new ) {
				foreach( $new as $item => $item_data ) {
					foreach( $item_data as $item_name => $item_value ) {
						$old[$item]['items'][$item_name]['value'] = g365_process_data_point( $old[$item]['items'][$item_name]['type'], $item_value);
            if( $old[$item]['items'][$item_name]['value'] === null || $old[$item]['items'][$item_name]['value'] === 'null' ) $old[$item]['items'][$item_name]['value'] = '';
					}
				}
				return $old;
			}
			foreach( $g365_admin_settings as $setting_set => &$setting_data ) {
				if( !empty($_POST['g_process']) && $_POST['g_process'] == 'process' ) {
					$g365_db_option = $_POST['g365_admin_form_data'][$setting_set];
					$update = update_option( 'g365_' . $setting_set, $g365_db_option );
					$setting_data['section_records'] = g365_update_admin_site_settings( $setting_data['section_records'], $g365_db_option );
					if( $update === true ) echo '<p class="success">Updated settings data!</p>';
				} else {
					$g365_db_option = get_option( 'g365_' . $setting_set );
					if( $g365_db_option !== false && !empty($g365_db_option) ) $setting_data['section_records'] = g365_update_admin_site_settings( $setting_data['section_records'], $g365_db_option );
        }
			} ?>
			<h3>General Site Settings</h3>
			<form id="g365_form" method="post" class="g365_form" action="<?php echo $g365_admin_url; ?>">
				<input type="hidden" name="g_process" value="process" />
			<?php foreach( $g365_admin_settings as $setting_set_re => $setting_data_re ) : ?>
				<h4><?php echo $setting_data_re['section_title']; ?></h4>
				<?php foreach( $setting_data_re['section_records'] as $record => $record_data ) : ?>
				<hr />
				<h3>
					<?php echo $record_data['title']; ?>
					<?php echo ( empty($record_data['description']) ) ? '' : '<small>' . $record_data['description'] . '</small>'; ?>
				</h3>
				<table class="g365_form_section form-table">
					<tbody>
					<?php foreach( $record_data['items'] as $element => $element_data ) {
						$element_data['tag'] = 'g365_admin_form_data[' . $setting_set_re . '][' . $record . '][' . $element . ']';
						$element_data['description'] = ( empty($element_data['description']) ) ? '' : '<small>' . $element_data['description'] . '</small>';
						echo g365_template_construction( $element_data );
					} ?>
					</tbody>
				</table>
				<?php endforeach;
			endforeach; ?>
				<button class="button">Update Settings</button>
			</form>
			<?php break;
    case 'admin_data_photo_verif': g365_dir_render('photo-upload', 'admin-upload', $player_id, $arg = null); 
      break;
    case 'admin_data_video_verif': g365_dir_render('photo-upload', 'admin-upload', $player_id, $arg = null); 
      break;
    case 'admin_all_tournament':
      $event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
      if( empty($event_id) ) : ?>
          <div id="g365_form_wrap" class="g365_form_wrap">
            <div id="g365_player_form_wrap">
              <div class="container" style="font-family: dharma-gothic-e, sans-serif;">
                <div class="grid-x grid-margin-x">  
                  <div class="cell small-12 medium-8 large-6">
                    <label for="event_link_selector" style="font-size: 2.2rem;">Event</label>
                    <div class="form-holder">
                      <input type="text" class="g365_livesearch_input" id="event_link_selector"
                             data-g365_type="event_all_tournament" placeholder="Enter Event Name" autocomplete="off">
                    </div>
                </div>
              </div>
            </div>
          </div>
      <?php 
      else :
        // save the changes
        if(isset($_POST['divisions'])){
          $data = file_get_contents('php://input');
          g365_save_division_roster($event_id, json_decode(explode('divisions=',urldecode($data))[1]));
          return;
        }
      
        $tournament_players = $wpdb->g365_players;
        //if we need to grab data from exposure, do it before we build the page
        if( isset( $_POST['export_games'] ) ) $save_count = save_exposure_game( $event_id );
        if( isset( $_POST['SCIBCA_export_games'] ) ) $save_count = save_SCIBCA_exposure_game( $event_id );
        //get the roster and event data for this id
//         $event_rosters = g365_get_rosters(array('event_id' => $event_id, 'order_by_master' => 'roster.level ASC, CASE WHEN roster.division = \'Open\' THEN \'1\' WHEN roster.division = \'Gold\' THEN \'2\' WHEN roster.division = \'Silver\' THEN \'3\' WHEN roster.division = \'Bronze\' THEN \'4\' WHEN roster.division = \'Copper\' THEN \'5\' ELSE roster.division END ASC, orgs.name ASC'), false, true);
        $event_info = g365_get_event_data( $event_id, true);
        //day list for taking attendance
        $event_days = '';
        if( !empty($event_info->dates) ) $event_days = g365_get_days_from_string( $event_info->dates );
  ?>
       <div class="divisions-form">
        <div class="loader" style="text-align: center; width: 100%; margin: 0 auto; padding-top: 50px;"><h1>Loading...&nbsp;&nbsp;&nbsp;<svg style="stroke:white;" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg><h1></div>
        <form id="divisions-form">
           <div class="container-div" style="width: 100%; font-family: dharma-gothic-e, sans-serif;">
              <div class="grid-x grid-margin-x">
                <div class="cell small-12 medium-6 large-6 event_block" style="display: none;">
                  <label for="event_link_selector" style="font-size: 2.2rem;">Event</label>
                  <div class="form-holder">
                    <input type="hidden" name="eventId" value="<?php echo $event_info->id; ?>" autocomplete="off">
                    <input type="text" value="<?php echo $event_info->name . ' -  ' . $event_info->id; ?>" 
                           class="g365_livesearch_input" id="event_link_selector" 
                           data-g365_type="event_all_tournament" placeholder="Search Event by Name" 
                           autocomplete="off">
<!--                     <label id="<?php //echo $event_info->id ?>" style="font-size: 1.2rem;"><?php //echo $event_info->name; ?></label> -->
                  </div>
                </div>
              </div>
             
               
            <?php //$ev_id = filter_input( INPUT_GET, 'ev', FILTER_SANITIZE_NUMBER_INT ); $ev_type = url_param('type'); empty($arg['enabled_access']) ? "" : $enabled_access = $arg['enabled_access']; 
           // $authorized_user = get_current_user_id(); 
           // if(empty($arg['dir_ev'])){ $arg['dir_ev'] = ''; } if(empty($ev_id)){ $ev_id = $arg['dir_ev']; $ajax_url = true; }
          //  if(!isset($_POST['roster_level']) && !isset($_POST['roster_dvs']) && !isset($_POST['stat_catagory']) && !isset($_POST['ev_val'])){ $post_level_val = "false"; $post_dvs_val = "false"; $post_stat_val = "false"; $post_ev_val = "false"; }else{ $post_ros_level = $_POST['roster_level']; 
          //  $post_dvs = $_POST['roster_dvs']; $post_stat_catagory = $_POST['stat_catagory']; $post_ev_id = (empty($_POST['ev_val']) ? '' : $_POST['ev_val']); }
         //   $g365_stat_leader = remote_stat_leader(['post_level_val'=>(empty($post_level_val) ? "" : $post_level_val), 'post_dvs_val'=>(empty($post_dvs_val) ? "" : $post_dvs_val), 'post_stat_val'=>(empty($post_stat_val) ? "" : $post_stat_val), 'post_ev_val'=>(empty($post_ev_val) ? "" : $post_ev_val), 'select_level'=>(empty($post_ros_level) ? "" : $post_ros_level), 'post_dvs'=>(empty($post_dvs) ? "" : $post_dvs), 'post_stat_catagory'=>(empty($post_stat_catagory) ? "" : $post_stat_catagory), 'post_ev_id'=>(empty($post_ev_id) ? "" : $post_ev_id), 'authorized_user'=>(empty($authorized_user) ? "" : $authorized_user), 'filter_ev_id'=>(empty($ev_id) ? "" : $ev_id), 'is_dcp'=>'dcp']);
          //    $g365_stat_leader = json_decode(json_encode($g365_stat_leader), true);
           //   $key_level = $g365_stat_leader[1];
             // $stat_lists = $g365_stat_leader[2];
            //  if(isset($_POST['year'])){ $select_year = $_POST['year']; };
           //   if(!isset($post_stat_catagory)){$post_stat_catagory = $g365_stat_leader[9];}
          //    $default_event_info = $g365_stat_leader[3];
           //   $event_info = $g365_stat_leader[4];
             // $default_num_pl = 5;
           //   $set_top_pl_num = 50; //echo "<pre>"; print_r($g365_stat_leader[13]); echo "</pre>";
             // $ev_location = str_replace('|', ' | ', $event_info['locations']);
              $level_key = g365_return_keys('g365_all_tournament_grade_key');
              $all_divisions = [];
      
//               $divisionRanges = [
//                   ['start' => 8, 'end' => 26],
//                   ['start' => 49, 'end' => 83],
//                   ['start' => 97, 'end' => 244]
//               ];
      
//               foreach($divisionRanges as $range) {
//                   for($i =$range['start']; $i <= $range['end']; $i++) {
//                     $all_divisions[] = ['id' => $i, 'name' => $level_key[$i]];
//                   }
//               }
      
              // if(($i > 11 && $i < 18) || ($i > 39 && $i < 48 )): duu
              $divisionIDs = [];
              for($i = 8; $i <= 26; $i++){
                $divisionIDs[] = $i;
                $all_divisions[] = ['id' => $i, 'name' => $level_key[$i]];
              }
              for($i = 49; $i <= 83; $i++){
                $divisionIDs[] = $i;
                $all_divisions[] = ['id' => $i, 'name' => $level_key[$i]];
              }
             // change this number when adding new all tournament division duuu
              for($i = 97; $i <= 331; $i++){
                $divisionIDs[] = $i;
                $all_divisions[] = ['id' => $i, 'name' => $level_key[$i]];
              }
      
              $divisionsData = g365_get_all_event_rosters($event_id, $divisionIDs);
      
      
             $data =  [
                 "eventId" => $event_id,  
                 "divisions" => $divisionsData
             ];
      
             // used in JS to populate / generate the form
             echo "<script>var formdata = ".json_encode($data).";</script>";
      
      
             // used later in JS to add options to dropdowns
             echo '<script>var all_divisions = '.json_encode($all_divisions).';</script>';
              ?>
             
             <div class="no-rosters" style="display: none;">
              <p>No rosters for this event yet</p>
             </div>
             
              <!--  JS will populate firt division and based on data some more. add divisions appends here -->
                <div class="divisions"></div>
             
                <!-- confirmation popup -->
<!--                 <div id="confirm" class="confirm-modal">
                  <div class="confirmation-content">
                    <p>Are you sure you want to save?</p>
                    <button id="yesButton" class="button-primary">Yes</button>
                    <button id="noButton" class="button-primary">No</button>
                  </div>
                </div> -->

                <div style="text-decoration: underline;">
                  <a class="add_division" style="color: blue; font-size: 1.2rem; display: none; margin-top: 20px;">+ Add division</a>
                </div>
             
                <br><br>
                <div style="display: flex; justify-content: flex-end; width: 100%;">
                   <button type="submit" class="button-primary button-medium save_all" 
                              style="font-size: 1.5rem; margin-top: 20px; font-family: dharma-gothic-e, sans-serif; display: none;">
                        Submit All Tournament
                      </button>
                </div>
                
          </form>
          <!-- Hidden Livesearch that gets filled by any player input and then we grab the list and append it to that 
                input that filled it to simulate a custom autcomplete -->
          <div class="form-holder" style="opacity: 0; position: fixed; top: 100vh; left: 100vw;">
            <input id="pl_name_autocomplete_hidden" type="text" class="g365_livesearch_input ls_query tournament_pl"
                   data-g365_type="event_all_tournament_player" placeholder="Enter Player Name" autocomplete="off" autofocus>
          </div>
           
          <script>
            
        // Start Functionality for Admin All-Tournament tab
        document.addEventListener('DOMContentLoaded', initializeInterface);

        function initializeInterface() {
              /*$(document).on('input', '#searchInput', function() {
                  var searchValue = this.value.toLowerCase();
                  var options = document.getElementById('optionsList').getElementsByTagName('option');
                  for (var i = 0; i < options.length; i++) {
                      var optionText = options[i].value.toLowerCase();
                      if (optionText.indexOf(searchValue) === -1) {
                          options[i].setAttribute('hidden', true);
                      } else {
                          options[i].removeAttribute('hidden');
                      }
                  }
              });*/
          
             // Player search inputs
             $(document).on('input', '.tournament_pl', function(event) {
               // find the hidden live search field, and simulate typing in it.
               var hasValue = $(event.target).parent().find('input[type=hidden]')[0].value;
               $(event.target).parent().find('input[type=hidden]')[0].value = '';
               if(hasValue){
                 event.target.value = '';
               }
             });
               
             // when selecting award type refreshes the spans above the players.
             $(document).on('change', "select.tournament_award", function() {
               updatePlayerSpanAwardType();
             })

             // Find all the already picked divisions, and regenarate the options for all of them when one gets picked.
             function updateAlreadySelectedDivisions() {
               //var selectedDivisionId = getDivisionIdFromName();

                // grab alreadySelectedDivisions from DOM
                alreadySelectedDivision = $('input.tournament_div').toArray()
                  .map(function(input) { return input.value })
                  .filter(a => a)
                  .map(function(a){
                    var divisionId = getDivisionIdFromName(a);
                    return (divisionId !== "");
                  });

                // regenerate all options.
                regenerateOptions();
             }


             // on picking a division
             $(document).on('change', "input.tournament_div", function() {
                updateAlreadySelectedDivisions();
             });

             function regenerateOptions() {

               // grab all dropdowns
               $('input.tournament_div').toArray().map(function(input) {
                 var value = input.value;

                 // remove all options
                  $(input).find('option').remove(); // 'datalist';

                 // add new ones with proper selected and disabled according to current alreadySelectDivision array.
                 input.innerHTML = generateDivisionsOptions(getDivisionIdFromName(value));
               });
             }


            var HiddenAutocompleteInput = $('#pl_name_autocomplete_hidden')[0];
            var activeInput;
            var oldResults;
          
            // Handles the player inputs to get the "live search" work by feeding a hidden input with what 
            // the user types in the visible player inputs.
            $(document).on('keyup', 'input.player-autocomplete-input', function(event){
                // clear the list 
                $('.autocomplete-results').empty();

                // set the value
                HiddenAutocompleteInput.value = event.target.value;

                // dispatch an event to force autocomplete to run with new value
                HiddenAutocompleteInput.dispatchEvent(
                    new KeyboardEvent("keyup", {
                        key: "e",
                        keyCode: 69,
                        code: "KeyE",
                        which: 69,
                        shiftKey: false,
                        ctrlKey: false,
                        metaKey: false
                    }));


                activeInput = event.target;
            });
          
           // when users unfocuses one of the visibile player inputs, we can remove the results
           $(document).on('blur', 'input.player-autocomplete-input', function(event){
             setTimeout(function() {
               $('.autocomplete-results').empty();
             },300);
           });
          
          $(document).on('input', '.tournament_div', function(event){
             var division = $(event.target);
             
             // check if valid
             division.css('background-color', getDivisionIdFromName(event.target.value) ? '' : 'rgb(255 140 140)');
           });
               
            // copy results over from hidden autocomplete to visible player inputs
            setInterval(() => {
                // sync the content of the active player input to the ones of the autocomplete
                var currentAutocompleteResults = $(activeInput).parent().find('.autocomplete-results')[0];

                // grab the hidden results
                var results = $('[id^=search_event_all_tournament_player_pl_name_autocomplete_hidden] .ls_result_main')[0];

                // put the results there only if they are different (means we got new data);
                if(currentAutocompleteResults && oldResults !== results.innerHTML){
                    oldResults = results.innerHTML;
                    // empty all autocomplete for visible player fields.
                    $('.autocomplete-results').empty();
                    currentAutocompleteResults.innerHTML = results.innerHTML;
                }
            },200);

            // clicking on one of those results
            $(document).on('click', '.autocomplete-results', function(event) {
            //$('.autocomplete-results').on( "click", function(event) {
                var tr = $(event.target).closest('tr')[0];

                // sets the player input visible text.
                activeInput.value = tr.attributes['data-name'].value;

                var activeInputHiddenInput = $(activeInput).closest('div').find('input[type=hidden]')[0];
                if(!activeInputHiddenInput){
                  console.error('cant find hidden input next to', activeInput);
                }else{
                  // sets the invisible player input id.
                  activeInputHiddenInput.value = tr.attributes['data-href'].value.split('player_id=').pop();
                }

                // clear the list we are done.
                $('.autocomplete-results').empty();
                // stop the auto-copying
                activeInput = undefined;
            });

            function updatePlayerSpanAwardType(){
               var dropdownValues = $('.tournament_award').toArray().map(function(e){ return e.value; });

               // set the first span.
               dropdownValues.map(function(dropdownValue, index) {
                 var firstSpan = $(`.division:nth-child(${index + 1}) span`)[0];
                 if(firstSpan) {
                   firstSpan.innerHTML = dropdownValue;
                 }
               });

               // set all the other
               $('.players>div:not(:first-child) span').toArray().map(function(e) {
                   e.innerHTML = 'All-Tournament Team';
                 })
             }
               
             // Populate division dropdowns.
             function generateDivisionsOptions(selectedId){
               var optionsHTML = all_divisions.map(function(division) {
                 var alreadySelected = alreadySelectedDivision.indexOf(division.id) !== -1;
                 var optionHTML = alreadySelected ? '' : `<option value="${division.name}">${division.name}</option> `; // `<option ${(alreadySelected ? 'disabled' : '')}
                                // ${division.id === selectedId ? 'selected' : ''}
                                // value="${division.name}">${division.name}</option>`;
                 return optionHTML;
               }).join('');


               return optionsHTML;//`<option value="" id="select" ${!selectedId ? 'selected' : ''} disabled>Select Division</option>` + optionsHTML;
             }
               
             // returns a division name or nothing.
             function getDivisionNameFromId(divisionId){
               var matchingDivision = all_divisions.find(function(d){ return d.id == divisionId});
               return matchingDivision ? matchingDivision.name : '';
             }
               
             // returns a division name or nothing.
             function getDivisionIdFromName(divisionName){
               var matchingDivision = all_divisions.find(function(d){ return d.name == divisionName});
               return matchingDivision ? matchingDivision.id : '';
             }
               
            $(document).on('click', '.add_division', function() { 
              addDivision(); 
            });

            var alreadySelectedDivision = [];

            function addDivision(divisionData){
              var hadData = !!divisionData;
              var divisionCount = $(".division").length;
              var firstPlayer = divisionData ? divisionData.players[0] : {};
              divisionData = divisionData ? divisionData : {};
              var divisionValueText = divisionData.divisionId ? getDivisionNameFromId(divisionData.divisionId) : '';

              var divisionHtml = `
                <div class="division">
                  <div class="grid-x grid-margin-x grid-padding-x">
                    <div class="cell small-6 medium-4 large-3">
                      <div class="form-holder">
                        <label for="division_selector-${divisionCount}" style="font-size: 1.2rem;">Division</label>
                        <input type="text" id="division_selector-${divisionCount}" 
                               list="division_list-${divisionCount}" value="${divisionValueText}"
                               name="divisions[${divisionCount}].divisionId" class="tournament_div">
                          <datalist id="division_list-${divisionCount}">
                              ${generateDivisionsOptions(divisionData.divisionId)}
                          </datalist>
                      </div>
                    </div>
                    <div class="form-holder cell small-6 medium-8 large-12">
                      <label style="font-size: 1.2rem;" for="award-type-selector-${divisionCount}">First player Award Type</label>
                      <select name="divisions[${divisionCount}]awardType" class="tournament_award" name="award" 
                              id="award-type-selector-${divisionCount}"
                              onchange="pickedAwardType(${divisionCount})">
                          <option value="" disabled>Select Award</option>
                          <option ${firstPlayer.awardType == '11' ? 'selected' : ''} value="All-Tournament Team">All-Tournament Team</option>
                          <option ${firstPlayer.awardType == '12' ? 'selected' : ''} value="All-Tournament MVP">All-Tournament MVP</option>
                      </select>
                    </div>
                    <div class="cell small-12 medium-12 large-12">
                      <div style="text-decoration: underline; margin-bottom:20px;">
                        <a class="add_players" style="color: blue; font-size: 1.2rem;"  
                           onclick="addPlayers(event)">+ Add more players</a>
                      </div>
                    </div>
                  </div>

                  <div class="grid-x grid-margin-x" id="tournament-player">
                    <div class="players grid-x grid-margin-x cell small-12 medium-12 large-12"></div>
                  </div>

                  <button class="delete-division" style="background: #d5d5d5" type="button" onclick="deleteDivision(event)">Delete Division</button>
                 </div>
               </div> 
              </div>`;
              
              // create div that will hold the division.
              var newDivision = $(divisionHtml);

              // append it
              var divisionsHolder = $(".divisions")[0];

              // does this append to the end?
              divisionsHolder.appendChild(newDivision[0]);
              
              // update no rosters message
              updateNoDivisions();

              if(hadData){
                divisionData.players.slice(0).map(playerData => addPlayers(divisionCount, playerData));
                alreadySelectedDivision.push(divisionData.divisionId);
              } else {
                [1,2,3,4,5].map(function(n) { addPlayers(divisionCount, undefined)})
              }
           }
          
          window.updateNoDivisions = function() {
            $('.no-rosters').css('display', $('.division').toArray().length === 0 ? 'block' : 'none');
          }
             
          window.pickedAwardType = function(divisionIndex) {
            var division = $('.division')[divisionIndex];
            var playerCount = $(division).find('input[type=hidden]').length;
            if(playerCount < 5){
              for(var i = playerCount; i < 5; i++){
                window.addPlayers(divisionIndex);
              }  
            }

            updatePlayerSpanAwardType();
            // update labels under players.
          }
              
          window.deleteDivision = function(event){

            var division = $(event.target).closest('.division');
            var divisionPicked = division.find('.tournament_div')[0].value;
            
            //prompt the user for confirmation
            var confirmDelete = window.confirm("Are you sure you want to delete this division?");
            
            if(confirmDelete) {
              //make division accessible again
              if(divisionPicked !== '') {
                var divisionPickedId = all_divisions.find(function(d) { return d.name == divisionPicked }).id;
                
                //remove from alreadySelected ones
                alreadySelectedDivision.splice(alreadySelectedDivision.indexOf(divisionPickedId), 1);
                regenerateOptions();
              }
              division.remove();
            }
            
            updateNoDivisions();
          }
                                    
          window.removePlayer = function(event){
              //we have to grab division before the remove otherwise can't do "closest"
              var division = $(event.target).closest('.division');
              $(event.target).closest('.player').remove();
              // update visibility of add players. 
              $(division).find('.add_players')[0].style.display = 'block';

              updatePlayerSpanAwardType();
          }
             
          window.addPlayers = function(divisionIndex, playerData) {
              if(divisionIndex === undefined) return console.error('need divisionIndex to add player', divisionIndex);

              // from a click on adddPlayer() in DOM
              if(divisionIndex.target){
                divisionIndex = $(divisionIndex.target).closest('.division').index();
              }

              var playerData = playerData || {};

              var division = $('.division .players')[divisionIndex];
              // todo this should work with an index... because we got multiple on page.todo
              var playerCount = $(division).find("input[type='hidden']").length;

              // Limit to a maximum of 5 extra players (so 5 + 5 is 10.)  Hide the link if the maximum player count is reached
              var addPlayerButton = $($('.division')[divisionIndex]).find('.add_players')[0].style.display = (playerCount >= 9 ? 'none' : 'block');

              var html = 
                  `<div class="cell small-4 medium-4 large-2 player" style="display: flex; flex-direction: row; align-items: center;">
                       <div class="form-holder">
                          <span></span>
                          <input type="hidden" value="${playerData.id ?? ''}" name="divisions[${divisionIndex}]players[${playerCount - 1}].id">
                          <input type="text" class="tournament_pl player-autocomplete-input" name="divisions[${divisionIndex}]players[3]"
                                 id="divisions[${divisionIndex}]players[${playerCount - 1}]" placeholder="Enter Player Name" value="${playerData.name ?? ''}" autocomplete="off">
                          <div class="autocomplete-results"></div>
                       </div>
                       <button class="delete-button" type="button" onclick="removePlayer(event)">X</button>
                    </div>`;

              division.appendChild($(html)[0]);

              updatePlayerSpanAwardType();
          }
              
          if(formdata.divisions.length === 0){
               updateNoDivisions();
          }else{
            // restore form values after load
            formdata.divisions.map(function(divisionData){
              if (divisionData === null){
                return console.log("no division data");
              } 
              addDivision(divisionData);
            });

          }
                  
          
          updatePlayerSpanAwardType();

          // show what's needed
          $('.loader').hide();
          $('.add_division')[0].style.display = 'block';
          $('.save_all')[0].style.display = 'block';
          $('.event_block')[0].style.display = 'block';

//             //get the modal and buttons
//             var modal = document.getElementById("confirm");
//             //var saveButton = document.getElementById("saveButton");
//             var yesButton = document.getElementById("yesButton");
//             var noButton = document.getElementById("noButton");
             
//             // click to open modal
//             saveButton.onclick = function() {
//              modal.style.display = "block";
//             }
            
//             // todo not sure what this does.
//             yesButton.onclick = function() {
//               console.log('yes click');
//               modal.style.display = "none";
//               document.getElementById('add_division').style.display = 'inline';

//                 // List of classes for the form elements you want to disable
//                 var classNames = ['tournament_div', 'tournament_pl', 'tournament_award'];
              
//                 //function to check if all input fields are filled out
//                 function areAllFieldsFilled() {
//                   var allFieldsFilled = true;
//                   classNames.forEach(function(className) {
//                     var elements = document.getElementsByClassName(className);
//                     for(var i = 0; i < elements.length; i++) {
//                       if(elements[i].value.trim() === '') {
//                         allFieldsFilled = false;
//                         break;
//                       }
//                     }
//                   });
//                   return allFieldsFilled;
//                 }
//                 //Check if all input fields are filled out before disabling them
//                 if(areAllFieldsFilled()) {
//                   classNames.forEach(function(className) {
//                     var elements = document.getElementsByClassName(className);
//                     for(var i = 0; i < elements.length; i++) {
//                       elements[i].setAttribute('disabled', 'true');
//                     }
//                   });
//                 } else {
//                   alert("Please fill out all fields before saving.");
//                 }
//             }
            
//             noButton.onclick = function() {
//               modal.style.display = "none";
//             }

          document.getElementById('divisions-form').addEventListener('submit', function(e) {
            e.preventDefault();
            saveAll();
          });
              
          function afterSaving(){
              $('.loader').hide();
              $('.container-div').show();
          }

          function saveAll(event) {
            $('.loader').show();
            $('.container-div').hide();
            $('.division').css('background-color', 'transparent');
            $('select.tournament_award, .player:first-child() input.tournament_pl') // input.tournament_div, 
              .toArray()
              .map(function(input){
                  $(input).css('background-color', '');
              });

            var invalidDivisions = [];
            var divisionData = $('.division').toArray().map(function(division){
                return {
                  divisionDOMObject : division,
                  divisionId : getDivisionIdFromName($(division).find('.tournament_div')[0].value),
                  awardType : $(division).find('.tournament_award')[0].value,
                  players: $(division).find('input[type=hidden]')
                    .toArray().map(function(input){ return input.value; })
                    .filter(function(player){
                        return !(!player);
                      })
                };
            }).filter(function(division){
               var hasData = division.divisionId && division.players.length > 0 && division.awardType;
               if(!hasData){
                 invalidDivisions.push(division.divisionDOMObject);
                 // division.divisionDOMObject.remove();
               }
               return hasData;
            });
            
            var checkForDuplicates = true;
            
            // look for multiple times the same values in some fields to check for duplicates.
            var duplicatesCount = 0;
            if(checkForDuplicates){
              var dupesSelector = '.tournament_div, .divisions input[type=hidden]';
              var formValues = $(dupesSelector).toArray().filter(e => e.value).map(e => e.value);
              $(dupesSelector).toArray().filter(e => e.value).map((e) => {
                 var count = 0;
                 for(var i = 0; i <= formValues.length; i++){
                   if(formValues[i] === e.value) count++;
                 }
                 if(count > 1){
                   duplicatesCount++;
                   var toHighlight = e;
                   if(e.getAttribute('type') === 'hidden'){
                     e = $(e).closest('div').find('input[type=text]')[0];
                   }
                   $(e).css('background-color', 'rgb(255 190 190)');
                 }
              });
            }

            if(invalidDivisions.length > 0 || duplicatesCount > 0){
              afterSaving();
              invalidDivisions.map(function(e){
                $(e).css('background-color', 'rgb(255 190 190)');
                $('input.tournament_div, select.tournament_award, .player:first-child() input.tournament_pl')
                  .toArray()
                  .map(function(input){
                    if(!input.value){
                      $(input).css('background-color', 'rgb(255 140 140)');
                    }
                  });
              });
              setTimeout(function(){
                alert(duplicatesCount > 0 ? 'Some players or divisions have been picked twice' : 'Some divisions are missing data');
              },1);
              return;
            }
            
            divisionData.map(d => delete d.divisionDOMObject);

            $.ajax({
            url : window.location.href,
            method: 'post',
            data: {
              "eventId" : $("input[name='eventId']")[0].value,
              "divisions" : JSON.stringify(divisionData)
              }
            }).done(function(){
              afterSaving();
              
              // update data.updata about what selects are possible and not.
              updateAlreadySelectedDivisions();

              // remove empty player inputs
              $(".player input[type=hidden]").toArray().filter(i => !i.value).map(i => $(i).closest('.player')[0].remove());
            });
          };
        };

       // End Admin All tournament functionality.
       </script>
          </div>
          <?php
      endif;
      break;
    case 'admin_data_team_ev':
      $event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
        if( empty($event_id) ) : ?>
          <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-8 large-6">
              <label for="event_link_selector">Event</label>
              <div class="form-holder">
                <input type="text" class="g365_livesearch_input" id="event_link_selector" data-g365_type="event_admin_team_stat" placeholder="Enter Event Name" autocomplete="off">
              </div>
            </div>
          </div>
        <?php
        else :
        $event_stats = scope_get_team_stats( null, intval( $event_id ), '0-1', 'front_page DESC, event_time DESC', null, 'camps' );
        $event_info = g365_get_event_data( $event_id, true);
//         global $wpdb;
//         $wpdb_team_stats = $wpdb->g365_team_stats;
      
//         $event_stats = $wpdb->get_results(
//           "SELECT
//             JSON_UNQUOTE(JSON_EXTRACT(trends, '$.ss_event_participated')) AS event_participated
//           FROM 
//               $wpdb_team_stats
//           WHERE JSON_UNQUOTE(JSON_EXTRACT(trends, '$.ss_event_participated')) = $event_id;");
//       var_dump($event_stats);
        
        if( !empty($event_stats) && is_array($event_stats) ) {
            $admin_string = '';
            if( current_user_can('administrator') ) {
              $admin_string = ', "g365_admin" : "true"';
              $current_user = wp_get_current_user();
              $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
              $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
            }
    //           <div class="grid-x grid-margin-x">
    //             <div class="cell small-12 medium-8 large-6 medium-offset-2 large-offset-3">
    //             </div>
    //           </div>

          echo '<h2>' . $event_info->name . '</h2>'; ?>
    <!--         <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                <div class="relative">
                  <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="g365_form_options_anchor" data-g365_type="player_event_admin"></div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script> -->
          <?php 
             if($event_info->org == 7164) { ?>
              <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                    <div class="relative">
                      <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div id="g365_form_options_anchor" data-g365_type="hhh_team_event_admin"></div>
              </div>
              <script type="text/javascript">var g365_form_details = {"items" : {"Manage Team Stats" : {"name" : "Make changes to team event stats", "title" : "", "type" : "hhh_team_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

              <div class="grid-x grid-margin-x">
                <div class="cell small-12">
                  <table class="edit-table">
                    <thead>
                      <tr>
                        <th>Team</th>
                        <th>Action</th>
                        <th>Evaluation</th>
                        <th>Strengths</th>
                        <th>Weaknesses</th>
                        <th>Stats</th>
                        <th>Event Data</th>
                        <th>Video</th>
                        <th>Offers</th>
                        <th>Player to Watch?</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $disabled_switch = false;
                    foreach( $event_stats as $stat_dex => $stat_data ) {
      //                 print_r($stat_data);
//                       if( empty($stat_data->name) ) continue;
                      if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                        echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                        $disabled_switch = true;
                      }
                      $pl_xtra_data = json_decode($stat_data->trends);
                      $stat_data->notes = json_decode($stat_data->notes);
                      $today = date("Y-m-d");
                      $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
                      echo "<tr$enabled>";
                        echo '<td>' . $stat_data->org_name . ' ' . $stat_data->search_list . '</td>';
                        echo '<td><a class="button small tiny-margin g365-edit-data" id="tm_ev_' . $stat_data->id . '" data-g365_type="hhh_team_event_admin::' . $stat_data->id . '">Edit</a></td>';
                        echo '<td>' . ((empty($stat_data->evaluation)) ? '' : $stat_data->evaluation) . '</td>';
                        echo '<td>' . ((empty($stat_data->strengths)) ? '' : $stat_data->strengths) . '</td>';
                        echo '<td>' . ((empty($stat_data->weaknesses)) ? '' : $stat_data->weaknesses) . '</td>';
                        echo '<td>' . ((empty($stat_data->stats)) ? '' : $stat_data->stats) . '</td>';
                        echo '<td>' . ((empty($stat_data->trends)) ? '' : $stat_data->trends) . '</td>';
                        echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
                        echo '<td>' . ((empty($pl_xtra_data->offers)) ? '' : $pl_xtra_data->offers) . '</td>';
                        echo '<td>' . ((empty($pl_xtra_data->player_to_watch)) ? '0' : $pl_xtra_data->player_to_watch) . '</td>';
                      echo '</tr>';
                    }
                    ?>
                    </tbody>
                  </table>
                </div>
              </div>  

            <?php } else if ($event_info->org == 8437) { ?>
          <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                <div class="relative">
                  <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="g365_form_options_anchor" data-g365_type="ss_team_event_admin"></div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to team event stats", "title" : "", "type" : "ss_team_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

          <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <table class="edit-table">
                <thead>
                  <tr>
                    <th>Player</th>
                    <th>Action</th>
<!--                     <th>Age</th>
                    <th>Birthdate</th>
                    <th>Jersey Size</th>
                    <th>Grade</th>
                    <th>Class</th> -->
                    <th>Evaluation</th>
                    <th>Stats</th>
                    <th>Event Data</th>
                    <th>Video</th>
<!--                     <th>Level</th>
                    <th>Offers</th>
                    <th>Player to Watch?</th> -->
                    <th>Front Page</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $disabled_switch = false;
                $scope_event_stats = scope_get_team_stats( null, intval( $event_id ), '0-1', 'front_page DESC, event_time DESC', null, 'camps' );
//                   print_r($scope_event_stats);
                foreach( $scope_event_stats as $stat_dex => $stat_data ) { 
//                   if( empty($stat_data->name) ) continue;
                  if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                    echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                    $disabled_switch = true;
                  }
                  $pl_xtra_data = json_decode($stat_data->trends);
                  $stat_data->notes = json_decode($stat_data->notes);
                  $today = date("Y-m-d");
                  $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
                  echo "<tr$enabled>";
                    echo '<td>' . $stat_data->org_name . ' ' . $stat_data->search_list . '</td>';
                    echo '<td><a class="button small tiny-margin g365-edit-data" id="tm_ev_' . $stat_data->id . '" data-g365_type="ss_team_event_admin::' . $stat_data->id . '">Edit</a></td>';
//                     echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create($today))->format('%y')) . '</td>';
//                     echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
//                     echo '<td>' . ((empty($stat_data->notes->jersey_size)) ? '' : $stat_data->notes->jersey_size) . '</td>';
//                     echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
//                     echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
                    echo '<td>' . ((empty($stat_data->evaluation)) ? '' : $stat_data->evaluation) . '</td>';
//                     echo '<td>' . ((empty($stat_data->strengths)) ? '' : $stat_data->strengths) . '</td>';
//                     echo '<td>' . ((empty($stat_data->weaknesses)) ? '' : $stat_data->weaknesses) . '</td>';
                    echo '<td>' . ((empty($stat_data->stats)) ? '' : $stat_data->stats) . '</td>';
                    echo '<td>' . ((empty($stat_data->trends)) ? '' : $stat_data->trends) . '</td>';
                    echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
//                     echo '<td>' . ((empty($pl_xtra_data->level_division)) ? '' : $pl_xtra_data->level_division) . '</td>';
//                     echo '<td>' . ((empty($pl_xtra_data->offers)) ? '' : $pl_xtra_data->offers) . '</td>';
//                     echo '<td>' . ((empty($pl_xtra_data->player_to_watch)) ? '0' : $pl_xtra_data->player_to_watch) . '</td>';
                    echo '<td>' . ((empty($pl_xtra_data->front_page)) ? '0' : $pl_xtra_data->front_page) . '</td>';
                  echo '</tr>';
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>  
          
        <?php } else { ?>
    <!--       <p>
          hello  
          </p> -->
          <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal data-append-to="#g365_data_manager_admin">
                <div class="relative">
                  <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="g365_form_options_anchor" data-g365_type="team_event_admin"></div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to team event stats", "title" : "", "type" : "team_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>

          <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <table class="edit-table">
                <thead>
                  <tr>
                    <th>Team</th>
                    <th>Action</th>
                    <th>Evaluation</th>
<!--                     <th>Strengths</th>
                    <th>Weaknesses</th> -->
                    <th>Stats</th>
                    <th>Event Data</th>
                    <th>Video</th>
    <!--                   <th class="hide">Level</th>
                    <th class="hide">Offers</th>
                    <th class="hide">Player to Watch?</th> -->
                  </tr>
                </thead>
                <tbody>
                <?php
                $disabled_switch = false;
                foreach( $event_stats as $stat_dex => $stat_data ) {
    //                 print_r($stat_data);
//                   if( empty($stat_data->name) ) continue;
                  if( $stat_data->st_enabled === '0' && $disabled_switch === false ) {
                    echo '<tr class="disabled-title"><td colspan="13">Disabled</td></tr>';
                    $disabled_switch = true;
                  }
                  $pl_xtra_data = json_decode($stat_data->trends);
                  $stat_data->notes = json_decode($stat_data->notes);
                  $today = date("Y-m-d");
                  $enabled = ($stat_data->st_enabled === '0') ? ' class="disabled"' : '';
                  echo "<tr$enabled>";
                    echo '<td>' . $stat_data->org_name . ' ' . $stat_data->search_list . '</td>';
                    echo '<td><a class="button small tiny-margin g365-edit-data" id="tm_ev_' . $stat_data->id . '" data-g365_type="team_event_admin::' . $stat_data->id . '">Edit</a></td>';
                    echo '<td>' . ((empty($stat_data->evaluation)) ? '' : $stat_data->evaluation) . '</td>';
//                     echo '<td>' . ((empty($stat_data->strengths)) ? '' : $stat_data->strengths) . '</td>';
//                     echo '<td>' . ((empty($stat_data->weaknesses)) ? '' : $stat_data->weaknesses) . '</td>';
                    echo '<td>' . ((empty($stat_data->stats)) ? '' : $stat_data->stats) . '</td>';
                    echo '<td>' . ((empty($stat_data->trends)) ? '' : $stat_data->trends) . '</td>';
                    echo '<td>' . ((empty($stat_data->video)) ? '' : $stat_data->video) . '</td>';
    //                   echo '<td class="hide">' . ((empty($pl_xtra_data->level_division)) ? '' : $pl_xtra_data->level_division) . '</td>';
    //                   echo '<td class="hide">' . ((empty($pl_xtra_data->offers)) ? '' : $pl_xtra_data->offers) . '</td>';
    //                   echo '<td class="hide">' . ((empty($pl_xtra_data->player_to_watch)) ? '0' : $pl_xtra_data->player_to_watch) . '</td>';
                  echo '</tr>';
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php
            }
          } else {
          ?>
          <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-8 large-6">
              <p>No records found.</p>
              <a href="<?php echo $g365_sections[$g_action]['url']; ?>">Modify Search</a>
            </div>
          </div>
          <?php
          }
        endif;
        break;
    case 'admin_data_team_stats':
//         echo '<H1> Cronos is working </H1>
//               <p> Add the team into the event here so then we can go into the event to create the evaluation</p>
//               ';
      ?>
      <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-8 large-6">
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats":{"name":"Make changes to team event stats","title":"","type":"team_event","items":{}}}, "wrapper_target" : "g365_form_options_anchor"};</script>
          <div>
            <div id="g365_form_options_anchor" data-g365_type="team_event"></div>
          </div>
        </div>
      </div>
      <?php
      break;
    case 'admin_pass_rep':
      session_start();

      if (isset($_POST['download_csv'])) {
          // Check if $pass_report is in the session
          if (empty($_SESSION['pass_report'])) {
              echo 'No data available for download.';
              exit();
          }

          // Retrieve $pass_report from the session
          $pass_report = $_SESSION['pass_report'];
          ob_clean();
          // Set headers to force download as a CSV file
          header('Content-Type: text/csv');
          header('Content-Disposition: attachment;filename="player_report.csv"');

          // Open output stream
          $output = fopen('php://output', 'w');

          // Write CSV headers
          fputcsv($output, array('Player ID', 'Player Name', 'Player Email', 'Player Birthday', 'Player Class', 'Player Phone'));

          // Write data rows
          foreach ($pass_report as $report_data) {
              fputcsv($output, array(
                  $report_data->player_id,
                  $report_data->player_name,
                  (empty($report_data->player_email)) ? '' : $report_data->player_email,
                  (empty($report_data->player_birthday)) ? '' : $report_data->player_birthday,
                  (empty($report_data->player_class)) ? '' : $report_data->player_class,
                  (empty($report_data->player_phone)) ? '' : $report_data->player_phone
              ));
          }

          // Close the output stream
          fclose($output);
          session_unset();
          session_destroy();
          exit(); // Stop further execution to prevent any HTML output
      }
      $event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
      
      if(!isset($_SESSION['event_id']) || $_SESSION['event_id'] != $event_id) {
        $_SESSION['pass_report'] = [];
      }
      $_SESSION['event_id'] = $event_id;
      
      if( empty($event_id) ) : ?>
          <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-8 large-6">
              <label for="event_link_selector">Event</label>
              <div class="form-holder">
                <input type="text" class="g365_livesearch_input" id="event_link_selector" data-g365_type="event_pass_rep" placeholder="Enter Event Name" autocomplete="off">
              </div>
            </div>
          </div>
          <?php
          else :
            $all_player_ids = g365_get_passport_data($event_id, 'ids');
            $event_name = g365_get_event_data($event_id, true);
            
            if(array_key_exists('non_passport', $_POST)) {
              $_SESSION['pass_report'] = g365_get_passport_data($event_id, 'non_passport', $all_player_ids);
            }
            if(array_key_exists('all_players', $_POST)) {
              $_SESSION['pass_report'] = g365_get_passport_data($event_id, 'all_players', $all_player_ids);
            }
            if(array_key_exists('non_passport2', $_POST)) {
              $_SESSION['pass_report'] = g365_get_passport_data($event_id, 'non_age_range', $all_player_ids, $_POST['start'], $_POST['end']);
            }
            if(array_key_exists('all_players2', $_POST)) {
              $_SESSION['pass_report'] = g365_get_passport_data($event_id, 'all_age_range', $all_player_ids, $_POST['start'], $_POST['end']);
            }
  
            $pass_report = $_SESSION['pass_report'];
      
            echo '<h2>' . $event_name->name . '</h2>';
            if($all_player_ids) {
             ?>
          <h5>
              Filter by:
          </h5>
          <form method="post">
            <div class="report_buttons" style="margin-top: 5px; display: inline-block;">
                <button id="non_pass" type="submit" class="button-primary button-medium" name="non_passport" value="non_passport">Non Passport Players</button>
                <button id="non_pass" type="submit" class="button-primary button-medium" name="all_players" value="all_players">All Players</button>
            </div>
            <div id="non_pass" class="button no-margin btn-toggleButton">Age Range</div>
            <div class="age_range_cont hide" style="margin-top: 5px;">
              <input class="pl_age_start" type="text" id="start" placeholder="Starting Age" name="start" style="width: 200px; display: inline-block;">
              <input class="pl_age_end" type="text" id="end" placeholder="Ending Age" name="end" style="width: 200px; display: inline-block;">
              <div>
                <button id="non_pass" type="submit" class="button-primary button-medium" name="non_passport2" value="non_passport2">Non Passport Players</button>
                <button id="non_pass" type="submit" class="button-primary button-medium" name="all_players2" value="all_players2">All Players</button>
              </div>
            </div>              
          </form>
    <?php } else {
              echo "No player data for this event";
            }
      if(!empty($pass_report)) { ?>
      <form method="post" action="">
        <div>
          <button id="non_pass" type="submit" class="button-primary button-medium" name="download_csv" value="download_csv">Download to CSV File</button>
        </div>
        <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <table class="report-table">
                <thead>
                  <tr>
                    <th>Player ID</th>
                    <th>Player Name</th>
                    <th>Player Email</th>
                    <th>Player Birthday</th>
                    <th>Player Class</th>
                    <th>Player Phone</th>
                  </tr>
                </thead>
                <tbody>
     <?php
          foreach($pass_report as $report_dex => $report_data) {
            echo '<tr>';
            echo '<td>' . $report_data->player_id . '</td>';
            echo '<td>' . $report_data->player_name . '</td>';
            echo '<td>' . ((empty($report_data->player_email)) ? '' : $report_data->player_email) . '</td>';
            echo '<td>' . ((empty($report_data->player_birthday)) ? '' : $report_data->player_birthday) . '</td>';
            echo '<td>' . ((empty($report_data->player_class)) ? '' : $report_data->player_class) . '</td>';
            echo '<td>' . ((empty($report_data->player_phone)) ? '' : $report_data->player_phone) . '</td>';
            echo '</tr>';
          } 
        ?>
            </tbody>
          </table>
        </div>
       </div>
    </form>
   <?php }
      endif;
   ?> 
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const content = document.querySelector(".report-table");
        const items_per_page = 50;
        let current_page = 0;
        const items = Array.from(content.getElementsByTagName("tr")).slice(1);
        
        function showPage(page) {
          const startIndex = page * items_per_page;
          const endIndex = startIndex + items_per_page;
          items.forEach((item, index) => {
            item.classList.toggle("hidden", index < startIndex || index >= endIndex);
          });
          updateActiveButtonStates();
        }
        
        function createPageButtons() {
          const totalPages = Math.ceil(items.length / items_per_page);
          const paginationContainer = document.createElement("div");
          paginationContainer.classList.add("pagination");
          
          for(let i = 0; i < totalPages; i++) {
            const pageButton = document.createElement("button");
            pageButton.textContent = i + 1;
            pageButton.addEventListener("click", () => {
              event.preventDefault();
              current_page = i;
              showPage(current_page);
              updateActiveButtonStates();
            });
            paginationContainer.appendChild(pageButton);
          }
          content.parentElement.appendChild(paginationContainer);
        }
        
        function updateActiveButtonStates() {
          const pageButtons = document.querySelectorAll(".pagination button");
          pageButtons.forEach((button, index) => {
            if(index === current_page) {
              button.classList.add("active");
            } else {
              button.classList.remove("active");
            }
          });
        }
        createPageButtons();
        showPage(current_page);
      });     
    </script>
   <?php
    break;

		default:
			echo '<h2>Error, this page shouldn\'t be accessible. Please contact your system administrator.</h2>';
	}
	echo '</div></div>';
}


//g365 data converter string to associative array
function g365_data_string_array($data) {
  $data = explode('|', $data);
  $data = array_map( function($section){ return explode(',', $section); }, $data);
  $proc_data_compile = array();
  foreach( $data as $dex => $vals ) $proc_data_compile[array_shift($vals)] = $vals;
  return $proc_data_compile;
}
//g365 reference/owner integrator
function g365_reference_data_integrator( $new_data, $existing ) {
  
  if( empty($new_data) ) return $existing;
  //parse incoming
  if( is_string($new_data) ) $new_data = g365_data_string_array( $new_data );
  //if there isn't any existing data return what we have
  if( $existing === '' || $existing === null ) return $new_data;
  //parse exisitng data
  if( is_string($exisiting) ) $existing = g365_data_string_array( $existing );
  //integrate the new data into the existing
  foreach( $new_data as $key => $vals ) {
    if( !isset($existing[ $key ]) ) $existing[ $key ] = array();
    $existing[ $key ] = array_unique(array_merge(array_map(function($val_ids){ return intval($val_ids); }, $vals),$existing[ $key ]), SORT_REGULAR);
  }
  return $existing;
}

//player spotlight
function g365_player_spotlight_build( $atts ) {
  $player_spotlights_arr = g365_get_awards_featured(false, 4);
  $return_string = '';
  if( !empty($player_spotlights_arr) ) {
    $return_string = '<div class="grid-x grid-margin-x small-up-2 medium-up-4 text-center profile-feature profile-widget">';
    foreach( $player_spotlights_arr as $dex => $obj ) {
      $validate_img = g365_player_img_dir($obj->player_url, $obj->event_url, $obj->id); 
      $return_string .= '<div class="cell callout gray"><div class="gfont small-margin-bottom"><!-- callout gset --><h5 class="weight-bold no-margin-bottom">';
      $return_string .= '<a class="spotlight__card--heading" href="' . get_site_url() . '/player/' . $obj->player_url . '">' . ucwords(strtolower($obj->player)) . '</a>';
      $return_string .= '</h5><div class="relative small-margin-top small-margin-bottom"><!--<div class="award-title">Defense</div> -->';
      $return_string .= '<a href="' . get_site_url() . '/player/' . $obj->player_url . '">';
      $return_string .= '<img class="profile-image__player" src="' . $validate_img . '" alt="' . $obj->player . ' at ' . $obj->event . '" />';
      $return_string .= '</a></div>';
      $return_string .= '<a href="' . get_site_url() . '/event/' . $obj->event_url . '" target="_blank"><img class="profile-event-img tiny-margin-top" src="' . $obj->event_logo . '" alt="' . $obj->event . ' Logo" />' . $obj->event . '</a></div></div>';
    }
    $return_string .= '</div>';
  }
  return $return_string;
}
add_shortcode( 'g365_player_splotlight', 'g365_player_spotlight_build' );

//featured events /Upcoming Events on landing page
function g365_featured_events_build( $atts ) {
  $featured_events_arr = g365_display_events();
  $return_string = '';
  if( !empty($featured_events_arr) ) {
    $return_string = '<div class="grid-x small-up-2 medium-up-4 text-center profile-feature profile-widget">';
    foreach( $featured_events_arr as $dex => $obj ) {
      $return_string .= '<div class="cell"><div class="small-margin-bottom">';
      $return_string .= '<a href="' . $obj->link . '" target="_blank"><img class="spotlight__events--image" src="' . ((!empty($obj->logo_img)) ? $obj->logo_img : $default_event_img) . '" alt="' . $obj->name . ' official logo" />';
      $return_string .= '<p class="spotlight__events--text">' . (( empty($obj->short_name) ) ? $obj->name : $obj->short_name) . '<br><small class="tiny-margin-top block">' . g365_build_dates($obj->dates, 2) . '</small></p>';
      $return_string .= '</a></div></div>';
    }
    $return_string .= '</div>';
  }
  return $return_string;
}
add_shortcode( 'g365_featured_events', 'g365_featured_events_build' );

// Contact us span
function g365_sticky_contact_us_note() {
  return '<span class="sticky-note"><p>Contact Us</p><a href="' . get_site_url() . '/contact/" target="_blank"><img src="' . get_site_url() .'/wp-content/uploads/2021/03/contact-us.png"></a>
</span>';
}
add_shortcode( 'sticky_contact_us_note', 'g365_sticky_contact_us_note' );

//rankings snippet
function g365_rankings_snippet_build( $atts ) {
  $ranking_data = g365_build_ranking(48);
  $return_string = '';
  if( !empty($ranking_data->records) ) {
    $return_string = '<div class="grid-x grid-margin-x small-up-2 medium-up-3 table-data count-data team-tables-home">';
    foreach( $ranking_data->records as $dex => $rankings ) { if( $dex == 6 ) break;
      $return_string .= '<div class="cell"><h3>' . $rankings->ranking_type . '</h3><table><tbody>';
      foreach( $rankings->rankings as $sub_dex => $org_id ) { if( $sub_dex == 5 ) break;
        $return_string .= '<tr><td class="home-ranking__td"><a class="home-ranking__link" href="' . get_site_url() . '/club/' . ($ranking_data->org_records[$org_id]->org_url ?? '') . '"><img src="' . get_site_url() . '/wp-content/uploads/org-logos/' . ($ranking_data->org_records[$org_id]->org_logo ?? 'g365_blank-placeholder_400x300.png') . '" class="team-logo"> <span class="team-name">' . ($ranking_data->org_records[$org_id]->name ?? 'Name not found.') . '</span></a></td></tr>';
      }
      $return_string .= '</tbody></table></div>';
    }
    $return_string .= '</div>';
  }
  return $return_string;
}
add_shortcode( 'g365_rankings_snippet', 'g365_rankings_snippet_build' );


//serve ads when needed

function g365_start_ads( $pageID ){
// 	$pageID = ( $pageID === null ) ? $post->ID : $pageID;
	//see if we have any ads on this page
	$g365_page_ad = array(
		'title'	=> get_post_meta($pageID, 'ad_title', true),
		'link'	=> get_post_meta($pageID, 'ad_link', true),
		'img'		=> get_post_meta($pageID, 'ad_img', true),
		'element_type'	=> 'rotator'
	);
	//get site global ad settings
	$g365_site_ads = get_option( 'g365_display' );

  //if the page doesn't have an ad or the site global is empty, don't add to array
	$ad_info['go'] = ( empty($g365_page_ad['link']) || empty($g365_page_ad['img']) || empty($g365_site_ads) ) ? false : true;
	//general header banner ads GOOOO!
	if ( $ad_info['go'] ) {
		$ad_info = array(
			'go' => true,
			'ad_section_class'	=> ' no-padding-top',
			'ad_before'	=> '<div id="event_display_rotator" class="slick display-wrapper small-small-margin-bottom large-margin-bottom" role="region" aria-label="Upcoming Events">',
			'ad_content'	=> '',
			'ad_after'	=> '</div>'
		);
		//future functionality, ad treatment type
		$g365_site_ads['site_1']['element_type'] = 'rotator';
		$g365_site_ads['site_2']['element_type'] = 'rotator';
		//build rotator element for page ad
		$ad_info['ad_content'] .= g365_template_construction( $g365_page_ad );
		//if we have a global ad, build and add it
		if( !empty($g365_site_ads['site_1']['img']) ) $ad_info['ad_content'] .= g365_template_construction( $g365_site_ads['site_1'] );
		//if we have a secondary global ad, build if and add it along with another primary ad to help the flow
		if( !empty($g365_site_ads['site_2']['img']) ) {
			$ad_info['ad_content'] .= g365_template_construction( $g365_page_ad );
			$ad_info['ad_content'] .= g365_template_construction( $g365_site_ads['site_2'] );
		}
	}
	//on the front page, and if we have a global splash setting
	if( is_front_page() && !empty($g365_site_ads['site_3']) ) {
		//controls for the splash
		$g365_splash = array(
			'title'				=> ( empty($g365_site_ads['site_3']['title']) ) ? '' : $g365_site_ads['site_3']['title'],
			'link'				=> ( empty($g365_site_ads['site_3']['link']) ) ? '' : $g365_site_ads['site_3']['link'],
			'img'					=> ( empty($g365_site_ads['site_3']['img']) ) ? '' : $g365_site_ads['site_3']['img'],
			'img_mobile'	=> ( empty($g365_site_ads['site_3']['img_mobile']) ) ? '' : $g365_site_ads['site_3']['img_mobile'],
			'description'	=> ( empty($g365_site_ads['site_3']['description']) ) ? '' : $g365_site_ads['site_3']['description']
		);
		//if we have minimum info process the splash
		if( !empty($g365_splash['title']) && !empty($g365_splash['link']) && !empty($g365_splash['img']) ) {
			//build the splash html
			$ad_info['splash'] = '<div class="reveal text-center" id="g365_home_reveal" aria-labelledby="' . $g365_splash['title'] . '" data-reveal><div class="relative"><h1 id="reveal-title" class="show-for-sr">' . $g365_splash['title'] . '</h1>';
			//if there is a derscription, add it
			if( !empty($g365_splash['description']) ) $ad_info['splash'] .= '<p class="reveal-description">'. $g365_splash['description'] . '</p>';
			//if there is a mobile image, add it
			if( !empty($g365_splash['img_mobile']) ) $ad_info['splash'] .= '<p class="text-center show-for-small-only"><a href="' . $g365_splash['link'] . '"><img src="'. $g365_splash['img_mobile'] . '" alt="'. $g365_splash['title'] . '" title="'. $g365_splash['title'] . '" /></a></p>';
			//if there is a mobile image, add supporting class to the large image
			$mobile_img_class = ( empty($g365_splash['img_mobile']) ) ? '' : ' show-for-medium';
			//add main splash image
			$ad_info['splash'] .= '<p class="text-center' . $mobile_img_class . '"><a href="' . $g365_splash['link'] . '"><img src="'. $g365_splash['img'] . '" alt="'. $g365_splash['title'] . '" title="'. $g365_splash['title'] . '" /></a></p>';
			//finish up the reveal with the close button and closing div tags
			$ad_info['splash'] .= '<button id="reveal_close_today" class="close-button close-button-friend close-today" data-close aria-label="Close Splash Reveal for Today" type="button"><span aria-hidden="true">Close for Today</span></button> <button class="close-button" data-close aria-label="Close Splash Reveal" type="button"><span aria-hidden="true">&times;</span></button></div></div>';
		}
	}
	return $ad_info;
}

function g365_event_data_proc( $atts ) {
	$atts = shortcode_atts( array(
		'data_set' => '',
    'reg_days' => false
	), $atts, 'g365_event_data' );
  if( $atts['data_set'] === '' ) return '<p>Add Attributes.</p>';
  $data_sets = explode(',', $atts['data_set']);
  $product_event_schedule_link = intval(get_post_meta( get_the_ID(), '_event_link', true ));
  if( !empty($product_event_schedule_link) ) $event_object = g365_get_event_data( $product_event_schedule_link, true );
  if( empty( $event_object ) ) return;
  $data_compile = '';
  foreach( $data_sets as $dex => $type ){
    switch( $type ){
      case 'locations':
        $event_locations = explode('|', $event_object->locations);
        $data_compile .= '<h3>Location</h3><p class="no-margin-bottom">';
        if( empty($event_locations) ) {
          $data_compile .= 'TBD';
        } else {
          foreach( $event_locations as $loc_dex => $loc ){
            $data_compile .= '<a href="https://www.google.com/maps/search/' . preg_replace('/[^a-zA-Z0-9,]/', '+', $loc) . '/" target="_blank">' . $loc . '</a><br/>';
          }
        }                   
        $data_compile .= '</p>';
        break;
      case 'date':
        $data_compile .= '<h3>Dates</h3><p>';
        if( empty($event_object->dates) ) {
          $data_compile .= 'TBD';
        } else {
          $reg_days = $atts['reg_days'];
          $date_reg = g365_build_dates($event_object->dates, 2, false, $reg_days);
          if( $reg_days === false ) {
            $data_compile .= $date_reg;
          } else {
            $data_compile .= $date_reg[0];
            $data_compile .= ( empty($date_reg[1]) ) ? '' : '<h3>Registration Deadline</h3><p><strong>' . $date_reg[1] . '</strong><small class="block"><em>' . $reg_days . ' days prior to tournament.</em></small></p>';
          }
        }
        $data_compile .= '</p>';
        break;
      case 'full_address':
        if( empty($event_object->short_locations) ) {
          $data_compile .= '';
        } else {
          $reg_days = $atts['reg_days'];
          $date_reg = g365_build_dates($event_object->short_locations, 2, false, $reg_days);
          if( $reg_days === false ) {
            $data_compile .= $date_reg;
          } else {
            $data_compile .= $date_reg[0];
            $data_compile .= ( empty($date_reg[1]) ) ? '' : '<h3>Registration Deadline</h3><p><strong>' . $date_reg[1] . '</strong><small class="block"><em>' . $reg_days . ' days prior to tournament.</em></small></p>';
          }
        }
        $data_compile .= '</p>';
        break;
    }
  }
	return $data_compile;
}
add_shortcode( 'g365_event_data', 'g365_event_data_proc' );


/*
Plugin Name: Extend Blocks
*/

function guten_extend_enqueue() {
  wp_enqueue_script( 'guten-extend_blocks',
    get_stylesheet_directory_uri() . '/inc/guten-extend_blocks.js',
    array( 'wp-blocks')
  );
}
add_action( 'enqueue_block_editor_assets', 'guten_extend_enqueue' );

//add functionality for player profile control

//create hhh insert database **2694
function hhh_scouting_custom_function_after_purchase($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);

    // Get the user ID who placed the order
    $user_id = $order->get_user_id();
  
    // Prepare an array to hold product information
    $product_info_array = array();

    // Loop through order items
    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        
        // Get the product object
        $product = wc_get_product($product_id);
      
        // Get post meta data for the product
        $custom_field_value = get_post_meta($product_id, '_event_link', true);

        // Build an array with product information
        $product_info[] = array(
            'Product Name' => $product->get_name(),
            'Product SKU' => $product->get_sku(),
            'Product Price' => wc_price($product->get_price()),
            'Custom Field Value' => $custom_field_value,
            // Add more information as needed
        );
      
        // Add the product information to the array
        $product_info_array[] = $product_info;
      

        // Your custom code here 
        //to test dev site change product_id to 84376
        // Example: Check if the product ID matches a specific target product
        if (($product_id == 92793 || $product_id == 84376) && $custom_field_value == 832) {
          hhh_scouting_report_access($product_id, $user_id, $custom_field_value);
            // Your custom logic here
            // This code will be executed when the specified product is purchased
        }
    }
  
    // Convert the PHP array to a JSON string
    $product_info_json = json_encode($product_info_array);

    // Add a script to the HTML output to log to the console
//     echo '<script>';
//     echo 'console.log( here here: ' . $product_info_json . ');';
//     echo '</script>';
  
  
}


add_action('woocommerce_order_status_completed', 'hhh_scouting_custom_function_after_purchase');





add_action('woocommerce_thankyou', 'custom_redirect_after_purchase');

function custom_redirect_after_purchase($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);
  
    // Get the user ID who placed the order
    $user_id = $order->get_user_id();
  
    // Prepare an array to hold product information
    $product_info_array = array();

    // Loop through order items
    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        
        // Get the product object
        $product = wc_get_product($product_id);
      
        // Get post meta data for the product
        $custom_field_value = get_post_meta($product_id, '_event_link', true);

        // Build an array with product information
        $product_info[] = array(
            'Product Name' => $product->get_name(),
            'Product SKU' => $product->get_sku(),
            'Product Price' => wc_price($product->get_price()),
            'Custom Field Value' => $custom_field_value,
            // Add more information as needed
        );
      
        // Add the product information to the array
        $product_info_array[] = $product_info;
      

        // Your custom code here 
        //to test dev site change product_id to 84376
        // Example: Check if the product ID matches a specific target product
        if ( ($product_id == 92793|| $product_id == 84376) && $custom_field_value == 832) {
            $site_url = get_site_url();
            if (strpos($site_url, 'https://dev.sportspassports.com') !== false) {
                // You are on the development site
                $redirect_url = 'https://dev.sportspassports.com/hhh-scouting-services-3/';
                echo 'This is a development site.';
            } else {
                // You are on the live site
                $redirect_url = 'https://sportspassports.com/hype-her-hoops-report/';
                echo 'This is the live site.';
            }


            // Perform the redirect
            wp_redirect($redirect_url);
        }
    }
}

// add_action('woocommerce_order_status_changed', 'custom_function_on_order_failed', 10, 4);

// function custom_function_on_order_failed( $new_status, $order) {
//   echo "<script>console.log('Order status changed to failed.');</script>";
//     if ($new_status === 'failed') {
//         // Your custom code here
//          global $wpdb;
//         $wpdb_orgs = $wpdb->g365_orgs;
//         $wpdb_rosters = $wpdb->g365_rosters;
//         $wpdb_events = $wpdb->g365_events;
//         $wpdb_teams = $wpdb->g365_teams;
//         $wpdb_players = $wpdb->g365_players;
//         $wpdb_stats = $wpdb->g365_stats;
//         $wpdb_stats = $wpdb->g365_stats;
//         $order_data_meta = get_post_meta( $order->get_id(), '_order_data', true );
//         $pssprt_id = explode("," , $order_data_meta);
        
//         $query = $wpdb->prepare(
//              "UPDATE $wpdb->g365_stats
//               SET stats = JSON_REMOVE(
//                 stats,
//                 CONCAT('$.seasons.\"', JSON_UNQUOTE(JSON_EXTRACT(stats, '$.seasons.*.order_id')), '\"')
//               )
//               WHERE id = %d AND JSON_CONTAINS(stats, %s, '$.seasons.*.order_id')",
//               $pssprt_id, // Placeholder for the ID
//               $order_id // Placeholder for the order ID
//         );

//         $result = $wpdb->query($query);  
      
      
      
//     }
// }

//password restrictions
// function custom_password_requirements( $errors, $user ) {
//     $min_length = 8;

//     if (strlen($_POST['pass1']) < $min_length) {
//         $errors->add('password_too_short', sprintf('<strong>Error</strong>: Password must be at least %d characters.', $min_length));
//     }

//     if (!preg_match('/[A-Z]/', $_POST['pass1'])) {
//         $errors->add('password_no_upper', '<strong>Error</strong>: Password must contain at least one uppercase letter.');
//     }

//     if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $_POST['pass1'])) {
//         $errors->add('password_no_special', '<strong>Error</strong>: Password must contain at least one special character.');
//     }

//     return $errors;
// }
// add_filter('registration_errors', 'custom_password_requirements', 10, 2);
// add_filter('user_profile_update_errors', 'custom_password_requirements', 10, 2);

/*
Control capabilities for custom roles
*/
// add_action( 'set_user_role', function( $user_id, $role, $old_roles ) {
//   $user = get_user_by('id', $user_id);
//   if( in_array('cps_moderator', $old_roles) && $role !== 'cps_moderator' ) $user->remove_cap( 'cps_mod' );
// }, 10, 3 );

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    $fields['billing']['billing_first_name']['placeholder'] = 'First Name*';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last Name*';
    $fields['billing']['billing_address_1']['placeholder'] = 'Street Address*';
    $fields['billing']['billing_address_1']['class'] = 'maps-autocomplete';
    $fields['billing']['billing_address_2']['required'] = false;
    $fields['billing']['billing_city']['placeholder'] = 'City*';
    $fields['billing']['billing_city']['class'] = 'maps-autocomplete-city';
    $fields['billing']['billing_postcode']['placeholder'] = 'Zip Code*';
    $fields['billing']['billing_postcode']['class'] = 'maps-autocomplete-zip';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone Number*';
    $fields['billing']['billing_phone']['class'] = 'maps-autocomplete-phone';
    $fields['billing']['billing_email']['placeholder'] = 'Email*';
    $fields['billing']['billing_state']['placeholder'] = 'State*';
    $fields['billing']['billing_state']['class'] = 'maps-autocomplete-state';
    return $fields;
}

// // Set current date time as custom item data
// add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data_timestamp', 10, 3 );
// function add_cart_item_data_timestamp( $cart_item_data, $product_id, $variation_id ) {
//     // Set the shop time zone (List of Supported Timezones: https://www.php.net/manual/en/timezones.php)
//     date_default_timezone_set( 'America/Los_Angeles' );

//     $cart_item_data['timestamp'] = strtotime( date('Y-m-d h:i:s') );

//     return $cart_item_data;
// }

// // Empty cart after 2 hours
// add_filter( 'template_redirect', 'empty_cart_after_3_days' );
// function empty_cart_after_3_days(){
//     if ( WC()->cart->is_empty() ) return; // Exit

//     // Set the shop time zone (List of Supported Timezones: https://www.php.net/manual/en/timezones.php)
//     date_default_timezone_set( 'America/Los_Angeles' );

//     // Set the threshold time in seconds (2 hours in seconds)
//     $threshold_time  = 2 * 60 * 60;

//     $cart_items      = WC()->cart->get_cart(); // get cart items
//     $cart_items_keys = array_keys($cart_items); // get cart items keys array
//     $last_item       = end($cart_items); // Last cart item
//     $last_item_key   = end($cart_items_keys); // Last cart item key
//     $now_timestamp   = strtotime( date('Y-m-d h:i:s') ); // Now date time

//     if( isset($last_item['timestamp']) && ( $now_timestamp - $last_item['timestamp'] ) >= $threshold_time ) {
//         WC()->cart->empty_cart(); // Empty cart
//     }
// }


// functions.php
function load_page_content_by_id($atts) {
    $atts = shortcode_atts(array(
        'id' => null,
    ), $atts, 'load_page');

    if (!isset($atts['id'])) {
        return 'No page ID provided.';
    }

    $page = get_post($atts['id']);
    if ($page) {
        return apply_filters('the_content', $page->post_content);
    } else {
        return 'Page not found.';
    }
}
add_shortcode('load_page', 'load_page_content_by_id');


///////////// under is new updated function 
//try making j players grab here.
function hhh_player_info_build( $player_info = null){
//   $pl_inf = spp_fn(['fn_name'=>'spp_grab_info', 'arguments'=>['plyer_info', ['target_url'=>get_site_url(), 'season' => 'frnt_page']]]); //this should grab the function message.....hopefully.
  $pl_inf = hhh_grab_info("plyer_info", ['season' => 'frnt_page']);
  $pl_inf = json_decode(json_encode($pl_inf), true);
//   echo '<br> ' . $pl_inf['message'] . ' <br><br> herehere' . print_r($pl_inf['query_result']);
  $return_string = '';
  
  if (!isset($pl_inf['query_result']) || !is_array($pl_inf['query_result'])) {
        return '<p>No results found.</p>';
  }
    
  // Group the results by brand
    $grouped_results = [];
    foreach ($pl_inf['query_result'] as $result) {
        $brand = $result['event_brand'];
        $event_name = $result['event_name'];
      
        if (!isset($grouped_results[$brand])) {
            $grouped_results[$brand] = [];
        }
        if (!isset($grouped_results[$brand][$event_name])) {
          $grouped_results[$brand][$event_name] = [];
        }
        $grouped_results[$brand][$event_name][] = $result;
    }
  
    // Sort each brand's results by event time
//     foreach ($grouped_results as $brand => &$events_by_name) {
//       foreach ($events_by_name as &$events) {
//         usort($events, function($a, $b) {
//             return strtotime($a['event_time']) - strtotime($b['event_time']);
//         });
//       }  
//     }
//     unset($events); // Break reference to last array element
//     echo '<pre>' . print_r($grouped_results, true) . '</pre>';
    $return_string = '
      <ul class="grid-y eval-list eval-list--players">
        <h2>Players</h2>';
      
    foreach ($grouped_results as $brand => $events_by_name) {
      $count = 0;
      foreach ($events_by_name as $event_name => $events) {
        foreach ($events as $result) {
            if ($count >= 4) break; // Limit to 4 evaluations per brand
//             echo ('<script>console.log(' . $count . ');</script>');
            $event_time = esc_html($result['event_time']);
            $date = new DateTime($event_time);
            $year = $date->format('Y');
            $stat_trends = json_decode($result['stat_trends'], true);
            $event = $result['event_connected'];
            $display = $stat_trends['hhh_front_page'];
            $ssEval = $stat_trends['ss_evaluation'];
            $ssOrg = $result['event_brand'];
            $player_class = $result['player_class'];
        
            if ($display === "False" || empty($display)) {
                continue;
            }

            $org_search = $result['org_name'];
            if (str_contains($org_search, ',')) {
                $cleanOrgName = explode(",", $org_search);
                $org_search = $cleanOrgName[1];
            }
            $event_short_name = $result['event_short'] ?? '';

            $return_string .= '<li class="eval-listItem">
                <div class="grid-x grid-y-small">
                    <img src="' . (!empty(esc_html($result['player_image'])) ? 'https://sportspassports.com/wp-content/uploads/player-profiles/' . esc_html($result['player_image']) : 'https://dev.sportspassports.com/wp-content/uploads/event-profiles/' . 'g365_profile_placeholder.gif') . '" alt="" class="">
                </div>
                <div class="eval-full-text">
                    <div class="eval-info">
                        <h3>' . esc_html($result['player_name']) . '<span class="hide-for-small-only"> | ' . (!empty($player_class) ? esc_html($player_class) : esc_html($year)) . ' | ' . (!empty($org_search) ? esc_html($org_search) : esc_html($event_short_name)) . '</span></h3>
                        <p class="show-for-small-only">' . (!empty($player_class) ? esc_html($player_class) : esc_html($year)) . ' </p>
                        <p class="player-event show-for-small-only" data-event-brand="' . esc_html($result['event_brand']) . '" data-full-event-name="' . esc_html($result['event_name']) . '">' . (!empty($org_search) ? esc_html($org_search) : esc_html($event_short_name)) . ' </p>
                    </div>
                    <p class="eval-body">' . ((!empty($ssEval)) ? $ssEval : $result['player_eval']) . ' </p>
                </div>
                <button class="read-eval-btn">read more</button>
            </li>';

          $count++;
          
        }
      }
  }
      $return_string .= ' </ul>';

    
  
  
  return $return_string;
}
add_shortcode( 'hhh_player_info', 'hhh_player_info_build' );


?>