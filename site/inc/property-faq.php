<?php
if ( !empty($gets->id) ) {
	$mySearchFor=strtolower($gets->id);
	$product=$ezDb->get_row("SELECT *  FROM `projects` WHERE `token`='$mySearchFor'");
		
	$smarty->assign("property", $product);
	
}else{
    echo 'empty: not found';
}
$smarty->assign("project", $gets->id);