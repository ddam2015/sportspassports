<?php

function custom_rewrite_rule() {
	add_rewrite_rule('^club/([^/]*)/?([^/]*)?/?([^/]*)?/?([^/]*)?/?','index.php?page_id=866&org_name=$matches[1]&pg_type=$matches[2]&team_name=$matches[3]&event_name=$matches[4]','top');
	add_rewrite_rule('^player/([^/]*)/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?','index.php?page_id=479&pl_id=$matches[1]&pl_pg=$matches[2]&pl_tp=$matches[3]&y=$matches[4]&st_type=$matches[5]','top');
	add_rewrite_rule('^event/([^/]*)/?([^/]*)?/?','index.php?page_id=518&ev_id=$matches[1]&ev_tp=$matches[2]','top');
	add_rewrite_rule('^team-ranking/([^/]*)/?([^/]*)?/?','index.php?page_id=52&rk_id=$matches[1]&rk_tp=$matches[2]','top');
	add_rewrite_rule('^register/([^/]*)/?([^/]*)?/?([^/]*)?/?','index.php?page_id=4176&rg_tp=$matches[1]&rg_ps=$matches[2]&rg_og=$matches[3]','top');
	add_rewrite_rule('^players-to-watch/([^/]*)/?([^/]*)?/?','index.php?page_id=1041&wt_id=$matches[1]&wt_tp=$matches[2]','top');
	add_rewrite_rule('^calendar/([^/]*)/?([^/]*)?/?','index.php?page_id=1205&cl_id=$matches[1]&cl_tp=$matches[2]','top');
	add_rewrite_rule('^tournament-awards/([^/]*)/?([^/]*)?/?','index.php?page_id=19786&aw_id=$matches[1]&aw_tm=$matches[2]','top');
	add_rewrite_rule('^stat-leaderboard/([^/]*)/?([^/]*)/?','index.php?page_id=5088&pg_type=$matches[1]&subpg_type=$matches[2]','top');
  add_rewrite_rule('^club-team-standing/([^/]*)/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?([^/]*)?/?','index.php?page_id=26098&pg_type=$matches[1]&lv_type=$matches[2]&lv_label=$matches[3]&tm=$matches[4]&gm=$matches[5]&y=$matches[6]&lv_pl=$matches[7]','top');
	add_rewrite_rule('^features/([^/]*)/?([^/]*)?/?([^/]*)?/?','index.php?page_id=84210&v_type=$matches[1]&ft_type=$matches[2]&org_id=$matches[3]','top');
  
  add_rewrite_rule('^tournament-table.js','wp-content/plugins/g365-data-manager/js/tournament_table.js','top');
	add_rewrite_rule('^data-processor.js','wp-content/plugins/g365-data-manager/js/g365_ajax_cookie_ls_app.js','top');
	add_rewrite_rule('^data-processor-admin.js','wp-content/plugins/g365-data-manager/js/g365_ajax_cookie_ls_app_admin.js','top');
	add_rewrite_rule('^data-processor-front-admin.js','wp-content/plugins/g365-data-manager/js/g365_ajax_cookie_ls_app_front_admin.js','top');
	add_rewrite_rule('^data-process/?','wp-admin/admin-ajax.php?action=g365_ajax_registration_form','top');
	add_rewrite_rule('^data-request/?','wp-admin/admin-ajax.php?action=g365_ajax_request_data','top');
	add_rewrite_rule('^livesearch/','wp-content/plugins/g365-data-manager/inc/livesearch.php','top');
	add_rewrite_rule('^session-gen/','wp-content/plugins/g365-data-manager/inc/session-handler-public.php','top');
	add_rewrite_rule('^exposure.css/?','wp-content/themes/g365-press/css/exposure.css','top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);

//custom page url rewrites
function custom_rewrite_tag() {
  add_rewrite_tag('%org_name%', '([^&]+)');
  add_rewrite_tag('%pg_type%', '([^&]+)');
  add_rewrite_tag('%team_name%', '([^&]+)');
  add_rewrite_tag('%event_name%', '([^&]+)');
  add_rewrite_tag('%y%', '([^&]+)');
  add_rewrite_tag('%st_type%', '([^&]+)');
  add_rewrite_tag('%tm%', '([^&]+)');
  add_rewrite_tag('%gm%', '([^&]+)');
  add_rewrite_tag('%lv_pl%', '([^&]+)');
  add_rewrite_tag('%v_type%', '([^&]+)');
  add_rewrite_tag('%ft_type%', '([^&]+)');
  add_rewrite_tag('%org_id%', '([^&]+)');

	add_rewrite_tag('%pl_id%', '([^&]+)');
  add_rewrite_tag('%pl_pg%', '([^&]+)');
  add_rewrite_tag('%pl_tp%', '([^&]+)');
  add_rewrite_tag('%ev_id%', '([^&]+)');
  add_rewrite_tag('%ev_tp%', '([^&]+)');	
  add_rewrite_tag('%rk_id%', '([^&]+)');
  add_rewrite_tag('%rk_tp%', '([^&]+)');	
  add_rewrite_tag('%rg_tp%', '([^&]+)');
  add_rewrite_tag('%rg_ps%', '([^&]+)');	
  add_rewrite_tag('%rg_og%', '([^&]+)');	
  add_rewrite_tag('%wt_id%', '([^&]+)');
  add_rewrite_tag('%wt_tp%', '([^&]+)');	
  add_rewrite_tag('%cl_id%', '([^&]+)');
  add_rewrite_tag('%cl_tp%', '([^&]+)');	
  add_rewrite_tag('%aw_id%', '([^&]+)');
  add_rewrite_tag('%aw_tm%', '([^&]+)');
  add_rewrite_tag('%lv_type%', '([^&]+)');
  add_rewrite_tag('%lv_label%', '([^&]+)');
  add_rewrite_tag('%subpg_type%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);
/*
 * load required files
 */
get_template_part( 'inc/cleanup' );
get_template_part( 'inc/menu-cache' );
get_template_part( 'inc/menu-walkers' );
get_template_part( 'inc/gallery' );
get_template_part( 'inc/general' );
get_template_part( 'inc/woocomm' );
get_template_part( 'inc/woocomm-gatekeep' );
get_template_part( 'inc/filepond' );

function my_theme_setup(){
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'my_theme_setup');

// add_action( 'woocommerce_review_order_before_submit', 'pp_find_product_in_cart' );

//   function pp_find_product_in_cart() {
   
//     $annual_product_id = 10388;
//     $monthly_product_id = 84826;
//     $annual_in_cart = false;
//     $monthly_in_cart = false;
  
//    foreach( WC()->cart->get_cart() as $cart_item ) {
//       $product_in_cart = $cart_item['product_id'];
//       if ( $product_in_cart === $annual_product_id ) $annual_in_cart = true;
//       if ( $product_in_cart === $monthly_product_id ) $monthly_in_cart = true;
//    }
  
//    if ( $annual_in_cart ) {
//      echo '<p class="form-row validate-required" id="termsAnnual" >
// 				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
// 				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="termsAnnual" />
// 					<span class="woocommerce-terms-and-conditions-checkbox-text" style="color:black;">I have read and agree to the <a target="_blank"href="https://dev.sportspassports.com/annual-passport-terms/" style="color:black;">Annual Subscription Terms and Conditions</a></span>&nbsp;<abbr class="required">*</abbr>
// 				</label>
// 				<input type="hidden" name="terms-field" value="1" />
// 			</p>';
//    }
    
//    if ( $monthly_in_cart ) {
//      echo '<p class="form-row validate-required" id="termsMonthly" >
// 				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
// 				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="termsMonthly" />
// 					<span class="woocommerce-terms-and-conditions-checkbox-text" style="color:black;">I have read and agree to the <a target="_blank"href="https://dev.sportspassports.com/monthly-passport-terms/" style="color:black;">Monthly Subscription Terms and Conditions</a></span>&nbsp;<abbr class="required">*</abbr>
// 				</label>
// 				<input type="hidden" name="terms-field" value="1" />
// 			</p>';
//    }

//   }
?>