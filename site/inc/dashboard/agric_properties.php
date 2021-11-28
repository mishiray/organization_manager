<?php 
$whereClause="";
//$events=$ezDb->get_results("SELECT * from `projects` ORDER BY `id` DESC ");
$events=tableRoutine("agriculture", '*' , "$whereClause", '`id`', 'DESC', '`id`', 6);

$smarty->assign("property", $events);
