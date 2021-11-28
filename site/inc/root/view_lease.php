<?php

$subid=(!empty($gets->id)? $gets->id: "");

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$whereClause = "";

$client=$ezDb->get_row("SELECT * from `lease` WHERE `token`= '$subid'");
$memos=$ezDb->get_results("SELECT * FROM `memo_crm` WHERE (`receivers` LIKE'%\"$client->email\"%') ORDER BY `dateadded` DESC;");

$smarty->assign("subs", $client)->assign("memos",$memos);


