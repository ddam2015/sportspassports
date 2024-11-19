<?php
$badges_operator_header = 
  <<<EOD
    <tr class="bdg_header">
      <th>Order</th>
      <th>Note/Edit</th>
      <th>Website</th>
      <th>Season</th>
      <th>Event</th>
      <th>Game</th>
      <th>Trophy</th>
      <th>Point</th>
      <th>Rebound</th>
      <th>Assist</th>
      <th>Steal</th>
      <th>Block</th>
      <th>3-Point Made</th>
      <th>Badge Name</th>
      <th>Badge Logo</th>
    </tr>
EOD;
$badges_operator_tr = 
  <<<EOD
    <tr id="badge-{{badge_id}}" class="bdg_rows"><td style="vertical-align:top">Text Field<textarea id="bdg_note_{{badge_id}}" name="" placeholder="Your note.">{{note_field}}</textarea><button class="button alert" onclick="delete_badge(this)" id="rm-badge-{{badge_id}}" style="color:#fff;">Delete</button><button class="button success" onclick="sigle_save_badge(this)" id="update-badge-{{badge_id}}" style="color:#fff;">Update</button></td><td style="vertical-align:top; min-width:90px;"><input type="checkbox" id="bdg_g365" name="bdg_g365" value="3191" {{3191}}><label>G365</label><br><input type="checkbox" id="bdg_ogp" name="bdg_ogp" value="1" {{1}}><label>OGP</label><br><input type="checkbox" id="bdg_ebc" name="bdg_ebc" value="2" {{2}}><label>EBC</label><br><input type="checkbox" id="bdg_tsc" name="bdg_tsc" value="3" {{3}}><label>TSC</label><br><input type="checkbox" id="bdg_hhh" name="bdg_hhh" value="7164" {{7164}}><label>HHH</label><br><input type="checkbox" id="bdg_scs" name="bdg_scs" value="7165" {{7165}}><label>SCS</label><br></td><td style="vertical-align:top" class="season_options"><div class="small-4 large-4">Operator<select id="season_ops"><option>N/A</option><option {{equal_selected_season}}>=</option><option {{greater_selected_season}}>></option><option {{lesser_selected_season}}><</option><option {{goe_selected_season}}>>=</option><option {{loe_selected_season}}><=</option></select></div><div class="small-4 large-4">Value<input id="bdg_season_op_val" type="text" placeholder="1, 2, 3 etc." value="{{season_value}}"><div class="small-4 large-4">Season Year<input id="bdg_season_val" type="text" placeholder="2021, 2022 etc." value="{{season_year}}"></div></div></td><td style="vertical-align:top" class="event_options"><div class="small-4 large-4">Operator<select id="event_ops"><option>N/A</option><option {{equal_selected_event}}>=</option><option {{greater_selected_event}}>></option><option {{lesser_selected_event}}><</option><option {{goe_selected_event}}>>=</option><option {{loe_selected_event}}><=</option></select></div><div class="small-4 large-4">Value<input id="bdg_event_op_val" type="text" placeholder="1, 2, 3 etc." value="{{event_value}}"></div><div class="small-4 large-4">Individual Event<select id="event_indi_ev"><option>N/A</option><option value="cumulative_individual_event" {{cumulative_individual_event}}>Cumulative by event</option><option value="cumulative_event_year" {{cumulative_event_year}}>Cumulative by year</option><option value="indi_gm_indi_event" {{indi_gm_indi_event}}>Individual game by event</option><option value="avg_cond_indi_event" {{avg_cond_indi_event}}>Average by event</option></select></div></td><td style="vertical-align:top" class="game_options"><div class="small-6 large-6">Operator<select id="game_ops"><option>N/A</option><option {{equal_selected_game}}>=</option><option {{greater_selected_game}}>></option><option {{lesser_selected_game}}><</option><option {{goe_selected_game}}>>=</option><option {{loe_selected_game}}><=</option></select></div><div class="small-6 large-6">Value<input id="bdg_game_op_val" type="text" placeholder="1, 2, 3 etc." value="{{game_value}}"></div></td><td style="vertical-align:top" class="trophy_options"><div class="small-6 large-6">Operator<select id="trophy_ops"><option>N/A</option><option {{equal_selected_trophy}}>=</option><option {{greater_selected_trophy}}>></option><option {{lesser_selected_trophy}}><</option><option {{goe_selected_trophy}}>>=</option><option {{loe_selected_trophy}}><=</option></select></div><div class="small-6 large-6">Value<input id="bdg_trophy_op_val" id="trophy_value" type="text" placeholder="1, 2, 3 etc." value="{{trophy_value}}"></div></td><td style="vertical-align:top" class="stat_options"><div class="small-4 large-4">Type<select id="pts_type"><option>N/A</option><option id="pts_total" value="total" {{pts_total}}>Total</option><option id="pts_average" value="average" {{pts_average}}>Average</option><option id="pts_individual_game" value="individual_game" {{pts_individual_game}}>Individual Game</option></select></div><div class="small-4 large-4">Operator<select id="pts_ops"><option>N/A</option><option {{equal_selected_pts}}>=</option><option {{greater_selected_pts}}>></option><option {{lesser_selected_pts}}><</option><option {{goe_selected_pts}}>>=</option><option {{loe_selected_pts}}><=</option></select></div><div class="small-4 large-4">Value<input id="pts_value" type="text" placeholder="1, 2, 3 etc." value="{{pts_value}}"></div></td><td style="vertical-align:top" class="stat_options"><div class="small-4 large-4">Type<select id="reb_type"><option>N/A</option><option id="reb_total" value="total"{{reb_total}}>Total</option><option id="reb_average" value="average" {{reb_average}}>Average</option><option id="reb_individual_game" value="individual_game" {{reb_individual_game}}>Individual Game</option></select></div><div class="small-4 large-4">Operator<select id="reb_ops"><option>N/A</option><option {{equal_selected_reb}}>=</option><option {{greater_selected_reb}}>></option><option {{lesser_selected_reb}}><</option><option {{goe_selected_reb}}>>=</option><option {{loe_selected_reb}}><=</option></select></div>Value<input id="reb_value" type="text" placeholder="1, 2, 3 etc." value="{{reb_value}}"></div></td><td style="vertical-align:top" class="stat_options"><div class="small-4 large-4">Type<select id="ast_type"><option>N/A</option><option id="ast_total" value="total" {{ast_total}}>Total</option><option id="ast_average" value="average" {{ast_average}}>Average</option><option id="ast_individual_game" value="individual_game" {{ast_individual_game}}>Individual Game</option></select></div><div class="small-4 large-4">Operator<select id="ast_ops"><option>N/A</option><option {{equal_selected_ast}}>=</option><option {{greater_selected_ast}}>></option><option {{lesser_selected_ast}}><</option><option {{goe_selected_ast}}>>=</option><option {{loe_selected_ast}}><=</option></select></div><div class="small-4 large-4">Value<input id="ast_value" type="text" placeholder="1, 2, 3 etc." value="{{ast_value}}"></div></td><td style="vertical-align:top" class="stat_options"><div class="small-4 large-4">Type<select id="stl_type"><option>N/A</option><option id="stl_total" value="total"{{stl_total}}>Total</option><option id="stl_average" value="average" {{stl_average}}>Average</option><option id="stl_individual_game" value="individual_game" {{stl_individual_game}}>Individual Game</option></select></div><div class="small-4 large-4">Operator<select id="stl_ops"><option>N/A</option><option {{equal_selected_stl}}>=</option><option {{greater_selected_stl}}>></option><option {{lesser_selected_stl}}><</option><option {{goe_selected_stl}}>>=</option><option {{loe_selected_stl}}><=</option></select></div><div class="small-4 large-4">Value<input id="stl_value" type="text" placeholder="1, 2, 3 etc." value="{{stl_value}}"></div></td><td style="vertical-align:top" class="stat_options"><div class="small-4 large-4">Type<select id="blk_type"><option>N/A</option><option id="blk_total" value="total"{{blk_total}}>Total</option><option id="blk_average" value="average" {{blk_average}}>Average</option><option id="blk_individual_game" value="individual_game" {{blk_individual_game}}>Individual Game</option></select></div><div class="small-4 large-4">Operator<select id="blk_ops"><option>N/A</option><option {{equal_selected_blk}}>=</option><option {{greater_selected_blk}}>></option><option {{lesser_selected_blk}}><</option><option {{goe_selected_blk}}>>=</option><option {{loe_selected_blk}}><=</option></select></div><div class="small-4 large-4">Value<input id="blk_value" type="text" placeholder="1, 2, 3 etc." value="{{blk_value}}"></div></td><td style="vertical-align:top" class="stat_options"><div class="small-4 large-4">Type<select id="three_pt_type"><option>N/A</option><option id="three_pt_total" value="total"{{three_pt_total}}>Total</option><option id="three_pt_average" value="average" {{three_pt_average}}>Average</option><option id="three_pt_individual_game" value="individual_game" {{three_pt_individual_game}}>Individual Game</option></select></div><div class="small-4 large-4">Operator<select id="three_pt_ops"><option>N/A</option><option {{equal_selected_three_pt}}>=</option><option {{greater_selected_three_pt}}>></option><option {{lesser_selected_three_pt}}><</option><option {{goe_selected_three_pt}}>>=</option><option {{loe_selected_three_pt}}><=</option></select></div><div class="small-4 large-4">Value<input id="three_pt_value" type="text" placeholder="1, 2, 3 etc." value="{{three_pt_value}}"></div></td></td><td style="vertical-align:top">Option<select class="bdg_name">{{badge_option}}</select></td><td style="vertical-align:top">{{badge_url}}</td></tr>
  EOD;
