<!-- <h5>Team Rankings</h5> -->
<ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12 hide" data-accordion data-allow-all-closed="true">
  <li class="accordion-item" data-accordion-item>
    <!-- Accordion tab title -->
    <a href="#" class="accordion-title" style="font-size:18px; padding:20px 0 20px 14px"><?php $select_year = year_dd_opt('most_recent_event')[1]; echo "Team Rankings ".g365_date_format($select_year, 2); ?></a>
    <div class="extra-info grid-container">
      <div class="grid-x grid-margin-x">
      </div>
    </div>
    <div class="accordion-content" data-tab-content>
      <div class="grid-container">
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <div class="ranked-team small-12 medium-12 large-12 small-padding-top"><!-- Ranked Team -->
            <?php
              $team_id = url_param('team_id');
              if(empty($team_id)){
                $team_rankings = cp_team_ranking($team_id, $arg[0], $select_year, 1);
              }else{
                $team_rankings = cp_team_ranking($team_id, $arg[0], $select_year, 2);
              }
              $t_ranking_lists = array();
              foreach($team_rankings as $index => $team_ranking){
                $t_ranking_lists[$team_ranking->start_datetime][] = $team_ranking;
              } 
              if(!empty($t_ranking_lists)):
                foreach($t_ranking_lists as $index => $ranking_blocks):
            
            
            ?>
             <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-6">
              <h5 class="small-12 medium-12 large-12" style="text-align:center;background-color:#000;padding:10px;"><?php $ranking_date = new DateTime($ranking_blocks[0]->start_datetime); echo $ranking_date->format('F Y'); ?></h5>
              <table>
                <?php foreach($ranking_blocks as $ranking_block): 
                $ranking_arr = str_replace(str_split('[]'),"", explode(",",$ranking_block->rankings));
                $team_id = str_replace(array(str_split('[]'), '"','[',']'), array('','','',''), explode(",", $ranking_block->is_team_id));
                $rankings = array_search($arg[0], $ranking_arr)+1; 
                $is_team = get_tm_ranking($team_id[($rankings-1)])[0]->team_name; if($rankings=='1'){$gold_border='gold-border';}else{$gold_border="";} 
                ?> 
                  <div class="team-ranking small-6 medium-3 large-2 <?php echo $gold_border; ?>">
                    <div class="rank_position">
                      <img src="<?php echo get_site_url().'/wp-content/themes/g365-press/assets/team-rankings/Ranking-'.$rankings.'.png' //echo ranking_label($rankings); ?>">
                    </div>
                    <div class="rank_info" style="font-size:12px">
                      <?php echo (empty($is_team) ? $arg[1] : $is_team). "<br/>"; echo $ranking_block->ranking_type. "<br/>"; ?>
                    </div>
                  </div> 
                <?php endforeach; ?>
              </table>
             </div>   
            <?php endforeach; else: ?>
            <div>
              <p><?php echo g365_message()['team_ranking']; ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </li>
</ul>





  <h1 class="event-result-championship-text small-padding-left small-padding-top" style="width: 100%;" id="teamRankingsHeading">Team Rankings</h1>
<div class="grid-container grid-container-team-rankings">
<?php
              $team_id = url_param('team_id');
              if(empty($team_id)){
                $team_rankings = cp_team_ranking($team_id, $arg[0], $select_year, 1);
              }else{
                $team_rankings = cp_team_ranking($team_id, $arg[0], $select_year, 2);
              }
              $t_ranking_lists = array();
              foreach($team_rankings as $index => $team_ranking){
                $t_ranking_lists[$team_ranking->start_datetime][] = $team_ranking;
              } 
              if(!empty($t_ranking_lists)):
                ?>
                <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-5">
                <div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="team-championships font-eurostile bold flex align-center align-middle" onclick="getAllRankings()"> All Rankings  </a></div><?php
                $award_mnth = array();
                foreach($t_ranking_lists as $index => $ranking_blocks):
                $ranking_date = new DateTime($ranking_blocks[0]->start_datetime);
                if(in_array( $ranking_date->format('F'), $award_mnth) || empty($ranking_date->format('F')) ) { 
                     continue;
                } else {
                     array_push($award_mnth, $ranking_date->format('F'));
                     $month = $ranking_date->format('F');
//                      echo('<div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="team-championships" onclick="filterRankings("' . $month . '")"> ' . $ranking_date->format('F') . '  </a></div>');
                     ?><div class="cell small-margin-top small-margin-bottom championship-padd championship-teams"><a class="team-championships font-eurostile bold flex align-center align-middle" onclick="filterRankings( '<?php echo $month; ?>'  )">  <?php echo $ranking_date->format('F'); ?>  </a></div><?php

                  
                }
            
            
                endforeach;
               ?></div><?php
              endif;
