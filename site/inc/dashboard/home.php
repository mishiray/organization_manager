<?php global $sitePage, $Site, $ezDb, $siteName, $smarty;
/*
if ($lastreg->isExpired===true or empty(((array)$lastreg))) {
	redirect("register");
}
*/
$dashStat=new stdClass();

$dashStat->news=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `news` WHERE `addedby`='$userinfo->email';");
$dashStat->blogs=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `blog` WHERE `addedby`='$userinfo->email';");
$dashStat->subscription=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `payments` WHERE `paidby`='$userinfo->email';");

// $dashStat->exams=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `results` WHERE `user`='$userinfo->email';");
// $dashStat->assignments=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `assignments` WHERE `user`='$userinfo->email';");
// $dashStat->toolbox=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `toolbox`;");


$smarty->assign("dashStat", $dashStat);