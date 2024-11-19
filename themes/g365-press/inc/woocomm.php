<?php

// print script handles
// function head_scripts_handle() {
//     global $wp_scripts;
//     foreach( $wp_scripts->queue as $handle ) :
//         echo $handle,' ';
//     endforeach;
// }
// add_action( 'wp_print_scripts', 'head_scripts_handle' );
// wc-add-to-cart woocommerce wc-cart-fragments wc-chase-paymentech jquery foundation js-all
// wc-add-to-cart wc-cart selectWoo wc-password-strength-meter wc-checkout woocommerce wc-cart-fragments wc-chase-paymentech jquery foundation js-all


//all woocommerce modifications

//declare theme support for woocommerce
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'woocommerce_support' );

//see if woocommerce is installed
if ( function_exists( 'is_woocommerce' ) ) {
  
  //add multiple products to cart with url
  class add_more_to_cart {

    private $prevent_redirect = false; //used to prevent WC from redirecting if we have more to process

    function __construct() {
      if ( ! isset( $_REQUEST[ 'add-more-to-cart' ] ) ) return; //don't load if we don't have to
      $this->prevent_redirect = 'no'; //prevent WC from redirecting so we can process additional items
      add_action( 'wp_loaded', [ $this, 'add_more_to_cart' ], 21 ); //fire after WC does, so we just process extra ones
      add_action( 'pre_option_woocommerce_cart_redirect_after_add', [ $this, 'intercept_option' ], 9000 ); //intercept the WC option to force no redirect
    }

    function intercept_option() {
      return $this->prevent_redirect;
    }

    function add_more_to_cart() {
      $product_ids = explode( '|', $_REQUEST['add-more-to-cart'] );
      $count = count( $product_ids );
      $number = 0;

      foreach ( $product_ids as $product_id ) {
        $quantity = 1;
        if ( ++$number === $count ) $this->prevent_redirect = false; //this is the last one, so let WC redirect if it wants to.
        if (stripos($product_id, ',') !== false) { //get var_ids and quantities if we have them
          $product_data = explode(',', $product_id);
          if( count($product_data) > 2 ) continue;
          $product_id = $product_data[0];
          $quantity = $product_data[1];
        }
        $_REQUEST['add-to-cart'] = $product_id; //set the next product id
        $_REQUEST['quantity'] = $quantity; //set the next product quantity
        WC_Form_Handler::add_to_cart_action(); //let WC run its own code
      }
    }
  }
  new add_more_to_cart;

  //remove extra image sizes
  add_action('init', 'remove_plugin_image_sizes');
  function remove_plugin_image_sizes() {

    wp_register_script( 'jqueryUI', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), '1.12.1', true );

    remove_image_size('woocommerce_gallery_thumbnail');
    remove_image_size('woocommerce_thumbnail');
    remove_image_size('shop_thumbnail');
    remove_image_size('woocommerce_single');
    remove_image_size('shop_catalog');
    remove_image_size('shop_single');
  }
	
	//woocommerce wrappers
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
	function g365_wrapper_start() {
		echo '<section id="content" class="grid-x site-main woocomm-wrap" role="main">';
		echo '<div class="cell small-12 large-padding">';
	}
	function g365_wrapper_end() {
		echo '</div>';
		echo '</section>';
	}
	add_action('woocommerce_before_main_content', 'g365_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'g365_wrapper_end', 10);

	//unhook woocommerce css
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	//unhook woocommerce js if not on a woocommerce page
	function woocommerce_remove_frontend_scripts() {
    if ( !is_product() && !is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() ) {
			remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
			remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
			remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
		}
	}
	add_action( 'wp', 'woocommerce_remove_frontend_scripts', 99 );

	//if we need to grab certain files for any reason
	// wp_dequeue_style( 'woocommerce_fancybox_styles' );
	// wp_dequeue_style( 'woocommerce_chosen_styles' );
	// wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	// wp_dequeue_script( 'wc_price_slider' );
	// wp_dequeue_script( 'wc-single-product' );
	// wp_dequeue_script( 'wc-add-to-cart' );
	// wp_dequeue_script( 'wc-cart-fragments' );
	// wp_dequeue_script( 'wc-checkout' );
	// wp_dequeue_script( 'wc-add-to-cart-variation' );
	// wp_dequeue_script( 'wc-single-product' );
	// wp_dequeue_script( 'wc-cart' );
	// wp_dequeue_script( 'wc-chosen' );
	// wp_dequeue_script( 'woocommerce' );
	// wp_dequeue_script( 'prettyPhoto' );
	// wp_dequeue_script( 'prettyPhoto-init' );
	// wp_dequeue_script( 'jquery-blockui' );
	// wp_dequeue_script( 'jquery-placeholder' );
	// wp_dequeue_script( 'fancybox' );
	// wp_dequeue_script( 'jqueryui' );
	
	//change default image sizes
	function g365_woocommerce_image_dimensions() {
		global $pagenow;

		if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}
		$catalog = array(
			'width' 	=> '400',	// px
			'height'	=> '300',	// px
			'crop'		=> 1 		// true
		);
		$single = array(
			'width' 	=> '600',	// px
			'height'	=> '450',	// px
			'crop'		=> 1 		// true
		);
		$thumbnail = array(
			'width' 	=> '200',	// px
			'height'	=> '150',	// px
			'crop'		=> 0 		// false
		);
		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
	add_action( 'after_switch_theme', 'g365_woocommerce_image_dimensions', 1 );

	//remove the breadcrumb (on product pages)
	function g365_remove_wc_breadcrumbs() {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}
	add_action( 'init', 'g365_remove_wc_breadcrumbs' );

	//remove coupon from checkout page/section
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
  //remove login prompt before checkout and move it to before cart since they are on the same page.
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
  add_action( 'g365', 'woocommerce_checkout_login_form' );

	//remove the 'added to cart' message on the cart page
	add_filter( 'wc_add_to_cart_message_html', '__return_null' );

	//change checkout button text
	function g365_place_order_button_text() { return __( 'Complete Registration', 'woocommerce' ); }
	add_filter( 'woocommerce_order_button_text', 'g365_place_order_button_text' );

  //change empty cart return link
  function wc_empty_cart_redirect_url() {
    return '/';
  }
  add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

  //change add to cart button text
	function g365_woocommerce_product_add_to_cart_text(){
		return 'Register Now';
	}
	add_filter( 'woocommerce_product_add_to_cart_text' , 'g365_woocommerce_product_add_to_cart_text' );
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'g365_woocommerce_product_add_to_cart_text' );
	add_filter( 'woocommerce_booking_single_add_to_cart_text', 'g365_woocommerce_product_add_to_cart_text' );
	
  //add support for gutenberg blocks on products
  function wplook_activate_gutenberg_products($can_edit, $post_type){
    if($post_type == 'product') $can_edit = true;
	  return $can_edit;
  }
  add_filter('use_block_editor_for_post_type', 'wplook_activate_gutenberg_products', 10, 2);
  
  function redefine_subscriber_role() {
      remove_role('subscriber'); // Remove the subscriber role

      // Add the subscriber role with custom capabilities
      add_role( 'subscriber', 'Subscriber', array( 'read' => true, 'customer' => true, 'data_editor' => true ) );
  }
  add_action('init', 'redefine_subscriber_role');
  
  //my account add profile edit section
  add_filter ( 'woocommerce_account_menu_items', 'g635_account_customization' );
  function g635_account_customization( $menu_links ){
    if( current_user_can('front_editor') ) {
//       $profile_links = array( 'g365-data' => 'Your Data' );
      if( current_user_can('cps_moderator') ) $profile_links[ 'cps_mod' ] = 'CPS Player Editor';
      if( current_user_can('event_moderator') ) $profile_links[ 'event_mod' ] = 'Event Player Editor';
      if( current_user_can('score_keeper') ) $profile_links[ 'scor_keep' ] = 'Score Keeper Tools';
//       if( current_user_can('stat_keeper') ) $profile_links[ 'stat_keep' ] = 'Stat Keeper Tools';
      
      //if this is a stat_keeper, just give them a logout button
      if( current_user_can('stat_keeper') ) return array_slice( $menu_links, -1, NULL, true );
      //if this is a stat_vip, just give them a logout button
      if( current_user_can('stat_vip') ) return array_slice( $menu_links, -1, NULL, true );
      //get rid of everything but the dashboard and logout
      $menu_links = $profile_links + array_slice( $menu_links, -1, NULL, true );
    } elseif ( current_user_can( 'data_editor' ) ) {
      //add the profiles after the dashboard and any other links we need
      if( current_user_can('player_editor') || current_user_can('subscriber') ) {
          $profile_links[ 'player_editor' ] = 'Player Editor';
          $profile_links[ 'player-photos' ] = 'Player Photos';
          $profile_links[ 'player-videos' ] = 'Player Videos';
      }
      if( current_user_can('coach') ) $profile_links[ 'coach' ] = 'Coach Editor';
      if( current_user_can('club') ) $profile_links[ 'club' ] = 'Manage Club';
      if( current_user_can('rosters') ) {
        //see if we have a org id otherwise leave blank
        $org_id = filter_input( INPUT_GET, 'org_id', FILTER_SANITIZE_NUMBER_INT );
        if( empty($org_id) ) {
          echo '<p  id="directorTag" class="hidden"></p>';
          $profile_links[ 'rosters' ] = 'Manage Rosters';
//           $profile_links[ 'rosters_all' ] = 'Submit Rosters';
//           $profile_links[ 'director_features' ] = 'Features';
        } else {
          echo '<p  id="directorTag" class="hidden"></p>';
          $profile_links[ 'rosters/?org_id=' . $org_id ] = 'Manage Rosters';
//           $profile_links[ 'rosters_all/?org_id=' . $org_id ] = 'Submit Rosters';
//           $profile_links[ 'director_features' ] = 'Features';
        }
      }
      if( current_user_can('college_coach') || current_user_can('administrator') ) $profile_links[ 'dcp' ] = 'Digital Coach Packets';
//       if( true ) $profile_links[ 'ros_ev' ] = 'Rosters by Event';
      $menu_links = array_slice( $menu_links, 0, 1, true ) + $profile_links + array_slice( $menu_links, 1, NULL, true );
    }
    if( current_user_can('administrator') ){ $profile_links[ 'dcp' ] = 'Digital Coach Packets';
      $menu_links = array_slice( $menu_links, 0, 1, true ) + $profile_links + array_slice( $menu_links, 1, NULL, true );
    }
    return $menu_links;
  }
  //my account g365 data edit
  add_action( 'init', 'g365_data_edit_endpoint' );
  function g365_data_edit_endpoint() {
    //so we can add a page
    add_rewrite_endpoint( 'g365-data', EP_PAGES );
    add_rewrite_endpoint( 'rosters', EP_PAGES );
    add_rewrite_endpoint( 'rosters_all', EP_PAGES );
    add_rewrite_endpoint( 'cps_mod', EP_PAGES );
    add_rewrite_endpoint( 'event_mod', EP_PAGES );
    add_rewrite_endpoint( 'scor_keep', EP_PAGES );
    add_rewrite_endpoint( 'stat_keep', EP_PAGES );
    add_rewrite_endpoint( 'player_editor', EP_PAGES );
    add_rewrite_endpoint( 'coach', EP_PAGES );
    add_rewrite_endpoint( 'club', EP_PAGES );
    add_rewrite_endpoint( 'player-photos', EP_PAGES );
    add_rewrite_endpoint( 'player-videos', EP_PAGES );
    add_rewrite_endpoint( 'director_features', EP_PAGES );
  }
  //Add account data edit
  add_action('init', 'dcp_data_edit_endpoint');
  function dcp_data_edit_endpoint(){
    add_rewrite_endpoint( 'dcp', EP_PAGES );
  }
  //My account edit data page
  add_action('woocommerce_account_dcp_endpoint', 'dcp_data_endpoint_content');
  function dcp_data_endpoint_content(){
    g365_dir_render('digital-coach-packets', 'home', get_current_user_id(), $arg = null);
  }  
  //my account g365 edit data page
  add_action( 'woocommerce_account_g365-data_endpoint', 'g365_data_endpoint_content' );
  function g365_data_endpoint_content() { ?>
    <div class="cell small-12 medium-8 large-6">
      <h1>Your Data</h1>
    <?php
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    if( $user_g365 === '' || $user_g365[0] === '' || !is_array($user_g365) ) {
      echo '<p class="xlarge-margin-top xlarge-margin-bottom">Please add some info to the site and you can view/edit it here!</p>';
    } else {
      $user_g365_keys = array_keys($user_g365);
      $item_string = array_map( function($key, $ids){ return $key . ':::' . implode(':::', $ids); }, $user_g365_keys, $user_g365 );
      $item_string = implode('|', $item_string);
      ?>
      <div>
        <script type="text/javascript">
          var g365_form_details = {
            "items" : {
              <?php
              foreach( $user_g365_keys as $val ) {
                switch( $val ) {
                  case 'pl_ed': ?>
              "Players":{
                "name":"",
                "title":"Player Editor",
                "type":"pl_ed",
                "items":{}
              },
                  <?php
                  break;
                  case 'og_ed':
                    global $wpdb;
                    $wpdb_rosters = $wpdb->g365_rosters;
                    $org_ids = implode(',', $user_g365['og_ed']);
                    $roster_ids = $wpdb->get_results("SELECT id FROM $wpdb_rosters WHERE org IN ($org_ids) AND event = 0;", OBJECT_K );
                    if( !empty($roster_ids) ) {
                      $item_string = 'ro_ed:::' . implode(':::', array_keys($roster_ids)) . '|' . $item_string;
                    ?>
              "Rosters":{
                "name":"",
                "title":"Roster Editor",
                "type":"ro_ed",
                "items":{}
              },
                    <?php
                    }
                    ?>
              "Clubs":{
                "name":"",
                "title":"Club Editor",
                "type":"og_ed",
                "items":{}
              },
                    <?php
                    break;
                }
              }
              ?>
            },
            "wrapper_target" : "g365_form_options_anchor",
            "user_org": "<?php echo get_user_meta($current_user->ID, '_default_org', true); ?>",
            "admin_key": "<?php echo g365_make_admin_key(); ?>"
          };
        </script>
        <div>
        <div id="g365_form_options_anchor" data-g365_type="<?php echo $item_string; ?>"></div>
        </div>
      </div>
    <?php
    }
    ?>
      <hr class="xlarge-margin-top">
      <p class="button-group expanded">
        <a href="<?php echo site_url(); ?>/register/player-certification/" class="button">Add Player</a>
        <a href="<?php echo site_url(); ?>/register/club-teams/" class="button">Add Team/Roster</a></p>
    </div>
    <?php
  }
  //my account g365 edit data page
  add_action( 'woocommerce_account_player_editor_endpoint', 'player_editor_endpoint_content' );
  function player_editor_endpoint_content() { ?>
    <div class="cell small-12 medium-8 large-6">
<!--       <h1>Player Manager</h1> -->
    <?php
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    if( !empty($user_g365) && isset($user_g365['pl_ed']) && !empty($user_g365['pl_ed']) ) {
      ?>
      <div>
        <script type="text/javascript">
          var g365_form_details = {
            "items" : {
              "Players":{
                "name":"",
                "title":"Player Editor",
                "type":"pl_ed",
                "items":{}
              }
            },
            "wrapper_target" : "g365_form_options_anchor",
            "admin_key": "<?php echo g365_make_admin_key(); ?>"
          };
        </script>
        <div>
        <div id="g365_form_options_anchor" data-g365_type="pl_ed:::<?php echo implode(':::', $user_g365['pl_ed']); ?>"></div>
        </div>
      </div>
    <?php
    } else {
      echo '<p class="xlarge-margin-top xlarge-margin-bottom">Please add some info to the site and you can view/edit it here!</p>';
    }
    ?>
      <hr class="xlarge-margin-top">
      <p class="button-group expanded">
        <a href="<?php echo site_url(); ?>/register/player-certification/" class="button">Add Player</a>
    </div>
    <?php
  }
  //NEW ADD my account g365 player images
  add_action( 'woocommerce_account_player-photos_endpoint', 'player_photos_endpoint_content' );
  function player_photos_endpoint_content(){ echo filepond_core()['core']; echo filepond_core()['ext']; g365_dir_render('photo-upload', 'user-upload', get_current_user_id(), $arg = null); }
  //END NEW ADD
  // Player video
  add_action( 'woocommerce_account_player-videos_endpoint', 'player_videos_endpoint_content' );
  function player_videos_endpoint_content(){ echo filepond_core()['core']; echo filepond_core()['ext']; g365_dir_render('photo-upload', 'user-upload', get_current_user_id(), $arg = null); }
