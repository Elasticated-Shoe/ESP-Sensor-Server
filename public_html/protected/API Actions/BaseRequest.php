<?php
    interface apiInterface {
        public function init();
    }
    abstract class BaseRequest implements apiInterface {
        private $authorized;

        function __construct($authorized) {
            $this->authorized = $authorized;
        }
        function callInbuiltQuery($function, $argArray) {
            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $this->data = call_user_func_array( array($currentConnection, $function), $argArray );
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