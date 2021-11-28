<?php

$catTitle=array("commercial"=>"Commercial Property", "event-center"=>"Event Centers & Venues", "house-apartment"=>"Houses & Apartments", "land-plots"=>"Land & Plots");
$forProp=array("lease"=>"Lease | Rent", "sales"=>"Sales");

if(!empty($posts->triggers) and $posts->triggers=='showInterest'):
	if( empty($posts->names)):
		$fail='<p>Invalid Name: kindly enter your name</p>';
		$err++;
	endif;
	if( empty($posts->email) or !checkEmail($posts->email)):
		$fail='<p>Invalid Email: kindly enter a valid email</p>';
		$err++;
	endif;
	if( empty($posts->phone)):
		$fail='<p>Invalid Email: kindly enter your phone number</p>';
		$err++;
	endif;
	if( empty($ezDb->get_var("SELECT `token` FROM `real_estate` WHERE `token`='$posts->resToken'"))):
		$fail='<p>Invalid Property: cannot identify this business</p>';
		$err++;
	endif;

	if($err==0):
		$restate=$ezDb->get_row("SELECT * FROM `real_estate` WHERE `token`='$posts->resToken'");
		$userDetail=new stdClass;
		$userDetail->names=testInput($posts->names);
		$userDetail->phone=testInput($posts->phone);
		$userDetail=json_encode($userDetail);
		$comments=testInput($posts->comment);
		require_once 'mail_prop_poster.php';

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;

$whereClause='';

$realEstates=tableRoutine("real_estate", '*', " `status`='1' $whereClause", '`id`', 'DESC', "9");

if(!empty($realEstates) and is_array($realEstates)){
	foreach ($realEstates as $value) {
		$value->images=json_decode($value->images);
	}
}
$smarty->assign('catTitle', $catTitle)->assign('forProp', $forProp)->assign("realEstates", $realEstates);