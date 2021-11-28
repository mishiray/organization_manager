<?php 
$whereClause = '';
if( !in_array( $userinfo->userrole, array('level0','level1','level2'))   && !in_array($userinfo->department, array('Customer Service'))):
	$whereClause = "AND `addedby`='$userinfo->email'";
endif;


$requests=tableRoutine("property_request", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', 10);
if (!empty($requests)) {
	foreach ($requests as $key => $request) {
		$request->description2=testInputReverseln(trim($request->description));
		$request->description=testInputReverse(trim($request->description));
		$request->remarks2=testInputReverseln(trim($request->remarks));
		$request->remarks=testInputReverse(trim($request->remarks));
	}
}
$smarty->assign("requests", $requests);