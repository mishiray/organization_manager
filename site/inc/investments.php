<?php

$query = "SELECT DISTINCT `percentage` FROM `investments`";
$invest_per = $ezDb->get_col($query);

$investment = $ezDb->get_row("SELECT * FROM `webpages` WHERE `title_page`= 'investment' AND `status` = 1");

if(!empty($investment)){

	$investment->contentln=testinputReverse($investment->content);
	$investment->content=testinputReverse($investment->content);
	$investment->content_stripe=stripeInputReverse($investment->contentln);
	$investment->content_stripe=str_replace("&quot;", "\"", $investment->content_stripe);

}

$smarty->assign("investment",$investment);
$smarty->assign("invest_per", $invest_per);