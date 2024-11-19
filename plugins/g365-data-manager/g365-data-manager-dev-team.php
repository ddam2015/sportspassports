<?php
// session_start();


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update session variables with the selected dates
    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date'] = $_POST['end_date'];
}

function mk_upload_image($image_id) {  
  $short_name = $_POST['short_name'];
  $file_name = $_FILES["missingFileToUpload"]["name"];
  $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
  
  // Remove problematic characters such as #, spaces, and other special characters
  $sanitized_file_name = preg_replace('/[^A-Za-z0-9\-_.]/', '', $file_name);
  
  //specify directory to upload to
  $target_dir =  dirname( dirname( dirname(__FILE__) )) . "/themes/g365-press/assets/badges"; 

     
  if(str_ends_with($sanitized_file_name, "-Champions.png") || str_ends_with($sanitized_file_name, "-Runner-Up.png")) {
    $target_dir .=  "/championship-and-runner-up/";
    $new_file_name = $short_name . '-Champions.png';
  } elseif (str_ends_with($sanitized_file_name, "-All-Tournament-Team.png") || str_ends_with($sanitized_file_name, "-All-Tournament-MVP.png")) {
    $target_dir =  $target_dir . "/all-tournament/";
    $new_file_name = $short_name . '-All-Tournament-Team.png';
  } else {
    echo "Unsupported file name.";
    return;
  }
  

//   $target_file = $target_dir . basename($file_name);
  $target_file = $target_dir . '/' . $new_file_name;
  $uploadOk = 1;
   
  // Check if image file is a actual image 
  $check = getimagesize($_FILES["missingFileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists. Should never happen...";
    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["missingFileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large. Max is 500KB";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if ($imageFileType != "png") {
    echo "Sorry, only PNG files are allowed (.png lower case).";
    $uploadOk = 0;
    //if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["missingFileToUpload"]["tmp_name"], $target_file)) {
      echo "File uploaded succesfully";
    } 
//     else {
//       echo "Sorry, there was an error uploading your file.";
//     }
  }
}


if($_FILES['missingFileToUpload']){
  mk_upload_image($_FILES['missingFileToUpload']);
}


if($_POST['image_to_delete']){
  $target_file = basename($_POST["image_to_delete"]);
  $badgeFolder  =  basename( dirname($_POST["image_to_delete"]) );
  $target_dir = dirname( dirname( dirname(__FILE__) )) . "/themes/g365-press/assets/badges/" . $badgeFolder . "/";
  $existing_image_path = $target_dir . $target_file;

  if(unlink($existing_image_path)){
    echo "Delete the file successfully";
  }else{
    echo "could not delete...";
  }
}
if($_POST['filesToDelete']) {
  $filesToDelete = explode(',',$_POST['filesToDelete']);
  foreach($filesToDelete as $_filesToDelete) {
     $target_file = basename($_filesToDelete);
     $badgeFolder  =  basename( dirname($_filesToDelete));
     $target_dir = dirname( dirname( dirname(__FILE__) )) . "/themes/g365-press/assets/badges/" . $badgeFolder . "/";
     $existing_image_path = $target_dir . $target_file;
    if(unlink($existing_image_path)){
      echo "Delete the file successfully";
    }else{
      echo "could not delete...";
    }
  }
 }

if($_POST['imagesToReplace']){
  
  mk_replace_image($_POST['image_id']); 
}

if($_POST['imagesToUpload']) {
 
  foreach($_FILES as $key => $imagesToUpload) {
      
      $target_file = $imagesToUpload['name'];
     
      $badgeFolder =  basename( dirname($key) );
      $target_dir = dirname( dirname( dirname(__FILE__) )) . "/themes/g365-press/assets/badges/" . $badgeFolder . "/"; 
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  
       // Check file size
      if ($imagesToUpload["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }
       
      // Allow certain file formats
      if($imageFileType != "png") {
        echo "Sorry, only PNG files are allowed.";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
        $existing_image_path = $target_dir . $target_file;
         //delete the existing image
        unlink($existing_image_path);
         
        $path = $target_dir . basename($key) .'.'. $imageFileType;

        //upload image
        if(move_uploaded_file($imagesToUpload["tmp_name"], $path)) {
          echo "The file " . htmlspecialchars(basename($imagesToUpload["name"])) . " has been uploaded.";
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      }
  }
}

function mk_replace_image() {
 // echo  "iam in , inside";
  // getting the file to replace name of the target file
  $image_to_replace = explode(',',$_POST["image_to_replace"]);
  $imagesToReplace = explode(',',$_POST['imagesToReplace']);
  foreach($image_to_replace as $key => $_image_to_replace) {
    
    $badgeFolder  =  basename( dirname($_image_to_replace) ); 

    $target_file = basename($_image_to_replace);
    
    $lockFile = $target_dir. "lock.txt";
    
    //Obtain an exclusive lock 
    $lockHandle = fopen($lockFile, 'w');

      if(str_ends_with($target_file, "-Champions.png") || str_ends_with($target_file, "-Runner-Up.png")) {
        $badgeFolder = "championship-and-runner-up";
      } elseif (str_ends_with($file_name, "-All-Tournament-Team.png") || str_ends_with($file_name, "-All-Tournament-MVP.png")) {
      $badgeFolder =   "all-tournament";
      }
      $target_dir = dirname( dirname( dirname(__FILE__) )) . "/themes/g365-press/assets/badges/" . $badgeFolder . "/"; 
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      // Check if image file is a actual image or fake image
      if(isset($_POST["replace"])) {
        $check = getimagesize($_FILES['fileToUpload_'.$imagesToReplace[$key]]["tmp_name"]); // checks the uploaded file
        if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
        } else {
          echo "File is not an image.";
          $uploadOk = 0;
        }
      }

      // Check file size
      if ($_FILES['fileToUpload_'.$imagesToReplace[$key]]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if($imageFileType != "png") {
        echo "Sorry, only PNG files are allowed.";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
        $existing_image_path = $target_dir . $target_file;


     // if(file_exists($existing_image_path)) {
        //delete the existing image
        unlink($existing_image_path);
        
        //upload image
        if(move_uploaded_file($_FILES['fileToUpload_'.$imagesToReplace[$key]]["tmp_name"], $target_dir . $target_file)) {
          echo "The file " . htmlspecialchars(basename($_FILES['fileToUpload_'.$imagesToReplace[$key]]["name"])) . " has been uploaded and replaced the existing image.";       

//           $name_file = $_FILES['fileToUpload_'.$imagesToReplace[$key]]["name"]; 
          
//           $name_file = str_replace('-Champions.png','',$name_file);
//           $name_file = str_replace('-Runner-Up.png','',$name_file);
//           $name_file = str_replace('-All-Tournament-Team.png','',$name_file);
//           $name_file = str_replace('-All-Tournament-MVP.png','',$name_file);

//            global $wpdb;
//           $wpdb_events = $wpdb->prefix . 'g365_events';

//           $sql_query = $wpdb->prepare(
//             "SELECT ev.id,ev.short_name FROM $wpdb_events ev WHERE ev.short_name LIKE '%C2%' "
//           );

//           // Execute the query
//           $sname = $wpdb->get_results($sql_query);
//             var_dump($sname); die();

//             $wpdb->update(
//               $wpdb_events,
//               array(
//                   'short_name' => $name_file,
//               ),
//               array(
//                   'id' => $imagesToReplace[$key],
//               )
//             );   
          
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
    
     
    
   
//     } else {
//       echo "Sorry, the existing image to be replace does not exist.";
//     }
     }
     
  }
  flock($lockHandle, LOCK_UN);
  fclose($lockHandle);
}

function mk_pulling_events($start_date = null, $end_date = null) { 
    global $wpdb; 
    $wpdb_events = $wpdb->prefix . 'g365_events';
    $short_names = [];

    if ($wpdb !== null) {
      // Prepare the SQL statement
      $sql_query = $wpdb->prepare(
        "SELECT ev.id,ev.short_name FROM $wpdb_events ev WHERE ev.eventtime >= %s AND ev.eventtime <= %s ORDER BY ev.short_name",
        $start_date,
        $end_date
      );

      // Execute the query
      $results = $wpdb->get_results($sql_query);
      
      if ($wpdb->last_error) {
        echo 'Database Error: ' . $wpdb->last_error;
        return $short_names;
      }
      
      foreach($results as $event) {
        $event->short_name = preg_replace('/[^A-Za-z0-9\- ]/', '', $event->short_name);
        $short_names[] = $event;
      }
    } else {
      echo 'Error: $wpdb is null.';
    }
    
    return $short_names;
}


function mk_g365_get_event_date($start_date, $end_date) {
    if(isset($_POST["start_date"]) && isset($_POST["end_date"])) {
    $start_date = $_POST["start_date"];
    $end_date =  $_POST["end_date"];
  }
    // format date to match database
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));
    $start_date = str_replace('/', "-", $start_date);
    $end_date = str_replace('/', "-", $end_date);
    
    // make sure we have a value
    if ($start_date === null || $end_date === null) return 'Need date range.';
    if ($start_date >= $end_date) return 'Start date must be before end date';
  
    $short_name = mk_pulling_events($start_date, $end_date);
  
    
    // replace space with dash in $short_name
    foreach ($short_name as &$event) {
      $event->short_name = str_replace(' ', "-", $event->short_name);
      $event->short_name = rtrim($event->short_name, "-");
    }

    
  
    $merged_files = [];
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $directory1 = $document_root . "/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/";
    $directory2 = $document_root . "/wp-content/themes/g365-press/assets/badges/all-tournament/";
  
    // Get list of files from each directory
//     $files1 = scandir($directory1);
//     $files2 = scandir($directory2);
  
    $iterator1 = new DirectoryIterator($directory1);
    $files1 = [];
    foreach($iterator1 as $file) {
      if($file->isfile() && !$file->isDot()) {
        $files1[] = $file->getFilename();
      }
    }
  
    $iterator2 = new DirectoryIterator($directory2);
    $files2 = [];
    foreach($iterator2 as $file) {
      if($file->isfile() && !$file->isDot()) {
        $files2[] = $file->getFilename();
      }
    }
   
    // Remove '.' and '..' from the lists
    $files1 = array_diff($files1, array('..', '.'));
    $files2 = array_diff($files2, array('..', '.'));

    // Merge the arrays
    $merged_files = array_merge($files1, $files2);

    $champions_files = [];
    $runner_up_files = [];
    $mvp_files = [];
    $tournament_files = [];
    $missing_files = [];
    $all_missing_file = [];
    $count_cup = 0;
    
    foreach ($short_name as $substring) {
      $cup = false;
      foreach ($merged_files as $file) {      
         $matched = false;
        // Check for each condition and store files in array
        if($substring->short_name . "-Champions.png" == $file) {      
          $champions_files[$count_cup] = [
            'short_name' => $file,
            'id' => $substring->id
            ];
          $matched = true;
          $cup = true;
        } elseif($substring->short_name . "-Runner-Up.png" == $file) {
          $runner_up_files[$count_cup] = [
            'short_name' => $file,
            'id' => $substring->id
            ];
          $matched = true;
          $cup = true;
        }  elseif($substring->short_name . "-All-Tournament-MVP.png" == $file) {
          $mvp_files[$count_cup] = [
            'short_name' => $file,
            'id' => $substring->id
            ];
          $matched = true;
          $cup = true;
        } elseif($substring->short_name . "-All-Tournament-Team.png" == $file) {
          $tournament_files[$count_cup] = [
            'short_name' => $file,
            'id' => $substring->id
            ];
          $matched = true;
          $cup = true;
        } 
        
      }
      $all_missing_file[$count_cup] = $substring->short_name;
      
      echo $matched; 
     
      if($matched==false) {
         $missingfiles[] =$substring->short_name; 
      } 
      //if($cup == true) {
        $count_cup++;
      //}
        
    }
    
     if(!is_array($missingfiles)) {
          return "Input is not an array.";
        }
        //initialize result array
        $missing_champions = [];
        $missing_runner_up = [];
        $missing_mvp = [];
        $missing_tournament = [];

        //itereate through each element in missingfiles
        foreach($missingfiles as $element) {

          //duplicate the element 4 times and append the desired string
          $missing_champions[] = $element . "-Champions";
          $missing_runner_up[] = $element . "-Runner-Up";
          $missing_mvp[] = $element . "-All-Tournament-MVP";
          $missing_tournament[] = $element . "-All-Tournament-Team";
        }
//       sort($champions_files);
//       sort($runner_up_files);
//       sort($mvp_files);
//       sort($tournament_files);
      sort($missing_champions);
      sort($missing_runner_up);
      sort($missing_mvp);
      sort($missing_tournament);
  
    echo  '<div class="files">
          <button id="all-files" class="all button-primary button-large" onclick="showdiv1()">All files</button>
          <button id="missing-files" class="missing button-primary button-large" onclick="showdiv2()"> Missing files</button>
          <button class="save button-primary button-large" onclick="onSave()" hidden>Save</button>
          </div>';
  
    echo "<div id='foundfiles'>";
    echo '<table>
            <tr class="found_header">
                <th id="titles">Champions</th>
                <th id="titles">Runner Up</th>
                <th id="titles">All Tournament MVP</th>
                <th id="titles">All Tournament Team</th>
            </tr>';

      $maxRowCount = max(
      count($champions_files),
      count($runner_up_files),
      count($mvp_files),
      count($tournament_files)
    );
    $count_missing = 0;
    for ($i = 0; $i < $count_cup; $i++) {
//       $short_name_tournament_files = $short_name_mvp_files = $short_name_runner_up = $short_name_champions = $short_name = '';
//       if (isset($champions_files[$i])) {
//          $short_name = str_replace('-Champions.png','',$champions_files[$i]['short_name']);
//       } elseif (isset($runner_up_files[$i])) {
//         $short_name = str_replace('-Runner-Up.png','',$runner_up_files[$i]['short_name']);
//       } elseif (isset($mvp_files[$i])) {
//         $short_name = str_replace('-All-Tournament-MVP.png','',$mvp_files[$i]['short_name']);
//       } elseif (isset($tournament_files[$i])) {
//         $short_name = str_replace('-All-Tournament-Team.png','',$tournament_files[$i]['short_name']);
//       }
      $short_name = $all_missing_file[$i];
  
    // Display Champions Files
    echo '<tr>
          <td id="award">';
      
    if (isset($champions_files[$i])) {
        $image_id = 'champion_' . $i; //id for each image
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/";
        echo '<div class="container">';
      echo '<div class="file_name" style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $champions_files[$i]['short_name'] . '</div>';
        echo '<img class="badges" id="' . $image_id . '" src="' . $prefix . $champions_files[$i]['short_name'] . '?get_fresh='.time().'" alt="Champion Image"><br>';
        echo '<div class="button-container">';
          echo '<form action="" method="post" enctype="multipart/form-data">';
            echo '<input type="hidden" name="image_id" value="' . $image_id . '">';
           echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="' . $prefix . $champions_files[$i]['short_name'] . '">';
//             echo '<input type="file" name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelect(\'' . $image_id . '\')">';
            echo '<label for="fileInput' . $image_id . '" class="replace button-primary button-large">Replace</label>';
            echo '<input type="file" name="fileToUpload_'.$champions_files[$i]['id'].'" data-id="'.$champions_files[$i]['id'].'" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectReplace(\'' . $image_id . '\')">';
          echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
         // echo '<input name="undo" style="display:none;">';
//             echo '<input type="hidden" name="image_to_delete" value="' . $prefix . $champions_files[$i] . '">';
          echo '<button  data-file-name="' . $champions_files[$i]['short_name'] . '" onclick="highlightDelete(\'' . $image_id . '\', \'/championship-and-runner-up/' . str_replace("'","\'",$champions_files[$i]['short_name']) . '\')" class="delete button-primary button-large" type="button">Delete</button>';
          echo '<input name="delete" style="display:none;">';
          echo '</form>';   
          echo '</div>';
        echo '</div>';
    } else {
        $_short_name = $short_name.'-Champions';
    
        //if ($_short_name && in_array($_short_name,$missing_champions)) {
          $image_id = 'all_files_missing_champion_' . $count_missing; //id for each image
          $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
          $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";

            echo '<div class="container">';
            echo '<div class="image-container">';
            echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
            echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $_short_name . '</div>';
            echo '<div class="button-container">';
              echo '<form action="" method="post" enctype="multipart/form-data">';
               echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';

                echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
               echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
               echo '<input type="file" accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/championship-and-runner-up/' . $_short_name . '">';
              echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
             // echo '<input name="undo" style="display:none;">';
              echo '</form>';
            echo '</div>';
            echo '</div>';
         // }
    }
    echo '</td>';


    // Display Runner Up Files
    echo '<td id="award">';
    if (isset($runner_up_files[$i])) {
        $image_id = 'runner_up_' . $i;
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/";
        echo '<div class="container">';
      echo '<div class="file_name" style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $runner_up_files[$i]['short_name'] . '</div>';
        echo '<img class="badges" id="' . $image_id . '" src="' . $prefix . $runner_up_files[$i]['short_name'] . '?get_fresh='.time().'" alt="Runner Up Image"><br>';
        echo '<div class="button-container">';
        echo '<form action="" method="post" enctype="multipart/form-data">';
          echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="' . $prefix . $runner_up_files[$i]['short_name'] . '">';
          echo '<input type="hidden" name="image_id" value="' . $image_id . '">';
//           echo '<input type="file" name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelect(\'' . $image_id . '\')">';
          echo '<label for="fileInput' . $image_id . '" class="replace button-primary button-large">Replace</label>';
          echo '<input type="file" name="fileToUpload_'.$runner_up_files[$i]['id'].'" data-id="'.$runner_up_files[$i]['id'].'" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectReplace(\'' . $image_id . '\')">';
          echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
         // echo '<input name="undo" style="display:none;">';
//           echo '<input type="hidden" name="image_to_delete" value="' . $prefix . $runner_up_files[$i] . '">';
          echo '<button  data-file-name="' . $runner_up_files[$i]['short_name'] . '" onclick="highlightDelete(\'' . $image_id . '\', \'/championship-and-runner-up/' . str_replace("'","\'",$runner_up_files[$i]['short_name']) . '\')" class="delete button-primary button-large" type="button">Delete</button>';
      echo '<input name="delete" style="display:none;">';
        echo '</form>';   
        echo '</div>';
        echo '</div>';
    } else {
        $_short_name =  $short_name.'-Runner-Up';
        //if ($_short_name && in_array($_short_name,$missing_runner_up)) {
          $image_id = 'all_files_missing_runner_up_' . $count_missing; //id for each image
          $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
          $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";

            echo '<div class="container">';
            echo '<div class="image-container">';
            echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
            echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $_short_name . '</div>';
            echo '<div class="button-container">';
              echo '<form action="" method="post" enctype="multipart/form-data">';
               echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';

                echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
               echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
               echo '<input type="file" accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/championship-and-runner-up/' . $_short_name . '">';
              echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
             // echo '<input name="undo" style="display:none;">';
              echo '</form>';
            echo '</div>';
            echo '</div>';
         // }
    }
    echo '</td>';

    // Display All Tournament MVP Files
    echo '<td id="award">';
    if (isset($mvp_files[$i])) {
        $image_id = 'mvp_' . $i;
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/all-tournament/";
        echo '<div class="container">';
        echo '<div class="file_name" style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $mvp_files[$i]['short_name'] . '</div>';
        echo '<img class="badges" id="' . $image_id . '" src="' . $prefix . $mvp_files[$i]['short_name'] . '?get_fresh='.time().'" alt="MVP Image"><br>';
        echo '<div class="button-container">';
        echo '<form action="" method="post" enctype="multipart/form-data">';
          echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="' . $prefix . $mvp_files[$i]['short_name'] . '">';
          echo '<input type="hidden" name="image_id" value="' . $image_id . '">';
//           echo '<input type="file" name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelect(\'' . $image_id . '\')">';
          echo '<label for="fileInput' . $image_id . '" class="replace button-primary button-large">Replace</label>';
          echo '<input type="file" name="fileToUpload_'.$mvp_files[$i]['id'].'" data-id="'.$mvp_files[$i]['id'].'" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectReplace(\'' . $image_id . '\')">';
          echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
         // echo '<input name="undo" style="display:none;">';
//           echo '<input type="hidden" name="image_to_delete" value="' . $prefix . $mvp_files[$i] . '">';
          echo '<button  data-file-name="' . $mvp_files[$i]['short_name'] . '" onclick="highlightDelete(\'' . $image_id . '\', \'/all-tournament/' . str_replace("'","\'",$mvp_files[$i]['short_name']) . '\')" class="delete button-primary button-large" type="button">Delete</button>';
         echo '<input name="delete" style="display:none;">';
         echo '</form>';   
         echo '</div>';
        echo '</div>';
    }  else {
        $_short_name =  $short_name.'-All-Tournament-MVP';

        //if ($_short_name && in_array($_short_name,$missing_mvp)) {
          $image_id = 'all_files_missing_mvp_' . $count_missing; //id for each image
          $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
          $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";

            echo '<div class="container">';
            echo '<div class="image-container">';
            echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
            echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $_short_name . '</div>';
            echo '<div class="button-container">';
              echo '<form action="" method="post" enctype="multipart/form-data">';
               echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';

                echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
               echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
               echo '<input type="file" accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/all-tournament/' . $_short_name . '">';
              echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
             // echo '<input name="undo" style="display:none;">';
              echo '</form>';
            echo '</div>';
            echo '</div>';
          //}
    }
    echo '</td>';

    // Display All Tournament Team Files
    echo '<td id="award">';
    if (isset($tournament_files[$i])) {
        $image_id = 'tournament_' . $i;
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/all-tournament/";
        echo '<div class="container">';
        echo '<div class="file_name" style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $tournament_files[$i]['short_name'] . '</div>';
        echo '<img class="badges" id="' . $image_id . '" src="' . $prefix . $tournament_files[$i]['short_name'] . '?get_fresh='.time().'" alt="All Tournament Team Image"><br>';
        echo '<div class="button-container">';
        echo '<form action="" method="post" enctype="multipart/form-data">';
          echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="' . $prefix . $tournament_files[$i]['short_name'] . '">';
          echo '<input type="hidden" name="image_id" value="' . $image_id . '">';
//           echo '<input type="file" name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelect(\'' . $image_id . '\')">';
          echo '<label for="fileInput' . $image_id . '" class="replace button-primary button-large">Replace</label>';
          echo '<input type="file" name="fileToUpload_'.$tournament_files[$i]['id'].'" data-id="'.$tournament_files[$i]['id'].'" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectReplace(\'' . $image_id . '\')">';
          echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
         // echo '<input name="undo" style="display:none;">';
//           echo '<input type="hidden" name="image_to_delete" value="' . $prefix . $tournament_files[$i] . '">';
         echo '<button data-file-name="' . $tournament_files[$i]['short_name'] . '" onclick="highlightDelete(\'' . $image_id . '\', \'/all-tournament/' . str_replace("'","\'",$tournament_files[$i]['short_name']) . '\')" class="delete button-primary button-large" type="button">Delete</button>';
         echo '<input name="delete" style="display:none;">';
        echo '</form>';   
       echo '</div>';
        echo '</div>';
    } else {
        $_short_name = $short_name.'-All-Tournament-Team';

        //if ($_short_name && in_array($_short_name,$missing_tournament)) {
          $image_id = 'all_files_missing_tournament_' . $count_missing; //id for each image
          $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
          $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";

            echo '<div class="container">';
            echo '<div class="image-container">';
            echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
            echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $_short_name . '</div>';
            echo '<div class="button-container">';
              echo '<form action="" method="post" enctype="multipart/form-data">';
               echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';

                echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
               echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
               echo '<input type="file" accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/all-tournament/' . $_short_name . '">';
              echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
             // echo '<input name="undo" style="display:none;">';
              echo '</form>';
            echo '</div>';
            echo '</div>';
        //  }
    }
    echo '</td>';
      $count_missing++;
    }
  echo '</tr></table></div>';
  
   echo '<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
         <script>
          
            $(document).ready(function() {
              showdiv1();
              $(".all").addClass("active");
              });
          
            document.addEventListener("DOMContentLoaded", function() {
              const content = document.querySelector("#foundfiles");
              const items_per_page = 5;
              let current_page = 0;
              const items = Array.from(content.getElementsByTagName("tr")).slice(1);
            
             function showPage(page) {
               const startIndex = page * items_per_page;
               const endIndex = startIndex + items_per_page;
               items.forEach((item, index) => {
                 item.classList.toggle("hidden", index < startIndex || index >= endIndex);
               });
               updateActiveButtonStates();
             }
            
             function createPageButtons() {
               const totalPages = Math.ceil(items.length / items_per_page);
               const paginationContainer = document.createElement("div");
               const paginationDiv = document.body.appendChild(paginationContainer);
               paginationContainer.classList.add("pagination");
              
               for(let i = 0; i < totalPages; i++) {
                 const pageButton = document.createElement("button");
                 pageButton.textContent = i + 1; 
                 pageButton.addEventListener("click", () => {
                     current_page = i;
                     showPage(current_page);
                     updateActiveButtonStates();
                   });
                 content.appendChild(paginationContainer);
                 paginationDiv.appendChild(pageButton);
                 }
             }
            
             function updateActiveButtonStates() {
               const pageButtons = document.querySelectorAll(".pagination button");
               pageButtons.forEach((button, index) => {
                 if(index === current_page) {
                   button.classList.add("active");
                 } else {
                   button.classList.remove("active");
                 }
               });
             }
             createPageButtons();
             showPage(current_page);
           });
           
           document.addEventListener("DOMContentLoaded", function() {
              const content = document.querySelector("#missingfiles");
              const items_per_page = 5;
              let current_page = 0;
              const items = Array.from(content.getElementsByTagName("tr")).slice(1);
            
             function showPage(page) {
               const startIndex = page * items_per_page;
               const endIndex = startIndex + items_per_page;
               items.forEach((item, index) => {
                 item.classList.toggle("hidden", index < startIndex || index >= endIndex);
               });
               updateActiveButtonStates();
             }
            
             function createPageButtons() {
               const totalPages = Math.ceil(items.length / items_per_page);
               const paginationContainer = document.createElement("div");
               const paginationDiv = document.body.appendChild(paginationContainer);
               paginationContainer.classList.add("pagination");
              
               for(let i = 0; i < totalPages; i++) {
                 const pageButton = document.createElement("button");
                 pageButton.textContent = i + 1; 
                 pageButton.addEventListener("click", () => {
                     current_page = i;
                     showPage(current_page);
                     updateActiveButtonStates();
                   });
                 content.appendChild(paginationContainer);
                 paginationDiv.appendChild(pageButton);
                 }
             }
            
             function updateActiveButtonStates() {
               const pageButtons = document.querySelectorAll(".pagination button");
               pageButtons.forEach((button, index) => {
                 if(index === current_page) {
                   button.classList.add("active");
                 } else {
                   button.classList.remove("active");
                 }
               });
             }
             createPageButtons();
             showPage(current_page);
           });
           
            //Declare arrays to store files
            const replaceFiles = [];
            const filesToDelete = [];
            const uploadFiles = [];
            
            function onSave(){
                // create a JS form.
                let form = document.createElement("form");
                form.action = window.location.href;
                form.method = "POST";
                form.enctype = "multipart/form-data";
                form.type = "multipart/data";
                  document.body.appendChild(form);
                form.style= "display:hidden";
               
                var testInput = document.createElement("input");
                testInput.type = "text";
                testInput.name = "test";
                testInput.value = "test";
                form.appendChild(testInput);
               
                
                // files to add or edit => as many file inputs with name looking like name=[filesToAdd][\'runner-up/test.png\',\'runner-up/test.png\']
        /*        uploadFiles.concat(replaceFiles).map(fileToAdd => {
                //console.log(fileToAdd["fileName"]);
                //console.log(fileToAdd.fileName);
                 var filesToAddInput = document.createElement("input");
                  filesToAddInput.type = "file";
                  filesToAddInput.name = "[filesAdd]["+ fileToAdd.realPath +"]";
                  filesToAddInput.value =  "";
                  form.appendChild(filesToAddInput);
                })*/
                
                // files to add or edit => as many file inputs with name looking like name=[filesToAdd][\'runner-up/test.png\',\'runner-up/test.png\']
             
                uploadFiles.concat(replaceFiles).map(fileToAdd => {
                   

                    // Create a container div
                    var container = document.createElement("div");

                   

                    // Create a file input for actual file selection
                    var fileInput = document.createElement("input");
                    fileInput.type = "file";
                    fileInput.name = "[filesAdd][" + fileToAdd.realPath.name + "]";
                    
                    // Append both inputs to the container
                    container.appendChild(fileInput);
                    if(fileToAdd.realPath) {
                      container.appendChild(fileToAdd.realPath);
                     }

                    // Append the container to the form
                    
                    form.appendChild(container);
                });
                
                // Create a text input to display the file name
                var fileNameInput = document.createElement("input");
                fileNameInput.type = "text";
                fileNameInput.name = "image_to_replace";
                fileNameInput.readOnly = true; // Make it read-only
                fileNameInput.setAttribute("value",replaceFiles.map(filesTodelet => filesTodelet.imagePath).join(","));
                form.appendChild(fileNameInput);
                
                // files to Delete => make one text input with value as a string, comma separated of all files to delete. name = "filesToDelete"
                // value = "/champions/dfkdjkfjdf.png,/runnter-up/kdfjdkjd.
                var filesToDeleteInput = document.createElement("input");
                filesToDeleteInput.type = "text";
                filesToDeleteInput.name = "filesToDelete";
                //console.log(filesToDelete.map(filesTodelet => filesTodelet.fileName).join(","));
                filesToDeleteInput.setAttribute("value",filesToDelete.map(filesTodelet => filesTodelet.fileName).join(","));
                
                var imagesToReplaceInput = document.createElement("input");
                imagesToReplaceInput.type = "text";
                imagesToReplaceInput.name = "imagesToReplace";
                imagesToReplaceInput.setAttribute("value",replaceFiles.map(imagesToRe => imagesToRe.id).join(","));
                
                var imagesToUploadInput = document.createElement("input");
                imagesToUploadInput.type = "text";
                imagesToUploadInput.name = "imagesToUpload";
                imagesToUploadInput.setAttribute("value",uploadFiles.map(imagesToRe => imagesToRe.realPath.name).join(","));
                
                form.appendChild(imagesToUploadInput);
                 
                form.appendChild(imagesToReplaceInput);
                
                form.appendChild(filesToDeleteInput);
                
                form.appendChild(document.getElementById("start_date"));
                form.appendChild(document.getElementById("end_date" ));
                 
                //console.log(form);
                //console.log("Form Data:", new FormData(form));
                form.submit();
            }
            
            function showdiv1() {
              $("#foundfiles").show();
              $("#missingfiles").hide();
              $(".missing").removeClass("active");
              $(".all").addClass("active");
            }
             
             function showdiv2() {
               $("#foundfiles").hide();
               $("#missingfiles").show();
               $(".all").removeClass("active");
               $(".missing").addClass("active");
            }
            
            function handleFileSelectReplace(imageId) {
              highlightReplace(imageId);
            
              const fileInput = document.getElementById("fileInput" + imageId);
              const imageToReplace = document.getElementById("image_to_replace" + imageId);
              const fileToUploadInput = document.getElementById("fileInput" + imageId);
//               fileInput.closest("form").submit();
//console.log(fileInput);
//console.log(fileInput.value);
              const file = fileInput.files[0];
              //console.log(file["name"]);
              const realPath = fileInput.name;
            //  console.log("name",fileInput.name);

              
              if(file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                  const image = document.getElementById(imageId);
                  image.src = e.target.result;
                  
                  //Push the new file into the array
                  replaceFiles.push({
                    imageId: imageId,
                    fileName: file["name"],
                    realPath : fileInput,
                    imagePath : imageToReplace.value,
                    id:fileToUploadInput.getAttribute("data-id")
                  });
                  
                  //console.log("New Files:", replaceFiles);
                };
                reader.readAsDataURL(file);
              }
            }
            
            function handleFileSelect(imageId) {
              const imageToReplace = document.getElementById("image_to_replace" + imageId);
              const image = document.getElementById(imageId);
              let fileToUploadInput = document.getElementById("fileInput" + imageId);
              
              fileToUploadInput.value = "";
              //console.log(fileToUploadInput.value);
              image.src = imageToReplace.value;
              
              var addclass = "container";
              var $button = $("#" + imageId);  
              var $container = $button.closest(".replace-container");
              
              if($container.length > 0) {
                replaceFiles.pop();
                //Remove any existing classes from the container
                $container.removeClass().addClass(addclass);

                 //Hide the delete button only in the clicked container
                $container.find(".delete").show();
                $container.find(".replace").show();
                $container.find(".undo").hide();
                $(".save").hide();
              }
              var $containerDelete = $button.closest(".delete-container");
              if($containerDelete.length > 0) {
                filesToDelete.pop();           
                //Remove any existing classes from the container
                $containerDelete.removeClass().addClass(addclass);

                 //Hide the delete button only in the clicked container
                $containerDelete.find(".delete").show();
                $containerDelete.find(".replace").show();
                $containerDelete.find(".undo").hide();
                $(".save").hide();
              }
              
               var $containerUpload = $button.closest(".upload-container");
            
               if($containerUpload.length > 0) {
                  filesToDelete.pop();           
                  //Remove any existing classes from the container
                  $containerUpload.removeClass().addClass(addclass);

                   //Hide the delete button only in the clicked container
                  $containerUpload.find(".delete").show();
                  $containerUpload.find(".replace").show();
                  $containerUpload.find(".undo").hide();
                  $(".save").hide();
                }
              
             
              var $containerMissing = $button.closest(".image-container");
              
              //Remove any existing classes from the container
              $containerDelete.removeClass().addClass(addclass);
              $containerUpload.removeClass().addClass(addclass);
              $container.removeClass().addClass(addclass);
              
               //Hide the delete button only in the clicked container
              $containerMissing.find(".upload").show();
              $containerMissing.find(".undo").hide();
              $(".save").hide();
            }
            
            function handleFileSelectUpload(imageId) {
              highlightUpload(imageId);
            
              const fileInput = document.getElementById("fileInput" + imageId);
              const label = document.querySelector("[for=\'fileInput" + imageId + "\']");
//               fileInput.closest("form").submit();
              const file = fileInput.files[0];
              const fileValue = fileInput.value;
              const realPath = fileInput.name;
              if(file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                  const image = document.getElementById(imageId);
                  image.src = e.target.result;
                  
                  //Push the new file into the array
                  uploadFiles.push({
                    imageId: imageId,
                    file: file,
                    fileValue: fileValue,
                    realPath: fileInput
                  });
                  
                  //console.log("New Files:", uploadFiles);
                };
                reader.readAsDataURL(file);
              }
            }
            
            function highlightReplace(imageId) {
              var addclass = "replace-container";
              var $button = $("#" + imageId);  
              var $container = $button.closest(".container");
              
              //Remove any existing classes from the container
              $container.removeClass().addClass(addclass);
              
              //Hide the delete button only in the clicked container
              $container.find(".delete").hide();
              $container.find(".replace").hide();
              $container.find(".undo").show();
              $(".save").show();
              
              return $button;
            }
            
            function highlightDelete(imageId, fileName) {
              var addclass = "delete-container";
              var $button = $("#" + imageId);  
              var $container = $button.closest(".container");
              
              //Remove any existing classes from the container
              $container.removeClass().addClass(addclass);
              
              //Hide the delete button only in the clicked container
              $container.find(".replace").hide();
              $container.find(".delete").hide();
              $container.find(".undo").show();
              $(".save").show();
              
              //Add files to array
              filesToDelete.push({
                imageId: imageId, 
                fileName: fileName
              });
              
              //console.log("Files to Delete:", filesToDelete);
              
              return $button;
            }
            
            function highlightUpload(imageId) {
              var addclass = "upload-container";
              var $button = $("#" + imageId);  
              var $container = $button.closest(".container");
              
              //Remove any existing classes from the container
              $container.removeClass().addClass(addclass);
              
              //Hide the delete button only in the clicked container
              $container.find(".upload").hide();
              $container.find(".undo").show();
              $(".save").show();
              
              return $button;
            }

         </script>';

  
   echo "<div id='missingfiles'>";
   echo '<table>
            <tr>
                <th id="titles">Champions</th>
                <th id="titles">Runner Up</th>
                <th id="titles">All Tournament MVP</th>
                <th id="titles">All Tournament Team</th>
            </tr>';
  
      $maxRowCount =  max(
        count($missing_champions), 
        count($missing_runner_up), 
        count($missing_mvp), 
        count($missing_tournament)
       );
  
    for ($i = 0; $i < $maxRowCount; $i++) {   
    // Display Champions Files
    echo '<tr class="missing-container">
            <td id="award">';
      
    if (isset($missing_champions[$i])) {
        $image_id = 'missing_champion_' . $i; //id for each image
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
        $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";
     
        if(file_exists($dir . "championship-and-runner-up/". $missing_champions[$i].".png") !=true) {
          echo '<div class="container">';
          echo '<div class="image-container">';
          echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
          echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $missing_champions[$i] . '</div>';
          echo '<div class="button-container">';
            echo '<form action="" method="post" enctype="multipart/form-data">';
             echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';

              echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
             echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
             echo '<input type="file" accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/championship-and-runner-up/' . $missing_champions[$i] . '">';
            echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
           // echo '<input name="undo" style="display:none;">';
            echo '</form>';
          echo '</div>';
          echo '</div>';
        }
    } 
    echo '</td>';
      
    echo '<td id="award">';
    if (isset($missing_runner_up[$i])) {
        $image_id = 'missing_runner_up_' . $i; //id for each image
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
        $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";
     
        if(file_exists($dir . "championship-and-runner-up/". $missing_runner_up[$i].".png") !=true) {
          echo '<div class="container">';
          echo '<div class="image-container">';
          echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
          echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $missing_runner_up[$i] . '</div>';
          echo '<div class="button-container">';
            echo '<form action="" method="post" enctype="multipart/form-data">';
             echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';
  //             echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
              echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
              echo '<input type="file" accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/championship-and-runner-up/' . $missing_runner_up[$i] . '">';
            echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
            //echo '<input name="undo" style="display:none;">';
            echo '</form>';
          echo '</div>';
          echo '</div>';
        }
    } 
    echo '</td>';
      
    echo '<td id="award">';
    if (isset($missing_mvp[$i])) {
        $image_id = 'missing_mvp_' . $i; //id for each image
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
        $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";
     
        if(file_exists($dir . "all-tournament/". $missing_mvp[$i].".png") !=true) {
          echo '<div class="container">';
          echo '<div class="image-container">';
          echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
          echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $missing_mvp[$i] . '</div>';
          echo '<div class="button-container">';
            echo '<form action="" method="post" enctype="multipart/form-data">';
             echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';
  //             echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
            echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
            echo '<input type="file"  accept="image/png" old-name="fileToUpload" style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')"  name="/all-tournament/' . $missing_mvp[$i] . '">';
            echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
            //echo '<input name="undo" style="display:none;">';
            echo '</form>';
          echo '</div>';
          echo '</div>';
        }
    } 
    echo '</td>';
      
    echo '<td id="award">';
    if (isset($missing_tournament[$i])) {
        $image_id = 'missing_tournament_' . $i; //id for each image
        $prefix = get_site_url() . "/wp-content/themes/g365-press/assets/badges/";
      
        $dir = dirname( dirname( dirname(__FILE__) ))."/themes/g365-press/assets/badges/";
     
        if(file_exists($dir . "all-tournament/". $missing_tournament[$i].".png") !=true) {
          echo '<div class="container">';
          echo '<div class="image-container">';
          echo "<img class='badges' style='max-width: 50% !important;' id='$image_id' src='$prefix" . "Passport-P-2023.png' alt='Missing Image'><br>";
          echo '<div style="font-family: dharma-gothic-e, sans-serif; font-size: 1.2rem;">' . $missing_tournament[$i] . '</div>';
          echo '<div class="button-container">';
            echo '<form action="" method="post" enctype="multipart/form-data">';
             echo '<input type="hidden" name="image_to_replace" id="image_to_replace' . $image_id . '" value="'.$prefix.'Passport-P-2023.png">';
  //             echo '<input type="file" name="missingFileToUpload" id="fileToUpload' . $image_id . '" onchange="this.closest(\'form\').submit()" style="display: none;">';
            echo '<label for="fileInput' . $image_id . '" class="upload button-primary button-large">Upload</label>';
            echo '<input type="file"  accept="image/png" old-name="fileToUpload"  style="display:none;" id="fileInput' . $image_id . '" onchange="handleFileSelectUpload(\'' . $image_id . '\')" name="/all-tournament/' . $missing_tournament[$i] . '">';
            echo '<button for="fileInput' . $image_id . '" onclick="handleFileSelect(\'' . $image_id . '\')" type="button" hidden class="undo button-primary button-large">Undo</button>';
            //echo '<input name="undo" style="display:none;">';
            echo '</form>';
          echo '</div>';
          echo '</div>';
        }
    } 
    echo '</td>';
  }
echo "</tr></table></div>";
  
}

//get all event profile data by event name
function cj_g365_get_event_data($event = null, $truncate = false) {
	//make sure we have a value
	if( $event === null ) return 'Need Event URL to start build.';

	global $wpdb;
	//all the tables we have to get data from
	$wpdb_players = $wpdb->g365_players;
	$wpdb_awards = $wpdb->g365_awards;
	$wpdb_award_refs = $wpdb->g365_award_refs;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	//see if we have an id or name
	if( is_numeric($event) ) {
		$event = intval( $event );
		$data_columns = $wpdb->get_results(
			"SELECT ev.*, org.name as org_name, org.nickname as org_url, org.social as org_social
			FROM $wpdb_events AS ev
			LEFT JOIN $wpdb_orgs AS org ON ev.org=org.id
			WHERE ev.id = $event;"
		);
	} else {
		$event = sanitize_text_field( $event );
		$data_columns = $wpdb->get_results(
			"SELECT ev.*, org.name as org_name, org.nickname as org_url, org.social as org_social
			FROM $wpdb_events AS ev
			LEFT JOIN $wpdb_orgs AS org ON ev.org=org.id
			WHERE ev.name = '$event';"
		);
	}
	//return message if we can't find event record
	if( empty($data_columns) ) return "Couldn't retrieve event for this url.";
	//Simplify the object for a single record
	$data_columns = $data_columns[0];
  //if it's the default club team add a link var in
  if( $event === 0 ) $data_columns->level_link = 'hide';
	//only return basic event data
	if( $truncate )	return $data_columns;
	//Extract event id to use in queries
	$ev_id = $data_columns->id;
	//See if event has awards and add them to the tree
	$data_columns->awards = $wpdb->get_results(
		"SELECT pl.id as player_id, aw.name as award_type, ar.class as grad_class, aw.logo_img as award_img, ar.name as award_title
		FROM $wpdb_award_refs AS ar
		JOIN $wpdb_awards AS aw ON ar.award=aw.id
		JOIN $wpdb_players AS pl ON ar.player=pl.id
		WHERE ar.event = $ev_id AND ar.enabled = 1
		ORDER BY FIELD(ar.award, 1, 5, 4, 3, 9, 2, 10, 14, 16), ar.class, pl.name;"
	);
	if( !empty($data_columns->awards) ) {
		$data_columns->award_types = array();
    $test = array();
		$awards_process =  new stdClass();
		foreach( $data_columns->awards as $dex => $vals ) {
			//organize award data by event_id->award_id[grad_class][award_item]
//   			$awards_process->{$vals->award_type}->{$vals->grad_class}[] = $vals;   
  			$awards_process->{$vals->award_type}[$vals->grad_class][] = $vals;
			//add event id to positive data array if we don't already have it
			if( !in_array($vals->award_type, $data_columns->award_types) ) $data_columns->award_types[] = $vals->award_type;
		}
		//sort grad_class in descending order;
	// 	rsort($data_columns->award_classes);
		//add awards to main object
		$data_columns->awards = $awards_process;
	}
	//Get all players camp stats
	$data_columns->stats = $wpdb->get_results(
		"SELECT pl.id as player_id, pl.name, pl.nickname as player_url, org.name as player_club, org.nickname as club_url, st.profile_img, st.evaluation, st.strengths, st.weaknesses, st.stats, st.trends, st.video, st.enabled
		FROM $wpdb_stats AS st
		JOIN $wpdb_players AS pl ON st.player=pl.id
		LEFT JOIN $wpdb_orgs AS org ON pl.club_team=org.id
		WHERE st.event = $ev_id
		ORDER BY pl.name;",
		OBJECT_K
	);
	return $data_columns;
}


function cj_g365_team_standing($arg = null, $type = null){
//   echo("<script>console.log('stands: " . $arg['is_dcp_only'] . " ');</script>");
  // $arg[0]: 2022; $arg[1]: level keys; $arg[2]: ; $arg[3]: /wp-content/uploads/org-logos/; $arg[4]: /wp-content/uploads/org-logos/g365_blank-placeholder_400x300.png;
  $key_level = (g365_return_keys('g365_grade_key'));
  $get_lv_key = array_search($arg['post_gp_lv'], $key_level); //should give 17U
  if($arg['is_dcp_only'] === true){ $is_dcp = true; }else{ $is_dcp = $arg['is_dcp_only']; }
//    echo("<script>console.log('stands1: " . $is_dcp . " ');</script>");
//   if($arg['is_unlocked_dcp_ev'] === true){ $is_unlocked_dcp_ev = true; }else{ $is_unlocked_dcp_ev = $arg['is_unlocked_dcp_ev']; }
  !empty($arg['is_unlocked_dcp_ev']) ? $is_unlocked_dcp_ev = $arg['is_unlocked_dcp_ev'] : $is_unlocked_dcp_ev = '' ; //this is empty so should return as ''
//    echo("<script>console.log('stands2: " . $arg['is_dcp_only'] . " ');</script>");
  if($arg['brand'] === 'HHH'){
//      echo("<script>console.log('stands3: " . $arg['brand'] . " ');</script>");
    $g365_club_team_stat = cj_g365_club_team_stat($arg['is_dcp_ev'], null, null, null, $arg['post_year'], 9, [$get_lv_key, null, 'is_standing_only', 'by-level', $_POST['cts_type'], 'is_dcp'=>$is_dcp, 'is_dcp_ev'=>$arg['is_dcp_ev'], 'post_ros_dvs'=>$arg['post_ros_dvs'], 'is_unlocked_dcp_ev'=>$is_unlocked_dcp_ev]);
//     echo("<script>console.table('g365_club_team_stat: " . $g365_club_team_stat[0] . " ');</script>");
    if(!empty($arg['is_dcp_ev'])){
      $cts_lv_type = g365_submenu_type(['post_year'=>$arg['post_year'], 'post_gp_lv'=>$arg['post_gp_lv'], 'lv_play'=>$arg['post_ros_dvs']], 15);
    }else{
      $cts_lv_type = g365_submenu_type(['post_year'=>$arg['post_year'], 'post_gp_lv'=>$arg['post_gp_lv'], 'lv_play'=>$arg['post_ros_dvs']], 14);
    }
  }else{
    $g365_club_team_stat = g365_club_team_stat(null, null, null, null, $arg['post_year'], 7, [$get_lv_key, null, 'is_standing_only', 'by-level', $_POST['cts_type'], 'is_dcp'=>$is_dcp, 'is_dcp_ev'=>$arg['is_dcp_ev'], 'post_ros_dvs'=>$arg['post_ros_dvs'], 'is_unlocked_dcp_ev'=>$is_unlocked_dcp_ev]);
    $cts_lv_type = g365_submenu_type(['post_year'=>$arg['post_year'], 'post_gp_lv'=>$arg['post_gp_lv'], 'lv_play'=>$arg['post_ros_dvs']], 6);
  }
  $g365_get_site_url = g365_get_site_url(null, 'default-root');
  $club_team_tb_form = club_team_tb_form(); 
  $key_level = (g365_return_keys('g365_grade_key'));
  
  // Use this menu filter options for only unlocked DCP events
  if($is_unlocked_dcp_ev == true){
    $cts_lv_type = g365_submenu_type(['post_year'=>$arg['post_year'], 'post_gp_lv'=>$arg['post_gp_lv'], 'lv_play'=>$arg['post_ros_dvs']], 12);
  }
  $cts_js_caller = cj_cts_dialog_js();
  $select_option = '
    <div class="grid-x hhh-scouting-dropdown">
      <div class="flex item-center">
        <div>'.$cts_lv_type.'</div>
      </div>
    </div>
  ';
  return [$select_option, $g365_club_team_stat, $club_team_tb_form, $key_level, $cts_js_caller, $g365_get_site_url, g365_message()['not_available'], g365_message()['team_standing']];
}

function cj_cts_dialog_js($arg = null, $type = null){
  $script  = '<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">';
  $script .= '<link rel="stylesheet" href="/resources/demos/style.css">';
  $script .= '<script src="https://code.jquery.com/jquery-3.6.0.js"></script>';
  $script .= '<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>';
  switch($type){
    case 'dcp-tm-ros':
      $script .= '
        <script>
          var box_score_dialog = $("#dialong_div").dialog({
            autoOpen: false, 
            open: function (event, ui){$("body").css({ overflow: "hidden" });$(".pl_box_score").css({height:"900px", overflow:"auto"});},
            close: function(event, ui){$("body").css({ overflow: "visible" });},
            open: function (event, ui){$(".ui-dialog").css({ margin: "40px" });$("#masthead").css({display: "block"});$("#site-footer").css({display: "block"});$(".full_width_container").css({display: "block"});},
            autoOpen: false,
            modal: true,
            width: "300px",
            margin: 0,
            closeOnEscape: true,
            responsive: true
          });
          function dcp_tm_ros(pointer){ 
            var pl_id = pointer.dataset.plId;
            var site_url = pointer.dataset.urlLink;
            var ev_id = pointer.dataset.evId;
            var url = site_url+"/account/dcp/teams/"+ev_id+"/?type=player-event&pl="+pl_id;

            box_score_dialog.html("Loading...").dialog({title:"Recruit Player"}).dialog("open")         
            $.get(url, function(data) {
              var tempDiv = $("<div>").html(data);
              var eventContent = tempDiv.find(".dcp-event").html();
              box_score_dialog.html(eventContent);
              return false;
            });
          }
        </script>
      ';
      return $script;
      break;
  }
      $script .= '
        <script>
          function view_result(result_team_id){$("#"+result_team_id+"-result_box").toggle();}
          var box_score_dialog = $("#dialong_div").dialog({
            autoOpen: false, 
            open: function (event, ui){$("body").css({ overflow: "hidden" });$(".pl_box_score").css({height:"900px", overflow:"auto"});},
            close: function(event, ui){$("body").css({ overflow: "visible" });},
            open: function (event, ui){$(".ui-dialog").css({ margin: "40px" });$("#masthead").css({display: "block"});$("#site-footer").css({display: "block"});$(".full_width_container").css({display: "block"});},
            autoOpen: false,
//             position: { my: "left", at: "top+150", of: window },
            modal: true,
            width: "100%",
//             minWidth: 390,
//            maxWidth: 1200,
            margin: 0,
            closeOnEscape: true,
            responsive: true
          });
          function pl_box_score(pointer){
            var game_id = pointer.dataset.gameId;
            var gamed_date = pointer.dataset.gameDate;
            if(typeof gamed_date === "undefined"){ gamed_date = ""; }else{ gamed_date = gamed_date; }
            var team_id = pointer.dataset.teamId;
            var event_name = pointer.dataset.eventName;
            var select_year = pointer.dataset.selectYear;
            var url = pointer.dataset.url;
            $(".tabs-panel").addClass("dialog-active");
            box_score_dialog.html("Loading...").load(url+"/club-team-standing/team-box-score/_/_/"+team_id+"/"+game_id+"/"+select_year+"/").dialog({title: event_name+gamed_date}).dialog("open");
            return false;
          }
        </script>
      ';
  return $script;
}


function cj_g365_club_team_stat($event_id = null, $team_id = null, $org_id, $opponent_id = null, $year = null, $type, $arg = null){
  global $wpdb; $default_pct = '0.59';
  $dbs = json_decode(dbs());
  $date_format = g365_date_format($year, 1);
//   echo 'date: ' . $date_format . ' ';
  $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
  $filter_game = " WHERE orgs.id = $org_id AND events.eventtime BETWEEN $date_format ";
  $order_by = " ORDER BY events.eventtime DESC ";
  $include_field = " rosters.id AS roster_id, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team  ) AS opponent_id, ";
  $case_game_result = " (CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END) AS game_result, (CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END) AS game_result_label ";
  switch($type){
    case 1: // List of all program games
      if(!empty($event_id)){ // Get games on certain event
        $filter_game = " $filter_game AND events.id = $event_id ";
      }
      elseif(empty($event_id)){// Get all games by Org
        $filter_game = $filter_game;      
      }else{
        echo "Missing event id";
      }
      $order_by = " $order_by, teams.level ASC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 2: // Event only
      $include_field = "DISTINCT events.id AS event_id, events.eventtime AS event_time, events.name AS event_name, events.org AS event_org";
      $group_by = " GROUP BY events.id ";
      return $wpdb->get_results("SELECT $include_field $joins $filter_game $group_by $order_by");
      break;
    case 3: // Opponent only
      $include_field = " DISTINCT teams.search_list AS team_name ";
      $filter_game = " WHERE rosters.id = $opponent_id ";
      return $wpdb->get_results("SELECT $include_field $joins $filter_game");
      break;
    case 4: // Year only. Need Org id only
      $include_field = " DISTINCT YEAR(events.eventtime) AS event_date ";
      $filter_game = " WHERE orgs.id = $org_id ";
      $group_by = " GROUP BY events.eventtime ";
      $order_by = " ORDER BY YEAR(events.eventtime) DESC ";
      return $wpdb->get_results("SELECT $include_field $joins $group_by $order_by");
      break;
    case 5: // With team id
      if(empty($team_id)) return 'Need team id to process';
      $filter_game = " WHERE orgs.id = $org_id AND teams.id = $team_id AND games.start_time BETWEEN $date_format ";
      $order_by = " $order_by, teams.level ASC, games.start_time DESC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 6: // Overall team/club statistics. Need org id only
      $filter_game = " WHERE orgs.id = $org_id AND games.start_time BETWEEN $date_format ";
      $order_by = " $order_by, teams.level ASC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 7: // Club team standing
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
      }else{
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";
          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";
          $tb_1_fields = " inner_tb.* ";
          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");        
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
    case 8:
      // 0: game id. 1: team id.
//       !empty($arg[0]) ? $game_id = 'AND games.id = ' . $arg[0] : $game_id = '';
//       !empty($arg[1]) ? $team_id = 'AND teams.id = ' . $arg[1]: $team_id = '';
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $include_field = " orgs.profile_img AS org_logo, rosters.id AS roster_id, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, rosters.level AS level_of_play, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ";
      $filter_game = " WHERE games.start_time BETWEEN $date_format AND games.id = $arg[0] AND teams.id = $arg[1] ";
//       $filter_game = " WHERE games.start_time BETWEEN $date_format $game_id $team_id ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game");
      break;
     case 9: // Club team standing
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 7164 ";
        if(!empty($dcp_ev)){ 
          $is_dcp = " AND events.org = 7164 AND events.id = $dcp_ev ";
        }
      }else{ 
        $is_dcp = "";
      }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
      }else{
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";
          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";
          $tb_1_fields = " inner_tb.* ";
          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
          }
          if(empty($arg[4])){ 
            $arg[4] = "win"; 
          }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");        
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
  }
}


