<?php
    require_once("protected/API Actions/baseAction.php");

    class readFailedLogins extends baseAction {
        function init() {
            return $this->actOnHandle($this->dbHandle, $_GET["user"]);
        }
        public function actOnHandle($readHandle, $user) {
            $query = "SELECT * FROM userFailedLogins WHERE userEmail = ?";

            $columnsArray = array("userEmail", "attemptCount", "attemptDatetime");

            $params = array_merge(
                array("s"),
                array($user)
            );
            
            $result = $readHandle->runParameterizedQuery($query, $columnsArray, $params);
            if(count($result) === 1) {
                return $result[0];
            }
            throw new exception("User Not Found");
        }
    }
?>