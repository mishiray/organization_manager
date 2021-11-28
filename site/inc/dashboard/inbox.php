<?php

$userinfo=$Site["session"]["User"]["userinfo"];

$messageid=(!empty($gets->id)? $gets->id: "");
$message=$ezDb->get_row("SELECT * FROM `messages_crm` WHERE `receivers` LIKE'%\"$userinfo->email\"%' AND `messageid`='$messageid';");

if (!empty($message)) {
	/*Reply of Message*/
	if ( in_array($sitePage, array("inbox")) && ($requestMethod == 'POST') && isset($Site["post"]['reply_message']) ) {
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Enter post content please!</p>';
		endif;
		if ($err==0) {
			$message_id=  getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `messages` ORDER BY `id` DESC LIMIT 1;");
		    $posts->content=testInput($posts->content);
		    $receiver=array();
		    array_push($receiver, $message->sender);

		    $ezDb->query("INSERT INTO `messages_crm` (`messageid`, `title`, `content`, `sender`, `receivers`, `readers`, `datesent`, `reply`, `attachement`) VALUES ('$message_id', '$message->title', '$posts->content', '$userinfo->email', '".json_encode($receiver)."', '".json_encode($emptyArray)."', '$dateNow', '$messageid', '')");
		    $posts->title='Reply';
		    require_once "drop_message.php";
			$message=$ezDb->get_row("SELECT * FROM `messages_crm` WHERE `receivers` LIKE'%\"$userinfo->email\"%' AND `messageid`='$messageid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Message was successfully replied.</p></div>';
		}
	}
	$message->title=testInputReverse($message->title);
	$message->content=testInputReverse($message->content);
	$message->receivers=json_decode($message->receivers);
	$message->readers=json_decode($message->readers);
	if (!in_array($userinfo->email, $message->readers)) {
		array_push($message->readers, $userinfo->email);
		$ezDb->query("UPDATE `messages_crm` SET `readers`='".json_encode($message->readers)."' WHERE `messageid`='$messageid';");
	}
	$message->receivernames=array();
	$message->main=$ezDb->get_row("SELECT * FROM `messages_crm` WHERE `messageid`='$message->reply';");
	if (!empty($message->reply)){
		$message->main->title=testInputReverse($message->main->title);
		$message->main->content=testInputReverse($message->main->content);
	}
	foreach ($message->receivers as $key => $val) {
		$message->receivernames[$key]=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$val'");
	}
	$message->readernames=array();
	foreach ($message->readers as $key => $val) {
		$message->readernames[$key]=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$val'");
	}
	$message->sendername=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$message->sender'");
	$smarty->assign("message", $message);
}else{
	$whereClause="AND  `receivers` LIKE'%\"$userinfo->email\"%'";
	$messages=tableRoutine("messages_crm", '*', " `id`!='0' $whereClause", '`id`');
	if(!empty($messages) and is_array($messages)){
		foreach ($messages as $value) {
			$value->title=testInputReverse($value->title);
			$value->content=testInputReverse($value->content);
			$value->receivers=json_decode($value->receivers);
			$value->readers=json_decode($value->readers);
		}
	}
	$smarty->assign("messages", $messages);
}