function cj_get_club_lifetime_events($org_id = null, $type){
  
  global $wpdb;
	//all the tables we have to get data from
	$wpdb_rosters = $wpdb->g365_rosters;
  
  switch($type){
    case 1:
      
      $data_columns = $wpdb->get_results(
			"SELECT DISTINCT event as events
			FROM $wpdb_rosters
			WHERE org = $org_id AND event != 0;"
		  );
      return $data_columns;
    break;   
  }
}


function cj_championship_award_get_all( $type, $org_id=null){
  global $wpdb;
  $dbs = json_decode(dbs());
  $year = g365_date_format($year, 1);
  $joins = " FROM $dbs->games game LEFT JOIN $dbs->events ev ON ev.id = game.event_id LEFT JOIN $dbs->rosters home_ros ON home_ros.id = game.home_team LEFT JOIN $dbs->rosters away_ros ON away_ros.id = game.away_team LEFT JOIN $dbs->teams home_teams ON home_teams.id = home_ros.team LEFT JOIN $dbs->teams away_teams ON away_teams.id = away_ros.team ";
  $wtb_cond = " WHERE (bracket_name LIKE 'Playoffs' OR bracket_name LIKE 'Championship Playoffs') AND (home_team_score IS NOT NULL OR away_team_score IS NOT NULL) ";
  $winner_tb = $wpdb->get_results("SELECT ev.id AS event_id, ev.name AS event_name, ev.short_name AS event_shortname, game.division, game.start_time AS game_time, game.home_team AS home_ros_id, game.home_team_score AS home_ros_score, game.away_team AS away_ros_id, game.away_team_score AS away_ros_score, game.bracket_name, IF( game.home_team_score > game.away_team_score, game.home_team, game.away_team ) AS champ_ros, IF( game.home_team_score > game.away_team_score, home_teams.id, away_teams.id ) AS championship_team, IF( game.home_team_score > game.away_team_score, home_teams.search_list, away_teams.search_list ) AS championship_team_name, IF( game.home_team_score > game.away_team_score, away_teams.id, home_teams.id ) AS runner_up_team, IF( game.home_team_score > game.away_team_score, away_teams.search_list, home_teams.search_list ) AS runner_up_team_name $joins $wtb_cond ORDER BY event_id, home_ros.level, home_ros.level, game_time DESC");
  $wtb_orw = " WHERE home_team_score IS NOT NULL OR away_team_score IS NOT NULL ";
  $include_orw_field = " event_id, event_name, substring_index(group_concat(org_id ORDER BY num_win DESC),',',1) AS winner_org_id, division, substring_index(group_concat(most_win_ros ORDER BY num_win DESC),',',1) AS most_win_ros_id, MAX(num_win) AS num_win "; 
  $include_orw_ctb_field = " org_id, event_id, event_name, division, most_win_ros, count(most_win_ros) AS num_win FROM (SELECT IF(game.home_team_score > game.away_team_score, home_teams.org, away_teams.org) AS org_id, ev.id AS event_id, ev.name AS event_name, game.division, game.start_time AS game_time, game.home_team AS home_ros_id, game.home_team_score AS home_ros_score, game.away_team AS away_ros_id, game.away_team_score AS away_ros_score, game.bracket_name, IF( game.home_team_score > game.away_team_score, game.home_team, game.away_team ) AS most_win_ros, IF( game.home_team_score > game.away_team_score, home_teams.id, away_teams.id ) AS most_win_team ";
  $group_by_orw_ctb2 = " GROUP BY division, most_win_ros, org_id, champ_tb.event_id ";
  $order_by_orw_ctb = " ORDER BY event_id, game_time DESC ";
  $order_by_ctb2 = " ORDER BY event_id, num_win DESC ";
  $overall_wins = $wpdb->get_results("SELECT $include_orw_field FROM (SELECT $include_orw_ctb_field $joins $wtb_orw $order_by_orw) champ_tb $group_by_orw_ctb2 $order_by_ctb2) champ_tb2 GROUP BY division, champ_tb2.event_id HAVING winner_org_id = $org_id ORDER BY event_id");
  $winner_tb = json_decode(json_encode($winner_tb), true);
  $overall_wins = json_decode(json_encode($overall_wins), true);
  $champ_bracket = array();
  $team_championship = array();
  foreach($overall_wins as $overall_win){ 
    foreach($winner_tb as  $index => $winner){
      if( ($overall_win['most_win_ros_id'] == $winner['home_ros_id'] && $overall_win['event_id'] == $winner['event_id'] && ($overall_win['division'] == $winner['division'] ) || ($overall_win['most_win_ros_id'] == $winner['away_ros_id'] && $overall_win['event_id'] == $winner['event_id'] && ($overall_win['division'] == $winner['division']))) ){ 
        $champ_ros_col = array_column($champ_bracket, 'champ_ros');
        if (!in_array($overall_win['most_win_ros_id'], $champ_ros_col)){
          $champ_bracket[$index] = $winner;
        }
      }
    }
  }
  switch($type){
    case 'team_champ': // Need $team_id only
      foreach($champ_bracket as $index => $winner_type){
        if($team_id == $winner_type['championship_team']){
          $team_championship[$index] = $winner_type;
          unset($team_championship[$index]['runner_up_team']);
          unset($team_championship[$index]['runner_up_team_name']);
        }
        if($team_id == $winner_type['runner_up_team']){
          $team_championship[$index] = $winner_type;
          unset($team_championship[$index]['championship_team']);
          unset($team_championship[$index]['championship_team_name']);
        }
      }
      return $team_championship;
      break;
    case 'org_champ': // Need $org_id only
      foreach($overall_wins as $overall_win){
        $ros_id = $overall_win['most_win_ros_id'];
        $get_team_id = $wpdb->get_results("SELECT team.id FROM $dbs->teams team LEFT JOIN $dbs->rosters ros ON ros.team = team.id WHERE ros.id = $ros_id");
        foreach($champ_bracket as $index => $winner_type){
          if($get_team_id[0]->id == $winner_type['championship_team']){
            $team_championship[$index] = $winner_type;
            unset($team_championship[$index]['runner_up_team']);
            unset($team_championship[$index]['runner_up_team_name']);
          }
          if($get_team_id[0]->id == $winner_type['runner_up_team']){
            $team_championship[$index] = $winner_type;
            unset($team_championship[$index]['championship_team']);
            unset($team_championship[$index]['championship_team_name']);
          }
        }
      }
      return $team_championship;
      break;
    case 'pl_profile_champ':
      break;
  }
}

