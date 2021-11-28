<?php 

$employee=$ezDb->get_row("SELECT * from `employees`  WHERE `employeeid` = '$userinfo->employeeid'");
$employee->dept = idtodepartment($employee->employeeid);
$employee->name = ucwords($employee->first_name.' '.$employee->surname);
$smarty->assign("employee", $employee);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;

if ( in_array($sitePage, array("add_variance_report")) && ($requestMethod == 'POST') && isset($Site["post"]['add_report']) ) {
	$targetDir="site/media/variance_reports/";
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->emp_name)) ):
		$err++;
		$fail.='<p>Enter Name please!</p>';
	endif;
	if( empty(trim($posts->department)) ):
		$posts->department = 'Administrative';
	endif;
	if( empty(trim($posts->pages)) ):
		$err++;
		$fail.='<p>Enter pages affected please!</p>';
	endif;
	if( empty(trim($posts->report)) ):
		$err++;
		$fail.='<p>Enter report description please!</p>';
	endif;
	if( empty(trim($posts->severity)) ):
		$err++;
		$fail.='<p>Choose severity please!</p>';
	endif;
	if( empty($posts->status)):
		$err++;
		$fail.='<p>Enter status please!</p>';
	endif;
	if( empty(trim($posts->note)) ):
		$posts->note = " ";
	endif;
	if(!in_array($userinfo->userrole, array('level0', 'level1','level3','level2','level4'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;
	
	$posts->note=cleanUp($posts->note);
	$posts->report=cleanUp($posts->report);
	
	if ($err==0) {
		if( !file_exists("$targetDir") ):
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $token= getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `variance_report` ORDER BY `id` DESC LIMIT 1;");
	    $targetFile = $targetDir . $token."_blog.png";
	    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
	    else:
	    	$targetFile="";
	    endif;
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `variance_report` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `variance_report`(`token`,`email`, `name`, `department`, `pages`,`image`, `report`, `severity`, `status`, `note`) VALUES 
											('$token','$userinfo->email','$posts->emp_name','$posts->department','$posts->pages','$targetFile','$posts->report','$posts->severity','$posts->status','$posts->note');";
		if($ezDb->query($query)){
			include_once "mail_variance_report.php";
			alertUser('admin@mail.com',0,'Variance report has been sent');
			alertUser($userinfo->email,0,'Variance report has been sent');
			$report=$ezDb->get_row("SELECT * FROM `variance_report` WHERE `token`='$token';");
    		logEvent($userinfo->email, "new-variance-report-added", $userinfo->usertype, 'variance_report', $report);
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to send variance report</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}