<?php

$userinfo=$Site["session"]["User"]["userinfo"];
//check access
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) && !in_array( $userinfo->department, array('Administrative', 'Accounting')) ):
	redirect("home");
endif;
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$filter = new stdClass();


if (in_array($sitePage, array("commissions_payrolls")) && ($requestMethod == 'GET') && isset($Site["get"]['evt'])  && isset($Site["get"]['id']) ){
	$id = (!empty($gets->id)) ? $gets->id : "";
	if($payroll=$ezDb->get_row("SELECT * from `commissions_payroll` WHERE `payrollid` = '$id'"))
	{
		if (!empty($payroll) and $gets->evt=='generate') {
			$tableData = $payroll->data;
			$json  = json_decode($tableData, true);
			//print_r($json);
			if(!empty($json)){
				$num = 0;
				$updtednum = 0;
				foreach ($json as $Key=>$value) {
					$employeeid = $ezDb->get_var("SELECT `userid` FROM `userprofile` WHERE `email` = '$value[empId]'");
					//$employeeid = $value['empId'];
					$name = $value['name'];
					$bankname = $value['bankname'];
					$accnum = $value['accnum'];
					$payrollid = $payroll->payrollid;
					$subscription_token = $value['subscription_token'];
					$month = $payroll->month;
					$year = $payroll->year;
					$commission = $value['commission'];
					$total_amount = $value['total_amount'];
					$net_total = $value['net_total'];
					$email = idToEmail($employeeid);
					$default = $ezDb->get_row("SELECT * FROM `payslips` WHERE `payrollid` = '$payrollid' AND `employeeid` = '$employeeid'");
					if(!empty($default)){
						$query = "UPDATE `payslips` SET `accname`= '$name', `bankname`= '$bankname', `accnum` = '$accnum', `month` = '$month',`year` = '$year', `commission` = '$commission',`net_total`='$net_total',`dateupdated` = '$dateNow' WHERE  `payrollid` = '$payrollid' AND `employeeid` = '$employeeid'";
						if($ezDb->query($query)){
							alertUser($email,0,"Your Commission Payslip for $month $year has been updated");
							$updtednum++;
						}else{
							
						}
					}else{
						$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `payslips` ORDER BY `id` DESC LIMIT 1;");
						$query = "INSERT INTO `payslips` (`token`,`payrollid`,`employeeid`,`accname`,`bankname`,`accnum`,`month`,`year`,`commission`,`net_total`,`dateupdated`) VALUES ('$token','$payrollid','$employeeid','$name','$bankname','$accnum','$month','$year','$commission','$net_total','$dateNow');";
						if($ezDb->query($query)){
							alertUser($email,0,"Your Commission Payslip for $month $year has been uploaded");
							$num++;
						}else{
							
						}
					}
				}
				$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$num.' Staff PaySlips was successfully generated.</p></div>';
				$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$updtednum.' Staff PaySlips was successfully updated.</p></div>';
				logEvent($userinfo->email, "payroll-generated", $userinfo->usertype, 'payroll', $payroll);
			}
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payroll not found.</p></div>';
	}
}


$whereClause = " WHERE `id` != 0 ";
if (in_array($sitePage, array("commission_payrolls")) && ($requestMethod == 'POST') && $posts->triggers=='commission_payrolls') {
		
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
}

$count = 0;
$approved = 0;
$disapproved = 0;
$sent = 0;
$payrolls=$ezDb->get_results("SELECT COUNT(if(status = 1,1,NULL)) as total_disapproved, COUNT(if(status = 2,1,NULL)) as total_approved, COUNT(if(status = 3,1,NULL)) as total_sent, COUNT(total_pay) as total_commission,`status`,`month`, `year`, SUM(`total_pay`) AS `totals` from `commission_payroll` $whereClause GROUP BY `month`,`year` ORDER BY `id` DESC");

$smarty->assign("payrolls", $payrolls)->assign("months", $months)->assign("filter",$filter);