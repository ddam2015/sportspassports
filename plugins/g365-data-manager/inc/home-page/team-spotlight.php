<?php
  $stat_leaderboards = player_team_spotlight('stat-leaderboard');
  $all_tournament_mvps = player_team_spotlight('all-tournament-mvp')['award_data'];
  $all_t_mvp_ev_data = player_team_spotlight('all-tournament-mvp')['event_data'];
  $key_level = (g365_return_keys('g365_all_tournament_grade_key'));
  $num_count = 5;
  $team_rankings = player_team_spotlight('team-ranking')['org_records'];
  $team_data = player_team_spotlight('team-ranking')['org_data'];
  $championships = player_team_spotlight('championship');
  ?>
<!-- SLB <?php
?>-->
  <div class="mobile-horizontal-nav-outer small-margin-bottom">
    <div class="spotlight__mobile-nav grid-x" id="spotlightMobileNav">
      <button class="" id="btnSpotlightTeam">Team Rankings</button>
      <button class="button--secondary" id="btnSpotlightChamp">Championships</button>
    </div>
  </div>

<div class="grid-x grid-margin-x small-up-2 medium-up-4 text-center profile-feature profile-widget player-spotlight hide" id="homepageTeamSpotlight">
    <div class="player-spotlight__section" id="spotlightTeamRanking">
      <div class="orbit" role="region" aria-label="Stat Leaderboard" data-orbit data-auto-play="false">
        <div class="orbit-wrapper">
          <ul class="orbit-container">
<!--             <h5 class="weight-bold small-margin-bottom player-spotlight__heading">Team Rankings</h5> -->
            <?php foreach($team_rankings as $team_ranking):
              empty($team_ranking->ev_profile_img) ? $profile_img = get_site_url().'/wp-content/uploads/event-profiles/g365_profile_placeholder.gif' : $profile_img = $team_ranking->ev_profile_img;
            $team_name = $team_data->org_records[$team_ranking->rankings[0]]->name;
            $org_img = ( empty($team_data->org_records[$team_ranking->rankings[0]]->org_logo) ) ? $default_profile_img : '/wp-content/uploads/org-logos/' . $team_data->org_records[$team_ranking->rankings[0]]->org_logo;
//             $is_team_name = get_tm_ranking($is_team[$subdex]->org->team_id)[0]->team_name;
            $team_url = get_site_url()."/club/".$team_data->org_records[$team_ranking->rankings[0]]->org_url."/teams/".$full_team_url."/".$team_custom_url;
            $club_url = get_site_url()."/club/".$team_data->org_records[$team_ranking->rankings[0]]->org_url;
//             print_r($team_name);
            ?>
            <li class="is-active orbit-slide">
                <!-- callout gset -->
                <div class="grid-y relative">
                  <h5 class="weight-bold small-margin-bottom player-spotlight__category">Team Rankings</h5>
                  <div class="relative small-margin-top small-margin-bottom player-spotlight__pic">
                    <a class="pl_tm_sl_a" href="<?php echo $club_url; ?>">
                      <img class="pl_tm_sl profile-image__player" src="<?php echo $org_img; ?>" alt="<?php echo $org_img; ?>">
                    </a>
                  </div>
                  <div class="grid-x player-spotlight__info">
                    <div class="grid-y align-center" style="align-items: center;">
                      <?php echo '<br /><span style="color:white">'.date('F Y', strtotime($team_ranking->start_datetime)) .'</span>'; ?>
                    <h5 class="weight-bold small-margin-bottom text-underline" style="width: 100%;"><?php echo $team_ranking->ranking_type; ?></h5>
                    </div>
                    <a class="player-spotlight__event" href="<?php echo $club_url; ?>" target="_blank">
                      <img class="profile-event-img tiny-margin-top" src="https://grassroots365.com/wp-content/themes/g365-press/assets/team-rankings/Ranking-1.png" alt="ranking-1">
                    </a>
                  </div>
                  <h5 class="weight-bold no-margin-bottom player-spotlight__heading">
                    <a class="" href="<?php echo $club_url; ?>"><?php echo $team_name; ?></a>
                  </h5>
                </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <nav class="orbit-bullets">
          <?php for($i = 0; $i < $num_count; $i++ ): ?>
          <button data-slide="<?php echo $i; ?>"></button>
          <?php endfor; ?>
        </nav>
      </div>
    </div>
    <div class="player-spotlight__section" id="spotlightChampionships">
      <div class="orbit" role="region" aria-label="Stat Leaderboard" data-orbit data-auto-play="false">
        <div class="orbit-wrapper">
          <ul class="orbit-container">