$badges_player_table_header = 
  <<<EOD
    <tr class="bdg_header"><th>Order</th><th>Note/Edit</th><th>Badge Type</th><th>Badge Name</th><th>Badge Logo</th></tr>
  EOD;
$admin_player_bdg_table_tr = 
  <<<EOD
    <tr id="badge-{{badge_id}}" class="bdg_rows"><td style="vertical-align:top"><textarea id="bdg_note_{{badge_id}}" name="" placeholder="Your note.">{{note_field}}</textarea><button class="button alert" onclick="delete_badge(this)" id="rm-badge-{{badge_id}}" style="color:#fff;">Delete</button></td><td style="vertical-align:top"><select id="event_indi_ev"><option>N/A</option><option value="cumulative_individual_event" {{cumulative_individual_event}}>Cumulative by event</option><option value="cumulative_event_year" {{cumulative_event_year}}>Cumulative by year</option><option value="indi_gm_indi_event" {{indi_gm_indi_event}}>Individual game by event</option><option value="avg_cond_indi_event" {{avg_cond_indi_event}}>Average by event</option></select></td><td style="vertical-align:top"><select class="bdg_name">{{badge_option}}</select></td><td style="vertical-align:top">{{badge_url}}</td></tr>
  EOD;
$admin_player_saved_bdg_table_tr = 
  <<<EOD
    <tr id="badge-{{badge_id}}" class="bdg_rows player_bdg"><td style="vertical-align:top"><textarea id="bdg_note_{{badge_id}}" name="" placeholder="Your note.">{{note_field}}</textarea></td><td style="vertical-align:top"><select id="event_indi_ev"><option>N/A</option><option value="cumulative_individual_event" {{cumulative_individual_event}}>Cumulative by event</option><option value="cumulative_event_year" {{cumulative_event_year}}>Cumulative by year</option><option value="indi_gm_indi_event" {{indi_gm_indi_event}}>Individual game by event</option><option value="avg_cond_indi_event" {{avg_cond_indi_event}}>Average by event</option></select></td><td style="vertical-align:top"><select class="bdg_name">{{badge_option}}</select></td><td style="vertical-align:top">{{badge_url}}</td></tr>
  EOD;
?>