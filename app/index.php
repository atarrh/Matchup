<?php

require_once '../google-api-php-client-master/autoload.php';

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
 
// if (isset($_POST['submit'])) {
//     echo "got here";
// }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Muh Matchup app</title>


        <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script>
        $(function() {
            $( "#datepicker" ).datepicker();
        });
        </script>-->

    </head>
    <body>


        <h2> Welcome to Matchup! </h2>

            <?php
                echo "<h1>Might actually be working...</h1>";

                echo "<a href= $logout_url >Click here to logout of Matchup!!!</a>";

                // Important code            
                $service  = new Google_Service_Calendar($client);
                $calendar = $service->calendars->get('primary');
                $someStr  = $calendar->getSummary();

                // Print the primary calendar title to make sure we're not crazy
                echo "<h3>Primary calendar title: $someStr</h3>";

                // echo "<p>type: " . gettype($someStr) . "; length: " . strlen($someStr) . "</p>";
                // var_dump($_POST);

                if (isset($_POST['submit'])) {
                    echo "<h3>Waiting for other users to respond...</h3>";
                    $day = $_POST['date'];
                    $others = $_POST['others'];

                    date_default_timezone_set('EST');

                    $minTime = new DateTime($day . " 00:00:00");
                    $maxTime = new DateTime($day . " 23:59:59");

                    $test = date($minTime::ATOM);
                    // $test = $minTime->format('Y-m-d H:i:s');
                    echo "<p>$test</p>";

                    $params = array('orderBy'=>'startTime',
                                    'singleEvents'=>true,
                                    'timeMax' => date($maxTime::ATOM),
                                    'timeMin' => date($minTime::ATOM));
                    $event_list = $service->events->listEvents('primary', $params);

                    if ($events === NULL) {
                        echo "<h3>You have no events on $day !!</h3>";
                    } else {
                    echo "<ul>";
                        while(true) {
                            foreach ($events->getItems() as $event) {
                                echo "<li> " . $event->getSummary() . " </li>";
                            }
                            $pageToken = $events->getNextPageToken();
                            if ($pageToken) {
                                $optParams = array('pageToken' => $pageToken);
                                $events = $service->events->listEvents('primary', $optParams);
                            } else {
                                break;
                            }
                        }
                    }




                    $funcs = get_class_methods($event_list);

                    echo "<ul>";
                    foreach ($funcs as $method_name) {
                        echo "<li>$method_name</li>";
                    }
                    echo "</ul>";

                    // now perform API call
                    echo "<p>got here!</p>";
                } else {
                    echo "got there";
                    echo $_POST['date'];
            ?>

            <form action='./index.php' method='POST'>
            <table>
              <tr>
                <td>When would you like to meet?</td>
                <td><input type='text' name='date'></td>
              </tr><tr>
                <td>And who else will be there?</td>
                <td><input type='text' name='others'></td>
              </tr><tr>
                <td><input type='submit' name='submit' value='submit' /></td>
              </tr>
            </table>
            </form>

            <?php
                }

                // $events = $service->events->listEvents('primary');
                
                // while(true) {
                //     foreach ($events->getItems() as $event) {
                //         echo $event->getSummary();
                //     }
                //     $pageToken = $events->getNextPageToken();
                //     if ($pageToken) {
                //         $optParams = array('pageToken' => $pageToken);
                //         $events = $service->events->listEvents('primary', $optParams);
                //     } else {
                //         break;
                //     }
                // }



                // $feed = $calendar->getCalendarEventFeed();
                // echo "<ul>\n";
                // foreach ($eventFeed as $event) {
                //     echo "\t<li>" . $event->title->text .  " (" .
                //     $event->id->text . ")\n";
                //     echo "\t\t<ul>\n";
                //     foreach ($event->when as $when) {
                //         echo "\t\t\t<li>Starts: " . $when->startTime .
                //         "</li>\n";
                //     }
                //     echo "\t\t</ul>\n";
                //     echo "\t</li>\n";
                // }
                // echo "</ul>\n";

            ?>

    </body>
</html>
