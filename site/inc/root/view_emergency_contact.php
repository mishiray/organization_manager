<?php

$employeeid = ($gets->id) ? $gets->id : '';
$emergency=$ezDb->get_row("SELECT * from `emergency_contact` WHERE `employeeid` = '$employeeid';");
if(!empty($emergency)){
	$emergency->employee = $ezDb->get_var("SELECT CONCAT(`surname`,' ',`first_name`) as name FROM `employees` WHERE `employeeid` = '$emergency->employeeid' ");
}
$smarty->assign("emergency", $emergency);

