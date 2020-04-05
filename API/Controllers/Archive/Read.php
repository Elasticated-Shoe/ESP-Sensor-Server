<?php
    require("Controllers/AbstractController.php");

    class Read extends AbstractController {
        function CheckInput() {
            if(!isset($_GET["sensors"]) || !isset($_GET["start"]) || !isset($_GET["end"])) {
                return "Please Define Some Constraints For The Data To Be Returned";
            }
            if(!is_array($_GET["sensors"])) {
                return "Please Pass Sensors As An Array";
            }
            
            return true;
        }
        function CheckPermission() {
            $DoesLoggedInUserMatchRequestedUser = $this->session->isUser();
            $allRequestedSensors = $this->dbCache->getSensorSensorMetadata( $_GET["sensors"] );

            $publicAndOwned = true;
            $allPublic = true;
            
            foreach($allRequestedSensors as $sensor) {
                if(!$sensor["sensorPublic"]) {
                    $allPublic = false;
                }
                if(!$sensor["sensorPublic"] && $sensor["sensorOwner"] !== $this->session->getUser()) {
                    $publicAndOwned = false;
                }
            }

            // if you do not own the sensors but you have only requested sensors you own or a public
            if(!$allPublic && (!$DoesLoggedInUserMatchRequestedUser || !$publicAndOwned) ) {
                return "You Are Not Authorized To View Some Of The Requested Data";
            }
            return true;
        }
        function Action() {
            return $this->dbCache->getFromArchive(
                $_GET["sensors"], 
                $_GET["start"], 
                $_GET["end"]
            );
        }
    }
?>