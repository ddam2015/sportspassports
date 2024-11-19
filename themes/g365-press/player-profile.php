<?php
/**
 * Template Name: Player Profile
 */

//load variables
global $wp_query; 
//available vars from url
// $pl_id = $wp_query->query_vars['pl_id'];
// $pl_pg = $wp_query->query_vars['pl_pg'];
// $pl_tp = $wp_query->query_vars['pl_tp'];

get_header();
$g365_ad_info = g365_start_ads( $post->ID );
$print_search = true;
// print_r($wp_query->query_vars);
//if we have a player, process it, otherwise show the search page
if( !empty($wp_query->query_vars['pl_id']) ) : 
	//get player data
	$player_data = g365_get_profile( $wp_query->query_vars['pl_id'], false, 0 );
//get the rankings info

global $wp_query_rankings;
	//pull vars if we have them
	$watchlist_set = ( empty($wp_query_rankings->query_vars['wt_id']) ? 55 : $wp_query_rankings->query_vars['wt_id'] );
	$watchlist_date = ( empty($wp_query_rankings->query_vars['wt_tp']) ? null : explode('_', $wp_query_rankings->query_vars['wt_tp']) );
	if( is_array($watchlist_date) && !empty($watchlist_date[0]) ) {
		$watchlist_date = array(
			'min-limit' => $watchlist_date[0],
			'max-limit' => (empty($watchlist_date[1]) ? $watchlist_date[0] : $watchlist_date[1]),
//       'player_id' => $wp_query->query_vars['pl_id']
		);
	}

//   $watchlist_data = g365_build_watchlist($watchlist_set, $watchlist_date);
//   echo '<pre class="">';
// 		print_r($watchlist_date);
// 	echo '</pre>';
///////////////////////////////////////////
	if( !empty($player_data) && is_object($player_data) ) :
		$print_search = false;
    $event_types = [];

		//convert some data for output
		if( !empty($player_data->height_ft) ) $player_data->height = $player_data->height_ft . "'";
		if( !empty($player_data->height_ft) && !empty($player_data->height_in) ) $player_data->height .= ' ' . $player_data->height_in . '"';
		if( !empty($player_data->state) ) $player_data->hometown = $player_data->state;
		if( !empty($player_data->state) && !empty($player_data->city) ) $player_data->hometown = $player_data->city . ', ' . $player_data->hometown;
		if( !empty($player_data->stats) ) {
      foreach($player_data->stats as $ev_id => $ev_data) $event_types[] = $ev_data->event_type;
      $event_types = array_unique($event_types);
    }
    //set verification data
    $ver_level = '';
    if( ($player_data->verified == 2 || $player_data->verified == 3) && !empty($player_data->birthday) ) {
      $ver_level = g365_age_level( $player_data->birthday );
    }
		//img defaults
		$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
		$default_badge_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/Passport-P-2023.png';
    
