<?php
  include_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/g365-data-manager/js/form-templates/spp_acct_api_form.php';
  if( !empty(get_user_meta( $arg['user_data'], '_user_owns_g365', true )) ){
    echo bind_to_template($replacements, $spp_acct_slb_form, ['bind_acct_data'=>get_acct_features_templates('acct-data', ['user_data'=>$arg['user_data']])], 'acct-api-forms');
  }else{
    echo bind_to_template($replacements, $spp_acct_missing_owned_club_data_btn, [], '');
  }