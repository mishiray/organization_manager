<?php

$type=(!empty($gets->type)? $gets->type: "");

$whereClause="";
$query = "SELECT * from `payments` WHERE `paidby` = '$userinfo->email' AND `purpose` LIKE '%$type%' ORDER BY `id` DESC " ;

$payments=$ezDb->get_results($query);

$smarty->assign("payments", $payments)->assign("type",$type);