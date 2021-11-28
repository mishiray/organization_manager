<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3')) ):
	redirect("home");
endif;

if ( in_array($sitePage, array("client_listing")) && ($requestMethod == 'POST') && isset($posts->triggers) && $posts->triggers == 'legal_doc_1' ) {
		
	$fail="";
	$err=0;

	if(empty($posts->sub_token)):
		$err++;
		$fail.='<p>Cannot find client data!</p>';
	endif;
	if($err == 0){
		if($ezDb->query("UPDATE `subscription` SET `status` = 4 WHERE `token` = '$posts->sub_token'")){
			$subitem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$posts->sub_token';");
			logEvent($userinfo->email, "subscription-letter-sent-manually", $userinfo->usertype, 'subscription', $subitem);
			alertUser($subitem->email,0,"Subscription Letter has been sent for $subitem->product");
			$subscriptions=$ezDb->get_results("SELECT * from `subscription` ORDER BY `reg_id` DESC");
		  	$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Subscription Letter has been sent to Client</p></div>';
		}else{
			$fail.='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Try again!</p></div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';		
	}
}

if ( in_array($sitePage, array("client_listing")) && ($requestMethod == 'POST') && isset($posts->triggers) && $posts->triggers == 'legal_doc_2' ) {
		
	$fail="";
	$err=0;

	if(empty($posts->sub_token)):
		$err++;
		$fail.='<p>Cannot find client data!</p>';
	endif;
	if($err == 0){
		if($ezDb->query("UPDATE `subscription` SET `status` = 5 WHERE `token` = '$posts->sub_token'")){
			$subitem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token`='$posts->sub_token';");
			logEvent($userinfo->email, "contract-of-sale-sent-manually", $userinfo->usertype, 'subscription', $subitem);
			$subscriptions=$ezDb->get_results("SELECT * from `subscription` ORDER BY `reg_id` DESC");
			alertUser($subitem->email,0,"Contract of sale has been sent for $subitem->product");
		$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Contract of sale has been sent to Client</p></div>';
		}else{
			$fail.='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Try again!</p></div>';
		
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';		
	}
}

$whereClause="";

$subscriptions=$ezDb->get_results("SELECT * from `subscription` WHERE `status` != 0 ORDER BY `reg_id` DESC");
if(!empty($subscriptions) and is_array($subscriptions)):
	foreach ($subscriptions as $value):
		$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
		$value->outstanding = ($value->total_amount<=$value->total_paid)?0:$value->total_amount-$value->total_paid;
		
		$value->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$value->reg_date')");
		$diff  = $value->datediff;
		$month = $diff / 30.5; 
		$month = round($month);
		$value->months = $month; 
	endforeach;
endif;
$smarty->assign("subscriptions", $subscriptions);