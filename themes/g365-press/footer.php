<?php
  $g365_layout_type = get_option( 'g365_layout' );
  if( !is_front_page() || $g365_layout_type['front_layout']['type'] !== 'tiles' ) echo '</div>';
?>
<footer id="site-footer" class="xlarge-padding small-small-padding no-margin-bottom relative">
  <div class="grid-container">
    <div class="grid-x medium-unstack align-middle">
    <a class="nav__logo g365-logo main-logo title-logos current-site cell shrink has-tip" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/Passport-2023.png" alt="<?php bloginfo( 'name' ); ?> Official Logo" /><span class="show-for-sr"><?php bloginfo( 'name' ); ?> Official Site</span></a>
					  
<!--       <div class="small-12 medium-4 large-3 cell">
        <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/g365-logo.png" alt="<?php bloginfo( 'name' ); ?> Logo" class="tiny-margin-bottom" />
      </div> -->
      <div class="small-12 medium-8 large-9 cell">
        <ul class="menu vertical medium-horizontal align-center small-text-center">
          <?php wp_nav_menu( array( 'theme_location' => 'footer_nav', 'container' => false, 'items_wrap' => '%3$s', 'depth' => 1 ) ); ?>
        </ul>
      </div>
			<?php $blog_info = get_bloginfo( 'name' ); ?>
			<?php if ( ! empty( $blog_info ) ) : ?>
        <div class="small-12 medium-12 large-12 text-center cell">
          <p>
           All Rights Reserved | &#169; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?> &trade; | <a href="<?php echo get_site_url() ?>/privacy-policy/" aria-current="page">Privacy Policy</a>
          </p>
        </div>
			<?php endif; ?>   
    </div>
  </div>
</footer>
<?php if(!strpos(get_site_url(), 'dev.')): ?>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y2BPRRCJBB"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-Y2BPRRCJBB');
  </script>
<?php endif; ?>
<?php wp_footer(); ?>
<?php if(!strpos(get_site_url(), 'dev.')): ?>
  <!-- Start of Salesmsg Embed Code -->
  <script type="text/javascript" src="https://d20ufhxg3m5wej.cloudfront.net/js/combine-200edb216d1a2e3e08b69b903d6608fc.js"></script>
  <!-- End of Salesmsg Embed Code -->
<?php endif; ?>
</body>

</html>