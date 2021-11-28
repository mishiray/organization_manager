<?php

/*For Businesses*/
if (!empty($posts->triggers) and $posts->submit=='saveBusiness') {
	if (!empty($servers->HTTP_REFERER)) {
		$curl = curl_init();
		// $settings=getSettings();
		// $settings->business=json_decode($settings->business);

		$email = $posts->email;
		// factor in the discount but ask boss first
		// error_log($settings->business->subamount);
		$amount = 10000;  //the amount in kobo. This value is actually 

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode([
		    'amount'=>$amount,
		    'email'=>$email,
		  ]),
		  CURLOPT_HTTPHEADER => [
		    "authorization: Bearer pk_test_de2dd33aed79ae537562f4927a76db653a9357f8", //replace this with your own test key  sk_test_c0923d55c0fd8a5ae24e7a5910b07ccf7548297a
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

			// comment out this line if you want to redirect the user to the payment page
			// print_r($tranx);
			// error_log(json_encode($tranx ));
			// Added by Me
			// $ezDb->query("INSERT INTO `paystack_hits` (`data_intransit`,`token`) VALUES ('".json_encode($tranx)."','$sessions->bizToken');");
			// End Added By me
			// redirect to page so User can pay
			// uncomment this line to allow the user redirect to the payment page
			// header('Location: ' . $tranx['data']['authorization_url']);
			/*End Payshack Payment*/
		}
	}
}