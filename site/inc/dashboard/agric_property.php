<?php

$token=(!empty($gets->item)? $gets->item: "");


if ( !empty($token) ) {

	$product=$ezDb->get_row("SELECT *  FROM `agriculture` WHERE `id`='$token'");
	$product->land = json_decode($product->land);
	$product->lease = json_decode($product->lease);
	$product->landr = json_decode($product->landr);

	

}else{
}
$smarty->assign("item", $product);
	
