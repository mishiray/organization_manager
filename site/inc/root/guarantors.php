<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
//	redirect("home");
endif;

$gId=(!empty($gets->id)? $gets->id: "");
if (!empty($posts->triggers) and $posts->triggers=='delete_guarantor' and !empty($gId)) {
	$ezDb->query("DELETE FROM `guarantors` WHERE `id`='$gId';");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Guarantor was successfully deleted.</p></div>';
}

$isadmin = 'yes';
$whereClause="`id` != 0";
if( !in_array( $userinfo->userrole, array('level0', 'level1')) ):	
	$isadmin = 'no';
	$whereClause="`employeeid` = '$userinfo->employeeid'";
endif;

$guarantors=$ezDb->get_results("SELECT * from `guarantors` WHERE $whereClause");
if(!empty($guarantors)){
	foreach ($guarantors as $value) {
		$value->name = $value->surname." ".$value->firstname; 
		$value->employee= $ezDb->get_row("SELECT `first_name`,`surname` FROM `employees` where `employeeid` = '$value->employeeid'");
	}
}

$smarty->assign("guarantors", $guarantors)->assign("isadmin", $isadmin);