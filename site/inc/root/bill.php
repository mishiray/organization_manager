<?php 
$id=(!empty($gets->id)? $gets->id: "");
$bill=$ezDb->get_row("SELECT * FROM `bills` WHERE `token`='$id';");

if (!empty($bill)){
	$bill->payment = $ezDb->get_results("SELECT * FROM `bill_payments` WHERE `bill_token`='$bill->token';");
	$bill->vendor = $ezDb->get_row("SELECT * FROM `vendors` WHERE `vendor_name`='$bill->vendor_name';");

	$tableData = $bill->data;
	$json  = json_decode($tableData, true);
	$val = new stdClass();
	$myArray = [];
	if(!empty($json)){
		$num = 0;
		foreach ($json as $Key=>$value) {
			array_push($myArray, (object)[
				'item' => $value['item'],
				'expense_category' => $value['expense_category'],
				'description' => $value['description'],
				'quantity' => $value['quantity'],
				'price' => $value['price'],
				'tax' => $value['tax'],
				'amount' => $value['amount']
			]);
		}
	}


	
if (in_array($sitePage, array("bill")) && ($requestMethod == 'POST') && isset($Site["post"]['update_bill']) ) {	
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$tableData = stripcslashes($_POST['allData']);
	if(strtotime($posts->date) > strtotime($posts->due_date)):
		$err++;
		$fail.='<p>Due date cannot be less than Bill Date</p>';
	endif;
	if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;

	if ($err==0) {
			$query = "UPDATE `bills` SET `data` ='$tableData', `total_amount` = '$posts->total_amount', `due_date`='$posts->due_date' WHERE `token` = '$bill->token'";
				if($ezDb->query($query)){
					$bill=$ezDb->get_row("SELECT * FROM `bills` WHERE `token`='$bill->token';");
					logEvent($userinfo->email, "bill-updated", $userinfo->usertype, 'bills', $bill);
				}
			
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Bill has been successfully added.</p></div>';

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
	
}

}


$accounts = $ezDb->get_results("SELECT * FROM `bank_account` WHERE `status` = 1 ORDER BY `bank_name`;");
$vendors = $ezDb->get_results("SELECT * FROM `vendors` ORDER BY `vendor_name` ASC");
$products = $ezDb->get_results("SELECT `product_name`,`expense_category`,`price`,`tax` FROM `products` ORDER BY `product_name` ASC");

$query = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%income%' OR `type` LIKE '%income%' ORDER BY account_charts.id ASC";
$query2 = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%expense%' OR `type` LIKE '%expense%' ORDER BY account_charts.id ASC";

$income_accounts = $ezDb->get_results($query);
$expense_accounts = $ezDb->get_results($query2);


$smarty->assign("accounts", $accounts)->assign("income_accounts", $income_accounts)->assign("expense_accounts", $expense_accounts)->assign("vendors",$vendors)->assign("products",$products)->assign("bill",$bill)->assign("data",$myArray);