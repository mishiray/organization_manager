<?php 
$whereClause ="`parent`=''";


$gets->chatId = (!empty($gets->chatId)? $gets->chatId : '');
if (!empty($gets->chatId) && !empty($gets->endChat) && $gets->endChat=='true') {
	
	$chatParent = $ezDb->get_row("SELECT * FROM `customer_care_chat` WHERE `chatId`='$gets->chatId' AND `status`='0' ORDER BY `id` ASC LIMIT 1;");
	if (!empty($chatParent)) {
		$ezDb->query("UPDATE `customer_care_chat` SET `status` = 1 WHERE `chatId`='$gets->chatId' ORDER BY `id` ASC LIMIT 1");
		$fail = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>CHat thread successfully ended</p></div>';
	}
}

$chat_inbox=tableRoutine("customer_care_chat", '*', " $whereClause", '`id`', "DESC", "50");
$smarty->assign("chat_inbox", $chat_inbox);