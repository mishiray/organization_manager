<?php 

$userinfo=$Site['session']['User']['userinfo'];

$id=(!empty($gets->id)? $gets->id: "");
$business=$ezDb->get_row("SELECT * FROM `business` WHERE `token`='$id';");

if (!empty($business)){
  
	if ( in_array($sitePage, array("business")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_business']) ) {
		$inputdata = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/business/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	  endif;
    if(empty(trim($inputdata->category)) ):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Choose a category please!</p>';
    endif;
    if( empty(trim($inputdata->subcategory))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Choose a sub-category please!</p>';
    endif;
    if( empty(trim($inputdata->businessname))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input a business name!</p>';
    endif;
    if( empty(trim($inputdata->phonenumber))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input a phone number!</p>';
    endif;
    if( empty(trim($inputdata->email))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input an email!</p>';
    endif;
    if( empty(trim($inputdata->businessdesc))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Please write a shot description of your business!</p>';
    endif;
    if( empty(trim($inputdata->address))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Input an address!</p>';
    endif;
    if( empty(trim($inputdata->city))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">A city is required!</p>';
    endif;
    if( empty(trim($inputdata->state))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">A state is required!</p>';
    endif;
    if( empty(trim($inputdata->postal))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Your postal code is required!</p>';
    endif;
    if( empty(trim($inputdata->contactperson))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Additional contact is required!p>';
    endif;
    if( empty(trim($inputdata->subscription))):
      $err++;
      $fail.='<p class="border border-danger p-1 rounded">Please choose a subscription plan!</p>';
    endif;

		if( $err==0 ):
			if( !file_exists("$targetDir") ):
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $targetFile = $targetDir . $id."_business.png";
		    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
		    else:
		    	$targetFile=$business->image;
		    endif;
		    $inputdata->phonenumber=testInput($inputdata->phonenumber);
        $inputdata->businessname=testInput($inputdata->businessname);
        $inputdata->email=testInput($inputdata->email);
        $inputdata->businessdesc=testInput($inputdata->businessdesc);
        $inputdata->address=testInput($inputdata->address);
        $inputdata->city=testInput($inputdata->city);
        $inputdata->state=testInput($inputdata->state);
        $inputdata->postal=testInput($inputdata->postal);
        $inputdata->contactperson=testInput($inputdata->contactperson);
        $inputdata->subscription = testInput("$" . $inputdata->subscription . "/month");
        
		    //id	updateid	title	content	image	addedby	dateadded        
        $ezDb->query("UPDATE `business` SET `category`='$inputdata->category', `sub_category`='$inputdata->subcategory', `business_name`='$inputdata->businessname', `image`='$targetFile', `phone_number`='$inputdata->phonenumber', `email`='$inputdata->email', `business_desc`='$inputdata->businessdesc', `address`='$inputdata->address', `city`='$inputdata->city', `state`='$inputdata->state', `postal_code`='$inputdata->postal', `contact_person`='$inputdata->contactperson', `subscription`='$inputdata->subscription';");

			$business=$ezDb->get_row("SELECT * FROM `business` WHERE `token`='$id';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> business was successfully updated.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	if ( in_array($sitePage, array("business")) && ($requestMethod == 'POST') && isset($Site["post"]['delete_business']) ) {
		if ($err==0) {
		    if (!empty($business->image) and file_exists($business->image)):
		    	@unlink($business->image);
			endif;
			$ezDb->query("DELETE FROM `business` WHERE `token`='$id';");
			// Delete project file or images
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>business detail had been successfully deleted.</p></div>';
		}
	}

  $business->businessname=testinputReverse($business->business_name);
  $business->business_desc=testinputReverse($business->business_desc);
  $business->business_desc_edit=br2nl2($business->business_desc);
  $business->business_desc_stripe=stripeInputReverse($business->business_desc_edit);
  $business->business_desc_stripe=str_replace("&quot;", "\"", $business->business_desc_stripe);
  $business->business_desc_stripe=str_replace("&nbsp;", " ", $business->business_desc_stripe);
}else{
	redirect("businesses");
}
$smarty->assign("business", $business);