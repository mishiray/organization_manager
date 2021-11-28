<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_gallery")) && ($requestMethod == 'POST') && isset($Site["post"]['gallery']) ) {

	$fail="";
	$err=0;
	$tags="";
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];

	$targetDir="site/media/gallery/";
	//print_r($posts);
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
    endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter title please!</p>';
	endif;
	if(empty($posts->tags)):
		$err++;
		$fail.='<p>Select one or more tags please!</p>';
	else:
		foreach($posts->tags  as $selected){
			$tags.= $selected." ";
		} 
	endif;
	if(!empty($posts->description)):
		$posts->description = cleanUp($posts->description);
	endif;
	if ($err==0) {
	    $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `gallery` ORDER BY `id` DESC LIMIT 1;");
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $extn=end(explode(".", $files->file_upload['name']));
	    $targetFile = $targetDir.$token."_image.".$extn;
		if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif;

		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `gallery` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `gallery` (`token`,`tags`,`title`, `description`,`addedby`,`image`) VALUES ('$token','$tags','$posts->title', '$posts->description','$userinfo->email','$targetFile')";
		if($ezDb->query($query)):
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Image was successfully added.</p></div>';
			$gallery=$ezDb->get_row("SELECT * FROM `gallery` WHERE `token`='$token';");
      		logEvent($userinfo->email, "new-image-added-to-site", $userinfo->usertype, 'gallery', $gallery);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Unable to Upload. Please try again</div>';
		endif;

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}