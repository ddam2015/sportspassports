$('#g365_data_manager_admin').foundation();

var the_order_input = $('#g365_order_data');
var the_post = $('#post');
if( the_order_input && the_post ) {
  the_post.on( 'submit', function(e) {
    //prevent default submit while we see if everything is ok
    e.preventDefault();
    //if we need to update the field, do so
    if( the_order_input.val() !== the_order_input.attr('data-g365_og_val') ) {
      //set the send status to false;
      if( the_post.data('form_fired') === true ) the_post.data('form_fired', false);
      //try to submit the custom g356 form
      var g365_forms = $('#g365_form_wrap .primary-form');
      g365_forms.each(function(){
        //reference to the form element
        var current_set = $(this);
        //set the listener for the submission completion
        $('#' + current_set.attr('id') + '_message', current_set).on( 'result_complete', function(e, result_info) {
          //if there are no errors, add the result data and finish the form
          if( result_info.error_status === false ){
            //add it to the order_data object and try to submit the whole form again
            the_post.data(current_set.attr('data-g365_type') + '_g365', result_info.result_ids.join(','));
            //if we have the right amount of fieldsets results, add the data to the order_data field and submit the form, but screen out the keys that aren't ours
            var woo_form_data_keys = Object.keys(the_post.data()).filter(function(val){ return val.substr(val.length - 5) === '_g365'; });
            //if all our forms are handled, make the value for the order_data field, then finish submitting
            if( woo_form_data_keys.length === g365_forms.length && the_post.data('form_fired') === false ) {
              the_post.data('form_fired', true);
              var woo_order_data = [];
              $.each( woo_form_data_keys, function(dex, val){
                var val_name = val.substr(0, val.length-5);
                $.each( g365_form_details.items, function(cat_dex, cat_vals) {
                  if( cat_vals.type == val_name ) {
                    inc_status = false;
                    return false;
                  }
                });
                if( inc_status ) return;
                woo_order_data[woo_order_data.length] = val_name + ',' + the_post.data(val);
              });
              the_order_input.attr('data-g365_og_val', woo_order_data.join('|')).val(woo_order_data.join('|'));
              the_post.submit();
            }
          }
        });
        current_set.submit();
      });
      return false;
    } else {
      console.log('All quiet on the western front...');
      return true;
    }
  });
}

//verification change ajax for certification at tournament
$('.g365_admin_verification_toggle_tournament input[type=radio]', '#g365_data_manager_admin').on('change', function(){
  var pl_id = $(this).siblings('.pl_id').val();
  var pl_form = $(this).parent();
  var pl_form_row = pl_form.closest('tr.tournament-players');
  var team_row = pl_form_row.closest('tr.team-row');
  var team_players = pl_form_row.siblings('.tournament-players');
  var data_set = {
    'player_names_admin' : {
      proc_type : 'proc_data',
      ids : pl_id,
      form_data : pl_form.serialize(),
      //if there is an admin key for working with the full data set, add it
      admin_key : ( typeof window.g365_sess_data.admin_key !== 'undefined' ) ? window.g365_sess_data.admin_key : null
    }
  };
  window.g365_serv_con( data_set )
  .done( function(response) {
    if( response.status === 'success' ) {
      if( response.message.player_names_admin[pl_id].verified === 2 ) {
        pl_form_row.addClass('verified-player').removeClass('unverified-player');
        if( !team_players.hasClass('unverified-player') ) team_row.addClass('verified-team').removeClass('unverified-team');
      } else {
        pl_form_row.addClass('unverified-player').removeClass('verified-player');
        team_row.addClass('unverified-team').removeClass('verified-team');
      }
    }
  });
});
//attendance management in the tournament manager
$('.g365_admin_attendance_toggle_tournament input[type=checkbox]', '#g365_data_manager_admin').on('change', function(){
  var ros_id = $(this).siblings('.ros_id').val();
  var ros_form = $(this).parent();
  var data_set = {
    'attendance' : {
      proc_type : 'proc_data',
      ids : ros_id,
      form_data : ros_form.serialize(),
      //if there is an admin key for working with the full data set, add it
      admin_key : ( typeof window.g365_sess_data.admin_key !== 'undefined' ) ? window.g365_sess_data.admin_key : null
    }
  };
  window.g365_serv_con( data_set )
  .done( function(response) {
    if( response.status === 'success' ) {
      if( response.message.attendance[ros_id].message === 'Attendance updated.' ) {
        ros_form.addClass('success-flash');
      } else {
        ros_form.addClass('error-flash');
      }
      setTimeout(
        function() {
          ros_form.removeClass('error-flash success-flash');
        }, 1000
      );
    }
  });
});

