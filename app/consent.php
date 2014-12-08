<?php

include('set_environment.php');

// Database crap to test if a user was on the waiting list
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
$user = $row['user_email'];
$length = split(":", $row['request_length']);
$request_date = new DateTime($row['request_date']);
$day = $request_date->format('Y/m/d');

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

            if ($consent == 'yes') {
               
                // Then yank events and insert into DB
                echo "<p>now must insert events into db</p>";

                $minTime = new DateTime($day . " 00:00:00");
                $maxTime = new DateTime($day . " 23:59:59");

                $params = array('orderBy'=>'startTime',
                                'singleEvents'=>true,
                                'timeMax' => $maxTime->format(DateTime::ATOM),
                                'timeMin' => $minTime->format(DateTime::ATOM));
                $event_list = $service->events->listEvents('primary', $params);
                $events = $event_list->getItems();
                get_events($events, $event_list, $day, $email);

                $suggested_start = $request_date->format('Y/m/d H:i:s');
                $duration = new DateInterval('PT' . $length[0] . 'H' . $length[1] . 'M' . $length[2] . 'S');
                $request_date->add($duration);
                $suggested_end = $request_date->format('Y/m/d H:i:s');
                echo "<h2>$user suggested meeting from $suggested_start 'till $suggested_end.</h2>";

                $query = "SELECT * FROM events WHERE (user_email='$email' " .
                                    "AND ((starttime BETWEEN '$suggested_start' AND '$suggested_end') " .
                                    "OR (endtime BETWEEN '$suggested_start' AND '$suggested_end')));";
                $query_events = mysql_query($query);

                // echo "<p>Query was: $query</p>";
                if (mysql_num_rows($query_events) > 0) { 
                    echo "<p>Sorry, you had a conflict with the following event(s):</p>";

                    echo "<ul>";
                    while($row = mysql_fetch_array($query_events)){
                        
                        $conflict = $row['event_name'];
                        $c_start = $row['starttime'];
                        $c_end = $row['endtime'];
                        echo "<li>$conflict ( $c_start - $c_end )</li>";
                    }
                    echo "</ul>";

                    echo "<p>(removing request from queue...)</p>";
                    $query = "DELETE FROM waiting WHERE other_email = '$email';";
                    if (!(mysql_query($query))) {
                        echo "<h2>Couldn't remove request from queue!</h2>";
                    }

                    echo "<p>Would you like to select another time?</p>";

                    ?>

                    <!-- Some silly form -->
                    <form action='waiting.php' method='POST'>
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
                    <input type="hidden" name="day" value="<?php echo $day; ?>">
                    <input type="hidden" name="other" value="<?php echo $user; ?>">
                    </form>
                    
                    <?php

                } else {
                    echo "<p>There were no conflicts.</p>";
                    echo "<p>Would you like to accept the event / add it to your calendar?</p>";

                // After events yanked, logout?
                // I think not; better to make a 'success' page

                ?>
                <!-- Insert stupid simple form here -->
                <form action='success.php' method='POST'>
                    <input type='radio' name='success' value='yes' checked />
                        <label for = 'yes'>yes</label>
                    <input type='radio' name='success' value='no' />
                        <label for = 'no'>no</label>
                    <input type='submit' name='submit' value='submit' />
                    <input type='hidden' name='start' value="<?php echo $suggested_start; ?>">
                    <input type='hidden' name='end' value="<?php echo $suggested_end; ?>">
                </form>

                <?php

                }

            // Otherwise, consent was not granted.
            } else {

                // some stuff
                echo "<h2>Awwww... We'll delete the request. Have a good day!</h2>";
                $query = "UPDATE waiting SET rejected=1 WHERE other_email = '$email';";
                $query_update = mysql_query($query);
 
            }

            if (!mysql_close($dbhandle)) {
                die("Could not successfully close db!");
            }
        }

        ?>


    </body>
</html>

