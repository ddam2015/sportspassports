<?php
$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    //get base info together
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['verified'])){ $verified = htmlspecialchars($row['verified']); }else{ $verified = ''; }
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $name = htmlspecialchars($row['name']);
//     $verified = intval($row['verified']);
    $location_info = '';
    //if the player is verified
    if( $verified > 1 ) {
      $ver_class = ' ls_verified';
      $verified = ' <small class="ls_ver_tag">(verified)</small>';
    } else {
      $ver_class = ' ls_unverified';
      $verified = ' <small class="ls_ver_tag">(unverified)</small>';
    }
    //create location string
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
    $outside_tag = '';
    //see if the player is an exception
    $birthday = ( !empty($row['birthday']) ) ? $row['birthday'] : '';
    $exception = ( !empty($add_lock->birthday) ) ? $add_lock->birthday : '';
// //    $proc_birthday = substr($add_lock->birthday, 3, -1) . ' :: ' . $birthday;
//     $exception = ( $birthday !== '' && $exception !== '' && strtotime(substr($add_lock->birthday, 3, -1)) > strtotime($birthday)) ? ' data-g365_exception' : '';
    if( $birthday !== '' && $exception !== '' && strtotime(substr($add_lock->birthday, 3, -1)) > strtotime($birthday)) $ver_class .= ' ls_exception';

    $class = ( !empty($row['grad_year']) ) ? $row['grad_year'] : '';
    $eligible = ( !empty($add_lock->grad_year) ) ? substr( $add_lock->grad_year, 1) : '';
    if( $class !== '' && $eligible !== '' && intval($eligible) >= intval($class)) $outside_tag .= '<span class="tag-label">Ineligible</span>';
    if( empty($row['access']) ) $outside_tag .= '<span class="tag-label">Unclaimed</span>';
    if( $outside_tag !== '' ) {
//       $row['id'] = 'null';
      $ver_class .= ' disabled';
    } 
    //concat the player line
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_short_name="' . $name . '"><td><a class="button g365-button expanded' . $ver_class . '">' . $name . $location_info . $verified . '</a>' . $outside_tag .'</td></tr>';
  }
  if( count($rows) < 6 )   $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";

} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td>There is no result for \"{$query}\"<br><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";
}

return $html;