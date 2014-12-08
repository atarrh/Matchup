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


if (isset($_POST['submit'])) {

    $time = $_POST['time'];
    $length = $_POST['length'];
    $day = $_POST['day'];
    $date = $day . " " . $time;

    include("connect.php");
    // $query = "SELECT * FROM waiting WHERE other_email='$email';";
    $query1 = "UPDATE waiting SET request_date='$date' WHERE user_email='$email';";
    $query2 = "UPDATE waiting SET request_length='$length' WHERE user_email='$email';";
    $query_db1 = mysql_query($query1);
    $query_db2 = mysql_query($query2);

    if (!($query_db1 and $query_db2)) {
        die(mysql_error());
    }

    if (!mysql_close($dbhandle)) {
        die("Could not successfully close db!");    
    }

    // WRONG: Now need to connect to DB to see if time is free...
    // don't need to check if time is free? Wait for response from other user?

    echo "<h2>Your request has been submitted!</h2>";
    echo "<h3>Please <a href= $logout_url >log out</a> and wait for other users to respond.....</h3>";




} else {


}




?>
