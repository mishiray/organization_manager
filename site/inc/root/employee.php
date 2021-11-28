<?php
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2')) && $userinfo->department != 'Human Resources' ):
	redirect("home");
endif;




$fail="";
$err=0;

$empid=(!empty($gets->id)? $gets->id: "");

$employee=$ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$empid';");
$department=$ezDb->get_results("SELECT * from  `department`;");

if(!empty($employee)){
	
	$staff_log=$ezDb->get_results("SELECT * FROM `staff_log` WHERE `email` = '$employee->email';");
	if(!empty($staff_log)){
        foreach($staff_log as $value){
            $value->addedbyName = $ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$value->addedby'");
        }
    }
	$countries=getCountries();
	$states=getStates($country=$employee->country);
	if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='$employee->state'")) ){
		$state=$states[0]->name;
	}
	$cities=getCities($state);

	
    if( in_array($sitePage, array("employee")) && ($requestMethod == 'POST') && $posts->triggers == 'update_log'){
        $fail="";
        $err=0;
        if( empty(trim($posts->content))):
            $err++;
            $fail.='<p>Kindly enter content</p>';
        endif;

	    if( $err==0 ):
            $token=getToken("5") .($ezDb->get_var("SELECT IFNULL((`id`+1),'1') FROM `client_log` ORDER BY `id` DESC LIMIT 1;"));
	        $query = "INSERT INTO `staff_log` (`token`,`addedby`,`email`,`content`) VALUES ('$token','$userinfo->email','$employee->email','$posts->content')";
            if($ezDb->query($query)){
                $staff_log=$ezDb->get_results("SELECT * FROM `staff_log` WHERE `email` = '$employee->email';");
                logEvent($userinfo->email, "updated-staff-log", $userinfo->usertype, 'client_log', $posts);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Log was successfully updated	.</p></div>';
			}
        endif;
    }

	if (!empty($posts->triggers) and $posts->triggers=='edit_employee') {
		
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/employees/";

		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
		"text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
		'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
			$fail .= '<p>The uploaded file is not supported.</p>';
			$err++;
		endif;
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
		if( empty(trim($posts->address)) ):
			$err++;
			$fail.='<p>Enter the Address please!</p>';
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
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2')) && $userinfo->department != 'Human Resources' ):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
		/*if( empty(trim($posts->clientemail)) and checkEmail($posts->clientemail)):
			$err++;
			$fail.='<p>Enter client email please!</p>';
		endif;*/

		if ($err==0) {

			$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
			if ( !file_exists("$targetDir") ) :
				mkdir("$targetDir", 0777, true);
			endif;
			$extn=end(explode(".", $files->img_upload['name']));
			$targetFile = $targetDir.$token.'file.'.$extn;
			if(!empty($_FILES["img_upload"]["name"])){
				if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $targetFile)) {

				}else{
					if($_FILES['img_upload']['error']==1){
						$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Image size is too large. max(2mb)</div>';
					}
				}
			}else{
				$targetFile = $employee->photo;
			}

			$query = "UPDATE `employees` SET `department_id` = '$posts->department', `title` = '$posts->title',
			`first_name` = '$posts->firstname',`middle_name` = '$posts->middlename',
			`surname` = '$posts->surname',`sex` = '$posts->sex', `manager_id` = '$posts->manager',
			`marital_status` = '$posts->marital',`dob` = '$posts->dob',
			`nimn` = '$posts->nimn',`address` = '$posts->address',
			`country` = '$posts->country', `city` = '$posts->city', `state` = '$posts->state',`phone` = '$posts->phone',
			`other_phone` = '$posts->other_phone',`email` = '$posts->email',
			`photoid` = '$posts->photoid',
			`photo` = '$targetFile'
			WHERE `employeeid` = '$empid'";
			
			$query2 = "UPDATE `userprofile` SET `userrole` = '$posts->userrole'
			WHERE `email` = '$posts->email'";
			$ezDb->query($query2);

			if($ezDb->query($query)):
				$employee = $ezDb->get_row("SELECT * FROM `employees` WHERE `employeeid`='$empid';");
				logEvent($userinfo->email, "updated-employee", $userinfo->usertype, 'employee', $employee);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Employee info was successfully updated</p></div>';
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;

		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$managers=$ezDb->get_results("SELECT u.firstname as firstname, u.lastname as lastname, u.email as email, e.employeeid as employeeid FROM `employees` AS e INNER JOIN `userprofile` as u ON e.email = u.email WHERE u.userrole != 'level4' AND u.usertype = 'admin' AND u.active = 1 ORDER BY e.department_id");
	
	if(!empty($managers)){
		foreach($managers as $value){
			$value->department = idtodepartment($value->employeeid);
		}
	}

	if($employee->manager_id){
		$manager = $ezDb->get_row("SELECT `first_name`, `surname` from `employees` WHERE `employeeid` = '$employee->manager_id'");
		$employee->manager = $manager->surname.' '.$manager->first_name;
	}
	
	$employee->department= idtodepartment($employee->employeeid);
	
	$employee->userProfile = $ezDb->get_row("SELECT * from  `userprofile` WHERE `email` = '$employee->email';");
	
	if(in_array($employee->userProfile->userrole, array('level0','level1', 'level2','level3'))){
		$employee->managed = $ezDb->get_results("SELECT * from  `employees` WHERE `manager_id` = '$employee->employeeid';");
		if(!empty($employee->managed)){
			foreach($employee->managed as $value){
				$value->department= idtodepartment($value->employeeid);
			}
		}
	}


	if (!empty($posts->triggers) and $posts->triggers=='delete_employee') {
		if ($err==0) {
			if($employee->userProfile){
				$ezDb->query("UPDATE `userprofile` SET `active` = 0 WHERE `userid`='$empid';");
			}
			$ezDb->query("UPDATE `employees` SET `status` = 0 WHERE `employeeid`='$empid';");

			logEvent($userinfo->email, "deleted-employee", $userinfo->usertype, 'employee', $employee);
				
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Employee was successfully deleted.</p></div>';
			$smarty->clear_assign("item");
		}
	}


	if (!empty($posts->triggers) and $posts->triggers=='activate') {
		$posts = (object) $Site["post"];

		$ezDb->query("UPDATE `userprofile` SET `active` = '$posts->active' WHERE `email`='$employee->email';");

		if($posts->active == 1){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Employee account has been activated.</p></div>';		
		}else{
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Employee account has been deactivated.</p></div>';		
		}
		$smarty->assign("fail", $fail);
		header('Location: employees');
	}

}else{
	redirect("employees");
}

$smarty->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities)->assign("staff_log",$staff_log)->assign("item", $employee)->assign("managers", $managers)->assign("departments", $department)->assign("urole", array("level0"=>"Super Admin", "level1"=>"BDM", "level2"=>"Manager","level3"=>"Supervisor", "level4"=>"Employee"));
