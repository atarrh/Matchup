<?php

include('set_environment.php');

// Database crap to test if a user was on the waiting list
include("connect.php");
$query = "SELECT * FROM waiting WHERE other_email = '$email';";
$query_waiting = mysql_query($query);

// Was testing to see how to test if value exists in table
// If a user is being waited on, redirect them to the consent page!
if (!(mysql_num_rows($query_waiting) == 0)) {
    header('Location: ' . filter_var($consent_url, FILTER_SANITIZE_URL));
} 

$query = "SELECT * FROM waiting WHERE user_email = '$email';";
$query_waiting = mysql_query($query);
if(!(mysql_num_rows($query_waiting) == 0)) {
    $row = mysql_fetch_array($query_waiting);
    $acceptance = $row['accepted'];

    if ($acceptance) {

        $result = get_data($row);

        $suggested_start = $result['suggested_start'];
        $suggested_end = $result['suggested_end'];

        echo "<h2>Your friend has accepted your Matchup Request!</h2>";
        echo "<h3>Please select below if you'd like to add an event to your calendar!</h3>";

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

        exit();

    } 
}


if (!mysql_close($dbhandle)) {
    die("Database could not be successfully closed");
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

                // Print the primary calendar title to make sure we're not crazy
                echo "<h3>Primary calendar title: $email</h3>";

                if (isset($_POST['submit'])) {
                    echo "<h3>Waiting for other users to respond...</h3>";
                    $day = $_POST['date'];
                    $others = explode(",", $_POST['others']);

                    date_default_timezone_set('America/New_York');

                    $minTime = new DateTime($day . " 00:00:00");
                    $maxTime = new DateTime($day . " 23:59:59");

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


                    // Connect to the database
                    include("connect.php");

                    $uid = uniqid(true);
                    $query = "INSERT INTO users (user_uid, user_email) " .
                        "VALUES ( '$uid', '$email' )";
                    // Commented out the query for now, to reduce clutter
                    $query_user = mysql_query($query);

                    // Handling inserting into waiting table
                    echo "<ul>";
                    for ($i = 0; $i < count($others); $i++) {
                        // Adds domain to emails that do not contain it.
                        $other = $others[$i];
                        if (!strpos($other, '@')) {
                            $others[$i] = $other . "@gmail.com";
                        }

                        echo "<li>Collaborator: $other</li>";

                    }
                    echo "</ul>";
                    $others = implode(",", $others);


                    $events = $event_list->getItems();

                    get_events($events, $event_list, $day, $email);

                    // print_funcs($event_list);
                    if (mysql_close($dbhandle)) {
                        // echo "<p>Database successfully closed~</p>";
                    }
                    
                    echo "<h3>Please choose a time to meet up!</h3>";
                    echo "<p>(on $day with $others)</p>";

                    ?>

            <!-- should it go to waiting? ==> yes! -->
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
            <input type="hidden" name="other" value="<?php echo $others; ?>">
            </form>

            <?php

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
