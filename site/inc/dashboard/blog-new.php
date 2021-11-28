<?php

// if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
// 	redirect("blogs");
// endif;
if ($lastreg->isExpired===true or empty(((array)$lastreg))) {
	redirect("register");
}

$userinfo=$Site['session']['User']['userinfo'];

$fail="";
$err=0;
if ( in_array($sitePage, array("blog-new")) && ($requestMethod == 'POST') && isset($Site["post"]['new-bog']) ) {
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/blogs/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
	if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded blog image is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter blog title please!</p>';
	endif;
	/*if ( !(in_array( $userinfo->userrole, array('level0','level1', 'level2')) and  $userinfo->active==true)):
		$err++;
		$fail.='<p>You are not authorized to add blog!</p>';
	endif;*/
	if( empty(trim($posts->author))):
		$posts->author="$userinfo->firstname $userinfo->lastname";
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p>Enter blog detail please!</p>';
	endif;
	if( $err==0 ):
		if( !file_exists("$targetDir") ):
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $token= getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `blog` ORDER BY `id` DESC LIMIT 1;");
	    $targetFile = $targetDir . $token."_blog.png";
	    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
	    else:
	    	$targetFile="";
	    endif;
	    $posts->title=nl2br2($posts->title);
	    $posts->title=tb2sp2($posts->title);
	    $posts->content=nl2br2($posts->content);
	    $posts->content=tb2sp2($posts->content);
	    $posts->content=testInput($posts->content);
	    $posts->title=testInput($posts->title);
	    $posts->slug=str_replace(" ", "-", strtolower($posts->title));
	    //id	token	title	content	image	addedby	dateadded
	    $ezDb->query("INSERT INTO `blog` (`token`, `title`, `slug`, `content`, `dateadded`, `addedby`, `source`, `image`, `author`) VALUES ('$token', '$posts->title', '$posts->slug', '$posts->content', '$dateNow', '$userinfo->email', '$posts->source', '$targetFile', '$posts->author')");

		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> blog was successfully posted.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}