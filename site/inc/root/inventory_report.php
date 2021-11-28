<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;
$zones = $ezDb->get_results("SELECT * FROM `id_zone` ");

$whereClause=" WHERE `id` != 0 ";
$filter = new stdClass();
if (in_array($sitePage, array("inventory_report")) && ($requestMethod == 'POST') && $posts->triggers=='inventory_filter') {
	if (empty($posts->location)):
		$whereClause .= "";
	else: 
		$value = $posts->location;
		$search = substr($value,3);
		$regex = "BPNL/%/$search/%";
		$whereClause .= " AND `serial_number` LIKE '$regex' ";
		$filter->location = $posts->location;
	endif;
}

$inventories=$ezDb->get_results("SELECT * from `inventory` $whereClause");

if(!empty($inventories)){
	foreach ($inventories as $value) {
		if($value->network_id){
			$value->network=$ezDb->get_row("SELECT * from  `network` WHERE `id` = $value->network_id;");
		}
		if($value->dept_id){
			$value->department=$ezDb->get_row("SELECT * from  `department` WHERE `id` = $value->dept_id;");
		}
	}
}
$smarty->assign("inventories", $inventories)->assign("zones",$zones)->assign("filter",$filter);