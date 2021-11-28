<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) ):
	redirect("home");
endif;

$depId=(!empty($gets->id)? $gets->id: "");

if (!empty($posts->triggers) and $posts->triggers=='delete_department' and !empty($depId)) {
	$ezDb->query("DELETE FROM `department` WHERE `id`='$depId';");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Department was successfully deleted.</p></div>';
}

$whereClause="";

$departments=$ezDb->get_results("SELECT * from `department`");
if(!empty($departments)){

	foreach ($departments as $value) {
		$value->emp_no = $ezDb->get_var("SELECT COUNT(`email`) FROM `employees` where `department_id` = '$value->id'");
	}
}

$smarty->assign("departments", $departments);