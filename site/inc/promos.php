<?php

$promo_token = (!empty($gets->token) ? $gets->token : '');
$promo_name = (!empty($gets->name) ? $gets->name : '');
$whereClause = "AND `status` = 1";
$promos=tableRoutine("promos", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');

$smarty->assign("promos", $promos);