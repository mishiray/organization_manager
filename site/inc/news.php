<?php 

$whereClause="";
$news=tableRoutine("news", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');
if (!empty($news)) {
	foreach ($news as $value) {
		$value->titleln=testinputReverse($value->title);
		$value->title=testinputReverse($value->title);
		$value->contentln=testinputReverse($value->content);
		$value->content=testinputReverse($value->content);
		$value->content_stripe=stripeInputReverse($value->content);
		$value->content_stripe=str_replace("&quot;", "\"", $value->content_stripe);
		$value->content_stripe=str_replace("&nbsp;", " ", $value->content_stripe);
		$value->content_stripe = strlen($value->content_stripe) > 50 ? substr($value->content_stripe,0,50)."..." : $value->content_stripe;
		$value->creatornames=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`,' ',`lastname`) FROM `userprofile` WHERE `email`='$value->addedby';");
		$value->creatorimg=$ezDb->get_var("SELECT CONCAT(`userimg`) FROM `userprofile` WHERE `email`='$value->addedby';");
		$value->replies=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `news_comments` WHERE `postid`='$value->token';");
		// $value->content=str_replace("\/", "/", $value->content);
	}
}

$smarty->assign("news", $news);