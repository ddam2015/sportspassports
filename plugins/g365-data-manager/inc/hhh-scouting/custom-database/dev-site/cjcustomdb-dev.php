<?php
// * Description: This is where I make the connection to the dev site database.


// if(strpos( site_url(), 'dev' ) !== false){
  //in dev site
$hostname = 'db-mysql-sfo3-50037-do-user-1744987-0.g.db.ondigitalocean.com:25060';
$username = 'doadmin';
$password = 'AVNS_zcYevEXyOsnS4WlkMOE';
$database = 'sportspassports-dev';

$connection = mysqli_connect($hostname, $username, $password, $database);

if(!$connection){
    die('Connection failed: ' . mysqli_connect_error());
}
  
// }else{
// //   in live site
  
// }

?>