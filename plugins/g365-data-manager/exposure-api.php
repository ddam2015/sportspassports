<?php
//all exposure functionality

function exposure_api_data($type = null, $args = null){
  if($args['remote_exposure'] === true){
    if(!empty($type)){
      switch($type){
        case 'spp-app':
          if(!strpos(get_site_url(), 'dev.')){
            return ['api_key'=>'SC6CLR9FG99NRA', 'api_secret_key'=>'K9zhwFH62sBA9PsSO0dvA8RVb19u999r'];
          }else{
            return ['api_key'=>'gWJ99E99Wi9uXt', 'api_secret_key'=>'92920pj99D98nGZU6to99ApbtS99R2tC']; //  Disconnect from live exposure account to prevent dev site testing data send to exposure
          }
          break;
      }
    }
  }else{
    // G365 Exposure account
    if(!strpos(get_site_url(), 'dev.')){
      return ['api_key'=>'SC6CLR9FG99NRA', 'api_secret_key'=>'K9zhwFH62sBA9PsSO0dvA8RVb19u999r'];
      // Custom organization exposure account
      // Cali Live
    //   return ['api_key'=>'la93cD9Qjon9Ij', 'api_secret_key'=>'yi93cJ05scMf9Mf9cvg9ZGOfczO9BlyY'];
    }else{
      return ['api_key'=>'gWJ99E99Wi9uXt', 'api_secret_key'=>'92920pj99D98nGZU6to99ApbtS99R2tC']; //  Disconnect from live exposure account to prevent dev site testing data send to exposure
    }
  }
}

function g365_get_expo_data($type, $query, $return_fields = null, $exact = true, $limit = 1000, $offset = 0, $order_col = 'roster.level', $direction = 'ASC') {
	//main function for getting data out of the plugin
	if( empty($type) || empty($query) ) return 'Missing paramters, cannot get record(s).';
	global $wpdb;
	$wpdb_table = $wpdb->{$type};
	//build where string
	$where_string = g365_build_where($query,$exact);
	//incorporate select string 
	$select_string = ( is_null($return_fields) ) ? '*' : sanitize_text_field( $return_fields );
	//get the columns
	$data = $wpdb->get_results(
		"SELECT $select_string
    FROM $wpdb_table AS roster
    LEFT JOIN wp_54ab678738_g365_organizations AS orgs ON roster.org=orgs.id
    LEFT JOIN wp_54ab678738_g365_teams AS teams ON roster.team=teams.id
    LEFT JOIN wp_54ab678738_g365_events AS ev ON roster.event=ev.id
    LEFT JOIN wp_54ab678738_g365_coaches AS coaches ON roster.coach=coaches.id
    LEFT JOIN wp_54ab678738_g365_coaches AS assts ON roster.asst=assts.id
    $where_string
		ORDER BY $order_col $direction LIMIT $offset, $limit;"
	);
  //set the divisons and team names before returning
  foreach( $data as &$team_record ) {
    $team_record->DIVISION = g365_level_key($team_record->DIVISION, false) .' '. $team_record->LEVEL;
    $team_record->TEAMNAME = str_replace('{{division}}', $team_record->DIVISION, $team_record->TEAMNAME);
  }
	if( count($data) === 1 && $return_fields !== null && count(explode(',',$return_fields)) === 1 ) return $data[0]->{$return_fields};
	return $data;
}

function compare_records($incumbent,$incipient) {
	//return error if not an array
	if( !is_object($incumbent) ) return 'Duplicate record could not be parsed. Please check your data.';
	//return error if we don't have times to compare.
	if( empty($incumbent->updatetime) ) return "Could not compare times of new and duplicate data.";
	//if the incipient data doesn't have an updatetime to compare against, assume that it's being generated now.
	if( empty( $incipient['updatetime'] ) ) $incipient['updatetime'] = date('Y-m-d H:i:s');
	//figure out which record is newer
	$newer = ($incumbent->updatetime <= $incipient['updatetime']) ? true : false;
	//add data from newer record.
	$incipient_update = array();
	//compare each new value, picking the version
	foreach($incipient as $name => $data){
		//don't change ids if present
		if( $name == 'id' ) continue;
		//if new data is the same as incumbent, skip it 
		if( $data == $incumbent->{$name} ) continue;
		//see if we have any incumbent data to compare against
		if( !empty($incumbent->{$name}) ) {
			//add switch statement here //
			//see if we are working with a JSON field
			$dup_val = json_decode($incumbent->{$name}, true);
			if( is_array( $dup_val ) ) {
				//if the incumbent data is JSON check that we have JSON data to merge
				$new_val = json_decode($data, true);
				if( is_array( $new_val ) ) {
					//merge the new data according to newness
					$base_arr = ( $newer ) ? $new_val : $dup_val;
					$update_arr = ( $newer ) ? $dup_val : $new_val;
					$new_var_count = 0;
					$type_arr = array_is_indexed( $base_arr );
					foreach($update_arr as $key => $value) {
						$value = trim($value);
						if( $type_arr ) {
							if( !in_array($value, $base_arr) ) {
								$base_arr[] = $value;
								$new_var_count++;
							}
						} else {
							if( $base_arr[$key] != $value ) {
								$base_arr[$key] = $value;
								$new_var_count++;
							}
						}
					}
					if( $new_var_count === 0 ) continue;
					$data = json_encode($base_arr);
				} else {
					//if the incoming data isn't JSON, it can't be incoporated into the existing data
					continue;
				}
			} else {
				//if the incumdent data isn't JSON, only add if newer
				if( $newer === false ) continue;
			}
		}
		//incase we somehow have no data to write, skip adding, otherwise add it to the array for db updating
		if( !empty($data) ) $incipient_update[$name] = $data;
	}
	//if there isn't any new data, return a message
	if( empty($incipient_update) ) return 'No new data present. No records updated.';
	//incumbent record to update
	$incipient_update['id'] = $incumbent->id;
	return $incipient_update;
}


