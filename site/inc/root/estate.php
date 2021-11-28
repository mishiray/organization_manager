<?php

$token=(!empty($gets->item)? $gets->item: "");


if ( !empty($token) ) {

	$product=$ezDb->get_row("SELECT *  FROM `projects` WHERE `id`='$token'");
		
	if(!empty($posts->triggers) and $posts->triggers=='edit_estate'){

		$estateItem=$ezDb->get_row("SELECT * FROM `projects` WHERE `id`='$token' ");

		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));

		$targetDir="site/media/i/";
		$targetDir2="site/media/d/";

		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream");
		if( empty(trim($posts->name))):
			$err++;
			$fail.='<p>Kindly enter estate name!</p>';
		endif;
		if( empty(trim($posts->description)) ):
			$err++;
			$fail.='<p>Kindly enter project description or content!</p>';
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
			$extn4=end(explode(".", $files->doc_upload['name']));
			$extn5=end(explode(".", $files->form_upload['name']));
			$extn6=end(explode(".", $files->plot_image_upload['name']));

			$targetFile = $targetDir.'main_'.$posts->name.'.'.$extn;
			$targetFile2 = $targetDir .'sec_'.$posts->name.'.'.$extn2;
			$targetFile3 = $targetDir .'tri_'.$posts->name.'.'.$extn3;
			$docs = $targetDir2 .'doc'.$posts->name.'.'.$extn4;
			$forms = $targetDir2 .'form'.$posts->name.'.'.$extn5;
			$plot_image = $targetDir2.'plot'.$posts->name.'.'.$extn6;

		
			if(!empty($_FILES["img_upload"]["name"])){
				if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $targetFile)) {

				}else{
					$fail .= "Failed to upload image";
				}
			}else{
				$targetFile = $estateItem->images;
			}

			if(!empty($_FILES["img_upload2"]["name"])){
				if (move_uploaded_file($_FILES['img_upload2']['tmp_name'], $targetFile2)) {

				}else{
					$fail .= "Failed to upload image";
				}
			}else{
				$targetFile2 = $estateItem->images2;
			}

			if(!empty($_FILES["img_upload3"]["name"])){
				if (move_uploaded_file($_FILES['img_upload3']['tmp_name'], $targetFile3)) {

				}else{
					$fail .= "Failed to upload image";
				}
			}else{
				$targetFile3 = $estateItem->images3;
			}

			if(!empty($_FILES["doc_upload"]["name"])){
				if (move_uploaded_file($_FILES['doc_upload']['tmp_name'], $docs)) {

				}else{
					$fail .= "Failed to upload document";
				}
			}else{
				$docs = $estateItem->documents;
			}

			if(!empty($_FILES["form_upload"]["name"])){
				if (move_uploaded_file($_FILES['form_upload']['tmp_name'], $forms)) {

				}else{
					$fail .= "Failed to upload form";
				}
			}else{
				$forms = $estateItem->forms;
			}
			if(!empty($_FILES["plot_image_upload"]["name"])){
				if (move_uploaded_file($_FILES['plot_image_upload']['tmp_name'], $plot_image)) {

				}else{
					$fail .= "Failed to upload plot image";
				}
			}else{
				$plot_image = $estateItem->plot_image;
			}

			$query ="UPDATE `projects` 
			SET 
			`name` ='$posts->name',
			`title` ='$posts->title', 
			`location` ='$posts->location', 
			`landmark` ='$posts->landmark', 
			`images` ='$targetFile', 
			`images2`= '$targetFile2',
			`images3`='$targetFile3',  
			`content`='$posts->description', 
			`neighbourhood`='$posts->neighbourhood', 
			`infrastructure`='$posts->infrastructure', 
			`type`= '$posts->category', 
			`phone`= '$posts->phone',  
			`totalplot`='$posts->totalplot',  
			`sizeplot`='$posts->sizeplot', 
			`documents`='$docs', 
			`forms`='$forms', 
			`plot_image`='$plot_image', 
			`amt_350sqm` = '$posts->amt1',  
			`amt_450sqm`='$posts->amt2', 
			`amt_600sqm`='$posts->amt3', 
			`amt_survey`= '$posts->amtsurvey',  
			`legal_fee` = '$posts->amtlegal',  
			`other` = '$posts->other',
			`release_date`= '$posts->reldate', 
			`promo_desc`='$posts->promodesc',  
			`discount`= '$posts->discount',   
			`commission`= '$posts->commission',   
			`manager_commission`= '$posts->manager_commission', 
			`active`= '$posts->active'
			WHERE `id`='$token'";;
			if($ezDb->query($query)):
				$product=$ezDb->get_row("SELECT * FROM `projects` WHERE `id`='$token';");
				logEvent($userinfo->email, "updated-estate", $userinfo->usertype, 'projects', $product);
				
				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> The property has been successfully updated.</p></div>';
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to update the property. kindly re-try</p></div>';
			endif;
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;

	}

	if (!empty($posts->triggers) and $posts->triggers=='delete_estate') {
		if ($err==0) {
			$ezDb->query("DELETE FROM `projects` WHERE `id`='$token';");
			logEvent($userinfo->email, "deleted-estate", $userinfo->usertype, 'projects', $product);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Project was successfully deleted.</p></div>';
			$site = 'estates';
			header('Location: '.$site);
		}
	}
}else{
}
$smarty->assign("item", $product);
	
