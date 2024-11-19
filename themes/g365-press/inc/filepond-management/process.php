<?php
  // Load our configuration for this server
  include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
  require_once('config.php');
  require("./util/uploadmedia.php");
  require("./util/read_write_functions.php");
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $files = $_FILES["filepond"];
    $imageName = null;
    $id = null;
    function saveImagesToTempLocation($uploadedFile){
      global $imageName;
      global $id;
      $get_file_type = $_FILES["filepond"]['type'][0];
      $imageUniqueId = null;
      $current_user = wp_get_current_user(); 
        // check that there were no errors while uploading file 
        if (isset($uploadedFile) && $uploadedFile['error'] === UPLOAD_ERR_OK){
          $imageName = uploadImage($uploadedFile, UPLOAD_DIR);
          // If upload successfully
          if($imageName){
            $auth_user = wp_get_current_user();
            if(current_user_can('administrator')){ $access_type = 'admin'; }else{ $access_type = 'user'; $claimed_pl = get_user_meta($auth_user->ID, '_user_owns_g365', true); }
            // Check for duplicate file name
            g365_photo_upload_process_type($access_type, ['img_name'=>$imageName, 'auth_user_id'=>$auth_user->ID, 'claimed_pl'=>$claimed_pl, 'file_type'=>$get_file_type]);
            // Delete temp file(s) from local folder
            delete_file(dirname(__FILE__, 3).'/assets/photo-additions/uploads/', $imageName);
          }
        }
      return $id;
    }

    $structuredFiles = [];
    if (isset($files)) {
      foreach($files["name"] as $filename) {
        $structuredFiles[] = [
          "name" => $filename
        ];
      }

      foreach($files["type"] as $index => $filetype) {
        $structuredFiles[$index]["type"] = $filetype;
      }

      foreach($files["tmp_name"] as $index => $file_tmp_name) {
        $structuredFiles[$index]["tmp_name"] = $file_tmp_name;
      }

      foreach($files["error"] as $index => $file_error) {
        $structuredFiles[$index]["error"] = $file_error;
      }

      foreach($files["size"] as $index => $file_size) {
        $structuredFiles[$index]["size"] = $file_size;
      }
    }
    $uniqueImgID = null;
    if (count($structuredFiles)){
      foreach ($structuredFiles as $structuredFile){
        $given_file_ext = str_replace(array('image/', 'video/'), array('', ''), $structuredFile['type']);
        if(in_array($given_file_ext, g365_media_file_type('photo'))){ $media_type = 'photo'; }
        else if(in_array($given_file_ext, g365_media_file_type('video'))){ $media_type = 'video'; }
        $is_limit_reached = g365_media_upload_limit($media_type, $current_user->ID); 
        if($is_limit_reached['locked'] !== true){
          $uniqueImgID = saveImagesToTempLocation($structuredFile);
        }else{ return false; }
      }
    }
    $response = [];
    if(!$uniqueImgID){
      $response["test_log"] = $uniqueImgID;
      $response["file_type"] = $media_type;
      http_response_code(200);
    } else {
      $response["status"] = "error";
      $response["key"] = null;
      $response["msg"] = "An error occured while uploading image";
      $response["files"] = json_encode($structuredFiles);
      http_response_code(400);
    }

    header('Content-Type: application/json');
    echo json_encode($response);

    exit();

  } else {

    exit();
  }