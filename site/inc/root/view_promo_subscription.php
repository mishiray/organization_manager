<?php

$subid=(!empty($gets->id)? $gets->id: "");

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

$settings=getSettings();
if(!empty($settings->subscription_change)){
    $limit = $settings->subscription_change->days;
}else{
    $limit = 30;
}
$whereClause="";
$promos=$ezDb->get_results("SELECT *  FROM `promos` where `status` = 1");
$client=$ezDb->get_row("SELECT * from `promos_subscription` WHERE `token`= '$subid'");

$totals = new stdClass();
$totals->subtotal = 0;
$totals->totalamount = 0;
$totals->totaldiscount = 0;
$totals->paymentmade = 0;
$totals->balance = 0;
if(!empty($client)){

    $client->promo=$ezDb->get_row("SELECT * from `promos` WHERE `token`= '$client->promo_token'");
    $memo_log=$ezDb->get_results("SELECT * FROM `client_log` WHERE `email` = '$client->email' AND `subscriptionid` = '$subid';");
    if(!empty($memo_log)){
        foreach($memo_log as $value){
            $value->addedbyName = $ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$value->addedby'");
        }
    }
    $payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subid' ");
    

    if( in_array($sitePage, array("view_promo_subscription")) && ($requestMethod == 'POST') && $posts->triggers == 'update_log'){
        $fail="";
        $err=0;
        if( empty(trim($posts->content))):
            $err++;
            $fail.='<p>Kindly enter content</p>';
        endif;

	    if( $err==0 ):
            $token=getToken("5") .($ezDb->get_var("SELECT IFNULL((`id`+1),'1') FROM `client_log` ORDER BY `id` DESC LIMIT 1;"));
	        $query = "INSERT INTO `client_log` (`subscriptionid`,`token`,`addedby`,`email`,`content`) VALUES ('$subid','$token','$userinfo->email','$client->email','$posts->content')";
            if($ezDb->query($query)){
                $memo_log=$ezDb->get_results("SELECT * FROM `client_log` WHERE `email` = '$client->email' AND `subscriptionid` = '$subid';");
                logEvent($userinfo->email, "updated-client-log", $userinfo->usertype, 'client_log', $posts);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Log was successfully updated	.</p></div>';
			}
        endif;
    }


    $client->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$client->dateadded')");

    $client->amt=$ezDb->get_var("SELECT SUM(amount) AS `amt` FROM `payments` WHERE `token`='$client->token'");


    $diff  = $client->datediff;
    //echo $diff;
    $month = $diff / 30.5; 
    //echo $month;
    $month = round($month);
    $client->months = $month; 

    $smarty->assign("subs", $client)->assign("promos", $promos)->assign("limit", $limit)->assign("memo_log",$memo_log)->assign("payment", $payments);

}

