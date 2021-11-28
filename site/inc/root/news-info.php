<?php 
$userinfo=$Site['session']['User']['userinfo'];

$id=(!empty($gets->id)? $gets->id: "");
$news=$ezDb->get_row("SELECT * FROM `news` WHERE `token`='$id';");
if (!empty($news)){
	if ( in_array($sitePage, array("news-info")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_news']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/news/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p>The uploaded news image is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter news title please!</p>';
		endif;
		if ( !(in_array( $userinfo->userrole, array('level0','level1')) and  $userinfo->active==true)):
			$err++;
			$fail.='<p>You are not authorized to update news!</p>';
		endif;
		if( empty(trim($posts->author))):
			$posts->author=$news->author;
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
		    $targetFile = $targetDir . $id."_news.png";
		    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
		    else:
		    	$targetFile=$news->image;
		    endif;
		    $posts->content=testInput($posts->content);
		    $posts->title=testInput($posts->title);
		    //id	updateid	title	content	image	addedby	dateadded
		    $ezDb->query("UPDATE `news` SET `title`='$posts->title', `content`='$posts->content', `image`='$targetFile', `author`='$posts->author', `newsdate`='$posts->newsdate' WHERE `token`='$id';");
			$news=$ezDb->get_row("SELECT * FROM `news` WHERE `token`='$id';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> news was successfully updated.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	if ( in_array($sitePage, array("news-info")) && ($requestMethod == 'POST') && isset($Site["post"]['delete_news']) ) {
		if ($err==0) {
		    if (!empty($news->image) and file_exists($news->image)):
		    	@unlink($news->image);
			endif;
			$ezDb->query("DELETE FROM `news` WHERE `token`='$id';");
			// Delete project file or images
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>news detail had been successfully deleted.</p></div>';
		}
	}
	$news->title=testinputReverse($news->title);
	$news->content=testinputReverse($news->content);
	$news->content_stripe=stripeInputReverse($news->content);
	$news->content_stripe=str_replace("&quot;", "\"", $news->content_stripe);
	$news->content_stripe=str_replace("&nbsp;", " ", $news->content_stripe);
}else{
	redirect("news");
}
$smarty->assign("news", $news);