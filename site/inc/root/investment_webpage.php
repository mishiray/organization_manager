<?php

if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("dashboard");
endif;

$userinfo=$Site['session']['User']['userinfo'];

$investment = $ezDb->get_row("SELECT * FROM `webpages` WHERE `title_page`= 'investment' ");
//print_r($investment);
if(!empty($investment)){
	$fail="";
	$err=0;
	if ( in_array($sitePage, array("investment_webpage")) && ($requestMethod == 'POST') && isset($Site["post"]['update_page']) ) {
		$posts = (object) $Site["post"];
		$files= (object) $Site["files"];
		$extensions = array("image/jpg","image/png","image/jpe","image/jpeg", "image/jp2", "image/tiff", "image/tiff");
		
		if ( !(in_array( $userinfo->userrole, array('level0','level1', 'level2')) and  $userinfo->active==true)):
			$err++;
			$fail.='<p>You are not authorized to update webpage!</p>';
		endif;
		if( empty(trim($posts->content)) ):
			$err++;
			$fail.='<p>Fill up webpage content please!</p>';
		endif;
		if( $err==0 ):
			$posts->content=nl2br2($posts->content);
			$posts->content=tb2sp2($posts->content);
			$posts->content=testInput($posts->content);
	
			$ezDb->query("UPDATE `webpages` SET `content` = '$posts->content', `status` = '$posts->status', `dateupdated` = '$dateNow'  WHERE `title_page` = 'investment' ");
			
			$investment = $ezDb->get_row("SELECT * FROM `webpages` WHERE `title_page`= 'investment' ");
			logEvent($userinfo->email, "updated-investment-webpage", $userinfo->usertype, 'webpages', $investment);
				
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Webpage was successfully edited.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}

	$investment->contentln=testinputReverse($investment->content);
	$investment->content=testinputReverse($investment->content);
	$investment->content_stripe=stripeInputReverse($investment->contentln);
	$investment->content_stripe=str_replace("&quot;", "\"", $investment->content_stripe);
}

$smarty->assign("investment",$investment);