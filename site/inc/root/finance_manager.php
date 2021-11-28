<?php 
$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0','level1','level6')) && !in_array($userinfo->department, array('Accounting'))):
	//redirect("home");
endif;
$accounts = $ezDb->get_results("SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%assets%' OR `type` LIKE '%assets%' ORDER BY account_charts.id ASC");
$query = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category`  LIKE '%income%' OR `type` NOT LIKE '%expense%' ORDER BY account_charts_cat.type ASC";
$query2 = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%expense%' OR `type` NOT LIKE '%income%' ORDER BY account_charts_cat.type ASC";
$income_accounts = $ezDb->get_results($query);
$expense_accounts = $ezDb->get_results($query2);


if (in_array($sitePage, array("finance_manager")) && ($requestMethod == 'POST') && isset($Site["post"]['addexpense']) ) {
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$tableData = stripcslashes($_POST['allData']);
	if ($err==0) {
			$tableData = json_decode($tableData);
			$cor = 0;
			$ncor = 0;
			//print_r($tableData);
			foreach($tableData as $data){
				$token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `finances` ORDER BY `id` DESC LIMIT 1;");
				$financeId = 'fin'.getToken(8);
				$data->beneficiary = strtolower($data->beneficiary);
				$data->category = strtolower($data->category);
				$data->sub_category = strtolower($data->sub_category);
				$data->details = strtolower($data->details);
				$data->description = strtolower($data->description);
				$query = "INSERT INTO `finances` (`token`, `financeid`, `bankaccount`, `payment`, `beneficiary`, `category`,`sub_category`,`details`, `description`,`amount` ,`transdate`,`status`) VALUES 
												 ('$token','$financeId','$data->bankaccount','$data->payment','$data->beneficiary','$data->category','$data->sub_category','$data->details','$data->description','$data->amount','$data->date',1)";
				if($ezDb->query($query)){
					$report=$ezDb->get_row("SELECT * FROM `finances` WHERE `token`='$token';");
					logEvent($userinfo->email, "new-finance-added", $userinfo->usertype, 'finances', $report);
					$cor++;
				}
				else{
				//	$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add transaction</div>';
				}
			}
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your '.$cor.' transaction(s) has been successfully added.</p></div>';

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
	
    //echo json_encode($tableData);
}


$smarty->assign("accounts", $accounts)->assign("income_accounts", $income_accounts)->assign("expense_accounts", $expense_accounts);