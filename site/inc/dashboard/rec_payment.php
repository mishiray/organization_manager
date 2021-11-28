<?php 
	$trnsReference=date("Ymd").getToken('5').$ezDb->get_var("SELECT IFNULL(`id`,(`id`+1)) FROM `payments` ORDER BY `id` DESC LIMIT 1;");
 	$ezDb->query("INSERT INTO `payments` (`transid`, `transid1`, `token`, `purpose`, `amount`, `transdate`, `gateway`, `paidby`, `status1`, `status`, `discount`, `proof`) VALUES ('$trnsReference', '$trnsReference', '$trnsReference', '$posts->purpose', '$posts->amount', '$dateNow', 'paypal', '$userinfo->email', 'success', '1', '$sessions->discount', '');");
	$ezDb->query("UPDATE `userprofile` SET `active`=1 WHERE `email`='$userinfo->email';");