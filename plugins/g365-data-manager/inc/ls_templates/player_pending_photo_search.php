<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
    $id = htmlspecialchars($row['id']);
    $name = htmlspecialchars($row['name']);
    $nickname = htmlspecialchars($row['nickname']);
    $location_info = '';
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
    $html .= '<tr data-href="/wp-admin/admin.php?page=admin_data_photo_verif&type=pending&pl_id=' . $id . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name .'</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;