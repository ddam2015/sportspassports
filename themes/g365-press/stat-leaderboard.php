<?php 
 /**
 * Template Name: Stat Leaderboard
 * Author: Daradona Dam
 * Version: 1.0
 */
get_header();
global $wp_query;
// $headerbg = get_site_url() . '/wp-content/uploads/2021/09/stat-header.jpg'; 
$headerbg = 'https://sportspassports.com/wp-content/uploads/2024/03/Stat-Background.png'; 

?>
<div class="full_width_container small-12 medium-12 large-12 medium-padding-top">
  <div class="stat-header__wrap large-margin-bottom">
    <img src="<?php echo $headerbg ?>" class="stat-header__img" alt="stat-header image">
    <div class="stat-header__info">
      <h2 class="stat-header__heading">Stat Leaderboard <?php echo g365_date_format('full_year', 5) ?></h2>
    </div>
  </div>
</div>
<?php
  switch( $wp_query->query_vars['pg_type'] ){
    case '':
    case 'event':
    case 'year':
    case 'organization-list':
      g365_dir_render('stat-leaderboard','org-menu', '', $arg = null);
      break;
//       g365_dir_render('stat-leaderboard','by-event', '', $arg = null);
//       g365_dir_render('stat-leaderboard','by-year', '', $arg = null);
//       g365_dir_render('stat-leaderboard','by-event', '', $arg = null);
//       break;
  }
if( $wp_query->query_vars['pg_type'] !== 'organization-list' ): ?>
  <ul class="accordion xlarge-margin-top" data-accordion data-allow-all-closed="true">
    <li class="accordion-item" data-accordion-item>
      <a href="#" class="accordion-title disclaimer--stat">Stat Disclaimer</a>
      <div class="accordion-content" data-tab-content>
          <p>We make every effort to provide the most accurate stats possible. However, a number of factors including but not limited to duplicate jerseys and the subjective nature of certain basketball stats, prevent us from 100% accuracy. The stats we provide are intended to be a metric to track progress 
          and reward achievement over the course of a youth basketball career while providing a more robust experience for players and teams. It is not possible to provide this experience without some margin of error. We will do our best to provide the most accurate statistical data 
          possible, but we reserve the right to refuse any request to update statistics.
          </p>
      </div>
    </li>
  </ul>
<?php endif; get_footer(); ?>