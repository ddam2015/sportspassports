<!-- <h5>Individual Awards</h5> -->
<ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12 hide" data-accordion data-allow-all-closed="true">
  <li class="accordion-item" data-accordion-item>
    <!-- Accordion tab title -->
    <a href="#" class="accordion-title cl hide" style="font-size:18px; padding:20px 0 20px 14px"><?php echo "Individual Awards ".g365_date_format($arg[1], 2); ?></a>
    <div class="extra-info grid-container">
      <div class="grid-x grid-margin-x">
      </div>
    </div>
    <div class="accordion-content" data-tab-content>
      <div class="grid-container">
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <?php
          if( !empty($arg[0]->awards) ) :
            $not_just_watchlist = array_filter($arg[0]->awards, function($val){ return $val->award_type != 8; });
            if( !empty($not_just_watchlist) ) : ?>
              <div class="gray-bg">
                <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-7">
                <?php print_r($not_just_watchlist); ?>
                <?php foreach( $not_just_watchlist as $dex => $award ) : if( $award->award_type == 8 ) continue; 
                  $award_year = new DateTime($not_just_watchlist[$dex]->event_time);
                  $award_year = $award_year->format('Y');
                  $event_name = $not_just_watchlist[$dex]->event_shortname." ".$not_just_watchlist[$dex]->award_title." ".$award_year;
                  $player_name = $not_just_watchlist[$dex]->player_name;
                  $player_nickname = $not_just_watchlist[$dex]->player_nickname;
                  $player_id = $not_just_watchlist[$dex]->player_id;
                  $badge_log = str_replace(' ', '-', $not_just_watchlist[$dex]->event_shortname."-".$not_just_watchlist[$dex]->award.".png");
                  $event_nickname = $not_just_watchlist[$dex]->event_nickname;
                  $pl_img_url = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
                ?>
                  <div class="watchlist__player cell small-margin-top small-margin-bottom cjwatchlist__playerv2"> 
