<?php global $sitePage, $Site, $ezDb, $siteName, $smarty;

$whereClause="";

$sports=tableRoutine("sports", '*', " `id`!='0' $whereClause", '`id`');
if(!empty($sports) and is_array($sports)){
	foreach ($sports as $value) {
		$value->students=$ezDb->get_results("SELECT DISTINCT * FROM `students` WHERE `sport` LIKE'%\"$value->sportid\"';");
		// $value->categ=$ezDb->get_var("SELECT `title` FROM `categories` WHERE `slug`='$value->category'");
		$value->description=testInputReverse($value->description);
		$value->description1=br2nl2($value->description);
		// $value->applicantDetails=$ezDb->get_results("SELECT `comment`, `email` FROM `applicants` WHERE `token`='$value->token'");
	}
}
$smarty->assign("sports", $sports)->assign('categs',$categs);