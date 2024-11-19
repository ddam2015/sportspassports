<?php
/**
 * Template Name: Club Profile
 */

//load variables
global $wp_query;
//available vars from url
// $org_name = $wp_query->query_vars['org_name'];
// $team_name = $wp_query->query_vars['team_name'];
// $event_name = $wp_query->query_vars['event_name'];
// $tab_name = $wp_query->query_vars['pg_type'];

get_header();
$g365_ad_info = g365_start_ads( $post->ID );
$print_search = true;

//if we have a team name process it.
if( !empty($wp_query->query_vars['org_name']) ) :
	//get all org data
	$org_data = g365_get_org_profile( $wp_query->query_vars['org_name'] );
	//if there is data, build the page and turn off the search
	if( !empty($org_data) ) :
		$print_search = false;
    $org_data->location = '';
		if( !empty($org_data->address) ) $org_data->location .= $org_data->address . '<br>';
		if( !empty($org_data->city) && !empty($org_data->state) ) $org_data->location .= $org_data->city . ', ';
		if( !empty($org_data->state) ) $org_data->location .= $org_data->state;
		// if( !empty($org_data->state) && !empty($org_data->country) ) $org_data->location .= ', ';
		// if( !empty($org_data->country) ) $org_data->location .= $org_data->country;

		//img defaults
		$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
		$default_badge_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/g365_default_badge.png';
	?>
