<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$deptId = depttodepid("Marketing");
$employees=$ezDb->get_results("SELECT * FROM `employees` ORDER BY `employeeid` ASC");
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$filter = new stdClass();

$isadmin = false;

$whereClause = " WHERE `id` != 0 ";
if($userinfo->department == 'Marketing' && $userinfo->userrole == 'level2'){
	$isadmin = true;
}
if(in_array($userinfo->userrole, array('level0', 'level1'))){
	$isadmin = true;
}
$whereClause = " WHERE `id` != 0 ";
	if (in_array($sitePage, array("marketers_center")) && ($requestMethod == 'POST') && $posts->triggers=='commission_marketers') {
		
		if (empty($posts->year)):
			$whereClause .= "";
		else: 
			$whereClause .= " AND YEAR(`dateadded`) = '$posts->year' ";
			$filter->year = $posts->year;
		endif;
		if (empty($posts->month)):
			$whereClause .= "";
		else: 
			$whereClause .= " AND MONTHNAME(`dateadded`) = '$posts->month' ";
			$filter->month = $posts->month;
		endif;
		if (empty($posts->email)):
			if(!$isadmin){ 
				$ref_id = emailToId($userinfo->email);
				$whereClause .= " AND `email` = '$ref_id' ";
			}else{
				$whereClause .= "";
			}
		else:
			if(!$isadmin){
				$ref_id = emailToId($userinfo->email);
				$whereClause .= " AND `email` = '$ref_id' ";
				$filter->email = $userinfo->email;
			}else{
				$ref_id = emailToId($posts->email);
				//echo $ref_id;
				$whereClause .= " AND `email` = '$ref_id'";
				$filter->email = $posts->email;
			}
		endif;
	}
if(!$admin){
	$whereClause .= "AND `email` = '$userinfo->email' "; 
}
	$query = "SELECT * from `commission_payroll` $whereClause ORDER BY `dateadded` DESC ";
	$commissions=$ezDb->get_results($query);
	if(!empty($commissions)):
		foreach ($commissions as $value):
			if(!empty($value->subscription_token)){
				$value->subscription = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$value->subscription_token' ");
				$value->subscription->outstanding = ($value->subscription->total_amount <= $value->subscription->total_paid) ? 0 : $value->subscription->total_amount - $value->subscription->total_paid;
			}
			if(!empty($value->investment_token)){
				$value->investment = $ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token`='$value->investment_token' ");
			}
			if(!empty($value->email)){
				$value->employee = $ezDb->get_row("SELECT CONCAT(`lastname`,' ',`firstname`) AS names,`email`,`phone`  FROM `userprofile` WHERE `email`='$value->email' ");
			}
		endforeach;
	endif;
$smarty->assign("commissions", $commissions);
$smarty->assign("employees", $employees)->assign("months", $months)->assign("isadmin",$isadmin)->assign("filter",$filter);