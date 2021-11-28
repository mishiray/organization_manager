<?php 

$id=(!empty($gets->id)? $gets->id: "");

if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ):
	redirect("home");
endif;

$record=$ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `employeeid`='$id'");
if (!empty($record)) {
	//Delete Record
	if ( in_array($sitePage, array("salary_record")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) && in_array($userinfo->userrole, array("level0","level1")) || (in_array($userinfo->department, array("Accounting","Human Resources")) && in_array($userinfo->userrole, array("level0","level1","level2"))) ) {
		if (!empty($record) and $gets->evt=='delete') {
			$ezDb->query("DELETE FROM `employment_salary_records` WHERE `employeeid`='$id'");
			logEvent($userinfo->email, "salary-record-deleted", $userinfo->usertype, 'record', $record);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Salary Record was successfully deleted.</p></div>';
		}
	}

	//Update record
	if ( in_array($sitePage, array("salary_record")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_record']) && in_array($userinfo->userrole, array("level0","level1")) || (in_array($userinfo->department, array("Accounting","Human Resources")) && in_array($userinfo->userrole, array("level0","level1","level2"))) ) {
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
		if( empty(trim($posts->year)) ):
			$err++;
			$fail.='<p>Enter year please!</p>';
		endif;
		if( empty(trim($posts->position)) ):
			$err++;
			$fail.='<p>Enter position please!</p>';
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
		if( empty(trim($posts->status)) ):
			$posts->status = 0;
		endif;
		if( empty(trim($posts->late_fee)) ):
			$posts->late_fee = 0;
		endif;
		if( empty(trim($posts->tax)) ):
			$err++;
			$fail.='<p>Enter tax please!</p>';
		endif;
		if( empty(trim($posts->gross_year)) ):
			$posts->gross_year = 0;
		endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		if ($err==0) {
			$query = " UPDATE `employment_salary_records` SET `bankname` = '$posts->bankname', `accnum` = '$posts->accnum', `year`='$posts->year', `position` = '$posts->position', `salary` = '$posts->salary', `percent_increase` = '$posts->percent_inc', `naira_increase` = '$posts->naira_inc', `transport` = '$posts->transport',`housing` = '$posts->housing',`lunch` = '$posts->lunch',`pension` = '$posts->pension',`welfare` = '$posts->welfare', `reason` = '$posts->reason', `personal_objectives` = '$posts->personal_obj', `commission` = '$posts->commission', `annual_salary` = '$posts->annual_salary', `tax` = '$posts->tax',`late_fee` = '$posts->late_fee', `gross_year` = '$posts->gross_year', `dateupdated` = '$dateNow', `status` = '$posts->status' WHERE `employeeid` = '$posts->employeeid' ";
			if($ezDb->query($query)){
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Salary record has been successfully updated.</p></div>';
				logEvent($userinfo->email, "salary-record-updated", $userinfo->usertype, 'employment_salary_record', $record);
			}
			else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to update employee salary</div>';
			}
		}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}
	
}else{
	redirect('salary_records');
}
$smarty->assign("record", $record);