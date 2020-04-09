<?php
    require("Controllers/AbstractController.php");

    class Insert extends AbstractController {
        function CheckInput() {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return "Insert Method Must Be POST";
            }
            
            if( !isset($_POST["sensorObject"])) {
                return "Please Set All Required Data";
            }
            if(count(array_filter(array_keys($_POST["sensorObject"]), 'is_string')) === 0) {
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
            $this->dbHandle->crud("sensorMetadata", "insert", $_POST["sensorObject"]);

            return "Success";
        }
    }
?>