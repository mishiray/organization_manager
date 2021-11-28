<?php 
$posts = (object) $Site["post"];
$files= (object) $Site["files"];

$deptId = depttodepid("Marketing");

$investments=$ezDb->get_results("SELECT * from `investments`");
$smarty->assign("inv_properties", $investments);

$properties=$ezDb->get_results("SELECT *  FROM `projects` where `active` = 1");
$smarty->assign("properties", $properties);

$employees=$ezDb->get_results("SELECT u.lastname as surname, u.firstname as firstname, u.email as email, u.userid as refid FROM `userprofile` AS u LEFT JOIN `employees` AS e on u.email = e.email LEFT JOIN `department` as d on d.id = e.department_id  WHERE u.active = 1 AND u.usertype = 'admin' OR (u.usertype = 'client' AND u.userrole = 'level1') ORDER BY FIELD(d.id,$deptId,1,3,4,5);");
$employees = json_encode($employees);

$smarty->assign("employees", $employees);

if(!empty($posts->triggers) and $posts->triggers=='getProject'){
	$inv_id = $posts->inv_id;
	$property=$ezDb->get_row("SELECT *  FROM `investments` WHERE `id` = '$inv_id'");
	$smarty->assign("inv_item", $property);
	
}

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
	$commission_ref = false;
	
	if(!empty(trim($posts->employeeid))):
		$employee = $ezDb->get_row("SELECT * FROM `userprofile` WHERE `userid` = '$posts->employeeid'");
		if(empty($employee->datehired)){
			$employee->datehired = $employee->dateadded;
		}
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

		$extn=end(explode(".", $files->file_upload['name']));
	    $targetFile = $targetDir.$token."_inv.".$extn;
		$folder = $targetFile; 
		
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $folder)):
			$token = $token=getToken(8);
			$payment_status = ($posts->amountpaid >= $posts->totalpay) ? 1 : 0;
			
				$query1 = "INSERT INTO `investment_subscription`(
				`token`,
				`investments_id`,
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
				`ref_fullname`, 
				`ref_phone`, 
				`ref_email`,
				`payment_type`,
				`total_amount`,
				`percentage`, 
				`category`,
				`principal`,
				`duration`,
				`roi`,
				`maturity`,
				`total_paid`,
				`paid`,
				`status`,
				`reg_date`
				) VALUES (
				'$token',
				'$posts->investment_id',
				'$posts->investment_name',
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
				'$posts->refname',
				'$posts->refnumber',
				'$posts->refemail',
				'admin',
				'$posts->totalpay',
				'$posts->percentage',
				'$posts->sqm',
				'$posts->principal',
				'$posts->duration',
				'$posts->roi',
				'$posts->maturity',
				'$posts->amountpaid',
				'$payment_status',
				'$posts->investoptions',
				'$posts->transdate');";

			if($ezDb->query($query1)):	
				
				//$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You subscription form has been successfully submitted.</p></div>';
				
				logTransaction($token,$posts->email,$posts->totalpay,'investment_subscription','Property Investment', 1);

				$report=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token`='$token';");
				logEvent($userinfo->email, "new-investment-client-registered", $userinfo->usertype, 'investment_subscription', $report);
				
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
							`amount`, 
							`transdate`, 
							`gateway`, 
							`paidby`, 
							`proof`,
							`status`,
							`status1`, 
							`purpose`
							) VALUES (
								'$token',
								'".getToken(8)."',
								'$posts->amountpaid',
								'$posts->transdate',
								'cash',
								'$posts->email',
								'$targetFile',
								1,
								'success',
								'investment');";
						   
					if($ezDb->query($queryy)):
						
						$targetDir="site/media/client_docs/";
						$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
							 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
							 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
						if( empty(trim($posts->cert_title))):
								$err++;
								$fail.='<p>Kindly enter title!</p>';
						endif;
						if( empty(trim($posts->description)) ):
								$err++;
								$fail.='<p>Kindly enter description or content!</p>';
						endif;
						
						if ( $err==0 ) {
							if ( !file_exists("$targetDir") ) :
								mkdir("$targetDir", 0777, true);
							endif;
							$newArray=array();
						
							$previous=new stdClass(); 
							$errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
							
							foreach($_FILES['material_upload']['name'] as $key=>$val){ 
								// File upload path 
								$fileName = basename($_FILES['material_upload']['name'][$key]); 
								$targetFilePath = $targetDir . $fileName; 
								$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
									if(move_uploaded_file($_FILES["material_upload"]["tmp_name"][$key], $targetFilePath)){ 
										$insertValuesSQL .= "$targetFilePath,"; 
									}else{ 
										$errorUpload .= $_FILES['material_upload']['name'][$key].' | '; 
									}
							
							}
								$insertValuesSQL = trim($insertValuesSQL, ','); 
								$previous->description=$posts->description=testInput(str_replace("\n", "<br/>", $posts->description));
								$previous->title=$posts->title=testInput($posts->title);
								$previous->certurl=$targetFilePath;
							
								if($ezDb->query("INSERT INTO `certificate` (`token`,`user`, `title`, `description`, `certurl`, `datesent`, `dateupdated`, `updatedby`,`status`) VALUES ('$report->token','$posts->email', '$posts->title', '$posts->description', '$insertValuesSQL', '$dateNow', '$dateNow', '$userinfo->email',1)"))							{
									$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project was successfully created and submitted.</p></div>';
									logEvent($userinfo->email, "client-document", $userinfo->usertype, 'certificate', $previous);
								}else{
									$errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
									$errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
									$errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
									$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$errorMsg.'</p></div>';
								}
						}
						$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You investment form has been successfully submitted.</p></div>';
							
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