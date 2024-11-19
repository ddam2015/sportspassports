 <div id="teams" class="grid-x large-12">
  <?php global $wp_query; $org_data = g365_get_org_profile( $wp_query->query_vars['org_name'] ); ?>
  <?php $program_years = g365_club_team_stat($event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 4); // Year only
   $select_year = year_dd_opt('cp_date_selector')[1]; echo year_dd_opt('cp_date_selector')[0];
   if(!empty($program_years[0]->event_date)): /*if-1*/ ?>
<!--     <h5>Overall Statistics</h5> -->
<!--     <div id="club_overall_graph" class="small-12 medium-4 large-4">
      <?php 
        $club_overall_lists = g365_club_team_stat($event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 6); // Overall team statistics
        if(!empty($club_overall_lists)){
          $club_overall_graph = g365_program_graph($club_overall_lists, 'game_result_label');
        }else{
          echo "<p>Overall Win/Loss % is not available.</p>";
        }
      ?>
    </div> -->
    <?php foreach($program_years as $index => $program_year):
      $program_year = $program_years[$index]->event_date; 
      $club_team_stats = g365_club_team_stat($event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 2);
//    echo '<pre>';
//    print_r($club_team_stats[$index]->event_org);
//    print_r($club_team_stats);
//    echo '</pre>';
      endforeach;
      if(!empty($club_team_stats)):
    ?>
   
  <?php global $wp_query;
  $get_org_lists =  slb_org_menu('slb-org-data', ['org_string'=>$wp_query->query_vars['subpg_type']], "stlb");?>
   
  <h3 id="lifetime_events" class=" width-full text-center">Select an Organization</h3>
<!--   display brands  -->
  <div class="container grid-x small-up-4 medium-up-3 large-up-4 text-center profile-feature medium-margin-top" id="eventBrandContainer">
    <div class="grid-x grid-margin-x small-up-3 medium-up-4 large-up-4 align-center text-center img-grid small-padding-sides">
    <?php foreach($get_org_lists['org_list'] as $org_list): 
      if(empty($org_list->profile_img)){ $org_logo = 'g365_profile_placeholder-spp-logo.gif'; }else{ $org_logo = $org_list->profile_img; }
      if($wp_query->query_vars['subpg_type'] === $org_list->nickname){ $is_selected = 'is-selected'; }else{ $is_selected = ''; }
    ?>
      <div class="cell relative small-margin-bottom stat-organization <?php echo $is_selected; ?> ">
        <input type="hidden" name="subject" id="subject" value="<?php echo $org_list->id; ?>">
<!--     backup old href <a href="<?php //echo get_site_url() ."/club/". $org_data->nickname ."/stats/"; ?>#event-list-<?php //echo $org_list->id; ?>" -->
        <a href="<?php echo get_site_url() ."/club/". $org_data->nickname ."/stats/"; ?>#event-list-<?php echo $org_list->id; ?>" onclick="displayEvents(event, '<?php echo $org_list->id; ?>')" style="text-decoration: none;">
            <img class="" loading="lazy" src="/wp-content/uploads/org-logos/<?php echo $org_logo; ?>" alt="<?php echo $org_list->nickname; ?>">
            <p class="no-margin-bottom font-eurostile bold"><?php echo $org_list->name; ?></p>
        </a>
      </div>                                
    <?php endforeach; ?>
    </div>
  </div>
  <?php if(!empty($wp_query->query_vars['subpg_type'])){ echo $get_org_lists['by_org']; } ?>
   
<br>
<hr id="profile-title-divider" class="profile-divider small-margin-top small-margin-bottom event-results-divider"> 
<?php $first_iteration = 'true';  ?>   
<?php foreach($get_org_lists['org_list'] as $org_list): ?>
  <?php if($first_iteration == 'true'){echo '<div class="orbit small-12 large-12 large-margin-bottom redeploy event-orbit" role="region" aria-label="Favorite Space Pictures" data-orbit  data-auto-play="false" id="event-list-'.$org_list->id.'">';}else{ echo '<div class="orbit large-12 large-margin-bottom redeploy event-orbit" role="region" aria-label="Favorite Space Pictures" data-orbit  data-auto-play="false" style="display: none;"  id="event-list-'.$org_list->id.'"><br><br><br>';}
  $first_iteration = 'false';?>
