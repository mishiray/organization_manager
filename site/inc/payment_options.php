<?php 

$posts = (object) $Site["post"];
if (!empty($sessions->token) && in_array($sitePage, array("payment_options")) && ($requestMethod == 'POST') && !empty($posts->triggers) && $posts->triggers=='payment_option') {
	$err=0;
	if (empty($posts->amount)):
    	$fail = '<p>Please select a Payment Plan</p>';
        $err++;
	endif;
	
	if($err==0):
	$ezDb->query("UPDATE `$sessions->table_name` SET `status` = 1, `initial_percent` = '$posts->payment_option', `duration` = '$posts->payment_duration'  WHERE `token` ='$sessions->token' ");
	$query = "UPDATE `payments` SET `amount` = '$posts->amount' WHERE `token` = '$sessions->token' AND `transid1` = '$sessions->transid'";
	   if($ezDb->query($query)):
			$Site["session"]['amount']=$posts->amount;
			$_SESSION=$Site["session"];
			$sessions= (object)$Site["session"];

			header('Location: paystack');
	   else:
		$fail='<div class="alert alert-danger> Please Try again</div>';
		endif;
	else:
		$fail='<div class="alert alert-danger "><h3>Error Messages</h3> '.$fail.'</div>';
		
	endif;
}
if (!empty($sessions->token)) {

    $tag=$sessions->token;
    
    $subscription=$ezDb->get_row("SELECT * FROM `$sessions->table_name` WHERE `token` = '$tag' ");
		
	$smarty->assign("subscription", $subscription);
	
}else{
    echo 'Empty: not found';
}

