<?php 
//obtain the brand we are looking at
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathSegments = explode('/', trim($urlPath, '/'));
$placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png';
$select_group = "17,16,15,14,13,12,11,10,9,8,47,46,45,44,43,42,41,40"; // if we override it here, why bother getting it on top.
$org_logo = '/wp-content/uploads/org-logos/';

global $wp_query; $key_lv = $arg[1]; 
$key_level_value = $pathSegments[4];
if(!empty($wp_query->query_vars['lv_label'])){ $is_post_lv = $key_lv[$wp_query->query_vars['lv_label']]; }else{ $is_post_lv = ''; }
$select_year = $_POST['g365_year']; $select_lv_play = $_POST['lv_of_play']; $select_group = $_POST['group_lv']; $brand_sel = $_POST['brand_select']; $key_level_value = $_GET['key_level']; 
if(!isset($select_year) && !isset($select_lv_play) && !isset($select_group)){
  if(empty($is_post_lv)){ $select_group = $key_level_value; }else{ $select_group = $is_post_lv; } 
  if(empty($arg[0])){  $select_year = wp_date('Y'); }else{ $select_year = $arg[0];} 
  if(empty(url_param('lv'))){  $select_lv_play = ''; }else{ $select_lv_play = url_param('lv'); } 
}

if(empty($brand_sel)){
$brand_sel = $pathSegments[2];
}

$get_org_lists = slb_org_menu(null, null, 'standings');
foreach($get_org_lists['org_list'] as $org_list):
  if($brand_sel === $org_list->nickname){ $selected_org_id = $org_list->id; }
endforeach;

global $wp_query; $key_level = (g365_return_keys('g365_grade_key'));
// OLD
// $test = g365_team_standing(['post_year'=>$select_year OK, 'post_ros_dvs'=>$select_lv_play OK, 'post_gp_lv'=>$select_group, 'is_year'=>$is_year, 'is_dcp_only'=>false, 'is_gp_lv'=>$is_gp_lv, 'brand_sel'=>$brand_sel]);

// Taken from team-standing
 $club_team_datas = g365_club_team_stat(
   $event_id = null,
   $team_id = null,
   false,
   $opponent_id = null,
   $select_year,
   14,
   [
     'select_brand' => $brand_sel,
     'select_year' => $select_year,
     'win_loss_percent_cutoff' => '0.5',
     'max_results_per_division' => '10',
     'show_girls' => 'true',
     'division' => $key_level_value,//'All',
     $select_group,
     'level_of_play' => 'All' // $level_query (doesnt work either...) // 'All' //$key_level_value (doesnt work) // $key_level (doesnt work)
   ]);

// echo "<pre>"; print_r($club_team_datas); echo "</pre>";

$filtered_club_team_data = array();
foreach($club_team_datas as $index => $club_team_data){
  $filtered_club_team_data[''.$level_query][] = $club_team_data;// array_slice($club_team_data, 0, 50);
}

?>
<div class="text-center slb_more_list" id="<?php //echo $index ?>" style="font-weight:bold;cursor:pointer;">
        <div class="buttonization" style="width: 33%;" >
          <div>
            <a style="color:#ffffff" href="<?php echo get_site_url(); ?>/club-team-standing/"><span>Return to main page</span></a>
<!--             <br> -->
          </div>
        </div>
</div>
<br>
<section class="grid-x flex flex-wrap mv1 medium-padding-bottom">
  <?php 

//   echo ' select group: ' . $select_group . ' select year: ' . $select_year . ' select play: ' . $select_lv_play . ' brand: ' . $brand_sel; //this is what we need to push to get the info.
  echo g365_submenu_type(['post_gp_lv'=>$select_group, 'post_year'=>$select_year, 'lv_play'=>$select_lv_play, 'brand_sel'=>$brand_sel], 17); ?>
