//add functions below
g365_extend_form = function ( target_container ) {
// Drag and drop options
//   $('.sortable-main', target_container).each(function(){
//     $( $(this).attr('data-sort_pointer'), target_container ).sortable({
//       connectWith: ($(this).attr('data-sort_connect')) ? $(this).attr('data-sort_connect') : '.drop',
//       placeholder: 'phantom-block'
//     }).disableSelection();
//   });
/**************** Score Plateform Interface ****************/
  
  // Create TimeStamp on Active Player
  function get_timestamp(target_container) { // Set timestamp format: hh:mm:ss
    var pl_timestamp = new Date();
    return ( pl_timestamp.getHours() + ':' + ((pl_timestamp.getMinutes() < 10) ? ('0' + pl_timestamp.getMinutes()) :(pl_timestamp.getMinutes())) + ':' + ((pl_timestamp.getSeconds() < 10) ? ('0' + pl_timestamp.getSeconds()) : (pl_timestamp.getSeconds())) );
  }
  // Stat buttons
  $(document).on('click', 'a', target_container, function() {
    var team_table = $(this).parents('table').eq(1).attr('id');
    var click_button = this.className;
    var score_type = click_button.replace(/one_|two_|three_|minus_| button/g,'');
    var player_id = $(this).closest('.draggable').attr('id');
    var counter = parseInt($('#indi_player_'+score_type+'-'+player_id).val());
    var three_count = parseInt($('#indi_player_three_'+score_type+'-'+player_id).val());
    if(isNaN(counter) || counter < 0) {
     counter = 0;
    }
    if(isNaN(three_count) || three_count < 0){
     three_count = 0;
    }
    switch(click_button) {
    case 'one_'+score_type+' button':
      counter++;
      break;
    case 'two_'+score_type+' button':
      counter = counter + 2;
      break;
    case 'three_'+score_type+' button':
      counter = counter + 30;
      three_count++;
      break;
    case 'minus_three_'+score_type+' button':
      if(three_count>0){ // Stop -3 on point when 3pm reaches 0
        counter = counter - 3;
        three_count = three_count - 1;
      }
      if(three_count < 0) {
       three_count = 0;
      }
      if(counter < 0) {
       counter = 0;
      }
      break;
    case 'minus_one_'+score_type+' button':
      counter = counter - 1;
      if(counter < 0) {
       counter = 0;
      }
    }
    $('#indi_player_'+score_type+'-'+player_id).val(counter);
    $('#indi_player_pub_'+score_type+'-'+player_id).text(counter);
    $('#indi_player_three_'+score_type+'-'+player_id).val(three_count);
    $('#indi_player_three_pt_three_'+score_type+'-'+player_id).text(three_count);
    var total_score = 0;
    $('.'+team_table).each(function(){
      var point = $(this).val();
      if(point < 0) {
        point = 0;
      }
      // Validate Number
      if(!isNaN(point) && point.length != 0) {
        total_score += parseFloat(point);
      }
    })
    $('.'+team_table+'_score').text(total_score);
  });
  // Game Clock
  // Active Player Timestamp 
  var array = [];
  function check_active_pl_for_ts() {
    var ids = $('.current_pl').map(function() {
      return $(this).attr('id');
    }).get();
    return ids;
  }
  // Calculate player times
  function pl_time(start_time, stop_time) {
    var time_diff_sec = Math.abs(start_time - stop_time) / 1000;

    // calculate hours
    var t_in_hours = Math.floor(time_diff_sec / 3600) % 24;
    time_diff_sec -= t_in_hours * 3600;
    t_in_hours = ('0' + t_in_hours).slice(-2); // format 1=>01
//     console.log('hours', t_in_hours);

    // calculate minutes
    var t_in_minutes = Math.floor(time_diff_sec / 60) % 60;
    time_diff_sec -= t_in_minutes * 60;
    t_in_minutes = ('0' + t_in_minutes).slice(-2); // format 1=>01
//     console.log('minutes', t_in_minutes);
    
    // calculate seconds
    var t_in_seconds = Math.floor(time_diff_sec);
    t_in_seconds = ('0' + t_in_seconds).slice(-2); // format 1=>01
//     console.log('seconds', t_in_seconds);

    var pl_time = '';
    if(t_in_hours > 0) {
      pl_time = t_in_hours+':'+t_in_minutes+':'+t_in_seconds;   
    }
    if(t_in_hours <= 0 && t_in_minutes > 0) {
      pl_time = '00:'+t_in_minutes+':'+t_in_seconds;
    }
    if(t_in_hours <= 0 && t_in_minutes <= 0) {
      pl_time = '00:00:'+t_in_seconds;
    }
    return pl_time;
  }
  function play_time_calculation(ts_array) {
    var hr_sum  = 0;
    var min_sum = 0;
    var sec_sum = 0;
    var hr_timestamp = [];
    var min_timestamp = [];
    var sec_timestamp = [];
    if(ts_array !== '') {
      for (var j = 0; j < ts_array.length; j++) {
        hr_timestamp.push(
           parseInt(ts_array[j].split(':')[0])
        );
        min_timestamp.push(
           parseInt(ts_array[j].split(':')[1])
        );
        sec_timestamp.push(
           parseInt(ts_array[j].split(':')[2])
        );
      }
      // Getting sum of timestamps
      hr_sum = hr_timestamp.reduce(function(a, b) {
          return a + b;
      }, 0);
      min_sum = min_timestamp.reduce(function(a, b) {
          return a + b;
      }, 0);
      hr_sum  = hr_sum + min_sum/60;
      min_sum = min_sum%60;
      sec_sum = sec_timestamp.reduce(function(a, b) {
          return a + b;
      }, 0);
      min_sum = min_sum + sec_sum/60;
      sec_sum = sec_sum%60;
    }
    return parseInt(hr_sum)+':'+parseInt(min_sum)+':'+parseInt(sec_sum);
  }
  function time_log_field_val(arr_cont_id, time_type, ts_obj_ex_val) {
    document.getElementById(arr_cont_id).querySelector('.active_pl_t_log').value = time_type+': '+ts_obj_ex_val.toString();
  }
  function sub_btn_ts_fn(sub_stp_ts_obj_ex_val, sub_stp_arr_cont_id, sub_last_stop_t_arr, sub_ts_cal, sub_arr_index){ // Swap players during game clock is running
    var stp_ts_obj_ex_val = sub_stp_ts_obj_ex_val;
    var stp_arr_cont_id =  sub_stp_arr_cont_id;
    var last_stop_t_arr = sub_last_stop_t_arr;
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '/' + mm + '/' + dd + ' ';
    var each_pl_ts = pl_time( new Date(today+stp_ts_obj_ex_val[stp_ts_obj_ex_val.length -1]) , new Date(today+last_stop_t_arr[last_stop_t_arr.length - 1]) );
    sub_ts_cal.push(each_pl_ts);
    array[sub_arr_index].total_play_time = play_time_calculation(sub_ts_cal);
    document.getElementById(stp_arr_cont_id).querySelector('.active_pl_t_log').value = 'start_time: '+stp_ts_obj_ex_val.toString()+'; stop_time: '+last_stop_t_arr; // Player time log
    document.getElementById(stp_arr_cont_id).querySelector('.active_pl_tt').value = play_time_calculation(sub_ts_cal); // Player total time played
  }
  function search_id(id_to_search, array_container, active_pl_index, time_type, sub_id, sub_btn_active) {
    var query_object = array_container.find(function(element) {
      return element.id == id_to_search;
    });
    if(sub_btn_active === false) { // Start, Stop through clock btns
      if(query_object !== undefined) {
        for(var i = 0; i < array_container.length; i++) {
          if(time_type == 'start'){
            if (array_container[i].id == query_object.id) {
              array[i].start_time.push( // Update existing id's start time
                get_timestamp()
              );
              var ts_obj_ex_val = array[i].start_time;
              var arr_cont_id =  array_container[i].id;
              time_log_field_val(arr_cont_id, time_type, ts_obj_ex_val);
            }
          }
          if(time_type == 'stop') {
            if(array_container[i].id == query_object.id) {
              array[i].stop_time.push( // Update existing id's stop time
               get_timestamp()
              );
              sub_btn_ts_fn(array[i].start_time, array_container[i].id, array[i].stop_time, array[i].play_time, i);
            }  
          }
        }
      } else {
        switch(time_type) {
          case 'start':
          array.push( // Create new start timestamp id
            { 
              id: id_to_search, 
              start_time: [ get_timestamp() ],
              stop_time: [],
              play_time: [],
              total_play_time: []
            } 
          );
          for (var k = 0; k < array_container.length; k++) {
            var ts_obj_new_val = array[k].start_time;
            time_log_field_val(id_to_search, time_type, ts_obj_new_val);
          }
          break;
        }
      }
    }
  }
  function init_call(init_index, time_type) {
    switch(time_type) {
      case 'start':
        array.push( // Create timestamp
          { 
            id: check_active_pl_for_ts()[init_index],
            start_time: [ get_timestamp() ],
            stop_time: [],
            play_time: [],
            total_play_time: []
          } 
        ); 
        var init_ts_obj_val = array[init_index].start_time;
        var active_pl_id = check_active_pl_for_ts()[init_index];
        time_log_field_val(active_pl_id, time_type, init_ts_obj_val);
        break;
    }
  }
  function active_player_timestamp(time_type, sub_id, sub_btn_active) { // Timestamp on active roster => start time button
    if(array.length === 0 && sub_btn_active === false) { // Initial call
      for (var init_index = 0; init_index < check_active_pl_for_ts().length; init_index++) {
        init_call( init_index, time_type );
      }
    } 
    else if(array.length !== 0 && sub_btn_active === false) {
      for (var index = 0; index < check_active_pl_for_ts().length; index++) {
        search_id(check_active_pl_for_ts()[index], array, index, time_type, sub_id, sub_btn_active);
      }
    } 
    else if(array.length !== 0 && sub_btn_active === true) { // Swap players through substitute btns
      var sub_query_object = array.find(function(element) {
        return element.id == sub_id;
      });
      if(sub_query_object !== undefined) {
        for (var j = 0; j < array.length; j++) {
          if(time_type == 'start') { // sub btn add 
            if(sub_id == array[j].id) {
              array[j].start_time.push( // Update existing id's start time
                get_timestamp()
              );
              if(array[j].start_time.length == array[j].stop_time.length) {
                sub_btn_ts_fn(array[j].start_time, array[j].id, array[j].stop_time, array[j].play_time, j);              
              }
            } 
          }
          if(time_type == 'stop') { // sub btn subtract
            if(sub_id == array[j].id) {
              array[j].stop_time.push( // Update existing id's stop time
                get_timestamp()
              );
              sub_btn_ts_fn(array[j].start_time, array[j].id, array[j].stop_time, array[j].play_time, j);
            } 
          }
        }
      }else{ // New player id
        switch(time_type) {
          case 'start':
          array.push( // Create new start timestamp id
            { 
              id: sub_id, 
              start_time: [ get_timestamp() ],
              stop_time: [],
              play_time: [],
              total_play_time: []
            } 
          );
          for (var k = 0; k < array.length; k++) {
            var ts_obj_new_val = array[k].start_time;
            time_log_field_val(sub_id, time_type, ts_obj_new_val);
          }
          break;
        } 
      }
    }
//     console.log('*********');
//     console.log( array );
//     console.log('*********');
  }
  function sub_pl_during_clock(time_type, sub_id, sub_btn_active) {
    if($('#cd_status').html() == 'Game is in progress') { // if clock is running
      active_player_timestamp(time_type, sub_id, sub_btn_active);
    } 
  }
 // Player Substitute Action Handler
  $(document).on('click', '.sub_button', target_container, function() {
    var pl_field_id = $(this).closest('.draggable').attr('id');
    var home_dir_div = document.querySelector('.drop_home.active');
    var away_dir_div = document.querySelector('.drop_away.active');
    var home_inactive_div = document.querySelector('.drop_home.inactive');
    var away_inactive_div = document.querySelector('.drop_away.inactive');
    var parent_id =  $('#'+pl_field_id).parent().attr('id');
    switch(parent_id) {
      case 'drop_home_inactive': // home add
      $('#'+pl_field_id).appendTo(home_dir_div);
      $('#'+pl_field_id+' .sub_button').html('-');
      $('#drop_home_active .draggable').addClass('current_pl');
      sub_pl_during_clock('start', pl_field_id, true);
      break;
      case 'drop_away_inactive':
      $('#'+pl_field_id).appendTo(away_dir_div); // away add
      $('#'+pl_field_id+' .sub_button').html('-');
      $('#drop_away_active .draggable').addClass('current_pl');
      sub_pl_during_clock( 'start', pl_field_id, true );
      break;
      case 'drop_home_active':
      $('#'+pl_field_id).appendTo(home_inactive_div); // home sub
      $('#'+pl_field_id+' .sub_button').html('+');
      $('#drop_home_inactive .draggable').removeClass('current_pl');
      sub_pl_during_clock( 'stop', pl_field_id, true );
      break;
      case 'drop_away_active':
      $('#'+pl_field_id).appendTo(away_inactive_div); // away sub
      $('#'+pl_field_id+' .sub_button').html('+');
      $('#drop_away_inactive .draggable').removeClass('current_pl');
      sub_pl_during_clock( 'stop', pl_field_id, true );
      break;
    }
  });
  // 1st, 2nd and OT Clock, on page load actions
  // Load 1st Quarter Button on Page Load 
  window.onload = function() {
    $('#cd_pause').hide();
    $('#cd_reset').hide();
    $('#cd_first_half').focus().click();
  }  // End
  $('#cd_first_half').click(function() {
    $('#cd_minutes').val('20');
  });
  $('#cd_second_half').click(function() {
    $('#cd_minutes').val('20');
  });
  $('#cd_ot').click(function() {
    $('#cd_minutes').val('5');
  });
  //   Ribbon clock bar
  window.addEventListener('scroll', function() {
    var clock_bar = document.getElementById('ribbon_clock_btn');
    var score_bar = document.getElementById('game_score');
    var ribbon_bar = document.getElementById('ribbon_clock_btn');
    if ( window.scrollY > (clock_bar.offsetTop) ) {
      clock_bar.classList.add('ribbon_bar');
      score_bar.classList.add('score_to_ribbon_bar'); 
      $('#ribbon_clock_btn>div').addClass('small-12 large-2');
      $('#ribbon_clock_btn>div').removeClass('small-12 large-12'); 
      $('#ribbon_clock_btn .clock_action_button').addClass('small-12 large-3');
      $('#ribbon_clock_btn .clock_action_button').removeClass('large-2');
      $('#ribbon_clock_btn .set_time_and_status').addClass('small-12 large-3');
      $('#ribbon_clock_btn .set_time_and_status').removeClass('large-2');
      $('#ribbon_clock_btn .site-button').addClass('no-margin-bottom');
      $('#ribbon_clock_btn').removeClass('callout');
      if($('#ribbon_clock_btn .game_score').length > 0) {
        return;
      } else {
        $('.game_score').clone().appendTo(ribbon_bar).insertAfter(ribbon_bar.children[1]); // Append a copy of game score to ribbon
        $('.site-button').clone().appendTo(ribbon_bar).insertAfter(ribbon_bar.children[5]); // Append a copy of submit game data button to ribbon
      }
    } else {
      clock_bar.classList.remove('ribbon_bar');
      score_bar.classList.remove('score_to_ribbon_bar');
      $('#ribbon_clock_btn>div').addClass('small-12 large-12');
      $('#ribbon_clock_btn>div').removeClass('small-12 large-2 large-3');
      $('#ribbon_clock_btn').addClass('callout');
      $('#ribbon_clock_btn .game_score').remove();
      $('#ribbon_clock_btn .site-button').remove();
    }
  });
  // Activate clone site-button click function
  $(document).on('click', '.site-button', target_container, function() {
    $('.site-button').submit();
  });
  $.extend({
    APP : {                
      formatTimer : function(a) {
        if (a < 10) {
          a = '0' + a;
        }                              
        return a;
      },    
      startTimer : function(dir) {
        var a;
        // save type
        $.APP.dir = dir;
        // get current date
        $.APP.d1 = new Date();
        switch($.APP.state) {
          case 'pause' :    
            // resume timer
            // get current timestamp (for calculations) and
            // substract time difference between pause and now
            $.APP.t1 = $.APP.d1.getTime() - $.APP.td;                            
          break;
          default :
            // get current timestamp (for calculations)
            $.APP.t1 = $.APP.d1.getTime(); 
            // if countdown add ms based on seconds in textfield
            if ($.APP.dir === 'cd') {
              var get_input_time = $('#cd_minutes').val(); // 
              if (get_input_time.indexOf(':') > -1){
                var get_minutes = parseInt(get_input_time.split(':')[0]*60000);
                var get_seconds = parseInt(get_input_time.split(':')[1]*1000);
                $.APP.t1 += get_minutes; // Set to minutes
                $.APP.t1 += get_seconds; // Set to seconds
              } else {
                var get_min = parseInt(get_input_time*60000);
                $.APP.t1 += get_min; // Set to minutes
              }
            }    
            break; 
        }                                   
        // reset state
        $.APP.state = 'alive';   
        $('#' + $.APP.dir + '_status').html('Game is in progress');
        // start loop
        $.APP.loopTimer();
      },
      pauseTimer : function() {
        // save timestamp of pause
        $.APP.dp = new Date();
        $.APP.tp = $.APP.dp.getTime();
        // save elapsed time (until pause)
        $.APP.td = $.APP.tp - $.APP.t1;
        // change button value
        $('#' + $.APP.dir + '_start').val('Resume');
        // set state
        $.APP.state = 'pause';
        $('#' + $.APP.dir + '_status').html('Game is paused');
      },
      stopTimer : function() {
          // change button value
          $('#' + $.APP.dir + '_start').val('Restart');                    
          // set state
          $.APP.state = "stop";
          $('#' + $.APP.dir + '_status').html('Stopped');
      },
      resetTimer : function() {
          // reset display
          $('#' + $.APP.dir + '_ms,#' + $.APP.dir + '_s,#' + $.APP.dir + '_m,#' + $.APP.dir + '_h').html('00');                 
          // change button value
          $('#' + $.APP.dir + '_start').val('Start');                    
          // set state
          $.APP.state = 'reset';  
          $('#' + $.APP.dir + '_status').html('Game clock reset');
      },
      endTimer : function(callback) {
        // change button value
        $('#' + $.APP.dir + '_start').val('Restart');
        // set state
        $.APP.state = 'end';
        // invoke callback
        if (typeof callback === 'function') {
        }    
            callback();
      },    
      loopTimer : function() {
        var td;
        var d2,t2;
        var ms = 0;
        var s  = 0;
        var m  = 0;
        var h  = 0;
        if ($.APP.state === 'alive') { 
          // get current date and convert it into 
          // timestamp for calculations
          d2 = new Date();
          t2 = d2.getTime();   
          // calculate time difference between
          // initial and current timestamp
          if ($.APP.dir === 'sw') {
              td = t2 - $.APP.t1;
          // reversed if countdown
          } else {
            td = $.APP.t1 - t2;
            if (td <= 0) {
              // if time difference is 0 end countdown
              $.APP.endTimer(function(){ // Time end restart time
                $.APP.resetTimer();
                // Activate stop/pause button
//                 $('#cd_pause').trigger('click');
                active_player_timestamp('stop', '', false);
                $('#cd_pause').hide();
                $('#cd_start').show();
                $('#' + $.APP.dir + '_status').html('Game has finished. Start 2nd Half, OT or End Game(If game is finished, please commit game data on bottom of the page)');
              });
            }    
          }    
          // calculate milliseconds
          ms = td%1000;
          if (ms < 1) {
            ms = 0;
          } else {    
            // calculate seconds
            s = (td-ms)/1000;
            if (s < 1) {
              s = 0;
            } else {
              // calculate minutes   
              m = (s-(s%60))/60;
              if (m < 1) {
                m = 0;
              } else {
                // calculate hours
                h = (m-(m%60))/60;
                if (h < 1) {
                  h = 0;
                }                             
              }    
            }
          }
          // substract elapsed minutes & hours
          ms = Math.round(ms/100);
          s  = s-(m*60);
          m  = m-(h*60);                                
          // update display
          $('#' + $.APP.dir + '_ms').html($.APP.formatTimer(ms));
          $('#' + $.APP.dir + '_s').html($.APP.formatTimer(s));
          $('#' + $.APP.dir + '_m').html($.APP.formatTimer(m));
          $('#' + $.APP.dir + '_h').html($.APP.formatTimer(h));
          // loop
          $.APP.t = setTimeout($.APP.loopTimer,1);
        } else {
          // kill loop
          clearTimeout($.APP.t);
          return true;
        }  
      }
    }    
  });
  $('#cd_start').on('click', function() {
    var check_hm_active_roster = document.getElementById('drop_home_active');
    var check_awy_active_roster = document.getElementById('drop_away_active');
    if( $('#cd_minutes').val() == '' ){
      alert("Please set game time");
    }
    else if( (check_hm_active_roster.hasChildNodes() && check_awy_active_roster.hasChildNodes() ) === false) {
      alert("Please make sure to have player(s) on both home and away active roster before start game clock.");
    }
    else{
      $.APP.startTimer('cd');
      active_player_timestamp('start', '', false);
      $('#cd_pause').show();
      $('#cd_start').hide();
      $('#cd_reset').hide();
    }
  });           
//   $('#cd_stop').on('click', function() {
//     $.APP.stopTimer();
//   });
  $('#cd_reset').on('click', function() {
    $.APP.resetTimer();
  });  
  $('#cd_pause').on('click', function() {
    $.APP.pauseTimer();
    active_player_timestamp('stop', '', false);
    $('#cd_pause').hide();
    $('#cd_start').show();
    $('#cd_reset').show();
  });
  /**
  * Create Home and Away Player Zero
  * Scenario: Team shows up without roster attached or missing player(s)
  */
  var query_string = window.location.search;
  var url_params = new URLSearchParams(query_string);
  var event_id = url_params.get('ev_game');
  var home_pl_zero_id = '11000'; // Hardcoded home player zero
  var away_pl_zero_id = '11001'; // Hardcoded away player zero
var home_pl_default_shell = 
      '<div id="gm_st_'+event_id+'_'+home_pl_zero_id+'" class="draggable grid-x grid-margin-x small-margin-collapse tiny-margin-bottom"><input type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][player]" value="'+home_pl_zero_id+'"><input type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][homeaway]" value="home"><input class="active_pl_tt" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][time_pl]" value=""><input class="active_pl_t_log" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][time_log]" value=""><div class="cell hide-for-small-only medium-4 large-3 handle large_profile_img" style="background: black url(https://dev.grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif) no-repeat center; background-size: cover;"><a class="button sub_button no-margin-bottom" id="pl_sub_button_gm_st_'+event_id+'_'+home_pl_zero_id+'">+</a><div class="large_profile_title"><h3 class="profile_title no-margin-bottom"><span>0</span><small>Home Player Zero</small></h3></div></div><div class="cell small-12 medium-8 large-9"><div class="grid-x hide-for-active">  <div class="grid-x"><div class="cell small-1 hide-for-medium"><img src="https://dev.grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif"></div><div class="cell small-8 medium-auto"><h3 class="no-margin-bottom small-small-margin-left">Home Player Zero</h3></div><div class="cell small-3 medium-shrink"><h3 class="no-margin-bottom text-right small-small-margin-right">0</h3></div></div><a style="font-size:15px;padding:0" class="cell button sub_button no-margin-bottom small-12 medium-12 large-12" id="pl_sub_button_gm_st_'+event_id+'_'+home_pl_zero_id+'">+</a>    </div><table class="player_stat no-margin-bottom text-left" id="home_team"><tbody><tr><td colspan="6"><table class="no-margin-bottom text-left"><tbody><tr><th>PTS</th><td><a class="one_point button">1</a></td><td><a class="two_point button">2</a></td><td><a class="three_point button">3</a></td><td><a class="minus_one_point button">-</a></td><td><span id="indi_player_pub_point-gm_st_'+event_id+'_'+home_pl_zero_id+'" class=""></span><input id="indi_player_point-gm_st_'+event_id+'_'+home_pl_zero_id+'" class="indi_player_point home_team" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][pts]" value=""></td></tr></tbody></table></td></tr><tr><td colspan="3"><table class="no-margin-bottom text-left"><tbody><tr><th>REB</th><td><a class="minus_one_reb button">-</a></td><td><a class="one_reb button">+</a></td><td><span id="indi_player_pub_reb-gm_st_'+event_id+'_'+home_pl_zero_id+'" class=""></span><input id="indi_player_reb-gm_st_'+event_id+'_'+home_pl_zero_id+'" class="indi_player_reb" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][rbs]" value=""></td></tr></tbody></table></td><td colspan="3">  <table class="no-margin-bottom text-left">   <tbody><tr>     <th>AST</th>     <td><a class="minus_one_ast button">-</a></td>     <td><a class="one_ast button">+</a></td>     <td><span id="indi_player_pub_ast-gm_st_'+event_id+'_'+home_pl_zero_id+'" class=""></span><input id="indi_player_ast-gm_st_'+event_id+'_'+home_pl_zero_id+'" class="indi_player_ast" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][ast]" value=""></td>         </tr>       </tbody></table>     </td>   </tr>   <tr>     <td colspan="3">       <table class="no-margin-bottom text-left">         <tbody><tr>           <th>STL</th>           <td><a class="minus_one_st button">-</a></td>           <td><a class="one_st button">+</a></td>           <td><span id="indi_player_pub_st-gm_st_'+event_id+'_'+home_pl_zero_id+'" class=""></span><input id="indi_player_st-gm_st_'+event_id+'_'+home_pl_zero_id+'" class="indi_player_st" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][stl]" value=""></td>        </tr>      </tbody></table>    </td>    <td colspan="3">      <table class="no-margin-bottom text-left">        <tbody><tr>          <th>BLK</th>          <td><a class="minus_one_blk button">-</a></td>          <td><a class="one_blk button">+</a></td>          <td><span id="indi_player_pub_blk-gm_st_'+event_id+'_'+home_pl_zero_id+'" class=""></span><input id="indi_player_blk-gm_st_'+event_id+'_'+home_pl_zero_id+'" class="indi_player_blk" type="hidden" name="gm_st_'+event_id+'[data][players]['+home_pl_zero_id+'][stats][blk]" value=""></td>       </tr>      </tbody></table>    </td>  </tr    </tbody></table> </div>    </div>';
var away_pl_default_shell = 
      '<div id="gm_st_'+event_id+'_'+away_pl_zero_id+'" class="draggable grid-x grid-margin-x small-margin-collapse tiny-margin-bottom"><input type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][player]" value="'+away_pl_zero_id+'"><input type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][homeaway]" value="away"><input class="active_pl_tt" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][time_pl]" value=""><input class="active_pl_t_log" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][time_log]" value=""><div class="cell hide-for-small-only medium-4 large-3 handle large_profile_img" style="background: black url(https://dev.grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif) no-repeat center; background-size: cover;"><a class="button sub_button no-margin-bottom" id="pl_sub_button_gm_st_'+event_id+'_'+away_pl_zero_id+'">+</a><div class="large_profile_title"><h3 class="profile_title no-margin-bottom"><span>0</span><small>Away Player Zero</small></h3></div></div><div class="cell small-12 medium-8 large-9"><div class="grid-x hide-for-active">  <div class="grid-x"><div class="cell small-1 hide-for-medium"><img src="https://dev.grassroots365.com/wp-content/uploads/event-profiles/g365_profile_placeholder.gif"></div><div class="cell small-8 medium-auto"><h3 class="no-margin-bottom small-small-margin-left">Away Player Zero</h3></div><div class="cell small-3 medium-shrink"><h3 class="no-margin-bottom text-right small-small-margin-right">0</h3></div></div><a style="font-size:15px;padding:0" class="cell button sub_button no-margin-bottom small-12 medium-12 large-12" id="pl_sub_button_gm_st_'+event_id+'_'+away_pl_zero_id+'">+</a>    </div><table class="player_stat no-margin-bottom text-left" id="away_team"><tbody><tr><td colspan="6"><table class="no-margin-bottom text-left"><tbody><tr><th>PTS</th><td><a class="one_point button">1</a></td><td><a class="two_point button">2</a></td><td><a class="three_point button">3</a></td><td><a class="minus_one_point button">-</a></td><td><span id="indi_player_pub_point-gm_st_'+event_id+'_'+away_pl_zero_id+'" class=""></span><input id="indi_player_point-gm_st_'+event_id+'_'+away_pl_zero_id+'" class="indi_player_point away_team" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][pts]" value=""></td></tr></tbody></table></td></tr><tr><td colspan="3"><table class="no-margin-bottom text-left"><tbody><tr><th>REB</th><td><a class="minus_one_reb button">-</a></td><td><a class="one_reb button">+</a></td><td><span id="indi_player_pub_reb-gm_st_'+event_id+'_'+away_pl_zero_id+'" class=""></span><input id="indi_player_reb-gm_st_'+event_id+'_'+away_pl_zero_id+'" class="indi_player_reb" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][rbs]" value=""></td></tr></tbody></table></td><td colspan="3">  <table class="no-margin-bottom text-left">   <tbody><tr>     <th>AST</th>     <td><a class="minus_one_ast button">-</a></td>     <td><a class="one_ast button">+</a></td>     <td><span id="indi_player_pub_ast-gm_st_'+event_id+'_'+away_pl_zero_id+'" class=""></span><input id="indi_player_ast-gm_st_'+event_id+'_'+away_pl_zero_id+'" class="indi_player_ast" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][ast]" value=""></td>         </tr>       </tbody></table>     </td>   </tr>   <tr>     <td colspan="3">       <table class="no-margin-bottom text-left">         <tbody><tr>           <th>STL</th>           <td><a class="minus_one_st button">-</a></td>           <td><a class="one_st button">+</a></td>           <td><span id="indi_player_pub_st-gm_st_'+event_id+'_'+away_pl_zero_id+'" class=""></span><input id="indi_player_st-gm_st_'+event_id+'_'+away_pl_zero_id+'" class="indi_player_st" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][stl]" value=""></td>        </tr>      </tbody></table>    </td>    <td colspan="3">      <table class="no-margin-bottom text-left">        <tbody><tr>          <th>BLK</th>          <td><a class="minus_one_blk button">-</a></td>          <td><a class="one_blk button">+</a></td>          <td><span id="indi_player_pub_blk-gm_st_'+event_id+'_'+away_pl_zero_id+'" class=""></span><input id="indi_player_blk-gm_st_'+event_id+'_'+away_pl_zero_id+'" class="indi_player_blk" type="hidden" name="gm_st_'+event_id+'[data][players]['+away_pl_zero_id+'][stats][blk]" value=""></td>       </tr>      </tbody></table>    </td>  </tr    </tbody></table> </div>    </div>';
  
$(document).on('click', '#home_pl_zero', target_container, function(){
  if( $('#gm_st_'+event_id+'_'+home_pl_zero_id+'').length ){
    alert("You already had home player zero added.");
  } else {
    $('#drop_home_inactive').append(home_pl_default_shell);
    $('#home_pl_zero').remove();
  }
});
$(document).on('click', '#away_pl_zero', target_container, function(){
  if( $('#gm_st_'+event_id+'_'+away_pl_zero_id+'').length ){
    alert("You already had away player zero added.");
  } else {
    $('#drop_away_inactive').append(away_pl_default_shell);
    $('#away_pl_zero').remove();
  }
});
  function slb_form_submission(element){
  var id = $(element).attr('id');
  var form = document.getElementById('statleader-form');
  document.getElementById('stat_catagory').value = id;
  form.submit();
}
  /**
  * End Create Home and Away Player Zero
  */
/**************** End Score Plateform Interface ****************/ 
}