<!--             <h5 class="weight-bold small-margin-bottom player-spotlight__heading">Championships</h5> -->
            <?php foreach($championships as $index => $championship):
            ?>
            <li class="is-active orbit-slide">
                <!-- callout gset -->
                <div class="grid-y relative">
                  <h5 class="weight-bold small-margin-bottom player-spotlight__category">Championships</h5>
                  <div class="relative small-margin-top small-margin-bottom player-spotlight__pic">
                  <a class="pl_tm_sl_a" href="<?php echo get_site_url(); ?>/club/<?php echo $championship[0]->org_url; ?>">
                    <img class="pl_tm_sl profile-image__player" src="/wp-content/uploads/org-logos/<?php echo $championship[0]->org_logo; ?>" alt="<?php echo $championship[0]->org_url; ?>">
                  </a>
                  </div>
                  <div class="grid-x player-spotlight__info">
                    <h5 class="weight-bold small-margin-bottom text-underline"><?php echo $index; ?></h5>
                    
                    <a class="player-spotlight__event" href="<?php echo get_site_url(); ?>/event/<?php echo $championship[0]->event_nickname; ?>" target="_blank">
                      <img class="profile-event-img tiny-margin-top" src="<?php echo $championship[0]->event_logo; ?>" alt="<?php echo $championship[0]->event_logo; ?>">
                    </a>
                  </div>
                  <h5 class="weight-bold no-margin-bottom player-spotlight__heading">
                    <a class="" href="<?php echo get_site_url(); ?>/club/<?php echo $championship[0]->org_url; ?>"><?php echo $championship[0]->org_name; ?></a>
                  </h5>
                </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <nav class="orbit-bullets">
          <?php for($i = 0; $i < $num_count; $i++ ): ?>
          <button data-slide="<?php echo $i; ?>"></button>
          <?php endfor; ?>
        </nav>
      </div>
    </div>
  </div>


<!-- mobile player spotlight -->
<div class="player-spotlight-mobile" id="spotlightTeamRankingMobile">
    <div class="player-spotlight__sectionMobile">
       <?php foreach($team_rankings as $team_ranking):
              empty($team_ranking->ev_profile_img) ? $profile_img = get_site_url().'/wp-content/uploads/event-profiles/g365_profile_placeholder.gif' : $profile_img = $team_ranking->ev_profile_img;
            $team_name = $team_data->org_records[$team_ranking->rankings[0]]->name;
            $org_img = ( empty($team_data->org_records[$team_ranking->rankings[0]]->org_logo) ) ? $default_profile_img : '/wp-content/uploads/org-logos/' . $team_data->org_records[$team_ranking->rankings[0]]->org_logo;
//             $is_team_name = get_tm_ranking($is_team[$subdex]->org->team_id)[0]->team_name;
            $team_url = get_site_url()."/club/".$team_data->org_records[$team_ranking->rankings[0]]->org_url."/teams/".$full_team_url."/".$team_custom_url;
            $club_url = get_site_url()."/club/".$team_data->org_records[$team_ranking->rankings[0]]->org_url;
