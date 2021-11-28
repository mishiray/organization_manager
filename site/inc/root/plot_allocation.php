<?php

$token=(!empty($gets->item)? $gets->item: "");
$table=(!empty($gets->type)? $gets->type: "");
//SELECT * FROM `subscription` WHERE `project_token`='ZHZGZiXg' AND `paid` = 1 AND `status` = 1
$sub_table = ($table == 'projects') ? 'subscription' : 'agric_subscription';
$product=$ezDb->get_row("SELECT *  FROM `$table` WHERE `id`='$token'");
if ( !empty($product) ) {
		
	if(!empty($posts->triggers) and $posts->triggers=='allocate_plot'){
		$fail="";
		$err=0;
		$plots  = trim($posts->plotid);
		$plots = explode(',',$plots);
		$client = $ezDb->get_row("SELECT * FROM `$sub_table` WHERE `token`='$posts->client'");
		if( empty($posts->client) ):
			$fail.='<p>Select Client Please</p>';
			$err++;
		endif;
		if( empty($posts->plotid) ):
			$fail.='<p>Select Plot Space Please</p>';
			$err++;
		endif;
		if(count($plots) != $client->plot_number):
			$fail.='<p>Plots assigned not equal to client plot number</p>';
			$err++;
		endif;

		if($err == 0){
			$allocats = $ezDb->get_var("SELECT `allocation` FROM `plot_allocation` WHERE `project_token`='$product->token'");
		
			$allocats = json_decode($allocats);
			
			foreach($allocats as $al){
				//$al->dateadded = '';
				if(in_array($al->id, $plots)){
					$al->token = $client->token;
					$al->client = $client->email;
					$al->dateadded = $dateNow;
				}
			}
			//print_r($allocats);
			$query = "UPDATE `plot_allocation` SET `allocation` = '".json_encode($allocats)."' WHERE `project_token`='$product->token'";
			if($ezDb->query($query)){
				$query = "UPDATE `$sub_table` SET `status` = 6 WHERE `project_token`='$product->token' AND `token` = '$client->token'";
				$ezDb->query($query);
				alertUser2($client->email,0,"Your Plot has been Allocated for $product->name","plot_allocation?id=$product->token");
				$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Plots Allocated Successfully</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to allocate. kindly re-try</p></div>';
			}
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	
	if(!empty($posts->triggers) and $posts->triggers=='recreate_allocation'){
		$allocations_array = [];
		for($i = 1; $i <= $product->totalplot; $i++){
			$allocate = new stdClass();
			$allocate->id = $i;
			$allocate->token = '';
			$allocate->client = '';
			$allocate->dateadded = '';
			array_push($allocations_array, $allocate);
		}
		if(empty($ezDb->get_var("SELECT `allocation` FROM `plot_allocation` WHERE `project_token`='$product->token'"))){
			$query = "INSERT INTO `plot_allocation` (`project_token`, `allocation`, `dateadded`) VALUES
												('$product->token','".json_encode($allocations_array)."','$dateNow')";
		}else{
			$query = "UPDATE `plot_allocation` SET `allocation` = '".json_encode($allocations_array)."' WHERE `project_token`='$product->token'";
		}
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Plots Allocation Recreated Successfully</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to recreate. kindly re-try</p></div>';
		}
	}

	

	$pclients=$ezDb->get_results("SELECT * FROM `$sub_table` WHERE `project_token`='$product->token' AND `paid` = 1 AND `status` = 5");
	$plot_num =  $product->totalplot;
	$allocations = $ezDb->get_var("SELECT `allocation` FROM `plot_allocation` WHERE `project_token`='$product->token'");
	if (!empty($allocations) ) {
		$allocations = json_decode($allocations);
	}

		
}
$smarty->assign("item", $product)->assign("plot_num", $plot_num)->assign("allocations",$allocations)->assign("pclients",$pclients)->assign("table",$table)->assign("sub_table",$sub_table);
	
