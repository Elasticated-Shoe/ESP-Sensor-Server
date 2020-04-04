<?php
    require("Controllers/AbstractController.php");

    class readMeta extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
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