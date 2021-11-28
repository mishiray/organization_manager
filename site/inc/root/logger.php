<?php
$whereClause="";
$loggers=tableRoutine("logger", '*' , " `id`!=0 $whereClause", '`id`');

if (!empty($loggers)) {
	foreach ($loggers as $key => $logger) {
		$logger->content=@json_decode($logger->content);
		$logger->user=$ezDb->get_row("SELECT CONCAT(`firstname`, ' ', `lastname`) AS `names`, `phone`, `email` FROM `userprofile` WHERE `email`='$logger->user';");
		$logger->eventCapped=ucwords(str_replace("-", " ", $logger->event));
		$logger->utype=($logger->usertype=='client'? 'Student': 'Admin');
	}
}
/*Do foreach and get its list of packages it belong*/

$smarty->assign("loggers", $loggers);