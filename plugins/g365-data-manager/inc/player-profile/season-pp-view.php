<!-- /** Game Stats **/ -->
<?php $placeholder_img = $arg[5]; $org_logo = $arg[6]; global $wp_query; empty($wp_query->query_vars['st_type']) ? $stat_type = 'tournament': $stat_type = $wp_query->query_vars['st_type']; switch($wp_query->query_vars['st_type']){ case '': case 'tournament': $event_id = $wp_query->query_vars['pl_tp'];
if( (g365_passport_validation('subscription-validation', ['selected_year'=>$arg[1], 'pp_data'=>$arg[7]])) == 'true' ): /*IN*/ g365_dir_render('player-profile', 'current-season-avg', $player_id, array($arg[1], 1));
?>
   <div id="dialong_div"></div>
   <div id="dialong_result_box_div"></div>
   <div id="profile-games" class="cell small-12">
      <?php $game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]); if(!empty($game_stats)): ?>
      <h2 class="text-center">Game Stats</h2>
        <ul class="tabs separate small-margin-left small-margin-bottom small-up-2 medium-up-3 small-12 medium-12 large-12 text-center grid-x" id="season-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
        <?php foreach($game_stats as $game_stat): ?>
          <li class="tabs-title cell">
            <a onclick="ev_form_submit(this)" id="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" href="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" class="profile-title block">
              <?php echo $game_stat->event_name; ?>
            </a>
          </li>
          <?php $nickname = $game_stat->player_nickname; $pl_id = $game_stat->player_id; endforeach; ?>
        </ul>
        <div class="tabs-content small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
          <?php //if(!empty($event_id) && ($event_id == $game_stat->event_id)):/*if-1*/ ?>
          <?php 
            $simp_game_stats = game_stat_filter_sh($player_id, $event_id, $is_only_event = true, $arg[1], $arg[0]);
            if(!empty($simp_game_stats)): /*if-sgs*/
              foreach($simp_game_stats as $game_stat): $event_game_avg = avg_game_stat($player_id, $event_id); /*foreach-1*/ ?>
          <div class="tabs-panel is-active" id="<?php echo g365_url_linkage(array('', '', $game_stat->event_name, true, $arg[1], $stat_type), 'tc-tournament-stat') ?>">
            <div class="info-block">
              <div class="grid-x grid-margin-x">
                <div class="cell small-12">
                  <div style="text-align: center;">
                  
                    <?php } ?>
                    <br>
                  </div>
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
                    <?php echo cts_st_tb(null,4)[0]; $game_stat_lists = (g365_pl_game_stat($player_id, $game_stat->event_id, $is_only_event = false, $arg[1], $exception = null)); foreach(pl_gm_st_tb(array($game_stat_lists, $arg[1], $placeholder_img, $org_logo)) as $box_score){ echo $box_score; } ?>
                    </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; /*endforeach-1*/ endif; /*endif-sgs*/ endif; if(empty($game_stats) && $arg[0] < 2): ?>
      <div>
        <h2 class="text-center">Game Stats</h2>
        <p><?php echo g365_message()['p_ev_stat']; ?></p>
      </div>
      <?php endif; ?>
  </div>
<?php endif; if( (g365_passport_validation('subscription-validation', ['selected_year'=>$arg[1], 'pp_data'=>$arg[7]])) != 'true' ): /*NOT IN*/ ?>
  <div id="profile-games" class="cell small-12">
    <?php echo pp_pay_link($arg[1])[1]?>
    <?php $game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]); if(!empty($game_stats)): echo blur_box($arg[1]); ?>
    <h2 class="text-center">Game Stats</h2>
    <ul class="tabs separate small-margin-left small-margin-bottom small-up-2 medium-up-3 small-12 medium-12 large-12 text-center grid-x" id="season-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
      <?php echo monthly_subscription_frontend($game_stats, $arg[7], $arg, $stat_type); ?>
    </ul>
    <?php 
      $simp_game_stats = game_stat_filter_sh($player_id, $event_id, $is_only_event = true, $arg[1], $arg[0]);
      if(!empty($simp_game_stats)): /*if-sgs*/
        foreach($simp_game_stats as $game_stat): $event_game_avg = avg_game_stat($player_id, $event_id); /*foreach-1*/ ?>
          <div class="tabs-panel is-active" id="<?php echo g365_url_linkage(array('', '', $game_stat->event_name, true, $arg[1], $stat_type), 'tc-tournament-stat') ?>">
            <div class="info-block">
              <div class="grid-x grid-margin-x">
                <div class="cell small-12">
                  <div style="text-align: center;">
                  <?php if( strpos( site_url(), get_site_url() ) !== false or isset($_GET['flag-player-card'])){ ?>
                    <button class="generate-player" onclick="pl_card(this)" id="<?php echo g365_url_linkage(array($nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" href="<?php echo g365_url_linkage(array($nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $stat_type), 'tc-tournament-stat') ?>" data-event-id="<?php echo $event_id; ?>" data-pl-id="<?php echo $player_id; ?>" data-url="<?php echo home_url(); ?>">Player Event Card</button>
                    <?php } ?>
                    <br>
                  </div>
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
                    <?php echo cts_st_tb(null,4)[0]; $game_stat_lists = (g365_pl_game_stat($player_id, $game_stat->event_id, $is_only_event = false, $arg[1], $exception = null)); foreach(pl_gm_st_tb(array($game_stat_lists, $arg[1], $placeholder_img, $org_logo)) as $box_score){ echo $box_score; } ?>
                    </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; /*endforeach-1*/ endif; /*endif-sgs*/ ?>
  </div>
<?php else: echo pp_pay_link($arg[1])[0]; endif; endif; break; case 'camp': g365_dir_render('player-profile', 'game-stat', $player_id, $arg = array(2, $arg[1], 'camp')); break; case 'scholastic': g365_dir_render('player-profile', 'game-stat', $player_id, $arg = array(4, $arg[1], 'scholastic')); } echo page_loader('game-stat-form', 'season-stats-group', 'game-stat'); echo (cts_dialog_js()); ?>