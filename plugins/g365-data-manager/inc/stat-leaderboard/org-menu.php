<?php
  global $wp_query;
  $get_org_lists =  slb_org_menu('slb-org-data', ['org_string'=>$wp_query->query_vars['subpg_type']], "stlb");
?>
  <h3>Select an Organization</h3>
<div class="container grid-x grid-margin-x small-up-2 medium-up-3 large-up-4 text-center profile-feature medium-margin-top mobile-horizontal-nav-outer" id="organization-list">
  <div class="grid-x grid-margin-x small-up-3 medium-up-4 large-up-4 align-center text-center img-grid small-padding-sides" id="statMobileNav">
  <?php foreach($get_org_lists['org_list'] as $org_list): 
    if(empty($org_list->profile_img)){ $org_logo = 'g365_profile_placeholder-spp-logo.gif'; }else{ $org_logo = $org_list->profile_img; }
    if($wp_query->query_vars['subpg_type'] === $org_list->nickname){ $is_selected = 'is-selected'; }else{ $is_selected = ''; }
  ?>
    <div class="cell relative small-margin-bottom stat-organization <?php echo $is_selected; ?> ">
      <a href="<?php echo get_site_url() ."/stat-leaderboard/organization-list/". $org_list->nickname; ?>#organization-list" >
        <img class="" loading="lazy" src="/wp-content/uploads/org-logos/<?php echo $org_logo; ?>" alt="<?php echo $org_list->nickname; ?>"><br><?php echo $org_list->name; ?></a>
    </div>                                
  <?php endforeach; ?>
  </div>
</div>
<?php if(!empty($wp_query->query_vars['subpg_type'])){ echo $get_org_lists['by_org']; } ?>