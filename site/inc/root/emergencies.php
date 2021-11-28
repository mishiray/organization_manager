<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
//	redirect("home");
endif;

$gId=(!empty($gets->id)? $gets->id: "");
if (!empty($posts->triggers) and $posts->triggers=='delete_emergency' and !empty($gId)) {
	$ezDb->query("DELETE FROM `emergency_contact` WHERE `id`='$gId';");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Contact was successfully deleted.</p></div>';
}

$isadmin = 'yes';
$whereClause="`id` != 0";
if( !in_array( $userinfo->userrole, array('level0', 'level1')) ):	
	$isadmin = 'no';
	$whereClause="`employeeid` = '$userinfo->employeeid'";
endif;

$emergencies=$ezDb->get_results("SELECT * from `emergency_contact` WHERE $whereClause");
if(!empty($emergencies)){
	foreach ($emergencies as $value) {
		$value->name = $value->surname." ".$value->firstname; 
		$value->employee= $ezDb->get_row("SELECT `first_name`,`surname`,`employeeid` FROM `employees` where `employeeid` = '$value->employeeid'");
	}
}

$smarty->assign("emergencies", $emergencies)->assign("isadmin", $isadmin);