<!--                     <a class="watchlist__player-img" href="<?php echo get_site_url()."/player/".$player_nickname."/awards"?>" target="_blank"><img src="<?php echo ( !empty($award->award_img) ) ? $award->award_img.$badge_log : $default_badge_img; ?>" title="<?php echo $event_name; ?> Award"/></a> -->
                    <a href="<?php echo get_site_url()."/player/".$player_nickname."/awards"?>" target="_blank"><img class="watchlist__player-img" src="<?php echo $pl_img_url; ?>" title="<?php echo $event_name; ?> Award"/></a>
                    <div style="font-size:10px; line-height:1.4">
                      <?php echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>$player_name</strong></h3>"; ?>
                      <?php if($not_just_watchlist[$dex]->award_title === 'All-Tournament MVP'){echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>MVP</strong></h3>"; }else if($not_just_watchlist[$dex]->award_title === 'All-Tournament Team'){echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>All-Tournament</strong></h3>";}else{echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>" . $not_just_watchlist[$dex]->award_title . "</strong></h3>";}   ?>
                    </div>
                  </div>
                <?php endforeach; ?>
                </div>
              </div>
            <?php else : ?>
            <div>
              <p class="text-center"><?php echo g365_message()['p_ev_award']; ?></p>
            </div>
          <?php endif; ?>
          <?php else : ?>
          <div>
            <p class="text-center"><?php echo g365_message()['p_ev_award']; ?></p>
          </div>
          <?php endif; ?>
      </div>
      </div>
    </div>
  </li>
</ul>


<!-- updated display -->
<div class="grid-container" id="indiv_awards">
  <script type="text/javascript" style="display:none;">
             function checkEventsBtnName() {
              let viewAllTeamsBtn = document.getElementById('viewAllEventsBtn');
              if(viewAllEventsBtn.innerHTML == 'View All Events') {
                viewAllEventsBtn.innerHTML = 'Hide Events';
              } else if(viewAllEventsBtn.innerHTML == 'Hide Events') {
                viewAllEventsBtn.innerHTML = 'View All Events';
              }
            }
     
            function seeMore(container) {
              checkEventsBtnName();
              
              let cont = document.querySelector(container)
              let elements = cont.getElementsByClassName("hide");
              if (elements.length !== 0) {
                for (let i = elements.length - 1; i >= 0; i--) {
                 elements.item(i).classList.remove("hide")
                  } 
                } else {
                  elements = cont.getElementsByClassName("championship-teams")
                  for (let i = elements.length - 1; i >= 6; i--) {
                 elements.item(i).classList.add("hide")
                  } 
                }
            }
   </script> 
<!--    <div class="event-result-championship-container">
   </div> -->
   <div id="profile-awards indv-awards-v2" class="cell small-12 options-wrapper">
     
          <?php
          if( !empty($arg[0]->awards) ) :
            $not_just_watchlist = array_filter($arg[0]->awards, function($val){ return $val->award_type != 8; });
//             print_r($arg);
            if( !empty($not_just_watchlist) ) : ?>
              <div class="gray-bg v2-gray-bg event_results_individual_awards">
                <div class="event-result-championship-container">
                  <h1 class="event-result-championship-text club-h2 text-black small-padding-left small-padding-top" id="trophies_won">Individual Awards</h1>
                 <button class="event-result-championship-button" id="viewAllEventsBtn" onclick="seeMore('.event_results_individual_awards')">View All Events</button>
                </div>
                <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5 ">
                <div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="individual_awards team-championships font-eurostile bold flex align-center align-middle" onclick="getAllAwards()"> All Awards</a></div>
                <?php 
                  $team_nme = array();
                  $count = 1;
                  foreach( $not_just_watchlist as $dex => $award ) : if( $award->award_type == 8 ) continue;
                    $event_name = $not_just_watchlist[$dex]->event_shortname;
                    $event_id = $not_just_watchlist[$dex]->event_id;
                
                    if(in_array( $event_name, $team_nme) || empty($event_name) ) { 
                        continue;
                    } else {
                          array_push($team_nme, $event_name);
                        if ($count < 6) {
                          array_push($team_nme, $event_name);
                          echo('<div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="individual_awards team-championships font-eurostile bold flex align-center align-middle" onclick="filterAwards(' . $event_id . ')"> ' . $event_name . '  </a></div>');
                          $count++;
                        } else {
                          array_push($team_nme, $event_name);
                          echo('<div class="hide cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="individual_awards team-championships font-eurostile bold flex align-center align-middle" onclick="filterAwards(' . $event_id . ')"> ' . $event_name . '  </a></div>');
                          $count++;
                        } 
                    }
                  
//                   echo($event_name . " ");
                ?>
                
                 <?php endforeach; ?>
                </div>  
                
                
                
                <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-7 club-scroll-container small-margin-sides">
<!--                 <?php //print_r($not_just_watchlist); ?> -->
                <?php foreach( $not_just_watchlist as $dex => $award ) : if( $award->award_type == 8 ) continue; 
                  $award_year = new DateTime($not_just_watchlist[$dex]->event_time);
                  $award_year = $award_year->format('Y');
                  $event_name = $not_just_watchlist[$dex]->event_shortname." ".$not_just_watchlist[$dex]->award_title." ".$award_year;
                  $player_name = $not_just_watchlist[$dex]->player_name;
                  $player_nickname = $not_just_watchlist[$dex]->player_nickname;
                  $player_id = $not_just_watchlist[$dex]->player_id;
                  $badge_log = str_replace(' ', '-', $not_just_watchlist[$dex]->event_shortname."-".$not_just_watchlist[$dex]->award.".png");
                  $event_nickname = $not_just_watchlist[$dex]->event_nickname;
                  $pl_img_url = g365_player_img_dir($player_nickname, $event_nickname, $player_id);
                  $event_id = $not_just_watchlist[$dex]->event_id;
                ?>
                  <div class="watchlist__player cell small-margin-top small-margin-bottom cjwatchlist__playerv2 event_filter event_id_<?php echo $event_id; ?>"> 
<!--                     <a class="watchlist__player-img" href="<?php echo get_site_url()."/player/".$player_nickname."/awards"?>" target="_blank"><img src="<?php echo ( !empty($award->award_img) ) ? $award->award_img.$badge_log : $default_badge_img; ?>" title="<?php echo $event_name; ?> Award"/></a> -->
                    <a href="<?php echo get_site_url()."/player/".$player_nickname."/awards"?>" target="_blank"><img class="watchlist__player-img" src="<?php echo $pl_img_url; ?>" title="<?php echo $event_name; ?> Award"/></a>
                    <div style="font-size:10px; line-height:1.4">
                      <?php echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>$player_name</strong></h3>"; ?>
                      <?php if($not_just_watchlist[$dex]->award_title === 'All-Tournament MVP'){echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>MVP</strong></h3>"; }else if($not_just_watchlist[$dex]->award_title === 'All-Tournament Team'){echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>All-Tournament</strong></h3>";}else{echo "<h3 class='ind_pl_names cj_ind_pl_names'><strong>" . $not_just_watchlist[$dex]->award_title . "</strong></h3>";}   ?>
                    </div>
                  </div>
                <?php endforeach; ?>
                </div>
              </div>
            <?php else : ?>
            <div>
              <p class="text-center"><?php echo g365_message()['p_ev_award']; ?></p>
            </div>
          <?php endif; ?>
          <?php else : ?>
          <div>
            <p class="text-center"><?php echo g365_message()['p_ev_award']; ?></p>
          </div>
          <?php endif; ?>
     
     <script style="display:none;">
                
          function filterAwards(id){
              closeUnmatchingAwards();
              $('.event_id_' + id).css('display','block');
          }
                  
          function closeUnmatchingAwards(){
               $('.event_filter').css('display','none');
          }
       
       
          function getAllAwards(){
               $('.watchlist__player').css('display','block');
          }
                
     </script>
     
   </div>
</div>