//get all player profile data
function cj_g365_get_award($player = null, $org_id, $team_id, $type) {
	//make sure we have a value
// 	if( $player === null || empty($player) ) return 'Need Player id to start build.';
  if(!empty($player)){
    $player = implode(',', $player); // Get a list of players in team  
  }
//   $year = g365_date_format($year, 1);
	global $wpdb;
  $dbs = json_decode(dbs());
  $data_columns = new \stdClass();
  $include_field = " (ev.eventtime) AS event_time, org.id AS org_id, ar.player AS player_id, pl.name AS player_name, pl.nickname AS player_nickname, ar.event as event_id, ev.short_name as event_shortname, ev.nickname as event_nickname, aw.type as award_type, aw.name as award, aw.logo_img as award_img, ar.name as award_title, ar.enabled as enabled ";
  $joins = " FROM $dbs->award_refs AS ar LEFT JOIN $dbs->awards AS aw ON ar.award=aw.id LEFT JOIN $dbs->rankings AS rk ON ar.ranking=rk.id LEFT JOIN $dbs->groups AS rk_gr ON rk.group_id=rk_gr.id LEFT JOIN $dbs->events AS ev ON ev.id = ar.event LEFT JOIN $dbs->rosters AS ros ON ros.event = ev.id LEFT JOIN $dbs->orgs AS org ON org.id = ros.org LEFT JOIN $dbs->players AS pl ON pl.id = ar.player ";
  $where = " WHERE org.id = $org_id AND ( ros.players LIKE CONCAT('%\"',ar.player,'\":%') AND ros.event = ar.event ) AND ros.team = $team_id AND ar.enabled = 1 ";
  $order_by = " ORDER BY ar.event DESC ";
  switch($type){
    case 1:
      $data_columns->awards = $wpdb->get_results("SELECT $include_field $joins $where $order_by");
      break;
    case 2:
      $where = " WHERE org.id = $org_id AND ( ros.players LIKE CONCAT('%\"',ar.player,'\":%') AND ros.event = ar.event ) AND ar.enabled = 1 ";
      $order_by = " ORDER BY ar.event DESC ";
      $data_columns->awards = $wpdb->get_results("SELECT $include_field $joins $where $order_by");
      break;
  }
  return $data_columns;
}