<!--   <section id="content" class="grid-x grid-margin-x site-main small-padding-top medium-padding-bottom profile-wrap<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main"> -->
		<?php
		if ( $g365_ad_info['go'] ) {
			echo '<div class="cell small-12">';
			echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
			echo '</div>';
		}
		?>
		<div class="cell small-12 no-padding-top">
			<div id="profile-wrapper" class="grid-x medium-padding-top medium-padding-bottom large-margin-top profile">
                <div id="profile-image" class="cell small-6 medium-3 profile-image-club">
                    <div class="tabs-content">
                        <div class="tabs-panel relative text-center is-active" id="default-profile-img" aria-hidden="false">
                            <div class="profile-image-bg">
                                <img src="<?php echo ( !empty($org_data->profile_img) ) ? (get_site_url() . '/wp-content/uploads/org-logos/' . $org_data->profile_img) : $default_profile_img; ?>" alt="<?php echo ( !empty($org_data->profile_img) ) ? 'The official logo of ' . $org_data->name : 'Placeholder image for ' . $org_data->name; ?>" />
                            </div>
                             <div class="small-margin-bottom hide-for-small-only">
                                    <h1 class="profile-name verified-title large-margin-bottom" style="line-height: 0.6;">
                                        <?php echo ( !empty($org_data->abbreviation) ) ? ucwords(strtolower($org_data->abbreviation)) . ' <small>' . ucwords(strtolower($org_data->name)) . '</small>' : ucwords(strtolower($org_data->name)); ?>
                                    </h1>
                                    
                                    <div class="club-profile-data--new white-text">
                                             <div class="grid-y">
                                              <p class="text-center"><?php echo ( empty($org_data->location) ) ? '' : $org_data->city .', ' . $org_data->state; ?></p>
                                            </div>
                                             <div class="grid-y">
                                                <p class="text-center" class="subtitle">Director: <?php echo ( empty($org_data->director_first) ) ? '' : $org_data->director_first; ?> <?php echo ( empty($org_data->director_last) ) ? '' : $org_data->director_last; ?> </p>
                                             </div>
                                            <div class="grid-y text-center small-margin-bottom">
                                              <p style="margin-bottom: 10px !important"> <?php echo ( empty($org_data->link) ) ? '' : '<a href="' . $org_data->link . '" target="_blank">' . parse_url($org_data->link)['host'] . '</a>'; ?></p>
                                              <?php echo ( empty($org_data->social) ) ? '' : g365_build_social_block(json_decode($org_data->social)); ?>
                                            </div>

                                     </div>
                               
                            </div>
                        </div>
                    </div>
                 
                </div>
                <div id="profile-name-mobile" class="cell small-6 show-for-small-only">
                        <h1 class="profile-name verified-title no-margin-bottom club-name-mobile">
                            <?php echo ( !empty($org_data->abbreviation) ) ? $org_data->abbreviation . ' <small>' . $org_data->name . '</small>' : $org_data->name; ?>
                        </h1>
                        <!-- <hr id="profile-title-divider" class="profile-divider small-margin-top small-margin-bottom" /> -->
                    </div>
                <div id="profile-name" class="cell small-12 medium-9  profile-info--club">
                  
                  <?php
                  $program_years = cj_get_club_lifetime_events($org_data->id, 1); // only need the org id to get all lifetime events
                  $championship_awards = cj_championship_award_get_all('org_champ', $org_data->id); //to get all championships won. still loop through to get full count
                  $champCounter = 0;
                  foreach($championship_awards as $championship_award):
                  $champcounter++;
                  endforeach;
                  $player_data = cj_g365_get_award(null, $org_data->id, null, 2); //get all ind awards. still loop through to get total amount.
                  $indAwa = 0;
                  foreach($player_data->awards as $count => $player_datas):
                  $indAwa++;
                  endforeach;
                  ?>
                    
                   <div id="profile-info" class="cell small-12 medium-5">
                     
                     <div class="ticker-container ticker-container--player ">
                        <a href="<?php echo get_site_url() ."/club/". $org_data->nickname ."/stats/#lifetime_events"; ?>" class="pl-statistics-div">
                          <div class="ticker">
                             <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2023/08/events-played-icon.png" class="ticker__icon hide-for-small-only" alt="Events Played">
                             <p class="ticker__number"><?php echo sizeof($program_years);  ?></p>
                             <small>Lifetime<span class="block">Events</span></small>
                          </div>
                        </a>
                        <a href="<?php echo get_site_url() ."/club/". $org_data->nickname ."/awards/#trophies_won"; ?>" class="pl-statistics-div">
                           <div class="ticker">
                             <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2023/08/awards-earned-icon.png" class="ticker__icon hide-for-small-only" alt="Awards Earned">
                              <p class="ticker__number" id="award_earned"><?php echo $champcounter;  ?></p>
                              <small>Trophies<span class="block">Won</span></small>
                           </div>
                        </a>
                        <a href="<?php echo get_site_url() ."/club/". $org_data->nickname ."/awards/#indiv_awards"; ?>" class="pl-statistics-div">
                          <div class="ticker">
                             <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2023/08/badges-earned-icon.png" class="ticker__icon hide-for-small-only" alt="Badges Earned">
                              <p class="ticker__number"><?php echo $indAwa;  ?></p>
                              <small>Individual<span class="block">Awards</span></small>
                           </div>
                        </a>
                      </div>
                     
                     
                      
                      <table class="unstriped profile-data tiny-margin-bottom hide">
                          <tbody>
                              <tr><td><strong>Director:</strong></td><td>
                                  <?php echo ( empty($org_data->director_first) ) ? '' : $org_data->director_first; ?> <?php echo ( empty($org_data->director_last) ) ? '' : $org_data->director_last; ?>
                                  <?php //echo ( empty($org_data->phone) ) ? '' : '<br>' . $org_data->phone; ?>
                                  <?php //echo ( empty($org_data->email) ) ? '' : '<br>' . $org_data->email; ?>
                                  </td></tr>
                              <tr><td><strong>Location:</strong></td><td><?php echo ( empty($org_data->location) ) ? '' : $org_data->location; ?></td></tr>
                              <tr><td><strong>Website:</strong></td><td><?php echo ( empty($org_data->link) ) ? '' : '<a href="' . $org_data->link . '" target="_blank">' . parse_url($org_data->link)['host'] . '</a>'; ?></td></tr>
                              <tr><td><strong>Social:</strong></td><td><?php echo ( empty($org_data->social) ) ? '' : g365_build_social_block(json_decode($org_data->social)); ?></td></tr>
                          </tbody>
                        </table>
                    </div>
                    <hr id="profile-title-divider " class="profile-divider small-margin-top small-margin-bottom" />
                    <h2 class="text-center no-margin-bottom large-margin-top hide"> <?php echo ( !empty($org_data->abbreviation) ) ? $org_data->abbreviation : $org_data->name; ?> by the Numbers</h2>
                    
                      
                           <?php 
        //                    Semi-working version with type 3 date format
                              $year = date("Y-m-d");
//                               echo $year . ' ';
                              $select_year = g365_date_format($year, 7);
//                               echo 'select ' . $select_year. ' ';
                              $club_team_stat_lists = cj_g365_club_team_stat($event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 1); 
