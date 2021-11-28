<?php
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2'))  && !in_array($userinfo->department, array('Human Resources'))):
	redirect("home");
endif;

$empid=(!empty($gets->id)? $gets->id: "");

$employee=$ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$empid';");
if(!empty($employee)){
	$employee->department=$ezDb->get_var("SELECT `name` from  `department` WHERE `id` = '$employee->department_id';");
$employee->username = $employee->first_name[0].$employee->surname;

}
if(!empty($ezDb->get_var("SELECT `username` FROM `userprofile` WHERE `username` = '$employee->username'"))){
	$employee->username = $employee->username.rand(10,100);
}

$smarty->assign("item", $employee);


if (!empty($posts->triggers) and $posts->triggers=='create_login') {
	$posts = (object) $Site["post"];
	if( !empty($posts->username) and !empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `username`='".strtolower(trim($posts->username))."';"))):
		$fail.='<p>Invalid Username: Username already exists</p>';
	endif;
	if( empty($posts->datehired) ):
		$fail.='<p>Invalid Date: empty hire date is not allowed</p>';
		$err++;
	endif;
	
	if( empty($posts->userrole) ):
		$fail.='<p>Invalid Role: empty role is not allowed</p>';
		$err++;
	endif;
	if( !empty($posts->password) and (strlen($posts->password) < 8 or !preg_match($passwordAuth['1'], $posts->password)  or !preg_match($passwordAuth['1'], $posts->password) or !preg_match($passwordAuth['2'],$posts->password) ) ):
		$fail.='<p>Invalid Password: password should be at least 8 characters in length and should include at least one upper case letter, one number</p>';
		$err++;
	endif;
	if($err==0):
		$token = getToken('32');
		$ezDb->query("INSERT INTO `userprofile` (`token`,`title`,`firstname`,`middlename`,`lastname`, `phone`, `gender`, `address1`, `email`, `password`, `username`, `userid`, `state`, `city`, `terms`, `active`,`verified`, `dateadded`, `usertype`, `usercat`, `userrole`,`datehired`) VALUES ('$token','$employee->title','$employee->first_name','$employee->middle_name', '$employee->surname', '$employee->phone', '$employee->sex', '$employee->address', '".strtolower($employee->email)."', '".base64_encode($posts->password)."', '".strtolower($posts->username)."','$employee->employeeid', '$employee->state', '$employee->city', '1', '1','1', '$dateNow', 'admin', 'admin', '$posts->userrole', '$posts->datehired');");
		$message = "Welcome to Atobe CC Online, Please do well to update your emergency and guarantor contacts. We wish you a wonderful experience";
		alertUser(strtolower($employee->email),0,$message);
		$confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
		$ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($employee->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
		
		require_once 'mail_signup.php';
	else:
		$fail='<div class="alert alert-danger text-justify">
					<i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3>Error Messages</h3><div class="alert alert-danger alert-dismissible" role="alert"> '.$fail.'</div>
			   </div>';
	endif;
}