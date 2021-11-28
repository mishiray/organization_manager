<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;


if (in_array($sitePage, array("subscriptions")) && ($requestMethod == 'POST') && $posts->triggers=='delete_sub') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `subscription` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			$ezDb->query("DELETE FROM `payments` WHERE `token` = '$posts->token'");
			$ezDb->query("DELETE FROM `transactions` WHERE `transid` = '$posts->token'");
			logEvent($userinfo->email, "subscription-client-deleted", $userinfo->usertype, 'subscription', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Subscription Item Deleted</p></div>';
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
if (in_array($sitePage, array("subscriptions")) && ($requestMethod == 'POST') && $posts->triggers=='sub_filter') {
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
		$whereClause .= " AND `project_token` = '$posts->project_token' ";
		$filter->product = $posts->project_token;
	endif;

}else{
	$whereClause .= " ";
}



$subscriptions=$ezDb->get_results("SELECT * from `subscription` $whereClause ORDER BY `reg_id` DESC");
if(!empty($subscriptions) and is_array($subscriptions)):
	foreach ($subscriptions as $value):
		$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
		$value->outstanding = ($value->total_amount<=$value->total_paid)?0:$value->total_amount-$value->total_paid;
		
		$value->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$value->reg_date')");
		$diff  = $value->datediff;
		$month = $diff / 30.5; 
		$month = round($month);
		$value->months = $month; 
	endforeach;
endif;
$smarty->assign("subscriptions", $subscriptions)->assign("months",$months)->assign("filter",$filter)->assign("projects",$projects);