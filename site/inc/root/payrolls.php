<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
	redirect("home");
endif;

if ( in_array($sitePage, array("payrolls")) && ($requestMethod == 'GET') && isset($Site["get"]['evt'])  && isset($Site["get"]['id']) ){
	$id = (!empty($gets->id)) ? $gets->id : "";
	if($payroll=$ezDb->get_row("SELECT * from `payroll` WHERE `payrollid` = '$id'"))
	{
		if (!empty($payroll) and $gets->evt=='generate') {
			$tableData = $payroll->data;
			$json  = json_decode($tableData, true);
			//print_r($json);
			if(!empty($json)){
				$num = 0;
				$updtednum = 0;
				foreach ($json as $Key=>$value) {
					$employeeid = $value['empId'];
					$name = $value['name'];
					$bankname = $value['bankname'];
					$payrollid = $payroll->payrollid;
					$position = $value['position'];
					$accnum = $value['accnum'];
					$month = $payroll->month;
					$year = $payroll->year;
					$salary = $value['salary'];
					$transport = $value['transport'];
					$housing = $value['housing'];
					$lunch = $value['lunch'];
					$commission = $value['commission'];
					$payee = $value['payee'];
					$pension = $value['pension'];
					$hmo = $value['hmo'];
					$loan = $value['loan'];
					$fines = $value['fines'];
					$welfare = $value['welfare'];
					$others = $value['others'];
					$tax = $value['tax'];
					$drtotal = $value['drtotal'];
					$gross_total = $value['gross_total'];
					$net_total = $value['net_total'];
					$email = idToEmail($employeeid);
 					$default = $ezDb->get_row("SELECT * FROM `payslips` WHERE `payrollid` = '$payrollid' AND `employeeid` = '$employeeid'");
					if(!empty($default)){
						$query = "UPDATE `payslips` SET `accname`= '$name', `bankname`= '$bankname', `position` = '$position', `accnum` = '$accnum', `month` = '$month',`year` = '$year',`salary` = '$salary',`transport` = '$transport',`housing` = '$housing',`lunch` = '$lunch',`commission` = '$commission',`payee` = '$payee', `pension`='$pension',`hmo`='$hmo',`loan`='$loan',`fines`='$fines',`welfare`='$welfare',`others`='$others',`tax`='$tax',`gross_total`='$gross_total',`drtotal` = '$drtotal',`net_total`='$net_total',`dateupdated` = '$dateNow' WHERE  `payrollid` = '$payrollid' AND `employeeid` = '$employeeid'";
						if($ezDb->query($query)){
							alertUser($email,0,"Your Payslip for $month $year has been updated");
							$updtednum++;
						}else{
							
						}
					}else{
						$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `payslips` ORDER BY `id` DESC LIMIT 1;");
						$query = "INSERT INTO `payslips` (`token`,`payrollid`,`employeeid`,`accname`,`bankname`,`position`,`accnum`,`month`,`year`,`salary`,`transport`,`housing`,`lunch`,`commission`,`payee`,`pension`,`hmo`,`loan`,`fines`,`welfare`,`others`,`tax`,`gross_total`,`drtotal`,`net_total`,`dateupdated`) VALUES ('$token','$payrollid','$employeeid','$name','$bankname','$position','$accnum','$month','$year','$salary','$transport','$housing','$lunch','$commission','$payee','$pension','$hmo','$loan','$fines','$welfare','$others','$tax','$gross_total','$drtotal','$net_total','$dateNow');";
						if($ezDb->query($query)){
							alertUser($email,0,"Your Payslip for $month $year has been uploaded");
							$num++;
						}else{
								
						}
					}
				}
				$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$num.' Staff PaySlips was successfully generated.</p></div>';
				$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$updtednum.' Staff PaySlips was successfully updated.</p></div>';
				logEvent($userinfo->email, "payslip-generated", $userinfo->usertype, 'payroll', $payroll);
			}
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Payroll not found.</p></div>';
	}
}

$whereClause=(
	$userinfo->userrole=='level3'? "AND `employeeid`='$userinfo->employeeid' AND `status` IN('0','1','3', '2', '4')": (
		$userinfo->userrole=='level2'? "AND `status` IN('0', '1', '3', '4', '2')": (
			$userinfo->userrole=='level1'? "AND `status` IN( '1', '2', '4')": ""
		)
	)
);

$payrolls=$ezDb->get_results("SELECT * from `payroll` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
foreach ($payrolls as $value) {
	$value->name = $ezDb->get_row("SELECT `surname`, `first_name` FROM `employees` WHERE `employeeid` = '$value->employeeid'");
}
$smarty->assign("payrolls", $payrolls);