<?php
    require_once("protected/API Actions/baseAction.php");

    class readUser extends baseAction {
        function init() {
            return $this->actOnHandle($this->dbHandle, $_GET["user"]);
        }
        public function actOnHandle($readHandle, $user) {
            $query = "SELECT * FROM users WHERE userEmail = ?";

            $columnsArray = array("userEmail", "userPass", "isLocked");

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