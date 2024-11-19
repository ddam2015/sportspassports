<?php
$club_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$club_registration_form_min = <<<EOD
<div id="{{field-set-id}}" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}_fieldset" class="primary-form" name="g365_club_form" enctype="multipart/form-data" method="post" data-g365_type="club_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3 class="change-title" data-default_value="Club Team" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[data][type]" value="1">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <label for="{{field-set-id}}_name">Club Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="(max 60 characters)" maxlength="60" value="{{name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="full-width" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_county">Country </label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="full-width" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Logo Image (min. 400px x 300px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="org_profile" data-g365_croppie_img_url="{{profile_img_url}}">
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
    <button class="site-button button g365-primary-submit" type="submit" value="submit">Program</button>
  </form>
</div>
EOD;
//Add Club from main nav
$club_registration_form = <<<EOD
    <div id="{{field-set-id}}" class="g365_form">
      <hr class="g365-divider" />
      <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding primary-border input-group" data-click-target="{{field-set-id}}">
        <span class="input-group-field"></span>
        <a class="input-group-button button hide-on-disable">edit</a>
      </div>
      <div id="{{field-set-id}}_fieldset" class="form__border small-padding">
        <div><a class="site-close-button remove-button site-button button">remove</a></div>
        <h3 class="g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}">
          <span class="change-title" data-default_value="Club Team" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</span>
          <span class="change-title medium-gray parenthesis-wrapper" data-g365_change_targets="#{{field-set-id}}_abbreviation">{{abbreviation}}</span>
        </h3>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <input type="hidden" name="{{field-set-id}}[data][type]" value="1">
          <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
          <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
          <label for="{{field-set-id}}_name">Club Name <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="(max 60 characters)" maxlength="60" value="{{name}}" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_abbreviation">Club Nickname</label>
          <input type="text" name="{{field-set-id}}[data][abbreviation]" id="{{field-set-id}}_abbreviation" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{abbreviation}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_first">Director First Name</label>
          <input type="text" name="{{field-set-id}}[data][director_first]" id="{{field-set-id}}_director_first" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_first}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_last">Director Last Name</label>
          <input type="text" name="{{field-set-id}}[data][director_last]" id="{{field-set-id}}_director_last" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_last}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_email">Director Email</label>
          <input type="email" name="{{field-set-id}}[data][director_email]" id="{{field-set-id}}_director_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{director_email}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_phone">Director Phone </label>
          <input type="tel" name="{{field-set-id}}[data][director_phone]" id="{{field-set-id}}_director_phone" class="full-width g365-input-formatter" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" data-g365_input_format="tel" placeholder="112-223-3334" value="{{director_phone}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_email">Program Email</label>
          <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="info@acme.com" value="{{email}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_phone">Program Phone</label>
          <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone full-width g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" data-g365_input_format="tel" placeholder="800-223-3334" value="{{phone}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_address">Program Address</label>
          <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete full-width" maxlength="100"  placeholder="Street Address*" value="{{address}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city full-width" maxlength="100" placeholder="City*" value="{{city}}" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
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
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
          <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip full-width" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_county">Country <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country full-width" maxlength="100" placeholder="Country" value="{{country}}" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_phone">Program Phone</label>
          <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone full-width g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" placeholder="800-223-3334" value="{{phone}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_county">County </label>
          <input type="text" name="{{field-set-id}}[data][county]" id="{{field-set-id}}_county" class="full-width" maxlength="100" placeholder="Anycounty" value="{{county}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_link">Site URL </label>
          <input type="text" name="{{field-set-id}}[data][link]" id="{{field-set-id}}_link" class="full-width" maxlength="150" placeholder="https://funtimes.com" pattern="^(https?:\/\/)?[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}\/?$" value="{{link}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_instagram">Instagram</label>
          <input type="text" name="{{field-set-id}}[data][instagram]" class="full-width" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_profile_img">Logo Image (min. 400px x 300px)</label>
          <div class="crop_img medium-padding" data-g365_crop_settings="org_profile" data-g365_croppie_img_url="{{profile_img_url}}">
            <div class="cropped_img"></div>
            <div class="crop_upload">
              <div class="crop_upload_canvas_wrap hide">
                <div class="crop_upload_canvas"></div>
              </div>
              <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][profile_img_data]">
              <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
            </div>
            <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie button__close-crop no-margin-bottom small-margin-top hide">Remove Image</a>
          </div>
        </div>
        <div>
          <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Done with Clubs</a>
        </div>
      </div>
    </div>
EOD;
// in director account
$club_registration_form_sl = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable">edit</a>
  </div>
  <div id="{{field-set-id}}_fieldset" class="small-padding">
    <h3 class="g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}"><span class="change-title" data-default_value="Club Team" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</span><span class="change-title secondary-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_abbreviation">{{abbreviation}}</span></h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[data][type]" value="1">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_name">Full Club Program Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="Full Club Program Name* (max 60 characters)" maxlength="60" value="{{name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_abbreviation">Club Abbreviation</label>
      <input type="text" name="{{field-set-id}}[data][abbreviation]" id="{{field-set-id}}_abbreviation" class="full-width" placeholder="Club Abbreviation ex) OGP (max 30 characters)" maxlength="30" value="{{abbreviation}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_director_first">Director First Name<span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][director_first]" id="{{field-set-id}}_director_first" class="full-width" placeholder="Director First Name11* (max 30 characters)" maxlength="30" value="{{director_first}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_director_last">Director Last Name<span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][director_last]" id="{{field-set-id}}_director_last" class="full-width" placeholder="Director Last Name* (max 30 characters)" maxlength="30" value="{{director_last}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_director_email">Director Email<span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][director_email]" id="{{field-set-id}}_director_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="Email* ex) john@acme.com" value="{{director_email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_director_phone">Director Phone<span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][director_phone]" id="{{field-set-id}}_director_phone" class="full-width g365-input-formatter" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" data-g365_input_format="tel" placeholder="Phone* ex)112-223-3334" value="{{director_phone}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label class="hide" for="{{field-set-id}}_email">Program Email</label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="info@acme.com" value="{{email}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label class="hide" for="{{field-set-id}}_address">Program Address</label>
      <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete full-width" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_city">Program City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city full-width" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_state">Program State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state full-width" required style="border-radius: 50px;">
        <option value="">-- Select State*</option>
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
      <label class="hide" for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip full-width" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_county">Country <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country full-width" maxlength="100" placeholder="Country*" value="{{country}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label class="hide" for="{{field-set-id}}_phone">Program Phone</label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone full-width g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" placeholder="800-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label class="hide" for="{{field-set-id}}_county">County </label>
      <input type="text" name="{{field-set-id}}[data][county]" id="{{field-set-id}}_county" class="full-width" maxlength="100" placeholder="Anycounty" value="{{county}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_link">Website</label>
      <input type="text" name="{{field-set-id}}[data][link]" id="{{field-set-id}}_link" class="full-width" maxlength="150" placeholder="https://funtimes.com" pattern="^(https?:\/\/)?[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}\/?$" value="{{link}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="full-width" id="{{field-set-id}}_instagram" placeholder="instagram username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_facebook">FB</label>
      <input type="text" name="{{field-set-id}}[data][facebook]" class="full-width" id="{{field-set-id}}_facebook" placeholder="facebook username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-facebook}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="hide" for="{{field-set-id}}_twitter">X</label>
      <input type="text" name="{{field-set-id}}[data][twitter]" class="full-width" id="{{field-set-id}}_twitter" placeholder="x username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label class="" for="{{field-set-id}}_profile_img">Logo Image (min. 400px x 300px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="org_profile" data-g365_croppie_img_url="{{profile_img_url}}">
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
    <div>
      <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$club_registration_form_admin = <<<EOD
    <div id="{{field-set-id}}" class="g365_form">
      <hr class="g365-divider" />
      <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
        <span class="input-group-field"></span>
        <a class="input-group-button button hide-on-disable">edit</a>
      </div>
      <div id="{{field-set-id}}_fieldset" class="gset small-padding">
        <div><a class="site-close-button remove-button site-button button">remove</a></div>
        <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Club Team" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</h3>
        <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Admin Options" data-g365_before="Admin Options"><span class="field-title"></span><span class="field-button">Admin Options</span></a>
        
        <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_enabled">Enabled <span class="req">*</span></label>
            <select name="{{field-set-id}}[data][enabled]" id="{{field-set-id}}_enabled" data-g365_select="{{enabled}}" required>
              <option value="1">Enabled</option>
              <option value="0">Disabled</option>
            </select>
          </div>
          
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_nickname">URL (only alphanumeric & hyphens)<span class="req">*</span></label>
            <input type="text" name="{{field-set-id}}[data][nickname]" id="{{field-set-id}}_nickname" class="expanded" placeholder="(max 60 characters)" maxlength="60" value="{{nickname}}">
          </div>
        </div>
        
        
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <input type="hidden" name="{{field-set-id}}[data][type]" value="1">
          <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
          <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
          <label for="{{field-set-id}}_name">Club Name <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="(max 60 characters)" maxlength="60" value="{{name}}" required>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_abbreviation">Club Nickname</label>
          <input type="text" name="{{field-set-id}}[data][abbreviation]" id="{{field-set-id}}_abbreviation" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{abbreviation}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_first">Director First Name</label>
          <input type="text" name="{{field-set-id}}[data][director_first]" id="{{field-set-id}}_director_first" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_first}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_last">Director Last Name</label>
          <input type="text" name="{{field-set-id}}[data][director_last]" id="{{field-set-id}}_director_last" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_last}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_email">Director Email</label>
          <input type="email" name="{{field-set-id}}[data][director_email]" id="{{field-set-id}}_director_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{director_email}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_director_phone">Director Phone </label>
          <input type="tel" name="{{field-set-id}}[data][director_phone]" id="{{field-set-id}}_director_phone" class="full-width g365-input-formatter" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" data-g365_input_format="tel" placeholder="112-223-3334" value="{{director_phone}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_email">Program Email</label>
          <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="info@acme.com" value="{{email}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_address">Program Address</label>
          <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete full-width" maxlength="100" placeholder="Street Address*" value="{{address}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city full-width" maxlength="100" placeholder="City*" value="{{city}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_state">State <span class="req">*</span></label>
          <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state full-width">
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
          <label for="{{field-set-id}}_zip">Zip</label>
          <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip full-width" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_county">Country <span class="req">*</span></label>
          <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country full-width" maxlength="100" placeholder="Country" value="{{country}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_phone">Program Phone</label>
          <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone full-width g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" placeholder="800-223-3334" value="{{phone}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_county">County </label>
          <input type="text" name="{{field-set-id}}[data][county]" id="{{field-set-id}}_county" class="full-width" maxlength="100" placeholder="Anycounty" value="{{county}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_link">Site URL </label>
          <input type="text" name="{{field-set-id}}[data][link]" id="{{field-set-id}}_link" class="full-width" maxlength="150" placeholder="https://funtimes.com" pattern="^(https?:\/\/)?[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}\/?$" value="{{link}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_instagram">Instagram</label>
          <input type="text" name="{{field-set-id}}[data][instagram]" class="full-width" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_profile_img">Logo Image (min. 400px x 300px)</label>
          <div class="crop_img medium-padding" data-g365_crop_settings="org_profile" data-g365_croppie_img_url="{{profile_img_url}}">
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
        <div>
          <a class="site-button button in-block g365-expand-collapse-fieldset g365-small-button no-margin-bottom" data-click-target="{{field-set-id}}">Done with Club</a>
        </div>
      </div>
    </div>
EOD;
$club_registration_init = <<<EOD
<div id="g365_club_form_wrap">
  <h1 class="section_title">Club Form<br><small>Find or register clubs</small></h1>
  <p>Please search for your club team to start.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="sc_name">
      <label for="club_names">Club Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input full-width block" id="club_names" data-g365_action="load_form" data-g365_type="club_names" data-g365_form_template="form_template" data-g365_form_dest="g365_club_form" data-ls_user_ac="{{user_ac}}" placeholder="Enter Club Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_club_form" class="primary-form" name="g365_club_form" enctype="multipart/form-data" method="post" data-g365_type="club_names">
      <div id="g365_club_form_data"></div>
      <div id="g365_club_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_club_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Club Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
$club_registration_init_admin = <<<EOD
<div id="g365_club_form_wrap">
  <h1 class="section_title">Club Form<br><small>Find or register clubs</small></h1>
  <p>Please fillout this form to make your club available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="sc_name">
      <label for="player_names">Club Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input full-width block" data-g365_action="load_form" data-g365_type="club_names_admin" data-g365_form_template="form_template" data-g365_form_dest="g365_club_form" placeholder="Enter Club Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_club_form" class="primary-form" name="g365_club_form" enctype="multipart/form-data" method="post" data-g365_type="club_names_admin">
      <div id="g365_club_form_data"></div>
      <div id="g365_club_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_club_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Club Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
// <div id="g365_club_form_wrap" data-g365_form_load_min="true">
$club_registration_init_sl = <<<EOD
<div id="g365_club_form_wrap">
  <h1 class="section_title">Edit Club</h1>
  <div class="form-holder">
    <form id="g365_club_form" class="primary-form" name="g365_club_form" enctype="multipart/form-data" method="post" data-g365_type="og_ed">
      <div id="g365_club_form_data"></div>
      <div id="g365_club_form_submit" class="g365_form_sub_block">
        <hr />
        <div id="g365_club_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button secondary button expanded g365-primary-submit" type="submit" value="submit">Update Club Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
//Add Roster to Event Club form & Roster for Club Team Page & Checkout cart
$club_registration_form_full = <<<EOD
<div id="{{field-set-id}}_fieldset" class="form__border gray-bg small-padding tiny-margin-top">
  <form id="g365_club_form" class="primary-form" name="g365_club_form" enctype="multipart/form-data" method="post" data-g365_type="club_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3>
      <span class="change-title" data-default_value="Club Team" data-g365_change_targets="#{{field-set-id}}_name">{{name}}</span>
      <span class="change-title medium-gray parenthesis-wrapper" data-g365_change_targets="#{{field-set-id}}_abbreviation">{{abbreviation}}</span>
    </h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[data][type]" value="1">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <label for="{{field-set-id}}_name">Club Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][name]" id="{{field-set-id}}_name" class="full-width" placeholder="(max 60 characters)" maxlength="60" value="{{name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_abbreviation">Club Nickname</label>
      <input type="text" name="{{field-set-id}}[data][abbreviation]" id="{{field-set-id}}_abbreviation" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{abbreviation}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_director_first">Director First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][director_first]" id="{{field-set-id}}_director_first" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_director_last">Director Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][director_last]" id="{{field-set-id}}_director_last" class="full-width" placeholder="(max 30 characters)" maxlength="30" value="{{director_last}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_director_email">Director Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][director_email]" id="{{field-set-id}}_director_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{director_email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_director_phone">Director Phone <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][director_phone]" id="{{field-set-id}}_director_phone" class="full-width g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" placeholder="112-223-3334" value="{{director_phone}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Program Email</label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="full-width" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="info@acme.com" value="{{email}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="full-width" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_country">Country <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="full-width" maxlength="100" placeholder="Country (max. 100 characters)" value="{{country}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Program Phone</label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="full-width g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" placeholder="800-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_link">Site URL </label>
      <input type="text" name="{{field-set-id}}[data][link]" id="{{field-set-id}}_link" class="full-width" maxlength="150" placeholder="https://funtimes.com" pattern="^(https?:\/\/)?[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}\/?$" value="{{link}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="full-width" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_profile_img">Logo Image (400px x 300px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="org_profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][profile_img_data]">
          <input id="{{field-set-id}}_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a class="button tiny-padding remove-croppie button__close-crop no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div id="g365_club_form_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Club Data</button>
  </form>
</div>
EOD;
//address
//     <div class="tiny-margin-bottom tiny-padding no-input-margin">
//       <label for="{{field-set-id}}_address">Program Address <span class="req">*</span></label>
//       <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="full-width" maxlength="100" placeholder="123 Common St." value="{{address}}" required>
//     </div>

//zip
//     <div class="tiny-margin-bottom tiny-padding no-input-margin">
//       <label for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
//       <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="full-width" pattern="[0-9]{5}" placeholder="12345" value="{{zip}}" required>
//     </div>

// county
//     <div class="tiny-margin-bottom tiny-padding no-input-margin">
//       <label for="{{field-set-id}}_county">County </label>
//       <input type="text" name="{{field-set-id}}[data][county]" id="{{field-set-id}}_county" class="full-width" maxlength="100" placeholder="Any County (max. 100 characters)" value="{{county}}">
//     </div>

?>