<?php
$whereClause="";
//$projects= $ezDb->get_results("SELECT * from `projects` ORDER BY `name` ASC ");

$filter = new stdClass();
if (in_array($sitePage, array("projects")) && ($requestMethod == 'POST') && $posts->triggers=='search_property') {
	if (empty($posts->p_name)):
		$whereClause .= "";
	else: 
		$value = $posts->p_name;
		$whereClause .= " AND `name` LIKE '%$value%' ";
		$filter->p_name = $posts->p_name;
	endif;
	if (empty($posts->p_type)):
		$whereClause .= "";
	else: 
		$value = $posts->p_type;
		$whereClause .= " AND `type` = '$value' ";
		$filter->p_type = $posts->p_type;
	endif;
	if (empty($posts->location)):
		$whereClause .= "";
	else: 
		$value = $posts->location;
		$whereClause .= " AND `location` LIKE '%$value%' ";
		$filter->location = $posts->location;
	endif;
	if (empty($posts->p_limit)):
		$whereClause .= "";
	else: 
		$value = $posts->p_limit;
		$whereClause .= " AND `amt_450sqm` <= '$value' OR `amt_350sqm` <= '$value' OR `amt_600sqm` <= '$value' ";
		$filter->p_limit = $posts->p_limit;
	endif;
}

$projects=tableRoutine("projects", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '9');

$smarty->assign("pro", $projects)->assign("filter", $filter);