<?php
$official_email=(!empty($gets->id)? $gets->id: "");
$official=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$official_email' AND `usercat` IN('admin', 'official') AND `userrole` IN('level1','level2', 'level3', 'level4');");
$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
	redirect("home");
endif;

if (!empty($official)) {
	$countries=getCountries();
	$states=getStates($country=$official->country);
	if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='$official->state'")) ){
		$state=$states[0]->name;
	}
	$cities=getCities($state);
	$fail="";
	$err=0;

	/*Updating of Official*/
	if ( in_array($sitePage, array("edit_official")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_official']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/userdata/ppics/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->firstname)) ):
			$err++;
			$fail.='<p>Enter first name please!</p>';
		endif;
		if( empty(trim($posts->username)) ):
			$err++;
			$fail.='<p>Enter username please!</p>';
		endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		if( empty(trim($posts->email)) ):
			$err++;
			$fail.='<p>Enter email please!</p>';
		endif;
		if( !empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$posts->email' AND `email`!='$official_email'") ) ):
			$err++;
			$fail.='<p>There is a user with the supplied email kindly change it!</p>';
		endif;
		if( !empty($ezDb->get_var("SELECT `username` FROM `userprofile` WHERE `username`='$posts->username' AND `email`!='$official_email'") ) ):
			$err++;
			$token=getToken(3).date("Y").$ezDb->get_var("SELECT COUNT(`id`)+1 FROM `userprofile`;");
			$fail.='<p>There is a user with the supplied username kindly change it!, You can use this suggested username: `'.$token.'`</p>';
		endif;
		if( empty(trim($posts->lastname)) ):
			$err++;
			$fail.='<p>Enter last name please!</p>';
		endif;
		if( empty(trim($posts->gender)) or !in_array($posts->gender, array("male","female")) ):
			$err++;
			$fail.='<p>Choose a valid gender!</p>';
		endif;
		if( empty(trim($posts->userrole)) or !in_array($posts->userrole, array("level1", "level2", "level3", "level4")) ):
			$err++;
			$fail.='<p>Choose a valid Admin Dashboard Role!</p>';
		endif;
		/*if( empty($posts->password) ):
			$fail.='<p>Invalid Password: empty password is not allowed</p>';
			$err++;
		endif;
		//$specialChars = preg_match('@[^\w]@', $password);
		if( !empty($posts->password) and ( strlen($posts->password) < 8 or !preg_match('@[A-Z]@', $posts->password)  or !preg_match('@[0-9]@', $posts->password) or !preg_match('@[a-z]@',$posts->password) ) ):
			$fail.='<p>Invalid Password: password should be at least 8 characters in length and should include at least one upper case letter, one number, and one lowercase letter</p>';
			$err++;
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
			if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $targetFile = $targetDir . $posts->email."_profile.png";
		    if (!empty($files->img_upload['tmp_name']) and file_exists($official->userimg)) :
		    	@unlink($official->userimg);
		    endif;
			if ( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ) :
			else:
				$targetFile=$official->userimg;
			endif;
			$ezDb->query("UPDATE `userprofile` SET `username`='$posts->username', `firstname`='$posts->firstname', `middlename`='$posts->othername', `lastname`='$posts->lastname', `gender`='$posts->gender', `phone`='$posts->phone', `address1`='$posts->address1', `address2`='$posts->address2', `country`='$posts->country', `state`='$posts->state', `city`='$posts->city', `email`='$posts->email', `usertype`='admin', `usercat`='admin', `userrole`='$posts->userrole', `userimg`='$targetFile', `addedby`='$userinfo->email' WHERE `email`='$official_email';");
			$ezDb->query("UPDATE `userprofile` SET `addedby`='$posts->email' WHERE `addedby`='$official_email';");
			$ezDb->query("UPDATE `officials` SET `official`='$posts->email' WHERE `official`='$official_email';");
			$ezDb->query("UPDATE `officials` SET `addedby`='$posts->email' WHERE `addedby`='$official_email';");
			$ezDb->query("UPDATE `athletes` SET `addedby`='$posts->email' WHERE `addedby`='$official_email';");
			$ezDb->query("UPDATE `teams` SET `addedby`='$posts->email' WHERE `addedby`='$official_email';");
			$official=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$posts->email' AND `usercat` IN('admin', 'official' AND `userrole` IN('level1','level2'));");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Official detail had been successfully updated.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	/*Deleting of Official*/
	if ( in_array($sitePage, array("edit_official")) && ($requestMethod == 'POST') && isset($Site["post"]['delete_official']) ) {
		if ($err==0) {
			if (file_exists($official->userimg)):
		    	unlink($official->userimg);
		    	$official->userimg="";
			endif;
			$ezDb->query("DELETE FROM `officials` WHERE `official`='$official_email';");
			$ezDb->query("DELETE FROM `userprofile` WHERE `email`='$official_email';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Official detail had been successfully deleted from the system.</p></div>';
		}
	}

	if ( in_array($sitePage, array("add_official")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_official']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		$targetDir="site/media/userdata/ppics/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");

	}

}else{
	redirect("officials");
}
	$smarty->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities)->assign("fail", $fail)->assign("official", $official)->assign("offtype", array("admin"=>"Admin","official"=>"Official"))->assign("offrole", array("level1"=>"Manage Resource","level2"=>"Static Official"));