<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php' );
  function uploadImage($file, $fileDestination = "./images/") {
    $fileName = sanitize_file_name($file['name']);
    $fileType = $file['type'];
    $fileTempName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileSize = $file['size'];
    $key = $file['id'];
    $fullname = $file['name'] . $file['id'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $img_allowed_exts = g365_media_file_type('photo');
    $vid_allowed_exts = g365_media_file_type('video');
    if(in_array($fileActualExt, $img_allowed_exts)){
      if($fileError === 0){
        $allow_file_size = allow_upload_size('upload-photo-size', ['in_bytes'=>true]);
        if($fileSize < $allow_file_size){
          $fileNewName = $fileName;
          $fileDestination = $fileDestination . $fileNewName;
          move_uploaded_file($fileTempName, $fileDestination);
          return $fileNewName;
        }else{
          return false; // error: file size too big
        }
      }else{
        return false; // error: error uploading file
      }
    }
    else if(in_array($fileActualExt, $vid_allowed_exts)){
      if($fileError === 0){
        $allow_file_size = allow_upload_size('upload-video-size', ['in_bytes'=>true]);
        if($fileSize < $allow_file_size){
          $fileNewName = $fileName;
          $fileDestination = $fileDestination . $fileNewName;
          move_uploaded_file($fileTempName, $fileDestination);
          return $fileNewName;
        }else{
          return false; // error: file size too big
        }
      }else{
        return false; // error: error uploading file
      }
    }
    else{
      return false; // error: file ext not allowed
    }
  }

?>