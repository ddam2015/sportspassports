<div id="profile-games" class="cell small-12">
  <?php $game_stats = game_stat_filter($player_id, null, $is_only_event = true, $arg[1], $arg[0]); if(!empty($game_stats)): ?>
    <h6>Game Averages</h6>
    <?php foreach($game_stats as $game_stat): ?>
      <div class="tabs-content small-12 medium-12 large-12">
        <?php $simp_game_stats = game_stat_filter_sh($player_id, $game_stat->event_id, $is_only_event = true, $arg[1], $arg[0]); foreach($simp_game_stats as $game_stat): $event_game_avg = avg_game_stat($player_id, $game_stat->event_id); /*foreach-1*/ ?>
        <div class="form__border">
          <div class="info-block">
            <div class="grid-x grid-margin-x">
              <div class="cell small-12">
                <div class="cell small-12">
                   <h6 class="small-padding-top small-padding-left"><?php echo $game_stat->event_name; ?></h6>
                </div>
                <div class="ave_field small-margin-bottom table-scroll">
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
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; /*endforeach-1*/ ?>
    <?php endforeach; ?>
  <?php endif; if(empty($game_stats) && $arg[0] < 2): ?>
  <div>
    <h2 class="text-center">Game Stats</h2>
    <p><?php echo g365_message()['p_ev_stat']; ?></p>
  </div>
  <?php endif; ?>
</div>
<?php //echo page_loader('game-stat-form', 'season-stats-group', 'game-stat'); ?>