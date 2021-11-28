<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2')) ):
	redirect("home");
endif;

$last_zone = $ezDb->get_var("SELECT `prefix` FROM `id_zone` ORDER BY `id` DESC LIMIT 1");
$prefix = str_split($last_zone,4);
$prefix = $prefix[1]+1;
$prefix = sprintf('acc%02d', $prefix);
//echo $prefix;

if ( in_array($sitePage, array("add_branch")) && ($requestMethod == 'POST') && isset($Site["post"]['branch']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter name please!</p>';
	endif;
	if( empty(trim($posts->address)) ):
		$err++;
		$fail.='<p>Enter address please!</p>';
	endif;
	if( !empty($posts->name) and !empty($ezDb->get_var("SELECT `zone` FROM `id_zone` WHERE `zone`='$posts->name';"))):
		$fail.='<p>Invalid branch Name: Branch already exists</p>';
		$err++;
	endif;
	if ($err==0) {
		$posts->address = cleanUp($posts->address);
		$query = "INSERT INTO `id_zone` (`zone`,`prefix`,`address`) VALUES ('$posts->name','$prefix','$posts->address')";
		if($ezDb->query($query)):
			$branch=$ezDb->get_row("SELECT * FROM `branch` WHERE `prefix`='$prefix';");
      		logEvent($userinfo->email, "new-branch-added", $userinfo->usertype, 'branch', $branch);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your branch was successfully added.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Unable to Add </div>';
		endif;

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$smarty->assign("prefix",$prefix);