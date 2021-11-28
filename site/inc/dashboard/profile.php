<?php
/*Allow User to Add their own state and city if not in the system #improvement*/
$countries=getCountries();
$states=getStates($userinfo->country);

if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='".$userinfo->state."'")) ){
	$state=$states[0]->name;
}
$cities=getCities($state);

$fail="";
$err=0;
if ( in_array($sitePage, array("profile")) && ($requestMethod == 'POST') && isset($Site["post"]['personal']) ) {
	$posts = (object) $Site["post"];
	/*if( empty(trim($posts->firstname)) ):
		$err++;
		$fail.='<p>Enter first name please!</p>';
	endif;*/
	/*if( empty(trim($posts->username)) ):
		$err++;
		$fail.='<p>Enter username please!</p>';
	endif;*/
	/*if( empty(trim($posts->lastname)) ):
		$err++;
		$fail.='<p>Enter last name please!</p>';
	endif;*/
	/*if( empty(trim($posts->gender)) or !in_array($posts->gender, array("male","female")) ):
		$err++;
		$fail.='<p>Choose a valid gender!</p>';
	endif;*/
	if( empty(trim($posts->phone)) or !is_numeric($posts->phone)):
		$err++;
		$fail.='<p>Enter phone number please!</p>';
	endif;
	if( empty($ezDb->get_var("SELECT `name` FROM `countries` WHERE `name`='$posts->country'") ) ):
		$err++;
		$fail.='<p>Select a valid country please!</p>';
	endif;
	if( empty($ezDb->get_var("SELECT `st`.`name` FROM `countries` AS `cn`, `states` AS `st` WHERE `st`.`country_id`=`cn`.`id` AND `st`.`name`='$posts->state' AND `cn`.`name`='$posts->country' ") ) ):
		$err++;
		$fail.='<p>Select a valid state please!</p>';
	endif;
	/*error_log("$posts->state $posts->city");
	if( empty($ezDb->get_var("SELECT `ct`.`name` FROM `lawcon_state` AS `st`, `lawcon_city` AS `ct` WHERE `ct`.`state_id`=`st`.`ID` AND `ct`.`name`='$posts->city' AND `st`.`name`='$posts->state' ") ) ):
		$err++;
		$fail.='<p>Select a valid city please!</p>';
	endif;*/

	if ($err==0) {
		$ezDb->query("UPDATE `userprofile` SET `middlename`='$posts->othername', `phone`='$posts->phone', `address1`='$posts->address1', `address2`='$posts->address2', `country`='$posts->country', `state`='$posts->state', `city`='$posts->city' WHERE `email`='".$userinfo->email."' AND `usertype`='client'");
		logEvent($userinfo->email, "profile-update", $userinfo->usertype, 'userprofile', $userinfo);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your personal detail had been successfully updated.</p></div>';
		$userinfo = userInfo($userinfo->email);
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

if ( in_array($sitePage, array("profile")) && ($requestMethod == 'POST') && isset($Site["files"]['img_upload']) ) {
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/userdata/ppics/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
	if ( !file_exists("$targetDir") ) :
        mkdir("$targetDir", 0777, true);
    endif;
	if (!in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;
	if($err==0){
		$targetFile = $targetDir . $userinfo->email."_profile.png";
		$previous=new stdClass();
		$previous->path=$userinfo->userimg;
		$imgContent=@file_get_contents($userinfo->userimg);
		$previous->image=@base64_encode($imgContent);
		if ( @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ) :
			$userinfo->userimg=$targetFile;
			$ezDb->query("UPDATE `userprofile` SET `userimg`='$targetFile' WHERE `email`='".$userinfo->email."' AND `usertype`='client';");
			logEvent($userinfo->email, "profile-picture", $userinfo->usertype, 'userprofile', $previous);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your profile picture had been successfully updated.</p></div>';
		endif;
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$smarty->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities)->assign("fail",$fail);