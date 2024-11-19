<?php 
  // cts: Club Team Standing
  // Start time
  // $startTime = microtime(true);
  global $wp_query;
  $key_level = (g365_return_keys('g365_grade_key'));
  $is_post_lv = !empty($wp_query->query_vars['lv_label']) ? $key_lv[$wp_query->query_vars['lv_label']] : ''; 
  $event_id = $_POST['event_id'];
  $select_year = $_POST['g365_year'];
  $select_group = $_POST['group_lv'];
  $select_lv_play = $_POST['lv_of_play'];

  if(!isset($select_year) && !isset($select_lv_play) && !isset($select_group)){
    if(empty($arg['season_year'])){ $select_year = wp_date('Y'); }else{ $select_year = $arg['season_year']; }
    $select_lv_play = '';
  }

  $lv_of_lv_pg = ""; //!empty($select_lv_play) ? "&lv=".$select_lv_play."" : "";
  $get_org_lists = slb_org_menu(null, null, 'standings');
  $brand_sel = isset($_POST['brand_select']) ? $_POST['brand_select'] : 'grassroots-365';
  $selected_org_id;
/*
    if (empty($select_group)) {
    //     echo 'im here'; 
        $select_group_data = spp_get_divisions_lvl($select_year, $brand_sel);
        $select_group = $select_group_data['team_levels_string']; 
    //   print_r($select_group);
      } else {
        $check_group_data = spp_get_divisions_lvl($select_year, $brand_sel);
        $select_group = $_POST['group_lv'];
    //     echo 'hell naw i here0 ' . $check_group_data['team_levels_string'] . ' // ' . $select_group . ' // <br>';
        if($check_group_data['team_levels_string'] === $select_group){
          $select_group = $_POST['group_lv'];
    //       echo 'hell naw i here1 ' . $select_group . ' // <br>';
        }else{
          if(preg_match('/^(\d+,)*\d+$/', $select_group) === 1){  
            $select_group = $check_group_data['team_levels_string'];
    //         echo 'hell naw i here2.1 ' . $select_group . ' // <br>';
          }else{
            $select_group = $_POST['group_lv'];
    //         echo 'hell naw i here2.2 ' . $select_group . ' // <br>';
          }
    //         echo 'hell naw i here2 ' . $select_group . ' // <br>';
        }
    //         echo 'hell naw i here ' . $select_group . ' // ' . $select_group . ' // <br>';
      }*/

?>

<h3>Select an Organization:</h3>

<div class="container grid-x grid-margin-x small-up-2 medium-up-3 large-up-4 text-center profile-feature medium-margin-top mobile-horizontal-nav-outer" id="organization-list">
  <div class="grid-x grid-margin-x small-up-3 medium-up-4 large-up-4 align-center text-center img-grid small-padding-sides" id="standingsMobileNav">
    <?php foreach($get_org_lists['org_list'] as $org_list): 
      if(empty($org_list->profile_img)){ $org_logo = 'g365_profile_placeholder-spp-logo.gif'; }else{ $org_logo = $org_list->profile_img; }
      $is_selected = ($brand_sel === $org_list->nickname) ? 'is-selected' : '';
      if($brand_sel === $org_list->nickname){ $selected_org_id = $org_list->id; }
    ?>
      <div class="cell relative small-margin-bottom stat-organization <?php echo $is_selected; ?>" data-id="<?php echo $org_list->id; ?>" data-nickname="<?php echo $org_list->nickname; ?>">
        <a >
          <img loading="lazy" src="/wp-content/uploads/org-logos/<?php echo $org_logo; ?>" alt="<?php echo $org_list->nickname; ?>"><br><?php echo $org_list->name; ?></a>
      </div>                                
    <?php endforeach; ?>
  </div>
</div>

<div id="dialong_div"></div>
<?php
$placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png';
$select_group = "17,16,15,14,13,12,11,10,9,8,47,46,45,44,43,42,41,40"; // if we override it here, why bother getting it on top.
$org_logo = '/wp-content/uploads/org-logos/';
if(empty($wp_query->query_vars['lv_type']) || $wp_query->query_vars['lv_type'] == 'all-levels'): /*if-main*/

// End time
// $endTime = microtime(true);
// $executionTime = $endTime - $startTime;
// echo "Execution time of the script: " . $executionTime . " seconds\n";

