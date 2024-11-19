
var g365_cookies = Cookies.withConverter({
  write: function(value) {
    // Encode all characters according to the "encodeURIComponent" spec
    return encodeURIComponent(value)
      // Revert the characters that are unnecessarly encoded but are
      // allowed in a cookie value, except for the plus sign (%2B)
      .replace(/%(23|24|26|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
  },
  read: function(value) {
    return value
      // Decode the plus sign to spaces first, otherwise "legit" encoded pluses
      // will be replaced incorrectly
      .replace(/\+/g, ' ')
      // Decode all characters according to the "encodeURIComponent" spec
      .replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
  }
});
function g365_session_init() {
  window.g365_sess_data = g365_cookies.getJSON().g365_SID;
  if(
    typeof window.g365_sess_data !== 'object' ||
    typeof window.g365_sess_data.id !== 'string' || window.g365_sess_data.id === '' ||
    typeof window.g365_sess_data.token !== 'string' || window.g365_sess_data.token === '' ||
    typeof window.g365_sess_data.time !== 'number' || window.g365_sess_data.time === 0
  ) {
    $.ajax({
      type: "post",
      url: g365_script_domain + 'session-gen/',
      headers: {'X-Requested-With': 'XMLHttpRequest', 'uni_tag': g365_script_domain},
      dataType: "json",
      cache: false,
      success: function (response) {
        if (response.status === 'success') {
          g365_cookies.set( 'g365_SID', response.result, {domain: g365_script_domain.slice(8,-1), expires: 14, secure: true, sameSite: 'none' } );
          window.g365_sess_data = response.result;
          if (window.g365_func_wrapper.sess.length > 0) window.g365_func_wrapper.sess.forEach( function(func){ func.name.apply(null, func.args); });
        } else {
          // There is an error
          console.log('ResObj Error: ',response.result);
        }
      },
      error: function (response) {
        console.log('Ajax error.', response, response.responseText);
        if( response.responseText.indexOf('<b>Fatal error</b>') !== -1  ) {
          var error_count = g365_cookies.get('g365_ajax_error_auto_retry');
          if( error_count === 'undefined' ) {
            g365_cookies.set('g365_ajax_error_auto_retry', 'true', { expires: 0.0001, path: '' });
            location.reload();
          }
        }
      }
    });
  } else {
    if (window.g365_func_wrapper.sess.length > 0) window.g365_func_wrapper.sess.forEach( function(func){ func.name.apply(null, func.args); });
    window.g365_func_wrapper.sess_init = 1;
  }
}
if( typeof g365_script_domain !== 'undefined' ) g365_session_init();