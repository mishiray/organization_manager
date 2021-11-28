<?php 

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) and $userinfo->active==true ):
	redirect("home");
endif;
/*Add Update*/
if ( in_array($sitePage, array("docupload-new")) && ($requestMethod == 'POST') && isset($Site["post"]['add_update']) ) {
	$files= (object) $Site["files"];
	$targetDir="site/media/docuploads/";
	$fail0='';
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
		$targetFile = $targetDir .getToken("5") .($ezDb->get_var("SELECT IFNULL((`id`+1),'1') FROM `docuploads` ORDER BY `id` DESC LIMIT 1;")) .$files->doc_upload['name'];
		$targetFile = ((!empty($files->doc_upload['tmp_name']) and @move_uploaded_file($files->doc_upload['tmp_name'], $targetFile))? $targetFile: "");
		$emptyDownloaded=@json_encode(array());
	    $posts->title=testInput(nl2br2($posts->title));
	    $posts->content=testInput(nl2br2($posts->content));
	    $ezDb->query("INSERT INTO `docuploads` (`title`, `description`, `file`, `downloaded`, `addedby`, `category`,`active`, `dateadded`) VALUES ('$posts->title', '$posts->content', '$targetFile', '$emptyDownloaded', '$userinfo->email', '$posts->category', 1,'$dateNow')");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New update was successfully added.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}