<?php
$userinfo=$Site["session"]["User"]["userinfo"];

$receivers = new stdClass();
$receivers_array = [];
$receivers_array2 = [];
$recent_peoples = $ezDb->get_results("SELECT `receivers`,`datesent` FROM `messages` WHERE (`sender` = '$userinfo->email') OR (`receivers` LIKE '%$userinfo->email%') GROUP BY `receivers` ORDER BY `id` DESC");

if(!empty($recent_peoples)){
	foreach($recent_peoples as $recent2){
		$recent = json_decode($recent2->receivers);
		if(count($recent) > 1){
			foreach($recent as $rec){
				array_push($receivers_array,($rec));
			}
		}else{
			array_push($receivers_array,(implode($recent)));
			$recent2->email = implode($recent);
			$recent2->fullname = ucwords($ezDb->get_var("SELECT CONCAT(`firstname`,' ',`lastname`) as fullname  FROM `userprofile` WHERE `email` = '$recent2->email'"));
	
		}
	}
	//$receivers_array = array_unique($receivers_array, SORT_STRING);
}
foreach($receivers_array as $rec){
	$receivers = new stdClass();
	if($rec != $userinfo->email){
		$receivers->email = $rec;
		$receivers->employeeid = emailToId($rec);
		$receivers->hasred = 0;		
		$receivers->fullname = ucwords($ezDb->get_var("SELECT CONCAT(`firstname`,' ',`lastname`) as fullname  FROM `userprofile` WHERE `email` = '$rec'"));
	}
	array_push($receivers_array2, $receivers);
}
$returnMessage = $receivers_array2;
echo @json_encode($returnMessage);
exit();
