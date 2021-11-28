<?php 

$id=(!empty($gets->id)? $gets->id: "");

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3')) ):
	redirect("home");
endif;

$report=$ezDb->get_row("SELECT * FROM `payroll` WHERE `payrollid`='$id'");
$report->user = $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid`='$report->employeeid'");

if (!empty($report)) {

	if (in_array($sitePage, array("payroll")) && ($requestMethod == 'POST') && isset($Site["post"]['update_payroll']) ) {
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$tableData = stripcslashes($_POST['allData']);

		if(!in_array($userinfo->userrole, array('level0','level2'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		if ($err==0) {
			//$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
			$query = "UPDATE `payroll` SET `data` = '$tableData' WHERE `payrollid` = '$report->payrollid' ";
			if($ezDb->query($query)){
				$report=$ezDb->get_row("SELECT * FROM `payroll` WHERE `payrollid`='$report->payrollid';");
				  logEvent($userinfo->email, "payroll-updated", $userinfo->usertype, 'payroll', $report);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payroll record has been successfully updated.</p></div>';
			}
			else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to update payroll</div>';
			}
			//$report=$ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `reportid`='$token';");
			//logEvent($userinfo->email, "new-report-added", $userinfo->usertype, 'reports', $report);
			  
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
		
	}
	
	if ( in_array($sitePage, array("payroll")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( (in_array($report->status, array('0','3'))) 
		or (in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1')
	 	or $userinfo->userrole=='level0') ) {
		if (!empty($report) and $gets->evt=='delete') {
			$ezDb->query("DELETE FROM `payroll` WHERE `payrollid`='$id'");
			header('Location: payrolls');
      		logEvent($userinfo->email, "payroll-deleted", $userinfo->usertype, 'payroll', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payroll was successfully deleted.</p></div>';
		}
	}
	

	if ( in_array($sitePage, array("payroll")) && ($requestMethod == 'POST') && (isset($posts->lreview) or isset($posts->mreview)) and ( (in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or 
		(in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1')
	 or $userinfo->userrole=='level0') ) {

		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Your comment is required please!</p>';
		endif;

		if( (!in_array($posts->status, array('1', '3')) and isset($posts->lreview) and 
			( (in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		elseif( (!in_array($posts->status, array('2', '4')) and isset($posts->mreview) and 
			( (in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		endif;

		if ($err==0) {
			$revDet=new stdClass();
			$revDet->user=$userinfo->email;
			$revDet->comment=$posts->comment;
			$revLog='manager';
			if( ((in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0') and isset($posts->lreview) ):
				$ezDb->query("UPDATE `payroll` SET `status`='$posts->status', `manager_review`='".@json_encode($revDet)."' WHERE `payrollid`='$id'");
				alertUser($report->user,0,"Payroll for $report->month $report->year has been reviewed by Manager");
				alertAccountingManager(0,"Payroll for $report->month $report->year has been reviewed by Manager");
				alertMd2(0,"Payroll for $report->month $report->year has been reviewed by Manager","payroll?id=$id");

			elseif( ((in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0') and isset($posts->mreview) ):
				$revLog='md';
				$ezDb->query("UPDATE `payroll` SET `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `payrollid`='$id'");
				alertUser($report->user,0,"Payroll for $report->month $report->year has been reviewed by MD");
				alertAccountingManager(0,"Payroll  for $report->month $report->year has been reviewed by MD");
				alertMd2(0,"Payroll for $report->month $report->year has been reviewed by MD","payroll?id=$id");
			endif;
      		logEvent($userinfo->email, "report-reviewed-by-$revLog", $userinfo->usertype, 'reports', $revDet);
			$report=$ezDb->get_row("SELECT * FROM `payroll` WHERE `payrollid`='$id'");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payroll was successfully reviewed.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	

	$tableData = $report->data;
	//$jsonData= json_decode($tableData, true);
	//echo $tableData;
	$json  = json_decode($tableData, true);
	//$json = isset($json[0])?$json[0]:'';
	//$this->logger->info("Start update_new_data ->");
	$val = new stdClass();
	$myArray = [];
	if(!empty($json)){
		$num = 0;
		foreach ($json as $Key=>$value) {
			array_push($myArray, (object)[
				'empId' => $value['empId'],
				'name' => $value['name'],
				'bankname' => $value['bankname'],
				'accnum' => $value['accnum'],
				'position' => $value['position'],
				'salary' => $value['salary'],
				'per_inc' => $value['per_inc'],
				'naira_inc' => $value['naira_inc'],
				'transport' => $value['transport'],
				'housing' => $value['housing'],
				'lunch' => $value['lunch'],
				'reason' => $value['reason'],
				'commission' => $value['commission'],
				'gross_total' => $value['gross_total'],
				'loan' => $value['loan'],
				'payee' => $value['payee'],
				'pension' => $value['pension'],
				'hmo' => $value['hmo'],
				'fines' => $value['fines'],
				'welfare' => $value['welfare'],
				'others' => $value['others'],
				'tax' => $value['tax'],
				'drtotal' => $value['drtotal'],
				'net_total' => $value['net_total']
			]);
		}
	}
	//print_r($myArray);
	
	$report->manager_review=(empty($report->manager_review)? new stdClass(): @json_decode($report->manager_review));
	$report->manager=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->manager_review->user."'");
	$report->md_review=(empty($report->md_review)? new stdClass(): @json_decode($report->md_review));
	$report->md=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->md_review->user."'");
	$report->HrDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$report->user'");

}else{
	redirect('payrolls');
} 
$smarty->assign("report", $report)->assign("val", $myArray);