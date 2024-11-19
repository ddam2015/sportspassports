<?php
$roster_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$roster_registration_form_min = <<<EOD
<div id="{{field-set-id}}" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}_fieldset" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters" data-target_field="{{field-set-id-origin}}">
    <div><div><a class="site-close-button remove-button site-button button">cancel</a></div></div>
    <h3 class="change-title" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_names|#{{field-set-id}}_level|#{{field-set-id}}_name">{{org_name}} {{level}} {{name}}</h3>
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" id="{{field-set-id}}_event_divisions" value="{{event_divisions}}" data-g365_contingent="{{field-set-id}}_division_contingent" data-g365_immutable="true">
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team <span class="req">*</span></label>
      <input type="hidden" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{org_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_id" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Organization Name" autocomplete="off" required>
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="{{field-set-id}}_event_names">Event</label>
        <input type="hidden" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event" value="{{event_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_event_names" value="{{event_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_event" data-g365_form_dest="{{field-set-id}}_event_add" data-g365_type="event_names" placeholder="Enter Event Name" autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="{{field-set-id}}_level">Division <span class="req">*</span></label>
        <select name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" data-g365_select="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" required>
          <option value="">-- Please Select</option>
          <option value="8" data-g365_short_name="8U">8U / 2nd Grade</option>
          <option value="9" data-g365_short_name="9U">9U / 3rd Grade</option>
          <option value="10" data-g365_short_name="10U">10U / 4th Grade</option>
          <option value="11" data-g365_short_name="11U">11U / 5th Grade</option>
          <option value="12" data-g365_short_name="12U">12U / 6th Grade</option>
          <option value="13" data-g365_short_name="13U">13U / 7th Grade</option>
          <option value="14" data-g365_short_name="14U">14U / 8th Grade</option>
          <option value="15" data-g365_short_name="Frosh/Soph">15U / Frosh/Soph</option>
          <option value="16" data-g365_short_name="JV">16U / JV</option>
          <option value="17" data-g365_short_name="Varsity">17U / Varsity</option>
        </select>
      </div>
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_names">Team</label>
          <input type="hidden" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team" value="{{team_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_team_names" value="{{team_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_team" data-g365_form_dest="{{field-set-id}}_team_add" data-g365_type="team_names" data-ls_query_lock="{{field-set-id}}_club_id|{{field-set-id}}_level" placeholder="Search for Team..." autocomplete="off">
        </div>
        <div id="{{field-set-id}}_division_contingent">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_divisions">Level <span class="req">*</span></label>
            <select id="{{field-set-id}}_divisions" class="g365_livesearch_input expanded block"></select>
          </div>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Type</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Players</label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_template_new="form_template_min" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{division_selector_lock_type}}|only,id" placeholder="Search for Players..." autocomplete="off">
          <div id="{{field-set-id}}_players_wrapper">
            <div id="{{field-set-id}}_players_wrapper_data"></div>
          </div>
        </div>
      </div>
      <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
      <button type="button" class="site-button button g365-primary-submit no-margin-bottom" value="submit">Add New Roster Data</button>
    </div>
  </form>
</div>
EOD;
$roster_registration_checkbox = <<<EOD
  <div id="{{field-set-id}}_{{id}}" class="g365_form cell small-12 {{status}}">
    <label class="result-title">
      <input data-g365_contingent="{{field-set-id}}_{{id}}_select_contingent" class="" type="checkbox" name="{{field-set-id-flat}}[data][ros_data][{{id}}][id]" value="{{id}}">
      {{team_name}}
    </label>
    <div id="{{field-set-id}}_{{id}}_select_contingent">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="{{field-set-id}}_{{id}}_level">Division <span class="req">*</span></label>
        <select id="{{field-set-id}}_{{id}}_level" name="{{field-set-id-flat}}[data][ros_data][{{id}}][level]" class="select_local level {{field-set-id-flat}}_level" data-g365_contingent="{{field-set-id}}_{{id}}_level_contingent" data-g365_type="team_names" data-g365_select="{{level}}" data-g365_short_name="{{level_name}}" data-g365_load_target="{{field-set-id}}_{{id}}_division_selector" data-g365_additional_target_limit="{{field-set-id}}_{{id}}_level" data-g365_additional_data="{{team_level}}">{{level_selector_options}}</select>
      </div>
      <div id="{{field-set-id}}_{{id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin {{level_selector_options_hide}}">
          <label for="{{field-set-id}}_{{id}}_division_selector">Level <span class="req">*</span></label>
          <select id="{{field-set-id}}_{{id}}_division_selector" name="{{field-set-id-flat}}[data][ros_data][{{id}}][division]">{{division_selector_options}}</select>
        </div>
      </div>
    </div>
  </div>
EOD;
// Submit Rosters to Event - feat in Director Account
$roster_registration_form_min_xl_sl = <<<EOD
<div id="rosters_sl_xl_fieldset" class="callout white small-padding">
  <form id="rosters_sl_xl" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_sl_xl" data-target_field="reload_button">
    <small>{{event_full_name}}</small>
    <h2 style="text-align: center; color: black;">Submit Rosters to Event</h2>
    <div class="submit_steps grid-x align-center align-middle">
      <div class="grid-y align-top active" id="submitFirstStep">
        <p>STEP 1 OF 2 - Select Event & Roster(s)</p>
      </div>
      <div class="grid-y align-top hide" id="submitSecondStep">
        <p>STEP 2 OF 2 - Confirm Roster(s) & Submit to Event</p>
      </div>
    </div>
    <div class="g365_set_default">
      <input type="hidden" name="rosters_sl_xl[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="club_id" name="rosters_sl_xl[data][org]" id="rosters_sl_xl_club_id" value="{{org_id}}" data-g365_contingent="rosters_sl_xl_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
    </div>
    <div id="rosters_sl_xl_club_team_contingent" class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="rosters_sl_xl_event_id">Event</label>
      <input type="hidden" data-g365_data_key="event_id" id="rosters_sl_xl_event_id" name="rosters_sl_xl[data][event]" data-g365_contingent="rosters_sl_xl_event_contingent" data-g365_load_target=".rosters_sl_xl_level" data-g365_short_name="{{event_full_name}}" data-g365_additional_data='{{event_divisions}}' value="{{event_id}}" data-g365_error_target="event_names">
      <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="rosters_sl_xl_event_id" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{event_full_name}}">
    </div>
    <div id="rosters_sl_xl_event_contingent">
      <div id="rosters_sl_xl_teams_wrapper" class="grid-x grid-padding-x active-inactive-rosters">
        {{rosters_enabled}}
      <!--  {{rosters_disabled}}-->
      </div>
    </div>
    <div id="rosters_sl_xl_message" class="small-margin-top form_message hide"></div>
    <div class="submit-roster-btn-container">
      <p class="site-button button--secondary no-margin-bottom"id="submitRostersBack">Back</p>
      <p class="site-button button g365-primary-submit no-margin-bottom" id="checkRostersBtn">Next - Confirm Players on Roster</p>
      <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit" id="submitRostersBtn">Submit Roster(s)</button>
    </div>
  </form>
</div>
EOD;
$roster_registration_form_min_sl = <<<EOD
<div id="rosters_sl_fieldset" class="callout white small-padding">
  <form id="rosters_sl" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_sl" data-target_field="reload_button">
    <small>{{event_full_name}}</small>
    <h3>Add Rosters:</h3>
    <div class="g365_set_default">
      <input type="hidden" name="rosters_sl[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="club_id" name="rosters_sl[data][org]" id="rosters_sl_club_id" value="{{org_id}}" data-g365_contingent="rosters_sl_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <select data-g365_data_key="event_id" name="rosters_sl[data][event]" id="rosters_sl_event_id" class="select_local hide" data-g365_load_target=".rosters_sl_level">
        <option value="{{event_id}}" data-g365_additional_data="{{event_divisions}}">{{event_full_name}}</option>
      </select>
    </div>
    <div id="rosters_sl_club_team_contingent">
      <div id="rosters_sl_teams_wrapper" class="grid-x grid-padding-x active-inactive-rosters">
        {{rosters_enabled}}
        {{rosters_disabled}}
      </div>
    </div>
    <div id="rosters_sl_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add Roster</button>
  </form>
</div>
EOD;
$roster_registration_form_min_admin_sl = <<<EOD
<div id="tournament_roster_admin_fieldset" class="gset small-padding">
  <form id="tournament_roster_admin" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="tournament_roster_admin" data-target_field="reload_button">
    <small class="change-title" data-default_value="" data-g365_change_targets="#tournament_roster_admin_event_id|#tournament_roster_admin_level|#tournament_roster_admin_division_selector" data-g365_change_delimiter="|"></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="tournament_roster_admin" data-default_value="Team Roster" data-g365_change_targets="#tournament_roster_admin_club_id|#tournament_roster_admin_name|#tournament_roster_admin_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="tournament_roster_admin[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="tournament_roster_admin[id]" id="tournament_roster_admin_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="tournament_roster_admin[data][org]" id="tournament_roster_admin_club_id" value="{{org_id}}" data-g365_contingent="tournament_roster_admin_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" id="tournament_roster_admin_division_selector_birth_lock" value="{{division_selector_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="tournament_roster_admin_division_selector_class_lock" value="{{division_selector_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
      <select data-g365_data_key="event_id" name="tournament_roster_admin[data][event]" id="tournament_roster_admin_event_id" class="select_local hide" data-g365_load_target="tournament_roster_admin_level" data-g365_additional_target="tournament_roster_admin_division_selector,tournament_roster_admin_level" data-g365_short_name="{{event_short_name}}">
        <option value="{{event_id}}" data-g365_additional_data="{{event_divisions}}">{{event_full_name}}</option>
      </select>
      <input type="hidden" data-g365_data_key="tournament_roster_admin_level" name="tournament_roster_admin[data][team]" id="tournament_roster_admin_team_selector" value="{{team_id}}" data-g365_short_name="{{team_level_name}}" data-g365_additional_target_limit="tournament_roster_admin_level" data-g365_additional_data="{{team_level}}">
    </div>
    <div id="tournament_roster_admin_club_team_contingent">
      <a class="field-toggle block text-right" data-g365_after="" data-data_capture="tournament_roster_admin_team_name"><span class="field-title"></span><span class="field-button">edit team name</span></a>
      <div class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;">
        <label for="tournament_roster_admin_team_name">Team Name</label>
        <input type="text" name="tournament_roster_admin[data][team_name]" id="tournament_roster_admin_team_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{team_name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_pool_name">Pool Name </label>
        <input type="text" name="tournament_roster_admin[data][pool_name]" id="tournament_roster_admin_pool_name" value="{{pool_name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_pool_number">Pool Number</label>
        <input type="text" name="tournament_roster_admin[data][pool_number]" id="tournament_roster_admin_pool_number" value="{{pool_number}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_team_restrictions">Team Restrictions</label>
        <input type="text" name="tournament_roster_admin[data][team_restrictions]" id="tournament_roster_admin_team_restrictions" value="{{team_restrictions}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_date_restrictions">Date Restrictions</label>
        <input type="text" name="tournament_roster_admin[data][date_restrictions]" id="tournament_roster_admin_date_restrictions" value="{{date_restrictions}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_roster_enabled">Enable/Visibility <small></small></label>
        <select name="tournament_roster_admin[data][enabled]" id="tournament_roster_admin_roster_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_level">Division <span class="req">*</span></label>
        <select id="tournament_roster_admin_level" name="tournament_roster_admin[data][level]" class="select_local level" data-g365_contingent="tournament_roster_admin_level_contingent" data-g365_type="team_names" data-g365_select="{{level}}" data-g365_short_name="{{level_name}}" data-g365_load_target="tournament_roster_admin_division_selector" required>{{level_selector_options}}</select>
      </div>
      <div id="tournament_roster_admin_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin {{level_selector_options_hide}}">
          <label for="tournament_roster_admin_division_selector">Level</label>
          <select id="tournament_roster_admin_division_selector" name="tournament_roster_admin[data][division]" data-g365_select="{{division}}">{{division_selector_options}}</select>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_team_type">Type</label>
        <select name="tournament_roster_admin[data][team_type]" id="tournament_roster_admin_team_type" data-g365_select="{{team_type}}">
          <option value="">--</option>
          <option value="Boys">Boys</option>
          <option value="Girls">Girls</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_coach_names">Coach</label>
        <input type="hidden" name="tournament_roster_admin[data][coach]" id="tournament_roster_admin_coach" value="{{coach_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="tournament_roster_admin_coach" data-g365_form_dest="tournament_roster_admin_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_asst_names">Assistant Coach</label>
        <input type="hidden" name="tournament_roster_admin[data][asst]" id="tournament_roster_admin_asst" value="{{asst_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="tournament_roster_admin_asst" data-g365_form_dest="tournament_roster_admin_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
        <label for="tournament_roster_admin_description">Practice Times</label>
        <input type="text" name="tournament_roster_admin[data][description]" id="tournament_roster_admin_description" placeholder="Practice (ASC) Wed/Fri 6:00-7:30 PM" maxlength="60" value="{{description}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_players">Add Players1 <div class="float-right"><input type="checkbox" class="no-margin-bottom" name="tournament_roster_admin[data][push_to_def]" value="valid" checked="checked" /> Add to Default</div></label>
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_players" data-g365_base_id="tournament_roster_admin" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="tournament_roster_admin_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="tournament_roster_admin_player_add" data-g365_type="player_rosters_admin" data-g365_limit="max,30|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="tournament_roster_admin_division_selector_birth_lock|tournament_roster_admin_division_selector_class_lock" placeholder="Search for Players..." autocomplete="off">
        <div id="tournament_roster_admin_players_wrapper">
          <input type="hidden" name="tournament_roster_admin[data][players]" value="">
          <ol id="tournament_roster_admin_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
          {{player_rosters}}
          </ol>
        </div>
      </div>
    </div>
    <div id="tournament_roster_admin_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Roster</button>
  </form>
</div>
EOD;
$roster_registration_form_min_admin_sl_ev = <<<EOD
<div id="tournament_roster_admin_fieldset" class="gset small-padding">
  <form id="tournament_roster_admin" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="tournament_roster_admin" data-target_field="reload_button">
    <small class="change-title" data-default_value="" data-g365_change_targets="#tournament_roster_admin_event_id|#tournament_roster_admin_level|#tournament_roster_admin_division_selector" data-g365_change_delimiter="|"></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="tournament_roster_admin" data-default_value="Team Roster" data-g365_change_targets="#tournament_roster_admin_club_id|#tournament_roster_admin_name|#tournament_roster_admin_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="tournament_roster_admin[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="tournament_roster_admin[id]" id="tournament_roster_admin_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="tournament_roster_admin[data][org]" id="tournament_roster_admin_club_id" value="{{org_id}}" data-g365_contingent="tournament_roster_admin_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" id="tournament_roster_admin_division_selector_birth_lock" value="{{division_selector_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="tournament_roster_admin_division_selector_class_lock" value="{{division_selector_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
      <select data-g365_data_key="event_id" name="tournament_roster_admin[data][event]" id="tournament_roster_admin_event_id" class="select_local hide" data-g365_load_target="tournament_roster_admin_level" data-g365_additional_target="tournament_roster_admin_division_selector,tournament_roster_admin_level" data-g365_short_name="{{event_short_name}}">
        <option value="{{event_id}}" data-g365_additional_data="{{event_divisions}}">{{event_full_name}}</option>
      </select>
      <input type="hidden" data-g365_data_key="tournament_roster_admin_level" name="tournament_roster_admin[data][team]" id="tournament_roster_admin_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name_full}}" data-g365_additional_target_limit="tournament_roster_admin_level" data-g365_additional_data="{{level}}">
    </div>
    <div id="tournament_roster_admin_club_team_contingent">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_pool_name">Pool Name</label>
        <input type="text" name="tournament_roster_admin[data][pool_name]" id="tournament_roster_admin_pool_name" value="{{pool_name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_pool_number">Pool Number</label>
        <input type="text" name="tournament_roster_admin[data][pool_number]" id="tournament_roster_admin_pool_number" value="{{pool_number}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_team_restrictions">Team Restrictions</label>
        <input type="text" name="tournament_roster_admin[data][team_restrictions]" id="tournament_roster_admin_team_restrictions" value="{{team_restrictions}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_date_restrictions">Date Restrictions</label>
        <input type="text" name="tournament_roster_admin[data][date_restrictions]" id="tournament_roster_admin_date_restrictions" value="{{date_restrictions}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_roster_enabled">Enable/Visibility <small></small></label>
        <select name="tournament_roster_admin[data][enabled]" id="tournament_roster_admin_roster_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_level">Division <span class="req">*</span></label>
        <select id="tournament_roster_admin_level" name="tournament_roster_admin[data][level]" class="select_local level" data-g365_contingent="tournament_roster_admin_level_contingent" data-g365_type="team_names" data-g365_select="{{level}}" data-g365_short_name="{{level_name}}" data-g365_load_target="tournament_roster_admin_division_selector">{{level_selector_options}}</select>
      </div>
      <div id="tournament_roster_admin_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin {{level_selector_options_hide}}">
          <label for="tournament_roster_admin_division_selector">Level <span class="req">*</span></label>
          <select id="tournament_roster_admin_division_selector" name="tournament_roster_admin[data][division]" data-g365_select="{{division}}">{{division_selector_options}}</select>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_team_type">Type</label>
        <select name="tournament_roster_admin[data][team_type]" id="tournament_roster_admin_team_type" data-g365_select="{{team_type}}">
          <option value="">--</option>
          <option value="Boys">Boys</option>
          <option value="Girls">Girls</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_coach_names">Coach</label>
        <input type="hidden" name="tournament_roster_admin[data][coach]" id="tournament_roster_admin_coach" value="{{coach_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="tournament_roster_admin_coach" data-g365_form_dest="tournament_roster_admin_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_asst_names">Assistant Coach</label>
        <input type="hidden" name="tournament_roster_admin[data][asst]" id="tournament_roster_admin_asst" value="{{asst_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="tournament_roster_admin_asst" data-g365_form_dest="tournament_roster_admin_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
        <label for="tournament_roster_admin_description">Practice Times</label>
        <input type="text" name="tournament_roster_admin[data][description]" id="tournament_roster_admin_description" placeholder="Practice (ASC) Wed/Fri 6:00-7:30 PM" maxlength="60" value="{{description}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tournament_roster_admin_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_players" data-g365_base_id="tournament_roster_admin" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="tournament_roster_admin_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="tournament_roster_admin_player_add" data-g365_type="player_rosters" data-g365_limit="max,30|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="tournament_roster_admin_division_selector_birth_lock|tournament_roster_admin_division_selector_class_lock" placeholder="Search for Players..." autocomplete="off">
        <div id="tournament_roster_admin_players_wrapper">
          <ol id="tournament_roster_admin_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
          {{player_rosters}}
          </ol>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
        <label for="tournament_roster_admin_events">Add Events for Website Schedule<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
        <input type="text" class="g365_livesearch_input expanded" id="tournament_roster_admin_events" data-g365_base_id="tournament_roster_admin" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="tournament_roster_admin_events_wrapper" data-g365_type="event_names" placeholder="Search for Events..." autocomplete="off" data-ls_no_add="true">
        <div id="tournament_roster_admin_events_wrapper">
          <ol id="tournament_roster_admin_events_wrapper_data" class="event-list button-list no-margin-left small-margin-top medium-padding-left">
          {{event_names}}
          </ol>
        </div>
      </div>
    </div>
    <div id="tournament_roster_admin_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Roster</button>
  </form>
</div>
EOD;
$roster_registration_form_basic = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div class="green-border tiny-padding">
    <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title no-point tiny-padding-bottom input-group no-margin-bottom">
      <span class="input-group-field">
        <small class="block change-title no-point" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id|#{{field-set-id}}_level_selector|#{{field-set-id}}_division_selector" data-g365_change_delimiter=" | "></small>
        <span class="change-title" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_name|#{{field-set-id}}_team_selector"></span>
      </span>
      <div class="input-group-button">
        <a class="button site-close-button"><span>remove</span></a>
      </div>
    </div>
    <div class="input-group no-margin-bottom">
      <label for="{{field-set-id}}_level_selector" class="input-group-label {{level_selector_options_hide}}">Division <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][level]" class="input-group-field shrink {{level_selector_options_hide}}" id="{{field-set-id}}_level_selector" data-g365_load_target="{{field-set-id}}_division_selector" data-g365_select="{{level}}" required>{{level_selector_options}}</select>
      <div class="input-group no-margin-bottom {{division_selector_options_hide}}">
        <label for="{{field-set-id}}_division_selector" class="input-group-label">Level <span class="req">*</span></label>
        <select name="{{field-set-id}}[data][division]" class="input-group-field" id="{{field-set-id}}_division_selector" data-g365_select="{{division}}">{{division_selector_options}}</select>
      </div>
      <a class="input-group-button button no-margin-bottom field-toggle" data-g365_target="{{field-set-id}}_players_hold">Add Players</a>
    </div>
    <div id="{{field-set-id}}_players_hold" class="tiny-margin-bottom tiny-padding no-input-margin green-border" style="display:none;">
      <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="{{field-set-id}}_level_birth_lock|{{field-set-id}}_level_class_lock" placeholder="Search for Players..." autocomplete="off" data-ls_no_add="true">
      <div id="{{field-set-id}}_players_wrapper">
        <ol id="{{field-set-id}}_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
        {{player_rosters}}
        </ol>
      </div>
    </div>
    <div id="{{field-set-id}}_fieldset" class="gset hide small-padding">
      <div class="g365_set_default">
        <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
        <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
        <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
        <input type="hidden" data-g365_data_key="event_id" class="select_local" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_load_target="{{field-set-id}}_level_selector" data-g365_additional_target="{{field-set-id}}_division_selector,{{field-set-id}}_level_selector" data-g365_immutable="true" data-g365_short_name="{{event_name}}" data-g365_additional_data="{{divisions}}">
        <input type="hidden" id="{{field-set-id}}_level" value="{{team_level}}" data-g365_immutable="true" data-g365_short_name="{{team_level_name}}">
        <input type="hidden" id="{{field-set-id}}_level_birth_lock" value="{{level_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
        <input type="hidden" id="{{field-set-id}}_level_class_lock" value="{{level_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
        <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name}}">
        <ol class="hide">{{event_names}}</ol>
      </div>
    </div>
  </div>
</div>
EOD;
//above field to hide it
//     <a class="field-toggle block text-right" data-g365_after="" data-data_capture="event_names|event_id"><span class="field-title"></span><span class="field-button">revert event</span></a>
//     <select id="division_selector" data-g365_send_options="true" required></select>
//Add Roster to Event 
$roster_registration_init_basic = <<<EOD
<div id="g365_roster_form_wrap">
  <h1 class="section_title">Team Roster Data</h1>
  <div id="reload_button" class="hide input-group" data-g365_action="add_result">
    <a onclick="location.reload()" class="button">Add Another Roster</a>
    <a href="/account/rosters/" class="button">Goto Your Roster Data</a>
    <!--<a href="/cart/" class="button">Pay for Teams</a>-->
  </div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Teams</span></a>
    <div class="form-init tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
        <label for="event_names">Event <span class="req">*</span></label>
        <input type="hidden" id="event_id" class="select_local" data-g365_load_target="level" data-g365_additional_target="division_selector,level" data-g365_short_name="{{name}}" data-g365_send_additional="true" data-g365_additional_data='{{divisions}}' data-g365_additional_lock="g365_roster_form_data" data-g365_deps_start="team_names" value="{{event}}" data-g365_error_target="event_names">
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="club_search_block_contingent" data-g365_ls_lock="org" required>
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names" data-g365_deps_start="team_names" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-ls_user_ac="{{user_ac}}" data-g365_load_target="team_names" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off">
      </div>
      <div id="club_search_block_contingent" class="form-disabled form__border tiny-padding small-margin-top">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="team_names" class="tiny-margin-top louder"><span class="change-title emphasis title-disable" data-default_value="Choose Team" data-g365_change_targets="#event_id" data_g365_change_totals="true"></span> <span class="req">*</span></label>
          <input type="hidden" id="team_selector" data-g365_contingent="team_search_contingent" data-g365_additional_target_limit="level" data-g365_link_target="{{level_link}}" required>
          <input type="text" id="team_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_reset_target="level" data-g365_add_button="roster_add_button_wrap" data-g365_form_template="form_template_min" data-g365_type="team_names" data-ls_target="team_selector" data-g365_form_dest="team_add" data-ls_query_lock="club_id" data-g365_contributors="club_id" data-g365_contributors_req="club_id" placeholder="Enter Team Name: 14U Blue, Varsity Elite Black, etc..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin {{level_link}}" data-g365_link_target_dest="{{level_link}}" id="team_search_contingent">
          <label for="level">Event Division <span class="req">*</span></label>
          <select id="level" class="level" data-g365_static="true" data-g365_load_target="division_selector" data-g365_contingent="division_search_contingent" data-g365_add_button="roster_add_button_wrap" required></select>
        </div>
        <div id="division_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="division_selector">Division Level <span class="req">*</span></label>
            <select id="division_selector" required></select>
          </div>
        </div>
        <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;">
          <a type="button" id="roster_add_button" class="site-button button form_loader" data-g365_type="rosters_teams" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_contributors="club_id|event_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|event_id|team_selector|level|division_selector" data-g365_limit="only,club_id,event_id,level,team_selector|dropdown,event_id" data-g365_toggle_parent="init_toggle" data-g365_deps_start="team_names" data-g365_base_id="self" tabindex=0>Next</a>
        </div>
      </div>
    </div>
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_teams" data-target_field="reload_button">
      <div id="g365_roster_form_data"></div>
      <div id="g365_roster_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Submit Rosters</button>
      </div>
    </form>
  </div>
</div>
EOD;
$roster_registration_init_basic_admin = <<<EOD
<div id="g365_roster_form_wrap">
  <h1 class="section_title">Team Roster Data</h1>
  <div id="reload_button" class="hide input-group" data-g365_action="add_result">
    <a onclick="location.reload()" class="button">Add Another Roster</a>
    <a href="/account/rosters/" class="button">Goto Your Roster Data</a>
    <!--<a href="/cart/" class="button">Pay for Teams</a>-->
  </div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Teams</span></a>
    <div class="form-init tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
        <label for="event_names">Event <span class="req">*</span></label>
        <input type="hidden" id="event_id" class="select_local" data-g365_load_target="level" data-g365_additional_target="division_selector,level" data-g365_short_name="{{name}}" data-g365_send_additional="true" data-g365_additional_data='{{divisions}}' data-g365_additional_lock="g365_roster_form_data" data-g365_deps_start="team_names" value="{{event}}" data-g365_error_target="event_names">
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="club_search_block_contingent" data-g365_ls_lock="org" required>
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names_admin" data-g365_deps_start="team_names" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-g365_load_target="team_names" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off">
      </div>
      <div id="club_search_block_contingent" class="form-disabled form__border tiny-padding small-margin-top">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="team_names" class="tiny-margin-top louder"><span class="change-title emphasis title-disable" data-default_value="Choose Team" data-g365_change_targets="#event_id" data_g365_change_totals="true"></span> <span class="req">*</span></label>
          <input type="hidden" id="team_selector" data-g365_contingent="team_search_contingent" data-g365_additional_target_limit="level" data-g365_link_target="{{level_link}}" required>
          <input type="text" id="team_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_reset_target="level" data-g365_add_button="roster_add_button_wrap" data-g365_form_template="form_template_min" data-g365_type="team_names" data-ls_target="team_selector" data-g365_form_dest="team_add" data-ls_query_lock="club_id" data-g365_contributors="club_id" data-g365_contributors_req="club_id" placeholder="Enter Team Name: 14U Blue, Varsity Elite Black, etc..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin {{level_link}}" data-g365_link_target_dest="{{level_link}}" id="team_search_contingent">
          <label for="level">Event Division <span class="req">*</span></label>
          <select id="level" class="level" data-g365_static="true" data-g365_load_target="division_selector" data-g365_contingent="division_search_contingent" data-g365_add_button="roster_add_button_wrap" required></select>
        </div>
        <div id="division_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="division_selector">Division Level <span class="req">*</span></label>
            <select id="division_selector" required></select>
          </div>
        </div>
        <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;">
          <a type="button" id="roster_add_button" class="site-button button form_loader" data-g365_type="rosters_teams_admin" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_contributors="club_id|event_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|event_id|team_selector|level|division_selector" data-g365_limit="only,club_id,event_id,level,team_selector|dropdown,event_id" data-g365_toggle_parent="init_toggle" data-g365_deps_start="team_names" data-g365_base_id="self" tabindex=0>Next</a>
        </div>
      </div>
    </div>
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_teams_admin" data-target_field="reload_button">
      <div id="g365_roster_form_data"></div>
      <div id="g365_roster_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Submit Rosters testing?</button>
      </div>
    </form>
  </div>
</div>
EOD;
// $roster_registration_init_basic_OLD = <<<EOD
// <div id="g365_roster_form_wrap">
//   <h1 class="section_title">Team Roster Data</h1>
//   <div id="reload_button" class="hide input-group" data-g365_action="add_result">
//     <a onclick="location.reload()" class="button">Add Another Roster</a>
//     <a href="/account/rosters/" class="button">Goto Your Roster Data</a>
//     <!--<a href="/cart/" class="button">Pay for Teams</a>-->
//   </div>
//   <div class="form-holder">
//     <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Teams</span></a>
//     <div class="form-init gset tiny-padding">
//       <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
//         <label for="event_names">Event</label>
//         <input type="hidden" id="event_id" class="select_local" data-g365_load_target="level" data-g365_additional_target="division_selector,level" data-g365_short_name="{{name}}" data-g365_send_additional="true" data-g365_additional_data='{{divisions}}' data-g365_additional_lock="g365_roster_form_data" data-g365_deps_start="team_names" value="{{event}}" data-g365_error_target="event_names">
//         <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{name}}">
//       </div>
//       <div class="tiny-margin-bottom tiny-padding no-input-margin">
//         <label for="club_names">Club Organization <span class="req">*</span></label>
//         <input type="hidden" id="club_id" data-g365_contingent="club_search_block_contingent" data-g365_ls_lock="org" required>
//         <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names" data-g365_deps_start="team_names" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-ls_user_ac="{{user_ac}}" data-g365_load_target="team_names" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off">
//       </div>
//       <div id="club_search_block_contingent" class="form-disabled">
//         <div class="tiny-margin-bottom tiny-padding no-input-margin">
//           <label for="team_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Team" data-g365_change_targets="#event_id" data_g365_change_totals="true"></span> <span class="req">*</span></label>
//           <input type="hidden" id="team_selector" data-g365_contingent="team_search_contingent" data-g365_additional_target_limit="level" data-g365_link_target="{{level_link}}" required>
//           <input type="text" id="team_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_reset_target="level" data-g365_add_button="roster_add_button_wrap" data-g365_form_template="form_template_min" data-g365_type="team_names" data-ls_target="team_selector" data-g365_form_dest="team_add" data-ls_query_lock="club_id" data-g365_contributors="club_id" data-g365_contributors_req="club_id" placeholder="Enter Team Name: 14U Blue, Varsity Elite Black, etc..." autocomplete="off">
//         </div>
//         <div class="tiny-margin-bottom tiny-padding no-input-margin {{level_link}}" data-g365_link_target_dest="{{level_link}}" id="team_search_contingent">
//           <label for="level">Event Division <span class="req">*</span></label>
//           <select id="level" class="level" data-g365_static="true" data-g365_load_target="division_selector" data-g365_contingent="division_search_contingent" data-g365_add_button="roster_add_button_wrap" required></select>
//         </div>
//         <div id="division_search_contingent" class="form-disabled">
//           <div class="tiny-margin-bottom tiny-padding no-input-margin">
//             <label for="division_selector">Division Level <span class="req">*</span></label>
//             <select id="division_selector" required></select>
//           </div>
//         </div>
//         <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;">
//           <a type="button" id="roster_add_button" class="site-button button form_loader" data-g365_type="rosters_teams" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_contributors="club_id|event_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|event_id|team_selector|level|division_selector" data-g365_limit="only,club_id,event_id,level,team_selector|dropdown,event_id" data-g365_toggle_parent="init_toggle" data-g365_deps_start="team_names" data-g365_base_id="self" tabindex=0>Next</a>
//         </div>
//       </div>
//     </div>
//     <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_teams" data-target_field="reload_button">
//       <div id="g365_roster_form_data"></div>
//       <div id="g365_roster_form_submit" class="g365_form_sub_block" style="display:none;">
//         <hr />
//         <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
//         <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Submit Rosters</button>
//       </div>
//     </form>
//   </div>
// </div>
// EOD;
$roster_registration_init = <<<EOD
<div id="g365_roster_form_wrap">
  <h1 class="section_title">Team Roster Data</h1>
  <div id="reload_button" class="hide button" onclick="location.reload()" data-g365_action="add_result">Add More Teams</div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Teams</span></a>
    <div class="form-init gset tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
        <label for="event_names">Event</label>
        <input type="hidden" id="event_id" data-g365_additional_target="division_selector,level" data-g365_short_name="{{name}}" data-g365_additional_data='{{divisions}}' data-g365_additional_lock="g365_roster_form_data" data-g365_deps_start="team_names" value="{{event}}" data-g365_error_target="event_names">
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="team_search_block_contingent"  value="{{club_id}}" data-g365_error_target="club_names" required>
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names_admin" data-g365_deps_start="level" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off" autofocus value="{{club_name}}">
      </div>
      <div id="team_search_block_contingent" class="form-disabled">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="level"><span class="change-title emphasis green block" data-default_value="Choose Player" data-g365_change_targets="#event_id"></span>Division <span class="req">*</span></label>
          <select id="level" class="select_loader level" data-g365_contingent="team_search_contingent" data-g365_static="true" data-g365_type="team_names" data-g365_load_target="team_selector" data-g365_contributors="club_id|level" data-g365_contributors_req="club_id" disabled>
            <option value="">-- Please Select</option>
            <option value="8" data-g365_ref_val="8" data-g365_short_name="8U">8U / 2nd Grade</option>
            <option value="9" data-g365_ref_val="9" data-g365_short_name="9U">9U / 3rd Grade</option>
            <option value="10" data-g365_ref_val="10" data-g365_short_name="10U">10U / 4th Grade</option>
            <option value="11" data-g365_ref_val="11" data-g365_short_name="11U">11U / 5th Grade</option>
            <option value="12" data-g365_ref_val="12" data-g365_short_name="12U">12U / 6th Grade</option>
            <option value="13" data-g365_ref_val="13" data-g365_short_name="13U">13U / 7th Grade</option>
            <option value="14" data-g365_ref_val="14" data-g365_short_name="14U">14U / 8th Grade</option>
            <option value="15" data-g365_ref_val="15" data-g365_short_name="15U">15U / Frosh/Soph</option>
            <option value="16" data-g365_ref_val="16" data-g365_short_name="16U">16U / JV</option>
            <option value="17" data-g365_ref_val="17" data-g365_short_name="17U">17U / Varsity</option>
          </select>
        </div>
        <div id="team_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
            <label for="division_selector">Level <span class="req">*</span></label>
            <select id="division_selector"></select>
          </div>
          <div id="team_new_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="team_selector">Team Name <span class="req">*</span></label>
            <select id="team_selector" class="select_loader" data-g365_contingent="roster_search_contingent" data-g365_action="set_select" data-g365_type="rosters" data-g365_type_new="team_names" data-g365_form_dest="team_add" data-g365_load_target="roster_selector" data-g365_contributors="club_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|team_selector|division_selector" data-g365_add_button="roster_add_button_wrap" disabled></select>
          </div>
        </div>
        <div id="roster_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="roster_selector">New or Existing Roster <span class="req">*</span></label>
            <select id="roster_selector" disabled></select>
          </div>
        </div>
        <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;" >
          <a id="roster_add_button" class="site-button button form_loader" data-g365_type="rosters" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_data_target="roster_selector" data-g365_contributors="club_id|event_id|level|team_selector|division_selector|roster_selector" data-g365_contributors_req="club_id|level|team_selector|division_selector|roster_selector" data-g365_limit="only,club_id,event_id,level,team_selector" data-g365_toggle_parent="init_toggle" data-g365_deps_start="level" data-g365_base_id="self" tabindex=0>Next</a>
        </div>
      </div>
    </div>
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters" data-target_field="reload_button">
      <div id="g365_roster_form_data"></div>
      <div id="g365_roster_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Submit Rosters</button>
      </div>
    </form>
  </div>
</div>
EOD;
$roster_registration_form = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable tiny-margin-left">edit</a>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <div><a class="site-close-button remove-button site-button button">remove</a></div>
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id|#{{field-set-id}}_level|#{{field-set-id}}_division_selector" data-g365_change_delimiter=" | "></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_name|#{{field-set-id}}_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" data-g365_data_key="event_id" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_immutable="true" data-g365_short_name="{{event_short_name}}">
      <input type="hidden" data-g365_data_key="level" name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" value="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" data-g365_immutable="true" data-g365_short_name="{{level_name}}">
      <input type="hidden" name="{{field-set-id}}[data][division]" id="{{field-set-id}}_division_selector" value="{{division}}" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_birth_lock" value="{{division_selector_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_class_lock" value="{{division_selector_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
      <input type="hidden" class="change-title" data-default_value="none" data-default_value="" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_level|#{{field-set-id}}_name|#{{field-set-id}}_team_selector" value="">
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_names">Team <span class="req">*</span></label>
          <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name_full}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_team_names" value="{{team_name}}" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="{{field-set-id}}_team" data-g365_form_dest="{{field-set-id}}_team_add" data-g365_type="team_names" data-ls_query_lock="{{field-set-id}}_club_id|{{field-set-id}}_level" placeholder="Search for Team..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Type</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{roster_lock_type}}|only,id" data-ls_query_lock="{{field-set-id}}_division_selector_birth_lock|{{field-set-id}}_division_selector_class_lock" placeholder="Search for Players..." autocomplete="off" data-ls_no_add="true">
          <div id="{{field-set-id}}_players_wrapper">
            <ol id="{{field-set-id}}_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
            {{player_rosters}}
            </ol>
          </div>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
        <label for="{{field-set-id}}_events">Add Events for Website Schedule<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'close') ? 'add events' : 'close') );">close</a></label>
        <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_events" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_events_wrapper" data-g365_type="event_names" placeholder="Search for Events..." autocomplete="off" data-ls_no_add="true">
        <div id="{{field-set-id}}_events_wrapper">
          <ol id="{{field-set-id}}_events_wrapper_data" class="event-list button-list no-margin-left small-margin-top medium-padding-left">
          {{event_names}}
          </ol>
        </div>
      </div>
    </div>
    <div>
      <a class="text-right block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$roster_registration_form_admin = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable tiny-margin-left">edit</a>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <div><a class="site-close-button remove-button site-button button">remove</a></div>
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id|#{{field-set-id}}_level|#{{field-set-id}}_division_selector" data-g365_change_delimiter=" | "></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_name|#{{field-set-id}}_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" data-g365_data_key="event_id" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_immutable="true" data-g365_short_name="{{event_short_name}}">
      <input type="hidden" data-g365_data_key="level" name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" value="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" data-g365_immutable="true" data-g365_short_name="{{level_name}}">
      <input type="hidden" name="{{field-set-id}}[data][division]" id="{{field-set-id}}_division_selector" value="{{division}}" data-g365_immutable="true">
      <input type="hidden" class="change-title" data-default_value="none" data-default_value="" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_level|#{{field-set-id}}_name|#{{field-set-id}}_team_selector" value="">
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_names">Team <span class="req">*</span></label>
          <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name_full}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_team_names" value="{{team_name}}" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="{{field-set-id}}_team" data-g365_form_dest="{{field-set-id}}_team_add" data-g365_type="team_names" data-ls_query_lock="{{field-set-id}}_club_id|{{field-set-id}}_level" placeholder="Search for Team..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_roster_enabled">Visibility</label>
          <select name="{{field-set-id}}[data][enabled]" id="{{field-set-id}}_roster_enabled" data-g365_select="{{enabled}}">
            <option value="1">Shown</option>
            <option value="0">Hidden</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Type</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{division_selector_lock_type}}|only,id" placeholder="Search for Players..." autocomplete="off">
          <div id="{{field-set-id}}_players_wrapper">
            <ol id="{{field-set-id}}_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
            {{player_rosters}}
            </ol>
          </div>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
        <label for="{{field-set-id}}_events">Add Events for Website Schedule<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
        <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_events" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_events_wrapper" data-g365_type="event_names" placeholder="Search for Events..." autocomplete="off" data-ls_no_add="true">
        <div id="{{field-set-id}}_events_wrapper">
          <ol id="{{field-set-id}}_events_wrapper_data" class="event-list button-list no-margin-left small-margin-top medium-padding-left">
          {{event_names}}
          </ol>
        </div>
      </div>
    </div>
    <div>
      <a class="text-right block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$roster_registration_form_teams = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable tiny-margin-left">edit</a>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <div><a class="site-close-button remove-button site-button button">remove</a></div>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_level|#{{field-set-id}}_name|#{{field-set-id}}_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" data-g365_data_key="event_id" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_immutable="true" data-g365_short_name="{{event_short_name}}">
      <input type="hidden" data-g365_data_key="level" name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" value="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" data-g365_immutable="true" data-g365_short_name="{{level_name}}">
      <input type="hidden" name="{{field-set-id}}[data][division]" id="{{field-set-id}}_division_selector" value="{{division}}" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_birth_lock" value="{{division_selector_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_class_lock" value="{{division_selector_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
      <input type="hidden" class="change-title" data-default_value="none" data-default_value="" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_level|#{{field-set-id}}_name|#{{field-set-id}}_team_selector" value="">
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_names">Team <span class="req">*</span></label>
          <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name_full}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_team_names" value="{{team_name}}" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="{{field-set-id}}_team" data-g365_form_dest="{{field-set-id}}_team_add" data-g365_type="team_names" data-ls_query_lock="{{field-set-id}}_club_id|{{field-set-id}}_level" placeholder="Search for Team..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_roster_enabled">Visibility</label>
          <select name="{{field-set-id}}[data][enabled]" id="{{field-set-id}}_roster_enabled" data-g365_select="{{enabled}}">
            <option value="1">Shown</option>
            <option value="0">Hidden</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Type</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="{{field-set-id}}_division_selector_birth_lock|{{field-set-id}}_division_selector_class_lock" placeholder="Search for Players..." autocomplete="off">
          <div id="{{field-set-id}}_players_wrapper">
            <ol id="{{field-set-id}}_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
            {{player_rosters}}
            </ol>
          </div>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
        <label for="{{field-set-id}}_events">Add Events for Website Schedule<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
        <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_events" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_events_wrapper" data-g365_type="event_names" placeholder="Search for Events..." autocomplete="off" data-ls_no_add="true">
        <div id="{{field-set-id}}_events_wrapper">
          <ol id="{{field-set-id}}_events_wrapper_data" class="event-list button-list no-margin-left small-margin-top medium-padding-left">
          {{event_names}}
          </ol>
        </div>
      </div>
    </div>
    <div>
      <a class="text-right block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
//Rosters Editor in Manage Account
//     <label class="input-group-button small-padding-left tiny-padding bulk-add-checkbox">
//       <input id="{{field-set-id}}_add_to_event" type="checkbox" data-g365_bulk_id="{{id}}" />
//     </label>
$roster_registration_form_sl = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form enabled-{{enabled}}">
  <!--<hr class="g365-divider" />-->
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding gray-border white-bg input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button-secondary hide-on-disable tiny-margin-left">edit</a>
  </div>
  <div id="{{field-set-id}}_fieldset" class="form__wrapper white-bg small-margin-bottom small-padding">
    <div class="text-right">
      <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id|#{{field-set-id}}_level|#{{field-set-id}}_division_selector" data-g365_change_delimiter=" | "></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_name|#{{field-set-id}}_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" data-g365_data_key="event_id" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_immutable="true" data-g365_short_name="{{event_short_name}}">
      <input type="hidden" data-g365_data_key="level" name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" value="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" data-g365_immutable="true" data-g365_short_name="{{level_name}}">
      <input type="hidden" name="{{field-set-id}}[data][division]" id="{{field-set-id}}_division_selector" value="{{division}}" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_birth_lock" value="{{division_selector_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_class_lock" value="{{division_selector_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
          <label for="{{field-set-id}}_team_names">Team <span class="req">*</span></label>
          <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name_full}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_team_names" value="{{team_name}}" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="{{field-set-id}}_team" data-g365_form_dest="{{field-set-id}}_team_add" data-g365_type="team_names" data-ls_query_lock="{{field-set-id}}_club_id|{{field-set-id}}_level" placeholder="Search for Team..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_roster_enabled">Visibility <small>on club team pages</small></label>
          <select name="{{field-set-id}}[data][enabled]" id="{{field-set-id}}_roster_enabled" data-g365_select="{{enabled}}">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Gender</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}" required>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach or Create Coach Profile" autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
          <label for="{{field-set-id}}_description">Practice Times</label>
          <input type="text" name="{{field-set-id}}[data][description]" id="{{field-set-id}}_description" placeholder="Practice (ASC) Wed/Fri 6:00-7:30 PM" maxlength="60" value="{{description}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,18|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="{{field-set-id}}_division_selector_birth_lock|{{field-set-id}}_division_selector_class_lock" placeholder="Search for Players..." autocomplete="off" data-ls_no_add="true">
          <div id="{{field-set-id}}_players_wrapper">
            <ol id="{{field-set-id}}_players_wrapper_data" class="button-list editRosters__addPlayersList no-margin-left small-margin-top medium-padding-left">
            {{player_rosters}}
            </ol>
          </div>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
          <label for="{{field-set-id}}_events">Add Events for Website Schedule<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_events" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_events_wrapper" data-g365_type="event_names" placeholder="Search for Events..." autocomplete="off" data-ls_no_add="true">
          <div id="{{field-set-id}}_events_wrapper">
            <ol id="{{field-set-id}}_events_wrapper_data" class="event-list button-list no-margin-left small-margin-top medium-padding-left">
            {{event_names}}
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
EOD;
//     <label class="input-group-button small-padding-left tiny-padding bulk-add-checkbox">
//       <input id="{{field-set-id}}_add_to_event" type="checkbox" data-g365_bulk_id="{{id}}" />
//     </label>
//By Event
$roster_registration_form_sl_to = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form enabled-{{enabled}}">
  <!-- <hr class="g365-divider" />-->
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding gray-border white-bg input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button-secondary hide-on-disable tiny-margin-left">edit</a>
  </div>
  <div id="{{field-set-id}}_fieldset" class="form__wrapper white-bg small-margin-bottom s small-padding">
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id|#{{field-set-id}}_level|#{{field-set-id}}_division_selector" data-g365_change_delimiter=" | "></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_name|#{{field-set-id}}_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" data-g365_data_key="event_id" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_immutable="true" data-g365_short_name="{{event_short_name}}">
      <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_immutable="true" data-g365_short_name="{{team_name_full}}">
      <input type="hidden" data-g365_data_key="level" name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" value="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" data-g365_immutable="true" data-g365_short_name="{{level_name}}">
      <input type="hidden" name="{{field-set-id}}[data][division]" id="{{field-set-id}}_division_selector" value="{{division}}" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_birth_lock" value="{{division_selector_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_division_selector_class_lock" value="{{division_selector_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_roster_enabled">Visibility <small>on event pages</small></label>
          <select name="{{field-set-id}}[data][enabled]" id="{{field-set-id}}_roster_enabled" data-g365_select="{{enabled}}">
            <option value="1">Show</option>
            <option value="0">Hide</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Type</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="{{field-set-id}}_division_selector_birth_lock|{{field-set-id}}_division_selector_class_lock" placeholder="Search for Players..." autocomplete="off" data-ls_no_add="true">
          <div id="{{field-set-id}}_players_wrapper">
            <ol id="{{field-set-id}}_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
            {{player_rosters}}
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="text-right">
      <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;

$roster_registration_form_ev = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable tiny-margin-left">edit</a>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <div><a class="site-close-button remove-button site-button button">remove</a></div>
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id|#{{field-set-id}}_level|#{{field-set-id}}_division_selector" data-g365_change_delimiter="|"></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Team Roster" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_name|#{{field-set-id}}_team_selector">{{element_title}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" data-g365_data_key="id" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" data-g365_data_key="club_id" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_contingent="{{field-set-id}}_club_team_contingent" data-g365_immutable="true" data-g365_short_name="{{org_name}}">
      <input type="hidden" data-g365_data_key="event_id" name="{{field-set-id}}[data][event]" id="{{field-set-id}}_event_id" value="{{event_id}}" data-g365_immutable="true" data-g365_short_name="{{event_short_name}}">
      <input type="hidden" data-g365_data_key="level" name="{{field-set-id}}[data][level]" id="{{field-set-id}}_level" value="{{level}}" data-g365_contingent="{{field-set-id}}_level_contingent" data-g365_immutable="true" data-g365_short_name="{{level_name}}">
      <input type="hidden" name="{{field-set-id}}[data][division]" id="{{field-set-id}}_division_selector" value="{{division}}" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_level_birth_lock" value="{{level_birth_lock}}" data-g365_ls_lock="birthday" data-g365_immutable="true">
      <input type="hidden" id="{{field-set-id}}_level_class_lock" value="{{level_class_lock}}" data-g365_ls_lock="grad_year" data-g365_immutable="true">
    </div>
    <div id="{{field-set-id}}_club_team_contingent">
      <div id="{{field-set-id}}_level_contingent">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_names">Team <span class="req">*</span></label>
          <input type="hidden" data-g365_data_key="team_selector" name="{{field-set-id}}[data][team]" id="{{field-set-id}}_team_selector" value="{{team_id}}" data-g365_short_name="{{team_name_full}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_team_names" value="{{team_name}}" data-ls_no_add="true" data-g365_action="select_data" data-ls_target="{{field-set-id}}_team" data-g365_form_dest="{{field-set-id}}_team_add" data-g365_type="team_names" data-ls_query_lock="{{field-set-id}}_club_id|{{field-set-id}}_level" placeholder="Search for Team..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_team_type">Type</label>
          <select name="{{field-set-id}}[data][team_type]" id="{{field-set-id}}_team_type" data-g365_select="{{team_type}}">
            <option value="">--</option>
            <option value="Boys">Boys</option>
            <option value="Girls">Girls</option>
          </select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_coach_names">Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][coach]" id="{{field-set-id}}_coach" value="{{coach_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_coach_names" value="{{coach_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_coach" data-g365_form_dest="{{field-set-id}}_coach_add" data-g365_type="coach_names" placeholder="Search for Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_asst_names">Assistant Coach</label>
          <input type="hidden" name="{{field-set-id}}[data][asst]" id="{{field-set-id}}_asst" value="{{asst_id}}">
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_asst_names" value="{{asst_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_asst" data-g365_form_dest="{{field-set-id}}_asst_add" data-g365_type="coach_names" placeholder="Search for Assistant Coach..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
          <label for="{{field-set-id}}_description">Practice Times</label>
          <input type="text" name="{{field-set-id}}[data][description]" id="{{field-set-id}}_description" placeholder="Practice (ASC) Wed/Fri 6:00-7:30 PM" maxlength="60" value="{{description}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_players">Add Players<a class="float-right" onclick="jQuery(this).parent().next().toggle();jQuery(this).html( ((jQuery(this).html() === 'done') ? 'add more' : 'done') );">close</a></label>
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_players" data-g365_base_id="{{field-set-id}}" value="" data-g365_action="load_form" data-g365_form_template="form_template_input_item" data-g365_form_dest="{{field-set-id}}_players_wrapper" data-g365_form_template_new="form_template_min" data-g365_form_dest_new="{{field-set-id}}_player_add" data-g365_type="player_rosters" data-g365_limit="max,16|exceptions,{{division_selector_lock_type}}|only,id" data-ls_query_lock="{{field-set-id}}_level_birth_lock|{{field-set-id}}_level_class_lock" placeholder="Search for Players..." autocomplete="off">
          <div id="{{field-set-id}}_players_wrapper">
            <ol id="{{field-set-id}}_players_wrapper_data" class="button-list no-margin-left small-margin-top medium-padding-left">
            {{player_rosters}}
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div>
      <a class="text-right block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
//add/edit rosters in manage account page
$roster_registration_init_sl = <<<EOD
<div id="g365_roster_form_wrap" data-g365_form_load_min="true">
  <h1 class="section_title">Manage Rosters</h1>
  <div class="form-holder">
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="ro_ed">
      <div id="g365_roster_form_data">
        <ul class="tabs" data-tabs id="roster_enabled_switch">
          <li class="tabs-title is-active"><a href="#g365_roster_form_enabled" aria-selected="true">Active Rosters</a></li>
          <li class="tabs-title"><a href="#g365_roster_form_disabled">Inactive Rosters</a></li>
        </ul>
        <div class="tabs-content gray-bg" data-tabs-content="roster_enabled_switch">
          <div class="tabs-panel g365_enabled_data is-active" id="g365_roster_form_enabled"></div>
          <div class="tabs-panel g365_disabled_data" id="g365_roster_form_disabled"></div>
        </div>
      </div>
      <div id="g365_roster_form_submit" class="g365_form_sub_block medium-margin-top">
        <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" 
//         onclick="
//         var forms = document.getElementsByTagName('form');
//         function isVisible(element) {
//           return !!(element.offsetWidth || element.offsetHeight || element.getClientRects().length);
//         }
//         for (var i = 0; i < forms.length; i++) {
//            var form = forms[i];
//            const inputs = form.querySelectorAll('input');

//            for (const input of inputs) {
//               if (!input.checkValidity() && isVisible(input)) {
//                 input.focus();
//                 alert('Please Enter a Coach');
//                 return false;
//               }
//            }
//         }
//         return true;" 
        class="site-button button expanded g365-primary-submit" value="submit" style="border: 1px solid white">Save All Roster Changes</button>
      </div>
    </form>
  </div>
</div>
EOD;
//By Event Wrapper
$roster_registration_init_sl_to = <<<EOD
<div id="g365_roster_form_wrap" data-g365_form_load_min="true">
  <h1 class="section_title">Manage Rosters</h1>
  <div class="form-holder gray-bg small-padding">
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="to_ed">
      <div id="g365_roster_form_data">
        <ul class="tabs" data-tabs id="roster_enabled_switch">
          <li class="tabs-title is-active"><a href="#g365_roster_form_enabled" aria-selected="true">Active Rosters</a></li>
          <li class="tabs-title"><a href="#g365_roster_form_disabled">Inactive Rosters</a></li>
        </ul>
        <div class="tabs-content" data-tabs-content="roster_enabled_switch">
          <div class="tabs-panel g365_enabled_data is-active" id="g365_roster_form_enabled"></div>
          <div class="tabs-panel g365_disabled_data" id="g365_roster_form_disabled"></div>
        </div>
      </div>
      <div id="g365_roster_form_submit" class="g365_form_sub_block">
        <hr />
        <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Update Tournament Rosters</button>
      </div>
    </form>
  </div>
</div>
EOD;
$roster_registration_init_club_teams = <<<EOD
<div id="reload_button" class="hide" data-g365_action="add_result">
  <a onclick="location.reload()" class="button">Add Another Roster</a>
  <a href="/account/rosters/" class="button">Goto Your Roster Data</a>
</div>
<div id="g365_roster_form_wrap">
  <h1 class="section_title">Manage Club Team Rosters</h1>
  <div class="form-holder">
    <div class="form-init gset tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="team_search_block_contingent">
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names" data-g365_deps_start="level" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off" autofocus>
      </div>
      <div id="team_search_block_contingent" class="form-disabled">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="level">Division <span class="req">*</span></label>
          <select id="level" class="select_loader level" data-g365_contingent="team_search_contingent" data-g365_static="true" data-g365_type="team_names" data-g365_load_target="team_selector" data-g365_contributors="club_id|level" data-g365_contributors_req="club_id" disabled>
            <option value="">-- Please Select</option>
            <option value="8" data-g365_short_name="8U">8U / 2nd Grade</option>
            <option value="9" data-g365_short_name="9U">9U / 3rd Grade</option>
            <option value="10" data-g365_short_name="10U">10U / 4th Grade</option>
            <option value="11" data-g365_short_name="11U">11U / 5th Grade</option>
            <option value="12" data-g365_short_name="12U">12U / 6th Grade</option>
            <option value="13" data-g365_short_name="13U">13U / 7th Grade</option>
            <option value="14" data-g365_short_name="14U">14U / 8th Grade</option>
            <option value="15" data-g365_short_name="15U">15U / Frosh/Soph</option>
            <option value="16" data-g365_short_name="16U">16U / JV</option>
            <option value="17" data-g365_short_name="17U">17U / Varsity</option>
          </select>
        </div>
        <div id="team_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
            <label for="division_selector">Level <span class="req">*</span></label>
            <select id="division_selector"></select>
          </div>
          <div id="team_new_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="team_selector">Team Name <span class="req">*</span></label>
            <select id="team_selector" class="select_loader" data-g365_contingent="roster_search_contingent" data-g365_action="set_select" data-g365_type="rosters_teams" data-g365_type_new="team_names" data-g365_form_dest="team_add" data-g365_load_target="roster_selector" data-g365_contributors="club_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|team_selector|division_selector" data-g365_add_button="roster_add_button_wrap" disabled></select>
          </div>
        </div>
        <div id="roster_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="roster_selector">New or Existing Roster <span class="req">*</span></label>
            <select id="roster_selector" disabled></select>
          </div>
        </div>
        <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;" >
          <button id="roster_add_button" class="site-button button form_loader" data-g365_deps_start="level" data-g365_type="rosters_teams" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_data_target="roster_selector" data-g365_contributors="club_id|level|team_selector|roster_selector|division_selector" data-g365_contributors_req="club_id|level|division_selector" data-g365_limit="only,club_id,level,team_selector" data-g365_base_id="self">Next</button>
        </div>
      </div>
    </div>
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_teams" data-target_field="reload_button">
      <div id="g365_roster_form_data"></div>
      <div id="g365_roster_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
        <div class="button-group expanded">
          <a class="button" href="#content">Add another roster</a>
          <button type="submit" class="button secondary" value="submit">Submit Roster Data</button>
        </div>
      </div>
    </form>
  </div>
</div>
EOD;
//checkout form
$roster_registration_init_event = <<<EOD
<div id="g365_roster_form_wrap">
  <h1 class="section_title">Create Club Team Rosters</h1>
  <div class="form-holder">
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_event">
      <div id="g365_roster_form_data" class="g365-form-data-wrapper"></div>
      <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
      <hr />
    </form>
    <div class="g365_set_default">
      <select id="event_id" class="select_local hide" data-g365_load_target="level" data-g365_auto_advance="true" data-g365_additional_target="division_selector,level" data-g365_additional_lock="g365_roster_form_data" data-g365_deps_start="team_names" required></select>
    </div>
    <div class="form-init form__wrapper tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="club_search_block_contingent" data-g365_ls_lock="org" required>
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names" data-g365_deps_start="team_names" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-ls_user_ac="{{user_ac}}" data-g365_load_target="team_names" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off">
      </div>
      <div id="club_search_block_contingent" class="form-disabled">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="team_names" class="tiny-margin-top louder"><span class="change-title emphasis" data-default_value="Choose Team" data-g365_change_targets="#event_id" data_g365_change_totals="true"></span> <span class="req">*</span></label>
          <input type="hidden" id="team_selector" data-g365_contingent="team_search_contingent" data-g365_additional_target_limit="level" required>
          <input type="text" id="team_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="team_names" data-ls_target="team_selector" data-g365_form_dest="team_add" data-ls_query_lock="club_id" data-g365_contributors="club_id" data-g365_contributors_req="club_id" placeholder="Enter Team Name: 14U Blue, Varsity Elite Black, etc..." autocomplete="off">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin" id="team_search_contingent">
          <label for="level">Division <span class="req">*</span></label>
          <select id="level" class="level" data-g365_load_target="division_selector" data-g365_contingent="division_search_contingent" data-g365_add_button="roster_add_button_wrap" required></select>
        </div>
        <div id="division_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="division_selector">Division Level <span class="req">*</span></label>
            <select id="division_selector" required></select>
          </div>
        </div>
        <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;" >
          <button type="button" id="roster_add_button" class="site-button button secondary expanded form_loader" data-g365_type="rosters_event" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_contributors="club_id|event_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|event_id|team_selector|level|division_selector" data-g365_limit="only,club_id,event_id,level,team_selector|dropdown,event_id" data-g365_form_load_min="true">Next</button>
        </div>
      </div>
    </div>
  </div>
  <hr />
</div>
EOD;
$roster_registration_init_event_level_first = <<<EOD
<div id="g365_roster_form_wrap">
  <h1 class="section_title">Create Club Team Rosters</h1>
  <p>Please fillout this form to make your roster available to the system.</p>
  <div class="form-holder">
    <div class="form-init gset tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="team_search_block_contingent">
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names" data-g365_deps_start="level" data-ls_target="club_id" data-g365_form_dest="club_team_add" placeholder="Enter Club Name" autocomplete="off" autofocus>
      </div>
      <div id="team_search_block_contingent" class="form-disabled">
        <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="event_names">Event</label>
          <select id="event_id" data-g365_additional_target="division_selector,level" data-g365_additional_lock="g365_roster_form_data"></select>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="level">Division <span class="req">*</span></label>
          <select id="level" class="select_loader level" data-g365_contingent="team_search_contingent" data-g365_static="true" data-g365_type="team_names" data-g365_load_target="team_selector" data-g365_contributors="club_id|level" data-g365_contributors_req="club_id" disabled>
            <option value="">-- Please Select</option>
            <option value="8" data-g365_short_name="8U">8U / 2nd Grade</option>
            <option value="9" data-g365_short_name="9U">9U / 3rd Grade</option>
            <option value="10" data-g365_short_name="10U">10U / 4th Grade</option>
            <option value="11" data-g365_short_name="11U">11U / 5th Grade</option>
            <option value="12" data-g365_short_name="12U">12U / 6th Grade</option>
            <option value="13" data-g365_short_name="13U">13U / 7th Grade</option>
            <option value="14" data-g365_short_name="14U">14U / 8th Grade</option>
            <option value="15" data-g365_short_name="Frosh/Soph">15U / Frosh/Soph</option>
            <option value="16" data-g365_short_name="JV">16U / JV</option>
            <option value="17" data-g365_short_name="Varsity">17U / Varsity</option>
          </select>
        </div>
        <div id="team_search_contingent" class="form-disabled">
          <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
            <label for="division_selector">Level <span class="req">*</span></label>
            <select id="division_selector"></select>
          </div>
          <div id="team_new_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="team_selector">Team Name <span class="req">*</span></label>
            <select id="team_selector" class="select_loader" data-g365_action="set_select" data-g365_type_new="team_names" data-g365_form_dest="team_add" data-g365_contributors="club_id|team_selector|level|division_selector" data-g365_contributors_req="club_id|team_selector|division_selector" data-g365_add_button="roster_add_button_wrap" disabled></select>
          </div>
        </div>
        <div id="roster_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;" >
          <button type="button" id="roster_add_button" class="site-button button secondary expanded form_loader" data-g365_type="rosters_event" data-g365_form_template="form_template" data-g365_form_dest="g365_roster_form" data-g365_load_target="g365_roster_form" data-g365_contributors="club_id|event_id|level|team_selector|division_selector" data-g365_contributors_req="club_id|event_id|level|division_selector" data-g365_limit="only,club_id,event_id,level,team_selector|dropdown,event_id" data-g365_form_load_min="true">Next</button>
        </div>
      </div>
    </div>
    <form id="g365_roster_form" class="primary-form" name="g365_roster_form" enctype="multipart/form-data" method="post" data-g365_type="rosters_event">
      <hr />
      <h3>Teams</h3>
      <div id="g365_roster_form_data" class="g365-form-data-wrapper">
        <p>You haven't added any teams yet.</p>
      </div>
      <div id="g365_roster_form_message" class="small-margin-top form_message hide"></div>
      <hr />
    </form>
  </div>
</div>
EOD;
?>