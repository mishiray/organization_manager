<?php

$type=(!empty($gets->type)? $gets->type: "");

$whereClause="";
if($type == 'subscription'){
	$clients=$ezDb->get_results("SELECT * from `subscription` WHERE `status` != 0 AND `paid`<> 1 AND `email` = '$userinfo->email'  ORDER BY `reg_date` DESC");
}
else{
	$clients=$ezDb->get_results("SELECT * from `investment_subscription` WHERE `paid`<> 1 AND `email` = '$userinfo->email'  ORDER BY `reg_date` DESC");
}
if (!empty($clients)) {
	foreach($clients as $value) {
		$value->date = $ezDb->get_row("SELECT DATEDIFF(CURDATE(),'$value->reg_date') AS `datediff`");
	}
}

$smarty->assign("defclients", $clients)->assign("type",$type);