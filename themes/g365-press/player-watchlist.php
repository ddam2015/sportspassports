<?php
/**
 * Template Name: Player Watchlist
 */
get_header();
$g365_ad_info = g365_start_ads( $post->ID );

$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
$watchlistbg = get_site_url() . '/wp-content/uploads/2021/08/watchlistbg.jpg';

?>

<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12 watchlist">
		<?php
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', 'rankings' );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
		echo '<div class="watchlist__wrap large-margin-bottom">
            <img src="https://grassroots365.com/wp-content/uploads/2022/12/EBCWatchlist.jpeg" class="watchlist__img" alt="watchlist image">
            <div class="watchlist__info">
                <h1 class="watchlist__heading">Player Watchlist</h1>
                <p class="watchlist__text large-margin-top">Keep an eye out for these outstanding performers</p>
            </div>
        </div>';
		global $wp_query;
		//pull vars if we have them
		$watchlist_set = ( empty($wp_query->query_vars['wt_id']) ? 55 : $wp_query->query_vars['wt_id'] );
		$watchlist_date = ( empty($wp_query->query_vars['wt_tp']) ? null : explode('_', $wp_query->query_vars['wt_tp']) );
		if( is_array($watchlist_date) && !empty($watchlist_date[0]) ) {
			$watchlist_date = array(
				'min-limit' => $watchlist_date[0],
				'max-limit' => (empty($watchlist_date[1]) ? $watchlist_date[0] : $watchlist_date[1])
			);
		}
		$watchlist_data = g365_build_watchlist($watchlist_set, $watchlist_date);
