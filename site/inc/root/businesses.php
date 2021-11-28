<?php 

if ( in_array($sitePage, array("businesses")) && ($requestMethod == 'GET') && !empty($gets->id)) {
	$currbusiness=$ezDb->get_row("SELECT * FROM `business` WHERE `token`='$gets->id';");
	if (!empty($currbusiness)){
		if (file_exists($currbusiness->image) && is_file($currbusiness->image)){
			@unlink($currbusiness->image);
		}
		$ezDb->query("DELETE FROM `business` WHERE `token`='$gets->id';");
		$fail="<p>Business <b>".testInputReverse($currbusiness->business_name)."</b> was successfully deteted</p>";
		$fail='<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}


$whereClause="";
$businesses=tableRoutine("business", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', '8');

$smarty->assign("businesses", $businesses);