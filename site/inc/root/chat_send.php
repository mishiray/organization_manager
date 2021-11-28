<?php
$userinfo=$Site["session"]["User"]["userinfo"];

$returnMessage = new stdClass();
$returnMessage->status = 0;
if( empty(trim($posts->content)) ):
	$err++;
	$returnMessage->status = -1;
endif;
if ($err==0){

		$posts->content=testInput($posts->content);
		$message_id= date("YmdHis").$ezDb->get_var("SELECT IF(`id`= NULL,'1',(`id`+1)) FROM `messages` ORDER BY `id` DESC LIMIT 1;");
		$receiver=array();
		array_push($receiver, "$posts->receiver");
		if($ezDb->query("INSERT INTO `messages` (`messageid`, `content`, `sender`, `receivers`, `readers`, `datesent`, `attachement`) VALUES ('$message_id', '$posts->content', '$userinfo->email', '".json_encode($receiver)."', '".json_encode($emptyArray)."', '$dateNow', '')")){
		$returnMessage->status = 1;
	}
}

echo @json_encode($returnMessage);
exit();
