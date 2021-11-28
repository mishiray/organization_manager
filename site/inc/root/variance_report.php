<?php 

$token=(!empty($gets->token)? $gets->token: "");

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) ):
	redirect("home");
endif;

$report=$ezDb->get_row("SELECT * FROM `variance_report` WHERE `token`='$token'");
if (!empty($report)) {

	if ( in_array($sitePage, array("variance_report")) && ($requestMethod == 'GET') && isset($Site["get"]['evt'])){
		if (!empty($report) and $gets->evt=='delete') {
			$ezDb->query("DELETE FROM `variance_report` WHERE `token`='$token'");
			logEvent($userinfo->email, "report-deleted", $userinfo->usertype, 'variance_report', $report);
			alertUser($report->email,1,'Variance Report has been deleted');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully deleted.</p></div>';
		}
		if (!empty($report) and $gets->evt=='acknowledge') {
			$ezDb->query("UPDATE `variance_report` SET `status` = 2 WHERE `token`='$token'");
			logEvent($userinfo->email, "report-acknowledged", $userinfo->usertype, 'variance_report', $report);
			alertUser($report->email,1,'Variance Report has been acknowledged');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully acknowledged.</p></div>';
		}
		if (!empty($report) and $gets->evt=='done') {
			$ezDb->query("UPDATE `variance_report` SET `status` = 3, `datecompleted` = '$dateNow' WHERE `token`='$token'");
			logEvent($userinfo->email, "report-done", $userinfo->usertype, 'variance_report', $report);
			alertUser($userinfo->email,1,'Variance Report has been done');
			alertUser($report->email,1,'Variance Report has been done');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully finished.</p></div>';
		}
		if (!empty($report) and $gets->evt=='accept') {
			$ezDb->query("UPDATE `variance_report` SET `status` = 4 WHERE `token`='$token'");
			logEvent($userinfo->email, "report-accepted", $userinfo->usertype, 'variance_report', $report);
			alertUser($userinfo->email,1,'Variance Report has been accepted');
			alertUser($report->email,1,'Variance Report has been accepted');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully accepted.</p></div>';
		}
		if (!empty($report) and $gets->evt=='reject') {
			$ezDb->query("UPDATE `variance_report` SET `status` = 0 WHERE `token`='$token'");
			logEvent($userinfo->email, "report-rejected", $userinfo->usertype, 'variance_report', $report);
			alertUser($userinfo->email,2,'Variance Report was rejected');
			alertUser($report->email,2,'Variance Report was rejected');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfully rejected.</p></div>';
		}
	}
	
	if (in_array($sitePage, array("variance_report")) && ($requestMethod == 'POST') && isset($posts->report_update) ){
		if(empty(trim($posts->note)) ):
			$err++;
			$fail.='<p>Your comment is required please!</p>';
		endif;
		if( empty(trim($posts->report)) ):
			$err++;
			$fail.='<p>Enter report description please!</p>';
		endif;
		if( empty(trim($posts->pages)) ):
			$err++;
			$fail.='<p>Enter pages please!</p>';
		endif;
		if( empty(trim($posts->severity)) ):
			$err++;
			$fail.='<p>Choose severity please!</p>';
		endif;
		if( empty(trim($posts->status)) ):
			$err++;
			$fail.='<p>Enter status please!</p>';
		endif;

		$posts->note=cleanUp($posts->note);
		$posts->report=cleanUp($posts->report);
		
		if ($err==0) {
			$query = "UPDATE `variance_report` SET `pages`='$posts->pages', `status`='$posts->status', `severity`='$posts->severity',`note`='$posts->note', `report`='$posts->report' WHERE `token`='$token'";
			if($ezDb->query($query)){
				alertUser($userinfo->email,0,'Variance Report has been updated');
				$report=$ezDb->get_row("SELECT * FROM `variance_report` WHERE `token`='$token'");
				logEvent($userinfo->email, "report-updated", $userinfo->usertype, 'variance_report', $report);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull updated.</p></div>';
		
      		}else{
				$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
	}

}else{
	redirect('variance_reports');
}
$smarty->assign("report", $report);