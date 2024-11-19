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
      
      //get rid of everything but the dashboard and logput
      $menu_links = $profile_links + array_slice( $menu_links, -1, NULL, true );
    } elseif ( current_user_can( 'data_editor' ) ) {
      //add the profiles after the dashboard and any other links we need
      if( current_user_can('player_editor') ) $profile_links[ 'player_editor' ] = 'Player Editor';
      if( current_user_can('coach') ) $profile_links[ 'coach' ] = 'Coach Editor';
      if( current_user_can('rosters') ) $profile_links[ 'rosters' ] = 'Rosters Editor';
      if( current_user_can('club') ) $profile_links[ 'club' ] = 'Club Editor';
//       if( true ) $profile_links[ 'ros_ev' ] = 'Rosters by Event';
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
    add_rewrite_endpoint( 'cps_mod', EP_PAGES );
    add_rewrite_endpoint( 'event_mod', EP_PAGES );
    add_rewrite_endpoint( 'scor_keep', EP_PAGES );
    add_rewrite_endpoint( 'stat_keep', EP_PAGES );
    add_rewrite_endpoint( 'player_editor', EP_PAGES );
    add_rewrite_endpoint( 'coach', EP_PAGES );
    add_rewrite_endpoint( 'club', EP_PAGES );
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
      $item_string = array_map( function($key, $ids){ return $key . ',' . implode(',', $ids); }, $user_g365_keys, $user_g365 );
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
                      $item_string = 'ro_ed,' . implode(',', array_keys($roster_ids)) . '|' . $item_string;
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
        <div id="g365_form_options_anchor" data-g365_type="pl_ed,<?php echo implode(',', $user_g365['pl_ed']); ?>"></div>
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
  //my account g365 edit data page
  add_action( 'woocommerce_account_stat_keep_endpoint', 'stat_keep_endpoint_content' );
  function stat_keep_endpoint_content() {
    ?>
    <div class="cell small-12 medium-8 large-6">
    <?php
     if( current_user_can('stat_keeper') ){
       echo "
         <style>
          #masthead, .nav-spacer {
            display: none;
          }
         </style>
       ";
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
          <div class="grid-container grid-x grid-margin-x callout secondary tiny-padding tiny-margin-bottom primary-border">
              <div class="cell small-12 large-auto">EVENT: <strong><?php echo $ev_data->name;?></strong></div>
              <div class="cell small-12 large-shrink">START TIME: <strong><?php echo date_format(date_create($ev_game->start_time), 'M d Y g:i A');?></strong></div>
          </div>
          <div class="grid-container grid-x grid-margin-x callout secondary tiny-padding tiny-margin-bottom primary-border">
              <div class="cell small-12 large-auto">LOCATION: <strong><?php echo $ev_game->location;?></strong></div>
              <div class="cell small-12 large-shrink">COURT: <strong><?php echo $ev_game->court;?></strong></div>
          </div>
          <div class="text-center grid-container grid-x grid-margin-x callout white tiny-padding tiny-margin-bottom primary-border">
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
          <div class="text-center grid-container grid-x grid-margin-x callout white tiny-padding tiny-margin-bottom primary-border" id="ribbon_clock_btn">
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
          <div id="g365_form_options_anchor" data-g365_type="gm_st,<?php echo $ev_game->id; ?>"></div>
          
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
            foreach( $ev_games as $game_data ) {
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
                          <th>Home</th>
                          <th>Away</th>
                        </tr>
                      </thead>
                      <tbody>
              <?php } ?>
                        <tr><td><a class="button no-margin-bottom" href="<?php echo site_url(); ?>/account/stat_keep?ev_game=<?php echo $game_data->id; ?>">record stats</a></td><td><?php echo date_format(date_create($game_data->start_time), 'M d Y g:i A'); ?></td><td><?php echo $game_data->court; ?></td><td><?php echo $game_data->home_team; print_r($game_data->home_team);?></td><td><?php echo $game_data->away_team; ?></td></tr>
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
          <p class="button-group expanded">
            <?php foreach( $ev_locations as $dex => $loc ) { ?>
                <a href="<?php echo site_url(); ?>/account/stat_keep?ev_id=<?php echo $ev_id; ?>&ev_loc=<?php echo $loc; ?>" class="button"><?php echo $loc; ?></a>
            <?php } ?>
          </p>
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
        <div id="g365_form_options_anchor" data-g365_type="co_ed,<?php echo implode(',', $user_g365['co_ed']); ?>"></div>
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
          <div id="g365_form_options_anchor" data-g365_type="club_names" data-g365_init_pre="club_names_preset,user_ac:<?php echo (((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . get_current_user_id()) ?>"></div>
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
        <div id="g365_form_options_anchor" data-g365_type="og_ed,<?php echo implode(',', $user_g365['og_ed']); ?>"></div>
        </div>
      </div>
    <?php
    }
    ?>
    </div>
    <?php
  }
  
  //my account g365 edit data page
  add_action( 'woocommerce_account_rosters_endpoint', 'rosters_endpoint_content' );
  function rosters_endpoint_content() { ?>
    <?php
    //see if we have a build id otherwise load the default rosters
    $org_id = filter_input( INPUT_GET, 'org_id', FILTER_SANITIZE_NUMBER_INT );
    $ev_id = filter_input( INPUT_GET, 'ev_id', FILTER_SANITIZE_NUMBER_INT );
    //see what user data we have
    $current_user = wp_get_current_user();
    $user_g365 = get_user_meta($current_user->ID, '_user_owns_g365', true);
    $default_org_id = get_user_meta($current_user->ID, '_default_org', true);
    if( empty($org_id) ) $org_id = $default_org_id;
    if( empty($org_id) ) (($user_g365 !== '' && $user_g365['og_ed'][0]) ? intval($user_g365['og_ed'][0]) : null);

    //globals for db
    global $wpdb;
    $wpdb_orgs = $wpdb->g365_orgs;
    $wpdb_rosters = $wpdb->g365_rosters;
    $wpdb_events = $wpdb->g365_events;
  	$wpdb_teams = $wpdb->g365_teams;
    //url for dropdown
    
    $dropdown_menu_org_url = site_url() . '/account/rosters/?org_id=';
    $dropdown_menu_url = site_url() . '/account/rosters/?org_id=' . $org_id . '&ev_id=';
    
    //event_name
    $event_nickname = ( empty($ev_id) ) ? '0' : $wpdb->get_var( "SELECT nickname FROM $wpdb_events WHERE id = $ev_id" );
    //write the dropdown opener
    ?>
    <div class="cell small-12 medium-8 large-6">
      <?php if( $org_id !== null ) { ?>
        <h2 class="large-margin-bottom grid-x grid-margin-x">
          <div class="cell small-12 large-6">Manage Rosters</div>
          <?php
          //if we have data to pull rosters, build list of event rosters
          if( empty($ev_id) && is_array($user_g365) && count($user_g365['og_ed']) > 1 ) { ?>
          <div class="cell small-12 large-6 tiny-padding-top tiny-margin-bottom text-right">
            <a class="field-toggle button small no-margin-bottom" data-g365_class_toggle="hide">switch club</a>
            <div class="input-group hide">
              <span class="input-group-label normal-font-size">Your Club Teams</span>
              <select id="series_selector" class="no-margin-bottom input-group-field">
              <?php
              $user_orgs = $wpdb->get_results(
                "SELECT DISTINCT org.id, org.name
                FROM $wpdb_orgs AS org
                WHERE org.id IN ( " . implode(',', $user_g365['og_ed']) . " );"
              );
              if( !empty($user_orgs) ) foreach( $user_orgs as $dex => $vals ) echo '<option value="' . $dropdown_menu_org_url . $vals->id . '"' . (($org_id == $vals->id) ? ' selected="selected"' : '') . '>' . $vals->name . '</option>';
              ?>
              </select>
            </div>
          </div>
          <?php } ?>
        </h2>
      <?php if( empty($ev_id) && $ev_id !== '0' ) { ?>
        <div class="cell small-12 large-6 tiny-padding-top tiny-margin-bottom">
          <?php
          //if we have data to pull rosters, build list of event rosters
          if( is_array($user_g365) && !empty($user_g365['og_ed'][0]) ) {
            $all_rosters = $wpdb->get_results(
              "SELECT ev.id AS ev_id, ev.name AS ev_name, roster.enabled AS ros_enabled, tm.name AS team_name, tm.level AS team_level, roster.level AS ros_level
              FROM $wpdb_rosters AS roster
              LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
              LEFT JOIN $wpdb_teams AS tm ON roster.team=tm.id
              WHERE roster.org = $org_id ORDER BY roster.level, ev.eventtime, ev.id=0;"
            );
            if( !empty($all_rosters) ) { ?>
              <h3 class="medium-margin-bottom">Choose Roster Set</h3>
              <div class="event-roster-wrap">
              <?php
              $events_with_roster = array();
              foreach( $all_rosters as $dex => $roster_data ) $events_with_roster[ $roster_data->ev_id ] = $roster_data->ev_name;
//               echo '<pre>';
//               print_r($all_rosters);
//               echo '</pre>';
              foreach( $events_with_roster as $ev_id => $ev_name ) {
                echo '<a class="button no-margin-bottom" href="' . $dropdown_menu_url . $ev_id . '">' . (($ev_name == 'Club Team') ? 'Public Default Club Team Rosters' : $ev_name) . '</a><div><small>Currently Assigned Rosters</small><br><div>';
                foreach( $all_rosters as $dex => $roster_data ) {
                  if( intval($roster_data->ev_id) !== $ev_id ) continue;
                  echo '<span class="roster-label small tiny-padding-sides' . (( $roster_data->ros_enabled === '0' ) ? ' disabled' : '') . '">' . g365_level_key($roster_data->team_level) . ' ' . $roster_data->team_name . (($roster_data->team_level != $roster_data->ros_level) ? ' <span class="level-tag">playing<br>at ' . g365_level_key($roster_data->ros_level) . '</span>' : '') . '</span>';
                }
                echo '</div></div><hr>';
              }
            } else { ?>
            <h3 class="medium-margin-bottom">Looks like you don't have any rosters yet...</h3>
            <div class="event-roster-wrap">
              <a href="<?php echo site_url(); ?>/register/club-teams/0/" class="button no-margin-bottom small-margin-top medium">Add New Rosters</a>
            </div>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
        <?php } else { ?>
        <a  class="button no-margin-bottom float-right medium small-small g365-edit-data" id="<?php echo (( empty($ev_id) ) ? 'rosters_club_sl_' : 'rosters_sl_') . $ev_id; ?>" data-g365_type="<?php echo (( empty($ev_id) ) ? 'rosters_club_sl' : 'rosters_sl'); ?>">Add New Rosters</a>
        <script type="text/javascript">
          var g365_form_details = {
            "items" : {
              "Rosters":{
                "name":"",
                "title":"<?php echo ( empty($ev_id) ) ? 'Default' : $wpdb->get_var( "SELECT name FROM $wpdb_events WHERE id = $ev_id" ); ?>",
                "type":"<?php echo ( empty($ev_id) ) ? 'ro_ed' : 'to_ed'; ?>",
                "items":{}
              }
            },
            "wrapper_target" : "g365_form_options_anchor",
            "user_org": "<?php echo get_user_meta($current_user->ID, '_default_org', true); ?>",
            "admin_key": "<?php echo g365_make_admin_key(); ?>"
          };
        </script>
        <div>
          <?php
          $roster_ids = $wpdb->get_results( "SELECT id FROM $wpdb_rosters WHERE org = $org_id AND event = $ev_id;", OBJECT_K );
          if( !empty($roster_ids) ) $item_string = ((( empty($ev_id) ) ? 'ro_ed,' : 'to_ed,') . implode(',', array_keys($roster_ids)));
          ?>
          <div id="g365_form_options_anchor" data-g365_type="<?php echo ($item_string ?? ''); ?>"></div>
        </div>
        <br/>
        <h3>Add Roster to Event</h3>
        <hr class="small-margin-top">
        <div id="g365_bulk_add_wrap" class="input-group expanded">
          <div class="input-group-field">
            <input type="hidden" id="g365_bulk_add_event_id" data-g365_bulk_add_default="<?php echo $event_nickname ?>">
            <input type="text" id="event_names" class="g365_livesearch_input expanded button-height no-margin-bottom" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="g365_bulk_add_event_id" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off">
          </div>
          <a href="<?php echo site_url(); ?>/register/tournaments/" class="input-group-button button tiny-margin-left g365_bulk_add" data-g365_bulk_add_control="g365_bulk_add_event_id" data-g365_bulk_add_target="g365_roster_form_data">Add Selected Rosters</a>
        </div>
        <div class="reveal tiny" id="g365_form_reveal" aria-labelledby="Form Holder" data-reveal>
          <div class="relative">
            <button class="close-button" data-close aria-label="Close Form Reveal" type="button"><span aria-hidden="true">&times;</span></button>
          </div>
        </div>
      </div>
      <?php
      }
    } else {
    ?>
      <div class="cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"Choose or create club team","type":"club_names","items": {}}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "<?php echo g365_make_admin_key(); ?>"};</script>
        <p>&nbsp;</p>
        <div>
          <div id="g365_form_options_anchor" data-g365_type="club_names" data-g365_init_pre="club_names_preset,user_ac:<?php echo (((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . get_current_user_id()) ?>"></div>
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
              echo '<td class="form-floater"><a class="button g365-edit-data no-margin-bottom" id="pl_ev_' . $stat_data->id . '" data-g365_type="cps_manager,' . $stat_data->id . '">Edit</a></td>';
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
      'director_user' => 'club'
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
      $extra_types = array( 'fee', 'gear', 'event', 'ticket' );
      //product_cat and product_id is needed to organize the cart item data,or if the cat type is invalid
      if( empty($product_cat_slug) || empty($product_cat_id) || empty($product_id) || empty($form_type_opt[$product_cat_slug]) ) {
        if( in_array($product_cat_slug, $extra_types) ) {
          switch( $product_cat_slug ) {
            case 'fee':
              for ($i = 1; $i <= $cart_item['quantity']; ++$i) {
                $extra_fields[] = array(
                  'type'  => $product_cat_slug,
                  'title' => $product_name,
                  'type'  => 'text',
                  'placeholder'=> 'Players Full Name',
                  'label' => 'Player Name ' . $i,
                  'name'  => 'player_name_' . $i,
                  'required'=> true,
                  'class' => array($product_cat_slug)
                );
                $extra_fields[] = array(
                  'type'  => $product_cat_slug,
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
                  'type'  => $product_cat_slug,
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
      'class'   => array('input', 'medium-margin-bottom'),
      'options' => array(
        ''          => 'Please select',
        'friend'    => 'A friend',
        'google'    => 'Google',
        'o_website' => 'Our Website',
        'grpon'     => 'Groupon',
        'lnkdin'    => 'LinkedIn',
        'instag'    => 'Instagram',
        'faceb'     => 'Facebook',
        'twitt'     => 'Twitter',
        'indeed'    => 'Indeed',
        'walk_in'   => 'Walk-In'
      )
    );
    $g365_cart_types = array_unique( $g365_cart_types );
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
    //add the field with the parameters from the cart process
    add_filter( 'woocommerce_after_order_notes' , function( $fields ) use ( $order_data_field ) { return g365_player_checkout_field( $field, $order_data_field ); });
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
      case 'camps':
      case 'league':
      case 'club_team':
      case 'rosters_event':
        $current_user = wp_get_current_user();
        $presets[] = $type . '_preset,user_ac:' . ((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . $current_user->ID;
        break;
    }
  }
  if($presets) $presets = 'data-g365_init_pre="' . implode('|', $presets) . '"';
  //return string
  $field = '<div class="grid-x grid-margin-x small-margin-bottom" id="event_details"><div class="cell small-12 medium-8 large-6"><header class="entry-header"><h2 class="entry-title">' . __('Registration') . '</h2></header><div id="g365_registration_fields">';
  $field .= '<script type="text/javascript">var g365_form_details = {"items" : ' . json_encode($args['g365_cart_items']) . ', "wrapper_target" : "g365_form_options_anchor", "user_org": "' . get_user_meta($current_user->ID, '_default_org', true) . '", "admin_key": "' . $admin_key . '"};</script><div><div id="g365_form_options_anchor" data-g365_type="' . implode('|', $args['g365_cart_types']) . '"' . $presets . '></div></div>';
  $field .= '</div></div></div>';
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
function g365_player_checkout_field( $checkout, $extra_fields = null ) {
  if( $extra_fields === null ) return;
  echo '<h3>Player Info</h3>';
  //create a custom field that can be referenced to get the names of all the custom fields
  $added_field_names = array();
  //make a field for each product that needs it
  foreach( $extra_fields as $dex => $field_arr ) {
    echo '<div class="item">';
    //create a name unique to the order for each field
    $field_name = $field_arr['type'] . '_' . $field_arr['name'] . '_' . $dex;
    $added_field_names[] = $field_name;
    //use woocommerce to add the fields
    $field_arr_process = array(
      'type'  => $field_arr['type'],
      'class' => $field_arr['class'],
      'label' => $field_arr['label'] . ((!empty($field_arr['title'])) ? ' for ' . $field_arr['title'] : ''),
      'required' => $field_arr['required'],
      'placeholder' => $field_arr['placeholder'],
    );
    if( ($field_arr['type'] === 'select' || $field_arr['type'] === 'radio' || $field_arr['type'] === 'multiselect') && !empty($field_arr['options']) ) $field_arr_process['options'] = $field_arr['options'];
    woocommerce_form_field( $field_name, $field_arr_process, '');
    echo '</div>';
  }
  //don't forget to add the field names reference otherwise we can't process this data at all.
  echo '<input type="hidden" name="g365_extra_data" value="' . implode(',',$added_field_names) . '" />';
}

//make the extra data required based on the field names variable
add_action('woocommerce_checkout_process', 'g365_player_checkout_field_process');
function g365_player_checkout_field_process() {
  // Check if we have extra data
  if ( $_POST['g365_extra_data'] ) {
    //parse and loop through the set to make sure we have all the fields filled out
    $fields = explode(',', $_POST['g365_extra_data']);
    foreach( $fields as $dex => $key ) {
      if( ! $_POST[ $key ] ) wc_add_notice( __( 'This field  is required.' ), 'error' );
    }
  }
}

//if we have extra data, save it
add_action( 'woocommerce_checkout_update_order_meta', 'g365_player_checkout_field_update_order_meta' );
function g365_player_checkout_field_update_order_meta( $order_id ) {
  // Check if we have extra data
  if ( $_POST['g365_extra_data'] ) {
    //save the field reference so we know what to pull later
    update_post_meta( $order_id, 'g365_extra_data', sanitize_text_field( $_POST['g365_extra_data'] ) );
    //parse extra field names, then loop and save
    $fields = explode(',', $_POST['g365_extra_data']);
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
      echo '<p><strong>' . ucwords( implode(' ', $key_parts) ) . ':</strong> ' . get_post_meta( $order->id, $key, true ) . '</p>';
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
  if( isset($_POST['order_data']) && $_POST['order_data'] === 'null' && wc_notice_count( 'error' ) == 0 )  wc_add_notice( 'G365 Data needs to be processed or completed.', 'error');
}

//update cart data and user info
add_action( 'woocommerce_checkout_update_order_meta', 'g365_update_meta_form_fields' );
function g365_update_meta_form_fields( $order_id ) {
  if( !empty( $_POST['order_data'] ) ) update_post_meta( $order_id, '_order_data', $_POST['order_data'] );
}

//display and edit the custom data
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'g365_checkout_field_display_admin_order_meta', 10, 1 );
//display and custom order data for admin
function g365_checkout_field_display_admin_order_meta($order){
  $order_data_meta = get_post_meta( $order->get_id(), '_order_data', true );
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
                $val = $data_info->org_name . ' ' . $data_info->team_level . 'U' . ((!empty($data_info->team_name)) ? ' ' . $data_info->team_name : '');
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
			case 'change_payment_method':	// Hide "Change Payment Method" button?
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

?>