<?php
/*
 *	Plugin Name: G365 Data Management Framework
 *	Plugin URI: http://grassroots365.com/contact
 *	Description: Contains all functionality to support Grassroots 365 Data Management.
 *	Version: 1.0
 *	Author: Ben Webb
 *	Author URI: http://ideawebb.com
 *	License: None
 */

add_action( 'plugins_loaded', 'g365_handle_preflight' );
function g365_handle_preflight() {
	if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) {
		//this json file exits if the above is true
    require( 'inc/json-response-headers.php' );
    exit();
	}
}

add_filter( 'allowed_http_origins', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
  $origins[] = 'https://grassroots365.com';
  $origins[] = 'https://opengympremier.com';
  $origins[] = 'https://elitebasketballcircuit.com';
  $origins[] = 'https://thestagecircuit.com';
  $origins[] = 'http://hypeherhoopscircuit.com';
  $origins[] = 'https://sportspassports.com';
  $origins[] = 'https://scholasticseries.com';
  $origins[] = 'https://scopescouting.com';
  $origins[] = 'http://thrivebasketballleague.com';
  $origins[] = 'https://theundergroundcircuit.com';
  $origins[] = 'https://dev.grassroots365.com';
  $origins[] = 'https://dev.opengympremier.com';
  $origins[] = 'https://dev.elitebasketballcircuit.com';
  $origins[] = 'https://dev.thestagecircuit.com';
  $origins[] = 'http://dev.hypeherhoopscircuit.com';
  $origins[] = 'https://dev.sportspassports.com';
  $origins[] = 'https://dev.scholasticseries.com';
  $origins[] = 'https://dev.scopescouting.com';
  $origins[] = 'http://dev.thrivebasketballleague.com';
  $origins[] = 'https://dev.theundergroundcircuit.com';
  return $origins;
}

//include admin functions
if( is_admin() ) {
	require( 'inc/install.php' );
	register_activation_hook( __FILE__, 'g365_data_install' );
	require( 'inc/import.php' );
}

//confirm the id string is only numbers
function g365_validate_ids( $resource_ids ) {
	//check resource id
	if( gettype($resource_ids) === 'string' ) {
    $json_tester = json_decode($resource_ids);
    $resource_ids = ( $json_tester === null ) ? explode('|', $resource_ids) : $json_tester;
  }
	if( !is_array($resource_ids) || empty($resource_ids) ) return null;
  $resource_ids = array_map( function($resource_id){ return ( !is_numeric( $resource_id ) || empty( $resource_id ) ) ? null : intval($resource_id); }, $resource_ids);
	if( in_array( null, $resource_ids) ) return null;
	return $resource_ids;
}

//convert the form type string to number
function g365_validate_data_type( $form_types ) {

	//form type options
	$form_type_opt = array(
    'pl', //1
    'ev', //2
    'org', //3
    'team_names', //4
    'pl_eval', //5
    'pl_ev', //6
    'player_names', //7
    'rosters_event', //8
    'rosters', //9
    'school_names', //10
    'club_names', //11
    'coach_names', //12
    'player_event', //13
    'pl_cert', //14
    'club_team', //15
    'camps', //16
    'rosters_teams', //17
    'training', //18
    'pl_cert_sl', //19
    'player_names_admin', //20
    'event_names', //21
    'team_rankings', //22
    'player_watchlist', //23
    'pl_ed', //24
    'og_ed', //25
    'ro_ed', //26
    'player_event_admin', //27
    'cps_manager', //28
    'event_details', //29
    'club_names_admin', //30
    'co_ed', //31
    'tournament_roster_admin', //32
    'to_ed', //33
    'gm_st', //34
    'player_admin', //35
    'claiming', //36
    'player_rosters', //37
    'player_rosters_admin', //38
    'rosters_sl', //39
    'rosters_club_sl', //40
    'team_names_sl', //41
    'rosters_sl_xl', //42
    'passport', //43
    'gm_save', //44
    'attendance', //45
    'rosters_teams_admin', //46
		'player_award', //47
    'dcp_player_registration', //48
    'photo_player_claim', //49
    'claiming_delete', //50
    'awards_upload', //51
    'hhh_player_event_admin', //52
    'team_event', //53
    'ss_player_event', //54
    'ss_player_event_admin', //55
    'ss_team_event_admin', //56
    'hhh_team_event_admin', //57
    'player_status', //58
  );
	//convert string to array based on "|" delimiter
	$form_types = ( gettype($form_types) == 'string' ) ? explode('|', $form_types) : $form_types;
	$form_type_process = array();
	foreach( $form_types as $dex => $type ) {
		$type_key = array_search( $type, $form_type_opt );
		//if not found return null
		$form_type_process[$type] = (( is_int( $type_key ) ) ? ++$type_key : null);
	}
	//correct array index to the switch function case numbers
	return $form_type_process;
}

//create level labels
function g365_level_key( $level, $short = true ) {
  return g365_return_keys( (($short) ? 'g365_grade_key_short' : 'g365_grade_key' ) )[intval($level)];
}

//calculate roster lock values
//roster level, all event divisions json string, roster division (gold, silver, etc..)
function g365_roster_lock_vals($roster_level, $event_division, $roster_division) {
  $return_vals = array();
  $today = date("Y-m-d");
  $cont_target_year = (date('Y', strtotime($today)) - (( intval(date('n', strtotime($today))) > 8 ) ? 0 : 1 ));
  $level_mapping = [
    "8" => "8",
    "9" => "9",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "47" => "17",
    "46" => "16",
    "45" => "15",
    "44" => "14",
    "43" => "13",
    "42" => "12",
    "41" => "11",
    "40" => "10",
  ];

  if (isset($level_mapping[$roster_level])) {
      $roster_level = $level_mapping[$roster_level];
  }
//   if($roster_level < 15 || $roster_level > 16) {
  if($roster_level < 18) {
    $birth_lock = ($cont_target_year - $roster_level) . "-08-15";
    $return_vals['division_selector_birth_lock'] = "> '" . $birth_lock . "'-OR";
  }
  $return_vals['division_selector_class_lock'] = '> ' . ($cont_target_year + 18 - $roster_level) . '-OR';
  if( empty($event_division) || $event_division === 0 ) {
    $return_vals['division_selector_lock_type'] = 0;
  } else {
    $return_vals['event_division'] = json_decode($event_division);
    $return_vals['division_selector_lock_type'] = ( ( is_object($event_division) ) ? $event_division->{$roster_division} : 1 );
  }
  return $return_vals;
}


//output db errors
function g365_output_db_error($message){
  global $wpdb;
  if($wpdb->last_error !== '') {
    $err = $wpdb->last_error;
//     return "$message<br><small>$err</small>";
    return "$message<br>";
  } else {
    return "$message<br><small>Error message not found.</small>";
  }
}

//output db errors
function cj_g365_output_db_error($message){
  global $wpdb;
  if($wpdb->last_error !== '') {
    $err = $wpdb->last_error;
    return "$message<br><small>$err</small>";
  } else {
    return "$message<br><small></small>";
  }
}

function g365_return_keys( $key_type ) {
  switch( $key_type ) {
    case 'findus_options_key':
      return array(
        ''          => 'Please select',
        'friend'    => 'A friend',
        'google'    => 'Google',
        'o_website' => 'Our Website',
        'grpon'     => 'Groupon',
        'lnkdin'    => 'LinkedIn',
        'instag'    => 'Instagram',
        'faceb'     => 'Facebook',
        'twitt'     => 'Twitter',
        'indeed'    => 'Indeed',
        'walk_in'   => 'Walk-In'
      );
      break;
    case 'g365_cat_form_key': //[0] data_type for category, [1] data_target for management
      return array(array(
//         'tournaments' => 'rosters_event',
        'camps' => 'camps',
        'passport' => 'passport',
        'dcp' => 'dcp',
        'player-membership' => 'pl_ev',
        'club-teams' => 'club_team',
        'training' => 'club_team',
        'leagues' => 'club_team'
      ),array(
//         'tournaments' => 'event_id',
        'camps' => 'event_id_cp',
        'passport' => 'event_id_cp',
        'dcp_player_registration' => 'event_id_cp',
        'player-membership' => 'event_id_pm',
        'club-teams' => 'event_id_ct',
        'training' => 'event_id_ct',
        'leagues' => 'event_id_ct'
      ));
      break;
    case 'g365_url_form_key': //[0] data_type for url, [1] data_target for management
      return array(array(
        'tournaments' => 'rosters_teams',
        'camps' => 'player_event', 
        'passport' => 'player_event',
        'dcp_player_registration' => 'dcp_player_registration',
        'player-certification' => 'pl_cert',
        'club-teams' => 'rosters_teams',
        'training' => 'player_event', 
        'leagues' => 'player_event',
        'college-placement' => 'player_event',
        'coaches' => 'coach_names',
        'rosters' => 'rosters',
        'clubs' => 'club_names'
      ),array(
        'tournaments' => 'event_id',
        'camps' => 'event_id_cp',
        'passport' => 'event_id_cp',
        'dcp_player_registration' => 'event_id_cp',
        'player-certification' => 'event_id_pm',
        'club-teams' => 'event_id',
        'training' => 'event_id_pm',
        'leagues' => 'event_id_pm',
        'college-placement' => 'event_id_pm',
        'coaches' => 'coach_id',
        'rosters' => 'event_id',
        'clubs' => 'club_id'
      ));
      break;
    case 'g365_claim_type_key':
      return array(
        'pl' => 1,
        'org' => 2,
        'pl_eval' => 1,
        'pl_ev' => 1,
        'player_names' => 1,
        'rosters_event' => 2,
        'rosters' => 2,
        'school_names' => 2,
        'club_names' => 2,
        'coach_names' => 3,
        'player_event' => 1,
        'pl_cert' => 1,
        'club_team' => 2,
        'camps' => 1,
        'rosters_teams' => 2,
        'training' => 1,
        'pl_cert_sl' => 1,
        'player_names_admin' => 1,
        'pl_ed' => 1,
        'og_ed' => 2,
        'ro_ed' => 2,
        'player_event_admin' => 1,
        'club_names_admin' => 2,
        'co_ed' => 2,
        'tournament_roster_admin' => 2,
        'to_ed' => 2,
				'player_award' => 1,
        'dcp_player_registration' => 1,
        'claiming_delete' => 2,
        'hhh_player_event_admin' => 1
      );
      break;
    case 'g365_grade_key_short':
      return array(
        1 => "Lvl 1",
        2 => "Lvl 2",
        3 => "Lvl 3",
        4 => "Lvl 4",
        5 => "Lvl 5",
        6 => "Lvl 6",
        7 => "Lvl 7",
        8 => "8U",
        9 => "9U",
        10 => "10U",
        11 => "11U",
        12 => "12U",
        13 => "13U",
        14 => "14U",
        15 => "15U",
        16 => "16U",
        17 => "17U",
        18 => "18U",
        19 => "Lvl 19",
        20 => "Lvl 20",
        21 => "Lvl 21",
        22 => "Lvl 22",
        23 => "Lvl 23",
        24 => "Lvl 24",
        25 => "Lvl 25",
        26 => "Lvl 26",
        27 => "Lvl 27",
        28 => "Lvl 28",
        29 => "Lvl 29",
        30 => "Lvl 30",
        31 => "Lvl 1G",
        32 => "Lvl 2G",
        33 => "Lvl 3G",
        34 => "Lvl 4G",
        35 => "Lvl 5G",
        36 => "Lvl 6G",
        37 => "Lvl 7G",
        38 => "Lvl 8G",
        39 => "Lvl 9G",
        40 => "Girls 4th",
        41 => "Girls 5th",
        42 => "Girls 6th",
        43 => "Girls 7th",
        44 => "Girls 8th",
        45 => "Girls 9th",
        46 => "Girls 10th",
        47 => "Girls 11th",
        48 => "Lvl 18G",
        49 => "Lvl 19G",
        50 => "Lvl 20G",
        51 => "Lvl 21G",
        52 => "Lvl 22G",
        53 => "Lvl 23G",
        54 => "Lvl 24G",
        55 => "Lvl 25G",
        56 => "Lvl 26G",
        57 => "Lvl 27G",
        58 => "Lvl 28G",
        59 => "Lvl 29G",
        60 => "Lvl 30G",
        74 => "Frosh/Soph",
        75 => "JV",
        76 => "Varsity",
      );
      break;
    case 'g365_grade_key':
      return array(
        1 => "Level 1",
        2 => "Level 2",
        3 => "Level 3",
        4 => "Level 4",
        5 => "Level 5",
        6 => "Level 6",
        7 => "Level 7",
        8 => "8U/2nd Grade",
        9 => "9U/3rd Grade",
        10 => "10U/4th Grade",
        11 => "11U/5th Grade",
        12 => "12U/6th Grade",
        13 => "13U/7th Grade",
        14 => "14U/8th Grade",
        15 => "15U/9th Grade",
        16 => "16U/10th Grade",
        17 => "17U/11th Grade",
        18 => "Level 18",
        19 => "Level 19",
        20 => "Level 20",
        21 => "Level 21",
        22 => "Level 22",
        23 => "Level 23",
        24 => "Level 24",
        25 => "Level 25",
        26 => "Level 26",
        27 => "Level 27",
        28 => "Level 28",
        29 => "Level 29",
        30 => "Level 30",
        31 => "Level 1G",
        32 => "Level 2G",
        33 => "Level 3G",
        34 => "Level 4G",
        35 => "Level 5G",
        36 => "Level 6G",
        37 => "Level 7G",
        38 => "Level 8G",
//         39 => "Level 9G",
        39 => "Girls 3rd Grade",
        40 => "Girls 4th Grade",
        41 => "Girls 5th Grade",
        42 => "Girls 6th Grade",
        43 => "Girls 7th Grade",
        44 => "Girls 8th Grade",
        45 => "Frosh/Soph Girls",
        46 => "JV Girls",
        47 => "Varsity Girls",
        48 => "Level 18G",
        49 => "Level 19G",
        50 => "Level 20G",
        51 => "Level 21G",
        52 => "Level 22G",
        53 => "Level 23G",
        54 => "Level 24G",
        55 => "Level 25G",
        56 => "Level 26G",
        57 => "Level 27G",
        58 => "Level 28G",
        59 => "Level 29G",
        60 => "Level 30G",
        61 => "12U / 6th Grade and 13U / 7th Grade",
        62 => "9U / 3rd Grade and 10U / 4th Grade",
        63 => "8U / 2nd Grade and 9U / 3rd Grade",
        64 => "Girls 6th Grade and Girls 7th Grade",
        65 => "Girls 5th Grade and Girls 6th Grade",
        66 => "Girls 7th Grade and Girls 8th Grade",
        67 => "JV West",
        68 => "JV East",
        69 => "HS Girls Gold",
        70 => "14U Girls / 8th Grade",
        71 => "13 Gold",
        72 => "13 Silver/Bronze",
        73 => "Girls 3rd Grade",
        74 => "Frosh/Soph",
        75 => "JV",
        76 => "Varsity",
      );
      break;
      case 'g365_all_tournament_grade_key':
      return array(
        1 => "Level 1",
        2 => "Level 2",
        3 => "Level 3",
        4 => "Level 4",
        5 => "Level 5",
        6 => "Level 6",
        7 => "Level 7",
        8 => "8U / 2nd Grade",
        9 => "8U / 2nd Grade and 9U / 3rd Grade",
        10 => "9U / 3rd Grade",
        11 => "9U / 3rd Grade and 10U / 4th Grade",
        12 => "10U / 4th Grade",
        13 => "10U / 4th Grade and 11U / 5th Grade",
        14 => "11U / 5th Grade",
        15 => "11U / 5th Grade and 12U / 6th Grade",
        16 => "12U / 6th Grade",
        17 => "12U / 6th Grade and 13U / 7th Grade",
        18 => "13U / 7th Grade",
        19 => "13U / 7th Grade and 14U / 8th Grade",
        20 => "14U / 8th Grade",
        21 => "14U / 8th Grade and 15U / Frosh/Soph",
        22 => "15U / Frosh/Soph",
        23 => "15U / Frosh/Soph and 16U / JV",
        24 => "16U / JV",
        25 => "16U / JV and 17U / Varsity",
        26 => "17U / Varsity",
        27 => "Level 18",
        28 => "Level 19",
        29 => "Level 20",
        30 => "Level 21",
        31 => "Level 22",
        32 => "Level 23",
        33 => "Level 24",
        34 => "Level 25",
        35 => "Level 26",
        36 => "Level 27",
        37 => "Level 28",
        38 => "Level 29",
        39 => "Level 30",
        40 => "Level 1G",
        41 => "Level 2G",
        42 => "Level 3G",
        43 => "Level 4G",
        44 => "Level 5G",
        45 => "Level 6G",
        46 => "Level 7G",
        47 => "Level 8G",
        48 => "Level 9G",
        49 => "8U Girls / 2nd Grade",
        50 => "8U Girls / 2nd Grade and 9U Girls / 3rd Grade",
        51 => "9U Girls / 3rd Grade",
        52 => "9U Girls / 3rd Grade and 10U Girls / 4th Grade",
        53 => "10U Girls / 4th Grade",
        54 => "10U Girls / 4th Grade and 11U Girls / 5th Grade",
        55 => "11U Girls / 5th Grade",
        56 => "11U Girls / 5th Grade and 12U Girls / 6th Grade",
        57 => "12U Girls / 6th Grade",
        58 => "12U Girls / 6th Grade and 13U Girls / 7th Grade",
        59 => "13U Girls / 7th Grade",
        60 => "13U Girls / 7th Grade and 14U Girls / 8th Grade",
        61 => "14U Girls / 8th Grade",
        62 => "14U Girls / 8th Grade and 15U Girls / Frosh/Soph",
        63 => "15U Girls / Frosh/Soph",
        64 => "15U Girls / Frosh/Soph and 16U Girls / JV",
        65 => "16U Girls / JV",
        66 => "16U Girls / JV and 17U Girls / Varsity",
        67 => "17U Girls / Varsity",
        68 => "Girls 4th Grade",
        69 => "Girls 4th Grade and Girls 5th Grade",
        70 => "Girls 5th Grade",
        71 => "Girls 5th Grade and Girls 6th Grade",
        72 => "Girls 6th Grade",
        73 => "Girls 6th Grade and Girls 7th Grade",
        74 => "Girls 7th Grade",
        75 => "Girls 7th Grade and Girls 8th Grade",
        76 => "Girls 8th Grade",
        77 => "Girls 8th Grade and Frosh/Soph Girls",
        78 => "Frosh/Soph Girls",
        79 => "Frosh/Soph Girls and JV Girls",
        80 => "JV Girls",
        81 => "JV Girls and Varsity Girls",
        82 => "Varsity Girls",
        83 => "HS Girls Gold",
        84 => "Level 18G",
        85 => "Level 19G",
        86 => "Level 20G",
        87 => "Level 21G",
        88 => "Level 22G",
        89 => "Level 23G",
        90 => "Level 24G",
        91 => "Level 25G",
        92 => "Level 26G",
        93 => "Level 27G",
        94 => "Level 28G",
        95 => "Level 29G",
        96 => "Level 30G",
        97 => "JV West",
        98 => "JV East",
        99 => "13 Gold",
        100 => "13 Silver/Bronze",
        101 => "15U Gold",
        102 => "16U Gold",
        103 => "17U Gold",
        104 => "17U MLK",
        105 => "17U Barrack Obama",
        106 => "16U Rosa Parks",
        107 => "16U Harriett Tubman",
        108 => "15U Maya Angelou",
        109 => "15U Frederick Douglas",
        110 => "12U Circuit",
        111 => "13U Circuit",
        112 => "14U Circuit",
        113 => "15U Showcase",
        114 => "16U Showcase",
        115 => "17U Showcase",
        116 => "15U High School",
        117 => "16U High School",
        118 => "17U High School",
        119 => "9U Gold",
        120 => "9U Gold/Silver",
        121 => "9U Silver",
        122 => "9U Silver/Bronze",
        123 => "9U Bronze",
        124 => "10U Gold",
        125 => "10U Gold/Silver",
        126 => "10U Silver",
        127 => "10U Silver/Bronze",
        128 => "10U Bronze",
        129 => "11U Gold",
        130 => "11U Gold/Silver",
        131 => "11U Silver",
        132 => "11U Silver/Bronze",
        133 => "11U Bronze",
        134 => "12U Gold",
        135 => "12U Gold/Silver",
        136 => "12U Silver",
        137 => "12U Silver/Bronze",
        138 => "12U Bronze",
        139 => "13U Gold",
        140 => "13U Gold/Silver",
        141 => "13U Silver",
        142 => "13U Silver/Bronze",
        143 => "13U Bronze",
        144 => "14U Gold",
        145 => "14U Gold/Silver",
        146 => "14U Silver",
        147 => "14U Silver/Bronze",
        148 => "14U Bronze",
        149 => "15U / Frosh/Soph Gold",
        150 => "15U / Frosh/Soph Silver",
        151 => "15U / Frosh/Soph Bronze",
        152 => "Open Division",
        153 => "13U East",
        154 => "13U West",
        155 => "14U SuperGirlz",
        156 => "14U Gold Girls",
        157 => "14U Silver East Girls",
        158 => "14U Silver West Girls",
        159 => "14U Invite",
        160 => "13U Bronze East",
        161 => "13U Bronze West",
        162 => "13U Silver/Bronze East",
        163 => "13U Silver/Bronze West",
        164 => "11U Bronze East",
        165 => "11U Bronze West",
        166 => "14U Silver East",
        167 => "14U Silver West",
        168 => "HS",
        169 => "14U East",
        170 => "14U West",
        171 => "8U Gold",
        172 => "8U Gold/Silver",
        173 => "8U Silver",
        174 => "8U Silver/Bronze",
        175 => "8U Bronze",
        176 => "9U Silver/Bronze East",
        177 => "9U Silver/Bronze West",
        178 => "10U Silver/Bronze East",
        179 => "10U Silver/Bronze West",
        180 => "12U Bronze East",
        181 => "12U Bronze West",
        182 => "13U Silver East",
        183 => "13U Silver West",
        184 => "14U Silver South",
        185 => "14U Bronze East",
        186 => "14U Bronze West",
        187 => "17U James Harden",
        188 => "17U Damian Lillard",
        189 => "17U Tracy McGrady",
        190 => "17U Donovan Mitchell",
        191 => "17U Trae Young",
        192 => "17U Onyeka Okongwu",
        193 => "16U Evan Mobely",
        194 => "16U Derrick Rose",
        195 => "16U Gilbert Arenas",
        196 => "16U Jalen Green",
        197 => "15U Bennedict Mathurin",
        198 => "15U Jabari Smith",
        199 => "15U Keegan Murray",
        200 => "15U MarJon Beauchamp",
        201 => "15U Dalen Terry",
        202 => '10U Bronze/Copper',
        203 => '11U Bronze/Copper',
        204 => '12U Bronze/Copper',
        205 => '13U Bronze/Copper',
        206 => '14U Bronze/Copper',
        207 => "15U Gilbert Arenas",
        208 => "16U James Harden",
        209 => "16U Dalen Terry",
        210 => "17U Jalen Green",
        211 => "16U Evan Mobley",
        212 => "15U Keegan Murrat",
        213 => "17U Gilbert Arenas",
        214 => "15U James Harden",
        215 => "15U Donavan Mitchell",
        216 => "9U Bronze/Copper",
        217 => "15U Trae Young",
        218 => "15U Evan Mobley",
        219 => "16U Damian Lillard",
        220 => "16U Donovan Mitchell",
        221 => "16U Tracy McGrady",
        222 => "16U East",
        223 => "16U Super",
        224 => "17U East",
        225 => "17U West",
        226 => "17U South",
        227 => "17U Super",
        228 => "10U East",
        229 => "10U West",
        230 => "12U Bronze East",
        231 => "12U Bronze West",
        232 => "13U East",
        233 => "13U North",
        234 => "13U West",
        235 => "15U East",
        236 => "15U West",
        237 => "17U East",
        238 => "17U West",
        239 => "10U/11U Bronze",
        240 => "9u/10u bronze",
        241 => "10U/11U Copper",
        242 => "10U Copper",
        243 => "11U Copper",
        244 => "12U Copper",
        245 => "13U Copper",
        246 => "14U Copper",
        247 => "15U James Harden",
        248 => "16U Donovan Mitchelll",
        249 => "17U Anthony Edwards",
        250 => "12U HYPE",
        251 => "13U HYPE",
        252 => "14U CITYCENTER",
        253 => "14U HYPE",
        254 => "15U HYPE",
        255 => "15U LUXOR",
        256 => "16U HYPE",
        257 => "16U MGM",
        258 => "16U PARK MGM",
        259 => "17U BELLAGIO",
        260 => "17U HYPE",
        261 => "15u 3SGB",
        262 => "16u 3SGB",
        263 => "17u 3SGB",
        264 => "15u Dalen Terry",
        265 => "15u Damian Lillard",
        266 => "15u Evan Mobley",
        267 => "15u Trae Young",
        268 => "16u Derrick Rose",
        269 => "16u Donovan Mitchell",
        270 => "16u James Harden",
        271 => "9u/10u East",
        272 => "9u/10u West",
        273 => "11U/5th Bronze/Copper East",
        274 =>"11U/5th Bronze/Copper West",
        275 => "12U Gold/13U Bronze",
        276 => "15U Gold/Silver",
        277 => "15U Adidas 3SGB",
        278 => "15U Damian Lillard",
        279 => "15U Anthony Edwards",
        280 => "16U Gold/Silver",
        281 => "16U Silver",
        282 => "16U Bronze",
        283 => "16U Adidas 3SGB",
        284 => "17U Adidas 3SGB",
        285 => "17U Silver/Bronze",
        286 => "17U Anthony Edwards",
        287 => "Girls JV Gold",
        288 => "Girls JV Silver",
        289 => "Girls JV Bronze",
        290 => "Girls Varsity Gold",
        291 => "Girls Varsity Silver",
        292 => "Girls Varsity Bronze",
        293 => "6th Grade Hype",
        294 => "7th Grade Hype",
        295 => "8th Grade Hype",
        296 => "9th Grade Hype",
        297 => "10th Grade Hype",
        298 => "11th Grade Hype",
        299 => "12th Grade Hype",
        300 => "High School Hype",
        301 => "9U/10U silver",
        302 => "12U Silver East",
        303 => "12U Silver West",
        304 => "11U Bronze/Copper East",
        305 => "11U Bronze/Copper West",
        306 => "12U Bronze/Copper East",
        307 => "12U Bronze/Copper West",
        308 => "16U Girls Gold",
        309 => "16U Girls Silver",
        310 => "16U Girls Bronze",
        311 => "Girls 6th Silver",
        312 => "Girls 7th Silver",
        313 => "Girls 8th Silver",
        314 => "15U Silver/Bronze",
        315 => "17U Varsity Bronze",
        316 => "17U Varsity Silver",
        317 => "Girls Varsity Platinum",
        318 => "9U/10U Copper",
        319 => "12U/6th Grade Girls Gold",
        320 => "16U Trae Young",
        321 => "14U/15U East",
        322 => "14U/15U West",
        323 => "15U/16U Gold/Silver",
        324 => "8U/9U East",
        325 => "8U/9U West",
        326 => "12U Gold/Silver East",
        327 => "13U Bronze/Copper East",
        328 => "13U Bronze/Copper West",
        329 => "17U North",
        330 => "14U Bronze/Copper East",
        331 => "14U Bronze/Copper West"
      );
      break;
    case 'keys_by_domain' :
      return array(
        'https://dev.opengympremier.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => '2sdsv5StJc34345h9jdfbjlkwmb9en09je', //34 character
          'trans_id'  => 'Zplay23tg4school345vfw4dvbfty', //29 characters
          'id'        => 'OGD',
          'user_id'   => '22',
          'users'     => array(
            1 => 3
          )
        ),
        'https://opengympremier.com' => array(
//           'ip'        => '198.199.108.46',
          'ip'        => '198.199.105.66',
          'trans_key' => 'lBllSqHhNQsRNVUT06ayzZzXmSZhr5O1Co', //34 character
          'trans_id'  => 'soqqeRSa7RgHCkjWq0U6Mc6Gz5tSU', //29 characters
          'id'        => 'OGP',
          'user_id'   => '3',
          'users'     => array(
            5 => 3,
            15 => 3
          )
        ),
        'https://elitebasketballcircuit.com' => array(
          'ip'        => '198.199.108.46',
          'trans_key' => '4bJFRm974Usys2KWE8z1uIzKmu7qWEIiZm', //34 character
          'trans_id'  => '937u31EVu02qXPN63rlDWfQhOgCu5', //29 characters
          'id'        => 'EBP',
          'user_id'   => '1565',
          'org_id'    => '2',
          'users'     => array(
            1 => 1565,
            388 => 907
          )
        ),
        'https://dev.elitebasketballcircuit.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'iq737CdfH8H3s5RcjbfeM0A2a4Sg5jI0ZG', //34 character
          'trans_id'  => 'T3mxUsKpLrE5oyiodch20VL6V5VFE', //29 characters
          'id'        => 'EBD',
          'user_id'   => '377',
          'org_id'    => '2',
          'users'     => array(
            1 => 377
          )
        ),
        'https://grassroots365.com' => array(
          'ip'        => '198.199.117.208',
          'trans_key' => 'lB2lSqHhwQsRN5UT06byzZzXmSZhr512Tr', //34 character
          'trans_id'  => 'wTqYeRss7ReHC6jWa0U6Mg6Gz3uSU', //29 characters
          'user_id'   => '3',
          'id'        => 'G3P',
          'org_id'    => '3191',
          'users'     => array()
        ),
        'https://dev.grassroots365.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => '2sdsv5StJc34345h9jdfbjlkwmb9en09je', //34 character
          'trans_id'  => 'Zplay23tg4school345vfw4dvbfty', //29 characters
          'id'        => 'G3D',
          'user_id'   => '22',
          'org_id'    => '3191',
          'users'     => array()
        ),
        // Old site
//         'https://thestagecircuit.com' => array(
//           'ip'        => '198.199.108.46',
//           'trans_key' => '73n6us7iyf2wbq5sscrkrm0ABCDEFGHIJK', //34 character
//           'trans_id'  => '0k6zc7r7zzuwmbrkwhb8ol2v7xnjm', //29 characters
//           'id'        => 'TSD',
//           'user_id'   => '3',
//           'users'     => array()
//         ),
        'https://thestagecircuit.com' => array(
          'ip'        => '198.199.113.65',
          'trans_key' => '73n6us7iyf2wbq5sscrkrm0ABCDEFGHIJK', //34 character
          'trans_id'  => '0k6zc7r7zzuwmbrkwhb8ol2v7xnjm', //29 characters
          'id'        => 'TSD',
          'user_id'   => '3',
          'org_id'    => '3',
          'users'     => array()
        ),
        'https://dev.thestagecircuit.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'kxe0380f9qm1aw4d4pb9mll61kp00uy5x5', //34 character
          'trans_id'  => 'ee0ljsay7dwfcw3olz3lwsknin3bx', //29 characters
          'id'        => 'TSD',
          'user_id'   => '3',
          'org_id'    => '3',
          'users'     => array()
        ),
        'https://scholasticseries.com' => array(
          'ip'        => '198.199.108.46',
          'trans_key' => 'c4ys5dhgtqjtsjwia229ihh0zeywthhf6j', //34 character
          'trans_id'  => 'vaa8nxb0exmiinacd7n4kj32xs53x', //29 characters
          'id'        => 'SCS',
          'user_id'   => '3',
          'org_id'    => '7165',
          'users'     => array()
        ),
        'https://dev.scholasticseries.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'ezhao1ka6tgdn442p427s9o3007pqzlo11', //34 character
          'trans_id'  => 'amel5bf1ghy5b6t10cu3aymb7jclj', //29 characters
          'id'        => 'SCS',
          'user_id'   => '3',
          'org_id'    => '7165',
          'users'     => array()
        ),
        'https://hypeherhoopscircuit.com' => array(
          'ip'        => '198.199.108.46',
          'trans_key' => 'n8qsn1zvvsld2o3am3ufrochxuqoquohg4', //34 character
          'trans_id'  => 's9quysydll2ds0ktutc56zd2okawy', //29 characters
          'id'        => 'HHH',
          'user_id'   => '3',
          'org_id'    => '7164',
          'users'     => array()
        ),
        'https://dev.hypeherhoopscircuit.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => '7450sn79ebnblfpkaya5e2441wqtqaa265', //34 character
          'trans_id'  => 'qrwuh8o5by47hs0vw2sqdrcky4t5c', //29 characters
          'id'        => 'HHH',
          'user_id'   => '1',
          'org_id'    => '7164',
          'users'     => array()
        ),
        'https://sportspassports.com' => array(
          'ip'        => '198.199.108.46',
          'trans_key' => 'Hep243RZuA8pXnFIYpxFD63Nx8DrQCkfwu', //34 character
          'trans_id'  => '7A7ZqIYk09xlnIfk5welvgvS2PHZd', //29 characters
          'id'        => 'SPP',
          'user_id'   => '3',
          'users'     => array()
        ),
        'https://dev.sportspassports.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'wBQjRrzKyT7ZGBJtk28F6al8yWXRT0vwU0', //34 character
          'trans_id'  => 'lqfRVtzqHoaHk8st12HXxadJ5RoRJ', //29 characters
          'id'        => 'SPD',
          'user_id'   => '22',
          'users'     => array()
        ),
        'https://ogpcares.com' => array(
          'ip'        => '137.184.182.249',
          'trans_key' => 'jJQa3z0iDV0kmEp396wNXQPy9Aq2z3PXGK', //34 character
          'trans_id'  => 'wzJvQLkttT5q8lKDOpZsi794ykefN', //29 characters
          'id'        => 'OGC',
          'user_id'   => '22',
          'users'     => array()
        ),
        'https://dev.ogpcares.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'm72ndGLOw4T274sD3TMTQavk9atg8BxAG6', //34 character
          'trans_id'  => '5VALd7n91YuZMNd5PfXDHvE4glLAZ', //29 characters
          'id'        => 'OGC',
          'user_id'   => '22',
          'users'     => array()
        ),
        'https://dev.scopescouting.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'rTA2JsXRc3Sbdd3KQZnqEgpUfDjubSjsGb', //34 character
          'trans_id'  => 'mqsbweJhC9cysjVWDHVCKE65cUNrk', //29 characters
          'id'        => 'SSD',
          'user_id'   => '22',
          'users'     => array()
        ),
        'https://scopescouting.com' => array(
          'ip'        => '137.184.182.249',
          'trans_key' => 'fzzUGaEWHm4EgppjrVUDzV3zVSFaN6N1Y7', //34 character
          'trans_id'  => 'HCZ2Zc7c61Yhr2qVVGnyyhwGW1PfX', //29 characters
          'id'        => 'SSP',
          'user_id'   => '22',
          'users'     => array()
        ),
        'https://dev.thrivebasketballleague.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'WUad9RNuc63TnEY5w1fmbJztqFKQGP7CHh', //34 character
          'trans_id'  => 'SUKZEuqFNbMGad8C9xATk37j5Btw6', //29 characters
          'id'        => 'TBD',
          'user_id'   => '22',
          'org_id'    => '8495',
          'users'     => array()
        ),
        'https://thrivebasketballleague.com' => array(
          'ip'        => '137.184.182.249',
          'trans_key' => 'JEwKB4Zq5XY6pDj8ub2e3CaHQF7GyTdmtc', //34 character
          'trans_id'  => 'EMDsN3gXZ9A1wKu8hdqGYpnPWtB6C', //29 characters
          'id'        => 'TBP',
          'user_id'   => '22',
          'org_id'    => '8495',
          'users'     => array()
        ),
        'https://dev.theundergroundcircuit.com' => array(
          'ip'        => '192.241.214.29',
          'trans_key' => 'meKyA9CcH26rpjuZRxtX1WDJdTQzs43vkf', //34 character
          'trans_id'  => 'XWBm7VRfszT2qHDhCg4N5tEGrUdjb', //29 characters
          'id'        => 'UCD',
          'user_id'   => '22',
          'org_id'    => '8496',
          'users'     => array()
        ),
        'https://theundergroundcircuit.com' => array(
          'ip'        => '137.184.182.249',
          'trans_key' => '624VPX1TAjveRYHJW3Scms9bZUDExGg5ap', //34 character
          'trans_id'  => 'V6qceAyZs5pY4UrdQSuDkjHEK8Nzb', //29 characters
          'id'        => 'UCP',
          'user_id'   => '22',
          'org_id'    => '8496',
          'users'     => array()
        ),
        '' => array(
          'ip' => '',
          'id' => 0,
          'users'  => array()
        )
      );
      break;
  }
}

//similar function to remote sites
function g365_send_claim_notice( $data_args ) {
  if( !is_array($data_args) || count($data_args) !== 4 ) return 'Need all data points to send claim request.';
  $user_id = $data_args[0];
  $data_name = $data_args[1];
  $requesting_user = $data_args[2];
  $target_user = get_userdata( $user_id );
  $data_ref_id = intval( $data_args[3] );
  
  $subject = 'Access request for ' . $data_name;
  $message = "<p>You are the owner of the " . $data_name . " Profile on Sports Passports.<br>";
  $message .= 'There is a request for permission to share access to this profile data from ' . $requesting_user . '<br>';
  $message .= "If you don't recognize the requester please disregard this notice.</p>";
  $message .= '<p><a href="' . site_url() . '/register/claim-confirmations/?ref_id=' . $data_ref_id . '&ref_em=' . $requesting_user . '" style="background:#000;color:#fff;border-radius:10px;padding:10px 20px;font-size:1.25rem;text-decoration:none;">Please click here to grant access</a></p>';
  //send email
  $send_result = send_html_email( $target_user->user_email, $subject, $message );
  if( $send_result ) {
    $data_result['status'] = 'success';
    $data_result['message'] = ' Request successfully sent to owner.';
  } else {
    $data_result['message'] = ' Request not sent.';
  }
  return $data_result;

}

//similar function to remote sites -> sending email when admin comfirms claim
function g365_send_claim_acceptance( $parent, $player ) {
  if( empty($parent) || empty($player) ) return 'Need all data points to send claim requests.';
  $user_id = $parent; //id of person who we are sending the email too
  $target_user = get_userdata( $user_id ); //get the data from the person we are sending the email too.
  global $wpdb;
  $target_db_players = $wpdb->g365_players;
  $data_name = $wpdb->get_row( "SELECT * FROM $target_db_players WHERE id = '" . $player . "'" ); //player info -> preferably name of player
  $player_name = $data_name->name;
  $user_email = (string) $target_user->user_email;
//   echo($target_user->user_email . ' ' . $data_name->name);
  
  $subject = 'Your request to obtain access for ' . $player_name . ' has been approved!';
  $message = "<p>Great news! You have been given access to " . $player_name . " Profile.<br><br> Please complete the <a style='color: red;'>MANDATORY</a> registration process and purchase The Passport in order to be eligible to play.<br><br>";
  $message .= "To purchase the passport <a href='https://sportspassports.com/cart/?add-to-cart=10388&quantity=1' >click here</a>. <br><br> ";
  $message .= "If you did not request this claim or recognize this player please disregard this notice.</p>";
  
  //send email (email im sending it too, subject of the email, email itself)
//   echo(" test: " . $target_user->user_email. " subj: " . $subject . " messge: " . $message);
  $send_result = send_html_email_updated_accepted_claim( $user_email, $subject, $message );
//   $send_result = send_html_email_updated( 'christian.jimenez@opengympremier.com', $subject, $message );
  if( $send_result ) {
    $data_result['status'] = 'success';
    $data_result['message'] = ' Email sent to requestor.';
  } else {
    $data_result['message'] = ' Email not sent.';
  }
  return $data_result;

}

//testing sending customer success an email
function g365_send_claim_new_notice( $customer_success, $claim_data ) {
//   if( empty($parent) || empty($player) ) return 'Need all data points to send claim requestssss.';
  $user_id = $customer_success; //id of person who we are sending the email too
  $target_user = get_userdata( $user_id ); //get the data from the person we are sending the email too.
  $parent_user = get_userdata( $claim_data['owner_id'] );
//   print_r($parent_user);
//   echo("hi " . $parent_user);
  
  $subject = 'There is a new access request in the back end!';
  $message = "<p>Hello customer success team,<br><br> A parent with the email of " . $parent_user->user_email . " has made a brand new access request. Thank you for all your hard work. <br><br>";
//   $message .= "To purchase the passport <a href='https://sportspassports.com/product/passport-annual/' >click here</a>. <br><br> To access the player directory <a href='https://sportspassports.com/account/player_editor/' >click here</a>.<br><br>";
//   $message .= "If you did not request this claim or recognize this player please disregard this notice.</p>";
  
//   echo(" " . $subject . " " . $message);
//   //send email (email im sending it too, subject of the email, email itself)
  
  // List of additional email addresses
  $additional_emails = [
      'daniel.katz@sportspassports.com',
      'septain.zain@sportspassports.com',
      'hunter.vonterschpohrer@sportspassports.com'
  ];

  // Combine target user's email with additional emails
  $all_emails = array_merge([$target_user->user_email], $additional_emails);

  // Convert the array of emails to a comma-separated string
  $to_emails = implode(',', $all_emails);
  
  $send_result = send_html_email_updated( $to_emails, $subject, $message );
  if( $send_result ) {
    $data_result['status'] = 'success';
    $data_result['message'] = ' Email sent to requestor.';
  } else {
    $data_result['message'] = ' Email not sent.';
  }
  return $data_result;

}

function spp_send_roster_submission_email($new_ros_id = null, $event_id = null){
  global $wpdb;
  $target_db_players = $wpdb->g365_players;
  $target_db_teams = $wpdb->g365_teams;
  
  $roster_info = g365_get_roster($new_ros_id);
  $org_info = g365_get_org_profile($roster_info->org_id);
  // Decode the JSON string in org_info->access
  $access_data = json_decode($org_info->access, true); // true to get an associative array
  $org_name = $org_info->name;
  
  // Check if SPD key exists and store the values
  $spd_values = [];
  if (isset($access_data['SPP'])) {
      $spd_values = $access_data['SPP'];
      // Extract player IDs from the player_names JSON string
        $player_data = json_decode($roster_info->player_names, true);
        $player_ids = array_keys($player_data);
    
    
            $event_rosters = g365_get_rosters(array('event_id' => $event_id, 'order_by_master' => 'roster.level ASC, CASE WHEN roster.division = \'Open\' THEN \'1\' WHEN roster.division = \'Gold\' THEN \'2\' WHEN roster.division = \'Silver\' THEN \'3\' WHEN roster.division = \'Bronze\' THEN \'4\' WHEN roster.division = \'Copper\' THEN \'5\' ELSE roster.division END ASC, orgs.name ASC'), false, true);
            // Convert the data to a JSON string for output
            $event_rosters_json = json_encode($event_rosters);
            // Variable to store ineligible player IDs and labels
            $storare = [];
            foreach( $event_rosters[0] as $ros_dex => $ros_data ) {
              $roster_players = array();
              $team_veri = 'no-players';
              if( !empty($ros_data->players) ) {
                $team_veri = 'verified-team';
                $today = date("Y-m-d");
                $ros_data->players = json_decode($ros_data->players);
                $prod_site_img = site_url( '/wp-content/uploads/player-profiles/', 'https' ); 
                $prod_site_img = str_replace('dev.', '', $prod_site_img);
                foreach( $ros_data->players as $pl_id => $pl_data ) {
                        $roster_labels = '';
                        if( isset($ros_data->division_selector_birth_lock) && !empty($event_rosters[1][$pl_id]->birthday) ) {
  //                           $roster_labels .= intval($event_rosters[1][$pl_id]->grad_year);
                          //if birth lock is grerater in time than player, player is too old, find out if exception or violation
                          if( strtotime(explode("'", $ros_data->division_selector_birth_lock)[1]) > strtotime($event_rosters[1][$pl_id]->birthday) ) {
                            //in the right grade?
                            if( intval(explode("-", explode(" ", $ros_data->division_selector_class_lock)[1])[0]) < intval($event_rosters[1][$pl_id]->grad_year) ) {
                              $roster_labels .= '<span class="tag-label">Exception</span>';
                            } else {
                              $roster_labels .= '<span class="tag-label">Ineligible</span>';
                              $storare[] = $pl_id;
                            } 
                          }
                        }
//                       echo("<script>console.log(' testing here4 : " . $new_ros_id . " " . $roster_info->org_id . " here: " . $player_id . " " . $data_name->name  . " : " . $org_info->name . " " . $org_info->access . " " . $event_id . " " . $event_rosters_json . " this: " . $roster_labels . " " . $pl_id . " ');</script>");
                }
              }
            }
    
        // Construct HTML table with player IDs
        $player_table = "<table border='1'><tr><th>Player Name</th><th>Jersey #</th><th>Verified</th><th>Unlocked</th></th><th>Eligible</th></tr>";
        foreach ($player_ids as $player_id) {
            $data_name = $wpdb->get_row( "SELECT * FROM $target_db_players WHERE id = '" . $player_id . "'" ); //player info -> preferably name of player
            $subscription_info = stat_subscription($player_id);
            $team_data = $wpdb->get_row( "SELECT * FROM $target_db_teams WHERE id = '" . $roster_info->team_id . "'" );
            $team_level = $team_data->search_list;
            
          
            $player_name = $data_name->name;
            $jersey_number = $player_data[$player_id]['j_num'];
            $is_verified = ($data_name->verified === "2") ? "yes" : "no";

            // Extracting and formatting the subscription data for display
            $subscription_data = $subscription_info[2];
            $unlocked_str = '';

            if (isset($subscription_data['yearly_subscription'])) {
                $unlocked_str .= 'Yearly: ' . implode(', ', $subscription_data['yearly_subscription']) . '<br>';
            }
            if (isset($subscription_data['yearly_subscription_purchased_date'])) {
                $unlocked_str .= 'Yearly Purchased: ' . implode(', ', $subscription_data['yearly_subscription_purchased_date']) . '<br>';
            }
            if (isset($subscription_data['monthly_subscription_data'])) {
                $unlocked_str .= 'Monthly: ' . implode(', ', $subscription_data['monthly_subscription_data']);
            }
          
            // Prepare the argument for g365_passport_validation
            $validation_args = [
                'pp_data' => $subscription_data,
                'selected_year' => date('Y') // Example: current year, adjust as needed
            ];
            
            $is_unlocked = g365_passport_validation('subscription-validation', $validation_args);
          
            $is_unlocked = ($is_unlocked === 'true') ? 'yes' : 'no';
          
            // Check if the player is ineligible
            $is_eligible = in_array($player_id, $storare) ? 'no' : 'yes';
          
            $verified_style = ($is_verified == 'no') ? 'color: red; font-weight: bold;' : '';
            $unlocked_style = ($is_unlocked == 'no') ? 'color: red; font-weight: bold;' : '';
            $eligible_style = ($is_eligible == 'no') ? 'color: red; font-weight: bold;' : '';

            $player_table .= "<tr>
                <td>{$player_name}</td>
                <td style='text-align: center;'>{$jersey_number}</td>
                <td style='text-align: center; {$verified_style}'>{$is_verified}</td>
                <td style='text-align: center; {$unlocked_style}'>{$is_unlocked}</td>
                <td style='text-align: center; {$eligible_style}'>{$is_eligible}</td>
            </tr>";

        }
        $player_table .= "</table>";  
    
      // Iterate over spd_values to get emails
      foreach ($spd_values as $id) {
          $event_name = g365_get_event_data($event_id);
          $event_name = $event_name->name;
          $email = get_userdata($id);
          $emails[] = $email->user_email; //gets email of the user in access
          $user_name = $email->user_nicename; //user name
          
          $subject = 'Roster Submission Confirmation: ' . $event_name . ' ';
          $message = "<p>" . $user_name . ",<br><br> This is your roster submission receipt for: 
          <br><br><strong>Event Name:</strong> " . $event_name . "
          <br><br><strong>Team Name:</strong> " . $org_name . " " . $team_level . "
          <br><br>ALL PLAYERS must be verified, unlocked, and eligible in order to participate. See players’ status below. To make changes to your roster, click <a href='https://sportspassports.com/account/rosters/' >here</a>.
          <br><br>Need help? Reach out to our team!
          <br><br>Hunter (hunter.vonterschpohrer@sportspassports.com)
          <br><br>Septian (septian.zain@sportspassports.com)
          <br><br>We look forward to seeing you at " . $event_name . "!</p><br>";
          $message .= $player_table;
        
//             echo("<script>console.log('values: " . $email->user_email . "');</script>");
          //$email->user_email
          $send_result = send_html_email_updated_accepted_claim( $email->user_email, $subject, $message );
        
          if( $send_result ) {
            $data_result['status'] = 'success';
            $data_result['message'] = ' Email sent to requestor.';
          } else {
            $data_result['message'] = ' Email not sent.';
          }
      }
  }

  return $data_result;
}


function spp_player_roster_notice($player_ids = null, $team = null, $type = null) {
    // Check if the email is for players who have been added or removed from a roster
    switch ($type) {
        case 'added':
            if (!empty($player_ids)) { // Check if player_ids is not empty
                
//                 echo '<pre>';
//                 echo 'Added Players: ';
//                 print_r($player_ids);
//                 if (is_array($team) && isset($team[0]->search_list)) { // Check if team is an array and has the search_list property
//                     echo ' got into the function1: ' . print_r($team, true) . ' >' . $team[0]->search_list . '< ';
//                 } else {
//                     echo 'Invalid team structure or missing search_list property';
//                 }
//                 echo '</pre>';
              
                //add email creationg below
                //firts insert into a for loop to send to every player
                foreach ($player_ids as $player_id => $player_data){

                    //grab player email, and name to display in email.
                    $player_inf = g365_get_profile($player_id);
                    $play_name = $player_inf->name;

                    //get team name and division to display
                    $team_name = $team[0]->search_list;

                    //insert code below into the foreach to make sure every player added is sent an email
                    $subject = 'You have been inserted into a roster ' . $team_name . ' ';
                    $message = "<p>" . $play_name . ",<br><br> Congratulations you have been added to the roster " . $team_name . ".";
                    //add email sending function below so once email is built we just send it.

                    $send_result = send_html_email_updated_accepted_claim( 'christian.jimenez@opengympremier.com', $subject, $message );

                }
        
                if( $send_result ) {
                  $data_result['status'] = 'success';
                  $data_result['message'] = ' Email sent to requestor.';
                } else {
                  $data_result['message'] = ' Email not sent.';
                }
            }
            break; // Missing semicolon added
        case 'removed':
            if (!empty($player_ids)) { // Check if player_ids is not empty
                
//                   echo '<pre>';
//                   echo 'Removed Players: ';
//                   print_r($player_ids);
//                   if (is_array($team) && isset($team[0]->search_list)) { // Check if team is an array and has the search_list property
//                       echo ' got into the function2: ' . print_r($team, true) . ' >' . $team[0]->search_list . '< ';
//                   } else {
//                       echo 'Invalid team structure or missing search_list property';
//                   }
//                   echo '</pre>';

                  //add email creationg below
                  //firts insert into a for loop to send to every player
                  foreach ($player_ids as $player_id => $player_data){

                      //grab player email, and name to display in email.
                      $player_inf = g365_get_profile($player_id);
                      $play_name = $player_inf->name;

                      //get team name and division to display
                      $team_name = $team[0]->search_list;

                      //insert code below into the foreach to make sure every player added is sent an email
                      $subject = 'You have been removed from the roster ' . $team_name . ' ';
                      $message = "<p>" . $play_name . ",<br><br> This is a notice to inform you that you have been removed from the roster of " . $team_name . ".";
                      //add email sending function below so once email is built we just send it.

                      $send_result = send_html_email_updated_accepted_claim( 'christian.jimenez@opengympremier.com', $subject, $message );

                  }

                  if( $send_result ) {
                    $data_result['status'] = 'success';
                    $data_result['message'] = ' Email sent to requestor.';
                  } else {
                    $data_result['message'] = ' Email not sent.';
                  }
            }
            break; // Missing semicolon added
        default:
            echo 'Invalid type specified';
    }
}
  


//for sending ownership data to creator server
function g365_data_sender( $access_level, $send_type, $send_data ) {
  //check minimum variables
  if( empty($send_type) ||empty($send_data) || empty($access_level[1]) || ($send_type === 'claim' && empty($access_level[2])) ) return 'Error, need complete sending information.';
  //see if we have a record for the creating site user
  $keys_by_domain = array_filter( g365_return_keys('keys_by_domain'), function($data) use ($access_level){ return $data[ 'id' ] === $access_level[1]; });
  if( empty($keys_by_domain) ) return 'No key found';
  //grab the key
  $keys_by_domain_key = array_keys($keys_by_domain)[0];
  if( empty($keys_by_domain_key) ) return 'No key domain found';
  //make sure we only have one
  if( !wp_http_validate_url($keys_by_domain_key) ) return 'Inconclusive destination server.';
  //check that we have keys to send
  if( empty($keys_by_domain[ $keys_by_domain_key ]['trans_key'])  || empty($keys_by_domain[ $keys_by_domain_key ]['trans_id']) ) return 'Missing Key and/or ID credentials, please contact your administrator.';
  //if we are on our own domain then just write the data internally
  if( site_url() === $keys_by_domain_key ){
    switch($send_type) {
      case 'claim':
        //integrate user data
        $result = ( update_user_meta( $access_level[2], '_user_owns_g365', g365_reference_data_integrator( $send_data, get_user_meta( $access_level[2], '_user_owns_g365', true) ) ) === false ) ? 'Failed update.' : 'Successful update.';
        break;
      case 'notify':
        $result = g365_send_claim_notice(array($send_data[0], $send_data[1], $send_data[2], $send_data[3]));
        break;
    }
  } else {
    switch($send_type) {
      case 'claim':
        // Create a connection
        $url = $keys_by_domain_key . '/ownership-registration/';
        $response = wp_remote_post( $url, array(
          'body'    => ['data_own_ref' => $send_data, 'owner_id' => $access_level[2]],
          'headers' => array(
              'Authorization' => 'Basic ' . base64_encode( $keys_by_domain[ $keys_by_domain_key ]['trans_key'] .  $keys_by_domain[ $keys_by_domain_key ]['trans_id'] ),
            )
          )
        );
        break;
      case 'notify':
//         Create a connection
        $url = $keys_by_domain_key . '/send-claim/';
        $response = wp_remote_post( $url, array(
          'body'    => ['data_owner' => $send_data[0], 'data_name' => $send_data[1], 'data_requester' => $send_data[2], 'data_ref_id' => $send_data[3]],
          'headers' => array(
              'Authorization' => 'Basic ' . base64_encode( $keys_by_domain[ $keys_by_domain_key ]['trans_key'] .  $keys_by_domain[ $keys_by_domain_key ]['trans_id'] ),
            )
          )
        );
        break;
    }
    if ( is_wp_error( $response ) ) {
       return $response->get_error_message();
    }
    $result = json_decode($response['body']);
  }
  $result = $result->message;
  return $result;
}

function g365_authorize_claim_local( $id, $email, $for_admin = null, $site_key ){
  if( current_user_can( 'administrator' ) ) {
    $current_user = get_user_by( 'id', $for_admin );
  } else {
    $current_user = wp_get_current_user();
  }
  
  //check the site_key. currently only looking through grassroots database
  if($site_key === 'G3P' || $site_key === 'G3D' || $site_key === 'SPD' || $site_key === 'SPP'){
    $target_user = get_user_by( 'email', sanitize_email($email) );
  } 
//   if($site_key === 'EBD' || $site_key === 'EBP' || $site_key === 'OGD' || $site_key === 'OGP'){
// //     echo("<script>console.log('email: " . $email . " ');</script>");
// //     echo("<script>console.log('email: " . $site_key . " ');</script>");
//     $target_user = g365_international_obtain_user($site_key, $email);
//   }
  
  //$target_ser is making it break with different sites.
//   echo("<script>console.log('email: " . $current_user  . " ');</script>");
//   if( $target_user === false || $current_user === false ) return (object) array('message' => 'Cannot find both users.');
  //if we got this far, fire the gun
  if($site_key === 'G3P' || $site_key === 'G3D' || $site_key === 'SPD' || $site_key === 'SPP'){
    return g365_authorize_claim( $id, $current_user->ID, $target_user->ID );
  } 
  if($site_key === 'EBD' || $site_key === 'EBP' || $site_key === 'OGD' || $site_key === 'OGP'){
    return g365_authorize_claim( $id, $current_user->ID );
  }
}

// function g365_international_obtain_user($site_key, $email){
//   if($site_key === 'EBD'){
    
//     $conn = mysqli_connect("localhost","Lh1DsF8VxvZr","1KeNoI0EQ5OelPkr","ebc-dev-wp-LkXjMJsF" );
    
//     $sql = "SELECT ID, user_email FROM wp_415633e74a_users WHERE user_email = '" . $email . "'";
//     $resultsd1 = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($resultsd1);
//     //to show outputs
// //     echo "hello";
// //     echo $row['ID'];
//     //echo $row['user_email'];
//     //try to make this function the international for getting other websites........
    
    
//     return $row['ID'];
//     mysqli_close($conn);
    
//   }
  
// }

function g365_authorize_claim( $claim_id, $owner = null, $new_user = null, $finish = null ) {
	global $wpdb;
  $wpdb_claims = $wpdb->g365_claims;
  $claim_id = intval($claim_id);
  $owner = intval($owner);
  if( $finish !== null ) return ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? 'Claim clean-up error.' : ' Claim clean-up successful.';
  $status = 0;
  //pull claim record to check against
  $claim_record = $wpdb->get_row( "SELECT * FROM $wpdb_claims WHERE id = $claim_id" );
  $target_db = '';
  $access_type = '';
  switch( $claim_record->type ) {
    case 1:
      $target_db = $wpdb->g365_players;
      $access_type = 'pl_ed';
      break;
    case 2:
      $target_db = $wpdb->g365_orgs;
      $access_type = 'og_ed';
      break;
    case 3:
      $target_db = $wpdb->g365_coaches;
      $access_type = 'co_ed';
      break;
  }
  
  if($claim_record->site_key === 'G3D' || $claim_record->site_key === 'G3P' ){
      //check access to make sure authorizer has permission to grant access...first user is owner
      $owner_id = json_decode($wpdb->get_var( "SELECT access FROM $target_db WHERE id = " . $claim_record->target ));
      if( empty($owner_id) ) return 'Original owner undefined or claim completed';
  }
  
  $owner_id = $owner_id->{$claim_record->site_key}[0];
  if( $owner === $owner_id ) {
    $status = 1;
    $claim_record_target = $claim_record->target;
    //return "UPDATE $target_db SET access = JSON_SET(COALESCE(access, '{}'), '$." . $claim_record->site_key . "', JSON_ARRAY_APPEND(COALESCE(JSON_EXTRACT(access, '$." . $claim_record->site_key . "'), '[]'), '$', " . $target_user->ID . ")) WHERE id = " . $claim_record->target . " AND NOT JSON_CONTAINS(JSON_EXTRACT(access, '$." . $claim_record->site_key . "'), " . $target_user->ID . ", '$');";
    //if we are local then perform the user update, else return the result
    $access_add = array( $access_type => array($claim_record->target) );
    $update_claim = '';
    $target_user = new stdClass;
    if( $claim_record->site_key === 'G3P' || $claim_record->site_key === 'G3D' ) {
      //if the target user is available add the refernece to thier account, otherwise change the status for when they do get created
      $target_user = get_user_by( 'email', $claim_record->email );
      if( $target_user === false ) {
        $update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully for future use.";
      } else {
        //once the target user has the reference delete the claim record.
        //return (object) array( 'status' => $status, 'message' => g365_reference_data_integrator( $access_add, get_user_meta( $target_user->ID, '_user_owns_g365', true) ), 'target_user' => $claim_record->email, 'access_id' => $access_add);
        $update_claim .= ( update_user_meta( $target_user->ID, '_user_owns_g365', g365_reference_data_integrator( $access_add, get_user_meta( $target_user->ID, '_user_owns_g365', true) ) ) === false ) ? 'New access failed.' : 'New access granted.'; //1
        //if user who is asking for claim was added to player access AND player ID added to user owned data then delete
//         if( $update_claim === "New access granted." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.'; //2
        if ($update_claim === "New access granted.") {
             $data = array('type' => 0); // Define the data to update
            $where = array('id' => $claim_id); // Define the condition for the update
            $result = $wpdb->update($wpdb_claims, $data, $where); // Execute the update query

            // Check if the update was successful
            $update_claim .= ($result === false) ? ' Claim update error.' : ' Claim update successful.';
        }
      }
    } else {
      $update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully.";
      $target_user->ID = intval($new_user);
//       if( $update_claim === "Claim updated successfully." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.'; //2
      if ($update_claim === "Claim updated successfully." ) {
             $data = array('type' => 0); // Define the data to update
            $where = array('id' => $claim_id); // Define the condition for the update
            $result = $wpdb->update($wpdb_claims, $data, $where); // Execute the update query

            // Check if the update was successful
            $update_claim .= ($result === false) ? ' Claim update error.' : ' Claim update successful.';
        }

    }
    //if we have the right permission and the target_data, then update the target record
    if( !empty( $target_user ) && !empty( $target_user->ID ) ) {
      $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_SET(COALESCE(access, '{}'), '$." . $claim_record->site_key . "', JSON_ARRAY_APPEND(COALESCE(JSON_EXTRACT(access, '$." . $claim_record->site_key . "'), '[]'), '$', " . $target_user->ID . ")) WHERE id = " . $claim_record->target . " AND NOT JSON_CONTAINS(JSON_EXTRACT(access, '$." . $claim_record->site_key . "'), '" . $target_user->ID . "', '$');" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully."; 
      //echo( $claim_record->target . ' parent ' . $claim_record->owner_id);           
      //parent, player
      $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
      $status = 2;
    } else {
      $update_claim .= ' Missing target_user data, record permissions failed.';
    }
  } else {
    // To approve claim comes from satellite websites or G365 claim admin dashboard. If record does not contain G365 data, updating access base on site key
    if( $claim_record->site_key === 'G3D' || $claim_record->site_key === 'G3P' ){
      $update_claim = '';
      $update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully.";
//       if( $update_claim === "Claim updated successfully." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.'; //2
      if ($update_claim === "Claim updated successfully.") {
             $data = array('type' => 0); // Define the data to update
            $where = array('id' => $claim_id); // Define the condition for the update
            $result = $wpdb->update($wpdb_claims, $data, $where); // Execute the update query

            // Check if the update was successful
            $update_claim .= ($result === false) ? ' Claim update error.' : ' Claim update successful.';
        }
      if($claim_record->site_key === 'G3D'){
          $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'G3D', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
          //echo( $claim_record->target . ' parent ' . $claim_record->owner_id);           
          //parent, player
          $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
          $status = 2;
      }
      if($claim_record->site_key === 'G3P'){
        $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'G3P', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
        //echo( $claim_record->target . ' parent ' . $claim_record->owner_id);           
        //parent, player
        $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
        $status = 2;
      }  
    }
    // Else if access record does contains G365 data, proceed to insert requested access with satellite site key
    else if( $claim_record->site_key === 'EBD' || $claim_record->site_key === 'EBP' ){
      $update_claim = '';
      $update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully.";
//       $target_user->ID = intval($new_user);
//       if( $update_claim === "Claim updated successfully." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.'; //2
      if ($update_claim === "Claim updated successfully.") {
             $data = array('type' => 0); // Define the data to update
            $where = array('id' => $claim_id); // Define the condition for the update
            $result = $wpdb->update($wpdb_claims, $data, $where); // Execute the update query

            // Check if the update was successful
            $update_claim .= ($result === false) ? ' Claim update error.' : ' Claim update successful.';
        }
      if($claim_record->site_key === 'EBD'){
          $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'EBD', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
          
          $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
          $status = 2;
      }
      if($claim_record->site_key === 'EBP'){
        $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'EBP', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
        
        $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
        $status = 2;
      }  
    }
    // If this is a OGP claim
    else if( $claim_record->site_key === 'OGD' || $claim_record->site_key === 'OGP' ){
      $update_claim = '';
      $update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully.";
//       $target_user->ID = intval($new_user);
//       if( $update_claim === "Claim updated successfully." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.';
      if ($update_claim === "Claim updated successfully.") {
             $data = array('type' => 0); // Define the data to update
            $where = array('id' => $claim_id); // Define the condition for the update
            $result = $wpdb->update($wpdb_claims, $data, $where); // Execute the update query

            // Check if the update was successful
            $update_claim .= ($result === false) ? ' Claim update error.' : ' Claim update successful.';
        }
      if($claim_record->site_key === 'OGD'){
        $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'OGD', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
        
        $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
        $status = 2;
      }
      if($claim_record->site_key === 'OGP'){
        $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'OGP', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
        
        $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
        $status = 2;
      }
    } 
    // If this is a SPD
    else if( $claim_record->site_key === 'SPD' || $claim_record->site_key === 'SPP' ){
      $update_claim = '';
      //$update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully.";
      
      //if the target user is available add the refernece to thier account, otherwise change the status for when they do get created
      $access_add = array( $access_type => array($claim_record->target) );
      $update_claim = '';
      $target_user = new stdClass;
      $target_user = get_user_by( 'email', $claim_record->email );
      if( $target_user === false ) {
        $update_claim .= ( $wpdb->query( "UPDATE $wpdb_claims SET status = 2 $data = array('type' => 0);WHERE id = $claim_id;" ) === false ) ? "Claim update failed." : "Claim updated successfully for future use.";
      } else {
        //once the target user has the reference delete the claim record.
        //return (object) array( 'status' => $status, 'message' => g365_reference_data_integrator( $access_add, get_user_meta( $target_user->ID, '_user_owns_g365', true) ), 'target_user' => $claim_record->email, 'access_id' => $access_add);
        $update_claim .= ( update_user_meta( $target_user->ID, '_user_owns_g365', g365_reference_data_integrator( $access_add, get_user_meta( $target_user->ID, '_user_owns_g365', true) ) ) === false ) ? 'New access failed.' : 'New access granted.'; //1
        //if user who is asking for claim was added to player access AND player ID added to user owned data then delete
//         if( $update_claim === "New access granted." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.'; //2
        
        if ($update_claim === "New access granted.") {
             $data = array('type' => 0); // Define the data to update
            $where = array('id' => $claim_id); // Define the condition for the update
            $result = $wpdb->update($wpdb_claims, $data, $where); // Execute the update query

            // Check if the update was successful
            $update_claim .= ($result === false) ? ' Claim update error.' : ' Claim update successful.';
        }
        
      }
      
      //$target_user->ID = intval($new_user);
      //if( $update_claim === "Claim updated successfully." ) $update_claim .= ( $wpdb->delete( $wpdb_claims, array( 'id' => $claim_id )) === false )  ? ' Claim clean-up error.' : ' Claim clean-up successful.';
      if($claim_record->site_key === 'SPD'){
        $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'SPD', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
        //parent, player
        $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
//         echo( $claim_record->target . ' parent ' . $claim_record->owner_id . 'email: ' . $sending_email['message']);           
        $status = 2;
      }
      if($claim_record->site_key === 'SPP'){
        $update_claim .= ( $wpdb->query( "UPDATE $target_db SET access = JSON_MERGE_PRESERVE(access, JSON_OBJECT( 'SPP', JSON_ARRAY(".$claim_record->owner_id.") )) WHERE id = " . $claim_record->target . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : " Record updated successfully.";
        //parent, player
        $sending_email = g365_send_claim_acceptance($claim_record->owner_id, $claim_record->target);
//         echo( $claim_record->target . ' parent ' . $claim_record->owner_id . 'email: ' . $sending_email['message']);           
        $status = 2;
      }
    } else {
      return 'Incorrect record owner. You do not have permission to grant access to other users.<br>Please contact a customers service representative.';
    }
  }
  return (object) array( 'status' => $status, 'message' => $update_claim, 'target_user' => $claim_record->email, 'access_id' => $access_add);
}

// function claim_delete($claim_data){
  
//   echo("<script>console.log('test');</script>");
  
// }


//add data
add_action( 'wp_ajax_nopriv_g365_ajax_request_data', 'g365_ajax_request_data' );
add_action( 'wp_ajax_g365_ajax_request_data', 'g365_ajax_request_data' );

//use the ajax wordpress pathway to manage external data inputs
function g365_ajax_request_data(){
	//what ever data needs to be sent back
	$data_result = array(
		'status' => 'failed',
		'message'=> 'Default'
	);
  $remote_ip = $_SERVER['REMOTE_ADDR'];
  $remote_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? explode('; ', $_SERVER['HTTP_USER_AGENT']) : '' );
  $remote_url = ((strpos($remote_url[0], 'WordPress') === 0 && strpos($remote_url[1], 'https://') === 0) ? $remote_url[1] : '' );
  //see if we have a site record for the creating site user by ip
  $keys_by_domain = array_filter( g365_return_keys('keys_by_domain'), function($data) use ($remote_ip){ return $data[ 'ip' ] === $remote_ip; });
  //if we havr any key candidates match to domain name
  if( empty($keys_by_domain) || $remote_url === '' || !isset($keys_by_domain[$remote_url]) ) {
    $data_result['message'] = 'No key found';
  } else {
    $keys_by_domain_key = $keys_by_domain[$remote_url];
    //check that we have keys to send
    if( empty($keys_by_domain_key['trans_key'])  || empty($keys_by_domain_key['trans_id']) ) $data_result['message'] =  'Missing Key and/or ID credentials, please contact your administrator.';
    //we have the required info for the provided domain
  }
  if( $data_result['message'] === 'Default' ) {
    //get auth keys
    $auth_keys = base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6));
    //set default error message
    $data_set = ['error' => 'Error with Auth Keys, please contact your G365 Representative.'];
    //check keys
    if( $keys_by_domain_key['trans_key'] === substr($auth_keys, 0, 34) && $keys_by_domain_key['trans_id'] === substr($auth_keys, -29) ) {
      //if there is an admin user_id then activate it
      if( !empty( $keys_by_domain_key['user_id']) ) wp_set_current_user( $keys_by_domain_key['user_id'] );
      //sanitize function call and give it a try
      $func_handle = preg_replace('/[^a-zA-Z0-9_"\']/', '', $_POST['data_key']);
      if( is_callable( $func_handle ) && is_array($_POST['data_args']) ) {
        $data_set = call_user_func_array( $func_handle, array_map(function($val){ return ( $val === 'null' ) ? null : $val; }, $_POST['data_args']) );
        //change the message type to success
        if( $data_set !== FALSE ) $data_result['status'] = 'success';
        //make it an array so it sends uniformly
        if( gettype($data_set) != 'object' && gettype($data_set) != 'array' ) $data_set = [ $data_set ];
      } else {
        //auth key match error
        $data_set = ['error' => 'Error with function call, please contact your G365 Representative.'];
      }
      if( !is_null($_POST['data_key_2']) ) {
        //sanitize function call and give it a try
        $func_handle_2 = preg_replace('/[^a-zA-Z0-9_"\']/', '', $_POST['data_key_2']);
        if( is_callable( $func_handle_2 ) && is_array($_POST['data_args_2']) ) {
          $data_set_2 = call_user_func_array( $func_handle_2, array_map(function($val){ return ( $val === 'null' ) ? null : $val; }, $_POST['data_args_2']) );
          //change the message type to success
          if( $data_set_2 !== FALSE ) $data_result['status_2'] = 'success';
          //make it an array so it sends uniformly
          if( gettype($data_set_2) != 'object' && gettype($data_set_2) != 'array' ) $data_set_2 = [ $data_set_2 ];
        } else {
          //auth key match error
          $data_set_2 = ['error' => 'Error with function call, please contact your G365 Representative.'];
        }
        $data_set = [ $data_set, $data_set_2];
      }
    }
    //send it back
    $data_result['message'] = $data_set;
  }
  echo json_encode($data_result);
	die();
}

//add data
add_action( 'wp_ajax_nopriv_g365_ajax_registration_form', 'g365_ajax_registration_form' );
add_action( 'wp_ajax_g365_ajax_registration_form', 'g365_ajax_registration_form' );

//use the ajax wordpress pathway to manage external data inputs
function g365_ajax_registration_form(){
	require( 'inc/json-response-headers.php' );
	require( 'inc/session-handler.php' );
  
	if ( empty($_SESSION['token']) ) die('Bad token. Please reload page.');
	if ( empty($_SESSION['time']) ) die('Invalid time. Please reload page.');
	if ( empty($_SESSION['anti_bot']) )  die('Missing AntiBot. Please reload page.');
	//validate form types requested
	$data_set = $_POST['g365_data_set'];
	$form_types = g365_validate_data_type( array_keys($data_set) );
  //print_r($form_types);
	if ( array_search( null, $form_types ) ) die('<div class="error"><h4>One or more form types (' . implode(', ', array_keys($data_set)) . ') not found.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
  //set the admin key if we have one
  if( !isset($data_owner_reference['og_ed']) ) $data_owner_reference['og_ed'] = array(); 
  $data_owner_reference['og_ed'][] = $new_data['id'];

  $access_level = g365_check_user_access( (( isset($_POST['g365_admin_key']) ) ? $_POST['g365_admin_key'] : null) );

	//if we have a good type var load the db resources 
	global $wpdb;
	//all the tables we might have to get data from
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_teams = $wpdb->g365_teams;
	$wpdb_rosters = $wpdb->g365_rosters;
	$wpdb_coaches = $wpdb->g365_coaches;
	$wpdb_games = $wpdb->g365_games;
  $wpdb_claims = $wpdb->g365_claims;
  $wpdb_awards = $wpdb->g365_awards;
  $wpdb_team_stats = $wpdb->g365_team_stats;

	//what ever data needs to be sent back
	$data_result = array(
		'status' => 'success',
		'message'=> (object) array()
	);
  //holder for ownership data
  $data_owner_reference = array();
	//loop through each data type and process the request
	foreach( $form_types as $type => $type_key ) {
		//setup var for the result of type
		$data_result['message']->{$type} = (object) array();
		//switch between serving forms, processing data, and serving data
    
//     $player_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
//     include_once $player_template_file;
//     $data_result['message']->player_names = (object) array();
//     $data_result['message']->player_names->form_template_min = $player_registration_form_min;
//     $data_result['message']->player_names->form_template_result = $player_registration_result;
//     $data_result['message']->player_names->form_template_input_item = $player_registration_input_item;

    
		switch( $type_key ) {
      case 1: //pl: add/edit player
      case 6:  //add/edit player stat: pl_ev: managed certification
      case 7: //player_names: player search
      case 20: //player_names_admin: player search admin
      case 24: //pl_ed: player stand alone
      case 35: //player_admin: player single stand alone admin
      case 37: //player_rosters: add player to roster
      case 38: //player_rosters_admin: add player to roster admin
			case 47: //player_award: add player to awards
				switch( $data_set[$type]['proc_type'] ) {
					//what are we doing here?
					case 'get_form':
						if(
              !empty($data_result['message']->{$type}->form_template) &&
              !empty($data_result['message']->{$type}->form_template_init) &&
              !empty($data_result['message']->{$type}->form_template_result) &&
              !empty($data_result['message']->club_names->form_template_min) &&
              !empty($data_result['message']->club_names->form_template_result) && 
              !empty($data_result['message']->school_names->form_template_min) &&
              !empty($data_result['message']->school_names->form_template_result)
            ) break;
						$player_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
						$school_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_school_registration_form.php';
						$club_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_club_registration_form.php';
						include_once $player_template_file;
						include_once $school_template_file;
						include_once $club_template_file;
						if ( empty($player_registration_form) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Result.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_form_admin) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_init_admin) ) die('<div class="error"><h4>Cannot find extension file. Admin Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($school_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Sc-Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($school_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Sc-Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Ct-Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Ct-Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');

            if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
            if( !isset($data_result['message']->school_names) ) $data_result['message']->school_names = (object) array();
            if( !isset($data_result['message']->club_names) ) $data_result['message']->club_names = (object) array();

						//these two variables are located in loaded file
            switch( $type_key ) {
              case 20:
                $data_result['message']->{$type}->form_template_init = ( current_user_can('administrator') ) ? $player_registration_init_admin : $player_registration_init;
                $data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $player_registration_form_admin : $player_registration_form;
                break;
              case 24: //pl_ed: stand alone, player edit
                if ( empty($player_registration_init_sl) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($player_registration_form_sl) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $player_registration_init_sl;
                $data_result['message']->{$type}->form_template = $player_registration_form_sl;
                break;
              case 35: //player_admin: player single stand alone admin
                if ( empty($player_registration_init_sl_admin) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $player_registration_init_sl_admin;
                break;
              case 47: //player_award: add player to awards
                $data_result['message']->{$type}->form_template_init = $player_award_search_init;
                $data_result['message']->{$type}->form_template = $player_award_search_form;
                break;
              default:
                $data_result['message']->{$type}->form_template_init = $player_registration_init;
                $data_result['message']->{$type}->form_template = $player_registration_form;
            }
						if( $type_key === 20 || $type_key === 24 || $type_key === 35 ) {
							$curr_years = intval(date("Y"));
							$curr_years = '<option value="">Please select</option>' . implode('', array_map(function($a){ return '<option value="' . $a . '">' . $a . '</option>'; }, range($curr_years - 2, $curr_years - 20)));
							$data_result['message']->{$type}->form_defaults = (object) array('current_birth_years' => $curr_years);
							$data_result['message']->school_names->form_template_min = $school_registration_form_min;
							$data_result['message']->school_names->form_template_result = $school_registration_result;
							$data_result['message']->club_names->form_template_min = $club_registration_form_min;
							$data_result['message']->club_names->form_template_result = $club_registration_result;
						}
            $data_result['message']->{$type}->form_template_result = $player_registration_result;
						//set the styles flag
// 						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
            if( empty($data_set[$type]['ids']) ) break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
						if( empty($ids) ) die('<div class="error"><h4>Cannot parse player ids.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//setup var for type
						//pull user data\
						foreach( $ids as $pl_dex => $pl_val ) $data_result['message']->{$type}->{$pl_val} = g365_truncate_data( g365_get_profile( $pl_val, true), $type_key, $access_level );
						//add parameters that are needed for a form
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//process and check incoming data
						//parse the javascript form serialization
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						
						
						//if we get a request from the awards players group, don't process it at all, just kick it back
						if( $type_key == 47 ) {
							$data_result['message']->{$type} = $data_set[$type]['form_data'];
              echo json_encode($data_result);
              die();
						}
							
							

						//sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) { 
//             echo json_encode($new_data);
							//let's assign an id if we don't have one. Presumabily it's a new user
							if( empty($new_data['id']) ) {
                $first_name = g365_process_data_point( 'first_name', $new_data['data']['first_name'] );
                $last_name = g365_process_data_point( 'last_name', $new_data['data']['last_name'] );
                $new_player_data = array(
                  'first_name' => $first_name,
                  'last_name' => $last_name,
                  'nickname' => g365_process_data_point( 'nickname', ((!empty($new_data['data']['nickname']) && current_user_can( 'administrator' )) ? $new_data['data']['nickname'] : $first_name . '-' . $last_name))
                );
                //try to insert a new record
								$new_id = $wpdb->insert( $wpdb_players, $new_player_data );

//                 echo('hi ' . $new_id);
//             $data_result['message']->{$type} = array_merge(array('id'=>$wpdb->insert_id, 'resuklt'=>$new_id), $new_player_data);
//             die();

                //is there already a record
								if( $new_id === false ) {
                  //if we already have a record pull it to see if we should take further action
                  $new_id = $wpdb->get_row( "SELECT * FROM $wpdb_players WHERE nickname LIKE '" . $new_player_data['nickname'] . "'" );
                  //if we have data make some descitions
                  if( $new_id !== null ) {
                    //if we are an admin user skip all the locking
                    if( current_user_can( 'administrator' ) || current_user_can( 'front_editor' ) ) {
                      //if we have a birthday do some checking
                      if( empty($new_id->birthday) || $new_id->birthday == g365_process_data_point( 'birthday', $new_data['data']['birthday']) ) {
                        $new_data['id'] = $new_id->id;
                      } else {
                        if( !empty($new_data['data']['state']) ) {
                          $state = g365_process_data_point( 'state', $new_data['data']['state'] );
                          $new_player_data['nickname'] = g365_process_data_point( 'nickname', $first_name . '-' . $last_name . '-' . $state);
                          //try to insert a new record
                          $new_id = $wpdb->insert( $wpdb_players, $new_player_data );
                          if( $new_id === null ) {
                            $data_result['message']->{$type}->{$form_group} = array(
                              'message'       => 'Could not match birthdate, player existing in same state. Can\'t update profile.',
                              'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                            );
                            continue;
                          }
                          $new_data['id'] = $new_id->id;
                          unset( $new_data['data']['nickname'] );
                        } else {
                          $data_result['message']->{$type}->{$form_group} = array(
                            'message'       => 'Could not match birthdate, no location data. Can\'t update profile.',
                            'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                          );
                          continue;
                        }
                      }
                    } else {
                      //if there isn't any data to check against, claim this profile
                      if( $new_id->access === null ) {
                        $new_data['id'] = $new_id->id;
                        $new_data['data']['access'] = $access_level;
                        if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
                        $data_owner_reference['pl_ed'][] = $new_data['id'];
                      } else {
                        //pasre the access array
                        $new_id->access = json_decode($new_id->access);
                        //if this user isn't on the list, exit
                        if( empty($new_id->access->{$access_level[1]}) ) {
//                           //if we have a birthday do some checking
//                           if( empty($new_id->birthday) || $new_id->birthday == g365_process_data_point( 'birthday', $new_data['data']['birthday']) ) {
//                             $new_data['id'] = $new_id->id;
//                           } else {
//                             if( !empty($new_data['data']['state']) ) {
//                               g365_process_data_point( 'state', $new_data['data']['state'] );
//                               $new_player_data['nickname'] = g365_process_data_point( 'nickname', $first_name . '-' . $last_name . '-' . $state);
//                               //try to insert a new record
//                               $new_id = $wpdb->insert( $wpdb_players, $new_player_data );
//                             }
//                             if( $new_id === null ) {
//                               $data_result['message']->{$type}->{$form_group} = array(
//                                 'message'       => 'Could not match birthdate, name record exists in same state. Please load profile to edit.',
//                                 'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
//                               );
//                               continue;
//                             }
//                             $new_data['id'] = $new_id->id;
//                           }
//                           $new_data['data']['access'] = $access_level;
//                           if( !empty($new_data['id']) ) {
//                             if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
//                             $data_owner_reference['pl_ed'][] = $new_data['id'];
//                           }
                          $new_data['data']['access'] = $access_level;
                          if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
                          $data_owner_reference['pl_ed'][] = $new_data['id'];
                        } else {
                          if( !in_array( $access_level[2], $new_id->access->{$access_level[1]} ) ) {
                            $data_result['message']->{$type}->{$form_group} = array(
                              'message'       => 'This player profile is already claimed.',
                              'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                            );
                            continue;
                          }
                        }
                        $new_data['id'] = $new_id->id;
                      }
                    }
                  } else {
                    //if we tried to pull the preventing record and we fail
                    $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => g365_output_db_error('Failed to generate new player id.'),
                      'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                    );
                    continue;
                  }
                } else {
                  //new data was added
                  
  								$new_data['id'] = $wpdb->insert_id;
                  if( !current_user_can( 'administrator' ) ) {
                    if( !empty($new_data['id']) ) {
                      if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array();
                      $data_owner_reference['pl_ed'][] = $new_data['id'];
                    }
                    $new_data['data']['access'] = $access_level;
                  }
                }
              } else {
                //add in access if we are not an admin and there is a command to add
                if( !current_user_can( 'administrator' ) ) {
                  //get current access to make compare against
                  $new_access = json_decode($wpdb->get_var( 'SELECT access FROM $wpdb_players WHERE id = ' . ($new_data['id']) ));
                  //if profile hasn't been claimed for this site, claim it, otherweise exit process
                  if( empty($new_access->{$access_level[1]}) ) {
                    $new_data['data']['access'] = $access_level;
                    if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array();
                    $data_owner_reference['pl_ed'][] = $new_data['id'];


//               $data_result['message']->{$type} = $new_data;
//               echo json_encode($data_result);
//               die();


                  } else {
                    if( !in_array( $access_level[2], $new_access->{$access_level[1]} ) ) {
                      $data_result['message']->{$type}->{$form_group} = array(
                        'message'       => 'This player profile is already claimed for this site.',
                        'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                      );
                      continue;
                    }
                  }
                }
              }
              //add the id into the dataset
              $new_data['data']['id'] = $new_data['id'];
							//build sql string for this record
							$sql_prepare_query = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array();
							//process each data point and add to the query string
							foreach( $new_data['data'] as $data_name => &$data_value ) {
                //if we don't need to process a datapoint, skip it
                if( $data_name === 'revoke_access' || ( $data_name === 'nickname' && !current_user_can( 'administrator' ) ) || ( $data_name === 'nickname' && $data_value === '' ) ) continue;
                //santicze and process the raw data based on type
								$data_value = g365_process_data_point( $data_name, $data_value);
//                 echo("<script>console.log(hi: '$data_name . $data_value');</script>");
                
                //add the processed value to the array
                $new_data['data_processed']->{$data_name} = $data_value;
                //if the data is an image, process it accordingly
                if($data_name === 'profile_img_data' || $data_name === 'recard_img_data' || $data_name === 'bcert_img_data' ) {
                  //if we don't have a specific command, then continue
                  if( $data_value == '' ) continue;
                  //get reference for image upload directory
                  $uploads_url = wp_upload_dir();
                  //we need to make sure that the image name has been generated before we can process the image data
//                   echo($new_data['data']['id']);
                  $image_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name'] ) . '_' . $new_data['data']['id'] . '.jpg');
                  
                  //add timestamp to filename for notes
                  $pst_timestamp = time() - 28800; // subtract 8 hours (28800 seconds)
                  $image_name_timestamped = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name'] ) . '_' . $new_data['data']['id'] . '_' . date("Y-m-d H:i:s", $pst_timestamp) . '.jpg'); 

//                   echo($image_name);
                  //put some defaults together
                  $image_content_name = 'Default';
                  $image_content = 'default';
                  
                  //the settings for image processing based on type
                  switch( $data_name ) {
                    case 'profile_img_data':
                      $image_content_name = "Profile Image";
                      $image_content = 'profile_img';
                      $image_url = $uploads_url['basedir'] . '/player-profiles/';
                      $image_current = "profile_img";
                      break;
                    case 'recard_img_data':
                      $image_content_name = "Report Card Image";
                      $image_content = 'recard_img';
                      $image_url = $uploads_url['basedir'] . '/player-reportcards/';
                      $image_current = "json_extract(notes, '$.$image_content')";
                      break;
                    case 'bcert_img_data':
                      $image_content_name = "Birth Certificate Image";
                      $image_content = 'bcert_img';
                      $image_url = $uploads_url['basedir'] . '/player-birthcerts/';
                      $image_current = "json_extract(notes, '$.$image_content')";
                      break;
                  }
                  //see if we have a current image entry
                  $current_image = $wpdb->get_var( 'SELECT ' . $image_current . ' FROM ' . $wpdb_players . ' WHERE  id = ' . $new_data['data']['id'] );
                  $current_image_url = $image_url . $current_image;
                  $timestamped_url = $image_url . $image_name_timestamped;
                  $image_url = $image_url . $image_name;
                  
                  //if we don't have a value remove any leftovers
                  if($data_value === 'null') {
                    if( file_exists( $current_image_url ) ) unlink($current_image_url);
                    $image_name = null;
                    $image_name_timestamped = null;
                  } else {
                    //write the image to the server
                    $file_size = file_put_contents( $image_url, base64_decode( $data_value ) );
                    $decode_value = base64_decode( $data_value);
                    
                    $file_size_timestamped = file_put_contents( $timestamped_url, base64_decode( $data_value ) );
                    $decode_value = base64_decode( $data_value);
                    
//                     echo("<script>console.log('DUUU : $timestamped_url ');</script>");
                    
              
//                     if the write fails, make a note and continue 
                    if( $file_size === false ) {
                      $new_data['data_processed'][$data . '_error'] = $image_content_name . ' write error.';
//                       $new_data['data_processed']->{$data . '_error'} = $image_content_name . ' write error.';
                      continue;
                    }
                    if( !empty($current_image) && $current_image !== $image_name && file_exists($current_image_url) ) unlink($current_image_url);
                    file_put_contents($image_url, base64_decode( $data_value ));
                    $new_data['data_processed']->{$image_content} = $image_name;
                  }
                  $get_player_img = 'SELECT profile_img FROM ' . $wpdb_players . ' WHERE id = ' . $new_data['data']['id'];
                  $player_img_url = $wpdb->get_var($get_player_img);
                  //depending on the type, add the url to the db string
                  switch( $data_name ) {
                    case 'profile_img_data':
                      $sql_prepare_query .= ", $image_content = " . (($image_name === null) ? 'NULL' : "'$image_name'");
                      if(empty($player_img_url))  { 
                        $sql_prepare_query .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($image_content) . "', '$image_name_timestamped')";
                      } else { 
                        $sql_prepare_query .= ", notes = JSON_ARRAY_APPEND(COALESCE(notes, '{}'), '$." . strtolower($image_content) . "', '$image_name_timestamped')";
                      } 
                      break;
                    case 'recard_img_data':
                    case 'bcert_img_data':
                      if( $data_value === 'null' ) {
                        $sql_prepare_query .= ", notes = JSON_REMOVE(notes, '$." . strtolower($image_content) . "')";
                      } else {
                        $sql_prepare_query .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($image_content) . "', '$image_name')";
                      }
                      break;
                  }
                  continue;
                }
								if( $data_name == 'instagram' || $data_name == 'twitter' || $data_name == 'facebook' || $data_name == 'snapchat' ) {
									if( $data_value === 'null' ) {
										$sql_prepare_query .= ", social = JSON_REMOVE(social, '$." . ucfirst($data_name) . "')";
									} else {
										$sql_prepare_query .= ", social = JSON_SET(COALESCE(social, '{}'), '$." . ucfirst($data_name) . "', '$data_value')";
									}
									continue;
								}
								if( $data_name == 'jersey_size' ) {
									if( $data_value === 'null' ) {
										$sql_prepare_query .= ", notes = JSON_REMOVE(notes, '$." . strtolower($data_name) . "')";
									} else {
										$sql_prepare_query .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($data_name) . "', '$data_value')";
									}
									continue;
								}
                if( $data_name == 'bcert_resub' ) {
									if( $data_value === 'null' ) {
//                     echo("<script>console.log('DUUU test : $image_url ');</script>");
										$sql_prepare_query .= ", notes = JSON_REMOVE(notes, '$." . strtolower($data_name) . "')";
									} else {
										$sql_prepare_query .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($data_name) . "', '$data_value')";
									}
									continue;
								}
                if( $data_name == 'recard_resub' ) {
									if( $data_value === 'null' ) {
//                     echo("<script>console.log('DUUU test : ');</script>");
										$sql_prepare_query .= ", notes = JSON_REMOVE(notes, '$." . strtolower($data_name) . "')";
									} else {
										$sql_prepare_query .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($data_name) . "', '$data_value')";
									}
									continue;
								}
								if( $data_name == 'access' ) {
                  if( current_user_can('administrator') && ($type_key === 20 || $type_key === 35) && gettype($data_value) === 'string' ) {
                    $data_value = ( $data_value === '' ) ? 'null' : "'$data_value'";
                    $sql_prepare_query .= ", access = $data_value";
                  } else {
                    if( $new_data['data']['revoke_access'] === 'true' ) {
                      $sql_prepare_query .= ", access = JSON_REMOVE(access, '$[" . $data_value[1] . "]" . (($data_value[2] === 'null') ? "" : "[" . intval($data_value[2]) . "]") . "')";
                    } else {
                      if( !empty($data_value[2]) ) $sql_prepare_query .= ", access = JSON_SET(COALESCE(access, '{}'), '$." . $data_value[1] . "', JSON_ARRAY_APPEND(COALESCE(JSON_EXTRACT(access, '$." . $data_value[1] . "'), '[]'), '$', " . intval($data_value[2]) . "))";
                    }
                  }
									continue;
								}
								$data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
								$sql_prepare_query .= ", $data_name = $data_value";
							}
//               echo json_encode($new_data['data_processed']);
              //echo('type1' . $type_key);
              //echo('access' . $access_level[0]); 
              //truncate all current data continue working here
              $data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
              //pass along the wrapper if we have it
              if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$new_data['id']}['wrapper_id'] = $new_data['wrapper_id'];
              //change the verification status based on what was uploaded
              if( empty($new_data['data_processed']->verified) && (!empty($new_data['data_processed']->bcert_img) || !empty($new_data['data_processed']->rcard_img)) ) $sql_prepare_query .= ", verified = 1";
              //create the element title for result display
              $data_result['message']->{$type}->{$new_data['id']}['element_title'] = ((empty($new_data['data_processed']->first_name)) ? $wpdb->get_var( "SELECT name FROM $wpdb_players WHERE id = " . ($new_data['id']) ) : $new_data['data_processed']->first_name . ' ' . $new_data['data_processed']->last_name);
              //run the query
              

//               $data_result['message']->{$type} = "UPDATE $wpdb_players SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";";
//               echo('yo ' . json_encode($data_result['message']->{$type}->{$new_data['id']}));
//               die();

              
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_players SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";

              //if we actually made a new player, send the welcome message to the owning user.
              if( !current_user_can( 'administrator' ) && !empty($access_level[3]) && !empty($new_data['data']['access']) ) {
                //get the target url for the editing link
                $keys_by_domain = array_filter( g365_return_keys('keys_by_domain'), function($data) use ($access_level){ return $data[ 'id' ] === $access_level[1]; });
                $keys_by_domain_key = array_keys($keys_by_domain)[0];

                $html = '<div style="background-color:#f7f7f7;margin:0;padding:70px;"><div style="background:#1a315b;color:#ffffff;border-bottom:0;font-weight:bold;vertical-align:middle;font-family: Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0;padding:24px;display:block"><h2>';
                $html .= 'Thank you for creating a new G365 Player Profile';
                $html .= '</h2></div><p style="background:#ffffff;padding:40px 20px;margin:0;">';
                $html .= 'This profile is accessible/editable from your account.<br><a href="' . $keys_by_domain_key . '/account/player_editor/">You can login here to see your data.</a>';
                $html .= '</p></div>';
                send_html_email( $access_level[3], 'New Player Profile Created', $html );
              }
						}
						break;
					case 'claim_data':
//             $data_set[pl_ev] = {
//               ids: "6123",
//               proc_type: "claim_data"
//             }
            //get type key number from requesting site
//             echo("<script>console.log('RASENGAN : ');</script>");
            $g365_claim_type_key = g365_return_keys('g365_claim_type_key');
            if( in_array($type, array_keys($g365_claim_type_key)) ) {
              $g365_claim_type_key = $g365_claim_type_key[$type];
            } else {
              $data_result['message']->{$type} = array(
                'message'       => 'No data type match. Please contact your representative.',
                'element_title' => "Current Claim"
              );
              break;
            }
            //set database table for type
            $tbl_target = '';
            switch( $g365_claim_type_key ) {
              case 1:
                $tbl_target = $wpdb_players;
                break;
              case 2:
                $tbl_target = $wpdb_org;
                break;
              case 3:
                $tbl_target = $wpdb_coaches;
                break;
            }
            //get data owner reference
            $data_owner = $wpdb->get_row( 'SELECT name, access FROM ' . $tbl_target . ' WHERE  id = ' . $data_set[$type]['ids'] );
            if( $data_owner === false ) {
              $data_result['message']->{$type} = array(
                'message'       => 'No data for supplied id. Please contact your representative.',
                'element_title' => "Current Claim"
              );
              break;
            }
            $data_name = $data_owner->name;
            $data_owner = json_decode($data_owner->access);
            //just grab the first user id, presumably they are the master owner
            $data_owner = $data_owner->{$access_level[1]}[0];
            //claim data array
            $owner_info = $data_set[$type]['user_info'];
            $owner_info_array = array(
              'name' => $owner_info[0],
              'phone' => $owner_info[1],
              'relation' => $owner_info[2],
              'birthday' => $owner_info[3]
            );
            $owner_info = json_encode($owner_info_array);
            
            $claim_data = array( 'type' => $g365_claim_type_key, 'target' => intval($data_set[$type]['ids']), 'site_key' => $access_level[1], 'email' => $access_level[3], 'status' => 1, 'owner_id' => $access_level[2], 'owner_data' => $owner_info );
//             $claim_data = array( 'type' => $g365_claim_type_key, 'target' => intval($data_set[$type]['ids']), 'site_key' => 'SPD', 'email' => $access_level, 'status' => 1, 'owner_id' => 1001000 );
            //add the record to claim table
            
            $new_claim = $wpdb->insert( $wpdb_claims, $claim_data );
            $new_claim_status = 'Your request to access this account has been submitted. You will be notified via email when the request has been approved. If you have any additional questions or need further assistance, please contact our customer service team <a href="/contact" style="color: lightblue; text-transform: uppercase;">here</a>.';
            $sending_email = g365_send_claim_new_notice('44708', $claim_data);
//             $sending_email = g365_send_claim_new_notice('27712', $claim_data);
            //is there already a record 
            if( $new_claim === false ) {
              $ref_id = $wpdb->get_var( "SELECT id FROM $wpdb_claims where type = " . $claim_data['type'] . " AND target = " . $claim_data['target'] . " AND site_key LIKE '" . $claim_data['site_key'] . "'" . " AND email LIKE '" . $claim_data['email'] . "'" );
              $new_claim_status = 'Your request to access this account has been re-submitted. Please contact customer service if you require further assistance1, <a style="color: lightblue; text-transform: uppercase;" href="/contact">here</a>.';
            } else {
              $ref_id = $wpdb->insert_id;
            }
            //send notice to owner site
//             $owner_notice_result = g365_data_sender( $access_level, 'notify', array($data_owner, $data_name, $access_level[3], $ref_id) );
            //check the outcomes and create a status update to send back.
            if( is_array($owner_notice_result) ) {
              $owner_notice_result['claim_status'] = $new_claim_status;
              $data_result['message']->{$type} = $owner_notice_result;
            } else {
              $data_result['message']->{$type} = $new_claim_status . ' ' . $owner_notice_result;
            }
						break;
				}
				break;
//       case 6:  //add/edit player stat: pl_ev: managed certification
      case 13: //add/edit player stat: player_event: stand alone stat
      case 14: //add/edit player stat: pl_cert: stand alone certification
      case 19: //add      player stat: pl_cert_sl: single input, stand alone
      case 15: //add/edit player stat: club_team managed, player to team
      case 16: //add/edit player stat: camps mananged
      case 18: //add/edit player stat: training managed
      case 27: //add/edit player stat: player_event_admin: admin event data manager, stand alone
      case 52: //add/edit player stat: hhh_player_event_admin: admin event data manager, stand alone
      case 28: //edit player stat: cps_manager: cps role data manager, stand alone
      case 43: //add/edit player stat: passport mananged
      case 48: //add/edit player stat: digital coaching package managed
      case 49: // Claim player from player photo
	    case 53: //add/edit team stat: team_event
      case 54: //add/edit player stat: player_event: stand alone stat scope scouting
      case 55: //add/edit player stat: ss_player_event_admin: admin event data manager, stand alone
      case 56: //add/edit team stat: ss_team_event_admin: admin event data manager, stand alone
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
						if(
              !empty($data_result['message']->{$type}->form_template) &&
              !empty($data_result['message']->{$type}->form_template_init) &&
              !empty($data_result['message']->{$type}->form_template_result) &&
              !empty($data_result['message']->club_names->form_template_min) &&
              !empty($data_result['message']->club_names->form_template_result) && 
              !empty($data_result['message']->school_names->form_template_min) &&
              !empty($data_result['message']->school_names->form_template_result) &&
              (
                ( (current_user_can('administrator') || current_user_can('front_editor')) &&
                  !empty($data_result['message']->player_names_admin->form_template_min) &&
                  !empty($data_result['message']->player_names_admin->form_template_result)
                ) || (
                  !current_user_can('administrator') &&
                  !empty($data_result['message']->player_names->form_template_min) &&
                  !empty($data_result['message']->player_names->form_template_result)
                )
              )
            ) 
              break;
						$stat_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_stat_registration_form.php';
						$player_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
						$school_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_school_registration_form.php';
						$club_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_club_registration_form.php';
						include_once $stat_template_file;
						include_once $player_file_name;
						include_once $school_template_file;
						include_once $club_template_file;
						if ( empty($stat_registration_form_admin) ) die('<div class="error"><h4>Cannot find extension file. Admin Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($stat_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Result.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($school_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Sc-Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($school_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Sc-Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Ct-Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Ct-Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            if ( empty($player_registration_form_min_full) ) die('<div class="error"><h4>Cannot find extension file. Pl Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Pl Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');

            if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
            $data_result['message']->{$type}->additional_parts = "trend_";
            if( !isset($data_result['message']->school_names) ) $data_result['message']->school_names = (object) array();
            if( !isset($data_result['message']->club_names) ) $data_result['message']->club_names = (object) array();

            switch( $type_key ) {
              case 6: //pl_ev: managed certification
                if ( empty($stat_registration_init_mem) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_mem) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $stat_registration_init_mem;
                $data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $stat_registration_form_admin : $stat_registration_form_mem;
                break;
              case 14: //pl_cert: stand-alone certification
                if ( empty($stat_registration_init_mem_ind) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_mem) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $stat_registration_init_mem_ind;
                $data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $stat_registration_form_admin : $stat_registration_form_mem;
                break;
              case 49: // photo_player_claim: stand-alone
                if ( empty($stat_registration_init_mem_ind) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_mem) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $stat_registration_init_player_photo;
                break;
              case 13: //player_event: stand alone general stats
                if ( empty($stat_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = ( current_user_can('administrator') ) ? $stat_registration_init_admin : $stat_registration_init;
                $data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $stat_registration_form : $stat_registration_form;
                break;
              case 53: //team_event: stand alone general stats
                if ( empty($team_stat_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($team_stat_registration_form) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = ( current_user_can('administrator') ) ? $team_stat_registration_init_admin : $team_stat_registration_init;
                $data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $team_stat_registration_form : $team_stat_registration_form;
                break;
              case 54: //ss_player_event: stand alone general stats scope scouting
                if ( empty($stat_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = ( current_user_can('administrator') ) ? $stat_registration_init_admin_ss : $stat_registration_init;
                $data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $stat_registration_form_ss : $stat_registration_form_ss;
                break;
              case 16: //camps: managed camps
                if ( empty($stat_registration_init_camp) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_camp) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if( current_user_can('administrator') ) {
                  $data_result['message']->{$type}->form_template_init = $stat_registration_init_admin;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_admin;
                } else {
                  $data_result['message']->{$type}->form_template_init = $stat_registration_init_camp;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_camp;
                }
                break;
              case 43: //add/edit player stat: passport mananged
                if ( empty($stat_registration_init_passport) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_passport) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if( current_user_can('administrator') ) {
                  $data_result['message']->{$type}->form_template_init = $stat_registration_init_passport;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_passport;
                } else if( current_user_can('player_editor') ) {
//                   $data_result['message']->{$type}->form_template_init = $stat_registration_init_passport;
//                   $data_result['message']->{$type}->form_template = $stat_registration_form_passport;
                  $data_result['message']->{$type}->form_template_init = g365_pp_checkout_form(['annual_form_template_init'=>$stat_registration_init_passport, 'monthly_form_template_init'=>$stat_registration_init_monthyly_passport])['form_init'];
                  $data_result['message']->{$type}->form_template = g365_pp_checkout_form(['annual_form_template'=>$stat_registration_form_passport, 'monthly_form_template'=>$stat_registration_form_monthly_passport])['form_template'];
                }else if( current_user_can('subscriber') ) {
                  $data_result['message']->{$type}->form_template_init = $stat_registration_init_passport;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_passport;
//                   $data_result['message']->{$type}->form_template_init = g365_pp_checkout_form(['annual_form_template_init'=>$stat_registration_init_passport, 'monthly_form_template_init'=>$stat_registration_init_monthyly_passport])['form_init'];
//                   $data_result['message']->{$type}->form_template = g365_pp_checkout_form(['annual_form_template'=>$stat_registration_form_passport, 'monthly_form_template'=>$stat_registration_form_monthly_passport])['form_template'];
                }else { //if not a admin or player/parent account it will bring them here giving them no form
                  $data_result['message']->{$type}->form_template_init = $stat_registration_init_passport;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_passport;
//                   $data_result['message']->{$type}->form_template_init = $stat_registration_init_passport;
//                   $data_result['message']->{$type}->form_template = $stat_registration_form_passport;
                }
                break;
              case 48: //add/edit player stat: digital coaching package managed
                if ( empty($dcp_registration_init_camp) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_dcp) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if( current_user_can('administrator') ) {
                  $data_result['message']->{$type}->form_template_init = $stat_registration_init_admin;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_admin;
                } else if( current_user_can('coach') ) {
                  g365_build_form_from_data( current_user_can('coach') );
                } else {
                  $data_result['message']->{$type}->form_template_init = $dcp_registration_init_camp;
                  $data_result['message']->{$type}->form_template = $stat_registration_form_dcp;
                }
                break;
              case 19: //pl_cert_sl: stand alone, single, cert add
                if ( empty($stat_registration_form_min_pl_cert_sl) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $stat_registration_form_min_pl_cert_sl;
                break;
              case 27: //player_event_admin: stand alone, single, event data add
                if ( empty($stat_registration_form_min_pl_ev_admin) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 echo("<script>console.log('domain... expansion ');</script>");
                $data_result['message']->{$type}->form_template_init = $stat_registration_form_min_pl_ev_admin;
                //set extra form parts
                $data_result['message']->{$type}->additional_parts .= ",stat_";
                break;
              case 52: //hhh_player_event_admin: stand alone, single, event data add
                if ( empty($hhh_stat_registration_form_min_pl_ev_admin) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 echo("<script>console.log('infinite... void ');</script>");
                $data_result['message']->{$type}->form_template_init = $hhh_stat_registration_form_min_pl_ev_admin;
                //set extra form parts
                $data_result['message']->{$type}->additional_parts .= ",stat_";
                
                break; 
              case 56: //ss_team_event_admin: stand alone, single, event data add
                if ( empty($ss_stat_registration_form_min_tm_ev_admin_team) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 echo("<script>console.log('infinite... void ');</script>");
                $data_result['message']->{$type}->form_template_init = $ss_stat_registration_form_min_tm_ev_admin_team;
                //set extra form parts
                $data_result['message']->{$type}->additional_parts .= ",stat_";
                
                break;
              case 55: //ss_player_event_admin: stand alone, single, event data add
                if ( empty($ss_stat_registration_form_min_pl_ev_admin) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 echo("<script>console.log('infinite... void ');</script>");
                $data_result['message']->{$type}->form_template_init = $ss_stat_registration_form_min_pl_ev_admin;
                //set extra form parts
                $data_result['message']->{$type}->additional_parts .= ",stat_";
                
                break; 
              case 28: //edit player stat: cps_manager: cps role data manager, stand alone
                if ( empty($stat_registration_form_min_cps_admin) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $stat_registration_form_min_cps_admin;
                //set extra form parts
                $data_result['message']->{$type}->additional_parts .= ",stat_";
                break;
              default: //club team and default: managed club team
                if ( empty($stat_registration_init_club_team) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($stat_registration_form_club_team) ) die('<div class="error"><h4>Cannot find extension file. Form</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $stat_registration_init_club_team;
                $data_result['message']->{$type}->form_template = (current_user_can('administrator') || current_user_can('front_editor')) ? $stat_registration_form_admin : $stat_registration_form_club_team;
            }
            //year option defaults, add to player_names for new and stats for old
            $curr_years = intval(date("Y"));
            $curr_years = '<option value="">YYYY*</option>' . implode('', array_map(function($a){ return '<option value="' . $a . '">' . $a . '</option>'; }, range($curr_years - 2, $curr_years - 25)));
						//these variables are located in loaded file
//             $player_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
//             include_once $player_template_file;
						$data_result['message']->{$type}->form_template_result = $stat_registration_result;
            $data_result['message']->{$type}->form_defaults = (object) array('current_birth_years' => $curr_years);
            $data_result['message']->school_names->form_template_min = $school_registration_form_min;
            $data_result['message']->school_names->form_template_result = $school_registration_result;
            $data_result['message']->club_names->form_template_min = $club_registration_form_min;
            $data_result['message']->club_names->form_template_result = $club_registration_result;
            if((current_user_can('administrator') || (current_user_can('front_editor')) && $type_key === 28)) {
              if( !isset($data_result['message']->player_names_admin) ) $data_result['message']->player_names_admin = (object) array();
              $data_result['message']->player_names_admin->form_template_min = $player_registration_form_min_full;
              $data_result['message']->player_names_admin->form_template_result = $player_registration_result;
              $data_result['message']->player_names_admin->form_defaults = (object) array('current_birth_years' => $curr_years);
            } else {
              if( $type_key === 16 || $type_key === 15 || $type_key === 13 || $type_key === 54 ) {
                if( !isset($data_result['message']->pl_ev) ) $data_result['message']->pl_ev = (object) array();
                if( !isset($data_result['message']->player_names) ) $data_result['message']->player_names = (object) array();
                $data_result['message']->pl_ev->form_template_min = $player_registration_form_min_full;
                $data_result['message']->player_names->form_template_result = $player_registration_result;
                $data_result['message']->pl_ev->form_defaults = (object) array('current_birth_years' => $curr_years);
              } else {
                if( $type_key === 48 ) {
                if( !isset($data_result['message']->dcp_pl_ev) ) $data_result['message']->dcp_pl_ev = (object) array();
                if( !isset($data_result['message']->player_names) ) $data_result['message']->player_names = (object) array();
                $data_result['message']->dcp_pl_ev->form_template_min = $dcp_player_registration_form_min_full;
                $data_result['message']->player_names->form_template_result = $player_registration_result;
                $data_result['message']->dcp_pl_ev->form_defaults = (object) array('current_birth_years' => $curr_years);
                } else {
                  if( !isset($data_result['message']->player_names) ) $data_result['message']->player_names = (object) array();
                  $data_result['message']->player_names->form_template_min = $player_registration_form_min_full; 
                  $data_result['message']->player_names->form_template_result = $player_registration_result;
                  $data_result['message']->player_names->form_defaults = (object) array('current_birth_years' => $curr_years);
                  
                }
              }
            }
						//add presets if we need to
						$presets = [];
            if( !empty($data_set[$type]['preset']) ) {
              foreach($data_set[$type]['preset'] as $dex => $preset_pairs) {
                $preset = explode('::', $preset_pairs);
                switch( $preset[0] ) {
                  case 'event_id':
                  case 'event_id_pm':
                    $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] } = g365_truncate_data(g365_get_event_data( $preset[1], true), 29, 0);
                    $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }['current_birth_years'] = $curr_years;
                    if( empty($data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }) ) die( '<div class="error"><h4>Cannot find required event data. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>' );
                    break;
                  case 'user_ac':
                    $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }[$preset[0]] = $preset[1];
                    break;
                }
              }
            }
            //set the styles flag // DD
// 						$data_result['style'] = (empty($data_set[$type]['stop_style'])) ? true : false;
            if( empty($data_set[$type]['ids']) ) break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
            //if we have ids, look up the info and send it back
            if( !empty($ids) ) { 
              ///get each id
  						foreach( $ids as $stat_dex => $stat_val ){
                if($type_key === 27 || $type_key === 28 || $type_key === 52 || $type_key === 55){
                  
                //pull the stat data
                $stat_data_pull = g365_get_stat( $stat_val );
                } else if($type_key === 56){
                  
                  $stat_data_pull = g365_get_team_stat($stat_val);
                }
                //if we have event specific templates to assemble, start
//                 echo("<script>console.log('player_id_stats:');</script>");
                if( is_object($stat_data_pull) && isset($stat_data_pull->event) && (isset($stat_data_pull->event_trends) || (($type_key === 27 || $type_key === 28 || $type_key === 52 || $type_key === 55 || $type_key === 56) && (current_user_can('administrator') || current_user_can('front_editor')) && isset($stat_data_pull->event_stats))) ) { 
                  //set the holder in the export object
                  if( !isset($data_result['message']->form) ) $data_result['message']->form = (object) array();
                  if( !isset($data_result['message']->form->{$type}) ) $data_result['message']->form->{$type} = (object) array();
                  //import the templates
                  $stat_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_stat_registration_form.php';
                  include_once $stat_template_file;
                  //if we have valid data create event template
                  if( 
                    isset($stat_registration_form_min_pl_ev_admin__input) &&
                    isset($stat_registration_form_min_pl_ev_admin__select) &&
                    isset($stat_registration_form_min_pl_ev_admin__select_option) &&
                    isset($stat_registration_form_min_pl_ev_admin__textarea) 
                  ) {
                    //process stats if we have it and it hasn't been built already
                    if( ($type_key === 27 || $type_key === 28 || $type_key === 52 || $type_key === 55 || $type_key === 56) && (current_user_can('administrator') || current_user_can('front_editor')) && isset($stat_data_pull->event_stats) && !isset($data_result['message']->form->{$type}->{ 'stat_' . $stat_data_pull->event }) ) { 
                      //set template var
                      $stat_template = '';
                      //parse the event_stats, then loop through to build the template
                      foreach( json_decode($stat_data_pull->event_stats) as $dex => $stat_attributes  ) {
                        //add set type to attributes
                        $stat_attributes->type = 'stats';
                        $stat_attributes->value = '{{stat_' . $stat_attributes->handle . '_val}}';
                        $current_element = ${'stat_registration_form_min_pl_ev_admin__' . $stat_attributes->element};
                        foreach( $stat_attributes as $attr_name => $attr_val ) $current_element =  str_replace('{{' . $attr_name . '}}', $attr_val, $current_element);
                        $stat_template .= preg_replace('/\{\{(.+?[^_val])\}\}/', '', $current_element);
  //                           {
  //                             "element" : "", //input, select, select_option, textarea
  //                             "name" : "", //plain stat name: '3/4 Court Sprint' or sentence/question 'Favorite shoe?'
  //                             "handle" : "", //lowercase, no space, stat id: '3_4_court_sprint', auto generate from name
  //                             "type" : "", //stat or trend for now
  //                             "placeholder" : "", //placeholder for input or textarea or default for select, radio, etc..
  //                             "options" : {"name" : "value"}, //if it's a select or radio, etc..just name value pairs
  //                           },
                      }
                      //attach the template to the main export variable
                      $data_result['message']->form->{$type}->{ 'stat_' . $stat_data_pull->event } = $stat_template;
                    }
                    //process trends if we have it and it hasn't been built already
                    if( isset($stat_data_pull->event_trends) && !isset($data_result['message']->form->{$type}->{ 'trend_' . $stat_data_pull->event }) ) {
                      //set template var
                      $trend_template = '';
                      //parse the event_trends, then loop through to build the template
                      foreach( json_decode($stat_data_pull->event_trends) as $dex => $trend_attributes  ) {
                        //add set type to attributes
                        $trend_attributes->type = 'trends';
                        $trend_attributes->value = '{{trend_' . $trend_attributes->handle . '_val}}';
                        $current_element = ${'stat_registration_form_min_pl_ev_admin__' . $trend_attributes->element};
                        foreach( $trend_attributes as $attr_name => $attr_val ) $current_element =  str_replace('{{' . $attr_name . '}}', $attr_val, $current_element);
                        $trend_template .= preg_replace('/\{\{(.+?[^_val])\}\}/', '', $current_element);
                      }
                      //attach the template to the main export variable
                      $data_result['message']->form->{$type}->{ 'trend_' . $stat_data_pull->event } = $trend_template;
                    }

                  } else {
                    if( isset($stat_data_pull->event_trends) ) $data_result['message']->form->{$type}->{ 'trend_' . $stat_data_pull->event } = 'Missing trend templates file.';
                    if( isset($stat_data_pull->event_stats) ) $data_result['message']->form->{$type}->{ 'stat_' . $stat_data_pull->event } = 'Missing stat templates file.';
                  }
                }
                
                $stat_val_json = json_encode($stat_data_pull);
                $access_val_json = json_encode($access_level);
//                 echo("<script>console.log('inside10 print_r(" . $stat_val_json . ")  CONTINUE " . $type_key . "  access: " . $access_val_json . " ');</script>");
                $data_result['message']->{$type}->{$stat_val} = g365_truncate_data( $stat_data_pull, $type_key, $access_level );
              }
            } else {
              //adjust for the variety of pl and ev ids
              switch( $type_key ) {
                case 6: //pl_ev: managed certification
                case 14: //pl_cert: stand-alone certification
                case 19: //add      player stat: pl_cert_sl: single input, stand alone
                case 27: //player_event_admin: stand alone, single, event data add
                case 52: //hhh_player_event_admin: stand alone, single, event data add
                case 28: //edit player stat: cps_manager: cps role data manager, stand alone
                case 13: //player_event: stand alone general stats
                case 49: // Player photo
                case 53: //team_event: stand alone general stats
                case 54: //add/edit player stat: player_event: stand alone stat scope scouting
                case 55: //ss_player_event_admin: stand alone, single, event data add
                case 56: //ss_team_event_admin: stand alone, single, event data add
                  $player_id_stats = $data_set[$type]['contributions']['pl_ev_id'];
                  $event_id_stats = $data_set[$type]['contributions']['event_id_pm'];
//                   $pp_id_stats = $data_set[$type]['contributions']['passport_date'];
                  break;
                case 16: //camps: managed camps
                case 43: //add/edit player stat: passport mananged
                  $player_id_stats = $data_set[$type]['contributions']['pl_cp_id'];
                  $event_id_stats = $data_set[$type]['contributions']['event_id_cp'];
                  break;
                case 48: //add/edit player stat: digital coaching package managed
                  $player_id_stats = $data_set[$type]['contributions']['pl_cp_id'];
                  $event_id_stats = $data_set[$type]['contributions']['event_id_cp'];
                  break;
                default: //club team and default: managed club team
                  $player_id_stats = $data_set[$type]['contributions']['pl_ct_id'];
                  $event_id_stats = $data_set[$type]['contributions']['event_id_ct'];
              }
              //run proper query for singular or group records
              if( !empty($player_id_stats) && !empty($event_id_stats) ) {                
//                 echo("<script>console.log('player_id_stats: " . $player_id_stats . " ');</script>");                  
                $stat_record = g365_get_stat( null, $player_id_stats, $event_id_stats );
                $data_result['message']->{$type}->{(is_object($stat_record)) ? $stat_record->id : $player_id_stats . '|' . $event_id_stats } = g365_truncate_data( $stat_record, $type_key, $access_level );
              } else {
                if( !empty( $player_id_stats ) ) {
//                   echo("<script>console.log('event_id_stats: " . $event_id_stats . " ');</script>");
                  if( $data_set[$type]['contributions']['stats_id_multi'] === 'true' ) { 
                    $data_result['message']->{$type} = g365_get_stats( $player_id_stats );
                  }
                } elseif( !empty( $event_id_stats ) ) {
                  if( $data_set[$type]['contributions']['stats_id_multi'] === 'true' ) {
                    $data_result['message']->{$type} = g365_get_stats( null, $event_id_stats );
                  } else {
                    $stat_record = $wpdb->get_results(
                      "SELECT evrec.id as event, evrec.name AS event_name, evrec.short_name AS event_short
                      FROM $wpdb_events AS evrec
                      WHERE evrec.id = $event_id_stats;"
                    );
                    $data_result['message']->{$type} = $stat_record;
                  }
                } else {
                  $data_result['message']->{$type} = '<p class="error">No data found for these parameters.</p>';
                }
                //truncate each record
                if( !empty($data_result['message']->{$type}) && gettype($data_result['message']->{$type}) != 'string' ) foreach( $data_result['message']->{$type} as $stat_dex => &$stat_val ) $stat_val = g365_truncate_data( $stat_val, $type_key, $access_level );
              }
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
              
//               $data_result['message']->{$type} = $data_set[$type]['form_data'];
//               echo json_encode($data_result);
//               die();

						//sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
//               echo "<script>console.log('yello" . print_r($new_data) . "');</script>"; //start passport search here
							//let's see the player exists, and possibly create a record, we need the id before we can create a stat record
              if( !empty($new_data['team']) ) { //for team eval insert
//                 echo('new id4444 ' . print_r($new_data));
                // Validate and sanitize the input data
              $team = isset($_POST['team']) ? sanitize_text_field($_POST['team']) : '';
              $event = isset($_POST['event']) ? sanitize_text_field($_POST['event']) : '';
//               
              // Ensure required data is present
              if (!empty($new_data['team']) && !empty($new_data['event'])) {
                  $team = $new_data['team'];
                  $event = $new_data['event'];
                  // Prepare the data for insertion
                  $data = [
                      'createdate' => current_time('mysql'),
                      'updatetime' => current_time('mysql'),
                      'team' => $team,
                      'event' => $event,
                  ];
                
                  // Specify the table name
                  $table_name = $wpdb->prefix . "g365_team_stats";
                
                  // Check if the record already exists before inserting
                  $existing_record = $wpdb->get_row($wpdb->prepare(
                      "SELECT * FROM $table_name WHERE team = %s AND event = %s",
                      $team, $event
                  ));
                
                  if ($existing_record) {
                      // Record exists, do nothing or update if needed
                      echo "Record already exists.";
                  } else {
                   
                    // Insert the data into the database
                    $inserted = $wpdb->insert($table_name, $data);

                    if ($inserted) {
                        // Data inserted successfully
                        echo "Data inserted successfully.";
                        
                      
                        $event_id_ss_part = g365_process_data_point( 'id', $new_data['event_ss_part']);

                        if ($event_id_ss_part === null || $event_id_ss_part === '' || $event_id_ss_part === 'null') {
  //                         echo "event participated did not push to db";
                         
                        }else{ 

                          $trends_json = json_encode(['ss_event_participated' => $event_id_ss_part]);

                          $new_data_with_trends = [
                              'createdate' => current_time('mysql'),
                              'updatetime' => current_time('mysql'),
                              'team' => $team,
                              'event' => $event,
                              'trends' => $trends_json,
                          ];

                          // Check if the record already exists
                          $existing_record = $wpdb->get_row($wpdb->prepare(
                              "SELECT * FROM $table_name WHERE team = %s AND event = %s",
                              $team, $event
                          ));

                          if ($existing_record) {
                              // Record exists, update it
                              $updated_with_trends = $wpdb->update(
                                  $table_name,
                                  $new_data_with_trends,
                                  ['team' => $team, 'event' => $event]
                              );

  //                             if ($updated_with_trends !== false) {
  //                                 echo "Data with trends updated successfully.";
  //                             } else {
  //                                 echo "Failed to update data with trends.";
  //                                 echo "Error: " . $wpdb->last_error;
  //                             }
                          } else {
                              // Record does not exist, insert a new one
                              $new_id = $wpdb->insert($table_name, $new_data_with_trends);

  //                             if ($new_id) {
  //                                 echo "Data with trends inserted successfully.";
  //                             } else {
  //                                 echo "Failed to insert data with trends.";
  //                                 echo "Error: " . $wpdb->last_error;
  //                             }
                          }

  //                         echo("<script>console.log('Inserting with trends: " . $trends_json . ' // ' . $event_id_ss_part . "');</script>");
                        }
                    } else {
                        // Failed to insert data
                        echo "Failed to insert data.";
                    }
                    
                  }
                  
              } else {
                  // Required data is missing
                  echo "Required data is missing.";
                }
              }
              
              if( empty($new_data['player']) ) {
//                 echo('new id555 ' . print_r($new_data));
                 if(!empty($new_data['event']) && !empty($new_data['id'])) { //checking to see if its for teams 
                        $table_name = $wpdb->prefix . "g365_team_stats";
//                         $strengths = isset($_POST['strengths']) ? sanitize_text_field($_POST['strengths']) : '';
//                         $weaknesses = isset($_POST['weaknesses']) ? sanitize_text_field($_POST['weaknesses']) : '';
                        $strengths = $new_data['strengths'];
                        $weaknesses = $new_data['weaknesses'];
                        $front_page = $new_data['front_page'];
                        $evaluation = $new_data['evaluation'];
                        $json_strengths = json_encode($strengths);
                        $json_weaknesses = json_encode($weaknesses);
                   
                        $new_data_with_trends = [
                            'createdate' => current_time('mysql'),
                            'updatetime' => current_time('mysql'),
                            'strengths' => $json_strengths,
                            'weaknesses' => $json_weaknesses, 
                            'evaluation' => $evaluation,
                        ];
                        // Check if the record already exists
                        $existing_record = $wpdb->get_row($wpdb->prepare(
                            "SELECT * FROM $table_name WHERE id = %s",
                            $new_data['id']
                        ));
                   
//                         if ($existing_record !== false) {
// //                                 echo "Data with trends updated successfully.";
//                             } else {
//                                 echo "Failed to update data with trends.";
//                                 echo "Error: " . $wpdb->last_error;
//                             }

                        if ($existing_record) {
//                             echo("<script>console.log(exist: ". $existing_record . ");</script>");
                            // Record exists, update it
                            $updated_with_trends = $wpdb->update(
                                $table_name,
                                $new_data_with_trends,
                                ['id' => $new_data['id'], 'event' => $new_data['event']]
                            );

//                             if ($updated_with_trends !== false) {
//                                 echo "exist Data with trends updated successfully.";
//                             } else {
//                                 echo "exist Failed to update data with trends.";
//                                 echo "Error: " . $wpdb->last_error;
//                             }
                          
                              // Record exists, get the current trends
                              $current_trends = json_decode($existing_record->trends, true);

                              // Check if trends is already an array
                              if (!is_array($current_trends)) {
                                  $current_trends = [];
                              }

                              // Add front_page to trends
                              $current_trends['front_page'] = $front_page;

                              // JSON encode the updated trends
                              $json_trends = json_encode($current_trends);

                              // Update the data with trends
                              $new_data_with_trends['trends'] = $json_trends;

                              // Update the existing record
                              $updated_with_trends = $wpdb->update(
                                  $table_name,
                                  $new_data_with_trends,
                                  ['id' => $new_data['id']]
                              );
                          
                        } else {
//                           echo("<script>console.log(no exist: ". $existing_record . ");</script>");
                            // Record does not exist, insert a new one
                            $new_id = $wpdb->insert($table_name, $new_data_with_trends);

                            
//                             if ($new_id) {
//                                 echo "no exist Data with trends inserted successfully.";
//                             } else {
//                                 echo "no exist Failed to insert data with trends.";
//                                 echo "Error: " . $wpdb->last_error;
//                             }
                        }
                        
//                         echo("<script>console.log('Inserting with trends: " . $trends_json . ' // ' . $event_id_ss_part . "');</script>");
                      
                 }else{           
                
                $first_name = g365_process_data_point( 'first_name', $new_data['data']['player']['first_name']);
                $last_name = g365_process_data_point( 'last_name', $new_data['data']['player']['last_name']);
                $email = g365_process_data_point( 'email', $new_data['data']['player']['email']);
                $new_player_data = array(
                  'first_name' => $first_name,
                  'last_name' => $last_name,
                  'email' => $email,
                  'nickname' => g365_process_data_point( 'nickname', ((!empty($new_data['data']['nickname']) && current_user_can( 'administrator' )) ? $new_data['data']['nickname'] : $first_name . '-' . $last_name))
                );
                //try to insert a new record
								$new_id = $wpdb->insert( $wpdb_players, $new_player_data );
                //is there already a record

								if( $new_id === false ) {
                  //if we already have a record pull it to see if we should take further action
//                   $new_id = $wpdb->get_row( "SELECT * FROM $wpdb_players WHERE nickname LIKE '" . $new_player_data['nickname'] . "'" );
                  $new_id = $wpdb->get_row( 'SELECT * FROM $wpdb_players WHERE nickname LIKE ' . $new_player_data['nickname'] . '' );
//                   echo('new id ' . $new_id);
                  //if we have data make some descitions
                  if( $new_id !== null ) {
                    if( current_user_can( 'administrator' ) || current_user_can( 'front_editor' ) ) {
                      //if we have a birthday do some checking
                      if( empty($new_id->birthday) || $new_id->birthday == g365_process_data_point( 'birthday', $new_data['data']['player']['birthday']) ) {
                        $new_data['player'] = $new_id->id;
                      } else {
                        if( !empty($new_data['data']['player']['state']) ) {
                          $state = g365_process_data_point( 'state', $new_data['data']['player']['state'] );
                          $new_player_data['nickname'] = g365_process_data_point( 'nickname', $first_name . '-' . $last_name . '-' . $state);
                          //try to insert a new record
                          $new_id = $wpdb->insert( $wpdb_players, $new_player_data );
                          if( $new_id === null ) {
                            $data_result['message']->{$type}->{$form_group} = array(
                              'message'       => 'Could not match birthdate, player existing in same state. Can\'t update profile data',
                              'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                            );
                            continue;
                          }
                          $new_data['player'] = $new_id->id;
                        } else {
                          $data_result['message']->{$type}->{$form_group} = array(
                            'message'       => 'Could not match birthdate, no location data provided. Can\'t update profile data.',
                            'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                          );
                          continue;
                        }
                      }
                    } else {
                      //if there isn't any data to check against, claim this profile
                      if( $new_id->access === null ) {
                        $new_data['player'] = $new_id->id;
                        $new_data['data']['player']['access'] = $access_level;
                        if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
                        $data_owner_reference['pl_ed'][] = $new_data['player'];
                      } else {
                        //parse the access array
                        $new_id->access = json_decode($new_id->access);
                        //if this user isn't on the list, exit
                        if( empty($new_id->access->{$access_level[1]}) ){
//                           //if we have a birthday do some checking
//                           if( empty($new_id->birthday) || $new_id->birthday == g365_process_data_point( 'birthday', $new_data['data']['player']['birthday']) ) {
//                             $new_data['player'] = $new_id->id;
//                           } else {
//                             //this is a player without an id, supposedly a new player, birthdays dont match up, but names do, so add state info to create a new player 
//                             if( !empty($new_data['data']['player']['state']) ) {
//                               $state = g365_process_data_point( 'state', $new_data['data']['player']['state'] );
//                               $new_player_data['nickname'] = g365_process_data_point( 'nickname', $first_name . '-' . $last_name . '-' . $state);
//                               //try to insert a new record
//                               $new_id = $wpdb->insert( $wpdb_players, $new_player_data );
//                               if( $new_id === null ) {
//                                 $data_result['message']->{$type}->{$form_group} = array(
//                                   'message'       => 'Could not match birthdate, name record exists in same state. Please load profile to edit.',
//                                   'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
//                                 );
//                                 continue;
//                               }
//                             }
//                             $new_data['player'] = $new_id->id;
//                           }
//                           $new_data['player'] = $new_id->id;
//                           $new_data['data']['player']['access'] = $access_level;
//                           if( !empty($new_data['player']) ) {
//                             if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
//                             $data_owner_reference['pl_ed'][] = $new_data['player'];
//                           }
                          
                          //same as if it wasn't claimed
                          $new_data['data']['player']['access'] = $access_level;
                          if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
                          $data_owner_reference['pl_ed'][] = $new_id->id;
                        } else {
                          if( !in_array( $access_level[2], $new_id->access->{$access_level[1]} ) ) {
                            $data_result['message']->{$type}->{$form_group} = array(
                              'message'       => 'This player profile is already claimed.',
                              'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                            );
                            continue;
                          }
                        }
                        $new_data['player'] = $new_id->id;
                      }
                    }
                  } else {
                    //if we tried to pull the preventing record and we fail
//                       echo('yo');
                    if(empty($new_data['player']))
                      if(empty($new_data['event']) && empty($new_data['id']))
                    $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => g365_output_db_error('Failed to generate new player id.'),
                      'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                    );
                    continue;
                  }
                } else {
//                 echo('new id2 ' . print_r($new_data));
                  //new data was added
  								$new_data['player'] = $wpdb->insert_id;
                  if( !current_user_can( 'administrator' ) ) {
                    if( !empty($new_data['player']) ) {
                      if( !isset($data_owner_reference['pl_ed']) ) $data_owner_reference['pl_ed'] = array(); 
                      $data_owner_reference['pl_ed'][] = $new_data['player'];
                    }
                    $new_data['data']['player']['access'] = $access_level;
                  }
                }
                 }
              } else {
                //add in access if we are not an admin and there is a command to add
//                 echo('new id8888 ' . print_r($new_data));
                if( !current_user_can( 'administrator' ) ) {
                  
                  //get current access to make compare against
                  $player_data = $wpdb->get_row( "SELECT first_name, last_name, access FROM $wpdb_players WHERE id = " . ($new_data['player']) );
                  $first_name = $player_data->first_name;
                  $last_name = $player_data->last_name;
                  $new_access = json_decode($player_data->access);
                  //if profile hasn't been claimed for this site, claim it, otherweise exit process
                  if( empty($new_access->{$access_level[1]}) ) {
                    $new_data['data']['player']['access'] = $access_level;
                  } else {
                    if( !in_array( $access_level[2], $new_access->{$access_level[1]} ) ) {
                      $data_result['message']->{$type}->{$form_group} = array(
                        'message'       => 'This player profile is already claimed for this site.',
                        'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                      );
                      continue;
                    }
                  }
                }
              }              
              
//               echo('new id200000 ' . print_r($new_data));
              //add the id into the dataset if needed
//               $new_data['data']['player']['id'] = $new_data['player'];
							//build sql string for this record
							$sql_prepare_query_player = '';
              //compiled object to send back
              
              $new_data['data_processed'] = (object) array( 'player' => (object) array(), 'event' => (object) array(), 'stats' => (object) array() );
              if( !empty($new_data['data']['player']) ) {
                
                //process each data point and add to the query string
                foreach( $new_data['data']['player'] as $data_name => &$data_value ) {
                  //santicze and process the raw data based on type
                  $data_value = g365_process_data_point( $data_name, $data_value);
                  //add the processed value to the array
                  $new_data['data_processed']->player->{$data_name} = $data_value;
                  //if the data is an image, process it accordingly
//                    echo("<script>console.log('$new_data');</script>");
                  if($data_name === 'profile_img_data' || $data_name === 'recard_img_data' || $data_name === 'bcert_img_data' ) {
                    //if we don't have a specific command, then continue
                    if( $data_value == '' ) continue;
                    //get reference for image upload directory
                    $uploads_url = wp_upload_dir();
                    //we need to make sure that the image name has been generated before we can process the image data
                    $image_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name'] ) . '_' . $new_data['data']['player']['id'] . '.jpg' );
                    //put some defaults together
                    $image_content_name = 'Default';
                    $image_content = 'default';
                    //the settings for image processing based on type
                    switch( $data_name ) {
                      case 'profile_img_data':
                        $image_content_name = "Profile Image";
                        $image_content = 'profile_img';
                        $image_url = $uploads_url['basedir'] . '/player-profiles/';
                        $image_current = "profile_img";
                        break;
                      case 'recard_img_data':
                        $image_content_name = "Report Card Image";
                        $image_content = 'recard_img';
                        $image_url = $uploads_url['basedir'] . '/player-reportcards/';
                        $image_current = "json_extract(notes, '$.$image_content')";
                        break;
                      case 'bcert_img_data':
                        $image_content_name = "Birth Certificate Image";
                        $image_content = 'bcert_img';
                        $image_url = $uploads_url['basedir'] . '/player-birthcerts/';
                        $image_current = "json_extract(notes, '$.$image_content')";
                        break;
                    }
                    //see if we have a current image entry
                    $current_image = $wpdb->get_var( 'SELECT ' . $image_current . ' FROM ' . $wpdb_players . ' WHERE  id = ' . $new_data['data']['player']['id'] );
                    $current_image_url = $image_url . $current_image;
                    $image_url = $image_url . $image_name;
                    //if we don't have a value remove any leftovers
                    if($data_value === 'null') {
                      if( file_exists( $current_image_url ) ) unlink($current_image_url);
                      $image_name = null;
                    } else {
                      //write the image to the server
                      $file_size = file_put_contents( $image_url, base64_decode( $data_value ) );
                      //if the write fails, make a note and continue 
                      if( $file_size === false ) {
                        $new_data['data_processed']->player->{$data_name . '_error'} = $image_content_name . ' write error.';
                        continue;
                      }
                      //if we write the image successfully, add the reference
                      if( !empty($current_image) && $current_image !== $image_name && file_exists($current_image_url) ) unlink($current_image_url);
                      $new_data['data_processed']->player->{$image_content} = $image_name;
                    }
                    

                    //add the url to the db string, dependant on type of image
                    switch( $data_name ) {
                      case 'profile_img_data':
                        $sql_prepare_query_player .= ", $image_content = " . (($image_name === null) ? 'NULL' : "'$image_name'");
                        break;
                      case 'recard_img_data':
                      case 'bcert_img_data':
                        if( $data_value === 'null' ) {
                          $sql_prepare_query_player .= ", notes = JSON_REMOVE(notes, '$." . strtolower($image_content) . "')";
                        } else {
                          $sql_prepare_query_player .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($image_content) . "', '$image_name')";
                        }
                        break;
//                         echo("<script>console.log('$new_data');</script>");
                    }
                    continue;
                  }
                  //if we have birthcertificate pdf, process it
                  
                  
                  //if we have a social handle, process it
                  if( $data_name == 'instagram' || $data_name == 'twitter' || $data_name == 'facebook' || $data_name == 'snapchat' ) {
                    if( $data_value === 'null' ) {
                      $sql_prepare_query_player .= ", social = JSON_REMOVE(social, '$." . ucfirst($data_name) . "')";
                    } else {
                      $sql_prepare_query_player .= ", social = JSON_SET(COALESCE(social, '{}'), '$." . ucfirst($data_name) . "', '$data_value')";
                    }
                    continue;
                  }
                  //jersey sizes are kept in the notes section
                  if( $data_name == 'jersey_size' ) {
                    if( $data_value === 'null' ) {
                      $sql_prepare_query_player .= ", notes = JSON_REMOVE(notes, '$." . strtolower($data_name) . "')";
                    } else {
                      $sql_prepare_query_player .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$." . strtolower($data_name) . "', '$data_value')";
                    }
                    continue;
                  }
                  //if we have access changes, make them
                  if( $data_name == 'access' ) {
                    if( $new_data['data']['player']['revoke_access'] === 'true' ) {
                      $sql_prepare_query_player .= ", access = JSON_REMOVE(access, '$[" . $data_value[1] . "]" . (($data_value[2] === 'null') ? "" : "[" . intval($data_value[2]) . "]") . "')";
                    } else {
                      if( !empty($data_value[2]) ) $sql_prepare_query_player .= ", access = JSON_SET(COALESCE(access, '{}'), '$." . $data_value[1] . "', JSON_ARRAY_APPEND(COALESCE(JSON_EXTRACT(access, '$." . $data_value[1] . "'), '[]'), '$', " . intval($data_value[2]) . "))";
                    }
                    continue;
                  }
                  //for all other data points, process them normally and add to the sql string
                  $data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
                  $sql_prepare_query_player .= ", $data_name = $data_value";
                  //if we need to make a verification change based on what was uploaded
                  if( empty($new_data['data_processed']->player->verified) && (!empty($new_data['data_processed']->player->bcert_img) || !empty($new_data['data_processed']->player->rcard_img)) ) $sql_prepare_query_player .= ", verified = 1";
                }
                //run the player query
                $player_result = ( $wpdb->query( "UPDATE $wpdb_players SET " . substr($sql_prepare_query_player, 2) . " WHERE id = " . $new_data['player'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
                if( $player_result !== "Data updated successfully." ) {
                  //if we tried to update the player data and failed
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => g365_output_db_error('Failed to update player info.'),
                    'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                  );
                  continue;
                }
                //if we actually made a new player, send the welcome message to the owning user.
                if( !current_user_can( 'administrator' ) && !empty($access_level[3]) && !empty($new_data['data']['player']['access']) ) {
                  //get the target url for the editing link
                  $keys_by_domain = array_filter( g365_return_keys('keys_by_domain'), function($data) use ($access_level){ return $data[ 'id' ] === $access_level[1]; });
                  $keys_by_domain_key = array_keys($keys_by_domain)[0];

                  $html = '<div style="background-color:#f7f7f7;margin:0;padding:70px;"><div style="background:#1a315b;color:#ffffff;border-bottom:0;font-weight:bold;vertical-align:middle;font-family: Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0;padding:24px;display:block"><h2>';
                  $html .= 'Thank you for creating a new G365 Player Profile';
                  $html .= '</h2></div><p style="background:#ffffff;padding:40px 20px;margin:0;">';
                  $html .= 'This profile is accessible/editable from your account.<br><a href="' . $keys_by_domain_key . '/account/player_editor/">You can login here to see your data.</a>';
                  $html .= '</p></div>';
                  send_html_email( $access_level[3], 'New Player Profile Created', $html );
                }
              }
              //start adding event data if we have it
              //make sure that event id is taken care of
              $new_data['event'] = ( isset($new_data['event']) ) ? $new_data['event'] : null;
              
              //add the actual stat record
              if( empty($new_data['id']) ) {
                
                $player_id = g365_process_data_point( 'id', $new_data['player']);
                $event_id = g365_process_data_point( 'id', $new_data['event']);
                $event_id_ss = g365_process_data_point( 'id', $new_data['event_ss']);
                $paid_date = g365_process_data_point( 'id', $new_data['stat']);
                $paid_date = wp_date('Y-m-d H:i:s', strtotime($paid_date));
                $pp_action_type = g365_process_data_point( 'stat', $new_data['pp_action']);
                $scope_add_evals = g365_process_data_point( 'scope_access', $new_data['ssr_action']);
                
//                 $pp_year = $wpdb->get_results('SELECT eventtime FROM '.$wpdb_events.' WHERE id = '.$event_id.'');
//                 $year = wp_date('Y', strtotime($pp_year[0]->eventtime));
                $year = g365_process_data_point( 'id', $new_data['pp_year']);
                $check_pp_date = $wpdb->get_results(" SELECT id, stats FROM $wpdb_stats WHERE player = $player_id AND event = $event_id ");
                $check_pp_data = $wpdb->get_results(" SELECT JSON_LENGTH(JSON_EXTRACT(stats, '$.seasons')) num_unlock_pp_year, JSON_EXTRACT(stats, '$.seasons.\"$year\"') target_year FROM $wpdb_stats WHERE player = $player_id AND event = $event_id ");
                // Check if this is passport unlock/lock only
                if($event_id === 504){
                  // No record, add new record in
                  if(empty($check_pp_date[0]->id)){
                    $new_id = $wpdb->insert( $wpdb_stats, array(
                      'player' => g365_process_data_point( 'id', $new_data['player']),
                      'event' => g365_process_data_point( 'id', $new_data['event']),
                      'stats' => '{"seasons": {"'.$year.'": {"paid": "'.g365_process_data_point( 'stat', $new_data['stat']).'"}}}',
                    ) );
                  }else{
//                     $jsonDatatest = json_encode($new_data);
//                     //here is where I check if the user already has a previous record. if it does then $check_pp_date[0]->id should have the previouos stat id.
//                     $stat_data = json_decode($check_pp_date[0]->stats, true);
                    
                    if($pp_action_type === ''){
                       $new_id = $wpdb->insert( $wpdb_stats, array(
                          'player' => g365_process_data_point( 'id', $new_data['player']),
                          'event' => g365_process_data_point( 'id', $new_data['event']),
                          'stats' => '{"seasons": {"'.$year.'": {"paid": "'.g365_process_data_point( 'stat', $new_data['stat']).'"}}}',
                        ) );
                      
                    }
                    
                    // Check what type of request(Unlock, Lock)
                    if($pp_action_type === 'unlock_pp'){
                      // Existing json record, update it
                      if($check_pp_data[0]->num_unlock_pp_year > 0){
                        $new_id = $wpdb->query(" UPDATE $wpdb_stats SET stats = JSON_MERGE_PATCH(stats, JSON_OBJECT('seasons', JSON_OBJECT('$year', JSON_OBJECT('paid', '$paid_date')))) WHERE player = $player_id AND event = $event_id ");
                      }else{
                        // No json record, create it
                        $new_id = $wpdb->query(" UPDATE $wpdb_stats SET stats = JSON_OBJECT('seasons, JSON_OBJECT('$year', JSON_OBJECT('paid', '$paid_date'))) WHERE player = $player_id AND event = $event_id ");
                      }
                    }
                    if($pp_action_type === 'lock_pp'){
                      // If multiple records existed, remove the target one
                      if($check_pp_data[0]->num_unlock_pp_year > 1){
                        $new_id = $wpdb->query(" UPDATE $wpdb_stats SET stats = JSON_REMOVE(stats, '$.seasons.\"$year\"') WHERE player = $player_id AND event = $event_id ");
                      }else{
                        // If 1 or less record existed, delete the record row completely
                        if( !empty($check_pp_data[0]->target_year) && ($check_pp_data[0]->target_year != NULL) ){
                          $new_id = $wpdb->query(" DELETE FROM $wpdb_stats WHERE player = $player_id AND event = $event_id ");
                        }else{ // Request year to be deleted does not exist
                          $data_result['message']->{$type}->{$form_group} = array(
                          'message'       => g365_output_db_error('Failed to lock player. No record found under selected year.')
                          );
                          continue;
                        }
                      }
                    }
                  }
                }else{

                    if ($event_id_ss === null || $event_id_ss === '' || $event_id_ss === 'null') {
//                     echo('new id007 ' . print_r($new_data));
                        $new_id = $wpdb->insert(
                            $wpdb_stats,
                            array(
                                'player' => g365_process_data_point('id', $new_data['player']),
                                'event' => g365_process_data_point('id', $new_data['event'])
                            )
                        );
//                         echo("<script>console.log('Inserting without trends');</script>");
                        
                    } else {
                        
//                       echo('new id008 ' . print_r($new_data));
                          $trends_json = json_encode(array('ss_event_participated' => $event_id_ss));

                          $player_id = g365_process_data_point('id', $new_data['player']);
                          $event_id = g365_process_data_point('id', $new_data['event']);

                          // Check if the record exists
                          $existing_record = $wpdb->get_row(
                              $wpdb->prepare(
                                  "SELECT * FROM $wpdb_stats WHERE player = %s AND event = %s AND game = 0",
                                  $player_id,
                                  $event_id
                              )
                          );

                          if ($existing_record) {
                              // Record exists, update the trends column
                              $existing_trends = json_decode($existing_record->trends, true);

                              if (is_null($existing_trends) || empty($existing_trends)) {
                                  // Trends column is empty or invalid, initialize it with the new trends_json
                                  $updated_trends_json = $trends_json;
                              } else {
                                  // Add the new trend to the existing trends array
                                  $existing_trends = array_merge($existing_trends, json_decode($trends_json, true));
                                  // Encode the updated trends array back to JSON
                                  $updated_trends_json = json_encode($existing_trends);
                              }

                              // Update the record
                              $game = 0;
                              $wpdb->update(
                                  $wpdb_stats,
                                  array('trends' => $updated_trends_json),
                                  array('player' => $player_id, 'event' => $event_id, 'game' => $game)
                              );
                          } else {
                              // Record does not exist, insert a new one
                              $wpdb->insert(
                                  $wpdb_stats,
                                  array(
                                      'player' => $player_id,
                                      'event' => $event_id,
                                      'trends' => $trends_json
                                  )
                              );
                          }


//                         echo("<script>console.log('Inserting with trends: " . $trends_json . "');</script>");                      
                    }

                  
                }
								if( $new_id === false ) {
                  $new_id = $wpdb->get_row( 'SELECT * FROM ' . $wpdb_stats . ' WHERE player = ' . $new_data['player'] . ' AND event = ' . $new_data['event'] . '' );
                  if( $new_id === null ) {
                    $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => g365_output_db_error('Failed to generate new stat id.'),
                      'element_title' => $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name']
                    );
                    continue;
                  }
  								$new_data['id'] = $new_id->id;
                } else {
  								$new_data['id'] = $wpdb->insert_id;
                }
                $new_data['wrapper_id'] = $form_group;
							}
              
              //set variables incase we need to add them into the result set later
              $pp_data = array();

              //add id to the data set
              $new_data['stat'] = array();
              $new_data['stat']['id'] = $new_data['id'];
              $new_data['stat']['player'] = $new_data['player'];
              $new_data['stat']['event'] = $new_data['event'];
//               echo("<script>console.log('finalcheck15 " . $new_data['stat'] . "  " . $new_data['stat']['id'] . "  " . $new_data['stat']['player'] . "  " . $new_data['stat']['event'] . "  " . $check_pp_date[0]->id . " ');</script>");
              if( !empty($new_data['enabled']) || $new_data['enabled'] === "0" ) $new_data['stat']['enabled'] = $new_data['enabled'];
              if( !empty($new_data['profile_img_data']) || $new_data['profile_img_data'] === '') $new_data['stat']['profile_img_data'] = $new_data['profile_img_data'];
              switch( $type_key ) {
                case 43: //add/edit player stat: passport mananged
                  $today = date("Y-m-d H:i:s");
//                   //if we want to manually set the value, do it
                  $unlock_value = 'set';
//                   $unlock_value = $today; // Set unlock value to today date for manually input of group purchase 
                  if( !empty($new_data['unlock_value']) ) $unlock_value = $new_data['unlock_value'];
                  //are we processing a season
                  if( !empty($new_data['unlock_season']) ) {
                    $season = array();
                    //assume the current season
                    $season_label = (date('Y', strtotime($today)) - (( intval(date('n', strtotime($today))) < 9 ) ? 1 : 0 ));
                    if( is_numeric($new_data['unlock_season']) ) $season_label = intval($new_data['unlock_season']);
                    //add the target to use after the checkout is processed
                    $pp_data['passport'] = 'seasons::' . $season_label;
                    //put the year back into the return array for checkout
                    $new_data['stat']['pp_data'] = array( 'seasons' => array($season_label, $unlock_value) );
                  }
                  //are we processing an event unlock
                  if( !empty($new_data['unlock_event']) ) {
                    //add the target to use after the checkout is processed
                    $pp_data['passport'] = 'events::' . $new_data['unlock_event'];
                    $new_data['stat']['pp_data'] = array( 'events' => array( $new_data['unlock_event'], $unlock_value ));
                  }
                  /*Handle Monthly Process*/
                  if(!empty($new_data['unlock_monthly'])){
                    $monthly_label = wp_date('m');
                    $year_label = wp_date('Y');
                    //add the target to use after the checkout is processed
                    $pp_data['passport'] = 'monthly::' . $year_label . ':' . $monthly_label;
                    //put the month back into the return array for checkout
                    $new_data['stat']['pp_monthly_data'] = array('monthly' => array($monthly_label, $unlock_value));
                  }
                  /*End Monthly PP*/
                  break;
                default:
                  $hhhscope_check = g365_get_stat($new_data['id']); //grab event of event column connected to stat $hhhscope_check->event //cronos working here
                  $stat_trends = json_decode($hhhscope_check->trends); //checking to see if trends has anything in it
                  $current_event_org = g365_get_event_data($hhhscope_check->event); //looking for the org of the event connected
                  $event_connection_org = g365_get_event_data($stat_trends->ss_event_participated); //checking to see if ss_event_p is available
                  // Check if 'front_page' key exists in the new_data array
                  $front_page_exists = array_key_exists('front_page', $new_data);
//                   echo("<script>console.log('testesting " . " " . print_r($front_page_exists) ."  <- ');</script>");
                  if($front_page_exists && (($current_event_org->org == 7164 && $event_connection_org->org == 8437) || ($current_event_org->org == 2 && $event_connection_org->org == 8437) || ($current_event_org->org == 3 && $event_connection_org->org == 8437) || ($current_event_org->org == 7165 && $event_connection_org->org == 8437) || ($current_event_org->org == 3191 && $event_connection_org->org == 8437) )){ //in order to send eval into trends as hhh then make sure this goes into the else
                    //is an hhh scope eval
//                     echo("<script>console.log('testest " . " " . /*print_r($hhhscope_check) .*/ " -> " . print_r($event_connection_org->org) . " " . print_r($current_event_org->org) ." <- ');</script>");
                    
//                     if( !empty($new_data['evaluation']) || $new_data['evaluation'] === '' ) $new_data['stat']['evaluation'] = $new_data['evaluation'];
                    if( !empty($new_data['strengths']) || $new_data['strengths'] === '' ) $new_data['stat']['strengths'] = (($new_data['strengths'] === '') ? 'null' : json_encode(explode(',', $new_data['strengths'])));
                    if( !empty($new_data['weaknesses']) || $new_data['weaknesses'] === ''  ) $new_data['stat']['weaknesses'] = (($new_data['weaknesses'] === '') ? 'null' : json_encode(explode(',', $new_data['weaknesses'])));
                    if( !empty($new_data['stats']) ) $new_data['stat']['stats'] = $new_data['stats'];
                    if( !empty($new_data['trends']) ) $new_data['stat']['trends'] = $new_data['trends'];
                    if( !empty($new_data['level_division']) || $new_data['level_division'] === '' || !empty($new_data['offers']) || $new_data['offers'] === '' || !empty($new_data['player_to_watch']) || $new_data['player_to_watch'] === '' || !empty($new_data['front_page']) || $new_data['front_page'] === '' || !empty($new_data['hhh_front_page']) || $new_data['hhh_front_page'] === ''){

                      $json_array = array(
                          'level_division' => $new_data['level_division'],
                          'offers' => $new_data['offers'],
                          'player_to_watch' => $new_data['player_to_watch'],
                          'front_page' => $new_data['front_page'],
                          'hhh_front_page' => $new_data['hhh_front_page'],
                          'ss_evaluation' => $new_data['evaluation']
                      );
  //                   echo("<script>console.log('level_division: " . $new_data['level_division'] . " " . $new_data['offers'] . " " . $new_data['player_to_watch'] . " " . $json_array . " ');</script>");
  //                     $new_data['stat']['trends'] = json_encode($json_array);
                          $new_data['stat']['trends'] = $json_array;
                    }
                    
                  }else{
                    //handle like all other eval
//                     echo("<script>console.log('testesting11 " . " " . /*print_r($hhhscope_check) .*/ " -> " . print_r($event_connection_org->org) . " " . print_r($current_event_org->org) ." <- ');</script>");
                    
                    if( !empty($new_data['evaluation']) || $new_data['evaluation'] === '' ) $new_data['stat']['evaluation'] = $new_data['evaluation'];
                    if( !empty($new_data['strengths']) || $new_data['strengths'] === '' ) $new_data['stat']['strengths'] = (($new_data['strengths'] === '') ? 'null' : json_encode(explode(',', $new_data['strengths'])));
                    if( !empty($new_data['weaknesses']) || $new_data['weaknesses'] === ''  ) $new_data['stat']['weaknesses'] = (($new_data['weaknesses'] === '') ? 'null' : json_encode(explode(',', $new_data['weaknesses'])));
                    if( !empty($new_data['stats']) ) $new_data['stat']['stats'] = $new_data['stats'];
                    if( !empty($new_data['trends']) ) $new_data['stat']['trends'] = $new_data['trends'];
                    if( !empty($new_data['level_division']) || $new_data['level_division'] === '' || !empty($new_data['offers']) || $new_data['offers'] === '' || !empty($new_data['player_to_watch']) || $new_data['player_to_watch'] === '' || !empty($new_data['front_page']) || $new_data['front_page'] === '' || !empty($new_data['hhh_front_page']) || $new_data['hhh_front_page'] === ''){

                      $json_array = array(
                          'level_division' => $new_data['level_division'],
                          'offers' => $new_data['offers'],
                          'player_to_watch' => $new_data['player_to_watch'],
                          'front_page' => $new_data['front_page'],
                          'hhh_front_page' => $new_data['hhh_front_page']
                      );
  //                   echo("<script>console.log('level_division: " . $new_data['level_division'] . " " . $new_data['offers'] . " " . $new_data['player_to_watch'] . " " . $json_array . " ');</script>");
  //                     $new_data['stat']['trends'] = json_encode($json_array);
                          $new_data['stat']['trends'] = $json_array;
                    } 
                    
                  }
                  
                  if( !empty($new_data['video']) || $new_data['video'] === '' ) $new_data['stat']['video'] = $new_data['video'];
                  break;
              }

							//build sql string for this record
							$sql_prepare_query = '';
							//process each data point and add to the query string
							foreach( $new_data['stat'] as $data_name => &$data_value ) {
                //santicze and process the raw data based on type
								$data_value = g365_process_data_point( $data_name, $data_value);
//                 echo("<script>console.log('data_name9000: " . $data_name . " data_value: " . $data_value . " ');</script>");
//                 add the processed value to the array
//                 $new_data['data_processed']->stats->{$data_name} = $data_value;
                switch( $data_name ) {
                  case 'stats':
                  case 'trends':
                    if( is_array($data_value) ) {
                      foreach($data_value as $stat_data_name => $stat_data_val){
                        if( $stat_data_val === '' || $stat_data_val === 'null' ) {
                          $sql_prepare_query .= ", $data_name = JSON_REMOVE($data_name, '$.$stat_data_name')";
                        } else {
                          $stat_data_val = esc_sql($stat_data_val);
                          $sql_prepare_query .= ", $data_name = JSON_SET(COALESCE($data_name, '{}'), '$.$stat_data_name', '$stat_data_val')";
                        }
                      }
                    }
                    break;
                  case 'pp_data':
                    $today = wp_date("Y-m-d H:i:s");
                    if( is_array($data_value) ) {
                      foreach($data_value as $stat_data_name => $stat_data_val){
                        if( $stat_data_val === '' || $stat_data_val === 'null' ) {
                          $sql_prepare_query .= ", stats = JSON_REMOVE($data_name, '$.$stat_data_name')";
                        } else {
//                           $sql_prepare_query .= ", stats = JSON_SET(COALESCE(stats, JSON_OBJECT()), '$.$stat_data_name', JSON_MERGE_PATCH(COALESCE(JSON_EXTRACT(stats,'$.$stat_data_name'), JSON_OBJECT()), '{\"" . $stat_data_val[0] . '": "' . $stat_data_val[1] . '"}\'))';
                          $sql_prepare_query .= ", stats = JSON_SET(COALESCE(stats, JSON_OBJECT()), '$.$stat_data_name', JSON_MERGE_PATCH(COALESCE(JSON_EXTRACT(stats,'$.$stat_data_name'), JSON_OBJECT()), '{\"" . $stat_data_val[0] . '": {\"paid\": "'.$today.'"}}\'))';
                        }
                      }
                    }
                    break;
                  case 'pp_monthly_data':
                    $today = wp_date("Y-m-d H:i:s");
                    $today_year = wp_date("Y");
                    if( is_array($data_value) ) {
                      foreach($data_value as $stat_data_name => $stat_data_val){
                        if( $stat_data_val === '' || $stat_data_val === 'null' ) {
                          $sql_prepare_query .= ", stats = JSON_REMOVE($data_name, '$.$stat_data_name')";
                        } else {
                          $sql_prepare_query .= ", stats = JSON_SET(
                            COALESCE(stats, JSON_OBJECT()), 
                            '$.$stat_data_name', 
                            JSON_MERGE_PATCH(
                              COALESCE(JSON_EXTRACT(stats,'$.$stat_data_name'), JSON_OBJECT()), 
                              JSON_OBJECT(
                                '$today_year', JSON_OBJECT(
                                  '" . $stat_data_val[0] . "', JSON_OBJECT(
                                    'paid', '$today'
                                  )
                                )
                              )
                            )
                          )";
                        }
                      }
                    }
                    break;
                  case 'profile_img_data':
                    //if we don't have a specific command, then continue
                    if( $data_value == '' ) break;
                    //get reference for image upload directory
                    $uploads_url = wp_upload_dir();
                    //generated image name
                    $image_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['player']['first_name'] . ' ' . $new_data['data']['player']['last_name'] ) . '_' . $new_data['data']['player']['id'] . '.jpg' );
                    //target directory
                    $image_url_middle = '/event-profiles/' . g365_process_data_point( 'folder', $new_data['event_name'] ) . '/';
                    $image_url = $uploads_url['basedir'] . $image_url_middle;
                    //create event folder if it doesn't exist
                    if (!is_dir($image_url)) mkdir($image_url);
                    //see if we have a current image entry
                    $current_image_url = $wpdb->get_var( "SELECT profile_img FROM $wpdb_stats WHERE  id = " . $new_data['id'] );
//                     if( !empty($current_image_url) ) {
//                       $current_image_url = $uploads_url['basedir'] . substr($current_image_url, strpos($current_image_url, "uploads") + 7);
//                     }
                    $image_url = $image_url . $image_name;
                    $image_public_url = site_url() . '/wp-content/uploads' . $image_url_middle . $image_name;
///srv/users/ogpdevworker/apps/g365-dev/public/wp-content/uploads/event-profiles/college-placement-service-2019/
//https://dev.grassroots365.com/wp-content/uploads/event-profiles/college-placement-service-2019/james-darnel_5881.jpg
                    
// $data_result['message']->image_data = $current_image_url;
                    //if we don't have a value remove any leftovers
                    if($data_value === 'null') {
                      if( file_exists( $current_image_url ) ) unlink($current_image_url);
                      $image_public_url = null;
                    } else {
                      //write the image to the server
                      $file_size = file_put_contents( $image_url, base64_decode( $data_value ) );
                      //if the write fails, make a note and continue 
                      if( $file_size === false ) {
                        $new_data['data_processed']->stats->profile_img_error = 'Event Profile Image write error.';
                        break;
                      }
                      //if we have an old image that is getting replaced and somehow the name changes delete the old named image
                      if( !empty($current_image_url) && $current_image_url !== $image_url && file_exists($current_image_url) ) unlink($current_image_url);
                      //add the reference
                      $new_data['data_processed']->stats->profile_img = $image_public_url;
                    }
                    $sql_prepare_query .= ', profile_img = ' . (($image_public_url === null) ? 'NULL' : "'$image_public_url'");
                    break;
                  default:
//                     echo("<script>console.log('toji " . $data_value . " ');</script>");
                    //if the data is numeric or special don't doublew quote it
                    $data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
                    $sql_prepare_query .= ", $data_name = $data_value";
//                     echo("<script>console.log('zeus " . $data_value  . " ');</script>");
//                     echo("<script>console.log('data_name: " . $data_value . " data_value: " . $sql_prepare_query . " ');</script>");
                }
              }
              //if we need to truncate the data we send back
              
              $data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed']->stats, $type_key, $access_level );
              
              //add in the $pp_data if we have it. For passport checkout support
              if( !empty($pp_data) )  $data_result['message']->{$type}->{$new_data['id']} = array_merge($data_result['message']->{$type}->{$new_data['id']}, $pp_data);
              //recursively add the id
              $data_result['message']->{$type}->{$new_data['id']}['id'] = $new_data['id'];
              //add player update status to return var
              $data_result['message']->{$type}->{$new_data['id']}['player_message'] = $player_result;
              //create the element name to result display
                            

//         $data_result['message']->{$type} = "UPDATE $wpdb_stats SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";";
//         echo json_encode($data_result);
//         die();
              $ev_id = $new_data['stat']['event'];
//                echo("<script>console.log(here: ". print_r($new_data) . ");</script>");
              if(!empty($new_data['player'])){
              $data_result['message']->{$type}->{$new_data['id']}['element_title'] = ((empty($first_name)) ? ((empty($new_data['player'])) ? '' : $wpdb->get_var( "SELECT name FROM $wpdb_players WHERE id = " . ($new_data['player']) )) : $first_name . ' ' . $last_name) . (( empty($new_data['event']) ) ? '' : (' + ' . $wpdb->get_var( "SELECT name FROM $wpdb_events WHERE id = " . $new_data['event']) ));
              //run the stat update/create query
//               $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_stats SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Player registered successfully.";
              // Update successful message
                
//               g365_check_form_post(['ev_id'=>$new_data['stat']['event']], $type);
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_stats SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Player profile is successfully added. Please proceed to complete the checkout process below.";
              }else if(!empty($new_data['event']) && !empty($new_data['id'])){
//                 echo("<script>console.log(here2: ". print_r($new_data) . ");</script>");
                $table_team_stats = $wpdb->prefix . "g365_team_stats";
                $data_result['message']->{$type}->{$new_data['id']}['element_title'] = ((empty($first_name)) ? ((empty($new_data['event'])) ? '' : $wpdb->get_var( "SELECT team FROM $table_team_stats WHERE id = " . ($new_data['id']) )) : $first_name . ' ' . $last_name) . (( empty($new_data['event']) ) ? '' : (' + ' . $wpdb->get_var( "SELECT name FROM $wpdb_events WHERE id = " . $new_data['event']) ));
                $data_result['message']->{$type}->{$new_data['id']}['message'] = "Team profile is successfully added. Please proceed to complete the checkout process below.";
              }
//               g365_check_form_post(['ev_id'=>$new_data['stat']['event']], $type);
						}
            //if we are some data types add the redriect at the end
            switch( $type_key ) {
              case 27:
              case 55:
              case 56:
                $data_result['message']->{$type}->{$new_data['id']}['redirect'] = "reload";
            }
						break;
					case 'claim_data':
//             $data_set[pl_ev] = {
//               ids: "6123",
//               proc_type: "claim_data"
//             }

            //get type key number from requesting site            
            $g365_claim_type_key = g365_return_keys('g365_claim_type_key');
            if( in_array($type, array_keys($g365_claim_type_key)) ) {
              $g365_claim_type_key = $g365_claim_type_key[$type];
            } else {
              $data_result['message']->{$type} = array(
                'message'       => 'No data type match. Please contact your representative.',
                'element_title' => "Current Claim"
              );
              break;
            }
            //set database table for type
            $tbl_target = '';
            switch( $g365_claim_type_key ) {
              case 1:
                $tbl_target = $wpdb_players;
                break;
              case 2:
                $tbl_target = $wpdb_org;
                break;
              case 3:
                $tbl_target = $wpdb_coaches;
                break;
            }
            //get data owner reference
            $data_owner = $wpdb->get_row( 'SELECT name, access FROM ' . $tbl_target . ' WHERE  id = ' . $data_set[$type]['ids'] );
            if( $data_owner === false ) {
              $data_result['message']->{$type} = array(
                'message'       => 'No data for supplied id. Please contact your representative.',
                'element_title' => "Current Claim"
              );
              break;
            }
            $data_name = $data_owner->name;
            $data_owner = json_decode($data_owner->access);
            //just grab the first user id, presumably they are the master owner
            $data_owner = $data_owner->{$access_level[1]}[0];
            //claim data array
            $owner_info = $data_set[$type]['user_info'];
            $owner_info_array = array(
              'name' => $owner_info[0],
              'phone' => $owner_info[1],
              'relation' => $owner_info[2],
              'birthday' => $owner_info[3]
            );
            $owner_info = json_encode($owner_info_array);
            
            $claim_data = array( 'type' => $g365_claim_type_key, 'target' => intval($data_set[$type]['ids']), 'site_key' => $access_level[1], 'email' => $access_level[3], 'status' => 1, 'owner_id' => $access_level[2], 'owner_data' => $owner_info );
            //add the record to claim table
            $new_claim = $wpdb->insert( $wpdb_claims, $claim_data );
            $new_claim_status = 'Your request to access this account has been submitted test2. Please contact customer service if you require further assistance, <a href="/contact">here</a>.';
            //is there already a record
            if( $new_claim === false ) {
              $ref_id = $wpdb->get_var( "SELECT id FROM $wpdb_claims where type = " . $claim_data['type'] . " AND target = " . $claim_data['target'] . " AND site_key LIKE '" . $claim_data['site_key'] . "'" . " AND email LIKE '" . $claim_data['email'] . "'" );
              $new_claim_status = 'Your request to access this account has been re-submitted. Please contact customer service if you require further assistance, <a href="/contact">here</a>.';
            } else {
              $ref_id = $wpdb->insert_id;
            }
            //send notice to owner site
            $owner_notice_result = g365_data_sender( $access_level, 'notify', array($data_owner, $data_name, $access_level[3], $ref_id) );
            //check the outcomes and create a status update to send back.
            if( is_array($owner_notice_result) ) {
              $owner_notice_result['claim_status'] = $new_claim_status;
              $data_result['message']->{$type} = $owner_notice_result;
            } else {
              $data_result['message']->{$type} = $new_claim_status . ' ' . $owner_notice_result;
            }
						break;
				}
				break;
			case 2: //add/edit event
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
						break;
					case 'get_data':
						break;
					case 'proc_data':
						break;
				}
				break;
			case 3: //add/edit organization
			case 10: //schools
			case 11: //club_names
      case 25: //og_ed
      case 30: //club_names_admin
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
						if(
              !empty($data_result['message']->{$type}->form_template) &&
              !empty($data_result['message']->{$type}->form_template_init) &&
              !empty($data_result['message']->{$type}->form_template_result)
            ) break;
						$club_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_club_registration_form.php';
						include_once $club_template_file;
						if ( empty($club_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_form) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Result.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            
            if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
            
            switch( $type_key ) {
              case 25: //og_ed: stand alone, club edit
                if ( empty($club_registration_init_sl) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($club_registration_form_sl) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $club_registration_init_sl;
                $data_result['message']->{$type}->form_template = $club_registration_form_sl;
                break;
              default:
                $data_result['message']->{$type}->form_template_init = ( current_user_can( 'administrator' ) ) ? $club_registration_init_admin : $club_registration_init;
                $data_result['message']->{$type}->form_template = ( current_user_can( 'administrator' ) ) ? $club_registration_form_admin : $club_registration_form;
            }
						$data_result['message']->{$type}->form_template_result = $club_registration_result;
						//add presets if we need to
						$presets = [];
						if( !empty($data_set[$type]['preset']) ) {
							foreach($data_set[$type]['preset'] as $dex => $preset_pairs) {
								$preset = explode('::', $preset_pairs);
								switch( $preset[0] ) {
                  case 'user_ac':
                    $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }[$preset[0]] = $preset[1];
                    break;
								}
							}
						}
						//set the styles flag
// 						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
						break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
						if( empty($ids) ) die('<div class="error"><h4>Cannot parse organization ids.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//setup var for type
						//pull user data
						foreach( $ids as $org_dex => $org_val ) $data_result['message']->{$type}->{$org_val} = g365_truncate_data( g365_get_org_profile( $org_val ), $type_key, $access_level );
						//add parameters that are needed for a form
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//process and check incoming data
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            //sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
							//let's assign an id if we don't have one. Presumabily it's a new org
							if( empty($new_data['id']) ) {
  							if( !empty($new_data['data']['name']) && !empty($new_data['data']['state']) ) {
                  $new_id = $wpdb->insert( $wpdb_orgs, array(
                    'name'     => g365_process_data_point( 'name', $new_data['data']['name']),
                    'nickname' => g365_process_data_point( 'nickname', $new_data['data']['name'] . '-' . $new_data['data']['state'] )
                  ) );
                  //if it fails again, send error message
                  if( $new_id === false ) {
                    $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => g365_output_db_error('Failed to generate new id.'),
                      'element_title' => $new_data['data']['name']
                    );
                    continue;
                  }
                  $new_data['id'] = $wpdb->insert_id;
                  $new_data['wrapper_id'] = $form_group;
                  if( !current_user_can( 'administrator' ) ) {
                    if( !empty($new_data['id']) ) {
                      if( !isset($data_owner_reference['og_ed']) ) $data_owner_reference['og_ed'] = array(); 
                      $data_owner_reference['og_ed'][] = $new_data['id'];
                    }
                    $new_data['data']['access'] = $access_level;
                  }
                } else {
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => 'Missing minimum data points for new record to be written.',
                    'element_title' => $new_data['data']['name']
                  );
                  continue;
                }
							} else {
                //add in access if we are not an admin and there is a command to add
                if( !current_user_can( 'administrator' ) ) {
                  //get current access to make compare against
                  $new_access = json_decode($wpdb->get_var( "SELECT access FROM $wpdb_orgs WHERE id = " . ($new_data['id']) ));
                  //if profile hasn't been claimed for this site, claim it, otherweise exit process
                  if( empty($new_access->{$access_level[1]}) ) {
                    $new_data['data']['access'] = $access_level;
                    if( !isset($data_owner_reference['og_ed']) ) $data_owner_reference['og_ed'] = array();
                    $data_owner_reference['og_ed'][] = $new_data['id'];
                  } else {
                    if( !in_array( $access_level[2], $new_access->{$access_level[1]} ) ) {
                      $data_result['message']->{$type}->{$form_group} = array(
                        'message'       => 'This club profile is already claimed for this site.',
                        'element_title' => $new_data['data']['name']
                      );
                      continue;
                    }
                  }
                }
              }
              //add the id into the dataset
              $new_data['data']['id'] = $new_data['id'];
							//build sql string for this record
							$sql_prepare_query = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array();
							//process each data point and add to the query string
							foreach( $new_data['data'] as $data_name => &$data_value ) {
                //if we need to skip any datapoints
                if( $data_name === 'revoke_access' || ( $data_name === 'nickname' && !current_user_can( 'administrator' ) ) || ( $data_name === 'nickname' && $data_value === '' ) ) continue;
                //santicze and process the raw data based on type
								$data_value = g365_process_data_point( $data_name, $data_value);
                //add the processed value to the array
                $new_data['data_processed']->{$data_name} = $data_value;
                //if the data is an image, process it accordingly
                if($data_name === 'profile_img_data') {
                  //if we don't have a specific command, then continue
                  if( $data_value == '' ) continue;
                  //get reference for image upload directory
                  $uploads_url = wp_upload_dir();
                  //we need to make sure that the image name has been generated before we can process the image data
									$image_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['name'] ) . '_' . $new_data['data']['id'] . '.png' );
                  //put some defaults together
                  $image_content_name = 'Default';
                  $image_content = 'default';
                  //the settings for image processing based on type, we only have one for now
                  switch( $data_name ) {
                    case 'profile_img_data':
                      $image_content_name = "Profile Image";
                      $image_content = 'profile_img';
                      $image_url = $uploads_url['basedir'] . '/org-logos/';
                      $image_current = "profile_img";
                      break;
                  }
                  //see if we have a current image entry
                  $current_image = $wpdb->get_var( 'SELECT ' . $image_current . ' FROM ' . $wpdb_orgs . ' WHERE  id = ' . $new_data['data']['id'] );
                  $current_image_url = $image_url . $current_image;
                  $image_url = $image_url . $image_name;
                  //if we don't have a value remove any leftovers
                  if($data_value === 'null') {
                    if( file_exists( $current_image_url ) )  unlink($current_image_url);
                    $image_name = null;
                  } else {
                    //write the image to the server
                    $file_size = file_put_contents( $image_url, base64_decode( $data_value ) );
                    //if the write fails, make a note and continue 
                    if( $file_size === false ) {
                      $new_data['data_processed']->{$data_name . '_error'} = $image_content_name . ' write error.';
                      continue;
                    }
                    if( !empty($current_image) && $current_image !== $image_name && file_exists($current_image_url) ) unlink($current_image_url);
                    $new_data['data_processed']->{$image_content} = $image_name;
                  }
                  //depending on the type, add the url to the db string
                  switch( $data_name ) {
                    case 'profile_img_data':
                      $sql_prepare_query .= ", $image_content = " . (($image_name === null) ? 'NULL' : "'$image_name'");
                      break;
                  }
                  continue;
                }
                //if we have social data, handle it.
								if( $data_name == 'instagram' || $data_name == 'twitter' || $data_name == 'facebook' || $data_name == 'snapchat' ) {
									if( $data_value === 'null' ) {
										$sql_prepare_query .= ", social = JSON_REMOVE(social, '$." . ucfirst($data_name) . "')";
									} else {
										$sql_prepare_query .= ", social = JSON_SET(COALESCE(social, '{}'), '$." . ucfirst($data_name) . "', '$data_value')";
									}
									continue;
								}
								if( $data_name == 'access' ) {
									if( $new_data['data']['revoke_access'] === 'true' ) {
										$sql_prepare_query .= ", access = JSON_REMOVE(access, '$[" . $data_value[1] . "]" . (($data_value[2] === 'null') ? "" : "[" . intval($data_value[2]) . "]") . "')";
									} else {
										if( !empty($data_value[2]) ) $sql_prepare_query .= ", access = JSON_SET(COALESCE(access, '{}'), '$." . $data_value[1] . "', JSON_ARRAY_APPEND(COALESCE(JSON_EXTRACT(access, '$." . $data_value[1] . "'), '[]'), '$', " . intval($data_value[2]) . "))";
									}
									continue;
								}
                //see if we need to quote the datapoint
								$data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
                //add datapoint to query
								$sql_prepare_query .= ", $data_name = $data_value";
							}
              //truncate return data
              $data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
              //add wrapper reference
              if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$new_data['id']}['wrapper_id'] = $new_data['wrapper_id'];
              //run the query
//               $data_result['message']->{$type} = "UPDATE $wpdb_orgs SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";";
//               echo json_encode($data_result);
//               die();

              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_orgs SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
              //if we actually made a new club, send the welcome message to the user.
              if( !current_user_can( 'administrator' ) && !empty($access_level[3]) && !empty($new_data['data']['access']) ) {
                //get the target url for the editing link
                $keys_by_domain = array_filter( g365_return_keys('keys_by_domain'), function($data) use ($access_level){ return $data[ 'id' ] === $access_level[1]; });
                $keys_by_domain_key = array_keys($keys_by_domain)[0];

                $html = '<div style="background-color:#f7f7f7;margin:0;padding:70px;"><div style="background:#1a315b;color:#ffffff;border-bottom:0;font-weight:bold;vertical-align:middle;font-family: Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0;padding:24px;display:block"><h2>';
                $html .= 'Thank you for creating a new G365 Club Organization';
                $html .= '</h2></div><p style="background:#ffffff;padding:40px 20px;margin:0;">';
                $html .= 'This Organization is accessible/editable from your account.<br><a href="' . $keys_by_domain_key . '/account/club_editor/">You can login here to see your data.</a>';
                $html .= '</p></div>';
                send_html_email( $access_level[3], 'New Club Organization Created', $html );
              }
              //if we are some data types add the redriect at the end
  						switch( $type_key ) {
                case 11:
                  $data_result['message']->{$type}->{$new_data['id']}['redirect'] = "/account/club/";
                  break;
              }
						}
            break;
					case 'claim_data':
            //get type key number from requesting site
            $g365_claim_type_key = g365_return_keys('g365_claim_type_key');
            if( in_array($type, array_keys($g365_claim_type_key)) ) {
              $g365_claim_type_key = $g365_claim_type_key[$type];
            } else {
              $data_result['message']->{$type} = array(
                'message'       => 'No data type match. Please contact your representative.',
                'element_title' => "Current Claim"
              );
              break;
            }
            //get data owner reference
            $data_owner = $wpdb->get_row( "SELECT name, access FROM $wpdb_orgs WHERE id = " . $data_set[$type]['ids'] );

// 	              $data_result['message']->{$type} = $data_set;
// 	              echo json_encode($data_result);
// 	              die();

            if( $data_owner === false ) {
              $data_result['message']->{$type} = array(
                'message'       => 'No data for supplied id. Please contact your representative.',
                'element_title' => "Current Claim"
              );
              break;
            }
            $data_name = $data_owner->name;
            $data_owner = json_decode($data_owner->access);
            //just grab the first user id, presumably they are the master owner
            $data_owner = $data_owner->{$access_level[1]}[0];
            //claim data array
            $claim_data = array( 'type' => $g365_claim_type_key, 'target' => intval($data_set[$type]['ids']), 'site_key' => $access_level[1], 'email' => $access_level[3], 'status' => 1, 'owner_id' => $access_level[2] );
            //add the record to claim table
            $new_claim = $wpdb->insert( $wpdb_claims, $claim_data );
            $new_claim_status = 'Your request to access this account has been submitted. Please contact customer service if you require further assistance, <a href="/contact">here</a>.';
            //is there already a record
            if( $new_claim === false ) {
              $ref_id = $wpdb->get_var( "SELECT id FROM $wpdb_claims where type = " . $claim_data['type'] . " AND target = " . $claim_data['target'] . " AND site_key LIKE '" . $claim_data['site_key'] . "'" . " AND email LIKE '" . $claim_data['email'] . "'" );
              $new_claim_status = 'Your request to access this account has been re-submitted. Please contact customer service if you require further assistance, <a href="/contact">here</a>.';
            } else {
              $ref_id = $wpdb->insert_id;
            }
            //send notice to owner site
            $owner_notice_result = g365_data_sender( $access_level, 'notify', array($data_owner, $data_name, $access_level[3], $ref_id) );
            //check the outcomes and create a status update to send back.
            if( is_array($owner_notice_result) ) {
              $owner_notice_result['claim_status'] = $new_claim_status;
              $data_result['message']->{$type} = $owner_notice_result;
            } else {
              $data_result['message']->{$type} = $new_claim_status . ' ' . $owner_notice_result;
            }
						break;
				}
				break;
      case 4: //team_names: add/edit team
      case 41: //team_names_sl: add team
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
            
						//if any of our resources are missing just read everything
						if(
							!empty($data_result['message']->{$type}->form_template_init) &&
							!empty($data_result['message']->{$type}->form_template) &&
							!empty($data_result['message']->{$type}->form_template_result)
						) break;
						//file paths
						$team_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_team_registration_form.php';
						//only include once
						include_once $team_file_name;

						if ( empty($team_registration_form_min_sl) ) die('<div class="error"><h4>Cannot find extension file. Tm Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($team_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Tm Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            $data_result['message']->{$type}->form_template_init = $team_registration_form_min_sl;
            $data_result['message']->{$type}->form_template_result = $team_registration_result;
						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
            
						//add presets if we need to
						$presets = [];
						if( !empty($data_set[$type]['preset']) ) {
							foreach($data_set[$type]['preset'] as $dex => $preset_pairs) {
								$preset = explode('::', $preset_pairs);
								switch( $preset[0] ) {
                  default:
                    $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }[$preset[0]] = $preset[1];
                    break;
								}
							}
						}

						break;
					case 'get_data':
//             echo("<script>console.log('testing1');</script>");
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
//             die('<div class="error"><h4>Cannot parse team ids.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//pull team data
						if( !empty($ids) ) {
  						foreach( $ids as $tm_dex => $tm_val ) $data_result['message']->{$type}->{$tm_val} = g365_truncate_data( g365_get_team( $tm_val ), $type_key, $access_level );
            } else {
              if( empty($data_set[$type]['contributions']['club_id']) || intval($data_set[$type]['contributions']['club_id']) == 0 ) die('<div class="error">Need Club ID to retrieve teams.</div>');
              $where_string = "org = " . intval($data_set[$type]['contributions']['club_id']);
              $where_string .= ( !empty($data_set[$type]['contributions']['team_level']) && intval($data_set[$type]['contributions']['team_level']) > 0 ) ? ' AND level = ' . intval($data_set[$type]['contributions']['team_level']) : '';
              $where_string .= ( !empty($data_set[$type]['contributions']['event_id']) && intval($data_set[$type]['contributions']['event_id']) > 0 ) ? ' AND event = ' . intval($data_set[$type]['contributions']['event_id']) : '';
              $data_result['message']->{$type} = $wpdb->get_results(
                "SELECT id, CONCAT(level,'U',IFNULL(CONCAT(' ',name), '')) AS title, IFNULL(name, 'Base') AS short_name
                FROM $wpdb_teams
                WHERE $where_string;",
                OBJECT_K
              );
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//process and check incoming data
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
//               echo("<script>console.log('domain -> " . $new_data['id'] . " ');</script>"); //here
							//let's assign an id if we don't have one. Presumabily it's a new team
							if( empty($new_data['id']) ) {
                //process some variables
                $org_id = g365_process_data_point( 'org_id', $new_data['data']['org'] );
                $level = g365_process_data_point( 'level', $new_data['data']['level']);
                $team_name = g365_process_data_point( 'name', $new_data['data']['name']);
                
                $get_org_data = g365_get_org_names( $new_data['data']['org'] );
              
                $org_name = $get_org_data['0']->name;
                $data_name = $new_data['data']['name'];
                if(strpos($data_name, $org_name) !== false){
                  
//                   $new_id = false;
                  $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => (( strpos( site_url(), 'dev' ) !== false ) ? cj_g365_output_db_error('Team name can not contain your organizations name. Please choose a different name.') : 'Team name can not contain your organizations name. Please choose a different name.'),
                      'element_title' => g365_level_key($new_data['data']['level']) . ' ' . $new_data['data']['name']
                    );
                    continue;
                  
                }else{
								
                  $new_id = $wpdb->insert( $wpdb_teams, array(
                    'org'   => $org_id,
                    'level' => $level,
                    'name'  => $team_name
                  ) );
                  
                  //if it fails, send error message or activate the inactive roster
                  if( $new_id === false ) {
                    //try to pull the roster that's associated with this team
                    $default_roster = $wpdb->get_row(
                      "SELECT ros.* FROM $wpdb_rosters AS ros
                      LEFT JOIN $wpdb_teams AS tm ON tm.org = $org_id AND tm.level = $level AND tm.name = '$team_name'
                      WHERE ros.team = tm.id AND ros.event = 0;"
                    );
                    //if the roster is disabled and has no players it is probably from the reset, assume the user is trying to reactivate.
                    if( !empty($default_roster) && $default_roster->enabled === '0' && $default_roster->players === null ) {
  //                     $data_result['message']->{$type}->{$new_data['id']}['message'] = (( $wpdb->query( "UPDATE $wpdb_teams SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.");

                      $data_result['message']->{$type}->{$form_group} = array(
                        'message'       => (( $wpdb->query( "UPDATE $wpdb_rosters SET enabled = 1 WHERE id = $default_roster->id;" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update failed.') : 'Roster failed to activate.') : 'Roster has been successfully activated! Please add players at your convenience.'),
                        'element_title' => g365_level_key($new_data['data']['level']) . ' ' . $new_data['data']['name']
                      );
                      if( strpos($data_result['message']->{$type}->{$form_group}['message'], 'failed') === false ) {
                        switch( $type_key ) {
                          case 41:
                            $data_result['message']->{$type}->{$form_group}['redirect'] = "reload";
                            break;
                        }
                      }
                      continue;
                    } else {
                      $data_result['message']->{$type}->{$form_group} = array(
                        'message'       => (( strpos( site_url(), 'dev' ) !== false ) ? cj_g365_output_db_error('Team exist with active roster.') : 'Team already exists with active roster.'),
                        'element_title' => g365_level_key($new_data['data']['level']) . ' ' . $new_data['data']['name']
                      );
                      continue;
                    }
                  }
                  
//             $data_result['message']->{$type} = gettype($default_roster->players);
//             echo json_encode($data_result);
//             die();

								$new_data['id'] = $wpdb->insert_id;
                $new_data['wrapper_id'] = $form_group;
                 
                //if we make it this far then also write the default rosters for the zero event (club teams)
                $roster_id = $wpdb->insert( $wpdb_rosters, array(
                  'org'   => $org_id,
                  'level' => $level,
                  'team' => $new_data['id'],
                  'event' => 0
                ) );
                //then write the roster id back to the team record
                $new_data['data']['roster'] = strval($wpdb->insert_id);
                  
                }

							}
              
              
              
//               echo("<script>console.log('here here1 " . $new_data['data']['org'] . " name: " . $new_data['data']['name'] . " roster: " . $new_data['data']['roster'] . " id: " . $new_data['id'] . " printr: " . print_r($get_org_data) . " org name here -> " . $get_org_data['0']->name . " ');</script>");

              // Check if the organization name is in the data name and remove it if found
              if(strpos($data_name, $org_name) !== false) {
//                   $data_name = str_replace($org_name, '', $data_name);
//                   $new_data['data']['name'] = $data_name; // Update the original data
                
                  $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => (( strpos( site_url(), 'dev' ) !== false ) ? cj_g365_output_db_error('Team name can not contain your organizations name. Please choose a different name.') : 'Team name can not contain your organizations name. Please choose a different name.'), 
                      'element_title' => g365_level_key($new_data['data']['level']) . ' ' . $new_data['data']['name']
                    );
                    continue;
                
              }else{
                
//               echo("<script>console.log('here here2 " . $new_data['data']['org'] . " name: " . $new_data['data']['name'] . " roster: " . $new_data['data']['roster'] . " id: " . $new_data['id'] . " printr: " . print_r($get_org_data) . " org name here -> " . $get_org_data['0']->name . " ');</script>"); 
              
              
              //add the id into the dataset
              $new_data['data']['id'] = $new_data['id'];
							//build sql string for this record
							$sql_prepare_query = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array();
							//process each data point and add to the query string
							foreach( $new_data['data'] as $data_name => &$data_value ) {
								$data_value = g365_process_data_point( $data_name, $data_value);
                $new_data['data_processed']->{$data_name} = $data_value;
								$data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
								$sql_prepare_query .= ", $data_name = $data_value";
							}

							$data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
              if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$new_data['id']}['wrapper_id'] = $new_data['wrapper_id'];
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_teams SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? cj_g365_output_db_error('Team name can not contain your organizations name. Please choose a different name.') : 'Team name can not contain your organizations name. Please choose a different name.') : "Data updated successfully.";
              //if we are some data types add the redriect at the end
  						switch( $type_key ) {
                case 41:
                  $data_result['message']->{$type}->{$new_data['id']}['redirect'] = "reload";
                  break;
              }
                
              }
              
						}
            break;
				}
				break;
			case 5: //add/edit player evalutation
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
						break;
					case 'get_data':
						break;
					case 'proc_data':
						break;
				}
				break;
			case 8: //rosters_event: rosters for event: managed
			case 9: //rosters: rosters general: stand alone
			case 17: //rosters_teams: add only rosters to events: stand alone
			case 26: //ro_ed: stand alone, single, tournament edit
			case 32: //tournament_roster_admin: stand alone, tournament edit
			case 33: //to_ed: stand alone, tournament roster
      case 39: //rosters_sl, tournament stand alone, single use
      case 40: //rosters_club_sl, club team stand alone, single use
      case 42: //rosters_sl_xl, club with events, stand alone, single use
      case 45: //attendance, checkboxes, stand alone
      case 46: //rosters_teams_admin: add rosters to events: stand alone
      case 58: //player_status, checkboxes, stand alone
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form': 
						//if any of our resources are missing just readd everything
						if(
							!empty($data_result['message']->{$type}->form_template_init) &&
							!empty($data_result['message']->{$type}->form_template) &&
							!empty($data_result['message']->{$type}->form_template_result) &&
							!empty($data_result['message']->club_names->form_template_full) &&
							!empty($data_result['message']->club_names->form_template_result) &&
							!empty($data_result['message']->team->form_template_min) &&
							!empty($data_result['message']->team->form_template_result) &&
							!empty($data_result['message']->coach->form_template_min) &&
							!empty($data_result['message']->coach->form_template_result) &&
							!empty($data_result['message']->player_names->form_template_min) &&
							!empty($data_result['message']->player_names->form_template_result) &&
							!empty($data_result['message']->player_names->form_template_input_item)
						) break;
						//file paths
						$roster_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_roster_registration_form.php';
						$club_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_club_registration_form.php';
						$team_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_team_registration_form.php';
						$coach_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_coach_registration_form.php';
						$player_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
						$event_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_event_registration_form.php';
						//only include once
						include_once $roster_file_name;
						include_once $club_file_name;
						include_once $team_file_name;
						include_once $coach_file_name;
						include_once $player_file_name;
						include_once $event_file_name;
						//check that we have all the templates that we need, and abort if anything is missing
						if ( empty($roster_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_form_full) ) die('<div class="error"><h4>Cannot find extension file. Cb Full.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($club_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Cb Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($team_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Tm Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($team_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Tm Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($coach_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Co Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($coach_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Co Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Pl Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Pl Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($player_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. Pl Inp.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');

						if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
						if( !isset($data_result['message']->club_names) ) $data_result['message']->club_names = (object) array();
						if( !isset($data_result['message']->team_names) ) $data_result['message']->team_names = (object) array();
						if( !isset($data_result['message']->coach_names) ) $data_result['message']->coach_names = (object) array();
						if( !isset($data_result['message']->player_rosters) ) $data_result['message']->player_rosters = (object) array();

						//in case we need to overwrite these set them before the form specification block
						$data_result['message']->player_rosters->form_template_input_item = $player_registration_input_item;
						$data_result['message']->player_rosters->form_template_min = $player_registration_form_min;
						$data_result['message']->player_rosters->form_template_result = $player_registration_result;
						$data_result['message']->club_names->form_template_full = $club_registration_form_full;
						$data_result['message']->club_names->form_template_result = $club_registration_result;
						$data_result['message']->team_names->form_template_min = $team_registration_form_min;
						$data_result['message']->team_names->form_template_result = $team_registration_result;
						$data_result['message']->coach_names->form_template_min = $coach_registration_form_min;
						$data_result['message']->coach_names->form_template_result = $coach_registration_result;

						switch( $type_key ) {
							case 8: //rosters_event needs a different init, everything else is the same
								if ( empty($roster_registration_init_event) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form_ev) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->{$type}->form_template_init = $roster_registration_init_event;
								$data_result['message']->{$type}->form_template = $roster_registration_form_ev;
								break;
							case 9: //rosters: rosters general: stand alone
								if ( empty($roster_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($event_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. EvInput.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->{$type}->form_template_init = $roster_registration_init;
								$data_result['message']->{$type}->form_template = ( current_user_can('administrator') ) ? $roster_registration_form_admin : $roster_registration_form;
								if( !isset($data_result['message']->event_names) ) $data_result['message']->event_names = (object) array();
								$data_result['message']->event_names->form_template_input_item = $event_registration_input_item;
								break;
							case 17: //rosters_teams: add only rosters to events: stand alone
								if ( empty($roster_registration_init_basic) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form_basic) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($event_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. EvInput.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->{$type}->form_template_init = $roster_registration_init_basic;
								$data_result['message']->{$type}->form_template = $roster_registration_form_basic;
								if( !isset($data_result['message']->event_names) ) $data_result['message']->event_names = (object) array();
								$data_result['message']->event_names->form_template_input_item = $event_registration_input_item;
								break;
							case 46: //rosters_teams_admin: add rosters to events: stand alone
								if ( empty($roster_registration_init_basic_admin) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form_admin) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($event_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. EvInput.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->{$type}->form_template_init = $roster_registration_init_basic_admin;
								$data_result['message']->{$type}->form_template = $roster_registration_form_admin;
								if( !isset($data_result['message']->event_names) ) $data_result['message']->event_names = (object) array();
								$data_result['message']->event_names->form_template_input_item = $event_registration_input_item;
								break;
							case 26: //ro_ed: stand alone, club edit
								if ( empty($roster_registration_init_sl) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form_sl) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $roster_registration_init_sl;
                $data_result['message']->{$type}->form_template = $roster_registration_form_sl;
                if( !isset($data_result['message']->event_names) ) $data_result['message']->event_names = (object) array();
								$data_result['message']->event_names->form_template_input_item = $event_registration_input_item;
    						if( !isset($data_result['message']->team_names_sl) ) $data_result['message']->team_names_sl = (object) array();
//                 echo("<script>console.log('testing now ');</script>");
								$data_result['message']->team_names_sl->form_template_init = $team_registration_form_min_sl;
								$data_result['message']->team_names_sl->form_template_result = $team_registration_result;
                if( !isset($data_result['message']->rosters_sl_xl) ) $data_result['message']->rosters_sl_xl = (object) array();
								$data_result['message']->rosters_sl_xl->form_template_init = $roster_registration_form_min_xl_sl;
								$data_result['message']->rosters_sl_xl->form_template_result = $roster_registration_result;
                if( !isset($data_result['message']->rosters_enabled) ) $data_result['message']->rosters_enabled = (object) array();
								$data_result['message']->rosters_enabled->form_template_input_item = $roster_registration_checkbox;
                if( !isset($data_result['message']->rosters_disabled) ) $data_result['message']->rosters_disabled = (object) array();
								$data_result['message']->rosters_disabled->form_template_input_item = $roster_registration_checkbox;
								break;
              case 42: //rosters_sl_xl, club with events, stand alone, single use
								if ( empty($roster_registration_form_min_xl_sl) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_checkbox) ) die('<div class="error"><h4>Cannot find extension file. Form Checkbox.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->rosters_sl_xl->form_template_init = $roster_registration_form_min_xl_sl;
								$data_result['message']->rosters_sl_xl->form_template_result = $roster_registration_result;
								$data_result['message']->rosters_enabled->form_template_input_item = $roster_registration_checkbox;
								$data_result['message']->rosters_disabled->form_template_input_item = $roster_registration_checkbox;
								break;
							case 33: //to_ed: stand alone, tournament roster
								if ( empty($roster_registration_init_sl_to) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form_sl_to) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $roster_registration_init_sl_to;
                $data_result['message']->{$type}->form_template = $roster_registration_form_sl_to;
    						if( !isset($data_result['message']->rosters_sl) ) $data_result['message']->rosters_sl = (object) array();
								$data_result['message']->rosters_sl->form_template_init = $roster_registration_form_min_sl;
								$data_result['message']->rosters_sl->form_template_result = $roster_registration_result;
                if(!empty($data_result['message']->rosters_enabled->form_template_input_item)){
                  $rosters_enabled = $data_result['message']->rosters_enabled->form_template_input_item;
                }else{ $rosters_enabled = ''; }
                if(!empty($data_result['message']->rosters_disabled->form_template_input_item)){
                  $rosters_enabled = $data_result['message']->rosters_disabled->form_template_input_item;
                }else{ $rosters_enabled = ''; }
								$rosters_enabled = $roster_registration_checkbox;
								$rosters_enabled = $roster_registration_checkbox;
								break;
							case 32: //tournament_roster_admin: stand alone, tournament edit
								if ( empty($roster_registration_form_min_admin_sl) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->{$type}->form_template_init = $roster_registration_form_min_admin_sl;
// 								$data_result['message']->event_names->form_template_input_item = $event_registration_input_item_roster_sl;
    						if( !isset($data_result['message']->player_rosters_admin) ) $data_result['message']->player_rosters_admin = (object) array();
								$data_result['message']->player_rosters_admin->form_template_input_item = $player_registration_input_item_roster_sl;
                $curr_years = intval(date("Y"));
                $curr_years = '<option value="">Please select</option>' . implode('', array_map(function($a){ return '<option value="' . $a . '">' . $a . '</option>'; }, range($curr_years - 2, $curr_years - 20)));
                $data_result['message']->player_rosters_admin->form_defaults = (object) array('current_birth_years' => $curr_years);
								break;
							default: // default
								if ( empty($roster_registration_init_club_teams) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								if ( empty($roster_registration_form_teams) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
								$data_result['message']->{$type}->form_template_init = $roster_registration_init_club_teams;
								$data_result['message']->{$type}->form_template = $roster_registration_form_teams;
						}

						//load all the required templates into a tree to send to the user
						$data_result['message']->{$type}->form_template_result = $roster_registration_result;
						//add presets if we need to
						$presets = [];
						if( !empty($data_set[$type]['preset']) ) {
							foreach($data_set[$type]['preset'] as $dex => $preset_pairs) {
								$preset = explode('::', $preset_pairs);
								switch( $preset[0] ) {
									case 'event_id':
										$presets[ $preset[0] ] = g365_truncate_data(g365_get_event_data( $preset[1], true), 29, 0 );
										$data_result['message']->{$type}->{'event_id_' . $preset[1]} = $presets[ $preset[0] ];
										break;
                  case 'user_ac':
                    $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }[$preset[0]] = $preset[1];
                    break;
								}
							}
						}
						//set the styles flag
						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
						//if we don't have any ids just return the startup assets
						if( empty($data_set[$type]['ids']) ) break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
//             echo("<script>console.log('testing now1 ');</script>");
						if( !empty($ids) ) {
//               echo("<script>console.log('testing now2 ');</script>");
							if( empty($data_result['message']->player_rosters) ) $data_result['message']->player_rosters = (object) array();
							foreach( $ids as $ros_dex => $ros_val ){
//                 echo("<script>console.log('testing now3 ');</script>");
								$roster_data_pull = g365_get_roster( $ros_val );
								if( !empty($roster_data_pull->players_full) ) foreach($roster_data_pull->players_full as $id => $data) $data_result['message']->player_rosters->{$id} = $data;
								if( $roster_data_pull->event_id != 0 ) {
//                   echo("<script>console.log('testing now4 ');</script>");
									switch( $type_key ) {
										case 32: //tournament_roster_admin: stand alone, tournament edit
                      //scrub event data from set
//                       echo("<script>console.log('testing now5 $type_key ');</script>");
                      unset($roster_data_pull->event_names);
											$roster_event_data = g365_get_event_data( $roster_data_pull->event_id, true );
//                       echo("<script>console.log('testing now6 ');</script>");
											if( !empty($roster_event_data) ) foreach($roster_event_data as $item => $data) $roster_data_pull->{'event_' . $item} = $data;
                      //parse the divisions in to a dropdown
                      if( !empty($roster_event_data->divisions) ) {
//                         echo("<script>console.log('testing now7 ');</script>");
                        $roster_event_data->divisions = json_decode($roster_event_data->divisions);
                        $data_divisions_process = '';
                        //for the init, if the level is changed the js will rebuild the division dropdown
                        foreach($roster_event_data->divisions->{$roster_event_data->level} as $lvl => $lk_typ) {
                          $data_divisions_process .= ('<option value="' . $lvl . '" data-g365_short_name="' . $lvl . '" data-g365_add_data="' . $lk_typ . '">' . $lvl . '</option>');
                        }
                        $roster_data_pull->division_selector_options = trim($data_divisions_process);
                        if( empty($roster_data_pull->division_selector_options) ) $roster_data_pull->division_selector_options_hide = 'hide';
                      }
											break;
                    default:
        							if( empty($data_result['message']->event_names) ) $data_result['message']->event_names = (object) array();
                      if( !empty($roster_data_pull->events_full) ) foreach($roster_data_pull->events_full as $id => $data) $data_result['message']->event_names->{$id} = $data;
                      break;
									}
								}
								unset($roster_data_pull->players_full, $roster_data_pull->events_full);
								//add in preset data if we have it
								if( isset($presets) ) {
//                   echo("<script>console.log('testing now8 ');</script>");
									switch( $type_key ) {
                    case 17: //rosters_teams: add only rosters to events: stand alone
      							case 46: //rosters_teams_admin: add rosters to events: stand alone
//                       echo("<script>console.log('testing now9 ');</script>");
                      foreach($presets as $data) {
                        //if we are gathering records with event data, we are probably creating new event rosters, add that data into the rosters
                        $roster_data_pull->event_id = $data['event'];
                        $roster_data_pull->event_name = $data['name'];
                        $roster_data_pull->event_short = $data['short_name'];
                        //parse the divisions in to a dropdown
                        $roster_data_pull->division_selector_options_hide = 'hide';
                        if( !empty($data['divisions']) ) {
                          $roster_data_pull->divisions = ' ' . htmlentities($data['divisions'], ENT_COMPAT);
                          $data['divisions'] = json_decode($data['divisions']);
                          $roster_data_pull->division_selector_options_hide = '';
                        }
                      }
                      $roster_data_pull->id = '';
                      $roster_data_pull->field_set_id = $roster_data_pull->org_id . '_' . $roster_data_pull->event_id . '_' . $roster_data_pull->level . '_' . $roster_data_pull->team_id;
											break;
                    default:
                      $roster_data_pull->field_set_id = $roster_data_pull->id;
									}
								}
//                 echo("<script>console.log('testing now10 " . print_r($roster_data_pull) . /*" <br><br><br>// " . print_r($type_key) . "  <br><br><br>// " . print_r($access_level) . */"  <br><br><br>');</script>");
								$data_result['message']->{$type}->{$ros_val} = g365_truncate_data( $roster_data_pull, $type_key, $access_level );
							}
						} else {
              //make the where statement if we don't have ids to pull.
              switch( $type_key ) {
                case 46: //rosters_teams_admin: add rosters to events: stand alone

//             $data_result['message']->{$type} = gettype($data_result['message']->{$type});
//             echo json_encode($data_result);
//             die();

                  //team id is required, otherwise bounce
                  if( empty($data_set[$type]['contributions']['team_selector']) || intval($data_set[$type]['contributions']['team_selector']) == 0 ) die('<div class="error">Need Team ID to get roster.</div>');
                  //stripe slashes off divisions because they've already been passed back and forth a couple time.
                  if( gettype($data_set[$type]['contributions']['divisions']) === 'string' ) $data_set[$type]['contributions']['divisions'] = stripslashes($data_set[$type]['contributions']['divisions']);
                  //see if we have a handle to attach data with, otherwise abort.
                  if( !isset($data_set[$type]['field_group']) ) die('<div class="error">Need field group to return data.</div>');
                  //attach the contributions so they are ready to get dropped in when we return them
                  $data_result['message']->{$type}->{$data_set[$type]['field_group']} = $data_set[$type]['contributions'];
                  //run and assign the db call
                  $player_data = $wpdb->get_var("SELECT ros.players FROM $wpdb_rosters AS ros WHERE ros.team = " . intval($data_set[$type]['contributions']['team_selector']) . " AND ros.event = 0;");
                  //decode the players
                  $player_data = json_decode($player_data);
                  $player_ids = [];
                  //make a list of player ids to get from the database
                  foreach( $player_data as $pl_id => $pl_data ) $player_ids[] = $pl_id;
                  //get all player names
                  $player_ids = $wpdb->get_results("SELECT id, name FROM $wpdb_players WHERE id IN (" . implode(',', $player_ids) . ");", OBJECT_K);
                  //integrate the player name info into the data set
                  foreach( $player_data as $pl_id => &$pl_data ) $pl_data->element_title = $player_ids[$pl_id]->name;
                  //attach the data to the return var
                  $data_result['message']->{$type}->{$data_set[$type]['field_group']}['player_rosters'] = json_encode($player_data);
                  break;
                default:
                  //club_id is required, otherwise bounce
                  if( empty($data_set[$type]['contributions']['club_id']) || intval($data_set[$type]['contributions']['club_id']) == 0 ) die('<div class="error">Need Club ID to get rosters.</div>');
                  //assemble the where string based on the options
                  $where_string = "ros.org = " . intval($data_set[$type]['contributions']['club_id']);
                  $where_string .= ( !empty($data_set[$type]['contributions']['team_level']) && intval($data_set[$type]['contributions']['team_level']) > 0 ) ? ' AND ros.level = ' . intval($data_set[$type]['contributions']['team_level']) : '';
                  $where_string .= ( !empty($data_set[$type]['contributions']['event_id']) && intval($data_set[$type]['contributions']['event_id']) > 0 ) ? ' AND ros.event = ' . intval($data_set[$type]['contributions']['event_id']) : '';
                  $where_string .= ( !empty($data_set[$type]['contributions']['team_selector']) && intval($data_set[$type]['contributions']['team_selector']) > 0 ) ? ' AND ros.team = ' . intval($data_set[$type]['contributions']['team_selector']) : '';
                //check for a limit value
                $limit_string = (empty($data_set[$type]['limit'])) ? '' : 'LIMIT ' . intval($data_set[$type]['limit']);
                //run and assign the db call
                $data_result['message']->{$type} = $wpdb->get_results(
                  "SELECT ros.id, ros.level, tm.name AS tm_name, org.name org_name, ev.short_name AS event_short
                  FROM $wpdb_rosters AS ros
                  LEFT JOIN $wpdb_events AS ev ON ros.event=ev.id
                  LEFT JOIN $wpdb_orgs AS org ON ros.org=org.id
                  LEFT JOIN $wpdb_teams AS tm ON ros.team=tm.id
                  WHERE $where_string $limit_string;",
                  OBJECT_K
                );
              }
							foreach( $data_result['message']->{$type} as $id => &$data ){
// 								$data->title = $data->org_name . ' ' . (g365_level_key($data->level) . ((!empty($data->tm_name) && $data->tm_name !== 'null') ? ' ' . $data->tm_name : ''));
                if(!empty($data->title)){ $data->title = $data->org_name . ' ' . (g365_level_key($data->level) . ((!empty($data->tm_name) && $data->tm_name !== 'null') ? ' ' . $data->tm_name : '')); }
							}
						}
						//add parameters that are needed for a form
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//process and check incoming data
						//parse the javascript form serialization
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            
//             $data_result['message']->{$type} = $data_set;
//             echo json_encode($data_result);
//             die();
            
            switch( $type_key ) {
              case 39: //rosters_sl, tournament stand alone, single use
              case 42: //rosters_sl_xl, club with events, stand alone, single use
                //get the new event id
                $this_event = g365_process_data_point( 'event', $data_set[$type]['form_data'][$type]['data']['event'] );
                //for each new roster process it.
                foreach( $data_set[$type]['form_data'][$type]['data']['ros_data'] as $ros_id => &$ros_data ) {
                  //get the new vaules ready
                  $this_id = g365_process_data_point( 'id', $ros_data['id'] );
                  $this_level = g365_process_data_point( 'id', $ros_data['level'] );
//                   $this_org = g365_process_data_point( 'id', $ros_data['org'] );
                  $this_division = (empty($ros_data['division'])) ? 'NULL' : "'" . g365_process_data_point( 'name', $ros_data['division'] ) . "'";
                  //run the query to duplicate a default roster to attach to an event
                  $data_result['message']->{$type}->{$this_id}['element_title'] = g365_get_team_name(null, $this_id);
                  //where the new roster gets submitted
                  $data_result['message']->{$type}->{$this_id}['message'] = ($wpdb->query("INSERT INTO $wpdb_rosters ( enabled, org, team, event, level, division, team_type, coach, asst, players, description, events, notes )
                    SELECT * FROM (SELECT 1 AS og_enabled, org AS og_org, team AS og_team, $this_event AS og_event, $this_level AS og_level, $this_division AS og_division, team_type AS og_team_type, coach AS og_coach, asst AS og_asst, players AS og_players, description AS og_description, events AS og_events, notes AS og_notes FROM $wpdb_rosters WHERE id = $this_id) AS og_ros
                    ON DUPLICATE KEY UPDATE enabled=og_enabled, org=og_org, team=og_team, event=og_event, level=og_level, division=og_division, team_type=og_team_type, coach=og_coach, asst=og_asst, players=og_players, description=og_description, events=og_events, notes=og_notes;"
                  ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : "Failed to generate new roster") : "Roster successfully written.";
                  
//                   echo("<script>console.log(' testing here : " . $this_event . " " . $data_result['message']->{$type}->{$this_id}['message'] . " ');</script>");
//                   //check to see if the query succeeded to be able to grab the id of the new roster inserted
                  if($data_result['message']->{$type}->{$this_id}['message'] === "Failed to generate new roster"){
                    $data_result['message']->{$type}->{$this_id}['message'] = (strpos(site_url(), 'dev') !== false) ? g365_output_db_error('Database update error.') : "Failed to generate new roster, id=$this_id";
                  }else{
                    $new_id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
                    $data_result['message']->{$type}->{$this_id}['message'] = "Roster successfully written. New ID is $new_id.";
                  }
                  
                  $sending_email = spp_send_roster_submission_email($new_id, $this_event); //email variable is the new roster id needed. Org info will be grabbed in the function
                  
                  
//                   echo("<script>console.log(' testing here2 : " . $this_event . " " . $new_id . " ');</script>");
                  $data_result['message']->{$type}->{$this_id}['redirect'] = "reload";
                }
                break;
              case 45: //attendance, checkboxes, stand alone
                //get roster id
                $this_id = g365_process_data_point( 'id', $data_set[$type]['form_data']['id'] );
                $pl_id = g365_process_data_point( 'id', $data_set[$type]['form_data']['pl_id'] );
                if( empty($data_set[$type]['form_data']['attend']) ) {
                  $sql_prepare_query = "notes = JSON_REMOVE(notes, '$.attendance.\"$pl_id\"')";
                } else {
                  foreach($data_set[$type]['form_data']['attend'] as &$days) $days = '"' . $days . '"';
                  $attendance_day = str_replace('"', '\'', implode(',', $data_set[$type]['form_data']['attend']));
                  $sql_prepare_query = "notes = JSON_SET(COALESCE(notes, '{}'), '$.attendance', JSON_MERGE_PATCH(COALESCE(JSON_EXTRACT(notes, '$.attendance'), '{}'), JSON_OBJECT('$pl_id', JSON_ARRAY($attendance_day))))";
                } 
                $data_result['message']->{$type}->{$this_id}['element_title'] = 'Player: ' . $pl_id;
                $data_result['message']->{$type}->{$this_id}['message'] = ($wpdb->query( "UPDATE $wpdb_rosters SET $sql_prepare_query WHERE id = $this_id;" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : "Failed to update attendance.") : "Attendance updated.";
                break;
              case 58: //player_status, checkboxes, stand alone
                //get player id
                $this_id = g365_process_data_point( 'id', $data_set[$type]['form_data']['id'] );
                $pl_id = g365_process_data_point( 'id', $data_set[$type]['form_data']['pl_id'] );
                if(empty($data_set[$type]['form_data']['status'])) {
                  $sql_prepare_query = "notes =  JSON_REMOVE(notes, '$.player_status.\"$pl_id\"')";
                } else {
                  foreach($data_set[$type]['form_data']['status'] as &$player_status) $player_status = '"' . $player_status . '"';
                  $pl_status = str_replace('"', '\'', implode(',', $data_set[$type]['form_data']['status']));
                  $sql_prepare_query = "notes = JSON_SET(COALESCE(notes, '{}'), '$.player_status', JSON_MERGE_PATCH(COALESCE(JSON_EXTRACT(notes, '$.player_status'), '{}'), JSON_OBJECT('$pl_id', JSON_ARRAY($pl_status))))";
                } 
                $data_result['message']->{$type}->{$this_id}['element_title'] = 'Player: ' . $pl_id;
                $data_result['message']->{$type}->{$pl_id}['message'] = ($wpdb->query( "UPDATE $wpdb_players SET $sql_prepare_query WHERE id = $pl_id;") === false ) ? ((strpos(site_url(), 'dev') !== false) ? g365_ouput_db_error('Database update error.') : "Failed to update status.") : "Status updated.";
                break;
              default:
                //sanitize and pre-process incoming data
                foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
//                   echo("<script>console.log(' routing: ". print_r($new_data) ." ');</script>");
                  $this_id = g365_process_data_point( 'id', $new_data['id'] );
                  $this_org = g365_process_data_point( 'org_id', $new_data['data']['org'] );
                  $this_event = g365_process_data_point( 'event', $new_data['data']['event'] );
                  $this_team = g365_process_data_point( 'team', $new_data['data']['team'] );
                  $this_level = g365_process_data_point( 'level', $new_data['data']['level'] );
                  //if there is a roster id, check it to make sure we should be writing vs create new
                  if( $this_id !== 'null' ) {
//                     echo("<script>console.log(' routing1: ');</script>");
                    //double check existing roster
                    $roster = $wpdb->get_row( "SELECT ros.*, tm.level as team_level, tm.name FROM $wpdb_rosters AS ros LEFT JOIN $wpdb_teams AS tm ON ros.team=tm.id WHERE ros.id = $this_id" );
                    //if the provided event is different than the database, create a new record
                    if( $roster->event != $this_event || $roster->team != $this_team ) $this_id = 'null';
                  }
                  //let's assign an id if we don't have one. Presumabily it's a new roster
                  if( $this_id === 'null' ) {
//                     echo("<script>console.log(' routing2: ');</script>");
                    //pull the roster (potentially again) based on unique columns 
                    $roster = $wpdb->get_row( "SELECT ros.*, tm.name FROM $wpdb_rosters AS ros LEFT JOIN $wpdb_teams AS tm ON ros.team=tm.id WHERE ros.team = $this_team AND ros.event = $this_event" );
                    //if there is no match then add the roster record
                    if( is_numeric($roster->id) ) {
                      $this_id = $roster->id;
                    } else {
                      $new_id = $wpdb->insert( $wpdb_rosters, array(
                        'org'   => $this_org,
                        'level' => $this_level,
                        'team' => $this_team,
                        'event' => $this_event
                      ) );
                      //if it fails, send error message
                      if( $new_id === false ) {
                        $org_name = $wpdb->get_var( "SELECT name FROM $wpdb_orgs WHERE id = $this_org" );
                        $team_name = $wpdb->get_var( "SELECT name FROM $wpdb_teams WHERE id = $this_team" );
                        $data_result['message']->{$type}->{$form_group} = array(
                          'message'       => g365_output_db_error('Failed to generate new id.'),
                          'element_title' => $org_name . ' ' . g365_level_key($this_level) . ((!empty($team_name) && $team_name !== 'null') ? ' ' . $team_name : '')
                        );
                        continue;
                      }
                      //if it is a success and they want the same roster updated for default team, update now

                      $this_id = $wpdb->insert_id;
                    }
                    $new_data['wrapper_id'] = $form_group;
                  }
//                   echo("<script>console.log(' routing3: ');</script>");
                  //add the id into the dataset
                  $new_data['data']['id'] = $this_id;
                  //build sql string for this record
                  $sql_prepare_query = '';
                  //compiled object to send back
                  $new_data['data_processed'] = (object) array();
                  //process each data point and add to the query string
                  foreach( $new_data['data'] as $data_name => &$data_value ) {
                    $data_value = g365_process_data_point( $data_name, $data_value);
                    $new_data['data_processed']->{$data_name} = $data_value;                    
                    switch( $data_name ) {
                      //comes from the player checkbox for admins to push players to default roster
                      case 'push_to_def':
                        //see if we have player data to start with
                        if( !empty($new_data['data']['players']) ) {
//                           echo("<script>console.log(' routing4: ');</script>");
                          //////
                          // Get the existing player data from the database
                          $existing_players = $wpdb->get_var($wpdb->prepare("SELECT players FROM $wpdb_rosters WHERE team = %d AND event = 0;", $new_data['data']['team']));

                          // Log the existing player data for debugging
                          error_log("Existing player data: " . $existing_players);

                          // Check if existing player data was retrieved
                          if ($existing_players === null) {
                              error_log("No existing player data found for team " . $new_data['data']['team']);
                              $existing_players_array = [];
                          } else {
                              // Decode the existing player data to an associative array
                              $existing_players_array = json_decode($existing_players, true);
//                               echo("<script>console.log(' routing8: ". print_r($existing_players_array) ." ');</script>");
                          }
                          
                           // Decode the new player data to an associative array
                            $new_players_array = $new_data['data']['players'];

                            // Create a new array for merged players
                            $merged_players = $existing_players_array;

                            // Update or add new players from the new player data
                            foreach ($new_players_array as $key => $player) {
                                $merged_players[$key] = $player;
                            }

                            // Remove players that are not in the new player data
                            foreach ($existing_players_array as $key => $player) {
                                if (!array_key_exists($key, $new_players_array)) {
                                    unset($merged_players[$key]);
                                }
                            }

                            // Encode the merged data back to JSON
                            $merged_players_json = json_encode($merged_players);

                            // Prepare and execute the update query
                            $update_query = $wpdb->prepare("UPDATE $wpdb_rosters SET players = %s WHERE team = %d AND event = 0;", $merged_players_json, $new_data['data']['team']);
                            $update_result = $wpdb->query($update_query);

                            // Check if the update was successful
                            if ($update_result === false) {
                                // Log the error for debugging
                                error_log("Database update error: " . $wpdb->last_error);

                                // Set the error message based on the environment
                                $new_data['data_processed']->player_default = (strpos(site_url(), 'dev') !== false) ? g365_output_db_error('Database update error.') : 'Data update error.';
                            } else {
                                // Set the success message
                                $new_data['data_processed']->player_default = "Data updated successfully.";
                            }
                          
                          //////
                          //if we have some players to integrate do it with MySQL
//                           $new_data['data_processed']->player_default = ( $wpdb->query( "UPDATE $wpdb_rosters SET players = JSON_MERGE_PATCH(COALESCE(players, '{}'), '" . json_encode($new_data['data']['players']) . "') WHERE team = " . $new_data['data']['team'] . " AND event = 0;" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
                          continue 2;
                        }
                        break;
                      case 'team_name':
                        if($new_data['data_processed']->team_name === 'null') $new_data['data_processed']->team_name = '';
                        continue 2;
                        break;
                      case 'players':
                      case 'events':
                        if( current_user_can('club') ) {
                            $roster_info = g365_get_roster($this_id);
                            $roster_players = json_decode($roster_info->player_names, true);
                        }
                        
                        if( $data_value !== '' && $data_value !== 'null' ) $data_value = json_encode($data_value);
//                         echo("<script>console.log(' routing5: ');</script>");
                        
                        if( $data_value !== '' && $data_value !== 'null' ){
//                           echo("<script>console.log(' routing6: ". $data_value ." ');</script>");
                        }
  
//                         if( current_user_can('club') ) {
                            
//                             $data_players = json_decode($data_value, true);
//                             // Find added and removed players
//                             //echo("<script>console.log('values: here: -> data_players: " . print_r($data_players) . " roster_players: " . $roster_players . " team: " . $this_team . " id: " . $this_id . " roster_info: " . print_r($roster_info) . " <- :finale ');</script>");
//                             $added_players = array_diff_key($data_players, $roster_players);
//                             $removed_players = array_diff_key($roster_players, $data_players);
//                             $team_display = g365_get_team($this_team);

//                             if(!empty($added_players)){ //cj
//                               spp_player_roster_notice($added_players, $team_display, 'added');
//                             }

//                             if(!empty($removed_players)){
//                               spp_player_roster_notice($removed_players, $team_display, 'removed');
//                             }
//                         }
                        
                        break;
                      case 'date_restrictions':
                      case 'team_restrictions':
                      case 'pool_name':
                      case 'pool_number':
                        if( $data_value === '' || $data_value === 'null' ) {
                          $sql_prepare_query .= ", notes = JSON_REMOVE(notes, '$.$data_name')";
                        } else {
                          $sql_prepare_query .= ", notes = JSON_SET(COALESCE(notes, '{}'), '$.$data_name', '$data_value')";
                        }
                        continue 2;
                        break;
                    }
                    $data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
                    $sql_prepare_query .= ", $data_name = $data_value";
                  }
                  //if we are an admin and want to rename the 'team' instead of the 'roster'
                  if( $type_key === 32 && isset($new_data['data_processed']->team_name) )  $data_result['message']->{$type}->{$this_id}['team_name'] = ( $wpdb->query( "UPDATE $wpdb_teams SET name = '" . $new_data['data_processed']->team_name . "' WHERE id = " . $new_data['data_processed']->team . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";


//                   $data_result['message']->{$type} = "UPDATE $wpdb_rosters SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $this_id . ";";
//                   echo json_encode($data_result);
//                   die();

                  $data_result['message']->{$type}->{$this_id} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
                  if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$this_id}['wrapper_id'] = $new_data['wrapper_id'];
//                   echo("<script>console.log('values: testing2: " . substr($sql_prepare_query, 2) . " " . $this_id . "');</script>");
                  $data_result['message']->{$type}->{$this_id}['message'] = ( $wpdb->query( "UPDATE $wpdb_rosters SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $this_id . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
                  //if we are some data types add the redriect at the end
                  switch( $type_key ) {
                    case 26: //ro_ed: stand alone, club edit
                    case 32: //tournament_roster_admin: stand alone, tournament edit
                    case 33: //to_ed: stand alone, tournament roster
                     $data_result['message']->{$type}->{$this_id}['redirect'] = "reload";
                  }
                }
                break;
            }
						break;
					case 'delete_data':
						//check for incoming data
						if( empty($data_set[$type]['ids']) ) die('<div class="error"><h4>Cannot find ids to delete.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if( !is_array($data_set[$type]['ids']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            
            //check for admin
            if( current_user_can( 'administrator' ) ) {
              //check for deletability
              //do the deleteing
              $result = '';
              foreach( $data_set[$type]['ids'] as $id ) {
                $result .= "Roster $id removal: " . (($wpdb->delete( $wpdb_rosters, array( 'id' => $id ) )) ? 'success' : 'fail');
              }
              $data_result['message']->{$type} = $result;
            } else {
              $data_result['message']->{$type} = "Insufficient permissions to remove record.";
            }

            
//               $data_result['message']->{$type} = $data_set[$type];
//               echo json_encode($data_result);
//               die();
            
						break;
				}
				break;
      case 12: //coach_names: add/edit stand-alone
      case 31: //co_ed: stand-alone profile editor
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
            //if any of our resources are missing just readd everything
						if(
              !empty($data_result['message']->{$type}->form_template_init) &&
//               !empty($data_result['message']->{$type}->form_template) &&
              !empty($data_result['message']->{$type}->form_template_result)
            ) break;
            //file paths
						$coach_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_coach_registration_form.php';
            //only include once
						include_once $coach_file_name;
            //check that we have all the templates that we need, and abort if anything is missing
            if ( empty($coach_registration_form_sl) ) die('<div class="error"><h4>Cannot find extension file. Co Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($coach_registration_init_sl) ) die('<div class="error"><h4>Cannot find extension file. Co Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($coach_registration_form) ) die('<div class="error"><h4>Cannot find extension file. Co Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($coach_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Co Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');

            if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();

            switch( $type_key ) {
              case 31: //co_ed needs a different init
                $data_result['message']->{$type}->form_template_init = $coach_registration_init_sl;
                $data_result['message']->{$type}->form_template = $coach_registration_form_ed;
                break;
              default: // rosters_teams and default for new registers
                $data_result['message']->{$type}->form_template_init = $coach_registration_form_sl;
            }
            $data_result['message']->{$type}->form_template_result = $coach_registration_result;

						//set the styles flag
						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
            //if we don't have any ids just return the startup assets
            if( empty($data_set[$type]['ids']) ) break;
						break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
						//pull team data
						if( !empty($ids) ) {
  						foreach( $ids as $co_dex => $co_val ) $data_result['message']->{$type}->{$co_val} = g365_truncate_data( g365_get_coach( $co_val ), $type_key, $access_level );
            } else {
              if( empty($data_set[$type]['contributions']['club_id']) || intval($data_set[$type]['contributions']['club_id']) == 0 ) die('<div class="error">Need Club ID to retrieve teams.</div>');
              $where_string = "org = " . intval($data_set[$type]['contributions']['club_id']);
              $coach_data = $wpdb->get_results(
                "SELECT * FROM $wpdb_coaches WHERE $where_string;",
                OBJECT_K
              );
  						if( !empty( $coach_data ) ) foreach( $coach_data as $co_id => $co_val ) $data_result['message']->{$type}->{$co_id} = g365_truncate_data( $co_val, $type_key, $access_level );
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            //process and check incoming data
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
							//let's assign an id if we don't have one. Presumabily it's a new coach
							if( empty($new_data['id']) ) {
                $first_name = g365_process_data_point( 'first_name', $new_data['data']['first_name']);
                $last_name = g365_process_data_point( 'last_name', $new_data['data']['last_name']);
                $new_coach_data = array(
                  'first_name' => $first_name,
                  'last_name' => $last_name,
                  'nickname' => g365_process_data_point( 'nickname', ((!empty($new_data['data']['nickname']) && current_user_can( 'administrator' )) ? $new_data['data']['nickname'] : $first_name . '-' . $last_name) )
                );
                $write_owner_access = true;
                //try to insert a new record
								$new_id = $wpdb->insert( $wpdb_coaches, $new_coach_data );
                //is there already a record
								if( $new_id === false ) {
                  //if we already have a record pull it to see if we should take further action
                  $new_id = $wpdb->get_row( "SELECT * FROM $wpdb_coaches WHERE nickname LIKE '" . $new_coach_data['nickname'] . "'" );
                  //if we have data make some descitions
                  if( $new_id !== null ) {
                    //if we are an admin user skip all the locking
                    if( current_user_can( 'administrator' ) ) {
                      //set the id and continue
                      $new_data['id'] = $new_id->id;
                      $write_owner_access = false;
                    } else {
                      //if there isn't any data to check against, claim this profile
                      if( $new_id->access === null ) {
                        $new_data['id'] = $new_id->id;
                      } else {
                        //parse the access array
                        $new_id->access = json_decode($new_id->access);
                        //if this user ison the list add the id
                        if( in_array( $access_level[2], $new_id->access->{$access_level[1]} ) ) {
                          $new_data['id'] = $new_id->id;
                          $write_owner_access = false;
                        } else {
                          //add state and try to add coach
                          if( !empty($new_data['data']['state']) ) $new_coach_data['nickname'] = g365_process_data_point( 'nickname', $first_name . '-' . $last_name . '-' . $new_data['data']['state']);
                          $new_id = $wpdb->insert( $wpdb_coaches, $new_coach_data );
          								if( $new_id === false ) {
                            $data_result['message']->{$type}->{$form_group} = array(
                              'message'       => 'This coach profile is already claimed and unavailable for your state.',
                              'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                            );
                            continue;
                          }
                          $new_data['id'] = $new_id->id;
                        }
                      }
                    } //end administrator
                  } else {
                    //if we tried to pull the preventing record and we fail
                    $data_result['message']->{$type}->{$form_group} = array(
                      'message'       => g365_output_db_error('Failed to generate new coach id.'),
                      'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                    );
                    continue;
                  }
                }
                //write the vars
                if( empty($new_data['id']) ) $new_data['id'] = $wpdb->insert_id;
                $new_data['wrapper_id'] = $form_group; 
                //should we write the owner access
                if( $write_owner_access && !empty($new_data['id']) ) {
                  if( !isset($data_owner_reference['co_ed']) ) $data_owner_reference['co_ed'] = array(); 
                  $data_owner_reference['co_ed'][] = $new_data['id'];
                }
              }
              //add the id into the dataset
              $new_data['data']['id'] = $new_data['id'];
							//build sql string for this record
							$sql_prepare_query = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array();
							//process each data point and add to the query string
							foreach( $new_data['data'] as $data_name => &$data_value ) {
                //if we need to skip any datapoints
                if( $data_name === 'revoke_access' || ( $data_name === 'nickname' && !current_user_can( 'administrator' ) && ($data_value === null || $data_value === ''))) continue;
                //santicze and process the raw data based on type
								$data_value = g365_process_data_point( $data_name, $data_value);
                //add the processed value to the array
                $new_data['data_processed']->{$data_name} = $data_value;
                //if the data is an image, process it accordingly
                if($data_name === 'profile_img_data') {
                  //if we don't have a specific command, then continue
                  if( $data_value == '' ) continue;
                  //get reference for image upload directory
                  $uploads_url = wp_upload_dir();
                  //we need to make sure that the image name has been generated before we can process the image data
									$image_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name'] ) . '_' . $new_data['data']['id'] . '.jpg' );
                  //put some defaults together
                  $image_content_name = 'Default';
                  $image_content = 'default';
                  //the settings for image processing based on type, we only have one for now
                  switch( $data_name ) {
                    case 'profile_img_data':
                      $image_content_name = "Profile Image";
                      $image_content = 'profile_img';
                      $image_url = $uploads_url['basedir'] . '/coach-profiles/';
                      $image_current = "profile_img";
                      break;
                  }
                  //see if we have a current image entry
                  $current_image = $wpdb->get_var( 'SELECT ' . $image_current . ' FROM ' . $wpdb_coaches . ' WHERE  id = ' . $new_data['data']['id'] );
                  $current_image_url = $image_url . $current_image;
                  $image_url = $image_url . $image_name;
                  //if we don't have a value remove any leftovers
                  if($data_value === 'null') {
                    if( file_exists( $current_image_url ) )  unlink($current_image_url);
                    $image_name = null;
                  } else {
                    //write the image to the server
                    $file_size = file_put_contents( $image_url, base64_decode( $data_value ) );
                    //if the write fails, make a note and continue 
                    if( $file_size === false ) {
                      $new_data['data_processed']->{$data_name . '_error'} = $image_content_name . ' write error.';
                      unset($new_data['data_processed']->{$data_name});
                      continue;
                    }
                    if( !empty($current_image) && $current_image !== $image_name && file_exists($current_image_url) ) unlink($current_image_url);
                    $new_data['data_processed']->{$image_content} = $image_name;
                    unset($new_data['data_processed']->{$data_name});
                  }
                  //depending on the type, add the url to the db string
                  switch( $data_name ) {
                    case 'profile_img_data':
                      $sql_prepare_query .= ", $image_content = " . (($image_name === null) ? 'NULL' : "'$image_name'");
                      break;
                  }
                  continue;
                }
                //if we have social data, handle it.
								if( $data_name == 'instagram' || $data_name == 'twitter' || $data_name == 'facebook' || $data_name == 'snapchat' ) {
									if( $data_value === 'null' ) {
										$sql_prepare_query .= ", social = JSON_REMOVE(social, '$." . ucfirst($data_name) . "')";
									} else {
										$sql_prepare_query .= ", social = JSON_SET(COALESCE(social, '{}'), '$." . ucfirst($data_name) . "', '$data_value')";
									}
									continue;
								}
								if( $data_name == 'access' ) {
									if( $new_data['data']['revoke_access'] === 'true' ) {
										$sql_prepare_query .= ", access = JSON_REMOVE(access, '$[" . $data_value[1] . "]" . (($data_value[2] === 'null') ? "" : "[" . intval($data_value[2]) . "]") . "')";
									} else {
										if( !empty($data_value[2]) ) $sql_prepare_query .= ", access = JSON_SET(COALESCE(access, '{}'), '$." . $data_value[1] . "', JSON_ARRAY_APPEND(COALESCE(JSON_EXTRACT(access, '$." . $data_value[1] . "'), '[]'), '$', " . intval($data_value[2]) . "))";
									}
									continue;
								}
                //see if we need to quote the datapoint
								$data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
                //add datapoint to query
								$sql_prepare_query .= ", $data_name = $data_value";
							}
              $data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
              if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$new_data['id']}['wrapper_id'] = $new_data['wrapper_id'];
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_coaches SET " . substr($sql_prepare_query, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
            }
            break;
				}
				break;
			case 21: //event_names managed sub form
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':

//             //if any of our resources are missing just readd everything
// 						if(
//               !empty($data_result['message']->{$type}->form_template_init) &&
//               !empty($data_result['message']->{$type}->form_template) &&
//               !empty($data_result['message']->{$type}->form_template_result) &&
//               !empty($data_result['message']->club_names->form_template_full) &&
//               !empty($data_result['message']->club_names->form_template_result) &&
//               !empty($data_result['message']->team->form_template_min) &&
//               !empty($data_result['message']->team->form_template_result) &&
//               !empty($data_result['message']->coach->form_template_min) &&
//               !empty($data_result['message']->coach->form_template_result) &&
//               !empty($data_result['message']->player_names->form_template_min) &&
//               !empty($data_result['message']->player_names->form_template_result) &&
//               !empty($data_result['message']->player_names->form_template_input_item) 
//             ) continue;
//             //file paths
// 						$roster_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_roster_registration_form.php';
// 						$club_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_club_registration_form.php';
// 						$team_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_team_registration_form.php';
// 						$coach_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_coach_registration_form.php';
// 						$player_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
//             //only include once
// 						include_once $roster_file_name;
// 						include_once $club_file_name;
// 						include_once $team_file_name;
// 						include_once $coach_file_name;
// 						include_once $player_file_name;
//             //check that we have all the templates that we need, and abort if anything is missing
// 						if ( empty($roster_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//             if ( empty($club_registration_form_full) ) die('<div class="error"><h4>Cannot find extension file. Cb Full.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
// 						if ( empty($club_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Cb Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//             if ( empty($team_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Tm Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
// 						if ( empty($team_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Tm Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//             if ( empty($coach_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Co Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
// 						if ( empty($coach_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Co Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//             if ( empty($player_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Pl Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
// 						if ( empty($player_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Pl Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
// 						if ( empty($player_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. Pl Inp.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');

//             if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
//             if( !isset($data_result['message']->club_names) ) $data_result['message']->club_names = (object) array();
//             if( !isset($data_result['message']->team_names) ) $data_result['message']->team_names = (object) array();
//             if( !isset($data_result['message']->coach_names) ) $data_result['message']->coach_names = (object) array();
//             if( !isset($data_result['message']->player_names) ) $data_result['message']->player_names = (object) array();
            
//             switch( $type_key ) {
//               case 8: //rosters_event needs a different init, everything else is the same
//                 if ( empty($roster_registration_init_event) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 if ( empty($roster_registration_form_basic) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 $data_result['message']->{$type}->form_template_init = $roster_registration_init_event;
//                 $data_result['message']->{$type}->form_template = $roster_registration_form_basic;
//                 break;
//               case 9: // rosters
//     						$event_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_event_registration_form.php';
//                 include_once $event_file_name;
//                 if ( empty($roster_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 if ( empty($roster_registration_form) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//     						if ( empty($event_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. EvInput.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 $data_result['message']->{$type}->form_template_init = $roster_registration_init;
//                 $data_result['message']->{$type}->form_template = $roster_registration_form;
//                 if( !isset($data_result['message']->event_names) ) $data_result['message']->event_names = (object) array();
//                 $data_result['message']->event_names->form_template_input_item = $event_registration_input_item;
//                 break;
//               default: // rosters_teams and default
//                 if ( empty($roster_registration_init_club_teams) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 if ( empty($roster_registration_form_basic) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
//                 $data_result['message']->{$type}->form_template_init = $roster_registration_init_club_teams;
//                 $data_result['message']->{$type}->form_template = $roster_registration_form_basic;
//             }

// 						//load all the required templates into a tree to send to the user
//             $data_result['message']->{$type}->form_template_result = $roster_registration_result;
//             $data_result['message']->club_names->form_template_full = $club_registration_form_full;
//             $data_result['message']->club_names->form_template_result = $club_registration_result;
//             $data_result['message']->team_names->form_template_min = $team_registration_form_min;
//             $data_result['message']->team_names->form_template_result = $team_registration_result;
//             $data_result['message']->coach_names->form_template_min = $coach_registration_form_min;
//             $data_result['message']->coach_names->form_template_result = $coach_registration_result;
//             $data_result['message']->player_names->form_template_min = $player_registration_form_min;
//             $data_result['message']->player_names->form_template_result = $player_registration_result;
//             $data_result['message']->player_names->form_template_input_item = $player_registration_input_item;
// 						//set the styles flag

						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
						break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
            if( !empty($ids) ) {
  						foreach( $ids as $event_dex => $event_val ) $data_result['message']->{$type}->{$event_val} = g365_truncate_data( g365_get_events($event_val, null, 'id, name, short_name, eventtime'), $type_key, $access_level );
            } else {
              //search by org, eventtime
              if( (empty($data_set[$type]['contributions']['club_id']) || intval($data_set[$type]['contributions']['club_id']) == 0) && (empty($data_set[$type]['contributions']['eventtime']) || intval($data_set[$type]['contributions']['eventtime']) == 0) ) die('<div class="error">Need Club ID or Event Time to retrieve related events.</div>');
              //send appropriate data to get event info
              $event_result = g365_get_events(null, (( !empty($data_set[$type]['contributions']['club_id']) && intval($data_set[$type]['contributions']['club_id']) > 0 ) ? $data_set[$type]['contributions']['club_id'] : null), 'id, name, short_name, eventtime', (( !empty($data_set[$type]['contributions']['eventtime']) && intval($data_set[$type]['contributions']['eventtime']) > 0 ) ? $data_set[$type]['contributions']['eventtime'] : null) );
              foreach( $event_result as $ev_dex => $ev_val ) $data_result['message']->{$type}->{$ev_val} = g365_truncate_data( $ev_data, $type_key, $access_level );
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//process and check incoming data
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
							//let's assign an id if we don't have one. Presumabily it's a new user
							if( empty($new_data['id']) ) {
								$new_id = $wpdb->insert( $wpdb_coaches, array(
                  'first_name' => g365_process_data_point( 'first_name', $new_data['data']['first_name']),
                  'last_name' => g365_process_data_point( 'last_name', $new_data['data']['last_name']),
                  'nickname' => g365_process_data_point( 'nickname', $new_data['data']['first_name'] . '-' . $new_data['data']['last_name'])
                ) );
								if( $new_id === false ) {
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => g365_output_db_error('Failed to generate new id.'),
                    'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                  );
                  continue;
                }
								$new_data['id'] = $wpdb->insert_id;
                $new_data['wrapper_id'] = $form_group; 
							}
              //add the id into the dataset
              $new_data['data']['id'] = $new_data['id'];
							//build sql string for this record
							$sql_prepare_query = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array();
							//process each data point and add to the query string
							foreach( $new_data['data'] as $data_name => &$data_value ) {
								$data_value = g365_process_data_point( $data_name, $data_value);
								if( $data_name == 'profile_img_data' ) {
									$profile_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name'] ) . '_' . $new_data['data']['id'] . '.jpg' );
									$uploads_url = wp_upload_dir();
									$profile_url = $uploads_url['basedir'] . '/coach-profiles/' . $profile_name;
									$file_size = file_put_contents( $profile_url, base64_decode( $data_value ) );
									if( $file_size === false ) {
										$new_data['data']['profile_img_error'] = 'Write error.';
										continue;
									}
									$sql_prepare_query .= ", profile_img = '$profile_name'";
									$new_data['data']['profile_img'] = $profile_name;
									unset($new_data['data']['profile_img_data']);
									continue;
								}
								if( $data_name == 'instagram' || $data_name == 'twitter' || $data_name == 'facebook' || $data_name == 'snapchat' ) {
									if( $data_value === 'null' ) {
										$sql_prepare_query .= ", social = JSON_REMOVE(social, '$." . ucfirst($data_name) . "')";
									} else {
										$sql_prepare_query .= ", social = JSON_SET(COALESCE(social, '{}'), '$." . ucfirst($data_name) . "', '$data_value')";
									}
									continue;
								}
                $new_data['data_processed']->{$data_name} = $data_value;
								$data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
								$sql_prepare_query .= ", $data_name = $data_value";
							}
							$data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
              if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$new_data['id']}['wrapper_id'] = $new_data['wrapper_id'];
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_coaches SET " . substr($sql_prepare_query, 2) . " WHERE id = $new_data[id];" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
						}
						break;
				}
				break;
			case 22: //team_rankings managed sub form
			case 23: //player_watchlist managed sub form
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':

            //if any of our resources are missing just readd everything
						if(
              !empty($data_result['message']->{$type}->form_template_init) &&
              !empty($data_result['message']->{$type}->form_template) &&
              !empty($data_result['message']->{$type}->form_template_result) &&
              !empty($data_result['message']->group->form_template_full) &&
              !empty($data_result['message']->group->form_template_result) &&
              (
                ( $type_key === 22 &&
                  !empty($data_result['message']->team_names->form_template_min) &&
                  !empty($data_result['message']->team_names->form_template_result) &&
                  !empty($data_result['message']->team_names->form_template_input_item)
                ) || (
                  $type_key === 23 &&
                  !empty($data_result['message']->player_names->form_template_min) &&
                  !empty($data_result['message']->player_names->form_template_result) &&
                  !empty($data_result['message']->player_names->form_template_input_item) 
                )
              )
            ) break;
            //file paths
						$ranking_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_roster_registration_form.php';
						$group_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_group_registration_form.php';
						$team_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_team_registration_form.php';
						$player_file_name = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_player_registration_form.php';
            //only include once
						include_once $ranking_file_name;
						include_once $group_file_name;
						include_once $team_file_name;
						include_once $player_file_name;
            //check that we have all the templates that we need, and abort if anything is missing
						if ( empty($ranking_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            if ( empty($group_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Gr Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            //set the default objects
            if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
            if( !isset($data_result['message']->group) ) $data_result['message']->group = (object) array();
						
            switch( $type_key ) {
              case 22: //team_rankings managed sub form
                if ( empty($ranking_registration_team_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($ranking_registration_team_form) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($group_registration_team_form_min) ) die('<div class="error"><h4>Cannot find extension file. GrTe Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($team_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Tm Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($team_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Tm Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
    						if ( empty($team_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. Tm Inp.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');

                if( !isset($data_result['message']->team_names) ) $data_result['message']->team_names = (object) array();
                
                $data_result['message']->{$type}->form_template_init = $ranking_registration_team_event;
                $data_result['message']->{$type}->form_template = $ranking_registration_team_form;
                break;
              case 23: //player_watchlist managed sub form
                if ( empty($ranking_registration_player_init) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($ranking_registration_player_form) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($group_registration_player_form_min) ) die('<div class="error"><h4>Cannot find extension file. GrPl Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($player_registration_form_min) ) die('<div class="error"><h4>Cannot find extension file. Pl Min.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($player_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Pl Res.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($player_registration_input_item) ) die('<div class="error"><h4>Cannot find extension file. Pl Inp.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                
                if( !isset($data_result['message']->player_names) ) $data_result['message']->player_names = (object) array();

                $data_result['message']->{$type}->form_template_init = $ranking_registration_player_event;
                $data_result['message']->{$type}->form_template = $ranking_registration_player_form;
                break;
              default: // rosters_teams and default
                if ( empty($roster_registration_init_club_teams) ) die('<div class="error"><h4>Cannot find extension file. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                if ( empty($roster_registration_form_basic) ) die('<div class="error"><h4>Cannot find extension file.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
                $data_result['message']->{$type}->form_template_init = $roster_registration_init_club_teams;
                $data_result['message']->{$type}->form_template = $roster_registration_form_basic;
            }

						//load all the required templates into a tree to send to the user
            $data_result['message']->{$type}->form_template_result = $roster_registration_result;
            $data_result['message']->club_names->form_template_full = $club_registration_form_full;
            $data_result['message']->club_names->form_template_result = $club_registration_result;
            $data_result['message']->team_names->form_template_min = $team_registration_form_min;
            $data_result['message']->team_names->form_template_result = $team_registration_result;
            $data_result['message']->coach_names->form_template_min = $coach_registration_form_min;
            $data_result['message']->coach_names->form_template_result = $coach_registration_result;
            $data_result['message']->player_names->form_template_min = $player_registration_form_min;
            $data_result['message']->player_names->form_template_result = $player_registration_result;
            $data_result['message']->player_names->form_template_input_item = $player_registration_input_item;
						//set the styles flag

						$data_result['style'] = empty($data_set[$type]['stop_style']) ? true : false;
						break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
            if( !empty($ids) ) {
  						foreach( $ids as $event_dex => $event_val ) $data_result['message']->{$type}->{$event_val} = g365_truncate_data( g365_get_events($event_val, null, 'id, name, short_name, eventtime'), $type_key, $access_level );
            } else {
              //search by org, eventtime
              if( (empty($data_set[$type]['contributions']['club_id']) || intval($data_set[$type]['contributions']['club_id']) == 0) && (empty($data_set[$type]['contributions']['eventtime']) || intval($data_set[$type]['contributions']['eventtime']) == 0) ) die('<div class="error">Need Club ID or Event Time to retrieve related events.</div>');
              //send appropriate data to get event info
              $event_result = g365_get_events(null, (( !empty($data_set[$type]['contributions']['club_id']) && intval($data_set[$type]['contributions']['club_id']) > 0 ) ? $data_set[$type]['contributions']['club_id'] : null), 'id, name, short_name, eventtime', (( !empty($data_set[$type]['contributions']['eventtime']) && intval($data_set[$type]['contributions']['eventtime']) > 0 ) ? $data_set[$type]['contributions']['eventtime'] : null) );
              foreach( $event_result as $ev_dex => $ev_val ) $data_result['message']->{$type}->{$ev_val} = g365_truncate_data( $ev_data, $type_key, $access_level );
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//sanitize and pre-process incoming data
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
							//let's assign an id if we don't have one. Presumabily it's a new user
							if( empty($new_data['id']) ) {
								$new_id = $wpdb->insert( $wpdb_coaches, array(
                  'first_name' => g365_process_data_point( 'first_name', $new_data['data']['first_name']),
                  'last_name' => g365_process_data_point( 'last_name', $new_data['data']['last_name']),
                  'nickname' => g365_process_data_point( 'nickname', $new_data['data']['first_name'] . '-' . $new_data['data']['last_name'])
                ) );
								if( $new_id === false ) {
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => g365_output_db_error('Failed to generate new id.'),
                    'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                  );
                  continue;
                }
								$new_data['id'] = $wpdb->insert_id;
                $new_data['wrapper_id'] = $form_group; 
							}
              //add the id into the dataset
              $new_data['data']['id'] = $new_data['id'];
							//build sql string for this record
							$sql_prepare_query = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array();
							//process each data point and add to the query string
							foreach( $new_data['data'] as $data_name => &$data_value ) {
								$data_value = g365_process_data_point( $data_name, $data_value);
								if( $data_name == 'profile_img_data' ) {
									$profile_name = g365_process_data_point( 'profile_img', g365_process_data_point( 'nickname', $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name'] ) . '_' . $new_data['data']['id'] . '.jpg' );
									$uploads_url = wp_upload_dir();
									$profile_url = $uploads_url['basedir'] . '/coach-profiles/' . $profile_name;
									$file_size = file_put_contents( $profile_url, base64_decode( $data_value ) );
									if( $file_size === false ) {
										$new_data['data']['profile_img_error'] = 'Write error.';
										continue;
									}
									$sql_prepare_query .= ", profile_img = '$profile_name'";
									$new_data['data']['profile_img'] = $profile_name;
									unset($new_data['data']['profile_img_data']);
									continue;
								}
								if( $data_name == 'instagram' || $data_name == 'twitter' || $data_name == 'facebook' || $data_name == 'snapchat' ) {
									if( $data_value === 'null' ) {
										$sql_prepare_query .= ", social = JSON_REMOVE(social, '$." . ucfirst($data_name) . "')";
									} else {
										$sql_prepare_query .= ", social = JSON_SET(COALESCE(social, '{}'), '$." . ucfirst($data_name) . "', '$data_value')";
									}
									continue;
								}
                $new_data['data_processed']->{$data_name} = $data_value;
								$data_value = (( $data_value == 'null' || (is_numeric($data_value) && is_int(intval($data_value))) ) ? $data_value :  "'" . esc_sql($data_value) . "'");
								$sql_prepare_query .= ", $data_name = $data_value";
							}
							$data_result['message']->{$type}->{$new_data['id']} = g365_truncate_data( $new_data['data_processed'], $type_key, $access_level );
              if( !empty($new_data['wrapper_id']) ) $data_result['message']->{$type}->{$new_data['id']}['wrapper_id'] = $new_data['wrapper_id'];
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_coaches SET " . substr($sql_prepare_query, 2) . " WHERE id = $new_data[id];" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
						}
						break;
				}
				break;
      case 34: //game/player stats: stat_keep: record game and player stats, stand alone
//         print_r($data_set[$type]);
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
						if(
              !empty($data_result['message']->{$type}->form_template_init) &&
              !empty($data_result['message']->{$type}->form_template_result) &&
              !empty($data_result['message']->{$type}->form_template) &&
              !empty($data_result['message']->{$type}->form_template_pl)
            ) break;
						$stat_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_stat_registration_form.php';
						include_once $stat_template_file;
						if ( empty($stat_registration_init_game) ) die('<div class="error"><h4>Cannot find extension file. Init</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($stat_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Result.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($stat_registration_game_form) ) die('<div class="error"><h4>Cannot find extension file. Form.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($stat_registration_game_pl) ) die('<div class="error"><h4>Cannot find extension file. Game Pl.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            
            if( !isset($data_result['message']->{$type}) ) $data_result['message']->{$type} = (object) array();
            $data_result['message']->{$type}->additional_parts = "stat_";
            $data_result['message']->{$type}->form_template_init = $stat_registration_init_game;
						$data_result['message']->{$type}->form_template_result = $stat_registration_result;
            $data_result['message']->{$type}->form_template = $stat_registration_game_form;
            $data_result['message']->home_players = new \stdClass();
            $data_result['message']->away_players = new \stdClass();
            $data_result['message']->home_players->form_template_input_item = $stat_registration_game_pl;
            $data_result['message']->away_players->form_template_input_item = $stat_registration_game_pl;

//             if( !empty($data_set[$type]['preset']) ) {
//               foreach($data_set[$type]['preset'] as $dex => $preset_pairs) {
//                 $preset = explode('::', $preset_pairs);
//                 switch( $preset[0] ) {
//                   case 'event_id':
//                   case 'event_id_pm':
//                     $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] } = g365_truncate_data(g365_get_event_data( $preset[1], true), 29, 0);
//                     $data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }['current_birth_years'] = $curr_years;
//                     if( empty($data_result['message']->{$type}->{ $preset[0] . '_' . $preset[1] }) ) die( '<div class="error"><h4>Cannot find required event data. Init.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>' );
//                     break;
//                 }
//               }
//             }
            //set the styles flag
						$data_result['style'] = (empty($data_set[$type]['stop_style'])) ? true : false;
            if( empty($data_set[$type]['ids']) ) break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
            //if we have ids, look up the info and send it back
            
//             $data_result['message']->{$type} = $data_set;
//             echo json_encode($data_result);
//             die();

            
            if( !empty($ids) ) {
              ///get each id
  						foreach( $ids as $game_dex => $game_val ){ 
                //pull the stat data
                $game_data_pull = g365_get_game( $game_val );
                //add in player data
                if( !empty($game_data_pull->home_players_data) ) { 
                  foreach($game_data_pull->home_players_data as $id => $data){
                    if(empty($data->pts)){
                      $data->pts = 0;
                    }
                    if(empty($data->three_pt)){
                      $data->three_pt = 0;
                    }
                    if(empty($data->ast)){
                      $data->ast = 0;
                    }
                    if(empty($data->rbs)){
                      $data->rbs = 0;
                    }
                    if(empty($data->stl)){
                      $data->stl = 0;
                    }
                    if(empty($data->blk)){
                      $data->blk = 0;
                    }
                    $data_result['message']->home_players->{$id} = $data;
                  }
                  unset($game_data_pull->home_players_data);
                }
                if( !empty($game_data_pull->away_players_data) ) {
                  foreach($game_data_pull->away_players_data as $id => $data){
                    if(empty($data->pts)){
                      $data->pts = 0;
                    }
                    if(empty($data->three_pt)){
                      $data->three_pt = 0;
                    }
                    if(empty($data->ast)){
                      $data->ast = 0;
                    }
                    if(empty($data->rbs)){
                      $data->rbs = 0;
                    }
                    if(empty($data->stl)){
                      $data->stl = 0;
                    }
                    if(empty($data->blk)){
                      $data->blk = 0;
                    }
                    $data_result['message']->away_players->{$id} = $data; 
                  } 
                  unset($game_data_pull->away_players_data);
                }
                //if we have event specific templates to assemble, start
                if( is_object($game_data_pull) && isset($game_data_pull->event) && isset($game_data_pull->event_stats) ) {
                  //set the holder in the export object
                  if( !isset($data_result['message']->form) ) $data_result['message']->form = (object) array();
                  if( !isset($data_result['message']->form->{$type}) ) $data_result['message']->form->{$type} = (object) array();
                  //import the templates
                  $stat_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_stat_registration_form.php';
                  include_once $stat_template_file;
                  //if we have valid data create event template
                  if( 
                    isset($stat_registration_form_min_pl_ev_admin__input) &&
                    isset($stat_registration_form_min_pl_ev_admin__select) &&
                    isset($stat_registration_form_min_pl_ev_admin__select_option) &&
                    isset($stat_registration_form_min_pl_ev_admin__textarea)
                  ) {
                    
                    //process stats if we have it and it hasn't been built already
                    //set template var
                    $stat_template = '';
                    //parse the event_stats, then loop through to build the template
                    foreach( json_decode($game_data_pull->event_stats) as $dex => $stat_attributes  ) {
                      //add set type to attributes
                      $stat_attributes->type = 'stats';
                      $stat_attributes->value = '{{stat_' . $stat_attributes->handle . '_val}}';
                      $current_element = ${'stat_registration_form_min_pl_ev_admin__' . $stat_attributes->element};
                      foreach( $stat_attributes as $attr_name => $attr_val ) $current_element =  str_replace('{{' . $attr_name . '}}', $attr_val, $current_element);
                      $stat_template .= preg_replace('/\{\{(.+?[^_val])\}\}/', '', $current_element);
//                           {
//                             "element" : "", //input, select, select_option, textarea
//                             "name" : "", //plain stat name: '3/4 Court Sprint' or sentence/question 'Favorite shoe?'
//                             "handle" : "", //lowercase, no space, stat id: '3_4_court_sprint', auto generate from name
//                             "type" : "", //stat or trend for now
//                             "placeholder" : "", //placeholder for input or textarea or default for select, radio, etc..
//                             "options" : {"name" : "value"}, //if it's a select or radio, etc..just name value pairs
//                           },
                    }
                    //attach the template to the main export variable
                    $data_result['message']->form->{$type}->{ 'stat_' . $game_data_pull->event } = $stat_template;

                  } else {
                    if( isset($game_data_pull->event_stats) ) $data_result['message']->form->{$type}->{ 'stat_' . $game_data_pull->event } = 'Missing stat templates file.';
                  }
                }
                //add the field-set-id
                $data_result['message']->{$type}->{$game_val} = g365_truncate_data( $game_data_pull, $type_key, $access_level );
              }
            } else {
              $data_result['message']->{$type} = '<p class="error">No data found for these parameters.</p>';
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//sanitize and pre-process incoming data
            
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
							//we need an existing game record to proceed
              
							//build sql string for this record
							$sql_prepare_query_game = '';
              //compiled object to send back
              $new_data['data_processed'] = (object) array( 'game' => (object) array(), 'stats' => (object) array() );
              //required vars

//                 $data_result['message']->{$type} = $new_data;
//                 echo json_encode($data_result);
//                 die();

              if( empty($new_data['id']) ) die('Error, needs game id to proceed.');
              if( empty($new_data['data']['event_id']) ) die('Error, needs event id to proceed.');
              //if we ever needed to create games in this manner
              if( $new_data['id'] === 'new' ) { //broken
								$new_id = $wpdb->insert( $wpdb_games, array(
                  'first_name' => g365_process_data_point( 'first_name', $new_data['data']['first_name']),
                  'last_name' => g365_process_data_point( 'last_name', $new_data['data']['last_name'])
                ) );
								if( $new_id === false ) {
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => g365_output_db_error('Failed to generate new id.'),
                    'element_title' => $new_data['data']['first_name'] . ' ' . $new_data['data']['last_name']
                  );
                  continue;
                }
								$new_data['id'] = $wpdb->insert_id;
                $new_data['wrapper_id'] = $form_group; 
							} //end add new game

              //process incoming data
              $new_data['data']['event_id'] = g365_process_data_point( 'id', $new_data['data']['event_id']);
              //add id to the data set
              $new_data['data_processed']->game->id = g365_process_data_point( 'id', $new_data['id']);
              $new_data['data_processed']->game->home_team_score = 0;
              $new_data['data_processed']->game->away_team_score = 0;

							//build sql string for the player stats
              $sql_prepare_query_players_to_remove = [];
							$sql_prepare_query_players = [];
              //cache dup var
              $to_cache_dump = array();
							//process each data point and add to the query string
							foreach( $new_data['data']['players'] as $pl_id => &$pl_data ) {
                //sanitize data for player
								$pl_data['player'] = g365_process_data_point( 'id', $pl_data['player']);
                //add them to the list of players that need to have the cache purged
                $to_cache_dump[] = $pl_data['player'];
                //stats default
                $stats_query_string = "{}";
                //process the stats
                if( !empty( $pl_data['stats'] ) ) {
                  $to_add = [];
    							foreach( $pl_data['stats'] as $stat_name => &$stat_data ) {
                    //if it's not set, delete it
                    if( $stat_data !== '' ) {
                      $stat_data = esc_sql($stat_data);
                      $to_add[] = '"' . $stat_name . '": ' . (( is_numeric($stat_data) ) ? floatval($stat_data) : '"' . $stat_data . '"');
                      //add total game score from player points
                      if( $stat_name === 'pts' ) {
                        if( $pl_data['homeaway'] === 'home' ) {
                          $new_data['data_processed']->game->home_team_score += (int)g365_process_data_point( 'id', $stat_data);
                        } elseif ( $pl_data['homeaway'] === 'away' ) {
                          $new_data['data_processed']->game->away_team_score += (int)g365_process_data_point( 'id', $stat_data);
                        }
                      }
                    } else { //if it's set to '' then we purposely want to delete the value
                      $sql_prepare_query_players_to_remove[$stat_name][] = $pl_data['player'];
                    }
                  }
                  if( !empty($to_add) ) $stats_query_string = "{" . implode(', ', $to_add) . "}";
                }
                $sql_prepare_query_players[] = '(' . $new_data['data']['event_id'] . ', ' . $pl_data['player'] . ', ' . $new_data['data_processed']->game->id . ", '$stats_query_string' )";
              }
              //if we need to truncate the data we send back
              $data_result['message']->{$type}->{$new_data['id']} = [];//g365_truncate_data( $new_data['data_processed']->game, $type_key, $access_level );
              //recursively add the id
              $data_result['message']->{$type}->{$new_data['id']}['id'] = $new_data['id'];
              //create the element name to result display
              $data_result['message']->{$type}->{$new_data['id']}['element_title'] = 'Game ' . $new_data['data_processed']->game->id;

              //process removals first
              if( !empty($sql_prepare_query_players_to_remove) ) foreach( $sql_prepare_query_players_to_remove as $stat_to_remove => $pl_ids ) {
                //run the stat remove query
                $new_data['data_processed']->stats->players_clean .= ( $wpdb->query( "UPDATE $wpdb_stats SET stats=JSON_REMOVE(stats, '$.$stat_to_remove') WHERE event=" . $new_data['data']['event_id'] . " AND player IN (" . implode(', ', $pl_ids) . ") AND game=" . $new_data['data_processed']->game->id . ";" ) === false ) ? g365_output_db_error("Player $stat_to_remove cleaning error. ") : "Players $stat_to_remove cleaned successfully. ";
              }
              //add player stats
              if( !empty($sql_prepare_query_players) ) {
                //run the stat update/create query
                $new_data['data_processed']->stats->players = ( $wpdb->query( "INSERT INTO $wpdb_stats (event, player, game, stats) VALUES " . implode(', ', $sql_prepare_query_players) . " ON DUPLICATE KEY UPDATE stats=JSON_MERGE_PATCH(stats, VALUES(stats));" ) === false ) ? g365_output_db_error('Player update error.') : "Players updated successfully.";
//                 $data_result['message']->{$type}->{$new_data['id']}['players_stats'] = ( $wpdb->query( "INSERT INTO $wpdb_stats (event, player, game, stats) VALUES " . implode(', ', $sql_prepare_query_players) . " ON DUPLICATE KEY UPDATE stats=JSON_MERGE_PATCH(stats, VALUES(stats));" ) === false ) ? g365_output_db_error('Player update error.') : "Players updated successfully.";
                // Check if player zero is added
                global $wpdb; $dbs = json_decode(dbs());
                $wpdb_rosters = $wpdb->g365_rosters;
                $home_pl_zero = '11000'; // Home player zero ID
                $away_pl_zero = '11001'; // Away player zero ID
                $hm_json_obj = '{"'.$home_pl_zero.'":{"j_num": 0}}';
                $aw_json_obj = '{"'.$away_pl_zero.'":{"j_num": 0}}';
                $event_id = $new_data['data']['event_id'];
                $get_org_id = $wpdb->get_results("SELECT org FROM $dbs->events WHERE id = $event_id");
                $get_org_id = $get_org_id[0]->org;
                $proc_game_data = g365_get_game( $new_data['data_processed']->game->id );
                foreach( $sql_prepare_query_players as $key => $check_pl_zero ){
                  $check_pl_zero = explode(',',$sql_prepare_query_players[$key]); 
                  if( in_array($home_pl_zero, $check_pl_zero) ){
                    $wpdb->query("UPDATE $wpdb_rosters SET players = JSON_MERGE_PATCH(players, '$hm_json_obj') WHERE id = $proc_game_data->home_roster_id");
                  }
                  if(in_array($away_pl_zero, $check_pl_zero) ){
                    $wpdb->query("UPDATE $wpdb_rosters SET players = JSON_MERGE_PATCH(players, '$aw_json_obj') WHERE id = $proc_game_data->away_roster_id");
                  }
                }
              }
              //prepare all the game data for the database
              $sql_prepare_query_game = ', home_team_score = ' . $new_data['data_processed']->game->home_team_score . ', away_team_score = ' . $new_data['data_processed']->game->away_team_score;
              
              //run the stat update/create query
              $data_result['message']->{$type}->{$new_data['id']}['message'] = ( $wpdb->query( "UPDATE $wpdb_games SET " . substr($sql_prepare_query_game, 2) . " WHERE id = " . $new_data['id'] . ";" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Database update error.') : 'Data update error.') : "Data updated successfully.";
              $exposure_game_id = $wpdb->get_var( "SELECT exposure_game_id FROM $wpdb_games WHERE id = " . $new_data['id'] . ';' );
              if($get_org_id === '7474'){
                $exposure_result = post_game_data_to_SCIBCA_exposure(
                'api/v1/games',
                $exposure_game_id,
                $wpdb->get_var( "SELECT schedule_link->>'$.exposure' FROM $wpdb_events WHERE id = " . $new_data['data']['event_id'] . ';' ), 
                $postJSONData = '{
                  "Id": '. $exposure_game_id .',
                  "HomeScore": "'. $new_data['data_processed']->game->home_team_score .'",
                  "AwayScore": "'. $new_data['data_processed']->game->away_team_score .'",
                  "HomeTeamScore": "'. $new_data['data_processed']->game->home_team_score .'",
                  "AwayTeamScore": "'. $new_data['data_processed']->game->away_team_score .'"
                }'
              );
              }else{
                $exposure_result = post_game_data_to_exposure(
                  'api/v1/games',
                  $exposure_game_id,
                  $wpdb->get_var( "SELECT schedule_link->>'$.exposure' FROM $wpdb_events WHERE id = " . $new_data['data']['event_id'] . ';' ), 
                  $postJSONData = '{
                    "Id": '. $exposure_game_id .',
                    "HomeScore": "'. $new_data['data_processed']->game->home_team_score .'",
                    "AwayScore": "'. $new_data['data_processed']->game->away_team_score .'",
                    "HomeTeamScore": "'. $new_data['data_processed']->game->home_team_score .'",
                    "AwayTeamScore": "'. $new_data['data_processed']->game->away_team_score .'"
                  }'
                );
              }
              $data_result['message']->{$type}->{$new_data['id']}['expo'] = $exposure_result['response']['code'];
              //purge the cache from player pages.
              g365_pages_cache_purge( $to_cache_dump );
//               $data_result['message']->{$type}->{$new_data['id']}['redirect'] = "account/stat_keep/?ev_id=$event_id";
              $data_result['message']->{$type}->{$new_data['id']}['redirect'] = "reload";
              
//               $data_result['message']->{$type} =
//               echo json_encode($data_result);
//               die();
              
						}
						break;
				}
				break;
			case 36: //claiming
				switch( $data_set[$type]['proc_type'] ) {
					case 'get_form':
						if(
              !empty($data_result['message']->{$type}->form_template_init) &&
              !empty($data_result['message']->{$type}->form_template_result)
            ) break;
						$claim_template_file = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_claim_registration_form.php';
						include_once $claim_template_file;
						if ( empty($claim_registration_init) ) die('<div class="error"><h4>Cannot find extension file. Init</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if ( empty($claim_registration_result) ) die('<div class="error"><h4>Cannot find extension file. Result.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            $data_result['message']->{$type}->form_template_init = $claim_registration_init;
            $data_result['message']->{$type}->form_template_result = $claim_registration_result;
            //set the styles flag
						$data_result['style'] = (empty($data_set[$type]['stop_style'])) ? true : false;
            if( empty($data_set[$type]['ids']) ) break;
					case 'get_data':
						//sanitize incoming ids
						$ids = g365_validate_ids($data_set[$type]['ids']);
            if( !empty($ids) ) {
  						foreach( $ids as $claim_dex => $claim_val ) $data_result['message']->{$type}->{$claim_val} = g365_truncate_data( g365_get_claims($claim_val), $type_key, $access_level );
            } else {
              $data_result['message']->{$type} = '<p class="error">No claim data found for these parameters.</p>';
            }
						break;
					case 'proc_data':
						//check for incoming data
						if( empty($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot find new data to add/update.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						//parse the javascript form serialization 
						parse_str(stripslashes($data_set[$type]['form_data']), $data_set[$type]['form_data']);
						if( !is_array($data_set[$type]['form_data']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            
//               $data_result['message']->{$type} = $data_set[$type];
//               echo json_encode($data_result);
//               die();
            
						//sanitize and pre-process incoming data
            $domain_keys = g365_return_keys( 'keys_by_domain' );
            //basic variables for processing
						foreach( $data_set[$type]['form_data'] as $form_group => &$new_data ) {
              $data_id = g365_process_data_point( 'id', $new_data['id'] );
              $data_type = g365_process_data_point( 'id', $new_data['type'] );
              $data_name = g365_process_data_point( 'name', $new_data['name'] );
              $data_target = g365_process_data_point( 'id', $new_data['target'] );
              $data_site_key = g365_process_data_point( 'name', $domain_key['site_key'] );
              $data_email = g365_process_data_point( 'email', $new_data['email'] );
              $data_status = g365_process_data_point( 'id', $new_data['status'] );
							//let's assign an id if we don't have one. Presumabily it's a new user
							if( empty($data_id) ) {
                $site_key = substr($new_data['site_key'], 0, 3 );
                //see if we have a site record for the creating site user by ip
                $domain_key = array_filter( $domain_keys, function($data) use ($site_key){ return $data[ 'id' ] === $site_key; });
								if( empty($domain_key) ) {
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => 'Could not find target site.',
                    'element_title' => $site_key
                  );
                  continue;
                }
								$new_id = $wpdb->insert( $wpdb_claims, array(
                  'type' => $data_type,
                  'target' => $data_target,
                  'site_key' => $data_site_key,
                  'email' => $data_email,
                  'status' => 2
                ) );
                //is there already a record
                if( $new_id === false ) {
  								$data_id = $wpdb->get_var( "SELECT id FROM $wpdb_claims where type = " . $data_type . " AND target = " . $data_target . " AND site_key LIKE '" . $data_site_key . "'" . " AND email LIKE '" . $data_email . "'" );
                  $data_result['message']->{$type}->{$form_group} = array(
                    'message'       => 'Claim record previously submitted.',
                    'element_title' => $data_name
                  );
                } else {
  								$data_id = $wpdb->insert_id;
                }
                $new_data['wrapper_id'] = $form_group;
                //get data owner reference
                $data_owner = $wpdb->get_row( 'SELECT name, access FROM ' . (($data_type == 1) ? $wpdb_players : $wpdb_org) . ' WHERE  id = ' . $data_target );
                if( $data_owner === false ) {
                  $data_result['message']->{$type}->{$data_id} = array(
                    'message'       => 'No data for supplied id. Please contact your representative.',
                    'element_title' => $data_name
                  );
                  continue;
                }
                $data_name = $data_owner->name;
                $data_owner = json_decode($data_owner->access);
                //just grab the first user id, presumably they are the master owner
                $data_owner = $data_owner->{$data_site_key}[0];
                //send notice to owner site
                $owner_notice_result = g365_data_sender( array(1 => $data_site_key), 'notify', array($data_owner, $data_name, $data_email, $data_id) );
                //check the outcomes and create a status update to send back.
                $data_result['message']->{$type}->{$data_id} = array(
                  'message'       => $owner_notice_result,
                  'element_title' => $data_name
                );
							} else {
                //pull the record to check against before we process
                $claim_record = $wpdb->get_results(
                  "SELECT cl.*,
                  CASE
                  WHEN cl.type = 1 THEN pl.name
                  WHEN cl.type = 2 THEN org.name
                  END as name,
                  CASE
                  WHEN cl.type = 1 THEN pl.access
                  WHEN cl.type = 2 THEN org.access
                  END as access
                  FROM $wpdb_claims AS cl
                  LEFT JOIN $wpdb_players AS pl ON cl.target=pl.id
                  LEFT JOIN $wpdb_orgs AS org ON cl.target=org.id
                  WHERE cl.id = " . $data_id . ';'
                );
                //make sure we have the target record
                if( empty($claim_record) ) {
                  $data_result['message']->{$type}->{$data_id} = array(
                    'message'       => 'Cannot find claim record. Please contact your representative.',
                    'element_title' => $data_name
                  );
                  continue;
                } else {
                  $claim_record = $claim_record[0];
                }
                //if we don't have a name add it from the db
                if( empty($data_name) || $data_name === 'null' ) $data_name = $claim_record->name;
                $g365_auth_result = 'Nothing yet.';
                //we set this variable in the javascript when the admin submit buttons are clicked
                if( $new_data['sub_button'] !== 'update_record' ) {
                  //to grant access...first user is owner
                  $claim_record->access = json_decode($claim_record->access);
                  $owner_id = $claim_record->access->{$claim_record->site_key}[0];

                  //try to finish up the claim approval process
                  $g365_auth_result = g365_authorize_claim_local( $claim_record->id, $claim_record->email, $owner_id, $claim_record->site_key );
                  $g365_auth_result = ( gettype($g365_auth_result) === 'object' ) ? $g365_auth_result->message : $g365_auth_result;
                } else {
                  //update the claim record
                  $g365_auth_result = $wpdb->update( 
                    $wpdb_claims,
                    array(
                      'type' => ((empty($data_type) || $data_type === 'null') ? $claim_record->type : $data_type),
                      'target' => ((empty($data_target) || $data_target === 'null') ? $claim_record->target : $data_target),
                      'site_key' => ((empty($data_site_key)) ? $claim_record->site_key : $data_site_key),
                      'email' => ((empty($data_email)) ? $claim_record->email : $data_email),
                      'status' => ((empty($data_status) || $data_status === 'null') ? $claim_record->status : $data_status)
                    ),
                    array( 'id' => $data_id ), 
                    array( 
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                        '%d'
                    ),
                    array( '%d' ) 
                  );
                  $g365_auth_result = ( $g365_auth_result === false ) ? 'Failed to Update' : 'Record updated successfully.';
                }
                $g365_auth_result = ((is_array($g365_auth_result)) ? $g365_auth_result[0] : $g365_auth_result);
                $data_result['message']->{$type}->{$data_id} = array(
                  'message'       => $g365_auth_result,
                  'element_title' => $data_name
                );
              }
//               $data_result['message']->{$type}->{$data_id}['redirect'] = "reload";
						}

            //do we have an id
            //if we have an id authorize the claim
            
            //query the database to get the record
            //is it local or is it remote
            //if local use g365_authorize_claim_local else g365_authorize_claim
            
            //if we don't have id, create the claim record
            //make sure we have all the right varaibles
            //minimum data: target data type, tartget data id, requesting site, requesting user email
            //admin data: status
            
            //return result
            

						break;
				}
				break;
        
        
        case 50:
        switch( $data_set[$type]['proc_type'] ) {
					case 'delete_data':
            global $wpdb;
            $wpdb_claims = $wpdb->g365_claims;
						//check for incoming data
						if( empty($data_set[$type]['ids']) ) die('<div class="error"><h4>Cannot find ids to delete.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
						if( !is_array($data_set[$type]['ids']) ) die('<div class="error"><h4>Cannot parse new data.</h4><p>Please drop us a line to notify the system admin of your experience: https://grassroots.com/contact/</p></div>');
            
            //check for admin
            if( current_user_can( 'administrator' ) ) {
              //check for deletability
              //do the deleteing
              //make deleted claims type 8
              $result = '';
              foreach( $data_set[$type]['ids'] as $id ) {
                $result .= "Claim $id removal: " . (($wpdb->update( $wpdb_claims, array( 'type' => 8 ), array('id' => $id))) ? 'success' : 'fail');
              }
              $data_result['message']->{$type} = $result;
            } else {
              $data_result['message']->{$type} = "Insufficient permissions to remove record.";
            }

            
//               $data_result['message']->{$type} = $data_set[$type];
//               echo json_encode($data_result);
//               die();
            
						break;
					
        }
      break;
        
		}
	}
  if(empty($_SERVER['HTTP_ORIGIN'])){
  $http_origin = "https://".$_SERVER['HTTP_HOST'];
  }else{
    $http_origin = $_SERVER['HTTP_ORIGIN'];
  }
	if( !empty($data_result['style']) && $data_result['style'] === true && in_array( $http_origin, array("https://grassroots365.com", "https://dev.grassroots365.com", "https://sportspassports.com", "https://dev.sportspassports.com") ) === false  && $_POST['g365_settings']['styles'] !== 'false' ) {
		$file_style = plugin_dir_path( __FILE__ ) . 'js/form-templates/g365_form_styles.php';
		include_once $file_style;
		if ( empty($g365_form_styles) ) die('<div class="error"><h4>Cannot find style file.</h4></p>Please drop us a line to notify the system admin of your experience: https://sportspassports.com/contact/</p></div>');
		$data_result['style'] = $g365_form_styles;
	} else {
    unset( $data_result['style'] );
  }
  if( !empty($data_owner_reference) ) $data_result['owner_reg'] = g365_data_sender($access_level, 'claim', $data_owner_reference);
//   $data_result['owner_ref'] = json_encode($data_owner_reference);
//   $data_result['access_level'] = json_encode($access_level);
	echo json_encode($data_result);
	die();
}

function g365_template_construction( $ele_data ) {
	$ogp_admin_form_elements = array(
		'input' 	=> array(
			'template' => '<tr>
					<th>
						<label for="{{element_tag}}">{{element_title}}</label>
						{{element_description}}
					</th>
					<td>
						<input type="{{element_type}}" {{element_limits}} {{element_data}} name="{{element_tag}}" id="{{element_tag}}" value="{{element_value}}">
					</td>
				</tr>',
			'vars'	=> array('tag', 'title', 'description', 'type', 'limits', 'data', 'value')
		),
		'radio' 	=> array(
			'template' => '<tr>
					<th>
						<label for="{{element_tag}}">{{element_title}}</label>
						{{element_description}}
					</th>
					<td>{{element_options}}</td>
				</tr>',
      'template_option' => '<input type="{{element_type}}" {{element_chosen}} name="{{element_tag}}" value="{{element_option}}"> {{element_option_name}} <br />',
			'vars'	=> array('options', 'tag', 'title', 'description', 'type', 'value')
		),
		'rotator' 	=> array(
			'template' => '<figure class="">
					<a href="{{element_link}}" title="{{element_title}} Link" target="_blank">
						<img class="orbit-image" src="{{element_img}}" title="{{element_title}} Image" alt="Click for {{element_title}} Event Information">
					</a>
				</figure>',
			'vars'	=> array('title', 'link', 'img')
		)
	);
	if( empty($ele_data) ) return false;
	$ele = $ogp_admin_form_elements[$ele_data['element_type']]['template'];
	foreach( $ogp_admin_form_elements[$ele_data['element_type']]['vars'] as $dex => $var ) {
    if( $var === 'options' ) {
      $all_options = '';
      $option_ele = $ogp_admin_form_elements[$ele_data['element_type']]['template_option'];
      foreach( $ele_data[$var] as $opt_dex => $opt_details ) {
        $all_options .= str_replace( '{{element_option}}', $opt_details['option'], str_replace( '{{element_option_name}}', $opt_details['option_name'], str_replace( '{{element_chosen}}', (( $opt_details['option'] === $ele_data['value'] ) ? $ele_data['chosen'] : '' ), $option_ele)));
      }
      $ele_data[$var] = $all_options;
    }
		$ele = str_replace( ('{{element_' . $var . '}}'), $ele_data[$var], $ele);
	}
	return $ele;
}

//processes individual incoming datapoints base on typed name key
function g365_process_data_point( $name, $point ) {
	if( empty($name) ) return false;
	$data_process = (is_string($point)) ? trim($point) : $point;
	switch($name) {
		case 'state':
			$data_process = strtoupper(substr($data_process, 0, 2));
		case 'city':
		case 'address':
		case 'country':
		case 'first_name':
		case 'last_name':
		case 'club_abb':
		case 'club_name':
		case 'team_name':
		case 'school_name':
		case 'school':
    case 'position_name':
    case 'team_type':
    case 'jersey_size':
    case 'bcert_resub':
    case 'recard_resub':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : sanitize_text_field($data_process);
			break;
		case 'name':
		case 'locations':
		case 'short_locations':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? '' : sanitize_text_field($data_process);
			break;
		case 'text':
		case 'notes':
		case 'tagline':
		case 'description':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : sanitize_textarea_field($data_process);
			break;
		case 'videos':
		case 'instagram':
		case 'twitter':
		case 'facebook':
		case 'snapchat':
			if( is_array($data_process) ) {
				foreach( $data_process as $dex => &$val ) $val = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : filter_var($val, FILTER_SANITIZE_URL);
			} else {
				$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : filter_var($data_process, FILTER_SANITIZE_URL);
			}
			break;
		case 'players':
    case 'events':
			if( !is_array($data_process) ) $data_process = json_decode($data_process);
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : array_map( function($id_val){ return ( ( is_array($id_val) ) ? array_map( function($sub_id_val){ return intval($sub_id_val); }, $id_val) : intval($id_val)); }, $data_process);
// 			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : array_map( function($id_val){ return gettype($id_val); }, $data_process);
			break;
		case 'social':
			break;
		case 'profile_img':
    case 'bcert_img:':
    case 'recard_img':
		case 'event_profile_img':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : sanitize_file_name($data_process);
			break;
		case 'url':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : esc_url_raw($data_process);
			break;
		case 'club_url':
		case 'school_url':
		case 'nickname':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : strtolower(preg_replace("([^a-zA-Z])", "-", preg_replace("([^a-zA-Z -])", "", $data_process)));
			break;
		case 'folder':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : strtolower(preg_replace("([^a-zA-Z0-9])", "-", preg_replace("([^a-zA-Z0-9 -])", "", $data_process)));
			break;
		case 'email':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : sanitize_email($data_process);
			break;
		case 'createtime':
		case 'updatetime':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : substr(preg_replace("([^0-9: -])", "", $data_process),0,18);
			break;
		case 'birthday':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : DateTime::createFromFormat('m-d-Y', $data_process)->format('Y-m-d');
			break;
		case 'phone':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : preg_replace("([^0-9-\(\) ])", "", $data_process);
			break;
		case 'gpa':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : number_format(substr($data_process,0,4), 2);
			break;
    case 'attendance':
      $data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : $data_process;
      break;
		case 'account_level':
		case 'enabled':
		case 'verified':
		case 'height_in':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : intval($data_process);
			break;
		case 'act':
		case 'sat':
		case 'height_ft':
		case 'grad_year':
		case 'weight':
		case 'position':
    case 'position_id':
		case 'zip':
		case 'id':
		case 'club_team':
		case 'club_id':
		case 'school_id':
		case 'org':
		case 'org_id':
		case 'team_id':
		case 'player_id':
		case 'event_id':
		case 'coach':
		case 'coach_id':
		case 'asst':
		case 'asst_id':
		case 'level':
      if($data_process !== 0) {
        $data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 'null' : intval($data_process);
        if( $data_process === 0 ) $data_process = 'null';
      }
			break;
		case 'player':
		case 'team':
		case 'event':
    case 'ranking':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? 0 : intval($data_process);
      break;
		case 'profile_img_data':
		case 'recard_img_data':
		case 'bcert_img_data':
			$data_process = ($data_process === null || $data_process === 'null') ? 'null' : ( ($data_process == '') ? '' : substr($data_process,strpos($data_process, ",") + 1) );
			break;
    case 'stat': // DD get passport purchase value in string date formate only
      $data_process = strval($point);
      break;
    case 'scope_access':
			$data_process = ($data_process == null || $data_process == '' || $data_process == 'null') ? '' : sanitize_text_field($data_process);
      break;
	}
	return $data_process;
}

//verify user access level
function g365_check_user_access( $access_level ) {
  $keys_by_domain = g365_return_keys('keys_by_domain');
  //if we are curretnly a logged in admin exit
  if(current_user_can('administrator') || current_user_can('front_editor')) return array( 2, $keys_by_domain[ site_url() ]['id'], get_current_user_id() );
  //if we are sending nothing exit
  if( $access_level === null || $access_level === '' ) return array( 0, null, null, null);
  //other wise parse the data and see where we land
  $auth_keys = base64_decode(substr($access_level, 6));
  //extract the user data
  $user_keys = explode( ':::', substr($auth_keys, strpos($auth_keys, ',') + 1) );
  //if we have the right keys process the user
  if( $keys_by_domain[ $user_keys[0] ]['trans_key'] === substr($auth_keys, 0, 34) && $keys_by_domain[ $user_keys[0] ]['trans_id'] === substr($auth_keys, 34, 29) ) {
    //if the user is an admin from another site set the access
    if( !empty($keys_by_domain[ $user_keys[0] ][ 'users' ][ $user_keys[1] ]) && count($user_keys) === 3 ) {
      wp_set_current_user( $keys_by_domain[ $user_keys[0] ][ 'users' ][ $user_keys[1] ] );
      return array( 2, $keys_by_domain[ $user_keys[0] ]['id'], $keys_by_domain[ $user_keys[0] ][ 'users' ][ $user_keys[1] ], $user_keys[2] );
    } else {
      return array( 1, $keys_by_domain[ $user_keys[0] ]['id'], ((is_numeric($user_keys[1])) ? intval($user_keys[1]) : $user_keys[1]), ((count($user_keys) === 3) ? $user_keys[2] : 'null' ) );
    }
  }
  return array( 0, null, null, null);
}


//get the eligibility from the level
function g365_age_level( $pl_date_time, $pl_date_now = null, $pl_time_zone = null, $level_proc = true ){
//   If current date is 9/1-12/31 and player DOB is 1/1-8/31, then subtract player birth year from current year and add 1
//   If current date is 1/1-8/31 and player DOB is 9/1-12/31, then subtract player birth year from current year and subtract 1
//   If current date is 9/1-12/31 and player DOB is 9/1-12/31, then subtract player birth year from current year
//   If current date is 1/1-8/31 and player DOB is 1/1-8/31, then subtract player birth year from current year
//   current late, player early = + 1 
//   current early, player late = -1
//   current late, player late = 0
//   current early, player early = 0
  
//   echo("<script>console.log('date_time: " . $pl_date_time . " ');</script>");
  
  //exit if we don't have a target date to process
  if( empty($pl_date_time) ) return 'Need target time to process';
  //set time zone if not provided
  if( empty($pl_time_zone) ) {
    $pl_time_zone = new DateTimeZone('America/Los_Angeles');
  } else {
    $pl_time_zone = new DateTimeZone($pl_time_zone);
  }
  //turn the supplied string into a real date
  $pl_date_time = new DateTime($pl_date_time, $pl_time_zone);
  //if we didn't get a target date supplied, assume it's today
  if( empty($pl_date_now) ) {
    $pl_date_now = new DateTime("now", $pl_time_zone);
  } else {
    $pl_date_now = new DateTime($pl_date_now, $pl_time_zone);
  }
  //do the first age calculation
  $ver_level = ( intval($pl_date_now->format('Y')) - intval($pl_date_time->format('Y')));
  //see if we need to modify the base number
  
//   echo("<script>console.log('date_time3: " . intval($pl_date_now->format('n')) . " ');</script>");
  if( intval($pl_date_now->format('n')) < 9 ) {
    if( intval($pl_date_time->format('n')) > 8 ) $ver_level -= 1;
  } else {
    if( intval($pl_date_time->format('n')) < 9 ) $ver_level += 1;
  }
//   echo("<script>console.log('date_time3: " . $ver_level . " ');</script>");
  //process the raw number into a level title
  if( $level_proc ) $ver_level = g365_level_key($ver_level);
//   echo("<script>console.log('date_time3: " . $ver_level . " ');</script>");
  return $ver_level;
}


//get the grade from the class
function g365_class_to_grade($class_of, $add_suffix = false) {
  if( empty($class_of) || !is_numeric($class_of) ) return 'N/A';
  $today = date("Y-m-d");
  $grade_string = (12 - (intval($class_of) - date('Y', strtotime($today)) - (( intval(date('n', strtotime($today))) < 9 ) ? 0 : 1 )));
  if( $add_suffix ) {
    switch( $grade_string ) {
      case '1':
        $grade_string .= '<sup>st</sup>';
        break;
      case '2':
        $grade_string .= '<sup>nd</sup>';
        break;
      case '3':
        $grade_string .= '<sup>rd</sup>';
        break;
      default:
        $grade_string .= '<sup>th</sup>';
        break;
    }
  }
  return $grade_string;
}

//obfusticate data based on account level
function g365_truncate_data( $data, $type, $access ) {
  
  if( gettype($data) === 'string' ) return $data;
  $g365_grade_key = g365_return_keys('g365_grade_key');
  //default access level is always 0
	$data_trunc = '';
  //double check permissions for users looking for higher access
  if( $access[0] === 1 && isset($data->access) ) 
    // if there isn't any access set, don't serve sensitive info
    if( empty($data->access) || $data->access === 'NULL' ) {
      $access[0] = 0;
    } else {
      //print_r($data->access);
      //if we have access info, check it against the requesting user
      //$data->access = json_decode($data->access);
      
      if( !empty($data->access->{$access[1]}) ) {
        if( !in_array( $access[2], $data->access->{$access[1]} ) ) $access[0] = 0;
      } else {
        $access[0] = 0;
      }
    }
  
    // Decode the "trends" JSON string
    $trends_data = (!isset($data->trends)) ? '' : json_decode($data->trends);
//     $trends_data = json_decode($data->trends);
//     echo("<script>console.log('hhhhhur print_r(" . $data->trends . ")  ');</script>");
//     echo("<script>console.log('hhhhhur print_r(" . $access[0] . ")  ');</script>");
  

  
  
	switch( $type ) {
		case 6:  //add/edit player stat
		case 13: //add/edit player stat
    case 14: //pl cert
    case 19: //add      player stat: pl_cert_sl: single input, stand alone
    case 15: //add/edit player stat: club_team managed, player to team
    case 16: //add/edit player stat: camps mananged
    case 43: //add/edit player stat: passport mananged
    case 48: //add/edit player stat: digital coaching package managed
    case 18: //add/edit player stat: training managed
    case 27: //player_event_admin: stand alone, single, event data add
    case 52: //hhh_player_event_admin: stand alone, single, event data add
    case 28: //edit player stat: cps_manager: cps role data manager, stand alone
    case 49: // Player photo
    case 55: //ss_player_event_admin: stand alone, single, event data add
    case 54: //add/edit player stat: player_event: stand alone stat scope scouting  
			switch( $access[0] ) {
				case 0:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $data->profile_img,
            "event_profile_img_url" => (!isset($data->event_profile_img)) ? '' : $data->event_profile_img,
            "city" => (!isset($data->city)) ? '' : $data->city,
            "club_abb" => (!isset($data->club_abb)) ? '' : $data->club_abb,
            "club_id" => (!isset($data->club_id)) ? '' : $data->club_id,
            "club_name" => (!isset($data->club_name)) ? '' : $data->club_name,
            "club_url" => (!isset($data->club_url)) ? '' : $data->club_url,
            "country" => (!isset($data->country)) ? '' : $data->country,
            "enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
            "event" => (!isset($data->event)) ? '' : $data->event,
            "event_name" => (!isset($data->event_name)) ? '' : $data->event_name,
            "event_short" => (!isset($data->event_short)) ? '' : $data->event_short,
            "first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
            "grad_year" => (!isset($data->grad_year)) ? '' : $data->grad_year,
            "grade" => (!isset($data->grad_year)) ? '' : g365_class_to_grade($data->grad_year),
            "height_ft" => (!isset($data->height_ft)) ? '' : $data->height_ft,
            "height_in" => (!isset($data->height_in)) ? '' : $data->height_in,
            "id" => (!isset($data->id)) ? '' : $data->id,
            "last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
            "name" => (!isset($data->name)) ? '' : $data->name,
            "nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
            "player" => (!isset($data->player)) ? '' : $data->player,
//             "jersey_size" => (!isset($data->jersey_size)) ? '' : $data->jersey_size,
            "position_id" => (!isset($data->position_id)) ? '' : $data->position_id,
            "position_name" => (!isset($data->position_name)) ? '' : $data->position_name,
            "state" => (!isset($data->state)) ? '' : $data->state,
            "verified" => (!isset($data->verified)) ? '' : $data->verified,
            "attendance" => (!isset($data->attendance)) ? '' : $data->attendance,
            "weight" => (!isset($data->weight)) ? '' : $data->weight,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"instagram" => (empty($data->instagram)) ? '' : $data->instagram
					);
					break;
				case 1:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
          $birthday = (!isset($data->birthday)) ? '' : DateTime::createFromFormat('Y-m-d', $data->birthday)->format('m-d-Y');
          $birthday_part = (empty($birthday)) ? '' : explode('-', $birthday);
					$data_trunc = array(
            "act" => (!isset($data->act)) ? '' : $data->act,
            "address" => (!isset($data->address)) ? '' : $data->address,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $data->profile_img,
            "event_profile_img_url" => (!isset($data->event_profile_img)) ? '' : $data->event_profile_img,
						"bcert_img_url" => (!isset($data->bcert_img)) ? '' : site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $data->bcert_img,
						"recard_img_url" => (!isset($data->recard_img)) ? '' : site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $data->recard_img,
            "birthday" => (empty($birthday)) ? '' : $birthday,
            "birthday_dy" => (empty($birthday)) ? '' : $birthday_part[1],
            "birthday_mo" => (empty($birthday)) ? '' : $birthday_part[0],
            "birthday_yr" => (empty($birthday)) ? '' : $birthday_part[2],
            "age" => (!isset($data->birthday)) ? '' : date_diff(date_create($data->birthday), date_create(date("Y-m-d")))->format('%y'),
            "city" => (!isset($data->city)) ? '' : $data->city,
            "club_abb" => (!isset($data->club_abb)) ? '' : $data->club_abb,
            "club_id" => (!isset($data->club_id)) ? '' : $data->club_id,
            "club_name" => (!isset($data->club_name)) ? '' : $data->club_name,
            "club_url" => (!isset($data->club_url)) ? '' : $data->club_url,
            "country" => (!isset($data->country)) ? '' : $data->country,
            "email" => (!isset($data->email)) ? '' : $data->email,
            "enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
            "evaluation" => (!isset($data->evaluation)) ? '' : $data->evaluation,
            "event" => (!isset($data->event)) ? '' : $data->event,
            "event_name" => (!isset($data->event_name)) ? '' : $data->event_name,
            "event_short" => (!isset($data->event_short)) ? '' : $data->event_short,
            "first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
            "gpa" => (!isset($data->gpa)) ? '' : $data->gpa,
            "grad_year" => (!isset($data->grad_year)) ? '' : $data->grad_year,
            "grade" => (!isset($data->grad_year)) ? '' : g365_class_to_grade($data->grad_year),
            "height_ft" => (!isset($data->height_ft)) ? '' : $data->height_ft,
            "height_in" => (!isset($data->height_in)) ? '' : $data->height_in,
            "id" => (!isset($data->id)) ? '' : $data->id,
            "last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
            "name" => (!isset($data->name)) ? '' : $data->name,
            "nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
//             "notes" => (!isset($data->notes)) ? '' : $data->notes,
            "phone" => (!isset($data->phone)) ? '' : $data->phone,
            "player" => (!isset($data->player)) ? '' : $data->player,
            "team" => (!isset($data->team)) ? '' : $data->team,
            "jersey_size" => (!isset($data->jersey_size)) ? '' : $data->jersey_size,
            "position_id" => (!isset($data->position_id)) ? '' : $data->position_id,
            "position_name" => (!isset($data->position_name)) ? '' : $data->position_name,
            "sat" => (!isset($data->sat)) ? '' : $data->sat,
            "school_id" => (!isset($data->school_id)) ? '' : $data->school_id,
            "school_name" => (!isset($data->school_name)) ? '' : $data->school_name,
            "school_url" => (!isset($data->school_url)) ? '' : $data->school_url,
            "state" => (!isset($data->state)) ? '' : $data->state,
            "stats" => (!isset($data->stats)) ? '' : $data->stats,
            "strengths" => (!isset($data->strengths)) ? '' : $data->strengths,
            "tagline" => (!isset($data->tagline)) ? '' : $data->tagline,
            "trends" => (!isset($data->trends)) ? '' : $data->trends,
            "updatetime" => (!isset($data->updatetime)) ? '' : $data->updatetime,
            "verified" => (!isset($data->verified)) ? '' : $data->verified,
            "attendance" => (!isset($data->attendance)) ? '' : $data->attendance,
            "video" => (!isset($data->video)) ? '' : $data->video,
            "videos" => (!isset($data->videos)) ? '' : $data->videos,
            "weaknesses" => (!isset($data->weaknesses)) ? '' : $data->weaknesses,
            "level_division" => (!isset($trends_data->level_division)) ? '' : $trends_data->level_division,
            "offers" => (!isset($trends_data->offers)) ? '' : $trends_data->offers,
            "player_to_watch" => (!isset($trends_data->player_to_watch)) ? '' : $trends_data->player_to_watch,
            "front_page" => (!isset($trends_data->front_page)) ? '' : $trends_data->front_page,
            "ss_evaluation" => (!isset($trends_data->ss_evaluation)) ? '' : $trends_data->ss_evaluation,
            "hhh_front_page" => (!isset($trends_data->hhh_front_page)) ? '' : $trends_data->hhh_front_page,
            "weight" => (!isset($data->weight)) ? '' : $data->weight,
            "zip" => (!isset($data->zip)) ? '' : $data->zip,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"instagram" => (empty($data->instagram)) ? '' : $data->instagram
					);
					break;
				case 2:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
          $birthday = (!isset($data->birthday)) ? '' : DateTime::createFromFormat('Y-m-d', $data->birthday)->format('m-d-Y');
          $birthday_part = (empty($birthday)) ? '' : explode('-', $birthday);
					$data_trunc = array(
            "act" => (!isset($data->act)) ? '' : $data->act,
            "address" => (!isset($data->address)) ? '' : $data->address,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $data->profile_img,
            "event_profile_img_url" => (!isset($data->event_profile_img)) ? '' : $data->event_profile_img,
						"recard_img_url" => (!isset($data->recard_img)) ? '' : site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $data->recard_img,
						"bcert_img_url" => (!isset($data->bcert_img)) ? '' : site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $data->bcert_img,
            "birthday" => (empty($birthday)) ? '' : $birthday,
            "birthday_dy" => (empty($birthday)) ? '' : $birthday_part[1],
            "birthday_mo" => (empty($birthday)) ? '' : $birthday_part[0],
            "birthday_yr" => (empty($birthday)) ? '' : $birthday_part[2],
            "age" => (!isset($data->birthday)) ? '' : date_diff(date_create($data->birthday), date_create(date("Y-m-d")))->format('%y'),
            "city" => (!isset($data->city)) ? '' : $data->city,
            "club_abb" => (!isset($data->club_abb)) ? '' : $data->club_abb,
            "club_id" => (!isset($data->club_id)) ? '' : $data->club_id,
            "club_name" => (!isset($data->club_name)) ? '' : $data->club_name,
            "club_url" => (!isset($data->club_url)) ? '' : $data->club_url,
            "country" => (!isset($data->country)) ? '' : $data->country,
            "email" => (!isset($data->email)) ? '' : $data->email,
            "enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
            "evaluation" => (!isset($data->evaluation)) ? '' : $data->evaluation,
            "event" => (!isset($data->event)) ? '' : $data->event,
            "event_name" => (!isset($data->event_name)) ? '' : $data->event_name,
            "event_short" => (!isset($data->event_short)) ? '' : $data->event_short,
            "first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
            "gpa" => (!isset($data->gpa)) ? '' : $data->gpa,
            "grad_year" => (!isset($data->grad_year)) ? '' : $data->grad_year,
            "grade" => (!isset($data->grad_year)) ? '' : g365_class_to_grade($data->grad_year),
            "height_ft" => (!isset($data->height_ft)) ? '' : $data->height_ft,
            "height_in" => (!isset($data->height_in)) ? '' : $data->height_in,
            "id" => (!isset($data->id)) ? '' : $data->id,
            "last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
            "name" => (!isset($data->name)) ? '' : $data->name,
            "nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
//             "notes" => (!isset($data->notes)) ? '' : $data->notes,
            "phone" => (!isset($data->phone)) ? '' : $data->phone,
            "player" => (!isset($data->player)) ? '' : $data->player,
            "team" => (!isset($data->team)) ? '' : $data->team,
            "jersey_size" => (!isset($data->jersey_size)) ? '' : $data->jersey_size,
            "position_id" => (!isset($data->position_id)) ? '' : $data->position_id,
            "position_name" => (!isset($data->position_name)) ? '' : $data->position_name,
            "sat" => (!isset($data->sat)) ? '' : $data->sat,
            "school_id" => (!isset($data->school_id)) ? '' : $data->school_id,
            "school_name" => (!isset($data->school_name)) ? '' : $data->school_name,
            "school_url" => (!isset($data->school_url)) ? '' : $data->school_url,
            "state" => (!isset($data->state)) ? '' : $data->state,
            "strengths" => (!isset($data->strengths)) ? '' : implode(',', ((is_array($data->strengths)) ? $data->strengths : json_decode($data->strengths))),
            "tagline" => (!isset($data->tagline)) ? '' : $data->tagline,
            "updatetime" => (!isset($data->updatetime)) ? '' : $data->updatetime,
            "verified" => (!isset($data->verified)) ? '' : $data->verified,
            "attendance" => (!isset($data->attendance)) ? '' : $data->attendance,
            "video" => (!isset($data->video)) ? '' : $data->video,
            "videos" => (!isset($data->videos)) ? '' : $data->videos,
            "weaknesses" => (!isset($data->weaknesses)) ? '' : implode(',', ((is_array($data->weaknesses)) ? $data->weaknesses : json_decode($data->weaknesses))),
            "level_division" => (!isset($trends_data->level_division)) ? '' : $trends_data->level_division,
            "offers" => (!isset($trends_data->offers)) ? '' : $trends_data->offers,
            "player_to_watch" => (!isset($trends_data->player_to_watch)) ? '' : $trends_data->player_to_watch,
            "front_page" => (!isset($trends_data->front_page)) ? '' : $trends_data->front_page,
            "ss_evaluation" => (!isset($trends_data->ss_evaluation)) ? '' : $trends_data->ss_evaluation,
            "hhh_front_page" => (!isset($trends_data->hhh_front_page)) ? '' : $trends_data->hhh_front_page,
            "weight" => (!isset($data->weight)) ? '' : $data->weight,
            "zip" => (!isset($data->zip)) ? '' : $data->zip,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"instagram" => (empty($data->instagram)) ? '' : $data->instagram,
            "stats" => (!isset($data->stats)) ? '' : json_decode($data->stats),
            "trends" => (!isset($data->trends)) ? '' : json_decode($data->trends),
          );
          //flatten stats and trends if we need to.
          if( $type === 27 || $type === 28 || $type === 52 || $type === 55 || $type_key === 56 ) {
            if( is_object($data_trunc['stats']) ) {
              foreach( $data_trunc['stats'] as $handle => $val ){
                $data_trunc[ 'stat_' . $handle . '_val' ] = $val;
              }
              unset($data_trunc['stats']);
            }
            if( is_object($data_trunc['trends']) ) {
              foreach( $data_trunc['trends'] as $handle => $val ){
                $data_trunc[ 'trend_' . $handle . '_val' ] = $val;
              }
              unset($data_trunc['trends']);
            }
          }
					break;
			}
			break;
		case 1:  //add/edit player
		case 7:  //player search
    case 20: //player search admin
    case 24: //pl_ed: player single edit
    case 35: //player_admin: player single stand alone admin
    case 37: //player_rosters: add player to roster
    case 38: //player_rosters_admin: add player to roster admin
		case 47: //player_award: add player to awards 
    case 56: //add/edit team stat: ss_team_event_admin: admin event data manager, stand alone
      $curr_years = intval(date("Y"));
      $curr_years = '<option value="">Please select</option>' . implode('', array_map(function($a){ return '<option value="a-' . $a . '">' . $a . '</option>'; }, range($curr_years - 2, $curr_years - 20)));
			switch( $access[0] ) {
				case 0:
					$data->social = ( isset($data->social) && !empty($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->first_name)) ? '' : $data->first_name . ' ' . $data->last_name,
						"first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
						"last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
            "nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
            "birthday" => (!isset($data->birthday)) ? '' : $data->birthday,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $data->profile_img,
						"recard_img_url" => (!isset($data->recard_img)) ? '' : site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $data->recard_img,
						"bcert_img_url" => (!isset($data->bcert_img)) ? '' : site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $data->bcert_img,
						"enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"height_ft" => (!isset($data->height_ft)) ? '' : $data->height_ft,
						"height_in" => (!isset($data->height_in)) ? '' : $data->height_in,
						"weight" => (!isset($data->weight)) ? '' : $data->weight,
            "verified" => (!isset($data->verified)) ? '' : $data->verified,
            "attendance" => (!isset($data->attendance)) ? '' : $data->attendance,
            "jersey_size" => (!isset($data->jersey_size)) ? '' : $data->jersey_size,
						"grad_year" => (!isset($data->grad_year)) ? '' : $data->grad_year,
            "grade" => (!isset($data->grad_year)) ? '' : g365_class_to_grade($data->grad_year),
						"address" => (!isset($data->address)) ? '' : $data->address,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
						"zip" => (!isset($data->zip)) ? '' : $data->zip,
						"country" => (!isset($data->country)) ? '' : $data->country,
						"position_name" => (!isset($data->position_name)) ? '' : $data->position_name,
						"position_id" => (!isset($data->position_id)) ? '' : $data->position_id,
						"school_name" => (!isset($data->school_name)) ? '' : $data->school_name,
						"school_id" => (!isset($data->school_id)) ? '' : $data->school_id,
						"club_name" => (!isset($data->club_name)) ? '' : $data->club_name,
						"club_id" => (!isset($data->club_id)) ? '' : $data->club_id,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"instagram" => (empty($data->instagram)) ? $data->instagram : $data->instagram,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"social-facebook" => (empty($data->social->Facebook)) ? '' : $data->social->Facebook,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter,
            "bcert_resub" => (!isset($data->bcert_resub)) ? '' : $data->bcert_resub,
            "recard_resub" => (!isset($data->recard_resub)) ? '' : $data->recard_resub
					);
					break;
				case 1:
					$data->social = ( isset($data->social) && !empty($data->social) ) ? json_decode($data->social) : null;
          $birthday = (g365_validate_date($data->birthday)) ? DateTime::createFromFormat('Y-m-d', $data->birthday)->format('m-d-Y') : '';
          $birthday_part = (empty($birthday)) ? '' : explode('-', $birthday);
					$data_trunc = array(
						"id" => $data->id,
						"element_title" => (!isset($data->first_name)) ? '' : $data->first_name . ' ' . $data->last_name,
						"first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
						"last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
            "nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $data->profile_img,
						"recard_img_url" => (!isset($data->recard_img)) ? '' : site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $data->recard_img,
						"bcert_img_url" => (!isset($data->bcert_img)) ? '' : site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $data->bcert_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
            "birthday" => (empty($birthday)) ? '' : $birthday,
            "birthday_dy" => (empty($birthday)) ? '' : $birthday_part[1],
            "birthday_mo" => (empty($birthday)) ? '' : $birthday_part[0],
            "birthday_yr" => (empty($birthday)) ? '' : $birthday_part[2],
						"enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
						"height_ft" => (!isset($data->height_ft)) ? '' : $data->height_ft,
						"height_in" => (!isset($data->height_in)) ? '' : $data->height_in,
						"weight" => (!isset($data->weight)) ? '' : $data->weight,
            "verified" => (!isset($data->verified)) ? '' : $data->verified,
            "attendance" => (!isset($data->attendance)) ? '' : $data->attendance,
            "jersey_size" => (!isset($data->jersey_size)) ? '' : $data->jersey_size,
						"grad_year" => (!isset($data->grad_year)) ? '' : $data->grad_year,
            "grade" => (!isset($data->grad_year)) ? '' : g365_class_to_grade($data->grad_year),
						"address" => (!isset($data->address)) ? '' : $data->address,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
						"zip" => (!isset($data->zip)) ? '' : $data->zip,
						"country" => (!isset($data->country)) ? '' : $data->country,
						"position_name" => (!isset($data->position_name)) ? '' : $data->position_name,
						"position_id" => (!isset($data->position_id)) ? '' : $data->position_id,
            "school" => (!isset($data->school)) ? '' : $data->school,
						"school_name" => (!isset($data->school_name)) ? '' : $data->school_name,
						"school_id" => (!isset($data->school_id)) ? '' : $data->school_id,
						"club_name" => (!isset($data->club_name)) ? '' : $data->club_name,
						"club_id" => (!isset($data->club_id)) ? '' : $data->club_id,
						"account_level" => (!isset($data->account_level)) ? '' : $data->account_level,
						"tagline" => (!isset($data->tagline)) ? '' : $data->tagline,
						"videos" => (!isset($data->videos)) ? '' : $data->videos,
						"gpa" => (!isset($data->gpa)) ? '' : $data->gpa,
						"sat" => (!isset($data->sat)) ? '' : $data->sat,
						"act" => (!isset($data->act)) ? '' : $data->act,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"instagram" => (empty($data->instagram)) ? '' : $data->instagram,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter,
						"twitter" => (empty($data->twitter)) ? '' : $data->twitter,
						"access" => (empty($data->access)) ? '' : ' ' . $data->access,
            "bcert_resub" => (!isset($data->bcert_resub)) ? '' : $data->bcert_resub,
            "recard_resub" => (!isset($data->recard_resub)) ? '' : $data->recard_resub
					);
					break;
				case 2:
					$data->social = ( isset($data->social) && !empty($data->social) ) ? json_decode($data->social) : null;
          $birthday = (g365_validate_date($data->birthday)) ? DateTime::createFromFormat('Y-m-d', $data->birthday)->format('m-d-Y') : '';
          $birthday_part = (empty($birthday)) ? '' : explode('-', $birthday);
					$data_trunc = array(
						"id" => $data->id,
						"element_title" => (!isset($data->first_name)) ? '' : $data->first_name . ' ' . $data->last_name,
						"first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
						"last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
            "nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/player-profiles/', 'https' ) . '' . $data->profile_img,
						"recard_img_url" => (!isset($data->recard_img)) ? '' : site_url( '/wp-content/uploads/player-reportcards/', 'https' ) . '' . $data->recard_img,
						"bcert_img_url" => (!isset($data->bcert_img)) ? '' : site_url( '/wp-content/uploads/player-birthcerts/', 'https' ) . '' . $data->bcert_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
            "birthday" => (empty($birthday)) ? '' : $birthday,
            "birthday_dy" => (empty($birthday)) ? '' : $birthday_part[1],
            "birthday_mo" => (empty($birthday)) ? '' : $birthday_part[0],
            "birthday_yr" => (empty($birthday)) ? '' : $birthday_part[2],
						"enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
						"height_ft" => (!isset($data->height_ft)) ? '' : $data->height_ft,
						"height_in" => (!isset($data->height_in)) ? '' : $data->height_in,
						"weight" => (!isset($data->weight)) ? '' : $data->weight,
            "verified" => (!isset($data->verified)) ? '' : $data->verified,
            "attendance" => (!isset($data->attendance)) ? '' : $data->attendance,
            "jersey_size" => (!isset($data->jersey_size)) ? '' : $data->jersey_size,
						"grad_year" => (!isset($data->grad_year)) ? '' : $data->grad_year,
            "grade" => (!isset($data->grad_year)) ? '' : g365_class_to_grade($data->grad_year),
						"address" => (!isset($data->address)) ? '' : $data->address,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
						"zip" => (!isset($data->zip)) ? '' : $data->zip,
						"country" => (!isset($data->country)) ? '' : $data->country,
						"position_name" => (!isset($data->position_name)) ? '' : $data->position_name,
						"position_id" => (!isset($data->position_id)) ? '' : $data->position_id,
            "school" => (!isset($data->school)) ? '' : $data->school,
						"school_name" => (!isset($data->school_name)) ? '' : $data->school_name,
						"school_id" => (!isset($data->school_id)) ? '' : $data->school_id,
						"club_name" => (!isset($data->club_name)) ? '' : $data->club_name,
						"club_id" => (!isset($data->club_id)) ? '' : $data->club_id,
						"account_level" => (!isset($data->account_level)) ? '' : $data->account_level,
						"tagline" => (!isset($data->tagline)) ? '' : $data->tagline,
						"videos" => (!isset($data->videos)) ? '' : $data->videos,
						"gpa" => (!isset($data->gpa)) ? '' : $data->gpa,
						"sat" => (!isset($data->sat)) ? '' : $data->sat,
						"act" => (!isset($data->act)) ? '' : $data->act,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"instagram" => (empty($data->instagram)) ? '' : $data->instagram,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter,
						"twitter" => (empty($data->twitter)) ? '' : $data->twitter,
						"access" => (empty($data->access)) ? '' : ' ' . $data->access,
            "team" => (!isset($data->player)) ? '' : $data->team,
            "evaluation" => (!isset($data->evaluation)) ? '' : $data->evaluation,
            "strengths" => (!isset($data->strengths)) ? '' : json_decode($data->strengths, true),
            "weaknesses" => (!isset($data->weaknesses)) ? '' : json_decode($data->weaknesses, true),
            "level_division" => (!isset($trends_data->level_division)) ? '' : $trends_data->level_division,
            "offers" => (!isset($trends_data->offers)) ? '' : $trends_data->offers,
            "player_to_watch" => (!isset($trends_data->player_to_watch)) ? '' : $trends_data->player_to_watch,
            "front_page" => (!isset($trends_data->front_page)) ? '' : $trends_data->front_page,
            "ss_evaluation" => (!isset($trends_data->ss_evaluation)) ? '' : $trends_data->ss_evaluation,
            "hhh_front_page" => (!isset($trends_data->hhh_front_page)) ? '' : $trends_data->hhh_front_page,
            "event" => (!isset($data->event)) ? '' : $data->event,
            "bcert_resub" => (!isset($data->bcert_resub)) ? '' : $data->bcert_resub,
            "recard_resub" => (!isset($data->recard_resub)) ? '' : $data->recard_resub
					);
					break;
			}
			break;
		case 2: //add/edit event
			break;
		case 3: //add/edit organization
    case 10: //schools
    case 11: //clubs
    case 25: //og_ed
    case 30: //club_names_admin
      switch( $access[0] ) {
				case 0:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/org-logos/', 'https' ) . '' . $data->profile_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"address" => (!isset($data->address)) ? '' : $data->address,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
						"zip" => (!isset($data->zip)) ? '' : $data->zip,
						"country" => (!isset($data->country)) ? '' : $data->country,
						"county" => (!isset($data->county)) ? '' : $data->county,
						"abbreviation" => (!isset($data->abbreviation)) ? '' : $data->abbreviation,
            "director_first" => (!isset($data->director_first)) ? '' : $data->director_first,
						"director_last" => (!isset($data->director_last)) ? '' : $data->director_last,
						"director_email" => (!isset($data->director_email)) ? '' : $data->director_email,
						"director_phone" => (!isset($data->director_phone)) ? '' : $data->director_phone,
						"link" => (!isset($data->link)) ? '' : $data->link,
						"videos" => (!isset($data->videos)) ? '' : $data->videos,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"social-facebook" => (empty($data->social->Facebook)) ? '' : $data->social->Facebook,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter
					);
					break;
				case 1:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/org-logos/', 'https' ) . '' . $data->profile_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"address" => (!isset($data->address)) ? '' : $data->address,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
						"zip" => (!isset($data->zip)) ? '' : $data->zip,
						"country" => (!isset($data->country)) ? '' : $data->country,
						"county" => (!isset($data->county)) ? '' : $data->county,
						"abbreviation" => (!isset($data->abbreviation)) ? '' : $data->abbreviation,
						"director_first" => (!isset($data->director_first)) ? '' : $data->director_first,
						"director_last" => (!isset($data->director_last)) ? '' : $data->director_last,
						"director_email" => (!isset($data->director_email)) ? '' : $data->director_email,
						"director_phone" => (!isset($data->director_phone)) ? '' : $data->director_phone,
						"link" => (!isset($data->link)) ? '' : $data->link,
						"videos" => (!isset($data->videos)) ? '' : $data->videos,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram
					);
					break;
				case 2:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/org-logos/', 'https' ) . '' . $data->profile_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"address" => (!isset($data->address)) ? '' : $data->address,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
						"zip" => (!isset($data->zip)) ? '' : $data->zip,
						"country" => (!isset($data->country)) ? '' : $data->country,
						"county" => (!isset($data->county)) ? '' : $data->county,
						"abbreviation" => (!isset($data->abbreviation)) ? '' : $data->abbreviation,
						"director_first" => (!isset($data->director_first)) ? '' : $data->director_first,
						"director_last" => (!isset($data->director_last)) ? '' : $data->director_last,
						"director_email" => (!isset($data->director_email)) ? '' : $data->director_email,
						"director_phone" => (!isset($data->director_phone)) ? '' : $data->director_phone,
						"link" => (!isset($data->link)) ? '' : $data->link,
						"videos" => (!isset($data->videos)) ? '' : $data->videos,
						"notes" => (!isset($data->notes)) ? '' : $data->notes,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"nickname" => (!isset($data->nickname)) ? '' : $data->nickname,
						"access" => (empty($data->access)) ? '' : ' ' . $data->access
					);
					break;
			}
			break;
		case 4: //add/edit team
    case 41: //team_names_sl: add team
      switch( $access[0] ) {
				case 0:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
// 						"element_title" => (g365_level_key($data->level) . (($data->name !== '' && isset($data->name) && $data->name !== 'null') ? ' ' . $data->name : '') ),
						"element_title" => (g365_level_key($data->level) . (($data->name !== '' && isset($data->name) && $data->name !== 'null') ? ' ' . $data->name : '') ),
						"name" => (!isset($data->name)) ? '' : $data->name,
						"org_id" => (!isset($data->org_id)) ? '' : $data->org_id,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
						"roster_id" => (!isset($data->roster_id)) ? '' : $data->roster_id,
						"roster_name" => (!isset($data->roster_name)) ? '' : $data->roster_name,
						"team_type" => (!isset($data->team_type)) ? '' : $data->team_type,
						"media" => (!isset($data->media)) ? '' : $data->media,
						"level" => (!isset($data->level)) ? '' : $data->level,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"notes" => (!isset($data->notes)) ? '' : $data->notes,
					);
					break;
				case 1:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (g365_level_key($data->level) . (($data->name !== '' && isset($data->name) && $data->name !== 'null') ? ' ' . $data->name : '') ),
						"name" => (!isset($data->name)) ? '' : $data->name,
						"org_id" => (!isset($data->org_id)) ? '' : $data->org_id,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
						"roster_id" => (!isset($data->roster_id)) ? '' : $data->roster_id,
						"roster_name" => (!isset($data->roster_name)) ? '' : $data->roster_name,
						"team_type" => (!isset($data->team_type)) ? '' : $data->team_type,
						"media" => (!isset($data->media)) ? '' : $data->media,
						"level" => (!isset($data->level)) ? '' : $data->level,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"notes" => (!isset($data->notes)) ? '' : $data->notes,
					);
					break;
				case 2:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (g365_level_key($data->level) . (($data->name !== '' && isset($data->name) && $data->name !== 'null') ? ' ' . $data->name : '') ),
						"name" => (!isset($data->name)) ? '' : $data->name,
						"org_id" => (!isset($data->org_id)) ? '' : $data->org_id,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
						"roster_id" => (!isset($data->roster_id)) ? '' : $data->roster_id,
						"roster_name" => (!isset($data->roster_name)) ? '' : $data->roster_name,
						"team_type" => (!isset($data->team_type)) ? '' : $data->team_type,
						"media" => (!isset($data->media)) ? '' : $data->media,
						"level" => (!isset($data->level)) ? '' : $data->level,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"notes" => (!isset($data->notes)) ? '' : $data->notes,
					);
					break;
			}
			break;
    case 5: //add/edit player evalutation
			break;
		case 6: //register player for event
			break;
    case 8: //add team rosters for event
    case 9: //add/edit rosters
    case 17: //rosters_teams: add only rosters to events: stand alone
    case 26: //ro_ed
    case 32: //tournament_roster_admin: stand alone, tournament edit
    case 33: //club_teams: stand alone, tournament roster create/edit
    case 39: //rosters_sl, tournament stand alone, single use
    case 40: //rosters_club_sl, club team stand alone, single use
    case 42: //rosters_sl_xl, club with events, stand alone, single use
    case 46: //rosters_teams_admin: add rosters to events: stand alone
      $g365_grade_key = g365_return_keys('g365_grade_key_short');
      switch( $access[0] ) {
				case 0:
          $data->org_name = (empty($data->org_abbr)) ? $data->org_name : $data->org_abbr;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => ((isset($data->element_title)) ? $data->element_title : ((g365_level_key($data->team_level) . ((isset($data->team_name) && $data->team_name !== '' && $data->team_name !== 'null') ? ' ' . $data->team_name : '') . ((isset($data->name) && $data->name !== '' && $data->name !== 'null') ? ' ' . $data->name : '') . ((isset($data->event_name)) ? ' : ' . $data->event_name : '')))),
						"enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"org_id" => (!isset($data->org_id)) ? '' : $data->org_id,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
						"team_id" => (!isset($data->team_id)) ? '' : $data->team_id,
						"team_name" => (!isset($data->team_name)) ? '' : $data->team_name,
						"team_level" => (!isset($data->team_level)) ? '' : $data->team_level,
						"team_level_name" => (!isset($data->team_level)) ? '' : $g365_grade_key[$data->team_level],
						"team_name_full" => (!isset($data->team_name)) ? $g365_grade_key[$data->team_level] : $g365_grade_key[$data->team_level] . ' ' . $data->team_name,            
						"event_id" => (!isset($data->event_id)) ? '0' : $data->event_id,
						"event_name" => (!isset($data->event_name)) ? 'Club Rosters' : $data->event_name,
						"event_short_name" => (!isset($data->event_short)) ? ((!isset($data->event_name)) ? 'Club Rosters' : $data->event_name) : $data->event_short,
						"event_divisions" => (!isset($data->event_divisions)) ? '' : ' ' . $data->event_divisions,
						"coach_id" => (!isset($data->coach_id)) ? '' : $data->coach_id,
						"coach_name" => (!isset($data->coach_name)) ? '' : $data->coach_name,
						"asst_id" => (!isset($data->asst_id)) ? '' : $data->asst_id,
						"asst_name" => (!isset($data->asst_name)) ? '' : $data->asst_name,
						"team_type" => (!isset($data->team_type)) ? '' : $data->team_type,
            "player_rosters" => (!isset($data->player_names)) ? '' : $data->player_names,
						"event_names" => (!isset($data->event_names)) ? '' : $data->event_names,
						"level" => (!isset($data->level)) ? '' : $data->level,
						"level_name" => (!isset($data->level)) ? '' : $g365_grade_key[$data->level],
						"division" => (!isset($data->division)) ? '' : $data->division,
						"divisions123" => 'hello',
						"description" => (!isset($data->description)) ? '' : $data->description,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
            "field-set-id" => (!isset($data->field_set_id)) ? '' : $data->field_set_id,
            "division_selector_birth_lock" => (!isset($data->division_selector_birth_lock)) ? '' : $data->division_selector_birth_lock,
            "division_selector_class_lock" => (!isset($data->division_selector_class_lock)) ? '' : $data->division_selector_class_lock,
            "division_selector_lock_type" => (!isset($data->division_selector_lock_type)) ? '' : $data->division_selector_lock_type,
					);
					break;
				case 1:
					$data->org_name = (empty($data->org_abbr)) ? $data->org_name : $data->org_abbr;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => ((isset($data->element_title)) ? $data->element_title : ((g365_level_key($data->team_level) . ((isset($data->team_name) && $data->team_name !== '' && $data->team_name !== 'null') ? ' ' . $data->team_name : '') . ((isset($data->name) && $data->name !== '' && $data->name !== 'null') ? ' ' . $data->name : '') . ((isset($data->event_name)) ? ' : ' . $data->event_name : '')))),
						"enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"org_id" => (!isset($data->org_id)) ? '' : $data->org_id,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
						"team_id" => (!isset($data->team_id)) ? '' : $data->team_id,
						"team_name" => (!isset($data->team_name)) ? '' : $data->team_name,
						"team_level" => (!isset($data->team_level)) ? '' : $data->team_level,
						"team_level_name" => (!isset($data->team_level)) ? '' : $g365_grade_key[$data->team_level],
						"team_name_full" => (!isset($data->team_name)) ? $g365_grade_key[$data->team_level] : $g365_grade_key[$data->team_level] . ' ' . $data->team_name,            
						"event_id" => (!isset($data->event_id)) ? '0' : $data->event_id,
						"event_name" => (!isset($data->event_name)) ? 'Club Rosters' : $data->event_name,
						"event_short_name" => (!isset($data->event_short)) ? ((!isset($data->event_name)) ? 'Club Rosters' : $data->event_name) : $data->event_short,
						"event_divisions" => (!isset($data->event_divisions)) ? '' : str_replace('"', "'", ' ' . $data->event_divisions),
						"division_selector_options" => (!isset($data->division_selector_options)) ? '' : ' ' . $data->division_selector_options,
						"division_selector_options_hide" => (!isset($data->division_selector_options_hide)) ? '' : $data->division_selector_options_hide,
						"coach_id" => (!isset($data->coach_id)) ? '' : $data->coach_id,
						"coach_names" => (!isset($data->coach_names)) ? '' : $data->coach_names,
						"asst_id" => (!isset($data->asst_id)) ? '' : $data->asst_id,
						"asst_name" => (!isset($data->asst_name)) ? '' : $data->asst_name,
						"team_type" => (!isset($data->team_type)) ? '' : $data->team_type,
            "player_rosters" => (!isset($data->player_names)) ? '' : $data->player_names,
						"event_names" => (!isset($data->event_names)) ? '' : $data->event_names,
						"level" => (!isset($data->level)) ? '' : $data->level,
						"level_name" => (!isset($data->level)) ? '' : $g365_grade_key[$data->level],
						"division" => (!isset($data->division)) ? '' : $data->division,
						"divisions" => (!isset($data->divisions)) ? '' : $data->divisions,
						"description" => (!isset($data->description)) ? '' : $data->description,
						"wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
            "field-set-id" => (!isset($data->field_set_id)) ? '' : $data->field_set_id,
						"division_selector_birth_lock" => (!isset($data->division_selector_birth_lock)) ? '' : $data->division_selector_birth_lock,
						"division_selector_class_lock" => (!isset($data->division_selector_class_lock)) ? '' : $data->division_selector_class_lock,
            "division_selector_lock_type" => (!isset($data->division_selector_lock_type)) ? '' : $data->division_selector_lock_type,
            "player_default" => (!isset($data->player_default)) ? '' : $data->player_default,
					);
					break;
				case 2:
					if(isset($data->org_abbr)) $data->org_name = $data->org_abbr;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => ((isset($data->element_title)) ? $data->element_title : ((g365_level_key($data->team_level) . ((isset($data->team_name) && $data->team_name !== '' && $data->team_name !== 'null') ? ' ' . $data->team_name : '') . ((isset($data->name) && $data->name !== '' && $data->name !== 'null') ? ' ' . $data->name : '') . ((isset($data->event_name)) ? ' : ' . $data->event_name : '')))),
						"enabled" => (!isset($data->enabled)) ? '' : $data->enabled,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"org_id" => (!isset($data->org_id)) ? '' : $data->org_id,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
						"team_id" => (!isset($data->team_id)) ? '' : $data->team_id,
						"team_name" => (!isset($data->team_name)) ? '' : $data->team_name,
						"team_level" => (!isset($data->team_level)) ? '' : $data->team_level,
						"team_level_name" => (!isset($data->team_level)) ? '' : $g365_grade_key[$data->team_level],
						"team_name_full" => (!isset($data->team_name)) ? $g365_grade_key[$data->team_level] : $g365_grade_key[$data->team_level] . ' ' . $data->team_name,            
						"event_id" => (!isset($data->event_id)) ? '0' : $data->event_id,
						"event_name" => (!isset($data->event_name)) ? 'Club Rosters' : $data->event_name,
						"event_short_name" => (!isset($data->event_short)) ? ((!isset($data->event_name)) ? 'Club Rosters' : $data->event_name) : $data->event_short,
						"event_divisions" => (!isset($data->event_divisions)) ? '' : str_replace('"', "'", ' ' . $data->event_divisions),
						"division_selector_options" => (!isset($data->division_selector_options)) ? '' : ' ' . $data->division_selector_options,
						"division_selector_options_hide" => (!isset($data->division_selector_options_hide)) ? '' : $data->division_selector_options_hide,
						"event_name" => (!isset($data->event_name)) ? '' : $data->event_name,
						"coach_id" => (!isset($data->coach_id)) ? '' : $data->coach_id,
						"coach_name" => (!isset($data->coach_name)) ? '' : $data->coach_name,
						"asst_id" => (!isset($data->asst_id)) ? '' : $data->asst_id,
						"asst_name" => (!isset($data->asst_name)) ? '' : $data->asst_name,
						"team_type" => (!isset($data->team_type)) ? '' : $data->team_type,
            "player_rosters" => (!isset($data->player_names)) ? '' : $data->player_names,
						"event_names" => (!isset($data->event_names)) ? '' : $data->event_names,
						"level" => (!isset($data->level)) ? '' : $data->level,
						"level_name" => (!isset($data->level)) ? '' : $g365_grade_key[$data->level],
						"division" => (!isset($data->division)) ? '' : $data->division,
						"description" => (!isset($data->description)) ? '' : $data->description,
						"wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
            "field-set-id" => (!isset($data->field_set_id)) ? '' : $data->field_set_id,
						"division_selector_birth_lock" => (!isset($data->division_selector_birth_lock)) ? '' : $data->division_selector_birth_lock,
						"division_selector_class_lock" => (!isset($data->division_selector_class_lock)) ? '' : $data->division_selector_class_lock,
						"division_selector_lock_type" => (!isset($data->division_selector_lock_type)) ? '' : $data->division_selector_lock_type,
						"date_restrictions" => (!isset($data->date_restrictions)) ? '' : $data->date_restrictions,
						"team_restrictions" => (!isset($data->team_restrictions)) ? '' : $data->team_restrictions,
						"pool_name" => (!isset($data->pool_name)) ? '' : $data->pool_name,
						"pool_number" => (!isset($data->pool_number)) ? '' : $data->pool_number,
            "player_default" => (!isset($data->player_default)) ? '' : $data->player_default,
					);
					break;
			}
      if( $type === 17 || $type === 8 ) {
        $data_trunc['level_birth_lock'] = $data_trunc['division_selector_birth_lock'];
        $data_trunc['level_class_lock'] = $data_trunc['division_selector_class_lock'];
        unset($data_trunc['division_selector_birth_lock'], $data_trunc['division_selector_class_lock']);
      }
			break;
		case 12: //add/edit coach
    case 31: //co_ed: stand-alone profile editor
			switch( $access[0] ) {
				case 0:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->first_name)) ? '' : $data->first_name . ' ' . $data->last_name,
						"first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
						"last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/coach-profiles/', 'https' ) . '' . $data->profile_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"social-facebook" => (empty($data->social->Facebook)) ? '' : $data->social->Facebook,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter,
					);
					break;
				case 1:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->first_name)) ? '' : $data->first_name . ' ' . $data->last_name,
						"first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
						"last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/coach-profiles/', 'https' ) . '' . $data->profile_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"social-facebook" => (empty($data->social->Facebook)) ? '' : $data->social->Facebook,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter,
					);
					break;
				case 2:
					$data->social = ( isset($data->social) ) ? json_decode($data->social) : null;
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->first_name)) ? '' : $data->first_name . ' ' . $data->last_name,
						"first_name" => (!isset($data->first_name)) ? '' : $data->first_name,
						"last_name" => (!isset($data->last_name)) ? '' : $data->last_name,
						"profile_img_url" => (!isset($data->profile_img)) ? '' : site_url( '/wp-content/uploads/coach-profiles/', 'https' ) . '' . $data->profile_img,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"phone" => (!isset($data->phone)) ? '' : $data->phone,
						"city" => (!isset($data->city)) ? '' : $data->city,
						"state" => (!isset($data->state)) ? '' : $data->state,
            "wrapper_id" => (empty($data->wrapper_id)) ? '' : $data->wrapper_id,
						"social-instagram" => (empty($data->social->Instagram)) ? '' : $data->social->Instagram,
						"social-facebook" => (empty($data->social->Facebook)) ? '' : $data->social->Facebook,
						"social-twitter" => (empty($data->social->Twitter)) ? '' : $data->social->Twitter,
					);
					break;
			}
			break;
		case 21: //event_names
			switch( $access[0] ) {
				case 0:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
						"eventtime" => (!isset($data->eventtime)) ? '' : date("F d, Y", strtotime($data->eventtime))
					);
					break;
				case 1:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
						"eventtime" => (!isset($data->eventtime)) ? '' : date("F d, Y", strtotime($data->eventtime))
					);
					break;
				case 2:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
						"eventtime" => (!isset($data->eventtime)) ? '' : date("F d, Y", strtotime($data->eventtime))
					);
					break;
			}
			break;
		case 29: //event_details
			switch( $access[0] ) {
				case 0:
				case 1:
				case 2:
					$data_trunc = array(
						"event" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
						"eventtime" => (!isset($data->eventtime)) ? '' : date("F d, Y", strtotime($data->eventtime)),
						"org" => (!isset($data->org)) ? '' : $data->org,
						"logo_img" => (!isset($data->logo_img)) ? '' : $data->logo_img,
						"dates" => (!isset($data->dates)) ? '' : $data->dates,
						"times" => (!isset($data->times)) ? '' : $data->times,
						"divisions" => (!isset($data->divisions)) ? '' : $data->divisions,
						"locations" => (!isset($data->locations)) ? '' : $data->locations,
						"link" => (!isset($data->link)) ? '' : $data->link,
						"trends" => (!isset($data->trends)) ? '' : $data->trends,
						"stats" => (!isset($data->stats)) ? '' : $data->stats,
						"org_name" => (!isset($data->org_name)) ? '' : $data->org_name,
            "level_link" => (!isset($data->level_link)) ? '' : $data->level_link,
            "init_hide" => ' hide'
					);
					break;
			}
			break;
		case 34: //games
			switch( $access[0] ) {
				case 0:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
            "field-set-id" => (!isset($data->id)) ? '' : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name . ' : ' . $data->home_team . ' vs. ' . $data->away_team,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
            "away_players" => (!isset($data->away_players)) ? '' : $data->away_players,
            "away_profile_img" => (!isset($data->away_profile_img)) ? '' : $data->away_profile_img,
            "away_roster_id" => (!isset($data->away_roster_id)) ? '' : $data->away_roster_id,
            "away_team" => (!isset($data->away_team)) ? '' : stat_platform_girl_level(['team_name'=>$data->away_team]),
            "away_team_score" => (!isset($data->away_team_score)) ? '' : $data->away_team_score,
            "court" => (!isset($data->court)) ? '' : $data->court,
            "division" => (!isset($data->division)) ? '' : $data->division,
            "event_id" => (!isset($data->event_id)) ? '' : $data->event_id,
            "form_template" => (!isset($data->form_template)) ? '' : $data->form_template,
            "home_players" => (!isset($data->home_players)) ? '' : $data->home_players,
            "home_profile_img" => (!isset($data->home_profile_img)) ? '' : $data->home_profile_img,
            "home_roster_id" => (!isset($data->home_roster_id)) ? '' : $data->home_roster_id,
            "home_team" => (!isset($data->home_team)) ? '' : stat_platform_girl_level(['team_name'=>$data->home_team]),
            "home_team_score" => (!isset($data->home_team_score)) ? '' : $data->home_team_score,
            "location" => (!isset($data->location)) ? '' : $data->location,
            "start_time" => (!isset($data->start_time)) ? '' : $data->start_time,
					);
					break;
        //case 56:
				case 1:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
            "field-set-id" => (!isset($data->id)) ? '' : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name . ' : ' . $data->home_team . ' vs. ' . $data->away_team,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
            "away_players" => (!isset($data->away_players)) ? '' : $data->away_players,
            "away_profile_img" => (!isset($data->away_profile_img)) ? '' : $data->away_profile_img,
            "away_roster_id" => (!isset($data->away_roster_id)) ? '' : $data->away_roster_id,
            "away_team" => (!isset($data->away_team)) ? '' : stat_platform_girl_level(['team_name'=>$data->away_team]),
            "away_team_score" => (!isset($data->away_team_score)) ? '' : $data->away_team_score,
            "court" => (!isset($data->court)) ? '' : $data->court,
            "division" => (!isset($data->division)) ? '' : $data->division,
            "event_id" => (!isset($data->event_id)) ? '' : $data->event_id,
            "form_template" => (!isset($data->form_template)) ? '' : $data->form_template,
            "home_players" => (!isset($data->home_players)) ? '' : $data->home_players,
            "home_profile_img" => (!isset($data->home_profile_img)) ? '' : $data->home_profile_img,
            "home_roster_id" => (!isset($data->home_roster_id)) ? '' : $data->home_roster_id,
            "home_team" => (!isset($data->home_team)) ? '' : stat_platform_girl_level(['team_name'=>$data->home_team]),
            "home_team_score" => (!isset($data->home_team_score)) ? '' : $data->home_team_score,
            "location" => (!isset($data->location)) ? '' : $data->location,
            "start_time" => (!isset($data->start_time)) ? '' : $data->start_time,
					);
					break;
				case 2:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
            "field-set-id" => (!isset($data->id)) ? '' : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name . ' : ' . $data->home_team . ' vs. ' . $data->away_team,
						"name" => (!isset($data->name)) ? '' : $data->name,
						"short_name" => (!isset($data->short_name)) ? '' : $data->short_name,
            "away_players" => (!isset($data->away_players)) ? '' : $data->away_players,
            "away_profile_img" => (!isset($data->away_profile_img)) ? '' : $data->away_profile_img,
            "away_roster_id" => (!isset($data->away_roster_id)) ? '' : $data->away_roster_id,
            "away_team" => (!isset($data->away_team)) ? '' : stat_platform_girl_level(['team_name'=>$data->away_team]),
            "away_team_score" => (!isset($data->away_team_score)) ? '' : $data->away_team_score,
            "court" => (!isset($data->court)) ? '' : $data->court,
            "division" => (!isset($data->division)) ? '' : $data->division,
            "event_id" => (!isset($data->event_id)) ? '' : $data->event_id,
            "form_template" => (!isset($data->form_template)) ? '' : $data->form_template,
            "home_players" => (!isset($data->home_players)) ? '' : $data->home_players,
            "home_profile_img" => (!isset($data->home_profile_img)) ? '' : $data->home_profile_img,
            "home_roster_id" => (!isset($data->home_roster_id)) ? '' : $data->home_roster_id,
            "home_team" => (!isset($data->home_team)) ? '' : stat_platform_girl_level(['team_name'=>$data->home_team]),
            "home_team_score" => (!isset($data->home_team_score)) ? '' : $data->home_team_score,
            "location" => (!isset($data->location)) ? '' : $data->location,
            "start_time" => (!isset($data->start_time)) ? '' : $data->start_time,
					);
					break;
			}
			break;
    case 50: //claiming delete
    case 36: //claiming
      switch( $access[0] ) {
				case 0:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"updatetime" => (!isset($data->updatetime)) ? '' : $data->updatetime,
						"type" => (!isset($data->type)) ? '' : $data->type,
						"target" => (!isset($data->target)) ? '' : $data->target,
						"site_key" => (!isset($data->site_key)) ? '' : $data->site_key,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"status" => (!isset($data->status)) ? '' : $data->status,
						"name" => (!isset($data->name)) ? '' : $data->name,
            "type_name" => (!isset($data->type)) ? '' : (($data->type == 1) ? 'player_names' : 'club_names')
					);
					break;
				case 1:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"updatetime" => (!isset($data->updatetime)) ? '' : $data->updatetime,
						"type" => (!isset($data->type)) ? '' : $data->type,
						"target" => (!isset($data->target)) ? '' : $data->target,
						"site_key" => (!isset($data->site_key)) ? '' : $data->site_key,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"status" => (!isset($data->status)) ? '' : $data->status,
						"name" => (!isset($data->name)) ? '' : $data->name,
            "type_name" => (!isset($data->type)) ? '' : (($data->type == 1) ? 'player_names' : 'club_names')
					);
					break;
				case 2:
					$data_trunc = array(
						"id" => (!isset($data->id)) ? null : $data->id,
						"element_title" => (!isset($data->name)) ? '' : $data->name,
						"updatetime" => (!isset($data->updatetime)) ? '' : $data->updatetime,
						"type" => (!isset($data->type)) ? '' : $data->type,
						"target" => (!isset($data->target)) ? '' : $data->target,
						"site_key" => (!isset($data->site_key)) ? '' : $data->site_key,
						"email" => (!isset($data->email)) ? '' : $data->email,
						"status" => (!isset($data->status)) ? '' : $data->status,
						"name" => (!isset($data->name)) ? '' : $data->name,
            "type_name" => (!isset($data->type)) ? '' : (($data->type == 1) ? 'player_names' : 'club_names')
					);
					break;
			}
			break;
	}
  
	return array_filter($data_trunc, function($data_point_value){ 
    return ($data_point_value !== null && $data_point_value !== '');
  });
}

//format start and end date based on a 'pipe' separated string
function g365_build_dates($dates, $type = 1, $abbv = false, $add_reg = false) {
	//date is undetermined, don't process
	if( strpos($dates, 'TBD') !== false ) return $dates;
  //set default timezone
  date_default_timezone_set('America/Los_Angeles');
	//if the event is only one day, cut most of the processing
  $start_date = $dates;
  //if the date does have the "|" jump to bottom
	if( strpos($dates, '|') !== false ) {
		$dates = explode('|', $dates);
    //if we want just the dates, only first and last
    if( $type === 5 ) {
      return array( date("m-d-y", strtotime($dates[0])), date("m-d-y", strtotime(end($dates))) );
    }
		$start_date = $dates[0];
    if( $type === 4 ) return $start_date;
		$end_date = end($dates);
		$start_month = explode(' ', $start_date);
		$end_month = explode(' ', $end_date);
		if( $start_month[0] != $end_month[0] ) {
			if( end($start_month) != end($end_month) ) {
        $dates = $start_date . ' - ' . $end_date . $type;
			} else {
        if( $type === 3 ){
          $dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . substr($end_month[1], 0, -1);
        } else {
          $dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . $end_month[0] . ' ' . substr($end_month[1], 0, -1);
        }
			}
		} else {
			$start_day = substr($start_month[1], 0, -1);
			$end_day = substr($end_month[1], 0, -1);
			if( $start_day == $end_day ) {
				$dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1);
			} else {
				$dates = $start_month[0] . ' ' . substr($start_month[1], 0, -1) . ' - ' . substr($end_month[1], 0, -1);
			}
		}
		switch( $type ){
			case 1:
				break;
			case 2:
				$dates .= ', ' . end($end_month);
        $dates = preg_replace('/ \- /', '-', $dates);
				break;
			case 3:
				break;
		}
	} else {
		switch( $type ){
			case 1:
				$dates = explode(' ', $dates);
				if( strpos($dates[1], ',') !== false ) $dates[1] = substr($dates[1], 0, -1);
				$dates = $dates[0] . ' ' . $dates[1];
				break;
			case 2:
				break;
			case 3:
				$dates = explode(' ', $dates);
				if( strpos($dates[1], ',') !== false ) $dates[1] = substr($dates[1], 0, -1);
				$dates = $dates[0] . ' ' . $dates[1];
				break;
      case 4:
        return $dates;
        break;
		}
	}
  if( $abbv ) return preg_replace('/([A-Za-z]{3})( |.+? )/', '\1 ', $dates);
  if( $add_reg !== false ) {
    $registration_date = 'No registration deadline.';
    if( $add_reg !== 0 ) {
      $registration_date = date('F d, Y', strtotime('-' . intval($add_reg) . ' days', strtotime($start_date)));
    }
    return array($dates, $registration_date);
  }
	return $dates;
}
//format location info based on 'pipe' separated string
function g365_build_locations($locations, $type = 0) {
//     case 3:
//     case 2:
//     case 1:
//       if( strpos($locations, 'TBD') !== false ) return $locations;
//       $locations = explode('|', $locations);
//       $location_build = array();
//       foreach ($locations as $location) {
//         $loc_parts = explode(",", $location);
//         $acronym = '';
//         foreach (explode(" ", $loc_parts[0]) as $w) {
//           $acronym .= ( ctype_upper($w) ) ? ((empty($acronym)) ? $w : ' ' . $w ) : $w[0];
//         }
//         switch ($type) {
//           case 3:
//             $location_build[] = $acronym;
//             break;
//           case 2:
//             $loc_parts[0] = $acronym;
//             $location_build[] = (( $loc_parts[0] === end($loc_parts) ) ? $acronym : $acronym . ', ' . end($loc_parts));
//             break;
//           case 1:
//             $loc_parts[0] = $acronym;
//             $location_build[] = implode($loc_parts, ', ');
//             break;
//         }
//       }
//       $locations = implode($location_build, ' | ');  
//       break;
  if( empty($locations) ) return $locations;
  switch( $type ) {
    case 3:
    case 2:
      if( strpos($locations, 'TBD') !== false ) return $locations;
      $locations = explode('|', $locations);
      $location_build = array();
      foreach ($locations as $location) {
        $loc_parts = explode(",", $location);
        switch ($type) {
          case 3:
            $location_build[] = $loc_parts[0];
            break;
          case 2:
            $location_build[] = (( $loc_parts[0] === end($loc_parts) ) ? $loc_parts[0] : $loc_parts[0] . ' (' . end($loc_parts) . ')');
            break;
        }
      }
      $locations = implode($location_build, ' | ');  
      break;
    case 1:
      break;
    default:
      $locations = explode('|', $locations);
//       $locations = implode($locations, '<br>');      
      $locations = implode('<br>', $locations);      
  }
	return $locations;
}

//get all org profile data
function g365_get_org_profile($org = null) {
	//make sure we have a value
	if( $org === null ) return 'Need Organization URL to start build.';
	global $wpdb;
	//all the tables we have to get data from
	$wpdb_orgs = $wpdb->g365_orgs;
	//see if we have an id or nickname
  $enabled = ( current_user_can( 'administrator' ) ) ? '' : 'AND enabled = 1';
	if( is_numeric($org) ) {
		$org = intval( $org );
		$data_columns = $wpdb->get_results(
			"SELECT *
			FROM $wpdb_orgs
			WHERE id = $org $enabled;"
		);
	} else {
		$org = sanitize_text_field( $org );
		$data_columns = $wpdb->get_results(
			"SELECT *
			FROM $wpdb_orgs
			WHERE nickname = '$org' $enabled"
		);
	}
	//return message if we can't find player record
	if( empty($data_columns) || !is_array($data_columns) ) return "Couldn't retrieve club profile with the provided information.";
	//Simplify the object for a single record
	$data_columns = $data_columns[0];
	//Extract player id to use in queries
	return $data_columns;
}
//return html social block from associative object
function g365_build_social_block( $social_json = null, $hash_tag = null ){
	if( $social_json === null || !is_object($social_json) ) return 'Need an associative object to build social block.';
	$urls_arr = array(
		'Facebook' => 'https://facebook.com/',
		'Instagram' => 'https://instagram.com/',
		'Twitter' => 'https://twitter.com/',
		'Snapchat' => 'https://snapchat.com/'
	);
	$hash_urls_arr = array(
		'Facebook' => 'https://www.facebook.com/hashtag/',
		'Instagram' => 'https://instagram.com/tags/',
		'Twitter' => 'https://twitter.com/hashtag/'
	);
	$social_block = '<ul id="social-menu" class="menu horizontal" role="menubar">';
	foreach( $social_json as $service => $handle ) {
		$social_block .= '<li class="social-button menu-item" role="menuitem"><a target="_blank" href="' . $urls_arr[$service] . $handle . '"><i class="fi-social-' . strtolower($service) . '"></i> ' . $service . '</a>' . (( $hash_tag === null ) ? '' : ' <a href="' . $hash_urls_arr[$service] . $hash_tag . '" class="hash-tag" target="_blank">#' . $hash_tag . '</a>') . '</li>';
	}
	$social_block .= '</ul>';
	return $social_block;
}

//set profile db info
function g365_set_profile( $player = null, $player_data = null) {
	if( $player_data === null ) return 'Need Player data and data to update.';

	global $wpdb;
	//all the tables we have to get data from
	$wpdb_players = $wpdb->g365_players;
	
	$result = array(
		status	=> 'fail',
		message	=> 'No data written.'
	);
	$result = $wpdb->update(
		$wpdb_players,
		$player_data,
		array( 'id' => intval($player) )
	);

// `account_level` = '1',
// `enabled` = '1',
// `first_name` = 'Keylon',
// `last_name` = 'Kittleson',
// `email` = NULL,
// `phone` = NULL,
// `profile_img` = NULL,
// `address` = NULL,
// `city` = 'Portland',
// `state` = 'WA',
// `zip` = NULL,
// `country` = 'United States',
// `birthday` = '2008-12-03',
// `verified` = '0',
// `tagline` = NULL,
// `grad_year` = NULL,
// `height_ft` = '4',
// `height_in` = '6',
// `weight` = NULL,
// `position` = NULL,
// `social` = NULL,
// `videos` = NULL,
// `notes` = NULL,
// `club_team` = '3170',
// `school` = NULL,
// `gpa` = NULL,
// `sat` = NULL,
// `act` = NULL,
// `nickname` = 'url-string',
	
	//returns success or error message
	return $result;
	
	//if we are testing, return data at this spot
// 	$data_result = array(
// 		'status' => 'success',
// 		'message'=> $player_data
// 	);
// 	echo json_encode($data_result);
// 	die();
	//end return for testing
}

//get claim record(s)
function g365_get_claims($claim_id = null, $type = null, $site = null) {
  //do we have our vars
  if( $claim_id === null && $type === null && $site === null ) return 'Need ID/Type/Site parameters to start.';
	global $wpdb;
	//all the tables we might have to get data from
	$wpdb_claims = $wpdb->g365_claims;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_orgs = $wpdb->g365_orgs;

  $where_string = '';
  if( $claim_id === null ) {
    if( !is_numeric($type) && count($site) !== 3 ) return 'Need correct data to search.';
    //create where string for a player/event record search
    if( !empty($type) ) $where_string .= 'cl.type = ' . intval($type);
    if( !empty($type) && !empty($site) ) $where .= ' AND ';
    if( !empty($site) ) $where_string .= 'cl.site_key LIKE \'' . $site . '\'';
  } else {
  	if( (!is_numeric($claim_id) || intval($claim_id) < 1) && !is_array($claim_id)  ) return 'Need valid numeric claim id(s).';
    //if $type is null then make it 0
    if( $type === null ) $type = 0;
    //create where string for a stat id
    if( is_array($claim_id) ) {
      //write nice 'where...in' string
    } else {
      $where_string .= 'cl.id = ' . intval($claim_id);
    }
  }
  switch( $type ){
    case 0:
      if( $where_string != '' ) $where_string = 'WHERE ' . $where_string;
      $data_columns = $wpdb->get_results(
        "SELECT cl.*,
        CASE
        WHEN cl.type = 1 THEN pl.name
        WHEN cl.type = 2 THEN org.name
        END as name,
        CASE
        WHEN cl.type = 1 THEN pl.access
        WHEN cl.type = 2 THEN org.access
        END as access,
        CASE
        WHEN cl.type = 1 THEN pl.birthday
        -- Add other cases as needed
        END as birthday
        FROM $wpdb_claims AS cl
        LEFT JOIN $wpdb_players AS pl ON cl.target=pl.id
        LEFT JOIN $wpdb_orgs AS org ON cl.target=org.id
        $where_string
        ORDER BY cl.updatetime DESC;"
      );
      break;
    case 1:
      $data_columns = $wpdb->get_results(
        "SELECT cl.*, pl.name
        FROM $wpdb_claims AS cl
        LEFT JOIN $wpdb_players AS pl ON cl.target=pl.id
        WHERE $where_string;"
      );
      break;
    case 2:
      $data_columns = $wpdb->get_results(
        "SELECT cl.*, org.name
        FROM $wpdb_claims AS cl
        LEFT JOIN $wpdb_orgs AS org ON cl.target=org.id
        WHERE $where_string;"
      );
      break;
  }
	//return message if we can't find record
	if( empty($data_columns) ) return "No claim records exist.";
	return ( count($data_columns) === 1 && $claim_id !== null) ? $data_columns[0] : $data_columns;
}

//get single stat record
function g365_get_stat($stat = null, $player = null, $event = null) {
  //do we have a $stat or are we searching for a type
  if( $stat === null && $player === null && $event === null ) return 'Need Stat/Player/Event parameters to start.';
	global $wpdb;
	//all the tables we have to get data from
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_positions = $wpdb->g365_positions;

  $where_string = '';
  if( $stat === null ) {
    if( !is_numeric($player) || !is_numeric($event) || intval($player) < 0 || intval($event) < 0 ) return 'Need numeric player and event ids.';
    //create where string for a player/event record search
    $where_string = 'stats.player = ' . intval($player) . ' AND stats.event = ' . intval($event);
  } else {
  	if( !is_numeric($stat) || intval($stat) < 1 ) return 'Need valid numeric stat id.';
    //create where string for a stat id
    $where_string = 'stats.id = ' . intval($stat);
  }
  $data_columns = $wpdb->get_results(
    "SELECT stats.id, stats.player, stats.event, stats.updatetime, stats.enabled, stats.profile_img AS event_profile_img, stats.evaluation, stats.strengths, stats.weaknesses, stats.stats,
    stats.trends, stats.video, player.enabled as enabled_player, player.name, player.first_name, player.last_name, player.email,
      player.phone, player.profile_img, player.address, player.city, player.state, player.zip, player.country, player.birthday, player.verified, player.tagline,
      player.grad_year, player.height_ft, player.height_in, player.weight, pos.name AS position_name, pos.id AS position_id, player.social, player.videos, player.notes,
      org_school.name AS school_name, org_school.id AS school_id, org_school.nickname AS school_url, player.gpa, player.sat, player.act, player.nickname, player.access,
      org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb, org_club.id as club_id,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.jersey_size')) as jersey_size,
      events.name AS event_name, events.short_name AS event_short, events.stats AS event_stats, events.trends AS event_trends
    FROM $wpdb_stats AS stats
    LEFT JOIN $wpdb_players AS player ON stats.player=player.id
    LEFT JOIN $wpdb_positions AS pos ON player.position=pos.id
    LEFT JOIN $wpdb_orgs AS org_school ON player.school=org_school.id
    LEFT JOIN $wpdb_orgs AS org_club ON player.club_team=org_club.id
    LEFT JOIN $wpdb_events AS events ON stats.event=events.id
    WHERE $where_string;"
  );
  //if we get no data back and are doing a player/event search add the player/event data and return
  if( empty($data_columns) && $stat === null ) {
    $data_columns = (object)[];
    $pl_id = intval($player);
    $ev_id = intval($event);
    $data_columns->player = $wpdb->get_results(
      "SELECT plrec.id AS player, plrec.enabled, plrec.name, plrec.first_name, plrec.last_name, plrec.email, plrec.phone, plrec.profile_img, plrec.address, plrec.city, plrec.state,
      plrec.zip, plrec.country, plrec.birthday, plrec.verified, plrec.tagline, plrec.grad_year, plrec.height_ft, plrec.height_in, plrec.weight, pos.name AS position_name,
      pos.id AS position_id, plrec.social, plrec.videos, org_school.name AS school_name, org_school.id AS school_id, org_school.nickname AS school_url, plrec.gpa,
      plrec.sat, plrec.act, plrec.nickname, plrec.access, org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb, org_club.id as club_id,
      JSON_UNQUOTE(JSON_EXTRACT(plrec.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(plrec.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(plrec.notes, '$.jersey_size')) as jersey_size
      FROM $wpdb_players AS plrec
      LEFT JOIN $wpdb_positions AS pos ON plrec.position=pos.id
      LEFT JOIN $wpdb_orgs AS org_school ON plrec.school=org_school.id
      LEFT JOIN $wpdb_orgs AS org_club ON plrec.club_team=org_club.id
      WHERE plrec.id = $pl_id;"
    );
    $data_columns->event = $wpdb->get_results(
      "SELECT evrec.id as event, evrec.name AS event_name, evrec.short_name AS event_short, evrec.stats AS event_stats, evrec.trends AS event_trends
      FROM $wpdb_events AS evrec
      WHERE evrec.id = $ev_id;"
    );
    if( empty($data_columns->player) || empty($data_columns->event) ) {
      $data_columns = '';
    } else {
      $data_columns = array( (object) array_merge((array) $data_columns->player[0], (array) $data_columns->event[0]) );
    }
  }
	//return message if we can't find record
	if( empty($data_columns) ) return "Couldn't retrieve data for these parameters.";
	return $data_columns = $data_columns[0];
}

//get single team stat record
function g365_get_team_stat($team_stat = null, $team = null, $event = null) {
  //do we have a $stat or are we searching for a type
  if( $team_stat === null && $team === null && $event === null ) return 'Need Team Stat/Team/Event parameters to start.';
	global $wpdb;
	//all the tables we have to get data from
	$wpdb_team_stats = $wpdb->g365_team_stats;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_teams = $wpdb->g365_teams;

  $where_string = '';
  if( $team_stat === null ) {
    if( !is_numeric($team) || !is_numeric($event) || intval($team) < 0 || intval($event) < 0 ) return 'Need numeric team and event ids.';
    //create where string for a team/event record search
    $where_string = 'team_stats.team = ' . intval($team) . ' AND team_stats.event = ' . intval($event);
  } else {
  	if( !is_numeric($team_stat) || intval($team_stat) < 1 ) return 'Need valid numeric team stat id.';
    //create where string for a team stat id
    $where_string = 'team_stats.id = ' . intval($team_stat);
  }
  $data_columns = $wpdb->get_results(
    "SELECT team_stats.id, team_stats.team, team_stats.event, team_stats.updatetime, team_stats.enabled, team_stats.profile_img AS event_profile_img, team_stats.evaluation, team_stats.strengths, team_stats.weaknesses, team_stats.stats,
    team_stats.trends, team_stats.video, team.enabled as enabled_team, team.name, team.search_list,
      events.name AS event_name, events.short_name AS event_short, events.stats AS event_stats, events.trends AS event_trends
    FROM $wpdb_team_stats AS team_stats
    LEFT JOIN $wpdb_teams AS team ON team_stats.team=team.id
    LEFT JOIN $wpdb_events AS events ON team_stats.event=events.id
    WHERE $where_string;"
  );
  //if we get no data back and are doing a team/event search add the team/event data and return
  if( empty($data_columns) && $team_stat === null ) {
    $data_columns = (object)[];
    $tm_id = intval($team);
    $ev_id = intval($event);
    $data_columns->team = $wpdb->get_results(
      "SELECT tmrec.id AS team, tmrec.enabled, tmrec.name
      FROM $wpdb_teams AS tmrec
      WHERE tmrec.id = $tm_id;"
    );
    $data_columns->event = $wpdb->get_results(
      "SELECT evrec.id as event, evrec.name AS event_name, evrec.short_name AS event_short, evrec.stats AS event_stats, evrec.trends AS event_trends
      FROM $wpdb_events AS evrec
      WHERE evrec.id = $ev_id;"
    );
    if( empty($data_columns->team) || empty($data_columns->event) ) {
      $data_columns = '';
    } else {
      $data_columns = array( (object) array_merge((array) $data_columns->team[0], (array) $data_columns->event[0]) );
    }
  }
	//return message if we can't find record
	if( empty($data_columns) ) return "Couldn't retrieve data for these parameters.";
	return $data_columns = $data_columns[0];
}

//get stats for event or player
function g365_get_stats($player = null, $event = null, $status = 1, $order = 'stats.updatetime DESC', $ids = null, $type = null) {
  //do we have a $stat or are we searching for a type
  if( $player !== null && $event !== null && $ids === null ) return 'Parameters too specific.';
  if( !is_numeric($player) && !is_numeric($event) && intval($player) < 1 && intval($event) < 1 && $ids === null ) return 'Need numeric player/event ids.';
  
	global $wpdb;
	//all the tables we have to get data from
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_positions = $wpdb->g365_positions;
  
  //create where string for a player/event record search
  if( $ids !== null && is_array($ids) ) {
    $where_string = 'stats.id IN (' . implode(',', $ids) . ')';
  } else {
    $where_string = ( $player === null ) ? 'stats.event = ' . intval($event) : 'stats.player = ' . intval($player);
  }
  if( $type !== null ) {
    if( $type === 'camps' || $type === 'dcp_player_registration' ) {
      $where_string .= ' AND stats.game = 0';
    } else {
      $where_string .= ' AND stats.game != 0';
    }
  }
  
  $event_scope_check = g365_get_event_data($event);
  $org_value = $event_scope_check->org;
  
  
  if( $status !== '0-1' ) {$where_string .= ' AND stats.enabled = ' . $status;}
  if($org_value == 8437){
    $where_string = "JSON_UNQUOTE(JSON_EXTRACT(stats.trends, '$.ss_event_participated')) = '$event'";
  }
  
//   echo("<script>console.table('data_columns: " . print_r($where_string) . " ');</script>");
  
  $data_columns = $wpdb->get_results(
    "SELECT stats.id, stats.player, stats.event, stats.updatetime, stats.enabled AS st_enabled, stats.profile_img as event_profile_img, stats.evaluation, stats.strengths, stats.weaknesses, stats.stats,
    stats.trends, stats.video, player.enabled, player.name, player.first_name, player.last_name, player.email,
      player.phone, player.profile_img, player.address, player.city, player.state, player.zip, player.country, player.birthday, player.verified, player.tagline,
      player.grad_year, player.height_ft, player.height_in, player.school AS player_school, player.weight, pos.name AS position_name, pos.id AS position_id, player.social, player.videos, player.notes,
      org_school.name AS school_name, org_school.id AS school_id, org_school.nickname AS school_url, player.gpa, player.sat, player.act, player.nickname, player.access,
      org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb, org_club.id as club_id,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.jersey_size')) as jersey_size,
      events.name AS event_name, events.short_name AS event_short
    FROM $wpdb_stats AS stats
    LEFT JOIN $wpdb_players AS player ON stats.player=player.id
    LEFT JOIN $wpdb_positions AS pos ON player.position=pos.id
    LEFT JOIN $wpdb_orgs AS org_school ON player.school=org_school.id
    LEFT JOIN $wpdb_orgs AS org_club ON player.club_team=org_club.id
    LEFT JOIN $wpdb_events AS events ON stats.event=events.id
    WHERE $where_string
    ORDER BY $order;",
    OBJECT_K
  );
	//return message if we can't find records
	if( empty($data_columns) ) return "Couldn't retrieve stats for these player and/or event ids.";
	return $data_columns;
}

function g365_get_team_stats($team = null, $event = null, $status = 1, $order = 'team_stats.updatetime DESC', $ids = null, $type = null) {
  //do we have a $team_stat or are we searching for a type
  if( $team !== null && $event !== null && $ids === null ) return 'Parameters too specific.';
  if( !is_numeric($team) && !is_numeric($event) && intval($team) < 1 && intval($event) < 1 && $ids === null ) return 'Need numeric team/event ids.';
  
  global $wpdb;
  //all tables we have to get data from
  $wpdb_team_stats = $wpdb->g365_team_stats;
  $wpdb_events = $wpdb->g365_events;
  $wpdb_teams = $wpdb->g365_teams;
  
  //create where string for a team/event record search
  if( $ids !== null && is_array($ids) ) {
    $where_string = 'team_stats.id IN (' . implode(',', $ids) . ')';
  } else {
    $where_string = ( $team === null ) ? 'team_stats.event = ' . intval($event) : 'team_stats.team = ' . intval($team);
  }
  if( $type !== null ) {
    if( $type === 'camps' ) {
      $where_string .= ' AND team_stats.game = 0';
    } else {
      $where_string .= ' AND team_stats.game != 0';
    }
  }
  if( $status !== '0-1' ) $where_string .= ' AND team_stats.enabled = ' . $status;
  $data_columns = $wpdb->get_results(
    "SELECT team_stats.id, team_stats.team, team_stats.event, team_stats.updatetime, team_stats.enabled AS tm_enabled, 
            team_stats.profile_img as event_profile_img, team_stats.evaluation, team_stats.strengths, team_stats.weaknesses, 
            team_stats.stats, team_stats.trends, team_stats.video, 
            teams.enabled, teams.name, teams.team_type, teams.org, teams.search_list, 
            events.name AS event_name, events.short_name AS event_short
     FROM $wpdb_team_stats AS team_stats
     LEFT JOIN $wpdb_teams AS teams ON team_stats.team=teams.id
     LEFT JOIN $wpdb_events AS events ON team_stats.event=events.id
     WHERE $where_string
     ORDER BY $order;",
     OBJECT_K
  );
  return $data_columns;
}

function g365_get_roster($roster = null, $truncate = false) {
  //$roster should be an associative array
  if( $roster === null ) return 'Need Roster parameters to start.';
	if( !is_numeric($roster) ) return 'Need numeric id.';

	global $wpdb;
	//all the tables we have to get data from
	$wpdb_rosters = $wpdb->g365_rosters;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_teams = $wpdb->g365_teams;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_coaches = $wpdb->g365_coaches;
	$wpdb_players = $wpdb->g365_players;
  
  $roster = intval( $roster );
  
  $data_columns = $wpdb->get_results(
    "SELECT roster.id, roster.updatetime, roster.enabled, roster.org AS org_id, roster.team AS team_id, roster.event AS event_id, @level_ref:=roster.level AS level, roster.division,
    roster.team_type, roster.coach AS coach_id, roster.asst AS asst_id, roster.players AS player_names, roster.description, roster.events AS event_names,
    orgs.name AS org_name, orgs.abbreviation as org_abbr, teams.name AS team_name, teams.level AS team_level, ev.name AS event_name, ev.short_name AS event_short, JSON_EXTRACT(ev.divisions, CONCAT('$.\"', @level_ref, '\"')) AS event_division,
    JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.team_restrictions')) as team_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.date_restrictions')) as date_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_name')) as pool_name,
    JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_number')) as pool_number, CONCAT(coaches.first_name,' ',coaches.last_name) AS coach_name, CONCAT(assts.first_name,' ',assts.last_name) AS asst_name
    FROM $wpdb_rosters AS roster
    LEFT JOIN $wpdb_orgs AS orgs ON roster.org=orgs.id
    LEFT JOIN $wpdb_teams AS teams ON roster.team=teams.id
    LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
    LEFT JOIN $wpdb_coaches AS coaches ON roster.coach=coaches.id
    LEFT JOIN $wpdb_coaches AS assts ON roster.asst=assts.id
    WHERE roster.id = $roster;"
  );


	//return message if we can't find record
	if( empty($data_columns) ) return "Couldn't retrieve roster for this id.";
	//Simplify the object for a single record
	$data_columns = $data_columns[0];
	//only return basic data
	if( $truncate )	return $data_columns;

  $lock_vals = g365_roster_lock_vals( $data_columns->level, $data_columns->event_division, $data_columns->division );
  $data_columns->division_selector_birth_lock = $lock_vals['division_selector_birth_lock'];
  $data_columns->division_selector_class_lock = $lock_vals['division_selector_class_lock'];
  $data_columns->event_division = $lock_vals['event_division'];
  $data_columns->division_selector_lock_type = $lock_vals['division_selector_lock_type'];
//           var cont_date = new Date();
//           //which school year
//           var cont_target_year = (cont_date.getMonth() < 8) ? cont_date.getFullYear()-1 : cont_date.getFullYear();
//           //divison
//           var cont_division = parseInt(contribution_part_option.attr('data-g365_ref_val'));
//           //add the minimum birth year for players
//           contributions_compile[cont_id + '_birth_lock'] = "> '" + (cont_target_year - cont_division) + "-08-30'" + '-OR';
//           //add the minimum class for players
//           contributions_compile[cont_id + '_class_lock'] = '> ' + (cont_target_year + 18 - cont_division) + '-OR';
//           //add the lock type
//           contributions_compile[cont_id + '_lock_type'] = contribution_part_option.attr('data-g365_add_data');

  

  //Format player ids to use in queries
  $pl_ids_raw = json_decode($data_columns->player_names);
  $pl_ids = g365_validate_ids(array_keys((array)$pl_ids_raw));
  //exit if we had a bad players format
  if( empty($pl_ids) ) {
    $data_columns->player_names = null;
  } else {
    $query_where = g365_build_where(array('id'=>$pl_ids));
    //Grab player data and add them to the tree
    $data_columns->players_full = $wpdb->get_results(
      "SELECT id, name, birthday, city, state, profile_img, nickname AS url, verified
      FROM $wpdb_players
      $query_where
      ORDER BY name;",
      OBJECT_K
    );
    if( $data_columns->division_selector_birth_lock !== '' ) {
      $birth_lock = strtotime($birth_lock);
      foreach( $pl_ids_raw as $id_num => &$player_roster_data ) { if( strtotime($data_columns->players_full[$id_num]->birthday) < $birth_lock ) $player_roster_data->exception = ' data-g365_exception'; }
      $data_columns->player_names = json_encode($pl_ids_raw);
    }
    foreach($data_columns->players_full as $id_num => &$player_data) {
      unset($player_data->birthday);
      $player_data->element_title = $player_data->name;
    }
  }
	
  //Format event ids to use in queries
  $ev_ids = g365_validate_ids(array_keys((array)json_decode($data_columns->event_names)));
  //exit if we had a bad event format
  if( empty($ev_ids) ) {
    $data_columns->events_full = null;
  } else {
    $query_where = g365_build_where(array('id'=>$ev_ids));
    //Grab player data and add them to the tree
    $data_columns->events_full = $wpdb->get_results(
      "SELECT id, DATE_FORMAT(eventtime, '%M %d, %Y') AS eventtime, name, short_name, dates
      FROM $wpdb_events
      $query_where
      ORDER BY eventtime;",
      OBJECT_K
    );
  }
	return $data_columns;  
}


function g365_get_rosters( $id_data = null, $truncate = false, $admin_switch = false ) {  //cronos working here
//   $search_types = array(
//     'org',
//     'team',
//     'event',
//     'level',
//     'division',
//     'name',
//     'team_type'
//   );
  //hard coded for event_id
  if( $id_data === null || !is_array($id_data) ) return 'Need Roster parameters to start.';
	if( !is_numeric($id_data['event_id']) && !is_numeric($id_data['org_id']) && !isset($id_data['unlock']) ) return 'Need numeric id.';

	global $wpdb;
	//all the tables we have to get data from
	$wpdb_rosters = $wpdb->g365_rosters;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_teams = $wpdb->g365_teams;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_coaches = $wpdb->g365_coaches;
	$wpdb_players = $wpdb->g365_players;
  
  $where_string = array();
  if( !current_user_can( 'administrator' ) ) $where_string[] = 'roster.enabled = 1';
  
  if( is_numeric($id_data['org_id']) ) $where_string[] = 'roster.org = ' . intval( $id_data['org_id'] );
  if( is_numeric($id_data['event_id']) ) $where_string[] = 'roster.event = ' . intval( $id_data['event_id'] );
  if( is_numeric($id_data['level']) ) $where_string[] = 'roster.level = ' . intval( $id_data['level'] );
  if( is_numeric($id_data['team_id']) ) $where_string[] = 'roster.team = ' . intval( $id_data['team_id'] );
  $where_string = implode(' AND ', $where_string);
  if( !empty($where_string) ) $where_string = 'WHERE ' . $where_string;
  
  if( $id_data['order_by_master'] ) {
    $order_by = $id_data['order_by_master'];
  } else {
    $order_by = (( isset($id_data['order_by']) ) ? ('roster.' . $id_data['order_by']) : 'roster.level');
    $order_by .= (( $id_data['order_direction'] === 'ASC' ) ? ' ASC' : ' DESC');
  }
  
  $limit = '';
  if( isset($id_data['pg_no']) ) {
    $pg_no = intval($id_data['pg_no']);
    $per_pg = ( isset($id_data['per_pg']) ) ? intval($id_data['per_pg']) : 50;
    $offset = (($pg_no - 1) * $per_pg);
    $limit = "LIMIT $offset, $per_pg";
  }
  //add team and time restriction data for admin use
  if( current_user_can( 'administrator' ) && $admin_switch ) {
    $data_columns = $wpdb->get_results(
      "SELECT roster.id, teams.createdate AS team_createdate, roster.updatetime, roster.enabled, roster.org AS org_id, roster.team AS team_id, roster.event AS event_id, @level_ref:=roster.level AS level, roster.division,
      roster.team_type, roster.coach AS coach_id, roster.asst AS asst_id, roster.players, roster.description, roster.events AS event_names, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.attendance')) AS attendance,
      orgs.name AS org_name, orgs.abbreviation as org_abbr, CONCAT(orgs.director_first, ' ', orgs.director_last) AS director_name, orgs.director_phone AS director_phone, teams.name AS team_name, teams.level AS team_level, ev.name AS event_name, ev.short_name AS event_short, JSON_EXTRACT(ev.divisions, CONCAT('$.\"', @level_ref, '\"')) AS event_division,
      JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.team_restrictions')) as team_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.date_restrictions')) as date_restrictions, JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_name')) as pool_name,
      JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_number')) as pool_number, coaches.id AS coach_id, CONCAT(coaches.first_name,' ',coaches.last_name) AS coach_name, coaches.phone AS coaches_phone, CONCAT(assts.first_name,' ',assts.last_name) AS asst_name
      FROM $wpdb_rosters AS roster
      LEFT JOIN $wpdb_orgs AS orgs ON roster.org=orgs.id
      LEFT JOIN $wpdb_teams AS teams ON roster.team=teams.id
      LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
      LEFT JOIN $wpdb_coaches AS coaches ON roster.coach=coaches.id
      LEFT JOIN $wpdb_coaches AS assts ON roster.asst=assts.id
      $where_string 
      ORDER BY $order_by $limit;"
    );
  } else {
    $data_columns = $wpdb->get_results(
      "SELECT roster.id, teams.createdate AS team_createdate, roster.updatetime, roster.enabled, roster.org AS org_id, roster.team AS team_id, roster.event AS event_id, @level_ref:=roster.level AS level, roster.division,
      roster.team_type, roster.coach AS coach_id, roster.asst AS asst_id, roster.players, roster.description, roster.events AS event_names,
      orgs.name AS org_name, orgs.abbreviation as org_abbr, teams.name AS team_name, teams.level AS team_level, ev.name AS event_name, ev.short_name AS event_short, JSON_EXTRACT(ev.divisions, CONCAT('$.\"', @level_ref, '\"')) AS event_division,
      CONCAT(coaches.first_name,' ',coaches.last_name) AS coach_name, CONCAT(assts.first_name,' ',assts.last_name) AS asst_name
      FROM $wpdb_rosters AS roster
      LEFT JOIN $wpdb_orgs AS orgs ON roster.org=orgs.id
      LEFT JOIN $wpdb_teams AS teams ON roster.team=teams.id
      LEFT JOIN $wpdb_events AS ev ON roster.event=ev.id
      LEFT JOIN $wpdb_coaches AS coaches ON roster.coach=coaches.id
      LEFT JOIN $wpdb_coaches AS assts ON roster.asst=assts.id
      $where_string
      ORDER BY $order_by $limit;"
    );
  }
//     ORDER BY updatetime DESC
//     LIMIT $offset, $per_pg;"
	//return message if we can't find record
	if( empty($data_columns) ) return "Couldn't retrieve roster for this id.";
  //set the return array
  $data_return = array($data_columns);
	//only return basic data
	if( $truncate )	return $data_return;
	//Format player ids, event ids, and locking to use in queries
  $ev_ids = array();
  $pl_ids = array();
  //constants for calculating age locking 
  $today = date("Y-m-d");
  $cont_target_year = (date('Y', strtotime($today)) - (( intval(date('n', strtotime($today))) > 8 ) ? 0 : 1 ));
  $level_mapping = [
    "8" => "8",
    "9" => "9",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "47" => "17",
    "46" => "16",
    "45" => "15",
    "44" => "14",
    "43" => "13",
    "42" => "12",
    "41" => "11",
    "40" => "10",
  ];

  //loop through all results to make remaining queries
  foreach( $data_columns as $id_num => $roster_row ) {
    if( !is_null($roster_row->players) ) {
      $pl_keys = g365_validate_ids(array_keys((array)json_decode($roster_row->players)));
      if( is_array($pl_keys) ) $pl_ids = array_merge($pl_ids, $pl_keys);
      //parse the attendance data if we are adding kids to the mix
      if( !empty($roster_row->attendance) ) $roster_row->attendance = json_decode($roster_row->attendance);
    }
    if( !is_null($roster_row->event_names) ) {
      $ev_keys = g365_validate_ids(array_keys((array)json_decode($roster_row->event_names)));
      if( is_array($ev_keys) ) $ev_ids = array_merge($ev_ids, $ev_keys);
    }
    if (isset($level_mapping[$roster_row->level])) {
      $roster_level = $level_mapping[$roster_row->level];
    }
    //all rosters are locked by their own level
    $birth_lock = ($cont_target_year - $roster_level) . "-08-15";
    $roster_row->division_selector_birth_lock = "> '" . $birth_lock . "'-OR";
    $roster_row->division_selector_class_lock = '> ' . ($cont_target_year + 18 - $roster_level) . '-OR';
    
    //get event divison type
    if( empty($roster_row->event_division) || $roster_row->event_division === 0 ) {
      $roster_row->division_selector_lock_type = 0;
    } else {
      $roster_row->event_division = json_decode($roster_row->event_division);
      $roster_row->division_selector_lock_type = ( ( is_object($roster_row->event_division) ) ? $roster_row->event_division->{$roster_row->division} : 1 );
    }
  }
  
  //exit if we had a bad players format
  if( empty($pl_ids) ) {
    $data_return[] = null;
  } else {
    //Grab player data and add them to the tree
    //change query if admin data and variable
    if( current_user_can( 'administrator' ) && $admin_switch ) {
      //build the where string for however many players we need to get
      $query_where = g365_build_where(array('pl.id'=>$pl_ids));
      $players_full = $wpdb->get_results(
        "SELECT pl.id, pl.name, pl.city, pl.state, pl.profile_img, pl.birthday, pl.grad_year, pl.nickname AS url, pl.verified, JSON_UNQUOTE(JSON_EXTRACT(pl.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(pl.notes, '$.recard_img')) as recard_img, pl.access, st.stats as unlock_data
        FROM $wpdb_players AS pl
        LEFT JOIN $wpdb_stats AS st ON st.player = pl.id AND st.event = 504
        $query_where
        ORDER BY name;",
        OBJECT_K
      );
    } else {
      //build the where string for however many players we need to get
      $query_where = g365_build_where(array('id'=>$pl_ids));
      $players_full = $wpdb->get_results(
        "SELECT id, name, city, state, profile_img, birthday, grad_year, nickname AS url, verified
        FROM $wpdb_players
        $query_where
        ORDER BY name;",
        OBJECT_K
      );
    }
    $data_return[] = $players_full;
  }
  
  //exit if we had a bad events format
  if( empty($ev_ids) ) {
    $data_return[] = null;
  } else {
    $query_where = g365_build_where(array('id'=>$ev_ids));
    //Grab event data and add them to the tree
    $data_return[] = $wpdb->get_results(
      "SELECT id, DATE_FORMAT(eventtime, '%m/%d/%y') AS eventtime, name, short_name, dates, locations, short_locations, nickname AS url 
      FROM $wpdb_events
      $query_where
      ORDER BY eventtime;",
      OBJECT_K
    );
  }
	return $data_return;  
}

//get players
function g365_get_players($pg_no = 1, $per_pg = 50) {
	global $wpdb;
	$wpdb_players = $wpdb->g365_players;
	//create pagination offsets
  $offset = (($pg_no - 1) * $per_pg);
  return $data_columns = $wpdb->get_results(
    "SELECT * FROM $wpdb_players
    ORDER BY name ASC
    LIMIT $offset, $per_pg;"
  );
}
//get player verifications
function g365_get_players_verify($ver_level = 1, $pg_no = 1, $per_pg = 50, $order_by = 'player.updatetime DESC', $change_compare = '=') {
	global $wpdb;
	$wpdb_players = $wpdb->g365_players;
	//create pagination offsets
  $offset = (($pg_no - 1) * $per_pg);
  $ver_level = ( $ver_level === null || $ver_level === '' ) ? 1 : intval( $ver_level );
  $attachement_filter = g365_get_attachement_filter($ver_level, true);
  $real_ver_level = $ver_level > 1 ? $ver_level - 1 : $ver_level;
  return $data_columns = $wpdb->get_results(
    "SELECT player.id, player.updatetime, player.account_level, player.enabled, player.name, player.profile_img, player.birthday, player.verified, player.grad_year,
    JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img
    FROM $wpdb_players AS player
    WHERE player.verified $change_compare $real_ver_level $attachement_filter
    ORDER BY $order_by
    LIMIT $offset, $per_pg;"
  );
}

//function for formatting date data by day
// function get_att_val($val_type, $event_id){
//   $pl_ev_att = '{"'.$event_id.'": ["'.$val_type.'"]}';
//   $pl_ev_att = htmlspecialchars($pl_ev_att, ENT_QUOTES);
//   return array("att_day"=>array($val_type), "res_result" => $pl_ev_att);
// }
// JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) IS NOT NULL
function g365_get_attachement_filter($ver_level, $add_and = false){
  $attachement_filter = '';
  $ver_level = intval($ver_level);
  if($ver_level === 1){
    $attachement_filter .= " JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) IS NULL AND JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) IS NULL ";
  }else if($ver_level === 2){
    $attachement_filter .= " (JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) IS NOT NULL OR JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) IS NOT NULL) ";
  } else{
    return '';
  }
  return ($add_and ? ' AND ': '') . $attachement_filter;
}

//checks to see if the date string is valid as a date
function g365_validate_date($date, $format = 'Y-m-d') {
  $d = DateTime::createFromFormat($format, $date);
  // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
  return $d && $d->format($format) === $date;
}

//function for checking status of player passport with regard to a time or event
function g365_stat_set_lock( $player_id = null, $lock_action = null, $unlock_start = null, $unlock_end = null, $event_id = null ) {
  //if we don't have the proper vars, get out
  if( empty($player_id) || (!is_numeric($player_id) && !is_array($player_id)) || empty($lock_action) ) return 'Need Player ID and lock type to proceed.';
  if( (empty($unlock_start) && empty($unlock_end)) && empty($event_id) ) return 'Need timeframe to proceed.';
  
  //figure out if we are unlocking an event or a season
  if( $event_id === null ) {
    
    //set timezone for all calculations
    $time_zone = new DateTimeZone('America/Los_Angeles');

    //figure out if we are being instructed to lock or unlock specific date ranges
    if( $unlock_end === null ) {
      //'current' is the key word for the current season
      if( $unlock_start === 'current' ) {
        $unlock_start_yr = new DateTime("now", $time_zone);
  //       $unlock_start = date("Y-m-d");
        //now get the real start and end times for the current season
        $unlock_start_yr = (intval($unlock_start_yr->format('Y')) - ((intval($unlock_start_yr->format('n')) > 8) ? 0 : 1));
      } elseif( is_numeric($unlock_start) ) {
        //if the start time is just a year, process it
        $unlock_start_yr = intval($unlock_start);
        
//         return intval( new DateTime( 'now', $time_zone)->format('Y') );
        
        //a check to make sure it's within range
        if( $unlock_start_yr < 1980 || $unlock_start_yr > intval( date('Y', strtotime('+2 years')) ) ) return 'Unlock date range too ambiguous.';
      } else {
        //if we didn't get a properly formatted string, return error
        if( !g365_validate_date($unlock_start) ) return 'Need valid start date to proceed.';
        //make a time from the string
        $unlock_start_yr = new DateTime( $unlock_start, $time_zone);
        $unlock_start_yr = (intval($unlock_start_yr->format('Y')) - ((intval($unlock_start_yr->format('n')) > 8) ? 0 : 1));
      }
      $unlock_start = new DateTime( ($unlock_start_yr . '-09-01') );
      $unlock_end = new DateTime( (($unlock_start_yr + 1) . '-08-31') );

  //     $stats_result = ( $wpdb->query( "UPDATE $wpdb_stats SET " . substr($sql_prepare_query, 2) . " WHERE id = $new_data[id];" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Stat locking update error.') : 'Stat locking update error.') : "Stat locking successfully updated.";
    } else {
      //if we have a specific end date, just process exactly what is provided.
      //if we didn't get a properly formatted string, return error
      if( !g365_validate_date($unlock_start) || !g365_validate_date($unlock_end) ) return 'Need valid start and end date to proceed.';
      //make a time from the start string
      $unlock_start = new DateTime($unlock_start, $time_zone);
      $unlock_start = $unlock_start->format('Y-m-d');
      //make a time from the end string
      $unlock_end = new DateTime($unlock_end, $time_zone);
      $unlock_end = $unlock_end->format('Y-m-d');
    }
  } else {
    //the process for events
  }
  
  //databases we need
  global $wpdb;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
  
  //process the rest of the variables
  if( is_array($player_id) ) {
    $player_id = '= ' . intval($player_id);
  } else {
    $player_id = 'IN (' . implode(',', $player_id) . ')';
  }
  $lock_action = ( $lock_action === 'unlock' ) ? 1 : 0;
  
  //now let's try to write to the db
  return ( $wpdb->query( "UPDATE $wpdb_stats SET lock_stat = $lock_action WHERE player $player_id AND event IN (SELECT id FROM $wpdb_events WHERE eventtime BETWEEN '$unlock_start 00:00:00' AND '$unlock_end 23:59:59' );" ) === false ) ? (( strpos( site_url(), 'dev' ) !== false ) ? g365_output_db_error('Lock set error.') : 'Lock set error.') : "Lock set successful.";
}

//function for checking status of player passport with regard to a time or event
function g365_player_unlock_status( $player_id = null, $player_unlock = null, $event_id = null, $target_time = null ) {
  //if we are missing the player data abort
  if( empty($player_id) && empty($player_unlock) ) return array( 'N/A', 'No unlock');
  
  global $wpdb;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;

  //process to get the data we need if it's not supplied
  if( empty($player_unlock) ) {
    $player_unlock = $wpdb->get_var( "SELECT stats FROM $wpdb_stats WHERE player = $player_id AND event = 504;" );
    if( $player_unlock === false ) return array( 'N/A', 'No Stat');
  }
  //if we don't have any event data, assume that it's right now
  if( empty($target_time) ) {
    if( empty($event_id) ) {
      $target_time = date("Y-m-d H:i:s");
    } else {
      $target_time = $wpdb->get_var( "SELECT eventtime FROM $wpdb_events WHERE id = $event_id;" );
      if( $target_time === false ) return array( 'N/A', 'No time');
    }
  }
  $json_player_unlock = $player_unlock;
  //now make sure the data is usable and parse it out
  if( !is_object($player_unlock) ) $player_unlock = json_decode($player_unlock);
  //process the time string
  $target_time = strtotime($target_time);
  //see if the season target has to change based on when the target data is
  $target_year = (intval(date('Y', $target_time)) - ((intval(date('n', $target_time)) < 9) ? 1 : 0));
  $current_year = wp_date('Y');
  $subscription_data = g365_passport_validation('tounament-manager', ['tounament_pl_pp_data'=>$json_player_unlock]);
  $unset_date = date('m/d/y', strtotime(end($subscription_data['yearly_subscription_purchased_date'])));
  //if see if we have a season that covers this query
  // DD: Add second layer of validation to check if the passport is still within 12 months of subscription period
  $check_player_passport_validation = g365_passport_validation('subscription-validation', ['selected_year'=>$current_year, 'pp_data'=>$subscription_data]);
  if( isset($player_unlock->seasons->$target_year) ) {
    //if we have a season active, then send the confirmation back
    if($check_player_passport_validation == 'true'){
      return array( 'Season', date('m/d/y', strtotime($player_unlock->seasons->$target_year->paid)));
    }else{ return array( 'Expired', date('m/d/y', strtotime($player_unlock->seasons->$target_year->paid))); }
  } elseif( !empty($event_id) && isset($player_unlock->events->$event_id) ) {
    //if we can't find a qualifying season subscription then look for the event id
    return array( 'Event', date('m/d/y', strtotime($player_unlock->events->$event_id->paid)));
  } else {
    if($check_player_passport_validation == 'true'){
      return array( 'Season', $unset_date);
    }else{
      //if we can't find anything return an NA
//       return array( 'N/A', '--');
      //if we can't find anything return the last active sub
      return array( 'Expired', $unset_date );
    }
  }
}

//get player count
function g365_count_players() {
	global $wpdb;
	$wpdb_players = $wpdb->g365_players;
  return $data_columns = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb_players;");
}
//get player verified count
function g365_count_players_verify( $ver_level = 1 ) {
	global $wpdb;
  $attachement_filter = g365_get_attachement_filter($ver_level, true);
  $ver_level = intval($ver_level);
  $real_ver_level = $ver_level > 1 ? $ver_level - 1 : $ver_level;
	$wpdb_players = $wpdb->g365_players;
  return $data_columns = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb_players AS player WHERE player.verified = $real_ver_level $attachement_filter;");
}
//get roster count
function g365_count_rosters() {
	global $wpdb;
	$wpdb_rosters = $wpdb->g365_rosters;
  return $data_columns = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb_rosters;");
}
//how close are we to an event start
function g365_event_date_diff($ev_date) {
  //get the time info so we know how to process
  $ev_time_zone = new DateTimeZone('America/Los_Angeles');
  $ev_date_time = new DateTime($ev_date, $ev_time_zone);
  $ev_date_now = new DateTime("now", $ev_time_zone);
  $ev_date_diff = $ev_date_now->diff( $ev_date_time );
  //Invert means the event is in the past
  if( $ev_date_diff->invert === 1 ) {
    //Let's see if the event is from a past season
    if( $ev_date_time > (($ev_date_now->format('m') > 8) ? new DateTime( $ev_date_now->format('Y') . '-09-01', $ev_time_zone) : new DateTime( intval($ev_date_now->format('Y'))-1 . '-09-01', $ev_time_zone)) ) {
      return null;
    } else {
      return 5;
    }
  }
  //if it hasn't passed, see how close we are to the event start time
  if( $ev_date_diff->days < 1 ) return 1;
  if( $ev_date_diff->days < 3 ) return 2;
  if( $ev_date_diff->days < 7 ) return 3;
  return 4;
}

//filters a mysql roster call by event id
function all_rosters_filter($ev_id, $rosters){ return array_filter( $rosters, function( $v ) use ($ev_id) { return $v->event === $ev_id; }); }
// function g365_default_roster_presets($org_id) {
//   if( !is_numeric($org_id) ) return array('Need valid club id.');
//   //globals for db
//   global $wpdb;
//   $wpdb_rosters = $wpdb->g365_rosters;
//   $wpdb_teams = $wpdb->g365_teams;
//   $teams = $wpdb->get_results(
//     "SELECT ro.id AS ros_id, ro.enabled AS ros_enabled, tm.name AS team_name, tm.level AS team_level
//     FROM $wpdb_teams AS tm
//     LEFT JOIN $wpdb_rosters AS ro ON ro.team=tm.id AND ro.event = 0
//     WHERE tm.org = $org_id ORDER BY tm.level DESC;"
//   );
//   return $teams;
  
//   $preset_vars = array(
// //     'org_id:' . ,
// //     'org_name:' . ,
// //     'event_id:' . 
//   );
// }


//get all player profile data
function g365_get_profile($player = null, $truncate = false, $public_safe = true) {
	//make sure we have a value
	if( $player === null ) return 'Need Player URL to start build.';

	global $wpdb;
	//all the tables we have to get data from 
	$wpdb_players = $wpdb->g365_players;
	$wpdb_awards = $wpdb->g365_awards;
	$wpdb_award_refs = $wpdb->g365_award_refs;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_positions = $wpdb->g365_positions;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_rankings = $wpdb->g365_rankings;
	$wpdb_groups = $wpdb->g365_groups;
	$wpdb_games = $wpdb->g365_games;
	//see if we have an id or nickname
	if( is_numeric($player) ) {
		$player = intval( $player );
		$data_columns = $wpdb->get_results(
			"SELECT player.id, player.createtime, player.updatetime, player.account_level, player.enabled, player.name, player.first_name, player.last_name, player.email,
			player.phone, player.profile_img, player.address, player.city, player.state, player.zip, player.country, player.birthday, player.verified, player.tagline,
			player.grad_year, player.school, player.height_ft, player.height_in, player.weight, pos.name AS position_name, pos.id AS position_id, player.social, player.videos, player.notes,
      player.gpa, player.sat, player.act, player.nickname,
      org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb, org_club.id as club_id,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.jersey_size')) as jersey_size, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_resub')) as bcert_resub, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_resub')) as recard_resub, player.access
			FROM $wpdb_players AS player
			LEFT JOIN $wpdb_positions AS pos ON player.position=pos.id
			LEFT JOIN $wpdb_orgs AS org_club ON player.club_team=org_club.id
			WHERE player.id = $player;"
		);
	} else {
		$player = sanitize_text_field( $player );
		$data_columns = $wpdb->get_results(
			"SELECT player.id, player.createtime, player.updatetime, player.account_level, player.enabled, player.first_name, player.last_name, player.name, player.email,
			player.phone, player.profile_img, player.address, player.city, player.state, player.zip, player.country, player.birthday, player.verified, player.tagline,
			player.grad_year, player.height_ft, player.height_in, player.weight, pos.name AS position, player.social, player.videos, player.notes, org_school.name AS school,
			player.gpa, player.sat, player.act, player.nickname, org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.jersey_size')) as jersey_size, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_resub')) as bcert_resub, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_resub')) as recard_resub, player.access
			FROM $wpdb_players AS player
			LEFT JOIN $wpdb_positions AS pos ON player.position=pos.id
			LEFT JOIN $wpdb_orgs AS org_school ON player.school=org_school.id
			LEFT JOIN $wpdb_orgs AS org_club ON player.club_team=org_club.id
			WHERE player.nickname = '$player';"
		);
// 		$data_columns = $wpdb->get_results("SELECT * FROM $wpdb_players WHERE `nickname` = '$player'");
	}
	//return message if we can't find player record
	if( empty($data_columns) ) return "Couldn't retrieve player profile for this url.";
	//Simplify the object for a single record
	$data_columns = $data_columns[0];
	//only return basic player data
	if( $truncate )	return $data_columns;
	//Extract player id to use in queries
	$pl_id = $data_columns->id;
	//See if player has awards and add them to the tree
	$data_columns->awards = $wpdb->get_results(
		"SELECT ar.event as event_id, ev.eventtime, ev.short_name, ar.ranking as ranking_id, ar.team as team_id, aw.type as award_type, aw.name as award, aw.logo_img as award_img, ar.name as award_title, ar.enabled as enabled, rk.start_datetime as starttime, rk.end_datetime as endtime, rk.enabled as rk_enabled, rk_gr.nickname as rk_url
		FROM $wpdb_award_refs AS ar
		JOIN $wpdb_awards AS aw ON ar.award=aw.id
		LEFT JOIN $wpdb_rankings AS rk ON ar.ranking=rk.id
		LEFT JOIN $wpdb_groups AS rk_gr ON rk.group_id=rk_gr.id
    LEFT JOIN $wpdb_events AS ev ON ev.id = ar.event
		WHERE ar.player = $pl_id
		ORDER BY ar.updatetime DESC;"
	);
	//Get all players camp stats and truncate the data if we don't have direction of types
  $public_safe = (($public_safe === true) ? ' AND ev.type = 2' : (($public_safe === 0) ? '' : ((is_array($public_safe)) ? (' AND ev.type IN (' . implode(',',array_map(function($t){ return intval($t); }, $public_safe)) . ')') : (' AND type = ' . intval($public_safe)) )));
  $data_columns->stats = $wpdb->get_results(
		"SELECT st.id as id, st.game as game_id, CONCAT(gm.start_time, ' : ', gm.court) as game_handle, ev.id as event_id, ev.name as event, ev.nickname as event_url, ev.link as event_link, ev.logo_img as event_logo,ev.type as event_type, st.profile_img, st.evaluation, st.strengths, st.weaknesses, st.stats, st.trends, st.video, st.enabled
		FROM $wpdb_stats AS st
		JOIN $wpdb_events AS ev ON st.event=ev.id
    LEFT JOIN $wpdb_games AS gm ON st.game=gm.id
		WHERE st.player = $pl_id $public_safe
		ORDER BY ev.eventtime DESC;",
		OBJECT_K
	);
	return $data_columns;
}

//send a string of pipe delineated dates and get a array of days
function g365_get_days_from_string( $dates ) {
  if( empty($dates) ) return 'No string supplied.'; 
  $dates = explode('|', $dates);
  if( is_array($dates) ) {
    $date_processed = array();
    foreach( $dates as $date_string ) {
      $time_zone = new DateTimeZone('America/Los_Angeles');
      $day = new DateTime($date_string, $time_zone);
      $date_processed[] = $day->format('D');
    }
    if( !empty($date_processed) ) return $date_processed;
    return 'Days cannot be processed';
  } else {
    return 'Could not parse date string.';
  }
}

//get all event profile data
function g365_get_event_data($event = null, $truncate = false) {
	//make sure we have a value
	if( $event === null ) return 'Need Event URL to start build.';

	global $wpdb;
	//all the tables we have to get data from
	$wpdb_players = $wpdb->g365_players;
	$wpdb_awards = $wpdb->g365_awards;
	$wpdb_award_refs = $wpdb->g365_award_refs;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	//see if we have an id or nickname
	if( is_numeric($event) ) {
		$event = intval( $event );
		$data_columns = $wpdb->get_results(
			"SELECT ev.*, org.name as org_name, org.nickname as org_url, org.social as org_social
			FROM $wpdb_events AS ev
			LEFT JOIN $wpdb_orgs AS org ON ev.org=org.id
			WHERE ev.id = $event;"
		);
	} else {
		$event = sanitize_text_field( $event );
		$data_columns = $wpdb->get_results(
			"SELECT ev.*, org.name as org_name, org.nickname as org_url, org.social as org_social
			FROM $wpdb_events AS ev
			LEFT JOIN $wpdb_orgs AS org ON ev.org=org.id
			WHERE ev.nickname = '$event';"
		);
	}
  
  if ($data_columns !== false) {
    // Query was successful, and $results contains the data
    foreach ($data_columns as $row) {
        // Process each row of the result
//   echo("<script>console.table('data_columns: " . $row->column_name . " ');</script>");
//         echo $row->column_name;
    }
  }else{
    echo("<script>console.table('failed ');</script>");
  }
  
	//return message if we can't find event record
	if( empty($data_columns) ) return "Couldn't retrieve event for this url.";
	//Simplify the object for a single record
	$data_columns = $data_columns[0];
  //if it's the default club team add a link var in
  if( $event === 0 ) $data_columns->level_link = 'hide';
	//only return basic event data
	if( $truncate )	return $data_columns;
	//Extract event id to use in queries
	$ev_id = $data_columns->id;
	//See if event has awards and add them to the tree
	$data_columns->awards = $wpdb->get_results(
		"SELECT pl.id as player_id, aw.name as award_type, ar.class as grad_class, aw.logo_img as award_img, ar.name as award_title
		FROM $wpdb_award_refs AS ar
		JOIN $wpdb_awards AS aw ON ar.award=aw.id
		JOIN $wpdb_players AS pl ON ar.player=pl.id
		WHERE ar.event = $ev_id AND ar.enabled = 1
		ORDER BY FIELD(ar.award, 1, 5, 4, 3, 9, 2, 10, 14, 16), ar.class, pl.name;"
	);
	if( !empty($data_columns->awards) ) {
		$data_columns->award_types = array();
    $test = array();
		$awards_process =  new stdClass();
		foreach( $data_columns->awards as $dex => $vals ) {
			//organize award data by event_id->award_id[grad_class][award_item]
//   			$awards_process->{$vals->award_type}->{$vals->grad_class}[] = $vals;   
  			$awards_process->{$vals->award_type}[$vals->grad_class][] = $vals;
			//add event id to positive data array if we don't already have it
			if( !in_array($vals->award_type, $data_columns->award_types) ) $data_columns->award_types[] = $vals->award_type;
		}
		//sort grad_class in descending order;
	// 	rsort($data_columns->award_classes);
		//add awards to main object
		$data_columns->awards = $awards_process;
	}
	//Get all players camp stats
	$data_columns->stats = $wpdb->get_results(
		"SELECT pl.id as player_id, pl.name, pl.nickname as player_url, org.name as player_club, org.nickname as club_url, st.profile_img, st.evaluation, st.strengths, st.weaknesses, st.stats, st.trends, st.video, st.enabled
		FROM $wpdb_stats AS st
		JOIN $wpdb_players AS pl ON st.player=pl.id
		LEFT JOIN $wpdb_orgs AS org ON pl.club_team=org.id
		WHERE st.event = $ev_id
		ORDER BY pl.name;",
		OBJECT_K
	);
	return $data_columns;
}
//get event data for featured widget
function g365_display_events($group_id = 45, $limit = 4, $range = false) {
	global $wpdb;
	$wpdb_events = $wpdb->g365_events;
  $group_id = intval($group_id);
	if( is_bool($range) && $range ) {
		$data_columns = $wpdb->get_results(
			"SELECT name, short_name, logo_img, link, eventtime, nickname, dates
			FROM $wpdb_events
			WHERE eventtime > NOW() - INTERVAL 1 DAY
			ORDER BY eventtime ASC LIMIT $limit;"
			);
	} else {
		$wpdb_group_refs = $wpdb->g365_group_refs;
		$data_columns = $wpdb->get_results(
			"SELECT ev.name as name, ev.short_name as short_name, ev.logo_img as logo_img, ev.link as link, ev.eventtime as eventtime, ev.nickname as nickname, ev.dates as dates
			FROM $wpdb_group_refs as refs
			LEFT JOIN $wpdb_events as ev ON refs.item_id=ev.id
			WHERE refs.group_id = $group_id AND refs.enabled = 1 AND ev.enabled = 1 AND ev.eventtime > (NOW() - INTERVAL 2 DAY)
			ORDER BY ev.eventtime ASC LIMIT $limit;"
		);
	}
	return $data_columns;
}
//get data for player featured widget by event
function g365_get_players_featured($event = false, $limit = 4) {
	$last_year = date( "Y-m-d", strtotime( "now -1 year" ) );
	global $wpdb;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_events = $wpdb->g365_events;
	if($event === false) {
		$data_columns = $wpdb->get_results(
			"SELECT st.profile_img as player_profile_img, pl.id, pl.name as player, pl.nickname as player_url, ev.id as event_id, ev.name as event, ev.logo_img as event_logo, ev.nickname as event_url, ev.eventtime, ev.dates as event_dates, ev.link as event_link
			FROM $wpdb_stats AS st
			LEFT JOIN $wpdb_players AS pl ON st.player=pl.id 
			LEFT JOIN $wpdb_events AS ev ON st.event=ev.id
			WHERE ev.eventtime > '$last_year' AND ev.eventtime < NOW() AND st.profile_img IS NOT NULL
			ORDER BY RAND() LIMIT $limit;"
		);
		return $data_columns;
	}
}
//get data for player featured widget by award
function g365_get_awards_featured($event = false, $limit = 4) {
	$last_year = date( "Y-m-d", strtotime( "now -1 year" ) );
	global $wpdb;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_award_refs = $wpdb->g365_award_refs;
	if($event === false || $event === '0' || $event === 0) {
		$data_columns = $wpdb->get_results(
			"SELECT (SELECT st.profile_img FROM $wpdb_stats AS st WHERE st.player = aw.player AND st.profile_img IS NOT NULL ORDER BY st.updatetime DESC LIMIT 1) as player_profile_img, pl.id, pl.name as player, pl.nickname as player_url, ev.id as event_id, ev.name as event, ev.logo_img as event_logo, ev.nickname as event_url, ev.eventtime, ev.dates as event_dates, ev.link as event_link
			FROM $wpdb_award_refs AS aw
			LEFT JOIN $wpdb_players AS pl ON aw.player=pl.id 
			LEFT JOIN $wpdb_stats AS st ON aw.player=st.player
			LEFT JOIN $wpdb_events AS ev ON aw.event=ev.id
			WHERE ev.eventtime > '$last_year' AND ev.eventtime < NOW() AND st.profile_img IS NOT NULL
			ORDER BY RAND() LIMIT $limit;"
		);
		return $data_columns;
	}
}
//get data for player featured widget by event
function g365_get_orgs_featured($org = false, $limit = 4) {
	global $wpdb;
	$wpdb_orgs = $wpdb->g365_orgs;
	if($org === false) {
		$data_columns = $wpdb->get_results(
			"SELECT name, profile_img AS img, nickname AS url
			FROM $wpdb_orgs
			WHERE enabled = 1 AND profile_img IS NOT NULL
			ORDER BY RAND() LIMIT $limit;"
		);
		return $data_columns;
	}
}
//get data for teams
function g365_get_team($id = false) {
	global $wpdb;
	$wpdb_teams = $wpdb->g365_teams;
	if($id !== false) {
    //id, updatetime, level, enabled, name,	team_type, org, roster, media, practice, schedule, notes
		$data_columns = $wpdb->get_results( "SELECT * FROM $wpdb_teams WHERE id = $id;" );
		return $data_columns;
	}
}
//get data for teams
function g365_get_team_name($id = null, $roster = null ) {
	global $wpdb;
	$wpdb_rosters = $wpdb->g365_rosters;
	$wpdb_teams = $wpdb->g365_teams;
	$wpdb_orgs = $wpdb->g365_orgs;
  $outcome = '';
	if($id !== null) {
    //id, updatetime, level, enabled, name,	team_type, org, roster, media, practice, schedule, notes
		$data_columns = $wpdb->get_row( "SELECT org.name AS org_name, tm.level AS level, tm.name AS tm_name 
    FROM $wpdb_teams AS tm LEFT JOIN $wpdb_orgs AS org ON tm.org = org.id WHERE tm.id = $id;" );
    $outcome = $data_columns->org_name . ' ' . g365_level_key($data_columns->level) . ' ' . $data_columns->tm_name;
  } elseif( $roster !== null ) {
		$data_columns = $wpdb->get_row( "SELECT org.name AS org_name, tm.level AS level, tm.name AS tm_name 
    FROM $wpdb_rosters AS ros LEFT JOIN $wpdb_teams AS tm ON ros.team = tm.id LEFT JOIN $wpdb_orgs AS org ON tm.org = org.id WHERE ros.id = $roster;" );
    $outcome = $data_columns->org_name . ' ' . g365_level_key($data_columns->level) . ' ' . $data_columns->tm_name;
	} else {
    $outcome = 'Need team id to retrieve name';
  }
  return $outcome;
}
//get data for coaches
function g365_get_coach($id = false) {
	global $wpdb;
	$wpdb_coaches = $wpdb->g365_coaches;
	if($id !== false) {
		$data_columns = $wpdb->get_row( "SELECT * FROM $wpdb_coaches WHERE id = $id;" );
		return $data_columns;
	}
}

//retrieve groups and groups of groups
function g365_get_groups_data( $group = null, $type = null, $args = null ) {
//   echo("<script>console.log('test ');</script>");
	if( $group === null ) return 'Need group to pull data.';
	global $wpdb;
	$wpdb_groups = $wpdb->g365_groups;
  $enabled = 'AND `enabled` = 1';
  $enabled_ref = 'AND gr.enabled = 1';
  $enabled_part = 'enabled = 1';
  if( !is_null($args) && isset($args['enabled']) ) {
    if( is_numeric($args['enabled']) ) {
      $enabled = 'AND `enabled` = ' . intval($args['enabled']);
      $enabled_ref = 'AND gr.enabled = ' . intval($args['enabled']);
      $enabled_part = 'enabled = ' . intval($args['enabled']);
    } else {
      $enabled = '';
      $enabled_ref = '';
      $enabled_part = '';
    }
  }
	//if you have an id, go straigth there, otherwise see what we find with a name
	if( is_numeric($group) ) {
		$group = $wpdb->get_results(
			"SELECT * FROM $wpdb_groups WHERE `id` = $group $enabled;"
		);
	} else {
		//narrow the search by group type org, event, series
		$sql_type = ( $type === null || $type < 1 ) ? '' : "AND `type` = $type"; 
		$group = $wpdb->get_results(
			"SELECT * FROM $wpdb_groups WHERE `nickname` LIKE '$group' $sql_type $enabled;"
		);
	}
	if( empty($group) ) return 'No groups found.';
	//it the result is ambiguous then return the data for refinement
	if( count($group) > 1 || $type < 0 ) return $group;
	//simplify the data reference
	$group = $group[0];
	if( $group->groups != 1 && $type === 0 ) return $group;
	//the second phase is pulling the group references
	$wpdb_group_refs = $wpdb->g365_group_refs;
  $wpdb_events = $wpdb->g365_events;
	//extract the group id for use in the query
	$group_id = $group->id;
	//if the group is a group of groups do the diligence
	if( $group->groups == 1 ) {
		//get the list of subgroups
    switch($group_id) {
      case 44:
    		//for the master calendar...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref
          ORDER BY FIELD(gr.id,43,8,13,12,168,169,170,283,284);"
        );
        break;
      case 24:
        //for EBC Camps...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref
          ORDER BY FIELD(gr.id,23,22,14,15,63,16,17,18,19,20,21,54,64);"
        );
        break;
      case 89:
        if(!empty($args['tsc_only']) && $args['tsc_only'] === true){ $tsc_only = " AND gr.name LIKE ('%The Stage%') "; }else{ $tsc_only = ''; }
        //for all-tournament awards...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*, (
          SELECT ev_ref.eventtime 
          FROM $wpdb_group_refs AS sub_group_ref 
          LEFT JOIN $wpdb_events AS ev_ref ON sub_group_ref.item_id=ev_ref.id
          WHERE gr.id=sub_group_ref.group_id ORDER BY ev_ref.eventtime LIMIT 1
          ) AS eventtime, (
          SELECT ev_ref.org 
          FROM $wpdb_group_refs AS sub_group_ref 
          LEFT JOIN $wpdb_events AS ev_ref ON sub_group_ref.item_id=ev_ref.id
          WHERE gr.id=sub_group_ref.group_id ORDER BY ev_ref.eventtime LIMIT 1
          ) AS ev_org
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref $tsc_only
          ORDER BY eventtime DESC;"
        );
        break;
      case 287:
    		//for the master calendar...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref
          ORDER BY FIELD(gr.id,288,8,286,13,12,168,169,170,283,284);"
        );
        break;
       case 342:
    		//for the master calendar...put it in this order...
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref
          ORDER BY FIELD(gr.id,343,8,286,13,12,168,169,170,283,284,346,347,348);"
        );
        break;
      default:
        //defaults to no order
        $group->records = $wpdb->get_results(
          "SELECT gr.*
          FROM $wpdb_group_refs AS refs
          LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
          WHERE refs.group_id = $group_id $enabled_ref;"
        );
    }
		//if we only want the list of sub groups output now
		if( $type === 0 ) return $group;
		//make the search list for the subgroup pull
		$group_id_list = array();
		foreach( $group->records as $dex => $sub_group ) {
			$group_id_list[] = $sub_group->id;
		}
		//format for sql query
		$group_id_list = 'IN (' . implode(',',$group_id_list) . ')';
	} else {
		$group_id_list = "= $group_id";
	}
  //return only the groups
  if(!empty($args['truncate'])){
    if( $args['truncate'] === true ) return $group;
  }
  //attach the data that the groups are pointing at
	switch( $type ) {
		case 0: //sub groups
      $enabled_part = 'AND gr.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT gr.*
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_groups AS gr ON refs.item_id=gr.id
				WHERE refs.group_id $group_id_list $enabled_part;"
			);
			break;
		case 1: //orgs
			$wpdb_orgs = $wpdb->g365_orgs;
      $enabled_part = 'AND org.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT org.*, refs.group_id
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_orgs AS org ON refs.item_id=org.id
				WHERE refs.group_id $group_id_list $enabled_part;"
			);
			break;
		case 2: //events asc
			$wpdb_events = $wpdb->g365_events;
      $enabled_part = 'AND ev.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT ev.*, refs.group_id
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_events AS ev ON refs.item_id=ev.id
				WHERE refs.group_id $group_id_list $enabled_part
				ORDER BY ev.eventtime;"
			);
			break;
		case 3: //events desc
			$wpdb_events = $wpdb->g365_events;
      $enabled_part = 'AND ev.' . $enabled_part;
			$group_ref_data = $wpdb->get_results(
				"SELECT ev.*, refs.group_id
				FROM $wpdb_group_refs AS refs
				LEFT JOIN $wpdb_events AS ev ON refs.item_id=ev.id
				WHERE refs.group_id $group_id_list $enabled_part
				ORDER BY ev.eventtime DESC;"
			);
			break;
		case 4: //rankings
			$wpdb_rankings = $wpdb->g365_rankings;
			//create date based array for subnavigation
			$group->ranking_brackets = $wpdb->get_results(
				"SELECT DISTINCT start_datetime, end_datetime
				FROM $wpdb_rankings
				WHERE group_id $group_id_list $enabled
				ORDER BY start_datetime DESC;"
			);
			//if there is limit data use it otherwise assume we are pulling the lastest data
			$min_limit = ( empty($args['min-limit']) ? date("Y-m-d", strtotime($group->ranking_brackets[0]->start_datetime)) : date("Y-m-d", strtotime($args['min-limit'])) );
			$max_limit = ( empty($args['max-limit']) ? date("Y-m-d", strtotime($group->ranking_brackets[0]->end_datetime)) : date("Y-m-d", strtotime($args['max-limit'])) );
			//different stipulations if we are looking for the ranking at a specific time
// 			$date_limit = 'AND `start_datetime` >= "' . $min_limit . '" AND `end_datetime` <= "' . $max_limit . '"';
			$date_limit = "AND `start_datetime` >= '" . $min_limit . "' AND `end_datetime` <= '" . $max_limit . "'";
			$group_ref_data = $wpdb->get_results(
				"SELECT *
				FROM $wpdb_rankings
				WHERE group_id $group_id_list $date_limit $enabled
				ORDER BY group_id ASC, ranking_type DESC;"
			);
			break;
		default:
			return "Can't find group type to finish data processing.";
			break;
	}
	//if this is a group of groups, organize the data into sections then make lists of the contained group ids
	//otherwise make the id list and append the records
	$group->item_ids = array();
	if( $group->groups == 1 ) {
		foreach( $group->records as $dex => &$record ) {
			$record->item_ids = array();
			$record->records = array();
			foreach( $group_ref_data as $subdex => $subrecord ) {
				if( $record->id == $subrecord->group_id ) {
					$record->records[] = $subrecord;
					$group->item_ids[] = $subrecord->id;
					$record->item_ids[] = $subrecord->id;
				}
			}
		}
	} else {
		foreach( $group_ref_data as $dex => $record ) {
			$group->item_ids[] = $record->id;
		}
		$group->records = $group_ref_data;
	}
	return $group;
}
//retrieve awards data
//build awards widget
function g365_build_awards( $group = null, $args = null ) {
	if( $group === null ) return 'Need group id to pull data.';
	//get data groups
	$groups = g365_get_groups_data( $group, 3 );
	//if the group data is empty or an error message, send it back
	if( empty($groups) || gettype($groups) == 'string' ) return $groups;
	//make the search string for award retrieval
	$award_id_list = 'IN (' . implode(',',$groups->item_ids) . ')';
	global $wpdb;
	$wpdb_awards = $wpdb->g365_awards;
	$wpdb_award_refs = $wpdb->g365_award_refs;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	//Pull full list of awards for the tables
	$awards = $wpdb->get_results(
		"SELECT DISTINCT refs.name AS award_name, refs.class AS award_class,
		ev.id AS event_id, events.nickname AS event_nickname, refs.event, refs.award, 
		pl.name AS player_name, pl.id AS player_id, pl.nickname AS player_url, pl.birthday AS player_birthday,
		aw.name AS award_label, aw.logo_img, aw.type AS award_type,
		st.profile_img AS profile_img
		FROM $wpdb_award_refs AS refs
		LEFT JOIN $wpdb_award_refs AS ev ON refs.event=ev.id
    LEFT JOIN $wpdb_events AS events
    ON events.id = refs.event
		LEFT JOIN $wpdb_players AS pl ON refs.player=pl.id
		LEFT JOIN $wpdb_awards AS aw ON refs.award=aw.id
		LEFT JOIN $wpdb_stats AS st ON st.player = refs.player AND st.event=refs.event AND st.profile_img IS NOT NULL
		WHERE refs.event $award_id_list
		ORDER BY refs.event, FIELD(refs.award, 1, 5, 4, 3, 9, 2, 10, 6, 7, 12, 11, 14, 16), FIELD(refs.class, 1, 2, 3, 4, 5, 6, 7, 8, 171, 324, 325, 172, 173, 174, 175, 9, 10, 119, 120, 121, 122, 176, 177, 123, 216, 11, 271, 272, 301, 318, 240, 12, 319, 124, 125, 126, 127, 178, 179, 128, 242, 202, 228, 229, 13, 239, 304, 305, 241, 14, 129, 130, 131, 132, 133, 203, 274, 273, 243, 164, 165, 15, 16, 275, 134, 135, 326, 302, 303, 136, 137, 138, 204, 306, 307, 244, 250, 230, 231, 180, 181, 17, 18, 139, 140, 141, 182, 183, 142, 162, 163, 143, 205, 327, 328, 245, 232, 251, 233, 234, 160, 161, 19, 159, 20, 144, 145, 146, 147, 148, 206, 246, 252, 330, 331, 322, 321, 253, 185, 186, 166, 167, 184, 21, 169, 170, 22, 149, 150, 151, 23, 24, 25, 26, 168, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 153, 154, 60, 61, 155, 156, 157, 158, 62, 63, 64, 65, 308, 309, 310, 66, 67, 68, 69, 70, 71, 72, 311, 73, 74, 312, 75, 76, 313, 77, 78, 79, 287, 288, 289, 80, 81, 290, 317, 291, 292, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 276, 314, 277, 278, 279, 101, 197, 198, 199, 200, 201, 207, 212, 214, 215, 217, 218, 235, 236, 247, 254, 255, 261, 264, 265, 266, 267, 323, 320, 280, 281, 282, 283, 102, 193, 194, 195, 196, 208, 209, 211, 219, 220, 221, 222, 223, 248, 256, 257, 262, 268, 269, 270, 103, 284, 315, 329, 285, 316, 286, 187, 188, 189, 190, 191, 192, 210, 213, 224, 225, 226, 227, 237, 249, 259, 263, 300, 299, 298, 297, 296, 295, 294, 293 ,260, 238, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118);"
	);
  //check for the truncate flag
  if( $args['truncate'] === true ) {
    $groups->awards = $awards;
    return $groups;
  }
	//reformat data to be organized by event id
	$awards_process =  new stdClass();
	//add a data point to track which events actually have award data associated with them
	$groups->data_present = array();
	foreach( $awards as $dex => $vals ) {
		//organize award data by event_id->award_id[grad_class][award_item]
		if( !isset($awards_process->{$vals->event_id}) ) $awards_process->{$vals->event_id} = new stdClass();
    $awards_process->{$vals->event_id}->{$vals->award_type}[$vals->award_class][] = $vals;
		//add event id to positive data array if we don't already have it
		if( !in_array($vals->event_id, $groups->data_present) ) $groups->data_present[] = $vals->event_id;
	}
	//add awards to main object
	$groups->awards = $awards_process;
	return $groups;
}

//build the players to watch dataset
function g365_build_watchlist( $group = null, $args = null ) {
	if( $group === null ) return 'Need group id to pull data.';
	//get data groups
	$groups = g365_get_groups_data( $group, 4, $args );
	//if the group data is empty or an error message, send it back
	if( empty($groups) || gettype($groups) == 'string' ) return $groups;
	//make the search string for award retrieval
	$groups->player_records = array();
	foreach ( $groups->records as $dex => $group ) {
		if( $groups->groups == 1 ) {
			foreach( $group->records as $subdex => $subgroup ) {
				$subgroup->rankings = json_decode($subgroup->rankings);
				$groups->player_records = array_merge( $groups->player_records, $subgroup->rankings );
			}
		} else {
			$group->rankings = json_decode($group->rankings);
			$groups->player_records = array_merge( $groups->player_records, $group->rankings );
		}
	}
	$pl_ids = implode(' id UNION ALL SELECT ', $groups->player_records);
	global $wpdb;
	$wpdb_players = $wpdb->g365_players;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_events = $wpdb->g365_events;
	//Pull full list of awards for the tables
	$groups->player_records = $wpdb->get_results(
		"SELECT pl.id, pl.name, pl.nickname AS player_url, ev.nickname AS event_nickname, st.profile_img AS player_img
		FROM (SELECT $pl_ids) AS pl_ids
		LEFT JOIN $wpdb_players AS pl ON pl_ids.id=pl.id
		LEFT JOIN (SELECT pl_ids_latest.id AS player, MAX(st_latest.updatetime) updatetime
			FROM (SELECT $pl_ids) AS pl_ids_latest
			LEFT JOIN $wpdb_stats AS st_latest ON pl_ids_latest.id=st_latest.player 
			WHERE st_latest.profile_img IS NOT NULL
			GROUP BY pl_ids_latest.id) AS pl_latest ON pl_ids.id=pl_latest.player
		LEFT JOIN $wpdb_stats AS st ON pl_ids.id=st.player AND pl_latest.updatetime=st.updatetime
    LEFT JOIN $wpdb_events AS ev ON st.event=ev.id;",
		OBJECT_K
	);
	return $groups;
}

//build the team ranking dataset
function g365_build_ranking( $group = null, $args = null ) {
	if( $group === null ) return 'Need group id to pull data.';
	//get data groups
	$groups = g365_get_groups_data( $group, 4, $args );
	//if the group data is empty or an error message, send it back
	if( empty($groups) || gettype($groups) == 'string' ) return $groups;
	//make the search string for award retrieval
	$groups->org_records = array();
	foreach ( $groups->records as $dex => $group ) {
		if( $groups->groups == 1 ) {
			foreach( $group->records as $subdex => $subgroup ) {
				$subgroup->rankings = json_decode($subgroup->rankings);
				$groups->org_records = array_merge( $groups->org_records, $subgroup->rankings );
			}
		} else {
			$group->rankings = json_decode($group->rankings);
			$groups->org_records = array_merge( $groups->org_records, $group->rankings );
		}
	}
// 	$groups->org_records = array_unique( $groups->org_records );
	$or_ids = implode(' id UNION ALL SELECT ', $groups->org_records);
	global $wpdb;
	$wpdb_orgs = $wpdb->g365_orgs;
	//Pull full list of awards for the tables
	$groups->org_records = $wpdb->get_results(
		"SELECT org.id, org.name, org.nickname AS org_url, org.profile_img AS org_logo
		FROM (SELECT $or_ids) AS or_ids
		LEFT JOIN $wpdb_orgs AS org ON or_ids.id=org.id;",
		OBJECT_K
	);
	return $groups;
}


// //build calendar object
// function g365_build_calendar( $group = null ) {
// 	if( $group === null ) return 'Need group id to pull data.';
// 	//get data groups
// 	$groups = g365_get_groups_data( $group, 2 );
// 	//future error handling
// 	if( empty($groups) || gettype($groups) == 'string' ) return $groups;
// 	return $groups;
// }


$group_types = array(
	'Regions',
	'Series'
);
$ref_types = array(
	'Orgs'	=> 1,
	'Series'=> 2,
	'Events'=> 3
);
$g365_db_sheet_connector = array(
	'g365_players' => array(
		'first_name',
		'last_name',
		'email'
	),
	'g365_stats' => array(
		'event',
		'player'
	)
);

function g365_table_col_names($type = null) {
	//gets non generated column names
	if( empty($type) ) return 'Missing paramter, cannot get column names.';
	global $wpdb;
	$wpdb_table = $wpdb->{$type};
	$data_columns = $wpdb->get_results(
		"SELECT `COLUMN_NAME`,`EXTRA`, `DATA_TYPE`
		FROM `INFORMATION_SCHEMA`.`COLUMNS` 
		WHERE `TABLE_SCHEMA`='$wpdb->dbname' 
    AND `TABLE_NAME`='$wpdb_table';"
	);
	$col_names_arr = array();
	foreach($data_columns as $dex => $row){
		//add it as long as it's not a generated column
		if( strpos($row->EXTRA, 'GENERATED') === false ) $col_names_arr[$row->COLUMN_NAME] = $row->DATA_TYPE;
	}
	return $col_names_arr;
}

function g365_build_where($query_pairs, $exact = false){
	//builds the where string for db searches
	if( empty($query_pairs) ) return 'Missing target, cannot build where statement.';	
	if( array_is_indexed( $query_pairs ) ) return 'Missing array associations. Cannot build where statement.';
	//if we have specifc columns to search, otherwise add all the $query_pairs associative array pairs
	$where_string = '';
	$exact_char = ( $exact ) ? '' : '%';
	foreach( $query_pairs as $key => $val ) {
		$where_string .= ($where_string === '') ? 'WHERE ' : ' AND ';
		if(is_array($val)) {
			$val = is_numeric($val[0]) ? ' IN (' . implode(', ',$val) . ')' : ' IN ("' . implode('", "',$val) . '")';
		} else {
			$val = (gettype($val) == 'integer') ? ' = ' . $val : ' LIKE "' . $exact_char . $val . $exact_char . '"';
		}
		$where_string .=  '(' . $key . $val . ')';
	}
	return $where_string;
}

function array_is_indexed($array){ return (array_values($array) === $array); }


//set wpdb for use of new data
function g365_profile_set_data(){
	global $wpdb;
	$wpdb->{'g365_players'} = $wpdb->prefix . "g365_players";
	$wpdb->{'g365_events'} = $wpdb->prefix . "g365_events";
	$wpdb->{'g365_stats'} = $wpdb->prefix . "g365_stats";
	$wpdb->{'g365_games'} = $wpdb->prefix . "g365_games";
	$wpdb->{'g365_claims'} = $wpdb->prefix . "g365_claims";
	$wpdb->{'g365_awards'} = $wpdb->prefix . "g365_awards";
	$wpdb->{'g365_award_refs'} = $wpdb->prefix . "g365_award_refs";
	$wpdb->{'g365_teams'} = $wpdb->prefix . "g365_teams";
	$wpdb->{'g365_groups'} = $wpdb->prefix . "g365_groups";
	$wpdb->{'g365_group_refs'} = $wpdb->prefix . "g365_group_refs";
	$wpdb->{'g365_positions'} = $wpdb->prefix . "g365_positions";
	$wpdb->{'g365_orgs'} = $wpdb->prefix . "g365_organizations";
	$wpdb->{'g365_partners'} = $wpdb->prefix . "g365_partners";
	$wpdb->{'g365_partner_refs'} = $wpdb->prefix . "g365_partner_refs";
	$wpdb->{'g365_coaches'} = $wpdb->prefix . "g365_coaches";
	$wpdb->{'g365_rosters'} = $wpdb->prefix . "g365_rosters";
	$wpdb->{'g365_rankings'} = $wpdb->prefix . "g365_rankings";
	$wpdb->{'g365_account_level'} = $wpdb->prefix . "g365_account_level";
  $wpdb->{'g365_positions'} = $wpdb->prefix . "g365_positions";
	$wpdb->{'g365_club_refs'} = $wpdb->prefix . "g365_club_refs";
	$wpdb->{'g365_sessions'} = $wpdb->prefix . "g365_sessions";
	$wpdb->{'g365_yearly_slb'} = $wpdb->prefix . "g365_yearly_slb";
	$wpdb->{'g365_favorites'} = $wpdb->prefix . "g365_favorites";
	$wpdb->{'g365_images'} = $wpdb->prefix . "g365_images";
	$wpdb->{'g365_badges'} = $wpdb->prefix . "g365_badges";
	$wpdb->{'g365_badges_core'} = $wpdb->prefix . "g365_badges_core";
	$wpdb->{'g365_player_badges'} = $wpdb->prefix . "g365_player_badges";
	$wpdb->{'g365_api_keys'} = $wpdb->prefix . "g365_api_keys";
	$wpdb->{'g365_team_stats'} = $wpdb->prefix . "g365_team_stats";
	$wpdb->{'g365_device_tokens'} = $wpdb->prefix . "g365_device_tokens";
}
add_action( 'init', 'g365_profile_set_data' );

///function to export csv of roster data for import into exposure
function g365_export_csv() {
	if(isset($_POST['get_info'])){
		switch($_POST['get_info']){
			case 'exposure':
				if( empty($_POST['event_id']) || !is_numeric($_POST['event_id']) ) {
					echo 'Need Event ID.';
					break;
				}
				$g365_rosters = g365_get_expo_data('g365_rosters', array( 'event' => intval($_POST['event_id'])),
          "
           IF( ISNULL(roster.division) OR roster.division = '', IF( roster.level = '43' OR roster.level = '44', '7th/8th Grade Girls', IF(roster.level = '41', '5th Grade Girls', if(roster.level = '42', '6th Grade Girls', IF( roster.level = '46', 'JV Girls', IF( roster.level = '47', 'Varsity Girls', CONCAT(roster.level,'U'))  ) ) ) ), IF( roster.level = '43' OR roster.level = '44', CONCAT('7th/8th Grade Girls',' ', roster.division), IF(roster.level = '41', CONCAT('5th Grade Girls',' ', roster.division), IF(roster.level = '42', CONCAT('6th Grade Girls',' ', roster.division), IF(roster.level = '46', CONCAT('JV Girls',' ', roster.division), IF(roster.level = '47', CONCAT('Varsity Girls',' ', roster.division), roster.level ) ) ) ) ) ) AS 'DIVISION',
           IF( ISNULL(orgs.abbreviation) OR orgs.abbreviation = '', CONCAT(orgs.name,' ', teams.name), CONCAT(orgs.abbreviation,' ', teams.name) ) AS 'TEAMNAME',
           JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_name')) AS 'POOLNAME',
           JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.pool_number')) AS 'POOLNUMBER',
           JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.team_restrictions')) AS	'TEAMRESTRICTIONS',
           JSON_UNQUOTE(JSON_EXTRACT(roster.notes, '$.date_restrictions')) AS	'DATETIMERESTRICTIONS',
           '' AS 'EXHIBITIONRESTRICTION',
           '' AS	'TEAMID',
           '' AS	'GENDER',
           '' AS	'SEASONSTARTYEAR',
           '' AS	'SEASONENDYEAR',
           '' AS	'SEASONSESSION',
           '' AS	'CITY',
           '' AS	'STATEREGION',
           '' AS	'POSTALCODE',
           '' AS	'ABBREVIATION',
           '' AS	'TWITTERHANDLE',
           '' AS	'CONTACTFIRSTNAME',
           '' AS	'CONTACTLASTNAME',
           '' AS	'CONTACTEMAIL',
           '' AS	'CONTACTHOMEPHONE',
           '' AS	'CONTACTMOBILEPHONE',
           '' AS	'CONTACTWORKPHONE',
           '' AS	'CONTACTFAXPHONE',
           '' AS	'CONTACTSTREETADDRESS',
           '' AS	'CONTACTEXTENDEDADDRESS',
           '' AS	'CONTACTCITY',
           '' AS	'CONTACTSTATEREGION',
           '' AS	'CONTACTPOSTALCODE',
           '' AS	'CONTACTNCAAAPPROVALNUMBER',
           '' AS	'STATUS',
           '' AS	'PAID',
           '' AS	'NOTES',
           roster.id AS	'EXTERNALTEAMID',
           '' AS	'CUSTOMFIELDS',
           roster.division AS LEVEL
          ");
				if(empty($g365_rosters)) {
					echo 'No records found.';
					die();
					break;
				}
				g365_output_csv($g365_rosters);
				break;
			case 'pl':
				break;
			default:
				echo 'Bad request.';
				break;
		}
	} else {
		echo 'Submission error.';
		die();
	}
}

function g365_output_csv($rows) {
// 	echo '<pre>';
// 	print_r($rows);
	$filename = 'g365_export_' . date('m-d-y');
	//add column headers
	if( !is_array($rows) ) echo 'Empty player record set.';
	$column_titles = array();
	foreach( $rows[0] as $key => $val ){
		$column_titles[] = $key;
	}
// 	print_r($column_titles);
	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, $column_titles);

	// loop over the rows, outputting them
	foreach($rows as $row) {
		$row_arr = array();
		foreach( $row as $key => $val ) {
			$row_arr[] = $val;
		}
		fputcsv($output, $row_arr);
	}
// 	echo '</pre>';
//   print_r($output);
// 	echo $output;
	exit();
}

// make select options list
function g365_make_options_list($obj_list, $selected = false){
	$select_options = '<option value="">-- Please Choose --</option>';
	foreach ( $obj_list as $item ) {
		$selected_html = ( $item->id == $selected ) ? ' selected' : '';
		$select_options .= '<option value="' . $item->id . '"' . $selected_html . '>' . htmlentities($item->name) . '</option>';
	}
	return $select_options;
}

add_action( 'wp_ajax_nopriv_g365_get_events_ajax', 'g365_get_events_ajax' );
add_action( 'wp_ajax_g365_get_events_ajax', 'g365_get_events_ajax' );

//make group select list
function g365_get_events_ajax(){
	$org_id = $_POST['org_id'];
	if(is_numeric($org_id)){
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
			$event_list = g365_make_events_select($org_id);
			if(empty($event_list)){
				$data_compile = array(
					'status'  	=> false,
					'messages'	=> "Couldn't find any events."
					);
			} else {
				$data_compile = array(
					'status'  	=> true,
					'messages'	=> $event_list
					);
			}
		}
	} else {
		$data_compile = array(
			'status'   => false,
			'messages' => "<p>Error, Organization ID not formatted correctly. ID: $org_id</p>",
			);
	}
	echo json_encode($data_compile);
	die();
}

///get game
function g365_get_game($game_id = null){
	global $wpdb;
//   Hide warning for empty rosters
  if( empty($game->away_players_data) || empty($game->home_players_data) || empty($game->home_players) || empty($game->away_players) ){
    error_reporting(E_ERROR | E_PARSE);
  }
	if( !is_numeric($game_id) ) return "Need valid Game ID";
  $game_id = intval($game_id);
  $game = $wpdb->get_results(
      "SELECT games.id, games.event_id, games.court, games.division, games.start_time, games.location, games.home_team AS home_roster_id, games.home_team_score, games.away_team AS away_roster_id, games.away_team_score, IF( home_org.abbreviation IS NULL OR home_org.abbreviation = '', CONCAT(home_org.name,' ', home_roster.level,'U ', home_team.name), CONCAT(home_org.abbreviation,' ', home_roster.level,'U ', home_team.name) ) AS home_team, IF( away_org.abbreviation IS NULL OR away_org.abbreviation = '', CONCAT(away_org.name,' ', away_roster.level,'U ', away_team.name), CONCAT(away_org.abbreviation,' ', away_roster.level,'U ', away_team.name) ) AS away_team, home_org.profile_img AS home_profile_img, away_org.profile_img AS away_profile_img, home_roster.players AS home_players, away_roster.players AS away_players
      FROM $wpdb->g365_games AS games
      LEFT JOIN $wpdb->g365_rosters AS home_roster ON games.home_team = home_roster.id
      LEFT JOIN $wpdb->g365_teams AS home_team ON home_roster.team = home_team.id
      LEFT JOIN $wpdb->g365_orgs AS home_org ON home_roster.org = home_org.id
      LEFT JOIN $wpdb->g365_rosters AS away_roster ON games.away_team = away_roster.id
      LEFT JOIN $wpdb->g365_teams AS away_team ON away_roster.team = away_team.id
      LEFT JOIN $wpdb->g365_orgs AS away_org ON away_roster.org = away_org.id
      WHERE games.id = $game_id;"
  );
  $game = (is_array($game)) ? $game[0] : $game;
  $where_ids = array();
  $query_data = null;
  $home_query_ids = null;
  if( empty($game->home_players) ) {
    $game->home_players = '';
    $game->home_players_data = '';
  } else {
    $game->home_players_data = json_decode($game->home_players);
    $home_query_ids = array_keys((array) $game->home_players_data);
    $where_ids = array_merge( $where_ids, $home_query_ids);
  }
  if( empty($game->away_players) ) {
    $game->away_players = '';
    $game->away_players_data = '';
  } else {
    $game->away_players_data = json_decode($game->away_players);
    $where_ids = array_merge( $where_ids, array_keys((array) $game->away_players_data));
  }
  if( !empty($game->home_players) || !empty($game->away_players) ) {
    $query_where = g365_build_where(array('pl.id'=>$where_ids));
    //Grab player data and add them to the tree
    $query_data = $wpdb->get_results(
      "SELECT pl.id, pl.name, pl.birthday, pl.city, pl.state, pl.profile_img, pl.nickname AS url, pl.verified, st.stats AS stats
      FROM $wpdb->g365_players AS pl
      LEFT JOIN $wpdb->g365_stats AS st ON st.player = pl.id AND st.game = $game_id
      $query_where
      ORDER BY name;",
      OBJECT_K
    );
    foreach($query_data as $id_num => &$player_data) {
      //add reference for home or away
      $player_data->homeaway = ( in_array($id_num, $home_query_ids) ) ? 'home' : 'away';
      $player_data->element_title = $player_data->name;
      //add stat data into the main data tree
      if( !empty($player_data->stats) ) {
        $player_data->stats = json_decode($player_data->stats);
        foreach($player_data->stats as $stat_name => $stat_val) $player_data->{$stat_name} = $stat_val;
        unset($player_data->stats);
      }
      if( empty($player_data->profile_img) ) {
        $player_data->profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
      } else {
        $player_data->profile_img = get_site_url() . '/wp-content/uploads/player-profiles/' . $player_data->profile_img;
      }
    }
    foreach ( $game->home_players_data as $pl_dex => $pl_data ) $game->home_players_data->{$pl_dex} = $query_data[$pl_dex];
    foreach ( $game->away_players_data as $pl_dex => $pl_data ) $game->away_players_data->{$pl_dex} = $query_data[$pl_dex];
  }
	return $game;
}
///get games
function g365_get_games($ev_id = null, $location = null){
	global $wpdb;
	$ev = '';
  $games = 'Invalid Request.';
	if( is_numeric($ev_id) ) {
		$ev = "WHERE `event_id` = $ev_id";
		$ev .= ( !is_null($location)) ? " AND `location` LIKE '$location'" : '';
  	$games = $wpdb->get_results( 
      "SELECT games.id, games.event_id, games.court, games.division, games.start_time, games.location, games.home_team AS home_roster_id, games.home_team_score, games.away_team AS away_roster_id, games.away_team_score, IF( home_org.abbreviation IS NULL OR home_org.abbreviation = '', CONCAT(home_org.name,' ', home_roster.level,'U ', home_team.name), CONCAT(home_org.abbreviation,' ', home_roster.level,'U ', home_team.name) ) AS home_team, IF( away_org.abbreviation IS NULL OR away_org.abbreviation = '', CONCAT(away_org.name,' ', away_roster.level,'U ', away_team.name), CONCAT(away_org.abbreviation,' ', away_roster.level,'U ', away_team.name) ) AS away_team
      FROM $wpdb->g365_games AS games
      LEFT JOIN $wpdb->g365_rosters AS home_roster ON games.home_team = home_roster.id
      LEFT JOIN $wpdb->g365_teams AS home_team ON home_roster.team = home_team.id
      LEFT JOIN $wpdb->g365_orgs AS home_org ON home_roster.org = home_org.id
      LEFT JOIN $wpdb->g365_rosters AS away_roster ON games.away_team = away_roster.id
      LEFT JOIN $wpdb->g365_teams AS away_team ON away_roster.team = away_team.id
      LEFT JOIN $wpdb->g365_orgs AS away_org ON away_roster.org = away_org.id
      $ev ORDER BY court, start_time;"
    );
  }
	return $games;
}

///get orgs
function g365_get_orgs($org_id = null, $type = null, $dataSet = 'id, name'){
	global $wpdb;
	$org = '';
	if( is_numeric($org_id) || is_numeric($type) ) {
		$org = 'WHERE ';
		$org .= (is_numeric($org_id)) ? "`id` = $org_id" : '';
		$org .= (is_numeric($org_id) && is_numeric($type) ) ? ' AND ' : '';
		$org .= (is_numeric($type)) ? "`type` = $type" : '';
	}
	$org_names = $wpdb->get_results( 
		"SELECT $dataSet
		FROM $wpdb->g365_orgs $org;"
	);
	return $org_names;
}

///get events
function g365_get_events($event_id = null, $org = null, $dataSet = 'id, name'){
	global $wpdb;
	$event = '';
	if( !is_null($event_id) || is_numeric($org) ) {
		$event = 'WHERE ';
    $event .= (is_numeric($event_id)) ? "`id` = $event_id" : '';
    $event .= (!is_numeric($event_id)) ? "`nickname` LIKE '$event_id'" : '';
    $event .= (is_array($event_id)) ? ("`id` IN (" . implode(',',$event_id) . ")") : '';
		$event .= ( $event !== 'WHERE ' && is_numeric($org) ) ? ' AND ' : '';
		$event .= (is_numeric($org)) ? "`org` = $org" : '';
	}
	$event_names = $wpdb->get_results( 
		"SELECT $dataSet
		FROM $wpdb->g365_events $event
		ORDER BY eventtime DESC LIMIT 10;"
	);
  if( !empty($event_names) && count($event_names) === 1 && $event_id !== null ) $event_names = $event_names[0];
	return $event_names;
}

//make org select list
function g365_make_orgs_select($type = null){
	$org_list = g365_get_orgs(null, $type);
	$all_orgs = '<select name="org_id">';
	$all_orgs .= g365_make_options_list($org_list);
	$all_orgs .= '</select>';
	return $all_orgs;
}
//make org select list
function g365_make_events_select($org = null){
	$event_list = g365_get_events(null, $org);
	$all_events = '<select name="event_id">';
	$all_events .= g365_make_options_list($event_list);
	$all_events .= '</select>';
	return $all_events;
}

//Admin Pages
function g365_export(){
	echo '<h1>Export Data</h1>'; ?>
	<p>What data do you wish to retrieve?</p>
	<form id="g365-export-form" method="post" action="<?php echo plugins_url( 'export-data.php', __FILE__ ); ?>"> 
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label>Organization: </label></th>
				<td id="org-names-list">
					<?php echo g365_make_orgs_select(1); ?>
					<input name="get_info" type="hidden" id="get_info" value="exposure">
				</td>
			</tr>
			<tr>
				<th scope="row"><label>Event: </label></th>
				<td id="event-names-list">
          <input name="event_id" type="hidden" id="event_id" value="188">
        </td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" id="get-data-button" class="button button-primary button-large" value="Get Data"></td>
			</tr>
		</tbody>
	</table>
	</form>
	<?php echo '<script src="' . plugins_url( 'js/general.js', __FILE__ ) . '" ></script>';
}

function g365_get_org_names($org_id) {
	global $wpdb;
	$wpdb_orgs = $wpdb->g365_orgs;
  return $wpdb->get_results( "SELECT name, abbreviation FROM $wpdb_orgs WHERE id = $org_id" );
}

function ucname($string) {
	$string =ucwords(strtolower($string));
	foreach (array('-', '\'') as $delimiter) {
		if (strpos($string, $delimiter)!==false) {
			$string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
		}
	}
	return $string;
}

//reorder data
function g365_group_by($key, $stat_data) {
  $result = array();

  foreach($stat_data as $val) {
    if( !empty($val->$key)){
      $result[$val->$key][] = $val;
    } else {
      $result["Unclassified"][] = $val;
    }
  }
  return $result;
}

//send an email with html content
function send_html_email($email, $subject, $message) {
  $headers = ( strpos( site_url(), 'dev' ) !== false ) ? array('From: Sports Passports Dev Customer Service <no-reply@dev.sportspassports.com>') : array('From: Sports Passports Customer Service <no-reply@sportspassports.com>');
  add_filter('wp_mail_content_type', function(){ return "text/html";});
  $email_status = wp_mail( $email, $subject, $message, $headers );
  remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
  return $email_status;
}

function send_html_email_updated($email, $subject, $message) {
  $headers = ( strpos( site_url(), 'dev' ) !== false ) ? array('From: Sports Passports Dev Customer Service <no-reply@dev.sportspassports.com>') : array('From: Sports Passports Customer Service <no-reply@sportspassports.com>');
//   add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
  add_filter('wp_mail_content_type', function(){ return "text/html";});
  $email_status = wp_mail( $email, $subject, $message, $headers );
  remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
//   echo("test2");
  return $email_status;
}

function send_html_email_updated_accepted_claim($email, $subject, $message) {
  $headers = ( strpos( site_url(), 'dev' ) !== false ) ? array('From: Sports Passports Dev Customer Service <no-reply@dev.sportspassports.com>') : array('From: Sports Passports Customer Service <no-reply@sportspassports.com>');
//   add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
  add_filter('wp_mail_content_type', function(){ return "text/html; charset=UTF-8";});
//   echo(" test: " . $email . " subj: " . $subject . " messge: " . $message);
  $email_status = wp_mail( $email, $subject, $message, $headers );
  remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
//   echo("test2");
  return $email_status;
}

/**
 * Convert time into decimal time.
 */
function g365_time_to_decimal($time) {
  if(!empty($time)){
    $array_time = explode(':', $time);
    $time_in_dec = ($array_time[0]*60) + ($array_time[1]) + ($array_time[2]/60);
  }else{
//     echo "Need time data to format to decimal";
    echo "";
    $time_in_dec = '';
  }
  return $time_in_dec;
}

//use the ajax wordpress pathway to manage external data inputs
function g365_pages_cache_purge( $nicknames = null, $type = 'player' ){
  $status = 'WP Super Cache not active';
  if ( is_plugin_active('wp-super-cache/wp-cache.php') ) {
    if( gettype( $nicknames ) === 'string' ) $nicknames = array( $nicknames );
    if( gettype( $nicknames ) !== 'array' ) return 'Nickname type undefined.';
    $target_compile = array();
    global $wpdb;
    $wpdb_player = $wpdb->g365_players;
    switch($type) {
      case 'player':
        $target_compile = $wpdb->get_col( "SELECT nickname FROM $wpdb_player WHERE id IN ( " . implode(',', $nicknames) . " );" );
        $target_compile = array_filter($target_compile,'strlen');
        break;
    }
    $command_string = 'rm -rf';
    //rewrite to pull $types from array for saftey
    if ( strpos( site_url(), 'dev' ) !== false ) {
      $command_string .= ' /srv/users/ogpdevworker/apps/g365-dev/public/wp-content/cache/supercache/dev.grassroots365.com/' . $type . '/' . implode(' /srv/users/ogpdevworker/apps/g365-dev/public/wp-content/cache/supercache/dev.grassroots365.com/' . $type . '/', $target_compile);
    } else {
      $command_string .= ' /srv/users/serverpilot/apps/g365-press/public/wp-content/cache/supercache/grassroots365.com/' . $type . '/' . implode(' /srv/users/serverpilot/apps/g365-press/public/wp-content/cache/supercache/grassroots365.com/' . $type . '/', $target_compile);
    }
    $output = shell_exec( $command_string );
    $status = 'Profiles page cache purged';
  }
  return $status;
}

//includes

//import exposure support
include( 'exposure-api.php' );
include( 'g365-data-manager-ext.php' );
include( 'g365-data-manager-dev-team.php' );
// include( 'g365-data-locking.php' );
//badges/acheivements
include('inc/badges.php');

?>