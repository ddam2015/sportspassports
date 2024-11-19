<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * @package OGP G365 Press
 * @since G365 1.0.0
 */
get_header();
$g365_ad_info = g365_start_ads( $post->ID );

?>
<section id="content" class="grid-x site-main <?php echo ( has_post_thumbnail( $post->ID ) && !is_product() ) ? 'small-padding-top ' : 'large-padding-top '; if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell large-padding small-12">
		<?php
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : 
		if ( is_home() && ! is_front_page() ) : ?>
		<div class="tiny-margin-bottom tiny-padding gset no-border">
			<h1 class="entry-title screen-reader-text"><?php single_post_title(); ?></h1>
		</div>
		<?php endif;
		while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
	</div>
</section>

<?php get_footer(); ?>