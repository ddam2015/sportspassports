function pass_pl_id(pointer){
  var pl_id = pointer.dataset.plId;
  var pl_name = pointer.dataset.plName;
  var rm_e = '<div onclick="remove_assign_pl(this)" class="small-6 medium-6 large-1 rm_fav small-margin-bottom large-margin-right" data-toggle="rm_'+pl_id+'" data-rm-id="'+pl_id+'"><a class="rm_btn" href="#" role="button"><span>remove</span><div class="rm_icon"><i class="rm_x fa fa-remove">X</i><i class="rm_x fa fa-check">X</i></div></a></div>';
  $('.photo__container--attach').each(function(){
    if($(this).css('display') == 'block'){
      var target_id = $(this).attr('id');
      var img_id = $(this).attr('data-img-id');
      if($('#'+target_id+' #li-'+pl_id).length == 0){ // If no duplicate id, add id record to the list
        $('#'+target_id+' .photo__player-list--attach').append('<div class="flex item-center" id="li-'+pl_id+'">'+rm_e+'<li id='+pl_id+'>'+pl_name+'</li></div>');
        var to_save_id_list = document.getElementById('pl_list_string-'+img_id).value;
        var new_arr_val = to_save_id_list.length ? to_save_id_list.split(',') : [];
        new_arr_val.push(' '+pl_id);
        document.getElementById('pl_list_string-'+img_id).value = new_arr_val;
      }else{
        alert(''+pl_name+' is already in assigned list.');
      }
    }
  });
}
document.addEventListener("DOMContentLoaded", function(){
  if(document.getElementById('photoAdminLibrary')){
    var photos = $('#photoAdminLibrary .photo__img-container');
    photos.click(function(e){
      var container_id = e.currentTarget.id;
      var photoSelected = $('.photo__img--attach');
      var photoModal = $('.photo__container--attach#'+container_id);
      var targetSrc = e.currentTarget.children[0].src; // img src
      var modal;
      photoSelected.attr('src', targetSrc);
      photoModal.show();
      function removeModal(){ modal.remove(); }
      if($('body').hasClass('woocommerce-account')) {
          modal = $('<div>').css({
            background: 'RGBA(0, 0, 0, 0.8)',
            width: '100%',
            height: '100%',
            position: 'fixed',
            zIndex: '10000',
            top: '0',
            left: '0',
            cursor: 'zoom-out'
          }).addClass('modalAttach')
          .click(function() {
            removeModal();
            photoModal.hide();
          }).appendTo('#content');
        } else if($('body').hasClass('wp-admin')) {
          modal = $('<div>').css({
            background: 'RGBA(0, 0, 0, 0.8)',
            width: '100%',
            height: '100%',
            position: 'fixed',
            zIndex: '10000',
            top: '0',
            left: '0',
            cursor: 'zoom-out'
          }).addClass('modalAttach')
          .click(function() {
            removeModal();
            photoModal.hide();
          }).appendTo('body');
        }
      
      });
    //back button
    $('.button--back').on('click', function(){
      var photo_model = $('.photo__container--attach');
      photo_model.hide();
      $('.modalAttach').remove();
    })
    //Attach players
  }
});
function rm_arr_ele(arr, value){
  return arr.filter(function(e){ 
    return e != value; 
  });
}
// Remove player from photo assign list
function remove_assign_pl(pointer){
  var rm_id = pointer.dataset.rmId;
  $('.photo__container--attach').each(function(){
    var remove_id = pointer.dataset.rmId;
    if($(this).css('display') == 'block'){
      var target_id = $(this).attr('id');
      var img_id = $(this).attr('data-img-id');
      var to_save_id_list = document.getElementById('pl_list_string-'+img_id).value;
      var new_arr_val = to_save_id_list.split(',');
      var filter_arr_val = rm_arr_ele(new_arr_val, rm_id);
      document.getElementById('pl_list_string-'+img_id).value = filter_arr_val;
      $('#'+target_id+' #li-'+remove_id).remove();
    }
  });
}
jQuery(document).ready(function($){
  $('.filepond').on('click', function(){
    $('.finish-upload').show('slow');
  });
// First check on page load if player's photos/videos are private or public
//   var boxes = document.querySelectorAll('.user-approved-media .user_ph_toggle');
//   boxes.forEach((box, index) => { if($('#'+box.id).is(':checked')){ $('#'+box.id).parent().show(); }else{ $('#'+box.id).parent().hide(); } });
});
// Select players in photo to be published
$('.user_ph_toggle').on('click', function(){
  var img_id = this.dataset.imgId;
  var checkbox_id = $(this).attr('id');
  var get_ch_id = document.getElementById(checkbox_id); if(get_ch_id.checked){ var is_checked = 1; }else{ is_checked = 0; }
  // Check if 1 video is already set as profile video. If it's true hide all other toggle buttons
//   if(get_ch_id.checked){ var boxes = document.querySelectorAll('.user-approved-media .user_ph_toggle:not(#'+checkbox_id+')'); console.log(boxes); boxes.forEach((box, index) => {
//   $('#'+box.id).parent().hide(); }); }else{ var show_boxes = document.querySelectorAll('.user-approved-media .user_ph_toggle:not(#'+checkbox_id+')'); show_boxes.forEach((box, index) => {
//   $('#'+box.id).parent().show(); }); }
  // Players in the approved photo
  var number_of_players = $('#photoAttachPlayer-'+img_id+' .photo__player-list--attach li').length;
  var img_link = $('#photoAttachPlayer-'+img_id+' img');
  if(number_of_players > 1 && is_checked == 1){
    if(document.getElementById('photoAdminLibrary')){
      var photos = $('#photoAdminLibrary .photo__img-container');
        var photoSelected = $('.photo__img--attach');
        var photoModal = $('.photo__container--attach#photoAttachPlayer-'+img_id);
        var targetSrc = img_link[0].src;
        var modal;
        photoSelected.attr('src', targetSrc);
        photoModal.show();
        function removeModal(){ modal.remove(); }
        modal = $('<div>').css({
          background: 'RGBA(0, 0, 0, 0.8)',
          width: '100%',
          height: '100%',
          position: 'fixed',
          zIndex: '10000',
          top: '0',
          left: '0',
          cursor: 'zoom-out'
        }).addClass('modalAttach')
        .click(function() {
          removeModal();
          photoModal.hide();
        }).appendTo('body');
      //back button
      $('.button--back').on('click', function(){
        var photo_model = $('.photo__container--attach');
        photo_model.hide();
        $('.modalAttach').remove();
      })
    }
  }
});