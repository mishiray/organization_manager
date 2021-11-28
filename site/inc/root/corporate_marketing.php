<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2', 'level3')) ):
	redirect("home");
endif;

$whereClause=(
	$userinfo->userrole=='level3'? "AND `user`='$userinfo->email' AND `status` IN('0','1','3', '2', '4')": (
		$userinfo->userrole=='level2'? "AND `status` IN('0', '1', '3', '4', '2')": (
			$userinfo->userrole=='level1'? "AND `status` IN( '1', '2', '4')": ""
		)
	)
);

//$reports=tableRoutine("corperate_reports", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', 10);
$reports=$ezDb->get_results("SELECT * from `corperate_reports` WHERE `id` != 0 ORDER BY `id` DESC");
if (!empty($reports)) {
	foreach ($reports as $key => $report) {
		$report->lawyer_review=(empty($report->lawyer_review)? new stdClass(): @json_decode($report->lawyer_review));
		$report->md_review=(empty($report->md_review)? new stdClass(): @json_decode($report->md_review));
		$report->marketerDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$report->user'");
		$report->comment1=testInputReverseln(trim($report->comment));
		$report->comment=testInputReverse(trim($report->comment));
		$report->content_stripe=stripeInputReverse($report->comment);

		$report->title1=testInputReverseln(trim($report->title));
		$report->title=testInputReverse(trim($report->title));
		
	}
}
$smarty->assign("reports", $reports);