//make images work on player profiles
// var g365_profile_event_imgs = $('#event-imgs');
// if (g365_profile_event_imgs.length > 0) {
//   g365_profile_event_imgs.on('change.zf.tabs', function(ev, tar) {
//     console.log('tabs fired', ev, tar, $(tar.children('a').attr('href') + '-data'));
//     $('#event-stats .tabs-panel').attr('aria-hidden', true).removeClass('is-active');
//     $(tar.children('a').attr('href') + '-data').attr('aria-hidden', false).addClass('is-active');
//   });
// }

//start any functions that depend on foundations to be loaded. at the bottom
var the_doc = $(document);
the_doc.foundation();

//edit form in reveal
$( '.g365-edit-data' ).on('click', function(){
  var edit_but = $(this);
  var type_ids = edit_but.attr('data-g365_type');
  if( edit_but.length !== 1 && typeof type_ids === 'undefined' || type_ids === '' ) return false;
  type_ids = type_ids.split('::');
  var type_name = type_ids.shiftre
  var form_data = {
    query_type : type_name,
    id : ((type_ids.length === 0) ? null : type_ids),
    go_flat: false,
    template_format: ((typeof edit_but.attr('data-g365_form_template') === 'undefined') ? 'form_template_init' : edit_but.attr('data-g365_form_template')),
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
    var form_new_loaded = $( form_template_message ).prependTo( load_target.empty() );
    g365_form_start_up( form_new_loaded );
    $( '#g365_form_reveal' ).foundation( 'open' );
  });
});


$('.field-toggle', the_doc).click(function(){ g365_field_toggle( $(this) ); });

//for the splash closer
var g365_reveal_closer_today_button = $('#reveal_close_today');
if (g365_reveal_closer_today_button.length > 0) {
  g365_reveal_closer_today_button.on('click', function() {
    localStorage.setItem("g365_close_today", 'true');
    localStorage.setItem("g365_close_today_date", new Date() );
  });
}

//home page news article rotator
var g365_news_rotator = $('#news_rotator');
if( g365_news_rotator.length > 0 ) {
	g365_news_rotator.slick({
		autoplay: true,
		autoplaySpeed: 2000,
		arrows: false,
		dots: false
	});
	var g365_news_nav = $('#news_nav');
	var g365_news_nav_div = $('div', g365_news_nav);
	var g365_news_nav_a = $('a', g365_news_nav);
	function g365_select_nav(select_button) {
		g365_news_nav_a.attr("aria-selected","false").blur().parent().removeClass('is-active');
		select_button.attr("aria-selected","true").parent().addClass('is-active');
	}
	g365_news_rotator.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		g365_select_nav($('a', g365_news_nav_div[nextSlide]));
	});
	$('#slider-wrapper').on('mouseenter', function(){g365_news_rotator.slick('slickPause')});
	$('#slider-wrapper').on('mouseleave', function(){g365_news_rotator.slick('slickPlay')});
	g365_news_nav_a.on('click', function(e){
		e.preventDefault();
		var select_this = $(this);
		$('#news_rotator').slick('slickGoTo', select_this.parent().index());
		g365_select_nav(select_this);
	});
	g365_select_nav($(g365_news_nav_a[0]));
}

//support calendar table navigation if needed
var g365_table_hover_link = $('table.hover');
if( g365_table_hover_link.length > 0 ) {
  g365_table_hover_link.on('click','tr.event-line', function(){
    if( typeof $(this).attr('data-event_link') === 'undefined' ) return;
    var win = window.open($(this).attr('data-event_link'), '_blank');
  });
}

//make display rotator
var g365_display_rotator = $("#event_display_rotator");
if (g365_display_rotator.length > 0) {
  g365_display_rotator.slick({
    autoplay: true,
    autoplaySpeed: 4000,
    arrows: false,
    dots: false
  });
}


//event menu switcher
var event_menu_buttons = $('.event-menu-button', '#main-nav');
if( event_menu_buttons.length > 0 ) {
  $('a', event_menu_buttons).on('click', function(e){
    e.stopPropagation();
    var this_parent = $(this).parent();
    event_menu_buttons.removeClass('selected-tab');
    this_parent.addClass('selected-tab');
    event_menu_buttons.each(function(){ $('.' + $(this).attr('data-ev-target') ).addClass('hide'); });
    $('.' + this_parent.attr('data-ev-target') ).removeClass('hide');
  });
  $('.event-menu-button.event-menu-start a', '#main-nav').click();
}

//event region selector
var revealers = $('.revealer-column', '#event-menu-region');
if( revealers.length > 0 ) {
  $('.revealer-main #g365-all-regions-button', '#event-menu-region').click(function(e){
    e.stopPropagation();
    $(this).slideUp();
    $('.helper-title', '#event-menu-region').slideDown();
    revealers.removeClass('revealed-column hidden-column');
  });
  $('.revealer-column .nav-title', '#event-menu-region').click(function(e){
    e.stopPropagation();
    $('.revealer-main #g365-all-regions-button', '#event-menu-region').slideDown({
      start: function() {
        jQuery(this).css('display','inline-block');
      }
    });
    $('.helper-title', '#event-menu-region').slideUp();
    revealers.removeClass('revealed-column').addClass('hidden-column');
    $(this).closest('.revealer-column').addClass('revealed-column').removeClass('hidden-column');
  });
  $('.revealer-column.revealer-start .nav-title').click();
}

//event season accordion
var resizers = $('.resizer-column', '#event-menu-season');
if( resizers.length > 0 ) {
  $('.resizer-main #g365-all-events-button', '#event-menu-season').click(function(e){
    e.stopPropagation();
    $(this).slideUp();
    resizers.removeClass('expanded-column collapse-column');
    resizers.prev().removeClass('expanded-label-column collapse-label-column');
  });
  $('.resizer-column .nav-title', '#event-menu-season').click(function(e){
    e.stopPropagation();
    $('.resizer-main #g365-all-events-button', '#event-menu-season').slideDown();
    resizers.removeClass('expanded-column').addClass('collapse-column');
    resizers.prev().removeClass('expanded-label-column').addClass('collapse-label-column');
    $(this).closest('.resizer-column').addClass('expanded-column').removeClass('collapse-column').prev().addClass('expanded-label-column').removeClass('collapse-label-column');
  });
  $('.resizer-column.resizer-start .nav-title').click();
}

//toggles the next element
$('.toggle-next').click(function(){ $(this).next().toggleClass('hide');});

//mega menu support (full page)
$('[data-curtain-menu-button]').click(function(){
  $('body').toggleClass('curtain-menu-open');
})
//slide menu support 
$('[data-side-slide-menu-button]').click(function(){
  $('body').toggleClass('side-slide-menu-open');
})
//slide menu closer
$('.main-navigation.side-slide-menu-wrapper').click(function(e){ if (e.target !== this) return; $(this).prev().click(); });

//switch between series pages
$("#series_selector, #view_switch").change(function(){
  window.location.href = $( "option:selected", this ).val();
});