?>

<section class="flex flex-wrap mv1 medium-padding-bottom">
   <?php 
  // TODO Performance : this function is really slow.
  echo g365_submenu_type(['post_gp_lv'=>$select_group, 'post_year'=>$select_year, 'lv_play'=>$select_lv_play, 'brand_sel'=>$brand_sel], 20); //original 16  ?>
</section>

<?php 

//    Small test of a file cache... but not needed better look at the function above
//    $cacheFile = __DIR__ .'g365_club_team_stat_cache_file.txt';
//    $cacheExpiration = 30;

//   // Step 1: Check if the cache file exists and is not expired
//   if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheExpiration) {
//       // Step 2: Read from cache
//       $cachedData = file_get_contents($cacheFile);
//       $cachedObject = unserialize($cachedData);
//   } else {
//       file_put_contents($cacheFile, serialize($dataToCache));
//       print_r($dataToCache);
//   }


//  if($cachedObject){
//    $club_team_datas = $cachedObject;
//    echo "from cache";
//  } else {
   $club_team_datas = g365_club_team_stat($event_id = null, $team_id = null, false, $opponent_id = null, $select_year, 14, ['select_brand' => $brand_sel, 'select_year' => $select_year, 'win_loss_percent_cutoff' => '0.5', 'max_results_per_division' => '10', 'show_girls' => 'true', 'division' => 'All', $select_group, 'level_of_play' => 'All']);
//   echo "not from cache";
//    file_put_contents($cacheFile, serialize($club_team_datas));
//  }

  //echo print_r(json_encode(array_slice($club_team_datas,0,1)));



  //print_r(json_encode($_POST));
  $filtered_club_team_data = array();
  $seen_levels = array();

  // Prepare some data for H5 with lvl of play.
  $filtered_club_team_data = [];

  // Step 1: Group elements by 'division'
  foreach ($club_team_datas as $club_team_data) {
       $division = $club_team_data->division;
       if (!isset($filtered_club_team_data[$division])) $filtered_club_team_data[$division] = [];
       $filtered_club_team_data[$division][] = $club_team_data;
  }

  // Top level loops creates each table.
  $top_level_tr = club_team_tb_form();
  foreach($filtered_club_team_data as $level_index => $club_team_data):  ?> 
    <h5><?php echo ($key_level[$level_index]/*.' '.$_POST['lv_of_play']*/);  ?></h5>
    <table class="cell cts_tb small-padding-bottom">
      <!-- Create the Thead, th elements and Tbody -->
      <?php echo $top_level_tr; ?>
      <!-- Second level loop creates each Row inside the table. -->
      <?php foreach($club_team_data as $key => $club_team_data_list): ?>
        <tr>
          <td>                
            <div class="flex items-center">
              <!-- Clicking button here will look if element with id is present, if so just remove it. Otherwise, load it. --> 
              <span class="vr_btn small-margin-right" <?php $club_team_data_list->team_id ?> id="<?php echo $club_team_data_list->team_id ?>" onClick="view_result(event, '<?php echo $club_team_data_list->team_id; ?>', '<?php echo $club_team_data_list->team_name; ?>', '<?php echo $selected_org_id; ?>', '<?php echo $club_team_data_list->org_id; ?>', '<?php echo htmlspecialchars(json_encode($club_team_data_list, true), ENT_QUOTES, 'UTF-8'); ?>')">Box Scores</span>
              <span class="small-margin-right">
                <img style="height:25px;width:35px;" alt="<?php echo $club_team_data_list->full_team_name; ?>" title="<?php echo ''.$club_team_data_list->full_team_name; ?>" src="<?php echo (!empty($club_team_data_list->org_logo) ? $club_team_data_list->org_logo != "NULL" ? $org_logo.$club_team_data_list->org_logo : $placeholder_img : $placeholder_img); ?>">
              </span>
              <span><?php echo str_replace('.', '', $club_team_data_list->full_team_name); ?></span>
            </div>
          </td>
          <td><?php echo !empty($club_team_data_list->total_wins) ? round($club_team_data_list->total_wins, 2) : '0'; ?></td>
          <td><?php echo !empty($club_team_data_list->total_losses) ? round($club_team_data_list->total_losses, 2) : '0'; ?></td>
          <td><?php echo !empty($club_team_data_list->win_percentage) ? round((float)(number_format($club_team_data_list->win_percentage, 3)) * 100 ) . '%' : '0%'; ?></td>
          <td><?php echo !empty($club_team_data_list->ppg) ? number_format(round($club_team_data_list->ppg, 1), 1) : '0'; ?></td>
          <td><?php echo !empty($club_team_data_list->opp_ppg) ? number_format(round($club_team_data_list->opp_ppg, 1), 1) : '0'; ?></td>
        </tr>
      <?php endforeach; ?>
      <!-- HERE WILL GO THE AJAX loaded content from Case 15. --> 
      <!--<div id="<?php // echo $club_team_data_list->team_id; ?>-result_box">inject ajax html here.</div>-->
      <tr class="text-center slb_more_list" id="<?php echo $index ?>" style="font-weight:bold;cursor:pointer;">
        <td class="buttonization">
          <div>
            <a style="color:#ffffff" href="<?php echo get_site_url(); ?>/club-team-standing/<?php echo empty($_POST['group_lv']) ? 'youth-boys' :'youth-boys'; ?>/<?php echo $brand_sel?>/<?php $lv_url = strtolower(str_replace(array(" / ","/"," "), array("-","-","-"), $key_level[$level_index])); echo $lv_url ?>/<?php echo $level_index?>/?y=<?php echo $select_year. $lv_of_lv_pg ?>&key_level=<?php echo urlencode($key_level[$level_index]); ?>"><span>View Complete Leaders</span></a>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
    <p>
      Don't see your team? <a href="<?php echo get_site_url(); ?>/club/" style=" text-decoration: underline; padding: 4px 8px; border-radius: 5px; transition: background-color 0.3s;" >Search here</a>
    </p>
