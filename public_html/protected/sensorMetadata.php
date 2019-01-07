<?php

require('models/CRUD_Data.php');

// of user logged in, get data and show the sensorsMetadata page, otherwise direct to login
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE) {
    if(isset($_GET["Action"])) {
        echo "what is this and why is it 1";
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