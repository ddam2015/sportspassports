<?php
// * Description: this is the dev site connection to the database where I run my queries needed to make the livesearch work.
// Include your database connection file here
include 'cjcustomdb-dev.php';
// global $wpdb;
// $dbs = json_decode(dbs());

if(isset($_POST['query'])){
    $search = $_POST['query'];
  
    if(isset($_POST['event_id'])){
    $event_id = $_POST['event_id'];

      // First query to get the combined array with event id
    $firstQuery = "SELECT JSON_KEYS(`players`) AS combined
                   FROM `wp_54ab678738_g365_rosters` ros
                   WHERE `event` IN (
                       SELECT ID
                       FROM `wp_54ab678738_g365_events`
                       WHERE `org` = '7164' AND `id` = '$event_id'
                   )";
      
    }else{
      
    // First query to get the combined array
    $firstQuery = "SELECT JSON_KEYS(`players`) AS combined
                   FROM `wp_54ab678738_g365_rosters` ros
                   WHERE `event` IN (
                       SELECT ID
                       FROM `wp_54ab678738_g365_events`
                       WHERE `org` = '7164'
                   )";
    }
    

    $result = mysqli_query($connection, $firstQuery);

    $combinedArray = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if JSON string is not null before decoding
            if ($row['combined'] !== null) {
                // Ensure $row['combined'] is a JSON string
                $jsonString = is_array($row['combined']) ? json_encode($row['combined']) : $row['combined'];

                // Decode the JSON string to an array
                $keysArray = json_decode($jsonString, true);

                // Combine all keys into the main array
                $combinedArray = array_merge($combinedArray, $keysArray);
            }
        }
    } else {
        echo "No results found for the first query";
    }
  
  

    // Check if the first query was successful
    if ($combinedArray) {
        // Second Query to get player names based on player IDs
        
        $combinedIds = implode(",", $combinedArray);
      
        $secondQuery = "
            SELECT *
            FROM wp_54ab678738_g365_players
            WHERE name LIKE '%$search%' AND id IN ($combinedIds)
        ";

        // Perform the second query
        $resultSecondQuery = mysqli_query($connection, $secondQuery);

        // Check if the second query was successful
        if ($resultSecondQuery) {
            // Build an array to store player names
            $playerNames = array();

            // Fetch player names and add to the array
            while ($rowSecondQuery = mysqli_fetch_assoc($resultSecondQuery)) {
                $playerNames[] = array(
                      'name' => $rowSecondQuery['name'],
                      'nickname' => $rowSecondQuery['nickname'],
                      'city' => $rowSecondQuery['city'],
                      'state' => $rowSecondQuery['state']
                ); 
            }

            // Output the JSON-encoded array
            echo json_encode($playerNames);

            // Free the result set for the second query
            mysqli_free_result($resultSecondQuery);
        } else {
            // Handle the error for the second query
            echo json_encode(array('error' => mysqli_error($connection)));
        }

        // Free the result set for the first query
        mysqli_free_result($result);
    } else {
        // Handle the error for the first query
        echo json_encode(array('error' => mysqli_error($connection)));
    }

    // Close the connection
    mysqli_close($connection);
    
  
}

?>


