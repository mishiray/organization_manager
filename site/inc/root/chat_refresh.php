<?php
$userinfo=$Site["session"]["User"]["userinfo"];


$returnMessage = $ezDb->get_results("SELECT * FROM `messages` WHERE (`sender` LIKE '%$userinfo->email%' AND `receivers` LIKE '%$posts->receiver%') OR (`sender` LIKE '%$posts->receiver%' AND `receivers` LIKE '%$userinfo->email%') ORDER BY `datesent` ASC");
if(!empty($returnMessage)){
	foreach($returnMessage as $value){
		$value->readers = json_decode($value->readers);
		
		if (!in_array($userinfo->email, $value->readers)) {
			array_push($value->readers, $userinfo->email);
			$ezDb->query("UPDATE `messages` SET `readers`='".json_encode($value->readers)."' WHERE `messageid`='$value->messageid';");
		}
		
		$value->receiver = ucwords($ezDb->get_var("SELECT CONCAT(`firstname`,' ',`lastname`) as fullname  FROM `userprofile` WHERE `email` = '$posts->receiver'"));
		$date = new DateTime($value->datesent);		
		$value->datesent = date_format($date,'F j, Y, g:i a');
	}
}

echo @json_encode($returnMessage);
exit();
