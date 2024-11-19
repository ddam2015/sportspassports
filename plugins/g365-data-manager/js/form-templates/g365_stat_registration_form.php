<?php
$stat_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$stat_registration_form_min = <<<EOD
<div id="{{field-set-id}}_fieldset" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="pl_ev" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3 class="change-title" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][player][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field" id="{{field-set-id}}_birthday_mo" data-g365_select="{{birthday_mo}}" required>
          <option value="">Please select</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select>
        <span class="input-group-label">Month</span>
        <select class="input-group-field" id="{{field-set-id}}_birthday_dy" data-g365_select="{{birthday_dy}}" required>
          <option value="">Please select</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        <span class="input-group-label">Day</span>
        <select class="input-group-field" id="{{field-set-id}}_birthday_yr" data-g365_select="{{birthday_yr}}" required>
          {{current_birth_years}}
        </select>
        <span class="input-group-label">Year</span>
      </div>
      <input type="hidden" name="{{field-set-id}}[data][player][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
        <option value="">-- Please Select --</option>
        <option value="Y_Md">Youth Medium</option>
        <option value="Y_Lg">Youth Large</option>
        <option value="Y_XL">Youth X-Large</option>
        <option value="A_Sm">Adult Small</option>
        <option value="A_Md">Adult Medium</option>
        <option value="A_Lg">Adult Large</option>
        <option value="A_XL">Adult X-Large</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" required>
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
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][player][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
      <label for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][player][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_county">Country </label>
      <input type="text" name="{{field-set-id}}[data][player][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][player][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_position">Position</label>
      <input type="hidden" name="{{field-set-id}}[data][player][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][player][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][player][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][profile_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Confirm Player Info</button>
  </form>
</div>
EOD;
$stat_registration_form_mem = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_ev_id">{{name}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_ev_id" value="{{player}}" data-g365_data_key="pl_ev_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_pm" value="{{event}}" data-g365_data_key="event_id_pm" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
  </div>
</div>
EOD;
$stat_registration_form_mem_OLD = <<<EOD
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
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id_ct"></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="PLAYER" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_id" value="{{player}}" data-g365_data_key="pl_ev_id" data-g365_immutable="true" data-placer="">
      <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_ct" value="{{event}}" data-g365_data_key="event_id_pm" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][player][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][birthday]" id="{{field-set-id}}_birthday" class="expanded g365-input-formatter" data-g365_input_format="date" placeholder="12-30-1970" value="{{birthday}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
        <option value="">-- Please Select --</option>
        <option value="Y_Md">Youth Medium</option>
        <option value="Y_Lg">Youth Large</option>
        <option value="Y_XL">Youth X-Large</option>
        <option value="A_Sm">Adult Small</option>
        <option value="A_Md">Adult Medium</option>
        <option value="A_Lg">Adult Large</option>
        <option value="A_XL">Adult X-Large</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" required>
        <option value="">Please select</option>
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
      <input class="input-group-field" type="hidden" name="{{field-set-id}}[data][player][grad_year]" id="{{field-set-id}}_grade_grad_yr" value="{{grad_year}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_recard_img">Proof of Grade (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][player][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
      <input type="tel" name="{{field-set-id}}[data][player][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_county">Country </label>
      <input type="text" name="{{field-set-id}}[data][player][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][player][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_position">Position</label>
      <input type="hidden" name="{{field-set-id}}[data][player][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][player][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off" data-ls_no_add="true">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][player][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][profile_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <a class="text-right block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$stat_registration_form_admin = <<<EOD
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
    <small class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_event_id_ct"></small>
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_id" value="{{player}}" data-g365_data_key="pl_ev_id" data-g365_immutable="true" data-placer="">
      <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_ct" value="{{event}}" data-g365_data_key="event_id_pm" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][player][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_verified">Verified <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][verified]" id="{{field-set-id}}_verified" data-g365_select="{{verified}}" required>
        <option value="0">Unstarted</option>
        <option value="1">Awaiting Certification</option>
        <option value="2">Certified</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field" id="{{field-set-id}}_birthday_mo" data-g365_select="{{birthday_mo}}" required>
          <option value="">Please select</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select>
        <span class="input-group-label">Month</span>
        <select class="input-group-field" id="{{field-set-id}}_birthday_dy" data-g365_select="{{birthday_dy}}" required>
          <option value="">Please select</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        <span class="input-group-label">Day</span>
        <select class="input-group-field" id="{{field-set-id}}_birthday_yr" data-g365_select="{{birthday_yr}}" required>
          {{current_birth_years}}
        </select>
        <span class="input-group-label">Year</span>
      </div>
      <input type="hidden" name="{{field-set-id}}[data][player][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
        <option value="">-- Please Select --</option>
        <option value="Y_Md">Youth Medium</option>
        <option value="Y_Lg">Youth Large</option>
        <option value="Y_XL">Youth X-Large</option>
        <option value="A_Sm">Adult Small</option>
        <option value="A_Md">Adult Medium</option>
        <option value="A_Lg">Adult Large</option>
        <option value="A_XL">Adult X-Large</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" required>
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
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="5" value="{{height_ft}}">
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="11" value="{{height_in}}">
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][player][weight]" id="{{field-set-id}}_weight" min="30" max="500" placeholder="102" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][player][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][player][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][player][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
      <input type="tel" name="{{field-set-id}}[data][player][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_county">Country </label>
      <input type="text" name="{{field-set-id}}[data][player][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][player][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_position">Position</label>
      <input type="hidden" name="{{field-set-id}}[data][player][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][player][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off" data-ls_no_add="true">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][player][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
     <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_twitter">Twitter</label>
      <input type="text" name="{{field-set-id}}[data][player][twitter]" class="expanded" id="{{field-set-id}}_twitter" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][player][profile_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <a class="text-right block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$stat_registration_form = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_pm">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_ev_id">{{name}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
    <div class="">
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_ev_id" value="{{player}}" data-g365_data_key="pl_ev_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_pm" value="{{event}}" data-g365_data_key="event_id_pm" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[stat]" id="{{field-set-id}}_passport_date" value="{{stat}}" data-g365_data_key="passport_date" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[pp_action]" id="{{field-set-id}}_pp_action" value="{{pp_action}}" data-g365_data_key="pp_action" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[pp_year]" id="{{field-set-id}}_pp_year" value="{{pp_year}}" data-g365_data_key="pp_year" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
  </div>
</div>
EOD;
//for scope scouting
$stat_registration_form_ss = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_pm">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_ev_id">{{name}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
    <div class="">
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_ev_id" value="{{player}}" data-g365_data_key="pl_ev_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_pm" value="{{event}}" data-g365_data_key="event_id_pm" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event_ss]" id="{{field-set-id}}_event_id_pm_ss" value="{{event_ss}}" data-g365_data_key="event_id_pm_ss" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
  </div>
</div>
EOD;

//         <input type="text" id="pl_cp_names" class="g365_livesearch_input expanded block" data-g365_action="load_form" data-g365_form_template_new="form_template_min" data-g365_type="player_names" data-g365_form_dest="g365_pl_cp_form" data-g365_form_dest_new="player_add" data-g365_select_click="pl_cp_add_button" data-g365_contributors="pl_cp_id|event_id_cp" data-g365_contributors_req="event_id_cp" data-g365_limit="only,pl_cp_id,event_id_cp|dropdown,event_id_cp" placeholder="Enter Player Name" autocomplete="off" autofocus>
$stat_registration_form_camp = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_cp">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_cp_id">{{name}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
    <div class="">
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_cp_id" value="{{player}}" data-g365_data_key="pl_cp_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_cp" value="{{event}}" data-g365_data_key="event_id_cp" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
  </div>
</div>
EOD;

$stat_registration_form_dcp = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
    <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
      <div class="input-group-field">
        <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_cp">{{event_short}}</small>
        <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_cp_id">{{name}}</div>
      </div>
      <div class="input-group-button">        
        <div><a class="site-close-button button"><span>remove</span></a></div>
      </div>
    </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_cp_id" value="{{player}}" data-g365_data_key="pl_cp_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_cp" value="{{event}}" data-g365_data_key="event_id_cp" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
  </div>
</div>  
EOD;

$stat_registration_form_passport = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_cp">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_cp_id|#{{field-set-id}}_pl_cp_type" data-g365_change_delimiter=" | ">{{name}} | {{unlock_target_title}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
    <div class="">
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_cp_id" value="{{player}}" data-g365_data_key="pl_cp_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_cp" value="{{event}}" data-g365_data_key="event_id_cp" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[unlock_season]" id="{{field-set-id}}_pl_cp_type" value="{{unlock_season}}" data-g365_data_key="pl_cp_type" data-g365_immutable="true" data-g365_short_name="{{unlock_target_title}}" data-placer="">
  </div>
</div>
EOD;
// Player passport for monthly subscription
$stat_registration_form_monthly_passport = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_cp">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_cp_id|#{{field-set-id}}_pl_cp_type" data-g365_change_delimiter=" | ">{{name}} | {{unlock_target_title}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
    <div class="">
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_cp_id" value="{{player}}" data-g365_data_key="pl_cp_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_cp" value="{{event}}" data-g365_data_key="event_id_cp" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[unlock_monthly]" id="{{field-set-id}}_pl_cp_type" value="{{unlock_monthly}}" data-g365_data_key="pl_cp_type" data-g365_immutable="true" data-g365_short_name="{{unlock_target_title}}" data-placer="">
  </div>
</div>
EOD;
$stat_registration_init_club_team = <<<EOD
<div id="g365_pl_ct_form_wrap">
  <h1 class="section_title">Add Player</h1>
  <div class="form-holder">
    <form id="g365_pl_ct_form" class="primary-form" name="g365_pl_ct_form" enctype="multipart/form-data" method="post" data-g365_type="club_team">
      <div id="g365_pl_ct_form_data" class="g365-form-data-wrapper"></div>
      <div id="g365_pl_ct_form_message" class="small-margin-top form_message hide"></div>
      <hr />
    </form>
    <div class="g365_set_default">
      <select id="event_id_ct" class="select_local hide" data-g365_auto_advance="true" data-g365_additional_lock="g365_pl_ct_form_data" data-g365_deps_start="pl_ct_names" required></select>
    </div>
    <div class="form-init gset tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ct_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_ct" data_g365_change_totals="true"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_ct_id" data-g365_contingent="pl_ct_add_button_wrap" data-g365_error_target="pl_ct_names" required>
        <input type="text" id="pl_ct_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="pl_ev" data-ls_target="pl_ct_id" data-g365_form_dest="player_add" data-ls_user_ac="{{user_ac}}" data-g365_form_template_new="form_template_min" data-g365_load_target="player_names_ct" data-g365_select_click="pl_ct_add_button" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div id="pl_ct_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display: none;">
        <button type="button" id="pl_ct_add_button" class="site-button button secondary expanded form_loader" data-g365_type="club_team" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_ct_form" data-g365_load_target="g365_pl_ct_form" data-g365_contributors="pl_ct_id|event_id_ct" data-g365_contributors_req="event_id_ct" data-g365_limit="only,pl_ct_id,event_id_ct|dropdown,event_id_ct" data-g365_form_load_min="true">Add Player</button>
      </div>
    </div>
  </div>
  <hr>
</div>
EOD;
//         <input type="text" id="pl_ct_names" class="g365_livesearch_input expanded block" data-g365_action="load_form" data-g365_form_template_new="form_template_min" data-g365_type="player_names" data-g365_form_dest="g365_pl_ct_form" data-g365_form_dest_new="player_add" data-g365_select_click="pl_ct_add_button" data-g365_contributors="pl_ct_id|event_id_ct" data-g365_contributors_req="event_id_ct" data-g365_limit="only,pl_ct_id,event_id_ct|dropdown,event_id_ct" placeholder="Enter Player Name" autocomplete="off" autofocus>
$stat_registration_form_club_team = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_ct">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_pl_ct_id">{{name}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[player]" id="{{field-set-id}}_pl_ct_id" value="{{player}}" data-g365_data_key="pl_ct_id" data-g365_immutable="true" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_ct" value="{{event}}" data-g365_data_key="event_id_ct" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
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
//               <input type="hidden" class="profile_img" data-g365_name="{{field-set-id}}[data][player][profile_img]">
//               <input type="hidden" class="profile_img_data" data-g365_name="{{field-set-id}}[data][player][profile_img_data]">
//               <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
//             </div>
//             <a class="site-button remove-profile small-margin-top hide">Remove Image</a>
//           </div>
//         </div>
$stat_registration_init_game = <<<EOD
<div id="g365_game_stat_form_wrap">
  <div class="form-holder full_width_container">
    <form id="g365_game_stat_form" class="primary-form" name="g365_game_stat_form" enctype="multipart/form-data" method="post" data-g365_type="gm_st">
      <div id="g365_game_stat_form_data" class="g365-form-data-wrapper teams-stats grid-x grid-margin-x no-padding tiny-margin-bottom"></div>
      <div id="g365_game_stat_form_submit" class="g365_form_sub_block">
        <hr />
        <div id="g365_game_stat_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit expanded" type="submit" value="submit">Commit Game Result</button>
      </div>
    </form>
  </div>
</div>
EOD;
// $stat_registration_init_save_game = <<<EOD
// <div id="g365_game_stat_form_wrap">
//   <div class="form-holder">
//     <form id="g365_game_stat_form" class="primary-form" name="g365_game_stat_form" enctype="multipart/form-data" method="post" data-g365_type="gm_save">
//       <div id="g365_game_stat_form_data" class="g365-form-data-wrapper teams-stats grid-container grid-x grid-margin-x no-padding tiny-margin-bottom"></div>
//       <div id="g365_game_stat_form_submit" class="g365_form_sub_block">
//         <hr />
//         <div id="g365_save_game_stat_form_message" class="small-margin-top form_message hide"></div>
//         <button class="site-button button g365-primary-submit expanded" type="submit" value="submit">Save current game result</button>
//       </div>
//     </form>
//   </div>
// </div>
// EOD;
$stat_registration_game_form = <<<EOD
<div>
  <input type="hidden" name="{{field-set-id-flat}}[id]" value="{{id}}">
  <input type="hidden" name="{{field-set-id-flat}}[data][event_id]" value="{{event_id}}">
</div>
<a class="button warning expanded smalll-padding" href="/account/stat_keep/?ev_id={{event_id}}">Return to Court List</a>
<div class="home_team_roster cell small-12 large-6">
  <div class="grid-x tiny-padding primary-border">
    <div class="home_team_name large-12">
      <h2 class="text-center cell small-12 medium-12 large-12">{{home_team}}</h2>
      <a class="button cell small-12 medium-12 large-12" id="home_pl_zero">Add Home Player Zero</a>
    </div>
    <div class="home_team_inactive_players inactive_players large-2">
      <h4 class="text-center">INACTIVE</h4>
      <div class="cell small-12">
        <div id="drop_home_inactive" class="drop drop_home inactive">
          {{home_players}}
        </div>
      </div>
    </div>
    <div class="home_team_active_players large-10">
      <h4 class="text-center">ACTIVE</h4>
      <div class="cell small-12">
        <div id="drop_home_active" class="drop drop_home active sortable-main" data-sort_pointer="#drop_home_active, #drop_home_inactive" data-sort_connect=".drop_home"></div>
      </div>
    </div>
  </div>
</div>
<div class="away_team_roster cell small-12 large-6">
  <div class="grid-x tiny-padding primary-border">
    <div class="away_team_name large-12">
      <h2 class="text-center cell small-12 medium-12 large-12">{{away_team}}</h2>
      <a class="button cell small-12 medium-12 large-12" id="away_pl_zero">Add Away Player Zero</a>
    </div>
    <div class="away_team_active_players large-10">
      <h4 class="text-center">ACTIVE</h4>
      <div class="cell small-12">
        <div id="drop_away_active" class="drop drop_away active sortable-main" data-sort_pointer="#drop_away_active, #drop_away_inactive" data-sort_connect=".drop_away"></div>
      </div>
    </div>
    <div class="away_team_inactive_players inactive_players large-2">
      <h4 class="text-center">INACTIVE</h4>
      <div class="cell small-12">
        <div id="drop_away_inactive" class="drop drop_away inactive">
          {{away_players}}
        </div>
      </div>
    </div>
  </div>
</div>
EOD;
$stat_registration_game_pl = <<<EOD
<div id="{{field-set-id}}" class="draggable grid-x grid-margin-x small-margin-collapse tiny-margin-bottom" data-jnum={{j_num}}>
  <input type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][player]" value="{{id}}">
  <input type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][homeaway]" value="{{homeaway}}">
  <input class="active_pl_tt" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][time_pl]" value="{{time_pl}}">
  <input class="active_pl_t_log" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][time_log]" value="{{time_log}}">
  <div class="cell hide-for-small-only medium-4 large-3 handle large_profile_img" style="background: black url({{profile_img}}) no-repeat center; background-size: cover;">
    <a class="button sub_button no-margin-bottom" id="pl_sub_button_{{field-set-id}}">+</a>
    <div class="large_profile_title">
      <h3 class="profile_title no-margin-bottom">
        <span>{{j_num}}</span>
        <small>{{name}}</small>
      </h3>
    </div>
  </div>
  <div class="cell small-12 medium-8 large-9">
   <div class="grid-x hide-for-active">  
    <div class="grid-x">
      <div class="cell small-1 hide-for-medium">
        <img src="{{profile_img}}">
      </div>
      <div class="cell small-8 medium-auto"><h3 class="no-margin-bottom small-small-margin-left">{{name}}</h3></div>
      <div class="cell small-3 medium-shrink"><h3 class="no-margin-bottom text-right small-small-margin-right">&nbsp{{j_num}}</h3></div>
    </div>
    <a style="font-size:15px;padding:0" class="cell button sub_button no-margin-bottom small-12 medium-12 large-12" id="pl_sub_button_{{field-set-id}}">+</a>    
   </div>
    <table class="player_stat no-margin-bottom text-left" id="{{homeaway}}_team">
      <tr>
        <td colspan="3">
          <table class="no-margin-bottom text-left">
            <tr>
              <th>PTS</th>
              <td><a class="minus_one_point button" id="{{field-set-id}}">-</a></td>
              <td><a class="one_point button" id="{{field-set-id}}">1</a></td>
              <td><a class="two_point button" id="{{field-set-id}}">2</a></td>
              <td><a class="three_point button" id="{{field-set-id}}">3</a></td>
              <td><span id="indi_player_pub_point-{{field-set-id}}" class="">{{pts}}</span><input id="indi_player_point-{{field-set-id}}" class="indi_player_point {{homeaway}}_team" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][pts]" value="{{pts}}"></td>
            </tr>
          </table>
        </td>
        <td colspan="3">
          <table class="no-margin-bottom text-left">
            <tr>
              <th>3PM</th>
              <td><a class="minus_three_point button" id="{{field-set-id}}">-</a></td>
              <td><span id="indi_player_three_pt_three_point-{{field-set-id}}" class="">{{three_pt}}</span><input id="indi_player_three_point-{{field-set-id}}" class="indi_player_three_point " type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][three_pt]" value="{{three_pt}}"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <table class="no-margin-bottom text-left">
            <tr>
              <th>REB</th>
              <td><a class="minus_one_reb button" id="{{field-set-id}}">-</a></td>
              <td><a class="one_reb button" id="{{field-set-id}}">+</a></td>
              <td><span id="indi_player_pub_reb-{{field-set-id}}" class="">{{rbs}}</span><input id="indi_player_reb-{{field-set-id}}" class="indi_player_reb" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][rbs]" value="{{rbs}}"></td>
            </tr>
          </table>
        </td>
        <td colspan="3">
          <table class="no-margin-bottom text-left">
            <tr>
              <th>AST</th>
              <td><a class="minus_one_ast button" id="{{field-set-id}}">-</a></td>
              <td><a class="one_ast button" id="{{field-set-id}}">+</a></td>
              <td><span id="indi_player_pub_ast-{{field-set-id}}" class="">{{ast}}</span><input id="indi_player_ast-{{field-set-id}}" class="indi_player_ast" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][ast]" value="{{ast}}"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <table class="no-margin-bottom text-left">
            <tr>
              <th>STL</th>
              <td><a class="minus_one_st button" id="{{field-set-id}}">-</a></td>
              <td><a class="one_st button" id="{{field-set-id}}">+</a></td>
              <td><span id="indi_player_pub_st-{{field-set-id}}" class="">{{stl}}</span><input id="indi_player_st-{{field-set-id}}" class="indi_player_st" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][stl]" value="{{stl}}"></td>
            </tr>
          </table>
        </td>
        <td colspan="3">
          <table class="no-margin-bottom text-left">
            <tr>
              <th>BLK</th>
              <td><a class="minus_one_blk button" id="{{field-set-id}}">-</a></td>
              <td><a class="one_blk button" id="{{field-set-id}}">+</a></td>
              <td><span id="indi_player_pub_blk-{{field-set-id}}" class="">{{blk}}</span><input id="indi_player_blk-{{field-set-id}}" class="indi_player_blk" type="hidden" name="{{field-set-id-flat}}[data][players][{{id}}][stats][blk]" value="{{blk}}"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</div>