//get games data from exposure
function get_exposure_data( $reqType, $reqId, $eventId = null ){ // Leave $reqId blank to retrieves a paged list of active events 
  $secretKey  = exposure_api_data()['api_secret_key']; // Exposure secret key
  $apiKey     = exposure_api_data()['api_key']; // Exposure api key
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&GET&'.$timeStamp.'&/'.$reqType; // GET Signature format
  // Convert message to uppercase
  $upMes    = strtoupper($message);
  // Encode as UTF8
  $secretKeyEn  = utf8_encode($secretKey);
  $enUpMes      = utf8_encode($upMes);
  // Base64 encode
  $encoded = base64_encode(hash_hmac("sha256", $enUpMes, $secretKeyEn, true));
  if( !empty($reqId) ){ // List ".time()" to get latest API request 
    $api_url = 'https://basketball.exposureevents.com/'.$reqType.'?id='.$reqId.'&eventid='.$eventId.$apiKeyExt . time();
  } else { // Specific details
    $api_url = 'https://basketball.exposureevents.com/'.$reqType.'?eventid='.$eventId.$apiKeyExt . time();     
  }
//   if( $reqType == $divisionAPI AND empty($reqId) AND empty($eventId) ){ // Get a list of division id
//     $api_url = 'https://basketball.exposureevents.com/'.$divisionAPI . time();
//   }
//   if( $reqType == $divisionAPI AND empty($reqId) AND !empty($eventId) ){
//     $api_url = 'https://basketball.exposureevents.com/'.$reqType.'?eventid='.$eventId.$apiKeyExt . time();  
//   }    
  $response = wp_remote_get( $api_url ,
     array( 
  // 'timeout' => 10,
       'headers' => array(
          "Timestamp" => $timeStamp,
          "Authentication" => $apiKey.".".$encoded
        ) 
      )
  ); 
  // echo $api_url;
  $header = $response['headers'];
  $body   = $response['body'];
   // Convert JSON string to Array
  $encodedJSON = json_decode($body, true);
  return $encodedJSON;
}

//get games data from exposure
function get_SCIBCA_exposure_data( $reqType, $reqId, $eventId = null ){ // Leave $reqId blank to retrieves a paged list of active events 
  $secretKey  = 'yi93cJ05scMf9Mf9cvg9ZGOfczO9BlyY'; // Exposure secret key
  $apiKey     = 'la93cD9Qjon9Ij'; // Exposure api key
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&GET&'.$timeStamp.'&/'.$reqType; // GET Signature format
  // Convert message to uppercase
  $upMes    = strtoupper($message);
  // Encode as UTF8
  $secretKeyEn  = utf8_encode($secretKey);
  $enUpMes      = utf8_encode($upMes);
  // Base64 encode
  $encoded = base64_encode(hash_hmac("sha256", $enUpMes, $secretKeyEn, true));
  if( !empty($reqId) ){ // List ".time()" to get latest API request 
    $api_url = 'https://basketball.exposureevents.com/'.$reqType.'?id='.$reqId.'&eventid='.$eventId.$apiKeyExt . time();
  } else { // Specific details
    $api_url = 'https://basketball.exposureevents.com/'.$reqType.'?eventid='.$eventId.$apiKeyExt . time();     
  }
//   if( $reqType == $divisionAPI AND empty($reqId) AND empty($eventId) ){ // Get a list of division id
//     $api_url = 'https://basketball.exposureevents.com/'.$divisionAPI . time();
//   }
//   if( $reqType == $divisionAPI AND empty($reqId) AND !empty($eventId) ){
//     $api_url = 'https://basketball.exposureevents.com/'.$reqType.'?eventid='.$eventId.$apiKeyExt . time();  
//   }    
  $response = wp_remote_get( $api_url ,
     array( 
  // 'timeout' => 10,
       'headers' => array(
          "Timestamp" => $timeStamp,
          "Authentication" => $apiKey.".".$encoded
        ) 
      )
  ); 
  // echo $api_url;
  $header = $response['headers'];
  $body   = $response['body'];
   // Convert JSON string to Array
  $encodedJSON = json_decode($body, true);
  return $encodedJSON;
}

