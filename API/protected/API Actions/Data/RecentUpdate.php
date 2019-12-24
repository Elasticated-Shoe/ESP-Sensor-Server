<?php
    require_once("protected/API Actions/BaseRequest.php");

    class RecentUpdate extends BaseRequest {
        public $permission = "writeMeta";

        function init() {
            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $allColumns = $currentConnection->showColumns("sensorMetadata");
                $dataArray = json_decode($_POST["data"]);
                foreach($dataArray as $dataRow) {
                    $columnWhitelist = array_keys( (array)$dataRow );
                    $columnBlacklist = array_diff($allColumns, $columnWhitelist);
                    $columnWhitelist = array_diff($allColumns, $columnBlacklist);
                    $stringWhitelist = implode(", ", $columnWhitelist);
                    $stringWhitelist = $currentConnection->conn->real_escape_string($stringWhitelist);
                    $columnCount = count($columnWhitelist);
                    //
                    $valuesParamArray = array_pad( [], $columnCount, "?" );
                    $stringParamValues = implode(",", $valuesParamArray);
                    $duplicateArray = [];
                    foreach($columnWhitelist as $columnName) {
                        $columnName = $currentConnection->conn->real_escape_string($columnName);
                        array_push($duplicateArray, $columnName . "=?");
                    }
                    $valueArray = [];
                    foreach($dataRow as $key => $value) {
                        array_push($valueArray, $value);
                    }
                    $stringDuplicates = implode(",", $duplicateArray);
                    $insertQuery = "INSERT INTO sensorMetadata (" . $stringWhitelist . ") 
                                        VALUES (" . $stringParamValues . ")
                                        ON DUPLICATE KEY UPDATE " . $stringDuplicates . ";";
                    //
                    $paramArray = array_merge($valueArray, $valueArray);
                    //
                    $valueTypes = "";
                    foreach($valueArray as $value) {
                        $inputType = gettype($value);
                        if($inputType === "string") {
                            $valueTypes = $valueTypes . "s";
                        }
                        if($inputType === "integer") {
                            $valueTypes = $valueTypes . "i";
                        }
                    }
                    $valueTypes = $valueTypes . $valueTypes;
                    array_unshift($paramArray, $valueTypes);
                    $currentConnection->runParameterizedQuery(
                        $insertQuery,
                        null,
                        $paramArray
                    );
                }
                $this->data = "success";
            }  
        }
    }
?>