<?php

$id=(!empty($gets->id)? $gets->id: "");
$news=$ezDb->get_row("SELECT * FROM `news` WHERE `token`='$id';");
if (!empty($news)){

	$news->title=testinputReverse($news->title);
	$news->content=testinputReverse($news->content);
	$news->content_stripe=stripeInputReverse($news->content);
	$news->content_stripe=str_replace("&quot;", "\"", $news->content_stripe);
	$news->content_stripe=str_replace("&nbsp;", " ", $news->content_stripe);

	$fail="";
	$err=0;
	if ( in_array($sitePage, array("news-detail")) && ($requestMethod == 'POST') && isset($Site["post"]['commentBtn']) ) {
		$posts->comment=trim($posts->comment);
		$posts->email=trim($posts->email);
		$posts->names=trim($posts->names);
		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Kindly type something into the comment!</p>';
		endif;
		if( !empty($ezDb->get_var("SELECT `email` FROM `news_comments` WHERE `comment`='$posts->comment' AND `email`='$posts->email'")) ):
			$err++;
			$fail.='<p>This comment alredy exists!</p>';
		endif;
		if( $err==0 ):
			$posts->comment=testInput($posts->comment);
			
		 	$ezDb->query("INSERT INTO `news_comments` (`postid`, `comment`, `name`, `email`, `url`, `dateadded`) VALUES ('$id', '$posts->comment', '$posts->names', '$posts->email', '$posts->url', '$dateNow')");
		else:
			$fail='<div class="alert text-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}

	$comments=$ezDb->get_results("SELECT * FROM `news_comments` WHERE `postid`='$id' ORDER BY `id` DESC");
	if (!empty($comments)) {
		foreach ($comments as $key => $value) {
			$value->comment=testInputReverse($value->comment);
		}
	}
	$news->creatornames=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`,' ',`lastname`) FROM `userprofile` WHERE `email`='$news->addedby';");
	$news->creatorimg=$ezDb->get_var("SELECT CONCAT(`userimg`) FROM `userprofile` WHERE `email`='$news->addedby';");
	$news->replies=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `news_comments` WHERE `postid`='$news->token';");
}else{
	redirect("news");
}
$smarty->assign("news", $news)->assign("comments", $comments);
