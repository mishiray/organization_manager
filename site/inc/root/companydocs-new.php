<?php 

$userinfo=$Site["session"]["User"]["userinfo"];

/*Add Update*/
if ( in_array($sitePage, array("companydocs-new")) && ($requestMethod == 'POST') && isset($Site["post"]['add_update']) ) {
	$files= (object) $Site["files"];
	$targetDir="site/media/company-docuploads/";
	$fail0='';
	/*if( empty($files->doc_upload['tmp_name']) ):
		$err++;
		$fail.='<p>Kindly upload a file please!</p>';
    endif;*/
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p>Enter update title please!</p>';
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
	    $ezDb->query("INSERT INTO `companydocs` (`title`, `description`, `file`, `downloaded`, `addedby`, `active`, `dateadded`) VALUES ('$posts->title', '$posts->content', '$targetFile', '$emptyDownloaded', '$userinfo->email', 1,'$dateNow')");
		$everyone = $ezDb->get_col("SELECT `email` FROM `employees` WHERE `status` = 1;");
		alertUser2($everyone,0,"New company document uploaded","companydocs");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New update was successfully added.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}