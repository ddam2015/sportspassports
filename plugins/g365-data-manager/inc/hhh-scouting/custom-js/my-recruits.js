// this is where I make the connection to my query whenever the user updates the notes
$(document).ready(function () {
//     $("#original-pre-submit").html("Default Text");
});

function editNotes(playerId, currentUserID) {
    // Get the input value
    var inputData = $(".notesInput[data-player-id='" + playerId + "']").val();

    // Make an Ajax request
    $.ajax({
        type: "POST",
        url: "../wp-content/plugins/g365-data-manager/inc/hhh-scouting/custom-database/dev-site/cj-my-recruits-data-handler-dev.php",
        data: { inputData: inputData, 
               playerID: playerId,
               currentUserID: currentUserID 
              },
        dataType: "json",
        success: function(response) {
            // Update the input value with the response from the server
            $(".notesInput[data-player-id='" + playerId + "']").val(response);

            // Display a message or handle the response as needed
            $("#response" + playerId).html("Notes updated successfully: '" + response + "'");
            $("#original-pre-submit" + playerId).html("Current Notes: " + response);
        },
        error: function(error) {
            console.log("Error:", error);
        }
    });
}