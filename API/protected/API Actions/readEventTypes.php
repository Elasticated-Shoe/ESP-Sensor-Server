<?php
    require("protected/Database/Database.php");

    class readEventTypes {
        function init() {
            $readHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $readHandle->connect();

            $query = "SELECT * FROM eventTypes WHERE eventOwner = ?";

            $columnsArray = array(
                "eventId", "eventOwner", "eventName", "eventSensor", "eventAction", "eventData"
            );

            $params = array_merge(
                array("s"),
                array($_GET["owner"]),
            );

            return $readHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>