<?php 
$userinfo=$Site['session']['User']['userinfo'];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level4')) and $userinfo->active==true ):
	redirect("home");
endif;
$matid=(!empty($gets->id)? $gets->id: "");
$material=$ezDb->get_row("SELECT * FROM `course_material` WHERE `id`='$matid'");
if (!empty($material) and is_object($material)) {
	$mailInject=new stdClass;
	$mailInject->students=$ezDb->get_results("SELECT `email`, CONCAT(`firstname`, ' ', `lastname`) AS `student` FROM `userprofile` WHERE `email` IN (SELECT DISTINCT `user` FROM `registered_courses` WHERE `courseid`='$material->courseid');");

	// Get Assignment
	// Manage & Edit & Delete Assignment

	$material->course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='$material->courseid';");
    $material->course->instructors=(!empty($material->course->instructors)? @json_decode($material->course->instructors): array());
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

	/*Add Assignment*/
	if ( in_array($sitePage, array("material_ass")) && ($requestMethod == 'POST') && isset($Site["post"]['add_question']) ) {
		$files= (object) $Site["files"];
		$targetDir="site/media/assignment_q/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		$extensions2 = array('application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter the new assignment question or title!</p>';
		endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course assignment!</p>';
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
		if( empty(trim($posts->description)) ):
			$err++;
			$fail.='<p>Enter the new assignment description or guide!</p>';
		endif;
		if( $err==0 ):
			$posts->description=nl2br2($posts->description);
			$posts->title=nl2br2($posts->title);
			$posts->description=tb2sp2($posts->description);
			$posts->title=tb2sp2($posts->title);
			if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;
		    // $targetFile="";
		    $filAr=explode(".", $files->qpic['name']);
		    $tkend=end($filAr);
		    $targetFile=($targetDir."matasspic_$material->id$material->courseid".getToken(5).$ezDb->get_var("SELECT IFNULL(`id`+1, 1) FROM `mat_assignment` ORDER BY `id` DESC LIMIT 1").".$tkend");
			if(file_exists($targetFile)):
				@unlink($targetFile);
			endif;
			$targetFile=((!empty($files->qpic['tmp_name']) and @move_uploaded_file($files->qpic['tmp_name'], $targetFile))? $targetFile: "");
			//id	question	options	answer	courseid	addedby	dateadded
			$ezDb->query("INSERT INTO `mat_assignment` (`title`,`description`, `matid`,`courseid`,`addedby`,`dateadded`, `qpic`, `filetype`) VALUES ('$posts->title','$posts->description','$matid', '$material->courseid','$userinfo->email','$dateNow', '$targetFile', '$posts->filetype')");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Course lesson material quesion was successfully added.</p></div>';
		    /*Mail controller*/
		    if (!empty($mailInject->students)):
			    $mailInject->title="Course Assignment Question Update";
			    $mailInject->text="A question was successfully added in this course material assignment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
			    $mailInject->html='A question was successfully added in this course material assignment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
			    $mailInject->area='course assignment questions';
			    $mailInject->prevMsg='<p>A Course lesson assignment question detail was successfully added.</p>';
			    require_once "mail_students.php";
		    endif;
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}

	/*Editing Assignment*/
	if ( in_array($sitePage, array("material_ass")) && ($requestMethod == 'POST') && !empty($gets->question) && isset($posts->edit_question)) {
		$files= (object) $Site["files"];
		$targetDir="site/media/questions/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		$extensions2 = array('application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter the new assignment question or title!</p>';
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
		if( empty(trim($posts->description)) ):
			$err++;
			$fail.='<p>Enter the assignment description or guide!</p>';
		endif;
	    $theQuest="";
		if( empty(trim($gets->question)) or empty($theQuest=$ezDb->get_row("SELECT * FROM `mat_assignment` WHERE `id`='$gets->question' AND `matid`='$matid' AND `courseid`='$material->courseid'"))):
			$err++;
			$fail.='<p>The selected question does not exist!</p>';
		endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course assignment!</p>';
		endif;
		if( $err==0 ):
			$posts->description=nl2br2($posts->description);
			$posts->title=nl2br2($posts->title);
			$posts->description=tb2sp2($posts->description);
			$posts->title=tb2sp2($posts->title);
			if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $filAr=explode(".", $files->qpic['name']);
		    $tkend=end($filAr);
		    $targetFile=($targetDir."matasspic_$material->id$material->courseid".getToken(5).$theQuest->id.".$tkend");
			if(file_exists($theQuest->qpic) and !empty($files->qpic['tmp_name'])):
				@unlink($theQuest->qpic);
			endif;
			$targetFile=((!empty($files->qpic['tmp_name']) and @move_uploaded_file($files->qpic['tmp_name'], $targetFile))? $targetFile: $theQuest->qpic);
			//id	question	options	answer	courseid	addedby	dateadded
			$ezDb->query("UPDATE `mat_assignment` SET `title`='$posts->title', `description`='$posts->description', `qpic`='$targetFile', `filetype`='$posts->filetype' WHERE `id`='$gets->question' AND `matid`='$matid' AND `courseid`='$material->courseid';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>A Course Lesson assignment detail was successfully updated.</p></div>';
		    /*Mail controller*/
		    if (!empty($mailInject->students)):
			    $mailInject->title="Course Assignment Question Update";
			    $mailInject->text="A question was successfully updated in this course material assignment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
			    $mailInject->html='A question was successfully updated in this course material assignment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
			    $mailInject->area='course assignment questions';
			    $mailInject->prevMsg='<p>A Course lesson assignment question detail was successfully updated.</p>';
			    require_once "mail_students.php";
		    endif;
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	/*Deleting Assignment*/
	if ( in_array($sitePage, array("material_ass")) && ($requestMethod == 'GET') && isset($Site["get"]['delete_question']) && $Site["get"]['delete_question']=='true') {
		$theQuest="";
		if( empty(trim($gets->question)) or empty($theQuest=$ezDb->get_row("SELECT * FROM `mat_assignment` WHERE `id`='$gets->question' AND `matid`='$matid' AND `courseid`='$material->courseid'"))):
			$err++;
			$fail.='<p>The selected assignment does not exist!</p>';
		endif;
		if( !(in_array( $userinfo->userrole, array('level0','level1')) or ($userinfo->userrole=='level4' and in_array($userinfo->email, $material->course->instructors))) 
			or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You have no authorization to manage this course assignment!</p>';
		endif;
		if ($err==0) {
			if(file_exists($theQuest->qpic)):
				@unlink($theQuest->qpic);
			endif;
			$ezDb->query("DELETE FROM `mat_assignment` WHERE `id`='$gets->question' AND `matid`='$matid' AND `courseid`='$material->courseid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>An assignment was successfully removed from this lesson material.</p></div>';
		    /*Mail controller*/
		    if (!empty($mailInject->students)):
			    $mailInject->title="Course Assignment Question Deletion";
			    $mailInject->text="A question was successfully deleted in this course material assignment: ".$material->course->title." ($material->title ".$material->course->courseid.") ";
			    $mailInject->html='A question was successfully deleted in this course material assignment: '.$material->course->title.' ( '.$material->title.' '.$material->course->courseid.') ';
			    $mailInject->area='course assignment questions';
			    $mailInject->prevMsg='<p>A Course lesson assignment question detail was successfully deleted.</p>';
			    require_once "mail_students.php";
		    endif;
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$material->assignments=tableRoutine("mat_assignment", '*' , " `id`!=0 AND `matid`='$material->id' AND `courseid`='$material->courseid'", '`id`', 'DESC', '`id`', 10);

	if (!empty($material->assignments)):
      foreach ($material->assignments as $key => $assignment) :
        $material->assignments[$key]->description1=str_replace("<br/>", "\n", $assignment->description);
        $material->assignments[$key]->title1=str_replace("<br/>", "\n", $assignment->title);
      endforeach;
    endif;
}else{
	redirect("courses");
}

$smarty->assign("material", $material);