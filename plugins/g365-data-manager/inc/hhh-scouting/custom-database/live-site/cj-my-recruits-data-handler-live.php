<?php
  
// * Description: this is where I update the notes whenever the user adds notes for their favorited players.


include 'cjcustomdb-live.php';

if(isset($_POST['inputData'])){  
  $inputData = $connection->real_escape_string($_POST['inputData']);
  $playerID = $connection->real_escape_string($_POST['playerID']);
  $currentUserID = $connection->real_escape_string($_POST['currentUserID']);
  
  $firstQuery = "UPDATE `wp_54ab678738_g365_favorites`
                 SET notes = '{\"notes\": \"$inputData\"}'
                 WHERE `event_id` IS NULL AND user_id = '$currentUserID' AND player_id = '$playerID'";
  
  $result = mysqli_query($connection, $firstQuery);
  
  if ($result) {
      echo json_encode($inputData);
  } else {
      echo "Error updating record: " . mysqli_error($mysqli);
  }
  
  mysqli_close($connection);
  
}
  