function cj_remote_stat_leader($arg = null, $type = null){ // If needed, it can be used as API call for g365 stat leaderboard
  
  $stat_lists = g365_stat_list();
  $pl_division = g365_division();
  // echo("<script>console.log('test1 ');</script>");
  
//   $post_ros_level = $arg['post_dvs'];
//   $post_dvs = $arg['select_level'];
  $post_ros_level = $arg['hhh_roster_level'];
  // echo("<script>console.log('test2 ');</script>");
  $post_dvs = $arg['hhh_roster_dvs'];
  // echo("<script>console.log('test3 ');</script>");
  $post_ev_id = $arg['hhh_ev_id'];
  // echo("<script>console.log('test4 ');</script>");
  
//   $brand_type = '';
  if($arg['brand_type'] == 'scs'){
    // echo("<script>console.log('test5 ');</script>");
    $brand_type = 'scs';
    // echo("<script>console.log('test6 ');</script>");
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  if($arg['brand_type'] == 'ebc'){
    // echo("<script>console.log('test7 ');</script>");
    $brand_type = 'ebc';
    // echo("<script>console.log('test8 ');</script>");
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  if($arg['brand_type'] == 'tsc'){
    // echo("<script>console.log('test9 ');</script>");
    $brand_type = 'tsc';
    // echo("<script>console.log('test10 ');</script>");
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  if($arg['brand_type'] == 'hhh'){
    // echo("<script>console.log('test11 ');</script>");
    $brand_type = 'hhh';
    // echo("<script>console.log('test12 ');</script>");
    if(empty($arg['is_year'])){ $is_year = ''; }else{ $is_year = $arg['is_year']; }
  }
  
  // echo("<script>console.log('test13 ');</script>");
  $most_recent_event = cj_most_recent_event(4);
  // echo("<script>console.log('test14 " . $most_recent_event[0] . " ');</script>");
  $most_recent_event_id = $most_recent_event[0]->event_id;
  // echo("<script>console.log('test15 " . $most_recent_event_id . " ');</script>");
  $default_event_info = g365_get_event_data($most_recent_event_id, true);
  // echo("<script>console.log('test16 " . $default_event_info . " ');</script>");
  $avi_ev_list = avi_ev_list([$default_event_info->id, 'remote_post_ev_id'=>$post_ev_id, 'ev_type'=>$arg['is_dcp']]);
  // echo("<script>console.log('test17 " . $avi_ev_list . " ');</script>");
//   echo("<script>console.log('avi_ev_list: " . $avi_ev_list . " ');</script>");
  $post_stat_catagory = $arg['hhh_stat_catagory'];
  // echo("<script>console.log('test18 " . $post_stat_catagory . " filter: " . $arg['filter_ev_id'] .  " ');</script>");
//   echo("<script>console.log('post_stat_category: " . $post_stat_catagory . " ');</script>");
  
  if(empty($arg['filter_ev_id'])){
    // echo("<script>console.log('test19 ');</script>");
    if($arg['post_level_val'] === 'false' && $arg['post_dvs_val'] === 'false' && $arg['post_stat_val'] === 'false' && $arg['post_ev_val'] === 'false'){
      // echo("<script>console.log('test20 ');</script>");
      $post_stat_catagory = key($stat_lists); $post_ev_id = avi_ev_list([$default_event_info->id, 'ev_type'=>$arg['is_hhh']], 'default_ev_id');
      // echo("<script>console.log('test21 ');</script>");
    }
    
  }else{
    // echo("<script>console.log('test22 ');</script>");
    if($arg['post_level_val'] === 'false' && $arg['post_dvs_val'] === 'false' && $arg['post_stat_val'] === 'false' && $arg['post_ev_val'] === 'false'){
      // echo("<script>console.log('test23 ');</script>");
//       $post_stat_catagory = key($stat_lists); $post_ev_id = $arg['filter_ev_id'];
      $post_stat_catagory = $post_stat_catagory; $post_ev_id = $arg['filter_ev_id'];
      // echo("<script>console.log('test24 " . $post_stat_catagory . " ');</script>");
    }else{
      // echo("<script>console.log('test25 ');</script>");
      $post_stat_catagory = $post_stat_catagory; $post_ev_id = $arg['filter_ev_id'];
      // echo("<script>console.log('test26 ');</script>");
    }
    
  }
  // echo("<script>console.log('test27 ');</script>");
  $leaderboard_tb_form = leaderboard_tb_form($post_stat_catagory, true, 'remote_tsc_tlb');
  // echo("<script>console.log('test28 " . $leaderboard_tb_form . " ');</script>");
  $event_info = g365_get_event_data($post_ev_id, true);
  // echo("<script>console.log('test29 " . $event_info . " ');</script>");
  $ev_date_format = g365_date_format($event_info->eventtime, 7);
  // echo("<script>console.log('test30 " . $ev_date_format . " ');</script>");
  $pl_data_type = g365_stat_leader($post_ev_id, $post_stat_catagory, '', '', $post_dvs, 10, $post_ros_level, ['brand_type'=>$brand_type, 'year'=>$is_year]);
  // echo("<script>console.log('test31 " . $pl_data_type . " ');</script>");
  $pl_data_type = json_decode( json_encode($pl_data_type), true);
  // echo("<script>console.log('test32 " . $pl_data_type . " ');</script>");
  
  $new_pl_data_type = array();
  // echo("<script>console.log('test33 ');</script>");
  $new_pl_data_type_2 = array();
  // echo("<script>console.log('test34 ');</script>");
  $hhh_default_img = 'https://hypeherhoopscircuit.com/wp-content/uploads/2022/11/H-2c.png';
  // echo("<script>console.log('test35 ');</script>");
  function is_player_img($url){
    // echo("<script>console.log('test36 ');</script>");
    $init_curl = curl_init($url);
    // echo("<script>console.log('test37 ');</script>");
    curl_setopt($init_curl, CURLOPT_NOBODY, true);
    // echo("<script>console.log('test38 ');</script>");
    curl_exec($init_curl);
    // echo("<script>console.log('test39 ');</script>");
    $code = curl_getinfo($init_curl, CURLINFO_HTTP_CODE);
    // echo("<script>console.log('test40 ');</script>");
    if ($code == 200){ $status = true; }else{ $status = false; }
    // echo("<script>console.log('test41 ');</script>");
    
    curl_close($init_curl);
    // echo("<script>console.log('test42 ');</script>");
    return $status;
  }
  
  foreach($pl_data_type as $key => $pl_data_types){
    // echo("<script>console.log('test43 ');</script>");
    $pl_prof_img = get_site_url().'/wp-content/uploads/player-profiles/'.$pl_data_type[$key]['player_nickname'].'_'.$pl_data_type[$key]['player_id'].'.jpg';
    // echo("<script>console.log('test44 ');</script>");
    $player_profile = custom_link(array($pl_data_types['player_nickname'], $post_ev_id, $pl_data_type[0]['ev_type'], $ev_date_format, strtolower(preg_replace('/\s+|\.|-/', '-',$pl_data_types['event_nickname']))), 1);
    // echo("<script>console.log('test45 ');</script>");
    $new_pl_data_type[] = $pl_data_types;
    // echo("<script>console.log('test46 ');</script>");
    $new_pl_data_type[$key]['img_link'] = $pl_prof_img;
    // echo("<script>console.log('test47 ');</script>");
    $new_pl_data_type[$key]['player_profile'] = $player_profile;
    // echo("<script>console.log('test48 ');</script>");
    $new_pl_data_type[$key]['is_fav'] = check_fav_icon(['user_id'=>$arg['authorized_user'], 'pl_id'=>$pl_data_types['player_id']]);
    // echo("<script>console.log('test49 ');</script>");
    
  }
  $ev_date = g365_build_dates($event_info->dates, 2);
  // echo("<script>console.log('test50 ');</script>");
  $default_pl_stats = g365_stat_table_filter($new_pl_data_type, '3', '', '50', $post_stat_catagory, '4');
  return [$default_pl_stats, g365_return_keys('g365_grade_key'), $stat_lists, $default_event_info, $event_info, g365_message()['unavailable_opts'], $avi_ev_list, $leaderboard_tb_form, g365_division(null, 'dcp'), $post_stat_catagory, $post_ev_id, g365_message()['not_available'], $ev_date, 'test'=>$new_pl_data_type_2];
}

function cj_most_recent_event($type){
  global $wpdb;
  $dbs = json_decode(dbs());
  $include_field = "games.event_id AS event_id, events.eventtime AS event_time ";
  $from = " FROM $dbs->events AS events ";
  $joins = " INNER JOIN $dbs->games AS games ON games.event_id = events.id ";
  $where = " WHERE events.enabled = 1 ";
  $group_by = " GROUP BY events.id ";
  $order_by = " ORDER BY events.eventtime DESC ";
  switch($type){
    case 1:
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
    case 2:
      $include_field = " DISTINCT YEAR(events.eventtime) AS event_time ";
      $group_by = " GROUP BY event_time ";
      $order_by = " ORDER BY event_time DESC ";
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
    case 3: // DCP events
      $where = " WHERE events.enabled = 1 AND events.org = 3 ";
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
    case 4: // HHH events
      $where = " WHERE events.enabled = 1 AND events.org = 7164 ";
      $test = $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      // echo("<script>console.log('test50 SELECT " . $include_field . $from . $joins . $where . $group_by . $order_by . json_encode($test) . " ');</script>");
      return $wpdb->get_results("SELECT $include_field $from $joins $where $group_by $order_by");
      break;
  }
}

//get all player profile data
function cj_g365_get_profile($player = null) {
	//make sure we have a value
	if( $player === null ) return 'Need Player URL to start build.';

	global $wpdb;
	//all the tables we have to get data from
	$wpdb_players = $wpdb->g365_players;
	$wpdb_awards = $wpdb->g365_awards;
	$wpdb_award_refs = $wpdb->g365_award_refs;
	$wpdb_orgs = $wpdb->g365_orgs;
	$wpdb_positions = $wpdb->g365_positions;
	$wpdb_events = $wpdb->g365_events;
	$wpdb_stats = $wpdb->g365_stats;
	$wpdb_rankings = $wpdb->g365_rankings;
	$wpdb_groups = $wpdb->g365_groups;
	$wpdb_games = $wpdb->g365_games;
	
  
// 		$player = sanitize_text_field( $player );
		$data_columns = $wpdb->get_results(
			"SELECT player.id, player.createtime, player.updatetime, player.account_level, player.enabled, player.first_name, player.last_name, player.name, player.email,
			player.phone, player.profile_img, player.address, player.city, player.state, player.zip, player.country, player.birthday, player.verified, player.tagline,
			player.grad_year, player.height_ft, player.height_in, player.weight, pos.name AS position, player.social, player.videos, player.notes, org_school.name AS school,
			player.gpa, player.sat, player.act, player.nickname, org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.jersey_size')) as jersey_size, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_resub')) as bcert_resub, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_resub')) as recard_resub, player.access
			FROM $wpdb_players AS player
			LEFT JOIN $wpdb_positions AS pos ON player.position=pos.id
			LEFT JOIN $wpdb_orgs AS org_school ON player.school=org_school.id
			LEFT JOIN $wpdb_orgs AS org_club ON player.club_team=org_club.id
			WHERE player.nickname = '$player'"
		);
  
  echo "Debugging: $data_columns";
    
    // Check for errors
    if ( is_wp_error( $data_columns ) ) {
        // Handle the error
        echo 'Database query error: ' . $data_columns->get_error_message();
    }
  
  
// 		$data_columns = $wpdb->get_results("SELECT * FROM $wpdb_players WHERE `nickname` = '$player'");
	return $data_columns;
}


//check if current user has hhh scouting report unlocked
function g365_check_scouting_unlocked($current_user){
   if ($current_user == 0) {
        return false; // Assuming 0 means false in your context
    }

    global $wpdb; 
    $dbs = json_decode(dbs());

    if ($current_user != null) {
        $results = $wpdb->get_results("SELECT id FROM $dbs->favorites fav WHERE ( fav.event_id = 92793 OR fav.event_id = 84376) AND fav.user_id = $current_user AND fav.player_id IS NULL AND enabled != 0");

        // Return true if there are results, false otherwise
        return !empty($results);
    }

    return false;
}

function hhh_get_event_dates($currentUser){
  global $wpdb;
  $dbs = json_decode(dbs());
  
  $existing_row = $wpdb->get_row($wpdb->prepare("SELECT id, enabled, notes FROM $dbs->favorites WHERE (event_id = 92793 OR event_id = 84376) AND user_id = %d AND player_id IS NULL", $currentUser));
//   echo("<script>console.table('testing: " . $results . " ');</script>");
  
  if ($existing_row && !empty($existing_row->notes)) {
        // Decode the JSON string into a PHP array
        $season_bought_array = json_decode($existing_row->notes, true);

        // Move the array pointer to the last element
        end($season_bought_array);

        // Get the value of the last key
        $last_value = current($season_bought_array);

        return $last_value;
    }
  
  return false;
}

function grabplayerstats($player_id){
  global $wpdb;
  $dbs = json_decode(dbs());
  
  $existing_row = $wpdb->get_results($wpdb->prepare("SELECT pl.*, st.evaluation, st.strengths, st.weaknesses, st.trends FROM $dbs->players pl LEFT JOIN $dbs->stats st ON pl.id = st.player WHERE st.event = %d AND game = 0", $player_id));
  
  return $existing_row;
  
  
}

//function where I enable the hhh scouting report as well as keep track of when and what season it was bought.
function hhh_scouting_report_access($product_id, $user_id, $event_id) {
    global $wpdb;
    $dbs = json_decode(dbs());

    // Check if the current user is not null
    if ($user_id != null && $event_id != null) {
        
        $event_info = $wpdb->get_row($wpdb->prepare("SELECT id, name, dates FROM $dbs->events WHERE id = %d", $event_id));;
//         echo("<script>console.log('dates: " . $event_info->dates . " ');</script>");
        $date_array = explode("|", $event_info->dates);
        // Assign the values to two variables
        $first_date = trim($date_array[0]);  // Trim to remove leading/trailing spaces
        $first_date_year = DateTime::createFromFormat('F d, Y', $first_date)->format('Y');
        $second_date = trim($date_array[1]);
//         echo("<script>console.log('first dates: " . $first_date . " ');</script>");
//         echo("<script>console.log('first dates year: " . $first_date_year . " ');</script>");
      
        // Get today's date
        $today = new DateTime();

        // Create DateTime objects for September 1st and August 30th of the current year
        $year = (int) $today->format('Y');
        $start_date = new DateTime("September 1, $year");
        $end_date = new DateTime("August 30, $year");

        // Check if today's date is after August 30th of the current year
        if ($today > $end_date) {
            // If today is after August 30th, increment both start and end date to the next year
            $start_date = new DateTime("September 1, " . $year);
            $end_date = new DateTime("August 30, " . ($year + 1));
            $lower_year = $year;
        } else {
            // Otherwise, use the current and previous year for the date range
            $start_date = new DateTime("September 1, " . ($year - 1));
            $end_date = new DateTime("August 30, " . $year);
            $lower_year = $year - 1;
        }

        // Output the formatted date range
        $date_range = $start_date->format('F j, Y') . '|' . $end_date->format('F j, Y');

        // Debugging: Output the results in the console
        //echo("<script>console.log('second dates: " . $date_range . " " . $lower_year . " ');</script>");
            
        // Check if a row already exists for the given event_id and user_id
        $existing_row = $wpdb->get_row($wpdb->prepare("SELECT id, enabled FROM $dbs->favorites WHERE event_id = %d AND user_id = %d AND player_id IS NULL", $product_id, $user_id));

        // If the row doesn't exist, insert a new row
        if (!$existing_row) {
            $current_datetime = current_time('mysql'); // Get the current date and time in MySQL format

            $wpdb->insert(
                $dbs->favorites,
                array(
                    'event_id' => $product_id,
                    'user_id' => $user_id,
                    'player_id' => null, // Assuming player_id is a column in your table
                    'enabled' => 1, // Assuming enabled is a column in your table
                    'notes' => json_encode(array($lower_year => $date_range)),
                    'createdate' => $current_datetime,
                    'updatetime' => $current_datetime,
//                     'notes' =>  '{}',
//                     'pl_data' =>  '[{}]',
                ),
                array('%d', '%d', '%d', '%d', '%s', '%s', '%s')
            );
            return true; // Indicates a new row was inserted
        } else {
            // Check if 'enabled' is not 0, if it is, update it to 1
            if ($existing_row->enabled == 0) {
                $wpdb->update(
                    $dbs->favorites,
                    array('enabled' => 1, 'updatetime' => current_time('mysql')),
                    array('id' => $existing_row->id),
                    array('%d', '%s'),
                    array('%d')
                );
            }

            return false; // Indicates the row already exists
        }
    }

    return false; // Indicates the current user is null
}

function cj_g365_data_xfer($arg = null, $type = null){
//   echo("<script>console.table('domain ');</script>");
  global $wpdb; $dbs = json_decode(dbs());
  $db_name = $arg['db_tb'];
  $g365_db_tb = $dbs->$db_name;
  $query_type = $arg['qn_type'];
  $cond = ""; $limit = ""; $order = "";
  switch($type){
    case 'INSERT':
      $field_val = $arg['insert_field_val'];
      $sql = $wpdb->query("$type INTO $g365_db_tb VALUES ($field_val) ON DUPLICATE KEY UPDATE notes=VALUES(notes), enabled = 1, pl_data=VALUES(pl_data), updatetime=CURRENT_TIMESTAMP");
      return $sql;
      break;
    case 'SELECT':
      
      if(!empty($arg['limit'])){
        
        $limit = " LIMIT ".$arg['limit'];
        
      }else{$limit = "";}
      switch($query_type){
        case '1': //With conditions: fav notes and fav list
          
          $order = " ORDER BY updatetime DESC, createdate DESC ";
          
          if(!empty($arg['player_id'])){
            
            $cond = "WHERE enabled = 1 AND event_id IS NULL AND user_id = ".$arg['user_id']." AND player_id = ".$arg['player_id'];
            
          }else{$cond = "WHERE enabled = 1 AND event_id IS NULL AND user_id = ".$arg['user_id'];}
          break;
      }
      
      $sql = $wpdb->get_results(" $type * FROM $g365_db_tb $cond $order $limit ");
      $sql = json_decode(json_encode($sql), true);
      return $sql;  
      break;
//     case 'DELETE': // Delete record
//       $cond = " WHERE id = ".$arg['rec_id'];
//       $sql = " $type FROM $db_prefix$g365_db_tb $cond ";
//       mysqli_query($conn, $sql);
//       return $sql;
//       break;
     case 'DELETE': // Disable instead of delete the record
      $cond = " SET enabled = 0 WHERE id = ".$arg['rec_id'];
      $sql = $wpdb->query(" UPDATE $g365_db_tb $cond ");
      return $sql;
      break;
  }
}

// function ppNoConnectFix($player){
  
//     global $wpdb;
//         $wpdb_orgs = $wpdb->g365_orgs;
//         $wpdb_rosters = $wpdb->g365_rosters;
//         $wpdb_events = $wpdb->g365_events;
//         $wpdb_teams = $wpdb->g365_teams;
//         $wpdb_players = $wpdb->g365_players;
//         $wpdb_stats = $wpdb->g365_stats;
//         $wpdb_stats = $wpdb->g365_stats;
  
  
//         $player_info = $wpdb->get_results(
//               "SELECT players.name, players.grad_year, players.birthday
//               FROM $wpdb_stats AS stats
//               LEFT JOIN $wpdb_players AS players ON players.id = stats.player
//               WHERE stats.id IN ($data_ids) AND stats.event = 504;"
//             );
  
  
  
// }


function g365_division_cj($arg = null, $type = null){
  $dvs_list = array('Open', 'Gold', 'Silver', 'Bronze', 'Copper');
  switch($type){
    case 'dcp':
      $dvs_list = array('Gold', 'Open', 'Silver', 'Bronze');
      break;
    case 'HHH':
      $dvs_list = array('Gold', 'Silver', 'Bronze');
      break;
  }
  return $dvs_list;
}

function cts_type_selector_cj($type = null, $arg = null){
  $yb_levels = "14,13,12,11,10,9,8";
  $yg_levels = "17,16,15,44,43,42,41,40";
  $hs_levels = "17,16,15,14,13,12";
  $all_levels = "17,16,15,14,13,12,11,10,9,8,47,46,45,44,43,42,41,40";
  $lv_pl = "Open,Gold,Silver,Bronze,Copper";
  $level_catagories = "Youth Boys,Youth Girls,High School";
  $brand_pick = "All Brands, Grassroots, EBC, The Stage, Scholastic, Hype Her Hoops, Breakthrough Circuit";
  return array($yb_levels, $yg_levels, $hs_levels, $lv_pl, $all_levels, $level_catagories, 'youth-boys'=>$yb_levels, 'youth-girls'=>$yg_levels, 'high-school'=>$hs_levels, $brand_pick);
}

function g365_load_division_roster($event_id, $division_id) {
  global $wpdb;
  $wpdb_players = $wpdb->prefix . 'g365_award_refs';
  $wpdb_player = $wpdb->prefix . 'g365_players';
  
  $query = $wpdb->prepare("
    SELECT awards.id, awards.player, awards.award, players.name, players.phone, players.email
    FROM $wpdb_players as awards
      LEFT JOIN $wpdb_player as players ON players.id = awards.player
    WHERE event = %d AND class = %d 
    ", $event_id, $division_id);
  
  //execute query
  $results = $wpdb->get_results($query);
  
  //empty array to store data
  $data = [
      'divisionId' => $division_id,
      'players' => [],
    ];
  
  //loop to store data in array
  foreach($results as $row) {
    $data["players"][] = [
      "id" => $row->player, // player id
      "name" => $row->name, // full player name
      "awardType" => $row->award, // 11 or 12
      "phone" => $row->phone,
      "email" => $row->email
    ]; 
  }
  
  return $data;
}

function g365_get_all_event_rosters($event_id, $divisionIDs){
  $divisionsData = [];
  
  // load data for every division
  foreach($divisionIDs as $divisionID){
    $divisionDataResult = g365_load_division_roster($event_id, $divisionID);
    if(count($divisionDataResult['players']) > 0){
      $divisionsData[] = $divisionDataResult;
    }
  }
  return $divisionsData;
}

function g365_save_player_award_refs($count, $awardType, $event_id, $divisionId, $player_id){
  global $wpdb;
  
  $award = ($count == 0) ? ($awardType === "All-Tournament MVP" ? 12 : 11)  : 11;
  $name = ($award == 11) ? 'All-Tournament Team' : 'All-Tournament MVP';
  $wpdb_award_refs = $wpdb->prefix . 'g365_award_refs';
  $wpdb_groups = $wpdb->prefix . 'g365_groups';
  $wpdb_group_refs = $wpdb->prefix . 'g365_group_refs';

  // save one line in awards_refs
  $wpdb->insert(
      $wpdb_award_refs,
      array(
          'updatetime' => current_time('mysql'),
          'enabled' => 1,
          'player' => $player_id,
          'team' => 0,
          'ranking' => 0,
          'event' => $event_id,
          'award' => $award,
          'class' => $divisionId,
          'name' => $name,
          'progression' => null,
      ),
      array('%s', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%s', '%d' )
  );
}

function g365_delete_all_award_refs($event_id){
  global $wpdb;
  $wpdb_award_refs = $wpdb->prefix . 'g365_award_refs';

  $delete_all_query = "DELETE FROM $wpdb_award_refs WHERE event = ". $event_id;

  $sql = $wpdb->query($delete_all_query);
  return $delete_all_query;
}


function g365_save_division_roster($event_id, $divisions){
  // Delete all in the table awards_refs
  g365_delete_all_award_refs($event_id);

  // what we are saving
  foreach($divisions as $divisionToSave){
    $divisionId = $divisionToSave->divisionId;
      
      $count = 0;
      // save each player of the division.
      foreach($divisionToSave->players as $player_to_save_id){
        g365_save_player_award_refs($count, $divisionToSave->awardType, $event_id, $divisionId, $player_to_save_id);
        $count++;
      }
  }
  //insert into g365_group_refs table outside the loop
    g365_insert_group_refs($event_id);
}

function g365_insert_group_refs($event_id) {
  global $wpdb;
  $wpdb_group_refs = $wpdb->prefix . 'g365_group_refs';
  $wpdb_groups = $wpdb->prefix . 'g365_groups';
  
  // Query to fetch name and nickname from g365_events table based on event_id
  $event_data = $wpdb->get_row(
      $wpdb->prepare(
          "SELECT name, nickname FROM {$wpdb->prefix}g365_events WHERE id = %d",
          $event_id
      )
  );
  
  $wpdb->insert(
      $wpdb_groups,
      array(
          'updatetime' => current_time('mysql'),
          'enabled' => 1, 
          'name' => $event_data->name,
          'type' => 3,
          'groups' => NULL,
          'abbr' => $event_data->short_name,
          'nickname' => $event_data->nickname
      )
  );
  
  
  //query to fetch group_id based on event_id
  $group_id = $wpdb->get_var(
      $wpdb->prepare(
          "SELECT gg.id
          FROM {$wpdb->prefix}g365_groups AS gg
          INNER JOIN {$wpdb->prefix}g365_events AS ge ON gg.name = ge.name
          WHERE ge.id = %d",
          $event_id
      )
  );
  
  //check if group_id is not null
  if($group_id !== null) {
    //insert into g365_group_refs table
    $wpdb->insert(
      $wpdb_group_refs,
      array(
          'updatetime' => current_time('mysql'),
          'enabled' => 1,
          'group_id' => 89,
          'item_id' => $group_id
      )
    );
    
    $wpdb->insert(
      $wpdb_group_refs,
      array(
          'updatetime' => current_time('mysql'),
          'enabled' => 1,
          'group_id' => $group_id,
          'item_id' => $event_id
      )
    );
  }else {
    echo "Group ID not found for event_id: $event_id";
  }
}

function g365_club_team_stat_cj($event_id = null, $team_id = null, $org_id, $opponent_id = null, $year = null, $type, $arg = null){
  global $wpdb; $default_pct = '0.59';
  $dbs = json_decode(dbs());
  $date_format = g365_date_format($year, 1); 
  $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
  $filter_game = " WHERE orgs.id = $org_id AND events.eventtime BETWEEN $date_format ";
  $order_by = " ORDER BY events.eventtime DESC ";
  $include_field = " rosters.id AS roster_id, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team  ) AS opponent_id, ";
  $case_game_result = " (CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END) AS game_result, (CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END) AS game_result_label ";
  switch($type){
    case 1: // List of all program games
      if(!empty($event_id)){ // Get games on certain event
        $filter_game = " $filter_game AND events.id = $event_id ";
      }
      elseif(empty($event_id)){// Get all games by Org
        $filter_game = $filter_game;      
      }else{
        echo "Missing event id";
      }
      $order_by = " $order_by, teams.level ASC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 2: // Event only
      $include_field = "DISTINCT events.id AS event_id, events.eventtime AS event_time, events.name AS event_name, events.org AS event_org";
      $group_by = " GROUP BY events.id ";
      return $wpdb->get_results("SELECT $include_field $joins $filter_game $group_by $order_by");
      break;
    case 3: // Opponent only
      $include_field = " DISTINCT teams.search_list AS team_name ";
      $filter_game = " WHERE rosters.id = $opponent_id ";
      return $wpdb->get_results("SELECT $include_field $joins $filter_game");
      break;
    case 4: // Year only. Need Org id only
      $include_field = " DISTINCT YEAR(events.eventtime) AS event_date ";
      $filter_game = " WHERE orgs.id = $org_id ";
      $group_by = " GROUP BY events.eventtime ";
      $order_by = " ORDER BY YEAR(events.eventtime) DESC ";
      return $wpdb->get_results("SELECT $include_field $joins $group_by $order_by");
      break;
    case 5: // With team id
      if(empty($team_id)) return 'Need team id to process';
      $filter_game = " WHERE orgs.id = $org_id AND teams.id = $team_id AND games.start_time BETWEEN $date_format ";
      $order_by = " $order_by, teams.level ASC, games.start_time DESC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 6: // Overall team/club statistics. Need org id only
      $filter_game = " WHERE orgs.id = $org_id AND games.start_time BETWEEN $date_format ";
      $order_by = " $order_by, teams.level ASC ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game $order_by");
      break;
    case 7: // Club team standing
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
      }else{
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";
          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";
          $tb_1_fields = " inner_tb.* ";
          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");        
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
    case 8:
      // 0: game id. 1: team id.
//       !empty($arg[0]) ? $game_id = 'AND games.id = ' . $arg[0] : $game_id = '';
//       !empty($arg[1]) ? $team_id = 'AND teams.id = ' . $arg[1]: $team_id = '';
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $include_field = " orgs.profile_img AS org_logo, rosters.id AS roster_id, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, rosters.level AS level_of_play, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ";
      $filter_game = " WHERE games.start_time BETWEEN $date_format AND games.id = $arg[0] AND teams.id = $arg[1] ";
//       $filter_game = " WHERE games.start_time BETWEEN $date_format $game_id $team_id ";
      return $wpdb->get_results("SELECT $include_field $case_game_result $joins $filter_game");
      break;
    case 9:
      !empty($arg['level_of_play']) ? $is_level_of_play = $arg['level_of_play'] : $is_level_of_play = '';
      !empty($arg['indi_org_id']) ? $indi_org_id = $arg['indi_org_id'] : $indi_org_id = '';
      $level_of_play = " AND LOCATE(\"$is_level_of_play\", level_of_plays) ";
      $dcp_ev = (empty($arg['is_dcp_ev']) ? '' : $arg['is_dcp_ev']);
      $if_dcp = (empty($arg['is_dcp']) ? '' : $arg['is_dcp']);
      $brand_selected = empty($arg['brand_selected']) ? '' : $arg['brand_selected'];
      $if_unlocked_dcp_ev = (empty($arg['is_unlocked_dcp_ev']) ? false : $arg['is_unlocked_dcp_ev']);
      if($if_dcp == true){
        $default_pct = '0.39';
        $is_dcp = " AND events.org = 3 ";
        if(!empty($dcp_ev)){ $is_dcp = " AND events.org = 3 AND events.id = $dcp_ev "; }
      }else{ $is_dcp = ""; }
      if(!empty($team_id)){
        $box_score_year = g365_date_format($arg[0], 1);
        $box_score_condi = " teams.id = $team_id AND games.id = $arg[1] $is_dcp AND start_time BETWEEN $box_score_year ";
        
      }else{
        $box_score_condi = " teams.level IN ($arg[0]) $is_dcp AND start_time BETWEEN $date_format ";
        
      }
      // With level of plays
      if(!empty($arg['level_of_play'])){ // Selected level
        $lv_of_play = $arg['level_of_play'];
        $lv_play_field = " level_of_play, ";
        $lv_play_gp_by = " , level_of_play ";
        $lv_play_where = " AND LOCATE('$lv_of_play', level_of_play) ";
        $lv_play_outer_where = " WHERE level_of_play = '$lv_of_play' ";
      }else{ // All levels
//         $lv_play_field = " GROUP_CONCAT(level_of_play) level_of_play, ";
        $lv_play_field = "";
        $lv_play_gp_by = "";
        $lv_play_where = "";
//         $lv_play_outer_where = " WHERE level_of_play = '' ";
        $lv_play_outer_where = "";
      }
      
      if ($brand_selected == "the-stage") {
          $default_pct = '0.49';
          $org_id = '3'; // Assuming '3' is the ID for "The Stage"
          
      }else if ($brand_selected == "grassroots-365"){
//           $default_pct = '0.49';
          $org_id = '3191'; // Assuming '7729' is the ID
      }else if ($brand_selected == "scholastic-series"){
          $default_pct = '0.49';
          $org_id = '7165'; // Assuming '7165' is the ID
      }else if ($brand_selected == "hype-her-hoops-circuit"){
          $default_pct = '0.49';
          $org_id = '7164'; // Assuming '7164' is the ID
      }else if ($brand_selected == "breakthrough-circuit"){
          $default_pct = '0.49';
          $org_id = '7729'; // Assuming '7729' is the ID
      }else {
          $org_id = null;

      }
      
      if (!is_null($org_id)) {
          $additionalCondition = "AND events.org = $org_id ";
          $box_score_condi = " teams.level IN ($arg[0]) $additionalCondition AND start_time BETWEEN $date_format ";
        
      } else {
          $additionalCondition = "";
          
      }
      
      $num_result = 20;
      $win_count = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) ";
      $loss_count = " COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) ";
      $game_result = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score) WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score) ELSE '' END ";
      $game_result_label = " CASE WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W') WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L') WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W') WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L') ELSE '' END ";
      $joins = " FROM $dbs->rosters rosters INNER JOIN $dbs->orgs orgs ON orgs.id = rosters.org INNER JOIN $dbs->games games ON games.home_team = rosters.id OR games.away_team = rosters.id INNER JOIN $dbs->teams teams ON rosters.team = teams.id INNER JOIN $dbs->events events ON rosters.event = events.id ";
      $pct = " COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $ppg = " ( IF(SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END ))
    +  IF( SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) IS NULL, '0', SUM( CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END ) ))/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $opp_ppg = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) ";
      $box_score = " ( IF( SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END))
    + IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, '0', SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END)) )/(COUNT(CASE WHEN game_result_label = 'W'  THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L'  THEN 1 END)) AS opp_ppg, GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"event_id\": \"',event_id,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"org_nickname\": \"',org_nickname,'\", \"game_id\": \"',game_id,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_name\": \"',opp_name,'\", \"opp_nickname\": \"',opp_nickname,'\", \"player_stat\": [',game_data,']}') ";
      $w_away_stat = " WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN 
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $w_home_stat = " WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_away_stat = " WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $l_opp_home_stat = " WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN
  CONCAT( '[{\"pl_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.away_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')), ']}', ',{\"opp_data\": [', (SELECT GROUP_CONCAT('{\"player_info\": {\"player_id\": \"',(pl.id),'\", \"player_name\": \"',REPLACE(pl.name,'\"',''),'\", \"player_nickname\": \"',REPLACE(pl.nickname,'\"',''),'\", \"stats\": ',st.stats,'}}') from $dbs->games gm left join $dbs->stats st on gm.id = st.game left join $dbs->players pl on pl.id = st.player left join $dbs->rosters ros on ros.id = gm.home_team where gm.id = game_id AND ros.players LIKE CONCAT('%\"',pl.id,'\":%')),']', '}]') ";
      $opp_name = " (SELECT CONCAT(orgs_2.name,' ', tm.search_list) FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_nickname = " (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $opp_logo = " (SELECT orgs_3.profile_img FROM $dbs->orgs orgs_3 INNER JOIN $dbs->rosters rosters_3 ON rosters_3.org = orgs_3.id WHERE rosters_3.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) ";
      $tb_3_fields = " team_id, team_level, full_team_name, org_logo, win, loss, pct, ppg, opp_ppg, box_score ";
      $tb_2_fields = " team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $box_score AS box_score ";
      $tb_1_fields = " inner_tb.*, ( CASE $w_away_stat $w_home_stat $l_opp_away_stat $l_opp_home_stat END) AS game_data ";
      $inner_tb_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, $opp_name AS opp_name, $opp_nickname AS opp_nickname, $opp_logo AS opp_logo, ($game_result) AS game_result, ($game_result_label) AS game_result_label ";
      $tb_2_condition = " GROUP BY team_id, org_logo, full_team_name $lv_play_gp_by
ORDER BY win DESC ";
      $sec_tb3_fields = " pl_stat, win, team_level, full_team_name ";
      $sec_tb2_fields = " pl_stat, team_id, team_level, org_logo, CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg ";
      $sec_tb1_fields = " orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF( rosters.id = games.home_team, games.away_team, games.home_team ) AS opponent_id, ($game_result) AS game_result, ($game_result_label) AS game_result_label, (SELECT GROUP_CONCAT(st.stats) FROM $dbs->stats st INNER JOIN $dbs->games gm_2 ON gm_2.id = st.game WHERE gm_2.id = game_id) AS pl_stat ";
      $sec_tb2_condi = " $lv_play_outer_where GROUP BY team_id, org_logo, full_team_name ORDER BY win DESC, pct DESC ";
      $sec_tb1_condi = " ORDER BY events.eventtime DESC , teams.level ASC, games.start_time DESC ";
      $results = $wpdb->get_results("SET SESSION group_concat_max_len = 10000000000;");
      switch($arg[2]){
        case 'is_standing_only':
          
          if (is_null($org_id)) {
              //if no brand then stick to the OG
              $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
          }else{
              //if there is a brand then use else or if else to change the number of games required to display the team in the standings
              if ($brand_selected == "the-stage") {
                $num_game_by_month = 0;
              }else if ($brand_selected == "scholastic-series"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "hype-her-hoops-circuit"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "breakthrough-circuit"){
                $num_game_by_month = 0;
              }else if ($brand_selected == "grassroots-365"){
                $num_game_by_month = team_standing_game('', ['select_year'=>$year]);
              }
          }
          
          if(empty($arg['post_ros_dvs'])){ $ros_division = ''; }else{ $post_ros_dvs = $arg['post_ros_dvs']; $ros_division =  " AND rosters.division = '$post_ros_dvs' "; }
          $standing = " GROUP_CONCAT('{\"event_name\": \"',event_name,'\", \"team_name\": \"',CONCAT(org_name,' ',team_name),'\", \"game_id\": \"',game_id,'\", \"org_nickname\": \"',org_nickname,'\", \"gm_r_label\": \"',game_result_label,'\", \"game_result\": ','\"(',game_result,')\"',', \"opp_logo\": \"',IF(opp_logo IS NULL, '', opp_logo),'\", \"opp_nickname\": \"',opp_nickname,'\", \"opp_name\": \"',opp_name,'\"}') ";
          $tb_3_fields = " team_id, team_level, full_team_name, org_logo, total_game, win, loss, pct, ppg, opp_ppg, standing ";

          $tb_2_fields = " team_id, team_level, org_logo, $lv_play_field CONCAT(org_name,' ',team_name) AS full_team_name, $win_count AS win, $loss_count AS loss, $pct AS pct, $ppg AS ppg, $opp_ppg AS opp_ppg, COUNT(game_result_label) AS total_game, $standing AS standing ";

          $tb_1_fields = " inner_tb.* ";

          if(!empty($arg[3])){
            $num_result = 50;
            $box_score_condi = " teams.level IN ($arg[0]) $is_dcp  $ros_division AND games.start_time BETWEEN $date_format ";
            
            
          }
          if(empty($arg[4])){ $arg[4] = "win"; }
//           $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct $lv_play_where ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.$arg[4] DESC, tb_3.pct DESC;");

          // Or, if you're directly building your query string, log the entire query:
//           echo("<script>error_log('Executing query: SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3  :finish ');</script>");
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct AND total_game >= $num_game_by_month $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
          
          // Only for DCP team standing
          if($if_dcp == true){
            // Only for unlocked DCP events
            if($if_unlocked_dcp_ev == true){
              $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division ";
              $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
              
            }else{ 
              // Custom organization team standings
              if(!empty($indi_org_id)){
                $default_pct = '0';
                $is_indi_org = " AND events.org = $indi_org_id ";
                $box_score_condi = " teams.level IN ($arg[0]) $is_indi_org $ros_division ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
                
              }else{
                // Only for standard team standings
                $box_score_condi = " teams.level IN ($arg[0]) $is_dcp $ros_division AND games.start_time BETWEEN $date_format ";
                $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 WHERE pct > $default_pct  $lv_play_where ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.$arg[4] DESC ) tb_3 ");
                
              }
            }
          }
          break;
        case 'is_box_score_only':
//           $opp_org_nickname = " , (SELECT orgs_2.nickname FROM $dbs->orgs orgs_2 INNER JOIN $dbs->rosters rosters_2 ON rosters_2.org = orgs_2.id INNER JOIN $dbs->teams tm ON tm.id = rosters_2.team WHERE rosters_2.id = IF( rosters.id = games.home_team, games.away_team, games.home_team )) AS opp_org_nickname ";
          
          $results = $wpdb->get_results("SELECT tb_3.* FROM ( SELECT $tb_3_fields FROM ( SELECT $tb_2_fields FROM ( SELECT $tb_1_fields FROM ( SELECT $inner_tb_fields $joins WHERE  $box_score_condi ) inner_tb ) tb_1 $tb_2_condition ) tb_2 ) tb_3 INNER JOIN ( SELECT pl_stat, team_level, GROUP_CONCAT(full_team_name ORDER BY win DESC) grouped_year FROM ( SELECT $sec_tb3_fields FROM ( SELECT $sec_tb2_fields FROM ( SELECT $sec_tb1_fields $joins WHERE $box_score_condi $sec_tb1_condi ) sec_tb_1 $sec_tb2_condi ) sec_tb_2 ) sec_tb_3 GROUP BY team_level) group_max ON tb_3.team_level = group_max.team_level AND FIND_IN_SET(full_team_name, grouped_year) <=$num_result ORDER BY tb_3.team_level DESC, tb_3.win DESC;");
          break;
      }
      $results = json_decode( json_encode($results), true);
      $group_result = array();
      foreach ($results as $key => $result) {
        $group_result[$result['team_level']][$key] = $result;
      }
      krsort($group_result, SORT_NUMERIC);
      return $group_result;
      break;
  }
}


