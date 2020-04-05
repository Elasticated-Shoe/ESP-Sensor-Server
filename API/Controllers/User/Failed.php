<?php
    require("Controllers/AbstractController.php");

    class readFailedLogins extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
            return $this->dbCache->readAttempts($_GET["user"]);
        }
    }
?>