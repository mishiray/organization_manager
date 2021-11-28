<?php
$subid=(!empty($gets->id)? $gets->id: "");

$whereClause="";

$client=$ezDb->get_row("SELECT * from `subscription` WHERE `token`= '$subid'");

if(!empty($client)){

    $payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subid' ");
    $invoices=$ezDb->get_results("SELECT * from `invoices` WHERE `subscriptionid` = '$subid' ORDER BY `id` ASC");
    $memo_log=$ezDb->get_results("SELECT * FROM `client_log` WHERE `email` = '$client->email' AND `subscriptionid` = '$subid';");
        

    $totals = new stdClass();
    $totals->subtotal = 0;
    $totals->totalamount = 0;
    $totals->totaldiscount = 0;
    $totals->paymentmade = 0;
    $totals->balance = 0;
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
    $stretch_pay = (round($rem_amount / $duration,2));

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
}
$smarty->assign("subs", $client)->assign("payment_plan", $payment_plan)->assign("payments",$payments)->assign("memo_log",$memo_log)->assign("invoices",$invoices);