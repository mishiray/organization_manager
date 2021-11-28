<?php

if(in_array($sitePage, array("business")) && isset($_POST['payment_data'])){
  $inputdata = (object) $Site["post"];
  $files= (object) $Site["files"];
  $targetDir="site/media/business/";
  $extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/jfiff");
  if ( !empty($files->img_upload['tmp_name']) and (!in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)) ):
    $fail .= '<p>The uploaded logo image is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
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
  if( empty(trim($inputdata->subscription))):
    $err++;
    $fail.='<p class="border border-danger p-1 rounded">Please choose a subscription plan!</p>';
  endif;
  if( $err==0 ){
    if( !file_exists("$targetDir") ):
      mkdir("$targetDir", 0777, true);
    endif;
    $token= getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `business` ORDER BY `id` DESC LIMIT 1;");

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
    $inputdata->subscription = testInput("$" . $inputdata->subscription . "/month");

    $ezDb->query("INSERT INTO `business` (`token`, `category`, `sub_category`, `business_name`, `image`, `phone_number`, `email`, `business_desc`, `address`, `city`, `state`, `postal_code`, `contact_person`, `status`, `subscription`) VALUES ('$token', '$inputdata->category', '$posts->subcategory', '$inputdata->businessname', '$targetFile', '$inputdata->phonenumber', '$inputdata->email', '$inputdata->businessdesc', '$inputdata->address', '$inputdata->city', '$inputdata->state', '$inputdata->postal', '$inputdata->contactperson', '1', '$inputdata->subscription')");
     // require_once 'initialize.php';

    $fail='<div class="alert alert-success text-justify">
      <i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3>Success Messages</h3><p>Business registation is successful</p></div>';
  }
}

if(!empty($posts->triggers) and $posts->triggers=='rateBiz'):
  $fail ='';
  $err = 0;
  if( empty($posts->rating) or !in_array($posts->rating, array('1','2','3','4','5')) ):
    $fail='<p>Invalid Rating Selected: kindly choose a valid rating</p>';
    $err++;
  endif;
  if( empty($posts->names)):
    $fail='<p>Invalid Name: kindly enter your name</p>';
    $err++;
  endif;
  if( empty($posts->email) or !checkEmail($posts->email)):
    $fail='<p>Invalid Email: kindly enter a valid email</p>';
    $err++;
  endif;
  if( empty($posts->phone)):
    $fail='<p>Invalid Email: kindly enter your phone number</p>';
    $err++;
  endif;
  if( empty($ezDb->get_var("SELECT `token` FROM `business` WHERE `token`='$posts->bizToken'"))):
    $fail='<p>Invalid Business: cannot identify this business</p>';
    $err++;
  endif;

  if ($err == 0) :
    $userDetail = new stdClass;
    $userDetail->names = testInput($posts->names);
    $userDetail->phone = testInput($posts->phone);
    $userDetail=json_encode($userDetail);
    $comments=testInput($posts->comment);
    if($ezDb->get_var("SELECT `email` FROM `ratings` WHERE `token`='$posts->bizToken' AND `email`='$posts->email' AND `type`='business'")):
      $ezDb->query("UPDATE `ratings` SET `userdetails`='$userDetail', `comment`='$comments', `rating`='$posts->rating', `dateadded`='$dateNow' WHERE `token`='$posts->bizToken' AND `email`='$posts->email' AND `type`='business'");
    else:
      $ezDb->query("INSERT INTO `ratings` (`type`, `email`, `userdetails`, `comment`, `token`, `recordid`, `rating`, `dateadded`) VALUES ('business','$posts->email','$userDetail','$comments','$posts->bizToken','','$posts->rating','$dateNow');");
    endif;

    $fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Thanks for your rating.</p></div>';
  else:
    $fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
  endif;
endif;

$whereClause="";

if( in_array($gets->cat, array('automotive','salon','home','sports','recreations','hotels','fashion','market','others', 'health', 'schools', 'churches')) ){
  $whereClause=" AND `category`='$gets->cat'";
}

$businesses=tableRoutine("business", '*' , " `status`='1' $whereClause", '`id`', 'DESC', '`id`', '8');
if (!empty($businesses)) {
  foreach ($businesses as $value) {
    $value->businessname=testinputReverse($value->business_name);
    $value->business_desc=testinputReverse($value->business_desc);
    $value->business_desc_edit=br2nl2($value->business_desc);
    $value->business_desc_stripe=stripeInputReverse($value->business_desc_edit);
    $value->business_desc_stripe=str_replace("&quot;", "\"", $value->business_desc_stripe);
    $value->business_desc_stripe=str_replace("&nbsp;", " ", $value->business_desc_stripe);

    $value->rate=$ezDb->get_var("SELECT SUM(`rating`)/COUNT(`rating`) FROM `ratings` WHERE `token`='$value->token' AND `type`='business'");
    $value->rate=(empty($value->rate)? 0: $value->rate);
    $value->rateCeil=(count(explode(".", $value->rate))>1.0? explode(".", $value->rate)[0]: $value->rate);
    $value->rateFloor=(round(explode(".", $value->rate))>=1? 1 :0);
    $value->rateRem=5-$value->rateCeil;
    $value->rateDetails=$ezDb->get_results("SELECT `comment`, `email` FROM `ratings` WHERE `token`='$value->token' AND `type`='business' AND `comment`!=''");
  }
}

$gets->cat=(!isset($gets->cat)?'': $gets->cat);
switch ($gets->cat) {
  case 'automotive':
    $catTitle='Automotive & Transport Services';
  break;
  case 'salon':
    $catTitle='Salons & SPAs';
  break;
  case 'home':
    $catTitle='Home, Land, & Real Estate';
  break;
  case 'sports':
    $catTitle='Sports & Fitness Centers';
  break;
  case 'recreations':
    $catTitle='Recreations, Bars, & Eatries';
  break;
  case 'hotels':
    $catTitle='Hotels & Clubs';
  break;
  case 'schools':
    $catTitle='Schools';
  break;
  case 'churches':
    $catTitle='Religion Platforms';
  break;
  case 'fashion':
    $catTitle='Fashion Boutiques & Galleries';
  break;
  case 'market':
    $catTitle='Markets & Shopping Mall';
  break;
  case 'health':
    $catTitle='Health Care Centers';
  break;
  case 'others':
    $catTitle='Others';
  break;
  default:
    $catTitle='All';
  break;
}

if( isset($gets->cat) and !in_array($gets->cat, array('automotive','salon','home','sports','recreations','hotels','fashion','market','others', 'health','')) ){
  unset($gets->cat);
}

$smarty->assign("catTitle", $catTitle)->assign("businesses", $businesses);
$smarty->assign("businesses", $businesses);
