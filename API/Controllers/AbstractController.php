<?php
    require("Database/Database.php");
    require("Database/TryGetCache.php");

    abstract class AbstractController implements IController {
        protected $session;
        protected $dbCache;
        protected $dbHandle;

        function __construct($sessionHandler) {
            $this->session = $sessionHandler;

            $this->dbHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );
            $this->dbHandle->connect();
            $this->dbCache = new TryGetCache($this->dbHandle);
        }
    }
?>