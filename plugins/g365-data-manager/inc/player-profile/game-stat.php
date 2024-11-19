<div id="profile-games" class="cell small-12">
  <?php global $wp_query; $ev_id = $wp_query->query_vars['pl_tp']; $game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]); if(!empty($game_stats)): ?>
  <h2 class="text-center">Game Stats</h2>
    <ul class="tabs separate small-margin-left small-margin-bottom small-up-2 medium-up-3 small-12 medium-12 large-12 text-center grid-x" id="season-stats" data-tabs data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge="500" data-active-collapse="true">
    <?php foreach($game_stats as $game_stat):
      !empty( pl_stat_season_options(array($player_id))[4]['yearly_subscription'] ) ? $yearly_subscription = pl_stat_season_options(array($player_id))[4]['yearly_subscription'] : $yearly_subscription = array();
      if( (in_array($arg[1], $yearly_subscription )) || (g365_passport_validation('subscription-validation', ['selected_year'=>$arg[1], 'pp_data'=>pl_stat_season_options(array($player_id))[4]]) == true) ):/*if-unlocked-pp*/ ?>
      <li class="tabs-title cell">
        <a onclick="ev_form_submit(this)" id="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $arg[2]), 'tc-camp-stat') ?>" href="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $arg[2]), 'tc-camp-stat') ?>" class="profile-title block">
          <?php echo $game_stat->event_name; ?>
        </a>
      </li>
      <?php else: if(current_user_can('administrator')): echo ('<div class="fi-lock pl_lock_message large-12">'.g365_message()['pp_admin'].'</div>'); ?>
      <li class="tabs-title cell">
        <a onclick="ev_form_submit(this)" id="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $arg[2]), 'tc-camp-stat') ?>" href="<?php echo g365_url_linkage(array($game_stat->player_nickname, $game_stat->event_id, $game_stat->event_name, false, $arg[1], $arg[2]), 'tc-camp-stat') ?>" class="profile-title block">
          <?php echo $game_stat->event_name; ?>
        </a>
      </li>
      <?php else: ?>
        <li class="tabs-title cell event-unlock__trigger small-margin-bottom">
          <a class="fi-lock ev_locked"> <?php echo $game_stat->event_name; ?></a>
        </li>
      <?php echo pp_pay_link($arg[1])[0]; endif; endif;/*endif-paid-event*/ endforeach; ?>
    </ul>
    <div class="tabs-content small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
      <?php //if(!empty($event_id) && ($event_id == $game_stat->event_id)):/*if-1*/ ?>
      <?php $simp_game_stats = game_stat_filter_sh($player_id, $ev_id, $is_only_event = true, $arg[1], $arg[0]); foreach($simp_game_stats as $game_stat): $event_game_avg = avg_game_stat($player_id, $game_stat->event_id); /*foreach-1*/ ?>
      <div class="tabs-panel" id="<?php echo g365_url_linkage(array('', '', $game_stat->event_name, true, $arg[1], $arg[2]), 'tc-camp-stat') ?>">
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
              <div class="table-scroll">  
                <table class="text-center ghost-white-bg no-margin-bottom">
                  <tbody class="">
                    <tr class="stats__table--playerGames">
                      <th>Date/Court</th>
                      <th>PTS</th>
                      <th>REB</th>
                      <th>AST</th>
                      <th>BLK</th>
                      <th>STL</th>
                      <th>3PT</th>
                    </tr>
                    <?php $game_stat_lists = (g365_pl_game_stat($player_id, $game_stat->event_id, $is_only_event = false, $arg[1], $exception = null)); foreach($game_stat_lists as $index => $game_stat_list): $game_stat_data = json_decode($game_stat_list->stats); ?>
                    <tr class="color-body emphasis">                                 
                      <td><?php echo date_format(date_create($game_stat_list->game_date_time), 'M d Y g:i A') ." : ". $game_stat_list->game_court; ?></td>
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
      </div>
      <?php //endif;/*endif-1*/ ?>
    </div>
  <?php endforeach; /*endforeach-1*/ else: ?>
    <div>
      <h2 class="text-center">Game Stats</h2>
      <p><?php echo g365_message()['p_ev_stat']; ?></p>
    </div>
  <?php endif; if(empty($game_stats) && $arg[0] < 2): ?>
  <div>
    <h2 class="text-center">Game Stats</h2>
    <p><?php echo g365_message()['p_ev_stat']; ?></p>
  </div>
  <?php endif; ?>
</div>
<?php echo page_loader('game-stat-form', 'season-stats-group', 'camp-stat'); ?>