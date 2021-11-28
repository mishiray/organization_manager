<?php
if( !in_array( $userinfo->userrole, array('level0','level1','level2'))):
	redirect("home");
endif;

$fail="";
$err=0;
if ( !empty($posts->triggers) and $posts->triggers=='add_promo') {
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/i/";
	$targetDir2="site/media/d/";
	
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream");
	if( empty(trim($posts->name))):
		$err++;
		$fail.='<p>Kindly enter promo name!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly enter promo description or content!</p>';
	endif;
	if( !empty(trim($posts->description)) ):
		$posts->description = addslashes( $posts->description );  
	endif;
	if (!empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded project logo is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
	endif;
	
	if (!empty($files->img_upload2['tmp_name']) and !in_array(strtolower(getMime($files->img_upload2['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded project logo is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
	endif;
	if (!empty($files->img_upload3['tmp_name']) and !in_array(strtolower(getMime($files->img_upload3['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded project logo is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;
	if( $err==0 ):
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `projects` ORDER BY `id` DESC LIMIT 1;");
		
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
		endif;
		if ( !file_exists("$targetDir2") ) :
	        mkdir("$targetDir2", 0777, true);
		endif;

		$extn = pathinfo($files->img_upload['name'], PATHINFO_EXTENSION);
		$extn2 = pathinfo($files->img_upload2['name'], PATHINFO_EXTENSION);
		$extn3 = pathinfo($files->img_upload3['name'], PATHINFO_EXTENSION);

		$targetFile = $targetDir.$token.'main.'.$extn;
		$targetFile2 = $targetDir.$token.'sec.'.$extn2;
		$targetFile3 = $targetDir.$token.'tri.'.$extn3;

	
		if(!empty($_FILES["img_upload"]["name"])){
			if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $targetFile)) {

			}else{
				$fail .=  "Failed to upload image";
			}
		}else{
			$targetFile = '';
		}

		if(!empty($_FILES["img_upload2"]["name"])){
			if (move_uploaded_file($_FILES['img_upload2']['tmp_name'], $targetFile2)) {

			}else{
				$fail .=  "Failed to upload image";
			}
		}else{
			$targetFile2 = '';
		}

		if(!empty($_FILES["img_upload3"]["name"])){
			if (move_uploaded_file($_FILES['img_upload3']['tmp_name'], $targetFile3)) {

			}else{
				$fail .=  "Failed to upload image";
			}
		}else{
			$targetFile3 = '';
		}

		$token= getToken(8);
		$query ="INSERT INTO `promos` (
		 `token`,
		 `name`,
		 `image`, 
		 `image2`, 
		 `image3`, 
		 `description`,
		 `type`, 
		 `amount`, 
		 `status`, 
		 `addedby`,
		 `dateadded`) VALUES (
			'$token',
			'$posts->name', 
			'$targetFile', 
			'$targetFile2', 
			'$targetFile3', 
			'$posts->description',
			'$posts->category', 
			'$posts->amount', 
			'$posts->active', 
			'$userinfo->email', 
			'$dateNow')";
		if($ezDb->query($query)):
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> The Promo has been successfully added.</p></div>';
			$report=$ezDb->get_row("SELECT * FROM `promos` WHERE `token`='$token';");
      		logEvent($userinfo->email, "new-promo-added", $userinfo->usertype, 'promos', $report);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to add the promo. kindly re-try</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}