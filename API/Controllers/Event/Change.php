<?php
    require("Controllers/AbstractController.php");

    class Change extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return "Insert Method Must Be POST";
            }
            
            if( !isset($_POST["type"])) {
                return "Please Set All Required Data";
            }
            if(count(array_filter(array_keys($_POST["type"]), 'is_string')) === 0) {
                return "Incorrect Format";
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
            $this->dbHandle->crud("eventTypes", "update", $_POST["type"]);

            return "Success";
        }
    }
?>