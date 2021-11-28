<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3')) ):
	redirect("home");
endif;

$whereClause="";

$job_details=$ezDb->get_results("SELECT * from `job_details`");
if(!empty($job_details)){
	foreach($job_details as $value){
		$employee = $ezDb->get_row("SELECT `department_id`, `manager_id`, `location` FROM `employees` WHERE `employeeid` = '$value->employeeid'");
		$value->manager = $ezDb->get_var("SELECT CONCAT(`first_name`, ' ', `surname`) FROM `employees` WHERE `employeeid` = '$employee->manager_id'");
		$value->department = idtodepartment($value->employeeid);
		$value->location = $employee->location;
	}
}
$smarty->assign("job_details", $job_details);