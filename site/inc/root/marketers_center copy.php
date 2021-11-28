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

if($userinfo->department == 'Marketing' && $userinfo->userrole == 'level2'){
	$isadmin = true;
}
if(in_array( $userinfo->userrole, array('level0', 'level1'))){
	$isadmin = true;
}
$whereClause = " WHERE `reg_id` != 0 ";
if (in_array($sitePage, array("marketers_center")) && ($requestMethod == 'POST') && $posts->triggers=='commission_marketers') {
	//$err=0;
	//$whereClause = " WHERE `reg_id` != 0 ";
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
	if (empty($posts->email)):
		if(!$isadmin){ 
			$whereClause .= " AND `ref_email` = '$userinfo->email' ";
		}else{
			$whereClause .= "";
		}
	else:
		if(!$isadmin){
			$whereClause .= " AND `ref_email` = '$userinfo->email' ";
			$filter->email = $userinfo->email;
		}else{
			$whereClause .= " AND `ref_email` = '$posts->email'";
			$filter->email = $posts->email;
		}
	endif;
}

$query = "SELECT * from `subscription` $whereClause ORDER BY `reg_id` DESC ";
$clients_subs=$ezDb->get_results($query);
if(!empty($clients_subs) and is_array($clients_subs)):
	foreach ($clients_subs as $value):
		$ref_id = (!empty(emailToId($value->ref_email)) ? emailToId($value->ref_email) : '' );
		$value->projects = $ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$value->project_token'");
		$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
		$value->outstanding = ($value->total_amount<=$value->total_paid)?0:$value->total_amount-$value->total_paid;
		if(!empty($ref_id)){
			$value->commission = $ezDb->get_var("SELECT `commission` FROM `commission_payroll` WHERE `subscription_token`='$value->token' AND `employeeid` = '$ref_id' ");
		}else{
			$value->commission = 0;
		}
	endforeach;
endif;
	//investments
	$query = "SELECT * from `investment_subscription` $whereClause ORDER BY `reg_id` DESC ";
	$clients_inv=$ezDb->get_results($query);
	if(!empty($clients_inv) and is_array($clients_inv)):
		foreach ($clients_inv as $value):
			$ref_id = (!empty(emailToId($value->ref_email)) ? emailToId($value->ref_email) : '' );
			
			$value->invest = $ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$value->investments_id'");
			if(!empty($ref_id)){
				$value->commission = $ezDb->get_var("SELECT `commission` FROM `commission_payroll` WHERE `investment_token`='$value->token' AND `employeeid` = '$ref_id' ");
			}else{
				$value->commission = 0;
			}
		endforeach;
	endif;

$smarty->assign("clients", $clients_subs)->assign("clients_inv", $clients_inv);

$smarty->assign("employees", $employees)->assign("months", $months)->assign("isadmin",$isadmin)->assign("filter",$filter);