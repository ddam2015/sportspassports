<?php
$claim_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$claim_registration_init = <<<EOD
<div id="claim_admin_fieldset" class="gset small-padding">
  <form id="claim_admin" class="primary-form" name="g365_claim_form" enctype="multipart/form-data" method="post" data-g365_type="claiming">
    <h3>{{element_title}}</h3>
    <p>
    <strong>First:</strong> update status to authorized. <br> <strong>Second:</strong> select "Update Claim". <br> <strong>Third:</strong> select "Confirm Approval".
    </p>
    <input type="hidden" name="claim_admin[id]" value="{{id}}">
    <input type="hidden" name="claim_admin[type]" value="{{type}}">
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="target_names" class="tiny-margin-top louder">Name <span class="req">*</span></label>
      <input type="hidden" id="target_id" data-g365_error_target="target_names" value="{{target}}" name="claim_admin[target]" required>
      <input type="text" id="target_names" class="g365_livesearch_input expanded block" data-g365_action="select_data" data-g365_form_template="form_template_min" data-g365_type="{{type_name}}" data-ls_target="target_id" data-g365_form_dest="player_add" placeholder="Enter Name" value="{{name}}" autocomplete="off" autofocus>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="claiming_email">Requester Email <span class="req">*</span></label>
      <input type="email" name="claim_admin[email]" id="claiming_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="claim_admin_status">Status <span class="req">*</span></label>
      <select name="claim_admin[status]" id="claim_admin_status" data-g365_select="{{status}}" class="expanded" required>
        <option value="1">Awaiting Authorization</option>
        <option value="2">Authorized</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="claim_admin_site_key">Site <span class="req">*</span></label>
      <select name="claim_admin[site_key]" id="claim_admin_site_key" data-g365_select="{{site_key}}" class="expanded" required>
        <optgroup label="Live Sites">
          <option value="SPP">Sports Passports</option>
          <option value="G3P">Grassroots365</option>
          <option value="OGP">Open Gym Premier</option>
          <option value="EBP">Elite Basketball Circuit</option>
        </optgroup>
        <optgroup label="Dev Sites">
          <option value="SPD">Sports Passports</option>
          <option value="G3D">Grassroots365</option>
          <option value="OGD">Open Gym Premier</option>
          <option value="EBD">Elite Basketball Circuit</option>
        </optgroup>
      </select>
    </div>
    <div id="claim_admin_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" name="claim_admin[sub_button]" data-g365_sender="update_record" value="update">Update Claim</button>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" name="claim_admin[sub_button]" data-g365_sender="confirm_now" value="submit">Confirm Approval</button>
  </form>
</div>
EOD;
?>