// Save games to specific event
function save_exposure_game( $event_id ){
	global $wpdb;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_games = $wpdb->g365_games;
  if( !is_numeric($event_id) || $event_id == 0 ) return 'Need vaild Event ID.';
  $event_id = intval($event_id);
  $exposure_id   = $wpdb->get_var("SELECT JSON_UNQUOTE(JSON_EXTRACT(schedule_link, '$.exposure')) FROM $wpdb_events WHERE `id` = $event_id;");
  if( !is_numeric($exposure_id) || empty($exposure_id) ) return 'Exposure linkage not present.';
  //grab the exposure data
//   $exposure_team_data = get_exposure_data( 'api/v1/games', '', '172121' ); echo "<pre>"; print_r($exposure_team_data); echo "</pre>"; die;
//   return $exposure_team_data;
  $exposure_data = get_exposure_data( 'api/v1/games', '', $exposure_id );
  $exposure_data_compile = array();
  $errors = [];
  if( $exposure_data ) {
    foreach( $exposure_data['Games']['Results'] as $dex => $game_vals ){
      if( empty($game_vals['HomeTeam']['ExternalId']) || empty($game_vals['AwayTeam']['ExternalId']) ) continue;
        $new_datetime = date('Y-m-d H:i:s', strtotime( $game_vals['Date'] . ' ' . $game_vals['Time'] ));
        // Hardcoded ogp location to avoid miss match location
        if( $game_vals['VenueCourt']['Venue']['Name'] === 'Open Gym Premier'){
          $hardcoded_ogp_location = 'Open Gym Premier, Anaheim, CA';
        } else {
          $hardcoded_ogp_location = $game_vals['VenueCourt']['Venue']['Name'];
        }
      $exposure_data_compile[] = "(" . implode(',', [
        'event_id'         => "'" . $event_id . "'",
        'court'            => "'" . $game_vals['VenueCourt']['Court']['Name'] . "'",
        'division'         => "'" . $game_vals['Division']['Name'] . "'",
        'start_time'       => "'" . $new_datetime . "'",
        'location'         => "'" . $hardcoded_ogp_location . "'",
        'home_team'        => "'" . $game_vals['HomeTeam']['ExternalId'] . "'",
        'home_team_score'  => ( !is_numeric($game_vals['HomeTeam']['Score']) ) ? 'null' : "'" . $game_vals['HomeTeam']['Score'] . "'",
        'away_team'        => "'" . $game_vals['AwayTeam']['ExternalId'] . "'",
        'away_team_score'  => ( !is_numeric($game_vals['AwayTeam']['Score']) ) ? 'null' : "'" . $game_vals['AwayTeam']['Score'] . "'",
        'exposure_game_id' => "'" . $game_vals['Id'] . "'",
        'bracket_name' => "'" . $game_vals['BracketName'] . "'"
      ]) . ")";
    }
    $post_results = $wpdb->query(
      'INSERT INTO '.$wpdb_games.'( event_id, court, division, start_time, location, home_team, home_team_score, away_team, away_team_score, exposure_game_id, bracket_name ) 
      VALUES ' . implode( ',', $exposure_data_compile ) . ' ON DUPLICATE KEY UPDATE court = VALUES(court), division = VALUES(division), start_time = VALUES(start_time), location = VALUES(location), home_team = VALUES(home_team), home_team_score = VALUES(home_team_score), away_team = VALUES(away_team), away_team_score = VALUES(away_team_score), bracket_name = VALUES(bracket_name);'
    );
  }
  return $post_results;
}

