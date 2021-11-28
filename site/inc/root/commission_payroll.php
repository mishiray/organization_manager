<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2')) ):
	redirect("home");
endif;


$month = (!empty($gets->month)) ? $gets->month : "";
$year = (!empty($gets->year)) ? $gets->year : "";
$whereClause = "AND `month` = '$month' AND `year` = '$year'";
$query = "SELECT * from `commission_payroll` WHERE `id` != 0 $whereClause ORDER BY `id` DESC ";

$payroll=$ezDb->get_results($query);

if (in_array($sitePage, array("commission_payroll")) and $requestMethod == 'POST'and isset($posts->triggers) and $posts->triggers=='approval') {
	$id = $gets->id;
	$posts = (object) $Site["post"];
	//$payroll = $ezDb->get_row("SELECT * from `commission_payroll` WHERE `payrollid`='$posts->payrollid';");
	$query = "UPDATE `commission_payroll` SET `status` = '$posts->active',`payee` = '$posts->payee',`pension` = '$posts->pension',`hmo` = '$posts->hmo',`loan` = '$posts->loan',`fines`='$posts->fines',`welfare`='$posts->welfare',`others`='$posts->others',`tax`='$posts->tax',`drtotal`='$posts->debtotal',`net_pay` = '$posts->post_net_pay' WHERE `payrollid`='$id'";
	//echo $query;
	if($ezDb->query($query)){
		$report=$ezDb->get_row("SELECT * from `commission_payroll` WHERE `id` != 0 $whereClause ORDER BY `id` DESC ");
		alertMd2(0,"Commission Payroll for $month $year Updated","commission_payroll?month=$month&year=$year");
		logEvent($userinfo->email, "commission-payroll-updated", $userinfo->usertype, 'commission_payroll', $report);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payroll has been updated.</p></div>';		
	}else{
		
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Failed to update.</p></div>';
	}
}

if (in_array($sitePage, array("commission_payroll")) and $requestMethod == 'POST'and isset($posts->triggers) and $posts->triggers=='disapproval') {
	$id = $gets->id;
	$posts = (object) $Site["post"];
	//$payroll = $ezDb->get_row("SELECT * from `commission_payroll` WHERE `payrollid`='$posts->payrollid';");
	$query = "UPDATE `commission_payroll` SET `status` = '$posts->disapprove',`payee` = '$posts->payee',`pension` = '$posts->pension',`hmo` = '$posts->hmo',`loan` = '$posts->loan',`fines`='$posts->fines',`welfare`='$posts->welfare',`others`='$posts->others',`tax`='$posts->tax',`drtotal`='$posts->debtotal',`net_pay` = '$posts->post_net_pay' WHERE `payrollid`='$id'";
	//echo $query;
	if($ezDb->query($query)){
		$report=$ezDb->get_row("SELECT * from `commission_payroll` WHERE `id` != 0 $whereClause ORDER BY `id` DESC ");
		logEvent($userinfo->email, "commission-payroll-updated", $userinfo->usertype, 'commission_payroll', $report);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payroll has been updated.</p></div>';		
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Failed to update.</p></div>';
	}
}

