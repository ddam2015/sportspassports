<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wp_query;

$print_search = true;
$event_types = [];

if(isset($_GET['playerId'])){
  $player_id = $_GET['playerId'];
}

if(isset($_GET['eventId'])){
  $event_id = $_GET['eventId'];
}

if(isset($_GET['year'])){
  $_POST['year'] = $_GET['year'];
}

if(!empty(isset($_GET['playerId'])) && !empty(isset($_GET['eventId'])) && !empty(isset($_GET['year']))) {
  $_POST['year'] = $_GET['year']; //todo used for feeding pl_stat_season_options
}

//if we have a player, process it, otherwise show the search page
if(!empty($player_id)) :
  $player_data = g365_get_profile($player_id, false, 0);
//get the rankings info

$placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png'; $org_logo = '/wp-content/uploads/org-logos/';
$arg =  array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4], g365_message()['p_ev_stat']); // Game Stats
if(!empty($arg[3][0])){ $ev_name = (get_event($arg[3][0]))[0]->ev_name; }

$game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]);

// Used for player profile generate dropdowns for the player card button on the left
if(isset($_GET['game-stats-json'])){
  echo json_encode($game_stats);
  die();
}

?>
<div class="download-wrapper">
  <div class="social_sharing-card small-12 medium-12 large-12">
    <div class="small-4 medium-4 large-4 small-padding-right">
      <?php echo g365_social_sharing_btn($player_data->nickname); ?>
    </div> 
  </div>

  
  <button class="download" onclick="window.captureAndDownloadScreenshot()">Download</button>
  <button class="download" onclick="window.captureAndPrint()">Print</button>
  <button class="download" onclick="window.captureAndSendEmail()" id="email">Email</button>
</div>

<div class="profile_popup" id="profile_popup">

  
<?php
global $wp_query_rankings;
	//pull vars if we have them
	$watchlist_set = ( empty($wp_query_rankings->query_vars['wt_id']) ? 55 : $wp_query_rankings->query_vars['wt_id'] );
	$watchlist_date = ( empty($wp_query_rankings->query_vars['wt_tp']) ? null : explode('_', $wp_query_rankings->query_vars['wt_tp']) );
	if( is_array($watchlist_date) && !empty($watchlist_date[0]) ) {
		$watchlist_date = array(
			'min-limit' => $watchlist_date[0],
			'max-limit' => (empty($watchlist_date[1]) ? $watchlist_date[0] : $watchlist_date[1]),
		);
	}

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
		$default_badge_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/g365_default_badge.png';

	?>
	<section id="content" class="grid-x grid-margin-x site-main profile-wrap stat-card season-average-card" role="main">
		<div class="cell small-12 no-padding-top">

      <!--   TOP STATISTICS   -->
      <?php g365_dir_render('player-profile', 'player-statistics', $player_data->id, ['data_type'=>'player-statistics', 'award_data'=>$player_data->awards, 'player_nickname'=>$player_data->nickname]); ?>
			<div id="profile-wrapper" class="grid-x grid-margin-x medium-padding-bottom profile">
        <div class="grid-x small-padding-top medium-margin-bottom small-12 profile">
