<?php

require('models/verifyPassword.php');
require('models/failedLoginCooldown.php');

// should check if another user is already logged in?
if($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    header('Location: sensorAdmin');
}
elseif($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $twig->render('login.twig', array('pageHead' => 'Login'));
}
else {
    http_response_code(405); // Return method not allowed if not POST or GET
    die("Method Not Allowed");
}