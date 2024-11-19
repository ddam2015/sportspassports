<div id="profile-video" class="cell small-12 medium-6">
  <?php $profile_videos = g365_img_queries('profile-video', ['pl_id'=>$player_id]);
    if(!empty($profile_videos)){ $youtube_video_is_active = "is-active"; }
    $youtube_videos = $arg['youtube_videos'];
    $youtube_video = $arg['youtube_video'];
    $stat_data = $arg['stat_data'];
    //start with the main profile, add the mixtapes and then see if we have anything to display
    $youtube_videos = array();
    if( !empty( $youtube_video ) ) $youtube_videos[] = $youtube_video;
    foreach( $stat_data as $key => $val ){
      if( !empty( $val->video ) ) {
        $youtube_videos[] = $val->video;
      }
    }
    if( empty($youtube_videos) && !empty($profile_videos) ) : ?>
      <div class="grid-x grid-margin-x">
        <?php $profile_videos = g365_img_queries('profile-video', ['pl_id'=>$player_id]); ?>
          <div class="cell small-12">
            <div class="responsive-embed widescreen">
            <?php foreach($profile_videos as $profile_video){ echo $profile_video['video']; } ?>
            </div>
          </div>
          <?php
            foreach($profile_videos as $profile_video){
              $default_prof_vid_data = g365_get_media_thumbnail('video-thumbnail', ['file_id'=>'default-profile-video-'.$profile_video['file_id'].'', 'default_video_id'=>$profile_video['file_id'], 'video_name'=>$profile_video['video_name'], 'video_src'=>$profile_video['video_src']]);
              echo $default_prof_vid_data['thumbnail']; 
            }
          ?>
      </div>
    <?php else: ?>
    <div class="grid-x grid-margin-x">
      <?php
        $profile_videos = g365_img_queries('profile-video', ['pl_id'=>$player_id]);
        $vid_count = (count($youtube_videos) > 1) ? true : false;
        foreach($youtube_videos as $dex => $vid) :
          if($dex === 0) : ?>
            <div class="cell small-12">
              <div class="responsive-embed widescreen">
                <iframe id="profile-player" width="560" height="315" src="https://www.youtube.com/embed/<?php echo $vid; ?>?rel=0" frameborder="0" allowfullscreen style="z-index:1"></iframe>
              <?php foreach($profile_videos as $profile_video){ echo $profile_video['video']; } ?>
              </div>
            </div>
            <?php
              foreach($profile_videos as $profile_video){
                $default_prof_vid_data = g365_get_media_thumbnail('video-thumbnail', ['file_id'=>'default-profile-video-'.$profile_video['file_id'].'', 'default_video_id'=>$profile_video['file_id'], 'video_name'=>$profile_video['video_name'], 'video_src'=>$profile_video['video_src']]);
                echo $default_prof_vid_data['thumbnail']; 
              }
          endif;
          if( $vid_count ) : ?>
        <div onclick="youtube_video()" class="video-thumb cell small-6 medium-4 large-3" data-direction="https://www.youtube.com/embed/<?php echo $vid; ?>?rel=0">
          <img src="http://img.youtube.com/vi/<?php echo $vid; ?>/0.jpg" />
        </div>
      <?php endif; endforeach; ?>
    </div>
  <?php endif;  ?>
</div>
<div class="grid-x large-12 block text-right upload-video-btn" style="width:100%;">
  <a href="<?php echo get_site_url(); ?>/account/player-videos/"><button class="fi-upload large-12 tiny-padding small-margin-bottom report_btn" style="width:100px;"> Upload Video</button></a>
</div>
<script>
  function youtube_video(){ $('.responsive-embed video').remove(); }
  function upload_video(pointer){ 
    var video_id = pointer.dataset.videoId;
    var video_name = pointer.dataset.videoName;
    var video_src = pointer.dataset.videoSrc;
    $('.responsive-embed video').remove();
    $('.responsive-embed').append('<video controls="controls" height="200" width="300" name="'+video_name+'" id="'+video_id+'" style="z-index:2"><source src="'+video_src+'"></video>');
  }
	<?php 
  //add the js to power the stat jumping and video thumbs
	if( !empty($wp_query->query_vars['pl_tp']) ) : $targ = preg_replace('/\s+|\.|-/', '', $wp_query->query_vars['pl_tp']); ?>
		function g365_nav_click( targ ) { $('#click' + targ).click(); }
		if( typeof window.g365_func_wrapper !== 'object' ) window.g365_func_wrapper={sess:[],found:[],end:[]};
		window.g365_func_wrapper.found[window.g365_func_wrapper.found.length] = {name : g365_nav_click, args : ['<?php echo $targ; ?>']};
	<?php endif; ?>
	<?php if( $vid_count ) : ?>
		function g365_vid_switch() { $('.video-thumb', '#profile-video').click(function(){ $('#profile-player').attr('src', $(this).attr('data-direction')); }); }
		if( typeof window.g365_func_wrapper !== 'object' ) var g365_func_wrapper={sess:[],found:[],end:[]};
		window.g365_func_wrapper.found[window.g365_func_wrapper.found.length] = {name : g365_vid_switch, args : []};
	<?php endif; ?>
</script>