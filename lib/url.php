<?php 
global $getURI, $admin, $dashboard, $templateFolder, $sitePage;

$urIs = explode("/", $getURI);
if( count($urIs)==1 and empty($urIs[0]) ):
	$sitePage = "home";
elseif( count($urIs) >=1 and in_array($urIs[0],array($admin, $dashboard)) ):
	$sitePage = $urIs[0];
	if( empty($urIs[1]) and count($urIs)>1 ):
		redirect("home");
	elseif( empty($urIs[1]) and count($urIs)==1 and in_array($urIs[0],array($admin, $dashboard)) ):
		redirect($urIs[0]."/home");
	endif;
elseif( count($urIs)>=1 and !in_array($urIs[0],array($admin, $dashboard)) ):
	$sitePage = $urIs[0];
else:
	$sitePage = "default";
endif;
$templateFolder= ( ($urIs[0]==$dashboard and count($urIs) >1) ? $dashboard : (($urIs[0]==$admin and count($urIs) >1) ? $admin : 'base') );
// error_log(json_encode($getURI));