<?php
// https://stackoverflow.com/questions/22221807/session-cookies-http-secure-flag-how-do-you-set-these
// **PREVENTING SESSION HIJACKING** Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);
// **PREVENTING SESSION FIXATION** Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);
// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', 1);
// cookie expire at the end of browser session
ini_set('session.cookie_lifetime', 0);
// cookie only be sent on subdomain
// ini_set("session.cookie_domain", "www.example.com");
// call the above before session_start
session_start();
// if browser session has been inactive 30 minutes, delete the session
// https://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
if(isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > 1800)) {
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    session_start();
}
// if session is new log its creation time
if(!isset($_SESSION['SessionStart'])) {
    $_SESSION['SessionStart'] = time();
}
// if a browser session has remained active, but is older than 12 hours, delete it
if(time() - $_SESSION['SessionStart'] > 43200) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['SessionStart'] = time();
}
$_SESSION['lastActivity'] = time(); // update last activity time stamp for the session

$GLOBALS["config"] =  parse_ini_file(__DIR__ . "/protected/config.ini"); // settings defined in here
$url = $config["proxy"];
require_once '../framework/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('protected/views');
$twig = new Twig_Environment($loader, array(
    'cache' => '../framework/twigCache',
));
// $_SERVER['REDIRECT_URL'] not available on some hosting
$request = strtok($_SERVER["REQUEST_URI"],'?');
// poor router but I won't be using more than a handful of routes
switch ($request) {
    case $url . '/':
        echo $twig->render('dashboard.twig', array( 'pageHead' => 'Dashboard',
                                                    'scripts' => array("assets/js/displayRecent.js",
                                                                        "assets/js/generateGraph.js"),
                                                    'templates' => array("partial/recentReadings.twig"),
                                                    'currentPage' => "Home",
                                                    ));
        break;
    case $url . '/sensorAPI':
        require('protected/dataAPI.php');
        break;
    case $url . '/login':
        require('protected/handleLogin.php');
        break;
    case $url . '/sensorAdmin':
        require('protected/sensorMetadata.php');
        break;
    default:
        echo $twig->render('404.twig', array(   'pageHead' => '404 Page Not Found'));
        break;
}
?>