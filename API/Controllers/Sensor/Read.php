<?php
    require("Controllers/AbstractController.php");

    class Read extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return "Insert Method Must Be GET";
            }
            return true;
        }
        function CheckPermission() {
            $DoesLoggedInUserMatchRequestedUser = $this->session->isUser();
            if(!$DoesLoggedInUserMatchRequestedUser) {
                return "You Are Not Logged In For That User";
            }
            return true;
        }
        function Action() {
            return $this->dbCache->getUsersSensorMetadata($this->session->getLogin());
        }
    }
?>