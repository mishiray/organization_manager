<?php
$whereClause="";
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;
/*Edit Project*/
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$filter = new stdClass();
if (in_array($sitePage, array("documents")) && ($requestMethod == 'POST') && $posts->triggers=='cert_search') {
	//$err=0;
	$whereClause = "AND `user` = '$userinfo->email' ";
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`datesent`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`datesent`) = '$posts->month' ";
		$filter->month = $posts->month;
	endif;
	
	$certificates=tableRoutine("certificate", '*' , " `id`!=0 $whereClause", '`id`');
		if (!empty($certificates)) {
			foreach ($certificates as $key => $certificate) {
				$certificate->student=$ezDb->get_row("SELECT  CONCAT(`firstname`, ' ', `middlename`, ' ', `lastname`) AS `names`, `email`, `phone`, `username` FROM `userprofile` WHERE `email`='$certificate->user'");
				$certificate->description=testInputReverse(trim($certificate->description));
				$certificate->title=testInputReverse(trim($certificate->title));
				if(!empty($certificate->certurl)){
					$images = $certificate->certurl;
					$myArray = explode(',', $images);
					$certificate->images = $myArray;
				}
			}
		}
}else{
	$certificates=tableRoutine("certificate", '*' , " `id`!=0 $whereClause", '`id`');
	if (!empty($certificates)) {
		foreach ($certificates as $key => $certificate) {
			$certificate->student=$ezDb->get_row("SELECT  CONCAT(`firstname`, ' ', `middlename`, ' ', `lastname`) AS `names`, `email`, `phone`, `username` FROM `userprofile` WHERE `email`='$certificate->user'");
			$certificate->description=testInputReverse(trim($certificate->description));
			$certificate->title=testInputReverse(trim($certificate->title));
			if(!empty($certificate->certurl)){
				$images = $certificate->certurl;
				$myArray = explode(',', $images);
				$certificate->images = $myArray;
			}
		}
	}
}
$smarty->assign("certificates", $certificates)->assign("months", $months)->assign("filter",$filter);
