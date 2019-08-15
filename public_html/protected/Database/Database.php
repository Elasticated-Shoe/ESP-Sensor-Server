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
        function runParameterizedQuery($query, $columnsArray, $params = null) {
            $conn = $this->conn;
            $preparedQuery = $conn->prepare($query);
            if($params !== null) {
                // mysqli_stmt_bind_param() requires parameters to be passed by reference not a val
                call_user_func_array( array($preparedQuery,'bind_param'), $this->getRefNotVal($params) );
            }
            $preparedQuery->execute();
            // array of empty variables for bind_result to bind to
            foreach($columnsArray as $columnName) {
                $$columnName = null; 
                $bindings[$columnName] =& $$columnName;
            }
            call_user_func_array( array($preparedQuery,'bind_result'), $bindings );
            $result = array();
            while( $preparedQuery->fetch() ) {
                $row = array();
                foreach($bindings as $columnName => $columnValue) {
                    $row[$columnName] = $columnValue;
                }
                array_push($result, $row);
            }
            return $result;
        }
        protected function getRefNotVal($arr) {
            $refs = array();
            foreach($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
    }
?>