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
 
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Muh Matchup app</title>

        <!-- Commented out fancy date selector, because why bother -->
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
                $email = $calendar->getSummary();

                // Print the primary calendar title to make sure we're not crazy
                echo "<h3>Primary calendar title: $email</h3>";

                // echo "<p>type: " . gettype($someStr) . "; length: " . strlen($someStr) . "</p>";
                // var_dump($_POST);

                if (isset($_POST['submit'])) {
                    echo "<h3>Waiting for other users to respond...</h3>";
                    $day = $_POST['date'];
                    $others = $_POST['others'];

                    date_default_timezone_set('America/New_York');

                    $minTime = new DateTime($day . " 00:00:00");
                    $maxTime = new DateTime($day . " 23:59:59");

                    // $minTimeString = $minTime->format(DateTime::ATOM);
                    // $maxTimeString = $maxTime->format(DateTime::ATOM);

                    // These are bad! date() returns current date/time
                    // $test1 = date($minTime::ATOM);
                    // $test2 = date($maxTime::ATOM);
                    // $test = $minTime->format('Y-m-d H:i:s');
                    // echo "<p>$minTimeString</p>";
                    // echo "<p>$maxTimeString</p>";

                    // Now perform API call
                    $params = array('orderBy'=>'startTime',
                                    'singleEvents'=>true,
                                    'timeMax' => $maxTime->format(DateTime::ATOM),
                                    'timeMin' => $minTime->format(DateTime::ATOM));
                    $event_list = $service->events->listEvents('primary', $params);

                    echo "<p>event_list is of type " . gettype($event_list->getItems()) . "...</p>";
                    echo "<p>Length of event_list: " . sizeof($event_list->getItems()) . "...</p>";
                    include("connect.php");

                    $uid = uniqid(true);
                    $query = "INSERT INTO users (id, email) VALUES ( '$uid', '$email' )";
                    // Commented out the query for now, to reduce clutter
                    // $query_user = mysql_query($query);

                    // Debugging query strings - turns out you need quotes
                    // around your variables....
                    // if ($query_user) {
                    //     echo "<p>Successfully inserted into db</p>";
                    //     echo "<p>On query $query</p>";
                    // } else {
                    //     $fart = mysql_error();
                    //     echo "<p>Couldn't insert into db</p>";
                    //     echo "<p>Error: $fart</p>";
                    //     echo "<p>on query: $query</p>";
                    //     echo "<p>uid is $uid</p>";
                    //     echo "<p>un is $email</p>";
                    // }

                    include("debug.php");
                    $events = $event_list->getItems();
                    if (sizeof($events) === 0) {
                        echo "<h3>You have no events on $day !!</h3>";
                    } else {

                        echo "<h3>Your events on $day are:</h3>";
                        echo "<ul>";

                        // Why am I looping? I don't know!
                        while(true) {
                            foreach ($event_list->getItems() as $event) {
                                // print_funcs($event->getStart());

                                // Note to self: do not use getDate(). This
                                // returns null, or some such.
                                //$ev_start = new DateTime($event->getStart()->getDate());


                                // $query = "INSERT INTO events (id, name, starttime, endtime)" . 
                                //          "VALUES ( '$uid', '$event_name', '$ev_start', '$ev_end' )";

                                $ev_start = new DateTime($event->getStart()->getDateTime());
                                $ev_end = new DateTime($event->getEnd()->getDateTime());
                                $ev_start_str = $ev_start->format('H:i');
                                $ev_end_str = $ev_end->format('H:i');

                                // Echo relevant event information to the page
                                echo "<li> " . $event->getSummary() . " ( $ev_start_str - $ev_end_str )</li>";
                            }
                            
                            // No idea what this stuff does
                            $pageToken = $event_list->getNextPageToken();
                            if ($pageToken) {
                                $optParams = array('pageToken' => $pageToken);
                                $event_list = $service->events->listEvents('primary', $optParams);
                            } else {
                                break;
                            }
                        }
                        echo "</ul>";

                    }


                    if (mysql_close($dbhandle)) {
                        echo "<p>Database successfully closed~</p>";
                    }
                    echo "<p>Please choose a time to meet up!</p>";

                    // print_funcs($event_list);

                } else {
                    // Silly hack; use mixed blocks of php and html.
                    // Ugly as all hell, but it's working...
            ?>

            <!-- if user did not arrive at index.php via a POST request:
                 display the following form, to allow him/her to choose
                 a day to meet. -->
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

            <?php } ?>

    </body>
</html>
