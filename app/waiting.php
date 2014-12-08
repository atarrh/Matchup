<?php

include('set_environment.php');

if (isset($_POST['submit'])) {


    $time = $_POST['time'];
    $length = $_POST['length'];
    $day = $_POST['day'];
    $date = $day . " " . $time;
    $others = explode(",", $_POST['other']);

    include("connect.php");

    echo "<ul>";
    foreach ($others as $other) {

        // echo "<li>Inserting $other into the shindig</li>";
        $query = "INSERT INTO waiting (user_email, other_email, request_date, request_length, accepted, rejected) " .
                "VALUES ( '$email', '$other', '$date', '$length', false, false )";
        $query_insert = mysql_query($query);

        if (!$query_insert) {
            // die("Could not insert waiting event!");
            die(mysql_error());
        }
    }
    echo "</ul>";
    // $query = "SELECT * FROM waiting WHERE other_email='$email';";
    // $query1 = "UPDATE waiting SET request_date='$date' WHERE user_email='$email';";
    // $query2 = "UPDATE waiting SET request_length='$length' WHERE user_email='$email';";
    // $query_db1 = mysql_query($query1);
    // $query_db2 = mysql_query($query2);
    // if (!($query_db1 and $query_db2)) {
    //     die(mysql_error());
    // }

    if (!mysql_close($dbhandle)) {
        die("Could not successfully close db!");    
    }

    // WRONG: Now need to connect to DB to see if time is free...
    // don't need to check if time is free? Wait for response from other user?

    echo "<h2>Your request(s) have been submitted!</h2>";
    echo "<h3>Please <a href= $logout_url >log out</a> and wait for other users to respond.....</h3>";




} else {


}




?>