// Save games to specific SCIBCA event
function save_SCIBCA_exposure_game( $event_id ){
	global $wpdb;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_games = $wpdb->g365_games;
  if( !is_numeric($event_id) || $event_id == 0 ) return 'Need vaild Event ID.';
  $event_id = intval($event_id);
  $exposure_id   = $wpdb->get_var("SELECT JSON_UNQUOTE(JSON_EXTRACT(schedule_link, '$.exposure')) FROM $wpdb_events WHERE `id` = $event_id;");
  if( !is_numeric($exposure_id) || empty($exposure_id) ) return 'Exposure linkage not present.';
  //grab the exposure data
  $exposure_data = get_SCIBCA_exposure_data( 'api/v1/games', '', $exposure_id );
  $exposure_data_compile = array();
  $errors = [];
  if( $exposure_data ) {
    foreach( $exposure_data['Games']['Results'] as $dex => $game_vals ){
      if( empty($game_vals['HomeTeam']['ExternalId']) || empty($game_vals['AwayTeam']['ExternalId']) ) continue;
        $new_datetime = date('Y-m-d H:i:s', strtotime( $game_vals['Date'] . ' ' . $game_vals['Time'] ));
        // Hardcoded ogp location to avoid miss match location
        if( $game_vals['VenueCourt']['Venue']['Name'] === 'Open Gym Premier'){
          $hardcoded_ogp_location = 'Open Gym Premier, Anaheim, CA';
        } else {
          $hardcoded_ogp_location = $game_vals['VenueCourt']['Venue']['Name'];
        }
      $exposure_data_compile[] = "(" . implode(',', [
        'event_id'         => "'" . $event_id . "'",
        'court'            => "'" . $game_vals['VenueCourt']['Court']['Name'] . "'",
        'division'         => "'" . $game_vals['Division']['Name'] . "'",
        'start_time'       => "'" . $new_datetime . "'",
        'location'         => "'" . $hardcoded_ogp_location . "'",
        'home_team'        => "'" . $game_vals['HomeTeam']['ExternalId'] . "'",
        'home_team_score'  => ( !is_numeric($game_vals['HomeTeam']['Score']) ) ? 'null' : "'" . $game_vals['HomeTeam']['Score'] . "'",
        'away_team'        => "'" . $game_vals['AwayTeam']['ExternalId'] . "'",
        'away_team_score'  => ( !is_numeric($game_vals['AwayTeam']['Score']) ) ? 'null' : "'" . $game_vals['AwayTeam']['Score'] . "'",
        'exposure_game_id' => "'" . $game_vals['Id'] . "'",
        'bracket_name' => "'" . $game_vals['BracketName'] . "'"
      ]) . ")";
    }
    $post_results = $wpdb->query(
      'INSERT INTO '.$wpdb_games.'( event_id, court, division, start_time, location, home_team, home_team_score, away_team, away_team_score, exposure_game_id, bracket_name ) 
      VALUES ' . implode( ',', $exposure_data_compile ) . ' ON DUPLICATE KEY UPDATE court = VALUES(court), division = VALUES(division), start_time = VALUES(start_time), location = VALUES(location), home_team = VALUES(home_team), home_team_score = VALUES(home_team_score), away_team = VALUES(away_team), away_team_score = VALUES(away_team_score), bracket_name = VALUES(bracket_name);'
    );
  }
  return $post_results;
}

//update exposure teams
function update_exposure_team( $reqType, $reqTeamId, $eventId = null, $updateJSONData ){ // Required $reqTeamId
  $divisionAPI  = "api/v1/divisions"; // List("")
  $teamsAPI     = "api/v1/teams"; // List("")    
  $secretKey  = exposure_api_data()['api_secret_key']; // Exposure secret key
  $apiKey     = exposure_api_data()['api_key']; // Exposure api key
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&PUT&'.$timeStamp.'&/'.$reqType; // Update Signature format
  // Convert message to uppercase
  $upMes    = strtoupper( $message );
  // Encode as UTF8
  $secretKeyEn  = utf8_encode( $secretKey );
  $enUpMes      = utf8_encode( $upMes );
  // Base64 encode
  $encoded = base64_encode( hash_hmac( "sha256", $enUpMes, $secretKeyEn, true ) );
  if( !empty( $reqTeamId ) ){ // List; ".time()" to get latest API request 
    $api_url = 'https://basketball.exposureevents.com/'.$reqType;
  }
  if( empty( $reqTeamId) ){ // Specific details
    echo "Required Specific Team ID to make an update";
  }
  $updateTeam = wp_remote_request( $api_url, array(
    'method' => 'PUT',
    'timeout' => 10,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
      "Content-Type" => "application/json; charset=utf-8",
      "Timestamp" => $timeStamp,
      "Authentication" => $apiKey.".".$encoded
     ),
    'body' => $updateJSONData
  ));
  return $updateTeam;
}

