<?php global $urIs, $Site, $admin, $sitePage;

if( !empty($Site["session"]["User"]["admin"]["Token"]) and sessionExists($Site["session"]["User"]["admin"]["Token"])==true ):
	if( !empty($Site["session"]["User"]["client"]["Token"]) and sessionExists($Site["session"]["User"]["client"]["Token"])==true ):
	// error_log($sitePage."-1");
		unset($Site["session"]["User"]);
		unset($Site["session"]['Site']["Page"]);
		$_SESSION=$Site["session"];
		redirect( $Site['siteProtocol'].$Site['domainName']."/admin?e=".base64_encode( ("".$Site['siteProtocol'].$Site['domainName']."/".$Site['getURI'])."") );
	endif;
	if( count($urIs)>1 ):
		if($urIs[1]=="logout"):
			$sitePage=$urIs[1];
			redirect("../logout");
		endif;
		$user = $Site["session"]["User"]["Username"];
		$Site["session"]["User"]["userinfo"] = userInfo($user);
		/*Restrict Un authorized Admin*/
		if(!in_array($Site["session"]["User"]["userinfo"]->userrole, array("level0","level1","level2","level3", "level4"))):
			unset($Site["session"]["User"]);
			unset($Site["session"]['Site']["Page"]);
			$_SESSION=$Site["session"];
			redirect( $Site['siteProtocol'].$Site['domainName']."/admin?e=".base64_encode( ("".$Site['siteProtocol'].$Site['domainName']."/".$Site['getURI'])."") );
		endif;
		$posts= (object)$Site["post"];
		$gets= (object)$Site["get"];
		$servers=(object)$Site["server"];
		$sessions= (object)$Site["session"];
		$files=(object)$Site["files"];
		$err=0;
		$fail='';

		$smarty->assign("userinfo", $Site["session"]["User"]["userinfo"]);
		$dashStrPath="";

		// @file_put_contents("site/media/course_materials/.htaccess", "");//control with user privilege
		
		$lastPage=$urIs[count($urIs)-1];
		require_once"$admin/pre.php";
		for($i=1; $i < count($urIs); $i++):
			$dashStrPath.=("/".$urIs[$i]);
			if( !empty($urIs) and count($urIs) >= ($i+1) and file_exists(__DIR__."/$admin".$dashStrPath.".php") ):
				$sitePage=$urIs[$i];
				require_once "$admin/".$dashStrPath.".php";
			endif;
		endfor;
		$smarty->assign("part",(!empty($gets->p)?$gets->p:""))->assign('fail',$fail)->assign("posts",$posts);
		require_once"$admin/default.php";
	else:
		redirect("home");
	endif;
else:
    redirect( $Site['siteProtocol'].$Site['domainName']."/admin?e=".base64_encode( ("".$Site['siteProtocol'].$Site['domainName']."/".$Site['getURI']) ) );
endif;