<?php 
$post_type = $_POST['post_type'];
  $pl_id = $_POST['pl_id']; $pl_name = $_POST['pl_name']; $pl_note = $_POST['pl_note']; $rec_id = $_POST['rec_id']; $pl_img = $_POST['pl_img']; $pl_nickname = $_POST['pl_nickname']; $pl_grad_year = $_POST['pl_grad_year']; $pl_position = $_POST['pl_position']; $pl_height = $_POST['pl_height']; $pl_gpa = $_POST['pl_gpa']; $pl_sat = $_POST['pl_sat']; $pl_contact_info = $_POST['pl_contact_info'];
  switch($post_type){
    case 'add_fav' : g365_data_xfer(['db_tb'=>'favorites', 'insert_field_val'=>fav_insert(['pl_id'=>$pl_id, 'pl_note'=>$pl_note, 'pl_name'=>$pl_name, 'pl_nickname'=>$pl_nickname, 'pl_img'=>$pl_img, 'pl_grad_year'=>$pl_grad_year, 'pl_position'=>$pl_position, 'pl_height'=>$pl_height, 'pl_gpa'=>$pl_gpa, 'pl_sat'=>$pl_sat, 'pl_contact_info'=>$pl_contact_info], 'fav_insert')], 'INSERT');
      break;
    case 'remove_fav': g365_data_xfer(['db_tb'=>'favorites', 'rec_id'=>$rec_id], 'DELETE');
      break;
    case 'ls_pl':
       $pl_id = $_POST['pl_id']; $fav_data = g365_data_xfer(['db_tb'=>'favorites', 'qn_type'=>1, 'player_id'=>$pl_id, 'user_id'=>get_current_user_id()], 'SELECT'); echo fav_reveal(['data_toggle'=>'pl_'.$pl_id, 'data_note'=>'note_'.$pl_id, 'fav_data'=>$fav_data, 'pl_id'=>$pl_id, 'pl_img'=>$pl_img], 'add_fav');
      break;
  }
?>