<?php
/*Allow User to Add their own state and city if not in the system #improvement*/
$fail="";
$err=0;
if ( in_array($sitePage, array("bank")) && ($requestMethod == 'POST') && isset($Site["post"]['bank']) ) {
	$posts = (object) $Site["post"];
	if( empty($posts->bank_name) ):
		$fail.='<p>Invalid Bank Name: not a valid bank name</p>';
		$err++;
	endif;
	if( empty($posts->account_name)):
		$fail.='<p>Invalid Account Name: not a valid account name</p>';
		$err++;
	endif;
	if( empty($posts->account_number) or !is_numeric($posts->account_number)):
		$fail.='<p>Invalid Account Number: not a valid account number</p>';
		$err++;
	endif;

	if ($err==0) {
		$ezDb->query("UPDATE `userprofile` SET `bank_name`='$posts->bank_name', `account_name`='$posts->account_name', `account_number`='$posts->account_number' WHERE `email`='".$Site["session"]["User"]["userinfo"]->email."' AND `usertype`='client'");
		logEvent($userinfo->email, "profile-update", $userinfo->usertype, 'userprofile', $userinfo);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your bank detail had been successfully updated.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}


$smarty->assign("fail",$fail);
$smarty->assign("userinfo", $Site["session"]["User"]["userinfo"]);