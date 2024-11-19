<div class="tabs-content small-12 medium-12 large-12" id="season-stats-group" data-tabs-content="season-stats">
  <?php 
    if(!empty($arg[0])): /*if-sgs*/
      foreach($arg[0] as $game_stat): $event_game_avg = avg_game_stat($arg[1], $arg[2]); /*foreach-1*/ ?>
        <div class="tabs-panel is-active" id="<?php echo $arg[3]; ?>">
          <div class="info-block">
            <div class="grid-x grid-margin-x">
              <div class="cell small-12">
                <div style="text-align: center;">
                <?php if( strpos( site_url(), get_site_url() ) !== false or isset($_GET['flag-player-card'])){ ?>
                  <button class="generate-player" onclick="pl_card(this)" id="<?php $arg[4] ?>" href="<?php $arg[4] ?>" data-event-id="<?php echo $arg[2]; ?>" data-pl-id="<?php echo $arg[5]; ?>" data-url="<?php echo home_url(); ?>">Player Event Card</button>
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
                  <?php echo cts_st_tb(null,4)[0]; $game_stat_lists = (g365_pl_game_stat($arg[1], $game_stat->event_id, $is_only_event = false, $arg[6], $exception = null)); foreach(pl_gm_st_tb(array($game_stat_lists, $arg[6], $arg[7], $arg[8])) as $box_score){ echo $box_score; } ?>
                  </tbody>
                </table>
                </div>
              </div>
            </div>
          </div>
        </div>
  <?php endforeach; /*endforeach-1*/ endif; /*endif-sgs*/ ?>
</div>