<?php
$whereClause="";
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;
/*Edit Project*/
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$clients=$ezDb->get_results("SELECT CONCAT(`firstname`, ' ', `middlename`, ' ', `lastname`) AS `name`, `email`, `phone`, `username` FROM `userprofile` WHERE `usertype`='client' ORDER BY `id` DESC;");
$smarty->assign("clients", $clients);
$filter = new stdClass();
if (in_array($sitePage, array("certificates")) && ($requestMethod == 'POST') && $posts->triggers=='cert_search') {
	//$err=0;
	$whereClause = "";
	if (empty($posts->year)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND YEAR(`datesent`) = '$posts->year' ";
		$filter->year = $posts->year;
	endif;
	if (empty($posts->month)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND MONTHNAME(`datesent`) = '$posts->month' ";
		$filter->month = $posts->month;
	endif;
	if (empty($posts->client)):
		$whereClause .= "";
	else:
		$whereClause .= " AND `user` = '$posts->client'";
		$filter->client = $posts->client;
	endif;
	
	$certificates=tableRoutine("certificate", '*' , " `id`!=0 $whereClause", '`id`');
		if (!empty($certificates)) {
			foreach ($certificates as $key => $certificate) {
				$certificate->student=$ezDb->get_row("SELECT  CONCAT(`firstname`, ' ', `middlename`, ' ', `lastname`) AS `names`, `email`, `phone`, `username` FROM `userprofile` WHERE `email`='$certificate->user'");
				$certificate->description=testInputReverse(trim($certificate->description));
				$certificate->title=testInputReverse(trim($certificate->title));
				if(!empty($certificate->certurl)){
					$images = $certificate->certurl;
					$myArray = explode(',', $images);
					$certificate->images = $myArray;
				}
				if(!empty($certificate->token)){
					$certificate->item = 'subscription';
						$certificate->details = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$certificate->token'");
					if(empty($certificate->details)){
						$certificate->item = 'investment';
						$certificate->details = $ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$certificate->token'");
					}
				}
			}
		}
}else{
	$certificates=tableRoutine("certificate", '*' , " `id`!=0 $whereClause", '`id`');
	if (!empty($certificates)) {
		foreach ($certificates as $key => $certificate) {
			$certificate->student=$ezDb->get_row("SELECT  CONCAT(`firstname`, ' ', `middlename`, ' ', `lastname`) AS `names`, `email`, `phone`, `username` FROM `userprofile` WHERE `email`='$certificate->user'");
			$certificate->description=testInputReverse(trim($certificate->description));
			$certificate->title=testInputReverse(trim($certificate->title));
			if(!empty($certificate->certurl)){
				$images = $certificate->certurl;
				$myArray = explode(',', $images);
				$certificate->images = $myArray;
			}
			if(!empty($certificate->token)){
				$certificate->item = 'subscription';
					$certificate->details = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$certificate->token'");
				if(empty($certificate->details)){
					$certificate->item = 'investment';
					$certificate->details = $ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$certificate->token'");
				}
			}
		}
	}
}

if ( in_array($sitePage, array("certificates")) && ($requestMethod == 'POST') && isset($posts->edit_cert) ) {
	$files= (object) $Site["files"];
	$targetDir="site/media/certificates/";
	$files= (object) $Site["files"];
	$extensions = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/zip");
	$fail='';
	if( !empty($files->material_upload['tmp_name'])  and !in_array(strtolower(getMime($files->material_upload['tmp_name'])), $extensions)):
		$fail .='<p>Invalid File: Kindly upload a valid file: Document file is allowed</p>';
	    $err++;
    endif;
	if( empty(trim($posts->title))):
		$err++;
		$fail.='<p>Kindly enter certificate title!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly enter certificate description or content!</p>';
	endif;
	$cert=$ezDb->get_row("SELECT * FROM `certificate` WHERE `id`='$posts->certid' AND `user`='$posts->userid'");
	if(empty($cert)):
		$err++;
		$fail.='<p>Unable to find selected record. Certificate does not exist!</p>';
	endif;
	if( $err==0 ):
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    error_log(json_encode($files->material_upload));
		$targetFile = $targetDir .getToken("5") .$cert->id .$files->material_upload['name'];
		if (!empty($files->material_upload['name']) and @move_uploaded_file($files->material_upload['tmp_name'], $targetFile)):
			if ( file_exists($cert->certurl) ):
				@unlink($cert->certurl);
			endif;
			$cert->certurl = $targetFile;
		endif;
		$ezDb->query("UPDATE `certificate` SET `title`='$posts->title', `description`='$posts->description', `certurl`='$cert->certurl' WHERE `id`='$posts->certid' AND `user`='$posts->userid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Certificate detail successfully update.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
$smarty->assign("certificates", $certificates)->assign("months", $months)->assign("filter",$filter);