function sortEventsFromToday($events) {
    $today = date("Y-m-d");  // Gets today's date
    $priorityEventName = "National Evaluations"; // Name of the event to always appear first
    
    usort($events, function($a, $b) use ($today, $priorityEventName) {
        // Check if either event is the priority event by name
        if ($a['name'] == $priorityEventName) return -1;
        if ($b['name'] == $priorityEventName) return 1;

        $dateA = new DateTime($a['eventtime']);
        $dateB = new DateTime($b['eventtime']);

        // If the event date is past, compare by how recent it is. If future, sort it to the end.
        $diffA = $today >= $dateA->format("Y-m-d") ? $today - $dateA->getTimestamp() : PHP_INT_MAX;
        $diffB = $today >= $dateB->format("Y-m-d") ? $today - $dateB->getTimestamp() : PHP_INT_MAX;

        return $diffA <=> $diffB;
    });

    return $events;
}


function spp_grab_info( $type = null, $arg = null ){
  global $wpdb; $dbs = json_decode(dbs());
  !empty($arg['target_url']) ? $target_url = $arg['target_url'] : $target_url = '';
  $target_org = g365_return_keys('keys_by_domain')[$target_url]['org_id'];
  switch($type){
    case 'plyer_info': //grabbing player info
      
      $season = $arg['season'];

      // Extract the first year from the '2024-2025' format
      $yearParts = explode('-', $season);
      $startYear = $yearParts[0]; // This will be '2024'
      $select_year = wp_date('Y');
      global $wpdb;
      $wpdb_players = $wpdb->g365_players;
      $wpdb_stats = $wpdb->g365_stats;
      $wpdb_events = $wpdb->g365_events;
      $wpdb_rosters = $wpdb->g365_rosters;
      $wpdb_teams = $wpdb->g365_teams;
      $wpdb_organizations = $wpdb->g365_orgs;
      
      if( $season == 'frnt_page' ){ //for front page filter including in the first two months to still grab info from last year
        $date = g365_date_format($select_year, 17);  
        $date_ranges = explode(" AND ", $date);

        // If there are more than two parts, handle multiple date ranges
        if (count($date_ranges) > 2) {
            // Split into two sets of dates for the BETWEEN condition
            $date1_start = $date_ranges[0];
            $date1_end = $date_ranges[1];
            $date2_start = $date_ranges[2];
            $date2_end = $date_ranges[3];

            // Update the query to use OR between the two date ranges
            $get_players = "
                SELECT s.id AS stat_id, s.updatetime AS eval_date, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected,
                s.evaluation AS player_eval, s.strengths AS player_strength, s.weaknesses AS player_weak,
                e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short,
                e.logo_img AS event_image, e.org AS event_brand, e.dates AS event_dates, 
                p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image,
                p.height_ft AS player_ft, p.height_in AS player_in, p.grad_year AS player_class,
                p.position AS player_pos, org.search_list AS org_name
                FROM $wpdb_stats AS s
                JOIN $wpdb_events AS e ON e.id = s.event
                JOIN $wpdb_players AS p ON s.player = p.id
                LEFT JOIN $wpdb_organizations AS org ON p.club_team = org.id
                WHERE JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated'))
                AND s.event != 504
                AND s.enabled = 1
                AND (e.eventtime BETWEEN $date1_start AND $date1_end 
                    OR e.eventtime BETWEEN $date2_start AND $date2_end)
                ORDER BY e.eventtime DESC, RAND();
            ";
        } else {
            // Single date range
            $get_players = "
                SELECT s.id AS stat_id, s.updatetime AS eval_date, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected,
                s.evaluation AS player_eval, s.strengths AS player_strength, s.weaknesses AS player_weak,
                e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short,
                e.logo_img AS event_image, e.org AS event_brand, e.dates AS event_dates, 
                p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image,
                p.height_ft AS player_ft, p.height_in AS player_in, p.grad_year AS player_class,
                p.position AS player_pos, org.search_list AS org_name
                FROM $wpdb_stats AS s
                JOIN $wpdb_events AS e ON e.id = s.event
                JOIN $wpdb_players AS p ON s.player = p.id
                LEFT JOIN $wpdb_organizations AS org ON p.club_team = org.id
                WHERE JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated'))
                AND s.event != 504
                AND s.enabled = 1
                AND e.eventtime BETWEEN $date
                ORDER BY e.eventtime DESC, RAND();
            ";
        }

        
      }else{
          $date = g365_date_format($startYear, 16); // for the query to get the date ex. '2022-09-01' AND '2023-08-31'


          $get_players = "

                          SELECT s.id AS stat_id, s.updatetime AS eval_date, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected, s.evaluation AS player_eval, s.strengths AS player_strength, 
                          s.weaknesses AS player_weak, e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short, 
                          e.logo_img AS event_image, e.org AS event_brand, e.dates AS event_dates, p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image, p.height_ft AS player_ft, p.height_in AS player_in, p.grad_year AS player_class, p.position AS player_pos, org.search_list AS org_name
                          FROM $wpdb_stats AS s
                          JOIN $wpdb_events AS e ON e.id = s.event
                          JOIN $wpdb_players AS p ON s.player = p.id
                          LEFT JOIN $wpdb_organizations AS org ON p.club_team = org.id
                          WHERE JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated')) AND s.event != 504 AND s.enabled = 1 AND e.eventtime BETWEEN $date ORDER BY e.eventtime DESC, RAND();
                      ";  
        
      }
      
        $message = "testing here yes you got it right " . $get_players . ' //  ' . $startYear . ' // ' . $date . ' -> ' . $arg['season'] . ' -> ' . $season;
      
      
      
//       $get_players = "
                      
//                       SELECT s.id AS stat_id, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected, s.evaluation AS player_eval, s.strengths AS player_strength, 
//                       s.weaknesses AS player_weak, e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short, 
//                       e.logo_img AS event_image, e.org AS event_brand, e.dates AS event_dates, p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image, p.height_ft AS player_ft, p.height_in AS player_in, p.grad_year AS player_class, p.position AS player_pos, org.search_list AS org_name
//                       FROM $wpdb_stats AS s
//                       JOIN $wpdb_events AS e ON e.id = s.event
//                       JOIN $wpdb_players AS p ON s.player = p.id
//                       LEFT JOIN $wpdb_organizations AS org ON p.club_team = org.id
//                       WHERE JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated')) AND s.event != 504 AND s.enabled = 1 AND e.eventtime BETWEEN $date ORDER BY e.eventtime DESC, RAND();
//                   ";
      //old query(just in case)
      //SELECT s.id AS stat_id, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected, s.evaluation AS player_eval, s.strengths AS player_strength,  s.weaknesses AS player_weak, p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image, e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short,  e.logo_img AS event_image, e.org AS event_brand, e.dates AS evemt_dates FROM $wpdb_stats AS s JOIN $wpdb_events AS e ON e.id = s.event JOIN $wpdb_players AS p ON s.player = p.id WHERE s.evaluation IS NOT NULL AND JSON_CONTAINS_PATH(s.trends, 'one', '$.ss_event_participated') AND s.event != 504 AND e.eventtime BETWEEN $date /*AND e.org IN (8437)*/ ORDER BY p.name ASC;
      
      // Execute the query
      $player_results = $wpdb->get_results($get_players);
      
      
      
      return ['message'=>$message, 'query_result'=>$player_results];
      
      break;
    case 'grab_team_info': //grab player team info
      
      global $wpdb;
      $wpdb_rosters = $wpdb->g365_rosters;
      $wpdb_organizations = $wpdb->g365_orgs;
      $wpdb_teams = $wpdb->g365_teams;
      $wpdb_events = $wpdb->g365_events;
      
      
      $team_info_results = [];
            $event_info_results = [];

            foreach ($arg['player_id'] as $index => $player_id) {
                $event_id = $arg['event_id'][$index];

                // Combined query
                $query_combined = $wpdb->prepare(
                    "SELECT ev.id AS event_id, ev.name AS event_name, ev.org AS event_brand, team.id AS team_id, team.level AS team_level, team.name AS team_name, 
                            team.org AS team_org, team.search_list AS team_search, 
                            org.id AS org_id, org.name AS org_name, org.search_list AS org_search
                     FROM $wpdb_teams team
                     JOIN $wpdb_organizations org
                     ON team.org = org.id
                     JOIN $wpdb_events ev
                     ON ev.id = %d
                     WHERE team.id IN (
                         SELECT team 
                         FROM $wpdb_rosters 
                         WHERE JSON_CONTAINS_PATH(players, 'one', %s) 
                         AND event = %d
                     )",
                    $event_id,
                    '$."' . $player_id . '"', // The player ID must be within double quotes and prefixed by $.
                    $event_id
                );

                $results = $wpdb->get_results($query_combined);
                $team_info_results[] = json_decode(json_encode($results), true);

                // Fetch event info for the event_id
                $query_event_info = $wpdb->prepare(
                    "SELECT * 
                     FROM $wpdb_events ev
                     WHERE ev.id = %d",
                    $event_id
                );
                $event_info = $wpdb->get_results($query_event_info);
                $event_info_results[$event_id] = json_decode(json_encode($event_info), true);
            }

            return ['team_id' => $arg['player_id'], 'event_id' => $arg['event_id'], 'team_info' => $team_info_results, 'event_info' => $event_info_results];
      
      break;
    case "get_event_info": //grabbing the event info from the player participated in
      
      global $wpdb;
      $wpdb_events = $wpdb->g365_events;
      $event_id = $arg['event_id'];
      
      $query_teams = $wpdb->prepare(
      "SELECT * 
      FROM $wpdb_events ev
      WHERE ev.id = $event_id"
      );
      
      $event_results = $wpdb->get_results($query_teams);
      
      return ['event_info'=>$event_results, 'event_id'=>$event_id];
      
      break;
    case 'team_info': //grabbing player info
      $select_year = wp_date('Y');
      $season = $arg['season'];
      // Extract the first year from the '2024-2025' format
      $yearParts = explode('-', $season);
      $startYear = $yearParts[0]; // This will be '2024'
      global $wpdb;
      $wpdb_teams = $wpdb->g365_teams;
      $wpdb_organizations = $wpdb->g365_orgs;
      $wpdb_team_stats = $wpdb->g365_team_stats;
      $wpdb_events = $wpdb->g365_events;
//       $wpdb_rosters = $wpdb->g365_rosters;
//       $wpdb_players = $wpdb->g365_players;
//       $wpdb_stats = $wpdb->g365_stats;
      
      if( $season == 'frnt_page' ){
        
        $date = g365_date_format($select_year, 17); // for the query to get the date ex. '2022-09-01' AND '2023-08-31'
        $date_ranges = explode(" AND ", $date);
        
        // If there are more than two parts, handle multiple date ranges
        if (count($date_ranges) > 2) {
          
            // Split into two sets of dates for the BETWEEN condition
            $date1_start = $date_ranges[0];
            $date1_end = $date_ranges[1];
            $date2_start = $date_ranges[2];
            $date2_end = $date_ranges[3];
        
            $get_players = "
                            SELECT 
                                org.name AS org_name,
                                org.profile_img AS org_img,
                                t.search_list AS team_name, 
                                org.city AS org_city, 
                                org.state AS org_state, 
                                s.id AS stat_id, 
                                s.trends AS stat_trends, 
                                s.team AS team_id, 
                                s.event AS event_connected, 
                                s.evaluation AS team_eval, 
                                s.strengths AS team_strength, 
                                s.weaknesses AS team_weak, 
                                e.eventtime AS event_time, 
                                e.name AS event_name, 
                                e.short_name AS event_short, 
                                e.logo_img AS event_image, 
                                e.org AS event_brand, 
                                e.dates AS event_dates
                            FROM 
                                $wpdb_team_stats AS s
                            JOIN 
                                $wpdb_events AS e ON e.id = s.event
                            JOIN 
                                $wpdb_teams AS t ON t.id = s.team
                            JOIN 
                                $wpdb_organizations AS org ON org.id = t.org
                            WHERE 
                                JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated'))
                            AND 
                                s.event != 504
                            AND 
                                (e.eventtime BETWEEN $date1_start AND $date1_end OR e.eventtime BETWEEN $date2_start AND $date2_end)
                            ORDER BY 
                                org.name ASC;
                        ";
          
        }else{
          
            $get_players = "
                            SELECT 
                                org.name AS org_name,
                                org.profile_img AS org_img,
                                t.search_list AS team_name, 
                                org.city AS org_city, 
                                org.state AS org_state, 
                                s.id AS stat_id, 
                                s.trends AS stat_trends, 
                                s.team AS team_id, 
                                s.event AS event_connected, 
                                s.evaluation AS team_eval, 
                                s.strengths AS team_strength, 
                                s.weaknesses AS team_weak, 
                                e.eventtime AS event_time, 
                                e.name AS event_name, 
                                e.short_name AS event_short, 
                                e.logo_img AS event_image, 
                                e.org AS event_brand, 
                                e.dates AS event_dates
                            FROM 
                                $wpdb_team_stats AS s
                            JOIN 
                                $wpdb_events AS e ON e.id = s.event
                            JOIN 
                                $wpdb_teams AS t ON t.id = s.team
                            JOIN 
                                $wpdb_organizations AS org ON org.id = t.org
                            WHERE 
                                JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated'))
                            AND 
                                s.event != 504
                            AND 
                                e.eventtime BETWEEN $date
                            ORDER BY 
                                org.name ASC;
                        ";
          
        }
        
      }else{
        
      $date = g365_date_format($startYear, 16);
      $get_players = "
                        SELECT 
                            org.name AS org_name,
                            org.profile_img AS org_img,
                            t.search_list AS team_name, 
                            org.city AS org_city, 
                            org.state AS org_state, 
                            s.id AS stat_id, 
                            s.trends AS stat_trends, 
                            s.team AS team_id, 
                            s.event AS event_connected, 
                            s.evaluation AS team_eval, 
                            s.strengths AS team_strength, 
                            s.weaknesses AS team_weak, 
                            e.eventtime AS event_time, 
                            e.name AS event_name, 
                            e.short_name AS event_short, 
                            e.logo_img AS event_image, 
                            e.org AS event_brand, 
                            e.dates AS event_dates
                        FROM 
                            $wpdb_team_stats AS s
                        JOIN 
                            $wpdb_events AS e ON e.id = s.event
                        JOIN 
                            $wpdb_teams AS t ON t.id = s.team
                        JOIN 
                            $wpdb_organizations AS org ON org.id = t.org
                        WHERE 
                            JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated'))
                        AND 
                            s.event != 504
                        AND 
                            e.eventtime BETWEEN $date
                        ORDER BY 
                            org.name ASC;
                    ";
        
      }
      
      // Execute the query
      $team_results = $wpdb->get_results($get_players);
      
      $message = "testing here yes you got it right " . $get_players . ' // ' . $date . ' /// ' . $season;
      
      
      return ['message'=>$message, 'query_result'=>$team_results];
      
      break;
    case 'event_info':
      
      global $wpdb;
      $wpdb_events = $wpdb->g365_events;
      $today_date = $arg['today_date'];
      
      
      $query_events = $wpdb->prepare(
        "SELECT *
         FROM $wpdb_events
         WHERE eventtime > '$today_date'
         AND org IN (3191,3,7164,7165,2)
         ORDER BY eventtime ASC
         LIMIT 5;"
      );
      
      // Execute the query
      $event_results = $wpdb->get_results($query_events);
      
      return ['query_result'=>$event_results];
      
      break;
  }
}

