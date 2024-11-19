<!-- <p>Content for player directory</p> 
* event filtered player directory.
-->
<?php  

?>
<h2>
  Player Search 
<!--   <?php    echo $player_id;     ?> -->
</h2>

<div class="hhh-player-direc">
  Search all players who participated in this event.
  
</div>

<!-- <?php 
  $livesearching = '<div class="relative small-12 medium-12 large-12 large-padding-bottom scouting-search-padd">
			<span class="search-mag fi-magnifying-glass"></span>
			<input type="text" class="search-hero" id="search-sub-dir" placeholder="Enter Player Name" autocomplete="off" autofocus> 
      <div id="result-sub" class="resulting_custom_live"></div>
		</div>';

echo $livesearching;
?> -->

<div class="relative small-12 medium-12 large-12 large-padding-bottom scouting-search-padd">
			<span class="search-mag fi-magnifying-glass"></span>
			<input type="text" class="search-hero" id="search-sub-dir" placeholder="Enter Player Name" autocomplete="off" autofocus> 
      <div id="result-sub" class="resulting_custom_live"></div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- <script src="../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-js/hhh-livesearch.js"></script> -->

<script style="display: none;">

$(document).ready(function(){
  
  $('#search-sub-dir').keyup(function(){
                var query = $(this).val();
              
                // Check if the current domain is your development site if yes then it gets access to dev db if not and its in the live site then it gets access to live db
                if(window.location.hostname === 'dev.sportspassports.com'){var ajaxBaseUrl = '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cj-livesearch-dev.php'}else if(window.location.hostname === 'sportspassports.com'){var ajaxBaseUrl = '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cj-livesearch-live.php'};
                //to check what directory the search is going too.
                //console.log(ajaxBaseUrl);  
              
                if(query !== ''){
                  var event_id = <?php  echo json_encode($player_id);  ?>;
                    $.ajax({
                        url: ajaxBaseUrl,
                        method: 'POST',
                        data: {query: query,
                               event_id: event_id
                              },
                        dataType: 'json',
                        success:function(data){
                            var resultContainer = $('#result-sub');
                            resultContainer.empty();
                          
                            
                            if (data.error) {
                                // Handle the error case
                                console.error(data.error);
                            } else {
                                // Handle each result individually
                                $.each(data, function(index, value){
                                    var column_name = value.name;
                                    resultContainer.append('<div class="live-results"><a class="button g365-button expanded no-margin-bottom custom-live-button" href=" '+ window.location.origin +'/player/'+ value.nickname +'">' + value.name + ' <small>(' + value.city + ',' + value.state + ')</small></a></div>');
                                });
                            }
                            
                          
                          
                        },
                        error: function (xhr, status, error) {
                            // Handle the AJAX error
                            console.error("AJAX Error: " + status + " - " + error);
                        }
                    });
                } else {
                    $('#result-sub').html('');
                }
            });
  
});

</script>