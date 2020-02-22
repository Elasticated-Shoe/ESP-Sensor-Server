<?php
    require("protected/Database/Database.php");

    class readEvents {
        function init() {
            $readHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $readHandle->connect();

            $query = "SELECT * FROM eventLog WHERE eventId = ?";

            $columnsArray = array(
                "eventId", "eventName", "eventSensor", "eventTime", "eventOngoing", 
                "eventDesc", "userInformed", "userAck"
            );

            $params = array_merge(
                array("s"),
                array($_GET["owner"]),
            );

            return $readHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>