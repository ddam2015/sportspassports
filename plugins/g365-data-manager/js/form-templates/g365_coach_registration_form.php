<?php
$coach_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$coach_registration_form_min = <<<EOD
<div id="{{field-set-id}}" class="gset small-padding tiny-margin-top">
  <form id="{{field-set-id}}_fieldset" class="primary-form" name="g365_coach_form" enctype="multipart/form-data" method="post" data-g365_type="coach_names" data-target_field="{{field-set-id-origin}}">
    <div><div><a class="site-close-button remove-button site-button button">cancel</a></div></div>
    <h3 class="change-title" data-default_value="Coach" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <label for="{{field-set-id}}_first_name">Coach First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Coach Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
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
          <input id="{{field-set-id}}_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Coach Data</button>
  </form>
</div>
EOD;
$coach_registration_form = <<<EOD
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
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Coach" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <label for="{{field-set-id}}_first_name">Coach First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Coach Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="small-margin-bottom">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="full-width g365-input-formatter" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
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
          <input id="{{field-set-id}}_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div>
      <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$coach_registration_form_ed = <<<EOD
<div id="{{field-set-id}}" class="g365_form">
  <hr class="g365-divider" />
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding green-border input-group" data-click-target="{{field-set-id}}">
    <span class="input-group-field"></span>
    <a class="input-group-button button hide-on-disable">edit</a>
  </div>
  <div id="{{field-set-id}}_fieldset" class="gset small-padding">
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Coach" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      <label for="{{field-set-id}}_first_name">Coach First Name <span class="req">*</span></label>
      <input type="text" id="{{field-set-id}}_first_name" name="{{field-set-id}}[data][first_name]" class="expanded" value="{{first_name}}" readonly>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Coach Last Name <span class="req">*</span></label>
      <input type="text" id="{{field-set-id}}_last_name" name="{{field-set-id}}[data][last_name]" class="expanded" value="{{last_name}}" readonly>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone </label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="enter only numbers" value="{{phone}}">
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
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_facebook">Facebook</label>
      <input type="text" name="{{field-set-id}}[data][facebook]" class="expanded" id="{{field-set-id}}_facebook" placeholder="Facebook username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-facebook}}">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_twitter">X</label>
      <input type="text" name="{{field-set-id}}[data][twitter]" class="expanded" id="{{field-set-id}}_twitter" placeholder="X username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
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
          <input id="{{field-set-id}}_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div>
      <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
$coach_registration_init = <<<EOD
<div id="g365_coach_form_wrap">
  <h1 class="section_title">Coach Profile Form<br><small>Find or register coaches</small></h1>
  <p>Please fillout this form to make your coach available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="pl_name">
      <label for="player_names">Coach Name <span class="req">*</span></label>
      <input type="text" class="g365_livesearch_input full-width block" data-g365_action="load_form" data-g365_type="coach_names" data-g365_form_dest="g365_coach_form" placeholder="Enter Coach Name" autocomplete="off" autofocus>
    </div>
    <form id="g365_coach_form" class="primary-form" name="g365_coach_form" enctype="multipart/form-data" method="post" data-g365_type="coach_names">
      <div id="g365_coach_form_data"></div>
      <div id="g365_coach_form_submit" class="g365_form_sub_block" style="display:none;">
        <hr />
        <div id="g365_coach_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Coach Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
$coach_registration_init_sl = <<<EOD
<div id="g365_coach_form_wrap">
  <h1 class="section_title">Edit Coach</h1>
  <div class="form-holder">
    <form id="g365_coach_form" class="primary-form" name="g365_coach_form" enctype="multipart/form-data" method="post" data-g365_type="co_ed">
      <div id="g365_coach_form_data"></div>
      <div id="g365_coach_form_submit" class="g365_form_sub_block">
        <hr />
        <div id="g365_coach_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button secondary button expanded g365-primary-submit" type="submit" value="submit">Update Coach Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
//Add Coach from my Account
$coach_registration_form_sl = <<<EOD
<div id="reload_button" class="hide" data-g365_action="add_result">
  <a href="/account/coach/" class="button">Go to Your Profile</a>
</div>
<div id="coach_names" class="form__wrapper small-padding tiny-margin-top">
  <form id="coach_names_fieldset" class="primary-form" name="g365_coach_form" enctype="multipart/form-data" method="post" data-g365_type="coach_names" data-target_field="reload_button">
    <h3 class="change-title" data-default_value="" data-g365_change_targets="#coach_names_first_name|#coach_names_last_name">{{first_name}} {{last_name}}</h3>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <input type="hidden" name="coach_names[proc_type]" value="proc_data">
      <label for="coach_names_first_name">Coach First Name <span class="req">*</span></label>
      <input type="text" name="coach_names[data][first_name]" id="coach_names_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="coach_names_last_name">Coach Last Name <span class="req">*</span></label>
      <input type="text" name="coach_names[data][last_name]" id="coach_names_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="coach_names_phone">Phone </label>
      <input type="tel" name="coach_names[data][phone]" id="coach_names_phone" class="expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="enter only numbers" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="coach_names_email">Email <span class="req">*</span></label>
      <input type="email" name="coach_names[data][email]" id="coach_names_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="coach_names_city">City <span class="req">*</span></label>
      <input type="text" name="coach_names[data][city]" id="coach_names_city" class="expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="coach_names_state">State <span class="req">*</span></label>
      <select name="coach_names[data][state]" id="coach_names_state" data-g365_select="{{state}}" class="expanded" required>
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
      <label for="coach_names_instagram">Instagram</label>
      <input type="text" name="coach_names[data][instagram]" class="expanded" id="coach_names_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="coach_names_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="coach_names[data][profile_img_data]">
          <input id="coach_names_profile_img" type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a class="button tiny-padding remove-croppie button__close-crop no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div id="coach_names_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Coach Data</button>
  </form>
</div>
EOD;

?>