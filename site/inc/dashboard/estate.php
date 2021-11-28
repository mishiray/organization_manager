<?php
$token=(!empty($gets->item)? $gets->item: "");

if ( !empty($gets->item) ) {
	$mySearchFor=strtolower($gets->item);
	$product=$ezDb->get_row("SELECT *  FROM `projects` WHERE `token`='$mySearchFor'");
		
	$smarty->assign("item", $product);
	
}else{
    echo 'empty: not found';
}
$smarty->assign("project", $gets->item);

