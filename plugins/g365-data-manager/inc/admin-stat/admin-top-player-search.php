<?php 
  $event_id = filter_input( INPUT_GET, 'event_id', FILTER_SANITIZE_NUMBER_INT );
  $pl_id = filter_input( INPUT_GET, 'pl_id', FILTER_SANITIZE_NUMBER_INT );
  $sortable_icon = get_site_url()."/wp-content/uploads/2021/04/sortable_icon.png";
  $ev_avg = "hide";
  if(isset($_POST['stat_type'])){
    $post_stat_type = $_POST['stat_type'];  
  }
  if(isset($_POST['stat'])){
    $post_stat = $_POST['stat'];
  }
  if(isset($_POST['stat_year'])){
    $post_stat_year = $_POST['stat_year'];
  }
  if(isset($_POST['stat_month'])){
    $post_stat_month = $_POST['stat_month'];
  }
  if(isset($_POST['stat_day'])){
    $post_stat_day = $_POST['stat_day'];
  }
  if(isset($_POST['end_st_year'])){
    $post_end_year = $_POST['end_st_year'];
  }
  if(isset($_POST['end_st_month'])){
    $post_end_month = $_POST['end_st_month'];
  }
  if(isset($_POST['end_st_day'])){
    $post_end_day = $_POST['end_st_day'];
  }
  if(isset($_POST['roster_level'])){
    $select_level = $_POST['roster_level'];
  }
  if(isset($_POST['stat_catagory'])){
    $post_stat_catagory = $_POST['stat_catagory'];
  }
  if(isset($_POST['year'])){
    $select_year = $_POST['year'];
  }
  if(isset($_POST['brand'])){
    $post_brand = $_POST['brand'];
  }
  $key_level = (g365_return_keys('g365_grade_key'));
  $available_stat_years = cp_date_selector($pl_id, 'pl_stat_year');
  $available_stat_years = json_decode(json_encode($available_stat_years), true);
  $avai_stat_y = array();
  foreach($available_stat_years as $index=>$avai_stat_year){
    $avai_stat_y[$index] = $avai_stat_year['event_date'];
  }
