<?php 

$partnermail=(!empty($gets->id)? $gets->id: "");

$partner=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$partnermail' AND `usertype` IN('client') AND `userrole` IN('level1');");

if( !in_array( $userinfo->userrole, array('level0','level1','level2','level6','level5', 'level3')) or empty($partner)):
	redirect("partners");
endif;
$referee=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `referrer` IS NOT NULL AND `userid` IS NOT NULL AND `userid`='$partner->refid';");
//$whereClause="AND `referrer`='$partner->refid'";
//$referred=tableRoutine("userprofile", '*' , " `usertype`='client' $whereClause", '`id`', 'DESC', '`id`', "10");
$referred = $ezDb->get_results("SELECT * from userprofile WHERE `usertype`='client' AND `refid`='$partner->userid'");
$smarty->assign("partner", $partner)->assign("referred", $referred)->assign("referee", $referee);