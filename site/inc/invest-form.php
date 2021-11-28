<?php

if ( !empty($gets->plan) ) {
	$mySearchFor=strtolower($gets->plan);
	$deptId = depttodepid("Marketing");
	$employees=$ezDb->get_results("SELECT u.lastname as surname, u.firstname as firstname, u.email as email, u.userid as refid FROM `userprofile` AS u LEFT JOIN `employees` AS e on u.email = e.email LEFT JOIN `department` as d on d.id = e.department_id  WHERE u.active = 1 AND u.usertype = 'admin' OR (u.usertype = 'client' AND u.userrole = 'level1') ORDER BY FIELD(d.id,$deptId,1,3,4,5);");
	$employees = json_encode($employees);

	$investments=$ezDb->get_results("SELECT * from `investments` WHERE `percentage` = '$mySearchFor' AND `active` = 1");
	$smarty->assign("inv_properties", $investments)->assign("employees", $employees)->assign("investment_percentage",$mySearchFor);
}else{
    $fail = 'Empty: not found';
}

$properties=$ezDb->get_results("SELECT *  FROM `projects` where `active` = 1");

if(!empty($posts->triggers) and $posts->triggers=='getProject'){
	$inv_id = $posts->inv_id;
	$sqm = $posts->sqm;
	$property=$ezDb->get_row("SELECT *  FROM `investments` WHERE `token` = '$inv_id'");
	$smarty->assign("inv_item", $property);
}

$fail="";
$err=0;

if(!empty($posts->triggers) and ($posts->triggers=='subscribeProduct' or $posts->triggers=='signup')):
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	$targetDir="site/media/clients/";
	$commission_ref = false;

	
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
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
		$extn = pathinfo($files->file_upload['name'], PATHINFO_EXTENSION);
	    //$extn=end(explode(".", $files->uploadfile['name']));
	    $targetFile = $targetDir.$token."file.".$extn;
		if ( !empty($files->uploadfile['tmp_name']) and @move_uploaded_file($files->uploadfile['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif; 
		
 				$token = $token=getToken(8);
				$query = "INSERT INTO `investment_subscription`(
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
				`maturity`
				) VALUES (
				'$token',
				'$posts->investment_id',
				'$posts->investment_name',
				'$targetFile',
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
				'$posts->paymenttype',
				'$posts->totalpay',
				'$posts->percentage',
				'$posts->sqm',
				'$posts->principal',
				'$posts->duration',
				'$posts->roi',
				'$posts->maturity'
				);";

			if($ezDb->query($query)):	
				//commission
				if($commission_ref){
					
					$investment = $ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$token'");
					$Site["session"]['commission_ref']=true;
					$Site["session"]['commission_employee'] = $employee;
					$Site["session"]['posted_data'] = $subscription;
					$_SESSION=$Site["session"];
					$sessions= (object)$Site["session"];

					/*
					$payrollid = getToken(8);
					$settings = getSettings();
					$month_difference = get_month_diff($employee->datehired,$dateNow);
					if(!empty($settings->inv_commission)){
						$data = $settings->inv_commission;
						$employee_commission =  $data->amount;
						$total_pay = ($employee_commission/100) * $posts->tamount;
						$commission_query = "INSERT INTO `commission_payroll` (`payrollid`, `employeeid`, `investment_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$posts->employeeid','$token','$dateM','$dateY','','$employee_commission','$posts->principal','$total_pay',0,'','')";
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
					`state`,
					`city`,
					`country`,
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
						'".base64_encode($newpassword)."', 
						'".strtolower($posts->firstname)."', 
						'$posts->number',
						'$posts->address', 
						'$posts->city', 
						'$posts->country', 
						'client', 
						'client', 
						'level2', 
						'1');";
						
						if($ezDb->query($query2)):
							$Site["session"]['email']=$posts->email;
							$Site["session"]['name']=strtolower($posts->firstname);
							$Site["session"]['username']=strtolower($posts->firstname);
							$Site["session"]['password']=$newpassword;
							$_SESSION=$Site["session"];
							$sessions= (object)$Site["session"];

						else:
							$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
						endif;

				endif;
				
				if($posts->triggers=='signup'):	
						$Site["session"]['email']=$posts->email;
						$Site["session"]['username']=strtolower($posts->firstname);
						$Site["session"]['amount']=$posts->totalpay;
						$Site["session"]['purpose']="investment";
						$Site["session"]['transid']= getToken(8);
						$Site["session"]['token']=$token;
						$_SESSION=$Site["session"];

						$sessions= (object)$Site["session"];

						$queryy = "INSERT INTO `payments` (
							`token`,
							`transid1`,
							`amount`, 
							`transdate`, 
							`gateway`, 
							`paidby`, 
							`status`,
							`status1`, 
							`purpose`
							) VALUES (
								'$sessions->token',
								'$sessions->transid',
								'$sessions->amount',
								'$dateNow',
								'paystack',
								'$sessions->email',
								0,
								'error',
								'$sessions->purpose');";
						   if($ezDb->query($queryy)):
								header('Location: paystack');
						   else:
							//echo 'error';
						   endif;
				endif;
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-apply</p></div>';
			endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;

endif;


$genPassword=getToken(8);
$smarty->assign("invest", $gets->plan)->assign("properties", $properties)->assign("fail",$fail)->assign('genPassword', $genPassword);