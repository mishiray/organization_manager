<?php 

$userinfo=$Site['session']['User']['userinfo'];

$fail="";
$err=0;
if ( in_array($sitePage, array("news-new")) && ($requestMethod == 'POST') && isset($Site["post"]['add_news']) ) {
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/news/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/jfiff");
	if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded news image is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter news title please!</p>';
	endif;
	if ( !(in_array( $userinfo->userrole, array('level0','level1')) && !in_array($userinfo->department, array('Corporate Office')))):
		$err++;
		$fail.='<p>You are not authorized to add news!</p>';
	endif;
	if( empty(trim($posts->author))):
		$posts->author="$userinfo->firstname $userinfo->lastname";
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p>Enter news detail please!</p>';
	endif;
	if( empty(trim($posts->newsdate)) ):
		$err++;
		$fail.='<p>Enter news date please!</p>';
	endif;
	if( $err==0 ):
		if( !file_exists("$targetDir") ):
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $token= getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `news` ORDER BY `id` DESC LIMIT 1;");
	    $targetFile = $targetDir . $token."_news.png";
	    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
	    else:
	    	$targetFile="";
	    endif;
	    $posts->content=testInput($posts->content);
	    $posts->title=testInput($posts->title);
	    //id	token	title	content	image	addedby	dateadded
	    $ezDb->query("INSERT INTO `news` (`token`, `title`, `content`, `dateadded`, `addedby`, `image`, `author`, `newsdate`) VALUES ('$token', '$posts->title', '$posts->content', '$dateNow', '$userinfo->email', '$targetFile', '$posts->author', '$posts->newsdate')");

		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> news was successfully posted.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}