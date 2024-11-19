<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    
    if(!empty($row['name'])){ $name_raw = htmlspecialchars($row['name']); }else{ $name_raw = ''; }
    if(!empty($row['abbreviation'])){ $abbr = htmlspecialchars($row['abbreviation']); }else{ $abbr = ''; }
    if(!empty($row['profile_img'])){ $img = htmlspecialchars($row['profile_img']); }else{ $img = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['country'])){ $country = htmlspecialchars($row['country']); }else{ $country = ''; }
    
    $name = $name_raw;
//     $name_raw = htmlspecialchars($row['name']);
//     $abbr = htmlspecialchars($row['abbreviation']);
//     $img = htmlspecialchars($row['profile_img']);
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $country = htmlspecialchars($row['country']);
    if( !empty($abbr) ) $name = $abbr . ' <small>(' . $name . ')</small>';
    $location_info = '<span class="float-right">';
    if( !empty($city) && !empty($state) ) $location_info .= '<small>' . $city . '</small>, ';
    if( !empty($state) ) $location_info .= $state;
    if( !empty($state) && !empty($country) ) $location_info .= ', ';
    if( !empty($country) ) $location_info .= $country;
    $location_info .= '</span>';
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_short_name="' . (( empty($abbr) ) ? $name : $abbr) . '" data-g365_name="' . (( empty($abbr) ) ? $name : ($abbr . ', ' .$name_raw)) . '"><td><a class="button g365-button expanded"><span>' . $name . '</span>' . $location_info . ' | ' . $row['id'] . ' </a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message
  $html .= "<tr class=\"error\"><td><div class=\"tiny-margin-top\">There is no result for \"{$query}\". Please contact club team director/coach for club name information</div><a class=\"button g365-button g365_add_button\">add club +</a></tr></td>";
}

return $html;