<!--           <div id="profile-name-mobile" class="cell small-12 show-for-small-only">
            <div class="grid-margin-x grid-x align-middle">
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
<!--                 <div id="verification-badge" class="cell cute text-left hide-for-small-only">
                  <div class="grid-x text-center align-middle <?php echo ( $player_data->verified == 2 && !empty($player_data->birthday) && !empty($player_data->grad_year) ) ? 'small-up-4' : 'small-up-1'; ?>">
                    <?php if( !empty($ver_level) ) { ?>
                    <div class="verified-tag verified-age cell"><span class="cute-title">age<div class="help-tip"></div></span><?php echo $ver_level ?>
                        <div class="verified-age__tooltip small-padding" id="ageTooltip">

                          <?php
                            $current_month = (int)date('m');
                            $verified_year = 0;
                            if($current_month < 9) {
                              $verified_year = (int)date('Y');
                            } else $verified_year = (int)date('Y') + 1;
                          ?>
                          <strong>8U</strong>
                          <p>An athlete can be no older than 8 on August 31, <?php echo $verified_year;?></p>
                          <p>Birthday Range: September 1, <?php echo (int)date('Y') - 9;?> - Present</p>

                          <strong>9U</strong>
                          <p>An athlete can be no older than 9 on August 31, <?php echo $verified_year;?></p>
                          <p>Birthday Range: September 1, <?php echo (int)date('Y') - 10;?> - Present</p>

                          <strong>10U</strong>
                          <p>An athlete can be no older than 10 on August 31, <?php echo $verified_year;?></p>
                          <p>Birthday Range: September 1, <?php echo (int)date('Y') - 11;?> - Present</p>

                          <strong>11U</strong>
                          <p>An athlete can be no older than 11 on August 31, <?php echo $verified_year;?></p>
                          <p> Birthday Range: September 1, <?php echo (int)date('Y') - 12;?> - Present</p>

                          <strong>12U</strong>
                          <p>An athlete can be no older than 12 on August 31, <?php echo $verified_year;?></p>
                          <p>Birthday Range: September 1, <?php echo (int)date('Y') - 13;?> - Present</p>

                          <strong>13U</strong>
                          <p>An athlete can be no older than 13 on August 31, <?php echo $verified_year;?></p>
                          <p>Birthday Range: September 1, <?php echo (int)date('Y') - 14;?> - Present</p>

                          <strong>14U</strong>
                          <p>An athlete can be no older than 14 on August 31, <?php echo $verified_year;?></p>
                          <p>Birthday Range: September 1, <?php echo (int)date('Y') - 15;?> - Present</p>
                        </div>
                    </div> -->
                    <?php } ?>
                    <?php if( ($player_data->verified == 2 || $player_data->verified == 4) && !empty($player_data->grad_year) ) { ?>
<!--                     <div class="verified-tag verified-grade cell"><span class="cute-title">grade<div class="help-tip"></div></span><?php echo g365_class_to_grade($player_data->grad_year, true); ?>
                        <div class="verified-grade__tooltip small-padding" id="gradeTooltip">
                            <strong>2nd Grade</strong>
                                <p>If a player was born before 9/1/<?php echo (int)date('Y') - 9;?> BUT is in the 2nd grade, that player is eligible for 8U as an exception</p>

                                <strong>3rd Grade</strong>
                                <p>If a player was born before 9/1/<?php echo (int)date('Y') - 10;?> BUT is in the 3rd grade, that player is eligible for 9U as an exception</p>

                                <strong>4th Grade</strong>
                                <p>If a player was born before 9/1/<?php echo (int)date('Y') - 11;?> BUT is in the 4th grade, that player is eligible for 10U as an exception</p>

                                <strong>5th Grade</strong>
                                <p> If a player was born before 9/1/<?php echo (int)date('Y') - 12;?> BUT is in the 5th grade, that player is eligible for 11U as an exception</p>

                                <strong>6th Grade</strong>
                                <p>If a player was born before 9/1/<?php echo (int)date('Y') - 13;?> BUT is in the 6th grade, that player is eligible for 12U as an exception</p>

                                <strong>7th Grade</strong>
                                <p>If a player was born before 9/1/<?php echo (int)date('Y') - 14;?> BUT is in the 7th grade, that player is eligible for 13U as an exception</p>

                                <strong>8th Grade</strong>
                                <p>If a player was born before 9/1/<?php echo (int)date('Y') - 15;?> BUT is in the 8th grade, that player is eligible for 14U as an exception</p>
                        </div>
                    </div> -->
                    <?php } ?>
<!--                   </div>
                </div> -->
                <?php
                  }
                ?>
            <!--</div>          
            <hr id="profile-title-divider" class="hide profile-divider small-margin-top small-margin-bottom" />
          </div>
           -->
          
          
          <!--     TOP LEFT    -->
          <div id="profile-image-card" class="cell small-4 medium-3">
            <div class="profile-image-bg">
              <h1 class="profile-name no-margin-bottom">
                <?php echo ucwords(strtolower($player_data->name)); ?>
              </h1>
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
                echo get_site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img.'?get_fresh='.time();
              }
              ?>" />
            </div>
             <div id="profile-info" class="cell small-6 medium-8">
              <table class="unstriped profile-data small-margin-bottom">
                <tbody>
                  <tr><th><strong>Class:</strong></th><td><?php echo ( empty($player_data->grad_year) ) ? '' : $player_data->grad_year; ?></td></tr>
                  <tr><th><strong>Height:</strong></th><td><?php echo ( empty($player_data->height) ) ? '' : $player_data->height; ?></td></tr>
                  <tr><th><strong>Hometown:</strong></th><td><?php echo ( empty($player_data->hometown) ) ? '' : $player_data->hometown; ?></td></tr>