function spp_get_divisions_lvl($post_year = null, $post_brand = null){
  
  $date = g365_date_format($post_year, 1); // for the query to get the date ex. '2022-09-01' AND '2023-08-31'

      if ($post_brand == "the-stage") {
          $org_id = '3';
          $num_game_by_month = 0;
          $default_pct = '0.49';
      } else if ($post_brand == "grassroots-365") {
          $org_id = '3191';
          $num_game_by_month = team_standing_game('', ['select_year' => $year]);
          $default_pct = '0.59';
      } else if ($post_brand == "scholastic-series") {
          $org_id = '7165';
          $num_game_by_month = 0;
          $default_pct = '0.49';
      } else if ($post_brand == "hype-her-hoops-circuit") {
          $org_id = '7164';
          $num_game_by_month = 0;
          $default_pct = '0.49';
      } else if ($post_brand == "breakthrough-circuit") {
          $org_id = '7729';
          $num_game_by_month = 0;
          $default_pct = '0.49';
      }

      // Extract start and end dates from the $date
      $date_parts = explode(' AND ', $date);
      $start_date = trim($date_parts[0], "'");
      $end_date = trim($date_parts[1], "'");
  
      // Database connection
      global $wpdb;

      $wpdb_rosters = $wpdb->g365_rosters;
      $wpdb_organizations = $wpdb->g365_orgs;
      $wpdb_games = $wpdb->g365_games;
      $wpdb_teams = $wpdb->g365_teams;
      $wpdb_events = $wpdb->g365_events;
      $wpdb_stats = $wpdb->g365_stats;

      // SQL query with placeholders for prepared statements
      $query = "
          SELECT DISTINCT r.level
          FROM $wpdb_rosters r
          WHERE r.event IN (
              SELECT e.id
              FROM $wpdb_events e
              WHERE e.eventtime BETWEEN $date
              AND e.org = %d
              AND e.id IN (
                  SELECT s.event
                  FROM $wpdb_stats s
                  WHERE s.event = e.id
                  
              )
          )
      ";

      // Prepare and print the query for debugging
      $prepared_query = $wpdb->prepare($query, $org_id);
//       echo '<br> Prepared Query: ' . $prepared_query . '<br>';

      // Execute the query
      $results = $wpdb->get_col($prepared_query);

      // Check if results are found
      if ($results) {
          // Sort the results numerically
          rsort($results, SORT_NUMERIC);
          $all_lvs = implode(',', $results);
      } else {
          $all_lvs = '';
//           echo 'No results found for the initial query.<br>';
      }

      // Convert $all_lvs to an array
      $all_lvs_array = explode(',', $all_lvs);

      // Initialize arrays
      $all_lv_list = [];
      $mobile_app_all_lv_list = [];

      // Add "All Divisions" option at the top
      $all_divisions_value = implode(',', $all_lvs_array);
//       echo 'All Divisions Value: ' . $all_divisions_value . '<br>';

      // Construct the query
      $query2 = "
          SELECT tb_3.*
          FROM (
              SELECT team_id, team_level
              FROM (
                  SELECT team_id, team_level, org_logo, CONCAT(org_name, ' ', team_name) AS full_team_name,
                      COUNT(CASE WHEN game_result_label = 'W' THEN 1 END) AS win,
                      COUNT(CASE WHEN game_result_label = 'L' THEN 1 END) AS loss,
                      COUNT(CASE WHEN game_result_label = 'W' THEN 1 END) / (COUNT(CASE WHEN game_result_label = 'W' THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L' THEN 1 END)) AS pct,
                      (IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, 0, SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN away_team_score END)) +
                      IF(SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, 0, SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN home_team_score END))) /
                      (COUNT(CASE WHEN game_result_label = 'W' THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L' THEN 1 END)) AS ppg,
                      (IF(SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END) IS NULL, 0, SUM(CASE WHEN game_result_label = 'L' AND home_team_score > away_team_score THEN home_team_score WHEN game_result_label = 'L' AND home_team_score < away_team_score THEN away_team_score END)) +
                      IF(SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END) IS NULL, 0, SUM(CASE WHEN game_result_label = 'W' AND home_team_score > away_team_score THEN away_team_score WHEN game_result_label = 'W' AND home_team_score < away_team_score THEN home_team_score END))) /
                      (COUNT(CASE WHEN game_result_label = 'W' THEN 1 END) + COUNT(CASE WHEN game_result_label = 'L' THEN 1 END)) AS opp_ppg,
                      COUNT(game_result_label) AS total_game,
                      GROUP_CONCAT('{\"event_name\": \"', event_name, '\", \"team_name\": \"', CONCAT(org_name, ' ', team_name), '\", \"game_id\": \"', game_id, '\", \"org_nickname\": \"', org_nickname, '\", \"gm_r_label\": \"', game_result_label, '\", \"game_result\": \"(', game_result, ')\", \"opp_logo\": \"', IF(opp_logo IS NULL, '', opp_logo), '\", \"opp_nickname\": \"', opp_nickname, '\", \"opp_name\": \"', opp_name, '\"}') AS standing
                  FROM (
                      SELECT inner_tb.*
                      FROM (
                          SELECT orgs.profile_img AS org_logo, rosters.id AS roster_id, rosters.division AS level_of_play, events.eventtime AS event_time, events.id AS event_id, events.name AS event_name, games.id AS game_id, games.home_team_score AS home_team_score, games.court AS game_court, games.start_time AS game_time, games.away_team_score AS away_team_score, games.home_team AS home_team_id, games.away_team AS away_team_id, teams.id AS team_id, teams.search_list AS team_name, orgs.name AS org_name, orgs.nickname AS org_nickname, orgs.id AS org_id, teams.level AS team_level, IF(rosters.id = games.home_team, games.away_team, games.home_team) AS opponent_id,
                          (SELECT CONCAT(orgs_2.name, ' ', tm.search_list)
                           FROM $wpdb_organizations orgs_2
                           INNER JOIN $wpdb_rosters rosters_2 ON rosters_2.org = orgs_2.id
                           INNER JOIN $wpdb_teams tm ON tm.id = rosters_2.team
                           WHERE rosters_2.id = IF(rosters.id = games.home_team, games.away_team, games.home_team)) AS opp_name,
                          (SELECT orgs_2.nickname
                           FROM $wpdb_organizations orgs_2
                           INNER JOIN $wpdb_rosters rosters_2 ON rosters_2.org = orgs_2.id
                           INNER JOIN $wpdb_teams tm ON tm.id = rosters_2.team
                           WHERE rosters_2.id = IF(rosters.id = games.home_team, games.away_team, games.home_team)) AS opp_nickname,
                          (SELECT orgs_3.profile_img
                           FROM $wpdb_organizations orgs_3
                           INNER JOIN $wpdb_rosters rosters_3 ON rosters_3.org = orgs_3.id
                           WHERE rosters_3.id = IF(rosters.id = games.home_team, games.away_team, games.home_team)) AS opp_logo,
                          (CASE
                              WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN CONCAT('W ', games.home_team_score, ' - ', games.away_team_score)
                              WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN CONCAT('L ', games.home_team_score, ' - ', games.away_team_score)
                              WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN CONCAT('W ', games.away_team_score, ' - ', games.home_team_score)
                              WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN CONCAT('L ', games.away_team_score, ' - ', games.home_team_score)
                              ELSE ''
                          END) AS game_result,
                          (CASE
                              WHEN rosters.id = games.home_team AND (games.home_team_score > games.away_team_score) THEN 'W'
                              WHEN rosters.id = games.home_team AND (games.home_team_score < games.away_team_score) THEN 'L'
                              WHEN rosters.id = games.away_team AND (games.away_team_score > games.home_team_score) THEN 'W'
                              WHEN rosters.id = games.away_team AND (games.away_team_score < games.home_team_score) THEN 'L'
                              ELSE ''
                          END) AS game_result_label
                          FROM $wpdb_rosters rosters
                          INNER JOIN $wpdb_organizations orgs ON orgs.id = rosters.org
                          INNER JOIN $wpdb_games games ON games.home_team = rosters.id OR games.away_team = rosters.id
                          INNER JOIN $wpdb_teams teams ON rosters.team = teams.id
                          INNER JOIN $wpdb_events events ON rosters.event = events.id
                          WHERE teams.level IN ($all_divisions_value) AND events.org = $org_id AND start_time BETWEEN $date
                      ) inner_tb
                  ) tb_1
                  GROUP BY team_id, org_logo, full_team_name
                  ORDER BY win DESC
              ) tb_2
              WHERE pct > $default_pct AND total_game >= $num_game_by_month
              ORDER BY tb_2.team_level DESC, tb_2.pct DESC, tb_2.win DESC
          ) tb_3
      ";

      // Prepare and print the query for debugging
      $prepared_query2 = $wpdb->prepare($query2);