<!--  display events of brands   -->
  <div class="orbit-wrapper">
    <div class="orbit-controls">
      <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
      <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
    </div>
    <h3 class="text-center">Select an event to view results</h3>
    <ul class="orbit-container <?php echo $org_list->id ?> team-height-event" style="height: 128px !important;">      
             <?php $eventID;
                    $counter = 0;?>
             <?php foreach($club_team_stats as $club_team_stat):
               if($club_team_stat->event_org === $org_list->id){
                 $event_info = g365_get_event_data($club_team_stat->event_id, true);
                 if($counter === 0){
                   echo '<li class="is-active orbit-slide cell"><figure class="orbit-figure"><div class="event-contain">';
                 }   
                 $eventID = preg_replace('/\s+|\.|-/', '', $club_team_stat->event_id);?>
                 <a id="click<?php echo preg_replace('/\s+|\.|-/', '', $club_team_stat->event_org); ?>" href="#team_event_stats<?php echo $eventID; ?>" class="event-results--subevent profile-title block no-border" style="text-decoration: none;" onclick="displayStat(event, '<?php echo $eventID; ?>')">
                   <img class="orbit-image drop-shadow fit-cover transition-2 hover-scale" src="<?php echo $event_info->logo_img; ?>">
                     <!--<figcaption class="orbit-caption"><?php echo preg_replace('/\s+|\.|-/', '', $club_team_stat->event_org); ?></figcaption> -->
                 </a>
                 <?php 
                 $counter++;
                 if($counter === 8 || $club_team_stat === array_key_last($club_team_stats)){
                  echo ' </div></figure></li>';
                  $counter = 0;
                 }
               }
              endforeach; 
              ?>
    </ul>
    </div>
