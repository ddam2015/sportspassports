<?php  
// * Description: This is where I use Dara's API to create the stat leaderboard and wait till 
// its made to programmatically add the stars to each row of the players. When star is clicked 
// it will add that player to the favorites of the current user logged in.

// include '../g365-data-manager-dev-team.php'; 

$unlocked = 0;
$current_user_id = get_current_user_id();
// echo $current_user_id . ' ';
if($current_user_id != 0){
$unlocked = g365_check_scouting_unlocked($current_user_id);
}
if(empty($unlocked)){$unlocked = 0;}

// echo "player " . $unlocked;

if($unlocked === 0 ){
  
?>
  <div class="scouting-false-container">
    <div class="scouting-false-mid">
      <h3 class="small-margin-top">
        Scouting Report Inactive.
      </h3>
      <p>
        In order to obtain access to all functionalities of the scouting report please login to account with access or purchase below.
      </p>
      <a class="button buttonization scouting-purchase directory-access" href="https://sportspassports.com/product/hhh-scouting-report/">Purchase Scouting Report </a>
    </div>
  </div>
  
<?php
}else{

?>

<!-- <?php  echo $player_id;     ?> -->
<div class="grid-x stl-event-container"></div>
<div class="spp-container maindir-container"></div>
<div class="loading-text loading-message">Loading...</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://media.sportspassports.com/js/sportspassports.features.min.js?version=<?= time() ?>"></script>
<link rel="stylesheet" id="spp-css" href="https://media.sportspassports.com/css/spp.style.min.css?version=1" type="text/css" media="all" />
<script>

(function ($) {
  
function grabIdinfo(value) {
//     console.log("Handling action for value: " + value);
    // Call an AJAX function to fetch data from the server
    fetchDataFromEventsTable(value);
}
  
// Function to fetch data from the events table using AJAX
function fetchDataFromEventsTable(eventId) {
  if(window.location.hostname === 'dev.sportspassports.com'){var ajaxBaseUrl = '/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cjcustomdb-dev.php'; var sitetype = 'dev';}else if(window.location.hostname === 'sportspassports.com'){var ajaxBaseUrl = '/srv/users/spp-serverpilot/apps/sportspassports-press/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cjcustomdb-live.php'; var sitetype = 'live';};
    // Make an AJAX request to get data from the events table
    $.ajax({
        type: 'POST',
        url: '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/stat-leaderboard-handler.php', // Adjust the path as needed
        dataType: 'json',
        data: {
            getEventData: 'getEventData', // Specify the action for the server to handle
            eventId: eventId,
            ajaxBaseUrl: ajaxBaseUrl,
            sitetype: sitetype
        },
        success: function(response) {
            // Handle the response from the server
            if (response.error) {
                console.error('Error:', response.error);
            } else {
                //console.log('Data from events table:', response);
                // Display the response within the div with the class stl-event-container
                displayResponseInContainer(response);
            }
        },
        error: function(xhr, status, error) {
            // Handle the error case
            console.error('AJAX error:', status, error);
            console.log('Response Text:', xhr.responseText);
        }
    });
}
  
function changeEvent($eventid){
//   console.log('a thousand cuts');
  $("#event_list").val($eventid);
  
}
  
// Function to display the response within the div with the class stl-event-container
function displayResponseInContainer(responseData) {
    var eventName = JSON.stringify(responseData.name);
    var stringWithoutQuotes = eventName.replace(/^"(.*)"$/, '$1');
    var name = stringWithoutQuotes.replace(/Hype Her Hoops\s*/i, '');
    var currentSelectedValue = $("#event_list").val();
    if(currentSelectedValue == JSON.stringify(responseData.id).replace(/^"(.*)"$/, '$1')){
      var containerContent = '<div class="ev_inner bg-gray stl-scouting-container"><a class="stl-scouting-button active" onclick="(function(button, eventId) { var buttons = document.querySelectorAll(\'.stl-scouting-button\'); buttons.forEach(function(btn) { btn.classList.remove(\'active\'); }); document.getElementById(\'event_list\').value = eventId; button.classList.add(\'active\'); })(this, ' + JSON.stringify(responseData.id).replace(/^"(.*)"$/, '$1') + ');"><img class="stl-scouting-img" src=' + JSON.stringify(responseData.logo_img) + '><p class="stl-scouting-evname">' + name + '</p></a></div>';
    }else{
      var containerContent = '<div class="ev_inner bg-gray stl-scouting-container"><a class="stl-scouting-button" onclick="(function(button, eventId) { var buttons = document.querySelectorAll(\'.stl-scouting-button\'); buttons.forEach(function(btn) { btn.classList.remove(\'active\'); }); document.getElementById(\'event_list\').value = eventId; button.classList.add(\'active\'); })(this, ' + JSON.stringify(responseData.id).replace(/^"(.*)"$/, '$1') + ');"><img class="stl-scouting-img" src=' + JSON.stringify(responseData.logo_img) + '><p class="stl-scouting-evname">' + name + '</p></a></div>';
    }
    $(".stl-event-container").append(containerContent);
}
  
function displaySelectedEventValues() {
    var allEventValues = []; // Use Set to store unique values
    // Collect all values
    $("#event_list option").each(function() {
        var value = $(this).val();
        allEventValues.push(value);
    });
    // Remove duplicates by creating a Set and converting it back to an array
    var uniqueEventValues = [...new Set(allEventValues)];
    // Convert values to numbers and sort them numerically
    var sortedEventValues = uniqueEventValues.map(Number).sort(function(a, b) {
        return a - b;
    });
    // Iterate over sorted values and call the grabIdinfo function
    sortedEventValues.forEach(function(value) {
        grabIdinfo(value);
    });
    // Convert Set to an array and join the values into a string
    var selectedEventValues = [...uniqueEventValues].join(', ');
}  

  
  
// Function to be executed when a star is clicked
function starClickHandler(event) {
        //console.log('made it here also');
         event.preventDefault();
  
         // Toggle the 'selected' class on the clicked star
         $(this).addClass('selected');
  
         // Grab the text within the first 'td' in the current 'tr'
         var info = $(this).data('info');
         info = info.toLowerCase().replace(/\s+/g, '-');
         starClickEvent(info);
}
   

function starClickEvent(info) {
            // Use your PHP variable in the AJAX data
            var phpVariable = <?php echo json_encode($player_id); ?>;
            if(window.location.hostname === 'dev.sportspassports.com'){var ajaxBaseUrl = '/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cjcustomdb-dev.php'; var sitetype = 'dev';}else if(window.location.hostname === 'sportspassports.com'){var ajaxBaseUrl = '/srv/users/spp-serverpilot/apps/sportspassports-press/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cjcustomdb-live.php'; var sitetype = 'live';};
            // Make an AJAX request to the PHP script when the star is clicked
            $.ajax({
                type: 'POST',
                url: '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/stat-leaderboard-handler.php',
                dataType: 'json',
                data: { info: info,
                        user: phpVariable,
                        ajaxBaseUrl: ajaxBaseUrl,
                        sitetype: sitetype
                      }, // Include the data in the request
                success: function (response) {
                    // Handle the response from the server
                    if (response.error) {
                        console.error('Error:', response.error);
                    } else {  
                      $.each(response, function(index, value){
                        //console.log('PHP function result:', value);
                      });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error case
                    console.error('AJAX error:', status, error);
                    //console.log('Response Text:', xhr.responseText);
                }
            });
}

  
  
function checkFavorites(currentUser, playerName) {
  if(window.location.hostname === 'dev.sportspassports.com'){var ajaxBaseUrl = '/srv/users/dd-dev-sites/apps/sportspassports-dev/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cjcustomdb-dev.php'; var sitetype = 'dev';}else if(window.location.hostname === 'sportspassports.com'){var ajaxBaseUrl = '/srv/users/spp-serverpilot/apps/sportspassports-press/public/wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cjcustomdb-live.php'; var sitetype = 'live';};
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: 'POST',
            url: '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/stat-leaderboard-handler.php',
            dataType: 'json',
            data: {
                currentUser: currentUser,
                playerName: playerName,
                ajaxBaseUrl: ajaxBaseUrl,
                sitetype: sitetype
            },
            success: function (response) {
                resolve(response);
            },
            error: function (xhr, status, error) {
                reject({ status: status, error: error });
            }
        });
    });
}
  
