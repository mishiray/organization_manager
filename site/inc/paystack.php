<?php 


if (!empty($sessions->token) && in_array($sitePage, array("paystack")) && ($requestMethod == 'POST') && !empty($posts->payBtn) && $posts->payBtn=='paystack') {
	if (!empty($servers->HTTP_REFERER)) {
		$curl = curl_init();

		$email = $sessions->email;
		$token = $sessions->token;

		//$table = ($sessions->purpose=='investment') ? 'investment_subscription' : 'subscription';

		if($sessions->purpose=='investment'){
			$subscription=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$token' ");
			$property=$ezDb->get_row("SELECT * FROM `investments` WHERE `id` = '$subscription->investments_id' ");
		}else{
			$subscription=$ezDb->get_row("SELECT * FROM `$sessions->table_name` WHERE `token` = '$token' ");
			$property=$ezDb->get_row("SELECT * FROM `projects` WHERE `name` = '$subscription->product' ");
		}
		
			
		$amount = $sessions->amount;

		// factor in the discount but ask boss first
		// error_log($settings->business->subamount);
		$amount = $amount * 100;  //the amount in kobo. This value is actually 

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode([
		    'amount'=>$amount,
		    'email'=>$email,
		  ]),
		  CURLOPT_HTTPHEADER => [
		    "authorization: Bearer ".$paymentConfig->paystack["test_sk"], //replace this with your own test key  
		    "content-type: application/json",
		    "cache-control: no-cache"
		  ],
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		if(!empty($err) and $err){
		  	// there was an error contacting the Paystack API
		  	error_log('Curl returned error: ' . $err);
			$fail.='<p>'.'Curl returned error: ' . $err.'</p>';
		}else{
			$tranx = json_decode($response, true);
			if(!$tranx["status"]){
			  // there was an error from the API
			  // print_r('API returned error: ' . $tranx['message']);
			  $fail.='<p>'.'API returned error: ' . $tranx['message'].'</p>';
			}
			
			$discount = $property->discount;
			$Site["session"]['ptoken']=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `payments` ORDER BY `id` DESC LIMIT 1");
			$Site["session"]['regToken']=$token;
			//$Site["session"]['name']=$subscription->firstname;
			$amount = $amount / 100;
			
			$Site["session"]['amount']=$amount;
			$$Site["session"]['discount']=$discount;
			$_SESSION=$Site["session"];
			$sessions= (object)$Site["session"];

			$ezDb->query("UPDATE `payments` SET `amount`='$amount' WHERE `token`='$sessions->token' AND `transid1` = '$sessions->transid';");

			header('Location:'.$tranx['data']['authorization_url']);
			//header('receipt');
			
		}
	}
}

//$table = ($sessions->purpose=='investment') ? 'investment_subscription' : 'subscription';
if (!empty($sessions->token)) {
	if($sessions->purpose=='investment'){
		$tag=$sessions->token;
		$investment=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$tag' ");
		$investment->detail = $ezDb->get_row("SELECT * FROM `investments` WHERE `id` = '$investment->investments_id' ");
		$smarty->assign("investment", $investment)->assign("sessions", $sessions);
	}else{
		$tag=$sessions->token;
		$subscription=$ezDb->get_row("SELECT * FROM `$sessions->table_name` WHERE `token` = '$tag' ");
		$smarty->assign("subscription", $subscription)->assign("sessions", $sessions);
	}
}else{
    //echo 'empty: not found';
}

