<?php

$configFile = "config.ini";
$config = parse_ini_file($configFile); // settings defined in here
if(!array_key_exists('approximateHashTime', $config)) {
    // password hashing benchmark taken from http://www.php.net/manual/en/function.password-hash.php
    echo "Generating Hash Benchmark";
    $timeTarget = 1; // 1000 milliseconds
    $cost = 5;
    do {
        $cost++;
        $start = microtime(true);
        password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
        $end = microtime(true);
    } while (($end - $start) < $timeTarget);
    $config["approximateHashTime"] = $cost;
    // write new config file
    $newConfig = "";
    foreach($config as $key => $value) {
        $newConfig .= $key . " = '" . $value . "'\n";
    }
    file_put_contents($configFile, $newConfig);
}
// If POST request, check SSL, confirm login details and insert if okay
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
        $postedPass = $_POST['password'];
        if(gettype($postedPass) === "string" && strlen($postedPass) < 51) {
            $pepperedPostedPass = $config["pepper"] . $postedPass;
            password_hash($pepperedPostedPass, PASSWORD_BCRYPT, ["cost" => $config["approximateHashTime"]]);
            // Create connection
            $conn = new mysqli($config["servername"], config["username"], config["password"], config["usersDatabase"]);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // read password from database




            if(password_verify($pepperedPostedPass, $hashed_password)) {
                
            }
            else {
                http_response_code(403); // Return forbidden as password incorrect
                die();
            }
        }
        else {
            http_response_code(403); // Return forbidden as supplied password is not a string or too long
            die();
        }
    }
    else {
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