//                               echo ' club ';
//                               print_r($club_team_stat_lists);
//                               echo ' ';
                              $club_team_graph = g365_program_graph($club_team_stat_lists, 'game_result_label');
//                               echo $club_team_graph . ' ';
                              $wins = $club_team_graph['win'];
//                               echo 'win ' . $wins . ' ';
                              $losses = $club_team_graph['loss'];
//                               echo 'loss ' . $losses . ' ';
                              $total = $wins + $losses;
//                               echo 'total ' . $total . ' ';

        //                       only render sections if there are games played
                              if($total != 0):
                                  $winPercentage = ($wins * 100 / $total);
                                  $lossPercentage = ($losses * 100 / $total);
                  
                              if($winPercentage > 50) {
                                $percentShow = '';
                              } else {
                                $percentShow = 'hide';
                              };
                              ?>
                               <h3 class="text-center cell no-margin-bottom medium-margin-top linear-gray <?php echo $percentShow?>" >Current Season Record</h3>
                               <div class="win-loss-contain <?php echo $percentShow?>">
                                 <div class="club-win-loss-bar small-margin-top">
                                    <div class="club-win-bar" style="width:<?php echo $winPercentage?>%;" >
  <!--                                     <p class="club-win-bar__wins"><?php echo $wins?> Wins</p> -->
  <!--                                     <p class="club-win-bar__winsPercent"><?php echo round($winPercentage)?> % Games Won</p> -->
                                    </div>
                                    <p class="club-win-bar__wins linear-gray"><?php echo $wins .' W'?></p>
                                  </div>
                                  <div class="club-win-loss-bar ">
                                    <div class="club-loss-bar" style="width:<?php echo $lossPercentage?>%;">
  <!--                                     <p class="club-loss-bar__losses"><?php echo $losses?> Losses</p> -->
                                    </div>
                                   <p class="club-win-bar__wins linear-gray"><?php echo $losses . ' L'?></p>
                                  </div>
                               </div>
                    
                           <?php endif; ?>
                    <div class="grid-x grid-margin-x">
                     
                       
                        <div id="profile-video" class="cell small-12 medium-7 small-margin-top">
                        <?php //start with the main profile, add the mixtapes and then see if we have anything to display
                        if( !empty($org_data->videos) ) {
                        $org_data->videos = json_decode($org_data->videos);
                        if( count($org_data->videos) == 1 ) { ?>
                            <div class="responsive-embed widescreen">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $org_data->video[0]; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
                            </div>
                        <?php } else {
                            foreach( $org_data->videos as $dex => $handle ) {
                            if($dex === 0) : ?>
                                <div class="responsive-embed widescreen">
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $handle; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
                                </div>
                            <?php else : ?>
                                <div class="video-thumb">
                                <a href="https://www.youtube.com/watch?v=<?php echo $handle; ?>" target="_blank"><img src="http://img.youtube.com/vi/<?php echo $handle; ?>/0.jpg" /></a>
                                </div>
                            <?php endif;
                            }
                        }
                            } ?>
                            </div>
                    </div>
                </div>
				<?php
        $org_rosters = g365_get_rosters(array('org_id' => $org_data->id, 'event_id' => 0), false, true);

        ?>
        <!-- navbar -->
  <ul class="pl_profile_ul small-up-4 medium-up-4 text-center"><?php //print_r($wp_query->query_vars); ?>
    <li class="tabs-title cell<?php echo ( empty($wp_query->query_vars['pg_type']) || strtolower($wp_query->query_vars['pg_type']) === 'home' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/club/<?php echo $org_data->nickname; ?>" class="profile-title profile__nav--item block"<?php echo ( empty($wp_query->query_vars['pg_type']) || strtolower($wp_query->query_vars['pg_type']) === 'home' ) ? ' aria-selected="true"': ''; ?>>Program Home</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['team_name']) === 'stats' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/club/<?php echo $org_data->nickname; ?>/stats" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['pg_type']) === 'stats' ) ? ' aria-selected="true"': ''; ?>>Event Results</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['pg_type']) === 'teams' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/club/<?php echo $org_data->nickname; ?>/teams" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['pg_type']) === 'teams' ) ? ' aria-selected="true"': ''; ?>>Team Stats</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['pg_type']) === 'awards' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/club/<?php echo $org_data->nickname; ?>/awards" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['pg_type']) === 'awards' ) ? ' aria-selected="true"': ''; ?>>Trophy Case</a>
    </li>
  </ul>
        <?php if(empty($wp_query->query_vars['team_name'])): ?>
          <?php 
            switch( $wp_query->query_vars['pg_type'] ) {
              case '':
              case 'home':
                  ?>
                    <div class="club-recent-achievements hide">
                        <h2 class="black-text">Recent Championships</h2>
                        <div class="achievement-container">
                         <div class="club-achievement">
                           <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/King-of-the-Coast-Champions.png" alt="">
<!--                             <p class="strong black-text">G365 King of the Coast 2022</p> -->
                           <p class="black-text strong">17U Adidas Gold</p>
                         </div>
                          <div class="club-achievement">
                           <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/Fall-Kickoff-Champions.png" alt="">
<!--                              <p class="strong black-text">G365 Fall Kickoff 2022</p> -->
                           <p class="black-text strong">17U Adidas Gold</p>
                         </div>
                          <div class="club-achievement">
                           <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/On-the-Edge-Champions.png" alt="">
<!--                            <p class="strong black-text">G365 On the Edge 2022</p> -->
                           <p class="black-text strong">17U Adidas Gold</p>
                         </div>
                          <div class="club-achievement">
                           <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/Invitational-Champions.png" alt="">
<!--                            <p class="strong black-text">G365 Invitational 2022</p> -->
                           <p class="black-text strong">13U Blue</p>
                         </div>
                          <div class="club-achievement">
                           <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/The-Launch-Champions.png" alt="">
<!--                            <p class="strong black-text">G365 The Launch 2022</p> -->
                           <p class="black-text strong">13U Blue</p>
                         </div>
                          <div class="club-achievement">
                           <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/Above-the-Rim-Champions.png" alt="">
<!--                            <p class="strong black-text">G365 Above the Rim 2022</p> -->
                           <p class="black-text strong">13U Blue</p>
                         </div>
                        </div>
                    </div>
        <?php
                echo 
                  year_dd_opt('most_recent_event')[0];
                 g365_dir_render('club-profile', 'club-award', $player_id, $arg = null);
                g365_dir_render('club-profile', 'team-ranking', $player_id, $arg = array($org_data->id, $org_data->name));?>
                <?php break;
              case 'stats':
                g365_dir_render('club-profile','stat', $player_id, $arg = null);
                break;
              case 'awards':
                echo year_dd_opt('most_recent_event')[0];
                g365_dir_render('club-profile','club-award', $player_id, $arg = null);
                g365_dir_render('club-profile','team-ranking', $player_id, $arg = array($org_data->id, $org_data->name)); 
                break;
              case 'teams':
                g365_dir_render('club-profile','team', $player_id, $arg = array($org_data));
//                 g365_dir_render('club-profile','team', $player_id, $arg = array($org_rosters[0], $org_data));
                break; 
            } else: g365_dir_render('club-profile','team-statistic', $player_id, $arg = null); endif; ?>
      </div>
    </div>
