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
$properties=$ezDb->get_results("SELECT *  FROM `projects` where `active` = 1");
$client=$ezDb->get_row("SELECT * from `subscription` WHERE `token`= '$subid'");

$totals = new stdClass();
$totals->subtotal = 0;
$totals->totalamount = 0;
$totals->totaldiscount = 0;
$totals->paymentmade = 0;
$totals->balance = 0;
if(!empty($client)){

    $invoices=$ezDb->get_results("SELECT * from `invoices` WHERE `subscriptionid` = '$subid' ORDER BY `id` ASC");
    $memo_log=$ezDb->get_results("SELECT * FROM `client_log` WHERE `email` = '$client->email' AND `subscriptionid` = '$subid';");
    if(!empty($memo_log)){
        foreach($memo_log as $value){
            $value->addedbyName = $ezDb->get_var("SELECT CONCAT(`firstname`,' ',`middlename`, ' ', `lastname`) FROM `userprofile` WHERE `email`='$value->addedby'");
        }
    }
    $payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subid' ");
    

    if( in_array($sitePage, array("view_subscription")) && ($requestMethod == 'POST') && $posts->triggers == 'update_log'){
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

    if(!empty($invoices)){
        foreach($invoices as $value){
            $invoiceid = $value->invoiceid;
            $duedate = $value->duedate;
            if($value->item == 'Defaut Fee Selling Price'){
                if(strtotime($duedate)<strtotime($dateNow) && $client->paid == 0){
                        $totals->subtotal += $value->total;
                }elseif(strtotime($duedate)<strtotime($dateNow) && $client->paid == 1){
                    if($client->total_paid<($client->total_paid+$value->total)){
                        $totals->subtotal += $value->total;
                    }
                }
            }else{
                $totals->subtotal += $value->total;
            }
            $totals->totaldiscount += $value->discount;
        }
        $totals->totalamount = $totals->subtotal - $totals->totaldiscount;
    }

    if( in_array($sitePage, array("view_subscription")) && ($requestMethod == 'POST') && $posts->triggers == 'change_plan'){
        
        $projectName=$ezDb->get_var("SELECT `name` FROM `projects` where `token` = '$posts->project'");

        $token = $token=getToken(8);
        $query = "INSERT INTO `subscription`(
            `token`,
            `project_token`,
            `product`,
            `img_filename`, 
            `title`,
            `surname`, 
            `middlename`, 
            `firstname`, 
            `sex`, 
            `marital_status`, 
            `spouse_surname`, 
            `spouse_firstname`, 
            `mobile`, 
            `phone`, 
            `email`,
            `address`, 
            `city`, 
            `lga`, 
            `state`, 
            `country`, 
            `dob`, 
            `id_mode`, 
            `id_mode_others`, 
            `id_number`, 
            `nationality`, 
            `state_of_origin`, 
            `occupation`, 
            `emp_name`, 
            `emp_address`,
            `nok_surname`,
            `nok_middlename`, 
            `nok_firstname`, 
            `nok_phone`, 
            `nok_address`, 
            `plot_type`, 
            `plot_number`, 
            `plot_sqm`, 
            `ref_fullname`, 
            `ref_phone`, 
            `ref_email`,
            `payment_type`,
            `total_amount`
            ) VALUES (
            '$token',
            '$posts->project',
            '$projectName',
            '$client->img_filename',
            '$client->title',
            '$client->surname',
            '$client->middlename',
            '$client->firstname',
            '$client->sex',
            '$client->marital_status',
            '$client->spouse_surname',
            '$client->spouse_firstname',
            '$client->mobile',
            '$client->phone',
            '$client->email',
            '$client->address',
            '$client->city',
            '$client->lga',
            '$client->state',
            '$client->country',
            '$client->dob',
            '$client->id_mode',
            '$client->id_mode_others',
            '$client->id_number',
            '$client->nationality',
            '$client->state_of_origin',
            '$client->occupation',
            '$client->emp_name',
            '$client->emp_address',
            '$client->nok_surname',
            '$client->nok_middlename',
            '$client->nok_firstname',
            '$client->nok_phone',
            '$client->nok_address',
            '$client->plot_type',
            '$client->plot_number',
            '$client->plot_sqm',
            '$client->ref_fullname',
            '$client->ref_phone',
            '$client->ref_email',
            '$client->payment_type',
            '$client->total_amount');";
        
        if($ezDb->query($query)):	
            $product=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$client->project_token'");
            $invoiceid = $ezDb->get_var("SELECT `invoiceid`  FROM `invoices` WHERE `subscriptionid`='$client->token' LIMIT 1");
            $ezDb->query("UPDATE `invoices` SET `subscriptionid` = '$token' WHERE `invoiceid` = '$invoiceid'");
            $ezDb->query("UPDATE `subscription` SET `status` = 3 WHERE `token` = '$client->token'");

            $fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Your subscription property has been successfully changed.</p></div>';
        endif;
    }

    $client->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$client->reg_date')");

    $client->amt=$ezDb->get_var("SELECT SUM(amount) AS `amt` FROM `payments` WHERE `token`='$client->token'");
    $client->outstanding = ($client->total_amount<=$client->total_paid)?0:$client->total_amount-$client->total_paid;




    $diff  = $client->datediff;
    //echo $diff;
    $month = $diff / 30.5; 
    //echo $month;
    $month = round($month);
    $client->months = $month; 
    $expected = [];
    $duration = $client->duration;
    $installment = $client->initial_percent;
    $installment = $installment/100;
    $first_pay = $installment * $client->total_amount;
    array_push($expected,$first_pay);

    $rem_amount = $client->total_amount - $first_pay;
    $client->to_have_paid = $first_pay;

    $stretch_pay = ($rem_amount / $duration);
    $stretch_pay = round($stretch_pay,2,PHP_ROUND_HALF_UP);
    for($i = 0; $i < $duration ; $i++){
        array_push($expected,$stretch_pay);
        $client->to_have_paid += $stretch_pay;
    }


    function getAmount($client, $startmonth, $endmonth){
        $duration = $client->duration;
        $diff = ceil(abs($endmonth - $startmonth) / 86400);
        $total_amount = $client->total_amount;
        //$diff  = (date('d',$endmonth) - date('d',$startmonth));
        
        //$to_have_paid =0;
        //echo "--start month = ".date('F Y', $startmonth)."---".date('F Y', $endmonth)."--";
        $mon = $diff / 30.5;
        $mon = round($mon);
        //echo "-----mon = ".date('m', $startmonth)."-----";
        //echo "-----month = ".$mon."-----";
        //$client->months = $month; 
        $install = $client->initial_percent;
        $install = $install/100;
        //echo 'installment---'.$client->initial_percent.'--';
        $first_pay = $install * $total_amount;
        $to_have_paid = $first_pay;
        if($mon == 0){
            return $first_pay;
        }else{
            $rem_amount = $total_amount - $first_pay;
            //echo "rem pay = ".$rem_amount."-----";
            $stretch_pay = (round($rem_amount / $duration));
            //echo $stretch_pay."--";
            for($i = 1; $i <= $mon ; $i++){
                $to_have_paid += $stretch_pay;       
            }
        
        }
        //echo "to have paid = ".$to_have_paid."---";
        return $to_have_paid;
    }

    function getEnd($str){
        $in_date =  date("Y-m-t", $str);
        return strtotime($in_date);
    }

    function addOne($str){
        return strtotime("+25 day", $str);
    }

    //payment plan
    $start = $mon = strtotime($client->reg_date);
    $d_1 = false;
    if(date('d', $start)<15){
        $d_1 = true;
    }
    $timer = "+$duration months";
    $end = strtotime($timer, strtotime($client->reg_date));
    $i = 0;
    $payment_plan = [];
    while($i <= $duration)
    {
        if($i>0){
            $mon = getEnd($mon);
        }
        array_push($payment_plan, (object)[
            'month' => date('F d, Y', $mon),
            'expected' => $expected[$i],
            'total_expected' => getAmount($client,$start,$mon),
            'total_paid' => $client->total_paid
        ]);
        if($d_1){
            $d_1 = false;
        }else{
            $mon = addOne($mon);
        }
        $i++;
    }
    $smarty->assign("subs", $client)->assign("payment_plan", $payment_plan)->assign("properties", $properties)->assign("limit", $limit)->assign("memo_log",$memo_log)->assign("payment", $payments)->assign("invoices", $invoices);

}