//for adding 1 to input box in input-groups
$('.button.add-button', '.input-group').click(function() {
  var target = $(this).prev( '.input-number' );
  var new_val = parseInt(target.val()) + 1;
  new_val = ( new_val > parseInt(target.attr('max')) ) ? target.attr('max') : new_val; 
  target.val( new_val ).change();
});
//for subtracting 1 to input box in input-groups
$('.button.minus-button', '.input-group').click(function() {
  var target = $(this).next( '.input-number' );
  var new_val = parseInt(target.val()) - 1;
  new_val = ( new_val < parseInt(target.attr('min')) ) ? target.attr('min') : new_val; 
  target.val( new_val ).change();
});
//for creating sub totals in input boxes with input-groups
$('.input-quantity', '.input-group').change(function() {
  var this_quantity = $(this);
  this_quantity.siblings( '.target-total' ).children('span').html( this_quantity.siblings( '.target-number' ).attr('data-target_number') * this_quantity.val() ).change();
});
//add all sub totals together
var all_sub_totals = $('.calc-sub-total', '.input-group');
all_sub_totals.change(function() {
  var total_price = 0;
  all_sub_totals.each(function(){ total_price += parseInt($(this).html()); });
  $( '#calc-total' ).html( total_price );
});



//change the variation prices based on the dropdown. 
$( '.variations select' ).change(function(){
  var selector = $(this);
  var selector_parent = selector.closest('.product');
  var target_price = $( '.main-price', selector_parent );
  if( typeof target_price.data( 'var_prices_html' ) === 'undefined' ) {
    var var_prices_html = [];
    if( $( ':first-child', selector ).val() === '' ) var_prices_html[0] = target_price.html();
    var var_attr_full = JSON.parse($('.variations_form', selector_parent).attr('data-product_variations'));
    $.each(var_attr_full, function(attr_dex, attr_vals){
      var_prices_html[attr_dex+1] = attr_vals.price_html.replace('.00', '');
    });
    target_price.data( 'var_prices_html', var_prices_html );
  }
  var target_price_options = target_price.data( 'var_prices_html');
  target_price.html( target_price_options[$( 'option:selected', selector ).index()] );
}).change();

//add multiple variations to cart with quantities
$('.summary .variations_collector', '#content').click(function() {
  var var_parent = $('.variations', '.summary');
  var url_string = [];
  $('input.variable-input', var_parent).each(function(){
    var input_target = $(this);
    if( input_target.val() > 0 ) url_string[url_string.length] = input_target.attr('data-var_id') + ',' + input_target.val();
  });
  if( url_string.length > 0 ) window.location = '/cart/?add-more-to-cart=' + url_string.join('|');
});

// Facebook social sharing button
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
  fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

// Twitter social sharing button
!function(d,s,id){
  var js,fjs=d.getElementsByTagName(s)[0];
  if(!d.getElementById(id)){   
    js=d.createElement(s);
    js.id=id;js.src="//platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js,fjs);
  }
}(document,"script","twitter-wjs");

// Stat Leaderboard event toggle button and view a complete tab
$('#change_event_btn').on('click', function(){
  $('#event_search_box').toggle();
});

$('.slb_more_list').on('click', function(){
  var id = $(this).attr('id');
  var form = document.getElementById('statleader-form');
  document.getElementById('stat_catagory').value = id;
  form.submit();
});

// Player profile game stat event handler
function ev_form_submit(element){
  var current_url = window.location.href;
  if(element.id !== current_url){
    var profile_game = document.getElementById("season-stats-group");
    profile_game.classList.add("page_loader");
    var form = document.getElementById('game-stat-form');
    form.action = element.id;
    form.submit();
  }
}

//start any functions that depend on foundations to be loaded.
if (window.g365_func_wrapper.found.length > 0) window.g365_func_wrapper.found.forEach( function(func){ func.name.apply(null, func.args); });

//Mobile Nav double tap fix
if (document.documentElement.clientWidth < 640) {
  var touchmoved;
  $('#site-navigation #main-nav .is-dropdown-submenu.menu a').on('touchend', function(e){
      if(touchmoved != true){
      window.location.href = $(this).attr("href");
      }
  }).on('touchmove', function(e){
      touchmoved = true;
  }).on('touchstart', function(){
      touchmoved = false;
  });
}

//Regional nav see all btn at bottom
$('#event-menu-region').append('<div class="button auto-margin-sides" id="#eventAllBtnBtmWrapper"><a href="https://grassroots365.com/calendar/" class="show-for-medium-up" id="eventAllBtnBtm">see all events</a></div>');

//Set event modal icon mobile
var iconModal = '<div class="show-for-small-only icon-modal hide">\
  <div class="icon-box">\
    <img class="menu-icon national-icon" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/National-Icon.png"/>\
    <p>National Teams Traveling to Event</p>\
  </div>\
  <div class="icon-box">\
    <img class="menu-icon western-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Western-Icon.png"/>\
    <p>Western Teams Traveling to Event</p>\
  </div>\
  <div class="icon-box">\
    <img class="menu-icon passport-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Passport-Icon.png"/>\
    <p>Official Passport Event with Certification and Stats</p>\
  </div>\
  <div class="icon-box">\
    <img class="menu-icon photo-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Photo-Icon.png"/>\
    <p>Pictures & Social Media Coverage</p>\
  </div>\
  <div class="icon-box">\
   <img class="menu-icon ranking-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Ranking-Icon.png"/>\
    <p>Official G365 Rankings Event</p>\
  </div>\
  <div class="icon-box">\
  <img class="menu-icon special-award-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/SignatureAwards-Icon.png"/>\
    <p>Signature Awards</p>\
  </div>\
  <div class="icon-box">\
    <img class="menu-icon special-event-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/SpecialEvent-Icon.png"/>\
    <p>Special Event</p>\
  </div>\
   <div class="icon-box"> \
    <img class="menu-icon video-icon" title="" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Media-Icon.png"/>\
    <p>Video & Media Team Attending</p>\
  </div>\
</div>'

$('#event-menu-region').append(iconModal);

// Remove small-margin-right and float-left from post-thumbnail on news page
$('.posts__container .post-thumbnail').removeClass('small-margin-right float-left');


// let countdownNumberEl = $('#countdown-number');
// let countdownNum = 6;

$('.event-unlock__trigger').hover(
    function(){
      $('.event__tooltip--wrapper').css("visibility", "visible");
    },function(){
        return;
    }
  );
  
  
  $('.ev_inner.is_act.event-unlock__trigger').hover(
      function(e){
        mouseLeft = e.pageX - 450;
        mouseTop = e.pageY -200;
        $('.h_ev_box-container #eventTooltip').css("visibility", "visible");
        $('.h_ev_box-container #eventTooltip').css("top", mouseTop);
        $('.h_ev_box-container #eventTooltip').css("left", mouseLeft);
      },function(){
          return;
      }
    );
  
  $('.event__tooltip--exit').on('click', function() {
      $('.event__tooltip--wrapper').css("visibility", "hidden");
  }
  );
  
  
  $('.h_ev_box-container .event__tooltip').on('click', function() {
      $('.h_ev_box-container #eventTooltip').css("visibility", "hidden");
  }
  );

  

