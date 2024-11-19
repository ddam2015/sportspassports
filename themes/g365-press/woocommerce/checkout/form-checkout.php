<?php
/**
 * Checkout Form
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( $checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in() ) {
  echo do_shortcode( '[woocommerce_my_account]' );
  echo '<hr/>';
	echo '<h5 class="callout medium-padding">' . apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) . '</h5>';
	return;
}

// logged in - check if player account/subscriber or not need to account for DCP and Scouting report
// if(!current_user_can('player_editor')) {
// 	echo '<h5 class="callout medium-padding">' . apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be signed into a Player/Parent account to checkout.', 'woocommerce' ) ) . '</h5>';
// 	return;
// }

?>

<?php if ( $checkout->get_checkout_fields() ) do_action( 'g365_collect_data_fields' ); ?>

<form id="woocommerce-checkout-form" name="checkout" method="post" class="checkout woocommerce-checkout g365-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
    <!-- <meta http-equiv="refresh" content="10"> -->
 
	<?php if ( $checkout->get_checkout_fields() ) : 
    do_action( 'woocommerce_checkout_inside_top' ); ?>

    <header class="entry-header">
        <h2 class="entry-title"><?php _e( 'Checkout', 'woocommerce' ); ?></h2>
    </header>

    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="grid-x grid-margin-x" id="customer_details">
			<div class="cell small-12 medium-6">
				<?php
        do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="cell small-12 medium-6">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
      
		</div>

    <div id="order_review" class="woocommerce-checkout-review-order">
      
      <h3 id="order_review_heading" class="medium-margin-top"><?php _e( 'Review Order', 'woocommerce' ); ?></h3>
    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>


    <p style="color: white;">
        <strong>Refund Policy:</strong> No refunds, all sales are final.
    </p>
      
      <?php do_action( 'woocommerce_checkout_order_review' ); ?>
    </div>

    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
  

	<?php endif; ?>
  

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
