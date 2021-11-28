<?php 
if( (!in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) && !in_array($userinfo->department, array('Human Resources'))) ):
	redirect("home");
endif;

$zones = $ezDb->get_results("SELECT * FROM `id_zone`");
//$format = $menu->prefix;

$count = $ezDb->get_var("SELECT `value` FROM `counter`");
$newId = $count + 1;
$newForId = sprintf('%04d', $newId);
//$newEmpId = $format.$newForId;

$managers=$ezDb->get_results("SELECT u.firstname as firstname, u.lastname as lastname, u.email as email, e.employeeid as employeeid FROM `employees` AS e INNER JOIN `userprofile` as u ON e.email = u.email WHERE u.userrole != 'level4' AND u.usertype = 'admin' AND u.active = 1 ORDER BY e.department_id");
if(!empty($managers)){
	foreach($managers as $value){
		$value->department = idtodepartment($value->employeeid);
	}
}
$departments=$ezDb->get_results("SELECT * from `department`");


$countries=getCountries();
$states=getStates($Site['session']['User']['userinfo']->country);

if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='".$Site['session']['User']['userinfo']->state."'")) ){
	$state=$states[0]->name;
}
$cities=getCities($state);


if ( in_array($sitePage, array("add_employee")) && ($requestMethod == 'POST') && isset($Site["post"]['employee']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/employees/";

	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
	 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
	 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded file is not supported.</p>';
        $err++;
    endif;
	if(!empty($posts->email)){
		$posts->email = strtolower($posts->email);
	}
	$posts->email = strtolower($posts->email);
	if( empty(trim($posts->id)) ):
		$err++;
		$fail.='<p>Enter EmployeeID please!</p>';
	endif;
	if( empty(trim($posts->firstname)) ):
		$err++;
		$fail.='<p>Enter First Name please!</p>';
	endif;
		if( empty(trim($posts->surname)) ):
		$err++;
		$fail.='<p>Enter Surname please!</p>';
	endif;
	if( empty(trim($posts->sex)) ):
		$err++;
		$fail.='<p>Enter Male or Female please!</p>';
	endif;
	if( empty(trim($posts->marital)) ):
		$err++;
		$fail.='<p>Enter Marital Status please!</p>';
	endif;
	if( empty(trim($posts->dob)) ):
		$err++;
		$fail.='<p>Enter the Date of Birth please!</p>';
	endif;
	if( empty(trim($posts->nimn)) ):
		$err++;
		$fail.='<p>Enter The National ID Number please!</p>';
	endif;
	if( empty(trim($posts->address)) ):
		$err++;
		$fail.='<p>Enter the Address please!</p>';
	endif;
	if( !empty(trim($posts->address)) ):
		$posts->address = cleanUp( $posts->address );  
	endif;
	if( empty(trim($posts->city)) ):
		$err++;
		$fail.='<p>Enter the City please!</p>';
	endif;
	if( empty(trim($posts->country)) ):
		$err++;
		$fail.='<p>Enter the Country please!</p>';
	endif;
	if( empty(trim($posts->state)) ):
		$err++;
		$fail.='<p>Enter the State please!</p>';
	endif;
	if( empty(trim($posts->phone)) ):
		$err++;
		$fail.='<p>Enter the Phone please!</p>';
	endif;
	if( empty(trim($posts->email)) ):
		$err++;
		$fail.='<p>Enter the Email please!</p>';
	endif; 
	if( !empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$posts->email'") ) ):
		$err++;
		$fail.='<p>There is a user with the supplied email kindly change it!</p>';
	endif;

	if ($err==0) {
	    $token= getToken(5).$ezDb->get_var("SELECT IF(`employeeid`=NULL,'1',(`id`+1)) FROM `employees` ORDER BY `id` DESC LIMIT 1;");
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
		$extn = pathinfo($files->file_upload['name'], PATHINFO_EXTENSION);
	    $targetFile = $targetDir.$token."file.".$extn;
		if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif;
		/*id	reportid	project	title	client	clientemail	clientphone	location	user	comment	dateadded	status	lawyer_review	md_review attachment*/

		$query = "INSERT INTO `employees` (`location`,`employeeid`,`department_id`,`manager_id`, `title`, `first_name`,`middle_name`,`surname`,`sex`, `marital_status`,`dob`,`nimn`, `address`,`city`, `state`,`country`,`phone`,`other_phone`, `email`,`photo`) VALUES ('$posts->location','$posts->id', '$posts->department','$posts->manager','$posts->title', '$posts->firstname', '$posts->middlename', '$posts->surname', '$posts->sex', '$posts->marital', '$posts->dob', '$posts->nimn', '$posts->address', '$posts->city', '$posts->state','$posts->country', '$posts->phone', '$posts->other_phone', '$posts->email', '$targetFile' )";
		if($ezDb->query($query)):
			$ezDb->query("UPDATE `counter` SET `value` = '$newId'");
			$employees=$ezDb->get_row("SELECT * FROM `employees` WHERE `employeeid`='$posts->id';");
      		logEvent($userinfo->email, "new-employee-added", $userinfo->usertype, 'employees', $employees);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Employee was successfully added.</p></div>';
			sleep(3);
			redirect('create_login?id='.$employees->employeeid);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
$smarty->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities)->assign("fail",$fail);

$smarty->assign("departments", $departments)->assign("newEmpId", $newForId)->assign("managers", $managers)->assign("zones", $zones);