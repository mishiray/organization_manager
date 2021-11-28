<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;
	
$tasks=$ezDb->get_results("SELECT * from `variance_report` ORDER BY `id` DESC");
$smarty->assign("tasks", $tasks);
    
