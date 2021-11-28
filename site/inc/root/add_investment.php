<?php 


$properties=$ezDb->get_results("SELECT * from `projects` where active = 1;");

$smarty->assign("properties", $properties);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2'))  && in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;

if ( in_array($sitePage, array("add_investment")) && ($requestMethod == 'POST') && isset($Site["post"]['investment']) ) {

	$fail="";
	$err=0;
	
	if( empty(trim($posts->property)) ):
		$err++;
		$fail.='<p>Select property please!</p>';
	endif;
	if( empty(trim($posts->percentage)) ):
		$err++;
		$fail.='<p>Select percentage please!</p>';
	endif;
	if( empty(trim($posts->category)) ):
		$err++;
		$fail.='<p>Select category please!</p>';
	endif;
	if( empty(trim($posts->duration)) ):
		$err++;
		$fail.='<p>Enter duration please!</p>';
	endif;
	if( empty(trim($posts->min_principal)) ):
		$err++;
		$fail.='<p>Enter Min principal please!</p>';
	endif;
	if( empty(trim($posts->max_principal)) ):
		$err++;
		$fail.='<p>Enter Max principal please!</p>';
	endif;
	if($posts->max_principal < $posts->min_principal):
		$err++;
		$fail.='<p>Max principal less than Min Principal!</p>';
	endif;
	
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2','level4'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;

	if(!empty($ezDb->get_row("SELECT * FROM `investments` WHERE `property` = '$posts->property' AND  `min_principal` = '$posts->min_principal'  AND  `max_principal` = '$posts->max_principal' AND `percentage` = '$posts->percentage';"))){
		$err++;
		$fail.='<p>Error: Investment Plan Already Exists</p>';
	}
	if ($err==0) {
	    $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `investments` ORDER BY `id` DESC LIMIT 1;");
		$query = "INSERT INTO `investments` (`token`,`property`,`percentage`, `category`, `duration`,`min_principal`,`max_principal`,`roi`,`matunity`,`active`) VALUES ('$token','$posts->property','$posts->percentage', '$posts->category','$posts->duration','$posts->min_principal','$posts->max_principal' ,'$posts->roi', '$posts->matunity','$posts->active')";
		if(empty($ezDb->get_row("SELECT * FROM `investments` WHERE `property` = '$posts->property' AND `percentage` ='$posts->percentage' AND `category` = '$posts->category';"))){
			if($ezDb->query($query)):
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your plan was successfully added.</p></div>';
				$investment=$ezDb->get_row("SELECT * FROM `investments` WHERE `token`='$token';");
				  logEvent($userinfo->email, "new-investment-plan-added", $userinfo->usertype, 'investments', $investment);
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error: Please try again! </p></div>';
			endif;
	
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error: Investment Plan Already Exists </p></div>';
		}
		
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}