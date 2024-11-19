<?php $championship_awards = championship_award($arg[0], $arg[3], $arg[1], $arg[2]);
?>
<!-- <h5>Championships</h5> -->
<ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12 hide" data-accordion data-allow-all-closed="true">
  <li class="accordion-item is-active" data-accordion-item>
    <!-- Accordion tab title -->
    <a href="#" class="accordion-title" style="font-size:18px; padding:20px 0 20px 14px"><?php echo "Championships ".g365_date_format($arg[0], 2); ?></a>
    <div class="extra-info grid-container">
      <div class="grid-x grid-margin-x">
      </div>
    </div>
    <div class="accordion-content" data-tab-content>
      <div class="grid-container">
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <?php if( !empty($championship_awards) ) : ?>
              <div class="gray-bg">
                <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5 ">
                <?php foreach($championship_awards as $championship_award):
                  $event_name = $championship_award['event_name'];
                  $event_shortname = $championship_award['event_shortname'];
                  $division = $championship_award['division'];
                  $champ_name = $championship_award['championship_team_name'];
                  $runner_up_name = $championship_award['runner_up_team_name'];
                  $award_type = array('Champions', 'Runner-Up', 'championship-and-runner-up');
                  $badge_log_champ = g365_award_dir($event_shortname, $award_type[2], $award_type[0]);
                  $badge_log_runner_up = g365_award_dir($event_shortname, $award_type[2], $award_type[1]);
                ?>
                  <div class="cell">
                    <img src="<?php echo empty($championship_award['championship_team']) ? $badge_log_runner_up : $badge_log_champ; ?>" title="<?php echo $event_name; ?> Award"/>
                    <div style="font-size:10px; line-height:1.4" class="test">
                      <?php echo empty($championship_award['championship_team']) ? "<strong>$runner_up_name</strong>" : "<strong>$champ_name</strong>"; ?><br>
                    </div>
                  </div>
                <?php endforeach; ?>
                </div>
              </div>
            <?php else : ?>
            <div>
              <p><?php echo g365_message()['champ_award']; ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </li>
</ul>


<div id="profile-awards event-results-championship-v2" class="cell small-12 options-wrapper">
  
          
          <?php if( !empty($championship_awards) ) : ?>
          <script type="text/javascript" style="display:none;"> 
            
            function checkChampName() {
              let viewAllTeamsBtn = document.getElementById('viewAllTeamsBtn');
              if(viewAllTeamsBtn.innerHTML == 'View All Teams') {
                viewAllTeamsBtn.innerHTML = 'Hide Teams';
              } else if(viewAllTeamsBtn.innerHTML == 'Hide Teams') {
                viewAllTeamsBtn.innerHTML = 'View All Teams';
              }
            }
              
            function seeMoreCH(cont) {
              checkChampName();
           
              let container = document.querySelector(cont)
              let elements = container.getElementsByClassName("hide");
              if (elements.length !== 0) {
                for (let i = elements.length - 1; i >= 0; i--) {
                    elements.item(i).classList.remove("hide")
                  } 
                } else {
                    elements = container.getElementsByClassName("championship-teams")
                    for (let i = elements.length - 1; i >= 6; i--) {
                    elements.item(i).classList.add("hide")
                  } 
                }
            }
          </script>
              <div class="gray-bg">
                <div class="event-result-championship-container">
                  <h1 class="event-result-championship-text club-h2 small-padding-left small-padding-top" id="trophies_won">Championships</h1>
                  <button class="event-result-championship-button" id="viewAllTeamsBtn" onclick="seeMoreCH('.event-result-championship-team-container')">View All Teams</button>
                </div>
                <div class="event-result-championship-team-container grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5">
                <div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="team-championships font-eurostile bold flex align-center align-middle" onclick="getAllChamps()"> All Championships </a></div>
                <?php
                  $team_ids = array();
                  $count = 1;
                  foreach($championship_awards as $championship_award):
                    if(in_array( $championship_award['championship_team'], $team_ids) || empty($championship_award['championship_team']) ) { 
                        continue;
                    } else {
                      if($count < 6) {
                        array_push($team_ids, $championship_award['championship_team']);
                        echo('<div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="team-championships font-eurostile bold flex align-center align-middle" onclick="filterChamps(' . $championship_award['championship_team'] . ')"> ' . $championship_award['championship_team_name'] . '  </a></div>');
                        $count++;
                      } else {
                        array_push($team_ids, $championship_award['championship_team']);
                        echo('<div class="hide cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="team-championships font-eurostile bold flex align-center align-middle" onclick="filterChamps(' . $championship_award['championship_team'] . ')"> ' . $championship_award['championship_team_name'] . '  </a></div>');
                        $count++;
                      }
                    }
                  endforeach;
                ?>
                </div>
                
