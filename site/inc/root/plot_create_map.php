<?php

$token=(!empty($gets->item)? $gets->item: "");
//SELECT * FROM `subscription` WHERE `project_token`='ZHZGZiXg' AND `paid` = 1 AND `status` = 1
$product=$ezDb->get_row("SELECT *  FROM `projects` WHERE `id`='$token'");
if ( !empty($product) ) {
		
		
}
$smarty->assign("item", $product);
	
