<?php

function benchmarkServerHasher() {
    global $config;
    // password hashing benchmark taken from http://www.php.net/manual/en/function.password-hash.php
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

function validatePassword($password) {
    if(gettype($password) !== "string" || strlen($password) > 50) {
        return FALSE;
    }
    return TRUE;
}

function verifyPassword($postedPass, $postedUser) {
    global $config;
    // password_hash($pepperedPostedPass, PASSWORD_BCRYPT, ["cost" => $config["approximateHashTime"]]);
    // connect to database
    $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
    // pepper user password with pepper from server config file
    $pepperedPostedPass = $config["pepper"] . $postedPass;
    // https://stackoverflow.com/questions/24716560/do-i-need-to-escape-my-variables-if-i-use-mysqli-prepared-statements
    // prepared statements / parameterized queries are sufficient to prevent 1st order injection on that statement. 
    // If you use un-checked dynamic sql anywhere else in your application you are still vulnerable to 2nd order injection.
    // get users hash from the database
    $sql = $conn->prepare("SELECT passwordHash FROM users WHERE username = ?");
    $sql->bind_param("s", $postedUser);
    $sql->execute();
    $sql->bind_result($passwordHash);
    // map hashes in results to array
    $userHashes = array();
    while ($sql->fetch()) {
        $userHashes[] = $passwordHash;
    }
    // close connections
    $sql->close();
    $conn->close();
    if(password_verify($pepperedPostedPass, $userHashes[0])) {
        $_SESSION["loggedIn"] = TRUE;
        return TRUE;
    }
    return FALSE;

}

?>