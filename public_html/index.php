<?php

$GLOBALS["config"] =  parse_ini_file("../framework/config.ini"); // settings defined in here
include('../framework/verifyPassword.php');
include('../framework/failedLoginCooldown.php');

// If POST request, check SSL, confirm login details and insert if okay
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
        // validate provided password
        if(!validatePassword($_POST['password'])) {
            echo "User Password In Invalid Format\n";
            http_response_code(403); // Return forbidden as supplied password is not a string or too long
            die();
        }
        echo "User Password Valid\n";
        // check user has not exceeded failed login limit
        if(!checkUserAttempts()) {
            echo "User Banned\n";
            http_response_code(403); // as user Banned
            die();
        }
        echo "User Is Not Banned\n";
        // hash provided password and check with hash from database, for that user
        if(!verifyPassword($_POST['password'], $_POST['user'])) {
            echo "User Password Incorrect\n";
            incrementFailedCount();
            http_response_code(403); // Return forbidden as password incorrect
            die();
        }
        echo "Password Correct\n";
        //echo json_decode($_POST["data"], TRUE);
        $jsonString = $_POST["bacon"];
        echo "\n";
        echo print_r(json_decode($jsonString, TRUE)) . "\n";
        foreach($_POST["data"] as $key => $value) {
            echo $key . "\n";
            echo $value . "\n\n";
        }
    }
    else { // Should block http with apache and have this post stuff in seperate file?
        http_response_code(404);
        die();
    }
}
// If GET request, serve the page
elseif($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "GET";
}
else {
    http_response_code(405); // Return method not allowed if not POST or GET
    die();
}

?>