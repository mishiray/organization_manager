<?php 
$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0','level1','level6')) ):
	redirect("home");
endif;
$accounts = $ezDb->get_results("SELECT * FROM `bank_account` WHERE `status` = 1 ORDER BY `bank_name`;");

$finance=$ezDb->get_row("SELECT * FROM `finance` WHERE `financeid`='$id'");
//$data_length = count(@json_decode($finance->expenses));
$var = @json_decode($finance->expenses);
$data_length = '';
foreach ($var as $v){
	$data_length =  count($v);
	break;
}

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

if (!empty($finance)) {
	$finance->expenses=(empty($finance->expenses)? array(): @json_decode($finance->expenses));
	if ( in_array($sitePage, array("view_finance")) && ($requestMethod == 'POST') && isset($Site["post"]['managexpense']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1','level2')) || !in_array( $userinfo->department, array('Administrative','Accounting','Marketing'))  ):
			//redirect("finance_reports");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		//print_r($posts);
		$length = count($posts->purpose);
		for($idXX=1; $idXX<$length; $idXX++):
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
			$finance->expenses=$posts;

	    	if($ezDb->query("UPDATE `finance` SET `expenses`='".@json_encode($finance->expenses)."' WHERE `financeid`='$id';")){
				logEvent($userinfo->email, "finance-expense-updated", $userinfo->usertype, 'finance', $finance->expenses);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully updated.</p></div>';
		
			$finance=$ezDb->get_row("SELECT * FROM `finance` WHERE `financeid`='$id'");
			$var = @json_decode($finance->expenses);
			$data_length = '';
			foreach ($var as $v){
				$data_length =  count($v);
				break;
			}	
			$finance->expenses=(empty($finance->expenses)? array(): @json_decode($finance->expenses));
		}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to Update.</p></div>';
			}
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}


	if ( in_array($sitePage, array("view_finance")) && ($requestMethod == 'POST') && isset($Site["post"]['delexp']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finance_reports");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		if( !isset($posts->exkey) or is_nan($posts->exkey) or !array_key_exists($posts->exkey, $finance->expenses) ):
			$err++;
			$fail.='<p>Invalid expense selected: the selected expense does not exists!</p>';
		endif;
		if ($err==0) {
	    	logEvent($userinfo->email, "finance-expense-deleted", $userinfo->usertype, 'finances', $finance->expenses[$posts->exkey]);
			unset($finance->expenses[$posts->exkey]);
			$emptyArr=array();
			if (!empty($finance->expenses) and is_array($finance->expenses)) :
				foreach($finance->expenses as $expense):
					array_push($emptyArr, $expense);
				endforeach;
			endif;
			$finance->expenses=$emptyArr;

			$ezDb->query("UPDATE `finances` SET `expenses`='".@json_encode($finance->expenses)."' WHERE `financeid`='$id';");
			alertMd(0,"Finance Report has been updated","view_finance");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully deleted.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("view_finance")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( in_array($userinfo->userrole, array('level0','level1')) ) ) {
		if(!in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finance_reports");
		endif;
		if (!empty($finance) and $gets->evt=='delete') {
			if( file_exists($finance->attachment)):
				@unlink($finance->attachment);
			endif;
			$ezDb->query("DELETE FROM `finance` WHERE `financeid`='$id'");
	    	logEvent($userinfo->email, "finance-deleted", $userinfo->usertype, 'finance', $finance);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finance detail was successfull deleted.</p></div>';
		}
	}

	if ( in_array($sitePage, array("view_finance")) && ($requestMethod == 'POST') && isset($posts->mreview) and ( (in_array($report->status, array('0','1','2')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0') ) {
		if( !in_array($userinfo->userrole, array('level0','level1')) ):
			redirect("finance_reports");
		endif;
		if( empty(trim($posts->comment)) ):
			$err++;
			$fail.='<p>Your comment is required please!</p>';
		endif;

		if( !in_array($posts->status, array('0', '1', '2')) ):
			$err++;
			$fail.='<p>Kindly Choose a valid status!</p>';
		endif;

		if ($err==0) {
			$revDet=new stdClass();
			$revDet->user=$userinfo->email;
			$revDet->comment=$posts->comment;
	    	logEvent($userinfo->email, "finance-reviewed", $userinfo->usertype, 'finance', $revDet);
			$ezDb->query("UPDATE `finance` SET `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `financeid`='$id'");
			$finance=$ezDb->get_row("SELECT * FROM `finance` WHERE `financeid`='$id'");
			$var = @json_decode($finance->expenses);
			$data_length = '';
			foreach ($var as $v){
				$data_length =  count($v);
				break;
			}	
			$finance->expenses=(empty($finance->expenses)? array(): @json_decode($finance->expenses));
			alertUser($finance->user,1,"Finance has been reviewed by MD");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finance was successfully reviewed.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$finance->md_review=(empty($finance->md_review)? new stdClass(): @json_decode($finance->md_review));
	if(!empty($finance->md_review->user)){
		$finance->md=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$finance->md_review->user."'");
	}
	$finance->recorder=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$finance->user'");

}else{
	redirect('finance_reports');
}
$smarty->assign("projects", $projects)->assign("finance", $finance)->assign("accounts", $accounts)->assign("data_length", $data_length);