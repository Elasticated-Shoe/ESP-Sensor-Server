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
            // password_hash($pepperedPostedPass, PASSWORD_BCRYPT, ["cost" => $config["approximateHashTime"]]);
            // Create connection
            $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["usersDatabase"]);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // read password from database
            // https://stackoverflow.com/questions/24716560/do-i-need-to-escape-my-variables-if-i-use-mysqli-prepared-statements
            $sql = $conn->prepare("SELECT passwordHash FROM login.users WHERE username = ?"); // prepared statements / parameterized queries are sufficient to prevent 1st order injection on that statement. If you use un-checked dynamic sql anywhere else in your application you are still vulnerable to 2nd order injection.
            $sql->bind_param("s", $theUser);

            $theUser = $_POST['user'];
            $result = $sql->execute();
            if (!$sql->errno) {
                // Handle error here
            }
            $sql->bind_result($passwordHash);
            // map hashes in results to array
            $userHashes = array();
            while ($sql->fetch()) {
                $userHashes[] = $passwordHash;
            }
            // check size of array, as usernames are unique there should only be one, otherwise their is a problem
            if(sizeof($userHashes) === 1) {
                if(password_verify($pepperedPostedPass, $userHashes[0])) {
                    echo "\nCorrect Password!\n";
                }
                else {
                    // need store failed attempts
                    http_response_code(403); // Return forbidden as password incorrect
                    die();
                }
            }
            else {
                // user not found / count incorrect
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
        // Should block this with apache and seperate out this file into seperate scripts?
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