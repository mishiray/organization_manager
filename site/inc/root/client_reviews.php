<?php 

$Site['post']= (array) $posts;

if (in_array($sitePage, array("client_reviews")) && ($requestMethod == 'POST') && $posts->triggers=='add_review') {
	$fail="";
	$err=0;
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/reviews/";

	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
    endif;

	if(empty($posts->client_name)):
		$err++;
		$fail.='<p>Please add client name</p>';
	endif;
	if(empty($posts->rating)):
		$err++;
		$fail.='<p>Please add rating</p>';
	endif;
	if(empty($posts->comment)):
		$err++;
		$fail.='<p>Please add comment</p>';
	endif;
	if($err == 0){

	    $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `inventory` ORDER BY `id` DESC LIMIT 1;");
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $extn=end(explode(".", $files->file_upload['name']));
	    $targetFile = $targetDir.$token."_review.".$extn;
		if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif;
		$posts->comment = cleanUp($posts->comment);
		$token = getToken(6);
		$query = "INSERT INTO `client_reviews` (`token`,`image`,`client_name`,`rating`,`comment`) VALUES 
											('$token','$targetFile','$posts->client_name','$posts->rating','$posts->comment')";
		//echo $query;
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> New review added </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}

if (in_array($sitePage, array("client_reviews")) && ($requestMethod == 'POST') && $posts->triggers=='delete_review') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `client_reviews` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `client_reviews` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			logEvent($userinfo->email, "reciew-deleted", $userinfo->usertype, 'client_reviews', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Review Deleted</p></div>';
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to delete. kindly re-try</p></div>';
		}
	else: 
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured. Invalid token</p></div>';
	endif;
}


$whereClause="id != 0 ";

$query = "SELECT * FROM `client_reviews` ORDER BY `id` DESC";
$reviews = $ezDb->get_results($query);


$smarty->assign("client_reviews",$reviews);