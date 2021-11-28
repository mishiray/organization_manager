<?php 


$whereClause="";
$blogs=tableRoutine("blog", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');
if (!empty($blogs)) {
	foreach ($blogs as $value) {
		$value->title=testinputReverse($value->title);
		$value->content=testinputReverse($value->content);
		$value->content_stripe=stripeInputReverse($value->content);
		$value->content_stripe=str_replace("&quot;", "\"", $value->content_stripe);
		$value->content_stripe=str_replace("&nbsp;", " ", $value->content_stripe);
		$value->creatornames=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`,' ',`lastname`) FROM `userprofile` WHERE `email`='$value->addedby';");
		$value->creatorimg=$ezDb->get_var("SELECT CONCAT(`userimg`) FROM `userprofile` WHERE `email`='$value->addedby';");
		$value->replies=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `comments` WHERE `postid`='$value->token';");
	}
}

$smarty->assign("blogs", $blogs);
