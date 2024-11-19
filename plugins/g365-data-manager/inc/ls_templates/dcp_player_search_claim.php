<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    //get base info together
    if(!empty($row['first_name'])){ $first_name = htmlspecialchars($row['first_name']); }else{ $first_name = ''; }
    if(!empty($row['last_name'])){ $last_name = htmlspecialchars($row['last_name']); }else{ $last_name = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['email'])){ $email = htmlspecialchars($row['email']); }else{ $email = ''; }
    if(!empty($row['id'])){ $id = $row['id']; }else{ $id = ''; }
    $name = $first_name . ' ' . $last_name;
    
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $email = htmlspecialchars($row['email']);
    $location_info = '';
    //create location string
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
      $html .= '<tr data-g365_presets=\'{"first_name":"' . $first_name . '", "last_name":"' . $last_name . '", "pl_cp_id":' . $id . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
//       $html .= '<tr data-g365_presets=\'{"first_name":"' . htmlspecialchars($row['first_name']) . '", "last_name":"' . htmlspecialchars($row['last_name']) . '", "pl_cp_id":"' . $row['id'] . '", "email":"' . htmlspecialchars($row['email']) . '", "phone":"' . htmlspecialchars($row['phone']) . '", "birthday":"' . htmlspecialchars($row['birthday']) . '"}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';  
    
  }
  $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";

} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td>There is no result for \"{$query}\"</td></tr>";
  $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";
}

return $html;



      // $html .= "<tr" . ((empty($row['access'])) ? ' data-g365_presets=\'{"first_name":"' . htmlspecialchars($row['first_name']) . '", "last_name":"' . htmlspecialchars($row['last_name']) . '", "pl_ev_id":' . $row['id'] . '}\'' : ' data-g365_short_name="' . $name . '" data-g365_id="' . $row['id'] . '"') . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
