<?php 
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level4')) and $userinfo->active==true ):
	redirect("home");
endif;

// $whereClause="AND `user`='$userinfo->email' ";
$whereClause=" ";
// $regCourse=$ezDb->get_results("SELECT `courseid`, `regid` FROM `registered_courses` WHERE `user`='$userinfo->email'");


/*Reset Assessment*/
if ( in_array($sitePage, array("assessments")) && ($requestMethod == 'GET') && isset($Site["get"]['result']) && isset($Site["get"]['id']) && isset($Site["get"]['reset']) && $Site["get"]['reset']=='true' ) {

	$resid=(!empty($gets->result)? $gets->result: "");
	$clientid=(!empty($gets->id)? $gets->id: "");
	$result=$ezDb->get_row("SELECT * FROM `mat_results` WHERE `id`='$resid' AND `user`='$clientid'");
	$course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`=(SELECT `courseid` FROM `course_material` WHERE `id`='$result->parentid');");
	$course=(empty($course)? new stdClass(): $course);
	$course->instructors=(!empty($course->instructors)? @json_decode($course->instructors): array());
	if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $course->instructors))) 
		or $userinfo->active!=true ):
		$err++;
		$fail.='<p>You have no authorization to reset this course assessment!</p>';
	endif;

	if ($err==0 and !empty($result)) {
		$ezDb->query("DELETE FROM `mat_results` WHERE `id`='$resid' AND `user`='$clientid';");
		$ezDb->query("DELETE FROM `mat_attempt` WHERE `user`='$result->user' AND `regid`='$result->regid' AND `parentid`='$result->parentid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>The select assessment was successfully reset.</p></div>';
	}elseif($err>0){
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$results=tableRoutine("`mat_results`", '*' , "`id`!=0 $whereClause", '`id`', 'DESC', '`id`', '10');
if (!empty($results)) {
	foreach ($results as $key => $result) {
		$result->client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$result->user' OR `email`='$result->user' AND `usertype`='client';");
		$result->material=$ezDb->get_row("SELECT * FROM `course_material` WHERE `id`='$result->parentid';");
		$result->material=(empty($result->material)? new stdClass(): $result->material);
		$result->course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='".$result->material->courseid."';");
		$result->course=(empty($result->course)? new stdClass(): $result->course);
		$result->crid=$ezDb->get_var("SELECT DISTINCT `regid` FROM `registered_courses` WHERE `courseid`='".$result->material->courseid."'");
		$result->course->description=testInputReverse(trim($result->course->description));
	    $result->course->title=testInputReverse(trim($result->course->title));
	    $result->course->instructions=testInputReverse(trim($result->course->instructions));
		$result->course->category=testInputReverse(trim($result->course->category));
	    $result->course->outlines=$ezDb->get_results("SELECT * FROM `outlines` WHERE `courseid`='".$result->material->courseid."'");
		/*if (!empty($result->course->outlines) and is_array($result->course->outlines)) {
			foreach ($result->course->outlines as $ke => $value) {
				$result->course->outlines[$ke]->description1=str_replace("<br/>", "\n", testInputReverse($value->description));
			}
		}*/
		$result->material->description=testInputReverse(trim($result->material->description));
	    $result->material->title=testInputReverse(trim($result->material->title));
		$result->material->instruction=testInputReverse(trim($result->material->instruction));
	    // $material->results=$ezDb->get_row("SELECT * FROM `mat_results` WHERE `parentid`='$material->id' AND `user`='$userinfo->email'");
	    $result->settimestr=secToHMS($result->expectedduration);
		$result->usedtimestr=secToHMS($result->usedduration);
		$result->answers=$ezDb->get_var("SELECT `answers` FROM `mat_attempt` WHERE `user`='$result->user' AND `regid`='$result->regid' AND `parentid`='$result->parentid'");
		$result->answers=@json_decode($result->answers);
		$result->attempted=count($result->answers);
	}
}
/*Do foreach and get its list of packages it belong*/

$smarty->assign("results", $results);