<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2')) ):
	//redirect("home");
endif;

$branches = $ezDb->get_results("SELECT * FROM `id_zone` ");
$employees=$ezDb->get_results("SELECT employeeid as empid , surname as surname, first_name as first_name from `employees` ");
$smarty->assign("employees", $employees)->assign("branches", $branches);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2')) ):
	//redirect("home");
endif;

if ( in_array($sitePage, array("add_weekly_report")) && ($requestMethod == 'POST') && isset($Site["post"]['add_weekly_report']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->branch_name)) ):
		$err++;
		$fail.='<p>Enter Branch please!</p>';
	endif;
	if( empty(trim($posts->team)) ):
		$err++;
		$fail.='<p>Enter Team please!</p>';
	endif;
	if( empty(trim($posts->type)) ):
		$err++;
		$fail.='<p>Enter Month/Week please!</p>';
	endif;
	if( empty(trim($posts->date)) ):
		$err++;
		$fail.='<p>Enter Date please!</p>';
	endif;
	if( empty(trim($posts->organization)) ):
		$err++;
		$fail.='<p>Enter Organization/Institution please!</p>';
	endif;
	if( empty(trim($posts->activities)) ):
		$err++;
		$fail.='<p>Enter Activities please!</p>';
	endif;
	if( empty(trim($posts->action_plan)) ):
		$err++;
		$fail.='<p>Enter Action_Plan please!</p>';
	endif;
	if( empty(trim($posts->note)) ):
		$err++;
		$fail.='<p>Enter Note please!</p>';
	endif;

	$posts->note=cleanUp($posts->note);
	$posts->action_plan=cleanUp($posts->action_plan);
	$posts->activities=cleanUp($posts->activities);
	if ($err==0) {
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `weekly_report`(`reportid`,`employeeid`, `branchid`, `team`, `type`, `date`, `organization`, `activities`, `action_plan`, `note`,`manager_review`,`md_review`) VALUES
		                        ('$token','$userinfo->employeeid','$posts->branch_name','$posts->team','$posts->type','$posts->date','$posts->organization','$posts->activities','$posts->action_plan','$posts->note','','');";
		if($ezDb->query($query)){
			
			$report=$ezDb->get_row("SELECT * FROM `weekly_report` WHERE `reportid`='$token';");
      		logEvent($userinfo->email, "new-weekly_report-added", $userinfo->usertype, 'reports', $report);
			
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Weekly Summary Report has been successfully added.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add Weekly Summary Report records</div>';
		}
	    //$report=$ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `reportid`='$token';");
      	//logEvent($userinfo->email, "new-report-added", $userinfo->usertype, 'reports', $report);
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}