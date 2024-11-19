<?php global $wp_query; $pl_id = url_param('pl'); $type = url_param('type'); $post_ev_id = $arg['dir_ev']; $authorized_user = get_current_user_id(); if(dcp_access(['ev_id'=>$post_ev_id, 'user_id'=>$authorized_user], 'is_ev_unlocked') == true): /*if-access*/ 
$default_url = get_site_url()."/wp-content/uploads/event-profiles/g365_profile_placeholder.gif"; $pl_data = g365_get_pl_data(['pl_id'=>$pl_id]); if(!empty($pl_data)): foreach($pl_data as $get_pl_data): $fav_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'player_id'=>$get_pl_data->id, 'user_id'=>$authorized_user], 'SELECT'); echo dcp_access(['user_id'=>$authorized_user, 'pl_id'=>$pl_id, 'ev_name'=>get_event($post_ev_id)[0]->ev_name, 'pl_name'=>$get_pl_data->name, 'pl_nickname'=>$get_pl_data->nickname, 'school'=>$get_pl_data->school, 'fav_data'=>$fav_data, 'pl_profile_img'=> $get_pl_data->profile_img, 'default_img'=> $default_url, 'redir_url'=>get_site_url().(explode('?', $_SERVER['REQUEST_URI'], 2)[0]), 'pl_grad_year'=>(empty($get_pl_data->grade_year) ? "" : $get_pl_data->grade_year), 'pl_position'=>(empty(g365_get_pl_data(['pst_id'=>$get_pl_data->position], 'position')[0]->abbr) ? "" : g365_get_pl_data(['pst_id'=>$get_pl_data->position], 'position')[0]->abbr), 'pl_height'=>(empty($get_pl_data->height_ft) ? "" : ($get_pl_data->height_ft."' ".$get_pl_data->height_in)), 'gpa'=>(empty($get_pl_data->gpa) ? "" : $get_pl_data->gpa), 'sat'=>(empty($get_pl_data->sat) ? "" : $get_pl_data->sat), 'act'=>(empty($get_pl_data->act) ? "" : $get_pl_data->act),
'pl_contact_info'=>(empty($get_pl_data->email && $get_pl_data->phone) ? "" : (empty($get_pl_data->city) ? "" : $get_pl_data->city).", ".(empty($get_pl_data->state) ? "-" : $get_pl_data->state)."<br/>".($get_pl_data->email."<br/>".$get_pl_data->phone))], 'is_pl_unlocked'); echo ajax_data_xfer(['class_name'=>'ls_pl'], 'ls_pl'); echo dcp_custom_js(['pl_id'=>$pl_id], 'ls_reveal'); endforeach; endif; echo ajax_data_xfer(['class_name'=>'fav_pl', 'url'=>true], 'add_fav');?>
<div class="grid-x">
  <div>
    <div class="small-12 medium-12 large-6 cts_btn_group">
      <a class="cts_btn cts_btn--filter " tabindex="0" href="<?php echo get_site_url(); ?>/account/dcp/home/">Back to main home page</a>
    </div>
  </div>
