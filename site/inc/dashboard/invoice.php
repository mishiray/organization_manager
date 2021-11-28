<?php

$userinfo=$Site["session"]["User"]["userinfo"];

$id = (!empty($gets->id)) ? $gets->id : "";

$invoices=$ezDb->get_results("SELECT * from `invoices` WHERE `subscriptionid` = '$id' ORDER BY `id` ASC");
$subscription = $ezDb->get_row("SELECT * from `subscription` WHERE `token` = '$id'");
if(empty($subscription)){
	$subscription = $ezDb->get_row("SELECT * from `agric_subscription` WHERE `token` = '$id'");
}
$payments = $ezDb->get_results("SELECT * from `payments` WHERE `token` = '$id' AND `status` = 1");
//print_r($payments);
$totals = new stdClass();
$totals->subtotal = 0;
$totals->totalamount = 0;
$totals->totaldiscount = 0;
$totals->paymentmade = 0;
$totals->balance = 0;
$invoiceid = '';
if(!empty($invoices)){
	foreach($invoices as $value){
		$invoiceid = $value->invoiceid;
		$duedate = $value->duedate;
		if($value->item == 'Defaut Fee Selling Price'){
			if(strtotime($duedate)<strtotime($dateNow) && $subscription->paid == 0){
					$totals->subtotal += $value->total;
			}elseif(strtotime($duedate)<strtotime($dateNow) && $subscription->paid == 1){
				if($subscription->total_paid<($subscription->total_paid+$value->total)){
					$totals->subtotal += $value->total;
				}
			}
		}else{
			$totals->subtotal += $value->total;
		}
		$totals->totaldiscount += $value->discount;
	}
	$totals->totalamount = $totals->subtotal - $totals->totaldiscount;
	if($ezDb->get_row("SELECT * FROM `pdocuments` WHERE `reftoken` = '$invoiceid'")){
		$saved = true;
	}else{
		$saved = false;
	}
}
if(!empty($payments)){
	foreach($payments as $value){
		$totals->paymentmade += $value->amount;
	}
	$totals->balance = $totals->totalamount - $totals->paymentmade;
}else{
	$totals->balance = $totals->totalamount - $totals->paymentmade;
}
if($totals->paymentmade>=$totals->totalamount){
	$totals->balance = 0;
}
$smarty->assign("invoices", $invoices)->assign("totals", $totals)->assign("subscription", $subscription)->assign("receipts", $payments)->assign("saved", $saved)->assign("domainName", $domainName)->assign("dateNow", $dateNow)->assign("invoiceid", $invoiceid)->assign("duedate", $duedate);