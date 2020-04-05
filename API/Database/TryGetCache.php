<?php
    require("Database/Database.php");

    class TryGetCache {
        private $dbHandle;
        private $metaColumns = array(
            "sensorId", "sensorName", "sensorOwner", "displayName", "lastValue", "sensorType", "sensorUnits", 
            "sensorLocation", "sensorVersion", "lastSeen", "sensorPublic"
        );

        public function connectDb() {
            $this->dbHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );
            $this->dbHandle->connect();
        }
        public function getUsersSensorMetadata($owner) {
            $query = "SELECT * FROM sensorMetadata WHERE sensorOwner = ?";

            $columnsArray = $this->metaColumns;

            $params = array_merge(
                array("s"),
                array($owner),
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
        public function getSensorSensorMetadata($sensorArray) {
            $query = "SELECT * FROM sensorMetadata WHERE (SelectedSensors)";

            $selectedParamString = str_repeat('sensorId = ? OR ', count($sensorArray));
            $selectedParamString = substr($selectedParamString, 0, -4); // remove last ' OR '
            $query = str_replace("SelectedSensors", $selectedParamString, $query);

            $columnsArray = $this->metaColumns;

            $params = array_merge(
                array( str_repeat('i', count( $sensorArray )) ),
                $sensorArray
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
        public function getFromArchive($selectedArray, $startDate, $endDate) {
            $query = "SELECT * FROM sensorData WHERE (SelectedSensors) 
                                                AND sensorDatetime > ? 
                                                AND sensorDatetime < ?";
            $selectedParamString = str_repeat('sensorId = ? OR ', count($selectedArray));
            $selectedParamString = substr($selectedParamString, 0, -4); // remove last ' OR '
            $query = str_replace("SelectedSensors", $selectedParamString, $query);

            $columnsArray = array("sensorId", "sensorDatetime", "sensorValue");

            $params = array_merge(
                array(str_repeat('i', count( $selectedArray )) . "ss"),
                $selectedArray,
                array($startDate, $endDate)
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
        public function findUser($user) {
            $query = "SELECT * FROM users WHERE userEmail = ?";

            $columnsArray = array("userEmail", "userPass", "isLocked", "isAdmin");

            $params = array_merge(
                array("s"),
                array($user)
            );
            
            $result = $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
            if(count($result) === 1) {
                return $result[0];
            }
            return false;
        }
        public function readAttempts($user) {
            $query = "SELECT * FROM userFailedLogins WHERE userEmail = ?";

            $columnsArray = array("userEmail", "attemptCount", "attemptDatetime");

            $params = array_merge(
                array("s"),
                array($user)
            );
            
            $result = $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
            if(count($result) === 1) {
                return $result[0];
            }
            return false;
        }
        public function updateAttempts($user) {
            $resetFailedQuery = "UPDATE userFailedLogins SET attemptCount=? WHERE userEmail = ?";
            $params = array_merge(
                array("is"),
                array(0, $user)
            );
            $this->dbHandle->runParameterizedQuery($resetFailedQuery, null, $params);

            $lockQuery = "UPDATE users SET isLocked=0 WHERE userEmail = ?";
            $params2 = array_merge(
                array("s"),
                array($user)
            );
            $this->dbHandle->runParameterizedQuery($lockQuery, null, $params2);
        }
        public function incrementAttempts($userFailedLogins, $strDatetimeNow, $user) {
            // increment failed login count
            $incrementFailedQuery = "UPDATE userFailedLogins SET attemptCount=?, attemptDatetime=? WHERE userEmail = ?";
            $params = array_merge(
                array("iss"),
                array(++$userFailedLogins["attemptCount"], $strDatetimeNow, $user)
            );
            $this->dbHandle->runParameterizedQuery($incrementFailedQuery, null, $params);

            // if the user has 3 failed logins then lock the account
            if($userFailedLogins["attemptCount"] === 3) {
                $lockQuery = "UPDATE users SET isLocked=1 WHERE userEmail = ?";
                $params2 = array_merge(
                    array("s"),
                    array($user)
                );
                $this->dbHandle->runParameterizedQuery($lockQuery, null, $params2);
            }
        }
        public function insertArchive($sensorId, $sensorValue, $dateTime) {
            $query = "INSERT INTO sensorData(sensorId, sensorDatetime, sensorValue) VALUES (?, ?, ?)";

            $params = array_merge(
                array("iss"),
                array($sensorId, $dateTime, $sensorValue)
            );

            $this->dbHandle->runParameterizedQuery($query, null, $params);
        }
    }
?>