//   print_r($avai_stat_y[0]);
  if(!isset($select_year)){
    $select_year = $avai_stat_y[0];
  }
  $previous_stat_year = $post_stat_year - 1;
  $current_year = date('Y');
  if(in_array($event_id, explode(',',event_exception_list()))){
//   if($event_id == event_exception_list()){
    $limit_result = 550;
  }else{
    $limit_result = 50; // Set up the number of top players' stats
  }
  $stat_type_list = array();
  $set_stat_types = array('Total', 'Average');
  foreach($set_stat_types as $index => $set_stat_type){
    $stat_type_val = $index+1;
    if(!isset($post_stat_type)){
      $is_selected_type = "";
      $stat_type_list[] = '<option '.$is_selected_type.' value='.$stat_type_val.'>'.$set_stat_type.'</option>';
    }
    if(isset($post_stat_type)){
      if($post_stat_type == $stat_type_val){
        $is_selected_type = "selected=selected";
      }else{
        $is_selected_type = "";
      }
      $stat_type_list[] = '<option '.$is_selected_type.' value='.$stat_type_val.'>'.$set_stat_type.'</option>';
    }
  }
  $stat_type_list = implode('',$stat_type_list);
  $level_list = array();
  for($i = 8; $i <= 47; $i++){
    if(($i > 7 && $i < 18) || ($i > 39 && $i < 48 )){
      if(!isset($select_level)){
        $is_selected_level = "";
        $level_list[] = '<option '.$is_selected_level.' value='.$i.'>'.$key_level[$i].'</option>';
      }
      if(isset($select_level)){
        if($select_level == $i){
          $is_selected_level = "selected=selected";
        }else{
          $is_selected_level = "";
        }
        $level_list[] = '<option '.$is_selected_level.' value='.$i.'>'.$key_level[$i].'</option>';
      }
    }
  }
  $level_list = implode('',$level_list);
  $stat_lists = g365_stat_list();
  $stat_brand_lists = custom_web_brands('all-brands');
  $stat_catagories = array(); $stat_brands = array();
  foreach($stat_lists as $index => $stat_list){
    $stat_type = $stat_lists[$index]['type']; 
    $stat_alias = $stat_lists[$index]['alias'];
    if(!isset($post_stat)){
      $is_selected_stat = "";
      $stat_catagories[] = '<option '.$is_selected_stat.' value='.$stat_alias.'>'.$stat_type."s".'</option>';
    }
    if(isset($post_stat)){
      if($post_stat == $stat_alias){
        $is_selected_stat = "selected=selected";
      }else{
        $is_selected_stat = "";
      }
      $stat_catagories[] = '<option '.$is_selected_stat.' value='.$stat_alias.'>'.$stat_type."s".'</option>';
    }
  }
  $stat_catagories = implode('', $stat_catagories);
  foreach($stat_brand_lists as $index => $brand_list){
    $stat_type = $index;
    $stat_alias = $brand_list;
    if(!isset($post_brand)){
      $is_selected_stat = "";
      $stat_brands[] = '<option '.$is_selected_stat.' value='.$stat_alias.'>'.$stat_type.'</option>';
    }
    if(isset($post_brand)){
      if($post_brand == $stat_alias){
        $is_selected_stat = "selected=selected";
      }else{
        $is_selected_stat = "";
      }
      $stat_brands[] = '<option '.$is_selected_stat.' value='.$stat_alias.'>'.$stat_type.'</option>';
    }
  }
  $stat_brands = implode('', $stat_brands);
  $stat_form_radio_btn = '
    <div class="grid-x">
      <form method="post" class="grid-x">
        <div class="small-12 medium-2 large-2 small-padding-right" >
          <select name="stat_type" id="stat_type" style="border-radius: 20px">
            '.$stat_type_list.'
          </select>
        </div>
        <div class="small-12 medium-2 large-2 small-padding-right" >
          <select name="roster_level" id="roster_level" style="border-radius: 20px"> 
            <option value="">All Levels</option>
            '.$level_list.'
          </select>
        </div>
        <div class="small-12 medium-2 large-2 small-padding-right" >
          <select name="stat" id="stat_catagory" style="border-radius: 20px"> 
            '.$stat_catagories.'
          </select>
        </div>
        <div class="small-12 medium-2 large-2 small-padding-right" >
          <select name="brand" id="stat_brand" style="border-radius: 20px"> 
            '.$stat_brands.'
          </select>
        </div>
        <input type="submit" value="Filter Options" class="slb_btn small-12 medium-12 large-12" />
      </form>
    </div>
  ';
  $stat_form_by_year = '
   <div class="grid-x grid-margin-x">
    <div class="cell small-12 medium-7 large-7">
      <form method = "post">
        <div class="grid-x small-margin-bottom text-center">
          <div class="small-12 medium-3 large-3 small-padding-right">
            Start year<input type="text" name="stat_year" placeholder="2010, 2011 ... etc " />
          </div>
          <div class="small-12 medium-3 large-3 small-padding-right">
            Start month<input type="text" name="stat_month" placeholder="01, 02 .. etc" />
          </div>
          <div class="small-12 medium-3 large-3 small-padding-right">
            Start day<input type="text" name="stat_day" placeholder="01, 02 .. etc" />
          </div>
          <div class="small-12 medium-3 large-3 small-padding-right">
            End year<input type="text" name="end_st_year" placeholder="2012, 2013 ... etc" />
          </div>
          <div class="small-12 medium-3 large-3 small-padding-right">
            End month<input type="text" name="end_st_month" placeholder="03, 04 .. etc" />
          </div>
          <div class="small-12 medium-3 large-3 small-padding-right">
            End day<input type="text" name="end_st_day" placeholder="03, 04 .. etc" />
          </div>
        </div>
      '.$stat_form_radio_btn.'
      </form>
    </div>
   </div>
  ';
  $table_head = '
    <table class="edit-table stat_table">
      <thead>
        <tr>
          <th>Player Name<img src="'.$sortable_icon.'"></th>
          <th>Team Name<img src="'.$sortable_icon.'"></th>
          <th>Point<img src="'.$sortable_icon.'"></th>
          <th>Rebound<img src="'.$sortable_icon.'"></th>
          <th>Assist<img src="'.$sortable_icon.'"></th>
          <th>Steal<img src="'.$sortable_icon.'"></th>
          <th>3-Pointers Made<img src="'.$sortable_icon.'"></th>
          <th>Block<img src="'.$sortable_icon.'"></th>
        </tr>
      </thead>
    <tbody>
  ';
  $indi_tb = '
    <table class="edit-table stat_table">
      <thead>
        <tr>
          <th>Event Name<img src="'.$sortable_icon.'"></th>
          <th>Level<img src="'.$sortable_icon.'"></th>
          <th>Division<img src="'.$sortable_icon.'"></th>
          <th>Team Name<img src="'.$sortable_icon.'"></th>
          <th>Game Date<img src="'.$sortable_icon.'"></th>
          <th>Point<img src="'.$sortable_icon.'"></th>
          <th>Rebound<img src="'.$sortable_icon.'"></th>
          <th>Assist<img src="'.$sortable_icon.'"></th>
          <th>3-Pointers Made<img src="'.$sortable_icon.'"></th>
          <th>Steal<img src="'.$sortable_icon.'"></th>
          <th>Block<img src="'.$sortable_icon.'"></th>
        </tr>
      </thead>
      <tbody>
  ';
  if( empty($event_id) && empty($post_stat_year) ) :
