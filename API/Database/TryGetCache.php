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
    }
?>