//             print_r($team_name);
            ?>
            <div class="player-spotlight__container">
                <!-- callout gset -->
                <div class="grid-y relative">
                  <h5 class="weight-bold small-margin-bottom player-spotlight__category">Team Rankings</h5>
                  <div class="relative small-margin-top small-margin-bottom player-spotlight__pic club-spotlight__pic">
                    <a class="pl_tm_sl_a" href="<?php echo $club_url; ?>">
                      <img class="pl_tm_sl club-spotlight__img" src="<?php echo $org_img; ?>" alt="<?php echo $org_img; ?>">
                    </a>
                  </div>
                  <div class="grid-x player-spotlight__info">
                    <div class="grid-y align-center" style="align-items: center;">
                      <?php echo '<span style="color:white">'.date('F Y', strtotime($team_ranking->start_datetime)) .'</span>'; ?>
                    <h5 class="weight-bold small-margin-bottom text-underline" style="width: 100%;"><?php echo $team_ranking->ranking_type; ?></h5>
                    </div>
                    <a class="player-spotlight__event" href="<?php echo $club_url; ?>" target="_blank">
                      <img class="profile-event-img" src="https://grassroots365.com/wp-content/themes/g365-press/assets/team-rankings/Ranking-1.png" alt="ranking-1">
                    </a>
                  </div>
                  <h5 class="weight-bold no-margin-bottom player-spotlight__heading">
                    <a class="spotlight__card--heading" href="<?php echo $club_url; ?>"><?php echo $team_name; ?></a>
                  </h5>
                </div>
            </div>
            <?php endforeach; ?>
    </div>
</div>
<div class="player-spotlight-mobile hide" id="spotlightChampionshipsMobile">
    <div class="player-spotlight__sectionMobile"> 
      <?php foreach($championships as $index => $championship):
            ?>
            <div class="player-spotlight__container">
                <!-- callout gset -->
                <div class="grid-y relative">
                  <h5 class="weight-bold small-margin-bottom player-spotlight__category">Championships</h5>
                  <div class="relative small-margin-top small-margin-bottom player-spotlight__pic club-spotlight__pic">
                  <a class="pl_tm_sl_a" href="<?php echo get_site_url(); ?>/club/<?php echo $championship[0]->org_url; ?>">
                    <img class="pl_tm_sl club-spotlight__img" src="/wp-content/uploads/org-logos/<?php echo $championship[0]->org_logo; ?>" alt="<?php echo $championship[0]->org_url; ?>">
                  </a>
                  </div>
                  <div class="grid-x player-spotlight__info">
                    <h5 class="weight-bold small-margin-bottom text-underline"><?php echo $index; ?></h5>
                    
                    <a class="player-spotlight__event" href="<?php echo get_site_url(); ?>/event/<?php echo $championship[0]->event_nickname; ?>" target="_blank">
                      <img class="profile-event-img" src="<?php echo $championship[0]->event_logo; ?>" alt="<?php echo $championship[0]->event_logo; ?>">
                    </a>
                  </div>
                  <h5 class="weight-bold no-margin-bottom player-spotlight__heading">
                    <a class="spotlight__card--heading" href="<?php echo get_site_url(); ?>/club/<?php echo $championship[0]->org_url; ?>"><?php echo $championship[0]->org_name; ?></a>
                  </h5>
                </div>
            </div >
            <?php endforeach; ?>
    </div>
</div>

<!-- end mobile player spotlight -->

<script type="text/javascript">
      const buttons = document.querySelectorAll('#spotlightMobileNav button');
  
      const btnSpotlightTeam = document.getElementById('btnSpotlightTeam');
      const btnSpotlightChamp = document.getElementById('btnSpotlightChamp');
  
      const spotlightTeamRankingMobile = document.getElementById('spotlightTeamRankingMobile');
      const spotlightChampionshipsMobile = document.getElementById('spotlightChampionshipsMobile');
  
     buttons.forEach(button => {
       button.addEventListener('click', handleSpotlight);
     })
  
     function handleSpotlight() {
       hideAllSpotlight();
       deselectButtons();
       
       switch(this.id) {
           case 'btnSpotlightTeam':
            this.classList.remove('button--secondary');
            spotlightTeamRankingMobile.classList.remove('hide');
           break;
           case 'btnSpotlightChamp':
            this.classList.remove('button--secondary');
           spotlightChampionshipsMobile.classList.remove('hide');
           break;
       }
     }
  
    function hideAllSpotlight() {
      spotlightTeamRankingMobile.classList.add('hide')
      spotlightChampionshipsMobile.classList.add('hide')
    }
    
    function deselectButtons() {
      buttons.forEach(button => {
       button.classList.add('button--secondary');
     })
    }
      
</script>