<?php $ev_acts = g365_get_event(['authorized_user'=>get_current_user_id()], 'acts'); $ev_acts = json_decode(json_encode($ev_acts), true); ?>
<div class="grid-x">
  <div class="small-12 medium-12 large-12 small-padding-right">
    <div class="h_ev_box small-12 medium-12 large-12 medium-padding">
      <h3>Events</h3>
      <p>Acts that you have purchased accessed to will be shown in full color below.<br/> Each unlocked Act will give you access to that event's team rosters, stat leaderboard, and team standings. You will also be able to add players to your Recruits List.</p>
      <div class="grid-x small-up-2 medium-up-4 large-up-4 text-center">
        <?php foreach($ev_acts as $ev_act): echo dcp_tb(['lock_status'=>$ev_act['unlocked'], 'ev_nickname'=>$ev_act['nickname'], 'ev_link'=>$ev_act['link'], 'img_logo'=>(empty($ev_act['img_logo']) ? "" : $ev_act['img_logo']), 'ev_name'=>$ev_act['name'], 'ev_date'=>$ev_act['dates'], 'logo_img'=>$ev_act['logo_img'], 'ev_id'=>$ev_act['id'], 'ev_type'=>$ev_act['org']]); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>