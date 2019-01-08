<?php

require('models/CRUD_Data.php');

// if user logged in, get data and show the sensorsMetadata page, otherwise direct to login
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE) {
    // if this is a form submission
    if(isset($_POST["Action"])) {
        if($_POST["Action"] === "Edit") {
            if(isset($_POST["Type"]) && isset($_POST["Location"])) {
                if($_POST["Type"] !== "") {
                    editMeta("sensorType", $_POST["Type"], $_POST["Sensor"]);
                }
                if($_POST["Location"] !== "") {
                    editMeta("sensorLocation", $_POST["Location"], $_POST["Sensor"]);
                }
            }
        }
        elseif($_POST["Action"] === "Delete") {
            if(isset($_POST["Dash"])) {
                if($_POST["Dash"] === "Dash") {
                    removeRow($_POST["Sensor"]);
                }
            }
            if(isset($_POST["Historical"])) {
                if($_POST["Historical"] === "All") {
                    echo $_POST["Historical"] . "\n";
                    dropColumn($_POST["Sensor"]);
                }
                else {
                    deleteOld($_POST["range"]);
                }
            }
        }
        else {
            die("Invalid Request");
        }
        // redirect user back to page and end connection
        header('Location: sensorAdmin');
        die();
    }
    // render if not post
    echo $twig->render('sensorMetadata.twig', array('pageHead' => 'Sensor Admin',
                                                    'scripts' => array("assets/js/handleMeta.js"),
                                                    'meta' => fetchRecent()));
}
else {
    header('Location: login');
}

?>