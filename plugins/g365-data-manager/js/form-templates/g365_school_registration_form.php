<?php
$school_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$school_registration_form_min = <<<EOD
<div id="{{field-set-id}}" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}_fieldset" class="primary-form" name="g365_school_form" enctype="multipart/form-data" method="post" data-g365_type="school_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3 class="change-title" data-default_value="School" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</h3>
    <div class="small-margin-bottom">
      <input type="hidden" name="{{field-set-id}}[data][type]" value="2">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <label for="{{field-set-id}}_name">School Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="(max 60 characters)" maxlength="60" value="{{name}}" required>
    </div>
    <div class="small-margin-bottom">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="full-width" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="small-margin-bottom">
      <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="full-width" required>
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
      <label for="{{field-set-id}}_county">County </label>
      <input type="text" name="{{field-set-id}}[data][county]" id="{{field-set-id}}_county" class="full-width" maxlength="100" placeholder="Any County (max. 100 characters)" value="{{county}}">
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New School Data</button>
  </form>
</div>
EOD;
$school_registration_form = <<<EOD
    <div id="{{field-set-id}}" class="g365_form">
      <hr class="g365-divider" />
      <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
        <span class="input-group-field"></span>
        <a class="input-group-button button hide-on-disable">edit</a>
        <div class="input-group-button">
          <a class="button site-close-button"><span>remove</span></a>
        </div>
      </div>
      <div id="{{field-set-id}}_fieldset" class="gset small-padding">
        <div><a class="site-close-button remove-button site-button button">remove</a></div>
        <h3 class="change-title" data-default_value="School" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</h3>
        <div class="small-margin-bottom">
          <input type="hidden" name="{{field-set-id}}[data][type]" value="2">
          <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
          <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
          <label for="{{field-set-id}}_name">School Name <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{name}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_abbreviation">Short School Name</label>
          <input type="text" name="{{field-set-id}}[data][abbreviation]" id="{{field-set-id}}_abbreviation" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{abbreviation}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_director_first">Director First Name</label>
          <input type="text" name="{{field-set-id}}[data][director_first]" id="{{field-set-id}}_director_first" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_name}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_director_last">Director Last Name</label>
          <input type="text" name="{{field-set-id}}[data][director_last]" id="{{field-set-id}}_director_last" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_last}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_director_email">Director Email</label>
          <input type="email" name="{{field-set-id}}[data][director_email]" id="{{field-set-id}}_director_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{director_email}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_director_phone">Director Phone </label>
          <input type="tel" name="{{field-set-id}}[data][director_phone]" id="{{field-set-id}}_director_phone" class="full-width g365-input-formatter" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" placeholder="112-223-3334" value="{{director_phone}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_email">General Email <span class="req">*</span></label>
          <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="info@acme.com" value="{{email}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_address">General Address <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete full-width" maxlength="100" placeholder="Street Address*" value="{{address}}" required>
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
          <label for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
          <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip full-width" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_country">Country <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country full-width" maxlength="100" placeholder="Country (max. 100 characters)" value="{{country}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_phone">General Phone <span class="req">*</span></label>
          <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone full-width" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="800-223-3334" value="{{phone}}" required>
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_county">County </label>
          <input type="text" name="{{field-set-id}}[data][county]" id="{{field-set-id}}_county" class="maps-autocomplete-county full-width" maxlength="100" placeholder="Anycounty (max. 100 characters)" value="{{county}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_link">Site URL </label>
          <input type="text" name="{{field-set-id}}[data][link]" id="{{field-set-id}}_link" class="full-width" maxlength="150" placeholder="https://funtimes.com" pattern="^(https?:\/\/)?[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}\/?$" value="{{link}}">
        </div>
        <div class="small-margin-bottom">
          <label for="{{field-set-id}}_instagram">Instagram</label>
          <input type="text" name="{{field-set-id}}[data][instagram]" class="full-width" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
        </div>
        <div class="large-margin-bottom">
          <label for="{{field-set-id}}_profile_img">Crest or Logo Image (300px x 400px)</label>
          <div class="crop_img" data-g365_profile_img_url="{{profile_img_url}}">
            <div class="cropped_img">
              <img src="{{profile_img_url}}" />
            </div>
            <div class="crop_upload">
              <div class="crop_upload_canvas_wrap hide">
                <div class="crop_upload_canvas"></div>
              </div>
              <input type="hidden" class="profile_img" data-g365_name="{{field-set-id}}[data][profile_img]">
              <input type="hidden" class="profile_img_data" data-g365_name="{{field-set-id}}[data][profile_img_data]">
              <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
            </div>
            <a class="site-button g365-small-button remove-profile in-block small-margin-top hide">Remove Image</a>
          </div>
        </div>
        <div>
          <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Done with School</a>
        </div>
      </div>
    </div>
EOD;
$school_registration_init = <<<EOD
<div id="g365_school_form_wrap">
  <h1 class="section_title">School Form<br><small>Find or register schools</small></h1>
  <p>Please fillout this form to make your school available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="sc_name">
      <label for="player_names">School Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input full-width block" data-g365_action="load_form" data-g365_type="school_names" data-g365_form_dest="g365_school_form" placeholder="Enter School Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_school_form" class="primary-form" name="g365_school_form" enctype="multipart/form-data" method="post" data-g365_type="school_names">
      <div id="g365_school_form_data"></div>
      <div id="g365_school_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_school_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New School Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
?>