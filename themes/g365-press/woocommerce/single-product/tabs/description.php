<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Details', 'woocommerce' ) ) );

?>

<?php if ( $heading ) : ?>
  <h2 class="hide"><?php echo $heading; ?></h2>
<?php endif; ?>

<?php
  $product_event_schedule_link = intval(get_post_meta( get_the_ID(), '_event_link', true ));
  if( $product_event_schedule_link !== 0 ) {
    $product_event_data = g365_get_event_data($product_event_schedule_link, true);
    $product_event_schedule_data = json_decode($product_event_data->schedule_link);
    if( gettype($product_event_schedule_data) === 'object' && !empty($product_event_schedule_data) ) {
      echo '<div class="grid-x grid-margin-x">';
      if( !empty($product_event_schedule_data->exposure) ) : ?>
        <div id="schedule_link" class="cell <?php echo (empty($product_event_schedule_data->mobiticket)) ? 'small-12' : 'small-6' ?>">
          <h2 class="medium-margin-bottom"><a class="button secondary expanded no-margin-bottom" href="/event/<?php echo $product_event_data->nickname; ?>"><span class="fi-clipboard-pencil"></span> Schedule</a></h2>
        </div>
      <?php endif;
      if( !empty($product_event_schedule_data->mobiticket) ) : ?>
        <div id="ticket_link" class="cell <?php echo (empty($product_event_schedule_data->exposure)) ? 'small-12' : 'small-6' ?>">
          <h2 class="medium-margin-bottom"><a class="button secondary expanded no-margin-bottom" href="<?php echo $product_event_schedule_data->mobiticket; ?>"><span class="fi-ticket"></span> Tickets</a></h2>
        </div>
      <?php endif;
      echo '</div>';
    }
  }
?>

<?php the_content(); ?>
