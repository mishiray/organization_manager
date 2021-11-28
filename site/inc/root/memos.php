<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0', 'level2','level1','level3','level4')) ):
	redirect("home");
endif;

$memoToken2=(!empty($gets->id) ? $gets->id : " ");
$query = "SELECT * FROM `memo` WHERE (`receivers` LIKE'%\"$userinfo->email\"%'  AND `token`='$memoToken2') OR (`sender` = '$userinfo->email'  AND `token`='$memoToken2');" ;

$memo = $ezDb->get_row($query);

if (!empty($memo) && in_array($sitePage, array('memos'))) {
	$memo->content=testInputReverse($memo->content);
	$memo->receivers=json_decode($memo->receivers);
	$memo->readers=json_decode($memo->readers);
	$memo->receivernames=array();
	$memo->sendername=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$memo->sender'");
	foreach ($memo->receivers as $key => $val) {
		$memo->receivernames[$key]=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$val'");
	}
	$memo->readernames=array();
	foreach ($memo->readers as $key => $val) {
		$memo->readernames[$key]=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$val'");
	}

	if (!in_array($userinfo->email, $memo->readers)) {
		array_push($memo->readers, $userinfo->email);
		$ezDb->query("UPDATE `memo` SET `readers`='".json_encode($memo->readers)."' WHERE `token`='$memoToken2';");
	}
	$smarty->assign("memo_message", $memo);
}else{
	$memos=$ezDb->get_results("SELECT * FROM `memo` WHERE (`id`!='0' AND `receivers` LIKE'%\"$userinfo->email\"%') OR (`id`!='0' AND `sender` ='$userinfo->email') ORDER BY `id` DESC ");
	if(!empty($memos) and is_array($memos)){
		foreach ($memos as $value) {
			$value->content=testInputReverse($value->content);
			$value->receivers=json_decode($value->receivers);
			$value->readers=json_decode($value->readers);
			$value->sendername=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$value->sender'");
		}
		
		$smarty->assign("memos", $memos);
	}
}


