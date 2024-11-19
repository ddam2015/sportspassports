<?php
$team_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$team_registration_form_min_sl = <<<EOD
<div id="team_names_sl" class="form__wrapper white-bg small-padding tiny-margin-top">
  <form id="team_names_sl_fieldset" class="primary-form" name="g365_team_form" enctype="multipart/form-data" method="post" data-g365_type="team_names_sl">
    <h5>Add New Team</h5>
    <div class="small-margin-bottom">
      <input type="hidden" name="team_names_sl[proc_type]" value="proc_data">
      <input type="hidden" name="team_names_sl[data][org]" id="team_names_sl_club_id" value="{{org_id}}" data-g365_short_name="{{org_name}}" data-g365_immutable="true">
    </div>
    <div class="row">
    
      <div class="small-margin-bottom columns small-7">
        <label>Organization Name</label>
        <p style="background: #ddd; border: 1px solid #f2f2f2; padding: 10px;">{{org_name}}</p>
      </div>
      <div class="small-margin-bottom columns small-5">
        <label for="team_level">Grade/Age of Team <span class="req">*</span></label>
        <select name="team_names_sl[data][level]" id="team_names_sl_team_level" class="team_level" required>
          <option value="">-- Please Select</option>
          <optgroup label="Boys">
            <option value="8" data-g365_short_name="8U">8U / 2nd Grade</option>
            <option value="9" data-g365_short_name="9U">9U / 3rd Grade</option>
            <option value="10" data-g365_short_name="10U">10U / 4th Grade</option>
            <option value="11" data-g365_short_name="11U">11U / 5th Grade</option>
            <option value="12" data-g365_short_name="12U">12U / 6th Grade</option>
            <option value="13" data-g365_short_name="13U">13U / 7th Grade</option>
            <option value="14" data-g365_short_name="14U">14U / 8th Grade</option>
            <option value="15" data-g365_short_name="15U">15U</option>
            <option value="16" data-g365_short_name="16U">16U</option>
            <option value="17" data-g365_short_name="17U">17U</option>
          </optgroup>
          <optgroup label="Girls">
            <option value="40" data-g365_short_name="Girls 4th">Girls 4th Grade</option>
            <option value="41" data-g365_short_name="Girls 5th">Girls 5th Grade</option>
            <option value="42" data-g365_short_name="Girls 6th">Girls 6th Grade</option>
            <option value="43" data-g365_short_name="Girls 7th">Girls 7th Grade</option>
            <option value="44" data-g365_short_name="Girls 8th">Girls 8th Grade</option>
            <option value="45" data-g365_short_name="15U">15U</option>
            <option value="46" data-g365_short_name="16U ">16U</option>
            <option value="47" data-g365_short_name="17U">17U</option>
          </optgroup>
        </select>
      </div>
      <div class="small-margin-bottom columns small-7">
        <label for="team_names_sl_name">Team Description (optional)</label>
        <input type="text" name="team_names_sl[data][name]" id="team_names_sl_name" class="full-width" placeholder="i.e. 'Blue', 'Red Elite', 'Penguins'...(max 30 characters)" maxlength="30" value="{{name}}">
      </div>
    </div>
    <div style="background: #ddd; border: 1px solid #f2f2f2;">
      <p class="no-margin-bottom clear block text-center">Team Name will display as</p>
      <h3 class="change-title tiny-padding text-center" data-default_value="Team" data-g365_change_targets="#team_names_sl_club_id|#team_names_sl_team_level|#team_names_sl_name">{{name}}</h3>
    </div>
    <div id="team_names_sl_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button no-margin-bottom g365-primary-submit" type="submit" value="submit">Create team/roster</button>
  </form>
