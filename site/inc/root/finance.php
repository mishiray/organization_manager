<?php 
$id=(!empty($gets->id)? $gets->id: "");
if( !in_array( $userinfo->userrole, array('level0','level1','level6'))  && !in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;
$accounts = $ezDb->get_results("SELECT * FROM `bank_account` WHERE `status` = 1 ORDER BY `bank_name`;");

$finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$id'");

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
	$finance->expenses1=(empty($finance->expenses1)? array(): @json_decode($finance->expenses1));
	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'POST') && isset($Site["post"]['managexpense']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1','level2','level6')) || !in_array( $userinfo->department, array('Administrative','Accounting','Marketing'))  ):
			redirect("finances");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		for($idXX=1; $idXX<11; $idXX++):
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
			if( empty(trim($posts->date[$idXX-1])) && empty(trim($posts->account[$idXX-1])) && empty(trim($posts->payment[$idXX-1])) && empty(trim($posts->person[$idXX-1])) && empty(trim($posts->purpose[$idXX-1])) && empty(trim($posts->credit[$idXX-1])) && empty(trim($posts->debit[$idXX-1])) && empty(trim($posts->balance[$idXX-1])) ):
				unset($posts->date[$idXX-1]);
				unset($posts->account[$idXX-1]);
				unset($posts->payment[$idXX-1]);
				unset($posts->person[$idXX-1]);
				unset($posts->purpose[$idXX-1]);
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
			$finance->expenses1=$posts;

	    	logEvent($userinfo->email, "finance-expense-updated", $userinfo->usertype, 'finances', $finance->expenses1);
			$ezDb->query("UPDATE `finances` SET `expenses1`='".@json_encode($finance->expenses1)."' WHERE `financeid`='$id';");
			/*$finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$id'");
			$finance->expenses=(empty($finance->expenses)? array(): @json_decode($finance->expenses));*/
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully updated.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'POST') && isset($Site["post"]['addexpense']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finances");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		if( empty(trim($posts->contractor)) ):
			$err++;
			$fail.='<p>Enter contractor\'s name please!</p>';
		endif;
		if( empty(trim($posts->amount)) or is_nan($posts->amount) ):
			$err++;
			$fail.='<p>Enter a valid expense amount please!</p>';
		endif;
		if ($err==0) {
			$finex=new stdClass();
			$finex->contractor=$posts->contractor;
			$finex->amount=$posts->amount;
			$finex->date=$dateNow;
			array_push($finance->expenses, $finex); //["1":{"contractor":"Sean Kingston","amount":"500.00","date":"2020-05-18 16:20:03"}]
			// $finance->expenses[(count($finance->expenses)+1)]=((object) array('contractor'=> , 'amount'=> $posts->amount, 'date'=> $dateNow));
		    
			/*if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;*/

	    	logEvent($userinfo->email, "finance-expense-added", $userinfo->usertype, 'finances', $finance->expenses[(count($finance->expenses)-1)]);
			$ezDb->query("UPDATE `finances` SET `expenses`='".@json_encode($finance->expenses)."' WHERE `financeid`='$id';");
			/*$finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$id'");
			$finance->expenses=(empty($finance->expenses)? array(): @json_decode($finance->expenses));*/
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully added.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'POST') && isset($Site["post"]['updexp']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finances");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		if( empty(trim($posts->contractor)) ):
			$err++;
			$fail.='<p>Enter contractor\'s name please!</p>';
		endif;
		if( empty(trim($posts->amount)) or is_nan($posts->amount) ):
			$err++;
			$fail.='<p>Enter a valid expense amount please!</p>';
		endif;
		if( !isset($posts->exkey) or is_nan($posts->exkey) or !array_key_exists($posts->exkey, $finance->expenses) ):
			$err++;
			$fail.='<p>Invalid expense selected: the selected expense does not exists!</p>';
		endif;
		if ($err==0) {
			$finex=new stdClass();
			$finex->contractor=$posts->contractor;
			$finex->amount=$posts->amount;
			$finex->date=$dateNow;
	    	logEvent($userinfo->email, "finance-expense-updated", $userinfo->usertype, 'finances', $finance->expenses[$posts->exkey]);
			$finance->expenses[$posts->exkey]=$finex;

			$ezDb->query("UPDATE `finances` SET `expenses`='".@json_encode($finance->expenses)."' WHERE `financeid`='$id';");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully updated.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'POST') && isset($Site["post"]['delexp']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finances");
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
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully deleted.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'GET') && isset($Site["get"]['evt']) and ( in_array($userinfo->userrole, array('level0','level1')) ) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finances");
		endif;
		if (!empty($finance) and $gets->evt=='delete') {
			if( file_exists($finance->attachment)):
				@unlink($finance->attachment);
			endif;
	    	logEvent($userinfo->email, "finance-deleted", $userinfo->usertype, 'finances', $finance);
			$ezDb->query("DELETE FROM `finances` WHERE `financeid`='$id'");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finance detail was successfull deleted.</p></div>';
		}
	}

	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'POST') && isset($posts->mreview) and ( (in_array($report->status, array('0','1','2')) and $userinfo->userrole=='level1') or $userinfo->userrole=='level0') ) {
		if( !in_array( $userinfo->userrole, array('level0','level1')) ):
			redirect("finances");
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
	    	logEvent($userinfo->email, "finance-reviewed", $userinfo->usertype, 'finances', $revDet);
			$ezDb->query("UPDATE `finances` SET `status`='$posts->status', `md_review`='".@json_encode($revDet)."' WHERE `financeid`='$id'");
			$finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$id'");
			$finance->expenses=(empty($finance->expenses)? array(): @json_decode($finance->expenses));
			$finance->expenses1=(empty($finance->expenses1)? array(): @json_decode($finance->expenses1));
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finance was successfully reviewed.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	if ( in_array($sitePage, array("finance")) && ($requestMethod == 'POST') && isset($Site["post"]['finance']) ) {
		if( !in_array( $userinfo->userrole, array('level0','level1','level6')) ):
			redirect("finances");
		endif;
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		if( empty(trim($posts->title)) ):
			$err++;
			$fail.='<p>Enter finances title please!</p>';
		endif;
		if( empty(trim($posts->description)) ):
			$err++;
			$fail.='<p>Enter finances description please!</p>';
		endif;
		if( empty(trim($ezDb->get_var("SELECT `id` FROM `projects` WHERE `id`='$posts->project';"))) ):
			$err++;
			$fail.='<p>Select a valid project please!</p>';
		endif;
		if( !(in_array($userinfo->userrole, array('level0','level1')) or $userinfo->email==$finance->user)):
			$err++;
			$fail.='<p>You have no authorization to update this finances!</p>';
		endif;
		if( empty(trim($posts->amount)) or is_nan($posts->amount) ):
			$err++;
			$fail.='<p>Enter a valid budget amount please!</p>';
		endif;
		if( !isset($posts->discount) or is_nan($posts->discount) ):
			$err++;
			$fail.='<p>Enter a valid discount please!</p>';
		endif;
		if ($err==0) {
		    $posts->description=testInputln($posts->description);
		    $posts->title=testInputln($posts->title);
			/*if ( !file_exists("$targetDir") ) :
		        mkdir("$targetDir", 0777, true);
		    endif;*/
	    	logEvent($userinfo->email, "finance-edited", $userinfo->usertype, 'finances', $finance);

			$ezDb->query("UPDATE `finances` SET `project`='$posts->project', `title`='$posts->title', `amount`='$posts->amount', `discount`='$posts->discount', `description`='$posts->description' WHERE `financeid`='$id';");
			$finance=$ezDb->get_row("SELECT * FROM `finances` WHERE `financeid`='$id'");
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Finances detail was successfully updated.</p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

	$finance->md_review=(empty($finance->md_review)? new stdClass(): @json_decode($finance->md_review));
	if(!empty($finance->md_review->user)){
		$finance->md=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='".$finance->md_review->user."'");
	}
	$finance->description1=testInputReverseln(trim($finance->description));
	$finance->description=testInputReverse(trim($finance->description));
	$finance->title1=testInputReverseln(trim($finance->title));
	$finance->title=testInputReverse(trim($finance->title));
	$finance->recorder=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$finance->user'");
	$finance->projectinfo=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$finance->project';");

}else{
	redirect('finances');
}
$smarty->assign("projects", $projects)->assign("finance", $finance)->assign("accounts", $accounts);