<?php 

/*	id	token	title	email	amount	url	category	description	otherdetails	addedby	dateadded	show	images*/

if( !in_array( $userinfo->userrole, array('level0','level1','level5','level2', 'level6')) ):
	redirect("home");
endif;

$fail="";
$err=0;
if ( in_array($sitePage, array("projects")) && ($requestMethod == 'GET') && !empty($gets->evt)  && !empty($gets->id) ) {
	$project=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `token`='$gets->id'");
		$project->images=@json_decode($Store->images);
		// $Store->owner_detail=@json_decode($Store->owner_detail);

	if( !in_array( $userinfo->userrole, array('level0','level1')) ):
		redirect("projects");
	endif;

	if( !empty($project) and in_array($gets->evt, array('show', 'hide', 'delete', 'feature')) ){
		$evtMsg=($gets->evt=='show'?'showed': ($gets->evt=='hide'?'hidden': ($gets->evt=='feature'?'featured as ongoing project':'deleted')));
		if($gets->evt=='show' and $project->show=='1'):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">This project had already been showed click projects in the menu to refresh</p>';
		endif;
		if($gets->evt=='hide' and $project->show=='0'):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">This project had already been hidden click projects in the menu to refresh</p>';
		endif;
		if($gets->evt=='feature' and $project->set_featured=='1'):
			$err++;
			$fail.='<p class="border border-danger p-1 rounded">This project had already been featured as ongoing project click projects in the menu to refresh</p>';
		endif;
		if($err==0):
			$sqlQ="";

			if($gets->evt=='show'):
				$project->show=1;
				$sqlQ="UPDATE `construction_projects` SET `show`='1' WHERE `token`='$gets->id'";
				$eventLog='status-showed';
			elseif($gets->evt=='hide'):
				$project->show=0;
				$sqlQ="UPDATE `construction_projects` SET `show`='0' WHERE `token`='$gets->id'";
				$eventLog='status-hidden';
			elseif($gets->evt=='feature'):
				$sqlQ="UPDATE `construction_projects` SET `set_featured`='0'";
				$ezDb->query($sqlQ);
				$sqlQ="UPDATE `construction_projects` SET `set_featured`='1' WHERE `token`='$gets->id'";
				$project->set_featured=1;
				$eventLog='status-set-as-featured';
			elseif($gets->evt=='delete'):
				$sqlQ="DELETE FROM `construction_projects` WHERE `token`='$gets->id'";
				if (!empty($project->images) and is_array($project->images)) :
					foreach ($project->images as $value):
						if (file_exists($value)):
							@unlink($value);
						endif;
					endforeach;
				endif;
				$eventLog='deleted';
			endif;
      		logEvent($userinfo->email, "project-$eventLog", $userinfo->usertype, 'projects', $project);
			$ezDb->query($sqlQ);
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border border-success p-1 rounded">project was successfully '.$evtMsg.'.</p></div>';
		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		endif;
	}
}
$whereClause = '';
$projects=tableRoutine("construction_projects", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', 10);
if (!empty($projects)) {
	foreach ($projects as $key => $project) {
		$project->images=@json_decode($project->images);
		// $store->owner_detail=@json_decode($store->owner_detail);
		// $store->profile=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$store->store_owner';");
		$project->description2=testInputReverseln(trim($project->description));
		$project->description=testInputReverse(trim($project->description));
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
/*Do foreach and get its list of packages it belong*/
$smarty->assign("projects", $projects);