<?php 

if(!in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_report")) && ($requestMethod == 'POST') && isset($Site["post"]['report']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/reports/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
    endif;
	if( empty(trim($posts->client)) ):
		$err++;
		$fail.='<p>Enter clients name please!</p>';
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p>Enter marketers comment or description please!</p>';
	endif;
	if( empty(trim($posts->clientphone)) and checkPhone($posts->clientphone)):
		$err++;
		$fail.='<p>Enter clients phone no please!</p>';
	endif;
	if ($err==0) {
		$posts->content=cleanUp($posts->content);
	    $posts->content=testInputln($posts->content);
	    $posts->title=testInputln($posts->title);
	    $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $extn=end(explode(".", $files->file_upload['name']));
	    $targetFile = $targetDir.$token."file.".$extn;
		if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif;
		/*id	reportid	project	title	client	clientemail	clientphone	location	user	comment	dateadded	status	lawyer_review	md_review attachment*/
		if($ezDb->query("INSERT INTO `reports` (`reportid`, `project`, `title`, `type`, `client`, `clientemail`, `clientphone`, `location`, `user`, `comment`, `dateadded`, `status`, `supervisor_review`, `md_review`, `attachment`, `budget`, `address`, `requesttype`) VALUES ('$token', '$posts->project', '$posts->title','$posts->type', '$posts->client', '$posts->clientemail', '$posts->clientphone', '$posts->location', '$userinfo->email', '$posts->content', '$dateNow', '0', '', '', '$targetFile', '$posts->budget', '$posts->address', '$posts->requesttype');")){
			$report=$ezDb->get_row("SELECT * FROM `reports` WHERE `reportid`='$token';");
			logEvent($userinfo->email, "new-report-added", $userinfo->usertype, 'reports', $report);
		  alertManager($report->user,0,"New Report has been added by $userinfo->employeeid");
		  $fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Report was successfully sent.</p></div>';
	  
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to send</p></div>';
	  
		}
	 }else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}