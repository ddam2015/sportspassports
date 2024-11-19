/*** Player Badge Front End ***/
function badge_navigator(pointer){
  var badge_type = pointer;
  var btn = pointer.id;
  // Toggle View and Minimize all badge stats
  if($('#'+btn)[0].innerHTML.includes('View')){ $('#'+btn)[0].innerHTML = $('#'+btn)[0].innerHTML.replace('View All', 'Minimize'); }
  else if($('#'+btn)[0].innerHTML.includes('Minimize')){ $('#'+btn)[0].innerHTML = $('#'+btn)[0].innerHTML.replace('Minimize', 'View All'); }
  var badge_main_container = (pointer.parentNode).parentNode.parentNode.id; // Two levels up
  var badge_type = pointer.parentNode.id;
  $('#'+badge_main_container+' #'+badge_type+' .bdg_main_catagories').toggle('show');
  $('#'+badge_main_container+' #'+badge_type+' .bdg_stat_view').toggle('hide');
}
// if(document.querySelector('.page-template-player-profile .badge-overview')) {
//   var badgeBtns = document.querySelectorAll('.badge-button');

//   var badgeOverview = document.querySelector('.badge-overview');
//   var badgeSelected = document.querySelector('#badgeSelected');
//   var badgeSelectedHeading = document.querySelector('#badgeSelectedHeading');
//   var badgeSelectedSubheading = document.querySelector('#badgeSelectedSubheading');
//   var badgeSelectBack = document.querySelector('#badgeSelectBack');
//   var toggleSwitch = document.querySelector('.toggle-switch-container');

//   badgeBtns.forEach(function(badge) {
//     var category = badge.dataset.target;

//     badge.addEventListener('click', function(){
//     var categoryParent = badge.closest('.badge-category');
//     var heading = categoryParent.querySelector('.badge-category--heading').textContent;
//     var subheading = badge.closest('.badge-row').querySelector('h3').innerText;
//       console.log(subheading);
//       badgeView(category, heading, subheading);
//     });
//   });

//   badgeSelected.addEventListener('click', function(e) {
//     if(e.target.id == 'badgeSelectBack') {
//      closeBadgeView(); 
//     }
//   })

//   function badgeView(category, heading, subheading) {
//     openBadgeView();
//     badgeSelectedHeading.innerHTML = heading + ' Achievements';
//     badgeSelectedSubheading.innerHTML = subheading;
//     console.log(badgeSelectedHeading.innerHTML);
//    switch(category) {
//      case 'cumulative_individual_event':
//        toggleSwitch.style.display = 'block';
// //          console.log('total');
//        break;
//      case 'avg_cond_indi_event':
//        toggleSwitch.style.display = 'block';
// //          console.log('avg');
//        break;
// //     case for years
//      default:
//        toggleSwitch.style.display = 'none';
//        break;
//    }
//   }

//   function openBadgeView() {
//     badgeSelected.style.display = 'block';
//     badgeOverview.style.display = 'none';
//   }

//   function closeBadgeView() {
//     badgeSelected.style.display = 'none';
//     badgeOverview.style.display = 'block';
//   }
//   var checkbox = document.querySelector("#eventCategorySwitch");

// //     checkbox.addEventListener('change', function() {
// //         if (this.checked) {
// //           eventSelectedHeading.text('Averages');
// //           console.log('check')
// //         } else {
// //           eventSelectedHeading.text('Totals');
// //         }
// //     });
// //     2021
// //     2022
// //     2023
// }