<?php
    require("Controllers/AbstractController.php");
    require("protected/API Actions/User/readUser.php");
    require("protected/API Actions/User/readFailedLogins.php");

    class Login extends baseAction {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
            $userObj = new readUser($this->session);
            $user = $userObj->actOnHandle($this->dbHandle, $_POST["user"]);

            $userFailedLoginsObj = new readFailedLogins($this->session);
            $userFailedLogins = $userFailedLoginsObj->actOnHandle($this->dbHandle, $_POST["user"]);

            // get datetime and string of now
            $strDatetimeNow = date("Y-m-d H:i:s");

            // if failed logins exist and are old clear them
            if($userFailedLogins["attemptCount"] > 0) {
                $lastFailedLoginDatetime = strtotime($userFailedLogins["attemptDatetime"]);
                $datetimeNow = strtotime($strDatetimeNow);
                $timeAgoInMin = abs($datetimeNow - $lastFailedLoginDatetime) / 60;
                if($timeAgoInMin > 10) {
                    $resetFailedQuery = "UPDATE userFailedLogins SET attemptCount=? WHERE userEmail = ?";
                    $params = array_merge(
                        array("is"),
                        array(0, $_POST["user"])
                    );
                    $this->dbHandle->runParameterizedQuery($resetFailedQuery, null, $params);

                    $lockQuery = "UPDATE users SET isLocked=0 WHERE userEmail = ?";
                    $params = array_merge(
                        array("s"),
                        array($_POST["user"])
                    );
                    $this->dbHandle->runParameterizedQuery($lockQuery, null, $params);

                    $user["isLocked"] = 0;
                    $userFailedLogins["attemptCount"] = 0;
                }
            }
            // method must be a post
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                
                return array(
                    "Message" => "Method Must Be Post"
                );
            }
            // password is invalid
            if($this->passwordInvalid($_POST["password"])) {
                http_response_code(400);

                return array(
                    "Message" => "Password Is In An Invalid Format"
                );
            }
            // don't allow login for locked accounts
            if($user["isLocked"]) {
                http_response_code(429);

                return array(
                    "Message" => "User Account Has Been Locked"
                );
            }
            // password is incorrect
            $preHashPass = $this->preHash($_POST["password"]);
            if(!password_verify($preHashPass, $user["userPass"])) {
                http_response_code(403);

                // increment failed login count
                $incrementFailedQuery = "UPDATE userFailedLogins SET attemptCount=?, attemptDatetime=? WHERE userEmail = ?";
                $params = array_merge(
                    array("iss"),
                    array(++$userFailedLogins["attemptCount"], $strDatetimeNow, $_POST["user"])
                );
                $this->dbHandle->runParameterizedQuery($incrementFailedQuery, null, $params);

                // if the user has 3 failed logins then lock the account
                if($userFailedLogins["attemptCount"] === 3) {
                    $lockQuery = "UPDATE users SET isLocked=1 WHERE userEmail = ?";
                    $params = array_merge(
                        array("s"),
                        array($_POST["user"])
                    );
                    $this->dbHandle->runParameterizedQuery($lockQuery, null, $params);
                }

                return array(
                    "Message" => "Incorrect Password"
                );
            }

            $this->session->setLogin($_POST["user"]);

            return array(
                "Message" => "You Have Logged In"
            );
        }
        protected function passwordInvalid($password) {
            return false;
        }
        public function generateHash($userPass) {
            return password_hash(
                $userPass,
                PASSWORD_BCRYPT, 
                [
                    "cost" => $GLOBALS["Config"]["Password"]["Cost"]
                ]
            );
        }
        public function preHash($userPass) {
            # lessens long password spamming
            $userPass = $this->fastHash($userPass);
            # in case of db compromise
            $userPass = $this->pepperPassword($userPass);

            return $userPass;
        }
        protected function fastHash($userPass) {
            return hash('sha256', $userPass);
        }
        protected function pepperPassword($userPass) {
            return $GLOBALS["Config"]["Password"]["Pepper"] . $userPass; # I HAVE NO HSM
        }
    }
?>