<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$whereClause=($userinfo->userrole=='level2') ? " WHERE `department`='$userinfo->department'" : (($userinfo->userrole=='level0') ? "" : "" );
	
$isadmin = 'yes';

if( !in_array( $userinfo->userrole, array('level0', 'level1','level2')) ):	
    $isadmin = 'no';
endif;

if ($isadmin == 'yes') {
    $tasks=$ezDb->get_results("SELECT * from `daily_task` $whereClause ORDER BY `id` DESC");
    $smarty->assign("tasks", $tasks);
    
}else{
    
    $tasks=$ezDb->get_results("SELECT * from `daily_task` WHERE `employeeid` = '$userinfo->employeeid' ORDER BY `id` DESC");
    $smarty->assign("tasks", $tasks);
    
}