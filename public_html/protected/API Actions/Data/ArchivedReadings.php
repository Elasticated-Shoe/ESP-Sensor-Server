<?php
    require_once("protected/API Actions/BaseRequest.php");
    require_once("protected/Database/Database.php");

    class ArchivedReadings extends BaseRequest {
        function init() {
            $this->callInbuiltQuery(
                "archivedReadings",
                array(
                    $_GET["start"],
                    $_GET["end"],
                    $_GET["sensors"]
                )
            );
        }
    }
?>