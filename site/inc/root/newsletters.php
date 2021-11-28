<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2','level3', 'level4')) ):
	redirect("home");
endif;
$whereClause="`status`=1";

$fail="";
$err=0;

if ( in_array($sitePage, array("newsletters")) && ($requestMethod == 'GET') && !empty($Site["get"]['event']) && !empty($gets->id)) {
	$newsletter=$ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$gets->id';");
	if (!empty($newsletter)){

		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("home");
		endif;
		$ezDb->query("DELETE FROM `newsletter` WHERE `email`='$gets->id';");
	    logEvent($userinfo->email, "newsletter-user-deleted", $userinfo->usertype, 'newsletter', $newsletter);
		$fail="<p class='border badge-pill border-success'>Subscriber <b>".testInputReverse($newsletter->email)."</b> was successfully deteted</p>";
		$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$newsletters= $ezDb->get_results("SELECT * FROM `newsletter` WHERE $whereClause ORDER BY `id` DESC");
if (!empty($newsletters)) {
	if ( in_array($sitePage, array("newsletters")) && ($requestMethod == 'POST') && isset($Site["post"]['newsletter']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		// error_log(json_encode($files));
		/*$targetDir="site/media/news/";*/
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		/*if ( !empty($files->file_upload['tmp_name']) ):
	    	$fail .= '<p class="border badge-pill border-danger">The uploaded .</p>';
	        $err++;
	    endif;*/
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("home");
		endif;
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Enter newsletter title please!</p>';
		endif;
		if ( !(in_array( $userinfo->userrole, array('level0','level1')) and  $userinfo->active==true)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">You are not authorized to add news!</p>';
		endif;
		if( empty(trim($posts->author))):
			$posts->author="$userinfo->firstname $userinfo->lastname";
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Enter newsletter detail please.!</p>';
		endif;
		if( empty(trim($posts->sendto)) or !in_array($posts->sendto, array("*","#"))):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Choose a valid send to!</p>';
		elseif( $posts->sendto=='#'):
			if(empty($posts->receivers) or !is_array($posts->receivers) ):
				$err++;
				$fail.='<p class="border badge-pill border-danger">Kindly choose receiver(s)!</p>';
			else:
				if( $err==0 ):
					$newsletters = $posts->receivers;
					$posts->content_stripe=stripeInputReverse($posts->content);
					$posts->content_stripe=str_replace("&quot;", "\"", $posts->content_stripe);
					require_once 'mail_newsletter_new.php';
				else:
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
				endif;
			endif;
		elseif( $posts->sendto=='*'):
			$receivers = array();
			if(!empty($newsletters)){
				foreach ($newsletters as $key => $newsletter) {
					$receivers[] = $newsletter->email;
				}
				$newsletters = $receivers;
				$posts->content_stripe=stripeInputReverse($posts->content);
				$posts->content_stripe=str_replace("&quot;", "\"", $posts->content_stripe);
				require_once 'mail_newsletter_new.php';
			}

		endif;
		if( $err==0 ):
		    /*error_log($posts->content_stripe);
		    error_log(json_encode($posts));
		    error_log(json_encode($files));*/
		    /*$posts->title=testInputln($posts->title);*/
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Newsletters has been successfully sent</div>';
	    	logEvent($userinfo->email, "newsletter-sent", $userinfo->usertype, 'newsletter', $posts);
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
}
$newsletters= $ezDb->get_results("SELECT * FROM `newsletter` WHERE $whereClause ORDER BY `id` DESC");
$smarty->assign("newsletters", $newsletters);