</div>
<div class="small-12 medium-12 large-12 small-padding-top dcp-event">
  <?php 
  switch($type){
    case '':
    case 'team': $dcp_ev_data = get_event($post_ev_id, 'dcp-team-ev')[0]; $ev_location = str_replace('|', ' | ', $dcp_ev_data->locations); 
  if (isset($_GET['lv'])) { $lv = $_GET['lv']; }
  // Function to filter the array
  function filter_division($array, $get_division) {
    $result = [];
    foreach ($array as $level => $teams) {
      foreach ($teams as $team) {
        if (isset($team['division']) && $team['division'] == $get_division) {
          $result[$level][] = $team;
        }
      }
    }
    return $result;
  }

  // Function to filter the array
function filterLv($array, $get_lv) {
  if(isset($array[$get_lv])){
      return [$array[$get_lv]];
  }
  return [];
}
  ?>
      <div id="dialong_div"></div>
      <div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
        <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo $dcp_ev_data->logo_img; ?>" alt="<?php echo $dcp_ev_data->ev_name; ?>"></div>
        <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo date("F j, Y",strtotime($dcp_ev_data->eventtime)); ?></h4></div>
        <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
      </div>
      <div class="medium-padding text-center">
        <?php echo g365_submenu_type(['ev_id'=>$post_ev_id, 'type'=>$type], 7);  ?>
      </div>
      <?php $group_club_teams = array(); $post_ev_id = $arg['dir_ev']; $club_data = get_club_pl(['ev_id'=>$post_ev_id]); $club_data = json_decode(json_encode($club_data), true); $club_info = array(); foreach($club_data as $key => $club_data_list){ $club_info[$club_data_list['division']][$key] = $club_data_list; }  $tm_lv = array();
      if(!empty($club_info)):/*if-main*/ ?>
        <?php foreach($club_info as $dex => $club_pl): ?>
          <div class="cts_btn_group"><a class="cts_btn" href="<?php echo get_site_url().'/account/dcp/teams/'.$post_ev_id.'/?type=team&lv='.$dex.' '?>" class="lv-link" id="lv-type" style="font-size:18px; color:#000; padding: 0 14px 0 14px; text-decoration: none; margin-bottom:4pm; "><?php echo g365_return_keys('g365_grade_key')[$dex]; ?></a></div>
        <?php endforeach; ?>
        <br>
        <br>
  
        <?php $filtered_lv = filterLv($club_info, $lv); foreach($filtered_lv as $dex => $club_pl): /*foreach-main*/ ?>
          <div class="small-12 medium-12 large-12">  
<!--             <ul class="accordion club-rosters xsmall-padding-bottom" data-accordion data-allow-all-closed="true">
              <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title" style="font-size:18px; padding:10px 0 10px 14px"><?php echo g365_return_keys('g365_grade_key')[$dex]; ?></a>
                <div class="accordion-content overflow-x" data-tab-content>
                  <div class="grid-container"> -->
                    <?php foreach($club_pl as $index => $club_lv_data){
        $tm_lv[$club_lv_data['level_of_play']][$index] = $club_lv_data; } 
        if($dcp_ev_data->ev_org === '7474'){
          $tm_lvs = order_array($tm_lv, g365_division(null, 'scibca'));
        }else{
          $tm_lvs = order_array($tm_lv, g365_division(null, 'dcp'));
        }
         $filteredTeams = filter_division($tm_lvs, $lv); ?>
                    <?php foreach($filteredTeams as $key => $tm_lv_data):/*foreach-1*/ ?>
                      <ul class="accordion club-rosters xsmall-padding-bottom" data-accordion data-allow-all-closed="true">
                        <li class="accordion-item" data-accordion-item>
                          <a href="#" class="accordion-title" style="font-size:18px; padding:10px 0 10px 14px"><?php echo g365_return_keys('g365_grade_key')[$lv] .' '. $key; ?></a>
                          <div class="accordion-content overflow-x" data-tab-content>
                            <div class="grid-container">
                              <?php foreach($tm_lv_data as $tm_club):/*foreach-2*/ //echo "<pre>"; print_r($tm_club); echo "</pre>"; ?>
                                <ul class="accordion club-rosters xsmall-padding-bottom" data-accordion data-allow-all-closed="true">
                                  <li class="accordion-item" data-accordion-item>
                                    <a href="#" class="accordion-title" style="font-size:16px; padding:10px 0 10px 14px"><?php echo $tm_club['org_name']; ?></a>
                                    <div class="accordion-content overflow-x" data-tab-content>
                                      <div class="grid-container">
                                        <?php 
//                                           $defautl_ros = g365_team_rosters($year = null, $tm_club['team_id'], $tm_club['org_id'], 1);
                                          $defautl_ros = g365_team_rosters($year = null, $tm_club['team_id'], $tm_club['org_id'], 5, ['event_id'=>$post_ev_id]);
                                          if(!empty($defautl_ros)):/*if-ros*/
                                          foreach($defautl_ros as $team_roster):/*foreach-ros*/ if(empty($team_roster->players)){ echo g365_message()['no_roster']; }else{
                                          $roster_players = array();
                                          $team_veri = 'verified-team'; 
                                          $team_roster->players = json_decode($team_roster->players);
                                          foreach($team_roster->players as $pl_id => $pl_data){
                                            if($pl_id !== '11000' && $pl_id !== '11001'){
                                            $pl_info = g365_player_db($pl_id);
                                              if(!empty($pl_info)){
                                                $position_abbr = g365_get_pl_data(['pst_id'=>$pl_info[0]->position], 'position');
                                                $is_fav = check_fav_icon(['user_id'=>$authorized_user, 'pl_id'=>$pl_id]); 
                                                $fav_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'player_id'=>$pl_id, 'user_id'=>$authorized_user], 'SELECT'); 
                                                $pl_profile_img = g365_player_img_dir($pl_info[0]->nickname, null, $pl_id, 'profile_img_only');
                                                if($is_fav === 'true'){$fav_icon='⭐️';}else{$fav_icon='<a onClick="dcp_tm_ros(this)" data-pl-id="'.$pl_id.'" data-url-link="'.get_site_url().'" data-ev-id="'.$post_ev_id.'" href="#" class="btn-flip" data-back="⭐️" data-front="✩"></a>';}
                                                $is_fav_icon = get_dcp_auth_data(['user_id'=>$authorized_user, 'ev_id'=>(empty($post_ev_id) ? "" : $post_ev_id), 'fav_icon'=>(empty($fav_icon) ? "" : $fav_icon), 'pl_id'=>(empty($pl_id) ? "" : $pl_id), 'enabled_access'=>true], 'fav-star');
                                                  $roster_players[] = '<tr><td>' . (empty($is_fav_icon['fav_icon']) ? "" : $is_fav_icon['fav_icon']) . '</td><td class="jersey_num">#' . $pl_data->j_num . '</td><td class="pl_name"><a href="' . get_site_url() . '/player/' . $pl_info[0]->nickname . '" target="_blank">' . $pl_info[0]->name . '</a></td><td>' . (empty($team_roster->coach_name) ? '' : $team_roster->coach_name) . '</td><td>' . ($tm_club['org_name']) . '</td><td>' . (empty($pl_info[0]->school) ? '-' : $pl_info[0]->school) . '</td><td>' . ($pl_info[0]->grad_year) . '</td><td>' . (empty($pl_info[0]->position) ? '-' : $position_abbr[0]->abbr) . '</td><td>'. (empty($pl_info[0]->height_ft) ? '' : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in)) . '</td><td>' . (empty($pl_info[0]->gpa) ? '-' : $pl_info[0]->gpa) . '</td><td>' . (empty($pl_info[0]->sat) ? '-' : $pl_info[0]->sat) . '</td><td>' . (empty($pl_info[0]->act) ? '-' : $pl_info[0]->act) . '</td><td>' . (empty($pl_info[0]->email && $pl_info[0]->phone) ? '' : ((empty($pl_info[0]->city) ? '-' : $pl_info[0]->city).', '.(empty($pl_info[0]->state) ? '-' : $pl_info[0]->state).'<br/>'.$pl_info[0]->email.'<br/>'.$pl_info[0]->phone)) . '</td></tr>';
//                                                   $is_rvl_enabled = get_dcp_auth_data(['user_id'=>$authorized_user, 'ev_id'=>$post_ev_id, 'fav_icon'=>$fav_icon, 'pl_id'=>$pl_id, 'full_name'=>$pl_info[0]->name, 'school'=>(empty($pl_info[0]->school) ? '-' : $pl_info[0]->school), 'pl_name'=>$pl_info[0]->name, 'pl_nickname'=>$pl_info[0]->nickname, 'fav_data'=>$fav_data, 'pl_img'=>$pl_profile_img, 'pl_grad_year'=>($pl_info[0]->grad_year), 'pl_position'=>(empty($pl_info[0]->position) ? '-' : $position_abbr[0]->abbr), 'pl_height'=>(empty($pl_info[0]->height_ft) ? '' : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in)), 'gpa'=>(empty($pl_info[0]->gpa) ? '-' : $pl_info[0]->gpa), 'sat'=>(empty($pl_info[0]->sat) ? '-' : $pl_info[0]->sat), 'act'=>(empty($pl_info[0]->act) ? '-' : $pl_info[0]->act), 'pl_contact_info'=>(empty($pl_info[0]->email && $pl_info[0]->phone) ? '' : ((empty($pl_info[0]->city) ? '-' : $pl_info[0]->city).', '.(empty($pl_info[0]->state) ? '-' : $pl_info[0]->state).'<br/>'.$pl_info[0]->email.'<br/>'.$pl_info[0]->phone)), 'enabled_access'=>true], 'fav-star'); echo (empty($is_rvl_enabled['enabled_reveal']) ? "" : $is_rvl_enabled['enabled_reveal']); 
                                              }
                                            }
                                          }
                                          echo '<table><thead><th>Recruit</th><th>Jersey</th><th>Player</th><th>Director/Coach</th><th>Club</th><th>School</th><th>Grade Year</th><th>Position</th><th>Height</th><th>GPA</th><th>SAT</th><th>ACT</th><th>Contact Info</th><tbody>';
                                          echo implode('', $roster_players);
                                          echo '</tbody></table>';
                                        } endforeach;/*endforeach-ros*/ echo dcp_custom_js(null,'dcp-fav-star'); else: echo "<div><p>".g365_message()['not_available']."</p></div>"; endif;/*endif-ros*/?>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                              <?php endforeach;/*endforeach-2*/  ?>
                            </div>
                          </div>
                        </li>
                      </ul>
                    <?php endforeach;/*endforeach-1*/ ?>
