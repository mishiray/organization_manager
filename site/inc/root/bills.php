<?php 

$Site['post']= (array) $posts;
$query = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%assets%' OR `type` LIKE '%assets%' ORDER BY account_charts.id ASC";
$asset_accounts = $ezDb->get_results($query);

if (in_array($sitePage, array("bills")) && ($requestMethod == 'POST') && $posts->triggers=='add_payment') {
	$fail="";
	$err=0;
	$bill = $ezDb->get_row("SELECT * FROM `bills` WHERE `token` = '$posts->token'");

	if(empty($bill)):
		$err++;
		$fail.='<p>Bill not found</p>';
	endif;
	if(empty($posts->amount)):
		$err++;
		$fail.='<p>Please add amount</p>';
	endif;
	if(empty($posts->gateway)):
		$err++;
		$fail.='<p>Please select gateway</p>';
	endif;
	if(empty($posts->payment_account)):
		$err++;
		$fail.='<p>Please select payment account</p>';
	endif;
	if(empty($posts->transdate)):
		$err++;
		$fail.='<p>Please choose transaction date</p>';
	endif;
	
	if($err == 0){
		$transid = getToken(8);
		$query = "INSERT INTO `bill_payments` (`transid`,`bill_token`,`payment_account`,`amount`,`transdate`,`gateway`,`memo`,`status`,`status1`) VALUES 
					('$transid','$posts->token','$posts->payment_account','$posts->amount','$posts->transdate','$posts->gateway','$posts->memo',1,'success')";
		$ezDb->query($query);
		$result = logFinance($posts->token,$posts->amount,$posts->payment_account,$posts->gateway,$bill->vendor_name,'liabilities and credit cards','expected payments to vendors','accounts payable','bills',$posts->transdate,1);
		if($result){
			$amount_paid = $ezDb->get_var("SELECT SUM(`amount`) FROM `bill_payments` WHERE `bill_token` = '$posts->token'");
			if($amount_paid >= 0){
				$ezDb->query("UPDATE `bills` SET `status` = 1 WHERE `token` = '$posts->token'");
			}
			if($amount_paid >= $bill->total_amount){
				$ezDb->query("UPDATE `bills` SET `status` = 2 WHERE `token` = '$posts->token'");
			}
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Payment has been added </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add payment.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
	
}


$whereClause="id != 0 ";

$query = "SELECT * FROM `bills` ORDER BY `id` DESC";
$bills = $ezDb->get_results($query);


$smarty->assign("bills",$bills)->assign("asset_accounts",$asset_accounts);