<?php
    require_once("protected/API Actions/baseAction.php");

    class readMeta extends baseAction {
        function init() {
            $query = "SELECT * FROM sensorMetadata WHERE sensorOwner = ?";

            $columnsArray = array(
                "sensorId", "sensorName", "sensorOwner", "displayName", "lastValue", "sensorType", "sensorUnits", 
                "sensorLocation", "sensorVersion", "lastSeen"
            );

            $params = array_merge(
                array("s"),
                array($_GET["owner"]),
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>