//status management in the tournament manager  
$('.g365_admin_pl_status_toggle_tournament input[type=checkbox]', '#g365_data_manager_admin').on('change', function(){
  var pl_id = $(this).siblings('.pl_id').val();
  var pl_form = $(this).parent();
  var data_set = {
    'player_status' : {
      proc_type : 'proc_data',
      ids : pl_id,
      form_data : pl_form.serialize(),
      //if there is an admin key for working with the full data set, add it
      admin_key : ( typeof window.g365_sess_data.admin_key !== 'undefined' ) ? window.g365_sess_data.admin_key : null
    }
  };
  window.g365_serv_con( data_set )
  .done( function(response) {
    if( response.status === 'success' ) {
      if( response.message.player_status[pl_id].message === 'Status updated.' ) {
        pl_form.addClass('success-flash');
      } else {
        pl_form.addClass('error-flash');
      }
      setTimeout(
        function() {
          pl_form.removeClass('error-flash success-flash');
        }, 1000
      );
    }
  });
});

//verification change ajax general certification
$('.g365_admin_verification_toggle_cert input[type=radio]', '#g365_data_manager_admin').on('change', function(){
  var pl_id = $(this).siblings('.pl_id').val();
  var pl_form = $(this).parent();
  var pl_form_row = pl_form.closest('tr.player-cert');
  var data_set = {
    'player_names_admin' : {
      proc_type : 'proc_data',
      ids : pl_id,
      form_data : pl_form.serialize(),
      //if there is an admin key for working with the full data set, add it
      admin_key : ( typeof window.g365_sess_data.admin_key !== 'undefined' ) ? window.g365_sess_data.admin_key : null
    }
  };
  window.g365_serv_con( data_set )
  .done( function(response) {
    if( response.status === 'success' ) {
      if( response.message.player_names_admin[pl_id].verified === 2 ) {
        pl_form_row.addClass('verified-player').removeClass('unverified-player');
      } else {
        pl_form_row.addClass('unverified-player').removeClass('verified-player');
      }
    }
  });
});

//verification change ajax for certification at tournament
$('.g365-remove-data', '#g365_data_manager_admin').on('click', function(){
  var delete_but = $(this);
  var type_ids = delete_but.attr('data-g365_type');
  if( delete_but.length !== 1 && typeof type_ids === 'undefined' || type_ids === '' ) return false;

  type_ids = type_ids.split('::');
  var type_name = type_ids.shift();
  
  var data_set = {};
  data_set[ type_name ] = {
    proc_type : 'delete_data',
    ids : type_ids,
    //if there is an admin key for working with the full data set, add it
    admin_key : ( typeof window.g365_sess_data.admin_key !== 'undefined' ) ? window.g365_sess_data.admin_key : null
  };
  if(confirm('Please confirm roster deletion.')) window.g365_serv_con( data_set )
  .done( function(response) {
    console.log('hellu', response ); 
    if( response.status === 'success' ) {
      if(/success/i.test(response.message[type_name])) {
        alert(response.message[type_name]);
        location.reload();
      }
    }
  });
});

