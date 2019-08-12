<?php
    class Database {
        public $databaseName;
        public $databaseUser;
        public $databaseUrl;
        private $databasePass;
        private $sessionObject;

        function __construct($name, $pass, $server, $database, $sessionObject) {
            $this->databaseName = $database;
            $this->databaseUser = $name;
            $this->databaseUrl = $server;
            $this->databasePass = $pass;
            $this->sessionObject = $sessionObject;
        }
        function __destruct() {
            $conn = $this->conn;
            $conn->close();
        }
        function connect() {
            $mysqli = new mysqli($this->databaseUrl, $this->databaseUser, $this->databasePass, $this->databaseName);
            if ($mysqli->connect_errno) {
                return false;
            }
            $this->conn = $mysqli;
        }
        function showColumns($table) {
            $conn = $this->conn;
            $preparedQuery = $conn->prepare("SHOW COLUMNS FROM " . $table . ";");
            $preparedQuery->execute();
            //$preparedQuery->bind_result($column, $test, $pas, $test2, $test3);
            // map data to array
            $result = array();
            $columnProperties = $preparedQuery->get_result();
            while( $columnProperty = $columnProperties->fetch_assoc() ) {
                array_push($result, $columnProperty["Field"]);
            }
            return $result;
        }
        function recentReadings() {
            $conn = $this->conn;
            $preparedQuery = $conn->prepare("SELECT sensor, reading, lastSeen, sensorType, sensorLocation FROM mostRecentData;");
            $preparedQuery->execute();
            $preparedQuery->bind_result($sensor, $reading, $lastSeen, $sensorType, $sensorLocation);
            // map data to array
            while ($preparedQuery->fetch()) {
                $result[$sensor]["reading"] = strip_tags($reading);
                $result[$sensor]["lastSeen"] = strip_tags($lastSeen);
                $result[$sensor]["sensorType"] = strip_tags($sensorType);
                $result[$sensor]["sensorLocation"] = strip_tags($sensorLocation);
            }
            // close connections
            $preparedQuery->close();
            return $result;
        }
        function archivedReadings($start, $finish, $sensorWhitelist) {
            $conn = $this->conn;
            $allColumns = $this->showColumns("allData");
            array_push($sensorWhitelist, "readingTimestamp");
            $sensorBlacklist = array_diff($allColumns, $sensorWhitelist);
            $sensorWhitelist = array_diff($allColumns, $sensorBlacklist);
            $stringWhitelist = implode(", ", $sensorWhitelist);
            // because second order sql attack is still possible
            $stringWhitelist = $conn->real_escape_string($stringWhitelist);
            $preparedQuery = $conn->prepare("SELECT " . $stringWhitelist
                                            ." FROM allData WHERE readingTimestamp < ? AND readingTimestamp > ?;");
            $preparedQuery->bind_param("ii", $finish, $start);
            $preparedQuery->execute();
            // dynamic way of getting list of column names, for when we call bind_result
            foreach($sensorWhitelist as $columnName) {
                $var = $columnName; 
                $$var = null; 
                $fields[$var] = &$$var;
            }
            call_user_func_array(array($preparedQuery,'bind_result'),$fields);
            $i = 0;
            while ($preparedQuery->fetch()) {
                $result[$i] = array();
                foreach($fields as $k => $v)
                    $result[$i][$k] = $v;
                $i++;
            }
            return $result;
        }
    }
?>