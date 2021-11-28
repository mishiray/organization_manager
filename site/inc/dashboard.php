<?php global $urIs, $Site, $dashboard, $sitePage;
if( !empty($Site["session"]["User"]["client"]["Token"]) and sessionExists($Site["session"]["User"]["client"]["Token"])==true ):
	if( !empty($Site["session"]["User"]["admin"]["Token"]) and sessionExists($Site["session"]["User"]["admin"]["Token"])==true ):
		unset($Site["session"]["User"]);
		unset($Site["session"]['Site']["Page"]);
		$_SESSION=$Site["session"];
		redirect( $Site['siteProtocol'].$Site['domainName']."/login?e=".base64_encode( ("".$Site['siteProtocol'].$Site['domainName']."/".$Site['getURI'])."") );
	endif;
	if( count($urIs)>1 ):
		if($urIs[1]=="logout"):
			$sitePage=$urIs[1];
			redirect("../logout");
		endif;
		$user = $Site["session"]["User"]["Username"];
		$Site["session"]["User"]["userinfo"] = userInfo($user);
		/*Restrict Un authorized Admin*/
		if(!in_array($Site["session"]["User"]["userinfo"]->userrole, array("level0","level1", "level2"))):
			unset($Site["session"]["User"]);
			unset($Site["session"]['Site']["Page"]);
			$_SESSION=$Site["session"];
			redirect( $Site['siteProtocol'].$Site['domainName']."/login?e=".base64_encode( ("".$Site['siteProtocol'].$Site['domainName']."/".$Site['getURI'])."") );
		endif;
		$posts= (object)$Site["post"];
		$gets= (object)$Site["get"];
		$servers=(object)$Site["server"];
		$sessions= (object)$Site["session"];
		$files=(object)$Site["files"];
		$err=0;
		$fail='';
		// $Site["session"]['Site']["User"]["userInfo"]->initials= ucfirst( substr($Site["session"]['Site']["User"]["userInfo"]->firstName, 0,1) ) ."". ucfirst( substr($Site["session"]['Site']["User"]["userInfo"]->lastName, 0,1) );
		$dashStrPath="";

		$lastPage=$urIs[count($urIs)-1];
		require_once"$dashboard/pre.php";
		for($i=1; $i < count($urIs); $i++):
			$dashStrPath.=("/".$urIs[$i]);
			if( !empty($urIs) and count($urIs) >= ($i+1) and file_exists(__DIR__."/$dashboard".$dashStrPath.".php") ):
				$sitePage=$urIs[$i];
				require_once "$dashboard/".$dashStrPath.".php";
			endif;
		endfor;
		require_once"$dashboard/default.php";
		$smarty->assign("fail", $fail)->assign("err", $err)->assign("part",(!empty($gets->p)?$gets->p:""))->assign("posts",$posts);
	else:
		redirect("home");
	endif;
else:
	redirect( $Site['siteProtocol'].$Site['domainName']."/login?e=".base64_encode( ("".$Site['siteProtocol'].$Site['domainName']."/".$Site['getURI']) ) );
endif;