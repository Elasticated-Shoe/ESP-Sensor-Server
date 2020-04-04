<?php
    class SessionTracker {
        private $apiTargetUser;

        public function init() {
            if(!isset($_SESSION)) {
                // https://stackoverflow.com/questions/22221807/session-cookies-http-secure-flag-how-do-you-set-these
                ini_set('session.cookie_httponly', 1); // prevents JavaScript XSS attacks stealing cookie
                ini_set('session.use_only_cookies', 1); // stops session ID being passed in URL
                ini_set('session.cookie_secure', 1); // HTTPS only
                ini_set('session.cookie_lifetime', 0); // cookie expires at end of browser session
                // ini_set("session.cookie_domain", "www.example.com"); // cookie only sent on subdomain
                session_start(); // start or resume new session with cookie
                // log session creation time so we can work out session age
                $_SESSION["sessionStart"] = time();
            }
            if(!$this->hasExpired()) {
                session_unset();
                session_destroy();
                session_start();
            }
            $_SESSION['lastActivity'] = time(); // update last activity time stamp for the session
        }
        public function isUser() {
            
        }
        public function setUser($user) {
            $this->apiTargetUser = $user;
        }
        public function getUser() {
            return $this->apiTargetUser;
        }
        public function setLogin($user) {
            $_SESSION["user"] = $user;
        }
        public function getLogin() {
            return $_SESSION["user"];
        }
        public static function endRequest($code, $message) {
            http_response_code($code);
                
            if($code >= 500) {
                $response = !$GLOBALS["Config"]["Debug"]    ? array("Message" => "Something Went Wrong")
                                                            : array (
                                                                "Message" => $message->getMessage(),
                                                                "File" => $message->getFile(),
                                                                "Line" => $message->getLine()
                                                            );
                die(json_encode($response));  
            }
            $response = is_array($message)  ? $message 
                                            : array (
                                                "Message" => $message
                                            );
            die(json_encode($response));
        }
        private function hasExpired() {
            // delete the browser session after 30 minutes of inactivity in case people leave tab open
            if(isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > 1800)) {
                return true;
            }
            // if a browser session has remained active, but is older than 12 hours, delete it
            if(time() - $_SESSION['sessionStart'] > 43200) {
                $_SESSION['sessionStart'] = time();
                return true;
            }
            return true;
        }
    }
?>