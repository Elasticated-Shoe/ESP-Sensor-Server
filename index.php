<?php

$config = parse_ini_file("config.ini"); // settings defined in here

if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
    echo "Using HTTPS";
}

?>