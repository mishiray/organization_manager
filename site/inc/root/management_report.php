<?php 

$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3')) ):
	redirect("home");
endif;

$report=$ezDb->get_row("SELECT * FROM `management_report` WHERE `reportid`='$id'");
$report->user = $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid`='$report->employeeid'");
if (!empty($report)) {
	if ( in_array($sitePage, array("management_report")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( (in_array($report->status, array('0','3')) and $userinfo->email==$report->user) 
		or (in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1')
	 	or $userinfo->userrole=='level0') ) {
		if (!empty($report) and $gets->evt=='delete') {
			if( file_exists($report->attachment)):
				@unlink($report->attachment);
			endif;
			$ezDb->query("DELETE FROM `management_report` WHERE `reportid`='$id'");
			  logEvent($userinfo->email, "management-report-deleted", $userinfo->usertype, 'reports', $report);
			  alertUser($report->user,1,'Management Report has been deleted');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully deleted.</p></div>';
		}
	}

	if ( in_array($sitePage, array("management_report")) && ($requestMethod == 'POST') && (isset($posts->lreview) or isset($posts->mreview)) and ( (in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or 
		(in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1')
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
			( (in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		endif;

		if ($err==0) {
			$revDet=new stdClass();
			$revDet->user=$userinfo->email;
			$revDet->comment=$posts->comment;
			$revLog='manager';
			if( ((in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0') and isset($posts->lreview) ):
				$ezDb->query("UPDATE `management_report` SET `status`='$posts->status', `manager_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'");
				alertUser($report->user,0,'Management Report has been reviewed by Manager');
			elseif( ((in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0') and isset($posts->mreview) ):
				$revLog='md';
				$ezDb->query("UPDATE `management_report` SET `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'");
				alertUser($report->user,0,'Management Report has been reviewed by MD');
			endif;
      		logEvent($userinfo->email, "report-reviewed-by-$revLog", $userinfo->usertype, 'reports', $revDet);
			$report=$ezDb->get_row("SELECT * FROM `management_report` WHERE `reportid`='$id'");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully reviewed.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("management_report")) && ($requestMethod == 'POST') && isset($Site["post"]['report']) and ((in_array($report->status, array('0', '4','3')) and $userinfo->email==$report->user) or $userinfo->userrole=='level0') ) {
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		if( empty(trim($posts->branchid)) ):
			$err++;
			$fail.='<p>Select Branch please!</p>';
		endif;
		if( empty(trim($posts->department)) ):
			$err++;
			$fail.='<p>Enter Department please!</p>';
		endif;
		if( empty(trim($posts->date)) ):
			$err++;
			$fail.='<p>Enter Date please!</p>';
		endif;
		if( empty(trim($posts->period)) ):
			$err++;
			$fail.='<p>Enter Period please!</p>';
		endif;
		if( empty(trim($posts->new_business)) ):
			$err++;
			$fail.='<p>Enter New Business Production please!</p>';
		endif;
		if( empty(trim($posts->old_business)) ):
			$err++;
			$fail.='<p>Enter Old Business Renewal please!</p>';
		endif;
		if( empty(trim($posts->prospect)) ):
			$err++;
			$fail.='<p>Enter Prospects please!</p>';
		endif;
		if( empty(trim($posts->proposal_sent)) ):
			$err++;
			$fail.='<p>Enter Proposal Sent please!</p>';
		endif;
			if( empty(trim($posts->client_inspection)) ):
			$err++;
			$fail.='<p>Enter Clients For Inspection please!</p>';
		endif;
		if( empty(trim($posts->business_received)) ):
			$err++;
			$fail.='<p>Enter New Business Received please!</p>';
		endif;
		if(!in_array($userinfo->userrole, array('level0','level1', 'level2'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		if ($err==0) {
		    $ezDb->query("UPDATE `management_report` SET `branchid`='$posts->branchid', `team`='$posts->team', `date`='$posts->date', `period`='$posts->period', `new_business`='$posts->new_business', `old_business`='$posts->old_business', `prospect`='$posts->prospect',`proposal_sent`='$posts->proposal_sent',`client_inspection`='$posts->client_inspection',`business_received`='$posts->business_received' WHERE `reportid`='$id';");
      		logEvent($userinfo->email, "management-report-updated", $userinfo->usertype, 'reports', $report);
			$report=$ezDb->get_row("SELECT * FROM `management_report` WHERE `reportid`='$id'");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull updated.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}
	
		$report->contentln=testinputReverse($report->content);
		$report->content=testinputReverse($report->content);
		$report->content_stripe=stripeInputReverse($report->content);
		$report->content_stripe=str_replace("&quot;", "\"", $report->content_stripe);
		$report->content_stripe=str_replace("&nbsp;", " ", $report->content_stripe);
	}else{
	redirect('management_report');
}
$smarty->assign("report", $report);