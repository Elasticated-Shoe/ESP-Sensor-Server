<?php
    require_once("protected/Database/Database.php");

    interface apiInterface {
        public function init();
    }
    abstract class baseAction implements apiInterface {
        protected $session;
        protected $dbHandle;

        function __construct($sessionHandler) {
            $this->session = $sessionHandler;
        }
        public function connectDb() {
            $this->dbHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );
            $this->dbHandle->connect();
        }
    }
?>