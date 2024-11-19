<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['abbreviation'])){ $abbr = htmlspecialchars($row['abbreviation']); }else{ $abbr = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['id'])){ $id = htmlspecialchars($row['id']); }else{ $id = ''; }
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
//     $abbr = htmlspecialchars($row['abbreviation']);
//     $city = htmlspecialchars($row['city']);
//     $id = htmlspecialchars($row['id']);
//     $name = htmlspecialchars($row['name']);
//     $state = htmlspecialchars($row['state']);
    if( !empty($abbr) ) $name = $abbr . ' <small>(' . $name . ')</small>';
    $location_info = '<span class="float-right">';
    if( !empty($city) && !empty($state) ) $location_info .= '<small>' . $city . '</small>, ';
    if( !empty($state) ) $location_info .= $state;
    if( !empty($state) && !empty($country) ) $location_info .= ', ';
    if( !empty($country) ) $location_info .= $country;
    $location_info .= '</span>';
    $html .= '<tr data-href-adv="org_id=' . $id . '"><td><a class="button g365-button expanded"><span>' . $name . '</span>' . $location_info . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;