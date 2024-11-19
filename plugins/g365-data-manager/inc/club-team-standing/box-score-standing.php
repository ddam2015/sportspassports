<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wp_query;

$key_level = (g365_return_keys('g365_grade_key'));
$is_post_lv = !empty($wp_query->query_vars['lv_label']) ? $key_lv[$wp_query->query_vars['lv_label']] : ''; 
$event_id = $_POST['event_id'];
$select_year = $_POST['g365_year'];
$select_group = $_POST['group_lv'];
$select_lv_play = $_POST['lv_of_play'];
$brand_sel = $_POST['brand_select'];
$team_id = $_POST['team_id'];
$team_name = $_POST['team_name'];
$team_org_id = $_POST['team_org_id'];
$selected_org_id = $_POST['selected_org_id'];
$club_team_data_list = json_decode(stripslashes($_POST['club_team_data_list']));

$args = ['select_brand' => $brand_sel, 'select_year' => $select_year, 'win_loss_percent_cutoff' => '0.5', 'max_results_per_division' => '10', 'show_girls' => 'true', 'division' => 'All', $select_group, 'level_of_play' => 'All', 'team_org' => $team_name, 'team_id' => $team_id, 'team_org_id' => $team_org_id, 'selected_org_id' => $selected_org_id, 'club_team_data_list' => $club_team_data_list];

// POST request to get the "Box Score" content.
g365_club_team_stat($event_id, $team_id, false, $opponent_id = null, $select_year, 15, $args);
?>