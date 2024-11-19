<?php 
function import_data(){
	echo '<h1>Import result</h1>';
	echo '<p>Pulls from "plugins_url( \'g365-data-manager/import/\' )". Accepted files names:</p>';
	print(
	"<pre>
	g365_orgs		= 'g365_orgs.csv'
	g365_players		= 'g365_players.csv'
	g365_events		= 'g365_events.csv'
	g365_awards		= 'g365_awards.csv'
	g365_stats		= 'g365_stats.csv'
	g365_coaches		= 'g365_coaches.csv'
	g365_rosters		= 'g365_rosters.csv'
	g365_teams		= 'g365_teams.csv'
	g365_rankings		= 'g365_rankings.csv'
	</pre>"
	);
	$type = filter_input( INPUT_GET, 'g_import', FILTER_SANITIZE_URL );
	//see if we have a file before anything.
	$filenames = array(
		'g365_orgs'			=> 'g365_orgs.csv',
		'g365_players'	=> 'g365_players.csv',
		'g365_events'		=> 'g365_events.csv',
		'g365_awards'		=> 'g365_awards.csv',
		'g365_stats'		=> 'g365_stats.csv',
		'g365_coaches'	=> 'g365_coaches.csv',
		'g365_rosters'	=> 'g365_rosters.csv',
		'g365_teams'		=> 'g365_teams.csv',
		'g365_rankings'	=> 'g365_rankings.csv',
	);
	$file_to_get = plugins_url( 'g365-data-manager/import/' ) . $filenames[$type];
	$file_path = get_home_path() . 'wp-content/plugins/g365-data-manager/import/' . $filenames[$type];
	echo $file_path;
	if( !file_exists( $file_path ) ) return 'Import file "' . $file_to_get . '" does not exist.';
	$csv = array_map('str_getcsv', file( $file_to_get ) );
	if( empty($csv) ) return "No file/empty file. Please check your file location and contents, then try again.";
	//we have a non-empty file, attempt to add records
	
	//provide column headers so we don't pull it with each loop
	$col_names_arr = g365_table_col_names($type);
	$col_name_keys = array_keys($col_names_arr);

	$update_status_list = array();
	foreach($csv as $dex => $row){
		//If the first field  
		if( strtolower($row[0]) === 'id' ) continue;
		//make associative array to pass to wpdb insert statement
		$row_arr = array();
		foreach($col_names_keys as $dex => $name){
			//run all data through universal sanitizer
			$data_process = g365_process_data_point($name,$row[$dex]);
			if( $data_process !== false ) $row_arr[$name] = $data_process;
		}
		$update_status_list[] = g365_add_record($type,$row_arr);
	}
	echo '<pre>';
	print_r( $update_status_list );
	echo '</pre>';
}
