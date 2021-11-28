<?php 
$userinfo=$Site['session']['User']['userinfo'];
$fail="";
$err=0;
if ( in_array($sitePage, array("event-new")) && ($requestMethod == 'POST') ) {
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/events/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
	if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter event title please!</p>';
	endif;
	/*if ( !(in_array( $userinfo->userrole, array('level0','level1')) and  $userinfo->active==true)):
		$err++;
		$fail.='<p>You are not authorized to post event!</p>';
	endif;*/
	if( empty(trim($posts->eventdate))):
		$err++;
		$fail.='<p>Enter a valid event date please!</p>';
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p>Enter event detail please!</p>';
	endif;
	if( $err==0 ):
		if( !file_exists("$targetDir") ):
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $token= date("YmdHis").$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `events` ORDER BY `id` DESC LIMIT 1;");
	    $targetFile = $targetDir . $token."_event.png";
	    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
	    else:
	    	$targetFile="";
	    endif;
	    $posts->content=nl2br2($posts->content);
	    $posts->content=testInput($posts->content);
	    $posts->title=testInput($posts->title);
	    //id	updateid	title	content	image	addedby	dateadded
	    $ezDb->query("INSERT INTO `events` (`token`, `title`, `content`, `dateadded`, `addedby`, `eventdate`, `image`) VALUES ('$token', '$posts->title', '$posts->content', '$dateNow', '$userinfo->email', '$posts->eventdate', '$targetFile')");

		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> event had been successfully posted.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