//edit form in reveal
$( '.g365-edit-data', '.g365_data_manager_wrapper' ).on('click', function(){
  var edit_but = $(this);
  var type_ids = edit_but.attr('data-g365_type');
  if( edit_but.length !== 1 && typeof type_ids === 'undefined' || type_ids === '' ) return false;
  type_ids = type_ids.split('::');
  var type_name = type_ids.shift();
  var form_data = {
    query_type : type_name,
    id : ((type_ids.length === 0) ? null : type_ids),
    go_flat: false,
    template_format: (typeof edit_but.attr('data-g365_form_template') === 'undefined') ? 'form_template_init' : edit_but.attr('data-g365_form_template'),
    field_group: type_name + '_' + type_ids,
    contributions: null
  }
  //get the init presets
  var target_preset = edit_but.attr('data-g365_init_pre');
  //if we have init presets, add them
  if( typeof target_preset !== 'undefined' && target_preset !== '' ) {
    target_preset.split('|').forEach(function(el){
      el = el.split(':::');
      var el_name = el.shift();
      el_name = el_name.substring(0, el_name.length-7);
      el.forEach(function(sub_el){
        sub_el = sub_el.split('::');
        if( form_data.contributions === null ) form_data.contributions = {};
        form_data.contributions[sub_el[0]] = sub_el[1];
      });
    });
  }
  $.when(g365_build_template_from_data( form_data ))
  .done( function(form_template_message){
    //attach the new form and set a handle
    var load_target = (typeof edit_but.attr('data-wrapper_target') === 'undefined') ? $('#g365_form_wrap') : $( '#' + edit_but.attr('data-wrapper_target'));
    if( load_target.length === 0 ) {
      alert('No wrapper, please see administration.');
      return;
    }
   //incase we need to process it this way in the future 
//     if( typeof form_template_message === 'object' ) {
//       if( form_template_message.enabled === null && form_template_message.disabled === null ) {
//         $( '<p>No data found.</p>' ).prependTo( $('.form-holder>form>div:first-child', current_form) );
//       } else {
//         if( form_template_message.disabled === null ) {
//           $('.form-holder>form>div:first-child', current_form).html($( form_template_message.enabled ));
//         } else if( form_template_message.enabled === null ) {
//           $('.form-holder>form>div:first-child', current_form).html($( form_template_message.disabled ));
//         } else {
//           //attach the new form for both disabled and enabled data points
//           var enabled_div = $('.g365_enabled_data', current_form);
//           console.log('thisiii', enabled_div.length);
//           if( enabled_div.length === 0 ) {
//             $( form_template_message.disabled ).prependTo( $('.form-holder>form>div:first-child', current_form) );
//             $( form_template_message.enabled ).prependTo( $('.form-holder>form>div:first-child', current_form) );
//           } else {
//             $( form_template_message.disabled ).prependTo( $('.form-holder>form>div:first-child .g365_disabled_data', current_form) );
//             $( form_template_message.enabled ).prependTo( $('.form-holder>form>div:first-child .g365_enabled_data', current_form) );
//           }
//         }
//       }
//     } else {
//       //attach the new form
//       $('.form-holder>form>div:first-child', current_form).html($( form_template_message ));
// //                 $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
//     }

    var form_new_loaded = $( form_template_message ).prependTo( load_target.empty() );
    g365_form_start_up( form_new_loaded );
    $( '#g365_form_reveal' ).foundation( 'open' );
  });
});

$('.btn-togglePlayer').on('click' ,function () {
    //Go up 2 parents, select all td (children) then toggle vertical-align(top) class
    $(this).parent().parent().children().toggleClass('vertical-align');
    //traverse to pl_block element, toggle hide.
    $(this).next().toggleClass('hide');
});

// Social Sharing Buttons
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));



// $(function() {
    // if(document.getElementById('adminImgUploader')) {

    //     console.log('click');
    //     var photos = $('#photoAdminLibrary photo__img');
    //     var attachBackBtn = $('#attachBackBtn');
    //     var photoSelected = $('.photo__img--attach');
        
    //     photos.click(function(){
    //         var targetSrc = $(this).attr('src');
    //         var modal;

    //         photoSelected.src = targetSrc;

    //         function removeModal() {
    //             modal.remove();
    //             $('body').off('keyup.modal-close');
    //         }
    //         modal = $('<div>').css({
    //             background: 'RGBA(0, 0, 0, 0.8)',
    //             width: '100%',
    //             height: '100%',
    //             position: 'fixed',
    //             zIndex: '10000',
    //             top: '0',
    //             left: '0',
    //             cursor: 'zoom-out'
    //         }).click(function() {
    //             removeModal();
    //         }).appendTo('body');
    //         //handling ESC
    //         $('body').on('keyup.modal-close', function(e) {
    //             if (e.key === 'Escape') {
    //             removeModal();
    //             }
    //         });
    //     });

    //     //back button
    //     attachBackBtn

    //     //Attach players
    // }


    //Verify Photos
    // $('.photo__img--verification').click();

    //Deny Photos
    // $('.photo__library .button--deny').click();
// });
