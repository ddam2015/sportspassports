<?php
require( 'json-response-headers.php' );

if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) {
  exit();
}

require( 'session-handler.php' );
// echo "<pre>"; print_r($_SESSION['token']); echo "</pre>";
// echo "<pre>"; print_r($_POST['g365_token']); echo "</pre>";

if ( empty($_SESSION['token']) ) die('Missing server token.');
if ( empty($_SESSION['time']) ) die('Missing server time.');

file_exists(__DIR__ . $DS . 'Handler.php') ? require_once __DIR__ . $DS . 'Handler.php' : die('Handler.php not found');

/**
 * Class G365_Livesearch
 */
class G365_Livesearch
{
    private $handler;

    /**
     * G365_Livesearch constructor.
     */
    public function __construct()
    {
        $this->handler = new Handler();
    }

    /**
     * Validate the request
     */
    private function validateRequest()
    {
        // 1. Validate if the request is AJAX
        if ($this->handler->isAJAX() !== true) {
            $this->handler->formResponse('failed', 'Error: Request must be AJAX');
        }

        // 2. A layer of security against those Bots that submit a form quickly
        if (!isset($_POST['g365_time']) ||
            $this->handler->verifyBotSearched($_POST['g365_time']) !== true) {
            // Searching is started sooner than the search start time offset
            $this->handler->formResponse('failed', 'Error: You are too fast, or this is a Bot. Please search now.');
        }

        // 3. Verify the token - CSRF protection
        if (!isset($_POST['g365_token']) ||
            $this->handler->verifySessionValue('token', $_POST['g365_token']) !== true ||
            !isset($_POST['ls_anti_bot']) ||
            $this->handler->verifySessionValue('anti_bot', $_POST['ls_anti_bot']) !== true
        ) {
            // Tokens are not matched
            $this->handler->formResponse('failed', 'Error: Token mismatch. Refresh the page. It seems that your session is expired.');
        }

        // 4. Validate all inputs
        $errors = $this->handler->validateInput($_POST);

        if (!empty($errors)) {
            // Required inputs are not provided
            $this->handler->formResponse('failed', 'Error: Required or invalid inputs: ' . implode(',', $errors));
        }
    }

    /**
     * Search in database
     */
    private function search()
    {
        // DD
        isset($_POST['ls_query_lock']) ? $ls_query_lock = $_POST['ls_query_lock'] : $ls_query_lock = '';
        isset($_POST['ls_user_ac']) ? $ls_user_ac = $_POST['ls_user_ac'] : $ls_user_ac = '';
        try {
            // Start looking for the query
            $result = json_encode($this->handler->renderView(
                $_POST['ls_query_id'],
                $_POST['ls_query'],
                (int) $_POST['ls_current_page'],
                (int) $_POST['ls_items_per_page'],
                $ls_query_lock,
                $ls_user_ac
            ));
        } catch (\Exception $e) {
            $caughtError = $e->getMessage();
        }

        if (empty($caughtError)) {
            // 5. Return the result
            $this->handler->formResponse('success', 'Successful request', $result);
        } else {
            $this->handler->formResponse('failed', $caughtError);
        }
    }

    /**
     * Process the request
     */
    public function process()
    {
        $this->validateRequest();

        $this->search();
    }
}

/**
 * Create a new object of the class and call process function
 */
(new G365_Livesearch())->process();
