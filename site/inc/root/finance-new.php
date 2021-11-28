<?php 

if( !in_array( $userinfo->userrole, array('level0','level1','level6'))  && !in_array($userinfo->department, array('Accounting'))):
	redirect("finances");
endif;
$projects=$ezDb->get_results("SELECT * FROM `projects` ORDER BY `id` DESC;");
if (!empty($projects)) {
	foreach ($projects as $key => $project) {
		$project->images=@json_decode($project->images);
		$project->content2=testInputReverseln(trim($project->content));
		$project->content=testInputReverse(trim($project->content));
		$project->title2=testInputReverseln(trim($project->title));
		$project->title=testInputReverse(trim($project->title));
		$project->type2=testInputReverseln($project->type);
		$project->type=testInputReverse($project->type);
		
	}
}

if ( in_array($sitePage, array("finance-new")) && ($requestMethod == 'POST') && isset($Site["post"]['finance']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter finance title please!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Enter finance purpose please!</p>';
	endif;
	if( empty(trim($ezDb->get_var("SELECT `token` FROM `projects` WHERE `token`='$posts->project';"))) ):
		$err++;
		$fail.='<p>Select a valid project please!</p>';
	endif;
	if( empty(trim($posts->amount)) or is_nan($posts->amount) ):
		$err++;
		$fail.='<p>Enter a valid budget amount please!</p>';
	endif;
	if ($err==0) {
		 $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `finances` ORDER BY `id` DESC LIMIT 1;");
	    $posts->description=testInputln($posts->description);
	    $posts->title=testInputln($posts->title);
		/*if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;*/

		$ezDb->query("INSERT INTO `finances` (`financeid`, `project`, `title`, `amount`, `discount`, `description`, `user`,`md_review`, `dateadded`, `status`) VALUES ('$token', '$posts->project', '$posts->title', '$posts->amount', '$posts->discount', '$posts->description', '$userinfo->email', '".@json_encode($emptyArray)."', '$dateNow', '0');");
	    $finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$token';");
	    logEvent($userinfo->email, "new-finance-added", $userinfo->usertype, 'finances', $finance);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finance detail was successfully created.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$smarty->assign("projects", $projects);