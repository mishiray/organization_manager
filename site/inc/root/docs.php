<?php
$whereClause="";
$type = (!empty($gets->type)) ? $gets->type : "";
/*Edit Project*/

$documents=$ezDb->get_results("SELECT  * FROM `pdocuments` WHERE `addedby`='$userinfo->email' AND `category` LIKE '%$type%'");

$smarty->assign("documents", $documents)->assign("type", $type);