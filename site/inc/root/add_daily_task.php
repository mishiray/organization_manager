<?php 

$employee=$ezDb->get_row("SELECT * from `employees`  WHERE `employeeid` = '$userinfo->employeeid'");
$employee->dept = idtodepartment($employee->employeeid);
if($employee->manager_id){
	$manager = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$employee->manager_id'");
	$employee->manager = ucwords($manager->surname.' '.$manager->first_name);
}else{
	$employee->manager = '';
}
$smarty->assign("employee", $employee);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;

if ( in_array($sitePage, array("add_daily_task")) && ($requestMethod == 'POST') && isset($Site["post"]['add_daily_task']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->manager)) ):
		$err++;
		$fail.='<p>Enter Manager please!</p>';
	endif;
	if( empty(trim($posts->department)) ):
		$err++;
		$fail.='<p>Enter Department please!</p>';
	endif;
	if( empty(trim($posts->completion_rate)) ):
		$posts->completion_rate = 0;
	endif;
	if( empty(trim($posts->start_date)) ):
		$err++;
		$fail.='<p>Enter Start Date please!</p>';
	endif;
	if( empty(trim($posts->end_date)) ):
		$posts->end_date = $posts->start_date;
	endif;
	if(empty(trim($posts->note)) ):
		$posts->note = ' ';
	endif;
	
	$posts->note=cleanUp($posts->note);
	$posts->task=cleanUp($posts->task);
	
	if ($err==0) {
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `daily_task` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `daily_task`(`token`,`employeeid`, `name`, `manager`,`department`, `project`, `task`, `completion_rate`, `start_date`, `end_date`, `note`) VALUES ('$token','$userinfo->employeeid','$posts->name','$posts->manager','$posts->department','$posts->project','$posts->task','$posts->completion_rate','$posts->start_date','$posts->end_date','$posts->note');";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Daily Tasks has been successfully added.</p></div>';
		    alertUser($userinfo->email,0,'Task for today Added');
			alertUser($manager->email,0,"Daily Task for $posts->name has been added");
			$report=$ezDb->get_row("SELECT * FROM `daily_task` WHERE `token`='$token';");
    		logEvent($userinfo->email, "new-dailytask-added", $userinfo->usertype, 'daily_task', $report);
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add Daily Tasks records</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}