<?php

$userinfo=$Site["session"]["User"]["userinfo"];


$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause="WHERE `status` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("profit-and-loss")) && ($requestMethod == 'POST') && $posts->triggers=='sales_report') {

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
/* SELECT * from `finances` INNER JOIN `account_charts` ON finances.details = account_charts.account INNER
	 JOIN account_charts_cat ON account_charts.category_id = account_charts_cat.id
*/
$finances=$ezDb->get_results("SELECT * from `finances` $whereClause ORDER BY `id` DESC ");
$total_data = new stdClass();
$income_data = [];
$goods_data = [];
$expense_data = [];
if(!empty($finances)){	
	$total_income = 0;
	$total_expense = 0;
	$total_goods = 0;
	$total_profit = 0;
	foreach($finances as $value){
		if($value->sub_category == 'income'){
			array_push($income_data, $value);
			$total_income += $value->amount;
		}
		if($value->sub_category == 'operating expense'){
			array_push($expense_data, $value);
			$total_expense += $value->amount;
		}
		if($value->sub_category == 'cost of goods sold'){
			array_push($goods_data, $value);
			$total_goods += $value->amount;
		}    
	}
	$total_profit = $total_income - $total_goods - $total_expense;
	$total_data->income = $total_income;
	$total_data->expense = $total_expense;
	$total_data->goods = $total_goods;
	$total_data->profit = $total_profit;
}

$total_sales = new stdClass();
$total_sales->this_week = $ezDb->get_var("SELECT SUM(`amount`) from `payments` $whereClause  ORDER BY `id` DESC");
//print_r($total_sales);
$smarty->assign("payments", $payments)->assign("total_data",$total_data)->assign("months",$months)->assign("filter",$filter)->assign("income_data",$income_data)->assign("goods_data",$goods_data)->assign("expense_data",$expense_data);