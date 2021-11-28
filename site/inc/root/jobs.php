<?php
if(!in_array($Site["session"]["User"]["userinfo"]->userrole, array("level0", "level1", "level3"))):
	redirect("./my-account/transactions");
endif;


if(!empty($posts->triggers) and $posts->triggers=='saveJob'):
	$targetDir="site/media/jobs/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg","image/tiff","image/webp","image/jp2");

	if( empty($posts->paymentMode) or !in_array($posts->paymentMode, array('h','d','w','m','q','a')) ):
		$fail='<p>Invalid Salary Expectation Selected: select a valid salary expectation</p>';
		$err++;
	endif;
	if( empty($posts->role)):
		$fail='<p>Invalid Job Role: job role cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->description)):
		$fail='<p>Invalid Job Description: job description cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->jobtitle)):
		$fail='<p>Invalid Job Title: job title cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->fullname)):
		$fail='<p>Invalid Rep Name: reprensentative\'s name cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->category)):
		$fail='<p>Invalid Job Category: job category cannot be empty</p>';
		$err++;
	endif;
	if( !checkEmail($posts->email) or empty($posts->email)):
		$fail='<p>Invalid Contact Details: enter email and phone number</p>';
		$err++;
	endif;
	// error_log($posts->content);
	if( empty($posts->location) or empty($posts->city)):
		$fail='<p>Invalid Job Request Location: location and city cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->compname) ):
		$fail='<p>Invalid Company Name: kindly enter compaty name</p>';
		$err++;
	endif;
	// if( empty($posts->salary) or $posts->salary)<0):
	// 	$fail='<p>Invalid Salary: salary must not be less that 0</p>';
	// 	$err++;
	// endif;
	if( !empty($_FILES['complogo']['tmp_name']) ):
		if (!in_array(strtolower(getMime($_FILES['complogo']['tmp_name'])), $extensions)):
	    	$fail .= '<p>Kindly scan and upload company`s logo</p><p>This is not an image file. Only JPG, JPEG, PNG, JPE, WEBP, or JP2 file is allowed.</p>';
	        $err++;
	    endif;
	endif;

	if($err==0):
		if (!file_exists($targetDir)):
		    mkdir($targetDir, 0777, true);
		endif;
		$token="job-".date("YmdHis").getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `jobs` ORDER BY `id` DESC LIMIT 1;");

		$newName = $token.'_.png';
    	$targetFile = $targetDir . $newName;
    	if ( ( empty($_FILES['complogo']['tmp_name']) and @copy("site/media/i/jobs-icon.png", $targetFile)) or move_uploaded_file($_FILES["complogo"]["tmp_name"], $targetFile) ):
    		$settings=getSettings();
			$settings->jobs=json_decode($settings->jobs);
    		$posts->description=testInput($posts->description);
			$posts->role=testInput($posts->role);
			$posts->location=testInput($posts->location);
			$posts->jobtitle=testInput($posts->jobtitle);
			$posts->category=testInput($posts->category);
			$posts->compname=testInput($posts->compname);
			$expiry=date('Y-m-d H:i:s', strtotime($posts->expirydate));
			//id token jobtitle	category company description role expirydate postedby addedby updatedby	logo email phone dateadded	dateupdated	salary	salarytype	payment	url	city location status
		    $ezDb->query("INSERT INTO `jobs` (`token`, `category`, `jobtitle`, `postedby`, `url`, `company`, `expirydate`, `email`, `logo`, `phone`, `location`, `city`, `description`, `role`, `salary`, `open`, `salarytype`, `addedby`, `dateadded`, `dateupdated`, `status`, `payment`) VALUES ('$token', '$posts->category', '$posts->jobtitle', '$posts->fullname', '$posts->joburl', '$posts->compname', '$expiry', '$posts->email', '$targetFile','$posts->phone', '$posts->location', '$posts->city', '$posts->description', '$posts->role', '$posts->salary', '1', '$posts->paymentMode', '$user', '$dateNow', '$dateNow', '1', 'adm');");

		    	$jobDetails=$ezDb->get_row("SELECT * FROM `jobs` WHERE `token`='$token';");

		    	$jobDetails->bizname= "<strong>$jobDetails->postedby</strong> (Product: $jobDetails->jobtitle)";
				$receiveremail=$jobDetails->email;
				$jobDetails->daterenewed=$jobDetails->dateadded;
				$jobDetails->paymentFor='Job Posting';
				
				$tranx=new stdClass;
				$tranx->data=new stdClass;
				$tranx->data->reference='adm-'.getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `payment` ORDER BY `id` DESC LIMIT 1;");
				$tranx->data->status='success';

				$ezDb->query("INSERT INTO `payment` (`transid`, `token`, `amount`, `transdate`, `paymenttype`, `paidby`, `paymentstatus`, `paymentduration`) VALUES ('".$tranx->data->reference."','$token','".$settings->jobs->subamount."','$dateNow','$jobDetails->payment','$jobDetails->email','".($tranx->data->status=='success'?'1':'0')."','1');");
	            $divElements=setDivElement($tranx, $jobDetails, 'SUBSCRIPTION RECEIPT', 'JOB POSTING SUBSCRIPTION');
	            $receivermail=$jobDetails->email;
	            $divElements1="TRANSACTION ID: ".$tranx->data->reference."\nPAID BY: $jobDetails->company\nCONTACT: $jobDetails->email | $jobDetails->phone\nPAYMENT FOR: JOB POSTING SUBSCRIPTION\nPAYMENT DATE: $jobDetails->daterenewed\nPAYMENT STATUS: ".$tranx->data->status."";
	            require_once'send_mail_to_clients.php';

				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>Job Details Successfully Posted</p></div>'; //remember to send email to client
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to upload company`s logo.</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;


if(!empty($posts->triggers) and $posts->triggers=='editJob'):
	$targetDir="site/media/jobs/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg","image/tiff","image/webp","image/jp2");

	if( empty($posts->paymentMode) or !in_array($posts->paymentMode, array('h','d','w','m','q','a')) ):
		$fail='<p>Invalid Salary Expectation Selected: select a valid salary expectation</p>';
		$err++;
	endif;
	if( empty($posts->role)):
		$fail='<p>Invalid Job Role: job role cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->description)):
		$fail='<p>Invalid Job Description: job description cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->jobtitle)):
		$fail='<p>Invalid Job Title: job title cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->fullname)):
		$fail='<p>Invalid Rep Name: reprensentative\'s name cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->category)):
		$fail='<p>Invalid Job Category: job category cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->phone)  or !checkEmail($posts->email) or empty($posts->email)):
		$fail='<p>Invalid Contact Details: enter email and phone number</p>';
		$err++;
	endif;
	// error_log($posts->content);
	if( empty($posts->location) or empty($posts->city)):
		$fail='<p>Invalid Job Request Location: location and city cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->compname) ):
		$fail='<p>Invalid Company Name: kindly enter compaty name</p>';
		$err++;
	endif;
	// if( empty($posts->salary) or $posts->salary)<0):
	// 	$fail='<p>Invalid Salary: salary must not be less that 0</p>';
	// 	$err++;
	// endif;
	if( !empty($_FILES['complogo']['tmp_name']) ):
		if (!in_array(strtolower(getMime($_FILES['complogo']['tmp_name'])), $extensions)):
	    	$fail .= '<p>Kindly scan and upload company`s logo</p><p>This is not an image file. Only JPG, JPEG, PNG, JPE, WEBP, or JP2 file is allowed.</p>';
	        $err++;
	    endif;
	endif;
	if( empty($ezDb->get_var("SELECT `token` FROM `jobs` WHERE `token`='$posts->token';")) ):
		$fail .= '<p>Invalid Job Post ID</p><p>This job id cannot be found in the database</p>';
	    $err++;
	endif;

	if($err==0):
		if (!file_exists($targetDir)):
		    mkdir($targetDir, 0777, true);
		endif;
		$newName = $posts->token.'_.png';
    	$targetFile = $targetDir . $newName;
    	$moved=false;
    	if( !empty($_FILES['complogo']['tmp_name']) ):
    		if (file_exists($posts->prevjoblogo) and is_file($posts->prevjoblogo)):
    			unlink($posts->prevjoblogo);
    		endif;
    		$moved=move_uploaded_file($_FILES["complogo"]["tmp_name"], $targetFile);
    	else:
    		$targetFile=$posts->prevjoblogo;
    	endif;

    	if ( empty($_FILES['complogo']['tmp_name']) or $moved!=false ):
    		$settings=getSettings();
    		$posts->description=testInput($posts->description);
			$posts->role=testInput($posts->role);
			$posts->location=testInput($posts->location);
			$posts->jobtitle=testInput($posts->jobtitle);
			$posts->category=testInput($posts->category);
			$posts->compname=testInput($posts->compname);
			$expiry=date('Y-m-d H:i:s', strtotime($posts->expirydate));
			//id token jobtitle	category company description role expirydate postedby addedby updatedby	logo email phone dateadded	dateupdated	salary	salarytype	payment	url	city location status
		    if($ezDb->query("UPDATE `jobs` SET `category`='$posts->category', `jobtitle`='$posts->jobtitle', `postedby`='$posts->fullname', `url`='$posts->joburl', `company`='$posts->compname', `expirydate`='$expiry', `email`='$posts->email', `logo`='$targetFile', `phone`='$posts->phone', `location`='$posts->location', `city`='$posts->city', `description`='$posts->description', `role`='$posts->role', `salary`='$posts->salary', `open`='1', `salarytype`='$posts->paymentMode', `dateupdated`='$dateNow' WHERE `token`='$posts->token';")):

				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>Job Details Successfully Updated</p></div>'; 
				//remember to send email to client
			else:
				$fail='<div class="alert alert-info alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to update details, kindly contact developer.</p></div>';
			endif;
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to upload company`s logo.</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;

if(!empty($posts->triggers) and $posts->triggers=='deljob'):
	if( empty($posts->token) or empty($ezDb->get_var("SELECT `token` FROM `jobs` WHERE `token`='$posts->token';")) ):
		$fail='<p>Invalid Job Post ID</p><p>This job post id cannot be found in the database</p>';
		$err++;
	endif;
	if($err==0):
		if($ezDb->query("DELETE FROM `jobs` WHERE `token`='$posts->token';")){
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Messages</h3> <p>Job Details Successfully Deleted REF: \''.$posts->token.'\'</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> Unable to delete selected job detail, kindly contact developer</div>';
		}
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
elseif(!empty($posts->triggers) and $posts->triggers=='deactivatejob'):
	if( empty($posts->token) or empty($ezDb->get_var("SELECT `token` FROM `jobs` WHERE `token`='$posts->token' AND `status`='1';")) ):
		$fail='<p>Invalid Job Post ID</p><p>This job post id cannot be found in the database or is already in deactivation status</p>';
		$err++;
	endif;
	if($err==0):
		if($ezDb->query("UPDATE `jobs` SET `status`='0' WHERE `token`='$posts->token';")):
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Messages</h3> <p>Job Post Successfully Deactivated REF: \''.$posts->token.'\'</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> Unable to deactivate selected job detail, kindly contact developer</div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
elseif(!empty($posts->triggers) and $posts->triggers=='activatejob'):
	if( empty($posts->token) or empty($ezDb->get_var("SELECT `token` FROM `jobs` WHERE `token`='$posts->token' AND `status`='0';")) ):
		$fail='<p>Invalid Job Post ID</p><p>This job post id cannot be found in the database or is already in activation status</p>';
		$err++;
	endif;
	if($err==0):
		if($ezDb->query("UPDATE `jobs` SET `status`='1' WHERE `token`='$posts->token';")):
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Messages</h3> <p>Job Post Successfully Activated REF: \''.$posts->token.'\'</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> Unable to activate selected job detail, kindly contact developer</div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;


$whereClause='';

$jobs=$ezDb->get_results("SELECT * FROM `jobs` ORDER BY `id` DESC");
//print_r($jobs);
if(!empty($jobs) and is_array($jobs)){
	foreach ($jobs as $value) {
		$value->applicantDetails=$ezDb->get_results("SELECT `comment`, `userdetails`, `email` FROM `applicants` WHERE `token`='$value->token'");
		$value->expirydate=date('Y-m-d', strtotime($value->expirydate));
	}
}

// $number = 1234.56;
// setlocale(LC_MONETARY,"en_US");
// echo money_format("The price is %i", $number);

$payTypes= array('h'=> 'Hourly','d'=> 'Daily','w'=> 'Weekly','m'=> 'Monthly','q'=> 'Quarterly','a'=> 'Annually');
$smarty->assign("rjobs", $jobs)->assign('payTypes', $payTypes);