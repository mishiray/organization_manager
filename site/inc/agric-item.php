<?php
if ( !empty($gets->item) ) {
	$mySearchFor=strtolower($gets->item);
	$product=$ezDb->get_row("SELECT *  FROM `agriculture` WHERE `token`='$mySearchFor'");
		
	$product->land = json_decode($product->land);
	$product->lease = json_decode($product->lease);
	$product->landr = json_decode($product->landr);

	$smarty->assign("item", $product);
	
}else{
    echo 'empty: not found';
}
$smarty->assign("agric_project", $gets->item);

