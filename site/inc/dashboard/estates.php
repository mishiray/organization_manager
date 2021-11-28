<?php 

$whereClause="`id` != 0";
//$events=$ezDb->get_results("Select * from `projects` WHERE `active` != 0 ORDER BY `id` DESC");

//$events=$ezDb->get_results("SELECT * from `projects` $whereClause ORDER BY `id` DESC ");
$events=tableRoutine("projects", '*' , "$whereClause", '`id`', 'DESC', '`id`', 6);

$smarty->assign("property", $events);
