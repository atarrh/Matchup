<?php

// Require the google php client code to make our lives easier
require_once 'google-api-php-client-master/autoload.php';

// Start a PHP session to allow storing of variables in _SESSION array;
// required for persistent logins
session_start();

// Initialize the client across stages of authorization; insert data from
// Google developer console
$client = new Google_Client();
$client->setApplicationName("Matchup");

$client->setClientId('434299988117-l22dh8hrfccrcgidjnm1843t5efko4s6.apps.googleusercontent.com');
$client->setClientSecret('SDLY7zg5XOtavpIvGB0GkLji');
$client->setRedirectUri('http://localhost/~atarrh/matchup/index.php');
$client->addScope('https://www.googleapis.com/auth/calendar');

?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Muh login</title>
    </head>
    <body>
        <h1>OAuth2.0 Authorization Testing</h1>

            <?php
            
            if (isset($_GET['code'])) {
                $client->authenticate($_GET['code']);
                $_SESSION['access_token'] = $client->getAccessToken();
                $token = $_SESSION['access_token'];
                echo "<h2>redirectin'</h2>";

                // $redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/matchup/app/';
                $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

                echo "<h3>$redirect</h3>";
                echo "<h4>Access token: $token</h4>";

                header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));


            
            } else if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

                $client->setAccessToken($_SESSION['access_token']);
                echo "<p>Might be doing something useful</p>";

                // Important code            
                $service  = new Google_Service_Calendar($client);
                $calendar = $service->calendars->get('primary');
                $someStr  = $calendar->getSummary();

                // Print the primary calendar title to make sure we're not crazy
                echo "<h3>Primary calendar title: $someStr</h3>";


                $events = $service->events->listEvents('primary');
                
                while(true) {
                    foreach ($events->getItems() as $event) {
                        echo $event->getSummary();
                    }
                    $pageToken = $events->getNextPageToken();
                    if ($pageToken) {
                        $optParams = array('pageToken' => $pageToken);
                        $events = $service->events->listEvents('primary', $optParams);
                    } else {
                        break;
                    }
                }



                // $feed = $calendar->getCalendarEventFeed();
                // echo "<ul>\n";
                // foreach ($eventFeed as $event) {
                //     echo "\t<li>" . $event->title->text .  " (" . $event->id->text . ")\n";
                //     echo "\t\t<ul>\n";
                //     foreach ($event->when as $when) {
                //         echo "\t\t\t<li>Starts: " . $when->startTime . "</li>\n";
                //     }
                //     echo "\t\t</ul>\n";
                //     echo "\t</li>\n";
                // }
                // echo "</ul>\n";


                // Depricated code
                //$xml = file_get_contents("https://www.googleapis.com/calendar/v3/users/me/settings");
            
                //$someStr = "";
                //$settings = $service->settings->listSettings();
                //foreach ($settings->getItems() as $setting) {
                //  $someStr = $someStr +  $setting->getId() . ': ' . $setting->getValue() + "\n";
                //} 

            } else {
                $authUrl = $client->createAuthUrl();
                echo "<a href= $authUrl >Click here to login to google</a>";
                
                $token = $_SESSION['access_token'];
                echo "<p>$token</p>";
            }
            
            ?>
        
    </body>
</html>


