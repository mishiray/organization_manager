<?php 
$whereClause="";

$galleries=tableRoutine("gallery", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '20');
if(!empty($galleries)){
}
$smarty->assign("galleries", $galleries);