</div>
    <?php endforeach; ?>
   
   <script style="display:none" type="text/javascript">
        function clearActiveSelections(type) {
          if(type == 'event') {
            const selections = document.querySelectorAll('.stat-organization a');
            selections.forEach(selection => {
              selection.classList.remove('active-selection');
            })
          } else if(type == 'stat') {
            const selections = document.querySelectorAll('.orbit-container img');
            selections.forEach(selection => {
              selection.classList.remove('active-selection--shadow');
            
            })
          }
        }
     
        function displayStat(event, id){
         clearActiveSelections('stat');
         event.target.classList.add('active-selection--shadow');
          closingPrev();
          $('#team_event_stats' + id).css('display','block');
        }
        function closingPrev(id){
          $('.doublecheck').css('display','none'); 
        }
        function closeCurrent(id){
          closingPrev(); 
        }
       function displayEvents(event, id){
         clearActiveSelections('event');
         event.currentTarget.classList.add('active-selection');
         closingPrevEvent();
         $('#event-list-' + id).css('display','block');
       }
       function closingPrevEvent(id){
          $('.redeploy').css('display','none'); 
        }
     
     
   </script> 
  
   <?php foreach($club_team_stats as $club_team_stat):
                  ?>
                  <div class="small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
                    <div class="doublecheck" id="team_event_stats<?php echo preg_replace('/\s+|\.|-/', '', $club_team_stat->event_id); ?>" style="display:none;">
                      <div class="info-block">
                        <div class="grid-x grid-margin-x" id="club-team-game-scores">
                          <div class="cell small-12">
                            <div id="profile-stats-avg" class="cell small-12">
                              <h3 class="" style=";color:#fff ;text-align:center;padding:20px"><?php echo $club_team_stat->event_name; ?></h3>
                              <div class="exit_container">
                                <a class="event_stat_exit" style="text-decoration: none;" onclick="closeCurrent('<?php echo preg_replace('/\s+|\.|-/', '', $club_team_stat->event_id); ?>')">Close Event</a>
                              </div>
                              <div class="grid-x small-padding-top small-padding-bottom" style="background:black; color: white;">
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-2 medium-2 large-2 table-font-mobile">Team</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-2 medium-2 large-2 table-font-mobile">Opponent</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-2 medium-2 large-2 table-font-mobile">Result</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-2 medium-2 large-2 table-font-mobile">Court</p>
                                <p class="in-block no-margin-bottom text-center font-eurostile weight-bold small-4 medium-4 large-4 table-font-mobile">Date</p>
                              </div>
                            </div>
                          </div>
                          <?php $team_schedules = game_schedule_result_new($club_team_stat->event_id, $team_id, $org_data->id, $opponent_id, $select_year, $type = 1);
                            foreach($team_schedules as $team_schedule){
                            echo $team_schedule;
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
   <?php endforeach; ?>
   
   
   
   
    <?php else: ?> 
    <div class="small-padding-sides">
<!--       <h5>Yearly Stats</h5> -->
      <p>Stats are not available for the selected year.</p>
    </div>
    <?php endif; endif; ?>
</div>
<!-- PLACEHOLDER
<div class="club-recent-achievements" style="width: 100%;">
                        <h2 class="black-text">2023-2024 Championships</h2> 
                        <div class="flex" style="gap: 10px;">
                            <button id="17UBtn">17U Adidas Gold</button>
                            <button id="13UBtn">13U Blue</button>
                            <button id="10UBtn">10U Blue</button>
                          </div>
                        <div class="achievement-container">
                         <div class="club-achievement">
                           <img src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/King-of-the-Coast-Champions.png" alt="">
                    <p class="strong black-text">G365 King of the Coast 2022</p> 
                           <p class="black-text strong" data-team="17U Adidas Gold">17U Adidas Gold</p>
                         </div>
                          <div class="club-achievement">
                           <img src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/Fall-Kickoff-Champions.png" alt="">
                              <p class="strong black-text">G365 Fall Kickoff 2022</p> 
                           <p class="black-text strong" data-team="17U Adidas Gold">17U Adidas Gold</p>
                         </div>
                          <div class="club-achievement">
                           <img src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/On-the-Edge-Champions.png" alt="">
                            <p class="strong black-text">G365 On the Edge 2022</p> 
                           <p class="black-text strong" data-team="17U Adidas Gold">17U Adidas Gold</p>
                         </div>
                          <div class="club-achievement hide">
                           <img src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/Invitational-Champions.png" alt="">
                            <p class="strong black-text">G365 Invitational 2022</p> 
                           <p class="black-text strong" data-team="13U Blue">13U Blue</p>
                         </div>
                          <div class="club-achievement hide">
                           <img src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/The-Launch-Champions.png" alt="">
                            <p class="strong black-text">G365 The Launch 2022</p> 
                           <p class="black-text strong" data-team="13U Blue">13U Blue</p>
                         </div>
                          <div class="club-achievement hide">
                           <img src="https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/Above-the-Rim-Champions.png" alt="">
                            <p class="strong black-text">G365 Above the Rim 2022</p> 
                           <p class="black-text strong" data-team="10U Blue">10U Blue</p>
                         </div>
                        </div>
                    </div>
 END PLACEHOLDER -->
<div class="championships small-12 medium-12 large-12"><!-- Championship -->
  <?php g365_dir_render('club-profile','championship', $player_id, $arg = array($select_year, $team_id, $org_data->id, 'org_champ')); ?>
</div> 
<?php echo club_game_chart($club_team_graph['win'], $club_team_graph['loss'], $select_year, 'team_game_chart'); echo club_game_chart($club_overall_graph['win'], $club_overall_graph['loss'], 'Overall', 'club_overall_graph'); ?>

<!-- <script type="text/javascript">
//   window.addEventListener('DOMContentLoaded', () => {
// //     overview: on render hide list element, use javascript to display relevant choices
// //     TODO Add check for each brand, if any of the tournaments for a brand doesnt exist dont render the button
//     let seasonStats = document.querySelectorAll('#season-stats li');
//     let seasonEvents = document.querySelectorAll('#season-stats li a');
//     const grassrootsBtn = document.getElementById('grassrootsBtn');
//     const stageBtn = document.getElementById('stageBtn');
//     const hypeBtn = document.getElementById('hypeBtn');
    
// //     placeholder trophy
//     const btn17U = document.getElementById('17UBtn');
//     const btn13U = document.getElementById('13UBtn');
//     const btn10U = document.getElementById('10UBtn');
    
//     btn17U.addEventListener('click', function() {
//       document.querySelectorAll('.club-achievement').forEach(function(trophy){
//         trophy.classList.add('hide');
//       })
//       document.querySelectorAll('[data-team="17U Adidas Gold"]').forEach(function(trophy){
//         console.log(trophy.parentElement)
//         trophy.parentElement.classList.remove('hide');
//       })
//     })
//     btn13U.addEventListener('click', function() {
//       document.querySelectorAll('.club-achievement').forEach(function(trophy){
//         trophy.classList.add('hide');
//       })
//       document.querySelectorAll('[data-team="13U Blue"]').forEach(function(trophy){
//         console.log(trophy.parentElement)
//         trophy.parentElement.classList.remove('hide');
//       })
//     })
//     btn10U.addEventListener('click', function() {
//       document.querySelectorAll('.club-achievement').forEach(function(trophy){
//         trophy.classList.add('hide');
//       })
//       document.querySelectorAll('[data-team="10U Blue"]').forEach(function(trophy){
//         console.log(trophy.parentElement)
//         trophy.parentElement.classList.remove('hide');
//       })
//     })
// //     end placeholder trophy
  
// //     loop all events, attach data-brand name to it depending on string
//     seasonEvents.forEach(function(event) {
//       if(event.innerHTML.includes('G365') || event.innerHTML.includes('The Winter Challenge') || event.innerHTML.includes('Championship')) {
//         event.setAttribute('data-brand','g365');
//       } else if(event.innerHTML.includes('The Stage') || event.innerHTML.includes('SSB')) {
//         event.setAttribute('data-brand','stage');
//       } else if(event.innerHTML.includes('Hype Her Hoops')) {
//         event.setAttribute('data-brand','hype');      
//       }
//     })
    
//      grassrootsBtn.addEventListener("click", showG365);
//      stageBtn.addEventListener('click', showStage);
//      hypeBtn.addEventListener('click', showHype);
    
//     function showG365() {
//       let g365Events = document.querySelectorAll('[data-brand="g365"]');
//       //       hide other events
//       document.querySelectorAll('[data-brand="stage"]').forEach(function(event){
//         event.parentElement.classList.add('hide');
//       })
//       document.querySelectorAll('[data-brand="hype"]').forEach(function(event){
//         event.parentElement.classList.add('hide');
//       })
      
// //       show requested events
//       g365Events.forEach(function(event){
//         event.parentElement.classList.remove('hide');
//       })
//     }
//     function showStage() {
//       let stageEvents = document.querySelectorAll('[data-brand="stage"]');
      
// //       hide other events
//       document.querySelectorAll('[data-brand="g365"]').forEach(function(event){
//         event.parentElement.classList.add('hide');
//       })
//       document.querySelectorAll('[data-brand="hype"]').forEach(function(event){
//         event.parentElement.classList.add('hide');
//       })
      
// //       show requested events
//       stageEvents.forEach(function(event){
//         event.parentElement.classList.remove('hide');
//       })
//     }
//     function showHype() {
//       let hypeEvents = document.querySelectorAll('[data-brand="hype"]');
//       //       hide other events
//       document.querySelectorAll('[data-brand="g365"]').forEach(function(event){
//         event.parentElement.classList.add('hide');
//       })
//       document.querySelectorAll('[data-brand="stage"]').forEach(function(event){
//         event.parentElement.classList.add('hide');
//       })
      
// //       show requested events
//       hypeEvents.forEach(function(event){
//         event.parentElement.classList.remove('hide');
//       })
//     }
//   });
// </script> -->







