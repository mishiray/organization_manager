<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4'))  && !in_array($userinfo->department, array('Customer Service','Accounting','Administrative')) ):
	redirect("home");
endif;


	$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$projects = $ezDb->get_results("SELECT * FROM `projects`");
$whereClause=" WHERE `paid` <> 1 AND `reg_id` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("defaulters")) && ($requestMethod == 'POST') && $posts->triggers=='default_filter') {
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
	$whereClause .= " AND YEAR(`reg_date`) = '$dateY'  ";
}

$clients=$ezDb->get_results("SELECT * from `subscription` $whereClause ORDER BY `reg_date` DESC");
if(!empty($clients)){
	$total_outstanding = 0;
	foreach($clients as $value) {
		$value->date = $ezDb->get_row("SELECT DATEDIFF(CURDATE(),'$value->reg_date') AS `datediff`");
			$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
			$value->outstanding = ($value->total_amount<=$value->total_paid)?0:$value->total_amount-$value->total_paid;
			$total_outstanding += $value->outstanding;
			$value->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$value->reg_date')");
			$diff  = $value->datediff;
			$month = $diff / 30.5; 
			$month = round($month);
			$value->months = $month; 
	}
}

$smarty->assign("defclients", $clients)->assign("months",$months)->assign("filter",$filter)->assign("projects",$projects)->assign("total_outstanding",$total_outstanding);