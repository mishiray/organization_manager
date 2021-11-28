<?php

$curl = curl_init();

if (!empty($sessions->regToken) and !empty($userinfo)) {
	
	$email = $email;
	// factor in the discount but ask boss first
	$amount = $amount*100;  //the amount in kobo. This value is actually NGN 300

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
	  // error_log('Curl returned error: ' . $err);
		$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">'.'Curl returned error: ' . $err.'</p>';
	}else{
		$tranx = json_decode($response, true);

		if(!$tranx->status){
		  // there was an error from the API
		  // print_r('API returned error: ' . $tranx['message']);
		  $fail.='<p class="border bg-white border-success px-2 py-1 rounded">'.'API returned error: ' . $tranx['message'].'</p>';
		}

		// comment out this line if you want to redirect the user to the payment page
		// print_r($tranx);
		// error_log(json_encode($tranx ));
		// Added by Me
		// End Added By me
		// redirect to page so User can pay
		// uncomment this line to allow the user redirect to the payment page
		header('Location: ' . $tranx['data']['authorization_url']);
		/*End Payshack Payment*/
	}
	if(!empty($fail)):
		$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.' </div>';
	endif;

}else{
	echo 'else statement';
}