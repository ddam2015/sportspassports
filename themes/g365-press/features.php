<?php
/**
 * Template Name: Features APIs
 * Description: Central Data Hub for SPP Features APIs
 * Version: 1.0
 * Author: Daradona Dam
 */

  $admin_keys = url_param('admin_keys');
  $filter_options = url_param('filter_options');
  $get_array_keys = secured_data('secured-api-keys', ['keys'=>$admin_keys])['decrypted'];
  parse_str($get_array_keys, $array_keys_output);
  $api_keys = secured_data('secured-api-keys', ['keys'=>$array_keys_output['api_keys']])['decrypted'];
  $user_own_data = get_user_meta($api_keys, '_user_owns_g365', true);
  $api_keys = $user_own_data['og_ed'];
  $api_key_list = array();
  foreach($api_keys as $api_key){ 
    $api_key_list['encrypted'][] = secured_data('secured-api-keys', ['keys'=>$api_key])['encrypted'];
    $api_key_list['decrypted'][] = $api_key;
  }
  $check_api_key_str = implode(',', $api_key_list['decrypted']);
  $org_user_id = secured_data('decrypt', ['keys'=>$array_keys_output['secret_keys']]);
  $org_id = get_user_meta($org_user_id, '_user_owns_g365', true);
  $org_id = $org_id['og_ed'];
  $org_id = implode(',', $org_id);
  $event_id = $array_keys_output['event_id'];
  $stat_type = $array_keys_output['stat_type'];
  $dv_type = $array_keys_output['dv_type'];
  $lv_type = $array_keys_output['lv_type'];
  $special_cond = $array_keys_output['special_cond'];
  if( empty($array_keys_output['request_url']) ){
    $get_scheme = parse_url( url_param('request_url') )['scheme'];
    $get_host = parse_url( url_param('request_url') )['host'];
    $request_url = $get_scheme . '://' . $get_host; 
  }else{
    $get_scheme = parse_url( $array_keys_output['request_url'] )['scheme'];
    $get_host = parse_url( $array_keys_output['request_url'] )['host'];
    $request_url = $get_scheme . '://' . $get_host; 
  }
  // If this is a request base on filter existing options
  if(!empty($filter_options)){
    str_replace(array('%3D', '%7C', '+'), array('=', '&', ' '), $filter_options);
    parse_str($filter_options, $array_filter_options);
    $event_id = $array_filter_options['get_ev'];
    $stat_type = $array_filter_options['get_st_cat'];
    $lv_type = $array_filter_options['roster_lv'];
    $dv_type = $array_filter_options['roster_dv'];
    $select_year = $array_filter_options['select_year'];
    $request_url = 'https://'.$array_filter_options['request_url'];
  }
  // Verify API request before serve them records
  if( !empty($api_key_list) && ( count(array_intersect(get_encrypted_api_keys()['encrypted'], $api_key_list['encrypted'])) == count($api_key_list['encrypted']) ) && ($org_id === $check_api_key_str) ){
  header('Access-Control-Allow-Origin: '.$request_url.' ');
  header('Access-Control-Allow-Methods: GET');
    $sbl_data = features_api('stat-leaderboard', ['org_id'=>$org_id, 'api_keys'=>$api_keys, 'stat_type'=>$stat_type, 'event_id'=>$event_id, 'dv_type'=>$dv_type, 'lv_type'=>$lv_type, 'select_year'=>$select_year,  'special_cond'=>$special_cond]);
  //   $result['status'][] = 'success';
    http_response_code(200);
    echo json_encode($sbl_data);
  }else{
    header("HTTP/1.0 401 Unauthorized");
    echo 'Access Denied ' . http_response_code(401); exit;
  }
?>