</section>
<?php  ?>
<?php if(!empty($filtered_club_team_data)): /*main*/ foreach($filtered_club_team_data as $level_index => $club_team_data): /*foreach-main*/ ?>
    <h5><?php echo (/*$key_level[$level_index]*/$key_level_value.' '.$select_lv_play); ?></h5>
    <table class="cell cts_tb small-padding-bottom">
      <?php echo club_team_tb_form(); ?>
      <!-- Create the Thead, th elements and Tbody -->
      <?php echo $top_level_tr; /* print_r($club_team_data);*/?>
      <!-- Second level loop creates each Row inside the table. -->
      <?php foreach($club_team_data as $key => $club_team_data_list): unset($club_team_data_list->standing); ?>
        <tr>
          <td>    
            <div class="flex items-center">
              <!-- Clicking button here will look if element with id is present, if so just remove it. Otherwise, load it. --> 
              <span class="vr_btn small-margin-right" id="<?php echo $club_team_data_list->team_id ?>" onClick="view_result(event, '<?php echo $club_team_data_list->team_id; ?>', '<?php echo $club_team_data_list->full_team_name; ?>', '<?php echo $selected_org_id; ?>', '<?php echo $club_team_data_list->org_id; ?>', '<?php echo htmlspecialchars(json_encode($club_team_data_list, true), ENT_QUOTES, 'UTF-8'); ?>')">Box Scores</span>
              <span class="small-margin-right">
                <img style="height:25px;width:35px;" alt="<?php echo $club_team_data_list->full_team_name; ?>" title="<?php echo ''.$club_team_data_list->full_team_name; ?>" src="<?php echo ((!empty($club_team_data_list->org_logo) ? ($club_team_data_list->org_logo != "NULL" ? $org_logo.$club_team_data_list->org_logo : $placeholder_img) : $placeholder_img)); ?>">
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
      <tr><!-- for :last-of-type selector bg color --></tr>
      <!-- HERE WILL GO THE AJAX loaded content from Case 15. --> 
      </tbody>
    </table>
<?php endforeach;/*endforeach-main*/ else: echo ('<div class="stat_leaderboard grid-x small-padding-top"><h3 class="text-center small-padding small-12 medium-12 large-12">'.g365_message()['unavailable_opts'].'</h3></div>'); endif; /*main*/  ?>

<!-- <script>
// document.addEventListener('DOMContentLoaded', function() {
//   const orgLogos = document.querySelectorAll('.stat-organization');
//   const brandSelect = document.getElementById('brand_select');

//   orgLogos.forEach(logo => {
//     logo.addEventListener('click', function(event) {
//       event.preventDefault();  // Prevent default action to stop navigation or unintended form submission

//       const brandNickname = this.getAttribute('data-nickname');
//       console.log("Clicked brand:", brandNickname); // Debugging output

//       let isValueSet = false;
//       Array.from(brandSelect.options).forEach(option => {
//         if (option.value === brandNickname) {
//           console.log("Matching option found:", option.value); // Debugging output
//           brandSelect.value = option.value;
//           isValueSet = true;
//         }
//       });

//       if (isValueSet) {
//         console.log("Form will be submitted now."); // Debugging output
//         document.getElementById('dcp-form').submit(); // Only submit if a match was found
//       } else {
//         console.error("No matching option found for nickname:", brandNickname);
//         // Optionally, you could inform the user via a UI element that no data is available for the selected brand.
//       }
//     });
//   });
// });
</script> -->




<!-- NEW CODE BELOW -->
<?php 
//   $key_level = (g365_return_keys('g365_grade_key'));
//   $is_post_lv = !empty($wp_query->query_vars['lv_label']) ? $key_lv[$wp_query->query_vars['lv_label']] : ''; 
//   $event_id = $_POST['event_id'];
//   $select_year = $_POST['g365_year'];
//   $select_group = $_POST['group_lv'];
//   $select_lv_play = $_POST['lv_of_play'];

//   if(!isset($select_year) && !isset($select_lv_play) && !isset($select_group)){
//     if(empty($arg['season_year'])){ $select_year = wp_date('Y'); }else{ $select_year = $arg['season_year']; }
//     $select_lv_play = '';
//   }

//   $lv_of_lv_pg = ""; //!empty($select_lv_play) ? "&lv=".$select_lv_play."" : "";
//   $get_org_lists = slb_org_menu(null, null, 'standings');
//   $brand_sel = isset($_POST['brand_select']) ? $_POST['brand_select'] : 'grassroots-365';
//   $selected_org_id;
?>

<div id="dialong_div"></div>
<?php
// $placeholder_img = '/wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png';
// $select_group = "17,16,15,14,13,12,11,10,9,8,47,46,45,44,43,42,41,40"; // if we override it here, why bother getting it on top.
// $org_logo = '/wp-content/uploads/org-logos/';
// if(empty($wp_query->query_vars['lv_type']) || $wp_query->query_vars['lv_type'] == 'all-levels'): /*if-main*/
?>

<!-- <section class="flex flex-wrap mv1 medium-padding-bottom"> -->
   <?php 
  // TODO Performance : this function is really slow.
 // echo g365_submenu_type(['post_gp_lv'=>$select_group, 'post_year'=>$select_year, 'lv_play'=>$select_lv_play, 'brand_sel'=>$brand_sel], 20); //original 16  ?>
<!-- </section> -->
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
