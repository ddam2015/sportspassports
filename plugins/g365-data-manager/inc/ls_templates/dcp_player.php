<?php
include('../g365-data-manager-ext.php');
include('../ls-loader.php');
include('custom-ls.php');
$html = '';

/**
 * Body
 */


if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['id'])){ $pl_id = htmlspecialchars($row['id']); }else{ $pl_id = ''; }
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['city'])){ $city = htmlspecialchars($row['city']); }else{ $city = ''; }
    if(!empty($row['state'])){ $state = htmlspecialchars($row['state']); }else{ $state = ''; }
    if(!empty($row['profile_img'])){ $profile_img = htmlspecialchars($row['profile_img']); }else{ $profile_img = ''; }
    $pl_img = 'https://sportspassports.com/wp-content/uploads/player-profiles/' . $profile_img;
//     $pl_id = htmlspecialchars($row['id']);
//     $name = htmlspecialchars($row['name']);
//     $city = htmlspecialchars($row['city']);
//     $state = htmlspecialchars($row['state']);
//     $nickname = htmlspecialchars($row['nickname']);
//     $pl_img = 'https://sportspassports.com/wp-content/uploads/player-profiles/'.htmlspecialchars($row['profile_img']);
    $location_info = '';
    if( !empty($city) || !empty($state) ) {
      $location_info = ' <small>(';
      if( !empty($city) ) $location_info .= $city;
      if( !empty($city) && !empty($state) ) $location_info .= ', ';
      if( !empty($state) ) $location_info .= $state;
      $location_info .= ')</small>';
    }
    $html .= '<tr data-test="" data-href="?pl='.$pl_id.'&type=player-directory"><td><a onClick="ls_pl_caller(this)" class="button g365-button expanded no-margin-bottom" data-pl-id="'.$pl_id.'" data-pl-name="'.$name.'" data-pl-nickname="'.$nickname.'" data-pl-img="'.$pl_img.'" data-toggle="pl_'.$pl_id.'"><span>' . $name . $location_info . '</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;
?>