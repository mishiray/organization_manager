<?php

$defaults= new stdClass;

$whereClause = '';
$defaults->not['project']=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `projects`;");
$defaults->not['members']=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `userprofile` WHERE `usertype`='client' AND `verified`='1';");
$smarty->assign("defaults", $defaults);

	
	//read alerts
	if(!empty($gets->alertid)){
		$alerttoken=(!empty($gets->alertid)? $gets->alertid: "");
		$alert=$ezDb->get_row("SELECT * FROM `alerts` WHERE `receivers` LIKE'%\"$userinfo->email\"%' AND `token`='$alerttoken';");
		if (!empty($alert)) {
			$alert->receivers=json_decode($alert->receivers);
			$alert->readers=json_decode($alert->readers);
			if (!in_array($userinfo->email, $alert->readers)) {
				array_push($alert->readers, $userinfo->email);
				$ezDb->query("UPDATE `alerts` SET `readers`='".json_encode($alert->readers)."' WHERE `token`='$alerttoken';");
			}
			if(!empty($alert->page)){
				header("Location: $alert->page");
			}
		}
	}

	//echo 'working';

    $config['date'] = '%I:%M %p';
    $config['time'] = '%H:%M:%S';
    $smarty->assign('config', $config);

	$smarty->assign("dateNow", $dateNow);

