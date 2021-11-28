<?php 

$image = $_POST['image'];
$id = $_POST['id'];

$location="site/media/payslips/";

$image_parts = explode(";base64,", $image);

$image_base64 = base64_decode($image_parts[1]);

//$image_base64 = $image;

$filename = "payslip_".uniqid().'.png';

$file = $location . $filename;
//echo json_encode($image_base64);
if ( !file_exists("$location") ) :
	mkdir("$location", 0777, true);
endif;

$targetFile = $location . $filename;
if ( !empty($image_base64) and file_put_contents($targetFile, $image_base64)) :
	//send success message and insert into documents...
	$payslip = $ezDb->get_row("SELECT * FROM `payslips` WHERE `id` = '$id'");
	if(!empty($payslip)){
		$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `pdocuments` ORDER BY `id` DESC LIMIT 1;");
		$title = $payslip->month.' '.$payslip->year.' Payslip';
		$description = 'Your payslip for the month of '.$payslip->month.' '.$payslip->year;
		$ezDb->query("INSERT INTO `pdocuments` (`reftoken`,`token`,`employeeid`,`title`, `category`, `description`,`file`,`status`,`addedby`,`active`) VALUES ('$payslip->token','$token','$payslip->employeeid','$title','payslip','$description','$targetFile',0,'$userinfo->email',1)");
		echo json_encode("SAVED");
	}else{
		
	}
else:
	$targetFile="";
endif;
//file_put_contents($file, $image_base64);
//@move_uploaded_file($image_base64, $targetFile) 