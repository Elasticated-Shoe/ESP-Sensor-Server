<?php
    class TryGetCache {
        private $dbHandle;

        function __construct($dbConnection) {
            $this->dbHandle = $dbConnection;
        }
        public function getUsersSensorMetadata($owner) {
            $query = "SELECT * FROM sensorMetadata WHERE sensorOwner = ?";

            $schema = $this->dbHandle->tableFactory->getTable("sensorMetadata");

            $columnsArray = array_merge(array($schema->getKey()), $schema->getColumns());

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

            $schema = $this->dbHandle->tableFactory->getTable("sensorMetadata");
            $columnsArray = array_merge(array($schema->getKey()), $schema->getColumns());

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

            $schema = $this->dbHandle->tableFactory->getTable("sensorData");
            $columnsArray = $schema->getColumns();

            $params = array_merge(
                array(str_repeat('i', count( $selectedArray )) . "ss"),
                $selectedArray,
                array($startDate, $endDate)
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
        public function findUser($user) {
            $query = "SELECT * FROM users WHERE userEmail = ?";

            $schema = $this->dbHandle->tableFactory->getTable("users");
            $columnsArray = array_merge(array($schema->getKey()), $schema->getColumns());

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

            $schema = $this->dbHandle->tableFactory->getTable("userFailedLogins");
            $columnsArray = array_merge(array($schema->getKey()), $schema->getColumns());

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
        public function readEvents($user) {
            $query = "SELECT * FROM eventLog WHERE eventId = ?";

            $schema = $this->dbHandle->tableFactory->getTable("eventLog");
            $columnsArray = $schema->getColumns();

            $params = array_merge(
                array("s"),
                array($user),
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
        public function readTypes($user) {
            $query = "SELECT * FROM eventTypes WHERE eventOwner = ?";

            $schema = $this->dbHandle->tableFactory->getTable("eventTypes");
            $columnsArray = array_merge(array($schema->getKey()), $schema->getColumns());

            $params = array_merge(
                array("s"),
                array($user),
            );

            return $this->dbHandle->runParameterizedQuery($query, $columnsArray, $params);
        }
    }
?>