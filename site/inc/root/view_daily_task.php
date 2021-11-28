<?php

$id = ($gets->id) ? $gets->id : '';
$task=$ezDb->get_row("SELECT * from `daily_task` WHERE `token` = '$id';");
if(!empty($task)){
	
	$employee = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$task->employeeid'");
	if($employee->manager_id){
		$employee->manager = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$employee->manager_id'");
		$employee->manager->name = ucwords($employee->manager->surname.' '.$employee->manager->first_name);
	}else{
		$employee->manager->name = '';
	}
	
	if ( in_array($sitePage, array("view_daily_task")) && ($requestMethod == 'POST') && isset($Site["post"]['update_daily_task']) ) {

		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		if( empty(trim($posts->task)) ):
			$err++;
			$fail.='<p>Enter Task please!</p>';
		endif;
		if( empty(trim($posts->completion_rate)) ):
			$posts->completion_rate = 0;
		endif;
		if( empty(trim($posts->end_date)) ):
			$err++;
			$fail.='<p>Enter End Date please!</p>';
		endif;
		if(empty(trim($posts->note)) ):
			$fail.='<p>Enter Note please!</p>';
		endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2','level4'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		
		$posts->note=cleanUp($posts->note);
		$posts->task=cleanUp($posts->task);
		
		if ($err==0) {
			$query = "UPDATE `daily_task` SET `task` =  '$posts->task', `completion_rate` = '$posts->completion_rate', `end_date` = '$posts->end_date', `note` = '$posts->note' WHERE `token` = '$id';";
			if($ezDb->query($query)){
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Daily Tasks has been successfully updated.</p></div>';
				alertUser($employee->manager->email,0,"Daily Task for $posts->name has been updated");
				$task=$ezDb->get_row("SELECT * from `daily_task` WHERE `token` = '$id';");
				logEvent($userinfo->email, "dailytask-updated", $userinfo->usertype, 'daily_task', $task);
			}
			else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to update Daily Task</div>';
			}
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("view_daily_task")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( in_array($userinfo->userrole, array('level0','level1','level2','level3')) ) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1','level2','level3')) ):
			redirect("daily_tasks");
		endif;
		if (!empty($task) and $gets->evt=='delete') {
			$ezDb->query("DELETE FROM `daily_task` WHERE `token`='$id'");
			alertUser(idToEmail($task->employeeid),0,"Daily Task has been deleted");			
	    	logEvent($userinfo->email, "daily-task-deleted", $userinfo->usertype, 'daily_task', $task);
			redirect("daily_tasks");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Daily Task was successfully deleted.</p></div>';
		}
	}

	
	if ( in_array($sitePage, array("view_daily_task")) && ($requestMethod == 'POST') && isset($posts->mreview) and ( $userinfo->email== $employee->manager->email or $userinfo->userrole=='level0') ) {
		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Your comment is required please!</p>';
		endif;

		if( !in_array($posts->status, array('0', '1', '2')) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		endif;

		if ($err==0) {
			$revDet=new stdClass();
			$revDet->user=$userinfo->email;
			$revDet->comment=$posts->comment;
	    	logEvent($userinfo->email, "task-reviewed", $userinfo->usertype, 'daily_task', $revDet);
			$ezDb->query("UPDATE `daily_task` SET `status`='$posts->status', `manager_review`='".@json_encode($revDet)."' WHERE `token`='$id'");
			$task=$ezDb->get_row("SELECT * FROM `daily_task` WHERE `token`='$id'");
			alertUser(idToEmail($task->employeeid),1,"Task has been reviewed");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Task was successfully reviewed.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$employee = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$task->employeeid'");
	if($employee->manager_id){
		$employee->manager = $ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$employee->manager_id'");
		$employee->manager->name = ucwords($employee->manager->surname.' '.$employee->manager->first_name);
	}else{
		$employee->manager->name = '';
	}
	$task->manager_review=(empty($task->manager_review)? new stdClass(): @json_decode($task->manager_review));
}
$smarty->assign("task", $task)->assign("employee", $employee);

