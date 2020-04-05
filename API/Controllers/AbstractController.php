<?php
    require("Database/TryGetCache.php");

    abstract class AbstractController implements IController {
        protected $session;
        protected $dbCache;

        function __construct($sessionHandler) {
            $this->session = $sessionHandler;

            $this->dbCache = new TryGetCache();
            $this->dbCache->connectDb();
        }
    }
?>