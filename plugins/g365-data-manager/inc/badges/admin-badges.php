<?php include_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/g365-data-manager/js/form-templates/g365_admin_badge_form.php';
$url = get_site_url().$_SERVER['REQUEST_URI']; $submenu_type = url_param('submenu'); if(!empty($submenu_type)){ $url = substr($url, 0, strpos($url, '&')); } 
echo g365_submenu_type(['admin_bdg'=>$url, 'type'=>$submenu_type], 10);
switch($submenu_type){
  case '':
  case 'default-badge':
    $saved_badge = g365_badges('saved-badge', []); if(empty($saved_badge)): ?>
    <table id="bd_op_files"><?php echo ( $badges_operator_header . bind_to_template($replacements, $badges_operator_tr, []) ); ?></table>
    <?php else: ?>
    <table id="bd_op_files">
      <?php
      echo $badges_operator_header; 
      foreach($saved_badge as $saved_badge_data):
        $bdg_website = json_decode($saved_badge_data->website, true);
        $bdg_season = json_decode($saved_badge_data->season, true);
        $bdg_event = json_decode($saved_badge_data->event, true);
        $bdg_game = json_decode($saved_badge_data->game, true);
        $bdg_trophy = json_decode($saved_badge_data->trophy, true);
        $bdg_pts = json_decode($saved_badge_data->point, true);
        $bdg_reb = json_decode($saved_badge_data->rebound, true);
        $bdg_ast = json_decode($saved_badge_data->assist, true);
        $bdg_stl = json_decode($saved_badge_data->steal, true);
        $bdg_blk = json_decode($saved_badge_data->block, true);
        $bdg_three_pt = json_decode($saved_badge_data->three_pt, true);
        $bdg_name = $saved_badge_data->badge_name;
        $bdg_url = $saved_badge_data->badge_url;
        $bdg_admin = $saved_badge_data->admin_addition;
//         echo "<pre>"; print_r($bdg_reb['operator']); echo "</pre>";
      echo bind_to_template($replacements, $badges_operator_tr, ['badge_id'=>$saved_badge_data->id, 'catagory_fields'=>$bdg_season, 'note_field'=>$saved_badge_data->note, 'website_array'=>$bdg_website['value'], 'op_season'=>$bdg_season['operator'], 'season_value'=>$bdg_season['value'], 'season_year'=>$bdg_season['season_year'], 'event_value'=>$bdg_event['value'], 'op_event'=>$bdg_event['operator'], 'cumulative_individual_event'=>$bdg_event['indi_val'], 'indi_gm_indi_event'=>$bdg_event['indi_val'], 'avg_cond_indi_event'=>$bdg_event['indi_val'], 'cumulative_event_year'=>$bdg_event['indi_val'], 'game_value'=>$bdg_game['value'], 'op_game'=>$bdg_game['operator'], 'trophy_value'=>$bdg_trophy['value'], 'op_trophy'=>$bdg_trophy['operator'], 'pts_value'=>$bdg_pts['value'], 'op_pts'=>$bdg_pts['operator'], 'type_pts'=>$bdg_pts['type'], 'reb_value'=>$bdg_reb['value'], 'op_reb'=>$bdg_reb['operator'], 'type_reb'=>$bdg_reb['type'], 'ast_value'=>$bdg_ast['value'], 'op_ast'=>$bdg_ast['operator'], 'type_ast'=>$bdg_ast['type'], 'stl_value'=>$bdg_stl['value'], 'op_stl'=>$bdg_stl['operator'], 'type_stl'=>$bdg_stl['type'], 'blk_value'=>$bdg_blk['value'], 'op_blk'=>$bdg_blk['operator'], 'type_blk'=>$bdg_blk['type'], 'three_pt_value'=>$bdg_three_pt['value'], 'op_three_pt'=>$bdg_three_pt['operator'], 'type_three_pt'=>$bdg_three_pt['type'], 'badge_name'=>$bdg_name, 'badge_url'=>$bdg_url, 'badge_admin'=>$bdg_admin, 'get_badge'=>$player_badge_type]);
      endforeach;
      ?>
    </table>
    <?php endif; ?>
    <button class="button primary" id="add_bd_btn" onclick="add_new_badge_row()">Add New Badge</button>
    <button class="button success" id="save_bd_btn" onclick="save_badge_rows()">Save Badges</button>
    <button class="button warning" id="update_bd_btn" onclick="global_badge_update()">Global Update</button>
<?php
    break;
  case 'manual-badge':
    $pl_id = url_param('pl_id'); !empty($pl_id) ? $pl_data = g365_get_pl_data(['pl_id'=>$pl_id], 'g365-pl-data') : $pl_data = '';
    echo ('<h3 class="small-12 large-12 small-padding-bottom" style="max-width: 1400px; margin:0 auto;">'. $pl_data[0]->name . ' Achievement Badges</h3>');
?>
    <div class="cell large-6" style="max-width:1400px; margin:0 auto;"><input type="text" class="search-hero g365_livesearch_input" data-g365_type="player_badge" placeholder="Enter Player Name" autocomplete="off" autofocus></div>
<?php if(!empty($pl_data)): /*if-pl_id*/ ?>
  <div class="grid-x small-up-2 medium-up-4" style="display: flex; max-width: 1400px; margin:0 auto;">
    <input type="hidden" id="pl_id_to_save" value="<?php echo $pl_id; ?>">
  <?php    
      $player_badges = g365_badges('admin-player-badge', ['pl_id'=>$pl_id]);
      if(!empty($player_badges)):
          echo '<table id="bd_op_files">';
          echo $badges_player_table_header;
          foreach($player_badges as $player_badge):
            if($player_badge->admin_addition == '1'){ $player_badge_row = $admin_player_bdg_table_tr; }else{ $player_badge_row = $admin_player_saved_bdg_table_tr; }
            echo bind_to_template($replacements, $player_badge_row, ['badge_id'=>$player_badge->badge_id, 'badge_url'=>$player_badge->badge_url, 'note_field'=>$player_badge->note, 'badge_name'=>$player_badge->badge_name, 'cumulative_individual_event'=>$player_badge->badge_type, 'cumulative_event_year'=>$player_badge->badge_type, 'indi_gm_indi_event'=>$player_badge->badge_type, 'avg_cond_indi_event'=>$player_badge->badge_type]);
          endforeach;
        echo '</table>';
        echo '<button class="button primary" id="add_bd_btn" onclick="add_new_badge_row()">Add New Badge</button><button class="button success" id="save_player_badges" onclick="save_player_badges()">Save Badges</button>';
      else: echo ('<table id="bd_op_files">'. $badges_player_table_header . bind_to_template($replacements, $badges_admin_player_table_tr, []).'</table>'); echo '<button class="button primary" id="add_bd_btn" onclick="add_new_badge_row()">Add New Badge</button><button class="button success" id="save_player_badges" onclick="save_player_badges()">Save Badges</button>'; endif;
  ?>
  </div>
<?php endif; /*endif-pl_id*/
    break;
}
echo g365_custom_js('badges');