//   director features
  add_action( 'woocommerce_account_director_features_endpoint', 'director_features_endpoint_content' );
  function director_features_endpoint_content(){ 
    $current_user = wp_get_current_user();
    g365_dir_render('api','get-api-slb-template', '', ['user_data'=>$current_user->ID]);
  }
  //my account g365 edit data page
  add_action( 'woocommerce_account_stat_keep_endpoint', 'stat_keep_endpoint_content' );
  function stat_keep_endpoint_content() {
    ?>
    <div class="cell small-12 medium-8 large-6 stat-keep-woocomm">
    <?php
     if( current_user_can('stat_keeper') ){
       echo "<style>#masthead, .nav-spacer { display: none; }</style>";
     }
    //if we have a game id, go directly to the stat recording page
    $ev_game = filter_input( INPUT_GET, 'ev_game', FILTER_SANITIZE_NUMBER_INT );
    if( !empty($ev_game) ) {
      //record some stats, get the game and event data
      $ev_game = g365_get_game( $ev_game );
      if( !empty($ev_game) ) {
//         echo '<pre>';
//         print_r($ev_game);
//         echo '</pre>';
        $ev_data = g365_get_event_data( $ev_game->event_id, true );
        if( !empty($ev_data) ) {
          //switch up the js
          wp_dequeue_script( 'js-all' );
          wp_enqueue_script( 'jqueryUI' );
          wp_enqueue_script( 'js-g365-all-front-admin' );
          $admin_string = ', "g365_admin" : "false"';
          $current_user = wp_get_current_user();
          $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
          $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
          ?>
          <div class="grid-container grid-x grid-margin-x callout white tiny-padding tiny-margin-bottom">
              <div class="cell small-12 large-auto">EVENT: <strong><?php echo $ev_data->name;?></strong></div>
              <div class="cell small-12 large-shrink">START TIME: <strong><?php echo date_format(date_create($ev_game->start_time), 'M d Y g:i A');?></strong></div>
          </div>
          <div class="grid-container grid-x grid-margin-x callout white tiny-padding tiny-margin-bottom">
              <div class="cell small-12 large-auto">LOCATION: <strong><?php echo $ev_game->location;?></strong></div>
              <div class="cell small-12 large-shrink">COURT: <strong><?php echo $ev_game->court;?></strong></div>
          </div>
          <div class="text-center grid-container grid-x grid-margin-x callout gray tiny-padding tiny-margin-bottom">
            <div class="cell small-12 large-4">
              <img src="/wp-content/uploads/org-logos/<?php echo ((empty($ev_game->home_profile_img)) ? 'g365_blank-placeholder_400x300.png' : $ev_game->home_profile_img); ?>" >
              <h2>HOME</h2>
            </div>
            <div class="cell small-12 large-4">
              <span class="large-font-size medium-margin-top tiny-margin-bottom block hide-for-small-only">VS</span>
              <div class="grid-container">
                <div class="game_score grid-x grid-margin-x medium-padding small-padding-sides" id="game_score">
                  <div class="medium-6 cell">
                    <h3 class="home_team_score no-margin-bottom"><?php echo ((empty($ev_game->home_team_score)) ? '0' : $ev_game->home_team_score); ?></h3>
                  </div>
                  <div class="medium-6 cell">
                    <h3 class="away_team_score no-margin-bottom"><?php echo ((empty($ev_game->away_team_score)) ? '0' : $ev_game->away_team_score); ?></h3>
                  </div>
                </div>
              </div>
            </div>
            <div class="cell small-12 large-4">
             <img src="/wp-content/uploads/org-logos/<?php echo ((empty($ev_game->away_profile_img)) ? 'g365_blank-placeholder_400x300.png' : $ev_game->away_profile_img); ?>" >
              <h2>AWAY</h2>
            </div>
          </div>
          <div class="text-center grid-container grid-x grid-margin-x callout gray tiny-padding tiny-margin-bottom" id="ribbon_clock_btn">
            <div class="cell small-12 large-12">
              <h2>Game Clock</h2>
              <input type="button" class="button" value="1st Half" id="cd_first_half" />
              <input type="button" class="button" value="2nd Half" id="cd_second_half" />
              <input type="button" class="button" value="OT" id="cd_ot" />
            </div>
            <div class="clock_action_button cell small-12 large-12">
              <span id="cd_h">00</span>:<span id="cd_m">00</span>:<span id="cd_s">00</span>
            </div>
            <div class="cell small-12 large-12">
              <input type="button" class="button" value="Start" id="cd_start" />
              <input type="button" class="button" value="Pause" id="cd_pause" />
              <input type="button" class="button" value="Reset" id="cd_reset" />
            </div>
              <div class="set_time_and_status cell small-12 large-12">
                <input type="text" value="" id="cd_minutes" placeholder="Enter minute:second(15:10)"/>
               <span id="cd_status"></span>
              </div>
          </div>
          <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Take game stats", "title" : "", "type" : "gm_st", "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>
          <div id="g365_form_options_anchor" data-g365_type="gm_st:::<?php echo $ev_game->id; ?>"></div>
          
          <?php
        } else {
          echo '<p>Event data not found.</p>';
        }
      } else {
        echo '<p>Game data not found.</p>';
      }
    } else {
      //if we have an event id, we are trying to build the courts accordion
      $ev_id = filter_input( INPUT_GET, 'ev_id', FILTER_SANITIZE_NUMBER_INT );
      if( !empty($ev_id) ) {
        //pull the event data
        $ev_data = g365_get_event_data( $ev_id, true );
        echo '<h3>' . $ev_data->name . '</h3>';
        //check if we have multiple locations
        $ev_locations = explode('|', $ev_data->locations);
        //see if we have a location set
        $ev_loc = filter_input( INPUT_GET, 'ev_loc', FILTER_SANITIZE_STRING );
        // Set location name
        $ev_loc_name = str_replace('_', ' ', $ev_loc);
        echo '<h5>' . $ev_loc_name . '</h5>';
        //if the location is set or there is only one location, print the court list
        if(  empty($ev_locations[1]) || !empty($ev_loc) ) {
          //print the appropriate courts in an accordion
          if( !empty($ev_loc) ) $ev_locations[0] = $ev_loc;
          //get the game data
          $ev_games = g365_get_games( $ev_id, $ev_locations[0] );
          //if we have games, show them, else error message.
          if( !empty( $ev_games ) ) { ?>
            <ul class="accordion" data-accordion data-allow-all-closed="true">
            <?php
            //init for the loop
            $court = 'start';
            $today_day = wp_date('l', strtotime(wp_date('Y-m-d H:i:s')));
//             echo "<pre>"; print_r($ev_games); echo "</pre>";
            $new_ev_games = g365_reorder_courts($ev_games, $today_day);
            foreach( $new_ev_games as $game_data ) {
              $court_day_color = wp_date('l', strtotime($game_data->start_time));
              //if this is a new section do the wrapper
              if( $game_data->court !== $court ) {
                //unless this is the first section, close the previous section
                if( $court != 'start' ) echo '</tbody></table></div></li>';
                //set the court for the next loop to ask
                $court = $game_data->court; ?>
                <li class="accordion-item" data-accordion-item>
                  <a class="accordion-title"><?php echo $game_data->court; ?></a>
                  <div class="accordion-content" data-tab-content>
                    <table>
                      <thead>
                        <tr>
                          <th>Select</th>
                          <th>Time</th>
                          <th>Court</th>
                          <th>Division</th>
                          <th>Home</th>
                          <th>Away</th>
                        </tr>
                      </thead>
                      <tbody>
              <?php } ?>
                        <tr class="date_<?php echo $court_day_color; ?>"><td><a class="button no-margin-bottom" href="<?php echo site_url(); ?>/account/stat_keep?ev_game=<?php echo $game_data->id; ?>">record stats</a></td><td><?php echo date_format(date_create($game_data->start_time), 'M d Y g:i A'); ?></td><td><?php echo $game_data->court; ?></td><td><?php echo $game_data->division; ?></td><td><?php echo stat_platform_girl_level(['team_name'=>$game_data->home_team]); ?></td><td><?php echo stat_platform_girl_level(['team_name'=>$game_data->away_team]); ?></td></tr>
              <?php } ?>
                    </tbody>
                  </table>
                </div>
              </li>
            </ul>
            <?php
          } else {
            echo '<p>No Games Data for this event.';
          }
        } else {
          //print the button to select the location
          ?>
          <section class="pretty-buttons small-padding-top">
            <div class="grid-x pretty-container">
              <?php foreach( $ev_locations as $dex => $loc ) { $ev_loc_parma = str_replace(' ', '_', $loc); ?>
                <div class="small-12 large-6 small-padding-bottom">
                  <a href="<?php echo site_url(); ?>/account/stat_keep?ev_id=<?php echo $ev_id; ?>&ev_loc=<?php echo $ev_loc_parma; ?>" class="pretty-btn pretty-btn-1"><?php echo $loc; ?>
                    <svg><rect x="0" y="0" fill="none" width="100%" height="100%"/></svg>
                  </a>
                </div>
              <?php } ?>
            </div>
          </section>
          <?php
        }
      } else {
        //print the opening event search form
        ?>
        <h3>Event Search</h3>
        <div class="relative">
          <span class="search-mag fi-magnifying-glass"></span>
          <input type="text" class='search-hero g365_livesearch_input' data-g365_type="event_stats" placeholder="Enter Event Name" autocomplete="off" autofocus>
        </div>
        <?php
      }
    }
      ?> </div> <?php
  }
  //my account g365 edit data page
  add_action( 'woocommerce_account_coach_endpoint', 'coach_endpoint_content' );
  function coach_endpoint_content() { ?>
    <div class="cell small-12 medium-8 large-6">
<!--       <h1>Coach Manager</h1> -->
    <?php
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    if( $user_g365 === '' || !isset($user_g365['co_ed']) || empty($user_g365['co_ed']) ) {
      echo '<p class="xlarge-margin-top xlarge-margin-bottom">Please add some info to the site and you can view/edit it here!</p>';
      echo '<p class="button-group expanded"><a href="' . site_url() . '/register/coaches/" class="button">Add Coach</a></p>';
    } else {
      if( current_user_can('club') ) echo '<p class="float-right"><a href="' . site_url() . '/register/coaches/" class="button">Add Coach</a></p>';
      ?>
      <div>
        <script type="text/javascript">
          var g365_form_details = {
            "items" : {
              "Coaches":{
                "name":"",
                "title":"Coach Editor",
                "type":"co_ed",
                "items":{}
              }
            },
            "wrapper_target" : "g365_form_options_anchor",
            "admin_key": "<?php echo g365_make_admin_key(); ?>"
          };
        </script>
        <div>
        <div id="g365_form_options_anchor" data-g365_type="co_ed:::<?php echo implode(':::', $user_g365['co_ed']); ?>"></div>
        </div>
      </div>
      <?php
    }
    ?>
    </div>
    <?php
  }
  //my account g365 edit data page
  add_action( 'woocommerce_account_club_endpoint', 'club_endpoint_content' );
  function club_endpoint_content() { ?>
    <div class="cell small-12 medium-8 large-6">
<!--       <h1>Organization Manager</h1> -->
    <?php
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    if( $user_g365 === '' || !isset($user_g365['og_ed']) || empty($user_g365['og_ed']) ) { ?>
      <div class="cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"Choose or create club team","type":"club_names","items": {}}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "<?php echo g365_make_admin_key(); ?>"};</script>
        <p>&nbsp;</p>
        <div>
          <div id="g365_form_options_anchor" data-g365_type="club_names" data-g365_init_pre="club_names_preset:::user_ac::<?php echo (((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . get_current_user_id()) ?>"></div>
        </div>
      </div>
      <?php
    } else {
      ?>
      <div>
        <script type="text/javascript">
          var g365_form_details = {
            "items" : {
              "Clubs":{
                "name":"",
                "title":"Club Editor",
                "type":"og_ed",
                "items":{}
              }
            },
            "wrapper_target" : "g365_form_options_anchor",
            "user_org": "<?php echo get_user_meta($current_user->ID, '_default_org', true); ?>",
            "admin_key": "<?php echo g365_make_admin_key(); ?>"
          };
        </script>
        <div>
        <div id="g365_form_options_anchor" data-g365_type="og_ed:::<?php echo implode(':::', $user_g365['og_ed']); ?>"></div>
        </div>
      </div>
    <?php
    }
    ?>
    </div>
    <?php
  }
  
  //my account g365 edit data page director account edit rosters
  add_action( 'woocommerce_account_rosters_endpoint', 'rosters_endpoint_content' );
  function rosters_endpoint_content() { ?>
    <?php
    //see if we have a event id otherwise load the default rosters
    $org_id = filter_input( INPUT_GET, 'org_id', FILTER_SANITIZE_NUMBER_INT );
    $ev_id = filter_input( INPUT_GET, 'ev_id', FILTER_SANITIZE_NUMBER_INT );
    if( empty($ev_id) ) $ev_id = '0';

    //see what user data we have
    $current_user = wp_get_current_user();
    //user owned data on this site
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    //default org
    $default_org_id = get_user_meta($current_user->ID, '_default_org', true);
    //set defaults if we aren't supplied with data
    if( empty($org_id) ) $org_id = $default_org_id;
    if( empty($org_id) ) $org_id = (($user_g365 !== '' && $user_g365['og_ed'][0]) ? intval($user_g365['og_ed'][0]) : null);
    ?>

<!--      start -->
    <?php 
//     if coach add class of coachRosterWrapper
$isCoach;
     if( current_user_can('coach') ) {
       $isCoach = 'coachRosterWrapper';
     } else {
       $isCoach = '';
     }
?>

    <div class="cell small-12 medium-8 large-6 <?php echo $isCoach;?>" id="directorRosterWrapper">
      <?php if( $org_id !== null ) {
        //globals for db
        global $wpdb;
        $wpdb_orgs = $wpdb->g365_orgs;
        $wpdb_rosters = $wpdb->g365_rosters;
        $wpdb_events = $wpdb->g365_events;
        $wpdb_teams = $wpdb->g365_teams;
        $wpdb_players = $wpdb->g365_players;

        //pull some org data to make sure we have a viable id
        $org_data = $wpdb->get_row( "SELECT * FROM $wpdb_orgs WHERE id = $org_id;" );
        if( !empty($org_data) ) {

          //url for links and dropdown
          $dropdown_menu_org_url = site_url() . '/account/rosters/?org_id=';
          $dropdown_menu_url = site_url() . '/account/rosters/?org_id=' . $org_id . '&ev_id=';

          ?>
          <h2 class="no-margin-bottom grid-x grid-margin-x">
            <div class="cell small-12 large-12">Manage Rosters for <?php echo $org_data->name; ?></div>
            <?php
            //if we have multiple organizations, build list of orgs
            if( $ev_id === '0' && is_array($user_g365) && count($user_g365['og_ed']) > 1 ) { ?>
            <div class="cell small-12 large-6 tiny-padding-top tiny-margin-bottom text-right">
              <div class="input-group">
                <span class="input-group-label normal-font-size">Your Club Teams</span>
                <select id="series_selector" class="no-margin-bottom input-group-field">
                <?php
                //get the names for any orgs that we need
                $user_orgs = $wpdb->get_results(
                  "SELECT org.id, org.name
                  FROM $wpdb_orgs AS org
                  WHERE org.id IN ( " . implode(',', $user_g365['og_ed']) . " );"
                );
                //build org dropdown
                if( !empty($user_orgs) ) foreach( $user_orgs as $dex => $vals ) echo '<option value="' . $dropdown_menu_org_url . $vals->id . '"' . (($org_id == $vals->id) ? ' selected="selected"' : '') . '>' . $vals->name . '</option>';
                ?>
                </select>
              </div>
            </div>
<!--        //     end if we have multiple orgs -->
            <?php } ?>
          </h2>
          <?php
          $where_ev = "AND roster.event IN (0, $ev_id)";
          if( $ev_id === '0' ) $where_ev = "AND roster.event = 0";
          //try to pull rosters for the event we are loading
          $full_rosters = $wpdb->get_results(
            "SELECT roster.id AS ros_id, ev.id AS ev_id, ev.name AS ev_name, ev.eventtime AS ev_time, roster.event, roster.enabled AS ros_enabled, tm.name AS team_name, tm.level AS team_level, roster.level AS ros_level
            FROM $wpdb_rosters AS roster
            LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
            LEFT JOIN $wpdb_teams AS tm ON roster.team=tm.id
            WHERE roster.org = $org_id $where_ev ORDER BY roster.event=0 DESC, ev.eventtime DESC, roster.level DESC, tm.name;"
          );
          //if we have roster data, process otherwise print the page to prompt roster creation
          if( empty($full_rosters) ) { ?>
            <div class="cell small-12 medium-8 large-6">
              <p>
                Create your first team with the form below. After team creation you'll be able to submit rosters to an event, edit existing rosters, or create more rosters.
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
                  "user_org": "<?php echo get_user_meta($current_user->ID, '_default_org', true); ?>",
                  "admin_key": "<?php echo g365_make_admin_key(); ?>"
                };
              </script>
              <div>
                <div id="g365_form_options_anchor" data-g365_type="team_names_sl" data-g365_init_pre="team_names_sl_preset:::org_id::<?php echo $org_id; ?>:::org_name::<?php echo $org_data->name; ?>:::event_id::<?php echo $ev_id; ?>"></div>
              </div>
            </div>

          <?php } else {
            $default_rosters_sort = '';
            //pull default rosters out of the data set tp build the listing for the roster adding buttons
            $default_rosters = all_rosters_filter('0', $full_rosters);
            $default_roster_ids = array();
            $event_rosters = $default_rosters;
            $event_roster_ids = array();
            $event_data = (object) array(
              'name' => 'Default',
              'eventtime' => '9999-12-31 0:00:00'
            );
            //if we aren't on the default rosters page make a set for them too and get event details
            if( $ev_id !== '0' ) {
              $event_data = $wpdb->get_row( "SELECT * FROM $wpdb_events WHERE id = $ev_id;" );
              $event_rosters = all_rosters_filter($ev_id, $full_rosters);
              //if we have event rosters collect the ids for later reference
              if( !empty($event_rosters) ) foreach( $event_rosters as $dex => $roster_data ) $event_roster_ids[] = $roster_data->ros_id;
            }
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


//                 echo '<pre>';
//                 print_r( $default_rosters_sort );
//                 echo '</pre>';

            //if we have rosters for this event and it is editable print the form else print the event results
            if( $ev_id === '0' ) {
              $preset_vars = array(
                'org_id::' . $org_id,
                'rosters_enabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['enabled'])) . '}',
                'rosters_disabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['disabled'])) . '}',
              );

              ?>
           <ul class="accordion accordion--director" data-accordion data-allow-all-closed="true" data-multi-expand="true">
                <a  class="accordion-title g365-edit-data" id="team_names_sl_<?php echo $ev_id; ?>" data-g365_type="team_names_sl" data-g365_init_pre="team_names_sl_preset:::org_id::<?php echo $org_id; ?>:::org_name::<?php echo $wpdb->get_var( "SELECT name FROM $wpdb_orgs WHERE id = $org_id" ); ?>:::event_id::<?php echo $ev_id; ?>" data-wrapper_target="g365-reveal-modal">Add New Team/Roster</a>        
                 
                <li class="accordion-item hide" data-accordion-item>
                  <!-- Accordion tab title -->
                  <a href="#" class="accordion-title">Create New Roster</a>

                  <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                  <div class="accordion-content" data-tab-content>
<!--                     <a class="float-right add-event-text"> Dont forget to add your team to the event</a> -->
                    <br>
                      
                  </div>
                </li>
              <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal>
                <div class="relative">
                  <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div id="g365-reveal-modal" class="form_wrapper"></div>
              </div>
              <div class="clearfix"></div>
              <script type="text/javascript">
                var g365_form_details = { "items" : { "Rosters":{ "name":"", "title":"Current Rosters", "type":"ro_ed", "items":{} }}, "wrapper_target" : "g365_form_options_anchor", "user_org": "<?php echo get_user_meta($current_user->ID, '_default_org', true); ?>", "admin_key": "<?php echo g365_make_admin_key(); ?>"
                };
              </script>
              <li class="accordion-item" data-accordion-item  id='viewEditRoster'>
                  <!-- Accordion tab title -->
                  <a href="#" class="accordion-title">View/Edit Roster(s)</a>

                  <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                  <div class="accordion-content" data-tab-content>
                    <div>
                      <?php
                      $item_string = '';
                      if( !empty($default_rosters) ) $item_string .= (':::' . implode(':::', $default_roster_ids));
                      ?>
                      <div id="g365_form_options_anchor" data-g365_type="ro_ed<?php echo ($item_string ?? ''); ?>"></div>
                    </div>
                  </div>
                </li>
                  <li class="accordion-item hide" data-accordion-item>
                  <!-- Accordion tab title -->
                  <a href="#" class="accordion-title">Submit Roster(s)</a>
                    

                  <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                  <div class="accordion-content" data-tab-content>
                   
                  </div>
                </li>
                   <a  class="accordion-title medium g365-edit-data no-margin-bottom" id="rosters_sl_new" data-g365_type="rosters_sl_xl" data-g365_init_pre="rosters_sl_xl_preset:::<?php echo implode(':::', $preset_vars); ?>" data-wrapper_target="g365-reveal-modal">Submit Roster(s)</a>

            </ul>

              <?php
            } else {
             if( (count($event_rosters) > 0 && in_array( g365_event_date_diff($event_data->eventtime), array(1,2,3,4) )) ) {
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
             </ul>
            </div>
          <?php } else {
            //if we have rosters print them, otherwise print a message about there not being any roster for this event
            if( count($event_rosters) > 0 ) {
              echo '<div class="xlarge-margin-top">';
              echo '<h2 clsss="no-margin-bottom">' . $event_data->name . '</h2>';
              echo '<p class="large-margin-bottom">This event has passed and rosters can no longer be edited.<br><strong>Event started:</strong> ' . date('n-d-y', strtotime($event_data->eventtime)) . '</p>';
              foreach( $event_rosters as $dex => $roster_data ) {
                if( $roster_data->ros_enabled != 1 ) continue;
                echo '<div>';
                echo '<h4 class="no-margin-bottom">' . g365_level_key($roster_data->team_level) . ' ' . $roster_data->team_name . '</h4>';
                echo '<p>Playing at ' . g365_level_key($roster_data->ros_level) . '</p>';
                echo '</div>';
              }
              echo '</div>';

            } else {
              echo '<h2 clsss="no-margin-bottom">' . $event_data->name . '</h2>';
              echo '<h2>No rosters found.</h2><p>We couldn\'t find any rosters for this event, if you feel that this is an error, please contact one of our customer support specialists.</p>';
            }
          }
            }
        }
      } else { ?>
          <h2 class="no-margin-bottom grid-x grid-margin-x">
            <div class="cell small-12 large-6">Club Team Error</div>
          </h2>
          <div class="cell small-12 medium-8 large-6">
            <p>There has been an error processing your request, please contact customer service. We can't find your Club Team.</p>
          <div>
        <?php }
    } else {
    ?>
      <div class="cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"Choose or create club team","type":"club_names","items": {}}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "<?php echo g365_make_admin_key(); ?>"};</script>
        <p>&nbsp;</p>
        <div>
          <div id="g365_form_options_anchor" data-g365_type="club_names" data-g365_init_pre="club_names_preset:::user_ac::<?php echo (((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . get_current_user_id()) ?>"></div>
        </div>
      </div>
    <?php
    }
    ?>
   <script type="text/javascript">
