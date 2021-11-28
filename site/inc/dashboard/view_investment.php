<?php

$subid=(!empty($gets->id)? $gets->id: "");

$whereClause="";

$client=$ezDb->get_row("SELECT * from `investment_subscription` WHERE `token`= '$subid'");
$payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subid' ");

$client->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$client->reg_date')");

$client->payment=$ezDb->get_var("SELECT SUM(amount) AS `amt` FROM `payments` WHERE `token`='$client->token'");

$client->invest=$ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$client->investments_id'");

$smarty->assign("subs", $client)->assign("payments",$payments);