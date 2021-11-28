<?php

$userinfo=$Site['session']['User']['userinfo'];
$whereClause="";


$transactions=$ezDb->get_results("SELECT * FROM `transactions`;");


$smarty->assign("transactions", $transactions);