//watchlist functions
$(function() {
    if ($('#content div').is('.watchlist')) {
    //lazy load mobile compatible  
    var lazyloadImages;    

    //check for browser support
    if ("IntersectionObserver" in window) {
        lazyloadImages = document.querySelectorAll(".lazy-img");
        var imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
            var image = entry.target;
            image.src = image.dataset.src;
            image.classList.remove('lazy-img');
            imageObserver.unobserve(image);
            }
        });
        });

        lazyloadImages.forEach(function(image) {
        imageObserver.observe(image);
        });
    } else {  
        var lazyloadThrottleTimeout;
        lazyloadImages = document.querySelectorAll(".lazy-img");
        
        function lazyload () {
        if(lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }    

        lazyloadThrottleTimeout = setTimeout(function() {
            var scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if(img.offsetTop < (window.innerHeight + scrollTop)) {
                img.src = img.dataset.src;
                img.classList.remove('lazy-img');
                }
            });
            if(lazyloadImages.length == 0) { 
            document.removeEventListener("scroll", lazyload);
            window.removeEventListener("resize", lazyload);
            window.removeEventListener("orientationChange", lazyload);
            }
        }, 20);
        }

        document.addEventListener("scroll", lazyload);
        window.addEventListener("resize", lazyload);
        window.addEventListener("orientationChange", lazyload);
    }
  }

  //jump nav
  var watchlistItems = $('#watchlistNav .alphabet-li');

    watchlistItems.on('click', function(e){
        e.preventDefault();
        var target = $(e.currentTarget);
        // console.log(target);
    
        if(target.hasClass('jump-active') == true){
            return;
        } else {
            clearActiveNav();
            setActiveNav(target);
            jumpToLetter(target);
        }
    });
    
    //initial load - loop through all currently displayed players    
    $('.watchlist__panel.is-active div').children('.cell').each(function(i){
        // console.log($(this).text().trim().charAt(0));

        //Finds first letter of each item, and applies corresponding data-alphabet to element
        $(this).attr('data-alphabet', $(this).text().trim().charAt(0));
        
        //update nav list to reflect available players
        //each player loop first letter if there is a match with the alphabet letter, move onto the next one.
        //if no match, disable hide letter in watchlistItems
    })

    

    //On Current /Top player change - listen for new is-active re-render actual players
        //update nav list to reflect available players

    //on same tab but tab change, listen for new active and re-render actual players
        //update nav list to reflect available players

    function clearActiveNav() {
        watchlistItems.removeClass('jump-active');
    }

    function setActiveNav(target) {
        target.addClass('jump-active');
    }

    function jumpToLetter(target) {
        var targetLetter = target.text();

        //match first instance of letter
        var position = $('[data-alphabet='+targetLetter+']').first().offset().top;
        // console.log('Target letter is ' + targetLetter);

        //Scroll to position - offset the header
        $('html, body').animate({
            scrollTop: (position - 65)
        });
    }
});

