<?php
if (!empty($sessions->regToken) and !empty($userinfo)) {
	$errr=0;
	$curl = curl_init();
	$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
	if(!$reference){
	// die('No reference supplied');
	$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Error cannot verify payment: Invalid payment reference</p>';
	}else{
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				"accept: application/json",
				"authorization: Bearer ".$paymentConfig->paystack["test_sk"], //
				"cache-control: no-cache"
			],
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		if($err){
		  $errr++;
			// there was an error contacting the Paystack API
		  //die('Curl returned error: ' .$err );
		  $fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Curl returned error: '.$err.'</p>';
		}else{
			$tranx = json_decode($response);

			if(!$tranx->status){ 
				$errr++;
				// there was an error from the API
				// die('API returned error: ' .$tranx->message );
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">API returned error: '.$tranx->message.'</p>';
			}

			if('success' == $tranx->data->status){
				$transData=$tranx->data;
				$transData->amount=($transData->amount/100);
				$transData->status1=($tranx->data->status=='success'?'1':'0');

			    // transaction was successful...
			    // please check other things like whether you already gave value for this ref
			    // if the email matches the customer who owns the product etc
			    /*Update All necessary tables when done*/
			    //$ezDb->query("INSERT INTO `booking1` (`client`, `bookedBy`, `bookingtoken`, `ticketNo`, `bookingInfo`, `bookingDate`, `noOfPerson`, `totalFee`) VALUES ('$clientUn','guest','". $tempBookings->token."','$token','".json_encode($bookingInfo)."','$dateNow','".$tempBookings->search->passenger."','".$tempBookings->userdata->total."');");

			    // log Payment 	id	transid1	regid	transid	token	paidby	amount	purpose	program-	transdate	gateway	status	proof-	refund	discount	status1
			    $ezDb->query("INSERT INTO `payments` (`transid`, `transid1`, `token`, `purpose`, `amount`, `transdate`, `gateway`, `paidby`, `status1`, `status`, `discount`, `proof`) VALUES ('$sessions->regToken', '$transData->reference', '$sessions->ptoken', '$sessions->pslug', '$sessions->amount', '$dateNow', 'paystack', '$userinfo->email', '$transData->status', '$transData->status1', '$sessions->discount', '');");
			    $ezDb->query("UPDATE `userprofile` SET `active`=1 WHERE `email`='$userinfo->email';")
			    	// require_once 'enroll_paystack.php';
			    	// require_once 'enroll_paystack.php';

			    $fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p class="border bg-white border-success px-2 py-1 rounded">Payment for '.ucwords($sessions->purpose).' successfully renewed</p></div>';

			    $gets->id=$sessions->regToken;
			    unset($Site["session"]['regToken']);
			    unset($Site["session"]['amount']);
			    unset($Site["session"]['purpose']);
			    unset($Site["session"]['pslug']);
			    unset($Site["session"]['program']);
			    unset($Site["session"]['discount']);
			    $_SESSION=$Site["session"];
			    $sessions= (object)$Site["session"];
			}
		}
	}
	if($errr>0){
	  $fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	}
}
	