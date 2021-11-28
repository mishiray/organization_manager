<?php 

$employees=$ezDb->get_results("SELECT e.employeeid as empid , e.surname as surname, e.first_name as first_name from `employees` as e LEFT JOIN `employment_salary_records` as p on e.employeeid = p.employeeid WHERE p.employeeid IS NULL ");
//SELECT e.employeeid as empid , e.surname as surname, e.first_name as first_name from `employees` as e LEFT JOIN `payroll` as p on e.employeeid = p.employeeid WHERE p.employeeid IS NULL "
$smarty->assign("employees", $employees);

if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_salary_record")) && ($requestMethod == 'POST') && isset($Site["post"]['add_salary']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->employeeid)) ):
		$err++;
		$fail.='<p>Select an Employee please!</p>';
	endif;
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter Employee name please!</p>';
	endif;
	if( empty(trim($posts->bankname)) ):
		$err++;
		$fail.='<p>Enter Bank Name please!</p>';
	endif;
	if( empty(trim($posts->accnum)) ):
		$err++;
		$fail.='<p>Enter Account Number please!</p>';
	endif;
	if( empty(trim($posts->position)) ):
		$posts->position = "Employee";
	endif;
	if( empty(trim($posts->salary)) ):
		$err++;
		$fail.='<p>Enter salary please!</p>';
	endif;
	if( empty(trim($posts->percent_inc)) ):
		$posts->percent_inc = 0;
	endif;
	if( empty(trim($posts->naira_inc)) ):
		$posts->naira_inc = 0;
	endif;
	if( empty(trim($posts->transport)) ):
		$posts->transport = 0;
	endif;
	if( empty(trim($posts->housing)) ):
		$posts->housing = 0;
	endif;
	if( empty(trim($posts->lunch)) ):
		$posts->lunch = 0;
	endif;
	if( empty(trim($posts->pension)) ):
		$posts->pension = 0;
	endif;
	if( empty(trim($posts->welfare)) ):
		$posts->welfare = 0;
	endif;
	if( empty(trim($posts->reason)) ):
		$posts->reason = "none";
	endif;
	if( empty(trim($posts->personal_obj)) ):
		$posts->personal_obj = " ";
	endif;
	if( empty(trim($posts->annual_salary)) ):
		$posts->annual_salary = 0;
	endif;
	if( empty(trim($posts->commission)) ):
		$posts->commission = 0;
	endif;
	if( empty(trim($posts->tax)) ):		
		$posts->tax = 0;
	endif;
	if( empty(trim($posts->gross_year)) ):
		$posts->gross_year = 0;
	endif;
	if( empty(trim($posts->status)) ):
		$posts->status = 1;
	endif;
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level2'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;
	if ($err==0) {
		$query = "INSERT INTO `employment_salary_records`(`employeeid`, `name`, `bankname`,`accnum`,`year`, `position`, `salary`, `percent_increase`, `naira_increase`, `transport`,`housing`,`lunch`,`pension`,`welfare`, `reason`, `personal_objectives`, `commission`, `annual_salary`, `tax`, `gross_year`, `status`) VALUES ('$posts->employeeid','$posts->name','$posts->bankname','$posts->accnum','$posts->year','$posts->position','$posts->salary','$posts->percent_inc','$posts->naira_inc','$posts->transport','$posts->housing','$posts->lunch','$posts->pension','$posts->welfare','$posts->reason','$posts->personal_obj','$posts->commission','$posts->annual_salary','$posts->tax','$posts->gross_year','$posts->status');";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your salary record has been successfully added.</p></div>';
			$report=$ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `employeeid`='$posts->employeeid';");
			logEvent($userinfo->email, "new-salaryrecord-added", $userinfo->usertype, 'employment_salary_records', $report);
			alertDept("Accounting", 0 ,"New employee salary record added");	
			$email = idToEmail($posts->employeeid);
			alertUser($email, 0 ,"Your salary record has been added");	
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add employee salary</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}