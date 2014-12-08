<?php

require_once '../google-api-php-client/autoload.php';

session_start();

// Initialize the client across stages of authorization; insert data from
// Google developer console
include("../client.php");
include("functions.php");

$logout_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/logout.php';
$consent_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/consent.php';

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
date_default_timezone_set('America/New_York');


?>


