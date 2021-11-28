<?php global $sitePage;
$posteds=new stdClass();

require_once "contact_mailer.php";
require_once "newsletter.php";

	

/*$gallery_feat=$ezDb->get_results("SELECT * FROM `gallery` ORDER BY `id` DESC LIMIT 4");




$sPackages=$ezDb->get_results("SELECT * FROM `packages` ORDER BY `id` ASC;");



if (!empty($sPackages)) {

	foreach ($sPackages as $key => $value) {

		$value->description=testInputReverse(trim($value->description));

	    $value->title=testInputReverse(trim($value->title));

		$value->course_list=json_decode($value->course_list);

		$courseFlt=getCourseIN($value->course_list);



		$value->courses=( !empty($courseFlt) ?$ezDb->get_results("SELECT * FROM `courses` $courseFlt;") : array() );

	}

}*/



/*$smarty->assign("sPackages", $sPackages)->assign("gallery_feat", $gallery_feat);*/



$smarty->assign("posts", $posts)->assign("fail", $fail);

// error_log(base64_decode("aG9mZnRlY2gyMDIw"));



// Business Listing
// $businesses=$ezDb->get_results("SELECT * FROM `business` ORDER BY `id` DESC LIMIT 2");


$cus_reviews= $ezDb->get_results("SELECT * from `client_reviews` ORDER BY `id` DESC");
$estates= $ezDb->get_results("SELECT * from projects LIMIT 6");
$def_pro= $ezDb->get_results("SELECT * from projects");
$promos= $ezDb->get_results("SELECT * from promos WHERE `status` = 1");

$newsitem=$ezDb->get_results("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 2");
if (!empty($newsitem)) {
	foreach ($newsitem as $value) {
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
$whereClause = '';
$def_blogs=$ezDb->get_results("SELECT * FROM `blog` WHERE `id` != 0 ORDER BY `id` DESC LIMIT 4");
if (!empty($def_blogs)) {
	foreach ($def_blogs as $value) {
		$value->title=testinputReverse($value->title);
		$value->creatornames=$ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`,' ',`lastname`) FROM `userprofile` WHERE `email`='$value->addedby';");
		$value->creatorimg=$ezDb->get_var("SELECT CONCAT(`userimg`) FROM `userprofile` WHERE `email`='$value->addedby';");
		$value->replies=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `comments` WHERE `postid`='$value->token';");
	}
}
$all_videos=$ezDb->get_results("SELECT * FROM `videos` WHERE `id` != 0  AND `status` = 1 ORDER BY `id` DESC LIMIT 4");
if(!empty($all_videos)){
	foreach($all_videos as $video){
		$link = $video->url;
		$link_array = explode('/',$link);
		$video->url = end($link_array);
	}
}


$jobs=$ezDb->get_results("SELECT * FROM `jobs` ORDER BY `id` DESC LIMIT 2");

$payTypes= array('h' => 'Hourly', 'd' => 'Daily','w' => 'Weekly', 'm' => 'Monthly', 'q' => 'Quarterly', 'a' => 'Annually');
$smarty->assign("jobs", $jobs)->assign("payTypes", $payTypes)->assign("def_pro", $def_pro);
$smarty->assign("newsitem", $newsitem)->assign("def_blogs", $def_blogs)->assign("cus_reviews", $cus_reviews)->assign("promos", $promos)->assign("def_videos", $all_videos);



