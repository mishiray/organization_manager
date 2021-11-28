<?php

$token=(!empty($gets->item)? $gets->item: "");


if ( !empty($token) ) {

	$product=$ezDb->get_row("SELECT *  FROM `promos` WHERE `id`='$token'");
	$product->subscription=$ezDb->get_results("SELECT * FROM `promos_subscription` WHERE `promo_token`='$product->token'");
		
	if(!empty($posts->triggers) and $posts->triggers=='edit_promo'){

		$estateItem=$ezDb->get_row("SELECT * FROM `promos` WHERE `id`='$token' ");

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
			if ( !file_exists("$targetDir") ) :
				mkdir("$targetDir", 0777, true);
			endif;
			if ( !file_exists("$targetDir2") ) :
				mkdir("$targetDir2", 0777, true);
			endif;
			
			$extn=end(explode(".", $files->img_upload['name']));
			$extn2=end(explode(".", $files->img_upload2['name']));
			$extn3=end(explode(".", $files->img_upload3['name']));

			$targetFile = $targetDir.'main_'.$posts->name.'.'.$extn;
			$targetFile2 = $targetDir .'sec_'.$posts->name.'.'.$extn2;
			$targetFile3 = $targetDir .'tri_'.$posts->name.'.'.$extn3;

		
			if(!empty($_FILES["img_upload"]["name"])){
				if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $targetFile)) {

				}else{
					$fail .= "Failed to upload image";
				}
			}else{
				$targetFile = $estateItem->image;
			}

			if(!empty($_FILES["img_upload2"]["name"])){
				if (move_uploaded_file($_FILES['img_upload2']['tmp_name'], $targetFile2)) {

				}else{
					$fail .= "Failed to upload image";
				}
			}else{
				$targetFile2 = $estateItem->image2;
			}

			if(!empty($_FILES["img_upload3"]["name"])){
				if (move_uploaded_file($_FILES['img_upload3']['tmp_name'], $targetFile3)) {

				}else{
					$fail .= "Failed to upload image";
				}
			}else{
				$targetFile3 = $estateItem->image3;
			}

			$query ="UPDATE `promos` 
			SET 
			`name` ='$posts->name',
			`image` ='$targetFile', 
			`image2`= '$targetFile2',
			`image3`='$targetFile3',  
			`description`='$posts->description', 
			`type`= '$posts->category', 
			`amount`= '$posts->amount', 
			`dateupdated`='$dateNow',  
			`status`= '$posts->status'
			WHERE `id`='$token'";;
			if($ezDb->query($query)):
				$product=$ezDb->get_row("SELECT * FROM `promos` WHERE `id`='$token';");
				logEvent($userinfo->email, "updated-promo", $userinfo->usertype, 'promos', $product);
				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> The property has been successfully updated.</p></div>';
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to update the property. kindly re-try</p></div>';
			endif;
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

	}

	if (!empty($posts->triggers) and $posts->triggers=='delete_promo') {
		if ($err==0) {
			$ezDb->query("DELETE FROM `promos` WHERE `id`='$token';");
			logEvent($userinfo->email, "deleted-promo", $userinfo->usertype, 'promos', $product);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project was successfully deleted.</p></div>';
			$site = 'promos';
			header('Location: '.$site);
		}
	}
}else{
}
$smarty->assign("item", $product);
	
