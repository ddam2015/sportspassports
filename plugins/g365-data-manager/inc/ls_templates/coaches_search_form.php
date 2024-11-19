<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
//     $name = htmlspecialchars($row['name']);
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
    $location_info = '';
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    } 
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_name="' . $name . '"><td><a class="button g365-button expanded no-margin-bottom">' . $name . $location_info . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td>There is no result for \"{$query}\"<br><a class=\"button g365-button no-margin-bottom g365_add_button\">+ add coach</a></tr></td>";
}

return $html;