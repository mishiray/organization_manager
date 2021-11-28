<?php
$userinfo=$Site['session']['User']['userinfo'];
$fail="";
$err=0;
if ( in_array($sitePage, array("job-new")) && ($requestMethod == 'POST')):
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
		$fail='<p>Invalid Company Name: kindly enter company name</p>';
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
    		$posts->description=testInput($posts->description);
			$posts->role=testInput($posts->role);
			$posts->location=testInput($posts->location);
			$posts->jobtitle=testInput($posts->jobtitle);
			$posts->category=testInput($posts->category);
			$posts->compname=testInput($posts->compname);
			$expiry=date('Y-m-d H:i:s', strtotime($posts->expirydate));
			//id token jobtitle	category company description role expirydate postedby addedby updatedby	logo email phone dateadded	dateupdated	salary	salarytype	payment	url	city location status
		    if($ezDb->query("INSERT INTO `jobs` (`token`, `category`, `jobtitle`, `postedby`, `url`, `company`, `expirydate`, `email`, `logo`, `phone`, `location`, `city`, `description`, `role`, `salary`, `open`, `salarytype`, `addedby`, `dateadded`, `dateupdated`, `status`, `payment`) VALUES ('$token', '$posts->category', '$posts->jobtitle', '$posts->fullname', '$posts->joburl', '$posts->compname', '$expiry', '$posts->email', '$targetFile','$posts->phone', '$posts->location', '$posts->city', '$posts->description', '$posts->role', '$posts->salary', '1', '$posts->paymentMode', 'guest', '$dateNow', '$dateNow', '1', 'ps');")){
				
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You have successfully added new job</p></div>';
			}
			
			$Site["session"]['jobToken']=$token;
			$_SESSION=$Site["session"];
			$sessions= (object)$Site["session"];
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to upload company`s logo.</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;

$whereClause='';

$jobs=tableRoutine("jobs", '*', " `status`='1' $whereClause", '`id`');
$payTypes= array('h' => 'Hourly', 'd' => 'Daily','w' => 'Weekly', 'm' => 'Monthly', 'q' => 'Quarterly', 'a' => 'Annually');
$smarty->assign("jobs", $jobs)->assign("payTypes", $payTypes);