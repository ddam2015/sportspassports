<?php
  $stat_leaderboards = player_team_spotlight('stat-leaderboard');
  $all_tournament_mvps = player_team_spotlight('all-tournament-mvp')['award_data'];
  $all_t_mvp_ev_data = player_team_spotlight('all-tournament-mvp')['event_data'];
  $key_level = (g365_return_keys('g365_all_tournament_grade_key'));
  $num_count = 5;
  ?>
<!-- SLB <?php
?>-->
<!--   <h2 class="text-center show-for-small-only small-margin-bottom">Featured Profiles</h2> -->
  <div class="mobile-horizontal-nav-outer small-margin-bottom">
    <div class="spotlight__mobile-nav--player grid-x " id="spotlightMobileNav">
      <button id="btnSpotlightStat">Stat Leaderboard</button>
      <button class="button--secondary" id="btnSpotlightAllTournament">All Tournament</button>
    </div>
  </div>
<!-- mobile player spotlight -->
<div class="player-spotlight-mobile" id="spotlightStatLeaderboardMobile">
  <div class="player-spotlight__sectionMobile">
  <?php foreach($stat_leaderboards as $stat_leaderboard): 
              empty($stat_leaderboard->ev_profile_img) ? $profile_img = g365_player_img_dir($stat_leaderboard->player_url, $stat_leaderboard->event_nickname, $stat_leaderboard->player_id) : $profile_img = $stat_leaderboard->ev_profile_img;
            ?>
            <div class="player-spotlight__container">
                <!-- callout gset -->
                <div class="grid-y relative ">
                     <h5 class="weight-bold small-margin-bottom player-spotlight__category">Stat Leaderboard</h5>
                      <div class="small-margin-top small-margin-bottom player-spotlight__pic">
                         <a href="<?php echo get_site_url(); ?>/player/<?php echo $stat_leaderboard->player_url; ?>">
                          <img class="profile-image__player" src="<?php echo $profile_img; ?>" alt="<?php echo $profile_img; ?>">
                        </a>
                      </div>
                     <div class="grid-x player-spotlight__info">
                       <h5 class="weight-bold small-margin-bottom  player-spotlight__stat"><?php echo round($stat_leaderboard->avg_stat, 2); ?><span> PTS</span></h5>
                        <a class="player-spotlight__event" href="<?php echo get_site_url(); ?>/event/<?php echo $stat_leaderboard->event_nickname; ?>" target="_blank">
                          <img class="profile-event-img" src="<?php echo $stat_leaderboard->event_logo; ?>" alt="<?php echo $stat_leaderboard->event_name; ?>">
                        </a>
                      </div>
                    <h5 class="weight-bold no-margin-bottom player-spotlight__heading">
                      <a class="spotlight__card--heading" href="<?php echo get_site_url(); ?>/player/<?php echo $stat_leaderboard->player_url; ?>"><?php echo $stat_leaderboard->player_name; ?></a>
                    </h5>
                </div>
            </div>
            <?php endforeach; ?>
  </div>
</div>
<div class="player-spotlight-mobile hide"id="spotlightAllTournamentMobile">
  <div class="player-spotlight__sectionMobile" >
   <?php foreach($all_tournament_mvps as $all_tournament_mvp):
              if(!empty($all_tournament_mvp->player_id)){
                $validate_player_img = g365_player_img_dir($all_tournament_mvp->player_url, $all_tournament_mvp->event_nickname, $all_tournament_mvp->player_id);
              }
              $player_img = ( empty($all_tournament_mvp->profile_img) ) ? $validate_player_img : $all_tournament_mvp->profile_img;
              $award_division = str_replace(array('U', 'JV Girls', '12/13', '9 10'), array('', '46', '61', '62'), $all_tournament_mvp->award_class);
              $proper_syntax = str_replace(array(' /', 'and' ),array(' - ', '& '), $key_level[$award_division]);
            ?>
            <div class="player-spotlight__container">
              <div class="grid-y relative ">
                     <h5 class="weight-bold small-margin-bottom player-spotlight__category">All Tournament MVP</h5>
                      <div class="small-margin-top small-margin-bottom player-spotlight__pic">
                         <a href="<?php echo get_site_url(); ?>/player/<?php echo $all_tournament_mvp->player_url; ?>">
                          <img class="profile-image__player" src="<?php echo $player_img; ?>" alt="<?php echo $player_img; ?>">
                        </a>
                      </div>
                     <div class="grid-x player-spotlight__info">
                    <h5 class="weight-bold small-margin-bottom text-underline"><?php echo $proper_syntax; ?></h5>
                        <a class="player-spotlight__event" href="<?php echo get_site_url(); ?>/event/<?php echo $all_tournament_mvp->event_nickname; ?>" target="_blank">
                          <img class="profile-event-img tiny-margin-top" src="<?php echo $all_t_mvp_ev_data->logo_img; ?>" alt="<?php echo $all_t_mvp_ev_data->event_name; ?>">
                        </a>
                      </div>
                    <h5 class="weight-bold no-margin-bottom player-spotlight__heading">
                      <a class="spotlight__card--heading" href="<?php echo get_site_url(); ?>/player/<?php echo $all_tournament_mvp->player_url; ?>"><?php echo $all_tournament_mvp->player_name; ?></a>
                    </h5>
                </div>
            </div>
            <?php endforeach; ?>
  </div>
</div>
<!-- end mobile player spotlight -->

<script type="text/javascript">
      const buttons = document.querySelectorAll('#spotlightMobileNav button');
  
      const btnSpotlightStat = document.getElementById('btnSpotlightStat');
      const btnSpotlightAllTournament = document.getElementById('btnSpotlightAllTournament');
  
      const spotlightStatLeaderboardMobile = document.getElementById('spotlightStatLeaderboardMobile');
      const spotlightAllTournamentMobile = document.getElementById('spotlightAllTournamentMobile');
  
     buttons.forEach(button => {
       button.addEventListener('click', handleSpotlight);
     })
  
     function handleSpotlight() {
       hideAllSpotlight();
       deselectButtons();
       
       switch(this.id) {
           case 'btnSpotlightStat':  
            this.classList.remove('button--secondary');
           spotlightStatLeaderboardMobile.classList.remove('hide');
           break;
           case 'btnSpotlightAllTournament':
            this.classList.remove('button--secondary');
            spotlightAllTournamentMobile.classList.remove('hide');
           break;
       }
     }
  
    function hideAllSpotlight() {
      spotlightStatLeaderboardMobile.classList.add('hide')
      spotlightAllTournamentMobile.classList.add('hide')
    }
    
    function deselectButtons() {
      buttons.forEach(button => {
       button.classList.add('button--secondary');
     })
    }
      
</script>