// //       holds id of each team selected
     let submitArray = [];
     
//        if newRosters has been created - should have search param of ?roster=xxxxx
     const queryString = window.location.search;
     const urlParams = new URLSearchParams(queryString);
     const newRoster =  urlParams.get('roster');
     
//      if url param exists 1) open edit section and 2) associated rosters and 3) scroll to it
     document.addEventListener("DOMContentLoaded", function(event) {
       if(newRoster != null) {
         console.log(newRoster);

         document.querySelector('#viewEditRoster-label').click();
         setTimeout(() => {
            let targetRoster = document.querySelector(`input[value="${newRoster}"]`);
            let targetParent = targetRoster.closest('.g365_form');
           
           targetParent.querySelector('.form-collapse-title').click();
           targetParent.scrollIntoView(); 
          
        }, 2000);

       }
      
      });
     const observer = new MutationObserver(function(mutations_list) {
        mutations_list.forEach(function(mutation) {
          mutation.addedNodes.forEach(function(added_node) {
            if(added_node.id == 'rosters_sl_xl_fieldset') { //when this element is added_node 
               //all these are initialized after the form has been added to the document. Otherwise will error
                const submitFirstStep = document.querySelector('#submitFirstStep');
                const submitSecondStep = document.querySelector('#submitSecondStep');
                const checkRostersBtn = document.querySelector('#checkRostersBtn');
                const submitRostersBtn = document.querySelector('#submitRostersBtn');
                const submitRostersBack = document.querySelector('#submitRostersBack');
//               check if admin backend or frontend - since they use the same form
              if(document.querySelector('body').classList.contains('woocommerce-account')) {
                generateRosters();
                checkRostersBtn.addEventListener('click' , checkRosters);
                submitRostersBack.addEventListener('click', undoCheckRosters);
                
                submitRostersBtn.style.display ='none';
                submitRostersBack.style.display = 'none';
              }
//               comment this disconnect out for when people close and open window over and over
//               observer.disconnect();
            }
          });
        });
      });
      
      function getRosterPlayers(teamId) { 
        let players = document.querySelectorAll(`#ro_ed_${teamId}_players_wrapper_data li`);
        let playerInfo = [];
        
        players.forEach(player => {
          let playerName = player.children[0].children[0].innerText.trim();
          let playerJersey = player.children[0].children[1].value;
          
          playerInfo.push({
            pl_name: playerName, 
            j_num: playerJersey
          })
        })
        return playerInfo;
      }
      
      function createRosterCheck(array) {
        let teamsArray = array;
        const rosterContingent = document.getElementById('rosters_sl_xl_event_contingent');
        let ul = document.createElement('ul');
//         create div
        teamsArray.forEach(team => {
//           start getting player info
          let gatheredArray = getRosterPlayers(team.id);
          
//           reversed player numbers for some reason
          let playerInfoArray = gatheredArray.reverse();
          
          
          let div = document.createElement('div');
          div.classList.add('hide');
          div.classList.add('submit-roster-player-list');
          div.innerHTML = '<p class="text-underline" style="color: black;">Players on Roster</p>'
          
//        if player information is empty i.e theres no players on the roster 
          if(playerInfoArray.length !=0) {
//             create a p element for each player with jersey and name. ex) 13. Test Player
              playerInfoArray.forEach(player => {
               let p = document.createElement('p');
               p.innerText = `${player.j_num}. ${player.pl_name}`;
                p.style.color = 'black';
                div.append(p);
            })
          } else { //if there are no players
             let p = document.createElement('p');
                p.innerText = 'No Players on Roster.';
                p.style.color = 'black';
                div.append(p);
          }
          
//           after all players appended, create edit roster button
          let a = document.createElement('p');
          a.classList.add('submit-roster-player-list--edit')
          a.textContent = 'Need to edit roster?';
          
          a.addEventListener('click', function() {
            let confirmText = "You will be re-directed to the edit rosters section. Are you sure?"
            let confirmation = confirm(confirmText);
            
             if (confirmation === true) {
  //             close modal
              document.querySelector('html').classList.remove('is-reveal-open');
              document.querySelector('.reveal-overlay').style.display = 'none';
  //             open view/edit
              document.querySelector('#viewEditRoster-label').click();
              document.querySelector(`#ro_ed_${team.id}_fieldset_title`).click();

  //             delay because the scroll will break due to multiple height changes too quickly
              setTimeout(function(){
                document.querySelector(`#ro_ed_${team.id}`).scrollIntoView();
              }, 500)
            } else {
              return;
            }
          });
          
          div.append(a);
//           ul.classList.add('roster-check__ul')
//           ul.appendChild(li);
          
          document.getElementById(`rosters_sl_xl_${team.id}`).appendChild(div); //changed 4/4 (added it)
        })
          
//         let fixMessage = document.createElement('p');
//         fixMessage.innerHTML = "Missing players or have incorrect players on these rosters? <span class='text-underline'>Click Here</span> to correct them."
//         ul.appendChild(fixMessage);
        
//         document.getElementById('rosters_sl_xl_club_team_contingent').appendChild(ul);
        
//         hide event roster contingent
//        rosterContingent.style.display = 'none';
//        checkRostersBtn.style.display = 'none';
//        submitRostersBtn.style.display = 'block';
//        document.querySelector('#submitFirstStep').classList.remove('active');
//        document.querySelector('#submitSecondStep').classList.add('active');
      }
      
      function checkRosters() {
//       1.  clear array every time to prevent duplicates
        let checkedArr = [];
        
//      2.   get all checked inputs
        const allInputs = document.querySelectorAll('#rosters_sl_xl_teams_wrapper input');
  
        allInputs.forEach(input => {
           if(input.checked) {
            checkedArr.push(input)
            let playerList = input.parentElement.nextElementSibling.nextElementSibling;
            playerList.classList.remove('hide');
          } else {
            input.parentElement.classList.add('hide');
//               console.log('unchecked');
          }
          
        })
        
//         3.    making sure atleast one option is selected
        if(checkedArr.length != 0) {
           checkRostersBtn.style.display = 'none';
           submitRostersBtn.style.display = 'inline-block';
           submitRostersBack.style.display = 'inline-block';
          
                      
           submitFirstStep.classList.add('hide');
           submitSecondStep.classList.remove('hide');
       document.querySelector('#rosters_sl_xl_teams_wrapper').classList.add('active');
          
          
        document.getElementById('event_names').disabled = true;
          
        } else {
          allInputs.forEach(input => { 
            input.parentElement.classList.remove('hide');
          })
          window.alert('You must select an event and teams to move onto the next section.')
        }
        
      }
     
     function undoCheckRosters() {
        const allInputs = document.querySelectorAll('#rosters_sl_xl_teams_wrapper input');
         allInputs.forEach(input => {
           input.parentElement.classList.remove('hide');
         })
       
       document.querySelectorAll('.submit-roster-player-list').forEach(list => {
         list.classList.add('hide');
       })
       checkRostersBtn.style.display = 'block';
       submitRostersBtn.style.display = 'none';
       submitRostersBack.style.display = 'none';
       
           submitFirstStep.classList.remove('hide');
           submitSecondStep.classList.add('hide');
       document.querySelector('#rosters_sl_xl_teams_wrapper').classList.remove('active');
       
//         re-enable event name
        document.getElementById('event_names').disabled = false;
        
     }
     
     function generateRosters() {
        submitArray = [];
       const allInputs = document.querySelectorAll('#rosters_sl_xl_teams_wrapper input');
  
        allInputs.forEach(input => {
           if(input.checked) {
              let teamName = input.parentElement.textContent;
              let cleanStr = teamName.trim();
              submitArray.push({name: cleanStr, id: input.value})
          } else {
//             remove everything here to disable grabbing of all //changed 4/4 (added it)
              let teamName = input.parentElement.textContent;
              let cleanStr = teamName.trim();
              submitArray.push({name: cleanStr, id: input.value})
//               console.log('unchecked');
          }
          
        })
       
       createRosterCheck(submitArray);
       
     }
     

      observer.observe(document.querySelector("#g365-reveal-modal"), { subtree: false, childList: true });


      
   </script>
    <?php
  }
  //my account edit all rosters page
  add_action( 'woocommerce_account_rosters_all_endpoint', 'rosters_all_endpoint_content' );
  function rosters_all_endpoint_content() { ?>
    <?php
    //see if we have a event id otherwise load the default rosters
    $org_id = filter_input( INPUT_GET, 'org_id', FILTER_SANITIZE_NUMBER_INT );

    //see what user data we have
    $current_user = wp_get_current_user();
    //user owned data on this site
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    //default org
    $default_org_id = get_user_meta($current_user->ID, '_default_org', true);
    //set defaults if we aren't supplied with data
    if( empty($org_id) ) $org_id = $default_org_id;
    if( empty($org_id) ) $org_id = (($user_g365 !== '' && $user_g365['og_ed'][0]) ? intval($user_g365['og_ed'][0]) : null);

    //globals for db
    global $wpdb;
    $wpdb_orgs = $wpdb->g365_orgs;
    $wpdb_rosters = $wpdb->g365_rosters;
    $wpdb_events = $wpdb->g365_events;
  	$wpdb_teams = $wpdb->g365_teams;
    
    //url for links and dropdown    
    $dropdown_menu_org_url = site_url() . '/account/rosters_all/?org_id=';
    $dropdown_menu_url = site_url() . '/account/rosters/?org_id=' . $org_id . '&ev_id=';
    ?>
    <div class="cell small-12 medium-8 large-6">
      <?php if( $org_id !== null ) { ?>
        <h2 class="no-margin-bottom grid-x grid-margin-x">
          <div class="cell small-12 large-6">Manage Rosters</div>
          <?php
          //if we have multiple organizations, build list of orgs
          if( count($user_g365['og_ed']) > 1 ) { ?>
          <div class="cell small-12 large-6 tiny-padding-top tiny-margin-bottom text-right">
            <div class="input-group">
              <span class="input-group-label normal-font-size">Your Club Teams</span>
              <select id="series_selector" class="no-margin-bottom input-group-field">
              <?php
              //get the names for any orgs that we need
              $user_orgs = $wpdb->get_results(
                "SELECT org.id, org.name
                FROM $wpdb_orgs AS org
                WHERE org.id IN ( " . implode(',', $user_g365['og_ed']) . " );"
              );
              //build org dropdown
              if( !empty($user_orgs) ) foreach( $user_orgs as $dex => $vals ) echo '<option value="' . $dropdown_menu_org_url . $vals->id . '"' . (($org_id == $vals->id) ? ' selected="selected"' : '') . '>' . $vals->name . '</option>';
              ?>
              </select>
            </div>
          </div>
          <?php } ?>
        </h2>
      <?php
        //if we have data to pull rosters, build list of event rosters
        $all_rosters = $wpdb->get_results(
          "SELECT roster.id AS ros_id, ev.id AS ev_id, ev.name AS ev_name, ev.eventtime AS ev_time, roster.event, roster.enabled AS ros_enabled, tm.name AS team_name, tm.level AS team_level, roster.level AS ros_level
          FROM $wpdb_rosters AS roster
          LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
          LEFT JOIN $wpdb_teams AS tm ON roster.team=tm.id
          WHERE roster.org = $org_id ORDER BY roster.event=0 DESC, ev.eventtime DESC, roster.level DESC, tm.name;"
        );
        $default_rosters_sort = '';
        $default_rosters_ids = array();
        //if we have roster data, process otherwise print the page to prompt roster creation
        if( !empty($all_rosters) ) {
          $default_rosters = all_rosters_filter('0', $all_rosters);
          $default_rosters_sort = array('enabled'=>array(),'disabled'=>array());
          foreach( $default_rosters as $dex => $roster_data ) {
            if( $roster_data->ros_enabled == 1 ) {
              $default_rosters_sort['enabled'][] = '"' . $roster_data->ros_id . '":{"team_name":"' . g365_level_key($roster_data->team_level) . ((empty($roster_data->team_name)) ? '' : ' ' . $roster_data->team_name) . '", "status":"enabled_team", "team_level":"' . $roster_data->team_level . '"}';
            } else {
              $default_rosters_sort['disabled'][] = '"' . $roster_data->ros_id . '":{"team_name":"' . g365_level_key($roster_data->team_level) . ((empty($roster_data->team_name)) ? '' : ' ' . $roster_data->team_name) . '", "status":"disabled_team", "team_level":"' . $roster_data->team_level . '"}';
            }
          }
        } ?>
        <div class="cell small-12 large-6 tiny-padding-top tiny-margin-bottom">
          <?php
          //if we have roster data, process otherwise print the page to prompt roster creation
          if( !empty($all_rosters) ) {
            $preset_vars = array(
              'org_id::' . $org_id,
              'rosters_enabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['enabled'])) . '}',
              'rosters_disabled::{' . str_replace('"', "'", implode(',', $default_rosters_sort['disabled'])) . '}',
            );
            ?>
            <a  class="button float-right medium tiny-margin-right small-small g365-edit-data no-margin-bottom" id="rosters_sl_new" data-g365_type="rosters_sl_xl" data-g365_init_pre="rosters_sl_xl_preset:::<?php echo implode(':::', $preset_vars); ?>" data-wrapper_target="g365-reveal-modal">Add to Event</a>
            <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal>
              <div class="relative">
                <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
              </div>
              <div id="g365-reveal-modal" class="form_wrapper"></div>
            </div>
            <div class="clearfix"></div>
            <script type="text/javascript">
              var g365_form_details = {
                "items" : {
                  "Rosters":{
                    "name":"",
                    "title":"Add Default Rosters to Events",
                    "type":"roster_sl_xl",
                    "items":{}
                  }
                },
                "wrapper_target" : "g365_form_options_anchor",
                "user_org": "<?php echo $org_id; ?>",
                "admin_key": "<?php echo g365_make_admin_key(); ?>"
              };
            </script>
            <div>
              <?php // echo ($item_string ?? ''); Fantastic Trick. Best ?>
              <div id="g365_form_options_anchor" data-g365_type="ro_ed"></div>
            </div>

<!--   Event Rosters   -->
            <h3 class="medium-margin-top medium-margin-bottom">Current Season</h3>
            <p>Click on a roster set to edit/add a roster, or to add a roster to an event.</p>
            <hr>
            <div class="event-roster-wrap">
            <?php
            $events_with_roster = array();
            $previous_season = false;
            //make a second array to pull unique event data
            foreach( $all_rosters as $dex => $roster_data ) $events_with_roster[ $roster_data->ev_id ] = [$roster_data->ev_name, $roster_data->ev_time];
            //now that we have a distinct event list, process all events with rosters
            foreach( $events_with_roster as $event_id => $ev_data ) {
              //send the event start time to the function to see how we should label the event
              $ev_status = g365_event_date_diff($ev_data[1]);
              $ev_message = 'All rosters unlocked. You may edit these rosters.';
              $old_season = false;
              //make an exception for the club rosters
              if( $event_id !== 0 ) {
                //if it's not the default rosters set the label
                switch($ev_status) {
                  case null:
                    $ev_message = 'Event has started, rosters are now locked.';
                    break;
                  case 1:
                    $ev_message = 'Event is about to start, rosters lock at midnight. You will not be able to edit rosters once locked.';
                    break;
                  case 2:
                    $ev_message = 'Event is getting close, finalize your rosters.';
                    break;
                  case 3:
                    $ev_message = 'Event is upcoming, set your rosters soon.';
                    break;
                  case 4:
                    $ev_message = 'Event is upcoming.';
                    break;
                  case 5:
                    $ev_message = 'Event from previous season.';
                    $old_season = true;
                }
              }
              //if the g365_event_date_diff function comes back with 5 'out of season' process with out editing ability
              if( $old_season ) {
                //if we need to set the header for this section
                if( $previous_season === false ) {
                  //set the header divider for previous events
                  echo '<h4>Previous Seasons</h4>';
                  $previous_season = true;
                }
                //for old season, just print a link to the event with a name
                echo '<a class="button no-margin-bottom" href="' . $dropdown_menu_url . $event_id . '">' .  $ev_data[0] . '</a>';
              } else {
                //if this is an active, editable roster, print all the edit controls
                echo '<a class="button  g365-primary-submit no-margin-bottom" href="' . $dropdown_menu_url . $event_id . '">' . (($ev_data[0] == 'Club Team') ? 'Public Default Club Team Rosters' : $ev_data[0]) . '</a>';
                echo '<div class="small-margin-top small-margin-bottom"><small class="pointer text-underline" onclick="$(this).parent().next().slideToggle();">Assigned Rosters</small> | <small class="roster_status_label">' . $ev_message . '</small></div><div class="manage-roster__team-wrapper small-padding gray-bg gray-border" style="display:none;">';
                //loop through all the rosters and put them in the correct spots by event
                foreach( $all_rosters as $dex => $roster_data ) {
                  //if the roster event doesn't match, goto the next
                  if( intval($roster_data->ev_id) !== $event_id ) continue;
                  echo '<span class="roster-label small tiny-padding-sides' . (( $roster_data->ros_enabled === '0' ) ? ' disabled' : '') . '">' . g365_level_key($roster_data->team_level) . ' ' . $roster_data->team_name . (($roster_data->team_level != $roster_data->ros_level) ? ' <span class="level-tag">playing<br>at ' . g365_level_key($roster_data->ros_level) . '</span>' : '') . '</span>';
                }
                echo '</div>';
              }
              echo '<hr>';
            }
            echo '</div>';
          } else { //this is for users that have no rosters. ?>
            <h3 class="medium-margin-bottom">Looks like you don't have any rosters yet...</h3>
            <div class="event-roster-wrap">
              <a href="<?php echo site_url(); ?>/register/club-teams/0/" class="button no-margin-bottom small-margin-top medium">Add New Rosters</a>
            </div>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
      <div class="cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"Choose or create club team","type":"club_names","items": {}}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "<?php echo g365_make_admin_key(); ?>"};</script>
        <p>&nbsp;</p>
        <div>
          <div id="g365_form_options_anchor" data-g365_type="club_names" data-g365_init_pre="club_names_preset:::user_ac::<?php echo (((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . get_current_user_id()) ?>"></div>
        </div>
      </div>
    <?php
    }
  }

  //cps profiles manager page
  add_action( 'woocommerce_account_cps_mod_endpoint', 'cps_mod_endpoint_content' );
  function cps_mod_endpoint_content() {
    echo '<div id="g365_data_manager_admin" class="cell small-12 medium-8 large-6 g365_data_manager_wrapper">';
    $event_id = intval(filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT ));
    $stat_status = filter_input( INPUT_GET, 'stat_status', FILTER_SANITIZE_NUMBER_INT );
    $purge_cache = filter_input( INPUT_GET, 'dlt_cache', FILTER_SANITIZE_NUMBER_INT );
    if( empty($event_id) ) $event_id = 231;
    if( $purge_cache == 1 && is_plugin_active('wp-super-cache/wp-cache.php') ) {
      wpsc_delete_post_cache( 6463 ); //4837
      # Create a connection to 
      $response = wp_remote_post( 'https://opengympremier.com/wp-admin/admin-ajax.php?action=cps_pp_cache_purge' );
      if ( is_wp_error( $response ) ) {
        echo '<div><h4>Cache Purge Error</h4><p>' . $response->get_error_message() . '</p></div>';
      } else {
        echo '<div><h4>Cache Purged</h4><p>' . $response['body'] . '</p></div>';
      }
    }
    if( is_null($stat_status) ) {
      $stat_status = 1;
    } else {
      $stat_status = intval($stat_status);
    }
    $event_stats = g365_get_stats( null, intval( $event_id ), $stat_status ); ?>
      <h2><?php echo reset($event_stats)->event_name; ?></h2>
      <h3>Profile Status</h3>
      <div class="button-group tiny-margin-bottom">
        <a href="/account/cps_mod/?event_id=<?php echo $event_id; ?>&stat_status=0" class="button<?php if($stat_status == 0) echo ' is-active'; ?>">Disabled</a>
        <a href="/account/cps_mod/?event_id=<?php echo $event_id; ?>&stat_status=1" class="button<?php if($stat_status == 1) echo ' is-active'; ?>">Enabled</a>
      </div>
    <?php
    if( gettype($event_stats) !== 'string' && !empty($event_stats) ) {
      $admin_string = '';
      if( current_user_can('front_editor') || current_user_can('administrator') ) {
        wp_dequeue_script( 'js-all' );
        wp_enqueue_script( 'js-g365-all-front-admin' );

        $admin_string = ', "g365_admin" : "false"';
        $current_user = wp_get_current_user();
        $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
        $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
      }
      ?>
      <div class="reveal" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal>
        <div class="grid-x grid-margin-x">
          <div class="cell small-12 medium-8 medium-offset-2">
            <div class="relative">
              <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="g365_form_options_anchor" data-g365_type="cps_manager"></div>
          </div>
        </div>
      </div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "cps_manager", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>
      <div class="table-scroll">
        <table class="column-dividers">
          <thead>
            <tr>
              <th>Edit</th>
              <th>Player</th>
              <th>Height</th>
              <th>Class</th>
              <th>Position</th>
              <th>GPA</th>
              <th>SAT</th>
              <th>ACT</th>
              <th>College</th>
              <th>HS</th>
              <th>V</th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach( $event_stats as $stat_dex => $stat_data ) {
//           echo '<pre>';
//           print_r($stat_data);
//           echo '</pre>';
            if( empty($stat_data->name) ) continue;
            if( gettype($stat_data->trends) === 'string' ) $stat_data->trends = json_decode($stat_data->trends);
            $today = date("Y-m-d");
            echo '<tr>';
              echo '<td class="form-floater"><a class="button g365-edit-data no-margin-bottom" id="pl_ev_' . $stat_data->id . '" data-g365_type="cps_manager::' . $stat_data->id . '">Edit</a></td>';
              echo '<td>' . $stat_data->name . '</td>';
              echo '<td>' . ((empty($stat_data->height_ft)) ? '' : $stat_data->height_ft . "'") . ((empty($stat_data->height_in)) ? '' : $stat_data->height_in . '"') . '</td>';
              echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
              echo '<td>' . ((empty($stat_data->position_name)) ? '' : $stat_data->position_name) . '</td>';
              echo '<td>' . ((empty($stat_data->gpa)) ? '' : $stat_data->gpa) . '</td>';
              echo '<td>' . ((empty($stat_data->sat)) ? '' : $stat_data->sat) . '</td>';
              echo '<td>' . ((empty($stat_data->act)) ? '' : $stat_data->act) . '</td>';
              echo '<td>' . ((empty($stat_data->trends->college)) ? '' : $stat_data->trends->college) . '</td>';
              echo '<td>' . ((empty($stat_data->event_profile_img)) ? '' : "✓") . '</td>';
              echo '<td>' . ((empty($stat_data->trends->video_link)) ? '' : '✓') . '</td>';
            echo '</tr>';
          }
          ?>
          </tbody>
        </table>
      </div>
      <?php
      } else {
      ?>
      <p class="xlarge-margin-top xlarge-margin-bottom">No College Placement Service Event profiles in this search!</p>
      <?php
      } 
    ?>
      <hr class="xlarge-margin-top">
      <a href="<?php echo site_url() . '/register/college-placement/' . $event_id; ?>" class="button" target="_blank">Add Player to CPS</a>
      <a href="/account/cps_mod/?event_id=<?php echo $event_id; ?>&dlt_cache=1" class="button">Refresh Profiles Cache</a>
    <?php
    echo '</div>';
  }
  //event profiles manager page
  add_action( 'woocommerce_account_event_mod_endpoint', 'event_mod_endpoint_content' );
  function event_mod_endpoint_content() {
    echo '<div class="cell small-12 medium-8 large-6 g365_data_manager_wrapper">';
    $event_id = intval(filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT ));
    if( empty($event_id) ) $event_id = 264;
    $event_stats = g365_get_stats( null, intval( $event_id ) );
    echo '<h2>' . reset($event_stats)->event_name . '</h2>';
    
    if( !empty($event_stats) ) {
      $admin_string = '';
      if( current_user_can('front_editor') || current_user_can('administrator') ) {
        wp_dequeue_script( 'js-all' );
        wp_enqueue_script( 'js-g365-all-admin' );

        $admin_string = ', "g365_admin" : "false"';
        $current_user = wp_get_current_user();
        $admin_string .= ', "g365_user_name" : "' . (( $current_user->user_firstname == '' && $current_user->user_lastname == '' ) ? $current_user->display_name : $current_user->user_firstname . ' ' . $current_user->user_lastname) . '"';
        $admin_string .= ', "g365_user_email" : "' . $current_user->user_email . '"';
      }
      ?>
      <div id="g365_form_options_anchor" data-g365_type="player_event_admin"></div>
      <script type="text/javascript">var g365_form_details = {"items" : {"Manage Stats" : {"name" : "Make changes to player event stats", "title" : "", "type" : "player_event_admin", "no_init" : true, "items" : {}}}<?php echo $admin_string; ?>, "admin_key" : "<?php echo g365_make_admin_key(); ?>", "wrapper_target" : "g365_form_options_anchor"};</script>
      <div class="table-scroll">
        <table class="widefat striped">
          <thead>
            <tr>
              <th>Player</th>
              <th>Age</th>
              <th>Birthdate</th>
              <th>Grade</th>
              <th>Class</th>
              <th>Event Data</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
  //         echo '<pre>';
  //         print_r($event_stats);
  //         echo '</pre>';
          foreach( $event_stats as $stat_dex => $stat_data ) {
            if( empty($stat_data->name) ) continue;
            $today = date("Y-m-d");
            echo '<tr>';
              echo '<td>' . $stat_data->name . '</td>';
              echo '<td>' . ((empty($stat_data->birthday)) ? '' : date_diff(date_create($stat_data->birthday), date_create($today))->format('%y')) . '</td>';
              echo '<td>' . ((empty($stat_data->birthday)) ? '' : date('m/d/y', strtotime($stat_data->birthday))) . '</td>';
              echo '<td>' . ((empty($stat_data->grad_year)) ? '' : g365_class_to_grade($stat_data->grad_year)) . '</td>';
              echo '<td>' . ((empty($stat_data->grad_year)) ? '' : $stat_data->grad_year) . '</td>';
              echo '<td>' . ((empty($stat_data->trends)) ? '' : 'Yes') . '</td>';
              echo '<td class="form-floater"><a class="button g365-edit-data" id="pl_ev_' . $stat_data->id . '" data-g365_type="player_event_admin,' . $stat_data->id . '">Edit</a></td>';
            echo '</tr>';
          }
          ?>
          </tbody>
        </table>
      </div>
      <?php
      } else {
      ?>
      <p class="xlarge-margin-top xlarge-margin-bottom">Please add some players to this Event and you can view/edit it here!</p>
      <?php
      } 
    ?>
      <hr class="xlarge-margin-top">
      <a href="" class="button">Add Player to Event</a>
    <?php
    echo '</div>';
  }

  
  // ----- validate password match on the registration page
  function g365_confirm_password($reg_errors, $sanitized_user_login, $user_email) {
    global $woocommerce;
    extract( $_POST );
    if ( strcmp( $password, $password2 ) !== 0 ) {
      return new WP_Error( 'registration-error', __( 'Passwords do not match.', 'woocommerce' ) );
    }
    return $reg_errors;
  }
  add_filter('woocommerce_registration_errors', 'g365_confirm_password', 10,3);
  
  // ----- set role on registration
  function g365_set_role($user_id) {
    $roles = array(
      'player_user' => 'player_editor',
      'coach_user' => 'coach',
      'director_user' => 'club',
      'college_coach_user' => 'college_coach'
    );
    if ( isset($_POST['user_role']) ) {
      $u = new WP_User( $user_id );
      $u->set_role( $roles[$_POST['user_role']] );
    }
  }
  add_action('user_register', 'g365_set_role', 10, 3);

}

