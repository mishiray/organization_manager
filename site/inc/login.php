<?php global $sitePage;
$posts= (object)$Site["post"];
$servers=(object)$Site["server"];
$cons = (!empty($gets->c) ? $gets->c : 'false');

$smarty->assign("c",$cons);

if($sitePage=='login'):
	// redirect("home");
endif;