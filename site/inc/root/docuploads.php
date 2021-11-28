<?php 

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;

$cat = (!empty($gets->category)) ? "$gets->category" : "";
$category = (!empty($gets->category)) ? "`category` = '$gets->category'	" : "";
$whereClause = "AND $category";


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

if ( in_array($sitePage, array("docuploads")) && ($requestMethod == 'GET') && isset($Site["get"]['docupload']) && isset($Site["get"]['delete']) && $Site["get"]['delete']=='true') {
	$docuploadid=(!empty($gets->docupload)? $gets->docupload: "");
	$docupload=$ezDb->get_row("SELECT * FROM `docuploads` WHERE `id`='$docuploadid'");
	if ($err==0 and !empty($docupload)) {
		if ( !empty($docupload->file) and file_exists($docupload->file)) {
			@unlink($docupload->file);
		}
		$ezDb->query("DELETE FROM `docuploads` WHERE `id`='$docuploadid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Selected update was successfully deleted.</p></div>';
	}
}

$docuploads=$ezDb->get_results("SELECT * FROM `docuploads` WHERE `id` != 0 $whereClause  ORDER BY `id` DESC");
if(!empty($docuploads) and is_array($docuploads)){
	foreach ($docuploads as $value) {
		$value->title1=testInputReverse(str_replace("<br/>", "\n", $value->title));
		$value->title=testinputReverse($value->title);
		$value->contentln=testinputReverse($value->description);
		$value->content=testinputReverse($value->description);
		$value->content_stripe=stripeInputReverse($value->content);
		$value->content_stripe=str_replace("&quot;", "\"", $value->content_stripe);
		$value->content_stripe=str_replace("&nbsp;", " ", $value->content_stripe);
		$value->downloaded=@json_decode($value->downloaded);
	}
}
$smarty->assign("docuploads", $docuploads)->assign("categories", array("minute"=>"Minute", "meilestone"=>"Milestone", "project document"=> "Project Document"))->assign("category",$cat);