<?php 
$whereClause="";
//$events=$ezDb->get_results("SELECT * from `projects` ORDER BY `id` DESC ");
$events=tableRoutine("promos", '*' , "$whereClause", '`id`', 'DESC', '`id`', 6);

$smarty->assign("promos_list", $events);
