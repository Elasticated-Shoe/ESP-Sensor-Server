<?php
    require_once("protected/API Actions/BaseRequest.php");

    class ArchiveInsert extends BaseRequest {
        public $permission = "writeArchive";

        function init() {
            $addColumnQueryBase = "ALTER TABLE sensorData ADD COLUMN %s INT(11);";
            
            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $allColumns = $currentConnection->showColumns("sensorData");
                $dataArray = json_decode($_POST["data"]); // list of objects
                // add the columns to database if not already in, otherwise it would error on insert
                foreach($dataArray as $dataRow) {
                    foreach($dataRow as $key => $value) {
                        if(!in_array($key, $allColumns) ) {
                            echo "\n" . "key: " . $key . "Does Not Exist\n";
                            $key = $currentConnection->conn->real_escape_string($key);
                            $addColumnQuery = sprintf($addColumnQueryBase, $key);
                            $currentConnection->runParameterizedQuery($addColumnQuery);
                        }
                    }
                }
                // insert
                foreach($dataArray as $dataRow) {
                    $columnArray = [];
                    $valueArray = [];
                    $paramsArray = [];
                    foreach($dataRow as $key => $value) {
                        $key = $currentConnection->conn->real_escape_string($key);
                        $value = $currentConnection->conn->real_escape_string($value);
                        array_push($columnArray, $key);
                        array_push($valueArray, $value);
                    }
                    $paramsArray = array_pad($paramsArray, count($valueArray), "?");
                    $stringColumns = implode(", ", $columnArray);
                    $stringParams = implode(",", $paramsArray );
                    $insertQuery = "INSERT INTO sensorData (" . $stringColumns . ") VALUES 
                                        (" . $stringParams . ");";
                    //
                    $insertTypes = str_repeat( "i", count($valueArray) );
                    array_unshift($valueArray, $insertTypes);
                    $currentConnection->runParameterizedQuery(
                        $insertQuery,
                        null,
                        $valueArray
                    );
                }
                $this->data = "success";
            }
            else {
                $this->error = "Connection To The Database Failed";
            }
        }
    }
?>