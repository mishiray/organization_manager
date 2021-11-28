<?php 

$Site['post']= (array) $posts;
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause=" WHERE `id` != 0 AND `status` = 0 ";

$filter = new stdClass();
if (in_array($sitePage, array("aged_vendor_payables")) && ($requestMethod == 'POST') && $posts->triggers=='default_filter') {
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`date`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`date`) = '$posts->month' ";
		$filter->month = $posts->month;
	endif;

}else{
	$whereClause .= " AND YEAR(`date`) = '$dateY'  ";
}

$query = "SELECT * FROM `bills` $whereClause ORDER BY `id` DESC";
$bills = $ezDb->get_results($query);
$total_outstanding = 0;
if(!empty($bills)){
	
	foreach($bills as $value) {

		$paid=$ezDb->get_var("SELECT SUM(amount) AS `amount` FROM `bill_payments` WHERE `bill_token`='$value->token'");
		$value->outstanding = ($value->total_amount <= $paid) ? 0 : $value->total_amount - $paid;
		$total_outstanding += $value->outstanding;
		$value->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$value->due_date')");

	}
}
$smarty->assign("bills",$bills)->assign("months",$months)->assign("filter",$filter)->assign("total_outstanding",$total_outstanding);