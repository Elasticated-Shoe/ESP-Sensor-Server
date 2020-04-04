<?php
    require("Session Handling/SessionTracker.php");
    require("Router.php");

    class Main {
        public static function init() {
            $userSession = new SessionTracker();
            $userSession->init();

            $router = new Router();
            RouterBuilder::build($router);

            $apiAction = $router->getRouteFromUrl();

            $userSession->setUser($router->targetUser);

            if(!$apiAction) {
                SessionTracker::endRequest(404, "Route Not Found");
            }

            $apiAction = new $apiAction($userSession);

            $isInputValid = $apiAction->CheckInput();
            if($isInputValid !== true) {
                SessionTracker::endRequest(400, $isInputValid);
            }
            $isActionPermissable = $apiAction->CheckPermission();
            if($isActionPermissable !== true) {
                SessionTracker::endRequest(401, $isActionPermissable);
            }
            SessionTracker::endRequest(200, $apiAction->Action());
        }
        public static function errorHandler($error) {
            SessionTracker::endRequest(500, $error);
        }
    }
    // read in config options
    $configFileContents = file_get_contents("config.json");
    $GLOBALS["Config"] = json_decode($configFileContents, true);
    // I RETURN JSON
    //header('Content-Type: application/json');
    // disable notices
    if(!$GLOBALS["Config"]["Debug"]) {
        error_reporting(0);
    }
    // set an exception handler for all uncaught errors
    set_exception_handler("Main::errorHandler");

    // https://stackoverflow.com/questions/11990767/post-empty-when-curling-json
    // $_POST is an array that is only populated if you send the POST body in URL encoded format...
    $json = file_get_contents('php://input');
    $_POST = json_decode($json, true);

    Main::Init();
?>