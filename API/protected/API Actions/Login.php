<?php
    require("protected/API Actions/readUser.php");
    require("protected/API Actions/readFailedLogins.php");

    class Login {
        function init() {
            $dbHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $dbHandle->connect();

            $userObj = new readUser();
            $user = $userObj->actOnHandle($dbHandle, $_POST["user"]);

            $userFailedLoginsObj = new readFailedLogins();
            $userFailedLogins = $userFailedLoginsObj->actOnHandle($dbHandle, $_POST["user"]);

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
                    $dbHandle->runParameterizedQuery($resetFailedQuery, null, $params);

                    $lockQuery = "UPDATE users SET isLocked=0 WHERE userEmail = ?";
                    $params = array_merge(
                        array("s"),
                        array($_POST["user"])
                    );
                    $dbHandle->runParameterizedQuery($lockQuery, null, $params);

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
            if(!password_verify($_POST["password"], $user["userPass"])) {
                http_response_code(403);

                // increment failed login count
                $incrementFailedQuery = "UPDATE userFailedLogins SET attemptCount=?, attemptDatetime=? WHERE userEmail = ?";
                $params = array_merge(
                    array("iss"),
                    array(++$userFailedLogins["attemptCount"], $strDatetimeNow, $_POST["user"])
                );
                $dbHandle->runParameterizedQuery($incrementFailedQuery, null, $params);

                // if the user has 3 failed logins then lock the account
                if($userFailedLogins["attemptCount"] === 3) {
                    $lockQuery = "UPDATE users SET isLocked=1 WHERE userEmail = ?";
                    $params = array_merge(
                        array("s"),
                        array($_POST["user"])
                    );
                    $dbHandle->runParameterizedQuery($lockQuery, null, $params);
                }

                return array(
                    "Message" => "Incorrect Password"
                );
            }
            return array(
                "Message" => "You Have Logged In"
            );
        }
        protected function passwordInvalid($password) {
            return false;
        }
    }
?>