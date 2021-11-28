<?php //If the page requires authentication and the user is not logged in, go to login page
global $command, $ezDb, $requestMethod, $smarty, $Site;

if( in_array($sitePage,array("login","logout","admin", "login_auth")) ){
	if ($sitePage == "logout") {
		$mPage= ( !empty($Site["session"]["User"]["admin"])? "admin": ( !empty($Site["session"]["User"]["client"])? "dashboard": "login" ) );
		logEvent($Site['session']['User']['userinfo']->email, "logout", $Site['session']['User']['userinfo']->usertype, 'userprofile', array());
		logoutUser($mPage);
	}
    $userName=useIfPosted("dataUsername");
    $password=useIfPosted("dataPass");
	if ( in_array($sitePage,array("login","admin", "login_auth")) ) {
		if( !empty($Site["session"]["User"]["admin"]["Token"]) and sessionExists($Site["session"]["User"]["admin"]["Token"])==true ):
    		if( !empty($Site["session"]["User"]["client"]["Token"]) and sessionExists($Site["session"]["User"]["client"]["Token"])==true ):
				unset($Site["session"]["User"]["admin"]);
				$_SESSION=$Site["session"];
				redirect("dashboard");
			endif;
			redirect("root");
			exit();//newly added
	    elseif( !empty($Site["session"]["User"]["client"]["Token"]) and sessionExists($Site["session"]["User"]["client"]["Token"])==true ):
	    	if( !empty($Site["session"]["User"]["admin"]["Token"]) and sessionExists($Site["session"]["User"]["admin"]["Token"])==true ):
				// @session_destroy();
				unset($Site["session"]["User"]);
				unset($Site["session"]['Site']["Page"]);
				$_SESSION=$Site["session"];
				redirect( "login".( !empty($Site["get"]["e"])? "?e=".$Site["get"]["e"]: "" ) );
			endif;
			redirect("dashboard");
			exit();//newly added
	    endif;
	    $smarty->assign("userName",$userName)->assign("password",$password);
		if ( ($requestMethod=='POST') ) {
			$myLogin = false;
			$err=0;
			$fail='';
		    if(empty($userName)){
		    	$fail.='<p>The username field cannot be empty!</p>';
		    	$err++;
		    }
		    if(empty($password)){
		    	$fail.='<p>The password field cannot be empty!</p>';
		    	$err++;
		    }
		    if($err==0){
				$myLogin = ( loginUser()==true? true: false);
			}else{
				$myLogin=false;
				$smarty->assign("fail",$fail);
			}
			if ($myLogin == true) {
				$redPage= ( !empty($Site["get"]["e"])? base64_decode($Site["get"]["e"]): "".$Site["session"]['Site']["Page"] );
				redirect($redPage);
				exit();
			}else{
				$message->status=0;
				$message->message='failed';
			}
			return $myLogin;
		}
	}
}

/*if( !empty($Site["session"]["User"]["admin"]["Token"]) and sessionExists($Site["session"]["User"]["admin"]["Token"])==true ):
	file_put_contents("site/media/course_materials/.htaccess", "");
elseif( !empty($Site["session"]["User"]["client"]["Token"]) and sessionExists($Site["session"]["User"]["client"]["Token"])==true ):
	file_put_contents("site/media/course_materials/.htaccess", "order deny,allow\ndeny from all");
else:
	file_put_contents("site/media/course_materials/.htaccess", "order deny,allow\ndeny from all");
endif;*/