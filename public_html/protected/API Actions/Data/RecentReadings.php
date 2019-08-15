<?php
    require_once("protected/API Actions/BaseRequest.php"); // should be autoloader?
    require_once("protected/Database/Database.php");

    class RecentReadings extends BaseRequest {
        function init() {
            $this->callInbuiltQuery(
                "SELECT sensor, reading, lastSeen, sensorType, sensorLocation FROM sensorMetadata;",
                array("sensor", "reading", "lastSeen", "sensorType", "sensorLocation")
            );
        }
    }
?>