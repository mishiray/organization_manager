<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
	redirect("home");
endif;

$whereClause="WHERE `status` = 1 ";
$filter = new stdClass();
if (in_array($sitePage, array("yearly_sales_report")) && ($requestMethod == 'POST') && $posts->triggers=='yearly_report') {

	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`transdate`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
}else{
	$filter->year = $dateY;
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
$mkdate = "May 05 $filter->year";
$new_date=date('Y-m-d', strtotime($mkdate));
$total_sales->this_year = $ezDb->get_var("SELECT SUM(`amount`) from `payments` $whereClause  ORDER BY `id` DESC");
$total_sales->last_year = $ezDb->get_var("SELECT SUM(`amount`) from `payments` WHERE YEAR(`transdate`) = YEAR(DATE_SUB(DATE_FORMAT('$new_date', '%Y-%m-%d'), INTERVAL 1 Year)) AND `status` = 1 ORDER BY `id` DESC");
$total_sales->percent = ($total_sales->this_year - $total_sales->last_year) / $total_sales->this_year * 100;
$smarty->assign("payments", $payments)->assign("total_sales",$total_sales)->assign("filter",$filter);