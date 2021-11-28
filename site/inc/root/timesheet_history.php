<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$filter = new stdClass();


$settings=$ezDb->get_var("SELECT `working_hours` FROM `settings`;");
//echo $settings;
if(!empty($settings)){
    //echo 'ok';
    $settings = json_decode($settings);
    //print_r($settings);
    $startTime = $settings->open_hour_string;
    $startTime = new DateTime($startTime);
    $startTime = $startTime->format('H:i');
    $startTime = strtotime($startTime);
}else{
    $startTime = $startTime;
}

$isadmin = 'yes';
$whereClause = "";
$whereEmployee = "";
$data_in = '';
$data_out = '';

if( !in_array( $userinfo->userrole, array('level0', 'level1','level3','level2')) ):	
    $isadmin = 'no';
endif;

if( in_array( $userinfo->userrole, array('level2', 'level3')) ):
	$whereEmployee = " WHERE `manager_id` = '$userinfo->employeeid'";
endif;
$whereClause = " WHERE `id` != 0 ";
if (in_array($sitePage, array("timesheet_history")) && ($requestMethod == 'POST') && $posts->triggers=='timesheet') {
    $whereClause = " WHERE `id` != 0 ";
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`dateadded`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`dateadded`) = '$posts->month' ";
		$filter->month = $posts->month;
	endif;
	if (empty($posts->email)):
		if($isadmin != 'yes'){ 
			$whereClause .= " AND `email` = '$userinfo->email' ";
		}else{
			$whereClause .= "";
		}
	else:
		if($isadmin != 'yes'){
			$whereClause .= " AND `email` = '$userinfo->email' ";
			$filter->email = $userinfo->email;
		}else{
			$whereClause .= " AND `email` = '$posts->email'";
			$filter->email = $posts->email;
		}
	endif;

    
}else{
    if($isadmin == 'yes'){
        $whereClause .= "";
    }else{
        $whereClause .= " AND `email` = '$userinfo->email'";
    }
        
}

$employees=$ezDb->get_results("SELECT * FROM `employees` $whereEmployee ORDER BY `employeeid` ASC ");
$timesheet = $ezDb->get_results("SELECT * FROM `timesheet` $whereClause ORDER BY `id` DESC");
if(!empty($timesheet)){
    foreach ($timesheet as $value) {
        $value->name = $ezDb->get_row("SELECT * FROM `employees` where `email` = '$value->email'");
        $value->data_in = json_decode($value->location);
        $value->data_out = json_decode($value->location_out);
        $date = new DateTime($value->login);
        $newDate = $date->format('H:i');
        //echo date("H:i:s",$value->login);
        //echo ' ';
        //$diff = $startTime - strtotime($newDate);
        $value->status = ($startTime < strtotime($newDate)) ? 'Late' : 'On Time';
        //$value->diff = abs($diff);
    }
}

$config['date'] = '%I:%M %p';
$smarty->assign("timesheet", $timesheet)->assign('config', $config)->assign('isadmin', $isadmin)->assign('employees', $employees);
$smarty->assign("filter",$filter)->assign("months", $months)->assign('data_in', $data_in)->assign('data_out', $data_out);