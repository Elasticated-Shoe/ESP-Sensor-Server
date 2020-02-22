<?php
    require_once("protected/API Actions/baseAction.php");

    class readEvents extends baseAction {
        function init() {
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