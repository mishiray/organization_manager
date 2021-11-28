<?php
$email=(!empty($gets->token)? $gets->token: "");

if (!empty($email)) {
	$email = base64_decode($email);
	if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$email'"))) {
		$ezDb->query("UPDATE `newsletter` SET `status`='0', `dateupdated`='$dateNow' WHERE `email`='$email'");
		$fail = "You have successfully unsubscribed from our newsletter";
	}else{
		$fail = "Subscription email not found";
	}
}else{
	$fail = "404 Error";
}
//$smarty->assign("project", $project);