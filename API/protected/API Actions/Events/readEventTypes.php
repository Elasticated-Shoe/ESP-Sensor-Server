<?php
    require_once("protected/API Actions/baseAction.php");

    class readEventTypes extends baseAction {
        function init() {
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