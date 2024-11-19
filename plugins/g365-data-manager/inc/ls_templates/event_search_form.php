<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['short_name'])){ $short_name = htmlspecialchars($row['short_name']); }else{ $short_name = ''; }
//     $name = htmlspecialchars($row['name']);
//     $short_name = htmlspecialchars($row['short_name']);
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_short_name="' . (( empty($short_name) ) ? $name : $short_name . ' ' . date('Y', strtotime($row['eventtime']))) . '" data-g365_name="' . $name . '"><td><a class="button g365-button expanded">' . $name . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr class=\"error\"><td>There is no result for \"{$query}\"<br><a class=\"button g365-button g365_add_button\">+ add event</a></tr></td>";
}

return $html;