<!--                   </div>
                </div>
              </li>
            </ul> -->
      <?php endforeach;/*endforeach-main*/ else: echo "<div><p>".g365_message()['not_available']."</p></div>"; endif;/*endif-main*/?>
  <?php echo cts_dialog_js(null, 'dcp-tm-ros'); break; case 'stat': ?>
    <div class="grid-x">
      <h3>Stat Leaderboard</h3>
      <div class="small-12 medium-12 large-12 small-margin-top">
        <?php echo $player_id . ' '; echo $arg['dir_ev'];  ?>
        <?php g365_dir_render('digital-coach-packets', 'stats', $player_id, $arg = ['dir_ev'=>$arg['dir_ev'], 'enabled_access'=>true, 'dcp_nav'=>true]); ?>
      </div>
    </div>
  <?php break; 
    case 'team-standing': $ev_location = str_replace('|', ' | ', get_event($post_ev_id, 'dcp-team-ev')[0]->locations); ?>
    <div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
      <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo get_event($post_ev_id, 'dcp-team-ev')[0]->logo_img; ?>" alt="<?php echo get_event($post_ev_id)[0]->ev_name; ?>"></div>
      <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo date("F j, Y",strtotime(get_event($post_ev_id, 'dcp-team-ev')[0]->eventtime)); ?></h4></div>
      <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
    </div>
    <div class="medium-padding text-center">
      <?php echo g365_submenu_type(['ev_id'=>$post_ev_id, 'type'=>$type], 7);  ?>
    </div>
    <div class="grid-x small-padding-bottom">
      <h3>Team Standings</h3>
      <div class="small-12 medium-12 large-12 small-margin-top">
        <?php g365_dir_render('digital-coach-packets', 'team-event-standings', $player_id, $arg = ['ev_id'=>$post_ev_id, 'is_unlocked_dcp_ev'=>true]); ?>
      </div>
    </div>
  <?php break; 
    case 'player-directory': $ev_location = str_replace('|', ' | ', get_event($post_ev_id, 'dcp-team-ev')[0]->locations); //echo "<pre>"; print_r(gettype(get_fav_id())); echo "</pre>"; echo "<pre>"; print_r(get_fav_id()); echo "</pre>";  ?>
      <div class="all-tournament__details" style="flex-wrap: wrap;flex-direction: row;">
        <div style="flex: 0 1 100%;" class="text-center"><img class="width-50-200" src="<?php echo get_event($post_ev_id, 'dcp-team-ev')[0]->logo_img; ?>" alt="<?php echo get_event($post_ev_id)[0]->ev_name; ?>"></div>
        <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo date("F j, Y",strtotime(get_event($post_ev_id, 'dcp-team-ev')[0]->eventtime)); ?></h4></div>
        <div style="flex: 0 1 100%;" class="text-center"><h4><?php echo $ev_location; ?></h4></div>
      </div>
      <div class="medium-padding text-center">
        <?php echo g365_submenu_type(['ev_id'=>$post_ev_id, 'type'=>$type], 7);  ?>
      </div>
      <h3>Player Search</h3>
      <div class="relative small-12 medium-12 large-12 small-padding-bottom">
        <p>Search/Add players to your recruit list for easy access, and add your notes on them.</p>
        <span class="search-mag fi-magnifying-glass"></span>
        <input type="text" class='search-hero g365_livesearch_input' data-g365_type="dcp_players" placeholder="Enter Player Name" autocomplete="off" autofocus>
      </div>
  <?php break;
    case 'my-recruits': echo ('<div class="text-center medium-padding">'.g365_submenu_type(['ev_id'=>$post_ev_id, 'type'=>$type], 7).'</div>'); g365_dir_render('digital-coach-packets', 'favorites', $player_id, $arg = null);
      break;
    case 'player-event': $pl_id = url_param('pl'); $pl_data = g365_get_pl_data(['pl_id'=>$pl_id], 'g365-pl-data'); $position_abbr = g365_get_pl_data(['pst_id'=>$pl_data[0]->position], 'position'); $pl_img_url = get_site_url() . '/wp-content/uploads/player-profiles/';  ?>