// 		echo '<pre class="">';
// 		print_r(g365_player_unlock_status($player_data->id));
// 		echo '</pre>';

	?>
  <div id="dialong_div"></div>
  <div id="dialong_result_box_div"></div>
  <section id="content" class="grid-x grid-margin-x site-main small-padding-top medium-padding-bottom profile-wrap<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
    <?php
		if ( $g365_ad_info['go'] ) {
			echo '<div class="cell small-12">';
			echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
			echo '</div>';
		}
		?>
      <div class="">

      </div>
      <div class="cell small-12 no-padding-top">
        <div id="profile-wrapper" class="grid-x  medium-padding-bottom profile">
          <!--  			<div id="profile-wrapper"> -->
          <div class="grid-x small-padding-top medium-margin-bottom large-12 profile">
            <div id="profile-name-mobile" class="cell small-12 show-for-small-only">
              <div class="grid-x grid-margin-x align-middle">
                <h1 class="profile-name verified-title no-margin-bottom cell auto">
                  <?php echo $player_data->name; ?>
                </h1>

                <?php if( $player_data->verified !== null && $player_data->verified !== 'null' && is_numeric($player_data->verified) ) switch( intval($player_data->verified) ) {
              case 1:
            ?>

                <div id="verification-badge" class="cell shrink verification-processing--mobile"><h4>Verification Processing</h4></div>

                <?php
              case 2:
              case 3:
              case 4:
?>

                  <!-- Mobile verification badge -->
                  <div id="verification-badge" class="cell cute text-left">
                    <?php //<img src="< ?php echo get_template_directory_uri(); ? >/assets/verified.png" /> ?>
                    <!--               <span class="verification-title"><i class="fi-check"></i>Verified to play</span> -->
                    <div class="grid-x text-center align-middle <?php echo ( $player_data->verified == 2 && !empty($player_data->birthday) && !empty($player_data->grad_year) ) ? 'small-up-4' : 'small-up-1'; ?>">
                      <?php if( !empty($ver_level) ) { ?>
                      <div class="verified-tag verified-age cell">
                      <span class="cute-title">age<div class="help-tip"></div></span>
                      <p id="verAgeMobile" style="line-height: 1; margin-bottom: 0;"><?php echo $ver_level ?></p>
                        <div class="verified-age__tooltip small-padding" id="ageTooltip">

                          <?php
                        $current_month = (int)date('m');
                        $verified_year = 0;
                        if($current_month < 9) {
//                           check if 15th of august
                          $verified_year = (int)date('Y');
                        } else $verified_year = (int)date('Y') + 1;
                      ?>
                            <strong>8U</strong>
                            <p>An athlete can be no older than 8 on August 15, <?php echo $verified_year;?></p>
                            <p>Birthday Range: August 16, <?php echo $verified_year - 9;?> - Present</p>

                            <strong>9U</strong>
                            <p>An athlete can be no older than 9 on August 15, <?php echo $verified_year;?></p>
                            <p>Birthday Range: August 16, <?php echo $verified_year - 10;?> - Present</p>

                            <strong>10U</strong>
                            <p>An athlete can be no older than 10 on August 15, <?php echo $verified_year;?></p>
                            <p>Birthday Range: August 16, <?php echo $verified_year - 11;?> - Present</p>

                            <strong>11U</strong>
                            <p>An athlete can be no older than 11 on August 15, <?php echo $verified_year;?></p>
                            <p> Birthday Range: August 16, <?php echo $verified_year - 12;?> - Present</p>

                            <strong>12U</strong>
                            <p>An athlete can be no older than 12 on August 15, <?php echo $verified_year;?></p>
                            <p>Birthday Range: August 16, <?php echo $verified_year - 13;?> - Present</p>

                            <strong>13U</strong>
                            <p>An athlete can be no older than 13 on August 15, <?php echo $verified_year;?></p>
                            <p>Birthday Range: August 16, <?php echo $verified_year - 14;?> - Present</p>

                            <strong>14U</strong>
                            <p>An athlete can be no older than 14 on August 15, <?php echo $verified_year;?></p>
                            <p>Birthday Range: August 16, <?php echo $verified_year - 15;?> - Present</p>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if( ($player_data->verified == 2 || $player_data->verified == 4) && !empty($player_data->grad_year) ) { ?>
                      <div class="verified-tag verified-grade cell">
                        <span class="cute-title">grade<div class="help-tip"></div></span>
                        <p id="verGradeMobile" style="line-height: 1; margin-bottom: 0;"><?php echo g365_class_to_grade($player_data->grad_year, true); ?></p>
                        <div class="verified-grade__tooltip small-padding" id="gradeTooltip">
                          <strong>2nd Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 9;?> BUT is in the 2nd grade, that player is eligible for 8U as an exception</p>

                          <strong>3rd Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 10;?> BUT is in the 3rd grade, that player is eligible for 9U as an exception</p>

                          <strong>4th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 11;?> BUT is in the 4th grade, that player is eligible for 10U as an exception</p>

                          <strong>5th Grade</strong>
                          <p> If a player was born before 8/15/<?php echo $verified_year - 12;?> BUT is in the 5th grade, that player is eligible for 11U as an exception</p>

                          <strong>6th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 13;?> BUT is in the 6th grade, that player is eligible for 12U as an exception</p>

                          <strong>7th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 14;?> BUT is in the 7th grade, that player is eligible for 13U as an exception</p>

                          <strong>8th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 15;?> BUT is in the 8th grade, that player is eligible for 14U as an exception</p>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php
              }
            ?>

              </div>
              <hr id="profile-title-divider" class="hide profile-divider small-margin-top small-margin-bottom" />
            </div>
            <div id="profile-image" class="cell small-6 medium-3">
              <!--           <div class="profile-image-bg passport_brder">
           <a href="https://grassroots365.com/product/passport-annual/"><div class="passport_ribbon"></div></a>
            <img class="profile-image__player" src="<?php
            //if we have don't have a general profile img then get one
            if( empty( $player_data->profile_img ) ) {
              if( !empty($player_data->stats) ) {
                $profile_img_fail = true;
                foreach( $player_data->stats as $dex => $stat ) {
                  if( !empty($stat->profile_img) ) {
                    echo $stat->profile_img;
                    $profile_img_fail = false;
                    break;
                  }
                }
                if( $profile_img_fail ) echo $default_profile_img;
              } else {
                echo $default_profile_img;
              }
            } else {
              echo site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img;
            }
            ?>" />
          </div> -->
              <!--  new profile         <div class="profile-card-bg">
            <div class="profile-clip-container relative">
              <img class="profile-clip" src="<?php
                //if we have don't have a general profile img then get one
                if( empty( $player_data->profile_img ) ) {
                  if( !empty($player_data->stats) ) {
                    $profile_img_fail = true;
                    foreach( $player_data->stats as $dex => $stat ) {
                      if( !empty($stat->profile_img) ) {
                        echo $stat->profile_img;
                        $profile_img_fail = false;
                        break;
                      }
                    }
                    if( $profile_img_fail ) echo $default_profile_img;
                  } else {
                    echo $default_profile_img;
                  }
                } else {
                  echo site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img;
                }
                ?>" />
              <div class="profile-card-details">
                <div class="profile-card-info">
                  <p><?php echo $player_data->position ?></p>
                  <p>|</p>
                  <p><?php echo $player_data->club_abb ?></p>
                </div>
                <h2><?php echo $player_data->name ?></h2>
                <h2 class="profile--larger"><?php $player_data->last_name ?></h2> 
              </div>
            </div>
          </div> -->

              <div class="profile-image-bg">
                <!--            <a href="https://grassroots365.com/product/passport-annual/"><div class="passport_ribbon"></div></a> -->
                <img class="profile-image__player" src="<?php
            //if we have don't have a general profile img then get one
            if( empty( $player_data->profile_img ) ) {
              if( !empty($player_data->stats) ) {
                $profile_img_fail = true;
                foreach( $player_data->stats as $dex => $stat ) {
                  if( !empty($stat->profile_img) ) {
                    echo $stat->profile_img;
                    $profile_img_fail = false;
                    break;
                  }
                }
                if( $profile_img_fail ) echo $default_profile_img;
              } else {
                echo $default_profile_img;
              }
            } else {
//               echo site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img.'?get_fresh='.time();
              echo get_site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img.'?get_fresh='.time();
            }
            ?>" />
              </div>
              <div id="profile-info" class="cell small-6 medium-6">
                <table class="unstriped profile-data small-margin-bottom">
                  <tbody>
                    <tr><th><strong>Class:</strong></th><td><?php echo ( empty($player_data->grad_year) ) ? '' : $player_data->grad_year; ?></td></tr>
                    <tr><th><strong>Height:</strong></th><td><?php echo ( empty($player_data->height) ) ? '' : $player_data->height; ?></td></tr>
                    <tr><th><strong>Hometown:</strong></th><td><?php echo ( empty($player_data->hometown) ) ? '' : $player_data->hometown; ?></td></tr>
                    <tr><th class="profile-club-team"><strong>Club Team:</strong></th><td><?php echo ( empty($player_data->club_name) ) ? '' : '<a href="' . get_site_url() . '/club/' . $player_data->club_url . '">' . (( empty($player_data->club_abb) ) ? $player_data->club_name : $player_data->club_abb) . '</a>'; ?></td></tr>
                    <?php if( !empty( $player_data->position )  ) echo '<tr><th><strong>Position:</strong></th><td>' . $player_data->position . '</td></tr>'; ?>
                    <?php 
//                    echo ( empty($player_data->id) ) ? '' : $player_data->id;  
//                     //we are getting a re-indexed array of only the players to watch. We are taking the latest entry for the link
//                     echo '<pre class="">';
//                         print_r($watchlist_data->player_records[$player_data->id]);
//                     echo '</pre>';
//                     if( !empty($watchlist_data->player_records[$player_data->id]) ) :
//                     $just_watchlist = array_values(array_filter($player_data->awards, function($val){ return $val->award_type == 8; }));
//                       $watchlist_date_start = date("Y-m-d", strtotime($just_watchlist[0]->starttime));
//                       $watchlist_date_end = date("Y-m-d", strtotime($just_watchlist[0]->endtime));
//                       $watchlist_date_url = get_site_url() . '/players-to-watch/' . $just_watchlist[0]->rk_url . '/' . $watchlist_date_start . '_' . $watchlist_date_end;
                  ?>
                    <!--                       <tr><td colspan="2" class="no-padding"><a id="ptwLink" href="https://elitebasketballcircuit.com/players-to-watch/"><img src="https://sportspassports.com/wp-content/uploads/2023/08/PTW-Icon.png" alt="Players to Watch Badge - <?php echo $just_watchlist[0]->award_title; ?>" /></a></td></tr> -->
                    <?php //endif; ?>
                  </tbody>
                </table>

                <!--   PLAYER Card / Game picker Wrapper   -->
                <?php 
                  if( strpos( site_url(), get_site_url() ) !== false or isset($_GET['flag-player-card'])){ 
                ?>
                <script type="text/javascript">
                  var playerId = "<?php echo $player_data->id; ?>";
                </script>
                <div style="display: flex; flex-direction: column; justify-content: center; align-items:center;">
                  <a href="#profilePhoto"><img src="https://sportspassports.com/wp-content/uploads/2023/08/media-link.png"></a>
                  <button class="gp_card"  id="gp_card" onclick="(typeof toggleSelect !== 'undefined') && toggleSelect()">Generate Player Card</button>
                  <select onchange="loadPlayerEvents(this.value)" name="year" id="year" style="margin-top: 13px; display: none; border-radius: 20px;height:2.1rem;">
                    <?php foreach(pl_stat_season_options(array($player_data->id))[1] as $available_stat_year): ?>
                      <option <?php if(isset(pl_stat_season_options(array($player_data->id))[0]) && pl_stat_season_options(array($player_data->id))[0] == $available_stat_year){ echo 'selected= "selected"'; } ?> value="<?php echo $available_stat_year ?>"><?php echo g365_date_format($available_stat_year, 2); ?> Season</option>
                    <?php endforeach; ?>
                   </select>
                  <button onclick="openAvgCard(undefined)" id="avgYear" style="margin-top:-2px; margin-bottom: 10px;padding-top: 8px; padding-left: 6px; display: none; border-radius: 20px;height:2.1rem;width:100%;font-size:1rem;">
                     Season Card
                   </button>
                  <select onchange="openPlayerCard(this.value)" name="event" id="event" style="margin-top:2px;display: none; border-radius: 20px;height:2.1rem; font-size:1.5rem !important; font-family: dharma-gothic-e, sans-serif;">
                   </select>
                  <div id="eventLoader" style="display: none;">
                    <svg style="stroke:white;" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg>
                  </div>
                </div>
                <?php } ?>
            
                <?php if(!empty($game_stats)): ?>
                <ul>
                  <?php
              foreach($game_stats as $game_stat): ?>
                    <li>
                      <!--                 <a onclick="ev_form_submit(this)" id="<?php //echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" href="<?php // echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" class="profile-title block"> -->
                      <?php echo $game_stat->event_name; echo $game_stat->player_nickname; echo $pl_id = $game_stat->player_id; ?>
                      <!--                 </a> -->
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </div>
            </div>
            <div id="profile-name" class="cell small-12 medium-9">
              <div class="hide-for-small-only grid-margin-x grid-x align-middle align-justify">
                <h1 class="profile-name no-margin-bottom">
                  <?php echo ucwords(strtolower($player_data->name)); ?>
                </h1>
                <?php if( $player_data->verified !== null && $player_data->verified !== 'null' && is_numeric($player_data->verified) ) switch( intval($player_data->verified) ) {
              case 1:
            ?>

                <div id="verification-badge" class="cell shrink"><h4>Verification Processing</h4></div>

                <?php
              case 2:
              case 3:
              case 4:
            ?>

                  <div id="verification-badge" class="cell shrink cute text-center">
                    <?php //<img src="< ?php echo get_template_directory_uri(); ? >/assets/verified.png" /> ?>
                    <!--               <span class="verification-title"><i class="fi-check"></i>Verified to play</span> -->
                    <div class="grid-x text-center align-middle <?php echo ( $player_data->verified == 2 && !empty($player_data->birthday) && !empty($player_data->grad_year) ) ? 'small-up-2' : 'small-up-1'; ?>">

                      <?php if( !empty($ver_level) ) { ?>
                      <div class="verified-tag verified-age cell">
                        <div class="help-tip"></div>
                        <p id="verAge" style="line-height: 1; margin-bottom: 0;"><?php echo $ver_level . '<span style="display: inline;position: relative; left: 10px;">/</span>'; ?></p>
                        <div class="verified-age__tooltip small-padding" id="ageTooltip">
                          <strong>8U</strong>
                          <p>An athlete can be no older than 8 on August 15, <?php echo $verified_year;?></p>
                          <p>Birthday Range: August 16, <?php echo $verified_year - 9;?> - Present</p>

                          <strong>9U</strong>
                          <p>An athlete can be no older than 9 on August 15, <?php echo $verified_year;?></p>
                          <p>Birthday Range: August 16, <?php echo $verified_year - 10;?> - Present</p>

                          <strong>10U</strong>
                          <p>An athlete can be no older than 10 on August 15, <?php echo $verified_year;?></p>
                          <p>Birthday Range: August 16, <?php echo $verified_year - 11;?> - Present</p>

                          <strong>11U</strong>
                          <p>An athlete can be no older than 11 on August 15, <?php echo $verified_year;?></p>
                          <p> Birthday Range: August 16, <?php echo $verified_year - 12;?> - Present</p>

                          <strong>12U</strong>
                          <p>An athlete can be no older than 12 on August 15, <?php echo $verified_year;?></p>
                          <p>Birthday Range: August 16, <?php echo $verified_year - 13;?> - Present</p>

                          <strong>13U</strong>
                          <p>An athlete can be no older than 13 on August 15, <?php echo $verified_year;?></p>
                          <p>Birthday Range: August 16, <?php echo $verified_year - 14;?> - Present</p>

                          <strong>14U</strong>
                          <p>An athlete can be no older than 14 on August 15, <?php echo $verified_year;?></p>
                          <p>Birthday Range: August 16, <?php echo $verified_year - 15;?> - Present</p>
                        </div>
                      </div>
                      <?php } ?>


                      <?php if( ($player_data->verified == 2 || $player_data->verified == 4) && !empty($player_data->grad_year) ) { ?>
                      <div class="verified-tag verified-grade cell">
                        <div class="help-tip"></div>
                        <p id="verGrade" style="line-height: 1; margin-bottom: 0;"><?php echo g365_class_to_grade($player_data->grad_year, true); ?></p>
                        <div class="verified-grade__tooltip small-padding" id="gradeTooltip">
                          <strong>2nd Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 9;?> BUT is in the 2nd grade, that player is eligible for 8U as an exception</p>

                          <strong>3rd Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 10;?> BUT is in the 3rd grade, that player is eligible for 9U as an exception</p>

                          <strong>4th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 11;?> BUT is in the 4th grade, that player is eligible for 10U as an exception</p>

                          <strong>5th Grade</strong>
                          <p> If a player was born before 8/15/<?php echo $verified_year - 12;?> BUT is in the 5th grade, that player is eligible for 11U as an exception</p>

                          <strong>6th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 13;?> BUT is in the 6th grade, that player is eligible for 12U as an exception</p>

                          <strong>7th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 14;?> BUT is in the 7th grade, that player is eligible for 13U as an exception</p>

                          <strong>8th Grade</strong>
                          <p>If a player was born before 8/15/<?php echo $verified_year - 15;?> BUT is in the 8th grade, that player is eligible for 14U as an exception</p>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php
              }
            ?>
              </div>
              <hr id="profile-title-divider" class="profile-divider small-margin-top small-margin-bottom" />
              <div class="grid-x grid-margin-x">
                <?php 
              $placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png'; $org_logo = '/wp-content/uploads/org-logos/';
              g365_dir_render('player-profile', 'player-statistics', $player_data->id, ['data_type'=>'player-statistics', 'award_data'=>$player_data->awards, 'player_nickname'=>$player_data->nickname]); 
            ?>
                <div id="profile-stats-overview" class="profile-stats-avg--player">
              <?php //endif;
                if(current_user_can('administrator') || current_user_can('stat_vip') || pl_stat_season_options([$player_data->id])[0] == year_exception_list()){ // Admin view -> Full access
                  g365_dir_render('player-profile', 'career-high', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0])); // Career High Stats
                  g365_dir_render('player-profile', 'admin-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4], '')); // Game Stats
                }
                elseif(!empty(pl_stat_season_options(array($player_data->id))[4])){ // Player -> Season pass
                  g365_dir_render('player-profile', 'career-high', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0]));
                  g365_dir_render('player-profile', 'season-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], $player_data->id, $player_data->nickname, $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4], '')); // Game Stats
                }
                elseif(empty(pl_stat_season_options(array($player_data->id))[4])){
                  if(!empty(pl_stat_season_options(array($player_data->id))[3])){ // By Event exists
                     g365_dir_render('player-profile', 'by-event-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[c0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $placeholder_img, $org_logo));// By event pass
                  }else{
                    g365_dir_render('player-profile', 'init-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo));// Not a passport member
                  }
                }
              ?>
                </div>
                <?php g365_dir_render('player-profile', 'player-statistics', $player_data->id, ['data_type'=>'recent-achievements', 'player_nickname'=>$player_data->nickname]); ?>
              </div>
            </div>
          </div>
          <!-- <div class="profile__homepage-container large-margin-bottom">
             <div class="photo__img-container">
                    <img class="photo__img" src="https://picsum.photos/id/17/200/300" alt="" data-id="1">
            </div>
        </div> -->
          <?php 
        $tournament_events = '';
        if( in_array(1, $event_types) ) :
          //setup vars to process tournament data
          $tournament_games = [];
          $tournament_avg = [];
          $tournament_count = 0;
          //loop through all records and group data by event
          foreach( $player_data->stats as $dex => $stat ) {
            //if it's not the right type of event or the record is disabled, skip it
            if($stat->event_type != 1 || $stat->enabled != 1 || $tournament_count > 6) continue;
            $tournament_games[$stat->event_id][] = $stat;
            $tournament_count++;
          }
//         echo '<pre>';
//         print_r($gm_data);
//         echo '</pre>';
          //once all records are done grouping, loop through those groups to break out the stat data and prep it for averaging
          foreach( $tournament_games as $ev_id => &$gm_data ) {
            //if there isn't more than one record, skip it
            if( count($gm_data) < 2 ) {
              $gm_data[0]->stats = json_decode($gm_data[0]->stats);
              continue;
            }
            $tournament_avg[$ev_id] = [];
            //loop through data for an event, presumably game data
            foreach( $gm_data as $st_id => $stat_data ) {
              //if we don't have stats to average, skip it
              if( empty($stat_data->stats) ) continue;
              //parse the stat
              $stat_data->stats = (is_object($stat_data->stats)) ? $stat_data->stats : json_decode($stat_data->stats);
              //loop through each stat to organise the data for averageing
              foreach( $stat_data->stats as $stat_name => $stat_value) {
                //skip the time log
                if( $stat_name === 'time_log' ) continue;
                $tournament_avg[$ev_id][$stat_name][] = $stat_value; 
              }
            }
            //now that we have data to average, do it
            if( !empty($tournament_avg) ) {
              foreach( $tournament_avg[$ev_id] as $stat_name => &$stat_values ) { 
//                 print_r($tournament_avg[$ev_id]);
                //if there is enough data to make averages
                if(count($stat_values)) {
                  //if this is a time, process the average using time rules
                  $_count = count($stat_values);
                  if( $stat_name === 'time_pl' ) {
//                     for($l=0; $l<count($stat_values); $l++){
//                       $time_to_decimal += g365_time_to_decimal($stat_values[$l]);
//                     }
//                     $stat_values = round($time_to_decimal/$_count, 2);
//                     print_r($stat_values);
                    $total_time = array_reduce($stat_values, function ($c, $v) { return $c + strtotime($v) - strtotime('00:00'); }, 0);
                    $hours = floor($total_time / 3600);
                    $minutes = floor(($total_time % 3600) / 60);
                    $seconds = $total_time % 60;
                    $stat_values =  str_pad($hours, 2, '0', STR_PAD_LEFT)/$_count . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT)/$_count . ":" . str_pad($seconds, 2, '0', STR_PAD_LEFT)/$_count;
//                     $stat_values = g365_time_to_decimal($stat_values)/$_count;
                  } else {
                    //if the data is normal, just do simple averages
//                     $stat_values = array_sum($stat_values)/count($stat_values); 
                    $stat_values = array_sum($stat_values)/$_count; 
//                     var_dump($stat_values);
//                     print_r($_count);
                  }
                } else {
                  //if the data is wrong or missing
                  $stat_values = '-';
                }
              }
              //duplicate and front load the first record then over write the stats to present averages
              array_unshift($tournament_games[$ev_id], (object) array());
              $tournament_games[$ev_id][0]->event = $tournament_games[$ev_id][1]->event;
              $tournament_games[$ev_id][0]->stats = (object) $tournament_avg[$ev_id];
              $tournament_games[$ev_id][0]->game_handle = 'Event Averages';
            }
          }
          $tournament_events = array_keys( $tournament_games ); // print_r($tournament_games);
        
        endif;
        
        
                //first load choose the random tab
//     if(empty($wp_query->query_vars['pl_pg'])){
// //       echo $randompick;
// //       echo $wp_query->query_vars['pl_pg'];
//       $wp_query->query_vars['pl_pg'] = ($randompick == 0 ? 'stats' : ($randompick == 1 ? 'profiles' : ($randompick == 2 ? 'awards' : ($randompick == 3 ? 'photos' : ($randompick == 4 ? 'badges' : '')))));
      
// //       echo $wp_query->query_vars['pl_pg'];
//     }

        ?>
          <!-- navbar -->
          <ul class="pl_profile_ul pl_profile_ul--player">
            <!--           <li class="tabs-title cell<?php echo ( empty($wp_query->query_vars['pl_pg']) || strtolower($wp_query->query_vars['pl_pg']) === 'bio' ) ? ' is-active': ''; ?>">
            <a href="<?php echo get_site_url(); ?>/player/<?php echo $player_data->nickname; ?>" class="profile-title profile__nav--item block"<?php echo ( empty($wp_query->query_vars['pl_pg']) || strtolower($wp_query->query_vars['pl_pg']) === 'bio' ) ? ' aria-selected="true"': ''; ?>>Bio</a>
          </li> -->
            <li class="tabs-title<?php echo ( empty($wp_query->query_vars['pl_pg']) || strtolower($wp_query->query_vars['pl_pg']) === 'stats' ) ? ' is-active': ''; ?>">
              <a href="<?php echo get_site_url(); ?>/player/<?php echo $player_data->nickname; ?>/stats" class="profile-title profile__nav--item block" <?php echo ( empty($wp_query->query_vars['pl_pg']) || strtolower($wp_query->query_vars['pl_pg']) === 'stats' ) ? ' aria-selected="true"': ''; ?>>Stats</a>
            </li>
            <li class="tabs-title<?php echo ( strtolower($wp_query->query_vars['pl_pg']) === 'profiles' ) ? ' is-active': ''; ?>">
              <a href="<?php echo get_site_url(); ?>/player/<?php echo $player_data->nickname; ?>/profiles" class="profile-title profile__nav--item block" <?php echo ( strtolower($wp_query->query_vars['pl_pg']) === 'profiles' ) ? ' aria-selected="true"': ''; ?>>Camps</a>
            </li>
            <li class="tabs-title<?php echo ( strtolower($wp_query->query_vars['pl_pg']) === 'awards' ) ? ' is-active': ''; ?>">
              <a href="<?php echo get_site_url(); ?>/player/<?php echo $player_data->nickname; ?>/awards" class="profile-title profile__nav--item block" <?php echo ( strtolower($wp_query->query_vars['pl_pg']) === 'awards' ) ? ' aria-selected="true"': ''; ?>>Awards</a>
            </li>
            <li class="tabs-title<?php echo ( strtolower($wp_query->query_vars['pl_pg']) === 'badges' ) ? ' is-active': ''; ?>">
              <a href="<?php echo get_site_url(); ?>/player/<?php echo $player_data->nickname; ?>/badges" class="profile-title profile__nav--item block" <?php echo ( strtolower($wp_query->query_vars['pl_pg']) === 'badges' ) ? ' aria-selected="true"': ''; ?>>Achievements</a>
            </li>
          </ul><?php
    switch( $wp_query->query_vars['pl_pg'] ) {
      case 'profiles': ?>
            <div id="profile-stats" class="cell small-12 small-margin-top">
              <h2><span class="fi-ticket"></span> Event Profiles</h2>
              <?php if( in_array(2, $event_types) ) : ?>
              <ul class="tabs separate small-padding-bottom small-margin-bottom small-up-2 medium-up-3 text-center grid-x" id="event-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
                <li class="tabs-title cell is-active hide"><a href="#default-profile-img" class="profile-title text-center block">Main Profile</a></li>
                <?php
            foreach( $player_data->stats as $dex => $stat ) : if($stat->event_type != 2) continue; ?>
                  <?php if($stat->game_id == 0):/*if-2*/?>
                  <li class="tabs-title cell<?php echo ( $count === 0 ) ? ' is-active': ''; ?>">
                    <a id="click<?php echo preg_replace('/\s+|\.|-/', '', $stat->event); ?>" href="#events<?php echo preg_replace('/\s+|\.|-/', '', $stat->event); ?>" class="profile-title block">
                      <?php echo $stat->event; ?>
                    </a>
                  </li>
                  <?php endif;/*endif-2*/ ?>
                  <?php $count++; endforeach; ?>
              </ul>
              <div class="tabs-content" id="event-stats-group" data-tabs-content="event-stats">
                <?php foreach( $player_data->stats as $dex => $stat ) : if($stat->event_type != 2) continue;  
                  $check_ss = json_decode($stat->trends, true);
//         echo "<pre>"; print_r($check_ss['ss_event_participated']); echo "</pre>";
                ?>
                <?php //if($stat->game_id == 0): /*if-2*/?>
                <?php if($stat->game_id == 0): /*if-2*/?>
                <div class="tabs-panel gray-bg" id="events<?php echo preg_replace('/\s+|\.|-/', '', $stat->event); ?>">
                  <div class="cell small-12">
                    <div class="info">
                      <div class="table-scroll">
                        <!--                             Display physical stats -->
                        <table class="text-center no-margin-bottom">
                          <tbody class="stats__table--player">
                            <tr>
                              <?php foreach(json_decode($stat->stats) as $stat_title => $stat_value) :
                        switch($stat_title){
                          case 'vert':
                            $stat_title = "Vertical Jump";
                            break;
                          case 'wing':
                            $stat_title = "Wingspan";
                            break;
                          case 'ln_ag':
                            $stat_title = "Lane Agility";
                            break;
                          case 'std_rch':
                            $stat_title = "Reach";
                            break;
                          case 'three_q_sprint':
                            $stat_title = "3/4 Court Sprint";
                            break;
                        }
                        ?>
                              <th><?php echo $stat_title; ?></th>
                              <?php endforeach; ?>
                            </tr>
                            <tr class="color-body">
                              <?php foreach(json_decode($stat->stats) as $stat_title => $stat_value) : ?>
                              <td><?php echo $stat_value; ?></td>
                              <?php endforeach; ?>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="medium-padding info-block">
                    <div class="grid-x grid-margin-x">
                      <div class="cell small-12 medium-shrink medium-order-2">
                        <div class="profile-image-bg relative auto-margin-sides inner-profile small-small-margin-bottom">
                          <img class="profile-image__player" src="<?php echo ( !empty($stat->profile_img) ) ? $stat->profile_img : $default_profile_img; ?>?get_fresh='<?php echo time(); ?>" alt="<?php $player_data->name . ' at ' . $stat->event; ?>" />
                          <?php if( !empty($award_names) && 2 == 3 ) echo '<h5 class="profile_award_label text-center">' . $award_names . '</h5>'; ?>
                          <?php if( !empty($stat->event_logo) ) echo '<img class="profile-event-img" src="' . $stat->event_logo . '" alt="' . $stat->event . ' Logo" />'; ?>
                        </div>
                      </div>
                      <div class="cell small-12 medium-auto">
                        <div class="grid-x grid-margin-x">
                          <div class="cell small-12">
                            <h3 class="small-text-center text-underline"><?php echo $stat->event; ?></h3>
                          </div>
                          <?php if($stat->enabled == 1) :
                         if( !empty($stat->evaluation) ) : ?>
                          <div class="cell small-12">
                            <div class="info">
                              <strong>Evaluation: </strong>
                              <div class="evalBlock">
                                <?php echo $stat->evaluation; ?>
                              </div>
                            </div>
                          </div>
                          <?php endif; ?>
                          <?php if( !empty($stat->strengths) ) : ?>
                          <div class="cell medium-6 small-padding-bottom">
                            <div class="info">
                              <strong>Strengths:</strong>
                              <ul class="skillList strength">
                                <?php foreach(json_decode($stat->strengths) as $item) : ?>
                                <li><?php echo $item; ?></li>
                                <?php endforeach; ?>
                              </ul>
                            </div>
                          </div>
                          <?php endif; ?>
                          <?php if( !empty($stat->weaknesses) ) : ?>
                          <div class="cell medium-6 small-padding-bottom">
                            <div class="info">
                              <strong>Improvement Areas:</strong>
                              <ul class="skillList improve">
                                <?php foreach(json_decode($stat->weaknesses) as $item) : ?>
                                <li><?php echo $item; ?></li>
                                <?php endforeach; ?>
                              </ul>
                            </div>
                          </div>
                          <?php endif; ?>


                          <?php //endif;
                      endif; ?>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
                <?php endforeach;
        else : ?>
                <div>
                  <p>Participate in a <a href="<?php echo get_site_url()?>/calendar/#ebcevents">Elite Basketball Circuit Camp</a> to receive scouting reports!</p>
                </div>
                <?php endif; ?>
                <div class="cell small-12">
                  <div class="event-link large-margin-top">
                    <!--               <a href="<?php echo $stat->event_link; ?>" target="_blank">More info: <?php echo $stat->event; ?></a> -->
                    <!-- 	<a href="<?php echo get_site_url(); ?>/event/<?php echo $stat->event_url; ?>" target="_blank">More info: <?php echo $stat->event; ?></a> -->
                  </div>
                </div>
              </div>
            </div>
            <?php
        break;
      case '':
      case 'stats':
      ?>
              <?php //$player_badges = g365_img_queries('player-badge', ['pl_id'=>$player_data->id]); if(!empty($player_badges)): ?>
              <!--             <div class="modal__confirm--outer badge-modal--outer" style="display: none;">
              <div class="badge-notification grid-y small-padding border-radius align-justify text-center">
                <h2><?php //echo $player_data->name; ?> Achievements</h2>
                <div class="grid-x medium-12 large-12">
                  <div id="profile-awards" class="cell small-12 options-wrapper">
                      <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5">
                        <?php //foreach($player_badges as $player_badge): ?>
                          <div class="cell small-margin-top small-margin-bottom">
                            <img loading="lazy" src="<?php //echo $player_badge->badge_url; ?>">
                          </div>
                        <?php //endforeach; ?>
                    </div>
                  </div>
                </div>
                <div class="grid-x">
                  <button class="button primary cookieBadgeExit large-6" onclick="remove_bdg_modal()">Close</button>
                  <button class="button primary cookieBadgeExit large-6" onclick="remove_bdg_modal()"><a style="color: #fff; text-decoration: none; display: block;" href="<?php echo get_site_url(); ?>/player/<?php echo $player_data->nickname; ?>/badges">View <?php echo $player_data->nickname; ?> Achievements</a></button>
                </div>
              </div>  
            </div> -->
              <?php //endif; ?>
              <div class="social_sharing small-12 medium-12 large-12">
                <div class="small-4 medium-4 large-4">
                  <form method="post" id="game-stat-form" action="./" method="POST" class="grid-x">
                    <div class="small-12 large-3 small-padding-right hi" style="width: 200px">
                      <select onchange="this.form.submit()" name="year" id="year" style="border-radius: 20px;height:2.1rem">
                  <?php foreach(pl_stat_season_options(array($player_data->id))[1] as $available_stat_year): ?>
                    <option <?php if(isset(pl_stat_season_options(array($player_data->id))[0]) && pl_stat_season_options(array($player_data->id))[0] == $available_stat_year){ echo 'selected= "selected"'; } ?> value="<?php echo $available_stat_year ?>"><?php echo g365_date_format($available_stat_year, 2); ?> Season</option>
                  <?php endforeach; ?>
                </select>
                    </div>
                  </form>
                </div>
                <div class="small-4 medium-4 large-4 small-padding-right">
                  <?php echo g365_social_sharing_btn($player_data->nickname); ?>
                </div>
                <div class="small-4 medium-4 large-4 small-padding-right"><?php echo g365_submenu_type(array($wp_query->query_vars['st_type'], $player_data->nickname, pl_stat_season_options()[0]), 3); ?></div>
              </div>
         <?php //endif;
          $placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png'; $org_logo = '/wp-content/uploads/org-logos/';
          if(current_user_can('administrator') || current_user_can('stat_vip') || pl_stat_season_options([$player_data->id])[0] == year_exception_list()){ // Admin view -> Full access
            $arg =  array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4], g365_message()['p_ev_stat']);
            g365_dir_render('player-profile', 'admin-pp-view', $player_data->id, $arg); // Game Stats
          }
          elseif(!empty(pl_stat_season_options(array($player_data->id))[4])){ // Player -> Season pass
            g365_dir_render('player-profile', 'season-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], $player_data->id, $player_data->nickname, $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4])); // Game Stats
          }
          elseif(empty(pl_stat_season_options(array($player_data->id))[4])){
            if(!empty(pl_stat_season_options(array($player_data->id))[3])){ // By Event exists
               g365_dir_render('player-profile', 'by-event-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $placeholder_img, $org_logo));// By event pass
            }else{
              g365_dir_render('player-profile', 'init-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo));// Not a passport member
            }
          }
        ?>
              <ul class="accordion xlarge-margin-top disclaimer--stat" data-accordion data-allow-all-closed="true">
                <li class="accordion-item" data-accordion-item>
                  <a href="#" class="accordion-title">Stat Disclaimer</a>
                  <div class="accordion-content" data-tab-content>
                    <p>We make every effort to provide the most accurate stats possible. However, a number of factors including but not limited to duplicate jerseys and the subjective nature of certain basketball stats, prevent us from 100% accuracy. The
                      stats we provide are intended to be a metric to track progress and reward achievement over the course of a youth basketball career while providing a more robust experience for players and teams. It is not possible to provide this
                      experience without some margin of error. We will do our best to provide the most accurate statistical data possible, but we reserve the right to refuse any request to update statistics.
                    </p>
                  </div>
                </li>
              </ul>
              <?php echo g365_custom_js('badge-cookies');
        break;
      case 'awards': g365_dir_render('player-profile', 'award', $player_data->id, [$player_data->awards]);
        break;
      case 'photos': g365_dir_render('player-profile', 'player-photo', $player_data->id, $arg = []);
        break;
      case 'badges': g365_dir_render('badges', 'player-badges', $player_data->id, $arg = []);
        break;
    }
        ?>
        </div>
      </div>
      <div class="profile__media--photo large-margin-bottom" id="profilePhoto">
        <hr style="border-bottom: 3px solid #333">
        <h3>Photo Gallery</h3>
        <div class="profile__media--photo-container">
          <?php g365_dir_render('player-profile', 'player-photo', $player_data->id, $arg = []); ?>

        </div>
      </div>
      <div class="profile__media--video" id="profileVideo">
        <h3>Videos</h3>
        <?php
              if(!empty($player_data->videos)){ $player_videos = $player_data->videos; }else{ $player_videos = ''; }
              if(!empty($player_data->video)){ $player_video = $player_data->video; }else{ $player_video = ''; }
              g365_dir_render('player-profile', 'profile-video', $player_data->id, ['youtube_videos'=>$player_videos, 'youtube_video'=>$player_video, 'stat_data'=>$player_data->stats]); 
            ?>
      </div>
  </section>
  <script>
    <?php //add the js to power the stat jumping and video thumbs
	if( !empty($wp_query->query_vars['pl_tp']) ) : $targ = preg_replace('/\s+|\.|-/', '', $wp_query->query_vars['pl_tp']); ?>
   function g365_nav_click(targ) { $('#click' + targ).click(); }
    if (typeof window.g365_func_wrapper !== 'object') window.g365_func_wrapper = {sess: [], found: [], end: []};
    window.g365_func_wrapper.found[window.g365_func_wrapper.found.length] = {name: g365_nav_click, args: ['<?php echo $targ; ?>']};
    <?php endif; ?>
    <?php if( !empty($vid_count) ) : ?>
    function g365_vid_switch() {$('.video-thumb', '#profile-video').click(function() { $('#profile-player').attr('src', $(this).attr('data-direction')); });}
    if (typeof window.g365_func_wrapper !== 'object') var g365_func_wrapper = {sess: [],found: [],end: []};
    window.g365_func_wrapper.found[window.g365_func_wrapper.found.length] = {name: g365_vid_switch,args: []};
    <?php endif; ?>
  </script>
  <?php	else :
		$g365_player_error = '<h3>Sorry, no data found, please use the search below.</h3>';
		if( !empty($player_data) ) $g365_player_error .= '<p>' . $player_data . '</p>';
	endif;
endif;
// else process the club name var that we have
if( $print_search !== false ) :
?>
    <section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
      <div class="cell small-12">
        <?php
        // if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
        
        //Below used for ad banner placement using WP Backend
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

        endif;
		?>
          <h1 class="entry-title">Player Directory</h1>
          <?php echo ( empty($g365_player_error) ) ? '' : $g365_player_error; ?>
          <div class="relative">
            <span class="search-mag fi-magnifying-glass"></span>
            <input type="text" class='search-hero g365_livesearch_input' data-g365_type="player_profiles" placeholder="Enter Player Name" autocomplete="off" autofocus>
          </div>
           <a id="passport-banner" href="https://elitebasketballcircuit.com/calendar/">
            <img class="banner-new-img" src="https://sportspassports.com/wp-content/uploads/2024/08/EBCSlogan.jpg">
           <!-- <div class="banner__container large-margin-top">
              <div class="banner__bg"></div>
              <div class="banner__info">
                <img class="banner__img" src="https://sportspassports.com/wp-content/themes/g365-press/assets/tiny-logos/Passport-2023.png">
                <p class="banner__text hide-for-small-only">Fair Play, Stats, Exposure.<span class="banner__btn emphasis">Unlock Your Passport</span></p>
                <p class="banner__text show-for-small-only">Fair Play, Stats, Exposure.<span class="banner__btn emphasis block">Unlock Your Passport</span></p>
                 <p class="show-for-small-only emphasis banner__btn">get certified today</p> 
              </div>
            </div>-->
          </a>
          <!-- <div class="xlarge-margin-top small-margin-bottom small-padding gset no-border  border-radius">
			<h2 class="entry-title">Player Spotlight</h2>
		</div> -->
          <!-- 		<div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4 text-center profile-feature xlarge-margin-top "> -->
          <?php
// 			$player_spotlights_arr = g365_get_awards_featured();
// 			foreach( $player_spotlights_arr as $dex => $obj ) :
//       $validate_img = g365_player_img_dir($obj->player_url, $obj->event_url, $obj->id);
      ?>
            <!-- 			<div class="cell">
				<div class="callout gfont spotlight__card spotlight__card--players">
                    <div class="spotlight__flex--1">
                        <h5 class="tiny-margin-bottom weight-bold">
                            <a class="spotlight__card--heading" href="<?php echo get_site_url(); ?>/player/<?php echo $obj->player_url; ?>"><?php echo $obj->player; ?></a>
                        </h5>
                        <div class="relative">
                            <a href="<?php echo get_site_url(); ?>/player/<?php echo $obj->player_url; ?>">
                <img class="profile-image__player" src="<?php echo $validate_img; ?>" alt="<?php echo $obj->player; ?> at <?php echo $obj->event; ?>" />
                            </a>
                        </div>
                    </div>
                    <div class="spotlight__flex--2">
                        <?php if( !empty($obj->event_logo) ) echo '<img class="profile-event-img medium-margin-top" src="' . $obj->event_logo . '" alt="' . $obj->event . ' Logo" />'; ?>
                        <?php if( !empty($obj->event_logo) && !empty($obj->event_link) ) echo '<br>'; ?>
                        <?php if( !empty($obj->event_link) ) echo '<a href="' . $obj->event_link . '" target="_blank">' . $obj->event . '</a>'; ?>
                    </div>
				</div>
			</div> -->
            <?php //endforeach; 
			?>
            <!-- 		</div> -->
            <h2 class="text-center small-margin-top">Featured Players</h2>
            <?php g365_dir_render('home-page','player-spotlight', '', $arg = null); ?>
      </div>
    </section>
    <?php endif;
get_footer();