<?php
    require_once("protected/API Actions/Login/LoginBase.php");

    class Login extends LoginBase {
        public $permission = "loginUser";

        function init() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                die("Method Must Be POST");
            }
            $userProvidedPassword = $_POST["password"];
            $userProvidedName = $_POST["name"];
            if($this->isValid($userProvidedPassword) !== true) {
                http_response_code(400);
                die("Password Provided In Invalid Format");
            }
            if( $this->isLock($userProvidedName) ) {
                http_response_code(429);
                die("Too Many Failed Logins, Try Again Later");
            }
            if( $this->isPassword($userProvidedName, $userProvidedPassword) ) {
                $this->callInbuiltQuery(
                    "SELECT readArchive, writeArchive, writeMeta, writeNotification, readNotification, 
                        addPermission, createUser, loginUser FROM userpermissions WHERE username = ?;",
                    array("readArchive", "writeArchive", "writeMeta", "writeNotification", "readNotification", 
                            "addPermission", "createUser", "loginUser"),
                    array("s", $userProvidedName)
                );
                $permissionsDict = $this->data[0];
                $permissionsArray = array();
                foreach($permissionsDict as $key => $value) {
                    if($value === 1) {
                        array_push($permissionsArray, $key);
                    }
                }
                $_SESSION["permissions"] = $permissionsArray;
                //echo print_r($_SESSION);
            }
            else {
                $this->incorrectAttempt($userProvidedName);
                http_response_code(403); // Return forbidden as password incorrect
                die("User Password Incorrect");
            }
        }
    }
?>