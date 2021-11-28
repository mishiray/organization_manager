<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;



$whereClause="";

$investments=$ezDb->get_results("SELECT * from `investments`");
$smarty->assign("investments", $investments);