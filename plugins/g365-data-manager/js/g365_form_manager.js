//var for all data related to the form
var g365_form_data = {};
// if( typeof g365_form_details === 'undefined'  ) var g365_form_details = {items: {}};
var g365_start_status = false;
//placeholder to add functionality after basic form is loaded.
function g365_extend_form (target_container){ /*to prevent error when there is no extension*/ }

function g365_form_start_up( target_container ) {
  //select options with key
  $('select[data-g365_select]', target_container).each(function(){var select_ele = $(this); $('option[value="' + select_ele.attr('data-g365_select') + '"]', select_ele).prop('selected', true);});
  $('.crop_img', target_container).each(function(){ g365_start_croppie( $(this) ); });
  $('.grade-graduation', target_container).change(function(){ g365_grad_year_controller( $(this) ); }).change();
  $('[data-g365_contingent]', target_container).change(function(){ g365_contingent_manager( $(this) ); }).change();
  $('[data-g365_additional_target_limit]', target_container).each(function(){
    var this_target = $(this);
    this_target.change(function(){ g365_addition_limiter( this_target ); }).change();
//     $('#' + this_target.attr('data-g365_additional_target_limit')).change( function(){ this_target.change(); });
  });
  $('.select_loader', target_container).on('change', function(){ g365_build_dropdown_from_data( $(this) ); }).change();
  $('.select_local', target_container).on('change', function(e){ g365_build_dropdown_from_object( $(this) ); }).change();
  $('.select_calc', target_container).on('change', function(){ g365_set_field_calc( $(this) ); }).change();
  $('.form_loader', target_container).on('click keyup', function(e){
    e.preventDefault();
    var keycode = e.keyCode || e.which;
    if (e.type === 'keyup' && keycode !== 13) return;
    g365_build_form_from_data( $(this) );
  });
  $('.field-toggle', target_container).click(function(){ g365_field_toggle( $(this) ); });
  $(".site-close-button", target_container).click( g365_form_section_closer );
  $(".g365-input-formatter", target_container).on('change keyup input', function(){ g365_field_format_lock( $(this), $(this).attr('data-g365_input_format') ) });
  $(".g365-expand-collapse-fieldset", target_container).on('click', function(e, direction){ if( $(e.target).is('input') || $(e.target).is('label') || $(e.target).is('#viewProfileBtn')) return; g365_form_section_expand_collapse( $(this), direction ); });
  $('.change-title', target_container).each(function(){ g365_change_title( $(this) ) });
  
  window.g365_func_wrapper.end[window.g365_func_wrapper.end.length] = {name : g365_extend_form, args : [target_container]};

  g365_livesearch_init( target_container, ((target_container.attr('id') !== 'g365_form_wrap') ? target_container.attr('id') : null) );
  var form_child = target_container.children('.primary-form');
  if( form_child.length ) form_child.submit( g365_handle_form_submit );
  //foundation dependancies
  target_container.foundation();
}

function g365_change_title( target ){
  if( target instanceof jQuery ) {
    var ele_targets = target.attr('data-g365_change_targets');
    var display_totals = target.attr('data_g365_change_totals');
    var delimiter = target.attr('data-g365_change_delimiter');
    delimiter = (typeof delimiter === 'undefined') ? ' ' : delimiter;
    if( typeof ele_targets === 'undefined' || target === '' ) return;
    ele_targets = $(ele_targets.split('|').join());
    if( ele_targets.length === 0 ) return;
    ele_targets.on('keyup change', function(){
      var full_name = [];
      ele_targets.each(function(){
        var new_target = $(this);
        if( new_target.is('select') ) {
          var target_option = $('option:selected', new_target);
          if(target_option.val() !== '' && target_option.val() !== 'new') full_name.push( (typeof target_option.attr('data-g365_short_name') === 'undefined') ? target_option.text() : target_option.attr('data-g365_short_name') );
        } else {
          if(new_target.val() !== '' && new_target.val() !== 'new') full_name.push( (typeof new_target.attr('data-g365_short_name') === 'undefined') ? new_target.val() : new_target.attr('data-g365_short_name') );
        }
      });
      full_name = full_name.filter(function (el) { return el != ''; }).join(delimiter);
      if( display_totals === 'true' && ele_targets.first().is('select') ) {
        full_name += ' <span class="count_totals">(' + (ele_targets.first().children('option:disabled:not([value=""])').length + 1) + ' of ' + ele_targets.first().children('option:not([value=""])').length + ')</span>';
      }
      if( target.is( 'input' ) ) {
        target.val((full_name === '') ? target.attr('data-default_value') : full_name);
      } else {
        target.html((full_name === '') ? target.attr('data-default_value') : full_name);
      }
    }).trigger('keyup');
  }
}
//functionality for the user roster admin multi-add button
function g365_bulk_add(e) {
  e.preventDefault();
  var click_ele = $(this);
  var click_href = click_ele.attr('href');
  if( click_href == '' || typeof click_href === 'undefined' ) return;
  var bulk_control = $('#' + click_ele.attr('data-g365_bulk_add_control'));
  var bulk_items = $('#' + click_ele.attr('data-g365_bulk_add_target'));
  //if we have a control element
  if( bulk_control.length === 1 ) {
    var bulk_control_val = bulk_control.val();
    if( bulk_control_val == '' || typeof bulk_control_val === 'undefined' ) {
      if( typeof bulk_control.attr('data-g365_bulk_add_default') !== 'undefined' ) {
        bulk_control_val = bulk_control.attr('data-g365_bulk_add_default');
      } else {
        console.log('bulk fail');
        return;
      }
    }
    //add to the url
    click_href += bulk_control_val;
    //see if we have team ids to auto-load and it's not the default club team, it has to be added independently
    if( bulk_items.length === 1 && bulk_control_val != 0 ) {
      var bulk_items_select = $('.bulk-add-checkbox input:checked', bulk_items);
      var bulk_items_vals = '';
      if( bulk_items_select.length > 0 ) {
        bulk_items_vals = [];
        bulk_items_select.each(function(){
          bulk_items_vals.push( $(this).attr('data-g365_bulk_id') );
        });
      }
      if( typeof bulk_items_vals === 'object' ) click_href += '?ro_ids=' + bulk_items_vals.join();
    }
  }
  //navigate once modification is done.
  window.location.href = click_href;
}
//       var full_name = '';
//       ele_targets.each(function(){
//         var new_target = $(this);
//         full_name += ' ';
//         if( new_target.is('select') ) {
//           var target_option = $('option:selected', new_target);
//           full_name += (typeof target_option.attr('data-g365_short_name') === 'undefined') ? ((target_option.val() === '' || target_option.val() === 'new') ? '' : ((full_name !== ' ' && delimiter) ? delimiter : '') + target_option.text()) : ((full_name !== ' ' && delimiter) ? delimiter : '') + target_option.attr('data-g365_short_name');
//         } else {
//           full_name += (typeof new_target.attr('data-g365_short_name') === 'undefined') ? ((new_target.val() === '' || new_target.val() === 'null') ? '' : ((full_name !== ' ' && delimiter) ? delimiter : '') + new_target.val()) : ((full_name !== ' ' && delimiter) ? delimiter : '') + new_target.attr('data-g365_short_name');
//         }
//       });
//       full_name = full_name.trim();
//       if( full_name.startsWith('| ') ) full_name = full_name.substring(2);
//       if( full_name.endsWith(' |') ) full_name = full_name.substring(0,-3);
//       if( display_totals === 'true' && ele_targets.first().is('select') ) {
//         full_name += ' <span class="count_totals">(' + (ele_targets.first().children('option:disabled:not([value=""])').length + 1) + ' of ' + ele_targets.first().children('option:not([value=""])').length + ')</span>';
//       }
//       if( target.is( 'input' ) ) {
//         target.val((full_name === '') ? target.attr('data-default_value') : full_name);
//       } else {
//         target.html((full_name === '') ? target.attr('data-default_value') : full_name);
//       }



//add smooth scoll to the form closers
$.fn.scrollView = function () {
  var html_body = $('html, body');
  var win_height = $(window).height();
  var scroll_target = '';
  if( html_body.data( 'force_next_scroll' ) === true ) {
    scroll_target = html_body.data( 'next_scroll_target' );
    html_body.removeData( 'force_next_scroll' );
  } else {
    scroll_target = ($(this).offset().top - (win_height/4));
  }
  //if the target is too close (in the middle of the current veiw) don't scroll
  if( Math.abs(scroll_target - html_body.scrollTop()) < (win_height/2)) return false;
  //set the target to check against if we are allowed to scroll
  html_body.data( 'next_scroll_target', scroll_target );
  //if we are already in a scroll, then exit
  if( html_body.data( 'scroll_in_progress' ) === true ) return false;
  //if it's allowed then set us in scroll mode
  html_body.data( 'scroll_in_progress', true );
  return this.each(function () {
    html_body.animate({
      scrollTop: scroll_target
    }, 500, function(){
      if( scroll_target == html_body.data( 'next_scroll_target' ) ) {
        html_body.data( 'scroll_in_progress', false );
        html_body.removeData( 'next_scroll_target' );
      } else {
        html_body.data( 'force_next_scroll', true );
        $(this).scrollView();
      }
    });
  });
}
//toggles next element, takes options, old
// function g365_field_toggle( toggle_button ) {
//   var field_title = $('.field-title', toggle_button);
//   var field_button = $('.field-button', toggle_button);
// //   <a class="field-toggle block text-right" data-g365_target="event-selector" data-g365_after="hide">select event</a>
//   //if the button has a target, use it, else toggle the next element
//   if( typeof toggle_button.attr('data-g365_target') !== 'undefined' ) {
//     $('#' + toggle_button.attr('data-g365_target')).slideToggle();
//   } else {
//     toggle_button.next().slideToggle();
//   }
//   //if the button should disappear after use, do that.
//   if( toggle_button.attr('data-g365_after') === 'hide' ) toggle_button.hide();
//   var field_toggle_name = field_button.html().match(/^(.+?) (.+)$/);
//   var data_compile = {};
//   var data_capture = [];
//   if( field_toggle_name !== false && Array.isArray(field_toggle_name) ) {
//     var field_action_title = '';
//     //what state are we in
//     data_capture = toggle_button.attr('data-data_capture').split('|');
//     if( field_toggle_name[1] === 'select' || field_toggle_name[1] === 'change' || field_toggle_name[1] === 'add' ) {
//       //when they clicked, it said select
//       if( typeof toggle_button.attr('data-g365_change_title') === 'undefined' ) {
//         toggle_button.attr('data-g365_change_title', field_toggle_name[1]);
//         field_action_title = field_toggle_name[1];
//         toggle_button.addClass('locked-in');
//       } else {
//         field_action_title = 'revert';
//         toggle_button.removeClass('locked-in');
//         field_title.hide();
//       }
//       if( data_capture[0] !== 'undefined' ) {
//         $.each(data_capture, function(dex, target_id){
//           data_compile[target_id] = $('#' + target_id).val();
//         });
//         toggle_button.data('og_vals', data_compile);
//       }
//     } else {
//       //when they clicked, it said revert
//       field_action_title = toggle_button.attr('data-g365_change_title');
//       toggle_button.addClass('locked-in');
//       field_title.show();
//       var revert_targets = toggle_button.data( 'og_vals' );
//       if( typeof revert_targets !== 'undefined' ) {
//         $.each(revert_targets, function(id_key, og_val){
//           $('#' + id_key).val(og_val).change();
//         });
//         data_compile = revert_targets;
//       }
//     }
//     field_title.html( ( typeof data_compile[data_capture[0]] === 'undefined' || data_compile[data_capture[0]] === '' ) ? ( (typeof field_toggle_name[2] === 'undefined') ? 'Datapoint not set.' : (field_toggle_name[2] + ' not set') ) : data_compile[data_capture[0]] );
//     field_button.html( field_action_title + ( (typeof field_toggle_name[2] === 'undefined') ? '' : (' ' + field_toggle_name[2]) ) );
//   }
// }

//toggles next element, takes options
function g365_field_toggle( toggle_button ) {
  if( typeof toggle_button.attr('data-g365_target') !== 'undefined' ) {
    $('#' + toggle_button.attr('data-g365_target')).slideToggle();
  } else {
    if( typeof toggle_button.attr('data-g365_class_toggle') !== 'undefined' ) {
      toggle_button.next().toggleClass( toggle_button.attr('data-g365_class_toggle') );
      toggle_button.addClass('hide');
    } else {
      toggle_button.next().slideToggle();
    }
  }
  var field_button = $('.field-button', toggle_button);
  if( field_button.length === 1 && typeof toggle_button.attr('data-g365_before') !== 'undefined' && typeof toggle_button.attr('data-g365_after') !== 'undefined' ) {
    if( toggle_button.attr('data-g365_before') == field_button.html() ) {
      field_button.html( toggle_button.attr('data-g365_after') );
    } else {
      field_button.html( toggle_button.attr('data-g365_before') );
    }
  }
}

//add functionality to collapse and re-expand field section
function g365_form_section_expand_collapse( caller, direction ) {
  var target_section_string = caller.attr('data-click-target');
  var target_section_title = $('#' + target_section_string + '_fieldset_title');
  var target_section = $('#' + target_section_string + '_fieldset');
  //check for valid before closing..
  if(g365_check_validation(target_section)) return false;
  //update section name
  var title_compile = '';
  $('.change-title', target_section).each(function(){
    var change_ele = $(this);
    var change_default = change_ele.attr('data-default_value');
    title_compile += ((typeof change_default === 'undefined' || change_default === '' &&  change_default !== 'none') ? '<small class="block">' + change_ele.html() + '</small>' : change_ele.html() + ' ');
  });
  $('span', target_section_title).first().html( title_compile );
  //if we have a direction (open, close)
  if( typeof direction !== 'undefined' ){
    if( direction === 'open' && target_section.hasClass('form-disabled') === false ) {
      target_section.removeClass('hide');
      target_section_title.addClass('hide');
    } else {
      target_section.addClass('hide');
      target_section_title.removeClass('hide');
    }
  } else {
    if( target_section.hasClass('hide') && target_section.hasClass('form-disabled') ) return false;
    //make sure the top of the expand section is visible
//     target_section.scrollView();
    //toggle field section
    target_section.toggleClass('hide');
    //toggle the section title
    target_section_title.toggleClass('hide');
  }
  return true;
}

