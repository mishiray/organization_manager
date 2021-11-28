<?php
if( !in_array( $userinfo->userrole, array('level0','level1','level2'))):
	redirect("home");
endif;

$fail="";
$err=0;
if ( !empty($posts->triggers) and $posts->triggers=='add_agric') {
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/i/";
	$targetDir2="site/media/d/";
	
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream");
	if( empty(trim($posts->name))):
		$err++;
		$fail.='<p>Kindly enter agriculture land name!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly enter project description or content!</p>';
	endif;
	if(empty(trim($posts->discount)) ):
		$posts->discount = 0;
	endif;
	if(empty(trim($posts->totalplot)) ):
		$posts->totalplot = 0;
	endif;
	if(empty(trim($posts->totalplotsold)) ):
		$posts->totalplotsold = 0;
	endif;
	if(empty(trim($posts->sizeplot)) ):
		$posts->sizeplot = 450;
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
	if (!empty($files->plot_image_upload['tmp_name']) and !in_array(strtolower(getMime($files->plot_image_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded plot image is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
	endif;
	if( $err==0 ):
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `agriculture` ORDER BY `id` DESC LIMIT 1;");
		
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
		endif;
		if ( !file_exists("$targetDir2") ) :
	        mkdir("$targetDir2", 0777, true);
		endif;

		$extn = pathinfo($files->img_upload['name'], PATHINFO_EXTENSION);
		$extn2 = pathinfo($files->img_upload2['name'], PATHINFO_EXTENSION);
		$extn3 = pathinfo($files->img_upload3['name'], PATHINFO_EXTENSION);
		$extn4 = pathinfo($files->doc_upload['name'], PATHINFO_EXTENSION);
		$extn5 = pathinfo($files->form_upload['name'], PATHINFO_EXTENSION);
		$extn6 = pathinfo($files->plot_image_upload['name'], PATHINFO_EXTENSION);

		$targetFile = $targetDir.$token.'main.'.$extn;
		$targetFile2 = $targetDir.$token.'sec.'.$extn2;
		$targetFile3 = $targetDir.$token.'tri.'.$extn3;
		$docs = $targetDir2.$token.'doc.'.$extn4;
		$forms = $targetDir2.$token.'form.'.$extn5;
		$plot_image = $targetDir2.$token.'plot.'.$extn6;

	
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

		if(!empty($_FILES["doc_upload"]["name"])){
			if (move_uploaded_file($_FILES['doc_upload']['tmp_name'], $docs)) {

			}else{
				$fail .=  "Failed to upload document";
			}
		}else{
			$docs = '';
		}

		if(!empty($_FILES["form_upload"]["name"])){
			if (move_uploaded_file($_FILES['form_upload']['tmp_name'], $forms)) {

			}else{
				$fail .=  "Failed to upload form";
			}
		}else{
			$forms = '';
		}

		if(!empty($_FILES["plot_image_upload"]["name"])){
			if (move_uploaded_file($_FILES['plot_image_upload']['tmp_name'], $plot_image)) {

			}else{
				$fail .=  "Failed to upload Plot Image";
			}
		}else{
			$plot_image = '';
		}

		$token= getToken(8);

		//arrange prices
		$land = new stdClass();
		$landr = new stdClass();
		$lease = new stdClass();
		
		$land->_350 = $posts->land_350;
		$land->_450 = $posts->land_450;
		$land->_600 = $posts->land_600;

		$landr->_350 = $posts->landr_350;
		$landr->_450 = $posts->landr_450;
		$landr->_600 = $posts->landr_600;

		$lease->_350 = $posts->lease_350;
		$lease->_450 = $posts->lease_450;
		$lease->_600 = $posts->lease_600;


		$query ="INSERT INTO `agriculture` (
		 `token`,
		 `name`,
		 `title`, 
		 `location`, 
		 `landmark`, 
		 `images`, 
		 `images2`, 
		 `images3`, 
		 `content`,
		 `neighbourhood`,
		 `infrastructure`,
		 `type`, 
		 `phone`, 
		 `totalplot`, 
		 `totalsold`,
		 `sizeplot`, 
		 `documents`, 
		 `forms`, 
		 `plot_image`, 
		 `land`, 
		 `landr`,
		 `lease`,
		 `amt_survey`, 
		 `legal_fee`, 
		 `other`, 
		 `release_date`, 
		 `promo_desc`, 
		 `discount`, 
		 `active`, 
		 `dateadded`) VALUES (
			'$token',
			'$posts->name', 
			'$posts->title', 
			'$posts->location', 
			'$posts->landmark', 
			'$targetFile', 
			'$targetFile2', 
			'$targetFile3', 
			'$posts->description', 
		 	'$posts->neighbourhood', 
		 	'$posts->infrastructure', 
			'$posts->category', 
			'$posts->phone', 
			'$posts->totalplot', 
			'$posts->totalplotsold', 
			'$posts->sizeplot', 
			'$docs',
			'$forms',
			'$plot_image',
			'".json_encode($land)."', 
			'".json_encode($landr)."',
			'".json_encode($lease)."', 
			'$posts->amtsurvey', 
			'$posts->amtlegal', 
			'$posts->other', 
			'$posts->reldate', 
			'$posts->promodesc', 
			'$posts->discount', 
			'$posts->active', 
			'$dateNow')";
		if($ezDb->query($query)):
			$allocations_array = [];
			for($i = 1; $i <= $posts->totalplot; $i++){
				$allocate = new stdClass();
				$allocate->id = $i;
				$allocate->token = '';
				$allocate->client = '';
				$allocate->coords = '';
				$allocate->location = '';
				$allocate->shape = '';
				$allocate->type = '';
				$allocate->dateadded = '';
				array_push($allocations_array, $allocate);
			}
			$query = "INSERT INTO `plot_allocation` (`project_token`, `allocation`, `dateadded`) VALUES
												   ('$token','".json_encode($allocations_array)."','$dateNow')";
			$ezDb->query($query);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> The property has been successfully added.</p></div>';
			$report=$ezDb->get_row("SELECT * FROM `agriculture` WHERE `token`='$token';");
      		logEvent($userinfo->email, "new-agric-property-added", $userinfo->usertype, 'agriculture', $report);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to add the property. kindly re-try</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}