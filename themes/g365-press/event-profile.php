<?php
/**
 * Template Name: Event Profile
 */

//load variables
global $wp_query;
//available vars from url
// $ev_id = $wp_query->query_vars['ev_id'];
// $ev_tp = $wp_query->query_vars['ev_tp'];

get_header();
$g365_ad_info = g365_start_ads( $post->ID );

//img defaults
$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
$default_badge_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/g365_default_badge.png';

//if we have an event, process it, otherwise show the search page
if( !empty($wp_query->query_vars['ev_id']) ) :
//get all event data
$event_data = g365_get_event_data( $wp_query->query_vars['ev_id'] );


	if( !empty($event_data) && is_object($event_data) ) :
		$print_search = false;

		$event_data->social = ( empty($event_data->social) ) ? $event_data->org_social : $event_data->social;

// 		echo '<pre class="hide">';
// 		print_r($event_data);
// 		echo '</pre>';

		?>

  <section id="content" class="grid-x grid-margin-x site-main small-padding-top medium-padding-bottom profile-wrap<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
			<?php
			if ( $g365_ad_info['go'] ) {
				echo '<div class="cell small-12">';
				echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
				echo '</div>';
			}
			?>
    <div class="cell small-12 small-padding no-padding-top">
			<div id="profile-wrapper" class="grid-x grid-margin-x small-padding-sides medium-padding-top medium-padding-bottom profile">
        <div id="profile-name-mobile" class="cell small-12 show-for-small-only">
          <h1 class="profile-name verified-title no-margin-bottom">
            <?php echo $event_data->name; ?>
          </h1>
          <hr id="profile-title-divider" class="profile-divider small-margin-top small-margin-bottom" />
        </div>
        <div id="profile-image" class="cell small-6 medium-3">
          <img src="<?php echo ( !empty($event_data->logo_img) ) ? $event_data->logo_img : $default_profile_img; ?>" alt="<?php echo $event_data->name . ' official logo'; ?>" />
        </div>
        <div id="profile-name" class="cell small-12 medium-9">
          <div class="hide-for-small-only">
						<h1 class="profile-name verified-title no-margin-bottom">
              <?php echo $event_data->name; ?>
						</h1>
          </div>
          <hr id="profile-title-divider" class="profile-divider small-margin-top small-margin-bottom" />
          <div class="grid-x grid-margin-x">
            <div id="profile-info" class="cell small-6 medium-5">
							<table class="unstriped stack profile-data tiny-margin-bottom">
								<tbody>
									<tr><th><strong>Host:</strong></th><td><a href="<?php echo $event_data->link; ?>"><?php echo $event_data->org_name; ?></a></td></tr>
									<tr><th><strong>Date:</strong></th><td><?php echo ( empty($event_data->dates) ) ? '' : g365_build_dates($event_data->dates); ?></td></tr>
									<tr><th><strong>Location:</strong></th><td><?php echo ( empty($event_data->locations) ) ? '' : g365_build_locations($event_data->locations); ?></td></tr>
									<!--<tr><th><strong>Schedule:</strong></th><td><?php echo ( empty($event_data->schedule_link) ) ? '' : $event_data->schedule_link; ?></td></tr>
									<tr><th><strong>Sign-Up:</strong></th><td><?php echo ( empty($event_data->link) ) ? '' : '<a href="' . $event_data->link . '" target="_blank">Event Page</a>'; ?></td></tr>
									<tr><th><strong>Contact:</strong></th><td>
										<?php echo ( empty($event_data->contact_name) ) ? '' : $event_data->contact_name . '<br>'; ?>
										<?php echo ( empty($event_data->email) ) ? '' : $event_data->email . '<br>'; ?>
										<?php echo ( empty($event_data->phone) ) ? '' : $event_data->phone; ?>
										</td></tr>-->
									<tr><th><strong>Social:</strong></th><td>
										<?php echo ( empty($event_data->social) ) ? '' : g365_build_social_block(json_decode($event_data->social), $event_data->hashtag); ?>
										</td></tr>
								</tbody>
							</table>
            </div>
            <div id="profile-video" class="cell small-12 medium-7">
						<?php if( !empty($event_data->video) ) : ?>
							<div class="responsive-embed widescreen">
								<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $event_data->video; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
							</div>
						<?php else: echo '&nbsp;'; endif; ?>
            </div>
            <!--     Stats and Standings for the Stage events only         -->
            <?php if($event_data->org == 3): ?>
            <div class="wp-block-columns is-layout-flex wp-container-9 large-6" style="text-align:center">
              <div class="wp-block-column is-layout-flow">
              <div class="wp-block-columns is-layout-flex wp-container-3">




              <div class="wp-block-column is-layout-flow">
              <a href="https://thestagecircuit.com/stat-leaderboard/"><figure class="wp-block-image size-full"><img decoding="async" loading="lazy" width="400" height="300" src="https://thestagecircuit.com/wp-content/uploads/2023/01/Stage-Stats.png" alt="" class="wp-image-2718"><figcaption>STATS</figcaption></figure></a>
              </div>
              </div>
              </div>



              <div class="wp-block-column is-layout-flow">
              <div class="wp-block-columns is-layout-flex wp-container-7">




              <div class="wp-block-column is-layout-flow">
              <a href="https://thestagecircuit.com/team-standings/"><figure class="wp-block-image size-full"><img decoding="async" loading="lazy" width="400" height="300" src="https://thestagecircuit.com/wp-content/uploads/2023/01/Stage-Standings.png" alt="" class="wp-image-2719"><figcaption>STANDINGS</figcaption></figure></a>
              </div>
              </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php if( !empty($event_data->awards) ) : ?>
        <div id="profile-awards" class="award-data cell small-12 options-wrapper xlarge-margin-top">
          <h2><span class="fi-trophy"></span> Awards</h2>
          <table class="pTable text-center table-data no-margin-bottom scroll-for-small gray-border">
            <tbody class="table-stripe">
              <tr class="sub-head">
              <?php
              $col_classes = array_keys( (array)$event_data->awards->{$event_data->award_types[0]} );
              $col_count = count( $col_classes );
              foreach( $col_classes as $award_title_dex => $award_class) : ?>
                <th>Class of <?php echo $award_class; ?></th>
              <?php endforeach; ?>
              </tr>
              <?php foreach( $event_data->awards as $award_title => $award_vals) : ?>
              <tr class="division-title">
                <th colspan="<?php echo $col_count; ?>">
                  <h3><?php echo $award_title; ?></h3>
                </th>
              </tr>
              <?php
              $line_count = 0;
              foreach( $award_vals as $award_item_dex => $award_val ) if( count( $award_val ) > $line_count ) $line_count = count( $award_val );
              for( $i=0; $i < $line_count; $i++ ){
                echo '<tr>';
                foreach( $col_classes as $award_title_dex => $award_class ) {
                  echo ( !empty($award_vals->{$award_class}[$i]->award_type) && $award_vals->{$award_class}[$i]->award_type == 'MVP') ? '<td class="award-profile-img">' : '<td>';
                  if( empty($event_data->stats[$award_vals->{$award_class}[$i]->player_id]->player_url) ) {
                    echo '&nbsp;';
                  } else {
                    echo '<a href="' . get_site_url() . '/player/' . $event_data->stats[$award_vals->{$award_class}[$i]->player_id]->player_url . '/profiles/#events' . preg_replace('/\s+|\.|-/', '', $event_data->name) . '">';
                    if( $award_vals->{$award_class}[$i]->award_type == 'MVP'){
                      $player_img = ( empty($event_data->stats[$award_vals->{$award_class}[$i]->player_id]->profile_img) ) ? $default_profile_img : $event_data->stats[$award_vals->{$award_class}[$i]->player_id]->profile_img;
                      echo '<img src="' . $player_img . '" alt="' . $event_data->stats[$award_vals->{$award_class}[$i]->player_id]->name . ' at ' . $event_data->name . '"><br>';
                    }
                    echo $event_data->stats[$award_vals->{$award_class}[$i]->player_id]->name . '</a>';
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
        <?php endif; ?>
        <?php if( $event_data->type == 2 && !empty($event_data->stats) ) : ?>
        <div id="profile-stats" class="cell small-12 xlarge-margin-top">
          <h2 class="medium-margin-bottom"><span class="fi-folder"></span> Profiles</h2>
          <div class="grid-x small-up-2 medium-up-3 large-up-5">
            <?php foreach ( $event_data->stats as $stat_dex => $stat ) : if( $stat->enabled == 0 ) continue; ?>
            <div class="cell">
              <h5><a href="<?php echo get_site_url(); ?>/player/<?php echo $stat->player_url . '/profiles/#events' . preg_replace('/\s+|\.|-/', '', $event_data->name); ?>"><?php echo $stat->name; ?></a></h5>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php if( !empty($event_data->schedule_link) ) : ?>
        <div id="profile-schedule" class="cell small-12">
          <h2 class="large-margin-top medium-margin-bottom"><span class="fi-clipboard-pencil"></span> Schedule</h2>
          <div class="grid-x">
            <?php
            $event_data->schedule_link = json_decode($event_data->schedule_link);
            //if we have movbile tickets add the link
            if( !empty($event_data->schedule_link->{'mobiticket'}) ) { ?>
            <div class="cell">
              <a class="button expanded" target="_blank" href="<?php echo esc_url_raw($event_data->schedule_link->{'mobiticket'}); ?>">Get your tickets Now!</a>
            </div>
            <?php }
            //if we have sponsors
            if( !empty($event_data->schedule_link->sponsors) ) {
              $sponsors = array();
              foreach($event_data->schedule_link->sponsors as $spon_key => $spon_data) {
                $sponsor_html = '<div class="cell text-center">';
                if( !empty($spon_data->link) ) $sponsor_html .= '<a href="' . $spon_data->link . '" target="_blank">';
                if( !empty($spon_data->img) ) $sponsor_html .= '<img class="round-corners" src="' . $spon_data->img . '" alt="' . $spon_key . '" />';
                if( !empty($spon_data->line) ) $sponsor_html .= $spon_data->line;
                if( !empty($spon_data->link) ) $sponsor_html .= '</a>';
                $sponsor_html .= '</div>';
                $sponsors[] = $sponsor_html;
              }
              $sponsor_count = count($sponsors);
              if( $sponsor_count > 0 ) { ?>
            <div class="cell">
              <div class="grid-x grid-padding-x small-up-2 medium-up-3 large-up-4 align-center small-margin-bottom">
                <?php echo implode($sponsors); ?>
              </div>
            </div>
              <?php }
            }
              
            foreach ( $event_data->schedule_link as $schedule_type => $schedule_data ) :
              switch($schedule_type) {
                case 'exposure': ?>
            <div class="cell">
              <div id="exp-schedule" data-href="https://basketball.exposureevents.com/widgets/v1/schedule?eventid=<?php echo intval($schedule_data); ?>" data-css="<?php echo get_site_url(); ?>/exposure.css?v=11" data-responsive="true" data-protocol="https" data-width="100%"></div>
              <script type="text/javascript">
                (function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.async = true;
                    js.src = "https://basketball.exposureevents.com/scripts/exposure.widgets.min.js";
                    fjs.parentNode.insertBefore(js, fjs);
                  }
                })(document, "script", "exp.widgets");
              </script>
            </div>
            <?php
                break;
              }
            endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php	else :
		$g365_event_error = '<h3>Sorry, no data found, please use the search below.</h3>';
		if( !empty($event_data) ) $g365_event_error .= '<p>' . $event_data . '</p>';
	endif;
endif;
// if there was any sort of error display the search and error message
if( $print_search !== false ) :
?>
<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		?>
		<h1 class="entry-title">Event Search</h1>
		<?php echo ( empty($g365_event_error) ) ? '' : $g365_event_error; ?>
		<div class="relative">
			<span class="search-mag fi-magnifying-glass"></span>
			<input type="text" class='search-hero g365_livesearch_input' data-g365_type="event_profiles" placeholder="Enter Event Name" autocomplete="off" autofocus>
		</div>
		<div class="xlarge-margin-top small-margin-bottom tiny-padding gset no-border">
			<h2 class="entry-title">Featured Events</h2>
		</div>
		<div class="grid-x grid-margin-x small-up-2 medium-up-4 text-center profile-feature">
			<?php $featured_events_arr = g365_display_events();
			foreach( $featured_events_arr as $dex => $obj ) : ?>
			<div class="cell">
				<div class="small-margin-bottom">
					<a href="<?php echo $obj->link; ?>" target="_blank">
						<img src="<?php echo (!empty($obj->logo_img)) ? $obj->logo_img : $default_event_img ?>" alt="<?php echo $obj->name; ?> official logo" />
						<p>
							<?php echo ( empty($obj->short_name) ) ? $obj->name : $obj->short_name; ?><br>	
							<small class="tiny-margin-top block"><?php echo g365_build_dates($obj->dates); ?></small>
						</p>
					</a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif;
get_footer(); ?>