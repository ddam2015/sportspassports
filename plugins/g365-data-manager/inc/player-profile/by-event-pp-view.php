<div id="dialong_div"></div>
<div id="profile-games" class="cell small-12">
  <?php $event_id = url_param('event_id'); $game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]); if(!empty($game_stats)): ?>
  <h2 class="text-center">Game Stats</h2>
  <ul class="tabs separate small-margin-left small-margin-bottom small-up-2 medium-up-3 small-12 medium-12 large-12 text-center grid-x" id="season-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
    <?php foreach($game_stats as $game_stat): if( (in_array($game_stat->event_id, $arg[3])) || (in_array($game_stat->event_id, pp_ev_exception())) ):/*if-paid-event*/ ?>
    <li class="tabs-title cell">
      <a onclick="ev_form_submit(this)" id="<?php echo get_site_url(); ?>/player/<?php echo $game_stat->player_nickname ?>/stats/?event_id=<?php echo $game_stat->event_id ?>#gamestat<?php echo preg_replace('/\s+|\.|-/', '', $game_stat->event_id); ?>" href="<?php echo get_site_url(); ?>/player/<?php echo $game_stat->player_nickname ?>/stats/?event_id=<?php echo $game_stat->event_id ?>#gamestat<?php echo preg_replace('/\s+|\.|-/', '', $game_stat->event_id); ?>" class="profile-title block">
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
  <?php $simp_game_stats = game_stat_filter_sh($player_id, $event_id, $is_only_event = true, $arg[1], $arg[0]); foreach($simp_game_stats as $game_stat): $event_game_avg = avg_game_stat($player_id, $event_id); ?>
    <?php //if(!empty($event_id) && ($event_id == $game_stat->event_id)):/*if-1*/ ?>
      <div class="tabs-panel" id="gamestat<?php echo preg_replace('/\s+|\.|-/', '', $event_id); ?>">
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
            <?php $placeholder_img = $arg[4]; $org_logo = $arg[5]; echo cts_st_tb(null,4)[0]; $game_stat_lists = (g365_pl_game_stat($player_id, $game_stat->event_id, $is_only_event = false, $arg[1], $exception = null)); foreach(pl_gm_st_tb(array($game_stat_lists, $arg[1], $placeholder_img, $org_logo)) as $box_score){ echo $box_score; } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php //endif;/*endif-1*/ ?>
    </div>
    <?php endforeach; endif; if(empty($game_stats) && $arg[0] < 2): ?>
  <div>
    <h2 class="text-center">Game Stats</h2>
    <p><?php echo g365_message()['p_ev_stat']; ?></p>
  </div>
  <?php endif; ?>
</div>
<?php echo page_loader('game-stat-form', 'season-stats-group', 'game-stat'); echo (cts_dialog_js());?>