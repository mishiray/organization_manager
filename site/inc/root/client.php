<?php


$clientemail=(!empty($gets->id)? $gets->id: "");
$client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$clientemail' OR `email`='$clientemail' AND `usertype`='client';");
$subdata = $ezDb->get_results("SELECT * FROM `subscription` WHERE `email` = '$clientemail'");

if (!empty($client) and is_object($client)) {
	$client->password=base64_decode($client->password);
	$fail="";
	$err=0;

	if ( !empty($posts->triggers) and $posts->triggers=='edit_client') {
		//echo 'im in';
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		$targetDir="site/media/userdata/ppics/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");

		$userinfo=$Site['session']['User']['userinfo'];
		if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">You are not authorised to edit student detail!</p>';
		endif;
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p class="border border-danger p-1 rounded">The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->firstname)) ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Enter first name please!</p>';
		endif;
		if( empty(trim($posts->username)) ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Enter username please!</p>';
		endif;
		if( !empty($ezDb->get_var("SELECT `username` FROM `userprofile` WHERE `username`='$posts->username' AND `email`!='$clientemail'") ) ):
			// $err++;
			$posts->username=$posts->username.$ezDb->get_var("SELECT IFNULL(`id`+1, 1) FROM `userprofile` ORDER BY `id` DESC LIMIT 1;");
			// $fail.='<p class="border border-danger p-1 rounded">There is a user with the supplied username kindly change it!, You can use this suggested username: `'.$token.'`</p>';
		endif;
		if( empty(trim($posts->lastname)) ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Enter last name please!</p>';
		endif;
		if( empty(trim($posts->gender)) or !in_array($posts->gender, array("male","female")) ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Choose a valid gender!</p>';
		endif;
		if( empty($posts->password) ):
			$fail.='<p class="border border-danger p-1 rounded">Invalid Password: empty password is not allowed</p>';
			$err++;
		endif;
		//$specialChars = preg_match('@[^\w]@', $password);
		if( !empty($posts->password) and strlen($posts->password) < 8 ):
			$fail.='<p class="border border-danger p-1 rounded">Invalid Password: password should be at least 8 characters in length and should include at least one upper case letter, one number, and one lowercase letter</p>';
			$err++;
		endif;
		/*
		if( empty($ezDb->get_var("SELECT `ct`.`name` FROM `lawcon_state` AS `st`, `lawcon_city` AS `ct` WHERE `ct`.`state_id`=`st`.`ID` AND `ct`.`name`='$posts->city' AND `st`.`name`='$posts->state' ") ) ):
			$err++;
			$fail.='<p>Select a valid city please!</p>';
		endif;*/
		if ($err==0) {
			if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $targetFile = $targetDir . $posts->email."_profile.png";
			if ( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ) :
			else:
				$targetFile=$client->userimg;
			endif;
			$ezDb->query("UPDATE `userprofile` SET `username`='$posts->username', `firstname`='$posts->firstname', `middlename`='$posts->othername', `lastname`='$posts->lastname', `gender`='$posts->gender', `phone`='$posts->phone', `password`='".base64_encode($posts->password)."',`userimg`='$targetFile' WHERE `email`='$clientemail' AND `usertype`='client';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
			<p>Member\'s detail was successfully updated.</p>
			<p><strong>Client Detail is</strong><br/> Username: '.$posts->username.'<br/> Password: '.$posts->password.'.</p>
			</div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
		//header('Location: clients');
		//echo $fail;
	}

	if (!empty($posts->triggers) and $posts->triggers=='delete_client') {
		if ($err==0) {
			$ezDb->query("DELETE FROM `userprofile` WHERE FROM `userprofile` WHERE `username`='$posts->username' AND `email`='$clientemail';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Client was successfully deleted.</p></div>';
			redirect("clients");
		}
	}
	
	if (!empty($posts->triggers) and $posts->triggers=='activate') {
		$posts = (object) $Site["post"];
		$ezDb->query("UPDATE `userprofile` SET `active` = '$posts->active' WHERE `email`='$client->email';");
		
		if($posts->active == 1){
			$new = false;
			$confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
			if(empty($ezDb->query("SELECT * FROM `keys` WHERE `email` = '$client->email'"))){
				$ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($client->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
				$new = true;	
			}else{
				$new = false;
				$ezDb->query("UPDATE `keys` SET `key` = '$confirmkey', `expiredon` = DATE_ADD('$dateNow', INTERVAL 2 DAY)  WHERE `email` = '".strtolower($client->email)."'");
			}
			$client->name = $client->lastname.' '.$client->firstname;
			require_once 'mail_login_detail.php';
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Client account has been activated.</p></div>';		
		}else{
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Client account has been deactivated.</p></div>';		
		}
	}
}else{
	redirect("clients");
}

$smarty->assign("client", $client)->assign("subdata",$subdata);