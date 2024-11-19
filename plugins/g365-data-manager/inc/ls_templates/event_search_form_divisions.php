<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    $stage_youth_ev = array('546', '547', '548', '549'); // Hardcoded stage youth events
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['divisions'])){ $divisions = htmlspecialchars($row['divisions']); }else{ $divisions = ''; }
    if(!empty($row['short_name'])){ $short_name = htmlspecialchars($row['short_name']); }else{ $short_name = ''; }
//     $name = htmlspecialchars($row['name']);
//     $divisions = htmlspecialchars($row['divisions']);
//     $short_name = htmlspecialchars($row['short_name']);
    $date = htmlspecialchars($row['dates']);
    $dates = explode('|', $date);
    if( !in_array($row['id'], $stage_youth_ev) ){ // Hide Stage Youth events
      $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_short_name="' . (( empty($short_name) ) ? $name : $short_name . ' ' . date('Y', strtotime($row['eventtime']))) . '" data-g365_name="' . $name . '" data-g365_additional_data="' . $divisions . '"><td><a class="button g365-button expanded">' . $name . ' <small>(' . date('M j', strtotime($dates[0])) . '-' . date('j Y', strtotime(end($dates)))  . ')</small></a></td></tr>';
    }
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr class=\"error\"><td>There is no result for \"{$query}\"<br><a class=\"button g365-button g365_add_button\">+ add event</a></tr></td>";
}

return $html;