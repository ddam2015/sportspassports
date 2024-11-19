<?php
/**
 * Template Name: All Tournament Awards
 */

get_header();
$g365_ad_info = g365_start_ads( $post->ID );
$award_id = $wp_query->query_vars['aw_id'];
// $awards_tm = $wp_query->query_vars['aw_tm'];

$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
//we need all group options to create the navigation
$award_options = g365_get_groups_data( 89, 3 , array('truncate'=>true) );
//if the group id doesn't come in on the url set it to be global
if( empty($award_id) ) $award_id = ( !empty($award_options) ? $award_options->records[0]->id : null);
//get all the awards
$award_data = ( !empty($award_id) ) ? g365_build_awards( $award_id, array('truncate'=>true) ) : null;
//general urls for the page build, one version for page switching, one referencing this page
$awards_url = site_url() . '/tournament-awards/'; 
$awards_url_event = $awards_url . $award_id;
$award_data_divisions = g365_build_awards(89);
$key_level = (g365_return_keys('g365_all_tournament_grade_key'));
// $allTournamentbg = get_site_url() . '/wp-content/uploads/2021/09/all-tournament-header-1.jpg';
$allTournamentbg = 'https://grassroots365.com/wp-content/uploads/2021/09/all-tournament-header-1.jpg';
// echo '<pre class="">';
// print_r($award_options);
// print_r($award_data->awards);
// print_r($award_data->item_ids[0]);
// foreach($award_data->awards as $test){
//     echo $test->award_class;
// }
// echo '</pre>';	
// print_r($award_options);
?>

