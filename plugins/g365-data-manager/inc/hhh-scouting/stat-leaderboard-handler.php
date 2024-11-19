<?php
// * Description: this is where I run my queries for my main stat leaderboard.

//below make the check if its the live site or dev site.
// $current_site_url = site_url();

// // Define your development site URL
// $dev_site_url = 'https://dev.sportspassports.com/';

// // Check if you are in the development environment
// $is_dev_environment = (strpos($current_site_url, $dev_site_url) !== false);

// if ($is_dev_environment) {
    // You are in the development environment
    // Add your development-specific code here
if (isset($_POST['ajaxBaseUrl'])) {
  $url = $_POST['ajaxBaseUrl'];
  $sitetype = $_POST['sitetype'];
  include $url;
//     '/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cjcustomdb-dev.php';
  
  
}
// } else {
//     // You are in the production environment
//     // Add your production-specific code here
// }



error_reporting(E_ERROR | E_PARSE);
// Check if the request method is POST
if (isset($_POST['user'])) {
   // Access the data sent in the AJAX request
    $pl_nickname = $_POST['info'];
    $user = $_POST['user'];

  
    $query = "
            SELECT *
            FROM wp_54ab678738_g365_players
            WHERE nickname LIKE '%" . mysqli_real_escape_string($connection, $pl_nickname) . "%'
        ";
  
    // Perform the first query
    $resultQuery = mysqli_query($connection, $query);
  
    // Check if the second query was successful
        if ($resultQuery) {
            // Build an array to store player names
            $playerinfo = array();

            // Fetch player names and add to the array
            while ($rowSecondQuery = mysqli_fetch_assoc($resultQuery)) {
              $fulldate = $rowSecondQuery['height_ft'] ."'".$rowSecondQuery['height_in'];
              $fullcontact = $rowSecondQuery['email'] ." <br>". $rowSecondQuery['phone'];
              $img_link = 'https://sportspassports.com//wp-content/uploads/player-profiles/' . $rowSecondQuery['profile_img']; 
              $playerinfo[] = array(
                      'gpa' => $rowSecondQuery['gpa'],
                      'sat' => $rowSecondQuery['sat'],
                      'height' => $fulldate,
                      'pl_name' => $rowSecondQuery['name'],
                      'img_link' => $img_link,
                      'position' => $rowSecondQuery['position'],
                      'grad_year' => $rowSecondQuery['grad_year'],
                      'pl_nickname' => $rowSecondQuery['nickname'],
                      'contact_info' => $fullcontact
                ); 
              
              // Encode the player data as JSON
              $escapedJsonPlayerData = mysqli_real_escape_string($connection, json_encode($playerinfo, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
              
              // Ensure the JSON data is properly escaped for SQL
//               $escapedJsonPlayerData = mysqli_real_escape_string($connection, $pl_data_final);
              
              $playerId = $rowSecondQuery['id'];

              // Get the current date and time
              $currentDateTime = date('Y-m-d H:i:s');
              $notes = '{"notes": ""}';
              $escapedNotes = mysqli_real_escape_string($connection, $notes);
              
              // Perform the second query
              $insertQuery = "
                  INSERT INTO wp_54ab678738_g365_favorites (enabled, event_id, user_id, player_id, notes, createdate, updatetime, pl_data)
                  VALUES (1, NULL, '$user', '$playerId', '$escapedNotes', '$currentDateTime', '$currentDateTime', '$escapedJsonPlayerData')
                  ON DUPLICATE KEY UPDATE
                  notes = '$escapedNotes', updatetime = '$currentDateTime'
              ";
              
              // Execute the second query
              $resultInsert = mysqli_query($connection, $insertQuery);
            
              if ($resultInsert) {
                  echo json_encode(['status' => 'success', 'message' => 'Record inserted successfully']);
              } else {
                  echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($mysqli)]);
              }
              
              
            }

            // Output the JSON-encoded array
//             echo json_encode($playerinfo);

            // Free the result set for the first query
            mysqli_free_result($resultQuery);
          
            // Free the result set for the first query
//             mysqli_free_result($resultInsert);
          
        } else {
            // Handle the error for the second query
            echo json_encode(array('error' => mysqli_error($connection)));
        }
  

    // Return the result as JSON
//     echo json_encode(['result' => $result]);
} else if( isset($_POST['currentUser'])){
    $pl_name = $_POST['playerName'];
    $currentUser = $_POST['currentUser'];
  
  
    $query = "
            SELECT id
            FROM wp_54ab678738_g365_players
            WHERE name LIKE '%" . mysqli_real_escape_string($connection, $pl_name) . "%'
        ";
  
    // Perform the first query
    $resultQuery = mysqli_query($connection, $query);
  
    // Check if the second query was successful
    if ($resultQuery) {
        // Fetch the results and process them as needed
        while ($row = mysqli_fetch_assoc($resultQuery)) {
                // Access the player ID using $row['id']
                $playerId = $row['id'];

                // ... Do something with the player ID ...
        }
      
        // Check if the record already exists
        $selectQuery = "
            SELECT * FROM wp_54ab678738_g365_favorites
            WHERE player_id = '$playerId' AND user_id = '$currentUser' AND enabled != 0
        ";

        $resultSelect = mysqli_query($connection, $selectQuery);

        if ($resultSelect) {
            // Check if any rows were returned
            if (mysqli_num_rows($resultSelect) > 0) {
                // The record already exists, return true
                echo json_encode(['status' => 'success', 'exists' => true]);
            } else {
                // No matching record found, return false
                echo json_encode(['status' => 'success', 'exists' => false]);
            }
        } else {
            // Error in the query
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($connection)]);
        }
      

        // Free the result set
        mysqli_free_result($resultQuery);
      
        mysqli_free_result($resultSelect);
    } else {
        // Handle the error
        echo json_encode(['status' => 'success', 'exists' => false]);
    }
  
}else if(isset($_POST['getEventData'])){
  $eventId = $_POST['eventId'];
  
  $eventId = mysqli_real_escape_string($connection, $eventId);
  
  $query = "  SELECT *
              FROM wp_54ab678738_g365_events
              WHERE id = $eventId";
  
    // Perform the first query
    $resultQuery = mysqli_query($connection, $query);
  
    // Check for query execution errors
    if (!$resultQuery) {
        $error = mysqli_error($connection);
        $responseData = array('error' => 'Query execution failed: ' . $error);
    } else {
        // Fetch the single row directly
        $row = mysqli_fetch_assoc($resultQuery);

        if ($row) {
            // Process the data as needed
            $responseData = $row;
        } else {
            // No rows found
            $responseData = array('error' => 'No data found for the specified event ID.');
        }
    }

    // Return the response as JSON
    echo json_encode($responseData);
    exit;
  
  
}else {
    // Return an error if the request method is not POST
    echo json_encode(['error' => 'Invalid request method.']);
}

?>