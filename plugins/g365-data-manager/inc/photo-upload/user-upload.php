<?php $media_type = g365_woocom_current_page('page-type'); //print_r(g365_db_handler('photo-player', null, ['pl_id_list'=>'39403, 9558'])); ?>
<div class="cell small-12 medium-8 large-6">
  <h2 class="text-underline medium-margin-bottom text-center"><?php echo $media_type['label']; ?> Manager</h2>
  <?php echo g365_message()[$media_type['alias'].'_allowed_file']; 
  $current_user = wp_get_current_user(); $claimed_pl = get_user_meta($current_user->ID, '_user_owns_g365', true); $is_limit_reached = g365_media_upload_limit($media_type['alias'], $current_user->ID); $user_meta = get_userdata($current_user->ID); $user_roles = $user_meta->roles; if($is_limit_reached['locked'] !== true): echo g365_file_upload($media_type['alias']);?>
  <?php else: echo $is_limit_reached['message']; endif; ?>
  <!--  Photo Library  -->
  <div class="g365_data_manager_content_wrapper medium-margin-top">
    <h3 class="text-underline text-center"><?php echo $media_type['label']; ?> Library</h3>
    <!-- Claimed Player List -->
    <h5 class="text-underline">Claimed player(s) under your account.</h5>
    <?php if(!empty($claimed_pl)): ?>
    <div class="claimed_player_list">
      <ol>
        <?php foreach($claimed_pl as $claimed_pl_data): ?>
          <?php foreach($claimed_pl_data as $claimed_pl_id): ?>
          <div><li><?php echo g365_get_pl_data(['pl_id'=>$claimed_pl_id], 'g365-pl-photo')[0]->name; ?></li></div>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </ol>
    </div>
    <?php else: echo "<h5 class='text-center'>".g365_message()['claimed_pl']."</h5>" ; endif; ?>
    <ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12" data-accordion data-allow-all-closed="true">
      <li class="accordion-item small-padding-bottom" data-accordion-item>
        <!-- Accordion tab title -->
        <a href="#" class="accordion-title">Approved <?php echo $media_type['label']; ?>s</a>
        <div class="accordion-content" data-tab-content>
          <!--  Approved  -->
          <div class="approved_ph_box">
            <div class="grid-x grid-margin-x small-up-2 medium-up-4 text-center profile-feature profile-widget profile-photos small-padding user-approved-media" style="display: flex;width: 100%;" id="photoAdminLibrary">
            <?php $i = 0; 
              $uploaded_photos = g365_img_queries('user-acct-ph-view', ['user_upload_status'=>'approved', 'user_id'=>$current_user->ID]); 
              if(!empty($uploaded_photos)): if($media_type['alias'] == 'video'){ echo g365_message()['profile_video']; } 
              foreach($uploaded_photos as $uploaded_photo): /*main-foreach*/ 
              $pl_lists = json_decode($uploaded_photo->player_id); 
              $pl_lists = json_decode(json_encode($pl_lists), true); 
              $file_view_rendered = g365_media_view_rendering($media_type['alias'].'-only', ['auth_user'=>$user_roles[0], 'user_id'=>$uploaded_photo->user_id, 'is_admin'=>$uploaded_photo->admin_addition, 'file_name'=>$uploaded_photo->img_name, 'file_id'=>$uploaded_photo->id, 'given_file_ext'=>$uploaded_photo->highlight, 'file_private'=>$uploaded_photo->private]); 
              echo $file_view_rendered['main_view']; 
            ?>
              <div class="photo__container--attach text-left" id="photoAttachPlayer-<?php echo $uploaded_photo->id; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>" style="max-height:800px; overflow-y:scroll;">
                <div class="grid-x align-spaced">
                  <div class="grid-y photo__container--attach-left-col">
                    <button class="button--back" id="back_btn-<?php echo $uploaded_photo->id; ?>">&lt; Back</button>
                    <div class="photo__img-container--attach">
                      <?php echo $file_view_rendered['model_view_class']; ?>
                    </div>
                    <?php if(!empty($uploaded_photo->player_id)): ?>
                    <div class="assigned_pl_box">
                      <?php if(!empty($pl_lists['pl_id'])){ echo '<p class="photo__heading--attach" style="color: black;">Player(s) in Photo:</p>'; } ?>
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
                  <div class="relative photo__container--attach-rt-col">
                    <p style="color: black;">You can attach a player's name to the photo here. Search the player's name and select them to add them to the photo.</p>
                    <?php //echo g365_ls_claim_player(['auth_user'=>$current_user->ID, 'form_type'=>'photo_player_claim'], 'player-registration'); ?>
                    <?php echo g365_ls_claim_player(['auth_user'=>$current_user->ID, 'form_type'=>'photo_player_claim'], 'photo-player-claim'); ?>
                  </div>
                </div>
                <div class="grid-x align-right small-margin-top">
                  <p class="ph-assign-succ"></p>
                  <div class="link_view_full_list">
                    <section class="pretty-buttons small-padding-top">
                      <div class="grid-x pretty-container">
                        <div class="small-12 large-12 small-padding-bottom">
                          <a class="pretty-btn pretty-btn-1 user_assigned_ph" data-img-name="<?php echo $uploaded_photo->img_name; ?>" data-img-id="<?php echo $uploaded_photo->id; ?>">Assign Player(s) to <?php echo $media_type['label']; ?>
                            <svg><rect x="0" y="0" fill="none" width="100%" height="100%"></rect></svg>
                          </a>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
            </div>
            <?php $i++; endforeach; else: echo ('<h5 class="large-12 text-center">'.g365_message()['photo_upload'].'<h5>'); endif; /*main-if-and-foreach*/ ?>
            </div>
            <div class="grid-x grid-margin-x small-up-2 medium-up-4 text-center profile-feature profile-widget profile-photos small-padding" style="display: flex;width: 100%;" id="photoAdminLibrary">
              <ul class="accordion club-rosters small-padding-bottom small-12 medium-12 large-12" data-accordion data-allow-all-closed="true">
              <h5 class="text-underline large-12 small-padding-bottom"><?php echo g365_message(['media_type'=>$media_type['alias']])['admin_user_photo']; ?></h5>
              <?php
                if(!empty($claimed_pl)) {
                foreach($claimed_pl['pl_ed'] as $claimed_data):
                $player_name = g365_get_pl_data(['pl_id'=>$claimed_data], 'g365-pl-photo')[0]->name;
                $admin_user_data = g365_img_queries('user-acct-ph-view', ['user_upload_status'=>'admin-user-view', 'pl_id'=>$claimed_data, 'file_type'=>$media_type['alias']]);
                $admin_pl_lists = json_decode($admin_user_data[0]->player_id); $admin_pl_lists = json_decode(json_encode($admin_pl_lists), true);
//                 echo '<pre>'; print_r($admin_user_data); echo '</pre>';
                if(!empty($admin_user_data)): // if-foreach-admin-photo ?>
                <li class="accordion-item" data-accordion-item>
                  <!-- Accordion tab title -->
                  <a href="#" style="background-color:green; padding:6px; font-size:18px" class="accordion-title"><?php echo $player_name; ?></a>
                  <div class="accordion-content" data-tab-content>
                    <?php foreach($admin_user_data as $admin_user_photo): 
                      $user_admin_view_rendered = g365_media_view_rendering('user-admin-view', ['auth_user'=>$user_roles[0], 'user_id'=>$admin_user_photo->user_id, 'is_admin'=>$admin_user_photo->admin_addition, 'file_name'=>$admin_user_photo->img_name, 'file_id'=>$admin_user_photo->id, 'given_file_ext'=>$admin_user_photo->highlight, 'file_private'=>$admin_user_photo->private, 'media_type'=>$media_type['alias']]); echo $user_admin_view_rendered['main_view']; 
                      echo ph_toggle(['toggle_id'=>'tog_'.$claimed_data, 'is_checked'=>$admin_user_photo->private, 'img_id'=>$admin_user_photo->id, 'user_admin_toggle'=>true], 'user-approved'); 
                    endforeach; ?>
                </li>
              <?php endif; endforeach; // end-if-foreach-admin-photo 
                } ?>
              </ul>
            </div>
          </div>
        </div>
      </li>
      <li class="accordion-item" data-accordion-item>
        <!-- Accordion tab title -->
        <a href="#" class="accordion-title">Pending <?php echo $media_type['label']; ?>s</a>
        <div class="accordion-content" data-tab-content>
          <!-- Pending -->
          <div class="pending_ph_box">
            <div class="grid-x grid-margin-x small-up-2 medium-up-4 text-center profile-feature profile-widget profile-photos small-padding" style="display: flex;width: 100%;">
            <?php $i = 0; $uploaded_photos = g365_img_queries('user-acct-ph-view', ['user_upload_status'=>'pending', 'user_id'=>$current_user->ID]); if(!empty($uploaded_photos)): foreach($uploaded_photos as $uploaded_photo): /*main-foreach*/ $pl_lists = json_decode($uploaded_photo->player_id); $pl_lists = json_decode(json_encode($pl_lists), true); $file_view_rendered = g365_media_view_rendering($media_type['alias'].'-only', ['auth_user'=>$user_roles[0], 'user_id'=>$uploaded_photo->user_id, 'is_admin'=>$uploaded_photo->admin_addition, 'file_name'=>$uploaded_photo->img_name, 'file_id'=>$uploaded_photo->id, 'given_file_ext'=>$uploaded_photo->highlight, 'file_private'=>$uploaded_photo->private]); echo $file_view_rendered['media_only']; ?>
            <?php $i++; endforeach; else: echo ('<h5 class="large-12 text-center">'.g365_message()['photo_upload'].'<h5>'); endif; /*main-if-and-foreach*/ echo ajax_data_xfer(['class_name'=>'user_assigned_ph'], 'user-assinged-ph'); echo ajax_data_xfer(['class_name'=>'user_ph_toggle'], 'user-photo-status'); echo g365_custom_js('filepond'); ?>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>