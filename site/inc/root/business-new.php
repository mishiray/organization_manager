<?php 
$userinfo=$Site['session']['User']['userinfo'];
$fail="";
$err=0;
if ( in_array($sitePage, array("business-new")) && ($requestMethod == 'POST')) {
	$inputdata = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/events/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
	if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
      $err++;
  endif;
	if( empty(trim($inputdata->category)) ):
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
      // if( empty(trim($inputdata->subscription))):
      //   $err++;
      //   $fail.='<p class="border border-danger p-1 rounded">Please choose a subscription plan!</p>';
      // endif;
	if( $err==0 ):
		if( !file_exists("$targetDir") ):
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $token= date("zXdGBs").$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `business` ORDER BY `id` DESC LIMIT 1;");
	    $targetFile = $targetDir . $token."_business.png";
	    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
	    else:
	    	$targetFile="";
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
        $inputdata->subscription = testInput("$100/month");
	    //id	updateid	title	content	image	addedby	dateadded
	    $ezDb->query("INSERT INTO `business` (`token`, `category`, `sub_category`, `business_name`, `image`, `phone_number`, `email`, `business_desc`, `address`, `city`, `state`, `postal_code`, `contact_person`, `subscription`, `status`) VALUES ('$token', '$inputdata->category', '$posts->subcategory', '$inputdata->businessname', '$targetFile', '$inputdata->phonenumber', '$inputdata->email', '$inputdata->businessdesc', '$inputdata->address', '$inputdata->city', '$inputdata->state', '$inputdata->postal', '$inputdata->contactperson', '$inputdata->subscription', '1')");

		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Business has been successfully posted.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
