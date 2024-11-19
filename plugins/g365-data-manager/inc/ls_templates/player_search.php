<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['nickname'])){ $nickname = htmlspecialchars($row['nickname']); }else{ $nickname = ''; }
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $name = htmlspecialchars($row['name']);
//     $nickname = htmlspecialchars($row['nickname']);
    $location_info = '';
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
    $html .= '<tr data-href="/player/' . $nickname . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name . $location_info . '</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;