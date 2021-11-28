<?php 

if ( in_array($sitePage, array("donations")) && ($requestMethod == 'GET') && !empty($gets->id)) {
	$currdonation=$ezDb->get_row("SELECT * FROM `donation` WHERE `token`='$gets->id';");
	if (!empty($currdonation)){
		$ezDb->query("DELETE FROM `donation` WHERE `token`='$gets->id';");
		$fail="<p>Business <b>".testInputReverse($currdonation->business_name)."</b> was successfully deteted</p>";
		$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}

$whereClause="";
$donations=tableRoutine("donation", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');
$smarty->assign("donations", $donations);