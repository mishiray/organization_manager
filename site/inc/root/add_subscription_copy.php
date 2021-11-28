<?php 
$posts = (object) $Site["post"];

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
	//redirect("home");
endif;

$fail="";
$err=0;

if(!empty($posts->triggers) and ($posts->triggers=='subscribeProduct' or $posts->triggers=='signup')):

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
				`amount`,
				`plot_sqm`, 
				`ref_fullname`, 
				`ref_phone`, 
				`ref_email`,
				`payment_type`,
				`total_amount`
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
				'$posts->amount',
				'$posts->plotsqm',
				'$posts->refname',
				'$posts->refnumber',
				'$posts->refemail',
				'$posts->paymenttype',
				'$posts->totalpay');";

			if($ezDb->query($query1)):	
				$product=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$posts->project_token'");
				logTransaction($token,$posts->email,$posts->totalpay,'subscription','Property Subscription', 0);
				$invoiceid = getToken(8);
				$default_fee = 0.01 * $posts->tamount;
				$addate  = $ezDb->get_var("SELECT DATE_ADD(NOW(), INTERVAL 12 MONTH);");
				$addate  = $ezDb->get_var("SELECT DATE_ADD('$addate', INTERVAL -12 DAY);");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','$product->name Plot $posts->plotsqm SQM','$dateY','$dateM','$posts->amount','$posts->numplots','$product->discount','$posts->tamount','$addate','$dateNow')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Survey Selling Price','$dateY','$dateM','$product->amt_survey','$posts->numplots','$product->discount','$posts->survey','$addate','$dateNow')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Legal Documentaition Selling Price','$dateY','$dateM','$posts->legal','1',0,'$posts->legal','$addate','$dateNow')");
				$ezDb->query("INSERT INTO `invoices`(`subscriptionid`, `invoiceid`, `name`, `item`, `year`, `month`, `price`, `quantity`, `discount`, `total`,`duedate`,`dateupdated`) VALUES	('$token','$invoiceid','$posts->surname $posts->firstname','Defaut Fee Selling Price','$dateY','$dateM','$default_fee','1',0,'$default_fee','$addate','$dateNow')");
				//commission
				//commission
				if($commission_ref){
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
				}
				
				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You subscription form has been successfully submitted.</p></div>';
				
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
						'".base64_encode($newpassword)."', 
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
				
				if($posts->triggers=='signup'):
					$Site["session"]['email']=$posts->email;
					$Site["session"]['username']=strtolower($posts->firstname);
					//$Site["session"]['amount']=$posts->totalpay;
					$Site["session"]['purpose']="subscription";
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
						   else:
							//echo 'error';
						   endif;

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