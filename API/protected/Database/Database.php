<?php
    class DatabaseActions {
        private $databaseName;
        private $databaseUser;
        private $databaseUrl;
        private $databasePass;
        public $conn;

        function __construct($name, $pass, $server, $database) {
            $this->databaseName = $database;
            $this->databaseUser = $name;
            $this->databaseUrl = $server;
            $this->databasePass = $pass;
        }
        function __destruct() {
            //$this->conn->close();
        }
        public function connect() {
            $mysqli = new mysqli($this->databaseUrl, $this->databaseUser, $this->databasePass, $this->databaseName);
            if ($mysqli->connect_errno) {
                throw new exception($mysqli->connect_errno);
            }
            $this->conn = $mysqli;
        }
        function runParameterizedQuery($query, $columnsArray = null, $params = null) {
            $preparedQuery = $this->conn->prepare($query);
            if (!$preparedQuery) {
                throw new exception($conn->error);
            }
            // if passing straight query with no parameters dont need this
            if($params !== null) {
                // mysqli_stmt_bind_param() requires parameters to be passed by reference not a val
                $bindParam = call_user_func_array( array($preparedQuery,'bind_param'), $this->getRefNotVal($params) );
                if(!$bindParam) {
                    throw new exception($preparedQuery->error);
                }
            }
            $preparedQuery->execute();
            // array of empty variables for bind_result to bind to
            // if running insert dont need to bind so this is optional
            if($columnsArray !== null) {
                foreach($columnsArray as $columnName) {
                    $$columnName = null; 
                    $bindings[$columnName] =& $$columnName;
                }
                $bindResult = call_user_func_array( array($preparedQuery,'bind_result'), $bindings );
                if(!$bindResult) {
                    throw new exception($preparedQuery->error);
                }
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