<div class="medium-padding main_fav">
  <h3>Recruits' List</h3>
  <?php $player_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'user_id'=>get_current_user_id()], 'SELECT'); if(!empty($player_data)): foreach($player_data as $pl_data): $pl_id = $pl_data['player_id']; $pl_note = json_decode($pl_data['notes'], true); $pl_data_fields = json_decode($pl_data['pl_data'], true); $rec_id = $pl_data['id']; $pl_info = g365_get_pl_data(['pl_id'=>$pl_id]); $ev_recruit = url_param('type'); if($ev_recruit == 'my-recruits'){ $url = true; }else{ $url = false; } ?>
    <div class="grid-x home_fav_box" id="<?php echo $rec_id ?>">
      <div class="small-6 medium-6 large-1 rm_fav small-margin-bottom" data-toggle="rm_<?php echo $rec_id ?>" data-rm-id="<?php echo $rec_id ?>" >
        <a class="rm_btn" href="#" role="button">
          <span>remove</span>
          <div class="rm_icon">
            <i class="rm_x fa fa-remove">X</i>
            <i class="rm_x fa fa-check">X</i>
          </div>
        </a>
      </div>
      <div class="small-12 medium-6 large-4 flex text-center">
        <div class="cell" data-alphabet="A">
          <a class="emphasis" href="<?php echo get_site_url(); ?>/player/<?php echo $pl_data_fields['pl_nickname']; ?>" target="_blank">
              <img class="watchlist__player-img small-margin-bottom" loading="lazy" data-src="<?php echo $pl_data_fields['img_link']; ?>" alt="Player headshot for <?php echo $pl_data_fields['pl_name']; ?>" src="<?php echo $pl_data_fields['img_link']; ?>"><br>
              <p><?php echo $pl_data_fields['pl_name']; ?></p>
          </a>
        </div>
      </div>
      <div class="info-fav small-12 medium-12 large-6">
        <?php echo cdp_fav_pl_info(['pl_school'=>$pl_info[0]->school,'grad_year'=>$pl_info[0]->grad_year,'position'=>g365_get_pl_data(['pst_id'=>$pl_info[0]->position], 'position')[0]->abbr,'height'=>empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in),'gpa'=>$pl_info[0]->gpa,'sat'=>$pl_info[0]->sat,'act'=>$pl_info[0]->act,'contact_info'=>empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : ($pl_info[0]->city.", ".$pl_info[0]->state."<br/>".$pl_info[0]->email."<br/>".$pl_info[0]->phone)], 'pl_fav'); ?>
      </div>
<!--       <div class="small-12 medium-12 large-3">
        <div class="small-12 medium-12 large-12"><a href="">Link to Passport</a></div>
      </div> -->
      <div class="small-12 medium-12 large-12 fav_note" data-toggle="pl_<?php echo $pl_id; ?>"><h5>Notes: <?php echo $pl_note['notes']; ?></h5>
        <i class="fi-pencil float-right" data-pl-id="<?php echo $pl_id?>"></i>
      </div>
    </div>
  <?php $position_abbr = g365_get_pl_data(['pst_id'=>$pl_info[0]->position], 'position'); echo fav_reveal(['data_toggle'=>'pl_'.$pl_id, 'full_name'=>$pl_info[0]->name, 'pl_nickname'=>$pl_info[0]->nickname, 'data_note'=>'note_'.$pl_id, 'fav_data'=>g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'player_id'=>$pl_id, 'user_id'=>get_current_user_id()], 'SELECT'), 'pl_id'=>$pl_id, 'pl_img'=>$pl_data_fields['img_link'], 'pl_grad_year'=>(empty($pl_info[0]->grad_year) ? "" : $pl_info[0]->grad_year), 'school'=>(empty($pl_info[0]->school) ? "" : $pl_info[0]->school), 'school'=>(empty($pl_info[0]->school) ? "" : $pl_info[0]->school), 'pl_position'=>(empty($pl_info[0]->position) ? "" : $position_abbr[0]->abbr), 'pl_height'=>(empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in)), 'gpa'=>(empty($pl_info[0]->gpa) ? "" : $pl_info[0]->gpa), 'sat'=>(empty($pl_info[0]->sat) ? "" : $pl_info[0]->sat), 'act'=>(empty($pl_info[0]->act) ? "" : $pl_info[0]->act), 'pl_contact_info'=>(empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : (empty($pl_info[0]->city) ? '-' : $pl_info[0]->city).', '.(empty($pl_info[0]->state) ? '-' : $pl_info[0]->state).'<br/>'.($pl_info[0]->email."<br/>".$pl_info[0]->phone))], 'edit_note'); echo fav_reveal(['rec_id'=>$rec_id, 'data_toggle'=>'rm_'.$rec_id, 'full_name'=>$pl_info[0]->name, 'pl_id'=>(empty($rec_id) ? "" : $rec_id), 'pl_img'=>(empty($pl_data_fields['img_link']) ? "" : $pl_data_fields['img_link']), 'pl_grad_year'=>(empty($pl_info[0]->grad_year) ? "" : $pl_info[0]->grad_year), 'school'=>(empty($pl_info[0]->school) ? "" : $pl_info[0]->school), 'pl_position'=>(empty($pl_info[0]->position) ? "" : $position_abbr[0]->abbr), 'pl_height'=>(empty($pl_info[0]->height_ft) ? "" : ($pl_info[0]->height_ft."' ".$pl_info[0]->height_in)), 'gpa'=>(empty($pl_info[0]->gpa) ? "" : $pl_info[0]->gpa), 'sat'=>(empty($pl_info[0]->sat) ? "" : $pl_info[0]->sat), 'act'=>(empty($pl_info[0]->act) ? "" : $pl_info[0]->act), 'pl_contact_info'=>(empty($pl_info[0]->email && $pl_info[0]->phone) ? "" : (empty($pl_info[0]->city) ? '-' : $pl_info[0]->city).', '.(empty($pl_info[0]->state) ? '-' : $pl_info[0]->state).'<br/>'.($pl_info[0]->email."<br/>".$pl_info[0]->phone))], 'remove_fav'); endforeach; else: echo ("<p>No player is added to the favorite list</p>"); endif; echo ajax_data_xfer(['class_name'=>'rm_pl', 'url'=>$url], 'remove_fav'); echo ajax_data_xfer(['class_name'=>'edit_note', 'url'=>$url], 'add_fav'); echo dcp_custom_js(['delay'=>500], 'dcp-rm'); ?>
</div>