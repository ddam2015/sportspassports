<?php
// * Description: This is where I make the connection to the live site database.


// if(strpos( site_url(), 'dev' ) !== false){
  //in dev site
$hostname = 'db-g365-do-user-1744987-0.b.db.ondigitalocean.com:25060';
$username = 'doadmin';
$password = 'AVNS_KxVuEbdOgjNws4MLVxb';
$database = 'spp';

$connection = mysqli_connect($hostname, $username, $password, $database);

if(!$connection){
    die('Connection failed: ' . mysqli_connect_error());
}
  
// }else{
// //   in live site
  
// }

?>