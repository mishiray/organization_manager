<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
	redirect("home");
endif;



$whereClause="";

$salary_records=$ezDb->get_results("SELECT * from `employment_salary_records`");
$smarty->assign("salary_records", $salary_records);