//create divisions
function create_division_in_exposure( $postType, $eventId, $teamName, $divisionName, $g365RosterId, $postDiviJSONData ){
  $teamsAPI   = "api/v1/teams"; // List("")
  $divisionAPI  = "api/v1/divisions"; // List("")
  $secretKey  = exposure_api_data()['api_secret_key']; // Exposure secret key
  $apiKey     = exposure_api_data()['api_key']; // Exposure api key
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&POST&'.$timeStamp.'&/'.$postType; // Request type "POST"
  // Convert message to uppercase
  $upMes    = strtoupper( $message );
  // Encode as UTF8
  $secretKeyEn  = utf8_encode($secretKey);
  $enUpMes      = utf8_encode($upMes);
  // Base64 encode
  $encoded = base64_encode( hash_hmac( "sha256", $enUpMes, $secretKeyEn, true ) );
  if( !empty( $eventId ) ){ // List
    $division_api_url = 'https://basketball.exposureevents.com/'.$divisionAPI;   
    $team_api_url = 'https://basketball.exposureevents.com/'.$teamsAPI;   
  }else{
     echo "Need requested parameter.";
   }
  $existingDivId = get_exposure_data ( $divisionAPI, '', $eventId );
  $existingDivIdResults = $existingDivId['Divisions']['Results'];
  $divisionNewArray = array();
   foreach( $existingDivIdResults as $k => $v) {
    $divisionNewArray[$k] = $v;
   }
  $existingTeam = get_exposure_data( $teamsAPI, '', $eventId );
  $existingTeamIdResults = $existingTeam['Teams']['Results'];
  $teamNewArray = array();
   foreach( $existingTeamIdResults as $l => $x) {
    $teamNewArray[$l] = $x;
   }
//   $multiD = $divisionNewArray;
//   $singleD = array_reduce($divisionNewArray, 'array_merge', array());
  $simplifyMulDArr = join( ',', array_column( $divisionNewArray, 'Name' ) ); // Convert Multidimention Array to Name Column String
  $convertedExistingDiv = explode( ',', $simplifyMulDArr ); // Rearrange string back to array
    // Create Divison ID
  $attPostDiv = json_decode( $postDiviJSONData );
    if( empty( $convertedExistingDiv ) OR ( in_array( $attPostDiv->Name, $convertedExistingDiv ) == false ) ){ // If division is empty or does not exist, create the post division
      $createDivision = wp_remote_request( $division_api_url, array(
        'method' => 'POST',
        'timeout' => 10,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(
          "Content-Type" => "application/json; charset=utf-8",
          "Timestamp" => $timeStamp,
          "Authentication" => $apiKey.".".$encoded
         ),
        'body' => $postDiviJSONData
      ));
      $result = $createDivision['body'];
      $decodeResult = json_decode($result);
      $createdDivId = $decodeResult->Id;
      echo $attPostDiv->Name." Division is created with id: ".$createdDivId;
      if ( is_wp_error( $createDivision ) ) {
       $error_message = $createDivision->get_error_message();
       echo "Something went wrong with division creation: $error_message";
      } else {
      // If division is created without error, post team to that division
      // Replace ExternalTeamId with rosterId
        $postTeamJSONData = 
        '
          {
            "DivisionId": '.$createdDivId.',
            "Name": "'.$teamName.'",
            "ExternalTeamId": '.$g365RosterId.'
          }
        ';
        post_team_to_exposure( $teamsAPI, $eventId, $teamName, $postTeamJSONData );
//           echo $attPostDiv->Name." is created.";         
      }
    }
    if( in_array( $attPostDiv->Name, $convertedExistingDiv ) == true ){ // If division already exist, do not create division instead add team to that division
      // Get existing team(s)
      $postTeamJSONData = 
      '
        {
          "ExternalTeamId": '.$g365RosterId.'
        }
      ';
      $simplifyTeamMulDArr = join( ',', array_column( $teamNewArray, 'ExternalTeamId' ) ); // Convert Multidimention Array to External Team Id Column String
      $convertedExistingTeam = explode( ',', $simplifyTeamMulDArr ); // Rearrange string back to array
      $attPostTeam = json_decode( $postTeamJSONData ); // Attemp post team data
      $keyTeamId = array_search( $attPostTeam->ExternalTeamId, $convertedExistingTeam );
      $existingTeamId = $teamNewArray[$keyTeamId]['Id']; // Team id on exposure
//         if( in_array( $attPostTeam->ExternalTeamId, $convertedExistingTeam ) == true ){ // If team id is already existed then update that record
      if( in_array( $attPostTeam->ExternalTeamId, $convertedExistingTeam ) == true ){ // If team id is already existed then update that record
        update_exposure_team( $teamsAPI, $existingTeamId, $eventId,  
        '
          {
            "Id":'.$existingTeamId.',
            "Name": "'.$teamName.'",
          }
        '
        );
      }
      if( in_array( $attPostTeam->ExternalTeamId, $convertedExistingTeam ) == false ){ // If team id does not exist then add that record
        if ( false !== $keyDivisionId = array_search( $attPostDiv->Name, $convertedExistingDiv ) ) { // find duplicate array and get its id
          $existingKeyDiId = $divisionNewArray[$keyDivisionId]['Id'];
          post_team_to_exposure( $teamsAPI, $eventId, $teamName, '
            {
              "DivisionId": '.$existingKeyDiId.',
              "Name": "'.$teamName.'",
              "ExternalTeamId": "'.$g365RosterId.'"
            }
          '    
          );
          echo $attPostDiv->Name." is added to existing division.";
        } else {
          echo "Mismatch key id";
        }  
      }       
    }  
}

