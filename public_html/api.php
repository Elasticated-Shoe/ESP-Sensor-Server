<?php
    require("protected/Session Handling/SessionTracker.php");

    class Main {
        public static function init() {
            spl_autoload_register("Main::autoloader");

            $userSession = new SessionTracker();
            $userSession->init();

            if($_SERVER['REQUEST_METHOD'] === 'GET') {
                $apiAction = $_GET["action"];
            }
            elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
                $apiAction = $_POST["action"];
            }
            else {
                http_response_code(405); // Return method not allowed if not POST or GET
                die("Method Not Allowed");
            }
            if( class_exists($apiAction) ) {
                $userAction = new $apiAction(false);
                $userAction->init();
                $userAction->readyResponse();
                echo $userAction->response;
            }
            else {
                echo json_encode(
                    array(
                        "error" => "Action Not Found"
                    )
                );
            }
        }
        public static function autoloader($class) {
            $actionsDir = 'protected/API Actions/';
            $directories = glob($actionsDir . '*' , GLOB_ONLYDIR);
            foreach($directories as $dir) {
                $classFile = $dir . "/" . $class . '.php';
                if( file_exists($classFile) ) {
                    include $classFile;
                    break;
                }
            }
        }
    }

    Main::init();
?>