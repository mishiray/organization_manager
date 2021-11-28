<?php

// redirect("home");

if(!empty($gets->e)):

	if(!empty($gets->e) and !empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='".strtolower(trim($gets->e))."' AND `usertype`='client' AND `verified`='1';"))):
		$fail.='<p>Invalid Email: this email had already been verified kindly request for password reset from the login page</p>';
		$err++;
	endif;

	if(!empty($gets->e) and empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='".strtolower(trim($gets->e))."' AND `usertype`='client';"))):
		$fail.='<p>Invalid Email: this email does not exists</p>';
		$err++;
	endif;

	if($err==0 and !empty($posts=$ezDb->get_row("SELECT `firstname`,`email` FROM `userprofile` WHERE `email`='".strtolower(trim($gets->e))."' AND `usertype`='client' AND `verified`='0';")) ):
		if(!empty($gets->e) and empty($confirmkey=$ezDb->get_var("SELECT `key` FROM `keys` WHERE `email`='".strtolower(trim($gets->e))."';"))):
			$confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
			$ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($posts->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
		else:
			$confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
			$ezDb->query("UPDATE `keys` SET `key`='$confirmkey', `expiredon`=DATE_ADD('$dateNow', INTERVAL 2 DAY) WHERE `email`='".strtolower($posts->email)."';");
		endif;
		$posts=new stdClass();
		$posts->email=$gets->e;
		require_once 'mail_signup.php';
		$fail='<div class="alert alert-primary alert-dismissible" role="alert">
					<i class="fa fa-checked"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3>Messages</h3><p>Your verification link had been resent to your email</p>
					<p>Kindly visit your email to verify OR Click on <a href="'.$Site['siteProtocol'].$Site['domainName']."/resend?e=$posts->email".'" class="btn btn-link">Resend</a> if you are not receiving any confirmation link</p>
				</div>';
	else:
		$fail='<div class="alert alert-danger text-justify">
					<i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3>Error Messages</h3><div class="alert alert-danger alert-dismissible" role="alert"> '.$fail.'</div>
				</div>';
	endif;
endif;