EOD;
//         <input type="text" id="pl_ct_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="player_names" data-ls_target="pl_ct_id" data-g365_form_dest="pl_ct_add"
// data-g365_type_new="club_team" data-g365_form_template_new="form_template" data-g365_form_dest_new="g365_pl_ct_form" data-g365_contributors="pl_ct_id|event_id_ct" data-g365_contributors_req="event_id_ct" data-g365_select_click="pl_ct_add_button" data-g365_additional_lock="g365_pl_ct_form_data" placeholder="Enter Player Name" autocomplete="off" autofocus>
$stat_registration_init = <<<EOD
<div id="g365_player_event_form_wrap">
  <h1 class="section_title">Player Event Data</h1>
  <div id="reload_button" class="hide button" onclick="location.reload()" data-g365_action="add_result">Add More Players</div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Players</span></a>
    <div class="form-init gset tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
        <label for="event_names">Event</label>
        <input type="hidden" id="event_id_pm" data-g365_short_name="{{name}}" data-g365_additional_data="{{divisions}}" value="{{event}}" data-g365_error_target="event_names" required>
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_ev_id" data-g365_contingent="player_event_add_button_wrap" value="{{player}}" data-g365_error_target="pl_ev_names" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data"  data-g365_type="pl_ev" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="event_id_pm" placeholder="Enter Player Name" autocomplete="off" autofocus value="{{player_name}}">
      </div>
      <div id="player_event_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <a id="player_event_add_button" class="site-button button form_loader" data-g365_type="player_event" data-g365_form_template="form_template" data-g365_form_dest="g365_player_event_form" data-g365_load_target="g365_player_event_form" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="pl_ev_id|event_id_pm" data-g365_limit="only,pl_ev_id,event_id_pm" data-g365_toggle_parent="init_toggle" data-g365_deps_start="pl_ev_names" tabindex=0>Next</a>
      </div>
    </div>
    <form id="g365_player_event_form" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="player_event" data-target_field="reload_button">
      <div id="g365_player_event_form_data"></div>
      <div id="g365_player_event_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_event_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;