//remove related products section from single product pages
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//add scripts for the admin
// function g365_wc_admin_scripts() {
//   wp_enqueue_script( 'admin-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), '3.4.1', true );
// }
// add_action( 'admin_enqueue_scripts', 'g365_wc_admin_scripts', 9 );

//add meta data to products
// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'g365_add_event_id_reference' );
// Save Fields
add_action( 'woocommerce_process_product_meta', 'g365_add_event_id_reference_save' );

function g365_add_event_id_reference() {
	wp_enqueue_script( 'admin-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '2.2.4', true );
	wp_enqueue_style( 'foundation-admin', site_url( '/wp-content/themes/g365-press', 'https' ) . "/css/style-admin.css", array(), filemtime(get_template_directory() . '/css/style-admin.css') );
  wp_enqueue_script( 'js-g365-all-admin', site_url( '/', 'https' ) . 'data-processor-admin.js', array('admin-jquery', 'foundation-admin'), filemtime(WP_PLUGIN_DIR . "/g365-data-manager/js/g365_ajax_cookie_ls_app_admin.js"), true );
  global $wpdb, $woocommerce, $post;
  $wpdb_events = $wpdb->g365_events;
  ?>
  <div class="options_group g365_manage_wrapper">
    <p class="form-field g365_event_link no-input-margin">
      <label for="event_link_selector">G365 Event Link</label>
      <?php
      $event_id = get_post_meta( $post->ID, '_event_link', true );
      if( !empty($event_id) ) $event_name_object = $wpdb->get_results( "SELECT name FROM $wpdb_events WHERE id = $event_id" ); 
      woocommerce_wp_hidden_input(array(
        'id'    => 'event_link',
        'value' => empty($event_id) ? '' : $event_id
      ));
      ?>
      <input type="text" class="g365_livesearch_input" id="event_link_selector" value="<?php echo ( empty($event_name_object) ) ? '' : $event_name_object[0]->name; ?>" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="event_link" data-g365_type="event_names" placeholder="Enter Event Name" autocomplete="off">
    </p>
  </div>
  <?php
}

function g365_add_event_id_reference_save( $post_id ){
	$event_link = $_POST['event_link'];
	if( !empty( $event_link ) ) update_post_meta( $post_id, '_event_link', intval($event_link) );
}

function g365_cart_process(){
  //for getting form parts
  $g365_cart_types = array();
  //for setting form restrictions/helpers
  $g365_cart_items = array();
  //if we have to add special fields for products like gear or fees or events
  $extra_fields = array();
  //if we have cart data
  if( !empty(WC()->cart->get_cart()) ) {
    //process each cart item
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

      //parse product info for us to build with
      $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
      //root product id
      $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
      //form type is tied to product categories, we only grab the first cat
      $product_cats = get_the_terms( $product_id, 'product_cat' );
      $product_cat_id = $product_cats[0]->term_id;
      $product_cat_name = $product_cats[0]->name;
      $product_cat_slug = $product_cats[0]->slug;

      //root product title
      $product_name = $_product->get_title();
      //variation id, if there isn't one it should default to 0
      $product_var_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['variation_id'], $cart_item, $cart_item_key );
      //product variation name, if there isn't one it should be empty
      $product_var_name = (isset($cart_item['variation']['attribute_level'])) ? apply_filters( 'woocommerce_cart_item_product_name', $cart_item['variation']['attribute_level'], $cart_item, $cart_item_key ) : '';
      //get the event id to pull in any further restictions and key the new data
      $product_event_link = intval(get_post_meta( $product_id, '_event_link', true ));
      if( $product_event_link !== 0 ) {
        //get event data
        $product_event_divisions_pull = g365_get_event_data( $product_event_link, true );
        //extract the division information
        $product_event_divisions = json_decode($product_event_divisions_pull->divisions);
        if( !empty( $product_event_divisions ) ){
          //the correc order for divisions
          $product_event_divisions_base = ["Open", "Gold", "Silver", "Bronze", "Copper"];
          //loop through divisions available and reorder them
          foreach($product_event_divisions as $dex => $level) {
            $product_div_reorder = (object) array();
            foreach($product_event_divisions_base as $val) {
              if(isset($level->$val)) $product_div_reorder->$val = $level->$val;
            }
            $product_event_divisions->$dex = $product_div_reorder;
          }
        } else {
          $product_event_divisions = null;
        }
      }
      $form_type_data = g365_return_keys( 'g365_cat_form_key' );
      //key for form type
      $form_type_opt = $form_type_data[0];
      //key for form targets
      $form_type_target = $form_type_data[1];
      //key for extra field types outside managment
      $extra_types = array( 'fee', 'gear', 'event', 'ticket', 'tournaments', 'dcp_team_info', 'highlight_film', 'college_coach_school' );
//       echo (empty($product_cat_slug))?'true':'false';
//       echo (empty($product_cat_id))?'true':'false';
//       echo (empty($product_id))?'true':'false';
//       echo (empty($form_type_opt[$product_cat_slug]))?'true':'false';
      //product_cat and product_id is needed to organize the cart item data,or if the cat type is invalid
      if( empty($product_cat_slug) || empty($product_cat_id) || empty($product_id) || empty($form_type_opt[$product_cat_slug]) ) {
        if( in_array($product_cat_slug, $extra_types) ) {
          switch( $product_cat_slug ) {
            case 'fee':
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'Players Full Name',
                  'label' => 'Player Name ' . $i,
                  'name'  => 'player_name_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
                $extra_fields[] = array(
                  'title' => $product_name,
                  'type'  => 'select',
                  'placeholder'=> 'Players Jersey Size',
                  'label' => 'Player Jersey ' . $i,
                  'name'  => 'player_jersey_size_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug, 'medium-margin-bottom'),
                  'options' => array(
                    'y_sm' => 'Youth Small',
                    'y_md' => 'Youth Medium',
                    'y_lg' => 'Youth Large',
                    'y_xl' => 'Youth X-Large',
                    'a_xs' => 'Adult X-Small',
                    'a_sm' => 'Adult Small',
                    'a_md' => 'Adult Medium',
                    'a_lg' => 'Adult Large',
                    'a_xl' => 'Adult X-Large',
                    'a_2x' => 'Adult 2X-Large'
                  )
                );
              }
              break;
            case 'gear':
            case 'event':
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'Players Full Name',
                  'label' => 'Player Name ' . $i,
                  'name'  => 'player_name_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
              }
              break;
            case 'tournaments':
              $handle = g365_process_data_point('folder', $product_name);
              $extra_fields[] = array(
                'title'       => '',
                'type'        => 'text',
                'placeholder' => 'Club Name',
                'label'       => 'Club Name',
                'name'        => 'club_name',
                'required'    => true,
                'class'       => array($product_cat_slug)
              );
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'title'       => $product_name,
                  'type'        => 'text',
                  'placeholder' => 'Team Name',
                  'label'       => 'Team Name',
                  'label_class' => array('woo-registration__label'),
                  'name'        => 'team_name_' . $i,
                  'required'    => true,
                  'handle'      => $handle . '_' . $i,
                  'sub_title'   => "$product_name: Team $i",
                  'class'       => array($product_cat_slug, 'woo-registration__input', 'inputLong')
                );
                $extra_fields[] = array(
                  'title'       => $product_name,
                  'type'        => 'select',
                  'placeholder' => '',
                  'label'       => 'Type ',
                  'label_class' => array('woo-registration__label'),
                  'name'        => 'team_type_' . $i,
                  'required'    => true,
                  'handle'      => $handle . '_' . $i,
                  'class'       => array($product_cat_slug, 'woo-registration__input'),
                  'options'     => array(
                          ''      => 'Team Type',
                          'boys'  => 'Boys',
                          'girls' => 'Girls'
                  )
                );
                $extra_fields[] = array(
                  'title'       => $product_name,
                  'type'        => 'select',
                  'placeholder' => '',
                  'label'       => 'Division ',
                  'label_class' => array('woo-registration__label'),
                  'name'        => 'division_' . $i,
                  'required'    => true,
                  'handle'      => $handle . '_' . $i,
                  'class'       => array($product_cat_slug, 'woo-registration__input',  'woo-registration__input--middle'),
                  'options'     => array(
                            ''    => 'Requested Division',
                            '8U'  => "8U / 2nd Grade",
                            '9U'  => "9U / 3rd Grade",
                            '10U' => "10U / 4th Grade",
                            '11U' => "11U / 5th Grade",
                            '12U' => "12U / 6th Grade",
                            '13U' => "13U / 7th Grade",
                            '14U' => "14U / 8th Grade",
                            '15U' => "15U / Frosh/Soph",
                            '16U' => "16U / JV",
                            '17U' => "17U / Varsity",
                            'G3'  => "Girls 3th Grade",
                            'G4'  => "Girls 4th Grade",
                            'G5'  => "Girls 5th Grade",
                            'G6'  => "Girls 6th Grade",
                            'G7'  => "Girls 7th Grade",
                            'G8'  => "Girls 8th Grade",
                            'GFS' => "Frosh/Soph Girls",
                            'GJV' => "JV Girls",
                            'GV'  => "Varsity Girls",
                  )
                );
                $extra_fields[] = array(
                  'title'       => $product_name,
                  'type'        => 'select',
                  'placeholder' => '',
                  'label'       => 'Level ',
                  'label_class' => array('woo-registration__label'),
                  'name'        => 'team_level_' . $i,
                  'required'    => true,
                  'handle'      => $handle . '_' . $i,
                  'class'       => array($product_cat_slug, 'woo-registration__input'),
                  'options'     => array(
                          ''       => 'Team Level',
                          'Gold'   => 'Gold',
                          'Silver' => 'Silver',
                          'Bronze' => 'Bronze',
                          'Copper' => 'Copper',
                  )
                );
              }
              break;
              
              
              case 'dcp_team_info':
              $handle = g365_process_data_point('folder', $product_name);
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'School Name',
                  'label' => 'School Name ' . $i,
                  'name'  => 'school_name_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
              }
              break;
              
              case 'highlight_film':
              $handle = g365_process_data_point('folder', $product_name);
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'title'       => $product_name,
                  'type'        => 'text',
                  'placeholder' => 'Team Name',
                  'label'       => 'Team Name',
                  'label_class' => array('woo-registration__label'),
                  'name'        => 'team_name_' . $i,
                  'required'    => true,
                  'handle'      => $handle . '_' . $i,
                  'sub_title'   => "$product_name: Team $i",
                  'class'       => array($product_cat_slug, 'woo-registration__input', 'inputLong')
                );
                $extra_fields[] = array(
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'Players Full Name',
                  'label' => 'Player Name ',
                  'name'  => 'player_name_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
                $extra_fields[] = array(
                  'title'       => $product_name,
                  'type'        => 'select',
                  'placeholder' => '',
                  'label'       => 'Division ',
                  'label_class' => array('woo-registration__label'),
                  'name'        => 'division_' . $i,
                  'required'    => true,
                  'handle'      => $handle . '_' . $i,
                  'class'       => array($product_cat_slug, 'woo-registration__input'),
                  'options'     => array(
                            ''    => 'Requested Division',
                            '8U'  => "8U / 2nd Grade",
                            '9U'  => "9U / 3rd Grade",
                            '10U' => "10U / 4th Grade",
                            '11U' => "11U / 5th Grade",
                            '12U' => "12U / 6th Grade",
                            '13U' => "13U / 7th Grade",
                            '14U' => "14U / 8th Grade",
                            '15U' => "15U / Frosh/Soph",
                            '16U' => "16U / JV",
                            '17U' => "17U / Varsity",
                            'G4'  => "Girls 4th Grade",
                            'G5'  => "Girls 5th Grade",
                            'G6'  => "Girls 6th Grade",
                            'G7'  => "Girls 7th Grade",
                            'G8'  => "Girls 8th Grade",
                            'GFS' => "Frosh/Soph Girls",
                            'GJV' => "JV Girls",
                            'GV'  => "Varsity Girls",
                  )
                );
              }
              break;
              
              case 'college_coach_school':
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'School Name',
                  'label' => 'School Name ' . $i,
                  'name'  => 'school_name_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
              }
              break;
              
              case 'ticket':
                $extra_fields[] = array(
                  'label'   => 'I acknowledge all admissions sales are final and not eligible for refunds',
                  'name'    => 'checkbox',
                  'type'    => 'checkbox',
                  'required'=> true,
                  'class'   => array($product_cat_slug)
              );
              break;
            
              
              
          }
        }
        continue;
      }
      //add type to types array;
      $g365_cart_types[] = $form_type_opt[$product_cat_slug];
      //start the category if there isn't one.
      if( empty($g365_cart_items[ $product_cat_id ]) ) $g365_cart_items[ $product_cat_id ] = array( 'name' => $product_cat_name, 'title' => ($product_cats[0]->description), 'type' => ($form_type_opt[$product_cat_slug]), 'target' => ($form_type_target[$product_cat_slug]), 'items' => array() );
      //start the product if there isn't one.
      if( empty($g365_cart_items[ $product_cat_id ]['items'][ $product_id ]) ) $g365_cart_items[ $product_cat_id ]['items'][ $product_id ] = array( 'name' => $product_name, 'vars' => array() );
      //write some data to build the form with js
      $g365_cart_items[ $product_cat_id ][ 'items' ][ $product_id ][ 'vars' ][ $product_var_id ] = array(
        'id' => $product_id,
        'name' => ((empty($product_event_divisions_pull->name)) ? $product_name : $product_event_divisions_pull->name),
        'full_name' => ((empty($product_event_divisions_pull->short_name)) ? stripslashes($_product->get_name()) : $product_event_divisions_pull->short_name),
        'var_id' => $product_var_id,
        'var_name' => $product_var_name,
        'sku' => $_product->get_sku(),
        'qty' => $cart_item['quantity'],
        'event_divisions' => ($product_event_divisions === null) ? 0 : $product_event_divisions
      );
      $g365_cart_items[ $product_cat_id ][ 'items' ][ $product_id ][ 'vars' ][ $product_var_id ][$form_type_target[$product_cat_slug]] = $product_event_link;
    }
    $extra_fields[] = array(
      'label'   => 'Where did you find us?',
      'name'    => 'findus',
      'type'    => 'select',
      'required'=> false,
      'class'   => array('input', 'medium-margin-bottom', 'hide'),
      'options' => g365_return_keys( 'findus_options_key' )
    );
    
