<?php

$configFile = "config.ini";
$config = parse_ini_file($configFile); // settings defined in here
$postedPass = "2Jm466!6";
$pepperedPostedPass = $config["pepper"] . $postedPass;
echo password_hash($pepperedPostedPass, PASSWORD_BCRYPT, ["cost" => $config["approximateHashTime"]]);

?>