// 		echo '<pre class="">';
// 		print_r($watchlist_data);
// 		echo '</pre>';
	//if we have data, process it.
	if( !empty($watchlist_data) && is_object($watchlist_data) ) :
		//if we have a group of groups, create the heading for that
		if( $watchlist_data->groups == 1 ) : ?>
			<h3><?php echo date("F Y", strtotime($watchlist_data->records[0]->records[0]->start_datetime)); ?></h3>
			<div class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse" id="event-tabs" data-tabs data-deep-link="true" data-deep-link-smudge="true" data-deep-link-smudge-delay="600">
				<?php foreach( $watchlist_data->records as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue; ?>
					<div class="tabs-title cell<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>">
						<a href="#<?php echo strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name)); ?>"><?php echo (empty($group_data->abbr)) ? $group_data->name : $group_data->abbr; ?></a>
          </div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<h3><?php echo date("F Y", strtotime($watchlist_data->records[0]->start_datetime)) . ' - ' . $watchlist_data->name; ?></h3>
		<?php endif; ?>
		<div id="tables-container" class="tabs-content table-data table-reveal gset-wrap-tabs" data-tabs-content="event-tabs">
			<?php foreach( ( $watchlist_data->groups == 1 ? $watchlist_data->records : array($watchlist_data) ) as $dex => $group_data ) : if( empty($group_data->item_ids) ) continue;
				$group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
			?>
			<div class="grid-x tabs-panel small-padding<?php echo ( $dex == 0 ) ? ' is-active" role="tabpanel" aria-hidden="false' : ''; ?>" id="<?php echo $group_data->handle; ?>">
				<div class="cell">
					<h2 class="watchlist__tab-heading emphasis border-radius"><?php echo $group_data->name; ?></h2>
					<div class="relative">
                        <!-- <div class="mobile-tabs__arrows">
                            <p class="mobile-tabs__leftArrow"><</p>
                            <p class="mobile-tabs__rightArrow">></p>
                        </div> -->
                        <div class="watchlist__tabs-wrapper mobileScroll">
                            <nav class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse medium-padding-bottom" id="<?php echo $group_data->handle; ?>_players" data-tabs>
                                <?php foreach( $group_data->records as $dex => $subgroup_data ) : if( empty($subgroup_data->rankings) ) continue; ?>
                                <div class="tabs-title<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>"><a href="#<?php echo $group_data->handle; ?>_players_<?php echo $subgroup_data->id; ?>"><?php echo $subgroup_data->ranking_type; ?></a></div>
                                <?php endforeach; ?>
                            </nav>
                        </div>
						<div class="tabs-content relative" data-tabs-content="<?php echo $group_data->handle; ?>_players">
                            <?php foreach( $group_data->records as $dex => $subgroup_data ) : if( empty($subgroup_data->rankings) ) continue; ?>
                                <div class="watchlist__panel gray-bg tabs-panel<?php echo ( $dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>_players_<?php echo $subgroup_data->id; ?>">
                                    <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-5 align-center text-center img-grid">
                                        <?php
                                        foreach( $subgroup_data->rankings as $subdex => $player_id ) :
                                            $validate_player_img = g365_player_img_dir($watchlist_data->player_records[$player_id]->player_url, $watchlist_data->player_records[$player_id]->event_nickname, $watchlist_data->player_records[$player_id]->id);
                                            $player_img = ( empty($watchlist_data->player_records[$player_id]->player_img) ) ? $validate_player_img : $watchlist_data->player_records[$player_id]->player_img;
                                        ?>
                                        <div class="cell">
                                            <a class="emphasis watchlist__player" href="<?php echo get_site_url(); ?>/player/<?php echo $watchlist_data->player_records[$player_id]->player_url; ?>">
                                                <img class="watchlist__player-img small-margin-bottom lazy-img" loading="lazy" data-src="<?php echo $validate_player_img; ?>" alt="Player headshot for <?php echo $watchlist_data->player_records[$player_id]->name; ?>" /><br>
                                                <p><?php echo $watchlist_data->player_records[$player_id]->name; ?></p>
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- <ul class="alphabet-nav" id="watchlistNav">
                                <li class="alphabet-li">A</li>
                                <li class="alphabet-li">B</li>
                                <li class="alphabet-li">C</li>
                                <li class="alphabet-li">D</li>
                                <li class="alphabet-li">E</li>
                                <li class="alphabet-li">F</li>
                                <li class="alphabet-li">G</li>
                                <li class="alphabet-li">H</li>
                                <li class="alphabet-li">I</li>
                                <li class="alphabet-li">J</li>
                                <li class="alphabet-li">K</li>
                                <li class="alphabet-li">L</li>
                                <li class="alphabet-li">M</li>
                                <li class="alphabet-li">N</li>
                                <li class="alphabet-li">O</li>
                                <li class="alphabet-li">P</li>
                                <li class="alphabet-li">Q</li>
                                <li class="alphabet-li">R</li>
                                <li class="alphabet-li">S</li>
                                <li class="alphabet-li">T</li>
                                <li class="alphabet-li">U</li>
                                <li class="alphabet-li">V</li>
                                <li class="alphabet-li">W</li>
                                <li class="alphabet-li">X</li>
                                <li class="alphabet-li">Y</li>
                                <li class="alphabet-li">Z</li>
                            </ul> -->
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="archive large-margin-top">
			<hr>
			<h2>Archive</h2>
			<?php
			foreach( $watchlist_data->ranking_brackets as $dex => $dates ) {
				$date_start = date("Y-m-d", strtotime($dates->start_datetime));
				$date_end = date("Y-m-d", strtotime($dates->end_datetime));
				$date_name = ( ( $dex === 0 ) ? '' : '' ) . date("M Y", strtotime($dates->start_datetime));
				if( ($watchlist_date === null && $dex === 0) || $date_start == date("Y-m-d", strtotime($watchlist_date['min-limit'])) ) {
					echo '<a class="button primary archive__btn" href="#">' . $date_name . '</a> ';
				} else {
					$date_url_helper = ( is_numeric($watchlist_set) ) ? $watchlist_data->nickname : $watchlist_set;
					$date_url = get_permalink() . $date_url_helper . '/' . $date_start . '_' . $date_end;
					echo '<a class="button archive__btn" href="' . $date_url . '">' . $date_name . '</a> ';
				}
			}
			?>
		</div>
	<?php	else :
		$g365_error = '<h3>Sorry, no data found.</h3><br><a href="/players-to-watch/">back to main watchlist</a>';
		if( !empty($player_data) ) $g365_error .= '<p>' . $watchlist_data . '</p>';
		echo $g365_error;
	endif; ?>
	</div>
</section>

<?php get_footer(); ?>