if (in_array($sitePage, array("commission_payroll")) and $requestMethod == 'POST' and isset($posts->triggers) and $posts->triggers=='generate_payslip') {
	$id = $gets->id;
	$err = 0;
	$posts = (object) $Site["post"];
	$payroll=$ezDb->get_row("SELECT * FROM `commission_payroll` WHERE `payrollid` = '$id';");
	$payroll->employeeid = $ezDb->get_var("SELECT `userid` FROM `userprofile` WHERE `email`='$payroll->email'");
	$salary_info = $ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `employeeid`='$payroll->employeeid'");
	if(empty($salary_info)){
		$cons = $ezDb->get_row("SELECT `bank_name`,`account_name`,`account_number`,`email` FROM `userprofile` WHERE `email`='$payroll->email'");
		if(empty($cons)){	
			$err++;	
			$fail='<div class="alert alert-error alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Please Update Employee salary record.</p></div>';		
		}else{
			$salary_info->name = $cons->account_name;
			$salary_info->bankname = $cons->bank_name;
			$salary_info->accnum = $cons->account_number;
		}
	}
	if($err == 0){
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `payslips` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `payslips` (`token`,`payrollid`,`employeeid`,`accname`,`bankname`,`position`,`accnum`,`month`,`year`,`salary`,`transport`,`housing`,`lunch`,`commission`,`payee`,`pension`,`hmo`,`loan`,`fines`,`welfare`,`others`,`tax`,`gross_total`,`drtotal`,`net_total`,`dateupdated`) VALUES ('$token','$id','$payroll->employeeid','$salary_info->name','$salary_info->bankname','','$salary_info->accnum','$payroll->month','$payroll->year',0,0,0,0,'$payroll->total_pay',$payroll->payee,$payroll->pension,$payroll->hmo,$payroll->loan,$payroll->fines,$payroll->welfare,$payroll->others,$payroll->tax,'$payroll->total_pay',$payroll->drtotal,'$payroll->net_pay','$dateNow');";
		//echo $query;
		if($ezDb->query($query)){
			$ezDb->query("UPDATE `commission_payroll` SET `status` = '$posts->active' WHERE `payrollid`='$id';");
			$report=$ezDb->get_row("SELECT * from `commission_payroll` WHERE `id` != 0 $whereClause ORDER BY `id` DESC ");
			logEvent($userinfo->email, "commission-payroll-updated", $userinfo->usertype, 'commission_payroll', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payslip has been sent.</p></div>';	
			alertUser($payroll->email,0,"Your Commission Payslip has been uploaded");
		}
	}
}


$month = (!empty($gets->month)) ? $gets->month : "";
$year = (!empty($gets->year)) ? $gets->year : "";
$whereClause = "AND `month` = '$month' AND `year` = '$year'";
$query = "SELECT * from `commission_payroll` WHERE `id` != 0 $whereClause ORDER BY `id` DESC ";
$payroll=$ezDb->get_results($query);
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


	if (in_array($sitePage, array("add_commission_payroll")) && ($requestMethod == 'POST') && isset($Site["post"]['add_payroll']) ) {
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$tableData = stripcslashes($_POST['allData']);
		//$tableData = json_decode($tableData,TRUE);
		if( empty(trim($posts->month))):
			$err++;
			$fail.='<p>Kindly enter month</p>';
		endif;
		if( empty(trim($posts->year))):
			$err++;
			$fail.='<p>Kindly enter year</p>';
		endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		$month = $posts->month;
		$year = $posts->year;
		if ($err==0) {
			
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
		
		//echo json_encode($tableData);
	}

	
if(!empty($payroll) and is_array($payroll)):
	foreach ($payroll as $value):
		$value->employee = $ezDb->get_row("SELECT * FROM `employees` WHERE `email`='$value->email'");
		$value->employeeid = $ezDb->get_var("SELECT `userid` FROM `userprofile` WHERE `email`='$value->email'");
		if(!empty($value->subscription_token)){
			$value->sub = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$value->subscription_token' ");
			$value->sub->outstanding = ($value->sub->total_amount <= $value->sub->total_paid) ? 0 : $value->sub->total_amount - $value->sub->total_paid;
		}
		if(!empty($value->investment_token)){
			$value->investment = $ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token`='$value->investment_token' ");
		}
		$value->salary_info = $ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `employeeid`='$value->employeeid'");
		if(empty($value->salary_info)){
			$cons = $ezDb->get_row("SELECT `bank_name`,`account_name`,`account_number`,`email` FROM `userprofile` WHERE `email`='$value->email'");
			if(!empty($cons)){
				$value->salary_info->name = $cons->account_name;
				$value->salary_info->bankname = $cons->bank_name;
				$value->salary_info->accnum = $cons->account_number;
			}
		}
		//print_r($value->salary_info);//$value->employee = $ezDb->get_row("SELECT * FROM `employees` WHERE `email`='$value->email'");
	endforeach;
endif;

$smarty->assign("payroll", $payroll)->assign("dateNow", $dateNow)->assign("month", $month)->assign("year", $year);

