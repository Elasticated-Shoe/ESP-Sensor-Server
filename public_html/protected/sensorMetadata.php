<?php

require('models/CRUD_Data.php');

// of user logged in, get data and show the sensorsMetadata page, otherwise direct to login
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE) {
    echo $twig->render('sensorMetadata.twig', array('pageHead' => 'Sensor Admin',
                                                    'meta' => fetchMeta()));
}
else {
    header('Location: login');
}

?>