<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['team_type'])){ $type = htmlspecialchars($row['team_type']); }else{ $type = ''; }
//     $name = htmlspecialchars($row['name']);
//     $type = htmlspecialchars($row['team_type']);
    $name_short = (($row['level'] > 30) ? 'Girls ' . (intval($row['level']) - 36) . 'th Grade' : $row['level'] . 'U ') . (( empty($name) ) ? '' : ' ' . $name);
    $name_full = $name_short . (( empty($type) ) ? '' : ' (' . $type . ')');
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_name="' . $name_full . '" data-g365_short_name="' . $name_short . '" data-g365_additional_data="' . $row['level'] . '"><td><a class="button g365-button expanded"><span>' . $name_full . '</span></a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message
  $html .= "<tr class=\"error\"><td><div>There is no result for \"{$query}\"</div><a class=\"button g365-button g365_add_button\">create new team +</a></tr></td>";
}

return $html;