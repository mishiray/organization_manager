<?php 
$whereClause="AND `userrole`='level3'";
//redirect("home");
if( !in_array( $userinfo->userrole, array('level0','level1','level2','level6','level5', 'level3')) ):
	redirect("home");
endif;
$referrals=$ezDb->get_results("SELECT * from userprofile WHERE `usertype`='client' AND `userrole`='level1'");
if(!empty($referrals) and is_array($referrals)):
	foreach ($referrals as $referral):
		$referral->by=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `userid`='$referral->refid'");
	endforeach;
endif;

$smarty->assign("referrals", $referrals);
