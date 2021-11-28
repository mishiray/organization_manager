<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
	//redirect("home");
endif;

$whereClause=" `id` != 0 ";

$employee_id = (!empty($gets->id)? $gets->id: "");
if(!empty($employee_id)){
	$whereClause.= " AND `employeeid` = '$employee_id' ";
}

$isadmin = 'yes';

if(!in_array( $userinfo->userrole, array('level0', 'level1')) ):	
	$isadmin = 'no';
endif;

$disciplinary=$ezDb->get_results("SELECT * from `disciplinary` WHERE $whereClause ORDER BY `id` DESC");

$smarty->assign("disciplinary", $disciplinary)->assign("isadmin", $isadmin);