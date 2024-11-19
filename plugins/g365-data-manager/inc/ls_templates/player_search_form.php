<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
//15,16,17U Elibility Edit
//   check if its before or after sept to determine year to use
  $current_season = intval(date('m'));
  $current_year = null;
  if($current_season < 9) {
    $current_year = intval(date('Y'));
  } else $current_year = (intval(date('Y')) + 1);
  
  $girlKeys = array(
        40 => "10",
        41 => "11",
        42 => "12",
        43 => "13",
        44 => "14",
        45 => "15",
        46 => "16",
        47 => "17"
  );
  
  foreach ($rows as $row) {
    //get base info together
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['verified'])){ $verified = intval($row['verified']); }else{ $verified = ''; }
//     $name = htmlspecialchars($row['name']);
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
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
    
//  define which class the lock is for
    $current_class_lock = ( !empty($add_lock->grad_year) ) ? substr( $add_lock->grad_year, 1) : '';
    $highschool_division = null;
    if($current_class_lock == $current_year) {
      $highschool_division = 17;
    } else if($current_class_lock == ($current_year + 1)) {
      $highschool_division = 16;
    } else if($current_class_lock == ($current_year + 2)) {
      $highschool_division = 15;
    }
    //see if the player is an exception
    $birthday = ( !empty($row['birthday']) ) ? $row['birthday'] : '';
    $exception = ( !empty($add_lock->birthday) ) ? $add_lock->birthday : '';
    $exception = ( $birthday !== '' && $exception !== '' && strtotime(substr($add_lock->birthday, 3, -1)) > strtotime($birthday)) ? ' data-g365_exception' : '';
    if( $exception != '' ) $ver_class .= ' ls_exception';
    $class = ( !empty($row['grad_year']) ) ? $row['grad_year'] : '';
    $eligible = ( !empty($add_lock->grad_year) ) ? substr( $add_lock->grad_year, 1) : '';
//     birthday check FIRST then grade - mark as exception if in the grade
    $birthdayCheck =  ( $birthday !== '' && $exception !== '' && strtotime(substr($add_lock->birthday, 3, -1)) > strtotime($birthday) ) ?  $outside_tag .= '<span class="tag-label">Ineligible Age</span>' : '';
    
//  adjust eligiblity grade lock for 15, 16, 17U, keep original as else statement
    if($highschool_division !== null) {
     if( $class !== '' && $eligible !== '' && intval($eligible) > intval($class)) $outside_tag .= '<span class="tag-label">Ineligible</span>';
    } else {
     if( $class !== '' && $eligible !== '' && intval($eligible) >= intval($class)) $outside_tag .= '<span class="tag-label">Ineligible</span>';
    }
//     if( $class !== '' && $eligible !== '' && intval($eligible) >= intval($class)) $outside_tag .= '<span class="tag-label">Ineligible</span>';
    if( empty($row['access']) ) $outside_tag .= '<span class="tag-label">Unclaimed</span>';
//     If anything is inside the outside tag like ineligible or unclaimed, cant add to roster
    if( $outside_tag !== '' ) {
      $row['id'] = 'null';
      $ver_class .= ' disabled';
    }
    //concat the player line
    $html .= "<tr" . $exception . ' data-g365_id="' . $row['id'] . '" data-g365_short_name="' . $name . '"><td><a class="button g365-button expanded' . $ver_class . '">' . $name .  $location_info . $verified . $eligible . '::' . $class . '</a>' . $outside_tag .'</td></tr>';
//        $html .= "<tr><td>". $highschool_division . "</td></tr>"; 
  }
//   if( count($rows) < 6 )   $html .= "<tr><td><a class=\"button g365-button g365_add_button no-margin-bottom\">+ add player</a></td></tr>";

} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<h4 class=\"search_error\">There is no result for \"{$query}\"</h4>";
}

return $html;