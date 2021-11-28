<?php
$whereClause="";
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1')) and $userinfo->active==true ):
	redirect("home");
endif;

/*Edit Project*/
if ( in_array($sitePage, array("finalprojects")) && ($requestMethod == 'POST') && isset($posts->edit_prj) ) {
	if( empty(trim($posts->title))):
		$err++;
		$fail.='<p>Kindly enter project title!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly enter project description or content!</p>';
	endif;
	if( $err==0 ):
		$posts->description=nl2br2($posts->description);
		$posts->title=nl2br2($posts->title);
		$posts->description=tb2sp2($posts->description);
		$posts->title=tb2sp2($posts->title);
		$ezDb->query("UPDATE `finalprojects` SET `title`='$posts->title', `description`='$posts->description' WHERE `id`='$posts->fpid' AND `user`='$posts->userid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project detail successfully update.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
/*Review Assignment*/
if ( in_array($sitePage, array("finalprojects")) && ($requestMethod == 'POST') && isset($posts->review_prj) ) {
	if( $err==0 ):
		$posts->review=nl2br2($posts->review);
		$posts->review=tb2sp2($posts->review);
		$ezDb->query("UPDATE `finalprojects` SET `mark`='$posts->score', `review`='$posts->review' WHERE `id`='$posts->fpid' AND `user`='$posts->userid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project successfully reviewed.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
/*Deleting of Final project*/
if ( in_array($sitePage, array("finalprojects")) && ($requestMethod == 'GET') && isset($gets->fpid) && isset($gets->evt) && $gets->evt=='delete') {
	if( !empty(trim($gets->fpid)) and !empty($ezDb->get_var("SELECT `title` FROM `assignments` WHERE `id`='$gets->fpid' AND `user`='$gets->u'"))):
		if ($err==0) {
			$pro=$ezDb->get_row("SELECT * FROM `finalprojects` WHERE `id`='$gets->fpid' AND `user`='$gets->u'");
			if (!empty($pro->sfiles) and is_array($pro->sfiles)):
				foreach ($pro->sfiles as $key => $value) :
					if (file_exists($value)) :
						@unlink($value->url);
					endif;
				endforeach;
			endif;
			$ezDb->query("DELETE FROM `finalprojects` WHERE `id`='$gets->fpid' AND `user`='$gets->u';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project had been successfully deleted.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	endif;
}

$finalprojects=tableRoutine("finalprojects", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', "10");
if (!empty($finalprojects)) {
	foreach ($finalprojects as $key => $finalproject) {
		$finalproject->client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$finalproject->user'");
		$finalproject->description=testInputReverse(trim($finalproject->description));
	    $finalproject->title=testInputReverse(trim($finalproject->title));
	    $finalproject->sfiles=@json_decode($finalproject->sfiles);
		$finalproject->review=testInputReverse(trim($finalproject->review));

	}
}
$smarty->assign("finalprojects", $finalprojects);