?>
<div class="grid-x grid-margin-x">
  <div class="cell small-12 medium-7 large-7">
    <ul class="stat_ul accordion club-rosters" data-accordion="" data-allow-all-closed="true" role="tablist" data-n="k63lfd-n">
      <li class="accordion-item" data-accordion-item="">
        <!-- Accordion tab title -->
        <a href="#" class="stat_ul accordion-title" aria-controls="krk1nx-accordion" role="tab" id="krk1nx-accordion-label" aria-expanded="false" aria-selected="false">Search players' stats by event</a>
        <div class="extra-info grid-container">
          <div class="grid-x grid-margin-x">
          </div>
        </div>
        <div class="accordion-content" data-tab-content="" role="tabpanel" aria-labelledby="krk1nx-accordion-label" aria-hidden="true" id="krk1nx-accordion" style="display: none;">
          <input type="text" class="g365_livesearch_input ls_query" id="event_link_selector" data-g365_type="player_stat_table" placeholder="Enter Event Name" autocomplete="off" name="ls_query" maxlength="60">
        </div>
      </li>
      <li class="accordion-item" data-accordion-item="">
        <!-- Accordion tab title -->
        <a href="#" class="accordion-title" aria-controls="krk1nx-accordion" role="tab" id="krk1nx-accordion-label" aria-expanded="false" aria-selected="false">Search players' stats by year</a>
        <div class="extra-info grid-container">
          <div class="grid-x grid-margin-x">
          </div>
        </div>
        <div class="accordion-content" data-tab-content="" role="tabpanel" aria-labelledby="krk1nx-accordion-label" aria-hidden="true" id="krk1nx-accordion" style="display: none;">
          <?php echo $stat_form_by_year; ?>   
        </div>
      </li>
      <li class="accordion-item" data-accordion-item="">
        <!-- Accordion tab title -->
        <a href="#" class="stat_ul accordion-title" aria-controls="krk1nx-accordion" role="tab" id="krk1nx-accordion-label" aria-expanded="false" aria-selected="false">Individual player's stats</a>
        <div class="extra-info grid-container">
          <div class="grid-x grid-margin-x">
          </div>
        </div>
        <div class="accordion-content" data-tab-content="" role="tabpanel" aria-labelledby="krk1nx-accordion-label" aria-hidden="true" id="krk1nx-accordion" style="display: none;">
          <input type="text" class="g365_livesearch_input ls_query" data-g365_type="indi_pl_stat" placeholder="Enter Player Name" autocomplete="off" autofocus="" name="ls_query" maxlength="60">
        </div>
      </li>
    </ul>
  </div>
</div>
<?php
  endif;
  /**** Search by year ****/
  if( (empty( $event_id ) && empty($post_stat_type) && !empty($post_stat_year)) ):
  echo $stat_form_by_year;
  endif;
  if( empty($event_id) && !empty($post_stat_type) && !empty($post_stat_year) ): // Search by year
  $pl_stat_tb = g365_top_pl_stat( $event_id, $post_stat_type, $post_stat, $previous_stat_year, $post_stat_year, $limit_result, array($post_stat_year, $post_stat_month, $post_stat_day, $post_end_year, $post_end_month, $post_end_day, 'brand'=>$post_brand), 1);
  $pl_stat_tb = json_decode( json_encode($pl_stat_tb), true);
  $new_st_avg_arr = g365_stat_table_filter($pl_stat_tb, $post_stat_type, $post_stat, $limit_result, $stat_types);
  $is_ev_name = "hide";
  echo $stat_form_by_year;
  endif;
  /**** Search by event ****/
  if( (!empty($event_id) && empty($post_stat_type)) ):
  echo $stat_form_radio_btn;
  endif;
  if( !empty($event_id) && is_numeric($select_level) && !empty($post_stat_type) ): /*if-5*/
  $pl_stat_tb = g365_stat_leader($event_id, $post_stat, $select_year, '', $select_level, $type = 8); // "8" Total and selected level/stat type
  $pl_stat_tb = json_decode( json_encode($pl_stat_tb), true);
  $new_st_avg_arr = g365_stat_table_filter($pl_stat_tb, $post_stat_type, $post_stat, $limit_result, $post_stat_type);
  echo $stat_form_radio_btn;
  endif;
  if( (!empty($event_id) && !is_numeric($select_level) && !empty($post_stat_type)) ):
  $pl_stat_tb = g365_top_pl_stat($event_id, $post_stat_type, $post_stat, '', '', $limit_result, null, null); 
  $pl_stat_tb = json_decode( json_encode($pl_stat_tb), true);
  $new_st_avg_arr = g365_stat_table_filter($pl_stat_tb, $post_stat_type, $post_stat, $limit_result, $stat_types);
  echo $stat_form_radio_btn; 
  endif;
