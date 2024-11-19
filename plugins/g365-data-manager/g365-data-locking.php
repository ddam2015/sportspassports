<?php

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
  if( intval($pl_date_now->format('n')) < 9 ) {
    if( intval($pl_date_time->format('n')) > 8 ) $ver_level -= 1;
  } else {
    if( intval($pl_date_time->format('n')) < 9 ) $ver_level += 1;
  }
  //process the raw number into a level title
  if( $level_proc ) $ver_level = g365_level_key($ver_level);
  return $ver_level;
}

?>