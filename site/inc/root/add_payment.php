<?php 
$subscriptions = $ezDb->get_results("SELECT * FROM `subscription` ORDER BY `paid`,`reg_id` DESC");
$smarty->assign("subscriptions", $subscriptions);
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4'))  && !in_array($userinfo->department, array('Accounting','Administrative'))):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_payment")) && ($requestMethod == 'POST') && isset($Site["post"]['payment']) ) {

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
	if(empty(trim($posts->subs_token)) ):
		$token= getToken(8);
	else: 
		$token= $posts->subs_token;
	endif;
	if( empty(trim($posts->paid_by)) ):
		$err++;
		$fail.='<p>Enter Paid by please!</p>';
	endif;
	if( empty(trim($posts->amount)) ):
		$err++;
		$fail.='<p>Enter amount paid please!</p>';
	endif;
	if( empty(trim($posts->gateway)) ):
		$err++;
		$fail.='<p>Enter payment gateway please!</p>';
	endif;
	if(!empty($_POST['purpose'])):
		foreach($_POST['purpose']  as $selected){
			$purpose .= $selected." ";
		} 
	else:
		$err++; 
		$fail.='<p>Enter payment purpose please!</p>';
	endif;
	if( empty(trim($posts->status)) ):
		$err++;
		$fail.='<p>Enter payment status please!</p>';
	endif;
	if ($err==0) {
	    $transid= getToken(8);
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $extn=end(explode(".", $files->file_upload['name']));
	    $targetFile = $targetDir.getToken(4)."payment_proof.".$extn;
		if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif;
		$status1 = '';
		if($posts->status == 1){
			$status1 = 'success';
		}else{
			$status1 = 'fail';
		}
		if(empty($posts->discount)){
			$posts->discount = 0;
		}
		$query = "INSERT INTO `payments` (`transid1`, `token`, `paidby`, `amount`, `purpose`, `transdate`, `gateway`, `status`, `proof`, `discount`,`status1`) VALUES ('$transid','$token', '$posts->paid_by', '$posts->amount', '$purpose', '$posts->transdate', '$posts->gateway','$posts->status', '$targetFile', '$posts->discount', '$status1')";
		if($ezDb->query($query)){	
			logTransaction($token,$posts->paid_by,$posts->amount,'payments',$purpose, $posts->status);
			logFinance($token,$posts->amount,'cash in bank','paystack','Atobe CC','income','income','sales','property subscription',$dateNow,1);
			
			//update tables
			$subItem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$token'");
			if(!empty($subItem) && $posts->status == 1){
				//echo 'im in';
				$totalsold=$ezDb->get_var("SELECT `totalsold` FROM `projects` WHERE `token` = '$subItem->project_token'");
				$new_totalpaid = $subItem->total_paid + $posts->amount;
				$newTotalSold = $totalsold + $subItem->plot_number;
				
				$ezDb->query("UPDATE `subscription` SET `total_paid`='$new_totalpaid'  WHERE `token`='$subItem->token'");
				$ezDb->query("UPDATE `projects` SET `totalsold`='$newTotalSold' WHERE `token` = '$subItem->project_token'");
				
				$subscription=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$subItem->token' ");
				
				if($subscription->total_paid>=$subscription->total_amount){
					$ezDb->query("UPDATE `subscription` SET `paid`= 1, `status` = 5  WHERE `token`='$subscription->token';");
					$ezDb->query("UPDATE `commission_payroll` SET `status` = 1 WHERE `subscription_token` = '$subscription->token';");
					alertUser($subscription->email,0,'You have completed your payment for '.$subscription->product.' subscription');
				}
	
				//commission issue
				if (!empty(trim($subscription->ref_email))){
					$employee = $ezDb->get_row("SELECT * FROM `userprofile` WHERE `email` = '$subscription->ref_email'");
					$commission_line = getAMGLine($employee->userid);
					$posted_data = $subscription;
					$payrollid = getToken(8);
					$settings = getSettings();
					if(!empty($settings->amg_commission)){
						$data = $settings->amg_commission;
						$deductions = $settings->inv_commission;
	
						//commission lines
						$level0_commission =  $data->level0;
						$level1_commission =  $data->level1;
						$level2_commission =  $data->level2;
	
						$total_pay = ($level0_commission/100) * $posts->amount; 
						$pension = ($deductions->pension/100) * $total_pay;
						$new_bal = $total_pay - $pension; 
						$taxes = ($deductions->taxes/100) * $new_bal; 
						$final_bal = $new_bal - $taxes; 
						$commission_query_1 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$posted_data->ref_email','$token','$dateM','$dateY','','$level0_commission','$posts->amount','$total_pay','$pension','$taxes',1,'','')";
						$ezDb->query($commission_query_1);
							
						if(!empty($commission_line->level1)){
							$level1 = $commission_line->level1;
							$total_pay = ($level1_commission/100) * $posts->amount; 
							$pension = ($deductions->pension/100) * $total_pay;
							$new_bal = $total_pay - $pension; 
							$taxes = ($deductions->taxes/100) * $new_bal; 
							$final_bal = $new_bal - $taxes; 
							
							$commission_query_2 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$level1->email','$token','$dateM','$dateY','','$level1_commission','$posts->amount','$total_pay','$pension','$taxes',1,'','')";
							$ezDb->query($commission_query_2);
							
							if(!empty($commission_line->level2)){
	
								$level2 = $commission_line->level2;
								$total_pay = ($level2_commission/100) * $posts->amount; 
								$pension = ($deductions->pension/100) * $total_pay;
								$new_bal = $total_pay - $pension; 
								$taxes = ($deductions->taxes/100) * $new_bal; 
								$final_bal = $new_bal - $taxes; 
								
								$commission_query_3 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$level2->email','$token','$dateM','$dateY','','$level2_commission','$posts->amount','$total_pay','$pension','$taxes',1,'','')";
								$ezDb->query($commission_query_3);
							
							}
						}
					}
				}
			}
				$client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$subscription->email' OR `email`='$subscription->email' AND `usertype`='client';");
				if($client->verified == 0){
					$client->password = base64_decode($client->password);
					$new = false;
					$confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
					if(empty($ezDb->query("SELECT * FROM `keys` WHERE `email` = '$subscription->email'"))){
						$ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($subscription->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
						$new = true;	
					}else{
						$new = false;
						$ezDb->query("UPDATE `keys` SET `key` = '$confirmkey', `expiredon` = DATE_ADD('$dateNow', INTERVAL 2 DAY)  WHERE `email` = '".strtolower($subscription->email)."'");
					}
					$client->name = $client->lastname.' '.$client->firstname;
					require_once 'mail_login_detail.php';
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Client account has been activated.</p></div>';		
				}
			//Log Payment Data
			$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$token';");
			logEvent($userinfo->email, "new-payment-added", $userinfo->usertype, 'payment', $payment);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payment was successfully added.</p></div>';
		
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payment was not successfully added.</p></div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
