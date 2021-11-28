<?php 

if (!empty($posts->evt) and $posts->evt=='delete') {

	$vidid = $posts->vidid;
	$videos=$ezDb->get_row("SELECT * FROM `videos` WHERE `id`='$vidid';");
	if (file_exists($videos->url)) {
		@unlink($videos->url);
	}
	$ezDb->query("DELETE FROM `videos` WHERE `id`='$vidid'");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Video successfully removed from videos.</p></div>';
}

if (!empty($posts->evt) and $posts->evt=='activate') {
	$vidid = $posts->vidid;
	$ezDb->query("UPDATE `videos` SET `status` = 1 WHERE `id`='$vidid'");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Video successfully activated.</p></div>';
}

if (!empty($posts->evt) and $posts->evt=='deactivate') {
	$vidid = $posts->vidid;
	$ezDb->query("UPDATE `videos` SET `status` = 0 WHERE `id`='$vidid'");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Video successfully deactivated.</p></div>';
}

$whereClause="";
//$galleries=tableRoutine("videos", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');
$all_videos=$ezDb->get_results("SELECT * FROM `videos` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
if(!empty($all_videos)){
	foreach($all_videos as $video){
		$link = $video->url;;
		$link_array = explode('/',$link);
		$video->url = end($link_array);
	}
}
$smarty->assign("videos", $all_videos);
