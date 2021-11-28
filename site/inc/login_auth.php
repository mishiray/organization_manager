<?php 
$message=new stdClass;
$message->status=0;
$message->message='failed';
if (!empty($posts) and !empty($posts->sitePage)) {
	if ($posts->api=='facebook'){
		$access_token = $posts->access_token;
		error_log($posts->access_token);
		$graph_url = 'https://graph.facebook.com/'.$posts->userID.'?fields=first_name,last_name,profile_pic,gender&access_token=' . $access_token;
		$curl = curl_init();
		curl_setopt_array($curl, array(
          CURLOPT_URL => $graph_url,
          CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
		// $graph_url = 'https://graph.facebook.com/oauth/access_token_info?client_id=204777407241205&amp;access_token=' . $access_token;
		// $access_token_info = json_decode(file_get_contents($graph_url));
		$access_token_info = json_decode($response);
		$posts->email="";
		$message->accessDetails=$access_token_info;
		error_log(json_encode($access_token_info));
		function nonceHasBeenUsed($auth_nonce) {
		  return false;
		}

		if (nonceHasBeenUsed($access_token_info->auth_nonce) != true) {
		  $message->message='failed';
		} else {
		  $message->status=1;
		  $message->message='success';
		}
	}

	$userDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$posts->email'");

	
	if (empty($userDetail)) {
		$pword=getToken(10);
		$ezDb->query("INSERT INTO `userprofile` (`firstname`, `lastname`, `email`, `password`, `username`, `terms`, `active`, `dateadded`, `usertype`, `usercat`, `userrole`, `verified`, `instructor`) VALUES ('$posts->firstname', '$posts->lastname', '".strtolower($posts->email)."', '".base64_encode($pword)."', '".strtolower($posts->email)."', '1', '1', '$dateNow', 'client', 'individual', 'level', '1', '0');");

		if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posts->email'"))) {
			$ezDb->query("UPDATE `newsletter` SET `status`='0', `dateupdated`='$dateNow' WHERE `email`='$posts->email'");
		}else{
			$ezDb->query("INSERT INTO `newsletter` (`status`, `dateupdated`, `email`) VALUES ('0', '$dateNow', '$posts->email');");
		}
		$userDetail=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `email`='$posts->email'");
	}
	$_POST['dataUsername']=$posts->email;
	$_POST['dataPass']=base64_decode($userDetail->password);
	$Site["post"]=$_POST;
	$posts= (object)$Site["post"];
	/*if (file_exists("$libraries/authenticate.php")) {
		require_once "$libraries/authenticate.php";
	}*/
	if ( ($requestMethod=='POST' && !empty($userDetail) and is_object($userDetail)) ) {
	    $userName=$userDetail->email;
	    $password=$userDetail->password;
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
			$message->status=1;
			$message->message='success';
			$message->redirect_url="$siteProtocol$domainName/".$redPage;
		}else{
			$message->error=$fail;
		}
	}
	//[29-Jan-2020 16:09:20 Africa/Lagos] {"firstname":"Abdullaah","lastname":"Majoyeogbe","email":"amajoyeogbe.hofftech@gmail.com","imageurl":"https:\/\/lh4.googleusercontent.com\/-9jyCE1a8vWU\/AAAAAAAAAAI\/AAAAAAAAAAA\/ACHi3rfZZAwDLQ5NV9AGsJsgtgHzyTielg\/s96-c\/photo.jpg","sitePage":"signup"}
}
/*error_log(json_encode($posts));*/
echo json_encode($message);
exit;