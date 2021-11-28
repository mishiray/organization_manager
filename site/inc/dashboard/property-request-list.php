<?php 

$userinfo=$Site['session']['User']['userinfo'];

$whereClause ="AND `addedby`='$userinfo->email'";
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