?>
        <div id="profile-awards" class="cell small-12 options-wrapper">
          <div class="ranked-team small-12 medium-12 large-12 small-padding-top"><!-- Ranked Team -->
            <?php
              $team_id = url_param('team_id');
              if(empty($team_id)){
                $team_rankings = cp_team_ranking($team_id, $arg[0], $select_year, 1);
              }else{
                $team_rankings = cp_team_ranking($team_id, $arg[0], $select_year, 2);
              }
              $t_ranking_lists = array();
              foreach($team_rankings as $index => $team_ranking){
                $t_ranking_lists[$team_ranking->start_datetime][] = $team_ranking;
              } 
              if(!empty($t_ranking_lists)):
//               print_r($team_rankings);
                foreach($t_ranking_lists as $index => $ranking_blocks):
            
                $ranking_date = new DateTime($ranking_blocks[0]->start_datetime);
            ?>
             <div class="grid-x grid-margin-x align-center small-margin-top small-margin-bottom text-center small-up-2 medium-up-4 large-up-6 team_rankings_filter rankings_month_<?php echo $ranking_date->format('F'); ?>">
              <h5 class="small-12 medium-12 large-12" style="text-align:center;background-color:#000;padding:10px;"><?php  echo $ranking_date->format('F Y'); ?></h5>
              <table>
                <?php foreach($ranking_blocks as $ranking_block):
                $ranking_arr = str_replace(str_split('[]'),"", explode(",",$ranking_block->rankings));
                $team_id = str_replace(array(str_split('[]'), '"','[',']'), array('','','',''), explode(",", $ranking_block->is_team_id));
                $rankings = array_search($arg[0], $ranking_arr)+1;
                $is_team = get_tm_ranking($team_id[($rankings-1)])[0]->team_name; if($rankings=='1'){$gold_border='gold-border';}else{$gold_border="";} 
                ?> 
                  <div class="team-ranking small-6 medium-3 large-2 <?php echo $gold_border; ?> ">
                    <div class="rank_position">
                      <img src="<?php echo get_site_url().'/wp-content/themes/g365-press/assets/team-rankings/Ranking-'.$rankings.'.png' //echo ranking_label($rankings); ?>">
                    </div>
                    <div class="rank_info" style="font-size:12px">
                      <?php echo (empty($is_team) ? $arg[1] : $is_team). "<br/>"; echo $ranking_block->ranking_type. "<br/>"; ?>
                    </div>
                  </div> 
                <?php endforeach; ?>
              </table>
             </div>   
            <?php endforeach; else: ?>
            <div>
              <p class="small-text-center"><?php echo g365_message()['team_ranking']; ?></p>
            </div>
            <?php endif; ?>
          </div>
          
          <script style="display:none;">
                
          function filterRankings(id){
              closeUnmatchingTeamAwards();
//               console.log('.rankings_month_' + id);
              $('.rankings_month_' + id).css('display','flex');
          }
                  
          function closeUnmatchingTeamAwards(){
               $('.team_rankings_filter').css('display','none');
          }
            
            function getAllRankings(){
              $('.team_rankings_filter').css('display','flex');
          }
                
          </script>
          
        </div>
      </div>