<?php

$employeeid = ($gets->id) ? $gets->id : '';
$guarantors=$ezDb->get_results("SELECT * from `guarantors` WHERE `employeeid` = '$employeeid';");
if(!empty($guarantors)){
	foreach($guarantors as $value){
		$value->employee = $ezDb->get_var("SELECT CONCAT(`surname`,' ',`first_name`) as name FROM `employees` WHERE `employeeid` = '$value->employeeid' ");
	}
}
$smarty->assign("guarantors", $guarantors);

