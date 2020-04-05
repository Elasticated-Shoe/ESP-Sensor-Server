<?php
    require("Controllers/AbstractController.php");

    class Insert extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return "Insert Method Must Be Post";
            }
            if(!isset($_POST["sensorId"]) || !isset($_POST["sensorValue"])) {
                return "Please Provide A sensorId And A sensorValue";
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
            $dateTime = isset($_POST["sensorDatetime"]) ? $_POST["sensorDatetime"] : date("Y-m-d H:i:s");

            $this->dbCache->insertArchive($_POST["sensorId"], $_POST["sensorValue"], $dateTime);

            return "Inserted";
        }
    }
?>