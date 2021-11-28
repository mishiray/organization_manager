<?php 

$cat = (!empty($gets->category)) ? "$gets->category" : "";
//$category = (!empty($gets->category)) ? "`category` = '$gets->category'	" : "";
$whereClause = " ";



if ( in_array($sitePage, array("companydocs")) && ($requestMethod == 'GET') && isset($Site["get"]['docupload']) && isset($Site["get"]['delete']) && $Site["get"]['delete']=='true') {
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

$docuploads=$ezDb->get_results("SELECT * FROM `companydocs` WHERE `id` != 0 $whereClause  ORDER BY `id` DESC");
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
		if (in_array($userinfo->email, $value->downloaded)) {
			$value->acknowledged = true;
		}else{
			$value->acknowledged = false;
		}
	}
}
$smarty->assign("docuploads", $docuploads)->assign("categories", array("minute"=>"Minute", "meilestone"=>"Milestone", "project document"=> "Project Document"))->assign("category",$cat);