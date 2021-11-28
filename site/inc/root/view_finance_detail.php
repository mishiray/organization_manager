<?php

$financeid = ($gets->id) ? $gets->id : '';
$accounts = $ezDb->get_results("SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%assets%' OR `type` LIKE '%assets%' ORDER BY account_charts.id ASC");
$query = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%income%' OR `type` LIKE '%income%' ORDER BY account_charts.id ASC";
$query2 = "SELECT `type`,`category`,`account`,account_charts.dateadded as dateadded FROM `account_charts` INNER JOIN `account_charts_cat` ON account_charts.category_id = account_charts_cat.id WHERE `category` LIKE '%expense%' OR `type` LIKE '%expense%' ORDER BY account_charts.id ASC";
$income_accounts = $ezDb->get_results($query);
$expense_accounts = $ezDb->get_results($query2);

$projects=$ezDb->get_results("SELECT * FROM `projects` ORDER BY `id` DESC;");
if (!empty($projects)) {
	foreach ($projects as $key => $project) {
		$project->images=@json_decode($project->images);
		$project->content2=testInputReverseln(trim($project->content));
		$project->content=testInputReverse(trim($project->content));
		$project->title2=testInputReverseln(trim($project->title));
		$project->title=testInputReverse(trim($project->title));
		$project->type2=testInputReverseln($project->type);
		$project->type=testInputReverse($project->type);
		
	}
}

$finance = $ezDb->get_row("SELECT * from `finances` WHERE `financeid` = '$financeid';");
if(!empty($finance)){
	if (in_array($sitePage, array("view_finance_detail")) && ($requestMethod == 'POST') && $posts->triggers = 'update_transaction' ){
		$err = 0;
		$fail = '';
		if(empty(trim($posts->date))){
			$err++;
			$fail .= '<p>Please select transaction date</p>';
		}
		if(empty(trim($posts->account))){
			$err++;
			$fail .= '<p>Please select bank account</p>';
		}
		if(empty(trim($posts->payment))){
			$err++;
			$fail .= '<p>Please choose payment type</p>';
		}
		if(empty(trim($posts->person))){
			
			$fail .= '<p>Please enter beneficiary name</p>';
			$err++;
		}
		if(empty(trim($posts->category))){
			$fail .= '<p>Please select category</p>';
			$err++;
		}
		if(empty(trim($posts->purpose))){
			$fail .= '<p>Please select details</p>';			
			$err++;
		}
		if(empty(trim($posts->description))){
			$fail .= '<p>Please enter description</p>';
			
			$err++;
		}
		if(empty(trim($posts->amount))){
			$fail .= '<p>Please enter amount</p>';
			$err++;
		}
		if($posts->amount <= 0){
			$fail .= '<p>Please amount cannot be less than zero</p>';
			$err++;
		}
		if($err == 0){
			$query = "UPDATE `finances` SET `bankaccount`= '$posts->account', `payment`='$posts->payment',`beneficiary`='$posts->person',`category`='$posts->category',`sub_category` = '$posts->sub_category',`details`='$posts->purpose',`description`='$posts->description',`amount`='$posts->amount' WHERE `financeid` = '$financeid'";
			if($ezDb->query($query)){
				$finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$financeid';");
				logEvent($userinfo->email, "finance-updated", $userinfo->usertype, 'finances', $finance);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your transaction has been successfully updated.</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to update.</p></div>';
			}
		}else{
			$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
		}
	}
}
$smarty->assign("record", $finance)->assign("projects", $projects)->assign("accounts", $accounts)->assign("income_accounts", $income_accounts)->assign("expense_accounts", $expense_accounts);

