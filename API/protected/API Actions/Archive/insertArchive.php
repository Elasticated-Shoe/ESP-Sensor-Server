<?php
    require_once("protected/API Actions/baseAction.php");

    class insertArchive extends baseAction {
        function init() {
            $query = "INSERT INTO sensorData(sensorId, sensorDatetime, sensorValue) VALUES (?, ?, ?)";

            $params = array_merge(
                array("iss"),
                array($_POST["sensorId"], $_POST["sensorDatetime"], $_POST["sensorValue"])
            );

            $this->dbHandle->runParameterizedQuery($query, null, $params);

            return array(
                "Inserted" => 1
            );
        }
    }
?>