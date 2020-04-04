<?php
    require("Controllers/AbstractController.php");

    class readEventTypes extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
            $query = "SELECT * FROM eventTypes WHERE eventOwner = ?";

            $columnsArray = array(
                "eventId", "eventOwner", "eventName", "eventSensor", "eventAction", "eventData"
            );

            $params = array_merge(
                array("s"),
                array($_GET["owner"]),
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>