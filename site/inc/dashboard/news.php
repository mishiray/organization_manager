<?php 

if ($lastreg->isExpired===true or empty(((array)$lastreg))) {
	redirect("register");
}

if ( in_array($sitePage, array("news")) && ($requestMethod == 'GET') && !empty($Site["get"]['event']) && !empty($gets->id)) {
	$new=$ezDb->get_row("SELECT * FROM `news` WHERE `token`='$gets->id' AND `addedby`='$userinfo->email';");
	if (!empty($new)){
		if (file_exists($new->image) && is_file($new->image)){
			@unlink($new->image);
		}
		$ezDb->query("DELETE FROM `news` WHERE `token`='$gets->id' AND `addedby`='$userinfo->email';");
		$fail="<p>News <b>".testInputReverse($new->title)."</b> was successfully deteted</p>";
		$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

/*$contents=str_replace("'", "&apos;", $posts->content);
$contents=str_replace("\"", "&quot;", $contents);*/
$whereClause="AND `addedby`='$userinfo->email'";
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
		// $value->content=str_replace("\/", "/", $value->content);
	}
}

$smarty->assign("news", $news);
