<?php 

$whereClause = '';
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
	$whereClause = "AND `addedby`='$userinfo->email'";
endif;

$id=(!empty($gets->id)? $gets->id: "");
$request=$ezDb->get_row("SELECT * FROM `property_request` WHERE `token`='$id' $whereClause");
if (empty($request)) {
	redirect("property-requests");
}
$request->description2=testInputReverseln(trim($request->description));
$request->description=testInputReverse(trim($request->description));
$request->remarks2=testInputReverseln(trim($request->remarks));
$request->remarks=testInputReverse(trim($request->remarks));
		
$smarty->assign("request", $request);