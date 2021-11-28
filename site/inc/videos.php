<?php 

$all_videos=tableRoutine("videos", '*' , " `id`!=0 AND `status` = 1", '`id`', 'DESC', '`id`', '8');
if(!empty($all_videos)){
	foreach($all_videos as $video){
		$link = $video->url;
		$link_array = explode('/',$link);
		$video->url = end($link_array);
	}
}
$smarty->assign("al_videos", $all_videos);
