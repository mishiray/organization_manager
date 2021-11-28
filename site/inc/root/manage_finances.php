<?php 
$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0','level1','level6'))   && !in_array($userinfo->department, array('Accounting'))):
	//redirect("home");
endif;
$accounts = $ezDb->get_results("SELECT * FROM `bank_account` WHERE `status` = 1 ORDER BY `bank_name`;");
$budget_amount = 0;
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

if ( in_array($sitePage, array("manage_finances")) && ($requestMethod == 'POST') && isset($Site["post"]['budget_value']) ) {
	$err=0;
	$posts = (object) $Site["post"];
	$finance = new stdClass();
	if(empty($posts->finance_budget)){
		$posts->finance_budget = 100000;
		$finance->budget = $posts->finance_budget;
	}else{
		$budget_amount = $posts->finance_budget;
		$finance->budget = $posts->finance_budget;
	}
	//echo $finance->budget;
	echo "<script>
			const budget=Number('$finance->budget');
		 </script>
		";
	$smarty->assign("finance", $finance);
}

if ( in_array($sitePage, array("manage_finances")) && ($requestMethod == 'POST') && isset($Site["post"]['managexpense']) ) {
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$length = count($posts->purpose);
	for($idXX=1; $idXX < $length; $idXX++):
		if($idXX==1):
			if ( empty(trim($posts->date[$idXX-1])) ):
				$fail.='<p class="border border-danger p-1 rounded">Date '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( empty(trim($posts->account[$idXX-1])) ):
				$fail.='<p class="border border-danger p-1 rounded">Account '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( empty(trim($posts->payment[$idXX-1])) ):
				$fail.='<p class="border border-danger p-1 rounded">Payment Type '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( empty(trim($posts->person[$idXX-1])) ):
				$fail.='<p class="border border-danger p-1 rounded">Person '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( empty(trim($posts->purpose[$idXX-1])) ):
				$fail.='<p class="border border-danger p-1 rounded">Purpose '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( empty(trim($posts->description[$idXX-1])) ):
				$fail.='<p class="border border-danger p-1 rounded">Description '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( !isset($posts->credit[$idXX-1]) or is_nan($posts->credit[$idXX-1])):
				$fail.='<p class="border border-danger p-1 rounded">Credit '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( !isset($posts->debit[$idXX-1]) or is_nan($posts->debit[$idXX-1])):
				$fail.='<p class="border border-danger p-1 rounded">Debit '.$idXX.' is required</p>';
				$err++;
			endif;
			if ( !isset($posts->balance[$idXX-1]) or is_nan($posts->balance[$idXX-1])):
				$fail.='<p class="border border-danger p-1 rounded">Balance '.$idXX.' is required</p>';
				$err++;
			endif;
		endif;
		if( empty(trim($posts->date[$idXX-1])) && empty(trim($posts->account[$idXX-1])) && empty(trim($posts->payment[$idXX-1])) && empty(trim($posts->person[$idXX-1])) && empty(trim($posts->purpose[$idXX-1])) && empty(trim($posts->description[$idXX-1])) && empty(trim($posts->credit[$idXX-1])) && empty(trim($posts->debit[$idXX-1])) && empty(trim($posts->balance[$idXX-1])) ):
			unset($posts->date[$idXX-1]);
			unset($posts->account[$idXX-1]);
			unset($posts->payment[$idXX-1]);
			unset($posts->person[$idXX-1]);
			unset($posts->purpose[$idXX-1]);
			unset($posts->description[$idXX-1]);
			unset($posts->credit[$idXX-1]);
			unset($posts->debit[$idXX-1]);
			unset($posts->balance[$idXX-1]);
		endif;
	endfor;
	if( !isset($posts->credittotal) or is_nan($posts->credittotal) ):
		$err++;
		$fail.='<p>Enter a valid credit total please!</p>';
	endif;
	if( !isset($posts->debittotal) or is_nan($posts->debittotal) ):
		$err++;
		$fail.='<p>Enter a valid debit total please!</p>';
	endif;
	if( !isset($posts->grandtotal) or is_nan($posts->grandtotal) ):
		$err++;
		$fail.='<p>Enter a valid balance total please!</p>';
	endif;
	if ($err==0) {
		for($idXX=1; $idXX<(count($posts->date)+1); $idXX++):
			$posts->person[$idXX-1]=testInputln($posts->person[$idXX-1]);
			$posts->purpose[$idXX-1]=testInputln($posts->purpose[$idXX-1]);
		endfor;
		unset($posts->managexpense);
		$finance_data=$posts;
		$finance_data= json_encode($finance_data);
		$token = getToken(8);
		$query = "INSERT INTO `finance` (`financeid`,`budget`, `expenses`, `user`, `dateadded`, `status`, `md_review`) VALUES ('$token','$budget_amount','$finance_data','$userinfo->email','$dateNow','0','".@json_encode($emptyArray)."')	";
		
		if($ezDb->query($query)){
			alertMd(0,"New Finance Report has been added");
			logEvent($userinfo->email, "finance-expense-added", $userinfo->usertype, 'finance', $finance_data);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully added.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to add finances detail </p></div>';
	
		}

	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}



$smarty->assign("projects", $projects)->assign("accounts", $accounts);