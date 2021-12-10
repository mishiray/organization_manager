<?php 

global $sitePage, $Site, $ezDb, $siteName, $smarty;

if( !in_array( $userinfo->userrole, array('level0','level1', 'level2', 'level3', 'level4')) ):
	redirect("profile");
endif;


$year_filter = $dateY;
$filter = new stdClass();	
	//earnings chart
	$query = "SELECT
	SUM(IF(months = 'January', total, 0)) AS 'Jan',
	SUM(IF(months = 'February', total, 0)) AS 'Feb',
	SUM(IF(months = 'March', total, 0)) AS 'Mar',
	SUM(IF(months = 'April', total, 0)) AS 'Apr',
	SUM(IF(months = 'May', total, 0)) AS 'May',
	SUM(IF(months = 'June', total, 0)) AS 'Jun',
	SUM(IF(months = 'July', total, 0)) AS 'Jul',
	SUM(IF(months = 'August', total, 0)) AS 'Aug',
	SUM(IF(months = 'September', total, 0)) AS 'Sep',
	SUM(IF(months = 'October', total, 0)) AS 'Oct',
	SUM(IF(months = 'November', total, 0)) AS 'Nov',
	SUM(IF(months = 'December', total, 0)) AS 'Dec'
	FROM (
		SELECT MONTHNAME(`transdate`) as months, SUM(`amount`) as total FROM `payments` WHERE `status` = 1 AND YEAR(`transdate`) = '$year_filter' GROUP BY MONTH(`transdate`) 
	) as sub";

	$earnings = $ezDb->get_row($query);


$dashStat=new stdClass();
$smarty->assign("dashStat", $dashStat)->assign("earnings", $earnings)->assign("filter", $filter);