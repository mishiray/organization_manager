<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$id = (!empty($gets->id)) ? $gets->id : "";

$payslip=$ezDb->get_row("SELECT * from `payslips` WHERE `id` = '$id'");
if(!empty($payslip)){
	$payslip->employee = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$payslip->employeeid'");
	if(empty($payslip->employee)){
		$payslip->employee = $ezDb->get_row("SELECT `firstname` as first_name,`lastname` as surname from `userprofile` WHERE `userid` = '$payslip->employeeid'");
	}
	if($employee = $payslip->employee){
		$payslip->employee->department = $ezDb->get_var("SELECT `name` from `department` WHERE `id` = '$employee->department_id' ");
	}
	$title = $payslip->month.' '.$payslip->year.' Payslip';
	if($ezDb->get_row("SELECT * FROM `pdocuments` WHERE `reftoken` = '$payslip->token'")){
		$payslip->saved = true;
	}else{
		$payslip->saved = false;
	}
	$payslip->salary_record = $ezDb->get_row("SELECT * from `employment_salary_records` WHERE `employeeid` = '$payslip->employeeid'");
}
$smarty->assign("payslip", $payslip)->assign("domainName", $domainName)->assign("dateNow", $dateNow);