<?php

$config = parse_ini_file("config.ini"); // settings defined in here

// If POST request, check SSL, confirm login details and insert if okay
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
        echo "POST Using HTTPS";
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