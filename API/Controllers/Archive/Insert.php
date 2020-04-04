<?php
    require("Controllers/AbstractController.php");

    class insertArchive extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
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