<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2','level3','level4')) ):
	redirect("home");
endif;

if ( in_array($sitePage, array("upcoming_birthday")) && ($requestMethod == 'POST') && isset($Site["post"]['send_email']) ){
		$posts = (object) $Site["post"];
		//print_r($posts);
		if (!empty($posts->email) && !empty($posts->name)) {
		    
		    require_once('mail_birthday.php');
			//$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Email was successfully sent.</p></div>';
		 
		}else{
		    	$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Email was not sent.</p></div>';
		 
    	}
}

$birthmonth = $ezDb->get_results("SELECT * from `subscription` WHERE `dob` <> '' AND MONTH(STR_TO_DATE(dob, '%Y-%m-%d')) = MONTH(NOW()) GROUP BY email ORDER BY DATE_FORMAT(dob,'%e') DESC");
if(!empty($birthmonth)){

	foreach($birthmonth as $value){
		$value->format = $ezDb->get_var("SELECT DATE_FORMAT('$value->dob','%W %D %M %Y')");
	}
	
}
$now = $ezDb->get_var("SELECT DATE_FORMAT(NOW(),'%W %D %M %Y')");

$smarty->assign("birthmonths", $birthmonth)->assign("now", $now);

