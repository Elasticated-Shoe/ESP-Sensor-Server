<?php
    interface apiInterface {
        public function init();
    }
    abstract class BaseRequest implements apiInterface {
        private $authorized;

        function __construct($authorized) {
            $this->authorized = $authorized;
        }
        
    }
?>