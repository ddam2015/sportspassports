/*** Admin Badges ***/
// Auto set up badge order number
var tables = document.getElementsByTagName('table');
var table = tables[tables.length -1];
var rows = table.rows;
for(var i = 1, td; i < rows.length; i++){
  td = document.createElement('td');
//   td.setAttribute('style', 'vertical-align:middle;font-size: 100px;background: #3adb76;color: #fff;border: 5px solid #d4d4d4; text-align:center; position:relative; min-width:105px;');
  td.setAttribute('class', 'table_order');
  td.appendChild(document.createTextNode(i));
  // Create delete btn
//   var bdg_delete_btn = document.createElement("button");
//   bdg_delete_btn.innerHTML = "Delete";
//   bdg_delete_btn.setAttribute('class', 'button alert');
//   bdg_delete_btn.setAttribute('onclick', 'delete_badge(this)');
//   bdg_delete_btn.setAttribute('id', 'rm-badge-'+i);
//   bdg_delete_btn.setAttribute('style', 'position:absolute; bottom:-14px; right:12px; color:#fff;');
//   td.appendChild(bdg_delete_btn);
  rows[i].insertBefore(td, rows[i].firstChild);
}
// End auto set up badge order number

function delete_badge(pointer){
  var row_to_delete_id = pointer.closest('tr').id;
  $('#'+row_to_delete_id).css('border-left', '5px solid red');
  $('#'+row_to_delete_id).attr('class', 'bdg_rows is_mod is_deleted');
}
function add_new_badge_row(){
  var last_row = $('#bd_op_files').closest('table').find('tr:last-child');
  var cloned = last_row.clone();
  cloned.find('textarea').val('');
  cloned.find('textarea').empty();
  cloned.find('input[type="text"]').val('');
  cloned.find('input[type="checkbox"]:checked').each(function(index){ cloned.find('input[type="checkbox"]:checked')[0].checked = false; });
  cloned.find('select').val('N/A');
  var new_row = cloned[0].id;
  var reg_match = /^(.+)(\d+)$/; 
  var get_tr_id = new_row.match(reg_match);
  var update_tr_id = get_tr_id[1] + (parseInt(get_tr_id[2], 10) + 1);
  cloned.attr('id', update_tr_id);
  cloned.attr('class', 'bdg_rows is_mod');
  cloned.find('img')[0].src = ''; // Clear default image
  cloned.find('img')[0].id = update_tr_id+'-logo'; // Reset image id
  cloned.find('td')[0].innerHTML++;
//     cloned.find('td')[0].removeClass('is_mod');
//     console.log(cloned.find('form').attr('id', 'search_player_profiles_undefined_1'));
  cloned.insertAfter(last_row);
}
// If badge data is changed, notify modified row
document.addEventListener('input', function(ev){
  if(ev.path[3].id !== ''){
    var indi_ev = $('#'+ev.path[3].id+' #event_indi_ev')[0].value;  
  // Check if individual event option is used
    if(indi_ev !== 'N/A'){
      $('#'+ev.path[3].id+' .season_options').addClass('disable_field');
      $('#'+ev.path[3].id+' #season_ops')[0].value = 'N/A';
      $('#'+ev.path[3].id+' .season_options input')[0].value = '';
      $('#'+ev.path[3].id+' .season_options input')[1].value = '';
      $('#'+ev.path[3].id+' .event_options')[0].childNodes[0].classList.add('disable_field');
      $('#'+ev.path[3].id+' .event_options')[0].childNodes[1].classList.add('disable_field');
      $('#'+ev.path[3].id+' #event_ops')[0].value = 'N/A';
      $('#'+ev.path[3].id+' .event_options input')[0].value = '';
      $('#'+ev.path[3].id+' .trophy_options').addClass('disable_field');
    }else{
      $('#'+ev.path[3].id+' .season_options').removeClass('disable_field');
      $('#'+ev.path[3].id+' .event_options')[0].childNodes[0].classList.remove('disable_field');
      $('#'+ev.path[3].id+' .event_options')[0].childNodes[1].classList.remove('disable_field');
      $('#'+ev.path[3].id+' .trophy_options').removeClass('disable_field');
    }
  }
  var changed_tr_id_checkbox = ev.path[2].id;
  var changed_tr_id_input = ev.path[3].id;
  var changed_tr_id_textarea = ev.path[4].id;
  // Update badge logo base on selected badge
  if(ev.path[2].id.length !== 0){
    var array_length = $('#'+ev.path[2].id+' .bdg_name').length;
    var get_first_array_element = array_length - 1;
    var selected_url = $('#'+ev.path[2].id+' .bdg_name')[get_first_array_element].options[$('#'+ev.path[2].id+' .bdg_name')[get_first_array_element].selectedIndex].getAttribute('bdg_url');
    document.getElementById(ev.path[2].id+'-logo').src = selected_url;
  }
  if(changed_tr_id_input.length !== 0){
   $('#bd_op_files #'+changed_tr_id_input).addClass('is_mod');
  }
  if(changed_tr_id_checkbox.length !== 0){
    $('#bd_op_files #'+changed_tr_id_checkbox).addClass('is_mod');      
  }
  if(changed_tr_id_textarea.length !== 0){
    $('#bd_op_files #'+changed_tr_id_textarea).addClass('is_mod');
  }
}, false);
function save_badge_rows(){
  var row_to_save = document.getElementsByClassName('is_mod');
  var saved_index = 0;
  for(var i=0; i < row_to_save.length; i++){
    var row_to_delete = document.getElementsByClassName('is_deleted');
    if(row_to_delete[i]){ var enabled = '0'; }else{ enabled = '1'; }
//       var note_data = document.getElementById($('#'+row_to_save[i].id).find('textarea')[0].id).value;
    var note_data = $('#'+row_to_save[i].id).find('textarea')[0].value;
    var website_data = $('#'+row_to_save[i].id).find('input[type="checkbox"]:checked');
    var website_id = [];
    website_data.each(function(index){ website_id.push(website_data[index].value); });
    var stat_type_array = []; var stat_op_array = []; var stat_value_array = []; 
    var badge_cat_op_array = []; var badge_cat_op_val_array = []; var badge_year_val_array = []; var badge_individual_ev_array = [];
    var stat_types = ['pts', 'reb', 'ast', 'stl', 'blk', 'three_pt'];
    var badge_catagorie = ['season', 'event', 'game', 'trophy'];
    var badge_gr_cat_op_array = {}; var badge_gr_cat_op_val_array = {}; var badge_gr_stat_type_array = {}; var badge_gr_stat_op_array = {}; var badge_gr_stat_op_val_array = {}; var badge_individual_ev_json = {};
    var badge_id = row_to_save[i].id.replace('badge-', '');
    for(var k = 0; k < badge_catagorie.length; k++){
      var badge_catagory_op = $('#'+row_to_save[i].id).find('select[id="'+badge_catagorie[k]+'_ops"]')[0].value;
      var badge_catagory_op_val = $('#'+row_to_save[i].id).find('input[id="bdg_'+badge_catagorie[k]+'_op_val"]')[0].value;
      badge_cat_op_array.push(badge_catagory_op); badge_cat_op_val_array.push(badge_catagory_op_val);
    }
    for(var h = 0; h < badge_catagorie.length && h < badge_cat_op_array.length; h++){
      badge_gr_cat_op_array[badge_catagorie[h]] = badge_cat_op_array[h];
    }
    for(var g = 0; g < badge_catagorie.length && g < badge_cat_op_val_array.length; g++){
      badge_gr_cat_op_val_array[badge_catagorie[g]] = badge_cat_op_val_array[g];
    }
    var badge_catagory_indi_ev = $('#'+row_to_save[i].id).find('select[id="event_indi_ev"]')[0].value;
    badge_individual_ev_array.push(badge_catagory_indi_ev);
    // Cumulative for event only
    badge_individual_ev_json['event'] = badge_individual_ev_array[0];
    for(var j = 0; j < stat_types.length; j++){
      var stat_type = $('#'+row_to_save[i].id).find('select[id="'+stat_types[j]+'_type"]')[0].value;
      var stat_op = $('#'+row_to_save[i].id).find('select[id="'+stat_types[j]+'_ops"]')[0].value;
      var stat_op_val = $('#'+row_to_save[i].id).find('input[id="'+stat_types[j]+'_value"]')[0].value;
      stat_type_array.push(stat_type); stat_op_array.push(stat_op); stat_value_array.push(stat_op_val);  
    }
    for(var l = 0; l < stat_types.length && l < stat_type_array.length; l++){
      badge_gr_stat_type_array[stat_types[l]] = stat_type_array[l];
    }
    for(var m = 0; m < stat_types.length && m < stat_op_array.length; m++){
      badge_gr_stat_op_array[stat_types[m]] = stat_op_array[m];
    }
    for(var n = 0; n < stat_types.length && n < stat_value_array.length; n++){
      badge_gr_stat_op_val_array[stat_types[n]] = stat_value_array[n];
    }
    var season_year_val = $('#'+row_to_save[i].id).find('input[id="bdg_season_val"]')[0].value;
    var badge_name = $('#'+row_to_save[i].id).find('select[class="bdg_name"]')[0].value;
    var selected_bdg_name = $('#'+row_to_save[i].id).find('select[class="bdg_name"]')[0];
    var badge_url = selected_bdg_name.options[selected_bdg_name.selectedIndex].getAttribute('bdg_url');
    var badge_type = selected_bdg_name.options[selected_bdg_name.selectedIndex].getAttribute('bdg_type');
    $.ajax({
     url: '../../wp-admin/ajax-caller.php',
     data: { post_type: "admin-badge", badge_id: badge_id, enabled: enabled, note_row: note_data, website_id: website_id, badge_cat_op_array: badge_gr_cat_op_array, badge_gr_cat_op_val_array: badge_gr_cat_op_val_array, badge_individual_ev_json: badge_individual_ev_json, season_year_val: season_year_val, stat_type_array: badge_gr_stat_type_array, badge_gr_stat_op_array: badge_gr_stat_op_array, badge_gr_stat_op_val_array: badge_gr_stat_op_val_array, badge_name: badge_name, badge_url: badge_url, badge_type: badge_catagory_indi_ev, badge_range: badge_type },
     type: "POST",
      success: function(data){
        saved_index++;
//         $.ajax({
//           url: '../../wp-admin/ajax-caller.php',
//           data: { post_type: "admin-update-player-badge" },
//           type: "POST",
//           success: function(data){
//             console.log("Successfully updated all players' badges");
//           }
//         });
        if(saved_index == i){
          if(!alert("Changes are saved")){
            window.location.reload();
          }
        }
      }
    });
  }
}
// Admin Assign Player Badge
// function save_player_badges(){
//   var tb_data_to_save = document.getElementsByClassName('is_mod');
//   var saved_index = 0; var badge_id_list = []; var badge_note_list = []; var badge_name_list = []; var badge_url_list = []; var badge_player_id = []; var badge_enabled_list = []; var badge_type_list = []; var badge_range_list = [];
//   var pl_id = $('#pl_id_to_save').val();
//   for(var i=0; i < tb_data_to_save.length; i++){
//     var row_to_delete = document.getElementsByClassName('is_deleted');
//     if(row_to_delete[i]){ var enabled = '0'; }else{ enabled = '1'; }
//     var row_data = tb_data_to_save[i].id;
//     var badge_id = row_data.replace('badge-','');
//     badge_id = badge_id.replace(pl_id+'-','');
//     var note_data = $('#'+tb_data_to_save[i].id).find('textarea')[0].value;
//     var badge_name = $('#'+tb_data_to_save[i].id).find('select[class="bdg_name"]')[0].value;
//     var selected_bdg_name = $('#'+tb_data_to_save[i].id).find('select[class="bdg_name"]')[0];
//     var badge_catagory_indi_ev = $('#'+tb_data_to_save[i].id).find('select[id="event_indi_ev"]')[0].value;
//     var badge_url = selected_bdg_name.options[selected_bdg_name.selectedIndex].getAttribute('bdg_url');
//     var badge_range = selected_bdg_name.options[selected_bdg_name.selectedIndex].getAttribute('bdg_type');
//     var admin_addition = 1;
//     badge_id_list[i] = pl_id+'-'+badge_id;
//     badge_note_list[i] = note_data;
//     badge_name_list[i] = badge_name;
//     badge_url_list[i] = badge_url;
//     badge_type_list[i] = badge_catagory_indi_ev;
//     badge_range_list[i] = badge_range;
//     badge_player_id[i] = pl_id;
//     badge_enabled_list[i] = enabled;
//   }
// //   console.log(badge_range_list);
//   $.ajax({
//     url: '../../wp-admin/ajax-caller.php',
//     data: { post_type: "admin-player-badge", badge_id_list: badge_id_list, badge_note_list: badge_note_list, badge_name_list: badge_name_list, badge_url_list: badge_url_list, badge_type_list: badge_type_list, badge_range_list: badge_range_list, badge_player_id: badge_player_id, badge_enabled_list: badge_enabled_list },
//     type: "POST",
//     success: function(data){
//       if(!alert("Changes are saved")){
//         window.location.reload(); 
//       }
//     }
//   });
// }
function global_badge_update(){
  alert('Players\' badges are updating. Check console log for the update status.');
  var number_of_badges = document.getElementsByClassName('bdg_rows').length;
  var counter = 1;
  $('#update-badge-'+counter).trigger('click'); // Initial update of row 1
  console.log('Updating badge id 1');
  const update_bdg_per_row = setInterval(() => {
     counter = counter + 1
     $('#update-badge-'+counter).trigger('click');
     console.log('Updating badge id '+counter);
    if(counter == number_of_badges){ stop_update_bdg(); }
  }, ( 2 * 60000) ); // 2 minutes
  function stop_update_bdg(){
    clearInterval(update_bdg_per_row);
  }
}
function sigle_save_badge(pointer){
  var row_to_update = pointer.id;
  var badge_id = row_to_update.replace('update-badge-', '');
  var badge_name = document.getElementById('badge-'+ badge_id +'-logo').alt;
//   alert('Players\' badge "'+ badge_name +'" is updating. Check console log for the update status.');
//   console.log('Updating badge id ' + badge_id);
  $.ajax({
    url: '../../wp-admin/ajax-caller.php',
    data: { post_type: 'admin-update-player-badge', badge_ids: badge_id },
    type: 'POST',
    success: function(data){
      console.log('Successfully updated badge id '+ badge_id +': "' + badge_name +' "');
    }
  });
}
/*** End Badges ***/