<!--   </section> -->
<?php	else :
		$g365_club_error = '<h3>Sorry, no data found, please use the search below.</h3>';
	endif;
endif;
// else process the club name var that we have
if( $print_search !== false ) :
?>
<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
        // if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after']; OLD
        
        //Below used for ad banner placement using WP Frontend
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

        endif;
		?>

		<h1 class="entry-title">Team Directory</h1>
		<?php echo ( empty($g365_club_error) ) ? '' : $g365_club_error; ?>
		<div class="relative">
			<span class="search-mag fi-magnifying-glass"></span>
			<input type="text" class='search-hero g365_livesearch_input' data-g365_type="club_profiles" placeholder="Enter Team Name" autocomplete="off" autofocus>
		</div>
		<!-- <div class="xlarge-margin-top small-margin-bottom small-padding gset no-border">
			<h2 class="entry-title">Club Spotlight</h2>
		</div> -->
      <div class="club-team-banner-container">
        <a id="passport-banner" href="https://grassroots365.com/product/ogp-fall-kickoff/">
          <img class="banner-new-img" src="https://sportspassports.com/wp-content/uploads/2024/09/HC-Banner.jpg">
        </a>
      </div>
            <h2 class="text-center small-margin-top">Featured Teams</h2>
            <?php g365_dir_render('home-page','team-spotlight', '', $arg = null); ?>
		</div>
	</div>
</section>
<?php endif;
get_footer(); ?>