//Photo feature start
$(function() {
    var photoPlayerList = $('#photoPlayerList');
    var photoPlayerListItem = $('.photo__player-list--item');
    var photoAccountContainer = $('#photoAccountContainer');
    var photoSwitchPlayerBtn = $('#photoSwitchPlayerBtn');
    var accountAddPhotoBtn = $('#accountAddPhotoBtn');
    var photoLibrary = $('#photoLibrary');
    
  
    var accountImgUploadWrapper = $('#accountImgUploadWrapper');
    var accountUploadBackBtn = $('#accountUploadBackBtn');
    var accountImgUploader = document.getElementById('accountImgUploader');
    var profilePhotosImg = $('.profile-feature .photo__img');
//     var profilePhotosHomepage = $('.profile__homepage-container .photo__img');
    var photoInitialHelpText = $('#photoInitialHelpText');
    var claimedPlayerText = $('#claimedPlayerText');

    var pendingWrapper = $('#pendingWrapper');
    var pendingImgList = $('#pendingList');
    var pendingImgContainer = $('.pending__img-container');
    var pendingImg = $('.pending__img');
    var pendingCount = $('#pendingCount');
    var photoTarget;
  
    // profile homepage fullscreen
//     profilePhotosHomepage.click(function() {
//         var src = $(this).attr('src');
//         var modal;

//         function removeModal() {
//             modal.remove();
//             $('body').off('keyup.modal-close');
//         }
//         modal = $('<div>').css({
//             background: 'RGBA(0,0,0,.8) url(' + src + ') no-repeat center',
//             backgroundSize: 'contain',
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

    if(document.querySelector('.page-template-player-profile')){
    // Fullscreen image click for profile view
    profilePhotosImg.click(function() {
        var src = $(this).attr('src');
        photoCurrentOrder = parseInt($(this).attr('data-order'));
        photoCurrentOrder++;
        var modal;

        function removeModal() {
            modal.remove();
            $('body').off('keyup.modal-close');
            removeProfileGalleryNav();
        }
        modal = $('<div>').css({
            background: 'RGBA(0,0,0,0.9) url(' + src + ') no-repeat center',
            backgroundSize: 'auto',
            width: '100%',
            height: '100%',
            position: 'fixed',
            zIndex: '10000',
            top: '0',
            left: '0',
            cursor: 'zoom-out'
        }).addClass('gallery-fullscreen')
          .click(function() {
            removeModal();
          
        }).appendTo('body');
        //handling ESC
        $('body').on('keyup.modal-close', function(e) {
            if (e.key === 'Escape') {
            removeModal();
            }
        });
      
         renderProfileGalleryNav(photoCurrentOrder);
    });
        
        var photoArray;
        var photoCurrentOrder;
         var firstNum;  
         var lastNum;
      
         createProfilePhotosArray();

        function renderProfileGalleryNav(photoCurrentOrder) {
          var navContainer = $('<div>').addClass('gallery__nav');
          var arrowLeft = $('<i>').addClass('fi-arrow-left');
          var arrowRight = $('<i>').addClass('fi-arrow-right');
          var textCount = $('<p>').addClass('gallery__nav--text');
          

          function removeModal() {
              modal.remove();
          }
          
          navContainer.append(arrowLeft, textCount, arrowRight);
          navContainer.appendTo('body');
          countGalleryNav(photoCurrentOrder);
          
          var splitText = $('.gallery__nav--text').html().split(' / ');
          firstNum = $('#currentPhotoNum');
          lastNum = splitText[1];
          
          checkNavCount(firstNum, lastNum);
//           console.log(typeof firstNum);
          
          arrowLeft.click(function() {
            updateGalleryCount('subtract');
          })
          
          arrowRight.click(function() {
            updateGalleryCount('add',firstNum);
          })
        }
      
        function removeProfileGalleryNav() {
          $('.gallery__nav').remove();
        }
      
        function updateGalleryCount(action){
          switch(action) {
            case 'subtract': 
              if(firstNum.html() == 1) {
               $('.gallery__nav .fi-arrow-left').css('opacity', 0);
              } else {
                if(firstNum.html() - 1 != lastNum) {
                  $('.gallery__nav .fi-arrow-right').css('opacity', 1);
                }
                var imgModal = $('.gallery-fullscreen');
                var newNavCount = firstNum.html() - 1;
                firstNum.html(newNavCount);
                imgModal.css({
                      background: 'RGBA(0, 0, 0, 0.9) url(' + photoArray[(newNavCount - 1)].src + ') no-repeat center',
                      backgroundSize: 'auto',
                      width: '100%',
                      height: '100%',
                      position: 'fixed',
                      zIndex: '10000',
                      top: '0',
                      left: '0',
                      cursor: 'zoom-out',
                      
                  });
                if(newNavCount == 1) {
                   $('.gallery__nav .fi-arrow-left').css('opacity', 0);
                }
              }
              break;
            case 'add':
              if(firstNum.html() == lastNum) {
               $('.gallery__nav .fi-arrow-right').css('opacity', 0);
              } else {
                $('.gallery__nav .fi-arrow-left').css('opacity', 1);
                var imgModal = $('.gallery-fullscreen');
                var newNavCount = parseInt(firstNum.html()) + 1;
                firstNum.html(newNavCount);
                imgModal.css({
                      background: 'RGBA(0, 0, 0, 0.9) url(' + photoArray[(newNavCount - 1)].src + ') no-repeat center',
                      backgroundSize: 'auto',
                      width: '100%',
                      height: '100%',
                      position: 'fixed',
                      zIndex: '10000',
                      top: '0',
                      left: '0',
                      cursor: 'zoom-out'
                  });
                if(newNavCount == lastNum) {
                   $('.gallery__nav .fi-arrow-right').css('opacity', 0);
                }
              } break;
          }
        }
      
      
        function countGalleryNav(photoCurrentOrder) {
//           count all photos
          var numPhotos = $('.profile-photos .photo__img').length;
          var navCount = $('.gallery__nav--text');
          
//           order comes from click event on image photoimg.click
          var currentOrder = photoCurrentOrder;
          
          
          var str = '<span id="currentPhotoNum">'+currentOrder+'</span>' + ' / ' + numPhotos;
          
          navCount.html(str);
          
//           update text counter
        }
      
      function checkNavCount(firstNum, lastNum){
     
          if(firstNum.html() - 1 == 0) {
            $('.gallery__nav .fi-arrow-left').css('opacity', 0);
          } 
        
        
          if(firstNum.html() === lastNum) {
            $('.gallery__nav .fi-arrow-right').css('opacity', 0);
          }
      }
      
        function createProfilePhotosArray () {
                 var photoSet = document.querySelectorAll('.profile-photos .photo__img');
                 photoArray = Array.from(photoSet);
        }
      }
  // Start account photo functions
    // only load Filepond and account on my account player_images page
    if(document.getElementById('photoPlayerList')) {

        //Register FilePond Plugins
        FilePond.registerPlugin(
            FilePondPluginFileValidateType, 
            FilePondPluginImagePreview, 
            FilePondPluginFileValidateSize,
            FilePondPluginImageTransform
        );

        //Create Filepond instance and configure settungs
        var accountPond = FilePond.create(accountImgUploader, {
            acceptedFileTypes: [
                'image/jpg',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ],
//             maxFileSize: '500KB',
            allowFileSizeValidation: true,
            maxFileSize: '100MB',
            maxFiles: 5,
            imageTransformOutputQuality: 60
        });

        //TODO Check Pending Images for limit, if good proceed to allow upload

        //Test server to demo upload button
        FilePond.setOptions({
            server: './',
            instantUpload: false,
        });

        //Count initial pending images and homepage images assign value
        countPending();
        setInitialHomepageText();

        // Check for mobile and assign click/tap event listeners for context menus
        var isMobile;
        
        function mobileCheck() {
            if(window.outerWidth < 1100) {
                isMobile = true;
            } else  {
                isMobile = false;
            }
        }   

        mobileCheck();
        
          var photoArray;
          var photoCurrentOrder;
//      Desktop clicks assign
        function assignPhotoClick() {
          var photoImg = $('#photoAccountContainer .photo__img');
          
          if(isMobile == false) {
              //primary click action (fullscreen)
              photoImg.click(function() {
                  removeContextMenu();
//                   var parentElement = $(this).parent();
                  var src = $(this).attr('src');
                  photoCurrentOrder = $(this).attr('data-order');
                  
                
                  var modal;

                  function removeModal() {
                      modal.remove();
                      $('body').off('keyup.modal-close');
                      removeGalleryNav();
                  }
                
                  modal = $('<div>').css({
                      background: 'RGBA(0, 0, 0, 0.8) url(' + src + ') no-repeat center',
                      backgroundSize: 'contain',
                      width: '100%',
                      height: '100%',
                      position: 'fixed',
                      zIndex: '10000',
                      top: '0',
                      left: '0',
                      cursor: 'zoom-out'
                  }).addClass('gallery-fullscreen')
                    .click(function() {
                      removeModal();
                  }).appendTo('body');
                  //handling ESC
                  $('body').on('keyup.modal-close', function(e) {
                      if (e.key === 'Escape') {
                      removeModal();
                      }
                  });
                
            
                  renderGalleryNav(photoCurrentOrder); 
              });

              //right click action
              photoImg.on('contextmenu', function(e){
                  e.preventDefault();
                  var target = e.currentTarget;
                  //Assign target photo to global var for use in context click handle
                  photoTarget = target;

                  console.log(photoTarget);
                  showContextMenu(e.clientX, e.clientY, target, 'library');
                  $('#contextMenu').on('click', contextClickHandler);
              });

              // pending images cancel action 
              pendingImg.on('contextmenu', function(e){
                  e.preventDefault();
                  var target = e.currentTarget;

                  console.log('image pending is:'+ target);
                  showContextMenu(e.clientX, e.clientY, target, 'pending')
                  $('#contextMenu').on('click', contextClickHandler);
              });
            
            
          } else {
          //Mobile Click Assign
              photoImg.click(
                  function(e) {
                      var src = $(this).attr('src');
                      var modal;

                      function removeModal() {
                          modal.remove();
                      }
                    
                      modal = $('<div>').css({
                          background: 'RGBA(0,0,0,.8) url(' + src + ') no-repeat center',
                          backgroundSize: '40%',
                          backgroundRepeat: "no-repeat",
                          backgroundPosition: "50% 20%",
                          width: '100%',
                          height: '100%',
                          position: 'fixed',
                          zIndex: '1000',
                          top: '0',
                          left: '0',
                          cursor: 'zoom-out'
                      }).addClass('mobilePhotoModal')
                      .click(function() {
                          removeModal();
                          removeContextMenu();
                      }).appendTo('body');

                      //context menu
                      var target = e.currentTarget;

                      //Assign target photo to global var for use in context click handle
                      photoTarget = target;

                      console.log(photoTarget);
                      showContextMenu(e.clientX, e.clientY, target, 'libraryMobile');
                      $('#contextMenu').on('click', contextClickHandler);
                  });

              // pending images cancel action 
              pendingImg.on('click', function(e){
                  e.preventDefault();
                  var target = e.currentTarget;

                  console.log('image pending is:'+ target);
                  showContextMenu(e.clientX, e.clientY, target, 'pendingMobile')
                  $('#contextMenu').on('click', contextClickHandler);
              });
          }
        }

        photoSwitchPlayerBtn.on('click', function(){
            hideAccountPhotos();
            clearAccountPhotos();
            hideAccountImgUpload();
            renderPlayerList();
            hidePlayerSwitch();
        })

        photoPlayerListItem.on('click',function(e){
            e.preventDefault();
            var target = $(e.currentTarget);
            console.log('player:' + target+ "clicked");
            var targetID = target.attr('data-id');
            
            target.addClass('active');
            clearPlayerList(targetID);
            showPlayerSwitch();

            // loadAccountPhotos(targetID);

            //show photo account section
            renderAccountPhotos();
            showAccountPhotos();
            assignPhotoClick();
        });

        accountAddPhotoBtn.on('click', function(){
            hideAccountPhotos();
            showAccountImgUpload();
        });

        accountUploadBackBtn.on('click', function() {
            showAccountPhotos();
            hideAccountImgUpload();
        });
        
        
        //pending list display on click, Allow Click through container for cancel request
        pendingWrapper.on('click', function(e){
            console.log(e.target);
            if(e.target.id == "cancelRequestBtn"){
                return;
            } else {
                pendingImgContainer.slideDown(300, function() {
                    if (pendingImgContainer.is(':visible'))
                    pendingImgContainer.css('display','flex');
                });
            }
        });

        $('#page').click(function(e) {
            if(!e.target.classList.contains('photo__img') && document.getElementById('contextMenu')) {
                removeContextMenu();
            } else {
                return;
            }
        })
      
         var firstNum;  
         var lastNum;
      

        function renderGalleryNav(photoCurrentOrder) {
          var navContainer = $('<div>').addClass('gallery__nav');
          var arrowLeft = $('<i>').addClass('fi-arrow-left');
          var arrowRight = $('<i>').addClass('fi-arrow-right');
          var textCount = $('<p>').addClass('gallery__nav--text');
          

          function removeModal() {
              modal.remove();
          }
          
          navContainer.append(arrowLeft, textCount, arrowRight);
          navContainer.appendTo('body');
          countGalleryNav(photoCurrentOrder);
          
          var splitText = $('.gallery__nav--text').html().split(' / ');
          firstNum = $('#currentPhotoNum');
          lastNum = splitText[1];
          
          checkNavCount(firstNum, lastNum);
//           console.log(typeof firstNum);
          
          arrowLeft.click(function() {
            updateGalleryCount('subtract');
          })
          
          arrowRight.click(function() {
            updateGalleryCount('add',firstNum);
          })
        }
      
        function updateGalleryCount(action){
          switch(action) {
            case 'subtract': 
              if(firstNum.html() == 1) {
               $('.gallery__nav .fi-arrow-left').css('opacity', 0);
              } else {
                if(firstNum.html() - 1 != lastNum) {
                  $('.gallery__nav .fi-arrow-right').css('opacity', 1);
                }
                var imgModal = $('.gallery-fullscreen');
                console.log(firstNum);
                
                var newNavCount = firstNum.html() - 1;
                
                console.log('new count '+ newNavCount);
                firstNum.html(newNavCount);
                imgModal.css({
                      background: 'RGBA(0, 0, 0, 0.8) url(' + photoArray[(newNavCount - 1)].src + ') no-repeat center',
                      backgroundSize: 'contain',
                      width: '100%',
                      height: '100%',
                      position: 'fixed',
                      zIndex: '10000',
                      top: '0',
                      left: '0',
                      cursor: 'zoom-out'
                  });
                if(newNavCount == 1) {
                   $('.gallery__nav .fi-arrow-left').css('opacity', 0);
                }
              }
              break;
            case 'add':
              if(firstNum.html() == lastNum) {
               $('.gallery__nav .fi-arrow-right').css('opacity', 0);
              } else {
                $('.gallery__nav .fi-arrow-left').css('opacity', 1);
                var imgModal = $('.gallery-fullscreen');
                console.log(firstNum);
                var newNavCount = parseInt(firstNum.html()) + 1;
                console.log('new count '+ newNavCount);
                firstNum.html(newNavCount);imgModal.css({
                      background: 'RGBA(0, 0, 0, 0.8) url(' + photoArray[(newNavCount - 1)].src + ') no-repeat center',
                      backgroundSize: 'contain',
                      width: '100%',
                      height: '100%',
                      position: 'fixed',
                      zIndex: '10000',
                      top: '0',
                      left: '0',
                      cursor: 'zoom-out'
                  });
                if(newNavCount == lastNum) {
                   $('.gallery__nav .fi-arrow-right').css('opacity', 0);
                }
              } break;
          }
        }
      
        function removeGalleryNav() {
          $('.gallery__nav').remove();
        }
      
        function countGalleryNav(photoCurrentOrder) {
//           count all photos
          var numPhotos = $('.photo__library .photo__img-container').length;
          var navCount = $('.gallery__nav--text');
          
//           order comes from click event on image photoimg.click
          var currentOrder = photoCurrentOrder;
          
          
          var str = '<span id="currentPhotoNum">'+currentOrder+'</span>' + ' / ' + numPhotos;
          
          navCount.html(str);
          
//           update text counter
        }
      
      function checkNavCount(firstNum, lastNum){
     
          if(firstNum.html() - 1 == 0) {
            $('.gallery__nav .fi-arrow-left').css('opacity', 0);
          } 
        
        
          if(firstNum.html() === lastNum) {
            $('.gallery__nav .fi-arrow-right').css('opacity', 0);
          }
      }
      
        function showConfirmModal(type) {
            var modal;
            var txt;
            var cancelBtn;
            var confirmBtn;

            switch(type) {
                case 'deletePhoto':
                    txt = $("<p></p>").text("Are you sure you want to delete this photo? Note: reuploads of photos will still require verification from our team.");
                    cancelBtn = $('<button>').text('Cancel').click(function(){
                        removeModal();
                        removeMobileModal();
                    });
                    confirmBtn = $('<button>').text('Confirm').click(function(){
                        deletePhoto();
                        removeModal();
                        removeMobileModal();
                        toast('photoDelete');
                    });
                    break;
                case 'cancelRequest':
                    txt = $("<p></p>").text("Are you sure you want to cancel the request for approval? Note: reuploads of photos will still require verification from our team.");
                    cancelBtn = $('<button>').text('Cancel').click(function(){
                        removeModal();
                        removeMobileModal();
                    });
                    confirmBtn = $('<button>').text('Confirm').click(function(){
                        cancelRequest();
                        removeModal();
                        removeMobileModal();
                        toast('photoCancel');
                    });
                    break;
            }

            function removeModal() {
                modal.remove();
            }

            //Create outer modal
            modal = $('<div>').addClass('modal__confirm--outer').appendTo('body');

            //create inner modal and attach inner content
            var confirmModal = $('<div>').addClass('modal__confirm--inner').appendTo(modal);

            cancelBtn.css({
                fontSize: '.9rem',
                color: '#696969',
                border: 'none'
            });

            confirmBtn.css({
                background: '#ec5840',
                color: 'white',
                border: 'none',
                padding: '6px 10px',
                marginLeft: '1rem'
            });


            var btnRow = $('<div>').addClass('grid-x');
            btnRow.addClass('align-right');
            btnRow.append(cancelBtn,confirmBtn);

            confirmModal.append(txt, btnRow);
        }
    

        function showContextMenu(mouseX, mouseY, target, type) {
            switch(type){
                case 'library':
                    var cursorX = mouseX+'px';
                    var cursorY = mouseY+'px';
                    var cursorSubX = mouseX+'px';
                    var cursorSubY = mouseY+'px';
                    var contextMenu = document.createElement('div');
                    var menuWidth = 170;
                    var menuHeight = 130;
                    var menuItemH = 40;
                    var submMenuHGap = menuItemH*3;
                    var isPrivate = checkPrivacy(target);
                    var isHomePhoto = checkHomePhoto(target);
                    var targetParent = target.parentElement;
                    var homePageAccountImageContainer = $('#homePageAccountImageContainer');

                    removeContextMenu();

                    // check photo privacy and assign value
                    if(isPrivate == true) {
                            menuPrivate = '<li id="publicBtn">Make Public</li>';
                        } else {
                            menuPrivate = '<li id="privateBtn">Make Private</li>';
                        }

                    //check if homepage or not TODO
                    if(isHomePhoto == false && isPrivate == true) {
                            menuHomePhoto = '';
                        } else if(isHomePhoto == false && isPrivate == false){
                            menuHomePhoto = '<li id="makeHomeBtn">Make Homepage Photo</li>';
                        } else {
                            menuHomePhoto = '<li id="removeHomeBtn">Remove Homepage Photo</li>';
                        }


                    //create context menu
                    contextMenu.innerHTML = 
                    '<div id="contextMenu">'+
                        '<ul>'+ 
                        menuPrivate +
                        menuHomePhoto +
                        '<li id="deletePhotoBtn">Delete Image</li>'
                        '</ul>'+
                    '</div>';
                    // console.table(contextMenu);
                    contextMenu.classList.add('photo__context-menu');

                    //TODO Rewrite for when hitting the edges of the menu
                    if(mouseY<menuHeight){
                        cursorY = 50;
                    } else{
                        cursorY = 50;
                    }
                    if((window.innerWidth-mouseX)<menuWidth){
                        cursorX = -75;
                        // contextMenu.classList.add("revCtxMenu");
                    } else {
                        cursorX = 50;
                    }
                    
                    contextMenu.style.top = cursorY+'%';
                    contextMenu.style.left = cursorX+'%';

                    //Attaches to photo__img-container
                    targetParent.appendChild(contextMenu);

                    
                    // var sbmnTrigger = document.getElementById('frtItem');
                    // var submenu = document.getElementsByClassName('subMenu')[0];

                    // if((window.innerHeight - mouseY)<submMenuHGap){
                    //     cursorSubY = (submMenuHGap-40)-((mouseY+submMenuHGap)-window.innerHeight);
                    // }else{
                    //     cursorSubY=submMenuHGap;
                    // }
                    // if((window.innerWidth-mouseX)<menuWidth*2){
                    //     cursorSubX = -menuWidth;
                    //     // contextMenu.classList.add("revCtxMenu");
                    //     }else{
                    //     cursorSubX = menuWidth;
                    //     }
                    break;
                case 'libraryMobile':
                    var cursorX = mouseX+'px';
                    var cursorY = mouseY+'px';
                    var cursorSubX = mouseX+'px';
                    var cursorSubY = mouseY+'px';
                    var contextMenu = document.createElement('div');
                    var menuWidth = 170;
                    var menuHeight = 130;
                    var menuItemH = 40;
                    var submMenuHGap = menuItemH*3;
                    var isPrivate = checkPrivacy(target);
                    var isHomePhoto = checkHomePhoto(target);
                    var targetParent = target.parentElement;
                    var homePageAccountImageContainer = $('#homePageAccountImageContainer');

                    removeContextMenu();

                    // check photo privacy and assign value
                    if(isPrivate == true) {
                            menuPrivate = '<li id="publicBtn">Make Public</li>';
                        } else {
                            menuPrivate = '<li id="privateBtn">Make Private</li>';
                        }

                    //check if homepage or not TODO
                    if(isHomePhoto == false && isPrivate == true) {
                            menuHomePhoto = '';
                        } else if(isHomePhoto == false && isPrivate == false){
                            menuHomePhoto = '<li id="makeHomeBtn">Mark as Homepage Photo</li>';
                        } else {
                            menuHomePhoto = '<li id="removeHomeBtn">Remove from Homepage Photo</li>';
                        }


                    //create context menu
                    contextMenu.innerHTML = 
                    '<div id="contextMenu">'+
                        '<ul>'+ 
                        menuPrivate +
                        menuHomePhoto +
                        '<li id="deletePhotoBtn">Delete Image</li>'
                        '</ul>'+
                    '</div>';
                    // console.table(contextMenu);
                    contextMenu.classList.add('photo__context-menu');

                    // //TODO Rewrite for when hitting the edges of the menu
                    // if(mouseY<menuHeight){
                    //     cursorY = 50;
                    // } else{
                    //     cursorY = 50;
                    // }
                    // if((window.innerWidth-mouseX)<menuWidth){
                    //     cursorX = mouseX-menuWidth;
                    //     // contextMenu.classList.add("revCtxMenu");
                    // } else {
                    //     cursorX = 50;
                    // }
                    
                    contextMenu.style.bottom = '10%';
                    contextMenu.style.left = '0';
                    contextMenu.style.right = '0';

                    //Attaches to photo__img-container
                    targetParent.appendChild(contextMenu);

                    
                    // var sbmnTrigger = document.getElementById('frtItem');
                    // var submenu = document.getElementsByClassName('subMenu')[0];

                    // if((window.innerHeight - mouseY)<submMenuHGap){
                    //     cursorSubY = (submMenuHGap-40)-((mouseY+submMenuHGap)-window.innerHeight);
                    // }else{
                    //     cursorSubY=submMenuHGap;
                    // }
                    // if((window.innerWidth-mouseX)<menuWidth*2){
                    //     cursorSubX = -menuWidth;
                    //     // contextMenu.classList.add("revCtxMenu");
                    //     }else{
                    //     cursorSubX = menuWidth;
                    //     }
                    break;
                case 'pending':
                    var cursorX = mouseX+'px';
                    var cursorY = mouseY+'px';
                    var contextMenu = document.createElement('div');
                    var menuWidth = 170;
                    var menuHeight = 130;
                    var menuItemH = 40;
                    var targetParent = target.parentElement;

                    removeContextMenu();


                    //create context menu
                    contextMenu.innerHTML = 
                    '<div id="contextMenu">'+
                        '<ul>'+ 
                        '<li id="cancelRequestBtn">Cancel Request</li>'
                        '</ul>'+
                    '</div>';
                    // console.table(contextMenu);
                    contextMenu.classList.add('photo__context-menu');

                    //TODO Rewrite for when hitting the edges of the menu
                    if(mouseY<menuHeight){
                        cursorY = 50;
                    } else{
                        cursorY = 50;
                    }
                    if((window.innerWidth-mouseX)<menuWidth){
                        cursorX = mouseX-menuWidth;
                        // contextMenu.classList.add("revCtxMenu");
                    } else {
                        cursorX = 50;
                    }
                    
                    contextMenu.style.top = cursorY+'%';
                    contextMenu.style.left = cursorX+'%';

                    //Attaches to photo__img-container
                    targetParent.appendChild(contextMenu);
                break;
                case 'pendingMobile':
                    var cursorX = mouseX+'px';
                    var cursorY = mouseY+'px';
                    var cursorSubX = mouseX+'px';
                    var cursorSubY = mouseY+'px';
                    var contextMenu = document.createElement('div');
                    var menuWidth = 170;
                    var menuHeight = 130;
                    var menuItemH = 40;
                    var submMenuHGap = menuItemH*3;
                    var targetParent = target.parentElement;

                    removeContextMenu();


                    //create context menu
                    contextMenu.innerHTML = 
                    '<div id="contextMenu">'+
                        '<ul>'+ 
                        '<li id="cancelRequestBtn">Cancel Request</li>'
                        '</ul>'+
                    '</div>';
                    // console.table(contextMenu);
                    contextMenu.classList.add('photo__context-menu');
                    
                    contextMenu.style.bottom = '10%';
                    contextMenu.style.left = '0';
                    contextMenu.style.right = '0';
                    
                    //Attaches to photo__img-container
                    targetParent.appendChild(contextMenu);
                break;
            }
        }
        function removeContextMenu() {
            if(document.getElementById('contextMenu')) {
                document.getElementById('contextMenu').removeEventListener('click',contextClickHandler);
            }
            $('.photo__context-menu').remove();
        }

        function contextClickHandler(e) {
            var contextSelection = e.target.id;
            switch(contextSelection) {
                case 'publicBtn':
                    makePublic();
                    break;
                case 'privateBtn':
                    makePrivate();
                    break;
                case 'makeHomeBtn':
                    countHomepage('add');
                    break;
                case 'removeHomeBtn':
                    countHomepage('remove');
                    break;
                case 'deletePhotoBtn':
                    showConfirmModal('deletePhoto');
                    break;
                case 'cancelRequestBtn':
                    showConfirmModal('cancelRequest');
                    break;
            }
        }

        function removeMobileModal() {
            if(isMobile && document.querySelector('.mobilePhotoModal')) {
                $('.mobilePhotoModal').remove();
            }
        }

        function countPending() {
            //Initial photo state is hidden, so check for that
            pendingCount.html($('#pendingImgList li').find( ":hidden" ).length);
        }

        function updatePending() {
            //when they are shown, and cancelled, cancelRequest and count again.
            var count = $('#pendingImgList').find('.pending__img').length;
            pendingCount.html(count);
        }

        //these utilize the changing photoTarget global var
        function cancelRequest() {
            photoTarget.parentNode.remove();
            updatePending();
            removeContextMenu();
        }

        function makePublic() {
            photoTarget.classList.remove('photo__private');
            photoTarget.parentNode.classList.remove('photo__private-container');
            removeContextMenu();
            removeMobileModal();
        }

        function makePrivate() {
            removeHomepage();
            photoTarget.classList.add('photo__private');
            photoTarget.parentNode.classList.add('photo__private-container');
            removeContextMenu();
            removeMobileModal();
        }

        function makeHomepage() {
            //if count is 10, break from here show messaging failure
            photoTarget.parentNode.classList.add('photo__homepage');
            var newContainer = document.createElement('div');
            var newHomeImg = document.createElement('img');

            newContainer.classList.add('photo__img-container--homepage');
            newHomeImg.classList.add('photo__img');

            newHomeImg.classList.add('photo__homepage--selected');
            newHomeImg.src = photoTarget.src;
            newContainer.appendChild(newHomeImg);
            
            homePageAccountImageContainer.appendChild(newContainer);
            removeContextMenu();
            removeMobileModal();
        }

        function countHomepage(type) {
            var count =  $('#homePageAccountImageContainer').find('.photo__img-container--homepage').length;
            var homePageText = $('.homepage-account__text');
            var defaultText = 'You may select up to 10 Homepage Images to feature on your profile.';
            var maxText = 'Max of 10 Homepage Photos reached.'

            switch(type) {
                case 'add': 
                    //on Final addition change text
                    if((count + 1) === 10) {
                        makeHomepage();
                        homePageText.html(maxText);
                    }
                    else if(count < 10) {
                        makeHomepage();
                        homePageText.html(defaultText);
                    } else 
                        homePageText.html(maxText);
                break;
                case 'remove': 
                    if((count - 1) < 10) {
                        homePageText.html(defaultText);
                    }
                    removeHomepage();
                break;

            }
            
           
        }

        function setInitialHomepageText() {
            var count =  $('#homePageAccountImageContainer').find('.photo__img-container--homepage').length;
            if(count === 10) {
                $('.homepage-account__text').html('Max of 10 Homepage Photos reached.');
            } else 
                $('.homepage-account__text').html('You may select up to 10 Homepage Images to feature on your profile.');
        }

        function removeHomepage() {
            // console.log(photoTarget + "has been removed from home");
            photoTarget.parentNode.classList.remove('photo__homepage');

            var targetSrc = photoTarget.src;

            console.log(targetSrc);
            var homeMatch = homePageAccountImageContainer.querySelectorAll('.photo__homepage--selected');
            homeMatch.forEach(function(image) {
                if(image.src == targetSrc) {
                    image.parentNode.remove();
                }
            });

            removeContextMenu();
            removeMobileModal();
        }

        function deletePhoto() {
            console.log(photoTarget + "has been deleted");

            photoTarget.parentNode.remove();
            removeContextMenu();
        }

        function checkPrivacy(target) {
            // console.log($(target).hasClass('photo__private'));
            return $(target).hasClass('photo__private');
        }

        function checkHomePhoto(target) {
            return $(target).parent().hasClass('photo__homepage');
        }

        function hideAccountImgUpload() {
            accountImgUploadWrapper.addClass('hide');
        }

        function showAccountImgUpload() {
            accountImgUploadWrapper.removeClass('hide');
        }
        function hidePlayerSwitch() {
            photoSwitchPlayerBtn.addClass('hide');
            photoInitialHelpText.removeClass('hide');
            claimedPlayerText.css('opacity', 1);
        }

        function showPlayerSwitch () {
            photoSwitchPlayerBtn.removeClass('hide');
            photoInitialHelpText.addClass('hide');
            claimedPlayerText.css('opacity', 0);
        }

        function showAccountPhotos() {
            photoAccountContainer.slideDown(300);
        }

        function renderAccountPhotos () {
//           //if no photos to display CHANGE to actual db check
//           if(photos for player == 0) {
//             photoLibrary.html('<p>You don\'t have any photos uploaded yet. Click the "Add Photo" to begin building your photo library!</p>');
//           } else {
                 var imgArray = ['1','10', '23', '64', '35','61','27', '68', '19', '70'];
                 let i = 1;
          
                 imgArray.forEach(function(image) {
                   var div = document.createElement('div');
                   var img = document.createElement('img');
                   var imgSrc = 'https://picsum.photos/id/'+image+'/100/100';
                   
                  
                   div.classList.add('photo__img-container');
                   img.classList.add('photo__img');
                   img.src = imgSrc;
                   img.setAttribute('data-order', i);
                   i++;
                   
                   div.append(img);
                   photoLibrary.append(div);
                });
          
//               set array of all sources, will tie order to src in array TODO
                 var photoSet = document.querySelectorAll('#photoLibrary .photo__img');
                 photoArray = Array.from(photoSet);
                  console.log(photoArray);
//         }
        }
      
      function clearAccountPhotos() {
        photoLibrary.empty();
      }

        function hideAccountPhotos() {
            photoAccountContainer.slideUp(300);
        }
    
        function clearPlayerList(targetID) {
            photoPlayerListItem.each(function(){
                if(targetID == $(this).attr('data-id')) {
                    return;
                } else 
                    $(this).addClass('hide');
            });
        }

        function renderPlayerList() {
            photoPlayerListItem.each(function() {
                $(this).removeClass('hide');
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
        }
    }

    function toast(msg){
        var text;
        switch(msg) {
            case 'photoDelete':
                text = 'Photo has been deleted successfully!';
            break;
            case 'photoCancel': 
                text = 'Your photo request has been cancelled successfully!';
            break;
        }
        //Remove first item in array that matches
        if(document.getElementsByClassName('toastwrp')[0]){
            document.body.removeChild(document.getElementsByClassName('toastwrp')[0]);
        }
        var toast = document.createElement('div');
        toast.innerHTML = 
            '<div id="toast" class="toast">'+
            text+
            '</div>';
        toast.classList.add("toastwrp");
        document.body.appendChild(toast);

        //Match timeout duration to toast animation length in CustomizeJL.scss
        setTimeout(
            function() {
                document.body.removeChild(document.getElementsByClassName('toastwrp')[0]);
            }, 3000
        )
    }
});
    //End account photo functions

    //render icons in event menu
    $( document ).ready(function() {
        var nationalIcon = '<img class="menu-icon national-icon" title="National Teams Traveling to Event" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/National-Icon.png"/>';
        var passportIcon = '<img class="menu-icon passport-icon" title="Official Passport Event with Certification and Stats" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Passport-Icon.png"/>';
        var photoIcon = '<img class="menu-icon photo-icon" title="Pictures & Social Media Coverage" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Photo-Icon.png"/>';
        var rankingIcon = '<img class="menu-icon ranking-icon" title="Official G365 Rankings Event" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Ranking-Icon.png"/>';
        var awardIcon = '<img class="menu-icon special-award-icon" title="Signature Awards" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/SignatureAwards-Icon.png"/>';
        var specialEventIcon = '<img class="menu-icon special-event-icon" title="Special Event" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/SpecialEvent-Icon.png"/>';
        var westernIcon = '<img class="menu-icon western-icon" title="Western Teams Traveling to Event" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Western-Icon.png"/>';
        var videoIcon = '<img class="menu-icon video-icon" title="Video & Media Team Attending" src="https://dev.grassroots365.com/wp-content/uploads/2022/03/Media-Icon.png"/>';        

        var nationalIconContainer = '<div class="icon-container">'+ 
        nationalIcon + 
        rankingIcon +
        passportIcon +
        awardIcon +
        videoIcon +
        photoIcon +
        specialEventIcon +'</div>';
        
        var signatureIconContainer = '<div class="icon-container">'+ 
        westernIcon + 
        rankingIcon +
        passportIcon +
        awardIcon +
        videoIcon +
        photoIcon + '</div>';

         var anaheimIconContainer = '<div class="icon-container">'+ 
         rankingIcon +
         passportIcon +
         photoIcon +'</div>';

        $('.national-header').append(nationalIconContainer);
        $('.signature-header').append(signatureIconContainer);
        $('.anaheim-header').append(anaheimIconContainer);
        $('.regional-header').append(signatureIconContainer);

        var isMobile;
        
        function mobileCheck() {
            if(window.outerWidth < 1100) {
                isMobile = true;
            } else  {
                isMobile = false;
            }
        }   

        mobileCheck();

        if(isMobile == true) {
            // Open event menu icon modal on mobile
            $('.icon-container').click(function(){
                $('.icon-modal').toggleClass('hide');
            });

                // Close icon modal
            $('.icon-modal').click(function(){
                $('.icon-modal').toggleClass('hide');
            });


            // Inject text from table header into sticky header
            var stickyDCPHeader = $('.tableheader--sticky');
            var statCategory = stickyDCPHeader.siblings('.stat-table').children('thead').children().children()[3].textContent;

            stickyDCPHeader.children()[3].textContent = statCategory;
        }

        function setMobileTable() {
            // if (window.innerWidth > 600) return false;
            // Grab all th names
            const tableEl = document.querySelector('.dcp-event .club-rosters table');
            const thEls = tableEl.querySelectorAll('.dcp-event .club-rosters thead th');

            // Loop through all tables and apply th names on each row. 
            const allTables = document.querySelectorAll('.dcp-event .club-rosters table');
            allTables.forEach(function(table){
                const tdLabels = Array.from(thEls).map(el => el.innerText);
                table.querySelectorAll('tr').forEach( tr => {
                Array.from(tr.children).forEach( 
                    (td, ndx) =>  td.setAttribute('label', tdLabels[ndx])
                );
                });
            })
          }
          // //sets mobile view for DCP club rosters
          if(document.querySelector('.dcp-event') && document.querySelector('.dcp-event .club-rosters table'))  {
            setMobileTable();
          }
    
    });
//end inc/app.js