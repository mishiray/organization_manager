<?php 

if (!in_array($userinfo->userrole, array("level0", "level1", "level2")) or $userinfo->active!=true) {
	redirect("home");
}

$countries=getCountries();
if( empty($country=$ezDb->get_var("SELECT `name` FROM `countries` WHERE `name`='".$Site['session']['User']['userinfo']->country."'")) ){
	$country=$countries[0]->name;
}
$states=getStates($country);
$state=$states[0]->name;
$cities=getCities($state);
$userinfo=$Site['session']['User']['userinfo'];

$fail="";
$err=0;
if ( in_array($sitePage, array("add_sport")) && ($requestMethod == 'POST') && isset($Site["post"]['add_sport']) ) {
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	// error_log(json_encode($files));
	$targetDir="site/media/sports/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream");
	$term_fee=new stdClass;
	if( empty(trim($posts->sport)) ):
		$err++;
		$fail.='<p>Enter Sport sport name!</p>';
	endif;
	if( empty(trim($posts->description)) ):
		$err++;
		$fail.='<p>Kindly describe sport sport!</p>';
	endif;

	if( empty(trim($posts->location)) ):
		$err++;
		$fail.='<p>Enter location of sport sport!</p>';
	endif;
	if( empty(trim($posts->category)) or !array_key_exists($posts->category, $categs) ):
		$err++;
		$fail.='<p>Choose a valid category, this category does not exists!</p>';
	endif;
	if (!empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded sport club logo is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;
	if( empty($ezDb->get_var("SELECT `name` FROM `countries` WHERE `name`='$posts->country'") ) ):
		$err++;
		$fail.='<p>Select a valid country please!</p>';
	endif;
	if( empty($ezDb->get_var("SELECT `st`.`name` FROM `countries` AS `cn`, `states` AS `st` WHERE `st`.`country_id`=`cn`.`id` AND `st`.`name`='$posts->state' AND `cn`.`name`='$posts->country' ") ) ):
		$err++;
		$fail.='<p>Select a valid state please!</p>';
	endif;
    if ($err==0) {
		if ( !file_exists("$targetDir") ) :
	        mkdir("$targetDir", 0777, true);
	    endif;
	    $posts->description=testInput(nl2br2(tb2sp2($posts->description)));
		$posts->location=testInput(nl2br2(tb2sp2($posts->location)));
    	$sportid= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `sports` ORDER BY `id` DESC LIMIT 1;");
    	$targetFile = $targetDir . $sportid."_profile.png";
    	$targetFile=(@move_uploaded_file($files->img_upload['tmp_name'], $targetFile)? $targetFile:"");
		$ezDb->query("INSERT INTO `sports` (`sportid`, `name`, `description`, `category`, `dateadded`, `addedby`, `logo`, `location`, `country`, `state`, `city`) VALUES ('$sportid', '$posts->sport', '$posts->description', '$posts->category', '$dateNow', '$userinfo->email', '$targetFile', '$posts->location', '$posts->country', '$posts->state', '$posts->city')");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Sport Center detail was successfully added.</p></div>';
		
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}

}
$smarty->assign("sport", $posts)->assign("fail",$fail)->assign('categs',$categs)->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities);