<?php
//set session storage path
session_save_path((dirname(__FILE__) . '/storage'));
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include "lib/LeapSession.php";
include "lib/LeapView.php";
include "lib/LeapDB.php";
include "lib/LeapModel.php";
include "lib/LeapRequest.php";
include "lib/LeapController.php";
include "lib/LeapWebSocketServer.php";
include "lib/LeapEngine.php";
include "lib/LeapRouter.php";
include "conf/router.config.php";

$engine = new LeapEngine();
$engine->start($routes);
