<?php 
if( !in_array( $userinfo->userrole, array('level0','level1', 'level4')) and $userinfo->active==true ):
	redirect("home");
endif;
$matid=(!empty($gets->id)? $gets->id: "");
$material=$ezDb->get_row("SELECT * FROM `course_material` WHERE `id`='$matid'");
if (!empty($material) and is_object($material)) {
	$material->course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='$material->courseid';");
    $material->course->instructors=(!empty($material->course->instructors)? @json_decode($material->course->instructors): array());
	$mailInject=new stdClass;
	$mailInject->students=$ezDb->get_results("SELECT `email`, CONCAT(`firstname`, ' ', `lastname`) AS `student` FROM `userprofile` WHERE `email` IN (SELECT DISTINCT `user` FROM `registered_courses` WHERE `courseid`='$material->courseid');");
	/*Editing Instruction*/
	if ( in_array($sitePage, array("material_mgt")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_instruction']) ) {
		if( empty($posts->examduration) or $posts->examduration<1):
			$err++;
			$fail.='<p>Assessment duration cannot be lesser than a minute!</p>';
		endif;
		if( !in_array($posts->exam, array("0", "1")) ):
			$err++;
			$fail.='<p>Select a valid exam commencement status!</p>';
		endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course material!</p>';
		endif;
		if( $err==0 ):
			$posts->instructions=nl2br2($posts->instructions);
			$posts->instructions=tb2sp2($posts->instructions);
		    $posts->instructions=testInput(trim($posts->instructions));  
		    $ezDb->query("UPDATE `course_material` SET `instruction`='$posts->instructions', `testduration`='$posts->examduration', `assess`='$posts->exam' WHERE `id`='$matid'");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Lesson Assessment instruction was successfully updated.</p></div>';
		    /*Mail controller*/
		    if (!empty($mailInject->students)):
			    $mailInject->title="Course Assessment Instruction Update";
			    $mailInject->text="An instruction of this course assessment: ".$material->course->title." ($material->title ".$material->course->courseid.") was recently updated, also your ability to take this course assessment is now ".($posts->exam==0?"disabled": "enabled");
			    $mailInject->html='An instruction of this course assessment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') was recently updated, also your ability to take this course assessment is now '.($posts->exam==0?"disabled": "enabled");
			    $mailInject->area='course material assessment area';
			    $mailInject->prevMsg='<p>Lesson Assessment instruction was successfully updated.</p>';
			    require_once "mail_students.php";
		    endif;
			$material=$ezDb->get_row("SELECT * FROM `course_material` WHERE `id`='$matid'");
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	/*Add Question*/
	if ( in_array($sitePage, array("material_mgt")) && ($requestMethod == 'POST') && isset($Site["post"]['add_question']) ) {
		$files= (object) $Site["files"];
		$targetDir="site/media/questions/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		$extensions2 = array('application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if( empty(trim($posts->question)) ):
			$err++;
			$fail.='<p>Enter the new exam question!</p>';
		endif;
		if( !in_array($posts->qtype, array("theory", "objective")) ):
			$err++;
			$fail.='<p>Selected form Fill is not recognized!</p>';
		endif;
		if( !in_array($posts->filetype, array("picture", "video")) ):
			$err++;
			$fail.='<p>Selected file type is not recognized!</p>';
		endif;
		if ( !empty($files->qpic['tmp_name']) and !in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions) and in_array($posts->filetype, array("picture"))):
	    	$fail .= '<p class="border border-danger p-1 rounded">The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
        if($posts->filetype=='video' and !empty($files->qpic['tmp_name']) and in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions)):
        	$fail .= '<p class="border border-danger p-1 rounded">Picture file cannot be uploaded for video.</p>';
        	$err++;
        endif;
		if ( !empty($files->qpic['tmp_name']) and !in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions2) and in_array($posts->filetype, array("video"))):
	    	$fail .= '<p class="border border-danger p-1 rounded">The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
        if($posts->filetype=='picture' and !empty($files->qpic['tmp_name']) and in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions2)):
        	$fail .= '<p class="border border-danger p-1 rounded">Video file cannot be uploaded for picture.</p>';
        	$err++;
        endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course material!</p>';
		endif;
		if($posts->qtype=='objective'):
			if( empty($posts->options) and !is_array($posts->options) ):
				$err++;
				$fail.='<p>Kindly add answer options to the questions !</p>';
			else:
				foreach ($posts->options as $key => $value) :
					if (empty($value) and in_array($key, array(0, 1))) :
						$err++;
						$fail.='<p>Option '.($key+1).' cannot be empty!</p>';
					endif;
					$value=nl2br2($value);
					$value=tb2sp2($value);
					$posts->options[$key]=$value;
					if ($posts->answer==("option".($key+1))) :
						$posts->theAnswer=$value;
					endif;
				endforeach;
			endif;
			if( empty(trim($posts->answer)) or !in_array($posts->answer, array("option1","option2","option3","option4")) ):
				$err++;
				$fail.='<p>Kindly choose a valid answer !</p>';
			endif;
			if( $err==0 ):
				$posts->question=nl2br2($posts->question);
				$posts->question=tb2sp2($posts->question);
				if ( !file_exists("$targetDir") ) :
			        mkdir("$targetDir", 0777, true);
			    endif;
			    // $targetFile="";
			    $filAr=explode(".", $files->qpic['name']);
			    $tkend=end($filAr);
			    $targetFile=($targetDir."matqpic_$courseid".getToken(5).$ezDb->get_var("SELECT IFNULL(`id`+1, 1) FROM `mat_questions` ORDER BY `id` DESC LIMIT 1").".$tkend");
				if(file_exists($targetFile)):
					@unlink($targetFile);
				endif;
				$targetFile=((!empty($files->qpic['tmp_name']) and @move_uploaded_file($files->qpic['tmp_name'], $targetFile))? $targetFile: "");
				//id	question	options	answer	courseid	addedby	dateadded
				$ezDb->query("INSERT INTO `mat_questions` (`question`,`options`,`answer`,`parentid`,`addedby`,`dateadded`, `qtype`, `qpic`, `filetype`) VALUES ('$posts->question','".json_encode($posts->options)."','$posts->theAnswer','$matid','$userinfo->email','$dateNow', '$posts->qtype', '$targetFile', '$posts->filetype')");

				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Course lesson assessment quesion was successfully added.</p></div>';
			    /*Mail controller*/
			    if (!empty($mailInject->students)):
				    $mailInject->title="Course Assessment Question Update";
				    $mailInject->text="A question was successfully added in this course material assessment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
				    $mailInject->html='A question was successfully added in this course material assessment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
				    $mailInject->area='course assessment questions';
				    $mailInject->prevMsg='<p>A Course lesson assessment question detail was successfully added.</p>';
				    require_once "mail_students.php";
			    endif;
				$material->questions=getOQuestions($matid);
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
		elseif($posts->qtype=='theory'):
			if( empty(trim($posts->theAnswer)) ):
				$err++;
				$fail.='<p>Enter the new exam answer!</p>';
			endif;
			if( !in_array($posts->fileupload, array("0","1")) ):
				$err++;
				$fail.='<p>Kindly select a vaild Picture Status!</p>';
			endif;
			if( $err==0 ):
				$posts->theAnswer=nl2br2($posts->theAnswer);
				$posts->theAnswer=tb2sp2($posts->theAnswer);
				$posts->question=nl2br2($posts->question);
				$posts->question=tb2sp2($posts->question);
				if ( !file_exists("$targetDir") ) :
			        mkdir("$targetDir", 0777, true);
			    endif;
			    // $targetFile="";
			    $filAr=explode(".", $files->qpic['name']);
			    $tkend=end($filAr);
			    $targetFile=($targetDir."matqpic_$courseid".getToken(5).$ezDb->get_var("SELECT IFNULL(`id`+1, 1) FROM `mat_questions` ORDER BY `id` DESC LIMIT 1").".$tkend");
				if(file_exists($targetFile)):
					@unlink($targetFile);
				endif;
				$targetFile=((!empty($files->qpic['tmp_name']) and @move_uploaded_file($files->qpic['tmp_name'], $targetFile))? $targetFile: "");
				//id	question	options	answer	courseid	addedby	dateadded
				$ezDb->query("INSERT INTO `mat_questions` (`question`,`options`,`answer`,`parentid`,`addedby`,`dateadded`, `qtype`, `fileupload`, `qpic`, `filetype`) VALUES ('$posts->question','".json_encode(array())."','$posts->theAnswer','$matid','$userinfo->email','$dateNow', '$posts->qtype', '$posts->fileupload', '$targetFile', '$posts->filetype')");

				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Course lesson material quesion was successfully added.</p></div>';
			    /*Mail controller*/
			    if (!empty($mailInject->students)):
				    $mailInject->title="Course Assessment Question Update";
				    $mailInject->text="A question was successfully added in this course material assessment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
				    $mailInject->html='A question was successfully added in this course material assessment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
				    $mailInject->area='course assessment questions';
				    $mailInject->prevMsg='<p>A Course lesson assessment question detail was successfully updated.</p>';
				    require_once "mail_students.php";
			    endif;
				$material->questions=getOQuestions($matid);
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
		endif;		
	}
	/*Editing Question*/
	if ( in_array($sitePage, array("material_mgt")) && ($requestMethod == 'POST') && !empty($gets->question) && isset($posts->edit_question)) {
		$files= (object) $Site["files"];
		$targetDir="site/media/questions/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		$extensions2 = array('application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if( empty(trim($posts->questiontext)) ):
			$err++;
			$fail.='<p>Question cannot be empty!</p>';
		endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course material!</p>';
		endif;
		if( !in_array($posts->qtype, array("theory", "objective")) ):
			$err++;
			$fail.='<p>Selected form Fill is not recognized!</p>';
		endif;
		if( !in_array($posts->filetype, array("picture", "video")) ):
			$err++;
			$fail.='<p>Selected file type is not recognized!</p>';
		endif;
		if ( !empty($files->qpic['tmp_name']) and !in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions) and in_array($posts->filetype, array("picture"))):
	    	$fail .= '<p class="border border-danger p-1 rounded">The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
        if($posts->filetype=='video' and !empty($files->qpic['tmp_name']) and in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions)):
        	$fail .= '<p class="border border-danger p-1 rounded">Picture file cannot be uploaded for video.</p>';
        	$err++;
        endif;
		if ( !empty($files->qpic['tmp_name']) and !in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions2) and in_array($posts->filetype, array("video"))):
	    	$fail .= '<p class="border border-danger p-1 rounded">The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
        if($posts->filetype=='picture' and !empty($files->qpic['tmp_name']) and in_array(strtolower(getMime($files->qpic['tmp_name'])), $extensions2)):
        	$fail .= '<p class="border border-danger p-1 rounded">Video file cannot be uploaded for picture.</p>';
        	$err++;
        endif;
	    $theQuest="";
		if( empty(trim($gets->question)) or empty($theQuest=$ezDb->get_row("SELECT * FROM `mat_questions` WHERE `id`='$gets->question' AND `parentid`='$matid'"))):
			$err++;
			$fail.='<p>The selected question does not exist!</p>';
		endif;
		if($posts->qtype=='objective'):
			if( empty($posts->options) and !is_array($posts->options) ):
				$err++;
				$fail.='<p>Kindly add answer options to the questions !</p>';
			else:
				foreach ($posts->options as $key => $value) :
					if (empty($value) and in_array($key, array(0, 1))) :
						$err++;
						$fail.='<p>Option '.($key+1).' cannot be empty!</p>';
					endif;
					$value=nl2br2($value);
					$value=tb2sp2($value);
					$posts->options[$key]=$value;
					if ($posts->answer==("option".($key+1))) :
						$posts->theAnswer=$value;
					endif;
				endforeach;
			endif;
			if( empty(trim($posts->answer)) or !in_array($posts->answer, array("option1","option2","option3","option4")) ):
				$err++;
				$fail.='<p>Kindly choose a valid answer !</p>';
			endif;
			if ($err==0):
				$posts->question=nl2br2($posts->question);
				$posts->question=tb2sp2($posts->question);
				if ( !file_exists("$targetDir") ) :
			        mkdir("$targetDir", 0777, true);
			    endif;
			    $filAr=explode(".", $files->qpic['name']);
			    $tkend=end($filAr);
			    $targetFile=($targetDir."matqpic_$courseid".getToken(5).$theQuest->id.".$tkend");
				if(file_exists($theQuest->qpic) and !empty($files->qpic['tmp_name'])):
					@unlink($theQuest->qpic);
				endif;
				$targetFile=((!empty($files->qpic['tmp_name']) and @move_uploaded_file($files->qpic['tmp_name'], $targetFile))? $targetFile: $theQuest->qpic);
				$ezDb->query("UPDATE `mat_questions` SET `question`='$posts->questiontext',`options`='".json_encode($posts->options)."', `answer`='$posts->theAnswer', `filetype`='$posts->filetype' ,`parentid`='$matid', `qpic`='$targetFile' WHERE `id`='$gets->question' AND `parentid`='$matid' AND `qtype`='$posts->qtype';");
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>A Course lesson assessment question detail was successfully updated.</p></div>';
			    /*Mail controller*/
			    if (!empty($mailInject->students)):
				    $mailInject->title="Course Assessment Question Update";
				    $mailInject->text="A question was successfully edited in this course material assessment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
				    $mailInject->html='A question was successfully edited in this course material assessment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
				    $mailInject->area='course assessment questions';
				    $mailInject->prevMsg='<p>A Course lesson assessment question detail was successfully updated.</p>';
				    require_once "mail_students.php";
			    endif;
				$material->questions=getOQuestions($matid);
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
		elseif($posts->qtype=='theory'):
			if( empty(trim($posts->theAnswer)) ):
				$err++;
				$fail.='<p>Enter the new exam answer!</p>';
			endif;
			if( !in_array($posts->fileupload, array("0","1")) ):
				$err++;
				$fail.='<p>Kindly select a vaild Picture Status!</p>';
			endif;
			if( $err==0 ):
				$posts->theAnswer=nl2br2($posts->theAnswer);
				$posts->theAnswer=tb2sp2($posts->theAnswer);
				$posts->question=nl2br2($posts->question);
				$posts->question=tb2sp2($posts->question);
				if ( !file_exists("$targetDir") ) :
			        mkdir("$targetDir", 0777, true);
			    endif;
			    $filAr=explode(".", $files->qpic['name']);
			    $tkend=end($filAr);
			    $targetFile=($targetDir."matqpic_$courseid".getToken(5).$theQuest->id.".$tkend");
				if(file_exists($theQuest->qpic) and !empty($files->qpic['tmp_name'])):
					@unlink($theQuest->qpic);
				endif;
				$targetFile=((!empty($files->qpic['tmp_name']) and @move_uploaded_file($files->qpic['tmp_name'], $targetFile))? $targetFile: $theQuest->qpic);
				//id	question	options	answer	courseid	addedby	dateadded
				$ezDb->query("UPDATE `mat_questions` SET `question`='$posts->questiontext',`options`='".json_encode(array())."',`answer`='$posts->theAnswer', `filetype`='$posts->filetype' ,`parentid`='$matid', `fileupload`='$posts->fileupload', `qpic`='$targetFile' WHERE `id`='$gets->question' AND `parentid`='$matid' AND `qtype`='$posts->qtype';");

				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>A Course Lesson assessment question detail was successfully updated.</p></div>';
			    /*Mail controller*/
			    if (!empty($mailInject->students)):
				    $mailInject->title="Course Assessment Question Update";
				    $mailInject->text="A question was successfully edited in this course material assessment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
				    $mailInject->html='A question was successfully edited in this course material assessment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
				    $mailInject->area='course assessment questions';
				    $mailInject->prevMsg='<p>A Course lesson assessment question detail was successfully updated.</p>';
				    require_once "mail_students.php";
			    endif;
				$material->questions=getOQuestions($matid);
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
		endif;
	}
	/*Deleting Question*/
	if ( in_array($sitePage, array("material_mgt")) && ($requestMethod == 'GET') && isset($Site["get"]['delete_question']) && $Site["get"]['delete_question']=='true') {
		$theQuest="";
		if( empty(trim($gets->question)) or empty($theQuest=$ezDb->get_row("SELECT * FROM `mat_questions` WHERE `id`='$gets->question' AND `parentid`='$matid'"))):
			$err++;
			$fail.='<p>The selected question does not exist!</p>';
		endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course material!</p>';
		endif;
		if ($err==0) {
			if(file_exists($theQuest->qpic)):
				@unlink($theQuest->qpic);
			endif;
			$ezDb->query("DELETE FROM `mat_questions` WHERE `id`='$gets->question' AND `parentid`='$matid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>A question was successfully removed from this lesson material.</p></div>';
		    /*Mail controller*/
		    if (!empty($mailInject->students)):
			    $mailInject->title="Course Assessment Question Deletion";
			    $mailInject->text="A question was successfully removed from this course assessment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
			    $mailInject->html='A question was successfully removed from this course assessment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
			    $mailInject->area='course assessment questions';
			    $mailInject->prevMsg='<p>A question was successfully removed from this lesson material.</p>';
			    require_once "mail_students.php";
		    endif;
			$material->questions=getOQuestions($matid);
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$material->questions=getOQuestions($matid);
	if (!empty($material->questions)):
      foreach ($material->questions as $key => $question) :
        $material->questions[$key]->answer1=str_replace("<br/>", "\n", $question->answer);
      endforeach;
    endif;
	$material->course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='$material->courseid';");
	$material->course->outlines=$ezDb->get_results("SELECT * FROM `outlines` WHERE `courseid`='$material->courseid'");
	/*if (!empty($material->course->outlines) and is_array($material->course->outlines)) {
		foreach ($material->course->outlines as $key => $value) {
			$material->course->outlines[$key]->description1=str_replace("<br/>", "\n", testInputReverse($value->description));
		}
	}*/
	$material->course->instructions=testInputReverse(trim($material->course->instructions));
	$material->course->description=testInputReverse(trim($material->course->description));
    $material->course->title=testInputReverse(trim($material->course->title));
    $material->course->category=testInputReverse(trim($material->course->category));
    $material->description=testInputReverse(trim($material->description));
	$material->title=testInputReverse(trim($material->title));

}else{
	redirect("courses");
}

$smarty->assign("material", $material);
