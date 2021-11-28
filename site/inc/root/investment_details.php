<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;



if (in_array($sitePage, array("investment_details")) && ($requestMethod == 'POST') && $posts->triggers=='delete_inv') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `investment_subscription` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			$ezDb->query("DELETE FROM `payments` WHERE `token` = '$posts->token'");
			$ezDb->query("DELETE FROM `transactions` WHERE `transid` = '$posts->token'");
			logEvent($userinfo->email, "investment-client-deleted", $userinfo->usertype, 'investment_subscription', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Investment Item Deleted</p></div>';
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to delete. kindly re-try</p></div>';
		}
	else: 
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured. Invalid token</p></div>';
	endif;
}

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$projects = $ezDb->get_results("SELECT * FROM `projects`");
$whereClause=" WHERE `reg_id` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("investment_details")) && ($requestMethod == 'POST') && $posts->triggers=='inv_filter') {
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
	if (empty($posts->project_token)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND `product` = '$posts->project_token' ";
		$filter->product = $posts->project_token;
	endif;
	if (empty($posts->status)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND `status` = '$posts->status' ";
		$filter->status = $posts->status;
	endif;

}else{
	$whereClause .= " ";
}


$investment_details=$ezDb->get_results("SELECT * from `investment_subscription` $whereClause ORDER BY `reg_id` DESC");
if(!empty($investment_details) and is_array($investment_details)):
	foreach ($investment_details as $value):
		$duration = "+".$value->duration ."months";
		$end_date = date('Y-m-d', strtotime($duration, strtotime($value->reg_date)));
		$value->end_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
		$value->isDone = ($value->paid==1) ? (strtotime($value->end_date)<strtotime(date('Y-m-d'))) ? 'YES' : 'NO' : "Not Paid";
		$value->invest=$ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$value->investments_id'");
		//$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
	endforeach;
endif;
$smarty->assign("investment_details", $investment_details)->assign("months",$months)->assign("filter",$filter)->assign("projects",$projects);