//     $extra_fields[] = array(
//       'title' => $product_name,
//       'type'  => 'text',
//       'placeholder'=> 'Full Name',
//       'label' => 'Full Name ' . $i,
//       'name'  => 'full_name_' . $i,
//       'required'=> true,
//       'class' => array($product_cat_slug, 'input')
//     );
    
//     $extra_fields[] = array(
//         'title' => $product_name,
//         'type'  => 'date',
//         'placeholder'=> 'Select Date',
//         'label' => 'Select Date ' . $i,
//         'name'  => 'select_date_' . $i,
//         'required'=> true,
//         'class' => array($product_cat_slug),
//         'custom_attributes' => array(
//             'min' => date('Y-m-d')  // Sets the minimum date to today's date
//         )
//     );
    
    $g365_cart_types = array_unique( $g365_cart_types );
//     print_r($extra_fields);
    return array($g365_cart_items, $g365_cart_types, $extra_fields);
  }
}

//global to use processed cart data
$cart_parse = array();

//start the process at the top of the cart load
add_filter( 'woocommerce_before_cart' , 'g365_cart_setup');
function g365_cart_setup() {
  //load in global proc var
  global $cart_parse;
  //process cart items
  $cart_parse = g365_cart_process();
  //if we don't have items that need g365 data managment, and we need data collected, add it
  if(!empty($cart_parse[2]) ){
    //pull the array into a var for the function closure
    $order_data_field = $cart_parse[2];
    //see if we need to include a title
    $front_end_title = (empty($cart_parse[0])) ? true : false;
    //add the field with the parameters from the cart process
    add_filter( 'woocommerce_checkout_inside_top' , function( $fields ) use ( $order_data_field, $cart_parse ) { return g365_player_checkout_field( $field, $order_data_field, $front_end_title ); });
  }
}

