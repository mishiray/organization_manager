<?php 
$userinfo=$Site['session']['User']['userinfo'];
$fail="";
$err=0;
if ( in_array($sitePage, array("donation-new")) && ($requestMethod == 'POST')) {
	$inputdata = (object) $Site["post"];
	if( empty(trim($inputdata->firstname)) ):
        $err++;
        $fail.='<p class="border border-danger p-1 rounded">Your Firstname is required!</p>';
      endif;
      if( empty(trim($inputdata->lastname))):
        $err++;
        $fail.='<p class="border border-danger p-1 rounded">Your Lastname is required!</p>';
      endif;
      if( empty(trim($inputdata->email))):
        $err++;
        $fail.='<p class="border border-danger p-1 rounded">Input an email!</p>';
      endif;
      if( empty(trim($inputdata->mobile))):
        $err++;
        $fail.='<p class="border border-danger p-1 rounded">Please input your mobile number</p>';
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
        $fail.='<p class="border border-danger p-1 rounded">Your postal/zip code is required!</p>';
      endif;
      if( empty(trim($inputdata->amount))):
        $err++;
        $fail.='<p class="border border-danger p-1 rounded">Please choose an amount to donate!</p>';
      endif;
	if( $err==0 ):
	    $token= date("zYcCTa").$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `donation` ORDER BY `id` DESC LIMIT 1;");
	    $inputdata->firstname=testInput($inputdata->firstname);
        $inputdata->lastname=testInput($inputdata->lastname);
        $inputdata->email=testInput($inputdata->email);
        $inputdata->mobile=testInput($inputdata->mobile);
        $inputdata->address=testInput($inputdata->address);
        $inputdata->city=testInput($inputdata->city);
        $inputdata->state=testInput($inputdata->state);
        $inputdata->zipcode=testInput($inputdata->zipcode);
        $inputdata->amount = testInput($inputdata->amount);

        $ezDb->query("INSERT INTO `donation` (`token`, `firstname`, `lastname`, `email`, `mobile`, `address`, `city`, `state`, `zipcode`, `amount`) VALUES ('$token', '$inputdata->firstname', '$inputdata->lastname', '$inputdata->email', '$inputdata->mobile', '$inputdata->address', '$inputdata->city', '$inputdata->state', '$inputdata->zipcode', '$inputdata->amount')");

		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Donor has been successfully added.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
