<?php

$token=(!empty($gets->item)? $gets->item: "");
$table=(!empty($gets->type)? $gets->type: "");
$sub_table = ($table == 'projects') ? 'subscription' : 'agric_subscription';
//SELECT * FROM `subscription` WHERE `project_token`='ZHZGZiXg' AND `paid` = 1 AND `status` = 1

//$product=$ezDb->get_row("SELECT * FROM `$table` WHERE `id`='$token'");
$properties=$ezDb->get_results("SELECT DISTINCT `project_token` as token, product as name  FROM `$sub_table` WHERE `email` = '$userinfo->email'");
$smarty->assign("properties", $properties);


$token=(!empty($gets->id)? $gets->id: "");
if ( !empty($token) ) {
		
	
	$product=$ezDb->get_row("SELECT * FROM `$table` WHERE `token`='$token'");
	if (!empty($product) ) {
		
		$plot_num =  $product->totalplot;
		
		$allocations = $ezDb->get_var("SELECT `allocation` FROM `plot_allocation` WHERE `project_token`='$product->token'");
		if (!empty($allocations) ) {
			$taken_plot = [];
			$allocations = json_decode($allocations);
			foreach($allocations as $alloc){
				$keys = new stdClass();
				if(!empty($alloc->client)){
					$key = $alloc->id	;
					$keys->key = "$key";
					$keys->selected = true;
					$keys->isDeselectable = false;

					array_push($taken_plot,($keys));
				}
			}
			$taken_plot = json_encode($taken_plot);
			//print_r($taken_plot);
		}
	}
}
$smarty->assign("item", $product)->assign("taken_plot", $taken_plot)->assign("plot_num", $plot_num)->assign("allocations",$allocations)->assign("pclients",$pclients)->assign("table",$table)->assign("sub_table",$sub_table);
	
