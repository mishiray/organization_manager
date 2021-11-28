<?php 

$employee_id = (!empty($gets->id)? $gets->id: "");
$bwhereClause = '';
if(!empty($employee_id)){
	$bwhereClause = " WHERE `employeeid` = '$employee_id' ";
}

$employees=$ezDb->get_results("SELECT * from `employees` $bwhereClause");
$warnings=$ezDb->get_results("SELECT * from `disciplinary_warnings`");
$actions=$ezDb->get_results("SELECT * from `disciplinary_action`");
if(!empty($employees)){
	foreach($employees as $value){
		if($value->manager_id){
			$manager = $ezDb->get_row("SELECT `first_name`, `surname` from `employees` WHERE `employeeid` = '$value->manager_id'");
			$value->manager = ucwords($manager->surname.' '.$manager->first_name);
		}else{
			$value->manager = '';
		}
	}
}
$smarty->assign("employees", $employees)->assign("actions", $actions)->assign("warnings", $warnings);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2','level3')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_disciplinary")) && ($requestMethod == 'POST') && isset($Site["post"]['add_disciplinary']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->employeeid)) ):
		$err++;
		$fail.='<p>Select an Employee please!</p>';
	endif;
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter Employee name please!</p>';
	endif;
	if( empty(trim($posts->warning_date)) ):
		$err++;
		$fail.='<p>Enter Warning Date please!</p>';
	endif;
	if( empty(trim($posts->misconduct_date)) ):
		$err++;
		$fail.='<p>Enter Date of Misconduct please!</p>';
	endif;
	if(empty(trim($posts->manager)) ):
		$err++;
		$fail.='<p>Enter Manager please!</p>';
	endif;
	if( empty(trim($posts->hr_head)) ):
		$err++;
		$fail.='<p>Enter HR_Head please!</p>';
	endif;
	if( empty(trim($posts->warning_type)) ):
		$err++;
		$fail.='<p>Enter Type of Warnings please!</p>';
	endif;
	if( empty(trim($posts->warning_description)) ):
		$err++;
		$fail.='<p>Enter Description of Warnings please!</p>';
	endif;
	if( empty(trim($posts->action_plan)) ):
		$err++;
		$fail.='<p>Enter Action Plan please!</p>';
	endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;
	if ($err==0) {
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `daily_task` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `disciplinary`(`token`,`employeeid`, `name`, `warning_date`, `misconduct_date`, `manager`, `hr_head`, `warning_type`, `warning_description`, `action_plan`) VALUES ('$token','$posts->employeeid','$posts->name','$posts->warning_date','$posts->misconduct_date','$posts->manager','$posts->hr_head','$posts->warning_type','$posts->warning_description','$posts->action_plan');";
		if($ezDb->query($query)){	
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Disciplinary has been added to records and sent to the offender</p></div>';
			$email = idToEmail($posts->employeeid);
			alertUser($email,2,'You have been issued a query');
			$report=$ezDb->get_row("SELECT * FROM `disciplinary` WHERE `token`='$token';");
			logEvent($userinfo->email, "new-disciplinary-added", $userinfo->usertype, 'disciplinary', $report);
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add disciplinary records</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}