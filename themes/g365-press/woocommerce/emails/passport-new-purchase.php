<?php 



if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }



    
  /*
   * @hooked WC_Emails::email_header() Output the email header
   */
//   do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>" style="max-width: none;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<div id="template_header_image">
							<?php
							if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
								echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
							}
							?>
						</div>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="custom_passport_header" class="custom_passport_header">
										<tr>
											<td id="header_wrapper">
												<h1 id="passport-header"><?php echo $email_heading; ?></h1>
											</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">

  <?php /* translators: %s: Customer first name */ ?>
  <p id="passport_name"><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<!--   <p id="passport_body"><?php esc_html_e( 'TEST TESTING We have finished processing your order.', 'woocommerce' ); ?></p> -->
  
                                
  <section class="passport-green-bg">
    <div class="contain-middle">
      <div class="passport-green--text">
        <h2 id="thank-you-text">Thank you for purchasing The Passport</h2>
        <p id="thank-you-text" class="renewal-title">Your annual subscription is set to renew in 12 months</p>
      </div>
      <div class="passport-white">
        <img id="passport_mobile_preview" src="<?php echo get_site_url() ?>/wp-content/themes/g365-press/assets/tiny-logos/Passport-Phone-Preview.png" alt="Phone Preview">
        <div class="passport-icons--players">
          <a href="https://sportspassports.com/account/subscriptions/" class="passport-icon--product">
            <p>Manage Subscription</p>
          </a>
          <a href="https://sportspassports.com/account/player_editor/" class="passport-icon--product">
            <p>View Account</p>
          </a>
          <a href="https://sportspassports.com/product/passport-annual/" class="passport-icon--product">
            <p>Passport Info and Features</p>
          </a>
        </div>
      </div>
    </div>
  </section>
                                
                                
  <section class="passport-media">
    <div class="contain-middle">
     <p>For any questions, contact customersuccess@sportspassports.com</p>

    </div>
  </section>
                                
  <?php
  
  defined( 'ABSPATH' ) || exit;
?>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<!-- Footer -->
						<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
							<tr>
								<td valign="top">
									<table border="0" cellpadding="10" cellspacing="0" width="100%">
										<tr>
											<td colspan="2" valign="middle" id="credit">
<!-- 												<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?> -->
                        <p style="margin:0 0 16px">
                        The Passport All Rights Reserved.
                        </p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- End Footer -->
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>