<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 * @since 2.6.0
 */
$user_id = get_current_user_id();
//if there is a user role to change then do it.
if ( isset($_POST['user_role']) ) {
  g365_set_role( $user_id );
  echo '<script>window.location = window.location.href;</script>';
  exit();
}
// contact us sticky note
echo do_shortcode("[sticky_contact_us_note]");

function g365_user_roles( $user = null ) {
  $user = $user ? new WP_User( $user ) : wp_get_current_user();
  return $user->roles;
}

//do we need to force a role change
if( in_array('customer', g365_user_roles($user_id)) ) { ?>

<div class="grid-x grid-margin-x">
  <div class="woocommerce-MyAccount-content cell small-12 medium-8 large-9 large-margin-bottom">
    <form action="" method="post" class="callout">
      <h2>Account Type Selection Required</h2>
      <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <input type="radio" name="user_role" value="college_coach_user" id="reg_role_director"><label for="reg_role_director">College Coach</label>
        <input type="radio" name="user_role" value="player_user" id="reg_role_player" required checked><label for="reg_role_player">Parent/Player</label>
        <input type="radio" name="user_role" value="coach_user" id="reg_role_coach"><label for="reg_role_coach">Coach</label>
        <input type="radio" name="user_role" value="director_user" id="reg_role_director"><label for="reg_role_director">Club Director</label>
      </p>
      <button class="button">Select</button>
    </form>
  </div>
</div>  

<?php } else {

  //if we have a stat keeper, get them to the right spot
  if( current_user_can('stat_keeper') || current_user_can('gate_controller') ) {
    ?>

    <div class="grid-x grid-margin-x">
      <div class="woocommerce-MyAccount-content cell small-12 large-margin-bottom">
        <?php do_action( 'woocommerce_account_content' ); ?>
      </div>
      <div class="text-right cell small-12 border-color-gray top-border thin-border xlarge-margin-top">
      <?php do_action( 'woocommerce_account_navigation' ); ?>
      </div>
    </div>

    <?php

  } else {
    ?>

    <div class="grid-x grid-margin-x">
      <?php do_action( 'woocommerce_account_navigation' ); ?>

      <div class="woocommerce-MyAccount-content cell small-12 medium-8 large-9 large-margin-bottom">
        <?php
          /**
           * My Account content.
           * @since 2.6.0
           */
          do_action( 'woocommerce_account_content' );
        ?>
      </div>

    </div>

    <?php
  }
}
