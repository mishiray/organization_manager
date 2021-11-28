<?php

$emergency=$ezDb->get_row("SELECT * from `emergency_contact` WHERE `employeeid` = '$userinfo->employeeid';");


//echo 'ok';
if (in_array($sitePage, array("emergency_contact")) && ($requestMethod == 'POST') && isset($Site["post"]['emergency_update']) ) {
	//echo 'ok';
	$posts = (object) $Site["post"];
	$fail="";
	$err=0;
	if( empty(trim($posts->firstname)) ):
		$err++;
		$fail.='<p>Enter First Name please!</p>';
	endif;
		if( empty(trim($posts->surname)) ):
		$err++;
		$fail.='<p>Enter Surname please!</p>';
	endif;
	if( empty(trim($posts->address)) ):
		$err++;
		$fail.='<p>Enter the Address please!</p>';
	endif;
	if( empty(trim($posts->phone)) ):
		$err++;
		$fail.='<p>Enter the Phone please!</p>';
	endif;
	if( empty(trim($posts->email)) ):
		$err++;
		$fail.='<p>Enter the Email please!</p>';
	endif;
	if( empty(trim($posts->relationship)) ):
		$err++;
		$fail.='<p>Enter the relationship please!</p>';
	endif;
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2','level4'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;

	if ($err==0) {
		if(empty($emergency)){
		$query = "INSERT INTO `emergency_contact`(`employeeid`, `firstname`, `surname`, `address`, `phone`, `email`, `relationship`) VALUES ('$userinfo->employeeid','$posts->firstname','$posts->surname','$posts->address','$posts->phone','$posts->email','$posts->relationship')";
			
		if($ezDb->query($query)):
			$emergency=$ezDb->get_row("SELECT * from `emergency_contact` WHERE `employeeid` = '$userinfo->employeeid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Emergency Contact was successfully added</p></div>';
			logEvent($userinfo->email, "emergency-contact-added", $userinfo->usertype, 'emergency_contact', $emergency);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

		}else{
		$query = "UPDATE `emergency_contact` SET `firstname`='$posts->firstname', `surname`='$posts->surname', `address`='$posts->address', `phone`='$posts->phone', `email`='$posts->email', `relationship` = '$posts->relationship' WHERE `employeeid` = '$userinfo->employeeid'";
		if($ezDb->query($query)):
			$emergency=$ezDb->get_row("SELECT * from `emergency_contact` WHERE `employeeid` = '$userinfo->employeeid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Emergency Contact was successfully updated</p></div>';
			logEvent($userinfo->email, "emergency-contact-updated", $userinfo->usertype, 'emergency_contact', $emergency);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$smarty->assign("emergency", $emergency);
