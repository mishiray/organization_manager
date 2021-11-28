<?php

$userinfo=$Site["session"]["User"]["userinfo"];
$table=(!empty($gets->type)? $gets->type: "");
$sub_table = ($table == 'subscription') ? 'projects' : 'agriculture';

if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4'))   && !in_array($userinfo->department, array('Accounting','Customer Service','Administrative'))):
	redirect("home");
endif;

$clients=$ezDb->get_results("SELECT * from `$table` GROUP BY `email` ORDER BY `surname`,`firstname` ASC");

$properties=$ezDb->get_results("SELECT * from `$sub_table` WHERE `active` = 1");

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$isadmin = false;

if($userinfo->department == 'Accounting' && $userinfo->userrole == 'level2'){
	$isadmin = true;
}
if(in_array( $userinfo->userrole, array('level0', 'level1'))){
	$isadmin = true;
}


$whereClause = " WHERE `reg_id` != 0";
$query = "SELECT * from `$table` $whereClause ORDER BY `surname`,`firstname` ASC";
$subscriptions=$ezDb->get_results($query);

if (in_array($sitePage, array("invoices")) && ($requestMethod == 'POST') && $posts->triggers=='invoices') {
	//$err=0;
	$whereClause = " WHERE `reg_id` != 0 ";
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND Year(`reg_date`) = '$posts->year' ";
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`reg_date`) = '$posts->month' ";
	endif;
	if (empty($posts->clientemail)):
		$whereClause .= "";
	else:
		$whereClause .= " AND `email` = '$posts->clientemail' ";
	endif;
	if (empty($posts->property)):
		$whereClause .= "";
	else:
		$whereClause .= " AND `project_token` = '$posts->property' ";
	endif;
	$query = "SELECT * from `$table` $whereClause ORDER BY `reg_id` DESC ";
	
	$subscriptions=$ezDb->get_results($query);
}

if(!empty($subscriptions)){
	foreach($subscriptions as $value){
		$value->property= $ezDb->get_row("SELECT * from `$sub_table` WHERE `token` = '$value->project_token'");
		$value->invoices = $ezDb->get_row("SELECT * from `invoices` WHERE `subscriptionid` = '$value->token' GROUP BY `subscriptionid` ORDER BY `id` ");
	}
}

$smarty->assign("subscriptions", $subscriptions);
$smarty->assign("clients", $clients)->assign("properties", $properties)->assign("months", $months)->assign("isadmin",$isadmin);