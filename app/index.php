<?php

require_once '../google-api-php-client/autoload.php';

session_start();

// Initialize the client across stages of authorization; insert data from
// Google developer console
include("../client.php");

$logout_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/logout.php';
$consent_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/consent.php';

// If a user isn't logged in, redirect them to the main page:
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    header('Location: ' . filter_var($logout_url, FILTER_SANITIZE_URL));
}
<<<<<<< HEAD


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
// If a user is being waited on, redirect them to the consent page!
if (!(mysql_num_rows($query_waiting) == 0)) {
    header('Location: ' . filter_var($consent_url, FILTER_SANITIZE_URL));
    // die("query was nonzero");
} 

// else {
//     die($email);
// }

if (mysql_close($dbhandle)) {
    // echo "<p>Database successfully closed~</p>";
}
 

=======
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f
 
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

<<<<<<< HEAD
                // Print the primary calendar title to make sure we're not crazy
                echo "<h3>Primary calendar title: $email</h3>";

                // print_funcs($query_waiting);
                // if ($query_waiting === NULL) {
                //     echo "something";
                // }
=======
                // Important code            
                $service  = new Google_Service_Calendar($client);
                $calendar = $service->calendars->get('primary');
                $email = $calendar->getSummary();

                // Print the primary calendar title to make sure we're not crazy
                echo "<h3>Primary calendar title: $email</h3>";
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f

                // echo "<p>type: " . gettype($someStr) . "; length: " . strlen($someStr) . "</p>";
                // var_dump($_POST);

                if (isset($_POST['submit'])) {
                    echo "<h3>Waiting for other users to respond...</h3>";
                    $day = $_POST['date'];
                    $others = explode(",", $_POST['others']);

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

<<<<<<< HEAD
                    // echo "<p>event_list is of type " . gettype($event_list->getItems()) . "...</p>";
                    // echo "<p>Length of event_list: " . sizeof($event_list->getItems()) . "...</p>";


                    // Connect to the database
                    include("connect.php");

                    $uid = uniqid(true);
                    $query = "INSERT INTO users (user_uid, user_email) " .
                        "VALUES ( '$uid', '$email' )";
                    // Commented out the query for now, to reduce clutter
                    $query_user = mysql_query($query);


                    echo "<ul>";
                    foreach ($others as $other) {
                        // Adds domain to emails that do not contain it.
                        if (!strpos($other, '@')) {
                            $other = $other . "@gmail.com";
                        }
                        $query = "INSERT INTO waiting (user_email, other_email, consent, rejected) " .
                            "VALUES ( '$email', '$other', false, false )";
                        $query_waiting = mysql_query($query);
                        if ($query_waiting) {
                            echo "<li>Other is $other</li>";
                        } else {
                            echo mysql_error();
                        }
                    }
                    echo "</ul>";

=======
                    echo "<p>event_list is of type " . gettype($event_list->getItems()) . "...</p>";
                    echo "<p>Length of event_list: " . sizeof($event_list->getItems()) . "...</p>";
                    include("connect.php");

                    $uid = uniqid(true);
                    $query = "INSERT INTO users (id, email) VALUES ( '$uid', '$email' )";
                    // Commented out the query for now, to reduce clutter
                    // $query_user = mysql_query($query);
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f

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

<<<<<<< HEAD
=======
                    include("debug.php");
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f
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

<<<<<<< HEAD
                                $ev_name = $event->getSummary();
                                $ev_start = new DateTime($event->getStart()->getDateTime());
                                $ev_end = new DateTime($event->getEnd()->getDateTime());
                                $ev_start_str = $ev_start->format('Y-m-d H:i:s');
                                $ev_end_str = $ev_end->format('Y-m-d H:i:s');

                                $query = "INSERT INTO events (user_uid, event_name, starttime, endtime)".
                                         "VALUES ( '$uid', '$ev_name', '$ev_start_str', '$ev_end_str' )";
                                $query_event = mysql_query($query);

                                if (!$query_event) {
                                    echo "<p>failed to insert event $ev_name!!!</p>";
                                }




                                $ev_start_str = $ev_start->format('H:i');
                                $ev_end_str = $ev_end->format('H:i');

                               // Echo relevant event information to the page
                                echo "<li> $ev_name ( $ev_start_str - $ev_end_str )</li>";
=======

                                // $query = "INSERT INTO events (id, name, starttime, endtime)" . 
                                //          "VALUES ( '$uid', '$event_name', '$ev_start', '$ev_end' )";

                                $ev_start = new DateTime($event->getStart()->getDateTime());
                                $ev_end = new DateTime($event->getEnd()->getDateTime());
                                $ev_start_str = $ev_start->format('H:i');
                                $ev_end_str = $ev_end->format('H:i');

                                // Echo relevant event information to the page
                                echo "<li> " . $event->getSummary() . " ( $ev_start_str - $ev_end_str )</li>";
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f
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
<<<<<<< HEAD
                    }


                    // print_funcs($event_list);
                    if (mysql_close($dbhandle)) {
                        // echo "<p>Database successfully closed~</p>";
                    }
                    
                    echo "<h3>Please choose a time to meet up!</h3>";
                    ?>

            <!-- temporarily redirects to logout!!! -->
            <form action='./logout.php' method='POST'>
            <table>
              <tr>
                <td>What time would you like to meet?</td>
                <td><input type='text' name='time'></td>
              </tr><tr>
                <td>How long are yee meeting far?</td>
                <td><input type='text' name='length'></td>
              </tr><tr>
                <td><input type='submit' name='submit' value='submit' /></td>
              </tr>
            </table>
            </form>

            <?php
=======

                    }


                    if (mysql_close($dbhandle)) {
                        echo "<p>Database successfully closed~</p>";
                    }
                    echo "<p>Please choose a time to meet up!</p>";

                    // print_funcs($event_list);
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f

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
