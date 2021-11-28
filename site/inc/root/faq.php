<?php 
$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
	redirect("home");
endif;

$whereClause="";


/*Add FaQ*/
if ( in_array($sitePage, array("faq")) && ($requestMethod == 'POST') && isset($Site["post"]['add_faq']) ) {
	if( empty(trim($posts->question)) ):
		$err++;
		$fail.='<p>Enter faq question please!</p>';
	endif;
	if( empty(trim($posts->answer)) ):
		$err++;
		$fail.='<p>Enter faq answer please!</p>';
	endif;
	if ($err==0) {
	    $posts->answer=testInput(nl2br2($posts->answer));
	    $posts->question=testInput(nl2br2($posts->question));
	    $ezDb->query("INSERT INTO `faqs` (`question`, `answer`, `addedby`, `dateadded`) VALUES ('$posts->question', '$posts->answer', '$userinfo->email', '$dateNow')");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New F.A.Q. was successfully added.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}
if ( in_array($sitePage, array("faq")) && ($requestMethod == 'POST') && isset($Site["post"]['edit_faq']) ) {
	if (empty($ezDb->get_var("SELECT `id` FROM `faqs` WHERE `id`='$posts->faq'"))) :
		$err++;
		$fail.='<p>Selected F.A.Q does not exist!</p>';
	endif;
	if( empty(trim($posts->question)) ):
		$err++;
		$fail.='<p>Enter post title please!</p>';
	endif;
	if( empty(trim($posts->answer)) ):
		$err++;
		$fail.='<p>Enter post content please!</p>';
	endif;
	if ($err==0) {
	    $posts->answer=testInput(nl2br2($posts->answer));
	    $posts->question=testInput(nl2br2($posts->question));
		$ezDb->query("UPDATE `faqs` SET `question`='$posts->question', `answer`='$posts->answer' WHERE `id`='$posts->faq';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> F.A.Q. was successfully updated.</p></div>';
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

/*Deleting of FaQ*/
if ( in_array($sitePage, array("faq")) && ($requestMethod == 'GET') && isset($Site["get"]['faq']) && isset($Site["get"]['delete']) && $Site["get"]['delete']=='true') {

	$faqid=(!empty($gets->faq)? $gets->faq: "");
	if ($err==0 and !empty($ezDb->get_var("SELECT `id` FROM `faqs` WHERE `id`='$faqid'"))) {
		$ezDb->query("DELETE FROM `faqs` WHERE `id`='$faqid';");
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>F.A.Q. was successfully deleted.</p></div>';
	}
}

$faqs=tableRoutine("faqs", '*', " `id`!='0' $whereClause", '`id`');
if(!empty($faqs) and is_array($faqs)){
	foreach ($faqs as $value) {
		$value->question1=testInputReverse(str_replace("<br/>", "\n", $value->question));
		$value->answer1=testInputReverse(str_replace("<br/>", "\n", $value->answer));
	}
}
$smarty->assign("faqs", $faqs);