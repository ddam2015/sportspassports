<?php
$player_registration_result = <<<EOD
  <li class="{{li_class}}"><span class="result-title">{{result_title}}</span> : <span class="result-status">{{result_status}}</span></li>
EOD;
$player_registration_input_item = <<<EOD
  <li id="{{field-set-id}}" class="g365_form"{{exception}}>
    <div class="input-group tiny-margin-bottom">
      <span class="input-group-label result-title">
        {{element_title}}
      </span>
      <input class="input-group-field jersey-input" type="number" min="0" max="9999" maxlength="4" name="{{field-set-id-flat}}[data][players][{{id}}][j_num]" value="{{j_num}}" placeholder="Jersey #" required>
      <div class="input-group-button">
        <a class="button site-close-button"><span>X</span></a>
      </div>
    </div>
    <input type="hidden" value="{{id}}" data-g365_data_key="id">
  </li>
EOD;
$player_registration_input_item_roster_sl = <<<EOD
  <li id="{{field-set-id}}" class="g365_form"{{exception}}>
    <div class="input-group tiny-margin-bottom">
      <span class="input-group-label result-title">
        {{element_title}}
      </span>
      <input class="input-group-field jersey-input" type="number" min="0" max="9999" maxlength="4" name="tournament_roster_admin[data][players][{{id}}][j_num]" value="{{j_num}}" placeholder="Jersey #" required>
      <div class="input-group-button">
        <a class="button site-close-button"><span>X</span></a>
      </div>
    </div>
    <input type="hidden" value="{{id}}" data-g365_data_key="id">
  </li>
EOD;
$player_registration_form_min = <<<EOD
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
        <select class="input-group-field" id="{{field-set-id}}_birthday_dy" data-g365_select="{{birthday_dy}}" required>
          {{current_birth_years}}
        </select>
        <span class="input-group-label">Year</span>
      </div>
      <input type="hidden" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_grad" style="width: 100%">
      <label class="hide" for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" style="border-bottom-left-radius: 2rem; 
        border-top-left-radius: 2rem;" required>
          <option value="">-- Select Grade*</option>
          <option value="0">Kindergarten</option>
          <option value="1">1st Grade</option>
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
        <input class="input-group-field read-only" type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grade_grad_yr" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required readonly="readonly" style="border-bottom-right-radius: 2rem; 
        border-top-right-radius: 2rem;">
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
        <option value="">-- Please Select --</option>
//         <option value="Y_Md">Youth Medium</option>
        <option value="Y_Lg">Youth Large</option>
        <option value="Y_XL">Youth X-Large</option>
        <option value="A_Sm">Adult Small</option>
        <option value="A_Md">Adult Medium</option>
        <option value="A_Lg">Adult Large</option>
        <option value="A_XL">Adult X-Large</option>
      </select>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address</label>
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
      <label for="{{field-set-id}}_zip">Zip</label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_country">Country</label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone</label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" pattern="[0-9]{3}-?[0-9]{3}-?[0-9]{4}" data-g365_input_format="tel" placeholder="Phone" value="{{phone}}" maxlength="17">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team <span class="req">*</span></label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}" required>
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
    </div>
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="site-button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Add New Player Data</button>
  </form>
