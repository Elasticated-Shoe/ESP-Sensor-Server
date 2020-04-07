<?php
    require("Controllers/AbstractController.php");

    class Insert extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return "Insert Method Must Be Post";
            }
            if( (!isset($_POST["sensorId"]) || !isset($_POST["sensorValue"]) ) && !isset($_POST["data"])) {
                return "Please Post Some Data";
            }
            if(isset($_POST["sensorId"]) && isset($_POST["sensorId"]) && isset($_POST["data"])) {
                return "Pass Either An Array Or Single Value. Not Both";
            }
            return true;
        }
        function CheckPermission() {
            $DoesLoggedInUserMatchRequestedUser = $this->session->isUser();
            if(!$DoesLoggedInUserMatchRequestedUser) {
                return "You Are Not Logged In For That User";
            }
            $user = $this->session->getUser();
            $allRequestedSensors = $this->dbCache->getUsersSensorMetadata( $user );

            $isOwned = false;
            foreach($allRequestedSensors as $sensor) {
                if($sensor["sensorOwner"] === $user && $sensor["sensorId"] === $_POST["sensorId"]) {
                    $isOwned = true;
                    break;
                }
            }
            if(!$isOwned) {
                return "You Do Not Have Permission To Access That Sensor";
            }

            return true;
        }
        function Action() {
            if(isset($_POST["data"])) {
                foreach($_POST["data"] as $sensor) {
                    $dateTime = isset($sensor["sensorDatetime"]) ? $sensor["sensorDatetime"] : date("Y-m-d H:i:s");

                    $this->dbCache->insertArchive($sensor["sensorId"], $sensor["sensorValue"], $dateTime);
                }
            }
            else {
                $dateTime = isset($_POST["sensorDatetime"]) ? $_POST["sensorDatetime"] : date("Y-m-d H:i:s");

                $this->dbCache->insertArchive($_POST["sensorId"], $_POST["sensorValue"], $dateTime);
            }
            return "Inserted";
        }
    }
?>