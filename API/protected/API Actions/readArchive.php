<?php
    require("protected/Database/Database.php");

    class readArchive {
        function init() {
            $readHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $readHandle->connect();

            $selectedArray = $_GET["sensors"];
            $startDate = $_GET["start"];
            $endDate = $_GET["end"];

            $query = "SELECT * FROM sensorData WHERE (SelectedSensors) AND sensorDatetime > ? AND sensorDatetime < ?";
            $selectedParamString = str_repeat('sensorId = ? OR ', count($selectedArray));
            $selectedParamString = substr($selectedParamString, 0, -4); // remove last ' OR '
            $query = str_replace("SelectedSensors", $selectedParamString, $query);

            $columnsArray = array("sensorId", "sensorDatetime", "sensorValue");

            $params = array_merge(
                array( str_repeat('i', count( $selectedArray )) . "ss"),
                $selectedArray,
                array($startDate, $endDate)
            );

            return $readHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>