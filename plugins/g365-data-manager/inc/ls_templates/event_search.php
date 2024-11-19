<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['nickname'])){ $nickname = htmlspecialchars($row['nickname']); }else{ $nickname = ''; }
//     $name = htmlspecialchars($row['name']);
//     $nickname = htmlspecialchars($row['nickname']);
    $html .= '<tr data-href="/event/' . $nickname . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name . '</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;