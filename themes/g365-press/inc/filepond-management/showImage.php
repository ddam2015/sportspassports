<?php
               $files = glob("../wp-content/themes/g365-press/assets/photo-additions/uploads/*.*");

                for ($i=0; $i<count($files); $i++) {
                    $image = $files[$i];
//                     print $image ."<br />";
                    echo '<img src="'.$image .'" alt="Random image" />'."<br /><br />";
                }

?>