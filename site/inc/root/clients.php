<?php

$userinfo=$Site["session"]["User"]["userinfo"];

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$whereClause=" WHERE `usertype`='client' AND `userrole`!='level0' AND `id` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("clients")) && ($requestMethod == 'POST') && $posts->triggers=='client_filter') {

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

}else{
	$whereClause .= " ";
}

$clients=$ezDb->get_results("SELECT * from `userprofile` $whereClause ORDER BY `id` DESC");

$smarty->assign("clients", $clients)->assign("months",$months)->assign("filter",$filter);