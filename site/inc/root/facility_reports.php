<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;
	
$whereClause = 'WHERE `id` != 0 ';
if($userinfo->department != 'Facility' || $userinfo->userrole != 'level0'){
	$whereClause.=" AND `email` = '$userinfo->email'";
}
$tasks=$ezDb->get_results("SELECT * from `facility_report` $whereClause ORDER BY `id` DESC");
if(!empty($tasks)){
	foreach($tasks as $value){
		if(!empty($value->facility)){
			$value->fac_name =  $ezDb->get_var("SELECT `name` from `inventory` WHERE `serial_number` = '$value->facility'");
		}
	}
}
$smarty->assign("tasks", $tasks);
    
