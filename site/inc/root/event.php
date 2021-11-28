<?php 

$userinfo=$Site['session']['User']['userinfo'];

$id=(!empty($gets->id)? $gets->id: "");
$event=$ezDb->get_row("SELECT * FROM `events` WHERE `token`='$id';");

if (!empty($event)){
	if ( in_array($sitePage, array("event")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_event']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		$targetDir="site/media/events/";
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		if ( !empty($files->img_upload['tmp_name']) and !in_array(strtolower(getMime($files->img_upload['tmp_name'])), $extensions)):
	    	$fail .= '<p>The uploaded profile picture is not an image file. Only JPG, JPEG, JP2, TIFF, PNG or JPE files is allowed.</p>';
	        $err++;
	    endif;
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter event title please!</p>';
		endif;
		/*if ( !(in_array( $userinfo->userrole, array('level0','level1')) and  $userinfo->active==true)):
			$err++;
			$fail.='<p>You are not authorized to post event!</p>';
		endif;*/
		if( empty(trim($posts->eventdate))):
			$err++;
			$fail.='<p>Enter a valid event date please!</p>';
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Enter event detail please!</p>';
		endif;
		if( $err==0 ):
			if( !file_exists("$targetDir") ):
		        mkdir("$targetDir", 0777, true);
		    endif;
		    $targetFile = $targetDir . $id."_event.png";
		    if( !empty($files->img_upload['tmp_name']) and @move_uploaded_file($files->img_upload['tmp_name'], $targetFile) ):
		    else:
		    	$targetFile=$event->image;
		    endif;
		    $posts->content=nl2br2($posts->content);
		    $posts->content=testInput($posts->content);
		    $posts->title=testInput($posts->title);
		    //id	updateid	title	content	image	addedby	dateadded
		    $ezDb->query("UPDATE `events` SET `title`='$posts->title', `content`='$posts->content', `eventdate`='$posts->eventdate', `image`='$targetFile', `author`='$posts->author' WHERE `token`='$id';");
			$event=$ezDb->get_row("SELECT * FROM `events` WHERE `token`='$id';");

			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> event was successfully updated.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
	if ( in_array($sitePage, array("event")) && ($requestMethod == 'POST') && isset($Site["post"]['delete_event']) ) {
		if ($err==0) {
		    if (!empty($event->image) and file_exists($event->image)):
		    	@unlink($event->image);
			endif;
			$ezDb->query("DELETE FROM `event` WHERE `token`='$id';");
			// Delete project file or images
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>event detail had been successfully deleted.</p></div>';
		}
	}

	$event->title=testinputReverse($event->title);
	$event->content=testinputReverse($event->content);
	$event->content_edit=br2nl2($event->content);
	$event->content_stripe=stripeInputReverse($event->content_edit);
	$event->content_stripe=str_replace("&quot;", "\"", $event->content_stripe);
	$event->content_stripe=str_replace("&nbsp;", " ", $event->content_stripe);
}else{
	redirect("events");
}
$smarty->assign("event", $event);