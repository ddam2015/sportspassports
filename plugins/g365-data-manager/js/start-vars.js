// get reference to make the form init request
var g365_script_element, g365_script_anchor, g365_script_url, g365_script_domain, g365_script_ajax, g365_script_ajax_request;
//function that sets the form init variables

//get the form insert element
g365_script_element = document.getElementById('g365_form_script');
//if there is a form element support it
if( typeof g365_script_element == 'undefined' || g365_script_element == null ) {
  //set global flag to false
  g365_script_anchor = false;
} else {
  //set global flag to true
  g365_script_url = (g365_script_element.hasAttribute('src')) ? g365_script_element.src : g365_script_element.getAttribute('data-g365_url');
  if( g365_script_url === '' || g365_script_url === null ) {
    g365_script_anchor = false;
  } else {
    g365_script_domain = g365_script_url.slice(0, (g365_script_url.indexOf('.com/') + 5));
    g365_script_ajax = g365_script_domain + 'data-process/';
    g365_script_ajax_request = g365_script_domain + 'data-request/';
    g365_script_anchor = true;
  } 
}

//g365 session array
window.g365_sess_data = [];
//if the init object isn't declared, add it
if( typeof window.g365_func_wrapper !== 'object' ) window.g365_func_wrapper={sess:[],found:[],end:[]};
