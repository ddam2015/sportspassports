<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['eventtime'])){ $eventtime = htmlspecialchars($row['eventtime']); }else{ $eventtime = ''; }
    if(!empty($row['id'])){ $get_id = intval($row['id']); }else{ $get_id = ''; }
//     $name = htmlspecialchars($row['name']);
//     $eventtime = htmlspecialchars($row['eventtime']);
    $id = $get_id;
    $html .= '<tr data-href-adv="ev_id=' . $id . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name . ' | id: ' . $id . ' | time: ' . $eventtime .  '</span>' . '</a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;