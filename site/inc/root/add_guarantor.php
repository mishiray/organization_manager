<?php 

$userinfo=$Site['session']['User']['userinfo'];

$isadmin = 'yes';

if( !in_array( $userinfo->userrole, array('level0', 'level1')) ):	
    $isadmin = 'no';
endif;

$countries=getCountries();
$states=getStates($Site['session']['User']['userinfo']->country);

if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='".$Site['session']['User']['userinfo']->state."'")) ){
	$state=$states[0]->name;
}
$cities=getCities($state);

$employees=$ezDb->get_results("SELECT e.employeeid as empid , e.surname as surname, e.first_name as first_name from `employees` as e LEFT JOIN `payroll` as p on e.employeeid = p.employeeid WHERE p.employeeid IS NULL ");

$smarty->assign("employees", $employees)->assign("isadmin", $isadmin);

if ( in_array($sitePage, array("add_guarantor")) && ($requestMethod == 'POST') && isset($Site["post"]['add_guarantor']) ) {
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/guarantors/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
	if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
	endif;
	if(!empty($posts->email)){
		$posts->email = strtolower($posts->email);
	}
	if($userinfo->userrole!='level0'){
		$posts->employeeid = $ezDb->get_var("SELECT `employeeid` FROM `employees` WHERE `email` = '$userinfo->email'");
	}
	if( empty(trim($posts->employeeid)) ):
		$err++;
		$fail.='<p>Select an Employee please!</p>';
	endif;
	if( empty(trim($posts->firstname)) ):
		$err++;
		$fail.='<p>Enter First Name please!</p>';
	endif;
	if( empty(trim($posts->surname)) ):
		$err++;
		$fail.='<p>Enter Surname please!</p>';
	endif;
	if( empty(trim($posts->phone)) or !is_numeric($posts->phone)):
		$err++;
		$fail.='<p>Enter phone number please!</p>';
	endif;
	if( empty($ezDb->get_var("SELECT `name` FROM `countries` WHERE `name`='$posts->country'") ) ):
		$err++;
		$fail.='<p>Select a valid country please!</p>';
	endif;
	if( empty($ezDb->get_var("SELECT `st`.`name` FROM `countries` AS `cn`, `states` AS `st` WHERE `st`.`country_id`=`cn`.`id` AND `st`.`name`='$posts->state' AND `cn`.`name`='$posts->country' ") ) ):
		$err++;
		$fail.='<p>Select a valid state please!</p>';
	endif;
	if( empty(trim($posts->email)) ):
		$err++;
		$fail.='<p>Enter Email please!</p>';
	endif;
	if( empty(trim($posts->relationship)) ):
		$err++;
		$fail.='<p>Enter Relationship please!</p>';
	endif;
	if( empty(trim($posts->address)) ):
		$err++;
		$fail.='<p>Enter Description_of_Warnings please!</p>';
	endif;
	if( !empty(trim($posts->address)) ):
		$posts->address = cleanUp( $posts->address );  
	endif;
	if( empty(trim($posts->work_address)) ):
		$err++;
		$fail.='<p>Enter personal objectives please!</p>';
	endif;
	if( !empty(trim($posts->work_address)) ):
		$posts->work_address = cleanUp( $posts->work_address );  
	endif;
	if ($err==0) {
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $targetFile = $targetDir.getToken(5)."_guarantor.png";
		if ( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ) :
		else:
			$targetFile="";
		endif;
		$query = "INSERT INTO `guarantors`(`employeeid`, `firstname`, `surname`, `address`, `city`, `state`, `country`, `relationship`, `guarantors_image`, `phone`, `email`, `work_address`, `notes`) VALUES ('$posts->employeeid','$posts->firstname','$posts->surname','$posts->address','$posts->city','$posts->state','$posts->country','$posts->relationship','$targetFile','$posts->phone','$posts->email','$posts->work_address','$posts->notes');";
	    if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Guarantor successfully added.</p></div>';
			$guarantor=$ezDb->get_row("SELECT * FROM `guarantors` WHERE `employeeid`='$posts->employeeid' ORDER BY `id` LIMIT 1;");
			logEvent($userinfo->email, "new-guarantor-added", $userinfo->usertype, 'guarantors', $guarantor); 
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add guarantor</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
$smarty->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities)->assign("fail",$fail);
$smarty->assign("userinfo", $Site["session"]["User"]["userinfo"]);
