<div class="photo-container">
  <?php if($arg['is_pending_menu'] === true){ 
    $admin_ph_pd_url = $arg['url']; $submenu_type = url_param('submenu'); echo g365_submenu_type(['admin_ph_pd_url'=>$admin_ph_pd_url, 'type'=>$submenu_type], 8); } ?>
  <hr/>
  <?php $pl_id_param = url_param('pl_id'); $filter_pl_name = g365_get_pl_data(['pl_id'=>$pl_id_param], 'g365-pl-photo')[0]->name; switch($arg['type']){ case 'admin-assigned-photo': if($arg['page_type'] == 'admin_data_photo_verif'){ $file_ls = 'player_photo_search'; }else if($arg['page_type'] == 'admin_data_video_verif'){ $file_ls = 'player_video_search'; } ?>
    <div class="cell large-12"><input type="text" class="search-hero g365_livesearch_input" data-g365_type="<?php echo $file_ls; ?>" placeholder="Enter Player Name" autocomplete="off" autofocus></div>
  <?php break; 
    case 'user-photo-upload': 
      if($arg['page_type'] == 'admin_data_photo_verif'){ 
        $file_ls = 'player_pending_photo_search'; 
      }else if($arg['page_type'] == 'admin_data_video_verif'){ 
        $file_ls = 'player_pending_video_search'; 
      } ?>
  <div class="cell large-12"><input type="text" class="search-hero g365_livesearch_input" data-g365_type="<?php echo $file_ls; ?>" placeholder="Enter Player Name" autocomplete="off" autofocus></div>
  <?php break; } ?>
  <div class="photo__library photo-frames" id="photoAdminLibrary">
    <?php 
      if (isset($_GET['pageno'])) {
          $pageno = $_GET['pageno'];
      } else {
          $pageno = 1;
      }
    
   $totalQuery = g365_img_count($arg['type'], ['pl_id'=>$player_id, 'is_approved'=>$submenu_type]); 
   $total = $totalQuery[0]->count;
   $limit = 54;
   $total_pages = ceil($total / $limit);
   $offset = ($pageno-1) * $limit;
    
    
    $uploaded_photos = g365_img_queries($arg['type'], ['pl_id'=>$pl_id_param, 'is_approved'=>$submenu_type], $offset, $limit); 
    if(!empty($pl_id_param) && empty($uploaded_photos)){
      echo '<div class="cell large-12"><h3>There isn\'t any results for: '.$filter_pl_name.'</h3></div>'; 
    } 
    if(!empty($uploaded_photos)): 
      if(!empty($pl_id_param)){ 
        if(!empty($filter_pl_name)){ echo '<div class="cell large-12"><h3>Results for: '.$filter_pl_name.'</h3></div>'; 
       } 
      } 
    foreach($uploaded_photos as $uploaded_photo):
      $user_meta = get_userdata($uploaded_photo->user_id); $user_roles = $user_meta->roles; /*main-foreach*/ 
      $file_view_rendered = g365_media_view_rendering('view-media-file', ['auth_user'=>$user_roles[0], 'user_id'=>$uploaded_photo->user_id, 'file_name'=>$uploaded_photo->img_name, 'file_id'=>$uploaded_photo->id, 'is_admin'=>$uploaded_photo->admin_addition, 'given_file_ext'=>$uploaded_photo->highlight]);
      $pl_lists = json_decode($uploaded_photo->player_id); $pl_lists = json_decode(json_encode($pl_lists), true); ?>
       <?php if($arg['page_type'] == 'admin_data_photo_verif'):/*if-page*/ if($file_view_rendered['file_type'] == 'image'):/*if-image*/ switch($arg['type']){ case 'user-photo-upload':  echo '<div class="photo__pending--img-container">'; echo ph_toggle(['toggle_id'=>'tog_'.$uploaded_photo->id, 'is_checked'=>$uploaded_photo->rejected, 'img_id'=>$uploaded_photo->id], 'admin-pending'); break; } ?>
      <div class="photo__img-container photo-frames-container" id="photoAttachPlayer-<?php echo $uploaded_photo->id; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>">
        <?php echo $file_view_rendered['main_view']; ?>
      </div>
      <div class="photo__container--attach" id="photoAttachPlayer-<?php echo $uploaded_photo->id; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" style="max-height:800px; overflow-y:scroll;">
            <button class="button--back" id="back_btn-<?php echo $uploaded_photo->id; ?>">&lt; Back</button>
          <h3 class="text-center">Attach Player to Photo</h3>
          <div class="grid-x align-spaced">
          <div class="grid-y">
            <div class="photo__img-container--attach">
              <?php echo $file_view_rendered['model_view_class']; ?>
            </div>
            <?php if(!empty($uploaded_photo->player_id)): ?>
            <div class="assigned_pl_box">
              <?php if(!empty($pl_lists['pl_id'])){ echo '<p class="photo__heading--attach">Player(s) in Photo:</p>'; } ?>
              <ol class="photo__player-list--attach">
                <?php foreach($pl_lists as $pl_list): $pl_list_string = implode(', ', $pl_list); ?><input type="hidden" id="pl_list_string-<?php echo $uploaded_photo->id; ?>" name="g365_id_list" value="<?php echo $pl_list_string; ?>"><?php foreach($pl_list as $pl_id): ?>
                  <div class="flex item-center" id="li-<?php echo $pl_id; ?>">
                    <?php echo g365_admin_rm_btn(['pl_id'=>$pl_id]); ?>
                    <li id="<?php echo $pl_id; ?>"><?php echo g365_get_pl_data(['pl_id'=>$pl_id], 'g365-pl-photo')[0]->name; ?></li>
                  </div>
                <?php endforeach; endforeach; ?>
              </ol>
            </div>
            <?php endif; ?>
          </div>
          <div class="relative">
            <input type="text" class='search-hero g365_livesearch_input expanded' data-g365_action="" data-g365_type="player_photo" placeholder="Enter Player Name" autocomplete="off" autofocus>
          </div>
        </div>
        <div class="grid-x align-right small-margin-top">
          <p class="ph-assign-succ"></p>
          <button data-img-name="<?php echo $uploaded_photo->img_name; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" class="button edit_photo rm_ph">Delete this photo</button>
          <button data-img-name="<?php echo $uploaded_photo->img_name; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" class="button assign_photo">Assign Player(s) to Photo</button>
        </div>
      </div>
     <?php switch($arg['type']){ case 'user-photo-upload': echo '</div>';  break; } endif;/*if-image*/ endif;/*endif-page-img*/ 
      if($arg['page_type'] == 'admin_data_video_verif'):/*if-page-vid*/ if($file_view_rendered['file_type'] == 'video'):/*if-image*/ switch($arg['type']){ case 'user-photo-upload':  echo '<div class="photo__pending--img-container">'; echo ph_toggle(['toggle_id'=>'tog_'.$uploaded_photo->id, 'is_checked'=>$uploaded_photo->rejected, 'img_id'=>$uploaded_photo->id], 'admin-pending'); break; } ?>
      <div class="photo__img-container photo-frames-container" id="photoAttachPlayer-<?php echo $uploaded_photo->id; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>">
        <?php echo $file_view_rendered['main_view']; ?>
      </div>
      <div class="photo__container--attach" id="photoAttachPlayer-<?php echo $uploaded_photo->id; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" style="max-height:800px; overflow-y:scroll;">
         <button class="button--back" id="back_btn-<?php echo $uploaded_photo->id; ?>">&lt; Back</button>
         <h3 class="text-center">Attach Player</h3>
        <div class="grid-x align-spaced">
          <div class="grid-y">
            <div class="photo__img-container--attach">
              <?php echo $file_view_rendered['model_view_class']; ?>
            </div>
            <?php if(!empty($uploaded_photo->player_id)): ?>
            <div class="assigned_pl_box">
              <?php if(!empty($pl_lists['pl_id'])){ echo '<p class="photo__heading--attach">Player(s) in Video:</p>'; } ?>
              <ol class="photo__player-list--attach">
                <?php foreach($pl_lists as $pl_list): $pl_list_string = implode(', ', $pl_list); ?><input type="hidden" id="pl_list_string-<?php echo $uploaded_photo->id; ?>" name="g365_id_list" value="<?php echo $pl_list_string; ?>"><?php foreach($pl_list as $pl_id): ?>
                  <div class="flex item-center" id="li-<?php echo $pl_id; ?>">
                    <?php echo g365_admin_rm_btn(['pl_id'=>$pl_id]); ?>
                    <li id="<?php echo $pl_id; ?>"><?php echo g365_get_pl_data(['pl_id'=>$pl_id], 'g365-pl-photo')[0]->name; ?></li>
                  </div>
                <?php endforeach; endforeach; ?>
              </ol>
            </div>
            <?php endif; ?>
          </div>
          <div class="relative">
            <input type="text" class='search-hero g365_livesearch_input expanded' data-g365_action="" data-g365_type="player_photo" placeholder="Enter Player Name" autocomplete="off" autofocus>
          </div>
        </div>
        <div class="grid-x align-right small-margin-top">
          <p class="ph-assign-succ"></p>
          <button data-img-name="<?php echo $uploaded_photo->img_name; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" class="button edit_photo rm_ph">Delete this file</button>
          <button data-img-name="<?php echo $uploaded_photo->img_name; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" class="button assign_photo">Assign Player(s)</button>
        </div>
      </div>
     <?php switch($arg['type']){ case 'user-photo-upload': echo '</div>';  break; } endif;/*if-image*/ endif;/*endif-page-vid*/ endforeach;/*main-endforeach*/ else: echo ('<h6 class="text-center">'.g365_message()['admin_no_photo'].'</h6>'); endif; echo g365_custom_js('filepond'); echo ajax_data_xfer(['class_name'=>'assign_photo'], 'assign-ph-pl'); echo ajax_data_xfer(['class_name'=>'ph_toggle'], 'approve-photo'); echo ajax_data_xfer(['class_name'=>'edit_photo'], 'edit-photo'); ?>
  </div>
  
  <div>
    
    <?php  if(!empty($uploaded_photos)): ?>
      <div class="pagination-container">
<!--         <p id="pagination-current"></p> -->
        <ul class="pagination">
              <li>Go to Page: </li>
              <?php
                for($num = 0; $num < $total_pages; $num++) {
                  $newNum = $num + 1;
                  echo "<li><a data-page-id='{$newNum}'>{$newNum}</a></li>";
                }
              ?>
          </ul>
      </div>
    <?php endif;?>
  </div>
</div>

<script type="text/javascript">  
  let url = window.location.href;
  const pageNumbers = document.querySelectorAll('.pagination li a');
  
  
  pageNumbers.forEach(function(number) {
    number.href = url + '&pageno='+ number.getAttribute('data-page-id');  
  })
  
  
</script>