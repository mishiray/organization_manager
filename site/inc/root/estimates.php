<?php 

$Site['post']= (array) $posts;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");


$filter = new stdClass();

if (in_array($sitePage, array("estimates")) && ($requestMethod == 'POST') && $posts->triggers=='add_estimate') {
	$fail="";
	$err=0;
	if(empty($posts->customer)):
		$err++;
		$fail.='<p>Please add Customer</p>';
	endif;
	if(empty($posts->date)):
		$err++;
		$fail.='<p>Please select date</p>';
	endif;
	if(empty($posts->expiry_date)):
		$err++;
		$fail.='<p>Please select expiry date</p>';
	endif;
	if(empty($posts->product)):
		$err++;
		$fail.='<p>Please choose product</p>';
	endif;
	if(empty($posts->description)):
		$err++;
		$fail.='<p>Please enter description</p>';
	endif;
	if(empty($posts->quantity)):
		$err++;
		$fail.='<p>Please enter quantity</p>';
	endif;
	if(empty($posts->price)):
		$err++;
		$fail.='<p>Please enter price</p>';
	endif;
	if(empty($posts->amount)):
		$err++;
		$fail.='<p>Please enter amount</p>';
	endif;
	if(empty($posts->tax)):
		$posts->tax = 0;
	endif;
	strtolower($posts->customer);
	if($err == 0){
		$token = getToken(6);
		$query = "INSERT INTO `estimates` (`token`,`customer`,`product`,`description`,`quantity`,`price`,`amount`,`tax`,`expiry`,`date`) VALUES 
											('$token','$posts->customer','$posts->product','$posts->description','$posts->quantity','$posts->price','$posts->amount','$posts->tax','$posts->expiry_date','$posts->date')";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> New estimate added </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}
if (in_array($sitePage, array("estimates")) && ($requestMethod == 'POST') && $posts->triggers=='update_estimate') {
	$fail="";
	$err=0;
	if(empty($posts->customer)):
		$err++;
		$fail.='<p>Please add Customer</p>';
	endif;
	if(empty($posts->date)):
		$err++;
		$fail.='<p>Please select date</p>';
	endif;
	if(empty($posts->expiry_date)):
		$err++;
		$fail.='<p>Please select expiry date</p>';
	endif;
	if(empty($posts->product)):
		$err++;
		$fail.='<p>Please choose product</p>';
	endif;
	if(empty($posts->description)):
		$err++;
		$fail.='<p>Please enter description</p>';
	endif;
	if(empty($posts->quantity)):
		$err++;
		$fail.='<p>Please enter quantity</p>';
	endif;
	if(empty($posts->price)):
		$err++;
		$fail.='<p>Please enter price</p>';
	endif;
	if(empty($posts->amount)):
		$err++;
		$fail.='<p>Please enter amount</p>';
	endif;
	if(empty($posts->tax)):
		$posts->tax = 0;
	endif;
	strtolower($posts->customer);
	if($err == 0){
		$query = "UPDATE `estimates` SET `customer` = '$posts->customer',`product` = '$posts->product',`description` = '$posts->description',`quantity`='$posts->quantity',`price`='$posts->price',`amount`='$posts->amount',`tax`='$posts->tax',`expiry`='$posts->expiry_date',`date` = '$posts->date' WHERE `token` = '$posts->token' ";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Estimate Updated </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to update.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}


if (in_array($sitePage, array("estimates")) && ($requestMethod == 'POST') && $posts->triggers=='delete_estimate') {
	if (!empty($posts->token)):
		$report=$ezDb->get_row("SELECT * FROM `estimates` WHERE `token`='$posts->token'");
		$query = "DELETE FROM `estimates` WHERE `token` = '$posts->token'";
		if($ezDb->query($query)){
			logEvent($userinfo->email, "estimate-deleted", $userinfo->usertype, 'estimates', $report);
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Estimate Deleted</p></div>';
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to delete. kindly re-try</p></div>';
		}
	else: 
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured. Invalid token</p></div>';
	endif;
}

if (in_array($sitePage, array("accounting_charts")) && ($requestMethod == 'POST') && $posts->triggers=='account_names') {
	$fail="";
	$err=0;
	if(empty($posts->acc_cat)):
		$err++;
		$fail.='<p>Account category cannot be empty. Please select</p>';
	endif;
	if(!empty($ezDb->query("SELECT * FROM `account_charts` WHERE `category_id` = '$posts->acc_cat' AND `account` = '$posts->acc_name'"))):
		$err++;
		$fail.='<p>Account already exists</p>';
	endif;
	if(empty($posts->acc_name)):
		$err++;
		$fail.='<p>Please enter account name</p>';
	endif;
	strtolower($posts->acc_name);
	if($err == 0){
		$query = "INSERT INTO `account_charts` (`account`,`category_id`) VALUES ('$posts->acc_name','$posts->acc_cat')";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>You have successfully added a new account </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}


$whereClause="WHERE account_charts.id != 0 ";
if (in_array($sitePage, array("accounting_charts")) && ($requestMethod == 'POST') && $posts->triggers=='filter_accounts') {

	if (empty($posts->type)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND account_charts_cat.type = '$posts->type' ";
		$filter->type = $posts->type;
	endif;
	if (empty($posts->cat)):
		$whereClause .= "";
	else: 
		$whereClause .= " AND account_charts.category_id = '$posts->cat' ";
		$filter->cat = $posts->cat;
	endif;
}else{
	$whereClause .= "";
}

$query = "SELECT `name`, IF(`amt_400sqm`=NULL,`amt_400sqm`,(`amt_550sqm`)) as amount FROM `projects` WHERE `active` = 1 ORDER BY `name` ASC";
$products = $ezDb->get_results($query);

$query = "SELECT DISTINCT `customer` FROM `estimates` ORDER BY `customer` ASC";
$customers = $ezDb->get_col($query);

$query = "SELECT * FROM `estimates` ORDER BY `id` DESC";
$estimates = $ezDb->get_results($query);

if(!empty($charts)){

}

$smarty->assign("months",$months)->assign("filter",$filter)->assign("products",$products)->assign("customers",$customers)->assign("estimates",$estimates);