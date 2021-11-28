<?php global $sitePage, $smarty, $ezDb, $requestMethod, $Site, $siteName, $domainName;

// redirect("home");

$newPass=null; $confPass=null;
$key=( !empty($gets->k)? $gets->k: null);
$err=0;
$fail='';
if( empty($key) or empty($posts->email=$ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `reckey`='$key' AND `verified`='1'")) or explode("-", $key)[0]!="rec" ):
	$fail.='<p><strong>Notice:</strong></p><p>The link you request for is broken.</p>';
	$key=null;
	$err++;
else:
	if( $keyage= $ezDb->get_var("SELECT TIMESTAMPDIFF(SECOND, `recdate`, '$dateNow') AS `keyage` FROM `userprofile` WHERE `reckey`='$key' AND `verified`='1'") ):
		if ($keyage > 24*60*60):
			$fail.='<p>The link you request for has expired. Request for password reset again</p>';
			$fail.='<p>Kindly request for another reset link using this link <a href="'.$Site['siteProtocol'].$Site['domainName']."/resend?e=$posts->email".'" class="btn btn-link">Resend</a></p>';
			$ezDb->query("UPDATE `userprofile` SET `reckey`=NULL WHERE `reckey`='$key' AND `verified`='1'");
			$key=null;
			$err++;
		endif;
	endif;
endif;
if ( in_array($sitePage, array("reset")) and ($requestMethod == 'POST') ) :
	$pass= $Site["post"]['dataNewPass'];
	$cpass= $Site["post"]['dataConfPass'];
	$fail='<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	if( empty(trim($pass)) ):
		$err++;
		$fail.='<p>Password field cannot be empty</p>';
	endif;
	if( empty(trim($cpass)) ):
		$err++;
		$fail.='<p>Confirm password field cannot be empty</p>';
	endif;
	if( $pass!=$cpass ):
		$err++;
		$fail.='<p>Password does not match confirm password</p>';
	endif;
	if( stripos($pass, " ")==true ):
		$err++;
		$fail.='<p>Weak password entry</p>';
	endif;
	if ( !preg_match("/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $pass) ):
		$err++;
		$fail .= '<p>Password must contain alteast an Uppercase, a lowercase, numbers, and special symbol of characters!<p>';
	endif;
	if(strlen($pass) < 8 ):
		$fail.= '<p class="alert alert-danger">Password must be minimum of eight (8) characters</p>';
		$err ++;
	endif;
	if( $err==0 and $pass==$cpass):
		$ezDb->query("UPDATE `userprofile` SET `password`='".base64_encode($pass)."', `reckey`=NULL WHERE `reckey`='$key'");
		$fail='<div class="alert alert-primary alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your password had been successfully change, you can now proceed to login</p></div>';
	endif;
endif;
if( $err >0 ):
	$fail='<div class="alert alert-danger alert-dismissible" role="alert"> '.$fail.'</div>';
endif;

$smarty->assign("userIn", userInfo($posts->email));
$smarty->assign("fail", $fail)->assign("key", $key)->assign("newPass", $newPass)->assign("confPass", $confPass);