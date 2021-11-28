<?php 

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;

$id=(!empty($gets->id)? $gets->id: "");

$docupload=$ezDb->get_row("SELECT * FROM `docuploads` WHERE `id`='$gets->id'");
if (empty($docupload)) {
	redirect("docuploads");
}

if ( in_array($sitePage, array("docupload")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_update']) ) {
	$files= (object) $Site["files"];
	$targetDir="site/media/docuploads/";
	$fail0='';
	if (empty($docupload)) :
		$err++;
		$fail.='<p>Selected update does not exist!</p>';
	endif;
	/*if( empty($files->doc_upload['tmp_name']) ):
		$err++;
		$fail.='<p>Kindly upload a file please!</p>';
    endif;*/
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter update title please!</p>';
	endif;
	if (empty(trim($posts->category)) || !in_array($posts->category, array("minute", "milestone", "project document"))):
		$err++;
		$fail.='<p>Select a valid category!</p>';
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p>Kindly enter upload briefing please!</p>';
	endif;
	if ($err==0) {
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
		$targetFile = $targetDir .getToken("5") . ($gets->id) .$files->doc_upload['name'];
		if ( !empty($files->doc_upload['tmp_name']) and @move_uploaded_file($files->doc_upload['tmp_name'], $targetFile) ):
			if ( !empty($docupload->file) and file_exists($docupload->file)):
				@unlink($docupload->file);
			endif;
		else:
			$targetFile= $docupload->file;
		endif;
		//$targetFile = ((!empty($files->material_upload['tmp_name']) and @move_uploaded_file($files->material_upload['tmp_name'], $targetFile))? $targetFile: $toolbox->file);
	    $posts->title=testInput(nl2br2($posts->title));
	    $posts->content=testInput(nl2br2($posts->content));
		$ezDb->query("UPDATE `docuploads` SET `title`='$posts->title', `description`='$posts->content', `file`='$targetFile' WHERE `id`='$gets->id';");
		$docupload=$ezDb->get_row("SELECT * FROM `docuploads` WHERE `id`='$gets->id'");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Selected update was successfully updated.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$fail.'</div>';
	}
}

/*Deleting of Upload*/
if ( in_array($sitePage, array("docupload")) && ($requestMethod == 'GET') && isset($Site["get"]['id']) && isset($Site["post"]['delete_upload']) && $Site["get"]['delete']=='true') {
	$docupload=$ezDb->get_row("SELECT * FROM `docuploads` WHERE `id`='$id'");
	if ($err==0 and !empty($docupload)) {
		if ( !empty($docupload->file) and file_exists($docupload->file)) {
			@unlink($docupload->file);
		}
		$ezDb->query("DELETE FROM `docuploads` WHERE `id`='$id';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Selected update was successfully deleted.</p></div>';
	}
}

$docupload->title1=testInputReverse(str_replace("<br/>", "\n", $docupload->title));
$docupload->title=testinputReverse($docupload->title);
$docupload->contentln=testinputReverse($docupload->description);
$docupload->content=testinputReverse($docupload->description);
$docupload->content_stripe=stripeInputReverse($docupload->content);
$docupload->content_stripe=str_replace("&quot;", "\"", $docupload->content_stripe);
$docupload->content_stripe=str_replace("&nbsp;", " ", $docupload->content_stripe);
$docupload->downloaded=@json_decode($docupload->downloaded);

$smarty->assign("docupload", $docupload);