//see if we have any invalid elements
function g365_check_validation(target) {
  var all_inputs = $("input, select, textarea", target);
  var errs = false;
  all_inputs.each(function(){
    var ele = $(this);
    if(ele[0].checkValidity()) {
      ele.parent().removeClass('error_item');
    } else {
      ele.parent().addClass('error_item');
      ele.on('focusout', function(){
        if(ele[0].checkValidity()) {
          ele.parent().removeClass('error_item');
          ele.off('focusout');
        }
      });
      errs = true;
    }
  });
  
  return errs;
}

function g365_form_section_closer() {
  //grab a reference to manage master submit button
  var field_wrapper = $(this).parent().parent().parent().parent();
  //to remove element
  var to_remove = $(this).parent().parent().parent();
  //origin info
  var to_remove_dropdown_key = to_remove.attr('data-g365_dropdown_key');
  var to_remove_dropdown_target = to_remove.attr('data-g365_dropdown_target');
  to_remove_dropdown_target = $('#' + to_remove_dropdown_target);

  if( to_remove_dropdown_target.length === 1 ) {
    //make sure that this is visible in case it got folded up
    var to_remove_dropdown_target_closest = to_remove_dropdown_target.closest('.form-holder').children(':not(.primary-form)').removeClass('hide').parent();
    to_remove_dropdown_target_closest.parent().children(':not(.form-holder)').removeClass('hide');
  }
  //if there aren't any other entries open any init forms which may have been hidden and hide their respective toggle buttons
  if( to_remove.siblings().length < 1 ) to_remove.closest('.form-holder').children(':not(.primary-form)').removeClass('hide').prev('.field-toggle').addClass('hide');
  //if there is a reference to this element in the order_data field, strip it
  var order_data_field = $('#g365_order_data');
  var order_data_field_val = ( order_data_field.length === 1 ) ? order_data_field.val() : '';
  if( order_data_field_val !== null || order_data_field_val !== 'null' || order_data_field_val !== '' ) order_data_field.val('null');

  //remove section field set
  to_remove.slideUp( "normal", function() {
    $(this).remove();
    //if there are no more sections, hide the master submit button (for master forms)
    if( field_wrapper.children('div').length === 0 ) field_wrapper.next('.g365_form_sub_block').hide();
    //if we have origin dropdown info, reactive, and select the new option
    if( typeof to_remove_dropdown_key !== 'undefined' && typeof to_remove_dropdown_target !== 'undefined' && to_remove_dropdown_target.length === 1 ) {
      to_remove_dropdown_target.children('option').map( function() {
        if($(this).text() == to_remove_dropdown_key) {
          $(this).prop('disabled', false).prop('selected', true);
          to_remove_dropdown_target.change();
        }
      });
    }
  });
  //if the previous search input form is hidden, reactivate it (for nested select forms only)
  if( field_wrapper.prev().is(':hidden') ) field_wrapper.prev().slideDown();
  if( field_wrapper.prev().is('select') ) field_wrapper.prev().val('');

  //Makes "Create Player" add button re-appear
  if($('#addBtnProfile')) {
     $('#addBtnProfile').show();
    }

}

function g365_form_message_clear( form ) {
  function g365_clear_message( event ) {
    form = event.data.wrapper_form;
    $('#' + form.attr('id') + '_message').html('').addClass('hide');
    form.off( 'focus', ':input', g365_clear_message);
  }
  form.on( 'focus', ':input', { wrapper_form: form }, g365_clear_message );
}

function g365_grad_year_controller( target_element ){
  var grad_target = $( '#' + target_element.attr('id') + '_grad_yr' );
  if( grad_target.length === 1 ) {
    var grade_val = $('option:selected', target_element).val();
    if( grade_val === '' ) {
      grad_target.val( '' );
    } else {
      var month = new Date();
      var year = month.getFullYear();
      month = month.getMonth();
      grad_year = year + (( month < 8 ) ? 12 : 13 ) - grade_val;
      grad_target.val( grad_year );
    }
  }
}

function g365_start_croppie( target_element ) {
	var $uploadCrop;
	function readFile(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$uploadCrop.parent().addClass('crop-size').removeClass('hide');
				$uploadCrop.croppie('bind', {
					url: e.target.result
				}).then(function(){
				});
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			alert("Sorry - you're browser doesn't support the FileReader API.");
      input.parentNode.parentNode.innerHTML = "<p>Sorry - you're browser doesn't support the FileReader API</p>";
		}
	}

  
  
  var image_sizes = target_element.attr('data-g365_crop_settings'),
    viewport = {width: 400, height: 300},
    size = { width: 420, height: 320 },
    result_size =  { width: 400, height: 300 },
    enforceBoundaryMin = true,
    enforceBoundary = true,
    backgroundColor = false,
    format = 'jpg';
  switch(image_sizes) {
    case 'profile':
      viewport = {width: 200, height: 300, type: 'square'}; 
      size = { width: 250, height: 350 };
      result_size = { width: 400, height: 600 };
      break;
    case 'reportcard':
      viewport = {width: 300, height: 300, type: 'square'}; 
      size = { width: '100%', height: 320 };
      result_size = { width: 2000, height: 2000 };
//       enforceBoundaryMin = true;
      backgroundColor = '#fefefe';
      break;
    case 'birthcert':
//       console.log("helloooo");
      viewport = {width: 300, height: 300, type: 'square'}; 
      size = { width: 420, height: 320 };
      result_size = { width: 6000, height: 6000 };
      backgroundColor = '#fefefe';
      break;
    case 'org_profile':
      viewport = {width: 300, height: 225, type: 'square'}; 
      size = { width: 400, height: 300 };
      result_size = { width: 400, height: 300 };
      format = 'png';
      break;
    default:
      image_sizes = (typeof image_sizes === 'string' && image_sizes !== '') ? image_sizes.split('|') : '';
      if( Array.isArray( image_sizes ) ) {
        image_sizes.forEach(function(image_size, dex){
          image_sizes[dex] = image_sizes[dex].split(',');
          if( !image_sizes[dex].isArray() || !Number.isInteger(image_sizes[dex][0]) || !Number.isInteger(image_sizes[dex][1]) ) {
            image_sizes = '';
            return false;
          }
        });
        viewport = {width: image_sizes[0][0], height: image_sizes[0][1]};
        size = { width: image_sizes[1][0], height: image_sizes[1][1] };
      }
      break;
  }
  var desiredMaximumWidth = viewport.width; // pixels
	$uploadCrop = $('.crop_upload_canvas', target_element).croppie({
    boundary: size,
    viewport: viewport,
		enableExif: true,
    enableOrientation: true,
		mouseWheelZoom: false,
    enforceBoundary: true
	});
	$uploadCrop.on('update.croppie', function (ev, data) {
		$uploadCrop.croppie('result', {
			type: 'base64',
			format: format,
			size: result_size,
			quality: 0.8,
      backgroundColor: backgroundColor
		}).then(function (resp) {
			$('.croppie_img_data', target_element).val(resp);
			$('.remove-croppie', target_element).removeClass('hide');
		});
	});
	$('.crop_uploader', target_element).on('change', function () { readFile(this); });
	$('.remove-croppie', target_element).on('click', function(){
		$('.crop_upload', target_element).removeClass('hide');
		$('.crop_uploader', target_element).val('');
		$('.croppie_img_data', target_element).val('null');
		$('.crop_upload_canvas_wrap', target_element).removeClass('crop-size');
		$('.remove-croppie, .cropped_img, .crop_upload_canvas_wrap', target_element).addClass('hide');
	});
	if( typeof target_element.attr('data-g365_croppie_img_url') != 'undefined' && target_element.attr('data-g365_croppie_img_url') != '' ) {
		$('.crop_upload', target_element).addClass('hide');
		$('.remove-croppie', target_element).removeClass('hide');
    $('.cropped_img', target_element).append( '<img src="' + target_element.attr('data-g365_croppie_img_url') + '" />' );
	}
}

//server connection function
g365_serv_con = function( data_set, settings ){
  var send_data = {
    g365_session : g365_sess_data.id,
    g365_token : g365_sess_data.token,
    g365_time : g365_sess_data.time,
    g365_admin_key : ( typeof g365_sess_data.admin_key !== 'undefined' ) ? g365_sess_data.admin_key : null,
    g365_data_set : data_set,
    g365_settings : ( g365_serv_con.arguments.length === 2 ) ? settings : null
  };
  return $.ajax({
    type: "post",
    url: g365_script_ajax,
    cache: false,
    headers: {'X-Requested-With': 'XMLHttpRequest'},
    data: send_data,
    dataType: "json"
  });
}

//start a data point claim
function g365_claim_start( field_type, request_target ){
  if( arguments.length !== 2) return 'Need array of the correct size to claim.' + typeof arguments + ':' + arguments.length;
	//to support use of ajax for getting data_set
	var defObj = $.Deferred();
  var data_set = {};
  data_set[ field_type ] = {
    proc_type : 'claim_data',
    ids : request_target
  }
  g365_serv_con( data_set )
  .always( function(response) {
    defObj.resolve(response);
  });
  return defObj.promise();
}

