<?php
    require("protected/Database/Database.php");

    class insertArchive {
        function init() {
            $writeHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $writeHandle->connect();

            $query = "INSERT INTO sensorData(sensorId, sensorDatetime, sensorValue) VALUES (?, ?, ?)";

            $params = array_merge(
                array("iss"),
                array($_POST["sensorId"], $_POST["sensorDatetime"], $_POST["sensorValue"])
            );

            return array(
                "Inserted" => 1
            );
        }
    }
?>