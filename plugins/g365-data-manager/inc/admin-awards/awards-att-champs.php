 <form class="input-group-label" id="awards" method="post">
        <label class="input-group-label" for="start_date" name="start_date">Start Date:</label>
        <input type="date" class='input-group-field' id="start_date" name="start_date" data-g365_type="" placeholder="mm/dd/yyyy" autocomplete="off" value="YYYY-MM-DD" autofocus>
        <label class="input-group-label" for="end_date" name="end_date">End Date:</label>
        <input type="date" class='input-group-field' id="end_date" name="end_date" data-g365_type="" placeholder="mm/dd/yyyy" value="YYYY-MM-DD" autofocus>
        <button type="submit" onclick="submit_form()">Submit</button>
      </form>

      <h2>Awards</h2>
      <table class="award_badges">
        <thead>
          <tr>
            <th>Champions</th>
            <th>Runner Up</th>
            <th>All MVP</th>
            <th>All Team</th>
          </tr>
        </thead>
        <tbody class="badges">
          <!-- Badges appear here -->
        </tbody>
      </table>

      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
      <script>
        function submit_form() {
          var form_data = $('#awards').serialize(); // Serialize the form data

          $.ajax({
            type: 'POST',
            url: '/wp-content/plugins/g365-data-manager/g365-data-manager-dev-team.php',
            data: form_data,
            success: function(response) {
              console.log(response);
            },
            error: function(error) {
              console.error(error);
            }
          });
        }
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $start_date = $_POST['start_date'];
//     $end_date = $_POST['end_date'];

//     mk_g365_get_event_date($start_date, $end_date);
// }
      </script>

