<?php 

if (!empty($posts->evt) and $posts->evt=='delete') {

	$galid = $posts->galid;
	$gallery=$ezDb->get_row("SELECT * FROM `gallery` WHERE `id`='$galid';");
	if (file_exists($gallery->url)) {
		@unlink($gallery->url);
	}
	$ezDb->query("DELETE FROM `gallery` WHERE `id`='$galid'");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Image successfully removed from gallery.</p></div>';
}

if (!empty($posts->evt) and $posts->evt=='activate') {
	$galid = $posts->galid;
	$ezDb->query("UPDATE `gallery` SET `status` = 1 WHERE `id`='$galid'");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Image successfully activated.</p></div>';
}

if (!empty($posts->evt) and $posts->evt=='deactivate') {
	$galid = $posts->galid;
	$ezDb->query("UPDATE `gallery` SET `status` = 0 WHERE `id`='$galid'");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Image successfully deactivated.</p></div>';
}

$whereClause="";
//$galleries=tableRoutine("gallery", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');
$galleries=$ezDb->get_results("SELECT * FROM `gallery` WHERE `id`!=0 $whereClause ORDER BY `id` DESC");
$smarty->assign("galleries", $galleries);
