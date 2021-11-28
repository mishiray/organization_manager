<?php global $sitePage;
// redirect("home");

$refid=(empty($gets->id)?"":$gets->id);
$referees=$ezDb->get_results("SELECT `firstname`,`lastname`,`userid` FROM `userprofile` WHERE `active` = 1");
$referees = json_encode($referees);
if (!empty($refid) and !empty($ezDb->get_var("SELECT `userid` FROM `userprofile` WHERE `userid`='$refid';"))):
	$Site["session"]["refid"]=$refid;
	$sessions= (object)$Site["session"];
	$_SESSION=$Site["session"];
endif;

if (!empty($sessions->refid)):
	$referredBy=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `userid`='".strtolower(trim($sessions->refid))."';");
	$smarty->assign('referredBy', $referredBy);
endif;

if (!empty($gets->email)) {
	$smarty->assign('email', $gets->email);
}
// error_log(json_encode($posts));
$states=getStates($country='Nigeria');
$countryid=$states[0]->country_id;
$state=$states[0]->name;
$cities=getCities($state);

if(!empty($posts->signup) and $posts->signup=='signup' and $sitePage=='signup'):
	if( empty($posts->username) ):
		$fail.='<p>Invalid Username: username is not entered</p>';
		$err++;
	endif;
	if( empty($posts->firstName) ):
		$fail.='<p>Invalid First Name: first name is not entered</p>';
		$err++;
	endif;
	if( empty($posts->lastName) ):
		$fail.='<p>Invalid Last Name: last name is not entered</p>';
		$err++;
	endif;
	if( empty($posts->terms) or $posts->terms!='1'):
		$fail.='<p>Terms Is Required: you have to agree with website operative terms & policy</p>';
		$err++;
	endif;
	if( $posts->newsletter!="1" ):
		$posts->newsletter="0";
	endif;
	if( empty($posts->gender) or !in_array($posts->gender, array('male', 'female')) ):
		$fail.='<p>Invalid Gender Selection: select a valid gender</p>';
		$err++;
	endif;
	if( empty($posts->email) or !checkEmail($posts->email)):
		$fail.='<p>Invalid Email: not a valid email</p>';
		$err++;
	endif;
	if( empty($posts->phone) or !checkPhone($posts->phone)):
		$fail.='<p>Invalid Phone: not a valid phone number</p>';
		$err++;
	endif;
	if( empty($posts->bankname) ):
		$fail.='<p>Invalid Bank Name: not a valid bank name</p>';
		$err++;
	endif;
	if( empty($posts->accname)):
		$fail.='<p>Invalid Account Name: not a valid account name</p>';
		$err++;
	endif;
	if( empty($posts->accno) or !is_numeric($posts->accno)):
		$fail.='<p>Invalid Account Number: not a valid account number</p>';
		$err++;
	endif;
	if( !empty($posts->email) and strpos(strtolower($posts->email), $domainName)>-1):
		$fail.='<p>Invalid Email: this kind of email is not allowed</p>';
		$err++;
	endif;
	if( !empty($posts->email) and !empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='".strtolower(trim($posts->email))."';"))):
		$fail.='<p>Invalid Email: there is an account with this email, kindly reqest for a password reset if you owns the email</p>';
		$err++;
	endif;
	if( !empty($posts->username) and !empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `username`='".strtolower(trim($posts->username))."';"))):
		$posts->username = $posts->username.getToken('2').$ezDb->get_var("SELECT IFNULL(`id`+1,'1') FROM `userprofile` ORDER BY `id` DESC LIMIT 1;");
		$fail.='<p>Invalid Username: there is an account with this username, you can use this instead `'.$posts->username.getToken('2').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `userprofile` ORDER BY `id` DESC LIMIT 1;").'`</p>';
		$err++;
	endif;
	if( empty($posts->refid) or empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `userid`='".strtolower(trim($posts->refid))."';"))):
		$fail.='<p class="border border-danger p-2">Invalid Referral Code: the referral code does not exist</p>';
		$err++;
	endif;
	if( !empty($posts->state) and empty($ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='$posts->state' AND `country_id`='$countryid'"))):
		$fail.='<p class="border border-danger p-2">Invalid State: your selected state is invalid</p>';
		$err++;
	endif;
	if( empty($posts->password) ):
		$fail.='<p>Invalid Password: empty password is not allowed</p>';
		$err++;
	endif;
	if( !empty($posts->password) and $posts->password!=$posts->cpassword):
		$fail.='<p>Invalid Password: your password must match the confirm password value</p>';
		$err++;
	endif;
	//$specialChars = preg_match('@[^\w]@', $password);
	if( !empty($posts->password) and ( strlen($posts->password) < 8 or !preg_match($passwordAuth['1'], $posts->password)  or !preg_match($passwordAuth['1'], $posts->password) or !preg_match($passwordAuth['2'],$posts->password) ) ):
		$fail.='<p>Invalid Password: password should be at least 8 characters in length and should include at least one upper case letter, one number</p>';
		$err++;
	endif;

	if($err==0):
		
		$count = $ezDb->get_var("SELECT `value` FROM `counter2`");
		$newId = $count + 1;
		$format = 'acc';
		$genRefid = $format.$newId;

		//$genRefid="bpl000".$ezDb->get_var("SELECT IFNULL((`id`+1),'1') FROM `userprofile` WHERE `usertype`='client' AND `userrole`='level3' AND `refid` IS NOT NULL ORDER BY `id` DESC LIMIT 1;");
		// $modNames=explode(" ", $posts->names);
		$posts->firstname= $posts->firstName;
		// $posts->firstname=(empty($modNames[0])?$posts->names:$modNames[0]);
		// $posts->lastName=(empty($modNames[1])?"":$modNames[1]);
		$token = getToken('32');
		if($ezDb->query("INSERT INTO `userprofile` (`token`,`firstname`,`lastname`, `phone`, `gender`, `address1`, `email`, `password`, `username`, `state`, `city`, `terms`, `active`, `dateadded`, `usertype`, `usercat`, `userrole`, `verified`,`userid` ,`referrer`, `refid`, `bank_name`, `account_name`, `account_number`, `country`) VALUES ('$token','$posts->firstname', '$posts->lastName', '$posts->phone', '$posts->gender', '$posts->address1', '".strtolower($posts->email)."', '".base64_encode($posts->password)."', '".strtolower($posts->username)."', '$posts->state', '$posts->city', '$posts->terms', '1', '$dateNow', 'client', 'client', 'level1', '0','$genRefid', '$referredBy->email', '$sessions->refid', '$posts->bankname', '$posts->accname', '$posts->accno','Nigeria');")):
			
			$ezDb->query("UPDATE `counter2` SET `value` = '$newId' WHERE `id` = 1");
			
			$confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
			$ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($posts->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
			if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posts->email'"))) {
				$ezDb->query("UPDATE `newsletter` SET `status`='$posts->newsletter', `dateupdated`='$dateNow' WHERE `email`='$posts->email'");
			}else{
				$ezDb->query("INSERT INTO `newsletter` (`status`, `dateupdated`, `email`) VALUES ('$posts->newsletter', '$dateNow', '$posts->email');");
			}
			require_once 'mail_signup.php';
		endif;
	else:
		$fail='<div class="alert alert-danger text-justify">
					<i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3>Error Messages</h3><div class="alert alert-danger alert-dismissible" role="alert"> '.$fail.'</div>
				</div>';
	endif;
endif;

$smarty->assign("states", $states)->assign("cities", $cities)->assign("referees",$referees);