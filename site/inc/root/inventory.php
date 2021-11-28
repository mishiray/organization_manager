<?php

$inventoryid=(!empty($gets->id)? $gets->id: "");

$inventories=$ezDb->get_row("SELECT * from `inventory` WHERE `token` = '$inventoryid';");

	if(!empty($inventories->network_id)){
		$inventories->network=$ezDb->get_row("SELECT * from  `network` WHERE `id` = '$inventories->network_id';");
	}

	if(!empty($inventories->dept_id)){
		$inventories->department=$ezDb->get_row("SELECT * from  `department` WHERE `id` = '$inventories->dept_id';");
	}

$manufacturers=$ezDb->get_results("SELECT * from `manufacturer`;");

$departments=$ezDb->get_results("SELECT * from `department`;");

if(!empty($inventories)){
	if (!empty($posts->triggers) and $posts->triggers=='edit_inventory') {
		
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/inventory/";
	
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream",
		 "text/plain", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/rtf", "application/zip", "application/x-rar-compressed", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-tar", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", 
		 'application/annodex','application/mp4','application/ogg','application/vnd.rn-realmedia','application/x-matroska','video/3gpp','video/3gpp2','video/annodex','video/divx','video/flv','video/h264','video/mp4','video/mp4v-es','video/mpeg','video/mpeg-2','video/mpeg4','video/ogg','video/ogm','video/quicktime','video/ty','video/vdo','video/vivo','video/vnd.rn-realvideo','video/vnd.vivo','video/webm','video/x-bin','video/x-cdg','video/x-divx','video/x-dv','video/x-flv','video/x-la-asf','video/x-m4v','video/x-matroska','video/x-motion-jpeg','video/x-ms-asf','video/x-ms-dvr','video/x-ms-wm','video/x-ms-wmv','video/x-msvideo','video/x-sgi-movie','video/x-tivo','video/avi','video/x-ms-asx','video/x-ms-wvx','video/x-ms-wmx');
		if ( !empty($files->file_upload['tmp_name']) and !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
			$fail .= '<p>The uploaded file is not supported.</p>';
			$err++;
		endif;
		if( empty(trim($posts->serial)) ):
			$err++;
			$fail.='<p>Enter serial please!</p>';
		endif;
		if( empty(trim($posts->name)) ):
			$err++;
			$fail.='<p>Enter name please!</p>';
		endif;
		if( empty(trim($posts->quantity)) ):
			$err++;
			$fail.='<p>Enter quantity please!</p>';
		endif;
		if( empty(trim($posts->price)) ):
			$err++;
			$fail.='<p>Enter price please!</p>';
		endif;
		if( empty(trim($posts->description)) ):
			$err++;
			$fail.='<p>Enter description please!</p>';
		endif;
		if(!empty($posts->description)):
			$posts->description = cleanUp($posts->description);
		endif;
		if( empty(trim($posts->condition)) ):
			$err++;
			$fail.='<p>Enter item condition or description please!</p>';
		endif;
		if(!empty($posts->condition)):
			$posts->condition = cleanUp($posts->condition);
		endif;	
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2','level4'))):
			$err++;
			$fail.='<p>Your user level is not authorized to use this section!</p>';
		endif;
	
		if ($err==0) {
			$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `reports` ORDER BY `id` DESC LIMIT 1;");
			if ( !file_exists("$targetDir") ) :
				mkdir("$targetDir", 0777, true);
			endif;
			$extn=end(explode(".", $files->file_upload['name']));
			$targetFile = $targetDir.$token."file.".$extn;
			if ( !empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile) ) :
			else:
				$targetFile = $inventories->image;
			endif;
			/*id	reportid	project	title	client	clientemail	clientphone	location	user	comment	dateadded	status	lawyer_review	md_review attachment*/
			$query = "UPDATE `inventory` SET `serial_number` = '$posts->serial' , `name` = '$posts->name',`image` = '$targetFile',`quantity` = '$posts->quantity', `price` = '$posts->price' ,`manufacturer` = '$posts->manufacturer',`dept_id` = '$posts->department' ,`description` = '$posts->description', `condition_asset` = '$posts->condition', `status` = '$posts->status' WHERE `token` = '$inventoryid'";
			if($ezDb->query($query)):
				$inventories=$ezDb->get_row("SELECT * FROM `inventory` WHERE `token`='$inventoryid';");
				logEvent($userinfo->email, "updated-inventory", $userinfo->usertype, 'inventory', $inventories);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your Item was successfully updated	.</p></div>';
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;
	
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}
	
	
	if (!empty($posts->triggers) and $posts->triggers=='delete_inventory') {
		if ($err==0) {
			$ezDb->query("DELETE FROM `inventory` WHERE `token`='$inventoryid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Item was successfully deleted.</p></div>';
			//$site = 'inventories';
			//header('Location: '.$site);
			$smarty->clear_assign("inv_item");
		}
	}
}else{
	$fail = 'Not Found';
}
$smarty->assign("inv_item", $inventories)->assign("manufacturers", $manufacturers)->assign("departments", $departments);