<?php $pl_ev_tm_data = spp_statistics('homepage-statistics'); ?>
<div class="ticker-container medium-margin-bottom">
  <div class="ticker">
     <p class="ticker__number"><?php echo number_format($pl_ev_tm_data['total_pl_profile']); ?></p>
     <small>Total Player<span class="block">Profiles Created</span></small>
  </div>
  <div class="ticker">
      <p class="ticker__number"><?php echo number_format($pl_ev_tm_data['total_events']); ?></p>
      <small>Events<span class="block">Covered</span></small>
   </div>
   <div class="ticker">
      <p class="ticker__number"><?php echo number_format($pl_ev_tm_data['total_tm_profile']); ?></p>
      <small>Total Team<span class="block">Profiles Created</span></small>
   </div>
</div>