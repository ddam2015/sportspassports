<?php
/**
 * Template Name: Player Featured
 */
get_header();
$g365_ad_info = g365_start_ads( $post->ID );

$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
// $campBg = get_site_url() . '/wp-content/uploads/2021/09/camp-awards-header.jpg';
$campBg = 'https://grassroots365.com/wp-content/uploads/2021/09/camp-awards-header.jpg';

$award_data = g365_build_awards(24);
// echo '<pre class="">';
// print_r($award_data->records);
// echo '</pre>';	

?>

<section id="content" class="grid-x grid-margin-x site-main xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
// echo '<pre>';
// print_r($award_data->awards);
// echo '</pre>';
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		?>
        <div class="camp-awards__wrap large-margin-bottom">
          
            <img src="<?php echo $campBg ?>" class="camp-awards__img" alt="camp-awards image">
          
            <div class="camp-awards__info">
                <h1 class="camp-awards__heading">Camp Awards</h1>
                <p class="camp-awards__text large-margin-top">Here are the award winners from our <a class="camp-awards__link" href="https://elitebasketballcircuit.com">Elite Basketball Circuit Camps</a></p>
            </div>
        </div>
        <div class="event-tabs__wrapper">
            <div class="tabs separate grid-x small-up-2 medium-up-3 large-up-4 align-center text-center collapse event-tabs--campAwards" id="event-tabs" data-tabs>
                <?php foreach( $award_data->records as $dex => $group_data ) : if( empty(array_intersect($group_data->item_ids, $award_data->data_present)) ) continue; ?>
                    <div class="tabs-title cell<?php echo ( $dex == 1 ) ? ' is-active expanded' : ''; ?>">
                        <a href="#<?php echo strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name)); ?>"><?php echo $group_data->name; ?></a></div>
                <?php endforeach; ?>
            </div>
        </div>
		<div id="tables-container" class="tabs-content table-data table-reveal gset-wrap-tabs" data-tabs-content="event-tabs">
			<?php foreach( $award_data->records as $dex => $group_data ) : if( empty(array_intersect($group_data->item_ids, $award_data->data_present)) ) continue;
				$group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
			?>
			<div class="grid-x tabs-panel tabs-panel--camp<?php echo ( $dex == 1 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>">
				<div class="cell small-12 campAwards">
					<h2 class="hide"><?php echo $group_data->name; ?></h2>
					<div class="text-center">
						<img class="width-50-200" src="<?php echo $group_data->records[0]->logo_img; ?>" alt="Official Logo of <?php echo $group_data->name; ?>">
					</div>
					<div>
                        <div class="event-tabs__wrapper">
                            <nav class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse medium-padding-bottom small-padding-top camp-awards__nav" id="<?php echo $group_data->handle; ?>_awards" data-tabs>
                                <?php foreach( $group_data->records as $ev_dex => $event_data ) : if( empty(in_array($event_data->id, $award_data->data_present)) ) continue;
                                    $increment_title = date('Y',strtotime($event_data->eventtime));
                                ?>
                                <div class="tabs-title<?php echo ( $ev_dex == 0 ) ? ' is-active' : ''; ?>"><a href="#<?php echo $group_data->handle; ?>_awards_<?php echo $increment_title; ?>"><?php echo $increment_title; ?></a></div>
                                <?php endforeach; ?>
                            </nav>
                        </div>    
						<div class="award-data tabs-content table-data camp-awards__content" data-tabs-content="<?php echo $group_data->handle; ?>_awards">
						<?php foreach( $group_data->records as $ev_dex => $event_data ) : if( empty(in_array($event_data->id, $award_data->data_present)) ) continue; ?>
							<div class="tabs-panel tabs-panel--camp<?php echo ( $ev_dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>_awards_<?php echo date('Y',strtotime($event_data->eventtime)); ?>">
								 <table class="pTable text-center no-margin-bottom scroll-for-small">
									<tbody class="table-stripe">
										<tr class="sub-head">
										<?php
										$col_classes = array_keys( current($award_data->awards->{$event_data->id}) );
                    $col_count = count( $col_classes );
										foreach( $col_classes as $award_title_dex => $award_class) : ?>
											<th>Class of <?php echo $award_class; ?></th>
										<?php endforeach; ?>
										</tr>
										<?php foreach( $award_data->awards->{$event_data->id} as $award_dex => $award_vals) : ?>
										<tr class="division-title table-border--vertical">
											<th colspan="<?php echo $col_count; ?>">
												<h3><?php echo $award_vals[$col_classes[0]][0]->award_label; ?></h3>
											</th>
										</tr>
										<?php
										$line_count = 0;
										foreach( $award_vals as $award_item_dex => $award_val ) if( count( $award_val ) > $line_count ) $line_count = count( $award_val );
										for( $i=0; $i < $line_count; $i++ ){
											echo '<tr>';
											foreach( $col_classes as $award_title_dex => $award_class ) {
												echo ( !empty($award_vals[$award_class][$i]->award_type) && $award_vals[$award_class][$i]->award_type == 1) ? '<td class="vertical-align award-data__td gold-border reflection">' : '<td class="vertical-align award-data__td">';
												if( empty($award_vals[$award_class][$i]->player_url) ) {
// 													echo '&nbsp;';
                          echo '';
												} else {
													echo '<a href="' . get_site_url() . '/player/' . $award_vals[$award_class][$i]->player_url . '"  class="award-data__a">';
// 													if( $award_vals[$award_class][$i]->award_type == 1){
														$player_img = ( empty($award_vals[$award_class][$i]->profile_img) ) ? $default_profile_img : $award_vals[$award_class][$i]->profile_img;
														echo '<img loading="lazy" class="allTournament__player-img small-margin-top small-margin-bottom" src="' . $player_img . '" alt="' . $award_vals[$award_class][$i]->player_name . ' at ' . $group_data->name . '"><br>';
// 													}
													echo '<a style="color: #FEFEFE" >' . $award_vals[$award_class][$i]->player_name . '</a><span class="camp-class--mobile show-for-small-only">Class of '.$award_vals[$award_class][$i]->award_class .'</span></a>';
												}
												echo '</td>';
											}
											echo '</tr>';
										}
										endforeach;
										?>
									</tbody>
								</table>
							</div>
						<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<script type="text/javascript">
  window.addEventListener('DOMContentLoaded', function(event) {
  var allAmericanTitle = document.querySelector('#ebcjrallamerican-label');
  allAmericanTitle.innerHTML = 'Ballislife Middle School All-American';
});
</script>
<?php get_footer(); ?>