<?php

$userinfo=$Site["session"]["User"]["userinfo"];
//check access
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2','level3','level4')) && !in_array( $userinfo->department, array('Administrative', 'Accounting')) ):
	redirect("home");
endif;
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$filter = new stdClass();


$whereClause = " WHERE `id` != 0 ";
if (in_array($sitePage, array("commission_reports")) && ($requestMethod == 'POST') && $posts->triggers=='commission_payrolls') {
		
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
}

$count = 0;
$approved = 0;
$disapproved = 0;
$sent = 0;
$payrolls=$ezDb->get_results("SELECT COUNT(if(status = 1,1,NULL)) as total_disapproved, COUNT(if(status = 2,1,NULL)) as total_approved, COUNT(if(status = 3,1,NULL)) as total_sent, COUNT(total_pay) as total_commission,`status`,`month`, `year`, SUM(`total_pay`) AS `totals` from `commission_payroll` $whereClause GROUP BY `month`,`year` ORDER BY `id` DESC");

$smarty->assign("payrolls", $payrolls)->assign("months", $months)->assign("filter",$filter);