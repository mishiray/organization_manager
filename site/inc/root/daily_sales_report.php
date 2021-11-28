<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
	redirect("home");
endif;

$whereClause="WHERE `status` = 1 ";
if (in_array($sitePage, array("daily_sales_report")) && ($requestMethod == 'POST') && $posts->triggers=='daily_report') {
	$whereClause.=" AND DATE_FORMAT(`transdate`, '%Y-%m-%d') = '$posts->datesearch'";
	$datesearch = $posts->datesearch;
}
else{
	$whereClause.="AND DATE_FORMAT(`transdate`, '%Y-%m-%d') = '$dateNow'";
	$datesearch = $dateNow2;
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
$total_sales->today = $ezDb->get_var("SELECT SUM(`amount`) from `payments` $whereClause  ORDER BY `id` DESC");
$total_sales->yesterday = $ezDb->get_var("SELECT SUM(`amount`) from `payments` WHERE DATE_FORMAT(`transdate`, '%Y-%m-%d') = DATE_SUB(DATE_FORMAT('$datesearch', '%Y-%m-%d'), INTERVAL 1 DAY) AND `status` = 1 ORDER BY `id` DESC");
$total_sales->percent = ($total_sales->today - $total_sales->yesterday) / $total_sales->today * 100;
//print_r($total_sales);
$smarty->assign("payments", $payments)->assign("datesearch",$datesearch)->assign("total_sales",$total_sales);