</div>
EOD;
$team_registration_form_min = <<<EOD
<div id="{{field-set-id}}" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}_fieldset" class="primary-form" name="g365_team_form" enctype="multipart/form-data" method="post" data-g365_type="team_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h5>Add New Team</h5>
    <p class="no-margin-bottom clear block">Full Team Name</p>
    <h3 class="change-title name-emphasis tiny-padding" data-default_value="Team" data-g365_change_targets="#{{field-set-id}}_club_id|#{{field-set-id}}_team_level|#{{field-set-id}}_name">{{name}}</h3>
    <div class="small-margin-bottom">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[data][org]" id="{{field-set-id}}_club_id" value="{{org_id}}" data-g365_immutable="true">
    </div>
    <div class="row">
      <div class="small-margin-bottom columns small-5">
        <label for="team_level">Grade/Age Level of Team <span class="req">*</span></label>
        <select name="{{field-set-id}}[data][level]" id="{{field-set-id}}_team_level" class="team_level" required>
          <option value="">-- Please Select</option>
          <optgroup label="Boys">
            <option value="8" data-g365_short_name="8U">8U / 2nd Grade</option>
            <option value="9" data-g365_short_name="9U">9U / 3rd Grade</option>
            <option value="10" data-g365_short_name="10U">10U / 4th Grade</option>
            <option value="11" data-g365_short_name="11U">11U / 5th Grade</option>
            <option value="12" data-g365_short_name="12U">12U / 6th Grade</option>
            <option value="13" data-g365_short_name="13U">13U / 7th Grade</option>
            <option value="14" data-g365_short_name="14U">14U / 8th Grade</option>
            <option value="15" data-g365_short_name="15U">15U / Frosoph</option>
            <option value="16" data-g365_short_name="16U">16U / JV</option>
            <option value="17" data-g365_short_name="17U">17U / Varsity</option>
          </optgroup>
          <optgroup label="Girls">
            <option value="40" data-g365_short_name="Girls 4th">Girls 4th Grade</option>
            <option value="41" data-g365_short_name="Girls 5th">Girls 5th Grade</option>
            <option value="42" data-g365_short_name="Girls 6th">Girls 6th Grade</option>
            <option value="43" data-g365_short_name="Girls 7th">Girls 7th Grade</option>
            <option value="44" data-g365_short_name="Girls 8th">Girls 8th Grade</option>
            <option value="46" data-g365_short_name="Girls JV">JV Girls</option>
            <option value="47" data-g365_short_name="Varsity Girls">Varsity Girls</option>
          </optgroup>
        </select>
      </div>
      <div class="small-margin-bottom columns small-7">
        <label for="{{field-set-id}}_name">Team Name (w/o level)</label>
        <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="i.e. 'Blue', 'Red Elite', 'Penguins'...(max 30 characters)" maxlength="30" value="{{name}}">
      </div>
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button no-margin-bottom g365-primary-submit" type="submit" value="submit">Store team</button>
  </form>
</div>
EOD;
$team_registration_form = <<<EOD
    <div id="{{field-set-id}}" class="g365_form">
      <hr class="g365-divider" />
      <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
        <span class="input-group-field"></span>
        <a class="input-group-button button hide-on-disable">edit</a>
      </div>
      <div id="{{field-set-id}}_fieldset" class="gset small-padding">
        <div><a class="site-close-button remove-button site-button button">remove</a></div>
        <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
        <div class="small-margin-bottom">
          <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
          <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
          <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
          <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
          <input type="date" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" min="{{birth-min}}" max="{{birth-max}}" placeholder="12-30-1970" value="{{birthday}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_grad_year">Graduation Year</label>
          <input type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grad_year" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_height">Height</label>
          <input type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}">'
          <input type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}">"
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_weight">Weight</label>
          <input type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">lbs.
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_address">Address </label>
          <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete full-width" maxlength="100" placeholder="Street Address*" value="{{address}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city full-width" maxlength="100" placeholder="City*" value="{{city}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
          <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state full-width" required>
            <option value="">-- Please Select</option>
            <option value="NULL">-- Not Listed</option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
          </select>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_zip">Zip </label>
          <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip full-width" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_county">Country </label>
          <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country full-width" maxlength="100" placeholder="Country" value="{{country}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_phone">Phone </label>
          <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone full-width" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_position">Position</label>
          <input type="hidden" name="{{field-set-id}}[data][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
          <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input full-width" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_club_names">Club Team</label>
          <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
          <input type="text" class="g365_livesearch_input full-width" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_school_names">School</label>
          <input type="hidden" name="{{field-set-id}}[data][school]" id="{{field-set-id}}_school" value="{{school_id}}">
          <input type="text" class="g365_livesearch_input full-width" id="{{field-set-id}}_school_names" value="{{school_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_school" data-g365_form_dest="{{field-set-id}}_school_add" data-g365_type="school_names" placeholder="Enter School Name" autocomplete="off">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_instagram">Instagram</label>
          <input type="text" name="{{field-set-id}}[data][instagram]" class="full-width" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
        </div>
        <div class="large-margin-bottom">
          <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
          <div class="crop_img" data-g365_croppie_img_url="{{profile_img_url}}">
            <div class="cropped_img">
              <img src="{{profile_img_url}}" />
            </div>
            <div class="crop_upload">
              <div class="crop_upload_canvas_wrap hide">
                <div class="crop_upload_canvas"></div>
              </div>
              <input type="hidden" class="croppie_img" data-g365_name="{{field-set-id}}[data][profile_img]">
              <input type="hidden" class="croppie_img_data" data-g365_name="{{field-set-id}}[data][profile_img_data]">
              <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
            </div>
            <a class="site-button g365-small-button remove-croppie in-block small-margin-top hide">Remove Image</a>
          </div>
        </div>
        <div>
          <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Done with Player</a>
        </div>
      </div>
    </div>
EOD;
$team_registration_init = <<<EOD
<div id="g365_team_form_wrap">
  <h1 class="section_title">Player Profile Form<br><small>Find or register players</small></h1>
  <p>Please fillout this form to make your player available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="pl_name">
      <label for="player_names">Player Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input full-width block" data-g365_action="load_form" data-g365_type="player_names" data-g365_form_dest="g365_team_form" placeholder="Enter Player Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_team_form" class="primary-form" name="g365_team_form" enctype="multipart/form-data" method="post" data-g365_type="player_names">
      <div id="g365_team_form_data"></div>
      <div id="g365_team_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_team_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
?>