<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) && !in_array($userinfo->department, array('Human Resources')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_disciplinary_warning")) && ($requestMethod == 'POST') && isset($Site["post"]['warning_type']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter warning type please!</p>';
	endif;
	if( !empty($posts->name) and !empty($ezDb->get_var("SELECT `warning` FROM `disciplinary_warnings` WHERE `warning`='$posts->name';"))):
		$fail.='<p>Invalid Warning: Warning already exists</p>';
		$err++;
	endif;
	if ($err==0) {
	    $query = "INSERT INTO `disciplinary_warnings` (`warning`) VALUES ('$posts->name')";
		if($ezDb->query($query)):
			$warning=$ezDb->get_row("SELECT * FROM `disciplinary_warnings` WHERE `warning`='$posts->name';");
      		logEvent($userinfo->email, "new-warning-added", $userinfo->usertype, 'disciplinary_warnings', $warning);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your warning type was successfully added.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}