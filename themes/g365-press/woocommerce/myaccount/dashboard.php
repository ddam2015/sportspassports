<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

function user_capabilities( $user = null ) {
  $user = $user ? new WP_User( $user ) : wp_get_current_user();
  return $user;
  return array_keys( $user->allcaps );
}

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//if we have a stat keeper, get them to the right spot
if( current_user_can('stat_keeper') ) echo '<script>window.location="' . site_url() . '/account/stat_keep/";</script>';
//if we have a stat keeper, get them to the right spot
if( current_user_can('gate_controller') ) echo '<script>window.location="' . site_url() . '/account/gatekeep/";</script>';

//Dont show dashboard message director Account
if( current_user_can('club') ) {
  ?><p></p><?php
} else {
?><p><?php
	printf(
		__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
?></p><?php
}
?>

<p><?php
	/* translators: 1: user display name 2: logout url */
	printf(
		__( 'Hello %1$s! Welcome to your Passport Dashboard.', 'woocommerce' ),
// 		__( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) )
	);
?></p>


<?php
//Director Account
if( current_user_can('club') ) {
  echo '<div class="dashboard-directors">
          <a href="' . site_url() . '/account/club/">
            <h2 class="font-dharma text-center">Manage<span class="block">Club</span></h2>
            <p class="font-dharma text-center hide">Manage Club Information</p>
          </a>
          <a href="' . site_url() . '/account/rosters/">
            <h2 class="font-dharma text-center">Manage<span class="block">Rosters</span></h2>
            <p class="font-dharma text-center hide">Create Rosters</p>
          </a>
        </div>';  
}
?>

<pre>
<?php //print_r(user_capabilities()); ?>
</pre>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
