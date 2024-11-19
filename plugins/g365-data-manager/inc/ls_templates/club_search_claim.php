<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name_raw = htmlspecialchars($row['name']); }else{ $name_raw = ''; }
//     $name_raw = htmlspecialchars($row['name']);
    $name = $name_raw;
    if(!empty($row['abbreviation'])){ $abbr = htmlspecialchars($row['abbreviation']); }else{ $abbr = ''; }
//     $abbr = htmlspecialchars($row['abbreviation']);
    //if we don't have a abbreviation then set one from name otherwise update name to include the abbreviation
    if( empty($abbr) ) {
      $abbr = $name;
    } else {
      $name = $abbr . ' <small>(' . $name . ')</small>';
    }
    if(!empty($row['profile_img'])){ $img = htmlspecialchars($row['profile_img']); }else{ $img = ''; }
//     $img = htmlspecialchars($row['profile_img']);
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
//     $city = htmlspecialchars($row['city']);
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
//     $state = htmlspecialchars($row['state']);
    if(!empty($row['country'])){ $country = htmlspecialchars($row['country']); }else{ $country = ''; }
//     $country = htmlspecialchars($row['country']);
    $location_info = '<span class="float-right">';
    if( !empty($city) && !empty($state) ) $location_info .= '<small>' . $city . '</small>, ';
    if( !empty($state) ) $location_info .= $state;
    if( !empty($state) && !empty($country) ) $location_info .= ', ';
    if( !empty($country) ) $location_info .= $country;
    $location_info .= '</span>';
    
    //if the player has not been claimed set that up //issue is if the org has access filled in
    if( !empty($row['access']) ) {
      $claim_button = '';
      //if the searher hasn't provided ID then kick it
      if(!empty($user_access) ) {
        $user_keys = explode('-', $user_access);
        $permissions = json_decode($row['access']);
        //if we haven't had a user claim  from the searcher site, allow them to claim for that site.
        if( !empty($permissions->{$user_keys[0]}) ) {
          $user_ids = $permissions->{$user_keys[0]};
          //if the provided user ID doesn't match send the 'claim now button' otherwise send the normal record
          if( !in_array($user_keys[1], $user_ids) ) {
            $html .= '<tr data-g365_request_access="' . $row['id'] . '"><td><div class="input-group no-margin-bottom"><a class="button input-group-button g365-button no-margin-top no-margin-bottom flex-grow">' . $name . $location_info . '</a><a class="mini-button input-group-button button no-margin-bottom request-access-btn">Request Access</a></td></tr>';
            continue;
          } else {
            $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_short_name="' . $abbr . '" data-g365_name="' . (( $abbr == $name ) ? $name : ($abbr . ', ' .$name_raw)) . '"><td><a class="button g365-button expanded"><span>' . $name . '</span>' . $location_info . '</a></td></tr>';
          }
        } else {
          $html .= '<tr data-g365_presets=\'{"name":"' . $name_raw . '", "abbreviation":"' . $abbr . '", "id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
        }
      } else if(current_user_can( 'administrator' )){
          $html .= '<tr data-g365_presets=\'{"name":"' . $name_raw . '", "abbreviation":"' . $abbr . '", "id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
      } else {
        //if there is no user data provided by the user, show error message
        $html .= '<tr><td>Need your user info to proceed. Please see the site admin. test</td></tr>';
      }
    } else {
      $html .= '<tr data-g365_presets=\'{"name":"' . $name_raw . '", "abbreviation":"' . $abbr . '", "id":' . $row['id'] . '}\'' . '><td><a class="button g365-button expanded">' . $name . $location_info . '</a> </td></tr>';
    }
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message
  $html .= "<tr class=\"error\"><td><div class=\"tiny-margin-top\">There is no result for \"{$query}\". Please contact club team director/coach for club name information</div><a class=\"button g365-button g365_add_button g365_club_add_btn\">add club +</a></tr></td>";
}

return $html;