<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
//	redirect("home");
endif;

$gId=(!empty($gets->id)? $gets->id: "");
$applicant = $ezDb->get_row("SELECT * FROM `leave_application` WHERE `token`='$gId';");
if(!empty($applicant)){
	$applicant->email = $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid`='$applicant->employeeid';");
	if (!empty($posts->triggers) and $posts->triggers=='delete_app' and !empty($gId)) {
		$ezDb->query("DELETE FROM `leave_application` WHERE `token`='$gId';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Application was successfully deleted.</p></div>';
		alertUser($applicant->email,0,"Leave Application Deleted");
	}
	if (!empty($posts->triggers) and $posts->triggers=='approve_app' and !empty($gId)) {
		$ezDb->query("UPDATE `leave_application` SET `status` = 1, `reviewed_by` = '$userinfo->email' WHERE `token`='$gId';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Application was successfully Approved.</p></div>';
		alertUser($applicant->email,0,"Leave Application Approved");
	}
	if (!empty($posts->triggers) and $posts->triggers=='disapprove_app' and !empty($gId)) {
		$ezDb->query("UPDATE `leave_application` SET `status` = 2, `reviewed_by` = '$userinfo->email' WHERE `token`='$gId';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Application was successfully Disapproved.</p></div>';
		alertUser($applicant->email,2,"Leave Application Disapproved");
	}
}

$isadmin = 'no';

$whereClause="`employeeid` = '$userinfo->employeeid'";
//echo $userinfo->userrole;
if($userinfo->department == 'Human Resources' && $userinfo->userrole == 'level2'){
	$isadmin = 'yes';
	$whereClause="`id` != 0";
}
if(in_array( $userinfo->userrole, array('level0', 'level1'))){
	$isadmin = 'yes';
	$whereClause="`id` != 0";
}

$leave_applications=$ezDb->get_results("SELECT * from `leave_application` WHERE $whereClause ORDER BY `id` DESC");

$smarty->assign("leave_applications", $leave_applications)->assign("isadmin", $isadmin);