<?php
$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
require_once( $path . 'wp-load.php' );
echo pp_pay_link($_POST['year'], true)[0];

?>