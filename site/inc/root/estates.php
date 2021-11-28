<?php
$whereClause = "`id` != 0";
//$events=$ezDb->get_results("SELECT * from `projects` ORDER BY `id` DESC ");
$estates_red = tableRoutine("projects", '*', "$whereClause", '`id`', 'DESC', '`id`', 6);

$smarty->assign("property", $estates_red);
