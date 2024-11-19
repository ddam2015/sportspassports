<?php
  include_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/g365-data-manager/js/form-templates/spp_api_slb_form.php';
  $post_event_id = $_POST['post_event_id']; $post_stat_type = $_POST['stat_type']; $post_api_keys = $_POST['api_keys']; $post_secret_keys = $_POST['secret_keys'];
  $get_filter_label = leaderboard_tb_form($post_stat_type, '', 'api-fields');
  if(isset($post_stat_type)){
    echo bind_to_template($replacements, $player_table, ['stat_type'=>$get_filter_label['label'], 'stat_abbr'=>$get_filter_label['alias'], 'event_id'=>$post_event_id], 'api-forms');
  }else{
    if(secured_data('secured-api-keys', ['keys'=>url_param('api_keys')])['decrypted'] === '1'){
      echo bind_to_template($replacements, $slb_by_org_forms, ['admin_keys'=>admin_api_console('slb-by-org', ['api_keys'=>url_param('api_keys'), 'secret_keys'=>url_param('secret_keys'),  'request_data'=>url_param('request_data'), 'request_url'=>url_param('request_url')])], 'api-forms');
    }else{
      echo bind_to_template($replacements, $slb_forms, ['admin_keys'=>admin_api_console('features-api', ['api_keys'=>url_param('api_keys'), 'secret_keys'=>url_param('secret_keys'), 'request_url'=>url_param('request_url')])], 'api-forms');
    }
  }
?>