//add support for custom order data
add_filter('woocommerce_form_field_order_data', 'g365_order_data_form_field', 999, 4);
function g365_order_data_form_field($no_parameter, $key, $args, $value) {
  $current_user = wp_get_current_user();
  $admin_key = g365_make_admin_key();

  $presets = [];
  foreach( $args['g365_cart_types'] as $dex => $type ){
    switch( $type ){
      case 'tournaments':
      case 'dcp_team_info':
      case 'camps':
      case 'dcp_player_registration':
      case 'passport':
      case 'league':
      case 'club_team':
      case 'rosters_event':
        $current_user = wp_get_current_user();
        $presets[] = $type . '_preset:::user_ac::' . ((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . $current_user->ID;
        break;
    }
  }

  if($presets) $presets = 'data-g365_init_pre="' . implode('|', $presets) . '"';

  //return string
  $field = '<div class="grid-x grid-margin-x small-margin-bottom" id="event_details"><div class="cell small-12 medium-8 large-6"><header class="entry-header"><h2 class="entry-title">' . __('Registration') . '</h2></header><div id="g365_registration_fields">';
  $field .= '<script type="text/javascript">var g365_form_details = {"items" : ' . json_encode($args['g365_cart_items']) . ', "wrapper_target" : "g365_form_options_anchor", "user_org": "' . get_user_meta($current_user->ID, '_default_org', true) . '", "admin_key": "' . $admin_key . '"};</script><div><div id="g365_form_options_anchor" data-g365_type="' . implode('|', $args['g365_cart_types']) . '"' . $presets . '></div></div>';
  $field .= '</div></div></div>';
//   echo $field;
  return $field;
  //
  // end update functionality
  //
}

//make the admin credential string
function g365_make_admin_key() {
  $grassroots_keys = get_option( 'spp_connector' );
  $current_user = wp_get_current_user();
  $email = ( empty($current_user->user_email) ) ? '' : ':::' . $current_user->user_email ;
  $admin_key = '';
  if( !empty($grassroots_keys['connector_data']['trans_key'])  && !empty($grassroots_keys['connector_data']['trans_id']) ) $admin_key = 'Basic ' . base64_encode( $grassroots_keys['connector_data']['trans_key'] .  $grassroots_keys['connector_data']['trans_id'] . ',' . site_url() . ':::' . $current_user->ID . $email);
  return $admin_key;
}

//to collect data for each cart based on items purchased
add_action( 'g365_collect_data_fields', 'g365_cart_order_fields' );
function g365_cart_order_fields($checkout) {
  //load in global proc var
  global $cart_parse;
  //if we have cart items that we are manageing with g365 add the fields for data collection
  if( !empty($cart_parse[0]) ) {
    //add field with woocommerce handler
    woocommerce_form_field( 'order_data', array(
      'type'         => 'order_data',
      'class'        => array(),
      'required'     => true,
      'g365_cart_items' => $cart_parse[0],
      'g365_cart_types' => $cart_parse[1]
    ), '' );
  }
}

//field function for custom data collection unmanaged by g365
function g365_player_checkout_field( $checkout, $extra_fields = null, $front_end_title) {
  if( $extra_fields === null ) return;
  if( $front_end_title ) echo '<h2 class="entry-title">Registrations</h2>';
  //create a custom field that can be referenced to get the names of all the custom fields
  $added_field_names_required = array();
  $added_field_names = array();
  //make a field for each product that needs it

  
  //vars for tournaments layout
  $tournament_fields = array();
  $tournament_fields_titles = array();
  $general_fields = array();
  foreach( $extra_fields as $dex => $field_arr ) {
    //create a name unique to the order for each field
    $field_name = $field_arr['type'] . '_' . $field_arr['name'] . '_' . $dex;
    //figure out if we need to set the variable to required or not for post-processing
    if( $field_arr['required'] ) {
      $added_field_names_required[] = $field_name;
    } else {
      $added_field_names[] = $field_name;
    }
    //setup the woocomm array to add the fields
    $field_arr_process = array(
      'type'        => $field_arr['type'],
      'class'       => $field_arr['class'],
      'label'       => $field_arr['label'] . ((!empty($field_arr['title'])) ? '' : ''),
      'label_class' => $field_arr['label_class'],
      'input_class' => $field_arr['input_class'],
      'required'    => $field_arr['required'],
      'placeholder' => $field_arr['placeholder'],
      'return'      => true
    );
        
    // old label for above     'label'       => $field_arr['label'] . ((!empty($field_arr['title'])) ? ' for ' . $field_arr['title'] : ''),
//   echo '<pre>';
//   print_r($field_arr_process);
//   echo '</pre>';


    //if the field requires options data add it to the array
    if( ($field_arr['type'] === 'select' || $field_arr['type'] === 'radio' || $field_arr['type'] === 'multiselect') && !empty($field_arr['options']) ) $field_arr_process['options'] = $field_arr['options'];
    //if it's a tournament process it differently
    if( $field_arr['class'][0] === 'tournaments' || $field_arr['class'][0] === 'dcp_team_info' ) {  
//       $field_arr_process['class'][] = 'input-group-field';
//         'label_class' => array(),  
//         'input_class' => array(),  
      if( !empty($field_arr['sub_title']) ) $tournament_fields_titles[$field_arr['handle']] .= '<h5 class="tiny-margin-bottom">' . $field_arr['sub_title'] . '</h5>';
      if( empty($field_arr['handle']) ) {
        $tournament_fields[''] = woocommerce_form_field( $field_name, $field_arr_process, '');
      } else {
        $tournament_fields[$field_arr['handle']] .= woocommerce_form_field( $field_name, $field_arr_process, '');
      }
    } else {
      $general_fields[] = woocommerce_form_field( $field_name, $field_arr_process, '');
    }
  }
  if( !empty($tournament_fields) ) {
    foreach( $tournament_fields as $handle => $field_html ) {
      if( !empty($tournament_fields_titles[$handle]) ) echo $tournament_fields_titles[$handle];
      echo '<div class="border-radius woo-registration__wrapper gray-bg small-padding large-margin-bottom">';
      echo $field_html;
      echo '</div>';
    }
  }
  if( !empty($general_fields) ) {
    foreach( $general_fields as $dex => $field_html ) {
      echo '<div class="item">';
      echo $field_html;
      echo '</div>';
    }
  }
  //don't forget to add the field names reference otherwise we can't process this data at all.
  echo '<input type="hidden" name="g365_extra_data_required" value="' . implode(',',$added_field_names_required) . '" />';
  echo '<input type="hidden" name="g365_extra_data" value="' . implode(',',$added_field_names) . '" />';
}

//make the extra data required based on the field names variable
add_action('woocommerce_checkout_process', 'g365_player_checkout_field_process');
function g365_player_checkout_field_process() {
  // Check if we have extra data
  if ( $_POST['g365_extra_data_required'] ) {
    //parse and loop through the set to make sure we have all the fields filled out
    $fields = explode(',', $_POST['g365_extra_data_required']);
    foreach( $fields as $dex => $key ) {
      if( ! $_POST[ $key ] ) wc_add_notice( __( "$key field  is required." ), 'error' );
    }
  }
}


add_filter( 'woocommerce_email_classes', 'passport_custom_woocommerce_emails' );

function passport_custom_woocommerce_emails( $email_classes ) {

// 	$upload_dir = get_site_url();
	
	require_once( dirname( __FILE__ ) . '/emails/class-wc-email-customer-completed-passport-purchase-order.php' );
	$email_classes['WC_Email_Customer_Completed_Passport_Purchase_Order'] = new WC_Email_Customer_Completed_Passport_Purchase_Order(); // add to the list of email classes that WooCommerce loads


	return $email_classes;
	
}

add_filter( 'woocommerce_email_classes', __CLASS__ . 'passport_renewal_custom_woocommerce_emails', 10, 2 );

function passport_renewal_custom_woocommerce_emails( $email_classes ) {

// 	$upload_dir = get_site_url();
  
  
	require_once( dirname( __FILE__ ) . '/emails/class-wcs-email-processing-passport-renewal-orders.php' );
	$email_classes['WCS_Email_Processing_Passport_Renewal_Orders'] = new WCS_Email_Processing_Passport_Renewal_Orders(); // add to the list of email classes that WooCommerce loads
// 	echo('test');


	return $email_classes;
	
}


add_filter( 'woocommerce_email_classes', __CLASS__ . 'passport_renewal_custom_woocommerce_emails_failed', 10, 2 );

function passport_renewal_custom_woocommerce_emails_failed( $email_classes ) {

// 	$upload_dir = get_site_url();
  
  
	require_once( dirname( __FILE__ ) . '/emails/class-wc-email-customer-failed-passport-purchase-order.php' );
	$email_classes['WCS_Email_Failed_Passport_Renewal_Orders'] = new WCS_Email_Failed_Passport_Renewal_Orders(); // add to the list of email classes that WooCommerce loads
// 	echo('test');


	return $email_classes;
	
}


// //if a subscription changes update the correct record
// // add_action( 'woocommerce_subscription_status_updated', 'g365_pp_sub_manager');
// function g365_pp_sub_manager( $subscription_id, $old_status, $new_status ) {
// //    $file_content = $subscription_id.' '.$old_status.' '.$new_status;
// //    $filename = 'test_file.txt';
// //    file_put_contents($filename, $file_content);
// }
// Renew passport on completed subscription renewal
// g365_subscription_pp_renewal($subscription, $last_order);
add_action('woocommerce_subscription_renewal_payment_complete', 'g365_subscription_pp_renewal', 10, 2);
function g365_subscription_pp_renewal($subscription, $last_order){
  $order_id = $subscription->parent_id;
  $subscription_number = $subscription->get_order_number();
//   $get_new_order_id = $last_order->id;
  g365_subscription_pp_renewal_fn($order_id);
}
add_action( 'woocommerce_payment_complete', 'g365_order_payment_complete' );
function g365_order_payment_complete( $order_id ){
  //get order data to see what was purchased
  $order = wc_get_order( $order_id );
  //parse the skus of all purchased products
  global $wpdb;
  foreach ($order->get_items() as $item) {
    $product = wc_get_product($item->get_product_id());
    $prod_sku = $product->get_sku(); $prod_type = substr($prod_sku, 0, strpos($prod_sku, '-')); $prod_ev_id = substr($prod_sku, strpos($prod_sku, '-') + 1);
    if( in_array($product->get_sku(), array('GPP-001', 'GPP-002', 'GPP-003')) ) {
      $order_data_meta = get_post_meta( $order_id, '_order_data', true );
      if( !empty($order_data_meta) ) {
        $order_data_meta = explode( '|', $order_data_meta );
        $order_data_meta_proc = array();
        foreach( $order_data_meta as $dex => $data ) {
          $data = explode( ',', $data );
          $type = array_shift($data);
          if( count($data) > 0 ) {
            $order_data_meta_proc[$type] = array('ids' => $data, 'result' => array());
            switch( $type ) {
              case 'passport':
                $order_data_add_meta = get_post_meta( $order_id, '_order_data_add', true );
                //this is the parse string that should come out of a successfull pp purchase: passport,200651:::seasons::2021. For monthly passport,200651:::monthly::2021:08
                if( !empty($order_data_add_meta) ) {
                  //explode the types, in this case we just care about 'passport' for additional processing
                  $order_data_add_meta = explode( '|', $order_data_add_meta );
                  //loop through the saved checkout data
                  foreach( $order_data_add_meta as $dex => $data ) {
                    //break down the var we get
                    $data = explode( ',', $data );
                    //pull the key off the front
                    $type = array_shift($data);
                    //if we have any data, continue to process
                    if( count($data) === 1 ) {
                      //break down the var again
                      $sub_data = explode( ':::', $data[0] );
                      //pull the id off the string
                      $sub_id = array_shift($sub_data);
                      //break the last two vars that we need to write the query
                      $sub_data = explode( '::', $sub_data[0] );
                      if( count($sub_data) === 2 ) {
                        //should be either 'seasons' or 'events'
                        $sub_type = $sub_data[0];
                        //the year or event that is being unlocked
                        $sub_target = $sub_data[1];
                        //reference to the time that the unlocking happened
                        $today = date("Y-m-d H:i:s");
                        //WARNING :: pull the line item to write the cost, come up with a lock for this, right now multiple items with over write each other, which is only a problem, with multiple pp purchased and discounted separately, which might not be possible.
                        $amount = $item->get_total();
                        //all the tables we have to get data from
                        $wpdb_stats = $wpdb->g365_stats;
                        if(trim($sub_type) === 'monthly'){
                          $sub_month_data = explode( ':', $sub_data[0] );
                          $today_year = wp_date('Y');
                          //try to write the payment confirmation timestamp to any records that we find in the order details //christian maybe here
                          $order_data_meta_proc[$type]['result'][$sub_id] = ( $wpdb->query( "UPDATE $wpdb_stats SET stats = JSON_SET(stats, '$.$sub_type.\"$today_year\".\"$sub_month_data\"', JSON_OBJECT('paid', '$today', 'order_id', $order_id, 'amount', $amount)) WHERE id = $sub_id;" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
                        }else{
                        //try to write the payment confirmation timestamp to any records that we find in the order details //christian maybe here
                        $order_data_meta_proc[$type]['result'][$sub_id] = ( $wpdb->query( "UPDATE $wpdb_stats SET stats = JSON_SET(stats, '$.$sub_type.\"$sub_target\"', JSON_OBJECT('paid', '$today', 'order_id', $order_id, 'amount', $amount)) WHERE id = $sub_id;" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully."; 
                        }
                      }
                    }
                  }
                }
//                 else{
//                   //explode the types, in this case we just care about 'passport' for additional processing
//                   $order_data_add_meta = explode( '|', $order_data_add_meta );
//                   //loop through the saved checkout data
//                   foreach( $order_data_add_meta as $dex => $data ) {
//                     //break down the var we get
//                     $data = explode( ',', $data );
//                     //pull the key off the front
//                     $type = array_shift($data);
//                     //if we have any data, continue to process
//                     if( count($data) === 1 ) {
//                       //break down the var again
//                       $sub_data = explode( ':::', $data[0] );
//                       //pull the id off the string
//                       $sub_id = array_shift($sub_data);
//                       //break the last two vars that we need to write the query
//                       $sub_data = explode( '::', $sub_data[0] );
//                       if( count($sub_data) === 2 ) {
//                         //should be either 'seasons' or 'events'
//                         $sub_type = $sub_data[0];
//                         //the year or event that is being unlocked
//                         $sub_target = $sub_data[1];
//                         //reference to the time that the unlocking happened
//                         $today = date("Y-m-d H:i:s");
//                         //WARNING :: pull the line item to write the cost, come up with a lock for this, right now multiple items with over write each other, which is only a problem, with multiple pp purchased and discounted separately, which might not be possible.
//                         $amount = $item->get_total();
//                         //all the tables we have to get data from
//                         $wpdb_stats = $wpdb->g365_stats;
//                         $order_data_meta_proc[$type]['result'][$sub_id] = ( $wpdb->query( "UPDATE $wpdb_stats SET stats = JSON_SET(stats, '$.$sub_type.\"$sub_target\"', JSON_OBJECT('paid', '$today')) WHERE id = $sub_id;" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
//                       }
//                     }
//                   }
//                 }
                break;
            }
          }
        }
      }
    }
    elseif (in_array( $prod_type, array('DCP') )){
      $dbs = json_decode(dbs());
      $wpdb->query("INSERT INTO $dbs->favorites VALUES ('".wp_date('Y-m-d H:i:s')."', '".wp_date('Y-m-d H:i:s')."',DEFAULT,1,'".$prod_ev_id."','".get_current_user_id()."',NULL,'{}','{}')");
    }
  }
}





//if we have extra data, save it
add_action( 'woocommerce_checkout_update_order_meta', 'g365_checkout_field_update_order_meta' );
function g365_checkout_field_update_order_meta( $order_id ) {
  //save the order_data if we have any
  if( !empty( $_POST['order_data'] ) ) update_post_meta( $order_id, '_order_data', $_POST['order_data'] );
  //save the order_data_add if we have any
  if( !empty( $_POST['order_data_add'] ) ) update_post_meta( $order_id, '_order_data_add', $_POST['order_data_add'] );
  //join the extra_data list if we need to
  $g365_extra_data = array();
  $g365_extra_data_fields = array();
  if ( $_POST['g365_extra_data'] ) {
    $g365_extra_data[] = $_POST['g365_extra_data'];
    $g365_extra_data_fields[] = $_POST['g365_extra_data'];
  }
  if ( $_POST['g365_extra_data_required'] ) {
    $g365_extra_data[] = $_POST['g365_extra_data_required'];
    $g365_extra_data_fields[] = $_POST['g365_extra_data_required'];
  }
  //save the field reference so we know what to pull later
  if ( !empty($g365_extra_data) ) update_post_meta( $order_id, 'g365_extra_data', sanitize_text_field( implode(',', $g365_extra_data) ) );
  // Check if we have extra data
  if ( !empty($g365_extra_data_fields) ) {
    //parse extra field names, then loop and save
    $fields = explode(',', implode(',', $g365_extra_data_fields));
    foreach( $fields as $dex => $key ) {
      if( !empty($_POST[ $key ]) ) update_post_meta( $order_id, $key, sanitize_text_field( $_POST[ $key ] ) );
    }
  }
}

//display any extra data at end of admin order paqge
add_action( 'woocommerce_admin_order_data_after_billing_address', 'g365_player_checkout_field_display_admin_order_meta', 10, 1 );
function g365_player_checkout_field_display_admin_order_meta($order){
  //parse the field names, if we have them, then echo them
  $fields = get_post_meta( $order->id, 'g365_extra_data', true );
  if( !empty($fields) ) {
    $fields = explode(',', $fields);
    foreach( $fields as $dex => $key ) {
      //clean up the cariable name a little
      $key_parts = explode('_', $key);
      unset($key_parts[0]);
      array_pop($key_parts);
      $key_parts = implode(' ', $key_parts);
      //get the actual value
      $key_value = get_post_meta( $order->id, $key, true );
      //if we need to relabel it, go ahead
      if( $key_parts === 'findus' ) {
        $key_parts = 'How did they find us';
        $options_key = g365_return_keys( 'findus_options_key' );
        $key_value = $options_key[ $key_value ];
      }
      echo '<p><strong>' . ucwords( $key_parts ) . ':</strong> ' . $key_value . '</p>';
    }
  }
}

//prevent users from canceling subscriptions, extend for per product control
function g365_edit_memberships_actions( $actions ) {
  // Get the current active user
  $user_id = wp_get_current_user();

  if(!$user_id) return $actions; // No valid user, abort

  // Only query active subscriptions
  $memberships_info = wc_memberships_get_user_active_memberships($user_id, array( 'status' => array( 'active' ) ));

  // Loop through each active subscription
  foreach ($memberships_info as $membership) {
    $subscription_start_date = date("Y/m/d", strtotime($membership->get_start_date()));
    //$subscription_end_date = date("Y/m/d", strtotime($membership->get_end_date()));
    //$subscription_name = $membership->get_plan()->get_name();
    //$subscription_id = $membership->get_plan()->get_id();

    if($subscription_id == 'YOUR_ID') { // Active subscription
      // Compare the starting date of the subscription with the current date
      $datetime1 = date_create($subscription_start_date);
      $datetime2 = date_create(date(time()));

      $interval = date_diff($datetime1, $datetime2);

      if($interval->format('%m') <= 11) {
        // remove the "Cancel" action for members
        unset( $actions['cancel'] );
      }
    }
  }
 return $actions;
}

add_filter( 'wc_memberships_members_area_my-memberships_actions', 'g365_edit_memberships_actions' );
add_filter( 'wc_memberships_members_area_my-membership-details_actions', 'g365_edit_memberships_actions' );

/**
 * Add series products functionality
 */
add_action( 'woocommerce_product_options_related', 'product_series_support', 10, 2 );
function product_series_support() { global $post; ?>
  <div class="options_group">
		<p class="form-field">
			<label for="series_ids"><?php esc_html_e( 'Series', 'woocommerce' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="series_ids" name="series_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
        $product_ids = get_post_meta( $post->ID, '_series_ids', true );
        foreach ( $product_ids as $product_id ) {
          $product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
          }
				}
				?>
			</select> <?php echo wc_help_tip( __( 'A product series will group products together in a way that supports an event with multiple separate dates.', 'woocommerce' ) ); // WPCS: XSS ok. ?>
		</p>
	</div>
<?php }

add_action( 'woocommerce_process_product_meta', 'save_product_series' );
function save_product_series( $post_id ) {
  // grab the series array from $_POST
  $series_ids = isset( $_POST[ 'series_ids' ] ) ? array_map( 'intval', (array) wp_unslash( $_POST['series_ids'] ) ) : array();
	update_post_meta( $post_id, '_series_ids', ( !empty( $series_ids ) ) ? $series_ids : [] );
}

add_action( 'woocommerce_single_product_summary', 'add_series_options', 11 );
function add_series_options() {
  // get the series data if the is any
  global $post;
  $product_ids = get_post_meta( $post->ID, '_series_ids', true );
  if( !empty( $product_ids ) && is_array($product_ids) ) {
    $series_products = array();
    $series_events = array();
    $series_order = array();
    $product_ids[] = $post->ID;
    foreach ( $product_ids as $product_id ) {
      $series_products[ $product_id ] = wc_get_product( $product_id );
      $series_events[ $product_id ] = $series_products[ $product_id ]->get_meta( '_event_link' );
      if( !empty($series_events[ $product_id ]) ) {
        $series_events[ $product_id ] = array( 'data_pull' => g365_get_event_data($series_events[ $product_id ], true), 'label' => array() );
        if( !empty($series_events[ $product_id ][ 'data_pull' ]) ) {
          $series_order[ $product_id ] = $series_events[ $product_id ][ 'data_pull' ]->eventtime;
          if( !empty($series_events[ $product_id ][ 'data_pull' ]->dates) ) {
            $series_events[ $product_id ][ 'label' ][] = g365_build_dates($series_events[ $product_id ][ 'data_pull' ]->dates, 1, true);
          } else {
            $series_events[ $product_id ][ 'label' ][] = $series_events[ $product_id ][ 'data_pull' ]->short_name;
          }
          if( !empty($series_events[ $product_id ][ 'data_pull' ]->locations) ) $series_events[ $product_id ][ 'label' ][] = implode(', ', array_map(function($val){ return explode(',', $val)[0]; }, explode('|', $series_events[ $product_id ][ 'data_pull' ]->locations)));
          $series_events[ $product_id ][ 'label' ] = implode(' | ', $series_events[ $product_id ][ 'label' ]);
        }
      }
    }
    asort( $series_order );
    echo '<div class="cell small-12">
  <h5>Available Dates/Locations</h5>
    <select id="series_selector">';
    foreach ( $series_order as $product_id => $event_time ) {
      echo '<option value="' . esc_attr( $series_products[ $product_id ]->get_permalink() ) . '"' . selected( $product_id, $post->ID, false ) . '>' . wp_kses_post( $series_events[ $product_id ][ 'label' ] ) . '</option>
      ';
    }
    echo '</select>
    </div>';
  }
}

//remove additional notes section of checkout
add_filter( 'woocommerce_checkout_fields' , 'g365_modify_checkout_fields' );
// Our hooked in function - $fields is passed via the filter!
function g365_modify_checkout_fields( $fields ) {
   unset($fields['order']['order_comments']);
   return $fields;
}

//validate the checkout form before finishing the process of writting data to g365 about the purchase
add_action('woocommerce_after_checkout_validation', 'process_g365_data');
function process_g365_data($form_data) {
  //if we have a order_data field and it's not filled, send it back to get that processed out.
  if( isset($_POST['order_data']) && $_POST['order_data'] === 'null' && wc_notice_count( 'error' ) == 0 )  wc_add_notice( 'G365 form needs to be processed or completed.', 'error');
}


function get_player_stats($wpdb, $player_id) {
    
    global $wpdb;
        $wpdb_orgs = $wpdb->g365_orgs;
        $wpdb_rosters = $wpdb->g365_rosters;
        $wpdb_events = $wpdb->g365_events;
        $wpdb_teams = $wpdb->g365_teams;
        $wpdb_players = $wpdb->g365_players;
        $wpdb_stats = $wpdb->g365_stats;
        $wpdb_stats = $wpdb->g365_stats;
  
    $query = $wpdb->prepare(
        "SELECT st.id, st.stats
        FROM $wpdb->g365_stats AS st
        WHERE st.event = 504 AND st.player = %d",
        $player_id
    );
    return $wpdb->get_results($query);
}

//display and edit the custom data
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'g365_checkout_field_display_admin_order_meta', 10, 1 );
//display and custom order data for admin
function g365_checkout_field_display_admin_order_meta($order){
  
  global $wpdb;
        $wpdb_orgs = $wpdb->g365_orgs;
        $wpdb_rosters = $wpdb->g365_rosters;
        $wpdb_events = $wpdb->g365_events;
        $wpdb_teams = $wpdb->g365_teams;
        $wpdb_players = $wpdb->g365_players;
        $wpdb_stats = $wpdb->g365_stats;
        $wpdb_stats = $wpdb->g365_stats;
  
  $order_data_meta = get_post_meta( $order->get_id(), '_order_data', true );
  $custom_field_value = get_post_meta($order->get_id(), '', true);
  $pssprt_id = explode("," , $order_data_meta);
  $pssprt_id = $pssprt_id[1];
  $order_status = $order->get_status();
  
  
  //if order meta is 0 then check the buyers owned data to see if any players are connected to their account. 
  //Then process passport to that player.
  if($pssprt_id == 0 && $order_status === 'completed' || $order_status === 'processing'){
    echo '<br> Passport did not connect. Creating the connection:<br><br>';
    $date_paid = $order->get_date_paid();
    $date_paid = $date_paid->format('Y-m-d');
    $ord_id = $order->get_id();
    $order_total = $order->get_total();
    
    $pssprt_id = (int)$custom_field_value['_customer_user'][0];
    $claimed_pl = get_user_meta($pssprt_id, '_user_owns_g365', true);
    
    echo 'Players with a connection to this email account: <br>';
    echo '<table>';
    $closest_stat = null;
    $closest_difference = null;
    $skip_update = false;
    $new_paid_date = $date_paid . ' 00:00:00';
     foreach ($claimed_pl as $claimed_pl_data) {
            foreach ($claimed_pl_data as $claimed_pl_id) {
                $player_name = g365_get_pl_data(['pl_id' => $claimed_pl_id], 'g365-pl-photo')[0]->name;
                echo "<tr style='display: block; border: 1px solid black; border-collapse: collapse; padding: 8px; margin-bottom: 1rem;' class='pp-no-connection'><td style=' border: 1px solid black; border-collapse: collapse; padding: 8px;' class='pp-no-connection single-block-pp'> <strong> ID: </strong> $claimed_pl_id </td> <td style=' border: 1px solid black; border-collapse: collapse; padding: 8px;' class='pp-no-connection single-block-pp' > <strong> player: </strong> $player_name</td></tr>";

                //grabbing all the player stats who has a connection with the user who purchased
                $stats = get_player_stats($wpdb, $claimed_pl_id);
                if ($stats) {
                  //inside this loop if where we find the date that is closes to the date purchased for this product to estimate who the passport is going too.
                    foreach ($stats as $stat) {
                        $stats_data = json_decode($stat->stats, true);
                        foreach ($stats_data['seasons'] as $year => $data) {
                            
                            if ($data['paid'] === $new_paid_date) {
                                $skip_update = true; // Set the flag to true if match found
                                $connected_stat = ['id' => $stat->id, 'paid' => $data['paid'], 'player' => $player_name, 'pl_id' => $claimed_pl_id, 'year' => $year];
                                break 2; // Break both foreach loops
                            }
                            if (intval($year) === intval(date('Y', strtotime($date_paid))) || intval($year) === intval(date('Y', strtotime($date_paid . ' -1 year')))) {
                                $paid_date = date('Y-m-d', strtotime($data['paid'] . ' +1 year')); // Add one year to the paid date
//                                 echo '<td>' .  $paid_date . '</td>';
                                $difference = abs(strtotime($date_paid) - strtotime($paid_date)) / (60 * 60 * 24);
//                                 echo '<td>' .  $difference . '</td>';
                                if ($closest_difference === null || $difference < $closest_difference) {
                                    $closest_stat = ['id' => $stat->id, 'paid' => $data['paid'], 'player' => $player_name, 'pl_id' => $claimed_pl_id, 'year' => $year, 'new_year' => $paid_date, 'stat_check' => $stats_data['seasons'] ];
                                    $closest_difference = $difference;
                                }
                            }
                          
                        }
                    }
                }
            }
        }
    echo '</table>';
    
    //to check which stat ID and the time previously paid uncomment below.
    $time_zone = new DateTimeZone('America/Los_Angeles');   
    $paid_date = new DateTime($closest_stat['paid'], $time_zone);
    // Get the year part of the closest_stat['year']
    $year = intval($closest_stat['year']);
    $pl_id = $closest_stat['pl_id'];
    // Check if the paid date is before September 1st of the closest_stat['year']
    if ($paid_date->format('m-d') < '09-01' && $paid_date->format('Y') == $year) {
//         echo "Paid date is before September 1st of {$closest_stat['year']}. <br>";
            
        // If no match found, proceed with the update
        if (!$skip_update) {
        echo '<br> Connection made too: <br> <table>  <tr style="display: block; border: 1px solid black; border-collapse: collapse; padding: 8px; margin-bottom: 1rem; width: 200%;" class="pp-no-connection"> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> Player ID: </strong> ' . $closest_stat['pl_id'] . '</td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> Player: </strong> ' . $closest_stat['player'] . '</td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong>Season: </strong> ' . $closest_stat['year'] . ' </td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong>Date: </strong> ' . $closest_stat['paid'] . ' </td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> ID: </strong> ' . $closest_stat['id'] . ' </td> </td> </tr></table><br>';    
        $query = $wpdb->prepare(
            "UPDATE $wpdb->g365_stats
            SET enabled = 1,
                stats = JSON_SET(
                    stats,
                    CONCAT('$.seasons.\"', %s, '\".paid'),
                    %s,
                    CONCAT('$.seasons.\"', %s, '\".amount'),
                    %d,
                    CONCAT('$.seasons.\"', %s, '\".order_id'),
                    %d
                )
            WHERE player = %d AND id = %d",
            $closest_stat['year'], // Placeholder for the year
            $new_paid_date,        // Placeholder for the new paid date
            $closest_stat['year'], // Placeholder for the year (for amount and order_id)
            $order_total,               // Placeholder for the amount
            $closest_stat['year'], // Placeholder for the year (for amount and order_id)
            $ord_id,             // Placeholder for the order_id
            $pl_id,                // Placeholder for the player ID
            $closest_stat['id']    // Placeholder for the stat ID
        );

        $result = $wpdb->query($query); // Execute the query
      
          
          
          echo '<br> what I want to update: ' . $order_data_meta;
          $order_data_meta_test = $order_data_meta;
          $order_data_meta_test = 'passport,' . $closest_stat['id'];
          echo '<br> updated: ' . $order_data_meta_test;
          $order_data_meta = $order_data_meta_test;
          
          
          } else {
              echo '<strong>Skipping update, connection made: </strong><br> 
                    
                    <table>  <tr style="display: block; border: 1px solid black; border-collapse: collapse; padding: 8px; margin-bottom: 1rem; width: 200%;" class="pp-no-connection"> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> Player ID: </strong> ' . $connected_stat['pl_id'] . '</td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> Player: </strong> ' . $connected_stat['player'] . '</td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong>Season: </strong> ' . $connected_stat['year'] . ' </td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong>Date: </strong> ' . $connected_stat['paid'] . ' </td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> ID: </strong> ' . $connected_stat['id'] . ' </td> </td> </tr></table>
                    
                    ';
          
              echo '<br> what I want to update: ' . $order_data_meta;
              $order_data_meta_test = $order_data_meta;
              $order_data_meta_test = 'passport,' . $connected_stat['id'];
              echo '<br> updated: ' . $order_data_meta_test;
              $order_data_meta = $order_data_meta_test;
          
          
          }
      
      
      
    } else {
      
//           echo " Paid date is on or after September 1st of {$closest_stat['year']}. <br>";
          $new_year = date('Y', strtotime($closest_stat['new_year'] ));
          $stats_data = $closest_stat['stat_check'];
//           echo $new_year .  '   ' . $new_paid_date . ' ' . $order_total. ' ' . $ord_id . ' ' . $pl_id . '      ' . $closest_stat['id'] . '    <br> updated: ' . print_r($stats_data) ;
          echo '<br><strong> Passport added to player: </strong><br> <table>  <tr style="display: block; border: 1px solid black; border-collapse: collapse; padding: 8px; margin-bottom: 1rem; width: 200%;" class="pp-no-connection"> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> Player ID: </strong> ' . $closest_stat['pl_id'] . '</td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> Player: </strong> ' . $closest_stat['player'] . '</td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong>Season: </strong> ' . $closest_stat['year'] . ' </td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong>Date: </strong> ' . $closest_stat['paid'] . ' </td> <td style=" border: 1px solid black; border-collapse: collapse; padding: 8px;" class="pp-no-connection single-block-pp" > <strong> ID: </strong> ' . $closest_stat['id'] . ' </td> </td> </tr></table>';
      
      
          $existing = false;
          // Iterate through each year in the stats data
          foreach ($stats_data as $year => $data) {
              // Check if the current year matches the new year you want to add
              if ($year == $new_year) {
                      $existing = true;
                      break;
              }
          }

          if(!$existing){
              //if the new season being added is not already added
              $query = $wpdb->prepare(
                  "UPDATE $wpdb->g365_stats
                  SET stats = JSON_INSERT(
                      stats,
                      '$.seasons.\"%d\"',
                      JSON_OBJECT(
                          'paid', %s,
                          'amount', %d,
                          'order_id', %d
                      )
                  )
                  WHERE player = %d AND id = %d",
                  $new_year, // Placeholder for the new year
                  $new_paid_date,            // Placeholder for the new paid date
                  $order_total,              // Placeholder for the new amount
                  $ord_id,                   // Placeholder for the new order_id
                  $pl_id,                    // Placeholder for the player ID
                  $closest_stat['id']        // Placeholder for the stat ID
              );

              $result = $wpdb->query($query); // Execute the query

                //how to test whats wrong with the query
//               if ($result !== false) {
//                   echo "Query executed successfully. $result rows were affected.";
//               } else {
//                   echo "Error executing query: " . $wpdb->last_error;
//               }
            
          } else {
            //if the new season being added is already added
            $query = $wpdb->prepare(
                "UPDATE $wpdb->g365_stats
                SET enabled = 1,
                    stats = JSON_SET(
                        stats,
                        CONCAT('$.seasons.\"', %s, '\".paid'),
                        %s,
                        CONCAT('$.seasons.\"', %s, '\".amount'),
                        %d,
                        CONCAT('$.seasons.\"', %s, '\".order_id'),
                        %d
                    )
                WHERE player = %d AND id = %d",
                $new_year, // Placeholder for the year
                $new_paid_date,        // Placeholder for the new paid date
                $new_year, // Placeholder for the year (for amount and order_id)
                $order_total,               // Placeholder for the amount
                $new_year, // Placeholder for the year (for amount and order_id)
                $ord_id,             // Placeholder for the order_id
                $pl_id,                // Placeholder for the player ID
                $closest_stat['id']    // Placeholder for the stat ID
            );

            $result = $wpdb->query($query); // Execute the query
            
            //how to test whats wrong with the query
//             if ($result !== false) {
//                   echo "Query updated  successfully. $result rows were affected.";
//               } else {
//                   echo "Error executing query: " . $wpdb->last_error;
//               }
            
          }
      
          echo '<br> what I want to update: ' . $order_data_meta;
          $order_data_meta_test = $order_data_meta;
          $order_data_meta_test = 'passport,' . $closest_stat['id'];
          echo '<br> updated: ' . $order_data_meta_test;
          $order_data_meta = $order_data_meta_test;
    }

    // Meta key
    $meta_key = '_order_data_add';
    $query = $wpdb->prepare(
        "UPDATE wp_54ab678738_postmeta 
        SET meta_value = REPLACE(meta_value, %s, %s)
        WHERE post_id = %d AND meta_key = %s",
        "passport,0", // Old passport number
        $order_data_meta,
        $ord_id,
        $meta_key
    );
    $result = $wpdb->query($query);
    
    // Meta key
    $meta_key_2 = '_order_data';
    $query_2 = $wpdb->prepare(
          "UPDATE wp_54ab678738_postmeta 
          SET meta_value = %s
          WHERE post_id = %d AND meta_key = '_order_data'",
          $order_data_meta,
          $ord_id
    );
    $result_2 = $wpdb->query($query_2);
    
    
  }
  $message = '<p>No additional order data to retrieve.</p>';
  if( !empty($order_data_meta) ) { 
    $message = '';
    $order_data_meta = explode( '|', $order_data_meta );
    $order_data_meta_proc = array();
    foreach( $order_data_meta as $dex => $data ) {
      $data = explode( ',', $data );
      $type = array_shift($data);
      $message .= '<h3>' . ucfirst($type) . '</h3>';
      if( count($data) > 0 ) {
        $order_data_meta_proc[$type] = array('ids' => $data);
        $order_data_meta_proc[$type]['data'] = array();
        switch( $type ) {
          case 'camps':
          case 'passport':
          case 'dcp_player_registration':
            //make the id string to get those records associated with the order
             
            $data_ids = implode(',', $data);
            $player_info = $wpdb->get_results(
              "SELECT players.name, players.grad_year, players.birthday
              FROM $wpdb_stats AS stats
              LEFT JOIN $wpdb_players AS players ON players.id = stats.player
              WHERE stats.id IN ($data_ids) AND stats.event = 504;"
            );
            
//             $player_info = $wpdb->get_results(
//               "SELECT players.name, players.grad_year, players.birthday
//               FROM $wpdb_stats AS stats
//               LEFT JOIN $wpdb_players AS players ON players.id = stats.player
//               WHERE stats.player = players.id AND stats.event = 504;"
//             );
            
//////////  passport identifier for additional order info ///////////////////////
//             $pp_order = wc_get_order( $order_id );
//             $pp_items = $order->get_items();
//             foreach ( $pp_items as $item ) {
//                 $pp_product_name = $item->get_name();
//                 $pp_product_id = $item->get_product_id();
// //                 $pp_product_variation_id = $item->get_variation_id();
//             }
// //             if passport is annual or monthly product id ru
// //             monthly on dev is 84826, different on live
//             if($pp_product_id == 84826 || $pp_product_id == 10388) {
//               $pp_terms_link = get_site_url();
              
//               if($pp_product_id == 10388) {
//                 $pp_terms_link .= '/annual-passport-terms';
//               }
              
//               if($pp_product_id == 84826) {
//                 $pp_terms_link .= '/monthly-passport-terms';
//               }
              
//               $message .= '<p>User Accepted ' . $pp_product_name . ' <a href="'.$pp_terms_link.'" target="_blank">Terms and Conditions</a> of subscription at time of purchase at ' .date_format($order->date_created,"m/d/y H:i:s") .'</p>';
//             }
//////////   end passport identifier for additional order info /////////////////
//             echo'<pre>';print_r($order);echo'</pre>';
            foreach($player_info as $result){
              $message .= '<p>';
              $message .= '<strong>Name:</strong> ' . $result->name . ' <br>';
              $message .= '<strong>Grad Year:</strong> ' . $result->grad_year.'<br>';
              $message .= '<strong>Birthday:</strong> ' . date('m/d/y', strtotime($result->birthday)) .'<br>';
//               $message .= '<strong>Birthday:</strong> ' . $data .'<br>';
              $message .= '</p>';
//               print_r($order_data_meta_proc[$type]);
//               print_r($order_data_meta_proc[$type]['data']);
//               print_r($order_data_meta);
            }
            break;
          case 'dcp_team_info':
          case 'tournaments':
            foreach( $data as $dex => $id ) $order_data_meta_proc[$type]['data'][] = g365_get_roster( $id, true );
    //         print_r($order_data_meta_proc);
            $column_order = array(
              'level' => 'Division',
              'org_name' => 'Team Name',
              'pool' => 'Pool Name',
              'pool_number' => 'Pool Number',
              'team_restrictions' => 'Team Restrictions',
              'time_restrictions' => 'Time Restrictions',
              'contact' => 'Contact',
              'email' => 'Email'
            );

            global $order, $post;
            if( ! is_a($order, 'WC_Order') ) {
                $order_id = $post->ID;
            } else {
                $order_id = $order->id;
            }
            //get level key
            $level_labels = g365_return_keys('g365_grade_key');
            // Get the user ID
            $user_data = get_userdata(get_post_meta($order_id, '_customer_user', true));

            $message .= '<table class="widefat"><thead><tr>';
            foreach( $column_order as $val ) $message .= '<th>' . $val . '</th>';
            $message .= '</tr></thead><tbody>';
            foreach( $order_data_meta_proc[$type]['data'] as $data_id => $data_info ) {
              $message .= '<tr>';
              foreach( $column_order as $key => $title ) {
    // Array ( [rosters_event] => Array ( [ids] => Array ( [0] => 311 ) [data] => Array ( [0] => stdClass Object ( [id] => 311 [updatetime] => 2020-11-03 00:49:38 [enabled] => 1 [org_id] => 4181 [team_id] => 397 [event_id] => 241 [level] => 14 [division] => Silver [name] => [team_type] => [coach_id] => [asst_id] => [player_names] => [description] => [event_names] => [org_name] => Club Rush Basketball [org_abbr] => Rush RB [team_name] => [team_level] => 14 [event_name] => Grassroots 365 Halloween Classic 2020 [event_short] => Halloween Classic [event_division] => {"Gold": "0", "Open": "0", "Bronze": "0", "Copper": "0", "Silver": "0"} [team_restrictions] => [date_restrictions] => [pool_name] => [pool_number] => [coach_name] => [asst_name] => ) ) ) )
                switch($key) {
                  case 'level':
                    $val = ((empty($data_info->division)) ? $level_labels[$data_info->level] : $level_labels[$data_info->level] . ' ' . $data_info->division);
                    break;
                  case 'email':
                    $val = $user_data->user_email;
                    break;
                  case 'contact':
                    $val = $user_data->first_name . ' ' . $user_data->last_name;
    //                 $val = $user_data->display_name;
                    break;
                  case 'org_name':
                    $val = $data_info->org_name . ' ' . $level_labels[$data_info->team_level] . ((!empty($data_info->team_name)) ? ' ' . $data_info->team_name : '');
                    break;
                  case 'age':
                    $val = ((!empty($data_info->birthday)) ? date_diff(date_create($data_info->birthday), date_create(date("Y-m-d")))->format('%y') : '--');
                    break;
                  case 'grade':
                    $val = ((!empty($data_info->grad_year)) ? g365_class_to_grade($data_info->grad_year) : '--');
                    break;
                  default:
                    $val = ((!empty($data_info->{$key})) ? $data_info->{$key} : '--');
                }
                $message .= '<th>' . $val . '</th>';
              }
              $message .= '</tr>';
            }
            $message .= '</tbody></table>';
            break;
          default:
            $message .= '<p>' . $type . ' :: ' . implode(',', $data) . '</p>';
            break;
        }
      } else {
        $message .= '<p>No additional ' . $type . ' order ids.</p>';
        continue;
      }
    }
  }
  echo '</div><div class="clear"></div><div>';
  echo '<div>';
  echo '<h3>Additional Order Information</h3>';
  echo $message;
  echo '</div>';
}


//erase the following two functions after hubspot is installed
//to make it easier to copy and paste to a mailing list
function g365_shop_order_columns( $columns ) {
  foreach ( $columns as $column_name => $column_info ) {
    if ( 'order_total' === $column_name && is_search() ) {
      if(  strpos( get_search_query(), 'Ticket' ) === false && strpos( get_search_query(), 'Admission' ) === false ) {
        $columns['order_full_name'] = __( 'Name', 'g365' );
        $columns['order_email'] = __( 'Email', 'g365' );
        $columns['order_phone_number'] = __( 'Phone', 'g365' );
      } else {
        $columns['order_ticket_meta'] = __( 'Tickets', 'g365' );
      }
    }
  }
  return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'g365_shop_order_columns', 20);
function g365_shop_order_columns_content( $column ) {
  global $post;
  if ( 'order_full_name' === $column ) {
    $order = wc_get_order( $post->ID );
    echo '<p>' . $order->get_formatted_billing_full_name() . '</p>';
  }
  if ( 'order_email' === $column ) {
    $order = wc_get_order( $post->ID );
    echo '<p>' . $order->get_billing_email() . '</p>';
  }
  if ( 'order_phone_number' === $column ) {
    $order = wc_get_order( $post->ID );
    echo '<p>' . $order->get_billing_phone() . '</p>';
  }
  if ( 'order_ticket_meta' === $column ) {
    $order = wc_get_order( $post->ID );
    //get existing data if there is any
    $existing = $order->get_meta( '_gate_control' );
    if( empty($existing) ) $existing = array();
    foreach ( $order->get_items() as $item_id => $item ) {
      $normalized_id = ($item->get_variation_id() === 0) ? $item->get_product_id() : $item->get_variation_id();
      if( $existing[$normalized_id] ) continue;
      $existing[$normalized_id] = array(
        'name'      => $item->get_name(),
        'quantity'  => $item->get_quantity(),
        'quantity_redeemed'  => 0
      );
    }
    $print_list = array();
    foreach ( $existing as $item_id => $item_data ) {
      if( $item_id === 'ut' ) {
        $print_list[] = 'Last Update Time: ' . $item_data;
      } else {
        $print_list[] = substr($item_data['name'], strpos($item_data['name'], ' - ') + 2) . ' | Available: ' . ($item_data['quantity'] - $item_data['quantity_redeemed']) . '/' . $item_data['quantity'];
      }
    }
    echo '<p>' . implode('<br>', $print_list) . '</p>';
  }
}
add_action( 'manage_shop_order_posts_custom_column', 'g365_shop_order_columns_content' );

// //modify the search fields when doing an order search
// function g365_mod_search_columns( $search_fields ) {
//   $new_search_fields = array( '_billing_address_index', '_billing_last_name', '_billing_email', '_sku' );
//   return $new_search_fields;
// }
// add_filter( 'woocommerce_shop_order_search_fields', 'g365_mod_search_columns');


//add the stock to the variation dropdown menu, by overiding the builtin
// function wc_dropdown_variation_attribute_options( $args = array() ) { 
//   //defaults
//   $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
//     'options' => false,  
//     'attribute' => false,  
//     'product' => false,  
//     'selected' => false,  
//     'name' => '',  
//     'id' => '',  
//     'class' => '',  
//     'show_option_none' => __( 'Choose an option', 'woocommerce' ),  
//   ) );
//   //internal vars
//   $options = $args['options']; 
//   $product = $args['product']; 
//   $attribute = $args['attribute']; 
//   $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
//   $id = $args['id'] ? $args['id'] : sanitize_title( $attribute );
//   $class = $args['class'];
//   $stock = array();
// //   $show_option_none = $args['show_option_none'] ? true : false; 
// //   $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

//   //get attributes if we don't have them
//   if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
//     $attributes = $product->get_variation_attributes();
//     $options = $attributes[ $attribute ];
//   }
//   //get stock for each variation
//   foreach ($product->get_available_variations() as $key) {
//     $attr_string = array();
//     foreach ( $key['attributes'] as $attr_name => $attr_value) $attr_string[] = $attr_value;
//     $stock[ implode(', ', $attr_string) ] = $key['max_qty'];
//   }
//   //start the select menu
//   $html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';
// //   $html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
// //   $html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

//   if ( ! empty( $options ) ) {
//     if ( $product && taxonomy_exists( $attribute ) ) {
//       // Get terms if this is a taxonomy - ordered. We need the names too.
//       $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
//       foreach ( $terms as $term ) if ( in_array( $term->slug, $options ) ) $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
//     } else {
//       foreach ( $options as $option ) {
//         //put the name together
//         $attr_name = esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) );
//         // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
//         $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
//         //option line for each attribute with stock
//         $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . $attr_name . (empty($stock[$attr_name]) ? " - SOLD OUT!" : " - $stock[$attr_name] spaces left.") . '</option>';
//       }
//     }
//   }
//   //close dropdown
//   $html .= '</select>';
//   echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args );
// }


add_action( 'save_post', 'g365_update_order', 10, 1 );
function g365_update_order( $post_id ) {
  // We need to verify this with the proper authorization (security stuff).
  // Check if our nonce is set.
  if ( ! isset( $_POST[ 'order_data' ] ) ) return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST[ 'post_type' ] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
  }
  // Update the meta field in the database.
  update_post_meta( $post_id, '_order_data', $_POST[ 'order_data' ] );
}


//A PICK UP FROM: https://www.cssigniter.com/how-to-add-a-custom-user-field-in-wordpress/
add_action( 'show_user_profile', 'g365_user_fields_display' );
add_action( 'edit_user_profile', 'g365_user_fields_display' );

function g365_user_fields_display( $user ) {
	wp_enqueue_script( 'admin-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '2.2.4', true );
	wp_enqueue_style( 'foundation-admin', site_url( '/wp-content/themes/g365-press', 'https' ) . "/css/style-admin.css", array(), filemtime(get_template_directory() . '/css/style-admin.css') );
  wp_enqueue_script( 'js-g365-all-admin', site_url( '/', 'https' ) . 'data-processor-admin.js', array('admin-jquery', 'foundation-admin'), filemtime(WP_PLUGIN_DIR . "/g365-data-manager/js/g365_ajax_cookie_ls_app_admin.js"), true );
  global $wpdb;
  $wpdb_orgs = $wpdb->g365_orgs;
  ?>
	<h3><?php esc_html_e( 'Additional User Data', 'g365' ); ?></h3>
  <div class="options_group g365_manage_wrapper">
    <table class="form-table">
      <tbody>
        <tr>
          <th>
            <label for="g365_default_org">Default Club or Organization</label>
          </th>
          <td>
            <p>
              <?php
              $org_id = get_the_author_meta( '_default_org', $user->ID );
              if( !empty($org_id) ) $org_name_object = $wpdb->get_results( "SELECT name, abbreviation FROM $wpdb_orgs WHERE id = $org_id" );
              ?>
              <input type="hidden" id="g365_user_org" name="g365_user_org" value="<?php echo ( empty($org_id) ) ? '' : $org_id; ?>" />
              <input type="text" class="g365_livesearch_input short no-margin-bottom" id="g365_default_org" value="<?php echo ( empty($org_name_object) ) ? '' : $org_name_object[0]->name; ?>" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="g365_user_org" data-g365_type="orgs" placeholder="Organization Name" autocomplete="off">
            </p>
          </td>
        </tr>
        <tr>
          <th>
            <label for="g365_ownership">Owned Data</label>
          </th>
          <td>
            <?php
            $owned_string = json_encode(get_user_meta( $user->ID, '_user_owns_g365', true ));
            ?>
            <input type="text" id="g365_ownership" name="user_owns_g365" value="<?php if( !empty($owned_string) ) echo htmlspecialchars($owned_string); ?>">
            <p class="description"><?php echo $owned_string; ?></p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
	<?php
}

//remove subscription cancel
function eg_remove_my_subscriptions_button( $actions, $subscription ) {

	foreach ( $actions as $action_key => $action ) {
		switch ( $action_key ) {
// 			case 'change_payment_method':	// Hide "Change Payment Method" button?
//			case 'change_address':		// Hide "Change Address" button?
//			case 'switch':			// Hide "Switch Subscription" button?
//			case 'resubscribe':		// Hide "Resubscribe" button from an expired or cancelled subscription?
//			case 'pay':			// Hide "Pay" button on subscriptions that are "on-hold" as they require payment?
//			case 'reactivate':		// Hide "Reactive" button on subscriptions that are "on-hold"?
			case 'cancel':			// Hide "Cancel" button on subscriptions that are "active" or "on-hold"?
				unset( $actions[ $action_key ] );
				break;
			default: 
				error_log( '-- $action = ' . print_r( $action, true ) );
				break;
		}
	}

	return $actions;
}
add_filter( 'wcs_view_subscription_actions', 'eg_remove_my_subscriptions_button', 100, 2 );


//FORM VALIDATION AND REQUIREMENT
add_action( 'user_profile_update_errors', 'g365_user_field_error', 10, 3 );
function g365_user_field_error( $errors, $update, $user ) {
	if ( ! $update ) {
		return;
	}
}

add_action( 'personal_options_update', 'g365_user_fields_update' );
add_action( 'edit_user_profile_update', 'g365_user_fields_update' );
function g365_user_fields_update( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
  if( isset($_POST['g365_user_org']) && (intval($_POST['g365_user_org']) || $_POST['g365_user_org'] === '') ) update_user_meta( $user_id, '_default_org', intval($_POST['g365_user_org']) );
  if( isset($_POST['user_owns_g365']) ) update_user_meta( $user_id, '_user_owns_g365', (( empty($_POST['user_owns_g365']) ) ? array() :  (array) json_decode(stripslashes($_POST['user_owns_g365'])) ) );
}

// cart checkout expiration 2hrs
add_filter( 'wc_session_expiration', 'woocommerce_cart_session_expires'); 
function woocommerce_cart_session_expires() {
  return 60 * 60 * 2; 
}

// Hook your custom function to the woocommerce_order_status_changed action
add_action('woocommerce_order_status_changed', 'handle_order_status_change', 10, 4);
function handle_order_status_change($order_id, $old_status, $new_status, $order) {
    // Check if the new status is "failed"
  
  
    if ($new_status === 'failed' || $new_status === 'refunded') {
        // Perform database updates, send notifications, etc.
        global $wpdb;
        $wpdb_orgs = $wpdb->g365_orgs;
        $wpdb_rosters = $wpdb->g365_rosters;
        $wpdb_events = $wpdb->g365_events;
        $wpdb_teams = $wpdb->g365_teams;
        $wpdb_players = $wpdb->g365_players;
        $wpdb_stats = $wpdb->g365_stats;
        $wpdb_stats = $wpdb->g365_stats;
        $order_data_meta = get_post_meta( $order->get_id(), '_order_data', true );
        $pssprt_id = explode("," , $order_data_meta);
        $pssprt_id = $pssprt_id[1];      
        // Retrieve the current stats from the database
        $current_stats = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT stats FROM $wpdb->g365_stats WHERE id = %d",
                $pssprt_id // Assuming $pssprt_id is the ID of the row
            )
        );

        // Decode the JSON string into a PHP associative array
        $stats_array = json_decode($current_stats, true);

        // Check if the stats array exists and contains the 'seasons' key
        if (isset($stats_array['seasons'])) {
            // Iterate over the 'seasons' array to find and remove the matching order_id
            foreach ($stats_array['seasons'] as $year => $season) {
                if (isset($season['order_id']) && $season['order_id'] == $order_id) {
                    unset($stats_array['seasons'][$year]); // Remove the matching year
                    break; // Stop iterating once the match is found
                }
            }

            // Encode the modified array back to JSON
            $updated_stats = json_encode($stats_array);

            // Update the stats in the database
            $result = $wpdb->update(
                $wpdb->g365_stats,
                array('stats' => $updated_stats),
                array('id' => $pssprt_id), // Assuming $pssprt_id is the ID of the row
                array('%s'),
                array('%d')
            );

            if ($result !== false) {
                error_log('Stats updated successfully for order ID: ' . $order_id);
            } else {
                error_log('Failed to update stats for order ID: ' . $order_id);
            }
        } else {
            error_log('No "seasons" array found in the stats.');
        }
       
        
          error_log('Order STATUSSSSS changed. New status: ' . $new_status . ' ' . $pssprt_id . ' ' . $order_id);
    }
}


//add put on hold option

// Enqueue the app.js script for AJAX
function enqueue_app_script() {
    // Check if we are on the WooCommerce "My Account" page or a subscription-related page
    if ( is_wc_endpoint_url('view-subscription') ) {
        // Enqueue your JavaScript file only on these pages
        wp_enqueue_script( 'app-script', get_template_directory_uri() . '/js/app.js', array( 'jquery' ), null, true );

        // Localize the script with the AJAX URL and nonce
        wp_localize_script( 'app-script', 'ajax_object', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),  // URL for AJAX requests
            'nonce'    => wp_create_nonce( 'put_on_hold_nonce' ) // Nonce for security
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_app_script' );

// Handle the "Put on Hold" AJAX request in PHP
add_action( 'wp_ajax_put_on_hold_subscription', 'handle_ajax_put_on_hold_subscription' );
add_action( 'wp_ajax_remove_hold_subscription', 'handle_ajax_remove_hold_subscription' );

function handle_ajax_put_on_hold_subscription() {
    check_ajax_referer( 'put_on_hold_nonce', 'security' );

    if ( isset( $_POST['subscription_id'] ) ) {
        $subscription_id = absint( $_POST['subscription_id'] );
        $subscription = wcs_get_subscription( $subscription_id );

        if ( $subscription ) {
            // Set the subscription to manual payments (Put on Hold)
            $subscription->set_requires_manual_renewal( true ); // Set auto-renewal
            $subscription->save();

            wp_send_json_success( array( 'message' => 'Subscription has been put on hold (manual renewal).' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Invalid subscription or subscription not found.' ) );
        }
    } else {
        wp_send_json_error( array( 'message' => 'Subscription ID is missing.' ) );
    }

    wp_die(); // Required to terminate immediately and return a proper response.
}

function handle_ajax_remove_hold_subscription() {
    check_ajax_referer( 'put_on_hold_nonce', 'security' );

    if ( isset( $_POST['subscription_id'] ) ) {
        $subscription_id = absint( $_POST['subscription_id'] );

        if ( ! $subscription_id ) {
            wp_send_json_error( array( 'message' => 'Invalid subscription ID.' ) );
            wp_die();
        }

        $subscription = wcs_get_subscription( $subscription_id );

        if ( ! $subscription ) {
            wp_send_json_error( array( 'message' => 'Subscription not found for ID: ' . $subscription_id ) );
            wp_die();
        }

        // If it reaches here, subscription exists and can proceed
        $subscription->set_requires_manual_renewal( false ); // Set auto-renewal
        $subscription->save();

        wp_send_json_success( array( 'message' => 'Subscription ' . $subscription_id . ' is now set to auto-renew.' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Subscription ID is missing.' ) );
    }

    wp_die();
}



add_filter( 'wcs_view_subscription_actions', 'add_custom_put_on_hold_action', 10, 2 );

function add_custom_put_on_hold_action( $actions, $subscription ) {
    // Get the subscription ID
    $subscription_id = $subscription->get_id();

    // Check if the subscription is set to auto-renew or manual renew
    $is_auto_renew = $subscription->is_manual() ? false : true;

    if ( $is_auto_renew ) {
        // If the subscription is set to auto-renew, label the button as "Put on Hold"
        $actions['put_on_hold'] = array(
            'url'  => '#', // Placeholder URL, we will handle it with JavaScript
            'name' => __( 'Put on Hold', 'woocommerce-subscriptions' ), // The button label
            'class' => 'button put_on_hold', // Add your custom class here
            'data-subscription-id' => $subscription_id, // Pass the subscription ID
        );
    } else {
        // If the subscription is set to manual renew, label the button as "Remove Hold"
        $actions['remove_hold'] = array(
            'url'  => '#', // Placeholder URL, we will handle it with JavaScript
            'name' => __( 'Remove Hold', 'woocommerce-subscriptions' ), // The button label
            'class' => 'button remove_hold', // Add your custom class here
            'data-subscription-id' => $subscription_id, // Pass the subscription ID
        );
    }

    return $actions; // Return all existing actions, including your custom one
}






//here stars the check to make sure the subscriptions tab in the parent account is correctly grabbing all appropriate subscriptions connected to their account
add_action('woocommerce_account_subscriptions_endpoint', 'custom_display_user_subscriptions');

function custom_display_user_subscriptions() {
    // Check if the current user has the role of 'player_editor'
    if (!current_user_can('player_editor')) {
        return;
    }

    $user_id = get_current_user_id();

    // First, attempt to retrieve subscriptions using WooCommerce Subscriptions function
    if (class_exists('WC_Subscriptions')) {
        $subscriptions = wcs_get_users_subscriptions($user_id);
    } else {
        $subscriptions = array(); // Fallback if WC_Subscriptions isn't active
    }

    // If no subscriptions found, run the backup function to get subscriptions directly
    if (empty($subscriptions)) {
        $subscriptions = get_manual_renewal_subscriptions($user_id);
        // Display the subscriptions in a table format
        if (!empty($subscriptions)) {
            echo '<div id="custom-subscriptions-container">';
            echo '<table style="width:100%; border-collapse: collapse;">';
            echo '<tr class="subs-subst-header" >';
            echo '<th class="subs-subst-border" >Subscription</th>';
            echo '<th class="subs-subst-border" >Status</th>';
            echo '<th class="subs-subst-border" >Next payment</th>';
            echo '<th class="subs-subst-border" >Total</th>';
            echo '<th></th>';
            echo '</tr>';

            foreach ($subscriptions as $subscription) {
                // Get subscription status, next payment date, and total
                $subscription_id = $subscription->ID;
                $status = $subscription->post_status;
                
                // Check if the subscription requires manual renewal
                $manual_renewal = get_post_meta($subscription_id, '_requires_manual_renewal', true) === 'true';

                // Convert status to a human-readable format
                $human_readable_status = wc_get_order_status_name($status);

                // Determine next payment or manual renewal label
                if ($manual_renewal) {
                    $next_payment = 'Manual Renewal';
                } elseif ($status === 'wc-active') {
                    $next_payment_raw = get_post_meta($subscription_id, '_schedule_next_payment', true);
                    $next_payment = $next_payment_raw ? date('F j, Y', strtotime($next_payment_raw)) : '-';
                } else {
                    $next_payment = '-';
                }
              
                $total = get_post_meta($subscription_id, '_order_total', true) ?: '$0';

                // Display each subscription row
                echo '<tr style=" background-color: #4e4e4e">';
                echo '<td class="subs-subst-info-border" ><a href="' . site_url() . '/account/view-subscription/' . $subscription_id . '">#' . $subscription_id . '</a></td>';
                echo '<td class="subs-subst-info-border" >' . $human_readable_status . '</td>';
                echo '<td class="subs-subst-info-border" >' . $next_payment . '</td>';
                echo '<td class="subs-subst-info-border" >' . wc_price($total) . '</td>';
                echo '<td class="subs-subst-info-border" ><a href="' . site_url() . '/account/view-subscription/' . $subscription_id . '">VIEW</a></td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</div>';
        } else {
            echo '<p>No subscriptions found.</p>';
        }
      
        // Add the JavaScript to hide the default WooCommerce "no subscriptions" message
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var subscriptionTable = document.getElementById("custom-subscriptions-container");
                var noSubscriptionsMessage = document.querySelector(".woocommerce_account_subscriptions .no_subscriptions");

                if (subscriptionTable && noSubscriptionsMessage) {
                    noSubscriptionsMessage.style.display = "none"; // Hide the "no subscriptions" message if the table is present
                }
            });
        </script>';
      
    }

}

function get_manual_renewal_subscriptions($user_id) {
    global $wpdb;

    // Query to retrieve all subscriptions for the user, ignoring manual renewal restrictions
    $results = $wpdb->get_results($wpdb->prepare(
        "
        SELECT * FROM {$wpdb->posts}
        WHERE post_type = 'shop_subscription'
        AND post_author = %d
        AND post_status IN ('wc-active', 'wc-on-hold', 'wc-pending-cancel')
        ",
        $user_id
    ));

    return $results;
}




?>