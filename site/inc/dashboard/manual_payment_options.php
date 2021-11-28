<?php 

$table=(!empty($gets->type)? $gets->type: "");
$posts = (object) $Site["post"];
if (!empty($sessions->token) && in_array($sitePage, array("manual_payment_options")) && ($requestMethod == 'POST') && !empty($posts->triggers) && $posts->triggers=='payment_option') {
	print_r($posts);
	$err=0;
	if (empty($posts->amount)):
    	$fail = '<p>Please select a Payment Plan</p>';
        $err++;
	endif;
	if (empty($posts->payment_duration)):
    	$fail = '<p>Please select a Payment Duration</p>';
        $err++;
	endif;
	
	if($err==0):
		$query = "UPDATE `$table` SET `status` = 1, `initial_percent` = '$posts->payment_option', `duration` = '$posts->payment_duration' WHERE `token` ='$sessions->token'";
	   if($ezDb->query($query)):
		$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Subscription Successful </p> <p> Payment Plan Saved </p></div>';
		
		alertUser($userinfo->email,0,"Subscription successful, Payment plan saved");
		unset($Site["session"]['email']);
		unset($Site["session"]['username']);
		unset($Site["session"]['purpose']);
		unset($Site["session"]['token']);
		$_SESSION=$Site["session"];
		
		$sessions= (object)$Site["session"];
		redirect('thank-you');
		else:
		$$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error Messages : '.$fail.'</p></div>';
	endif;
	$smarty->assign("fail", $fail);
}
if (!empty($sessions->token)) {

    $tag=$sessions->token;
    
    $subscription=$ezDb->get_row("SELECT * FROM `$table` WHERE `token` = '$tag' ");
		
	$smarty->assign("subscription", $subscription);
	
}

