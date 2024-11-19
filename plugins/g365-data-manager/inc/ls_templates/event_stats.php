<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['id'])){ $id = htmlspecialchars($row['id']); }else{ $id = ''; }
//     $name = htmlspecialchars($row['name']);
//     $id = htmlspecialchars($row['id']);
    $html .= '<tr data-href="/account/stat_keep/?ev_id=' . $id . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name . '</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;