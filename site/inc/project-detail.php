<?php 

$id=(!empty($gets->id)? $gets->id: "");
$project=$ezDb->get_row("SELECT * FROM `construction_projects` WHERE `token`='$id' AND `show`='1'");
if (!empty($project)) {
	$project->images=@json_decode($project->images);

	$project->description2=testInputReverseln(trim($project->description));
	$project->description=testInputReverse(trim($project->description));
	$project->title2=testInputReverseln(trim($project->title));
	$project->title=testInputReverse(trim($project->title));
	$project->category2=testInputReverseln($project->category);
	$project->category=testInputReverse($project->category);


	if(!empty($posts->contact) and $posts->contact=='contactPerson'):
		if( empty($posts->names)):
			$fail='<p class="border border-danger p-1 rounded">Invalid Name: kindly enter your name</p>';
			$err++;
		endif;
		if( empty($posts->email) or !checkEmail($posts->email)):
			$fail='<p class="border border-danger p-1 rounded">Invalid Email: kindly enter a valid email</p>';
			$err++;
		endif;
		if( empty($posts->phone)):
			$fail='<p class="border border-danger p-1 rounded">Invalid Email: kindly enter your phone number</p>';
			$err++;
		endif;
		if( empty($ezDb->get_var("SELECT `token` FROM `construction_projects` WHERE `token`='$id'"))):
			$fail='<p class="border border-danger p-1 rounded">Invalid Project: cannot identify this project</p>';
			$err++;
		endif;
		if( empty($posts->comment) ):
			$fail='<p>Invalid Message: kindly enter your message</p>';
			$err++;
		endif;
		if($err==0):
			$posts->mailEmail = $project->email;
			require_once 'mail_owner.php';

		else:
			$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
		endif;

	endif;


}else{
	redirect('project');
}
$smarty->assign("project", $project);