<?php endforeach;
else: g365_dir_render('club-team-standing','by-level-standing', '', $arg = array($select_year, $key_level, $org_id, $org_logo, $placeholder_img, $level_index)); endif; echo (cts_dialog_js()); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const orgLogos = document.querySelectorAll('.stat-organization');
  const brandSelect = document.getElementById('brand_select');
  const groupLvSelect = document.getElementById('group_lv');

  orgLogos.forEach(logo => {
    logo.addEventListener('click', function(event) {
      event.preventDefault();
      const brandNickname = this.getAttribute('data-nickname');

      let isValueSet = false;
      Array.from(brandSelect.options).forEach(option => {
        if (option.value === brandNickname) {
          brandSelect.value = option.value;
          isValueSet = true;
        }
      });

      if (isValueSet) {
        document.getElementById('group_lv').selectedIndex = 0;
        document.getElementById('dcp-form').submit();
      } else {
        console.error("No matching option found for nickname:", brandNickname);
      }
    });
  });

  document.getElementById('year').addEventListener('change', function() {
    document.getElementById('lv_of_play').selectedIndex = 0;
    document.getElementById('group_lv').selectedIndex = 0;
    document.getElementById('dcp-form').submit();
  });
  
  function view_result(event, team_id, team_name, selected_org_id, team_org_id, club_team_data_list){
           // todo check if box present
           var box = $(`#${team_id}-result_box`);
           var button = event.target;
          
           // box is present and not empty simply slide it up remove it.
           if(box.length > 0) return $(box).slideUp().remove();

           // Step 1: Parse the string using URLSearchParams
           var params = new URLSearchParams($("#dcp-form:first").serialize());

           // Step 2: Convert the parsed params into a plain object
           var obj = {team_id : team_id, team_name : team_name, team_org_id : team_org_id, selected_org_id : selected_org_id, club_team_data_list : club_team_data_list};
           params.forEach((value, key) => {
              obj[key] = value;
           });

           $.ajax({
              type: 'POST',
              url: window.location.origin + '/wp-content/plugins/g365-data-manager/inc/club-team-standing/box-score-standing.php',
              data: obj,
              success: function(response) {
                $(button).closest('tr').after(response);
                $(button).closest('tr').next().hide().slideDown();
              },
              error: function(xhr, status, error) {
                  console.error("AJAX Error: ", xhr, status, error);
              }
           });
        }
  window.view_result = view_result;
});
</script>