//       echo '<br> Prepared Query 2: ' . $prepared_query2 . '<br>';

      // Execute the query
      $results2 = $wpdb->get_results($prepared_query2);

//       Debugging: Print the results
//       echo '<pre>';
//       print_r($results2);
//       echo '</pre>';

      if ($results2) {
            // Extract and make the team_level values distinct
            $team_levels = array_unique(array_map(function($result) {
                return $result->team_level;
            }, $results2));

            // Sort the team levels
            rsort($team_levels);
            // Join them into a comma-separated string
            $all_lvs2 = implode(',', $team_levels);

        } else {
            $all_lvs2 = '';
//             echo 'No results found for the second query.<br>';
        }
      

//         echo "<br><br> ===== two2: " . print_r($team_levels) . " lvs: " . print_r($all_lvs_array) . " ====";
  
        return ['team_levels'=>$team_levels, 'team_levels_string'=>$all_lvs2];
}


function scope_get_stats($player = null, $event = null, $status = 1, $order = 'stats.updatetime DESC', $ids = null, $type = null) {
  //do we have a $stat or are we searching for a type
  if( $player !== null && $event !== null && $ids === null ) return 'Parameters too specific.';
  if( !is_numeric($player) && !is_numeric($event) && intval($player) < 1 && intval($event) < 1 && $ids === null ) return 'Need numeric player/event ids.';
  
  global $wpdb;
  //all the tables we have to get data from
  $wpdb_stats = $wpdb->g365_stats;
  $wpdb_events = $wpdb->g365_events;
  $wpdb_players = $wpdb->g365_players;
  $wpdb_orgs = $wpdb->g365_orgs;
  $wpdb_positions = $wpdb->g365_positions;
  
  //create where string for a player/event record search
  if( $ids !== null && is_array($ids) ) {
    $where_string = 'stats.id IN (' . implode(',', $ids) . ')';
  } else {
    $where_string = ( $player === null ) ? 'stats.event = ' . intval($event) : 'stats.player = ' . intval($player);
  }
  if( $type !== null ) {
    if( $type === 'camps' || $type === 'dcp_player_registration' ) {
      $where_string .= ' AND stats.game = 0';
    } else {
      $where_string .= ' AND stats.game != 0';
    }
  }
  if( $status !== '0-1' ) $where_string .= ' AND stats.enabled = ' . $status;
  
  $data_columns = $wpdb->get_results(
    "SELECT stats.id, stats.player, stats.event, stats.updatetime, stats.enabled AS st_enabled, stats.profile_img as event_profile_img, stats.evaluation, stats.strengths, stats.weaknesses, stats.stats, JSON_UNQUOTE(JSON_EXTRACT(stats.trends, '$.front_page')) as front_page,
    stats.trends, stats.video, player.enabled, player.name, player.first_name, player.last_name, player.email,
      player.phone, player.profile_img, player.address, player.city, player.state, player.zip, player.country, player.birthday, player.verified, player.tagline,
      player.grad_year, player.height_ft, player.height_in, player.school AS player_school, player.weight, pos.name AS position_name, pos.id AS position_id, player.social, player.videos, player.notes,
      org_school.name AS school_name, org_school.id AS school_id, org_school.nickname AS school_url, player.gpa, player.sat, player.act, player.nickname, player.access,
      org_club.name as club_name, org_club.nickname as club_url, org_club.abbreviation as club_abb, org_club.id as club_id,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img,
      JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.jersey_size')) as jersey_size,
      events.name AS event_name, events.short_name AS event_short, events.eventtime AS event_time
    FROM $wpdb_stats AS stats
    LEFT JOIN $wpdb_players AS player ON stats.player=player.id
    LEFT JOIN $wpdb_positions AS pos ON player.position=pos.id
    LEFT JOIN $wpdb_orgs AS org_school ON player.school=org_school.id
    LEFT JOIN $wpdb_orgs AS org_club ON player.club_team=org_club.id
    LEFT JOIN $wpdb_events AS events ON stats.event=events.id
    WHERE JSON_UNQUOTE(JSON_EXTRACT(stats.trends, '$.ss_event_participated')) = '$event'
    ORDER BY $order;",
    OBJECT_K
  );
  
  //return message if we can't find records
  if( empty($data_columns) ) return "Couldn't retrieve stats for these player and/or event ids.";
  return $data_columns;
}