<!--                 <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5 test-1">

                      <?php foreach($championship_awards as $championship_award):
                        $event_name = $championship_award['event_name'];
                        $event_shortname = $championship_award['event_shortname'];
                        $division = $championship_award['division'];
                        $champ_name = $championship_award['championship_team_name'];
                        $champ_name_id = $championship_award['championship_team'];
                        $runner_up_name = $championship_award['runner_up_team_name'];
                        $award_type = array('Champions', 'Runner-Up', 'championship-and-runner-up');
                        $badge_log_champ = g365_award_dir($event_shortname, $award_type[2], $award_type[0]);
                        $badge_log_runner_up = g365_award_dir($event_shortname, $award_type[2], $award_type[1]);
                      
                        echo('champ_name:' . $champ_name_id . ' team_display:' . $team_display);

                  ?>
                        <div class="cell small-margin-top small-margin-bottom championship-padd">
                          <img src="<?php echo empty($championship_award['championship_team']) ? $badge_log_runner_up : $badge_log_champ; ?>" title="<?php echo $event_name; ?> Award"/>
                          <div style="font-size:10px; line-height:1.4" class="test">
                            <?php echo empty($championship_award['championship_team']) ? "<strong>$runner_up_name hh</strong>" : "<strong>$champ_name</strong>"; ?><br>
                          </div>
                        </div>
                      <?php endforeach; ?>
                </div> -->
                
<!--                 <?php print_r($team_ids); ?> -->
                  <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5 test-1 club-scroll-container small-margin-sides">                  
                  <?php foreach($championship_awards as $championship_award):
                  $event_name = $championship_award['event_name'];
                  $event_shortname = $championship_award['event_shortname'];
                  $division = $championship_award['division'];
                  $champ_name = $championship_award['championship_team_name'];
                  $champ_name_id = $championship_award['championship_team'];
                  $runner_up_name = $championship_award['runner_up_team_name'];
//                    print_r($championship_award);
                  $award_type = array('Champions', 'Runner-Up', 'championship-and-runner-up');
                  $badge_log_champ = g365_award_dir($event_shortname, $award_type[2], $award_type[0]);
                  $badge_log_runner_up = g365_award_dir($event_shortname, $award_type[2], $award_type[1]);
                ?>
                  <?php if( $team_display = $champ_name_id) {
//                   echo('champ_name:' . $champ_name_id . ' team_display:' . $team_display);
                  ?>
                  <div class="cell small-margin-top small-margin-bottom flex flex-dir-column align-justify championship-padd champs_filter team_id_<?php echo $team_display; ?>" id="">
                    <img src="<?php echo empty($championship_award['championship_team']) ? $badge_log_runner_up : $badge_log_champ; ?>" title="<?php echo $event_name; ?> Award"/>
                    <div style="font-size:10px; line-height:1.4" class="test font-eurostile bold">
                      <?php echo empty($championship_award['championship_team']) ? "<strong>$runner_up_name hh</strong>" : "<strong>$champ_name</strong>"; ?><br>
                    </div>
                  </div>
                  <? }else {continue;} ?>
                <?php endforeach; ?>
                </div>
                
                <script style="display:none;">
                
                  function filterChamps(id){
                    closeUnmatchingChamps();
                    $('.team_id_' + id).css('display','flex');
                  }
                  
                  function closeUnmatchingChamps(){
                    $('.champs_filter').css('display','none');
                  }
                  
                  function getAllChamps(){
                    $('.champs_filter').css('display','flex');
                  }
                
                </script>
                
              </div>
            <?php else : ?>
            <div class="small-padding-sides">
              <p class="text-center"><?php echo g365_message()['champ_award']; ?></p>
            </div>
          <?php endif; ?>
</div>