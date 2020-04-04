<?php
    require("Controllers/AbstractController.php");

    class readUser extends AbstractController {
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