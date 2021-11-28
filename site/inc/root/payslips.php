<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$employees=$ezDb->get_results("SELECT * from `employees` ORDER BY `employeeid` ASC");
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$filter = new stdClass();

$isadmin = false;

$whereClause = " WHERE `id` != 0 ";

if($userinfo->department == 'Human Resources' && $userinfo->userrole == 'level2'){
	$isadmin = true;
}
if(in_array( $userinfo->userrole, array('level0', 'level1'))){
	$isadmin = true;
}

if (in_array($sitePage, array("payslips")) && ($requestMethod == 'POST') && $posts->triggers=='payslips') {
	//$err=0;
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND `year` = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND `month` = '$posts->month' ";
		$filter->month = $posts->month;
	endif;
	if (empty($posts->employeeid)):
		if(!$isadmin){
			$whereClause .= " AND `employeeid` = '$userinfo->employeeid' ";
		}else{
			$whereClause .= "";
		}
	else:
		if(!$isadmin){
			$whereClause .= " AND `employeeid` = '$userinfo->employeeid' ";
			$filter->employeeid = $userinfo->employeeid;
		}else{
			$whereClause .= " AND `employeeid` = '$posts->employeeid' ";
			$filter->employeeid = $posts->employeeid;
		}
	endif;
}else{
	if(!$isadmin){
		$whereClause .= " AND `employeeid` = '$userinfo->employeeid' ";
	}
}

$query = "SELECT * from `payslips` $whereClause ORDER BY `id` DESC ";
$payslips=$ezDb->get_results($query);
//echo $query;
$smarty->assign("payslips", $payslips);

$smarty->assign("employees", $employees)->assign("months", $months)->assign("isadmin",$isadmin)->assign("filter",$filter);