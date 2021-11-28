<?php 
$userinfo=$Site['session']['User']['userinfo'];

if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) ):
	redirect("blogs");
endif;

$id=(!empty($gets->id)? $gets->id: "");
$blog=$ezDb->get_row("SELECT * FROM `blog` WHERE `token`='$id' AND `addedby`='$userinfo->email'");
if (!empty($blog)){
	if ( in_array($sitePage, array("blog")) && ($requestMethod == 'POST') && isset($Site["post"]['blog-edit']) ) {
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
			$fail.='<p>You are not authorized to update blog!</p>';
		endif;*/
		if( empty(trim($posts->author))):
			$posts->author=$blog->author;
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Enter blog detail please!</p>';
		endif;
		if( $err==0 ):
			if( !file_exists("$targetDir") ):
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $targetFile = $targetDir . $id."_blog.png";
		    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
		    else:
		    	$targetFile=$blog->image;
		    endif;
		    $posts->title=nl2br2($posts->title);
		    $posts->title=tb2sp2($posts->title);
		    $posts->content=nl2br2($posts->content);
		    $posts->content=tb2sp2($posts->content);
		    $posts->content=testInput($posts->content);
		    $posts->title=testInput($posts->title);
		    //id	updateid	title	content	image	addedby	dateadded
		    $ezDb->query("UPDATE `blog` SET `title`='$posts->title', `source`='$posts->source', `content`='$posts->content', `image`='$targetFile', `author`='$posts->author' WHERE `token`='$id' AND `addedby`='$userinfo->email';");
			$blog=$ezDb->get_row("SELECT * FROM `blog` WHERE `token`='$id' AND `addedby`='$userinfo->email';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> blog was successfully updated.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	if ( in_array($sitePage, array("blog")) && ($requestMethod == 'POST') && isset($Site["post"]['delete_blog']) ) {
		if ($err==0) {

		    if (!empty($blog->image) and file_exists($blog->image)):
		    	@unlink($blog->image);
			endif;
			$ezDb->query("DELETE FROM `blog` WHERE `token`='$id' AND `addedby`='$userinfo->email';");
			// Delete project file or images
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Blog detail had been successfully deleted.</p></div>';
		}
	}
	$blog->titleln=testinputReverse($blog->title);
	$blog->contentln=testinputReverse($blog->content);
	$blog->title=testinputReverse($blog->title);
	$blog->content=testinputReverse($blog->content);
	$blog->content_stripe=stripeInputReverse($blog->contentln);
	$blog->content_stripe=str_replace("&quot;", "\"", $blog->content_stripe);
}else{
	redirect("blogs");
}
$smarty->assign("blog", $blog);