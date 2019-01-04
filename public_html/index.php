<?php

$GLOBALS["config"] =  parse_ini_file(__DIR__ . "/../framework/config.ini"); // settings defined in here
$url = $config["proxy"];
require_once '../framework/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('protected/views');
$twig = new Twig_Environment($loader, array(
    'cache' => '../framework/twigCache',
)); // echo $twig->render('index.html', array('name' => 'Fabien'));
$request = $_SERVER['REDIRECT_URL'];
switch ($request) {
    case '':
        echo $twig->render('dashboard.twig', array( 'pageHead' => 'Dashboard',
                                                    'scripts' => array("assets/js/displayRecent.js"),
                                                    'templates' => array("partial/recentReadings.twig"),
                                                    ));
        break;
    case $url . '/sensorAPI':
        require('protected/dataAPI.php');
        break;
    default:
        echo $twig->render('404.twig', array(   'pageHead' => '404 Page Not Found'));
        break;
}
?>