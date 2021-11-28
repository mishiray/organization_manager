<?php


$token=(!empty($gets->item)? $gets->item: "");
$table=(!empty($gets->type)? $gets->type: "");
$sub_table = ($table == 'projects') ? 'subscription' : 'agric_subscription';

$properties=$ezDb->get_results("SELECT DISTINCT `project_token` as token, product as name  FROM `$sub_table` WHERE `email` = '$userinfo->email'");
$smarty->assign("properties", $properties);


$token=(!empty($gets->id)? $gets->id: "");

if(!empty($token)){

	$product=$ezDb->get_row("SELECT * FROM `$table` WHERE `token`='$token'");

	if (!empty($product) ) {
		$plot_num =  $product->totalplot;
		$allocations = $ezDb->get_var("SELECT `allocation` FROM `plot_allocation` WHERE `project_token`='$product->token'");
		if (!empty($allocations) ) {
			$allocations = json_decode($allocations);
		}
	
			
	}
}

$smarty->assign("item", $product)->assign("plot_num", $plot_num)->assign("allocations",$allocations)->assign("table",$table)->assign("sub_table",$sub_table);
