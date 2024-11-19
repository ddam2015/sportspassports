<?php
  $spp_slb_data = 
  <<<EOD
    <div id="spp-slb-container" class="hide"></div>
  EOD;
  $logo_container = <<<EOD
    <div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
      <div style="flex: 0 1 100%;" class="text-center" id="spp_event_log"><img class="width-50-200" id="spp_org_logo_img" src="{{logo_img}}" alt="{{logo_alt}}"></div>
      <div style="flex: 0 1 100%;" class="text-center" id="spp_event_date"><h4>{{event_date}}</h4></div>
      <div style="flex: 0 1 100%;" class="text-center" id="spp_event_location">{{locations}}</div>
  </div>
  EOD;
  $slb_filter_menu = 
  <<<EOD
    <div id="spp_slb_menu">
      <form method="post" action="" id="statleader-form" class="grid-x">
        <div class="year_list small-12 small-padding-right">
          <select name="stat_year" id="spp_stat_year" style="border-radius: 20px">
          </select>
        </div>
        <div class="dv_list small-12 small-padding-right">
          <select name="roster_level" id="roster_level" style="border-radius: 20px"> 
            <option value="">All Divisions</option>        
          </select>
        </div>
        <div class="lv_play small-12 small-padding-right">
          <select name="roster_dvs" id="roster_dvs" style="border-radius: 20px"> 
            <option value="">All Levels of Play</option>
          </select>
        </div>
        <div class="small-12 small-padding-right">
          <select name="stat_catagory" id="stat_catagory" style="border-radius: 20px"> 
            {{stat_options}}
          </select>
        </div>
        <div class="small-12 small-padding-right">
          <div>
            <div id="spp-slb">
            </div>
            <div class="event-selector small-12" >
              <select name="ev_val" id="event_list" style="border-radius: 20px">
              </select>
            </div>
          </div>
        </div>
        <div class="small-12 small-padding-right">
          <input type="button" id="slb_submit_btn" onClick="get_slb()" value="Filter" class="spotlight__card--heading small-12 medium-12 large-3">
        </div>
      </form>
    </div>
  EOD;
  $player_table = 
  <<<EOD
    <div class="grid-container stat_leaderboard grid-x small-padding-top">
      <div class="spp_slb_content small-12 medium-12 large-12">
        <div class="responsive-table">
          <table class="stat-table">
            <thead>
              <tr>
                <th>PLAYER</th>
                <th style="text-align:left">LEVEL</th>
                <th style="text-align:left">DIVISION</th>
                <th><span>{{stat_abbr}}</span></th>
              </tr>
            </thead>
           <tbody class="slb_player_table">
           </tbody>
          </table>
        </div>
      </div>
    </div>
  EOD;
  $spp_request_url = 
  <<<EOD
    <div id="spp_request_url" class="hide">{{admin_keys}}</div>
  EOD;
  $spp_slb_filter_url = 
  <<<EOD
    <div id="spp_slb_filter_url" class="hide">{{slb_filter_url}}</div>
  EOD;
  $spp_placeholder = 
  <<<EOD
    <div id="spp_placeholder">{{spp_placeholder}}</div>
  EOD;
  $grid_container = 
  <<<EOD
    <div class="grid-container">
  EOD;
  $close_div = 
  <<<EOD
    </div>
  EOD;
  $custom_fields = 
  <<<EOD
    <div class="stat-header__wrap large-margin-bottom">
      <img src="{{default_banner}}" class="stat-header__img" alt="stat-header image">
      <div class="stat-header__info">
        <h1 class="stat-header__heading">{{slb-season-year}}</h1>
      </div>
    </div>
  EOD;
  $slb_forms = $grid_container . $spp_placeholder . $spp_slb_data . $slb_filter_menu . $logo_container . $player_table . $spp_request_url . $close_div;
  $slb_by_org_forms = $grid_container . $spp_placeholder . $spp_slb_data . $slb_filter_menu . $logo_container . $player_table . $spp_request_url . $close_div;
?>