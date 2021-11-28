<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) && !in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause="WHERE `status` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("balance_sheet")) && ($requestMethod == 'POST') && $posts->triggers=='cash_flow_report') {

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
	if (empty($posts->datesearch)):
		$whereClause .= " ";
	else:
		$filter = new stdClass();
		$whereClause = "WHERE `status` != 0";
		$whereClause.=" AND DATE_FORMAT(`transdate`, '%Y-%m-%d') <= '$posts->datesearch'";
		$filter->datesearch = $posts->datesearch;
	endif;

}else{
	$whereClause .= " AND YEAR(`transdate`) = '$dateY' ";
}




$finances=$ezDb->get_results("SELECT * from `finances` $whereClause ORDER BY `id` DESC ");

$total_data = new stdClass();
$cashnb_data = [];
$curassets_data = [];
$tax_data = [];
$long_asset_data = [];
$payroll_data = [];
$other_data = [];
$current_liability_data = [];
$long_liability_data = [];
$other_equity_data = [];
$retained_earnings_data = [];
$other_finance_data = [];


$operating_income = 0;
$assets_totals = 0;
$liabilities_totals = 0;
$investing_expense = 0;
$equity_totals = 0;
$financing_expense = 0;

//Assets
	//Cash and Bank
	$total_cashnb = 0;
	$all_cashnb = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'cash and bank' GROUP BY `details` ");
	if(!empty($all_cashnb)){	
		foreach($all_cashnb as $value){
			$total_cashnb += $value->tamounts;
		}
	}
	$total_data->cashnb = $total_cashnb;
	$assets_totals += $total_cashnb;
	$cashnb_data = $all_cashnb;


	//Other current assets
	$total_curassets = 0;
	$all_curassets = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details`,`type` from `finances` INNER JOIN `account_charts_cat` ON account_charts_cat.category = finances.sub_category  $whereClause AND `sub_category` != 'cash and bank' AND `sub_category` != 'other long-term asset' AND `type` = 'assets' GROUP BY `details` ");
	if(!empty($all_curassets)){	
		foreach($all_curassets as $value){
			$total_curassets += $value->tamounts;
		}
	}
	$total_data->curassets = $total_curassets;
	$assets_totals += $total_curassets;
	$total_data->to_be_received = $total_curassets;
	$curassets_data = $all_curassets;

	//long term assets
	$total_long_asset = 0;
	$all_long_asset = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances` INNER JOIN `account_charts_cat` ON account_charts_cat.category = finances.sub_category  $whereClause AND `sub_category` = 'other long-term asset'  AND `type` = 'assets' GROUP BY `details` ");
	if(!empty($all_long_asset)){	
		foreach($all_long_asset as $value){
			$total_long_asset += $value->tamounts;
		}
	}
	$total_data->long_asset = $total_long_asset;
	$assets_totals += $total_long_asset;
	$total_data->to_be_received += $total_long_asset;
	$long_asset_data = $all_long_asset;


	$total_data->assets = $assets_totals;



//Liabilities
	//current liabilities
	$total_current_liability = 0;
	$all_current_liability = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances` INNER JOIN `account_charts_cat` ON account_charts_cat.category = finances.sub_category $whereClause AND `sub_category` != 'other long-term liability' AND `type` = 'liabilities and credit cards' GROUP BY `details` ");
	if(!empty($all_current_liability)){	
		foreach($all_current_liability as $value){
			$total_current_liability += $value->tamounts;
		}
	}
	$total_data->current_liability = $total_current_liability;
	$liabilities_totals += $total_current_liability;
	$current_liability_data = $all_current_liability;

	//long-term liabilities	
	$total_long_liability = 0;
	$all_long_liability = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'other long-term liability' GROUP BY `details` ");
	if(!empty($all_long_liability)){	
		foreach($all_long_liability as $value){
			$total_long_liability += $value->tamounts;
		}
	}
	$total_data->long_liability = $total_long_liability;
	$liabilities_totals += $total_long_liability;
	$long_liability_data = $all_long_liability;

	$total_data->liabilities = $liabilities_totals;


	//total paid out
	$total_data->to_be_paid_out = $liabilities_totals;

	//total balance
	$total_data->balance_sheet_balance = $total_data->cashnb + $total_data->to_be_received - $total_data->to_be_paid_out;

//Equity	
	//other equity	
	$total_other_equity = 0;
	$all_other_equity = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'business owner contribution and drawing' GROUP BY `details` ");
	if(!empty($all_other_equity)){	
		foreach($all_other_equity as $value){
			$total_other_equity += $value->tamounts;
		}
	}
	$total_data->other_equity = $total_other_equity;
	$equity_totals += $total_other_equity;
	$other_equity_data = $all_other_equity;


	//retained earnings	
	$total_retained_earnings = 0;
	$all_retained_earnings = $ezDb->get_results("SELECT  SUM(`amount`) as tamounts, `details` from `finances`  $whereClause AND `sub_category` = 'retained earnings: profit' GROUP BY `details` ");
	if(!empty($all_retained_earnings)){	
		foreach($all_retained_earnings as $value){
			$total_retained_earnings += $value->tamounts;
		}
	}
	$total_data->retained_earnings = $total_retained_earnings;
	$equity_totals += $total_retained_earnings;
	$retained_earnings_data = $all_retained_earnings;

	$total_data->equity = $equity_totals;



$smarty->assign("total_data",$total_data)->assign("months",$months)->assign("filter",$filter)->assign("cashnb_data",$cashnb_data)->assign("curassets_data",$curassets_data)->assign("long_asset_data",$long_asset_data)->assign("payroll_data",$payroll_data)->assign("current_liability_data",$current_liability_data)->assign("long_liability_data",$long_liability_data);
$smarty->assign("other_equity_data",$other_equity_data)->assign("retained_earnings_data",$retained_earnings_data)->assign("other_finance_data",$other_finance_data);