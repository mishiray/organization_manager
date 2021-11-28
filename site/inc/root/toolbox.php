<?php 
//toolbox
$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) and $userinfo->active==true ):
	redirect("home");
endif;

$whereClause="";
/*Add Toolbox*/
if ( in_array($sitePage, array("toolbox")) && ($requestMethod == 'POST') && isset($Site["post"]['add_toolbox']) ) {
	$files= (object) $Site["files"];
	$targetDir="site/media/toolbox/";
	$fail0='';
	if( empty($files->material_upload['tmp_name']) ):
		$err++;
		$fail.='<p>Kindly upload a file please!</p>';
    endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter software toolbox title please!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly describe software toolbox please!</p>';
	endif;
	if ($err==0) {
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
		$targetFile = $targetDir .getToken("5") .($ezDb->get_var("SELECT IFNULL((`id`+1),'1') FROM `toolbox` ORDER BY `id` DESC LIMIT 1;")) .$files->material_upload['name'];
		$targetFile = ((!empty($files->material_upload['tmp_name']) and @move_uploaded_file($files->material_upload['tmp_name'], $targetFile))? $targetFile: "");
		$emptyDownloaded=@json_encode(array());
	    $posts->title=testInput(nl2br2($posts->title));
	    $posts->description=testInput(nl2br2($posts->description));
	    $ezDb->query("INSERT INTO `toolbox` (`title`, `description`, `file`, `downloaded`, `addedby`, `dateadded`) VALUES ('$posts->title', '$posts->description', '$targetFile', '$emptyDownloaded', '$userinfo->email', '$dateNow')");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New Software Toolbox was successfully added.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

if ( in_array($sitePage, array("toolbox")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_toolbox']) ) {
	$files= (object) $Site["files"];
	$targetDir="site/media/toolbox/";
	$fail0='';
	$toolbox=$ezDb->get_row("SELECT * FROM `toolbox` WHERE `id`='$posts->toolbox'");
	if (empty($toolbox)) :
		$err++;
		$fail.='<p>Selected software toolbox does not exist!</p>';
	endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter software toolbox title please!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly describe software toolbox please!</p>';
	endif;
	if ($err==0) {
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
		$targetFile = $targetDir .getToken("5") .($ezDb->get_var("SELECT IFNULL((`id`+1),'1') FROM `toolbox` ORDER BY `id` DESC LIMIT 1;")) .$files->material_upload['name'];
		if ( !empty($files->material_upload['tmp_name']) and @move_uploaded_file($files->material_upload['tmp_name'], $targetFile) ):
			if ( !empty($toolbox->file) and file_exists($toolbox->file)):
				@unlink($toolbox->file);
			endif;
		else:
			$targetFile= $toolbox->file;
		endif;
		//$targetFile = ((!empty($files->material_upload['tmp_name']) and @move_uploaded_file($files->material_upload['tmp_name'], $targetFile))? $targetFile: $toolbox->file);
	    $posts->title=testInput(nl2br2($posts->title));
	    $posts->description=testInput(nl2br2($posts->description));
		$ezDb->query("UPDATE `toolbox` SET `title`='$posts->title', `description`='$posts->description', `file`='$targetFile' WHERE `id`='$posts->toolbox';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Selected software toolbox was successfully updated.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$fail.'</div>';
	}
}

/*Deleting of FaQ*/
if ( in_array($sitePage, array("toolbox")) && ($requestMethod == 'GET') && isset($Site["get"]['toolbox']) && isset($Site["get"]['delete']) && $Site["get"]['delete']=='true') {

	$toolboxid=(!empty($gets->toolbox)? $gets->toolbox: "");
	$toolbox=$ezDb->get_row("SELECT * FROM `toolbox` WHERE `id`='$toolboxid'");
	if ($err==0 and !empty($toolbox)) {
		if ( !empty($toolbox->file) and file_exists($toolbox->file)) {
			@unlink($toolbox->file);
		}
		$ezDb->query("DELETE FROM `toolbox` WHERE `id`='$toolboxid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Selected Software Toolbox was successfully deleted.</p></div>';
	}
}

$toolboxes=tableRoutine("toolbox", '*', " `id`!='0' $whereClause", '`id`');
if(!empty($toolboxes) and is_array($toolboxes)){
	foreach ($toolboxes as $value) {
		$value->title1=testInputReverse(str_replace("<br/>", "\n", $value->title));
		$value->description1=testInputReverse(str_replace("<br/>", "\n", $value->description));
		$value->downloaded=@json_decode($value->downloaded);
	}
}
$smarty->assign("toolboxes", $toolboxes);