<?php

$html = '';

//get player_name _keep and player_id_keep if they exist
$player_name_keep = filter_input(INPUT_GET, 'player_name_keep', FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
$player_id_keep = filter_input(INPUT_GET, 'player_id_keep', FILTER_SANITIZE_NUMBER_INT) ?: '';

// Retrieve the current action type, whether we're selecting a player to keep or merge
$action_type = filter_input(INPUT_GET, 'action_type', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'keep';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['nickname'])){ $nickname = htmlspecialchars($row['nickname']); }else{ $nickname = ''; }
    if(!empty($row['id'])){ $id = $row['id']; }else{ $id = ''; }
    
    $location_info = '';
    if(!empty($city) || !empty($state)) {
      $location_info = ' <small>(';
      if(!empty($city)) $location_info .= $city;
      if(!empty($city) && !empty($state)) $location_info .= ', ';
      if(!empty($state)) $location_info .= $state;
      $location_info .= ')</small>';
    }
    
    //depending on whether we're selecting "keep" or "merge", create the appropriate href
    if($action_type === 'keep') {
      $html .= '<tr data-name="' . $name . '" data-href="/wp-admin/admin.php?page=admin_data_merge&action_type=keep&player_name_keep=' . $name . '&player_id_keep='. $id . '"><td><div class="button g365-button expanded no-margin-bottom">' . $name . $location_info . " - " . $id . '</div></td></tr>';
    } elseif($action_type === 'merge' && !empty($player_id_keep)) {
      $html .= '<tr data-name="' . $name . '" data-href="/wp-admin/admin.php?page=admin_data_merge&action_type=merge&player_name_keep=' . urlencode($player_name_keep) . '&player_id_keep=' . $player_id_keep . '&player_name_merge=' . urlencode($name) . '&player_id_merge=' . $id . '">
            <td><div class="button g365-button expanded no-margin-bottom">' . $name . $location_info . ' - ' . $id . '</div></td></tr>';
      }     
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;
