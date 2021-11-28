<?php

$id = ($gets->id) ? $gets->id : '';
$job=$ezDb->get_row("SELECT * from `job_details` WHERE `token` = '$id';");
if(!empty($job)){
	$job->department = idtodepartment($job->employeeid);
	$employee = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$job->employeeid'");
	if($employee->manager_id){
		$employee->manager = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$employee->manager_id'");
		$employee->manager->name = ucwords($employee->manager->surname.' '.$employee->manager->first_name);
	}else{
		$employee->manager->name = '';
	}
	
	if ( in_array($sitePage, array("view_job_details")) && ($requestMethod == 'POST') && isset($Site["post"]['update_job_details']) ) {

		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];

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
			$err++;
			$fail.='<p>Enter End Date please!</p>';
		endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level2','level3','level4'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;

		if ($err==0) {
			$query = "UPDATE `job_details` SET `job_title` =  '$posts->job_title', `contract_type` = '$posts->contract_type', `grade` = '$posts->grade', `company_pension` = '$posts->company_pension',`private_pension` = '$posts->private_pension',`length_of_service` = '$posts->length_of_service',`probation_period` = '$posts->probation_period',`source` = '$posts->source',`agency` = '$posts->agency',`start_date` = '$posts->start_date',`end_date` = '$posts->end_date' ,`health_insurance` = '$posts->health_insurance' ,`professional_qualification` = '$posts->professional_qualification' ,`institute_membership` = '$posts->institute_membership' WHERE `token` = '$id';";
			if($ezDb->query($query)){
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Job details has been successfully updated.</p></div>';
				$emp_email = idToEmail($job->employeeid);
				alertUser($emp_email,0,"Your Job Detail has been updated");
				$job=$ezDb->get_row("SELECT * from `job_details` WHERE `token` = '$id';");
				logEvent($userinfo->email, "job-details-updated", $userinfo->usertype, 'job_details', $job);
			}
			else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to update job details</div>';
			}
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("view_job_details")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( in_array($userinfo->userrole, array('level0','level1','level2','level3')) ) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1','level2','level3')) ):
			redirect("job_details");
		endif;
		if (!empty($job) and $gets->evt=='delete') {
			$ezDb->query("DELETE FROM `job_details` WHERE `token`='$id'");
			alertUser(idToEmail($job->employeeid),0,"Job Detail has been deleted");			
	    	logEvent($userinfo->email, "job-detail-deleted", $userinfo->usertype, 'job_details', $job);
			redirect("job_details");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Job Detail was successfully deleted.</p></div>';
		}
	}

	$job->department = idtodepartment($job->employeeid);
	$employee = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$job->employeeid'");
	if($employee->manager_id){
		$employee->manager = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$employee->manager_id'");
		$employee->manager->name = ucwords($employee->manager->surname.' '.$employee->manager->first_name);
	}else{
		$employee->manager->name = '';
	}
}
$smarty->assign("job", $job)->assign("employee", $employee);

