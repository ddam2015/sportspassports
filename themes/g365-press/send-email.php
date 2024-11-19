<?php
require_once('../../../wp-load.php');

function send_html_email_with_files($email, $subject, $message, $headers, $files) {
  add_filter('wp_mail_content_type', function(){ return "text/html";});
  $email_status = wp_mail( $email, $subject, $message, $headers, $files);
  return $email_status;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cardPdf']) && isset($_POST['playerName']) && isset($_POST['recipientEmail'])) {
  
     if(isset($_POST['cardType'])){
       $cardType = $_POST['cardType']; // 'season-average-card' or 'stat-card'
     }
  
    if(isset($_POST['year'])){
      $year = $_POST['year'];
    }
   
    if($cardType === 'season-average-card') {
      $file_name = "Player Season Card " . $year . " " . $_POST['playerName'] . ".pdf";
    }else {
      $file_name = "Player Card " . $_POST['playerName'] . ".pdf";
    }
    $subject = 'Check out ' . $_POST['playerName'] .'\'s player card';
    $message = 'Find attached my player card!';
    $recipientEmail = $_POST['recipientEmail'];
    $files = [$file_name => $_FILES['cardPdf']['tmp_name']];
    
    $headers = array(( strpos( site_url(), get_site_url() ) === false ) ? 
                      'From: Sports Passports Customer Service <no-reply@sportspassports.com>' :
                     'From: Sports Passports Dev Customer Service <no-reply@dev.sportspassports.com>');

    if(send_html_email_with_files($recipientEmail, $subject, $message, $headers, $files)) {
      echo "Email sent successfully.";
    } else{
        echo json_encode(array("status" => "error", "message" => "Failed to send email."));
    }
    unlink($_FILES['cardPdf']['tmp_name']);
}else{
  echo "Bad request";
}
  
