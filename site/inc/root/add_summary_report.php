<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;
$branches = $ezDb->get_results("SELECT * FROM `id_zone` ");
$smarty->assign("branches", $branches);

if ( in_array($sitePage, array("add_summary_report")) && ($requestMethod == 'POST') && isset($Site["post"]['add_summary_report']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->branch_name)) ):
		$err++;
		$fail.='<p>Enter Branch please!</p>';
	endif;
	if( empty(trim($posts->team)) ):
		$err++;
		$fail.='<p>Enter Team please!</p>';
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
	if( empty(trim($posts->achievements)) ):
		$err++;
		$fail.='<p>Enter achievements please!</p>';
	endif;
	if( empty(trim($posts->challenges)) ):
		$err++;
		$fail.='<p>Enter challenges faced please!</p>';
	endif;
	if( empty(trim($posts->suggestions)) ):
		$err++;
		$fail.='<p>Enter suggestions please!</p>';
	endif;
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2','level4'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;

	$posts->achievements = cleanUp($posts->achievements);
	$posts->challenges = cleanUp($posts->challenges);
	$posts->suggestions = cleanUp($posts->suggestions);

	if ($err==0) {
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `summary_report` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `summary_report`(`reportid`,`employeeid`, `branchid`, `team`, `date`, `period`, `new_business`, `old_business`, `prospect`,`proposal_sent`,`client_inspection`,`business_received`,`achievements`,`challenges`,`suggestions`,`manager_review`,`md_review`) VALUES 
										('$token','$userinfo->employeeid','$posts->branch_name','$posts->team','$posts->date','$posts->period','$posts->new_business','$posts->old_business','$posts->prospect','$posts->proposal_sent','$posts->client_inspection','$posts->business_received','$posts->achievements','$posts->challenges','$posts->suggestions','','');";
		if($ezDb->query($query)){
			$report=$ezDb->get_row("SELECT * FROM `summary_report` WHERE `reportid`='$token';");
      		logEvent($userinfo->email, "new-summary_report-added", $userinfo->usertype, 'reports', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Weekly/Monthly Summary Report has been successfully added.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed to add Weekly/monthly Summary Report records</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}