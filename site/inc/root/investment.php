<?php

$investmentid=(!empty($gets->id)? $gets->id: "");

$investment=$ezDb->get_row("SELECT * from `investments` WHERE `token` = '$investmentid';");

$properties=$ezDb->get_results("SELECT * from `projects` where active = 1;");

if(!empty($investment)){

	if (!empty($posts->triggers) and $posts->triggers=='edit_investment') {
		
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/inventory/";
	
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
		 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
		 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		
		 if( empty(trim($posts->property)) ):
			$err++;
			$fail.='<p>Select property please!</p>';
		endif;
		if( empty(trim($posts->percentage)) ):
			$err++;
			$fail.='<p>Select percentage please!</p>';
		endif;
		if( empty(trim($posts->category)) ):
			$err++;
			$fail.='<p>Select category please!</p>';
		endif;
		if( empty(trim($posts->duration)) ):
			$err++;
			$fail.='<p>Enter duration please!</p>';
		endif;
		if( empty(trim($posts->min_principal)) ):
			$err++;
			$fail.='<p>Enter min principal please!</p>';
		endif;
		if( empty(trim($posts->max_principal)) ):
			$err++;
			$fail.='<p>Enter max principal please!</p>';
		endif;
		if( empty(trim($posts->roi)) ):
			$err++;
			$fail.='<p>Enter R.O.I please!</p>';
		endif;
		if( empty(trim($posts->matunity)) ):
			$err++;
			$fail.='<p>Enter maturity please!</p>';
		endif;
		/*if( empty(trim($posts->clientemail)) and checkEmail($posts->clientemail)):
			$err++;
			$fail.='<p>Enter client email please!</p>';
		endif;*/
	
		if ($err==0) {
			
			$query = "UPDATE `investments` SET `property` = '$posts->property' , `category` = '$posts->category',`duration` = '$posts->duration' , `percentage` = '$posts->percentage' ,`min_principal` = '$posts->min_principal',`max_principal` = '$posts->max_principal',`roi` = '$posts->roi' ,`matunity` = '$posts->matunity' , `active` = '$posts->active' WHERE `token` = '$investmentid'";
			//echo $query;
			if($ezDb->query($query)):
				$investment=$ezDb->get_row("SELECT * FROM `investments` WHERE `token`='$investmentid';");
				logEvent($userinfo->email, "updated-investment", $userinfo->usertype, 'investment', $investment);
			
				//$investment=$ezDb->get_row("SELECT * from `investments` WHERE `token` = '$investmentid';");

				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your plan was successfully updated</p></div>';
				//header('Location: investment?id='.$investmentid);
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
	
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}
	
	
	if (!empty($posts->triggers) and $posts->triggers=='delete_investment') {
		if ($err==0) {
			$ezDb->query("DELETE FROM `investments` WHERE `token`='$investmentid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Item was successfully deleted.</p></div>';

		}
	}
}

$smarty->assign("inv_item", $investment)->assign("properties", $properties);
