<?php
    require_once("protected/API Actions/BaseRequest.php");

    class RecentReadings extends BaseRequest {
        public $permission = "Metadata";

        function init() {
            $this->callInbuiltQuery(
                "SELECT sensor, reading, lastSeen, sensorType, sensorLocation FROM sensorMetadata;",
                array("sensor", "reading", "lastSeen", "sensorType", "sensorLocation")
            );
        }
    }
?>