<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) && !in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause="WHERE `status` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("cash_flow")) && ($requestMethod == 'POST') && $posts->triggers=='cash_flow_report') {

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




$finances=$ezDb->get_results("SELECT * from `finances` $whereClause ORDER BY `id` DESC ");

$total_data = new stdClass();
$sales_data = [];
$purchases_data = [];
$tax_data = [];
$inventory_data = [];
$payroll_data = [];
$other_data = [];
$property_plant_data = [];
$other_invest_data = [];
$loans_data = [];
$owner_share_data = [];
$other_finance_data = [];


$operating_income = 0;
$operating_expense = 0;
$investing_income = 0;
$investing_expense = 0;
$financing_income = 0;
$financing_expense = 0;

//operating income
	//sales
	$total_sales = 0;
	$all_sales = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'income' GROUP BY `details` ");
	if(!empty($all_sales)){	
		foreach($all_sales as $value){
			$operating_income += $value->tamounts;
		}
	}
	$total_data->sales = $operating_income;
	$total_data->cash_inflow = $operating_income;
	$sales_data = $all_sales;


//operating expense
	//purchases
	$total_purchases = 0;
	$all_purchases = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'operating expense' GROUP BY `details` ");
	if(!empty($all_purchases)){	
		foreach($all_purchases as $value){
			$total_purchases += $value->tamounts;
		}
	}
	$total_data->purchases = $total_purchases;
	$operating_expense += $total_purchases;
	$purchases_data = $all_purchases;

	//inventory
	$total_inventory = 0;
	$all_inventory = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'inventory' GROUP BY `details` ");
	if(!empty($all_inventory)){	
		foreach($all_inventory as $value){
			$total_inventory += $value->tamounts;
		}
	}
	$total_data->inventory = $total_inventory;
	$operating_expense += $total_inventory;
	$inventory_data = $all_inventory;


	//payroll
	$total_payroll = 0;
	$all_payroll = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'payroll expenses' GROUP BY `details` ");
	if(!empty($all_payroll)){	
		foreach($all_payroll as $value){
			$total_payroll += $value->tamounts;
		}
	}
	$total_data->payroll = $total_payroll;
	$operating_expense += $total_payroll;
	$payroll_data = $all_payroll;

	$total_data->cash_outflow = $operating_expense;


	//net cashflow
	$total_data->op_profit = $total_data->cash_inflow - $total_data->cash_outflow;


//investing expense
	//property plant	
	$total_property_plant = 0;
	$all_property_plant = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'property, plant, equipment' GROUP BY `details` ");
	if(!empty($all_property_plant)){	
		foreach($all_property_plant as $value){
			$total_property_plant += $value->tamounts;
		}
	}
	$total_data->property_plant = $total_property_plant;
	$investing_expense += $total_property_plant;
	$property_plant_data = $all_property_plant;

	//other_inv	
	$total_other_invest = 0;
	$all_other_invest = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'other investment' GROUP BY `details` ");
	if(!empty($all_other_invest)){	
		foreach($all_other_invest as $value){
			$total_other_invest += $value->tamounts;
		}
	}
	$total_data->other_invest = $total_other_invest;
	$investing_expense += $total_other_invest;
	$other_invest_data = $all_other_invest;

	$total_data->invest = $investing_expense;


//financing expense
	//Loans Credit	
	$total_loans = 0;
	$all_loans = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'loans and lines of credit' GROUP BY `details` ");
	if(!empty($all_loans)){	
		foreach($all_loans as $value){
			$total_loans += $value->tamounts;
		}
	}
	$total_data->loans = $total_loans;
	$financing_income += $total_loans;
	$loans_data = $all_loans;


	//owners_shares	
	$total_owners_shares = 0;
	$all_owner_shares = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'business owner contribution and drawing' GROUP BY `details` ");
	if(!empty($all_owner_shares)){	
		foreach($all_owner_shares as $value){
			$total_owners_shares += $value->tamounts;
		}
	}
	$total_data->owner_share = $total_owners_shares;
	$financing_income += $total_owners_shares;
	$owner_share_data = $all_owner_shares;

	
	//other_finance
	$total_other_finance = 0;
	$all_other_finance = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'other finances' GROUP BY `details` ");
	if(!empty($all_other_finance)){	
		foreach($all_other_finance as $value){
			$total_other_finance += $value->tamounts;
		}
	}
	$total_data->other_finance = $total_other_finance;
	$financing_income += $total_other_finance;
	$other_finance_data = $all_other_finance;

	$total_data->finance = $financing_income;



	$total_data->cash_inflow += $financing_income;
	$total_data->net_cash = $total_data->cash_inflow - $total_data->cash_outflow;



$smarty->assign("total_data",$total_data)->assign("months",$months)->assign("filter",$filter)->assign("sales_data",$sales_data)->assign("purchases_data",$purchases_data)->assign("inventory_data",$inventory_data)->assign("payroll_data",$payroll_data)->assign("property_plant_data",$property_plant_data)->assign("other_invest_data",$other_invest_data);
$smarty->assign("loans_data",$loans_data)->assign("owner_share_data",$owner_share_data)->assign("other_finance_data",$other_finance_data);