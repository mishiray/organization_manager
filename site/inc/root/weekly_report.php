<?php 

$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3','level4')) ):
	redirect("home");
endif;

$report=$ezDb->get_row("SELECT * FROM `weekly_report` WHERE `reportid`='$id'");
$report->user = $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid`='$report->employeeid'");
if (!empty($report)) {
	if ( in_array($sitePage, array("weekly_report")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( (in_array($report->status, array('0','3')) and $userinfo->email==$report->user) 
		or (in_array($report->status, array('0','2','1','4')) and $userinfo->userrole=='level1')
	 	or $userinfo->userrole=='level0') ) {
		if (!empty($report) and $gets->evt=='delete') {
			if( file_exists($report->attachment)):
				@unlink($report->attachment);
			endif;
			$ezDb->query("DELETE FROM `weekly_report` WHERE `reportid`='$id'");
      		logEvent($userinfo->email, "weekly_report-deleted", $userinfo->usertype, 'reports', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully deleted.</p></div>';
		}
	}

	if ( in_array($sitePage, array("weekly_report")) && ($requestMethod == 'POST') && (isset($posts->lreview) or isset($posts->mreview)) and ( (in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or 
		(in_array($report->status, array('0','2','1','4')) and $userinfo->userrole=='level1')
	 or $userinfo->userrole=='level0') ) {

		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Your comment is required please!</p>';
		endif;

		if( (!in_array($posts->status, array('1', '3')) and isset($posts->lreview) and 
			( (in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		elseif( (!in_array($posts->status, array('2', '4')) and isset($posts->mreview) and 
			( (in_array($report->status, array('0','2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		endif;

		if ($err==0) {
			$revDet=new stdClass();
			$revDet->user=$userinfo->email;
			$revDet->comment=$posts->comment;
			$revLog='manager';
			if( ((in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0' or $userinfo->userrole=='level1') and isset($posts->lreview) ):
				
				if($ezDb->query("UPDATE `weekly_report` SET `status`='$posts->status', `manager_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'")){
					logEvent($userinfo->email, "summary-report-updated", $userinfo->usertype, 'reports', $report);
					$report=$ezDb->get_row("SELECT * FROM `summary_report` WHERE `reportid`='$id'");
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull updated.</p></div>';
			
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully updated.</p></div>';
			
				}
			elseif( ((in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0') and isset($posts->mreview) ):
				$revLog='md';
				if($ezDb->query("UPDATE `weekly_report` SET `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'")){

					logEvent($userinfo->email, "report-reviewed-by-$revLog", $userinfo->usertype, 'reports', $revDet);
					$report=$ezDb->get_row("SELECT * FROM `weekly_report` WHERE `reportid`='$id'");
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully reviewed.</p></div>';
				
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully reviewed.</p></div>';
			
				}
			endif;
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("weekly_report")) && ($requestMethod == 'POST') && isset($Site["post"]['report']) and ((in_array($report->status, array('0', '4','3')) and $userinfo->email==$report->user) or $userinfo->userrole=='level0') ) {
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		if( empty(trim($posts->branchid)) ):
			$err++;
			$fail.='<p>Enter BranchID please!</p>';
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
		if(!in_array($userinfo->userrole, array('level0','level1', 'level2', 'level3','level4'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		if ($err==0) {
		    if($ezDb->query("UPDATE `weekly_report` SET `branchid`='$posts->branchid', `team`='$posts->team', `type` = '$posts->type', `date` = '$posts->date', `organization` = '$posts->organization', `activities`='$posts->activities', `action_plan`='$posts->action_plan', `note`='$posts->note' WHERE `reportid`='$id';")){
				logEvent($userinfo->email, "report-updated", $userinfo->usertype, 'reports', $report);
				$report=$ezDb->get_row("SELECT * FROM `weekly_report` WHERE `reportid`='$id'");
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully updated.</p></div>';
			
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully updated.</p></div>';
			
			}
      	}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$report->manager_review=(empty($report->manager_review)? new stdClass(): @json_decode($report->manager_review));
	$report->manager=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->manager_review->user."'");
	$report->md_review=(empty($report->md_review)? new stdClass(): @json_decode($report->md_review));
	$report->md=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->md_review->user."'");
	$report->supervisorDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$report->user'");

}else{
	redirect('weekly_reports');
}
$smarty->assign("report", $report);