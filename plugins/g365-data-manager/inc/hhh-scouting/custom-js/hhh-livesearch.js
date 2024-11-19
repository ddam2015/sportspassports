// Description: This is the ajax call that is made for the main player directory of the scouting report in order to have the livesearch work.

$(document).ready(function(){
            $('#search').keyup(function(){
                var query = $(this).val();
              
                // Check if the current domain is your development site if yes then it gets access to dev db if not and its in the live site then it gets access to live db
                if(window.location.hostname === 'dev.sportspassports.com'){var ajaxBaseUrl = '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cj-livesearch-dev.php'}else if(window.location.hostname === 'sportspassports.com'){var ajaxBaseUrl = '../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/live-site/cj-livesearch-live.php'; console.log(ajaxBaseUrl);};
                //to check what directory the search is going too.  
              
                if(query !== ''){
                    $.ajax({
                        url: ajaxBaseUrl,
                        method: 'POST',
                        data: {query: query,
                               
                              },
                        dataType: 'json',
                        success:function(data){
                            var resultContainer = $('#result');
                            resultContainer.empty();
                          
                            
                            if (data.error) {
                                // Handle the error case
                                console.error(data.error);
                            } else {
                                // Handle each result individually
                                $.each(data, function(index, value){
                                    var column_name = value.name;
                                    resultContainer.append('<div class="live-results"><a target="_blank" class="button g365-button expanded no-margin-bottom custom-live-button" href=" '+ window.location.origin +'/player/'+ value.nickname +'">' + value.name + ' <small>(' + value.city + ',' + value.state + ')</small></a></div>');
                                });
                            }
                            
                          
                          
                        },
                        error: function (xhr, status, error) {
                            // Handle the AJAX error
                            console.error("AJAX Error: " + status + " - " + error);
                        }
                    });
                } else {
                    $('#result').html('');
                }
            });
        });