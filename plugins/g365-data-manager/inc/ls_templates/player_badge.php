<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['id'])){ $id = htmlspecialchars($row['id']); }else{ $id = ''; }
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['nickname'])){ $nickname = htmlspecialchars($row['nickname']); }else{ $nickname = ''; }
//     $id = htmlspecialchars($row['id']);
//     $name = htmlspecialchars($row['name']);
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $nickname = htmlspecialchars($row['nickname']);
    $location_info = '';
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
    $html .= '<tr data-pl-id="'.$id.'" data-pl-name="'.$name.'"><td><a href="admin.php?page=admin_g365_badge_data&submenu=manual-badge&pl_id='.$id.'" class="button g365-button expanded no-margin-bottom"><span>' . $name . $location_info . '</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;