<?php 
redirect("home");
$userinfo=$Site['session']['User']['userinfo'];

$blogid=(!empty($gets->id)? $gets->id: "");
$currblog=$ezDb->get_row("SELECT * FROM `updates` WHERE `updateid`='$blogid' AND `ctype`='blog';");
if (!empty($currblog)){
	if ( in_array($sitePage, array("edit_blog")) && ($requestMethod == 'POST') && isset($Site["post"]['add_update']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/blogs/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter blog title please!</p>';
		endif;
		if ( !(in_array( $userinfo->userrole, array('level0','level1')) and  $userinfo->active==true)):
			$err++;
			$fail.='<p>You are not authorized to post blog!</p>';
		endif;
		if( empty(trim($posts->author))):
			$posts->author=$currblog->author;
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Enter blog detail please!</p>';
		endif;
		if( $err==0 ):
			if( !file_exists("$targetDir") ):
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $targetFile = $targetDir . $blogid."_update.png";
		    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
		    else:
		    	$targetFile=$currblog->image;
		    endif;
		    $posts->content=testInput($posts->content);
		    $posts->title=testInput($posts->title);
		    //id	updateid	title	content	image	addedby	dateadded
		    $ezDb->query("UPDATE `updates` SET `title`='$posts->title', `content`='$posts->content', `image`='$targetFile', `author`='$posts->author' WHERE `updateid`='$blogid' AND `ctype`='blog';");
			$currblog=$ezDb->get_row("SELECT * FROM `updates` WHERE `updateid`='$blogid' AND `ctype`='blog';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> blog had been successfully updated.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	$currblog->title=testinputReverse($currblog->title);
	$currblog->content=testinputReverse($currblog->content);
	$currblog->content_stripe=stripeInputReverse($currblog->content);
}else{
	redirect("blogs");
}
$smarty->assign("blog", $currblog);