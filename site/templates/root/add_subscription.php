<?php 
$posts = (object) $Site["post"];
$files= (object) $Site["files"];
	
$deptId = depttodepid("Marketing");
$employees=$ezDb->get_results("SELECT u.lastname as surname, u.firstname as firstname, u.email as email, u.refid as refid FROM `userprofile` AS u LEFT JOIN `employees` AS e on u.email = e.email LEFT JOIN `department` as d on d.id = e.department_id  WHERE u.active = 1 AND u.usertype = 'admin' OR (u.usertype = 'client' AND u.userrole = 'level4') ORDER BY FIELD(d.id,$deptId,1,3,4,5);");
$employees = json_encode($employees);

$properties=$ezDb->get_results("SELECT *  FROM `projects` where `active` = 1");
$smarty->assign("properties", $properties)->assign("employees", $employees);

if(!empty($posts->triggers) and $posts->triggers=='getProject'){
	$proj_name = $posts->project;
	$property=$ezDb->get_row("SELECT *  FROM `projects` WHERE `token` = '$proj_name'");
	$smarty->assign("project_item", $property);
	
}

//redirect("home");
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;

$fail="";
$err=0;

if(!empty($posts->triggers) and ($posts->triggers=='subscribeProduct' or $posts->triggers=='signup')):
	$targetDir2="site/media/receipts/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
	endif;
	$targetDir="site/media/clients/";

	if(!empty(trim($posts->employeeid))):
		$employee = $ezDb->get_row("SELECT * FROM `employees` WHERE `employeeid` = '$posts->employeeid'");
		$employee->datehired = $ezDb->get_var("SELECT `datehired` FROM `userprofile` WHERE `refid` = '$posts->employeeid'");
		$posts->refname = $employee->surname.' '.$employee->first_name;
		$posts->refnumber = $employee->phone;
		$posts->refemail = $employee->email;
	endif;
	
	if($err==0):
		

		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
		
		if (!file_exists($targetDir)):
		    mkdir($targetDir, 0777, true);
		endif;
		$fileName=$_FILES["uploadfile"]["name"];
		$tempName = $_FILES["uploadfile"]["name"];  
      
		$newName = "file".$_FILES['uploadfile']['name'];

		$folder = $targetDir .$token. $newName; 
		
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $folder)):
/*  */		$token = $token=getToken(8);
			$payment_status = ($posts->amountpaid >= $posts->totalpay) ? 1 : 0;
				$query1 = "INSERT INTO `subscription`(
				`token`,
				`project_token`,
				`product`,
				`img_filename`, 
				`title`,
				`surname`, 
				`middlename`, 
				`firstname`, 
				`sex`, 
				`marital_status`, 
				`spouse_surname`, 
				`spouse_firstname`, 
				`mobile`, 
				`phone`, 
				`email`,
				`address`, 
				`city`, 
				`lga`, 
				`state`, 
				`country`, 
				`dob`, 
				`id_mode`, 
				`id_mode_others`, 
				`id_number`, 
				`nationality`, 
				`state_of_origin`, 
				`occupation`, 
				`emp_name`, 
				`emp_address`,
				`nok_surname`,
				`nok_middlename`, 
				`nok_firstname`, 
				`nok_phone`, 
				`nok_address`, 
				`plot_type`, 
				`plot_number`, 
				`plot_sqm`, 
				`ref_fullname`, 
				`ref_phone`, 
				`ref_email`,
				`payment_type`,
				`total_amount`,
				`total_paid`,
				`paid`,
				`reg_date`
				) VALUES (
				'$token',
				'$posts->project_token',
				'$posts->project',
				'$folder',
				'$posts->title',
				'$posts->surname',
				'$posts->middlename',
				'$posts->firstname',
				'$posts->gender',
				'$posts->marital',
				'$posts->spousesurname',
				'$posts->spousefirstname',
				'$posts->number',
				'$posts->number1',
				'$posts->email',
				'$posts->address',
				'$posts->city',
				'$posts->lga',
				'$posts->state',
				'$posts->country',
				'$posts->dob',
				'$posts->idmode',
				'$posts->otheridmode',
				'$posts->idno',
				'$posts->nationality',
				'$posts->stateoforigin',
				'$posts->occupation',
				'$posts->empname',
				'$posts->empaddress',
				'$posts->noksurname',
				'$posts->nokmiddlename',
				'$posts->nokfirstname',
				'$posts->noknumber',
				'$posts->nokaddress',
				'$posts->landuse',
				'$posts->numplots',
				'$posts->plotsqm',
				'$posts->refname',
				'$posts->refnumber',
				'$posts->refemail',
				'laterpay',
				'$posts->totalpay',
				'$posts->amountpaid',
				'$payment_status',
				'$posts->transdate');";

			if($ezDb->query($query1)):	
				$product=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$posts->project_token'");
				//logTransaction($token,$posts->email,$posts->totalpay,'subscription','Property Subscription', 0);
				$invoiceid = getToken(8);
				$default_fee = 0.01 * $posts->tamount;
				$dateYear = date('Y', strtotime($posts->transdate));
				$dateMonth = date('M', strtotime($posts->transdate));
				$addate  = $ezDb->get_var("SELECT DATE_ADD('$posts->transdate', INTERVAL 12 MONTH);");
				$addate  = $ezDb->get_var("SELECT DATE_ADD('$addate', INTERVAL -12 DAY);");
				$duedate = $addate;
				$amt_survey = ($posts->numplots>=1) ? $posts->survey / $posts->numplots : $posts->survey;
				//date("Y-m-d", strtotime(date("Y-m-d", strtotime($posts->transdate)) . " + 1 year"));
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`,`date_added`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','$product->name Plot $posts->plotsqm SQM','$dateYear','$dateMonth','$posts->amount','$posts->numplots','$product->discount','$posts->tamount','$duedate','$posts->transdate','$posts->transdate')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`,`date_added`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Survey Selling Price','$dateYear','$dateMonth','$amt_survey','$posts->numplots','$product->discount','$posts->survey','$duedate','$posts->transdate','$posts->transdate')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`,`date_added`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Legal Documentaition Selling Price','$dateYear','$dateMonth','$posts->legal','1',0,'$posts->legal','$duedate','$posts->transdate','$posts->transdate')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`,`date_added`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Defaut Fee Selling Price','$dateYear','$dateMonth','$default_fee','1',0,'$default_fee','$duedate','$posts->transdate','$posts->transdate')");
				

				$result=$ezDb->get_row("SELECT *  FROM `userprofile` WHERE `email`='$posts->email'");
				
				if(!$result):
					$newemail = $posts->email;
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
						'".strtolower($posts->email)."', 
						'$posts->title', 
						'$posts->firstname', 
						'$posts->surname', 
						'$posts->middlename', 
						'$posts->gender', 
						'".base64_encode("password")."', 
						'".strtolower($posts->firstname)."', 
						'$posts->number',
						'$posts->address', 
						'client', 
						'client', 
						'level2', 
						'1');";
						
						if($ezDb->query($query2)):
						else:
							$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
						endif;

				endif;	
				$transid= getToken(8);
				if ( !file_exists("$targetDir2") ) :
					mkdir("$targetDir2", 0777, true);
				endif;
				$extn=end(explode(".", $files->file_upload['name']));
				$targetFile = $targetDir2.getToken(4)."payment_proof.".$extn;
				if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
				else:
					$targetFile="";
				endif;
			
				$newToken = getToken(8);
						$queryy = "INSERT INTO `payments` (
							`token`,
							`transid1`,
							`transdate`, 
							`gateway`, 
							`paidby`, 
							`amount`,
							`proof`,
							`status`,
							`status1`, 
							`purpose`
							) VALUES (
								'$token',
								'$newToken',
								'$posts->transdate',
								'cash',
								'$posts->email',
								'$posts->amountpaid',
								'$targetFile',
								1,
								'success',
								'$posts->purpose');";

						   if($ezDb->query($queryy)):
				
							$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You subscription form has been successfully submitted.</p></div>';
								
						   else:
							$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
			
							endif;

			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
			endif;
			
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to upload picture. kindly check</p></div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;

endif;


$genPassword=getToken(8);
$smarty->assign("fail",$fail)->assign('genPassword', $genPassword);