<!-- class="over-screen" -->
  <div>
<!-- class="small" top: 142px;  -->
    <div class="reveal fav_box recruit-player" id="pl_<?php echo $pl_id; ?>" style="display: block"> 
        <div class="grid-x small-padding-bottom">
          <div class="small-6 medium-6 large-4 flex text-center">
            <div class="cell" data-alphabet="A">
              <img class="watchlist__player-img small-margin-bottom" loading="lazy" src="<?php echo (empty($pl_data[0]->profile_img) ? $default_url : $pl_img_url.$pl_data[0]->profile_img); ?>"><br><p><?php echo $pl_data[0]->name; ?></p>
            </div>
          </div>
          <div class="info-fav small-12 medium-12 large-6">
            <p>School: <?php echo $pl_data[0]->school; ?></p><p>Position: <?php echo $position_abbr->abbr; ?></p><p>Height: <?php echo (empty($pl_data[0]->height_ft) ? '' : ($pl_data[0]->height_ft."' ".$pl_data[0]->height_in)); ?></p><p>Grad Year: <?php echo $pl_data[0]->grad_year; ?></p><p>GPA: <?php echo $pl_data[0]->gpa; ?></p><p>SAT: <?php echo $pl_data[0]->sat; ?></p><p>ACT: <?php echo $pl_data[0]->act; ?></p><p>Contact: <?php echo (($pl_data[0]->city.', '.$pl_data[0]->state).'<br/>'.$pl_data[0]->email.'<br/>'.$pl_data[0]->phone); ?></p>
          </div>
        </div>
      <textarea style="text-transform: none;" class="secondary button text-left" id="note_<?php echo $pl_id; ?>" name="note_<?php echo $pl_id; ?>" rows="2" cols="50" placeholder="Leave a note for <?php echo $pl_data[0]->name; ?> "></textarea>
      <button onclick="fav_icon_animation(this)" class="fav_pl success button no-margin-bottom btn-close" data-pl-id="<?php echo $pl_data[0]->id; ?>" data-pl-name="<?php echo $pl_data[0]->name; ?>" data-pl-nickname="<?php echo $pl_data[0]->nickname; ?>" data-pl-img="<?php echo (empty($pl_data[0]->profile_img) ? $default_url : $pl_img_url.$pl_data[0]->profile_img); ?>" data-pl-grad-year="<?php echo $pl_data[0]->grad_year; ?>" data-pl-position="<?php echo $position_abbr->abbr; ?>" data-pl-height="<?php echo (empty($pl_data[0]->height_ft) ? '' : ($pl_data[0]->height_ft."' ".$pl_data[0]->height_in)); ?>" data-pl-gpa="<?php echo $pl_data[0]->gpa; ?>" data-pl-sat="<?php echo $pl_data[0]->sat; ?>" data-pl-act="<?php echo $pl_data[0]->act; ?>" data-pl-contact-info="<?php echo (($pl_data[0]->city.', '.$pl_data[0]->state).'<br/>'.$pl_data[0]->email.'<br/>'.$pl_data[0]->phone); ?>">Add to recruit list</button>
      <button class="secondary button btn-close">Cancel</button>
    </div>
  </div>   
  <script>  
