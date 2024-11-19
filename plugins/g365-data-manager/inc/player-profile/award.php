<div id="profile-awards" class="cell small-12 options-wrapper xlarge-margin-top">
  <h2><span class="fi-trophy"></span> Awards</h2>
  <?php if( !empty($arg[0]) ) : /*if-1*/
    $not_just_watchlist = array_filter($arg[0], function($val){ return $val->award_type != 8; });
    if( !empty($not_just_watchlist) ) : ?>
      <div class="gray-bg border-radius">
        <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-6" id="award_placeholder">
        <?php foreach( $not_just_watchlist as $dex => $award ) : if( $award->award_type == 8 ) continue; ?>
          <?php
            $award_url = $award->award_img;
            $event_shortname =  $arg[0][$dex]->short_name."-".$arg[0][$dex]->award.".png"; $event_shortname = str_replace(" ", "-", $event_shortname);
            $default_badge_img = get_site_url() . '/wp-content/themes/g365-press/assets/badges/Passport-P-2023.png';
            if($arg[0][$dex]->award_type == 11 || $arg[0][$dex]->award_type == 12 || $arg[0][$dex]->award_type >= 1 && $arg[0][$dex]->award_type <= 5 || $arg[0][$dex]->award_type == 9 || $player_data->awards[$dex]->award_type == 134 || $player_data->awards[$dex]->award_type == 135 || $player_data->awards[$dex]->award_type == 136 || $arg[0][$dex]->award_type = 41){ $award_url=$award_url.$event_shortname; }
            $event_year = wp_date('Y', strtotime($award->eventtime));
            
            $headers = @get_headers($award_url);
            $img_url = (is_array($headers) && strpos($headers[0], '200') !== false) ? $award_url : $default_badge_img;
          ?>
            <div class="cell small-margin-top small-margin-bottom"> 
              <img class="award_container" src="<?php echo $img_url; ?>" title="<?php echo $award->award; ?> Award"/>
              <div class="badge-name">
                <?php
                      echo "<strong>$award->award_title $event_year</strong><br/>"; //echo g365_build_dates($award->dates, 2);  ?><br>
                <?php
                  if( !empty($player_data->stats) && is_array($player_data->stats) ){
                    foreach ($player_data->stats as $stat_key => $vars) {
                      if( $vars->event_id == $award->event_id) { echo $vars->event; break; } 
                    }
                  }
                ?>
              </div>
            </div>
        <?php endforeach; ?>
        </div>
      </div>
  <?php else : ?>
  <div>
   <p>Participate in a <a href="<?php echo get_site_url()?>/calendar/#grassroots365events">Grassroots 365 event</a> or <a href="<?php echo get_site_url()?>/calendar/#ebcevents">Elite Basketball Circuit Camp</a> to receive awards!</p>
  </div>
  <?php endif; ?>
  <?php else : ?>
    <div>
      <p>Participate in a <a href="<?php echo get_site_url()?>/calendar/#grassroots365events">Grassroots 365 event</a> or <a href="<?php echo get_site_url()?>/calendar/#ebcevents">Elite Basketball Circuit Camp</a> to receive awards!</p>
    </div>
  <?php endif; /*endif-1*/?>
</div>