//post a team's data to exposure
function post_team_to_exposure( $postType, $eventId, $teamName, $postTeamJSONData ){
  $teamsAPI   = "api/v1/teams"; // List("")
  $secretKey  = exposure_api_data()['api_secret_key']; // Exposure secret key
  $apiKey     = exposure_api_data()['api_key']; // Exposure api key
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&POST&'.$timeStamp.'&/'.$postType; // Request type "POST"
  // Convert message to uppercase
  $upMes    = strtoupper($message);
  // Encode as UTF8
  $secretKeyEn  = utf8_encode($secretKey);
  $enUpMes      = utf8_encode($upMes);
  // Base64 encode
  $encoded = base64_encode( hash_hmac( "sha256", $enUpMes, $secretKeyEn, true ) );
  $includePools = "&includes=teampools&";
   if( !empty( $eventId ) ){ // List
    $team_api_url     = 'https://basketball.exposureevents.com/api/v1/teams';     
  }else{
     echo "Need requested parameter.";
   }
  $createTeam = wp_remote_request( $team_api_url, array(
    'method' => 'POST',
    'timeout' => 10,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
      "Content-Type" => "application/json; charset=utf-8",
      "Timestamp" => $timeStamp,
      "Authentication" => $apiKey.".".$encoded
     ),
    'body' => $postTeamJSONData
  ));
  if ( is_wp_error( $createTeam ) ) {
     $error_message = $createTeam->get_error_message();
     echo "Something went wrong with creating team: $error_message";
  }
  return $createTeam;
}


//post game data to exposure
function post_game_data_to_exposure( $postType, $postId, $eventId = null, $postJSONData = null, $args = null ){
  $secretKey  = exposure_api_data()['api_secret_key']; // Exposure secret key
  $apiKey     = exposure_api_data()['api_key']; // Exposure api key
  if($args['remote_exposure'] === true){
    $secretKey  = exposure_api_data($args['type'], ['remote_exposure'=>true])['api_secret_key']; // Exposure secret key
    $apiKey     = exposure_api_data($args['type'], ['remote_exposure'=>true])['api_key']; // Exposure api key
  }
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&PUT&'.$timeStamp.'&/'.$postType; // Request type PUT
  // Convert message to uppercase
  $upMes    = strtoupper($message);
  // Encode as UTF8
  $secretKeyEn  = utf8_encode($secretKey);
  $enUpMes      = utf8_encode($upMes);
  // Base64 encode
  $encoded = base64_encode(hash_hmac("sha256", $enUpMes, $secretKeyEn, true));
   if( !empty($postId) ){ // List
    $api_url = 'https://basketball.exposureevents.com/'.$postType.'?id='.$postId.'&eventid='.$eventId.$apiKeyExt;
  }
  if( empty($postId) ){ // Specific details
    $api_url ='https://basketball.exposureevents.com/'.$postType.'?eventid='.$eventId.$apiKeyExt;     
  }
//     echo $api_url;
  $response = wp_remote_request( $api_url, array(
    'method' => 'PUT',
    'timeout' => 10,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
      "Content-Type" => "application/json; charset=utf-8",
      "Timestamp" => $timeStamp,
      "Authentication" => $apiKey.".".$encoded
     ),
    'body' => $postJSONData
  ));
//   if( empty($postData) ){
//     echo "Missing update data";
//   }
  if ( is_wp_error( $response ) ) {
     $error_message = $response->get_error_message();
     echo "Something went wrong: $error_message";
  } else {
//      echo 'Response:<pre>';
//       print_r( $response );
//      echo '</pre>';
  }
  return $response; 
} 

