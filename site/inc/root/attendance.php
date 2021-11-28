<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3','level4')) ):
	redirect("home");
endif;
$department = $userinfo->department;
$depId = $ezDb->get_var("SELECT `id` FROM `department` WHERE `name` = '$department'");
$isadmin = 'yes';

$whereClause="AND `id` != 0";
if(in_array( $userinfo->userrole, array('level2')) && $userinfo->department == 'Human Resources'){
	$isadmin = 'yes';
	$whereClause="AND `department_id` = '$depId'";
}else if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ){
	$isadmin = 'no';
	$whereClause="AND `employeeid` = '$userinfo->employeeid'";
}
$employees=$ezDb->get_results("SELECT * FROM `employees` WHERE `id` != 0 $whereClause");

$smarty->assign("employees", $employees)->assign("isadmin", $isadmin);

$posts = (object) $Site["post"];

if ( in_array($sitePage, array("attendance")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( $userinfo->userrole=='level2' or $userinfo->userrole=='level1' or $userinfo->userrole=='level0'))
{	
	$posts = (object) $Site["post"];
	$attendance_review = $ezDb->get_row("SELECT * FROM `attendance` WHERE `id` = '$gets->id'");
	if (!empty($attendance_review) and $gets->evt=='delete') {
			$ezDb->query("DELETE FROM `attendance` WHERE `id`='$attendance_review->id'");
			logEvent($userinfo->email, "attendance-deleted", $userinfo->usertype, 'attendance', $attendance_review);
			$employee = $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid` = '$attendance_review->employeeid'");
			alertUser($employee,1,'Attendance has been deleted by Manager');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Attendance was successfull deleted.</p></div>';
	}
}
if (in_array($sitePage, array("attendance")) && ($requestMethod == 'POST') && (isset($posts->mreview)) and ( $userinfo->userrole=='level2' or $userinfo->userrole=='level1' or $userinfo->userrole=='level0') ) 
{
			
				$posts = (object) $Site["post"];
				$err = 0;
				if( empty(trim($posts->id)) ):
					$err++;
					$fail.='<p>Can not find review info!</p>';
				endif;
				$attendance_review = $ezDb->get_row("SELECT * FROM `attendance` WHERE `id` = '$posts->id'");

				if(!empty($attendance_review)){
					if( empty(trim($posts->comment)) ):
						$err++;
						$fail.='<p>Your comment is required please!</p>';
					endif;
	
					if( isset($posts->mreview) and  in_array($attendance_review->status, array('2','1','3'))  and (($userinfo->userrole=='level2') or $userinfo->userrole=='level0' ) ):
						$err++;
						$fail.='<p>Kindly Choose a valid status!</p>';
					endif;
	
					if ($err==0) {
						$revDet=new stdClass();
						$revDet->user=$userinfo->email;
						$revDet->comment=$posts->comment;
						$revLog='Manager';
						if( ((in_array($attendance_review->status, array('0','3','1','2')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0') and isset($posts->mreview) ):
							$ezDb->query("UPDATE `attendance` SET `dateupdated` = '$dateNow', `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `id`='$posts->id'");
							$employee = $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid` = '$attendance_review->employeeid'");
							alertUser($employee,0,'Attendance has been reviewed by Manager');
						endif;
						logEvent($userinfo->email, "attendance-reviewed-by-$revLog", $userinfo->usertype, 'attendance', $revDet);
						$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Attendance was successfully reviewed.</p></div>';
					}else{
						$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
					}
	
				}
				
			}

if ( in_array($sitePage, array("attendance")) && ($requestMethod == 'POST') && !empty($posts->triggers) and $posts->triggers=='get_attendance' ) {
	
$fail="";
$err=0;	
$isadmin = 'yes';
$whereClause="AND `id` != 0 AND `employeeid` = '$posts->employeeid'";

if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ){
	$isadmin = 'no';
	$whereClause="AND `employeeid` = '$userinfo->employeeid'";
}
	if( empty(trim($posts->year)) ):
		$err++;
		$fail.='<p>Enter Year Please</p>';
	endif;
	if($isadmin == 'yes'){
		if(empty(trim($posts->employeeid))):
			$err++;
			$fail.='<p>Select Employee Please</p>';
		endif;
	}

	if($err==0){
		$attendance_data = $ezDb->get_row("SELECT * FROM `attendance` WHERE `year` = '$posts->year' $whereClause");
		
		if(!empty($attendance_data)){
			$employee = $ezDb->get_row("SELECT * FROM `employees` where `employeeid` = '$attendance_data->employeeid'");
			$attendance_data->name = $employee->surname." ".$employee->first_name;
			//echo $attendance_data->name;
			$tableData = $attendance_data->data;
					
					$jsonData= json_decode($tableData, true);
					
					$val = new stdClass();
					$myArray = [];
					if(!empty($jsonData)){
						$num = 0;
						foreach ($jsonData as $Key=>$value) {
							//echo ($value);
							array_push($myArray, (object)[
								'dayName' => $value['dayName'],
								'workDays' => implode(",", $value['workDays']),
								'vactionDays' => implode(",", $value['vactionDays']),
								'breakHours' => implode(",", $value['breakHours']),
								'sickDays' => implode(",", $value['sickDays']),
								'maternityDays' => implode(",", $value['maternityDays']),
								'otherDays' => implode(",", $value['otherDays']),
								'totalWorkDays' => $value['totalWorkDays'],
								'totalVactionDays' => $value['totalVactionDays'],
								'totalBreakDays' => $value['totalBreakDays'],
								'totalSickDays' => $value['totalSickDays'],
								'totalMaternityDays' => $value['totalMaternityDays'],
								'totalOtherDays' => $value['totalOtherDays']
							]);
						}
					}
					
			$smarty->assign("attendance_data", $attendance_data)->assign("myArray",$myArray);
		}else{	
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Record Not Found</div>';
	
		}
		//$report=$ezDb->get_row("SELECT * FROM `employment_salary_records` WHERE `reportid`='$token';");
		//logEvent($userinfo->email, "new-report-added", $userinfo->usertype, 'reports', $report);
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
	
	//$smarty->assign("attendance_data", $attendance_data)->assign("myArray",$myArray);
}