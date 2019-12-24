<?php
    require_once("protected/API Actions/Login/LoginBase.php");

    class CreateUser extends LoginBase {
        public $permission = "createUser";

        function init() {
            if(! $this->isValid($_POST["password"]) ) {
                $this->error = "Invalid Password Format";
                return;
            }
            $pepperedPassword = $this->pepperPassword($_POST["password"]);
            $hashedPepperedPassword = $this->generateHash($pepperedPassword);
            // add user to the users table
            $this->callInbuiltQuery(
                "INSERT INTO users( username, userPass, userEmail, isLocked ) 
                VALUES ( ?, ?, ?, ? )",
                null,
                array("sssi", $_POST["name"], $hashedPepperedPassword, $_POST["email"], 0)
            );
            $this->data = "User Created";  
        }
    }
?>