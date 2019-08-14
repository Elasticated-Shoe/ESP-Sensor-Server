<?php
    require_once("protected/API Actions/BaseRequest.php"); // should be autoloader?
    require_once("protected/Database/Database.php");

    class RecentReadings extends BaseRequest {
        function init() {
            $this->callInbuiltQuery(
                "recentReadings",
                array()
            );
        }
    }
?>