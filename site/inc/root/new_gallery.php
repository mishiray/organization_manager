<?php 

redirect("home");
$userinfo=$Site['session']['User']['userinfo'];
if ( in_array($sitePage, array("new_gallery")) && ($requestMethod == 'POST') && isset($Site["post"]['triggers']) and $posts->triggers=='upload_gallery' ) {
	$targetDir="site/media/gallery/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "image/pjpeg", "image/pjp", "image/jfif", "image/webp", "image/gif");
	/*if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter image title please!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Enter image description please!</p>';
	endif;*/

	if (empty($files->img_upload['tmp_name']) or !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded sport club logo is not an image file. Only JPG, JPEG, JP2, GIF, WEBP, JFIF, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;
	if( $err==0 ):
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $posts->description=testInput(trim($posts->description));

	    $posts->title=testInput(str_replace("\n", "<br/>", (!empty($posts->title)? $posts->title: $files->img_upload['name'])));
		$spilledName=explode(".", $files->img_upload['name']);
		$imgtoken=getToken(6).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `gallery` ORDER BY `id` DESC LIMIT 1;");

	    $targetFile = $targetDir . $imgtoken .".". (end($spilledName));
    	$targetFile = ((!empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile))? $targetFile: '');
	    $ezDb->query("INSERT INTO `gallery` (`url`, `title`, `description`, `datadded`, `addedby`) VALUES ('$targetFile', '$posts->title', '$posts->description', '$dateNow', '$userinfo->email');");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Image successfully uploaded to gallery.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}