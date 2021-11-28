<?php

ob_start();
$command = basename(__FILE__);
date_default_timezone_set('Africa/Lagos');

require_once "config.php";
//Load Smarty
require_once "clear_cache.php";
require_once "$libraries/functions.php";
require_once "$libraries/ezsql/ez_sql_core.php";
require_once "$libraries/ezsql/ez_sql_mysqli.php";
require_once "../db_config.php";
@session_start();
// @session_destroy();
// require_once "$libraries/url.php";
//Load variables in config.php into $Site array
foreach ($myGlobals as $key => $value) {
    $Site["$key"] = $value;
}
// $_SESSION['tempToken']='5JWbJ6W845iEp47';
$Site["session"]=$_SESSION;
$Site["cookie"]=$_COOKIE;
$Site["post"]=$_POST;
$Site["get"]=$_GET;
$Site["files"]=$_FILES;
$Site["server"]=$_SERVER;

/*Cron Job Code Starts Here*/
// scheduleRoutineGenerator();

/*Cron Job Code Ends Here*/