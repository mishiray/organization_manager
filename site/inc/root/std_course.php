<?php

if( !in_array( $userinfo->userrole, array('level0','level1','level2', 'level3','level4')) and $userinfo->active==true ):
	redirect("home");
endif;

$clientemail=(!empty($gets->id)? $gets->id: "");
$client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$clientemail' OR `email`='$clientemail' AND `usertype`='client';");
if (!empty($client) and is_object($client)) {
	$client->courses=$ezDb->get_results("SELECT * FROM `courses` WHERE `courseid` IN (SELECT DISTINCT `courseid` FROM `registered_courses` WHERE `user`='$client->email');");
	foreach ($client->courses as $key => $course) {
		$course->description=testInputReverse(trim($course->description));
	    $course->title=testInputReverse(trim($course->title));
	    $course->category=testInputReverse(trim($course->category));
	}
}else{
	redirect("students");
}

$smarty->assign("student", $client);