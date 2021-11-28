<?php 

$employees=$ezDb->get_results("SELECT * from `employment_salary_records` WHERE `status` != 0");
$smarty->assign("employees", $employees)->assign("dateNow", $dateNow);


if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) ):
	redirect("home");
endif;
//$tableData = stripcslashes($_POST['allData']);
    
if (in_array($sitePage, array("add_payroll")) && ($requestMethod == 'POST') && isset($Site["post"]['add_payroll']) ) {
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
		if($check = $ezDb->get_row("SELECT * from `payroll` WHERE `month` = '$posts->month' AND `year` = '$posts->year'")){
			$query = "UPDATE `payroll` SET `employeeid` = '$userinfo->employeeid', `month` = '$posts->month', `year`='$posts->year', `data`='$tableData',`total_salary`='$posts->total_salary',`total_gross`='$posts->total_gross',`total_loan`='$posts->total_loan',`total_tax`='$posts->total_tax',`total_deb`='$posts->total_deb',`total_pay` = '$posts->total_pay',`status` = 0, `manager_review` = '',`md_review` = '', `dateupdated` = '$dateNow' WHERE `payrollid` = '$check->payrollid'";
			if($ezDb->query($query)){
				alertAccountingManager(0,"Payroll For $posts->month $posts->year Has Been Updated");
				alertMd2(0,"Payroll For $posts->month $posts->year Has Been Updated","payrolls");
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payroll record for '.$month.' '.$year.' has been updated.</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payroll record for '.$month.' '.$year.' couldnt be updated.</p></div>';
			
			}
		}else{
			$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
			$query = "INSERT INTO `payroll`(`payrollid`,`employeeid`, `month`, `year`, `data`,`total_salary`,`total_gross`,`total_loan`,`total_tax`,`total_deb`,`total_pay`) VALUES ('$token','$userinfo->employeeid','$posts->month','$posts->year','$tableData','$posts->total_salary','$posts->total_gross','$posts->total_loan','$posts->total_tax','$posts->total_deb','$posts->total_pay');";
			if($ezDb->query($query)){
				alertAccountingManager(0,"Payroll For $posts->month $posts->year Has Been Added");
				alertMd2(0,"Payroll For $posts->month $posts->year Has Been Added","payrolls");
				require_once "mail_management.php";
				$report=$ezDb->get_row("SELECT * FROM `payroll` WHERE `payrollid`='$token';");
				logEvent($userinfo->email, "new-payroll-added", $userinfo->usertype, 'payroll', $report);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payroll record has been successfully added.</p></div>';
			}
			else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add employee salary</div>';
			}
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
	
    //echo json_encode($tableData);
}