<?php

include('set_environment.php');

if (!isset($_POST['submit'])) {
    echo "<h2>Whatchu doin here boy giddout</h2>";
} else {
    $success = $_POST['success'];
    include("connect.php");

    
    if ($success == 'yes') {
        echo "<h2>Mission accomplished! Adding the new event to your calendar...</h2>";

        $event_starttime = new Google_Service_Calendar_EventDateTime();
        $event_endtime = new Google_Service_Calendar_EventDateTime();

        $starttime = new DateTime($_POST['start']);
        $endtime = new DateTime($_POST['end']);
        $starttimestr = $starttime->format("Y-m-d\TH:i:s") . ".000-05:00";
        $endtimestr = $endtime->format("Y-m-d\TH:i:s") . ".000-05:00";

        $event_starttime->setDateTime($starttimestr);
        $event_endtime->setDateTime($endtimestr);

        echo "<h3>Event starts at $starttimestr and ends at $endtimestr</h3>";
        $event = new Google_Service_Calendar_Event();
        $event->setSummary('Matchup Meeting!');
        $event->setStart($event_starttime);
        $event->setEnd($event_endtime);
        $createdEvent = $service->events->insert('primary', $event);

        echo $createdEvent->getId();

        $query = "UPDATE waiting SET accepted=1 WHERE other_email = '$email';";
        $query_update = mysql_query($query);


    } else {
        echo "<h2>Awwww... We'll delete the request. Have a good day!</h2>";
        $query = "UPDATE waiting SET rejected=1 WHERE other_email = '$email';";
        $query_update = mysql_query($query);

    }


    if(!mysql_close($dbhandle)) {
        die("could not successfully close db!");
    }
}
?>


