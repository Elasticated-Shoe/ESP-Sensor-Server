<?php
    require_once("protected/Database/Database.php");

    class readUser {
        function init() {
            $readHandle = new DatabaseActions(
                $GLOBALS["Config"]["Database"]["User"], 
                $GLOBALS["Config"]["Database"]["Password"],
                $GLOBALS["Config"]["Database"]["Location"], 
                $GLOBALS["Config"]["Database"]["Database"]
            );

            $readHandle->connect();

            return $this->actOnHandle($readHandle, $_GET["user"]);
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