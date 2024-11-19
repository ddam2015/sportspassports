<?php 
// * Description: Here is where I run my query to disable the favorite player the user chose to remove

if(isset($_POST['ajaxBaseUrl'])){
  $link = $_POST['ajaxBaseUrl'];
  include $link;
}

if(isset($_POST['rec_id'])){  
  $favoriteRowID = $connection->real_escape_string($_POST['rec_id']);
  
  $firstQuery = "DELETE FROM `wp_54ab678738_g365_favorites`
                WHERE `id` = '$favoriteRowID'";
  
  $result = mysqli_query($connection, $firstQuery);
  
  if ($result) {
      echo json_encode($favoriteRowID);
  } else {
      echo "Error updating record: " . mysqli_error($mysqli);
  }
  
  mysqli_close($connection);
  
}