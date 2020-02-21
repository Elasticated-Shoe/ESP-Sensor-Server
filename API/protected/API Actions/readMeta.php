<?php
    require("protected/Database/Database.php");

    class readMeta {
        function init() {
            $readHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $readHandle->connect();

            $query = "SELECT * FROM sensorMetadata WHERE sensorOwner = ?";

            $columnsArray = array(
                "sensorId", "sensorName", "sensorOwner", "displayName", "lastValue", "sensorType", "sensorUnits", 
                "sensorLocation", "sensorVersion", "lastSeen"
            );

            $params = array_merge(
                array("s"),
                array($_GET["owner"]),
            );

            return $readHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>