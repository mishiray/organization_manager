<?php 

$userinfo=$Site['session']['User']['userinfo'];


$employees=$ezDb->get_results("SELECT e.employeeid as empid , e.surname as surname, e.first_name as first_name from `employees` as e LEFT JOIN `payroll` as p on e.employeeid = p.employeeid WHERE p.employeeid IS NULL ");

$smarty->assign("employees", $employees);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("leave_application")) && ($requestMethod == 'POST') && isset($Site["post"]['apply_leave']) ) {
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Name not found please!</p>';
	endif;
	if( empty(trim($posts->department)) ):
		$err++;
		$fail.='<p>Department not found please!</p>';
	endif;
	if( empty(trim($posts->days_no)) ):
		$err++;
		$fail.='<p>Enter Surname please!</p>';
	endif;
	if( empty(trim($posts->start_date)) ):
		$err++;
		$fail.='<p>Enter start date please!</p>';
	endif;
	if( empty(trim($posts->end_date)) ):
		$err++;
		$fail.='<p>Enter end date please!</p>';
	endif;
	if( empty(trim($posts->reasons)) ):
		$err++;
		$fail.='<p>Enter reasons please!</p>';
	endif;
	if( empty(trim($posts->type))):
		$err++;
		$fail.='<p>Select leave type please!</p>';
	endif;
	if ($err==0) {
		$posts->reasons = cleanUp($posts->reasons);
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `leave_application` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `leave_application`(`token`,`employeeid`, `name`, `department`, `days_no`, `start_date`, `end_date`, `reasons`, `type`, `reviewed_by`, `dateupdated`, `status`) VALUES ('$token','$userinfo->employeeid','$posts->name','$posts->department','$posts->days_no','$posts->start_date','$posts->end_date','$posts->reasons','$posts->type','','$dateNow','0');";
		if($ezDb->query($query)){
			$report=$ezDb->get_row("SELECT * FROM `leave_application` WHERE `token`='$token';");
      		logEvent($userinfo->email, "new-leave-application-added", $userinfo->usertype, 'leave_application', $report);
			alertUser($userinfo->email,0,"Your leave application has been submitted");
			alertHRManager(0,"New leave application submitted");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Leave Application submitted successfully.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to submit leave application</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
$smarty->assign("userinfo", $Site["session"]["User"]["userinfo"]);
