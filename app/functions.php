<?php

function print_funcs($obj)
{
    $funcs = get_class_methods($obj);
    $objtype = gettype($obj);

    echo "<p>Below are the functions for an object of type $objtype</p>";    
    echo "<ul>";
    foreach ($funcs as $method_name) {
        echo "<li>$method_name</li>";
    }
    echo "</ul>";
}

function clear_db()
{
    include('connect.php');
    if (mysql_query("DELETE FROM users;") and
        mysql_query("DELETE FROM waiting;") and
        mysql_query("DELETE FROM events;")) {
        echo "<h2>All tables cleared!</h2>";
    }
    mysql_close($dbhandle);
}

function get_events($events, $event_list, $day, $email)
{
    if (sizeof($events) === 0) {
        echo "<h3>You have no events on $day !!</h3>";
    } else {

        echo "<h3>Your events on $day are:</h3>";
        echo "<ul>";

        // Why am I looping? I don't know!
        while(true) {
            foreach ($events as $event) {
                // print_funcs($event->getStart());

                // Note to self: do not use getDate(). This
                // returns null, or some such.
                //$ev_start = new DateTime($event->getStart()->getDate());

                $ev_name = $event->getSummary();
                $ev_start = new DateTime($event->getStart()->getDateTime());
                $ev_end = new DateTime($event->getEnd()->getDateTime());
                $ev_start_str = $ev_start->format('Y-m-d H:i:s');
                $ev_end_str = $ev_end->format('Y-m-d H:i:s');

                $query = "INSERT INTO events (user_email, event_name, starttime, endtime)".
                         "VALUES ( '$email', '$ev_name', '$ev_start_str', '$ev_end_str' )";
                $query_event = mysql_query($query);

                if (!$query_event) {
                    echo "<p>failed to insert event $ev_name!!!</p>";
                    echo mysql_error();
                }


                $ev_start_str = $ev_start->format('H:i');
                $ev_end_str = $ev_end->format('H:i');

               // Echo relevant event information to the page
                echo "<li> $ev_name ( $ev_start_str - $ev_end_str )</li>";

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
}


?>
