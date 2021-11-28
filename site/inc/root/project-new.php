<?php 

if( !in_array( $userinfo->userrole, array('level0','level1','level2','level3')) ):
	redirect("projects");
endif;
//echo 'okay';

/*	id	token	title	email	amount	url	category	description	otherdetails	addedby	dateadded	show	images*/

if ( in_array($sitePage, array("project-new")) && ($requestMethod == 'POST') && isset($Site["post"]['addproject']) ) {
	//echo 'okay-2';
	if( !in_array( $userinfo->userrole, array('level0','level1','level2')) ):
		redirect("projects");
	endif;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/projects/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
	$extensions2 = array('application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
	if ( empty($files->img_upload['tmp_name']) or (!in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions2)) ):
    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 1 file for project. Only pictures and videos files is allowed.</p>';
        $err++;
    endif;
	if ( !empty($files->img_upload1['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload1['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload1['tmp_name'])), $extensions2)) ):
    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 2 file for project. Only pictures and videos files is allowedd.</p>';
        $err++;
    endif;
	if ( !empty($files->img_upload2['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload2['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload2['tmp_name'])), $extensions2)) ):
    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 3 file for project. Only pictures and videos files is allowed.</p>';
        $err++;
    endif;
	if ( !empty($files->img_upload3['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload3['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload3['tmp_name'])), $extensions2)) ):
    	$fail .= '<p class="border border-danger p-1 rounded">Kindly upload a valid image 4 file for project. Only pictures and videos files is allowed.</p>';
        $err++;
    endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p class="border border-danger p-1 rounded">Enter project name or title please!</p>';
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
	    $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `construction_projects` ORDER BY `id` DESC LIMIT 1;");
	    $targetFiles =array();
	    $end[0]=end(explode(".", $files->img_upload['name']));
	    $end[1]=end(explode(".", $files->img_upload1['name']));
	    $end[2]=end(explode(".", $files->img_upload2['name']));
	    $end[3]=end(explode(".", $files->img_upload3['name']));

	    $targetFiles[0]= $targetDir . $token."_image.".$end[0];
	    $targetFiles[0]=( (!empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFiles[0]))? $targetFiles[0]: "");
	    $targetFiles[1]= $targetDir . $token."_image1.".$end[1];
	    $targetFiles[1]=( (!empty($files->img_upload1['tmp_name']) and @move_uploaded_file($files->img_upload1['tmp_name'], $targetFiles[1]))? $targetFiles[1]: "");
	    $targetFiles[2]= $targetDir . $token."_image2.".$end[2];
	    $targetFiles[2]=( (!empty($files->img_upload2['tmp_name']) and @move_uploaded_file($files->img_upload2['tmp_name'], $targetFiles[2]))? $targetFiles[2]: "");
	    $targetFiles[3]= $targetDir . $token."_image3.".$end[3];
	    $targetFiles[3]=( (!empty($files->img_upload3['tmp_name']) and @move_uploaded_file($files->img_upload3['tmp_name'], $targetFiles[3]))? $targetFiles[3]: "");

	    $targetFiles=@json_encode($targetFiles);
	    $posts->description=testInputln($posts->description);
	    $posts->title=testInputln($posts->title);
	    $posts->category=testInputln($posts->category);
	    $ezDb->query("UPDATE `construction_projects` SET `set_featured`=0");
	    if($ezDb->query("INSERT INTO `construction_projects` (`token`, `title`, `email`, `phone`, `description`, `category`, `addedby`, `dateadded`, `show`, `images`, `set_featured`) VALUES ('$token', '$posts->title', '$posts->email', '$posts->phone', '$posts->description', '$posts->category', '$userinfo->email', '$dateNow', '1', '$targetFiles', '1')")){
			$project=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `token`='$token';");
			logEvent($userinfo->email, "new-project-added", $userinfo->usertype, 'projects', $project);
	  
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Project was successfully added.</p></div>';
		}else{
			
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Project was not successfully added.</p></div>';
		}
	 else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
$categories=$ezDb->get_col("SELECT DISTINCT `category` FROM `construction_projects`");
$smarty->assign("categories", $categories);