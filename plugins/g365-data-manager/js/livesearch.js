$.fn.ajaxlivesearch = function (options) {
		/**
		 * Start validation
		 */
		if (options.loaded_at === undefined) {
				throw 'loaded_at must be defined';
		}

		if (options.token === undefined) {
				throw 'token must be defined';
		}
		/**
		 * Finish validation
		 */
		var ls = {
				url: options.target_url + "livesearch/",
				// This should be the same as the same parameter's value in config file
				form_anti_bot: "ajaxlivesearch_guard",
				cache: false,
				/**
				 * Beginning of classes
				 */
				form_anti_bot_class: "ls_anti_bot",
				footer_class: "ls_result_footer",
				next_page_class: "ls_next_page",
				previous_page_class: "ls_previous_page",
				page_limit: "page_limit",
				result_wrapper_class: "ls_result_div",
				result_class: "ls_result_main",
				container_class: "ls_container",
				pagination_class: "pagination",
				form_class: "search",
				loaded_at_class: "ls_page_loaded_at",
				token_class: "ls_token",
				current_page_hidden_class: "ls_current_page",
				current_page_lbl_class: "ls_current_page_lbl",
				last_page_lbl_class: "ls_last_page_lbl",
				total_page_lbl_class: "ls_last_page_lbl",
				page_range_class: "ls_items_per_page",
				page_ranges: [0, 5, 10],
				page_range_default: 5,
				navigation_class: "navigation",
				arrow_class: "ls-arrow",
				table_class: "",
				/**
				 * End of classes
				 */
				slide_speed: "fast",
				type_delay: 350,
				max_input: 20,
				min_chars_to_search: 0
		}

		ls = $.extend(ls, options);

		/**
		 * Remove footer, add border radius to bottom right and left
		 *
		 * @param footer
		 * @param result
		 */
		function remove_footer(footer, result) {
				footer.hide();
				// add border radius to the last row of the result
				result.find("table").addClass("border_radius");
		}

		/**
		 * Add footer, and remove border radius from bottom right and left
		 *
		 * @param footer
		 * @param result
		 */
		function show_footer(footer, result) {
				// add border radius to the last row of the result
				result.find("table").removeClass("border_radius");
				footer.show();
		}

		/**
		 * Return minimum value of
		 *
		 * @param page_range
		 */
		function get_minimum_option_value(page_range) {
				var minimumOptionValue;
				var i;
				var all_options = page_range.find('option');
				for (i = 0; i < all_options.length; i += 1) {
						if (minimumOptionValue === undefined && parseInt(all_options[i].value) !== 0) {
								minimumOptionValue = all_options[i].value;
						} else {
								if (parseInt(all_options[i].value) < parseInt(minimumOptionValue) && parseInt(all_options[i].value) !== 0) {
										minimumOptionValue = all_options[i].value;
								}
						}
				}

				return minimumOptionValue;
		}

		/**
		 * Return the relevant element of the form
		 *
		 * @param form
		 * @param key
		 * @param options
		 * @returns {*}
		 */
		function getFormInfo(form, key, options) {
				if (form === undefined || options === undefined) {
						throw 'Form or Options is missing';
				}

				var find = '.' + options.current_page_hidden_class;

				switch (key) {
						case 'result':
								return form.find('.' + options.result_wrapper_class);
						case 'footer':
								return form.find('.' + options.footer_class);
						case 'arrow':
								return form.find('.' + options.arrow_class);
						case 'navigation':
								return form.find('.' + options.navigation_class);
						case 'current_page':
								return form.find(find);
						case 'current_page_lbl':
								return form.find('.' + options.current_page_lbl_class);
						case 'total_page_lbl':
								return form.find('.' + options.total_page_lbl_class);
						case 'page_range':
								return form.find('.' + options.page_range_class);
						default:
								throw 'Key: ' + key + ' is not found';
				}
		}

		/**
		 * Slide up the result
		 *
		 * @param result
		 * @param options
		 */
		function hide_result(result, options) {
				result.slideUp(options.slide_speed);
		}

		/**
		 * Slide down the result
		 *
		 * @param result
		 * @param options
		 */
		function show_result(result, options) {
				result.slideDown(options.slide_speed);
		}

		/**
		 * Get the search input object (not just its value)
		 *
		 * @param search_object
		 * @param form
		 * @param options
		 * @param bypass_check_last_value
		 * @param reset_current_page
		 */
		function search(search_object, form, options, bypass_check_last_value, reset_current_page) {
				var result = getFormInfo(form, 'result', options);

				if ($.trim(search_object.value).length && $.trim(search_object.value).length >= options.min_chars_to_search) {
						/**
						 * If the previous value is different from the new one perform the search
						 * Otherwise ignore that. This is useful when user change cursor position on the search field
						 */
						if (bypass_check_last_value || search_object.latest_value !== search_object.value) {
								if (reset_current_page) {
										var current_page = getFormInfo(form, 'current_page', options);
										var current_page_lbl = getFormInfo(form, 'current_page_lbl', options);

										// Reset the current page (label and hidden input)
										current_page.val("1");
										current_page_lbl.html("1");
								}

								// Reset selected row if there is any
								search_object.selected_row = undefined;

								/*
								 If a search is in the queue to be executed while another one is coming,
								 prevent the last one
								 */
								if (search_object.to_be_executed) {
										clearTimeout(search_object.to_be_executed);
								}

								// Start search after the type delay
								search_object.to_be_executed = setTimeout(function () {
										// Sometimes requests with no search value get through, double check the length to avoid it
										if ($.trim(search_object.value).length) {
												// Display loading icon
												$(search_object).addClass('ajax_loader');

												var navigation = getFormInfo(form, 'navigation', options);
												var total_page_lbl = getFormInfo(form, 'total_page_lbl', options);
												var page_range = getFormInfo(form, 'page_range', options);
												var footer = getFormInfo(form, 'footer', options);

												var toPostData = $(form).serializeArray();
												var customData = $(search_object).data();

                        var kill_switch = false;
												$.each(customData, function(k, v){
                          if( k.substring(0,3) !== 'ls_' ) return;
                          if( k === 'ls_query_lock' ) {
                            var lock_set = v.split('|');
                            lock_set.forEach(function(lock_id){
                              var lock = $('#' + lock_id);
                              if( lock.length === 1 ) {
                                var lock_val = lock.val();
                                var lock_name = ( ((lock.attr('name')) ? lock.attr('name').match(/\[([^\[]+)\]$/) : null) || [0,lock.attr('data-g365_ls_lock')]);
                                if( typeof lock_val === 'undefined' || lock_val === '' || typeof lock_name[1] === 'undefined' ) {
                                  kill_switch = true;
                                  return false;
                                }
                                var dataObj = {};
                                dataObj['name'] = 'ls_query_lock[' + lock_name[1] + ']';
                                dataObj['value'] = lock_val;
                                toPostData.push(dataObj);
                              } else {
                                kill_switch = true;
                                return false;
                              }
                            });
                            if( kill_switch ) return false;
                          } else {
                            var dataObj = {};
                            dataObj['name'] = k;
                            dataObj['value'] = v;
                            toPostData.push(dataObj);
                          }
												});
                        if( kill_switch ) {
                          console.log('ls 3illed');
                          return false;
                        }
                        //clear any old messages
												$(search_object).siblings('.error, .success').remove();

												// Send the request
												$.ajax({
														type: "post",
														url: ls.url,
														headers: {'X-Requested-With': 'XMLHttpRequest'},
														data: toPostData,
														dataType: "json",
														cache: ls.cache,
														success: function (response) {
																if (response.status === 'success') {
																		var responseResultObj = $.parseJSON(response.result);

																		// set html result and total pages
																		result.find('table tbody').html(responseResultObj.html);

																		/*
																		 If the number of results is zero, hide the footer (pagination)
																		 also unbind click and select_result handler
																		 */
																		if (responseResultObj.number_of_results === 0) {
																				remove_footer(footer, result);
																		} else {
																				/*
																				 If total number of pages is 1 there is no point to have navigation / paging
																				 */
																				if (responseResultObj.total_pages > 1) {
																						navigation.show();
																						total_page_lbl.html(responseResultObj.total_pages);
																				} else {
																						// Hide paging
																						navigation.hide();
																				}

																				/**
																				 * Display select options based on the total number of results
																				 * There is no point to have a option with the value of 10 when there is
																				 * only 5 results
																				 */
																				//remove_select_options(responseResultObj.number_of_results, page_range, result, footer);

																				var minimumOptionValue = get_minimum_option_value(page_range);

																				// if is visible
																				if (footer.is(":visible")) {
																						// if number of results is less than minimum range except 0: Hide
																						if (parseInt(responseResultObj.number_of_results) <= parseInt(minimumOptionValue)) {
																								remove_footer(footer, result);
																						} else {
																								show_footer(footer, result);
																						}
																				} else {
																						// if number of results is NOT less than minimum range except 0: show
																						if (parseInt(responseResultObj.number_of_results) > parseInt(minimumOptionValue)) {
																								show_footer(footer, result);
																						} else {
																								remove_footer(footer, result);
																						}
																				}
																		}

																} else {
																		// There is an error
                                    if( response.message === 'Error: Token mismatch. Refresh the page. It seems that your session is expired.' ) location.reload();
																		result.find('table tbody').html(response.message);

																		remove_footer(footer, result);
																}

														},
														error: function (response) {
																console.log(response);
																result.find('table tbody').html('Something went wrong. Please modify your search.');

																remove_footer(footer, result);
														},
														complete: function (e) {
																/*
																 Because this is a asynchronous request
																 it may add result even after there is no query in the search field
																 */
																if ($.trim(search_object.value).length && result.is(":hidden")) {
																		show_result(result, options);
																}

																$(search_object).removeClass('ajax_loader');

																if (options.onAjaxComplete !== undefined) {
																		var data = {this: this};
																		options.onAjaxComplete(e, data);
																}
														}
												});
												// End of request
										}

								}, ls.type_delay);

						}
				} else {
						// If search field is empty, hide the result
						if (result.is(":visible") || result.is(":animated")) {
								hide_result(result, options);
						}
				}

				search_object.latest_value = search_object.value;
		}

		/**
		 * Generate Form html for the text input
		 *
		 * @param elem
		 * @param ls
		 * @returns {string}
		 */
		function generateFormHtml(elem, form_id, ls) {
				var elem_id = elem.attr('data-g365_type');
        var elem_add = (elem.attr('data-ls_no_add') === 'true') ? ' no-add-buttons' : '';

				elem.attr('autocomplete', 'off');
				elem.attr('name', 'ls_query');
				elem.addClass('ls_query');
				elem.attr('maxlength', ls.max_input);

				var optionsHtml = '', i, selected, option_value;
				var option_name = '';
				for (i = 0; i < ls.page_ranges.length; i += 1) {
						option_value = ls.page_ranges[i];
						if (option_value === 0) {
								option_name = 'All';
						} else {
								option_name = option_value;
						}

						if (ls.page_range_default === option_value) {
								selected = 'selected';
						} else {
								selected = '';
						}

						optionsHtml += '<option value="' + option_value + '" ' + selected + '>' + option_name + '</option>';
				}

				var paginationHtml = '<div class="grid-x ' + ls.footer_class + '">' +
								'<div class="cell small-12 ' + ls.page_limit + '">' +
                  '<select name="ls_items_per_page" class="' + ls.page_range_class + '">' + optionsHtml + '</select>' +
								'</div>' +
								'<div class="cell shrink ' + ls.navigation_class + '">' +
  								'<i class="left-arrow-button ' + ls.arrow_class + ' ' + ls.previous_page_class + '" tabindex="0">' + '</i>' +
								'</div>' +
								'<div class="cell shrink ' + ls.navigation_class + ' ' + ls.pagination_class + '">' +
                  '<label class="' + ls.current_page_lbl_class + '">1</label> / ' +
                  '<label class="' + ls.last_page_lbl_class + '"></label>' +
								'</div>' +
								'<div class="cell shrink ' + ls.navigation_class + '">' +
								  '<i class="right-arrow-button ' + ls.arrow_class + ' ' + ls.next_page_class + '" tabindex="0">' + '</i>' +
								'</div>' +
              '</div>';

				var wrapper = '<div class="' + ls.container_class + '">' +
								'<form accept-charset="UTF-8" class="' + ls.form_class + '" id="' + form_id + '" name="ls_form">' +
								'</form>' +
								'</div>';

				var hidden_inputs = '<input type="hidden" name="ls_anti_bot" class="' + ls.form_anti_bot_class + '" value="">' +
								'<input type="hidden" name="g365_session" value="' + ls.session + '">' +
								'<input type="hidden" name="g365_token" class="' + ls.token_class + '" value="' + ls.token + '">' +
								'<input type="hidden" name="g365_time" class="' + ls.loaded_at_class + '" value="' + ls.loaded_at + '">' +
								'<input type="hidden" name="ls_current_page" class="' + ls.current_page_hidden_class + '" value="1">' +
								'<input type="hidden" name="ls_query_id" value="' + elem_id + '">';

				var result = '<div class="' + ls.result_wrapper_class + elem_add + '" style="display: none;">' +
								'<div class="' + ls.result_class + '">' +
								'<table class="' + ls.table_class + '"><tbody></tbody></table>' +
								'</div>' + paginationHtml + '</div>';

				elem.wrap(wrapper);
				elem.before(hidden_inputs);
				elem.after(result);
		}

		//action functions
		var ls_action_functions = {
			navigate: function(e, data) {
				//the id of existing data or the kill command
				var form_selected_id = data.selected.attr('data-href');
        if( form_selected_id === "null" ) return;
        //check to see if we need additional data to make the url
        var form_selected_advance = data.selected.attr('data-href-adv');
        //if we have a hook and the corresponding url object to build with, please do
        if( form_selected_advance !== 'undefined' && $.isPlainObject(document.g365_herf_adv) ) {
          //split the hook so we know what to replace
          form_selected_advance = form_selected_advance.split('=');
          //the first part should be the same
          form_selected_id = document.g365_herf_adv.href;
          //loop through the vars and selectively replace with a new var
          $.each(document.g365_herf_adv.href_build, function(key, data){
            //if it's the key we are replacing, set it equal to the new var, otherwise use the previously set var
            form_selected_id += "&" + key + '=' + (( key === form_selected_advance[0] ) ? form_selected_advance[1] : data);
          });
        }
				// goto the target url
				window.location.href = form_selected_id;
	//         // set the input value
	//         this_input.val(selectedOne);
	//         // hide the result
	//         this_input.trigger('ajaxlivesearch:hide_result');
			},
			load_form: function(e, data) {
        //clear any old messages
        data.searchField.siblings('.error, .success').remove();
				//the id of existing data or the kill command
				var form_selected_id = data.selected.attr('data-g365_id');
        if( form_selected_id === "null" ) return;
				//string to connect all elements to support form type
				var form_input_target_id = data.searchField.attr('data-g365_form_dest');

        //see if we have to claim the user
				var form_selected_request = data.selected.attr('data-g365_request_access'); //grab the player id of who the user is submitting the form for
        if( typeof form_selected_request !== "undefined" ) {
          var form_field_user_id = data.searchField.attr('data-ls_user_ac'); //id of user submitting the claim
          var form_field_type = data.searchField.attr('data-g365_type'); //get the type that is being sent
          form_field_user_id = form_field_user_id.split('-'); //get rid of the SPD and only use the id
          if( typeof form_field_user_id !== "undefined" && typeof form_field_type !== "undefined" ) {
            //send request to grassroots for claiming
            $.when( g365_claim_start( form_field_type, form_selected_request ) )
            .done( function(claim_result){
              //handle results
              $('<p id="' + (form_input_target_id + '_success_message') + '" class="success">' + claim_result.message[form_field_type] + '</p>').insertAfter(data.searchField);
            });
          } else {
            $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing user or target data. Please contact a representative.</p>').insertAfter(data.searchField);
          }
          data.searchField.trigger('ajaxlivesearch:hide_result');
          return false;
        }

				//data set to send for form field assebly
				var form_data = {
          go_flat : false
        };
        //clear any old messages
        data.searchField.siblings('.error, .success').remove();
				//type of search
				form_data.query_type = ( typeof data.searchField.attr('data-g365_type_new') !== 'undefined' ) ? data.searchField.attr('data-g365_type_new') : data.searchField.attr('data-g365_type');
				if( typeof form_data.query_type == 'undefined' || form_data.query_type === '' ) {
          $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing type. Form will not submit. Please reload the page.</p>').insertAfter(data.searchField);
					return;
				}
        
        //see if we need to collect requirement data
        var contributions_compile = g365_cross_check_reqs( data.searchField );
        if( contributions_compile === false ) {
          $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing required field data.</p>').insertAfter(data.searchField);
          return false;
        }
        //if we have hardcocded presets in the search results add them to the contributions
        if( typeof data.selected.attr('data-g365_presets') !== 'undefined' ) {
          if( contributions_compile === null ) contributions_compile = {'data' : {}};
          contributions_compile.hide = {};
          var search_presets = $.parseJSON(data.selected.attr('data-g365_presets'));
          if( typeof search_presets === 'object' ) $.each(search_presets, function(key, value){ contributions_compile.data[key] = value; contributions_compile.hide[key] = 'hide'; });
        }
        //attach any contributions we collected to the main form build object
        if( contributions_compile !== null ) form_data.contributions = contributions_compile.data;

        var form_input_target = $( '#' + form_input_target_id );
				//element to add new form fields
				var form_new_wrapper = $( '#' + form_input_target_id + '_data' );

        //make sure that we aren't running into any limitations on the add
        var limits = data.searchField.attr('data-g365_limit');
        var dropdown_select = false;
        var dropdown_id;
        if( typeof limits !== 'undefined') {
          var break_out = false;
          limits = limits.split('|');
          $.each(limits, function(dex, limit_key){
            var limit_vals = limit_key.split(',');
            switch( limit_vals[0] ){
              case 'max':
                if( form_new_wrapper.children().length >= limit_vals[1] ) {
                  $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Maximum reached.</p>').insertAfter(data.searchField);
                  break_out = true;
                  return false;
                }
                break;
              case 'exceptions':
                //see this is an exception, and if we need to mark
                if(typeof data.selected.attr('data-g365_exception') !== 'undefined') {
                  if( contributions_compile === null ) contributions_compile = {'attrs': {}};
                  contributions_compile.attrs['data-g365_exception'] = '';
                  var limit_val = (parseInt(limit_vals[1]) === 0) ? 2 : 0;
                  if( limit_val !== 0 && form_new_wrapper.children('[data-g365_exception]').length >= limit_val ) {
                    $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Exception maximum reached.</p>').insertAfter(data.searchField);
                    break_out = true;
                    return false;
                  }
                }
                break;
              case 'dropdown':
                var dropdown_keys = form_new_wrapper.children('[data-g365_dropdown_key]').map( function(){ return $(this).attr('data-g365_dropdown_key'); } ).get();
                dropdown_select = $('#' + limit_vals[1]).children('option:selected');
                if( dropdown_select.length !== 1 ){
                  $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Cannot find unique listing.</p>').insertAfter(data.searchField);
                  break_out = true;
                  return false;
                } else {
                  dropdown_id = dropdown_select.html();
                  var dupe = false;
                  $.each(dropdown_keys, function(dex,key_val){ if( key_val === dropdown_id ) dupe = true; return false; });
                  if( dropdown_keys.length > 0 && dupe ) {
                    $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Event slot already in use.</p>').insertAfter(data.searchField);
                    break_out = true;
                    return false;
                  }
                }
                break;
              case 'only':
                //get the vars to use in the 'only' lock
                var only_fields = [];
                $.each(limit_vals.slice(1), function(dex, val){only_fields[only_fields.length] = val;});
                //get all the current data_key vars from the elements already in place
                var key_fields = {};
                form_new_wrapper.children('.g365_form').each(function(){
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
                  if( form_selected_id === set_vars.id  ) {
                    $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Cannot add. Duplicate entries not allowed.</p>').insertAfter(data.searchField);
                    break_out = true;
                    return false;
                  }
                });
                break;
            }
          });
          if( break_out ) return false;
        }
        //set the form to build with
        form_data.template_format = (typeof data.searchField.attr('data-g365_form_template') !== 'undefined') ? data.searchField.attr('data-g365_form_template') : 'form_template_min';
        //build the field_group var
        form_data.field_group = data.query_id;
        //if we have contributions, make a unique trail to each element out of them
        if( contributions_compile !== null) $.each(contributions_compile.data, function(key, val){ if( val !== "0" && $.isNumeric(val) && key !== data.searchField.attr('data-ls_target') ) form_data.field_group += '_' + val; });
        //make sure that we don't already have a control set for this data point in play
        if( $( '#' + form_data.field_group + '_' + form_selected_id ).length > 0 ) {
          $('<p id="' + (form_selected_id + '_error_message') + '" class="error">There is already a field set for the selected entry.</p>').insertAfter(data.searchField);
          return;
        }
				//are we loading data from the server to update or adding new data
				if( typeof form_selected_id !== 'undefined' && typeof parseInt(form_selected_id) === 'number' ) {
			 		form_data.id = [form_selected_id];
				} else {
          //add default args to build object
			 		form_data.id = null;
          var new_destination = data.searchField.attr('data-g365_form_dest_new');
          if( typeof new_destination !== 'undefined' ) form_input_target_id = new_destination;
          //set the form to build with a new template if provided
          if( typeof data.searchField.attr('data-g365_form_template_new') !== 'undefined' ) form_data.template_format = data.searchField.attr('data-g365_form_template_new');
				}
        //if we are going flat, set the variable
        var go_flat = data.searchField.attr('data-g365_base_id');
        if( typeof go_flat !== 'undefined' ) form_data.go_flat = go_flat;
        //if we are missing the wrapper, create one
        if( form_new_wrapper.length === 0 ) form_new_wrapper = $( '<div class="new_form" id="' + form_input_target_id + '_data"></div>' ).appendTo( data.searchField.parent().parent() );
        form_data.field_origin_id = data.searchField.attr('id');
        //if there is no target or wrapper get out
				if( typeof form_input_target_id == 'undefined' || form_new_wrapper.length !== 1 ) {
          $('<p id="' + (form_input_target_id + '_error_message') + '" class="error">Missing target or target element. Form will not submit. Please reload the page.</p>').insertAfter(data.searchField);
					return;
				}
				//build form and add it to the fields holder
        // function located in: g365_form_manager.js
				$.when(g365_build_template_from_data( form_data ))
				.done( function(form_template_message){
          //attach the new form and set a handle
          var form_new_loaded = '';
          //set a handle for the new form element, add presets, and attach the new form
          if( typeof form_template_message === 'object' ) {
            if( form_template_message.enabled === null && form_template_message.disabled === null ) {
              form_new_loaded = $( '<p>No data found.</p>' ).prependTo( form_new_wrapper );
            } else {
              if( form_template_message.disabled === null ) {
                g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( form_new_wrapper );
              } else if( form_template_message.enabled === null ) {
                g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( form_new_wrapper );
              } else {
                //attach the new form for both disabled and enabled data points
                var enabled_div = $('.g365_enabled_data', load_target);
                if( enabled_div.length === 0 ) {
                  g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( form_new_wrapper );
                  g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( form_new_wrapper );
                } else {
                  g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( $('.g365_enabled_data', form_new_wrapper) );
                  g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( $('.g365_disabled_data', form_new_wrapper) );
                }
//                 g365_add_presets( $( form_template_message.enabled ), contributions_compile ).prependTo( $('.g365_enabled_data', form_new_wrapper) );
//                 g365_add_presets( $( form_template_message.disabled ), contributions_compile ).prependTo( $('.g365_disabled_data', form_new_wrapper) );
              }
              form_new_loaded = form_new_wrapper;
            }
          } else {
            //attach the new form
      //       form_new_loaded = $( form_template_message ).prependTo( $('.form-holder>form>div:first-child', current_form) );
            form_new_loaded = g365_add_presets( $( form_template_message ), contributions_compile ).prependTo( form_new_wrapper );
          }
					//initialize form_data
          if( typeof data.searchField.attr('data-g365_form_template_new') !== 'undefined' ) data.searchField.val('');
					g365_form_start_up( form_new_loaded );
          //if this isn't nested show the submit buttons now that we have a form
          $( '#' + form_input_target_id + '_submit' ).show();
					//hide results
					data.searchField.trigger('ajaxlivesearch:hide_result');
				});
			},
			select_data: function(e, data) {
        //clear any old messages
        data.searchField.siblings('.error, .success').remove();
				//string to connect all elements to support form type
				var ls_target = data.searchField.attr('data-ls_target');
        //see if we have to claim the user
				var form_selected_request = data.selected.attr('data-g365_request_access');
        if( typeof form_selected_request !== "undefined" ) {
          var form_field_user_id = data.searchField.attr('data-ls_user_ac');
          var form_field_type = data.searchField.attr('data-g365_type');
          form_field_user_id = form_field_user_id.split('-');
          if( typeof form_field_user_id !== "undefined" && typeof form_field_type !== "undefined" ) {
            //send request to grassroots for claiming
            $.when( g365_claim_start( form_field_type, form_selected_request ) )
            .done( function(claim_result){
              //handle results
              $('<p id="' + (ls_target + '_success_message') + '" class="success">' + claim_result.message[form_field_type] + '</p>').insertAfter(data.searchField);
            });
          } else {
            $('<p id="' + (ls_target + '_error_message') + '" class="error">Missing user or target data. Please contact a representative.</p>').insertAfter(data.searchField);
          }
          data.searchField.trigger('ajaxlivesearch:hide_result');
          return false;
        }
				//the id of existing data or the kill command
				var form_selected_id = data.selected.attr('data-g365_id');
        if( form_selected_id === "null" ) return;
				//element to add new form fields
				var form_target = $( '#' + ls_target );
				if( typeof ls_target == 'undefined' || form_target.length !== 1 ) {
          $('<p id="' + (ls_target + '_error_message') + '" class="error">Missing target or target element. Form will not submit. Please reload the page.</p>').insertAfter(data.searchField);
					return false;
				}
        //if we have an id to put in the designated field, or we need to see about getting one
        if( typeof form_selected_id === 'undefined'  ){
          //make sure the data target is empty if we are adding a new record
          form_target.val('');
          //see if we are allowed to add to this datatype
          if( data.searchField.attr('data-ls_no_add') === 'true' ) return false;
          //if we have a click target and we don't want to add data inline, use that to maintain continuity
          if( typeof data.searchField.attr('data-g365_select_click') !== 'undefined' && typeof data.searchField.attr('data-g365_form_dest_new') !== 'undefined' ) {
            var form_click_target = $( '#' + data.searchField.attr('data-g365_select_click') );
            if( form_click_target.length === 1 ) form_click_target.click();
            return false;
          }
          //load the form so that we can collect the data
          ls_action_functions.load_form(e,data);
          if( typeof data.searchField.attr('data-g365_type_new') === 'undefined' ) data.searchField.parent().slideUp();
        } else {
          //revert all the subsequent dependancies before change
          revert_dependance_chain( $('#' + data.searchField.attr('data-load_target')) );
          var val_name = data.selected.attr('data-g365_name');
          if( typeof val_name === 'undefined' ) val_name = data.selected.attr('data-g365_short_name');
          data.searchField.val( val_name );

          //see if there is additional data on the selected entry
//           var additional_data = data.selected.attr('data-g365_additional_data');
//           additional_data = (( typeof additional_data === 'undefined' || additional_data === '' ) ? null : additional_data);
          //see if we have a division that need to be handled
//           var additional_target = data.searchField.attr('data-g365_additional_target');
//           console.log('select', additional_target, additional_data);
//           //set the data var to null if we don't have any data so the function will unload everything
//           if( typeof additional_target !== 'undefined' ) g365_manage_additional_data(additional_target, additional_data );
          
          //if we need to build wit a local object
//           var local_build_target = data.searchField.attr('data-g365_local_build_target');
//           if( typeof local_build_target != 'undefined' ) g365_build_dropdown_from_object( $('#' + local_build_target) );

          //set main query fields
          if(typeof data.selected.attr('data-g365_additional_data') !== 'undefined') form_target.attr( 'data-g365_additional_data', data.selected.attr('data-g365_additional_data') );
          if(typeof data.selected.attr('data-g365_short_name') !== 'undefined') form_target.attr( 'data-g365_short_name', data.selected.attr('data-g365_short_name') );
          form_target.val( form_selected_id ).change();
          //set the values we pushed incase we need to revert the field.
          var targets_compile = {
            target_val: form_selected_id,
            target_name: data.selected.attr('data-g365_name')
          };
          data.searchField.data('compare_vals', targets_compile);
        }
				data.searchField.trigger('ajaxlivesearch:hide_result');
        //if the toggle button flag is set
        if(data.searchField.attr('data-g365_field_toggle') === 'true') {
          if( form_target.val() === '' ) return;
          var search_field_toggle = data.searchField.closest('.ls_container').parent();
          var field_change_button = search_field_toggle.prev('.field-change-button');
          if( field_change_button.length === 0 ) field_change_button = $('<a class="field-change-button block text-right tiny-padding" style="display:none;"></a>').insertBefore(search_field_toggle);
          field_change_button.html('<span class="field-title">' + data.searchField.val() + '</span><span class="field-button">change</span>').show();
          field_change_button.on('click',function(){ $(this).hide().next().show(); });
          search_field_toggle.hide();
        }
        //if we have an add button controlled by this element, toggle appropriately
        g365_manage_add_button( form_target );
        //adds a click on 'data-g365_select_click' target at the end of the process
        if( typeof form_target.val() !== 'undefined' && form_target.val() !== '' && typeof data.searchField.attr('data-g365_select_click') === 'string' ) {
          $('#' + data.searchField.attr('data-g365_select_click')).click();
//           var select_click_target = $('#' + data.searchField.attr('data-g365_select_click'));
//           if( select_click_target.length == 1 && typeof select_click_target.attr('data-g365_join_data') === 'string' && select_click_target.attr('data-g365_join_data') === 'true' ) {
//             select_click_target.click();
//           } else {
//             select_click_target.click();
//           }
        }
			}
		}
		this.each(function ( dex ) {
      var query = $(this);
      var query_id = ( ls.decendant_id === false ) ? ls.form_class + '_' + query.attr('data-g365_type') + '_' + query.attr('id') + '_' + dex : ls.decendant_id + '_' + dex;
      //action of the livesearch: load_form, navigate, select_data
      var ls_action = query.attr('data-g365_action');
      //if the 'click' action is going to be different than the 'enter' action set it with 'data-g365_action_click'
      var ls_action_click = query.attr('data-g365_action_click');
      //if there isn't a definited action, default to navigate
      if( typeof ls_action === 'undefined' ) ls_action = 'navigate';
      if( typeof ls_action_click === 'undefined' ) ls_action_click = ls_action;
      //if we need a target, and there isn't one, send a notice

      switch( ls_action ) {
        case 'navigate':
          break;
        case 'select_data':
          var ls_target = $( '#' + query.attr('data-ls_target') );
          if( ls_target === 0 ) console.log('Need input target. This form will not submit.', query);
          if( typeof query.attr('data-g365_form_dest') === 'undefined' && query.attr('data-ls_no_add') !== 'true' ) console.log('Need input destination. This form will not submit.', query);
          //just for live searches that lock/control multiple fields
          if( typeof ls_target.attr('data-g365_load_target') !== 'undefined' && ls_target.attr('data-g365_load_target').charAt(0) === '.' )   ls_target.on('change', function(){ g365_build_dropdown_from_object( $(this) ); });

          query.on('focusout resetter', function(evnt){
            //make sure we aren't clicking on one of the navigation arrows
            if( $(evnt.relatedTarget).hasClass("ls-arrow") ) {
  //             $(evnt.relatedTarget).on('focusout', function(arrow_evnt){
  //               if( $(arrow_evnt.relatedTarget) !== query )
  //             });
              return;
            }
            //if either value is empty, empty the other
            var search_input = $(this);
            var revert_targets = search_input.data( 'compare_vals' );
            var search_input_val = search_input.val();
            var ls_target_val = ls_target.val();
            if( search_input_val === '' || ls_target_val === '' || (typeof revert_targets === 'object' && ( (revert_targets.target_name !== search_input_val && revert_targets.target_val === ls_target_val) || (revert_targets.target_name === search_input_val && revert_targets.target_val !== ls_target_val) )) ) {
              ls_target.attr('data-g365_short_name', '');
              ls_target.attr('data-g365_additional_data', '');
              if( ls_target.val() !== '' ) ls_target.val('').change();
              if( search_input.val() !== '' ) search_input.val('').change();
              //if we have an add button controlled by this element, toggle appropriately
              g365_manage_add_button( ls_target );
            }
          });
          break;
        case 'load_form':
          var form_dest = query.attr('data-g365_form_dest');
          if( typeof form_dest === 'undefined' ) console.log('Need input target. This form will not submit.', query);
          break;
      }
      //add the click and enter handlers to individual search elements
      var onResultEnter = ls_action_functions[ls_action];
      var onResultClick = ls_action_functions[ls_action_click];
      generateFormHtml(query, query_id, ls);
      var form = $('#' + query_id);
      var result = getFormInfo(form, 'result', ls);
      var arrow = getFormInfo(form, 'arrow', ls);
      var current_page = getFormInfo(form, 'current_page', ls);
      var current_page_lbl = getFormInfo(form, 'current_page_lbl', ls);
      var total_page_lbl = getFormInfo(form, 'total_page_lbl', ls);
      var page_range = getFormInfo(form, 'page_range', ls);
      /**
       * Start binding
       */

      // Trigger search when typing is started
      query.on('keyup', function (event) {
        // If enter key is pressed check if the user wants to select hovered row
        var keycode = event.keyCode || event.which;
        if ($.trim(query.val()).length && keycode === 13) {
          if (!(result.is(":visible") || result.is(":animated")) || parseInt(result.find("tr").length) === 0) {
              show_result(result, ls);
          } else {
            if (query.selected_row !== undefined) {
              var data = {selected: $(query.selected_row), this: this, searchField: query, query_id: query_id};
              if (onResultEnter !== undefined) {
                onResultEnter(event, data);
              }
            }
          }
          event.stopPropagation();
        } else {
          // If something other than enter is pressed start search immediately
          search(this, form, ls, false, true);
        }
      });

				/**
				 * While search input is in focus
				 * Move among the rows, by pressing or keep pressing arrow up and down
				 */
				query.on('keydown', function (event) {
						var keycode = event.keyCode || event.which;
						if (keycode === 40 || keycode === 38) {
								if ($.trim(query.val()).length && result.find("tr").length !== 0) {
										if (result.is(":visible") || result.is(":animated")) {
												result.find('tr').removeClass('hover');

												if (query.selected_row === undefined) {
														// Moving just started
														query.selected_row = result.find("tr").eq(0);
														$(query.selected_row).addClass("hover");
												} else {
														$(query.selected_row).removeClass("hover");

														if (keycode === 40) {
																// next
																if ($(query.selected_row).next().length === 0) {
																		// here is the end of the table
																		query.selected_row = result.find("tr").eq(0);
																		$(query.selected_row).addClass("hover");
																} else {
																		$(query.selected_row).next().addClass("hover");
																		query.selected_row = $(query.selected_row).next();
																}
														} else {
																// previous
																if ($(query.selected_row).prev().length === 0) {
																		// here is the end of the table
																		query.selected_row = result.find("tr").last();
																		query.selected_row.addClass("hover");
																} else {
																		$(query.selected_row).prev().addClass("hover");
																		query.selected_row = $(query.selected_row).prev();
																}
														}
												}
										} else {
												// If there is any results and hidden and the search input is in focus, show result by press down
												if (keycode === 40) {
														show_result(result, ls);
												}
										}
								}

								// prevent cursor from jumping to beginning or end of input
								return false;
						}
				});

				// Show result when is focused
				query.on('focus', function () {
						// check if the result is not empty show it
						if ($.trim(query.val()).length && (result.is(":hidden") || result.is(":animated")) && result.find("tr").length !== 0) {
								search(this, form, ls, false, true);
								show_result(result, ls);
						}
				});

				// In the beginning, there is no result / tr, so we bind the event to the future tr
				result.on('mouseover', 'tr', function () {
						// remove all the hover classes, otherwise there are more than one hovered rows
						result.find('tr').removeClass('hover');

						// set the current selected row
						query.selected_row = this;

						$(this).addClass('hover');
				});

				// In the beginning, there is no result / tr, so we bind the event to the future tr
				result.on('mouseleave', 'tr', function () {
						// remove all the hover classes, otherwise there are more than one hovered rows
						result.find('tr').removeClass('hover');

						// Reset selected row
						query.selected_row = undefined;
				});

				// disable the form submit on pressing enter
				form.submit(function () {
						return false;
				});

				// arrow button - next
				arrow.on('click', function () {
						var new_current_page;

						if ($(this).hasClass(ls.next_page_class)) {
								// go next if it will be lower or equal to the total
								if (parseInt(current_page.val(), 10) + 1 <= parseInt(total_page_lbl.html(), 10)) {
										new_current_page = parseInt(current_page.val(), 10) + 1;
								} else {
										return;
								}
						} else {
								// previous button
								if (parseInt(current_page.val(), 10) - 1 >= 1) {
										new_current_page = parseInt(current_page.val(), 10) - 1;
								} else {
										return;
								}
						}

						current_page.val(new_current_page);
						current_page_lbl.html(new_current_page);

						// search again
						search(query[0], form, ls, true, false);
				});

				// Search again when the items per page dropdown is changed
				page_range.on('change', function () {
						/**
						 * we need to pass a DOM Element: query[0]
						 * In this case last value should not check against the current one
						 */
						search(query[0], form, ls, true, true);
				});

//             result.css({left: query.position().left + 1, width: query.outerWidth() - 2});

//             // re-Adjust result position when screen resizes
//             $(window).resize(function () {
//                 //adjust_result_position();
//                 result.css({left: query.position().left + 1, width: query.outerWidth() - 2});
//             });

				/**
				 * Click doesn't work on iOS - This is to fix that
				 * According to: http://stackoverflow.com/a/9380061/2045041
				 */
				var touchStartPos;
				$(document)
						// log the position of the touchstart interaction
						.bind('touchstart', function () {
								touchStartPos = $(window).scrollTop();
						})
						// log the position of the touchend interaction
						.bind('touchend', function (event) {
								// calculate how far the page has moved between
								// touchstart and end.
								var distance, clickableItem;

								distance = touchStartPos - $(window).scrollTop();

								clickableItem = $(document);

								/**
								 * adding this class for devices that
								 * will trigger a click event after
								 * the touchend event finishes. This
								 * tells the click event that we've
								 * already done things so don't repeat
								 */
								clickableItem.addClass("touched");

								if (distance < 10 && distance > -10) {
										/**
										 * Distance was less than 20px
										 * so we're assuming it's tap and not swipe
										 */
										if (!$(event.target).closest(result).length && !$(event.target).is(query) && $(result).is(":visible")) {
												hide_result(result, ls);
										}
								}
						});

				$(document).on('click', function (event) {
						/**
						 * For any non-touch device, we need to still apply a click event but we'll first check to see if there
						 * was a previous touch event by checking for the class that was left by the touch event.
						 */
						if ($(this).hasClass("touched")) {
								/**
								 * This item's event was already triggered via touch so we won't call the function and reset this
								 * for the next touch by removing the class
								 */
								$(this).removeClass("touched");
						} else {
								/**
								 * There wasn't a touch event. We're instead using a mouse or keyboard hide the result if outside
								 * of the result is clicked
								 */
								if (!$(event.target).closest(result).length && !$(event.target).is(query) && $(result).is(":visible")) {
										hide_result(result, ls);
								}
						}
				});
				/**
				 * Finish binding
				 */

				/**
				 * Custom Events
				 */
				$(result).on('click', 'tr', function (e) {
          var data = {selected: $(query.selected_row), this: this, searchField: query, query_id: query_id};
          if (onResultClick !== undefined) {
            onResultClick(e, data);
          }
        });
                
        $('#addBtnProfile').on('click', function (e) {
          var data = {selected: $(query.selected_row), this: this, searchField: query, query_id: query_id};
          if (onResultClick !== undefined) {
            onResultClick(e, data);
            $(this).hide();
          }
				});

				/**
				 * Custom Triggers
				 */
				$(this).on('ajaxlivesearch:hide_result', function () {
          hide_result(result, ls);
				});
				$(this).on('ajaxlivesearch:search', function (e, params) {
          $(this).val(params.query);
          search(this, form, ls, true, true);
        });
		});
		// Set anti bot value for those that do not have JavaScript enabled
		$('.' + ls.form_anti_bot_class).val(ls.form_anti_bot);
		// keep chaining
		return this;
}
function g365_livesearch_init( parent_limit, query_id ){
	var g365_live_search_input = ( typeof parent_limit == 'undefined' ) ? $(".g365_livesearch_input") : $(".g365_livesearch_input", parent_limit);
	var ls_decendant_id = ( typeof query_id === 'undefined' || query_id === null ) ? false : query_id;
	//see if we have any ls inputs to activate
	if ( g365_live_search_input.length > 0 ) {
		g365_live_search_input.ajaxlivesearch({
			loaded_at: g365_sess_data.time,
			token: g365_sess_data.token,
			session: g365_sess_data.id,
			target_url: g365_script_domain,
			decendant_id: ls_decendant_id,
			max_input: 60,
			footer_class: 'ls_result_footer',
			page_range_default: 10,
			table_class: 'unstriped compact expanded no-margin-bottom',
			onAjaxComplete: function(e, data) {
        if( e.result === 'remove-session' ) {
          g365_cookies.remove( 'g365_SID', {domain: g365_script_domain.slice(8,-1)} );
//           g365_sess_data = false;
          location.reload();
        }
			}
		});
	}
}
window.g365_func_wrapper.sess[window.g365_func_wrapper.sess.length] = {name : g365_livesearch_init, args : []};
