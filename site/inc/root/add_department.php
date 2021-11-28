<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_department")) && ($requestMethod == 'POST') && isset($Site["post"]['department']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter name please!</p>';
	endif;
	if( !empty($posts->name) and !empty($ezDb->get_var("SELECT `name` FROM `department` WHERE `name`='$posts->name';"))):
		$fail.='<p>Invalid Department Name: Department already exists</p>';
		$err++;
	endif;
	if ($err==0) {
	    $query = "INSERT INTO `department` (`name`) VALUES ('$posts->name')";
		if($ezDb->query($query)):
			$department=$ezDb->get_row("SELECT * FROM `department` WHERE `name`='$posts->name';");
      		logEvent($userinfo->email, "new-department-added", $userinfo->usertype, 'department', $department);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your department was successfully added.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}