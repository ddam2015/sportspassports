<?php
/**
 * Class WCS_Email_Failed_Passport_Renewal_Orders file.
 *
 * @package WooCommerce\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WCS_Email_Failed_Passport_Renewal_Orders', false ) ) :

	/**
	 * Customer Completed Order Email.
	 *
	 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
	 *
	 * @class       WCS_Email_Failed_Passport_Renewal_Orders
	 * @version     2.0.0
	 * @package     WooCommerce\Classes\Emails
	 * @extends     WC_Email
	 */
	class WCS_Email_Failed_Passport_Renewal_Orders extends WC_Email {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id             = 'customer_passport_purchase_order_failed';
			$this->customer_email = true;
			$this->title          = __( 'Passport Purchase Order Failed', 'woocommerce' );
			$this->description    = __( 'This Passport Order email is sent to customers when passport purchase fails.', 'woocommerce' );
			$this->template_html  = 'emails/customer-processing-passport-renewal-orders-failed.php';
			$this->template_plain = 'emails/customer-processing-passport-renewal-orders-failed.php';
			$this->placeholders   = array(
				'{order_date}'   => '',
				'{order_number}' => '',
			);

			// Triggers for this email.
			add_action( 'woocommerce_order_status_pending_to_failed_notification', array( $this, 'trigger' ), 10, 2 );
			add_action( 'woocommerce_order_status_on-hold_to_failed_notification', array( $this, 'trigger' ), 10, 2 );

			// Call parent constructor.
			parent::__construct();
		}
    
    
    /**
		 * Get email subject.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_subject() {
			return __( 'Your Passport purchase has failed.', 'woocommerce' );
		}

		/**
		 * Get email heading.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_heading() {
			return __( 'We apologize for the inconvenience', 'woocommerce' );
		}

		/**
		 * Trigger the sending of this email.
		 *
		 * @param int            $order_id The order ID.
		 * @param WC_Order|false $order Order object.
		 */
		public function trigger( $order_id, $order = false ) {
			$this->setup_locale();

			if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
				$order = wc_get_order( $order_id );
			}
      
      //specidy the product we are sending this email for (should be the id of passport product)
      $product_id = array(10388);
      
      //for testing id
//       $product_id = array(87121);
      
      //getting all products inside the order
      $items = $order->get_items();
      
      //variable to check if specific product was bought
      $bought = false;
      
      //     //looping through all items in order to check if passport is being purchased
      foreach($items as $item){


        if( in_array( $item['product_id'], $product_id ) ){
          $bought = true;
        }

      }
      
      
      if($bought){
        
			if ( is_a( $order, 'WC_Order' ) ) {
				$this->object                         = $order;
				$this->recipient                      = $this->object->get_billing_email();
				$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
				$this->placeholders['{order_number}'] = $this->object->get_order_number();
			}

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}

			$this->restore_locale();
        
        
      }
      

		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'order'              => $this->object,
					'email_heading'      => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'sent_to_admin'      => false,
					'plain_text'         => false,
					'email'              => $this,
				)
			);
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			return wc_get_template_html(
				$this->template_plain,
				array(
					'order'              => $this->object,
					'email_heading'      => $this->get_heading(),
					'additional_content' => $this->get_additional_content(),
					'sent_to_admin'      => false,
					'plain_text'         => true,
					'email'              => $this,
				)
			);
		}

		/**
		 * Default content to show below main email content.
		 *
		 * @since 3.7.0
		 * @return string
		 */
		public function get_default_additional_content() {
			return __( 'Thanks for shopping with us.', 'woocommerce' );
		}
	}

endif;

return new WCS_Email_Failed_Passport_Renewal_Orders();
