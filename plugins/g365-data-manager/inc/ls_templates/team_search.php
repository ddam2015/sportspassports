<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['abbreviation'])){ $abbr = htmlspecialchars($row['abbreviation']); }else{ $abbr = ''; }
    if(!empty($row['profile_img'])){ $img = htmlspecialchars($row['profile_img']); }else{ $img = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['country'])){ $country = htmlspecialchars($row['country']); }else{ $country = ''; }
    if(!empty($row['nickname'])){ $nickname = htmlspecialchars($row['nickname']); }else{ $nickname = ''; }
//     $name = htmlspecialchars($row['name']);
//     $abbr = htmlspecialchars($row['abbreviation']);
//     $img = htmlspecialchars($row['profile_img']);
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $country = htmlspecialchars($row['country']);
//     $nickname = htmlspecialchars($row['nickname']);
    if( !empty($abbr) ) $name = $abbr . ' <small>(' . $name . ')</small>';
    $location_info = '<span class="float-right">';
    if( !empty($city) && !empty($state) ) $location_info .= '<small>' . $city . '</small>, ';
    if( !empty($state) ) $location_info .= $state;
    if( !empty($state) && !empty($country) ) $location_info .= ', ';
    if( !empty($country) ) $location_info .= $country;
    $location_info .= '</span>';
    $html .= '<tr data-href="/club/' . $nickname . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name . '</span>' . $location_info . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;