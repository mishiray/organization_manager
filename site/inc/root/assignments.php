<?php
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level4')) and $userinfo->active==true ):
	redirect("home");
endif;
$whereClause="";
	/*Review Assignment*/
	if ( in_array($sitePage, array("assignments")) && ($requestMethod == 'POST') && isset($posts->review_ass) ) {
		$course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`=(SELECT `courseid` FROM `assignments` WHERE `id`='$posts->assid' AND `user`='$posts->userid');");
		$course=(empty($course)? new stdClass(): $course);
    	$course->instructors=(!empty($course->instructors)? @json_decode($course->instructors): array());
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to review this course assignment!</p>';
		endif;
		if( $err==0 ):
			$posts->review=nl2br2($posts->review);
			$posts->review=tb2sp2($posts->review);
			$ezDb->query("UPDATE `assignments` SET `mark`='$posts->score', `review`='$posts->review' WHERE `id`='$posts->assid' AND `user`='$posts->userid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Assignment successfully reviewed.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	/*Deleting of Assignment*/
	if ( in_array($sitePage, array("assignments")) && ($requestMethod == 'GET') && isset($gets->assid) && isset($gets->evt) && $gets->evt=='delete') {
		if( !empty(trim($gets->assid)) and !empty($ezDb->get_var("SELECT `title` FROM `assignments` WHERE `id`='$gets->assid' AND `user`='$gets->u'"))):
			$course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`=(SELECT `courseid` FROM `assignments` WHERE `id`='$gets->assid' AND `user`='$gets->u');");
			$course=(empty($course)? new stdClass(): $course);
	    	$course->instructors=(!empty($course->instructors)? @json_decode($course->instructors): array());
			if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $course->instructors))) 
				or $userinfo->active!=true ):
				$err++;
				$fail.='<p>You have no authorization to delete this course assignment!</p>';
			endif;
			if ($err==0) {
				$ass=$ezDb->get_row("SELECT * FROM `assignments` WHERE `id`='$gets->assid' AND `user`='$gets->u'");
				if (!empty($ass->sfile)):
			    	@unlink($ass->sfile);
				endif;
				$ezDb->query("DELETE FROM `assignments` WHERE `id`='$gets->assid' AND `user`='$gets->u';");
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Assignment had been successfully deleted.</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		endif;
	}

$assignments=tableRoutine("assignments", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '10');
if (!empty($assignments)) {
	foreach ($assignments as $key => $assignment) {
		$assignment->title=testInputReverse(trim($assignment->title));
		$assignment->description=testInputReverse(trim($assignment->description));
		$assignment->userinfo=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$assignment->user' OR `email`='$assignment->user';");
		$assignment->review=testInputReverse(trim($assignment->review));
		$assignment->course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='$assignment->courseid';");
		$assignment->course=(empty($assignment->course)? new stdClass(): $assignment->course);
		$assignment->course->description=testInputReverse(trim($assignment->course->description));
	    $assignment->course->title=testInputReverse(trim($assignment->course->title));
	    $assignment->course->category=testInputReverse(trim($assignment->course->category));
	    $assignment->material=$ezDb->get_row("SELECT * FROM `course_material` WHERE `courseid`='$assignment->courseid' AND `id`='$assignment->lesson';");
	    $assignment->material=(empty($assignment->material)? new stdClass(): $assignment->material);
	    $assignment->material->title=testInputReverse(trim($assignment->material->title));
	    $assignment->material->description=testInputReverse(trim($assignment->material->description));
	    $assignment->sfile=@json_decode($assignment->sfile);
	    $assignment->assignment=$ezDb->get_row("SELECT * FROM `mat_assignment` WHERE `courseid`='$assignment->courseid' AND `matid`='$assignment->lesson' AND `id`='$assignment->assid';");
	    if (!empty($assignment->assignment)) {
		    $assignment->assignment->title1=testInputReverse(trim(str_replace("<br/>", "\n", $assignment->assignment->title)));
		    $assignment->assignment->description1=testInputReverse(trim(str_replace("<br/>", "\n", $assignment->assignment->description)));
	    }
	}
}
/*Do foreach and get its list of packages it belong*/

$smarty->assign("assignments", $assignments);