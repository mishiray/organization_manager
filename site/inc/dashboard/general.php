<?php

ob_start();
$command = basename(__FILE__);
date_default_timezone_set('Africa/Lagos');

require_once "../../../config.php";
require_once "../../../$libraries/functions.php";
require_once "../../../$libraries/ezsql/ez_sql_core.php";
require_once "../../../$libraries/ezsql/ez_sql_mysql.php";
require_once "../../../../db_config.php";
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
if($sitePage=="security" and !empty($posts->sd)){
	if( $data=$ezDb->get_row("SELECT `password`,`username` FROM `".$siteName."_users` WHERE (FROM_base64(`username`)='".$posts->sd['username']."' OR FROM_base64(`email`)='".$posts->sd['username']."') AND `userType`='client' AND `verified`='1';") ){
    	if( $data->password==sha1(md5(base64_encode($posts->sd['password']))) ){
    		$Site["session"]['Site']["User"]["security2"]=true;
    		$_SESSION=$Site["session"];
    		generateSecurityToken();
    		$response='success';
    	}else{
    		unset($Site["session"]['Site']["User"]["security2"]);
    		$_SESSION=$Site["session"];
    		$response='Invalid username or password';
    		nullifySecurityToken();
    	}
    }else{
		unset($Site["session"]['Site']["User"]["security2"]);
		$_SESSION=$Site["session"];
    	$response='Invalid username or password';
    	nullifySecurityToken();
    }
}elseif( in_array($sitePage, array("profile")) and !empty($posts->csd) ){
    if( $posts->csd['type']=="country" ){
        $response= json_encode( getStates($posts->csd['data']) );
    }elseif( $posts->csd['type']=="state" ){
        $response= json_encode( getCities($posts->csd['data']) );
    }
}

if(!empty($posts->evt)){
    if($posts->evt=='getsubs'):
        $response=getSubCategory($posts->slug);
    elseif( $posts->evt=='updateTime' and $sitePage=='exam'):
        $response=getExamTiming();
    elseif( $posts->evt=='updateTime2' and $sitePage=='test'):
        $response=getExamTiming2();
    elseif( $posts->evt=='getCities' ):
        $response=getDeliveryCities($posts->stateID);
    endif;
}

/*Return back to requester*/
if( in_array($posts->evt, array('getsubs', 'getCities', "updateTime", "updateTime2")) ){
    $response=json_encode($response);
}

if ( !empty($response) ) {
	echo $response;
}