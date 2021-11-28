<?php 

$Site['post']= (array) $posts;

$countries=getCountries();
$states=getStates($Site['session']['User']['userinfo']->country);

if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='".$Site['session']['User']['userinfo']->state."'")) ){
	$state=$states[0]->name;
}
$cities=getCities($state);


if (in_array($sitePage, array("vendors")) && ($requestMethod == 'POST') && $posts->triggers=='add_vendor') {
	$fail="";
	$err=0;
	if(empty($posts->vendor_name)):
		$err++;
		$fail.='<p>Please add vendor name</p>';
	endif;
	if(!empty($ezDb->query("SELECT * FROM `vendors` WHERE `vendor_name` = '$posts->vendor_name' AND `email` = '$posts->email'"))):
		$err++;
		$fail.='<p>Vendor already exists</p>';
	endif;
	if(empty($posts->firstname)):
		$err++;
		$fail.='<p>Please add first name</p>';
	endif;
	if(empty($posts->lastname)):
		$err++;
		$fail.='<p>Please add last name</p>';
	endif;
	if(empty($posts->city)):
		$err++;
		$fail.='<p>Please add city</p>';
	endif;
	if(empty($posts->state)):
		$err++;
		$fail.='<p>Please add state</p>';
	endif;
	if(empty($posts->country)):
		$err++;
		$fail.='<p>Please add country</p>';
	endif;
	if(empty($posts->address)):
		$err++;
		$fail.='<p>Please add address</p>';
	endif;
	if(empty($posts->email)):
		$err++;
		$fail.='<p>Please add email</p>';
	endif;
	if($err == 0){
		$token = getToken(6);
		$query = "INSERT INTO `vendors` (`token`,`vendor_name`,`firstname`,`lastname`,`email`,`phone`,`city`,`state`,`country`,`address`) VALUES 
											('$token','$posts->vendor_name','$posts->firstname','$posts->lastname','$posts->email','$posts->phone','$posts->city','$posts->state','$posts->country','$posts->address')";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> New vendor added </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}

if (in_array($sitePage, array("vendors")) && ($requestMethod == 'POST') && $posts->triggers=='update_vendor') {
	$fail="";
	$err=0;
	if(empty($posts->firstname)):
		$err++;
		$fail.='<p>Please add firstname</p>';
	endif;
	if(empty($posts->lastname)):
		$err++;
		$fail.='<p>Please add lastname</p>';
	endif;
	if(empty($posts->phone)):
		$err++;
		$fail.='<p>Please enter phone</p>';
	endif;
	if(empty($posts->address)):
		$err++;
		$fail.='<p>Please enter address</p>';
	endif;
	if(empty($posts->country)):
		$err++;
		$fail.='<p>Please enter country</p>';
	endif;
	if(empty($posts->state)):
		$err++;
		$fail.='<p>Please enter state</p>';
	endif;
	if(empty($posts->city)):
		$err++;
		$fail.='<p>Please enter city</p>';
	endif;
	if($err == 0){
		$query = "UPDATE `vendors` SET `firstname` = '$posts->firstname',`lastname` = '$posts->lastname',`phone` = '$posts->phone',`address`='$posts->address',`country`='$posts->country',`state`='$posts->state',`city`='$posts->city' WHERE `token` = '$posts->token' ";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Vendor Updated </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to update.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}


if (in_array($sitePage, array("vendors")) && ($requestMethod == 'POST') && $posts->triggers=='delete_vendor') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `vendors` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `vendors` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			logEvent($userinfo->email, "vendor-deleted", $userinfo->usertype, 'vendors', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Vendor Deleted</p></div>';
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to delete. kindly re-try</p></div>';
		}
	else: 
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured. Invalid token</p></div>';
	endif;
}


$whereClause="id != 0 ";

$query = "SELECT * FROM `vendors` ORDER BY `id` DESC";
$vendors = $ezDb->get_results($query);


$smarty->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities)->assign("vendors",$vendors);