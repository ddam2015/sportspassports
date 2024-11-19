<?php if(current_user_can('administrator') || current_user_can('college_coach')): global $wp_query; $pl_id = url_param('pl'); $post_ev_id = url_param('ev'); $direct_url = $_SERVER['REQUEST_URI']; $direct_url = explode('/', $direct_url); $dir_ev = $direct_url[count($direct_url)-2]; ?>
<div>
  <ul class="pl_profile_ul pl_profile_ul--player  pl_profile_ul--dcp small-up-5 medium-up-5 text-center">
    <li class="tabs-title cell<?php echo ( empty($wp_query->query_vars['dcp']) || strtolower($wp_query->query_vars['dcp']) === 'home' || strtolower($wp_query->query_vars['dcp']) === 'teams/'.$dir_ev ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/account/dcp/home/" class="profile-title profile__nav--item block"<?php echo ( empty($wp_query->query_vars['dcp']) || strtolower($wp_query->query_vars['dcp']) === 'home' || strtolower($wp_query->query_vars['dcp']) === 'teams/'.$dir_ev ) ? ' aria-selected="true"': ''; ?>>Home</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'teams' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/account/dcp/teams/" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'teams' ) ? ' aria-selected="true"': ''; ?>>Teams</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'stats' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/account/dcp/stats/" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'stats' ) ? ' aria-selected="true"': ''; ?>>Stats</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'team-standings' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/account/dcp/team-standings/" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'team-standings' ) ? ' aria-selected="true"': ''; ?>>Standings</a>
    </li>
    <li class="tabs-title cell<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'recruits' ) ? ' is-active': ''; ?>">
      <a href="<?php echo get_site_url(); ?>/account/dcp/favorites/" class="profile-title profile__nav--item block"<?php echo ( strtolower($wp_query->query_vars['dcp']) === 'favorites' ) ? ' aria-selected="true"': ''; ?>>Recruits</a>
    </li>
  </ul>
