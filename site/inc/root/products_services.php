<?php 

$Site['post']= (array) $posts;

$query2 = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%expense%' OR `type` LIKE '%expense%' ORDER BY account_charts.id ASC";

$expense_accounts = $ezDb->get_results($query2);

if (in_array($sitePage, array("products_services")) && ($requestMethod == 'POST') && $posts->triggers=='add_product') {
	$fail="";
	$err=0;
	if(empty($posts->product_name)):
		$err++;
		$fail.='<p>Please add product name</p>';
	endif;
	if(empty($posts->description)):
		$err++;
		$fail.='<p>Please add description</p>';
	endif;
	if(empty($posts->expense_category)):
		$err++;
		$fail.='<p>Please add expense category</p>';
	endif;
	if(empty($posts->price)):
		$err++;
		$fail.='<p>Please add price</p>';
	endif;
	if(empty($posts->tax)):
		$posts->tax= 0;
	endif;
	if($err == 0){
		$token = getToken(6);
		$query = "INSERT INTO `products` (`token`,`product_name`,`description`,`expense_category`,`price`,`tax`) VALUES 
											('$token','$posts->product_name','$posts->description','$posts->expense_category','$posts->price','$posts->tax')";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> New Product added </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}

if (in_array($sitePage, array("products_services")) && ($requestMethod == 'POST') && $posts->triggers=='update_product') {
	$fail="";
	$err=0;
	
	if(empty($posts->product_name)):
		$err++;
		$fail.='<p>Please add product name</p>';
	endif;
	if(empty($posts->description)):
		$err++;
		$fail.='<p>Please add description</p>';
	endif;
	if(empty($posts->expense_category)):
		$err++;
		$fail.='<p>Please add expense category</p>';
	endif;
	if(empty($posts->price)):
		$err++;
		$fail.='<p>Please add price</p>';
	endif;
	if(empty($posts->tax)):
		$posts->tax= 0;
	endif;
	if($err == 0){
		$query = "UPDATE `products` SET `product_name` = '$posts->product_name',`expense_category` = '$posts->expense_category', `description` = '$posts->description',`price` = '$posts->price',`tax`='$posts->tax' WHERE `token` = '$posts->token' ";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Product Service Updated </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to update.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}


if (in_array($sitePage, array("products_services")) && ($requestMethod == 'POST') && $posts->triggers=='delete_product') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `products` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `products` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			logEvent($userinfo->email, "products-deleted", $userinfo->usertype, 'products', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Product Deleted</p></div>';
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to delete. kindly re-try</p></div>';
		}
	else: 
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured. Invalid token</p></div>';
	endif;
}


$whereClause="id != 0 ";

$query = "SELECT * FROM `products` ORDER BY `id` DESC";
$products = $ezDb->get_results($query);


$smarty->assign("expense_accounts", $expense_accounts)->assign("products", $products);