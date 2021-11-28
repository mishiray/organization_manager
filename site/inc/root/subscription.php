<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;

$subtoken=(!empty($gets->token)? $gets->token: "");

$subdata=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$subtoken' ");

$payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subtoken' ");

$smarty->assign("subdata", $subdata)->assign("payment", $payments);

if ( in_array($sitePage, array("subscription")) && ($requestMethod == 'POST') && isset($Site["post"]['payment']) ) {

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
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;
	/*if( empty(trim($posts->clientemail)) and checkEmail($posts->clientemail)):
		$err++;
		$fail.='<p>Enter client email please!</p>';
	endif;*/

	if ($err==0) {
	    $transid= getToken(16);
	    $token= $subtoken;
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
		/*id	reportid	project	title	client	clientemail	clientphone	location	user	comment	dateadded	status	lawyer_review	md_review attachment*/
		$ezDb->query("INSERT INTO `payments` (`transid1`, `token`, `paidby`, `amount`, `purpose`, `transdate`, `gateway`, `status`, `proof`, `discount`,`status1`) VALUES ('$transid','$token', '$posts->paid_by', '$posts->amount', '$purpose', '$posts->transdate', '$posts->gateway','$posts->status', '$targetFile', '$posts->discount', '$status1');");
		
		$ezDb->query("UPDATE `subscription` SET `paid` = 1 WHERE `token`='$subtoken';");
		//$subItem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->regToken'");
        $totalsold=$ezDb->get_var("SELECT `totalsold` FROM `projects` WHERE `name` = '$subdata->product'");
		
		$newTotalSold = $totalsold + $subdata->plot_number;
		$ezDb->query("UPDATE `projects` SET `totalsold`='$newTotalSold'  WHERE `name`='$subdata->product';");
		
		$result=$ezDb->get_row("SELECT *  FROM `userprofile` WHERE `email`='$subdata->email'");
				
				if(!$result):
					$newemail = $subdata->email;
					$newpassword = getToken(8);
					$newToken = getToken(32);
					$query2 = "INSERT INTO `userprofile` (
					`token`,
					`email`,
					`title`,
					`firstname`, 
					`lastname`,
					`middlename`,
					`gender`,
					`password`,
					`username`, 
					`phone`, 
					`address1`,
					`usertype`,
					`usercat`,
					`userrole`, 
					`verified`) 
					VALUES (
						'$newToken',
						'".strtolower($subdata->email)."', 
						'$subdata->title', 
						'$subdata->firstname', 
						'$subdata->surname', 
						'$subdata->middlename', 
						'$subdata->sex', 
						'".base64_encode($newpassword)."', 
						'".strtolower($subdata->firstname)."', 
						'$subdata->mobile',
						'$subdata->address', 
						'client', 
						'client', 
						'level1', 
						'1');";
						
						if($ezDb->query($query2)):
							$Site["session"]['email']=$posts->email;
							$Site["session"]['firstname']=strtolower($posts->firstname);
							$Site["session"]['username']=strtolower($subdata ->firstname);
							$Site["session"]['password']=$newpassword;
							$_SESSION=$Site["session"];
						
							$sessions= (object)$Site["session"];

							require_once 'mail_signup2.php';
						else:
							$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
						endif;
					
				endif;

		$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$token';");
      	logEvent($userinfo->email, "new-payment-added", $userinfo->usertype, 'payment', $payment);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payment was successfully added.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}