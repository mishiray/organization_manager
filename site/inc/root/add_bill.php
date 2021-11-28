<?php 
$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0','level1','level6'))   && !in_array($userinfo->department, array('Accounting'))):
	//redirect("home");
endif;
$accounts = $ezDb->get_results("SELECT * FROM `bank_account` WHERE `status` = 1 ORDER BY `bank_name`;");
$vendors = $ezDb->get_results("SELECT * FROM `vendors` ORDER BY `vendor_name` ASC");
$products = $ezDb->get_results("SELECT `product_name`,`expense_category`,`price`,`tax` FROM `products` ORDER BY `product_name` ASC");

$query = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%income%' OR `type` LIKE '%income%' ORDER BY account_charts.id ASC";
$query2 = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%expense%' OR `type` LIKE '%expense%' ORDER BY account_charts.id ASC";

$income_accounts = $ezDb->get_results($query);
$expense_accounts = $ezDb->get_results($query2);


if (in_array($sitePage, array("add_bill")) && ($requestMethod == 'POST') && isset($Site["post"]['add_bill']) ) {	
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$tableData = stripcslashes($_POST['allData']);
	if(empty($posts->vendor)):
		$err++;
		$fail.='<p>Please select a vendor</p>';
	endif;
	if(strtotime($posts->date) > strtotime($posts->due_date)):
		$err++;
		$fail.='<p>Due date cannot be less than Bill Date</p>';
	endif;

	if ($err==0) {
			$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `bills` ORDER BY `id` DESC LIMIT 1;");
			$billId = 'bill'.getToken(5);
				$query = "INSERT INTO `bills` (`token`, `billid`, `data`, `total_amount`, `vendor_name`, `status`,`date`,`due_date`) VALUES 
												 ('$token','$billId','$tableData','$posts->total_amount','$posts->vendor',0,'$posts->date','$posts->due_date')";
				if($ezDb->query($query)){
					$report=$ezDb->get_row("SELECT * FROM `bills` WHERE `token`='$token';");
					logEvent($userinfo->email, "new-bill-added", $userinfo->usertype, 'bills', $report);
				}
			
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Bill has been successfully added.</p></div>';

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
	
}


$smarty->assign("accounts", $accounts)->assign("income_accounts", $income_accounts)->assign("expense_accounts", $expense_accounts)->assign("vendors",$vendors)->assign("products",$products);