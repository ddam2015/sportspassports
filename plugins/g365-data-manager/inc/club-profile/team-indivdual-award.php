<?php 
  $defautl_ros = g365_team_rosters($arg[0], $arg[1], $arg[2], 3); // Get all players info within select year and team
  $team_pl_id = array();
  foreach($defautl_ros as $team_roster){ 
  $player_id_list = $team_roster->players;
    if(!empty($player_id_list)){
      $player_id_list = array_keys(json_decode($player_id_list, true));
      foreach($player_id_list as $index => $player_ids){ // Filter players
        if (!in_array($player_ids, $team_pl_id)){
          $team_pl_id[] = $player_ids;
        }   
      }
    }
  }
  $player_data = g365_get_award(null, $arg[0], $arg[1], $arg[2], 1);
?>
<!-- <h5>Team Individual Awards</h5> -->
<ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12 hide" data-accordion data-allow-all-closed="true">
  <li class="accordion-item" data-accordion-item>
    <!-- Accordion tab title -->
    <a href="#" class="accordion-title cl" style="font-size:18px; padding:20px 0 20px 14px"><?php echo "Individual Awards ".g365_date_format($arg[0], 2); ?></a>
    <div class="extra-info grid-container">
      <div class="grid-x grid-margin-x">
      </div>
    </div>
    <div class="accordion-content" data-tab-content>
      <div class="grid-container">
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <?php
          if( !empty($player_data->awards) ) :
            $not_just_watchlist = array_filter($player_data->awards, function($val){ return $val->award_type != 8; });
            if( !empty($not_just_watchlist) ) : ?>
          <div class="form__border gray-bg">
            <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-8">
            <?php foreach( $not_just_watchlist as $dex => $award ) : if( $award->award_type == 8 ) continue; 
              $award_year = new DateTime($not_just_watchlist[$dex]->event_time);
              $award_year = $award_year->format('Y');
              $event_name = $not_just_watchlist[$dex]->event_shortname." ".$not_just_watchlist[$dex]->award_title." ".$award_year;
              $player_name = $not_just_watchlist[$dex]->player_name;
              $player_nickname = $not_just_watchlist[$dex]->player_nickname;
              $badge_log = str_replace(' ', '-', $not_just_watchlist[$dex]->event_shortname."-".$not_just_watchlist[$dex]->award.".png");
            ?>
              <div class="cell small-margin-top small-margin-bottom"> 
                <a href="<?php echo get_site_url()."/player/".$player_nickname."/awards"?>" target="_blank"><img src="<?php echo ( !empty($award->award_img) ) ? $award->award_img.$badge_log : $default_badge_img; ?>" title="<?php echo $event_name; ?> Award"/></a>
                <div style="font-size:10px; line-height:1.4">
                  <?php echo "<strong>$event_name $player_name</strong>"; ?><br>
                </div>
              </div>
            <?php endforeach; ?>
            </div>
          </div>
          <?php else : ?>
          <div>
            <p class="small-text-center"><?php echo g365_message()['p_ev_award']; ?></p>
          </div>
          <?php endif; else: ?>
          <div>
            <p class="small-text-center"><?php echo g365_message()['p_ev_award']; ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </li>
</ul>


<div class="grid-container">
        <h1 class="event-result-championship-text">Individual Awards</h1>
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <?php
          if( !empty($player_data->awards) ) :
            $not_just_watchlist = array_filter($player_data->awards, function($val){ return $val->award_type != 8; });
            if( !empty($not_just_watchlist) ) : ?>
          <div class="form__border gray-bg teamprofile_indiv_award_v2">
            <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-8">
            <?php foreach( $not_just_watchlist as $dex => $award ) : if( $award->award_type == 8 ) continue; 
              $award_year = new DateTime($not_just_watchlist[$dex]->event_time);
              $award_year = $award_year->format('Y');
              $event_name = $not_just_watchlist[$dex]->event_shortname." ".$not_just_watchlist[$dex]->award_title." ".$award_year;
              $player_name = $not_just_watchlist[$dex]->player_name;
              $player_nickname = $not_just_watchlist[$dex]->player_nickname;
              $badge_log = str_replace(' ', '-', $not_just_watchlist[$dex]->event_shortname."-".$not_just_watchlist[$dex]->award.".png");
            ?>
              <div class="cell small-margin-top small-margin-bottom"> 
                <a href="<?php echo get_site_url()."/player/".$player_nickname."/awards"?>" target="_blank"><img src="<?php echo ( !empty($award->award_img) ) ? $award->award_img.$badge_log : $default_badge_img; ?>" title="<?php echo $event_name; ?> Award"/></a>
                <div style="font-size:10px; line-height:1.4">
                  <?php echo "<strong>$event_name $player_name</strong>"; ?><br>
                </div>
              </div>
            <?php endforeach; ?>
            </div>
          </div>
          <?php else : ?>
          <div>
            <p class="small-text-center"><?php echo g365_message()['p_ev_award']; ?></p>
          </div>
          <?php endif; else: ?>
          <div>
            <p class="small-text-center"><?php echo g365_message()['p_ev_award']; ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>