$dcp_registration_init_camp = <<<EOD
<div id="g365_pl_cp_form_wrap" class="">
  <h1 class="section_title">Add Player</h1>
  <p>Instructions:<br>First: Search and choose The Stage event you want to register for using roman numerals in the event section.<br><br>
  Second: Enter the name of the player registering for this event. If player does not have an account select "+ADD PLAYER".<br><br>
  Third: Proceed to fill out the form and select "CONFIRM PLAYER INFO". Once completed select "ADD PLAYER EVENT".<br><br>
  Fourth: Select "SUBMIT PLAYER DATA". Once confirmation appears reload page.</p>
<br>
  <div class="form-holder dcp-registration">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Players</span></a>
    <div class="form-init form__wrapper tiny-padding">
      <div id="event-selector" class="dcp-registration tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
        <label for="event_names">Event</label>
        <input type="hidden" id="event_id_cp" data-g365_short_name="{{name}}" data-g365_additional_data="{{divisions}}" value="{{name}}" required>
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_cp" data-ls_no_add="true"  value="The Stage ACT">
      </div>   
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_cp"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_cp_id" data-g365_contingent="player_event_add_button_wrap" data-g365_error_target="pl_ev_names" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="dcp_pl_ev" data-ls_target="pl_cp_id" data-g365_form_dest="pl_ev_add" data-ls_user_ac="{{user_ac}}" data-g365_contributors="pl_cp_id|event_id_cp" placeholder="Enter Player Name" autocomplete="off" value="{{player_name}}">
      </div>
      <div id="player_event_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <a id="player_event_add_button" class="site-button button form_loader" data-g365_type="dcp_player_registration" data-g365_form_template="form_template" data-g365_form_dest="g365_player_event_form" data-g365_load_target="g365_player_event_form" data-g365_contributors="pl_cp_id|event_id_cp" data-g365_contributors_req="pl_cp_id|event_id_cp" data-g365_limit="only,pl_cp_id,event_id_cp" data-g365_toggle_parent="init_toggle" data-g365_deps_start="pl_ev_names" tabindex=0>Add Player Event</a>
      </div>
    </div>
    <form id="g365_player_event_form" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="dcp_player_registration" data-target_field="reload_button">
      <div id="g365_player_event_form_data"></div>
      <div id="g365_player_event_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_event_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;


$stat_registration_init_admin = <<<EOD
<div id="g365_player_event_form_wrap">
  <h1 class="section_title">Manage Player Stats</h1>
  <div class="form-holder">
    <div class="form-init gset tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="event_names">Event</label>
        <input type="hidden" id="event_id_pm" data-g365_error_target="event_names">
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus>
        <label>Date</label>
        <input type="hidden" id="passport_date" data-g365_contingent="pl_ev_add_button_wrap" data-g365_error_target="pl_ev_id" required="">
        <input type="text" id="datepicker" required>
        <input type="hidden"  id="pp_action" data-g365_contingent="pl_ev_add_button_wrap" data-g365_error_target="pl_ev_id" required="" value="unlock_pp">
        <input type="hidden"  id="pp_year" data-g365_contingent="pl_ev_add_button_wrap" data-g365_error_target="pl_ev_id" required="" value="2022">
        <label>Passport Unlock Options</label>
        <form action="">
          <input type="radio" id="unlock_pl_pp" name="pl_pp_radio" value="unlock_pp" checked>
          <label for="unlock_pl_pp">Unlock Passport</label><br>
          <input type="radio" id="lock_pl_pp" name="pl_pp_radio" value="lock_pp">
          <label for="lock_pl_pp">Lock Passport</label><br>
          <label>Passport Year Options</label>
          <input type="radio" id="pp_current_year" name="pp_year_radio" value="2024" checked>
          <label for="pp_current_year">Passport 2024</label><br>
          <input type="radio" id="pp_current_year" name="pp_year_radio" value="2023">
          <label for="pp_current_year">Passport 2023</label><br>
          <input type="radio" id="pp_current_year" name="pp_year_radio" value="2022">
          <label for="pp_current_year">Passport 2022</label><br>
          <input type="radio" id="pp_prev_year" name="pp_year_radio" value="2021">
          <label for="pp_prev_year">Passport 2021</label><br>
        </form>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_ev_id" data-g365_contingent="pl_ev_add_button_wrap" data-g365_error_target="pl_ev_id" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data"  data-g365_type="player_names_admin" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div id="player_event_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <a id="player_event_add_button" class="site-button button form_loader" data-g365_type="player_event" data-g365_form_template="form_template" data-g365_form_dest="g365_player_event_form" data-g365_load_target="g365_player_event_form" data-g365_contributors="pl_ev_id|event_id_pm|passport_date|pp_action|pp_year" data-g365_contributors_req="pl_ev_id|event_id_pm|passport_date|pp_action|pp_year" data-g365_limit="only,pl_ev_id,event_id_pm" tabindex=0>Add Player Event</a>
      </div>
   </div>
    <form id="g365_player_event_form" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="player_event">
      <div id="g365_player_event_form_data"></div>
      <div id="g365_player_event_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_event_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Player Data</button>
      </div>
    </form>
  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script>
  function formatDate(date){
    var new_date = new Date(date),
    month = '' + (new_date.getMonth() + 1),
    day = '' + new_date.getDate(),
    year = new_date.getFullYear();
    if (month.length < 2){ month = '0' + month; }
    if (day.length < 2){ day = '0' + day; }
    return [year, month, day].join('-') + " 00:00:00";
  }
  $("#datepicker").datepicker({
    onSelect: function(){
      var dateObject = $(this).datepicker('getDate');
      var formatedDate = new Date(dateObject).toLocaleDateString();
      document.getElementById('passport_date').value = formatDate(formatedDate);
    }
  });
  $('input[type=radio][name="pl_pp_radio"]').change(function() {
    document.getElementById('pp_action').value = $(this).val();
  });
  document.getElementById('pp_year').value = document.querySelector('input[type=radio][name="pp_year_radio"]:checked').value;
  $('input[type=radio][name="pp_year_radio"]').change(function() {
    document.getElementById('pp_year').value = $(this).val();
  });
  
</script>
EOD;

$stat_registration_init_admin_ss = <<<EOD
<div id="g365_player_event_form_wrap">
  <h1 class="section_title">Manage Player Scope Scouting Stats</h1>
  <div class="form-holder">
    <div class="form-init gset tiny-padding">
    
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="event_names_ss">Select Scope Scouting Event</label>
        <input type="hidden" id="event_id_pm_ss" data-g365_error_target="event_names_ss">
        <input type="text" id="event_names_ss" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm_ss" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus>
      </div>
      
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="event_names">Participating Event</label>
        <input type="hidden" id="event_id_pm" data-g365_error_target="event_names">
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_ev_id" data-g365_contingent="pl_ev_add_button_wrap" data-g365_error_target="pl_ev_id" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data"  data-g365_type="player_names_admin" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div id="player_event_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <a id="player_event_add_button" class="site-button button form_loader" data-g365_type="ss_player_event" data-g365_form_template="form_template" data-g365_form_dest="g365_player_event_form" data-g365_load_target="g365_player_event_form" data-g365_contributors="pl_ev_id|event_id_pm|event_id_pm_ss" data-g365_contributors_req="pl_ev_id|event_id_pm" data-g365_limit="only,pl_ev_id,event_id_pm|event_id_pm_ss" tabindex=0>Add Player Event</a>
      </div>
   </div>
    <form id="g365_player_event_form" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="ss_player_event">
      <div id="g365_player_event_form_data"></div>
      <div id="g365_player_event_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_event_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Player Data</button>
      </div>
    </form>
  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script>
  
  
</script>
EOD;

//Create Player add button <a class="button g365-button g365_add_button no-margin-bottom">+ add player</a>
$stat_registration_init_mem_ind = <<<EOD
<div id="g365_pl_cert_form_wrap">
  <h1 class="section_title">Manage Player Stats</h1>
  <p>Please search for your player to start. If the player has been already added to the database, or has previously attended an event, they will be listed below. Click on the name to begin claiming the player profile.</p> 
  <p>If the player you're searching is not listed, please click on “Add Player” to begin creating the player profile.</p>
  <p>If a player profile has been claimed already, you may "request access" - a notification will be sent to our admin team to verify the request.</p>
  <div id="reload_button" class="hide input-group" data-g365_action="add_result">
    <a onclick="location.reload()" class="button">Add Another Player</a>
    <a href="/account/player_editor/" class="button">Go to Your Player Data</a>
  </div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Players</span></a>
    <div class="form-init form__wrapper tiny-padding">
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top small-padding-bottom louder  grid-x align-justify">
          <div>
            <span class="change-title emphasis" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm"></span>
            <span class="req">*</span>
          </div>
        <a id="addBtnProfile"class="button g365-button g365_add_button g365-primary-submit no-margin-bottom">+ add player</a>
        </label>
        <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="Enter player name" value="213" data-g365_error_target="pl_ev_names">
        <input type="hidden" id="pl_ev_id"  data-g365_short_name="" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="player_names" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" data-ls_user_ac="{{user_ac}}" data-g365_form_template_new="form_template_min" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="event_id_pm" data-g365_select_click="pl_cert_add_button" placeholder="Full Name" autocomplete="off" autofocus>
      </div>
      <div id="pl_cert_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;">
        <button type="button" id="pl_cert_add_button" class="site-button button form_loader" data-g365_type="pl_cert" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_cert_form" data-g365_load_target="g365_pl_cert_form" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="pl_ev_id|event_id_pm" data-g365_limit="only,pl_ev_id,event_id_pm" data-g365_toggle_parent="init_toggle" data-g365_deps_start="pl_ev_names" data-g365_base_id="self" data-g365_form_load_min="true" tabindex=0>Add Player for Certification</button>
      </div>
    </div>
    <form id="g365_pl_cert_form" class="primary-form" name="g365_pl_cert_form" enctype="multipart/form-data" method="post" data-g365_type="pl_cert" data-target_field="reload_button">
      <div id="g365_pl_cert_form_data"></div>
      <div id="g365_pl_cert_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_pl_cert_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Submit Registration</button>
      </div>
    </form>
  </div>
