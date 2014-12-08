<?php

session_start();

if (session_destroy()) {
    $logged_out = true;
}

$redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/'

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Muh Logout Page</title>
    </head>
    <body>

        <?php
        if ($logged_out) {
            // include('connect.php');
            // if (mysql_query("DELETE FROM users;") and
            //     mysql_query("DELETE FROM waiting;") and
            //     mysql_query("DELETE FROM events;")) {
            //     echo "<h2>All tables cleared!</h2>";
            // }
            // mysql_close($dbhandle);

            echo "<h1>You have been successfully logged out!</h1>";
            header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));

            // echo "<p>Redirecting you to: " . $redirect_url . "</p>";
        } else {
            echo "<h1>We were unable to log you out.... sorry</h1>";
        }
        ?>

    </body>
</html>

