<?php 
/*************************
*** Admin Photo Upload ***
*************************/
// Load all necessary filepond core files
echo filepond_core()['core']; echo filepond_core()['ext'];
$ul_type = url_param('type'); $url = get_site_url().$_SERVER['REQUEST_URI']; $url = preg_replace('~(\?|&)pl_id=[^&]*~','$1', $url);
$page_type = url_param('page');
?>
<div class="photo-upload-box">
  <?php echo g365_message()['max_upload_at_a_time'];
  if($page_type == 'admin_data_photo_verif'){
//     echo g365_message()['photo_allowed_file'];
    echo g365_file_upload('admin-photo'); 
  }
  else if($page_type == 'admin_data_video_verif'){ 
//     echo g365_message()['video_allowed_file']; 
    echo g365_file_upload('admin-video'); } 
  ?>
</div>
<ul class="pl_profile_ul slb small-up-3 medium-up-3 text-center xlarge-padding-bottom">
  <li class="tabs-title cell<?php echo ( empty($ul_type) || strtolower($ul_type) === 'unassigned' ) ? ' is-active': ''; ?>">
    <a href="<?php echo $url; ?>&type=unassigned&pageno=1" style="font-size:20px" class="profile-title profile__nav--item block"<?php echo ( empty($ul_type) || strtolower($ul_type) === 'unassigned' ) ? ' aria-selected="true"': ''; ?>>Unassigned</a>
  </li>
  <li class="tabs-title cell<?php echo ( strtolower($ul_type) === 'assigned' ) ? ' is-active': ''; ?>">
    <a href="<?php echo $url; ?>&type=assigned&pageno=1" style="font-size:20px" class="profile-title profile__nav--item block"<?php echo ( strtolower($ul_type) === 'assigned' ) ? ' aria-selected="true"': ''; ?>>Assigned</a>
  </li>
  <li id="pendingPhotosAdmin" class="tabs-title cell<?php echo ( strtolower($ul_type) === 'pending' ) ? ' is-active': ''; ?>">
    <a href="<?php echo $url; ?>&type=pending&pageno=1" style="font-size:20px" class="profile-title profile__nav--item block"<?php echo ( strtolower($ul_type) === 'pending' ) ? ' aria-selected="true"': ''; ?>>Pending</a>
  </li>
</ul>
<?php 
  switch($ul_type){
    case '':
    case 'unassigned':
      g365_dir_render('photo-upload', 'photo-view-render', $player_id, ['type'=>'admin-photo-upload', 'page_type'=>$page_type]);
      break;
    case 'assigned':
      g365_dir_render('photo-upload', 'photo-view-render', $player_id, ['type'=>'admin-assigned-photo', 'title'=>$title, 'page_type'=>$page_type]);
      break;
    case 'pending':
      $is_pending_menu = true;
      g365_dir_render('photo-upload', 'photo-view-render', $player_id, ['type'=>'user-photo-upload', 'is_pending_menu'=>$is_pending_menu, 'url'=>$url, 'page_type'=>$page_type]);
      break;
  }
?>

