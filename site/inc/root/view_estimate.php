<?php
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;
$token=(!empty($gets->id)? $gets->id: "");

$estimate=$ezDb->get_row("SELECT * from `estimates` WHERE `token` = '$token';");

if(!empty($estimate)){
	
}else{
	redirect("estimates");
}

$smarty->assign("estimate", $estimate);
