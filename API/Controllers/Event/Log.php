<?php
    require("Controllers/AbstractController.php");

    class Log extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return "Insert Method Must Be POST";
            }
            
            if( !isset($_POST["event"])) {
                return "Please Set All Required Data";
            }
            if(count(array_filter(array_keys($_POST["event"]), 'is_string')) === 0) {
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
            $this->dbHandle->crud("eventLog", "insert", $_POST["event"]);

            return "Success";
        }
    }
?>