function bindStarClickEvents() {
    // Unbind the click event to avoid multiple bindings
    $('.maindir-container').off('click', '.star-custom');
    // Check if the table with class 'stat-table' exists
    if ($('.stat-table tbody tr').length) {
        // Display header star if not already added
        if ($('.stat-table thead .star').length === 0) {
            $('.stat-table thead').find('tr').each(function () {
                // Create a new 'td' with a star
                //console.log('made it');
                var starCell = $('<td><span class="star">&#9733;</span></td>');
                // Prepend the 'td' to the current 'tr'
                $(this).prepend(starCell);
            });
        }


$('.maindir-container').on('click', '.star-custom', starClickHandler);
        // Use event delegation for the click event
        
        // Display player stars if not already loaded
            if ($('.stat-table tbody .star-custom').length === 0) {
                $('.stat-table tbody').find('tr').each(function () {
                  // Grab the text within the first 'td' in the current 'tr'
                  var textInFirstTd = $(this).find('td:first-child').text();
                  
                  // Store reference to 'this' before entering the promise block
                  var currentRow = this;  
                  
                  //add function here to check if the player is in current users favorites
                  var currentUser = <?php echo json_encode($player_id); ?>;
//                     console.log(currentUser);
//                     console.log(textInFirstTd);
                  
                  
                  // Call the checkFavorites function and handle the result asynchronously
                  checkFavorites(currentUser, textInFirstTd)
                      .then(function (result) {
//                           console.log(result);
                          // Use the result to determine whether the player is a favorite
                          var isFavorite = result && result.exists;

                          // Create a new 'td' with a star and apply the 'selected' class if the player is a favorite
                          var starCell = $('<td><a class="btn-flip star-custom" data-back="⭐️" data-front="✩" data-info="' + textInFirstTd + '"></a></td>');
                          if (isFavorite) {
                              starCell.find('.star-custom').addClass('selected');
                          }

                          // Place the 'td' to the current 'tr'
                          $(currentRow).prepend(starCell);
                      })
                      .catch(function (error) {
                          console.error('Error checking favorites:', error);
                      });
                });
            }

    } else {
        console.error("Table with class 'stat-table' not found.");
    }
}

