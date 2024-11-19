<?php
require( 'json-response-headers.php' );
//support preflight from external domains
if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) exit();
require( 'session-handler.php' );
// if(empty($_SERVER['HTTP_ORIGIN'])){
//   $http_origin = 'https://'.$_SERVER['HTTP_HOST'];
// }else{
  $http_origin = $_SERVER['HTTP_ORIGIN'];
// }
if ( empty($_SESSION['token']) ) $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
if ( empty($_SESSION['time']) ) $_SESSION['time'] = time();
if ( empty($_SESSION['anti_bot']) )  $_SESSION['anti_bot'] = Config::getConfig('antiBot');
// print_r($_SERVER['REQUEST_METHOD']);

if( session_id() == '' ) {
  echo json_encode(array(
    'status'  => 'error',
    'result'  => session_id(),
//     'http_origin'=> $_SERVER['HTTP_ORIGIN']
    'http_origin'=> $http_origin
  ));
} else {
  echo json_encode(array(
    'status'  => 'success',
//     'http_origin'=> $_SERVER['HTTP_ORIGIN'],
    'http_origin'=> $http_origin,
    'result'  => array(
      'id'      => session_id(),
      'token'   => $_SESSION['token'],
      'time'    => $_SESSION['time']
    )
  ));
}
exit();

?>