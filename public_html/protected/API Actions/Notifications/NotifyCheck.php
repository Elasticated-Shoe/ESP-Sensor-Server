<?php
    require_once("protected/API Actions/BaseRequest.php");

    class NotifyCheck extends BaseRequest {
        public $permission = "readNotification";

        function init() {
            $toCheckQuery = "SELECT eventName, eventSensor, eventAction, eventThreshold FROM eventTypes";
            $insertEventQuery = "INSERT INTO eventLog (eventId, eventName, eventSensor, eventTimestamp, 
                eventOngoing, eventDesc, userInformed, userAck) VALUES (?,?,?,?,?,?,?,?);";

            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $toCheckData = $currentConnection->runParameterizedQuery(
                    $toCheckQuery,
                    array("eventName", "eventSensor", "eventAction", "eventThreshold")
                );
                $currentStateArray = $currentConnection->runParameterizedQuery(
                    "SELECT sensor, reading, lastSeen FROM sensorMetadata;",
                    array("sensor", "reading", "lastSeen")
                );
                $mailUsers = $currentConnection->runParameterizedQuery(
                    "SELECT userEmail FROM users;",
                    array("userEmail")
                );
                foreach($currentStateArray as $sensor) {
                    $sensorName = $sensor["sensor"];
                    $currentStateSensorIndexed[$sensorName] = $sensor;
                }
                foreach($toCheckData as $checkItem) {
                    $sensorToCheck = $checkItem["eventSensor"];
                    $sensorData = $currentStateSensorIndexed[$sensorToCheck];
                    if($checkItem["eventAction"] === "isOn") {
                        // sensor has not been seen for over a minute
                        if( time() - $sensorData["lastSeen"] > 60 ) {
                            $currentEvents = $currentConnection->runParameterizedQuery(
                                "SELECT eventId, eventName, eventSensor, eventTimestamp, eventOngoing, eventDesc, 
                                        userInformed, userAck FROM eventLog 
                                        WHERE eventOngoing = 1 AND eventName = ? AND eventSensor = ?;",
                                array(
                                    "eventId", "eventName", "eventSensor", "eventTimestamp", "eventOngoing", 
                                    "eventDesc", "userInformed", "userAck"
                                ),
                                array("ss", $checkItem["eventName"], $sensorToCheck)
                            );
                            if( count($currentEvents) !== 1 ) {
                                // event does not exist, or multiple exits
                                $currentConnection->runParameterizedQuery(
                                    $insertEventQuery,
                                    null,
                                    array(
                                        "sssiisii", "uniqueThing", $checkItem["eventName"], $sensorToCheck, 
                                        time(), 1, "Event Description", 1, 0
                                    )
                                );
                                foreach($mailUsers as $mailUser) {
                                    $to = $mailUser["userEmail"];
                                    $subject = "My subject";
                                    $txt = "";
                                    $headers = "From: test@growandsell.co.uk";
                                    mail($to, $subject, $txt, $headers);
                                }
                            }
                        }
                    }
                }

                $this->data = "success";
            }
        }
    }
?>