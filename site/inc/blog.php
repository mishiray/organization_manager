<?php 

$blogid=(!empty($gets->id)? $gets->id: "");
$blog=$ezDb->get_row("SELECT * FROM `blog` WHERE `token`='$blogid';");
if (!empty($blog)){
	$blog->title=testinputReverse($blog->title);
	$blog->content=testinputReverse($blog->content);
	$blog->content_stripe=stripeInputReverse($blog->content);
	$blog->content_stripe=str_replace("&quot;", "\"", $blog->content_stripe);
	$blog->content_stripe=str_replace("&nbsp;", " ", $blog->content_stripe);

	$fail="";
	$err=0;
	if ( in_array($sitePage, array("blog")) && ($requestMethod == 'POST') && isset($Site["post"]['commentBtn']) ) {
		$posts->comment=trim($posts->comment);
		$posts->email=trim($posts->email);
		$posts->names=trim($posts->names);
		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Kindly type something into the comment!</p>';
		endif;
		if( !empty($ezDb->get_var("SELECT `email` FROM `comments` WHERE `comment`='$posts->comment' AND `email`='$posts->email'")) ):
			$err++;
			$fail.='<p>This comment alredy exists!</p>';
		endif;
		if( $err==0 ):
			$posts->comment=testInput($posts->comment);
		 	$ezDb->query("INSERT INTO `comments` (`postid`, `comment`, `name`, `email`, `dateadded`) VALUES ('$blogid', '$posts->comment', '$posts->names', '$posts->email', '$dateNow')");
		else:
			$fail='<div class="alert text-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}

	$comments=$ezDb->get_results("SELECT * FROM `comments` WHERE `postid`='$blogid' ORDER BY `id` DESC");
	if (!empty($comments)) {
		foreach ($comments as $key => $value) {
			$value->comment=testInputReverse($value->comment);
		}
	}
	$blog->creatornames=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`,' ',`lastname`) FROM `userprofile` WHERE `email`='$blog->addedby';");
	$blog->creatorimg=$ezDb->get_var("SELECT CONCAT(`userimg`) FROM `userprofile` WHERE `email`='$blog->addedby';");
	$blog->replies=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `comments` WHERE `postid`='$blog->token';");
}else{
	redirect("blogs");
}
$smarty->assign("blog", $blog)->assign("comments", $comments);