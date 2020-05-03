<?php
    require("Controllers/AbstractController.php");

    class Verify extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return "Method Must Be GET";
            }
            return true;
        }
        function CheckPermission() {
            return true;
        }
        function Action() {
            return $this->session->getLogin();
        }
    }
?>