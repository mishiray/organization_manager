<?php


$token=(!empty($gets->id)? $gets->id: "");
$token = base64_decode($token);

	
$isadmin = 'yes';
if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ):	
    $isadmin = 'no';
	$whereClause .= "AND `email` = $token";
endif;

$employee = $ezDb->get_row("SELECT `userid`,`firstname`,`lastname`,`refid`,`email` FROM `userprofile` WHERE `email` = '$token'");

$filter = new stdClass();
if(!empty($employee)){
	$head = $employee;
	$holder = getConsultTree($employee->userid);
	$holder = json_encode($holder);
	$filter->consultant = $token;
}

$whereClause = " `id` != 0 AND `active` = 1 AND `usertype` = 'admin' OR (`usertype` = 'client' AND `userrole` = 'level1') ";
$consultant=$ezDb->get_results("SELECT * from `userprofile` WHERE $whereClause  ORDER BY RIGHT(userid,4) ASC");

$smarty->assign("head", $holder)->assign("consultants",$consultant)->assign("filter",$filter)->assign("main",$head);

