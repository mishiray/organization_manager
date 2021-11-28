<?php
$whereClause=" status != 0";

$subscriptions=$ezDb->get_results("SELECT * from `agric_subscription` WHERE $whereClause AND `email` = '$userinfo->email' ORDER BY `reg_id` DESC");
if(!empty($subscriptions) and is_array($subscriptions)):
	foreach ($subscriptions as $value):
		$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
		$value->outstanding = ($value->total_amount<=$value->total_paid)?0:$value->total_amount-$value->total_paid;
		$value->plot_type = (($value->plot_type == 'land') ? 'Land' : ($value->plot_type == 'landr')) ? 'Land & Residence' : 'Lease/Rent';
		
		$value->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$value->reg_date')");
		$diff  = $value->datediff;
		$month = $diff / 30.5; 
		$month = round($month);
		$value->months = $month; 
	endforeach;
endif;
$smarty->assign("agric_subscriptions", $subscriptions);