<ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12" data-accordion data-allow-all-closed="true">
  <?php
  $seasonal_club_team_awards = club_team_award('org', ['selected_year'=>$arg[0], 'org_id'=>$arg[1], 'award_type'=>'seasonal'])['team_id'];
  $seasonal_club_team_awards = json_decode(json_encode($seasonal_club_team_awards), true);
  if(!empty($seasonal_club_team_awards)):
  foreach(explode(',', $seasonal_club_team_awards[0]['team_id']) as $team_id):
    $team_awards = club_team_award('team', ['selected_year'=>$arg[0], 'team_id'=>$team_id, 'org_id'=>$arg[1], 'award_type'=>'seasonal']);
    if( !empty($team_awards) ) : ?>
      <li class="accordion-item" data-accordion-item>
        <!-- Accordion tab title -->
        <a href="#" class="accordion-title" style="font-size:18px; padding:20px 0 20px 14px"><?php echo "Seasonal Awards ".g365_date_format($arg[0], 2); ?></a>
        <div class="accordion-content" data-tab-content>
          <div class="grid-container">
            <div id="profile-awards" class="cell small-12 options-wrapper">
              <div class="gray-bg">
                <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5">
                  <?php 
                    $team_name = club_team_award('org', ['selected_year'=>$arg[0], 'team_id'=>$team_id, 'org_id'=>$arg[1], 'award_type'=>'seasonal'])['team_info'];
                    echo '<div class="large-12 small-padding"><strong>'.$team_name[0]->team_name.'</strong></div>';
                    foreach($team_awards as $team_award):
                    $award_name = $team_award->name;
                    $award_logo = $team_award->logo_img;
                  ?>
                    <div class="cell small-margin-top small-margin-bottom">
                      <img src="<?php echo $award_logo; ?>" title="<?php echo $award_name; ?> Award"/>
                      <div style="font-size:10px; line-height:1.4">
                        <?php echo "<strong>$award_name</strong>"; ?><br>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>
    <?php endif; endforeach; ?>
  <?php endif; ?>
</ul>