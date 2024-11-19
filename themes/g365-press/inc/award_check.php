<?php

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $start_date = $_POST['start_date'];
//     $end_date = $_POST['end_date'];

//     mk_g365_get_event_date($start_date, $end_date);
// }

// // get dates and store in array
// function mk_g365_get_event_date($start_date, $end_date) {
//     // format date to match database
//     $start_date = date('Y-m-d', strtotime($start_date));
//     $end_date = date('Y-m-d', strtotime($end_date));

//     // make sure we have a value
//     if ($start_date === null || $end_date === null) return 'Need date range.';
//     if ($start_date >= $end_date) return 'Start date must be before end date';

//     $short_name = g365_get_events(null, 1);

  

//     // replace space with underscore in $short_name
//     foreach ($short_name as &$event) {
//         $event->short_name = str_replace(' ', "-", $event->short_name);
//     }
//   //Directories to search
//     $directoriesToSearch = array(
//       "/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/",
//       "/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/themes/g365-press/assets/badges/all-tournament/"
//     );

// //     $files1 = glob("/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/" . '/*');
// //     $files2 = glob("/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/themes/g365-press/assets/badges/all-tournament/" . '/*');
// //     $all_files = array_merge($files1, $files2);
// //     $merged_files = [];
    
// //     //take all files and combine into one array
// //     foreach ($all_files as $file) {      
// //       if(is_file($file)) {
// //         $merged_files[] = file_get_contents($file);
// //       }
// //     }
    
//     //Output the HTML for the table
//     echo '<table>' .
//          '<thead>' .
//          '<tr>' .
//          '<th style="text-align: center;">Champions</th>' .
//          '<th style="text-align: center;">Runner Up</th>' .
//          '<th style="text-align: center;">All Tournament MVP</th>' .
//          '<th style="text-align: center;">All Tournament Team</th>' .
//          '</tr>' .
//          '</thead>' .
//          '<tbody>';

  
//     //Loop through each directory
//     foreach ($directoriesToSearch as $directory) {
//       //Initialize variable to track the content for each column
//       $champions_content = '';
//       $runner_up_content = '';
//       $mvp_content = '';
//       $team_content = '';
//       $uploadButton = '';
      
//       //Get the list of files in the directory
// //       $files = scandir($directory);
      
//       $files = glob($directory . '*');
//       $merged_files = array_merge($merged_files, $files);
      
//         //Loop through each file in the directory
//         foreach ($merged_files as $file) { 
//           $file_name = basename($file);
//           $matchFound = false;
//           //check if any part of the file name matches any string in the array
//           foreach ($short_name as $substring) {
//             // Check for each condition and append content if a match is found
//             if($substring->short_name . "-Champions.png" == $file_name) {
//               $matchFound = true;
//               $prefix = "https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/";   
//               $champions_content .= '<td style="text-align: center;"><img src="' . $prefix . $file_name . '" alt="Image">' .
//                                     '<button class="button-primary button-large" type="button" onclick="replaceImage(\'' . $file_name . '\')">Replace</button>' .
//                                     '<button class="button-primary button-large" type="button" onclick="deleteImage(\'' . $file_name . '\')">Delete</button>' .
//                                     '</td>';
//             } 
//             elseif($substring->short_name . "-Runner-Up.png" == $file_name) {
//               $matchFound = true;
//               $prefix = "https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/championship-and-runner-up/";
//               $runner_up_content .= '<td style="text-align: center;"><img src="' . $prefix . $file_name . '" alt="Image">' .
//                                     '<button class="button-primary button-large" type="button" onclick="replaceImage(\'' . $file_name . '\')">Replace</button>' .
//                                     '<button class="button-primary button-large" type="button" onclick="deleteImage(\'' . $file_name . '\')">Delete</button>' .
//                                     '</td>';
//             }
//             elseif($substring->short_name . "-All-Tournament-MVP.png" == $file_name) {
//               $matchFound = true;
//               $prefix = "https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/all-tournament/";
//               $mvp_content .= '<td style="text-align: center;"><img src="' . $prefix . $file_name . '" alt="Image">' .
//                               '<button class="button-primary button-large" type="button" onclick="replaceImage(\'' . $file_name . '\')">Replace</button>' .
//                               '<button class="button-primary button-large" type="button" onclick="deleteImage(\'' . $file_name . '\')">Delete</button>' .
//                               '</td>';
//             } 
//             elseif($substring->short_name . "-All-Tournament-Team.png" == $file_name) {
//               $matchFound = true;
//               $prefix = "https://dev.sportspassports.com/wp-content/themes/g365-press/assets/badges/all-tournament/";
//               $team_content .= '<td style="text-align: center;"><img src="' . $prefix . $file_name . '" alt="Image">' .
//                                 '<button class="button-primary button-large" type="button" onclick="replaceImage(\'' . $file_name . '\')">Replace</button>' .
//                                 '<button class="button-primary button-large" type="button" onclick="deleteImage(\'' . $file_name . '\')">Delete</button>' .
//                                 '</td>';
//             } 
//             // Display upload button if no match is found
//             if (!$matchFound) {
//               $uploadButton = '<td style="text-align: center;" colspan="3"><button type="button" onclick="uploadImage()">Upload</button></td>';
//             }
//         }
//       }  
//       //check if content exists before adding rows
//       if(!empty($champions_content)) {
//         echo '<tr>' . $champions_content . '</tr>';
//       }
//       if(!empty($runner_up_content)) {
//         echo '<tr>' . $runner_up_content . '</tr>';
//       }
//       if(!empty($mvp_content)) {
//         echo '<tr>' . $mvp_content . '</tr>';
//       }
//       if(!empty($team_content)) {
//         echo '<tr>' . $team_content . '</tr>';
//       }
      
      
//       echo '</tbody>' .
//            '</table>';
//       echo $uploadButton;
//     }
?>
