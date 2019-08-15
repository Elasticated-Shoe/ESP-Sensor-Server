<?php
    require_once("protected/API Actions/BaseRequest.php");

    class AddPermission extends BaseRequest {
        public $permission = "addPermission";

        function init() {
            $this->callInbuiltQuery(
                "INSERT INTO userpermissions(
                    username, readArchive, addPermission, createUser, loginUser
                ) VALUES (
                    ?, ?, ?, ?, ?
                );",
                null,
                array(
                    "siiii", $_POST["name"], $_POST["readArchive"], $_POST["addPermission"], 
                    $_POST["createUser"], $_POST["loginUser"]
                )
            );
            $this->data = "Permissions Added";
        }
    }
?>