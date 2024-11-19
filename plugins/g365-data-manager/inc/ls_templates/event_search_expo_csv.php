<?php
$html = '';

/**
 * Body
 */
// https://dev.grassroots365.com/wp-content/plugins/g365-data-manager/export-data.php
$pluginUrl  = '/wp-content/plugins/g365-data-manager/export-data.php';
if (!empty($rows)) {
  foreach ($rows as $row) {
    if(!empty($row['name'])){ $name = htmlspecialchars($row['name']); }else{ $name = ''; }
    if(!empty($row['nickname'])){ $nickname = htmlspecialchars($row['nickname']); }else{ $nickname = ''; }
    if(!empty($row['id'])){ $eventId = $row['id']; }else{ $eventId = ''; }
//     $name       = htmlspecialchars($row['name']);
//     $nickname   = htmlspecialchars($row['nickname']);
//     $eventId    = $row['id'];
    $html      .= '
                <tr data-href="exposure-data-manager/">
                  <td>
                    <form id="g365-export-form" method="post" action="'.$pluginUrl.'"> 
                      <table class="form-table">
                        <tbody>
                          <tr>
                            <input name="get_info" type="hidden" id="get_info" value="exposure">
                            <input name="event_id" type="hidden" id="event_id" value="'.$eventId.'">
                            <td colspan="2"><input type="submit" id="get-data-button" class="button g365-button expanded no-margin-bottom" value="'.$name.'"></td>
                          </tr>
                        </tbody>
                      </table>
                    </form>
                  </td>
                </tr>
              ';
  }
} else {
  // No result

  // To prevent XSS prevention convert user input to HTML entities
  $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

  // there is no result - return an appropriate message.
  $html .= "<tr><td><h4 class=\"search_error\">There is no result for \"{$query}\"</h4></tr></td>";
}

return $html;