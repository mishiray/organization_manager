<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2')) && !in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;

$paymentid=(!empty($gets->id)? $gets->id: "");

$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `id`='$paymentid' ");

$smarty->assign("payment", $payment);

if ( in_array($sitePage, array("update_payment")) && ($requestMethod == 'POST') && isset($Site["post"]['update_payment']) ) {

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
	if (!empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
    endif;
	if(empty(trim($posts->paid_by)) ):
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
	if(empty(trim($posts->purpose)) ):
		$err++; 
		$fail.='<p>Enter payment purpose please!</p>';
	endif;
	if( empty(trim($posts->status)) ):
		$err++;
		$fail.='<p>Enter payment status please!</p>';
	endif;
	if ($err==0) {
		
		$pay=$ezDb->get_var("SELECT `proof` FROM `payments` WHERE `id`='$paymentid' ");

	    $token= getToken(8);
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
		$extn=end(explode(".", $files->file_upload['name']));
		$targetFile = $targetDir.$token."payment_proof.".$extn;
		if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile = $pay;
		endif;
		$status1 = '';
		if($posts->status == 1){
			$status1 = 'success';
		}else{
			$status1 = 'fail';
		}
		/*id	reportid	project	title	client	clientemail	clientphone	location	user	comment	dateadded	status	lawyer_review	md_review attachment*/
		$ezDb->query("UPDATE `payments` SET 
		`paidby` = '$posts->paid_by', 
		`amount` = '$posts->amount', 
		`purpose` ='$posts->purpose', 
		`transdate`= '$posts->transdate', 
		`gateway`='$posts->gateway', 
		`status`= '$posts->status', 
		`proof`= '$targetFile', 
		`discount` ='$posts->discount', 
		`status1`='$status1' 
		WHERE 
		`id` = '$paymentid';");
		$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `id`='$paymentid';");
      	logEvent($userinfo->email, "payment-updated", $userinfo->usertype, 'payment', $payment);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your payment was successfully updated.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
