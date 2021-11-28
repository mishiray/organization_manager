<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;


$isadmin = 'yes';


$whereClause=" `id` != 0 ";

$employee_id = (!empty($gets->id)? $gets->id: "");
if(!empty($employee_id)){
	$whereClause.= " AND `employeeid` = '$employee_id' ";
}

if( !in_array( $userinfo->userrole, array('level0', 'level1')) ):	
	$isadmin = 'no';
endif;

$appraisals=$ezDb->get_results("SELECT * from `appraisal` WHERE $whereClause ORDER BY `id` DESC");

$smarty->assign("appraisals", $appraisals)->assign("isadmin", $isadmin);