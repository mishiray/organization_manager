<?php

	$userinfo=$Site["session"]["User"]["userinfo"];	

	$returnMessage = new stdClass();
	$returnMessage->code = 0;

	if($posts->sendMode == 'getFresh'){

		$returnMessage->code = 1;

		//memo announcements
		$whereClause="AND `receivers` LIKE'%\"$userinfo->email\"%' AND `readers` NOT LIKE'%\"$userinfo->email\"%'";
		$memos_dash=$ezDb->get_results("SELECT * FROM `memo` WHERE `id`!='0' $whereClause ORDER BY `id` DESC LIMIT 5 ");
		if(!empty($memos_dash) and is_array($memos_dash)){
			foreach ($memos_dash as $value) {
				$value->content=testInputReverse($value->content);
				$value->sendername=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$value->sender'");
			}	
		}


		//view alerts
		$whereClause="AND `receivers` LIKE'%\"$userinfo->email\"%' AND `readers` NOT LIKE'%\"$userinfo->email\"%'";
		$alertBar=$ezDb->get_results("SELECT * FROM `alerts` WHERE `id`!='0' $whereClause ORDER BY `id` DESC LIMIT 5 ");
		$counter = 0;
		if(!empty($alertBar) and is_array($alertBar)){
			$count = 0;
			foreach ($alertBar as $value) {
				$value->content=testInputReverse($value->content);
				$value->receivers=json_decode($value->receivers);
				$value->readers=json_decode($value->readers);
				$counter++;
			}
			$counter;
		}

		//messages
		$whereClause="AND `receivers` LIKE'%\"$userinfo->email\"%' AND `readers` NOT LIKE'%\"$userinfo->email\"%'";
		$messagesbar=tableRoutine("messages", '*', " `id`!='0' $whereClause", '`id`');
		$count = 0;
		if(!empty($messagesbar) and is_array($messagesbar)){
			$count = 0;
			foreach ($messagesbar as $value) {
				$value->title=testInputReverse($value->title);
				$value->content=testInputReverse($value->content);
				$value->receivers=json_decode($value->receivers);
				$value->readers=json_decode($value->readers);
				$value->sendername=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$value->sender'");
				$value->senderimg = $ezDb->get_var("SELECT `userimg` FROM `userprofile` WHERE `email`='$value->sender'");
				$date = new DateTime($value->datesent);		
				$value->datesent = date_format($date,'D, j M Y H:i:s');
				$count++;
			}
			$count;
		}

		$returnMessage->memo = $memos_dash;
		$returnMessage->messages = $messagesbar;
		$returnMessage->count = $count;
		$returnMessage->counter = $counter;
		$returnMessage->alertBar = $alertBar;
	}
	echo @json_encode($returnMessage);
	$smarty->assign("messagesbar", $messagesbar)->assign("count", $count)->assign("alertBar", $alertBar)->assign("counter", $counter)->assign("dateNow", $dateNow);
	exit();
