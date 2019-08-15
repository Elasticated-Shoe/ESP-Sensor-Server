<?php
    include("protected/Database/Database.php");

    class SessionTracker {
        function init() {
            // https://stackoverflow.com/questions/22221807/session-cookies-http-secure-flag-how-do-you-set-these
            ini_set('session.cookie_httponly', 1); // prevents JavaScript XSS attacks stealing cookie
            ini_set('session.use_only_cookies', 1); // stops session ID being passed in URL
            ini_set('session.cookie_secure', 1); // HTTPS only
            ini_set('session.cookie_lifetime', 0); // cookie expires at end of browser session
            // ini_set("session.cookie_domain", "www.example.com"); // cookie only sent on subdomain
            session_start(); // start or resume new session with cookie
            // log session creation time so we can work out session age
            if(!isset( $_SESSION["sessionStart"] )) {
                $_SESSION["sessionStart"] = time();
            }
            if($this->hasExpired() == false) {
                session_unset();
                session_destroy();
                session_start();
            }
            $_SESSION['lastActivity'] = time(); // update last activity time stamp for the session
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
        function permission( $requiredPermission ) {
            $currentConnection = new Database("root", "", "localhost", "ESP_Project", null);
            $Connected = $currentConnection->connect();
            if($Connected !== false) {
                $rowCounts = $currentConnection->runParameterizedQuery(
                    "SELECT COUNT(username) FROM users;", 
                    array("username")
                );
                if( $rowCounts[0]["username"] === 0 ) {
                    return true; // permissions are ignored if there are no users
                }
            }
            else {
                $this->error = "Connection To The Database Failed";
            }
            if($requiredPermission === "readMetadata" || $requiredPermission === "loginUser") {
                return true;
            }
            if( isset($_SESSION["permissions"]) ) {
                if( in_array( $requiredPermission, $_SESSION["permissions"] ) ) {
                    return true;
                }
            }
            return false;
        }
    }
?>