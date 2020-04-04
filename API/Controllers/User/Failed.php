<?php
    require("Controllers/AbstractController.php");

    class readFailedLogins extends AbstractController {
        function CheckInput() {
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
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