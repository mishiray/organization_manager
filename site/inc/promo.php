<?php

$promo_token = (!empty($gets->token) ? $gets->token : '');
$promo_name = (!empty($gets->name) ? $gets->name : '');

$promo = $ezDb->get_row("SELECT * FROM `promos` WHERE `name` = '$promo_name' AND `token` = '$promo_token'");
if(!empty($promo)){
	if(!empty($posts->triggers) and ($posts->triggers=='subscribeProduct')):
	
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		$targetDir="site/media/promos";
	
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
		 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
		 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if ( !empty($files->uploadfile['tmp_name']) and !in_array(strtolower(getMime($files->uploadfile['tmp_name'])), $extensions)):
			$fail .= '<p>The uploaded file is not supported.</p>';
			$err++;
		endif;
	
		if($err==0):
			$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
			if ( !file_exists("$targetDir") ) :
				mkdir("$targetDir", 0777, true);
			endif;
			$extn = pathinfo($files->uploadfile['name'], PATHINFO_EXTENSION);
			//$extn=end(explode(".", $files->uploadfile['name']));
			$targetFile = $targetDir.$token."file.".$extn;
			if ( !empty($files->uploadfile['tmp_name']) and @move_uploaded_file($files->uploadfile['tmp_name'], $targetFile) ) :
			else:
				$targetFile="";
			endif;
			
			$token = $token=getToken(8);
	
			$query = "INSERT INTO `promos_subscription`(
					`token`,
					`promo_token`,
					`img_filename`, 
					`title`,
					`surname`, 
					`middlename`, 
					`firstname`, 
					`sex`, 
					`mobile`, 
					`phone`, 
					`email`,
					`address`, 
					`city`, 
					`lga`, 
					`state`, 
					`country`, 
					`dob`, 
					`id_mode`, 
					`id_mode_others`, 
					`id_number`, 
					`nationality`, 
					`state_of_origin`, 
					`occupation`, 
					`nok_surname`,
					`nok_middlename`, 
					`nok_firstname`, 
					`nok_phone`, 
					`nok_address`, 
					`numplots`, 
					`amount`,
					`total_amount`
					) VALUES (
					'$token',
					'$promo->token',
					'$targetFile',
					'$posts->title',
					'$posts->surname',
					'$posts->middlename',
					'$posts->firstname',
					'$posts->gender',
					'$posts->number',
					'$posts->number1',
					'$posts->email',
					'$posts->address',
					'$posts->city',
					'$posts->lga',
					'$posts->state',
					'$posts->country',
					'$posts->dob',
					'$posts->idmode',
					'$posts->otheridmode',
					'$posts->idno',
					'$posts->nationality',
					'$posts->stateoforigin',
					'$posts->occupation',
					'$posts->noksurname',
					'$posts->nokmiddlename',
					'$posts->nokfirstname',
					'$posts->noknumber',
					'$posts->nokaddress',
					'$posts->numplots',
					'$posts->tamount',
					'$posts->totalpay');";
				//echo $query;
				if($ezDb->query($query)):	
					
					$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Your form has been successfully submitted.</p></div>';
					
				else:
					$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
				endif;
				
			
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
		endif;
	
	endif;
}
$smarty->assign("promo", $promo);