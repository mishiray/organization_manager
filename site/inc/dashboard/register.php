<?php

if($userinfo->active=='0'):
	$ezDb->query("UPDATE `userprofile` SET `active`='0' WHERE `email`='$userinfo->email'");
endif;

$fail="";
if ( in_array($sitePage, array("register")) && ($requestMethod == 'POST') 
	&& !empty($posts->pay) && $posts->pay=='paypal' ) {
	$settings=getSettings();
	if ($lastreg->isExpired==true) {
		$email=$userinfo->email;
		// $amount=$settings->subscription->amount-($settings->subscription->amount*($settings->subscription->discount/100));
		$amount = 
		$token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `payments` ORDER BY `id` DESC LIMIT 1");
		$posts->amount = $membershipSub["$userinfo->membership"]["amount"];
		// $Site["session"]['ptoken']=$Site["session"]['regToken']=$token;
		// $Site["session"]['amount']=$settings->subscription->amount;
		// $Site["session"]['pslug']=$Site["session"]['purpose']='membership';
		$posts->purpose = 'membership';
		// $Site["session"]['discount']=$settings->subscription->discount;
		// $Site["session"]['program']='';
		$_SESSION=$Site["session"];
		$sessions= (object)$Site["session"];
		// require_once 'paystack_connect.php';
		require_once 'rec_payment.php';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
			<p class="border bg-white border-danger px-2 py-1 rounded">Your membership plan is currently active</p>
		</div>';
	}
}

$smarty->assign("settings", $settings)->assign("lastreg", $lastreg);
// echo "page is under development";