<?php

require_once '../google-api-php-client/autoload.php';

session_start();

// Initialize the client across stages of authorization; insert data from
// Google developer console
include("../client.php");

$logout_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/logout.php';

// If a user isn't logged in, redirect them to the main page:
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    header('Location: ' . filter_var($logout_url, FILTER_SANITIZE_URL));
}

// Important code
$service = new Google_Service_Calendar($client);
$calendar = $service->calendars->get('primary');
$email = $calendar->getSummary();


// Database crap to test if a user was on the waiting list
include("debug.php");
include("connect.php");
$query = "SELECT * FROM waiting WHERE other_email = '$email';";
$query_waiting = mysql_query($query);

// Was testing to see how to test if value exists in table
// If a user is NOT being waited on, redirect them to the logout
// page posthaste!
if (mysql_num_rows($query_waiting) == 0) {
    die("Nobody was waiting on you!");
    // header('Location: ' . filter_var($logout_url, FILTER_SANITIZE_URL));
} else if (mysql_num_rows($query_waiting) > 1) {
    die("More than one user waiting on you! Don't know how.");
}

$row = mysql_fetch_array( $query_waiting );

if (mysql_close($dbhandle)) {
    // echo "<p>Database successfully closed~</p>";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Muh Consent Page</title>
    </head>
    <body>

        <?php

        echo "<h1>Matchup app - Consent page</h1>";

        if (!isset($_POST['submit'])) {
            $user = $row['user_email'];
            echo "<h3>Do you want to grant $user permission to schedule a meeting?</h3>";

            ?>

            <!-- Insert stupid simple form here -->
            <form action='consent.php' method='POST'>
                <input type='radio' name='consent' value='yes' checked />
                    <label for = 'yes'>yes</label>
                <input type='radio' name='consent' value='no' />
                    <label for = 'no'>no</label>
                <input type='submit' name='submit' value='submit' />
            </form>

            <?php

        } else {
            echo "<p>Why sure thing doc!</p>";
            $consent = $_POST['consent'];
            echo "<p>Will execute a $consent for you.</p>";

            // gotta connect to db
            include("connect.php");
            // $query = "SELECT * FROM waiting WHERE other_email = '$email';";
            $query = "UPDATE waiting SET consent 1 WHERE other_email = '$email';";
            $query_waiting = mysql_query($query);
            
            // Was testing to see how to test if value exists in table
            // If a user is NOT being waited on, redirect them to the logout
            // page posthaste!
            if (mysql_num_rows($query_waiting) == 0) {
                die("Nobody was waiting on you!");
                // header('Location: ' . filter_var($logout_url, FILTER_SANITIZE_URL));
            } else if (mysql_num_rows($query_waiting) > 1) {
                die("More than one user waiting on you! Don't know how.");
            }
            
            $row = mysql_fetch_array( $query_waiting );
            
            if (mysql_close($dbhandle)) {
                // echo "<p>Database successfully closed~</p>";
            }

        }

        ?>




    </body>
</html>

