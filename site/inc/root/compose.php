<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2', 'level3', 'level4')) ):
	redirect("home");
endif;

$deptid = depttodepid($userinfo->department);
$clients=$ezDb->get_results("SELECT `email`, CONCAT(`firstname`, ' ', `middlename`, '', `lastname`) AS `names` FROM `userprofile` WHERE `usertype`='client' AND `verified`='1';");
$recipients=$ezDb->get_results("SELECT `email`,`usertype`, `verified`, CONCAT(`firstname`,' ', `lastname`) AS `names` FROM `userprofile` WHERE `verified`='1' AND  `usertype`='admin' ORDER BY `names` ASC;");
$departments = $ezDb->get_results("SELECT * FROM `department` ");

if ( in_array($sitePage, array("compose")) && ($requestMethod == 'POST') && isset($Site["post"]['send_message']) ) {
	// error_log(json_encode($files));
	/*$targetDir="site/media/messaging/";
	$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");*/
	/*if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
        $err++;
    endif;*/
	$receiver=array();
	if( empty(trim($posts->sendto)) or !in_array($posts->sendto, array("*","#"))):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Choose a valid send to!</p>';
	elseif( $posts->sendto=='#'):
		if(empty($posts->receivers) or !is_array($posts->receivers) ):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly choose receiver(s)!</p>';
		else:
			foreach ($posts->receivers as $key => $value):
				if(empty($value) or empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$value' AND `verified`='1'"))):
					$err++;
					$fail.='<p class="border badge-pill border-danger">This ('.$value.') receiver is not a valid registered member!</p>';
				elseif(!empty($value)):
					array_push($receiver, $value);
				endif;
			endforeach;
		endif;
	elseif( $posts->sendto=='*'):
		$receiver=$ezDb->get_col("SELECT `email` FROM `userprofile` WHERE `verified`='1';");
	else:
		$token = $posts->sendto;
		$receiver=$ezDb->get_col("SELECT DISTINCT(u.email) FROM `employees` as e INNER JOIN `userprofile`as u ON e.email = u.email WHERE u.verified = '1' AND e.department_id = '$token'");
	endif;
	if( empty(trim($posts->title)) ):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Enter post title please!</p>';
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Enter post content please!</p>';
	endif;
	if( $err==0 ):
		/*if( !file_exists("$targetDir") ):
	        mkdir("$targetDir", 0777, true);
	    endif;*/
	    $messageid= getToken(6).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `messages` ORDER BY `id` DESC LIMIT 1;");
	    /*$targetFile = $targetDir . $updateid."_update.png";
	    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
	    else:
	    	$targetFile="";
	    endif;*/
	    $posts->content=testInput($posts->content);
	    $posts->title=testInput($posts->title);
	    //id	messageid	sender	title	content	receivers	readers	datesent	reply	attachement
	    $ezDb->query("INSERT INTO `messages` (`messageid`, `title`, `content`, `sender`, `receivers`, `readers`, `datesent`, `reply`, `attachement`) VALUES ('$messageid', '$posts->title', '$posts->content', '$userinfo->email', '".@json_encode($receiver)."', '".@json_encode($emptyArray)."', '$dateNow', '', '')");

		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Message succesfully sent.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
$smarty->assign("clients", $clients)->assign("recipients", $recipients)->assign("departments", $departments)->assign("deptid", $deptid);