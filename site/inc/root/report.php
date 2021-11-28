<?php 

$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3','level4')) ):
	redirect("home");
endif;

$report=$ezDb->get_row("SELECT * FROM `reports` WHERE `reportid`='$id'");
if (!empty($report)) {
	if ( in_array($sitePage, array("report")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( (in_array($report->status, array('0','3')) and $userinfo->email==$report->user) 
		or (in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1')
	 	or $userinfo->userrole=='level0') ) {
		if (!empty($report) and $gets->evt=='delete') {
			if( file_exists($report->attachment)):
				@unlink($report->attachment);
			endif;
			$ezDb->query("DELETE FROM `reports` WHERE `reportid`='$id'");
			logEvent($userinfo->email, "report-deleted", $userinfo->usertype, 'reports', $report);
			alertUser($report->user,1,'Report has been deleted');
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull deleted.</p></div>';
		}
	}

	if ( in_array($sitePage, array("report")) && ($requestMethod == 'POST') && (isset($posts->lreview) or isset($posts->mreview) or isset($posts->hreview)) and ( (in_array($report->status, array('0','3','1','4')) and in_array($userinfo->userrole, array('level3','level2'))) or 
		(in_array($report->status, array('0','2','1','4')) and $userinfo->userrole=='level1')
	 or $userinfo->userrole=='level0') ) {

		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Your comment is required please!</p>';
		endif;

		if( (!in_array($posts->status, array('1', '3')) and isset($posts->lreview) and 
			( (in_array($report->status, array('0','3','1','4')) and $userinfo->userrole=='level3') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		elseif( (!in_array($posts->status, array('5', '7')) and isset($posts->hreview) and 
				( (in_array($report->status, array('5','1','7')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0' ) ) ):
				$err++;
				$fail.='<p>Kindly Choose a valid status!</p>';
		elseif( (!in_array($posts->status, array('2', '4')) and isset($posts->mreview) and 
			( (in_array($report->status, array('2','5','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0' ) ) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		endif;

		if ($err==0) {
			$revDet=new stdClass();
			$revDet->user=$userinfo->email;
			$revDet->comment=$posts->comment;
			$revLog='supervisor';
			if( ((in_array($report->status, array('0','3','1','4')) and in_array($userinfo->userrole, array('level2','level3'))) or $userinfo->userrole=='level0' or $userinfo->userrole=='level1') and isset($posts->lreview) ):
				if($ezDb->query("UPDATE `reports` SET `status`='$posts->status', `supervisor_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'")){

					alertUser2($report->user,0,'Report has been reviewed by Supervisor',"report?id=$id");
					$supervisor = getMyManager($report->user);
					$hos = getMyManager($supervisor);
					alertUser2($hos,0,'Report has been reviewed by Supervisor',"report?id=$id");
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull reviewed.</p></div>';
		
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully reviewed.</p></div>';
			
				}

			elseif(((in_array($report->status, array('5','7','0','1')) and $userinfo->userrole=='level2') or $userinfo->userrole=='level0' ) and isset($posts->hreview) ):
					$revLog='hos';
					if($ezDb->query("UPDATE `reports` SET `status`='$posts->status', `hos_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'")){

						alertManager($report->user,0,'Report has been reviewed by HOS',"report?id=$id");
						alertMd2(0,'New Report has been reviewed by HOS',"report?id=$id");
						$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull reviewed.</p></div>';
		
					}else{
						$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully reviewed.</p></div>';
			
					}
			elseif(((in_array($report->status, array('2','1','4')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0') and isset($posts->mreview) ):
				$revLog='md';
				if($ezDb->query("UPDATE `reports` SET `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `reportid`='$id'")){

					alertManager($report->user,0,'Report has been reviewed by MD',"report?id=$id");
					alertUser($report->user,0,'Report has been reviewed by MD',"report?id=$id");
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull reviewed.</p></div>';
		
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully reviewed.</p></div>';
			
				}
			endif;
      		logEvent($userinfo->email, "report-reviewed-by-$revLog", $userinfo->usertype, 'reports', $revDet);
			$report=$ezDb->get_row("SELECT * FROM `reports` WHERE `reportid`='$id'");
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("report")) && ($requestMethod == 'POST') && isset($Site["post"]['report']) and ((in_array($report->status, array('0', '4','3')) and $userinfo->email==$report->user) or $userinfo->userrole=='level0') ) {
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
	    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->requesttype)) ):
			$err++;
			$fail.='<p>Enter clients report type please!</p>';
		endif;
		if( empty(trim($posts->client)) ):
			$err++;
			$fail.='<p>Enter clients name please!</p>';
		endif;
		if( empty(trim($posts->address)) ):
			$err++;
			$fail.='<p>Enter clients address please!</p>';
		endif;
		if( empty(trim($posts->budget)) ):
			$err++;
			$fail.='<p>Enter clients budget please!</p>';
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Enter marketers comment or description please!</p>';
		endif;
		if(!in_array($userinfo->userrole, array('level0','level1', 'level2', 'level3','level4'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		// if( empty(trim($posts->clientemail)) and checkEmail($posts->clientemail)):
		// 	$err++;
		// 	$fail.='<p>Enter client email please!</p>';
		// endif;
		if( empty(trim($posts->clientphone)) and checkPhone($posts->clientphone)):
			$err++;
			$fail.='<p>Enter clients phone please!</p>';
		endif;
		if( empty($posts->location)):
			$err++;
			$fail.='<p>Enter clients request location please!</p>';
		endif;

		if ($err==0) {
		    $posts->content=testInputln($posts->content);
		    $posts->title=testInputln($posts->title);
			if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $extn="";
		    if ( !empty($files->file_upload['tmp_name']) ) :
		    	$extn=end(explode(".", $files->file_upload['name']));
				@unlink($report->attachment);
			endif;
		    $targetFile = $targetDir.$id."file.".$extn;
			if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
				$report->attachment=$targetFile;
			else:
				$targetFile=$report->attachment;
			endif;
			/*id	reportid	project	title	client	clientemail	clientphone	location	user	comment	dateadded	status	supervisor_review	md_review attachment*/
			if($ezDb->query("UPDATE `reports` SET `requesttype`='$posts->requesttype', `address`='$posts->address', `budget`='$posts->budget', `project`='$posts->project', `title`='$posts->title', `client`='$posts->client', `clientemail`='$posts->clientemail', `clientphone`='$posts->clientphone', `location`='$posts->location', `comment`='$posts->content', `attachment`='$targetFile' WHERE `reportid`='$id';")){
				logEvent($userinfo->email, "report-updated", $userinfo->usertype, 'reports', $report);
				alertManager2($report->user,0,"Report has been updated by $userinfo->employeeid","report?id=$id");
				alertUser2($report->user,0,"Report has been updated by $userinfo->employeeid","report?id=$id");
				$report=$ezDb->get_row("SELECT * FROM `reports` WHERE `reportid`='$id'");
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was successfull updated.</p></div>';
			
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Report was not successfully updated.</p></div>';
			
			}
      	}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$report->supervisor_review=(empty($report->supervisor_review)? new stdClass(): @json_decode($report->supervisor_review));
	$report->supervisor=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->supervisor_review->user."'");
	$report->hos_review=(empty($report->hos_review)? new stdClass(): @json_decode($report->hos_review));
	$report->hos=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->hos_review->user."'");
	$report->md_review=(empty($report->md_review)? new stdClass(): @json_decode($report->md_review));
	$report->md=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$report->md_review->user."'");
	$report->marketerDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$report->user'");
	$report->comment1=testInputReverse(trim($report->comment));
	$report->comment=testInputReverse(trim($report->comment));
	$report->content_stripe=stripeInputReverse($report->comment);

	$report->title1=testInputReverse(trim($report->title));
	$report->title=testInputReverse(trim($report->title));
}else{
	redirect('reports');
}
$smarty->assign("report", $report);