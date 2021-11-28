<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3','level4')) ):
	redirect("home");
endif;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$dep_id = depttodepid($userinfo->department);
$filter = new stdClass();
if (in_array($sitePage, array("reports")) && ($requestMethod == 'POST') && $posts->triggers=='reports') {
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
	
	$whereClause=(
		$userinfo->userrole=='level4' ? "AND `user`='$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
			$userinfo->userrole=='level3' ? "AND `user` = (SELECT `email` FROM `employees` where manager_id = '$userinfo->employeeid')  OR  `user` = '$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
				$userinfo->userrole=='level2'? " AND `user` = (SELECT `email` FROM `employees` where department_id = '$dep_id' AND `email` = `user`) AND `status` IN('0', '1', '3', '4', '2')": (
					$userinfo->userrole=='level1'? "AND `status` IN('0', '1', '2', '4')": ""
				)
			)
		)
	);

	$reports=$ezDb->get_results("SELECT * from `reports` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
	if (!empty($reports)) {
		foreach ($reports as $key => $report) {
			$report->supervisor_review=(empty($report->supervisor_review)? new stdClass(): @json_decode($report->supervisor_review));
			$report->md_review=(empty($report->md_review)? new stdClass(): @json_decode($report->md_review));
			$report->marketerDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$report->user'");
			$report->comment1=testInputReverseln(trim($report->comment));
			$report->comment=testInputReverse(trim($report->comment));
			$report->content_stripe=stripeInputReverse($report->comment);

			$report->title1=testInputReverseln(trim($report->title));
			$report->title=testInputReverse(trim($report->title));
			
		}
	}
}else{
	$whereClause=(
		$userinfo->userrole=='level4' ? "AND `user`='$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
			$userinfo->userrole=='level3' ? "AND `user` = (SELECT `email` FROM `employees` where manager_id = '$userinfo->employeeid')  OR  `user` = '$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
				$userinfo->userrole=='level2'? " AND `user` = (SELECT `email` FROM `employees` where department_id = '$dep_id' AND `email` = `user`) AND `status` IN('0', '1', '3', '4', '2')": (
					$userinfo->userrole=='level1'? "AND `status` IN('0', '1', '2', '4')": ""
				)
			)
		)
	);
	$reports=$ezDb->get_results("SELECT * from `reports`  WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
	if (!empty($reports)) {
		foreach ($reports as $key => $report) {
			$report->supervisor_review=(empty($report->supervisor_review)? new stdClass(): @json_decode($report->supervisor_review));
			$report->md_review=(empty($report->md_review)? new stdClass(): @json_decode($report->md_review));
			$report->marketerDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$report->user'");
			$report->comment1=testInputReverseln(trim($report->comment));
			$report->comment=testInputReverse(trim($report->comment));
			$report->content_stripe=stripeInputReverse($report->comment);

			$report->title1=testInputReverseln(trim($report->title));
			$report->title=testInputReverse(trim($report->title));
		}
	}
	
	$smarty->assign("reports", $reports)->assign("months", $months)->assign("filter",$filter);

}

$smarty->assign("reports", $reports)->assign("months", $months);