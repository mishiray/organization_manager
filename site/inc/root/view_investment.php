<?php

$subid=(!empty($gets->id)? $gets->id: "");

$whereClause="";

$client=$ezDb->get_row("SELECT * from `investment_subscription` WHERE `token`= '$subid'");

if(!empty($client)){
    
    $client->datediff = $ezDb->get_var("SELECT DATEDIFF(CURDATE(),'$client->reg_date')");
    $client->invest=$ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$client->investments_id'");
    $memos=$ezDb->get_results("SELECT * FROM `memo_crm` WHERE (`receivers` LIKE'%\"$client->email\"%') ORDER BY `dateadded` DESC;");
    $payments=$ezDb->get_results("SELECT * from `payments` WHERE `token`='$subid' ");
}

$smarty->assign("subs", $client)->assign("memos",$memos)->assign("payment", $payments);