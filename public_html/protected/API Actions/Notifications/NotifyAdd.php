<?php
    require_once("protected/API Actions/BaseRequest.php");

    class NotifyAdd extends NotifyBase {
        public $permission = "writeNotification";
        
        function init() {
            $insertQuery = "INSERT INTO eventTypes (eventName, eventSensor, eventAction, eventThreshold) 
                                VALUES (?, ?, ?, ?);";
            $dataArray = json_decode($_POST["data"]);
            foreach($dataArray as $dataRow) {
                $valueArray = [];
                foreach($dataRow as $key => $value) {
                    array_push($valueArray, $value);
                }
                array_unshift($valueArray, "ssss");
                $this->callInbuiltQuery(
                    $insertQuery,
                    null,
                    $valueArray
                );
            }
            
        }
    }
?>