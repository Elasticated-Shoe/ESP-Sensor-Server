<?php
    interface apiInterface {
        public function init();
    }
    abstract class BaseRequest implements apiInterface {
        function __construct() {
            
        }
        function callInbuiltQuery($query, $columnsArray, $params = null) {
            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $this->data = $currentConnection->runParameterizedQuery($query, $columnsArray, $params);
            }
            else {
                $this->error = "Connection To The Database Failed";
            }
        }
        function readyResponse() {
            if( isset($this->error) ) {
                $this->response = json_encode(
                    array(
                        "error" => $this->error
                    )
                );
            }
            else {
                $this->response = json_encode($this->data);
            }
        }
    }
?>