<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) && !in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause="WHERE `status` = 1 ";
$filter = new stdClass();
if (in_array($sitePage, array("sales_report")) && ($requestMethod == 'POST') && $posts->triggers=='sales_report') {

	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`transdate`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`transdate`) = '$posts->month' ";
		$filter->month = $posts->month;
	endif;
	if (empty($posts->week)):
		$whereClause .= " AND FLOOR((DayOfMonth(`transdate`)-1)/7)+1 =  FLOOR((DayOfMonth('$dateNow')-1)/7)+1";
	else:
		$whereClause .= " AND FLOOR((DayOfMonth(`transdate`)-1)/7)+1  = '$posts->week'";
		$filter->week = $posts->week;
	endif;

}else{
	$whereClause .= " AND YEAR(`transdate`) = '$dateY' ";
}

$payments=$ezDb->get_results("SELECT * from `payments` $whereClause ORDER BY `id` DESC ");
if(!empty($payments)){	
	foreach($payments as $value){
		if(strpos($value->purpose, 'investment') !== false){
			$value->details=$ezDb->get_row("SELECT `surname`, `firstname`, `product` FROM `investment_subscription` WHERE `token` = '$value->token' "); 
			
		}else{
			$value->details=$ezDb->get_row("SELECT `surname`, `firstname`, `product` FROM `subscription` WHERE `token` = '$value->token'"); 
		}  
		$value->details = (empty($value->details)) ? ' ' : $value->details;      
	}
}

$total_sales = new stdClass();
$total_sales->this_week = $ezDb->get_var("SELECT SUM(`amount`) from `payments` $whereClause  ORDER BY `id` DESC");
//print_r($total_sales);
$smarty->assign("payments", $payments)->assign("total_sales",$total_sales)->assign("months",$months)->assign("filter",$filter);