<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12" id="allTournamentBody">
        
		<?php 
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

        endif;
        
    //build switcher
    if( !empty($award_options) ) { ?>
    <div class="allTournament__wrap large-margin-bottom">
            <img src="<?php echo $allTournamentbg ?>" class="allTournament__img" alt="allTournament image">
            <div class="allTournament__info">
                <h1 class="allTournament__heading">All-Tournament Awards</h1>
                <p class="allTournament__text large-margin-top">Here are the award winners from our <a class="block" href="https://grassroots365.com/calendar/">Grassroots 365 Tournaments</a></p>
            </div>
    </div>
    <h1 class="cell small-12 text-center">All-Tournament Awards</h1>
    <div class="cell small-12 large-6 tiny-padding-top tiny-margin-bottom text-right">
      <div class="input-group">
        <span class="input-group-label normal-font-size" id="allTournLabel">Change Event:</span>
        <select id="view_switch" class="no-margin-bottom input-group-field">
          <option value="">-- Please select an event from the dropdown --</option>
        <?php 
        //build event dropdown
 			  foreach( $award_options->records as $dex => $group_data ) {
           echo '<option value="' . $awards_url . $group_data->id . '"' . (($group_data->id == $award_id) ? ' selected="selected"' : '') . '>' . $group_data->name . '</option>';
        } ?>
        </select>
      </div>
    </div>
    <?php }
    // if( empty($award_data->name) ) {
    // the_title( '<h1 class="entry-title cell small-12">', '</h1>' );
    // } else {
    // }
    if( $award_data !== null && !empty($award_data) ) { ?>
    <div clas="grid-x tabs-panel small-padding">
			<?php foreach( $award_data->records as $dex => $group_data ) {
				$group_data->handle = strtolower(preg_replace('/\s+|\.|-/', '', $group_data->name));
			?>
				<div class="cell small-12 tournamentAwards">
					<h2 class="hide"><?php echo $group_data->name; ?></h2>
					<div class="all-tournament__details">
						<img class="width-50-200" src="<?php echo $group_data->logo_img; ?>" alt="Official Logo of <?php echo $group_data->name; ?>">
<!--                         <?php 
                            if( !empty($award_data->name) ) {
                            the_title( '<h2 class="all-tournament__heading">' . (($award_data->abbr) ? $award_data->abbr : $award_data->name) . '<br><small class="hide">', '</small></h2>' );
                            } else {
                            the_title( '<h2 class="cell small-12">', '</h2>' );
                            }
                        ?> -->
                    </div>
					<div>
						<nav class="tabs separate grid-x small-up-2 medium-up-3 large-up-7 align-center text-center collapse" id="<?php echo $group_data->handle; ?>_awards" data-tabs>
							<?php foreach( $award_data->records as $ev_dex => $event_data ) {
								$increment_title = date('Y',strtotime($event_data->eventtime));
							?>
							<div class="tabs-title<?php echo ( $ev_dex == 0 ) ? ' is-active' : ''; ?>"><a href="#<?php echo $group_data->handle; ?>_awards_<?php echo $increment_title; ?>"><?php echo $increment_title; ?></a></div>
							<?php } ?>
						</nav>
            
            <center><h4>
              <br>
              <?php
//                   $replacement = $group_data->dates;
// //                   $dates = preg_replace("/[ ,2021 ]/", " ", $replacement);
//                   $dates = preg_replace("/[|]/", " - ", $replacement);
                  echo(g365_build_dates($group_data->dates, 2, false));
                  
              ?>
            </h4></center>
            
						<div class=" award-data tabs-content table-data" data-tabs-content="<?php echo $group_data->handle; ?>_awards">
						<?php foreach( $award_data->records as $ev_dex => $event_data ) { ?>
							<div class="tabs-panel<?php echo ( $ev_dex == 0 ) ? ' is-active' : ''; ?>" id="<?php echo $group_data->handle; ?>_awards_<?php echo date('Y',strtotime($event_data->eventtime)); ?>">
<!-- 								 <table class="pTable text-center no-margin-bottom scroll-for-small .tournamentAwards"> -->
                
                   
                      <?php
                    $last_division = "";
										$col_count = 5;
                    $current_award_title = '';
                    $current_dex = 0;
//                    $col_classes = array_keys( current($award_data->awards->{$event_data->id}) );
//                     echo '<div style="overflow-x: scroll">';
                                                                             
                                                                             
                                                                             
                                                                             
                                                                             
                    $datecheck = '2023-01-01 00:00:00';
                    $eventdate = $award_data->records[0]->eventtime;
                    //new stage events
                    if($award_data->records[0]->org == 3 && $datecheck < $eventdate){
                      
                      
                      
                      foreach( $award_data->awards as $award_dex => $award_vals) {
//                         echo ' stoppoint ' . $award_vals->award_class . ' HERE ' . $last_division;
                        
                        if($award_vals->award_label == 'All Act 1 Team' || $award_vals->award_label == 'Second Team All 3SGB' || $award_vals->award_label == 'First Team All 3SGB' ){
                          if($award_vals->award_class != $last_division){
                          $last_division = $award_vals->award_class;
//                           echo '"' . $last_division . '"';
                          
                            if( $current_award_title != $award_vals->award_class ) {
                              $current_award_title = $award_vals->award_class;
                              $current_dex = 0;
                              $award_division = str_replace(array('U', 'JV Girls', '12/13', '9 10'), array('', '46', '61', '62'), $current_award_title);
                              $proper_syntax = str_replace(array(' /', 'and' ),array(' - ', '& '), $key_level[$award_division]);
                              ?>
                            <tr class="division-title">
                              <th colspan="<?php echo $col_count; ?>">
                                <h3><?php echo $proper_syntax ?></h3>
                              </th>
                            </tr>
                            <?php } ?>
                            <?php
                            
                            
                            echo '<div class="award-data__div"><table class="pTable text-center" >';

                            $counter = 0;
                            $current_dex++;
                          
                          
                            foreach($award_data->awards as $award_dex_loop => $award_vals_loop ){
  //                             echo ' test ' . $current_award_title . ' = ' . $award_vals_loop->award_class;
                              if($award_vals_loop->award_class == $current_award_title ){
//                               echo 'award_vals_loop->award_class=' . ' ' . $award_vals_loop->award_class . ' current_award_title= ' . $current_award_title;
//                               echo ' secondstoppoint ';
                                
                                echo '<td class="vertical-align award-data__td">';
                                echo '<a class="award-data__a" href="' . get_site_url() . '/player/' . $award_vals_loop->player_url . '">';

                                if( $award_vals_loop->award_type >= 134 || $award_vals_loop->award_type <= 136) {
                                  $validate_player_img = spp_player_img_dir($award_vals_loop->player_url, $award_vals_loop->event_nickname, $award_vals_loop->player_id);
                                  $player_img = ( empty($award_vals_loop->profile_img) ) ? $validate_player_img : $award_vals_loop->profile_img;
                                  if($award_vals_loop->award_label == "All-Tournament MVP"){
                                    echo('<p class="player_award_label_mvp" >' . $award_vals_loop->award_label . '</p>');
                                  }else{
                                  echo('<p class="player_award_label" >' . $award_vals_loop->award_label . '</p>');
                                  }
                                      echo '<img src="' . $player_img . '" alt="' . $award_vals_loop->player_name . ' at ' . $group_data->name . '"  class="allTournament__player-img small-margin-top small-margin-bottom" ><br>';
                                }
                                
                                

                                echo '<div class="award-data__info"><span class="emphasis">'. $award_vals_loop->player_name . '</span> '. '</div></a>';

                                echo '</td>';

                                 $counter++;
    //                             echo($counter);
                                if($counter === 6){
                                  echo '<tr><td><br></td></tr>';
                                }

                              }else{
                                continue;
                              }

                            }
                            echo '</table></div>';
                          
                          }
                        }
                    }
                      
                    
                    }else{
                      
                      
                       foreach( $award_data->awards as $award_dex => $award_vals) {
//                    if( $award_vals->event_id != $event_data->id ) return;
                      
                      if($award_vals->award_label == 'All-Tournament MVP' && $award_vals->award_class != $last_division){
                        $last_division = $award_vals->award_class;
                      if( $current_award_title != $award_vals->award_class ) {
                        $current_award_title = $award_vals->award_class;
                        $current_dex = 0;
                        $award_division = str_replace(array('U', 'JV Girls', '12/13', '9 10'), array('', '46', '61', '62'), $current_award_title);
                        $proper_syntax = str_replace(array(' /', 'and' ),array(' - ', '& '), $key_level[$award_division]);
                        ?>
                      <tr class="division-title">
                        <th colspan="<?php echo $col_count; ?>">
                          <h3><?php echo $proper_syntax ?></h3>
                        </th>
                      </tr>
                      <?php } ?>
                      <?php
//                         echo $current_award_title;
                        echo '<div class="award-data__div"><table class="pTable text-center" >';
//                         if( $award_dex === 0 ) echo '<tr>';
//                         if($award_dex !== 0 ) echo '</tr><tr class="go">';
                        $counter = 0;
                        $current_dex++;
//                         echo($award_dex);
                        foreach($award_data->awards as $award_dex_loop => $award_vals_loop ){
//                         echo '<div style="overflow-x:auto">';
                          
                          if($award_vals_loop->award_class == $current_award_title){
                            if($award_vals_loop->award_label == "All-Tournament MVP"){
                              echo '<tr align="center" ><td class="vertical-align award-data__td gold-border reflection">';
                            }else{
                              echo '<td class="vertical-align award-data__td">';
                            }
                              echo '<a class="award-data__a" href="' . get_site_url() . '/player/' . $award_vals_loop->player_url . '">';
                            if( $award_vals_loop->award_type >= 11 || $award_vals_loop->award_type <= 50) {
                                $validate_player_img = spp_player_img_dir($award_vals_loop->player_url, $award_vals_loop->event_nickname, $award_vals_loop->player_id);
                                $player_img = ( empty($award_vals_loop->profile_img) ) ? $validate_player_img : $award_vals_loop->profile_img;
                              if($award_vals_loop->award_label == "All-Tournament MVP"){
                                echo('<p class="player_award_label_mvp" >' . $award_vals_loop->award_label . '</p>');
                              }else{
                              echo('<p class="player_award_label" >' . $award_vals_loop->award_label . '</p>');
                              }
                                  echo '<img src="' . $player_img . '" alt="' . $award_vals_loop->player_name . ' at ' . $group_data->name . '"  class="allTournament__player-img small-margin-top small-margin-bottom" ><br>';
                            }
                            echo '<div class="award-data__info"><span class="emphasis">'. $award_vals_loop->player_name . '</span> '. '</div></a>';

                            echo '</td>';
                            
                             $counter++;
//                             echo($counter);
                            if($counter === 6){
                              echo '<tr><td><br></td></tr>';
                            }
                          }else{
                            continue;
                          }
                        }
                       echo '</table></div>';
                      }
                    }
                      
                    }                                                       
                                                                             
                   
                
                ?>
                  </div>
                      
                    
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
      <?php } ?>
		</div>
    <?php } else { ?>
		<div class="callout small-padding">
      <!-- <p>No event data selected</p> -->
      <p>Welcome to the Grassroots 365 All Tournament Awards page! Select an event from the dropdown above to view tournament awards.</p>
    </div>
    <?php } ?>
	</div>
</section>

<?php get_footer(); ?>

