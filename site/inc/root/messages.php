<?php
$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;
 
$messageid=(!empty($gets->id)? $gets->id: "");
$message=$ezDb->get_row("SELECT * FROM `messages` WHERE `sender`='$userinfo->email' AND `messageid`='$messageid';");

if (!empty($message)) {
	/*Edit of Message*/
	if ( in_array($sitePage, array("messages")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_message']) ) {
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter post title please!</p>';
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Enter post content please!</p>';
		endif;
		if ($err==0) {
		    $posts->content=testInput($posts->content);
		    $posts->title=testInput($posts->title);
			$ezDb->query("UPDATE `messages` SET `title`='$posts->title', `content`='$posts->content' WHERE `sender`='$userinfo->email' AND `messageid`='$messageid';");
			$message=$ezDb->get_row("SELECT * FROM `messages` WHERE `sender`='$userinfo->email' AND `messageid`='$messageid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Message was successfully updated.</p></div>';
		}
	}
	$message->title=testInputReverse($message->title);
	$message->content=testInputReverse($message->content);
	$message->receivers=json_decode($message->receivers);
	$message->readers=json_decode($message->readers);
	$message->receivernames=array();
	foreach ($message->receivers as $key => $val) {
		$message->receivernames[$key]=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$val'");
	}
	$message->readernames=array();
	foreach ($message->readers as $key => $val) {
		$message->readernames[$key]=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$val'");
	}
	/*Deleting of Message*/
	if ( in_array($sitePage, array("messages")) && ($requestMethod == 'POST') && isset($Site["post"]['delete_message']) ) {
		if ($err==0) {
			$ezDb->query("DELETE FROM `messages` WHERE `sender`='$userinfo->email' AND `messageid`='$messageid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Message was successfully deleted.</p></div>';
		}
	}
	$smarty->assign("message", $message);
}else{
	$whereClause="AND `sender`='$userinfo->email'";
	$messages=tableRoutine("messages", '*', " `id`!='0' $whereClause", '`id`');
	if(!empty($messages) and is_array($messages)){
		foreach ($messages as $value) {
			$value->title=testInputReverse($value->title);
			$value->content=testInputReverse($value->content);
			$value->receivers=@json_decode($value->receivers);
			$value->readers=@json_decode($value->readers);
		}
	}
	$smarty->assign("messages", $messages);
}
