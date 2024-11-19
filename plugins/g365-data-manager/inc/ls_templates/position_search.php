<?php

$html = '';

/**
 * Body
 */
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
//     $name = htmlspecialchars($row['name']);
    $html .= '<tr data-g365_id="' . $row['id'] . '" data-g365_name="' . $name . '"><td><a class="button g365-button expanded no-margin-bottom"><span>' . $name . '</span></a></td></tr>';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message
  $html .= "<tr class=\"error\"><td><div>There is no result for \"{$query}\"</div><a class=\"button g365-button no-margin-bottom g365_add_button\">add position +</a></tr></td>";
}

return $html;