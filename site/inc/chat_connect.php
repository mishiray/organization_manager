<?php 

$err = 0;
$returnMessage = new stdClass();
$returnMessage->status = false;
$returnMessage->code = 0;
$returnMessage->message = 'No response';

if (!empty($posts->send) and !empty($posts->message) and !empty($posts->email) and !empty($posts->name) and $posts->sendMode == 'start') {
	if (!checkEmail($posts->email)) {
		$returnMessage->message = 'Invalid email';
		$returnMessage->code = -1;
		$err++;
	}
	if ($err === 0) {
		$posts->message=cleanUp($posts->message);
		$returnMessage->code = 1;
		$returnMessage->status = !$returnMessage->status;
		$sessions->careChatId = getToken("4").$ezDb->get_var("SELECT (IFNULL(`id`, 0)+1) FROM `customer_care_chat` ORDER BY `id` DESC LIMIT 1;");
		$ezDb->query("INSERT INTO `customer_care_chat` (`sender`, `sender_name`, `message`, `chatId`, `parent`, `date_sent`, `status`) VALUES ('$posts->email', '$posts->name', '$posts->message', '$sessions->careChatId', '', '$dateNow', '0')");
		alertDept2("Customer Service",1,"New customer live chat notification","chat_thread?chatid=$sessions->careChatId");
		$receivers = get_employees_in_dept("Customer Service");
		//print_r($receivers);
		$mail_email_to = $receivers;
		$mail_subject = 'New Customer Care Chat';
		$mail_title = "Atobe Chat Care!";
		$mail_body = "You have a new chat thread.";
		require_once 'mail_general.php';

		$message_resp = "Thank you for contacting Atobe CC Online, One moment Please..";

		$ezDb->query("INSERT INTO `customer_care_chat` (`sender`, `sender_name`, `message`, `chatId`, `parent`, `date_sent`, `status`) VALUES ('admin', 'Atobe Online', '$message_resp', '$sessions->careChatId', '', '$dateNow', '0')");
		
		$returnMessage->message = 'Chat successfully created';
		$returnMessage->chatThread = $ezDb->get_results("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id`DESC;");
		$returnMessage->chatParent = $returnMessage->chatThread[0];
		$Site["session"]["careChatId"] = $_SESSION["careChatId"] = $sessions->careChatId;
	}
}

if (!empty($posts->send) and !empty($posts->message) and $posts->sendMode == 'continue' and !empty($sessions->careChatId)) {
	$chatParent = $ezDb->get_row("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id` ASC LIMIT 1;");
	if (empty($chatParent)) {
		$returnMessage->message = 'Cannot find chat thread';
		$returnMessage->code = -2;
		$err++;
	}
	if ($err === 0) {
		$returnMessage->code = 2;
		$posts->message=cleanUp($posts->message);
		$returnMessage->status = !$returnMessage->status;
		$ezDb->query("INSERT INTO `customer_care_chat` (`sender`, `sender_name`, `message`, `chatId`, `parent`, `date_sent`, `status`) VALUES ('$chatParent->sender', '$posts->name', '$posts->message', '$sessions->careChatId', '$chatParent->id', '$dateNow', '0')");
		$returnMessage->message = 'Message successfully sent';
		$returnMessage->chatThread = $ezDb->get_results("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id`DESC;");
		$returnMessage->chatParent = $chatParent;

	}
}

if (!empty($posts->send) and $posts->sendMode == 'endchat' and !empty($sessions->careChatId)) {
	$chatParent = $ezDb->get_row("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id` ASC LIMIT 1;");
	if (empty($chatParent)) {
		$returnMessage->message = 'Cannot find chat thread';
		$returnMessage->code = -2;
		$err++;
	}
	if ($err === 0) {
		$returnMessage->code = 3;
		$returnMessage->status = !$returnMessage->status;
		// $ezDb->query("INSERT INTO `customer_care_chat` (`sender`, `sender_name`, `message`, `chatId`, `parent`, `date_sent`, `status`) VALUES ('$posts->email', '$posts->name', '$posts->message', '$sessions->careChatId', '', '$dateNow', '0')");
		$ezDb->query("UPDATE `customer_care_chat` SET `status` = 1 WHERE `chatId`='$sessions->careChatId' ORDER BY `id` ASC LIMIT 1");
		$returnMessage->message = 'Message successfully ended';
		// $returnMessage->chatParent = $chatParent;
		// $returnMessage->chatThread = $ezDb->get_results("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id`DESC;");
		unset($_SESSION["careChatId"]);
		$Site["session"]=$_SESSION;
		$sessions= (object)$Site["session"];
	}
}

if (!empty($posts->send) and $posts->sendMode == 'getchat') {
	if (empty($sessions->careChatId)) {
		$returnMessage->message = 'Chat session is not set';
		$returnMessage->code = -4;
		$err++;
		unset($_SESSION["careChatId"]);
		$Site["session"]=$_SESSION;
		$sessions= (object)$Site["session"]; 
	}else{
		$chatParent = $ezDb->get_row("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id` ASC LIMIT 1;");
	}

	if (empty($chatParent) && !empty($sessions->careChatId)) {
		$returnMessage->message = 'Empty chat thread';
		$returnMessage->code = -3;
		$err++;
	}
	if ($err === 0) {
		$returnMessage->code = 4;
		$returnMessage->status = !$returnMessage->status;
		$returnMessage->message = 'Message successfully fetched';
		$returnMessage->chatThread = $ezDb->get_results("SELECT * FROM `customer_care_chat` WHERE `chatId`='$sessions->careChatId' ORDER BY `id`DESC;");
		$returnMessage->chatParent = $chatParent;

	}
}

echo @json_encode($returnMessage);

exit();