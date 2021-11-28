<?php

ob_start();
$command = basename(__FILE__);
date_default_timezone_set('Africa/Lagos');

require_once "../../config.php";
require_once "../../$libraries/functions.php";
require_once "../../$libraries/ezsql/ez_sql_core.php";
require_once "../../$libraries/ezsql/ez_sql_mysqli.php";
require_once "../../../db_config.php";
foreach ($myGlobals as $key => $value) {
    $Site["$key"] = $value;
}
$Site["session"]=$_SESSION;
$Site["cookie"]=$_COOKIE;
$Site["post"]=$_POST;
$Site["get"]=$_GET;
$response=null;
//Your checkings starts here
$posts=(object)$Site["post"];
$gets=(object)$Site["get"];
$sessions=(object)$Site["session"];

$sitePage=null;
if( !empty($posts->sp) or !empty($gets->sp)){
	$sitePage=(!empty($posts->sp)? $posts->sp: $gets->sp);
}
if( in_array($sitePage, array("signup")) and !empty($posts->csd) ){
    if( $posts->csd['type']=="country" ){
        $response= json_encode( getStates($posts->csd['data']) );
    }elseif( $posts->csd['type']=="state" ){
        $response= json_encode( getCities($posts->csd['data']) );
    }
}


/*Return back to requester
if( in_array($posts->evt, array('getsubs', 'getCities')) ){
    $response=json_encode($response);
}
*/


if ( !empty($response) ) {
	echo $response;
}