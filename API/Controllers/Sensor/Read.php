<?php
    require("Controllers/AbstractController.php");

    class Read extends AbstractController {
        private $returnAll = true;
        private $sensorMeta;
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return "Insert Method Must Be GET";
            }
            return true;
        }
        function CheckPermission() {
            $DoesLoggedInUserMatchRequestedUser = $this->session->isUser();

            if(isset($_GET["sensors"])) {
                $this->sensorMeta = $this->dbCache->getSensorSensorMetadata( $_GET["sensors"] );
                $nonPublic = array_filter($this->sensorMeta, function ($var) {
                    return ($var["sensorPublic"] == false);
                });
                if(count($nonPublic) > 0) {
                    $this->returnAll = false;
                }
            }
            if(!$DoesLoggedInUserMatchRequestedUser) {
                $this->returnAll = false;
            }
            return true;
        }
        function Action() {
            $allSensors = isset($_GET["sensors"])   ? $this->sensorMeta
                                                    : $this->dbCache->getUsersSensorMetadata($this->session->getUser());

            if(!$this->returnAll) {
                $allSensors = array_filter($allSensors, function ($var) {
                    return ($var["sensorPublic"] == true);
                });
            }

            return $allSensors;
        }
    }
?>