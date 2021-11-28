<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3','level4')) ):
	redirect("home");
endif;

if ( in_array($sitePage, array("attendance_sheet")) && ($requestMethod == 'POST') && isset($Site["post"]['attendance_sheet']) ) {
	//echo 'im in';
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->allData)) ):
		$err++;
		$fail.='<p>Cannot get data</p>';
	endif;
	if( empty(trim($posts->year)) ):
		$err++;
		$fail.='<p>Enter Year please!</p>';
	endif;

	if($err==0){
		if (!empty($ezDb->get_row("SELECT * FROM `attendance` WHERE `employeeid`='$userinfo->employeeid' AND `year` = '$posts->year'"))) {
			if($ezDb->query("UPDATE `attendance` SET `data` ='$posts->allData', `dateupdated`='$dateNow' WHERE `employeeid`='$userinfo->employeeid' AND `year` = '$posts->year'")){
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Attendance Report has been successfully updated.</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
			}
		}else{

			$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `attendance` ORDER BY `id` DESC LIMIT 1;");
			$query = "INSERT INTO `attendance` (`token`,`employeeid`, `year`,`name`, `data`, `dateupdated`) VALUES ('$token','$userinfo->employeeid','$posts->year','$userinfo->lastname $userinfo->firstname','$posts->allData','$dateNow');";
			
			if($ezDb->query($query)){
			$report=$ezDb->get_row("SELECT * FROM `attendance` WHERE `token`='$token';");
			logEvent($userinfo->email, "new-attendance-added", $userinfo->usertype, 'attendance', $report);

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Attendance Report has been successfully added.</p></div>';
			
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
			}

		}
		//$report=$ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `reportid`='$token';");
		//logEvent($userinfo->email, "new-report-added", $userinfo->usertype, 'reports', $report);
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}

}