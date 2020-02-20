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
            else {
                http_response_code(405); // Return method not allowed if not POST or GET
                die("Method Not Allowed");
            }
            if( class_exists($apiAction) ) {
                $userAction = new $apiAction();
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
        public static function errorHandler($error) {
            http_response_code(500);

            die(
                json_encode(
                    array (
                        "Message" => $error->getMessage(),
                        "File" => $error->getFile(),
                        "Line" => $error->getLine()
                    )
                )
            );
        }
    }
    set_exception_handler("Main::errorHandler");

    Main::init();
?>