</div>
EOD;
$stat_registration_init_player_photo = <<<EOD
<div id="g365_pl_cert_form_wrap">
  <div id="reload_button" class="hide input-group" data-g365_action="add_result">
    <a onclick="location.reload()" class="button">Add Another Player</a>
    <a href="/account/player_editor/" class="button">Go to Your Player Data</a>
  </div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Players</span></a>
    <div class="form-init form__wrapper tiny-padding">
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top small-padding-bottom louder  grid-x align-justify">
          <div>
            <span class="change-title emphasis" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm"></span>
            <span class="req">*</span>
          </div>
        </label>
        <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="Enter player name" value="213" data-g365_error_target="pl_ev_names">
        <input type="hidden" id="pl_ev_id"  data-g365_short_name="" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="player_names" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" data-ls_user_ac="{{user_ac}}" data-g365_form_template_new="form_template_min" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="event_id_pm" data-g365_select_click="pl_cert_add_button" placeholder="Full Name" autocomplete="off" autofocus>
      </div>
      <div id="pl_cert_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display:none;">
        <button type="button" id="pl_cert_add_button" class="site-button button form_loader" data-g365_type="pl_cert" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_cert_form" data-g365_load_target="g365_pl_cert_form" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="pl_ev_id|event_id_pm" data-g365_limit="only,pl_ev_id,event_id_pm" data-g365_toggle_parent="init_toggle" data-g365_deps_start="pl_ev_names" data-g365_base_id="self" data-g365_form_load_min="true" tabindex=0>Add Player for Certification</button>
      </div>
    </div>
    <form id="g365_pl_cert_form" class="primary-form" name="g365_pl_cert_form" enctype="multipart/form-data" method="post" data-g365_type="pl_cert" data-target_field="reload_button">
      <div id="g365_pl_cert_form_data"></div>
      <div id="g365_pl_cert_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_pl_cert_form_message" class="small-margin-top form_message hide"></div>
        <button type="submit" class="site-button button secondary expanded g365-primary-submit" value="submit">Submit Registration</button>
      </div>
    </form>
  </div>
</div>
EOD;
$stat_registration_init_mem = <<<EOD
<div id="g365_pl_ev_form_wrap">
  <h1 class="section_title">Add Player</h1>
  <div class="form-holder">
    <form id="g365_pl_ev_form" class="primary-form" name="g365_pl_ev_form" enctype="multipart/form-data" method="post" data-g365_type="pl_ev">
      <div id="g365_pl_ev_form_data" class="g365-form-data-wrapper"></div>
      <div id="g365_pl_ev_form_message" class="small-margin-top form_message hide"></div>
      <hr />
    </form>
    <div class="g365_set_default">
      <select id="event_id_pm" class="select_local hide" data-g365_auto_advance="true" data-g365_additional_lock="g365_pl_ev_form_data" data-g365_deps_start="pl_ev_names" required></select>
    </div>
    <div class="form-init gset tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm" data_g365_change_totals="true"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_ev_id" data-g365_contingent="pl_ev_add_button_wrap" data-g365_error_target="pl_ev_names" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_select_click="pl_ev_add_button" data-g365_type="player_names" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" data-ls_user_ac="{{user_ac}}" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div id="pl_ev_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display: none;">
        <button type="button" id="pl_ev_add_button" class="site-button button secondary expanded form_loader" data-g365_type="pl_ev" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_ev_form" data-g365_load_target="g365_pl_ev_form" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="pl_ev_id|event_id_pm" data-g365_limit="only,pl_ev_id,event_id_pm|dropdown,event_id_pm" data-g365_form_load_min="false">Add Player</button>
      </div>
    </div>
  </div>
  <hr>
</div>
EOD;
//Player Profile in cart Register EBC
$stat_registration_init_camp = <<<EOD
<div id="g365_pl_cp_form_wrap">
  <h1 class="section_title">Add Player to Camp</h1>
  <div class="form-holder">
    <form id="g365_pl_cp_form" class="primary-form" name="g365_pl_cp_form" enctype="multipart/form-data" method="post" data-g365_type="camps">
      <div id="g365_pl_cp_form_data" class="g365-form-data-wrapper"></div>
      <div id="g365_pl_cp_form_message" class="small-margin-top form_message"><p>Add a player to the camp using the form below hello. Once the player is added to the camp, please continue with the checkout below.</p></div>
      <hr />
    </form>
    <div class="g365_set_default">
      <select id="event_id_cp" class="select_local hide" data-g365_auto_advance="true" data-g365_additional_lock="g365_pl_cp_form_data" data-g365_deps_start="pl_cp_names" required></select>
    </div>
    <div class="form-init form__wrapper tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_cp_names" class="tiny-margin-top louder"><span class="change-title emphasis" data-default_value="Choose Player" data-g365_change_targets="#event_id_cp" data_g365_change_totals="true"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_cp_id" data-g365_contingent="pl_cp_add_button_wrap" data-g365_error_target="pl_cp_names" required>
        <input type="text" id="pl_cp_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="pl_ev" data-ls_target="pl_cp_id" data-g365_form_dest="player_add_cp" data-ls_user_ac="{{user_ac}}" data-g365_form_template_new="form_template_min" data-g365_load_target="player_names_cp" data-g365_select_click="pl_cp_add_button" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div id="pl_cp_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin" style="display: none;">
        <button type="button" id="pl_cp_add_button" class="site-button button secondary expanded form_loader" data-g365_type="camps" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_cp_form" data-g365_load_target="g365_pl_cp_form" data-g365_contributors="pl_cp_id|event_id_cp" data-g365_contributors_req="event_id_cp" data-g365_limit="only,pl_cp_id,event_id_cp|dropdown,event_id_cp" data-g365_form_load_min="true">Add Player</button>
      </div>
    </div>
  </div>
  <hr>
</div>
EOD;

//Player Profile in cart Register for digital coaching packeges

//Player Profile in cart Register subscription
$stat_registration_init_passport = <<<EOD
<div id="g365_pl_cp_form_wrap">
  <div class="form-holder">
    <form id="g365_pl_cp_form" class="primary-form" name="g365_pl_cp_form" enctype="multipart/form-data" method="post" data-g365_type="passport">
      <div id="g365_pl_cp_form_data" class="g365-form-data-wrapper"></div>
      <div id="g365_pl_cp_form_message" class="small-margin-top form_message hide"></div>
      <hr />
    </form>
    <div class="g365_set_default">
      <select id="event_id_cp" class="select_local hide" data-g365_auto_advance="true" data-g365_additional_lock="g365_pl_cp_form_data" data-g365_deps_start="pl_cp_names" required></select>
    </div>
    <div class="form-init form__wrapper tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_cp_names" class="tiny-margin-top louder"><span class="change-title emphasis" data-default_value="Choose Player" data-g365_change_targets="#event_id_cp" data_g365_change_totals="true"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_cp_id" data-g365_contingent="pl_cp_add_button_wrap" data-g365_error_target="pl_cp_names" required>
        <input type="text" id="pl_cp_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="player_names" data-ls_target="pl_cp_id" data-g365_form_dest="player_add_cp" data-ls_user_ac="{{user_ac}}" data-g365_form_template_new="form_template_min" data-g365_load_target="player_names_cp" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_cp_type">Subscription Type</label>
        <select id="pl_cp_type" class="expanded">
          <option value="2024" selected>Current Season(2024-2025)</option>
          <option value="2023">2023-24 Season</option>
          <option value="2022">2022-23 Season</option>
          <option value="2021">2021-22 Season</option>
        </select>
      </div>
      <div id="pl_cp_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <button type="button" id="pl_cp_add_button" class="site-button button secondary expanded form_loader" data-g365_type="passport" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_cp_form" data-g365_load_target="g365_pl_cp_form" data-g365_contributors="pl_cp_id|event_id_cp|pl_cp_type" data-g365_contributors_req="event_id_cp|pl_cp_type" data-g365_limit="only,pl_cp_id,event_id_cp|pl_cp_type|dropdown,event_id_cp" data-g365_form_load_min="true">Confirm Player Data To Complete Step 1</button>
      </div>
    </div>
  </div>
  <hr>
</div>
EOD;
// Player profile in cart register monthly subscription
$stat_registration_init_monthyly_passport = <<<EOD
<div id="g365_pl_cp_form_wrap">
  <h1 class="section_title">Add Player</h1>
  <div class="form-holder">
    <form id="g365_pl_cp_form" class="primary-form" name="g365_pl_cp_form" enctype="multipart/form-data" method="post" data-g365_type="passport">
      <div id="g365_pl_cp_form_data" class="g365-form-data-wrapper"></div>
      <div id="g365_pl_cp_form_message" class="small-margin-top form_message hide"></div>
      <hr />
    </form>
    <div class="g365_set_default">
      <select id="event_id_cp" class="select_local hide" data-g365_auto_advance="true" data-g365_additional_lock="g365_pl_cp_form_data" data-g365_deps_start="pl_cp_names" required></select>
    </div>
    <div class="form-init form__wrapper tiny-padding">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_cp_names" class="tiny-margin-top louder"><span class="change-title emphasis" data-default_value="Choose Player" data-g365_change_targets="#event_id_cp" data_g365_change_totals="true"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_cp_id" data-g365_contingent="pl_cp_add_button_wrap" data-g365_error_target="pl_cp_names" required>
        <input type="text" id="pl_cp_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="player_names" data-ls_target="pl_cp_id" data-g365_form_dest="player_add_cp" data-ls_user_ac="{{user_ac}}" data-g365_form_template_new="form_template_min" data-g365_load_target="player_names_cp" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_cp_type">Subscription Type</label>
        <select id="pl_cp_type" class="expanded">
          <option value="current" selected>Current Month</option>
        </select>
      </div>
      <div id="pl_cp_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <button type="button" id="pl_cp_add_button" class="site-button button secondary expanded form_loader" data-g365_type="passport" data-g365_form_template="form_template" data-g365_form_dest="g365_pl_cp_form" data-g365_load_target="g365_pl_cp_form" data-g365_contributors="pl_cp_id|event_id_cp|pl_cp_type" data-g365_contributors_req="event_id_cp|pl_cp_type" data-g365_limit="only,pl_cp_id,event_id_cp|pl_cp_type|dropdown,event_id_cp" data-g365_form_load_min="true">Confirm Player Data</button>
      </div>
    </div>
  </div>
  <hr>
