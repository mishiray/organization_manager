<?php 

$image = $_POST['image'];
$id = $_POST['id'];

$location="site/media/contract_of_sale/";

$image_parts = explode(";base64,", $image);

$image_base64 = base64_decode($image_parts[1]);

//$image_base64 = $image;

$filename = "letter_".uniqid().'.png';

$file = $location . $filename;
//echo json_encode($image_base64);
if ( !file_exists("$location") ) :
	mkdir("$location", 0777, true);
endif;

$targetFile = $location . $filename;
if ( !empty($image_base64) and file_put_contents($targetFile, $image_base64)) :
	//send success message and insert into documents...
	$sub = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$id'");
	if(!empty($sub)){
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `pdocuments` ORDER BY `id` DESC LIMIT 1;");
		$title = 'Subscription letter for '.$sub->first_name.' '.$sub->last_name.' on '.$sub->product;
		$description = 'Your subscription letter for '.$sub->product;
		$ezDb->query("INSERT INTO `pdocuments` (`reftoken`,`token`,`title`, `category`, `description`,`file`,`status`,`addedby`,`active`) VALUES ('$sub->token','$token','$title','subscription_letter','$description','$targetFile',0,'$userinfo->email',1)");
		echo json_encode("SAVED");
	}else{
		
	}
else:
	$targetFile="";
endif;
//file_put_contents($file, $image_base64);
//@move_uploaded_file($image_base64, $targetFile) 