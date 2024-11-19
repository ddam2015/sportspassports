<?php

$html = '';

/**
 * Body
 */
// if (!empty($rows)) {
//   foreach ($rows as $row) {
//     //get base info together
//     $name = htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']);;
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $location_info = '';
//     //create location string
//     if( !empty($city) || !empty($state) ) {
//       $location_info = ' <small>(';
//       if( !empty($city) ) $location_info .= $city;
//       if( !empty($city) && !empty($state) ) $location_info .= ', ';
//       if( !empty($state) ) $location_info .= $state;
//       $location_info .= ')</small>';
//     }
//     //if the player has not been claimed set that up
//     if( !empty($row['access']) ) {
//       $claim_button = '';
//       //if the searcher hasn't provided ID then kick it
//       if(!empty($user_access)) {
//         $user_keys = explode('-', $user_access);
//         $permissions = json_decode($row['access']);
//         //if we haven't had a user claim from the searcher site, allow them to claim for that site.
//         if( !empty($permissions->{$user_keys[0]}) ) {
//           $user_ids = $permissions->{$user_keys[0]};
//           //if the provided user ID doesn't match send the 'claim now button' otherwise send the normal record
//           if( !in_array($user_keys[1], $user_ids) ) {
//             $html .= '<tr data-g365_request_access="' . $row['id'] . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a class="mini-button input-group-button button no-margin-bottom">Request Access</a></td></tr>';
// //             $html .= '<tr><td><div class="input-group no-margin-bottom"><a class="button g365-button input-group-button no-margin-bottom flex-grow">' . $name . $location_info . '</a><a class="button g365-button input-group-button no-margin-bottom">Request Access</a></div></td></tr>';
//             continue;
//           } else {
//             $html .= '<tr data-g365_short_name="' . $name . '" data-g365_id="' . $row['id'] . '"' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a></td></tr>';
//           }
//         } else {
//           // Error here
//           $html .= '<tr data-g365_presets=\'{"first_name":"' . htmlspecialchars($row['first_name']) . '", "last_name":"' . htmlspecialchars($row['last_name']) . '", "pl_ev_id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
//         }
//       } else {
//         //if there is no user data provided by the user, show error message
//         $html .= '<tr><td>Need your user info to proceed. Please see the site admin.</td></tr>';
//       }
//     } else {
//       $html .= '<tr data-g365_presets=\'{"first_name":"' . htmlspecialchars($row['first_name']) . '", "last_name":"' . htmlspecialchars($row['last_name']) . '", "pl_ev_id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
//     }
//   }
//   if( count($rows) < 6 )   $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";

// } else {
//   // No result

//   // To prevent XSS prevention convert user input to HTML entities
//   $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

//   // there is no result - return an appropriate message.
//   $html .= "<tr><td>There is no result for \"{$query}\"</td></tr>";
//   $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";
// }

// return $html;



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
    $name = $first_name . ' ' . $last_name;
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
    $location_info = '';
    //create location string
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
    //if the player has not been claimed set that up
    if( !empty($row['access']) ) {
      $claim_button = '';
      //if the searcher hasn't provided ID then kick it
      if(!empty($user_access)) {
        $user_keys = explode('-', $user_access);
        $permissions = json_decode($row['access']);
        // If EBC claim exists, check if the access contain the request user id
        if( !empty($permissions->{$user_keys[0]}) ) {
          $user_ids = $permissions->{$user_keys[0]};
          //if the provided user ID doesn't match send the 'claim now button' otherwise send the normal record
          if( !in_array($user_keys[1], $user_ids) ) {
            $html .= '<tr data-g365_request_access="' . $row['id'] . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a class="mini-button input-group-button button no-margin-bottom request-access-btn">Request Access</a></td></tr>';
            continue;
          } else {
            $html .= '<tr data-g365_short_name="' . $name . '" data-g365_id="' . $row['id'] . '"' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a></td></tr>';
          }
        } else {
          // If EBC claim does not exist, allow they to claim player
          $html .= '<tr data-g365_request_access="' . $row['id'] . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a class="mini-button input-group-button button no-margin-bottom request-access-btn">Request Access</a></td></tr>';
        }
      } else {
        //if there is no user data provided by the user, show error message
        $html .= '<tr><td>Need your user info to proceed. Please see the site admin.</td></tr>';
      }
    } else {
      $html .= '<tr data-g365_presets=\'{"first_name":"' . htmlspecialchars($row['first_name']) . '", "last_name":"' . htmlspecialchars($row['last_name']) . '", "pl_ev_id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
    }
  }
  if( count($rows) < 6 )   $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";

} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td>There is no result for \"{$query}\"</td></tr>";
  $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ create profile</a></td></tr>";
}

return $html;


// $html .= '<tr data-g365_presets=\'{"first_name":"' . htmlspecialchars($row['first_name']) . '", "last_name":"' . htmlspecialchars($row['last_name']) . '", "pl_ev_id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';