</div>
EOD;
// different option for hidding add button on livesearch hidden input
//        <input type="hidden" id="pl_ev_id"  data-g365_add_button="pl_cert_add_button_wrap" required>
$stat_registration_form_min_pl_ev_admin = <<<EOD
<div id="pl_ev_admin_fieldset" class="gset small-padding">
  <form id="pl_ev_admin" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="player_event_admin" data-target_field="reload_button">
    <h3>{{first_name}} {{last_name}}</h3>
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="{{event_short}}" name="pl_ev_admin[event]" value="{{event}}">
    <input type="hidden" id="event_name" name="pl_ev_admin[event_name]" value="{{event_name}}">
    <input type="hidden" name="pl_ev_admin[id]" id="pl_ev_admin_id" value="{{id}}">
    <input type="hidden" name="pl_ev_admin[proc_type]" value="proc_data">
    <input type="hidden" name="pl_ev_admin[player]" value="{{player}}">
    <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Player Data" data-g365_before="Player Data"><span class="field-title"></span><span class="field-button">Player Data</span></a>
    <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_first_name">Player First Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][first_name]" id="pl_ev_admin_first_name" value="{{first_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_last_name">Player Last Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][last_name]" id="pl_ev_admin_last_name" value="{{last_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_email">Email</label>
        <input type="email" name="pl_ev_admin[data][player][email]" id="pl_ev_admin_email" value="{{email}}" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_birthday">Birthdate</label>
        <div class="input-group">
          <select class="input-group-field" id="pl_ev_admin_birthday_mo" data-g365_select="{{birthday_mo}}">
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          <span class="input-group-label">Month</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_dy" data-g365_select="{{birthday_dy}}">
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
          </select>
          <span class="input-group-label">Day</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_yr" data-g365_select="{{birthday_yr}}">
            {{current_birth_years}}
          </select>
          <span class="input-group-label">Year</span>
        </div>
        <input type="hidden" name="pl_ev_admin[data][player][birthday]" id="pl_ev_admin_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#pl_ev_admin_birthday_mo|#pl_ev_admin_birthday_dy|#pl_ev_admin_birthday_yr" data-g365_change_delimiter="-">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_bcert_img">Birth Certificate (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][bcert_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_jersey_size">Jersey Size</label>
        <select name="pl_ev_admin[data][player][jersey_size]" id="pl_ev_admin_jersey_size" data-g365_select="{{jersey_size}}" class="expanded">
          <option value="">-- Please Select --</option>
          <option value="Y_Md">Youth Medium</option>
          <option value="Y_Lg">Youth Large</option>
          <option value="Y_XL">Youth X-Large</option>
          <option value="A_Sm">Adult Small</option>
          <option value="A_Md">Adult Medium</option>
          <option value="A_Lg">Adult Large</option>
          <option value="A_XL">Adult X-Large</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_grade">Grade</label>
        <div class="input-group">
          <select class="input-group-field grade-graduation" id="pl_ev_admin_grade" data-g365_select="{{grade}}">
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
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][grad_year]" value="{{grad_year}}" id="pl_ev_admin_grade_grad_yr" placeholder="2999">
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_recard_img">Proof of Grade (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][recard_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_height">Height</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_ft]" value="{{height_ft}}" id="pl_ev_admin_height" min="2" max="9" placeholder="5">
          <span class="input-group-label">ft.</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_in]" value="{{height_in}}" id="pl_ev_admin_height_in" min="0" max="11" placeholder="11">
          <span class="input-group-label">in.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weight">Weight</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][weight]" value="{{weight}}" id="pl_ev_admin_weight" min="30" max="500" placeholder="102">
          <span class="input-group-label">lbs.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_address">Address </label>
        <input type="text" name="pl_ev_admin[data][player][address]" id="pl_ev_admin_address" value="{{address}}" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_city">City</label>
        <input type="text" name="pl_ev_admin[data][player][city]" id="pl_ev_admin_city" value="{{city}}" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_state">State</label>
        <select name="pl_ev_admin[data][player][state]" id="pl_ev_admin_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded">
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
        <label for="pl_ev_admin_zip">Zip </label>
        <input type="tel" name="pl_ev_admin[data][player][zip]" id="pl_ev_admin_zip" value="{{zip}}" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_country">Country </label>
        <input type="text" name="pl_ev_admin[data][player][country]" id="pl_ev_admin_country" value="{{country}}" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_phone">Phone </label>
        <input type="tel" name="pl_ev_admin[data][player][phone]" id="pl_ev_admin_phone" value="{{phone}}" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_position">Position</label>
        <input type="hidden" name="pl_ev_admin[data][player][position]" id="pl_ev_admin_position_id" value="{{position_id}}">
        <input type="text" id="pl_ev_admin_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_club_names">Club Team</label>
        <input type="hidden" name="pl_ev_admin[data][player][club_team]" id="pl_ev_admin_club_team" value="{{club_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="pl_ev_admin_club_names" class="g365_livesearch_input expanded" value="{{club_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_club_team" data-g365_type="club_names" data-ls_no_add="true" placeholder="Enter Club Team Name" autocomplete="off">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_instagram">Instagram</label>
        <input type="text" name="pl_ev_admin[data][player][instagram]" class="expanded" id="pl_ev_admin_instagram" value="{{instagram}}" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_twitter">Twitter</label>
        <input type="text" name="pl_ev_admin[data][player][twitter]" class="expanded" id="pl_ev_admin_twitter" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_profile_img">Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][profile_img_data]">
            <input id="pl_ev_admin_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
    </div>
    <div>
      <h2>Event Data</h2>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="pl_ev_admin[enabled]" id="pl_ev_admin_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_eval">Evaluation</label>
        <textarea name="pl_ev_admin[evaluation]" id="pl_ev_admin_eval" class="expanded" placeholder="">{{evaluation}}</textarea>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_strengths">Stengths</label>
        <input type="text" name="pl_ev_admin[strengths]" id="pl_ev_admin_strengths" class="expanded" placeholder="Running, Walking, Jumping" value="{{strengths}}" />
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weaknesses">Weaknesses</label>
        <input type="text" name="pl_ev_admin[weaknesses]" id="pl_ev_admin_weaknesses" class="expanded" placeholder="Running, Walking, Jumping" value="{{weaknesses}}" />
      </div>
      
      
      
     <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_video">Event Video</label>
        <input type="text" name="pl_ev_admin[video]" id="pl_ev_admin_video" class="expanded" placeholder="ONLY YouTube video ID -- find in URL" value="{{video}}" />
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_event_profile_img">Event Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{event_profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[profile_img_data]">
            <input id="pl_ev_admin_event_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      {{stat_}}
      {{trend_}}
    </div>
    <div id="pl_ev_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Player Event Data</button>
  </form>
</div>
EOD;

$hhh_stat_registration_form_min_pl_ev_admin = <<<EOD
<div id="pl_ev_admin_fieldset" class="gset small-padding">
  <form id="pl_ev_admin" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="hhh_player_event_admin" data-target_field="reload_button">
    <h3>{{first_name}} {{last_name}}</h3>
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="{{event_short}}" name="pl_ev_admin[event]" value="{{event}}">
    <input type="hidden" id="event_name" name="pl_ev_admin[event_name]" value="{{event_name}}">
    <input type="hidden" name="pl_ev_admin[id]" id="pl_ev_admin_id" value="{{id}}">
    <input type="hidden" name="pl_ev_admin[proc_type]" value="proc_data">
    <input type="hidden" name="pl_ev_admin[player]" value="{{player}}">
    <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Player Data" data-g365_before="Player Data"><span class="field-title"></span><span class="field-button">Player Data</span></a>
    <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_first_name">Player First Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][first_name]" id="pl_ev_admin_first_name" value="{{first_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_last_name">Player Last Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][last_name]" id="pl_ev_admin_last_name" value="{{last_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_email">Email</label>
        <input type="email" name="pl_ev_admin[data][player][email]" id="pl_ev_admin_email" value="{{email}}" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_birthday">Birthdate</label>
        <div class="input-group">
          <select class="input-group-field" id="pl_ev_admin_birthday_mo" data-g365_select="{{birthday_mo}}">
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          <span class="input-group-label">Month</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_dy" data-g365_select="{{birthday_dy}}">
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
          </select>
          <span class="input-group-label">Day</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_yr" data-g365_select="{{birthday_yr}}">
            {{current_birth_years}}
          </select>
          <span class="input-group-label">Year</span>
        </div>
        <input type="hidden" name="pl_ev_admin[data][player][birthday]" id="pl_ev_admin_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#pl_ev_admin_birthday_mo|#pl_ev_admin_birthday_dy|#pl_ev_admin_birthday_yr" data-g365_change_delimiter="-">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_bcert_img">Birth Certificate (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][bcert_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_jersey_size">Jersey Size</label>
        <select name="pl_ev_admin[data][player][jersey_size]" id="pl_ev_admin_jersey_size" data-g365_select="{{jersey_size}}" class="expanded">
          <option value="">-- Please Select --</option>
          <option value="Y_Md">Youth Medium</option>
          <option value="Y_Lg">Youth Large</option>
          <option value="Y_XL">Youth X-Large</option>
          <option value="A_Sm">Adult Small</option>
          <option value="A_Md">Adult Medium</option>
          <option value="A_Lg">Adult Large</option>
          <option value="A_XL">Adult X-Large</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_grade">Grade</label>
        <div class="input-group">
          <select class="input-group-field grade-graduation" id="pl_ev_admin_grade" data-g365_select="{{grade}}">
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
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][grad_year]" value="{{grad_year}}" id="pl_ev_admin_grade_grad_yr" placeholder="2999">
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_recard_img">Proof of Grade (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][recard_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_height">Height</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_ft]" value="{{height_ft}}" id="pl_ev_admin_height" min="2" max="9" placeholder="5">
          <span class="input-group-label">ft.</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_in]" value="{{height_in}}" id="pl_ev_admin_height_in" min="0" max="11" placeholder="11">
          <span class="input-group-label">in.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weight">Weight</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][weight]" value="{{weight}}" id="pl_ev_admin_weight" min="30" max="500" placeholder="102">
          <span class="input-group-label">lbs.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_address">Address </label>
        <input type="text" name="pl_ev_admin[data][player][address]" id="pl_ev_admin_address" value="{{address}}" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_city">City</label>
        <input type="text" name="pl_ev_admin[data][player][city]" id="pl_ev_admin_city" value="{{city}}" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_state">State</label>
        <select name="pl_ev_admin[data][player][state]" id="pl_ev_admin_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded">
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
        <label for="pl_ev_admin_zip">Zip </label>
        <input type="tel" name="pl_ev_admin[data][player][zip]" id="pl_ev_admin_zip" value="{{zip}}" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_county">Country </label>
        <input type="text" name="pl_ev_admin[data][player][country]" id="pl_ev_admin_country" value="{{country}}" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_phone">Phone </label>
        <input type="tel" name="pl_ev_admin[data][player][phone]" id="pl_ev_admin_phone" value="{{phone}}" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_position">Position</label>
        <input type="hidden" name="pl_ev_admin[data][player][position]" id="pl_ev_admin_position_id" value="{{position_id}}">
        <input type="text" id="pl_ev_admin_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_club_names">Club Team</label>
        <input type="hidden" name="pl_ev_admin[data][player][club_team]" id="pl_ev_admin_club_team" value="{{club_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="pl_ev_admin_club_names" class="g365_livesearch_input expanded" value="{{club_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_club_team" data-g365_type="club_names" data-ls_no_add="true" placeholder="Enter Club Team Name" autocomplete="off">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_instagram">Instagram</label>
        <input type="text" name="pl_ev_admin[data][player][instagram]" class="expanded" id="pl_ev_admin_instagram" value="{{instagram}}" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_twitter">Twitter</label>
        <input type="text" name="pl_ev_admin[data][player][twitter]" class="expanded" id="pl_ev_admin_twitter" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_profile_img">Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][profile_img_data]">
            <input id="pl_ev_admin_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
    </div>
    <div>
      <h2>Event Data</h2>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="pl_ev_admin[enabled]" id="pl_ev_admin_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_eval">Evaluation</label>
        <textarea name="pl_ev_admin[evaluation]" id="pl_ev_admin_eval" class="expanded" placeholder="">{{evaluation}}</textarea>
      </div>
      
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_hhh_front_page">Display in Home Page?</label>
        <select name="pl_ev_admin[hhh_front_page]" id="pl_ev_admin_hhh_front_page" data-g365_select="{{hhh_front_page}}">
          <option value="False">Disabled</option>
          <option value="True">Enabled</option>
        </select>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_strengths">Stengths</label>
        <input type="text" name="pl_ev_admin[strengths]" id="pl_ev_admin_strengths" class="expanded" placeholder="Running, Walking, Jumping" value="{{strengths}}" />
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weaknesses">Weaknesses</label>
        <input type="text" name="pl_ev_admin[weaknesses]" id="pl_ev_admin_weaknesses" class="expanded" placeholder="Running, Walking, Jumping" value="{{weaknesses}}" />
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_level_division">Level</label>
        <select name="pl_ev_admin[level_division]" id="pl_ev_admin_level_division" data-g365_select="{{level_division}}" class="expanded">
          <option value="">-- Please Select --</option>
          <option value="Division I - Power 5">Division I - Power 5</option>
          <option value="Division I - Mid Major">Division I - Mid Major</option>
          <option value="Division I - Low Major">Division I - Low Major</option>
          <option value="Division II">Division II</option>
          <option value="Division III">Division III</option>
          <option value="NAIA">NAIA</option>
          <option value="JUCO - Junior College">JUCO - Junior College</option>
        </select>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_offers">Offers</label>
        <input type="text" name="pl_ev_admin[offers]" id="pl_ev_admin_offers" class="expanded" placeholder="Running, Walking, Jumping" value="{{offers}}" />
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_player_to_watch">Player to Watch</label>
        <select name="pl_ev_admin[player_to_watch]" id="pl_ev_admin_player_to_watch" data-g365_select="{{player_to_watch}}">
          <option value="True">Enabled</option>
          <option value="False">Disabled</option>
        </select>
      </div>
      
      
     <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_video">Event Video</label>
        <input type="text" name="pl_ev_admin[video]" id="pl_ev_admin_video" class="expanded" placeholder="ONLY YouTube video ID -- find in URL" value="{{video}}" />
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_event_profile_img">Event Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{event_profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[profile_img_data]">
            <input id="pl_ev_admin_event_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      {{stat_}}
      {{trend_}}
    </div>
    <div id="pl_ev_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Player Event Data</button>
  </form>
</div>
EOD;


$ss_stat_registration_form_min_pl_ev_admin = <<<EOD
<div id="pl_ev_admin_fieldset" class="gset small-padding">
  <form id="pl_ev_admin" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="ss_player_event_admin" data-target_field="reload_button">
    <h3>{{first_name}} {{last_name}}</h3>
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="{{event_short}}" name="pl_ev_admin[event]" value="{{event}}">
    <input type="hidden" id="event_name" name="pl_ev_admin[event_name]" value="{{event_name}}">
    <input type="hidden" name="pl_ev_admin[id]" id="pl_ev_admin_id" value="{{id}}">
    <input type="hidden" name="pl_ev_admin[proc_type]" value="proc_data">
    <input type="hidden" name="pl_ev_admin[player]" value="{{player}}">
    <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Player Data" data-g365_before="Player Data"><span class="field-title"></span><span class="field-button">Player Data</span></a>
    <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_first_name">Player First Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][first_name]" id="pl_ev_admin_first_name" value="{{first_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_last_name">Player Last Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][last_name]" id="pl_ev_admin_last_name" value="{{last_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_email">Email</label>
        <input type="email" name="pl_ev_admin[data][player][email]" id="pl_ev_admin_email" value="{{email}}" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_birthday">Birthdate</label>
        <div class="input-group">
          <select class="input-group-field" id="pl_ev_admin_birthday_mo" data-g365_select="{{birthday_mo}}">
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          <span class="input-group-label">Month</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_dy" data-g365_select="{{birthday_dy}}">
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
          </select>
          <span class="input-group-label">Day</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_yr" data-g365_select="{{birthday_yr}}">
            {{current_birth_years}}
          </select>
          <span class="input-group-label">Year</span>
        </div>
        <input type="hidden" name="pl_ev_admin[data][player][birthday]" id="pl_ev_admin_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#pl_ev_admin_birthday_mo|#pl_ev_admin_birthday_dy|#pl_ev_admin_birthday_yr" data-g365_change_delimiter="-">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_bcert_img">Birth Certificate (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][bcert_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_jersey_size">Jersey Size</label>
        <select name="pl_ev_admin[data][player][jersey_size]" id="pl_ev_admin_jersey_size" data-g365_select="{{jersey_size}}" class="expanded">
          <option value="">-- Please Select --</option>
          <option value="Y_Md">Youth Medium</option>
          <option value="Y_Lg">Youth Large</option>
          <option value="Y_XL">Youth X-Large</option>
          <option value="A_Sm">Adult Small</option>
          <option value="A_Md">Adult Medium</option>
          <option value="A_Lg">Adult Large</option>
          <option value="A_XL">Adult X-Large</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_grade">Grade</label>
        <div class="input-group">
          <select class="input-group-field grade-graduation" id="pl_ev_admin_grade" data-g365_select="{{grade}}">
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
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][grad_year]" value="{{grad_year}}" id="pl_ev_admin_grade_grad_yr" placeholder="2999">
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_recard_img">Proof of Grade (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][recard_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_height">Height</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_ft]" value="{{height_ft}}" id="pl_ev_admin_height" min="2" max="9" placeholder="5">
          <span class="input-group-label">ft.</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_in]" value="{{height_in}}" id="pl_ev_admin_height_in" min="0" max="11" placeholder="11">
          <span class="input-group-label">in.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weight">Weight</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][weight]" value="{{weight}}" id="pl_ev_admin_weight" min="30" max="500" placeholder="102">
          <span class="input-group-label">lbs.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_address">Address </label>
        <input type="text" name="pl_ev_admin[data][player][address]" id="pl_ev_admin_address" value="{{address}}" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_city">City</label>
        <input type="text" name="pl_ev_admin[data][player][city]" id="pl_ev_admin_city" value="{{city}}" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_state">State</label>
        <select name="pl_ev_admin[data][player][state]" id="pl_ev_admin_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded">
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
        <label for="pl_ev_admin_zip">Zip </label>
        <input type="tel" name="pl_ev_admin[data][player][zip]" id="pl_ev_admin_zip" value="{{zip}}" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_county">Country </label>
        <input type="text" name="pl_ev_admin[data][player][country]" id="pl_ev_admin_country" value="{{country}}" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_phone">Phone </label>
        <input type="tel" name="pl_ev_admin[data][player][phone]" id="pl_ev_admin_phone" value="{{phone}}" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_position">Position</label>
        <input type="hidden" name="pl_ev_admin[data][player][position]" id="pl_ev_admin_position_id" value="{{position_id}}">
        <input type="text" id="pl_ev_admin_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_club_names">Club Team</label>
        <input type="" name="pl_ev_admin[data][player][club_team]" id="pl_ev_admin_club_team" value="{{club_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="pl_ev_admin_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_club_team" data-g365_type="club_names" data-ls_no_add="true" placeholder="Enter Club Team Name" autocomplete="off">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_instagram">Instagram</label>
        <input type="text" name="pl_ev_admin[data][player][instagram]" class="expanded" id="pl_ev_admin_instagram" value="{{instagram}}" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_twitter">Twitter</label>
        <input type="text" name="pl_ev_admin[data][player][twitter]" class="expanded" id="pl_ev_admin_twitter" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_profile_img">Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][profile_img_data]">
            <input id="pl_ev_admin_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
    </div>
    <div>
      <h2>Event Data</h2>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="pl_ev_admin[enabled]" id="pl_ev_admin_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_eval">Evaluation</label>
        <textarea name="pl_ev_admin[evaluation]" id="pl_ev_admin_eval" class="expanded" placeholder="">{{evaluation}}</textarea>
        <textarea name="pl_ev_admin[ss_evaluation]" id="pl_ev_admin_ss_eval" class="expanded" placeholder="" style="display: none;">{{ss_evaluation}}</textarea>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_front_page">Front Page</label>
        <select name="pl_ev_admin[front_page]" id="pl_ev_admin_front_page" data-g365_select="{{front_page}}">
          <option value="False">Disabled</option>
          <option value="True">Enabled</option>
        </select>
      </div>
      
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_strengths">Stengths</label>
        <input type="text" name="pl_ev_admin[strengths]" id="pl_ev_admin_strengths" class="expanded" placeholder="Running, Walking, Jumping" value="{{strengths}}" />
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weaknesses">Weaknesses</label>
        <input type="text" name="pl_ev_admin[weaknesses]" id="pl_ev_admin_weaknesses" class="expanded" placeholder="Running, Walking, Jumping" value="{{weaknesses}}" />
      </div>
      
      
      
      
     <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_video">Event Video</label>
        <input type="text" name="pl_ev_admin[video]" id="pl_ev_admin_video" class="expanded" placeholder="ONLY YouTube video ID -- find in URL" value="{{video}}" />
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_event_profile_img">Event Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{event_profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[profile_img_data]">
            <input id="pl_ev_admin_event_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      {{stat_}}
      {{trend_}}
    </div>
    <div id="pl_ev_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Player Event Data</button>
  </form>
