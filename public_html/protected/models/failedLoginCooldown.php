<?php

function getUserAttempts() {
    global $config;
    $theUser = $_POST['user'];
    // Create connection
    $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["usersDatabase"]);
    // Check failed logins and times
    $loginAttempts = $conn->prepare("SELECT username, lastFailedLogin, failedLoginsInWindow FROM login.users 
                                    WHERE username = ?");
    $loginAttempts->bind_param("s", $theUser);
    $loginAttempts->execute();
    $loginAttempts->bind_result($username, $lastFailedLogin, $failedLoginsInWindow);
    // map times to array
    while ($loginAttempts->fetch()) {
        $loginAttemptsResult[$username]["lastFailedLogin"] = $lastFailedLogin;
        $loginAttemptsResult[$username]["failedLoginsInWindow"] = $failedLoginsInWindow;
    }
    // close connections
    $loginAttempts->close();
    $conn->close();
    // check size of array, as usernames are unique there should only be one, otherwise their is a problem
    if(sizeof($loginAttemptsResult) !== 1) {
        http_response_code(403); // user not found / count incorrect
        die("User Not Found");
    }
    return $loginAttemptsResult;
}

function checkUserAttempts() {
    global $config;
    $theUser = $_POST['user'];
    // get failed login attempts from database
    $loginAttemptsResult = getUserAttempts();
    // unix timestamp to int
    $unixTimestamp = time();
    $unixTimestamp = (int)$unixTimestamp;
    // for ease of typing
    $failedCount = $loginAttemptsResult[$theUser]["failedLoginsInWindow"];
    $failedTimestamp = $loginAttemptsResult[$theUser]["lastFailedLogin"];
    // if there are failed login attempts...
    if($failedCount !== null) {
        if($failedCount > 5) {
            // if login attempts younger than timeout then respond 403
            if($unixTimestamp - $failedTimestamp < $config["banTime"]) {
                return FALSE;
            }
            // otherwise the failed login attempts are old, they need to be updated
            $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["usersDatabase"]);
            $loginUnblock = $conn->prepare('UPDATE login.users SET lastFailedLogin = ?, 
                                                                    failedLoginsInWindow = ?
                                                                WHERE username = ?');
            $loginUnblock->bind_param("sss", $SQLnull, $SQLnull, $theUser);
            $SQLnull = NULL;
            $loginUnblock->execute();
            // close connections
            $loginUnblock->close();
            $conn->close();
        }
    }
    return TRUE;
}
function incrementFailedCount() {
    global $config;
    $theUser = $_POST['user'];
    // get failed login attempts from the database
    $loginAttemptsResult = getUserAttempts();
    $failedCount = $loginAttemptsResult[$theUser]["failedLoginsInWindow"];
    // get time
    $unixTimestamp = time();
    $unixTimestamp = (int)$unixTimestamp;
    // if no failed attempts its null so set it to 1, otherwise increment it
    if($failedCount === null) {
        $failedCount = 1;
    }
    else {
        $failedCount = (int)$failedCount + 1;
    }
    // connect to database
    $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["usersDatabase"]);
    // update failed login attempts
    $loginIncrementFailed = $conn->prepare("UPDATE login.users SET lastFailedLogin = ?, 
                                                                failedLoginsInWindow = ?
                                                            WHERE username = ?");
    $loginIncrementFailed->bind_param("iis", $unixTimestamp, $failedCount, $theUser);
    $loginIncrementFailed->execute();
    // close connections
    $loginIncrementFailed->close();
    $conn->close();
}

?>