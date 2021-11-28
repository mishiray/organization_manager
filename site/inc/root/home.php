<?php global $sitePage, $Site, $ezDb, $siteName, $smarty;
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2', 'level3', 'level4')) ):
	redirect("profile");
endif;


$year_filter = $dateY;
$filter = new stdClass();	
if (in_array($sitePage, array('home','index')) && ($requestMethod == 'POST') && $posts->triggers=='chart_year') {
	//$err=0;
	$whereClause = " ";
	echo "im clicked";
	if (empty($posts->year)):
		$posts->year = $dateY;
	else: 
		$filter->year = $posts->year;
	endif;
	//clients reg chart
	$query = "SELECT
	SUM(IF(months = 'January', total, 0)) AS 'Jan',
	SUM(IF(months = 'February', total, 0)) AS 'Feb',
	SUM(IF(months = 'March', total, 0)) AS 'Mar',
	SUM(IF(months = 'April', total, 0)) AS 'Apr',
	SUM(IF(months = 'May', total, 0)) AS 'May',
	SUM(IF(months = 'June', total, 0)) AS 'Jun',
	SUM(IF(months = 'July', total, 0)) AS 'Jul',
	SUM(IF(months = 'August', total, 0)) AS 'Aug',
	SUM(IF(months = 'Septembet', total, 0)) AS 'Sep',
	SUM(IF(months = 'October', total, 0)) AS 'Oct',
	SUM(IF(months = 'November', total, 0)) AS 'Nov',
	SUM(IF(months = 'December', total, 0)) AS 'Dec'
	FROM (
		SELECT MONTHNAME(`reg_date`) as months, COUNT(`reg_id`) as total FROM `subscription` WHERE YEAR(`reg_date`) = '$posts->year' GROUP BY MONTH(`reg_date`) 
	) as sub";
	$subscribers = $ezDb->get_row($query);

		
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
	SUM(IF(months = 'Septembet', total, 0)) AS 'Sep',
	SUM(IF(months = 'October', total, 0)) AS 'Oct',
	SUM(IF(months = 'November', total, 0)) AS 'Nov',
	SUM(IF(months = 'December', total, 0)) AS 'Dec'
	FROM (
		SELECT MONTHNAME(`transdate`) as months, SUM(`amount`) as total FROM `payments` WHERE `status` = 1 AND YEAR(`transdate`) = '$posts->year' GROUP BY MONTH(`transdate`) 
	) as sub";
	$earnings = $ezDb->get_row($query);
}else{

		
	//clients reg chart
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
		SELECT MONTHNAME(`reg_date`) as months, COUNT(`reg_id`) as total FROM `subscription` WHERE YEAR(`reg_date`) = '$year_filter' GROUP BY MONTH(`reg_date`) 
	) as sub";
	$subscribers = $ezDb->get_row($query);
	//print_r($subscribers);

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
}

//print_r($filter);
$earnings->sub_source = $ezDb->get_var("SELECT SUM(`total_paid`) as sub_amount FROM `subscription`");
$earnings->inv_source = $ezDb->get_var("SELECT SUM(`total_paid`) as inv_amount FROM `investment_subscription`");


$dashStat=new stdClass();
$dashStat->news=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `news`;");
$dashStat->business=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `business`;");
$dashStat->blogs=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `blog`;");
$dashStat->events=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `events`;");
$dashStat->projects=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `projects`;");
$dashStat->donations=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `donation`;");
$dashStat->jobs=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `jobs`;");
$dashStat->members=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `userprofile` WHERE `usertype`='client' AND `verified`='1';");
$smarty->assign("dashStat", $dashStat)->assign("earnings", $earnings)->assign("subscribers", $subscribers)->assign("filter", $filter);