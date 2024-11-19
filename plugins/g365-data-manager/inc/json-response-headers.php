<?php

//setup variables
$http_origin = $_SERVER['HTTP_ORIGIN'];
// DD 
// if(empty($_SERVER['HTTP_ORIGIN'])){
//   $http_origin = 'https://'.$_SERVER['HTTP_HOST'];
// }else{
//   $http_origin = $_SERVER['HTTP_ORIGIN'];
// }
// if(empty($_SERVER['uni_tag'])){
//   $http_origin = 'https://'.$_SERVER['HTTP_HOST'];
// }else{
//   $http_origin = $_SERVER['uni_tag'];
// }

$allow_list = array(
  'https://sportspassports.com',
  'https://dev.sportspassports.com',
  'https://elitebasketballcircuit.com',
  'https://dev.elitebasketballcircuit.com',
  'https://grassroots365.com',
  'https://dev.grassroots365.com',
  'https://opengympremier.com',
  'https://dev.opengympremier.com',
  'https://thestagecircuit.com',
  'https://dev.thestagecircuit.com',
  'https://hypeherhoopscircuit.com',
  'https://dev.hypeherhoopscircuit.com',
  'https://scholasticseries.com',
  'https://dev.scholasticseries.com',
  'https://breakthroughcircuit.com',
  'https://dev.breakthroughcircuit.com',
  'https://ogpcares.com/',
  'https://dev.ogpcares.com',
  'https://theundergroundcircuit.com/',
  'https://dev.theundergroundcircuit.com'
);
// figure out if the request is legal
if ( in_array( $http_origin, $allow_list )  || ( $http_origin === NULL && in_array( $_SERVER['uni_tag'], $allow_list) ) ) {
  header("Access-Control-Allow-Origin: $http_origin");
  //assume that we are responding with json
  header('Access-Control-Allow-Methods: *');
  header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, uni_tag');
  header('Content-Type: application/json');
  if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) exit();
} else {
  die(('Domain not allowed. [' . var_dump($_SERVER) . ']'));
}

?>