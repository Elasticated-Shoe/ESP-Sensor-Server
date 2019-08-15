<?php
    require_once("protected/API Actions/BaseRequest.php");

    abstract class LoginBase extends BaseRequest {
        function generateHash($userPass) {
            return password_hash($userPass, PASSWORD_BCRYPT, ["cost" => 14]);
        }
        function pepperPassword($password) {
            return $password;
        }
        function isValid($suppliedString) {
            if(gettype($suppliedString) !== "string" || strlen($suppliedString) > 50) {
                return false;
            }
            return true;
        }
        function isLock($username) {
            return false;
        }
        function isPassword($username, $suppliedPassword) {
            //SELECT `username`, `userPass`, `userEmail`, `isLocked` FROM `users` WHERE 1
            $this->callInbuiltQuery(
                "SELECT userPass FROM users WHERE username = ?;",
                array("userPass"),
                array("s", $username)
            );
            $userHash = $this->data[0]["userPass"];
            return password_verify($suppliedPassword, $userHash);
        }
        function incorrectAttempt($username) {

        }
    }
?>