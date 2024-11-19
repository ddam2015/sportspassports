<?php $seasonal_team_awards = club_team_award('team', ['selected_year'=>$arg[0], 'team_id'=>$arg[1], 'award_type'=>'seasonal']); if( !empty($seasonal_team_awards) ) : ?>
<ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12" data-accordion data-allow-all-closed="true">
  <li class="accordion-item" data-accordion-item>
    <!-- Accordion tab title -->
    <a href="#" class="accordion-title" style="font-size:18px; padding:20px 0 20px 14px"><?php echo "Seasonal Awards ".g365_date_format($arg[0], 2); ?></a>
    <div class="extra-info grid-container">
      <div class="grid-x grid-margin-x">
      </div>
    </div>
    <div class="accordion-content" data-tab-content>
      <div class="grid-container">
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <div class="gray-bg">
            <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5">
            <?php foreach($seasonal_team_awards as $seasonal_team_award):
              $award_name = $seasonal_team_award->name;
              $award_logo = $seasonal_team_award->logo_img;
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
</ul>
<?php endif; ?>