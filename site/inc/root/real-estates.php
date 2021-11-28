<?php
if(!in_array($Site["session"]["User"]["userinfo"]->userrole, array("level0", "level1", "level2"))):
	redirect("home");
endif;
$catTitle=array("commercial"=>"Commercial Property", "event-center"=>"Event Centers & Venues", "house-apartment"=>"Houses & Apartments", "land-plots"=>"Land & Plots");
$forProp=array("lease"=>"Lease | Rent", "sales"=>"Sales");

if(!empty($posts->triggers) and $posts->triggers=='saveProperty'):
	$targetDir="site/media/properties/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg","image/tiff","image/webp","image/jp2");
	if( empty($posts->category) or !in_array($posts->category, array('commercial','event-center','house-apartment','land-plots')) ):
		$fail.='<p>Invalid Category Selected: select a valid category</p>';
		$err++;
	endif;
	if( empty($posts->purchase) or !in_array($posts->purchase, array('lease','sales')) ):
		$fail.='<p>Invalid Type Selected: select a valid type</p>';
		$err++;
	endif;
	if( empty($posts->company) ):
		$fail.='<p>Invalid Company Name: kindly enter company or agent name</p>';
		$err++;
	endif;
	if( empty($posts->title)):
		$fail.='<p>Invalid Property Title: property title cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->description)):
		$fail.='<p>Invalid Description: kindly add property description</p>';
		$err++;
	endif;
	if( empty($posts->worth) or !is_numeric($posts->worth)):
		$fail.='<p>Invalid Property Worth: kindly enter property worth</p>';
		$err++;
	endif;
	if( empty($posts->phone)  or !checkEmail($posts->email) or empty($posts->email)):
		$fail.='<p>Invalid Contact Details: enter email and phone number</p>';
		$err++;
	endif;
	// error_log($posts->content);
	// error_log(json_encode($files));
	if( empty($files->images1) and empty($files->images2) and empty($files->images3) and empty($files->images4) and empty($files->images5) ):
		$fail.='<p>Invalid Property Image: kindly select at least one image</p>';
		$err++;
		// error_log(0);
	else:
		$cnter=0;
		foreach ($files as $key => $value):
			// $value="files->images".$idX."['tmp_name']";
			if($key=='complogo'):
				continue;
			endif;
			// error_log(json_encode($value));
			if(empty($value['tmp_name'])):
				$cnter++;
			else:
				/*Check File Type and Size 86802*/
				if(!in_array(strtolower(getMime($value['tmp_name'])), $extensions)):
					$fail .= '<p>Invalid Property Image: Image file with the following extension is required. [JPG, JPEG, PNG, JPE, WEBP, or JP2].</p>';
	        		$err++;
				endif;
			endif;
		endforeach;
		if($cnter==5):
			$fail.='<p>Invalid Property Image: kindly select at least one image</p>';
			$err++;
			// error_log(1);
		endif;
	endif;
	// if( empty($posts->salary) or $posts->salary)<0):
	// 	$fail='<p>Invalid Salary: salary must not be less that 0</p>';
	// 	$err++;
	// endif;
	if( !empty($_FILES['complogo']['tmp_name']) ):
		if (!in_array(strtolower(getMime($_FILES['complogo']['tmp_name'])), $extensions)):
	    	$fail .= '<p>Kindly scan and upload company`s logo</p><p>This is not an image file. Only JPG, JPEG, PNG, JPE, WEBP, or JP2 file is allowed.</p>';
	        $err++;
	    endif;
	endif;

	if($err==0):
		if (!file_exists($targetDir)):
		    mkdir($targetDir, 0777, true);
		endif;
		$token="res-".date("YmdHis").getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `real_estate` ORDER BY `id` DESC LIMIT 1;");

		$newName = $token.'_logo.png';
    	$targetFile = $targetDir . $newName;
    	if ( ( empty($_FILES['complogo']['tmp_name']) and @copy("site/media/i/realestate-icon.png", $targetFile)) or move_uploaded_file($_FILES["complogo"]["tmp_name"], $targetFile) ):
			$posts->description=testInput($posts->description);
			$posts->location=testInput($posts->location);
			$posts->title=testInput($posts->title);
			$posts->company=testInput($posts->company);
			$theImages=array();

			$idX=1;
			foreach ($files as $key => $value):
				// $value="files->images".$idX."['tmp_name']";
				if($key=='complogo'):
					continue;
				endif;
				if(!empty($value['tmp_name'])):
					$newImageFile=$token.'_image'.$idX.'.png';
					$trgImageFile=$targetDir.$newImageFile;
					$idX++;
					move_uploaded_file($value['tmp_name'], $trgImageFile);
					array_push($theImages, $trgImageFile);
				endif;
			endforeach;
			//	id	token	company	category	purchase	logo	images	title	email	phone	payment	url	worth	location	description	dateadded	addedby	updatedby	dateupdated	status
		    if($ezDb->query("INSERT INTO `real_estate` (`token`, `category`, `company`, `images`, `title`, `addedby`, `purchase`, `email`, `logo`, `phone`, `location`, `description`, `person`, `worth`, `dateadded`, `dateupdated`, `status`, `payment`) VALUES ('$token', '$posts->category', '$posts->company', '".json_encode($theImages)."', '$posts->title', '$user', '$posts->purchase', '$posts->email', '$targetFile','$posts->phone', '$posts->location', '$posts->description', '$posts->person', '$posts->worth', '$dateNow', '$dateNow', '1', '$posts->purchase');")){

				$propDetails=$ezDb->get_row("SELECT * FROM `real_estate` WHERE `token`='$token';");

				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>Real Estate Property Details Successfully Posted</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error!</h3> <p>Failed to Post</p></div>';
			}
			 //remember to send email to client
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to upload company`s logo.</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;

if(!empty($posts->triggers) and $posts->triggers=='updateProperty'):
	$targetDir="site/media/properties/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg","image/tiff","image/webp","image/jp2");
	if( empty($posts->category) or !in_array($posts->category, array('commercial','event-center','house-apartment','land-plots')) ):
		$fail.='<p>Invalid Category Selected: select a valid category</p>';
		$err++;
	endif;
	if( empty($posts->purchase) or !in_array($posts->purchase, array('lease','sales')) ):
		$fail.='<p>Invalid Type Selected: select a valid type</p>';
		$err++;
	endif;
	if( empty($posts->company) ):
		$fail.='<p>Invalid Company Name: kindly enter company or agent name</p>';
		$err++;
	endif;
	if( empty($posts->title)):
		$fail.='<p>Invalid Property Title: property title cannot be empty</p>';
		$err++;
	endif;
	if( empty($posts->description)):
		$fail.='<p>Invalid Description: kindly add property description</p>';
		$err++;
	endif;
	if( empty($posts->worth) or !is_numeric($posts->worth)):
		$fail.='<p>Invalid Property Worth: kindly enter property worth</p>';
		$err++;
	endif;
	if( empty($posts->phone)  or !checkEmail($posts->email) or empty($posts->email)):
		$fail.='<p>Invalid Contact Details: enter email and phone number</p>';
		$err++;
	endif;
	$existingImages=json_decode($posts->prevpropimages);
	
	if( empty($files->images1) and empty($files->images2) and empty($files->images3) and empty($files->images4) and empty($files->images5) ):
	else:
		$cnter1=$cnter=0;
		foreach ($files as $key => $value):
			// $value="files->images".$idX."['tmp_name']";
			if($key=='complogo'):
				continue;
			endif;
			// error_log(json_encode($value));
			if(!empty($value['tmp_name'])):
				$cnter++;
				/*Check File Type and Size 86802*/
				if(!in_array(strtolower(getMime($value['tmp_name'])), $extensions)):
					$fail .= '<p>Invalid Property Image: Image file with the following extension is required. [JPG, JPEG, PNG, JPE, WEBP, or JP2].</p>';
	        		$err++;
				endif;
			else:
				$cnter1++;
			endif;
		endforeach;
		if(($cnter+count($existingImages))==5 and $cnter<5):
			$fail.='<p>Invalid Property Image: you are only authorized to upload '.(5-count($existingImages)).' more images</p>';
			$err++;
		endif;
		// if( $cnter==5 and $cnter1>0):
			// $fail.='<p>Invalid Property Image: kindly select at least one image</p>';
			// $err++;
		// endif;
	endif;
	if( $existingImages === NULL  and !empty($posts->prevpropimages)):
		$fail.='<p>Invalid Form Request: previous image had been tampered with</p>';
		$err++;
	endif;
	// if( empty($posts->salary) or $posts->salary)<0):
	// 	$fail='<p>Invalid Salary: salary must not be less that 0</p>';
	// 	$err++;
	// endif;
	if( !empty($_FILES['complogo']['tmp_name']) ):
		if (!in_array(strtolower(getMime($_FILES['complogo']['tmp_name'])), $extensions)):
	    	$fail .= '<p>Kindly scan and upload company`s logo</p><p>This is not an image file. Only JPG, JPEG, PNG, JPE, WEBP, or JP2 file is allowed.</p>';
	        $err++;
	    endif;
	endif;
	if( empty($ezDb->get_var("SELECT `token` FROM `real_estate` WHERE `token`='$posts->token';")) ):
		$fail .= '<p>Invalid Real Estate Property Post ID</p><p>This real estate property id cannot be found in the database</p>';
	    $err++;
	endif;


	if($err==0):
		if (!file_exists($targetDir)):
		    mkdir($targetDir, 0777, true);
		endif;
		$newName = $posts->token.'_logo.png';
    	$targetFile = $targetDir . $newName;
    	$moved=false;$moved1=false;
    	if( !empty($_FILES['complogo']['tmp_name']) ):
    		if (file_exists($posts->prevproplogo) and is_file($posts->prevproplogo)):
    			unlink($posts->prevproplogo);
    		endif;
    		$moved=move_uploaded_file($_FILES["complogo"]["tmp_name"], $targetFile);
    	else:
    		$targetFile=$posts->prevproplogo;
    	endif;

		if ( empty($_FILES['complogo']['tmp_name']) or $moved!=false ):
			$settings=getSettings();
			$settings->property=json_decode($settings->property);
    		$posts->description=testInput($posts->description);
			$posts->location=testInput($posts->location);
			$posts->title=testInput($posts->title);
			$posts->company=testInput($posts->company);
			$theImages=array();
	    	$theImages=json_decode($posts->prevpropimages);
	    	if( empty($files->images1) and empty($files->images2) and empty($files->images3) and empty($files->images4) and empty($files->images5) ):
	    		$theImages=json_decode($posts->prevpropimages);
			else:
				$idX=1;
				if($cnter==5):
					foreach ($theImages as $value):
						unlink($value);
					endforeach;
					$theImages=array();
				else:
				endif;
				foreach ($files as $key => $value):
					// $value="files->images".$idX."['tmp_name']";
					if($key=='complogo'):
						continue;
					endif;
					if(!empty($value['tmp_name'])):
						$newImageFile=$posts->token.'_image'.(count($theImages)+$idX).'.png';
						$trgImageFile=$targetDir.$newImageFile;
						$idX++;
						move_uploaded_file($value['tmp_name'], $trgImageFile);
						array_push($theImages, $trgImageFile);
					endif;
				endforeach;
			endif;
			//	id	token	company	category	purchase	logo	images	title	email	phone	payment	url	worth	location	description	dateadded	addedby	updatedby	dateupdated	status
			if($ezDb->query("UPDATE `real_estate` SET `category`='$posts->category', `title`='$posts->title', `person`='$posts->person', `company`='$posts->company', `email`='$posts->email', `logo`='$targetFile', `images`='".json_encode($theImages)."', `phone`='$posts->phone', `location`='$posts->location', `updatedby`='$user', `description`='$posts->description', `worth`='$posts->worth', `purchase`='$posts->purchase', `dateupdated`='$dateNow' WHERE `token`='$posts->token';")):

				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>Property Details Successfully Updated</p></div>';
			else:
				$fail='<div class="alert alert-info alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to update details, kindly contact developer.</p></div>';
			endif;

		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> An error has occured: unable to upload company`s logo.</p></div>';
		endif;

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;

if(!empty($posts->triggers) and $posts->triggers=='delprop'):
	if( empty($posts->token) or empty($ezDb->get_var("SELECT `token` FROM `real_estate` WHERE `token`='$posts->token';")) ):
		$fail='<p>Invalid Real Estate Property Post ID</p><p>This property post id cannot be found in the database</p>';
		$err++;
	endif;
	if($err==0):
		if($ezDb->query("INSERT INTO `real_estate_backup` SELECT * FROM `real_estate` WHERE `token`='$posts->token';") and $ezDb->query("DELETE FROM `real_estate` WHERE `token`='$posts->token';")){
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Messages</h3> <p>Property Details Successfully Deleted REF: \''.$posts->token.'\'</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> Unable to delete selected property detail, kindly contact developer</div>';
		}
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
elseif(!empty($posts->triggers) and $posts->triggers=='deactivateprop'):
	if( empty($posts->token) or empty($ezDb->get_var("SELECT `token` FROM `real_estate` WHERE `token`='$posts->token' AND `status`='1';")) ):
		$fail='<p>Invalid Real Estate Property Post ID</p><p>This property post id cannot be found in the database or is already in deactivation status</p>';
		$err++;
	endif;
	if($err==0):
		if($ezDb->query("UPDATE `real_estate` SET `status`='0' WHERE `token`='$posts->token';")):
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Messages</h3> <p>Real Estate Property Post Successfully Deactivated REF: \''.$posts->token.'\'</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> Unable to deactivate selected property detail, kindly contact developer</div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
elseif(!empty($posts->triggers) and $posts->triggers=='activateprop'):
	if( empty($posts->token) or empty($ezDb->get_var("SELECT `token` FROM `real_estate` WHERE `token`='$posts->token' AND `status`='0';")) ):
		$fail='<p>Invalid Real Estate Property Post ID</p><p>This property post id cannot be found in the database or is already in activation status</p>';
		$err++;
	endif;
	if($err==0):
		if($ezDb->query("UPDATE `real_estate` SET `status`='1' WHERE `token`='$posts->token';")):
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Messages</h3> <p>Real Estate Property Post Successfully Activated REF: \''.$posts->token.'\'</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> Unable to activate selected property detail, kindly contact developer</div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	endif;
endif;

$whereClause='';

$realEstates=tableRoutine("real_estate", '*', "$whereClause", '`id`', 'DESC', "9");

if(!empty($realEstates) and is_array($realEstates)){
	foreach ($realEstates as $value) {
		$value->images=json_decode($value->images);
	}
}
$smarty->assign('catTitle', $catTitle)->assign('forProp', $forProp)->assign("realEstates", $realEstates);