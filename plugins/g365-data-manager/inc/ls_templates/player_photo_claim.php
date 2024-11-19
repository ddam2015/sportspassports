<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['first_name'])){ $first_name = htmlspecialchars($row['first_name']); }else{ $first_name = ''; }
    if(!empty($row['last_name'])){ $last_name = htmlspecialchars($row['last_name']); }else{ $last_name = ''; }
    if(!empty($row['id'])){ $id = htmlspecialchars($row['id']); }else{ $last_name = ''; }
    $name = $first_name . ' ' . $last_name;
    //get base info together
//     $name = htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']);
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
    // Check if user access is provided
    if(!empty($user_access)){
      // If user access get user access
      $user_keys = explode('-', $user_access); $user_access_site = $user_keys[0];
      // Check player access record(user access)
      $player_user_access_record = json_decode($row['access']);
      // Just in case only claimed player need to be shown, uncomment codes below
//       if(empty($player_user_access_record->$user_access_site)){ $html .= ''; }else{
//         // If user access in player access record display normal player search info
//         if(in_array($user_keys[1], $player_user_access_record->$user_access_site)){
//           $html .= '<tr onclick="pass_pl_id(this)" data-pl-id="'.$row['id'].'" data-pl-name="'.$name.'" data-g365_short_name="' . $name . '" data-g365_id="' . $row['id'] . '"' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a></td></tr>';
//         }else{
//           // If user access not in player access record display normal player search info with 'request access' message
//           $html .= '<tr data-g365_request_access="' . $row['id'] . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a class="mini-button input-group-button button no-margin-bottom">Request Access</a></td></tr>';
//         }
//       }
      if(empty($player_user_access_record->$user_access_site)){
        $html .= '<tr data-g365_request_access="' . $id . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a target="_blank" href="https://grassroots365.com/register/player-certification/" class="mini-button input-group-button button no-margin-bottom">Request Access</a></td></tr>';
      }else{
        // If user access in player access record display normal player search info
        if(in_array($user_keys[1], $player_user_access_record->$user_access_site)){
          $html .= '<tr onclick="pass_pl_id(this)" data-pl-id="'.$id.'" data-pl-name="'.$name.'" data-g365_short_name="' . $name . '" data-g365_id="' . $id . '"' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a></td></tr>';
        }else{
          // If user access not in player access record display normal player search info with 'request access' message
          $html .= '<tr data-g365_request_access="' . $id . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a target="_blank" href="https://grassroots365.com/register/player-certification/" class="mini-button input-group-button button no-margin-bottom">Request Access</a></td></tr>';
        }
      }
    }else{
      // If no user access send out error message
       $html .= '<tr><td>Need your user info to proceed. Please see the site admin.</td></tr>';
    }
  }

}else{
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td>There is no result for \"{$query}\"</td></tr>";
//   $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";
}

return $html;