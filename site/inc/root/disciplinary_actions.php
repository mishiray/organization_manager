<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1'))  && !in_array($userinfo->department, array('Human Resources'))):
	redirect("home");
endif;

$depId=(!empty($gets->id)? $gets->id: "");

if (!empty($posts->triggers) and $posts->triggers=='delete_action' and !empty($depId)) {
	$ezDb->query("DELETE FROM `disciplinary_action` WHERE `id`='$depId';");
	$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Action type was successfully deleted.</p></div>';
}

$whereClause="";

$actions=$ezDb->get_results("SELECT * from `disciplinary_action`");

$smarty->assign("actions", $actions);