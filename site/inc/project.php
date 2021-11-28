<?php 
$whereClause=" AND `set_featured`!='1' AND `show`='1'";
$projects=tableRoutine("construction_projects", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', 10);
if (!empty($projects)) {
	foreach ($projects as $key => $project) {
		$project->images=@json_decode($project->images);
		// $store->owner_detail=@json_decode($store->owner_detail);
		// $store->profile=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$store->store_owner';");
		$project->description2=testInputReverseln(trim($project->description));
		$project->description=testInputReverse(trim($project->description));
		$project->description = strlen($project->description) > 200 ? substr($project->description,0,200)."..." : $project->description;
		$project->title2=testInputReverseln(trim($project->title));
		$project->title=testInputReverse(trim($project->title));
		$project->category2=testInputReverseln($project->category);
		$project->category=testInputReverse($project->category);
		
		/*$store->rate=$ezDb->get_var("SELECT SUM(`rating`)/COUNT(`rating`) FROM `rating` WHERE `token`='$store->token' AND `type`='store'");
		$store->rate=(empty($store->rate)? 0: $store->rate);
		$store->rateCeil=(count(explode(".", $store->rate))>1.0? explode(".", $store->rate)[0]: $store->rate);
		$store->rateFloor=(round(explode(".", $store->rate))>=1? 1 :0);
		$store->rateRem=5-$store->rateCeil;
		$store->rateDetails=$ezDb->get_results("SELECT `comment`, `email` FROM `rating` WHERE `token`='$store->token' AND `type`='store' AND `comment`!=''");*/
	}
}
$featured=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `set_featured`='1'");

$featured->images=@json_decode($featured->images);
$featured->description2=testInputReverseln(trim($featured->description));
$featured->description=testInputReverse(trim($featured->description));
$featured->title2=testInputReverseln(trim($featured->title));
$featured->title=testInputReverse(trim($featured->title));
$featured->category2=testInputReverseln($featured->category);
$featured->category=testInputReverse($featured->category);
/*Do foreach and get its list of packages it belong*/
$smarty->assign("projects", $projects)->assign("featured", $featured);