</div>

<script>
  $(document).ready(function() {
    var ssEvaluation = $('#pl_ev_admin_ss_eval').val();
    var evaluationField = $('#pl_ev_admin_eval');
    var eventName = $('#event_name').val().toLowerCase();

    // Debugging logs
    console.log('ssEvaluation:', ssEvaluation);
    console.log('evaluationField before:', evaluationField.val());
    console.log('eventName:', eventName);

   
      evaluationField.val(ssEvaluation);
    

    // Debugging log
    console.log('evaluationField after:', evaluationField.val());
  });
</script>
EOD;


$stat_registration_form_min_pl_ev_admin__input = <<<EOD
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_{{type}}_{{handle}}">{{name}}</label>
        <input type="text" name="pl_ev_admin[{{type}}][{{handle}}]" id="pl_ev_admin_{{type}}_{{handle}}" class="expanded" placeholder="{{placeholder}}" value="{{value}}" />
      </div>
EOD;
$stat_registration_form_min_pl_ev_admin__select = <<<EOD
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_{{type}}_{{handle}}">{{name}}</label>
        <select name="pl_ev_admin[{{type}}][{{handle}}]" id="pl_ev_admin_{{type}}_{{handle}}" class="expanded" selected="{{value}}">
          {{options}}
        </select>
      </div>
EOD;
$stat_registration_form_min_pl_ev_admin__select_option = <<<EOD
          <option value="{{value}}">{{name}}</option>
EOD;
$stat_registration_form_min_pl_ev_admin__textarea = <<<EOD
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_{{type}}_{{handle}}">{{name}}</label>
        <textarea name="pl_ev_admin[{{type}}][{{handle}}]" id="pl_ev_admin_{{type}}_{{handle}}" class="expanded" placeholder="">{{value}}</textarea>
      </div>
