<?php
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;
$token=(!empty($gets->id)? $gets->id: "");

$client=$ezDb->get_row("SELECT * from `subscription` WHERE `token` = '$token';");

if(!empty($client)){
	$client->names = ucwords($client->surname.' '.$client->firstname.' '.$client->middlename);
	$client->plot_words = numberTowords($client->plot_number);
	$client->amount_words = numberTowords($client->total_amount);
	$client->initial_deposit = $client->total_amount * ($client->initial_percent) / 100;
	$client->amount_deposit_words = numberTowords($client->initial_deposit);
	$client->init_total = $client->amount * $client->plot_number;
	$client->property = $ezDb->get_row("SELECT * from `projects` WHERE `token` = '$client->project_token';");
	if($ezDb->get_row("SELECT * FROM `pdocuments` WHERE `reftoken` = '$client->token' AND `category` = 'subscription_letter'")){
		$client->saved = true;
	}else{
		$client->saved = false;
	}
}else{
	redirect("client_listing");
}

$smarty->assign("client", $client);
