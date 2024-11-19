<?php
 /**
 * Template Name: Club Team Standing
 * Author: Daradona Dam
 * Version: 1.0
 */
    get_header(); 
  global $wp_query; $key_level = (g365_return_keys('g365_grade_key'));
//   $headerbg = get_site_url() . '/wp-content/uploads/2021/09/club-team-standing-banner.jpg';
  $headerbg = 'https://sportspassports.com/wp-content/uploads/2024/03/Standings-Background.png';
  
?>
<div id="dialong_result_box_div"></div>
<div id="dialong_div"></div>
<!-- <script src='https://dev.grassroots365.com/tournament-table.js?ver=1618947760' id='js-g365-all-admin-tournament-js'></script> -->
<div class="full_width_container small-12 medium-12 large-12 medium-padding-top">
  <div class="stat-header__wrap large-margin-bottom">
    <img src="<?php echo $headerbg ?>" class="stat-header__img" alt="stat-header image">
    <div class="stat-header__info">
      <h2 class="stat-header__heading">Team Standings <?php echo g365_date_format('full_year', 5) ?></h2>
    </div>
  </div>
</div>
<!-- <div><h5>Glossary</h5><ul class="ul-display--3"><li class="li-display--3"><span>W </span>Wins</li><li class="li-display--3"><span>L </span>Losses</li><li class="li-display--3"><span>PCT </span>Winning Percentage</li><li class="li-display--3"><span>PPG </span>Points Per Game</li><li class="li-display--3"><span>PPG </span>Opponent Points Per Game</li></ul></div> -->
<?php
if(!empty($wp_query->query_vars['lv'])){ $season_year = ''; }else{ $season_year = year_dd_opt('ct_year', array(url_param('y')))[1]; }
  switch( $wp_query->query_vars['pg_type'] ){
    case '':
    case 'high-school':
      g365_dir_render('club-team-standing','team-standing', '', [cts_type_selector()[2], 'season_year'=>$season_year]);
      break;
    case 'youth-boys':
      g365_dir_render('club-team-standing','team-standing', '', [cts_type_selector()[0], 'season_year'=>$season_year]);
      break;
    case 'youth-girls':
      $ev_id = isset($_GET['ev_id']) ? sanitize_text_field($_GET['ev_id']) : '';
//       echo("<script>console.log('test87: " . $ev_id . " ');</script>");
      g365_dir_render('club-team-standing','team-standing', '', [cts_type_selector()[1], 'season_year'=>$season_year, 'event_id'=>$ev_id]);
      break;
    case 'team-box-score':
      g365_dir_render('club-team-standing','team-box-score', '', $arg = null);
      break;
  }
  get_footer(); 
?>