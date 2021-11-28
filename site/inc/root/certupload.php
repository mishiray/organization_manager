<?php
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;

// Submitv Assignment
if ( in_array($sitePage, array("certupload")) && ($requestMethod == 'POST') && isset($posts->triggers) && $posts->triggers=='cert_upload' ) {
	$targetDir="site/media/client_docs/";
	$files= (object) $Site["files"];
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	$fail='';
	/*
	if(!empty($project=$ezDb->get_row("SELECT * FROM `certificate` WHERE `user`='$posts->student'"))):
		$err++;
		$fail.='<p>The selected client is already having a certificate, kindy check the certificate list!</p>';
	endif;
	*/
	if( empty(trim($posts->title))):
		$err++;
		$fail.='<p>Kindly enter title!</p>';
	endif;
	if( empty($posts->token)):
		$err++;
		$fail.='<p>Kindly select Reference!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly enter description or content!</p>';
	endif;

	if ( $err==0 ) {
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $newArray=array();

		$previous=new stdClass(); 
		$errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
		foreach($_FILES['material_upload']['name'] as $key=>$val){ 
            // File upload path 
            $fileName = basename($_FILES['material_upload']['name'][$key]); 
            $targetFilePath = $targetDir . $fileName; 
            // Check whether file type is valid 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			/*if (in_array(strtolower(getMime($key), $extensions)){
				$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
				$err++;
			}*/
            if(move_uploaded_file($_FILES["material_upload"]["tmp_name"][$key], $targetFilePath)){ 
                // Image db insert sql 
                $insertValuesSQL .= "$targetFilePath,"; 
            }else{ 
                $errorUpload .= $_FILES['material_upload']['name'][$key].' | '; 
            } 
        } 
		
		$insertValuesSQL = trim($insertValuesSQL, ','); 
		//$targetFile = $targetDir .getToken("5") .($ezDb->get_var("SELECT (IFNULL((`id`),0)+1) FROM `certificate` ORDER BY `id` DESC LIMIT 1;")) .$files->material_upload['name'];
		//$targetFile = ((!empty($files->material_upload['tmp_name']) and @move_uploaded_file($files->material_upload['tmp_name'], $targetFile))? $targetFile: "");
		$previous->description=$posts->description=testInput(str_replace("\n", "<br/>", $posts->description));
		$previous->title=$posts->title=testInput($posts->title);
		$previous->certurl=$targetFilePath;
		if($ezDb->query("INSERT INTO `certificate` (`token`,`user`, `title`, `description`, `certurl`, `datesent`, `dateupdated`, `updatedby`,`status`) VALUES ('$posts->token','$posts->student', '$posts->title', '$posts->description', '$insertValuesSQL', '$dateNow', '$dateNow', '$userinfo->email',1)"))
		{
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project was successfully created and submitted.</p></div>';
			logEvent($userinfo->email, "client-document", $userinfo->usertype, 'certificate', $previous);
		}else{
			$errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
			$errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
			$errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$errorMsg.'</p></div>';
		}
	}else{
		$fail='<tr><td colspan="4"><div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div></td></tr>';
	}
}

$clients=$ezDb->get_results("SELECT CONCAT(`firstname`, ' ', `middlename`, ' ', `surname`) AS `names`, `email`, `phone`, `token` FROM `subscription` ORDER BY `names` DESC;");

//$clients=$ezDb->get_results("SELECT CONCAT(`firstname`, ' ', `middlename`, ' ', `lastname`) AS `names`, `email`, `phone`, `username` FROM `userprofile` WHERE `usertype`='client' ORDER BY `id` DESC;");

$smarty->assign("students", $clients);