<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3')) ):
	redirect("home");
endif;

	
$departments = $ezDb->get_results("SELECT * FROM `department` ");
$zones = $ezDb->get_results("SELECT * FROM `id_zone` ");


$whereClause="WHERE e.id != 0 AND `manager_id` = '$userinfo->employeeid' ";
$filter = new stdClass();
if (in_array($sitePage, array("employees_assigned")) && ($requestMethod == 'POST') && $posts->triggers=='employee_filter') {

	if (empty($posts->location)):
		$whereClause .= "";
	else: 
		$whereClause .= "AND `location` = '$posts->location' ";
		$filter->location = $posts->location;
	endif;
}

$employees=$ezDb->get_results("SELECT * from `employees` as e INNER JOIN `userprofile` as u on e.email = u.email $whereClause ORDER BY RIGHT(employeeid,4) ASC");

if(!empty($employees)){
	foreach($employees as $value) {
		if($value->department_id){
			$value->department= idtodepartment($value->employeeid);
		}
		if($value->manager_id){
			$manager = $ezDb->get_row("SELECT `first_name`, `surname` from `employees` WHERE `employeeid` = '$value->manager_id'");
			$value->manager = $manager->surname.' '.$manager->first_name;
		}
		$value->userProfile = $ezDb->get_row("SELECT * from  `userprofile` WHERE `email` = '$value->email';");
		if(!empty($value->userProfile)){
			$value->hasAcc = ($value->userProfile->verified == 1) ? 'yes' : 'no' ;
		}else{
			$value->hasAcc =  'no' ;
		}
	}
}
$smarty->assign("employees", $employees)->assign("urole", array("level0"=>"Super Admin", "level1"=>"BDM", "level2"=>"Manager","level3"=>"Supervisor", "level4"=>"Employee"))->assign("zones",$zones)->assign("filter",$filter);