//     function addToRecruitList(button) {
//       var playerData = {
//         post_type: 'add_fav',
//         id: $(button).data('pl_id'),
//         note: $(textarea).data('pl_note'),
//         name: $(button).data('pl_name'),
//         nickname: $(button).data('pl_nickname'),
//         img: $(button).data('pl_img'),
//         grad_year: $(button).data('pl_grad_year'),
//         position: $(button).data('pl_position'),
//         height: $(button).data('pl_height'),
//         gpa: $(button).data('pl_gpa'),
//         sat: $(button).data('pl_sat'),
//         contact_info: $(button).data('pl_contact_info')
//       };
      
//       $.ajax({
//         url: '/wp-content/plugins/g365-data-manager/inc/ajax-caller.php',
//         type: 'POST',
//         data: playerData,
//         success: function(response) {
//           alert('Player added to recruit list successfully.');
//         },
//         error: function(xhr, status, error) {
//           alert('An error occurred while adding the player to recruit list.');
//         }
//       });
//     }
    
    $('.btn-close').on('click', function(){
      isInPopup = $(this).closest('.ui-dialog-content')[0];
      !isInPopup && window.location.reload(); 
    });
  </script>
    <?php break; } ?>
</div>
<?php else: echo "<div class='text-center'><h4>".g365_message()['access_deny']."</h4></div>"; endif/*endif-access*/;