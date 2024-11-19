<div class="grid-x large-12 block text-right" style="width:100%;">
  <button class="fi-flag large-12 tiny-padding small-margin-bottom report_btn" onClick="report_btn()" style="width:100px;"> Report</button>
</div>
<?php echo g365_custom_ninja_forms('report-photo'); ?>
<div class="grid-x small-up-2 medium-up-4 text-center profile-feature profile-widget profile-photos" style="display: flex;width: 100%;">
  <?php 
  $i = 0; 
  
  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
      $pageno = 1;
  }
  $limit = 15;
  $totalQuery = g365_img_count('player-photo-view', ['pl_id'=>$player_id]); 
  $total = $totalQuery[0]->count;
  $total_pages = ceil($total / $limit);
  $offset = ($pageno-1) * $limit;
  $uploaded_photos = g365_img_queries('player-photo-view', ['pl_id'=>$player_id], $offset, $limit); 
  if(!empty($uploaded_photos)): ?>
  <?php
  if(current_user_can('administrator')){ echo ('<div class="fi-shield pl_lock_message large-12 small-margin-bottom"> '.g365_message()['admin_view'].'</div>'); }else{
    if(g365_player_unlock_status($player_id)[0] === 'Season') { 
      echo '';
    } else echo '<a class="small-margin-bottom" href="'.get_site_url().'/product/passport-annual/" style="width: 100%;">'.g365_message()['pp_ph_link'].'</a>';
  }
  ?> 
  <?php foreach($uploaded_photos as $uploaded_photo): /*main-foreach*/ $pl_lists = json_decode($uploaded_photo->player_id); $pl_lists = json_decode(json_encode($pl_lists), true); $is_pp_photo_acc = g365_passport_validation('subscription-validation', ['pp_data'=>stat_subscription($player_id)[2], 'selected_year'=>$uploaded_photo->updatetime]); $restricted_pp_photo = g365_passport_validation('restricted-passport-photo', ['photo_upload_date'=>$uploaded_photo->updatetime, 'photo_order'=>$i]);
  $user_meta = get_userdata($uploaded_photo->user_id); $user_roles = $user_meta->roles; ?>
      <div class="photo__img-container" style="box-shadow: 0px 0px 3px 1px rgba(0,0,0,0.2);">
        <!-- Check if this profile photo is unlocked for passport.  -->
        <?php if($is_pp_photo_acc === 'true' || current_user_can('administrator')): ?>
        <img class="photo__img" loading="lazy" src="<?php echo g365_media_dir('admin-photo-media-g365', ['auth_user'=>$user_roles[0], 'user_id'=>$uploaded_photo->user_id]) . $uploaded_photo->img_name; ?>" alt="<?php echo $uploaded_photo->img_name?>" data-order="<?php echo $i;?>">
        <?php else: echo $restricted_pp_photo; endif; ?>
      </div>
  <?php $i++; endforeach; else: echo ('<h5 class="large-12 text-center">'.g365_message()['photo_upload'].'<h5>'); endif; /*main-if-and-foreach*/ ?>
</div>
<?php  if(!empty($uploaded_photos)): ?>
<div class="pagination-container">
  <ul class="pagination">
        <li>Page: </li>
        <?php
          for($num = 0; $num < $total_pages; $num++) {
            $newNum = $num + 1;
            echo "<li><a href='?pageno={$newNum}'>{$newNum}</a></li>";
          }
        ?>
    </ul>
</div>
<?php endif;?>
<div class="grid-x large-12 block text-right" style="width:100%;">
  <a href="<?php echo get_site_url(); ?>/account/player-photos/"><button class="fi-upload large-12 tiny-padding small-margin-bottom report_btn" style="width:100px;"> Upload Photo</button></a>
</div>