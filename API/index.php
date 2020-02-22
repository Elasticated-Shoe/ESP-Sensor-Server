<?php
    require("protected/Session Handling/SessionTracker.php");

    class Main {
        public static function init() {
            spl_autoload_register("Main::autoloader", true);

            $userSession = new SessionTracker();
            $userSession->init();

            # https://stackoverflow.com/questions/11990767/post-empty-when-curling-json
            # $_POST is an array that is only populated if you send the POST body in URL encoded format...
            $json = file_get_contents('php://input');
            $_POST = json_decode($json, true);

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
                echo json_encode( $userAction->init() );
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
            $classFile = $actionsDir . $class . '.php';
            if(file_exists($classFile)) {
                include $classFile;
            }
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

            if($GLOBALS["Config"]["Debug"]) {
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
            die(
                json_encode(
                    array (
                        "Message" => "Something Went Wrong"
                    )
                )
            );
        }
    }
    // read in config options
    $configFileContents = file_get_contents("config.json");
    $GLOBALS["Config"] = json_decode($configFileContents, true);
    // I RETURN JSON
    header('Content-Type: application/json');
    // disable notices
    if(!$GLOBALS["Config"]["Debug"]) {
        error_reporting(0);
    }
    // set an exception handler for all uncaught errors
    set_exception_handler("Main::errorHandler");

    Main::init();
?>