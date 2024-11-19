// Player profile badge js
  function remove_bdg_modal(){
    $('.modal__confirm--outer').remove();
  }
  if($('body').hasClass('page-template-player-profile')) {
    if($('.badge-notification')) {
//       on load check for cookie badge, if exists do not display modal.
      if(getCookie('badge-cookie') !== '') {
        $('.modal__confirm--outer.badge-modal--outer').css('display', 'none');
      }else{
        $('.modal__confirm--outer.badge-modal--outer').css('display', 'block');
      }
      
      $('.badge-notification .fi-x').click(function(e){
        $('.modal__confirm--outer').remove();
      })
      
//       make cookie out of all current badges
      $('.cookieBadgeExit').click(function(e){
        var currentBadges = document.querySelectorAll('.badge-container .achievement-badge');
        var cookieArray = [];
        
        currentBadges.forEach(function(badge) {
          badgeData = badge.textContent;
            cookieArray.push(badgeData);
        })
        var json_str = JSON.stringify(cookieArray);
        
//          console.log(typeof json_str);
        createCookie('badge-cookie', json_str);
        $('.modal__confirm--outer').remove();
      })
    }
    
    function createCookie(name, value, days) {
      var expires;
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toGMTString();
      }
      else {
          expires = "";
      }
      document.cookie = name + "=" + value + expires + "; path=/";
    }
    
    function getCookie(c_name) {
      if (document.cookie.length > 0) {
          c_start = document.cookie.indexOf(c_name + "=");
          if (c_start != -1) {
              c_start = c_start + c_name.length + 1;
              c_end = document.cookie.indexOf(";", c_start);
              if (c_end == -1) {
                  c_end = document.cookie.length;
              }
              return unescape(document.cookie.substring(c_start, c_end));
          }
      }
      return "";
    }
}