var apiSummoned = false;
function primedircall() {
    if (apiSummoned) {
        return;
    }

    // Check if the API form is already summoned
    if ($('.maindir-container').hasClass('api-summoned')) {
        return;
    }

    var url = 'https://sportspassports.com/api-data-form/';
    var request_url = document.URL;
    var api_keys = 'SDRzVzV4RzI1ZWxSbTBsaUI3Ujk2Yzc1aE5Hd0g1TVBzeEVBRE9vdHVzb2oyOUo4eTlqTk9ValVNQT09';
    var secret_keys = 'VnZZQU9sVUFjYWlxSXlIK0M4R25Cdz09';
    var get_url = new URL(url);
    get_url.searchParams.append('api_keys', api_keys);
    get_url.searchParams.append('secret_keys', secret_keys);
    get_url.searchParams.append('request_url', request_url);

    $.ajax({
        type: 'GET',
        url: get_url.href,
        context: document, // Set the context to document
        success: function (response) {
            var $maindirContainer = $('.maindir-container');
            // Append the response to the container
            $maindirContainer.append(response);
          
            var hideEvent = $('.maindir-container #event_list');
            hideEvent.addClass('hide');
            var loadingcontainer = $('.maindir-container .spp_slb_content');
            loadingcontainer.addClass('hide');

            var checkElement = $('.spp-container').find('.stat-table tbody tr').length;
            //console.log($('.maindir-container').find('.stat-table tbody tr').length);
            displaySelectedEventValues();

            if (checkElement > 0) {
                //console.log('domain expansion');
//                 console.log(response);
                $maindirContainer.addClass('api-summoned');
                apiSummoned = true; // Set the flag to true when the API form is summoned
                bindStarClickEvents();
                clearInterval(checkInterval);
            } else {
                //console.log('ohno');
                
            }
        },
    });
}
  
// Set an interval to periodically check and recall primedircall
var checkInterval = setInterval(function () {
    primedircall();
}, 1000);

