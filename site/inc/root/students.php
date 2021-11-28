<?php
$whereClause="";
$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1','level2', 'level3')) ):
	redirect("home");
endif;

$clients=tableRoutine("userprofile", '*' , " `usertype`='client' $whereClause", '`id`', 'DESC', '`id`', "10");

if (!empty($clients)) {
	foreach ($clients as $key => $client):
		$client->courses=$ezDb->get_results("SELECT * FROM `courses` WHERE `courseid` IN (SELECT DISTINCT `courseid` FROM `registered_courses` WHERE `user`='$client->email');");
	endforeach;
}
$smarty->assign("clients", $clients);