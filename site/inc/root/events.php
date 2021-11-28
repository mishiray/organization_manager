<?php 

if ( in_array($sitePage, array("events")) && ($requestMethod == 'GET') && !empty($Site["get"]['event']) && !empty($gets->id)) {
	$currevent=$ezDb->get_row("SELECT * FROM `events` WHERE `token`='$gets->id';");
	if (!empty($currevent)){
		if (file_exists($currevent->image) && is_file($currevent->image)){
			@unlink($currevent->image);
		}
		$ezDb->query("DELETE FROM `events` WHERE `token`='$gets->id';");
		$fail="<p>Event <b>".testInputReverse($currevent->title)."</b> was successfully deteted</p>";
		$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

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
