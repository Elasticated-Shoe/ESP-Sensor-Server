<?php
    require("protected/Session Handling/SessionTracker.php");
    require("protected/API Actions/RecentReadings.php");

    $userSession = new SessionTracker();
    $userSession->init();
    
    $userAction = new RecentReadings(false);
    $userAction->init();
?>