<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3')) ):
	redirect("home");
endif;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	$filter = new stdClass();
if (in_array($sitePage, array("management_report")) && ($requestMethod == 'POST') && $posts->triggers=='reports') {
	//$err=0;
	$whereClause = " ";

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
	if (empty($posts->week)):
		$whereClause .= "";
	else:
		$whereClause .= " AND FLOOR((DayOfMonth(`dateadded`)-1)/7)+1  = '$posts->week'";
		$filter->week = $posts->week;
	endif;
	$whereClause .=(
		$userinfo->userrole=='level3'? "AND `user`='$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
			$userinfo->userrole=='level2'? "AND `status` IN('0', '1', '3', '4', '2')": (
				$userinfo->userrole=='level1'? "AND `status` IN( '1', '2', '4')": ""
			)
		)
	);
		
	$management_report=$ezDb->get_results("SELECT * from `management_report` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
	if(!empty($management_report)){
		foreach ($management_report as $value) {
			$value->name = $ezDb->get_row("SELECT `surname`, `first_name` FROM `employees` WHERE `employeeid` = '$value->employeeid'");
		}
	}
	$smarty->assign("management_report", $management_report)->assign("months", $months)->assign("filter",$filter);
}else{
	$whereClause=(
		$userinfo->userrole=='level3'? "AND `user`='$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
			$userinfo->userrole=='level2'? "AND `status` IN('0', '1', '3', '4', '2')": (
				$userinfo->userrole=='level1'? "AND `status` IN( '1', '2', '4')": ""
			)
		)
	);
	
	$management_report=$ezDb->get_results("SELECT * from `management_report` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
	if(!empty($management_report)){
		foreach ($management_report as $value) {
			$value->name = $ezDb->get_row("SELECT `surname`, `first_name` FROM `employees` WHERE `employeeid` = '$value->employeeid'");
			$value->contentln=testinputReverse($value->content);
			$value->content=testinputReverse($value->content);
			$value->content_stripe=stripeInputReverse($value->content);
			$value->content_stripe=str_replace("&quot;", "\"", $value->content_stripe);
			$value->content_stripe=str_replace("&nbsp;", " ", $value->content_stripe);
		}
	}
	$smarty->assign("management_report", $management_report)->assign("months", $months)->assign("filter",$filter);
}
	
$smarty->assign("management_report", $management_report)->assign("months", $months)->assign("filter",$filter);