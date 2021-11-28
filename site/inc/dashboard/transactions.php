<?php
//redirect("home");

$userinfo=$Site['session']['User']['userinfo'];
$whereClause="AND `paidby`='$userinfo->email' AND `statusmessage`='succeeded' ";

$transactions=$ezDb->get_results("SELECT * FROM `transactions` WHERE `madeby`='$userinfo->email';");

$smarty->assign("transactions", $transactions);