<?php
    class TableFactory {
        public function getTable($name) {
            if($name === "users") {
                return new users();
            }
            elseif($name === "usersFailedLogins") {
                return new usersFailedLogins();
            }
            elseif($name === "sensorMetadata") {
                return new sensorMetadata();
            }
            elseif($name === "eventTypes") {
                return new eventTypes();
            }
            elseif($name === "eventLog") {
                return new eventLog();
            }
            elseif($name === "sensorData") {
                return new sensorData();
            }
            else {
                
            }
        }
    }
    class users {
        public function getKey() {
            return "userEmail";
        }
        public function getColumns() {
            return array("userPass", "isAdmin", "isLocked");
        }
        public function getColumnTypes() {
            return array(
                "userPass" => "s",
                "isAdmin" => "i", 
                "isLocked" => "i"
            );
        }
    }
    class usersFailedLogins {
        public function getKey() {
            return "userEmail";
        }
        public function getColumns() {
            return array("attemptCount", "attemptDatetime");
        }
        public function getColumnTypes() {
            return array(
                "attemptCount" => "i",
                "attemptDatetime" => "s"
            );
        }
    }
    class sensorMetadata {
        public function getKey() {
            return "sensorId";
        }
        public function getColumns() {
            return array(   "sensorName", "sensorOwner", "sensorPublic", "displayName", "lastValue", 
                            "sensorType", "sensorUnits", "sensorLocation", "sensorVersion", "lastSeen");
        }
        public function getColumnTypes() {
            return array(
                "sensorName" => "s",
                "sensorOwner" => "s", 
                "sensorPublic" => "i", 
                "displayName" => "s", 
                "lastValue" => "s", 
                "sensorType" => "s", 
                "sensorUnits" => "s", 
                "sensorLocation" => "s", 
                "sensorVersion" => "i", 
                "lastSeen" => "s"
            );
        }
    }
    class eventTypes {
        public function getKey() {
            return "eventId";
        }
        public function getColumns() {
            return array("eventOwner", "eventName", "eventSensor", "eventAction", "eventData");
        }
        public function getColumnTypes() {
            return array(
                "eventOwner" => "s",
                "eventName" => "s",
                "eventSensor" => "i",
                "eventAction" => "s",
                "eventData" => "s",
            );
        }
    }
    class eventLog {
        public function getKey() {
            return null;
        }
        public function getColumns() {
            return array("eventId", "eventName", "eventSensor", "eventTime", "eventOngoing", "eventDesc", "userInformed", "userAck");
        }
        public function getColumnTypes() {
            return array(
                "eventId" => "i",
                "eventName" => "s",
                "eventSensor" => "i",
                "eventTime" => "s",
                "eventOngoing" => "i",
                "eventDesc" => "s",
                "userInformed" => "i",
                "userAck" => "i",
            );
        }
    }
    class sensorData {
        public function getKey() {
            return null;
        }
        public function getColumns() {
            return array("sensorId", "sensorDatetime", "sensorValue");
        }
        public function getColumnTypes() {
            return array(
                "sensorId" => "i",
                "sensorDatetime" => "s",
                "sensorValue" => "s",
            );
        }
    }
?>