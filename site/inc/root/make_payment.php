<?php 

$type=(!empty($gets->type)? $gets->type: "");
$subtoken=(!empty($gets->id)? $gets->id: "");
$smarty->assign("sett",$type);
$invdata = '';
$subdata = '';
if($type=='investment'){
	$subtoken=(!empty($gets->id)? $gets->id: "");
	$invdata=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token`='$subtoken' ");
	if(empty($invdata)){
		$fail = 'Not Found';
	}
	$smarty->assign("invdata", $invdata);
}elseif($type=='subscription'){
	$subtoken=(!empty($gets->id)? $gets->id: "");
	$subdata=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$subtoken' ");
	if(!empty($subdata)){
		$subdata->detail=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$subdata->project_token' ");
	}else{
		$fail = 'Not Found';
	}
	$smarty->assign("subdata", $subdata);

}else{
	$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';		
}
$payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subtoken' ");
$smarty->assign("payment", $payments);
	if ( in_array($sitePage, array("make_payment")) && ($requestMethod == 'POST') && isset($Site["post"]['investment_payment']) ) {
	
		$fail="";
		$purpose="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/receipts/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
		 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
		 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
			$fail .= '<p>The uploaded file is not supported.</p>';
			$err++;
		endif;
		if( empty(trim($posts->amount)) ):
			$err++;
			$fail.='<p>Enter amount paid please!</p>';
		endif;
		if(empty(trim($posts->purpose))):
			$err++; 
			$fail.='<p>Enter payment purpose please!</p>';
		endif;
	
		if ($err==0) {
			$transid= getToken(8);

			$investment = $ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$posts->token'");
	
			//commission issue
			if (!empty(trim($subscription->ref_email))){
				$employee = $ezDb->get_row("SELECT * FROM `userprofile` WHERE `email` = '$subscription->ref_email'");
				if(empty($employee->datehired)){
					$employee->datehired = $employee->dateadded;
				}
	
				$Site["session"]['commission_ref']=true;
				$Site["session"]['commission_employee'] = $employee;
				$Site["session"]['posted_data'] = $investment;
	
			}
	
			$Site["session"]['email']=$posts->paid_by;
			$Site["session"]['name']=strtolower($posts->firstname);
			$Site["session"]['amount']=$posts->amount;
			$Site["session"]['purpose']='investment';
			$Site["session"]['transid']= $transid;
			$Site["session"]['token']=$posts->token;
			//echo $sessions->token;
			$_SESSION=$Site["session"];
					
			$sessions= (object)$Site["session"];
			
			logTransaction($posts->token,$posts->paid_by,$posts->amount,'investment_subscription','Investment Subscription', 0);
			$ezDb->query("INSERT INTO `payments` (`transid1`, `token`, `paidby`, `amount`, `purpose`, `transdate`, `gateway`, `discount`) VALUES ('$transid','$posts->token', '$posts->paid_by', '0', '$sessions->purpose', '$dateNow', 'paystack', '$posts->discount');");
			
			$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$token';");
			logEvent($userinfo->email, "new-payment-added", $userinfo->usertype, 'payment', $payment);
			
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payment was successfully added.</p></div>';
			
			header('Location: paystack');
	
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}
	
	if ( in_array($sitePage, array("make_payment")) && ($requestMethod == 'POST') && isset($Site["post"]['subscription_payment']) ) {
		$fail="";
		$purpose="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/receipts/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
		 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
		 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
			$fail .= '<p>The uploaded file is not supported.</p>';
			$err++;
		endif;
		if( empty(trim($posts->amount)) ):
			$err++;
			$fail.='<p>Enter amount paid please!</p>';
		endif;
		if(!empty($_POST['purpose'])):
			foreach($_POST['purpose']  as $selected){
				$purpose .= $selected." ";
			} 
		else:
			$err++; 
			$fail.='<p>Enter payment purpose please!</p>';
		endif;
	
		if ($err==0) {
			$transid= getToken(8);

			$subscription = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$posts->token'");
			
				//commission issue
				if (!empty(trim($subscription->ref_email))){
					$employee = $ezDb->get_row("SELECT * FROM `userprofile` WHERE `email` = '$subscription->ref_email'");
					if(empty($employee->datehired)){
						$employee->datehired = $employee->dateadded;
					}
	
					$Site["session"]['commission_ref']=true;
					$Site["session"]['commission_employee'] = $employee;
					$Site["session"]['posted_data'] = $subscription;
	
				}
	
			$Site["session"]['email']=$posts->paid_by;
			$Site["session"]['name']=strtolower($posts->firstname);
			$Site["session"]['amount']=$posts->amount;
			$Site["session"]['purpose']= $purpose;
			$Site["session"]['transid']= $transid;
			$Site["session"]['token']=$posts->token;
			//echo $sessions->token;
			$_SESSION=$Site["session"];
					
			$sessions= (object)$Site["session"];
			
			$ezDb->query("INSERT INTO `payments` (`transid1`, `token`, `paidby`, `amount`, `purpose`, `transdate`, `gateway`, `discount`) VALUES ('$transid','$posts->token', '$posts->paid_by', '$sessions->amount', '$sessions->purpose', '$dateNow', 'paystack', '$posts->discount');");
			
			logTransaction($posts->token,$posts->paid_by,$posts->amount,'subscription','Subscription', 0);
			$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$token';");
			logEvent($userinfo->email, "new-payment-added", $userinfo->usertype, 'payment', $payment);

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payment was successfully added.</p></div>';
			
			//set session and proceed to paystack
			header('Location: paystack');
	
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}