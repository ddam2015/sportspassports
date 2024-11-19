<?php
$event_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$event_registration_input_item = <<<EOD
  <li id="{{field-set-id}}">
    <input type="hidden" name="{{field-set-id-flat}}[data][events][{{id}}]" value="1" required>
    <div class="input-group tiny-margin-bottom">
      <span class="input-group-label">
        {{name}} <small>{{eventtime}}</small>
      </span>
      <div class="input-group-button">
        <a class="button site-close-button"><span>remove</span></a>
      </div>
    </div>
  </li>
EOD;
$event_registration_input_item_roster_sl = <<<EOD
  <li id="{{field-set-id}}">
    <input type="hidden" name="tournament_roster_admin[data][events][{{id}}]" value="1" required>
    <div class="input-group tiny-margin-bottom">
      <span class="input-group-label">
        {{name}} <small>{{eventtime}}</small>
      </span>
      <div class="input-group-button">
        <a class="button site-close-button"><span>remove</span></a>
      </div>
    </div>
  </li>
EOD;
$event_registration_form_min = <<<EOD
<div id="{{field-set-id}}_fieldset" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3 class="change-title" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <input type="date" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" min="{{birth-min}}" max="{{birth-max}}" placeholder="12-30-1975" value="{{birthday}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="expanded" required>
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team <span class="req">*</span></label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}" required>
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off">
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Player Data</button>
  </form>
</div>
EOD;
$event_registration_form_min_full = <<<EOD
<div data-player-form id="{{field-set-id}}_fieldset" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3 class="change-title" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <input type="date" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" min="{{birth-min}}" max="{{birth-max}}" placeholder="12-30-1970" value="{{birthday}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grade">Grade</label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" required>
          <option value="">-- Please Select</option>
          <option value="2">2nd Grade</option>
          <option value="3">3rd Grade</option>
          <option value="4">4th Grade</option>
          <option value="5">5th Grade</option>
          <option value="6">6th Grade</option>
          <option value="7">7th Grade</option>
          <option value="8">8th Grade</option>
          <option value="9">9th Grade</option>
          <option value="10">10th Grade</option>
          <option value="11">11th Grade</option>
          <option value="12">12th Grade</option>
        </select>
        <span class="input-group-label">Graduation Year</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][grad_year]" id="{{field-set-id}}_grade_grad_yr" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_recard_img">Proof of Grade (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}">
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}">
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_zip">Zip </label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_country">Country </label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_position">Position</label>
      <input type="hidden" name="{{field-set-id}}[data][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][profile_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Player Data</button>
  </form>
</div>
EOD;
$event_registration_form = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable">edit</a>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <div><a class="site-close-button remove-button button">remove</a></div>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <input type="date" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" min="{{birth-min}}" max="{{birth-max}}" placeholder="12-30-1970" value="{{birthday}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grad_year">Graduation Year</label>
      <input type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grad_year" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}">
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}">
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_zip">Zip </label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_county">Country </label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_position">Position</label>
      <input type="hidden" name="{{field-set-id}}[data][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div>
      <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Done with Player</a>
    </div>
  </div>
</div>
EOD;
$event_registration_form_full = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable">edit</a>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <div><a class="site-close-button remove-button button">remove</a></div>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <input type="date" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" min="{{birth-min}}" max="{{birth-max}}" placeholder="12-30-1970" value="{{birthday}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grad_year">Graduation Year</label>
      <input type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grad_year" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_recard_img">Proof of Grade (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}">
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}">
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_zip">Zip </label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_county">Country </label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-countryexpanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_position">Position</label>
      <input type="hidden" name="{{field-set-id}}[data][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off" data-ls_no_add="true">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][profile_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Done with Player</a>
    </div>
  </div>
</div>
EOD;
//add croppie
//         <div class="large-margin-bottom">
//           <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
//           <div class="crop_img" data-g365_crop_settings="profile" data-g365_profile_img_url="{{profile_img_url}}">
//             <div class="cropped_img">
//               <img src="{{profile_img_url}}" />
//             </div>
//             <div class="crop_upload">
//               <div class="crop_upload_canvas_wrap hide">
//                 <div class="crop_upload_canvas"></div>
//               </div>
//               <input type="hidden" class="profile_img" data-g365_name="{{field-set-id}}[data][profile_img]">
//               <input type="hidden" class="profile_img_data" data-g365_name="{{field-set-id}}[data][profile_img_data]">
//               <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
//             </div>
//             <a class="site-button remove-profile small-margin-top hide">Remove Image</a>
//           </div>
//         </div>
//add school
//     <div class="tiny-margin-bottom tiny-padding no-input-margin">
//       <label for="{{field-set-id}}_school_names">School</label>
//       <input type="hidden" name="{{field-set-id}}[data][school]" id="{{field-set-id}}_school" value="{{school_id}}">
//       <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_school_names" value="{{school_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_school" data-g365_form_dest="{{field-set-id}}_school_add" data-g365_type="school_names" placeholder="Enter School Name" autocomplete="off">
//     </div>

$event_registration_init = <<<EOD
<div id="g365_player_form_wrap">
  <h1 class="section_title">Add Player</h1>
  <p>Please fillout this form to make your player available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="pl_name">
      <label for="player_names">Player Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input expanded" data-g365_action="load_form" data-g365_form_template="form_template" data-g365_type="player_names" data-g365_form_dest="g365_player_form" placeholder="Enter Player Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_player_form" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names">
      <div id="g365_player_form_data"></div>
      <div id="g365_player_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
$event_registration_init_admin = <<<EOD
<div id="g365_player_form_wrap">
  <h1 class="section_title">Add Player</h1>
  <p>Please fillout this form to make your player available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="pl_name">
      <label for="player_names">Player Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input expanded" data-g365_action="load_form" data-g365_form_template="form_template" data-g365_type="player_names_admin" data-g365_form_dest="g365_player_form" placeholder="Enter Player Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_player_form" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names">
      <div id="g365_player_form_data"></div>
      <div id="g365_player_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;

?>