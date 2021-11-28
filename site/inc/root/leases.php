<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;


if (in_array($sitePage, array("leases")) && ($requestMethod == 'POST') && $posts->triggers=='delete_sub') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `lease` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `lease` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			logEvent($userinfo->email, "lease-client-deleted", $userinfo->usertype, 'lease', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Lease Item Deleted</p></div>';
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to delete. kindly re-try</p></div>';
		}
	else: 
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured. Invalid token</p></div>';
	endif;
}

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$whereClause=" WHERE `id` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("leases")) && ($requestMethod == 'POST') && $posts->triggers=='sub_filter') {
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`reg_date`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`reg_date`) = '$posts->month' ";
		$filter->month = $posts->month;
	endif;
}else{
	$whereClause .= " ";
}



$subscriptions=$ezDb->get_results("SELECT * from `lease` $whereClause ORDER BY `id` DESC");
$smarty->assign("subscriptions", $subscriptions)->assign("months",$months)->assign("filter",$filter);