<!--                   <tr><th class="profile-club-team"><strong>Club Team:</strong></th><td><?php echo ( empty($player_data->club_name) ) ? '' : '<a href="' . get_site_url() . '/club/' . $player_data->club_url . '">' . (( empty($player_data->club_abb) ) ? $player_data->club_name : $player_data->club_abb) . '</a>'; ?></td></tr> -->
                  <?php if( !empty( $player_data->position_name )  ) echo '<tr><th><strong>Position:</strong></th><td>' . $player_data->position_name . '</td></tr>'; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!--     TOP RIGHT   -->
          <div class="cell small-8 grid-x align-center">
            <div class="grid-x grid-margin-x">
            
            <div id="profile-stats-overview" class="profile-stats-avg--player">
              <?php 
              $placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png'; 
              $org_logo = '/wp-content/uploads/org-logos/';
         
                if(current_user_can('administrator') || current_user_can('stat_vip') || pl_stat_season_options([$player_data->id])[0] == year_exception_list()){ // Admin view -> Full access
                  g365_dir_render('player-profile', 'career-high-abrv', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0])); // Career High Stats
                  g365_dir_render('player-profile', 'admin-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4], '')); // Game Stats
                }
                elseif(!empty(pl_stat_season_options(array($player_data->id))[4])){ // Player -> Season pass
                  g365_dir_render('player-profile', 'career-high', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0]));
                  g365_dir_render('player-profile', 'season-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0],
                                                                                              pl_stat_season_options(array($player_data->id))[2],
                                                                                              $player_data->id, 
                                                                                              $player_data->nickname,
                                                                                              $placeholder_img, $org_logo, pl_stat_season_options([$player_data->id])[4], '')); // Game Stats
                }
                elseif(empty(pl_stat_season_options(array($player_data->id))[4])){
                  if(!empty(pl_stat_season_options(array($player_data->id))[3])){ // By Event exists
                     g365_dir_render('player-profile', 'by-event-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[c0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $placeholder_img, $org_logo));// By event pass
                  }else{
                    g365_dir_render('player-profile', 'init-pp-view', $player_data->id, array(1, pl_stat_season_options([$player_data->id])[0], pl_stat_season_options(array($player_data->id))[2], pl_stat_season_options(array($player_data->id))[3], $player_data->nickname, $placeholder_img, $org_logo));// Not a passport member
                  }
                }else {}
              ?>
            </div>
            </div>
              <div id="right-player-score" class="cell small-12 medium-12" hidden>
                <div class="grid-x">
                  <div id="profile-name" class="cell small-12 medium-12">
                    <div class="grid-x align-middle align-justify"> 
                     <div id="profile-games" class="cell small-12">
                       <!-- <ul>--><?php
                       if(!empty($game_stats)): 
                        foreach($game_stats as $game_stat): ?>
          <!--                     <a onclick="ev_form_submit(this)" id="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" href="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" class="profile-title block"> -->
          <!--                       <?php echo $game_stat->event_name; ?>
                              </a>
                            </li>      
                            <?php $nickname = $game_stat->player_nickname; $pl_id = $game_stat->player_id; endforeach; ?>
                          </ul> -->
                            <div class="tabs-content small-12 allow-overflow" id="season-stats-group" data-tabs-content="season-stats">
                              <?php //if(!empty($event_id) && ($event_id == $game_stat->event_id)):/*if-1*/ ?>
                              <?php $simp_game_stats = game_stat_filter_sh($player_id, $event_id, $is_only_event = true, $arg[1], $arg[0]); foreach($simp_game_stats as $game_stat): $event_game_avg = avg_game_stat($player_id, $event_id); /*foreach-1*/ ?>
                              <div class="tabs-panel is-active" id="<?php echo g365_url_linkage(array('', '', $game_stat->event_name, true, $arg[1], $stat_type), 'tc-tournament-stat') ?>">
                                <div class="info-block">
                                  <div class="grid-x grid-margin-x">
                                    <div class="cell small-12">
                                      <div id="profile-stats-avg" class="cell small-12">
                                        <div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
                                          <div style="flex: 0 1 100%;" class="text-center" id="spp_event_log"><img class="width-50-200" src="<?php echo $game_stat->event_logo; ?>"></div>
                                          <div style="flex: 0 1 100%;" class="text-center" id="spp_event_date"><h4></h4><h4><?php echo g365_build_dates($game_stat->event_date, 2) ?></h4></div>
                                          <div style="flex: 0 1 100%;" class="text-center" id="spp_event_location"><h4><?php echo str_replace('|', ' | ', $game_stat->event_location); ?></h4></div>
                                          <div id="player-container"></div>
                                        </div>
                                        <table class="table-stats-player text-center no-margin-bottom">
                                          <tbody class="stats__table--player">
                                            <tr>
                                              <th class="averages">PPG</th>
                                              <th class="averages">RPG</th>
                                              <th class="averages">APG</th>
                                              <th class="averages">BPG</th>
                                              <th class="averages">SPG</th>
                                              <th class="averages">3PT</th>
                                            </tr>
                                            <tr class="color-body emphasis">              
                                              <td class="averages"><?php echo $event_game_avg['avg_pt']; ?></td>
                                              <td class="averages"><?php echo $event_game_avg['avg_reb']; ?></td>
                                              <td class="averages"><?php echo $event_game_avg['avg_ast']; ?></td>
                                              <td class="averages"><?php echo $event_game_avg['avg_blk']; ?></td>
                                              <td class="averages"><?php echo $event_game_avg['avg_stl']; ?></td>
                                              <td class="averages"><?php echo $event_game_avg['avg_three']; ?></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; 
                            endif; 

                            if(empty($game_stats) && $arg[0] < 2): ?>
                          <div>
                            <h2 class="text-center">Game Stats</h2>
                            <p><?php echo $arg[8]; ?></p>
                          </div>
                        <?php endif; ?>
                      </div>

                      <?php if( $player_data->verified !== null && $player_data->verified !== 'null' && is_numeric($player_data->verified) ) switch( intval($player_data->verified) ) {
                        case 1:
                      ?>

                      <div id="verification-badge" class="cell shrink"><h4>Verification Processing</h4></div>

                      <?php
                        case 2:
                        case 3:
                        case 4:
                      ?>
                      
                      <div id="verification-badge" class="cell shrink cute text-center verification-badge-top-right" hidden>
<!--                          <div style="flex: 0 1 100%;" class="text-center" id="spp_event_log"><img class="width-50-200" src="<?php echo $game_stat->event_logo; ?>"></div> -->
                        <?php //<img src="< ?php echo get_template_directory_uri(); ? >/assets/verified.png" /> ?>
          <!--               <span class="verification-title"><i class="fi-check"></i>Verified to play</span> -->
                        <div class="grid-x text-center align-middle <?php echo ( $player_data->verified == 2 && !empty($player_data->birthday) && !empty($player_data->grad_year) ) ? 'small-up-2' : 'small-up-1'; ?>">

                          <?php if( !empty($ver_level) ) { ?>
                          <div class="verified-tag verified-age cell">
<!--                               <div class="help-tip"></div><?php echo $ver_level . '<span class="separator" style="display: inline;position: relative; left: 10px;">/</span>'; ?>
                              <div class="verified-age__tooltip small-padding" id="ageTooltip">
                                  <strong>8U</strong>
                                      <p>An athlete can be no older than 8 on August 31, <?php echo $verified_year;?></p>
                                      <p>Birthday Range: September 1, <?php echo (int)date('Y') - 9;?> - Present</p>

                                      <strong>9U</strong>
                                      <p>An athlete can be no older than 9 on August 31, <?php echo $verified_year;?></p>
                                      <p>Birthday Range: September 1, <?php echo (int)date('Y') - 10;?> - Present</p>

                                      <strong>10U</strong>
                                      <p>An athlete can be no older than 10 on August 31, <?php echo $verified_year;?></p>
                                      <p>Birthday Range: September 1, <?php echo (int)date('Y') - 11;?> - Present</p>

                                      <strong>11U</strong>
                                      <p>An athlete can be no older than 11 on August 31, <?php echo $verified_year;?></p>
                                      <p> Birthday Range: September 1, <?php echo (int)date('Y') - 12;?> - Present</p>

                                      <strong>12U</strong>
                                      <p>An athlete can be no older than 12 on August 31, <?php echo $verified_year;?></p>
                                      <p>Birthday Range: September 1, <?php echo (int)date('Y') - 13;?> - Present</p>

                                      <strong>13U</strong>
                                      <p>An athlete can be no older than 13 on August 31, <?php echo $verified_year;?></p>
                                      <p>Birthday Range: September 1, <?php echo (int)date('Y') - 14;?> - Present</p>

                                      <strong>14U</strong>
                                      <p>An athlete can be no older than 14 on August 31, <?php echo $verified_year;?></p>
                                      <p>Birthday Range: September 1, <?php echo (int)date('Y') - 15;?> - Present</p>
                              </div> -->
                          </div>
<!--                           <?php } ?> -->


<!--                           <?php if( ($player_data->verified == 2 || $player_data->verified == 4) && !empty($player_data->grad_year) ) { ?> -->
                          <div class="verified-tag verified-grade cell">
<!--                             <img class="width-50-200" src="<?php echo $game_stat->event_logo; ?>"> -->
<!--                             <div class="help-tip"></div><?php echo g365_class_to_grade($player_data->grad_year, true); ?>
                              <div class="verified-grade__tooltip small-padding" id="gradeTooltip">
                                  <strong>2nd Grade</strong>
                                      <p>If a player was born before 9/1/<?php echo (int)date('Y') - 9;?> BUT is in the 2nd grade, that player is eligible for 8U as an exception</p>

                                      <strong>3rd Grade</strong>
                                      <p>If a player was born before 9/1/<?php echo (int)date('Y') - 10;?> BUT is in the 3rd grade, that player is eligible for 9U as an exception</p>

                                      <strong>4th Grade</strong>
                                      <p>If a player was born before 9/1/<?php echo (int)date('Y') - 11;?> BUT is in the 4th grade, that player is eligible for 10U as an exception</p>

                                      <strong>5th Grade</strong>
                                      <p> If a player was born before 9/1/<?php echo (int)date('Y') - 12;?> BUT is in the 5th grade, that player is eligible for 11U as an exception</p>

                                      <strong>6th Grade</strong>
                                      <p>If a player was born before 9/1/<?php echo (int)date('Y') - 13;?> BUT is in the 6th grade, that player is eligible for 12U as an exception</p>

                                      <strong>7th Grade</strong>
                                      <p>If a player was born before 9/1/<?php echo (int)date('Y') - 14;?> BUT is in the 7th grade, that player is eligible for 13U as an exception</p>

                                      <strong>8th Grade</strong>
                                      <p>If a player was born before 9/1/<?php echo (int)date('Y') - 15;?> BUT is in the 8th grade, that player is eligible for 14U as an exception</p>
                              </div> -->
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php
                        }
                    
                      ?>
                    </div>
                </div>
              </div>
          </div>
        </div>
      </div>
      <?php endif;
      endif;?>
    </div>
    <!--   QR CODE AND LOGO     -->
    <div class="grid-x cell bottom-grid small-align-center small-padding-top medium-margin-bottom small-12 profile">
      <div class="cell medium-offset-1 large-offset-1 small-4 medium-6">
        <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/Passport-2023.png" alt="Sports Passports Official Logo">
      </div>
      <div class="cell align-center small-margin-top small-4 small-offset-1 medium-offset-1 medium-4 small-end">
        <canvas id="qrcode"></canvas>
      </div>
    </div>
    <a class="sports-passport-text" href="https://www.sportspassports.com/" target="_blank">SportsPassports.com</a>
    <div>
    </div>
		</div>
	</section>
	<script>
    initCardJs();
  </script>
</div>
<textarea id="emailContent" style="display: none;"></textarea>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>