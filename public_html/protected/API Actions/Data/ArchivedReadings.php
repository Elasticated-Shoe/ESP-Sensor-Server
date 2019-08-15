<?php
    require_once("protected/API Actions/BaseRequest.php");

    class ArchivedReadings extends BaseRequest {
        public $permission = "Archive";

        function init() {
            $sensorWhitelist = $_GET["sensors"];
            array_push($sensorWhitelist, "readingTimestamp");
            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $allColumns = $currentConnection->showColumns("sensorData");
                $sensorBlacklist = array_diff($allColumns, $sensorWhitelist);
                $sensorWhitelist = array_diff($allColumns, $sensorBlacklist);
                $stringWhitelist = implode(", ", $sensorWhitelist);
                // because second order sql attack is still possible
                $stringWhitelist = $currentConnection->conn->real_escape_string($stringWhitelist);
                $query =   "SELECT " . $stringWhitelist ." FROM sensorData WHERE readingTimestamp < ? AND readingTimestamp > ?;";
                $test = $_GET["end"];
                $test2 = $_GET["start"];
                $this->callInbuiltQuery(
                    $query,
                    $sensorWhitelist,
                    array("ii", $test, $test2)
                );
            }
            else {
                $this->error = "Connection To The Database Failed";
            }
        }
    }
?>