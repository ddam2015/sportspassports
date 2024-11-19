<?php
/**
 * Template Name: Data Input Variable
 */

defined( 'ABSPATH' ) || exit;


//load variables
get_header();
$g365_ad_info = g365_start_ads( $post->ID );
?>
  <section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
    <!--   contact us sticky note -->
    <?php echo do_shortcode("[sticky_contact_us_note]"); ?>
<?php
		if ( $g365_ad_info['go'] ) echo '<div class="cell small-12">' . $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'] . '</div>';
// Check if the user is logged in before showing content
if ( !is_user_logged_in() ) { ?>

    <div class="cell small-12">
    
  <?php
  wp_localize_script( 'wc-password-strength-meter', 'pwsL10n', array(
    'unknown' => __('Password strength unknown'),
    'empty' => __( 'Strength indicator' ),
    'short' => __( 'Very weak' ),
    'bad' => __( 'Weak' ),
    'good' => __( 'Medium', 'password strength' ),
    'strong' => __( 'Strong' ),
    'mismatch' => __( 'Mismatch' )
  ) );
  wp_enqueue_script( 'password-strength-meter' );
  wp_enqueue_script( 'wc-password-strength-meter', site_url() . '/wp-content/plugins/woocommerce/assets/js/frontend/password-strength-meter.min.js', array('jquery', 'password-strength-meter'), '4', true );
  wp_localize_script( 'wc-password-strength-meter', 'wc_password_strength_meter_params', array(
    'min_password_strength' => '3',
    'stop_checkout' => '',
    'i18n_password_error' => __('Please enter a stronger password.'),
    'i18n_password_hint' => __('Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! \" ? $ % ^ & ).')
  ) );
  
//   echo '<h1 class="xlarge-margin-bottom">Please Login to Continue to:<br><small class="loudest">' . get_the_title() . '</small></h1>';
  echo '<div class="cell">';
  echo do_shortcode( '[woocommerce_my_account]' );
  echo '</div>';
  ?>
  
      <h5 class="cell callout medium-padding">You must be logged in to modify data.</h5>
    </div>

  <?php
} else {
  //you are logged in, congrates
  //load variables
  global $wp_query;
  //available vars from url
  // $rg_tp = $wp_query->query_vars['rg_tp'];
  // $rg_ps = $wp_query->query_vars['rg_ps'];

  //get keys to test type
  $form_type_data = g365_return_keys( 'g365_url_form_key' );
  //print_r($form_type_data);
  
  //url keys for form type
  $form_type_opt = $form_type_data[0];
  //echo("key1 " . $form_type_opt . "<br>");
  //print_r($form_type_opt);
  
  //key for form targets
  $form_type_target = $form_type_data[1];
  //if we have a form, process it or default page
  if( !empty($form_type_opt[$wp_query->query_vars['rg_tp']]) ) {
    $url_type_key = $wp_query->query_vars['rg_tp'];
    //echo("key2 " . $url_type_key . "<br>");
    
    //allow only the correct forms based on user type
    if( current_user_can('administrator') ||
      ((current_user_can('cps_moderator') || current_user_can('player_editor')) && ($url_type_key == 'camps' || $url_type_key == 'player-certification' || $url_type_key == 'training' || $url_type_key == 'leagues' || $url_type_key == 'college-placement' || $url_type_key == 'dcp_player_registration')) ||
      (current_user_can('coach') && ($url_type_key == 'coaches')) ||
      (current_user_can('rosters') && ($url_type_key == 'tournaments' || $url_type_key == 'club-teams' || $url_type_key == 'rosters' || $url_type_key == 'coaches')) ||
      (current_user_can('club') && ($url_type_key == 'clubs'))
    ) {
//       echo("key " . $form_type_opt[$url_type_key] . "<br>");
      $rg_tp = $form_type_opt[$url_type_key];
//       echo("YO " . $rg_tp);
      $rg_tp_full = $rg_tp;
      $rg_ps = '';
      $event_details = '{}';
      switch($url_type_key) {
        case 'tournaments':
          $title = 'Add Tournament Roster';
          break;
        case 'camps':
          $title = 'Add Player to Camp';
          break;
        case 'dcp_player_registration':
          $title = 'Register Player to The Stage';
          break;
        case 'player-certification':
          $title = 'Player Registration';
//           $wp_query->query_vars['rg_ps'] = '0';
          break;
        case 'club-teams':
          $title = 'Add Club Team to Organization';
          $wp_query->query_vars['rg_ps'] = '0';
          break;
        case 'training':
          $title = 'Add Player for Training';
          break;
        case 'leagues':
          $title = 'Add Roster to League';
          break;
        case 'college-placement':
          $title = 'Add new player to College Placement Service';
          break;
        case 'coaches':
          $title = 'Add Coach';
          break;
        case 'rosters':
          $title = 'Add Roster';
          break;
        case 'clubs':
          $title = 'Add Club';
          break;
      }
      $ids = filter_input( INPUT_GET, 'ro_ids', FILTER_SANITIZE_STRING );
//       echo("yoyyoyoyoyoyoyoyyoyo " . $title);

      if( !empty($ids) ) $rg_tp_full .= ':::' . $ids;
      //set global preset
      $rg_presets = ' data-g365_init_pre="' . $rg_tp . '_preset:::user_ac::' . ((strpos(site_url(), 'dev') === false) ? 'SPP' : 'SPD') . '-' . get_current_user_id();
//       $rg_presets = ' data-g365_init_pre="' . $rg_tp . '_preset:::user_ac::' . ((strpos(site_url(), 'dev') === false) ? 'G3P' : 'G3D') . '-' . get_current_user_id();
      
      //if the preset deafult is set, use it, otherwise
      if( (!empty($wp_query->query_vars['rg_ps']) && empty($wp_query->query_vars[$url_type_key . '_' . $form_type_target[$url_type_key]])) || $wp_query->query_vars['rg_ps'] === '0'){
        $rg_ps = $wp_query->query_vars['rg_ps'];
        //convert to a number if given a url resource
        if( !is_numeric($rg_ps) ) {
          //make numeric
          $rg_ps = $wpdb->get_row( $wpdb->prepare( "SELECT id, name from $wpdb->g365_events where nickname LIKE %s", $rg_ps ) );
          if( empty($rg_ps) ) {
            $rg_ps = '';
          } else {
            $title = $rg_ps->name;
            $rg_ps = $rg_ps->id;
          }
        }

        if( $rg_ps !== '' ) {
          switch( $form_type_target[$url_type_key] ) {
            case 'event_id':
            case 'event_id_pm':
            case 'event_id_ct':
            case 'event_id_cps':
            case 'event_id_cp':
              if( $rg_ps === '0' ) {
                $event_details = '{"ev":{"id": "ev", "name": "Default","vars": [{"name": "Default","full_name": "Default","event_divisions": "0","event_id": "0"}]}}';
                $ev_data = (object) array( 'name' => 'Roster for Club Team Page' );
//                 echo("IDDDDDD   " . $ids);
              } else {
                $ev_data = $wpdb->get_row( $wpdb->prepare( "SELECT name, short_name, divisions from $wpdb->g365_events where id = %d", $rg_ps ) );
                $event_details = '{"ev":{"id": "ev", "name": "' . $ev_data->short_name . '","vars": [{"name": "' . $ev_data->name . '","full_name": "' . $ev_data->short_name . '","event_divisions": ' . (empty($ev_data->divisions) ? "0" : $ev_data->divisions) . ',"event_id": "' . $rg_ps . '"}]}}';
              }
              if( !empty($ev_data->name)) $title = $ev_data->name;
              break;
            case 'coach_id':
              $event_details = '{"co":{"id": "co", "name": "Add Coach","vars": [{"name": "Add Coach","full_name": "Add Coach"}]}}';
              break;
            case 'club_id':
              $event_details = '{"cb":{"id": "cb", "name": "Add Club","vars": [{"name": "Add Club","full_name": "Add Club"}]}}';
              break;
          }
        }
        $rg_presets .= ':::' . $form_type_target[$url_type_key] . '::' . $rg_ps;
      }
      $rg_presets .= '"';
      ?>
      <div class="cell small-12 medium-8 large-6"><script type="text/javascript">var g365_form_details = {"items" : {"Registration Forms":{"name":"Please fillout entire form.","title":"<?php echo $title; ?>","type":"<?php echo $rg_tp; ?>","items": <?php echo $event_details; ?>}}, "wrapper_target" : "g365_form_options_anchor", "admin_key" : "<?php echo g365_make_admin_key(); ?>"};</script>
        <p>&nbsp;</p>
        <div>
          <div id="g365_form_options_anchor" data-g365_type="<?php echo $rg_tp_full; ?>"<?php echo $rg_presets; ?>></div>
        
        </div>
      </div>
      <?php
    } else {
      ?>
      <div class="cell small-12 medium-8 large-6">
        <h2>Alert!</h2>
        <p>Your account is not allowed to modify this data type. Please contact the proper user or one of <a class="emphasis" href="/about/#contact">our reps here.</a></p>
      </div>
      <?php
    }
  } else { 
    if( !empty($_GET['ref_id']) && !empty(intval($_GET['ref_id'])) && !empty($_GET['ref_em']) ){
      if( current_user_can( 'administrator' ) ) {
        $g365_auth_result = 'Please use the admin back-end to authorize claims.';
      } else {
        //see if we can authorize the claim
//         if( strpos( site_url(), 'dev') !== false ){ $site_type = 'G3D'; }else{ $site_type = 'G3P'; }
        if( strpos( site_url(), 'dev') !== false ){ $site_type = 'SPD'; }else{ $site_type = 'SPP'; }
        $g365_auth_result = g365_authorize_claim_local( $_GET['ref_id'], $_GET['ref_em'], '', $site_type );
      }
      ?>
      <article id="post-<?php the_ID(); ?>">
        <header class="entry-header">
          <h1 class="entry-title">Grant Access</h1>
        </header><!-- .entry-header -->

        <div class="entry-content">
          <?php 
//       echo '<pre>';
//       echo gettype($g365_auth_result);
//       print_r( $g365_auth_result );
//       echo '</pre>';
          ?>
          <p>
            <?php echo ((is_object($g365_auth_result)) ? $g365_auth_result->message : $g365_auth_result) ?>
          </p>
        </div><!-- .entry-content -->

      </article><!-- #post-## -->
      <?php
    } else {
        echo("STARTING!!");
      // show some links and messaging if we aren't doing a form
      if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'page-parts/content', get_post_type() );

//       echo ("YOOOO   " . $GLOBALS['wp_query']->request);
      
      
        endwhile;
      else :

          get_template_part( 'page-parts/content', 'none' );

      endif;

      $child_pg_args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $post->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order'
      );
      
      $parent_pg = new WP_Query( $child_pg_args );
      
      if ( $parent_pg->have_posts() ) : ?>

        <div class="cell small-12">

        <?php while ( $parent_pg->have_posts() ) : $parent_pg->the_post(); ?>
        
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> | 

      <?php endwhile; ?>

        </div>

      <?php endif; wp_reset_postdata();
    }
  }
}?>
</section>

<?php get_footer();

if ( !is_user_logged_in() ) { ?>
  <script type="text/javascript">
    (function($) {
      $('#reg_password2').on('focusout', function(){
        var pass2 = $(this);
        if( pass2.prev().attr('id') === 'reg_password2_warning' ) pass2.prev().remove();
        if( pass2.val() !== $('#reg_password').val() ) $( '<div id="reg_password2_warning" class="woocommerce-password-strength bad">Passwords don\'t match.</div>' ).insertBefore(pass2)
      });
    })(jQuery);
  </script>
<?php } ?>