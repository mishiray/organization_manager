<?php 

$Site['post']= (array) $posts;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause="WHERE `id` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("finance_reports")) && ($requestMethod == 'POST') && $posts->triggers=='finance_report') {

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
	if (empty($posts->category)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND `category` = '$posts->category' ";
		$filter->category = $posts->category;
	endif;
	if (empty($posts->week)):
		$whereClause .= " AND FLOOR((DayOfMonth(`dateadded`)-1)/7)+1 =  FLOOR((DayOfMonth('$dateNow')-1)/7)+1";
	else:
		$whereClause .= " AND FLOOR((DayOfMonth(`dateadded`)-1)/7)+1  = '$posts->week'";
		$filter->week = $posts->week;
	endif;

}else{
	$whereClause .= " AND YEAR(`dateadded`) = '$dateY' AND MONTHNAME(`dateadded`) = '$dateM' ";
}

$query = "SELECT * FROM `finances` $whereClause ORDER BY `id` DESC";

$records=$ezDb->get_results($query);
if(!empty($records) and is_array($records)):
	$sumtotal=new stdClass();
	$sumtotal->credit=0.0;
	$sumtotal->debit=0.0;
	$sumtotal->balance=0.0;
	foreach ($records as $record):
		if($record->category == 'income'){
			$sumtotal->credit += $record->amount;
		}
		if($record->category == 'expense'){
			$sumtotal->debit += $record->amount;
		}
	endforeach;
	$sumtotal->balance = $sumtotal->credit - $sumtotal->debit ;
endif;
$smarty->assign("records", $records)->assign("sumtotal", $sumtotal)->assign("months",$months)->assign("filter",$filter);