function g365_manage_additional_data( additional_target_base, additional_data ){
  //save the reference to the original element
  var additional_target = additional_target_base;
  var add_target_type = null;
  var additional_target_ele = null;
  if( additional_target instanceof jQuery ) {
    add_target_type = additional_target.attr('data-g365_load_target');
    console.log('check me', add_target_type, additional_target);
    //where we are going to put these addition(s)
    additional_data = additional_target.attr('data-g365_additional_data');
    //if its a select field then grab the data off the option
    if( additional_target.is('select') ) additional_data = additional_target.children('option:selected').attr('data-g365_additional_data');
    //if this is a multi-element, don't try to update the target
    if( typeof add_target_type !== 'undefined' && add_target_type.charAt(0) !== '.' ) {
      additional_target = additional_target.attr('data-g365_additional_target');
      //make sure we have something to process
      if( typeof additional_target !== 'undefined' ){
        //split to get our variables
        additional_target = additional_target.split(',');
        //first position is the write target
        additional_target_ele = $( '#' + additional_target[0] );
        if( additional_target_ele.length === 0 ) return;
        //all the rest of the positions are contributors
        additional_target.shift();
        //if the data is null, reset everything and exit
        if( additional_data === 'null' || additional_data === null || additional_data === '' || typeof additional_data === 'undefined') {
          additional_target_ele.parent().addClass('hide');
          additional_target_ele.html('');
          return;
        }

      } else {
        console.log('No additional data to process.');
        return;
      }
    }
  }
  //switch quotes if needed
  if( $.trim(additional_data).charAt(1) === "'" ) additional_data = additional_data.replace(/'/g, '"');
  //process incoming data
  if( additional_data !== '' ) additional_data = JSON.parse(additional_data);
  if( typeof additional_data !== 'object' ) return false;
  //if we have a bunch of elements
  if( typeof add_target_type !== 'undefined' && add_target_type.charAt(0) === '.' ) {
    //only look at elements in the same form
    add_target_type = $( add_target_type, additional_target_base.closest('form') );
    //loop through all elements and add the listeners for locking
    if( add_target_type.length > 0 ) {
      //clean up any listeners that are hanging around
      add_target_type.each(function(dex, target_element){
        //make a jQuery object to the target_element
        target_element = $(target_element);
        //because jQuery for some reason includes a bunch of nodes that I didn't ask for
        if(parseInt(target_element.data('index')) < 3) return;
        //remove old handlers
        target_element.off('change.additional_data');
        var sub_target_key = target_element.attr('data-g365_additional_target');
        //attach a listener to build the field
        target_element.on('change.additional_data', { add_target: $('#' + target_element.attr('data-g365_load_target')), add_data: additional_data, add_data_key: sub_target_key }, g365_additional_data_handler).trigger('change.additional_data');

      });
    } else {
      console.log('No targets to update.');
    }
  } else {
    //clean up any listeners that are hanging around
    $.each(additional_target, function(dex, val){
      //check that the element exists
      if( typeof val === 'object' ) return;
      var additional_target_contributor = $( '#' + val );
      if( additional_target_contributor.length === 0 ) return;
      //remove any previous handlers and data
      additional_target_contributor.off('change.additional_data');
      //attach a listener to build the field
      additional_target_contributor.on('change.additional_data', { add_target: additional_target_ele, add_data: additional_data }, g365_additional_data_handler).change();
    });
  }
}
function g365_additional_data_handler( e_data ){
  var additional_data_key = $(this).val();
  var additional_data = e_data.data.add_data;
  var additional_target_ele = e_data.data.add_target;
  var additional_options = '';
  //see if there isn't a button that needs to be revealed
  g365_manage_add_button($(this));
  //if there isn't data to add to the field, erase everything
  if( additional_data_key === '' || additional_data_key === null || additional_data_key === 'null' || typeof additional_data[additional_data_key] !== 'object' ) {
    additional_target_ele.parent().addClass('hide');
    additional_target_ele.html('').prop('disabled', true);
    return false;
  }
  //need to work off a pre-ordered list because the keys get reordered alpha (incorrectly)
  $.each(g365_division_key, function( key, key_name ) {
    if( key in additional_data[additional_data_key] ) {
      additional_options += '<option value="' + key + '" data-g365_short_name="' + key + '" data-g365_add_data="' + additional_data[additional_data_key][key] + '">' + key + '</option>';
    }
  });
  if( additional_options === '' ) {
    additional_target_ele.parent().addClass('hide');
    additional_target_ele.html('').prop('disabled', true);
    return false;
  }
  //with the default option
  additional_target_ele.attr('data-g365_og_val', '').html( '<option value="">-- Please Select</option>' + additional_options );
//   additional_target_ele.attr('data-g365_og_val', '').html( additional_options );
  if( additional_target_ele.attr('data-g365_select') !== '' ) {
    $('option[value="' + additional_target_ele.attr('data-g365_select') + '"]', additional_target_ele).prop('selected', true);
  }
  additional_target_ele.parent().removeClass('hide');
  additional_target_ele.prop('disabled', false);
}

function g365_cross_check_reqs( target ) {
  //opening vars
  var contributions_compile = {};
  var contributions_short_name = {};
  var contributions_compile_req = {};
//   var contributions_additional_data = {};
  //get contributions
  var contributions = target.attr('data-g365_contributors');
//   console.log("hello " . contributions);
  if( typeof contributions === 'undefined' ) return null;
  //set error status
  var error = false;
  //parse
  contributions = contributions.split('|');
  //try to get optional reqiured element list
  var contributions_req = target.attr('data-g365_contributors_req')
  //set it empty or parse into array
  contributions_req = ( typeof contributions_req === 'undefined' ) ? [] : contributions_req.split('|');
  //loop through and check for vaild contributions
  $.each(contributions, function(dex, cont_id){
    //each entry is an id
    var contribution_part = $( '#' + cont_id );
    //get the value of the contribution
    contributions_compile[cont_id] = contribution_part.val();
    //test for requirement, set error if there is an issue
    if( contributions_req.length > 0 && ($.inArray( cont_id, contributions_req ) > -1) && (typeof contributions_compile[cont_id] === 'undefined' || contributions_compile[cont_id] === '' || contributions_compile[cont_id] === null) ) {
      var error_target = contribution_part;
      //add error to individual elements with a self cancelling highlight
      if( typeof error_target.attr('data-g365_error_target') !== 'undefined' && $('#' + error_target.attr('data-g365_error_target')).length === 1 ) error_target = $('#' + contribution_part.attr('data-g365_error_target'));
      error_target.addClass('g365_error').parent().addClass('error_parent');
      error_target.on('focus.g365_error', function(){
        var error_self = $(this);
        error_self.parent().removeClass('error_parent');
        error_self.off('focus.g365_error');
      });
      error = true;
    }

    //pass along the attachment data if needed
    if( contribution_part.attr('data-g365_send_additional') === 'true' && typeof contribution_part.attr('data-g365_additional_data') != 'undefined' && contribution_part.attr('data-g365_additional_data') != '') {
      contributions_compile['divisions'] = contribution_part.attr('data-g365_additional_data');//.replace(/"/g, '&quot;');//$('<div />').text(contribution_part.attr('data-g365_additional_data')).html();
    }

    //add required to a separate object
    if( $.inArray( cont_id, contributions_req ) > -1 ) contributions_compile_req[cont_id] = contribution_part.val();
    //special instructions for select elements
    if( contribution_part.is('select') ) {
      //if the select element doesn't have any options unset the error
      if( contribution_part.children().length === 0 ) {
        error = false;
      } else {
        //otherwise grab some info off the selected option
        var contribution_part_option = $('option:selected', contribution_part);
        //get the short names if they are present
        contributions_short_name[cont_id] = (typeof contribution_part_option.attr('data-g365_short_name') === 'undefined') ? ((contribution_part_option.val() === '' || contribution_part_option.val() === 'new') ? '' : contribution_part_option.text()) : contribution_part_option.attr('data-g365_short_name');
        //if we have additional data add it to the dataset
        if( typeof  contribution_part_option.attr('data-g365_ref_val') !== 'undefined' && contribution_part_option.attr('data-g365_ref_val') !== '' ) {
          var cont_date = new Date();
          //which school year
          var cont_target_year = (cont_date.getMonth() < 8) ? cont_date.getFullYear()-1 : cont_date.getFullYear();
          //divison
          var cont_division = parseInt(contribution_part_option.attr('data-g365_ref_val'));
          //add the minimum birth year for players
          contributions_compile[cont_id + '_birth_lock'] = ">" + (cont_target_year - cont_division) + "-07-30";
//           contributions_compile[cont_id + '_birth_lock'] = "> '" + (cont_target_year - cont_division) + "-07-30'"; // + '-OR'
          //add the minimum class for players
          contributions_compile[cont_id + '_class_lock'] = '>' + (cont_target_year + 18 - cont_division); //  + '-OR'
          contributions_compile['roster_lock_type'] = 0; //default
        }
        //if we have additional exception info add it
        if( typeof contribution_part_option.attr('data-g365_add_data') !== 'undefined' && contribution_part_option.attr('data-g365_add_data') !== '' ) {
          //add the lock type
          contributions_compile['roster_lock_type'] = contribution_part_option.attr('data-g365_add_data');
        }
      }
      //if we needed to send the options of a select, probably deprecated
      if( contribution_part.attr('data-g365_send_options') === 'true' && contribution_part.children('option').length ) {
        if( contribution_part.children().first().html().indexOf("Please") != -1 ) contribution_part.children().first().remove();
        contributions_compile[contribution_part.attr('id') + '_options'] = contribution_part.html();
      }
    } else {
      //for all other elements grab the info
      contributions_short_name[cont_id] = (typeof contribution_part.attr('data-g365_short_name') === 'undefined') ? contribution_part.val() : contribution_part.attr('data-g365_short_name');
    }
    if( error ) return false;
  });
  if( error ) return false;
  return {data: contributions_compile, names: contributions_short_name, req: contributions_compile_req};
//   return {data: contributions_compile, names: contributions_short_name, additional_data: contributions_additional_data};
}

//add functionality for form dependancy
function g365_contingent_manager( caller ) {
  var target = $( '#' + caller.attr('data-g365_contingent') );
  if( target.length !== 1 ) return 'Cannont find target.';
  var the_val = caller.val();
  //make the var to target all contingent divs
  var target_children = target.children('div');
  //if it's a checkbox handle it differently
  if( caller.prop('type') === 'checkbox' ) {
    if( caller.prop('checked') === true ) {
      $(':input', target_children).prop('disabled', false).prop('required', true);
      target.removeClass('form-disabled').slideDown();
    } else {
      var sub_target = $(':input', target_children); //.prop('disabled', true).prop('required', false).val('');
      sub_target.each(function(){
        $(this).prop('disabled', true).prop('required', false).val('');
      });
      target.addClass('form-disabled').slideUp();
    }
  } else {
    //other input fields depend on the data being entered by the user
    if( typeof the_val === 'undefined' || the_val === '' || the_val === 'new' || the_val === 'null' || the_val === null ) {
      $(':input', target_children).prop('disabled', true);
      target.addClass('form-disabled').slideUp();
  //     target.addClass('form-disabled');
    } else {
      $(':input', target_children).prop('disabled', false);
      target.removeClass('form-disabled').slideDown();
  //     target.removeClass('form-disabled');
    }
  }
}

//grade order
var g365_grade_key_order = [
  "30",
  "29",
  "28",
  "27",
  "26",
  "25",
  "24",
  "23",
  "22",
  "21",
  "20",
  "19",
  "18",
  "17",
  "16",
  "15",
  "14",
  "13",
  "12",
  "11",
  "10",
  "9",
  "8",
  "7",
  "6",
  "5",
  "4",
  "3",
  "2",
  "1",
  "60",
  "59",
  "58",
  "57",
  "56",
  "55",
  "54",
  "53",
  "52",
  "51",
  "50",
  "49",
  "48",
  "47",
  "46",
  "45",
  "44",
  "43",
  "42",
  "41",
  "40",
  "39",
  "38",
  "37",
  "36",
  "35",
  "34",
  "33",
  "32",
  "31",
];
//grade label key
var g365_grade_key = {
  "30" : "Level 30",
  "29" : "Level 29",
  "28" : "Level 28",
  "27" : "Level 27",
  "26" : "Level 26",
  "25" : "Level 25",
  "24" : "Level 24",
  "23" : "Level 23",
  "22" : "Level 22",
  "21" : "Level 21",
  "20" : "Level 20",
  "19" : "Level 19",
  "18" : "Level 18",
  "17" : "17U / Varsity",
  "16" : "16U / JV",
  "15" : "15U / Frosoph",
  "14" : "14U / 8th Grade",
  "13" : "13U / 7th Grade",
  "12" : "12U / 6th Grade",
  "11" : "11U / 5th Grade",
  "10" : "10U / 4th Grade",
  "9" : "9U / 3rd Grade",
  "8" : "8U / 2nd Grade",
  "7" : "Level 7",
  "6" : "Level 6",
  "5" : "Level 5",
  "4" : "Level 4",
  "3" : "Level 3",
  "2" : "Level 2",
  "1" : "Level 1",
  "60" : "Level 30G",
  "59" : "Level 29G",
  "58" : "Level 28G",
  "57" : "Level 27G",
  "56" : "Level 26G",
  "55" : "Level 25G",
  "54" : "Level 24G",
  "53" : "Level 23G",
  "52" : "Level 22G",
  "51" : "Level 21G",
  "50" : "Level 20G",
  "49" : "Level 19G",
  "48" : "Level 18G",
  "47" : "Varsity Girls",
  "46" : "JV Girls",
  "45" : "Frosoph Girls",
  "44" : "Girls 8th Grade",
  "43" : "Girls 7th Grade",
  "42" : "Girls 6th Grade",
  "41" : "Girls 5th Grade",
  "40" : "Girls 4th Grade",
  "39" : "Level 9G",
  "38" : "Level 8G",
  "37" : "Level 7G",
  "36" : "Level 6G",
  "35" : "Level 5G",
  "34" : "Level 4G",
  "33" : "Level 3G",
  "32" : "Level 2G",
  "31" : "Level 1G",
  "74" : "Frosh/Soph",
  "75" : "JV",
  "76" : "Varsity",
};
//division label and order key
var g365_division_key = {
  "Open" : "Open",
  "Open East" : "Open East",
  "Open West" : "Open West",
  "Gold" : "Gold",
  "Gold East" : "Gold East",
  "Gold West" : "Gold West",
  "Gold North" : "Gold North",
  "Gold South" : "Gold South",
  "Silver" : "Silver",
  "Silver East" : "Silver East",
  "Silver West" : "Silver West",
  "Silver North" : "Silver North",
  "Silver South" : "Silver South",
  "Bronze" : "Bronze",
  "Bronze East" : "Bronze East",
  "Bronze West" : "Bronze West",
  "Bronze North" : "Bronze North",
  "Bronze South" : "Bronze South",
  "Copper" : "Copper",
  "Copper East" : "Copper East",
  "Copper West" : "Copper West",
  "Copper North" : "Copper North",
  "Copper South" : "Copper South",
  "ABT" : "ABT",
  "Baller TV" : "Baller TV",
  "Destination Irvine" : "Destination Irvine",
  "Kangaroo" : "Kangaroo",
  "OCYSF" : "OCYSF",
  "Passport" : "Passport",
  "SCIBCA" : "SCIBCA",
  "Circuit" : "Circuit",
  "Do Not Show" : "Do Not Show",
  "" : "",
};

//scrub the dependance chain
function revert_dependance_chain(ele){
  if( ele.length === 0 ) return;
  if( ele.is( 'form' ) ) {
    ele.children('.error, .success').remove();
  } else {
    ele.parent().children('.error, .success, .new_form').remove();
    if( ele.attr('data-g365_static') === "true") {
      ele.prop("selectedIndex", 0);
    } else {
      if( ele.is(':text') ) {
        ele.val('');
      } else {
        ele.html('');
      }
    }
    g365_contingent_manager(ele);
  }
  //if we have an add button, manage it
  g365_manage_add_button( ele );
//   var add_button = ele.attr('data-g365_add_button');
//   if( typeof add_button !== 'undefined' ) $('#' + add_button).hide();
  var reset_target = $('#' + ele.attr('data-g365_load_target') );
  if( reset_target.length === 0 ) {
    reset_target = $('#' + ele.attr('data-g365_reset_target') );
    if( reset_target.length !== 0 ) ele.trigger('resetter');
  }
  revert_dependance_chain( reset_target );
}

//assemble select list from local object on the select option
function g365_build_dropdown_from_object( target ) {
  //see if we are doing an id or a class
  var load_target = target.attr('data-g365_load_target');
  if( typeof load_target === 'undefined' || load_target === '' ) return 'Need load target to build.';
  //check data to build with too
  var the_data = ( target.is('select') ) ? target.children('option:selected').attr('data-g365_additional_data') : target.attr('data-g365_additional_data');
//   console.log('piece', target, load_target, the_data);
  //if we are building for a class figure it out now
  if( load_target.charAt(0) === '.' ) {
    //only look at elements in the same form
    load_target = $(load_target, target.closest("form"));
  } else {
    //grab the element we are trying to administer
    load_target = $('#' + load_target);
    if( load_target.length !== 1 ) return 'Bad load target reference.';
  }
  //exit if we are missing stuff
  if( typeof the_data === 'undefined' || the_data === '' ) {
    load_target.html('').change();
    load_target.parent().addClass('hide');
    return;
  }
  load_target.each(function (){revert_dependance_chain($(this))});
//   revert_dependance_chain(load_target);
  the_data = JSON.parse($.trim(the_data.replace(/\'/g, '"')));
  var newContent = '<option value="">-- Please Select</option>';
  $.each(the_data, function( key, levels ) {
    var option_title = g365_grade_key[key];
    var option_additional = ((levels == 0) ? '' : ' data-g365_additional_data="' + (JSON.stringify(levels).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')) + '"');
    newContent += '<option value="' + key + '" data-g365_ref_val="' + key + '"' + option_additional + '>' + ((typeof option_title === 'undefined') ? key : option_title) + '</option>';
  });
  load_target.each(function(){
    var load_tar = $(this);
    if( typeof load_tar.attr('data-g365_select') !== 'undefined' ) {
      load_tar.html(newContent);
      $('option[value="' + load_tar.attr('data-g365_select') + '"]', load_tar).prop('selected', true);
      load_tar.change();
      //add a hook for disabled inputs, if their parent or parent's parent has the class 'form-disabled' disable this element too
      if( load_tar.prop('disabled') === true || load_tar.parent().hasClass('form-disabled') || load_tar.parent().parent().hasClass('form-disabled') ) load_tar.prop('disabled', true);
    } else {
      load_tar.html(newContent).change();
    }
    //if we are hiding a field keep it hidden
    if( load_tar.parent().attr('data-g365_link_target_dest') !== 'hide' ) load_tar.parent().removeClass('hide');
  });
  //add dependant additions
  g365_manage_additional_data( target );
  g365_manage_add_button( target );
}

//toggle add buttons based on a single source
function g365_manage_add_button(target){
  var add_button = target.attr('data-g365_add_button');
  if( typeof add_button !== 'undefined' ) {
    var the_val = ( target.is('select') ) ? $('option:checked', target).val() : target.val();
    //if we don't have a value to use then hide the add button
    if( typeof the_val === 'undefined' || the_val === '' || the_val === null || the_val === 'new' || the_val === 'undefined' ) {
      $('#' + add_button).hide();
    } else {
      $('#' + add_button).show();
    }
  }
}

//assemble select list from data
function g365_build_dropdown_from_data( target ) {
// <select id="team_level" class="data_loader" data-g365_type="team_names" data-g365_load_target="team_selector" data-g365_contributors="club_id">
  //get all the manitory variables
  var newContent = '';
  var target_id = target.attr('id');
  var the_val = target.val();
  var load_target = $('#' + target.attr('data-g365_load_target'));
  //revert all the subsequent dependancies
  revert_dependance_chain(load_target);
  var type = target.attr('data-g365_type');
  var result_limit = target.attr('data-g365_limit');
  //if we have an add button, manage it
  var add_button = target.attr('data-g365_add_button');
  if( typeof add_button !== 'undefined' ) {
    //if we don't have a value to use then hide the add button
    if( the_val === '' || the_val === null || the_val === 'new' ) {
      $('#' + add_button).hide();
    } else {
      $('#' + add_button).show();
    }
  }
  //if there was an error set, remove it in preparation for the next try
  if( target.next().hasClass('error') || target.next().hasClass('success') ) target.next().remove();
  //after clearing the errors, exit if it's a new record
  if( the_val === 'new' ) {
    g365_build_form_from_data( target );
    return false;
  }
  //if we are getting missing or malformed data, exit.
  if( target.prop('disabled') === true || target.length !== 1 || the_val === '' || the_val === null || typeof the_val === 'undefined' || typeof type === 'undefined' || typeof target_id === 'undefined' || load_target.length !== 1 ) return false;
  //see if we need to collect requirement data
  var contributions_compile = g365_cross_check_reqs( target );
  if( contributions_compile === false ) {
//     console.log("hohohhohhhohhoh");
    target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Missing required field data.</p>');
    return false;
  }
  //make the val an array before we potentially change it to null
  the_val = the_val.split('|');
  //if one of the contributions is the calling field itself, we are doing a search without specific ids, disregard its value
  if( contributions_compile !== null && !$.isEmptyObject(contributions_compile.data) && ($.inArray( target_id, $.map(contributions_compile.data, function(el,key){ return key; }) ) > -1) ) the_val = null;
  
  //setup ajax data object
  var data_set = {};
  data_set[type] = {
    proc_type : 'get_data',
    ids : the_val,
    contributions: ( contributions_compile === null ) ? null : contributions_compile.data,
    limit: (typeof result_limit !== 'undefined') ? result_limit : ''
  }
  g365_serv_con( data_set )
  .done( function(response) {
    console.log(response);
    if (response.status === 'success') {
      //to reset any target
      newContent += '<option value="">-- Please Select</option>';
      //add new data if allowed
      newContent += (target.attr('data-ls_no_add') === "true") ? '' : '<optgroup label="New"><option value="new">Create New</option></optgroup>';
      var newContentOptions = '';
      //if we have an array, loop through template variables replacing each with data from the query
      $.each(response.message[type], function(id_key, vals){
        newContentOptions += '<option value="' + vals.id + '" data-g365_short_name="' + vals.short_name + '">' + vals.title + '</option>';
      });
      if( newContentOptions !== '' ) newContent += '<optgroup label="Existing">' + newContentOptions + '</optgroup>';
    } else {
      console.log('false');
      newContent = '';
      target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">' + response.message + '</p>');
    }
  })
  .fail( function(response) {
    console.log('error', response);
    newContent = '';
    target.parent().append( response.responseText );
  })
  .always( function(response) {
    //post respose
    load_target.html( newContent );
    //if there is an error on the field that we just posted to, clear it too
  });
}

//load presets into the form
function g365_add_presets( form_template, preset_compile ) {
  var element_id_key = form_template.attr('id').replace('_fieldset', '');
  if( $.isPlainObject(preset_compile) === false || typeof element_id_key === 'undefined' ) return form_template;
  $.each( preset_compile.data, function( key, key_val ){
    var preset_element = $( '#' + element_id_key + '_' + key, form_template );
    if( preset_element.length !== 1 ) return;
    preset_element.val(key_val);
    if( typeof preset_compile.names !== 'undefined' ) preset_element.attr('data-g365_short_name', preset_compile.names[key]);
    if( (preset_compile.hide && preset_compile.hide[key]) || preset_element.attr('type') === 'hidden' ){
      preset_element.parent().hide();
    }
    if( preset_element.is('select') ) {
      console.log('I is select');
    }
  });
  if( typeof preset_compile.attrs !== 'undefined' ) $.each( preset_compile.attrs, function( key, key_val ){
    var preset_element = $( element_id_key, form_template );
    form_template.attr(key, key_val);
  });
  return form_template;
}

//assemble form from data
function g365_build_form_from_data( target ) {
  var type = ( target.attr('data-g365_type_new') ) ? target.attr('data-g365_type_new') : target.attr('data-g365_type');
  var target_id = target.attr('id');
  var load_target_id = target.attr('data-g365_form_dest');
  var load_target = $('#' + load_target_id + '_data');
  //if we are missing the load target, we can create right after the form.
  if( load_target.length === 0 ) load_target = $( '<div class="new_form" id="' + load_target_id + '_data"></div>' ).appendTo( target.parent() );
  //exit if we don't have what we need or are malformed
  if( target.length !== 1 || typeof type === 'undefined' || typeof target_id === 'undefined' ) return false;

  //evaluate the target and it's value
  var the_val;
  var id_val;
  //add a pointer to the data (id) that controls the form creation
  if( typeof target.attr('data-g365_data_target') === 'undefined' ) {
    the_val = target.val();
    id_val = target.attr('id');
    var origin_id = id_val;
  } else {
    the_val = target.attr('data-g365_data_target');
    var new_target = $( '#' + the_val ); 
    if( new_target.length === 1 ) {
      id_val = new_target.attr('id');
      the_val = new_target.val();
    } else {
      id_val = null;
      the_val = null;
    }
  }
  //adds a click on 'data-g365_select_click' target at the end of the process
//   if( typeof data.searchField.attr('data-g365_select_click') === 'string' ) $('#' + data.searchField.attr('data-g365_select_click')).click();
  //if the val is not 'new' parse the data id
  the_val = ( the_val === null || the_val === 'new' || the_val === '' || typeof the_val === 'undefined' ) ? null : parseInt(the_val);
  //we need the val to proceed
  if( isNaN(the_val) ) return false;
  //if there was an error set, remove it in preparation for the next try
  if( target.next().hasClass('error') || target.next().hasClass('success') ) target.next().remove();
  //also if the field is a one off, clear it
  if( target.attr( 'data-g365_action' ) === 'load_form' && typeof target.attr('data-g365_form_template_new') !== 'undefined' ) {
    target.val('');
  }
  //see if we need to collect requirement data
  var contributions_compile = g365_cross_check_reqs( target );
  if( contributions_compile === false ) {
    target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Missing required field data.</p>');
    return false;
  }

  //make sure that we aren't running into any limitations on the add
  var limits = target.attr('data-g365_limit');
  var dropdown = false;
  var dropdown_select = false;
  var dropdown_id;
  if( typeof limits !== 'undefined') {
    var break_out = false;
    limits = limits.split('|');
    $.each(limits, function(dex, limit_key){
      var limit_vals = limit_key.split(',');
      switch( limit_vals[0] ){
        case 'max':
          if( load_target.children().length >= limit_vals[1] ) {
            target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Maximum reached.</p>');
            break_out = true;
            return false;
          }
          break;
        case 'exceptions':
          var limit_val = (limit_vals[1] === 0) ? 0 : 2;
          if( limit_val !== 0 && load_target.children('[data-g365_exception]').length >= limit_val ) {
            target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Exception maximum reached.</p>');
            break_out = true;
            return false;
          }
          break;
        case 'dropdown':
          dropdown = $('#' + limit_vals[1]);
          if( dropdown.is('select') ) {
            var dropdown_keys = load_target.children('[data-g365_dropdown_key]').map( function(){ return $(this).attr('data-g365_dropdown_key'); } ).get();
            dropdown_select = dropdown.children('option:selected');
            if( dropdown_select.length !== 1 ){
              target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Cannot find unique listing.</p>');
              break_out = true;
              return false;
            } else {
              dropdown_id = dropdown_select.html();
              var dupe = false;
              $.each(dropdown_keys, function(dex,key_val){ if( key_val === dropdown_id ) dupe = true; return false; });
              if( dropdown_keys.length > 0 && dupe ) {
                target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Event slot already in use.</p>');
                break_out = true;
                return false;
              }
            }
          }
          break;
        case 'only':
          //get the vars to use in the 'only' lock
          var only_fields = [];
          $.each(limit_vals.slice(1), function(dex, val){only_fields[only_fields.length] = val;});
          //get all the current data_key vars from the elements already in place
          var key_fields = {};
          load_target.children('.g365_form').each(function(){
            var this_fieldset = $(this);
            //use fieldset id to create unique reference
            var this_fieldset_id = this_fieldset.attr('id');
            key_fields[this_fieldset_id] = {};
            //collect all the data_keys for this fieldset
            $( '[data-g365_data_key]', this_fieldset).each(function(){
              var this_key = $(this);
              key_fields[this_fieldset_id][this_key.attr('data-g365_data_key')] = this_key.val();
            });
          });
          //loop through all the established fieldsets to see if we have any full matches
          $.each(key_fields, function(set_id, set_vars){
            var matched_sets = [];
            //loop through each datapoint for the fieldset and see if it matches something in contributions_compile.data
            if( contributions_compile !== null) $.each(only_fields, function(dex, key_id){if( typeof set_vars[key_id] !== 'undefined' && set_vars[key_id] == contributions_compile.data[key_id] ) matched_sets[matched_sets.length] = key_id;});
            //the matched set has the same number of entries as the locking set then we have a full match and we need to stop
            if( matched_sets.length === only_fields.length ) {
              target.parent().append('<p id="' + (target_id + '_error_message') + '" class="error">Cannot add. Duplicate entries not allowed.</p>');
              break_out = true;
              return false;
            }
          });
          break;
      }
    });
    if( break_out ) return false;
  }
  //build the field_group var
  var field_group = type;
  if( contributions_compile !== null) $.each(contributions_compile.data, function(key, val){
    //if the set contains a 'new' value, ask for creation by setting the id to null
    if( val === 'new' ) the_val = null;
    //make a unique trail to each element out of the presets that we are assigning
    if( val !== "0" && $.isNumeric(val) && key !== id_val ) field_group += '_' + val;
  });
  //if the val is unusable create a unique id based on the current timestamp
  if( the_val === null ) {
//     field_group += '_' + (new Date().valueOf());
    if( target.is('select') ) target.slideUp();
  } else {
    the_val = [the_val];
  }
  //create the data set to get our form put together
  var form_data = {
    query_type : type,
    id : the_val,
    field_group: field_group,
    go_flat: false,
    template_format: (typeof target.attr('data-g365_form_template') === 'undefined') ? 'form_template_min' : target.attr('data-g365_form_template'),
    contributions: (( contributions_compile !== null) ? contributions_compile.data : null)
  }
  //if we have an origin to push back to include it
  if( typeof origin_id !== 'undefined' ) form_data.field_origin_id = origin_id;
  //if we are going flat, set the variable
  form_data.go_flat = target.attr('data-g365_base_id');
  if( typeof form_data.go_flat !== 'undefined' ) form_data.go_flat = ( form_data.go_flat === 'self' ) ? field_group : form_data.go_flat;
  $.when(g365_build_template_from_data( form_data ))
  .done( function(form_template_message){
    //clear any errors before we write to the set
    load_target.children('.error, .success').remove();
    //try to close all other fieldsets
    //if the other fieldsets didn't close, it's probably because there is an error somewhere in the fields. handle that here
    var closed = $('.change-title.g365-expand-collapse-fieldset' , load_target).trigger('click', 'closed');
    var form_new_loaded = '';
    //set a handle for the new form element, add presets, and attach the new form
    if( typeof form_template_message === 'object' ) {
      if( form_template_message.enabled === null && form_template_message.disabled === null ) {
        form_new_loaded = $( '<p>No data found.</p>' ).prependTo( load_target );
      } else {
        if( form_template_message.disabled === null ) {
          g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( load_target );
        } else if( form_template_message.enabled === null ) {
          g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( load_target );
        } else {
          //attach the new form for both disabled and enabled data points
          var enabled_div = $('.g365_enabled_data', load_target);
          if( enabled_div.length === 0 ) {
            load_target.html( g365_add_presets( $( form_template_message.disabled ), contributions_compile ) );
            load_target.html( g365_add_presets( $( form_template_message.enabled ), contributions_compile ) );
          } else {
            g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( $('.g365_enabled_data', load_target) );
            g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( $('.g365_disabled_data', load_target) );
          }
        }
        form_new_loaded = load_target;
      }
    } else {
      //attach the new form
//       form_new_loaded = $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
      form_new_loaded = g365_add_presets( $( form_template_message ), contributions_compile ).prependTo( load_target );
    }
    //add the connector for the dropdown if we have one
    if( dropdown_select !== false ){
      form_new_loaded.attr('data-g365_dropdown_key', dropdown_id).attr('data-g365_dropdown_target', dropdown_select.parent().attr('id'));
      dropdown_select.prop('disabled', true);
      var drop_parent = dropdown_select.parent();
      var drop_enabled = drop_parent.children('option:enabled:not([value=""])');
      if(drop_parent.attr('data-g365_auto_advance') == 'true') {
        if( drop_enabled.length > 0 ) drop_enabled.first().prop('selected', true);
      } else {
        drop_parent.val('');
      }
      drop_parent.change();
      if( drop_enabled.length === 0 ) dropdown_select.closest('.form-holder').children(':not(.primary-form)').addClass('hide');
//       if( drop_enabled.length === 0 ) dropdown_select.closest('.form-holder').children(':not(.primary-form)').addClass('hide').parent().parent().children(':not(.form-holder)').addClass('hide');
      revert_dependance_chain( $( '#' + drop_parent.attr('data-g365_deps_start') ) );
    } else if( dropdown !== false ) {
//       dropdown.change();
    }
    if( typeof target.attr('data-g365_toggle_parent') !== 'undefined' ) {
      target.closest('.form-holder').children(':not(.primary-form)').addClass('hide');
      if( target.attr('data-g365_toggle_parent') !== 'true' ) $( '#' + target.attr('data-g365_toggle_parent') ).removeClass('hide');
    }
    //initialize form_data
    g365_form_start_up( form_new_loaded );
    //check to see if we should minify the formand if we should change the default afterwards
    if( target.attr('data-g365_form_load_min') === 'true' ) form_new_loaded.children('.form-collapse-title.g365-expand-collapse-fieldset').click();
    if( target.attr('data-g365_form_load_min') === 'true-false' ) {
      target.attr('data-g365_form_load_min', 'false');
      form_new_loaded.children('.form-collapse-title.g365-expand-collapse-fieldset').click();
    }
    if( typeof target.attr('data-g365_deps_start') !== 'undefined' ) {
      revert_dependance_chain( $( '#' + target.attr('data-g365_deps_start') ) );
    }
    //if this isn't nested show the submit buttons now that we have a form
    $( '#' + load_target_id + '_submit' ).show();
  });
}

function g365_sub_template_parser(data_key, data_id, data_val, form_data) {
  if( $.trim(data_val).charAt(1) === "'" ) data_val = data_val.replace(/'/g, '"');
  console.log('parser', data_key, data_id, data_val, form_data, g365_form_data.form[data_key]);
  data_val = $.parseJSON(data_val);
  if( typeof g365_form_data.form[data_key] === 'undefined') return 'No template hook found.';
  var sub_template = g365_form_data.form[data_key].form_template_input_item;
  var search_count = (((sub_template.match(/g365_livesearch_input/g) || [1] ).length)-1);
  var sub_collection = '';
  //find all the variables in the template
  var sub_contentVars = sub_template.match(/{{(.+?)}}/g);
  //call back function to eliminate duplicates
  sub_contentVars = sub_contentVars.filter( function (value, index, self) { return self.indexOf(value) === index; } );
  $.each(data_val, function(sub_id, sub_data){
    if( $.isPlainObject(sub_data) ) {
      if( typeof g365_form_data[data_key][sub_id] === 'undefined' ) g365_form_data[data_key][sub_id] = [];
      $.each(sub_data, function(sub_data_key, sub_datapoint){
        g365_form_data[data_key][sub_id][sub_data_key] = sub_datapoint;
      });
      if( typeof g365_form_data[data_key][sub_id]['id'] === 'undefined' ) g365_form_data[data_key][sub_id]['id'] = sub_id;
    }
    var go_flat = form_data.go_flat;
    //if the form is an init, then just use the query type
    switch( true ) {
      case (form_data.template_format === "form_template_init"):
        go_flat = form_data.query_type;
        //if the go_flat and the field_group are the same switch out the correct id for the '{{id}}'s 
        break;
      case (form_data.go_flat === form_data.field_group):
        go_flat = form_data.go_flat.replace('{{id}}', data_id); //.replace('{{field-set-id}}', data_id);
        break;
      case (form_data.field_group.indexOf('_{{id}}') !== false):
        go_flat = form_data.field_group.replace('_{{id}}', ('_' + data_id));
    }
    var sub_item = sub_template.replace(new RegExp('{{field-set-id-flat}}', 'g'), go_flat);
    if( typeof data_id === undefined || data_id === null || data_id === 'null' ) {
      sub_item = sub_item.replace(new RegExp('{{field-set-id}}', 'g'), form_data.query_type);
    } else {
      sub_item = sub_item.replace(new RegExp('{{field-set-id}}', 'g'), (form_data.query_type + '_' + data_id + (( form_data.field_group === form_data.query_type + '_{{id}}' ) ? '_' + sub_id : '')));
    }
    $.each(sub_contentVars, function(var_dex, var_name_brackets){
      var var_name = var_name_brackets.slice(2,-2);
      if( typeof g365_form_data[data_key][sub_id][var_name] !== 'undefined' ) sub_item = sub_item.replace(new RegExp(var_name_brackets, 'ig'), g365_form_data[data_key][sub_id][var_name]);
    });
    sub_item = sub_item.replace(/{{(.+?)}}/g, '');
    sub_collection += sub_item;
  });
  return sub_collection;
}

//assemble forms
//form_data = {query_type: "rosters", id: null, field_group: "search_rosters_21527434877266", template_format: "form_part_name", field_origin_id: "some_id", dropdown_target: "event_id"}
function g365_build_template_from_data( form_data ) {
  function g365_build_template_from_data_proc(newContent, data) {
    //build a form from the data and set 
    var newContent_all = '';
    var items_enabled = '';
    var items_disabled = '';
    var newContent_part = newContent;
    //change the order if we need it.
    if( data !== null && data.length > 1 ) {
      switch( form_data.query_type ) {
        case 'ro_ed':
        case 'to_ed':
        case 'rosters_club_sl':
        case 'rosters_sl':
        case 'rosters_event':
        case 'rosters':
        case 'rosters_teams':
        case 'tournament_roster_admin':
          var data_sort = [];
          //reorder data
          $.each(g365_grade_key_order, function(dex, level){
            $.each(g365_division_key, function(division, division_name){
              $.each(data, function(data_dex, data_id){
                if(
                  (g365_form_data[form_data.query_type][data_id].division == division ||
                  (typeof g365_form_data[form_data.query_type][data_id].division === 'undefined' && division === '') ) &&
                  g365_form_data[form_data.query_type][data_id].level == level ) data_sort.push(data_id);
              });
            });
          });
          if( data_sort.length > 1 ) data = data_sort;
          break;
      }
    }
    if( data === null ) {
//       items_enabled = ;
      //create form for this specific data point
      newContent_part = newContent;
      $.each(form_data.contributions, function(data_key, data_val){
        if( data_val === null ) return;
        //need more work to integrate this feature
        //check for json and build the required section
        if( data_val.toString().match(/^(\[|\{)/) ) {
          data_val = g365_sub_template_parser(data_key, form_data.field_group, data_val, form_data);
        }
        newContent_part = newContent_part.replace(new RegExp('{{' + data_key + '}}', 'ig'), data_val.toString().trim());
      });
      items_enabled = newContent_part;
    } else {
      $.each(data, function(data_dex, data_id) {
        var data_arr = g365_form_data[form_data.query_type][data_id];
        if( typeof data_arr === 'string' ) {
          items_enabled += '<div class="error">' + data_arr + '</div>';
          return;
        }
        //create form for this specific data point
        newContent_part = newContent;
        if( typeof g365_form_data.form[form_data.query_type].additional_parts !== 'undefined' ) {
          //get array of parts
          var parts = g365_form_data.form[form_data.query_type].additional_parts.split(',');
          $.each( parts, function(dx, val){ newContent_part = newContent_part.replace('{{' + val + '}}', ((typeof g365_form_data.form[form_data.query_type][val + data_arr.event] !== 'undefined') ? g365_form_data.form[form_data.query_type][val + data_arr.event] : '')); });
        }
        //add in values from the contributions (specifically tournament level locking vars)
        $.extend(data_arr, form_data.contributions);
        $.each(data_arr, function(data_key, data_val){
          if( data_val === null ) return;
          //check for json and build the required section
          if( data_val.toString().match(/^(\[|\{)/) ) {
            data_val = g365_sub_template_parser(data_key, data_id, data_val, form_data);
          }
          newContent_part = newContent_part.replace(new RegExp('{{' + data_key + '}}', 'ig'), data_val.toString().trim());
        });
        g365_form_data[form_data.query_type][data_id].form_template = newContent_part;
        if( g365_form_data[form_data.query_type][data_id].enabled !== '0' ) {
          items_enabled += newContent_part;
        } else {
          items_disabled += newContent_part;
        }
      });
    }
    if( items_enabled === '' ) items_enabled = null;
    if( items_disabled === '' ) items_disabled = null;
    return {'enabled': items_enabled, 'disabled': items_disabled};
	}
  console.log('build template', form_data);
	//to support the potential of ajax for getting data_set
	var defObj = $.Deferred();
	//get required content string, with a default
	var newContent = ( typeof form_data.template_format !== 'undefined' ) ? g365_form_data.form[form_data.query_type][form_data.template_format] : g365_form_data.form[form_data.query_type].form_template;
	//make sure that the template is there
	if( typeof newContent !== 'string' || newContent.length === 0 ) return '<p class="error">Form Template malformation. Please try your request again.</p>';
	//return if we don't have a unique handle for the field set
	if( typeof form_data.field_group !== 'string' || form_data.field_group === '' ) return '<p class="error">Need unique identifier for field set.</p>';
  //see if we have unique identifiers for the builder
  switch( form_data.query_type ) {
    case "rosters_teams":
    case "rosters_teams_admin":
      //if the event id is zero hide the level selector
      if( typeof form_data.contributions !== 'undefined' && form_data.contributions.event_id == 0 ) form_data.contributions.division_selector_options_hide = 'hide';
      if( form_data.query_type === form_data.field_group ) {
        //if there are multiple ids add the hook for the loop to use
        form_data.field_group = ( $.isArray( form_data.id ) ) ? form_data.field_group + '_{{field-set-id}}' : form_data.field_group;
        break;
      }
    default:
      //add the hook for the loop to use
      form_data.field_group = ( $.isArray( form_data.id ) ) ? form_data.field_group + '_{{id}}' : form_data.field_group;
  }
  //if we are going to have to manage sub items, add a hook for the parent id
  if( form_data.go_flat && $.isArray( form_data.id ) ) {
    //if the go_flat isn't set then add the id hook
    if( form_data.go_flat === form_data.query_type ) form_data.go_flat = form_data.field_group;
  } else {
    form_data.go_flat = '{{field-set-id}}';
  }
  //if we have a base id, use that
  newContent = newContent.replace(new RegExp('{{field-set-id-flat}}', 'g'), ( form_data.go_flat ) ? form_data.go_flat : form_data.field_group);
  //add unique identifier to new form template
  newContent = newContent.replace(new RegExp('{{field-set-id}}', 'g'), form_data.field_group);
  //if we need to go flat switch the form-id
	if( typeof form_data.field_origin_id !== 'undefined' ) newContent = newContent.replace(new RegExp('{{field-set-id-origin}}', 'g'), form_data.field_origin_id);
  //if we need to add a locker target, do so
	if( typeof form_data.dropdown_target !== 'undefined' ) newContent = newContent.replace(new RegExp('{{dropdown_target}}', 'g'), form_data.dropdown_target);
// 	//find all variables that we need to replace
// 	var contentVars = newContent.match(/{{(.+?)}}/g);
// 	//call back function to make eliminate duplicates
// 	contentVars = contentVars.filter( function (value, index, self) { return self.indexOf(value) === index; } );
  //if we don't have an id, get a new form if possible
  var form_new_or_get = ( form_data.id === null ) ? true : false;
  //if there are any global form presets add them now
  if( typeof g365_form_data.form[form_data.query_type]['form_defaults'] !== 'undefined' ) $.each( g365_form_data.form[form_data.query_type]['form_defaults'], function(preset_name, preset_val){ newContent = newContent.replace('{{' + preset_name + '}}', preset_val); });
  //these types of forms can take additional build conditions, so change the flag so we pull data from the server
  if(
    (form_data.query_type === 'pl_ev' || 
     form_data.query_type === 'player_event' || 
     form_data.query_type === 'pl_cert' || 
     form_data.query_type === 'pl_cert_sl' || 
     form_data.query_type === 'camps' || 
     form_data.query_type === 'passport' || 
     form_data.query_type === 'dcp_player_registration' || 
     form_data.query_type === 'rosters_teams_admin' 
    ) && form_data.contributions !== null ) form_new_or_get = false;
  //switch for getting data from the server or just using the data generated from the init form
  if( form_new_or_get ) {
    //do a preliminary integration with the contributions
    if( form_data.contributions !== null ) $.each( form_data.contributions, function(cont_name, cont_val){
      //if it's a special data type process further
      if( cont_val !== null && cont_val.toString().match(/^(\[|\{)/) ) {
        cont_val = g365_sub_template_parser(cont_name, form_data.id, cont_val, form_data);
      }
      newContent = newContent.replace(new RegExp('{{' + cont_name + '}}', 'g'), ((cont_val !== null) ? cont_val.toString().trim() : ''));
    });
		//regex replace all template variables
		defObj.resolve(newContent.replace(/{{(.+?)}}/g, ''));
	} else {
    var g365_get_data = false;
    //see if we already have all the data
    $.each(form_data.id, function( dex, val ){ if( typeof g365_form_data[form_data.query_type][val] === 'undefined' ) g365_get_data = true; });
    if( ( form_data.query_type === 'rosters_teams_admin' ) && form_data.contributions !== null ) g365_get_data = true;
    if( g365_get_data === true ) {
      //use the variables from the template to make a query and get the players existing data
      var data_set = {};
      data_set[form_data.query_type] = {
        proc_type : 'get_data',
        ids : form_data.id,
        contributions : form_data.contributions
      }
      //added for rosters_teams_admin to substitue for the id
      if( ( form_data.query_type === 'rosters_teams_admin' ) && form_data.id === null ) {
        data_set[form_data.query_type].field_group = form_data.field_group;
//         form_data.id = form_data.field_group;
      }
      
      g365_serv_con( data_set )
      .done( function(response) {
        console.log('build template response', response);
        if (response.status === 'success') {
          $.each(response.message, function(data_type, data_set){
            //set the base object if we don't already have one
            if( typeof g365_form_data[data_type] == 'undefined' ) g365_form_data[data_type] = {};
            //loop through local global variables replacing each with data from the query
            $.each(data_set, function(data_id, data_arr){
              //set the global object if it isn't set already
              if( typeof g365_form_data[data_type][data_id] == 'undefined' ) g365_form_data[data_type][data_id] = {};
              //log the string if we have a string, probably means error
              if( typeof data_arr === 'string' ) {
                g365_form_data[data_type][data_id] = data_arr;
                return;
              }
              //add each key value pair to the global set
              $.each(data_arr, function(data_key, data_val){
                g365_form_data[data_type][data_id][data_key] = data_val;
                //added for rosters_teams_admin to substitue for the id
                if( ( form_data.query_type === 'rosters_teams_admin' ) && data_key === 'player_rosters' ) form_data.contributions.player_rosters = data_val;
              });
            });
          });
          //now that we have the whole data set, compile the forms
          newContent = g365_build_template_from_data_proc(newContent, form_data.id);
        } else {
          console.log('false');
          newContent = response.message;
        }
      })
      .fail( function(response) {
        console.log('error', response);
        newContent = response.responseText;
      })
      .always( function(response) {
        if( typeof newContent === 'object' ) {
          $.each(newContent, function(data_string, data_content){
            if( data_content === null ) return;
            newContent[data_string] = data_content.replace(/{{(.+?)}}/g, '');
          });
          //if we don't have any disabled entries, just simplify the response
          if( Object.keys(newContent).length == 2 && newContent.disabled === null ) newContent = newContent.enabled;
          defObj.resolve(newContent);
        } else {
          defObj.resolve(newContent.replace(/{{(.+?)}}/g, ''));
        }
      });
    } else {
      //if we already had all the form data, put together the form.
      newContent = g365_build_template_from_data_proc(newContent, form_data.id);
      if( typeof newContent === 'object' ) {
        $.each(newContent, function(data_string, data_content){
          if( data_content === null ) return;
          newContent[data_string] = data_content.replace(/{{(.+?)}}/g, '');
        });
        if( Object.keys(newContent).length == 2 && newContent.disabled === null ) newContent = newContent.enabled;
        defObj.resolve(newContent);
      } else {
        defObj.resolve(newContent.replace(/{{(.+?)}}/g, ''));
      }
    }
  }
  return defObj.promise();
}

//handle form submits
function g365_handle_form_submit( form_event ){
	console.log('sge', form_event);
  form_event.preventDefault();
  var submitted_form = $(this);

	if( submitted_form.attr("action") ) {
		submitted_form.submit();
	}
	var target_form = $(this);
  $( "input[name='nds-pmd']", target_form ).remove();
  var field_type = target_form.attr('data-g365_type');
  if( typeof field_type === 'undefined' ) return '<p>Bad type.</p>';
  var target_form_id = target_form.attr('id');
	var newContent = '';
	var data_set = {};
  var locker = $("#" + target_form_id + "_wrap select[data-g365_additional_lock='" + target_form_id + "_data']");
  var lockers = locker.children('option');
  var lock_incomplete = false;
  submitted_form.addClass('submit_shield');
  if(lockers.length !== 0) lockers.each(function(){ var lock = $(this); if(lock.val() !== '' && lock.prop('disabled') !== true ) lock_incomplete = true; });
  if( lock_incomplete ) {
    var locker_parent = locker.parent();
    var locker_closest = locker.closest(':visible');
    locker_parent.children('.error').remove();
    locker_closest.children('.error').remove();
    if( locker.is(':visible') ) {
      locker_parent.append('<p id="' + (target_form_id + '_error_message') + '" class="error">Must use all entries.</p>');
      locker_parent.scrollView();
    } else {
      locker_closest.prepend('<p id="' + (target_form_id + '_error_message') + '" class="error">This section needs to be filled out.</p>')
      locker_closest.scrollView();
    }
    submitted_form.removeClass('submit_shield');
    return;
  }
  
  //if we are going to include a var about the button clicked - used for admin claiming form
  var temp_mods = '';
  if( document.activeElement.hasAttribute('data-g365_sender') ) temp_mods = $( '<input type="hidden" name="' + document.activeElement.getAttribute('name') + '" value="' + document.activeElement.getAttribute('data-g365_sender') + '">' ).prependTo(target_form);

  data_set[field_type] = {
		proc_type : 'proc_data',
		form_data : target_form.serialize()
	}
	if( submitted_form.attr("action") ) { submitted_form.submit();
		console.log('ggege', data_set);
		window.location.reload( submitted_form.attr("data-href") );
	}

  
  if( temp_mods !== '' ) temp_mods.remove();
  
  //keep track of error status
  var section_errors = false;
  //keep track of successful record ids
  var success_record_ids = [];
  var success_record_add = [];
  //try to submit form
  var temp2 = [];
  $.each(data_set[field_type].form_data.replace(new RegExp('%5B', 'g'), '[' ).replace(new RegExp('%5D', 'g'), ']' ).split('&'), function(dex, data){ data = data.split('='); temp2[data[0]] = data[1]; });
	g365_serv_con( data_set )
	.done( function(response) {
    console.log(response);
    if (typeof response.message === 'object') {
      //if we need a global setting for a success message
      //if (response.status === 'success') {}
      //loop through template variables replacing each with data from the query
      $.each(response.message, function(data_type, data_results) {
        var result_template = g365_form_data.form[data_type].form_template_result;
        //make sure that the template is there
        if( typeof result_template !== 'string' || result_template.length === 0 ) {
          newContent += '<p class="error">Form Result Template malformation for ' + data_type + '. Data write successful.</p>';
          section_errors = true;
          submitted_form.removeClass('submit_shield');    
          return;
        }
        //check for result data
        if( typeof data_results !== 'object' || $.isEmptyObject(data_results) ) {
          newContent += '<pclass="error">No results returned ' + data_type + '.</p>';
          section_errors = true;
          submitted_form.removeClass('submit_shield');    
          return;
        }
        //build the result block and update our object
        newContent += '<div><ul>';
        $.each(data_results, function(data_id, data_result){
          //set the class for the result message;
          var li_class;
          if(/success/i.test(data_result.message)) {
            //if we are successfull and need a redirect, please
            if( typeof data_result.redirect === 'string' && data_result.redirect !== '' ) {
              if( data_result.redirect === 'reload' ) {
                window.location.reload();
              } else {
                window.location.href=data_result.redirect;
              }
            }
            li_class = 'success';
            if( typeof data_result.wrapper_id !== 'undefined' ) {
              $('#' + data_result.wrapper_id).removeClass('error_wrapper');
              $('#' + data_result.wrapper_id + '_id' ).val(data_result.id);
            }
            success_record_ids[success_record_ids.length] = data_result.id;
            if( typeof data_result.passport !== 'undefined' ) success_record_add[data_result.id] = data_result.passport;
          } else {
            $('#' + data_id).addClass('error_wrapper');
            li_class = 'error';
            section_errors = true;
          }
          //if we have a proper id, feel free to update our internal data
          if( !isNaN(parseInt(data_id)) ) {
            if( typeof g365_form_data[data_type] == 'undefined' ) g365_form_data[data_type] = {};
            if( typeof g365_form_data[data_type][data_id] == 'undefined' ) {
              g365_form_data[data_type][data_id] = {};
              //also add the id to the form incase the user submits again.
              $('#' + data_result.wrapper_id + '_id').val(data_id);
            }
            $.each(data_result, function(data_key, data_val){
              g365_form_data[data_type][data_id][data_key] = data_val;
            });
            //process the result, error or success
            newContent += result_template.replace(new RegExp('{{li_class}}', 'g'), li_class ).replace(new RegExp('{{result_title}}', 'g'), g365_form_data[data_type][data_id].element_title).replace(new RegExp('{{result_status}}', 'g'), '<br>' + data_result.message);
          } else {
            //set the target
            //process the result, error or success
            newContent += result_template.replace(new RegExp('{{li_class}}', 'g'), li_class ).replace(new RegExp('{{result_title}}', 'g'), data_result.element_title).replace(new RegExp('{{result_status}}', 'g'), '<br>' + data_result.message);
          }
        });
        newContent += '</ul></p>';
        //if we are a nested form, push our results to the appropriate orgin fields and wrap up the nested form
        //we execute this after the above each block to get the form data object updated before we exit
        var origin_form_element_id = target_form.attr('data-target_field');
        var origin_form_element = $('#' + origin_form_element_id);
        if( origin_form_element.length ){
          //only evaluate the first entry, we only have one reference to pass data back to.
          var response_reference = data_results[$.map(data_results, function(value, key){return key;})[0]];
          //if we have success
          if( typeof response_reference.id !== 'undefined' ) {
            //then take the action of the origin field
            var origin_action = origin_form_element.attr( 'data-g365_action' );
            if( typeof origin_action !== 'undefined' ){
              switch( origin_action ) {
                case 'load_form':
                  //set the field to pull from
                  origin_form_element.val(response_reference.id).trigger('ajaxlivesearch:hide_result');
                  //build the form or line item
                  g365_build_form_from_data( origin_form_element );
                  break;
                case 'set_select':
                  //create and set the select drop down 
                  origin_form_element.append('<option value="' + response_reference.id + '" data-g365_short_name="' + (( typeof response_reference.name === 'undefined' ) ? '' : response_reference.name) + '">' + response_reference.element_title + '</option>');
                  origin_form_element.val(response_reference.id).change();
                  break;
                case 'select_data':
                  //set the vanity field for a livesearch
                  origin_form_element.val(response_reference.element_title).trigger('ajaxlivesearch:hide_result');
                  //set the proper target with the new id
                  var origin_form_element_target = $('#' + origin_form_element.attr('data-ls_target'));
                  origin_form_element_target.attr('data-g365_short_name', response_reference.element_title);
                  origin_form_element_target.attr('data-g365_additional_data', response_reference.level);
                  origin_form_element_target.val(response_reference.id).change();

                  //incase we have multi-level conditional
                  g365_manage_add_button( origin_form_element_target );
                  // if this is a select_data action with a click at the end, perform the click action upon successful data add
                  if( !section_errors && response_reference.id !== '' && typeof origin_form_element.attr('data-g365_select_click') === 'string' ) {
                    $('#' + origin_form_element.attr('data-g365_select_click')).attr('data-g365_form_load_min', 'true-false').click();
                  }
                  break;
                case 'add_result':
                  $('<div class="form_message">' + newContent + '</div>').insertBefore(origin_form_element);
                  break;
              }
            }
            //open the origin field parent or element itself if it's a select
            if( origin_form_element.is('select') ) {
              origin_form_element.slideDown();
            } else if( origin_form_element.is('div') ) {
              origin_form_element.removeClass( 'hide' );
            } else {
              origin_form_element.parent().slideDown();
            }
            //delete the form_event
            target_form.parent().fadeOut(400, 'swing', function(){
              
              target_form.parent().remove();
              //scroll to the target form field
              origin_form_element.scrollView();
            });
            submitted_form.removeClass('submit_shield');    
            return;
          }
        } else {
          g365_form_message_clear( target_form );
        }
      });
		} else {
			console.log('false');
			newContent = '<p>' + response.message + '</p>';
		}
	})
	.fail( function(response) {
		console.log('error', response);
		newContent = '<p>' + response.responseText + '</p>';
	})
	.always( function(response) {
    var message_wrap = target_form.attr('id');
    if( message_wrap.endsWith('_fieldset') ) message_wrap = message_wrap.substring(0, message_wrap.length-9);
		var message_wrapper = $('#' + message_wrap + '_message', target_form);
    if( message_wrapper.length ) message_wrapper.html(newContent).removeClass('hide').trigger('result_complete', { 'error_status': section_errors, 'result_ids': success_record_ids, 'result_add': success_record_add });
    if( section_errors ) {
      message_wrapper.scrollView();
      submitted_form.removeClass('submit_shield');    
      return 'return_error';
    }
    submitted_form.removeClass('submit_shield');
  	return 'return_success';
	});
  return 'whaaaaa...';
}

//temp function to force rebuild, please remove if you are seeing this.
function non_sense(){
  console.log('none');
  var tensor = 'hello';
  return tensor;
}

function g365_field_format_lock( target, lock_type ) {
  if( target instanceof jQuery ) {
    var target_val = target.val();
    var target_val_new = '';
    if( target_val === '' ) return;
    switch(lock_type) {
      case 'tel':
//         // Remove non-numeric characters
//         var digits = target_val.replace(/\D/g, '');

//         // Extract country code if present
//         var countryCode = '';
//         if (digits.length > 10) {
//             // Determine country code length
//             var countryCodeLength = digits.length - 10;
//             countryCode = digits.substr(0, countryCodeLength);
//             digits = digits.substr(countryCodeLength);
//         }

//         // Ensure there are exactly 10 digits left for the phone number
//         digits = digits.substr(0, 10);

//         // Format the phone number
//         var formatted_val = '';
//         if (countryCode !== '') {
//             formatted_val = countryCode + '-';
//         }

//         if (digits.length > 3) {
//             formatted_val += digits.substr(0, 3) + '-';
//             if (digits.length > 6) {
//                 formatted_val += digits.substr(3, 3) + '-';
//                 formatted_val += digits.substr(6, 4);
//             } else {
//                 formatted_val += digits.substr(3);
//             }
//         } else {
//             formatted_val += digits;
//         }
        target_val = target_val.replace(/[^\d]/g,'').substring(0,10);
        target_val_new = target_val.substr(0, 3);
        if( target_val.length >= 4 ) target_val_new += '-' + target_val.substr(3, 3);
        if( target_val.length >= 7 ) target_val_new += '-' + target_val.substr(6);
        break;
      case 'date':
        var current_d = new Date();
        target_val = target_val.replace(/[^\d]/g,'').substring(0,8);
        target_val_new = ((parseInt(target_val.substr(0, 2)) > 12) ? '12' : target_val.substr(0, 2));
        if( target_val.length >= 3 ) target_val_new += '-' + ((parseInt(target_val.substr(2, 2)) > 31) ? '31' : target_val.substr(2, 2));
        if( target_val.length >= 5 ) target_val_new += '-' + ((parseInt(target_val.substr(4)) > current_d.getFullYear()) ? current_d.getFullYear() : target_val.substr(4));
        break;
    }
    target.val( target_val_new );
  }
}

//attach to input, controls select elements, (handles division limiting (gteq), future support for lteq, gt, lt)
function g365_addition_limiter( limiter ){
  var to_limit = $('#' + limiter.attr('data-g365_additional_target_limit'));
  var the_limit = limiter.attr('data-g365_additional_data');
  if( to_limit.length !== 1 ) return;
  if( typeof the_limit === 'undefined' ) the_limit = null;
  //loop through and release or disable
  $('option', to_limit).each(function(){
    var this_option = $(this);
    this_option.prop('disabled', false);
    if( parseInt(the_limit) > parseInt(this_option.val()) ) this_option.prop('disabled', true);
  });
  //if we have a link, then the field is hidden and we need to select the
  if( limiter.attr('data-g365_link_target') === 'hide' ) {
    to_limit.val( (the_limit === null) ? '' : the_limit );
    g365_manage_add_button( to_limit );
  }
}

//update and validate the forms dataset at the top and in the fieldset sections
function g365_manage_cart_form( target, type, items ) {
  //build/rebuild the drop down
  switch( type ) {
    case 'rosters_event':
    case 'rosters_teams':
    case 'rosters':
      //see if we have existing fieldsets to manage
      var secondary_check_target = $('#' + target.attr('data-g365_additional_lock'));
      var secondary_ele_list = {};
      var secondary_ele_list_unassigned = {};
      //check the already created options to see if we need to call out a fieldset.
      if( secondary_check_target.length === 1 ) secondary_check_target.children('div').each(function(dex){
        //evaluate all elements to make sure the we have everything accounted for, or mark it and disable items
        var secondary_target = $(this);
        var secondary_target_key = secondary_target.attr('data-g365_dropdown_key');
        //if we don't have a target set, set them here.
        if( secondary_target.attr('data-g365_dropdown_target') !== target.attr('id') ) secondary_target.attr('data-g365_dropdown_target', 'event_id');
        //if we don't have a key set, set them here.
        if( typeof secondary_target_key === 'undefined' || secondary_target_key === '' ) {
          //create a temp key for the entry
          var temp_secondary_target_key = $('#' + secondary_target.attr('id') + '_event_id').attr('data-g365_short_name') + ' | un' + dex;
          //try to assign the target and key
          secondary_target.attr('data-g365_dropdown_key', temp_secondary_target_key);
          secondary_target_key = temp_secondary_target_key;
        }
        secondary_ele_list[secondary_target_key] = {origin: secondary_target, key: secondary_target_key};
      });
      //start to create the event drop down
      target.html('');
//       target.append('<option value=""> -- Please Select</option>');
      var option_titles = {};
      //figure out what we got, loop through the provided dataset starting with top level product
      $.each(items, function(id_key, event_data){
        //then loop through the product versions, specially organized this way to support product variations
        $.each(event_data.vars, function(var_id_key, var_data){
          //if we don't have a linked event get out; we can't lock the form without that info
          if( var_data.event_id === 0 ) return;
          //we need a line item for every quantity
          for (i = 1; i <= var_data.qty; i++) {
            //if an event has divisions, add it on so we can get the options later
            var_data.event_divisions = ( var_data.event_divisions === 0 ) ? '' : var_data.event_divisions;
            //if we only have one line item, don't call it out, otherwise add a unique count to differentiate
            var count = ' | Team ' + i;
            //concat the option title and add it to a list
            var option_title = var_data.full_name + count;
            //if we have a match with a deployed fieldset disable this option, and remove it from the object so we can do some reconciliation with any left overs
            var deployed_set = '';
            if( typeof secondary_ele_list[option_title] !== 'undefined' ) {
              deployed_set = ' disabled';
              secondary_ele_list[option_title].origin.children('div').removeClass('form-disabled').prop('disabled', false);
              delete secondary_ele_list[option_title]; 
            }
            //write all options that we created
            var this_option = $( '<option value="' + var_data.event_id + '" data-g365_additional_data=\'' + ((typeof var_data.event_divisions === 'string') ? '' : JSON.stringify(var_data.event_divisions) ) + '\'' + deployed_set + '>' + option_title + '</option>' ).appendTo(target);
            //compile the option that haven't been used
            if( deployed_set === '' ) option_titles[option_title] = {origin: this_option, key: option_title, type: var_data.full_name, count: count};
          }
        });
      });
      //loop through any leftover fieldsets that don't have a designation and try to assign them
      $.each(secondary_ele_list, function(key, val){
        //extract the base key
        var base_key = key.substr(0, key.indexOf(' | '));
        //keep track of it's assignment status
        var unassigned = true;
        //see if we have any others to fall back on
        $.each(option_titles, function(option_key, option_vals){
          if( base_key === option_vals.type ) {
            //disable the new selection
            option_vals.origin.prop('disabled', true);
            //change the underlaying event short name
            $('#' + val.origin.attr('id') + '_' + option_vals.origin.parent().attr('id')  ).attr( 'data-g365_short_name', option_key ).change();
            //change the parent data reference
            val.origin.attr('data-g365_dropdown_key', option_key);
            //call title updaters, first inner form, then collapsed
            g365_form_section_expand_collapse( $('#' + val.origin.attr('id') + '_fieldset .change-title.g365-expand-collapse-fieldset'), 'close' );
            //delete this entry
            delete secondary_ele_list[key];
            //delete from the available options too
            delete option_titles[option_key];
            //set the assigment status so this fieldset doesn't get disabled
            unassigned = false;
            //since we have a match, exit this loop
            return false;
          }
        });
        //if we didn't get assigned, disable the fieldset and continue looping
        if( unassigned ) {
          $('.change-title.g365-expand-collapse-fieldset' , val.origin).trigger('click', 'closed');
          val.origin.children('div').addClass('form-disabled').prop('disabled', true);
          return;
        }
        val.origin.children('div').removeClass('form-disabled').prop('disabled', false);
      });
      var form_holder = target.closest('.form-holder').children(':not(.primary-form)');
      if( target.children('option:enabled:not([value=""])').length === 0 ) {
        form_holder.addClass('hide');
      } else if ( form_holder.first().hasClass('hide') ) {
        form_holder.removeClass('hide');
      }
      
      target.change();
      break;
    case 'pl_ev':
    case 'camps':
    case 'passport':
    case 'dcp_player_registration':
    case 'club_team':
      //set global var
      var event_pointer = null;
      switch(type) {
        case 'pl_ev':
          event_pointer = 'event_id_pm';
          break;
        case 'camps':
        case 'passport':
        case 'dcp_player_registration':
          event_pointer = 'event_id_cp';
          break;
        case 'club_team':
          event_pointer = 'event_id_ct';
          break;
      }
      //see if we have existing fieldsets to manage
      var secondary_check_target = $('#' + target.attr('data-g365_additional_lock'));
      var secondary_ele_list = {};
      var secondary_ele_list_unassigned = {};
      //check the already created options to see if we need to call out a fieldset.
      if( secondary_check_target.length === 1 ) secondary_check_target.children('div').each(function(dex){
        //evaluate all elements to make sure the we have everything accounted for, or mark it and disable items
        var secondary_target = $(this);
        var secondary_target_key = secondary_target.attr('data-g365_dropdown_key');
        //if we don't have a target set, set them here.
        if( secondary_target.attr('data-g365_dropdown_target') !== target.attr('id') ) secondary_target.attr('data-g365_dropdown_target', 'event_id_pm');
        //if we don't have a key set, set them here.
        if( typeof secondary_target_key === 'undefined' || secondary_target_key === '' ) {
          //create a temp key for the entry
          var temp_secondary_target_key = $('#' + secondary_target.attr('id') + '_' + event_pointer).attr('data-g365_short_name') + ' | un' + dex;
          //try to assign the target and key
          secondary_target.attr('data-g365_dropdown_key', temp_secondary_target_key);
          secondary_target_key = temp_secondary_target_key;
        }
        secondary_ele_list[secondary_target_key] = {origin: secondary_target, key: secondary_target_key};
      });
      //start to create the event drop down
      target.html('');
//       target.append('<option value=""> -- Please Select</option>');
      var option_titles = {};
      //figure out what we got, loop through the provided dataset starting with top level product
      $.each(items, function(id_key, event_data){
        //then loop through the product versions, specially organized this way to support product variations
        $.each(event_data.vars, function(var_id_key, var_data){
          //if we don't have a linked event get out; we can't lock the form without that info
          if( var_data[event_pointer] === 'undefined' || var_data[event_pointer] === 0) return;
          //we need a line item for every quantity
          for (i = 1; i <= var_data.qty; i++) {
            //if we only have one line item, don't call it out, otherwise add a unique count to differentiate
            var count = ' | Player ' + i;
            //concat the option title and add it to a list
            var option_title = var_data.full_name + count;
            //if we have a match with a deployed fieldset disable this option, and remove it from the object so we can do some reconciliation with any left overs
            var deployed_set = '';
            if( typeof secondary_ele_list[option_title] !== 'undefined' ) {
              deployed_set = ' disabled';
              secondary_ele_list[option_title].origin.children('div').removeClass('form-disabled').prop('disabled', false);
              delete secondary_ele_list[option_title];
            }
            //write all options that we created
            var this_option = $( '<option value="' + var_data[event_pointer] + '"' + deployed_set + '>' + option_title + '</option>' ).appendTo(target);
            //compile the option that haven't been used
            if( deployed_set === '' ) option_titles[option_title] = {origin: this_option, key: option_title, type: var_data.full_name, count: count};
          }
        });
      });
      //loop through any leftover fieldsets that don't have a designation and try to assign them
      $.each(secondary_ele_list, function(key, val){
        //extract the base key
        var base_key = key.substr(0, key.indexOf(' | '));
        //keep track of it's assignment status
        var unassigned = true;
        //see if we have any others to fall back on
        $.each(option_titles, function(option_key, option_vals){
          if( base_key === option_vals.type ) {
            //disable the new selection
            option_vals.origin.prop('disabled', true);
            //change the underlaying event short name // should be equal to event_pointer: option_vals.origin.parent().attr('id');
            $('#' + val.origin.attr('id') + '_' + event_pointer  ).attr( 'data-g365_short_name', option_key ).change();
            //change the parent data reference
            val.origin.attr('data-g365_dropdown_key', option_key);
            //call title updaters, first inner form, then collapsed
            g365_form_section_expand_collapse( $('#' + val.origin.attr('id') + '_fieldset .change-title.g365-expand-collapse-fieldset'), 'close' );
            //delete this entry
            delete secondary_ele_list[key];
            //delete from the available options too
            delete option_titles[option_key];
            //set the assigment status so this fieldset doesn't get disabled
            unassigned = false;
            //since we have a match, exit this loop
            return false;
          }
        });
        //if we didn't get assigned, disable the fieldset and continue looping
        if( unassigned ) {
          $('.change-title.g365-expand-collapse-fieldset' , val.origin).trigger('click', 'closed');
          val.origin.children('div').addClass('form-disabled').prop('disabled', true);
          return;
        }
        val.origin.children('div').removeClass('form-disabled').prop('disabled', false);
      });
      var form_holder = target.closest('.form-holder').children(':not(.primary-form)');
      if( target.children('option:enabled:not([value=""])').length === 0 ) {
        form_holder.addClass('hide');
      } else if ( form_holder.first().hasClass('hide') ) {
        form_holder.removeClass('hide');
      }
      target.change();
      break;
  }
  return;
}

//form init
function g365_form_start( target ) {
  //get types from the element
  var data_set = {};
  //function for processing the request data tree
  function g365_proc_start_data(e){
    //need the type var to send
    if( e === '' || e === null || typeof e === 'undefined' ) {
      console.log( 'Need proper form type variable.' );
      return false;
    }
    //if we have ids on the element, include them
    e = e.split(':::');
    //place we are writing ids
    var j = 'ids';
    e.forEach(function(el){
      //if its the first item, that's the type and we need to add it to the request
      if( el == e[0] ) {
        //if this is a preset, we need to add it differently
        if( el.indexOf('_preset') !== -1 ) {
          e[0] = el.substring(0, el.length-7);
          j = 'preset';
        }
        //if the type already exist don't create it again
        if ( typeof data_set[e[0]] === 'undefined' ) data_set[e[0]] = {
          proc_type: 'get_form',
          ids: [],
          preset: []
        };
        //continue the looping
        return;
      }
      data_set[e[0]][j].push(el);
    });
  }
  //function to write responses
  function g365_inner_form_start( error_message ) {
    function g365_inner_wrap_up( current_form ) {
      var current_form_eval = $('.form-holder', current_form);
      if( current_form_eval.length === 1 ) current_form = current_form_eval;
      g365_form_start_up( current_form );
      //after start-up considerations
      //if we need to start minimized
      if( current_form.parent().attr('data-g365_form_load_min') === 'true' ) current_form.find('.form-collapse-title.g365-expand-collapse-fieldset').click();
      //if we need to close up an init form on load 
      var toggle_target = $('a[data-g365_toggle_parent]', current_form);
      var load_target = $('#' + toggle_target.attr('data-g365_load_target') + '_data>*');
      if( toggle_target.length && load_target.length ) {
        $( '#' + toggle_target.attr('data-g365_load_target') + '_submit' ).show();
        current_form.children(':not(.primary-form)').addClass('hide');
        if( toggle_target.attr('data-g365_toggle_parent') !== 'true' ) $( '#' + toggle_target.attr('data-g365_toggle_parent') ).removeClass('hide');
      }
      //extra form helpers if needed
      $('.g365_bulk_add', '#g365_bulk_add_wrap').click( g365_bulk_add );
    }

    //parse the errors
    if( typeof error_message !== 'undefined' ) g365_form_data.error = error_message;
    //set a wrapper if we don't have one
    if( typeof g365_form_data.wrapper === 'undefined' ) g365_form_data.wrapper = $("<div id='g365_form_wrap' class='g365_form_wrap'></div>").insertBefore(target);
    //ensure there are not errors
    if( g365_form_data.error === '' ) {
      $.each( data_set, function( key, value ) {
        //if we have the right data_type, process
        if( typeof g365_form_details.items !== 'undefined' ) {
          var no_locker_skip = true;
          $.each( g365_form_details.items, function(cat_id, cat_data){ if( cat_data.type === key && cat_data.no_init !== true ) no_locker_skip = false; } );
          if( no_locker_skip ) return;
        }
        if( typeof g365_form_data.form[key] !== 'undefined' ) {
          var presets = null;
          var this_form_template = g365_form_data.form[key].form_template_init;
          //set presets if we get them from the init pull
          if( typeof g365_form_data.form[key].form_defaults === 'object' ) presets = g365_form_data.form[key].form_defaults;
          //set presets from the init script
          if( value.preset.length > 0 ) {
            $.each(value.preset, function(dex, preset_pair){
              var preset = preset_pair.split('::');
              if( preset.length !== 2 ) return;
              if( presets === null ) presets = {};
              $.extend(presets, g365_form_data.form[key][ preset[0] + '_' + preset[1] ]);
            });
          }
          //start here
          //if we have presets, hit it
          if( typeof presets === 'object' ) $.each( presets, function(preset_name, preset_val){ this_form_template = this_form_template.replace(new RegExp('\{\{' + preset_name + '\}\}','g'), preset_val); });
          //regex replace all template variables if they aren't accounted for with presets
          this_form_template = this_form_template.replace(/{{(.+?)}}/g, '');
          //add the base form for the type
          var current_form = $( this_form_template ).appendTo( g365_form_data.wrapper );
          //add reference to proper cat_id
          var cat_id_ref;
          //see what data we have to integrate with the base form
          $.each( g365_form_details.items, function(cat_id, cat_data){
            //if we have the right data_type, process
            if( cat_data.type !== key ) return;
            //set the reference for later init
            cat_id_ref = cat_id;
            //if we have replacement title switch it
            if( cat_data.title !== '' ) {
              var h1s = $('h1.section_title', current_form);
              if( h1s.length ) {
                $('<h3 class="section_title">' + cat_data.title + '</h3>').insertBefore( h1s );
                h1s.remove();
              } else {
                $('<h3 class="section_title">' + cat_data.title + '</h3>').insertBefore( current_form.first() );
              }
            } 
            //if we have g365_form_details to initialize, build the dropdowns
            //specific to data type
            switch( cat_data.type ) {
              case 'rosters_event':
              case 'rosters_teams':
              case 'rosters_teams_admin':
              case 'rosters':
                //setup references
                g365_form_data.form[key].locker = $('#event_id', current_form);
                //if it changes run the updater
                if( g365_form_data.form[key].locker.is( 'select' ) ) {

                  g365_form_data.form[key].locker.change(function(){
                    //fieldset reference
                    var caller = $(this);
                    //get previous selection text to compare
                    var pre_event = caller.data('pre_event');
                    //set the new previous text
                    caller.data( 'pre_event', caller.children('option:selected').text().substr(0, caller.text().indexOf(' | ')) );
                    //if the main selection hasn't changed, stop updating.
                    if( pre_event === caller.data('pre_event') ) return;
                    //otherwise rebuild the additional data
                    g365_manage_additional_data( caller ); //check logic: onChange overwrite previous handler
                    //and show the next step
                    $('#team_level').parent().show();
                    //get the level part from the selector
                    var target_level = caller.children('option:selected').text().match(/\d\d?U/);
                    //if we don't have a target level, default to unselected
                    if( target_level === null ) {
                      $('#team_level').val('').change();
                    } else {
                      //if we have a target level, auto select the level dropdown
                      target_level = target_level[0].slice(0,-1);
                      target_level = parseInt(target_level);
                      //if we have a usable number select the level value
                      if( !isNaN(target_level) ) {
                        $('#team_level').val(target_level).change();
                        $('#team_level').parent().hide();
                      }
                    }
                  });
                } else {
                  //add additional data if we have it
                  if( typeof g365_form_data.form[key].locker.attr('data-g365_additional_data') !== 'undefined' && typeof g365_form_data.form[key].locker.attr('data-g365_additional_target') !== 'undefined' ) g365_form_data.form[key].locker.change(function(){ g365_manage_additional_data( $(this) ); }).change();
                }
                break;
              case 'pl_ev':
              case 'club_team':
              case 'camps':
              case 'passport':
              case 'dcp_player_registration':
                //setup references
                switch( cat_data.type ) {
                  case 'pl_ev':
                    g365_form_data.form[key].locker = $('#event_id_pm', current_form);
                    break;
                  case 'club_team':
                    g365_form_data.form[key].locker = $('#event_id_ct', current_form);
                    break;
                  case 'camps':
                  case 'passport':
                  case 'dcp_player_registration':
                    g365_form_data.form[key].locker = $('#event_id_cp', current_form);
                    break;
                }
                //if it changes run the updater
                g365_form_data.form[key].locker.change(function(){
                  //fieldset reference
                  var caller = $(this);
                  //get previous selection text to compare
                  var pre_event = caller.data('pre_event');
                  //set the new previous text
                  caller.data( 'pre_event', caller.children('option:selected').text().substr(0, caller.text().indexOf(' | ')) );
                  //if the main selection hasn't changed, stop updating.
                  if( pre_event === caller.data('pre_event') ) return;
                });
                break;
            }
          });
          //add existing data if we have any
          if(value.ids.length) {
            //reorder ids if needed
            switch(key) {
              case 'rosters':
              case 'rosters_event':
              case 'rosters_teams':
              case 'rosters_teams_admin':
              case 'to_ed':
              case 'ro_ed':
                value.ids = value.ids.sort(function(a, b){return ((g365_form_data[key][a].level == g365_form_data[key][b].level) ? g365_form_data[key][a].division - g365_form_data[key][b].division : g365_form_data[key][a].level - g365_form_data[key][b].level)});
                break;
            }
            //the 'data-g365_dropdown_key' and the 'data-g365_dropdown_target' attributes need to be set for the locking (and subsequent roll up) to happen
            var form_data = {query_type: key, id: value.ids, field_group: key, go_flat: key};
            if( typeof g365_form_data.form[key].locker !== 'undefined' ) form_data.dropdown_target = g365_form_data.form[key].locker.attr('id');
            $.when(g365_build_template_from_data( form_data ))
            .done( function(form_template_message){
              //see what we got
              if( typeof form_template_message === 'object' ) {
                if( form_template_message.enabled === null && form_template_message.disabled === null ) {
                  $( '<p>No data found.</p>' ).prependTo( $('.form-holder>form>div:first-child', current_form) );
                } else {
                  if( form_template_message.disabled === null ) {
                    $('.form-holder>form>div:first-child', current_form).html($( form_template_message.enabled ));
                  } else if( form_template_message.enabled === null ) {
                    $('.form-holder>form>div:first-child', current_form).html($( form_template_message.disabled ));
                  } else {
                    //attach the new form for both disabled and enabled data points
                    var enabled_div = $('.g365_enabled_data', current_form);
                    if( enabled_div.length === 0 ) {
                      $( form_template_message.disabled ).prependTo( $('.form-holder>form>div:first-child', current_form) );
                      $( form_template_message.enabled ).prependTo( $('.form-holder>form>div:first-child', current_form) );
                    } else {
                      $( form_template_message.disabled ).prependTo( $('.form-holder>form>div:first-child .g365_disabled_data', current_form) );
                      $( form_template_message.enabled ).prependTo( $('.form-holder>form>div:first-child .g365_enabled_data', current_form) );
                    }
                  }
                }
              } else {
                //attach the new form
                $('.form-holder>form>div:first-child', current_form).html($( form_template_message ));
//                 $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
              }
              //handle setup and/or locking
              if( typeof g365_form_data.form[key].locker !== 'undefined' && g365_form_data.form[key].locker.is('select') && typeof cat_id_ref !== 'undefined' ) g365_manage_cart_form(g365_form_data.form[key].locker, key, g365_form_details.items[cat_id_ref].items);
              //if this we want the admin functionality add it here
//               if( g365_form_details.g365_admin === "true" ) {
//                 var data_table = '';
//                 switch(key) {
//                   case 'rosters':
//                   case 'rosters_event':
//                   case 'rosters_teams':
//                     data_table += '<table class="widefat"><thead><tr><th>Division</th><th>Team Name</th><th>Pool Name</th><th>Pool Number</th><th>Team Restrictions</th><th>Time Restriction</th><th>Contact</th><th>Email</th><th>Event</th><th>Price</th></tr></thead><tbody>';
//                     $.each(value.ids, function(val_dex, val_id){
//                       data_table += '<tr><td>' + g365_grade_key[g365_form_data[key][val_id].level] + ((typeof g365_form_data[key][val_id].division !== 'undefined') ? ' ' + g365_form_data[key][val_id].division : '') + '</td><td>' + g365_form_data[key][val_id].org_name + ' ' + g365_form_data[key][val_id].level + 'U' + ((typeof g365_form_data[key][val_id].team_name !== 'undefined') ? ' ' + g365_form_data[key][val_id].team_name : '') + '</td><td></td><td></td><td></td><td></td><td>' + g365_form_details.g365_user_name + '</td><td>' + g365_form_details.g365_user_email + '</td><td>' + g365_form_data[key][val_id].event_name + '</td><td>' + '[insert price]' + '</td></tr>';
//                     });
//                     data_table += '</tbody></table>';
//                     break;
//                   case 'club_team':
//                   case 'camps':
//                     data_table += '<table class="widefat"><thead><tr><th>Player</th><th>Age</th><th>Birthdate</th><th>Grade</th><th>Grad Class</th><th>Jersey Size</th></tr></thead><tbody>'; 
//                     $.each(value.ids, function(val_dex, val_id){
//                       data_table += '<tr><td>' + g365_form_data[key][val_id].first_name + ' ' + g365_form_data[key][val_id].last_name + '</td><td>' + g365_form_data[key][val_id].age + '</td><td>' + g365_form_data[key][val_id].birthday + '</td><td>' + g365_form_data[key][val_id].grade + '</td><td>' + g365_form_data[key][val_id].grad_year + '</td><td>' + g365_form_data[key][val_id].jersey_size + '</td></tr>';
//                     });
//                     data_table += '</tbody></table>';
//                     break;
//                   case 'pl_ev':
//                     data_table += '<table class="widefat"><thead><tr><th>Player</th><th>Profile Image</th><th>Birthdate</th><th>Birth Certificate</th><th>Graduation Class</th><th>Report Card</th><th>First Verified</th><th>Verify Now</th></tr></thead><tbody>'; 
//                     $.each(value.ids, function(val_dex, val_id){
//                       data_table += '<tr><td>' + g365_grade_key[g365_form_data[key][val_id].level] + ((g365_form_data[key][val_id].division != '') ? ' ' + g365_form_data[key][val_id].division : '') + '</td><td>' + g365_form_data[key][val_id].org_name + ' ' + g365_form_data[key][val_id].level + 'U ' + ((g365_form_data[key][val_id].team_name != '') ? ' ' + g365_form_data[key][val_id].team_name : '') + '</td><td></td><td></td><td></td><td></td><td>' + g365_form_details.g365_user_name + '</td><td>' + g365_form_details.g365_user_email + '</td><td>' + g365_form_data[key][val_id].event_name + '</td><td>' + '[insert price]' + '</td></tr>';
//                     });
//                     data_table += '</tbody></table>';
//                     break;
//                 }
//                 current_form.prepend(data_table);
//               }
              g365_inner_wrap_up( current_form );
            });
          } else {
            //locking
            if( typeof g365_form_data.form[key].locker !== 'undefined' && g365_form_data.form[key].locker.is('select') && typeof cat_id_ref !== 'undefined' ) {
              g365_manage_cart_form(g365_form_data.form[key].locker, key, g365_form_details.items[cat_id_ref].items);
            }
            g365_inner_wrap_up( current_form );
          }
        }
      });
    } else {
      g365_form_data.wrapper.html( g365_form_data.error );
    }
    if( typeof error_message !== 'undefined' ) return false;
    return true;
  }
  
  //check that the processing flag is set
  if( g365_script_anchor === false ) return "Missing global connector.";
  //make sure that we have an element to put the form in
  if( typeof target === 'undefined' && !g365_script_element.hasAttribute('data-g365_type') ) return false;
  target = ( typeof target === 'undefined' || $('#' + target).length !== 1 ) ? $(g365_script_element) : $('#' + target);
  //get the form types
  var target_type = target.attr('data-g365_type');
  if( typeof target_type === 'undefined' || target_type === '' ) return false;
  //setup vars
  if( typeof g365_form_data.form === 'undefined' ) g365_form_data.form = {};
  //set/reset error status/code
  g365_form_data.error = '';
  //set global user key if available
  if( typeof g365_sess_data !== 'undefined' && typeof g365_form_details.admin_key !== 'undefined' ) g365_sess_data.admin_key = g365_form_details.admin_key;
  //if we have the info, process it
  target_type.split('|').forEach(g365_proc_start_data);
  //if we don't have any types get out
  if( Object.keys(data_set).length === 0 ) if( g365_inner_form_start("Error parsing form types.") === false ) return false;
  //get the init presets
  var target_preset = target.attr('data-g365_init_pre');
  //if we have init presets, add them
  if( typeof target_preset !== 'undefined' && target_preset !== '' ) target_preset.split('|').forEach(g365_proc_start_data);
  //add global settings if needed
  var settings = {};
  //style switch for external domains, styles never load on g365 domains
  if( g365_script_element.dataset.g365_styles == 'false' ) settings.styles = 'false';
  //settings default value
  if( Object.keys(settings).length === 0 ) settings = null;
  //get the form templates
  console.log('j', data_set);
  g365_serv_con( data_set, settings )
  .done( function(response) {
    console.log('p', response);
    if (response.status === 'success') {
      //loop through response and selectively add data to our tree
      $.each( response.message, function( type_key, type_values ) {
				if( typeof g365_form_data.form[type_key] === 'undefined' ) g365_form_data.form[type_key] = {};
				if( typeof g365_form_data[type_key] === 'undefined' ) g365_form_data[type_key] = {};
        $.each( type_values, function( values_key, value ) {
          if( $.isNumeric(values_key) ) {
            g365_form_data[type_key][values_key] = value;
          } else {
  	  			g365_form_data.form[type_key][values_key] = value;
          }
  			});
			});
      //possibly perform another check here
    } else {
      console.log('false', response);
      g365_form_data.error = response.message;
    }
  })
  .fail( function(response) {
    console.log('error', response);
    g365_form_data.error = response.responseText;
  })
  .always( function(response) {
    //start here to add the hook for after the form initializes
    if( g365_inner_form_start() && window.g365_func_wrapper.end.length > 0 ) window.g365_func_wrapper.end.forEach( function(func){ func.name.apply(null, func.args); });
    if( typeof response.style !== 'undefined' ) g365_form_data.wrapper.prepend( response.style );
  });
  return 'initalized.';
}
if( typeof g365_form_details !== 'undefined' ) window.g365_func_wrapper.sess[window.g365_func_wrapper.sess.length] = ( Object.keys(g365_form_details.items).length > 0 ) ?  {name : g365_form_start, args : [g365_form_details.wrapper_target]} : {name : g365_form_start, args : ['g365_form_options_anchor']};