//post game data to SCIBCA exposure
function post_game_data_to_SCIBCA_exposure( $postType, $postId, $eventId = null, $postJSONData = null, $args = null ){
  $apiKey     = 'la93cD9Qjon9Ij'; // Exposure api key
  $secretKey  = 'yi93cJ05scMf9Mf9cvg9ZGOfczO9BlyY'; // Exposure secret key
  if($args['remote_exposure'] === true){
    $apiKey     = 'la93cD9Qjon9Ij'; // Exposure api key
    $secretKey  = 'yi93cJ05scMf9Mf9cvg9ZGOfczO9BlyY'; // Exposure secret key
  }
  $apiKeyExt  = '&apikey='.$apiKey;
  $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
  $message    = $apiKey.'&PUT&'.$timeStamp.'&/'.$postType; // Request type PUT
  // Convert message to uppercase
  $upMes    = strtoupper($message);
  // Encode as UTF8
  $secretKeyEn  = utf8_encode($secretKey);
  $enUpMes      = utf8_encode($upMes);
  // Base64 encode
  $encoded = base64_encode(hash_hmac("sha256", $enUpMes, $secretKeyEn, true));
   if( !empty($postId) ){ // List
    $api_url = 'https://basketball.exposureevents.com/'.$postType.'?id='.$postId.'&eventid='.$eventId.$apiKeyExt;
  }
  if( empty($postId) ){ // Specific details
    $api_url ='https://basketball.exposureevents.com/'.$postType.'?eventid='.$eventId.$apiKeyExt;     
  }
//     echo $api_url;
  $response = wp_remote_request( $api_url, array(
    'method' => 'PUT',
    'timeout' => 10,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
      "Content-Type" => "application/json; charset=utf-8",
      "Timestamp" => $timeStamp,
      "Authentication" => $apiKey.".".$encoded
     ),
    'body' => $postJSONData
  ));
//   if( empty($postData) ){
//     echo "Missing update data";
//   }
  if ( is_wp_error( $response ) ) {
     $error_message = $response->get_error_message();
     echo "Something went wrong: $error_message";
  } else {
//      echo 'Response:<pre>';
//       print_r( $response );
//      echo '</pre>';
  }
  return $response; 
} 