?>
  <h2 class="<?php echo $is_ev_name; ?>"><?php echo empty($pl_stat_tb[0]['event_name']) ? false : $pl_stat_tb[0]['event_name'].' ('.date('Y-m-d', strtotime($pl_stat_tb[0]['event_time'])).')'; ?></h2>
  <?php if( !empty($pl_stat_tb) && isset($post_stat_type) ): echo $table_head; foreach($new_st_avg_arr as $key => $pl_stat_tbs): $team_level = substr($pl_stat_tbs['team_name'], 0, strpos($pl_stat_tbs['team_name'], 'U ')); $team_level_full = $key_level[$team_level]; $team_level = str_replace($team_level.'U', $team_level_full, $pl_stat_tbs['team_name']); ?>
      <tr>
        <td><?php echo $pl_stat_tbs['player_name']; ?></td>
        <td><?php echo $team_level; ?></td>
        <td><?php echo $pl_stat_tbs['stat_point']; ?></td>
        <td><?php echo $pl_stat_tbs['stat_rebound']; ?></td>
        <td><?php echo $pl_stat_tbs['stat_assist']; ?></td>
        <td><?php echo $pl_stat_tbs['stat_steal']; ?></td>
        <td><?php echo (!empty($pl_stat_tbs['stat_three']) ? $pl_stat_tbs['stat_three'] : "0"); ?></td>
        <td><?php echo $pl_stat_tbs['stat_block']; ?></td>
      </tr>
      <?php endforeach; endif; ?>
     </tbody>
    </table>
   <?php if( empty($pl_stat_tb) && isset($post_stat_type) ): ?>
    <div class="cell small-12 medium-8 large-12 large-margin-top text-center">
      <h4><?php echo g365_message()['admin_pl_st']; ?></h4>
    </div>
   <?php endif; if(!empty($pl_id)): $ev_avg = ""; $player_data = g365_pl_game_stat($pl_id, $event_id, true, $select_year, 3); if(!empty($player_data)): ?>
   <div>  
    <form method="post" id="pl-stat-form" class="grid-x">
      <div class="small-12 large-3 small-padding-right" style="width: 200px">
        <select name="year" onchange="this.form.submit()" id="year" style="border-radius: 20px">
          <?php foreach($avai_stat_y as $avai_stat_y): ?>
            <option <?php if(isset($select_year) && $select_year == $avai_stat_y){echo 'selected= "selected"';} ?> value="<?php echo $avai_stat_y ?>"><?php echo g365_date_format($avai_stat_y, 2)?> Season</option>
          <?php endforeach; ?>
        </select>
      </div>
    </form>
   </div>
   <h3><?php echo $player_data[0]->player_name; ?></h3>
    <div class="<?php echo $ev_avg; ?>"><?php //g365_dir_render('admin-stat', 'individual-player-stat', $pl_id, array(1, $select_year)); ?></div>
    <?php echo $indi_tb; foreach($player_data as $key => $pl_data): $decode_stat = json_decode($pl_data->stats); ?>
    <tr>
      <td><?php echo $pl_data->event_name;  ?></td>
      <td><?php echo (!empty($pl_data->ros_level) ? $key_level[$pl_data->ros_level] : "-"); ?></td>
      <td><?php echo (!empty($pl_data->ros_division) ? $pl_data->ros_division : "-"); ?></td>
      <td><?php echo (!empty($pl_data->team_name) ? $pl_data->team_name : "-"); ?></td>
      <td><?php echo date_format(date_create($pl_data->start_time), 'M d Y g:i A') ." : ". $pl_data->court; ?></td>
      <td><?php echo (!empty($decode_stat->pts) ? $decode_stat->pts : "0"); ?></td>
      <td><?php echo (!empty($decode_stat->rbs) ? $decode_stat->rbs : "0"); ?></td>
      <td><?php echo (!empty($decode_stat->ast) ? $decode_stat->ast : "0"); ?></td>
      <td><?php echo (!empty($decode_stat->three_pt) ? $decode_stat->three_pt : "0"); ?></td>
      <td><?php echo (!empty($decode_stat->stl) ? $decode_stat->stl : "0"); ?></td>
      <td><?php echo (!empty($decode_stat->blk) ? $decode_stat->blk : "0"); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
   </table>
<?php else: ?>
<h3 class="small-padding"><?php echo g365_message()['admin_pl_st']; ?></h3>
<?php endif; endif; 
?>