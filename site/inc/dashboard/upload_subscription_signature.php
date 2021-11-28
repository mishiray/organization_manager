<?php 

$image = $_POST['image'];
$id = $_POST['id'];

$location="site/media/signatures/";

$image_parts = explode(";base64,", $image);

$image_base64 = base64_decode($image_parts[1]);

//$image_base64 = $image;

$filename = "signature_".uniqid().'.png';

$file = $location . $filename;
//echo json_encode($image_base64);
if ( !file_exists("$location") ) :
	mkdir("$location", 0777, true);
endif;

$message = new stdClass();

$targetFile = $location . $filename;
if ( !empty($image_base64) and file_put_contents($targetFile, $image_base64)) :
	//send success message and insert into documents...
	$sub = $ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$id'");
	if(!empty($sub)){

		$ezDb->query("UPDATE `subscription` SET `signature` = '$targetFile' WHERE `token` = '$id' ");
		//alertHRManager(0,"Completion Letter has been signed for Reference: $sub->token");
		$message->status = 1;
		$message->content = 'Signature Upload Successful';
	}else{
		$message->status = -1;
		$message->content = 'Item Not Found';
	}
else:
	$message->status = -1;
	$message->content = 'Image Not Uploaded';
endif;

echo @json_encode($message);
exit();