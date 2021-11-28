<?php
$clientemail=(!empty($gets->client)? $gets->client: "");
redirect("client?id=$clientemail");
$client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$clientemail' OR `email`='$clientemail';");
if (!empty($client) and is_object($client)) {
	$courseid=(!empty($gets->id)? $gets->id: "");
	$client->course=$ezDb->get_row("SELECT  `rcs`.`id`, `cs`.`courseid`, `cs`.`title`, `cs`.`description`, `cs`.`category`, `cs`.`unit`, `cs`.`fee`, `rcs`.`transid`, `rcs`.`datereg`, `rcs`.`regid`, `rcs`.`completion` FROM `courses` AS `cs`, `registered_courses` AS `rcs` WHERE `rcs`.`courseid`=`cs`.`courseid` AND `rcs`.`user`='$client->email' AND `rcs`.`courseid`='$courseid';");
	if (!empty($client->course)) {
		$client->course->description=testInputReverse(trim($course->description));
	    $client->course->title=testInputReverse(trim($course->title));
	    $client->course->category=testInputReverse(trim($course->category));
		
	}else{
		redirect("client?id=$clientemail");
	}
		
}else{
	redirect("clients");
}

$smarty->assign("client", $client);