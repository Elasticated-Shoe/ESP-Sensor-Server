<?php
    require("Controllers/AbstractController.php");

    class readUser extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
            return $this->dbCache->findUser($_GET["user"]);
        }
    }
?>