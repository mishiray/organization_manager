<?php

$fail="";
$err=0;
if ( in_array($sitePage, array("change_password")) && ($requestMethod == 'POST') && isset($Site["post"]['change_password']) ) {
	$posts = (object) $Site["post"];
	if( empty(trim($posts->passwordold)) ):
		$err++;
		$fail.='<p>Enter your old password!</p>';
	else:
		if(base64_encode($posts->passwordold) !== $userinfo->password):
			$err++;
			$fail.='<p>Old password incorrect. Please reset your password</p>';
		endif;
	endif;
	if( empty($posts->password) ):
		$fail.='<p>Invalid Password: empty password is not allowed</p>';
		$err++;
	endif;
	if( !empty($posts->password) and (strlen($posts->password) < 8 or !preg_match($passwordAuth['1'], $posts->password)  or !preg_match($passwordAuth['1'], $posts->password) or !preg_match($passwordAuth['2'],$posts->password) ) ):
		$fail.='<p>Invalid Password: password should be at least 8 characters in length and should include at least one upper case letter, one number</p>';
		$err++;
	endif;
	if( empty($posts->password2)):
		$fail.='<p>Invalid Password: Please repeat password</p>';
		$err++;
	endif;
	if($posts->password2 !== $posts->password):
		$fail.='<p>Invalid Password: Passwords do not match</p>';
		$err++;
	endif;
	/*error_log("$posts->state $posts->city");
	if( empty($ezDb->get_var("SELECT `ct`.`name` FROM `lawcon_state` AS `st`, `lawcon_city` AS `ct` WHERE `ct`.`state_id`=`st`.`ID` AND `ct`.`name`='$posts->city' AND `st`.`name`='$posts->state' ") ) ):
		$err++;
		$fail.='<p>Select a valid city please!</p>';
	endif;*/
	if ($err==0) {
		$newPassword = base64_encode($posts->password);
		$ezDb->query("UPDATE `userprofile` SET `password`='$newPassword' WHERE `email`='".$Site["session"]["User"]["userinfo"]->email."' AND `usertype`='client'");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your password has been successfully updated. Please login again</p></div>';
		$Site["session"]["User"]["userinfo"] = userInfo($Site["session"]["User"]["userinfo"]->email);
		require_once "mail_change_password.php";
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$smarty->assign("fail",$fail);
$smarty->assign("userinfo", $Site["session"]["User"]["userinfo"]);