<?php 

$employees=$ezDb->get_results("SELECT e.employeeid as empid , e.surname as surname, e.first_name as first_name from `employees` as e LEFT JOIN `payroll` as p on e.employeeid = p.employeeid WHERE p.employeeid IS NULL ");

$smarty->assign("employees", $employees);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2'))  && !in_array($userinfo->department, array('Human Resources')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_job_detail")) && ($requestMethod == 'POST') && isset($Site["post"]['add_job_detail']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->employeeid)) ):
		$err++;
		$fail.='<p>Select an Employee please!</p>';
	endif;
	if( empty(trim($posts->job_title)) ):
		$err++;
		$fail.='<p>Enter Job_title please!</p>';
	endif;
	if( empty(trim($posts->contract_type)) ):
		$err++;
		$fail.='<p>Enter Contract Type please!</p>';
	endif;
	if( empty(trim($posts->start_date)) ):
		$err++;
		$fail.='<p>Enter Start Date please!</p>';
	endif;
	if( empty(trim($posts->end_date)) ):
		//$err++;
		//$fail.='<p>Enter End Date please!</p>';
	endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2','level4'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;
	if ($err==0) {
		$token = getToken(8);
		$query = "INSERT INTO `job_details`(`token`,`employeeid`, `name`, `job_title`, `contract_type`, `grade`, `company_pension`, `private_pension`, `length_of_service`, `probation_period`, `source`, `agency`, `start_date`, `health_insurance`, `professional_qualification`, `institute_membership`) VALUES ('$token','$posts->employeeid','$posts->name','$posts->job_title', '$posts->contract_type','$posts->grade','$posts->company_pension' ,'$posts->private_pension','$posts->length_of_service','$posts->probation_period','$posts->source','$posts->agency','$posts->start_date','$posts->health_insurance','$posts->professional_qualification','$posts->institute_membership');";
		if($ezDb->query($query)){
			$emp_email = idToEmail($posts->employeeid);
			alertUser($emp_email,0,"Your Job Detail has been added");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Job detail record has been successfully added.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add to job records</div>';
		}
	    $report=$ezDb->get_row("SELECT * FROM `job_details` WHERE `token`='$token';");
      	logEvent($userinfo->email, "new-job-detail-added", $userinfo->usertype, 'job_details', $report);
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}