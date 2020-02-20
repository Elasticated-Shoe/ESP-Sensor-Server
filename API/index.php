<?php
    require("protected/Session Handling/SessionTracker.php");

    class Main {
        public static function init() {
            spl_autoload_register("Main::autoloader");

            $userSession = new SessionTracker();
            $userSession->init();

            if( isset($_GET["action"]) ) {
                $apiAction = $_GET["action"];
            }
            else {
                http_response_code(400); // no API action has been defined

                die(
                    json_encode(
                        array (
                            "Message" => "No Action Has Been Defined In The Query String"
                        )
                    )
                );
            }
            if( class_exists($apiAction) ) {
                $userAction = new $apiAction();
                $userAction->init();

                $userAction->readyResponse();
                echo $userAction->response;
            }
            else {
                http_response_code(400); // an invalid API action has been defined

                die(
                    json_encode(
                        array (
                            "Message" => "Action Has Not Been Found"
                        )
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
    // disable notices
    error_reporting(0);
    // set an exception handler for all uncaught errors
    set_exception_handler("Main::errorHandler");

    Main::init();
?>