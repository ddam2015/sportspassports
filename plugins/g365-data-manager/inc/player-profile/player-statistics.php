<?php 
  $player_statistics = spp_statistics('player-statistics', ['player_id'=>$player_id]);
  $counter = 0;
  if(!empty($arg['award_data'])){ foreach ($arg['award_data'] as $value){ if ($value->award_title != 'Watchlist'){ $counter++; } } }
  switch($arg['data_type']){
    case 'player-statistics':
      if(!empty($player_statistics)):
?>
<div class="ticker-container ticker-container--player">
  <a href="<?php echo get_site_url(); ?>/player/<?php echo $arg['player_nickname']; ?>/stats/#profile-stats-avg" class="pl-statistics-div">
    <div class="ticker">
       <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/events-played-icon.png" class="ticker__icon" alt="Events Played">
       <p class="ticker__number"><?php echo $player_statistics['event_played'][0]->event_played; ?></p>
       <small>Total Lifetime<span class="block">Events Played</span></small>
    </div>
  </a>
  <a href="<?php echo get_site_url(); ?>/player/<?php echo $arg['player_nickname']; ?>/badges/#bdg_in_an_event" class="pl-statistics-div">
    <div class="ticker">
       <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/badges-earned-icon.png" class="ticker__icon" alt="Badges Earned">
        <p class="ticker__number"><?php echo $player_statistics['player_badge_data'][0]->badge_earned; ?></p>
        <small>Total Badges<span class="block">Earned</span></small>
     </div>
  </a>
  <a href="<?php echo get_site_url(); ?>/player/<?php echo $arg['player_nickname']; ?>/awards/#profile-awards" class="pl-statistics-div">
     <div class="ticker">
       <img src="<?php echo get_site_url(); ?>/wp-content/themes/g365-press/assets/tiny-logos/awards-earned-icon.png" class="ticker__icon" alt="Awards Earned">
        <p class="ticker__number" id="award_earned"><?php echo $counter; ?></p>
        <small>Total Awards<span class="block">Earned</span></small>
     </div>
  </a>
</div>
<?php endif; 
      break;
    case 'recent-achievements':
      if(!empty($player_statistics['player_badge_data'][0]->badge_data)):
        $recent_unlocked_badges = array();
        foreach(json_decode($player_statistics['player_badge_data'][0]->badge_data) as $recent_unlocked_badge){
          $recent_unlocked_badge = json_decode(json_encode($recent_unlocked_badge), true);
          $recent_unlocked_badge_type = substr(key($recent_unlocked_badge), 0, strpos(key($recent_unlocked_badge), '_'));
          if(!empty($recent_unlocked_badge) && is_array($recent_unlocked_badge)){
            // Only get most recent in the game stats
            if( $recent_unlocked_badge_type >= 13 && $recent_unlocked_badge_type <= 18){
              $recent_unlocked_badges[$recent_unlocked_badge_type] = $recent_unlocked_badge[key($recent_unlocked_badge)];            
            }
          }
        }
      ?>
      <div class="profile__media--video recent-achievements">  
        <h3 class="text-center small-padding-bottom"><?php echo g365_message()['recent_achievements']; ?></h3>
        <div class="profile__media--video-container">
          <?php foreach($recent_unlocked_badges as $recent_earned_badge): ?>
            <figure style="text-align: center; font-size: 10px; font-weight: bolder; min-width: 100px;">
              <img src="<?php echo $recent_earned_badge['badge_logo']; ?>">
<!--               <figcaption><?php echo $recent_earned_badge['name']; ?></figcaption> -->
            </figure>
          <?php endforeach; ?>
        </div>
      </div>
    <? endif; 
      break;
  } // End switch
?>