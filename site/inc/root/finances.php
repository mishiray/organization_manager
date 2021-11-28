<?php 
if( !in_array( $userinfo->userrole, array('level0','level1','level6'))  && !in_array($userinfo->department, array('Accounting'))):
	redirect("home");
endif;
$whereClause="";
$finances=tableRoutine("finances", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', 10);
if (!empty($finances)) {
	foreach ($finances as $key => $finance) {
		$finance->description2=testInputReverseln(trim($finance->description));
		$finance->description=testInputReverse(trim($finance->description));
		$finance->title2=testInputReverseln(trim($finance->title));
		$finance->title=testInputReverse(trim($finance->title));
		$finance->recorder=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$finance->user'");
		$finance->projectinfo=$ezDb->get_row("SELECT * FROM `projects` WHERE `token`='$finance->project';");

	}
}
/*Do foreach and get its list of packages it belong*/
$smarty->assign("finances", $finances);