// Stop the interval if the API form is summoned
$(document).ajaxComplete(function () {
    if ($('.maindir-container').hasClass('api-summoned')) {
        clearInterval(checkInterval);
        
    }
});
  
primedircall();

// Function to bind star click events
function bindStars() {
    return new Promise(function (resolve, reject) {

      
      // Unbind the click event to avoid multiple bindings
    $('.maindir-container').off('click', '.star-custom');

    // Check if the table with class 'stat-table' exists
    if ($('.stat-table tbody tr').length) {
        // Display header star if not already added
        if ($('.stat-table thead .star').length === 0) {
            $('.stat-table thead').find('tr').each(function () {
                // Create a new 'td' with a star
//                 console.log('made it');
                var starCell = $('<td><span class="star">&#9733;</span></td>');

                // Prepend the 'td' to the current 'tr'
                $(this).prepend(starCell);
            });
        }


        $('.maindir-container').on('click', '.star-custom', starClickHandler);
        // Use event delegation for the click event
        
        // Display player stars if not already loaded
            if ($('.stat-table tbody .star-custom').length === 0) {
                $('.stat-table tbody').find('tr').each(function () {
                  // Grab the text within the first 'td' in the current 'tr'
                  var textInFirstTd = $(this).find('td:first-child').text();
                  // Store reference to 'this' before entering the promise block
                  var currentRow = this;  
                  //add function here to check if the player is in current users favorites
                  var currentUser = <?php echo json_encode($player_id); ?>;
                  //console.log(currentUser);
                  //console.log(textInFirstTd);
                  // Call the checkFavorites function and handle the result asynchronously
                  checkFavorites(currentUser, textInFirstTd)
                      .then(function (result) {
                          //console.log(result);
                          // Use the result to determine whether the player is a favorite
                          var isFavorite = result && result.exists;
                          // Create a new 'td' with a star and apply the 'selected' class if the player is a favorite
                          var starCell = $('<td><a class="btn-flip star-custom" data-back="⭐️" data-front="✩" data-info="' + textInFirstTd + '"></a></td>');
                          if (isFavorite) {
                              starCell.find('.star-custom').addClass('selected');
                          }
                          // Place the 'td' to the current 'tr'
                          $(currentRow).prepend(starCell);
                      })
                      .catch(function (error) {
                          console.error('Error checking favorites:', error);
                      });     
                });
            }
    } else {
        console.error("Table with class 'stat-table' not found.");
    }
        resolve(); // Resolve the promise once the elements are present
    });
}
  
function reloadingTable(){
  setTimeout(hideLoadingMessage, 5000);
}
  
// Function to handle the filter button click
function handleFilterButtonClick() {
    //console.log('deep purple');
    // Wait for the table and player rows to be generated before calling bindStars
    checkTableAndRows().then(function () {
            var loadingcontainer = $('.maindir-container .spp_slb_content');
            loadingcontainer.addClass('hide');
            var loadingcontainer = $('.loading-message');
            loadingcontainer.removeClass('hide');
            reloadingTable();
        // Call bindStars to regenerate the stars
        bindStars().then(function () {
            // Stars are now bound after the filter
            $(".stl-event-container").empty();
            displaySelectedEventValues();
        });
    });
}
  

// Function to check if the table and player rows are generated
function checkTableAndRows() {
    return new Promise(function (resolve) {
        var checkInterval = setInterval(function () {
            var checkElementFilter = $('.spp-container').find('.stat-table tbody tr').length;

            if (checkElementFilter > 0) {
//                 console.log('ten shadows');
                //console.log('Table and rows generated');
                clearInterval(checkInterval);
                resolve();
            }
        }, 1000);
    });
}
  
function hideLoadingMessage() {
    var loadingcontainer = $('.maindir-container .spp_slb_content');
    loadingcontainer.removeClass('hide');
    var loadingcontainer = $('.loading-message');
    loadingcontainer.addClass('hide');
    //console.log("load");
}
  
  
// Add an event listener for the filter button
$(document).on('click', '#slb_submit_btn', handleFilterButtonClick);
  
// Event listener for the change event on the year dropdown
$(document).on('change', '#spp_stat_year', handleFilterButtonClick);
  
setTimeout(hideLoadingMessage, 7000);
  
})(jQuery);
</script>

<?php
      
}