<?php 


$employee_id = (!empty($gets->id)? $gets->id: "");
$bwhereClause = '';
if(!empty($employee_id)){
	$bwhereClause = " WHERE `employeeid` = '$employee_id' ";
}

$employees=$ezDb->get_results("SELECT * from `employees` $bwhereClause");
if(!empty($employees)){
	foreach($employees as $value){
		if($value->manager_id){
			$manager = $ezDb->get_row("SELECT `first_name`, `surname` from `employees` WHERE `employeeid` = '$value->manager_id'");
			$value->manager = ucwords($manager->surname.' '.$manager->first_name);
		}else{
			$value->manager = ' ';
		}
	}
}
$smarty->assign("employees", $employees);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2')) ):
	redirect("home");
endif;

if (in_array($sitePage, array("add_appraisal")) && ($requestMethod == 'POST') && isset($Site["post"]['add_appraisal']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->employeeid)) ):
		$err++;
		$fail.='<p>Select an Employee please!</p>';
	endif;
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter Employee name please!</p>';
	endif;
	if( empty(trim($posts->appraisal_date)) ):
		$err++;
		$fail.='<p>Enter Appraisal_Date please!</p>';
	endif;
	if( empty(trim($posts->next_appraisal_date)) ):
		$err++;
		$fail.='<p>Enter Date of Misconduct please!</p>';
	endif;
	if( empty(trim($posts->timekeeping)) ):
		$err++;
		$fail.='<p>Enter Timekeeping please!</p>';
	endif;
	if( empty(trim($posts->attendance)) ):
		$err++;
		$fail.='<p>Enter Attendance please!</p>';
	endif;
	if( empty(trim($posts->performance)) ):
		$err++;
		$fail.='<p>Enter Performance please!</p>';
	endif;
	if( empty(trim($posts->initiative)) ):
		$err++;
		$fail.='<p>Enter Initiative please!</p>';
	endif;
	if( empty(trim($posts->decision)) ):
		$err++;
		$fail.='<p>Enter Decision Making please!</p>';
	endif;
	if( empty(trim($posts->communication)) ):
		$err++;
		$fail.='<p>Enter Appearance please!</p>';
	endif;
	if(empty(trim($posts->leadership)) ):
		$err++;
		$fail.='<p>Enter Leadership please!</p>';
	endif;
	if(empty(trim($posts->financial_contribution)) ):
		$err++;
		$fail.='<p>Enter financial contribution please!</p>';
	endif;
	if( empty(trim($posts->over_per)) ):
		$err++;
		$fail.='<p>Enter Overall Performance please!</p>';
	endif;
	if( empty(trim($posts->over_comment)) ):
		$err++;
		$fail.='<p>Enter Overall Comment please!</p>';
	endif;
	$posts->over_comment = cleanUp( $posts->over_comment ); 
	if ($err==0) {
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `appraisal` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `appraisal`(`token`,`employeeid`, `name`, `appraisal_date`, `next_appraisal_date`, `manager`, `timekeeping`, `attendance`, `performance`,`appearance`, `initiative`, `decision_making`, `communication`, `leadership`, `financial_contribution`,`overall_performance`, `overall_comment`) VALUES ('$token','$posts->employeeid','$posts->name','$posts->appraisal_date','$posts->next_appraisal_date','$posts->manager','$posts->timekeeping','$posts->attendance','$posts->performance','$posts->appearance','$posts->initiative','$posts->decision','$posts->communication','$posts->leadership','$posts->financial_contribution','$posts->over_per','$posts->over_comment');";
		if($ezDb->query($query)){
			$email = idToEmail($posts->employeeid);
			alertUser($email,0,'Your appraisal record has been updated');
			$report=$ezDb->get_row("SELECT * FROM `appraisal` WHERE `token`='$token';");
    		logEvent($userinfo->email, "new-appraisal-added", $userinfo->usertype, 'appraisals', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Appraisal record has been successfully added.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add Appraisal records</div>';
		}
	    }else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}