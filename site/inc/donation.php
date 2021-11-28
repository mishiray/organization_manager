<?php 

if (in_array($sitePage, array("donation")) && isset($Site["post"]['donate'])) {
	$fail = "";
	$err = 0;
	/*$curl = curl_init();

	if($err == 0):
		if (!empty($sessions->regToken)) {
			$email = $posts->email;
			// factor in the discount but ask boss first
			$amount = $$posts->amount*100;  //the amount in kobo. This value is actually NGN 300

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode([
			    'amount' => $amount,
			    'email' => $email,
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
				// End Payshack Payment
			}

			if(!empty($fail)):
				$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.' </div>';
			endif;
		}
	endif;*/


	//Database Level
	$inputdata = (object) $Site["post"];
    $files= (object) $Site["files"];
    if( empty(trim($inputdata->firstname))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input your First Name please!</p>';
    endif;
    if( empty(trim($inputdata->lastname))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input your Last Name please!</p>';
    endif;
    if( empty(trim($inputdata->email))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input an email!</p>';
    endif;
    if( empty(trim($inputdata->mobile))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Please input your mobile number!</p>';
    endif;
    if( empty(trim($inputdata->address))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input an address!</p>';
    endif;
    if( empty(trim($inputdata->city))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">A city is required!</p>';
    endif;
    if( empty(trim($inputdata->state))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">A state is required!</p>';
    endif;
    if( empty(trim($inputdata->zipcode))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Your postal code is required!</p>';
    endif;
    if( empty(trim($inputdata->amount))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Additional contact is required!p>';
	endif;
	$token= getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `donation` ORDER BY `id` DESC LIMIT 1;");
    if( $err==0 ){
        $inputdata->firstname=testInput($inputdata->firstname);
        $inputdata->lastname=testInput($inputdata->lastname);
        $inputdata->email=testInput($inputdata->email);
        $inputdata->mobile=testInput($inputdata->mobile);
        $inputdata->address=testInput($inputdata->address);
        $inputdata->city=testInput($inputdata->city);
        $inputdata->state=testInput($inputdata->state);
        $inputdata->zipcode=testInput($inputdata->zipcode);
		$inputdata->amount=testInput($inputdata->amount);
		
		$fail = '<div class="alert alert-success text-justify">
					<i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3>Success Messages</h3><p>Donation successfully made!</p>
				</div>';
        $ezDb->query("INSERT INTO `donation` (`token`, `firstname`, `lastname`, `email`, `mobile`, `address`, `city`, `state`, `zipcode`, `amount`) VALUES ('$token', '$inputdata->firstname', '$inputdata->lastname', '$inputdata->email', '$inputdata->mobile', '$inputdata->address', ' $inputdata->city', '$inputdata->state', '$inputdata->zipcode', '$inputdata->amount')");
    }else{
    	$fail = '<div class="alert alert-danger text-justify">
					<i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3>Error Messages</h3><div class="alert alert-danger alert-dismissible" role="alert">'.$fail.'</div>
				</div>';
    }
    echo ($fail);
    exit();
}

// Donors Listing
$whereClause="";
$donations=tableRoutine("donation", '*' , " `id`!=0 $whereClause", '`id`');
$smarty->assign("donations", $donations);

	