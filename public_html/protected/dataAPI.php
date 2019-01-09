<?php

require('models/verifyPassword.php');
require('models/failedLoginCooldown.php');
require('models/CRUD_Data.php');

// If POST request, check SSL, confirm login details and insert if okay
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
        if(!isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== TRUE) {
            // validate provided password // should check if another user is already logged in?
            if(!validatePassword($_POST['password'])) {
                http_response_code(400); // Return forbidden as supplied password is not a string or too long
                die("Password Provided In Invalid Format");
            }
            // check user has not exceeded failed login limit
            if(!checkUserAttempts()) {
                http_response_code(429); // as user Banned
                die("Too Many Failed Logins, Try Again Later");
            }
            // hash provided password and check with hash from database, for that user
            if(!verifyPassword($_POST['password'], $_POST['user'])) {
                incrementFailedCount();
                http_response_code(403); // Return forbidden as password incorrect
                die("User Password Incorrect");
            }
        }
        if(!isset($_POST["data"])) {
            http_response_code(400); // logged in, but did not send a payload to insert in post
            die("Missing Required Data");
        }
        // at this point user either logged in or its the same session
        // sensor data is sent as JSON string, I was having trouble sending it as JSON
        $_POST["data"] = json_decode($_POST["data"], TRUE);
        // insert data
        insertData();
    }
    else { // Should block http with apache and have this post stuff in seperate file?
        http_response_code(404);
        die("Using HTTP, Must Be Using HTTPS");
    }
}
// If GET request, serve the page
elseif($_SERVER['REQUEST_METHOD'] === 'GET') {
    // see https://stackoverflow.com/questions/2279316/beginner-data-caching-in-php // cache should be in the fetch recent funciton
    if(isset($_GET['sensorsMetadata'])) {

    }
    if($_GET['timePeriod'] === "Current") {
        echo json_encode(fetchRecent());
    }
    elseif($_GET['timePeriod'] === "Past") {
        if(isset($_GET["Start"]) && isset($_GET["End"])) {
            echo json_encode(fetchRange($_GET["Sensors"], $_GET["Start"], $_GET["End"]));
        } else {
            echo json_encode(fetchRange($_GET["Sensors"]));
        }
    }
    else {
        echo "Invalid Query String";
    }
}
else {
    http_response_code(405); // Return method not allowed if not POST or GET
    die("Method Not Allowed");
}
die();

?>