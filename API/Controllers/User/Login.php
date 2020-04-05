<?php
    require("Controllers/AbstractController.php");

    class Login extends AbstractController {
        private $user;
        private $userAttempts;
        private $strDatetimeNow;

        function __construct($session) {
            $this->strDatetimeNow = date("Y-m-d H:i:s");

            parent :: __construct($session);
        }

        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return "Login Method Must Be Post";
            }
            if(!isset($_POST["user"]) || !isset($_POST["password"])) {
                return "Please Provide A Username And A Password";
            }
            $this->user = $this->dbCache->findUser($_POST["user"]);
            if(!$this->user) {
                return "User Not Found";
            }
            if($this->passwordInvalid($_POST["password"])) {
                return "Password Failed Validation";
            }
            return true;
        }
        function CheckPermission() {
            $this->userAttempts = $this->dbCache->readAttempts($_POST["user"]);
            if($this->userAttempts) {
                // if failed logins exist and are old clear them
                if($this->userAttempts["attemptCount"] > 0) {
                    $lastFailedLoginDatetime = strtotime($this->userAttempts["attemptDatetime"]);
                    $datetimeNow = strtotime($this->strDatetimeNow);
                    $timeAgoInMin = abs($datetimeNow - $lastFailedLoginDatetime) / 60;
                    if($timeAgoInMin > 10) {
                        $this->dbCache->updateAttempts($_POST["user"]);

                        $this->user["isLocked"] = 0;
                        $this->userAttempts["attemptCount"] = 0;
                    }
                }
            }
            // don't allow login for locked accounts
            if($this->user["isLocked"]) {
                return "User Account Is Locked, Try Again Later";
            }
            return true;
        }
        function Action() {
            $preHashPass = $this->preHash($_POST["password"]);
            if(!password_verify($preHashPass, $this->user["userPass"])) {
                $this->dbCache->incrementAttempts($this->userAttempts, $this->strDatetimeNow, $this->user);

                return "Incorrect Password";
            }

            $this->session->setLogin($_POST["user"]);

            return "You Have Logged In";
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