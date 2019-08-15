<?php
    require_once("protected/API Actions/Login/LoginBase.php");

    class CreateUser extends LoginBase {
        public $permission = "Create";

        function init() {
            $this->callInbuiltQuery(
                "INSERT INTO users(
                    username, userPass, userEmail, isLocked
                ) 
                VALUES (
                    ?, ?, ?, ?
                )",
                null,
                array("sssi", $_POST["name"], $_POST["password"], $_POST["email"], 0)
            );
            $this->data = "User Created";  
        }
    }
?>