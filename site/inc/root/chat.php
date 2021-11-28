<?php
$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$peoples = $ezDb->get_results("SELECT * FROM `userprofile` WHERE `usertype` = 'admin' AND  `email` != '$userinfo->email' AND `active` = 1");
/*
$receivers = new stdClass();
$receivers_array = [];
$receivers_array2 = [];
$recent_peoples = $ezDb->get_results("SELECT `receivers` FROM `messages` WHERE (`sender` = '$userinfo->email') OR (`receivers` LIKE '%$userinfo->email%') GROUP BY `receivers` ORDER BY `datesent` DESC");
if(!empty($recent_peoples)){
	foreach($recent_peoples as $recent){
		$recent = json_decode($recent->receivers);
		if(count($recent) > 1){
			foreach($recent as $rec){
				array_push($receivers_array,($rec));
			}
		}else{
			array_push($receivers_array,(implode($recent)));
		}
	}
	$receivers_array = array_unique($receivers_array, SORT_STRING);
}
foreach($receivers_array as $rec){
	$receivers = new stdClass();
	if($rec != $userinfo->email){
		$receivers->email = $rec;
		$receivers->employeeid = emailToId($rec);
		$checker = $ezDb->get_var("SELECT `readers` FROM `messages` WHERE `sender` = '$rec' AND `receivers` LIKE '%$userinfo->email%'  ORDER BY `datesent` DESC");
		$checker = json_decode($checker);
		if(in_array($userinfo->email, $checker)){
			//print_r($checker);
			$receivers->hasread = 1;
		}else{
			$receivers->hasread = 0;
		}
		$receivers->fullname = ucwords($ezDb->get_var("SELECT CONCAT(`firstname`,' ',`lastname`) as fullname  FROM `userprofile` WHERE `email` = '$rec'"));
	}
	array_push($receivers_array2, $receivers);
}
*/
$smarty->assign("peoples",$peoples);
//->assign("recent_peoples",$receivers_array2);

