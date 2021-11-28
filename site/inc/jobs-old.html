<?php

if(!empty($posts->triggers) and $posts->triggers=='applyJob'):
	$targetDir="site/media/applicants/";
	$extensions = array("application/pdf","application/rtf","application/vnd.ms-powerpoint","application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.openxmlformats-officedocument.presentationml.presentation");
	if( empty($posts->comment) ):
		$fail='<p>Invalid Summary: kindly enter summary</p>';
		$err++;
	endif;
	if( empty($posts->names)):
		$fail='<p>Invalid Name: kindly enter your name</p>';
		$err++;
	endif;
	if( empty($posts->email) or !checkEmail($posts->email)):
		$fail='<p>Invalid Email: kindly enter a valid email</p>';
		$err++;
	endif;
	if( empty($posts->phone)):
		$fail='<p>Invalid Email: kindly enter your phone number</p>';
		$err++;
	endif;
	if( empty($ezDb->get_var("SELECT `token` FROM `jobs` WHERE `token`='$posts->jobToken'"))):
		$fail='<p>Invalid Job: cannot identify this job</p>';
		$err++;
	endif;
	$mimeMail=getMime($_FILES['cv']['tmp_name']);
	// error_log($mimeMail);
	if( empty($_FILES['cv']['tmp_name']) or !in_array(strtolower($mimeMail), $extensions)):
    	$fail .= '<p>Invalid CV: Kindly scan and upload your cv</p><p>Supported cv format should be a MS Word or PDF document.</p>';
        $err++;
	endif;

	if($err==0):
		if (!file_exists($targetDir)):
		    mkdir($targetDir, 0777, true);
		endif;
		$fileCVName=$_FILES['cv']['name'];
		$newName = $posts->email."_".$posts->jobToken."_".$_FILES['cv']['name'];
		$targetFile = $targetDir . $newName;
		if (file_exists($targetFile)) {
			unlink($targetFile);
		}

		// $path_parts = pathinfo($_FILES["file"]["name"]); $extension = $path_parts['extension']

		$userDetail=new stdClass;
		$userDetail->names=testInput($posts->names);
		$userDetail->phone=testInput($posts->phone);
		$userDetail=json_encode($userDetail);
		$comments=testInput($posts->comment);
		if(move_uploaded_file($_FILES["cv"]["tmp_name"], $targetFile) ):
			$attachment = $_FILES["cv"]["tmp_name"];
			if($ezDb->get_var("SELECT `email` FROM `applicants` WHERE `token`='$posts->jobToken' AND `email`='$posts->email'")):
				$ezDb->query("UPDATE `applicants` SET `userdetails`='$userDetail', `comment`='$comments', `cv`='$targetFile', `dateadded`='$dateNow' WHERE `token`='$posts->jobToken' AND `email`='$posts->email'");
			else:
				$ezDb->query("INSERT INTO `applicants` (`email`, `userdetails`, `comment`, `token`, `cv`, `status`, `dateadded`) VALUES ('$posts->email', '$userDetail', '$comments', '$posts->jobToken', '$targetFile', '0', '$dateNow');");
			endif;
			$jobDetails=$ezDb->get_row("SELECT * FROM `jobs` WHERE `token`='$posts->jobToken'");
			//id token jobtitle	category company description role expirydate postedby addedby updatedby	logo email phone dateadded	dateupdated	salary	salarytype	payment	url	city location status
			require_once 'mail_job_poster.php';
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You application had been successfully submitted for review.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to upload CV. kindly re-apply</p></div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;

endif;

$whereClause='';

//$jobs=tableRoutine("jobs", '*', " `status`='1' $whereClause", '`id`');
$jobs = $ezDb->get_results("SELECT * from jobs");
if(!empty($jobs) and is_array($jobs)){
	foreach ($jobs as $value) {
		$value->applicantDetails=$ezDb->get_results("SELECT `comment`, `email` FROM `applicants` WHERE `token`='$value->token'");
	}
}
$payTypes= array('h' => 'Hourly', 'd' => 'Daily','w' => 'Weekly', 'm' => 'Monthly', 'q' => 'Quarterly', 'a' => 'Annually');
$smarty->assign("jobs", $jobs)->assign("payTypes", $payTypes);