</div>
  <?php switch($wp_query->query_vars['dcp']){ case '': case 'home': $ev_acts = g365_get_event(['authorized_user'=>get_current_user_id()], 'acts'); $ev_acts = json_decode(json_encode($ev_acts), true); ?>
  <div class="medium-padding">
    <h1>Digital Coach Packets</h1>
    <p>Welcome to the <span style="font-weight:bolder;text-decoration:underline">Digital Coaching Packets Dashboard.</span> Here you will be able to <a href="<?php echo get_site_url()?>/account/dcp/teams/">view teams</a>, players and  <a href="<?php echo get_site_url(); ?>/account/dcp/stats/">stats</a> and view <a href="#myRecruits">My Recruits</a> from The Stage events.</p> 
    <p>The Recruits List is your player shortlist. Add outstanding players to your Recruits, and add notes for each player.</p><br/>
    <div class="grid-y">
      <div class="small-12 medium-6 large-6 h_ev_box-container">
        <div class="h_ev_box small-12 medium-12 large-12 medium-padding">
          <h3>Events</h3>
          <p>Acts that you have purchased accessed to will be shown in full color below.</p>
          <p>Each unlocked Act will give you access to that event's team rosters, stat leaderboard, and team standings. You will also be able to add players to your Recruits List.</p>
          <div class="grid-x small-up-2 medium-up-4 large-up-4 text-center">
            <?php 
//                   print_r($ev_acts);
                  $events = $ev_acts;  // Assuming $ev_acts is your original events array
                  $sortedEvents = sortEventsFromToday($events);
//                   print_r($sortedEvents);
                  foreach($sortedEvents as $ev_act): echo dcp_tb(['lock_status'=>$ev_act['unlocked'], 'ev_nickname'=>$ev_act['nickname'], 'ev_link'=>$ev_act['link'], 'img_logo'=>(empty($ev_act['img_logo']) ? "" : $ev_act['img_logo']), 'ev_name'=>$ev_act['name'], 'ev_date'=>$ev_act['dates'], 'logo_img'=>$ev_act['logo_img'], 'ev_id'=>$ev_act['id'], 'ev_type'=>$ev_act['org']]); ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="h_fav_box small-12 medium-6 large-6 medium-padding">
        <h3 id="myRecruits">My Recruits</h3>
        <?php $player_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'user_id'=>get_current_user_id(), 'limit'=>'3'], 'SELECT'); if(!empty($player_data)): foreach($player_data as $pl_data): $pl_id = $pl_data['player_id']; $pl_note = json_decode($pl_data['notes'], true); $pl_data_field = json_decode($pl_data['pl_data'], true); $pl_info = g365_get_pl_data(['pl_id'=>$pl_id]); ?>
          <div class="grid-x home_fav_box">
            <div class="small-12 medium-6 large-6">
              <div class="cell" data-alphabet="A">
                <a class="emphasis" href="<?php echo get_site_url(); ?>/player/<?php echo $pl_data_field['pl_nickname']; ?>" target="_blank">
                    <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="<?php echo $pl_data_field['img_link']; ?>" alt="Player headshot for <?php echo $pl_data_field['pl_name']; ?>" src="<?php echo $pl_data_field['img_link']; ?>"><br>
                    <p class="text-center"><?php echo $pl_data_field['pl_name']; ?></p>
                </a>
              </div>
            </div>
            <div class="info-fav small-12 medium-6 large-6">
              <?php $position_abbr = g365_get_pl_data(['pst_id'=>$pl_info[0]->position], 'position'); echo cdp_fav_pl_info(['pl_school'=>$pl_info[0]->school,'grad_year'=>$pl_data_field['grad_year'],'position'=>(empty($pl_info[0]->position) ? "" : $position_abbr[0]->abbr),'height'=>empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in),'gpa'=>$pl_info[0]->gpa,'sat'=>$pl_info[0]->sat,'act'=>$pl_info[0]->act,'contact_info'=>empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : ($pl_info[0]->email."<br/>".$pl_info[0]->phone)], 'pl_fav'); ?>
            </div>
            <div class="small-12 medium-12 large-12 fav_note"><h6>Notes: <?php echo $pl_note['notes']; ?></h6></div>
          </div>
        <?php endforeach; ?>
          <div class="link_view_full_list">
            <section class="pretty-buttons small-padding-top">
              <div class="grid-x pretty-container">
                <div class="small-12 large-12 small-padding-bottom">
                  <a href="<?php echo get_site_url() ?>/account/dcp/favorites/" class="pretty-btn pretty-btn-1">View/Edit full list
                    <svg><rect x="0" y="0" fill="none" width="100%" height="100%"/></svg>
                  </a>
                </div>
              </div>
            </section>
          </div>
        <?php else: echo ("<p>No player is added to the recruit list</p>"); endif; ?>
      </div>
    </div>
  </div>
<?php echo dcp_custom_js(['delay'=>500], 'dcp-pg-reload');
    break; 
  case 'teams/'.$dir_ev: g365_dir_render('digital-coach-packets', 'team-event', $player_id, $arg = ['post_pl_id'=>$pl_id, 'post_ev_id'=>$post_ev_id, 'dir_ev'=>$dir_ev]);
    break;
  case 'teams': g365_dir_render('digital-coach-packets', 'teams', $player_id, $arg = null); 
    break;
  case 'stats': g365_dir_render('digital-coach-packets', 'stats', $player_id, $arg = null); 
    break;
  case 'team-standings': g365_dir_render('digital-coach-packets', 'team-standings', $player_id, $arg = null); 
    break;
  case 'favorites': g365_dir_render('digital-coach-packets', 'favorites', $player_id, $arg = null); 
    break;
  case 'overview': g365_dir_render('digital-coach-packets', 'overview', $player_id, $arg = null);
    break;
  case 'ajax-caller': g365_dir_render('', 'ajax-caller', $player_id, $arg = null);
    break;
} else: echo ('<h4 class="text-center">'.g365_message()['access_deny'].'</h4>'); endif; ?>