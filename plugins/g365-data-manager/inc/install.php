<?php

//activation instructions
//create database if not already created
//set extra user roles
function g365_additional_roles(){
  add_role( 'college_coach', 'Digital Coach Packet', array( 'read' => true, 'data_editor' => true ) );
  add_role( 'stat_vip', 'Stat VIP', array( 'read' => true, 'front_editor' => true ) );
}
echo g365_additional_roles();
function g365_data_install() {
  //clean roles before activation
//   remove_role( 'cps_moderator' );

  //user foles
  add_role( 'college_coach', 'Digital Coach Packet', array( 'read' => true, 'data_editor' => true ) );
  add_role( 'cps_moderator', 'CPS Editor', array( 'read' => true, 'front_editor' => true ) );
  add_role( 'score_keeper', 'Scorekeeper', array( 'read' => true, 'front_editor' => true ) );
  add_role( 'stat_keeper', 'Statkeeper', array( 'read' => true, 'front_editor' => true ) );
  add_role( 'gate_controller', 'Gatekeeper', array( 'read' => true ) );
  add_role( 'event_moderator', 'Event Editor', array( 'read' => true, 'front_editor' => true ) );
  add_role( 'player_editor', 'Player Manager', array( 'read' => true, 'customer' => true, 'data_editor' => true ) );
  add_role( 'coach', 'Coach', array( 'read' => true, 'customer' => true, 'data_editor' => true, 'rosters' => true ) );
  add_role( 'club', 'Director', array( 'read' => true, 'customer' => true, 'data_editor' => true, 'rosters' => true ) );

  //db setup
	g365_profile_set_data();
	global $wpdb;
//				for adding full name index to players
//         name varchar(100) AS (CONCAT(first_name,' ',ifnull(last_name,''))) STORED NOT NULL,
//         KEY (name)

// groups types:
	// Orgs:		1
	// Series:	2
	// Events:	3
	$charset_collate = $wpdb->get_charset_collate();
	$sql = array(
		"CREATE TABLE $wpdb->g365_players (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		createtime datetime  DEFAULT CURRENT_TIMESTAMP,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		account_level tinyint(1) DEFAULT 1,
		enabled tinyint(1) DEFAULT 1,
		first_name varchar(30) NOT NULL,
		last_name varchar(30),
		email varchar(100),
		phone varchar(20),
		profile_img varchar(200),
		address varchar(100),
		city varchar(100),
		state varchar(2),
		zip mediumint(10),
		country varchar(100),
		birthday date,
		verified tinyint(1) DEFAULT 0,
		tagline varchar(100),
		grad_year smallint(4),
		height_ft tinyint(1),
		height_in tinyint(2),
		weight smallint(3),
		position mediumint(7),
		social json,
		videos json,
		notes json,
		attendant json,
		club_team mediumint(7),
		school mediumint(7),
		gpa decimal(3,2),
		sat smallint(5),
		act smallint(5),
		nickname varchar(60) NOT NULL,
		access json,
		name varchar(60) GENERATED ALWAYS AS (CONCAT(first_name,' ',last_name)) STORED,
		PRIMARY KEY  (id),
		UNIQUE KEY nickname (nickname),
		KEY name (name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_events (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		eventtime datetime NOT NULL,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		account_level tinyint(1) NOT NULL,
		enabled tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		short_name varchar(40),
		org mediumint(7) NOT NULL,
		type tinyint(2) DEFAULT 1,
		logo_img varchar(200),
		hashtag varchar(30),
		description blob,
		dates varchar(100),
		times varchar(100),
		divisions json,
		locations varchar(255),
		short_locations varchar(255),
		social json,
		video varchar(100),
		link varchar(120),
		schedule_link json,
		stats json,
		trends json,
		contact_name varchar(50),
		email varchar(100),
		phone varchar(20),
		nickname varchar(100),
		PRIMARY KEY  (id),
		UNIQUE KEY org_name (org,name),
		UNIQUE KEY nickname (nickname),
		KEY name (name),
		KEY type (type)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_stats (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		player mediumint(7) NOT NULL,
		event mediumint(7) NOT NULL,
    game mediumint(10),
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		lock_stat tinyint(1) DEFAULT 0,
		profile_img varchar(200),
		evaluation varchar(350),
		strengths json,
		weaknesses json,
		stats json,
		trends json,
		video varchar(100),
		PRIMARY KEY  (id),
		UNIQUE KEY event_player_game (event,player,game),
		KEY player (player),
		KEY updatetime (updatetime)
    ) $charset_collate",
		"CREATE TABLE $wpdb->g365_games (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		event_id mediumint(7),
    court varchar(100) NOT NULL,
    division varchar(100) NOT NULL,
    start_time datetime NOT NULL,
    location varchar(100) NOT NULL,
    home_team mediumint(7) NOT NULL,
    home_team_score mediumint(3),
    away_team mediumint(7) NOT NULL,
    away_team_score mediumint(3),
    exposure_game_id int(10),
    bracket_name varchar(100),
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		UNIQUE KEY game_event (event_id, court, start_time, location)
		) $charset_collate",
    "CREATE TABLE $wpdb->g365_favorites (
    createdate datetime DEFAULT CURRENT_TIMESTAMP,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		id mediumint(7) NOT NULL AUTO_INCREMENT,
    enabled tinyint(1),
    event_id mediumint(7) NULL, 
    user_id	mediumint(7),
    player_id	mediumint(7) NULL,
    notes	json NULL,
    pl_data	json NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY dcp_event (user_id, player_id)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_claims (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		type tinyint(2) NOT NULL,
		target mediumint(7) NOT NULL,
		site_key varchar(3) NOT NULL,
		email varchar(100) NOT NULL,
		status tinyint(2) NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY type_site_key_email_target (type,site_key,email,target)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_awards (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		type tinyint(2) NOT NULL,
    badge_type tinyint(2) DEFAULT 0,
    badge_time tinyint(2) DEFAULT NULL,
		name varchar(60) NOT NULL,
		logo_img varchar(200)  DEFAULT NULL,
    progression json NULL DEFAULT NULL,
    PRIMARY KEY  (id),
    KEY badge_type_badge_time (badge_type,badge_time),
		UNIQUE KEY name (name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_award_refs (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		player mediumint(7) NOT NULL,
		team mediumint(7) NOT NULL,
		ranking mediumint(7) NOT NULL,
		event mediumint(7) NOT NULL,
		award mediumint(7) NOT NULL,
		class smallint(4),
		name varchar(60) NOT NULL,
		progression smallint(3) DEFAULT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY player_team_ranking_event_award (player,team,ranking,event,award),
		KEY team (team)
    KEY ranking (ranking),
		KEY event (event),
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_teams (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
    createdate datetime DEFAULT CURRENT_TIMESTAMP,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		level tinyint(2) NOT NULL,
		enabled tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		team_type varchar(30),
		org mediumint(7) NOT NULL,
		roster mediumint(7),
		media json,
		practice varchar(150),
		schedule json,
		notes blob,
		search_list varchar(60) GENERATED ALWAYS AS (CONCAT(IF(level > 30, CONCAT('Girls ',(level - 36),'th Grade'), CONCAT(level,'U ')),COALESCE(CONCAT(' ',NULLIF(name, '')),''),COALESCE(CONCAT(' (',NULLIF(team_type, ''),')'),''))) STORED,
		PRIMARY KEY  (id),
		UNIQUE KEY org_level_name (org,level,name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_groups (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		type tinyint(1) NOT NULL,
		groups tinyint(1),
		abbr varchar(30),
		nickname varchar(30),
		PRIMARY KEY  (id),
		UNIQUE KEY type_name (type,name),
		UNIQUE KEY nickname (nickname,type)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_group_refs (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		group_id mediumint(7) NOT NULL,
		item_id mediumint(7) NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY group_id_item_id (group_id,item_id),
		KEY item_id (item_id)
		) $charset_collate",
    "CREATE TABLE $wpdb->g365_images (
    createdate	datetime DEFAULT CURRENT_TIMESTAMP,
    updatetime	datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id	mediumint(7) NOT NULL AUTO_INCREMENT,
    user_id	mediumint(9),
    enabled	tinyint(1) DEFAULT 1,
    player_id	json NULL,
    private	tinyint(1),
    verified	tinyint(1),
    img_name	varchar(100),	 
    rejected	tinyint(1), 
    highlight	json NULL,
    admin_addition	tinyint(1),
    PRIMARY KEY  (id),
		UNIQUE KEY id (id)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_positions (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		abbr varchar(4),
		PRIMARY KEY  (id),
		UNIQUE KEY name (name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_orgs (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		createtime datetime DEFAULT CURRENT_TIMESTAMP,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		account_level tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		type tinyint(1) DEFAULT 1,
		abbreviation varchar(30),
		profile_img varchar(200),
		director_first varchar(30),
		director_last varchar(30),
		director_email varchar(100),
		director_phone varchar(20),
		email varchar(100),
		phone varchar(20),
		address varchar(100),
		city varchar(100),
		state varchar(2),
		zip mediumint(10),
		country varchar(100),
		link varchar(150),
		social json,
		videos json,
		notes blob,
		county varchar(100),
		nickname varchar(60),
		access json,
		tags json,
		search_list varchar(300) GENERATED ALWAYS AS ( CONCAT(COALESCE(CONCAT(abbreviation,', '),''),COALESCE(name,''),COALESCE(CONCAT(', ',NULLIF(CAST(tags AS CHAR), '')),'')) ) STORED,
		PRIMARY KEY  (id),
		UNIQUE KEY nickname (nickname),
		KEY type (type),
		KEY search_list (search_list)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_partners (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		logo_img varchar(200),
		banner_img varchar(200),
		tagline varchar(200),
		description tinytext,
		link varchar(200),
		PRIMARY KEY  (id),
		UNIQUE KEY name (name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_partner_refs (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		event mediumint(7) NOT NULL,
		partner mediumint(7) NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY event_partner (event,partner),
		KEY partner (partner)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_coaches (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		createtime datetime DEFAULT CURRENT_TIMESTAMP,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		account_level tinyint(1) DEFAULT 1,
		enabled tinyint(1) DEFAULT 1,
		first_name varchar(30) NOT NULL,
		last_name varchar(30),
		nickname varchar(30),
		email varchar(100),
		phone varchar(20),
		profile_img varchar(200),
		city varchar(100),
		state varchar(2),
		social json,
		videos json,
		notes blob,
		name varchar(60) GENERATED ALWAYS AS (CONCAT(first_name,' ',last_name)) STORED,
		PRIMARY KEY  (id),
		UNIQUE KEY nickname (nickname),
		KEY name (name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_rosters (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		org mediumint(7) NOT NULL,
		team mediumint(7) NOT NULL,
		event mediumint(7) NOT NULL,
		level tinyint(2) NOT NULL,
		division varchar(20),
		name varchar(60) NOT NULL,
		team_type varchar(30),
		coach mediumint(7),
		asst mediumint(7),
		players json,
		description tinytext,
		events json,
		notes json,
		PRIMARY KEY  (id),
		UNIQUE KEY org_team_level_event_name (org,team,level,event,name),
		KEY team (team),
		KEY event (event)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_rankings (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		release_datetime datetime NOT NULL,
		start_datetime datetime NOT NULL,
		end_datetime datetime NOT NULL,
		enabled tinyint(1) DEFAULT 1,
		group_id mediumint(7),
		ranking_type varchar(30),
		team_rankings json,
		rankings json,
		PRIMARY KEY  (id)
		KEY group_id (group_id)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_account_level (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		name varchar(60) NOT NULL,
		roles json,
		PRIMARY KEY  (id),
		UNIQUE KEY name (name)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_club_refs (
		id mediumint(7) NOT NULL AUTO_INCREMENT,
		updatetime datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		enabled tinyint(1) DEFAULT 1,
		player mediumint(7) NOT NULL,
		org mediumint(7) NOT NULL,
		team mediumint(7) NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY player_org_team (player,org,team),
		KEY org (org),
		KEY team (team)
		) $charset_collate",
		"CREATE TABLE $wpdb->g365_sessions (
		id varchar(32) NOT NULL,
		access int(10) unsigned DEFAULT NULL,
		data text,
		PRIMARY KEY  (id)
		) $charset_collate"
	);
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$status = dbDelta( $sql );
}