</div>
EOD;
//Main Create Player - form found on this page -> https://dev.grassroots365.com/register/player-certification/ -> christian currently working here & EBC
$player_registration_form_min_full = <<<EOD
<div data-player-form id="{{field-set-id}}_fieldset" class="form__border small-padding tiny-margin-top">
  <form id="{{field-set-id}}" class="player-registration-main-form primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names" data-target_field="{{field-set-id-origin}}">
    <div id="event_details_cancel_cont">
      <h3 class="change-title hide" data-default_value="PLAYER" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
      <a class="site-close-button remove-button site-button button">cancel</a>
    </div>
    <div class="hide">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[proc_cl]" id="{{field-set-id}}_proc_cl" value="{{pl_ev_id}}">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_pl_ev_id" value="{{pl_ev_id}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_fname">
      <label class="hide" for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" placeholder="First Name*" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_lname">
      <label class="hide" for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" placeholder="Last Name*" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_email">
      <label class="hide" for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="Email*" value="{{email}}" required>
    </div>  
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_birth">
      <label class="hide" for="{{field-set-id}}_birthday">Birthdate <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field" id="{{field-set-id}}_birthday_mo" data-g365_select="{{birthday_mo}}" style="border-bottom-left-radius: 2rem; 
        border-top-left-radius: 2rem;" required>
          <option value="">MM*</option>
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
        <select class="input-group-field" id="{{field-set-id}}_birthday_dy" data-g365_select="{{birthday_dy}}" required>
          <option value="">DD*</option>
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
        <select class="input-group-field" id="{{field-set-id}}_birthday_yr" data-g365_select="{{birthday_yr}}" style="border-bottom-right-radius: 2rem; 
        border-top-right-radius: 2rem;" required>
          {{current_birth_years}}
        </select>
      </div>
      <input type="hidden" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded">
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_school">High School </label>
      <input type="text" name="{{field-set-id}}[data][school]" id="{{field-set-id}}_school" class="expanded" placeholder="(max 50 characters)" maxlength="50" value="{{school}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_grad">
      <label class="hide" for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" style="border-bottom-left-radius: 2rem; 
        border-top-left-radius: 2rem;" required>
          <option value="">-- Select Grade*</option>
          <option value="0">Kindergarten</option>
          <option value="1">1st Grade</option>
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
        <input class="input-group-field read-only" type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grade_grad_yr" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required readonly="readonly" style="border-bottom-right-radius: 2rem; 
        border-top-right-radius: 2rem;">
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_gpa">GPA </label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][gpa]" id="{{field-set-id}}_gpa" value="{{gpa}}">
      </div>
      <label for="{{field-set-id}}_sat">SAT </label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][sat]" id="{{field-set-id}}_sat" value="{{sat}}">
      </div>
      <label for="{{field-set-id}}_act">ACT </label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][act]" id="{{field-set-id}}_act" value="{{act}}">
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_height">
      <label class="hide" for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" placeholder="Height*" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" placeholder="Height*" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_address">
      <label class="hide" for="{{field-set-id}}_address">Address </label>
      <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_city">
      <label class="hide" for="{{field-set-id}}_city">City <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_state">
      <label class="hide" for="{{field-set-id}}_state">State <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded" required>
        <option value="">-- State* </option>
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
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_zip">
      <label class="hide" for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code" value="{{zip}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_country">
      <label class="hide" for="{{field-set-id}}_country">Country </label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_phone">
      <label class="hide" for="{{field-set-id}}_phone">Phone  <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Phone*" value="{{phone}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_position">Position </label>
      <input type="hidden" name="{{field-set-id}}[data][position]" id="{{field-set-id}}_position_id" value="{{position_id}}">
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin" id="event_details_club">
      <label class="hide" for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <h4 id="event_details_age_veri">Age Verification</h4>
    <p>If you have them now, you may opt to upload a birth certificate or proof of grade. Note that if you skip this now you will still have to provide one of these documents on-site at the event.</p>
    <div id="event_details_upload_cont">
      <div class="tiny-margin-bottom tiny-padding no-input-margin event_details_upload">
            <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
            <div class="crop_img" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
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
      <div class="tiny-margin-bottom tiny-padding no-input-margin event_details_upload">
        <label for="{{field-set-id}}_recard_img">Proof of Grade (800px x 800px min)</label>
        <div class="crop_img" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
          <div class="cropped_img"></div>
          <div class="crop_upload">
            <div class="crop_upload_canvas_wrap hide">
              <div class="crop_upload_canvas"></div>
            </div>
            <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][recard_img_data]">
            <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
          </div>
          <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie  button__close-crop no-margin-bottom small-margin-top hide">Remove Image</a>
        </div>
      </div>
      <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    </div>
    <button class="event_details_button button g365-primary-submit no-margin-bottom" type="submit" value="submit">Save Player Info</button>
  </form>
</div>
EOD;

//dcp registration form for new player link:https://dev.grassroots365.com/register/dcp/undefined/undefined/
$dcp_player_registration_form_min_full = <<<EOD
<div data-player-form id="{{field-set-id}}_fieldset" class="form__border small-padding tiny-margin-top">
  <form id="{{field-set-id}}" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names" data-target_field="{{field-set-id-origin}}">
    <div><a class="site-close-button remove-button site-button button">cancel</a></div>
    <h3 class="change-title" data-default_value="PLAYER" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="hide">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[proc_cl]" id="{{field-set-id}}_proc_cl" value="{{pl_cp_id}}">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_pl_cp_id" value="{{pl_cp_id}}">
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
      <div class="input-group">
        <select class="input-group-field" id="{{field-set-id}}_birthday_mo" data-g365_select="{{birthday_mo}}" required>
          <option value="">MM</option>
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
        <select class="input-group-field" id="{{field-set-id}}_birthday_dy" data-g365_select="{{birthday_dy}}" required>
          <option value="">DD</option>
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
        <select class="input-group-field" id="{{field-set-id}}_birthday_yr" data-g365_select="{{birthday_yr}}" required>
          {{current_birth_years}}
        </select>
      </div>
      <input type="hidden" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded">
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
      <label for="{{field-set-id}}_school">High School <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][school]" id="{{field-set-id}}_school" class="expanded" placeholder="(max 50 characters)" maxlength="50" value="{{school}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" required>
          <option value="">-- Please Select</option>
          <option value="0">Kindergarten</option>
          <option value="1">1st Grade</option>
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
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grade_grad_yr" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_gpa">GPA </label>
      <div class="input-group">
        <input class="input-group-field" type="number" min="1" max="5" step="0.01" name="{{field-set-id}}[data][gpa]" id="{{field-set-id}}_gpa" value="{{gpa}}">
      </div>
      <label for="{{field-set-id}}_sat">SAT </label>
      <div class="input-group">
        <input class="input-group-field" type="number" min="400" max="1600" step="10" name="{{field-set-id}}[data][sat]" id="{{field-set-id}}_sat" value="{{sat}}">
      </div>
      <label for="{{field-set-id}}_act">ACT </label>
      <div class="input-group">
        <input class="input-group-field" type="number" min="1" max="36" step="1" name="{{field-set-id}}[data][act]" id="{{field-set-id}}_act" value="{{act}}">
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" value="{{weight}}">
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
      <label for="{{field-set-id}}_zip">Zip <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_country">Country </label>
      <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_phone">Phone  <span class="req">*</span></label>
      <input type="tel" name="{{field-set-id}}[data][phone]" id="{{field-set-id}}_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="enter only numbers" value="{{phone}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin hide">
      <label for="{{field-set-id}}_position">Position <span class="req">*</span></label>
      <input type="hidden" name="{{field-set-id}}[data][position]" id="{{field-set-id}}_position_id" value="{{position_id}}" required>
      <input type="text" id="{{field-set-id}}_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_club_names">Club Team</label>
      <input type="hidden" name="{{field-set-id}}[data][club_team]" id="{{field-set-id}}_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    
    <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Save Player Info</button>
  </form>
</div>
EOD;

//form found on player admin search in the backend
$player_registration_form_admin = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form player-tab">
  <form id="{{field-set-id}}" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="player_names_admin" >
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
        <h3 class="change-title g365-expand-collapse-fieldset test" data-click-target="{{field-set-id}}" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
        <div class="g365_set_default">
          <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
          <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
        </div>
        <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Admin Options" data-g365_before="Admin Options"><span class="field-title"></span><span class="field-button">Admin Options</span></a>
        <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_verified">Verified <span class="req">*</span></label>
            <select name="{{field-set-id}}[data][verified]" id="{{field-set-id}}_verified" data-g365_select="{{verified}}" required>
              <option value="0">Unstarted</option>
              <option value="1">Awaiting Certification</option>
              <option value="2">Verified</option>
              <option value="3">Certified</option>
            </select>
          </div>
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_enabled">Enabled <span class="req">*</span></label>
            <select name="{{field-set-id}}[data][enabled]" id="{{field-set-id}}_enabled" data-g365_select="{{enabled}}" required>
              <option value="1">Enabled</option>
              <option value="0">Disabled</option>
            </select>
          </div>
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_account_level">Account Level <span class="req">*</span></label>
            <select name="{{field-set-id}}[data][account_level]" id="{{field-set-id}}_account_level" data-g365_select="{{account_level}}" required>
              <option value="1">Base</option>
              <option value="2">Level 1 - Standard</option>
              <option value="3">Level 2 - Advanced</option>
              <option value="4">Level 3 - Elite</option>
            </select>
          </div>
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_tagline">Tagline</label>
            <input type="text" name="{{field-set-id}}[data][tagline]" id="{{field-set-id}}_tagline" class="expanded" placeholder="(max 100 characters)" maxlength="100" value="{{tagline}}">
          </div>
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_videos">Videos (Raw JSON)</label>
            <textarea name="{{field-set-id}}[data][videos]" id="{{field-set-id}}_videos" class="expanded" placeholder="{'yt1': 'BeINMjshnXr', 'yt2': 'BeINMjshnW', etc...}">{{videos}}</textarea>
          </div>
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_permissions">Permissions (Raw JSON)</label>
            <textarea name="{{field-set-id}}[data][access]" id="{{field-set-id}}_access" class="expanded" placeholder="{'G3P': [1,2,5], 'OGP': [381], 'EBP': [443,369], etc...}">{{access}}</textarea>
          </div>
          <div class="tiny-margin-bottom tiny-padding no-input-margin">
            <label for="{{field-set-id}}_nickname">URL (only alphanumeric & hyphens)<span class="req">*</span></label>
            <input type="text" name="{{field-set-id}}[data][nickname]" id="{{field-set-id}}_nickname" class="expanded" placeholder="(max 60 characters)" maxlength="60" value="{{nickname}}">
          </div>
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
          <label for="{{field-set-id}}_email">Email</label>
          <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_birthday">Birthdate</label>
          <div class="input-group">
            <select class="input-group-field" id="{{field-set-id}}_birthday_mo" data-g365_select="{{birthday_mo}}">
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
            <select class="input-group-field" id="{{field-set-id}}_birthday_dy" data-g365_select="{{birthday_dy}}">
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
            <select class="input-group-field" id="{{field-set-id}}_birthday_yr" data-g365_select="{{birthday_yr}}" >
              {{current_birth_years}}
            </select>
            <span class="input-group-label">Year</span>
          </div>
          <input type="hidden" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-">
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
              <input type="file" class="crop_uploader" value="Choose a file" accept="image/*, .pdf" />
            </div>
            <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
          </div>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_jersey_size">Jersey Size</label>
          <select name="{{field-set-id}}[data][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded">
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
          <label for="{{field-set-id}}_grade">Grade</label>
          <div class="input-group">
            <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}">
              <option value="">-- Please Select</option>
              <option value="0">Kindergarten</option>
              <option value="1">1st Grade</option>
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
            <input class="input-group-field" type="number" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grade_grad_yr" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}">
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
          <label for="{{field-set-id}}_gpa">GPA</label>
          <input type="number" min="1" max="5" step="0.01" name="{{field-set-id}}[data][gpa]" id="{{field-set-id}}_gpa" class="expanded" placeholder="3.0" value="{{gpa}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_sat">SAT</label>
          <input type="number" min="400" max="1600" step="10" name="{{field-set-id}}[data][sat]" id="{{field-set-id}}_sat" class="expanded" placeholder="1400" value="{{sat}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_act">ACT</label>
          <input type="number" min="1" max="36" step="1" name="{{field-set-id}}[data][act]" id="{{field-set-id}}_act" class="expanded" placeholder="25" value="{{act}}">
        </div>
        <hr class="large-margin-top large-margin-bottom">
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_height">Height</label>
          <div class="input-group">
            <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" value="{{height_ft}}">
            <span class="input-group-label">ft.</span>
            <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" value="{{height_in}}">
            <span class="input-group-label">in.</span>
          </div>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_weight">Weight</label>
          <div class="input-group">
            <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" value="{{weight}}">
            <span class="input-group-label">lbs.</span>
          </div>
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_address">Address</label>
          <input type="text" name="{{field-set-id}}[data][address]" id="{{field-set-id}}_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_city">City</label>
          <input type="text" name="{{field-set-id}}[data][city]" id="{{field-set-id}}_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_state">State</label>
          <select name="{{field-set-id}}[data][state]" id="{{field-set-id}}_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded">
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
          <input type="tel" name="{{field-set-id}}[data][zip]" id="{{field-set-id}}_zip" class="maps-autocomplete-zip expanded" placeholder="Zip Code*" value="{{zip}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_country">Country</label>
          <input type="text" name="{{field-set-id}}[data][country]" id="{{field-set-id}}_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_phone">Phone</label>
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
          <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
        </div>
        
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_school">School</label>
          <input type="text" class="expanded" name="{{field-set-id}}[data][school]" id="{{field-set-id}}_school" value="{{school}}" placeholder="Enter School Name" >
        </div>
        
        
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_instagram">Instagram</label>
          <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
        </div>
        <div class="tiny-margin-bottom tiny-padding no-input-margin">
          <label for="{{field-set-id}}_twitter">Twitter</label>
          <input type="text" name="{{field-set-id}}[data][twitter]" class="expanded" id="{{field-set-id}}_twitter" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
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
              <input type="file" class="crop_uploader" value="Take Photo or Choose a file" accept="image/*" />
            </div>
            <a id="{{field-set-id}}_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
          </div>
        </div>
        <div>
          <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
        </div>
      </div>

      <br>
      <div id="{{field-set-id}}_message" class="small-margin-top form_message hide"></div>
      <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit New Player Data</button>
  </form>
</div>
EOD;
$player_registration_init_sl_admin = <<<EOD
<div id="pl_admin_fieldset" class="gset small-padding">
  <form id="pl_admin" class="primary-form" name="g365_player_admin_form" enctype="multipart/form-data" method="post" data-g365_type="player_admin" data-target_field="reload_button">
    <h3>{{first_name}} {{last_name}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="pl_admin[proc_type]" value="proc_data">
      <input type="hidden" name="pl_admin[id]" id="pl_admin_id" value="{{id}}">
    </div>
    <a class="field-toggle button tiny-margin-bottom" data-g365_after="Close Admin Options" data-g365_before="Admin Options"><span class="field-title"></span><span class="field-button">Admin Options</span></a>
    <div class="tiny-margin-bottom tiny-padding no-input-margin callout" style="display:none;">
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_verified">Verified <span class="req">*</span></label>
        <select name="pl_admin[data][verified]" id="pl_admin_verified" data-g365_select="{{verified}}" required>
          <option value="0">Unstarted</option>
          <option value="1">Awaiting Certification</option>
          <option value="2">Verified</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_enabled">Enabled <span class="req">*</span></label>
        <select name="pl_admin[data][enabled]" id="pl_admin_enabled" data-g365_select="{{enabled}}" required>
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_account_level">Account Level <span class="req">*</span></label>
        <select name="pl_admin[data][account_level]" id="pl_admin_account_level" data-g365_select="{{account_level}}" required>
          <option value="1">Base</option>
          <option value="2">Level 1 - Standard</option>
          <option value="3">Level 2 - Advanced</option>
          <option value="4">Level 3 - Elite</option>
        </select>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_tagline">Tagline</label>
        <input type="text" name="pl_admin[data][tagline]" id="pl_admin_tagline" class="expanded" placeholder="(max 100 characters)" maxlength="100" value="{{tagline}}">
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_videos">Videos (Raw JSON)</label>
        <textarea name="pl_admin[data][videos]" id="pl_admin_videos" class="expanded" placeholder="{'yt1': 'BeINMjshnXr', 'yt2': 'BeINMjshnW', etc...}">{{videos}}</textarea>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_permissions">Permissions (Raw JSON)</label>
        <textarea name="pl_admin[data][access]" id="pl_admin_access" class="expanded" placeholder="{'G3P': [1,2,5], 'OGP': [381], 'EBP': [443,369], etc...}">{{access}}</textarea>
      </div>
      <div class="tiny-margin-bottom tiny-padding no-input-margin">
        <label for="pl_admin_nickname">URL (only alphanumeric & hyphens)<span class="req">*</span></label>
        <input type="text" name="pl_admin[data][nickname]" id="pl_admin_nickname" class="expanded" placeholder="(max 60 characters)" maxlength="60" value="{{nickname}}">
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="pl_admin[data][first_name]" id="pl_admin_first_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{first_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="pl_admin[data][last_name]" id="pl_admin_last_name" class="expanded" placeholder="(max 30 characters)" maxlength="30" value="{{last_name}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_email">Email</label>
      <input type="email" name="pl_admin[data][email]" id="pl_admin_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_birthday">Birthdate</label>
      <div class="input-group">
        <select class="input-group-field" id="pl_admin_birthday_mo" data-g365_select="{{birthday_mo}}">
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
        <select class="input-group-field" id="pl_admin_birthday_dy" data-g365_select="{{birthday_dy}}">
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
        <select class="input-group-field" id="pl_admin_birthday_yr" data-g365_select="{{birthday_yr}}">
          {{current_birth_years}}
        </select>
        <span class="input-group-label">Year</span>
      </div>
      <input type="hidden" name="pl_admin[data][birthday]" id="pl_admin_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#pl_admin_birthday_mo|#pl_admin_birthday_dy|#pl_admin_birthday_yr" data-g365_change_delimiter="-">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_bcert_img">Birth Certificate (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="pl_admin[data][bcert_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="pl_admin_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="pl_admin[data][jersey_size]" id="pl_admin_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
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
      <label for="pl_admin_grade">Grade <span class="req">*</span></label>
      <div class="input-group">
        <select class="input-group-field grade-graduation" id="pl_admin_grade" data-g365_select="{{grade}}" required>
          <option value="">-- Please Select</option>
          <option value="0">Kindergarten</option>
          <option value="1">1st Grade</option>
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
        <input class="input-group-field" type="number" name="pl_admin[data][grad_year]" id="pl_admin_grade_grad_yr" min="{{grad-min}}" max="{{grad-max}}" placeholder="2999" value="{{grad_year}}" required>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_recard_img">Proof of Grade (800px x 800px min)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="reportcard" data-g365_croppie_img_url="{{recard_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="pl_admin[data][recard_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="pl_admin_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_gpa">GPA</label>
      <input type="number" min="1" max="5" step="0.01" name="pl_admin[data][gpa]" id="pl_admin_gpa" class="expanded" placeholder="3.0" value="{{gpa}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_sat">SAT</label>
      <input type="number" min="400" max="1600" step="10" name="pl_admin[data][sat]" id="pl_admin_sat" class="expanded" placeholder="1400" value="{{sat}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_act">ACT</label>
      <input type="number" min="1" max="36" step="1" name="pl_admin[data][act]" id="pl_admin_act" class="expanded" placeholder="25" value="{{act}}">
    </div>
    <hr class="large-margin-top large-margin-bottom">
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_height">Height</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="pl_admin[data][height_ft]" id="pl_admin_height" min="2" max="9" value="{{height_ft}}">
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="pl_admin[data][height_in]" id="pl_admin_height_in" min="0" max="11" value="{{height_in}}">
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="pl_admin[data][weight]" id="pl_admin_weight" min="30" max="500" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_address">Address</label>
      <input type="text" name="pl_admin[data][address]" id="pl_admin_address" class="maps-autocomplete expanded" maxlength="100" placeholder="Street Address*" value="{{address}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_city">City</label>
      <input type="text" name="pl_admin[data][city]" id="pl_admin_city" class="maps-autocomplete-city expanded" maxlength="100" placeholder="City*" value="{{city}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_state">State</label>
      <select name="pl_admin[data][state]" id="pl_admin_state" data-g365_select="{{state}}" class="maps-autocomplete-state expanded">
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
      <label for="pl_admin_zip">Zip</label>
      <input type="tel" name="pl_admin[data][zip]" id="pl_admin_zip" class="maps-autocomplete-zip expanded" pattern="[0-9]{5}" placeholder="Zip Code*" value="{{zip}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_county">Country</label>
      <input type="text" name="pl_admin[data][country]" id="pl_admin_country" class="maps-autocomplete-country expanded" maxlength="100" placeholder="Country" value="{{country}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_phone">Phone</label>
      <input type="tel" name="pl_admin[data][phone]" id="pl_admin_phone" class="maps-autocomplete-phone expanded g365-input-formatter" data-g365_input_format="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="112-223-3334" value="{{phone}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_position">Position</label>
      <input type="hidden" name="pl_admin[data][position]" id="pl_admin_position_id" value="{{position_id}}">
      <input type="text" id="pl_admin_position" class="g365_livesearch_input expanded" value="{{position_name}}" data-g365_action="select_data" data-ls_target="pl_admin_position_id" data-g365_type="positions" data-ls_no_add="true" placeholder="Find Position" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_club_names">Club Team</label>
      <input type="hidden" name="pl_admin[data][club_team]" id="pl_admin_club_team" value="{{club_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="pl_admin_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="pl_admin_club_team" data-g365_form_dest="pl_admin_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_school_names">School</label>
      <input type="hidden" name="pl_admin[data][school]" id="pl_admin_school" value="{{school_id}}">
      <input type="text" class="g365_livesearch_input expanded" id="pl_admin_school_names" value="{{school_name}}" data-g365_action="select_data" data-ls_target="pl_admin_school" data-g365_form_dest="pl_admin_school_add" data-g365_type="school_names" placeholder="Enter School Name" autocomplete="off">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_instagram">Instagram</label>
      <input type="text" name="pl_admin[data][instagram]" class="expanded" id="pl_admin_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_twitter">Twitter</label>
      <input type="text" name="pl_admin[data][twitter]" class="expanded" id="pl_admin_twitter" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-twitter}}">
    </div>
    <div class="large-margin-bottom tiny-padding no-input-margin">
      <label for="pl_admin_profile_img">Profile Image (400px x 600px)</label>
      <div class="crop_img medium-padding" data-g365_crop_settings="profile" data-g365_croppie_img_url="{{profile_img_url}}">
        <div class="cropped_img"></div>
        <div class="crop_upload">
          <div class="crop_upload_canvas_wrap hide">
            <div class="crop_upload_canvas"></div>
          </div>
          <input type="hidden" class="croppie_img_data" name="pl_admin[data][profile_img_data]">
          <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
        </div>
        <a id="pl_admin_profile_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide">Remove Image</a>
      </div>
    </div>
    <div id="pl_admin_message" class="small-margin-top form_message hide"></div>
    <button class="button g365-primary-submit no-margin-bottom" type="submit" value="submit">Update Player Event Data</button>
  </form>
</div>
EOD;
$player_registration_form = <<<EOD
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
      <input type="hidden" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
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
        <option value="0">Kindergarten</option>
        <option value="1">1st Grade</option>
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
      <input class="input-group-field" type="hidden" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grade_grad_yr" value="{{grad_year}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address</label>
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
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_instagram">Instagram</label>
      <input type="text" name="{{field-set-id}}[data][instagram]" class="expanded" id="{{field-set-id}}_instagram" placeholder="username only" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{social-instagram}}">
    </div>
    <div>
      <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
</div>
EOD;
//not currently used - 11/2020
$player_registration_form_full = <<<EOD
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
      <input type="hidden" name="{{field-set-id}}[data][birthday]" id="{{field-set-id}}_birthday" placeholder="12-30-1970" value="{{birthday}}" class="change-title" data-default_value="{{birthday}}" data-g365_change_targets="#{{field-set-id}}_birthday_mo|#{{field-set-id}}_birthday_dy|#{{field-set-id}}_birthday_yr" data-g365_change_delimiter="-" required>
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
      <label for="{{field-set-id}}_grade">Grade <span class="req">*</span></label>
      <select class="input-group-field grade-graduation" id="{{field-set-id}}_grade" data-g365_select="{{grade}}" required>
        <option value="">Please select</option>
        <option value="0">Kindergarten</option>
        <option value="1">1st Grade</option>
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
      <input class="input-group-field" type="hidden" name="{{field-set-id}}[data][grad_year]" id="{{field-set-id}}_grade_grad_yr" value="{{grad_year}}" required>
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
      <label for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" value="{{weight}}">
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
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off" data-ls_no_add="true">
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
    <div>
      <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
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
$player_award_search_form = <<<EOD
  <li id="{{field-set-id}}" class="g365_form">
    <div class="input-group tiny-margin-bottom">
      <span class="input-group-label result-title">
        {{element_title}}
      </span>
      <div class="input-group-button">
        <a class="button site-close-button"><span>X</span></a>
      </div>
    </div>
    <input type="hidden" value="{{id}}" name="player[pl]">
  </li>
EOD;
$player_award_search_init = <<<EOD
<div id="g365_player_award_wrap">
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="pl_name">
      <label for="player_award">Player Name <span class="req">*</span></label>
			<input type="text" class="g365_livesearch_input expanded" id="player_award" value="" data-g365_action="load_form" data-g365_form_template="form_template" data-g365_form_dest="g365_player_award" data-g365_type="player_award" placeholder="Search for Players..." autocomplete="off">
    </div>
    <form id="g365_player_award" class="primary-form" name="g365_player_award" enctype="multipart/form-data" method="post" data-g365_type="player_award" action="/wp-admin/admin.php?page=admin_badge_data">
      <ul id="g365_player_award_data"></ul>
      <div id="g365_player_award_submit" class="g365_form_sub_block">
        <hr />
        <div id="g365_player_award_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit" type="submit" value="submit">Submit Player Data for all Awards</button>
      </div>
    </form>
  </div>
</div>
EOD;
$player_registration_init = <<<EOD
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
$player_registration_init_admin = <<<EOD
<div id="g365_player_form_wrap">
  <h1 class="section_title">Add Player</h1>
  <p>Please fillout this form to make your player available to the system.</p>
  <div class="form-holder">
    <hr />
    <div class="g365_toggle open_element" data-group="pl_name">
      <label for="player_names">Player Name <span class="req">*</span></label>
      <input type="text" id="player_names" class="g365_livesearch_input expanded" data-g365_action="load_form" data-g365_form_template="form_template" data-g365_type="player_names_admin" data-g365_form_dest="g365_player_form" placeholder="Enter Player Name" autocomplete="off" autofocus>
    </div>
  </div>
</div>
EOD;
//Player Editor Form
$site = get_site_url();
$player_registration_form_sl = <<<EOD
<div id="{{field-set-id}}" data-g365_dropdown_key="{{dropdown_key}}" data-g365_dropdown_target="{{dropdown_target}}" class="g365_form">
  <!--<hr class="g365-divider" /> -->
  <div id="{{field-set-id}}_fieldset_title" class="form-collapse-title hide g365-expand-collapse-fieldset tiny-padding gray-border white-bg flex-container flex-dir-row align-justify align-middle" data-click-target="{{field-set-id}}">
    <span class=""></span>
    <div class="flex-container align-right flex-dir-row small-flex-dir-column">
        <a href="{$site}/player/{{nickname}}" class="button-secondary viewProfileBtn  hide-on-disable margin-right" id="viewProfileBtn {{field-set-id}}_nickname">view</a>
        <a class=" button-tertiary hide-on-disable">edit</a>
    </div>
  </div>
  <div id="{{field-set-id}}_fieldset" class="form__wrapper white-bg small-padding small-margin-bottom">
    <h3 class="change-title g365-expand-collapse-fieldset" data-click-target="{{field-set-id}}" data-default_value="Player" data-g365_change_targets="#{{field-set-id}}_first_name|#{{field-set-id}}_last_name">{{first_name}} {{last_name}}</h3>
    <div class="g365_set_default">
      <input type="hidden" name="{{field-set-id}}[proc_type]" value="proc_data">
      <input type="hidden" name="{{field-set-id}}[id]" id="{{field-set-id}}_id" value="{{id}}">
      
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_first_name">Player First Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][first_name]" id="{{field-set-id}}_first_name" class="expanded" value="{{first_name}}" readonly>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_last_name">Player Last Name <span class="req">*</span></label>
      <input type="text" name="{{field-set-id}}[data][last_name]" id="{{field-set-id}}_last_name" class="expanded" value="{{last_name}}" readonly>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_birthday">Player Birthday <span class="req">*</span></label>
      <input type="text" id="{{field-set-id}}_birthday" class="expanded" value="{{birthday}}" disabled>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_grade">Player Grade & Class</label>
      <div class="input-group">
        <span class="input-group-label">Grade</span>
        <input class="input-group-field" type="number" id="{{field-set-id}}_grade" value="{{grade}}" disabled>
        <span class="input-group-label">Class of</span>
        <input class="input-group-field" type="number" id="{{field-set-id}}_grad_year" value="{{grad_year}}" disabled>
      </div>
    </div>
    
      
    
    <hr>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_email">Email <span class="req">*</span></label>
      <input type="email" name="{{field-set-id}}[data][email]" id="{{field-set-id}}_email" class="expanded" pattern="^[A-Za-z0-9\._%-]+@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$" placeholder="john@acme.com" value="{{email}}" required>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_jersey_size">Jersey Size <span class="req">*</span></label>
      <select name="{{field-set-id}}[data][jersey_size]" id="{{field-set-id}}_jersey_size" data-g365_select="{{jersey_size}}" class="expanded" required>
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
      <label for="{{field-set-id}}_height">Height <span class="req">*</span></label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_ft]" id="{{field-set-id}}_height" min="2" max="9" value="{{height_ft}}" required>
        <span class="input-group-label">ft.</span>
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][height_in]" id="{{field-set-id}}_height_in" min="0" max="11" value="{{height_in}}" required>
        <span class="input-group-label">in.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_weight">Weight</label>
      <div class="input-group">
        <input class="input-group-field" type="number" name="{{field-set-id}}[data][weight]" id="{{field-set-id}}_weight" min="30" max="500" value="{{weight}}">
        <span class="input-group-label">lbs.</span>
      </div>
    </div>
    <div class="tiny-margin-bottom tiny-padding no-input-margin">
      <label for="{{field-set-id}}_address">Address</label>
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
      <input type="text" class="g365_livesearch_input expanded" id="{{field-set-id}}_club_names" value="{{club_name}}" data-g365_action="select_data" data-ls_target="{{field-set-id}}_club_team" data-g365_form_dest="{{field-set-id}}_club_add" data-g365_type="club_players" placeholder="Enter Club Team Name" data-ls_no_add="true" autocomplete="off">
    </div>
    <div class="small-margin-bottom tiny-padding no-input-margin">
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
    
    <div class="player-field-set" data-player-id="{{field-set-id}}">
        <div class="small-margin-bottom tiny-padding no-input-margin hide" >
          <label for="{{field-set-id}}_bcert_resub">Resub</label>
          <input type="text" name="{{field-set-id}}[data][bcert_resub]" class="expanded" id="{{field-set-id}}_bcert_resub" placeholder="" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{bcert_resub}}" >
        </div>


        <div class="tiny-margin-bottom tiny-padding no-input-margin">
              <label for="{{field-set-id}}_bcert_img">Birth Certificate (800px x 800px min)</label>
              <label for="{{field-set-id}}_bcert_img">**One time resubmission only** </label>
              <label for="{{field-set-id}}_bcert_img">**JPEG & JPG ONLY** </label>
              <div class="crop_img medium-padding" data-g365_crop_settings="birthcert" data-g365_croppie_img_url="{{bcert_img_url}}">

                <div class="cropped_img"></div>
                <div class="crop_upload">
                  <div class="crop_upload_canvas_wrap hide">
                    <div class="crop_upload_canvas"></div>
                  </div>
                  <input type="hidden" class="croppie_img_data" name="{{field-set-id}}[data][bcert_img_data]" >
                  <input type="file" class="crop_uploader" value="Choose a file" accept="image/*" />
                </div>
                <a id="{{field-set-id}}_bcert_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide" onclick="newImage('{{field-set-id}}')" name="{{field-set-id}}_bcert_resub" >Remove Image</a>
              </div>
        </div>


        <div class="small-margin-bottom tiny-padding no-input-margin hide" >
          <label for="{{field-set-id}}_recard_resub">Resub</label>
          <input type="text" name="{{field-set-id}}[data][recard_resub]" class="expanded" id="{{field-set-id}}_recard_resub" placeholder="" pattern="^([A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)$" value="{{recard_resub}}" >
        </div>

        <div class="tiny-margin-bottom tiny-padding no-input-margin ">
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
            <a id="{{field-set-id}}_recard_img" class="button tiny-padding remove-croppie no-margin-bottom small-margin-top hide" onclick="handleRecardResub('{{field-set-id}}')" name="{{field-set-id}}_recard_resub">Remove Image</a>
          </div>
        </div>
    </div>

    
    
    <div class="text-right">
      <a class="button in-block g365-expand-collapse-fieldset no-margin-bottom" data-click-target="{{field-set-id}}">Minimize</a>
    </div>
  </div>
 
  <script>

    document.addEventListener("DOMContentLoaded", function() {
        // Assuming you have multiple players with a class "player-field-set"
        var playerFieldSets = document.querySelectorAll('.player-field-set');

        // Loop through each player's field set
        playerFieldSets.forEach(function (playerFieldSet) {
            var playerId = playerFieldSet.getAttribute('data-player-id'); // Example: pl_ed_87613 or pl_ed_69309

            // Call the functions for each player's ID
            imageResub(playerId);
            recardResubCheck(playerId);
        });

        
        console.log("hello here");
    });

    function imageResub(id) {
        
        
        var bcertResubValue = document.getElementById(id + "_bcert_resub").value;
        if (bcertResubValue === "true") {
            
            document.getElementById(id + "_bcert_img").style.display = "none";
        }
    }

    function newImage(id) {
        
        
        var bcertResubInput = document.getElementById(id + "_bcert_resub");
        if (bcertResubInput) {
            bcertResubInput.setAttribute('value', 'true');
        }
    }

    function recardResubCheck(id) {
        var recardResubValue = document.getElementById(id + "_recard_resub").value;
        if (recardResubValue === "true") {
            document.getElementById(id + "_recard_img").style.display = "none";
        }
    }

    function handleRecardResub(id) {
        
        
        var recardResubInput = document.getElementById(id + "_recard_resub");
        if (recardResubInput) {
            recardResubInput.setAttribute('value', 'true');
        }
    }

     
     
  </script>
  
</div>
EOD;
//Player Editor Init
$player_registration_init_sl = <<<EOD
<div id="g365_player_form_wrap" data-g365_form_load_min="true" class="account--player-editor">
  <h1 class="section_title">Edit Player</h1>
  <div class="form-holder small-padding">
    <form id="g365_player_form yyo" class="primary-form" name="g365_player_form" enctype="multipart/form-data" method="post" data-g365_type="pl_ed">
      <div id="g365_player_form_data"></div>
      <div id="g365_player_form_submit" class="g365_form_sub_block">
        <hr />
        <div id="g365_player_form_message" class="small-margin-top form_message hide"></div>
        <button class="site-button button g365-primary-submit expanded g365-primary-submit" type="submit" value="submit">Update Player Data</button>
      </div>
    </form>
  </div>
</div>
EOD;
//     <div class="g365_toggle open_element" data-group="pl_name">
//       <label for="player_names">Player Name <span class="req">*</span></label>
//       <input type="text" class="g365_livesearch_input expanded" data-g365_action="load_form" data-g365_form_template="form_template" data-g365_type="player_names" data-g365_form_dest="g365_player_form" placeholder="Enter Player Name" autocomplete="off" autofocus>
//     </div>
?>