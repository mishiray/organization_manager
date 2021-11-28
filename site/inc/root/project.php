<?php 


if( !in_array( $userinfo->userrole, array('level0','level1','level5','level2', 'level6')) ):
	redirect("projects");
endif;
$id=(!empty($gets->id)? $gets->id: "");
$project=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `token`='$id'");
if (!empty($project)) {
	$project->images=@json_decode($project->images);
	if ( in_array($sitePage, array("project")) && ($requestMethod == 'GET') && !empty($gets->evt)  && !empty($gets->id) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("projects");
		endif;
		$project=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `token`='$gets->id'");
		$project->images=@json_decode($project->images);
			// $Store->owner_detail=@json_decode($Store->owner_detail);
		if( !empty($project) and in_array($gets->evt, array('show', 'hide', 'delete','feature')) ){
			$evtMsg=($gets->evt=='show'?'showed': ($gets->evt=='hide'?'hidden': ($gets->evt=='feature'?'featured as ongoing project':'deleted')));
			if($gets->evt=='show' and $project->show=='1'):
				$err++;
				$fail.='<p class="border border-danger p-1 rounded">This project had already been showed click projects in the menu to refresh</p>';
			endif;
			if($gets->evt=='hide' and $project->show=='0'):
				$err++;
				$fail.='<p class="border border-danger p-1 rounded">This project had already been hidden click projects in the menu to refresh</p>';
			endif;
			if($gets->evt=='feature' and $project->set_featured=='1'):
				$err++;
				$fail.='<p class="border border-danger p-1 rounded">This project had already been featured as ongoing project click projects in the menu to refresh</p>';
			endif;
			if($err==0):
				$sqlQ="";
				if($gets->evt=='show'):
					$project->show=1;
					$sqlQ="UPDATE `construction_projects` SET `show`='1' WHERE `token`='$gets->id'";
					$eventLog='status-showed';
				elseif($gets->evt=='hide'):
					$project->show=0;
					$sqlQ="UPDATE `construction_projects` SET `show`='0' WHERE `token`='$gets->id'";
					$eventLog='status-hidden';
				elseif($gets->evt=='feature'):
					$sqlQ="UPDATE `construction_projects` SET `set_featured`='0'";
					$ezDb->query($sqlQ);
					$sqlQ="UPDATE `construction_projects` SET `set_featured`='1' WHERE `token`='$gets->id'";
					$project->set_featured=1;
					$eventLog='status-set-as-featured';
				elseif($gets->evt=='delete'):
					$sqlQ="DELETE FROM `construction_projects` WHERE `token`='$gets->id'";
					if (!empty($project->images) and is_array($project->images)) :
						foreach ($project->images as $value):
							if (file_exists($value)):
								@unlink($value);
							endif;
						endforeach;
					endif;
					$eventLog='deleted';
				endif;
      			logEvent($userinfo->email, "project-$eventLog", $userinfo->usertype, 'projects', $project);
				$ezDb->query($sqlQ);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border border-success p-1 rounded">project was successfully '.$evtMsg.'.</p></div>';
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
		}
	}

	if ( in_array($sitePage, array("project")) && ($requestMethod == 'POST') && isset($Site["post"]['editproject']) ) {
		if( !in_array( $userinfo->userrole, array('level0', 'level1')) ):
			redirect("projects");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/projects/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		$extensions2 = array('application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if ( !empty($files->img_upload['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions2)) ):
	    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 1 file for project. Only pictures and videos files is allowed.';
	        $err++;
	    endif;
		if ( !empty($files->img_upload1['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload1['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload1['tmp_name'])), $extensions2)) ):
	    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 2 file for project. Only pictures and videos files is allowed.</p>';
	        $err++;
	    endif;
		if ( !empty($files->img_upload2['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload2['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_uploa3['tmp_name'])), $extensions2)) ):
	    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 3 file for project. Only pictures and videos files is allowed.</p>';
	        $err++;
	    endif;
		if ( !empty($files->img_upload3['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload3['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions2)) ):
	    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 4 file for project. Only pictures and videos files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Enter project title please!</p>';
		endif;
		if( empty(trim($posts->description))):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Enter project detail / description please!</p>';
		endif;
		if( empty(trim($posts->category)) ):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">Enter project category please!</p>';
		endif;
		if( $err==0 ):
			if( !file_exists("$targetDir") ):
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $token= $id;
		    $targetFiles =array();

		    $targetFiles[0]= $targetDir . $token."_image.png";
		    if(!empty($files->img_upload['tmp_name'])):
		    	@unlink($project->images[0]);
		    endif;
		    $targetFiles[0]=( (!empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFiles[0]))? $targetFiles[0]: $project->images[0]);
		    $targetFiles[1]= $targetDir . $token."_image1.png";
		    if(!empty($files->img_upload1['tmp_name'])):
		    	@unlink($project->images[1]);
		    endif;
		    $targetFiles[1]=( (!empty($files->img_upload1['tmp_name']) and @move_uploaded_file($files->img_upload1['tmp_name'], $targetFiles[1]))? $targetFiles[1]: $project->images[1]);
		    $targetFiles[2]= $targetDir . $token."_image2.png";
		    if(!empty($files->img_upload2['tmp_name'])):
		    	@unlink($project->images[2]);
		    endif;
		    $targetFiles[2]=( (!empty($files->img_upload2['tmp_name']) and @move_uploaded_file($files->img_upload2['tmp_name'], $targetFiles[2]))? $targetFiles[2]: $project->images[2]);
		    $targetFiles[3]= $targetDir . $token."_image3.png";
		    if(!empty($files->img_upload3['tmp_name'])):
		    	@unlink($project->images[3]);
		    endif;
		    $targetFiles[3]=( (!empty($files->img_upload3['tmp_name']) and @move_uploaded_file($files->img_upload3['tmp_name'], $targetFiles[3]))? $targetFiles[3]: $project->images[3]);

		    $targetFiles=@json_encode($targetFiles);
		    $posts->description=testInputln($posts->description);
		    $posts->title=testInputln($posts->title);
		    $posts->category=testInputln($posts->category);
      		logEvent($userinfo->email, "project-edited", $userinfo->usertype, 'projects', $project);

		    $ezDb->query("UPDATE `construction_projects` SET `title`='$posts->title', `email`='$posts->email', `phone`='$posts->phone', `description`='$posts->description', `category`='$posts->category', `dateadded`='$dateNow', `images`='$targetFiles' WHERE `token`='$id';");
			$project=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `token`='$id'");
			$project->images=@json_decode($project->images);

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border border-success p-1 rounded"> project detail was successfully updated.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}

	$project->description2=testInputReverseln(trim($project->description));
	$project->description=testInputReverse(trim($project->description));
	$project->title2=testInputReverseln(trim($project->title));
	$project->title=testInputReverse(trim($project->title));
	$project->category2=testInputReverseln($project->category);
	$project->category=testInputReverse($project->category);


}else{
	redirect('projects');
}
$categories=$ezDb->get_col("SELECT DISTINCT `category` FROM `construction_projects`");
$smarty->assign("project", $project)->assign("categories", $categories);