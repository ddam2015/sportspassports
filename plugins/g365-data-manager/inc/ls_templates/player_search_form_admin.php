<?php

$html = '';

/**
 * Body //admin_data_players
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['verified'])){ $verified = htmlspecialchars($row['verified']); }else{ $verified = ''; }
    if( $verified > 1 ) {
      $verified = 'Verified';
    } else {
      $verified = 'Unverified';
    }
    
    if(!empty($row['access'])){ $access = 'Claimed'; }else{ $access = 'Unclaimed'; }
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
//     $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_name="' . $name . '" data-g365_short_name="' . $name . '"><td><a class="button g365-button expanded' . $ver_class . '">' . $name . $location_info . ' | id: ' . $row['id'] . '</a></td></tr>';
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_name="' . $name . '" data-g365_short_name="' . $name . '"><td><a class="button g365-button expanded">' . $name . $location_info . ' | id: ' . $row['id'] . '| ' . $verified. '| ' . $access.'</a></td></tr>';
  }
  $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";

} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td>There is no result for \"{$query}\"<br><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";
}

return $html;