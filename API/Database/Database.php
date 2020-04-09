<?php
    require("Database/Model.php");

    class DatabaseActions {
        private $databaseName;
        private $databaseUser;
        private $databaseUrl;
        private $databasePass;
        public $tableFactory;
        public $conn;

        function __construct($name, $pass, $server, $database) {
            $this->databaseName = $database;
            $this->databaseUser = $name;
            $this->databaseUrl = $server;
            $this->databasePass = $pass;
            $this->tableFactory = new TableFactory();
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
        public function crud($table, $crud, $sensorObject) {
            $schema = $this->tableFactory->getTable($table);

            $primaryKey = $schema->getKey();
            $primaryKeyValue = $crud !== "insert" ? $sensorObject[$primaryKey] : null;
            if($crud !== "insert") {
                unset($sensorObject[$primaryKey]);
            }
    
            $columnsToUpdate = array_keys($sensorObject);
            $columnsInTable = array_keys($schema->getColumnTypes());
            $unknownColumns = array_diff($columnsToUpdate, $columnsInTable);
            if(count($unknownColumns) !== 0) {
                return false;
            }
            
            $changedValues = array_values($sensorObject);

            $preparedTypesArray = array_intersect_key($schema->getColumnTypes(), array_flip($columnsToUpdate));
            $preparedTypes = implode("", array_values($preparedTypesArray));

            $query = false;
            if($crud === "insert") {
                $query = $this->createQuery($table, $columnsToUpdate);
            }
            elseif($crud === "update") {
                $query = $this->updateQuery($table, $primaryKey, $primaryKeyValue);
            }
            elseif($crud === "delete") {
                $query = $this->deleteQuery($table, $primaryKey, $primaryKeyValue);
            }
            else {
                throw new Exception("Invalid Argument Passed To crud");
            }
            
            $params = array_merge(
                array($preparedTypes),
                $changedValues
            );

            $this->runParameterizedQuery($query, null, $params);
        }
        function createQuery($table, $columnsToUpdate) {
            $query = "INSERT INTO " . $table . "(ChangedColumns) VALUES (ChangedValuesQ)";

            $changedColumns = implode(", ", $columnsToUpdate);
            $changedValuesQ = implode(", ", array_pad(array(), count($columnsToUpdate), "?"));

            $query = str_replace("ChangedColumns", $changedColumns, $query);
            return str_replace("ChangedValuesQ", $changedValuesQ, $query);
        }
        function updateQuery($table, $primaryKey, $primaryKeyValue) {
            $updateArray = array(); // ContactName = 'Alfred Schmidt',
            foreach($columnsToUpdate as $columnName => $columnValue) {
                array_push($updateArray, $columnName . " = '" . $columnValue . "'");
            }
            $updateString = implode(", ", $updateArray);
            return "UPDATE " . $table . " SET " . $updateString . " WHERE " . $primaryKey . " = " . $primaryKeyValue;
        }
        function deleteQuery($table, $primaryKey, $primaryKeyValue) {
            return "DELETE FROM " . $table . " WHERE " . $primaryKey . " = " . $primaryKeyValue;
        }
        function runParameterizedQuery($query, $columnsArray = null, $params = null) {
            $preparedQuery = $this->conn->prepare($query);
            if (!$preparedQuery) {
                throw new exception($this->conn->error);
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