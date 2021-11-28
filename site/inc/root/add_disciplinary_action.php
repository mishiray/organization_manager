<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2'))  && !in_array($userinfo->department, array('Human Resources'))):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_disciplinary_action")) && ($requestMethod == 'POST') && isset($Site["post"]['action_type']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter action please!</p>';
	endif;
	if( !empty($posts->name) and !empty($ezDb->get_var("SELECT `action` FROM `disciplinary_action` WHERE `action`='$posts->name';"))):
		$fail.='<p>Invalid Action: Action type already exists</p>';
		$err++;
	endif;
	if ($err==0) {
	    $query = "INSERT INTO `disciplinary_action` (`action`) VALUES ('$posts->name')";
		if($ezDb->query($query)):
			$warning=$ezDb->get_row("SELECT * FROM `disciplinary_action` WHERE `action`='$posts->name';");
      		logEvent($userinfo->email, "new-action-added", $userinfo->usertype, 'disciplinary_action', $warning);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your action type was successfully added.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}