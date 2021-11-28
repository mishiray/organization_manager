<?php 
if (!in_array($userinfo->userrole, array("level0", "level1", "level2")) or $userinfo->active!=true) {
	redirect("home");
}

$sportid=(!empty($gets->id)? $gets->id: "");
$sport=$ezDb->get_row("SELECT * FROM `sports` WHERE `sportid`='$sportid';");
if (!empty($sport)) {
	$countries=getCountries();
	if( empty($country=$ezDb->get_var("SELECT `name` FROM `countries` WHERE `name`='$sport->country'")) ){
		$country=$countries[0]->name;
	}
	$states=getStates($country);
	if( empty($state=$ezDb->get_var("SELECT `name` FROM `states` WHERE `name`='$sport->state'")) ){
		$state=$states[0]->name;
	}
	$cities=getCities($state);

	$fail="";
	$err=0;
	/*Updating Sport sport*/
	if ( in_array($sitePage, array("sport")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_sport']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/sports/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/octet-stream");
		if( !in_array($userinfo->userrole, array("level0", "level1", "level2")) or $userinfo->active!=true ):
			$err++;
			$fail.='<p>You do not have authorization to modify this sport center!</p>';
		endif;
		if( empty(trim($posts->sport)) ):
			$err++;
			$fail.='<p>Enter Sport name!</p>';
		endif;
		if( empty(trim($posts->description)) ):
			$err++;
			$fail.='<p>Kindly describe sport center!</p>';
		endif;
		if( empty(trim($posts->location)) ):
			$err++;
			$fail.='<p>Enter location of sport center!</p>';
		endif;
		if( empty(trim($posts->category)) or !array_key_exists($posts->category, $categs) ):
			$err++;
			$fail.='<p>Choose a valid category, this category does not exists!</p>';
		endif;
		if( empty($ezDb->get_var("SELECT `name` FROM `countries` WHERE `name`='$posts->country'") ) ):
			$err++;
			$fail.='<p>Select a valid country please!</p>';
		endif;
		if( empty($ezDb->get_var("SELECT `st`.`name` FROM `countries` AS `cn`, `states` AS `st` WHERE `st`.`country_id`=`cn`.`id` AND `st`.`name`='$posts->state' AND `cn`.`name`='$posts->country' ") ) ):
			$err++;
			$fail.='<p>Select a valid state please!</p>';
		endif;
		if (!empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p>The uploaded sport club logo is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
	    if ($err==0) {
		    $posts->description=testInput($posts->description);
		    $posts->location=testInput($posts->location);
			if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;
		    if (!empty($files->img_upload['tmp_name']) and file_exists($sport->logo)):
		    	unlink($sport->logo);
			endif;
	    	$targetFile = $targetDir . $sport->sportid."_profile.png";
	    	$targetFile = ((!empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile))? $targetFile: $sport->logo);
			$ezDb->query("UPDATE `sports` SET `name`='$posts->sport', `description`='$posts->description', `category`='$posts->category', `logo`='$targetFile', `location`='$posts->location', `country`='$posts->country', `state`='$posts->state', `city`='$posts->city' WHERE `sportid`='$sport->sportid';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>sport detail was successfully updated.</p></div>';
			$sport=$ezDb->get_row("SELECT * FROM `sports` WHERE `sportid`='$sportid';");
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$sport->description=testInputReverse($sport->description);
	$sport->description1=br2nl2($sport->description);
	$sport->location=testInputReverse($sport->location);
	$sport->location1=br2nl2($sport->location);


}else{
	redirect("sports");
}
$smarty->assign("sport", $sport)->assign('categs',$categs)->assign("countries", $countries)->assign("states", $states)->assign("cities", $cities);