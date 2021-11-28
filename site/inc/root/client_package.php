<?php

redirect("home");
$userinfo=$Site['session']['User']['userinfo'];
$clientemail=(!empty($gets->client)? $gets->client: "");
$client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$clientemail' OR `email`='$clientemail';");
if (!empty($client) and is_object($client)) {
	$packid=(!empty($gets->id)? $gets->id: "");
	$client->package=$ezDb->get_row("SELECT  `rpk`.`id`, `pk`.`packageid`, `pk`.`title`, `pk`.`description`, `pk`.`discount`, `pk`.`course_list`, `pk`.`fee`, `pk`.`icon`, `rpk`.`transid`, `rpk`.`datereg`, `rpk`.`regid`, `rpk`.`completion` FROM `packages` AS `pk`, `registered_packages` AS `rpk` WHERE `rpk`.`packageid`=`pk`.`packageid` AND `rpk`.`user`='$client->email' AND `rpk`.`packageid`='$packid';");
	if (!empty($client->package)) {
		$client->package->description=testInputReverse(trim($package->description));
	    $client->package->title=testInputReverse(trim($package->title));
		$client->package->course_list=@json_decode($client->package->course_list);
		$courseFlt=getCourseIN($client->package->course_list);
		$client->package->courses=( !empty($courseFlt) ?$ezDb->get_results("SELECT * FROM `courses` $courseFlt;") : array() );
		$client->package->course_results=array();
		if (!empty($client->package->courses)) {
			foreach ($client->package->courses as $key => $vals) {
				$client->package->courses[$key]->description=testInputReverse(trim($vals->description));
			    $client->package->courses[$key]->title=testInputReverse(trim($vals->title));
			    $client->package->courses[$key]->isreGistered=$ezDb->get_var("SELECT `courseid` FROM `registered_courses` WHERE `courseid`='$vals->courseid' AND `user`='$client->email';");
		    	$client->package->courses[$key]->results=$ezDb->get_row("SELECT * FROM `results` WHERE `regid`='".$client->package->regid."' AND `courseid`='$vals->courseid' AND `user`='$client->email'");
		    	if(!empty($client->package->courses[$key]->results)):
			    	array_push($client->package->course_results, $client->package->courses[$key]->results);
			    endif;
			}
		}
	    $client->package->materials=$ezDb->get_results("SELECT * FROM `course_material` $courseFlt ORDER BY `courseid` ASC;");
	    $client->package->cert=$ezDb->get_row("SELECT * FROM `certificate` WHERE `progid`='$packid' AND `progtype`='package' AND `email`='$client->email' AND `regid`='".$client->package->regid."'");
	    if ($client->package->cert and !empty($gets->evt) and $gets->evt=='decline') {
	    	if ($client->package->cert->status!='2') {
	    		$ezDb->query("UPDATE `certificate` SET `status`='2' WHERE `email`='$client->email' AND `progid`='$packid' AND `progtype`='package' AND `regid`='".$client->package->regid."' AND `id`='".$client->package->cert->id."';");
	    		$client->package->cert=$ezDb->get_row("SELECT * FROM `certificate` WHERE `progid`='$packid' AND `progtype`='package' AND `email`='$client->email' AND `regid`='".$client->package->regid."'");
	    		$fail='<div class="alert bg-secondary text-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Certificate successfully declined</p></div>';
	    	}
	    }

	    if ( in_array($sitePage, array("client_package")) && ($requestMethod == 'POST') && isset($Site["post"]['triggers']) && $Site["post"]['triggers']=='uploadCert' ) {
	    	$targetDir="site/media/certificates/";
			$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff", "application/pdf");
			if (empty($files->file_upload['tmp_name']) or !in_array(strtolower(getMime($files->file_upload['tmp_name'])), $extensions)):
		    	$fail .= '<p>The uploaded sport club logo is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
		        $err++;
		    endif;
			if ( $err==0 ) {
				if ( !file_exists("$targetDir") ) :
			        mkdir("$targetDir", 0777, true);
			    endif;
				$posts->certTitle=testInput(str_replace("\n", "<br/>", (!empty($posts->certTitle)? $posts->certTitle: $files->file_upload['name'])));
				$spilledName=explode(".", $files->file_upload['name']);
				$targetFile = $targetDir . $client->email. $packid. $client->package->regid. "." .(end($spilledName));
				if (!empty($files->file_upload['tmp_name']) and !empty($client->package->cert) and file_exists($client->package->cert->certurl)):
			    	@unlink($client->package->cert->certurl);
				endif;
		    	$targetFile = ((!empty($files->file_upload['tmp_name']) and @move_uploaded_file($files->file_upload['tmp_name'], $targetFile))? $targetFile: $client->package->cert->certurl);

				if (!empty($client->package->cert) and is_object($client->package->cert)):
					$ezDb->query("UPDATE `certificate` SET `status`='1', `certurl`='$targetFile', `title`='$posts->certTitle', `dateupdated`='$dateNow', `updatedby`='$userinfo->email' WHERE `email`='$client->email' AND `progid`='$packid' AND `progtype`='package' AND `regid`='".$client->package->regid."' AND `id`='".$client->package->cert->id."';");
				else:
					$ezDb->query("INSERT INTO `certificate` (`email`, `progid`, `datesent`, `status`, `progtype`, `regid`, `certurl`, `title`, `dateupdated`, `updatedby`) VALUES ('$client->email', '$packid', '$dateNow', '1', 'package', '".$client->package->regid."', '$targetFile', '$posts->certTitle', '$dateNow', '$userinfo->email')");
				endif;
				/*Send mail to client attach mail file here*/
    			$client->package->cert=$ezDb->get_row("SELECT * FROM `certificate` WHERE `progid`='$packid' AND `progtype`='package' AND `email`='$client->email' AND `regid`='".$client->package->regid."'");
				$fail='<div class="alert bg-secondary text-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Certificate successfully uploaded and sent to client mail</p></div>';
			}else{
				$fail='<tr><td colspan="4"><div class="alert bg-secondary text-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div></td></tr>';
			}
		}
	    if (!empty($client->package->materials)) {
			foreach ($client->package->materials as $key => $vals) {
				$client->package->materials[$key]->description=testInputReverse(trim($vals->description));
			    $client->package->materials[$key]->title=testInputReverse(trim($vals->title));
			    /*$vals->course=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='$vals->courseid';");
			    $vals->course->title=testInputReverse(trim($course->title));
			    $vals->course->category=testInputReverse(trim($course->category));*/
			}
		}
	}else{
		redirect("client?id=$clientemail");
	}
}else{
	redirect("clients");
}

$smarty->assign("client", $client)->assign("accType", array("individual"=>"Individual", "corporate"=>"Corporate", "college"=> "College", "government"=>"Government", "association"=>"Association"));