<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1','level2')) ):
	redirect("home");
endif;

if (in_array($sitePage, array("branches")) && ($requestMethod == 'POST') && $posts->triggers=='update_branch') {
	$fail="";
	$err=0;
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter name please!</p>';
	endif;
	if( !empty($posts->name) and !empty($ezDb->get_var("SELECT `zone` FROM `id_zone` WHERE `zone`='$posts->name' AND `id` != '$posts->id';"))):
		$fail.='<p>Invalid branch Name: Branch already exists</p>';
		$err++;
	endif;
	if(empty($posts->address)):
		$err++;
		$fail.='<p>Please enter address</p>';
	endif;
	if($err == 0){
		
		$posts->address = cleanUp($posts->address);
		$query = "UPDATE `id_zone` SET `zone` = '$posts->name',`address` = '$posts->address' WHERE `id` = '$posts->id' ";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Branch Updated </p></div>';
		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Unable to update.</p></div>';
		}
	}else{
		$fail = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p> Error: '.$fail.'</p></div>';
	}
}

$depId=(!empty($gets->id)? $gets->id: "");

if (!empty($posts->triggers) and $posts->triggers=='delete_branch' and !empty($depId)) {
	$ezDb->query("DELETE FROM `id_zone` WHERE `id`='$depId';");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Branch was successfully deleted.</p></div>';
}

$whereClause="";

$branches=$ezDb->get_results("SELECT * from `id_zone`");
if(!empty($branches)){
	foreach($branches as $value){
		$value->emp = $ezDb->get_var("SELECT COUNT(`email`) from `employees` WHERE `location` = '$value->zone'");
	}
}

$smarty->assign("branches", $branches);