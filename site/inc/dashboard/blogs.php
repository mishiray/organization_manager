<?php

if ($lastreg->isExpired===true or empty(((array)$lastreg))) {
	redirect("register");
}

$whereClause="AND `addedby`='$userinfo->email'";
$blogs=tableRoutine("blog", '*' , " `id`!=0 $whereClause", '`id`');

if (!empty($blogs)) {
	foreach ($blogs as $key => $value) {
		$value->titleln=testinputReverse($value->title);
		$value->title=testinputReverse($value->title);
		$value->contentln=testinputReverse($value->content);
		$value->content=testinputReverse($value->content);
		$value->content_stripe=stripeInputReverse($value->content);
		$value->content_stripe=str_replace("&quot;", "\"", $value->content_stripe);
		$value->content_stripe=str_replace("&nbsp;", " ", $value->content_stripe);
	}
}

$smarty->assign("blogs", $blogs);