function scope_get_team_stats($team = null, $event = null, $status = 1, $order = 'team_stats.updatetime DESC', $ids = null, $type = null) {
  //do we have a $team_stat or are we searching for a type
  if( $team !== null && $event !== null && $ids === null ) return 'Parameters too specific.';
  if( !is_numeric($team) && !is_numeric($event) && intval($team) < 1 && intval($event) < 1 && $ids === null ) return 'Need numeric team/event ids.';
  
  global $wpdb;
  //all tables we have to get data from
  $wpdb_team_stats = $wpdb->g365_team_stats;
  $wpdb_events = $wpdb->g365_events;
  $wpdb_teams = $wpdb->g365_teams;
  $wpdb_orgs = $wpdb->g365_orgs;
  
  //create where string for a team/event record search
  if( $ids !== null && is_array($ids) ) {
    $where_string = 'team_stats.id IN (' . implode(',', $ids) . ')';
  } else {
    $where_string = ( $team === null ) ? 'team_stats.event = ' . intval($event) : 'team_stats.team = ' . intval($team);
  }
  if( $type !== null ) {
    if( $type === 'camps' ) {
      $where_string .= ' AND team_stats.game = 0';
    } else {
      $where_string .= ' AND team_stats.game != 0';
    }
  } 
  if( $status !== '0-1' ) $where_string .= ' AND team_stats.enabled = ' . $status;
  $data_columns = $wpdb->get_results(
    "SELECT team_stats.id, team_stats.team, team_stats.event, team_stats.updatetime, team_stats.enabled AS tm_enabled, 
            team_stats.profile_img as event_profile_img, team_stats.evaluation, team_stats.strengths, team_stats.weaknesses, 
            team_stats.stats, team_stats.trends, team_stats.video, JSON_UNQUOTE(JSON_EXTRACT(team_stats.trends, '$.front_page')) as front_page,
            teams.enabled, teams.name, teams.team_type, teams.org, teams.search_list, 
            events.name AS event_name, events.short_name AS event_short, events.eventtime AS event_time,
            orgs.name AS org_name
     FROM $wpdb_team_stats AS team_stats
     LEFT JOIN $wpdb_teams AS teams ON team_stats.team=teams.id
     LEFT JOIN $wpdb_events AS events ON team_stats.event=events.id
     LEFT JOIN $wpdb_orgs AS orgs ON teams.org=orgs.id
     WHERE JSON_UNQUOTE(JSON_EXTRACT(team_stats.trends, '$.ss_event_participated')) = '$event'
     ORDER BY $order;",
     OBJECT_K
  );
//   print_r($data_columns);
  return $data_columns;
}

//get player verifications
function g365_get_players_access($pl_id = null) {
	global $wpdb;
	$wpdb_players = $wpdb->g365_players;
  
  return $data_columns = $wpdb->get_results(
    "SELECT player.id, player.updatetime, player.account_level, player.enabled, player.name, player.profile_img, player.birthday, player.verified, player.grad_year,
    JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.bcert_img')) as bcert_img, JSON_UNQUOTE(JSON_EXTRACT(player.notes, '$.recard_img')) as recard_img
    FROM $wpdb_players AS player
    WHERE player.verified $change_compare $real_ver_level $attachement_filter
    ORDER BY $order_by
    LIMIT $offset, $per_pg;"
  );
}

//get info to generate passport report
function g365_get_passport_data($event, $case, $array = null, $begin = null, $end = null) {
  global $wpdb;
  $wpdb_players = $wpdb->g365_players;
  $wpdb_rosters = $wpdb->g365_rosters;
  $wpdb_stats = $wpdb->g365_stats;
  $wpdb_orgs = $wpdb->g365_orgs;
  
  $wpdb->query("SET SESSION group_concat_max_len = 9999999999999999;");
  
  $data = $wpdb->get_results(
        "SELECT REPLACE(REPLACE(REPLACE(JSON_KEYS(players), '\"',''), '[',''), ']','') AS player_ids
        FROM $wpdb_rosters
        WHERE event = '$event' AND level >= '$begin' AND level <= '$end'"
        );
        
        $id_list = [];
        foreach($data as $row) {
          $id_list[] = $row->player_ids;
        }
        //join all IDs into a single comma-separated string
        $all_ids = implode(',', $id_list);
//         return $all_ids;
  
  switch($case){
    case 'ids':
      $data = $wpdb->get_results(
            "SELECT REPLACE(GROUP_CONCAT(player_id), '\"', '') AS pl_id_list
            FROM (
                SELECT REPLACE(REPLACE(JSON_KEYS(players), '[', ''), ']', '') AS player_id
                FROM $wpdb_rosters
                WHERE event IN ($event) 
                AND org NOT IN (1469, 2076) 
                AND players IS NOT NULL
            ) AS roster_tb_pl"
        );
      return $data[0]->pl_id_list; //Return the comma-separated list of IDs as a string
      break;
    case 'non_passport':
      if(!$array) {
        return "Error: No player IDs provided.";
      }
      return $data = $wpdb->get_results(
          "SELECT pl.id AS player_id, pl.name AS player_name, pl.email AS player_email, pl.birthday AS player_birthday, pl.grad_year AS player_class, pl.phone AS player_phone
          FROM $wpdb_players pl
          WHERE pl.id IN
          ($array)
          AND pl.id NOT IN (
              SELECT player
              FROM $wpdb_stats
              WHERE event = '504'
          )
          AND pl.id NOT IN (11000, 11001)
          GROUP BY pl.id;"
      );
      break;
    case 'all_players':
      if(!$array) {
        return "Error: No player IDs provided.";
      }
      return $data = $wpdb->get_results(
        "SELECT pl.id AS player_id, pl.name AS player_name, pl.email AS player_email, pl.birthday AS player_birthday, pl.grad_year AS player_class, pl.phone AS player_phone, org.name AS team_name
        FROM $wpdb_players pl
        CROSS JOIN $wpdb_orgs org ON pl.club_team=org.id
        WHERE pl.id IN
        ($array)
        AND pl.id NOT IN (11000, 11001)
        GROUP BY pl.id;"
      );
      break;
    case 'non_age_range':
      if(!$all_ids) {
        return "Error: No player IDs provided.";
      }
      return $data = $wpdb->get_results(
          "SELECT pl.id AS player_id, pl.name AS player_name, pl.email AS player_email, pl.birthday AS player_birthday, pl.grad_year AS player_class, pl.phone AS player_phone
          FROM $wpdb_players pl
          WHERE pl.id IN
          ($all_ids)
          AND pl.id NOT IN (
              SELECT player
              FROM $wpdb_stats
              WHERE event = '504'
          )
          AND pl.id NOT IN (11000, 11001)
          GROUP BY pl.id;"
      );
      break;
    case 'all_age_range':
      if(!$all_ids) {
        return "Error: No player IDs provided.";
      }
      return $data = $wpdb->get_results(
        "SELECT pl.id AS player_id, pl.name AS player_name, pl.email AS player_email, pl.birthday AS player_birthday, pl.grad_year AS player_class, pl.phone AS player_phone, org.name AS team_name
        FROM $wpdb_players pl
        CROSS JOIN $wpdb_orgs org ON pl.club_team=org.id
        WHERE pl.id IN
        ($all_ids)
        AND pl.id NOT IN (11000, 11001)
        GROUP BY pl.id;"
      );
      break;
    default: 
      echo "No Data Results";
  }
    
}

function g365_get_player_row($player_id) {
  global $wpdb;
	$wpdb_players = $wpdb->g365_players;
  
  return $data = $wpdb->get_row(
    "SELECT * FROM $wpdb_players WHERE id = $player_id;"
  );
}


function hhh_grab_info( $type = null, $arg = null ) {
    global $wpdb; 
    $dbs = json_decode(dbs());

    // Ensure $arg is an array and contains the 'season' key
    if (!is_array($arg)) {
        $arg = ['season' => $arg]; // Convert to array with 'season' if needed
    }

    switch($type) {
        case 'plyer_info': // Grabbing player info
            $season = $arg['season'];

            // Extract the first year from the '2024-2025' format if needed
            $yearParts = explode('-', $season);
            $startYear = $yearParts[0]; // This will be '2024'
            $select_year = wp_date('Y');
            $wpdb_players = $wpdb->g365_players;
            $wpdb_stats = $wpdb->g365_stats;
            $wpdb_events = $wpdb->g365_events;
            $wpdb_rosters = $wpdb->g365_rosters;
            $wpdb_teams = $wpdb->g365_teams;
            $wpdb_organizations = $wpdb->g365_orgs;        
        
            if( $season == 'frnt_page' ){ //for front page filter including in the first two months to still grab info from last year
              $date = g365_date_format($select_year, 17);  
              $date_ranges = explode(" AND ", $date);

              // If there are more than two parts, handle multiple date ranges
              if (count($date_ranges) > 2) {
                  // Split into two sets of dates for the BETWEEN condition
                  $date1_start = $date_ranges[0];
                  $date1_end = $date_ranges[1];
                  $date2_start = $date_ranges[2];
                  $date2_end = $date_ranges[3];

                  // Update the query to use OR between the two date ranges
                  $get_players = "
                      SELECT s.id AS stat_id, s.updatetime AS eval_date, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected,
                      s.evaluation AS player_eval, s.strengths AS player_strength, s.weaknesses AS player_weak,
                      e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short,
                      e.logo_img AS event_image, e.org AS event_brand, e.dates AS event_dates, 
                      p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image,
                      p.height_ft AS player_ft, p.height_in AS player_in, p.grad_year AS player_class,
                      p.position AS player_pos, org.search_list AS org_name
                      FROM $wpdb_stats AS s
                      JOIN $wpdb_events AS e ON e.id = s.event
                      JOIN $wpdb_players AS p ON s.player = p.id
                      LEFT JOIN $wpdb_organizations AS org ON p.club_team = org.id
                      WHERE e.org = 7164 -- Check if the event organization ID is 7164
                      AND s.evaluation IS NOT NULL
                      AND TRIM(s.evaluation) != ''
                      AND s.event != 504 -- Ensure the event is not 504
                      AND s.enabled = 1 -- Ensure the record is enabled
                      AND (e.eventtime BETWEEN $date1_start AND $date1_end 
                          OR e.eventtime BETWEEN $date2_start AND $date2_end) -- Event time range
                      ORDER BY e.eventtime DESC, RAND();
                  ";

                  $message = "testing here yes you got it right " . $get_players . ' //  ' . $startYear . ' // ' . $date . ' -> ' . $arg['season'] . ' -> ' . $season;
              } else {
                  // Single date range
                  $get_players = "
                      SELECT s.id AS stat_id, s.updatetime AS eval_date, s.trends AS stat_trends, s.player AS player_id, s.event AS event_connected,
                      s.evaluation AS player_eval, s.strengths AS player_strength, s.weaknesses AS player_weak,
                      e.eventtime AS event_time, e.name AS event_name, e.short_name AS event_short,
                      e.logo_img AS event_image, e.org AS event_brand, e.dates AS event_dates, 
                      p.nickname AS player_nick, p.name AS player_name, p.profile_img AS player_image,
                      p.height_ft AS player_ft, p.height_in AS player_in, p.grad_year AS player_class,
                      p.position AS player_pos, org.search_list AS org_name
                      FROM $wpdb_stats AS s
                      JOIN $wpdb_events AS e ON e.id = s.event
                      JOIN $wpdb_players AS p ON s.player = p.id
                      LEFT JOIN $wpdb_organizations AS org ON p.club_team = org.id
                      WHERE JSON_UNQUOTE(JSON_EXTRACT(s.trends, '$.ss_event_participated'))
                      AND s.event != 504
                      AND s.enabled = 1
                      AND e.eventtime BETWEEN $date
                      ORDER BY e.eventtime DESC, RAND();
                  ";
              }


            }
            
            // Message and query execution
//             $message = "Query: " . $get_players;
        
            $player_results = $wpdb->get_results($get_players);

            return ['message' => $message, 'query_result' => $player_results];
    }
}

function generate_seasons($start_year = 2023) {
    $current_year = (int)date('Y');
    $current_month = (int)date('m');
    $seasons = [];

    // Determine the current season based on today's date
    $is_fall_season = ($current_month >= 9); // September is the start of the season
    $current_season_year = $is_fall_season ? $current_year : $current_year - 1;

    // Generate seasons from 2023-2024 down to the current season
    for ($year = $current_season_year; $year >= $start_year; $year--) {
        $next_year = $year + 1;
        $season_label = "$year-$next_year";
        $seasons[] = $season_label;
    }

    return $seasons;
}

// function g365_player_img_dir($player_nickname, $event_nickname, $player_id, $type = null){
//   global $wpdb;
//   $wpdb_players = $wpdb->g365_players;

//   // Query to get profile image URL from the database
//   $get_profile_img_url = "SELECT profile_img FROM $wpdb_players WHERE id = %d;";
//   $profile_img_url_results = $wpdb->get_results($wpdb->prepare($get_profile_img_url, $player_id));

//   // Define default paths for images
//   $profile_img_url = './wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg';
//   $event_pro_img_url = './wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
//   $default_img = get_site_url().'/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';

//   // Check if event image exists
//   if( file_exists($event_pro_img_url) ){ 
//     return get_site_url().'/wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
//   } 
//   // Check if profile image exists
//   elseif( file_exists($profile_img_url) ){ 
//     return get_site_url().'/wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg' . '?get_fresh='.time();
//   } 
//   // Check if the database query returned a valid result
//   elseif( !empty($profile_img_url_results) && isset($profile_img_url_results[0]->profile_img) && !is_null($profile_img_url_results[0]->profile_img) ) {
//     return get_site_url().'/wp-content/uploads/player-profiles/'.$profile_img_url_results[0]->profile_img . '?get_fresh='.time(); // Return the profile image from the database
//   } 
//   // Check for a camp image
//   else {
//     $camp_img_url = get_stat_tb($player_nickname, $event_nickname, $player_id);
//     if( !empty($camp_img_url) && isset($camp_img_url[0]['profile_img']) && !is_null($camp_img_url[0]['profile_img']) ) {
//       return $camp_img_url[0]['profile_img']; // Return the latest camp image
//     } 
//     // Return default image if nothing is found
//     else {
//       return $default_img;
//     }
//   }
// }

function g365_player_img_dir($player_nickname, $event_nickname, $player_id, $type = null){
  global $wpdb;
  $wpdb_players = $wpdb->g365_players;
  $get_profile_img_url = 
    "SELECT profile_img FROM $wpdb_players WHERE id = $player_id;";
  $profile_img_url_results = $wpdb->get_results($get_profile_img_url);
//   print_r($profile_img_url_results);
  $profile_img_url = './wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg';
  $event_pro_img_url = './wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
  $default_img = get_site_url().'/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
  if( file_exists($event_pro_img_url) ){ // Get event image
    $event_pro_img_url = get_site_url().'/wp-content/uploads/event-profiles/'.$event_nickname.'/'.$player_nickname.'_'.$player_id.'.jpg';
    return $event_pro_img_url;
  }else{ 
    if( file_exists($profile_img_url) ){ // Profile image if no event image
      $profile_img_url = get_site_url().'/wp-content/uploads/player-profiles/'.$player_nickname.'_'.$player_id.'.jpg' . '?get_fresh='.time();
      return $profile_img_url;
    }else{ 
      $camp_img_url = get_stat_tb($player_nickname, $event_nickname, $player_id);
      if(!empty($camp_img_url)){ $camp_img_url = $camp_img_url[0]['profile_img']; } // Latest camp image
      if(!empty($camp_img_url)){
        return $camp_img_url;        
      }else{
        return $default_img;        
      }
    }
  }
}
?>