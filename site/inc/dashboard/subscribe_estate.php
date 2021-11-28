<?php

$deptId = depttodepid("Marketing");
$employees=$ezDb->get_results("SELECT u.lastname as surname, u.firstname as firstname, u.email as email, u.userid as refid FROM `userprofile` AS u LEFT JOIN `employees` AS e on u.email = e.email LEFT JOIN `department` as d on d.id = e.department_id  WHERE u.active = 1 AND u.usertype = 'admin' OR (u.usertype = 'client' AND u.userrole = 'level1') ORDER BY FIELD(d.id,$deptId,1,3,4,5);");
$employees = json_encode($employees);
$admin_manager = $ezDb->get_var("SELECT e.employeeid FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id INNER JOIN `userprofile` as u on u.email = e.email where u.userrole = 'level2' AND d.name = 'Administrative' LIMIT 1");

$client=$ezDb->get_row("SELECT * FROM `subscription` WHERE `email` = '$userinfo->email' ORDER BY `reg_id` DESC LIMIT 1 ");
if(empty($client)){
	$client=$ezDb->get_row("SELECT * FROM `agric_subscription` WHERE `email` = '$userinfo->email' ORDER BY `reg_id` DESC LIMIT 1 ");
}if(empty($client)){
	$client=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `email` = '$userinfo->email' ORDER BY `reg_id` DESC LIMIT 1 ");
}
$properties=$ezDb->get_results("SELECT *  FROM `projects` where `active` = 1");
$smarty->assign("properties", $properties)->assign("employees", $employees)->assign("client", $client);

if(!empty($posts->triggers) and $posts->triggers=='getProject'){
	$proj_name = $posts->project;
	$property=$ezDb->get_row("SELECT *  FROM `projects` WHERE `token` = '$proj_name'");
	//echo "<script>document.getElementById('precheck').classList.add('d-none');</script>";
	$smarty->assign("project_item", $property);
	
}
 
if(!empty($posts->triggers) and ($posts->triggers=='subscribeProduct' or $posts->triggers=='signup')):

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	$targetDir="site/media/clients/";
	$commission_ref=false;

	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( !empty($files->uploadfile['tmp_name']) and !in_array(strtolower(getMime($files->uploadfile['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
    endif;
	if(!empty(trim($posts->employeeid))):
		$employee = $ezDb->get_row("SELECT * FROM `userprofile` WHERE `userid` = '$posts->employeeid'");
		if(empty($employee->datehired)){
			$employee->datehired = $employee->dateadded;
		}
		$posts->refname = $employee->lastname.' '.$employee->firstname;
		$posts->refnumber = $employee->phone;
		$posts->refemail = $employee->email;
		$commission_ref = true;
	endif;

	if($err==0):
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
		
		$token = getToken(8);

		$query = "INSERT INTO `subscription`(
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
				`amount`,
				`plot_sqm`, 
				`ref_fullname`, 
				`ref_phone`, 
				`ref_email`,
				`payment_type`,
				`total_amount`,
				`admin_signature`,
				`status`
				) VALUES (
				'$token',
				'$posts->project_token',
				'$posts->project',
				'$client->img_filename',
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
				'$posts->amount',
				'$posts->plotsqm',
				'$posts->refname',
				'$posts->refnumber',
				'$posts->refemail',
				'$posts->paymenttype',
				'$posts->totalpay',
				'$admin_manager',
				0);";

			if($ezDb->query($query)):	
				$product=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$posts->project_token'");
				$invoiceid = getToken(8);
				$default_fee = 0.01 * $posts->tamount;
				$addate  = $ezDb->get_var("SELECT DATE_ADD(NOW(), INTERVAL 12 MONTH);");
				$addate  = $ezDb->get_var("SELECT DATE_ADD('$addate', INTERVAL -12 DAY);");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','$product->name Plot $posts->plotsqm SQM','$dateY','$dateM','$posts->amount','$posts->numplots','$product->discount','$posts->tamount','$addate','$dateNow')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Survey Selling Price','$dateY','$dateM','$product->amt_survey','$posts->numplots','$product->discount','$posts->survey','$addate','$dateNow')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Legal Documentaition Selling Price','$dateY','$dateM','$posts->legal','1',0,'$posts->legal','$addate','$dateNow')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Defaut Fee Selling Price','$dateY','$dateM','$default_fee','1',0,'$default_fee','$addate','$dateNow')");
				//commission
				if($commission_ref){
					$subscription = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$token'");
					$Site["session"]['commission_ref']=true;
					$Site["session"]['commission_employee'] = $employee;
					$Site["session"]['posted_data'] = $subscription;
					$_SESSION=$Site["session"];
					$sessions= (object)$Site["session"];
					
					/*
					$payrollid = getToken(8);
					$settings = getSettings();
					$month_difference = get_month_diff($employee->datehired,$dateNow);
					if(!empty($settings->commission)){
						$data = $settings->commission;
						$manager_commission =  $data->manager;
						if($month_difference > $data->employee_period && $posts->plotsqm >  $data->commission_sqm){
							$employee_commission = $data->secondary;
						}else{
							$employee_commission = $data->initial;
						}
						
						$total_pay = ($employee_commission/100) * $posts->tamount;
						$commission_query = "INSERT INTO `commission_payroll` (`payrollid`, `employeeid`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$posts->employeeid','$token','$dateM','$dateY','','$employee_commission','$posts->tamount','$total_pay',0,'','')";
						$ref_role = getUserRole($posts->refemail);
						if($ref_role != 'level2'){
							$manager = $ezDb->get_var("SELECT `manager_id` FROM `employees` WHERE `employeeid`='$posts->employeeid'");
							if(!empty($manager)){
									$payrollid = getToken(8);
									$total_pay = ($manager_commission/100) * $total_pay;
									$manager_commission_query = "INSERT INTO `commission_payroll` (`payrollid`, `employeeid`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$manager','$token','$dateM','$dateY','','$manager_commission','$posts->tamount','$total_pay',0,'','')";
									$ezDb->query($manager_commission_query);
							}
							
						}
					}

					$ezDb->query($commission_query);
					*/
				}
				
				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Your subscription form has been successfully submitted.</p></div>';
				
				if($posts->triggers=='signup'):
						$Site["session"]['email']=$posts->email;
						$Site["session"]['username']=strtolower($posts->firstname);
						//$Site["session"]['amount']=$posts->totalpay;
						$Site["session"]['purpose']="subscription";
						$Site["session"]['table_name']="subscription";
						$Site["session"]['transid']= getToken(8);
						$Site["session"]['token']=$token;
						$_SESSION=$Site["session"];
						
						$sessions= (object)$Site["session"];

						$queryy = "INSERT INTO `payments` (
							`token`,
							`transid1`,
							`transdate`, 
							`gateway`, 
							`paidby`, 
							`status`,
							`status1`, 
							`purpose`
							) VALUES (
								'$sessions->token',
								'$sessions->transid',
								'$dateNow',
								'paystack',
								'$sessions->email',
								0,
								'error',
								'$sessions->purpose');";

						   if($ezDb->query($queryy)):
								header('Location: payment_options');
						   endif;
					else:						
						$Site["session"]['email']=$posts->email;
						$Site["session"]['username']=strtolower($posts->firstname);
						$Site["session"]['purpose']="subscription";
						$Site["session"]['token']=$token;
						$_SESSION=$Site["session"];
						
						$sessions= (object)$Site["session"];

						header('Location: manual_payment_options?type=subscription');
				endif;

			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
			endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;

endif;