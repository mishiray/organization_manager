<?php 

$whereClause="";
$events=tableRoutine("events", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');
if (!empty($events)) {
	foreach ($events as $value) {
		$value->title=testinputReverse($value->title);
		$value->content=testinputReverse($value->content);
		$value->content_edit=br2nl2($value->content);
		$value->content_stripe=stripeInputReverse($value->content_edit);
		$value->content_stripe=str_replace("&quot;", "\"", $value->content_stripe);
		$value->content_stripe=str_replace("&nbsp;", " ", $value->content_stripe);
	}
}

$smarty->assign("events", $events);


//$whereClause=" WHERE MONTH(`eventdate`) = MONTH(CURDATE()) AND YEAR(`eventdate`) = YEAR(CURDATE()) ";
$eve=$ezDb->get_results("SELECT `title`,`eventdate`,`image`,`addedby`,`dateadded` FROM `events` $whereClause ");
if (!empty($eve)) {
	//echo "ok";
	foreach ($eve as $value) {
		$value->title=testinputReverse($value->title);
		$value->time= date("h:i:sa", strtotime($value->eventdate));
		$value->eventdate = strtotime($value->eventdate);
	}
}
$check = (!empty($eve)) ? true : false;
$eve = json_encode($eve);
$smarty->assign("eve", $eve)->assign("check", $check);