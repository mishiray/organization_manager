<?php 

$Site['post']= (array) $posts;

$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");


$filter = new stdClass();


if (in_array($sitePage, array("accounting_charts")) && ($requestMethod == 'POST') && $posts->triggers=='account_group') {
	$fail="";
	$err=0;
	if(empty($posts->acc_type)):
		$err++;
		$fail.='<p>Account type cannot be empty. Please select</p>';
	endif;
	if(!empty($ezDb->query("SELECT * FROM `account_charts_cat` WHERE `type` = '$posts->acc_type' AND `category` = '$posts->acc_name'"))):
		$err++;
		$fail.='<p>Account category already exists</p>';
	endif;
	if(empty($posts->acc_name)):
		$err++;
		$fail.='<p>Please enter account category</p>';
	endif;
	$posts->acc_name = strtolower($posts->acc_name);
	$posts->acc_type = strtolower($posts->acc_type);
	if($err == 0){
		$query = "INSERT INTO `account_charts_cat` (`type`,`category`) VALUES ('$posts->acc_type','$posts->acc_name')";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>You have successfully added a new category to '.$posts->acc_type.'</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
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
	$posts->acc_name = strtolower($posts->acc_name);
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

$query = "SELECT `type` FROM `account_charts_type` ORDER BY `type` ASC";
$charts = $ezDb->get_col($query);

$query2 = "SELECT * FROM `account_charts_cat` ORDER BY `type`,`category` ASC";
$categories = $ezDb->get_results($query2);

$query3 = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id $whereClause ORDER BY account_charts_cat.type, account_charts.account ASC";
$accounts = $ezDb->get_results($query3);

if(!empty($charts)){

}

$smarty->assign("months",$months)->assign("filter",$filter)->assign("charts",$charts)->assign("categories",$categories)->assign("accounts",$accounts);