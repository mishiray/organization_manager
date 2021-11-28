<?php
$whereClause="";
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) and $userinfo->active==true ):
	redirect("home");
endif;
$logger=$ezDb->get_results(" SELECT * FROM `logger` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");

if (!empty($logger)) {
	foreach ($logger as $key => $value) {
	   // $value->title=testInputReverse($value->title);
	}
}
$smarty->assign("logger", $logger);