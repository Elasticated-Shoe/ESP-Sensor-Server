<?php
    require("Controllers/AbstractController.php");

    class readEvents extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
            $query = "SELECT * FROM eventLog WHERE eventId = ?";

            $columnsArray = array(
                "eventId", "eventName", "eventSensor", "eventTime", "eventOngoing", 
                "eventDesc", "userInformed", "userAck"
            );

            $params = array_merge(
                array("s"),
                array($_GET["owner"]),
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>