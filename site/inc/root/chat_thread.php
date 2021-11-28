<?php 
$userinfo=$Site['session']['User']['userinfo'];

$gets->chatid = (!empty($gets->chatid)? $gets->chatid : '');

$chatParent = $ezDb->get_row("SELECT * FROM `customer_care_chat` WHERE `chatId`='$gets->chatid' ORDER BY `id` ASC LIMIT 1;");

if (empty($chatParent)) {
	redirect("chat_care");
}

$fail="";
$err=0;
if (in_array($sitePage, array("chat_thread")) && ($requestMethod == 'POST') && isset($Site["post"]['send_chat']) and $chatParent->status== 0) {
	if( empty(trim($posts->message)) ):
		$err++;
		$fail.='<p>Enter message please!</p>';
	endif;
	if ($err === 0) {
		$returnMessage->code = 2;
		$returnMessage->status = !$returnMessage->status;
		$ezDb->query("INSERT INTO `customer_care_chat` (`sender`, `sender_name`, `message`, `chatId`, `parent`, `date_sent`, `status`, `admin`) VALUES ('$userinfo->email', '$userinfo->firtname $userinfo->lastname', '$posts->message', '$gets->chatid', '$chatParent->id', '$dateNow', '0', '')");
		$fail = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Message successfully sent</p></div>';

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
$chatThread = $ezDb->get_results("SELECT * FROM `customer_care_chat` WHERE `chatId`='$gets->chatid' ORDER BY `id`DESC;");
$config['date'] = '%I:%M %p';
$smarty->assign('config', $config);
$smarty->assign("chatParent", $chatParent)->assign("chatThread", $chatThread);