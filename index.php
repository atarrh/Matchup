<?php

// Require the google php client code to make our lives easier
require_once 'google-api-php-client-master/autoload.php';

// Start a PHP session to allow storing of variables in _SESSION array;
// required for persistent logins
session_start();

// Initialize the client across stages of authorization; insert data from
// Google developer console
include("client.php");

$redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/index.php';

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
            // If a URL contains the 'code' variable, proceed to authenticate
            // and redirect to our app
            if (isset($_GET['code'])) {
                $client->authenticate($_GET['code']);
                $_SESSION['access_token'] = $client->getAccessToken();
                $token = $_SESSION['access_token'];
                echo "<h2>redirectin'</h2>";

                // Redirect a user that returned from Google OAuth to our
                // application
                header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));

                // Garbage code; used for testing
                // $redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/~atarrh/Matchup/app/';
                // $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                // echo "<h3>$redirect</h3>";
                // echo "<h4>Access token: $token</h4>";

            // If a user is already logged in, redirect them
            } else if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

                header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));

            // Otherwise: begin the processs of OAuth authentication.
            } else {
                $authUrl = $client->createAuthUrl();
                echo "<a href= $authUrl >Click here to login to google</a>";
                
                $token = $_SESSION['access_token'];
                echo "<p>$token</p>";
            }
            
            ?>
        
    </body>
</html>