//   function post_team_to_exposure( $postType, $postDivisiontId, $postJSONData ){
//     global $venuesAPI, $gamesAPI, $teamsAPI;
//     $venuesAPI    = "api/v1/venues"; // List(?eventid), Venue(?id&eventid)
//     $gamesAPI     = "api/v1/games"; // List(?eventid), Game(?id&eventid)
//     $teamsAPI     = "api/v1/teams"; // List("")
//     $secretKey  = "92920pj99D98nGZU6to99ApbtS99R2tC"; // Exposure secret key
//     $apiKey     = "GWJ99E99WI9UXT"; // Exposure api key
//     $apiKeyExt  = '&apikey='.$apiKey;
//     $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
//     $message    = $apiKey.'&POST&'.$timeStamp.'&/'.$postType; // Request type POST
//     // Convert message to uppercase
//     $upMes    = strtoupper($message);
//     // Encode as UTF8
//     $secretKeyEn  = utf8_encode($secretKey);
//     $enUpMes      = utf8_encode($upMes);
//     // Base64 encode
//     $encoded = base64_encode( hash_hmac( "sha256", $enUpMes, $secretKeyEn, true ) );
//      if( !empty( $postDivisiontId ) ){ // List
//       $api_url ='https://basketball.exposureevents.com/'.$postType;
//     }else{
//        echo "Need requested parameter.";
//      }
//   //     echo $api_url;
//     $response = wp_remote_request( $api_url, array(
//       'method' => 'POST',
//       'timeout' => 10,
//       'redirection' => 5,
//       'httpversion' => '1.0',
//       'blocking' => true,
//       'headers' => array(
//         "Content-Type" => "application/json; charset=utf-8",
//         "Timestamp" => $timeStamp,
//         "Authentication" => $apiKey.".".$encoded
//        ),
//       'body' => $postJSONData
//     ));
//     if ( is_wp_error( $response ) ) {
//        $error_message = $response->get_error_message();
//        echo "Something went wrong: $error_message";
//     } else {
//        echo 'Response:<pre>';
//         print_r( $response );
//        echo '</pre>';
//     }
//     return $response; 
//   }
//   function create_division_in_exposure( $postType, $eventId, $teamName, $postDiviJSONData ){
//     $teamsAPI   = "api/v1/teams"; // List("")
//     $divisionAPI  = "api/v1/divisions"; // List("")
//     $secretKey  = "92920pj99D98nGZU6to99ApbtS99R2tC"; // Exposure secret key
//     $apiKey     = "GWJ99E99WI9UXT"; // Exposure api key
//     $apiKeyExt  = '&apikey='.$apiKey;
//     $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
//     $message    = $apiKey.'&POST&'.$timeStamp.'&/'.$postType; // Request type "POST"
//     // Convert message to uppercase
//     $upMes    = strtoupper($message);
//     // Encode as UTF8
//     $secretKeyEn  = utf8_encode($secretKey);
//     $enUpMes      = utf8_encode($upMes);
//     // Base64 encode
//     $encoded = base64_encode( hash_hmac( "sha256", $enUpMes, $secretKeyEn, true ) );
//     if( !empty( $eventId ) ){ // List
//       $division_api_url = 'https://basketball.exposureevents.com/'.$divisionAPI;   
//     }else{
//        echo "Need requested parameter.";
//      }
//     $existingDivId = get_exposure_data ($divisionAPI, '', $eventId);
//     $existingDivIdResults = $existingDivId['Divisions']['Results'];
//     $newArray = array();
//      foreach( $existingDivIdResults as $k => $v) {
//       $newArray[$k] = $v;
//      }
// //     print_r($newArray);
// //     $multiD = $newArray;
// //     $singleD = array_reduce($newArray, 'array_merge', array());
//     $simplifyMulDArr = join(',', array_column($newArray, 'Name')); // Convert Multidimention Array to Name Column String
//     $convertedExistingDiv = explode(',', $simplifyMulDArr); // Rearrange string back to array
//       // Create Divison ID
//     $attPostDiv = json_decode($postDiviJSONData);
// //     print_r($existingDivId);
// //     print_r($simplifyMulDArr);
// //     print_r($newArray);
// //     print_r($existingDivId);
// //     print_r($attPostDiv->Name);
// //     print_r($convertedExistingDiv);
//       if( empty($convertedExistingDiv) OR ( in_array($attPostDiv->Name, $convertedExistingDiv) == false ) ){ // If division is empty or does not exist, create the post division
//         $createDivision = wp_remote_request( $division_api_url, array(
//           'method' => 'POST',
//           'timeout' => 10,
//           'redirection' => 5,
//           'httpversion' => '1.0',
//           'blocking' => true,
//           'headers' => array(
//             "Content-Type" => "application/json; charset=utf-8",
//             "Timestamp" => $timeStamp,
//             "Authentication" => $apiKey.".".$encoded
//            ),
//           'body' => $postDiviJSONData
//         ));
//         $result = $createDivision['body'];
//         $decodeResult = json_decode($result);
//         $createdDivId = $decodeResult->Id;
// //         echo $attPostDiv->Name." Division is created with id: ".$createdDivId;
//       } 
//       if ( is_wp_error( $createDivision ) ) {
//          $error_message = $createDivision->get_error_message();
//          echo "Something went wrong with division creation: $error_message";
//       } else {
//         // If division is created without error, post team to that division
//           post_team_to_exposure( $teamsAPI, $eventId, $teamName,  '
//             {
//               "DivisionId": '.$createdDivId.',
//               "Name": "'.$teamName.'"
//             }
//           '    
//           );
//       }
//       if( in_array( $attPostDiv->Name, $convertedExistingDiv ) == true ){ // If division already exist, dont create division instead add team to that division
//         if ( false !== $key = array_search( $attPostDiv->Name, $convertedExistingDiv ) ) { // find duplicate array and get its id
//           $existingKeyDiId = $newArray[$key]['Id'];
//           post_team_to_exposure( $teamsAPI, $eventId, $teamName,  '
//             {
//               "DivisionId": '.$existingKeyDiId.',
//               "Name": "'.$teamName.'"
//             }
//           '    
//           );
// //           echo $attPostDiv->Name." is added to existing division.";
//         } else {
//           echo "Mismatch key id";
//         }        
//       }  
//   }
//   function post_team_to_exposure( $postType, $eventId, $teamName, $postTeamJSONData ){
//     $teamsAPI     = "api/v1/teams"; // List("")
//     $secretKey  = "92920pj99D98nGZU6to99ApbtS99R2tC"; // Exposure secret key
//     $apiKey     = "GWJ99E99WI9UXT"; // Exposure api key
//     $apiKeyExt  = '&apikey='.$apiKey;
//     $timeStamp  = date('Y-m-d\TH:i:s.Z\Z', time()); // Current timestamp
//     $message    = $apiKey.'&POST&'.$timeStamp.'&/'.$postType; // Request type "POST"
//     // Convert message to uppercase
//     $upMes    = strtoupper($message);
//     // Encode as UTF8
//     $secretKeyEn  = utf8_encode($secretKey);
//     $enUpMes      = utf8_encode($upMes);
//     // Base64 encode
//     $encoded = base64_encode( hash_hmac( "sha256", $enUpMes, $secretKeyEn, true ) );
//      if( !empty( $eventId ) ){ // List
//       $team_api_url     = 'https://basketball.exposureevents.com/'.$teamsAPI;     
//     }else{
//        echo "Need requested parameter.";
//      }
//     $createTeam = wp_remote_request( $team_api_url, array(
//       'method' => 'POST',
//       'timeout' => 10,
//       'redirection' => 5,
//       'httpversion' => '1.0',
//       'blocking' => true,
//       'headers' => array(
//         "Content-Type" => "application/json; charset=utf-8",
//         "Timestamp" => $timeStamp,
//         "Authentication" => $apiKey.".".$encoded
//        ),
//       'body' => $postTeamJSONData
//     ));
//     if ( is_wp_error( $createTeam ) ) {
//        $error_message = $createTeam->get_error_message();
//        echo "Something went wrong with creating team: $error_message";
//     }
//     return $createTeam;
//   }


?>