EOD;
$stat_registration_form_min_cps_admin = <<<EOD
<div id="pl_ev_admin_fieldset" class="gset small-padding">
  <form id="pl_ev_admin" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="cps_manager" data-target_field="reload_button">
    <h3>{{first_name}} {{last_name}}</h3>
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="{{event_short}}" name="pl_ev_admin[event]" value="{{event}}">
    <input type="hidden" id="player_name" name="pl_ev_admin[data][player][id]" value="{{player}}">
    <input type="hidden" id="event_name" name="pl_ev_admin[event_name]" value="{{event_name}}">
    <input type="hidden" name="pl_ev_admin[id]" id="pl_ev_admin_id" value="{{id}}">
    <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Player Data" data-g365_before="Player Data"><span class="field-title"></span><span class="field-button">Player Data</span></a>
    <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <input type="hidden" name="pl_ev_admin[proc_type]" value="proc_data">
        <label for="pl_ev_admin_first_name">Player First Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][first_name]" id="pl_ev_admin_first_name" value="{{first_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_last_name">Player Last Name <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][last_name]" id="pl_ev_admin_last_name" value="{{last_name}}" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_email">Email <span class="req">*</span></label>
        <input type="email" name="pl_ev_admin[data][player][email]" id="pl_ev_admin_email" value="{{email}}" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_birthday">Birthdate <span class="req">*</span></label>
        <div class="input-group">
          <select class="input-group-field" id="pl_ev_admin_birthday_mo" data-g365_select="{{birthday_mo}}" required>
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          <span class="input-group-label">Month</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_dy" data-g365_select="{{birthday_dy}}" required>
            <option value="">Please select</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
          </select>
          <span class="input-group-label">Day</span>
          <select class="input-group-field" id="pl_ev_admin_birthday_yr" data-g365_select="{{birthday_yr}}" required>
            {{current_birth_years}}
          </select>
          <span class="input-group-label">Year</span>
        </div>
        <input type="hidden" name="pl_ev_admin[data][player][birthday]" id="pl_ev_admin_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#pl_ev_admin_birthday_mo|#pl_ev_admin_birthday_dy|#pl_ev_admin_birthday_yr" data-g365_change_delimiter="-" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_bcert_img">Birth Certificate (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][bcert_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_jersey_size">Jersey Size <span class="req">*</span></label>
        <select name="pl_ev_admin[data][player][jersey_size]" id="pl_ev_admin_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
          <option value="">-- Please Select --</option>
          <option value="Y_Md">Youth Medium</option>
          <option value="Y_Lg">Youth Large</option>
          <option value="Y_XL">Youth X-Large</option>
          <option value="A_Sm">Adult Small</option>
          <option value="A_Md">Adult Medium</option>
          <option value="A_Lg">Adult Large</option>
          <option value="A_XL">Adult X-Large</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_grade">Grade <span class="req">*</span></label>
        <div class="input-group">
          <select class="input-group-field grade-graduation" id="pl_ev_admin_grade" data-g365_select="{{grade}}" required>
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
          <span class="input-group-label">Graduation Year <span class="req">*</span></span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][grad_year]" value="{{grad_year}}" id="pl_ev_admin_grade_grad_yr" placeholder="2999" required>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_recard_img">Proof of Grade (800px x 800px min)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][recard_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="pl_ev_admin_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_height">Height</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_ft]" value="{{height_ft}}" id="pl_ev_admin_height" min="2" max="9">
          <span class="input-group-label">ft.</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][height_in]" value="{{height_in}}" id="pl_ev_admin_height_in" min="0" max="11">
          <span class="input-group-label">in.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_weight">Weight</label>
        <div class="input-group">
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][weight]" value="{{weight}}" id="pl_ev_admin_weight" min="30" max="500" placeholder="102">
          <span class="input-group-label">lbs.</span>
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_academia">Academics</label>
        <div class="input-group">
          <span class="input-group-label">GPA</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][gpa]" value="{{gpa}}" id="pl_ev_admin_gpa" min="0" max="5" step="0.01" placeholder="3.06">
          <span class="input-group-label">SAT</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][sat]" value="{{sat}}" id="pl_ev_admin_sat" min="100" max="1600" placeholder="1400">
          <span class="input-group-label">ACT</span>
          <input class="input-group-field" type="number" name="pl_ev_admin[data][player][act]" value="{{act}}" id="pl_ev_admin_act" min="0" max="36" placeholder="32">
        </div>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_address">Address </label>
        <input type="text" name="pl_ev_admin[data][player][address]" id="pl_ev_admin_address" value="{{address}}" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_city">City <span class="req">*</span></label>
        <input type="text" name="pl_ev_admin[data][player][city]" id="pl_ev_admin_city" value="{{city}}" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" required>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_state">State <span class="req">*</span></label>
        <select name="pl_ev_admin[data][player][state]" id="pl_ev_admin_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
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
        <label for="pl_ev_admin_zip">Zip </label>
        <input type="tel" name="pl_ev_admin[data][player][zip]" id="pl_ev_admin_zip" value="{{zip}}" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_country">Country </label>
        <input type="text" name="pl_ev_admin[data][player][country]" id="pl_ev_admin_country" value="{{country}}" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_phone">Phone </label>
        <input type="tel" name="pl_ev_admin[data][player][phone]" id="pl_ev_admin_phone" value="{{phone}}" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_club_names">Club Team</label>
        <input type="hidden" name="pl_ev_admin[data][player][club_team]" id="pl_ev_admin_club_team" value="{{club_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="pl_ev_admin_club_names" class="g365_livesearch_input expanded" value="{{club_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_club_team" data-g365_type="club_names" placeholder="Enter Club Team Name" autocomplete="off">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_school_names">School</label>
        <input type="hidden" name="pl_ev_admin[data][player][school]" id="pl_ev_admin_school_team" value="{{school_id}}">
        <input type="text" class="g365_livesearch_input expanded" id="pl_ev_admin_school_names" class="g365_livesearch_input expanded" value="{{school_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_school_team" data-g365_type="school_names" placeholder="Enter School Team Name" autocomplete="off">
      </div>
      <div class="small-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_instagram">Instagram</label>
        <input type="text" name="pl_ev_admin[data][player][instagram]" class="expanded" id="pl_ev_admin_instagram" value="{{instagram}}" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$">
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_profile_img">Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[data][player][profile_img_data]">
            <input id="pl_ev_admin_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
    </div>
    <div>
      <h2>CPS Data</h2>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="pl_ev_admin[enabled]" id="pl_ev_admin_enabled" data-g365_select="{{enabled}}" required>
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_position">Position</label>
        <input type="hidden" name="pl_ev_admin[data][player][position]" id="pl_ev_admin_position_id" value="{{position_id}}">
        <input type="text" id="pl_ev_admin_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="pl_ev_admin_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_admin_event_profile_img">Event Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{event_profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="pl_ev_admin[profile_img_data]">
            <input id="pl_ev_admin_event_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      {{stat_}}
      {{trend_}}
    </div>
    <div id="pl_ev_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update CPS Profile</button>
  </form>
</div>
EOD;
$stat_registration_form_min_pl_cert_sl = <<<EOD
<div id="reload_button" class="hide" data-g365_action="add_result">
  <a onclick="location.reload()" class="button">Add Another Player</a>
  <a href="/account/player_editor/" class="button">Go to Your Player Data</a>
</div>
<div id="pl_cert_gen_fieldset" class="gset small-padding tiny-margin-top">
  <form id="pl_cert_gen" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="pl_cert_sl" data-target_field="reload_button">
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{{divisions}}" data-g365_short_name="{{short_name}}" name="pl_cert_gen[event]" value="{{event}}">
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="pl_cert_gen[proc_type]" value="proc_data">
      <label for="pl_cert_gen_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="pl_cert_gen[data][player][first_name]" id="pl_cert_gen_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="pl_cert_gen[data][player][last_name]" id="pl_cert_gen_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_email">Email <span class="req">*</span></label>
      <input type="email" name="pl_cert_gen[data][player][email]" id="pl_cert_gen_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_birthday">Birthdate <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field" id="pl_cert_gen_birthday_mo" data-g365_select="{{birthday_mo}}" required>
          <option value="">Please select</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
        </select>
        <span class="input-group-label">Month</span>
        <select class="input-group-field" id="pl_cert_gen_birthday_dy" data-g365_select="{{birthday_dy}}" required>
          <option value="">Please select</option>
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
          <option value="04">04</option>
          <option value="05">05</option>
          <option value="06">06</option>
          <option value="07">07</option>
          <option value="08">08</option>
          <option value="09">09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        <span class="input-group-label">Day</span>
        <select class="input-group-field" id="pl_cert_gen_birthday_yr" data-g365_select="{{birthday_yr}}" required>
          {{current_birth_years}}
        </select>
        <span class="input-group-label">Year</span>
      </div>
      <input type="hidden" name="pl_cert_gen[data][player][birthday]" id="pl_cert_gen_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#pl_cert_gen_birthday_mo|#pl_cert_gen_birthday_dy|#pl_cert_gen_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="pl_cert_gen[data][player][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="pl_cert_gen_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="pl_cert_gen[data][player][jersey_size]" id="pl_cert_gen_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
        <option value="">-- Please Select --</option>
        <option value="Y_Md">Youth Medium</option>
        <option value="Y_Lg">Youth Large</option>
        <option value="Y_XL">Youth X-Large</option>
        <option value="A_Sm">Adult Small</option>
        <option value="A_Md">Adult Medium</option>
        <option value="A_Lg">Adult Large</option>
        <option value="A_XL">Adult X-Large</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_grade">Grade <span class="req">*</span></label>
      <select class="input-group-field grade-graduation" id="pl_cert_gen_grade" data-g365_select="{{grade}}" required>
        <option value="">Please select</option>
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
      <input class="input-group-field" type="hidden" name="pl_cert_gen[data][player][grad_year]" id="pl_cert_gen_grade_grad_yr" value="{{grad_year}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_recard_img">Proof of Grade (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="pl_cert_gen[data][player][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="pl_cert_gen_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="pl_cert_gen[data][player][height_ft]" id="pl_cert_gen_height" min="2" max="9" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="pl_cert_gen[data][player][height_in]" id="pl_cert_gen_height_in" min="0" max="11" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="pl_cert_gen[data][player][weight]" id="pl_cert_gen_weight" min="30" max="500">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_address">Address </label>
      <input type="text" name="pl_cert_gen[data][player][address]" id="pl_cert_gen_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_city">City <span class="req">*</span></label>
      <input type="text" name="pl_cert_gen[data][player][city]" id="pl_cert_gen_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_state">State <span class="req">*</span></label>
      <select name="pl_cert_gen[data][player][state]" id="pl_cert_gen_state" class="maps-autocomplete-state expanded" required>
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
      <label for="pl_cert_gen_zip">Zip <span class="req">*</span></label>
      <input type="tel" name="pl_cert_gen[data][player][zip]" id="pl_cert_gen_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_country">Country </label>
      <input type="text" name="pl_cert_gen[data][player][country]" id="pl_cert_gen_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_phone">Phone </label>
      <input type="tel" name="pl_cert_gen[data][player][phone]" id="pl_cert_gen_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_position">Position</label>
      <input type="hidden" name="pl_cert_gen[data][player][position]" id="pl_cert_gen_position_id">
      <input type="text" id="pl_cert_gen_position" class="g365_livesearch_input expanded" data-g365_action="select_data" data-ls_target="pl_cert_gen_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_club_names">Club Team</label>
      <input type="hidden" name="pl_cert_gen[data][player][club_team]" id="pl_cert_gen_club_team">
      <input type="text" class="g365_livesearch_input expanded" id="pl_cert_gen_club_names" class="g365_livesearch_input expanded" data-g365_action="select_data" data-ls_target="pl_cert_gen_club_team" data-g365_type="club_names" data-ls_no_add="true" placeholder="Enter Club Team Name" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="pl_cert_gen_instagram">Instagram</label>
      <input type="text" name="pl_cert_gen[data][player][instagram]" class="expanded" id="pl_cert_gen_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$">
    </div>
    <div id="pl_cert_gen_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Player Data</button>
  </form>
</div>
EOD;

//Team evaluations
$team_stat_registration_init_admin = <<<EOD
<div id="g365_team_event_form_wrap">
  <h1 class="section_title">Manage Team Stats</h1>
  <div class="form-holder">
    <div class="form-init gset tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="event_names_event_ss">Select Scope Scouting Event</label>
        <input type="hidden" id="event_id_pm_event_ss" data-g365_error_target="event_names_event_ss">
        <input type="text" id="event_names_event_ss" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm_event_ss" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus>
      </div>
      
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="event_names">Participating Event</label>
        <input type="hidden" id="event_id_pm" data-g365_error_target="event_names">
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="club_names">Club Organization <span class="req">*</span></label>
        <input type="hidden" id="club_id" data-g365_contingent="club_search_block_contingent" data-g365_ls_lock="org" required>
        <input type="text" id="club_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_full" data-g365_type="club_names_admin" data-g365_deps_start="team_names" data-ls_target="club_id" data-g365_form_dest="club_team_add" data-g365_load_target="team_names" data-g365_field_toggle="true" placeholder="Enter Club Name" autocomplete="off">
      </div>
      <div id="club_search_block_contingent" class="form-disabled form__border tiny-padding small-margin-top">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="team_names" class="tiny-margin-top louder"><span class="change-title emphasis green title-disable" data-default_value="Choose Team" data-g365_change_targets="#event_id_pm" data_g365_change_totals="true"></span> <span class="req">*</span></label>
          <input type="hidden" id="team_selector" data-g365_contingent="team_search_contingent" data-g365_additional_target_limit="level" data-g365_link_target="{{level_link}}" required>
          <input type="text" id="team_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_reset_target="level" data-g365_add_button="roster_add_button_wrap" data-g365_form_template="form_template_min" data-g365_type="team_names" data-ls_target="team_selector" data-g365_form_dest="team_add" data-ls_query_lock="club_id" data-g365_contributors="club_id" data-g365_contributors_req="club_id" placeholder="Enter Team Name: 14U Blue, Varsity Elite Black, etc..." autocomplete="off">
        </div>
      </div>
      <div id="player_event_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <a id="team_event_add_button" class="site-button button form_loader" data-g365_type="team_event" data-g365_form_template="form_template" data-g365_form_dest="g365_team_event_form" data-g365_load_target="g365_team_event_form" data-g365_contributors="club_id|event_id_pm|team_selector|event_id_pm_event_ss" data-g365_contributors_req="club_id|event_id_pm|team_selector" data-g365_limit="only,club_id,event_id_pm,team_selector,event_id_pm_event_ss" tabindex=0>Add Team Event</a>
      </div>
   </div>
    <form id="g365_team_event_form" class="primary-form" name="g365_team_event_form" enctype="multipart/form-data" method="post" data-g365_type="team_event">
      <div id="g365_team_event_form_data"></div>
      <div id="g365_team_event_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_team_event_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Team Data</button>
      </div>
    </form>
  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
EOD;

//Team evaluation edit
$team_stat_registration_init = <<<EOD
<div id="g365_player_event_form_wrap">
  <h1 class="section_title">Player Event Data</h1>
  <div id="reload_button" class="hide button" onclick="location.reload()" data-g365_action="add_result">Add More Players</div>
  <div class="form-holder">
    <a id="init_toggle" class="field-toggle button tiny-margin-bottom hide" data-g365_class_toggle="hide"><span class="field-title"></span><span class="field-button">Add More Players</span></a>
    <div class="form-init gset tiny-padding">
      <div id="event-selector" class="tiny-margin-bottom tiny-padding no-input-margin{{init_hide}}">
        <label for="event_names">Event</label>
        <input type="hidden" id="event_id_pm" data-g365_short_name="{{name}}" data-g365_additional_data="{{divisions}}" value="{{event}}" data-g365_error_target="event_names" required>
        <input type="text" id="event_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_type="event_names_div" data-ls_target="event_id_pm" data-ls_no_add="true" placeholder="Enter Event Name" autocomplete="off" autofocus value="{{name}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_ev_names" class="tiny-margin-top louder"><span class="change-title emphasis green" data-default_value="Choose Player" data-g365_change_targets="#event_id_pm"></span> <span class="req">*</span></label>
        <input type="hidden" id="pl_ev_id" data-g365_contingent="player_event_add_button_wrap" value="{{player}}" data-g365_error_target="pl_ev_names" required>
        <input type="text" id="pl_ev_names" class="g365_livesearch_input expanded block" data-g365_action="select_data"  data-g365_type="pl_ev" data-ls_target="pl_ev_id" data-g365_form_dest="pl_ev_add" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="event_id_pm" placeholder="Enter Player Name" autocomplete="off" autofocus value="{{player_name}}">
      </div>
      <div id="player_event_add_button_wrap" class="tiny-margin-bottom tiny-padding no-input-margin">
        <a id="player_event_add_button" class="site-button button form_loader" data-g365_type="player_event" data-g365_form_template="form_template" data-g365_form_dest="g365_player_event_form" data-g365_load_target="g365_player_event_form" data-g365_contributors="pl_ev_id|event_id_pm" data-g365_contributors_req="pl_ev_id|event_id_pm" data-g365_limit="only,pl_ev_id,event_id_pm" data-g365_toggle_parent="init_toggle" data-g365_deps_start="pl_ev_names" tabindex=0>Next</a>
      </div>
    </div>
    <form id="g365_player_event_form" class="primary-form" name="g365_player_event_form" enctype="multipart/form-data" method="post" data-g365_type="player_event" data-target_field="reload_button">
      <div id="g365_player_event_form_data"></div>
      <div id="g365_player_event_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_player_event_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;

$team_stat_registration_form = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title tiny-padding green-border input-group">
    <div class="input-group-field">
      <small class="block change-title" data-g365_change_targets="#{{field-set-id}}_event_id_pm_event_ss">{{event_short}}</small>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_club_id">{{name}}</div>
      <div class="change-title" data-g365_change_targets="#{{field-set-id}}_team_selector">{{name}}</div>
    </div>
    <div class="input-group-button">
      <a class="button site-close-button"><span>remove</span></a>
    </div>
    <div class="">
    </div>
  </div>
  <div class="g365_set_default">
    <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data_team_evals">
    <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    <input type="hidden" name="{{field-set-id}}[team]" id="{{field-set-id}}_team_selector" value="{{team}}" data-g365_data_key="team_selector" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event_ss_part]" id="{{field-set-id}}_event_id_pm_event_ss" value="{{event_ss_part}}" data-g365_data_key="event_id_pm_event_ss" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[event]" id="{{field-set-id}}_event_id_pm" value="{{event}}" data-g365_data_key="event_id_pm" data-g365_immutable="true" data-g365_short_name="{{event_short}}" data-placer="">
    <input type="hidden" name="{{field-set-id}}[club]" id="{{field-set-id}}_club_id" value="{{club}}" data-g365_data_key="club_id" data-g365_immutable="true" data-g365_short_name="{{name}}" data-placer="">
  </div>
</div>
EOD;

$ss_stat_registration_form_min_tm_ev_admin_team = <<<EOD
<div id="tm_ev_admin_fieldset" class="gset small-padding">
  <form id="tm_ev_admin" class="primary-form" name="g365_team_event_form" enctype="multipart/form-data" method="post" data-g365_type="ss_team_event_admin" data-target_field="reload_button">
    <h3>{{event_name}}</h3>
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="{{event_short}}" name="tm_ev_admin[event]" value="{{event}}">
    <input type="hidden" id="event_name" name="tm_ev_admin[event_name]" value="{{event_name}}">
    <input type="hidden" name="tm_ev_admin[id]" id="tm_ev_admin_id" value="{{id}}">
    <input type="hidden" name="tm_ev_admin[proc_type]" value="proc_data">
    <input type="hidden" name="tm_ev_admin[team]" value="{{team}}">
    <div>
      <h2>Event Data</h2>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="tm_ev_admin[enabled]" id="pl_ev_admin_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_eval">Evaluation</label>
        <textarea name="tm_ev_admin[evaluation]" id="tm_ev_admin_eval" class="expanded" placeholder="">{{evaluation}}</textarea>
      </div>
      
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_front_page">Front Page</label>
        <select name="tm_ev_admin[front_page]" id="tm_ev_admin_front_page" data-g365_select="{{front_page}}">
          <option value="False">Disabled</option>
          <option value="True">Enabled</option>
        </select>
      </div>
      
      
     <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_video">Event Video</label>
        <input type="text" name="tm_ev_admin[video]" id="tm_ev_admin_video" class="expanded" placeholder="ONLY YouTube video ID -- find in URL" value="{{video}}" />
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_event_profile_img">Event Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{event_profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="tm_ev_admin[profile_img_data]">
            <input id="tm_ev_admin_event_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      {{stat_}}
      {{trend_}}
    </div>
    <div id="tm_ev_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Team Event Data</button>
  </form>
</div>
EOD;

$stat_registration_form_min_tm_ev_admin_team = <<<EOD
<div id="tm_ev_admin_fieldset" class="gset small-padding">
  <form id="tm_ev_admin" class="primary-form" name="g365_team_event_form" enctype="multipart/form-data" method="post" data-g365_type="team_event_admin" data-target_field="reload_button">
    <h3>{{event_name}}</h3>
    <input type="hidden" id="event_id_pm" data-g365_additional_data="{'1': '0'}" data-g365_short_name="{{event_short}}" name="tm_ev_admin[event]" value="{{event}}">
    <input type="hidden" id="event_name" name="tm_ev_admin[event_name]" value="{{event_name}}">
    <input type="hidden" name="tm_ev_admin[id]" id="tm_ev_admin_id" value="{{id}}">
    <input type="hidden" name="tm_ev_admin[proc_type]" value="proc_data">
    <input type="hidden" name="tm_ev_admin[team]" value="{{team}}">
    <div>
      <h2>Event Data</h2>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="tm_ev_admin[enabled]" id="tm_ev_admin_enabled" data-g365_select="{{enabled}}">
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_eval">Evaluation</label>
        <textarea name="tm_ev_admin[evaluation]" id="tm_ev_admin_eval" class="expanded" placeholder="">{{evaluation}}</textarea>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_strengths">Stengths</label>
        <input type="text" name="tm_ev_admin[strengths]" id="tm_ev_admin_strengths" class="expanded" placeholder="Running, Walking, Jumping" value="{{strengths}}" />
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_weaknesses">Weaknesses</label>
        <input type="text" name="tm_ev_admin[weaknesses]" id="tm_ev_admin_weaknesses" class="expanded" placeholder="Running, Walking, Jumping" value="{{weaknesses}}" />
      </div>
      
      
      
     <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_video">Event Video</label>
        <input type="text" name="tm_ev_admin[video]" id="tm_ev_admin_video" class="expanded" placeholder="ONLY YouTube video ID -- find in URL" value="{{video}}" />
      </div>
      <div class="large-margin-bottom tiny-padding no-input-margin">
        <label for="tm_ev_admin_event_profile_img">Event Profile Image (400px x 600px)</label>
        <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{event_profile_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="tm_ev_admin[profile_img_data]">
            <input id="tm_ev_admin_event_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      {{stat_}}
      {{trend_}}
    </div>
    <div id="tm_ev_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Team Event Data</button>
  </form>
</div>
EOD;

?>