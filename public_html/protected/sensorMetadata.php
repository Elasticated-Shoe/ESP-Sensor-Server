<?php

require('models/CRUD_Data.php');

// of user logged in, get data and show the sensorsMetadata page, otherwise direct to login
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE) {
    if(isset($_GET["Action"])) {
        if($_GET["Action"] === "Edit") {
            echo "editing " . $_GET["Sensor"];
        }
        elseif($_GET["Action"] === "Delete") {
            removeRow($_GET["Sensor"]);
            if(isset($_GET["Historical"])) {
                if($_GET["Historical"] === "All") {
                    // drop column
                }
                else {
                    // remove records where date is earlier than provided
                }
            }
            echo true;
        }
        else {
            echo "Invalid Request";
        }
        die();
    }
    echo $twig->render('sensorMetadata.twig', array('pageHead' => 'Sensor Admin',
                                                    'scripts' => array("assets/js/handleMeta.js"),
                                                    'meta' => fetchMeta()));
}
else {
    header('Location: login');
}

?>