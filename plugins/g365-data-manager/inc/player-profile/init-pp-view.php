<?php global $wp_query; switch($wp_query->query_vars['st_type']){ case '': case 'tournament': case 'scholastic': if($wp_query->query_vars['st_type'] == 'scholastic'){ $arg[0] = 4; }else{ $arg[0] = $arg[0]; } ?>
<div id="profile-games" class="cell small-12">
  <?php $event_id = $wp_query->query_vars['pl_tp']; $game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]); if(!empty($game_stats)): echo blur_box($arg[1]); ?>
  <h2 class="text-center">Game Stats</h2>
  <ul class="tabs separate small-margin-left small-margin-bottom small-up-2 medium-up-3 small-12 medium-12 large-12 text-center grid-x" id="season-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
    <?php foreach($game_stats as $game_stat): if( (in_array($game_stat->event_id, $arg[3])) || (in_array($game_stat->event_id, pp_ev_exception())) ):/*if-paid-event*/ ?>
    <li class="tabs-title cell">
      <a onclick="ev_form_submit(this)" id="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1]), 'tc-tournament-stat') ?>" href="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1]), 'tc-tournament-stat') ?>" class="profile-title block">
        <?php echo $game_stat->event_name; ?>
      </a>
    </li>
    <?php else: ?>
      <li class="tabs-title cell event-unlock__trigger">
        <a class="fi-lock ev_locked"> <?php echo $game_stat->event_name; ?></a>
      </li>
    <?php endif;/*endif-paid-event*/ endforeach; ?>
  </ul>
  <?php echo pp_pay_link($arg[1])[1]; ?>
  <div class="tabs-content small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
  <?php 
    $simp_game_stats = game_stat_filter_sh($player_id, $event_id, $is_only_event = true, $arg[1], $arg[0]);
    if(!empty($simp_game_stats)): /*if sgs*/
      foreach($simp_game_stats as $game_stat): 
      $event_game_avg = avg_game_stat($player_id, $event_id); 
  ?>
    <?php //if(!empty($event_id) && ($event_id == $game_stat->event_id)):/*if-1*/ ?>
      <div class="tabs-panel" id="<?php echo g365_url_linkage(array('', '', $game_stat->event_name, true, $arg[1]), 'tc-tournament-stat') ?>">
        <div class="info-block">
          <div class="grid-x grid-margin-x">
            <div class="cell small-12">
              <div id="profile-stats-avg" class="cell small-12">
                 <h3 class="stats__table-heading"><?php echo $game_stat->event_name; ?></h3>
              </div>
              <div class="ave_field large-margin-bottom table-scroll">
                <strong>Event Averages:</strong>
                <table class="text-center ghost-white-bg no-margin-bottom">
                  <tbody class="stats__table--player">
                    <tr>
                      <th>PPG</th>
                      <th>RPG</th>
                      <th>APG</th>
                      <th>BPG</th>
                      <th>SPG</th>
                      <th>3PT</th>
                    </tr>
                    <tr class="color-body emphasis">              
                      <td><?php echo $event_game_avg['avg_pt']; ?></td>
                      <td><?php echo $event_game_avg['avg_reb']; ?></td>
                      <td><?php echo $event_game_avg['avg_ast']; ?></td>
                      <td><?php echo $event_game_avg['avg_blk']; ?></td>
                      <td><?php echo $event_game_avg['avg_stl']; ?></td>
                      <td><?php echo $event_game_avg['avg_three']; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="cell small-12">
                <div class="info">
                      <?php echo cts_st_tb(null,4)[0]; $game_stat_lists = (g365_pl_game_stat($player_id, $game_stat->event_id, $is_only_event = false, $arg[1], $exception = null)); foreach($game_stat_lists as $index => $game_stat_list): $game_stat_data = json_decode($game_stat_list->stats); $gm_res = g365_club_team_stat($event_id = null, $team_id = null, $org_id, $opponent_id = null, $arg[1], 8, array($game_stat_list->game_id,$game_stat_list->team_id));
                        if($gm_res[0]->game_result_label == "W"){
                          $gm_result_color = 'style="color:white; font-weight:bold;font-size:15px"';
                        }else{
                          $gm_result_color = 'style="color:hsl(0,60%,50%); font-weight:bold;font-size:15px"';
                        }?>
                      <tr class="color-body emphasis">
                        <td>
                          <?php if(!empty($gm_res[0]->game_result)): ?>
                          <div class="stats_customize cts_res flex items-center small-margin-bottom small-12 medium-12 large-12" style="max-width:640px">
                            <div class="team_logo_box"><!-- hide-for-small-only -->
                              <a class="flex align-middle" href="" target="_blank"><img style="height:100px;width:125px;z-index:0;" src="<?php echo (!empty($gm_res[0]->org_logo) ? $gm_res[0]->org_logo != "NULL" ? $org_logo.$gm_res[0]->org_logo : $placeholder_img : $placeholder_img); ?>">
                              </a>
                            </div>
                            <div class="grid-x cts_res_box align-center">
                              <div class="small-4 medium-4 large-2 large-offset-2">
                                <span class="large-8 end" style="width:100%;"><?php echo $gm_res[0]->org_name.' '.$gm_res[0]->team_name; ?></span>
                              </div>
                              <div class="grid-x small-3 medium-3 large-3 align-center">
                                <span class="small-padding-right small-12 medium-12 large-12" <?php echo $gm_result_color; ?>><?php echo '('.$gm_res[0]->game_result.')'; ?></span>
                                <button class="buttonization small-12 medium-12 large-12" style="max-width:100px; font-size:11px;padding:10px;max-height:35px" onClick="pl_box_score(this)" data-select-year="<?php echo $arg[1] ?>" data-event-name="<?php echo $game_stat_list->event_name ?>" data-game-id="<?php echo $game_stat_list->game_id ?>" data-team-id="<?php echo $game_stat_list->team_id ?>" data-url="<?php echo get_site_url(); ?>"> Box Score</button>
                              </div>
                              <div class="grid-x small-4 medium-4 large-4">
                                <span class="large-8 end"><?php echo $gm_res[0]->opp_name; ?></span>
                              </div>
                            </div>
                            <div class="opp_logo_box"><!-- hide-for-small-only -->
                              <a class="flex align-middle" href="" target="_blank"><img style="height:100px;width:125px;z-index:0;" src="<?php echo (!empty($gm_res[0]->opp_logo) ? $gm_res[0]->opp_logo != "NULL" ? $org_logo.$gm_res[0]->opp_logo : $placeholder_img : $placeholder_img); ?>">
                              </a>
                            </div>
                          </div>
                          <?php else: echo ('<p>'.g365_message()['gm_result'].'</p>'); endif; ?>
                        </td>
                        <td><?php echo (!empty($game_stat_data->pts)?$game_stat_data->pts:'0'); ?></td>
                        <td><?php echo (!empty($game_stat_data->rbs)?$game_stat_data->rbs:'0'); ?></td>
                        <td><?php echo (!empty($game_stat_data->ast)?$game_stat_data->ast:'0'); ?></td>
                        <td><?php echo (!empty($game_stat_data->blk)?$game_stat_data->blk:'0'); ?></td>
                        <td><?php echo (!empty($game_stat_data->stl)?$game_stat_data->stl:'0'); ?></td>
                        <td><?php echo (!empty($game_stat_data->three_pt)?$game_stat_data->three_pt:'0'); ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
        </div>
      </div>
      <?php //endif;/*endif-1*/ ?>
    </div>
    <?php endforeach; endif; /*endif sgs*/ endif; if(empty($game_stats) && $arg[0] < 2): echo pp_pay_link($arg[1])[0]; endif; break; case 'camp': g365_dir_render('player-profile', 'game-stat', $player_id, $arg = array(2, $arg[1], 'camp', $player_id)); break; //case 'scholastic': g365_dir_render('player-profile', 'game-stat', $player_id, $arg = array(4, $arg[1], 'scholastic')); break; 
                                                                } ?>
</div>
<?php echo page_loader('game-stat-form', 'season-stats-group', 'game-stat'); ?>