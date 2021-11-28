<?php

$isadmin = 'yes';
$whereClause="`id` != 0";
if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ):	
	$isadmin = 'no';
	$whereClause="`madeby` = '$userinfo->email'";
endif;

$transactions=$ezDb->get_results("SELECT * from `transactions` WHERE $whereClause");
if(!empty($transactions)){
	foreach ($transactions as $value) {
		$value->user=$ezDb->get_row("SELECT CONCAT(`firstname`, ' ', `lastname`) AS `names`, `phone`, `email` FROM `userprofile` WHERE `email`='$value->madeby';");
	}
}
$smarty->assign("transactions", $transactions);