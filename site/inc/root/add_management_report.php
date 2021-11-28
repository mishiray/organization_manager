<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2')) ):
	redirect("home");
endif;
$branches = $ezDb->get_results("SELECT * FROM `id_zone` ");
$smarty->assign("branches", $branches);

if ( in_array($sitePage, array("add_management_report")) && ($requestMethod == 'POST') && isset($Site["post"]['add_management_report']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->branch_name)) ):
		$err++;
		$fail.='<p>Enter Branch please!</p>';
	endif;
	if( empty(trim($posts->date)) ):
		$err++;
		$fail.='<p>Enter Date please!</p>';
	endif;
	if( empty(trim($posts->period)) ):
		$err++;
		$fail.='<p>Enter Period please!</p>';
	endif;
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level2'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;

	$posts->achievements = cleanUp($posts->achievements);
	$posts->challenges = cleanUp($posts->challenges);
	$posts->suggestions = cleanUp($posts->suggestions);
	$posts->content=nl2br2($posts->content);
	$posts->content=tb2sp2($posts->content);
	$posts->content=testInput($posts->content);

	if ($err==0) {
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `management_report` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `management_report`(`reportid`,`employeeid`, `branchid`, `department`, `date`, `period`, `new_business`, `old_business`, `prospect`,`proposal_sent`,`client_inspection`,`business_received`,`achievements`,`challenges`,`suggestions`,`content`) VALUES 
										('$token','$userinfo->employeeid','$posts->branch_name','$posts->department','$posts->date','$posts->period','$posts->new_business','$posts->old_business','$posts->prospect','$posts->proposal_sent','$posts->client_inspection','$posts->business_received','$posts->achievements','$posts->challenges','$posts->suggestions','$posts->content');";

		if($ezDb->query($query)){
			$report=$ezDb->get_row("SELECT * FROM `management_report` WHERE `reportid`='$token';");
      		logEvent($userinfo->email, "new-management-report-added", $userinfo->usertype, 'reports', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Report has been successfully added.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Failed to Report to the records</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}