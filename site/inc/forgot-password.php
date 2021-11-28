<?php global $sitePage, $smarty, $ezDb, $requestMethod, $Site, $siteName, $domainName, $dateNow, $companyName;

// redirect("home");

$eol = "\r\n";
if ( in_array($sitePage, array("forgot-password")) and ($requestMethod=='POST') ) :
	$err=0;
	$fail='';
    if( empty($posts->dataRecoverEmail) ):
    	$fail.='<p>The email field cannot be empty!</p>';
    	$err++;
    endif;
    if(  !checkEmail($posts->dataRecoverEmail)):
    	$fail.='<p>This is not a valid email!</p>';
    	$err++;
    endif;
    if( empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$posts->dataRecoverEmail'")) ):
    	$fail.='<p>The email supplied does not exist!</p>';
    	$err++;
    elseif( empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$posts->dataRecoverEmail' AND `verified`='1'")) ):
    	$fail.='<p>The email is currently yet to be verified!</p>';
    	$fail.='<p>The use the link below to request for verification link!</p>';
    	$fail.='<p><a href="'.$Site['siteProtocol'].$Site['domainName']."/resend?e=$posts->dataRecoverEmail".'" class="btn btn-link">Get Verification Link</a></p>';
    	$err++;
    endif;
    if( $err == 0 ){
		$recKey="rec-".getToken(20).date('YmdHis');
		$ezDb->query("UPDATE `userprofile` SET `reckey`='$recKey', `recdate`='$dateNow' WHERE `email`='$posts->dataRecoverEmail' AND `verified`='1'");
		require_once 'mail_forgot.php';
		
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
	$smarty->assign("fail", $fail);
endif;
$smarty->assign("remail", $posts->dataRecoverEmail);